<?php
/**
 * @package   astec
 * @name      c_produto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      13/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");

//Class C_PRODUTO
Class c_produto extends c_user {
/**
 * TABLE NAME EST_PRODUTO
 */
    
// Campos tabela
private $id             = NULL; // INT(11)
private $desc           = NULL; // VARCHAR(60)
private $descricaoDetalhada = NULL; // TEXT
private $uni            = NULL; // VARCHAR(3)
private $uniFracionada  = NULL; // VARCHAR(1)
private $grupo          = NULL; // VARCHAR(15)
private $fabricante     = NULL; // INT(11)
private $nome           = NULL; // VARCHAR(60)
private $codFabricante  = NULL; // VARCHAR(25)
private $codBarras      = NULL; // VARCHAR(25)
private $codProdutoAnvisa = NULL; // VARCHAR(45)
private $localizacao    = NULL; // VARCHAR(10)
private $dataForaLinha  = NULL; // DATE
private $ncm            = NULL; // VARCHAR(15)
private $cest           = NULL; // VARCHAR(15)
private $origem         = NULL; // CHAR(1)
private $tribIcms       = NULL; // VARCHAR(2)
private $moeda          = NULL; // SMALLINT(6)
private $venda          = 0;    // DECIMAL(9,2)
private $custoMedio     = 0;    // DECIMAL(9,2)
private $custoCompra    = 0;    // DECIMAL(9,2)
private $custoReposicao = 0;    // DECIMAL(9,2)
private $quantMinima    = 0;    // INT(11)
private $quantMaxima    = 0;    // INT(11)
private $obs            = NULL; // TEXT
private $precoPromocao  = NULL; // DECIMAL(9,2)
private $inicioPromocao = NULL; // DATE
private $fimPromocao    = NULL; // DATE
private $quantLimite    = NULL;    // INT(11)
private $tipoPromocao   = NULL; // char
private $precoPromocao1  = NULL; // DECIMAL(9,2)
private $inicioPromocao1 = NULL; // DATE
private $fimPromocao1    = NULL; // DATE
private $quantLimite1    = NULL;    // INT(11)
private $precoBase      = NULL; // DECIMAL(9,2)
private $precoInformado = NULL; // DECIMAL(9,2)
private $percCalculo    = NULL; // DECIMAL(9,2)
private $quantUltimaCompra    = NULL;    // INT(11)
private $nfUltimaCompra    = NULL;    // INT(11)
private $dataUltimaCompra = NULL; // DATE
private $precoMinimo      = 0;    

private $reparoCodFabricante = NULL;


private $codEquivalente    = NULL;    // INT(11)
private $contaEquivalencia    = NULL;    // INT(11)
private $nomeEquivalencia    = NULL;    // INT(11)
private $quantUltimaCompraEquiv    = NULL;    // INT(11)
private $nfUltimaCompraEquiv    = NULL;    // INT(11)
private $dataUltimaCompraEquiv = NULL; // DATE
private $dateChange = NULL; // DATE

private $peso    = NULL; // DECIMAL(9,2)
private $anp    = NULL; // DECIMAL(9,2)
private $marca  = NULL; //varchar(4)
//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

}

/**
* METODOS DE SETS E GETS
*/
public function setMarca($marca) { $this->marca = strtoupper($marca);}
public function getMarca() { return $this->marca;}

public function setId($produto){
         $this->id = $produto;
}
public function getId(){
         return $this->id;
}

public function setDesc($desc){
        $descFormatada = str_replace(array('\'', '"'), ' ', $desc);
         $this->desc = strtoupper($descFormatada);
}
public function getDesc(){
         return trim($this->desc);
}

public function setDescricaoDetalhada($descricaoDetalhada){
    $descFormatada = str_replace(array('\'', '"'), '', $descricaoDetalhada);
    $this->descricaoDetalhada = strtoupper($descFormatada);
}
public function getDescricaoDetalhada(){
    return trim($this->descricaoDetalhada);
}

public function setUni($uni){
         $this->uni = strtoupper($uni);
}
public function getUni(){
         return $this->uni;
}

public function setUniFracionada($uniFracionada){
         $this->uniFracionada = strtoupper($uniFracionada);
}
public function getUniFracionada(){
         return $this->uniFracionada;
}

public function setGrupo($grupo){
         $this->grupo = strtoupper($grupo);
}
public function getGrupo(){
         return $this->grupo;
}

public function setFabricante($fabricante) { $this->fabricante = $fabricante; }
public function getFabricante() { 
	if ($this->fabricante!=null):
            return $this->fabricante;
        else:
            return 0;
        endif;
    
    }

public function getFabricanteNome(){
		$cliente = new c_conta();
		$cliente->setId($this->getFabricante());
		$reg_nome = $cliente->select_conta();
		$this->nome = $reg_nome[0]['NOME'];
		return $this->nome;
}

public function setCodFabricante($codFabricante){
	$this->codFabricante = $codFabricante;
}
public function getCodFabricante(){
	$this->codFabricante = trim($this->codFabricante);
	//return ltrim($this->codFabricante, "0");
	return $this->codFabricante;
}
public function setCodTerceiro($codTerceiro){
	$this->codTerceiro = $codTerceiro;
}
public function getCodTerceiro(){
	return trim($this->codTerceiro);
}

public function setCodBarras($codBarras){
	$this->codBarras = $codBarras;
}
public function getCodBarras(){
	return trim($this->codBarras);
}

public function setCodProdutoAnvisa($codProdutoAnvisa){
	$this->codProdutoAnvisa = $codProdutoAnvisa;
}
public function getCodProdutoAnvisa(){
	return trim($this->codProdutoAnvisa);
}

public function setLocalizacao($localizacao){
         $this->localizacao = strtoupper($localizacao);
}
public function getLocalizacao(){
         return $this->localizacao;
}

public function setDataForaLinha($dataForaLinha) { $this->dataForaLinha=$dataForaLinha; }
public function getDataForaLinha($format = null) { 
        if ($this->dataForaLinha!=''){
                $this->dataForaLinha = strtr($this->dataForaLinha, "/","-");
                switch ($format) {
                        case 'F':
                                return date('d/m/Y', strtotime($this->dataForaLinha));
                                break;
                        case 'B':
                                return c_date::convertDateBd($this->dataForaLinha, $this->m_banco);
                                break;
                        default:
                                return $this->dataForaLinha;
                }
        }
        else
            return null;
}

public function setNcm($ncm){
         $this->ncm = $ncm;
}
public function getNcm(){
         return $this->ncm;
}

public function setCest($cest) { $this->cest = $cest; }
public function getCest() {
         return $this->cest;
}	

public function setOrigem($origem){
         $this->origem = strtoupper($origem);
}
public function getOrigem(){
         return $this->origem;
}

public function setTribIcms($tribIcms) { $this->tribIcms = strtoupper($tribIcms); }
public function getTribIcms() {
    return $this->tribIcms;
}	

public function setMoeda($moeda){
        $this->moeda = $moeda;
}
public function getMoeda(){
	if ($this->moeda!=null):
            return $this->moeda;
        else:
            return 0;
        endif;
}

public function setVenda($venda, $format=false) {
    $this->venda = $venda; 
    if ($format):
            $this->venda = number_format($this->venda, 4, ',', '.');
    endif;
    }
public function getVenda($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->venda), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->venda!=null){
				$num = str_replace('.', '', $this->venda);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
				break;
		default:
			return $this->venda; 
	 }					
}	

public function setCustoMedio($custoMedio, $format=false) {
    $this->custoMedio = $custoMedio; 
    if ($format):
            $this->custoMedio = number_format($this->valorDespAccustoMedioessorias, 2, ',', '.');
    endif;
    }
public function getCustoMedio($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->custoMedio), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->custoMedio!=null){
				$num = str_replace('.', '', $this->custoMedio);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->custoMedio; 
	 }					
}	

public function setCustoCompra($custoCompra, $format=false) {
    $this->custoCompra = $custoCompra; 
    if ($format):
            $this->custoCompra = number_format($this->custoCompra, 2, ',', '.');
    endif;
 }
public function getCustoCompra($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->custoCompra), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->custoCompra!=null){
				$num = str_replace('.', '', $this->custoCompra);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->custoCompra; 
	 }					
	
}	

public function setCustoReposicao($custoReposicao, $format=false) {
    $this->custoReposicao = $custoReposicao; 
    if ($format):
            $this->custoReposicao = number_format($this->custoReposicao, 2, ',', '.');
    endif;
 }
public function getCustoReposicao($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->custoReposicao), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->custoReposicao!=null){
				$num = str_replace('.', '', $this->custoReposicao);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->custoReposicao; 
	 }					
}	

public function setQuantMinima($quantMinima){
	$this->quantMinima = $quantMinima;
}
public function getQuantMinima($format = null){
    switch ($format){
		case 'F':
			return number_format(doubleval($this->quantMinima), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->quantMinima!=null){
				$num = str_replace('.', '', $this->quantMinima);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->quantMinima; 
	 }
}
/*	if ($this->quantMinima!=null):
            return $this->quantMinima;
        else:
            return 0;
        endif;
}*/

public function setQuantMaxima($quantMaxima){
	$this->quantMaxima = $quantMaxima;
}
public function getQuantMaxima($format = null){
    switch ($format){
		case 'F':
			return number_format(doubleval($this->quantMaxima), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->quantMaxima!=null){
				$num = str_replace('.', '', $this->quantMaxima);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->quantMaxima; 
	 }
}
/*	if ($this->quantMaxima!=null):
            return $this->quantMaxima;
        else:
            return 0;
        endif;
}*/

public function setObs($obs){ 
    $descFormatada = str_replace(array('\'', '"'), ' ', $obs);
    $this->obs = strtoupper($descFormatada); 
}
public function getObs(){ return $this->obs; }

public function setPrecoPromocao($precoPromocao, $format=false) {
    $this->precoPromocao = $precoPromocao; 
    if ($format):
            $this->precoPromocao = number_format($this->precoPromocao, 2, ',', '.');
    endif;     

}
public function getPrecoPromocao($format=NULL){
    switch ($format){
        case 'F':
            return number_format(doubleval($this->precoPromocao), 2, ',', '.'); 
            break;
        case 'B':
            if ($this->precoPromocao!=null){
                $num = str_replace('.', '', $this->precoPromocao);
                $num = str_replace(',', '.', $num);
                return $num; }
            else{
                return 0; }
            break;
        default:
            return $this->precoPromocao; 
    }
}

public function setInicioPromocao($inicioPromocao){
         $this->inicioPromocao = $inicioPromocao;
}
public function getInicioPromocao($format=NULL){
    if ($this->inicioPromocao!=''){
        $this->inicioPromocao = strtr($this->inicioPromocao, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->inicioPromocao));
                break;
            case 'B':
                return c_date::convertDateBd($this->inicioPromocao, $this->m_banco);
                break;
            default:
                return $this->inicioPromocao;
        }
    }
    else{
        return null;}
}

public function setFimPromocao($fimPromocao){
         $this->fimPromocao = $fimPromocao;
}
public function getFimPromocao($format=NULL){
     if ($this->fimPromocao!=''){
        $this->fimPromocao = strtr($this->fimPromocao, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->fimPromocao));
                break;
            case 'B':
                return c_date::convertDateBd($this->fimPromocao, $this->m_banco);
                break;
            default:
                return $this->fimPromocao;
        }
    }
    else{
        return null;}
}

