<?php
/**
 * @package   astec
 * @name      p_mdfe_imprime
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@hotmail.com>
 * @date      25/11/2022
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_manifesto_fiscal.php");

//Class p_mdfe_imprime
Class p_mdfe_imprime extends c_manifesto_fiscal {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    private $m_msg = NULL;
    public $smarty = NULL;

//---------------------------------------------------------------
//---------------------------------------------------------------
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
        $this->m_submenu = $this->parmPost['submenu'];
        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathCliente', ADMhttpCliente);

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        
    }

    function printDamdfe($id) {
        //quando imprime pelo mostra alimenta o id pelo GET
        if($id == null || $id = ''){
            $id = $this->parmGet["id"];
        }
        $damdfe = '';
        if ($damdfe==''):
            $this->setId($id);
            $result = c_manifesto_fiscal::selectManifestoFiscal($id, null);
            $damdfe = strtolower($result[0]['PATHDAMDFE']);
            $numNf = $result[0]['NUMERO'];
            $numPedido = $result[0]['DOC'];
        endif;
        
        $this->smarty->assign('id', $id);
        $this->smarty->assign('damdfe', $damdfe);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        
        $this->smarty->display('manifesto_fiscal_mostra_damdfe.tpl');
        
    }
}