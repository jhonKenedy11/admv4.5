<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_tabela_preco extends c_user {

/**
 * TABLE NAME EST_TABELA_PRECO
 */  
    
// Campos tabela
private $id           = NULL;
private $nome         = NULL; 
private $validade     = NULL; 
private $centrocusto  = NULL; 
private $precobase    = NULL;  
private $margem       = NULL; 

/**
* METODOS DE SETS E GETS
*/

public function setId($id){
    $this->id = $id;
}

public function getId(){
         return $this->id;
}

public function setNome($nome){
	$this->nome = $nome;
}

public function getNome(){
	return $this->nome;
}

public function setValidade($validade) {$this->validade = $validade;}

public function getValidade($format = null) {
  $this->validade = strtr($this->validade, "/", "-");
  switch ($format) {
			case 'F':
					return date('d/m/Y H:i', strtotime($this->validade));
					break;
			case 'B':
					return c_date::convertDateBd($this->validade, $this->m_banco);
					break;
			default:
					return $this->validade;
	}
}

public function setCentroCusto($centrocusto){
         $this->centrocusto = $centrocusto;
}
public function getCentroCusto(){
         return $this->centrocusto;
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

//############### FIM SETS E GETS ###############

public function buscar_tabela_preco() {
	$tabela = $this->select_tabela_preco();
	$this->setId($tabela[0]['ID']);
	$this->setNome($tabela[0]['NOME']);
	$this->setValidade($tabela[0]['VALIDADE']);
	$this->setCentroCusto($tabela[0]['CCUSTO']);
	$this->setPrecoBase($tabela[0]['PRECOBASE']);
	$this->setMargem($tabela[0]['MARGEM']);
} 

public function existe_tabela_preco() {
	 $sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);
}


public function select_tabela_preco() {
	$sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function select_tabela_preco_geral() {
	$sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO ";
	$sql .= "ORDER BY ID; ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}


public function incluir_tabela_preco($conn=null) {

	$sql = "INSERT INTO EST_TABELA_PRECO (NOME, VALIDADE, CCUSTO, PRECOBASE, MARGEM) ";
	$sql .= "VALUES ('".$this->getNome()."', '".$this->getValidade('B')."', '".$this->getCentroCusto()."', '".$this->getPrecoBase()."', '".$this->getMargem('B'). "'); ";
	
	$banco = new c_banco;
	$res = $banco->exec_sql($sql,$conn);
    
	if ($banco->result):
			$lastReg = mysqli_insert_id($conn);
			$banco->close_connection();
			return $lastReg;
	else:
			$banco->close_connection();
			return 'Tabela ' . $this->getNome() . ' nao foi cadastrado!';
	endif;

} 

public function alterar_tabela_preco() {

	$sql = "UPDATE EST_TABELA_PRECO ";
	$sql .= "SET NOME = '" . $this->getNome() . "', ";
	$sql .= " VALIDADE = '" . $this->getValidade('B') . "', ";
	$sql .= " CCUSTO = '" . $this->getCentroCusto() . "', ";
	$sql .= " PRECOBASE = '" . $this->getPrecoBase('B') . "', ";
	$sql .= " MARGEM = '" . $this->getMargem('B') . "' ";
	$sql .= "WHERE (ID = '" . $this->getID() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi alterado!';
	}
}// alteraAtividade

public function excluir_tabela_preco($tabela = '') {
	$sql = "DELETE FROM EST_TABELA_PRECO".$tabela." ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi excluida!';
	}
}

public function select_itens() {
	$sql = "SELECT CODIGO, VENDA, GRUPO ";
	$sql .= "FROM EST_PRODUTO ";
	$sql .= "ORDER BY CODIGO ASC ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function insere_item_tabela_preco($id, $grupo, $codigo, $precobase, $margem, $precofinal) {
	$sql = "INSERT INTO EST_TABELA_PRECO_ITEM ( ";
	$sql .= "ID, GRUPO, CODIGO, PRECOBASE, MARGEM, PRECOFINAL ) VALUES ( ";
	$sql .= $id.", '".$grupo."', '".$codigo."', '".$precobase."', ".$margem.", ".$precofinal."); ";
	
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

  

}	//	END OF THE CLASS
?>
