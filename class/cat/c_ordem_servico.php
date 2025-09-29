<?php
/**
 * @package   astecv3
 * @name      c_banco
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      03/06/2017
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_FIN_BANCO
Class c_ordem_servico extends c_user {

     /*
     * TABLE NAME FIN_BANCO
     */     
// Campos tabela
private $id = NULL; //smallint
private $nome = NULL;  //varchar(60)



//construtor
function __construct(){

}

/**
* METODOS DE SETS E GETS
*/
public function setId($id){
         $this->id = $id;
}

public function getId(){
         return $this->id;
}


//############### FIM SETS E GETS ###############

 /**
 * @name existeOrdemServico
 * @description pesquisa se já existe código do banco
 */
public function existeOrdemServico(){

	$sql  = "SELECT * ";
	$sql .= "FROM cat_atendimento ";
	$sql .= "WHERE (numatendimento = ".$this->getNumAtendimento().")";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeBanco

 /**
 * @name selectOrdemServiceAberto
 * @description pesquisa se já existe código do banco cadastrado
 */
public function selectOrdemServiceAberto(){

	$sql  = "SELECT DISTINCT a.numatendimento, a.dataaberatend, a.horaaberatend, c.nomereduzido, ";
	$sql  .= "c.endereco, c.numero, c.bairro, c.cidade, c.fonearea, c.fone  ";
   	$sql .= "FROM cat_atendimento a ";
        $sql .= "inner join fin_cliente c on c.cliente = a.cliente ";
   	$sql .= "WHERE (tecnicoresp = '') and (situacao=5) ";
   	$sql .= "order by a.dataaberatend, a.horaaberatend ";

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
public function select_banco_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_banco ";
   	$sql .= "ORDER BY banco ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;



} //fim select_banco_geral

 /**
 * @name incluiBanco
 * @description faz a inclusão do registro cadastrado
 */
public function incluiBanco(){

	$sql  = "INSERT INTO fin_banco (banco, nome) ";
	$sql .= "VALUES (".$this->getId().", '".$this->getNome()."')";
					
    // echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getNome().' não foram cadastrados!';
	}
} // fim incluiBanco

 /**
 * @name alteraBanco
 * @description altera registro existente
 */
public function alteraBanco(){

	$sql  = "UPDATE fin_banco ";
	$sql .= "SET  banco = ".$this->getId().", " ;
	$sql .= "nome = '".$this->getNome()."' " ;
	$sql .= "WHERE banco = ".$this->getId().";";
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getNome().' não foram alterados!';
	}

}  // fim alteraBanco

 /**
 * @name exlcuiBanco
 * @description esclui resgistro existe
 */
public function excluiBanco(){

	$sql  = "DELETE FROM fin_banco ";
	$sql .= "WHERE banco = ".$this->getId();
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql, 'delete');
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getNome().' não foram excluidos!';
	}
	
}  // fim excluiBanco

}	//	END OF THE CLASS
?>
