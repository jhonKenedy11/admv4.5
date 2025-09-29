<?php
/**
 * @name      p_atendimento_pedido
 * @version   4.3.1
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony
 * @date      17/03/2021
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_atendimento_pedido.php");
require_once($dir . "/../../class/cat/c_atendimento_pedido_tools.php");
require_once($dir . "/../../forms/est/p_nfephp_40.php");
require_once($dir . "/../../forms/est/p_nfephp_imprime_danfe.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir."/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/coc/c_ordem_compra.php");

// if($_POST['submenu'] == 'inclui'){
//     include_once($dir . "/../../forms/ped/p_pedido_venda_gerente.php");
// }



//Class p_atendimento_pedido
Class p_atendimento_pedido extends c_atendimento_pedido {

    private $m_submenu              = NULL;
    private $m_letra                = NULL;
    private $m_par                  = NULL;
    public  $smarty                 = NULL;
    private $m_situacoesAtendimento = NULL;
    private $numDocto               = NULL;
    private $serieDocto             = NULL;
    private $m_descCondPgto         = NULL;
    public  $m_dadosFinanceiros     = NULL;
    public  $m_dadosParcelas        = NULL;
    public  $m_dadosPecas           = NULL;
    private $parmPost               = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $this->parmPost['submenu'];

        $this->m_letra = $this->parmPost['letra'];
        
        $this->m_par = explode("|", $this->m_letra);        

        $this->m_dadosPecas = $this->parmPost['dadosPecas'];     
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Atendimento Pedido");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : '');
        $this->setNrItem(isset($this->parmPost['nrItem']) ? $this->parmPost['nrItem'] : '');
        $this->setPrazoEntrega(isset($this->parmPost['prazoEntrega']) ? $this->parmPost['prazoEntrega'] : '');
        
        $this->setCondPgto(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : '');
        $this->setObs(isset($this->parmPost['obs']) ? $this->parmPost['obs'] : '');
        $this->setSituacao(isset($this->parmPost['situacao']) ? $this->parmPost['situacao'] : '');
        $this->setCentroCusto(isset($this->parmPost['centroCusto']) ? $this->parmPost['centroCusto'] : '');
        if (isset($this->parmPost['pessoa'])):
            $this->setCliente($this->parmPost['pessoa']);
        else:    
            $this->setCliente('');
        endif;   
        $this->setValorFrete(isset($this->parmPost['valorFrete']) ? $this->parmPost['valorFrete'] : '0');
        //$this->setDesconto(isset($this->parmPost['desconto']) ? $this->parmPost['desconto'] : '0');
        $this->setDespAcessorias(isset($this->parmPost['despAcessorias']) ? $this->parmPost['despAcessorias'] : '0');
        $this->setUsrFatura(isset($this->parmPost['usrFatura']) ? $this->parmPost['usrFatura'] : '');
        $this->setEmissao(isset($this->parmPost['emissao']) ? $this->parmPost['emissao'] : date("Y-m-d"));
       
        // complemento descricao pedido_item
        $this->setValorTotal(isset($this->parmPost['valorTotal']) ? $this->parmPost['valorTotal'] : '0');
        $this->setTotalPecasUtilizada(isset($this->parmPost['totalPecasUtilizada']) ? $this->parmPost['totalPecasUtilizada'] : '0');

        
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {  
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $this->desenhaCadastroAtendimentoPedido();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    try {
                        $transaction = new c_banco();
                        //inicia transacao
                        $transaction->inicioTransacao($transaction->id_connection);
        

                        $this->setSituacao(5); // Situacao pedido 6 = PEDIDO
                        $this->getEspecie() == '' ? $this->setEspecie('D') : $this->getEspecie();
                        $idGerado = $this->incluiPedido($transaction->id_connection);
                        $objAtendimentoPedTools = new c_atendimento_pedido_tools();
                        $objAtendimentoPedTools->incluiAtendimentoPedidoItensControle(
                            $transaction->id_connection,
                            $this->m_dadosPecas, 
                            $idGerado, 
                            $this->getUsrFatura());
                        
                        $this->setId($idGerado);
                        $this->updateField('PEDIDO', $idGerado, 'FAT_PEDIDO', $transaction->id_connection);
                    
                        $transaction->commit($transaction->id_connection);
                        $transaction->close_connection($transaction->id_connection);

                        $msg = 'Pedido Gerado: '.$idGerado.' - Situação Pedido COTAÇÃO';
                        $tipoMsg = "sucesso";
                        // $this->desenhaCadastroAtendimentoPedido($msg, 'sucesso');
                        // $pedidoFinaliza = new p_pedido_venda_gerente('', '', '', '', $msg);
                        // $pedidoFinaliza->controle();
                    } catch (Error $e) {
                        $transaction->rollback($transaction->id_connection);    
                        $transaction->close_connection($transaction->id_connection);
                        $msg = "Pedido Não Gerado - Verificar produtos cadastrados<br>".$e->getMessage();
                        $tipoMsg = "error";
    
                    } catch (Exception $e) {
                        if ($transaction->id_connection != null){
                            $transaction->rollback($transaction->id_connection);
                            $transaction->close_connection($transaction->id_connection);
                        }
                        $msg = "Pedido Não Gerado - Verificar produtos cadastrados<br>".$e->getMessage();
                        $tipoMsg = "error";
                    }
                    $this->desenhaCadastroAtendimentoPedido($msg, $tipoMsg);
                }
                break;
            case 'geraOrdemCompra':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    try {
                        //inicia transacao
                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);
        
                        $objOrdemCompra = new c_ordemCompra();
                        $objOrdemCompra->setCliente($this->parmPost['pessoa']);
                        $objOrdemCompra->setSituacao('5');
                        $objOrdemCompra->setEmissao($this->parmPost['emissao']);
                        $objOrdemCompra->setCondPg($this->parmPost['condPgto']);
                        $objOrdemCompra->setObs($this->parmPost['obs']);
                        $objOrdemCompra->setCentroCusto($this->parmPost['centroCusto']);
                        $objOrdemCompra->setNumeroNf('0');
                        $objOrdemCompra->setSerie('0');
                        $objOrdemCompra->setDataEntrada('');
                        $objOrdemCompra->setDataEmissao($this->parmPost['emissao']);
                        $objOrdemCompra->setGenero($this->getGenero());
                        $objOrdemCompra->setDesconto('');
                        $objOrdemCompra->setProdutos('');
                        $objOrdemCompra->setTotal($this->parmPost['valorTotal']);
                        $objOrdemCompra->setFrete($this->parmPost['valorFrete']);
                        $objOrdemCompra->setSeguro('');
                        $objOrdemCompra->setDespAcessorias($this->parmPost['despAcessorias']);

                        $idGerado = $objOrdemCompra->incluiOrdemCompra($transaction->id_connection);

                        //add items to purchase order
                        if(is_int($idGerado)){
                            //variable responsible for block validation the items
                            $process = null;
                                
                            $item = explode("|", $this->m_dadosPecas );      

                            $numItem = 1;    
                            for ($i=1; $i<count($item); $i++){

                                $itemArr = explode("*", $item[$i]);

                                $objOrdemCompra->setId((int) $idGerado);
                                $objOrdemCompra->setNrItem($i);
                                $objOrdemCompra->setOc($idGerado);
                                $objOrdemCompra->setItemEstoque(trim($itemArr[0]));
                                $objOrdemCompra->setCodigoNota(trim($itemArr[1]));
                                $objOrdemCompra->setQtSolicitada($itemArr[6]);
                                $objOrdemCompra->setUnitario($itemArr[7]);
                                $objOrdemCompra->setDescontoItem($itemArr[9]);
                                $objOrdemCompra->setPercDescontoItem($itemArr[8]);
                                $objOrdemCompra->setTotalItem($itemArr[10]);
                                $objOrdemCompra->setDescricaoItem(trim($itemArr[2]));
                                $objOrdemCompra->setUnidade(trim($itemArr[3]));

                                $consulta = new c_banco;
                                $consulta->setTab("EST_PRODUTO");
                                $codFabricante = $consulta->getField("CODFABRICANTE", "CODIGO=".$itemArr[0]);
                                $consulta->close_connection();

                                $objOrdemCompra->setItemFabricante($codFabricante);

                                $result = $objOrdemCompra->incluiOrdemCompraItem($transaction->id_connection);

                                if(!is_int($result)){
                                    $process = false;
                                    break;
                                }else{
                                    //update work order with purchase order number
                                    $banco = new c_banco;
                                    $sql = "update CAT_AT_PECAS set oc_id =".$idGerado.
                                           " where CAT_ATENDIMENTO_ID =" .$this->getId(). " and codproduto=".$objOrdemCompra->getItemEstoque();
                                    $banco->exec_sql($sql, $transaction->id_connection);
                                    $banco->close_connection();

                                    if($banco->result == false or $banco->result == null){
                                        throw new Exception("Erro ao atualizar o item!");
                                    }

                                    $process = true;
                                }

                            } //END for

                            if($process == true){ //if the insertion of the item was successful

                                $transaction->commit($transaction->id_connection);
                                $transaction->close_connection($transaction->id_connection);

                                $msg = "Ordem de compra ".$idGerado." gerada!<br>";
                                $tipoMsg = "sucesso";
                            }else{ // if the inserion of the item was error
                                $transaction->rollback($transaction->id_connection);
                                $transaction->close_connection($transaction->id_connection);
                                throw new Exception("Item " . trim($itemArr[0]) ." não cadastrado, entre em contato com o suporte!");
                            }
                            
                        }else{ //if the insertion of the purchase order was error
                            throw new Exception("Erro ao inserir a ordem de compra!");
                        }

                    } catch (Error $e) {
                        $transaction->rollback($transaction->id_connection);    
                        $transaction->close_connection($transaction->id_connection);
                        $msg = "Ordem de compra não gerada - Verificar produtos cadastrados<br>".$e->getMessage();
                        $tipoMsg = "error";
    
                    } catch (Exception $e) {
                        if ($transaction->id_connection != null){
                            $transaction->rollback($transaction->id_connection);
                            $transaction->close_connection($transaction->id_connection);

                        }
                        $msg = "Ordem de compra não gerada! <br>".$e->getMessage();
                        $tipoMsg = "error";
                    } 

                    $this->desenhaCadastroAtendimentoPedido($msg, $tipoMsg);
                }

                break;
            default:
                if ($this->verificaDireitoUsuario('CatAtendimento', 'C')) {
                    $this->desenhaCadastroAtendimentoPedido();
                }
        }
    }


    function desenhaCadastroAtendimentoPedido($mensagem = NULL,$tipoMsg=NULL) {       
        

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);        

        $this->smarty->assign('id', $this->getId());
        $atendimento = $this->select_atendimento($this->getId());

        $this->setCliente($atendimento[0]['CLIENTE']);
        if($this->getCondPgto() == ''){
            $this->setCondPgto($atendimento[0]['CONDPGTO']);
        }

        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('contato', $this->getContato());
        $this->smarty->assign('atendimento', $this->getAtendimento());
        $this->smarty->assign('situacao', $this->getSituacao());

        if($this->getEmissao() == ''){
            $this->smarty->assign('emissao', date("d-m-Y"));
        }else{
            $this->smarty->assign('emissao', $this->getEmissao('F'));
        }
        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega('F'));
        $this->smarty->assign('condPgto', $this->getCondPgto());
        $this->smarty->assign('obs', $this->getObs());
        $this->setValorTotal($atendimento[0]['TOTALUTILIZADOPECAS']);  

        

        if ($this->getId()!=''):
                   
            $lancPesq = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPesq', $lancPesq);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrPecas = $consulta->getField("VALORUTILIZADOPECAS", "ID=".$this->getId());
            $consulta->close_connection();
            $vlrPecas = number_format($vlrPecas, 2, ',', '.');
            $this->smarty->assign('totalPecasUtilizada', $vlrPecas);            
            
            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrDesconto = $consulta->getField("VALORDESCONTO", "ID=".$this->getId());
            $consulta->close_connection();
            $vlrDesconto = number_format($vlrDesconto, 2, ',', '.');
            $this->smarty->assign('valorDesconto', $vlrDesconto); 

            
            $this->smarty->assign('valorTotal', '0,00');  

        else:
            {$this->smarty->assign('totalatendimento', '0');}
        endif;

        // COMBOBOX ATENDENTE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $usrFatura_ids[$i + 1] = $result[$i]['USUARIO'];
            $usrFatura_names[$i] = $result[$i]['NOME'];
        }
        $this->smarty->assign('usrFatura_ids',   $usrFatura_ids);
        $this->smarty->assign('usrFatura_names', $usrFatura_names);
        if($this->getUsrFatura() == ''){
            $this->getUsrFatura($this->m_userid);
        }
        $this->smarty->assign('usrFatura', $this->getUsrFatura());

        // COMBOBOX COND PAGAMENTO
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

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());


        if (is_null($lancPesq) and $tipoMsg !== 'sucesso'){
            $mensagem .= " OS com itens não cadastrados no estoque, revisar OS!!";
            $tipoMsg = "alerta";
         }
         $this->smarty->assign('mensagem', $mensagem);
         $this->smarty->assign('tipoMsg', $tipoMsg);        
 
        $this->smarty->display('atendimento_pedido_cadastro.tpl');
    }   

   
}

// Rotina principal - cria classe
$atendimento_nf = new p_atendimento_pedido();

$atendimento_nf->controle();

