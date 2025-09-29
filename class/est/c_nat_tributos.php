<?php
/**
 * @package   astecv3
 * @name      c_nat_tributo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      23/10/2016
 */

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_tools.php");
include_once($dir."/../../class/est/c_nat_operacao.php");

//Class C_NAT_OPERACAO
Class c_nat_tributos extends c_user {

/* Campos tabela
  `ID` int(11) NOT NULL,
  `IDNATOP` int(11) DEFAULT NULL,
  `UF` varchar(2) DEFAULT NULL,
  `PESSOA` char(1) DEFAULT 'J',
  `ORIGEM` char(1) DEFAULT NULL,
  `TRIBICMS` varchar(2) DEFAULT '00',
  `TRIBICMSSAIDA` varchar(2) DEFAULT '00',
  `NCM` varchar(15) DEFAULT NULL,
  `CEST` varchar(15) DEFAULT NULL,
  `CFOP` int(11) DEFAULT NULL,
  `ALIQICMS` decimal(5,2) DEFAULT '0.00',
  `PERCREDUCAOBC` decimal(5,2) DEFAULT '0.00',
  `PERCDEFERIDO` decimal(5,2) DEFAULT NULL,
  `MODBC` char(1) DEFAULT NULL,
  `MODBCST` char(1) DEFAULT NULL,
  `MVAST` decimal(5,2) DEFAULT '0.00',
  `ALIQICMSST` decimal(5,2) DEFAULT NULL,
  `PERCREDUCAOBCST` decimal(5,2) DEFAULT NULL,
  `ALIQISS` decimal(5,2) DEFAULT '0.00',
  `CALCULAIPI` char(1) DEFAULT 'N',
  `ALIQIPI` decimal(5,2) DEFAULT NULL,
  `INSIDEIPIBC` char(1) DEFAULT NULL,
  `CSTPIS` varchar(2) DEFAULT '00',
  `CSTCOFINS` varchar(2) DEFAULT '00',
  `LEGISLACAO` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `CODFISC_UNIQUE` (`IDNATOP`,`UF`,`PESSOA`,`ORIGEM`,`TRIBICMS`,`CFOP`,`NCM`)
 */         
private $id = NULL;
private $ccusto = NULL;
private $idNatop = NULL;
private $natDesc = NULL;
private $natTipo = NULL;
private $uf = NULL;
private $pessoa = NULL;
private $origem = NULL;
private $tribIcms = NULL;
private $tribIcmsSaida = NULL;
private $ncm = NULL;
private $cest = NULL;
private $cfop = NULL;
private $aliqIcms = NULL;
private $redBaseIcms = NULL;
private $percDeferido = NULL;
private $modBc = NULL;
private $modBcSt = NULL;
private $mvast = NULL;
private $aliqSitTrib = NULL;
private $percReducaoBcSt = NULL;
private $iss = NULL;
private $ipi = NULL;
private $insideIpiBc = NULL;
private $calculaIpi = NULL;
private $cstPis = NULL;
private $aliqPis = NULL;
private $cstCofins = NULL;
private $aliqCofins = NULL;
private $ligislacao = NULL;
private $cbenef = NULL;
private $mvastajustada = NULL;
private $aliqicmsst = NULL;

private $contribuinteICMS    = NULL; //CHAR(1)    
private $consumidorFinal     = NULL; //CHAR(1)

private $aliqICMSSimplesST = NULL;

private $aliqFCPST = NULL;
private $anp = NULL;


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

public function setCcusto($ccusto){$this->ccusto = $ccusto;}
public function getCcusto(){return $this->ccusto;}

public function setIdNatop($idNatop){$this->idNatop = $idNatop;}
public function getIdNatop(){return $this->idNatop;}

public function setNatDesc(){
		$nat = new c_nat_operacao();
		$nat->setId($this->getIdNatop());
		$reg_nat = $nat->selectNatOperacao();
		$this->natDesc = $reg_nat[0]['NATOPERACAO'];
		$this->natTipo = $reg_nat[0]['TIPO'];
		
}
public function getNatDesc(){ return $this->natDesc; }
public function getNatTipo(){ return $this->natTipo; }

public function setUf($uf){$this->uf = strtoupper($uf);}
public function getUf(){return $this->uf;}

public function setPessoa($pessoa){$this->pessoa = strtoupper($pessoa);}
public function getPessoa(){return $this->pessoa;}

public function setOrigem($origem){$this->origem = strtoupper($origem);}
public function getOrigem(){return $this->origem;}

public function setTribIcms($tribIcms){$this->tribIcms = strtoupper($tribIcms);}
public function getTribIcms(){return $this->tribIcms;}

public function setTribIcmsSaida($tribIcmsSaida){$this->tribIcmsSaida = strtoupper($tribIcmsSaida);}
public function getTribIcmsSaida(){return $this->tribIcmsSaida;}

public function setNcm($ncm){$this->ncm = strtoupper($ncm);}
public function getNcm(){return $this->ncm;}

public function setCest($cest){$this->cest = strtoupper($cest);}
public function getCest(){return $this->cest;}

public function setCfop($cfop){$this->cfop = $cfop;}
public function getCfop(){return $this->cfop;}

public function setAliqIcms($aliqIcms){$this->aliqIcms = $aliqIcms;}
public function getAliqIcms($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqIcms, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqIcms = c_tools::moedaBd($this->aliqIcms);
        return $this->aliqIcms;	
    }
    else{
        return $this->aliqIcms;
    }
}

