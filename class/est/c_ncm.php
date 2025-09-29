<?php
/**
 * @package   astecv3
 * @name      c_ncm
 * @version   3.0.00
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      30/07/2019
 */

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
//include_once($dir."/../../class/est/c_nat_operacao.php");

//Class C_NCM
Class c_ncm extends c_user {

/* Campos tabela
  `ID` int(11) NOT NULL,
  `NCM` varchar(8) DEFAULT NULL,
  `DESCRICAO` varchar(260) DEFAULT NULL,
  `ALIQIPI` decimal(9,2) DEFAULT '0.00',
  `ALIQPISMONOFASICA` decimal(9,2) DEFAULT '0.00',
  `ALIQCOFINSMONOFASICA` decimal(9,2) DEFAULT '0.00',
  `ALIQTTNACFEDERAL` decimal(9,2) DEFAULT '0.00',
  `ALIQTTIMPFEDERAL` decimal(9,2) DEFAULT '0.00',
  `ALIQTTESTADUAL` decimal(9,2) DEFAULT '0.00',
  `ALIQTTMUNICIPAL` decimal(9,2) DEFAULT '0.00',  
  PRIMARY KEY (`ID`)
 */         
private $id = NULL;
private $ncm = NULL;
private $descricao = NULL;
private $aliqIpi = NULL;
private $aliqPisMonofasica = NULL;
private $aliqCofinsMonofasica = NULL;
private $aliqTTNacFederal = NULL;
private $aliqTTImpFederal = NULL;
private $aliqTTEstadual = NULL;
private $aliqTTMunicipal = NULL;
private $vigenciaInicio = NULL;
private $vigenciaFim = NULL;

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

public function setNcm($ncm){$this->ncm = strtoupper($ncm);}
public function getNcm(){return $this->ncm;}

public function setDescricao($descricao){$this->descricao = $descricao;}
public function getDescricao(){return $this->descricao;}

public function setAliqIpi($aliqIpi){$this->aliqIpi = $aliqIpi;}
public function getAliqIpi($format = null) {
    switch ($format){
		case 'F':
			return number_format(doubleval($this->aliqIpi), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->aliqIpi!=null){
				$num = str_replace('.', '', $this->aliqIpi);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->aliqIpi; 
	 }
}

public function setAliqPisMonofasica($aliqPisMonofasica){$this->aliqPisMonofasica = $aliqPisMonofasica;}
public function getAliqPisMonofasica($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqPisMonofasica, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqPisMonofasica = c_tools::moedaBd($this->aliqPisMonofasica);
        return $this->aliqPisMonofasica;	
    }
    else{
        return $this->aliqPisMonofasica;
    }
}

public function setAliqCofinsMonofasica($aliqCofinsMonofasica){$this->aliqCofinsMonofasica = $aliqCofinsMonofasica;}
public function getAliqCofinsMonofasica($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqCofinsMonofasica, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqCofinsMonofasica = c_tools::moedaBd($this->aliqCofinsMonofasica);
        return $this->aliqCofinsMonofasica;	
    }
    else{
        return $this->aliqCofinsMonofasica;
    }
}

public function setAliqTTNacFederal($aliqTTNacFederal){$this->aliqTTNacFederal = $aliqTTNacFederal;}
public function getAliqTTNacFederal($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqTTNacFederal, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqTTNacFederal = c_tools::moedaBd($this->aliqTTNacFederal);
        return $this->aliqTTNacFederal;	
    }
    else{
        return $this->aliqTTNacFederal;
    }
}

public function setAliqTTImpFederal($aliqTTImpFederal){$this->aliqTTImpFederal = $aliqTTImpFederal;}
public function getAliqTTImpFederal($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqTTImpFederal, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqTTImpFederal = c_tools::moedaBd($this->aliqTTImpFederal);
        return $this->aliqTTImpFederal;	
    }
    else{
        return $this->aliqTTImpFederal;
    }
}

public function setAliqTTEstadual($aliqTTEstadual){$this->aliqTTEstadual = $aliqTTEstadual;}
public function getAliqTTEstadual($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqTTEstadual, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqTTEstadual = c_tools::moedaBd($this->aliqTTEstadual);
        return $this->aliqTTEstadual;	
    }
    else{
        return $this->aliqTTEstadual;
    }
}

public function setAliqTTMunicipal($aliqTTMunicipal){$this->aliqTTMunicipal = $aliqTTMunicipal;}
public function getAliqTTMunicipal($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqTTMunicipal, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqTTMunicipal = c_tools::moedaBd($this->aliqTTMunicipal);
        return $this->aliqTTMunicipal;	
    }
    else{
        return $this->aliqTTMunicipal;
    }
}