public function setQuantLimite($quantLimite){
	$this->quantLimite = $quantLimite;
}
public function getQuantLimite($format=null){
    switch ($format) {
        case 'F':
            return number_format(doubleval($this->quantLimite), 2, ',', '.');
            break;
        case 'B':
            if ($this->quantLimite != null) {
                $num = str_replace('.', '', $this->quantLimite);
                $num = str_replace(',', '.', $num);
                return $num;
            } else {
                return 0;
            }
            break;
        default:
            if($this->quantLimite == null){
                return 0;
            }else{
                return $this->quantLimite;
            }
            
    }
}


//-------------------
public function setTipoPromocao($tipoPromocao){
         $this->tipoPromocao = $tipoPromocao;
}
public function getTipoPromocao(){
         return $this->tipoPromocao;
}


public function setPrecoPromocao1($precoPromocao1, $format=false) {
    $this->precoPromocao1 = $precoPromocao1; 
    if ($format):
            $this->precoPromocao1 = number_format($this->precoPromocao1, 2, ',', '.');
    endif;
}
public function getPrecoPromocao1($format=NULL){
    switch ($format){
        case 'F':
            return number_format(doubleval($this->precoPromocao1), 2, ',', '.'); 
            break;
        case 'B':
            if ($this->precoPromocao1!=null){
                $num = str_replace('.', '', $this->precoPromocao1);
                $num = str_replace(',', '.', $num);
                return $num; }
            else{
                return 0; }
            break;
        default:
            return $this->precoPromocao1; 
    }
}

public function setInicioPromocao1($inicioPromocao1){
         $this->inicioPromocao1 = $inicioPromocao1;
}
public function getInicioPromocao1($format=NULL){
    if ($this->inicioPromocao1!=''){
        $this->inicioPromocao1 = strtr($this->inicioPromocao1, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->inicioPromocao1));
                break;
            case 'B':
                return c_date::convertDateBd($this->inicioPromocao1, $this->m_banco);
                break;
            default:
                return $this->inicioPromocao1;
        }
    }
    else{
        return null;}
}

public function setFimPromocao1($fimPromocao1){
         $this->fimPromocao1 = $fimPromocao1;
}
public function getFimPromocao1($format=NULL){
     if ($this->fimPromocao1!=''){
        $this->fimPromocao1 = strtr($this->fimPromocao1, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->fimPromocao1));
                break;
            case 'B':
                return c_date::convertDateBd($this->fimPromocao1, $this->m_banco);
                break;
            default:
                return $this->fimPromocao1;
        }
    }
    else{
        return null;}
}

public function setQuantLimite1($quantLimite1){
	$this->quantLimite1 = $quantLimite1;
}
public function getQuantLimite1(){
	if ($this->quantLimite1!=null):
            return $this->quantLimite1;
        else:
            return 0;
        endif;
}


public function setDataUltimaCompra($dataUltimaCompra){
         $this->dataUltimaCompra = $dataUltimaCompra;
}
public function getDataUltimaCompra($format=NULL){
    if ($this->dataUltimaCompra!=''){
        $this->dataUltimaCompra = strtr($this->dataUltimaCompra, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dataUltimaCompra));
                break;
            case 'B':
                return c_date::convertDateBd($this->dataUltimaCompra, $this->m_banco);
                break;
            default:
                return $this->dataUltimaCompra;
        }
    }
    else{
        return null;}
}

public function setQuantUltimaCompra($quantUltimaCompra, $format=false) {
    $this->quantUltimaCompra = $quantUltimaCompra; 
    if ($format):
            $this->quantUltimaCompra = number_format($this->quantUltimaCompra, 2, ',', '.');
    endif;

}
public function getQuantUltimaCompra($format=null){
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->quantUltimaCompra), 4, ',', '.'); 
			break;
		case 'B':
			if (($this->quantUltimaCompra!=null) and ($this->quantUltimaCompra!='')){
				$num = str_replace('.', '', $this->quantUltimaCompra);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->quantUltimaCompra; 
	 }					
	
}

public function setNfUltimaCompra($nfUltimaCompra){
	$this->nfUltimaCompra = $nfUltimaCompra;
}
public function getNfUltimaCompra(){
	if ($this->nfUltimaCompra!=null):
            return $this->nfUltimaCompra;
        else:
            return 0;
        endif;
}



//---------------
public function setPrecoBase($precoBase){
         $this->precoBase = $precoBase;
}
public function getPrecoBase(){
         return $this->precoBase;
}

public function setPrecoInformado($precoInformado, $format=false) {
    $this->precoInformado = $precoInformado; 
    if ($format):
            $this->precoInformado = number_format($this->precoInformado, 4, ',', '.');
    endif;
}
public function getPrecoInformado($format=NULL){
    switch ($format){
        case 'F':
            return number_format(doubleval($this->precoInformado), 2, ',', '.'); 
            break;
        case 'B':
            if ($this->precoInformado!=null){
                $num = str_replace('.', '', $this->precoInformado);
                $num = str_replace(',', '.', $num);
                return $num; }
            else{
                return 0; }
            break;
        default:
            return $this->precoInformado; 
    }
}

public function setPercCalculo($percCalculo, $format=false) {
    $this->percCalculo = $percCalculo;
    if ($format):
        $this->percCalculo = number_format($this->percCalculo, 2, ',', '.');
    endif;     
}
public function getPerCalculo($format){
          switch ($format){
        case 'F':
            return number_format(doubleval($this->percCalculo), 2, ',', '.'); 
            break;
        case 'B':
            if ($this->percCalculo!=null){
                $num = str_replace('.', '', $this->percCalculo);
                $num = str_replace(',', '.', $num);
                return $num; }
            else{
                return 0; }
            break;
        default:
            return $this->percCalculo; 
    }
}

//############### SETS E GETS REPARO ###############
public function setIdReparo($id) {$this->idReparo = $id;}
public function getIdReparo(){
    return $this->idReparo;
}

public function setProdutoReparo($cod) {$this->produtoReapro = $cod;}
public function getProdutoReparo(){
    $this->produtoReapro = trim($this->produtoReapro);
    return $this->produtoReapro;
}

public function setReparoCodFabricante($reparoCodFabricante) {$this->reparoCodFabricante = $reparoCodFabricante;}
public function getReparoCodFabricante(){
    $this->reparoCodFabricante = trim($this->reparoCodFabricante);
    return $this->reparoCodFabricante;
}

public function setReparoCodProduto($reparoCodProduto) {$this->reparoCodProduto = $reparoCodProduto;}
public function getReparoCodProduto(){
    $this->reparoCodProduto = trim($this->reparoCodProduto);
    return $this->reparoCodProduto;
}


public function setProdutoIdReparo($produtoId) { $this->produtoIdReparo = $produtoId; }
public function getProdutoIdReparo() { 
if ($this->produtoIdReparo!=null):
       return $this->produtoIdReparo;
   else:
       return 0;
   endif;

}

public function getDescProdutoIdReparo(){
   $produto = new c_conta();
   $produto->setId($this->getProdutoIdReparo());
   $reg_desc = $cliente->select_produto();
   return $reg_desc[0]['DESCRICAO'];
}

public function setQuantReparo($quantReparo, $format=false) {
    $this->quantReparo = $quantReparo;
    if ($format):
        $this->quantReparo = number_format($this->quantReparo, 4, ',', '.');
    endif;
}
public function getQuantReparo($format=null){
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->quantReparo), 2, ',', '.'); 
			break;
		case 'B':
			if (($this->quantReparo!=null) and ($this->quantReparo!='')){
				$num = str_replace('.', '', $this->quantReparo);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->quantReparo; 
	 }					
	
}


//############### SETS E GETS EQUIVALENCIA ###############
public function setIdEquiv($id){
         $this->idEquiv = $id;
}
public function getIdEquiv(){
         return $this->idEquiv;
}

public function setCodEquivalente($cod){
	$this->codEquivalente = $cod;
}
public function getCodEquivalente(){
	$this->codEquivalente = trim($this->codEquivalente);
	return $this->codEquivalente;
}

public function setContaEquiv($conta) { $this->contaEquivalencia = $conta; }
public function getContaEquiv() { 
	if ($this->contaEquivalencia!=null):
            return $this->contaEquivalencia;
        else:
            return 0;
        endif;
    
    }

public function getNomeEquivalencia(){
		$cliente = new c_conta();
		$cliente->setIdEquiv($this->getFabricante());
		$reg_nome = $cliente->select_conta();
		$this->nomeEquivalencia = $reg_nome[0]['NOME'];
		return $this->nomeEquivalencia;
}

public function setDataUltimaCompraEquiv($dataUltimaCompraEquiv){
         $this->dataUltimaCompraEquiv = $dataUltimaCompraEquiv;
}
public function getDataUltimaCompraEquiv($format=NULL){
    if ($this->dataUltimaCompraEquiv!=''){
        $this->dataUltimaCompraEquiv = strtr($this->dataUltimaCompraEquiv, "/","-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dataUltimaCompraEquiv));
                break;
            case 'B':
                return c_date::convertDateBd($this->dataUltimaCompraEquiv, $this->m_banco);
                break;
            default:
                return $this->dataUltimaCompraEquiv;
        }
    }
    else{
        return null;}
}

public function setDateChange($dateChange){
         $this->dateChange = $dateChange;
}
public function getDateChange(){
    if ($this->dateChange!=''){
        $this->dateChange = strtr($this->dateChange, "/","-");
        return date('d/m/Y', strtotime($this->dateChange));
    }
    else{
        return null;
    }
}

public function setQuantUltimaCompraEquiv($quantUltimaCompraEquiv, $format=false) {
    $this->quantUltimaCompraEquiv = $quantUltimaCompraEquiv;
    if ($format):
        $this->quantUltimaCompraEquiv = number_format($this->quantUltimaCompraEquiv, 4, ',', '.');
    endif;
}
public function getQuantUltimaCompraEquiv($format=null){
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->quantUltimaCompraEquiv), 2, ',', '.'); 
			break;
		case 'B':
			if (($this->quantUltimaCompraEquiv!=null) and ($this->quantUltimaCompraEquiv!='')){
				$num = str_replace('.', '', $this->quantUltimaCompraEquiv);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->quantUltimaCompraEquiv; 
	 }					
	
}

public function setNfUltimaCompraEquiv($nfUltimaCompraEquiv){
	$this->nfUltimaCompraEquiv = $nfUltimaCompraEquiv;
}
public function getNfUltimaCompraEquiv(){
	if ($this->nfUltimaCompraEquiv!=null):
            return $this->nfUltimaCompraEquiv;
        else:
            return 0;
        endif;
}


public function setPeso($peso) { $this->peso = $peso; }
public function getPeso($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->peso), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->peso!=null){
				$num = str_replace('.', '', $this->peso);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->peso; 
	 }					
}

public function setPrecoMinimo($precoMinimo) { $this->precoMinimo = $precoMinimo; }
public function getPrecoMinimo($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->precoMinimo), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->precoMinimo!=null){
				$num = str_replace('.', '', $this->precoMinimo);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
			break;
		default:
			return $this->precoMinimo; 
	 }					
	
}

