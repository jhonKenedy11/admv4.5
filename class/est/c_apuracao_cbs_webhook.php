<?php
/**
 * @package   adm4.5
 * @name      c_apuracao_cbs_webhook
 * @version   4.5.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Auto
 * @date      20/07/2026
 *
 * Receptor do webhook (urlRetorno) da Apuração Assistida IBS/CBS.
 *
 * Fluxo oficial (assíncrono): solicitar consulta -> a Receita entrega o
 * TÍQUETE DE DOWNLOAD nesta rota -> o sistema faz o GET de download.
 * Portanto o formato esperado neste webhook é apenas o tíquete. O suporte a
 * receber o JSON completo de débitos (payloadTemDebitos) é um FALLBACK
 * tolerante e não corresponde ao layout documentado.
 *
 * Esta classe NÃO depende de sessão: é chamada pelo endpoint público
 * compartilhado na raiz da versão (admv4.5/webhook_apuracao_cbs.php?cliente=...).
 */

$dir = dirname(__FILE__);
include_once($dir . '/../../bib/c_database_pdo.php');
include_once($dir . '/c_apuracao_cbs_repository.php');
include_once($dir . '/c_apuracao_cbs.php');
include_once($dir . '/c_apuracao_cbs_log.php');

class c_apuracao_cbs_webhook
{
    /** @var c_apuracao_cbs_repository */
    protected $repository;

    public function __construct()
    {
        $this->repository = new c_apuracao_cbs_repository();
    }

    /**
     * Processa uma requisição de webhook.
     *
     * @param string $rawPayload Corpo bruto do POST (JSON)
     * @param string $segredoInformado Segredo recebido (header/query)
     * @param array  $headers Cabeçalhos recebidos (para auditoria)
     * @param string $origemIp IP de origem
     * @return array ['http_code' => int, 'ok' => bool, 'msg' => string]
     */
    public function processar($rawPayload, $segredoInformado = '', array $headers = [], $origemIp = '')
    {
        $resultado = $this->processarInterno($rawPayload, $segredoInformado, $headers, $origemIp);

        // Log em arquivo do recebimento (para reprocessar/simular depois).
        c_apuracao_cbs_log::registrar('webhook', [
            'request' => [
                'origem_ip' => $origemIp,
                'headers' => $headers,
                'payload' => $rawPayload,
            ],
            'response' => $resultado,
        ]);

        return $resultado;
    }