public function setVigenciaInicio($vigenciaInicio) {$this->vigenciaIncio = $vigenciaInicio;}
public function getVigenciaInicio($format = null) {
    $this->vigenciaInicio = strtr($this->vigenciaInicio, "/", "-");
    switch ($format) {
        case 'F':
            return date('Y-m-d', strtotime($this->vigenciaInicio));
            break;
        case 'B':
            return c_date::convertDateBd($this->vigenciaInicio, $this->m_banco);
            break;
        default:
            return $this->vigenciaInicio;
    }
}

public function setVigenciaFim($vigenciaFim) {$this->vigenciaFim = $vigenciaFim;}
public function getVigenciaFim($format = null) {
    $this->vigenciaFim = strtr($this->vigenciaFim, "/", "-");
    switch ($format) {
        case 'F':
            return date('Y-m-d', strtotime($this->vigenciaFim));
            break;
        case 'B':
            return c_date::convertDateBd($this->vigenciaFim, $this->m_banco);
            break;
        default:
            return $this->vigenciaFim;
    }
}

//############### FIM SETS E GETS ###############

 /**
 * @name existeNcm
 * @description pesquisa se já existe a NCM 
 */
public function existeNcm(){

	$sql  = "SELECT N.* ";
	$sql .= "FROM est_ncm N ";
	$sql .= "WHERE (N.ncm = '".$this->getNcm()."') ";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeNcm

 /**
 * @name selectNcmID
 * @description seleciona a NCM pelo ID para alteração
 */
public function selectNcmID(){

	$sql  = "SELECT N.* ";
   	$sql .= "FROM est_ncm N ";
   	$sql .= "WHERE (N.ID = '".$this->getId()."') ";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim selectNcmID


/**
 * Funcao de consulta para todos os registros da tabela
 * @name select_ncm_geral
 * @return ARRAY de todas as colunas da table
 */
 public function select_ncm_geral(){
	$sql  = "SELECT * ";
   	$sql .= "FROM est_ncm  ";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_ncm_geral

/**
 * @name incluiNcm
 * @description faz a inclusão de registro cadastrado
 */public function incluiNcm(){

	$sql  = "INSERT INTO EST_NCM (";
	$sql .= "NCM, DESCRICAO, ALIQIPI, ALIQPISMONOFASICA, ALIQCOFINSMONOFASICA, ALIQTTNACFEDERAL, ALIQTTIMPFEDERAL, ALIQTTESTADUAL, ALIQTTMUNICIPAL, VIGENCIAINICIO, VIGENCIAFIM) VALUES (";
        $sql .= $this->getNcm().", '";
        $sql .= $this->getDescricao()."' , '";
        $sql .= $this->getAliqIpi('B')."', '";
        $sql .= $this->getAliqPisMonofasica('B')."', '";
        $sql .= $this->getAliqCofinsMonofasica('B')."', '";
        $sql .= $this->getAliqTTNacFederal('B')."', '";
        $sql .= $this->getAliqTTImpFederal('B')."', '";
        $sql .= $this->getAliqTTEstadual('B')."', '";
        $sql .= $this->getAliqTTMunicipal('B')."', '";
        $sql .= $this->getVigenciaInicio('B') . "', '";
        $sql .= $this->getVigenciaFim('B') . "')";
					
     //echo $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
    return $banco->result;

} // fim incluiNcm

 /**
 * @name alteraNcm
 * @description altera registro existente
 */
public function alteraNcm(){

	$sql  = "UPDATE est_ncm ";
    $sql .= "SET DESCRICAO = '".$this->getDescricao()."', ";
        $sql .= "ALIQIPI= ".$this->getAliqIpi('B').", ";
        $sql .= "ALIQPISMONOFASICA= ".$this->getAliqPisMonofasica('B').", ";
        $sql .= "ALIQCOFINSMONOFASICA= ".$this->getAliqCofinsMonofasica('B').", ";
        $sql .= "ALIQTTNACFEDERAL= '".$this->getAliqTTNacFederal('B')."', ";
        $sql .= "ALIQTTIMPFEDERAL= '".$this->getAliqTTImpFederal('B')."', ";
        $sql .= "ALIQTTESTADUAL= '".$this->getAliqTTEstadual('B')."', ";
        $sql .= "ALIQTTMUNICIPAL= ".$this->getAliqTTMunicipal('B').", ";
        $sql .= "VIGENCIAINICIO= '".$this->getVigenciaInicio('B')."', ";
        $sql .= "VIGENCIAFIM= '".$this->getVigenciaFim('B')."' ";
	$sql .= "WHERE (id = ".$this->getId().")";
        //echo $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
    return $banco->result;

}  // fim alteraNcm

 /**
 * @name exlcuiNcm
 * @description esclui resgistro existe
 */
public function excluirNcm(){

	$sql  = "DELETE FROM est_ncm ";
	$sql .= "WHERE (ID = ".$this->getId().")";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
    return $banco->result;
}  // fim exlcuiNcm

}	//	END OF THE CLASS
?>
