<?php
/**
 * @package   astec
 * @name      c_saldo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */


$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");

//Class C_SALDO
Class c_saldo extends c_user {

// Campos tabela | Objetos da classe
private $id = NULL;
private $conta = NULL;
private $data = NULL;
private $saldo = NULL;




//construtor
function c_saldo(){

}

//---------------------------------------------------------------
//---------------------------------------------------------------

public function setId($id){
         $this->id = $id;
}

public function getId(){
         return $this->id;
}

public function setConta($conta){
         $this->conta = $conta;
}

public function getConta(){
         return $this->conta;
}

public function setData($data){
         $this->data = $data;
}

public function getData($format=NULL){

		$this->data = strtr($this->data, "/","-");
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->data)); 
				break;
			case 'B':
                                return c_date::convertDateBdSh($this->data, $this->m_banco);
				break;
			default:
				return $this->data;
		}            
            
}

public function setSaldo($saldo) {
	$this->saldo = $saldo;
}
public function getSaldo($format = NULL) {
		if ($format=='F') {
			return number_format((float)$this->saldo, 2, ',', '.'); }
		else {
			if ($this->saldo != null){
				$num = str_replace('.', '', $this->saldo);
				$num = str_replace(',', '.', $num);
				return (float) $num; }
			else{
				return 0; }
		}	
}




