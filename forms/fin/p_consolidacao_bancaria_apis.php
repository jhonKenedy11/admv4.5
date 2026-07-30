<?php
/**
 * Form: Consolidação Bancária — APIs (consultas e retorno JSON).
 * Local: forms/fin/p_consolidacao_bancaria_apis.php
 *
 * Roteamento: index.php?mod=fin&form=consolidacao_bancaria_apis
 */

if (!defined('ADMpath')) {
    exit();
}

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/fin/c_consolidacao_bancaria_apis.php");

class p_consolidacao_bancaria_apis extends c_consolidacao_bancaria_apis
{
    public $parm_post;
    public $parm_get;
    public $smarty;
    public $dados;
    public $submenu;

    public function __construct()
    {
        @set_exception_handler(array($this, 'exception_handler'));

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_array'])) {
            c_user::from_array($_SESSION['user_array']);
        }

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parm_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parm_get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $this->smarty = new Smarty();
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('pathSweet', ADMraizCliente . '/sweet/dist');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('titulo', 'Consolidação Bancária — APIs');
        $this->smarty->assign('pathCss', ADMhttpBib . '/css/fin');

        $this->dados = $this->parm_post['dados'] ?? '';
        $this->submenu = $this->parm_post['submenu'] ?? '';
    }

    public function exception_handler($exception)
    {
        echo "Erro: " . $exception->getMessage();
        echo "Arquivo: " . $exception->getFile();
        echo "Linha: " . $exception->getLine();
        exit();
    }

    function controle() {
        switch($this->submenu) {
            case 'processaTitulosSelecionados':
                $this->processaTitulosSelecionados($this->dados);
                break;
            default:
                $this->mostraConsolidacaoBancariaApis();
                break;
        }
    }


    public function mostraConsolidacaoBancariaApis()
    {
        // Bancos API
        $bancos = $this->getBancos();
        $this->smarty->assign('bancos_api', $bancos);

        // Contas API
        $contas = $this->getContas();
        $this->smarty->assign('contas_api', $contas);

        // Centros de custo API
        $centros_custo = $this->getCentroCusto();
        $this->smarty->assign('centros_custo_api', $centros_custo);

        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        $this->smarty->display('consolidacao_bancaria_apis_mostra.tpl');
    }
}

$p = new p_consolidacao_bancaria_apis();
$p->controle();
