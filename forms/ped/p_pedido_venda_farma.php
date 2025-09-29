<?php
/** 
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda_farma.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf.php");

//Class P_situacao
Class p_pedido_venda extends c_pedidoVendaFarma {
            
    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    private $m_agrupar_pedidos = NULL;
    public $smarty          = NULL;
            
    private $baseIcms = null;
    private $valorIcms = null;
    private $valorIcmsSt = null;
    private $basePis = null;
    private $valorPis = null;
    private $baseCofins = null;
    private $valorCofins = null;
    private $exibirmotivo = null;    
    private $itensperdido = null;
 
    private $m_motivoSelecionados = null;
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
        $this->m_desconto = $parmPost['desconto'];
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        $this->m_agrupar_pedidos = $parmPost['agrupar_pedidos'];
        $this->m_motivoSelecionados = $parmPost['motivosSelecionados'];

        if ($this->verificaDireitoPrograma('FATVENDAPERDIDA', 'S')) {
            $exibirmotivo = 'S';
            $this->exibirmotivo = $exibirmotivo;
            $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
          } else {
              $exibirmotivo = '';
              $this->exibirmotivo = $exibirmotivo;
              $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
          }
        //$this->exibirmotivo = $parmPost['exibirmotivo'];
        $this->itensperdido = $parmPost['itensperdido'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setDataEntrega(isset($parmPost['dataEntrega']) ? $parmPost['dataEntrega'] : '');
        $this->setCondPg(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setIdNatop(isset($parmPost['natop']) ? $parmPost['natop'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
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
        // include do javascript
        //include ADMjs . "/ped/s_pedido_venda.js";
    }
/**
* <b> É responsavel para calcular os impostos de um item </b>
* @name calculoImpostosItem
* @param vazio
* @return atualiza os totais dos impostos
*/
function calculaImpostosItem() {
    $objPedidoNf = new c_pedidoVendaNf();
    $objNfProduto = new c_nota_fiscal_produto();

    // seta dados do item 
    $total  = $this->getQtSolicitada('B')*$this->getUnitario('B');

    $objNfProduto->setCodProduto($this->getItemEstoque());
    $objNfProduto->setDescricao($this->getDescricaoItem());
    // $objNfProduto->setUnidade($this->get  arrItemPedido[$i]['UNIDADE']);
    $objNfProduto->setQuant($this->getQtSolicitada('B'), true);
    $objNfProduto->setUnitario($this->getUnitario('B'), true);
    $objNfProduto->setDesconto($this->getPerDesconto('B'), true); // VERIFICAR DESCONTO
    $objNfProduto->setTotal($total, true);

    // busca produto
    $objProdutoC = new c_produto();
    $objProdutoC->setId($this->getItemEstoque());
    $arrProdutoC = $objProdutoC->select_produto();

    $objNfProduto->setOrigem($arrProdutoC[0]['ORIGEM']);
    $objNfProduto->setTribIcms($arrProdutoC[0]['TRIBICMS']);
    $objNfProduto->setNcm($arrProdutoC[0]['NCM']);
    $objNfProduto->setCest($arrProdutoC[0]['CEST']);

    $cliente = $this->getCliente();
    $this->setClienteNome();
    $uf = $this->getUfPessoa();
    $tipop = $this->getTipoPessoa();

    $result = $objPedidoNf->calculaImpostosNfe($objNfProduto, 
                    $this->getIdNatop(), 
                    $this->getUfPessoa(), 
                    $this->getTipoPessoa(),
                    $this->m_empresacentrocusto); 

    if (!$result):
        return  0 ;
    else:
        $valorIcmsSt = $objNfProduto->getValorIcmsSt();
        return $valorIcmsSt;
    endif;
    
}

    
    /**
* <b> É responsavel para calcular os impostos dos itens selecionados </b>
* @name calculoImpostos
* @param vazio
* @return atualiza os totais dos impostos
*/
    function calculaImpostos() {
        $this->setPedidoVenda();
        $arrItemPedido = $this->select_pedido_item_id();
        $objPedidoNf = new c_pedidoVendaNf();
        $objNfProduto = new c_nota_fiscal_produto();

        // Operador de coalescencia para php 8.3 
        $arrItemPedido = $arrItemPedido ?? [];

        for ($i = 0; $i < count($arrItemPedido); $i++) {
            $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QTSOLICITADA']*$arrItemPedido[$i]['UNITARIO'];
            $objNfProduto->setCodProduto($arrItemPedido[$i]['ITEMESTOQUE']);
            $objNfProduto->setDescricao($arrItemPedido[$i]['DESCRICAO']);
            $objNfProduto->setUnidade($arrItemPedido[$i]['UNIDADE']);
            $objNfProduto->setQuant($arrItemPedido[$i]['QTSOLICITADA'], true);
            $objNfProduto->setUnitario($arrItemPedido[$i]['UNITARIO'], true);
            $objNfProduto->setDesconto($arrItemPedido[$i]['DESCONTO'], true); // VERIFICAR DESCONTO
            $objNfProduto->setTotal($arrItemPedido[$i]['TOTAL'], true);

            $objNfProduto->setOrigem($arrItemPedido[$i]['ORIGEM']);
            $objNfProduto->setTribIcms($arrItemPedido[$i]['TRIBICMS']);
            $objNfProduto->setNcm($arrItemPedido[$i]['NCM']);
            $objNfProduto->setCest($arrItemPedido[$i]['CEST']);

            $result = $objPedidoNf->calculaImpostosNfe($objNfProduto, 
                          $this->getIdNatop(), 
                          $this->getUfPessoa(), 
                          $this->getTipoPessoa(),
                          $this->m_empresacentrocusto); 

            if (!$result):
                $this->m_msg = "Tributos não localizado ".$objNfProduto->getDescricao()." Nat. Operação:".$this->getIdNatop().
                    "<br> UF:".$this->getUfPessoa()." Tipo:".$this->getTipoPessoa().
                    " CST:".$objNfProduto->getOrigem().$objNfProduto->getTribIcms().
                    "<br> NCM:".$objNfProduto->getNcm()." CEST:".$objNfProduto->getCest()."<br>";
                return  $this->m_msg ;
            else:
                $this->baseIcms += $objNfProduto->getBcIcms('B');
                $this->valorIcms += $objNfProduto->getValorIcms('B');
                $this->valorIcmsSt += $objNfProduto->getValorIcmsSt('B');
                $this->basePis += $objNfProduto->getBcPis('B');
                $this->valorPis += $objNfProduto->getValorPis('B');
                $this->baseCofins += $objNfProduto->getBcCofins('B');
                $this->valorCofins += $objNfProduto->getValorCofins('B');
                return '';
            endif;
        }
        
    }
    /**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
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
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    
                    //tipoMsg = "success" (verde) | "danger" (vermelho) | "warning" (amarelo)

                    $itens_pedido = c_pedidoVendaFarma::select_itens_pedido($this->getId());
                    $itens_pedido_estoque = c_produto_estoque::verify_itemns_order($this->getId());


                    if (!is_array($itens_pedido)) {
                        $this->mostraPedido("Pedido ". $this->getId(). " nao possui item cadastrado", "danger");
                        exit;
                    }

                    if (!is_array($itens_pedido_estoque)) {
                        $this->mostraPedido("Pedido " . $this->getId() . " nao possui item reservado", "danger");
                        exit;
                    }

                    foreach($itens_pedido as $item){

                        $validate_quant = c_produto_estoque::verify_itemns_order_product($this->getId(), $item["ITEMESTOQUE"], 1);
                        
                        $qtd_reservada = count($validate_quant);
                        $qtd_solicitada = intval($item["QTSOLICITADA"]);

                        $qtd_reservada_f = number_format($qtd_reservada, 2, '.', '');
                        $qtd_solicitada_f = number_format($qtd_solicitada, 2, '.', '');
                        

                        if($qtd_reservada < $qtd_solicitada){

                            $this->desenhaCadastroPedido("<h6><b>" . $item["ITEMESTOQUE"] . " - ". $item["ITEMFABRICANTE"] . " - " . $item["DESCRICAO"] . "
                                                            </br>
                                                            </br>
                                                            <p id='infoError'>
                                                                Quantidade reservada ". $qtd_reservada_f.", menor que a solicitada ".$qtd_solicitada_f. "</br> Remova o item e adicione novamente ao pedido! 
                                                            </p>
                                                            </b></h6></br></br>", 
                                                            "warning"
                                                        );
                            exit;
                        }
                    }
                    
                    
                    $parametros = new c_banco;
                    $parametros->setTab("FAT_PARAMETRO");
                    $fluxo = $parametros->getField("FLUXOPEDIDO", "FILIAL=".$this->m_empresacentrocusto);
                    $situacaoEmitirNf = $parametros->getField("SITEMITIRNF", "FILIAL=".$this->m_empresacentrocusto);
                    $parametros->close_connection();                        
                    //$this->setPedidoVenda();
                    $this->setTotal($this->select_totalPedido());
                    if (($fluxo=='S') or ($fluxo=='I')):
                        $this->setSituacao(1);
                    else:    
                        $this->setSituacao($situacaoEmitirNf);
                    endif;
                    $this->setPedido($this->getId());
                    
                    $this->alteraPedidoTotal();
                    $this->atualizarField('CLIENTE', $this->getCliente());
                    $this->mostraPedido('Pedido confirmado. Número: '.$this->getId() );
                    
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    if ($this->getId()!=''):
                        //$this->setPedidoVenda();

                        //$this->setTotal(str_replace('.', ',', $this->select_totalPedido()));
                        $this->setTotal($this->select_totalPedido());
                        $this->setPedido(0);
                        $this->setSituacao(0);

                        $this->alteraPedidoTotal();
                        $this->mostraPedido('Pedido em Digitação. Número: '.$this->getId());
                    else:    
                        $this->mostraPedido('');
                    endif;
                    
                }
                break;
            case 'exclui': // CANCELA
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) 
                {
                    try{
                        $arrPedido = $this->select_pedidoVenda(0);

                        if (is_array($arrPedido)){
                            //$this->excluiPedido();
                            $this->setSituacao(8);
                            $this->alteraPedidoSituacao();

                            // retira reserva estoque
                            $arrItem = $this->select_pedido_item_id();

                            if (is_array($arrItem)){

                                for ($i = 0; $i < count($arrItem); $i++) {

                                    $objProdutoQtde = new c_produto_estoque();

                                    $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "PED", $arrItem[$i]['ID'], $arrItem[$i]['ITEMESTOQUE'], abs($arrItem[$i]['QTSOLICITADA']));
                                }    

                            }

                            $this->mostraPedido($this->getId()." - Pedido cancelado!", 'success');
                        }else{
                            $this->mostraPedido('Pedido não pode ser CANCELADO.', 'warning');
                        }
                    } catch (Throwable $e) {

                        $this->mostraPedido("Ocorreu um erro ao cancelar o pedido, entre em contato com o suporte!", "danger");
                        echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                    }
                }
                break;
            case 'estorna': // Estorna pedido voltando para digitação..
                //tipoMsg = "success" (verde) | "danger" (vermelho) | "warning" (amarelo)
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $arrPedido = $this->select_pedidoVenda();
                    if (is_array($arrPedido) and (!in_array($arrPedido[0]['SITUACAO'], ['9', '8'])))
                    {   
                        //Fluxo de atualizacao do financeiro
                        $b_financial = c_lancamento::search_invoices_docto($arrPedido[0]['ID']);
                        if(intval($b_financial[0]['FATURA_BAIXADA']) > 0)
                        {
                            $this->mostraPedido("Não foi possível realizar o estorno, pedido com financeiro baixado!", "warning");
                            break;
                        }

                        $b_update_invoices = c_lancamento::update_invoices_docto($arrPedido[0]['ID'], $arrPedido[0]['CLIENTE'], "C");
                        if(!$b_update_invoices)
                        {
                            $this->mostraPedido("Erro ao atualizar financeiro, entre em contato com o suporte!", "warning");
                            break;
                        }

                        //Fluxo de atualizacao do produto
                        // $verify_itemns = null;
                        // $verify_itemns = c_produto_estoque::verify_itemns_order($arrPedido[0]['ID']);

                        // if($verify_itemns !== null){
                        //     c_produto_estoque::update_itemns_order($arrPedido[0]['ID'], 0, null, "yes");
                        // }

                        $this->setSituacao(0);
                        $this->alteraPedidoSituacao();

                        $this->mostraPedido("Estorno do pedido " . $this->getId() . " realizado!", "success");
                    }else{
                        $this->mostraPedido("Situação atual do pedido não permite o estorno!", "danger");
                    }
                    
                    
                }
                break;
            
            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) 
                {
                    try{
                        if ($this->getSituacao() == '0'):
                            if (empty($this->getId())){
                                $this->setSituacao(0);
                                $this->setEmissao(date("d/m/Y"));
                                $this->setAtendimento(date("d/m/Y"));
                                $this->setHoraEmissao(date("H:i:s"));
                                $this->setEspecie("D");
                                //$this->setCentroCusto($this->m_empresacentrocusto);
                                $this->setId($this->incluiPedido());
                            }
                            // cadastra itens selecionados.
                            // m_itensPedido -> contem todos os itens checados
                            $msg = "";
                            //$this->formItemQuant($this->parmPost);
                            if ($this->m_itensPedido != ""){
                                $item = explode("|", $this->m_itensPedido);

                                $objProduto = new c_produto();
                                $objProdutoQtde = new c_produto_estoque();
                                for ($i=0;$i<count($item);$i++){
                                    $itemQuant = explode("*", $item[$i]);
                                    $codProduto = $itemQuant[1];
                                    $quant = str_replace(',', '.',$itemQuant[0]);
                                    $vlPromocao = $itemQuant[2];
                                    $quantDigitada = $quant; // quant em digitacao
                                    $quantPedido = 0;
                                    $quantTotalPromocaoMes = $this->selectQuantPedidoItem($this->getCliente(), $codProduto);
                                    $quantTotal = $quantDigitada;
                                    // verifica se produto existe na tabela pedido item.
                                    // verificar se existe o item no pedido
                                    $this->setItemEstoque($codProduto);
                                    $arrItemPedido = $this->select_pedido_item_id_itemestoque($transaction->id_connection);
                                    if (is_array($arrItemPedido)):
                                        $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
                                        $quantTotal = $quantDigitada + $quantPedido;
                                        $this->pedido_venda_item(false, $arrItemPedido);
                                    endif;
                                    // Consluta na table de produtos para pegar os dados
                                    $objProduto->setId($codProduto); // CODIGO PRODUTO
                                    $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, 
                                            $this->m_empresacentrocusto, $objProduto->getId());
                                    if (($quantDigitada >0) AND 
                                            //($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                                        (floatval($arrProduto[0]['VENDA']) > floatval(0)) and ($arrProduto[0]['UNIDADE'] <> '')): // TESTA PRECO E QUANT DISPONIVEL, UNIDADE
                                        if ((floatval($arrProduto[0]['PROMOCAO']) >floatval(0)) and 
                                            (($quantTotal + $quantTotalPromocaoMes) > $arrProduto[0]['QUANTLIMITE'])): // TESTA MAXIMO VENDA PROMOCAO
                                            $msg .= $arrProduto[0]['DESCRICAO']." Quantidade acima limite promoção - Quant:".$arrProduto[0]['QUANTLIMITE']."<br>";
                                        else:
                                            //$this->setItemEstoque($item[$i]);
                                            $this->setItemFabricante($arrProduto[0]['CODFABRICANTE']);
                                            $this->setDesconto(str_replace('.', ',', $this->m_desconto));
                                            $this->setQtSolicitada($quantTotal);
                                            if (floatval($vlPromocao) >floatval(0)):
                                                $this->setUnitario(str_replace('.', ',', $vlPromocao));
                                            else:
                                                $this->setUnitario(str_replace('.', ',', $arrProduto[0]['VENDA']));
                                            endif;    
                                            $this->setPrecoPromocao(str_replace('.', ',', $vlPromocao));
                                            $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));

                                            // calcula valor de ST do item 
                                            $this->setVlIcmsSt($this->calculaImpostosItem());

                                            $this->setTotalItem();
                                            $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                                            $this->setDescricaoItem($arrProduto[0]['DESCRICAO']);

                                            //inicio transação
                                            $transaction = new c_banco();
                                            $transaction->inicioTransacao($transaction->id_connection);

                                            if (is_array($arrItemPedido)):
                                                $this->alteraPedidoItem($transaction->id_connection);
                                            else:
                                                //pegar o ultimo NrItem do pedido
                                                $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem($transaction->id_connection);
                                                $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                                                $this->IncluiPedidoItem($transaction->id_connection);
                                            endif;
                                            // reserva produto
                                            if ($arrProduto[0]['UNIFRACIONADA'] == "N"){
                                                $quantReserva = $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                                                    $this->getId(), $this->getItemEstoque(), $quantDigitada, $transaction->id_connection);
                                            } else {
                                            //apagar informações do grid
                                            $objProdutoQtde->produtoReserva=null;
                                            }
                                            if ($quantDigitada <> $quantReserva):
                                                $msg .= "Quantidade do Produtos indiponível";
                                            else:    
                                                //; commit transação
                                                $transaction->commit($transaction->id_connection);                                      
                                            endif;                                                    

                                        endif;  
                                    else:
                                        $msg .= $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel<br> Unidade venda não cadastrada";
                                    endif;


                                }
                                $ItemFoiAdicionado = 'S';
                                $this->desenhaCadastroPedido($msg,'sucesso',$ItemFoiAdicionado);                            
                                $ItemFoiAdicionado = 'N';
                            }
                            else{
                                $this->desenhaCadastroPedido("Selecione um Produto para compra",'erro');
                            }
                        else:
                            $tipoMensagem = 'alerta';
                            $msg  = 'Pedido não pode ser alterado.';
                            $this->m_submenu = "cadastrar";
                            $this->desenhaCadastroPedido($msg, $tipoMensagem);
                        endif;
                    } catch (Error $e) {
                        $transaction->rollback($transaction->id_connection);    
                        throw new Exception($e->getMessage()."Item não cadastrado " );

                    } catch (Exception $e) {

                        $transaction->rollback($transaction->id_connection);    
                        $this->desenhaCadastroPedido("Item não cadastrado - FAZER LOGIN NO SISTEMA, SE PERSISTER A MENSAGEM ENTRAR EM CONTATO COM O SUPORTE.<br>".$e->getMessage(), 'error');
                    }
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
                    $this->mostraPedido('Pedido alterado para ENTREGAR', 'success');
                }else{
                    $this->mostraPedido('Pedido não pode ser alterado, status diferente de EMITIR NF.', 'warning');
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
                $this->desenhaCadastroPedido();               
                break;
            case 'duplicaPedido':
                try{
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaPedido($idAntigo);
                    $this->setId($idGerado);
                    $this->atualizarField('PEDIDO', $idGerado);
                    // $this->atualizarField('SITUACAO', '0');
                    // $this->atualizarField('EMISSAO', date('Y-m-d'));
                    // $this->atualizarField('HORAEMISSAO', date('H:i:s')); 
                    $this->select_pedidoVenda_situacao('0');                   
                    $this->duplicaPedidoItem($idGerado, $idAntigo);

                    //busca itens dos pedidos para efetuar reserva
                    $arrItensPedidos = $this->select_pedido_item_id();
                                    
                    $objProdutoQtde = new c_produto_estoque();
                    
                    for ($i=0;$i<count($arrItensPedidos);$i++){
                        // reserva produto
                        if ($arrItensPedidos[$i]['UNIFRACIONADA'] == "N"){
                            //adiciona reserva
                            $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                                $this->getId(), $arrItensPedidos[$i]['ITEMESTOQUE'], (int) $arrItensPedidos[$i]['QUANTIDADE']);
                        } else {
                            $objProdutoQtde->produtoReserva=null;
                        }
                    }

                    $this->setPedidoVenda();  
                    $this->m_submenu = 'cadastrar';                 
                    $this->desenhaCadastroPedido();
                                    
                } catch (Throwable $e){

                    $this->mostraPedido("Ocorreu um erro ao duplicar o pedido, entre em contato com o suporte!", "danger");
                    echo "<script>console.error('PHP Error: " . addslashes($e->getMessage()) . "');</script>";
                }

                break;
            case 'cadastraPedidoMassa':
                $id_original = $this->getId();
                // Consulta para obter os clientes
                $queryClientesAtividade = "SELECT CLIENTE FROM FIN_CLIENTE WHERE atividade = 'PM'";
                $banco = new c_banco;
                $banco->exec_sql($queryClientesAtividade);
                $result_clientes = $banco->resultado;
                $banco->close_connection();

                $totalClientes = count($result_clientes);

                if($totalClientes == null and $totalClientes == ''){
                    $this->mostraPedido('Clientes com atividade Pedido em Massa (PM) não localizados!');
                    break;
                }

                $this->setPedidoVenda();
                //formata campos para utilizar os sets
                $vlrEntradaCondicao = $this->getEntradaCondPg('F');
                $vlrDesconto   = $this->getDesconto('F');
                $vlrTaxaEntrega = $this->getTaxaEntrega('F');
                $vlrTotal = $this->getTotal('F');
                $vlrTotalRecebido = $this->getTotalRecebido('F');
                $vlrTotalProdutos   = $this->getTotalProdutos('F');

                for ($i=0; $i < $totalClientes; $i++) {
                    //sets novo pedido
                    $this->setCliente($result_clientes[$i]['CLIENTE']);
                    $this->setSituacao(0);
                    $this->setEmissao(date('Y-m-d'));
                    $this->setHoraEmissao(date('H:m:s'));
                    $this->setEntradaCondPg($vlrEntradaCondicao);
                    $this->setDesconto($vlrDesconto);
                    $this->setTaxaEntrega($vlrTaxaEntrega);
                    $this->setTotal($vlrTotal);
                    $this->setTotalRecebido($vlrTotalRecebido);
                    $this->setTotalProdutos($vlrTotalProdutos);
                    $this->setPedido(0);
                    //inclui pedido novo
                    $result_pedido_novo = $this->incluiPedido();

                    if($result_pedido_novo){
                        $result_duplica = $this->duplicaPedidoItem($result_pedido_novo, $id_original);

                        if($result_duplica == 0){
                            $pedidos_gerados .= $result_pedido_novo . '-' ;
                        }
                    }

                }
                $pedidos_format = rtrim($pedidos_gerados, '-');
                $this->mostraPedido('Pedidos gerados em digitação:</br></br>'.$pedidos_format);                       
                break;
            default:
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg=NULL, $ItemFoiAdicionado=NULL) {

       if (($this->m_submenu != 'cadastrar')and($this->m_submenu != 'agruparPedidos')):
           $mensagem .= $this->calculaImpostos();
       endif;
        $this->smarty->assign('baseIcms', $this->baseIcms);
        $this->smarty->assign('valorIcms', $this->valorIcms);
        $this->smarty->assign('valorIcmsSt', $this->valorIcmsSt);
        $this->smarty->assign('basePis', $this->basePis);
        $this->smarty->assign('valorPis', $this->valorPis);
        $this->smarty->assign('baseCofins', $this->baseCofins);
        $this->smarty->assign('valorCofins', $this->valorCofins);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'pedido_venda_farma');
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nrItem', $this->getId());
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
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
        $this->smarty->assign('horaEmissao', $this->getHoraEmissao('F'));
        $this->smarty->assign('taxaEntrega', $this->getTaxaEntrega('F'));
        $this->smarty->assign('totalRecebido', $this->getTotalRecebido('F'));
        $this->smarty->assign('dataEntrega', $this->getDataEntrega('B'));
        $this->smarty->assign('horaEntrega', $this->getHoraEntrega('F'));
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
        
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
        $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
        if ($ItemFoiAdicionado != null) {
            if ($ItemFoiAdicionado = "S")  {
                $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
                $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);            
            } else {
                $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
                $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
            }
        }
        if ($this->getId()!=''):
            {$this->smarty->assign('totalPedido', $this->select_totalPedido());}
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;

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
        $this->smarty->assign('situacao', $situacao);
        
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
        
        if (!empty($this->m_pesq)){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
            
            $objProdutoQtde = new c_produto_estoque();
            if ($ItemFoiAdicionado != "S")  {
                $lancPesq = $objProdutoQtde->produtoQtdePreco_40($this->m_pesq, $this->m_empresacentrocusto, null, 'P');
            } else {
              $lancPesq = $objProdutoQtde->null;  
            }
//            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        $id = $this->getId();
        if (!empty($id)){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }
        
        //QUANTIDADE
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

        // ########## CENTROCUSTO ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $centroCusto_ids[0] = '';
        $centroCusto_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i + 1] = $result[$i]['ID'];
            $centroCusto_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        
        // BUSCA PARAMETROS CENTRO CUSTO
        $cCusto = $this->getCentroCusto();
        if ($cCusto == null) { 
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $cCusto = $parametros->getField("CENTROCUSTO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();
        }    

        $this->smarty->assign('centroCusto_id', $cCusto);
        
        // COMBOBOX MOTIVO
        $consulta = new c_banco();
        $sql = "select motivo, descricao from fat_motivo ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $motivo_ids[0] = '';
        $motivo_names[0] = 'Selecione Venda Perdida';

        // Operador de coalescencia para versao php 8.3
        $result = $result ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $motivo_ids[$i + 1] = $result[$i]['MOTIVO'];
            $motivo_names[$i + 1] = $result[$i]['MOTIVO'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        $this->smarty->assign('motivo_id', null);
        
        if ($this->verificaDireitoPrograma('FatVendaPerdida', 'S')) {
          $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        } else {
            $exibirmotivo = '';
            $this->exibirmotivo = $exibirmotivo;
            $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        }

        $this->smarty->display('pedido_venda_farma_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL, $tipoMsg=NULL) 
    {
        if ($this->m_letra !=''){
            $lanc = $this->select_pedidoVenda_letra($this->m_letra, $this->m_motivoSelecionados);
        }
        
        if($this->m_par[0] == ""){
            $this->smarty->assign('dataIni', date("01/m/Y"));
        } else {
            $this->smarty->assign('dataIni', $this->m_par[0]);
        }

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else { 
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }

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
        
        if (($this->m_par[7] != '0') and ($this->m_par[7] != '')){

            for ($i = 8; $i < count($this->m_par); $i++) {
               $sit[$i-8] = $this->m_par[$i];
            }

        } else { 
            $sit[0] = "0";  
        }
        
        if (count($sit) == 1) {
            $agruparPedidosSituacao = $sit[0];
        } else {
            $agruparPedidosSituacao = 0;
        }
        
        $permiteAgruparPedidos = $this->verificaDireitoUsuario('PEDPERMITEAGRUPARPEDIDOS', 'S');
                
        // pessoa
        if($this->m_par[2] == ""){
            $this->smarty->assign('pessoa', "");
        } else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }
        
        // produto
        if($this->m_par[4] == ""){ 
            $this->smarty->assign('codProduto', "");
        } else {
            $arrProduto = "";
            $objProduto = new c_produto();
            $objProduto->setId($this->m_par[4]);
            $arrProduto = $objProduto->select_produto();
            $objProduto->setDesc($arrProduto[0]["DESCRICAO"]);
            $this->smarty->assign('codProduto', $this->m_par[4]);
            $this->smarty->assign('descProduto', $objProduto->getDesc());
        }
        
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $sit);
        
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'pedido_venda_farma');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('agruparPedidosSituacao', $agruparPedidosSituacao);
        $this->smarty->assign('permiteAgruparPedidos', $permiteAgruparPedidos); 

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT MOTIVO, DESCRICAO FROM FAT_MOTIVO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $motivo_ids[$i + 1] = $result[$i]['MOTIVO'];
            $motivo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);

        $this->smarty->display('pedido_venda_farma_mostra.tpl');
    }
//fim mostragrupos
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda();

$pedido->controle();

