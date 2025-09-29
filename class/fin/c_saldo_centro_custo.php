<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir."/../../bib/c_tools.php");
//Class C_SALDO_CENTRO_CUSTO
Class c_saldo_centro_custo extends c_user {

// Campos tabela | Objetos da classe
private $id = NULL;
private $centrocusto = NULL;
private $data = NULL;
private $saldo = NULL;

//construtor
function c_saldo_centro_custo(){ }

public function setId($id){
         $this->id = $id;
}

public function getId(){
         return $this->id;
}

public function setCentroCusto($centrocusto){
         $this->centrocusto = $centrocusto;
}

public function getCentroCusto(){
         return $this->centrocusto;
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

public function setSaldo($saldo, $format = null) { 
    $this->saldo = $saldo ?? []; 
    if ($format){
            $this->saldo = number_format($this->saldo, 2, ',', '.');
	}
    
}
public function getSaldo($format = null) {
	if ($format=='F') {
                        return number_format((float)$this->saldo, 2, ',', '.'); }
		else if ($format=='B'){      
                    $this->saldo = c_tools::moedaBd($this->saldo);
                    return $this->saldo;
                        
                }else {
                    return $this->saldo;
                }	
}    


public function select_saldo_geral(){
	$sql  = "SELECT  * ";
  $sql .= "FROM fin_centro_custo_saldo ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_saldo


public function select_saldo_centro_custo(){
	$sql  = "SELECT  * ";
   	$sql .= "FROM fin_centro_custo_saldo ";
	$sql .= "WHERE (ID = ".$this->getId().")";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_saldo


//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiSaldo(){

	$sql  = "INSERT INTO fin_centro_custo_saldo (CENTROCUSTO, DATA, SALDO) ";
	$sql .= "VALUES ('".$this->getCentroCusto()."', '".$this->getData('B')."', ".$this->getSaldo('B')."); ";
					
        //echo $sql;
	$banco = new c_banco;
	$res_saldo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_saldo > 0){
        return '';
	}
	else{
        return 'Os dados do centro de custo '.$this->getCentroCusto().' n&atilde;o foram cadastrados!';
	}
} // fim incluiSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------
public function alteraSaldo(){

	$sql  = "UPDATE fin_centro_custo_saldo ";
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
        return 'Os dados do centro de custo '.$this->getCentroCusto().' n&atilde;o foram alterados!'.$res_saldo;
	}

}  // fim alteraSaldo

//---------------------------------------------------------------
//---------------------------------------------------------------
public function excluiSaldo(){

	$sql  = "DELETE FROM fin_centro_custo_saldo ";
	$sql .= "WHERE (ID = ".$this->getId().")";
	$banco = new c_banco;
	$res_saldo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_saldo > 0){
        return '';
	}
	else{
        return 'Os dados do centro de custo '.$this->getCentroCusto().' n&atilde;o foram excluidos!';
	}
	
}  // fim excluiSaldo

public function select_saldo_letra($letra){
	
	$par = explode("|", $letra);
	
	$sql = "SELECT ID, EXTRACT(month FROM s.data) as mes, EXTRACT(YEAR FROM s.data) as ano,s.centrocusto, c.descricao, s.data, s.saldo ";
	$sql .= "FROM fin_centro_custo_saldo s ";
	$sql .= "INNER JOIN fin_centro_custo c on (c.centrocusto = s.centrocusto) ";
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
				$sql .= "AND (s.centrocusto = ".$par[2].") ";}
			else{
				$iswhere = true;
				$sql .= "WHERE (s.centrocusto = ".$par[2].") ";}
		}
   	
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_saldo_geral

public function existeSaldo(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_centro_custo_saldo ";
	$sql .= "WHERE (centrocusto = ".$this->getCentroCusto().") AND (data = '".$this->getData('B')."')";
	//ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeSaldo

}	//	END OF THE CLASS
?>
