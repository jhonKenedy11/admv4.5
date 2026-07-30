<?php

if (!defined('ADMpath')):
    exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/util/c_template_email.php");

class p_template_email extends c_template_email {

    private $m_submenu = null;
    private $m_letra = null;
    public $smarty = null;

    function __construct() {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('mod', 'util');
        $this->smarty->assign('form', 'template_email');

        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setParametro(isset($parmPost['parametro']) ? $parmPost['parametro'] : '');
        $this->setBody(isset($parmPost['body']) ? $parmPost['body'] : '');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('AmbTemplateEmail', 'I')) {
                    $this->desenhaCadastroTemplateEmail();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('AmbTemplateEmail', 'A')) {
                    $this->desenhaCadastroTemplateEmail();
                }
                break;
            case 'inclui':
                if ($this->select_template_email()) {
                    $this->m_submenu = "cadastrar";
                    $this->desenhaCadastroTemplateEmail("J&aacute; existe template com este c&oacute;digo.", "alerta");
                } else {
                    $this->mostrarTemplateEmail($this->incluirTemplate());
                }
                break;
            case 'altera':
                $this->alterarTemplate();
                $this->mostrarTemplateEmail('Registro salvo.');
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('AmbTemplateEmail', 'E')) {
                    $this->excluirTemplate();
                    $this->mostrarTemplateEmail('Registro exclu&iacute;do.');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('AmbTemplateEmail', 'C')) {
                    $this->mostrarTemplateEmail('');
                }
        }
    }

    function desenhaCadastroTemplateEmail($mensagem = null, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $lanc = $this->select_template_email();
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->display('template_email_cadastro.tpl');
    }

    function mostrarTemplateEmail($mensagem) {
        $lista = $this->select_template_email_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lista', $lista);

        $this->smarty->display('template_email_mostra.tpl');
    }
}

$template_email = new p_template_email();
$template_email->controle();
