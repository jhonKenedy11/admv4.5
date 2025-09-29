<?php

/**
 * @package   astecv3
 * @name      p_produto_validade_fiscal
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      05/01/2017
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/est/c_produto_estoque.php");


//Class p_produto_validade_fiscal
Class p_produto_validade extends c_produto_estoque {

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
        $this->smarty->assign('titulo', "Produto por data de validade");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5  ]"); 
        $this->smarty->assign('disableSort', "[ 0 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/est/s_nota_fiscal.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
    $this->mostraConsolidacao('');
} // fim controle



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraConsolidacao($mensagem){

    $par = explode("|", $this->m_letra);


    $lanc = $this->selectProdutoEstoqueLetra($this->m_letra, "V");
	
    
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', "");
    $this->smarty->assign('subMenu', "");
    $this->smarty->assign('lanc', $lanc);

        
//    // dados para o grafico Debitos e Creditos
//    if (is_array($reg_lanc)):
//        $arrLabel = "[";
//        $arrPag = "[";
//        $arrRec = "[";
//        $totalPag = 0;
//        $totalRec = 0;
//        $dataAnt = $reg_lanc[0]['PAGAMENTO'];
//        for ($i=0; $i < count($reg_lanc); $i++){
//            if ($dataAnt != $reg_lanc[$i]['PAGAMENTO']):
//                $arrLabel .= "'".$dataAnt."',";
//                $arrPag .= (int)($totalPag).",";
//                $arrRec .= (int)($totalRec).",";
//                $dataAnt = $reg_lanc[$i]['PAGAMENTO'];
//                //$totalPag = 0;
//                //$totalRec = 0;
//            endif;
//            if ($reg_lanc[$i]['TIPOLANCAMENTO']=="RECEBIMENTO"):
//                $totalRec += $reg_lanc[$i]['TOTAL'];
//            else:    
//                $totalPag += $reg_lanc[$i]['TOTAL'];
//            endif;
//        }
//        $arrLabel .= "'".$dataAnt."']";
//        $arrPag .= (int)($totalPag)."]";
//        $arrRec .= (int)($totalRec)."]";
//    else:
//        $arrLabel .= "[]";
//        $arrPag .= "[]";
//        $arrRec .= "[]";
//        
//    endif;
    $this->smarty->assign('label', $arrLabel);
    $this->smarty->assign('pag', $arrPag);
    $this->smarty->assign('rec', $arrRec);
    

    $this->smarty->display('rel_produto_validade.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$produto_validade = new p_produto_validade();


$produto_validade->controle();
 
  
?>
