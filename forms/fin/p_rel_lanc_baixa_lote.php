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


//Class P_FLUXO_CAIXA
Class p_rel_lanc_baixa_lote extends c_lancamento {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_dados_lanc = NULL;
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
        $this->m_dados_lanc = isset($parmGet['dadosLanc']) ? $parmGet['dadosLanc'] : '';


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
        $lanc = $this->select_titulos_a_baixar($this->m_letra, $this->m_dados_lanc);
    endif;
    
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);    
    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    
    $this->smarty->display('rel_lanc_baixa_lote.tpl');
	

} //fim mostrasituacaos

function select_titulos_a_baixar($letra, $titulos){

    $par = explode("|", $letra);
    $par_titulos = explode("|", $titulos);

    $dataMov = c_date::convertDateTxt($par[0]);

    $con = new c_banco();
    $con->setTab("FIN_CONTA");
    $banco = $con->getField("NOMEINTERNO", "CONTA =".$par[1]);
    $con->close_connection();

    
    $ids = '';
    for($i=0; $i<count($par_titulos); $i++){
        if(!empty($par_titulos[$i])){
            if($ids == ''){
                $ids = $par_titulos[$i];
            }else{
                $ids = $ids.", ".$par_titulos[$i];
            }
        }
        

    }

    $sql = "SELECT a.*, c.NOME, 'BAIXADO' as SituacaoPAG, '".$banco."' as contaBancaria, '".$dataMov."' as DataMovimento FROM FIN_LANCAMENTO a ";
    $sql .= "inner join fin_cliente c on c.cliente = a.pessoa ";
    $sql .= "left join fin_genero g on g.genero = a.genero ";
    $sql .= "inner join amb_ddm t on ((t.alias='FIN_MENU') and (t.campo='TipoLanc') and (t.tipo = a.tipolancamento)) ";
    $sql .= " WHERE ID in (".$ids.")";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();

    return $banco->resultado;
    
}
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancPed = new p_rel_lanc_baixa_lote();


$lancPed->controle();
 
  
?>
