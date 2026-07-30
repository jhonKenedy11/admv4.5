<?php

/**
 * @package   astecv3
 * @name      c_api_bradesco_service
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 */

$dir = dirname(__FILE__);

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/c_api_bradesco_json_builder.php");
include_once($dir . "/c_api_bradesco_repository.php");
include_once($dir . "/c_api_bradesco_curl.php");

class c_api_bradesco_service extends c_user
{

    public $conta_bancaria = 0;

    /**
     * Construtor - carrega dados do usuário da sessão
     */
    function __construct()
    {
        // Carrega o usuário da sessão se disponível
        if (isset($_SESSION['user_array'])) {
            $this->from_array($_SESSION['user_array']);
        }
    }

    /**
     * Processa o registro de um boleto na API do Bradesco
     * 
     * @param int $id_lancamento ID do lançamento
     * @return array
     */
    function processaRegistraBoleto(int $id_lancamento)
    {

        // Instancia os objetos necessários
        $repository   = new c_api_bradesco_repository();
        $json_builder = new c_api_bradesco_json_builder();

        // Obtém dados do boleto do banco
        $dados = $repository->getDadosRegistraBoleto($id_lancamento);

        if (!$dados) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar dados do boleto',
                'erros' => [],
                'http_code' => 422 // Erro de validação local
            ];
        }

        // Define a conta bancária obtido no banco de dados
        $this->conta_bancaria = $dados['CONTA_BANCARIA'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // Cria o objeto cURL para a consulta de títulos pendentes e realiza a consulta
        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Monta e valida o JSON
        $json_validate = $json_builder->jsonRegistraBoleto($dados);

        // Se a validação dos dados for falha, salva log e retorna erro
        if ($json_validate['sucesso'] === false) {
            // Salva log de erro de validação local
            $repository->insertLog([
                'id_lancamento' => $id_lancamento,
                'tipo_operacao' => 'REGISTRO_BOLETO',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-registro/v1/cobranca',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados do boleto',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Erro na validação dos dados do boleto',
                    'detail' => 'Verifique os dados informados antes do envio!'
                ],
                'erros' => $json_validate['erros'],
                'http_code' => 999 // Erro interno
            ];
        }


        // Consulta as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, $id_lancamento);

        // Se as credenciais não foram encontradas retorna erro
        if ($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar credenciais da API do Bradesco',
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        // Define as credenciais da API do Bradesco no objeto cURL
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);


        // Envia o JSON pronto para a API do Bradesco
        $retorno = $curl->registrarBoleto($json_validate['dados']);

        // Se o retorno não for 200, salva log e retorna erro
        if ($retorno["http_code"] !== 200) {

            // Extrai erros de validação da API (se houver)
            $errosApi = $this->extrairErrosApi($retorno);

            // Salva log SEMPRE (sucesso ou erro)
            $repository->insertLog([
                'id_lancamento' => $id_lancamento,
                'tipo_operacao' => 'REGISTRO_BOLETO',
                'ambiente' => $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-registro/v1/cobranca',
                'http_code' => $retorno['http_code'] ?? null,
                'sucesso' => $retorno['sucesso'] ?? false,
                'cod_retorno_api' => $retorno['status_header']['codigo'] ?? ($retorno['body']['codigo'] ?? null),
                'mensagem_api' => $retorno['status_header']['descricao'] ?? ($retorno['body']['mensagem'] ?? $retorno['mensagem'] ?? null),
                'erros_validacao' => $errosApi,
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao registrar boleto',
                'erros' => $errosApi,
                'http_code' => $retorno['http_code'] // Dados invalidos
            ];
        }

        $dadosSalvar = [
            'id_lancamento' => $id_lancamento,
            'http_code' => $retorno['http_code'],
            'response_array' => $retorno['body'],
            'json_retorno_completo' => $retorno['response_raw'],
            'created_user' => $this->m_userid
        ];

        // Insere o registro na tabela de API Bradesco
        $id_tabela_api_bradesco = $repository->insertRegistraBoleto($dadosSalvar);

        // Verifica se a tabela API Bradesco foi atualizada para enviar para o frontend
        $tabela_api_atualizada =  $id_tabela_api_bradesco ? true : false;

        // Atualiza o lançamento financeiro
        $update_lancamento = $repository->updateLancamento($id_tabela_api_bradesco, $retorno['body'], $id_lancamento, $this->m_userid);

        // Verifica se o lançamento financeiro foi atualizado para enviar para o frontend
        $lancamento_atualizado = $update_lancamento ? true : false;

        return [
            'sucesso' => true,
            'mensagem' => 'Boleto registrado com sucesso',
            'erros' => [],
            'http_code' => $retorno['http_code'],
            'id_tabela_api_bradesco' => $id_tabela_api_bradesco,
            'meta' => [
                'update_lancamento' => $lancamento_atualizado,
                'update_tabela_api_bradesco' => $tabela_api_atualizada
            ]
        ];
    }

    /**
     * Processa a baixa de um título na API do Bradesco
     * 
     * @param int $id_tabela_api ID do título na API Bradesco
     * @param int $id_lancamento ID do lançamento
     * @return array
     */
    function processaBaixaTitulo(int $id_lancamento, int $id_tabela_api)
    {

        // Instancia os objetos necessários
        $repository   = new c_api_bradesco_repository();
        $json_builder = new c_api_bradesco_json_builder();

        // Obtém dados para baixa do título do banco
        $dados = $repository->getDadosBaixaTitulo($id_lancamento, $id_tabela_api);

        if (!$dados) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar dados para baixa do título',
                'erros' => ['Lançamento não encontrado ou sem registro de boleto na API Bradesco!'],
                'http_code' => 422 // Erro de validação local
            ];
        }

        // Define a conta bancária obtido no banco de dados
        $this->conta_bancaria = $dados['CONTA_BANCARIA'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // Consulta as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, $id_lancamento);

        // Cria o objeto cURL para a baixa de título e realiza a baixa
        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco no objeto cURL
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        // Monta e valida o JSON
        $json_validate = $json_builder->jsonBaixaTitulo($dados);


        if (!$json_validate['sucesso']) {
            // Salva log de erro de validação local
            $repository->insertLog([
                'id_lancamento' => $id_lancamento,
                'tipo_operacao' => 'BAIXA_TITULO',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-baixa/v1/baixar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para baixa do título',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Erro na validação dos dados para baixa do título',
                    'detail' => 'Verifique os dados informados antes do envio!'
                ],
                'erros' => $json_validate['erros'],
                'http_code' => 999 // Erro interno
            ];
        }

        // Envia o JSON para a API do Bradesco
        $retorno = $curl->baixarTitulo($json_validate['dados']);

        if ($retorno['http_code'] !== 200) {

            // Extrai erros de validação da API (se houver)
            $errosApi = $this->extrairErrosApi($retorno);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao baixar título no Bradesco',
                'erros' => $errosApi,
                'http_code' => $retorno['http_code']
            ];
        }

        // Salva o registro principal no banco se foi sucesso
        $dadosSalvar = [
            'id_lancamento' => $id_lancamento,
            'id_tabela_api_bradesco' => $id_tabela_api,
            'http_code' => $retorno['http_code'],
            'response_array' => $retorno['body']['dados'],
            'json_retorno_completo' => $retorno['response_raw'],
            'updated_user' => $this->m_userid,
            'situacao' => 'B'
        ];

        // Salva o registro na tabela de API Bradesco
        $tabela_api_atualizada = $repository->updateTabelaApi($dadosSalvar);

        // Atualiza o lançamento financeiro
        $lancamento_atualizado = $repository->updateLancamentoBaixado($dadosSalvar);


        return [
            'sucesso' => true,
            'mensagem' => 'Baixa do título realizada com sucesso',
            'erros' => [],
            'http_code' => $retorno['http_code'],
            'meta' => [
                'update_lancamento' => $lancamento_atualizado,
            ]
        ];
    }


    /**
     * Processa a baixa de um título consolidado na API do Bradesco
     * 
     * @param array $dados Dados para baixa de título consolidado
     * @return array
     */
    function processaBaixaTituloConsolidacao($dados)
    {
        // Instancia os objetos necessários
        $repository   = new c_api_bradesco_repository();
        $json_builder = new c_api_bradesco_json_builder();

        // Popula os dados da baixa
        $centro_custo   = $dados['centro_custo'] ?? null;
        $conta_bancaria = $dados['conta'] ?? $dados['conta_bancaria'] ?? null;
        $nosso_numero   = $dados['nosso_numero'] ?? null;

        if (empty($centro_custo) || empty($conta_bancaria)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Dados incompletos para baixa do título',
                'erros' => ['Informe centro de custo e conta bancária'],
                'http_code' => 422
            ];
        }

        $dadosBase = $repository->getDadosBaixaTituloConsolidacao($centro_custo, $conta_bancaria, $nosso_numero);

        // Define a conta bancária obtido no banco de dados
        $this->conta_bancaria = $dadosBase['CONTA_BANCARIA'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        if (empty($dadosBase)) {
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'BAIXA_TITULO_CONSOLIDACAO',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-baixa/v1/baixar',
                'sucesso' => false,
                'mensagem_api' => 'Não foi possível obter dados da conta/centro de custo para baixa',
                'json_enviado' => $dados,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Não foi possível obter dados da conta/centro de custo',
                'erros' => ['Verifique centro de custo e conta bancária selecionados'],
                'http_code' => 422
            ];
        }

        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        $json_validate = $json_builder->jsonBaixaTitulo($dadosBase);

        if (!$json_validate['sucesso']) {
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'BAIXA_TITULO_CONSOLIDACAO',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-baixa/v1/baixar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para baixa do título',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dadosBase,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro na validação dos dados para baixa do título',
                'erros' => $json_validate['erros'],
                'http_code' => 422
            ];
        }

        $retorno = $curl->baixarTitulo($json_validate['dados']);

        if ($retorno['http_code'] !== 200) {

            $errosApi = $this->extrairErrosApi($retorno);

            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'BAIXA_TITULO_CONSOLIDACAO',
                'ambiente' => $retorno['ambiente'] ?? $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-baixa/v1/baixar',
                'http_code' => $retorno['http_code'] ?? null,
                'sucesso' => $retorno['sucesso'] ?? false,
                'cod_retorno_api' => $retorno['status'] ?? ($retorno['body']['codigo'] ?? null),
                'mensagem_api' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? null),
                'erros_validacao' => $errosApi,
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao baixar título na API Bradesco',
                'erros' => $errosApi,
                'http_code' => $retorno['http_code'] ?? null
            ];
        }

        return $retorno;
    }


    function processaAlteraTitulo($dados)
    {
        $repository = new c_api_bradesco_repository();
        $json_builder = new c_api_bradesco_json_builder();
        $curl = new c_api_bradesco_curl('producao', $this->m_empresaid);

        // Suporta: processaAlteraTitulo(int $id_lancamento) ou processaAlteraTitulo(array $dados)
        $idLancamento = null;
        if (is_numeric($dados)) {
            $idLancamento = (int)$dados;
            $dadosParaRepo = ['id_lancamento' => $idLancamento];
        } else if (is_array($dados)) {
            $idLancamento = isset($dados['id_lancamento']) ? (int)$dados['id_lancamento'] : (isset($dados['idLancamento']) ? (int)$dados['idLancamento'] : null);
            $dadosParaRepo = $dados;
        } else {
            $dadosParaRepo = [];
        }

        $dadosBase = $repository->getDadosAlteraTitulo($dadosParaRepo);

        if (empty($dadosBase)) {
            $repository->insertLog([
                'id_lancamento' => $idLancamento,
                'tipo_operacao' => 'ALTERA_TITULO',
                'ambiente' => 'producao',
                'endpoint' => '/boleto/cobranca-alteracao/v1/alterar',
                'sucesso' => false,
                'mensagem_api' => 'Não foi possível encontrar os dados para alteração do título (getDadosAlteraTitulo vazio)',
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Não foi possível encontrar os dados para alteração do título',
                    'detail' => 'Verifique o lançamento informado!'
                ],
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        // Monta e valida JSON
        $json_validate = $json_builder->jsonAlteraTitulo($dadosBase);
        if (!$json_validate['sucesso']) {
            $repository->insertLog([
                'id_lancamento' => $idLancamento,
                'tipo_operacao' => 'ALTERA_TITULO',
                'ambiente' => 'producao',
                'endpoint' => '/boleto/cobranca-alteracao/v1/alterar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para alteração do título',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dadosBase,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Erro na validação dos dados para alteração do título',
                    'detail' => 'Verifique os dados informados antes do envio!'
                ],
                'erros' => $json_validate['erros'],
                'http_code' => 999 // Erro interno
            ];
        }

        // Envia para API
        $retorno = $curl->alterarTitulo($json_validate['dados']);
        $errosApi = $this->extrairErrosApi($retorno);

        // Log SEMPRE
        $repository->insertLog([
            'id_lancamento' => $idLancamento,
            'tipo_operacao' => 'ALTERA_TITULO',
            'ambiente' => $retorno['ambiente'] ?? 'producao',
            'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-alteracao/v1/alterar',
            'http_code' => $retorno['http_code'] ?? null,
            'sucesso' => $retorno['sucesso'] ?? false,
            'cod_retorno_api' => $retorno['status'] ?? ($retorno['body']['codigo'] ?? null),
            'mensagem_api' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? $retorno['mensagem'] ?? null),
            'erros_validacao' => $errosApi,
            'json_enviado' => $json_validate['dados'],
            'json_retorno' => $retorno['body'] ?? $retorno,
            'user_id' => $this->m_userid
        ]);

        if (!empty($errosApi)) {
            $retorno['erros_api'] = $errosApi;
        }

        if ($retorno['sucesso'] ?? false) {
            return [
                'sucesso' => true,
                'mensagem' => $retorno['mensagem'] ?? 'Alteração efetuada com sucesso',
                'dados' => [
                    'status' => $retorno['status'] ?? ($retorno['body']['status'] ?? 200),
                    'transacao' => $retorno['transacao'] ?? null,
                    'mensagem' => $retorno['mensagem'] ?? null
                ],
                'causa' => null
            ];
        }

        return [
            'sucesso' => false,
            'mensagem' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? 'Falha ao alterar título'),
            'dados' => $retorno['body'] ?? null,
            'causa' => $retorno['causa'] ?? ($retorno['body']['causa'] ?? null),
            'erros_api' => $errosApi
        ];
    }


    /**
     * Processa consulta de título unitário na API Bradesco.
     */
    function processaConsultaTituloUnitario(array $dados)
    {

        $repository = new c_api_bradesco_repository();
        $json_builder = new c_api_bradesco_json_builder();

        // Obtém o ID da tabela API
        $id_tabela_api = $dados['id_tabela_api'];
        $id_lancamento = $dados['id_lancamento'];

        if (empty($id_tabela_api) || empty($id_lancamento)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Falha ao consultar título',
                'erros' => ['ID da tabela API e ID do lançamento não encontrados'],
                'http_code' => 422
            ];
        }

        // Obtém os dados para consulta de título unitário
        $dados_repository = $repository->getConsultaTituloUnitario($id_tabela_api);

        if (empty($dados_repository)) {

            return [
                'sucesso' => false,
                'mensagem' => 'Falha ao consultar título',
                'erros' => ['Não foi possível encontrar os dados para consulta'],
                'http_code' => 422
            ];
        }

        // Define a conta bancária atraves da consulta do banco
        $this->conta_bancaria = $dados_repository['CONTA_BANCARIA'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $this->conta_bancaria);

        if ($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar credenciais da API do Bradesco',
                'erros' => [],
                'http_code' => 422 // Erro de validação local
            ];
        }

        // Cria o objeto cURL para a consulta de título unitário
        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);


        $json_validate = $json_builder->jsonConsultaTituloUnitario($dados_repository);
        if (!$json_validate['sucesso']) {
            $repository->insertLog([
                'id_lancamento' => $id_tabela_api,
                'tipo_operacao' => 'CONSULTA_TITULO_UNITARIO',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/consultar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para consulta de título unitário',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados_repository,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro de validação interna na consulta de título unitário',
                'erros' => $json_validate['erros'],
                'http_code' => 999 // Erro de validação local
            ];
        }

        $retorno = $curl->consultarTituloUnitario($json_validate['dados']);

        if ($retorno["http_code"] !== 200) {

            $errosApi = $this->extrairErrosApi($retorno);

            $repository->insertLog([
                'id_lancamento' => $id_tabela_api,
                'tipo_operacao' => 'CONSULTA_TITULO_UNITARIO',
                'ambiente' => $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-consulta/v1/consultar',
                'http_code' => $retorno['http_code'] ?? null,
                'sucesso' => $retorno['sucesso'] ?? false,
                'cod_retorno_api' => $retorno['status'] ?? ($retorno['body']['codigo'] ?? null),
                'mensagem_api' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? $retorno['mensagem'] ?? null),
                'erros_validacao' => $errosApi,
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar título unitário',
                'erros' => $errosApi['erros'],
                'http_code' => $retorno['http_code']
            ];
        }

        // Atualiza FIN_API_BRADESCO com retorno da consulta unitária
        $tabela_api_atualizada = $repository->updateTabelaApiConsultaTituloUnitario([
            'id_tabela_api' => $id_tabela_api,
            'response_array' => $retorno['body'],
            'id_conta' => $this->conta_bancaria,
            'updated_user' => $this->m_userid
        ]);

        // Se a atualização da tabela API Bradesco falhou, retorna erro
        if ($tabela_api_atualizada['http_code'] === 422) {
            // Salva log de erro
            return [
                'sucesso' => false,
                'mensagem' => $tabela_api_atualizada['mensagem'],
                'erros' => [$tabela_api_atualizada['erros']],
                'http_code' => 422
            ];
        }

        // Atualiza o lançamento financeiro
        $tabela_lancamento = $repository->updateLancamentoConsultaTituloUnitario([
            'id_lancamento' => $id_lancamento,
            'titulo' => $retorno['body']['titulo'] ?? null,
            'id_conta' => $this->conta_bancaria,
            'updated_user' => $this->m_userid
        ]);

        // Se a atualização do lançamento financeiro falhou, retorna erro
        if ($tabela_lancamento['http_code'] === 422) {

            return [
                'sucesso' => false,
                'mensagem' => $tabela_lancamento['mensagem'],
                'erros' => $tabela_lancamento['erros'],
                'http_code' => 422
            ];
        }

        return [
            'sucesso' => true,
            'mensagem' => 'Consulta realizada com sucesso',
            'http_code' => $retorno['http_code'],
            'meta' => [
                'update_lancamento' => $tabela_lancamento
            ]
        ];
    }

    /**
     * Processa a consulta de título pendente na API do Bradesco
     * 
     * @param array $dados Dados para consulta de título pendente
     * @return array
     */
    function processaConsultaTituloPendente(array $dados)
    {

        // Obtém os dados para consulta de título pendente
        $repository = new c_api_bradesco_repository();
        $dados_repo = $repository->getDadosConsultaTituloPendente($dados);

        // Define a conta bancária recebida do frontend
        $this->conta_bancaria = $dados['conta_bancaria'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // Se os dados não foram encontrados, salva log e retorna erro
        if (!$dados_repo) {
            // Salva log de erro
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULO_PENDENTE',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/consultar',
                'sucesso' => false,
                'mensagem_api' => 'Erro de validação interna na consulta de título pendente',
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro interno ao buscar os dados, entre em contato com o suporte'],
                'http_code' => 422 // Erro de validação local

            ];
        }

        // Cria o objeto JSON para a consulta de títulos pendentes e valida os dados
        $json_builder = new c_api_bradesco_json_builder();
        $json_validate = $json_builder->jsonConsultaTituloPendente($dados_repo);

        // Se a validação dos dados for falha, salva log e retorna erro
        if (!$json_validate['sucesso']) {

            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULO_PENDENTE',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/consultar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para consulta de título pendente',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $json_validate,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro de validação interna na consulta de título pendente',
                'erros' => $json_validate['erros'],
                'http_code' => 422 // Erro de validação local
            ];
        }

        // Atualiza a página no query_array e regrava na sessão para a próxima paginação
        $json_validate['tipo_consulta'] = 'titulos_pendentes';
        $_SESSION['json_consulta_titulos_bradesco'] = $json_validate;

        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $this->conta_bancaria);

        if ($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro ao consultar credenciais da API do Bradesco, entre em contato com o suporte'],
                'http_code' => 422 // Erro interno
            ];
        }

        // Cria o objeto cURL para a consulta de títulos pendentes e realiza a consulta
        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        $retorno = $curl->consultarPendentes($json_validate['dados']);

        // Extrai erros de validação da API (se houver)
        $errosApi = $this->extrairErrosApiConsultaTitulos($retorno);

        // Se a consulta não foi realizada com sucesso, salva log e retorna erro
        if ($retorno["http_code"] !== 200) {

            // Salva log
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULO_PENDENTE',
                'ambiente' => $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-pendente/v1/listar',
                'http_code' => $retorno['http_code'] ?? null,
                'sucesso' => $retorno['sucesso'] ?? false,
                'cod_retorno_api' => $retorno['status'] ?? ($retorno['body']['codigo'] ?? null),
                'mensagem_api' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? $retorno['mensagem'] ?? null),
                'erros_validacao' => $retorno['causa'],
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => $errosApi['mensagem'],
                'erros' => $errosApi['erros'],
                'http_code' => $retorno['http_code']
            ];
        }

        return $retorno;
    }

    function processaConsultaTitulosBaixados(array $dados)
    {

        // Obtém os dados para consulta de títulos baixados
        $repository = new c_api_bradesco_repository();
        $dados_repo = $repository->getDadosConsultaTitulosBaixados($dados);

        // Define a conta bancária recebida do frontend
        $this->conta_bancaria = $dados['conta_bancaria'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // Se os dados não foram encontrados, salva log e retorna erro
        if (!$dados_repo) {
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_BAIXADOS',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/baixados',
                'sucesso' => false,
                'mensagem_api' => 'Erro de validação interna na consulta de títulos baixados',
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro interno ao buscar os dados, entre em contato com o suporte'],
                'http_code' => 422 // Erro de validação local
            ];
        }

        $json_builder = new c_api_bradesco_json_builder();
        $json_validate = $json_builder->jsonConsultaTitulosBaixados($dados_repo);

        if (!$json_validate['sucesso']) {
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_BAIXADOS',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/baixados',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para consulta de títulos baixados',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados_repo,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro de validação interna na consulta de títulos baixados',
                'erros' => $json_validate['erros'],
                'http_code' => 422 // Erro de validação local
            ];
        }


        // Salva a consulta na sessão para a próxima paginação
        $json_validate['tipo_consulta'] = 'titulos_baixados';
        $_SESSION['json_consulta_titulos_bradesco'] = $json_validate;


        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $this->conta_bancaria);

        if ($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro ao consultar credenciais da API do Bradesco, entre em contato com o suporte'],
                'http_code' => 422 // Erro de validação interna
            ];
        }

        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        $retorno = $curl->consultarBaixados($json_validate['dados']);
        //$retorno = $curl->consultarBaixados($json_teste);


        if ($retorno["http_code"] !== 200) {

            // Salva log
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_BAIXADOS',
                'ambiente' => $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-consulta/v1/baixados',
                'sucesso' => false,
                'mensagem_api' => 'Erro ao processar consulta de títulos baixados',
                'erros_validacao' => $retorno['causa'],
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            $errosApi = $this->extrairErrosApiConsultaTitulos($retorno);

            return [
                'sucesso' => false,
                'mensagem' => $errosApi['mensagem'],
                'erros' => $errosApi['erros'],
                'http_code' => $retorno['http_code']
            ];
        }

        return $retorno;
    }

    /**
     * Processa a consulta de títulos liquidados na API do Bradesco
     * 
     * @param array $dados Dados para consulta de títulos liquidados
     * @return array
     */
    function processaConsultaTitulosLiquidados(array $dados)
    {

        $repository = new c_api_bradesco_repository();
        $dados_repo = $repository->getDadosConsultaTitulosLiquidados($dados);

        // Define a conta bancária recebida do frontend
        $this->conta_bancaria = $dados['conta_bancaria'];

        // Recupera o ambiente da conta bancária
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        if (!$dados_repo) {
            // Salva log de erro
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_LIQUIDADOS',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/consultar',
                'sucesso' => false,
                'mensagem_api' => 'Não foi possível encontrar os dados para consulta de títulos liquidados',
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro interno ao buscar os dados, entre em contato com o suporte'],
                'http_code' => 422 // Erro de validação local
            ];
        }

        $json_builder = new c_api_bradesco_json_builder();
        $json_validate = $json_builder->jsonConsultaTitulosLiquidados($dados_repo);


        if (!$json_validate['sucesso']) {
            // Salva log de erro de validação local
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_LIQUIDADOS',
                'ambiente' => $ambiente,
                'endpoint' => '/boleto/cobranca-consulta/v1/consultar',
                'sucesso' => false,
                'mensagem_api' => 'Erro na validação dos dados para consulta de títulos liquidados',
                'erros_validacao' => $json_validate['erros'],
                'json_enviado' => $dados_repo,
                'user_id' => $this->m_userid
            ]);

            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro interno ao buscar os dados, entre em contato com o suporte'],
                'http_code' => 422 // Erro de validação local
            ];
        }

        // Consulta e define as credenciais da API do Bradesco
        $credenciais = $repository->getCredenciais($ambiente, 0, $this->conta_bancaria);

        if ($credenciais['sucesso'] === false) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno',
                'erros' => ['Erro ao consultar credenciais da API do Bradesco, entre em contato com o suporte'],
                'http_code' => 422 // Erro interno
            ];
        }

        $curl = new c_api_bradesco_curl($ambiente, $this->m_empresaid);

        // Define as credenciais da API do Bradesco
        $curl->setClientId($credenciais['client_id']);
        $curl->setClientSecret($credenciais['client_secret']);

        // Mock de dados para consulta de títulos liquidados
        // $json_validate['dados'] = json_encode([
        //     "cpfCnpj" => [
        //         "cpfCnpj" => 114383908,
        //         "filial" => 0,
        //         "controle" => 7
        //     ],
        //     "produto" => 9,
        //     "negociacao" => 28560230114,
        //     "dataMovimentoDe" => 21062017,
        //     "dataMovimentoAte" => 21062017,
        //     "dataPagamentoDe" => 14062017,
        //     "dataPagamentoAte" => 14062017,
        //     "origemPagamento" => 0,
        //     "valorTituloDe" => 0,
        //     "valorTituloAte" => 0,
        //     "paginaAnterior" => 0
        // ]);

        // Atualiza a página no query_array e regrava na sessão para a próxima paginação
        $json_validate['tipo_consulta'] = 'titulos_liquidados';
        $_SESSION['json_consulta_titulos_bradesco'] = $json_validate;

        $retorno = $curl->consultarLiquidados($json_validate['dados']);

        // Testa se a consulta foi realizada com sucesso
        if ($retorno["http_code"] !== 200) {
            // Salva log
            $repository->insertLog([
                'id_lancamento' => null,
                'tipo_operacao' => 'CONSULTA_TITULOS_LIQUIDADOS',
                'ambiente' => $ambiente,
                'endpoint' => $retorno['endpoint'] ?? '/boleto/cobranca-consulta/v1/consultar',
                'http_code' => $retorno['http_code'] ?? null,
                'sucesso' => $retorno['sucesso'] ?? false,
                'cod_retorno_api' => $retorno['status'] ?? ($retorno['body']['codigo'] ?? null),
                'mensagem_api' => $retorno['mensagem'] ?? ($retorno['body']['mensagem'] ?? $retorno['mensagem'] ?? null),
                'erros_validacao' => $retorno['causa'],
                'json_enviado' => $json_validate['dados'],
                'json_retorno' => $retorno['body'] ?? $retorno,
                'user_id' => $this->m_userid
            ]);

            $errosApi = $this->extrairErrosApiConsultaTitulos($retorno);

            return [
                'sucesso' => false,
                'mensagem' => $errosApi['mensagem'],
                'erros' => $errosApi['erros'],
                'http_code' => $retorno["http_code"] // Dados invalidos
            ];
        }

        return $retorno;
    }

    /**
     * Processa a alteração de página da consulta de títulos no Bradesco.
     *
     * Reaproveita o filtro original gravado em sessão na consulta inicial
     * (`$_SESSION['json_consulta_titulos_bradesco']`), ajusta o campo
     * `paginaAnterior` do payload conforme a direção informada e refaz a
     * chamada à API correspondente ao `tipo_consulta` original.
     *
     * Fluxo:
     *   1. Recupera o filtro da sessão (`dados` JSON-string + `tipo_consulta`).
     *   2. Decodifica `dados`, ajusta `paginaAnterior` (+1 next / -1 previous).
     *   3. Despacha para o cURL correto conforme `tipo_consulta`.
     *   4. Em caso de sucesso, regrava a sessão com a nova página.
     *   5. Em caso de erro, grava log e devolve mensagem padronizada.
     *
     * @param array $dados { direcao: 'next' | 'previous' }
     * @return array Retorno padronizado com `http_code`, `body`, etc.
     */
    function processaAlterarPagina(array $dados)
    {

        // 1. Recupera o filtro original da consulta inicial gravado na sessão.
        $sessao = $_SESSION['json_consulta_titulos_bradesco'] ?? null;

        if (empty($sessao) || !is_array($sessao)) {
            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Consulta não encontrada na sessão',
                    'detail' => 'Execute uma nova consulta de títulos antes de paginar.'
                ],
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        $tipo_consulta = $sessao['tipo_consulta'] ?? null;
        $direcao       = $dados['direcao']         ?? null;

        $tipos_suportados = ['titulos_pendentes', 'titulos_baixados', 'titulos_liquidados'];
        if (!in_array($tipo_consulta, $tipos_suportados, true)) {
            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Tipo de consulta inválido',
                    'detail' => 'Não foi possível identificar o tipo de consulta na sessão.'
                ],
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        if (!in_array($direcao, ['previous', 'next'], true)) {
            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Direção de paginação inválida',
                    'detail' => 'A direção deve ser "previous" ou "next".'
                ],
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        // 2. Decodifica o payload (gravado como string JSON pelo json_builder).
        $payload = json_decode($sessao['dados'] ?? '', true);
        if (!is_array($payload)) {
            return [
                'sucesso' => false,
                'erro' => [
                    'title' => 'Payload da consulta corrompido',
                    'detail' => 'Não foi possível decodificar os filtros gravados na sessão.'
                ],
                'erros' => [],
                'http_code' => 999 // Erro interno
            ];
        }

        // Calcula a nova página com base na direção (mínimo zero – primeira página).
        $pagina_atual = (int) ($payload['paginaAnterior'] ?? 0);

        $nova_pagina  = $direcao === 'next'
            ? $pagina_atual + 1
            : max(0, $pagina_atual - 1);

        $payload['paginaAnterior'] = $nova_pagina;
        $json_payload              = json_encode($payload);

        // Define a conta bancária recebida do frontend
        $this->conta_bancaria = $dados['conta_bancaria'];

        // Recupera o ambiente da conta bancária
        $repository = new c_api_bradesco_repository();
        $ambiente = $repository->getAmbiente($this->conta_bancaria);

        // 3. Despacha para o cURL conforme o tipo de consulta original.
        $curl    = new c_api_bradesco_curl($ambiente, $this->m_empresaid);
        $retorno = null;
        $endpoint_log = null;

        switch ($tipo_consulta) {
            case 'titulos_pendentes':
                $retorno      = $curl->consultarPendentes($json_payload);
                $endpoint_log = '/boleto/cobranca-consulta/v1/consultar';
                break;
            case 'titulos_baixados':
                $retorno      = $curl->consultarBaixados($json_payload);
                $endpoint_log = '/boleto/cobranca-consulta/v1/baixados';
                break;
            case 'titulos_liquidados':
                $retorno      = $curl->consultarLiquidados($json_payload);
                $endpoint_log = '/boleto/cobranca-consulta/v1/consultar';
                break;
        }

        // 4. Em caso de erro, grava log e devolve mensagem padronizada.
        if (($retorno['http_code'] ?? null) !== 200) {

            $repository = new c_api_bradesco_repository();
            $repository->insertLog([
                'id_lancamento'   => null,
                'tipo_operacao'   => 'ALTERAR_PAGINA_' . strtoupper($tipo_consulta),
                'ambiente'        => $ambiente,
                'endpoint'        => $retorno['endpoint'] ?? $endpoint_log,
                'http_code'       => $retorno['http_code'] ?? null,
                'sucesso'         => $retorno['sucesso']   ?? false,
                'cod_retorno_api' => $retorno['status']    ?? ($retorno['body']['codigo']   ?? null),
                'mensagem_api'    => $retorno['mensagem']  ?? ($retorno['body']['mensagem'] ?? null),
                'erros_validacao' => $retorno['causa']     ?? null,
                'json_enviado'    => $json_payload,
                'json_retorno'    => $retorno['body']      ?? $retorno,
                'user_id'         => $this->m_userid
            ]);

            $erros_api = $this->extrairErrosApiConsultaTitulos($retorno);

            return [
                'sucesso'       => false,
                'http_code'     => $retorno['http_code'],
                'mensagem'      => $erros_api['mensagem'] ?? 'Erro ao alterar a página da consulta',
                'erros'         => $erros_api['erros'],
                'tipo_consulta' => $tipo_consulta
            ];
        }

        // 5. Sucesso – regrava a sessão com a nova página para a próxima paginação.
        $sessao['dados']                            = $json_payload;
        $_SESSION['json_consulta_titulos_bradesco'] = $sessao;

        // Adiciona o tipo de consulta ao retorno para ser usado na resposta
        $retorno['body']['tipo_consulta']           = $tipo_consulta;

        return $retorno;
    }


    /**
     * Extrai erros de validação do retorno da API Bradesco
     * 
     * @param array $retorno Retorno da API
     * @return array Lista de erros
     */
    private function extrairErrosApi($retorno)
    {
        $erros = [];
        $body = $retorno['body'] ?? [];

        // Formato: {"codigoErro": "CBTT0488", "descricaoErro": "CNPJ/CPF INVALIDO"}
        if (isset($body['codigoErro']) || isset($body['descricaoErro'])) {
            $codigo = $body['codigoErro'] ?? '';
            $descricao = $body['descricaoErro'] ?? '';
            $erros[] = trim($codigo . ($codigo && $descricao ? ': ' : '') . $descricao);
        }

        // Formato: errosValidacao
        if (isset($body['errosValidacao']) && is_array($body['errosValidacao'])) {
            foreach ($body['errosValidacao'] as $validacao) {
                if (isset($validacao['erros']) && is_array($validacao['erros'])) {
                    foreach ($validacao['erros'] as $erro) {
                        $erros[] = $erro;
                    }
                }
            }
        }

        // Formato: {"errors":["msg1","msg2",...]}
        if (isset($body['errors']) && is_array($body['errors'])) {
            foreach ($body['errors'] as $erro) {
                if (is_string($erro) && trim($erro) !== '') {
                    $erros[] = trim($erro);
                }
            }
        }

        // Formato: {"mensagem":"texto"}
        if (isset($body['mensagem']) && !$retorno['sucesso']) {
            $erros[] = $body['mensagem'];
        }

        // Formato: {"erro":"texto"}
        if (isset($body['erro']) && is_string($body['erro'])) {
            $msg = trim($body['erro']);
            if ($msg !== '') {
                $erros[] = $msg;
            }
        }

        if (isset($retorno['causa'])) {

            $msg = trim($retorno['causa']);

            if ($msg !== '') {
                $erros[] = $msg;
            }
        }

        return array_values(array_unique(array_filter($erros)));
    }

    /**
     * Extrai erros do retorno da API Bradesco para consultas de títulos.
     *
     * Layout: { "mensagem": "...", "causa": "...", ... }
     *
     * @param array $retorno Retorno padronizado da API.
     * @return array ['mensagem' => string, 'erros' => string[]]
     */
    private function extrairErrosApiConsultaTitulos($retorno)
    {
        $mensagem        = trim($retorno['mensagem'] ?? $retorno['body']['mensagem'] ?? '');
        $causa_completa  = trim($retorno['causa']    ?? $retorno['body']['causa']    ?? '');
        $cauda_explode   = explode(' - ', $causa_completa);
        $causa           = $cauda_explode[1] ?? '';

        return [
            'mensagem' => $mensagem,
            'erros'    => array_values(array_filter([$causa])),
        ];
    }
}
