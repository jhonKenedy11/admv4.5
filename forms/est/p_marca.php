<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_marca.php");

Class p_marca extends c_marca {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    //construtor
    function __construct() {
        @set_exception_handler(array($this, 'exception_handler'));
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte .   "/template/est";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Classe");
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setMarca(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
    }//construtor

    //controle
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstMarca', 'I')) 
                {
                  $this->desenharCadastroMarca();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstMarca', 'A')) 
                {
                    $this->buscar_marca();
                    $this->desenharCadastroMarca();
                }
                break;
            case 'inclui':
                if ($this->existeMarca()) 
                {
                    $this->m_submenu = "cadastrar";
                    $this->desenharCadastroMarca("Já existe marca com este código, por favor digite outro código", "alerta");
                } else {
                    $this->incluirMarca()
                    ? $this->mostrarMarca(msgAdd.' ==> Marca: '.$this->__get('marca'), typSuccess)
                    : $this->desenharCadastroMarca(msgNotAdd, typError);
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstMarca', 'A'))
                {
                    $this->alterarMarca()
                    ? $this->mostrarMarca(msgUpdate.' ==> Marca: '.$this->__get('marca'), typSuccess)
                    : $this->desenharCadastroMarca(msgNotUpdate, typAlert);
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstMarca', 'E'))
                {
                    $this->excluirMarca()
                    ? $this->mostrarMarca(msgDelete.' ==> Marca: '.$this->__get('marca'), typSuccess)
                    : $this->mostrarMarca(msgNotDelete.' ==> Marca: '.$this->__get('id'), typAlert);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstMarca', 'C'))
                {
                    $this->mostrarMarca('');
                }
        }
    }//controle
    
    //exibir leiaute
    function desenharCadastroMarca($mensagem = NULL, $tipoMsg = '') {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getMarca());
        $this->smarty->assign('descricao',  "'" . $this->getDescricao().  "'");

        $this->smarty->display('marca_cadastro.tpl');
    }

    function mostrarMarca($mensagem, $tipoMsg = '') {

        $lanc = $this->select_marca_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('marca_mostra.tpl');
    }

}

// Rotina principal - instacia objeto
$marca = new p_marca();

$marca->controle();
?>
