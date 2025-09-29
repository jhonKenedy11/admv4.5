<?php
/**
 * @package   astec
 * @name      p_nota_fiscal_produto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/ped/c_parametro.php");
if ($this->opcao == 'devolucao'):
require_once($dir."/../../forms/est/p_nota_fiscal.php");
endif;
require_once($dir."/../../class/ped/c_pedido_venda_nf.php");

//Class P_Nota_Fiscal
Class p_nota_fiscal_produto extends c_nota_fiscal_produto {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_nfOBJ = NULL;
    private $m_opcao = NULL;
    private $m_pesq = null;
    public $smarty = NULL;
    public $readonly = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * @param VARCHAR $opcao
     * @param VARCHAR $pesquisa
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT); 

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
        if(($this->parmPost["submenu"] !== "") && ($this->parmPost["submenu"] !== null)){
            $this->m_submenu = $this->parmPost['submenu'];
        }elseif($this->parmGet["submenu"] !== '' && $this->parmGet["submenu"] !== null){
            $this->m_submenu = $this->parmGet["submenu"] ;
        }else{
            $this->m_submenu = '';
        }
        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_pesquisa = $this->parmPost['pesquisa'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        $this->telaOrigem = $this->parmPost['telaOrigem'];

        // variaveis form
        $this->m_pessoa = $this->parmPost['pessoa'];
        
        //$this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : "");
        if(($this->parmPost["id"] !== "") && ($this->parmPost["id"] !== null)){
            $this->setId($this->parmPost["id"]);
        }elseif($this->parmGet["id"] !== '' && $this->parmGet["id"] !== null){
            $this->setId($this->parmGet["id"]);
        }else{
            $this->setId('');
        }
        //$this->setIdNf(isset($this->parmPost['idnf']) ? $this->parmPost['idnf'] : "");
        if(($this->parmPost["idnf"] !== "") && ($this->parmPost["idnf"] !== null)){
            $this->setIdNf($this->parmPost["idnf"]);
        }elseif($this->parmGet["idnf"] !== '' && $this->parmGet["idnf"] !== null){
            $this->setIdNf($this->parmGet["idnf"]);
        }else{
            $this->setIdNf('');
        }
        $this->setCodProduto(isset($this->parmPost['codProduto']) ? $this->parmPost['codProduto'] : "");
        $this->setDescricao(isset($this->parmPost['descProduto']) ? $this->parmPost['descProduto'] : "");
        $this->setUnidade(isset($this->parmPost['unidade']) ? $this->parmPost['unidade'] : "");
        $this->setQuant(isset($this->parmPost['quant']) ? $this->parmPost['quant'] : 0.00);
        $this->setUnitario(isset($this->parmPost['unitario']) ? $this->parmPost['unitario'] : 0.00);
        $this->setDesconto(isset($this->parmPost['desconto']) ? $this->parmPost['desconto'] : 0.00);
        $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : 0.00);
        $this->setOrigem(isset($this->parmPost['origem']) ? $this->parmPost['origem'] : "");
        $this->setCfop(isset($this->parmPost['cfop']) ? $this->parmPost['cfop'] : "0");
        $this->setTribIcms(isset($this->parmPost['tribIcms']) ? $this->parmPost['tribIcms'] : "");
        $this->setBcIcms(isset($this->parmPost['bcIcms']) ? $this->parmPost['bcIcms'] : 0.00);

        $this->setPercReducaoBc(isset($this->parmPost['percReducaoBc']) ? $this->parmPost['percReducaoBc'] : 0.00);
        $this->setPercDiferido(isset($this->parmPost['percDiferido']) ? $this->parmPost['percDiferido'] : 0.00);
        $this->setValorIcmsDiferido(isset($this->parmPost['valorIcmsDiferido']) ? $this->parmPost['valorIcmsDiferido'] : 0.00);
        $this->setValorIcmsOperacao(isset($this->parmPost['valorIcmsOperacao']) ? $this->parmPost['valorIcmsOperacao'] : 0.00);

        $this->setValorIcms(isset($this->parmPost['valorIcms']) ? $this->parmPost['valorIcms'] : 0.00);
        $this->setBaseCalculoIpi(isset($this->parmPost['baseCalculoIpi']) ? $this->parmPost['baseCalculoIpi'] : 0.00);
        $this->setCstIpi(isset($this->parmPost['cstIpi']) ? $this->parmPost['cstIpi'] : "");
        $this->setValorIpi(isset($this->parmPost['valorIpi']) ? $this->parmPost['valorIpi'] : 0.00);
        $this->setAliqIpi(isset($this->parmPost['aliqIpi']) ? $this->parmPost['aliqIpi'] : 0.00);
        $this->setAliqIcms(isset($this->parmPost['aliqIcms']) ? $this->parmPost['aliqIcms'] : 0.00);
        $this->setCstPis(isset($this->parmPost['cstPis']) ? $this->parmPost['cstPis'] : "");
        $this->setBcPis(isset($this->parmPost['bcPis']) ? $this->parmPost['bcPis'] : 0.00);
        $this->setAliqPis(isset($this->parmPost['aliqPis']) ? $this->parmPost['aliqPis'] : 0.00);
        $this->setValorPis(isset($this->parmPost['valorPis']) ? $this->parmPost['valorPis'] : 0.00);
        $this->setCstCofins(isset($this->parmPost['cstCofins']) ? $this->parmPost['cstCofins'] : 0.00);
        $this->setBcCofins(isset($this->parmPost['bcCofins']) ? $this->parmPost['bcCofins'] : 0.00);
        $this->setAliqCofins(isset($this->parmPost['aliqCofins']) ? $this->parmPost['aliqCofins'] : 0.00);
        $this->setValorCofins(isset($this->parmPost['valorCofins']) ? $this->parmPost['valorCofins'] : 0.00);
        $this->setNcm(isset($this->parmPost['ncm']) ? $this->parmPost['ncm'] : "");
        $this->setCest(isset($this->parmPost['cest']) ? $this->parmPost['cest'] : "");
        $this->setNrSerie(isset($this->parmPost['nrserie']) ? $this->parmPost['nrserie'] : "");
        $this->setLote(isset($this->parmPost['lote']) ? $this->parmPost['lote'] : "");
        $this->setDataFabricacao(isset($this->parmPost['dataFabricacao']) ? $this->parmPost['dataFabricacao'] : "");
        $this->setDataValidade(isset($this->parmPost['dataValidade']) ? $this->parmPost['dataValidade'] : "");
        $this->setDataGarantia(isset($this->parmPost['dataGarantia']) ? $this->parmPost['dataGarantia'] : "");
        $this->setDataConferencia(isset($this->parmPost['dataConferencia']) ? $this->parmPost['dataConferencia'] : "");
        $this->setOrdem(isset($this->parmPost['ordem']) ? $this->parmPost['ordem'] : "");
        $this->setProjeto(isset($this->parmPost['projeto']) ? $this->parmPost['projeto'] : "");
        $this->setCBenef(isset($this->parmPost['cbenef']) ? $this->parmPost['cbenef'] : "");

        $this->setModBc(isset($this->parmPost['modBc']) ? $this->parmPost['modBc'] : "");
        $this->setModBcSt(isset($this->parmPost['modBcSt']) ? $this->parmPost['modBcSt'] : "");
        $this->setPercMvaSt(isset($this->parmPost['percMvaSt']) ? $this->parmPost['percMvaSt'] : 0.00);
        $this->setPercReducaoBcSt(isset($this->parmPost['percReducaoBcSt']) ? $this->parmPost['percReducaoBcSt'] : 0.00);
        $this->setValorBcSt(isset($this->parmPost['valorbcst']) ? $this->parmPost['valorbcst'] : 0.00);
        $this->setAliqIcmsSt(isset($this->parmPost['aliqicmsst']) ? $this->parmPost['aliqicmsst'] : 0.00); 
        $this->setValorIcmsSt(isset($this->parmPost['valoricmsst']) ? $this->parmPost['valoricmsst'] : 0.00); 


        $this->setBcFcpSt(isset($this->parmPost['bcFcpSt']) ? $this->parmPost['bcFcpSt'] : 0.00);
        $this->setAliqFcpSt(isset($this->parmPost['aliqFcpSt']) ? $this->parmPost['aliqFcpSt'] : 0.00);
        $this->setValorFcpSt(isset($this->parmPost['valorFcpSt']) ? $this->parmPost['valorFcpSt'] : 0.00);        
        
        $this->setBcFcpUfDest(isset($this->parmPost['bcfcpufdest']) ? $this->parmPost['bcfcpufdest'] : 0.00);
        $this->setAliqFcpUfDest(isset($this->parmPost['aliqfcpufdest']) ? $this->parmPost['aliqfcpufdest'] : 0.00);
        $this->setValorFcpUfDest(isset($this->parmPost['valorfcpufdest']) ? $this->parmPost['valorfcpufdest'] : 0.00);
        
        $this->setBcIcmsUfDest(isset($this->parmPost['bcicmsufdest']) ? $this->parmPost['bcicmsufdest'] : 0.00);
        $this->setAliqIcmsUfDest(isset($this->parmPost['aliqicmsufdest']) ? $this->parmPost['aliqicmsufdest'] : 0.00);
        $this->setAliqIcmsInter(isset($this->parmPost['aliqicmsinter']) ? $this->parmPost['aliqicmsinter'] : 0.00);
        $this->setAliqIcmsInterPart(isset($this->parmPost['aliqicmsinterpart']) ? $this->parmPost['aliqicmsinterpart'] : 0.00);       
        $this->setValorIcmsUfDest(isset($this->parmPost['valoricmsufdest']) ? $this->parmPost['valoricmsufdest'] : 0.00);
        $this->setValorIcmsUfRemet(isset($this->parmPost['valoricmsufremet']) ? $this->parmPost['valoricmsufremet'] : 0.00);
        $this->setRFreteProd(isset($this->parmPost['rFrete']) ? $this->parmPost['rFrete'] : 0.00);
        $this->setRDespProd(isset($this->parmPost['rDesp']) ? $this->parmPost['rDesp'] : 0.00);

        $this->setValorBaseCalculoStRetido($this->parmGet['valorBaseCalculoStRetido'] ?? $this->parmPost['valorBaseCalculoStRetido'] ?? 0.00);
        $this->setValorIcmsStRetido($this->parmGet['valorIcmsStRetido'] ?? $this->parmPost['valorIcmsStRetido'] ?? 0.00);
        $this->setValorIcmsSubstituto($this->parmGet['valorIcmsSubstituto'] ?? $this->parmPost['valorIcmsSubstituto'] ?? 0.00);
    
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : "");

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Nota Fiscal Produtos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8,9,10,11,12]"); 
        $this->smarty->assign('disableSort', "[ 12 ]"); 
        $this->smarty->assign('numLine', "25"); 
                

        // include do javascript
        // include ADMjs . "/est/s_nota_fiscal_produto.js";
    }


//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        $this->m_nfOBJ = new c_nota_fiscal;
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->m_nfOBJ->existeNotaFiscalBaixa($this->getIdNf())) {
                    $this->mostraNotaFiscalProduto('Nota fiscal foi recebida, n&atilde;o sendo possivel incluir novos produtos.');
                } else {
                    $this->setUnidade('');
                    $this->setTribIcms('');
                    $this->setOrigem('');
                    $this->setQuant('0.00');
                    $this->setDataConferencia('');
                    $this->desenhaCadastroNotaFiscalProduto();
                }
                //if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){

                break;
            case 'calcular':
                try {
                    // CADASTRA ITENS NF
                    $objNotaFiscal = new c_nota_fiscal();
                    $objProduto = new c_produto();
                    // busca produto    ===>>> pode buscar dados dos produtos na funcao this->select_pedido_item_id();
                    $objProduto->setId($this->getCodProduto());
                    $arrProduto = $objProduto->select_produto();
                    // verificar se acchou o produto
                    if (!isset($arrProduto)):
                        $this->m_msg = "Produto não localizado ".$this->getDescricao();
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;

                    $this->setUnitario($arrProduto[0]['VENDA']);
                    $this->setOrigem($arrProduto[0]['ORIGEM']);
                    $this->setTribIcms($arrProduto[0]['TRIBICMS']);
                    $this->setNcm($arrProduto[0]['NCM']);
                    $this->setCest($arrProduto[0]['CEST']);
                    
                    $this->setTotal($this->calculaTotalProduto($this), FALSE);
                    //$this->setTotal($this->getTotal('F'));

                    // set dados nota
                    /*$objNotaFiscal->setId($this->getIdNf());
                    $objNotaFiscal->setNotaFiscal();

                    $objVendaNf = new c_pedidoVendaNf;
                    $result = $objVendaNf->calculaImpostosNfe($this, 
                                $objNotaFiscal->getIdNatop(), 
                                $objNotaFiscal->getUfPessoa(), 
                                $objNotaFiscal->getTipoPessoa(), $this->m_empresacentrocusto);

                    if (!$result):
                        $this->m_msg = "Tributos não localizado ".$this->getDescricao()." Nat. Operação:".$objNotaFiscal->getIdNatop().
                            " UF:".$objNotaFiscal->getUfPessoa()." Tipo:".$objNotaFiscal->getTipoPessoa().
                            " CST:".$this->getOrigem().$this->getTribIcms().
                            " NCM:".$this->getNcm()." CEST:".$this->getCest();
                        throw new Exception( $this->m_msg );
                    endif;*/
                    