public function setAnp($anp){
    $this->anp = strtoupper($anp);
}
public function getAnp(){
    return $this->anp;
}

function getDesconto($format = NULL) 
{
    if (!empty($this->desconto)) {

        if ($format == 'F') {
            
            // Operador de coalescencia versao php.8.3
            $this->desconto = $this->desconto ?? 0;

            $valor = (float) str_replace(',', '.', $this->desconto);

            return $valor;
        } else {
            return c_tools::moedaBd($this->desconto);
        }

    } else {
        return 0;
    }        
}
//############### FIM SETS E GETS ###############


/**
* Funcao para Unificar Produto
* @name unificaProduto
* @return string vazio se ocorrer com sucesso
*/
public function unificaProduto($codPermanecer, $codRetirar){

    // EST_PRODUTO_ESTOQUE
    $sqlEstProdutoEstoque = "UPDATE EST_PRODUTO_ESTOQUE SET CODPRODUTO = '".$codPermanecer."' WHERE CODPRODUTO = '".$codRetirar."';";

    // FAT_PEDIDO_ITEM
    $sqlFatPedidoItem = "UPDATE FAT_PEDIDO_ITEM SET ITEMESTOQUE = '".$codPermanecer."' WHERE ITEMESTOQUE = '".$codRetirar."';";

    // FAT_PEDIDO_ITEM_COMP
    $sqlFatPedidoItemComp = "UPDATE FAT_PEDIDO_ITEM_COMP SET ITEMESTOQUE = '".$codPermanecer."' WHERE ITEMESTOQUE = '".$codRetirar."';";

    // EST_NOTA_FISCAL_PRODUTO
    $sqlEstNotaFiscalProduto = "UPDATE EST_NOTA_FISCAL_PRODUTO SET CODPRODUTO = '".$codPermanecer."' WHERE CODPRODUTO = '".$codRetirar."';";

    // CAT_AT_PECAS
    $sqlCatAtPecas = "UPDATE CAT_AT_PECAS SET CODPRODUTO = '".$codPermanecer."' WHERE CODPRODUTO = '".$codRetirar."';";

    // EST_INVENTARIO_PRODUTO
    $sqlEstInventarioProduto = "UPDATE EST_INVENTARIO_PRODUTO SET CODPRODUTO = '".$codPermanecer."' WHERE CODPRODUTO = '".$codRetirar."';";

    // EST_ORDEM_COMPRA_ITEM
    $sqlEstOrdemCompraItem = "UPDATE EST_ORDEM_COMPRA_ITEM SET ITEMESTOQUE = '".$codPermanecer."' WHERE ITEMESTOQUE = '".$codRetirar."';";
    
    // EST_PRODUTO_EQUIVALENCIA - INSERE EQUIVALENCIA
    $sqlEstProdutoEquivalenciaInsere = "INSERT INTO EST_PRODUTO_EQUIVALENCIA (IDPRODUTO, CODEQUIVALENTE, NFULTIMACOMPRA, QUANTULTIMACOMPRA)"; 
    $sqlEstProdutoEquivalenciaInsere .= "SELECT '".$codPermanecer."', CODFABRICANTE, NFULTIMACOMPRA, QUANTULTIMACOMPRA FROM EST_PRODUTO WHERE CODIGO = '".$codRetirar."';";

    // EST_PRODUTO_EQUIVALENCIA
    $sqlEstProdutoEquivalencia = "UPDATE EST_PRODUTO_EQUIVALENCIA SET IDPRODUTO = '".$codPermanecer."' WHERE IDPRODUTO = '".$codRetirar."';";

    // EST_PRODUTO_USER
    $sqlEstProdutoUser = "UPDATE EST_PRODUTO_USER SET CODPRODUTO= '".$codPermanecer."' WHERE CODPRODUTO = '".$codRetirar."';";

    // EST_TABELA_PRECO_ITEM
    $sqlEstTabelaPrecoItem = "UPDATE EST_TABELA_PRECO_ITEM SET CODIGO= '".$codPermanecer."' WHERE CODIGO = '".$codRetirar."';";

    // EST_PRODUTO
   $sqlEstProduto = "DELETE FROM EST_PRODUTO WHERE CODIGO = '".$codRetirar."';";

	$banco = new c_banco;
    //inicia transacao
    $banco->inicioTransacao($banco->id_connection);
    
    try{
	    $resEstProdutoEstoque            =  $banco->exec_sql($sqlEstProdutoEstoque, $banco->id_connection);
        $resFatPedidoItem                =  $banco->exec_sql($sqlFatPedidoItem, $banco->id_connection);
	    $resFatPedidoItemComp            =  $banco->exec_sql($sqlFatPedidoItemComp, $banco->id_connection);
        $resEstNotaFiscalProduto         =  $banco->exec_sql($sqlEstNotaFiscalProduto, $banco->id_connection);
	    $resCatAtPecas                   =  $banco->exec_sql($sqlCatAtPecas, $banco->id_connection);
        $resEstInventarioProduto         =  $banco->exec_sql($sqlEstInventarioProduto, $banco->id_connection);
	    $resEstOrdemCompraItem           =  $banco->exec_sql($sqlEstOrdemCompraItem, $banco->id_connection);
        $resEstProdutoEquivalenciaInsere =  $banco->exec_sql($sqlEstProdutoEquivalenciaInsere, $banco->id_connection);
        $resEstProdutoEquivalencia       =  $banco->exec_sql($sqlEstProdutoEquivalencia, $banco->id_connection);
        $resEstProdutoUser               =  $banco->exec_sql($sqlEstProdutoUser, $banco->id_connection);
        $resEstTabelaPrecoItem           =  $banco->exec_sql($sqlEstTabelaPrecoItem, $banco->id_connection);
        $resEstProduto                   =  $banco->exec_sql($sqlEstProduto, $banco->id_connection);
        //echo strtoupper($sqlEstOrdemCompraItem)."<BR>";
 
        $banco->commit($banco->id_connection);
        $banco->close_connection($banco->id_connection);
        return $banco->result;

    }
    catch(Exception $e){
        if ($banco->id_connection != null){
            $banco->rollback($banco->id_connection);
            $banco->close_connection($banco->id_connection);
            return false;
        }
     
    }
	
}  // fim excluiPRODUTO


//############### FUNCTION EQUIVALENCIA ###############

    public function select_produto_equivalencia(){
//            $sql  = "SELECT DISTINCT E.* ";
            $sql  = "SELECT DISTINCT E.*, C.NOME ";
            $sql .= "FROM est_produto_equivalencia E ";
            $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = E.CONTA) ";
            $sql .= "WHERE (idproduto = ".$this->getId().") order by codequivalente";
            //echo strtoupper($sql)."<BR>";
            $banco = new c_banco();
            $banco->exec_sql($sql);
            $banco->close_connection();
            return $banco->resultado;
    } //fim select_PRODUTO



    /**
     * @name     incluiProdutoEquivalencia
     * @param    string gets de todos os objetos private da classe
     * @return   INSERT retorna VAZIO caso a insercao ocorra com sucesso
     */ 
    public function incluiProdutoEquivalencia($conn=null) {
        $banco = new c_banco;
        $sql = "INSERT INTO EST_PRODUTO_EQUIVALENCIA (";
        $sql .= "IDPRODUTO, CODEQUIVALENTE, CONTA, NFULTIMACOMPRA, DATAULTIMACOMPRA, QUANTULTIMACOMPRA, USERINSERT, DATEINSERT) ";
        $sql .= "values ( ";
        $sql .= $this->getId().", '".  $this->getCodEquivalente()."', ".  $this->getContaEquiv().", ";
        $sql .= $this->getNfUltimaCompraEquiv().", ";
    if ($this->getDataUltimaCompraEquiv('B')==''):
        $sql .= "null, ";
    else:
        $sql .= "'".$this->getDataUltimaCompraEquiv('B')."', ";endif;
        $sql .= $this->getQuantUltimaCompraEquiv('B').", ".$this->m_userid.",'".date("Y-m-d H:i:s")."'); ";
        $resProduto = $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        if ($banco->row > 0){
            return true;
        } else {
            return 'Os dados do Item ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }//if
    }

/**
* Funcao para Alteracao dados do produto conforme dados da nf de entrada
* @name alteraProdutoNFEntrada
* @return string vazio se ocorrer com sucesso
 * incluir Numero da Ultima NF entrada e saida no cadastro.
*/
public function alteraProdutoEquivalencia(){
	$sql  = "UPDATE est_produto_equivalencia ";
	$sql .= "SET " ;
	$sql .= "NFULTIMACOMPRA = '".$this->getNfUltimaCompraEquiv()."', ";
	$sql .= "DATAULTIMACOMPRA = '".$this->getDataUltimaCompraEquiv('B')."', ";
	$sql .= "QUANTULTIMACOMPRA = '".$this->getQuantUltimaCompraEquiv('B')."', ";
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE (IDPRODUTO = ".$this->getID().") AND (CODEQUIVALENTE = '".$this->getCodEquivalente()."') and (CONTA = ".$this->getContaEquiv().");";
	$banco = new c_banco;
	$resProduto =  $banco->exec_sql($sql);
	$banco->close_connection();
	if ($banco->row > 0){
        return true;
	}
	else{
        return false;
        //return 'Os dados do Item '.$this->getDesc().' n&atilde;o foi alterado!';
	}

}  // fim alteraPRODUTO

/**
* Funcao para Exclusao no banco
* @name excluiProdutoEquivalencia
* @return string vazio se ocorrer com sucesso
*/
public function excluiProdutoEquivalencia(){
	$sql  = "DELETE FROM est_produto_equivalencia ";
	$sql .= "WHERE id = '".$this->getIdEquiv()."';";
	$banco = new c_banco;
	$resProduto =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resProduto > 0){
        return '';
	}
	else{
        return 'Os dados do Item '.$this->getId().' n&atilde;o foi excluido!';
	}
}  // fim excluiPRODUTO

//############### FUNCTION REPARO ###############

/**
 * @name     selectProdutoReparo
 * @param    idProdutoReparo Código do produtos principal do reparo
 * @return   lista de todos os produtos que compõe o Reparo
 */ 
public function selectProdutoReparo($idProdutoReparo){
    $sql  = "SELECT DISTINCT R.*, P.DESCRICAO, P.CODFABRICANTE ";
    $sql .= "FROM est_produto_reparo R ";
    $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO = R.PRODUTO_ID) ";
    $sql .= "WHERE (R.produto_reparo = ".$idProdutoReparo.") order by id";
    //echo strtoupper($sql)."<BR>";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
} //fim selectProdutoReparo
    
/**
 * @name     incluiProdutoReparo
 * @param    string gets de todos os objetos private da classe
 * @return   INSERT retorna TRUE caso a insercao ocorra com sucesso ou msg de erro
 */ 
public function incluiProdutoReparo($idProdutoReparo, $produtoId, $quant, $conn=null) {
    $sql = "INSERT INTO EST_PRODUTO_REPARO (";
    $sql .= "PRODUTO_REPARO, PRODUTO_ID, QUANT, USERINSERT) ";
    $sql .= "values ( ";
    $sql .= $idProdutoReparo.", '".  $produtoId."', ".  $quant.", ".$this->m_userid.");";

    $banco = new c_banco;
    $resProduto = $banco->exec_sql($sql, $conn);
    $banco->close_connection();
    if ($banco->row > 0){
        return true;
    } else {
        return 'Os Item ' . $produtoId . ' n&atilde;o foi cadastrado!';
}//fim incluiProdutoReparo
}

