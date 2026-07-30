<?php

/**
 * @package   admv4.5
 * @name      p_processa_recupera_pdf_boleto
 * @version   4.5.00
 * @link      http://www.admservice.com.br/
 *
 * Endpoint SSE (Server-Sent Events) responsável por recuperar o PDF de cada
 * boleto vinculado a uma Nota Fiscal, transmitindo o progresso em tempo real
 * ao front-end.
 *
 * Fluxo esperado:
 *   1. Front-end abre um EventSource apontando para este endpoint com ?id=<id_nf>
 *   2. Este endpoint busca todos os lançamentos vinculados à NF
 *   3. Para cada lançamento, chama o service de recuperação de PDF no Banco Inter
 *   4. Cada resultado é transmitido como um evento SSE individual
 *   5. Ao final, um evento "concluido" é enviado e a conexão é encerrada
 *
 * Eventos emitidos:
 *   - progresso : { status, seq, total, id_lancamento, mensagem }
 *   - boleto    : { status, seq, total, id_lancamento, id_api_inter, pdf_url, vencimento, valor }
 *   - erro      : { status, seq, total, id_lancamento, mensagem }
 *   - concluido : { status, total, sucesso, falha }
 *   - falha     : { status, mensagem }  — erros fatais antes do stream iniciar
 */
/**
 * Desabilita exibição de erros e ativa reporte de erros
 */
ini_set('display_errors', 0);
error_reporting(E_ALL);


if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_database_pdo.php');


// ---------------------------------------------------------------------------
// Classe principal
// ---------------------------------------------------------------------------

class c_sse_imprime_boleto
{

    protected int $m_id_registro;

    // -----------------------------------------------------------------------

    public function __construct()
    {
        set_exception_handler([$this, 'exception_handler']);
        set_error_handler([$this, 'error_handler']);
        register_shutdown_function([$this, 'shutdown_handler']);
    }


    // -----------------------------------------------------------------------
    // Ponto de entrada
    // -----------------------------------------------------------------------

    public function initialize(): void
    {

        // Valida sessão
        if (!$this->sessaoValida()) {
            $this->emitirEvento('sessao_invalida', [
                'status'        => 'sessao_invalida',
                'seq'           => "",
                'total'         => "",
                'id_lancamento' => "",
                'mensagem'      => "Sessão de controle inválida. Faça login novamente.",
            ]);
            exit;
        }

        $this->stream();
    }

    // -----------------------------------------------------------------------
    // Stream SSE principal
    // -----------------------------------------------------------------------

    /**
     * Inicia o stream SSE e processa cada boleto vinculado à NF.
     */
    private function stream(): void
    {
        // A partir daqui nenhum header JSON pode ser enviado — inicia SSE
        $this->configurarHeadersSse();

        // Valida o parâmetro 'id'
        if ($this->m_id_registro === false || $this->m_id_registro === null || $this->m_id_registro <= 0) {
            $this->emitirEvento('registro_nao_encontrado', [
                'status'        => 'erro',
                'seq'           => "",
                'total'         => "",
                'id_lancamento' => "",
                'mensagem'      => "Parâmetro 'id' inválido ou ausente.",
            ]);
            return;
        }

        // Busca todos os lançamentos com a conta bancaria
        $lancamentos_conta_bancaria = $this->getFinLancamentoFinConta($this->m_id_registro);

        // Se não encontrar nenhum lançamento financeiro, retorna erro
        if (empty($lancamentos_conta_bancaria)) {
            $this->emitirEvento('registro_nao_encontrado', [
                'status'        => 'erro',
                'seq'           => "",
                'total'         => "",
                'id_lancamento' => "",
                'mensagem'      => "Não foi possivel encontrar lançamentos financeiros para o pedido: " . $this->m_id_registro . ".",
            ]);
            return;
        }

        $total   = count($lancamentos_conta_bancaria);
        $sucesso = 0;
        $falha   = 0;

        foreach ($lancamentos_conta_bancaria as $seq => $lancamento) {

            $seq_atual          = $seq + 1;
            $banco              = $lancamento['BANCO'];
            $id_lancamento      = (int) $lancamento['ID_LANCAMENTO'];
            $id_na_tabela_banco = (int) $lancamento['ID_NA_TABELA_BANCO'];

            // Notifica o front que este boleto está sendo processado
            $this->emitirEvento('progresso', [
                'status'        => 'processando',
                'seq'           => $seq_atual,
                'total'         => $total,
                'id_lancamento' => $id_lancamento,
                'mensagem'      => "Recuperando PDF do boleto {$seq_atual} de {$total}...",
            ]);


            // Recupera o PDF 
            $info_boleto = $this->getInfoBoletoPorBanco($banco, $id_na_tabela_banco, $lancamento);

            if ($info_boleto["sucesso"] === true) {
                $sucesso++;
                $this->emitirEvento('boleto', [
                    'status'        => 'finalizado',
                    'seq'           => $seq_atual,
                    'total'         => $total,
                    'id_lancamento' => $id_lancamento,
                    'id_api_do_banco'  => $id_na_tabela_banco,
                    'vencimento'    => date('d/m/Y', strtotime($lancamento['VENCIMENTO'])) ?? null,
                    'valor'         => number_format($lancamento['TOTAL'], 2, ',', '') ?? null,
                    'pdf_base64'    => $info_boleto['data']['boleto_Base64'] ?? null,
                ]);
            } else {
                $falha++;
                // Trata $info_boleto como um array com a resposta de erro
                $mensagem_erro = 'Falha ao recuperar PDF do boleto.';
                if (is_array($info_boleto) && isset($info_boleto['erros'])) {
                    $mensagem_erro = $info_boleto['erros']['title'] ?? $mensagem_erro;
                }

                $this->emitirEvento('erro', [
                    'status'        => 'erro',
                    'seq'           => $seq_atual,
                    'total'         => $total,
                    'id_lancamento' => $id_lancamento,
                    'mensagem'      => $mensagem_erro,
                ]);
            }
        }

        // Evento final — indica ao front que todos os boletos foram processados
        $this->emitirEvento('concluido', [
            'status'  => 'concluido',
            'total'   => $total,
            'sucesso' => $sucesso,
            'falha'   => $falha,
        ]);
    }


