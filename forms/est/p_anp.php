<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_anp.php");

Class p_anp extends c_anp {

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
        $this->setAnp(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
    }//construtor

    //controle
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstAnp', 'I')) 
                {
                  $this->desenharCadastroAnp();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstAnp', 'A')) 
                {
                    $this->buscar_anp();
                    $this->desenharCadastroAnp();
                }
                break;
            case 'inclui':
                if ($this->existeAnp()) 
                {
                    $this->m_submenu = "cadastrar";
                    $this->desenharCadastroAnp("Já existe Anp com este código, por favor digite outro código", "alerta");
                } else {
                    $this->incluirAnp()
                    ? $this->mostrarAnp(msgAdd.' ==> Anp: '.$this->__get('anp'), typSuccess)
                    : $this->desenharCadastroAnp(msgNotAdd, typError);
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstAnp', 'A'))
                {
                    $this->alterarAnp()
                    ? $this->mostrarAnp(msgUpdate.' ==> Anp: '.$this->__get('anp'), typSuccess)
                    : $this->desenharCadastroAnp(msgNotUpdate, typAlert);
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstAnp', 'E'))
                {
                    $this->excluirAnp()
                    ? $this->mostrarAnp(msgDelete.' ==> Anp: '.$this->__get('anp'), typSuccess)
                    : $this->mostrarAnp(msgNotDelete.' ==> Anp: '.$this->__get('id'), typAlert);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstAnp', 'C'))
                {
                    $this->mostrarAnp('');
                }
        }
    }//controle
    
    //exibir leiaute
    function desenharCadastroAnp($mensagem = NULL, $tipoMsg = '') {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getAnp());
        $this->smarty->assign('descricao',  "'" . $this->getDescricao().  "'");

        $this->smarty->display('anp_cadastro.tpl');
    }

    function mostrarAnp($mensagem, $tipoMsg = '') {

        $lanc = $this->select_anp_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('anp_mostra.tpl');
    }

}

// Rotina principal - instacia objeto
$anp = new p_anp();

$anp->controle();
?>
