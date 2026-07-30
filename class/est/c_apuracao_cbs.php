<?php

/**
 * @package   adm4.5
 * @name      c_apuracao_cbs
 * @version   4.5.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Auto
 * @date      14/07/2026
 *
 * Integração com a Apuração Assistida IBS/CBS da Receita Federal.
 * Fluxo: token → solicitar consulta → download → persistência/comparação → aceite.
 */

$dir = dirname(__FILE__);
include_once($dir . '/../../bib/c_user.php');
include_once($dir . '/../../bib/c_database_pdo.php');
include_once($dir . '/c_apuracao_cbs_repository.php');
include_once($dir . '/c_apuracao_cbs_log.php');

class c_apuracao_cbs extends c_user
{
    const LIMITE_CONSULTA_DIA = 2;
    const LIMITE_DOWNLOAD_DIA = 8;
    const VALIDADE_ARQUIVO_HORAS = 24;
    const TOKEN_VALIDADE_SEGUNDOS = 3600;

    const URL_TOKEN_HOMOLOG = 'https://h-gateway.receitaintegra.serpro.gov.br/token';
    const URL_TOKEN_PROD = 'https://api.receitafederal.gov.br/token';
    const URL_API_HOMOLOG = 'https://h-gateway.receitaintegra.serpro.gov.br';
    const URL_API_PROD = 'https://api.receitafederal.gov.br';

    // Prefixo de path por ambiente (Produção Restrita usa prr-rtc)
    const PATH_PREFIX_PROD_RESTRITA = '/prr-rtc';
    const PATH_PREFIX_PADRAO = '/rtc';

    private $id = null;
    private $id_historico = null;
    private $id_debito = null;
    private $cnpj_base = null;
    private $client_id = null;
    private $client_secret = null;
    private $ambiente = 'HOMOLOGACAO';
    private $webhook_url = null;
    private $webhook_secret = null;
    private $tiquete = null;
    private $token = null;
    private $chave_dfe = null;
    private $tp_evento = null;
    private $papel = null;
    private $observacao = null;
    private $msg = null;

    /** @var c_apuracao_cbs_repository */
    protected $repository;

    function __construct()
    {
        $this->repository = new c_apuracao_cbs_repository();
    }

    //---------------------------------------------------------------
    // SETS E GETS
    //---------------------------------------------------------------

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setIdHistorico($id)
    {
        $this->id_historico = $id;
    }
    public function getIdHistorico()
    {
        return $this->id_historico;
    }

    public function setIdDebito($id)
    {
        $this->id_debito = $id;
    }
    public function getIdDebito()
    {
        return $this->id_debito;
    }

    public function setCnpjBase($cnpj)
    {
        // NT 2026.004: CNPJ pode conter letras (alfanumérico). Mantém os 8 primeiros do CNPJ base.
        $limpo = preg_replace('/[^A-Z0-9]/', '', strtoupper((string) $cnpj));
        $this->cnpj_base = substr($limpo, 0, 8);
    }
    public function getCnpjBase()
    {
        return $this->cnpj_base;
    }

    public function setClientId($v)
    {
        $this->client_id = trim((string) $v);
    }
    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientSecret($v)
    {
        $this->client_secret = trim((string) $v);
    }
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    public function setAmbiente($v)
    {
        $amb = strtoupper(trim((string) $v));
        $this->ambiente = in_array($amb, ['HOMOLOGACAO', 'PRODUCAO', 'PRODUCAO_RESTRITA'], true) ? $amb : 'HOMOLOGACAO';
    }
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    public function setWebhookUrl($v) { $this->webhook_url = trim((string) $v); }
    public function getWebhookUrl() { return $this->webhook_url; }

    public function setWebhookSecret($v) { $this->webhook_secret = trim((string) $v); }
    public function getWebhookSecret() { return $this->webhook_secret; }

    public function setTiquete($v)
    {
        $this->tiquete = trim((string) $v);
    }
    public function getTiquete()
    {
        return $this->tiquete;
    }

    public function setToken($v)
    {
        $this->token = $v;
    }
    public function getToken()
    {
        return $this->token;
    }

    public function setChaveDfe($v)
    {
        $this->chave_dfe = preg_replace('/[^0-9]/', '', (string) $v);
    }
    public function getChaveDfe() { return $this->chave_dfe; }

    public function setTpEvento($v) { $this->tp_evento = preg_replace('/[^0-9]/', '', (string) $v); }
    public function getTpEvento() { return $this->tp_evento; }

    public function setPapel($v)
    {
        $p = strtoupper(trim((string) $v));
        $this->papel = in_array($p, ['EMITENTE', 'DESTINATARIO'], true) ? $p : null;
    }
    public function getPapel() { return $this->papel; }

    public function setObservacao($v)
    {
        $this->observacao = $v;
    }
    public function getObservacao()
    {
        return $this->observacao;
    }

    public function setMsg($v) { $this->msg = $v; }
    public function getMsg() { return $this->msg; }

    //---------------------------------------------------------------
    // CATÁLOGO DE EVENTOS FISCAIS (por papel da empresa na nota)
    //---------------------------------------------------------------

    /**
     * Catálogo de eventos suportados na tela, separados por papel.
     * DESTINATARIO (adquirente) -> eventos 2xxxx
     * EMITENTE (fornecedor)     -> eventos 1xxxx
     *
     * @return array<string,array<int,array{tp:string,label:string}>>
     */
    public function catalogoEventos()
    {
        return [
            'DESTINATARIO' => [
                ['tp' => '211110', 'label' => 'Solicitação de apropriação de crédito presumido'],
                ['tp' => '211128', 'label' => 'Aceite de débito'],
                ['tp' => '211130', 'label' => 'Imobilização de item'],
                ['tp' => '211140', 'label' => 'Apropriação de crédito de combustível'],
                ['tp' => '211150', 'label' => 'Apropriação de crédito (bens/serviços por atividade)'],
                ['tp' => '211124', 'label' => 'Perda/Roubo em frete FOB'],
            ],
            'EMITENTE' => [
                ['tp' => '112110', 'label' => 'Informação de efetivo pagamento integral'],
                ['tp' => '112130', 'label' => 'Perda/Roubo em transporte contratado'],
                ['tp' => '112140', 'label' => 'Fornecimento não realizado'],
                ['tp' => '112150', 'label' => 'Atualizar data de entrega'],
            ],
        ];
    }

