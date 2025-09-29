<?php
/****************************************************************************
*Cliente...........:
*Contratada........: admService
*Desenvolvedor.....: Marcio Sergio da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: p_os_baixa - cadastro de os_baixas PAGES
*Ultima Atualizacao: 21/08/15 
****************************************************************************/
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../bib/reader.php");
require_once($dir . "/../../bib/c_tools.php");
require_once($dir . "/../../bib/c_date.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/crm/c_conta.php");

//Class p_os_baixa
Class p_relatorio_gerencial extends c_produto_estoque {

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

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = $this->parmPost['submenu'];
        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);


        // include do javascript
        // include ADMjs . "/est/s_relatorio_gerencial.js";
}


//---------------------------------------------------------------
//---------------------------------------------------------------
function controle() {
        switch ($this->m_submenu) {
            case 'consolidacao':
                //if ($this->verificaDireitoUsuario('EstRelatorioGerencial', 'C')) {
                //echo "passou".$this->m_letra;
                    $this->grava_excel('');
               // }//if
                break;
            default:
               // if ($this->verificaDireitoUsuario('EstRelatorioGerencial', 'C')) {
                    $this->mostraRelatorioGerencial('');
                //}
        }
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
function grava_excel($mensagem){

//    error_reporting(E_ALL);
//    ini_set('display_errors', 1);  
    
    $toolsDate = new c_date();
    $arquivo = 'consolidacao-'.date("d-m-Y");  
    header("Pragma: public");
    header("Expires: 0");
    header("Content-Type:application/vnd.ms-excel; charset=UTF-8");
    header("Content-type:application/x-msexcel; charset=UTF-8");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=$arquivo.xls");
    header("Content-Transfer-Encoding: binary ");   

    // Criamos uma tabela HTML com o formato da planilha  

    $html = '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';  
    $html .= '<table>';  
    $html .= '<tr>';  
    $html .= '<td colspan="5"><b>Consolida&ccedil;&atilde;o Fiscal - Per&iacute;odo - ' .date('d/m/Y', strtotime($this->m_par[0])).' - '.date('d/m/Y', strtotime($this->m_par[1])).'</b></td>';  
    $html .= '</tr>';  
    $html .= '<tr>';  
    $html .= '<td></td>';  
    $html .= '</tr>';   
    $html .= '</table>';  


    
    //echo $html; 
    

    session_start(); 
    $this->carrregaVarsConfig(0);
    
    $this->from_array($_SESSION['user_array']);
    $this->empresa($this->m_empresanome, $this->m_usernome);
    
    $html .= '<table width="100%"  border=1 cellpadding=2 cellspacing=2 align=center>';
    

                $html .= '<tr>';
		
		$html .= '<td align=center ><b>NUM NF </b></td>';
		$html .= '<td align=center ><b>DATA EMISS&Atilde;O</b></td>';
		$html .= '<td align=center ><b>DATA RECEBIMENTO</b></td>';
		$html .= '<td align=center ><b>NAT. OPERA&Ccedil;&Atilde;O</b></td>';
		$html .= '<td align=center ><b>COD. FABRICANTE</b></td>';
		$html .= '<td align=center ><b>DESCRI&Ccedil;&Atilde;O</b></td>';
                $html .= '<td align=center ><b>PROJETO</b></td>';
		$html .= '<td align=center ><b>RAT APLICADO</b></td>';
		$html .= '<td align=center ><b>NUM LOTE DEV.</b></td>';
		$html .= '<td align=center ><b>DATA LOTE DEV</b></td>';
		$html .= '<td align=center ><b>NUM NF DEV</b></td>';
		$html .= '<td align=center ><b>RASTREIO PE&Ccedil;A</b></td>';
		$html .= '<td align=center ><b>LOCALIZA&Ccedil;&Atilde;O</b></td>';
		$html .= '<td align=center ><b>DEV P/ EMPRESA</b></td>';
		$html .= '<td align=center ><b>APLICADO</b></td>';
		$html .= '<td align=center ><b>OBSERVA&Ccedil;&Atilde;O</b></td>';
                $html .= '</tr>';


    if (isset($this->m_letra) and ($this->m_letra!='') and ($this->m_letra!='|')) {
            $cons = $this->select_consolidacao_fiscal($this->m_letra);
            for ($i=0; $i < count($cons); $i++){
		$html .= "<tr>";

		$html .= "<td> ".$cons[$i]['NUMERO']." </td>";
		$html .= "<td> ".date('d/m/Y H:m:s', strtotime($cons[$i]['EMISSAO']))." </td>";
		$html .= "<td> ".date('d/m/Y H:m:s', strtotime($cons[$i]['DATACONFERENCIA']))." </td>";
		$html .= "<td> ".$cons[$i]['NATOPERACAO']." </td>";
		$html .= "<td> ".$cons[$i]['CODFABRICANTE']." </td>";
		$html .= "<td> ".$cons[$i]['DESCRICAO']." </td>";
		$html .= "<td> ".$cons[$i]['CONTRATO']." </td>";
		$html .= "<td> ".$cons[$i]['NUMCHAMADOSOLICITANTE']." </td>";
		$html .= "<td> ".$cons[$i]['NUMLOTE']." </td>";
                if ($cons[$i]['DATAENTREGA'] != ''){
                    $html .= "<td> ".date('d/m/Y', strtotime($cons[$i]['DATAENTREGA']))." </td>";
                }else{
                    $html .= "<td> ".$cons[$i]['DATAENTREGA']." </td>";
                }		
		$html .= "<td> ".$cons[$i]['NUMNF']." </td>";
		$html .= "<td> ".$cons[$i]['NOMEREDUZIDO']." </td>";
		$html .= "<td> ".$cons[$i]['LOCALIZACAO']." </td>";
                if ($cons[$i]['DEVOLUCAOUSERPRODUTO'] != '0000-00-00 00:00:00'){
                    $html .= "<td> ".date('d/m/Y', strtotime($cons[$i]['DEVOLUCAOUSERPRODUTO']))." </td>";
                }else{
                    $html .= "<td>  </td>";
                }
                if ($cons[$i]['APLICADO'] =='S'){
                    $html .= "<td> S - Sim </td>";
                }else{
                    $html .= "<td> N - N&atilde;o </td>";
                }//if
		$html .= "<td> ".$cons[$i]['OBS']." </td>";
		$html .= "</tr>";
            }//fot
            $html .= "</table>";

    }//if
    
    
    
    
    echo $html;
    exit;
    
} //fim grava_excel

function mostraRelatorioGerencial($mensagem){

        $this->smarty->assign('dirCliente', $this->nomeCliente);
        $this->smarty->assign('username', $this->m_usernome);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        
        $dataIni = date('d/m/Y', strtotime('first day of this month'));
        $dataFim = date('d/m/Y', strtotime('last day of this month'));

        
        if($this->m_par[6] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[6]);
    
        if($this->m_par[7] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        //	$data = mktime(0, 0, 0, $mes, 1, $ano);
        //	$this->smarty->assign('dataFim', date("d",$data-1).date("/m/Y"));
        }
        else $this->smarty->assign('dataFim', $this->m_par[7]);
            
        $this->smarty->display('relatorio_gerencial_mostra.tpl');
}

//-------------------------------------------------------------
}	//	END OF THE CLASS


// Rotina principal - cria classe
  $relatorio_gerencial = new p_relatorio_gerencial();

  $relatorio_gerencial->controle();
 
  
?>
