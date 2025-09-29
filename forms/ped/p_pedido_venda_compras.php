<?php
/**
 * @package   astec
 * @name      p_pedido_venda_compras
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
require_once($dir . "/../../class/ped/c_pedido_venda_compras.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");

//Class P_pedido
Class p_pedido_venda_compras extends c_pedido_venda_compras {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
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
        
        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda_compras.js";
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
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->setPedidoVenda();
                    //$arrPedido = $this->select_pedidoVenda('0');
                    //if ($this->getSituacao() == '0'){
                        //$this->setSituacao($arrPedido[0]['SITUACAO']);
                        //$this->setCliente($arrPedido[0]['CLIENTE']);
                        $this->desenhaCadastroPedido();
                    //}else{
                    //    $this->mostraPedido('Pedido não pode ser alterado.');
                    //}
                    
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if ($this->getSituacao() == '0'):
                        $parametros = new c_banco;
                        $parametros->setTab("FAT_PARAMETRO");
                        $fluxo = $parametros->getField("FLUXOPEDIDO", "FILIAL=".$this->m_empresacentrocusto);
                        $situacaoEmitirNf = $parametros->getField("SITEMITIRNF", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();                        
                        //$this->setPedidoVenda();
                        $this->setTotal($this->select_totalPedido());
                        if ($fluxo=='S'):
                            $this->setSituacao(1);
                        else:    
                            $this->setSituacao($situacaoEmitirNf);
                        endif;
                        $this->setPedido($this->getId());

                        $this->alteraPedidoOticaTotal();

                        // form para finalizar pedido
                        $pedidoFinaliza = new p_pedido_venda_compras_nf($this->getId(), 'cadastrar');
                        $pedidoFinaliza->controle();
                    else:
                        $this->mostraPedido('Pedido já finalizado');
                        
                    endif;
                    
                    //$this->mostraPedido('Pedido confirmado.');
                    
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $msg = '';
                    if ($this->getId()!=''):
                        if ($this->getSituacao() == '0'):
                            $this->setTotal($this->select_totalPedido());
                            $this->setPedido(0);
                            $this->setSituacao(0);

                            $this->alteraPedidoOticaTotal();
                            $msg = 'Pedido em Digitação.';
                        else:
                            $this->alteraPedidoOticaReceita();
                        endif;
                    endif;
                    $this->mostraPedido($msg);
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    if (is_array($this->select_pedidoVenda(0))){
                        $this->excluiPedido();
                        $this->mostraPedido();
                    }else{
                        $this->mostraPedido('Pedido não pode ser deletado.');
                    }
                    
                    
                }
                break;
            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $sit = $this->getSituacao();
                    if ($this->getSituacao() == '0'):
                        $tipoMensagem = '';
                        $objPedidoTools = new c_pedidoVendaTools();
                        $id = $this->getId();
                        // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
                        if (empty($id)){
                            $this->setSituacao(0);
                            $this->setEmissao(date("d/m/Y"));
                            $this->setAtendimento(date("d/m/Y"));
                            $this->setHoraEmissao(date("H:i:s"));
                            $this->setEspecie("D");
                            $this->setCentroCusto($this->m_empresacentrocusto);
                            $id = $this->incluiPedido();
                        }


                        $msg = $objPedidoTools->incluiItensPedidoControle($this->m_empresacentrocusto, $id, $this->m_itensPedido, 
                                $this->m_itensQtde, $this->m_desconto, $tipoMensagem, $this->getCliente());
                        $this->setId($id);
                    else:
                        $tipoMensagem = 'alerta';
                        $msg  = 'Pedido não pode ser alterado.';
                    endif;
                    $this->desenhaCadastroPedido($msg, $tipoMensagem);
                }
                break;
            case 'excluiItem':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    // exclui
                    if ($this->getSituacao() == '0'):
                        $tipoMensagem = '';
                        $objPedidoTools = new c_pedidoVendaTools();
                        $msg = $objPedidoTools->excluiItensPedidoControle($this->m_empresacentrocusto, 
                                $this->getId(), $this->getNrItem(), 
                                $tipoMensagem);
                        $this->desenhaCadastroPedido($msg);
                    else:
                        $this->mostraPedido('Pedido não pode ser alterado.');
                    endif;
                    
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'pedido_venda_otica');
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
        $this->smarty->assign('odEsferico', $this->getOdEsferico());
        $this->smarty->assign('oeEsferico', $this->getOeEsferico());
        $this->smarty->assign('odCilindrico', $this->getOdCilindrico());
        $this->smarty->assign('oeCilindrico', $this->getOeCilindrico());
        $this->smarty->assign('odEixo', $this->getOdEixo());
        $this->smarty->assign('oeEixo', $this->getOeEixo());
        $this->smarty->assign('odAd', $this->getOdAd());
        $this->smarty->assign('oeAd', $this->getOeAd());
        $this->smarty->assign('medico', $this->getMedico());
        $this->smarty->assign('obs', $this->getObs());
        
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
        if ($this->getId()!=''):
            {$this->smarty->assign('totalPedido', $this->select_totalPedido());}
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;
        
        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 1; $i < count($result); $i++) {
            if ($this->getCondPg()==$result[$i]['ID']):
                $descCondPgto = $result[$i]['DESCRICAO'];
            endif;
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', "$descCondPgto");
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
            $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, NULL, $consultaEstoque);
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

        $this->smarty->display('pedido_venda_otica_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL) {
        $cliente = '';
        if ($this->m_letra !=''):
            $lanc = c_pedidoVenda::select_pedidoVenda();
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

        // COMBOBOX PEDIDO
        $consulta = new c_banco();
        $sql = "SELECT ID, PEDIDO FROM FAT_PEDIDO WHERE (SITUACAO=1) AND (CCUSTO=".$this->m_empresacentrocusto.")";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $pedido_ids[$i] = $result[$i]['ID'];
            $pedido_names[$i] = $result[$i]['PEDIDO'];
        }

        $this->smarty->assign('pedido_ids', $pedido_ids);
        $this->smarty->assign('pedido_names', $pedido_names);
        $this->smarty->assign('pedido_id', '');
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'pedido_venda_otica');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_compras_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda_compras();

$pedido->controle();

