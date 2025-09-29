<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_tabela_preco_item extends c_user {

/**
 * TABLE NAME EST_TABELA_PRECO_ITEM
 */  
    
// Campos tabela
private $id           = NULL;
private $grupo        = NULL; 
private $codigo       = NULL; 
private $margem       = NULL; 
private $precofinal   = NULL;
private $precobase    = NULL; 

/**
* METODOS DE SETS E GETS
*/

public function setId($id){
    $this->id = $id;
}

public function getId(){
         return $this->id;
}

public function setGrupo($grupo){
	$this->grupo = $grupo;
}

public function getGrupo(){
	return $this->grupo;
}

public function setCodigo($codigo) {
	$this->codigo = $codigo;
}

public function getCodigo(){
	return $this->codigo;
}

public function setPrecoFinal($precofinal, $format=false) {
	$this->precofinal = $precofinal;
	if ($format):
			$this->precofinal = number_format($this->precofinal, 2, ',', '.');
	endif;
	
}

public function getPrecoFinal($format = null) {
	if (isset($this->precofinal)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd($this->precofinal);
							break;
					case 'F':
							return number_format((double) $this->precofinal, 2, ',', '.');
							break;
					default :
							return $this->precofinal;
			}
	else:
			return 0;
	endif;
}

public function setMargem($margem, $format=false) {
	$this->margem = $margem;
	if ($format):
			$this->margem = number_format($this->margem, 2, ',', '.');
	endif;
	
}

public function getMargem($format = null) {
	if (isset($this->margem)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd($this->margem);
							break;
					case 'F':
							return number_format((double) $this->margem, 2, ',', '.');
							break;
					default :
							return $this->margem;
			} else:
				return 0;
		endif;
}

public function setPrecoBase($precobase, $format=false) {
	$this->precobase = $precobase;
	if ($format):
			$this->precobase = number_format($this->precobase, 2, ',', '.');
	endif;
	
}

public function getPrecoBase($format = null) {
	if (isset($this->precobase)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd($this->precobase);
							break;
					case 'F':
							return number_format((double) $this->precobase, 2, ',', '.');
							break;
					default :
							return $this->precobase;
			}
	else:
			return 0;
	endif;
}

//############### FIM SETS E GETS ###############

public function buscar_tabela_preco_item() {
	$item = $this->select_tabela_preco_item();
	$this->setId($item[0]['ID']);
	$this->setGrupo($item[0]['GRUPO']);
	$this->setCodigo($item[0]['CODIGO']);
	$this->setMargem($item[0]['MARGEM']);
	$this->setPrecoFinal($item[0]['PRECOFINAL']);
	$this->setPrecoBase($item[0]['PRECOBASE']);
} 

public function existe_tabela_preco() {
	 $sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);
}


public function select_tabela_preco_item() {
	$sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getID() . "') ";
	$sql .= "AND (CODIGO = '" . $this->getCodigo() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function select_tabela_preco_item_geral($tabela = null) {
	$sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM ";
	if ($tabela != '') {
		$sql .= "WHERE ID = ".$tabela." ";
	}
	$sql .= "ORDER BY ID; ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function alterar_tabela_preco_item() {

	$sql = "UPDATE EST_TABELA_PRECO_ITEM ";
	$sql .= "SET GRUPO = '" . $this->getGrupo() . "', ";
	$sql .= " CODIGO = '" . $this->getCodigo() . "', ";
	$sql .= " PRECOFINAL = '" . $this->getPrecoFinal('B') . "', ";
	$sql .= " MARGEM = '" . $this->getMargem('B') . "', ";
	$sql .= " PRECOBASE = '" . $this->getPrecoBase('B') . "' ";
	$sql .= "WHERE (ID = '" . $this->getID() . "') ";
	$sql .= "AND (CODIGO = '" . $this->getCodigo() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi alterado!';
	}
}// alteraAtividade

public function excluir_tabela_preco_item() {
	$sql = "DELETE FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getId() . "') ";
	$sql .= "AND (CODIGO = '" . $this->getCodigo() . "') ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi excluida!';
	}
}
  

}	//	END OF THE CLASS
?>