/*
                        $this->setCustoProduto($arrItemPedido[$i]['CUSTOPRODUTO']);

                        $this->setNrSerie($arrProdutoEstoqueReserva[$r]['NS']);
                        $this->setLote($arrProdutoEstoqueReserva[$r]['FABLOTE']);
                        $this->setDataValidade($arrProdutoEstoqueReserva[$r]['FABDATAVALIDADE']);
                        $this->setDataFabricacao($arrProdutoEstoqueReserva[$r]['FABDATAFABRICACAO']);
                        $this->setDataGarantia('');

                        $this->setOrdem($arrItemPedido[$i]['ORDEM']);
                        $this->setProjeto($arrItemPedido[$i]['PROJETO']);
                        $this->setDataConferencia($arrItemPedido[$i]['DATACONFERENCIA']);
  */                      
                        
                }catch (Exception $e) {
                    $this->m_submenu = "cadastrar";
                    $this->desenhaCadastroNotaFiscalProduto($this->m_msg);
                    break;
                }

                $this->m_submenu = "cadastrar";
                $this->desenhaCadastroNotaFiscalProduto();
                break;
            case 'alterar':
                if ($this->m_nfOBJ->existeNotaFiscalBaixa($this->getIdNf())) {
                    $this->mostraNotaFiscalProduto('Nota fiscal foi recebida, n&atilde;o sendo possivel alterar produto.');
                } else {
                    $this->setNotaFiscalProduto();
                    $this->desenhaCadastroNotaFiscalProduto();
                }
                //if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'A')){
                //}
                break;         
            case 'alterarDev':
                if ($this->m_nfOBJ->existeNotaFiscalBaixa($this->getIdNf())) {
                    $this->mostraNotaFiscalProduto('Nota fiscal foi recebida, n&atilde;o sendo possivel alterar produto.');
                } else {
                    $this->setNotaFiscalProduto();
                    $this->desenhaCadastroNotaFiscalProduto();
                }
                break;       
            case 'inclui':
                //if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){
                    try {
                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
                        $integraFin = $parametros->getField("INTEGRAFIN", "FILIAL=".$this->m_empresacentrocusto);
                        $validaNfAuto = $parametros->getField("VALIDANFAUTO", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();                        

                        $msg = '';
                        $objProduto = new c_produto();
                        $objProdutoQtde = new c_produto_estoque();

                        // Consluta na table de produtos para pegar os dados
                        $arrProduto = $objProdutoQtde->produtoQtdeCC($this->getCodProduto(), $this->m_empresacentrocusto);

                        if (!isset($arrProduto)):
                            throw new Exception( "Produto não localizado ".$this->getDescricao());
                        endif;

                        $uniFrac = $arrProduto[0]['UNIFRACIONADA'];

                        $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));

                        // testa se controla estoque
                        if ($ifControlaEstoque)
                        { 
                            $tquant = $this->getQuant();
                            $tquantarr = $arrProduto[0]['QUANTIDADE'];
                            $tvendaarr = $arrProduto[0]['VENDA'];
                            $tvenda = $this->getUnitario();
                            $ifquant = ($tquant <= $tquantarr);
                            $ifpreco = (($tvendaarr > floatval(0)) or ($tvenda> floatval(0)));

                            if ($ifpreco and $ifquant) { // TESTA PRECO E QUANT DISPONIVEL

                                // reserva produto
                                $result = $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, 
                                                                            "NFS", 
                                                                            $this->getIdNf(), 
                                                                            $this->getCodProduto(), 
                                                                            (int) $this->getQuant());

                                if(!$result){

                                    throw new Exception( "Produto ".$this->getDescricao()." NÃO foi reservado - entrar em contato com suporte!! " );
                                }
                            } else { 

                                throw new Exception( $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel" );
                            }
                        }

                        // insere produto nf
                        $this->setDataConferencia('');

                        //search date items
                        $objProduto->setId($this->parmPost["codProduto"]);
                        $arrayProdutoData = $objProduto->select_produto();

                        if($arrayProdutoData[0]['CODFABRICANTE'] !== '' and $arrayProdutoData[0]['CODFABRICANTE'] !== null){
                            $this->setCodigoNota($arrayProdutoData[0]['CODFABRICANTE']);
                        }

                        $result = $this->incluiNotaFiscalProduto();
                        
                        //Calcula rateios
                        $this->calculaRateios($this->getIdNf());

                        if(!$result){

                            throw new Exception( "Produto ".$this->getDescricao()." não cadastrado " );
                        }

                        $result = $this->atualizaTotalNfe($this->getIdNf());

                        if(!$result){

                            throw new Exception( "Produto ".$this->getDescricao()." TOTAL NÃO CALCULADO - entrar em contato com suporte!! " );
                        }

                    }catch (Exception $e) {

                        $msg = $e->getMessage();

                    }finally {

                        if ($msg == ''){

                            $this->mostraNotaFiscalProduto();
                        } else {

                            $this->m_submenu = "cadastrar";
                            $this->setQuant($this->getQuant('B'));
                            $this->setUnitario($this->getUnitario('B'));
                            $this->desenhaCadastroNotaFiscalProduto($msg);
                        }
                    }
                
                //}		
                break;
            case 'altera':
                //if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                    try {
                        $msg = '';
                        $tipoMsg = 'sucesso';
                        $this->setTotal($this->calculaTotalProduto($this), TRUE);
                        //$this->setTotal($this->getTotal('B'));
                        $result = $this->alteraNotaFiscalProduto();
                        if(!$result):
                            throw new Exception( "Produto ".$this->getCodProduto()." não atulalizado" );
                        endif;
                        $this->calculaRateios($this->getIdNf());
                        $result = $this->atualizaTotalNfe($this->getIdNf());
                        if(!$result):
                            throw new Exception( "Produto ".$this->getDescricao()." TOTAL NÃO CALCULADO - entrar em contato com suporte!! " );
                        endif;
                    } catch (Exception $e) {
                        $tipoMsg = 'alerta';
                        $msg = $e->getMessage();
                    }finally {
                        if ($msg == ''):
                            $this->mostraNotaFiscalProduto();
                        else:
                            $this->setQuant($this->getQuant('B'));
                            $this->setUnitario($this->getUnitario('B'));
                            $this->desenhaCadastroNotaFiscalProduto($msg, $tipoMsg);
                        endif;
                    }
                    
                    
                //}
                break;
                case 'alterarDevolucaoNf':
                    //if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                        try {
                            $msg = '';
                            $tipoMsg = 'sucesso';
                            $this->setTotal($this->calculaTotalProduto($this), TRUE);
                            //$this->setTotal($this->getTotal('B'));
                            $result = $this->alteraNotaFiscalProduto();
                            if(!$result):
                                throw new Exception( "Produto ".$this->getCodProduto()." não atulalizado" );
                            endif;
                            $result = $this->atualizaTotalNfe($this->getIdNf());
                            if(!$result):
                                throw new Exception( "Produto ".$this->getDescricao()." TOTAL NÃO CALCULADO - entrar em contato com suporte!! " );
                            endif;
                        } catch (Exception $e) {
                            $tipoMsg = 'alerta';
                            $msg = $e->getMessage();
                        }finally {
                            if ($msg == ''):                                
                                
                            else:
                                $this->setQuant($this->getQuant('B'));
                                $this->setUnitario($this->getUnitario('B'));
                                $this->desenhaCadastroNotaFiscalProduto($msg, $tipoMsg);
                            endif;
                        }
                        
                        
                    //}
                    break;
            case 'excluir':
                try {
                    $objProdutoQtde = new c_produto_estoque();
                    $msg = "";
                    if ($this->m_nfOBJ->existeNotaFiscalBaixa($this->getIdNf())) {
                        throw new Exception( 'Nota fiscal BAIXADA, n&atilde;o sendo possivel excluir produto.'); //}
                    } else {
                        $this->setNotaFiscalProduto();
                        $result = $this->excluiNotafiscalProduto();
                        if(!$result):
                            throw new Exception( "Produto ".$this->getDescricao()." não excluido " );
                        endif;
                        $result = $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "NFS",
                                $this->getIdNf(), $this->getCodProduto(), (int) $this->getQuant());
                        if(!$result):
                            throw new Exception( "Produto ".$this->getDescricao()." NÃO retirado da reserva - entrar em contato com suporte!! " );
                        endif;
                        $this->calculaRateios($this->getIdNf());
                        $result = $this->atualizaTotalNfe($this->getIdNf());
                        if(!$result):
                            throw new Exception( "Produto ".$this->getDescricao()." TOTAL NÃO CALCULADO - entrar em contato com suporte!! " );
                        endif;
                    }
                }catch (Exception $e) {
                    $msg = $e->getMessage();
                }finally {
                    $this->mostraNotaFiscalProduto($msg);
                }
                break;    
            case 'baixar':
                $this->setNotaFiscalProduto();
                if ($this->existeDataConferencia()) {
                    $this->m_opcao = "receber";
                    $this->mostraNotaFiscalProduto("PRODUTO JA RECEBIDO - " . $this->getDescricao());
                } else {
                    $this->readonly = 'readonly';
                    $this->desenhaCadastroNotaFiscalProduto('');
                }

                break;
            case 'baixa':
                    try {
                        // inicializa transação
                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);
                        
                        $msg == '';
                        $tipoMsg = 'sucesso';
                        $this->setDataConferencia(date("d/m/Y H:i:s"));
                        $result = $this->alteraNotaFiscalProduto($transaction->id_connection);
                        if(!$result):
                            throw new Exception( "Produto ".$this->getCodProduto()." não atulalizado" );
                        endif;
                        
                        //inclui produto_estoque *** testar se controla estoque..
                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
