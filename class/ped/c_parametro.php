<?php
/**
 * @package   admv4.5
 * @name      c_paramentro
 * @version   4.5
 * @copyright 2023
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      20/02/2023
 */

$dir = dirname(__FILE__);

Class c_parametros extends c_user {

private $filial         = NULL; //"int(11)"
private $grupoServico   = NULL; //"varchar(15)"
private $apresentacao   = NULL; //"text"
private $objetivo       = NULL; //"text"
private $garantia       = NULL; //"text"
private $impostos       = NULL; //"text"
private $prazoentrega   = NULL; //"text"
private $validade       = NULL; //"text"
private $aceite         = NULL; //"text"
private $obs            = NULL; //"text"
private $fluxoPedido    = NULL; //"char(1)"
private $sitEmitirNf    = NULL; //"smallint(6)"
private $sitBaixado     = NULL; //"smallint(6)"
private $sitAberto      = NULL; //"smallint(6)"
private $valorPedMinimo = NULL; //"decimal(11,2)"
private $aprovacao      = NULL; //"char(1)"
private $descontoMaximo = NULL; //"decimal(11,2)"
private $lancPedBaixado = NULL; //"char(1)" 
private $tipoDesconto   = NULL; //"char(1)"
private $encomenda      = NULL; //"char(1)"

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    //session_start();
    c_user::from_array($_SESSION['user_array']);
}

###### INICIO SET's e GET's ######
function setFilial($filial){$this->filial = $filial;}
function getFilial(){return $this->filial;}

function setGrupoServico($grupoServico){$this->grupoServico = $grupoServico;}
function getGrupoServico(){return $this->grupoServico;}

function setApresentacao($apresentacao){$this->apresentacao = $apresentacao;}
function getApresentacao(){return $this->apresentacao;}

function setObjetivo($objetivo){$this->objetivo = $objetivo;}
function getObjetivo(){return $this->objetivo;}

function setGarantia($garantia){$this->garantia = $garantia;}
function getGarantia(){return $this->garantia;}

function setImpostos($impostos){$this->impostos = $impostos;}
function getImpostos(){return $this->impostos;}

function setPrazoEntrega($prazoentrega){$this->prazoentrega = $prazoentrega;}
function getPrazoEntrega(){return $this->prazoentrega;}

function setValidade($validade){$this->validade = $validade;}
function getValidade(){return $this->validade;}

function setAceite($aceite){$this->aceite = $aceite;}
function getAceite(){return $this->aceite;}

function setObs($obs){$this->obs = $obs;}
function getObs(){return $this->obs;}

function setFluxoPedido($fluxoPedido){$this->fluxoPedido = $fluxoPedido;}
function getFluxoPedido(){return $this->fluxoPedido;}

function setSitEmitirNf($sitEmitirNf){$this->sitEmitirNf = $sitEmitirNf;}
function getSitEmitirNf(){return $this->sitEmitirNf;}

function setSitBaixado($sitBaixado){$this->sitBaixado = $sitBaixado;}
function getSitBaixado(){return $this->sitBaixado;}

function setSitAberto($sitAberto){$this->sitAberto = $sitAberto;}
function getSitAberto(){return $this->sitAberto;}

function setValorPedMinimo($valorPedMinimo){$this->valorPedMinimo =$valorPedMinimo;}
function getValorPedMinimo($format=null){
    if (!empty($this->valorPedMinimo)) {
        if ($format == 'F') {
            return number_format($this->valorPedMinimo, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->valorPedMinimo);
        }
    } else {
        return 0;
    } 
}

function setAprovacao($aprovacao){$this->aprovacao = $aprovacao;}
function getAprovacao(){return $this->aprovacao;}

function setDescontoMaximo($descontoMaximo){$this->descontoMaximo = $descontoMaximo;}
function getDescontoMaximo($format=null){
    if (!empty($this->descontoMaximo)) {
        if ($format == 'F') {
            return number_format($this->descontoMaximo, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->descontoMaximo);
        }
    } else {
        return 0;
    }
}

function setLancPedBaixado($lancPedBaixado){$this->lancPedBaixado = $lancPedBaixado;}
function getLancPedBaixado(){return $this->lancPedBaixado;}

function setTipoDesconto($tipoDesconto){$this->tipoDesconto = $tipoDesconto;}
function getTipoDesconto(){return $this->tipoDesconto;}

function setEncomenda($encomenda){$this->encomenda = $encomenda;}
function getEncomenda(){return $this->encomenda;}
###### FIM SET's e GET's ######