/**
* Funcao para Alteracao dados do produto reaparo
* @name alteraProdutoReparo
* @return string vazio se ocorrer com sucesso
*/
public function alteraProdutoReparo($id, $idProdutoReparo, $produtoId, $quant, $conn=null){
    $sql  = "UPDATE est_produto_reparo ";
    $sql .= "SET " ;
    $sql .= "PRODUTO_REPARO = '".$idProdutoReparo."', ";
    $sql .= "PRODUTO_ID = '".$produtoId."', ";
    $sql .= "QUANT = '".$quant."', ";
    $sql .= "userchange = ".$this->m_userid.", ";
    $sql .= "WHERE (ID = ".$id.");";

    $banco = new c_banco;
    $resProduto =  $banco->exec_sql($sql);
    $banco->close_connection();
    if ($banco->row > 0){
        return true;
    }
    else{
        // return false;
        return 'O Item n&atilde;o foi alterado!';
    }

}  // fim alteraProdutoReparo
    
    /**
    * Funcao para Exclusao do item pertencente ao reparo
    * @name excluiProdutoReparo
    * @return string vazio se ocorrer com sucesso
    */
    public function excluiProdutoReparo($id){
        $sql  = "DELETE FROM est_produto_reparo ";
        $sql .= "WHERE id = '".$id."';";
        $banco = new c_banco;
        $resProduto =  $banco->exec_sql($sql);
        $banco->close_connection();
        if($resProduto > 0){
            return true;
        }
        else{
            return 'O item n&atilde;o foi excluido!';
        }
    }  // fim excluiProdutoReparo

    function removeAcentos($string, $slug = false)
    {
        $conversao = array(
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e',
            'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö" => "o",
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï' => 'I', "Ö" => "O", 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N'
        );
        return strtr($string, $conversao);
    }

/**
 * Funcao para setar todos os objetos da classe
 * @name produto  
 * @param INT GetId Codigo do produto, chave primaria
 */
 function produto() {
    $produto = $this->select_produto();
    $this->setDesc($produto[0]['DESCRICAO']);
    $this->setDescricaoDetalhada($produto[0]['DESCRICAODETALHADA']);
    $this->setUni($produto[0]['UNIDADE']);
    $this->setUniFracionada($produto[0]['UNIFRACIONADA']);
    $this->setGrupo($produto[0]['GRUPO']);
    $this->setFabricante($produto[0]['FABRICANTE']);
    $this->setCodFabricante($produto[0]['CODFABRICANTE']);
    $this->setCodTerceiro($produto[0]['CODTERCEIRO']);
    $this->setLocalizacao($produto[0]['LOCALIZACAO']);
    $this->setCodBarras($produto[0]['CODIGOBARRAS']);
    $this->setCodProdutoAnvisa($produto[0]['CODPRODUTOANVISA']);
    $this->setDataForaLinha($produto[0]['DATAFORALINHA']);
    $this->setNcm($produto[0]['NCM']);
    $this->setCest($produto[0]['CEST']);
    $this->setOrigem($produto[0]['ORIGEM']);
    $this->setTribIcms($produto[0]['TRIBICMS']);
    $this->setMoeda($produto[0]['MOEDA']);
    $this->setVenda($produto[0]['VENDA']);
    $this->setCustoMedio($produto[0]['CUSTOMEDIO']);
    $this->setCustoCompra($produto[0]['CUSTOCOMPRA']);
    $this->setcustoReposicao($produto[0]['CUSTOREPOSICAO']);
    $this->setQuantMinima($produto[0]['QUANTMINIMA']);
    $this->setQuantMaxima($produto[0]['QUANTMAXIMA']);
    $this->setObs($produto[0]['OBS']);
    $this->setPrecoPromocao($produto[0]['PRECOPROMOCAO']);
    $this->setInicioPromocao($produto[0]['INICIOPROMOCAO']);
    $this->setFimPromocao($produto[0]['FIMPROMOCAO']);
    $this->setQuantLimite($produto[0]['QUANTLIMITE']);
    $this->setTipoPromocao($produto[0]['TIPOPROMOCAO']);
    $this->setPrecoPromocao1($produto[0]['PRECOPROMOCAO1']);
    $this->setInicioPromocao1($produto[0]['INICIOPROMOCAO1']);
    $this->setFimPromocao1($produto[0]['FIMPROMOCAO1']);
    $this->setQuantLimite1($produto[0]['QUANTLIMITE1']);
    $this->setDataUltimaCompra($produto[0]['DATAULTIMACOMPRA']);
    $this->setQuantUltimaCompra($produto[0]['QUANTULTIMACOMPRA']);
    $this->setNfUltimaCompra($produto[0]['NFULTIMACOMPRA']);
    $this->setPrecoBase($produto[0]['PRECOBASE']);
    $this->setPrecoInformado($produto[0]['PRECOINFORMADO']);
    $this->setPercCalculo($produto[0]['PERCCALCULO']);
    $this->setDateChange($produto[0]['DATECHANGE']);
    $this->setPeso($produto[0]['PESO']);
    $this->setPrecoMinimo($produto[0]['PRECOMINIMO']);
    $this->setAnp($produto[0]['ANP']);
     
 }
 /**
 * @name produtoXmlJson
 * Funcao para montar um Json através do XML da NFe
 * @param XML arquivo xml  nfe  a partir do nivel infNFe->det[$i]->prod
 * @param int código da pessoa
 * @param char tipo de dados a ser setado - J = Json / P = Set Produto / N = Set NF Produto
 * @param obj objeto a ser setado = Produto / NF Produto
 * @param string codigo do produto
 * @return STRING JSON com os dados do cliente
 */
