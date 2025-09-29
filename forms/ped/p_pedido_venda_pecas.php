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
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_pecas.php");

//Class P_situacao
Class p_pedido_venda_pecas extends c_pedidoVenda {
            
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
        $this->setDataEntrega(isset($parmPost['dataEntrega']) ? $parmPost['dataEntrega'] : date("Y-m-d"));
        
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
                $this->baseIcms += $objNfProduto->getBcIcms();
                $this->valorIcms += $objNfProduto->getValorIcms();
                $this->basePis += $objNfProduto->getBcPis();
                $this->valorPis += $objNfProduto->getValorPis();
                $this->baseCofins += $objNfProduto->getBcCofins();
                $this->valorCofins += $objNfProduto->getValorCofins();
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
                    $this->mostraPedido('Pedido confirmado.');
                    
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
                        $this->mostraPedido('Pedido em Digitação.');
                    else:    
                        $this->mostraPedido('');
                    endif;
                    
                }
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

                        $this->mostraPedido($this->getId()." - pedido Cancelado com sucesso!!");
                    }else{
                        $this->mostraPedido('Pedido não pode ser CANCELADO.');
                    }
                    
                    
                }
                break;
            case 'estorna': // Estorna pedido voltando para digitação..
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $arrPedido = $this->select_pedidoVenda();
                    if (is_array($arrPedido) and ($arrPedido[0]['SITUACAO'] != 9)){
                        $this->setSituacao(0);
                        $this->alteraPedidoSituacao();

                        $this->mostraPedido($this->getId()." - pedido Estornado com sucesso!!");
                    }else{
                        $this->mostraPedido('Pedido já baixado, não pode ser ESTORNADO.');
                    }
                    
                    
                }
                break;

            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
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

                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();                        
                        
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
                                $codProduto = $itemQuant[0];
                                $quant = str_replace(',', '.',$itemQuant[2]);
                                $vlPromocao = $itemQuant[3];
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
                                            $this->m_empresacentrocusto, $objProduto->getId(), $consultaEstoque);
                                    if (($arrProduto[0]['UNIFRACIONADA'] == "S") or (($arrProduto[0]['QUANTIDADE']  >= $quantDigitada ) and
                                        ($arrProduto[0]['UNIFRACIONADA'] == "N"))){

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
                                                $this->setUnitario( $vlPromocao);
                                            else:
                                                $this->setUnitario($itemQuant[1]);
                                            endif;    
                                            $this->setPrecoPromocao(str_replace('.', ',', $vlPromocao));
                                            $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));
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
                                                $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                                                    $this->getId(), $this->getItemEstoque(), $quantDigitada, $transaction->id_connection);
                                            } else {
                                            //apagar informações do grid
                                            $objProdutoQtde->produtoReserva=null;
                                            }
                                            //; commit transação
                                            $transaction->commit($transaction->id_connection);                                      
                                        endif;  
                                    else:
                                        $msg .= $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel<br> Unidade venda não cadastrada";
                                    endif;
                                                               
                                } else {
                                    if ($quantDigitada > 0) {
                                       $msg .= $arrProduto[0]['DESCRICAO']." Quantidade não disponivel<br> Unidade venda não cadastrada";
                                    }                                    
                                }    
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
                    $this->mostraPedido('Pedido não pode ser alterado para entregue.');
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
            default:
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg=NULL, $ItemFoiAdicionado=NULL) {

       $msg = $mensagem; 
       if (($this->m_submenu != 'cadastrar')and($this->m_submenu != 'agruparPedidos')):
           $mensagem = $this->calculaImpostos();
       endif;
       
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
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'pedido_venda_pecas');
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
        $this->smarty->assign('dataEntrega', $this->getDataEntrega('F'));
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
                $this->smarty->assign('pesProduto', "");
                $this->smarty->assign('pesLocalizacao',"");            
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
        
        if (!empty($this->m_pesq)){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
            
            $objProdutoQtde = new c_produto_estoque();
            if ($ItemFoiAdicionado != "S")  {
                $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, null, $consultaEstoque );
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

        $this->smarty->display('pedido_venda_pecas_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL) {
//            $this->m_letra = "1|2|3|4";
        // busco codigo do cliente no cadastro de usuário.
/*        $parametros = new c_banco;
        $parametros->setTab("AMB_USUARIO");
        $cliente = $parametros->getField("CLIENTE", "USUARIO=".$this->m_userid);
        $parametros->close_connection();                        
        $this->setCliente($cliente);*/

        //$cliente = $this->getCliente();
        $cliente = '';
        //$this->m_letra = "||".$cliente."||0|1|2|3|4";
        if ($this->m_letra !=''):
            $lanc = $this->select_pedidoVenda_letra($this->m_letra, $this->m_motivoSelecionados);
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
/*            $i = 5;
            $sit[$i-5] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[4]+4)) {
                    $sit[$i-5] = $this->m_par[$i];
                    $i++;
            }*/
        }
        else { $sit[0] = "0";  }
        
        if (count($sit) == 1) {
          $agruparPedidosSituacao = $sit[0];
        } else {
          $agruparPedidosSituacao = 0;
        }
        $permiteAgruparPedidos = $this->verificaDireitoUsuario('PEDPERMITEAGRUPARPEDIDOS', 'S');
                
        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }
        
        // produto
        if($this->m_par[4] == "") $this->smarty->assign('codProduto', "");
        else {
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
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'pedido_venda_pecas');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('agruparPedidosSituacao', $agruparPedidosSituacao);
        $this->smarty->assign('permiteAgruparPedidos', $permiteAgruparPedidos); 

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT MOTIVO, DESCRICAO FROM FAT_MOTIVO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $motivo_ids[$i + 1] = $result[$i]['MOTIVO'];
            $motivo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        //$this->smarty->assign('situacao_id', $sit);

        $this->smarty->display('pedido_venda_smart_mostra.tpl');
    }
//fim mostragrupos
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda_pecas();

$pedido->controle();