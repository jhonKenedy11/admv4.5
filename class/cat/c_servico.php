<?php
/**
 * @package   admv4.3.1
 * @name      c_servico
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_database_pdo.php");

//Class c_anuncio_mkp
Class c_servico extends c_user {
/**
 * TABLE NAME CAT_SERVICO
 */    
    
// Campos tabela
private $id         	= NULL; // INT(11)
private $descricao  	= NULL; // VARCHAR(60)
private $unidade		= NULL; // VARCHAR(3)
private $quantidade		= NULL; // DECIMAL(6,2)
private $valorunitario	= NULL; // DECIMAL(8,2)
private $status         = NULL; // TINYINT(1)
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
 * @name select_servico
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_servico(){
	$result = $this->select_servico();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('UNIDADE',$result[0]['UNIDADE']);
	$this->__setNumber('QUANTIDADE',$result[0]['QUANTIDADE'],2,'F');
	$this->__setNumber('VALORUNITARIO',$result[0]['VALORUNITARIO'],2,'F');
	$this->__set('STATUS',$result[0]['STATUS']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$result[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Servico
 * @description pesquisa se já existe código do servico cadastrado
 */
public function select_servico(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_servico ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_servico

 /**
 * @name select_servico_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_servico_geral($status = NULL){
    $sql = "SELECT * FROM CAT_SERVICO WHERE 1=1 ";

    if ($status !== NULL && is_numeric($status)) {
        $sql .= " AND STATUS = :status ";
    }

    try {
        $this->banco = new c_banco_pdo();
        $this->banco->prepare($sql);

        if ($status !== NULL && is_numeric($status)) {
            $this->banco->bindValue(":status", $status, PDO::PARAM_INT);
        }

        $this->banco->execute();
        return $this->banco->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
        return false;
    }
} //fim select_servico_geral

 /**
 * @name incluiServico
 * @description faz a inclusão do registro cadastrado
 */
public function incluiServico(){

	$sql  = "INSERT INTO cat_servico (DESCRICAO, UNIDADE, QUANTIDADE, VALORUNITARIO, STATUS, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."','".$this->__get('UNIDADE')."','".$this->__getNumber('QUANTIDADE', 2, 'B')."'
						,'".$this->__getNumber('VALORUNITARIO', 2, 'B')."',".$this->__get('STATUS').",".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
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
} // fim incluiServico

 /**
 * @name alteraServico
 * @description altera registro existente
 */
public function alteraServico(){

	$sql  = "UPDATE cat_servico ";
	$sql .= "SET DESCRICAO = '".$this->__get('DESCRICAO')."', " ;
	$sql .= "UNIDADE = '".$this->__get('UNIDADE')."',";
	$sql .= "QUANTIDADE = '".$this->__getNumber('QUANTIDADE', 2, 'B')."',";
	$sql .= "VALORUNITARIO = '".$this->__getNumber('VALORUNITARIO', 2, 'B')."',";
	$sql .= "STATUS = ".$this->__get('STATUS').",";
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

}  // fim alteraServico

 /**
 * @name exlcuiServico
 * @description esclui resgistro existe
 */
public function excluiServico(){

	$sql  = "DELETE FROM cat_servico ";
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
	
}  // fim excluiServico

}	//	END OF THE CLASS
?>
