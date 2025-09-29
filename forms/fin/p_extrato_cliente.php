<?php
/**
 * @package   astecv3
 * @name      p_extrato
 * @category  PAGES - p_extrato - Lancamento de receitas ou despesas
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/05/2016
 */

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_extrato.php");
include_once($dir."/../../bib/c_date.php");


//Class p_extrato
Class p_extrato extends c_extrato {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_par = NULL;
public $smarty = NULL;
public $mes = NULL;
public $centroCusto = NULL;
public $datavenc = NULL;
public $conta = NULL;

//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

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

        if(isset($parmPost['mes'])) {
            $this->mes = $parmPost['mes'];
            $dataIni = date("01/".$parmPost['mes']."/Y");
            $ano = date("Y");
            $diaFim = date("t", mktime(0,0,0,$parmPost['mes'],'01',$ano));
            $dataFim = date($diaFim."/".$parmPost['mes']."/Y");
        }    
        else {
            $this->mes = date("m");
            $dataIni = date("01/m/Y");
            $ano = date("Y");
            $diaFim = date("t", mktime(0,0,0,$this->mes,'01',$ano));
            $dataFim = date($diaFim."/m/Y");
        }    
    
        $cliente = $this->m_empresacliente =='' ? 0 : $this->m_empresacliente;
        $this->setPessoa($cliente);
        $this->setPessoaNome();
    
        $this->m_letra = "competencia|".$dataIni."|".$dataFim."|".$cliente."||1|B|2|P|R";
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('form', $this->parmPost['form']);
        $this->smarty->assign('titulo', "Lançamentos Financeiros");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]"); 
        $this->smarty->assign('disableSort', "[ 1 ]"); 
        $this->smarty->assign('numLine', "25"); 

}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
 * @name controle
 * @param VARCHAR submenu 
 * @return vazio
 */
function controle(){
        $this->mostraExtrato('');

} // fim controle

/**
* <b> Listagem de todas as registro cadastrados de tabela Lancamentos. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraExtrato($mensagem){

    
    if ($this->m_letra != ''){
    	$lanc = $this->select_extrato_letra($this->m_letra);
    }
	
    $this->smarty->assign('pessoa', '');
    $this->smarty->assign('genero', '');

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->assign('nome', "'".$this->getPessoaNome()."'");

    // MESES ANO
    for ($i = 1; $i <= 12; $i++) {
        $mes_ids[$i] = $i;
        $mes = date("Y")."-".$i;
        $mes_names[$i] = date("F/Y", strtotime($mes));
    }
    $mes = date("n");
    $this->smarty->assign('mes_ids', $mes_ids);
    $this->smarty->assign('mes_names', $mes_names);
    $this->smarty->assign('mes_id', $this->mes);


    // calculo totais
    $lanc = $lanc ?? [];
    $idfin = 0;
    $totalRec = 0;
    $totalPag = 0;
    $boleto = 'false';
    for ($i=0; $i < count($lanc); $i++){
        $idfin = $lanc[$i]['IDFIN'];
        if ($lanc[$i]['TIPOLANCAMENTO'] == 'RECEBIMENTO') {
            $totalRec += $lanc[$i]['VALOR'];
        }else {
            $totalPag += $lanc[$i]['VALOR'];
        }
    }
    $saldo = $totalRec - $totalPag;
    if ($saldo < 0) $boleto = 'true';
    $this->smarty->assign('idfin', $idfin);
    $this->smarty->assign('totalRec', $totalRec);
    $this->smarty->assign('totalPag', $totalPag);
    $this->smarty->assign('saldo', $saldo);


    $this->smarty->display('extrato_cliente_mostra.tpl');
	

} //fim mostraExtrato
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancamento = new p_extrato();                          


$lancamento->controle();
 
  
?>
