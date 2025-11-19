<?php
/**
 * @package   astec
 * @name      c_parametro
 * @version   4.5.0
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy Dos Santos Mello <jhon.kened11@hotmail.com>
 * @date      30/05/2025
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_ipm_estrategy.php");
include_once($dir . "/c_nfs_response.php");

//Class c_nota_fiscal_servico
class c_nota_fiscal_servico extends c_user
{
    protected $config;
    protected $dados;
    protected $schemaPath;

    function __construct()
    {   
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);

        $this->config = NULL;
        $this->dados = NULL;
    }

    public function typeFramework( string $origem_dados, ?int $id = null, ?string $json = null)
    {
        $sql = "SELECT CODMUNICIPIO FROM AMB_EMPRESA WHERE EMPRESA = ?";

        $banco = new c_banco();
        $banco->prepare($sql);
        $banco->bind('s',  $this->m_empresaid);
        $banco->execute();

        $resultado = $banco->fetchOneAssoc();

        $codigo_municipio = $resultado["CODMUNICIPIO"];

        $config = $this->getConfigMunicipality($codigo_municipio);

        switch (isset($config["padrao"])) {
            case 'IPM':
                // $origem_dados = 'pedido_servico';
                // $id = 13;
                $objIpm =  new IpmStrategy();

                if($json){
                    $resultado = $objIpm->processForShipping($config, $origem_dados, $id, $json);
                }else{
                    $resultado = $objIpm->processForShipping($config, $origem_dados, $id, null);
                }
                
                // Usar a classe de resposta padronizada
                c_nfs_response::fromResult($resultado);
                return $resultado;

            case 'GINFES':
                //return new GinfesStrategy($config, $dados);
            default:
                throw new \Exception("Municipio nao suportado:  $codigo_municipio");
        }
    }

    public function getConfigMunicipality(string $codigoMunicipio): ?array
    {
        $caminhoJson = __DIR__ . '/../../bib/storage/urls_webservices.json';

        if (!file_exists($caminhoJson)) {
            throw new \Exception("Arquivo de configuração não encontrado: $caminhoJson");
        }

        $conteudo = file_get_contents($caminhoJson);
        $configs = json_decode($conteudo, true);

        if (!is_array($configs)) {
            throw new \Exception("Erro ao decodificar JSON de configuração.");
        }

        return $configs[$codigoMunicipio] ?? null;
    }

/**
     * Salva um evento relacionado à Nota Fiscal de Serviço na tabela
     * EST_NOTA_FISCAL_SERVICO_EVENTOS.
     *
     * Essa função armazena os dados do evento (como emissão, cancelamento, consulta, etc.)
     * incluindo o XML de retorno da requisição feita, que deve ser previamente capturado.
     *
     * @param PDO $pdo Conexão PDO com o banco de dados.
     * @param array $dados Array associativo com os seguintes campos obrigatórios:
     *                     - id_nfs (int): ID da nota fiscal.
     *                     - centro_custo (string): Código do centro de custo (até 11 caracteres).
     *                     - serie (string|null): Série da NFS (até 3 caracteres).
     *                     - numero (int|null): Número da NFS.
     *                     - tipo_evento (string): Tipo de evento (C, E, S, N).
     *                     - codigo_retorno (string|null): Código de retorno da operação (até 10 caracteres).
     *                     - created_user (int): ID do usuário que criou o registro.
     * @param string $xmlRetorno XML de resposta do serviço (pequeno), extraído do corpo da resposta.
     *
     * @return bool Retorna true em caso de sucesso, ou false se ocorrer algum erro na execução.
     */
    function saveEventInvoice(array $dados, string $xmlRetorno): bool
    {



        /*

            '<?xml version="1.0" encoding="ISO-8859-1"?>
            <retorno>
                <mensagem>
                    <codigo>00031 - C�digo do item da lista de servi�o est� preenchido incorretamente.</codigo>
                    <codigo>00034 - Al�quota do servi�o prestado n�o foi preenchida corretamente.</codigo>
                </mensagem>
            </retorno>'

        */

        $sql = "
            INSERT INTO EST_NOTA_FISCAL_SERVICO_EVENTOS (
                ID_NFS,
                CENTRO_CUSTO,
                SERIE,
                NUMERO,
                TIPO_EVENTO,
                CODIGO_RETORNO,
                XML_RETORNO,
                CREATED_USER
            ) VALUES (
                :id_nfs,
                :centro_custo,
                :serie,
                :numero,
                :tipo_evento,
                :codigo_retorno,
                :xml_retorno,
                :created_user
            )
        ";

        try {

            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            $xmlUtf8 = mb_convert_encoding($xmlRetorno, 'UTF-8', 'ISO-8859-1');


            $this->banco->bindValue(':id_nfs',         $dados['id_nfs'],         PDO::PARAM_INT);
            $this->banco->bindValue(':centro_custo',   $dados['centro_custo'],   PDO::PARAM_STR);
            $this->banco->bindValue(':serie',          $dados['serie'],          PDO::PARAM_STR);
            $this->banco->bindValue(':numero',         $dados['numero'],         PDO::PARAM_INT);
            $this->banco->bindValue(':tipo_evento',    $dados['tipo_evento'],    PDO::PARAM_STR);
            $this->banco->bindValue(':codigo_retorno', $dados['codigo_retorno'], PDO::PARAM_STR);
            $this->banco->bindValue(':xml_retorno',    $xmlUtf8,                 PDO::PARAM_STR);
            $this->banco->bindValue(':created_user',   $dados['created_user'],   PDO::PARAM_INT);

            $this->banco->execute();

            if ($this->banco->rowCount() > 0) {
                return true;
            }

        } catch (PDOException $e) {
            // DEV verifique o erro nesse caminho /var/tmp/my-errors.log
            error_log("Erro ao salvar evento NFS: " . $e->getMessage());
            return false;
        }
        
        return false;
    }

    /**
     * Salva uma Nota Fiscal de Serviço na tabela EST_NOTA_FISCAL_SERVICO
     *
     * @param array $dados Array associativo com os dados da nota fiscal
     * @return int|false Retorna o ID da nota fiscal inserida ou false em caso de erro
     */
    function saveNotaFiscalServico(array $dados)
    {
        $sql = "
            INSERT INTO EST_NOTA_FISCAL_SERVICO (
                IDENTIFICADOR_ARQUIVO,
                NUMERO,
                SERIE,
                DATA_EMISSAO,
                HORA_EMISSAO,
                COD_VERIFICADOR_AUTENTICIDADE,
                LINK_NFSE,
                SITUACAO,
                RPS_NUMERO,
                RPS_SERIE,
                RPS_DATA_EMISSAO,
                RPS_HORA_EMISSAO,
                COD_EQUIPAMENTO_AUTOMATICO,
                DATA_FATO_GERADOR,
                OBSERVACAO,
                VALOR_TOTAL,
                VALOR_DESCONTO,
                VALOR_IR,
                VALOR_INSS,
                VALOR_CONTRIBUICAO_SOCIAL,
                VALOR_RPS_RETENCAO,
                VALOR_PIS,
                VALOR_COFINS,
                PRESTADOR_CPFCNPJ,
                PRESTADOR_CIDADE_CODIGO,
                TOMADOR_ID,
                TOMADOR_CPFCNPJ,
                TOMADOR_TIPO,
                TOMADOR_NOME_RAZAO_SOCIAL,
                TOMADOR_NOME_FANTASIA,
                TOMADOR_IE,
                TOMADOR_EMAIL,
                TOMADOR_ENDERECO_INFORMADO,
                TOMADOR_LOGRADOURO,
                TOMADOR_NUMERO_RESIDENCIA,
                TOMADOR_COMPLEMENTO,
                TOMADOR_BAIRRO,
                TOMADOR_CIDADE_CODIGO,
                TOMADOR_CEP,
                TOMADOR_PONTO_REFERENCIA,
                TOMADOR_DDD_FONE_COMERCIAL,
                TOMADOR_FONE_COMERCIAL,
                TOMADOR_DDD_FONE_RESIDENCIAL,
                TOMADOR_FONE_RESIDENCIAL,
                TOMADOR_DDD_FAX,
                TOMADOR_FONE_FAX,
                TOMADOR_IDENTIFICADOR_ESTRANGEIRO,
                TOMADOR_ESTADO_ESTRANGEIRO,
                TOMADOR_PAIS_ESTRANGEIRO,
                PRODUTOS_DESCRICAO,
                PRODUTOS_VALOR_TOTAL,
                FORMA_PAGAMENTO_TIPO,
                CREATED_USER
            ) VALUES (
                :identificador_arquivo,
                :numero,
                :serie,
                :data_emissao,
                :hora_emissao,
                :cod_verificador_autenticidade,
                :link_nfse,
                :situacao,
                :rps_numero,
                :rps_serie,
                :rps_data_emissao,
                :rps_hora_emissao,
                :cod_equipamento_automatico,
                :data_fato_gerador,
                :observacao,
                :valor_total,
                :valor_desconto,
                :valor_ir,
                :valor_inss,
                :valor_contribuicao_social,
                :valor_rps_retencao,
                :valor_pis,
                :valor_cofins,
                :prestador_cpfcnpj,
                :prestador_cidade_codigo,
                :tomador_id,
                :tomador_cpfcnpj,
                :tomador_tipo,
                :tomador_nome_razao_social,
                :tomador_nome_fantasia,
                :tomador_ie,
                :tomador_email,
                :tomador_endereco_informado,
                :tomador_logradouro,
                :tomador_numero_residencia,
                :tomador_complemento,
                :tomador_bairro,
                :tomador_cidade_codigo,
                :tomador_cep,
                :tomador_ponto_referencia,
                :tomador_ddd_fone_comercial,
                :tomador_fone_comercial,
                :tomador_ddd_fone_residencial,
                :tomador_fone_residencial,
                :tomador_ddd_fax,
                :tomador_fone_fax,
                :tomador_identificador_estrangeiro,
                :tomador_estado_estrangeiro,
                :tomador_pais_estrangeiro,
                :produtos_descricao,
                :produtos_valor_total,
                :forma_pagamento_tipo,
                :created_user
            )
        ";

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            $data_fato_gerador = isset($dados["nota_fiscal"]["data_fato_gerador"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["data_fato_gerador"]) : null;
            $rps_data_emissao = isset($dados["nota_fiscal"]["rps_data_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["rps_data_emissao"]) : null;
            $data_emissao = isset($dados["nota_fiscal"]["data_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["data_emissao"]) : null;
            $hora_emissao = isset($dados["nota_fiscal"]["hora_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["hora_emissao"]) : null;

            // Bind dos parâmetros
            $this->banco->bindValue(':identificador_arquivo', isset($dados["nota_fiscal"]["identificador_arquivo"]) ? $dados["nota_fiscal"]["identificador_arquivo"] : null, PDO::PARAM_STR);
            $this->banco->bindValue(':numero', $dados["nota_fiscal"]["numero"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':serie', $dados["nota_fiscal"]["serie"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':data_emissao', $data_emissao, PDO::PARAM_STR);
            $this->banco->bindValue(':hora_emissao', $hora_emissao, PDO::PARAM_STR);
            $this->banco->bindValue(':cod_verificador_autenticidade', $dados["nota_fiscal"]["cod_verificador_autenticidade"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':link_nfse', $dados["nota_fiscal"]["link_nfse"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':situacao', $dados['situacao'] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':rps_numero', $dados["nota_fiscal"]["rps_numero"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':rps_serie', $dados["nota_fiscal"]["rps_serie"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':rps_data_emissao', $rps_data_emissao, PDO::PARAM_STR);
            $this->banco->bindValue(':rps_hora_emissao', $dados['rps_hora_emissao'] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':cod_equipamento_automatico', $dados["nota_fiscal"]["cod_equipamento_automatico"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':data_fato_gerador', $data_fato_gerador, PDO::PARAM_STR);
            $this->banco->bindValue(':observacao', $dados["nota_fiscal"]["observacao"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_total', $dados["nota_fiscal"]["valor_total"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_desconto', $dados["nota_fiscal"]["valor_desconto"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_ir', $dados["nota_fiscal"]["valor_ir"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_inss', $dados["nota_fiscal"]["valor_inss"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_contribuicao_social', $dados['valor_contribuicao_social'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_rps_retencao', $dados["nota_fiscal"]["valor_rps_retencao"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_pis', $dados["nota_fiscal"]["valor_pis"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_cofins', $dados["nota_fiscal"]["valor_cofins"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':prestador_cpfcnpj', $dados["prestador"]["cpfcnpj"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':prestador_cidade_codigo', $dados["prestador"]["cidade_codigo"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':tomador_id', $dados["tomador"]["tomador_id"], PDO::PARAM_INT);
            $this->banco->bindValue(':tomador_cpfcnpj', $dados["tomador"]["cpfcnpj"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_tipo', $dados["tomador"]["tipo"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_nome_razao_social', $dados["tomador"]["nome_razao_social"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_nome_fantasia', $dados["tomador"]["nome_fantasia"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ie', $dados["tomador"]["ie"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_email', $dados["tomador"]["email"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_endereco_informado', $dados["tomador"]["endereco_informado"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_logradouro', $dados["tomador"]["logradouro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_numero_residencia', $dados["tomador"]["numero_residencia"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_complemento', $dados["tomador"]["complemento"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_bairro', $dados["tomador"]["bairro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_cidade_codigo', $dados["tomador"]["cidade_codigo"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_cep', $dados["tomador"]["cep"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ponto_referencia', $dados["tomador"]["ponto_referencia"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ddd_fone_comercial', $dados["tomador"]["ddd_fone_comercial"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_fone_comercial', $dados["tomador"]["fone_comercial"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ddd_fone_residencial', $dados["tomador"]["ddd_fone_residencial"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_fone_residencial', $dados["tomador"]["fone_residencial"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ddd_fax', $dados["tomador"]["ddd_fax"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_fone_fax', $dados["tomador"]["fone_fax"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_identificador_estrangeiro', $dados["tomador"]["identificador_estrangeiro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_estado_estrangeiro', $dados["tomador"]["estado_estrangeiro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_pais_estrangeiro', $dados["tomador"]["pais_estrangeiro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':produtos_descricao', $dados["nota_fiscal"]["produtos_descricao"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':produtos_valor_total', $dados["nota_fiscal"]["produtos_valor_total"] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':forma_pagamento_tipo', $dados["nota_fiscal"]["forma_pagamento_tipo"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':created_user', $this->m_userid, PDO::PARAM_INT);

            $this->banco->execute();

            if ($this->banco->rowCount() > 0) {
                return $this->banco->lastInsertId();
            }

            return false;

        } catch (PDOException $e) {
            error_log("Erro ao salvar Nota Fiscal de Serviço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Salva um item da Nota Fiscal de Serviço na tabela EST_NOTA_FISCAL_SERVICO_ITEM
     *
     * @param int $idNotaFiscal ID da nota fiscal de serviço
     * @param array $dadosItem Array associativo com os dados do item
     * @return bool Retorna true em caso de sucesso ou false em caso de erro
     */
    function saveNotaFiscalServicoItem(int $idNotaFiscal, array $dadosItem): bool
    {
        $sql = "
            INSERT INTO EST_NOTA_FISCAL_SERVICO_ITEM (
                ID_NOTA_FISCAL_SERVICO,
                ID_CAT_SERVICO,
                TRIBUTA_MUNICIPIO_PRESTADOR,
                CODIGO_LOCAL_PRESTACAO_SERVICO,
                CODIGO_ITEM_LISTA_SERVICO,
                DESCRITIVO,
                ALIQUOTA,
                SITUACAO_TRIBUTARIA,
                VALOR_TRIBUTAVEL,
                VALOR_DEDUCAO,
                VALOR_ISSRF,
                UNIDADE_CODIGO,
                UNIDADE_QUANTIDADE,
                UNIDADE_VALOR_UNITARIO,
                CREATED_USER
            ) VALUES (
                :id_nota_fiscal_servico,
                :id_cat_servico,
                :tributa_municipio_prestador,
                :codigo_local_prestacao_servico,
                :codigo_item_lista_servico,
                :descritivo,
                :aliquota,
                :situacao_tributaria,
                :valor_tributavel,
                :valor_deducao,
                :valor_issrf,
                :unidade_codigo,
                :unidade_quantidade,
                :unidade_valor_unitario,
                :created_user
            )
        ";

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            // Bind dos parâmetros
            $this->banco->bindValue(':id_nota_fiscal_servico', $idNotaFiscal, PDO::PARAM_INT);
            $this->banco->bindValue(':id_cat_servico', $dadosItem['id_cat_servico'], PDO::PARAM_INT);
            $this->banco->bindValue(':tributa_municipio_prestador', $dadosItem['tributa_municipio_prestador'] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':codigo_local_prestacao_servico', $dadosItem['codigo_local_prestacao_servico'] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':codigo_item_lista_servico', $dadosItem['codigo_item_lista_servico'] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':descritivo', $dadosItem['descritivo'] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':aliquota', $dadosItem['aliquota'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':situacao_tributaria', $dadosItem['situacao_tributaria'] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':valor_tributavel', $dadosItem['valor_tributavel'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_deducao', $dadosItem['valor_deducao'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':valor_issrf', $dadosItem['valor_issrf'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':unidade_codigo', $dadosItem['unidade_codigo'] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':unidade_quantidade', $dadosItem['unidade_quantidade'] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':unidade_valor_unitario', $dadosItem['unidade_valor_unitario'] ?? 0.00, PDO::PARAM_STR);
            $this->banco->bindValue(':created_user', $this->m_userid, PDO::PARAM_INT);

            $this->banco->execute();

            return $this->banco->rowCount() > 0;

        } catch (PDOException $e) {
            error_log("Erro ao salvar item da Nota Fiscal de Serviço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida o JSON da nota fiscal conforme especificações NFSe
     *
     * @param string $json JSON com os dados da nota fiscal
     * @return array Retorna array com 'valido' (bool) e 'erros' (array)
     */
    function validateJsonNotaFiscal(string $json): array
    {
        $erros = [];
        
        try {
            $dados = json_decode($json, true);
            
            if (!$dados) {
                return [
                    'valido' => false,
                    'erros' => ['JSON inválido ou malformado']
                ];
            }

            // Validações de campos obrigatórios da nota fiscal principal
            $camposObrigatorios = [
                'tomador_id' => 'ID do tomador é obrigatório',
                'data_fato_gerador' => 'Data do fato gerador é obrigatória',
                'valor_total' => 'Valor total é obrigatório',
                'produtos_valor_total' => 'Valor total dos produtos é obrigatório'
            ];

            foreach ($camposObrigatorios as $campo => $mensagem) {
                if (!isset($dados[$campo]) || empty($dados[$campo])) {
                    $erros[] = $mensagem;
                }
            }

            // Validações de tamanho de campos - baseado na estrutura da tabela
            $validacoesTamanho = [
                'observacao' => ['max' => 1000, 'nome' => 'Observação'],
                'prestador_cpfcnpj' => ['max' => 14, 'nome' => 'CPF/CNPJ do prestador'],
                'prestador_cidade_codigo' => ['max' => 100, 'nome' => 'Código da cidade do prestador'],
                'tomador_cpfcnpj' => ['max' => 14, 'nome' => 'CPF/CNPJ do tomador'],
                'tomador_tipo' => ['max' => 1, 'nome' => 'Tipo do tomador'],
                'tomador_nome_razao_social' => ['max' => 100, 'nome' => 'Nome/Razão social do tomador'],
                'tomador_nome_fantasia' => ['max' => 100, 'nome' => 'Nome fantasia do tomador'],
                'tomador_ie' => ['max' => 16, 'nome' => 'IE do tomador'],
                'tomador_email' => ['max' => 100, 'nome' => 'Email do tomador'],
                'tomador_endereco_informado' => ['max' => 1, 'nome' => 'Endereço informado'],
                'tomador_logradouro' => ['max' => 70, 'nome' => 'Logradouro do tomador'],
                'tomador_numero_residencia' => ['max' => 8, 'nome' => 'Número da residência'],
                'tomador_complemento' => ['max' => 50, 'nome' => 'Complemento'],
                'tomador_bairro' => ['max' => 30, 'nome' => 'Bairro'],
                'tomador_cidade_codigo' => ['max' => 100, 'nome' => 'Código da cidade'],
                'tomador_cep' => ['max' => 8, 'nome' => 'CEP'],
                'tomador_ponto_referencia' => ['max' => 100, 'nome' => 'Ponto de referência'],
                'tomador_ddd_fone_comercial' => ['max' => 3, 'nome' => 'DDD fone comercial'],
                'tomador_fone_comercial' => ['max' => 9, 'nome' => 'Fone comercial'],
                'tomador_ddd_fone_residencial' => ['max' => 3, 'nome' => 'DDD fone residencial'],
                'tomador_fone_residencial' => ['max' => 9, 'nome' => 'Fone residencial'],
                'tomador_ddd_fax' => ['max' => 3, 'nome' => 'DDD fax'],
                'tomador_fone_fax' => ['max' => 9, 'nome' => 'Fone fax'],
                'tomador_identificador_estrangeiro' => ['max' => 20, 'nome' => 'Identificador estrangeiro'],
                'tomador_estado_estrangeiro' => ['max' => 100, 'nome' => 'Estado estrangeiro'],
                'tomador_pais_estrangeiro' => ['max' => 100, 'nome' => 'País estrangeiro'],
                'produtos_descricao' => ['max' => 200, 'nome' => 'Descrição dos produtos']
            ];

            foreach ($validacoesTamanho as $campo => $config) {
                if (isset($dados[$campo]) && !empty($dados[$campo])) {
                    if (strlen($dados[$campo]) > $config['max']) {
                        $erros[] = "{$config['nome']} excede o tamanho máximo de {$config['max']} caracteres";
                    }
                }
            }


            // Validações de formato específicas
            if (isset($dados['tomador_tipo']) && !empty($dados['tomador_tipo'])) {
                if (!in_array($dados['tomador_tipo'], ['F', 'J', 'E'])) {
                    $erros[] = 'Tipo do tomador deve ser F (Física), J (Jurídica) ou E (Estrangeiro)';
                }
            }

            // Validações de valores numéricos
            $camposNumericos = [
                'valor_total', 'valor_desconto', 'valor_ir', 'valor_inss', 
                'valor_contribuicao_social', 'valor_rps_retencao', 'valor_pis', 
                'valor_cofins', 'produtos_valor_total'
            ];

            foreach ($camposNumericos as $campo) {
                if (isset($dados[$campo]) && !is_numeric($dados[$campo])) {
                    $erros[] = "Campo {$campo} deve ser numérico";
                }
                if (isset($dados[$campo]) && $dados[$campo] < 0) {
                    $erros[] = "Campo {$campo} não pode ser negativo";
                }
            }

            // Validação dos itens
            if (!isset($dados['itens']) || !is_array($dados['itens']) || empty($dados['itens'])) {
                $erros[] = 'Deve conter pelo menos um item de serviço';
            } else {
                foreach ($dados['itens'] as $index => $item) {
                    $itemErros = $this->validateItemNotaFiscal($item, $index + 1);
                    $erros = array_merge($erros, $itemErros);
                }
            }

            $resultado = [
                'valido' => empty($erros),
                'erros' => $erros
            ];

            return $resultado;

        } catch (Exception $e) {
            return [
                'valido' => false,
                'erros' => ['Erro ao validar JSON: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Valida um item da nota fiscal de serviço
     *
     * @param array $item Dados do item
     * @param int $numeroItem Número do item para identificação nos erros
     * @return array Array com erros encontrados
     */
    private function validateItemNotaFiscal(array $item, int $numeroItem): array
    {
        $erros = [];

        // Campos obrigatórios do item
        $camposObrigatorios = [
            'id_cat_servico' => 'ID do serviço é obrigatório',
            'codigo_item_lista_servico' => 'Código do item da lista de serviço é obrigatório',
            'descritivo' => 'Descritivo do serviço é obrigatório',
            'aliquota' => 'Alíquota é obrigatória',
            'valor_tributavel' => 'Valor tributável é obrigatório'
        ];

        foreach ($camposObrigatorios as $campo => $mensagem) {
            if (!isset($item[$campo]) || empty($item[$campo])) {
                $erros[] = "Item {$numeroItem}: {$mensagem}";
            }
        }

        // Validações de tamanho
        $validacoesTamanho = [
            'codigo_item_lista_servico' => ['max' => 9, 'nome' => 'Código do item'],
            'descritivo' => ['max' => 1000, 'nome' => 'Descritivo'],
            'tributa_municipio_prestador' => ['max' => 1, 'nome' => 'Tributa município prestador']
        ];

        foreach ($validacoesTamanho as $campo => $config) {
            if (isset($item[$campo]) && !empty($item[$campo])) {
                if (strlen($item[$campo]) > $config['max']) {
                    $erros[] = "Item {$numeroItem}: {$config['nome']} excede o tamanho máximo de {$config['max']} caracteres";
                }
            }
        }

        // Validações numéricas
        $camposNumericos = [
            'aliquota', 'valor_tributavel', 'valor_deducao', 'valor_issrf', 
            'unidade_quantidade', 'unidade_valor_unitario'
        ];

        foreach ($camposNumericos as $campo) {
            if (isset($item[$campo]) && !empty($item[$campo])) {
                if (!is_numeric($item[$campo])) {
                    $erros[] = "Item {$numeroItem}: Campo {$campo} deve ser numérico";
                }
                if ($item[$campo] < 0) {
                    $erros[] = "Item {$numeroItem}: Campo {$campo} não pode ser negativo";
                }
            }
        }

        // Validação específica da alíquota (0 a 100%)
        if (isset($item['aliquota']) && is_numeric($item['aliquota'])) {
            if ($item['aliquota'] > 100) {
                $erros[] = "Item {$numeroItem}: Alíquota não pode ser superior a 100%";
            }
        }

        // Validação do tributa_municipio_prestador
        if (isset($item['tributa_municipio_prestador']) && !empty($item['tributa_municipio_prestador'])) {
            if (!in_array($item['tributa_municipio_prestador'], ['0', '1'])) {
                $erros[] = "Item {$numeroItem}: Tributa município prestador deve ser '0' ou '1'";
            }
        }

        return $erros;
    }

    /**
     * Processa o JSON da nota fiscal e salva os dados nas tabelas
     *
     * @param string $json JSON com os dados da nota fiscal
     * @return int|false Retorna o ID da nota fiscal inserida ou false em caso de erro
     */
    function processJsonNotaFiscal(string $json)
    {
        try {
            $dados = json_decode($json, true);
            
            if (!$dados) {
                throw new Exception("JSON inválido");
            }

            // Salva a nota fiscal principal
            $idNotaFiscal = $this->saveNotaFiscalServico($dados);
            
            if (!$idNotaFiscal) {
                throw new Exception("Erro ao salvar nota fiscal");
            }

            // Salva os itens da nota fiscal
            if (isset($dados['itens']) && is_array($dados['itens'])) {
                foreach ($dados['itens'] as $item) {
                    $sucesso = $this->saveNotaFiscalServicoItem($idNotaFiscal, $item);
                    if (!$sucesso) {
                        error_log("Erro ao salvar item da nota fiscal ID: " . $idNotaFiscal);
                    }
                }
            }

            return $idNotaFiscal;

        } catch (Exception $e) {
            error_log("Erro ao processar JSON da nota fiscal: " . $e->getMessage());
            return false;
        }
    }



    /**
     * Salva as parcelas da Nota Fiscal de Serviço na tabela FIN_LANCAMENTO
     *
     * @param int $idNotaFiscal ID da nota fiscal de serviço
     * @param mixed $dados Array ou JSON string com os dados das parcelas e informações do lançamento
     * @return bool Retorna true em caso de sucesso ou false em caso de erro
     */
    function saveNotaFiscalServicoParcela(int $idNotaFiscal, $dados)
    {
        try {
            // Decodificar JSON se necessário
            if (is_string($dados)) {
                $dados = json_decode($dados, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    c_nfs_response::error('Erro ao decodificar JSON: ' . json_last_error_msg());
                    return false;
                }
            }

            // se nao existir forma_pagamento ou tipo_pagamento, retorna false
            if($dados["forma_pagamento"]["tipo_pagamento"] == "0" || $dados["forma_pagamento"]["tipo_pagamento"] == null){
                return false;
            }
            
            // Verifica se existem parcelas nos dados
            if (!isset($dados["forma_pagamento"]["parcelas"]) || 
                !is_array($dados["forma_pagamento"]["parcelas"]) || 
                empty($dados["forma_pagamento"]["parcelas"])) {

                c_nfs_response::error('Nenhuma parcela encontrada nos dados');
                return false;
            }

            $parcelasInseridas = 0;
            $sql = "
                INSERT INTO FIN_LANCAMENTO (
                    PESSOA,
                    DOCTO,
                    SERIE,
                    PARCELA,
                    TIPODOCTO,
                    SITDOCTO,
                    SITPGTO,
                    USRSITPGTO,
                    MODOPGTO,
                    DOCBANCARIO,
                    CONTA,
                    CHEQUE,
                    USRAPROVACAO,
                    GENERO,
                    CENTROCUSTO,
                    LANCAMENTO,
                    EMISSAO,
                    VENCIMENTO,
                    PAGAMENTO,
                    ORIGINAL,
                    MULTA,
                    JUROS,
                    ADIANTAMENTO,
                    DESCONTO,
                    TOTAL,
                    MOEDA,
                    ORIGEM,
                    NUMLCTO,
                    OBS,
                    OBSCONTABIL,
                    REMESSANUM,
                    NOSSONUMERO,
                    REMESSADATA,
                    REMESSAARQ,
                    RETORNOARQ,
                    RETORNOCOD,
                    TIPOLANCAMENTO,
                    USERINSERT,
                    DATEINSERT
                ) VALUES (
                    :pessoa,
                    :docto,
                    :serie,
                    :parcela,
                    :tipodocto,
                    :sitdocto,
                    :sitpgto,
                    :usrsitpgto,
                    :modopgto,
                    :docbancario,
                    :conta,
                    :cheque,
                    :usraprovacao,
                    :genero,
                    :centrocusto,
                    :lancamento,
                    :emissao,
                    :vencimento,
                    :pagamento,
                    :original,
                    :multa,
                    :juros,
                    :adiantamento,
                    :desconto,
                    :total,
                    :moeda,
                    :origem,
                    :numlcto,
                    :obs,
                    :obscontabil,
                    :remessanum,
                    :nossonumero,
                    :remessadata,
                    :remessaarq,
                    :retornoarq,
                    :retornocod,
                    :tipolancamento,
                    :userinsert,
                    :dateinsert
                )
            ";

            foreach ($dados["forma_pagamento"]["parcelas"] as $parcela) {
                
                $this->banco = new c_banco_pdo();
                $this->banco->prepare($sql);
                
                // Conversão de datas
                $dataLancamento = isset($dados['lancamento']) ? c_date::convertDateBdSh($dados['lancamento']) : date('Y-m-d');
                $dataEmissao = isset($dados['emissao']) ? c_date::convertDateBdSh($dados['emissao']) : date('Y-m-d');
                $dataVencimento = isset($parcela['vencimento']) ? c_date::convertDateBdSh($parcela['vencimento']) : null;
                $dataPagamento = isset($parcela['pagamento']) ? c_date::convertDateBdSh($parcela['pagamento']) : null;
                
                // Bind dos parâmetros
                $this->banco->bindValue(':pessoa', $dados["tomador"]["tomador_id"], PDO::PARAM_INT);
                $this->banco->bindValue(':docto', $idNotaFiscal ?? null, PDO::PARAM_INT);
                $this->banco->bindValue(':serie', $dados["nota_fiscal"]["serie"] ?? 'NFS', PDO::PARAM_STR);
                $this->banco->bindValue(':parcela', $parcela['parcela'] ?? 1, PDO::PARAM_STR);
                $this->banco->bindValue(':tipodocto', $parcela["tipo_documento"] ?? 'B', PDO::PARAM_STR);
                $this->banco->bindValue(':sitdocto', 'N', PDO::PARAM_STR);
                $this->banco->bindValue(':sitpgto', $parcela["situacao"] ?? 'A', PDO::PARAM_STR);
                $this->banco->bindValue(':usrsitpgto', $this->m_userid, PDO::PARAM_INT);
                $this->banco->bindValue(':modopgto', 'B', PDO::PARAM_STR);
                $this->banco->bindValue(':docbancario', $parcela['docbancario'] ?? null, PDO::PARAM_STR);
                $this->banco->bindValue(':conta', $parcela["conta_recebimento"] ?? null, PDO::PARAM_INT);
                $this->banco->bindValue(':cheque', $parcela['cheque'] ?? null, PDO::PARAM_STR);
                $this->banco->bindValue(':usraprovacao', $this->m_userid, PDO::PARAM_INT);
                $this->banco->bindValue(':genero', $dados['genero'] ?? null, PDO::PARAM_STR);
                $this->banco->bindValue(':centrocusto', $this->m_empresacentrocusto ?? null, PDO::PARAM_INT);
                $this->banco->bindValue(':lancamento', $dataLancamento, PDO::PARAM_STR);
                $this->banco->bindValue(':emissao', $dataEmissao, PDO::PARAM_STR);
                $this->banco->bindValue(':vencimento', $dataVencimento, PDO::PARAM_STR);
                $this->banco->bindValue(':pagamento', $dataPagamento, PDO::PARAM_STR);
                $this->banco->bindValue(':original', $parcela['valor'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':multa', $parcela['multa'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':juros', $parcela['juros'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':adiantamento', $parcela['adiantamento'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':desconto', $parcela['desconto'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':total', $parcela['valor'] ?? 0.00, PDO::PARAM_STR);
                $this->banco->bindValue(':moeda', 0, PDO::PARAM_INT);
                $this->banco->bindValue(':origem', $dados['origem'] ?? 'NFS', PDO::PARAM_STR);
                $this->banco->bindValue(':numlcto', $dados['numlcto'] ?? $dados['docto'], PDO::PARAM_INT);
                $this->banco->bindValue(':obs', $parcela['obs'] ?? $dados['obs'] ?? null, PDO::PARAM_STR);
                $this->banco->bindValue(':obscontabil', $dados['obscontabil'] ?? null, PDO::PARAM_STR);
                $this->banco->bindValue(':remessanum', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':nossonumero', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':remessadata', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':remessaarq', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':retornoarq', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':retornocod', null, PDO::PARAM_NULL);
                $this->banco->bindValue(':tipolancamento', $dados['tipolancamento'] ?? 'R', PDO::PARAM_STR);
                $this->banco->bindValue(':userinsert', $this->m_userid, PDO::PARAM_INT);
                $this->banco->bindValue(':dateinsert', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                
                $this->banco->execute();
                
                if ($this->banco->rowCount() > 0) {
                    $parcelasInseridas++;
                }
            }

            return true;

        } catch (PDOException $e) {
            error_log("Erro ao salvar parcelas no financeiro: " . $e->getMessage());
            c_nfs_response::error('Erro ao salvar parcelas no financeiro: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Erro ao processar parcelas do financeiro: " . $e->getMessage());
            c_nfs_response::error('Erro ao processar parcelas do financeiro: ' . $e->getMessage());
            return false;
        }
    }


}
?>
