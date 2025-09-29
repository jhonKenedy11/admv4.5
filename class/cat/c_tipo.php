<?php
/**
 * @package   admv4.3.1
 * @name      c_tipo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class c_anuncio_mkp
Class c_tipo extends c_user {
/**
 * TABLE NAME CAT_TIPO
 */    
    
// Campos tabela
private $id         			= NULL; // INT(11)
private $descricao  			= NULL; // VARCHAR(50)
private $garantia				= NULL; // CHAR(1)
private $cobservico				= NULL; // CHAR(1)
private $cobpecas				= NULL; // CHAR(1)
private $cobdespesas			= NULL; // CHAR(1)
private $cobtipopreco			= NULL; // CHAR(1)
private $diasgarantiapecas		= NULL; // INT(11)
private $diasgarantiaservico	= NULL; // INT(11)
private $created_user  			= NULL; // INT(11)
private $update_user  			= NULL; // INT(11)
private $created_at	    		= NULL; // TIMESTAMP
private $update_at     			= NULL; //TIMESTAMP

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

}

/**
 * Funcao de consulta atraves do ID da table
 * @name select_tipo
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_tipo(){
	$result = $this->select_tipo();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('GARANTIA',$result[0]['GARANTIA']);
	$this->__set('COBSERVICO',$result[0]['COBSERVICO']);
	$this->__set('COBPECAS',$result[0]['COBPECAS']);
	$this->__set('COBDESPESAS',$result[0]['COBDESPESAS']);
	$this->__set('COBTIPOPRECO',$result[0]['COBTIPOPRECO']);
	$this->__set('DIASGARANTIAPECAS',$result[0]['DIASGARANTIAPECAS']);
	$this->__set('DIASGARANTIASERVICO',$result[0]['DIASGARANTIASERVICO']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$created_at[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Tipo
 * @description pesquisa se já existe código do tipo cadastrado
 */
public function select_tipo(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_tipo ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_tipo

 /**
 * @name select_tipo_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_tipo_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_tipo ";
   	$sql .= "ORDER BY ID ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;



} //fim select_tipo_geral

 /**
 * @name incluiTipo
 * @description faz a inclusão do registro cadastrado
 */
public function incluiTipo(){

	$sql  = "INSERT INTO cat_tipo (DESCRICAO, GARANTIA, COBSERVICO, COBPECAS, COBDESPESAS, COBTIPOPRECO, DIASGARANTIAPECAS, DIASGARANTIASERVICO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."','".$this->__get('GARANTIA')."','".$this->__get('COBSERVICO')."'
						,'".$this->__get('COBPECAS')."','".$this->__get('COBDESPESAS')."','".$this->__get('COBTIPOPRECO')."'
							,'".$this->__get('DIASGARANTIAPECAS')."','".$this->__get('DIASGARANTIASERVICO')."'
								,".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
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
} // fim incluiTipo

 /**
 * @name alteraTipo
 * @description altera registro existente
 */
public function alteraTipo(){

	$sql  = "UPDATE cat_servico ";
	$sql .= "SET DESCRICAO = '".$this->__get('DESCRICAO')."', " ;
	$sql .= "GARANTIA = '".$this->__get('GARANTIA')."',";
	$sql .= "COBSERVICO = '".$this->__get('COBSERVICO')."',";
	$sql .= "COBPECAS = '".$this->__get('COBPECAS')."',";
	$sql .= "COBDESPESAS = '".$this->__get('COBDESPESAS')."',";
	$sql .= "COBTIPOPRECO = '".$this->__get('COBTIPOPRECO')."',";
	$sql .= "DIASGARANTIAPECAS = '".$this->__get('DIASGARANTIAPECAS')."',";
	$sql .= "DIASGARANTIASERVICO = '".$this->__get('DIASGARANTIASERVICO')."',";
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

}  // fim alteraTipo

 /**
 * @name exlcuiTipo
 * @description esclui resgistro existe
 */
public function excluiTipo(){

	$sql  = "DELETE FROM cat_tipo ";
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
	
}  // fim excluiTipo

}	//	END OF THE CLASS
?>
