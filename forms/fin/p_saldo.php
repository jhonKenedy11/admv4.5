<?php

/**
 * @package   astec
 * @name      p_saldo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas Tortola da Silva Bucko / Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      13/04/2012 / 27/12/2016
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/fin/c_saldo.php");

//Class p_saldo
Class p_saldo extends c_saldo {

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
    //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
    $this->m_submenu = $this->parmPost['submenu'];
    $this->m_opcao = $this->parmPost['opcao'];
    $this->m_letra = $this->parmPost['letra'];
    $this->m_par = explode("|", $this->m_letra);

    // caminhos absolutos para todos os diretorios biblioteca e sistema
    $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
    $this->smarty->assign('bootstrap', ADMbootstrap);
    $this->smarty->assign('raizCliente', $this->raizCliente);
    $this->smarty->assign('admClass', ADMclass);

    // metodo SET dos dados do FORM para o TABLE
    $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : '');
    $this->setData(isset($this->parmPost['data']) ? $this->parmPost['data'] : '');
    $this->setSaldo(isset($this->parmPost['saldo']) ? $this->parmPost['saldo'] : '');
    $this->setConta(isset($this->parmPost['conta']) ? $this->parmPost['conta'] : '');

    // dados para exportacao e relatorios
    $this->smarty->assign('titulo', "Saldo de Contas");
    $this->smarty->assign('colVis', "[ 0,1,2]"); 
    $this->smarty->assign('disableSort', "[ 3 ]"); 
    $this->smarty->assign('numLine', "50"); 

    // include do javascript
    // include ADMjs . "/fin/s_saldo.js";
}


/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
 * @name controle
 * @param VARCHAR submenu 
 * @return vazio
 */
function controle(){
  switch ($this->m_submenu){
            case 'cadastrar':
                    if ($this->verificaDireitoUsuario('FinSaldo', 'I')){
                            $this->setData(date("d/m/Y"));
                            $this->desenhaCadastroSaldo();
                    }
                    break;
            case 'alterar':
                    if ($this->verificaDireitoUsuario('FinSaldo', 'A')){
                            $saldo = $this->select_saldo();
                            $this->setConta($saldo[0]['CONTA']);
                            $this->setData($saldo[0]['DATA']);
                            $this->setSaldo($saldo[0]['SALDO']);
                            $this->desenhaCadastroSaldo();
                        }
                    break;
            case 'inclui':
                    if ($this->verificaDireitoUsuario('FinSaldo', 'I')){
                            if ($this->existeSaldo()){
                                    $this->m_submenu = "cadastrar";
                                    $this->desenhaCadastroSaldo("SALDO JÁ EXISTENTE NESTA DATA, ALTERE A CONTA OU A DATA", "alerta");}
                            else {
                                    $this->mostraSaldo($this->incluiSaldo());}

                    }		
                    break;
            case 'altera':
                    if ($this->verificaDireitoUsuario('FinSaldo', 'A')){
                            $this->mostraSaldo($this->alteraSaldo());
                    }
                    break;
            case 'exclui':
                    if ($this->verificaDireitoUsuario('FinSaldo', 'E')){
                            $this->mostraSaldo($this->excluiSaldo());
                    }
                    break;

            default:
                    if ($this->verificaDireitoUsuario('FinSaldo', 'C')){
                            $this->mostraSaldo('');
                    }

    }

} // fim controle

//---------------------------------------------------------------
//---------------------------------------------------------------
function desenhaCadastroSaldo($mensagem=NULL, $tipoMsg=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('data', "'".$this->getData('F')."'");
    $this->smarty->assign('saldo', $this->getSaldo('F'));
    $this->smarty->assign('anoSaldo', $this->m_par[1]);	
    $this->smarty->assign('mesSaldo', $this->m_par[0]);	
    
    $consulta = new c_banco();
    $sql = "SELECT * FROM fin_conta  where status ='A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['CONTA'];
            $conta_names[$i] = $result[$i]['NOMEINTERNO'];
    }	
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);	
    if ($this->m_submenu=='alterar'):
        $this->smarty->assign('conta_id', $this->getConta());
    else:
        $this->smarty->assign('conta_id', $this->m_par[2]);
    endif;


    $this->smarty->display('saldo_cadastro.tpl');
    
}//fim desenhaCadastroSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraSaldo($mensagem){
	if ($this->m_letra != ""){
		$lanc = $this->select_saldo_letra($this->m_letra);	
	}
	

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('lanc', $lanc);
    if($this->m_par[1] == "") $anoSaldo = $this->smarty->assign('anoSaldo',date("Y"));
    else $anoSaldo = $this->smarty->assign('anoSaldo', $this->m_par[1]);	
	
     // combobox Conta
  	$consulta = new c_banco();
  	$sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta  where status ='A'";
  	$consulta->exec_sql($sql);
	$consulta->close_connection();
  	$result = $consulta->resultado;
	$contaPesq_ids[0] = '';
	$contaPesq_names[0] = '';
  	for ($i=0; $i < count($result); $i++){
		$contaPesq_ids[$i+1] = $result[$i]['ID'];
		$contaPesq_names[$i+1] = $result[$i]['DESCRICAO'];
	}
	$this->smarty->assign('contaPesq_ids', $contaPesq_ids);
	$this->smarty->assign('contaPesq_names', $contaPesq_names);
        if($this->m_par[2] == "") $this->smarty->assign('contaPesq_id', '');
        else $this->smarty->assign('contaPesq_id', $this->m_par[2]);
    
    //combobox Mes Saldo
  	$mesSaldo_ids[0] = '';
	$mesSaldo_names[0] = '';
	$mesSaldo_ids[1] = 1;
	$mesSaldo_names[1] = 'Janeiro';
	$mesSaldo_ids[2] = 2;
	$mesSaldo_names[2] = 'Fevereiro';
	$mesSaldo_ids[3] = 3;
	$mesSaldo_names[3] = 'Março';
	$mesSaldo_ids[4] = 4;
	$mesSaldo_names[4] = 'Abril';
	$mesSaldo_ids[5] = 5;
	$mesSaldo_names[5] = 'Maio';
	$mesSaldo_ids[6] = 6;
	$mesSaldo_names[6] = 'Junho';
	$mesSaldo_ids[7] = 7;
	$mesSaldo_names[7] = 'Julho';
	$mesSaldo_ids[8] = 8;
	$mesSaldo_names[8] = 'Agosto';
	$mesSaldo_ids[9] = 9;
	$mesSaldo_names[9] = 'Setembro';
	$mesSaldo_ids[10] = 10;
	$mesSaldo_names[10] = 'Outubro';
	$mesSaldo_ids[11] = 11;
	$mesSaldo_names[11] = 'Novembro';
	$mesSaldo_ids[12] = 12;
	$mesSaldo_names[12] = 'Dezembro'; 
	$this->smarty->assign('mesSaldo_ids', $mesSaldo_ids);
	$this->smarty->assign('mesSaldo_names', $mesSaldo_names);
        if($this->m_par[0] == "") $mesSaldo[0] = date("m");
        else $mesSaldo[0] = $this->m_par[0];	
        $this->smarty->assign('mesSaldo_id', $mesSaldo);	
    
    
    
	$this->smarty->display('saldo_mostra.tpl');
	

} //fim mostraSaldo
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$saldo = new p_saldo();
                              
$saldo->controle();
 
  
?>
