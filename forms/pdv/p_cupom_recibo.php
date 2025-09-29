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
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");

//Class P_situacao
Class p_cupom_recibo extends c_pedidoVenda {

    private $m_submenu          = NULL;
    private $m_origem           = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    public $smarty              = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
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
        $this->smarty->template_dir = ADMraizFonte . "/template/pdv";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";


        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_origem = $parmPost['origem'];
        $this->m_letra = $parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

                // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');

        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmGet['parm']) ? $parmGet['parm'] : '');


    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraPedidoImprime('');
                }
        }
    }

   

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedidoImprime($mensagem=NULL, $tipoMsg=NULL) {
        
        
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $lanc = $this->select_pedidoVenda();
        $lancItem = $this->select_pedido_item_id('9'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
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
        

        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('fin', $fin);

        
        $this->smarty->display('cupom_recibo.tpl');
    }

//fim desenhaPedidoConferencia
//-------------------------------------------------------------


//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_cupom_recibo();

$pedido->controle();
?>