public function setRedBaseIcms($redBaseIcms){$this->redBaseIcms = $redBaseIcms;}
public function getRedBaseIcms($format = null) {
    if ($format=='F') {
        return number_format((float)$this->redBaseIcms, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->redBaseIcms = c_tools::moedaBd($this->redBaseIcms);
        return $this->redBaseIcms;	
    }
    else{
        return $this->redBaseIcms;
    }
}

public function setPercDiferido($percDiferido){$this->percDiferido = $percDiferido;}
public function getPercDiferido($format = null) {
    if ($format=='F') {
        return number_format((float)$this->percDiferido, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->percDiferido = c_tools::moedaBd($this->percDiferido);
        return $this->percDiferido;	
    }
    else{
        return $this->percDiferido;
    }
}

public function setModBc($modBc){$this->modBc = strtoupper($modBc);}
public function getModBc(){return $this->modBc;}

public function setModBcSt($modBcSt){$this->modBcSt = strtoupper($modBcSt);}
public function getModBcSt(){return $this->modBcSt;}

public function setMvaSt($mvast){$this->mvast = $mvast;}
public function getMvaSt($format = null) {
    if ($format=='F') {
        return number_format((float)$this->mvast, 4, ',', '.'); }
    elseif ($format=='B') {
        $this->mvast = c_tools::moedaBd($this->mvast);
        return $this->mvast;	
    }
    else{
        return $this->mvast;
    }
}

public function setAliqSitTrib($aliqSitTrib){$this->aliqSitTrib = $aliqSitTrib;}
public function getAliqSitTrib($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqSitTrib, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqSitTrib = c_tools::moedaBd($this->aliqSitTrib);
        return $this->aliqSitTrib;	
    }
    else{
        return $this->aliqSitTrib;
    }
}

public function setPercReducaoBcSt($percReducaoBcSt){$this->percReducaoBcSt = $percReducaoBcSt;}
public function getPercReducaoBcSt($format = null) {
    if ($format=='F') {
        return number_format((float)$this->percReducaoBcSt, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->percReducaoBcSt = c_tools::moedaBd($this->percReducaoBcSt);
        return $this->percReducaoBcSt;	
    }
    else{
        return $this->percReducaoBcSt;
    }
}

public function setIss($iss){$this->iss = $iss;}
public function getIss($format = null) {
    if ($format=='F') {
        return number_format((float)$this->iss, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->iss = c_tools::moedaBd($this->iss);
        return $this->iss;	
    }
    else{
        return $this->iss;
    }
}

public function setIpi($ipi){$this->ipi = $ipi;}
public function getIpi($format = null) {
    if ($format=='F') {
        return number_format((float)$this->ipi, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->ipi = c_tools::moedaBd($this->ipi);
        return $this->ipi;	
    }
    else{
        return $this->ipi;
    }
}

public function setInsideIpiBc($insideIpiBc){$this->insideIpiBc = strtoupper($insideIpiBc);}
public function getInsideIpiBc(){return $this->insideIpiBc;}

public function setAliqIcmsEcf($aliqIcmsEcf){$this->aliqIcmsEcf = $aliqIcmsEcf;}
public function getAliqIcmsEcf($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqIcmsEcf, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqIcmsEcf = c_tools::moedaBd($this->aliqIcmsEcf);
        return $this->aliqIcmsEcf;	
    }
    else{
        return $this->aliqIcmsEcf;
    }
}

public function setAliqPis($aliqPis){$this->aliqPis = $aliqPis;}
public function getAliqPis($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqPis, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqPis = c_tools::moedaBd($this->aliqPis);
        return $this->aliqPis;	
    }
    else{
        return $this->aliqPis;
    }
}

public function setAliqCofins($aliqCofins){$this->aliqCofins = $aliqCofins;}
public function getAliqCofins($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqCofins, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqCofins = c_tools::moedaBd($this->aliqCofins);
        return $this->aliqCofins;	
    }
    else{
        return $this->aliqCofins;
    }
}

public function setCstPis($cstPis){$this->cstPis = strtoupper($cstPis);}
public function getCstPis(){return $this->cstPis;}

public function setCstCofins($cstCofins){$this->cstCofins = strtoupper($cstCofins);}
public function getCstCofins(){return $this->cstCofins;}

public function setCalculaIpi($calculaIpi){$this->calculaIpi = strtoupper($calculaIpi);}
public function getCalculaIpi(){return $this->calculaIpi;}

public function setLegislacao($legislacao){$this->legislacao = $legislacao;}
public function getLegislacao(){return $this->legislacao;}

public function setCBenef($cbenef){$this->cbenef = $cbenef;}
public function getCBenef(){return $this->cbenef;}


public function setMvaStAjustada($mvastajustada){$this->mvastajustada = $mvastajustada;}
public function getMvaStAjustada($format = null) {
    if ($format=='F') {
        return number_format((float)$this->mvastajustada, 4, ',', '.'); }
    elseif ($format=='B') {
        $this->mvastajustada = c_tools::moedaBd($this->mvastajustada);
        return $this->mvastajustada;	
    }
    else{
        return $this->mvastajustada;
    }
}


public function setAliqIcmsSt($aliqicmsst){$this->aliqicmsst = $aliqicmsst;}
public function getAliqIcmsSt($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqicmsst, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqicmsst = c_tools::moedaBd($this->aliqicmsst);
        return $this->aliqicmsst;	
    }
    else{
        return $this->aliqicmsst;
    }
}

