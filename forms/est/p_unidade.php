<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_unidade.php");

Class p_unidade extends c_unidade {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    function __construct() {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');

        $this->smarty->assign('titulo', "Unidade");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]");
        $this->smarty->assign('disableSort', "[ 3 ]");
        $this->smarty->assign('numLine', "25");

        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setUnidade(isset($parmPost['unidade']) ? $parmPost['unidade'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setAtivo(isset($parmPost['ativo']) ? $parmPost['ativo'] : 'S');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstUnidade', 'I')) {
                    $this->desenharCadastroUnidade();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstUnidade', 'A')) {
                    $this->buscar_unidade();
                    $this->desenharCadastroUnidade();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstUnidade', 'I')) {
                    if ($this->existeUnidade()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenharCadastroUnidade("Já existe unidade com esta sigla, por favor informe outra.", "alerta");
                    } else {
                        $this->incluirUnidade()
                            ? $this->mostrarUnidade('Registro inserido.', 'Sucesso')
                            : $this->desenharCadastroUnidade('Erro ao inserir registro.', 'Alerta');
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstUnidade', 'A')) {
                    $this->alterarUnidade()
                        ? $this->mostrarUnidade('Registro salvo.', 'Sucesso')
                        : $this->desenharCadastroUnidade('Erro ao alterar registro.', 'Alerta');
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstUnidade', 'E')) {
                    if ($this->unidadeEmUso()) {
                        $this->mostrarUnidade('Unidade vinculada a produto(s). Não é possível excluir.', 'Alerta');
                    } elseif ($this->excluirUnidade()) {
                        $this->mostrarUnidade('Registro excluído.', 'Sucesso');
                    } else {
                        $this->mostrarUnidade('Erro ao excluir registro.', 'Alerta');
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstUnidade', 'C')) {
                    $this->mostrarUnidade('');
                }
        }
    }

    function desenharCadastroUnidade($mensagem = NULL, $tipoMsg = '') {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('unidade', $this->getUnidade());
        $this->smarty->assign('descricao', "'" . $this->getDescricao() . "'");
        $this->smarty->assign('ativo', $this->getAtivo());

        $this->smarty->display('unidade_cadastro.tpl');
    }

    function mostrarUnidade($mensagem, $tipoMsg = '') {
        $lanc = $this->select_unidade_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('unidade_mostra.tpl');
    }
}

$unidade = new p_unidade();
$unidade->controle();
?>