public function produtoXmlJson($item, $pessoa, $tipoDados = 'J', $obj = null, $codProduto = null, $lastNf = null) {

    $custoMedio = (double) str_replace('.', ',',  $item->prod->vUnCom);
    $custoReposicao = (double) str_replace('.', ',',  $item->prod->vUnCom);
    $modBC = 0;
    $vBC = 0;
    $pICMS = 0;
    $vICMS = 0;
    $pRedBC = 0; // incluir
    $plICMS = 0;
    $vlICMS = 0;
    $modBCST = 0; // incluir
    $pMVAST = 0; // incluir
    $pRedBCST = 0; // incluir
    $vBCST = 0; // incluir
    $plICMSST = 0; // incluir
    $vlICMSST = 0; // incluir

    $vBCSTRet = 0;
    $vICMSSTRet = 0;
    $pST = 0;
    $vICMSSubstituto = 0;   
    
    if ($item->imposto->ICMS->ICMS00->orig != ''): // tributado integralmente
        $origem = (string) $item->imposto->ICMS->ICMS00->orig;
        $cst = (string) $item->imposto->ICMS->ICMS00->CST;

        $modBC = (string) $item->imposto->ICMS->ICMS00->modBC;
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS00->vBC);
        $pICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS00->pICMS);
        $vICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS00->vICMS);
    elseif ($item->imposto->ICMS->ICMS10->orig != ''):// Tributada e com cobrança do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS10->orig;
        $cst = (string) $item->imposto->ICMS->ICMS10->CST;
        
        $modBC = (string) $item->imposto->ICMS->ICMS10->modBC;
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->vBC);
        $pICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->plICMS);
        $vICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->vlICMS);
        $modBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->modBCST); // incluir
        $pMVAST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->pMVAST); // incluir
        $pRedBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->pRedBCST); // incluir
        $vBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->vBCST); // incluir
        $plICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->plICMSST); // incluir
        $vlICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS10->vlICMSST); // incluir
        
    elseif ($item->imposto->ICMS->ICMS20->orig != ''):// Tributação com redução de base de cálculo
        $origem = (string) $item->imposto->ICMS->ICMS20->orig;
        $cst = (string) $item->imposto->ICMS->ICMS20->CST;
        
        $modBC = (string) $item->imposto->ICMS->ICMS20->modBC;
        $pRedBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->pRedBC); // incluir
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->vBC);
        $plICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->plICMS);
        $vlICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->vlICMS);
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS20->motDesICMS); // incluir
    elseif ($item->imposto->ICMS->ICMS30->orig != ''):// Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS30->orig;
        $cst = (string) $item->imposto->ICMS->ICMS30->CST;

        $modBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->modBCST); // incluir
        $pMVAST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->pMVAST); // incluir
        $pRedBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->pRedBCST); // incluir
        $vBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->vBCST); // incluir
        $plICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->plICMSST); // incluir
        $vlICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->vlICMSST); // incluir
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS30->motDesICMS); // incluir
        elseif ($item->imposto->ICMS->ICMS40->orig != ''):// Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS40->orig;
        $cst = (string) $item->imposto->ICMS->ICMS40->CST;
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS40->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS40->motDesICMS); // incluir
        elseif ($item->imposto->ICMS->ICMS41->orig != ''):// Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS41->orig;
        $cst = (string) $item->imposto->ICMS->ICMS41->CST;
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS41->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS41->motDesICMS); // incluir
        elseif ($item->imposto->ICMS->ICMS50->orig != ''):// Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS50->orig;
        $cst = (string) $item->imposto->ICMS->ICMS50->CST;
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS50->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS50->motDesICMS); // incluir
        elseif ($item->imposto->ICMS->ICMS51->orig != ''):  // Tributação com Diferimento (a exigência do preenchimento das
                                                            //informações do ICMS diferido fica a critério de cada UF).
        $origem = (string) $item->imposto->ICMS->ICMS51->orig;
        $cst = (string) $item->imposto->ICMS->ICMS51->CST;
        
        $modBC = (string) $item->imposto->ICMS->ICMS51->modBC;
        $pRedBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->pRedBC); // incluir
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->vBC);
        $plICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->plICMS);
        $vlICMSOp = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->vlICMSOp);
        $pDif = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->pDif);
        $vlICMSDif = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->vlICMSDif); // incluir
        $vlICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS51->vlICMS);
        
    elseif ($item->imposto->ICMS->ICMS60->orig != ''): // Tributação ICMS cobrado anteriormente por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS60->orig;
        $cst = (string) $item->imposto->ICMS->ICMS60->CST;
        /////////////////////////////// NOVO
        $vBCSTRet = (double) $item->imposto->ICMS->ICMS60->vBCSTRet; // incluir
        $vICMSSTRet = (double) $item->imposto->ICMS->ICMS60->vICMSSTRet; // incluir
        $pST = (double) $item->imposto->ICMS->ICMS60->pST; // incluir ALIQICMSST

        $vICMSSubstituto = (double) $item->imposto->ICMS->ICMS60->vICMSSubstituto; // incluir
        $vBCFCPSTRet = (double) $item->imposto->ICMS->ICMS60->vBCFCPSTRet; // incluir
        $pFCPSTRet = (double) $item->imposto->ICMS->ICMS60->pFCPSTRet; // incluir
        $vFCPSTRet = (double) $item->imposto->ICMS->ICMS60->vFCPSTRet; // incluir

        $pRedBCEfet = (double) $item->imposto->ICMS->ICMS60->pRedBCEfet; // incluir
        $vBCEfet = (double) $item->imposto->ICMS->ICMS60->vBCEfet; // incluir
        $pICMSEfet = (double) $item->imposto->ICMS->ICMS60->pICMSEfet; // incluir
        $vICMSEfet = (double) $item->imposto->ICMS->ICMS60->vICMSEfet; // incluir
    elseif ($item->imposto->ICMS->ICMS70->orig != ''): // Tributação ICMS com redução de base de cálculo e cobrança
                                                       // do ICMS por substituição tributária
        $origem = (string) $item->imposto->ICMS->ICMS70->orig;
        $cst = (string) $item->imposto->ICMS->ICMS70->CST;

        $modBC = (string) $item->imposto->ICMS->ICMS70->modBC;
        $pRedBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->pRedBC); // incluir
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vBC);
        $plICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->plICMS);
        $vlICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vlICMS);
        $modBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->modBCST); // incluir
        $pMVAST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->pMVAST); // incluir
        $pRedBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->pRedBCST); // incluir
        $vBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vBCST); // incluir
        $plICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->plICMSST); // incluir
        $vlICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vlICMSST); // incluir
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->motDesICMS); // incluir

        // $pICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->pICMS);
        // $vICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS70->vICMS);
        
    elseif ($item->imposto->ICMS->ICMS90->orig != ''): // Tributação ICMS: Outros
        $origem = (string) $item->imposto->ICMS->ICMS90->orig;
        $cst = (string) $item->imposto->ICMS->ICMS90->CST;
        
        $modBC = (string) $item->imposto->ICMS->ICMS90->modBC;
        $pRedBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->pRedBC); // incluir
        $vBC = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->vBC);
        $plICMS =(double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->plICMS);
        $vlICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->vlICMS);
        $modBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->modBCST); // incluir
        $pMVAST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->pMVAST); // incluir
        $pRedBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->pRedBCST); // incluir
        $vBCST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->vBCST); // incluir
        $plICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->plICMSST); // incluir
        $vlICMSST = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->vlICMSST); // incluir
        // $vICMSDeson = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->vICMSDeson); // incluir
        // $motDesICMS = (double) str_replace('.', ',',  $item->imposto->ICMS->ICMS90->motDesICMS); // incluir
    endif;

        // Grupo de Partilha do ICMS
        // Grupo de Repasse do ICMS ST

    
    $unidade = (string) $this->removeAcentos($item->prod->uCom, $slug = false);

     switch ($tipoDados){
        case 'J':
            if(isset($item->prod->infAdProd)){
                    //cria um array produto
                    $conta = array('conta' => array(
                        array('campo' => 'opcao', 'valor' => 'cadastrar'),
                        array('campo' => 'mod', 'valor' => 'est'),
                        array('campo' => 'form', 'valor' => 'produto'),
                        array('campo' => 'submenu', 'valor' => 'cadastrar'),
                        array('campo' => 'codigo', 'valor' => $codProduto),
                        array('campo' => 'desc', 'valor' => substr($item->prod->xProd, 0, 100)),
                        array('campo' => 'produtoNome', 'valor' => substr($item->prod->xProd, 0, 60)),
                        array('campo' => 'pessoa', 'valor' => $pessoa),
                        array('campo' => 'codFabricante', 'valor' => (string) $item->prod->infAdProd),
                        //                array('campo' => 'codFabricante', 'valor' => (int) $item->prod->cProd),
                        array('campo' => 'codBarras', 'valor' => (string) $item->prod->cEANTrib),
                        //                array('campo' => 'codProdudoAnvisa', 'valor' => (string) $item->prod->cEAN),
                        array('campo' => 'ncm', 'valor' => (string) $item->prod->NCM),
                        array('campo' => 'cest', 'valor' => (string) $item->prod->CEST),
                        array('campo' => 'uni', 'valor' => substr((string) $unidade, 0, 2)),
                        array('campo' => 'quantMinima', 'valor' => 0),
                        array('campo' => 'quantMaxima', 'valor' => 10),
                        array('campo' => 'custoCompra', 'valor' => (float) $item->prod->vUnCom),
                        array('campo' => 'custoMedio', 'valor' => $custoMedio),
                        array('campo' => 'custoReposicao', 'valor' => $custoReposicao),
                        array('campo' => 'origem', 'valor' => $origem),
                        array('campo' => 'tribIcms', 'valor' => $cst),
                    ));
            }else{
                //cria um array produto
                $conta = array('conta' => array(
                    array('campo' => 'opcao', 'valor' => 'cadastrar'),
                    array('campo' => 'mod', 'valor' => 'est'),
                    array('campo' => 'form', 'valor' => 'produto'),
                    array('campo' => 'submenu', 'valor' => 'cadastrar'),
                    array('campo' => 'codigo', 'valor' => $codProduto),
                    array('campo' => 'desc', 'valor' => substr($item->prod->xProd, 0, 100)),
                    array('campo' => 'produtoNome', 'valor' => substr($item->prod->xProd, 0, 60)),
                    array('campo' => 'pessoa', 'valor' => $pessoa),
                    array('campo' => 'codFabricante', 'valor' => (string) $item->prod->cProd),
        //                array('campo' => 'codFabricante', 'valor' => (int) $item->prod->cProd),
                    array('campo' => 'codBarras', 'valor' => (string) $item->prod->cEANTrib),
        //                array('campo' => 'codProdudoAnvisa', 'valor' => (string) $item->prod->cEAN),
                    array('campo' => 'ncm', 'valor' => (string) $item->prod->NCM),
                    array('campo' => 'cest', 'valor' => (string) $item->prod->CEST),
                    array('campo' => 'uni', 'valor' => substr((string) $unidade, 0, 2)),
                    array('campo' => 'quantMinima', 'valor' => 0),
                    array('campo' => 'quantMaxima', 'valor' => 10),
                    array('campo' => 'custoCompra', 'valor' => (double) $item->prod->vUnCom),
                    array('campo' => 'custoMedio', 'valor' => $custoMedio),
                    array('campo' => 'custoReposicao', 'valor' => $custoReposicao),
                    array('campo' => 'origem', 'valor' => $origem),
                    array('campo' => 'tribIcms', 'valor' => $cst),
                    ));
            }

            //converte o conteúdo do array para uma string JSON
            $json_str = json_encode($conta);

            if ($json_str === false) {
                $errorCode = json_last_error();
                $errorMessage = json_last_error_msg();
                echo "Erro de codificação JSON: Código $errorCode - $errorMessage";
            }

            return $json_str;
            break;
        case 'P':
            $obj->setId($codProduto);
            $obj->setFabricante($pessoa);

            if(isset($item->prod->infAdProd)){
                $obj->setCodFabricante($$item->prod->infAdProd);
            }else{
                $obj->setCodFabricante($item->prod->cProd);
            }

            $obj->setCodBarras($item->prod->cEANTrib);
            $obj->setNcm($item->prod->NCM);
            $obj->setCest($item->prod->CEST);
            $obj->setCustoCompra(str_replace('.', ',', $item->prod->vUnCom));
            $obj->setQuantUltimaCompra(str_replace('.', ',', $item->prod->qCom));
            $obj->setOrigem($origem);
            $obj->setTribIcms($cst);
            return true;
            break;
        case 'N':
            $obj->setIdNf($lastNf);
            $obj->setCodProduto($codProduto);
            $obj->setDescricao(substr($item->prod->xProd, 0, 100));
            $obj->setUnidade(substr($unidade, 0, 2));
            
            $obj->setQuant(str_replace('.', ',', $item->prod->qCom));
            $obj->setUnitario(str_replace('.', ',', $item->prod->vUnCom));
            $obj->setDesconto(0);
            $obj->setTotal(str_replace('.', ',', $item->prod->vProd));
            
            $obj->setOrigem($origem);
            $obj->setTribIcms($cst);
            $obj->setBcIcms(str_replace('.', ',', $vBC));
            $obj->setCfop($item->prod->CFOP);
            $obj->setValorIcms(str_replace('.', ',', $vICMS));
            $obj->setValorIpi(0);
            $obj->setAliqIcms(str_replace('.', ',', $pICMS));
            $obj->setAliqIpi(0);
            $obj->setNcm($item->prod->NCM);
            $obj->setCest($item->prod->CEST);
            
            $obj->setVBCSTRet(str_replace('.', ',', $vBCSTRet));
            $obj->setVICMSSubstituto(str_replace('.', ',', $vICMSSubstituto));
            $obj->setVICMSSTRet(str_replace('.', ',', $vICMSSTRet));
            
            $obj->setCstPis(str_replace('.', ',', $item->imposto->PIS->PISAliq->CST));
            $obj->setBcPis(str_replace('.', ',', $item->imposto->PIS->PISAliq->vBC));
            $obj->setAliqPis(str_replace('.', ',', $item->imposto->PIS->PISAliq->pPIS));
            $obj->setValorPis(str_replace('.', ',', $item->imposto->PIS->PISAliq->vPIS));
            $obj->setCstCofins(str_replace('.', ',', $item->imposto->COFINS->COFINSAliq->CST));
            $obj->setBcCofins(str_replace('.', ',', $item->imposto->COFINS->COFINSAliq->vBC));
            $obj->setAliqCofins(str_replace('.', ',', $item->imposto->COFINS->COFINSAliq->pCOFINS));
            $obj->setValorCofins(str_replace('.', ',', $item->imposto->COFINS->COFINSAliq->vCOFINS));
            
            // ordem de servico
            //$par = explode(" ", $item->infAdProd);			
            //$obj->setOrdem(number_format($par[3],0,'',''));
            //$obj->setProjeto('');
            return true;
            break;
        default:
            return false;
     }				



}//fim produtoXmlJson

public function movimento_estoque_entrada($par){

    $dataIni = c_date::convertDateTxt($par[0]) ;
    $dataFim = c_date::convertDateTxt($par[1]) ;
    $produto = $par[2];
    $grupo = $par[3];
    $codFab = $par[4];

    $sql =  "SELECT NFP.IDNF, NFP.CODPRODUTO, NFP.DESCRICAO, NFP.QUANT AS ENTRADA, NFP.TOTAL AS TOTAL_ENTRADA, NF.DATASAIDAENTRADA AS DATA_ENTRADA ";
    $sql .= "FROM EST_NOTA_FISCAL_PRODUTO NFP ";
    $sql .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID) ";
    $sql .= "LEFT JOIN EST_PRODUTO PROD ON (NFP.CODPRODUTO = PROD.CODIGO) ";


    $sqlData = "(NF.DATASAIDAENTRADA BETWEEN '".$dataIni."' AND '".$dataFim."')";
    $sqlProduto = "(NFP.DESCRICAO = '".$produto."')";
    $sqlGrupo = "(PROD.GRUPO = '".$grupo."')";
    $sqlCodFab = "(PROD.CODFABRICANTE = '".$codFab."')";

    if($dataIni != '' ||  $produto != '' || $grupo != '' || $codFab != '' ){
        $sql .= "WHERE ( ";

        if($dataIni != ''){
            $sql .= $sqlData;
            if($produto != '' || $grupo != '' || $codFab != ''){
                $sql .= " AND ";
            }
        }

        if($produto != ''){
            $sql .= $sqlProduto;
            if($grupo != '' || $codFab != ''){
                $sql .= " AND ";
            }
        }
        if($grupo != ''){
            $sql .= $sqlGrupo;
            if($codFab != ''){
                $sql .= " AND ";
            }
        }
        if ($codFab != ''){
            $sql .= $sqlCodFab;
             
        }
        $sql .= " ) ";
    }else{
        $sql .= "";
    }

    $sql .= "ORDER BY NF.DATASAIDAENTRADA ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}