//                        $integraFin = $parametros->getField("INTEGRAFIN", "FILIAL=".$this->m_empresacentrocusto);
//                        $validaNfAuto = $parametros->getField("VALIDANFAUTO", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();                        
                        $objProduto = new c_produto();
                        $objProduto->setId($this->getCodProduto());
                        $arrProduto = $objProduto->select_produto();
                        $uniFrac = $arrProduto[0]['UNIFRACIONADA'];
                        $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));
                        
                        if ($ifControlaEstoque):
                            $quant = $this->getQuant('B');
                            $objEstProduto = new c_produto_estoque();
                            for ($i = 0; $i < $quant; $i++) {
                                $objEstProduto->setIdNfEntrada($this->getIdNf());
                                $objEstProduto->setCodProduto($this->getCodProduto());
                                $objEstProduto->setStatus('0');
                                $objEstProduto->setAplicado('0');
                                $objEstProduto->setCentroCusto($this->m_empresacentrocusto);
                                $objEstProduto->setUserProduto($this->m_userid);
                                $objEstProduto->setLocalizacao('');
                                //$objEstProduto->setNsEntrada($this->getNumSerie());
                                $objEstProduto->setFabLote($this->getLote());
                                $objEstProduto->setDataFabricacao($this->getDataFabricacao('F'));
                                $objEstProduto->setDataValidade($this->getDataValidade('F'));
                                $objEstProduto->incluiProdutoEstoque($transaction->id_connection);
                            }//for
                        endif;

                        // MUDAR ALTERAÇÃO DADOS DO PRODUTO DO IMPORTA PARA RECEBIMENTO
                        //$objProduto = new c_produto();
                        
        
                        //checa se todos produtos foram recebidos e altera situação NF
                        if (!$this->existeProdutoConferencia($transaction->id_connection)):
                            $this->m_nfOBJ->setId($this->getIdNf());
                            $this->m_nfOBJ->alteraSituacao('B');
                        endif;
                        
                        //; commit transação
                        $transaction->commit($transaction->id_connection);                        
                    } catch (Exception $e) {
                        $tipoMsg = 'alerta';
                        $msg = "Produto não recebido!!<br>".$e->getMessage();
                        $transaction->rollback($transaction->id_connection);
                        
                    }finally {
                        if ($msg == ''):
                            $this->mostraNotaFiscalProduto();
                        else:
                            $this->desenhaCadastroNotaFiscalProduto($msg, $tipoMsg);
                        endif;
                    }

                break;
            default:
                //if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
                $this->mostraNotaFiscalProduto(''); //}
        }
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaCadastroNotaFiscalProduto($mensagem = NULL, $tipoMsg = NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('readonly', $this->readonly);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idnf', $this->getIdNf());
        $this->smarty->assign('codProduto', "'" . $this->getCodProduto() . "'");
        $this->smarty->assign('descProduto', "'" . $this->getDescricao() . "'");
        $this->smarty->assign('quant', $this->getQuant('F'));
        $this->smarty->assign('unidade', $this->getUnidade());
        $this->smarty->assign('unitario', $this->getUnitario('F'));
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('cfop', $this->getCfop());
        $this->smarty->assign('bcIcms', $this->getBcIcms('F'));
        $this->smarty->assign('valorIcms', $this->getValorIcms('F'));
        $this->smarty->assign('aliqIcms', "'" . $this->getAliqIcms('F') . "'");
        $this->smarty->assign('cstPis', "'" . $this->getCstPis() . "'");
        $this->smarty->assign('bcPis', $this->getBcPis('F'));
        $this->smarty->assign('valorPis', $this->getValorPis('F'));
        $this->smarty->assign('aliqPis', "'" . $this->getAliqPis('F') . "'");
        $this->smarty->assign('cstCofins', "'" . $this->getCstCofins() . "'");
        $this->smarty->assign('bcCofins', $this->getBcCofins('F'));
        $this->smarty->assign('valorCofins', $this->getValorCofins('F'));
        $this->smarty->assign('aliqCofins', "'" . $this->getAliqCofins('F') . "'");
        $this->smarty->assign('baseCalculoIpi', "'" . $this->getBaseCalculoIpi('F') . "'");
        $this->smarty->assign('valorIpi', "'" . $this->getValorIpi('F') . "'");
        $this->smarty->assign('aliqIpi', "'" . $this->getAliqIpi('F') . "'");
        $this->smarty->assign('ncm', "'" . $this->getNcm() . "'");
        $this->smarty->assign('cest', "'" . $this->getCest() . "'");
        $this->smarty->assign('nrserie', $this->getNrSerie());
        $this->smarty->assign('lote', "'" . $this->getLote() . "'");
        $this->smarty->assign('dataFabricacao', "'" . $this->getDataFabricacao('F') . "'");
        $this->smarty->assign('dataValidade', "'" . $this->getDataValidade('F') . "'");
        $this->smarty->assign('dataGarantia', "'" . $this->getDataGarantia('F') . "'");
        $this->smarty->assign('dataConferencia', "'" . $this->getDataConferencia('F') . "'");
        $this->smarty->assign('ordem', "'" . $this->getOrdem() . "'");
        $this->smarty->assign('projeto', $this->getProjeto());
        $this->smarty->assign('percDiferido', "'" . $this->getPercDiferido('F') . "'");
        $this->smarty->assign('valorIcmsDiferido', $this->getValorIcmsDiferido('F'));
        $this->smarty->assign('valorIcmsOperacao', $this->getValorIcmsOperacao('F'));
        $this->smarty->assign('percReducaoBc', $this->getPercReducaoBc('F'));
        $this->smarty->assign('percMvaSt', $this->getPercMvaSt('F'));
        $this->smarty->assign('percReducaoBcSt', $this->getPercReducaoBcSt('F'));
        $this->smarty->assign('valorbcst', $this->getValorBcSt('F'));
        $this->smarty->assign('aliqicmsst', $this->getAliqIcmsSt('F'));
        $this->smarty->assign('valoricmsst', $this->getValorIcmsSt('F'));
        $this->smarty->assign('bcFcpSt', $this->getBcFcpSt('F'));
        $this->smarty->assign('aliqFcpSt', $this->getAliqFcpST('F'));
        $this->smarty->assign('valorFcpSt', $this->getValorFcpST('F'));
        $this->smarty->assign('bcfcpufdest', $this->getBcFcpUfDest('F'));
        $this->smarty->assign('aliqfcpufdest', $this->getAliqFcpUfDest('F'));
        $this->smarty->assign('valorfcpufdest', $this->getValorFcpUfDest('F'));
        $this->smarty->assign('bcicmsufdest', $this->getBcIcmsUfDest('F'));
        $this->smarty->assign('aliqicmsufdest', $this->getAliqIcmsUfDest('F'));        
        $this->smarty->assign('aliqicmsinter', $this->getAliqIcmsInter('F'));
        $this->smarty->assign('aliqicmsinterpart', $this->getAliqIcmsInterPart('F'));
        $this->smarty->assign('valoricmsufdest', $this->getValorIcmsUfDest('F'));
        $this->smarty->assign('valoricmsufremet', $this->getValorIcmsUfRemet('F'));
        $this->smarty->assign('rFrete', $this->getRFreteProd('F'));
        $this->smarty->assign('rDesp', $this->getRDespProd('F'));

        $this->smarty->assign('valorBaseCalculoStRetido', $this->getValorBaseCalculoStRetido('F'));
        $this->smarty->assign('valorIcmsStRetido', $this->getValorIcmsStRetido('F'));
        $this->smarty->assign('valorIcmsSubstituto', $this->getValorIcmsSubstituto('F'));



        // ORIGEM MERCADORIA
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i] = $result[$i]['ID'];
            $grupo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('origem_ids', $grupo_ids);
        $this->smarty->assign('origem_names', $grupo_names);
        $this->smarty->assign('origem', $this->getOrigem());

        // TRIBUTO ICMS
        // $sql = "select * from amb_empresa where (centrocusto=".$this->m_empresacentrocusto.")";
        // $emp = $consulta->exec_sql($sql);
        // $crt=$emp[0]['REGIMETRIBUTARIO'];
        // if ($crt=='3'):
        //     $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
        // else:    
        //     $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='CSOSN')";
        // endif;
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
        $consulta->exec_sql($sql);
        $resultTribIcms = $consulta->resultado ?? [];
        
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='csosn')";
        $consulta->exec_sql($sql);
        $resultCsosn = $consulta->resultado ?? [];
        
        $consulta->close_connection();
        
        // Combina os resultados
        $result = array_merge($resultTribIcms, $resultCsosn);

        for ($i = 0; $i < count($result); $i++) {
            $tribIcms_ids[$i] = $result[$i]['ID'];
            $tribIcms_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tribIcms_ids', $tribIcms_ids);
        $this->smarty->assign('tribIcms_names', $tribIcms_names);
        $this->smarty->assign('tribIcms', $this->getTribIcms());

        // Modalidade Bc
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBc')";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++){
                $modBc_ids[$i] = $result[$i]['ID'];
                $modBc_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('modBc_ids', $modBc_ids);
        $this->smarty->assign('modBc_names', $modBc_names);
        $this->smarty->assign('modBc', $this->getModBc());

        // Modalidade Bc St
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBcSt') ORDER BY ID ASC";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];
        
        for ($i = 0; $i < count($result); $i++){
                $modBcSt_ids[$i] = $result[$i]['ID'];
                $modBcSt_names[$i] = $result[$i]['DESCRICAO'];     
        }
        //array_push($modBcSt_ids, " ");
        $modBcSt_ids[count($result)+1] = '';
        $modBcSt_names[count($result)+1] = 'Selecione uma op&ccedil;&atilde;o';
        
        $this->smarty->assign('modBcSt_ids', $modBcSt_ids);
        $this->smarty->assign('modBcSt_names', $modBcSt_names);
        $this->smarty->assign('modBcSt', $this->getModBcSt());

        // CST IPI
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='CSTIPI') ORDER BY ID ASC";
        $consulta = new c_banco();
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];
 
        for ($i = 0; $i < count($result); $i++){
                $cstIpi_ids[$i] = $result[$i]['ID'];
                $cstIpi_names[$i] = $result[$i]['DESCRICAO'];
        }

        $cstIpi_ids[count($result)+1] = '';
        $cstIpi_names[count($result)+1] = 'Selecione uma op&ccedil;&atilde;o';
        //array_unshift($cstIpi_names, " ");
        $this->smarty->assign('cstIpi_ids', $cstIpi_ids);
        $this->smarty->assign('cstIpi_names', $cstIpi_names);
        $this->smarty->assign('cstIpi', $this->getCstIpi());

        // CST PIS/COFINS
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='PISCOFINS') ORDER BY ID ASC";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
       
        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];
        
        for ($i = 0; $i < count($result); $i++){
                $pisCofins_ids[$i] = $result[$i]['ID'];
                $pisCofins_names[$i] = $result[$i]['DESCRICAO'];
        }
        $pisCofins_ids[count($result)+1] = '';
        $pisCofins_names[count($result)+1] = 'Selecione uma op&ccedil;&atilde;o';
        //array_unshift($pisCofins_names, "");
        $this->smarty->assign('pisCofins_ids', $pisCofins_ids);
        $this->smarty->assign('pisCofins_names', $pisCofins_names);

        // BENEFICIO
        $consulta = new c_banco();
        $sql = "select * from EST_NAT_OP_BENEFICIO ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        
        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];
        
        for ($i = 1; $i < count($result); $i++) {
            $cbenef_ids[$i] = $result[$i]['CBENEF'];
            $cbenef_names[$i] = $result[$i]['CBENEF'].' - '.$result[$i]['DESCRICAO'];
        }
        array_unshift($cbenef_names, " ");
        $this->smarty->assign('cbenef_ids', $cbenef_ids);
        $this->smarty->assign('cbenef_names', $cbenef_names);

        $this->smarty->assign('cbenef', $this->getCBenef());

        $this->smarty->assign('cstCofins', $this->getCstCofins());
        $this->smarty->assign('cstPis', $this->getCstPis());

        $this->smarty->assign('telaOrigem', $this->telaOrigem);

        // Busca parâmetro CASASDECIMAIS
        $parametros = new c_parametros();
        $parametros->setFilial($this->m_empresacentrocusto);
        $casasDecimais = $parametros->getCasasDecimais();
        $this->smarty->assign('casasDecimais', $casasDecimais);

        $this->smarty->display('nota_fiscal_produto_cadastro.tpl');
    }

