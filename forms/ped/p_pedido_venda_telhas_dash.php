<?php

if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");


//Class P_situacao
Class p_pedido_venda_telhas_dash extends c_pedidoVenda {
            
    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    public $smarty          = NULL;
    
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];

        $this->m_letra = $parmPost['letra'];       
        
        $this->m_par = explode("|", $this->m_letra);
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");
    
    }
        

    function dias_uteis($mes,$ano){
  
        $uteis = 0;
        // Obtém o número de dias no mês 
        // (http://php.net/manual/en/function.cal-days-in-month.php)
        $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); 
      
        for($dia = 1; $dia <= $dias_no_mes; $dia++){
      
          $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
          $semana    = date("N", $timestamp);
      
          if($semana < 7) $uteis++;
      
        }
      
        return $uteis;
      
      }

      function d_uteis($datainicial,$datafinal=null){
        $d = substr($datainicial, 0, 4);
        // chama a funcao que calcula a pascoa	
        $pascoa_dt = $this->dataPascoa(date('Y'));

        // chama a funcao que calcula o carnaval	
        $carnaval_dt = $this->dataCarnaval(date('Y'));
        
        // chama a funcao que calcula corpus christi	
        $CorpusChristi_dt = $this->dataCorpusChristi(date('Y'));
        
        // chama a funcao que calcula a sexta feira santa	
        //$sexta_santa_dt = $this->dataSextaSanta(date('Y'));

        $feriados = array(
            strtotime(str_replace("/","-", '01/01/'.$d)),
            strtotime(str_replace("/","-",$carnaval_dt)), 
            //strtotime(str_replace("/","-",$sexta_santa_dt)), 
            strtotime(str_replace("/","-",$pascoa_dt)), 
            strtotime(str_replace("/","-","21/04/".$d)), 
            strtotime(str_replace("/","-","01/05/".$d)),
            strtotime(str_replace("/","-",$CorpusChristi_dt)),  
            strtotime(str_replace("/","-","07/09/".$d)), 
            strtotime(str_replace("/","-","12/10/".$d)), 
            strtotime(str_replace("/","-","02/11/".$d)), 
            strtotime(str_replace("/","-","15/11/".$d)), 
            strtotime(str_replace("/","-","25/12/".$d)));

        sort($feriados);
                
        if (!isset($datainicial)) 
            return false;
        
        $segundos_datainicial = strtotime(str_replace("/","-",$datainicial));
        
        if (!isset($datafinal)) 
            $segundos_datafinal=time();
        else 
            $segundos_datafinal = strtotime(str_replace("/","-",$datafinal));
        
        $qtFeriado = 0;
        for($i=0;$i<count($feriados);$i++){
            if (($feriados[$i] >= $segundos_datainicial) and ($feriados[$i] <= $segundos_datafinal)) {
                $w = date('w',$feriados[$i]);
                if ($w>0){ 
                    $qtFeriado ++; 
                }
            }						 
        } 
        
        $dias = abs(floor(floor(($segundos_datafinal-$segundos_datainicial)/3600)/24 ) );
        
        $uteis=0;
        
        for($i=1;$i<=$dias;$i++){
          $diai = $segundos_datainicial+($i*3600*24);
          $w = date('w',$diai);
          //if ($w>0 && $w<6){ $uteis++; }
          if ($w>0){ 
              $uteis++; 
          }
        }
        
        return $uteis;
      }

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }
    
    function controle() {
        switch ($this->m_submenu) {        
            default:
                if ($this->verificaDireitoUsuario('PedVendasDash', 'C')) {
                    $this->mostraPedidos('');
                }
        }
    }
/*
function dias_feriados($ano = null)
{
  if ($ano === null)
  {
    $ano = intval(date('Y'));
  }
 
  $pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
  $dia_pascoa = date('j', $pascoa);
  $mes_pascoa = date('n', $pascoa);
  $ano_pascoa = date('Y', $pascoa);
 
  $feriados = array(
    // Tatas Fixas dos feriados Nacionail Basileiras
    mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
    mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49
 
    // These days have a date depending on easter
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºferia Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºferia Carnaval	
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa  
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Cirist
  );
 
  sort($feriados);
  
  return $feriados;
} */