    /**
     * Emite um único evento SSE no formato padrão.
     *
     * @param string $evento  Nome do evento (ex: 'boleto', 'erro', 'concluido')
     * @param array  $payload Dados a serem serializados como JSON
     */
    private function emitirEvento(string $evento, array $payload): void
    {
        $payload['timestamp'] = date('c');

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo "event: {$evento}\n";
        echo "data: {$json}\n\n";

        // Força envio imediato ao cliente
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
    }

    /**
     * Obtém as informações do boleto por banco
     * @param string $banco
     * @param int $id_lancamento
     * @param array $lancamento
     * @return array
     */
    private function getInfoBoletoPorBanco($banco, $id_lancamento, $lancamento)
    {
        // Entra na rotina de recuperacao de cada banco
        switch ($banco) {
            case '77':

                include_once(__DIR__ . '/../../class/fin/c_api_inter_service.php');
                $service = new c_api_inter_service();
                $dados_boleto = $service->processaRecuperarCobrancaEmPdf($id_lancamento);

                if (empty($dados_boleto)) {
                    return false;
                }

                return $dados_boleto;

                break;
            case '237':
                // Gera o PDF do boleto Bradesco registrado via API
                if ($lancamento['ENVIA_BOLETO'] == 'A') {

                    // Verifica se o boleto já foi registrado via API
                    $id_tabela_api = (int) $id_lancamento;

                    // Se o boleto não foi registrado via API, registra o boleto
                    if ($id_tabela_api <= 0) {

                        
                        include_once(__DIR__ . '/../../class/fin/c_api_bradesco_service.php');

                        // Instancia o serviço de processamento do registro do boleto
                        $service = new c_api_bradesco_service();
                        // Obtém o ID do lançamento financeiro
                        $id_lancamento_fin = (int) ($lancamento['ID_LANCAMENTO'] ?? 0);

                        // Se o ID do lançamento financeiro não foi informado, retorna erro
                        if ($id_lancamento_fin <= 0) {
                            return [
                                'sucesso' => false,
                                'erros'   => ['title' => 'Lançamento financeiro não informado para registro do boleto.'],
                            ];
                        }

                        // Processa o registro do boleto
                        $retorno_registro = $service->processaRegistraBoleto($id_lancamento_fin);

                        // Se o registro do boleto não foi realizado com sucesso, retorna erro
                        if (($retorno_registro['sucesso'] ?? false) !== true) {
                            $mensagem = $retorno_registro['erro']['title']
                                ?? $retorno_registro['mensagem']
                                ?? 'Erro ao registrar boleto na API Bradesco.';

                            return [
                                'sucesso' => false,
                                'erros'   => ['title' => $mensagem],
                            ];
                        }

                        $id_tabela_api = (int) ($retorno_registro['id_tabela_api_bradesco'] ?? 0);

                        if ($id_tabela_api <= 0) {
                            return [
                                'sucesso' => false,
                                'erros'   => ['title' => 'Boleto registrado, porém o ID da tabela não foi retornado.'],
                            ];
                        }
                    }

                    // Gera o PDF do boleto Bradesco registrado via API
                    return $this->gerarPdfBoletoApiBradesco($lancamento, $id_tabela_api);

                } else {
                    // Gera o PDF do boleto Remessa
                    return $this->gerarPdfBoletoRemessa($lancamento, $id_lancamento);
                }

            case '748': // Sicredi — remessa/CNAB, PDF local (mesmo fluxo do boleto_imprime)
            case '341': // Itaú
                return $this->gerarPdfBoletoRemessa($lancamento, $id_lancamento);

            default:
                $this->emitirEvento('erro', [
                    'status'        => 'erro',
                    'seq'           => "",
                    'total'         => "",
                    'id_lancamento' => "",
                    'mensagem'      => "Banco não suportado. ",
                ]);

                return [];
        }

        return [];
    }

