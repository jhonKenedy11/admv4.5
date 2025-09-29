<?php

/**
 * @package   astec
 * @name      p_atividade
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      11/04/2016
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_atividade.php");

//Class p_atividade
Class p_atividade extends c_atividade {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
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
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

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
        $this->setAtividade(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        // include do javascript
        // include ADMjs . "/crm/s_atividade.js";
    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('FinAtividade', 'I')) {
                    $this->desenhaCadastroAtividade();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinAtividade', 'A')) {
                    $this->busca_atividade();
                    $this->desenhaCadastroAtividade();
                }
                break;
            case 'inclui':
                if ($this->existeAtividade()) {
                    $this->m_submenu = "cadastrar";
                    $this->desenhaCadastroAtividade("Já existe atividade com este código, por favor altere a atividade", "alerta");
                } else {
                    $this->incluiAtividade()
                    ? $this->mostraAtividade(msgAdd.' ==> Atividade: '.$this->__get('atividade'), typSuccess)
                    : $this->desenhaCadastroAtividade(msgNotAdd, typError);
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinBanco', 'A')){
                    $this->alteraAtividade()
                    ? $this->mostraAtividade(msgUpdate.' ==> Atividade: '.$this->__get('atividade'), typSuccess)
                    : $this->desenhaCadastroAtividade(msgNotUpdate, typAlert);
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FinAtividade', 'E')){
                    $this->excluiAtividade()
                    ? $this->mostraAtividade(msgDelete.' ==> Atividade: '.$this->__get('atividade'), typSuccess)
                    : $this->mostraAtividade(msgNotDelete.' ==> Atividade: '.$this->__get('id'), typAlert);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FinAtividade', 'C')) {
                    $this->mostraAtividade('');
                }
        }
    }

// fim controle

    /**
     * <b> Desenha cadastro Atividade. </b>
     * @param String $mensagem mensagem que ira apresentar na tela
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroAtividade($mensagem = NULL, $tipoMsg = '') {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getAtividade());
        $this->smarty->assign('descricao',  "'" . $this->getDescricao().  "'");

        $this->smarty->display('atividade_cadastro.tpl');
    }

//fim desenhaCadastroAtividade

    /**
     * <b> Listagem das Atividade. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostraAtividade($mensagem, $tipoMsg = '') {

            $lanc = $this->select_atividade_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('atividade_mostra.tpl');
    }

//fim mostraAtividade
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$atividade = new p_atividade();

$atividade->controle();
?>
