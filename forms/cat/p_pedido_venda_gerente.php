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
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_pecas.php");
require_once($dir . "/../../class/est/c_produto.php");

//Class P_situacao
Class p_pedido_venda_gerente extends c_pedidoVenda {

    private $m_submenu          = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    private $m_codProduto       = NULL;
    private $m_qtdeConferido    = NULL;
    private $m_msg              = NULL;
    public $smarty              = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra, $codProduto, $qtdeConferido, $msg=null) {
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
        $this->m_letra = $letra;
        $this->m_codProduto = $codProduto;
        $this->m_qtdeConferido = $qtdeConferido;
        $this->m_msg = $msg;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('raizFonte', ADMraizFonte);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('httpCliente', ADMhttpCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Conferencia Pedidos");
        $this->smarty->assign('colVis', "[ 0,1,2,3]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 
        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda_gerente.js";
        // include ADMjs . "/ped/s_pedido_venda_conferencia.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'imprime':
                if ($this->verificaDireitoUsuario('PedGerente', 'R')) {
                    // BUSCA PARAMETROS
                    $parametros = new c_banco;
                    $parametros->setTab("FAT_PARAMETRO");
                    $sitEmiteNf = $parametros->getField("FLUXOPEDIDO", "FILIAL=".$this->m_empresacentrocusto);
                    $parametros->close_connection();                        
                    
                    IF ($sitEmiteNf == 'S'):
                        $this->atualizarField('situacao', 2);
                    // $this->setSituacao(2);
                    else:    
                        $this->atualizarField('situacao', 3);
                        // $this->setSituacao(3);
                    endif;
                    // $this->alteraPedidoSituacao();
                    $this->mostraPedidoGerente('');
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf_pecas($this->getId(), 'cadastrar');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'MesAtual':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $this->mostraPedidoGerente('A');
                }
                break;    
            case 'inclui':
                break;  
            default:
                if ($this->verificaDireitoUsuario('PedGerente', 'C')) {
                    $this->mostraPedidoGerente($this->m_msg);
                }
        }
    }

   

//fim desenhaCadgrupo
//-------------------------------------------------------------
//---------------------------------------------------------------
//---------------------------------------------------------------
    function imprimePedido($mensagem) {

        // $lanc = $this->select_pedidoVenda_letra('||||1|2|3');
        $lanc = $this->select_pedidoVenda_letra('||||3,6|');

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_gerente.tpl');
    }

//fim mostraPedidoConferencia

//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedidoGerente($mensagem) {
        if ($mensagem == 'A'){
          $lanc = $this->select_pedidoVenda_letra_atual('');
        }
        else {        
        //   $lanc = $this->select_pedidoVenda_letra('|||||6,3||||||');
          $lanc = $this->select_pedidoVenda_letra('||||3,6|');
          $this->smarty->assign('mensagem', $mensagem);
        }
       
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_gerente.tpl');
    }

//fim mostraPedidoConferencia
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_pedido_venda_gerente($_POST['submenu'], $_POST['letra'], $_POST['codProduto'], $_POST['qtdeConferido'], $_POST['msg']);

if (isset($_POST['id'])) { $pedido->setId($_POST['id']); } else {$pedido->setId('');};

$pedido->controle();
?>