    /**
     * Gera PDF do boleto via mPDF (bancos com remessa: Sicredi, Bradesco sem API, Itaú).
     */
    private function gerarPdfBoletoRemessa(array $lancamento, int $id_lancamento): array
    {
        $lanc_pdf = $lancamento;
        if (empty($lanc_pdf['ID']) && !empty($lancamento['ID_LANCAMENTO'])) {
            $lanc_pdf['ID'] = $lancamento['ID_LANCAMENTO'];
        }

        require_once(__DIR__ . '/../../forms/blt/p_boleto_pdf.php');
        $obj_pdf = new p_boleto_pdf();
        $pdf_content = $obj_pdf->geraPdfBoletos([$lanc_pdf]);

        if (is_array($pdf_content) && isset($pdf_content['status']) && $pdf_content['status'] === false) {
            return [
                'sucesso' => false,
                'erros'   => ['title' => $pdf_content['msg'] ?? 'Erro ao gerar PDF do boleto'],
            ];
        }

        if (!is_string($pdf_content) || $pdf_content === '') {
            return [
                'sucesso' => false,
                'erros'   => ['title' => 'PDF do boleto vazio ou inválido'],
            ];
        }

        return [
            'sucesso' => true,
            'data' => [
                'id' => $id_lancamento,
                'boleto_Base64' => base64_encode($pdf_content),
            ],
            'http_code' => 200,
        ];
    }

    /**
     * Gera PDF do boleto Bradesco registrado via API (código de barras em FIN_API_BRADESCO).
     *
     * @param array $lancamento      Dados do lançamento + conta bancária
     * @param int   $id_tabela_api   ID em FIN_API_BRADESCO (REMESSANUM)
     * @return array
     */
    private function gerarPdfBoletoApiBradesco(array $lancamento, int $id_tabela_api): array
    {
        include_once(__DIR__ . '/../../class/fin/c_api_bradesco_repository.php');
        include_once(__DIR__ . '/../../class/fin/c_api_bradesco_barcode.php');
        include_once(__DIR__ . '/../../class/blt/funcoes_bradesco.php');

        $repository = new c_api_bradesco_repository();
        $dadosApi = $repository->getDadosImpressaoBoleto($id_tabela_api);

        if (empty($dadosApi)) {
            return [
                'sucesso' => false,
                'erros'   => ['title' => 'Registro do boleto não encontrado na API Bradesco.'],
            ];
        }

        if (empty($dadosApi['CD_BARRAS'])) {
            return [
                'sucesso' => false,
                'erros'   => ['title' => 'Código de barras não disponível para este boleto.'],
            ];
        }

        try {
            // Resolve o código de barras numérico
            $cdBarrasNumerico = c_api_bradesco_barcode::resolveCodigoBarrasNumerico($dadosApi['CD_BARRAS']);
        } catch (\InvalidArgumentException $e) {
            return [
                'sucesso' => false,
                'erros'   => ['title' => $e->getMessage()],
            ];
        }

        // Gera a linha digitável
        $linhaDigitavel = trim((string) ($dadosApi['LINHA_DIGITAVEL'] ?? ''));
        if ($linhaDigitavel === '') {
            $linhaDigitavel = monta_linha_digitavel($cdBarrasNumerico);
        }

        // Prepara os dados do lançamento
        $lanc_pdf = $lancamento;
        // Verifica se o ID do lançamento foi informado
        if (empty($lanc_pdf['ID']) && !empty($lancamento['ID_LANCAMENTO'])) {
            $lanc_pdf['ID'] = $lancamento['ID_LANCAMENTO'];
        }

        // Verifica se a conta foi informada
        if (empty($lanc_pdf['CONTA']) && !empty($dadosApi['CONTA'])) {
            $lanc_pdf['CONTA'] = $dadosApi['CONTA'];
        }

        // Gera o PDF do boleto Bradesco registrado via API
        require_once(__DIR__ . '/../../forms/blt/p_boleto_pdf.php');

        $obj_pdf     = new p_boleto_pdf();
        $pdf_content = $obj_pdf->geraPdfBoletoApiBradesco($lanc_pdf, $dadosApi, $cdBarrasNumerico, $linhaDigitavel);

        // Verifica se o PDF foi gerado com sucesso
        if (is_array($pdf_content) && isset($pdf_content['status']) && $pdf_content['status'] === false) {
            return [
                'sucesso' => false,
                'erros'   => ['title' => $pdf_content['msg'] ?? 'Erro ao gerar PDF do boleto API Bradesco'],
            ];
        }

        // Verifica se o PDF foi gerado com sucesso
        if (!is_string($pdf_content) || $pdf_content === '') {
            return [
                'sucesso' => false,
                'erros'   => ['title' => 'PDF do boleto vazio ou inválido'],
            ];
        }

        return [
            'sucesso' => true,
            'data' => [
                'id' => $id_tabela_api,
                'boleto_Base64' => base64_encode($pdf_content),
            ],
            'http_code' => 200,
        ];
    }


