<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/est/c_nota_fiscal_produto.php");
include_once($dir."/../../class/est/c_nota_fiscal.php");


Class p_nota_fiscal_periodo extends c_nota_fiscal {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmGet['letra']) ? $parmGet['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Vendas no período");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6, 7, 8  ]"); 
        $this->smarty->assign('disableSort', "[ 8 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/est/s_nota_fiscal.js";

}

function controle(){
    $this->mostraNFS('');
} 
function busca_dadosEmpresaCC($cc){
    $sql  = "SELECT * ";
    $sql .= "FROM amb_empresa ";
    $sql .= "WHERE (centrocusto = ".$cc.") ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();

    return $banco->resultado;
}

function mostraNFS($mensagem){

    $par = explode("|", $this->m_letra);
    $lanc = $this->select_vendas($par);
    $empresa = $this->busca_dadosEmpresaCC($par[0]);
    $dataHoraNow = date('d/m/Y H:i:s');
    $this->smarty->assign('lanc', $lanc); 
    $this->smarty->assign('empresa', $empresa);
    $this->smarty->assign('periodoIni',$par[3]);
    $this->smarty->assign('periodoFim',$par[4]);
    $this->smarty->assign('dataHoraNow', $dataHoraNow);
    
    $this->smarty->display('relatorio_vendas_periodo.tpl');
} 

}	//	END OF THE CLASS

// Rotina principal - cria classe
$nota_fiscal_periodo = new p_nota_fiscal_periodo();


$nota_fiscal_periodo->controle();
 
  
?>