function getContribuinteICMS() {  return $this->contribuinteICMS; }         
function getConsumidorFinal() {  return $this->consumidorFinal; }

function setContribuinteICMS($contribuinteICMS) { $this->contribuinteICMS = $contribuinteICMS; }     
function setConsumidorFinal($consumidorFinal) { $this->consumidorFinal = $consumidorFinal; }                     


public function setAliqICMSSimplesST($aliqICMSSimplesST){$this->aliqICMSSimplesST = $aliqICMSSimplesST;}
public function getAliqICMSSimplesST($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqICMSSimplesST, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqICMSSimplesST = c_tools::moedaBd($this->aliqICMSSimplesST);
        return $this->aliqICMSSimplesST;	
    }
    else{
        return $this->aliqICMSSimplesST;
    }
}

public function setAliqFCPST($aliqFCPST){$this->aliqFCPST = $aliqFCPST;}
public function getAliqFCPST($format = null) {
    if ($format=='F') {
        return number_format((float)$this->aliqFCPST, 2, ',', '.'); }
    elseif ($format=='B') {
        $this->aliqFCPST = c_tools::moedaBd($this->aliqFCPST);
        return $this->aliqFCPST;	
    }
    else{
        return $this->aliqFCPST;
    }
}

public function setAnp($anp){$this->anp = $anp;}
public function getAnp(){return $this->anp;}

//############### FIM SETS E GETS ###############

 /**
 * @name existeCodFisc
 * @description pesquisa se já existe código do chave primaria
 */
