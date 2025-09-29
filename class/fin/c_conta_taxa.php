<?php

$dir = dirname(__FILE__);

Class c_conta_taxa extends c_user {

private $id = NULL;
private $conta = NULL; 
private $condpgto = NULL; 
private $taxa = NULL; 

function __construct(){}

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

public function setCondpgto($condpgto){
         $this->condpgto = $condpgto;
}

public function getCondpgto(){
				 return $this->condpgto;
}

public function setTaxa($taxa) {
	$this->taxa = $taxa;
}

public function getTaxa($format = NULL) {
		if ($format=='F') {
			return number_format($this->taxa, 2, ',', '.'); }
		else {
			if ($this->taxa != null){
				$num = str_replace(',', '.', $this->taxa);
				return $num; }
			else{
				return 0; }
		}	
}

public function select_conta_taxa_geral(){
	$sql  = "SELECT DISTINCT * ";
 	$sql .= "FROM fin_conta_taxa ";
 	$sql .= "ORDER BY conta ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} 

public function select_conta_taxa(){

	$sql  = "SELECT DISTINCT * ";
  $sql .= "FROM fin_conta_taxa ";
  $sql .= "WHERE (id = ".$this->getId().")";
 
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} 

public function existeContaTaxa(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_conta_taxa ";
	$sql .= "WHERE (conta = ".$this->getConta().") and";
	$sql .= "(condpgto = ".$this->getCondpgto().")";

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} 

public function incluiContaTaxa(){

	$sql  = "INSERT INTO fin_conta_taxa (conta, condpgto, taxa) ";
	$sql .= "VALUES ('".$this->getConta()."', '".$this->getCondpgto()."', '".$this->getTaxa()."')";
					
	$banco = new c_banco;
	$resultado =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($resultado > 0){
        return '';
	}
	else{
        return 'Os dados não foram cadastrados!';
	}
} 

public function alteraContaTaxa(){

	$sql  = "UPDATE fin_conta_taxa ";
	$sql .= "SET  conta = '".$this->getConta()."', " ;
	$sql .= "condpgto = '".$this->getCondpgto()."', " ;
	$sql .= "taxa = '".$this->getTaxa()."' " ;
	$sql .= "WHERE id = ".$this->getId().";";
	$banco = new c_banco;
	$resultado =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($resultado > 0){
        return '';
	}
	else{
        return 'Os dados não foram alterados!';
	}

}  

public function excluiContaTaxa(){

	$sql  = "DELETE FROM fin_conta_taxa ";
	$sql .= "WHERE id = ".$this->getId();
	$banco = new c_banco;
	$resultado =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($resultado > 0){
        return '';
	}
	else{
        return 'Os dados não foram excluidos!';
	}
	
} 

}	
?>
