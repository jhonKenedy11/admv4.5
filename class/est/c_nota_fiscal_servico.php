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
include_once($dir . "/../../bib/c_database_pdo.php");
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_ipm_estrategy.php");
include_once($dir . "/c_nfs_response.php");

//Class c_nota_fiscal_servico
class c_nota_fiscal_servico extends c_user
{
    protected $config;
    protected $id;
    protected $dados;
    protected $parametros;
    protected $data_ini;
    protected $data_fim;
    protected $numero_nfs;
    protected $serie_nfs;
    protected $situacao_nfs;
    protected $centro_custo;
    protected $cliente;
    protected $cliente_id;
    protected $origem_nfse; // NULL ou 'OS' or 'Pedido' or 'NFe';
    protected $motivo_cancelamento;

    function __construct()
    {   
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);

        $this->config = NULL;
        $this->dados = NULL;
    }

    public function typeFramework( string $origem_dados, ?int $id_nota_fiscal = null, ?string $json = null)
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

                // Search the invoice by the id
                $this->id = $id_nota_fiscal;
                $nfs = $this->selectNotaFiscalServico($id_nota_fiscal);

                // Search the parameter of the cancelation by the filial
                $parameter = $this->selectParamterNfs($nfs[0]['CENTRO_CUSTO']);
                if(empty($parameter)) {
                    c_nfs_response::validationError('Parâmetro de nota fiscal de serviço não encontrado');
                    return false;
                }

                // Mount the config of the cancelation
                $config = [
                    'url' => $config["producao"],
                    'user' => $parameter[0]["NFS_USER"],
                    'password' => $parameter[0]['NFS_PASSWORD'],
                ];
                
                $objIpm =  new IpmStrategy($config, $id_nota_fiscal, 'OS');

                if($json){
                    $resultado = $objIpm->processForShipping( $origem_dados, $id_nota_fiscal, $json);
                }else{
                    $resultado = $objIpm->processForShipping( $origem_dados, $id_nota_fiscal, null);
                }

                //Error
                if (!$resultado['sucesso']) {

                    // Register the error event
                    $dadosEvento = [
                        'id_nfs' => $id_nota_fiscal,
                        'centro_custo' => $nfs[0]['CENTRO_CUSTO'],
                        'serie' => $nfs[0]['SERIE'],
                        'numero' => $nfs[0]['NUMERO'],
                        'origem' => 'OS',
                        'tipo_evento' => 'E',
                        'codigo_retorno' => $resultado["codigo_erro"],
                        'mensagem_retorno' => $resultado["mensagem"],
                        'created_user' => $this->m_userid
                    ];

                    $this->saveEventInvoice($dadosEvento, $resultado['responseXml']);

                    // Deleta a nota fiscal se ocorrer erro
                    $this->deletInvoiceError($id_nota_fiscal);
        
                    // if to present error in send returns here
                    c_nfs_response::validationError($resultado['mensagem']);
                    return;
                }
                
                // If success continue 
                $objIpmXml = new IpmStrategyXml();
                
                // Extract data of xml of return
                $dadosNfse = $objIpmXml->extrairDadosNfseRetorno($resultado['responseXml']);
                
                // Update invoice with the return data
                if ($id_nota_fiscal && !empty($dadosNfse)) {
                    $this->atualizarNotaFiscalComRetorno($id_nota_fiscal, $dadosNfse);
                }

                // Salva as parcelas
                $this->saveNotaFiscalServicoParcela($id_nota_fiscal, $json);

                // Retornar sucesso com dados da nota
                c_nfs_response::success($resultado['mensagem'], $dadosNfse);

            case 'GINFES':
                //return new GinfesStrategy($config, $dados);
            default:
                throw new \Exception("Municipio nao suportado:  $codigo_municipio");
        }
    }



    function cancelInvoice()
    {   
        try {

            if(empty($this->id)) {
                c_nfs_response::validationError('ID da nota fiscal de serviço não informado');
                return false;
            }
    
            if(empty($this->motivo)) {
                c_nfs_response::validationError('Motivo de cancelamento não informado');
                return false;
            }

            // Search the invoice by the id
            $nfs = $this->selectNotaFiscalServico();
            if(empty($nfs)) {
                c_nfs_response::validationError('Nenhuma nota fiscal de serviço encontrada');
                return false;
            }

            // Search the parameter of the cancelation by the filial
            $parameter = $this->selectParamterNfs($nfs[0]['CENTRO_CUSTO']);
            if(empty($parameter)) {
                c_nfs_response::validationError('Parâmetro de nota fiscal de serviço não encontrado');
                return false;
            }

            // Search the url of the cancelation by the parameter
            $params_municipality = $this->getConfigMunicipality($nfs[0]['PRESTADOR_CIDADE_CODIGO']);
            if(empty($params_municipality)) {
                c_nfs_response::validationError('Parâmetro de nota fiscal de serviço não encontrado');
                return false;
            }

            // Mount the config of the cancelation
            $config = [
                'url' => $params_municipality["producao"],
                'user' => $parameter[0]["NFS_USER"],
                'password' => $parameter[0]['NFS_PASSWORD'],
            ];

            
            $objIpm =  new IpmStrategy($config, $this->id);
            $resultado = $objIpm->cancelInvoiceIpm($nfs, $this->motivo);

            // if error return
            if ($resultado['sucesso'] === false) {
                c_nfs_response::validationError($resultado['mensagem']);
                return false;
            }
            
            // if success return
            c_nfs_response::success($resultado['mensagem']);

        } catch (\Exception $e) {

            // if error return
            error_log("Erro ao cancelar nota fiscal de serviço: " . $e->getMessage());
            c_nfs_response::error('Erro ao cancelar nota fiscal de serviço: ' . $e->getMessage());
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
     *                     - origem (string|null): Origem da NFS (OS, PED, NFS).
     *                     - tipo_evento (string): Tipo de evento (C, E, S, N).
     *                     - codigo_retorno (string|null): Código de retorno da operação (até 10 caracteres).
     *                     - mensagem_retorno (string|null): Mensagem de retorno da operação.
     *                     - created_user (int): ID do usuário que criou o registro.
     * @param string $xmlRetorno XML de resposta do serviço (pequeno), extraído do corpo da resposta.
     *
     * @return bool Retorna true em caso de sucesso, ou false se ocorrer algum erro na execução.
     */
    function saveEventInvoice(array $dados, ?string $xmlRetorno = null): bool
    {
        $sql = "
            INSERT INTO EST_NOTA_FISCAL_SERVICO_EVENTOS (
                ID_NFS,
                CENTRO_CUSTO,
                SERIE,
                NUMERO,
                ORIGEM,
                TIPO_EVENTO,
                CODIGO_RETORNO,
                MENSAGEM_RETORNO,
                XML_RETORNO,
                CREATED_USER
            ) VALUES (
                :id_nfs,
                :centro_custo,
                :serie,
                :numero,
                :origem,
                :tipo_evento,
                :codigo_retorno,
                :mensagem_retorno,
                :xml_retorno,
                :created_user
            )
        ";

        try {

            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            $xmlUtf8 = mb_convert_encoding($xmlRetorno, 'UTF-8', 'ISO-8859-1');


            $this->banco->bindValue(':id_nfs',           $dados['id_nfs'],           PDO::PARAM_INT);
            $this->banco->bindValue(':centro_custo',     $dados['centro_custo'],     PDO::PARAM_STR);
            $this->banco->bindValue(':serie',            $dados['serie'],            PDO::PARAM_STR);
            $this->banco->bindValue(':numero',           $dados['numero'],           PDO::PARAM_INT);
            $this->banco->bindValue(':tipo_evento',      $dados['tipo_evento'],      PDO::PARAM_STR);
            $this->banco->bindValue(':codigo_retorno',   $dados['codigo_retorno'],   PDO::PARAM_STR);
            $this->banco->bindValue(':mensagem_retorno', $dados['mensagem_retorno'], PDO::PARAM_STR);
            $this->banco->bindValue(':xml_retorno',      $xmlUtf8,                   PDO::PARAM_STR);
            $this->banco->bindValue(':origem',           $dados['origem'],           PDO::PARAM_STR);
            $this->banco->bindValue(':created_user',     $dados['created_user'],     PDO::PARAM_INT);

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
                CENTRO_CUSTO,
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
                CREATED_USER
            ) VALUES (
                :identificador_arquivo,
                :centro_custo,
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
                :created_user
            )
        ";

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            $data_fato_gerador = isset($dados["nota_fiscal"]["data_fato_gerador"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["data_fato_gerador"]) : null;
            $rps_data_emissao = isset($dados["nota_fiscal"]["rps_data_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["rps_data_emissao"]) : null;
            $data_emissao = isset($dados["nota_fiscal"]["data_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["data_emissao"]) : date('Y-m-d');
            $hora_emissao = isset($dados["nota_fiscal"]["hora_emissao"]) ? c_date::convertDateBdSh($dados["nota_fiscal"]["hora_emissao"]) : date('H:i:s');

            // Bind dos parâmetros
            $this->banco->bindValue(':identificador_arquivo', isset($dados["nota_fiscal"]["identificador_arquivo"]) ? $dados["nota_fiscal"]["identificador_arquivo"] : null, PDO::PARAM_STR);
            $this->banco->bindValue(':centro_custo', $dados["centro_custo"] ?? $this->m_empresacentrocusto, PDO::PARAM_STR);
            $this->banco->bindValue(':numero', $dados["nota_fiscal"]["numero"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':serie', $dados["nota_fiscal"]["serie"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':data_emissao', $data_emissao, PDO::PARAM_STR);
            $this->banco->bindValue(':hora_emissao', $hora_emissao, PDO::PARAM_STR);
            $this->banco->bindValue(':cod_verificador_autenticidade', $dados["nota_fiscal"]["cod_verificador_autenticidade"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':link_nfse', $dados["nota_fiscal"]["link_nfse"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':situacao', $dados['situacao'] ?? 0, PDO::PARAM_INT);
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
            $this->banco->bindValue(':prestador_cidade_codigo', $dados["prestador"]["cidade"] ?? null, PDO::PARAM_INT);
            $this->banco->bindValue(':tomador_id', $dados["tomador"]["tomador_id"], PDO::PARAM_INT);
            $this->banco->bindValue(':tomador_cpfcnpj', $dados["tomador"]["cpfcnpj"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_tipo', $dados["tomador"]["tipo"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_nome_razao_social', $dados["tomador"]["nome_razao_social"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_nome_fantasia', $dados["tomador"]["sobrenome_nome_fantasia"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_ie', $dados["tomador"]["ie"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_email', $dados["tomador"]["email"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_endereco_informado', $dados["tomador"]["endereco_informado"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_logradouro', $dados["tomador"]["logradouro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_numero_residencia', $dados["tomador"]["numero_residencia"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_complemento', $dados["tomador"]["complemento"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_bairro', $dados["tomador"]["bairro"] ?? null, PDO::PARAM_STR);
            $this->banco->bindValue(':tomador_cidade_codigo', $dados["tomador"]["cidade"] ?? null, PDO::PARAM_STR);
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
                'tomador_pais_estrangeiro' => ['max' => 100, 'nome' => 'País estrangeiro']
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
                'valor_cofins'
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
            if(empty($dados["forma_pagamento"]["parcelas"])){
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
                $this->banco->bindValue(':genero', $dados["forma_pagamento"]["genero"] ?? null, PDO::PARAM_STR);
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
                $this->banco->bindValue(':numlcto', $dados['numlcto'] ?? 0, PDO::PARAM_INT);
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

    /**
     * Seleciona notas fiscais de serviço com filtros opcionais
     * Utiliza as propriedades da classe como filtros:
     * - $this->id: ID da nota fiscal
     * - $this->data_ini: Data inicial (formato: Y-m-d ou d/m/Y)
     * - $this->data_fim: Data final (formato: Y-m-d ou d/m/Y)
     * - $this->situacao_nfs: Situação (1=Emitida, 2=Cancelada)
     * - $this->cliente_id: ID do tomador (cliente)
     * - $this->numero_nfs: Número da nota fiscal
     * - $this->serie_nfs: Série da nota fiscal
     * 
     * @return array Array com os registros encontrados
     */
    function selectNotaFiscalServico()
    {

        $sql = "SELECT EST_NOTA_FISCAL_SERVICO.*, 
                    FIN_CLIENTE.NOMEREDUZIDO AS NOME_CLIENTE, 
                    FIN_CENTRO_CUSTO.DESCRICAO AS CENTRO_CUSTO_DESCRICAO 
                FROM EST_NOTA_FISCAL_SERVICO 
                LEFT JOIN FIN_CLIENTE ON FIN_CLIENTE.CLIENTE = EST_NOTA_FISCAL_SERVICO.TOMADOR_ID 
                LEFT JOIN FIN_CENTRO_CUSTO ON FIN_CENTRO_CUSTO.CENTROCUSTO = EST_NOTA_FISCAL_SERVICO.CENTRO_CUSTO
        
        WHERE 1=1";
        
        $params = [];
        
        // PRIORIDADE 1: Se existir ID, buscar APENAS por ID (ignora todos os outros filtros)
        if (isset($this->id) && $this->id > 0) {
            $sql .= " AND ID = :id";
            $params[':id'] = ['value' => $this->id, 'type' => PDO::PARAM_INT];

        // PRIORIDADE 2: Se existir Número (e não tem ID), buscar APENAS por Número (ignora outros filtros)
        } elseif (isset($this->numero_nfs) && $this->numero_nfs > 0) {
            $sql .= " AND NUMERO = :numero";
            $params[':numero'] = ['value' => $this->numero_nfs, 'type' => PDO::PARAM_INT];

            // PRIORIDADE 3: Caso contrário, usar os filtros normais (datas, situação, cliente, série)
        } else {

            // Filtro por Data Inicial
            if (!empty($this->data_ini)) {

                $dataInicio = $this->data_ini;

                // Converter formato se necessário (d/m/Y para Y-m-d)
                if (strpos($dataInicio, '/') !== false) {
                    $dataInicio = implode('-', array_reverse(explode('/', $dataInicio)));
                }

                $sql .= " AND DATA_EMISSAO >= :data_inicio";
                $params[':data_inicio'] = ['value' => $dataInicio, 'type' => PDO::PARAM_STR];
            }
            
            // Filtro por Data Final
            if (!empty($this->data_fim)) {

                $dataFim = $this->data_fim;

                // Converter formato se necessário (d/m/Y para Y-m-d)
                if (strpos($dataFim, '/') !== false) {
                    $dataFim = implode('-', array_reverse(explode('/', $dataFim)));
                }

                $sql .= " AND DATA_EMISSAO <= :data_fim";
                $params[':data_fim'] = ['value' => $dataFim, 'type' => PDO::PARAM_STR];
            }
            
            // Filtro por Situação
            if (isset($this->situacao_nfs) && $this->situacao_nfs > 0) {

                $sql .= " AND SITUACAO = :situacao";
                $params[':situacao'] = ['value' => $this->situacao_nfs, 'type' => PDO::PARAM_INT];
            }
            
            // Filtro por Cliente (Tomador)
            if (isset($this->cliente_id) && $this->cliente_id > 0) {

                $sql .= " AND TOMADOR_ID = :id_cliente";
                $params[':id_cliente'] = ['value' => $this->cliente_id, 'type' => PDO::PARAM_INT];
            }
            
            // Filtro por Série (quando não usado com número)
            if (isset($this->serie_nfs) && $this->serie_nfs > 0) {

                $sql .= " AND SERIE = :serie";
                $params[':serie'] = ['value' => $this->serie_nfs, 'type' => PDO::PARAM_INT];
            }
        }
        
        // Ordenar por data de emissão decrescente (mais recentes primeiro)
        $sql .= " ORDER BY DATA_EMISSAO DESC, ID DESC";
        
        // Preparar e executar a consulta
        $this->banco = new c_banco_pdo();
        $this->banco->prepare($sql);
        
        // Bind dos parâmetros
        foreach ($params as $key => $param) {
            $this->banco->bindValue($key, $param['value'], $param['type']);
        }

        $queryString = $this->banco->queryString();
        
        $this->banco->execute();
        return $this->banco->fetchAll(PDO::FETCH_ASSOC);
    }

    function selectCentroCusto(){
        $banco = new c_banco_pdo();
            
        // Consulta SQL para buscar centros de custo ativos
        $sql = "SELECT CENTROCUSTO AS ID, DESCRICAO FROM FIN_CENTRO_CUSTO WHERE ATIVO = :ativo ORDER BY DESCRICAO";
        
        // Ordem correta: 1) prepare, 2) bindValue, 3) execute
        $banco->prepare($sql);
        $banco->bindValue(':ativo', 'S', PDO::PARAM_STR);
        $banco->execute();
        
        $resultado = $banco->fetchAll(PDO::FETCH_ASSOC);
        
        // Formata os resultados para o formato esperado
        $centro_custos = array(
            'ID' => array(),
            'DESCRICAO' => array()
        );
        
        foreach ($resultado as $centro) {
            $centro_custos['ID'][] = $centro['ID'];
            $centro_custos['DESCRICAO'][] = $centro['DESCRICAO'];
        }
        
        return $centro_custos;
    }


    /**
     * Atualiza a nota fiscal no banco com os dados retornados do webservice
     *
     * @param int $idNotaFiscal ID da nota fiscal a ser atualizada
     * @param array $dadosNfse Dados extraídos do XML de retorno
     * @return bool True se sucesso, False se erro
     */
    private function atualizarNotaFiscalComRetorno(int $idNotaFiscal, array $dadosNfse): bool
    {
        try {
            $sql = "
                UPDATE EST_NOTA_FISCAL_SERVICO 
                SET 
                    NUMERO = :numero,
                    SERIE = :serie,
                    DATA_EMISSAO = :data_emissao,
                    HORA_EMISSAO = :hora_emissao,
                    COD_VERIFICADOR_AUTENTICIDADE = :cod_verificador,
                    LINK_NFSE = :link_nfse,
                    SITUACAO = :situacao,
                    UPDATED_USER = :updated_user,
                    UPDATED_AT = NOW()
                WHERE ID = :id
            ";
            
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            
            // Converter data do formato brasileiro para banco (13/10/2025 -> 2025-10-13)
            $dataEmissao = null;
            if (isset($dadosNfse['data_nfse'])) {
                $dataEmissao = c_date::convertDateBdSh($dadosNfse['data_nfse']);
            }
            
            $banco->bindValue(':numero', $dadosNfse['numero_nfse'] ?? null, PDO::PARAM_INT);
            $banco->bindValue(':serie', $dadosNfse['serie_nfse'] ?? null, PDO::PARAM_INT);
            $banco->bindValue(':data_emissao', $dataEmissao, PDO::PARAM_STR);
            $banco->bindValue(':hora_emissao', $dadosNfse['hora_nfse'] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':cod_verificador', $dadosNfse['cod_verificador_autenticidade'] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':link_nfse', $dadosNfse['link_nfse'] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':situacao', $dadosNfse['situacao_codigo_nfse'] ?? 1, PDO::PARAM_INT);
            $banco->bindValue(':updated_user', $this->m_userid, PDO::PARAM_INT);
            $banco->bindValue(':id', $idNotaFiscal, PDO::PARAM_INT);

            $banco->execute();
            
            return $banco->rowCount() > 0;
            
        } catch (\PDOException $e) {
            error_log("Erro ao atualizar nota fiscal: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca logs de eventos de NFS com filtros
     * @param array $filtros Array com os filtros (data_ini, data_fim, numero_nfs, tipo_evento, origem, codigo_retorno, cliente_id)
     * @return array Array com os logs encontrados
     */
    function selectLogNFS($filtros = array()) {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT 
                        e.ID,
                        e.ID_NFS,
                        e.CENTRO_CUSTO,
                        e.SERIE,
                        e.NUMERO,
                        e.ORIGEM,
                        e.CODIGO_RETORNO,
                        e.CREATED_USER,
                        e.CREATED_AT,
                        u.NOME as USUARIO_NOME,
                        n.TOMADOR_NOME_RAZAO_SOCIAL as CLIENTE_NOME
                    FROM EST_NOTA_FISCAL_SERVICO_EVENTOS e
                    LEFT JOIN AMB_USUARIO u ON u.USUARIO = e.CREATED_USER
                    LEFT JOIN EST_NOTA_FISCAL_SERVICO n ON n.ID = e.ID_NFS
                    WHERE 1=1";
            
            // Filtro por período
            if (!empty($filtros['data_ini']) && !empty($filtros['data_fim'])) {
                $data_ini = c_date::convertDateBdSh($filtros['data_ini']);
                $data_fim = c_date::convertDateBdSh($filtros['data_fim']);
                $sql .= " AND DATE(e.CREATED_AT) BETWEEN :data_ini AND :data_fim";
            }

            
            // Filtro por origem
            if (!empty($filtros['origem'])) {
                $sql .= " AND e.ORIGEM = :origem";
            }

            // Filtro por cliente
            if (!empty($filtros['cliente_id'])) {
                $sql .= " AND n.TOMADOR_ID = :cliente_id";
            }
            
            $sql .= " ORDER BY e.CREATED_AT DESC LIMIT 500";
            
            $banco->prepare($sql);
            
            // Bind dos parâmetros
            if (!empty($filtros['data_ini']) && !empty($filtros['data_fim'])) {
                $banco->bindValue(':data_ini', $data_ini, PDO::PARAM_STR);
                $banco->bindValue(':data_fim', $data_fim, PDO::PARAM_STR);
            }
        
            
            if (!empty($filtros['origem'])) {
                $banco->bindValue(':origem', $filtros['origem'], PDO::PARAM_STR);
            }
        
            if (!empty($filtros['cliente_id'])) {
                $banco->bindValue(':cliente_id', $filtros['cliente_id'], PDO::PARAM_INT);
            }
            
            $banco->execute();
            
            return $banco->fetchAll();
            
        } catch (\PDOException $e) {
            error_log("Erro ao buscar logs NFS: " . $e->getMessage());
            return array();
        }
    }

    /**
     * Busca o XML de retorno de um log específico
     * @param int $id ID do log
     * @return string XML de retorno ou string vazia se não encontrado
     */
    function selectXMLLog($id) {
        
        try {
            $banco = new c_banco_pdo();

            $sql = "SELECT XML_RETORNO 
                    FROM EST_NOTA_FISCAL_SERVICO_EVENTOS 
                    WHERE ID = :id";
            
            $banco->prepare($sql);
            $banco->bindValue(':id', $id, PDO::PARAM_INT);
            $banco->execute();
            
            $resultado = $banco->fetch();
            
            // Verifica se há dados e se o XML_RETORNO não está vazio
            if($resultado && isset($resultado['XML_RETORNO']) && !empty($resultado['XML_RETORNO'])){
                $xml = $resultado['XML_RETORNO'];
                
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true, 
                    'xml' => $xml
                ]);
                exit;
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false, 
                    'message' => 'XML não encontrado ou vazio'
                ]);
                exit;
            }
            
        } catch (\PDOException $e) {
            header('Content-Type: application/json; charset=utf-8');
            error_log("Erro ao buscar XML do log: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Erro ao buscar XML: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Exclui uma Nota Fiscal de Serviço do sistema
     * Apenas notas com situação 0 (Aberta) podem ser excluídas
     * 
     * @return void
     */
    function deletInvoice(?int $id = null) {
        // Validação básica
        if (empty($id)) {
            c_nfs_response::validationError('ID da nota fiscal não informado');
            return;
        }

        try {
            // Exclusão direta com validação de situação na query
            // Apenas exclui se SITUACAO = 0 (Aberta)
            $banco = new c_banco_pdo();
            $banco->prepare("DELETE FROM EST_NOTA_FISCAL_SERVICO WHERE ID = :id AND SITUACAO = 0");
            $banco->bindValue(':id', $id, PDO::PARAM_INT);
            $banco->execute();

            // Verifica resultado
            if ($banco->rowCount() > 0) {
                c_nfs_response::success('Nota fiscal excluída com sucesso');
            } else {
                c_nfs_response::error('Nota não encontrada ou não está aberta. Apenas notas abertas podem ser excluídas.', [], null, 400);
            }
            
        } catch (\PDOException $e) {
            error_log("Erro ao excluir NFS: " . $e->getMessage());
            c_nfs_response::error('Erro ao excluir nota fiscal', [], null, 500);
        }
    }

        /**
     * Exclui uma Nota Fiscal de Serviço do sistema
     * Apenas notas com situação 0 (Aberta) podem ser excluídas
     * 
     * @return void
     */
    function deletInvoiceError(?int $id = null) {
        // Validação básica
        if (empty($id)) {
            c_nfs_response::validationError('ID da nota fiscal não informado');
            return;
        }

        try {

            $banco = new c_banco_pdo();
            $banco->prepare("DELETE FROM EST_NOTA_FISCAL_SERVICO WHERE ID = :id AND SITUACAO = 0");
            $banco->bindValue(':id', $id, PDO::PARAM_INT);
            $banco->execute();

            // Verifica resultado
            if (!$banco->rowCount() > 0) {
                throw new Exception('Nota não encontrada ou não está aberta');
            }

            return true;
            
        } catch (\PDOException $e) {
            error_log("Erro ao excluir NFS: " . $e->getMessage());
            return false;
        }
    }

    function selectParamterNfs(int $filial)
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT NFS_USER, NFS_PASSWORD FROM EST_PARAMETRO WHERE FILIAL = :filial");
        $banco->bindValue(':filial', $filial, PDO::PARAM_INT);
        $banco->execute();
        return $banco->fetchAll();
    }
}