// dataPascoa(ano, formato);
// Autor: Yuri Vecchi
//
// Funcao para o calculo da Pascoa
// Retorna o dia da pascoa no formato desejado ou false.
//
// ######################ATENCAO###########################
// Esta funcao sofre das limitacoes de data de mktime()!!!
// ########################################################
//
// Possui dois parametros, ambos opcionais
// ano = ano com quatro digitos
//	 Padrao: ano atual
// formato = formatacao da funcao date() http://br.php.net/date
//	 Padrao: d/m/Y


function dataPascoa($ano=false, $form="d/m/Y") {
	$ano=$ano?$ano:date("Y");
	if ($ano<1583) { 
		$A = ($ano % 4);
		$B = ($ano % 7);
		$C = ($ano % 19);
		$D = ((19 * $C + 15) % 30);
		$E = ((2 * $A + 4 * $B - $D + 34) % 7);
		$F = (int)(($D + $E + 114) / 31);
		$G = (($D + $E + 114) % 31) + 1;
		return date($form, mktime(0,0,0,$F,$G,$ano));
	}
	else {
		$A = ($ano % 19);
		$B = (int)($ano / 100);
		$C = ($ano % 100);
		$D = (int)($B / 4);
		$E = ($B % 4);
		$F = (int)(($B + 8) / 25);
		$G = (int)(($B - $F + 1) / 3);
		$H = ((19 * $A + $B - $D - $G + 15) % 30);
		$I = (int)($C / 4);
		$K = ($C % 4);
		$L = ((32 + 2 * $E + 2 * $I - $H - $K) % 7);
		$M = (int)(($A + 11 * $H + 22 * $L) / 451);
		$P = (int)(($H + $L - 7 * $M + 114) / 31);
		$Q = (($H + $L - 7 * $M + 114) % 31) + 1;
		return date($form, mktime(0,0,0,$P,$Q,$ano));
	}
}



// dataCarnaval(ano, formato);
// Autor: Yuri Vecchi
//
// Funcao para o calculo do Carnaval
// Retorna o dia do Carnaval no formato desejado ou false.
//
// ######################ATENCAO###########################
// Esta funcao sofre das limitacoes de data de mktime()!!!
// ########################################################
//
// Possui dois parametros, ambos opcionais
// ano = ano com quatro digitos
//	 Padrao: ano atual
// formato = formatacao da funcao date() http://br.php.net/date
//	 Padrao: d/m/Y

function dataCarnaval($ano=false, $form="d/m/Y") {
	$ano=$ano?$ano:date("Y");
	$a=explode("/", $this->dataPascoa($ano));
	return date($form, mktime(0,0,0,$a[1],$a[0]-47,$a[2]));
}




// dataCorpusChristi(ano, formato);
// Autor: Yuri Vecchi
//
// Funcao para o calculo do Corpus Christi
// Retorna o dia do Corpus Christi no formato desejado ou false.
//
// ######################ATENCAO###########################
// Esta funcao sofre das limitacoes de data de mktime()!!!
// ########################################################
//
// Possui dois parametros, ambos opcionais
// ano = ano com quatro digitos
//	 Padrao: ano atual
// formato = formatacao da funcao date() http://br.php.net/date
//	 Padrao: d/m/Y

function dataCorpusChristi($ano=false, $form="d/m/Y") {
	$ano=$ano?$ano:date("Y");
	$a=explode("/", $this->dataPascoa($ano));
	return date($form, mktime(0,0,0,$a[1],$a[0]+60,$a[2]));
}


// dataSextaSanta(ano, formato);
// Autor: Yuri Vecchi
//
// Funcao para o calculo da Sexta-feira santa ou da Paixao.
// Retorna o dia da Sexta-feira santa ou da Paixao no formato desejado ou false.
//
// ######################ATENCAO###########################
// Esta funcao sofre das limitacoes de data de mktime()!!!
// ########################################################
//
// Possui dois parametros, ambos opcionais
// ano = ano com quatro digitos
// Padrao: ano atual
// formato = formatacao da funcao date() http://br.php.net/date
// Padrao: d/m/Y

