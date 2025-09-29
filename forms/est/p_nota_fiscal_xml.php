<?php

/**
 * @package   admSistemas
 * @name      p_nota_fiscal_xml
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos<jhon.kened11@gmail|jhonkenedy@admsistema.com.br>
 * @date      29/05/2024
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')) : exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_date.php");

//Class P_Nota_Fiscal
class p_nota_fiscal_xml extends c_nota_fiscal
{
    private $m_submenu   = NULL;
    private $m_letra     = NULL;
    private $m_opcao     = NULL;
    private $m_par       = NULL;
    private $m_id        = NULL;
    private $m_form_prod = NULL;
    private $m_status    = NULL;
    private $m_mensagem  = NULL;

    public  $smarty = NULL;

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct($id=null ,$submenu=null)
    {
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        if($submenu !== null){
            $this->m_submenu = $submenu;
        }else{
            $this->m_submenu = !empty($this->parmPost['submenu']) ? $this->parmPost['submenu'] : (!empty($this->parmGet['submenu']) ? $this->parmGet['submenu'] : '');
        }

        if($id !== null){
            $this->m_id = $id;
        }else{
            $this->m_id = !empty($this->parmPost['id']) ? $this->parmPost['id'] : (!empty($this->parmGet['id']) ? $this->parmGet['id'] : '');
        }
        
        $this->m_opcao = !empty($this->parmPost['opcao']) ? $this->parmPost['opcao'] : (!empty($this->parmGet['opcao']) ? $this->parmGet['opcao'] : '');
        $this->m_letra = !empty($this->parmPost['letra']) ? $this->parmPost['letra'] : (!empty($this->parmGet['letra']) ? $this->parmGet['letra'] : '');
        $this->m_form_prod = !empty($this->parmPost['letra']) ? $this->parmPost['letra'] : (!empty($this->parmGet['letra']) ? $this->parmGet['letra'] : '');
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle(){
        switch ($this->m_submenu) {
            case 'validaNf':
                //testa dados da nf
                $validaNota = $this->validaNotaFiscal($this->m_id);
                if($validaNota !== null){
                    $this->mostraNotaXml(null, $validaNota, null);
                    break;
                }
                // testa dados dos produtos da nf
                $validaProd = $this->validaProdutos($this->m_id);
                if($validaProd !== null){
                    $this->mostraNotaXml(null, $validaProd, null);
                    break;
                }
                $this->mostraNotaXml(null, null, null);
                break;
            case 'updateProduto':
                $updatingProduct = $this->updatingProduct($this->parmPost);
                if($updatingProduct['status'] == false){
                    $json = json_encode($updatingProduct);

                    $escaped_string = htmlspecialchars($json, ENT_QUOTES, 'UTF-8');
                    $return = $escaped_string;
                }else{
                    $return = true;
                }
                $this->mostraNotaXml(null, null, $return);
            break;
            case 'carregar':
                    $this->mostraNotaXml();
                break;
            default: //default vazio para evitar form incorreto, o display executa essa tela antes
        }
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraNotaXml($mensagem = NULL,  $divergencias = NULL, $retorno =null){
        if ($this->m_id != '') {
            $this->setId($this->m_id);
            $arrayNotaFiscal = $this->select_nota_fiscal();
            $lanc = $this->existeNotaFiscalProduto($this->m_id);
            $arrayEmitente =  $this->select_empresa_centro_custo($this->m_empresacentrocusto);

            //search financial data
            $objFinanceiro = new c_lancamento();
            
            //checks if there is finance for the request
            $arrayFinanceiro = $objFinanceiro->select_lancamento_doc('PED', $arrayNotaFiscal[0]["DOC"]);
            if(!is_array($arrayFinanceiro)){
                $arrayFinanceiro = $objFinanceiro->calcularParcelas($arrayNotaFiscal[0]["CONDPGTO"], $arrayNotaFiscal[0]["TOTALNF"]);
            }
            
            $pessoaDestOBJ = new c_conta();
            $pessoaDestOBJ->setId($arrayNotaFiscal[0]['PESSOA']);
            $arrayDestinatario = $pessoaDestOBJ->select_conta();

            //search data transporter
            if($arrayNotaFiscal[0]["TRANSPORTADOR"] !== '' and $arrayNotaFiscal[0]["TRANSPORTADOR"] !== null){
                $pessoaDestOBJ->setId($arrayNotaFiscal[0]["TRANSPORTADOR"]);
                $arrayTransp = $pessoaDestOBJ->select_conta();
            }
        }

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('id', $this->m_id);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('pathBib', ADMhttpBib);
        $this->smarty->assign('lancFinanceiro', $arrayFinanceiro);
        //variavel para retorno do altera produto
        $this->smarty->assign("return_alter_product", $retorno);
        //variavel de divergencias
        $this->smarty->assign("divergencias", $divergencias);

        //cabecalho nota
        //EMITEMTE/DESTINATARIO
        $this->smarty->assign('nomeEmpresa', $arrayEmitente[0]["NOMEEMPRESA"]);
        $this->smarty->assign('endereco', $arrayEmitente[0]["ENDERECO"]);
        $this->smarty->assign('enderecoNum', $arrayEmitente[0]["NUMERO"]);
        $this->smarty->assign('enderecoCom', $arrayEmitente[0]["COMPLEMENTO"]);
        $this->smarty->assign('cidade', $arrayEmitente[0]["CIDADE"]);
        $this->smarty->assign('bairro', $arrayEmitente[0]["BAIRRO"]);
        $this->smarty->assign('cep', $arrayEmitente[0]["CEP"]);
        $this->smarty->assign('uf', $arrayEmitente[0]['UF']);
        $this->smarty->assign('fone', $arrayEmitente[0]["FONENUM"]);
        $this->smarty->assign('codigoOperacao', $arrayNotaFiscal[0]["TIPO"]);
        $this->smarty->assign('numeroNf', $arrayNotaFiscal[0]["NUMERO"]);
        $this->smarty->assign('serie', $arrayNotaFiscal[0]["SERIE"]);
        $this->smarty->assign('descNaturezaOperacao', $arrayNotaFiscal[0]["NATOPERACAO"]);
        $this->smarty->assign('protocolo', $arrayNotaFiscal[0]["NPROT"]);
        $this->smarty->assign('emitenteIe', $arrayEmitente[0]["INSCESTADUAL"]);
        $this->smarty->assign('emitenteCnpj', $arrayEmitente[0]["CNPJ"]);
        $this->smarty->assign('destRazao', $arrayDestinatario[0]["NOME"]);
        $this->smarty->assign('destCnpj', $arrayDestinatario[0]["CNPJCPF"]);
        $this->smarty->assign('dataEmissao', $arrayNotaFiscal[0]["EMISSAO"]);
        $this->smarty->assign('destEndereco', $arrayDestinatario[0]["ENDERECO"]);
        $this->smarty->assign('destEnderecoNum', $arrayDestinatario[0]["NUMERO"]);
        $this->smarty->assign('destBairro', $arrayDestinatario[0]["BAIRRO"]);
        $this->smarty->assign('destCep', $arrayDestinatario[0]["CEP"]);
        $this->smarty->assign('destBairro', $arrayDestinatario[0]["BAIRRO"]);
        $this->smarty->assign('destMunicipio', $arrayDestinatario[0]["CIDADE"]);
        $destFone = c_tools::limparCaracteresEspeciais($arrayDestinatario[0]["CELULAR"]);
        $this->smarty->assign('destFone', $destFone);
        $this->smarty->assign('destUf', $arrayDestinatario[0]["UF"]);
        $this->smarty->assign('destIe', $arrayDestinatario[0]["INSCESTRG"]);
        $this->smarty->assign('dataEntradaSaida', $arrayNotaFiscal[0]["EMISSAO"]);
        $this->smarty->assign('modFrete', $arrayNotaFiscal[0]["MODFRETE"]);
        
        //TRANSPORTADORA
        if(is_array($arrayTransp)){
            $this->smarty->assign('arrayTransp', $arrayTransp);
        }else{
            $this->smarty->assign('arrayTransp', null);
        }

        if ($this->m_par[3] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[3]);

        if ($this->m_par[4] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
        } else
            $this->smarty->assign('dataFim', $this->m_par[4]);

        // PESSOA
        if ($this->m_par[7] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setPessoa($this->m_par[7]);
            $this->setNomePessoa();
            $this->smarty->assign('pessoa', $this->m_par[7]);
            $this->smarty->assign('nome', $this->getNomePessoa());
        }

        // BENEFICIO
        $consulta = new c_banco();
        $sql = "select * from EST_NAT_OP_BENEFICIO ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        for ($i = 1; $i < count($result); $i++) {
            $cbenef_ids[$i] = $result[$i]['CBENEF'];
            $cbenef_names[$i] = $result[$i]['CBENEF'].' - '.$result[$i]['DESCRICAO'];
        }
        //sempre que não for obrigatorio, incluir o primeiro registro vazio.
        array_unshift($cbenef_names, " ");
        array_unshift($cbenef_ids, "");
        $this->smarty->assign('m_cbenef_ids', $cbenef_ids);
        $this->smarty->assign('m_cbenef_names', $cbenef_names);

        // ORIGEM
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i] = $result[$i]['ID'];
            $grupo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('m_origem_ids', $grupo_ids);
        $this->smarty->assign('m_origem_names', $grupo_names);

        // TRIBUTO ICMS
        $consulta = new c_banco();
        $sql = "select * from amb_empresa where (centrocusto=".$this->m_empresacentrocusto.")";
        $emp = $consulta->exec_sql($sql);
        $crt=$emp[0]['REGIMETRIBUTARIO'];
        if($crt=='3'){
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms') order by id";
        }else{    
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='CSOSN') order by id";
        }
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $tribIcms_ids[$i] = $result[$i]['ID'];
            $tribIcms_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('m_tribIcms_ids', $tribIcms_ids);
        $this->smarty->assign('m_tribIcms_names', $tribIcms_names);

        // MODALIDADE BC
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBc') order by id";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        for ($i = 0; $i < count($result); $i++){
            $modBc_ids[$i] = $result[$i]['ID'];
            $modBc_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('m_modBc_ids', $modBc_ids);
        $this->smarty->assign('m_modBc_names', $modBc_names);

        // MODALIDADE BC ST
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBcSt') order by id";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        for ($i = 0; $i < count($result); $i++){
            $modBcSt_ids[$i] = $result[$i]['ID'];
            $modBcSt_names[$i] = $result[$i]['DESCRICAO'];     
        }
        //sempre que não for obrigatorio, incluir o primeiro registro vazio.
        array_unshift($modBcSt_names, "Selecione uma op&ccedil;&atilde;o");
        array_unshift($modBcSt_ids, "");
        $this->smarty->assign('m_modBcSt_ids', $modBcSt_ids);
        $this->smarty->assign('m_modBcSt_names', $modBcSt_names);

        // CST IPI
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='CSTIPI')";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
 
        for ($i = 0; $i < count($result); $i++){
                $cstIpi_ids[$i] = $result[$i]['ID'];
                $cstIpi_names[$i] = $result[$i]['DESCRICAO'];
        }
        //sempre que não for obrigatorio, incluir o primeiro registro vazio.
        array_unshift($cstIpi_names, "Selecione uma op&ccedil;&atilde;o");
        array_unshift($cstIpi_ids, "");
        $this->smarty->assign('m_cstIpi_ids', $cstIpi_ids);
        $this->smarty->assign('m_cstIpi_names', $cstIpi_names);

        // CST PIS/COFINS
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='PISCOFINS') ORDER BY CAST(ID AS UNSIGNED) ASC;";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        for ($i = 0; $i < count($result); $i++){
                $pisCofins_ids[$i] = $result[$i]['ID'];
                $pisCofins_names[$i] = $result[$i]['DESCRICAO'];
        }
        //sempre que não for obrigatorio, incluir o primeiro registro vazio.
        array_unshift($pisCofins_names, "Selecione uma op&ccedil;&atilde;o");
        array_unshift($pisCofins_ids, "");
        $this->smarty->assign('m_cstCofins_ids', $pisCofins_ids);
        $this->smarty->assign('m_cstCofins_names', $pisCofins_names);
        $this->smarty->assign('m_cstPis_ids', $pisCofins_ids);
        $this->smarty->assign('m_cstPis_names', $pisCofins_names);

        // Divergencias
        $this->smarty->assign('divergencias', $divergencias);
        
        $this->smarty->display('nota_fiscal_xml_mostra.tpl');
    }
    //-------------------------------------------------------------

    private function updatingProduct($data=null){
        if($data == null){
            return false;
        }
        // Produto
        $objNfProd = new c_nota_fiscal_produto;
        $objNfProd->setId($data['m_idProd']);
        $objNfProd->setNrSerie($data['m_serie']);
        $objNfProd->setOrdem($data['m_ordemServico']);
        $objNfProd->setCfop($data["m_cfop"]);
        $objNfProd->setNcm($data['m_ncm']);
        $objNfProd->setCest($data['m_cest']);
        $objNfProd->setCBenef($data['m_cbenef']);
        $objNfProd->setLote($data['m_lote']);
        $objNfProd->setDataFabricacao($data['m_dataFabricacao']);
        $objNfProd->setDataValidade($data['m_dataValidade']);
        $objNfProd->setDataGarantia($data['m_dataGarantia']);
        // ICMS
        $objNfProd->setOrigem($data['m_origem']);
        $objNfProd->setTribIcms($data['m_tribIcms']);
        $objNfProd->setModBc($data['m_modBc']);
        $objNfProd->setBcIcms($data['m_bcIcms']);
        $objNfProd->setAliqIcms($data['m_bcIcms']);
        $objNfProd->setValorIcms($data['m_valorIcms']);
        $objNfProd->setPercReducaoBc($data['m_percReducaoBc']);
        $objNfProd->setValorIcmsOperacao($data['m_valorIcmsOperacao']);
        $objNfProd->setPercDiferido($data['m_percDiferimento']);
        $objNfProd->setValorIcmsDiferido($data['m_valorIcmsDiferimento']);
        // Substituicao tributaria
        $objNfProd->setModBcSt($data['m_modBcSt']);
        $objNfProd->setPercMvaSt($data['m_percMvaSt']);
        $objNfProd->setPercReducaoBcSt($data['m_percReducaoBcSt']);
        $objNfProd->setValorBcSt($data['m_valorBcSt']);
        $objNfProd->setAliqIcmsSt($data['m_aliqIcmsSt']);
        $objNfProd->setValorIcmsSt($data['m_valorIcmsSt']);
        // Fundo de combate a pobreza ST
        $objNfProd->setBcFcpSt($data['m_bcFcpSt']);
        $objNfProd->setAliqFcpSt($data['m_aliqFcpSt']);
        $objNfProd->setValorFcpSt($data['m_valorFcpSt']);
        // Fundo de combate a pobreza UF destino
        $objNfProd->setBcFcpUfDest($data['m_bcFcpUfDest']);
        $objNfProd->setAliqFcpUfDest($data['m_aliqFcpUfDest']);
        $objNfProd->setValorFcpUfDest($data['m_valorFcpUfDest']);
        // Icms UF destino
        $objNfProd->setBcIcmsUfDest($data['m_BcIcmsUfDest']);
        $objNfProd->setAliqIcmsUfDest($data['m_aliqIcmsUfDest']);
        $objNfProd->setValorIcmsUfDest($data['m_valorIcmsUfDest']);
        // Icms Interestadual 
        $objNfProd->setAliqIcmsInter($data['m_aliqIcmsInter']);
        $objNfProd->setAliqIcmsInterPart($data['m_aliqIcmsInterPart']);
        $objNfProd->setValorIcmsUFRemet($data['m_valorIcmsUfRemet']);
        // IPI
        $objNfProd->setCstIpi($data['m_cstIpi']);
        $objNfProd->setBcIpi($data['m_bcIpi']);
        $objNfProd->setAliqIpi($data['m_aliqIpi']);
        $objNfProd->setValorIpi($data['m_valorIpi']);
        // PIS
        $objNfProd->setCstPis($data['m_cstPis']);
        $objNfProd->setBcPis($data['m_bcPis']);
        $objNfProd->setAliqPis($data['m_aliqPis']);
        $objNfProd->setValorPis($data['m_valorPis']);
        // Cofins
        $objNfProd->setCstCofins($data['m_cstCofins']);
        $objNfProd->setBcCofins($data['m_bcCofins']);
        $objNfProd->setAliqCofins($data['m_aliqCofins']);
        $objNfProd->setValorCofins($data['m_valorCofins']);
        // Alter
        $process = $objNfProd->alteraNotaFiscalProdutoXml();
        if($process['status'] == false){
            return $process;
        }else{
            return true;
        }
    }
}
//	END OF THE CLASS

// Rotina principal - cria classe
$notaFiscalXml = new p_nota_fiscal_xml();
$notaFiscalXml->controle();