public function movimento_estoque_saida($par){

    $dataIni = c_date::convertDateTxt($par[0]) ;
    $dataFim = c_date::convertDateTxt($par[1]) ;
    $produto = $par[2];
    $grupo = $par[3];
    $codFab = $par[4];

    $sql =  "SELECT P.PEDIDO, PI.ITEMESTOQUE AS CODPRODUTO ,PI.DESCRICAO, PI.QTSOLICITADA AS SAIDA, P.TOTAL AS TOTAL_SAIDA, P.EMISSAO AS DATA_SAIDA ";
    $sql .= "FROM FAT_PEDIDO_ITEM PI ";
    $sql .= "LEFT JOIN FAT_PEDIDO P ON (P.ID = PI.ID) ";
    $sql .= "LEFT JOIN EST_PRODUTO PROD ON (PI.ITEMESTOQUE = PROD.CODIGO) ";

    $sqlData = "(P.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."')";
    $sqlProduto = "(PI.DESCRICAO = '".$produto."')";
    $sqlGrupo = "(PROD.GRUPO = '".$grupo."')";
    $sqlCodFab = "(PROD.CODFABRICANTE = '".$codFab."')";

    if($dataIni != '' || $dataFim != '' || $produto != '' || $grupo != '' || $codFab != '' ){
        $sql .= "WHERE ( ";

        if($dataIni != ''){
            $sql .= $sqlData;
            if($produto != '' || $grupo != '' || $codFab != ''){
                $sql .= " AND ";
            }
        }

        if($produto != ''){
            $sql .= $sqlProduto;
            if($grupo != '' || $codFab != ''){
                $sql .= " AND ";
            }
        }
        if($grupo != ''){
            $sql .= $sqlGrupo;
            if($codFab != ''){
                $sql .= " AND ";
            }
        }
        if ($codFab != ''){
            $sql .= $sqlCodFab;
             
        }
        $sql .= " ) ";
    }else{
        $sql .= "";
    }

    $sql .= "ORDER BY PI.ITEMESTOQUE ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}


/**
 * Funcao para quantidade atual de um produto
 * @name quant_atual
 * @param Integer getId Codigo do produto
 * @return BOOLEAN se existir registro retorna TRUE
 */
public function quant_atual($id){
    
    $sql  = "SELECT * ";
    $sql .= "FROM est_produto ";
    $sql .= "WHERE (id = '".$id."')";

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return is_array($banco->resultado);
} //fim existe_produto_fabricante

/**
 * Funcao para verificar a existencia de registros pelo cod Fabricante na tabela de produtos e produto equivalência
 * @name existeProdutoFabricante
 * @param String  Codigo do fabricante do produto
 * @return String se existir registro retorna MSG
 */
public function existeProdutoFabricante($codigo){
    if ( $codigo != ''){
        $sql  = "SELECT * FROM est_produto WHERE (codfabricante = '".$codigo."')";
        $banco = new c_banco();
        $banco->exec_sql($sql);
            //echo strtoupper($sql);

        $msg ='';
        if (is_array($banco->resultado)) {
            $msg = 'Código fabricante já cadastrado no item: '.$banco->resultado[0]['DESCRICAO'];
        }else {
            $sql  = "SELECT * FROM est_produto_equivalente WHERE (codequivalente = '".$codigo."')";
            $banco = new c_banco();
            $banco->exec_sql($sql);
            if (is_array($banco->resultado)) {
                $sql  = "SELECT * FROM est_produto WHERE (codigo = '".$banco->resultado[0]['IDPRODUTO']."')";
                $banco = new c_banco();
                $banco->exec_sql($sql);
                $msg = 'Código Código fabricante já cadastrado como equivalência no item: '.$banco->resultado[0]['DESCRICAO'];
            }
        }
        $banco->close_connection();
    }
	return $msg;
} //fim existeProdutoFabricante

/**
 * Funcao pesquisa da table produtos atraves da desc do produto
 * @name select_produto_nome
 * @param String $nome Descricao do produto
 * @return ARRAY todas as colunas do select
 */
public function select_produto_nome($nome){
	$sql  = "SELECT o.id, p.*, n.numero ";
   	$sql .= "FROM est_nota_fiscal_produto_os o ";
   	$sql .= "left join est_produto p on (o.codproduto =  p.codigo) ";
   	$sql .= "left join est_NOTA_FISCAL N on (o.idnfentrada =  n.id) ";
   	$sql .= "WHERE (p.descricao like '%".$nome."%'); ";
//	echo strtoupper($sql)."<BR>";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_PRODUTO

/**
 * Funcao pesquisa table produtos atraves do ID
 * @name select_produto
 * @param INT GetId Codigo da table produtos, chave primaria
 * @return ARRAY todos os campos da table
 */
public function select_produto(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM est_produto ";
   	$sql .= "WHERE (codigo = ".$this->getId().") ";
	//echo strtoupper($sql)."<BR>";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_PRODUTO


/**
 * <b> Funcao responsavel para pesquisa principal do formulario produto </b>
 * @param $letra (descricao|grupo|cod. Fabricante|localizacao)
 * @return type
 */
public function select_produto_letra($letra = null, $codigo = null){
    /*
    par[0] = 'Nome produto';
    par[1] = 'grupo';
    par[2] = 'codFabricante';
    par[3] = 'Localização';
    par[4] = 'chech Quant';
    par[5] = 'check Fora';
    */ 
    $par = explode("|", $letra);
    $iswhere = false;
    $fora = '';

    $sql  = "SELECT DISTINCT P.*, G.DESCRICAO AS NOMEGRUPO, 0 as ESTOQUE, 0 as RESERVA, ";
	$sql .= $par[2] == '' ? "P.CODFABRICANTE AS CODPRODUTONOTA, " : "'".$par[2]."' AS CODPRODUTONOTA, ";
    $sql .= "GROUP_CONCAT(R.PRODUTO_ID SEPARATOR '|') AS CODPRODUTOREPARO, ";
    $sql .= "M.DESCRICAO AS NOMEMARCA ";
   	$sql .= "FROM EST_PRODUTO P ";
    $sql .= "LEFT JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO) ";
    $sql .= "LEFT JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO=P.CODIGO) ";
    $sql .= "LEFT JOIN EST_PRODUTO_REPARO R ON (R.PRODUTO_REPARO = P.CODIGO) ";
    $sql .= "LEFT JOIN EST_MARCA M ON (M.MARCA=P.MARCA) ";
       
       
    if ($codigo != null) {
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($codigo) ? '':" $cond (p.codigo = $codigo)";
    } else if($par[2] != ''){
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and'; 
        $sql .= empty($par[2]) ? '':" $cond (p.codFabricante LIKE '".$par[2]."%') OR (E.CODEQUIVALENTE LIKE '".$par[2]."%') ";
        //condicao para verificar se o codigo é tipo numerico para pesquisar o codigo o nao
        if(is_numeric($par[2])){
            $sql .= " OR (P.CODIGO = '".$par[2]."') ";
        };
    }else{
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[0]) ? '':" $cond (P.descricao LIKE '%".$par[0]."%') ";
            
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[1]) ? '':" $cond (P.grupo = '".$par[1]."') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[3]) ? '':" $cond (P.localizacao = $par[3]) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= $par[5] != 'T' ? '' :" $cond (not isnull(P.dataforalinha)) ";

    }
    $sql .= "GROUP BY P.CODIGO, G.DESCRICAO, P.CODFABRICANTE ";
   	$sql .= "ORDER BY P.DESCRICAO; ";
    
    //echo strtoupper($sql)."<br>";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra

/**
 * <b> Funcao responsavel para pesquisa da combo na tela consulta preço </b>
 * @param $codigo codigo do item
 * @return array
 */
public function select_produto_letra_combo($codigo = null){
    $sql  = "SELECT P.CODIGO, P.DESCRICAO, E.CODEQUIVALENTE ";
    $sql .= "FROM EST_PRODUTO P ";
    $sql .= "LEFT JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO = P.CODIGO) ";
    $sql .= "WHERE P.CODIGO LIKE ('%$codigo%') OR P.CODFABRICANTE LIKE ('%$codigo%') OR E.CODEQUIVALENTE LIKE ('%$codigo%') OR P.DESCRICAO LIKE ('%$codigo%') ";
    $sql .= "GROUP BY P.CODIGO, P.DESCRICAO, E.CODEQUIVALENTE ";
    $sql .= "ORDER BY P.DESCRICAO";

    //echo strtoupper($sql)."<br>";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra

/**
* Funcao para Inclusao no banco
* @name incluiProduto
* @return string vazio se ocorrer com sucesso
*/
public function incluiProduto(){
	$banco = new c_banco;	
	$sql  = "INSERT INTO EST_PRODUTO (";

	$sql .= "DESCRICAO, DESCRICAODETALHADA, GRUPO,UNIDADE,UNIFRACIONADA,"; //L1
    $sql .= "FABRICANTE, CODFABRICANTE, CODIGOBARRAS, CODPRODUTOANVISA, LOCALIZACAO,"; //L2
    $sql .= "DATAFORALINHA, NCM, CEST, ORIGEM, TRIBICMS,"; // L3
    $sql .= "MOEDA, VENDA, CUSTOMEDIO, CUSTOCOMPRA, CUSTOREPOSICAO,"; //L4
	$sql .= "QUANTMINIMA, QUANTMAXIMA, OBS, PRECOPROMOCAO, TIPOPROMOCAO,"; //L5
    $sql .= "INICIOPROMOCAO, FIMPROMOCAO, QUANTLIMITE, PRECOPROMOCAO1, INICIOPROMOCAO1,"; //L6
    $sql .= "FIMPROMOCAO1, QUANTLIMITE1, PRECOBASE, PRECOINFORMADO, PERCCALCULO,"; //L7
	$sql .= "DATAULTIMACOMPRA, QUANTULTIMACOMPRA, NFULTIMACOMPRA, USERINSERT, DATEINSERT,"; //L8 
    $sql .= "PESO, PRECOMINIMO, ANP, MARCA) "; //L9
	$sql .= "VALUES ('";
    $sql .=	$this->getDesc(). "', '" .$this->getDescricaoDetalhada(). "', '" .$this->getGrupo(). "', '" .$this->getUni(). "', '" .$this->getUniFracionada(). "', '"; //L1
    $sql .= $this->getFabricante(). "', '" .$this->getCodFabricante(). "', '" .$this->getCodBarras(). "', '" .$this->getCodProdutoAnvisa(). "', '" .$this->getLocalizacao(). "', "; //L2
            $this->getDataForaLinha('B') == '' ? $sql .= "null, '" : $sql .= "'" .$this->getDataForaLinha('B'). "', '"; //L3
    $sql .= $this->getNcm(). "', '" .$this->getCest(). "', '" .$this->getOrigem(). "', '" .$this->getTribIcms(). "',"; //L3
    $sql .= $this->getMoeda(). ", " .$this->getVenda('B'). ", " .$this->getCustoMedio('B'). ", " .$this->getCustoCompra('B'). ", " .$this->getCustoReposicao('B'). ", "; //L4
    $sql .= $this->getQuantMinima('B'). ", " .$this->getQuantMaxima('B'). ", '" .$this->getObs(). "', " .$this->getPrecoPromocao('B'). ", '" .$this->getTipoPromocao(). "', "; //L5
            $this->getInicioPromocao('B') == NULL ? $sql .= "null, " : $sql .= "'".$this->getInicioPromocao('B')."', "; //L6
            $this->getFimPromocao('B') == NULL ? $sql .= "null, " : $sql .= "'".$this->getFimPromocao('B')."', "; //L6
    $sql .= $this->getQuantLimite('B'). ", " .$this->getPrecoPromocao1('B'). ", "; //L6
            $this->getInicioPromocao1('B') == NULL ? $sql .= "null, " : $sql .= "'".$this->getInicioPromocao1('B')."', "; //L6
            $this->getFimPromocao1('B') == NULL ? $sql .= "null, " : $sql .= "'".$this->getFimPromocao1('B')."', "; //L7
    $sql .= $this->getQuantLimite1(). ", '" .$this->getPrecoBase(). "', " .$this->getPrecoInformado('B'). ", " .$this->getPerCalculo('B'). ","; //L7
            $this->getDataUltimaCompra('B') == NULL ?  $sql .= "null, " : $sql .= "'" .$this->getDataUltimaCompra('B'). "', "; //L8
    $sql .= $this->getQuantUltimaCompra('B'). "," .$this->getNfUltimaCompra(). "," .$this->m_userid. ",'" .date("Y-m-d H:i:s"). "', "; //L8
    $sql .= $this->getPeso('B'). ", " .$this->getPrecoMinimo('B'). ", '"  .$this->getAnp(). "', '" .$this->getMarca()."');"; //L9

    //echo strtoupper($sql);

	$banco->exec_sql($sql);
    $lastReg = mysqli_insert_id($banco->id_connection);
    $status = $banco->result;
	$banco->close_connection();

    return $status;

} // fim incluiPRODUTO

/**
* Funcao para Alteracao no banco
* @name alteraProduto
* @return string vazio se ocorrer com sucesso
*/
public function alteraProduto(){
	$sql  = "UPDATE EST_PRODUTO ";
	$sql .= "SET " ;
    $sql .= "descricao = '".$this->getDesc()."', ";
    $sql .= "DESCRICAODETALHADA = '".$this->getDescricaoDetalhada()."', ";
	$sql .= "GRUPO = '".$this->getGrupo()."', ";
	$sql .= "UNIDADE = '".$this->getUni()."', ";
	$sql .= "UNIFRACIONADA = '".$this->getUniFracionada()."', ";
	$sql .= "FABRICANTE = '".$this->getFabricante()."', ";
	$sql .= "CODFABRICANTE = '".$this->getCodFabricante()."', ";
	$sql .= "CODIGOBARRAS = '".$this->getCodBarras()."', ";
	$sql .= "CODPRODUTOANVISA = '".$this->getCodProdutoAnvisa()."', ";
	$sql .= "LOCALIZACAO = '".$this->getLocalizacao()."', ";
	if ($this->getDataForaLinha('B')==NULL)
		$sql .= "DATAFORALINHA = null, ";
	else	
		$sql .= "DATAFORALINHA = '".$this->getDataForaLinha('B')."', ";

	$sql .= "NCM = '".$this->getNcm()."', ";
	$sql .= "CEST = '".$this->getCest()."', ";
	$sql .= "ORIGEM = '".$this->getOrigem()."', ";
	$sql .= "TRIBICMS = '".$this->getTribIcms()."', ";
	$sql .= "VENDA = '".$this->getVenda('B')."', ";
	$sql .= "CUSTOMEDIO = '".$this->getCustoMedio('B')."', ";
	$sql .= "CUSTOCOMPRA = '".$this->getCustoCompra('B')."', ";
	$sql .= "CUSTOREPOSICAO = '".$this->getCustoReposicao('B')."', ";
	$sql .= "QUANTMINIMA = ".$this->getQuantMinima('B').", ";
	$sql .= "QUANTMAXIMA = ".$this->getQuantMaxima('B').", ";
	$sql .= "PRECOPROMOCAO = '".$this->getPrecoPromocao('B')."', ";
	if ($this->getInicioPromocao('B')==NULL)
            $sql .= "INICIOPROMOCAO = null, ";
	else	
            $sql .= "INICIOPROMOCAO = '".$this->getInicioPromocao('B')."', ";
	if ($this->getFimPromocao('B')==NULL)
            $sql .= "FIMPROMOCAO = null, ";
	else	
            $sql .= "FIMPROMOCAO = '".$this->getFimPromocao('B')."', ";
	$sql .= "PRECOPROMOCAO1 = '".$this->getPrecoPromocao1('B')."', ";
	if ($this->getInicioPromocao1('B')==NULL)
            $sql .= "INICIOPROMOCAO1 = null, ";
	else	
            $sql .= "INICIOPROMOCAO1 = '".$this->getInicioPromocao1('B')."', ";
	if ($this->getFimPromocao1('B')==NULL)
            $sql .= "FIMPROMOCAO1 = null, ";
	else	
            $sql .= "FIMPROMOCAO1 = '".$this->getFimPromocao1('B')."', ";
	$sql .= "PRECOBASE = '".$this->getPrecoBase()."', ";
	$sql .= "TIPOPROMOCAO = '".$this->getTipoPromocao()."', ";
	$sql .= "PRECOINFORMADO = '".$this->getPrecoInformado('B')."', ";
	$sql .= "PERCCALCULO = '".$this->getPerCalculo('B')."', ";
	$sql .= "QUANTLIMITE = ".$this->getQuantLimite().", ";
	$sql .= "QUANTLIMITE1 = ".$this->getQuantLimite1().", ";

        if ($this->getDataUltimaCompra('B')==NULL)
            $sql .= "DATAULTIMACOMPRA = null, ";
	else	
            $sql .= "DATAULTIMACOMPRA = '".$this->getDataUltimaCompra('B')."', ";
	$sql .= "QUANTULTIMACOMPRA = ".$this->getQuantUltimaCompra('B').", ";
	$sql .= "NFULTIMACOMPRA = ".$this->getNfUltimaCompra().", ";
        
	$sql .= "OBS = '".$this->getObs()."', ";
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."', ";
	$sql .= "PESO = '".$this->getPeso('B')."', ";
	$sql .= "PRECOMINIMO = '".$this->getPrecoMinimo('B')."', ";
	$sql .= "ANP = '".$this->getAnp()."', ";
	$sql .= "MARCA = '".$this->getMarca()."' ";
	$sql .= "WHERE codigo = '".$this->getId()."';";
	$banco = new c_banco;
	$resProduto =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resProduto > 0){
        return '';
	}
	else{
        return 'Os dados do Item '.$this->getDesc().' n&atilde;o foi alterado!';
	}

}  // fim alteraPRODUTO


/**
* Funcao para Alteracao dados do produto conforme dados da nf de entrada
* @name alteraProdutoNFEntrada
* @return string vazio se ocorrer com sucesso
 * incluir Numero da Ultima NF entrada e saida no cadastro.
*/
public function alteraProdutoNFEntrada($altPrecos, $basePreco = null){
        
	$sql  = "UPDATE est_produto ";
	$sql .= "SET " ;
	$sql .= "FABRICANTE = '".$this->getFabricante()."', ";
	//$sql .= "CODFABRICANTE = '".$this->getCodFabricante()."', ";
	$sql .= "CODIGOBARRAS = '".$this->getCodBarras()."', ";
	$sql .= "NCM = '".$this->getNcm()."', ";
	$sql .= "CEST = '".$this->getCest()."', ";
	$sql .= "ORIGEM = '".$this->getOrigem()."', ";
	$sql .= "TRIBICMS = '".$this->getTribIcms()."', ";
	$sql .= "CUSTOCOMPRA = ".$this->getCustoCompra('B').", ";
	$sql .= "DATAULTIMACOMPRA = '".$this->getDataUltimaCompra('B')."', ";
	$sql .= "QUANTULTIMACOMPRA = ".$this->getQuantUltimaCompra('B').", ";
	$sql .= "NFULTIMACOMPRA = ".$this->getNfUltimaCompra('B').", ";
//	$sql .= "CUSTOMEDIO = '".$this->getCustoMedio('B')."', ";
//	$sql .= "CUSTOREPOSICAO = '".$this->getCustoReposicao('B')."', ";
        
        if ($altPrecos == S):
            $perCalculo = $this->getPerCalculo('B');
                
            if (isset($precoBase) == false) {
                $precoBase = $basePreco;
            } 
            switch ($precoBase){
                case 'I': // valor informado 
                    $venda = $this->getPrecoInformado('B');
                    // $base = " PRECOINFORMADO = '".$venda."', ";}
                    $base = '';
                    break;
                case 'C': // ultima compra
                    $venda = $this->getCustoCompra('B');
                    $base = " CUSTOCOMPRA = '".$venda."', ";
                    break;    
                case 'M': // custo medio
                    $venda = $this->getCustoMedio('B');
                    $base = " CUSTOMEDIO = '".$venda."', ";
                    break;
                case 'R': // custo reposicao
                    $venda = $this->getCustoReposicao('B');
                    $base = " CUSTOREPOSICAO = '".$venda."', ";
                    break;
            }
            if (doubleval($venda) > 0 ) {
                $sql .= $base;   
                $venda00 = $venda + ($venda * ($perCalculo/100));
                if (doubleval($venda00) > 0 ) {
                    //$this->setVenda($venda00);
                    //$sql .= "VENDA = ".$this->getVenda('B').", ";
                    $sql .= "VENDA = '".$venda00."', ";
                }
            }

            
        endif;

	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE codigo = '".$this->getId()."';";
	$banco = new c_banco;
	$resProduto =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resProduto > 0){
        if ($perCalculo <= 0) {
            return 'Item '.$this->getCodFabricante().' atualizado! Alíquota para atualização do preço de venda zerada.';
        } else {
            return '';
        }
	}
	else{
        if ($perCalculo <= 0) {
            return 'Os dados do Item '.$this->getCodFabricante().' n&atilde;o foi alterado! Alíquota para atualização do preço de venda zerada. /n';
        } else {
            return 'Os dados do Item '.$this->getDesc().' n&atilde;o foi alterado!';
        }
	}

}  // fim alteraPRODUTO

/**
* Funcao para Exclusao no banco
* @name excluiProduto
* @return string vazio se ocorrer com sucesso
*/
public function excluiProduto(){
	$sql  = "DELETE FROM est_produto ";
	$sql .= "WHERE codigo = '".$this->getId()."';";
	$banco = new c_banco;
	$resProduto =  $banco->exec_sql($sql);
	$banco->close_connection();
	if($resProduto > 0){
        return '';
	}
	else{
        return 'Os dados do Item '.$this->getId().' n&atilde;o foi excluido!';
	}
}  // fim excluiPRODUTO

public function updateCustoCompra($codigo, $custo){
	$sql  = "UPDATE est_produto ";
	$sql .= "SET " ;
	$sql .= "CUSTOCOMPRA = '".$custo."' ";
	$sql .= "WHERE codigo = '".$codigo."';";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return '';
}  // fim updateCustoCompra



public function select_vendas_produto($codigo = null){

    $sql   = "SELECT 'SAIDA' as TIPODOC, C.NOME, N.EMISSAO, N.PEDIDO as DOCTO, I.QTSOLICITADA AS QUANT, 'PED' AS TIPO, I.TOTAL , N.TOTAL AS TOTALNOTA ";
    $sql  .= "FROM EST_PRODUTO P ";
    $sql  .= "LEFT JOIN FAT_PEDIDO_ITEM I ON (I.ITEMESTOQUE=P.CODIGO) ";
    $sql  .= "LEFT JOIN FAT_PEDIDO N ON (N.PEDIDO=I.ID) ";
    $sql  .= "LEFT JOIN FIN_CLIENTE C ON (N.CLIENTE=C.CLIENTE)  ";
    $sql  .= "WHERE P.CODIGO = '".$codigo."' "; 
    $sql  .= "UNION ";
    $sql  .= "SELECT 'ENTRADA' as TIPODOC, C.NOME, N.EMISSAO, N.NUMERO as DOCTO, I.QUANT AS QUANT, 'NF' AS TIPO, I.TOTAL, N.TOTALNF AS TOTALNOTA ";
    $sql  .= "FROM EST_PRODUTO P ";
    $sql  .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO I ON (I.CODPRODUTO=P.CODIGO) ";
    $sql  .= "LEFT JOIN EST_NOTA_FISCAL N ON (N.ID=I.IDNF) ";
    $sql  .= "LEFT JOIN FIN_CLIENTE C ON (N.PESSOA=C.CLIENTE)  ";
    $sql  .= "WHERE P.CODIGO = '".$codigo."' "; 
    $sql  .= "ORDER BY 1 DESC";
   
   $banco = new c_banco;
   $banco->exec_sql($sql);
   $banco->close_connection();
   return $banco->resultado;
}

public function select_produto_tabela(){
    if ($this->getId() > 0 ) {
        $sql  = "SELECT I.*, T.NOME, T.VALIDADE ";
        $sql .= "FROM EST_TABELA_PRECO T ";
        $sql .= "LEFT JOIN EST_TABELA_PRECO_ITEM I ON (T.ID = I.ID) ";
        $sql .= "WHERE (I.CODIGO = ".$this->getId().") ";
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } else {
        return '';
    }
} //fim select_PRODUTO


public function select_importacao_tabela($codigo = null){

    $sql   = "SELECT C.NOME, T.CODORIGINAL, T.DESCRICAO, ";
    $sql  .= "T.PRECO, T.PRECOVENDA, T.MARCA, T.IPI, T.NCM, T.DATAIMPORTACAO ";
    $sql  .= "FROM EST_TABELA_FORNECEDOR T ";
    $sql  .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=T.FORNECEDOR) ";
    $sql  .= "WHERE T.CODORIGINAL = '".$codigo."' "; 
    $sql  .= "ORDER BY T.DATAIMPORTACAO DESC";
   
   $banco = new c_banco;
   $banco->exec_sql($sql);
   $banco->close_connection();
   return $banco->resultado;
}

