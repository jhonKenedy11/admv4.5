<?php

/**
 * @package   astecv3
 * @name      c_api_inter_service
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 */

 $dir = dirname(__FILE__);
 
include_once($dir."/../../bib/c_user.php");
include_once($dir."/c_api_inter_json_builder.php");
include_once($dir."/c_api_inter_repository.php");
include_once($dir."/c_api_inter_curl.php");

Class c_api_inter_service extends c_user {

    /**
     * Construtor - carrega dados do usuário da sessão
     */
    function __construct() {
        // Carrega o usuário da sessão se disponível
        if (isset($_SESSION['user_array'])) {
            $this->from_array($_SESSION['user_array']);
        }
    }


    /**
     * Processa a emissão de cobrança
     * @param int $id ID do lançamento financeiro
     * @return array Retorno da API
     */
    function processaEmitirCobranca($id) : array {

        $repository   = new c_api_inter_repository();
        $json_builder = new c_api_inter_json_builder();

        // Obtém dados do boleto do banco
        $dados_repository = $repository->getDadosEmitirCobranca($id);

        // Necessario para inclusao do header na requisição da API Inter
        if(isset($dados_repository['contaCorrenteHeader']) && $dados_repository['contaCorrenteHeader']) {
            
            $conta_corrente_header = $dados_repository['contaCorrenteHeader'];

            if(strlen($conta_corrente_header) < 9) {

                return $this->erro(
                    title: 'Conta Corrente e digito verificador deve ter 9 dígitos',    
                    detail: 'Verifique o cadastro da conta corrente e digito verificador',
                );
            }

        } else {

            return $this->erro(
                title: 'Conta Corrente Header não encontrada',
                detail: 'Verifique o cadastro da conta corrente e digito verificador',
            );
        }

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados_repository);

        // Monta e valida o JSON
        $json_validate = $json_builder->jsonRegistraBoleto($dados_repository);
        
        if (!$json_validate['sucesso']) {
            // Salva log de erro de validação local
            $repository->insertLog([
                'banco' => '77',
                'id_lancamento' => $id,
                'tipo_operacao' => 'EMITIR_COBRANÇA',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-registro/v1/cobranca',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados da cobrança',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados_repository,
                'user_id' => $this->m_userid
            ]);
            
            return $this->erro(
                title: 'Erro na validação dos dados do boleto',
                detail: 'Verifique os dados do boleto',
                violacoes: $json_validate['erros'],
            );
        }


        // Inicializa a requisição para emitir a cobrança
        // Envia o JSON pronto para a API do Bradesco
        $curl    = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Consulta e define as credenciais da API do Inter
        $credenciais = $repository->getCredenciais($ambiente, 0, $dados_repository['conta_bancaria']);
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);


        $retorno = $curl->emitirCobranca($json_validate['dados'], $conta_corrente_header);
    
        if($retorno["http_code"] == 200) {

            $dadosSalvar = [
                'id_lancamento' => $id,
                'http_code' => $retorno['http_code'],
                'response_array' => $retorno['body'],
                'json_retorno_completo' => $retorno['response_raw'],
                'created_user' => $this->m_userid
            ];

           $id_insert = $repository->insertParcialEmitirCobranca($id, $dadosSalvar, $conta_corrente_header);

           return [
            'sucesso' => true,
            'data' => $id_insert,
            'http_code' => 200 // Sucesso
           ];
      
        }
        
        // Extrai erros de validação da API (se houver)
        $errosApi = $this->ExtrairErrosApi($retorno);
        
        // Salva log SEMPRE (sucesso ou erro)
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => $id,
            'tipo_operacao' => 'EMITIR_COBRANÇA',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-registro/v1/cobranca',
            'http_code' => $retorno["http_code"] ?? null,
            'sucesso' => $retorno['sucesso'] ?? false,
            'mensagem_api' => $retorno["body"]["title"] ?? null,
            'json_enviado' => $json_validate['dados'],
            'json_retorno' => $retorno["response_raw"] ?? null,
            'user_id' => $this->m_userid
        ]);
        
        // Se houve erros da API, adiciona ao retorno para exibição
        if (!empty($errosApi)) {
            $retorno['erros'] = $errosApi;
        }

        return $retorno;
    }


    /**
     * Consulta cobrança na API Inter pelo código de solicitação.
     *
     * @param int $id ID do registro na tabela FIN_API_INTER
     * @return array Retorno da API
     */
    function processaRecuperarCobranca( int $id) : array {

        // Obtém dados do boleto do banco
        $repository = new c_api_inter_repository();
        $dados_repository = $repository->getDadosRecuperarCobranca($id);

        // Se não foi encontrado o código de solicitação, retorna erro
        if(!$dados_repository) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Código de solicitação não encontrado',
                            'detail' => 'Verifique o código de solicitação',
                            'violacoes' => []],
                'http_code' => 400 // Dados invalidos
            ];
        }

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados_repository);

        // Inicializa o repository e o curl
        $repository = new c_api_inter_repository();
        $curl       = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Consulta e define as credenciais da API do Inter
        $credenciais = $repository->getCredenciais($ambiente, 0, $dados_repository['conta_bancaria']);
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);
        
        // Inicializa a requisição para recuperar a cobrança
        $retorno = $curl->recuperarCobranca($dados_repository['CODIGO_SOLICITACAO'], $dados_repository['CONTA_CORRENTE_HEADER']);

        // Fluxo de sucesso
        if($retorno["http_code"] == 200) {

            // Monta os dados para salvar na tabela FIN_API_INTER
            $dados_salvar = [
                'json_retorno_completo' => $retorno['response_raw'],
                'response' => $retorno['body'],
                'id' => $id,
                'user' => $this->m_userid
            ];
            
            // Atualiza a tabela FIN_API_INTER
            $update_api_inter = $repository->updateRecuperarCobranca($dados_salvar);

            // Obtém o ID do lançamento
            $get_id_lancamento = $repository->getIdLancamento($id);
            
            // Atualiza o lançamento
            $update_lancamento = $repository->updateLancamento($get_id_lancamento, $dados_salvar);

            return [
                'sucesso' => true,
                'data' => [
                    'id' => $id,
                    'update_api_inter' => $update_api_inter,
                    'update_lancamento' => $update_lancamento
                ],
                'http_code' => 200 // Sucesso
            ];

        }

        // Extrai os erros da resposta da API
        $errosApi = $this->ExtrairErrosApi($retorno);

        // Fluxo de erro
        // Salva log
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => $id,
            'tipo_operacao' => 'RECUPERAR_COBRANÇA',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'],
            'http_code' => $retorno["http_code"] ?? null,
            'sucesso' => $retorno['sucesso'] ?? false,
            'mensagem_api' => $retorno["body"]["detail"] ?? null,
            'json_retorno' => $retorno["response_raw"] ?? null,
            'user_id' => $this->m_userid
        ]);

        // Retorna o erro
        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao recuperar a cobrança na API Inter',
                        'detail' => 'Verifique o código de solicitação',
                        'violacoes' => $errosApi],
            'http_code' => 500 // Erro
        ];
    }


    /**
     * Recupera a cobrança em PDF na API Inter
     * @param int $id ID do registro na tabela FIN_API_INTER
     * @return array Retorno da API
     */
    function processaRecuperarCobrancaEmPdf( int $id) : array {
        // Obtém dados do boleto do banco
        $repository = new c_api_inter_repository();
        $dados_repository = $repository->getDadosRecuperarCobranca($id);

        // Se não foi encontrado o código de solicitação, retorna erro
        if(!$dados_repository['CODIGO_SOLICITACAO']) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Código de solicitação não encontrado',
                            'detail' => 'Verifique o código de solicitação',
                            'violacoes' => []],
                'http_code' => 400 // Dados invalidos
            ];
        }

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados_repository);

        // Inicializa o curl
        $curl = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Inicializa a requisição para recuperar a cobrança em PDF
        $retorno = $curl->recuperarCobrancaEmPdf($dados_repository['CODIGO_SOLICITACAO'], $dados_repository['CONTA_CORRENTE_HEADER']);

        // Fluxo de sucesso
        if($retorno["http_code"] == 200) {

            // Salva o PDF na pasta de uploads
            $pdf_base64 = $retorno["body"]["pdf"];
            $pdf_path = $repository->savePdf($id, $pdf_base64);

            return [
                'sucesso' => true,
                'data' => [
                    'id' => $id,
                    'boleto_Base64' => $retorno["body"]["pdf"],
                    'save_pdf' => $pdf_path
                ],
                'http_code' => 200 // Sucesso
            ];
        }

        // Extrai os erros da resposta da API
        $errosApi = $this->ExtrairErrosApi($retorno);

        // Fluxo de erro
        // Salva log
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => $id,
            'tipo_operacao' => 'RECUPERAR_COBRANÇA_EM_PDF',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'],
            'http_code' => $retorno["http_code"] ?? null,
            'sucesso' => $retorno['sucesso'] ?? false,
            'mensagem_api' => $retorno["body"]["detail"] ?? null,
            'json_retorno' => $retorno["response_raw"] ?? null,
            'user_id' => $this->m_userid
        ]);

        // Retorna o erro
        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao recuperar a cobrança em PDF na API Inter',
                        'detail' => 'Verifique o código de solicitação',
                        'violacoes' => $errosApi],
            'http_code' => 500 // Erro
        ];
    }   

    /**
     * Cancela uma cobrança na API Inter
     * @param int $id_lancamento ID do lançamento financeiro
     * @param string $motivo_cancelamento Motivo do cancelamento
     * @return array Retorno da API
     */
    function processaCancelarCobranca(int $id_lancamento, string $motivo_cancelamento = 'Devedor pagou por outra forma') : array {

        $repository = new c_api_inter_repository();

        // Busca código de solicitação e conta corrente pelo ID do lançamento
        $dados_repository = $repository->getDadosCobrancaIdLancamento($id_lancamento);

        if (!$dados_repository || empty($dados_repository['CODIGO_SOLICITACAO'])) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Código de solicitação não encontrado',
                            'detail' => 'Não foi encontrada cobrança Inter para este lançamento',
                            'violacoes' => []],
                'http_code' => 400
            ];
        }

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados_repository);

        $curl = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Consulta e define as credenciais da API do Inter
        $credenciais = $repository->getCredenciais($ambiente, 0, $dados_repository['conta_bancaria']);
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        $retorno = $curl->cancelarCobranca(
            $dados_repository['CODIGO_SOLICITACAO'],
            $dados_repository['CONTA_CORRENTE_HEADER'],
            $motivo_cancelamento
        );

        // Inter retorna 204 No Content no cancelamento com sucesso
        if ($retorno['http_code'] == 202) {

            // FALTA VERIFICAR A TRATATIVA COMO SERA ATUALIZAD O LANÇAMENTO FINANCEIRO
            $repository->updateCancelarCobranca($id_lancamento);

            $retorno['user'] = $this->m_userid; // Define o usuário que cancelou a cobrança

            // Atualiza o lançamento para cancelar a cobrança
            $repository->updateLancamento($dados_repository['ID_LANCAMENTO'], $retorno, true);

            return [
                'sucesso' => true,
                'data' => ['id_lancamento' => $id_lancamento],
                'http_code' => 202
            ];
        }

        // Se a situação da resposta de erro da API for CANCELADO, atualiza o lançamento e retorna sucesso
        $situacao = $this->extrairSituacao($retorno['body']['detail']);
        if($situacao == 'CANCELADO') {

            // FALTA VERIFICAR A TRATATIVA COMO SERA ATUALIZAD O LANÇAMENTO FINANCEIRO
            $repository->updateCancelarCobranca($id_lancamento);

            $retorno['user'] = $this->m_userid; // Define o usuário que cancelou a cobrança

            // Atualiza o lançamento para cancelar a cobrança
            $repository->updateLancamento($id_lancamento, $retorno, true);


            return [
                'sucesso' => true,
                'data' => ['id_lancamento' => $id_lancamento],
                'http_code' => 204
            ];
        }

        // Extrai erros da resposta da API
        $errosApi = $this->ExtrairErrosApi($retorno);

        // Salva log apenas em caso de erro
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => $id_lancamento,
            'tipo_operacao' => 'CANCELAR_COBRANÇA',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'] ?? '/cobranca/v3/cobrancas/{codigoSolicitacao}/cancelar',
            'http_code' => $retorno['http_code'] ?? null,
            'sucesso' => false,
            'mensagem_api' => $retorno['body']['detail'] ?? $retorno['body']['title'] ?? null,
            'json_retorno' => $retorno['response_raw'] ?? null,
            'user_id' => $this->m_userid
        ]);

        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao cancelar a cobrança na API Inter',
                        'detail' => 'Verifique o código de solicitação',
                        'violacoes' => $errosApi],
            'http_code' => 500
        ];
    }

    /**
     * Paga a cobrança no Inter
     * @param int $id_lancamento ID da tabela API
     * @param string $metodo_pagamento Método de pagamento
     * @return array Retorno da API
     */
    function processaPagarCobranca(int $id_lancamento, string $metodo_pagamento) : array 
    {
        $repository = new c_api_inter_repository();

        // Busca código de solicitação e conta corrente pelo ID do lançamento
        $dados_repository = $repository->getDadosPagarCobranca($id_lancamento);

        if (!$dados_repository || empty($dados_repository['CODIGO_SOLICITACAO'])) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Código de solicitação não encontrado',
                            'detail' => 'Não foi encontrada cobrança Inter para este lançamento',
                            'violacoes' => []],
                'http_code' => 400
            ];
        }

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados_repository);

        $curl = new c_api_inter_curl($ambiente, $this->m_empresaid);

        $retorno = $curl->PagarCobranca(
            $dados_repository['CODIGO_SOLICITACAO'],
            $dados_repository['CONTA_CORRENTE_HEADER'],
            $metodo_pagamento
        );

        // Inter retorna 204 No Content no cancelamento com sucesso
        if ($retorno['http_code'] == 204) {


            // FALTA VERIFICAR A TRATATIVA COMO SERA ATUALIZAD O LANÇAMENTO FINANCEIRO
            $repository->updatePagarCobranca($id_lancamento);

            return [
                'sucesso' => true,
                'data' => ['id_lancamento' => $id_lancamento],
                'http_code' => 204
            ];
        }

        // Se a situação da resposta de erro da API for CANCELADO, atualiza o lançamento e retorna sucesso
        $situacao = $this->extrairSituacao($retorno['body']['detail']);
        if($situacao == 'RECEBIDO') {

            // FALTA VERIFICAR A TRATATIVA COMO SERA ATUALIZAD O LANÇAMENTO FINANCEIRO
            $repository->updatePagarCobranca($id_lancamento);
            return [
                'sucesso' => true,
                'data' => ['id_lancamento' => $id_lancamento],
                'http_code' => 204
            ];
        }

        // Extrai erros da resposta da API
        $errosApi = $this->ExtrairErrosApi($retorno);

        // Salva log apenas em caso de erro
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => $id_lancamento,
            'tipo_operacao' => 'PAGAR_COBRANÇA',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'] ?? '/cobranca/v3/cobrancas/{codigoSolicitacao}/pagar',
            'http_code' => $retorno['http_code'] ?? null,
            'sucesso' => false,
            'mensagem_api' => $retorno['body']['detail'] ?? $retorno['body']['title'] ?? null,
            'json_retorno' => $retorno['response_raw'] ?? null,
            'user_id' => $this->m_userid
        ]);

        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao pagar a cobrança na API Inter',
                        'detail' => 'Verifique o código de solicitação',
                        'violacoes' => $errosApi],
            'http_code' => 500
        ];
    }


    /**
     * Recupera a coleção de cobranças na API Inter
     * @param array $dados Dados da consulta
     * @return array Retorno da API
     */
    function processaRecuperarColecaoCobranca(array $dados) : array {

        // =====================================================================
        // MOCKUP PARA TESTE DO FRONTEND (REMOVER EM PRODUÇÃO)
        // Ativar enviando no POST: dados[mockup] = 1
        // =====================================================================
        if (!empty($dados['mockup'])) {
            $json_builder = new c_api_inter_json_builder();

            $paginaAtual    = isset($dados['paginaAtual'])    ? max(0, intval($dados['paginaAtual']))    : 0;
            $itensPorPagina = isset($dados['itensPorPagina']) ? max(1, intval($dados['itensPorPagina'])) : 20;
            $totalMockup    = isset($dados['totalMockup'])    ? max(0, intval($dados['totalMockup']))    : 47;

            $mockup = $json_builder->getMockupColecaoCobranca([
                'paginaAtual'    => $paginaAtual,
                'itensPorPagina' => $itensPorPagina,
                'totalMockup'    => $totalMockup,
            ]);

            // Sintetiza query_array mínimo para a paginação reutilizar
            $_SESSION['json_recuperar_colecao_inter'] = [
                'paginacao.paginaAtual'    => $paginaAtual,
                'paginacao.itensPorPagina' => $itensPorPagina,
            ];

            // Marca a sessão como mockup para que processaAlterarPagina volte aqui
            $_SESSION['mockup_recuperar_colecao_inter'] = [
                'totalMockup'    => $totalMockup,
                'itensPorPagina' => $itensPorPagina,
            ];

            return $mockup;

        }
        // =====================================================================
        // FIM DO MOCKUP
        // =====================================================================

        $repository = new c_api_inter_repository();
        $dados_repository = $repository->getDadosRecuperarColecaoCobranca($dados);

        // Se não foi encontrado o header da conta corrente, retorna erro
        if(!$dados_repository["contaCorrenteHeader"]) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Conta bancária não encontrada!',
                            'detail' => 'Verifique qual conta bancária foi informada.',
                            'errors' => ['Dados da conta corrente não encontrados na base de dados']],
                'http_code' => 403
            ];
        }

        // Monta o JSON para a consulta
        $json_builder = new c_api_inter_json_builder();
        $json_validate = $json_builder->jsonRecuperarColecaoCobranca($dados_repository);

        // Salva o JSON enviado na Sessão para paginação
        $_SESSION['json_recuperar_colecao_inter'] = $json_validate['query_array'];

        // Garante que a paginação não tente usar mockup de uma consulta anterior
        unset($_SESSION['mockup_recuperar_colecao_inter']);

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados);

        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $dados['conta_bancaria']);

        if($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar credenciais da API do Bradesco',
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        // Inicializa a requisição para recuperar a coleção de cobranças
        $curl = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        $retorno = $curl->recuperarColecaoCobranca($json_validate['query_array'], $dados_repository["contaCorrenteHeader"]);

        // Fluxo de sucesso
        if($retorno['http_code'] == 200) {
            return [
                'sucesso' => true,
                'data' => $retorno['body'],
                'http_code' => 200
            ];
        }

        // Tratamento para login invalido
        if($retorno['http_code'] == 401) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Login inválido!',
                            'detail' => 'Verifique se o Client ID, Client Secret e o certificado público estão corretos.',
                            'violacoes' => []],
                'http_code' => 401
            ];
        }

        // Fluxo de erro
        $errosApi = $this->ExtrairErrosApi($retorno);

        // Salva log apenas em caso de erro
        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => null,
            'tipo_operacao' => 'RECUPERAR_COLEÇÃO_DE_COBRANÇAS',
            'ambiente' => $ambiente,
            'endpoint' => $retorno['endpoint'] ?? '/cobranca/v3/cobrancas',
            'http_code' => $retorno['http_code'] ?? null,
            'sucesso' => false,
            'mensagem_api' => $retorno['body']['detail'] ?? $retorno['body']['title'] ?? null,
            'json_retorno' => $retorno['response_raw'] ?? null,
            'user_id' => $this->m_userid
        ]);

        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao consultar a coleção de cobranças na API Inter',
                        'detail' => 'Verifique os dados da consulta',
                        'violacoes' => $errosApi['violacoes'] ?? []],
            'http_code' => 400
        ];
    }


    /**
     * Altera a página da consulta de coleção de cobranças na API Inter.
     *
     * Lê da sessão o query_array da última consulta (salvo em
     * `processaRecuperarColecaoCobranca`), recupera a página atual, calcula
     * a próxima ou anterior com base em $direcao, atualiza o query_array,
     * salva novamente em sessão e refaz a chamada para a API. O header da
     * conta corrente é resolvido em banco a partir do parâmetro recebido
     * (mesmo padrão de `processaCancelarCobranca`).
     *
     * @param array $dados Dados da consulta
     * @return array Retorno no mesmo formato de processaRecuperarColecaoCobranca
     */
    function processaAlterarPagina(array $dados) : array {

        $direcao        = $dados['direcao'] ?? '';
        $conta_bancaria = $dados['conta_bancaria'] ?? '';

        // Recupera o JSON salvo na sessão (query_array da última consulta)
        $query_array = $_SESSION['json_recuperar_colecao_inter'] ?? null;

        if (empty($query_array) || !is_array($query_array)) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Consulta não inicializada',
                            'detail' => 'Realize uma consulta de cobranças antes de paginar',
                            'violacoes' => []],
                'http_code' => 400
            ];
        }

        // Página atual gravada no query_array (paginação base-0)
        $paginaAtual = isset($query_array['paginacao.paginaAtual'])
            ? max(0, intval($query_array['paginacao.paginaAtual']))
            : 0;

        // Calcula a nova página conforme a direção informada
        switch (strtoupper(trim($direcao))) {
            case 'NEXT':
                $novaPagina = $paginaAtual + 1;
                break;
            case 'PREVIOUS':
                $novaPagina = max(0, $paginaAtual - 1);
                break;
            default:
                return [
                    'sucesso' => false,
                    'erros' => ['title' => 'Direção de paginação inválida',
                                'detail' => "Direção '{$direcao}' não suportada. Use 'previous' ou 'next'.",
                                'violacoes' => []],
                    'http_code' => 400
                ];
        }

        // Atualiza a página no query_array 
        $query_array['paginacao.paginaAtual'] = $novaPagina;

        // =====================================================================
        // MOCKUP PARA TESTE DO FRONTEND (REMOVER EM PRODUÇÃO)
        // Quando a consulta inicial foi feita em modo mockup, mantém o fluxo
        // de mockup também na paginação.
        // =====================================================================
        $mockup_session = $_SESSION['mockup_recuperar_colecao_inter'] ?? null;
        if (!empty($mockup_session)) {
            $json_builder = new c_api_inter_json_builder();
            return $json_builder->getMockupColecaoCobranca([
                'paginaAtual'    => $novaPagina,
                'itensPorPagina' => $query_array['paginacao.itensPorPagina'] ?? ($mockup_session['itensPorPagina'] ?? 20),
                'totalMockup'    => $mockup_session['totalMockup'] ?? 47,
            ]);
        }
        // =====================================================================
        // FIM DO MOCKUP
        // =====================================================================

        // Conta bancária é obrigatória para buscar o header em banco
        if ($conta_bancaria === '') {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Conta bancária não informada',
                            'detail' => 'Informe a conta bancária para localizar o header da conta corrente',
                            'violacoes' => []],
                'http_code' => 400
            ];
        }

        // Busca o header da conta corrente em banco (mesma estratégia do processaCancelarCobranca)
        $repository       = new c_api_inter_repository();
        $dados_repository = $repository->getDadosRecuperarColecaoCobranca(['conta_bancaria' => $conta_bancaria]);

        if (empty($dados_repository) || empty($dados_repository['contaCorrenteHeader'])) {
            return [
                'sucesso' => false,
                'erros' => ['title' => 'Conta Corrente Header não encontrada',
                            'detail' => 'Verifique o cadastro da conta corrente e digito verificador',
                            'violacoes' => []],
                'http_code' => 403
            ];
        }

        $conta_corrente_header = $dados_repository['contaCorrenteHeader'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($dados);

        $curl = new c_api_inter_curl($ambiente, $this->m_empresaid);

        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $dados['conta_bancaria']);

        // Define as credenciais da API do Inter
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        $retorno = $curl->recuperarColecaoCobranca($query_array, $conta_corrente_header);

        // Fluxo de sucesso
        if ($retorno['http_code'] == 200) {

            // Atualiza a página no query_array e regrava na sessão para a próxima paginação
            $_SESSION['json_recuperar_colecao_inter'] = $query_array;

            return [
                'sucesso' => true,
                'data' => $retorno['body'],
                'http_code' => 200
            ];
        }

        // Fluxo de erro
        $errosApi = $this->ExtrairErrosApi($retorno);

        $repository->insertLog([
            'banco' => '77',
            'id_lancamento' => null,
            'tipo_operacao' => 'ALTERAR_PAGINA_COLEÇÃO_DE_COBRANÇAS',
            'ambiente' => $retorno['ambiente'] ?? 'sandbox',
            'endpoint' => $retorno['endpoint'] ?? '/cobranca/v3/cobrancas',
            'http_code' => $retorno['http_code'] ?? null,
            'sucesso' => false,
            'mensagem_api' => $retorno['body']['detail'] ?? $retorno['body']['title'] ?? null,
            'json_retorno' => $retorno['response_raw'] ?? null,
            'user_id' => $this->m_userid
        ]);

        return [
            'sucesso' => false,
            'erros' => ['title' => 'Erro ao alterar a página na API Inter',
                        'detail' => 'Verifique os dados da consulta',
                        'violacoes' => $errosApi['violacoes'] ?? []],
            'http_code' => 400
        ];
    }



    // =========================================================================
    // FUNCTIONS SUPPORTING
    // =========================================================================

    /**
     * Retorna a resposta da API
     * @param bool $sucesso Sucesso da operação
     * @param int $http_code Código HTTP
     * @param array $erros Erros da operação
     * @return array Resposta da API
     */
    private function resposta(bool $sucesso, int $http_code, array $erros = []): array
    {
        return [
            'sucesso'   => $sucesso,
            'erros'     => $erros,
            'http_code' => $http_code,
        ];
    }

    /**
     * Retorna a resposta de sucesso da API
     * @param mixed $dados Dados da operação
     * @param int $http_code Código HTTP
     * @return array Resposta de sucesso da API
     */
    private function sucesso(mixed $dados = [], int $http_code = 200): array
    {
        return $this->resposta(
            sucesso: true,
            http_code: $http_code,
            erros: $dados,
        );
    }

    /**
     * Retorna a resposta de erro da API
     * @param string $title Título do erro
     * @param string $detail Detalhe do erro
     * @param int $http_code Código HTTP
     * @param array $violacoes Violacões do erro
     * @return array Resposta de erro da API
     */
    private function erro(string $title, string $detail, int $http_code = 400, array $violacoes = []): array
    {
        return $this->resposta(
            sucesso: false,
            http_code: $http_code,
            erros: [
                'title'     => $title,
                'detail'    => $detail,
                'violacoes' => $violacoes,
            ]
        );
    }


    /**
     * Extrai os erros da resposta da API
     * @param array $retorno Resposta da API
     * @return array Erros extraídos
     */
    private function ExtrairErrosApi($retorno) 
    {   
        $http_code = $retorno['http_code'] ?? 0;
        $body      = is_array($retorno['body'] ?? []) ? $retorno['body'] : [];
        
        return match(true) {
            $http_code === 400 => $this->extrairErros400($body),
            $http_code === 401 => $this->juntarTituloDetalhe($body),
            $http_code === 403 => $this->juntarTituloDetalhe($body),
            $http_code === 404 => $this->juntarTituloDetalhe($body),
            $http_code === 500 => $this->juntarTituloDetalhe($body),
            default            => []
        };

    }


    /**
     * Extrai os erros da resposta da API
     * @param array $body Resposta da API
     * @return array Erros extraídos
     */
    private function extrairErros400(array $body): array
    {
        $erros = [];

        $erros['title'] = $body['title'] ?? '';
        $erros['detail'] = $body['detail'] ?? '';

        foreach ($body['violacoes'] ?? [] as $v) {
            if (!is_array($v)) continue;

            $propriedade = preg_replace('/^.*\.body\./i', '', trim($v['propriedade'] ?? ''));
            $valor       = trim($v['valor'] ?? '');
            $razao       = trim($v['razao'] ?? '');

            $partes = array_filter([
                $propriedade,
                $valor !== '' ? "valor: {$valor}" : '',
                $razao,
            ]);

            if ($partes) {
                $erros['violacoes'][] = implode(': ', $partes);
            }
        }

        return $erros;
    }

    /**
     * Junta o título e o detalhe da resposta da API
     * @param array $body Resposta da API
     * @return string Título e detalhe juntos
     */
    private function juntarTituloDetalhe(array $body): string
    {
        $titulo  = trim($body['title']  ?? '');
        $detalhe = trim($body['detail'] ?? '');

        return implode(': ', array_filter([$titulo, $detalhe]));
    }

    /**
     * Extrai a situação da resposta da API
     * @param string $mensagem Mensagem da resposta da API
     * @return string Situação extraída
     */
    private function extrairSituacao(string $mensagem): ?string
    {
        preg_match('/situação\s+([A-Z_]+)/', $mensagem, $matches);
        return $matches[1] ?? null;
    }

}