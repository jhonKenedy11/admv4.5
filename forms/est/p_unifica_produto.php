<?php
/**
 * @package   astec
 * @name      p_unifica_produto
 * @version   4.3.20
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio 
 * @date      27/09/2021
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto.php");


//Class p_unifica_produto_estoque
Class p_unifica_produto extends c_produto {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
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
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->cod_permanecer=(isset($parmGet['codPermanecer']) ? $parmGet['codPermanecer'] : (isset($parmPost['codPermanecer']) ? $parmPost['codPermanecer'] : ''));
        $this->desc_permanecer=(isset($parmGet['descPermanecer']) ? $parmGet['descPermanecer'] : (isset($parmPost['descPermanecer']) ? $parmPost['descPermanecer'] : ''));
        $this->cod_retirar=(isset($parmGet['codRetirar']) ? $parmGet['codRetirar'] : (isset($parmPost['codRetirar']) ? $parmPost['codRetirar'] : ''));
        $this->desc_retirar=(isset($parmGet['descRetirar']) ? $parmGet['descRetirar'] : (isset($parmPost['descRetirar']) ? $parmPost['descRetirar'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'inclui':
                $this->unificaProduto($this->cod_permanecer, $this->cod_retirar)
                ? $this->mostraBaixaEstoque(msgUpdate, typSuccess)
                : $this->mostraBaixaEstoque(msgNotAdd." Unicação produto não realizado!!", typError);
                break;
            default:
                $this->mostraBaixaEstoque('');
               
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function mostraBaixaEstoque($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);

        $this->smarty->display('unifica_produto_mostra.tpl');
    }


//fim mostraBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new p_unifica_produto();

$consultas->controle();
?>
