<?php
/**
 * @package   astecv3
 * @name      c_classe
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      11/04/2016
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_CLASSE
Class c_classe extends c_user {

     /*
     * TABLE NAME FIN_ATIVIDADE
     */ 
    
// Campos tabela
private $classe     = NULL; // varchar(2)
private $descricao  = NULL; // varchar(15)
private $bloqueado  = null; // char(1)


/**
* METODOS DE SETS E GETS
*/
public function setClasse($classe){
         $this->classe = c_tools::LimpaCamposGeral($classe);
}

public function getClasse(){
         return $this->classe;
}

public function setDescricao($desc){
         $this->descricao = c_tools::LimpaCamposGeral($desc);
}

public function getDescricao(){
         return $this->descricao;
}

public function setBloqueado($bloqueado){
        if ($bloqueado == ''){
            $this->bloqueado = 'N';
        }else{
            $this->bloqueado = strtoupper($bloqueado);
        }
         
}

public function getBloqueado(){
         return $this->bloqueado;
}
//############### FIM SETS E GETS ###############

    public function busca_classe() {
        $classe = $this->select_classe();
        $this->setClasse($classe[0]['CLASSE']);
        $this->setDescricao($classe[0]['DESCRICAO']);
        $this->setBloqueado($classe[0]['BLOQUEADO']);
    }// busca_classe


    /**
 * 
 * @name existeClasse
 */
public function existeClasse(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_classe ";
	$sql .= "WHERE (classe = '".$this->getClasse()."');";
       // echo strtoupper($sql);

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeClasse

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_classe(){

	$sql  = "SELECT * ";
   	$sql .= "FROM fin_classe ";
   	$sql .= "WHERE (classe = '".$this->getClasse()."') ";
   	$sql .= "ORDER BY descricao; ";
       // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_classe

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_classe_geral(){
	$sql  = "SELECT * ";
   	$sql .= "FROM fin_classe ";
   	$sql .= "ORDER BY classe; ";
        //echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;


} //fim select_classe_geral
//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_classe_letra($letra){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_classe ";
  	$sql .= "WHERE descricao LIKE '".$letra."%' ";
   	$sql .= "ORDER BY classe; ";
	//echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	return $banco->resultado;
}// fim select_classe_letra

//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiClasse(){

	$sql  = "INSERT INTO fin_classe (classe, descricao, bloqueado)";
	$sql .= "VALUES ('".$this->getClasse()."', '".$this->getDescricao()."', '".$this->getBloqueado()."'); ";
        //echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;

} // fim incluiClasse

//---------------------------------------------------------------
//---------------------------------------------------------------
public function alteraClasse(){

	$sql  = "UPDATE fin_classe ";
	$sql .= "SET  descricao = '".$this->getDescricao()."', " ;
	$sql .= "bloqueado = '".$this->getBloqueado()."' " ;
	$sql .= "WHERE classe = '".$this->getClasse()."';";
        //echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;
}  // fim alteraClasse

//---------------------------------------------------------------
//---------------------------------------------------------------
public function excluiClasse(){

	$sql  = "DELETE FROM fin_classe ";
	$sql .= "WHERE classe = '".$this->getClasse()."';";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$status = $banco->result;
	$banco->close_connection();

	return $status;
    
}  // fim excluiClasse

}	//	END OF THE CLASS
?>
