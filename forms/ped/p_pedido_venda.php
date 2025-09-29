<?php
/**
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      02/05/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/est/c_produto.php");

//Class P_situacao
Class p_pedido_venda extends c_pedidoVenda {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    public $smarty          = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra,$pesquisa, $itensPedido, $itensQtde) {
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
        $this->m_submenu = $submenu;
        $this->m_pesq = $pesquisa;
        $this->m_letra = $letra;
        $this->m_itensPedido = $itensPedido;
        $this->m_itensQtde = $itensQtde;
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

        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda.js";
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
                    if (is_array($this->select_pedidoVenda('D'))){
                        $this->desenhaCadastroPedido();
                    }else{
                        $this->mostraPedido('Pedido não pode ser alterado.');
                    }
                    
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->setPedidoVenda();
                    $this->setSituacao('A');
                    $this->alteraPedido();
                    $this->mostraPedido('Pedido confirmado.');
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    
                    $this->setPedidoVenda();
                    $this->setSituacao('A');
                    $this->alteraPedido();
                    $this->mostraPedido('Pedido confirmado.');
                    
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    if (is_array($this->select_pedidoVenda('D'))){
                        $this->excluiPedido();
                    }else{
                        $this->mostraPedido('Pedido não pode ser deletado.');
                    }
                    
                    
                }
                break;
            case 'cadastrarItem':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
                    if (empty($this->getId())){
                        $this->setSituacao('D');
                        $this->setEmissao(date("d/m/Y"));
                        $this->setAtendimento(date("d/m/Y"));
                        $this->setHoraEmissao(date("H:i:s"));
                        $this->setEspecie("D");
                        $this->setCentroCusto($this->m_empresacentrocusto);
                        $this->setId($this->incluiPedido());
                    } 
                    // m_itensPedido -> contem todos os itens checados
                    $item = explode("|", $this->m_itensPedido);
                    $produtoOBJ = new c_produto();
                    for ($i=0;$i<count($item);$i++){
                        $produtoOBJ->setId($item[$i]);
                        // Consluta na table de produtos para pegar os dados
                        $arrProduto = $produtoOBJ->select_produto();
                        //pegar o ultimo NrItem do pedido
                        $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem();
                        $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                        $this->setItemEstoque($item[$i]);
                        $this->setQtSolicitada($this->m_itensQtde);
                        $this->setUnitario($arrProduto[0]['VENDA']);
                        $totalItem = $arrProduto[0]['VENDA'] * $this->m_itensQtde;
                        $this->setTotalItem($totalItem);
                        $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                        $this->setDescricaoItem($arrProduto[0]['DESCRICAO']);
                        $this->IncluiPedidoItem();
                        
                        
                    }
                    $this->desenhaCadastroPedido('Itens incluido!','sucesso');
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
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('entregador', $this->getEntregador());
        $this->smarty->assign('usrFatura', $this->getUsrFatura());
        $this->smarty->assign('codFisc', $this->getCodFisc());
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
        $this->smarty->assign('pesProduto', "'".$this->m_parPesq[0]."'");
        
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
        
        $this->smarty->assign('promocoes', $this->m_parPesq[2]);
        
        if (!empty($this->m_pesq)){
            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        if (!empty($this->getId())){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }
        
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

//        // tipo GRUPO
//        $consulta = new c_banco();
//        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoGrupo')";
//        $consulta->exec_sql($sql);
//        $consulta->close_connection();
//        $result = $consulta->resultado;
//        for ($i = 0; $i < count($result); $i++) {
//            $tipoGrupo_ids[$i] = $result[$i]['ID'];
//            $tipoGrupo_names[$i] = $result[$i]['DESCRICAO'];
//        }
//        $this->smarty->assign('tipoGrupo_ids', $tipoGrupo_ids);
//        $this->smarty->assign('tipoGrupo_names', $tipoGrupo_names);
//
//        $this->smarty->assign('tipo', $this->getTipo());



        $this->smarty->display('pedido_venda_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL) {
        //    $lanc = $this->select_pedido_venda_letra_situacao($this->m_letra);
        
        
        
        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacao_ids[0] = '';
        $situacao_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $arr = array('D','A');
        $this->smarty->assign('situacao_id', $arr);

        
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_pedido_venda($_POST['submenu'], $_POST['letra'], $_POST['pesq'],$_POST['itensPedido'],$_POST['itensQtde']);

if (isset($_POST['id'])) { $pedido->setId($_POST['id']); } else {$pedido->setId('');};

$pedido->controle();
?>
