<?php
/**
 * @package   admv4.3.1
 * @name      c_equipamento
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class c_anuncio_mkp
Class c_equipamento extends c_user {
/**
 * TABLE NAME CAT_EQUIPAMENTO
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
 * @name select_equipamento
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_equipamento(){
	$result = $this->select_equipamento();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$created_at[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Equipamento
 * @description pesquisa se já existe código do equipamento cadastrado
 */
public function select_equipamento(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_equipamento ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_equipamento

 /**
 * @name select_equipamento_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_equipamento_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_equipamento ";
   	$sql .= "ORDER BY ID ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;



} //fim select_equipamento_geral

 /**
 * @name incluiEquipamento
 * @description faz a inclusão do registro cadastrado
 */
public function incluiEquipamento(){

	$sql  = "INSERT INTO cat_equipamento (DESCRICAO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."',".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
    // echo $sql;
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$regId = $banco->insertReg;
	$banco->close_connection();

	if($result > 0){
        return $regId;
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram cadastrados!';
	}
} // fim incluiEquipamento

 /**
 * @name alteraEquipamento
 * @description altera registro existente
 */
public function alteraEquipamento(){

	$sql  = "UPDATE cat_equipamento ";
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

}  // fim alteraEquipamento

 /**
 * @name exlcuiEquipamento
 * @description esclui resgistro existe
 */
public function excluiEquipamento(){

	$sql  = "DELETE FROM cat_equipamento ";
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
	
}  // fim excluiEquipamento

}	//	END OF THE CLASS
?>