function dataSextaSanta($ano=false, $form="d/m/Y") {
	$ano=$ano?$ano:date("Y");
	$a=explode("/", $this->dataPascoa($ano));
	return date($form, mktime(0,0,0,$a[1],$a[0]-2,$a[2]));
} 





function somar_dias_uteis($str_data,$int_qtd_dias_somar,$feriados) {

	// Caso seja informado uma data do MySQL do tipo DATETIME - aaaa-mm-dd 00:00:00
	// Transforma para DATE - aaaa-mm-dd

   $str_data = substr($str_data,0,10);

	// Se a data estiver no formato brasileiro: dd/mm/aaaa
	// Converte-a para o padrão americano: aaaa-mm-dd

	if ( preg_match("@/@",$str_data) == 1 ) {

		$str_data = implode("-", array_reverse(explode("/",$str_data)));

	}
	
	
	// chama a funcao que calcula a pascoa	
	$pascoa_dt = $this->dataPascoa(date('Y'));
	$aux_p = explode("/", $pascoa_dt);
	$aux_dia_pas = $aux_p[0];
	$aux_mes_pas = $aux_p[1];
	$pascoa = "$aux_mes_pas"."-"."$aux_dia_pas"; // crio uma data somente como mes e dia
	
	
	// chama a funcao que calcula o carnaval	
	$carnaval_dt = $this->dataCarnaval(date('Y'));
	$aux_carna = explode("/", $carnaval_dt);
	$aux_dia_carna = $aux_carna[0];
	$aux_mes_carna = $aux_carna[1];
	$carnaval = "$aux_mes_carna"."-"."$aux_dia_carna"; 

	
	// chama a funcao que calcula corpus christi	
	$CorpusChristi_dt = $this->dataCorpusChristi(date('Y'));
	$aux_cc = explode("/", $CorpusChristi_dt);
	$aux_cc_dia = $aux_cc[0];
	$aux_cc_mes = $aux_cc[1];
	$Corpus_Christi = "$aux_cc_mes"."-"."$aux_cc_dia"; 

	
	// chama a funcao que calcula a sexta feira santa	
	$sexta_santa_dt = $this->dataSextaSanta(date('Y'));
	$aux = explode("/", $sexta_santa_dt);
	$aux_dia = $aux[0];
	$aux_mes = $aux[1];
	$sexta_santa = "$aux_mes"."-"."$aux_dia"; 

	
   
   $feriados = array("01-01", $carnaval, $sexta_santa, $pascoa, $Corpus_Christi, "04-21", "05-01", "06-12" ,"07-09", "07-16", "09-07", "10-12", "11-02", "11-15", "12-24", "12-25", "12-31");


	$array_data = explode('-', $str_data);
	$count_days = 0;
	$int_qtd_dias_uteis = 0;



	while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {

		$count_days++;
		$day = date('m-d',strtotime('+'.$count_days.'day',strtotime($str_data))); 
		
		if(($dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', gmmktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' && !in_array($day,$feriados)) {

			$int_qtd_dias_uteis++;
		}

	}

	 return gmdate('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));

}

function getWorkingDays($startDate, $endDate) {
    $begin = strtotime($startDate);
    $end   = strtotime($endDate);
    if ($begin > $end) {
        echo "startdate is in the future! <br />";
        return 0;
    }
    else {
        $holidays = array('01/01','12/10','25/12');
        $weekends = 0;
        $no_days = 0;
        $holidayCount = 0;
        while ($begin <= $end) {
            $no_days++; // no of days in the given interval
            if (in_array(date("d/m", $begin), $holidays)) {
                $holidayCount++;
            }
            $what_day = date("N", $begin);
            if ($what_day > 6) { // 6 and 7 are weekend days
                $weekends++;
            };
            $begin += 86400; // +1 day
        };
        $working_days = $no_days - $weekends - $holidayCount;

        return $working_days;
    }
}

    
    function mostraPedidos($mensagem=NULL) {

        if ($this->m_letra !=''){
            $par = explode("|", $this->m_letra);
            $dataIni = c_date::convertDateTxt($par[0]);
            if ($par[1] > date('Y-m-d H:i:s')) {
                $dataFim = date('Y-m-d');
            } else {
                $dataFim = c_date::convertDateTxt($par[1]);
            }
            $dataFim = c_date::convertDateTxt($par[1]);
            $mes = date('m', strtotime($dataFim));
            $ano = date('yy', strtotime($dataFim));

            $qtFeriado = 0;
            /*foreach($this->dias_feriados($ano) as $a)
            {
                $feriado = date("Y-m-d",$a);
                if (( strtotime($feriado) >= strtotime($dataIni)) and (strtotime($feriado) <= strtotime($dataFim))) {
                    $qtFeriado += 1;
                }						 
            } */

            $data = explode("-", $dataFim);
            $d = new DateTime($dataIni);
            //$qtdDiasUteis = $this->d_uteis($d->format('Y-m-01'), $d->format('Y-m-t'));
            $qtdDiasUteis = $this->getWorkingDays($d->format('Y-m-01'), $d->format('Y-m-t'));

            //$qtdDiasUteis = 27;
            //$diasPassados = $this->getWorkingDays($dataIni,$dataFim);
            $diasPassados = 0;
            $diasPassados = $this->getWorkingDays($dataIni,date('Y-m-d'));
            $diasPassados = $diasPassados - 1;
            if ($diasPassados <= 0) {
                $diasPassados = 0;
            }
            //$diasPassados = $this->d_uteis($dataIni,$dataFim);
            //$diasPassados = 6;

            $where = " (";
            $wherel = " (";
            $wherec = " (";
            $wheres = " (";
            $cc = explode(",", $par[2]);
            for ($i = 0; $i < count($cc); $i++) {
                $wherel .= "( l.centrocusto = ".$cc[$i]." ) ";
                $where .= "( centrocusto = ".$cc[$i]." ) ";
                $wherec .= "( p.ccusto = ".$cc[$i]." ) ";
                $wheres .= "( ccusto = ".$cc[$i]." ) ";
                if ( ($i+1) < count($cc) ){
                    $wherel .= " or ";
                    $where .= " or ";
                    $wherec .= " or ";
                    $wheres .= " or ";
                    $whereM .= " or ";
                }
            }
            $where .= ") ";
            $wherel .= ") ";
            $wherec .= ") ";
            $wheres .= ") ";

            $df = explode("-", $dataFim);
            $di = explode("-", $dataIni);
            
            if (($df[1] == $di[1])and($df[0] == $di[0])) {
                $sql = "SELECT DISTINCT(TOTALDIAMES) FROM FAT_META_MENSAL ";
                $sql.= "WHERE (ANO = '".$df[0]."') and (MES = '".$df[1]."') AND ";
                $sql.= $wheres;
                $consulta = new c_banco();
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $dias = $result = $consulta->resultado;
                $qtdDiasUteis = $dias[0]['TOTALDIAMES'];
            } 
            
            
            $financeiro = $this->financeiro($dataIni, $dataFim, $wherel);
            $this->smarty->assign('financeiro', $financeiro);
            $total = $this->totais($dataIni, $dataFim, $wheres, $wherel);
            $this->smarty->assign('total', $total);
            $totaisDet = $this->totaisDetalhes($dataIni, $dataFim, $wheres);
            $this->smarty->assign('totaisDet', $totaisDet);
            //$forecast = $this->forecast($dataIni, $dataFim, $qtdDiasUteis, $data[2]);
            $forecast = $this->forecast($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $wherec, $mes, $wheres, $ano);
            $this->smarty->assign('forecast', $forecast);
            $projecao = $this->projecao($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $wherec );
            $this->smarty->assign('projecao', $projecao);
            $metas = $this->metas($dataIni, $dataFim, $wherec);
            $this->smarty->assign('metas', $metas);
        }
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[1]);

        // ########## CENTROCUSTO ##########
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $this->comboSql($sql, $this->m_par[3] ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        $this->smarty->assign('centroCusto_id', $centroCusto_id);  

        $this->smarty->display('pedido_venda_telhas_dash_mostra.tpl');
    }
}

// Rotina principal - cria classe
$pedido = new p_pedido_venda_telhas_dash();

$pedido->controle();