public function select_equivalente_letra($letra = null, $codigo = null){
    $par = explode("|", $letra);
    $iswhere = false;
    $fora = '';
    
    $sql  = "SELECT DISTINCT P.CODIGO, P.CODFABRICANTE, E.CODEQUIVALENTE, ";
	$sql .= $par[2] == '' ? "P.CODFABRICANTE AS CODPRODUTONOTA, " : "'".$par[2]."' AS CODPRODUTONOTA, ";
    $sql .= "P.DESCRICAO, P.UNIDADE ";
    $sql .= "FROM EST_PRODUTO_EQUIVALENCIA E ";
    $sql .= "LEFT JOIN EST_PRODUTO P ON (E.IDPRODUTO=P.CODIGO) ";
   if ($codigo != null) {
        $sql .= "WHERE (p.codigo = '".$codigo."') ";
    } else
    if ($par[2] != ''){ // caso cod fabricante-> iginorar todos os filtros
        $sql .= "WHERE ((p.codFabricante LIKE '%".$par[2]."%') ";
        $sql .= "OR (E.CODEQUIVALENTE LIKE '%".$par[2]."%')) ";
        $iswhere = true;
    }else{
        if ($par[0]!= ''){
           if ($iswhere){
                $sql .= "and (p.descricao LIKE '%".$par[0]."%') ";
            }else{
                $sql .= "WHERE (p.descricao LIKE '%".$par[0]."%') ";
                $iswhere = true;
            } 
        }
    }
   $sql .= "ORDER BY p.descricao ";
    //echo strtoupper($sql)."<br>";
$banco = new c_banco;
$banco->exec_sql($sql);
$banco->close_connection();
return $banco->resultado;
}// fim select_equivalente_letra

    //imagem produto

    /**
    * Funcao para selecionar imagem do produto estoque
    * @name select_produto_imagem
    * @param INT $id
    * @return array com as imagens do produto selecionado
    */
    public function select_produto_imagem($id=null){

        if ($id==null):
            $id = $this->getId();
        endif;
        
        $sql  = "SELECT * ";
        $sql .= "FROM AMB_IMAGEM ";
        $sql .= "WHERE (ID_DOC = ".$id.") AND (MODULO = 'EST') ; ";
            //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

    } //fim select_conta_geral

    /**
    * Funcao para gravar imagem do produto estoque
    * @name gravaImagemProduto
    * @param String $mod
    * @param String $destaque
    * @return int id da imagem gravada
    */
    public function gravaImagemProduto($mod, $destaque){
        $sql  = "INSERT INTO AMB_IMAGEM (ID_DOC, DESTAQUE, MODULO, USERINSERT )";
        $sql .= "VALUES (".$this->getId().", '".$destaque."', '".$mod."', ".$this->m_userid.")";
            //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
            if ($banco->result):
                $lastReg = $banco->insertReg;
                $banco->close_connection();
                return $lastReg;
            else:
                $banco->close_connection();
                return '';
            endif;

    } //fim gravaImagemProduto

    /**
    * Funcao para excluir imagem do produto estoque
    * @name excluiImagemProduto
    * @param int $id
    * @return string vazio se ocorrer com sucesso
    */
    public function excluiImagemProduto($id){
        $sql  = "DELETE FROM AMB_IMAGEM ";
        $sql .= "WHERE (ID = ".$id.");";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0){
                return '';
        }
        else{
                return 'A Imagem não foi excluida!';
        }

    } //fim excluiImagemProduto

    /**
    * Funcao para por a imagem do produto estoque em destaque
    * @name destaqueImagemProduto
    * @param int $id
    * @param CHAR $destaque
    * @return string vazio se ocorrer com sucesso
    */
    public function destaqueImagemProduto($id, $destaque){
        if ($destaque == 'N'):
            $destaque ='S';
        else:
            $destaque ='N';
        endif;
        $sql  = "UPDATE AMB_IMAGEM ";
        $sql .= "SET  DESTAQUE = '".$destaque."' ";
        $sql .= "WHERE ID = ".$id.";";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0):
                return '';
        
        else:
                return 'A Imagem não entrou em destaque!';

        endif;
    } 
    // fim destaqueImagemProduto

    /**
    * Funcao para Não por a imagem do produto estoque em destaque
    * @name destaqueImagemProdutoNao
    * @return string vazio se ocorrer com sucesso
    */
    public function destaqueImagemProdutoNao(){

        $sql  = "UPDATE AMB_IMAGEM ";
        $sql .= "SET  DESTAQUE = 'N' ";
        $sql .= "WHERE ID_DOC = ".$this->getId()." AND MODULO = 'EST'";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0){
                return '';
        }
        else{
                return 'A Imagem não entrou em destaque!';
        }
    }
    // fim destaqueImagemProdutoNao