//fim desenhaCadastroNotaFiscalProduto
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraNotaFiscalProduto($mensagem = null, $tipoMsg = '' ) {


        $nf = new c_nota_fiscal();
        $nf->setId($this->getIdNf());
        $nf->setNotaFiscal();

        //$this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idnf', $this->getIdNf());
        $this->smarty->assign('modelo', $nf->getModelo());
        $this->smarty->assign('serie', $nf->getSerie());
        $this->smarty->assign('numero', $nf->getNumero());
        $this->smarty->assign('pessoa', $nf->getPessoa());
        $this->smarty->assign('pessoaNome', $nf->getnomePessoa());
        $this->smarty->assign('natOperacao_name', "'".$nf->getNatOperacao()."'");
        $this->smarty->assign('totalnf', $nf->getTotalnf('F'));
        $this->smarty->assign('emissao', $nf->getEmissao('F'));
        $this->smarty->assign('modBc', $this->getModBc());


        
        $lanc = $this->select_nota_fiscal_produto_nf();

        // VERIFICA SE NF ESTA ABERTA E FAZ O BAIXA NA NOTA

/*        if (($nf->getSituacao() == 'A') and ( $this->m_opcao == 'recebimento')) {
            $situacao = 'B';
            for ($i = 0; $i < count($lanc); $i++) {
                if (($lanc[$i]['DATACONFERENCIA'] == '0000-00-00 00:00:00') || ($lanc[$i]['DATACONFERENCIA'] == NULL)) {
                    $situacao = 'A';
                } //if
            } //for
            if ($situacao == 'B') {
                $nf->setSituacao($situacao);
                $nf->setDataConferencia($this->getDataConferencia('B'));
                $nf->setUsuarioConferencia($this->m_userid);
                $nf->setTotalnf($nf->getTotalnf('F'));
                $nf->alteraNotaFiscal();
            }
        }//if	
        if ($nf->getTipo() == '1') {
            $situacao = 'B';
            $nf->setSituacao($situacao);
            $nf->setDataConferencia($this->getDataConferencia('B'));
            $nf->setUsuarioConferencia($this->m_userid);
            $nf->setTotalnf($nf->getTotalnf('F'));
            $nf->alteraNotaFiscal();
        }
*/
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('dataConferencia', "'" . $this->getDataConferencia('F') . "'");
        $this->smarty->assign('lanc', $lanc);

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='EST_MENU') AND (CAMPO='SITUACAONOTA') AND (TIPO = '".$nf->getSituacao()."')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('situacao_name', "'".$consulta->resultado[0]['DESCRICAO']."'");
        
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='EST_MENU') AND (CAMPO='TIPONOTAFISCAL') AND (TIPO = '".$nf->getTipo()."')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('tipo_name', "'".$consulta->resultado[0]['DESCRICAO']."'");
        
        $consulta->close_connection();


        $this->smarty->display('nota_fiscal_produto_mostra.tpl');
    }

//fim mostraNotaFiscal
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$NotaFiscal = new p_nota_fiscal_produto();

$NotaFiscal->controle();
