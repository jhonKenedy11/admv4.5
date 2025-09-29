<?php

/****************************************************************************
*Cliente...........:
*Contratada........: ADM
*Desenvolvedor.....: LUCAS TORTOLA DA SILVA BUCKO
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: C_FORM
*Ultima Atualizacao: 02/08/2012
****************************************************************************/

include_once("../../bib/c_user.php");

//Class C_FORM
Class c_form extends c_user {

// Campos tabela
private $id = NULL;//integer
private $nomeForm = NULL;//varchar(30)
private $descricao = NULL;//varchar(250)
private $help = NULL;//blob


//construtor
function c_form(){

}

//---------------------------------------------------------------
//---------------------------------------------------------------

public function setId($id){
         $this->id = $id;
}

public function getId(){
         return $this->id;
}
public function setNomeForm($nomeForm){
         $this->nomeForm = $nomeForm;
}

public function getNomeForm(){
         return $this->nomeForm;
}

public function setDescricao($descricao){
         $this->descricao = $descricao;
}

public function getDescricao(){
         return $this->descricao;
}

public function setHelp($help){
         $this->help = $help;
}

public function getHelp(){
         return $this->help;
}

//---------------------------------------------------------------
//---------------------------------------------------------------
public function existeDocumento(){

	$sql  = "SELECT * ";
	$sql .= "FROM amb_form ";
	$sql .= "WHERE (id = ".$this->getId().")";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeDocumento
//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_form(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM amb_form ";
   	$sql .= "WHERE ID = ".$this->getId();
   	

   //	echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_form

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_form_geral(){
	$sql  = "SELECT  * FROM amb_form ORDER BY nomeform ";
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	//echo $banco->resultado;
	return $banco->resultado;

//	$this->exec_sql($sql);
//	return $this->resultado;


} //fim select_form_geral
//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_form_letra($letra){
	$sql  = "SELECT  * FROM amb_form ";
	$sql  .= "WHERE (NOMEFORM LIKE '".$letra."%') ORDER BY nomeform ";
	//ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

//	$this->exec_sql($sql);
//	return $this->resultado;


} //fim select_form_geral

//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiForm(){

	$banco = new c_banco;
	if ($banco->gerenciadorDB == 'interbase') {
		$this->setId($banco->geraID("AMB_GEN_ID_FORM"));
		$sql  = "INSERT INTO amb_form (ID, ";
	}
	else{
		$sql  = "INSERT INTO amb_form (";
	}
	
	$sql  .= "nomeform, descricao, help) ";
	
	if ($banco->gerenciadorDB == 'interbase') {
		$sql .= "VALUES (".$this->getId().", '";
	}
	else{
		$sql .= "VALUES ('";
	}	
	
	$sql .= $this->getNomeForm()."', '".$this->getDescricao()."', '";
	$sql .= $this->getHelp()."' ) ";
					
     //echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados  '.$this->getDescricao().' n&atilde;o foram cadastrados!';
	}
} // fim incluiForm

//---------------------------------------------------------------
//---------------------------------------------------------------
public function alteraForm(){

	$sql  = "UPDATE amb_form ";
	$sql .= "SET nomeform = '".$this->getNomeForm()."', " ;
	$sql .= "descricao = '".$this->getDescricao()."', ";
	$sql .= "help = '".$this->getHelp()."' " ;
	$sql .= "WHERE (id = ".$this->getId().") ";
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();
	
	//echo $sql;
	
	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getDescricao().' n&atilde;o foram alterados!';
	}

}  // fim alteraForm

//---------------------------------------------------------------
//---------------------------------------------------------------
public function excluiForm(){

	$sql  = "DELETE FROM amb_form ";
	$sql .= "WHERE id = ".$this->getId();
	//echo $sql;
	//echo strtoupper($sql)."<BR>";
	
	$banco = new c_banco;
	$banco->exec_sql($sql, $banco->id_connection);
	$banco->close_connection();
	return $banco->resultado;
	
}  // fim excluiForm

}	//	END OF THE CLASS
?>