/**
 * Funcao pesquisa table produtos atraves do codigo fabricante
 * @name select_produto_cod_fabricante
 * @param INT $codFabricante Codigo da table produtos
 * @return ARRAY todos os campos da table
 */
public function select_produto_cod_fabricante($cod){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM est_produto ";
   	$sql .= "WHERE (codfabricante = '$cod') ";
	//echo strtoupper($sql)."<BR>";
	$banco = new c_banco();
	$banco->exec_sql($sql);

    if(is_array($banco->resultado)){
        return $banco->resultado;
    }else{
        $sql  = "SELECT P.CODIGO, P.DESCRICAO, P.UNIDADE, P.CODFABRICANTE, P.VENDA, E.CODEQUIVALENTE, 'EQUIVALENTE' AS ORIGEM FROM EST_PRODUTO P ";
   	    $sql .= "INNER JOIN EST_PRODUTO_EQUIVALENCIA E ON P.CODIGO = E.IDPRODUTO ";
   	    $sql .= "WHERE E.CODEQUIVALENTE = '$cod' ";
	    //echo strtoupper($sql)."<BR>";
	    $banco = new c_banco();
	    $banco->exec_sql($sql);

        return $banco->resultado;
    }
    $banco->close_connection();
} //fim select_produto_cod_fabricante

/**
 * Funcao pesquisa table FAT_PEDIDO atraves do codigo 
 * @name buscaPedido
 * @param INT $codProd Codigo da table FAT_PEDIDO
 * @return ARRAY todos os campos da table
 */
public function buscaPedidoPedido($idProd){
    $sql = "SELECT P.ID, P.EMISSAO, P.TOTAL, PI.ITEMESTOQUE, PI.ITEMFABRICANTE, C.NOME, PI.QTSOLICITADA FROM FAT_PEDIDO P ";
    $sql .= "INNER JOIN FAT_PEDIDO_ITEM PI ON P.PEDIDO = PI.ID ";
    $sql .= "INNER JOIN FIN_CLIENTE C ON P.CLIENTE = C.CLIENTE ";
    $sql .= "WHERE ((PI.ITEMESTOQUE = ".$idProd.") AND (P.SITUACAO = 6)) "; //6 = PEDIDO
    //echo strtoupper($sql)."<BR>";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

/**
 * Funcao pesquisa table FAT_PEDIDO atraves do codigo 
 * @name buscaPedido
 * @param INT $codProd Codigo da table FAT_PEDIDO
 * @return ARRAY todos os campos da table
 */
public function buscaPedidoCotacao($idProd){
    $sql = "SELECT P.ID, P.EMISSAO, P.TOTAL, PI.ITEMESTOQUE, PI.ITEMFABRICANTE, C.NOME, PI.QTSOLICITADA FROM FAT_PEDIDO P ";
    $sql .= "INNER JOIN FAT_PEDIDO_ITEM PI ON P.PEDIDO = PI.ID ";
    $sql .= "INNER JOIN FIN_CLIENTE C ON P.CLIENTE = C.CLIENTE ";
    $sql .= "WHERE ((PI.ITEMESTOQUE = ".$idProd.") AND (P.SITUACAO = 5)) "; //5 = COTACAO
    //echo strtoupper($sql)."<BR>";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

}	//	END OF THE CLASS

?>