/**
* @name existeParametro
* @description pesquisa se já existe código do banco
*/
public function existeParametros(){
    $sql  = "SELECT * ";
    $sql .= "FROM fat_parametro ";
    $sql .= "WHERE (filial = ".$this->getFilial().")";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    if(is_array($banco->resultado)){
        return $banco->resultado;
    }else{
        return false;
    }
} //fim existeParametro

 /**
 * @name select_parametros
 * @description pesquisa que retorna os campos do id pesquisado tabela fat_parametros
 */
public function selectParametros(){
    $sql  = "SELECT DISTINCT * ";
    $sql .= "FROM fat_parametro ";
    $sql .= "WHERE (filial = ".$this->getFilial().") ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
} //fim select_parametro

 /**
 * @name select_parametro_geral
 * @description pesquisa que retorna todos os registros cadastrado fat_parametros
 */
public function selectParametrosGeral(){
    $sql  = "SELECT DISTINCT f.*, e.nomefantasia ";
    $sql .= "FROM fat_parametro f ";
    $sql .= "inner join amb_empresa e ON e.centrocusto = f.filial ";
    $sql .= "ORDER BY filial ";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    if(is_array($banco->resultado)){
        return $banco->resultado;
    }else{
        return false;
    }
} //fim select_parametros_geral

 /**
 * @name incluiParametros
 * @description faz a inclusão do registro cadastrado
 */
public function incluiParametros(){

	$sql  = "INSERT INTO fat_parametro (FILIAL, GRUPOSERVICO, FLUXOPEDIDO, SITEMITIRNF, SITBAIXADO,
             SITABERTO, VALORPEDIDOMINIMO, APROVACAO, DESCONTOMAXIMO, LANCPEDBAIXADO, TIPODESCONTO, ENCOMENDA) VALUES (";
    $sql .= $this->getFilial().",";
    if($this->getGrupoServico() !== ''){
        $sql .= "'".$this->getGrupoServico()."',";
    }else{
        $sql .= " null,";
    }
    $sql .= "'".$this->getFluxoPedido()."',";
    $sql .= $this->getSitEmitirNf().",";
    $sql .= $this->getSitBaixado().",";
    $sql .= $this->getSitAberto().",";
    $sql .= $this->getValorPedMinimo().",";
    $sql .= "'".$this->getAprovacao()."',";
    $sql .= $this->getDescontoMaximo().",";
    $sql .= "'".$this->getLancPedBaixado()."',";
    if($this->getTipoDesconto() !== ''){
        $sql .= "'".$this->getTipoDesconto()."', ";
    }else{
        $sql .= " null,";
    }
	$sql .= "'".$this->getEncomenda()."');";
    
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();
        //echo strtoupper($sql)."<BR>";

	return $banco->result;
} // fim incluiParametros

 /**
 * @name alteraParametros
 * @description altera registro existente
 */
public function alteraParametros(){

    $sql = "UPDATE fat_parametro ";
    $sql .= "SET ";
    $sql .= "gruposervico = '".$this->getGrupoServico()."', ";
    $sql .= "fluxopedido = '".$this->getFluxoPedido()."', ";
    $sql .= "sitemitirnf = ".$this->getSitEmitirNf().", ";
    $sql .= "sitbaixado = ".$this->getSitBaixado().", ";
    $sql .= "sitaberto = ".$this->getSitAberto().", ";
    $sql .= "valorpedidominimo = ".$this->getValorPedMinimo().", ";
    $sql .= "aprovacao = '".$this->getAprovacao()."', ";
    $sql .= "descontomaximo = ".$this->getDescontoMaximo().", ";
    $sql .= "lancpedbaixado = '".$this->getLancPedBaixado()."', ";
    $sql .= "tipodesconto = '".$this->getTipoDesconto()."', ";
    $sql .= "encomenda = '".$this->getEncomenda()."'; ";
       
	$banco = new c_banco;
	$result = $banco->exec_sql($sql);
	$banco->close_connection();

    return $banco->result;
}  // fim alteraParametros

 /**
 * @name exlcuiBanco
 * @description esclui resgistro existe
 */
public function excluiParametros(){

	$sql  = "DELETE FROM fat_parametro ";
	$sql .= "WHERE filial = ".$this->getFilial();
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	return $banco->result;
	
}  // fim excluiParametros

/**
 * @name getCasasDecimais
 * @description retorna o valor do parâmetro CASASDECIMAIS da tabela fat_parametro
 */
public function getCasasDecimais(){
    $sql  = "SELECT CASASDECIMAIS ";
    $sql .= "FROM fat_parametro ";
    $sql .= "WHERE (filial = ".$this->getFilial().") ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    
    if(is_array($banco->resultado) && count($banco->resultado) > 0){
        return $banco->resultado[0]['CASASDECIMAIS'];
    }else{
        return 2; // valor padrão se não encontrar
    }
} //fim getCasasDecimais

}	//	END OF THE CLASS
?>
