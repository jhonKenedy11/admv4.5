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
Class p_rel_curva_ABC extends c_estoque_rel {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    public $m_grupo = null;
    public $m_par   = null;
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

        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"){
            $this->smarty->assign('titulo', "Kardex");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        } else{
            $this->smarty->assign('titulo', "Kardex");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        }
    
        // include do javascript
        // include ADMjs . "/est/s_est.js";
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            default:
            $this->relatorioCurvaABC(); 
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
function relatorioCurvaABC(){
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    $lanc = $this->select_relatorio_curva_abc() ?? [];

    $totalValor = 0;
    $totalQuant = 0;
    $totalNumVendas = 0;
    foreach($lanc as $key => $value){
        $totalValor += $value['VALOR'];
        $totalQuant += $value['QUANT'];
        $totalNumVendas += $value['NUMVENDAS'];
    }
    $participacao = 0;
    $acumulado = 0;
    foreach($lanc as $key => $value){
        $lanc[$key]['COUNT'] = $key+1;
        switch($this->m_par[7]){ // opcao escolhida 
            case 'QUANT':
                $participacao = $value['QUANT']/$totalQuant;
            break;
            case 'VALOR':
                $participacao = $value['VALOR']/$totalValor;
            break;
            case 'NUMVENDAS':
                $participacao = $value['NUMVENDAS']/$totalNumVendas;
            break;
            default:

        }
        $acumulado += $participacao;
        if($acumulado <= 0.35){
            $lanc[$key]['CLASSIFICACAO'] = 'A';    
        }else if($acumulado > 0.35 && $acumulado <= 0.67 ){
            $lanc[$key]['CLASSIFICACAO'] = 'B';  
        }else{
            $lanc[$key]['CLASSIFICACAO'] = 'C';  
        }
        $lanc[$key]['PARTICIPACAO'] = $participacao;
        $lanc[$key]['ACUMULADO'] = $acumulado;
        
    }

    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('periodoIni', $this->m_par[0]);
    $this->smarty->assign('periodoFim', $this->m_par[1]);
    
    $this->smarty->display('relatorio_curva_abc.tpl');
}

}

//	END OF THE CLASS
// Rotina principal - cria classe
$rel_curva_abc = new p_rel_curva_ABC();

$rel_curva_abc->controle();
?>
