<?php
/**
 * @package   astecv3
 * @name      c_banco
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      23/04/2016
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_FIN_BANCO
Class c_bancos extends c_user {

     /*
     * TABLE NAME FIN_BANCO
     */     
// Campos tabela
// public $id = NULL; //smallint
// public $nome = NULL;  //varchar(60)


//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);
}

//############### FIM SETS E GETS ###############

 /**
 * @name existeBanco
 * @description pesquisa se já existe código do banco
 */
public function existeBanco(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_banco ";
	$sql .= "WHERE (banco = ".$this->__get('id').")";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeBanco

 /**
 * @name select_Banco
 * @description pesquisa se já existe código do banco cadastrado
 */
public function select_banco(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_banco ";
   	$sql .= "WHERE (banco = ".$this->__get('id').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$result = $banco->exec_sql($sql);
	$banco->close_connection();

	if($result == false)
		throw new Exception('Erro na consulta dos dados '.$this->__get('id').'!<br>'.$result);
	else
        return $result;
} //fim select_banco

 /**
 * @name select_banco_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_banco_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_banco ";
   	$sql .= "ORDER BY banco ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$result = $banco->exec_sql($sql);
	$banco->close_connection();

	if($result == false)
		throw new Exception('Erro na consulta dos dados '.$this->__get('id').'!<br>'.$result);
	else
        return $result;
} //fim select_banco_geral

 /**
 * @name incluiBanco
 * @description faz a inclusão do registro cadastrado
 */
public function incluiBanco(){

	$sql  = "INSERT INTO fin_banco (banco, nome) ";
	$sql .= "VALUES (".$this->__get('id').", '".$this->__get('nome')."')";
					
    // echo $sql;
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->result;
	$status = $banco->result;
	$banco->close_connection();

	return $status;
} // fim incluiBanco

 /**
 * @name alteraBanco
 * @description altera registro existente
 */
public function alteraBanco(){

	$sql  = "UPDATE fin_banco ";
	$sql .= "SET  banco = ".$this->__get('id').", " ;
	$sql .= "nome = '".$this->__get('nome')."' " ;
	$sql .= "WHERE banco = ".$this->__get('id').";";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;

}  // fim alteraBanco

 /**
 * @name exlcuiBanco
 * @description esclui resgistro existe
 */
public function excluiBanco(){

	$sql  = "DELETE FROM fin_banco ";
	$sql .= "WHERE banco = ".$this->__get('id');
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;
	
}  // fim excluiBanco

}	//	END OF THE CLASS
?>
