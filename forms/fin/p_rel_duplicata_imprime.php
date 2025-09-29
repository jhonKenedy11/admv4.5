<?php

/**
 * @package   astecv3
 * @name      p_centrocusto
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
include_once($dir."/../../class/fin/c_lancamento_valor_extenso.php");


//Class P_FLUXO_CAIXA
Class p_rel_duplicata_imprime extends c_lancamento {

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

        $this->setId(isset($parmGet['id']) ? $parmGet['id'] : '');

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
    $this->mostraLancPed('');
} // fim controle



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraLancPed($mensagem){
    

    $par = explode("|", $this->m_letra);

    if ((isset($this->m_letra)) or ($this->m_letra != '')):
        $lanc = $this->select_lancamento();
    endif;

    //$con = new c_banco();
    //$con->setTab("FIN_CLIENTE");
    //$cliente = $con->getField("NOME", "CLIENTE =".$lanc[0]['PESSOA']);
    //$con->close_connection();

    $sql = "SELECT * FROM FIN_CLIENTE WHERE CLIENTE = ".$lanc[0]['PESSOA'];
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $cliente = $consulta->resultado;

    $contaBancaria = $lanc[0]['CONTA'];
    $sql = "select * from FIN_CONTA where CONTA = '".$contaBancaria."'";
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;

    $sql = "select * from AMB_EMPRESA where CENTROCUSTO = '".$this->m_empresacentrocusto."'";
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $empresa = $consulta->resultado;

    $totalLanc = $lanc[0]['TOTAL'];

    // passar valor com formato Bd ex: 100.00
    $objValorExtenso = new c_lancamento_valor_extenso();
    $valorExtenso = $objValorExtenso->valorPorExtenso($totalLanc,true,false);

    

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);    
    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('cliente', $cliente);
    $this->smarty->assign('dadosBancario', $result);
    $this->smarty->assign('empresa', $empresa);
    $this->smarty->assign('valorExtenso', $valorExtenso);
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    $this->smarty->display('rel_duplicata_imprime.tpl');

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancPed = new p_rel_duplicata_imprime();


$lancPed->controle();
 
  
?>
