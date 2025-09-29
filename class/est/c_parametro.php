<?php
/**
 * @package   astec
 * @name      c_parametro
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      18/05/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_parametros
Class c_parametros extends c_user {
/**
 * TABLE NAME EST_PARAMETROS
 */
    
    
// Campos tabela
private $id         = NULL; // VARCHAR(15)
private $cfop       = NULL; // VARCHAR(15)
private $natOp      = NULL; // VARCHAR(15)
private $condPgto   = NULL; // VARCHAR(15)
private $genero     = NULL; // VARCHAR(15)
private $conta      = NULL; // VARCHAR(15)
private $serie      = NULL; // VARCHAR(15)



/**
* METODOS DE SETS E GETS
*/

function getId() {
   return $this->id;
}

function getCfop() {
   return $this->cfop;
}

function getNatOp() {
   return $this->natOp;
}

function getCondPgto() {
   return $this->condPgto;
}

function getGenero() {
   return $this->genero;
}

function getConta() {
   return $this->conta;
}

function getSerie() {
   return $this->serie;
}

function setId($id) {
   $this->id = $id;
}

function setCfop($cfop) {
   $this->cfop = $cfop;
}

function setNatOp($natOp) {
   $this->natOp = $natOp;
}

function setCondPgto($condPgto) {
   $this->condPgto = $condPgto;
}

function setGenero($genero) {
   $this->genero = $genero;
}

function setConta($conta) {
   $this->conta = $conta;
}

function setSerie($serie) {
   $this->serie = $serie;
}
//############### FIM SETS E GETS ###############

    public function setParametro() {
        $par = $this->select_parametro();
        $this->setId($par[0]['FILIAL']);
        $this->setCfop($par[0]['CFOP']);
        $this->setNatOp($par[0]['NATOPERACAO']);
        $this->setCondPgto($par[0]['CONDPGTO']);
        $this->setGenero($par[0]['GENERO']);
        $this->setConta($par[0]['CONTA']);
        $this->setSerie($par[0]['SERIE']);
    }

/**
 * Funcao de consulta atraves do ID da table
 * @name select_parametro
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function select_parametro(){
	$sql  = "SELECT * ";
   	$sql .= "FROM est_parametro ";
   	$sql .= "WHERE (filial = '".$this->getId()."') ";
        // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_grupo

/**
 * Funcao de consulta para todos os registros da tabela
 * @name select_grupo_geral
 * @return ARRAY de todas as colunas da table
 */
public function select_grupo_geral(){
	$sql  = "SELECT * ";
   	$sql .= "FROM est_parametro; ";
        // echo strtoupper($sql);
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_grupo_geral



/**
* Funcao para Inclusao no banco
* @name incluiGrupo
* @return string vazio se ocorrer com sucesso
*/
public function inclui_parametro(){
	
	$sql  = "INSERT INTO est_parametro (filial, cfop, natoperacao, condpgto, genero, conta, serie) ";
	$sql .= "VALUES ('".$this->getId()."', '".$this->getCfop()."', '".$this->getNatOp()."', '".$this->getCondPgto()."', '";
        $sql .= $this->getGenero()."', '".  $this->getConta()."', '".  $this->getSerie()."'); ";
        // echo strtoupper($sql);
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Grupo '.$this->getDesc().' n&atilde;o foi cadastrado!';
	}
} // fim 

/**
* Funcao para Alteracao no banco
* @name alteraGrupo
* @return string vazio se ocorrer com sucesso
*/
public function altera_parametro(){

	$sql  = "UPDATE est_parametro ";
	$sql .= "SET  filial = '".$this->getId()."', " ;
	$sql .= "cfop = '".$this->getCfop()."', " ;
	$sql .= "natoperacao = '".$this->getNatOp()."', " ;
	$sql .= "condpgto = '".$this->getCondPgto()."', " ;
	$sql .= "genero = '".$this->getGenero()."', " ;
	$sql .= "conta = '".$this->getConta()."', " ;
	$sql .= "serie = '".$this->getSerie()."' " ;
	$sql .= "WHERE filial = '".$this->getId()."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
        // echo strtoupper($sql);
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Grupo '.$this->getDesc().' n&atilde;o foi alterado!';
	}

}  // fim 

}	//	END OF THE CLASS
?>
