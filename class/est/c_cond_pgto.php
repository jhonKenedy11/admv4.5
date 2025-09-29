<?php
/**
 * @package   astecv3
 * @name      c_cond_pgto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      13/10/2016
 */

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_tools.php");

//Class C_NAT_OPERACAO
Class c_cond_pgto extends c_user {

/* Campos tabela
  `CODFISC` int(11) NOT NULL,
  `NATREZAOPERACAO` varchar(30) NOT NULL,
  `TIPO` char(1) DEFAULT 'V',
  `CODFISCORIGEM` int(11) NOT NULL,
  `USRMENSAGEM` smallint(6) DEFAULT '0',
  `COMPOECREDITO` char(1) DEFAULT 'N',
  `ALTERAQUANT` char(1) DEFAULT 'N',
  `INTEGRAFIN` char(1) DEFAULT 'N',
  `POSICAOTRIBUTOS` char(1) DEFAULT 'N',
  `ALTERAPRECOS` char(1) DEFAULT 'N',
  `TRIBSIMPLES` decimal(5,2) DEFAULT NULL,
  `OBS` text,
  `DESCCOMPLETA` text,
  `UTILIZACAO` text,
 */         
private $id = NULL;
private $descricao = NULL;
private $formaPgto = NULL;
private $numParcelas = NULL;


//construtor
function __construct(){

}

//---------------------------------------------------------------
//---------------------------------------------------------------
/**
* METODOS DE SETS E GETS
*/
public function setId($id){$this->id = $id;}
public function getId(){return $this->id;}

public function setDescricao($descricao){$this->descricao = strtoupper($descricao);}
public function getDescricao(){return $this->descricao;}

public function setFormaPgto($formaPgto){$this->formaPgto = strtoupper($formaPgto);}
public function getFormaPgto(){return $this->formaPgto;}

public function setNumParcelas($numParcelas){$this->numParcelas = $idOrigem;}
public function getNumParcelas($format = null) {
    if ($format=='B') {return isset($this->numParcelas)?$this->numParcelas===''?0:$this->numParcelas:0;}
    else{return $this->numParcelas;}
}



//############### FIM SETS E GETS ###############

 /**
 * @name select_codFisc
 * @description pesquisa se já existe código do banco cadastrado
 */
public function selectCondPgto(){

	$sql  = "SELECT * ";
   	$sql .= "FROM fat_cond_pgto ";
   	$sql .= "WHERE (id = ".$this->getId().") ";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_codFisc


 /**
 * @name incluiDescricao
 * @description faz a inclusão de registro cadastrado
 */public function incluiCondPgto(){

	$sql  = "INSERT INTO fat_cond_pgto (DESCRICAO, FORMAPGTO, NUMPARCELAS ) VALUES ('";
        $sql .= $this->getDescricao()."', '";
        $sql .= $this->getFormaPgto()."', ";
        $sql .= $this->getNumParcelas('B').") ";
 					
    // echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getDescricao().' não foram cadastrados!';
	}
} // fim incluiDescricao

 /**
 * @name alteraDescricao
 * @description altera registro existente
 */
public function alteraCondPgto(){

	$sql  = "UPDATE fat_cond_pgto SET ";
        $sql .= "DESCRICAO= '".$this->getDescricao()."', ";
        $sql .= "formaPgto= '".$this->getFormaPgto()."', ";
        $sql .= "numparcelas = ".$this->getNumParcelas('B')." ";
	$sql .= "WHERE id = '".$this->getId()."';";
        //echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getDescricao().' não foram alterados!';
	}

}  // fim alteraDescricao

 /**
 * @name exlcuiDescricao
 * @description esclui resgistro existe
 */
public function excluiCondPgto(){

	$sql  = "DELETE FROM fat_cond_pgto ";
	$sql .= "WHERE id = '".$this->getId()."'";
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0)
            return '';
	else
            return 'Os dados '.$this->getDescricao().' não foram excluidos!';
	
}  // fim excluiDescricao

}	//	END OF THE CLASS
?>