public function existeTributos(){

	$sql  = "SELECT * ";
	$sql .= "FROM est_nat_op_tributo ";
    $sql .= "WHERE (idNatop = ".$this->getIdNatop().") and ";
    $sql .= "(uf = '".$this->getUf()."') AND (pessoa = '".$this->getPessoa()."') and ";
    $sql .= "(origem = '".$this->getOrigem()."') and (tribicms = '".$this->getTribIcms()."') and ";
    $sql .= "(cfop = ".$this->getCfop().")";

    if ($this->getNcm() != '') {
        $sql .= " AND ( NCM = '".$this->getNcm()."' ) ";
    }
    if ($this->getAnp() != '') {
        $sql .= " AND ( ANP = '".$this->getAnp()."' ) ";
    }
    if ($this->getConsumidorFinal() != '') {
        $sql .= " AND ( consumidorfinal = '".$this->getConsumidorFinal()."' ) ";
    }
    if ($this->getContribuinteICMS() != '') {
        $sql .= " AND ( contribuinteicms = '".$this->getContribuinteICMS()."' ) ";
    }

//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeUf

 /**
 * @name selectTributos
 * @description seleciona os tributos de um codigo fiscal
 */
public function selectTributos(){

    if($this->m_empresacentrocusto == ''){
        $this->m_empresacentrocusto = $_POST["mfilial"];
    }
	$sql  = "SELECT T.*,N.NATOPERACAO, d.padrao as desctipo ";
   	$sql .= "FROM est_nat_op_tributo T ";
   	$sql .= "INNER JOIN est_nat_op N ON (N.ID=T.IDNATOP) ";
    $sql .= "inner join amb_ddm d on ((N.tipo=d.tipo) and (d.alias='FAT_MENU') and (d.campo='TipoNatOp')) ";
   	$sql .= "WHERE (T.IDNATOP = ".$this->getIdNatop().") and (CENTROCUSTO = ".$this->m_empresacentrocusto.") ";
   	$sql .= "ORDER BY UF,PESSOA,ORIGEM,TRIBICMS";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_codFisc

 /**
 * @name selectTributos
 * @description seleciona os tributos pelo ID para alteração
 */
public function selectTributosID(){

	$sql  = "SELECT T.* ";
   	$sql .= "FROM est_nat_op_tributo T ";
   	$sql .= "WHERE (t.ID = ".$this->getId().") ";
   
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_codFisc

/**
 * @name incluiUf
 * @description faz a inclusão de registro cadastrado
 */public function incluiTributos(){

	$sql  = "INSERT INTO EST_NAT_OP_TRIBUTO (";
    $sql .= "CENTROCUSTO, IDNATOP, UF, PESSOA, ORIGEM, TRIBICMS, TRIBICMSSAIDA, ";
    $sql .= "NCM, CEST, CFOP, ALIQICMS, PERCREDUCAOBC, PERCDIFERIDO, MODBC,MODBCST,";
    $sql .= "MVAST, ALIQICMSST, PERCREDUCAOBCST, ALIQISS, ALIQIPI, CALCULAIPI, ";
    $sql .= "INSIDEIPIBC, CSTPIS, ALIQPIS, CSTCOFINS, ALIQCOFINS, LEGISLACAO, ";
    $sql .= "CBENEF, MVASTAJUSTADA, CONTRIBUINTEICMS, CONSUMIDORFINAL, ";
    $sql .= "ALIQFCPST, ALIQICMSSIMPLESST, ANP) VALUES (";    

        $sql .= $this->m_empresacentrocusto.", ";
        $sql .= $this->getIdNatop().", '";
        $sql .= $this->getUf()."', '";
        $sql .= $this->getPessoa()."', '";
        $sql .= $this->getOrigem()."', '";
        $sql .= $this->getTribIcms()."', '";
        $sql .= $this->getTribIcmsSaida()."', '";
        $sql .= $this->getNcm()."', '";
        $sql .= $this->getCest()."', ";
        $sql .= $this->getCfop().", ";
        $sql .= $this->getAliqIcms('B').", ";
        $sql .= $this->getRedBaseIcms('B').", ";
        $sql .= $this->getPercDiferido('B').", '";
        $sql .= $this->getModBc()."', '";
        $sql .= $this->getModBcSt()."', ";
        $sql .= $this->getMvaSt('B').", ";
        $sql .= $this->getAliqIcmsSt('B').", ";
        //$sql .= $this->getAliqSitTrib('B').", ";
        $sql .= $this->getPercReducaoBcSt('B').", ";
        $sql .= $this->getIss('B').", ";
        $sql .= $this->getIpi('B').", '";
        $sql .= $this->getCalculaIpi()."', '";
        $sql .= $this->getInsideIpiBc()."', '";
        $sql .= $this->getCstPis()."',";
        $sql .= $this->getAliqPis('B').",'";
        $sql .= $this->getCstCofins()."', ";
        $sql .= $this->getAliqCofins('B').", '";
        $sql .= $this->getLegislacao()."','";
        $sql .= $this->getCBenef()."',";
        $sql .= $this->getMvaStAjustada('B').",'";   
        $sql .= $this->getContribuinteICMS() . "', '";   
        $sql .= $this->getConsumidorFinal() . "', ";  
        $sql .= $this->getAliqFCPST('B') . ", ";  
        $sql .= $this->getAliqICMSSimplesST('B') . ", '"; 
        $sql .= $this->getAnp() . "'); "; 
					
     //echo $sql;
	$banco = new c_banco;
	$res_tributo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_tributo > 0){
        return '';
	}
	else{
        return 'Os dados não foram cadastrados!';
	}
} // fim incluiUf

 /**
 * @name alteraUf
 * @description altera registro existente
 */
public function alteraTributos(){

	$sql  = "UPDATE est_nat_op_tributo ";
    $sql .= "SET  ALIQICMS = ".$this->getAliqIcms('B').", " ;
    $sql .= "PESSOA        = '".$this->getPessoa()."', ";    
    $sql .= "PERCREDUCAOBC = ".$this->getRedBaseIcms('B').", ";
    $sql .= "PERCDIFERIDO  = ".$this->getPercDiferido('B').", ";
    $sql .= "TRIBICMSSAIDA = '".$this->getTribIcmsSaida()."', ";
    $sql .= "TRIBICMS      = '".$this->getTribIcms()."', ";
    $sql .= "CFOP          = '".$this->getCfop()."', ";
    $sql .= "ORIGEM        = '".$this->getOrigem()."', ";
    $sql .= "NCM           = '".$this->getNcm()."', ";
    $sql .= "MODBC         = '".$this->getModBc()."', ";
    $sql .= "MODBCST       = '".$this->getModBcSt()."', ";
    $sql .= "MVAST         = ".$this->getMvaSt('B').", ";
    $sql .= "ALIQICMSST    = '".$this->getAliqIcmsSt('B')."', ";
//        $sql .= "ALIQICMSST= '".$this->getAliqSitTrib('B')."', ";
        $sql .= "PERCREDUCAOBCST = ".$this->getPercReducaoBcSt('B').", ";
        $sql .= "ALIQISS= ".$this->getIss('B').", ";
        $sql .= "ALIQIPI= ".$this->getIpi('B').", ";
        $sql .= "INSIDEIPIBC= '".$this->getInsideIpiBc()."', ";
        $sql .= "CSTPIS= '".$this->getCstPis()."', ";
        $sql .= "ALIQPIS= ".$this->getAliqPis('B').", ";
        $sql .= "CSTCOFINS= '".$this->getCstCofins()."', ";
        $sql .= "ALIQCOFINS= ".$this->getAliqCofins('B').", ";
        $sql .= "CALCULAIPI= '".$this->getCalculaIpi()."', ";
        $sql .= "LEGISLACAO= '".$this->getLegislacao()."', ";
        $sql .= "CBENEF= '".$this->getCBenef()."', ";
        $sql .= "MVASTAJUSTADA= '".$this->getMvaStAjustada('B')."', ";        
        $sql .= "CONTRIBUINTEICMS = '" . $this->getContribuinteICMS() . "', ";
        $sql .= "CONSUMIDORFINAL = '" . $this->getConsumidorfinal() . "', ";
        $sql .= "ALIQICMSSIMPLESST = " .$this->getAliqICMSSimplesST('B') . ", "; 
        $sql .= "ALIQFCPST = " .$this->getAliqFCPST('B') . ", "; 
        $sql .= "ANP = '" .$this->getAnp() . "' "; 
	$sql .= "WHERE (id = ".$this->getId().")";
        //echo $sql;
	$banco = new c_banco;
	$res_tributo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_tributo > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getCodFisc().' não foram alterados!';
	}

}  // fim alteraUf

 /**
 * @name exlcuiUf
 * @description esclui resgistro existe
 */
public function excluiTributos(){

	$sql  = "DELETE FROM est_nat_op_tributo ";
	$sql .= "WHERE (id = ".$this->getId().")";
	$banco = new c_banco;
	$res_tributo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_tributo > 0)
            return '';
	else
            return 'Os dados '.$this->getCodFisc().' não foram excluidos!';
	
}  // fim excluiUf

