<?php

/**
 * @package   astecv3
 * @name      p_new_relatorio_lancamentos
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      13/12/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_saldo.php");
include_once($dir."/../../class/fin/c_lancamento.php");


//Class p_new_relatorio_lancamentos
Class p_rel_baixa_usuario extends c_lancamento {

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
    $this->mostraFluxo('');
} // fim controle



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraFluxo($mensagem){

    $par = explode("|", $this->m_letra);

    $fluxo = new c_saldo();
    $reg_saldo = $fluxo->saldoContaAtual($this->m_letra);
    if ($reg_saldo[0]['SALDO'] != ''){
            $saldoTotal = $reg_saldo[0]['SALDO'];
    }else {
            $saldoTotal = 0;	
    }


    $lancamento = new c_lancamento();
    $lancamento = $this->select_lancamento_letra($this->m_letra);
    $lanc = $lancamento;
	
	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
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
    
    $this->smarty->display('rel_baixa_usuario.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$rel_baixa_usuario = new p_rel_baixa_usuario();


$rel_baixa_usuario->controle();
 
  
?>
