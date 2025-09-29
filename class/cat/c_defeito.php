<?php
/**
 * @package   admv4.3.1
 * @name      c_defeito
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class c_anuncio_mkp
Class c_defeito extends c_user {
/**
 * TABLE NAME CAT_DEFEITO
 */    
    
// Campos tabela
private $id         	= NULL; // INT(11)
private $descricao  	= NULL; // VARCHAR(60)
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
public function set_defeito(){
	$result = $this->select_defeito();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$created_at[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Defeito
 * @description pesquisa se já existe código do defeito cadastrado
 */
public function select_defeito(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_defeito ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_defeito

 /**
 * @name select_defeito_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_defeito_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_defeito ";
   	$sql .= "ORDER BY ID ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;



} //fim select_defeito_geral

 /**
 * @name incluiDefeito
 * @description faz a inclusão do registro cadastrado
 */
public function incluiDefeito(){

	$sql  = "INSERT INTO cat_defeito (DESCRICAO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."',".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
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
} // fim incluiDefeito

 /**
 * @name alteraDefeito
 * @description altera registro existente
 */
public function alteraDefeito(){

	$sql  = "UPDATE cat_defeito ";
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

}  // fim alteraDefeito

 /**
 * @name exlcuiDefeito
 * @description esclui resgistro existe
 */
public function excluiDefeito(){

	$sql  = "DELETE FROM cat_defeito ";
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
	
}  // fim excluiDefeito

}	//	END OF THE CLASS
?>