    /**
     * Obtém as informações do lançamento financeiro da nota fiscal
     * @param int $id_registro
     * @return array lancamentos e conta bancaria
     * @return array
     */
    private function getFinLancamentoFinConta($id_registro = null)
    {
        $sql = "SELECT 
                    FL.*,
                    FL.ID AS ID_LANCAMENTO,
                    FL.DOCTO AS NUMERO_DOCUMENTO,
                    FL.PARCELA AS PARCELA,
                    FL.CONTA AS ID_CONTA,
                    FL.REMESSANUM AS ID_NA_TABELA_BANCO,
                    FC.BANCO AS BANCO,
                    FC.NOMEINTERNO AS NOME_CONTABANCO ,
                    FL.VENCIMENTO AS VENCIMENTO,
                    FL.TOTAL AS TOTAL,
                    CASE WHEN FC.ENVIA_BOLETO IS NULL THEN 'R' ELSE FC.ENVIA_BOLETO END AS ENVIA_BOLETO
                    FROM FIN_LANCAMENTO FL 
                    INNER JOIN FIN_CONTA FC ON FL.CONTA = FC.CONTA
                WHERE 
                    FL.NUMLCTO = :numlct AND 
                    FL.SITPGTO = 'A' AND 
                    FL.MODOPGTO = 'B' AND 
                    FL.TIPOLANCAMENTO = 'R' AND 
                    FL.TIPODOCTO = 'B'";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindParam('numlct', $id_registro);
        // $banco->bindParam('serie', 'NFS');
        $banco->execute();
        return $banco->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Verifica se a sessão do usuário é válida.
     */
    private function sessaoValida(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $session = json_decode($_SESSION['user_array'] ?? '[]', true);

        return isset($session[0]) && $session[0] !== '';
    }

    /**
     * Define os headers necessários para um stream SSE.
     * Desabilita compressão e buffering para garantir entrega imediata.
     */
    private function configurarHeadersSse(): void
    {
        // Desabilita output buffering de todas as camadas
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('X-Accel-Buffering: no'); // Necessário para Nginx não bufferizar
        header('Connection: keep-alive');

        // Garante que o PHP não encerre a conexão por limite de tempo
        set_time_limit(0);
        ignore_user_abort(false);
    }


    // -----------------------------------------------------------------------
    // Tratamento global de exceções não capturadas
    // -----------------------------------------------------------------------

    public function exception_handler($e): void
    {
        error_log(
            'p_processa_recupera_pdf_boleto Exception: '
                . $e->getMessage()
                . ' | File: ' . $e->getFile()
                . ' | Line: ' . $e->getLine()
        );

        // Se o stream SSE ainda não começou, responde em JSON
        if (!headers_sent()) {
            $this->emitirEvento('erro', [
                'status'        => 'erro',
                'seq'           => "",
                'total'         => "",
                'id_lancamento' => "",
                'mensagem'      => "Erro o stream SSE ainda não começou: " . $e->getMessage(),
            ]);
        } else {
            // Dentro do stream, emite evento de falha e encerra
            $this->emitirEvento('erro', [
                'status'       => 'erro',
                'mensagem'     => 'Erro interno inesperado: ' . $e->getMessage(),
                'id_lancamento' => null,
            ]);
        }
    }

    /**
     * Tratamento de erros
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return bool
     */
    public function error_handler($errno, $errstr, $errfile, $errline): bool
    {
        // Se o erro não está sendo reportado, retorna false
        if (!(error_reporting() & $errno)) {
            return false;
        }

        // Se o erro é um aviso, retorna true
        if (in_array($errno, [E_NOTICE, E_WARNING, E_USER_NOTICE, E_USER_WARNING])) {
            return false;
        }

        // Emite evento de erro
        $this->emitirEvento('erro', [
            'status'       => 'erro',
            'mensagem'     => 'Erro interno inesperado: ' . $errstr,
            'id_lancamento' => null,
        ]);

        return true;
    }

    /**
     * Tratamento de shutdown
     */
    public function shutdown_handler(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->exception_handler(
                new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line'])
            );
        }
    }
}
