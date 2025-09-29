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
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
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
    
    //$data = "{label: '".'Europe'."', backgroundColor: '"."#8e5ea2"."', data: [408,547,675,734]}";
    $mes = date('m')*1;
    $ano = date('Y');
    $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
    /*
    $sql = "select Sum(p.Total) as TOTAL, a.nome as VENDEDOR, m.Meta as META, ";
    $sql .="p.MARGEMLIQUIDA ";
    $sql .="from EST_NOTA_FISCAL_PRODUTO p ";
    $sql .="left join FAT_METAS_MENSAL m on (m.VENDEDOR=p.USRFATURA) ";
    $sql .="left join AMB_USUARIO a on (a.USUARIO=p.USRFATURA) ";
    $sql .="where (Ano = '".$ano."') and (Mes = '".$mes."') ";
    $sql .="group by usrfatura";
    */

    $sql = "select m.Meta as METAS, round((Sum(p.TOTAL) / m.META),2)  as ICM_VENDAS, ";
    $sql .="Sum(p.TOTAL) as VALOR_VENDIDO, ";
    $sql .="( (m.META - Sum(p.TOTAL) ) / ";
    $sql .="(SELECT TIMESTAMPDIFF(DAY,'".$ano."-".$mes."-01','".$ano."-".$mes."-".$ultimo_dia."')) ) as META_DIARIA, ";
    $sql .="a.nome as VENDEDOR, round( (m.Meta * 0.15) ,2)  as METAMARGEMLIQUIDA, ";
    $sql .="round((Sum(p.MARGEMLIQUIDA) /  round( (m.Meta * 0.15) ,2) ) , 2) as ICM, ";
    $sql .="round(Sum(p.MARGEMLIQUIDA), 2) as MARGEMLIQUIDA, ";
    $sql .="( round((Sum(p.TOTAL) / Count(p.TOTAL)),2) ) as CUSTOMEDIO ";
    $sql .="from FAT_PEDIDO_ITEM p ";
    $sql .="left join FAT_METAS_MENSAL m on (m.VENDEDOR=p.USRFATURA) ";
    $sql .="left join AMB_USUARIO a on (a.USUARIO=p.USRFATURA) "; 
    $sql .="where (Ano = '".$ano."') and (Mes = '".$mes."') and (p.usrfatura > 0) ";
    $sql .="group by usrfatura";
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $lanc = $result = $consulta->resultado;

    $data = "";
    $labels = "";
    for ($i = 0; $i < count($result); $i++) {
        if ($i > 0 ){
            $dados .= ",";
            $labels .= ",";
            $bckgroundColor .= ",";  
        }
        $dados .= round ($result[$i]['VALOR_VENDIDO']).",". round ($result[$i]['METAS']);
        $bckgroundColor .= "'" . "#3e95cd" . "'" .",". "'" . "#8e5ea2" . "'";
        $labels .= "'".$result[$i]['VENDEDOR']."','META'";
    }

    $this->smarty->assign('bckgroundColor', $bckgroundColor); 
    
    //$dados = '2478,5267,734,784,433';
    $this->smarty->assign('dados', $dados); 
    
    $this->smarty->assign('labels', $labels); 

    $this->smarty->assign('lanc', $lanc);

    $dataIni = $ano.'-'.$mes.'-01';
    
    $dataFim = $ano.'-'.$mes.'-'.$ultimo_dia;

    $sql = "select round(Sum(n.TOTAL),2) as TOTAL, round(Sum(n.LUCROBRUTO),2) as LUCROBRUTO, ";
    $sql .= "round(SUM(n.CUSTOTOTAL),2) as CUSTOTOTAL, round(Sum(m.META),2) as TOTALMETA, ";
    $sql .= "((round(Sum(m.META),2) - round(Sum(n.TOTAL),2) ) / (SELECT TIMESTAMPDIFF(DAY,'".$ano."-".$mes."-01','".$ano."-".$mes."-".$ultimo_dia."'))) as METADIARIA,  ";
    $sql .= "(round(Sum(m.META),2) - round(Sum(n.TOTAL),2)) as METAASERALCANCADA ";
    $sql .= "FROM FAT_PEDIDO n ";
    $sql .= "LEFT JOIN FAT_PEDIDO_ITEM p on (n.id=p.id) "; 
    $sql .="left join FAT_METAS_MENSAL m on (m.VENDEDOR=p.USRFATURA) ";
    $sql .= "WHERE (emissao between '".$dataIni."' and '".$dataFim."') and (p.usrfatura > 0)";
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $totais = $result = $consulta->resultado;

    $this->smarty->assign('vendas', $totais[0][TOTALNF]);
    $this->smarty->assign('lucrobruto', $totais[0][LUCROBRUTO]);
    
    
    $sql = "select round(Sum(TOTAL),2) as TOTAL ";
    $sql .= "FROM FIN_LANCAMENTO ";
    $sql .= "WHERE (TIPOLANCAMENTO = 'P') and (vencimento between '".$dataIni."' and '".$dataFim."')";
    
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $despesas = $result = $consulta->resultado;


    $this->smarty->assign('lucroliquido', round(($totais[0][LUCROBRUTO] - $despesas[0][TOTAL]),2)); 
    $this->smarty->assign('pontoequilibrio', round((($totais[0][LUCROBRUTO] - $despesas[0][TOTAL])/$totais[0][TOTALNF]),2));
    $this->smarty->assign('custovenda', $totais[0][CUSTOTOTAL]);
        
    $this->smarty->display('est_dashboard.tpl');
        
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
