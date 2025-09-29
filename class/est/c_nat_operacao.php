<?php
/**
 * @package   astecv3
 * @name      c_nat_operacao
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      13/10/2016
 */

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_tools.php");

//Class C_NAT_OPERACAO
Class c_nat_operacao extends c_user {

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
private $natOperacao = NULL;
private $tipo = NULL;
private $codFiscOrigem = NULL;
private $usrMensagem = NULL;
private $compoeCredito = NULL;
private $alteraQuant = NULL;
private $integraFin = NULL;
private $posicaoTributos = NULL;
private $alteraPrecos = NULL;
private $tribSimples = NULL;
private $obs = NULL;
private $descCompleta = NULL;
private $utilizacao = NULL;
private $nfAuto = NULL;
private $modeloNf = NULL;
private $percCreditoSimples = NULL;



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

public function setNatOperacao($natOperacao){$this->natOperacao = strtoupper($natOperacao);}
public function getNatOperacao(){return $this->natOperacao;}

public function setTipo($tipo){$this->tipo = strtoupper($tipo);}
public function getTipo(){return $this->tipo;}

public function setCodFiscOrigem($codFiscOrigem){$this->codFiscOrigem = $idOrigem;}
public function getCodFiscOrigem($format = null) {
    if ($format=='B') {return isset($this->codFiscOrigem)?$this->codFiscOrigem===''?0:$this->codFiscOrigem:0;}
    else{return $this->codFiscOrigem;}
}

public function setUsrMensagem($usrMensagem){$this->usrMensagem = $usrMensagem;}
public function getUsrMensagem(){return $this->usrMensagem;}

public function setCompoeCredito($compoeCredito){$this->compoeCredito = strtoupper($compoeCredito);}
public function getCompoeCredito(){return $this->compoeCredito;}

public function setAlteraQuant($alteraQuant){$this->alteraQuant = strtoupper($alteraQuant);}
public function getAlteraQuant(){return $this->alteraQuant;}

public function setIntegraFin($integraFin){$this->integraFin = strtoupper($integraFin);}
public function getIntegraFin(){return $this->integraFin;}

public function setPosicaoTributos($posicaoTributos){$this->posicaoTributos = strtoupper($posicaoTributos);}
public function getPosicaoTributos(){return $this->posicaoTributos;}

public function setAlteraPrecos($alteraPrecos){$this->alteraPrecos = strtoupper($alteraPrecos);}
public function getAlteraPrecos(){return $this->alteraPrecos;}

public function setTribSimples($tribSimples){$this->tribSimples = $tribSimples;}
public function getTribSimples($format = null) {
    if ($format=='F') {
        return number_format((float)$this->tribSimples, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->tribSimples = c_tools::moedaBd($this->tribSimples);
        return $this->tribSimples;	
    }
    else{
        return $this->tribSimples;
    }
}

public function setPercCreditoSimples($percCreditoSimples){$this->percCreditoSimples = $percCreditoSimples;}
public function getPercCreditoSimples($format = null) {
    if ($format=='F') {
        return number_format((float)$this->percCreditoSimples, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->percCreditoSimples = c_tools::moedaBd($this->percCreditoSimples);
        return $this->percCreditoSimples;	
    }
    else{
        return $this->percCreditoSimples;
    }
}

public function setObs($obs){$this->obs = $obs;}
public function getObs(){return $this->obs;}

public function setDescCompleta($descCompleta){$this->descCompleta = $descCompleta;}
public function getDescCompleta(){return $this->descCompleta;}

public function setUtilizacao($utilizacao){$this->utilizacao = $utilizacao;}
public function getUtilizacao(){return $this->utilizacao;}

public function setNfAuto($nfAuto){$this->nfAuto = $nfAuto;}
public function getNfAuto(){return $this->nfAuto;}

public function setModeloNf($modeloNf){$this->modeloNf = $modeloNf;}
public function getModeloNf(){return $this->modeloNf;}


//############### FIM SETS E GETS ###############

 /**
 * @name existeCodFisc
 * @description pesquisa se já existe código do codFisc
 */
public function existeNatOperacao(){

	$sql  = "SELECT * ";
	$sql .= "FROM est_nat_op ";
	$sql .= "WHERE (id = '".$this->getId()."')";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeNatOperacao

 /**
 * @name select_codFisc
 * @description pesquisa se já existe código do banco cadastrado
 */
public function selectNatOperacao(){

	$sql  = "SELECT * ";
   	$sql .= "FROM est_nat_op ";
   	$sql .= "WHERE (id = ".$this->getId().") ";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_codFisc

/**
 * @name select_codFisc
 * @description pesquisa se já existe código do banco cadastrado
 */
public function selectNatOperacaoGeral(){

	$sql  = "SELECT n.*, d.padrao as desctipo ";
   	$sql .= "FROM est_nat_op n ";
        $sql .= "inner join amb_ddm d on ((n.tipo=d.tipo) and (d.alias='FAT_MENU') and (d.campo='TipoNatOp'))";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_codFisc

 /**
 * @name incluiNatOperacao
 * @description faz a inclusão de registro cadastrado
 */public function incluiNatOperacao(){

	$sql  = "INSERT INTO est_nat_op (NATOPERACAO, TIPO, CODFISCORIGEM, USRMENSAGEM, COMPOECREDITO, ALTERAQUANT, ";
        $sql .= "INTEGRAFIN, POSICAOTRIBUTOS, ALTERAPRECOS, TRIBSIMPLES, PRECCREDITOSIMPLES, OBS, DESCCOMPLETA, UTILIZACAO, NFAUTO, MODELONF) VALUES ('";
        $sql .= $this->getNatOperacao()."', '";
        $sql .= $this->getTipo()."', ";
        $sql .= $this->getCodFiscOrigem('B').", ";
        $sql .= $this->getUsrMensagem().", '";
        $sql .= $this->getCompoeCredito()."', '";
        $sql .= $this->getAlteraQuant()."', '";
        $sql .= $this->getIntegraFin()."', '";
        $sql .= $this->getPosicaoTributos()."', '";
        $sql .= $this->getAlteraPrecos()."', ";
        $sql .= $this->getTribSimples('B').", ";
        $sql .= $this->getPercCreditoSimples('B').", '";
        $sql .= $this->getObs()."', '";
        $sql .= $this->getDescCompleta()."', '";
        $sql .= $this->getUtilizacao()."', '";
        $sql .= $this->getNfAuto()."', '";
        $sql .= $this->getModeloNf()."') ";
					
    // echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getNatOperacao().' não foram cadastrados!';
	}
} // fim incluiNatOperacao

 /**
 * @name alteraNatOperacao
 * @description altera registro existente
 */
public function alteraNatOperacao(){

	$sql  = "UPDATE est_nat_op SET ";
        $sql .= "natoperacao= '".$this->getNatOperacao()."', ";
        $sql .= "tipo= '".$this->getTipo()."', ";
        $sql .= "codfiscorigem = ".$this->getCodFiscOrigem('B').", ";
        $sql .= "usrmensagem= ".$this->getUsrMensagem().", ";
        $sql .= "compoecredito= '".$this->getCompoeCredito()."', ";
        $sql .= "alteraquant= '".$this->getAlteraQuant()."', ";
        $sql .= "integrafin= '".$this->getIntegraFin()."', ";
        $sql .= "posicaotributos= '".$this->getPosicaoTributos()."', ";
        $sql .= "alteraprecos= '".$this->getAlteraPrecos()."', ";
        $sql .= "tribsimples= ".$this->getTribSimples('B').", ";
        $sql .= "PRECCREDITOSIMPLES = ".$this->getPercCreditoSimples('B').", ";
        $sql .= "obs= '".$this->getObs()."', ";
        $sql .= "desccompleta= '".$this->getDescCompleta()."', ";
        $sql .= "utilizacao= '".$this->getUtilizacao()."', ";
        $sql .= "nfauto= '".$this->getNfAuto()."', ";
        $sql .= "modelonf= '".$this->getModeloNf()."' ";
	$sql .= "WHERE id = '".$this->getId()."';";
        //echo $sql;
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getNatOperacao().' não foram alterados!';
	}

}  // fim alteraNatOperacao

 /**
 * @name exlcuiNatOperacao
 * @description esclui resgistro existe
 */
public function excluiNatOperacao(){

	$sql  = "DELETE FROM EST_NAT_OP ";
	$sql .= "WHERE ID = '".$this->getId()."'";
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_acessorio > 0)
            return '';
	else
            return 'Os dados '.$this->getNatOperacao().' não foram excluidos!';
	
}  // fim excluiNatOperacao

}	//	END OF THE CLASS
?>
