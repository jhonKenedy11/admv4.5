<?php
/**
 * @package   astec
 * @name      p_nota_fiscal_boleto_bancario
 * @version   4.5.00
 * @link      http://www.admservice.com.br/
 */

// Evita que usuários acessem este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");

Class p_nota_fiscal_boleto_bancario extends c_nota_fiscal {

    public $smarty    = NULL;
    public $parm_get  = NULL;
    public $parm_post = NULL;
    public $dados     = NULL;

    function __construct() {
        $this->parm_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parm_get  = filter_input_array(INPUT_GET,  FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->smarty->assign('pathJs',      ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap',   ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass',    ADMclass);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet',   ADMhttpCliente . '/../sweetalert2');
    }

    function mostrar($id = null) {
        if ($id != null){
            $this->setId($id);
            $result             = $this->select_nota_fiscal();
            $url_original       = strtolower($result[0]['PATHDANFE']);
            $numero_nota_fiscal = $result[0]['NUMERO'];
            $numero_pedido      = $result[0]['DOC'];
            $pessoa             = $result[0]['PESSOA'];

            //Ajuste realizado para teste local
            $base = rtrim(ADMhttpCliente, '/');
            // remove tudo depois da última barra
            $base = preg_replace('#/[^/]+$#', '', $base);
            $path = parse_url($url_original, PHP_URL_PATH);
            $danfe = $base . $path;
       }

       // Parametro para verificar se o boleto será gerado automaticamente
       $gera_boleto_automatico = $result[0]['GERA_BOLETO_AUTOMATICO'] ?? 'S';
       $this->smarty->assign('gera_boleto_automatico', $gera_boleto_automatico);
       
        $this->smarty->assign('id', $id);
        $this->smarty->assign('numero_pedido', $numero_pedido);
        $this->smarty->assign('numero_nota_fiscal', $numero_nota_fiscal);
        $this->smarty->assign('pessoa', $pessoa);
        $this->smarty->assign('danfe', $danfe);
        $this->smarty->assign('path_cliente', ADMhttpCliente);
        $this->smarty->display('nota_fiscal_boleto_bancario.tpl');
        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
    }

    function enviarEmail() {

        $id_nota_fiscal = $this->parm_post['id_nota_fiscal'];
        $numero_nota_fiscal = $this->parm_post['numero_nota_fiscal'];
        $numero_pedido = $this->parm_post['numero_pedido'];
        $pessoa = $this->parm_post['pessoa'];

        $this->enviaNotaBoleto($id_nota_fiscal, $numero_nota_fiscal, $numero_pedido, $pessoa);   
    }
}

// Auto-execução quando incluído pelo index.php
if (isset($_GET['form']) && $_GET['form'] === 'nota_fiscal_boleto_bancario') {
    $form = new p_nota_fiscal_boleto_bancario();
    $form->mostrar(isset($_GET['id']) ? $_GET['id'] : null);
}

if (isset($_POST['form']) && $_POST['form'] === 'nota_fiscal_boleto_bancario' && isset($_POST['submenu']) && $_POST['submenu'] === 'enviar_email') {
    $form = new p_nota_fiscal_boleto_bancario();
    $form->enviarEmail();
}
