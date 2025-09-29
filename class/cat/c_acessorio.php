<?php
/**
 * @package   astec
 * @name      c_acessorio
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class c_anuncio_mkp
Class c_acessorio extends c_user {
/**
 * TABLE NAME CAT_ACESSORIO
 */    
    
// Campos tabela
private $id         	= NULL; // INT(11)
private $descricao  	= NULL; // VARCHAR(50)
private $created_user  	= NULL; // INT(11)
private $update_user  	= NULL; // INT(11)
private $created_at	    = NULL; // TIMESTAMP
private $update_at     	= NULL; //TIMESTAMP

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

}

/**
 * Funcao de consulta atraves do ID da table
 * @name select_grupo
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_acessorio(){
	$result = $this->select_acessorio();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$created_at[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Banco
 * @description pesquisa se já existe código do banco cadastrado
 */
public function select_acessorio(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_acessorio ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_banco

 /**
 * @name select_banco_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_acessorio_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_acessorio ";
   	$sql .= "ORDER BY ID ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
		//contas_acompanhamento
	$banco->close_connection();
	return $banco->resultado;



} //fim select_banco_geral

 /**
 * @name incluiBanco
 * @description faz a inclusão do registro cadastrado
 */
public function incluiAcessorio(){

	$sql  = "INSERT INTO cat_acessorio (DESCRICAO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ('".$this->__get('DESCRICAO')."',".$this->m_userid.",'".date("Y-m-d H:i:s"). "';)"; 
					
    // echo $sql;
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram cadastrados!';
	}
} // fim incluiAcessorio

 /**
 * @name alteraAcessorio
 * @description altera registro existente
 */
public function alteraAcessorio(){

	$sql  = "UPDATE cat_acessorio ";
	$sql .= "SET DESCRICAO = '".$this->__get('DESCRICAO')."', " ;
	$sql .= "UPDATED_USER = ".$this->m_userid.", ";
	$sql .= "UPDATED_AT = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE id = ".$this->__get('ID').";";
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram alterados!';
	}

}  // fim alteraBanco

 /**
 * @name exlcuiAcessorio
 * @description esclui resgistro existe
 */
public function excluiAcessorio(){

	$sql  = "DELETE FROM cat_acessorio ";
	$sql .= "WHERE id = ".$this->__get('ID');
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram excluidos!';
	}
	
}  // fim excluiBanco

}	//	END OF THE CLASS
?>