    /**
     * Retorna o rótulo e o papel esperado de um tipo de evento, ou null se não catalogado.
     */
    public function buscarEventoCatalogo($tp_evento)
    {
        $tp = preg_replace('/[^0-9]/', '', (string) $tp_evento);
        foreach ($this->catalogoEventos() as $papel => $eventos) {
            foreach ($eventos as $ev) {
                if ($ev['tp'] === $tp) {
                    return ['tp' => $tp, 'label' => $ev['label'], 'papel' => $papel];
                }
            }
        }
        return null;
    }

    /**
     * Determina o papel da empresa (EMITENTE|DESTINATARIO|OUTRO) comparando o
     * CNPJ base (8 chars) com os 8 primeiros do NI de emitente/adquirente.
     */
    protected function determinarPapel($ni_emitente, $ni_adquirente, $cnpj_base)
    {
        $base = strtoupper((string) $cnpj_base);
        if ($base === '') {
            return 'OUTRO';
        }
        $emit = substr(strtoupper((string) $ni_emitente), 0, 8);
        $adq = substr(strtoupper((string) $ni_adquirente), 0, 8);

        if ($emit !== '' && $emit === $base) {
            return 'EMITENTE';
        }
        if ($adq !== '' && $adq === $base) {
            return 'DESTINATARIO';
        }
        return 'OUTRO';
    }

    //---------------------------------------------------------------
    // CRIPTOGRAFIA / HTTP
    //---------------------------------------------------------------

    /**
     * Gera a chave de criptografia AES-256-CBC
     * @return string Chave de criptografia
     */
    private function chaveCriptografia()
    {
        $base = (defined('DB_NAME') ? DB_NAME : 'adm') . '|' . (defined('HOSTNAME') ? HOSTNAME : 'local');
        return hash('sha256', $base . '|EST_APURACAO_CBS', true);
    }

    /**
     * Criptografa um valor usando AES-256-CBC
     * @param string|null $valor Valor a ser criptografado
     * @return string Valor criptografado
     */
    protected function criptografar($valor)
    {
        if ($valor === null || $valor === '') {
            return '';
        }
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encrypted = openssl_encrypt($valor, 'AES-256-CBC', $this->chaveCriptografia(), 0, $iv);
        // O IV é codificado separadamente para que bytes binários aleatórios (ex.: 0x3a 0x3a)
        // não colidam com o separador '::'. Base64 usa apenas [A-Za-z0-9+/=], nunca ':'.
        return base64_encode($iv) . '::' . $encrypted;
    }

    /**
     * Descriptografa um valor usando AES-256-CBC
     * @param string|null $valor Valor criptografado
     * @return string Valor descriptografado
     */
    protected function descriptografar($valor)
    {
        if ($valor === null || $valor === '') {
            return '';
        }

        $ivLen = openssl_cipher_iv_length('AES-256-CBC');

        // Formato novo: base64(iv) . '::' . encrypted
        // Detectável porque a string armazenada contém '::' (base64 nunca usa ':').
        if (strpos($valor, '::') !== false) {
            [$ivBase64, $encrypted] = explode('::', $valor, 2);
            $iv = base64_decode($ivBase64, true);
            if ($iv === false || strlen($iv) !== $ivLen) {
                return '';
            }
            $plain = openssl_decrypt($encrypted, 'AES-256-CBC', $this->chaveCriptografia(), 0, $iv);
            return $plain !== false ? $plain : '';
        }

        // Formato antigo: base64(iv + '::' + encrypted) — mantido para backward compat.
        $decoded = base64_decode($valor, true);
        if ($decoded === false || strpos($decoded, '::') === false) {
            return $valor;
        }
        [$iv, $encrypted] = explode('::', $decoded, 2);
        if (strlen($iv) !== $ivLen) {
            return '';
        }
        $plain = openssl_decrypt($encrypted, 'AES-256-CBC', $this->chaveCriptografia(), 0, $iv);
        return $plain !== false ? $plain : '';
    }

