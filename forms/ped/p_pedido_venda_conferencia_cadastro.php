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
Class p_pedido_venda_conferecia_cadastro extends c_pedidoVenda {

    private $m_submenu          = NULL;
    private $m_origem           = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    private $m_codProduto       = NULL;
    private $m_qtdeConferido    = NULL;
    public $smarty              = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra, $origem, $codProduto, $qtdeConferido) {
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
        $this->m_origem = $origem;
        $this->m_letra = $letra;
        $this->m_codProduto = $codProduto;
        $this->m_qtdeConferido = $qtdeConferido;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Conferencia Pedidos");
        $this->smarty->assign('colVis', "[ 0,1,2,3]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
        // include do javascript
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
            case 'conferir':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->setId($this->m_par[0]);
                    
                        // verificar se existe o item no pedido
                        $this->setItemEstoque($this->m_codProduto);
                        $arrItemPedido = $this->select_pedido_item_id_itemestoque();
                        
                        if (is_array($arrItemPedido)){
                            if (($this->m_qtdeConferido+$arrItemPedido[0]['QTCONFERIDA']) <= $arrItemPedido[0]['QTSOLICITADA']){
                                $this->setNrItem($arrItemPedido[0]['NRITEM']);
                                //$this->pedido_venda_item();
                                $this->setQtConferida(number_format($this->m_qtdeConferido+$arrItemPedido[0]['QTCONFERIDA'], 4, ',', '.'));
                                $this->alteraPedidoItemConferencia();
                                $this->desenhaPedidoConferencia('Produto : '.$this->m_codProduto.', pedido: '.$this->getId().". conferido.",'sucesso');
                            }else{
                                $this->desenhaPedidoConferencia('Quantidade informada do produto '.$this->m_codProduto.' no pedido: '.$this->getId()." é maior que a quantidade solicitada!",'alerta');
                            }
                        }else{
                            $this->desenhaPedidoConferencia('Não existe produto codigo: '.$this->m_codProduto.' no pedido: '.$this->getId(),'alerta');
                        }
                        //verificar a quantidade a conferir
                        
                }
                break;
            
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->desenhaPedidoConferencia('');
                }
        }
    }

   

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaPedidoConferencia($mensagem=NULL, $tipoMsg=NULL) {
        
        
        if (!empty($this->m_letra)){
            
            $this->setId($this->m_par[0]);
            $this->setPedido($this->m_par[1]);
            $lanc = $this->select_pedido_item_id();
            
            $this->smarty->assign('id', $this->getId());
            $this->smarty->assign('pedido', $this->getPedido());
            $this->smarty->assign('origem', $this->m_origem);
            $this->smarty->assign('letra', $this->m_letra);
            //var boolean verificador
            $flag = true;
            for ($i=0;$i < count($lanc);$i++){
                $qtSolicitada = $lanc[$i]['QTSOLICITADA'];
                $qtConferida = $lanc[$i]['QTCONFERIDA'];
                
                if (($lanc[$i]['QTSOLICITADA'] != $lanc[$i]['QTCONFERIDA']) or (is_null($lanc[$i]['QTCONFERIDA']))){
                    $flag = false;
                }
            }
            if ($flag){
//                $this->setPedidoVenda();
                $this->setSituacao(3);
                $this->alteraPedidoSituacao();
            }
            
        }
        
        $this->m_codProduto = '';
        
    
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('codProduto', $this->m_codProduto);
        if (empty($this->m_qtdeConferido)){
            $this->smarty->assign('qtdeConferido', 1);
        }else{
            $this->smarty->assign('qtdeConferido', $this->m_qtdeConferido);
        }
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        
        
        $this->smarty->display('pedido_venda_conferencia_cadastro.tpl');
    }

//fim desenhaPedidoConferencia
//-------------------------------------------------------------


//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_pedido_venda_conferecia_cadastro($_POST['submenu'], 
        $_POST['letra'], $_POST['origem'],
        $_POST['codProduto'], 
        $_POST['qtdeConferido']);

if (isset($_POST['id'])) { $pedido->setId($_POST['id']); } else {$pedido->setId('');};
if (isset($_POST['pedido'])) { $pedido->setPedido($_POST['pedido']); } else {$pedido->setPedido('');};

$pedido->controle();
?>
