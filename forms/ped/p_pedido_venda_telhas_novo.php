<?php
/** 
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 * 
 * direito de usuario 'PEDVERTODOSLANCAMENTOS' se sim pode ver todas as vendas.
 * se não, verá somente os seus
 * 
 * $tipovalidacao validação de permicao de desconto
 * N = Não se aplica
 * A = Percentual máximo que o vendedor por dar por item
 * M = Preco mínimo         
 * 
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
//require_once($dir . "/../../forms/ped/p_pedido_venda_nf.php");
require_once($dir . "/../../class/fin/c_lancamento.php");


require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir."/../../bib/dompdf/lib/html5lib/Parser.php");
require_once($dir."/../../bib/dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php");
require_once($dir."/../../bib/dompdf/lib/php-svg-lib-master/src/autoload.php");
require_once($dir."/../../bib/dompdf/src/Autoloader.php");
include_once($dir . "/../../bib/c_mail.php");


Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

//Class P_pedido_venda
Class p_pedido_venda_telhas_novo extends c_pedidoVenda {
            
    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensPedidoCC= NULL;
    private $m_itensQtde    = NULL;
    private $m_agrupar_pedidos = NULL;
    private $id_prod_preco_min = NULL;
    public $smarty          = NULL;
            
    private $baseIcms = null;
    private $valorIcms = null;
    private $basePis = null;
    private $valorPis = null;
    private $baseCofins = null;
    private $valorCofins = null;
    private $exibirmotivo = null;    
    private $itensperdido = null;
 
    
    private $m_motivoSelecionados = null;
    private $m_condPagamentoSelecionados = null;
    private $m_vendedoresSelecionados = null;
    private $m_centroCustoSelecionados = null;
    
    
    private $m_motivo = null;
    private $m_motivo_pedido_id = null;
    private $m_status = null;
    
    private $m_letra_old        = NULL;

    
    private $m_useridconf       = NULL;
    private $m_passwordconf     = NULL;

    private $totalOriginal       = NULL;

    //EMAIL VARIAVEIS 
    private $m_destinatario = NULL;
    private $m_comCopiaPara = NULL;
    private $m_assunto = NULL;
    private $m_emailCorpo = NULL;

    private $m_pesquisa_prod_vazio = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);
       
        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];

        $this->m_letra = $parmPost['letra'];
        $this->m_letra_old = $parmPost['letra_old'];
        $this->m_desconto = $parmPost['desconto'];
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_itensPedidoCC = $parmPost['itensPedidoCC'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        $this->m_agrupar_pedidos = $parmPost['agrupar_pedidos'];
        $this->m_motivoSelecionados = $parmPost['motivoSelected'];
        $this->m_vendedoresSelecionados = $parmPost['vendedorSelecionados'];
        $this->m_centroCustoSelecionados = $parmPost['centroCustoSelecionados'];
        $this->m_condPagamentoSelecionados = $parmPost['condPagamentoSelecionados'];
        $this->id_prod_preco_min = $parmPost['id_prod_preco_min'];

        $this->m_pesquisa_prod_vazio = $parmPost['pesquisa_prod_vazio'];
        
        $this->m_motivo = $parmPost['motivo'];
        $this->m_motivo_pedido_id = $parmPost['motivo_pedido_id'];

        $exibirmotivo = '';
        $this->exibirmotivo = $exibirmotivo;
        $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        
        //$this->exibirmotivo = $parmPost['exibirmotivo'];
        $this->itensperdido = $parmPost['itensperdido'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        if (isset($parmPost['usrautorizaconf'])){
            $this->m_useridconf            =  $parmPost['usrautorizaconf'];
        }
        if (isset($parmPost['passwordconf'])){
            $this->m_passwordconf        =  $parmPost['passwordconf'];
        }

        $this->totalOriginal = ($parmPost['totalOriginal']);

        // Envia Email Pedido 
        $this->m_destinatario = $parmPost['destinatario'];
        $this->m_comCopiaPara = $parmPost['comCopiaPara'];
        $this->m_assunto = $parmPost['assunto'];
        $this->m_emailCorpo = $parmPost['emailBody'];

        
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8]");
        $this->smarty->assign('disableSort', "[ 8 ]");
        $this->smarty->assign('numLine', "50");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        
        $this->setCondPg(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setIdNatop(isset($parmPost['natop']) ? $parmPost['natop'] : '1');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
        $this->setCentroCustoEntrega(isset($parmPost['centroCustoEntrega']) ? $parmPost['centroCustoEntrega'] : '');
        if (isset($parmPost['pessoa'])):
            $this->setCliente($parmPost['pessoa']);
        else:    
            /*$parametros = new c_banco;
            $parametros->setTab("AMB_USUARIO");
            $cliente = $parametros->getField("CLIENTE", "USUARIO=".$this->m_userid);
            $parametros->close_connection();                        
            $this->setCliente($cliente);*/
            $this->setCliente('');
        endif;   
        $this->setFrete(isset($parmPost['frete']) ? $parmPost['frete'] : '0');
        $this->setDesconto(isset($parmPost['desconto']) ? $parmPost['desconto'] : '0');
        $this->setDespAcessorias(isset($parmPost['despAcessorias']) ? $parmPost['despAcessorias'] : '0');
        $this->setUsrFatura(isset($parmPost['usrfatura']) ? $parmPost['usrfatura'] : '');
        $this->setEmissao(isset($parmPost['emissao']) ? $parmPost['emissao'] : date("Y-m-d"));
        // complemento descricao pedido_item
        $this->setDescricaoItem(isset($parmPost['desc']) ? $parmPost['desc'] : '');
        $this->setCredito(isset($parmPost['credito']) ? $parmPost['credito'] : '0');
        $this->setTotal(isset($parmPost['totalPedido']) ? $parmPost['totalPedido'] : '0');
        if ($this->getCredito() == "") {
            $this->setCredito(0); 
        }
        $this->setUsrAprovacao(isset($parmPost['usrAprovacao']) ? $parmPost['usrAprovacao'] : '');
        $this->totalCredito = $parmPost['totalCredito'];
        // include do javascript
        //include ADMjs . "/ped/s_pedido_venda.js";
    }

    
    /**
* <b> É responsavel para calcular os impostos dos itens selecionados </b>
* @name calculoImpostos
* @param vazio
* @return atualiza os totais dos impostos
*/
    function calculaImpostos($desconto=false) {        
        
        if ($this->getId() > 0) {
            if ($desconto){ // zera desconto pedido item
                $sql = "UPDATE  ";
                $sql .= " fat_pedido_item  SET DESCONTO = 0 ";
                $sql .= "WHERE (id = " . $this->getId() . ") ";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
            }
    
            $totalNF = $this->select_totalPedido(); // Total do pedido_item 
            $descontoNF = $this->select_totais('DESCONTO'); // Totais desconto pedido_item
            $total = $totalNF;
            $despAcessorias = $this->getDespAcessorias('B'); // despesas acessorias do pedido
            $frete = $this->getFrete('B');          // frete do pedido
            $descontoGeral = $this->getDesconto('B');  // desconto digitado no pedido form
            //$descontoGeral = $descontoNF; 
          
            $despAcessoriasDist = 0;
            $freteDist = 0;
            $descontoGeralDist = 0;
            $custototal = 0;
            $despesatotal = 0; //?
            $margemliquida = 0;
            $markup = 0;            
            $lucrobruto = 0;

            $totalNF = 0;
            
            $arrItemPedido = $this->select_pedido_item_id();

            $totalDescontoItem = $descontoNF;
            $this->setDesconto($descontoNF);
            // for ($i = 0; $i < count($arrItemPedido); $i++) {
            //     $totalDescontoItem += $arrItemPedido[$i]['DESCONTO']; 
            // }
            
            for ($i = 0; $i < count($arrItemPedido); $i++) {
                $sqlFields = '';


                $custototal += $arrItemPedido[$i]['CUSTO'];
                $lucrobruto += $arrItemPedido[$i]['LUCROBRUTO'];
                $margemliquida += $arrItemPedido[$i]['MARGEMLIQUIDA'];
                $markup += $arrItemPedido[$i]['MARKUP'];
    
                $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QTSOLICITADA']*$arrItemPedido[$i]['UNITARIO'];
                $totalNF += $arrItemPedido[$i]['TOTAL']; 

                if ($totalDescontoItem == 0){
                    if ($descontoGeral > 0 ) {
                        $perc = ( $arrItemPedido[$i]['TOTAL'] / $total) * 100;
                        $vlrDescontoGeral = round(($descontoGeral * ($perc/100)),2);
                        $descontoGeralDist += $vlrDescontoGeral;
                        if ($i == (count($arrItemPedido) - 1)) {
                            if ($descontoGeralDist > $descontoGeral) {
                                $vlrDescontoGeral = $vlrDescontoGeral - ($descontoGeralDist - $descontoGeral);
                            } else if ($descontoGeralDist < $descontoGeral) {
                                $vlrDescontoGeral = $vlrDescontoGeral + ($descontoGeral - $descontoGeralDist);
                            }
                        }    
                        $percDescontoItem = (($vlrDescontoGeral * 100)/$arrItemPedido[$i]['TOTAL']);            
                        $percDescontoItem = round($percDescontoItem,2);
                        $sqlFields .= 'percdesconto = '.$percDescontoItem.', desconto = '.$vlrDescontoGeral; 
                        //$sqlFields .= ', Total = '.$arrItemPedido[$i]['TOTAL'].' - desconto ';   
                    } else { 
                        $sqlFields .= ' percdesconto = 0, desconto = 0 ';
                    }
                
                }
                if ($despAcessorias > 0 ) {
                    $perc = ($arrItemPedido[$i]['TOTAL'] / $total) * 100;
                    $vlrDespAcessorias = round(($despAcessorias * ($perc/100)),2);
                    $despAcessoriasDist += $vlrDespAcessorias;
                    if ($i == (count($arrItemPedido) - 1)) {
                        if ($despAcessoriasDist > $despAcessorias) {
                            $vlrDespAcessorias = $vlrDespAcessorias - ($despAcessoriasDist - $despAcessorias);
                        } else if ($despAcessoriasDist < $despAcessorias) {
                            $vlrDespAcessorias = $vlrDespAcessorias + ($despAcessorias - $despAcessoriasDist);
                        }
                    }
                    if ($sqlFields <> "") {
                        $sqlFields .= ', despAcessorias = '.$vlrDespAcessorias;
                    } else {
                        $sqlFields .= ' despAcessorias = '.$vlrDespAcessorias; 
                    }                               
                } else {
                    if ($sqlFields == "") {
                        $sqlFields .= ' despAcessorias = 0 ';
                    }else{
                        $sqlFields .= ', despAcessorias = 0 ';
                    }
                }
    
                if ($frete > 0 ) {
                    $perc = ( $arrItemPedido[$i]['TOTAL'] / $total) * 100;
                    $vlrFrete = round(($frete * ($perc/100)),2);
                    $freteDist += $vlrFrete;
                    if ($i == (count($arrItemPedido) - 1)) {
                        if ($freteDist > $frete) {
                            $vlrFrete = $vlrFrete - ($freteDist - $frete);
                        } else if ($freteDist < $frete) {
                            $vlrFrete = $vlrFrete + ($frete - $freteDist);
                        }
                    } 
                    if ($sqlFields <> "") {
                        $sqlFields .= ', frete = '.$vlrFrete;
                    } else {
                        $sqlFields .= ' frete = '.$vlrFrete; 
                    }
                } else {
                    $sqlFields .= ', frete = 0 ';
                }
                
                $banco = new c_banco;
                $sql = 'UPDATE FAT_PEDIDO_ITEM SET '.$sqlFields." WHERE ID = ".$arrItemPedido[$i]['ID']." and NRITEM = ".$arrItemPedido[$i]['NRITEM'];
                $banco->exec_sql($sql);
                $banco->close_connection(); 
                }   
                
            
            $sqlField = "";
            // if (($frete > 0) or ($despAcessorias > 0) or ($descontoGeral > 0)) {
                $banco = new c_banco;
                if ($frete > 0 ) {
                    $sqlField = ' frete = '.$frete;
                } else {
                    $sqlField = ' frete = 0 ';  
                }
                                
                if ($despAcessorias > 0 ) {
                    if ($sqlField <> "") {
                        $sqlField .= ', despacessorias = '.$despAcessorias;
                    }
                    else {
                        $sqlField = ' despacessorias = '.$despAcessorias;
                    }
                } else {
                    $sqlField .= ', despacessorias =  0 ';  
                }
                if($totalDescontoItem > 0){
                   $descontoGeral = $totalDescontoItem;
                }

                if (($descontoGeral > 0 )){
                    if ($sqlField <> "") {
                        $sqlField .= ', desconto = '.$descontoGeral;
                    }
                    else {
                        $sqlField = ' desconto = '.$descontoGeral;
                    }
                } else {
                    $sqlField .= ', desconto = 0 ';
                }

                //$totalPedido = ($total +$frete + $despAcessorias) - $descontoGeral;
                $totalPedido = ($totalNF +$frete + $despAcessorias) - $descontoGeral;
                if ($sqlField <> "") {
                    $sqlField .= ', total = '.$totalPedido;
                }
                else {
                    $sqlField = ' total = '.$totalPedido;
                }

                $sqlField .= ", obs = '".$this->getObs()."'".", prazoentrega = '".$this->getPrazoEntrega('B')."'";
                if($lucrobruto == ''){
                    $lucrobruto = 0;
                }
                // if (($frete > 0 ) or ($descontoGeral > 0)){
                    $lucrobruto = $totalPedido - $custototal;
                    $margemliquida = $lucrobruto; 
                    $markup = ($lucrobruto/$totalPedido) * 100; 
                // }
                $sqlFieldTotais = ', CUSTOTOTAL = ' .$custototal. ', LUCROBRUTO = ' .$lucrobruto. ', ';
                $sqlFieldTotais .='MARGEMLIQUIDA = '.$margemliquida.', MARKUP = '.$markup.' ';

                
                $sql = 'UPDATE FAT_PEDIDO SET '.$sqlField.$sqlFieldTotais.' WHERE ID = '.$this->getId();
                $banco->exec_sql($sql);
                $banco->close_connection(); 
                    
//            }

            
            
        } 


    }
    // calcula totais

    public function verificaDevolucao($pedido, $nritem){
        $sql = "SELECT SUM(QUANTIDADE) AS QUANTIDADE FROM FIN_CLIENTE_CREDITO ";
        $sql .= "WHERE PEDIDO = '".$pedido."' AND NRITEM = '".$nritem."' ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function gerarDevolucao($cliente, $pedido, $nritem, $quantidade, $unitario, $valor) {
        $sql = "INSERT INTO FIN_CLIENTE_CREDITO ";
        $sql .= "(CLIENTE, PEDIDO, NRITEM, QUANTIDADE, UNITARIO, VALOR) VALUES ";
        $sql .= "('". $cliente."', '".$pedido."', '".$nritem."', '".$quantidade."' , '".$unitario."', '".$valor."' )";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }    
    /**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'atualizarDataEmissao':
                {
                    if ($this->m_par[0] > 0) {
                        $this->atualizarField('emissao', $this->m_par[0]);
                        $this->setPedidoVenda();                   
                        $this->desenhaCadastroPedido();
                    } 
                }                
                break;
            case 'atualizarCCEntrega':
                {
                    if ($this->m_par[0] > 0) {
                        $this->atualizarField('centrocustoentrega',$this->m_par[0]);
                        $this->setPedidoVenda();                   
                        $this->desenhaCadastroPedido();
                    }                
                }                
                break;
            case 'atualizarPrazoEntrega':
                {
                    if ($this->m_par[0] > 0) {
                        $this->atualizarField('prazoentrega',
                        implode('/', array_reverse(explode('-', $this->m_par[0]))));
                        $this->setPedidoVenda();                   
                        $this->desenhaCadastroPedido();
                    }                
                }                
                break;
            case 'atualizarVendedor':
                {
                    if ($this->m_par[0] > 0) {
                        $this->atualizarVendedor($this->m_par[0],'fat_pedido_item');
                        $this->atualizarVendedor($this->m_par[0]);
                        $this->setPedidoVenda();                   
                        $this->desenhaCadastroPedido();
                    }                
                }                
                break;
            case 'devolucao':
                {                    
                    $this->setPedidoVenda();   
                    $resp = $this->verificaDevolucao($this->m_par[0],$this->m_par[1]);
                    $qtOriginal = str_replace('.', '', $this->m_par[5]);
                    $qtOriginal = str_replace(',', '.', $qtOriginal);

                    $this->m_par[2] = str_replace('.', '', $this->m_par[2]);
                    $this->m_par[2] = str_replace(',', '.', $this->m_par[2]);

                    $this->m_par[3] = str_replace('.', '', $this->m_par[3]);
                    $this->m_par[3] = str_replace(',', '.', $this->m_par[3]);
                    
                    $this->m_par[4] = str_replace('.', '', $this->m_par[4]);
                    $this->m_par[4] = str_replace(',', '.', $this->m_par[4]);

                    if(is_array($resp)){
                        $totalQtde =  ($resp[0]['QUANTIDADE'] + $this->m_par[2]); 
                        if($totalQtde > $qtOriginal){
                            $msg = 'Quantidade a ser devolvida ultrapassa a Quantidade Vendida.';
                        }else{
                            $this->gerarDevolucao($this->getCliente(), $this->m_par[0],
                            $this->m_par[1], $this->m_par[2],
                            $this->m_par[3], $this->m_par[4]); 
                        }
                         
                    }else{
                        $this->gerarDevolucao($this->getCliente(), $this->m_par[0],$this->m_par[1],$this->m_par[2],
                            str_replace(',', '.', $this->m_par[3]), 
                            str_replace(',', '.', $this->m_par[4])); 
                    }
                                                      
                    $this->desenhaCadastroPedido($msg);
                }                
                break;
            case 'atualizarDataEntrega':
                {
                    $this->setDataEntrega(date("d/m/Y"));    
                    $this->atualizarField('dataentrega',$this->getDataEntrega('B'));
                    $this->mostraPedido("Pedido ".$this->getId() ." entregue com sucesso!!", 'sucesso');                                  
                                
                }                
                break;
            case 'addParcelaCotacao': // pedido
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'addParcelaAlteraPED': // pedido
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'alteraPED');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'atulizarInfoItem': {
                    $itemOrigem = $this->m_par[10];
                    $itemDestino = $this->m_par[11];
                    $this->setDescricaoItem($this->m_par[3]);
                    //formatando vlrCusto
                    if(strlen($this->m_par[0]) > 6){
                        $number = explode(",", ($this->m_par[0]));
                        $newNumber = str_replace('.', '', $number[0]);
                        $vlrCusto = $newNumber.".".$number[1];
                     }else{
                       $vlrCusto = str_replace(',', '.',$this->m_par[0]);
                     }
                    
                                        
                   if($vlrCusto > 0.00) {
                        $quant = str_replace('.', '', $this->m_par[2]);
                        $this->setQtSolicitada($quant);                                             
                        if ($this->m_par[5] == true ) {
                            $produto = new c_banco;
                            $produto->setTab("EST_PRODUTO");
                            $idProduto = $produto->getField("CODIGO", "CODFABRICANTE='".$this->m_par[4]."'");
                            $produto->close_connection();    

                            c_produto::updateCustoCompra($idProduto,$vlrCusto); 
                        }

                        $custo = $vlrCusto * $this->getQtSolicitada('B');
                        $this->setCusto($custo);                                                    
                        $despesas = 0;
                        $this->setDespesas($despesas);
                        $totalItem = str_replace('.', '', $this->m_par[1]);
                        $lucrobruto = floatval($totalItem) - $custo;
                        $this->setLucroBruto($lucrobruto,true);
                        $this->setMargemLiquida( ($lucrobruto - $despesas) ,true);
                        $this->setMarkUp(round((($lucrobruto / floatval($totalItem)) * 100 ), 2),true);     
                        
                    }
                    
                    $this->alteraPedidoItemTelhasDash();

                    
                    if ($this->m_par[0] > 0) {
                        $this->alteraPedidoTotalTelhasDash();                                  
                    }
                    $this->m_letra = $this->m_letra_old;
                    $this->m_letra_old = '';

                    $percDescontoItem = str_replace('.', '', $this->m_par[7]);
                    $percDescontoItem = str_replace(',', '.', $percDescontoItem);

                    $vlrDescontoItem = str_replace('.', '', $this->m_par[6]);
                    $vlrDescontoItem = str_replace(',', '.', $vlrDescontoItem);

                    $this->setItemFabricante($this->m_par[4]);
                    $this->setDescontoItem($this->m_par[6]);
                    $this->setPercDesconto($this->m_par[7]);
                    $this->setDescricaoItem($this->m_par[3]);
                    $this->setUnitario($this->m_par[8]);
                    $this->setQtSolicitada($this->m_par[2]);
                    $this->setNumeroOc($this->m_par[9]); 
                    $this->setTotalItem();
                    $this->atualizaPedidoVendaItem();

                    $pedItem = $this->select_pedido_item_id();
                    $descontoItemTotal = 0;
                    for($i = 0; $i < count($pedItem); $i++){
                        $descontoItemTotal += $pedItem[$i]['DESCONTO']; 
                    }
                    
                    $this->atualizarField('DESCONTO', $descontoItemTotal);
                    
                     $this->setPedidoVenda(); 
                    if (($itemDestino <> "") and ($itemDestino<>$itemOrigem)){
                        $this->alteraSeqItem($this->getId(), $itemOrigem, $itemDestino);
                    }
                    $this->desenhaCadastroPedido();
                }
                break;            
            case 'exclui': // CANCELA
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $arrPedido = $this->select_pedidoVenda(0);
                    if (is_array($arrPedido)){
                        $this->setSituacao(8);
                        $this->setCliente($arrPedido[0][CLIENTE]);
                        $this->setEmissao($arrPedido[0][EMISSAO]);
                        $this->setCondPg($arrPedido[0][CONDPG]);
                        $this->setObs($arrPedido[0][OBS]);
                        $this->setDataEntrega($arrPedido[0][DATAENTREGA]);
                        $this->alteraPedidoSituacao();

                        $this->mostraPedido();
                    }else{
                        $this->mostraPedido('Pedido não pode ser CANCELADO.', 'alerta');
                    }                 
                }
                break;
            case 'motivoGeral':
                //if ($this->verificaDireitoUsuario('PedGerente', 'S')) 
                {
                    $this->atualizarMotivoItem($this->m_motivoSelecionados);
                    $this->atualizarFieldPedido(7);
                    $this->mostraPedido('Venda perdida confirmada.', 'sucesso');                
                }
                break;
            case 'NFE':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'NFE');
                    $pedidoFinaliza->controle();    
                }
                break;
            case 'NFEEnviar':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'NFEEnviar');
                    $pedidoFinaliza->controle();    
                }
                break;
                
            case 'alteraPedidoNew':
                if ($this->getSituacao() == 11 ){
                    if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                        $arrFin = c_lancamento::somaTotalDocBaixado($this->getCliente(), $this->getId(), 'PED');
                        if ($this->getTotal('B') < $arrFin[0]["TOTAL"]){
                            $this->desenhaCadastroPedido(
                                "Valor do pedido ".$this->getTotal('B')." abaixo do valor financeiro ".$arrFin[0]["TOTAL"]." já recebido",
                                "Alert");                            
                        } else {
                            $this->atualizarField('situacao', '11');
                            $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'alteraPED');
                            $pedidoFinaliza->controle(); 
                        }   
                    }
                } else {
                    $this->mostraPedido('');
                }
                break;
            case 'cadastrarPedido': // pedido
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    //$this->updatePedido();      
                    $pedItensSemCodigo = $this->select_pedido_item_sem_codigo(); 
                    if(is_array($pedItensSemCodigo)){
                        $tipoMsg = 'alerta';
                        $itens = '';
                        for($i = 0; $i < count($pedItensSemCodigo); $i++){
                            if(empty($itens)){
                                $itens = $pedItensSemCodigo[$i]['NRITEM'] ." - ". $pedItensSemCodigo[$i]['DESCRICAO'] . "<br>";
                            }else{
                                $itens .= $pedItensSemCodigo[$i]['NRITEM'] ." - ". $pedItensSemCodigo[$i]['DESCRICAO'] . "<br>";
                            }
                        }
                        $msg = "Pedido com itens sem cadastro, favor corrigir para prosseguir com o cadastro.<br>".$itens;

                        $this->desenhaCadastroPedido($msg, $tipoMsg);
                    }else{
                        $minimo = explode('|',$this->id_prod_preco_min);
                        if((count($minimo) - 1 > 0) and 
                        (($this->getSituacao() == 0) or 
                        ($this->getSituacao() == 5))){
                            $this->setSituacao(10); // EM APROVAÇÃO
                            $this->alteraPedidoSituacao();
                            $this->mostraPedido('Documento com preço menor que o permitido. Documento será colocado para em Aprovação.', 'alerta');
                        } else {
                            $parametros = new c_banco;
                            $parametros->setTab("FAT_PARAMETRO");
                            $aprovacao = $parametros->getField("APROVACAO", "FILIAL=".$this->m_empresacentrocusto);
                            //
                            $param = new c_banco;
                            $sql = "SELECT APROVACAO, MARKUPMIN FROM EST_PARAMETRO WHERE FILIAL=".$this->m_empresacentrocusto;
                            $param->exec_sql($sql);                        
                            //$est_aprovacao = is_array($param->resultado);
                            $est_aprovacao = $param->resultado;
                            $param->close_connection();
    
                            if($est_aprovacao[0]['APROVACAO'] == 'M'){ // MARKUP MINIMO
                                $ped = new c_banco;
                                $ped->setTab("FAT_PEDIDO");
                                $pedMarkup = $ped->getField("MARKUP", "ID=".$this->getId());
                                if($pedMarkup <  $est_aprovacao[0]['MARKUPMIN']){
                                    if (($this->getUsrAprovacao() != 0 AND $this->getUsrAprovacao() != "") 
                                       or ($this->getSituacao()==12)){
                                        //$this->setPedidoVenda();
                                        // $this->calculaImpostos();
                                        $this->setPedidoVenda();
                                        if ($this->getSituacao()!=6) {
                                            $this->setSituacao(6);
                                        }
                                        $this->alteraPedidoSituacao();
                                        $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                                        $pedidoFinaliza->controle();
                                    }else{
                                        //em aprovacao
                                        //$this->setPedidoVenda(); 
                                        $this->calculaImpostos();                            
                                        $this->setSituacao(10);
                                        $this->atualizarField('usraprovacao', 'NULL'); 
                                        $this->alteraPedidoSituacao($this->getCondPG());
                                        $this->mostraPedido('Documento '.$this->getId().' em Aprovação.', 'sucesso');
                                    }
                                
                                }else {
                                    $this->setPedidoVenda();
                                    // $this->calculaImpostos();
                                    $this->setSituacao(5);
                                    $this->alteraPedidoSituacao();
                                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                                    $pedidoFinaliza->controle();
                                }
                            }else{
                                if($aprovacao[0]['APROVACAO'] == 'O'){ //OBRIGATORIO
                                    if ($this->getUsrAprovacao() != 0 AND $this->getUsrAprovacao() != "") {
                                        $this->setPedidoVenda();
                                        // $this->calculaImpostos();
                                        $this->setSituacao(5);
                                        $this->alteraPedidoSituacao();
                                        $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                                        $pedidoFinaliza->controle();
                                    }else{
                                        //$this->setSituacao(10); // EM APROVAÇÃO
                                        //$this->alteraPedidoSituacao();
                                        if ($this->getId() > 0 ) {                                                        
                                            $this->calculaImpostos();                            
                                            $this->setSituacao(10);
                                            $this->atualizarField('usraprovacao', 'NULL'); 
                                            //$this->alteraPedidoSituacao($this->getCondPG());
    
                                            $totalPed = $this->getTotal();
                                            $this->setDesconto($this->getDesconto(), true);
                                            $this->atualizaPedidoVenda($totalPed);
                                            $this->mostraPedido('Documento '.$this->getId().' em Aprovação.', 'sucesso');
                                        } else {
                                            $this->mostraPedido('Documento com status digitação.', 'sucesso');    
                                        }
                                    }
                                }else if($aprovacao[0]['APROVACAO'] == 'N'){ 
                                    $this->calculaImpostos();
                                    $this->setSituacao(5);
                                    $this->alteraPedidoSituacao();
                                    $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                                    $pedidoFinaliza->controle();
                                }else {
                                    if (($aprovacao[0]['APROVACAO'] == 'S') and 
                                        (($this->getUsrAprovacao() == 0) or ($this->getUsrAprovacao() == ""))) {
                                        $banco = new c_banco();
                                        $sql = "SELECT DESCONTOMAXIMO FROM FAT_PARAMETRO ";
                                        $sql.= "WHERE (FILIAL=".$this->m_empresacentrocusto.")";
                                        $resul = $banco->exec_sql($sql);
                                        $desconto = $resul[0]['DESCONTOMAXIMO'];
                                        
                                        //$parametros = new c_banco;
                                        //$parametros->setTab("FAT_PARAMETRO");
                                        //$parametros = new c_banco;
                                        //$parametros->setTab("FAT_PARAMETRO");
                                        //$desconto = $parametros->getField("DESCONTOMAXIMO", "FILIAL=".$this->m_empresacentrocusto);
                                        //$desconto = 5;
                                            
                                        if ($desconto > 0){
                                            $verificacao = new c_user();
                                            $verificacao->m_userid = addslashes(strtoupper($this->m_useridconf));
                                            $verificacao->m_usersenha = addslashes($this->m_passwordconf);
                                            if ($verificacao->verificaUsuarioId()) {
                                                $permiteAprovarDesconto = $verificacao->verificaDireitoUsuario('PEDPERMITEAPROVARDESCONTO', 'S', 'N');
                                                $this->setUsrAprovacao($verificacao->m_userid);
                                            } else {
                                                $this->setUsrAprovacao('');    
                                            } ;
                                        }
                                    }
                                    
                                    if ($this->getUsrAprovacao() != 0 AND $this->getUsrAprovacao() != "") {
                                        $this->setSituacao(5); 
                                        if (ADMcliente == 'ivemar'){                                    
                                            $totalPed = $this->getTotal();
                                            $this->atualizaPedidoVenda($totalPed);
                                        }
                                        
                                        $this->setPedidoVenda();
                                        //$this->calculaImpostos();
                                        $this->alteraPedidoSituacao();
                                        $pedidoFinaliza = new p_pedido_venda_nf($this->getId(), 'cadastrarCOTPed');
                                        $pedidoFinaliza->controle();
                                    }else{
                                        //$this->setSituacao(10); // EM APROVAÇÃO
                                        //$this->alteraPedidoSituacao();
                                        if ($this->getId() > 0 ) {                                                        
                                            $this->calculaImpostos();                            
                                            $this->setSituacao(10);
                                            $this->atualizarField('usraprovacao', 'NULL'); 
                                            $this->alteraPedidoSituacao($this->getCondPG());
                                            $this->mostraPedido('Documento '.$this->getId().' em Aprovação.', 'sucesso');
                                        } else {
                                            $this->mostraPedido('Documento com status digitação.', 'sucesso');    
                                        }
                                    }
                                }
                            } // fim else est_parametro                    
                        }

                    } // fim if $pedItensSemCodigo
                }
                break;
            case 'agruparPedidos':                
                $transaction = new c_banco();
                //inicia transacao
                $transaction->inicioTransacao($transaction->id_connection);
                
                //cancelar pedidos
                $agruparPedidos = explode("|", ($this->m_agrupar_pedidos)); 
                $objPedido = new c_pedidoVenda();
                for ($i=0;$i<count($agruparPedidos);$i++){
                    if ($agruparPedidos[$i] > 0) {           
                        $this->setId($agruparPedidos[$i]);            
                        $this->setSituacao(8);
                        $this->alteraPedidoSituacao(null,$transaction->id_connection);
                     }                      
                }
                
                //novo pedido
                $this->setSituacao(0);
                $this->setEmissao(date("d/m/Y"));
                $this->setAtendimento(date("d/m/Y"));
                $this->setHoraEmissao(date("H:i:s"));
                $this->setEspecie("D");
                $this->setIdNatop("1");
                $this->setCondPg("0");
                $this->setCentroCusto($this->m_empresacentrocusto);
                $this->setId($this->incluiPedido($transaction->id_connection));
                             
                //busca itens dos pedidos
                $arrItensPedidos = $this->agruparPedidos($this->m_agrupar_pedidos);
                                   
                $objProduto = new c_produto();
                $objProdutoQtde = new c_produto_estoque();
                
                for ($i=0;$i<count($arrItensPedidos);$i++){
                    $codProduto = $arrItensPedidos[$i]['ITEMESTOQUE'];
                    $quant = $arrItensPedidos[$i]['QTSOLICITADA'];
                    $vlPromocao = $arrItensPedidos[$i]['PRECOPROMOCAO'];
                    $quantDigitada = $quant; // quant em digitacao
                    $quantPedido = 0;
                    $quantTotal = $quantDigitada;
                    
                    $this->setItemEstoque($codProduto);
                    // verifica se produto existe na tabela pedido item.
                    $arrItemPedido = $this->select_pedido_item_id_itemestoque($transaction->id_connection);
                    if (is_array($arrItemPedido)):
                      $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
                      $quantTotal = $quantDigitada + $quantPedido;
                      $this->pedido_venda_item(false, $arrItemPedido);
                    endif;
                    
                    $objProduto->setId($codProduto); // CODIGO PRODUTO
                    //busca dados do produto                    
                    $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, 
                                        $this->m_empresacentrocusto, $objProduto->getId());
                    
                    $this->setItemFabricante($arrItensPedidos[$i]['ITEMFABRICANTE']);
                    $this->setDesconto(str_replace('.', ',',$arrItensPedidos[$i]['DESCONTO']));
                    $this->setQtSolicitada($quantTotal);
                    $this->setUnitario(str_replace('.', ',', $arrItensPedidos[$i]['UNITARIO']));
                    $this->setPrecoPromocao(str_replace('.', ',', $arrItensPedidos[$i]['PRECOPROMOCAO']));
                    $this->setVlrTabela(str_replace('.', ',', $arrItensPedidos[$i]['VLRTABELA']));
                    $this->setTotalItem();
                    $this->setGrupoEstoque($arrItensPedidos[$i]['GRUPOESTOQUE']);
                    $this->setDescricaoItem($arrItensPedidos[$i]['DESCRICAO']);
                    
                    if (is_array($arrItemPedido)):
                      //atualiza info se existe no pedido 
                      $this->alteraPedidoItem($transaction->id_connection); 
                    else:
                      //pegar o ultimo NrItem do pedido
                      $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem($transaction->id_connection);
                      $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                      $this->IncluiPedidoItem($transaction->id_connection);
                    endif;
                    
                    // reserva produto
                    if ($arrProduto[0]['UNIFRACIONADA'] == "N"){
                        //remove reserva
                        $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "PED", 
                                    $arrItensPedidos[$i]['ID'], $arrItensPedidos[$i]['ITEMESTOQUE'], 
                                    abs($arrItensPedidos[$i]['QTSOLICITADA']),$transaction->id_connection);
                            
                        //adiciona reserva
                        $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                       $this->getId(), $this->getItemEstoque(), (int) $quantDigitada, $transaction->id_connection);
                    } else {
                      $objProdutoQtde->produtoReserva=null;
                    }
                }              
                                   
                //; commit transação
                $transaction->commit($transaction->id_connection);  
                
                //calcula total
                $this->setTotal($this->select_totalPedido());
                $this->setSituacao(0);
                $this->setPedido($this->getId());
                //atualiza informações no pedido
                $this->alteraPedidoTotal();                                                          
                $this->desenhaCadastroPedido();
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $desc = $this->getDesconto('B');
                    $frete = $this->getFrete('B');
                    $desp = $this->getDespAcessorias('B');
                    $this->setDesconto($desc);
                    $this->setFrete($frete);
                    $this->setDespAcessorias($desp);
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    
                        $this->setPedidoVenda();
                    //$arrPedido = $this->select_pedidoVenda('0');
                        $testeSit = $this->getSituacao(); 
                    //if (($this->getSituacao() == '0') or ($this->getSituacao() == '')){
                        //$this->setSituacao($arrPedido[0]['SITUACAO']);
                        //$this->setCliente($arrPedido[0]['CLIENTE']);
                    //    $this->desenhaCadastroPedido();
                    //}else{
                    //    $this->mostraPedido('Pedido não pode ser alterado.');
                    //}
                    //$resp = $this->validaAlterarPedido();
                    //if($resp['tipoMsg'] == 'sucesso'){
                        $this->desenhaCadastroPedido();
                    //}else{
                    //    $this->mostraPedido($resp['msg'], $resp['tipoMsg'] );
                    //}
                    
                    
                    
                }
                break;
            case 'altera': // cotacao
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $res = $this->validaInfoCliente($this->getCliente());
                    if($res != ''){
                        $this->desenhaCadastroPedido($res, 'alerta');
                    }else{
                        if (ADMSistema == 'PECAS'){  
                            $this->calculaImpostos();                            
                            $this->setSituacao(6); // Pedido
                            $this->alteraPedidoSituacao($this->getCondPg());
                            $this->mostraPedido('Pedido confirmado - '.$this->getId(), 'sucesso'); 
                        } 
                        else {
                            $banco = new c_banco();
                            $sql = "SELECT DESCONTOMAXIMO FROM FAT_PARAMETRO ";
                            $sql.= "WHERE (FILIAL=".$this->m_empresacentrocusto.")";
                            $resul = $banco->exec_sql($sql);
                            $desconto = $resul[0]['DESCONTOMAXIMO'];
                            
                            //$parametros = new c_banco;
                            //$parametros->setTab("FAT_PARAMETRO");
                            //$parametros = new c_banco;
                            //$parametros->setTab("FAT_PARAMETRO");
                            //$desconto = $parametros->getField("DESCONTOMAXIMO", "FILIAL=".$this->m_empresacentrocusto);
                            //$desconto = 5;
                            
                            $aprovadoDesconto = 'N';    
                            if ($desconto > 0){
                                $verificacao = new c_user();
                                $verificacao->m_userid = addslashes(strtoupper($this->m_useridconf));
                                $verificacao->m_usersenha = addslashes($this->m_passwordconf);
                                if ($verificacao->verificaUsuarioId()) {
                                    $permiteAprovarDesconto = $verificacao->verificaDireitoUsuario('PEDPERMITEAPROVARDESCONTO', 'S', 'N');
                                    if ($permiteAprovarDesconto == 'S'){
                                        $aprovadoDesconto = 'S';
                                    }
                                };
                            }
                            
                            $this->calculaImpostos();
                            $minimo = explode('|',$this->id_prod_preco_min);
                            if(count($minimo) - 1 > 0){
                                $this->setSituacao(10); // EM APROVAÇÃO
                                $this->alteraPedidoSituacao();
                                $this->mostraPedido('Pedido com dados inválidos! Será colocado para em Aprovação.', 'sucesso');
                            }else{
                                if ($desconto > 0){
                                    if ($this->getDesconto() > 0) {
                                        $pAliq = ($this->getDesconto() / $this->getTotal());
                                        $pAliq = $pAliq * 100;
                                        if ($pAliq < $desconto) {
                                            $aprovadoDesconto = 'S';
                                        }    
                                    } else {
                                        $aprovadoDesconto = 'S';    
                                    }
                                    if ($aprovadoDesconto == 'S') {
                                        if ($this->getDesconto() > 0) {
                                          $this->setUsrAprovacao($this->m_useridconf);
                                        } else {
                                            $this->setUsrAprovacao('');
                                            $this->atualizarField('usraprovacao','NULL');
                                        }
                                        $this->setSituacao(5);
                                        $this->alteraPedidoSituacao($this->getCondPg());
                                        $this->mostraPedido('Cotação confirmada.', 'sucesso');            
                                    } else {
                                        $this->atualizarField('usraprovacao','NULL');
                                        $this->setSituacao(0);
                                        $this->alteraPedidoSituacao();
                                        $this->setPedidoVenda();                   
                                        $this->desenhaCadastroPedido('Desconto maior que o permitido');
                                    }
                                } else {
                                    $this->setSituacao(5);
                                    $this->alteraPedidoSituacao($this->getCondPg());
                                    $totalPed = $this->getTotal();
                                    $this->setDesconto($this->getDesconto(), true);
                                    $this->atualizaPedidoVenda($totalPed);
                                    $this->mostraPedido('Cotação confirmada.', 'sucesso');            
                                }
                            }
                        }
                    }
                    
                }
                break;
            case 'alteraCotacao': // cotacao
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->calculaImpostos();
                    $this->setSituacao(5);
                    $this->alteraPedidoSituacao($this->getCondPg());
                    $this->mostraPedido('Cotação confirmada - '.$this->getId(), 'sucesso');
                }
                break;

            case 'desaprovado': // desaprovado /  pedido perdido
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    //$this->updatePedido();
                    $this->calculaImpostos();
                   
                    $this->setSituacao(7);
                    $this->alteraPedidoSituacao();
                    $this->mostraPedido('Pedido Desaprovado.', 'sucesso');
                }
                break;   
            case 'digita': //VOLTAR / SALVAR                   
                if($this->getId() != ''){ //salva dados do pedido
                    $pedido = $this->getId();

                    if($this->getHoraEmissao() == ''){
                        $this->getHoraEmissao(date('H:m:s')); 
                    }                    
                    if($this->getEmissao() == ''){
                        $this->setEmissao(date("Y-m-d")); 
                    }
                   // $totalPed =  $this->select_totalPedido();
                    $this->atualizaPedidoVenda();
                }            
                $this->m_letra = $this->m_letra_old; 
                $this->m_par = explode("|", $this->m_letra); 
                $this->calculaImpostos(); 
                $this->mostraPedido('');
                break;
            case 'exclui': // CANCELA
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $arrPedido = $this->select_pedidoVenda(0);
                    if (is_array($arrPedido)){
                        //$this->excluiPedido();
                        $this->setSituacao(8);
                        $this->alteraPedidoSituacao();

                        // retira reserva estoque
                        $arrItem = $this->select_pedido_item_id();
                        if (is_array($arrItem)):
                            for ($i = 0; $i < count($arrItem); $i++) {
                                $objProdutoQtde = new c_produto_estoque();
                                $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "PED", 
                                    $arrItem[$i]['ID'], $arrItem[$i]['ITEMESTOQUE'], abs($arrItem[$i]['QTSOLICITADA']));
                            }    
                        endif;

                        $this->mostraPedido($this->getId()." - pedido Cancelado com sucesso!!", 'sucesso');
                    }else{
                        $this->mostraPedido('Pedido não pode ser CANCELADO.', 'alerta');
                    }
                    
                    
                }
                break;
            case 'estorna': // Estorna pedido voltando para digitação..
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $arrPedido = $this->select_pedidoVenda();
                    $arrFin = c_lancamento::verificaDocBaixado($arrPedido[0]['CLIENTE'], $this->getId(), 'PED');
                    if ($arrFin) {
                        $this->mostraPedido('Pedido com parcelas do financeiro já baixado, não pode ser ESTORNADO.', 'alerta');
                    } else if (is_array($arrPedido) and ($arrPedido[0]['SITUACAO'] != 9)){
                        $credUtilizado = c_conta::selecionaCreditoUtilizadoCliente($arrPedido[0]['CLIENTE'], $arrPedido[0]['PEDIDO']);
                        $totalCred = $arrPedido[0]['TOTAL'];
                        if (count($credUtilizado) > 0 ){
                            for ($i = 0; $i < count($credUtilizado); $i++) {
                                if (";".$arrPedido[$i]['PEDIDO'] == $credUtilizado[$i]['PEDIDOUTILIZADO']){
                                    c_conta::updateCreditoCliente($credUtilizado[$i]['ID']);
                                    $totalCred -= $credUtilizado[$i]['VALOR'];
                                } else {
                                    $pedidoUtilizado = explode(";", $credUtilizado[$i]['PEDIDOUTILIZADO']);
                                    for ($j = 1; $j < count($pedidoUtilizado); $j++) {
                                        if ($arrPedido[$i]['PEDIDO'] != $pedidoUtilizado[$j]) {
                                            $credUtilizado[$i]['PEDIDOUTILIZADO'] = ';'.$pedidoUtilizado[$j];
                                        }
                                    }
                                    c_conta::updateCreditoCliente($credUtilizado[$i]['ID'],$credUtilizado[$i]['PEDIDOUTILIZADO'],' UTILIZADO -'.$totalCred);
                                }
                            }
                        }
                        
                        
                        $this->setSituacao(5);
                        $this->estornarFinanceiroTelhas();
                        $this->atualizarFieldPedido('5');

                        $this->mostraPedido($this->getId()." - pedido Estornado com sucesso!!", 'sucesso');
                    }else{
                        $this->mostraPedido('Pedido já baixado, não pode ser ESTORNADO.', 'alerta');
                    }                  
                }
                break;

            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                try{
                    //if ($this->getSituacao() != '6'):
                        if($this->getUsrAprovacao() != '' && $this->getSituacao() == 12){
                            $this->atualizarField('situacao', '5');
                        }

                        if ($this->getSituacao() == '6'){
                            $this->atualizarField('situacao', '11');
                        }//alteracao
                        
                        if (empty($this->getId())){
                            if (ADMSistema == 'PECAS'){ 
                                $this->setSituacao(5);
                            } else {        
                                $this->setSituacao(0);}
                            $this->setPedido(0);
                            //$this->setEmissao(date("d/m/Y"));
                            $this->setAtendimento(date("d/m/Y"));
                            $this->setHoraEmissao(date("H:i:s"));
                            $this->setEspecie("D");

                            $id = $this->incluiPedido();
                            $this->setId($id);
                            $this->setPedido($id);
                            //$this->atualizarPedido();                         
                        }
                        // $this->atualizaCampos();  ATUALIZADO MARCIO

                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();                        
                        
                        $msg = "";
                        if (($this->m_itensPedido != "") or $this->m_itensPedidoCC != ""){
                            if ($this->m_itensPedidoCC != ""){
                                $item = explode("|", $this->m_itensPedidoCC);
                            } else {
                                $item = explode("|", $this->m_itensPedido);
                            }

                            $objProduto = new c_produto();
                            $objProdutoQtde = new c_produto_estoque();
                            for ($i=0;$i<count($item);$i++){
                                $itemQuant = explode("*", $item[$i]);
                                $codProduto = $itemQuant[0];
                                
                                if ($this->m_itensPedidoCC != ""){
                                    $codNota = $itemQuant[0];
                                    $codProduto = $itemQuant[0];        
                                } else {
                                    $itemCodigo = explode("$", $itemQuant[0]);
                                    $codNota = $itemCodigo[0];
                                    $codProduto = $itemCodigo[1];    
                                }
                                

                               //tratando qtde
                                
                               if ($this->m_itensPedidoCC != ""){
                                    if(strlen($itemQuant[5]) > 6){
                                        $number = explode(",", ($itemQuant[5]));
                                        $newNumber = str_replace('.', '', $number[0]);
                                        $quant = $newNumber.".".$number[1];
                                    }else{
                                        $quant = str_replace(',', '.',$itemQuant[5]);
                                    }
                                } else {
                                    if(strlen($itemQuant[2]) > 6){
                                        $number = explode(",", ($itemQuant[2]));
                                        $newNumber = str_replace('.', '', $number[0]);
                                        $quant = $newNumber.".".$number[1];
                                    }else{
                                        $quant = str_replace(',', '.',$itemQuant[2]);
                                    }
                                }

                                if ($this->m_itensPedidoCC != ""){
                                    $vlPromocao = 0;
                                } else {
                                    $vlPromocao = $itemQuant[3];
                                }
                                $quantDigitada = $quant; 
                                
                                $quantPedido = 0;
                                $quantTotalPromocaoMes = $this->selectQuantPedidoItem($this->getCliente(), $codProduto);
                                $quantTotal = $quantDigitada;
                                // verifica se produto existe na tabela pedido item.
                                // verificar se existe o item no pedido
                                $this->setItemEstoque($codProduto);
                                if ($itemQuant[4] == 'N') {
                                    $arrItemPedido = $this->select_pedido_item_id_itemestoque($transaction->id_connection);
                                    if (is_array($arrItemPedido)){
                                        $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
                                        $quantTotal = $quantDigitada + $quantPedido;
                                        $this->pedido_venda_item(false, $arrItemPedido);
                                    }
                                }
                                // Consluta na table de produtos para pegar os dados
                                $objProduto->setId($codProduto); // CODIGO PRODUTO
                                
                                    if($codProduto == 'SEM_CODIGO'){
                                        $arrProduto[0] =[
                                            'UNIDADE' => 'SEM',
                                            'CODFABRICANTE' => 'SEM_CODIGO',
                                            'GRUPO' => 0,
                                        ];
                                    }else{
                                        $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, 
                                        $this->m_empresacentrocusto, $objProduto->getId(), $consultaEstoque);
                                    }
                                    if ($itemQuant[4] == 'N') {
                                        $valorUnitario = $arrItemPedido[0]['UNITARIO'];
                                        $valorUnitario = number_format($valorUnitario, 2);   
                                    } else {
                                        if ($this->m_itensPedidoCC != ""){
                                            $valorUnitario = c_tools::moedaBd($itemQuant[4]);
                                            $valorUnitario = $itemQuant[4];
                                        } else {
                                            $valorUnitario = c_tools::moedaBd($itemQuant[1]);
                                        }
                                    }                                     
                                    //if ($valorUnitario > $arrProduto[0]['PRECOMINIMO'])   {                                            
                                        if ($quantDigitada > 0.00) {

                                        if (($quantDigitada > 0.00) AND ($valorUnitario > 0.00) AND ($arrProduto[0]['UNIDADE'] <> '')):
                                                //($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                                                //(floatval($arrProduto[0]['VENDA']) > floatval(0)) and ($arrProduto[0]['UNIDADE'] <> '')): // TESTA PRECO E QUANT DISPONIVEL, UNIDADE
                                            
                                            if ((floatval($arrProduto[0]['PROMOCAO']) >floatval(0)) and 
                                                (($quantTotal + $quantTotalPromocaoMes) > $arrProduto[0]['QUANTLIMITE'])): // TESTA MAXIMO VENDA PROMOCAO
                                                $msg .= $arrProduto[0]['DESCRICAO']." Quantidade acima limite promoção - Quant:".$arrProduto[0]['QUANTLIMITE']."<br>";
                                            else:
                                                //$this->setItemEstoque($item[$i]);
                                                $this->setItemFabricante($arrProduto[0]['CODFABRICANTE']);
                                                //$this->setDesconto(str_replace('.', ',', $this->m_desconto));
                                                $this->setQtSolicitada($quantTotal, true);
                                                if (floatval($vlPromocao) >floatval(0)){
                                                    $this->setUnitario( $vlPromocao);
                                                } else if ($itemQuant[4] == 'N') {
                                                    $this->setUnitario( $valorUnitario,true);
                                                } else {
                                                    if ($this->m_itensPedidoCC != ""){
                                                        $this->setUnitario($itemQuant[4],true); 
                                                    } else {
                                                        $this->setUnitario($itemQuant[1]); 
                                                    }                                          
                                                }
                                                // % Desconto Item  
                                                $this->setPercDesconto($itemQuant[6]);
                                                // $this->setDescontoItem($itemQuant[6]);                                                

                                                $this->setPrecoPromocao(str_replace('.', ',', $vlPromocao));
                                                $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));
                                                $this->setTotalItem();
                                                
                                                // $totalProduto = $this->getUnitario('B') * $this->getQtSolicitada('B');
                                                $totalProduto = $this->getTotalItem('B');

                                                //  valor de Desconto Item 
                                                // $percDescontoItem = (($this->getDescontoItem('B')*100) / $totalProduto);
                                                // $percDescontoItem = round($percDescontoItem, 2);
                                                // $percDescontoItem = number_format($percDescontoItem, 2, ',', '.');
                                                // $this->setPercDesconto($percDescontoItem);

                                                $valorDescontoItem = (($this->getPercDesconto('B')*$totalProduto)/100);
                                                $this->setDescontoItem($valorDescontoItem, true);

                                                $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                                                if ($this->m_itensPedidoCC != ""){
                                                    $this->setDescricaoItem($itemQuant[1]);
                                                } else {
                                                    
                                                    $str = $itemQuant[5];
                                                    //$p1 = htmlentities($str);
                                                    //$p1 = str_replace('&nbsp;', ' ', $p1);
                                                    $this->setDescricaoItem($str);
                                                }
                                                
                                                $this->setUsrFatura($this->m_userid);
                                                                            
                                                $custo = $arrProduto[0]['CUSTOCOMPRA'] * $quantTotal;
                                                $this->setCusto($custo,true);
                                                    
                                                $despesas = 0;
                                                $this->setDespesas($despesas);
                                                    
                                                $totalItem = $this->getTotalItem();
                                                $lucrobruto = floatval($totalItem) - $custo;
                                                $this->setLucroBruto($lucrobruto,true);
                                                $this->setMargemLiquida( ($lucrobruto - $despesas) ,true);
                                                $this->setMarkUp(round((($lucrobruto / floatval($totalItem)) * 100 ), 2),true); 
                                                $this->setCodigoNota($codNota); 
                                
                                                $calculoST = 'N';                                                
                                                if ($calculoST == 'S') { 
                                                    $this->setBcIcms(0, true);
                                                    $this->setValorIcms(0, true);
                                                    $this->setValorIcmsDiferido(0, true);
                                                    $this->setValorIcmsOperacao(0, true);
                                                    $this->setValorBcSt(0, true);
                                                    $this->setValorIcmsSt(0, true);
                                                    $this->setMvaSt(0, true);
                                                    $this->setAliqIcmsSt(0, true);
                                                    $this->setAliqRedBCST(0, true);  
                                                    $this->setAliqIcmsUfDest(0, true);        
                                                    $this->setAliqIcmsInter(0, true);
                                                    $this->setAliqIcmsInterPart(0, true);
                                                    $this->setFcpUfDest(0, true);
                                                    $this->setValorIcmsUfDest(0, true); 
                                                    $this->setValorIcmsUFRemet(0, true);

                                                    //ICMS/ICMS-ST
                                                    $bcIcms = 0;
                                                    $aliqIcms = 0;
                                                    $vlIcms = 0;
                                                    $vlIcmsDiferido = 0;
                                                    $vlIcmsOperacao = 0;
                                                    $vlBcSt = 0;
                                                    $vlIcmsSt = 0;
                                                    $mvaSt = 0;
                                                    $aliqIcmsSt = 0;
                                                    $percReduacaoBcSt = 0;
                                                    //DIFAL
                                                    $aliqFcpSt = 0;
                                                    $aliqIcmsInter = 0;
                                                    $aliqIcmsInterPart = 0;
                                                    $vlFcpUfDest = 0;
                                                    $vlDifal = 0;
                                                    $vlIcmsUFRemet = 0;
                                                    //PIS/COFINS
                                                    $aliqPis = 0;
                                                    $vlPis = 0;
                                                    $aliqCofins = 0;
                                                    $vlCofins = 0;    
                               
                                                    // BUSCA CLIENTE
                                                    $banco = new c_banco();
                                                    $sql = "select * from fin_cliente where (cliente=".$this->getCliente().")";
                                                    $cliente = $banco->exec_sql($sql);
                                                    $contribuinteICMS = $cliente[0]['CONTRIBUINTEICMS'];
                                                    $consumidorfinal = $cliente[0]['CONSUMIDORFINAL'];
                                                    $regimeespecialST = $cliente[0]['REGIMEESPECIALST'];
                                                    $regimeespecialSTMT = $cliente[0]['REGIMEESPECIALSTMT'];
                                                    $ufDestino = $cliente[0]['UF'];        

                                                    // BUSCA EMPRESA (CRT/UF)
                                                    $sql = "select * from amb_empresa where (centrocusto=".$this->m_empresacentrocusto.")";
                                                    $emp = $banco->exec_sql($sql);
                                                    $crt = $emp[0]['REGIMETRIBUTARIO'];
                                                    $empresaUF = $emp[0]['UF'];

                                                    // BUSCA EST_NAT_OP_TRIBUTO
                                                    $sql  = "SELECT * FROM EST_NAT_OP_TRIBUTO ";
                                                    $sql .= "WHERE (CENTROCUSTO =".$this->m_empresacentrocusto.") AND (IDNATOP = 9 ) ";
                                                    $sql .= "AND (UF='".$cliente[0]['UF']."') AND (PESSOA='".$cliente[0]['PESSOA']."') AND ";
                                                    if ($crt=='3'):
                                                        $sql .= "(ORIGEM='".$arrProduto[0]['ORIGEM']."') AND (TRIBICMS='".$arrProduto[0]['TRIBICMS']."') AND ((NCM='".$arrProduto[0]['NCM']."') OR (NCM='')) AND ((CEST='".$arrProduto[0]['CEST']."') OR (CEST=''));";
                                                    else:    
                                                        $sql .= "(ORIGEM='".$arrProduto[0]['ORIGEM']."') ";
                                                    endif;

                                                    if ($arrProduto[0]['NCM'] != ''){
                                                        $sql .= " and (NCM='".$arrProduto[0]['NCM']."');";
                                                    }
                                                    
                                                    $banco->exec_sql($sql);
                                                    $banco->close_connection();
                                                    $arrTributos =  $banco->resultado;

                                                    $insideIpiBc = 'N'; //??????


                                                    $aliqIcms = $arrTributos[0]['ALIQICMS'];
                                                    $aliqFcpSt = $arrTributos[0]['ALIQFCPST'];
                                                    $mvaSt = $arrTributos[0]['MVAST'];
                                                    $aliqIcmsSt = $arrTributos[0]['ALIQICMSST'];
                                                    $percReducaoBc = $arrTributos[0]['PERCREDUCAOBC'];
                                                    $percReducaoBcSt = $arrTributos[0]['PERCREDUCAOBCST'];
                                                    $percDiferido = $arrTributos[0]['PERCDIFERIDO'];
                                                    $cfop = $arrTributos[0]['CFOP'];
                                                    $tribicms = $arrTributos[0]['TRIBICMS'];
                                                    $csosn = $arrTributos[0]['TRIBICMSSAIDA'];
                                                    //DIFAL
                                                    $aliqIcmsInter = 0;
                                                    $aliqIcmsInterPart = 0;
                                                    //PIS/COFINS - Aliquotas
                                                    /*$aliqPis = $arrTributos[0]['ALIQPIS'];
                                                    $CstPis = $arrTributos[0]['CSTPIS'];
                                                    $aliqCofins = $arrTributos[0]['ALIQCOFINS'];
                                                    $CstCofins = $arrTributos[0]['CSTCOFINS'];  
                                                    // se for simples
                                                    if ($crt=='1') {
                                                        //if for saida ?
                                                        $CstPis = '49';
                                                        $CstCofins = '49'; 
                                                        //else
                                                        //$CstPis = '98';
                                                        //$CstCofins = '98';
                                                    }*/        
    
                                                    $totalProduto = $this->getTotalItem('B');;
                                                    $bcIcms = $totalProduto;
                                                /* if (($crt=='1') and ($natOp!='2')):  // natOp           
                                                        $bcIcms = 0;
                                                        //PIS/COFINS - BC
                                                        $bcPis = 0;
                                                        $bcCofins = 0;
                                                    else:    
                                                        //bcicms = prod - desc + ipi + despacess + frete
                                                        $bcIcms = $totalProduto - $descontoProduto;
                                                        //PIS/COFINS - BC
                                                        //$bcPis = $totalProduto - $descontoProduto;
                                                        //$bcCofins = $totalProduto - $descontoProduto;
                                                    endif;*/
                                                    //$vlBcSt = $bcIcms;
                                                    $vlIpi = 0; // ?????? calcular
                                                    
                                                    $totalProduto = $totalItem;
                                                    $descontoProduto = 0;
                                                    $freteItem = 0;
                                                    $despAcessoriasItem = 0;
                                                    $seguroItem = 0;

                                                    $origem = $arrProduto[0]['ORIGEM'];
                                                    
                                                    $calculoDifalNovo="S";

                                                    //IPI
                                                    // CALCULAR IPI 
                                                    // controle da CST na tela? ipi na tela? 
                                                    
                                                    //ICMS
                                                    // CALCULAR ICMS
                                                    if (( $csosn == '00') || ( $csosn == '10')  || ( $csosn == '30'))
                                                    {
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;    
                                                    }
                                                        
                                                    // CALCULAR DIFAL //00 e 102 //DIFAL
                                                    // CALCULAR DIFAL-FCP
                                                    //<ICMSUFDest> - Informação do ICMS Interestadual
                                                    // Grupo a ser informado nas vendas interestaduais para consumidor final, não contribuinte do ICMS.
                                                    if (($csosn == '00') || ($csosn == '102'))
                                                    {
                                                        if (($contribuinteICMS=="N") && ($consumidorfinal=="S"))
                                                        {
                                                            // <vBCUFDest> - Valor da BC do ICMS na UF destino
                                                            if ($crt==1){ // Simples Nacional
                                                                $vlbcIcmsUfDest = $totalProduto-$descontoProduto+
                                                                    $freteItem+$despAcessoriasItem+$seguroItem;
                                                            }
                                                            else{
                                                                $vlbcIcmsUfDest = $bcIcms;
                                                            }
                                                            //cAliqFecoepUFDest => $aliqFcpSt
                                                            //cAliqIcmsUFDest   => $aliqIcmsSt
                                                            if ($aliqFcpSt>0.01){
                                                                $aliqIcmsSt -= $aliqFcpSt;
                                                            }
                                                    
                                                            // <pFCPUFDest> - Percentual do ICMS relativo FCP na UF de destino $aliqFcpSt
                                                    
                                                            // <pICMSUFDest> - Alíquota interna da UF de destino $aliqIcmsSt
                                                    
                                                            // <pICMSInter> - Aliquota de ICMS interestadual - cAliqIcmsInter (4, 7, 12)
                                                            /*
                                                            if ($origem==1){
                                                                $aliqIcmsInter = 4;
                                                            }
                                                            else {
                                                                $aliqIcmsInter = $aliqIcms;
                                                            }
                                                            */
                                                            $aliqIcmsInter = $aliqIcms;
                                                            //<pICMSInterPart> Percentual provisório de partilha do ICMS Interestadual 
                                                            // 100% a partir de 2019.
                                                            $aliqIcmsInterPart = 100.00;                    
                                                            //Cálculo FCP
                                                            if ($aliqFcpSt>0.01){
                                                                //<vBCFCPUFDest> - Valor da BC FCP na UF de destino
                                                                $bcFecoepUFDest = $vlbcIcmsUfDest;
                                                                //<vFCPUFDest> - Valor do FCP UF Dest
                                                                $vlFcpUfDest = $bcFecoepUFDest*($aliqFcpSt/100);
                                                            }
                                                            // <vICMSUFDest> Cálculo Difal  BC * (18-12=6)
                                                            $vlDifal = $vlbcIcmsUfDest * (($aliqIcmsSt-$aliqIcmsInter) / 100);
                                                            // <vICMSUFRemet> Valor do ICMS Interestadual para a UF do remetente
                                                            // Nota: A partir de 2019, este valor será zero.
                                                            $vlIcmsUFRemet = 0.00;
                                                        }    
                                                    }

                                                    //ST
                                                    // CALCULAR ST
                                                    // CALCULAR DIFAL-ST //'10' '30' '70' '201' '202' '203'    
                                                    // CALCULAR FCP-ST
                                                    if (( $csosn == '10') || ( $csosn == '30')  || ( $csosn == '70') || 
                                                        ($csosn == '201') || ( $csosn == '202') || ( $csosn == '203') )
                                                    {
                                                        //Rodrigo
                                                        //Base de ICMS-ST
                                                        //Cálculo ICMS-ST (Normal)
                                                        if (($regimeespecialST=="N") && ($regimeespecialSTMT=="N") &&
                                                            ($contribuinteICMS=="S") && ($consumidorfinal=="N"))                                    
                                                        {
                                                            $vlBcSt = $bcIcms;
                                                            if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                                                                $vlBcSt += $vlIpi;
                                                            endif;
                                                            $vlBcSt = ($vlBcSt * (1 + ($mvaSt/100))); // aplica indice mva bc st
                                                            $vlIcmsSt = (($vlBcSt)*($aliqIcmsSt/100)) - $vlIcms; //calcula icms st
                                                        }
                                                    //Cálculo ICMS-ST (DIFAL-ST)
                                                    else if (($regimeespecialST=="N") && ($regimeespecialSTMT=="N") &&
                                                            ($contribuinteICMS=="S") &&  ($consumidorfinal=="S"))                                    
                                                    {
                                                        $vlBcSt = $bcIcms;
                                                        if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                                                            $vlBcSt += $vlIpi;
                                                        endif;
            
                                                        if ($calculoDifalNovo=="S") {
                                                            $vlBcSt = $bcIcms - $vlIcms;
                                                            $VlSubTribDif = 1 - ($aliqIcmsSt/100);
                                                            $vlBcSt = $vlBcSt/$VlSubTribDif;
                                                            $vlIcmsInterna = $vlBcSt*($aliqIcmsSt/100);
                                                            $vlIcmsSt = $vlIcmsInterna-$vlIcms; 
                                                        } else {
                                                            $vlIcmsSt = ( ($aliqIcmsSt - $aliqIcms) / 100 ) * $bcIcms;
                                                        }                   
                                                    } 
                                                    //Cálculo ICMS-ST (MT)
                                                    else if (($ufDestino=="MT") && ($regimeespecialSTMT=="S") &&
                                                            ($regimeespecialST=="N") && ($contribuinteICMS=="S"))
                                                    {
                                                        /*
                                                        if (($origem==1) || ($origem==6)){
                                                            $vlIcmsProprio = $vlBcSt*4/100;
                                                        }
                                                        else{
                                                            $vlIcmsProprio = $vlBcSt*$aliqIcms/100; 
                                                        }
                                                        */
                                                        $vlIcmsProprio = $vlBcSt*$aliqIcms/100; 
                                                        //$valorSTEstimativa = ($vlBcSt + $vlIpi) * ($aliqRegEspSTMT/100);                    
                                                        $valorSTEstimativa = $vlBcSt* ($aliqRegEspSTMT/100);
                                                        $vlTotl=$vlIcmsProprio+$valorSTEstimativa/($aliqIcmsSt/100);
                                                        $vlIcmsSt=$valorSTEstimativa;
                                                        $vlBcSt=$vlTotl;
                                                    }                    
                                                    //ICMS-ST e FCP-ST
                                                    if ($aliqFcpSt > 0.01){
                                                        $vlBcFcpSt = $eBaseSubTrib;
                                                        $vlFcpSt = $vlBcFcpSt*($aliqFcpSt/100);
                                                        //Descontar o valor da ST
                                                        $vlIcmsSt -= $vlFcpSt;
                                                    }           
                                                }

                                                switch ($arrTributos[0]['TRIBICMSSAIDA']){
                                                    case '00': // tributado integralmente
                                                        //ICMS
                                                        // observacao
                                                        //DIFAL                     

                                                        break;
                                                    case '10': // Tributada e com cobrança do ICMS por substituição tributária
                                                        //ICMS

                                                        //ICMS-ST
                                                        break;
                                                    case '20': // Tributação com redução de base de cálculo
                                                        $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao
                                                        $vlIcms = ($aliqIcms/100) * $bcIcms;

                                                        break;
                                                    case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                                                        //ICMS
                                                        $vlBcSt = $bcIcms;
                                                        if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                                                            $vlBcSt += $vlIpi;
                                                        endif;
                                                        $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                                                        $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                                                        $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st
                                                        $bcIcms = 0;
                                                        $vlIcms = 0;
                                                        break;
                                                    case '40': // Tributação Isenta
                                                    case '41': // Não tributada
                                                    case '50': // Suspensão
                                                        $bcIcms = 0;
                                                        $vlIcms = 0;
                                                        $vlBcSt = 0;
                                                        $vlIcmsSt = 0;
                                                        $vlBcStRet = 0;
                                                        $vlIcmsStRet = 0;
                                                        break;
                                                    case '51': // Tributação com Diferimento (a exigência do preenchimento das
                                                            //informações do ICMS diferido fica a critério de cada UF).

                                                        $bcIcms = ($percReducaoBc/100)*$totalProduto;
                                                        $vlIcmsDiferido = ($percDiferido/100)*$bcIcms;
                                                        $vlIcmsOperacao = ($aliqIcms/100)*$bcIcms;
                                                        $vlIcms = $vlIcmsOperacao-$vlIcmsDiferido;
                                                        break;
                                                    case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                                                        // buscar valor de impostos retido na nf de entrada
                                                        /*
                                                        O CST 060 significa: mercadoria de origem nacional, e ICMS cobrado anteriormente por Substituição Tributária.
                                                        Como o ICMS já foi cobrado anteriormente, esse imposto NÃO deve ser destacado na próxima circulação da mercadoria, 
                                                        em operações internas. Então, utiliza-se o CST 060. 
                                                        O ICMS devido por este contribuinte já foi pago na entrada da mercadoria, por Substituição Tributária, 
                                                        com margem de lucro e já recolhido aos cofres estaduais, pelo remetente. 
                                                        Portanto o sistema está correto em não destacar “Base de Cálculo do ICMS” e “Valor do ICMS”, 
                                                        porque o imposto já foi recolhido por ST. 
                                                        É necessário, porém, que seja informado no campo “Dados Adicionais – Informações Complementares”, da nota fiscal, 
                                                        o dispositivo legal que permite o não destaque do ICMS; em SC, o dispositivo legal é: 

                                                        “ Imposto Retido por Substituição Tributária – RICMS-SC/01 – Anexo 3”. 

                                                        Em toda nota fiscal, modelo 1, 1-A ou 55 (eletrônica), é obrigatório informar qual o dispositivo legal que permite 
                                                        o não destaque do ICMS. 
                                                        Há também nos Regulamentos de ICMS, uma determinação que seja indicada no campo “Dados Adicionais – 
                                                        Informações Complementares”, quando da emissão dos mesmos modelos de notas fiscais acima mencionados, 
                                                        a base de cálculo e o valor do imposto retido, salvo nas saídas destinadas a não contribuinte. 

                                                        Essas informações são obtidas na NF de compra, onde o ICMS Substituto é cobrado. 
                                                        Se faz necessária esse informação porque o destinatário poderá creditar esse ICMS, 
                                                        no caso de mercadoria para industrialização ou ativo imbolizado. 

                                                        Quem utiliza o CST 060, é o Contribuinte Substituído, ou seja, aquele que pagou antecipadamente o 
                                                        ICMS que seria de sua obrigação, quando da saída posterior da mercadoria.                  

                                                        */
                                                        $bcIcms = 0;
                                                        // ******* buscat o valor st retido na nf de entrada
                                                        $vlBcStRet = 0;
                                                        $vlIcmsStRet = 0;
                                                        break;
                                                    case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                                                            // do ICMS por substituição tributária
                                                        $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao Bc
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;
                                                        $vlBcSt = $bcIcms;
                                                        if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                                                            $vlBcSt += $vlIpi;
                                                        endif;
                                                        $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                                                        $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                                                        $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st


                                                        break;
                                                    case '90': // Tributação ICMS: Outros
                                                        $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao Bc
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;
                                                        $vlBcSt = $bcIcms;
                                                        if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                                                            $vlBcSt += $vlIpi;
                                                        endif;
                                                        $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                                                        $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                                                        $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st
                                                        break;
                                                    case '102': // Tributação com redução de base de cálculo
                                                        //$bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao
                                                        //$vlIcms = ($aliqIcms/100)*$bcIcms;
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;
                                                        // observacao
                                                        
                                                        //DIFAL
                                                                    
                                                        break; 
                                                    case '201': // Tributada e com cobrança do ICMS por substituição tributária
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;

                                                        //ICMS-ST             
                                                        break;
                                                    case '202': // Tributada e com cobrança do ICMS por substituição tributária
                                                        $vlIcms = ($aliqIcms/100)*$bcIcms;

                                                        //ICMS-ST
                                                    
                                                        break;
                                                    case '500': 
                                                        $bcIcms = 0;
                                                        // ******* buscat o valor st retido na nf de entrada
                                                        $vlBcStRet = 0;
                                                        $vlIcmsStRet = 0;
                                                        break;             
                                                }

                                                //PIS/COFINS
                                                // CALCULAR PIS/COFINS     
                                                // controle da CST na tela? 
                                                // calculo PIS
                                                /*switch ($cstPis){
                                                    case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                                                        $vlPis = ($bcPis * $aliqPis) / 100;
                                                        break;            
                                                    case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                                                        $vlPis = ($bcPis * $aliqPisMonofasica) / 100;
                                                        break;
                                                    case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                                                        $bcPis = $objNfProd->getQuant();
                                                        $vAliqProd = $aliqPis;
                                                        $vlPis = ($bcPis * $vAliqProd);
                                                        break;
                                                    case '04': 
                                                    case '05': 
                                                    case '06': 
                                                    case '07': 
                                                    case '08': 
                                                    case '09': 
                                                    case '49':
                                                        $bcPis = 0;
                                                        $aliqPis = 0;
                                                        $vlPis = 0;
                                                        break;
                                                    //CST – Entradas Créditos Básicos Regime Não Cumulativo
                                                    // 50, 51, 52, 53, 54, 55, 56
                                                    //CST – Entradas Créditos Presumidos Regime Não Cumulativo
                                                    // 60, 61, 62, 63, 64, 65, 66, 67
                                                    //CST – Demais Entradas Regimes Cumulativo e Não Cumulativo
                                                    // 70, 71, 72, 73, 74, 75, 98
                                                    default :
                                                        $vlPis = ($bcPis * $aliqPis) / 100;
                                                } // switch

                                                // calculo COFINS
                                                switch ($cstCofins){
                                                    case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                                                        $vlCofins = ($bcCofins * $aliqCofins) / 100;
                                                        break;            
                                                    case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                                                        $vlCofins = ($bcCofins * $aliqCofinsMonofasica) / 100;
                                                        break;
                                                    case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                                                        $bcCofins = $objNfProd->getQuant();
                                                        $vAliqProd = $aliqCofins;
                                                        $vlCofins = ($bcCofins * $vAliqProd) / 100;
                                                        break;
                                                    case '04': 
                                                    case '05': 
                                                    case '06': 
                                                    case '07': 
                                                    case '08': 
                                                    case '09': 
                                                    case '49':
                                                        $bcPis = 0;
                                                        $aliqPis = 0;
                                                        $vlPis = 0;
                                                        break;
                                                    //CST – Entradas Créditos Básicos Regime Não Cumulativo
                                                    // 50, 51, 52, 53, 54, 55, 56
                                                    //CST – Entradas Créditos Presumidos Regime Não Cumulativo
                                                    // 60, 61, 62, 63, 64, 65, 66, 67
                                                    //CST – Demais Entradas Regimes Cumulativo e Não Cumulativo
                                                    // 70, 71, 72, 73, 74, 75, 98
                                                    default :
                                                        $vlCofins = ($bcCofins * $aliqCofins) / 100;
                                                } // switch*/

                                                //setar valores calculados
                                                $this->setBcIcms($bcIcms, true);
                                                $this->setValorIcms($vlIcms, true);
                                                $this->setValorIcmsDiferido($vlIcmsDiferido, true);
                                                $this->setValorIcmsOperacao($vlIcmsOperacao, true);
                                                $this->setValorBcSt($vlBcSt, true);
                                                $this->setValorIcmsSt($vlIcmsSt, true);
                                                $this->setMvaSt($mvaSt, true);
                                                $this->setAliqIcmsSt($aliqIcmsSt, true);
                                                $this->setAliqRedBCST($percReduacaoBcSt, true);  
                                                $this->setAliqIcmsUfDest($aliqFcpSt, true);        
                                                $this->setAliqIcmsInter($aliqIcmsInter, true);
                                                $this->setAliqIcmsInterPart($aliqIcmsInterPart, true);
                                                $this->setFcpUfDest($vlFcpUfDest, true);
                                                $this->setValorIcmsUfDest($vlDifal, true); 
                                                $this->setValorIcmsUFRemet($vlIcmsUFRemet, true);
                                                $this->setCfop($cfop);
                                                $this->setOrigem($origem);
                                                $this->setTribIcms($tribicms);
                                                $this->setCsosn($csosn);
                                            }

                                                //inicio transação
                                                $transaction = new c_banco();
                                                $transaction->inicioTransacao($transaction->id_connection);

                                                if (is_array($arrItemPedido)):
                                                    $this->alteraPedidoItemTelhas($transaction->id_connection);
                                                    
                                                else:
                                                    //pegar o ultimo NrItem do pedido
                                                    $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem($transaction->id_connection);
                                                    $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                                                    $this->IncluiPedidoItemTelhas($transaction->id_connection);
                                                   // $descontoItemTotal = $this->getDescontoItem('B');
                                                endif;
                                                // DESCONTO TOTAL PEDIDO
                                                $desconto = $this->getDesconto('F');
                                                if(strlen($desconto) > 6){
                                                    $number = explode(",", ($desconto));
                                                    $newNumber = str_replace('.', '', $number[0]);
                                                    $descontoFormatado = $newNumber.".".$number[1];
                                                }else{
                                                    $descontoFormatado = str_replace(',', '.',$desconto);
                                                }
                                                $descontoFormatado += $this->getDescontoItem('B');
                                                $this->setDesconto($descontoFormatado, true);

                                                if ($arrProduto[0]['UNIFRACIONADA'] == "N"){
                                                    $objProdutoQtde->produtoReserva($this->getCentroCustoEntrega(), "PED", 
                                                        $this->getId(), $this->getItemEstoque(), $quantDigitada, $transaction->id_connection);
                                                }else {$objProdutoQtde->produtoReserva=null;}
                                                //msg de aviso preco do item
                                                number_format($arrProduto[0]['PRECOMINIMO'], 2, ',', '.');
                                                // if ($valorUnitario < $arrProduto[0]['PRECOMINIMO']){
                                                //     $msg .= $arrProduto[0]['DESCRICAO']." Preço menor que o Preço minimo";
                                                // }

                                                // commit
                                                $transaction->commit($transaction->id_connection);
                                                $this->calculaImpostos();

                                            endif;  
                                        else:
                                            if ($quantDigitada > 0) {
                                                $msg .= $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel<br> Unidade venda não cadastrada";
                                            }
                                        endif;
                                                                
                                    } else {
                                        if ($quantDigitada > 0) {
                                            $msg .= $arrProduto[0]['DESCRICAO']." Quantidade não disponivel<br> Unidade venda não cadastrada";
                                        }                                    
                                    }
                                //} else {
                                //  if ($quantDigitada > 0) {
                                //      $msg .= $arrProduto[0]['DESCRICAO']." Preço menor que o permitido";
                                //  } 
                                //}   
                            
                            }
                            $ItemFoiAdicionado = 'S';
                            $titleMsg = "sucesso";
                            if ($msg != "") {
                                $titleMsg = " ";
                            } 
                            $this->desenhaCadastroPedido($msg,$titleMsg,$ItemFoiAdicionado);                            
                            $ItemFoiAdicionado = 'N';
                        }
                        else{
                            $this->desenhaCadastroPedido("Selecione um Produto para compra",'erro');
                        }
                    /*else:
                        $tipoMensagem = 'alerta';
                        $msg  = 'Pedido não pode ser alterado.';
                        $this->m_submenu = "cadastrar";
                        $this->desenhaCadastroPedido($msg, $tipoMensagem);
                    endif;*/
                } catch (Error $e) {
                    $transaction->rollback($transaction->id_connection);    
                    throw new Exception($e->getMessage()."Item não cadastrado " );

                } catch (Exception $e) {
                    if ($transaction->id_connection != null)
                        $transaction->rollback($transaction->id_connection);    
                    $this->desenhaCadastroPedido("Item não cadastrado - FAZER LOGIN NO SISTEMA, SE PERSISTER A MENSAGEM ENTRAR EM CONTATO COM O SUPORTE.<br>".$e->getMessage(), 'error');
                   // throw new Exception($e->getMessage(). "Item não cadastrado " );

                }
                    
                    
                    
                    
