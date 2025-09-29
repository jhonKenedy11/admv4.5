<?php

/****************************************************************************
*Cliente...........:
*Contratada........: ADM
*Desenvolvedor.....: LUCAS TORTOLA DA SILVA BUCKO
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: C_GENERO_PAGAMENTO - BUSINESS CLASS
*Ultima Atualizacao: 13/04/2012
****************************************************************************/
/**
 * @package   astecv3
 * @name      c_genero
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/06/2016
 */

//Class C_GENERO
Class c_genero extends c_user {

// Campos tabela
private $genero = NULL;
private $tipo = NULL;
private $descricao = NULL;
private $tipoLancamento = NULL;

//construtor
function __construct(){
	// Cria uma instancia variaveis de sessao
	session_start();
	c_user::from_array($_SESSION['user_array']);
}

//---------------------------------------------------------------
//---------------------------------------------------------------
/**
* METODOS DE SETS E GETS
*/
public function setGenero($genero){
         $this->genero = $genero;
}

public function getGenero(){
         return $this->genero;
}

public function setTipo($tipo){
         $this->tipo = strtoupper($tipo);
}

public function getTipo(){
         return $this->tipo;
}

public function setDescricao($descricao){ 
		$this->descricao = strtoupper($descricao);
}

public function getDescricao(){
         return strtoupper($this->descricao);
}

public function setTipoLancamento($tipoLancamento){
         $this->tipoLancamento = strtoupper($tipoLancamento);
}

public function getTipoLancamento(){
         return $this->tipoLancamento;
}

//############### FIM SETS E GETS ###############

 /**
 * @name existeGenero
 * @description pesquisa se já existe código do genero
 */
public function existeGenero(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_genero ";
	$sql .= "WHERE (genero = '".$this->getGenero()."')";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeGenero

 /**
 * @name select_genero
 * @description pesquisa se já existe código do banco cadastrado
 */
public function select_genero(){

	$sql  = "SELECT * ";
   	$sql .= "FROM fin_genero ";
   	$sql .= "WHERE (genero = '".$this->getGenero()."') ";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_genero

 /**
 * @name select_genero_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_genero_geral(){
	$sql  = "SELECT DISTINCT g.genero, g.descricao, g.tipo, g.tipolancamento, t.padrao as desclancamento, v.padrao as desctipo ";
   	$sql .= "FROM fin_genero g ";
        $sql .= "inner join amb_ddm t on ((t.alias='FIN_MENU') and (t.campo='TipoLanc') and (t.tipo = g.tipolancamento)) ";
        $sql .= "inner join amb_ddm v on ((v.alias='FIN_MENU') and (v.campo='TipoGeneroPgto') and (v.tipo = g.tipo)) ";
   	$sql .= "ORDER BY genero ";
	//ECHO strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_genero_geral

 /**
 * @name select_genero_letra
 * @description pesquisa que retorna os registros cadastrado de acordo com a seleção algabetica
 */
public function select_genero_letra($letra){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_genero ";
  	$sql .= "WHERE descricao LIKE '".$letra."%' ";
   	$sql .= "ORDER BY genero ";
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_genero_letra

 /**
 * @name incluiGenero
 * @description faz a inclusão de registro cadastrado
 */public function incluiGenero(){

	$sql  = "INSERT INTO fin_genero (GENERO, TIPO, DESCRICAO, TIPOLANCAMENTO) ";
	$sql .= "VALUES ('".$this->getGenero()."', '".$this->getTipo()."', '".$this->getDescricao()."', '".$this->getTipoLancamento()."') ";
					
    // echo $sql;
	$banco = new c_banco;
	$result = $banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;
} // fim incluiGenero

 /**
 * @name alteraGenero
 * @description altera registro existente
 */
public function alteraGenero(){

	$sql  = "UPDATE fin_genero ";
	$sql .= "SET  genero = '".$this->getGenero()."', " ;
	$sql .= "tipo = '".$this->getTipo()."', " ;
	$sql .= "tipolancamento = '".$this->getTipoLancamento()."', " ;
	$sql .= "descricao = '".$this->getDescricao()."' " ;
	$sql .= "WHERE genero = '".$this->getGenero()."';";
        //echo $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result; 
	$banco->close_connection();
	
	return $status;
}  // fim alteraGenero

 /**
 * @name exlcuiGenero
 * @description esclui resgistro existe
 */
public function excluiGenero(){

	$sql  = "DELETE FROM fin_genero ";
	$sql .= "WHERE genero = ".$this->getGenero();
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;

	
}  // fim excluiGeneroPag

}	//	END OF THE CLASS
?>
