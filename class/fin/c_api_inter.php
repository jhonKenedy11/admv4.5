<?php
/**
 * @package   astecv3
 * @name      c_api_inter
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      02/12/2025
 */

$dir = dirname(__FILE__);


include_once($dir."/../../bib/c_user.php");
include_once($dir."/../util/c_api_response.php");
include_once($dir."/c_api_inter_service.php");


Class c_api_inter extends c_user {

    public $m_submenu       = NULL;
    public $m_letra         = NULL;
    public $m_banco         = NULL;
    public $m_id_lancamento = NULL;
    public $m_id            = NULL;
    public $parm_post       = [];
    public $parm_session    = [];
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
     * Emite uma cobrança no Inter
     * @param int $id_lancamento - ID da tabela API
     * @return void
     */
    function emitirCobranca($id_lancamento) : void {
        try {

            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaEmitirCobranca($id_lancamento);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                200 => c_api_response::success('Boleto registrado com sucesso', $retorno['data']['id'], []),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };

        } catch (Exception $e) {
            error_log('Erro emitirCobranca: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar emissão de cobrança', [$e->getMessage()]);
        }
    }


    /**
     * Recupera uma cobrança no Inter
     * @param int $id - ID da tabela API
     * @return void
     */
    function recuperarCobranca($id) : void {

        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaRecuperarCobranca($id);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                200 => c_api_response::success('Boleto registrado com sucesso', $retorno['data']['id'], $retorno['data']),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };
        } catch (Exception $e) {

            error_log('Erro recuperarCobranca: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de cobrança', [$e->getMessage()]);
        }
    }


    /**
     * Recupera uma cobrança no Inter em PDF
     * @param int $id - ID da tabela API
     */
    function recuperarCobrancaEmPdf($id) {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaRecuperarCobrancaEmPdf($id);

            return $retorno;

        } catch (Exception $e) {
            error_log('Erro recuperarCobrancaEmPdf: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de cobrança em PDF', [$e->getMessage()]);
        }
    }

    /**
     * Cancela uma cobrança no Inter
     * @param int $id_lancamento - ID da tabela API
     * @param string $motivo_cancelamento - Motivo do cancelamento
     * @return void
     */
    function cancelarCobranca( int $id_lancamento, string $motivo_cancelamento) : void {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaCancelarCobranca($id_lancamento, $motivo_cancelamento);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                204 => c_api_response::success('Boleto com situação CANCELADO no banco', $retorno['data']['id_lancamento'], []),
                202 => c_api_response::success('Boleto cancelado com sucesso', $retorno['data']['id_lancamento'], []),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };

        } catch (Exception $e) {
            error_log('Erro cancelarCobranca: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar cancelamento de cobrança', [$e->getMessage()]);
        }
    }

    /**
     * Paga a cobrança no Inter
     * @param int $id_lancamento - ID da tabela API
     * @param string $metodo_pagamento - Método de pagamento
     * @return void
     */
    function pagarCobranca( int $id_lancamento, string $metodo_pagamento) : void {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaPagarCobranca($id_lancamento, $metodo_pagamento);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                204 => c_api_response::success('Cobrança paga com sucesso', $retorno['data']['id_lancamento'], []),
                202 => c_api_response::success('Cobrança paga com sucesso', $retorno['data']['id_lancamento'], []),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };

        } catch (Exception $e) {
            error_log('Erro pagarCobranca: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar pagamento de cobrança', [$e->getMessage()]);
        }
    }
    
    /**
     * Recupera uma coleção de cobranças no Inter
     * @param array $dados - Dados da consulta de coleção de cobranças
     * @return void
     */
    function recuperarColecaoCobranca(array $dados) : void {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaRecuperarColecaoCobranca($dados);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                200 => c_api_response::success('Coleção de cobranças recuperada com sucesso', $retorno['data']),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };
        } catch (Exception $e) {
            error_log('Erro recuperarColecaoCobranca: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar consulta de coleção de cobranças', [$e->getMessage()]);
        } 
    }

    /**
     * Altera a página de uma coleção de cobranças no Inter
     * @param array $dados - Dados da alteração de página
     * @return void
     */
    function alterarPagina (array $dados) : void {
        try {
            // Obtém o usuário da sessão
            $session = json_decode($_SESSION['user_array'], true);

            if (!isset($session[0]) || $session[0] == '') {
                c_api_response::unauthorized('Não autorizado. Faça login para continuar.');
            }

            $service = new c_api_inter_service();

            $retorno = $service->processaAlterarPagina($dados);

            match($retorno['http_code']) {
                400 => c_api_response::badRequest($retorno["erros"]["title"], $retorno["erros"]["violacoes"] ?? [], $retorno["erros"]["detail"]),
                403 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                404 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                500 => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"]),
                200 => c_api_response::success('Página alterada com sucesso', $retorno['data']),
                default => c_api_response::validationError($retorno["erros"]["title"], [], $retorno["erros"]["detail"])
            };
        } catch (Exception $e) {
            error_log('Erro alterarPagina: ' . $e->getMessage());
            c_api_response::serverError('Erro interno ao processar alteração de página', [$e->getMessage()]);
        }
    }   
}	//	END OF THE CLASS

?>
