<?php
/**
 * @package   astecv3
 * @name      c_api_bradesco
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      02/12/2025
 * 
 * INFORMACOES IMPORTANTES:
 * Tabela de status 10
 * 01 - A VENCER / VENCIDO
 * 02 - COM PAGAMENTO VINCULADO
 * 03 - COM PAGTO VINCULADO E INSTRUCAO AGENDADA
 * 04 - COM INSTRUCAO DE PROTESTO
 * 05 - COM INSTR. DE PROTESTO E PAGTO VINCULADO
 * 06 - EM PODER DO CARTORIO
 * 07 - COM INSTR. E PEDIDO SUSTACAO - SEM BAIXA
 * 08 - COM INSTR. E PEDIDO SUSTACAO - COM BAIXA
 * 09 - EM CARTORIO E PEDIDO SUSTACAO - S/ BAIXA
 * 10 - EM CARTORIO E PEDIDO SUSTACAO - C/ BAIXA
 * 11 - COM BAIXA SOLICITADA
 * 12 - COM EXECUCAO SOLICITADA
 * 13 - PAGO NO DIA
 * 14 - EM CARTORIO COM PAGAMENTO VINCULADO
 * 15 - INSTR. PED. SUST. - S/ BAIXA - PGTO VINC
 * 16 - INSTR. PED. SUST. - C/ BAIXA - PGTO VINC
 * 17 - CARTORIO PED. SUST. -S/ BAIXA - PGTO VINC
 * 18 - CARTORIO PED. SUST. -C/ BAIXA - PGTO VINC
 * 19 - SUSTADO SEM REMESSA AO CARTORIO
 * 20 - SUSTADO RETIRADO DE CARTORIO
 * 21 - SUSTADO JUDICIALMENTE
 * 22 - PENDENTE NO DISTRIBUIDOR
 * 23 - TITULO COM IRREGULARIDADE
 * 24 - AGUARDANDO APONTAMENTO DE IRREGULARIDADE
 * 25 - AGUARDANDO SOLICIT. DE SUSTACAO C/ BAIXA
 * 26 - AGUARDANDO SOLICIT. DE SUSTACAO S/BAIXA
 * 27 - SOLIC. SUSTACAO C/ENVIO CARTOR. C/BAIXA
 * 28 - SOLIC. SUSTACAO C/ENVIO CARTOR. S/BAIXA
 * 29 - EM CARTORIO COM EDITAL
 * 30 - COM PAGAMENTO RETIDO
 * 31 - COM INSTR NEGATIVACAO
 * 32 - EM PROC NEGATIVACAO
 * 33 - NEGATIVADO
 * 34 - EXCL NEG S/BAIXA
 * 35 - EXCL NEG C/BAIXA
 * 51 - POR ACERTO
 * 52 - BAIXA POR REGISTRO DUPLICADO
 * 53 - POR DECURSO DE PRAZO
 * 54 - POR MEDIDA JUDICIAL
 * 55 - POR REMESSA (CEB)
 * 56 - COBRADO - POR RASTREAMENTO
 * 57 - CONFORME SEU PEDIDO
 * 58 - PROTESTADO
 * 59 - DEVOLVIDO
 * 60 - ENTREGUE FRANCO DE PAGAMENTO
 * 61 - PAGO
 * 62 - PAGO EM CARTORIO
 * 63 - SUSTADO RETIRADO DE CARTORIO
 * 64 - SUSTADO SEM REMESSA A CARTORIO
 * 65 - TRANSFERIDO PARA DESCONTO
 * 66 - CREDITO EXDD
 * 67 - CREDITO EXDD - PAGO EM CARTORIO
 * 68 - COBRADO - POR BAIXA MANUAL
 * 69 - COBRADO - POR BAIXA MANUAL - PAGO EM CARTORIO
 * 70 - TRANSFERENCIA RECEBIVEIS
 * 71 - DEVOLUCAO TRANSF RECEBIVEIS
 * 72 - TRANSF. FUNDOS RECEB. / COBRANCA
 * 98 - POR REGISTRO DUPLICADO
 * 99 - COM REATIVACAO SOLICITADA
 * 
 * 
 * Como gerar .pem e .key atraves do pfx:
 * 
 * PEM:
 * openssl pkcs12 -in certificado.pfx -nokeys -out certificado.pem
 * openssl pkcs12 -in la_2025_2026.pfx -nokeys -out la_2025_2026.pem

 * KEY:
 * openssl pkcs12 -in la_2025_2026.pfx -nocerts -nodes -out la_2025_2026.key
 * openssl pkcs12 -in certificado.pfx -nocerts -nodes -out certificado.key
 */

