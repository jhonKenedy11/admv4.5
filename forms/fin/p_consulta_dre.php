<?php

/****************************************************************************
*Cliente...........:
*Contratada........: ADMService
*Desenvolvedor.....: Marcio Sergio da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: P_CONSULTA_DRE - Consulta de receitas ou despesas -DRE
*Ultima Atualizacao: 17/08/2017
****************************************************************************/

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_lancamento.php");



//Class P_LANCAMENTO
Class p_consulta_dre extends c_lancamento {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
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
        $this->smarty->assign('titulo', "Contas Bancarias");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5  ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // include do javascript
        // include ADMjs . "/fin/s_lancamento.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
 
	if ($this->verificaDireitoUsuario('FinLancamento', 'C')){
		$this->mostraLancamentos('');}
	
} // fim controle


//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraLancamentos($mensagem){
include $this->js."/fin/s_lancamento.js";

	
	// busca dados para motrar na consulta
    if ((isset($this->m_letra)) or ($this->m_letra != '')){

    	$lanc = $this->select_lancamento_letra($this->m_letra, 1);
    }
	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('lanc', $lanc);
 
     // dados para o grafico
    if (is_array($lanc)):
        $arrLabel = "[";
        $arrPag = "[";
        $arrRec = "[";
        $totalPag = 0;
        $totalRec = 2000;
        $dataAnt = $lanc[0]['PAGAMENTO'];
        for ($i=0; $i < count($lanc); $i++){
            if ($dataAnt != $lanc[$i]['PAGAMENTO']):
                $arrLabel .= "'".$dataAnt."',";
                $arrPag .= (int)($totalPag).",";
                $arrRec .= (int)($totalRec).",";
                $dataAnt = $lanc[$i]['PAGAMENTO'];
                //$totalPag = 0;
                //$totalRec = 0;
            endif;
            if ($lanc[$i]['TIPOLANCAMENTO']=="RECEBIMENTO"):
                $totalRec += $lanc[$i]['TOTAL'];
            else:    
                $totalPag += $lanc[$i]['TOTAL'];
            endif;
        }
        $arrLabel .= "'".$dataAnt."']";
        $arrPag .= (int)($totalPag)."]";
        $arrRec .= (int)($totalRec)."]";
    else:
        $arrLabel .= "[]";
        $arrPag .= "[]";
        $arrRec .= "[]";
        
    endif;
    $this->smarty->assign('label', $arrLabel);
    $this->smarty->assign('pag', $arrPag);
    $this->smarty->assign('rec', $arrRec);
  
    
    $this->smarty->display('consulta_dre.tpl');
    //$this->smarty->display('genero_rel.tpl');
	

} //fim mostraLancamentos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancamento = new p_consulta_dre();

$lancamento->controle();
 
  
?>
