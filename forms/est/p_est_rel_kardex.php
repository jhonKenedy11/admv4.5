<?php
/**
 * @package   astec
 * @name      p_rel_compras
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silvao<marcio.sergio@admservice.com.br>
 * @date      27/04/2018
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_estoque_rel.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
// require_once($dir."/../../class/crm/c_conta.php");

//Class P_produto
Class p_est_rel_kardex extends c_estoque_rel {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    private $m_grupo = null;
    private $m_sit   = null;
    private $m_tipo  = null;
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
        $this->m_grupo=(isset($parmGet['grupoSelected']) ? $parmGet['grupoSelected'] : (isset($parmPost['grupoSelected']) ? $parmPost['grupoSelected'] : ''));
        $this->m_sit  =(isset($parmGet['sitLSelected']) ? $parmGet['sitLSelected'] : (isset($parmPost['sitLSelected']) ? $parmPost['sitLSelected'] : ''));
        $this->m_tipo =(isset($parmGet['tipoLSelected']) ? $parmGet['tipoLSelected'] : (isset($parmPost['tipoLSelected']) ? $parmPost['tipoLSelected'] : ''));

        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Kardex");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Kardex");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
        // include do javascript
        // include ADMjs . "/est/s_est.js";
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'relatorioKardexSintetico':
                $this->mostraRelatorioKardexSintetico('');
            break;
            case 'relatorioKardex': 
                $this->mostraRelatorioKardex('');
            break;
            default:
                    
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraRelatorioKardex($mensagem){
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    $par      = explode("|", $this->m_letra);
    $parGrupo = explode("|", $this->m_grupo);
    $parSit   = explode("|", $this->m_sit);
    $parTipo  = explode("|", $this->m_tipo);

    $lanc = $this->select_relatorio_mov_estoque($this->m_letra);
    
    $saldoIni = $this->saldo_inicial_entrada($par[0],$par[3]);
    $saldoIniSaida = $this->saldo_inicial_saida($par[0],$par[3]);
  
    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('saldoIni', $saldoIni[0]['QUANTIDADE']);
    $this->smarty->assign('saldoIniSaida', $saldoIniSaida[0]['QUANTIDADE']);
    $this->smarty->assign('periodoIni', $par[0]);
    $this->smarty->assign('periodoFim', $par[1]);
    
    $this->smarty->display('est_rel_kardex_analitico.tpl');
    
}

function mostraRelatorioKardexSintetico($mensagem){
     $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    $par      = explode("|", $this->m_letra);
    $parGrupo = explode("|", $this->m_grupo);

    $lanc = $this->select_relatorio_kardex_sintetico($par, $parGrupo);
  
    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('periodoIni', $par[0]);
    $this->smarty->assign('periodoFim', $par[1]);
    
    $this->smarty->display('est_rel_kardex_sintetico.tpl');
    
}

}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_rel_compras(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$rel_kardex = new p_est_rel_kardex();

$rel_kardex->controle();
?>