//                    $sit = $this->getSituacao();
//                    if ($this->getSituacao() == '0'):
//                        $tipoMensagem = '';
//                        $objPedidoTools = new c_pedidoVendaTools();
//                        $id = $this->getId();
//                        // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
//                        if (empty($id)){
//                            $this->setSituacao(0);
//                            $this->setEmissao(date("d/m/Y"));
//                            $this->setAtendimento(date("d/m/Y"));
//                            $this->setHoraEmissao(date("H:i:s"));
//                            $this->setEspecie("D");
//                            $this->setCentroCusto($this->m_empresacentrocusto);
//                            $id = $this->incluiPedido();
//                        }
//
//
//                        $msg = $objPedidoTools->incluiItensPedidoControle($this->m_empresacentrocusto, $id, $this->m_itensPedido, 
//                                $this->m_itensQtde, $this->m_desconto, $tipoMensagem, $this->getCliente());
//                        $this->setId($id);
//                    else:
//                        $tipoMensagem = 'alerta';
//                        $msg  = 'Pedido não pode ser alterado.';
//                        $this->m_submenu = "cadastrar";
//                    endif;
//                    $this->desenhaCadastroPedido($msg, $tipoMensagem);
                }
                break;
            case 'incluiDescItem':
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    // inclui complemento da descrição pedido_item
                    $tipoMensagem = '';
                    $objPedidoTool = new c_pedidoVendaTools();
                    $msg = $objPedidoTool->alteraPedidoItemDescricao(NULL,$this->getDescricaoItem(),$this->getId(), $this->getNrItem());
                    $this->desenhaCadastroPedido($msg);                   
                }
                break;
            case 'excluiItem':
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    // exclui
                    $tipoMensagem = '';
                    $objPedidoTools = new c_pedidoVendaTools();
                    $msg = $objPedidoTools->excluiItensPedidoControle($this->m_empresacentrocusto, 
                            $this->getId(), $this->getNrItem(), $tipoMensagem);
                    $this->desenhaCadastroPedido($msg);
                   
                }
                break;
            case 'entregue':
                //seta o pedido
                $this->setPedidoVenda();
                //estado de emissao de nota 
                if((($this->getSituacao()) == 3) and (($this->getPedido()) > 0)){
                    $this->setSituacao(4);
                    $this->alteraPedidoSituacao();
                    $this->mostraPedido();
                }else{
                    $this->mostraPedido('Pedido não pode ser alterado para entregue.', 'alerta');
                }                
                break;
            case 'motivo':
                    $this->setPedidoVenda();
                    $this->desenhaCadastroPedido();               
                    break;
            case 'itensmotivosalvar':
                $this->setPedidoVenda();
                $item = explode("|", $this->itensperdido);
                for ($i=1;$i<count($item);$i++){
                    if ($this->verificarPedidoItem($item[$i]) == "") {
                        $this->atualizarMotivoItem($item[0],$item[$i]);
                    }                                   
                } 
                $total = $this->select_totalPedido();
                $this->atualizarTotal($total);
                $this->desenhaCadastroPedido();               
                break;
            case 'atualizarInfo': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $desconto = $this->getDesconto();
                    $descontoFormatado = $this->getDesconto('F');
                    $this->setDesconto($descontoFormatado);
                    $this->calculaImpostos();
                   // $this->setPedidoVenda();
                    $this->m_pesq = '';
                    $this->setDesconto($desconto);
                    $this->desenhaCadastroPedido();
                                
                }
                break; 
            case 'cadastrarPed': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {                    
                    $this->m_letra = '';
                    $this->m_letra_old = '';
                    $this->mostraPedido('');
                }
                break;
            case 'recalcular':
                $this->calculaImpostos(true);
                $desc = $this->getDesconto('B');
                $frete = $this->getFrete('B');
                $desp = $this->getDespAcessorias('B');
                $this->setDesconto($desc);
                $this->setFrete($frete);
                $this->setDespAcessorias($desp);
                $this->desenhaCadastroPedido();
                break;
            case 'duplicaPedido':
                {   
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaPedido();
                    $this->setId($idGerado);
                    $this->atualizarField('PEDIDO', $idGerado);                   
                    $this->duplicaPedidoItem($idGerado, $idAntigo);
                    $this->setPedidoVenda();  
                    $this->m_submenu = 'alterar';                 
                    $this->desenhaCadastroPedido();
                }                
                break;
            default:
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function updatePedido(){
                    
        // $this->setCustoTotal($this->select_totais('CUSTO'),true);
        // $this->setDespesaTotal($this->select_totais('DESPESAS'),true);
        // $this->setLucroBruto($this->select_totais('LUCROBRUTO'),true);
        // $this->setMargemLiquida($this->select_totais('MARGEMLIQUIDA'),true);
        // $this->setMarkup($this->select_totais('MARKUP'),true);
        
        // $this->setFrete($this->select_totais('FRETE'),true);
        // $this->setDesconto($this->select_totais('DESCONTO'),true);
        // $this->setDespAcessorias($this->select_totais('DESPACESSORIAS'),true);
        
        // $total = $this->select_totalPedido() +
        //     $this->getFrete() + 
        //     $this->getDespAcessorias();
        

        // $this->setTotal($total);
        
        $this->setSituacao(5);
        $this->setPedido($this->getId());
        $this->alteraPedidoTotalTelhas();
        
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg=NULL, $ItemFoiAdicionado=NULL) {

        $msg = $mensagem; 
    //    if (($this->m_submenu != 'cadastrar')and($this->m_submenu != 'agruparPedidos') and ($this->getId() > 0)):
    //        $mensagem = $this->calculaImpostos();
    //    endif;
       
        $totalDdescontoItem = $this->select_totais('DESCONTO'); // Totais desconto pedido_item
        $this->setDesconto($totalDdescontoItem);
       
        if (strlen($mensagem) == 0) {
         $mensagem = $msg;
        }
        $this->smarty->assign('baseIcms', $this->baseIcms);
        $this->smarty->assign('valorIcms', $this->valorIcms);
        $this->smarty->assign('basePis', $this->basePis);
        $this->smarty->assign('valorPis', $this->valorPis);
        $this->smarty->assign('baseCofins', $this->baseCofins);
        $this->smarty->assign('valorCofins', $this->valorCofins);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('letra_old', $this->m_letra_old);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'pedido_venda_telhas_novo');
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');

        
        //verifica o status do pedido para poder altera-lo 
        $this->smarty->assign('permiteAlterarVenda', false );
        if($this->getSituacao() == 6){
            $response = $this->validaAlterarPedido();
            if($response['tipoMsg'] == 'sucesso'){
                $permiteAlterarVenda = $this->verificaDireitoUsuario('PEDPERMITEALTERARPEDIDOS', 'S', 'N');
                $this->smarty->assign('permiteAlterarVenda', $permiteAlterarVenda);
                $this->smarty->assign('totalOriginal', $this->totalOriginal == '' ? $this->getTotal('F') : ''); 
            }
        }

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nrItem', $this->getId());
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
            $consulta = new c_banco();
            $consulta->setTab("FIN_CLIENTE");
            $cepCliente = $consulta->getField("CEP", "CLIENTE = '".$this->getCliente()."'");
            $consulta->close_connection();

            $consulta = new c_banco();
            $consulta->setTab("FIN_CLIENTE");
            $codMunicipio = $consulta->getField("CODMUNICIPIO", "CLIENTE = '".$this->getCliente()."'");
            $consulta->close_connection();
            $this->smarty->assign('cep', $cepCliente);
            $this->smarty->assign('codMunicipio', $codMunicipio);

            $this->smarty->assign('dadosCliente', 'true'); 
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('esconderbtn','N'); 
        if (($this->getSituacao() == 6)or($this->getSituacao() == 9)){
            $this->smarty->assign('esconderbtn','S');    
        }
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('entregador', $this->getEntregador());
        $this->smarty->assign('usrFatura', $this->getUsrFatura());
        $this->smarty->assign('natop', $this->getIdNatop());
        $this->smarty->assign('tabPreco', $this->getTabPreco());
        $this->smarty->assign('entradaTabPreco', $this->getEntradaCondPg('F'));
        $this->smarty->assign('taxaFin', $this->getTaxaFin('F'));
        $this->smarty->assign('condPg', $this->getCondPg());
        $this->smarty->assign('entradaCondPg', $this->getEntradaCondPg('F'));
        $this->smarty->assign('vencimento1', $this->getVencimento1('F'));
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('moeda', $this->getMoeda());
        $this->smarty->assign('contaDeposito', $this->getContaDeposito());
        $this->smarty->assign('especie', $this->getEspecie());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('horaEmissao', $this->getHoraEmissao('F'));
        $this->smarty->assign('taxaEntrega', $this->getTaxaEntrega('F'));
        $this->smarty->assign('totalRecebido', $this->getTotalRecebido('F'));
        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega());
        $this->smarty->assign('genero', $this->getGenero());
        $this->smarty->assign('filial', $this->getCentroCusto());
        $this->smarty->assign('tipoEntrega', $this->getTipoEntrega());
        $this->smarty->assign('tabelaPreco', $this->getTabelaPreco());
        $this->smarty->assign('ipi', $this->getIpi('F'));
        $this->smarty->assign('comprador', $this->getComprador());
        $this->smarty->assign('transportadora', $this->getTransportadora());
        $this->smarty->assign('tabelaVenda', $this->getTabelaVenda());
        $this->smarty->assign('usrPedido', $this->getUsrPedido());
        $this->smarty->assign('dtUltimoPedidoCliente', $this->getDtUltimoPedidoCliente('F'));
        $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
        $this->smarty->assign('perDesconto', $this->getPerDesconto('F'));
        $this->smarty->assign('descontoNf', $this->getDesconto('F'));
        $this->smarty->assign('totalProdutos', $this->getTotalProdutos('F'));
        $this->smarty->assign('frete', $this->getFrete('F'));
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('despAcessorias', $this->getDespAcessorias('F'));
        
        // campos de pesquisa de produtos
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
        $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
        if ($ItemFoiAdicionado != null) {
            if ($ItemFoiAdicionado = "S")  {
                $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
                $this->smarty->assign('pesLocalizacao',"");            
            } else {
                $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
                $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
            }
        }
        $validarDescontoGeral = 'N';
        if ($this->getId()!=''):
            {
                
                // $total = $this->select_totalPedido() +
                // $this->getFrete() + 
                // $this->getDespAcessorias() - $this->getDesconto('B');

                $totalPedido = $this->select_totalPedido();
                $fretePedido = $this->getFrete();
                $despAcessoriaPedido = $this->getDespAcessorias();
                $descontoPedido = $this->getDesconto();
                $total = $totalPedido + $fretePedido + $despAcessoriaPedido - $descontoPedido;
                
                $this->smarty->assign('totalPedido', $total);
                $perDesconto = (($this->getDesconto('F') / ($total + $this->getDesconto('F'))) * 100);
                $this->smarty->assign('perDesconto', $perDesconto);
                
                
                $banco = new c_banco();
                $sql = "SELECT DESCONTOMAXIMO FROM FAT_PARAMETRO ";
                $sql.= "WHERE (FILIAL=".$this->m_empresacentrocusto.")";
                $resul = $banco->exec_sql($sql);
                $desconto = $resul[0]['DESCONTOMAXIMO'];
                
                if ($desconto > 0) {
                    if ($perDesconto > $desconto ){
                        $validarDescontoGeral = 'S';
                    }
                    $permiteAprovarDesconto = $this->verificaDireitoUsuario('PEDPERMITEAPROVARDESCONTO', 'S', 'N');
                    if ($permiteAprovarDesconto == false) {
                        $this->setUsrAprovacao(null);
                    }
                }

                //parcelas lanc  
                if($this->getSituacao() == 9 ){ //SITUACAO 9 = PEDIDO BAIXADO
                    $id = $this->getId();
                    $lancObj = new c_lancamento();
                    $parcelas = $lancObj->select_lancamento_doc('PED', $id);

                    if(!empty($parcelas)){
                        for($i=0; $i<count($parcelas); $i++){
                            $con = new c_banco();
                            $con->setTab("FIN_CONTA");
                            $banco = $con->getField("NOMEINTERNO", "CONTA=".$parcelas[$i]['CONTA']);
                            $con->close_connection();
                            $parcelas[$i]['CONTA'] = $banco;

                            $con = new c_banco();
                            $con->setTab("AMB_DDM");
                            $tipoDocto = $con->getField("PADRAO", "TIPO='".$parcelas[$i]['TIPODOCTO']."' AND (alias='FIN_MENU') and (campo='TipoDoctoPgto')");
                            $con->close_connection();
                            $parcelas[$i]['TIPODOCTO'] = $tipoDocto;

                            $con = new c_banco();
                            $con->setTab("AMB_DDM");
                            $sitPgto = $con->getField("PADRAO", "TIPO='".$parcelas[$i]['SITPGTO']."' AND (alias='FIN_MENU') and (campo='SituacaoPgto')");
                            $con->close_connection();
                            $parcelas[$i]['SITPGTO'] = $sitPgto;;
                        }
                    }        
                   
                    $this->smarty->assign('parcelas', $parcelas);
                }
                
                
            }
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;
        $this->smarty->assign('validarDescontoGeral', $validarDescontoGeral);
        
        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getSituacao());
        $situacao = ($this->getSituacao()); 
        if ($situacao == ''){
            $situacao = '0';
        }
        $this->smarty->assign('situacao', $situacao);

        $controlarStatusTela = $this->verificaDireitoUsuario('PEDCONTROLARSTATUSTELA', 'S', 'N');
        $this->smarty->assign('controlarStatusTela', $controlarStatusTela);        
        
        // COMBOBOX NAT OPERAÇÃO
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where tipo='S'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $natop_ids[0] = '';
        $natop_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $natop_ids[$i + 1] = $result[$i]['ID'];
            $natop_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natop_ids', $natop_ids);
        $this->smarty->assign('natop_names', $natop_names);
        $this->smarty->assign('natop_id', $this->getIdNatop());

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
//            if ($this->getCondPg()==$result[$i]['ID']):
//                $descCondPgto = $result[$i +1]['DESCRICAO'];
//            endif;
            $condPgto_ids[$i+1] = $result[$i]['ID'];
            $condPgto_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());

        
        // COMBOBOX GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_parPesq[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_parPesq[1]);
        

        //PROMOÇÃO
        $this->smarty->assign('promocoes', $this->m_parPesq[2]);

        if($this->m_pesquisa_prod_vazio == true){
            $lancPesq[0] = [
                'CODFABRICANTE' => "", 'TIPOPROMOCAO' => "", 'CODIGO' => "SEM_CODIGO", 'DESCRICAO'=> "",
                'GRUPO'=> "", 'UNIDADE' => "SEM", 'UNIFRACIONADA' => "", 'STATUS' => "",
                'QUANTIDADE'=> "", 'QUANTLIMITE'=> "", 'VENDA' => "", 'PROMOCAO'=>"",
                'CUSTOCOMPRA' => "", 'PRECOMINIMO'=> "",  'ORIGEM' => "",
                'NCM'=> "",  'CEST'=> "", 'TRIBICMS' => "", 'ALIQPISMONOFASICA'=> '',
                'ALIQCOFINSMONOFASICA' => '', 'ANP'=> '', 'ALIQIPI' => '', 'CODIGONOTA' =>''
            ];
            $permiteDigitarCodigo = true;
            $this->smarty->assign('lancPesq', $lancPesq);
            $this->smarty->assign('pesquisa_prod_vazio', $this->m_pesquisa_prod_vazio);
            
        }           
        else{
            $permiteDigitarCodigo = $this->verificaDireitoUsuario('PEDPERMITEDIGITARCODIGO', 'S', 'N');
        }
        
        if (!empty($this->m_pesq)){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
            
            $objProdutoQtde = new c_produto_estoque();
            // if (($ItemFoiAdicionado != "S") and ($this->m_pesq !='|||')) {
            if($this->m_pesquisa_prod_vazio != true){
                if ($this->m_pesq !='|||') {
                    $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, null, $consultaEstoque );
                } else {
                    $lancPesq = $objProdutoQtde->null;  
                }
//            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
                $this->smarty->assign('lancPesq', $lancPesq);
            }
        }
        $id = $this->getId();
        
        //array com os itens adicionados
        $itens = array();

        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $tipovalidacao = $parametros->getField("TIPOVALIDACAO", "FILIAL=".$this->m_empresacentrocusto);
        $percdescmaximo = $parametros->getField("PERCDESCMAXIMO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();   


        if (!empty($id)){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
            if (count($lancItens) > 0) {
                for ($i = 0; $i < count($lancItens); $i++) {
                        array_push($itens, $lancItens[$i]['ITEMESTOQUE']); 
                }           
            } 
                
            if ( $tipovalidacao != 'N') {
                //incremento quando o produto esta abaixo do preco de venda
                $this->id_prod_preco_min = "";          
                for($i=0;$i < count($lancItens); $i++){  
                    if ($tipovalidacao = "M") {
                        if($lancItens[$i]['PRECOMINIMO'] > $lancItens[$i]['UNITARIO'])                                
                            $this->id_prod_preco_min = $this->id_prod_preco_min."|".$lancItens[$i]['ITEMFABRICANTE'];
                    } else if ($tipovalidacao = "A") {
                        if ($percdescmaximo > 0) { //percentual máximo de desconto
                            $percItem = $lancItens[0]['UNITARIO'] / $lancItens[0]['PRECOMINIMO'];
                            if ($percItem < 1) {
                                $percItem = (1 - $percItem) * 100;
                            }
                            if($percdescmaximo < $percItem )                                
                                $this->id_prod_preco_min = $this->id_prod_preco_min."|".$lancItens[$i]['ITEMFABRICANTE'];    
                        }                    
                    } // ($tipovalidacao = "A")                                     
                }
                $this->smarty->assign('id_prod_preco_min', $this->id_prod_preco_min);
            } else {
                $this->smarty->assign('id_prod_preco_min', '');
            }
        } else {
            $this->smarty->assign('id_prod_preco_min', '');
        }

        //**** Sequencia pesquisa Modal ****
        $this->copiarEcolar();
        
        $str=implode("|",array_unique($itens));
        $this->smarty->assign('str', $str); 

        //QUANTIDADE
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

        // ########## CENTROCUSTO ENTREGA ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $centroCustoEntrega_ids[0] = '';
        $centroCustoEntrega_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $centroCustoEntrega_ids[$i + 1] = $result[$i]['ID'];
            $centroCustoEntrega_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCustoEntrega_ids',   $centroCustoEntrega_ids);
        $this->smarty->assign('centroCustoEntrega_names', $centroCustoEntrega_names);  
        $this->smarty->assign('centroCustoEntrega_id', $this->getCentroCustoEntrega());
        
        /*
        // BUSCA PARAMETROS CENTRO CUSTO
        $cCusto = $this->getCentroCusto();
        if ($cCusto == null) { 
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $cCusto = $parametros->getField("CENTROCUSTO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();
        }    

        $this->smarty->assign('centroCusto_id', $cCusto);*/
        
        // COMBOBOX MOTIVO
        // $consulta = new c_banco();
        // $sql = "select motivo, descricao from fat_motivo ";
        // $consulta->exec_sql($sql);
        // $consulta->close_connection();
        // $result = $consulta->resultado;
        // $motivo_ids[0] = '';
        // $motivo_names[0] = 'Selecione Venda Perdida';
        // for ($i = 0; $i < count($result); $i++) {
        //     $motivo_ids[$i + 1] = $result[$i]['MOTIVO'];
        //     $motivo_names[$i + 1] = $result[$i]['MOTIVO'] . " - " . $result[$i]['DESCRICAO'];
        // }
        // $this->smarty->assign('motivo_ids', $motivo_ids);
        // $this->smarty->assign('motivo_names', $motivo_names);
        // $this->smarty->assign('motivo_id', null);
        
        // if ($this->verificaDireitoPrograma('FatVendaPerdida', 'S')) {
        //   $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        // } else {
        //     $exibirmotivo = '';
        //     $this->exibirmotivo = $exibirmotivo;
        //     $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        // }

        // COMBOBOX VENDEDOR
        $consulta = new c_banco();
        //$sql = "SELECT USUARIO, NOME FROM AMB_USUARIO WHERE TIPO='V'";
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $usrfatura_ids[$i + 1] = $result[$i]['USUARIO'];
            $usrfatura_names[$i] = $result[$i]['NOME'];
        }
        
        $this->smarty->assign('usrfatura_ids', $usrfatura_ids);
        $this->smarty->assign('usrfatura_names', $usrfatura_names);
        if ($validarDescontoGeral = 'S') {
            $this->smarty->assign('usrautoriza_ids', $usrfatura_ids);
            $this->smarty->assign('usrautoriza_names', $usrfatura_names);        
        }

        $permiteAlterarCusto = $this->verificaDireitoUsuario('PEDPERMITEALTERARCUSTO', 'S', 'N');
        $this->smarty->assign('permiteAlterarCusto', $permiteAlterarCusto);

        $permiteAlterarValor = $this->verificaDireitoUsuario('PEDPERMITEALTERARVALOR', 'S', 'N');
        $this->smarty->assign('permiteAlterarValor', $permiteAlterarValor);

        $permiteAlterarVendedor = $this->verificaDireitoUsuario('PEDPERMITEALTERARVENDEDOR', 'S', 'N');
        $this->smarty->assign('permiteAlterarVendedor', $permiteAlterarVendedor);

        $permiteGerarBonus = $this->verificaDireitoUsuario('PEDPERMITEGERARBONUS', 'S', 'N');
        $this->smarty->assign('permiteGerarBonus', $permiteGerarBonus);

        $this->smarty->assign('permiteDigitarCodigo', $permiteDigitarCodigo);

        // BOOLEAN ##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $boolean_ids[$i] = $result[$i]['ID'];
                $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        
        if ($this->getUsrFatura() > 0 ) {
            $this->smarty->assign('usrfatura', $this->getUsrFatura());
        } else {
            $this->smarty->assign('usrfatura', $this->m_userid);
        }
        
        
        $credito = $this->getCredito();
        if ($credito > 0 ){
            $this->smarty->assign('credito', $this->getCredito());
            $this->smarty->assign('exibircredito', 'S');
        } else {
            $this->smarty->assign('exibircredito', 'N');
        }
    
        $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
        
        $this->smarty->assign('sistema', ADMSistema);
        $this->smarty->assign('from', 'telhasNovo');
    
        $this->smarty->display('pedido_venda_telhas_cadastro_novo.tpl');
    }