    /**
     * Faz uma requisição HTTP
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL da requisição
     * @param array|string|null $body Corpo da requisição
     * @param array $headers Cabeçalhos da requisição
     * @param bool $formUrlEncoded Indica se o corpo é enviado em URL-encoded
     * @param string $contexto Rótulo do log (token|solicitar_consulta|download|...)
     * @return array Resposta da requisição
     */
    protected function httpRequest($method, $url, $body = null, $headers = [], $formUrlEncoded = false, $contexto = '')
    {   
        // Inicializa a requisição
        $ch = curl_init();
        $defaultHeaders = ['Accept: application/json'];

        // Define o tipo de conteúdo da requisição
        if ($formUrlEncoded) {
            $defaultHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
            $payload = is_array($body) ? http_build_query($body) : $body;
        } else {
            $defaultHeaders[] = 'Content-Type: application/json';
            $payload = is_array($body) ? json_encode($body, JSON_UNESCAPED_UNICODE) : $body;
        }

        // Configura a requisição
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => array_merge($defaultHeaders, $headers),
        ]);

        // Define o corpo da requisição
        if ($payload !== null && strtoupper($method) !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        // Executa a requisição
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Decodifica a resposta
        $json = null;
        if ($response !== false && $response !== '') {
            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $json = $decoded;
            }
        }

        $resultado = [
            'http_code' => $httpCode,
            'body' => $response === false ? '' : $response,
            'json' => $json,
            'error' => $error ?: null,
        ];

        // Log em arquivo (segredos são mascarados no helper).
        c_apuracao_cbs_log::registrar($contexto !== '' ? $contexto : 'http', [
            'request' => [
                'method' => strtoupper($method),
                'url' => $url,
                'headers' => array_merge($defaultHeaders, $headers),
                'body' => $body,
            ],
            'response' => [
                'http_code' => $httpCode,
                'body' => $response === false ? '' : $response,
                'error' => $error ?: null,
            ],
        ]);

        // Retorna a resposta
        return $resultado;
    }

    /**
     * Retorna a URL base da API conforme ambiente
     * @param string|null $ambiente Ambiente (HOMOLOGACAO, PRODUCAO, PRODUCAO_RESTRITA)
     * @return string URL base da API
     */
    protected function baseUrlApi($ambiente = null)
    {
        $amb = strtoupper($ambiente ?: $this->getAmbiente());
        return $amb === 'HOMOLOGACAO' ? self::URL_API_HOMOLOG : self::URL_API_PROD;
    }

    /**
     * Retorna a URL de token conforme ambiente
     * @param string|null $ambiente Ambiente (HOMOLOGACAO, PRODUCAO, PRODUCAO_RESTRITA)
     * @return string URL de token
     */
    protected function urlToken($ambiente = null)
    {
        $amb = strtoupper($ambiente ?: $this->getAmbiente());
        return $amb === 'HOMOLOGACAO' ? self::URL_TOKEN_HOMOLOG : self::URL_TOKEN_PROD;
    }

    /**
     * Prefixo de path da API conforme ambiente (Produção Restrita usa /prr-rtc).
     * @param string|null $ambiente Ambiente (HOMOLOGACAO, PRODUCAO, PRODUCAO_RESTRITA)
     * @return string Prefixo de path da API
     */
    protected function pathPrefixApi($ambiente = null): string
    {
        $amb = strtoupper($ambiente ?: $this->getAmbiente());
        return $amb === 'PRODUCAO_RESTRITA' ? self::PATH_PREFIX_PROD_RESTRITA : self::PATH_PREFIX_PADRAO;
    }

    //---------------------------------------------------------------
    // CONSULTAS (delegam ao repository)
    //---------------------------------------------------------------

    /**
     * Busca credencial por CNPJ base e ambiente
     * @param string|null $cnpj_base CNPJ base
     * @param string|null $ambiente Ambiente (HOMOLOGACAO, PRODUCAO, PRODUCAO_RESTRITA)
     * @return array|bool Credencial encontrada ou false
     */
    public function buscarCredencial($cnpj_base = null, $ambiente = null): array|bool
    {
        $cnpj = $cnpj_base ?: $this->getCnpjBase();
        if (!$cnpj) {
            return false;
        }
        return $this->repository->getCredencial($cnpj, $ambiente ?: $this->getAmbiente());
    }

    /**
     * Seleciona todas as credenciais
     * @return array Credenciais encontradas
     */
    public function selecionaCredenciais(): array
    {
        return $this->repository->getCredenciais();
    }

    /**
     * Seleciona histórico por CNPJ base e filtros
     * @param string|null $cnpj_base CNPJ base
     * @param array $filtros Filtros para consulta
     * @return array Histórico encontrados
     */
    public function selecionaHistorico($cnpj_base = null, $filtros = []): array
    {
        return $this->repository->getHistorico($cnpj_base ?: null, $filtros);
    }

    /**
     * Seleciona débitos por ID de histórico
     * @param int|null $id_historico ID de histórico
     * @return array Débitos encontrados
     */
    public function selecionaDebitos($id_historico = null): array
    {
        $id = (int) ($id_historico ?: $this->getIdHistorico());
        if ($id <= 0) {
            return [];
        }
        return $this->repository->getDebitos($id);
    }

    /**
     * Verifica limite diário de consultas ou downloads
     * @param string|null $cnpj_base CNPJ base
     * @param string $tipo Tipo de limite (consulta, download)
     * @return array Limite diário
     */
    public function verificaLimiteDiario($cnpj_base = null, $tipo = 'consulta'): array
    {
        $cnpj = $cnpj_base ?: $this->getCnpjBase();
        $limite = $tipo === 'download' ? self::LIMITE_DOWNLOAD_DIA : self::LIMITE_CONSULTA_DIA;
        $total = 0;

        if ($cnpj) {
            $total = $tipo === 'download'
                ? $this->repository->countDownloadsDia($cnpj)
                : $this->repository->countConsultasDia($cnpj);
        }

        return [
            'total' => $total,
            'limite' => $limite,
            'restante' => max(0, $limite - $total),
            'excedido' => $total >= $limite,
        ];
    }

    //---------------------------------------------------------------
    // REGRAS DE NEGÓCIO / API
    //---------------------------------------------------------------

    /**
     * Salva ou atualiza credencial (usa propriedades do objeto)
     * @return bool true se credencial salva ou atualizada com sucesso, false caso contrário
     */
    public function salvarCredencial()
    {
        try {
            if (strlen($this->getCnpjBase()) !== 8) {
                $this->setMsg('CNPJ Base deve ter 8 dígitos.');
                return false;
            }
            if ($this->getClientId() === '') {
                $this->setMsg('Informe o Client ID.');
                return false;
            }

            $existente = $this->repository->getCredencial($this->getCnpjBase(), $this->getAmbiente());

            if ($existente) {
                $dados = [
                    'client_id' => $this->getClientId(),
                    'webhook_url' => $this->getWebhookUrl(),
                    'webhook_secret' => $this->getWebhookSecret(),
                    'user_update' => (int) ($this->m_userid ?? 0),
                ];
                if ($this->getClientSecret() !== '') {
                    $dados['client_secret'] = $this->criptografar($this->getClientSecret());
                }
                $this->repository->updateCredencial((int) $existente['ID'], $dados);
                $this->setId((int) $existente['ID']);
                $this->setMsg('Credencial atualizada com sucesso.');
                return true;
            }

            if ($this->getClientSecret() === '') {
                $this->setMsg('Informe o Client Secret.');
                return false;
            }

            $id = $this->repository->insertCredencial([
                'cnpj_base' => $this->getCnpjBase(),
                'client_id' => $this->getClientId(),
                'client_secret' => $this->criptografar($this->getClientSecret()),
                'ambiente' => $this->getAmbiente(),
                'webhook_url' => $this->getWebhookUrl(),
                'webhook_secret' => $this->getWebhookSecret(),
                'user_insert' => (int) ($this->m_userid ?? 0),
            ]);
            $this->setId($id);
            $this->setMsg('Credencial salva com sucesso.');
            return true;
        } catch (Exception $e) {
            $this->setMsg('Erro ao salvar credencial: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gera Bearer Token e persiste na credencial
     * @return bool true se token gerado com sucesso, false caso contrário
     */
    public function gerarToken()
    {
        try {

            // Verifica se o CNPJ base é válido
            $cred         = $this->repository->getCredencial($this->getCnpjBase(), $this->getAmbiente());
            $clientId     = $this->getClientId() ?: ($cred['CLIENT_ID'] ?? '');
            $clientSecret = $this->getClientSecret();

            // Verifica se o Client Secret é válido
            if ($clientSecret === '' && $cred) {
                $clientSecret = $this->descriptografar($cred['CLIENT_SECRET']);
            }

            // Verifica se o ambiente é válido
            $amb = $this->getAmbiente() ?: ($cred['AMBIENTE'] ?? 'HOMOLOGACAO');

            // Verifica se o Client ID e o Client Secret são válidos
            if ($clientId === '' || $clientSecret === '') {
                $this->setMsg('Client ID e Client Secret são obrigatórios. Cadastre a credencial antes.');
                return false;
            }

            // Verifica se o token já existe e está válido
            if ($cred && !empty($cred['TOKEN']) && !empty($cred['TOKEN_EXPIRA_EM'])) {
                if (strtotime($cred['TOKEN_EXPIRA_EM']) > time() + 60) {
                    $tokenSalvo = $this->descriptografar($cred['TOKEN']);
                    if ($tokenSalvo !== '') {
                        $this->setToken($tokenSalvo);
                        $this->setMsg('Token ainda válido reutilizado. Expira em: ' . $cred['TOKEN_EXPIRA_EM']);
                        return true;
                    }
                }
            }

            // Faz a requisição para gerar o token
            $resp = $this->httpRequest('POST', $this->urlToken($amb), [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ], [], true, 'token');

            // Verifica se houve erro na requisição
            if ($resp['error']) {
                $this->setMsg('Falha de conexão: ' . $resp['error']);
                return false;
            }

            // Verifica se o limite de requisições foi excedido
            if ($resp['http_code'] === 429) {
                $this->setMsg('Limite de requisições excedido na API (HTTP 429).');
                return false;
            }

            // Verifica se o token foi gerado com sucesso
            if ($resp['http_code'] < 200 || $resp['http_code'] >= 300 || empty($resp['json']['access_token'])) {
                $msg = $resp['json']['error_description']
                    ?? $resp['json']['error']
                    ?? $resp['json']['message']
                    ?? ('HTTP ' . $resp['http_code']);
                $this->setMsg('Erro ao gerar token: ' . $msg);
                return false;
            }

            // Extrai o token e a data de expiração
            $token = $resp['json']['access_token'];
            $expiresIn = (int) ($resp['json']['expires_in'] ?? self::TOKEN_VALIDADE_SEGUNDOS);
            $expiraEm = date('Y-m-d H:i:s', time() + $expiresIn);

            // Atualiza o token na credencial
            if ($cred) {
                $this->repository->updateToken(
                    (int) $cred['ID'],
                    $this->criptografar($token),
                    $expiraEm
                );
            }

            // Define o token e o mensagem de sucesso
            $this->setToken($token);
            $this->setMsg('Token gerado com sucesso. Expira em: ' . $expiraEm);
            return true;
        } catch (\Throwable $e) {
            $this->setMsg('Erro ao gerar token: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Solicita consulta na RF e grava tíquete
     * @return bool
     */
    public function solicitarConsulta()
    {
        try {
            if (strlen($this->getCnpjBase()) !== 8) {
                $this->setMsg('CNPJ Base inválido.');
                return false;
            }

            $limite = $this->verificaLimiteDiario($this->getCnpjBase(), 'consulta');
            if ($limite['excedido']) {
                $this->setMsg('Limite diário de consultas atingido (' . $limite['limite'] . '/dia).');
                return false;
            }

            $cred = $this->repository->getCredencial($this->getCnpjBase(), $this->getAmbiente());
            $amb = $this->getAmbiente() ?: ($cred['AMBIENTE'] ?? 'HOMOLOGACAO');
            $webhook = $this->getWebhookUrl() ?: ($cred['WEBHOOK_URL'] ?? '');

            // A URL de retorno (webhook) é pré-requisito: o tíquete de download é
            // entregue de forma assíncrona pela Receita nessa URL.
            if (trim((string) $webhook) === '') {
                $this->setMsg('Configure a URL de retorno (webhook) nas credenciais antes de solicitar a consulta.');
                return false;
            }

            if (!$this->getToken()) {
                if (!$this->gerarToken()) {
                    return false;
                }
            }

            $url = $this->baseUrlApi($amb) . $this->pathPrefixApi($amb) . '/apuracao-cbs/v1/' . $this->getCnpjBase();
            $body = ['urlRetorno' => $webhook];

            $resp = $this->httpRequest('POST', $url, $body, ['Authorization: Bearer ' . $this->getToken()], false, 'solicitar_consulta');

            if ($resp['http_code'] === 429) {
                $this->setMsg('Limite de requisições excedido na Receita Federal (HTTP 429).');
                return false;
            }

            $tiquete = $resp['json']['tiquete']
                ?? $resp['json']['ticket']
                ?? $resp['json']['id']
                ?? $resp['json']['protocolo']
                ?? null;

            $status = 'ERRO';
            $msg = $resp['json']['message'] ?? $resp['json']['mensagem'] ?? null;

            if ($resp['error']) {
                $msg = 'Falha de conexão: ' . $resp['error'];
            } elseif ($resp['http_code'] >= 200 && $resp['http_code'] < 300) {
                // Sucesso (201): a consulta foi aceita; o tíquete de download chega
                // depois pelo webhook (urlRetorno). Ficamos aguardando o retorno.
                $status = 'AGUARDANDO_RETORNO';
                $msg = $msg ?: ($tiquete
                    ? ('Consulta solicitada. Tíquete: ' . $tiquete . '. Aguardando retorno automático da Receita.')
                    : 'Consulta solicitada. Aguardando retorno automático da Receita pelo webhook.');
            } else {
                $msg = $msg ?: ('Erro HTTP ' . $resp['http_code']);
            }

            $idHist = $this->repository->insertHistorico([
                'cnpj_base' => $this->getCnpjBase(),
                'tiquete' => $tiquete,
                'webhook_url' => $webhook,
                'status' => $status,
                'http_code' => $resp['http_code'],
                'msg_retorno' => $msg,
                'user_insert' => (int) ($this->m_userid ?? 0),
            ]);

            $this->setIdHistorico($idHist);
            $this->setTiquete($tiquete);
            $this->setMsg($msg);

            return $status !== 'ERRO';
        } catch (Exception $e) {
            $this->setMsg('Erro ao solicitar consulta: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Download dos débitos pelo tíquete
     * @return bool
     */
    public function downloadDebitos()
    {
        try {
            if ($this->getTiquete() === '') {
                $this->setMsg('Informe o tíquete.');
                return false;
            }

            $hist = $this->repository->getHistoricoPorTiquete($this->getTiquete());
            if ($hist && !$this->getCnpjBase()) {
                $this->setCnpjBase($hist['CNPJ_BASE']);
            }

            if ($this->getCnpjBase()) {
                $limite = $this->verificaLimiteDiario($this->getCnpjBase(), 'download');
                if ($limite['excedido']) {
                    $this->setMsg('Limite diário de downloads atingido (' . $limite['limite'] . '/dia).');
                    return false;
                }
            }

            $cred = $this->getCnpjBase()
                ? $this->repository->getCredencial($this->getCnpjBase(), $this->getAmbiente())
                : false;
            $amb = $this->getAmbiente() ?: ($cred['AMBIENTE'] ?? 'HOMOLOGACAO');

            if (!$this->getToken()) {
                if (!$this->gerarToken()) {
                    return false;
                }
            }

            $url = $this->baseUrlApi($amb) . $this->pathPrefixApi($amb) . '/download/v1/' . rawurlencode($this->getTiquete());
            $resp = $this->httpRequest('GET', $url, null, ['Authorization: Bearer ' . $this->getToken()], false, 'download');

            if ($resp['http_code'] === 429) {
                $this->setMsg('Limite diário de downloads excedido na Receita Federal (HTTP 429).');
                return false;
            }
            if ($resp['error']) {
                $this->setMsg('Falha de conexão: ' . $resp['error']);
                return false;
            }
            if ($resp['http_code'] === 403) {
                if ($hist) {
                    $this->repository->updateStatusHistorico((int) $hist['ID'], 'ERRO', 403, 'CNPJ do tíquete diverge do CNPJ autenticado (HTTP 403).');
                }
                $this->setMsg('Acesso negado (HTTP 403): o tíquete pertence a outro CNPJ ou as credenciais não têm permissão para este download.');
                return false;
            }
            if ($resp['http_code'] === 404) {
                if ($hist) {
                    $this->repository->updateStatusHistorico((int) $hist['ID'], 'ERRO', 404, 'Tíquete inválido ou arquivo não encontrado (HTTP 404).');
                }
                $this->setMsg('Arquivo não encontrado (HTTP 404): o tíquete é inválido, já foi baixado (1 acesso por tíquete) ou expirou (validade de ' . self::VALIDADE_ARQUIVO_HORAS . 'h).');
                return false;
            }

            if (in_array($resp['http_code'], [202, 204], true)
                || (!empty($resp['json']['status']) && stripos((string) $resp['json']['status'], 'process') !== false)
            ) {
                if ($hist) {
                    $this->repository->updateStatusHistorico((int) $hist['ID'], 'PROCESSANDO', $resp['http_code'], 'Arquivo ainda em processamento.');
                }
                $this->setMsg('Arquivo ainda não está disponível. Aguarde e tente novamente.');
                return false;
            }

            if ($resp['http_code'] < 200 || $resp['http_code'] >= 300 || $resp['json'] === null) {
                $msg = $resp['json']['message'] ?? $resp['json']['mensagem'] ?? ('HTTP ' . $resp['http_code']);
                if ($hist) {
                    $this->repository->updateStatusHistorico((int) $hist['ID'], 'ERRO', $resp['http_code'], $msg);
                }
                $this->setMsg('Erro no download: ' . $msg);
                return false;
            }

            $idHistorico = $hist ? (int) $hist['ID'] : 0;
            if (!$idHistorico && $this->getCnpjBase()) {
                $idHistorico = $this->repository->insertHistoricoBaixado([
                    'cnpj_base' => $this->getCnpjBase(),
                    'tiquete' => $this->getTiquete(),
                    'http_code' => $resp['http_code'],
                    'user_insert' => (int) ($this->m_userid ?? 0),
                ], self::VALIDADE_ARQUIVO_HORAS);
            } else {
                $this->repository->updateHistoricoBaixado(
                    $idHistorico,
                    $resp['http_code'],
                    'Download realizado com sucesso.',
                    self::VALIDADE_ARQUIVO_HORAS
                );
            }

            $this->setIdHistorico($idHistorico);
            $persistido = $this->persistirDebitos($idHistorico, $resp['json']);

            $msg = 'Débitos baixados com sucesso (' . ($persistido['qtde'] ?? 0) . ').';
            $msg .= ' O arquivo JSON tem validade de ' . self::VALIDADE_ARQUIVO_HORAS . ' horas.';
            if (!empty($persistido['divergencias'])) {
                $msg .= ' Há ' . count($persistido['divergencias']) . ' divergência(s) em relação à apuração anterior.';
            }
            $this->setMsg($msg);
            return true;
        } catch (Exception $e) {
            $this->setMsg('Erro no download: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Persiste débitos (por DF-e) + formas de extinção e compara com o download anterior da mesma chave.
     */
    public function persistirDebitos($id_historico, $dados_json)
    {
        $idHistorico = (int) $id_historico;
        $itens = $this->normalizarItensDebito($dados_json);
        $debitos = [];
        $divergencias = [];

        $hist = $this->repository->getHistoricoPorId($idHistorico);
        $cnpjBase = $hist['CNPJ_BASE'] ?? $this->getCnpjBase();

        $this->repository->deleteDebitosPorHistorico($idHistorico);

        foreach ($itens as $item) {
            $anterior = $this->repository->getDebitoAnteriorPorChave($item['chave_dfe'], $idHistorico);
            $divergente = 'N';
            $idAnterior = null;

            if ($anterior) {
                $idAnterior = (int) $anterior['ID'];
                $diffSaldo = abs((float) $anterior['VALOR_CBS_NAO_EXTINTO'] - (float) $item['valor_cbs_nao_extinto']) > 0.009;
                $diffSituacao = (string) $anterior['SITUACAO_DEBITO'] !== (string) $item['situacao_debito'];
                if ($diffSaldo || $diffSituacao) {
                    $divergente = 'S';
                    $divergencias[] = [
                        'chave_dfe' => $item['chave_dfe'],
                        'anterior' => [
                            'nao_extinto' => (float) $anterior['VALOR_CBS_NAO_EXTINTO'],
                            'situacao' => $anterior['SITUACAO_DEBITO'],
                        ],
                        'atual' => [
                            'nao_extinto' => (float) $item['valor_cbs_nao_extinto'],
                            'situacao' => $item['situacao_debito'],
                        ],
                    ];
                }
            }

            $papel = $this->determinarPapel($item['ni_emitente'], $item['ni_adquirente'], $cnpjBase);

            $idDeb = $this->repository->insertDebito([
                'id_historico' => $idHistorico,
                'tipo_apuracao' => $item['tipo_apuracao'],
                'data_apuracao' => $item['data_apuracao'],
                'chave_dfe' => $item['chave_dfe'],
                'modelo_dfe' => $item['modelo_dfe'],
                'numero_dfe' => $item['numero_dfe'],
                'ni_emitente' => $item['ni_emitente'],
                'ni_adquirente' => $item['ni_adquirente'],
                'cnpj_base' => $cnpjBase,
                'data_dfe_emissao' => $item['data_dfe_emissao'],
                'data_dfe_autorizacao' => $item['data_dfe_autorizacao'],
                'data_dfe_registro' => $item['data_dfe_registro'],
                'valor_cbs_total' => $item['valor_cbs_total'],
                'valor_cbs_extinto' => $item['valor_cbs_extinto'],
                'valor_cbs_nao_extinto' => $item['valor_cbs_nao_extinto'],
                'valor_prescrito' => $item['valor_prescrito'],
                'data_prescrito' => $item['data_prescrito'],
                'situacao_debito' => $item['situacao_debito'],
                'papel_empresa' => $papel,
                'json_original' => json_encode($item['original'], JSON_UNESCAPED_UNICODE),
                'divergente' => $divergente,
                'id_debito_anterior' => $idAnterior,
            ]);

            foreach ($item['pagamentos'] as $pg) {
                $pg['id_debito'] = $idDeb;
                $this->repository->insertPagamento($pg);
            }
            foreach ($item['creditos'] as $cr) {
                $cr['id_debito'] = $idDeb;
                $this->repository->insertCreditoUtilizado($cr);
            }
            foreach ($item['eventos_rf'] as $ev) {
                if (($ev['tp_evento'] ?? '') === '') {
                    continue;
                }
                $this->repository->insertEventoRf([
                    'chave_dfe' => $item['chave_dfe'],
                    'id_debito' => $idDeb,
                    'tp_evento' => $ev['tp_evento'],
                    'papel' => $papel,
                    'descricao' => $ev['descricao'],
                    'protocolo' => $ev['protocolo'],
                    'json_retorno' => $ev['json_retorno'],
                ]);
            }

            $debitos[] = ['id' => $idDeb, 'divergente' => $divergente, 'papel' => $papel];
        }

        return [
            'qtde' => count($debitos),
            'debitos' => $debitos,
            'divergencias' => $divergencias,
        ];
    }

    /**
     * Normaliza o JSON da apuração (níveis 3 a 6) em uma lista de débitos por DF-e.
     */
    protected function normalizarItensDebito($dados_json)
    {
        $lista = [];
        if (!is_array($dados_json)) {
            return $lista;
        }

        foreach ($this->extrairGruposDebito($dados_json) as $grupo) {
            $tipoApuracao = $grupo['tipo_apuracao'];
            foreach ($grupo['itens'] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $chave = $item['chaveDfe'] ?? $item['chaveAcesso'] ?? $item['chave'] ?? '';
                $chave = preg_replace('/[^0-9]/', '', (string) $chave);
                if ($chave === '') {
                    continue;
                }

                $dataApuracao = $item['dataApuracao'] ?? $item['periodoApuracao'] ?? $item['competencia'] ?? null;
                if ($dataApuracao) {
                    $dataApuracao = preg_replace('/[^0-9]/', '', (string) $dataApuracao);
                    $dataApuracao = substr($dataApuracao, 0, 6) ?: null;
                }

                $extincao = $this->extrairFormasExtincao($item);

                $lista[] = [
                    'tipo_apuracao' => substr((string) $tipoApuracao, 0, 50),
                    'data_apuracao' => $dataApuracao,
                    'chave_dfe' => substr($chave, 0, 44),
                    'modelo_dfe' => isset($item['modeloDfe']) ? substr((string) $item['modeloDfe'], 0, 2) : (isset($item['modelo']) ? substr((string) $item['modelo'], 0, 2) : null),
                    'numero_dfe' => isset($item['numeroDfe']) ? substr((string) $item['numeroDfe'], 0, 20) : (isset($item['numero']) ? substr((string) $item['numero'], 0, 20) : null),
                    'ni_emitente' => $this->normalizarNi($item['niEmitente'] ?? $item['emitente'] ?? $item['cnpjEmitente'] ?? null),
                    'ni_adquirente' => $this->normalizarNi($item['niAdquirente'] ?? $item['adquirente'] ?? $item['cnpjAdquirente'] ?? $item['destinatario'] ?? null),
                    'data_dfe_emissao' => $this->normalizarDataHora($item['dataDfeEmissao'] ?? $item['dataEmissao'] ?? null),
                    'data_dfe_autorizacao' => $this->normalizarDataHora($item['dataDfeAutorizacao'] ?? null),
                    'data_dfe_registro' => $this->normalizarDataHora($item['dataDfeRegistro'] ?? $item['dataRegistro'] ?? null),
                    'valor_cbs_total' => $this->normalizarValor($item['valorCBSTotal'] ?? $item['valorCbsTotal'] ?? $item['valorCbs'] ?? null),
                    'valor_cbs_extinto' => $this->normalizarValor($item['valorCBSExtinto'] ?? $item['valorCbsExtinto'] ?? null),
                    'valor_cbs_nao_extinto' => $this->normalizarValor($item['valorCBSNaoExtinto'] ?? $item['valorCbsNaoExtinto'] ?? null),
                    'valor_prescrito' => $extincao['valor_prescrito'],
                    'data_prescrito' => $extincao['data_prescrito'],
                    'situacao_debito' => isset($item['situacaoDebito']) ? substr((string) $item['situacaoDebito'], 0, 50) : (isset($item['situacao']) ? substr((string) $item['situacao'], 0, 50) : null),
                    'pagamentos' => $extincao['pagamentos'],
                    'creditos' => $extincao['creditos'],
                    'eventos_rf' => $this->extrairEventosRf($item),
                    'original' => $item,
                ];
            }
        }

        return $lista;
    }

    /**
     * Identifica os grupos de débito por tipo de apuração no JSON.
     */
    protected function extrairGruposDebito(array $dados_json)
    {
        $grupos = [];

        // Formato oficial: chaves por tipo de apuração
        $mapaTipos = [
            'apuracaoCorrente' => 'apuracaoCorrente',
            'apuracaoAjuste' => 'apuracaoAjuste',
            'debitosExtemporaneos' => 'debitosExtemporaneos',
        ];

        $raiz = $dados_json['debitos'] ?? $dados_json;

        foreach ($mapaTipos as $chave => $tipo) {
            if (isset($raiz[$chave]) && is_array($raiz[$chave])) {
                $itens = $this->itensDoGrupo($raiz[$chave]);
                if ($itens) {
                    $grupos[] = ['tipo_apuracao' => $tipo, 'itens' => $itens];
                }
            }
        }

        if ($grupos) {
            return $grupos;
        }

        // Fallbacks: array simples de débitos
        if (isset($dados_json['debitos']) && $this->ehListaSequencial($dados_json['debitos'])) {
            return [['tipo_apuracao' => 'apuracaoCorrente', 'itens' => $dados_json['debitos']]];
        }
        if (isset($dados_json['itens']) && is_array($dados_json['itens'])) {
            return [['tipo_apuracao' => 'apuracaoCorrente', 'itens' => $dados_json['itens']]];
        }
        if ($this->ehListaSequencial($dados_json)) {
            return [['tipo_apuracao' => 'apuracaoCorrente', 'itens' => $dados_json]];
        }

        return [];
    }

    /**
     * Extrai a lista de DF-e de um grupo (pode ser array direto ou conter subchave 'documentos'/'notas').
     */
    protected function itensDoGrupo($grupo)
    {
        if (!is_array($grupo)) {
            return [];
        }
        if ($this->ehListaSequencial($grupo)) {
            return $grupo;
        }
        // Layout oficial: cada grupo é objeto com a subchave 'debitos'
        foreach (['debitos', 'documentos', 'notas', 'dfe', 'itens'] as $sub) {
            if (isset($grupo[$sub]) && is_array($grupo[$sub])) {
                return $this->ehListaSequencial($grupo[$sub]) ? $grupo[$sub] : [$grupo[$sub]];
            }
        }
        return [$grupo];
    }

    /**
     * Extrai formas de extinção (layout oficial): 'formasExtincao' é um OBJETO com
     * as subchaves creditosCBS, creditosPISCOFINS, pagamentosCBS e prescricao.
     */
    protected function extrairFormasExtincao(array $item)
    {
        $pagamentos = [];
        $creditos = [];
        $valorPrescrito = null;
        $dataPrescrito = null;

        $formas = $item['formasExtincao'] ?? $item['formasDeExtincao'] ?? [];
        if (!is_array($formas)) {
            return [
                'pagamentos' => $pagamentos,
                'creditos' => $creditos,
                'valor_prescrito' => null,
                'data_prescrito' => null,
            ];
        }

        // Créditos de CBS
        foreach ($this->comoLista($formas['creditosCBS'] ?? []) as $cr) {
            if (!is_array($cr)) {
                continue;
            }
            $creditos[] = [
                'tipo_tributo' => 'CBS',
                'cclass_cred' => isset($cr['cClassCred']) ? substr((string) $cr['cClassCred'], 0, 50) : null,
                'origem' => null,
                'chave_dfe_origem' => isset($cr['chaveDfe']) ? preg_replace('/[^0-9]/', '', (string) $cr['chaveDfe']) : null,
                'modelo_dfe_origem' => isset($cr['modeloDfe']) ? substr((string) $cr['modeloDfe'], 0, 2) : null,
                'numero_dfe_origem' => isset($cr['numeroDfe']) ? substr((string) $cr['numeroDfe'], 0, 20) : null,
                'data_utilizacao' => $this->normalizarDataHora($cr['dataCreditoUtilizado'] ?? $cr['dataUtilizacao'] ?? null),
                'valor_principal' => $this->normalizarValor($cr['valorCreditoUtilizadoPrincipal'] ?? $cr['valorPrincipal'] ?? null),
                'valor_multa' => $this->normalizarValor($cr['valorCreditoUtilizadoMulta'] ?? $cr['valorMulta'] ?? null),
                'valor_juros' => $this->normalizarValor($cr['valorCreditoUtilizadoJuros'] ?? $cr['valorJuros'] ?? null),
            ];
        }

        // Créditos de PIS/COFINS
        foreach ($this->comoLista($formas['creditosPISCOFINS'] ?? []) as $cr) {
            if (!is_array($cr)) {
                continue;
            }
            $creditos[] = [
                'tipo_tributo' => 'PISCOFINS',
                'cclass_cred' => isset($cr['cClassCred']) ? substr((string) $cr['cClassCred'], 0, 50) : null,
                'origem' => isset($cr['origem']) ? substr((string) $cr['origem'], 0, 60) : null,
                'chave_dfe_origem' => isset($cr['chaveDfe']) ? preg_replace('/[^0-9]/', '', (string) $cr['chaveDfe']) : null,
                'modelo_dfe_origem' => isset($cr['modeloDfe']) ? substr((string) $cr['modeloDfe'], 0, 2) : null,
                'numero_dfe_origem' => isset($cr['numeroDfe']) ? substr((string) $cr['numeroDfe'], 0, 20) : null,
                'data_utilizacao' => $this->normalizarDataHora($cr['dataCreditoUtilizado'] ?? $cr['dataUtilizacao'] ?? null),
                'valor_principal' => $this->normalizarValor($cr['valorCreditoUtilizado'] ?? $cr['valorCreditoUtilizadoPrincipal'] ?? $cr['valorPrincipal'] ?? null),
                'valor_multa' => $this->normalizarValor($cr['valorCreditoUtilizadoMulta'] ?? $cr['valorMulta'] ?? null),
                'valor_juros' => $this->normalizarValor($cr['valorCreditoUtilizadoJuros'] ?? $cr['valorJuros'] ?? null),
            ];
        }

        // Pagamentos de CBS (DARF / split)
        foreach ($this->comoLista($formas['pagamentosCBS'] ?? []) as $pg) {
            if (!is_array($pg)) {
                continue;
            }
            $pagamentos[] = [
                'numero_darf' => isset($pg['numeroDarf']) ? substr((string) $pg['numeroDarf'], 0, 17) : null,
                'tipo_pagamento' => isset($pg['tipoPagamento']) ? substr((string) $pg['tipoPagamento'], 0, 30) : null,
                'data_arrecadacao' => $this->normalizarDataHora($pg['dataDarfArrecadado'] ?? $pg['dataArrecadacao'] ?? null),
                'data_utilizacao' => $this->normalizarDataHora($pg['dataDarfUtilizado'] ?? $pg['dataUtilizacao'] ?? null),
                'valor_principal' => $this->normalizarValor($pg['valorDarfUtilizadoPrincipal'] ?? $pg['valorPrincipal'] ?? null),
                'valor_multa' => $this->normalizarValor($pg['valorDarfUtilizadoMulta'] ?? $pg['valorMulta'] ?? null),
                'valor_juros' => $this->normalizarValor($pg['valorDarfUtilizadoJuros'] ?? $pg['valorJuros'] ?? null),
            ];
        }

        // Prescrição (objeto único) -> grava no próprio débito
        $prescricao = $formas['prescricao'] ?? null;
        if (is_array($prescricao) && $prescricao) {
            $valorPrescrito = $this->normalizarValor(
                $prescricao['valorPrescrito'] ?? $prescricao['valor'] ?? null
            );
            $dataPrescrito = $this->normalizarDataHora(
                $prescricao['dataPrescricao'] ?? $prescricao['data'] ?? null
            );
        }

        return [
            'pagamentos' => $pagamentos,
            'creditos' => $creditos,
            'valor_prescrito' => $valorPrescrito,
            'data_prescrito' => $dataPrescrito,
        ];
    }

    /**
     * Extrai eventos retornados pela RF no nível do débito (nível 4).
     * O layout dos subcampos não é detalhado na doc, então guardamos o bruto.
     */
    protected function extrairEventosRf(array $item)
    {
        $eventos = [];
        foreach ($this->comoLista($item['eventos'] ?? []) as $ev) {
            if (!is_array($ev)) {
                continue;
            }
            $tp = $ev['tpEvento'] ?? $ev['tipoEvento'] ?? $ev['codigoEvento'] ?? $ev['codEvento'] ?? '';
            $eventos[] = [
                'tp_evento' => substr(preg_replace('/[^0-9]/', '', (string) $tp), 0, 6),
                'descricao' => isset($ev['descricao']) ? substr((string) $ev['descricao'], 0, 120) : null,
                'protocolo' => isset($ev['protocolo']) ? substr((string) $ev['protocolo'], 0, 100) : null,
                'json_retorno' => json_encode($ev, JSON_UNESCAPED_UNICODE),
            ];
        }
        return $eventos;
    }

    protected function comoLista($node)
    {
        if (!is_array($node)) {
            return [];
        }
        return $this->ehListaSequencial($node) ? $node : [$node];
    }

    protected function ehListaSequencial($valor)
    {
        return is_array($valor) && (empty($valor) ? false : array_keys($valor) === range(0, count($valor) - 1));
    }

    protected function normalizarNi($ni)
    {
        if ($ni === null) {
            return null;
        }
        if (is_array($ni)) {
            $ni = $ni['ni'] ?? $ni['cnpj'] ?? $ni['cpf'] ?? '';
        }
        $limpo = preg_replace('/[^A-Z0-9]/', '', strtoupper((string) $ni));
        return $limpo !== '' ? substr($limpo, 0, 14) : null;
    }

    protected function normalizarValor($valor)
    {
        if ($valor === null || $valor === '') {
            return null;
        }
        return round((float) str_replace(',', '.', (string) $valor), 2);
    }

    protected function normalizarDataHora($valor)
    {
        if ($valor === null || $valor === '') {
            return null;
        }
        $ts = strtotime((string) $valor);
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
    }

    /**
     * Registra localmente um evento fiscal para uma chave de acesso.
     * (Envio à RF fica como evolução futura; aqui apenas registro local.)
     * @return bool
     */
    public function emitirEvento()
    {
        try {
            $chave = $this->getChaveDfe();
            if ($chave === null || strlen($chave) !== 44) {
                $this->setMsg('Chave de acesso inválida (deve ter 44 dígitos).');
                return false;
            }

            $catalogo = $this->buscarEventoCatalogo($this->getTpEvento());
            if (!$catalogo) {
                $this->setMsg('Tipo de evento não reconhecido.');
                return false;
            }

            $papelInformado = $this->getPapel() ?: $catalogo['papel'];
            if ($papelInformado !== $catalogo['papel']) {
                $this->setMsg('Este evento (' . $catalogo['tp'] . ') pertence ao papel ' . $catalogo['papel'] . '.');
                return false;
            }

            $debito = null;
            $idDebito = null;
            if ((int) $this->getIdDebito() > 0) {
                $debito = $this->repository->getDebitoPorId((int) $this->getIdDebito());
            }
            if ($debito) {
                $idDebito = (int) $debito['ID'];
                if (($debito['PAPEL_EMPRESA'] ?? 'OUTRO') !== $catalogo['papel']) {
                    $this->setMsg('O papel da empresa nesta nota não permite o evento ' . $catalogo['tp'] . '.');
                    return false;
                }
            }

            $payload = [
                'tpEvento' => $catalogo['tp'],
                'chaveDfe' => $chave,
                'papel' => $catalogo['papel'],
                'observacao' => $this->getObservacao() ?? '',
            ];

            $idEvento = $this->repository->insertEvento([
                'chave_dfe' => $chave,
                'id_debito' => $idDebito,
                'tp_evento' => $catalogo['tp'],
                'papel' => $catalogo['papel'],
                'descricao' => $catalogo['label'],
                'observacao' => $this->getObservacao(),
                'json_envio' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'msg_retorno' => 'Evento registrado localmente. Envio à RF aguardando endpoint oficial.',
                'status' => 'REGISTRADO',
                'user_insert' => (int) ($this->m_userid ?? 0),
            ]);

            if ($idDebito) {
                $this->repository->updateStatusEventoDebito($idDebito, 'REGISTRADO');
                $this->setIdHistorico((int) $debito['ID_HISTORICO']);
            }

            $this->setMsg('Evento ' . $catalogo['tp'] . ' (' . $catalogo['label'] . ') registrado com sucesso (ID ' . $idEvento . ').');
            return true;
        } catch (Exception $e) {
            $this->setMsg('Erro ao registrar evento: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista eventos registrados (para a aba de histórico de eventos).
     */
    public function selecionaEventos($cnpj_base = null)
    {
        return $this->repository->getEventos($cnpj_base ? ['cnpj_base' => $cnpj_base] : []);
    }
}
