<?php
/**
 * @package   astec
 * @name      c_grupo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_grupo
Class c_grupo extends c_user {
/**
 * TABLE NAME EST_GRUPO
 */
    
    
// Campos tabela
private $id         = NULL; // VARCHAR(15)
private $grupoBase  = NULL; // VARCHAR(15)
private $desc       = NULL; // VARCHAR(40)
private $Tipo       = NULL; // CHAR(1)
private $Nivel      = NULL; // SMALLINT(6)



/**
* METODOS DE SETS E GETS
*/

public function setId($grupo){
    $tools = new c_tools();
	$this->id = $tools->LimpaCamposGeral($grupo);

}
public function getId(){
         return $this->id;
}

public function setGrupoBase($grupoBase){
		$tools = new c_tools();
         $this->grupoBase = $tools->LimpaCamposGeral($grupoBase);
}
public function getGrupoBase(){
         return $this->grupoBase;
}
public function setDesc($desc){
         $this->desc = addslashes($desc);
}
public function getDesc(){
         return $this->desc;
}
public function setGrupo($grupo){
         $this->grupo = $grupo;
}
public function getGrupo(){
         return $this->grupo;
}
public function setComissaoVendas($comissao) {
    $this->comissao = $comissao;
}
public function getComissaoVendas($format = null) {
    if ($format=='F') {
		return number_format((float)$this->comissao, 2, ',', '.'); 

	}else if ($format=='B'){      
		$this->comissao = c_tools::moedaBd($this->comissao);
		return $this->comissao;
					
	}else {
		return $this->comissao == null ? 0 : $this->comissao;
	}	
}


public function setTipo($tipo){
         $this->Tipo = strtoupper($tipo);
}
public function getTipo(){
         return $this->Tipo;
}

public function setNivel($Nivel){
         $this->Nivel = $Nivel;
}
public function getNivel(){
         return $this->Nivel;
}

//############### FIM SETS E GETS ###############

/**
 * Funcao para verificar a existencia de registro com o mesmo ID
 * @name existeDocumento
 * @param VAHCHAR GetId Chave primaria da tabela
 * @return BOOLEAN Caso encontre registro True
 */
public function existeDocumento(){
	$sql  = "SELECT * ";
	$sql .= "FROM est_grupo ";
	$sql .= "WHERE (grupo = '".$this->getId()."')";
        // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeDocumento

/**
 * Funcao de consulta atraves do ID da table
 * @name select_grupo
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function select_grupo(){
	$sql  = "SELECT * ";
   	$sql .= "FROM est_grupo ";
   	$sql .= "WHERE (id = '".$this->getId()."') ";
        // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_grupo

/**
 * Funcao de consulta para todos os registros da tabela
 * @name select_grupo_geral
 * @return ARRAY de todas as colunas da table
 */
public function select_grupo_geral(){
	$sql  = "SELECT G.GRUPO, G.DESCRICAO, A.PADRAO, G.NIVEL, G.ID FROM EST_GRUPO G
		INNER JOIN AMB_DDM A ON A.ALIAS = 'EST_MENU' AND A.CAMPO = 'TIPOGRUPO' AND A.TIPO = G.TIPO
		ORDER BY G.GRUPO, G.NIVEL"
	;
        // echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_grupo_geral

/**
 * Funcao de Consulta atraves da descricao
 * @name select_grupo_letra
 * @param VARCHAR $letra Coluna Descricao  da tabela
 * @return ARRAY de todas as colunas da table
 */
public function select_grupo_letra($letra){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM est_grupo ";
  	$sql .= "WHERE descricao LIKE '".$letra."%' ";
   	$sql .= "ORDER BY descricao ";
        // echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_grupo_letra

/**
* Funcao para Inclusao no banco
* @name incluiGrupo
* @return string vazio se ocorrer com sucesso
*/
public function incluiGrupo(){
	if ($this->getGrupoBase() != ''){
		$grupo = $this->getGrupoBase().".".$this->getId();	
	}
	else {
		$grupo = $this->getId();	
	}
	
	$sql  = "INSERT INTO est_grupo (GRUPO, descricao, Tipo, nivel, COMISSAOVENDAS, USERINSERT) ";
	$sql .= "VALUES 
		('".$this->getGrupo()."',
		'".$this->getDesc()."', 
		'".$this->getTipo()."', '".$this->getNivel()."','".$this->getComissaoVendas('B')."','".$this->m_userid."'); ";
        // echo strtoupper($sql);
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$lastReg = mysqli_insert_id($banco->id_connection);
    $banco->close_connection();

	if($resgrupo > 0){
        return $lastReg;
	}
	else{
        return 'Os dados do Grupo '.$this->getDesc().' n&atilde;o foi cadastrado!';
	}
} // fim incluigrupo

/**
* Funcao para Alteracao no banco
* @name alteraGrupo
* @return string vazio se ocorrer com sucesso
*/
public function alteraGrupo(){
	
	$comissaoVendas = str_replace(',', '.', $this->getComissaoVendas());

	$sql  = "UPDATE est_grupo ";
	$sql .= "SET ";
	$sql .= "grupo = '".$this->getGrupo()."', " ;
	$sql .= "descricao = '".$this->getDesc()."', " ;
	$sql .= "tipo = '".$this->getTipo()."', ";
	$sql .= "nivel= '".$this->getNivel()."', ";
	$sql .= "COMISSAOVENDAS = ".$comissaoVendas.", ";
	$sql .= "userchange = '".$this->m_userid."', ";
	$sql .= "datechange = now() ";
	$sql .= "WHERE id = '".$this->getId()."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$lastReg = mysqli_affected_rows($banco->id_connection);
	// echo strtoupper($sql);
	$banco->close_connection();

	if($lastReg > 0){
		return $lastReg;
	}
	else{
        return 'Os dados do Grupo '.$this->getDesc().' n&atilde;o foi alterado!';
	}

}  // fim alteragrupo

/**
* Funcao para Inclusao no banco
* @name excluiGrupo
* @return string vazio se ocorrer com sucesso
*/
public function excluiGrupo(){
	$sql  = "DELETE FROM est_grupo ";
	$sql .= "WHERE id = '".$this->getId()."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
        // echo strtoupper($sql);
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Grupo '.$this->getId().' n&atilde;o foi excluido!';
	}
}  // fim excluigrupo

}	//	END OF THE CLASS
?>
