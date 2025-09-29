<?php

/**
 * @package   astecv3
 * @name      p_genero
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      30/12/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_saldo.php");
include_once($dir."/../../class/fin/c_lancamento.php");


//Class P_FLUXO_CAIXA
Class p_genero extends c_lancamento {

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

        // include do javascript
        // include ADMjs . "/fin/s_lancamento.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
    $this->mostraGenero('');
} // fim controle



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraGenero($mensagem){

    $par = explode("|", $this->m_letra);

    if ((isset($this->m_letra)) or ($this->m_letra != '')):
        $lanc = $this->select_lancamento_letra($this->m_letra,2);
    endif;

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('lanc', $lanc);

/*        
    // dados para o grafico Debitos e Creditos
    if (is_array($reg_lanc)):
        $arrLabel = "[";
        $arrPag = "[";
        $arrRec = "[";
        $totalPag = 0;
        $totalRec = 0;
        $dataAnt = $reg_lanc[0]['PAGAMENTO'];
        for ($i=0; $i < count($reg_lanc); $i++){
            if ($dataAnt != $reg_lanc[$i]['PAGAMENTO']):
                $arrLabel .= "'".$dataAnt."',";
                $arrPag .= (int)($totalPag).",";
                $arrRec .= (int)($totalRec).",";
                $dataAnt = $reg_lanc[$i]['PAGAMENTO'];
                //$totalPag = 0;
                //$totalRec = 0;
            endif;
            if ($reg_lanc[$i]['TIPOLANCAMENTO']=="RECEBIMENTO"):
                $totalRec += $reg_lanc[$i]['TOTAL'];
            else:    
                $totalPag += $reg_lanc[$i]['TOTAL'];
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
    

    
    // dados para o grafico Saldo
    if (is_array($reg_saldo)):
        $arrLabelSaldo = "['".$reg_saldo[0]['DATA'];
        $arrSaldo = "[".($reg_saldo[0]['SALDO']);
        for ($i=1; $i < count($reg_saldo); $i++){
                $arrLabelSaldo .= "','".$reg_saldo[$i]['DATA'];
                $arrSaldo .= ",".($reg_saldo[$i]['SALDO']);
        }
        $arrLabelSaldo .= "']";
        $arrSaldo .= "]";
    else:
        $arrLabelSaldo = "[]";
        $arrSaldo = "[]";
        
    endif;
    $this->smarty->assign('labelSaldo', $arrLabelSaldo);
    $this->smarty->assign('saldo', $arrSaldo);
*/    
    $this->smarty->display('genero_analitico_mostra.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$genero = new p_genero();


$genero->controle();
 
  
?>