//fim desenhaCadgrupo

//---------------------------------------------------------------
//---------------------------------------------------------------
function comboSql($sql, $par, &$id, &$ids, &$names) {
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado ?? [];
    
    for ($i = 0; $i < count($result); $i++) {
        $ids[$i] = $result[$i]['ID'];
        $names[$i] = $result[$i]['DESCRICAO'];
    }

    
    $param = explode(",", $par);
    $i=0;
    $id[$i] = "0";
    while ($param[$i] != '') {
        $id[$i] = $param[$i];
        $i++;
    }    
}


function pesquisar_item_lista($pesq, $desc){
    $listaProd = NULL;
    $msg_modal = '';
    switch($pesq){
        case 1: // codigo
            for($i=0; $i < count($desc); $i++){
                $r = pesquisaProdCod($desc[$i]);
                if($r[0] != ""){
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal ="Código: ". $desc[$i]. " | " . $msg_modal;
                }
                    
            }     
        break;
        case 2: //[2] cod e qtde  
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);     
                $item = explode("", $desc[$i]);
                $r = pesquisaProdCod($item[0]); 
                /*
                $m_id = strstr($desc[$i], ' ', true);
                $qtde = strrchr($desc[$i],' ');
                $qtde = trim($qtde);
                $r = pesquisaProdCod($m_id); 
                */
                if($r[0] != ""){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'],
                        'VENDA'     => $r[0]['VENDA'],                                
                        'QUANT'     => $item[5]
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal ="Código: ". $desc[$i]. " | " . $msg_modal;
                }
                
            }     
        break;
        case 3: //[3] cod e desc 
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $item = explode("", $desc[$i]);
                $r = pesquisaProdCod($item[0]); 
                /*
                $m_id = strstr($desc[$i], ' ', true);
                $r = pesquisaProdCod($m_id);*/

                if($r[0] != ""){
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal ="Código: ". $desc[$i]. " | " . $msg_modal;
                }
                    
            }     
        break;
        case 4: //[4] cód, qtde e desc 
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);     
                $item = explode("", $desc[$i]);
                $r = pesquisaProdCod($item[0]); 
                /*
                $m_id = strstr($desc[$i], ' ', true);
                $aux = strstr($desc[$i], ' ');
                $aux = trim($aux);
                $qtde = strstr($aux, ' ', true);
                $qtde = trim($qtde);
                $r = pesquisaProdCod($m_id);
                */
                if($r[0] != ""){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $item[4]
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal ="Código: ". $desc[$i]. " | " . $msg_modal;
                }
            }
        break;    
        case 5: //[5]cód, desc e qtde
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);     
                $m_id = strstr($desc[$i], ' ', true);
                $qtde = strrchr($desc[$i],' ');
                $qtde = trim($qtde);
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal ="Código: ". $desc[$i]. " | " . $msg_modal;
                }
                
            }     
        break;
        case 6: // Qtde e Codigo
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $m_id = strstr($desc[$i], ' ');
                $m_id = trim($m_id);
                $qtde = strstr($desc[$i], ' ', true); 
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'],
                        'VENDA'     => $r[0]['VENDA'],                                  
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }    
                }else{
                    $msg_modal ="Código: ". $m_id. " | " . $msg_modal;
                }                    
            }
        break;                    
        case 7: // Qtde e Descricao
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]); 
                $qtde = strstr($desc[$i], ' ', true);                        
                $desc_linha = strstr($desc[$i], ' ');
                $desc_linha = trim($desc_linha);
                if($listaProd[0] != ""){
                    $pesq_aux =  pesquisaProdDesc($desc_linha);
                    if($pesq_aux[0] != ''){
                        $pesq_aux[0] = [
                            'CODIGO'    => $pesq_aux[0]['CODIGO'],
                            'DESCRICAO' => $pesq_aux[0]['DESCRICAO'],
                            'GRUPO'     => $pesq_aux[0]['GRUPO'],
                            'UNIDADE'   => $pesq_aux[0]['UNIDADE'], 
                            'VENDA'     => $r[0]['VENDA'],                                 
                            'QUANT'     => $qtde
                        ];
                        for($k=0; $k < count($pesq_aux); $k++){
                            array_push($listaProd, $pesq_aux[$k]);
                        } 
                    }else{
                        $msg_modal = $msg_modal . " | " .$desc[$i];
                    }                       
                    
                }else{
                    $listaProd = pesquisaProdDesc($desc_linha);
                    if($listaProd[0] != ""){
                        $listaProd[0] = [
                            'CODIGO'    => $listaProd[0]['CODIGO'],
                            'DESCRICAO' => $listaProd[0]['DESCRICAO'],
                            'GRUPO'     => $listaProd[0]['GRUPO'],
                            'UNIDADE'   => $listaProd[0]['UNIDADE'],
                            'VENDA'     => $r[0]['VENDA'],                                  
                            'QUANT'     => $qtde
                        ];
                    }else{
                        $msg_modal =$msg_modal . " | " .$desc[$i];
                    }
                }
            }     
        break;
        case 8: // Qtde, Descricao e código
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $qtde = strstr($desc[$i], ' ', true);
                $m_id = strrchr($desc[$i], ' ');
                $m_id = trim($m_id);
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal = $msg_modal . " | " .$desc[$i];
                }
            }     
            break;
        
        case 9: // Qtde, código e Descricao
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $qtde = strstr($desc[$i], ' ', true);
                $aux = strstr($desc[$i], ' ');
                $aux = trim($aux);
                $m_id = strstr($aux, ' ', true);;
                $m_id = trim($m_id);
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal =$msg_modal . " | " .$desc[$i];
                }
            }     
        break;
        case 10: // Descricao 
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $desc[$i] = trim($desc[$i]);
                $r = pesquisaProdDesc($desc[$i]);
                if($r[0] != ''){
                    if($listaProd[0] != ""){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal =$msg_modal . " | " .$desc[$i];
                }
                
            }     
        break;
        case 11: // Descrição e Código
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $m_id = strrchr($desc[$i], ' ');
                $m_id = trim($m_id);
                $r = pesquisaProdCod($m_id);  
                if($r != ''){                      
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }   
                }else{
                    $msg_modal = $msg_modal . " | " .$desc[$i];
                }                     
            }   
        break;
        case 12: // Descricao e quantidade
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $qtde = strrchr($desc[$i],' ');
                $qtde = trim($qtde);
                $desc_formatada = str_replace($qtde, "", $desc[$i]);
                $desc_formatada = trim($desc_formatada);
                $r = pesquisaProdDesc($desc_formatada);
                if($r[0] != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ""){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal = $msg_modal . " | " .$desc[$i];
                }
                
            }     
        break;
        case 13:  // Descrição, Código e Quantidade
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $qtde = strrchr($desc[$i],' ');
                $qtde = trim($qtde);
                $desc_formatada = str_replace($qtde, "", $desc[$i]);
                $desc_formatada = trim($desc_formatada);
                $m_id = strrchr($desc_formatada, ' ');
                $m_id = trim($m_id);
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    } 
                }else{
                    $msg_modal = $msg_modal . " | " .$desc[$i];
                }                             
            }     
        break;
        case 14: // Descrição Quantidade e Código
            for($i=0; $i < count($desc); $i++){
                $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                $m_id = strrchr($desc[$i], ' ');
                $m_id = trim($m_id);
                $desc_formatada = str_replace($m_id, "", $desc[$i]);
                $desc_formatada = trim($desc_formatada);
                $qtde = strrchr($desc_formatada, ' ');;
                $qtde = trim($qtde);
                
                $r = pesquisaProdCod($m_id);
                if($r != ''){
                    $r[0] = [
                        'CODIGO'    => $r[0]['CODIGO'],
                        'DESCRICAO' => $r[0]['DESCRICAO'],
                        'GRUPO'     => $r[0]['GRUPO'],
                        'UNIDADE'   => $r[0]['UNIDADE'], 
                        'VENDA'     => $r[0]['VENDA'],                                 
                        'QUANT'     => $qtde
                    ];
                    if($listaProd[0] != ''){
                        for($k=0; $k < count($r); $k++){
                            array_push($listaProd, $r[$k]);
                        }    
                    }else{
                        $listaProd[0] = $r[0];
                    }
                }else{
                    $msg_modal = $msg_modal . " | " .$desc[$i];
                }  
            }     
        break;
        default:
    }

    return $listaProd; 
    
}