public function alteraTributosGeral($where){

	$sql  = "UPDATE est_nat_op_tributo ";
	$sql .= "SET  ALIQICMS = ".$this->getAliqIcms('B').", " ;
        $sql .= "PERCREDUCAOBC= ".$this->getRedBaseIcms('B').", ";
        $sql .= "PERCDIFERIDO= ".$this->getPercDiferido('B').", ";
        //$sql .= "TRIBICMSSAIDA= '".$this->getTribIcmsSaida()."', ";
        //$sql .= "NCM = '".$this->getNcm()."', ";
        //$sql .= "MODBC= '".$this->getModBc()."', ";
        //$sql .= "MODBCST= '".$this->getModBcSt()."', ";
        $sql .= "MVAST= ".$this->getMvaSt('B').", ";
        $sql .= "MVASTAJUSTADA= '".$this->getMvaStAjustada('B')."', ";
        $sql .= "ALIQICMSST= '".$this->getAliqIcmsSt('B')."', ";        
//        $sql .= "ALIQICMSST= '".$this->getAliqSitTrib('B')."', ";
        $sql .= "PERCREDUCAOBCST = ".$this->getPercReducaoBcSt('B').", ";
        $sql .= "ALIQIPI= ".$this->getIpi('B').", ";
        $sql .= "ALIQISS= ".$this->getIss('B').", ";
        //$sql .= "INSIDEIPIBC= '".$this->getInsideIpiBc()."', ";
        //$sql .= "CSTPIS= '".$this->getCstPis()."', ";
        $sql .= "ALIQICMSSIMPLESST = " .$this->getAliqICMSSimplesST('B') . ", "; 
        $sql .= "ALIQFCPST = " .$this->getAliqFCPST('B') . ", "; 
        
        $sql .= "ALIQPIS= ".$this->getAliqPis('B').", ";
        //$sql .= "CSTCOFINS= '".$this->getCstCofins()."', ";
        $sql .= "ALIQCOFINS= ".$this->getAliqCofins('B')." ";
        //$sql .= "CALCULAIPI= '".$this->getCalculaIpi()."', ";
        //$sql .= "LEGISLACAO= '".$this->getLegislacao()."', ";
        //$sql .= "CBENEF= '".$this->getCBenef()."', ";
        //$sql .= "CONTRIBUINTEICMS = '" . $this->getContribuinteICMS() . "', ";
        //$sql .= "CONSUMIDORFINAL = '" . $this->getConsumidorfinal() . "', ";
        //$sql .= "ANP = '" .$this->getAnp() . "' "; 
        $cWhereCompleto = "";
        if ($where[1]!='')  {
            $cWhere = "(UF = '".$where[1]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }if ($where[2]!='') {
            $cWhere = "(PESSOA = '".$where[2]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }if ($where[3]!='') {
            $cWhere = "(ORIGEM = '".$where[3]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }if ($where[4]!='') {
            $cWhere = "(TRIBICMS = '".$where[4]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }if ($where[5]!='') {
            $cWhere = "(NCM = '".$where[5]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }if ($where[6]!='') {
            $cWhere = "(ANP = '".$where[6]."')";
            if ($cWhereCompleto!=''){
                $cWhereCompleto .=' and '.$cWhere;
            } else {
                $cWhereCompleto .=$cWhere;    
            }
        }
                            
	$sql .= " WHERE ".$cWhereCompleto;
        //echo $sql;
	$banco = new c_banco;
	$res_tributo =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_tributo > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->getCodFisc().' não foram alterados!';
	}

}  

}	//	END OF THE CLASS
?>