    /**
     * Processamento efetivo do webhook (sem a camada de log em arquivo).
     *
     * @return array ['http_code' => int, 'ok' => bool, 'msg' => string]
     */
    protected function processarInterno($rawPayload, $segredoInformado = '', array $headers = [], $origemIp = '')
    {
        $logId = $this->repository->insertWebhookLog([
            'origem_ip' => substr((string) $origemIp, 0, 45),
            'headers' => json_encode($headers, JSON_UNESCAPED_UNICODE),
            'payload' => (string) $rawPayload,
            'processado' => 'N',
        ]);

        $json = json_decode((string) $rawPayload, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
            $this->repository->updateWebhookLog($logId, 'N', 'Payload inválido (JSON).');
            return ['http_code' => 400, 'ok' => false, 'msg' => 'Payload inválido.'];
        }

        $cnpj = $this->extrairCnpj($json);
        $cnpjBase = $cnpj !== '' ? substr(preg_replace('/[^A-Z0-9]/', '', strtoupper($cnpj)), 0, 8) : '';
        $tiquete = $this->extrairTiquete($json);

        if ($cnpjBase === '') {
            $this->repository->updateWebhookLog($logId, 'N', 'CNPJ não identificado no payload.', null, $tiquete);
            return ['http_code' => 422, 'ok' => false, 'msg' => 'CNPJ não identificado.'];
        }

        $cred = $this->repository->getCredencialPorCnpj($cnpjBase);
        if (!$cred) {
            $this->repository->updateWebhookLog($logId, 'N', 'CNPJ sem credencial cadastrada.', $cnpjBase, $tiquete);
            return ['http_code' => 404, 'ok' => false, 'msg' => 'Credencial não encontrada.'];
        }

        // Validação do segredo (quando configurado). Compara de forma segura.
        $segredoEsperado = (string) ($cred['WEBHOOK_SECRET'] ?? '');
        if ($segredoEsperado !== '' && !hash_equals($segredoEsperado, (string) $segredoInformado)) {
            $this->repository->updateWebhookLog($logId, 'N', 'Segredo do webhook inválido.', $cnpjBase, $tiquete);
            return ['http_code' => 401, 'ok' => false, 'msg' => 'Não autorizado.'];
        }

        $hist = $this->repository->getHistoricoAguardandoPorCnpj($cnpjBase, $tiquete);
        if (!$hist) {
            $this->repository->updateWebhookLog($logId, 'N', 'Nenhuma solicitação aguardando retorno para o CNPJ.', $cnpjBase, $tiquete);
            return ['http_code' => 202, 'ok' => false, 'msg' => 'Sem solicitação pendente para o CNPJ.'];
        }

        $idHist = (int) $hist['ID'];

        // O payload já traz o JSON de débitos? Persiste direto e marca BAIXADO.
        if ($this->payloadTemDebitos($json)) {
            try {
                $apuracao = new c_apuracao_cbs();
                $resultado = $apuracao->persistirDebitos($idHist, $json);
                if ($tiquete !== '') {
                    $this->repository->updateTiqueteDownload($idHist, $tiquete, 'Tíquete recebido pelo webhook.');
                }
                $this->repository->updateHistoricoBaixado(
                    $idHist,
                    200,
                    'Débitos recebidos automaticamente pelo webhook (' . (int) ($resultado['qtde'] ?? 0) . ').',
                    c_apuracao_cbs::VALIDADE_ARQUIVO_HORAS
                );
                $this->repository->updateWebhookLog($logId, 'S', 'Débitos persistidos pelo webhook.', $cnpjBase, $tiquete);
                return ['http_code' => 200, 'ok' => true, 'msg' => 'Débitos processados.'];
            } catch (Exception $e) {
                $this->repository->updateWebhookLog($logId, 'N', 'Falha ao persistir débitos: ' . $e->getMessage(), $cnpjBase, $tiquete);
                return ['http_code' => 500, 'ok' => false, 'msg' => 'Erro ao processar débitos.'];
            }
        }

        // Caso normal: recebemos apenas o tíquete de download -> libera o download.
        if ($tiquete === '') {
            $this->repository->updateWebhookLog($logId, 'N', 'Webhook sem tíquete de download.', $cnpjBase, $tiquete);
            return ['http_code' => 422, 'ok' => false, 'msg' => 'Tíquete não informado.'];
        }

        $this->repository->updateTiqueteDownload($idHist, $tiquete, 'Tíquete de download recebido pelo webhook.');
        $this->repository->updateWebhookLog($logId, 'S', 'Tíquete recebido; download liberado.', $cnpjBase, $tiquete);

        return ['http_code' => 200, 'ok' => true, 'msg' => 'Tíquete recebido.'];
    }

    /**
     * Extrai o CNPJ do payload em diferentes formatos possíveis.
     */
    protected function extrairCnpj(array $json)
    {
        $candidatos = [
            $json['cnpj'] ?? null,
            $json['ni'] ?? null,
            $json['cnpjBase'] ?? null,
            $json['numeroInscricao'] ?? null,
            $json['contribuinte']['ni'] ?? null,
            $json['contribuinte']['cnpj'] ?? null,
        ];
        foreach ($candidatos as $c) {
            if ($c !== null && trim((string) $c) !== '') {
                return (string) $c;
            }
        }
        return '';
    }

    /**
     * Extrai o tíquete de download do payload.
     */
    protected function extrairTiquete(array $json)
    {
        $candidatos = [
            $json['tiqueteDownload'] ?? null,
            $json['tiquete'] ?? null,
            $json['ticket'] ?? null,
            $json['protocolo'] ?? null,
            $json['id'] ?? null,
        ];
        foreach ($candidatos as $c) {
            if ($c !== null && trim((string) $c) !== '') {
                return substr((string) $c, 0, 100);
            }
        }
        return '';
    }

    /**
     * Detecta se o payload contém o JSON completo de débitos (e não só o tíquete).
     */
    protected function payloadTemDebitos(array $json)
    {
        $raiz = $json['debitos'] ?? $json;
        if (!is_array($raiz)) {
            return false;
        }
        foreach (['apuracaoCorrente', 'apuracaoAjuste', 'debitosExtemporaneos'] as $grupo) {
            if (isset($raiz[$grupo])) {
                return true;
            }
        }
        return false;
    }
}
