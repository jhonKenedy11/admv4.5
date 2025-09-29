<?php
/**
 * @package   astec
 * @name      p_cupom
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      01/05/2018
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../forms/est/p_nfephp_exporta_xml.php");

//Class P_situacao
Class p_cupom extends c_pedidoVenda {

    private $m_submenu      = NULL;
    private $m_opcao        = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    private $m_numItem      = NULL;
    private $m_controlaEstoque = 'N';
    private $m_obs           = '';
    private $m_cpf           = '';
    public $smarty          = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/pdv";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_opcao = $parmPost['opcao'];
        $this->m_pesq = $parmPost['pesq'];
        $this->m_letra = $parmPost['letra'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

                // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');

        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Recibo");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
        
        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');        
        $this->setCliente(isset($parmPost['cliente']) ? $parmPost['cliente'] : '');        
        $this->setDesconto(isset($parmPost['desconto']) ? $parmPost['desconto'] : '');        
        $this->setTaxaEntrega(isset($parmPost['taxa']) ? $parmPost['taxa'] : '');        
        $this->setTotal(isset($parmPost['totalCupom']) ? $parmPost['totalCupom'] : '');        
        $this->setTotalProdutos(isset($parmPost['totalPedido']) ? $parmPost['totalPedido'] : '');
        $this->setTotalRecebido(isset($parmPost['valorPago']) ? $parmPost['valorPago'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_obs = $parmPost['obs'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        $this->m_numItem = $parmPost['numItem'];
        $this->m_cliente = $parmPost['cliente'];
        $this->m_cpf = $parmPost['cpf'];
        $this->m_valorDigitado = $parmPost['valor'];
        
        // include do javascript
        include ADMjs . "/pdv/s_cupom.js";
        include ADMjs . "/crm/s_cpf.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'cliente':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    if ($this->getId() != ''):
                        
                    endif;
                    

                    $this->desenhaCadastroCupom();
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroCupom();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if (is_array($this->select_pedidoVenda('0'))){
                        $this->desenhaCadastroCupom();
                    }else{
                        $this->mostraPedido('Pedido não pode ser alterado.');
                    }
                    
                }
                break;
            case 'inclui':
                $this->setPedidoVenda();
                $this->setSituacao(9);
                $this->alteraPedido();


                $this->desenhaCadastroEncerra();
                break;
            case 'cadastraNf': //encerra cupom ( cadastra nf, valida, imprime )
                $msg ='';
                try {
                    // BUSCA DADOS DOS PEDIDOS
                    $this->alteraPedidoRecebimentoCupom();
                    $this->setPedidoVenda();
                     // busca itens do pedido 
                    $arrItemPedido = $this->select_pedido_item_id();
                    if (!is_array($arrItemPedido)):
                        $msg = "Não existem produtos no pedido: ".$this->getId();
                        $result = false;
                        throw new Exception( $msg );
                    endif;

                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);
                    $result = true;

                    
                    // GERA NF
                    $objNotaFiscal = new c_nota_fiscal();
                    
                    $objNotaFiscal->setModelo('65');
                    $objNotaFiscal->setSerie($this->getSerie());
                    $objNotaFiscal->setPessoa($this->getCliente()); // ****** Define o cliente da nf de saida notafiscal!! *********
                    $objNotaFiscal->setNomePessoa(); // ****** Seta NOME, PESSOA, UF *********
                    $objNotaFiscal->setEmissao(date("d/m/Y H:i:s"));
                    $objNotaFiscal->setIdNatop($this->getIdNatop());
                    $objNotaFiscal->setTipo('1');
                    $objNotaFiscal->setSituacao('B');
                    $objNotaFiscal->setFormaPgto($this->getFormaPgto());//===
                    $objNotaFiscal->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                    $objNotaFiscal->setFormaEmissao('N');
                    $objNotaFiscal->setFinalidadeEmissao('1');
                    $objNotaFiscal->setCentroCusto($this->m_empresacentrocusto);
                    $objNotaFiscal->setGenero($this->getGenero());//====????
                    $objNotaFiscal->setTotalnf($this->getTotal('F'));//===
                    $objNotaFiscal->setModFrete('9');
                    $objNotaFiscal->setTransportador('0');
                    $objNotaFiscal->setObs($this->m_obs);
                    $objNotaFiscal->setCpfNota($this->m_cpf);
                    if ($this->m_opcao =='recibo'):
                        $objNotaFiscal->setOrigem('CPR');
                    else:
                        $objNotaFiscal->setOrigem('CPM');
                    endif;
                    $objNotaFiscal->setDoc($this->getPedido());
                    /*$objNotaFiscal->setVolume($this->volume);
                    $objNotaFiscal->setVolEspecie($this->volEspecie);
                    $objNotaFiscal->setVolMarca($this->volMarca);
                    $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
                    $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);
                    $objNotaFiscal->setObs($this->obs);*/
                    // ****** Gerar numero da notafiscal!! *********
                    $numNf = $objNotaFiscal->geraNumNf($objNotaFiscal->getModelo(), $objNotaFiscal->getSerie(), $this->m_empresacentrocusto);
                    $msg = $numNf." >>>Numero NF";
                    if (intval($numNf)==0):
                        $result = false;
                        throw new Exception( $msg );
                    endif;
                    $objNotaFiscal->setNumero($numNf);
                    $idGerado = $objNotaFiscal->incluiNotaFiscal($transaction->id_connection);
                    // verificar inclusao NF
                    if (intval($idGerado)==0):
                        $msg = $idGerado;
                        $result = false;
                        throw new Exception( $msg );
                    endif;

                    // CADASTRA ITENS NF
                    $objProduto = new c_produto();
                    $objCalcImposto = new c_pedidoVendaNf();
                    $objNfProduto = new c_nota_fiscal_produto();
                    for ($r = 0; $r < count($arrItemPedido); $r++) {
                        $objNfProduto->setIdNf($idGerado);
                        $objNfProduto->setCodProduto($arrItemPedido[$r]['ITEMESTOQUE']);
                        $objNfProduto->setDescricao($arrItemPedido[$r]['DESCRICAO']);
                        $objNfProduto->setUnidade($arrItemPedido[$r]['UNIDADE']);
                        $objNfProduto->setQuant($arrItemPedido[$r]['QTSOLICITADA'], true);
                        $objNfProduto->setUnitario($arrItemPedido[$r]['UNITARIO'], true);
                        $objNfProduto->setDesconto($arrItemPedido[$r]['DESCONTO'], true);
                        $objNfProduto->setTotal($arrItemPedido[$r]['TOTAL'], true);

                        // busca produto    ===>>> pode buscar dados dos produtos na funcao this->select_pedido_item_id();
                        $objNfProduto->setOrigem($arrItemPedido[$r]['ORIGEM']);
                        $objNfProduto->setTribIcms($arrItemPedido[$r]['TRIBICMS']);
                        $objNfProduto->setNcm($arrItemPedido[$r]['NCM']);
                        $objNfProduto->setCest($arrItemPedido[$r]['CEST']);

                        $result = $objCalcImposto->calculaImpostosNfe($objNfProduto, 
                                      $objNotaFiscal->getIdNatop(), 
                                      $objNotaFiscal->getUfPessoa(), 
                                      $objNotaFiscal->getTipoPessoa()); 

                        if (!$result):
                            $msg = "Tributos não localizado ".$objNfProduto->getDescricao()." Nat. Operação:".$objNotaFiscal->getIdNatop().
                                " UF:".$objNotaFiscal->getUfPessoa()." Tipo:".$objNotaFiscal->getTipoPessoa().
                                " CST:".$objNfProduto->getOrigem().$objNfProduto->getTribIcms().
                                " NCM:".$objNfProduto->getNcm()." CEST:".$objNfProduto->getCest();
                            throw new Exception( $msg );
                        endif;
                        $objNfProduto->setCustoProduto($arrItemPedido[$r]['CUSTOPRODUTO']);

                        $objNfProduto->setDataConferencia($arrItemPedido[$r]['DATACONFERENCIA']);

                        $result = $objNfProduto->incluiNotaFiscalProduto($transaction->id_connection);
                        // verificar inclusao item
                        if (is_string($result)):
                            $msg = $result;
                            $result = false;
                            throw new Exception( $msg );
                        endif;

                    } //for

                    $transaction->commit($transaction->id_connection);
                    $this->setSituacao(9);
                    $this->alteraPedidoSituacao();
                    
                    if ($this->m_opcao =='recibo'):
                        ?>
                           <script language="javascript" type="text/javascript">
                               javascript:abrir('index.php?mod=pdv&form=cupom_recibo&opcao=imprimir&parm=<?php echo $this->getId();?>')
                           </script>    
                        <?php
                        
                    else:    
                        // gera XML e autoriza o cupom
                        $objNotaFiscal->setId($idGerado);
                        $objNotaFiscal->setNotaFiscal();
                        $exporta = new p_exporta_xml();
                        $result = $exporta->Gera_XML($idGerado,  $this->m_empresacentrocusto, 1);
                    endif;
                   /* if (strpos($result, '100') === false ):
                        $msg = 'NF: '.$idGerado.' Erro: '.$result;
                    else:
                        $msg = 'NF: '.$idGerado.' Resultado: '.$result;
                    endif;*/
                    
                } catch (Error $e) {
                    throw new Exception($e->getMessage()."Nf Não foi gerado " );
                    break;

                }catch (Exception $e) {
                    $transaction->rollback($transaction->id_connection);    
                    throw new Exception($e->getMessage()."Nf/CUPOM Não foi gerado " );
                    break;
                }

                if ($this->m_opcao =='recibo'):
                    $this->desenhaCadastroCupom();
                else:
                    $this->desenhaCadastroEncerra($msg, null, $result);
                endif;
                break;
            case 'encerra': //encerra cupom ( cadastra nf, valida, imprime )
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                        $this->desenhaCadastroEncerra();
                }
                break;
            case 'exclui': // exclui pedido e itens
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                        $this->excluiPedidoItemGeral();
                        $this->excluiPedido();
                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $this->setCliente($parametros->getField("CLIENTEPADRAO",
                                "(FILIAL = ".$this->m_empresacentrocusto.") AND (MODELO=65)"));
                        $this->desenhaCadastroCupom();
                    }
                break;
            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
                try{
                    if (empty($this->getId())){
                        $this->setSituacao(0);
                        $this->setEmissao(date("d/m/Y"));
                        $this->setAtendimento(date("d/m/Y"));
                        $this->setHoraEmissao(date("H:i:s"));
                        $this->setEspecie("D");
                        $this->setCentroCusto($this->m_empresacentrocusto);
                        $parametros = new c_banco;
                        $parametros->setTab("AMB_USUARIO");
                        $cliente = $parametros->getField("CLIENTE", "USUARIO=".$this->m_userid);
                        $parametros->setTab("EST_PARAMETRO");
                        $parametros->close_connection();
                        //BUSCA PARAMETRO
                        $sql = "SELECT * FROM EST_PARAMETRO ";
                        $sql .= "WHERE (FILIAL = ".$this->m_empresacentrocusto.") AND (MODELO=65)";
                        $banco = new c_banco;
                        $res_parametro = $banco->exec_sql($sql);
                        $banco->close_connection();
                        $this->setSerie($res_parametro[0]['SERIE']);
                        $this->setIdNatop($res_parametro[0]['NATOPERACAO']);
                        $this->setCondPg($res_parametro[0]['CONDPGTO']);
                        $this->setGenero($res_parametro[0]['GENERO']);
                        $this->setContaDeposito($res_parametro[0]['CONTA']);
                        
                        $this->setId($this->incluiPedido());
                    }
                    // cadastra itens selecionados.
                    // m_itensPedido -> contem todos os itens checados
                    $msg = "";
                    $tipoMsg = "sucesso";
                    if ($this->m_itensPedido != ""){
                        $item = explode("|", $this->m_itensPedido);
                        $objProduto = new c_produto();
                        $objProdutoQtde = new c_produto_estoque();
                        for ($i=0;$i<count($item);$i++){
                            $quantDigitada = $this->m_itensQtde; // quant em digitacao
                            $quantPedido = 0;
                            $quantTotal = $quantDigitada;
                            // verifica se produto existe na tabela pedido item.
                            // verificar se existe o item no pedido
//                            $this->setItemEstoque($item[$i]);
//                            $arrItemPedido = $this->select_pedido_item_id_itemestoque();
//                            if (is_array($arrItemPedido)):
//                                $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
//                                $quantTotal = $quantDigitada + $quantPedido;
//                                $this->pedido_venda_item(false, $arrItemPedido);
//                            endif;
                            // Consluta na table de produtos para pegar os dados
                            $objProduto->setId($item[$i]); // CODIGO PRODUTO
                            $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, 
                                    $this->m_empresacentrocusto, $objProduto->getId(), $this->m_controlaEstoque);
                            $arrProduto[0]['VENDA'] = is_numeric($this->m_valorDigitado) ? $this->m_valorDigitado : $arrProduto[0]['VENDA'];
                            if ($this->m_controlaEstoque =='N'):
                                $arrProduto[0]['QUANTIDADE'] = $quantDigitada;
                            endif;
                            if (($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                                (floatval($arrProduto[0]['VENDA']) > floatval(0))): // TESTA PRECO E QUANT DISPONIVEL
                                if ((floatval($arrProduto[0]['PROMOCAO']) >floatval(0)) and 
                                    ($quantTotal > $arrProduto[0]['QUANTLIMITE'])): // TESTA MAXIMO VENDA PROMOCAO
                                    $msg .= $arrProduto[0]['DESCRICAO']." Quantidade acima limite promoção - Quant:".$arrProduto[0]['QUANTLIMITE']."<br>";
                                else:
                                    $this->setItemEstoque($item[$i]);
                                    $this->setItemFabricante($arrProduto[0]['CODFABRICANTE']);
                                    $this->setQtSolicitada($quantTotal);
                                    if (floatval($arrProduto[0]['PROMOCAO']) >floatval(0)):
                                        $this->setUnitario(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                                    else:
                                        $this->setUnitario(str_replace('.', ',', $arrProduto[0]['VENDA']));
                                    endif;    
                                    $this->setPrecoPromocao(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                                    $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));
                                    $this->setTotalItem();
                                    $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                                    $this->setDescricaoItem($arrProduto[0]['DESCRICAO']);
                                    if (is_array($arrItemPedido)):
                                        $this->alteraPedidoItem();
                                    else:
                                        //pegar o ultimo NrItem do pedido
                                        $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem();
                                        $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                                        $this->IncluiPedidoItem();
                                    endif;
                                    // reserva produto
                                    if ($this->m_controlaEstoque == "S"):
                                        $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                                           $this->getId(), $this->getItemEstoque(), $quantDigitada);
                                    endif;
                                endif;  
                            else: // PREÇO QUANTIDADE
                                $msg .= $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel<br>";
                                $tipoMsg = "alerta";
                            endif;
                        }
                        $this->desenhaCadastroCupom($msg, $tipoMsg);
                    }
                    else{
                        $this->desenhaCadastroCupom("Selecione um Produto para compra",'erro');
                    }
                } catch (Error $e) {
                    throw new Exception($e->getMessage()."Item não cadastrado " );

                } catch (Exception $e) {
                    throw new Exception($e->getMessage(). "Item não cadastrado " );

                }
                }
                break;
            case 'excluiItem':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    //BUSCAR DADOS DO ITEM A EXCLUIR
                    $arrPedidoItem = $this->select_pedido_item_id_nritem();
                    $this->setId($arrPedidoItem[0]['ID']);
                    $this->setItemEstoque($arrPedidoItem[0]['ITEMESTOQUE']);
                    $this->setQtSolicitada($arrPedidoItem[0]['QTSOLICITADA']);
                    
                    // retira de reserva
                    $objProdutoQtde = new c_produto_estoque();
                    $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "PED", 
                            $this->getId(), $this->getItemEstoque(), $this->getQtSolicitada());                    
                    
                    // exclui
                    $this->desenhaCadastroCupom($this->excluiPedidoItem());
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    //$cfop = $parametros->getParametros("CFOP");
                    //$this->setNatOperacao($parametros->getParametros("NATOPERACAO"));
                    
                    $this->desenhaCadastroCupom();
                }
        }
    }

    function desenhaCadastroEncerra($mensagem = NULL,$tipoMsg=NULL, $result=null) {

        $zero = "0,00";
        $id = $this->getId();
        $this->smarty->assign('id', $id);
        $this->smarty->assign('numItem', $this->m_numItem);
        $this->smarty->assign('totalPedido', $this->getTotalProdutos('F'));
        $this->smarty->assign('totalCupom', $this->getTotalProdutos('F'));
        $this->smarty->assign('valorPago', $this->getTotalProdutos('F'));
        $this->smarty->assign('troco', $zero);
        $this->smarty->assign('taxa', $this->getTaxaEntrega('F'));
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('danfe', $result['cDanfe']);
        $this->smarty->assign('obs', $this->m_obs);
        $this->smarty->assign('cpf', $this->m_cpf);
        
        
        // modo PAG/REC
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='ModoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $modo_ids[$i] = $result[$i]['ID'];
                $modo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('modo_ids', $modo_ids);
        $this->smarty->assign('modo_names', $modo_names);
        $this->smarty->assign('modo_id', 'D');	
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('emissao', $this->getEmissao('F'));

        // dados recibo
        $this->smarty->assign('nomeEmpresa', $this->m_empresanome);
        $empresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);
        $this->smarty->assign('foneEmpresa', $empresa[0]['FONEAREA'].' '.$empresa[0]['FONENUM']);
        
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nomeCliente', "'".$this->getClienteNome()."'");
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
        
        if (!empty($this->getId())){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }


        
        $this->smarty->display('cupom_encerra.tpl');
        
    }
    function desenhaCadastroCupom($mensagem = NULL,$tipoMsg=NULL) {

        $arrPedido = $this->max_pedidoVendaAberto(); // VERIFICA SE TEM PEDIDO ABERTO WHERE EMPRESA / USER / SITUACAO=0
        if ($arrPedido[0]['ID'] > 0):
            $this->setId($arrPedido[0]['ID']);
            $this->setCliente($arrPedido[0]['CLIENTE']);
        else:
            $this->setId('');
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $this->setCliente($parametros->getField("CLIENTEPADRAO",
                    "(FILIAL = ".$this->m_empresacentrocusto.") AND (MODELO=65)"));
            $this->m_parPesq[1] = $parametros->getField("GRUPOPADRAO",
                    "(FILIAL = ".$this->m_empresacentrocusto.") AND (MODELO=65)");
            $this->m_cpf = '';
        endif;
        $this->m_pesq = $this->m_parPesq[0] . "|" . $this->m_parPesq[1] . "|" .$this->m_parPesq[2];
        
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');
        $this->smarty->assign('cpf', $this->m_cpf);

        $id = $this->getId();
        $this->smarty->assign('id', $id);
        $this->smarty->assign('nrItem', $this->getId());
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nomeCliente', "'".$this->getClienteNome()."'");
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('entregador', $this->getEntregador());
        $this->smarty->assign('usrFatura', $this->getUsrFatura());
        $this->smarty->assign('idNatop', $this->getIdNatop());
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
        $this->smarty->assign('taxa', $this->getTaxaEntrega('F'));
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
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('totalProdutos', $this->getTotalProdutos('F'));
        $this->smarty->assign('frete', $this->getFrete('F'));
        $this->smarty->assign('obs', $this->m_obs);
        
        
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', "'".$this->m_parPesq[0]."'");
        if (isset($id)):
            {$this->smarty->assign('totalPedido', $this->select_totalPedido());}
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;
        // COMBOBOX GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Grupo';
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
            $objProdutoQtde = new c_produto_estoque();
            $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, NULL, $this->m_controlaEstoque);
//            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        if (!empty($this->getId())){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }
        
        //QUANTIDADE
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

        $this->smarty->display('cupom_cadastro.tpl');
    }

//fim desenhaCadastroCupom
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_cupom();

$pedido->controle();