function pesquisaProdCod($desc){
    $consulta = new c_banco();
    $sql = "select * from EST_PRODUTO WHERE (CODIGO = '".$desc."') OR ";
    $sql .= "(CODFABRICANTE = '".$desc."');";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;

    return $result;
}

function copiarEcolar(){
    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $pesq_cc_ids[0] = 1;
    $pesq_cc_names[0] = 'Código';
    $pesq_cc_ids[1] = 2;
    $pesq_cc_names[1] = 'Código e Quantidade';
    $pesq_cc_ids[2] = 3;
    $pesq_cc_names[2] = 'Código e Descrição';
    $pesq_cc_ids[3] = 4;
    /*
    $pesq_cc_names[3] = 'Código, Quantidade e Descrição';
    $pesq_cc_ids[4] = 5;
    $pesq_cc_names[4] = 'Código, Descrição e Quantidade';
    $pesq_cc_ids[5] = 6;
    $pesq_cc_names[5] = 'Quantidade e Código';
    $pesq_cc_ids[6] = 7;
    $pesq_cc_names[6] = 'Quantidade e Descrição';
    $pesq_cc_ids[7] = 8;
    $pesq_cc_names[7] = 'Quantidade, Descrição e Código';
    $pesq_cc_ids[8] = 9;
    $pesq_cc_names[8] = 'Quantidade, Código e Descrição';
    $pesq_cc_ids[9] = 10;
    $pesq_cc_names[9] = 'Descrição';        
    $pesq_cc_ids[10] = 11;
    $pesq_cc_names[10] = 'Descrição e Código';
    $pesq_cc_ids[11] = 12;
    $pesq_cc_names[11] = 'Descrição e Quantidade';
    $pesq_cc_ids[12] = 13;
    $pesq_cc_names[12] = 'Descrição, Código e Quantidade';
    $pesq_cc_ids[13] = 14;
    $pesq_cc_names[13] = 'Descrição Quantidade e Código';

    */
    
    $this->smarty->assign('pesq_cc_ids',   $pesq_cc_ids);
    $this->smarty->assign('pesq_cc_names', $pesq_cc_names);
    
    $pesq = isset($parmPost['pesq']) ? $parmPost['pesq_cc'] : '';
    $desc = preg_split('/\r\n|\r|\n/', $parmPost['desc_cc'] );
    
    $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");
    if($_SERVER["HTTP_AJAX_REQUEST"] == "true"):
        $ajax_request = 'true';
        $this->smarty->assign('ajax', $ajax_request);   
      
        //$listaProd = pesquisar_item_lista($pesq, $desc);    
        $listaProd = NULL;
        $msg_cc_modal = '';
        switch($pesq){
            case 1: // codigo
                for($i=0; $i < count($desc); $i++){
                    $r = $this->pesquisaProdCod($desc[$i]);
                    if($r[0] != ""){
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }                        
                }     
            break;
            case 2: //[2] cod e qtde  
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);     
                    $m_id = strstr($desc[$i], ' ', true);
                    $qtde = strrchr($desc[$i],' ');
                    $qtde = trim($qtde);
                    $r = $this->pesquisaProdCod($m_id);
                    if($r[0] != ""){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],
                            'VENDA'   => $r[0]['VENDA'],
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                    
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                    
                }     
            break;
            case 3: //[3] cod e desc 
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", ' ', strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $m_id = strstr($desc[$i], " ", true);
                    $r = $this->pesquisaProdCod($m_id);

                    if($r[0] != ""){
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                       
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                        
                }     
            break;
            case 4: //[4] cód, qtde e desc 
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);     
                    $m_id = strstr($desc[$i], ' ', true);
                    $aux = strstr($desc[$i], ' ');
                    $aux = trim($aux);
                    $qtde = strstr($aux, ' ', true);
                    $qtde = trim($qtde);
                    $r = pesquisaProdCod($m_id);
                    if($r[0] != ""){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],
                            'VENDA'   => $r[0]['VENDA'],
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                         
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                }
            break;    
            case 5: //[5]cód, desc e qtde
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);   
                    $m_id = strstr($desc[$i], ' ', true);
                    $qtde = strrchr($desc[$i],' ');
                    $qtde = trim($qtde);
                    $r = pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],
                            'VENDA'   => $r[0]['VENDA'],  
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                           
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                    
                }     
            break;
            case 6: // Qtde e Codigo
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $m_id = strstr($desc[$i], ' ');
                    $m_id = trim($m_id);
                    $qtde = strstr($desc[$i], ' ', true); 
                    $r = $this->pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],
                            'VENDA'   => $r[0]['VENDA'],    
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }    
                    }else{                                                                                              
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }                    
                }
            break;                    
            case 7: // Qtde e Descricao
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]); 
                    $qtde = strstr($desc[$i], ' ', true);                        
                    $desc_linha = strstr($desc[$i], ' ');
                    $desc_linha = trim($desc_linha);
                    if($listaProd[0] != ""){
                        $pesq_aux =  $this->pesquisaProdDesc($desc_linha);
                        if($pesq_aux[0] != ''){
                            $pesq_aux[0] = [
                                'CODIGO'    => $pesq_aux[0]['CODIGO'],
                                'DESCRICAO' => $pesq_aux[0]['DESCRICAO'],
                                'GRUPO'     => $pesq_aux[0]['GRUPO'],
                                'UNIDADE'   => $pesq_aux[0]['UNIDADE'],    
                                'VENDA'   => $r[0]['VENDA'],            
                                'QUANT'     => $qtde
                            ];
                            for($k=0; $k < count($pesq_aux); $k++){
                                array_push($listaProd, $pesq_aux[$k]);
                            } 
                        }else{                                                                                                  
                            if ($msg_cc_modal != ''){
                                $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                            } else {
                                $msg_cc_modal ="Código: ". $desc[$i];                        
                            }
                        }
                    }else{
                        $listaProd = $this->pesquisaProdDesc($desc_linha);
                        if($listaProd[0] != ""){
                            $listaProd[0] = [
                                'CODIGO'    => $listaProd[0]['CODIGO'],
                                'DESCRICAO' => $listaProd[0]['DESCRICAO'],
                                'GRUPO'     => $listaProd[0]['GRUPO'],
                                'UNIDADE'   => $listaProd[0]['UNIDADE'],
                                'VENDA'   => $r[0]['VENDA'],              
                                'QUANT'     => $qtde
                            ];
                        }else{                                                                                                  
                            if ($msg_cc_modal != ''){
                                $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                            } else {
                                $msg_cc_modal ="Código: ". $desc[$i];                        
                            }
                        }
                    }
                }     
            break;
            case 8: // Qtde, Descricao e código
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $qtde = strstr($desc[$i], ' ', true);
                    $m_id = strrchr($desc[$i], ' ');
                    $m_id = trim($m_id);
                    $r = $this->pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],
                            'VENDA'   => $r[0]['VENDA'],    
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                                              
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                }     
                break;
            
            case 9: // Qtde, código e Descricao
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $qtde = strstr($desc[$i], ' ', true);
                    $aux = strstr($desc[$i], ' ');
                    $aux = trim($aux);
                    $m_id = strstr($aux, ' ', true);;
                    $m_id = trim($m_id);
                    $r = $this->pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'], 
                            'VENDA'   => $r[0]['VENDA'],   
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                                              
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                }     
            break;
            case 10: // Descricao 
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $desc[$i] = trim($desc[$i]);
                    $r = $this->pesquisaProdDesc($desc[$i]);
                    if($r[0] != ''){
                        if($listaProd[0] != ""){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                          
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                    
                }     
            break;
            case 11: // Descrição e Código
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $m_id = strrchr($desc[$i], ' ');
                    $m_id = trim($m_id);
                    $r = $this->pesquisaProdCod($m_id);  
                    if($r != ''){                      
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }   
                    }else{                                                                          
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }                     
                }   
            break;
            case 12: // Descricao e quantidade
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $qtde = strrchr($desc[$i],' ');
                    $qtde = trim($qtde);
                    $desc_formatada = str_replace($qtde, "", $desc[$i]);
                    $desc_formatada = trim($desc_formatada);
                    $r = $this->pesquisaProdDesc($desc_formatada);
                    if($r[0] != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'], 
                            'VENDA'   => $r[0]['VENDA'],     
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ""){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                          
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }
                    
                }     
            break;
            case 13:  // Descrição, Código e Quantidade
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $qtde = strrchr($desc[$i],' ');
                    $qtde = trim($qtde);
                    $desc_formatada = str_replace($qtde, "", $desc[$i]);
                    $desc_formatada = trim($desc_formatada);
                    $m_id = strrchr($desc_formatada, ' ');
                    $m_id = trim($m_id);
                    $r = $this->pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'],  
                            'VENDA'   => $r[0]['VENDA'],      
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        } 
                    }else{                                                                     
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }                             
                }     
            break;
            case 14: // Descrição Quantidade e Código
                for($i=0; $i < count($desc); $i++){
                    $desc[$i] = preg_replace("[^a-zA-Z0-9_]", "", strtr($desc[$i], "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
                    $desc[$i] = preg_replace('/\\s\\s+/', ' ', $desc[$i]);
                    $m_id = strrchr($desc[$i], ' ');
                    $m_id = trim($m_id);
                    $desc_formatada = str_replace($m_id, "", $desc[$i]);
                    $desc_formatada = trim($desc_formatada);
                    $qtde = strrchr($desc_formatada, ' ');;
                    $qtde = trim($qtde);
                    
                    $r = $this->pesquisaProdCod($m_id);
                    if($r != ''){
                        $r[0] = [
                            'CODIGO'    => $r[0]['CODIGO'],
                            'DESCRICAO' => $r[0]['DESCRICAO'],
                            'GRUPO'     => $r[0]['GRUPO'],
                            'UNIDADE'   => $r[0]['UNIDADE'], 
                            'VENDA'   => $r[0]['VENDA'],     
                            'QUANT'     => $qtde
                        ];
                        if($listaProd[0] != ''){
                            for($k=0; $k < count($r); $k++){
                                array_push($listaProd, $r[$k]);
                            }    
                        }else{
                            $listaProd[0] = $r[0];
                        }
                    }else{                                                                                              
                        if ($msg_cc_modal != ''){
                            $msg_cc_modal ="Código: ". $desc[$i]. " | " . $msg_cc_modal;
                        } else {
                            $msg_cc_modal ="Código: ". $desc[$i];                        
                        }
                    }  
                }     
            break;
            default:
        } 

        $this->smarty->assign('msg_cc_modal', $msg_cc_modal); 
        $this->smarty->assign('lancCCModal', $listaProd); 

    else:
        $ajax_request = 'false';
        $this->smarty->assign('ajax', $ajax_request);

        $this->smarty->assign('lancCCModal', NULL); 
    endif;
    
}

