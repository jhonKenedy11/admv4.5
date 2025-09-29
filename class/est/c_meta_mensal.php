<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_meta_mensal extends c_user {
    
// Campos tabela
private $id               = NULL;
private $centrocusto      = NULL; 
private $totaldiames      = NULL; 
private $metamargem       = NULL; 
private $mes              = NULL; 
private $ano              = NULL; 

private $metaid           = NULL;
private $meta             = NULL;
private $vendedor         = NULL; 


/**
* METODOS DE SETS E GETS
*/

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

public function setTotalDiaMes($totaldiames){
	$this->totaldiames = $totaldiames;
}

public function getTotalDiaMes(){
	return $this->totaldiames;
}

public function setMetaMargem($metamargem, $format=false) {
	$this->metamargem = $metamargem;
	if ($format):
			$this->metamargem = number_format($this->metamargem, 2, ',', '.');
	endif;
	
}

public function getMetaMargem($format = null) {
	if (isset($this->metamargem)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd($this->metamargem);
							break;
					case 'F':
							return number_format((double) $this->metamargem, 2, ',', '.');
							break;
					default :
							return $this->metamargem;
			} else:
				return 0;
		endif;
}

public function setMes($mes){
	$this->mes = $mes;
}

public function getMes(){
			 return $this->mes;
}

public function setAno($ano){
	$this->ano = $ano;
}

public function getAno(){
			 return $this->ano;
}


public function setMetaId($metaid){
	$this->metaid = $metaid;
}

public function getMetaId(){
			 return $this->metaid;
}

public function setMeta($meta, $format=false) {
	$this->meta = $meta;
	if ($format):
			$this->meta = number_format($this->meta, 2, ',', '.');
	endif;
	
}

public function getMeta($format = null) {
	if (isset($this->meta)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd($this->meta);
							break;
					case 'F':
							return number_format((double) $this->meta, 2, ',', '.');
							break;
					default :
							return $this->meta;
			} else:
				return 0;
		endif;
}

public function setVendedor($vendedor){
	$this->vendedor = $vendedor;
}
public function getVendedor(){
	return $this->vendedor;
}


//############### FIM SETS E GETS ###############

public function buscar_meta_mensal() {
	$meta = $this->select_meta_mensal();
	$this->setId($meta[0]['ID']);
	$this->setTotalDiaMes($meta[0]['TOTALDIAMES']);
	$this->setCentroCusto($meta[0]['CCUSTO']);
	$this->setMetaMargem($meta[0]['METAMARGEM']);
	$this->setMeta($meta[0]['META']);
	$this->setAno($meta[0]['ANO']);
	$this->setMes($meta[0]['MES']);
} 

public function existe_meta_mensal() {
	$sql = "SELECT * ";
	$sql .= "FROM FAT_META_MENSAL ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);
}


public function select_meta_mensal() {
	$sql = "SELECT * ";
	$sql .= "FROM FAT_META_MENSAL ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function select_meta_mensal_geral() {
	$sql = "SELECT * ";
	$sql .= "FROM FAT_META_MENSAL ";
	$sql .= "ORDER BY ID; ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}


public function incluir_meta_mensal($conn=null) {

	$sql = "INSERT INTO FAT_META_MENSAL(ANO, MES, TOTALDIAMES, METAMARGEM, CCUSTO, META) ";
	$sql .= "VALUES ('".$this->getAno()."', '".$this->getMes()."', '";
	$sql .= $this->getTotalDiaMes()."', '".$this->getMetaMargem('B')."', '";
	$sql .= $this->getCentroCusto()."', '".$this->getMeta('B'). "'); ";
	
	$banco = new c_banco;
	$res = $banco->exec_sql($sql,$conn);
    
	if ($banco->result):
			$lastReg = mysqli_insert_id($conn);
			$banco->close_connection();
			return $lastReg;
	else:
			$banco->close_connection();
			return 'Meta não foi cadastrada!';
	endif;

} 

public function alterar_meta_mensal() {

	$sql = "UPDATE FAT_META_MENSAL ";
	$sql .= "SET MES = '" . $this->getMes() . "', ";
	$sql .= " ANO = '" . $this->getAno() . "', ";
	$sql .= " TOTALDIAMES = '" . $this->getTotalDiaMes() . "', ";
	$sql .= " METAMARGEM = '" . $this->getMetaMargem('B') . "', ";
	$sql .= " META = '" . $this->getMeta('B') . "', ";
	$sql .= " CCUSTO = '" . $this->getCentroCusto() . "' ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Meta n&atilde;o foi alterado!';
	}
}

public function excluir_meta_mensal($tabela = '') {
	$sql = "DELETE FROM FAT_META_MENSAL".$tabela." ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Meta n&atilde;o foi excluida!';
	}
}

public function incluir_meta_mensal_vendedor($conn=null) {

	$sql = "INSERT INTO FAT_META_MENSAL_VENDEDOR(METAID, META, VENDEDOR) ";
	$sql .= "VALUES ('".$this->getMetaId()."', '".$this->getMeta('B')."', '";
	$sql .= $this->getVendedor(). "'); ";
	
	$banco = new c_banco;
	$res = $banco->exec_sql($sql,$conn);
    
	if ($banco->result):
			$lastReg = mysqli_insert_id($conn);
			$banco->close_connection();
			return $lastReg;
	else:
			$banco->close_connection();
			return 'Meta não foi cadastrada!';
	endif;

} 

public function buscar_meta_vendedores() {
	$sql = "SELECT * ";
	$sql .= "FROM FAT_META_MENSAL_VENDEDOR ";
	$sql .= "WHERE (METAID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} 

public function buscar_meta_mensal_vendedor() {
	$meta = $this->select_meta_mensal_vendedor();
	$this->setId($meta[0]['ID']);
	$this->setMetaId($meta[0]['METAID']);
	$this->setMeta($meta[0]['META']);
	$this->setVendedor($meta[0]['VENDEDOR']);
}

public function select_meta_mensal_vendedor() {
	$sql = "SELECT * ";
	$sql .= "FROM FAT_META_MENSAL_VENDEDOR ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}


public function alterar_meta_mensal_vendedor() {

	$sql = "UPDATE FAT_META_MENSAL_VENDEDOR ";
	$sql .= "SET VENDEDOR = '" . $this->getVendedor() . "', ";
	$sql .= " META = '" . $this->getMeta('B') . "' ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Meta n&atilde;o foi alterado!';
	}
}

public function excluir_meta_mensal_vendedor() {
	$sql = "DELETE FROM FAT_META_MENSAL_VENDEDOR ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Meta n&atilde;o foi excluida!';
	}
}

}	//	END OF THE CLASS
?>
