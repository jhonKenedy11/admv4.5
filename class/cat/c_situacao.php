<?php
/**
 * @package   admv4.3.1
 * @name      c_situacao
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class c_anuncio_mkp
Class c_situacao extends c_user {
/**
 * TABLE NAME CAT_SITUACAO
 */    
    
// Campos tabela
private $id         	= NULL; // INT(11)
private $descricao  	= NULL; // VARCHAR(50)
private $ativo  		= NULL; // CHAR(1)
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
 * @name select_situacao
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_situacao(){
	$result = $this->select_situacao();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('ATIVO',$result[0]['ATIVO']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$created_at[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Situacao
 * @description pesquisa se já existe código da situacao cadastrado
 */
public function select_situacao(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_situacao ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_situacao

 /**
 * @name select_situacao_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_situacao_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_situacao ";
   	$sql .= "ORDER BY ID ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;



} //fim select_situacao_geral

 /**
 * @name incluiSituacao
 * @description faz a inclusão do registro cadastrado
 */
public function incluiSituacao(){

	$sql  = "INSERT INTO cat_situacao (DESCRICAO, ATIVO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."', '".$this->__get('ATIVO')."', ".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
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
} // fim incluiSituacao

 /**
 * @name alteraSituacao
 * @description altera registro existente
 */
public function alteraSituacao(){

	$sql  = "UPDATE cat_situacao ";
	$sql .= "SET DESCRICAO = '".$this->__get('DESCRICAO')."', " ;
	$sql .= "ATIVO = '".$this->__get('ATIVO')."',";
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

}  // fim alteraSituacao

 /**
 * @name exlcuiSituacao
 * @description esclui resgistro existe
 */
public function excluiSituacao(){

	$sql  = "DELETE FROM cat_situacao ";
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
	
}  // fim excluiSituacao

}	//	END OF THE CLASS
?>