//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL, $tipoMsg=NULL) {

        //$cliente = $this->getCliente();
        $cliente = '';
        //$this->m_letra = "||".$cliente."||0|1|2|3|4";
        $this->m_letra_old = $this->m_letra;
        if ($this->m_letra !=''):
            $lanc = $this->select_pedidoVenda_letra($this->m_letra, $this->m_motivoSelecionados);
            
            $par = explode("|", $this->m_letra);
            $usuario = explode(",", $par[5]);

            if ((count($usuario) == 1)and($usuario[0] != '')) {

                $data = "";
                $labels = "";

                $result = $this->select_pedidoVenda_usuario($usuario[0], $par[0], $par[1]);
            
                $bck = ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"];
                for ($i = 0; $i < count($result); $i++) {
                    if ($i > 0 ){
                        $dados .= ",";
                        $labels .= ",";
                        $bckgroundColor .= ",";  
                    }
                    $dados .= str_replace(',', '', number_format($result[$i]['TOTAL'],2));

                    //$bckgroundColor .= " '" .bck[$i]. "' ";
                    $bckgroundColor .= " '" .$bck[$i]. "' ";
                    $labels .= "'".$result[$i]['PADRAO']."'";
                }
                $this->smarty->assign('bckgroundColor', $bckgroundColor); 
        
                $this->smarty->assign('dados', $dados); 
                
                $this->smarty->assign('labels', $labels); 

            }
        endif;
        
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[1]);

        // COMBOBOX SITUACAO
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') ";
        
        if (ADMSistema != 'PECAS') {
            $sql .= " AND ((TIPO = 0) or (TIPO = 5) or (TIPO = 6) or (TIPO = 7) or (TIPO = 9) or (TIPO = 10) or (TIPO = 11) or (TIPO = 12))";
        }        
        
    // COMBOBOX SITUACAO
    if($this->m_par[4] == "") $this->m_par[4] = '5,12';

        $this->comboSql($sql, $this->m_par[4], $situacao_id, $situacao_ids, $situacao_names);
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $situacao_id);
        
        $situacao_id = $param_id;
        if (is_array($situacao_id) && count($situacao_id) == 1) {
          $agruparPedidosSituacao = $situacao_id[0];
        } else {
          $agruparPedidosSituacao = 0;
        }
        $permiteAgruparPedidos = $this->verificaDireitoUsuario('PEDPERMITEAGRUPARPEDIDOS', 'S');
        
        $permiteAprovarPedidos = $this->verificaDireitoUsuario('PEDPERMITEAPROVARPEDIDOS', 'S', 'N');
        
        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            
            //$this->smarty->assign('pessoa', $this->m_par[2]);
            //$this->smarty->assign('nome', $this->getClienteNome());
        }
        
        // produto
        if($this->m_par[3] == "") $this->smarty->assign('codProduto', "");
        else {
            $arrProduto = "";
            $objProduto = new c_produto();
            $objProduto->setId($this->m_par[3]);
            $arrProduto = $objProduto->select_produto();
            $objProduto->setDesc($arrProduto[0]["DESCRICAO"]);
          //  $this->smarty->assign('codProduto', $this->m_par[4]);
          //  $this->smarty->assign('descProduto', $objProduto->getDesc());
        }
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'pedido_venda_telhas_novo');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('agruparPedidosSituacao', $agruparPedidosSituacao);
        $this->smarty->assign('permiteAgruparPedidos', $permiteAgruparPedidos); 
        $this->smarty->assign('permiteAprovarPedidos', $permiteAprovarPedidos); 
        //campo codPedido
        $this->smarty->assign('codPedido', $this->m_par[9]);

        // COMBOBOX MOTIVO
        $sql = "SELECT MOTIVO AS ID, DESCRICAO FROM FAT_MOTIVO";
        $this->comboSql($sql, $this->m_par[8], $motivo_id, $motivo_ids, $motivo_names);
        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        $this->smarty->assign('motivo_id', $motivo_id);

        // ########## CENTROCUSTO ##########
        $verSomenteInfoDaLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');
        $cWhere = '';
        if ($verSomenteInfoDaLoja) {
            $cWhere = 'where centrocusto = '.$this->m_empresacentrocusto;
        }
        $sql = "select centrocusto as id, descricao from fin_centro_custo ".$aliqRegEspSTMTcWhere." order by centrocusto";
        $this->comboSql($sql, $this->m_par[7] ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        $this->smarty->assign('centroCusto_id', $centroCusto_id); 
        $this->smarty->assign('verSomenteInfoDaLoja',$verSomenteInfoDaLoja); 


        // COMBOBOX VENDEDOR
        $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('vertodoslancamentos',$vertodoslancamentos); 
        if($vertodoslancamentos == false){
            $vendedor = $this->verifica_vendedor();            
            $this->smarty->assign('vendedor_ids',   $vendedor[0]['USUARIO']);
            $this->smarty->assign('vendedor_names', $vendedor[0]['NOME']);
            $this->smarty->assign('vendedor_id', $vendedor[0]['USUARIO']);
        }else{
            //$sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
            $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
            $this->comboSql($sql, $this->m_par[5], $vendedor_id, $vendedor_ids, $vendedor_names);
            $this->smarty->assign('vendedor_id', $vendedor_id);
            $this->smarty->assign('vendedor_ids',   $vendedor_ids);
            $this->smarty->assign('vendedor_names', $vendedor_names);
        } 

        //COMBOBOX Cond Pagamento
        $sql = "SELECT * FROM FAT_COND_PGTO;";
        $this->comboSql($sql, $this->m_par[6], $condPag_id, $condPag_ids, $condPag_names);
        $this->smarty->assign('condPag_id', $condPag_id);
        $this->smarty->assign('condPag_ids',   $condPag_ids);
        $this->smarty->assign('condPag_names', $condPag_names);

        //COMBOBOX USR Fatura
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
        $this->comboSql($sql, $this->m_par[5], $usrfatura_id, $usrfatura_ids, $usrfatura_names);
        $this->smarty->assign('usrfatura_id', $usrfatura_id);
        $this->smarty->assign('usrfatura_ids',   $usrfatura_ids);
        $this->smarty->assign('usrfatura_names', $usrfatura_names);

        

        $permiteEstornarPedido = $this->verificaDireitoUsuario('PEDPERMITEESTORNARPEDIDO', 'S', 'N');
        $this->smarty->assign('permiteEstornarPedido', $permiteEstornarPedido);
        $this->smarty->assign('sistema',ADMSistema); 

        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL"] == "true"):
            $ajax_request = 'true';

            $consulta = new c_banco;
            $consulta->setTab("FIN_CLIENTE");
            $clienteEmail = $consulta->getField("EMAIL", "CLIENTE=".$this->getCliente());
            $consulta->close_connection(); 

            $consulta = new c_banco;
            $consulta->setTab("FAT_PEDIDO");
            $pedSituacao = $consulta->getField("SITUACAO", "ID=".$this->getId());
            $consulta->close_connection(); 

            $pedSituacao == 5 ? $ped = " Cotação " : $ped = " Pedido ";

            $assunto = $this->m_empresafantasia." - Ref".$ped."Nº ".$this->getId();

            $emailCorpo = "Prezado(a) Cliente, \n \n".
            "Estamos encaminhando ".$ped." no formato PDF.\n \n".
            "Para visualizar o arquivo PDF sugerimos o Acrobat Reader que pode ser baixado em:\n".
            "http://get.adobe.com/br/reader/ \n\n".            
            "Agradecemos, \n".
            $this->m_usernome ."\n".
            $this->m_empresanome;

            $idPedido = $this->getId();

            $this->setId('');
            $this->setCliente('');
            $this->smarty->assign('pessoa', '');
            $this->smarty->assign('id', '');

            $this->smarty->assign('idPedido', $idPedido);
            $this->smarty->assign('destinatario', $clienteEmail);
            $this->smarty->assign('comCopiaPara', $this->m_configemail);
            $this->smarty->assign('assunto',"'".$assunto."'");
            $this->smarty->assign('emailCorpo', $emailCorpo);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
  
        endif; 

        // envia email pedido

        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL_PEDIDO"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL_PEDIDO"] == "true"):
            $ajax_request = 'true';

            // caminhos absolutos para todos os diretorios do Smarty
            $this->smarty->template_dir = ADMraizCliente . "/template/ped";
            $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
            $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
            $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

            try{
                // Monta tpl p/ converter para pdf
                $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
                $this->smarty->assign('pathImagem', ADMimg);
                $this->smarty->assign('cssBootstrap', true);
                
                $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
    
                $lanc = $this->select_pedidoVenda();
                $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
                $empresa = $this->busca_dadosEmpresaCC($lanc[0]['CCUSTO']);
    
                // busca descrição condição pagamento
                if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
                    $descCondPgto = '';
                else:
                    $condPgto = new c_cond_pgto();
                    $condPgto->setId($lanc[0]['CONDPG']);
                    $descPgto = $condPgto->selectCondPgto();
                    $descCondPgto = $descPgto[0]['DESCRICAO'];
                endif;
                
                if ($lanc[0]['SITUACAO'] == 9):
                    // Busca lancamentos FINANCEIRO
                    $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
                else:
                    // Calcula lancamentos de acordo com condição pagamento.
                    //$fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
                endif;
                $this->smarty->assign('prazoEntrega', $lanc[0]['PRAZOENTREGA']);
                $this->smarty->assign('descCondPgto', $descCondPgto);
                $this->smarty->assign('empresa', $empresa);
                $this->smarty->assign('pedido', $lanc);
                $this->smarty->assign('pedidoItem', $lancItem);
                $this->smarty->assign('fin', $fin);
                
                // pega url imagem p/ converter pdf
                $urlImg = "https://admsistema.com.br/".ADMcliente."/images/logo.png";
    
                $this->smarty->assign('urlImg', $urlImg);
    
                $html = $this->smarty->fetch('pedido_venda_imp_romaneio.tpl');  
                $filePath =  ADMraizCliente."/images/doc/pedido".$this->getId(); 
                
                $filename = ADMraizCliente."/images/doc/pedido".$this->getId().".pdf";
                $options = new Options();
                $options->set('isRemoteEnabled', TRUE);
                // conversão PDF
                $dompdf = new DOMPDF($options);
                $dompdf->load_html($html);            
                $dompdf->set_paper('A4', 'portrait');
                $dompdf->render();
                file_put_contents($filename, $dompdf->output());

                chmod($filename, 0777);
                
                $tipoMsg = 'alerta';
                $ok = true;
                // Envia por email
                if($this->m_configsmtp == ''){
                    $msgAlert = "o SMTP do Usuario esta vazio. \n";
                    $ok = false;
                }
                if($this->m_configemail == ''){
                    empty($msgAlert) == true ? $msgAlert = "Email do Usuario esta vazio. \n" :
                    $msgAlert .= "Email do Usuario esta vazio.\n";
                    $ok = false;
                }
                if($this->m_configemailsenha == ''){
                    empty($msgAlert) == true ? $msgAlert = "Senha do Email do Usuario esta vazio." :
                    $msgAlert .= "Senha do Email do Usuario esta vazio.";
                    $ok = false;
                }
                if($ok == true){
                    $tipoMsg = 'sucesso';
                    $mail = new admMail();    

                    

                    $resp = $mail->SendMail($this->m_configsmtp, $this->m_configemail, "email Pedido PDF", $this->m_configemailsenha, 
                                   $this->m_emailCorpo, $this->m_assunto, $this->m_destinatario, "",$this->m_comCopiaPara,"", $filename, $filename);
                
                    $msgAlert = "Email Enviado.";
                }

                // deleta o PDF criado
                unlink($filename);
                $this->setId('');

                // caminhos absolutos para todos os diretorios do Smarty
                $this->smarty->template_dir = ADMraizFonte . "/template/ped";
                $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
                $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
                $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

                $this->smarty->assign('mensagem', $msgAlert);
                $this->smarty->assign('tipoMsg', $tipoMsg);

            }catch(Error $e){
                throw new Error($e->getMessage()."Erro ao enviar Email Pedido " );
            }
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
  
        endif; 



        $this->smarty->display('pedido_venda_telhas_mostra_novo.tpl');
    }//fim mostrapedido


    function validaAlterarPedido(){
        // verifica se tem direitos para alterar pedido
        if($this->verificaDireitoUsuario('PEDPERMITEALTERARPEDIDOS', 'S', 'N')){
            $msg = '';
            $alert = 'sucesso';
            
            $notaFiscal = new c_banco;
            $notaFiscal->setTab("EST_NOTA_FISCAL");
            $resultNota = $notaFiscal->getField("ID", "DOC =".$this->getPedido()." AND ORIGEM = 'PED' ");
            
            // verifica se já foi emitido nota fiscal
            if($resultNota != '' ||  $resultNota != NULL){ 
                $msg .= 'erro';
                $alert = 'alerta';
            }else{
                $lancamentos = new c_banco;
                $lancamentos->setTab("FIN_LANCAMENTO");
                $resultLanc = $lancamentos->getField("SITPGTO", "DOCTO =".$this->getPedido()." AND ORIGEM = 'PED' AND SITPGTO <> 'B' AND REMESSANUM <> NULL ");
                // verifica se tem parcelas (financeiro) em aberto
                if($resultLanc != '' ||  $resultLanc != NULL){
                    $msg .= 'erro';
                    $alert = 'alerta';
                }
            }

            $response = array('tipoMsg' => $alert, 'msg' => $msg );
            return $response;
        }        
            
    }

    function validaInfoCliente($idCliente){

        $sql = "SELECT * FROM FIN_CLIENTE   ";
        $sql .= "WHERE (CLIENTE = " . $idCliente . ") ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $clienteInfo = $banco->resultado;
        $banco->close_connection();

        if($clienteInfo[0]['NOME'] == '' || $clienteInfo[0]['NOME'] == ''){
            return 'Inserir Nome do Cliente';
        }
        if($clienteInfo[0]['CNPJCPF'] == ''){
            return 'Inserir CPF/CNPJ do Cliente';
        }
        if($clienteInfo[0]['CEP'] == '' ){
            return 'Inserir CEP do Cliente';
        }

        $cep = preg_replace("/[^0-9]/", "", $clienteInfo[0]['CEP']);
        $cep = str_pad($clienteInfo[0]['CEP'], 8, "0", STR_PAD_LEFT);
        $url = "https://viacep.com.br/ws/".$cep."/json/";
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Send the request
        $response = curl_exec($ch);

        $arr = json_decode($response, true);                                                        
        $ibge= $arr['ibge'];
        if ($ibge != null || $ibge != '' ) {
            $sql = "UPDATE  ";
            $sql .= " FIN_CLIENTE  SET CODMUNICIPIO = ".$ibge;
            $sql .= " WHERE (CLIENTE = " . $idCliente . ") ";

            $banco = new c_banco;
            $banco->exec_sql($sql);
            $banco->close_connection();
            return '';                           
        }else{
            return 'Verificar dados de Endereço do Cliente';
        } 


		
		
		
		
       
    }

   
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda_telhas_novo();

$pedido->controle();    