//---------------------------------------------------------------
//---------------------------------------------------------------
public function existeSaldo(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_saldo ";
	$sql .= "WHERE (conta = ".$this->getConta().") AND (data = '".$this->getData('B')."')";
	//ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------

public function select_saldo(){
	$sql  = "SELECT  * ";
   	$sql .= "FROM fin_saldo ";
	$sql .= "WHERE (ID = ".$this->getId().")";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;


} //fim select_saldo
//---------------------------------------------------------------
//Select SALDO ATUAL
//---------------------------------------------------------------
public function saldoContaAtual($letra){
	
	
	$par = explode("|", $letra);
	$dt = explode("/", $par[0]);
	
	$dataIni =  date('Y-m-d', strtotime("-1 day",strtotime($dt[2]."-".$dt[1]."-".$dt[0])));

	$diaSemana = date('N', strtotime($dataIni));
	if ($diaSemana == 6 ):
		$dataIni =  date('Y-m-d', strtotime("-2 day",strtotime($dt[0]."-".$dt[1]."-".$dt[2])));
	else:	
		if ($diaSemana == 7):
			$dataIni =  date('Y-m-d', strtotime("-3 day",strtotime($dt[0]."-".$dt[1]."-".$dt[2])));
		endif;	
	endif;	
	

	$sql  = "SELECT  sum(saldo) as saldo ";
   	$sql .= "FROM fin_saldo ";
   	$sql .= "WHERE data = '".$dataIni."' ";

			// sit lancamento
			if ($par[4] != '0'){
				$i = 5;	
   				$i++;
        		while ($i <= ($par[4]+4)) {
	   				$i++;
        		}				
			}

			// filial
			$posFilial = 5 + $par[4];
			if ($par[$posFilial] != '0'){
				$i = $posFilial + 1;	
   				$i++;
        		while ($i <= ($par[$posFilial]+$posFilial)) {
	   				$i++;
        		}				
			}
		
			// tipo lancamento
			$posTipoLanc = $posFilial + $par[$posFilial] + 1;
			if ($par[$posTipoLanc] != '0'){
				$i = $posTipoLanc + 1;	
   				$i++;
        		while ($i <= ($par[$posTipoLanc]+$posTipoLanc)) {
	   				$i++;
        		}				
			}
		
			// situacao documento
			$posSitDocto = $posTipoLanc + $par[$posTipoLanc] + 1;
			if ($par[$posSitDocto] != '0'){
				$i = $posSitDocto + 1;	
   				$i++;
        		while ($i <= ($par[$posSitDocto]+$posSitDocto)) {
	   				$i++;
        		}				
			}
	   	
	// Conta
	$posConta = $posSitDocto + $par[$posSitDocto] + 1;
	if ($par[$posConta] != '0'){
		$sql .= " AND ";
		$i = $posConta + 1;	
		$sql .= "(conta in (";
		$cc = $par[$i];
		$i++;		   
  		while ($i <= ($par[$posConta]+$posConta)) {
			$cc .= ','.$par[$i];
			$i++;
    	}				
		$sql .= $cc.")) ";
	}   	
   	
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim saldoConta


public function newSadoContaAtual($letra){
	$par = explode("|", $letra);
	$dt = explode("/", $par[0]);

	$dataIni =  date('Y-m-d', strtotime("-1 day", strtotime($dt[2] . "-" . $dt[1] . "-" . $dt[0])));

	$diaSemana = date('N', strtotime($dataIni));
	if ($diaSemana == 6) :
		$dataIni =  date('Y-m-d', strtotime("-2 day", strtotime($dt[0] . "-" . $dt[1] . "-" . $dt[2])));
	else :
		if ($diaSemana == 7) :
			$dataIni =  date('Y-m-d', strtotime("-3 day", strtotime($dt[0] . "-" . $dt[1] . "-" . $dt[2])));
		endif;
	endif;


	$sql  = "SELECT  sum(saldo) as saldo ";
	$sql .= "FROM fin_saldo ";
	$sql .= "WHERE data = '" . $dataIni . "' ";
	$sql .= "AND conta = " . $par[1] . "; ";

	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}



//---------------------------------------------------------------
//Select para o fluxo de caixa
//---------------------------------------------------------------
public function saldoContaDiaAnterior($letra){
	
	$par = explode("|", $letra);
	$dt = explode("/", $par[0]);
	
        $dataAnt =  date('d/m/Y', strtotime("-1 day",strtotime($dt[0]."-".$dt[1]."-".$dt[2])));
        $dataAnt = c_date::convertDateTxt($dataAnt);
	

	$sql  = "SELECT  sum(saldo) as saldo ";
   	$sql .= "FROM fin_saldo ";
   	$sql .= "WHERE data = '".$dataAnt."' ";

			// sit lancamento
			if ($par[4] != '0'){
				$i = 5;	
   				$i++;
        		while ($i <= ($par[4]+4)) {
	   				$i++;
        		}				
			}

			// filial
			$posFilial = 5 + $par[4];
			if ($par[$posFilial] != '0'){
				$i = $posFilial + 1;	
   				$i++;
        		while ($i <= ($par[$posFilial]+$posFilial)) {
	   				$i++;
        		}				
			}
		
			// tipo lancamento
			$posTipoLanc = $posFilial + $par[$posFilial] + 1;
			if ($par[$posTipoLanc] != '0'){
				$i = $posTipoLanc + 1;	
   				$i++;
        		while ($i <= ($par[$posTipoLanc]+$posTipoLanc)) {
	   				$i++;
        		}				
			}
		
			// situacao documento
			$posSitDocto = $posTipoLanc + $par[$posTipoLanc] + 1;
			if ($par[$posSitDocto] != '0'){
				$i = $posSitDocto + 1;	
   				$i++;
        		while ($i <= ($par[$posSitDocto]+$posSitDocto)) {
	   				$i++;
        		}				
			}
	   	
	// Conta
	$posConta = $posSitDocto + $par[$posSitDocto] + 1;
	if ($par[$posConta] != '0'){
		$sql .= " AND ";
		$i = $posConta + 1;	
		$sql .= "(conta in (".$par[$i];
   		$i++;
  		while ($i <= ($par[$posConta]+$posConta)) {
			$i++;
    	}				
		$sql .= ")) ";
	}   	
   	
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim saldoConta

//---------------------------------------------------------------
//Select busca saldo periodo
//---------------------------------------------------------------
public function saldoContaPeriodo($letra){
	
	$par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);
	
	
	$sql  = "SELECT  data, sum(saldo) as saldo ";
   	$sql .= "FROM fin_saldo ";

		if (array_sum($par) > 0){
   			$sql .= "WHERE (data >= '".$dataIni."') ";
   			$sql .= "AND (data <= '".$dataFim."') ";
			
			// sit lancamento
			if ($par[4] != '0'){
				$i = 5;	
   				$i++;
        		while ($i <= ($par[4]+4)) {
	   				$i++;
        		}				
			}

			// filial
			$posFilial = 5 + $par[4];
			if ($par[$posFilial] != '0'){
				$i = $posFilial + 1;	
   				$i++;
        		while ($i <= ($par[$posFilial]+$posFilial)) {
	   				$i++;
        		}				
			}
		
			// tipo lancamento
			$posTipoLanc = $posFilial + $par[$posFilial] + 1;
			if ($par[$posTipoLanc] != '0'){
				$i = $posTipoLanc + 1;	
   				$i++;
        		while ($i <= ($par[$posTipoLanc]+$posTipoLanc)) {
	   				$i++;
        		}				
			}
		
			// situacao documento
			$posSitDocto = $posTipoLanc + $par[$posTipoLanc] + 1;
			if ($par[$posSitDocto] != '0'){
				$i = $posSitDocto + 1;	
   				$i++;
        		while ($i <= ($par[$posSitDocto]+$posSitDocto)) {
	   				$i++;
        		}				
			}
			
		// Conta
			$posConta = $posSitDocto + $par[$posSitDocto] + 1;
			if ($par[$posConta] != '0'){
				$sql .= " AND ";
				$i = $posConta + 1;	
				$sql .= "(conta in (".$par[$i];
   				$i++;
        		while ($i <= ($par[$posConta]+$posConta)) {
	   				$i++;
        		}				
				$sql .= ")) ";
			}
		
		}   	
   	
   	$sql .= "group by data";
   	
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim saldoContaPeriodo

//---------------------------------------------------------------
// registros para mostrar no form saldo
//---------------------------------------------------------------
public function select_saldo_letra($letra){
	
	$par = explode("|", $letra);
	
	$sql = "SELECT ID, EXTRACT(month FROM s.data) as mes, EXTRACT(YEAR FROM s.data) as ano,s.conta, c.nomeinterno, s.data, s.saldo ";
	$sql .= "FROM fin_saldo s ";
	$sql .= "INNER JOIN fin_conta c on (c.conta = s.conta) ";
	$iswhere = false;
   	if ($par[0] != ''){
   		$sql .= "where (EXTRACT(month FROM s.data) = '".$par[0]."')";
   		$iswhere = true;
   	}
	if ($par[1] != ''){
			if ($iswhere){
				$sql .= "AND (EXTRACT(YEAR FROM s.data) = '".$par[1]."') ";}
			else{
				$iswhere = true;
				$sql .= "WHERE (EXTRACT(YEAR FROM s.data) = '".$par[1]."') ";}
		}
	if ($par[2] != ''){
			if ($iswhere){
				$sql .= "AND (s.conta = ".$par[2].") ";}
			else{
				$iswhere = true;
				$sql .= "WHERE (s.conta = ".$par[2].") ";}
		}
   	
	//ECHO  strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_saldo_geral

//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiSaldo(){

	$sql  = "INSERT INTO fin_saldo (CONTA, DATA, SALDO) ";
	$sql .= "VALUES (".$this->getConta().", '".$this->getData('B')."', ".$this->getSaldo('B')."); ";
					
        //echo $sql;
	$banco = new c_banco;
	$res_saldo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_saldo > 0){
        return '';
	}
	else{
        return 'Os dados da conta '.$this->getConta().' n&atilde;o foram cadastrados!';
	}
} // fim incluiSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------
public function alteraSaldo(){

	$sql  = "UPDATE fin_saldo ";
	$sql .= "SET saldo = ".$this->getSaldo('B')." ";
	$sql .= "WHERE (ID = ".$this->getId().")";
        //echo $sql;
	$banco = new c_banco;
	$res_saldo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_saldo > 0){
        return '';
	}
	else{
        return 'Os dados da conta '.$this->getConta().' n&atilde;o foram alterados!'.$res_saldo;
	}

}  // fim alteraSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------
public function excluiSaldo(){

	$sql  = "DELETE FROM fin_saldo ";
	$sql .= "WHERE (ID = ".$this->getId().")";
	$banco = new c_banco;
	$res_saldo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_saldo > 0){
        return '';
	}
	else{
        return 'Os dados da conta '.$this->getConta().' n&atilde;o foram excluidos!';
	}
	
}  // fim excluiSaldo

}	//	END OF THE CLASS
?>
