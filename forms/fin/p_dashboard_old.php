<?php
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
//Class p_dashboard
Class p_dashboard extends c_user {

private $m_submenu = NULL;
private $m_opcao = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct($submenu, $letra, $opcao){


	    // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $submenu;
        $this->m_opcao = $opcao;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        $this->smarty->assign('titulo', "Dashboard");
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
function controle(){
  switch ($this->m_submenu){
        default:
                $this->mostraDashboard('');

    }

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Genero. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function mostraDashboard($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);

    $sql ="select ";
    $sql.="max(if(MONTH(emissao)=1, Total, 0)) as january, ";
    $sql.="max(if(MONTH(emissao)=2, Total, 0)) as february, ";
    $sql.="max(if(MONTH(emissao)=3, Total, 0)) as march, ";
    $sql.="max(if(MONTH(emissao)=4, Total, 0)) as april, ";
    $sql.="max(if(MONTH(emissao)=5, Total, 0)) as may, ";
    $sql.="max(if(MONTH(emissao)=6, Total, 0)) as june, ";
    $sql.="max(if(MONTH(emissao)=7, Total, 0)) as july, ";
    $sql.="max(if(MONTH(emissao)=8, Total, 0)) as august, "; 
    $sql.="max(if(MONTH(emissao)=9, Total, 0)) as september, ";
    $sql.="max(if(MONTH(emissao)=10, Total, 0)) as october, "; 
    $sql.="max(if(MONTH(emissao)=11, Total, 0)) as november, "; 
    $sql.="max(if(MONTH(emissao)=12, Total, 0)) as december ";
    $sql.="FROM FIN_LANCAMENTO ";
    $sql.="where vencimento >= '2019-01-01' ";
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql." and tipolancamento = 'R';");
    $consulta->close_connection();
    $result = $consulta->resultado;

    $dados =  "[".$result[0][MAY];
    $dados .= ",".$result[0][JUNE];
    $dados .= ",".$result[0][JULY];
    $dados .= ",".$result[0][AUGUST];
    $dados .= ",".$result[0][SEPTEMBER];
    $dados .= ",".$result[0][OCTOBER];
    $dados .= ",".$result[0][NOVEMBER];
    $dados .= ",".$result[0][DECEMBER]."]";    

    $this->smarty->assign('receitas', $dados);    
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql." and tipolancamento = 'P';");
    $consulta->close_connection();
    $result = $consulta->resultado;
    
    $dados =  "[".$result[0][MAY];
    $dados .= ",".$result[0][JUNE];
    $dados .= ",".$result[0][JULY];
    $dados .= ",".$result[0][AUGUST];
    $dados .= ",".$result[0][SEPTEMBER];
    $dados .= ",".$result[0][OCTOBER];
    $dados .= ",".$result[0][NOVEMBER];
    $dados .= ",".$result[0][DECEMBER]."]";    

    $this->smarty->assign('despesas', $dados);     
    
    $sql = "select ";
    $sql.= "Sum(if (TIPODOCTO='B', Total, 0)) as BOLETO, ";
    $sql.= "Sum(if (TIPODOCTO='D', Total, 0)) as DINHEIRO, ";
    $sql.= "Sum(if (TIPODOCTO='T', Total, 0)) as TED, ";
    $sql.= "Sum(if (TIPODOCTO<>'B' AND TIPODOCTO <> 'D' AND TIPODOCTO <> 'T', Total, 0)) as OUTROS ";
    $sql.= "FROM FIN_LANCAMENTO ";
    $sql.= "where vencimento >= '2019-01-01'";

    $consulta = new c_banco();
    $consulta->exec_sql($sql." and tipolancamento = 'P';");
    $consulta->close_connection();
    $result = $consulta->resultado;

    $total = $result[0][BOLETO]+$result[0][DINHEIRO]+$result[0][TED]+$result[0][OUTROS]; 

    $dados = array( 
        array((round(($result[0][BOLETO]  /$total),2)*100),$result[0][BOLETO]),
        array((round(($result[0][DINHEIRO]/$total),2)*100),$result[0][DINHEIRO]),
        array((round(($result[0][TED]     /$total),2)*100),$result[0][TED]),
        array((round(($result[0][OUTROS]  /$total),2)*100),$result[0][OUTROS]));
        
    $this->smarty->assign('despesasTipo', $dados); 
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql." and tipolancamento = 'R';");
    $consulta->close_connection();
    $result = $consulta->resultado;

    $total = $result[0][BOLETO]+$result[0][DINHEIRO]+$result[0][TED]+$result[0][OUTROS]; 

    $dados = array( 
        array((round(($result[0][BOLETO]  /$total),2)*100),$result[0][BOLETO]),
        array((round(($result[0][DINHEIRO]/$total),2)*100),$result[0][DINHEIRO]),
        array((round(($result[0][TED]     /$total),2)*100),$result[0][TED]),
        array((round(($result[0][OUTROS]  /$total),2)*100),$result[0][OUTROS]));
        
    $this->smarty->assign('receitasTipo', $dados); 

    $this->smarty->display('fin_dashboard.tpl');
        
}//fim 


//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$dashboard = new p_dashboard($_POST['submenu'],
                       $_POST['letra'],$_REQUEST['opcao']);

                              
$dashboard->controle();
 
  
?>
