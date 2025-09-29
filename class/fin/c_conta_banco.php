<?php
/**
 * @package   astecv3
 * @name      c_contaBanco
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/12/2016
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_FIN_BANCO
Class c_contaBanco extends c_user {

     /*
     * TABLE NAME FIN_BANCO
     */     
// Campos tabela
private $id = NULL; //smallint
private $nomeInterno = NULL;  //varchar(30)
private $nomeContaBanco = NULL;  //varchar(40)
private $banco = NULL;  //varchar(6)
private $agencia = NULL;  //varchar(6)
private $contaCorrente = NULL;  //varchar(15)
private $contato = NULL;  //varchar(15)
private $descontoBonificacao = NULL; //decimal(5,2)  
private $situacao = NULL;



//construtor
function __construct(){

}

/**
* METODOS DE SETS E GETS
*/
public function setId($id){ $this->id = $id;}
public function getId(){return $this->id;}

public function setNomeInterno($nomeInterno){ $this->nomeInterno = $nomeInterno;}
public function getNomeInterno(){ return $this->nomeInterno;}

public function setNomeContaBanco($nomeContaBanco){ $this->nomeContaBanco = $nomeContaBanco;}
public function getNomeContaBanco(){ return $this->nomeContaBanco;}

public function setBanco($banco){ $this->banco = $banco;}
public function getBanco(){ return $this->banco;}

public function setAgencia($agencia){ $this->agencia = $agencia;}
public function getAgencia(){ return $this->agencia;}

public function setContaCorrente($contaCorrente){ $this->contaCorrente = $contaCorrente;}
public function getContaCorrente(){ return $this->contaCorrente;}

public function setContato($contato){ $this->contato = $contato;}
public function getContato(){ return $this->contato;}

public function setUltimoNossoNumero($nn){ $this->nn = $nn;}
public function getUltimoNossoNumero(){ return $this->nn;}

public function setDescontoBonificacao($descontoBonificacao){ $this->descontoBonificacao = $descontoBonificacao;}
public function getDescontoBonificacao($format = NULL) {
    if ($format=='F') {
        return number_format($this->descontoBonificacao , 2, ',', '.'); }
    else {
        if ($this->descontoBonificacao  != null){
            $num = str_replace('.', '', $this->descontoBonificacao );
            $num = str_replace(',', '.', $num);
            return $num; }
        else{
            return 0; }
    }	
}

public function setMulta($multa){ $this->multa = $multa;}
public function getMulta($format = NULL) {
    if ($format=='F') {
        return number_format($this->multa, 2, ',', '.'); }
    else {
        if ($this->multa != null){
            $num = str_replace('.', '', $this->multa);
            $num = str_replace(',', '.', $num);
            return $num; }
        else{
            return 0; }
    }	
}

public function setJuros($juros){ $this->juros = $juros;}
public function getJuros($format = NULL) {
    if ($format=='F') {
        return number_format($this->juros, 2, ',', '.'); }
    else {
        if ($this->juros != null){
            $num = str_replace('.', '', $this->juros);
            $num = str_replace(',', '.', $num);
            return $num; }
        else{
            return 0; }
    }	
}

public function setDiaProtesto($diaProtesto){ $this->diaProtesto = $diaProtesto;}
public function getDiaProtesto() { return $this->diaProtesto;}

public function setCarteiraCobranca($carteiraCobranca){ $this->carteiraCobranca = $carteiraCobranca;}
public function getCarteiraCobranca() { return $this->carteiraCobranca;}

public function setMsgBoleto($msgBoleto){ $this->msgBoleto = $msgBoleto;}
public function getMsgBoleto() { return $this->msgBoleto;}

public function setNumNoBanco($numNobanco){ $this->numNobanco = $numNobanco;}
public function getNumNoBanco() { return $this->numNobanco;}

public function setStatus($situacao){ $this->situacao = $situacao;}
public function getStatus() { return $this->situacao;}

//############### FIM SETS E GETS ###############

 /**
 * @name mod11
 * @description calcula digito verificador com base no calculo modulo 11
 * @param int $num - numero a ser calculado
 * @return int $count - numero de parcelas geradas
 */
