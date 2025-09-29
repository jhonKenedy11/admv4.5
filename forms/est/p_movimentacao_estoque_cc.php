<?php
/**
 * @package   astec
 * @name      movimentacao_estoque_cc
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/crm/c_conta.php");
require_once($dir."/../../class/ped/c_pedido_venda_tools.php");
//require_once($dir."/../../forms/est/p_movimentacao_estoque_cc_imprime.php");

//Class movimentacao_estoque_cc
Class movimentacao_estoque_cc extends c_produto{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;
    
    //VARIÁVEIS PARA IMPRESSÃO
    private $idEntrada   = null;
    private $idSaida     = null;
    private $modeloNota  = null;
    private $serieNota   = null;
    private $idCCEntrada = null;
    private $idCCSaida   = null;
    private $produto     = null;
    private $quantidade  = null;
    private $conta       = null;
    private $genero      = null;
    private $obsNf       = null;
    
    

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
        
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
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->id_produto=(isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
        $this->desc_prod=(isset($parmGet['descProduto']) ? $parmGet['descProduto'] : (isset($parmPost['descProduto']) ? $parmPost['descProduto'] : ''));
        $this->unidade_prod=(isset($parmGet['unidade']) ? $parmGet['unidade'] : (isset($parmPost['unidade']) ? $parmPost['unidade'] : ''));
        $this->valorVenda=(isset($parmGet['valorVenda']) ? $parmGet['valorVenda'] : (isset($parmPost['valorVenda']) ? $parmPost['valorVenda'] : ''));
        $this->uniFracionada=(isset($parmGet['uniFracionada']) ? $parmGet['uniFracionada'] : (isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : ''));
        $this->id_pessoa=(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        $this->m_quantNova=(isset($parmGet['qtdeEntrada']) ? $parmGet['qtdeEntrada'] : (isset($parmPost['qtdeEntrada']) ? $parmPost['qtdeEntrada'] : 0));
        $this->m_modelo=(isset($parmGet['modelo']) ? $parmGet['modelo'] : (isset($parmPost['modelo']) ? $parmPost['modelo'] : ''));
        $this->m_serieDocto=(isset($parmGet['serieNf']) ? $parmGet['serieNf'] : (isset($parmPost['serieNf']) ? $parmPost['serieNf'] : 'TFF'));
        $this->m_numDocto=(isset($parmGet['numDocto']) ? $parmGet['numDocto'] : (isset($parmPost['numDocto']) ? $parmPost['numDocto'] : ''));
        $this->m_genero=(isset($parmGet['genero']) ? $parmGet['genero'] : (isset($parmPost['genero']) ? $parmPost['genero'] : ''));
        $this->m_descGenero=(isset($parmGet['descGenero']) ? $parmGet['descGenero'] : (isset($parmPost['descGenero']) ? $parmPost['descGenero'] : ''));
        $this->m_obsNf=(isset($parmGet['obs']) ? $parmGet['obs'] : (isset($parmPost['obs']) ? $parmPost['obs'] : ''));
        $this->m_idPed=(isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));
        //Dados origem modal
        $this->m_modalDataEntrega  = (isset($parmPost['mDataEntrega']) ? $parmPost['mDataEntrega'] : null);
        $this->m_modalCCEntrega = (isset($parmPost['mCentroCusto']) ? $parmPost['mCentroCusto'] : null);
        $this->m_modalIdPedido = (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : null);
        
        $this->ccustoOrigem=(isset($parmGet['centroCustoOrigem']) ? $parmGet['centroCustoOrigem'] : (isset($parmPost['centroCustoOrigem']) ? $parmPost['centroCustoOrigem'] : ''));
        $this->ccustoDestino=(isset($parmGet['centroCustoDestino']) ? $parmGet['centroCustoDestino'] : (isset($parmPost['centroCustoDestino']) ? $parmPost['centroCustoDestino'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'inclui':
                $tipoMsg = null;
                $quant = str_replace('.', '',$this->m_quantNova);
                $quant = str_replace(',', '.', $quant);

                if (abs($quant) > 0) {
                    //Saída
                    if($this->ccustoOrigem != $this->ccustoDestino){
                        $result = $this->insereQuant($this->m_quantNova, $this->ccustoOrigem, '1');
                        $msg = "</br>".'N&deg; Docto <b>SA&Iacute;DA</b> '.$result ;
                        //Var para impressão
                        $this->idSaida = $result;
                    }
                    //Entrada
                    $result = $this->insereQuant($this->m_quantNova, $this->ccustoDestino, '0');
                    $msg .= "</br>".'N&deg; Docto <b>ENTRADA</b> '.$result;
                    //Var para impressão
                    $this->idEntrada   = $result;

                    //Atualiza tupula DOC Nota de entrada e saída setField($id, $field, $value)
                    $updateDoc = new c_banco;
                    $updateDoc->setTab("EST_NOTA_FISCAL");
                    $updateDoc->setField($this->idSaida, "DOC", $this->idSaida);
                    $updateDoc->setField($this->idEntrada, "DOC", $this->idSaida);
                    
                    $this->modeloNota = $this->m_modelo;
                    $this->serieNota =  $this->m_serieDocto;
                    $this->idCCEntrada = $this->ccustoDestino;
                    $this->idCCSaida = $this->ccustoOrigem;
                    $this->produto =$this->desc_prod;
                    $this->quantidade = $this->m_quantNova;
                    $this->conta = $this->id_pessoa;
                    $this->genero = $this->m_genero;
                    $this->obsNf = $this->m_obsNf;

                    //Consulta se exist o produto em encomenda
                    $resultProd = $this->select_produto_encomenda($this->id_produto);

                    if($resultProd != null){
                        $this->smarty->assign('mensagem', $resultProd);
                    };

                    $this->mostraBaixaEstoque($msg, 'sucesso');

                } else {
                    $msg = 'Quantidade inválida !!';
                    $this->mostraBaixaEstoque($msg, 'alerta');
                }  
            break;
            default:
                $this->mostraBaixaEstoque('');
               
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function mostraBaixaEstoque($msg, $tipoMsg = NULL) {
  
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('msg', $msg);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('idEntrada', $this->idEntrada);
        $this->smarty->assign('idSaida', $this->idSaida);
        $this->smarty->assign('idCCEntrada', $this->ccustoDestino);
        $this->smarty->assign('idCCSaida', $this->ccustoOrigem);
        $this->smarty->assign('modeloNota', $this->m_modelo);
        $this->smarty->assign('serieNota', $this->m_serieDocto);
        $this->smarty->assign('codProduto', $this->id_produto);
        $this->smarty->assign('produto',  "'".$this->desc_prod."'");
        $this->smarty->assign('quantidade', $this->m_quantNova);
        $this->smarty->assign('conta', $this->id_pessoa);
        $this->smarty->assign('genero', $this->m_genero);
        $this->smarty->assign('obsNf', "'".$this->m_obsNf."'");

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('centroCusto_ids',   $ccusto_ids);
        $this->smarty->assign('centroCusto_names', $ccusto_names);

        $this->smarty->assign('centroCustoOrigem',  $ccusto_id);
        $this->smarty->assign('centroCustoDestino', $ccusto_id);

        //Ajax responsavel por atualizar pedido
        $ajax_request = @($_SERVER["HTTP_AJAX_ATUALIZA_PEDIDO"] == "true");
        if($_SERVER["HTTP_AJAX_ATUALIZA_PEDIDO"] == "true"){
            $ajax_request = 'true';

            $objPedidoTool = new c_pedidoVendaTools();

            //CONVERSAO DA DATA PARA INSERIR NO BANCO
            //$this->m_modalDataEntrega = c_date::convertDateBdSh($this->m_modalDataEntrega, $this->m_banco);
            
            //ATUALIZA DADOS PEDIDO
            $objPedidoTool->alteraDadosPedido($this->m_modalIdPedido, $this->m_modalDataEntrega, $this->m_modalCCEntrega, null);
            
            //VALIDA PEDIDO
            $msg = $objPedidoTool->validaPedido($this->m_idPed, $this->m_modalCCEntrega);

            //VERIFICA SE MSG É NULL(QUANDO NAO TEM DIVERGENCIA NO PEDIDO)
            if (!is_null($msg)) {
                $this->smarty->assign('msgPedModal', 'PEDIDO PERMANECE EM ENCOMENDA'.'</br>'.'</br>'.'</br>'.$msg);
            }else{
                //BUSCA PARCELAS DO FINANCEIRO
                $parcFin = $this->select_lancamento($this->m_idPed);
                
                if(is_null($parcFin)){
                    $this->smarty->assign('msgPedModal', 'PEDIDO SEM FINANCEIRO!!!'.'</br>'.'DEVE SER ALTERADO MANUALMENTE ATRAVÉS DO MENU DE PEDIDOS');
                }else{
                    //ALTERA PARA PEDIDO
                    $objPedidoTool = new c_pedidoVendaTools();
                    $objPedidoTool->alteraDadosPedido($this->m_idPed, null, null, 6); // PEDIDO
                    $this->smarty->assign('msgPedModal', 'Pedido Alterado!');

                    //Consulta se exist o produto em encomenda
                    $resultProd = $this->select_produto_encomenda($this->id_produto);

                    if($resultProd != null){
                        $this->smarty->assign('mensagem', $resultProd);
                    };
                }
            }

        }else{
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        }
        
        $this->smarty->display('movimentacao_estoque_cc.tpl');
        
    }
    

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ids[0] = '';
        $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i+1] = $result[$i]['ID'];
            $names[$i+1] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }

    function insereQuant($quant, $centroCusto, $tipoNf = '0') {
        $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        //$tipoNf = '0';
    
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();                        

        $classNFProduto->setQuant($quant);
        $qtde = $classNFProduto->getQuant('B');
        if ($qtde < 0){
            $qtde = $qtde * -1;
            $tipoNf = '1';
        } 

        $classNFProduto->setUnitario($this->valorVenda);
        $vlrVenda = $classNFProduto->getUnitario('B');
        $totalProd = ($qtde * $vlrVenda);

       //EST_NOTA_FISCAL
        $classNF->setModelo($this->m_modelo);
        $classNF->setSerie('TFF');
        $classNF->setNumero(0);
        $classNF->setPessoa($this->id_pessoa);
        $classNF->setEmissao(date('d/m/Y H:i'));
        //nat operacao
        $classNF->setIdNatop(99);
        $classNF->setNatOperacao('AJUSTE QUANTIDADE DE ESTOQUE');
        $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
        $classNF->setSituacao('B');
        $classNF->setFormaPgto('0');
        $classNF->setDataSaidaEntrada(date('d/m/Y H:i'));
        $classNF->setFinalidadeEmissao(9);
        $classNF->setTransportador(0);
        $classNF->setCentroCusto($centroCusto);
        $classNF->setGenero($this->m_genero);
        $classNF->setOrigem('TFF');
        $classNF->setDoc(0);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($totalProd);
        $classNF->setObs($this->m_obsNf);
        $classNF->setParam('noFormat');	
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        $classNF->setId($lastNF);
        $classNF->setNumero($lastNF);
        $classNF->alteraNfNumero();
        
       //EST_NOTA_FISCAL_ESTOQUE
        

        $total = 1;
        $classNFProduto->setIdNf($lastNF);
        $classNFProduto->setCodProduto($this->id_produto);
        $classNFProduto->setDescricao($this->desc_prod);
        $classNFProduto->setUnidade($this->unidade_prod);
        $classNFProduto->setQuant($qtde, true);
        $classNFProduto->setUnitario($vlrVenda, true);
        $classNFProduto->setTotal($totalProd, true);
        $classNFProduto->setOrigem('0');
        $classNFProduto->setTribIcms('00');
        $classNFProduto->setCfop('9999');
        $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
        $classNFProduto->incluiNotaFiscalProduto();
        
        // QUANTIDADE PRODUTO_ESTOQUE 
        
        $ifControlaEstoque = (($controlaEstoque == 'S') && ($this->uniFracionada == 'N'));
        if ($ifControlaEstoque):
            $objEstProduto = new c_produto_estoque();
            if ($tipoNf == '0'):
                for ($i = 0; $i < $qtde; $i++) {
                    $objEstProduto->setIdNfEntrada($lastNF);
                    $objEstProduto->setCodProduto($this->id_produto);
                    $objEstProduto->setStatus('0');
                    $objEstProduto->setAplicado('0');
                    $objEstProduto->setCentroCusto($this->ccustoOrigem);
                    $objEstProduto->setUserProduto($this->m_userid);
                    $objEstProduto->setLocalizacao('');
                    //$objEstProduto->setNsEntrada($this->getNumSerie());
                    $objEstProduto->setFabLote('');
                    $objEstProduto->setDataFabricacao('');
                    $objEstProduto->setDataValidade('');
                    $objEstProduto->incluiProdutoEstoque();
                }//for
                
            else:
                $objEstProduto->produtoBaixa($this->ccustoOrigem, $this->id_produto, $qtde, $lastNF);
            endif;
        endif;

        return $lastNF;
    }

    public function select_nota_fiscal(){

        $sql  = "SELECT * ";
        $sql .= "FROM EST_NOTA_FISCAL ";
        $sql .= "WHERE (ID = ".$this->m_id ." AND CENTROCUSTO = ".$this->ccustoOrigem.")";
        //	ECHO $sql;
    
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

    }

//fim mostraBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new movimentacao_estoque_cc();

$consultas->controle();
?>