$dir = dirname(__FILE__);


include_once($dir."/../../bib/c_user.php");
include_once($dir."/../util/c_api_response.php");
include_once($dir."/c_api_bradesco_service.php");


Class c_api_bradesco extends c_user {

    public $m_submenu       = NULL;
    public $m_letra         = NULL;
    public $parm_post       = NULL;
    public $parm_session    = NULL;
    public $m_id_lancamento = NULL;
    public $m_banco         = NULL;
    public $m_dados         = [];

    function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtém POST filtrado
        $this->parm_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Obtém SESSION filtrado
        $this->parm_session = filter_var_array($_SESSION, FILTER_DEFAULT);

        // Carrega o usuário da sessão
        if (isset($_SESSION['user_array'])) {
            c_user::from_array($_SESSION['user_array']);
        }
    }

    /**
     * Registra um boleto na API do Bradesco
     * 
     * @param int $id_lancamento ID do lançamento
     * @return void
     */
    function registraBoleto( int $id_lancamento) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }     

            // Instancia o serviço de processamento do registro do boleto
            $service = new c_api_bradesco_service();

            // Processa o registro do boleto
            $dados = $service->processaRegistraBoleto($id_lancamento);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Boleto registrado com sucesso', $dados['body'], $dados['meta']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro registraBoleto: ' . $e->getMessage());
            c_api_response::serverError(
                'Erro interno ao processar registro do boleto',
                [$e->getMessage()]
            );
        }
    }

    /**
     * Baixa um título na API do Bradesco
     * 
     * @param array $dados Dados para baixa de título
     * @dados['id_lancamento'] ID do lançamento
     * @dados['id_tabela_api'] ID do título na API Bradesco
     * @return void
     */
    function baixaTitulo($dados) {
        try {

            $id_lancamento = $dados['id_lancamento'];
            $id_tabela_api = $dados['id_tabela_api'];

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }

            $service = new c_api_bradesco_service();
            $dados   = $service->processaBaixaTitulo($id_lancamento, $id_tabela_api);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Baixa do título realizada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro baixaTitulo: ' . $e->getMessage());
            c_api_response::serverError(
                'Erro interno ao processar baixa do título',
                [$e->getMessage()]
            );
        }
    }

    /**
     * Baixa um título via tela de baixa consolidacao do Bradesco
     * 
     * @param array $dados Dados para baixa de título consolidado
     * @return void
     */
    function baixaTituloConsolidacao($dados) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }   

            // Instancia o serviço de processamento da baixa de título consolidado
            $service = new c_api_bradesco_service();
            $dados   = $service->processaBaixaTituloConsolidacao($dados);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados["erros"]
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Baixa do título realizada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };
        } catch (Exception $e) {
            error_log('Erro baixaTituloConsolidacao: ' . $e->getMessage());
            c_api_response::serverError(
                'Erro interno ao processar baixa do título consolidado',
                [$e->getMessage()]
            );
        }
    }



    function alteraTitulo($id_lancamento) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }

            $service = new c_api_bradesco_service();
            $dados   = $service->processaAlteraTitulo($id_lancamento);

            match($dados['http_code']) {
                999 => c_api_response::validationError($dados['erro']['title'], $dados['erros'], $dados['erro']['detail']),
                400 => c_api_response::badRequest($dados['erro']['title'], $dados['erros'], $dados['erro']['detail']),
                412 => c_api_response::badRequest($dados['mensagem'], $dados['erros'], $dados['erros']['detail']),
                500 => c_api_response::badRequest($dados['mensagem'], $dados['erros'], $dados['erros']['detail']),
                200 => c_api_response::success('Alteração de título realizada com sucesso', $dados['body']),
                default => c_api_response::validationError($dados['erro']['title'], $dados['erros'], $dados['erro']['detail'])
            };

        } catch (Exception $e) {
            error_log('Erro alteraTitulo: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar alteração do título', [$e->getMessage()]);
        }
    }

    /**
     * Consulta de título unitário
     * @param array $dados
     * @return void
     */
    function consultaDeTituloUnitario(array $dados) {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }   

            // Instancia o serviço de processamento da consulta de título unitário
            $service = new c_api_bradesco_service();
            $dados   = $service->processaConsultaTituloUnitario($dados);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Consulta realizada com sucesso', '', $dados['meta']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro consultaDeTituloUnitario: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de título unitário', [$e->getMessage()]);
        }
    }


    /**
     * Consulta títulos liquidados
     * @param array $dados
     * @return void
     */
    function consultaTitulosLiquidados($dados) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }   

            // Instancia o serviço de processamento da consulta de títulos liquidados
            $service = new c_api_bradesco_service();
            $dados = $service->processaConsultaTitulosLiquidados($dados);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Consulta realizada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro consultaTitulosLiquidados: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de títulos liquidados', [$e->getMessage()]);
        }
    }


    /**
     * Consulta título pendente
     * @param array $dados
     * @return void
     */
    function consultaTituloPendente($dados) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }

            $service = new c_api_bradesco_service();
            $dados = $service->processaConsultaTituloPendente($dados);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Consulta realizada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro consultaTituloPendente: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de título pendente', [$e->getMessage()]);
        }
    }


    /**
     * Consulta títulos baixados
     * @param array $dados
     * @return void
     */
    function consultaTitulosBaixados($dados) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }

            $service = new c_api_bradesco_service();
            $dados   = $service->processaConsultaTitulosBaixados($dados);

            match($dados['http_code']) {
                // Erro de validação local (antes de chamar a API)
                422 => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                ),
    
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                ),
    
                200 => c_api_response::success('Consulta realizada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros']
                )
            };

        } catch (Exception $e) {
            error_log('Erro consultaTitulosBaixados: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de títulos baixados', [$e->getMessage()]);
        }
    }

    /**
     * Consulta títulos baixados
     * @param array $dados
     * @return void
     */
    function alterarPagina($dados) {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
                return;
            }   

            // Instancia o serviço de processamento da alteração de página  
            $service = new c_api_bradesco_service();
            $dados   = $service->processaAlterarPagina($dados);

            match($dados['http_code']) {
                // Erros retornados pela API Bradesco
                400,
                412,
                500 => c_api_response::badRequest(
                    $dados['mensagem'],
                    $dados['erros'],
                    $dados['tipo_consulta']     // detail pode repetir a mensagem principal
                ),
    
                200 => c_api_response::success('Página alterada com sucesso', $dados['body']),
    
                default => c_api_response::validationError(
                    $dados['mensagem'],
                    $dados['erros'],
                    $dados['tipo_consulta']
                )
            };

        } catch (Exception $e) {
            error_log('Erro alterarPagina: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar alteração de página', [$e->getMessage()]);
        }
    }



    //#################################### FUNCOES GERENCIAIS ####################################

    /**
     * Normaliza retorno da consulta de títulos liquidados (CBTTIAGW e estrutura equivalente).
     * Extrai cabeçalho em variáveis e lista títulos para cruzamento futuro por nosso número.
     *
     * @param array $dados id_lancamento, http_code, response_array, json_retorno_completo, created_user
     * @return array Entrada acrescida de cabecalho e titulos_tratados
     */
    static function trataRespostaApi($dados, $id_insert = null) {

        if (!is_array($dados)) {
            return $dados;
        }

        $body = $dados['body'] ?? null;

        if ($body !== null && !is_array($body)) {

            // Converte string para array
            if (is_string($body)) {
                $dec = json_decode($body, true);
                $body = is_array($dec) ? $dec : [];
            } else {
                $body = [];
            }
        }

        if (!is_array($body)) {
            $body = [];
        }

        $status = $body['status'] ?? null;
        $transacao = $body['transacao'] ?? null;
        $mensagem = $body['mensagem'] ?? null;
        $causa = $body['causa'] ?? null;
        $vtotTitulos = $body['vtotTitulos'] ?? null;
        $vtotPag = $body['vtotPag'] ?? null;
        $vtotOscila = $body['vtotOscila'] ?? null;
        $vtotOscilaS = $body['vtotOscilaS'] ?? null;
        $vtotCheque = $body['vtotCheque'] ?? null;
        $vtotDinheiro = $body['vtotDinheiro'] ?? null;
        $difMaior = $body['difMaior'] ?? null;
        $difMenor = $body['difMenor'] ?? null;
        $difMenorS = $body['difMenorS'] ?? null;
        $pagina = $body['pagina'] ?? null;
        $indMaisPagina = $body['indMaisPagina'] ?? null;
        $qtdeTitulos = $body['qtdeTitulos'] ?? null;
        $qtdeOcorr = $body['qtdeOcorr'] ?? null;
        $id_insert_consulta_titulos = $id_insert ?? null;

        $titulosBruto = $body['titulos'] ?? [];
        if (!is_array($titulosBruto)) {
            $titulosBruto = [];
        }

        $titulosTratados = [];
        foreach ($titulosBruto as $indice => $titulo) {

            if (!is_array($titulo)) {
                continue;
            }

            $nossoNumero = $titulo['nossoNumero'] ?? null;

            // Verifica se o nosso número existe na tabela FIN_LANCAMENTO para pegar o ID do lançamento
            $result_nosso_numero = c_api_bradesco_repository::getNossoNumero($nossoNumero);

            // Se o nosso número não existe, seta o ID do lançamento como null
            if(empty($result_nosso_numero)) {
                $existe_nosso_numero = false;
                $id_lancamento = null;
            }else{
                $existe_nosso_numero = true;
                $id_lancamento = $result_nosso_numero['ID'];
            }

            $titulosTratados[] = [
                'indice' => $indice,
                'id_lancamento' => $id_lancamento,
                'existe_nosso_numero' => $existe_nosso_numero,
                'nosso_numero' => $nossoNumero,
                'banco_recebor' => $titulo['bancoRecebor'] ?? null,
                'agencia_recebora' => $titulo['agenciaRecebora'] ?? null,
                'data_vencimento' => c_api_bradesco::formatarData($titulo['dataVencimento'] ?? null),
                'data_pagamento' => c_api_bradesco::formatarData($titulo['dataPagamento'] ?? null),
                'data_movimento' => c_api_bradesco::formatarData($titulo['dataMovimento'] ?? null),
                'nome_pagador' => $titulo['nomePagador'] ?? null,
                'descricao_origem_pagamento' => $titulo['descricaoOrigemPagamento'] ?? null,
                'valor_titulo' => c_api_bradesco::formatarValor($titulo['valorTitulo'] ?? null),
                'valor_pagamento' => c_api_bradesco::formatarValor($titulo['valorPagamento'] ?? null),
                'valor_movimento' => c_api_bradesco::formatarValor($titulo['valorMovimento'] ?? null),
                'valor_oscila' => c_api_bradesco::formatarValor($titulo['valorOscilacao'] ?? null),
                'valor_oscila_s' => $titulo['sinalValorOscilacao]'] ?? null,
                'digito_nosso_numero' => $titulo['digitoNossoNumero'] ?? null,
                'seu_numero' => $titulo['seuNumero'] ?? null,
                'tipo_registro' => $titulo['tipoRegistro'] ?? null,
            ];
        }

        $cabecalho = [
            'status' => $status,
            'transacao' => $transacao,
            'mensagem' => $mensagem,
            'causa' => $causa,
            'vtotTitulos' => $vtotTitulos,
            'vtotPag' => $vtotPag,
            'vtotOscila' => $vtotOscila,
            'vtotOscilaS' => $vtotOscilaS,
            'vtotCheque' => $vtotCheque,
            'vtotDinheiro' => $vtotDinheiro,
            'difMaior' => $difMaior,
            'difMenor' => $difMenor,
            'difMenorS' => $difMenorS,
            'pagina' => $pagina,
            'indMaisPagina' => $indMaisPagina,
            'qtdeTitulos' => $qtdeTitulos,
            'qtdeOcorr' => $qtdeOcorr,
            'id_insert_consulta_titulos' => $id_insert_consulta_titulos,
        ];

        return [
            'cabecalho' => $cabecalho,
            'titulos' => $titulosTratados
        ];
    }


    /**
     * Formata data para o formato d/m/Y
     * @param string $data Data no formato dmY
     * @return string Data formatada ou null se a data for vazia ou inválida
     */
    static function formatarData($data) {
        if (empty($data) || $data === '00000000') return null;
    
        $date = DateTime::createFromFormat('dmY', $data);
        return $date ? $date->format('d/m/Y') : null;
    }

    /**
     * Formata valor para o formato brasileiro (R$)
     * @param string|int|float $valor Valor no formato inteiro (ex: 68400 = 684,00)
     * @param int $decimais Quantidade de casas decimais (padrão 2)
     * @return string|null Valor formatado ou null se vazio ou inválido
     */
    static function formatarValor($valor, $decimais = 2) {
        if (empty($valor) || !is_numeric($valor)) return null;

        $valorFormatado = $valor / pow(10, $decimais);
        return number_format($valorFormatado, $decimais, ',', '.');
    }
}	//	END OF THE CLASS
?>