public static function mod11($num, $base = 9, $r = 0)
{
    $soma = 0;
    $fator = 2;
    $num = (int) $num;
    /* Separacao dos numeros */
    for ($i = strlen($num); $i > 0; --$i) {
        // pega cada numero isoladamente
        $numeros[$i] = substr($num, $i - 1, 1);
        // Efetua multiplicacao do numero pelo falor
        $parcial[$i] = $numeros[$i] * $fator;
        // Soma dos digitos
        $soma += $parcial[$i];
        if ($fator == $base) { // restaura fator de multiplicacao para 2
            $fator = 1;
        }
        ++$fator;
    }
    
    //calculo digito bradesco
    $resto = $soma % 11;
    switch ($resto) {
        case 0: 
            $digito= 0;
            break;
        case 1: 
            $digito= 'P';
            break;
        default :
            $digito = 11 - $resto;
    }
    return $digito;
    /* Calculo do modulo 11 
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }

        return $digito;
    } elseif ($r == 1) {
        $resto = $soma % 11;

        return $resto;
    }*/
}

 /**
 * @name geraNumeroRemessa
 * @param int $conta - conta bancaria para gravar nosso numero
 * @param int $nr -  numero remessa a ser incrementado
 * @description atualzia o numero de remessa de cobrrança as ser enviado.
 */
public function geraNumeroRemessa($conta, $nr){

    $nr = $nr+1;

    // SALVA NOSSO NUMERO
    $sql  = "UPDATE fin_conta ";
    $sql .= "SET  " ;
    $sql .= "NUMREMESSA = '".$nr."' " ;
    $sql .= "WHERE conta = ".$conta.";";

    $contaBanco = new c_banco();
    $res_contaBanco = $contaBanco->exec_sql($sql);
    $contaBanco->close_connection();
    if($res_contaBanco <= 0):
        $nr = 0;
    endif;
    return $nr;
        
} //fim geraNossoNumero

 /**
 * @name geraNossoNumero
 * @param int $conta - conta bancaria para gravar nosso numero
 * @param int $nn -  nosso numero a aser incrementado
 * @description atualiza o noso numero de cobrrança as ser enviado.
 */
public function geraNossoNumero($conta, $nn){

    $nn = (int) $nn + 1;

    // SALVA NOSSO NUMERO
    $sql  = "UPDATE fin_conta ";
    $sql .= "SET  " ;
    $sql .= "ULTIMONOSSONRO = '".$nn."' " ;
    $sql .= "WHERE conta = ".$conta.";";

    $contaBanco = new c_banco();
    $res_contaBanco = $contaBanco->exec_sql($sql);
    $contaBanco->close_connection();
    if($res_contaBanco <= 0):
        $nn = 0;
    endif;
    return $nn;
        
} //fim geraNossoNumero

 /**
 * @name existeBanco
 * @description pesquisa se já existe código do contaBanco
 */
public function existeContaBanco(){

	$sql  = "SELECT * ";
	$sql .= "FROM fin_conta ";
	$sql .= "WHERE (conta = ".$this->getId().")";
	//ECHO $sql;

	$contaBanco = new c_banco();
	$contaBanco->exec_sql($sql);
	$contaBanco->close_connection();
	return is_array($contaBanco->resultado);	
} //fim existeBanco

 /**
 * @name select_Banco
 * @description pesquisa se já existe código do contaBanco cadastrado
 */
public function select_ContaBanco(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_conta ";
   	$sql .= "WHERE (conta = ".$this->getId().") ";
   	

   	//echo $sql;
	$contaBanco = new c_banco();
	$contaBanco->exec_sql($sql);
	$contaBanco->close_connection();
	return $contaBanco->resultado;
} //fim select_contaBanco

 /**
 * @name select_contaBanco_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_contaBanco_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fin_conta ";
   	$sql .= "ORDER BY conta ";
   	
//	ECHO $sql;
	$contaBanco = new c_banco;
	$contaBanco->exec_sql($sql);
	$contaBanco->close_connection();
	return $contaBanco->resultado;



} //fim select_contaBanco_geral

 /**
 * @name incluiBanco
 * @description faz a inclusão do registro cadastrado
 */
