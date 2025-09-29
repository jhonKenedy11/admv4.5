<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_meta
Class c_meta extends c_user {

/**
 * TABLE NAME FAT_METAS_MENSAL
 */  
    
// Campos tabela
private $id         = NULL; 
private $vendedor   = NULL; 
private $ano        = NULL; 
private $mes        = NULL; 
private $meta       = NULL; 

/**
* METODOS DE SETS E GETS
*/

public function setId($id){
    $this->id = $id;
}
public function getId(){
         return $this->id;
}

public function setVendedor($vendedor){
         $this->vendedor = $vendedor;
}
public function getVendedor(){
         return $this->vendedor;
}
public function setAno($ano){
         $this->ano = $ano;
}
public function getAno(){
         return $this->ano;
}

public function setMes($mes){
         $this->mes = $mes;
}
public function getMes(){
         return $this->mes;
}

public function setMeta($meta){
         $this->meta = $meta;
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
			}
	else:
			return 0;            
	endif;        
}
//############### FIM SETS E GETS ###############


public function existeMeta(){
	$sql  = "SELECT * ";
	$sql .= "FROM FAT_METAS_MENSAL ";
	$sql .= "WHERE (VENDEDOR = '".$this->getVendedor()."') AND ";
	$sql .= "(ANO = '".$this->getAno()."') AND ";
	$sql .= "(MES = '".$this->getMes()."')";
        // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} 


public function select_meta(){
	$sql  = "SELECT * ";
	$sql .= "FROM FAT_METAS_MENSAL  ";
	$sql .= "WHERE (ID = '".$this->getId()."')";	

  $banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} 

public function select_meta_geral(){
	$sql  = "SELECT M.*, U.NOME ";
	$sql .= "FROM FAT_METAS_MENSAL M ";
	$sql .= "LEFT JOIN AMB_USUARIO U on (M.VENDEDOR = U.USUARIO) ";

  $banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} 


public function incluiMeta(){
	$sql  = "INSERT INTO FAT_METAS_MENSAL (VENDEDOR, ANO, MES, META) ";
	$sql .= "VALUES ('".$this->getVendedor()."', '".$this->getAno()."', '".$this->getMes()."', ".$this->getMeta('B')."); ";
	$banco = new c_banco;
	$resMeta =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($resMeta > 0){
        return '';
	}
	else{
        return 'A Meta do vendedor '.$this->getVendedor().' n&atilde;o foi cadastrado!';
	}
} // fim incluiMeta

public function alteraMeta(){

	$sql  = "UPDATE FAT_METAS_MENSAL ";
    $sql .= "SET ";
    $sql .= "vendedor = '".$this->getVendedor()."', ";
    $sql .= "ano = ".$this->getAno().", ";
    $sql .= "mes = ".$this->getMes().", ";
    $sql .= "meta = ".$this->getMeta('B')." " ;
    $sql .= "WHERE ID = '".$this->getId()."'"; 
    
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'n&atilde;o foi possível alterar a meta!';
	}

}  

public function excluiMeta(){
	$sql  = "DELETE FROM FAT_METAS_MENSAL ";
	$sql .= "WHERE ID = '".$this->getId()."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'n&atilde;o foi excluida a meta!';
	}
}  

}	//	END OF THE CLASS
?>
