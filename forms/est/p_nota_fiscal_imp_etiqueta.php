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
require_once($dir . "/../../forms/est/p_nfephp_40.php");
require_once($dir . "/../../forms/est/p_nfephp_imprime_danfe.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir."/../../bib/c_date.php");

//Class P_situacao
Class p_nota_fiscal_imp_etiqueta extends c_nota_fiscal {

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
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
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
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : '');
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : '');
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
                $this->mostraEtiquetaImprime('');
                
        }
    }

    //-------------------------------------------------------------
 

    function mostraEtiquetaImprime($mensagem=NULL, $tipoMsg=NULL) {
            
            
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $lanc = $this->select_nota_fiscal();
        $empresa = $this->busca_dadosEmpresaCC($lanc[0]['CENTROCUSTO']);

        // CLIENTE
        $consulta = new c_banco();
        $sql = "SELECT * FROM FIN_CLIENTE ";
        $sql.= "WHERE CLIENTE = ".$lanc[0]['PESSOA'];
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $cliente = $consulta->resultado;

        //TRANSPORTADORA
      
        $consulta = new c_banco();
        $sql = "SELECT NOME FROM FIN_CLIENTE ";
        $sql.= "WHERE CLIENTE = ".$lanc[0]['TRANSPORTADOR'];
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $transportadora = $consulta->resultado;
     
        $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('cliente', $cliente);
        $this->smarty->assign('transportadora', $transportadora);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('qtdeVol', $lanc[0]['VOLUME']);

        $this->smarty->display('nota_fiscal_etiqueta.tpl');
    }

    
//-------------------------------------------------------------


//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$etiqueta = new p_nota_fiscal_imp_etiqueta();

$etiqueta->controle();
?>