public function incluiContaBanco(){

	$sql  = "INSERT INTO FIN_CONTA (NOMEINTERNO, NOMECONTABANCO, BANCO, AGENCIA, CONTACORRENTE, 
             CONTATO, DESCONTOBONIFICACAO, STATUS, MULTA, JUROS, PROTESTO, NUMNOBANCO, CARTEIRA,
             MSGBLOQUETO, ULTIMONOSSONRO ) ";
	$sql .= "VALUES ('".$this->getNomeInterno()."',"
                . "'".$this->getNomeContaBanco()."',"
                . "'".$this->getBanco()."',"
                . "'".$this->getAgencia()."',"
                . "'".$this->getContaCorrente()."',"
                . "'".$this->getContato()."',"
                . "'".$this->getDescontoBonificacao('B')."',"
                . "'".$this->getStatus()."',"
                . "'".$this->getMulta('B')."',"
                . "'".$this->getJuros('B')."',"
                . "'".$this->getDiaProtesto()."',"
                . "'".$this->getNumNoBanco()."',"
                . "'".$this->getCarteiraCobranca()."',"
                . "'".$this->getMsgBoleto()."',"
                . "'".$this->getUltimoNossoNumero()."')";
					
    //echo $sql;
	$contaBanco = new c_banco;
	$res_contaBanco =  $contaBanco->exec_sql($sql);
	$contaBanco->close_connection();

	if($res_contaBanco > 0):
            return 'Dados '.$this->getNomeInterno().' foram cadastrados!';
        else:    
            return 'Os dados '.$this->getNomeInterno().' não foram cadastrados!';
        endif;
} // fim incluiBanco

 /**
 * @name alteraBanco
 * @description altera registro existente
 */
public function alteraContaBanco(){

	$sql  = "UPDATE fin_conta ";
	$sql .= "SET  " ;
	$sql .= "nomeInterno = '".$this->getNomeInterno()."', " ;
	$sql .= "nomeContaBanco = '".$this->getNomeContaBanco()."', " ;
	$sql .= "banco = '".$this->getBanco()."', " ;
	$sql .= "agencia = '".$this->getAgencia()."', " ;
	$sql .= "contacorrente = '".$this->getContaCorrente()."', " ;
	$sql .= "contato = '".$this->getContato()."', " ;
    $sql .= "descontoBonificacao = '".$this->getDescontoBonificacao('B')."', " ;
    $sql .= "status = " . "'".$this->getStatus()."', ";
    $sql .= "multa = ". "'".$this->getMulta('B')."', ";
    $sql .= "juros = ". "'".$this->getJuros('B')."', ";
    $sql .= "protesto = ". "'".$this->getDiaProtesto()."', ";
    $sql .= "numnobanco = ". "'".$this->getNumNoBanco()."', ";
    $sql .= "carteira = ". "'".$this->getCarteiraCobranca()."', ";
    $sql .= "msgbloqueto = ". "'".$this->getMsgBoleto()."', ";
    $sql .= "ultimonossonro = ". "'".$this->getUltimoNossoNumero()."' ";
	$sql .= "WHERE conta = ".$this->getId().";";
        
	$contaBanco = new c_banco;
	$res_contaBanco =  $contaBanco->exec_sql($sql);
	$contaBanco->close_connection();

	if($res_contaBanco > 0):
            return 'Dados '.$this->getNomeInterno().' foram alterados!';
        else:    
            return 'Os dados '.$this->getNomeInterno().' não foram alterados!';
        endif;

}  // fim alteraBanco

 /**
 * @name exlcuiBanco
 * @description esclui resgistro existe
 */
public function excluiContaBanco(){

	$sql  = "DELETE FROM fin_conta ";
	$sql .= "WHERE conta = ".$this->getId();
	$contaBanco = new c_banco;
	$res_contaBanco =  $contaBanco->exec_sql($sql);
	$contaBanco->close_connection();

	if($res_contaBanco > 0):
            return 'Os dados '.$this->getNomeInterno().' foram exclu&iacute;dos!';
        else:    
            return 'Os dados '.$this->getNomeInterno().' não foram excluidos!';
        endif;
	
}  // fim excluiBanco

}	//	END OF THE CLASS
?>
