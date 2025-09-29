<?php

/**
 * @package   astec
 * @name      c_nota_fiscal_produto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_NOTA_FISCAL
Class c_nota_fiscal_produto extends c_user {

/**
 * TABLE NAME EST_NOTA_FISCAL_PRODUTO
 */
    
// Campos tabela - Objetos que existe na classe
    private $id                 = NULL; // integer not null
    private $idNf               = NULL; // integer not null
    private $codProduto         = NULL; // integer not null
    private $itemFabricante     = NULL; // char(25)  null --------------
    private $descricao          = NULL; // char(60) not null
    private $unidade            = NULL; // char(3) not null
    private $quant              = NULL; // numeric(9,4) not null
    private $unitario           = NULL; // numeric(14,6) not null
    private $desconto           = NULL; // numeric(14,6)
    private $total              = NULL; // numeric(12,2) not null
    private $origem             = NULL; // char(1) not null
    private $tribIcms           = NULL; // char (2) not null

    // ICMS e ICMS-ST
    private $ncm                = NULL; // char(15)
    private $cest               = NULL; // char(15) ++++++++++
    private $cfop               = NULL; // INTEGER
    private $aliqIcms           = NULL; // numeric(5,2)
    private $percReducaoBc      = NULL; // numeric(5,2) +++++++
    private $modBc              = NULL; // char(1)
    private $bcIcms             = NULL; // numeric(11,2)
    private $valorIcms          = NULL; // numeric(11,2)
    private $insideIpiBc        = NULL; // char(1)
    private $enqIpi             = NULL; // varchar(3)
    private $cstIpi             = NULL; // varchar(2)
    private $baseCalculoIpi              = NULL; // decimal(11,2)
    private $valorIpi           = NULL; // numeric(11,2)
    private $aliqIpi            = NULL; // numeric(5,2)
    private $percDiferido       = NULL; // decimal(5,2) +++++++
    private $valorIcmsDiferido  = NULL; // numeric(11,2) +++++++
    private $valorIcmsOperacao  = NULL; // numeric(11,2) +++++++
    private $modBcSt            = NULL; // char(1) +++++++
    private $percMvaSt          = NULL; // numeric(5,2) +++++++
    private $bcFcpSt            = NULL; // numeric(5,2) +++++++    
    private $aliqFcpSt          = NULL; // numeric(5,2) +++++++    
    private $valorFcpSt         = NULL; // numeric(5,2) +++++++        
    private $percReducaoBcSt    = NULL; // decimal(5,2) +++++++
    private $valorBcSt          = NULL; // numeric(11,2) +++++++
    private $AliqIcmsSt         = NULL; // numeric(5,2) +++++++
    private $valoricmsst        = NULL; // decimal (11,2)
    private $valorTotalTributos = NULL; // numeric(11,2) +++++++
    private $valorBaseCalculoStRetido = NULL; // numeric(11,2) +++++++
    private $valorIcmsStRetido  = NULL; // numeric(11,2) +++++++
    private $vBCSTRet           = NULL; // numeric(11,2)
    private $pST                = NULL; // numeric(5,2)
    private $vICMSSubstituto    = NULL; // numeric(11,2)
    private $pCredSN            = NULL; // numeric(11,2)
    private $vCredICMSSN        = NULL; // numeric(11,2)

    private $custoProduto       = NULL; // numeric(5,2)
    private $nrSerie            = NULL; // char(25)
    private $lote               = NULL; // varchar(15)
    private $dataFabricacao     = NULL; // varchar(4)
    private $dataValidade       = NULL; // varchar(4)
    private $dataGarantia       = NULL; // date
    private $ordem              = NULL; // integer not null
    private $projeto            = NULL; // varchar(20) not null
    private $dataConferencia    = NULL; // timestamp
    private $localizacao        = NULL; // varchar -------------
    private $numSerie           = NULL; // varchar -------------

    //PIS/COFINS
    private $cstPis             = NULL; // varchar -------------
    private $valorBcPis         = NULL; // varchar -------------
    private $aliqPis            = NULL; // varchar -------------
    private $valorPis           = NULL; // varchar -------------
    private $quantBcProdPis     = NULL; // varchar -------------
    private $valorAliqPis       = NULL; // varchar -------------
    private $cstCofins             = NULL; // varchar -------------
    private $valorBcCofins         = NULL; // varchar -------------
    private $aliqCofins            = NULL; // varchar -------------
    private $valorCofins           = NULL; // varchar -------------
    private $quantBcProdCofins     = NULL; // varchar -------------
    private $valorAliqCofins       = NULL; // varchar -------------
    private $cbenef                = NULL; // varchar -------------

    
    //DIFAL
    private $bcFcpUfDest        = NULL; // <vBCFCPUFDest> - Valor da BC FCP na UF de destino
    private $aliqFcpUfDest      = NULL; // <pFCPUFDest> - Percentual do ICMS relativo FCP na UF de destino 
    private $valorFcpUfDest     = NULL; // <vFCPUFDest> - Valor do FCP    
    
    private $bcIcmsUfDest       = NULL; // <vBCUFDest> - Valor da BC do ICMS na UF destino
    private $aliqIcmsUfDest     = NULL; // <pICMSUFDest> - Alíquota interna da UF de destino
    private $aliqIcmsInter      = NULL; // <pICMSInter> - Aliquota de ICMS interestadual
    private $aliqIcmsInterPart  = NULL; // <pICMSInterPart> Percentual provisório de partilha do ICMS Interestadual
    private $valorIcmsUfDest    = NULL; // <vICMSUFDest> Cálculo Difal  BC * (18-12=6)
    private $valorIcmsUFRemet   = NULL; // <vICMSUFRemet> Valor do ICMS Interestadual para a UF do remetente
    
    private $despAcessorias = NULL;
    private $frete = NULL;
    private $codigonota = NULL;
    private $csosn = NULL;

    function __construct(){
        // Cria uma instancia variaveis de sessao
        //session_start();
        c_user::from_array($_SESSION['user_array']);
    }

    /* METODOS DE SETS E GETS */
    public function setId($id) { $this->id = $id; }

    public function getId() { return $this->id; }

    public function setIdNf($id) { $this->idNf = $id; }

    public function getIdNf() { return $this->idNf; }

    public function setCodProduto($codProduto) { $this->codProduto = $codProduto; }

    public function getCodProduto() { return $this->codProduto; }

    public function setDescricao($descricao) { $this->descricao = strtoupper($descricao); }

    public function getDescricao() { return $this->descricao; }

    public function setUnidade($unidade) { $this->unidade = strtoupper($unidade); }

    public function getUnidade() { return $this->unidade; }
    
    public function setUnifrac($unifrac) { $this->unifrac = strtoupper($unifrac); }
    public function getUnifrac() { return $this->unifrac; }

    public function setQuant($quant, $format=false) {

        $this->quant = (($quant == "0,00") or ($quant == "0.00"))  ? c_tools::stringToDouble($quant) : $quant;

        if ($format){
           $this->quant = number_format($this->quant, 4, ',', '.');
        }
    }

    public function getQuant($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->quant = $this->quant ?? 0;

                return number_format($this->quant, 2, ',', '.');
            break;
            case 'B':

                if ($this->quant !== null) {

                    $num = str_replace('.', '', $this->quant);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->quant;
        }
    }

    public function setUnitario($unitario, $format=false) {

        $this->unitario = (($unitario == "0,00") or ($unitario == "0.00"))  ? c_tools::stringToDouble($unitario) : $unitario;

        if ($format){
            $this->unitario = number_format($this->unitario, 10, ',', '.');
        }
    }

    public function getUnitario($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->unitario = $this->unitario ?? 0;
                
                return number_format((double) $this->unitario, 10, ',', '.');
            break;
            case 'B':

                if ($this->unitario != null) {

                    $num = str_replace('.', '', $this->unitario);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->unitario;
        }
    }

    public function setDesconto($desconto, $format = false) {

        $this->desconto = (($desconto == "0,00") or ($desconto == "0.00")) ? c_tools::stringToDouble($desconto) : strtoupper($desconto);

        if ($format){
            $this->desconto=number_format($this->desconto, 2, ',', '.');
        }
    }

    public function getDesconto($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->desconto = $this->desconto ?? 0;
                
                return number_format((double) $this->desconto, 2, ',', '.');
                break;
            case 'B':

                if ($this->desconto != null) {

                    $num = str_replace('.', '', $this->desconto);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
                break;
            default:
                return $this->desconto;
        }
    }

    public function setTotal($total, $format=false) {

        $this->total = (($total == "0,00") or ($total == "0.00")) ? c_tools::stringToDouble($total) : $total;

        if ($format){
                $this->total = number_format($this->total, 2, ',', '.');
        }
    }

    public function getTotal($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->total = $this->total ?? 0;

                return number_format((double) $this->total, 2, ',', '.');
            break;
            case 'B':

                if ($this->total != null) {
                    
                    $num = str_replace('.', '', $this->total);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->total;
        }
    }

    public function setOrigem($origem) { $this->origem = strtoupper($origem); }

    public function getOrigem() { return $this->origem; }

    public function setTribIcms($tribIcms) { $this->tribIcms = strtoupper($tribIcms); }

    public function getTribIcms() { return $this->tribIcms; }

    public function setNcm($ncm) { $this->ncm = strtoupper($ncm); }

    public function getNcm($format = null) { return $this->ncm;  }

    public function setCest($cest) { $this->cest = strtoupper($cest); }

    public function getCest() { return $this->cest;  }

    public function setCfop($cfop) { $this->cfop = $cfop; }

    public function getCfop() { return $this->cfop; }

    public function setModBc($modBc) { $this->modBc = strtoupper($modBc); }

    public function getModBc() { return $this->modBc; }

    public function setAliqIcms($aliqIcms, $format=false) {

        $this->aliqIcms = (($aliqIcms == "0,00") or ($aliqIcms == "0.00")) ? c_tools::stringToDouble($aliqIcms) : $aliqIcms;

        if ($format){
            $this->aliqIcms = number_format($this->aliqIcms, 2, ',', '.');
        }
    }

    public function getAliqIcms($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqIcms = $this->aliqIcms ?? 0;

                return number_format((double) $this->aliqIcms, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIcms != null) {

                    $num = str_replace('.', '', $this->aliqIcms);
                    $num = str_replace(',', '.', $num);
                    return $num;

                } else {
                    return 0;
                }
            break;
            default:
                return $this->aliqIcms;
        }
    }

    public function setPercReducaoBc($percReducaoBc, $format=false) {

        $this->percReducaoBc = (($percReducaoBc == "0,00") or ($percReducaoBc == "0.00")) ? c_tools::stringToDouble($percReducaoBc) : $percReducaoBc;

        if ($format){
            $this->percReducaoBc = number_format($this->percReducaoBc, 2, ',', '.');
        } 
    }

    public function getPercReducaoBc($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->percReducaoBc = $this->percReducaoBc ?? 0;

                return number_format($this->percReducaoBc, 2, ',', '.');
            break;
            case 'B':

                if ($this->percReducaoBc != null) {

                    $num = str_replace('.', '', $this->percReducaoBc);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->percReducaoBc;
        }
    }

    public function setBcIcms($bcIcms, $format=false) {

        $this->bcIcms = (($bcIcms == "0,00") or ($bcIcms == "0.00")) ? c_tools::stringToDouble($bcIcms) : $bcIcms;

        if ($format){
            $this->bcIcms = number_format($this->bcIcms, 2, ',', '.');
        } 
    }

    public function getBcIcms($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->bcIcms = $this->bcIcms ?? 0;

                return number_format((double) $this->bcIcms, 2, ',', '.');
            break;
            case 'B':

                if ($this->bcIcms != null) {

                    $num = str_replace('.', '', $this->bcIcms);
                    $num = str_replace(',', '.', $num);
                
                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->bcIcms;
        }
    }

    public function setValorIcms($valorIcms, $format=false) {

        $this->valorIcms = (($valorIcms == "0,00") or ($valorIcms == "0.00")) ? c_tools::stringToDouble($valorIcms) : $valorIcms;

        if ($format){
            $this->valorIcms = number_format($this->valorIcms, 2, ',', '.');
        } 
    }

    public function getValorIcms($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcms = $this->valorIcms ?? 0;

                return number_format((double) $this->valorIcms, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcms != null) {

                    $num = str_replace('.', '', $this->valorIcms);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorIcms;
        }
    }

    public function setInsideIpiBc($insideIpiBc){ $this->insideIpiBc = strtoupper($insideIpiBc); }

    public function getInsideIpiBc(){ return $this->insideIpiBc; }
    
    public function setBaseCalculoIpi($baseCalculoIpi, $format=false) {

        $this->baseCalculoIpi = (($baseCalculoIpi == "0,00") or ($baseCalculoIpi == "0.00")) ? c_tools::stringToDouble($baseCalculoIpi) : $baseCalculoIpi;

        if ($format){
            $this->baseCalculoIpi = number_format($this->baseCalculoIpi, 2, ',', '.');
        }   
    }

    public function getBaseCalculoIpi($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->baseCalculoIpi = $this->baseCalculoIpi ?? 0;

                return number_format((double) $this->baseCalculoIpi, 2, ',', '.');
            break;
            case 'B':

                if ($this->baseCalculoIpi != null) {

                    $num = str_replace('.', '', $this->baseCalculoIpi);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->baseCalculoIpi;
        }
    }
    
    public function setValorIpi($valorIpi, $format=false) {

        $this->valorIpi = (($valorIpi == "0,00") or ($valorIpi == "0.00")) ? c_tools::stringToDouble($valorIpi) : $valorIpi;

        if ($format){
            $this->valorIpi = number_format($this->valorIpi, 2, ',', '.');
        }
    }

    public function getValorIpi($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIpi = $this->valorIpi ?? 0;

                return number_format((double) $this->valorIpi, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIpi != null) {

                    $num = str_replace('.', '', $this->valorIpi);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorIpi;
        }
    }

    public function setAliqIpi($aliqIpi, $format=false) {

        $this->aliqIpi = (($aliqIpi == "0,00") or ($aliqIpi == "0.00")) ? c_tools::stringToDouble($aliqIpi) : $aliqIpi;

        if ($format){
            $this->aliqIpi = number_format($this->aliqIpi, 2, ',', '.');
        } 
    }

    public function getAliqIpi($format = null) 
    {
        switch ($format) {
            case 'F':
                return number_format((double) $this->aliqIpi, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIpi != null) {

                    $num = str_replace('.', '', $this->aliqIpi);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqIpi;
        }
    }

    public function setPercDiferido($percDiferido, $format=false) {

        // Check if is string, if yes, convert to double
        $this->percDiferido = (($percDiferido == "0,00") or ($percDiferido == "0.00")) ? c_tools::stringToDouble($percDiferido) : $percDiferido;

        if ($format){
            $this->percDiferido = number_format($this->percDiferido, 2, ',', '.');
        }
    }

    public function getPercDiferido($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->percDiferido = $this->percDiferido ?? 0;

                return number_format($this->percDiferido, 2, ',', '.');
            break;
            case 'B':

                if ($this->percDiferido != null) {

                    $num = str_replace('.', '', $this->percDiferido);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->percDiferido;
        }
    }

    public function setValorIcmsDiferido($valorIcmsDiferido , $format=false) {

        // Check if is string, if yes, convert to double
        $this->valorIcmsDiferido = (($valorIcmsDiferido == "0,00") or ($valorIcmsDiferido == "0.00")) ? c_tools::stringToDouble($valorIcmsDiferido) : $valorIcmsDiferido;

        if ($format){
            $this->valorIcmsDiferido = number_format($this->valorIcmsDiferido, 4, ',', '.');
        }
    }

    public function getValorIcmsDiferido($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcmsDiferido = $this->valorIcmsDiferido ?? 0;

                return number_format($this->valorIcmsDiferido, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcmsDiferido != null) {

                    $num = str_replace('.', '', $this->valorIcmsDiferido);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorIcmsDiferido;
        }
    }
    
    public function setValorIcmsOperacao($valorIcmsOperacao, $format=false) {

        $this->valorIcmsOperacao = (($valorIcmsOperacao == "0,00") or ($valorIcmsOperacao == "0.00")) ? c_tools::stringToDouble($valorIcmsOperacao) : $valorIcmsOperacao;

        if ($format){
            $this->valorIcmsOperacao = number_format($this->valorIcmsOperacao, 4, ',', '.');
        } 
    }

    public function getValorIcmsOperacao($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcmsOperacao = $this->valorIcmsOperacao ?? 0;

                return number_format($this->valorIcmsOperacao, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcmsOperacao != null) {
                    $num = str_replace('.', '', $this->valorIcmsOperacao);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorIcmsOperacao;
        }
    }
    
    public function setModBcSt($modBcSt) { $this->modBcSt = strtoupper($modBcSt); }

    public function getModBcSt() { return $this->modBcSt; }

    public function setPercMvaSt($percMvaSt, $format=false) {

        $this->percMvaSt = (($percMvaSt == "0,00") or ($percMvaSt == "0.00")) ? c_tools::stringToDouble($percMvaSt) : $percMvaSt;

        if ($format){
            $this->percMvaSt = number_format($this->percMvaSt, 4, ',', '.');
        }
    }

    public function getPercMvaSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->percMvaSt = $this->percMvaSt ?? 0;

                return number_format($this->percMvaSt, 2, ',', '.');
            break;
            case 'B':
                if ($this->percMvaSt != null) {

                    $num = str_replace('.', '', $this->percMvaSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->percMvaSt;
        }
    }

    public function setBcFcpSt($bcFcpSt, $format=false) {

        $this->bcFcpSt = (($bcFcpSt == "0,00") or ($bcFcpSt == "0.00")) ? c_tools::stringToDouble($bcFcpSt) : $bcFcpSt;

        if ($format){
            $this->bcFcpSt = number_format($this->bcFcpSt, 4, ',', '.');
        }
    }

    public function getBcFcpSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->bcFcpSt = $this->bcFcpSt ?? 0;

                return number_format($this->bcFcpSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->bcFcpSt != null) {

                    $num = str_replace('.', '', $this->bcFcpSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->bcFcpSt;
        }
    }   
    
    public function setAliqFcpSt($aliqFcpSt, $format=false) {

        $this->aliqFcpSt = (($aliqFcpSt == "0,00") or ($aliqFcpSt == "0.00")) ? c_tools::stringToDouble($aliqFcpSt) : $aliqFcpSt;

        if ($format){
            $this->aliqFcpSt = number_format($this->aliqFcpSt, 4, ',', '.');
        }
    }

    public function getAliqFcpSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqFcpSt = $this->aliqFcpSt ?? 0;

                return number_format($this->aliqFcpSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqFcpSt != null) {

                    $num = str_replace('.', '', $this->aliqFcpSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->aliqFcpSt;
        }
    } 
    
    public function setValorFcpSt($valorFcpSt, $format=false) {

        $this->valorFcpSt = (($valorFcpSt == "0,00") or ($valorFcpSt == "0.00")) ? c_tools::stringToDouble($valorFcpSt) : $valorFcpSt;

        if ($format){
            $this->valorFcpSt = number_format($this->valorFcpSt, 4, ',', '.');
        }
    }

    public function getValorFcpSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorFcpSt = $this->valorFcpSt ?? 0;

                return number_format($this->valorFcpSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorFcpSt != null) {

                    $num = str_replace('.', '', $this->valorFcpSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorFcpSt;
        }
    }     
    
    public function setPercReducaoBcSt($percReducaoBcSt, $format=false) {

        $this->percReducaoBcSt = (($percReducaoBcSt == "0,00") or ($percReducaoBcSt == "0.00"))  ? c_tools::stringToDouble($percReducaoBcSt) : $percReducaoBcSt;

        if ($format){
            $this->percReducaoBcSt = number_format($this->percReducaoBcSt, 4, ',', '.');
        }
    }

    public function getPercReducaoBcSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->percReducaoBcSt = $this->percReducaoBcSt ?? 0;

                return number_format($this->percReducaoBcSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->percReducaoBcSt != null) {

                    $num = str_replace('.', '', $this->percReducaoBcSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->percReducaoBcSt;
        }
    }

    public function setValorBcSt($valorBcSt, $format=false) {

        $this->valorBcSt = (($valorBcSt == "0,00") or ($valorBcSt == "0.00")) ? c_tools::stringToDouble($valorBcSt) : $valorBcSt;

        if ($format){
            $this->valorBcSt = number_format($this->valorBcSt, 2, ',', '.');
        }
    }

    public function getValorBcSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorBcSt =  $this->valorBcSt ?? 0;

                return number_format($this->valorBcSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorBcSt != null) {

                    $num = str_replace('.', '', $this->valorBcSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorBcSt;
        }
    }

    public function setAliqIcmsSt($aliqIcmsSt, $format=false) {

        $this->aliqIcmsSt = (($aliqIcmsSt == "0,00") or ($aliqIcmsSt == "0.00")) ? c_tools::stringToDouble($aliqIcmsSt) : $aliqIcmsSt;

        if ($format){
            $this->aliqIcmsSt = number_format($this->aliqIcmsSt, 2, ',', '.');
        }
    }

    public function getAliqIcmsSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqIcmsSt = $this->aliqIcmsSt ?? 0;

                return number_format($this->aliqIcmsSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIcmsSt != null) {

                    $num = str_replace('.', '', $this->aliqIcmsSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqIcmsSt;
        }
    }

    public function setValorIcmsSt($valorIcmsSt, $format=false) {

        $this->valorIcmsSt = (($valorIcmsSt == "0,00") or ($valorIcmsSt == "0.00")) ? c_tools::stringToDouble($valorIcmsSt) : $valorIcmsSt;

        if ($format){
            $this->valorIcmsSt = number_format($this->valorIcmsSt, 2, ',', '.');
        }
    }

    public function getValorIcmsSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcmsSt = $this->valorIcmsSt ?? 0;

                return number_format($this->valorIcmsSt, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcmsSt != null) {

                    $num = str_replace('.', '', $this->valorIcmsSt);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->valorIcmsSt;
        }
    }
    
    public function setValorTotalTributos($valorTotalTributos, $format=false) {

        $this->valorTotalTributos = (($valorTotalTributos == "0,00") or ($valorTotalTributos == "0.00")) ? c_tools::stringToDouble($valorTotalTributos) : $valorTotalTributos;

        if ($format){
            $this->valorTotalTributos = number_format($this->valorTotalTributos, 2, ',', '.');
        }
    }

    public function getValorTotalTributos($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->valorTotalTributos = $this->valorTotalTributos ?? 0;

            return number_format($this->valorTotalTributos, 2, ',', '.');
        } else {

            if ($this->valorTotalTributos != null) {

                $num = str_replace('.', '', $this->valorTotalTributos);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setValorBaseCalculoStRetido($valorBaseCalculoStRetido, $format=false) {

        $this->valorBaseCalculoStRetido = (($valorBaseCalculoStRetido == "0,00") or ($valorBaseCalculoStRetido == "0.00")) ? c_tools::stringToDouble($valorBaseCalculoStRetido) : $valorBaseCalculoStRetido;

        if ($format){
            $this->valorBaseCalculoStRetido = number_format($this->valorBaseCalculoStRetido, 2, ',', '.');
        }
    }

    public function getValorBaseCalculoStRetido($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->valorBaseCalculoStRetido = $this->valorBaseCalculoStRetido ?? 0;
            
            return number_format($this->valorBaseCalculoStRetido, 2, ',', '.');
        } else {

            if ($this->valorBaseCalculoStRetido != null) {

                $num = str_replace('.', '', $this->valorBaseCalculoStRetido);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setPSt($pST, $format=false) {

        $this->pST = (($pST == "0,00") or ($pST == "0.00")) ? c_tools::stringToDouble($pST) : $pST;

        if ($format){
            $this->aliqIcms = number_format($this->pST, 2, ',', '.');
        }
    }

    public function getPSt($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->pST = $this->pST ?? 0;

                return number_format((double) $this->pST, 2, ',', '.');
            break;
            case 'B':

                if ($this->pST != null) {

                    $num = str_replace('.', '', $this->pST);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->pST;
        }
    }

    public function setValorIcmsSubstituto($valorIcmsSubstituto, $format=false) {

        $this->valorIcmsSubstituto = (($valorIcmsSubstituto == "0,00") or ($valorIcmsSubstituto == "0.00")) ? c_tools::stringToDouble($valorIcmsSubstituto) : $valorIcmsSubstituto;

        if ($format){
            $this->valorIcmsSubstituto = number_format($this->valorIcmsSubstituto, 2, ',', '.');
        }
    }
    
    public function getValorIcmsSubstituto($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->valorIcmsSubstituto = $this->valorIcmsSubstituto ?? 0;

            return number_format($this->valorIcmsSubstituto, 2, ',', '.');
        } else {

            if ($this->valorIcmsSubstituto != null) {

                $num = str_replace('.', '', $this->valorIcmsSubstituto);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setValorIcmsStRetido($valorIcmsStRetido, $format=false) { 

        $this->valorIcmsStRetido = (($valorIcmsStRetido == "0,00") or ($valorIcmsStRetido == "0.00")) ? c_tools::stringToDouble($valorIcmsStRetido) : $valorIcmsStRetido;

        if ($format){
            $this->valorIcmsStRetido = number_format($this->valorIcmsStRetido, 2, ',', '.');
        }
    }

    public function getValorIcmsStRetido($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->valorIcmsStRetido = $this->valorIcmsStRetido ?? 0;

            return number_format($this->valorIcmsStRetido, 2, ',', '.');
        } else {

            if ($this->valorIcmsStRetido != null) {

                $num = str_replace('.', '', $this->valorIcmsStRetido);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setPCredSN($pCredSN, $format=false) {

        $this->pCredSN = (($pCredSN == "0,00") or ($pCredSN == "0.00")) ? c_tools::stringToDouble($pCredSN) : $pCredSN;

        if ($format){
            $this->pCredSN = number_format($this->pCredSN, 2, ',', '.');
        }  
    }

    public function getPCredSN($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->pCredSN = $this->pCredSN ?? 0;
             
            return number_format($this->pCredSN, 2, ',', '.');
        } else {

            if ($this->pCredSN != null) {

                $num = str_replace('.', '', $this->pCredSN);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }


    public function setVCredICMSSN($vCredICMSSN, $format=false) {

        $this->vCredICMSSN = (($vCredICMSSN == "0,00") or ($vCredICMSSN == "0.00")) ? c_tools::stringToDouble($vCredICMSSN) : $vCredICMSSN;

        if ($format){
            $this->vCredICMSSN = number_format($this->vCredICMSSN, 2, ',', '.');
        }
        
    }

    public function getVCredICMSSN($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->vCredICMSSN = $this->vCredICMSSN ?? 0;

            return number_format($this->vCredICMSSN, 2, ',', '.');
        } else {
            
            if ($this->vCredICMSSN != null) {

                $num = str_replace('.', '', $this->vCredICMSSN);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }
    
    public function setCustoProduto($custoProduto, $format=false) {  

        $this->custoProduto = (($custoProduto == "0,00") or ($custoProduto == "0.00")) ? c_tools::stringToDouble($custoProduto) : $custoProduto;

        if ($format){
            $this->custoProduto = number_format($this->custoProduto, 2, ',', '.');
        }
    }

    public function getCustoProduto($format = null) 
    {
        if ($format == 'F') {
            // Operador de coalescencia versao php.8.3
            $this->custoProduto = $this->custoProduto ?? 0;

            return number_format($this->custoProduto, 2, ',', '.');
        } else {

            if ($this->custoProduto != null) {

                $num = str_replace('.', '', $this->custoProduto);
                $num = str_replace(',', '.', $num);

                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setNrSerie($nrserie) { $this->nrserie = strtoupper($nrserie); }

    public function getNrSerie() { return $this->nrserie; }

    public function setLote($lote) { $this->lote = strtoupper($lote); }

    public function getLote() { return strtoupper($this->lote); }

    public function setDataFabricacao($dataFabricacao) { $this->dataFabricacao = strtoupper($dataFabricacao); }

    public function getDataFabricacao($format = null) 
    { 
        if (($this->dataFabricacao != '') && ($this->dataFabricacao != '0000-00-00 00:00:00')) {

            $this->dataFabricacao = strtr($this->dataFabricacao, "/", "-");

            switch ($format) {
                case 'F':

                    return date('d/m/Y H:i:s', strtotime($this->dataFabricacao));
                break;
                case 'B':

                    return c_date::convertDateBd($this->dataFabricacao, $this->m_banco);
                break;
                default:
                    return $this->dataFabricacao;
            }
        } else {
            return '';
        }
    }

    public function setDataValidade($dataValidade) { $this->dataValidade = strtoupper($dataValidade); }

    public function getDataValidade($format = null) 
    {
         if (($this->dataValidade != '') && ($this->dataValidade != '0000-00-00 00:00:00')) {

            $this->dataValidade = strtr($this->dataValidade, "/", "-");

            switch ($format) {
                case 'F':

                    return date('d/m/Y H:i:s', strtotime($this->dataValidade));
                break;
                case 'B':

                    return c_date::convertDateBd($this->dataValidade, $this->m_banco);
                break;
                default:
                    return $this->dataValidade;
            }
        } else
            return '';
        
    }

    public function setDataGarantia($dataGarantia) { $this->dataGarantia = strtoupper($dataGarantia); }

    public function getDataGarantia($format = null) 
    {
         if (($this->dataGarantia != '') && ($this->dataGarantia != '0000-00-00 00:00:00')) {

            $this->dataGarantia = strtr($this->dataGarantia, "/", "-");

            switch ($format) {
                case 'F':

                    return date('d/m/Y H:i:s', strtotime($this->dataGarantia));
                break;
                case 'B':

                    return c_date::convertDateBd($this->dataGarantia, $this->m_banco);
                break;
                default:
                    return $this->dataGarantia;
            }

        } else
            return '';
    }

    public function setOrdem($ordem) { $this->ordem = $ordem; }

    public function getOrdem() { return $this->ordem; }

    public function setProjeto($projeto) { $this->projeto = $projeto; }

    public function getProjeto() { return $this->projeto; }

    public function setDataConferencia($dataConferencia) { $this->dataConferencia = $dataConferencia; }

    public function getDataConferencia($format = NULL) 
    {
        if (($this->dataConferencia != '') && ($this->dataConferencia != '0000-00-00 00:00:00')) {

            $this->dataConferencia = strtr($this->dataConferencia, "/", "-");

            switch ($format) {
                case 'F':

                    return date('d/m/Y H:i:s', strtotime($this->dataConferencia));

                break;
                case 'B':

                    return c_date::convertDateBd($this->dataConferencia, $this->m_banco);

                break;
                default:
                    return $this->dataConferencia;
            }

        } else {
            return '';
        }
    }

    public function setCstPis($cstPis) { $this->cstPis = $cstPis; }

    public function getCstPis($format = null) { return $this->cstPis; }

    public function setBcPis($valorBcPis, $format=false) {

        $this->valorBcPis = (($valorBcPis == "0,00") or ($valorBcPis == "0.00")) ? c_tools::stringToDouble($valorBcPis) : $valorBcPis;

        if ($format){
            $this->valorBcPis = number_format($this->valorBcPis, 2, ',', '.');
        } 
    }

    public function getBcPis($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorBcPis = $this->valorBcPis ?? 0;

                return number_format((double) $this->valorBcPis, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorBcPis != null) {

                    $num = str_replace('.', '', $this->valorBcPis);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorBcPis;
        }
    }

    public function setAliqPis($aliqPis, $format=false) {

        $this->aliqPis = (($aliqPis == "0,00") or ($aliqPis == "0.00")) ? c_tools::stringToDouble($aliqPis) : $aliqPis;

        if ($format){
            $this->aliqPis = number_format($this->aliqPis, 2, ',', '.');
        }  
    }

    public function getAliqPis($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqPis = $this->aliqPis ?? 0;

                return number_format((double) $this->aliqPis, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqPis != null) {

                    $num = str_replace('.', '', $this->aliqPis);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqPis;
        }
    }
    
    public function setValorPis($valorPis, $format=false) {

        $this->valorPis = (($valorPis == "0,00") or ($valorPis == "0.00")) ? c_tools::stringToDouble($valorPis) : $valorPis;

        if ($format){
            $this->valorPis = number_format($this->valorPis, 2, ',', '.');
        }  
    }

    public function getValorPis($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorPis = $this->valorPis ?? 0;

                return number_format((double) $this->valorPis, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorPis != null) {

                    $num = str_replace('.', '', $this->valorPis);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorPis;
        }
    }
    
    public function setQuantBcProdPis($quantBcProdPis, $format=false) {

        $this->quantBcProdPis = (($quantBcProdPis == "0,00") or ($quantBcProdPis == "0.00")) ? c_tools::stringToDouble($quantBcProdPis) : $quantBcProdPis;

        if ($format){
            $this->quantBcProdPis = number_format($this->quantBcProdPis, 2, ',', '.');
        }  
    }

    public function getQuantBcProdPis($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->quantBcProdPis = $this->quantBcProdPis ?? 0;

                return number_format((double) $this->quantBcProdPis, 2, ',', '.');
            break;
            case 'B':

                if ($this->quantBcProdPis != null) {

                    $num = str_replace('.', '', $this->quantBcProdPis);
                    $num = str_replace(',', '.', $num);
                    
                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->quantBcProdPis;
        }
    }

    public function setCstCofins($cstCofins) { $this->cstCofins = $cstCofins; }

    public function getCstCofins($format = null) { return $this->cstCofins; }

    public function setBcCofins($valorBcCofins, $format=false) {

        $this->valorBcCofins = (($valorBcCofins == "0,00") or ($valorBcCofins == "0.00")) ? c_tools::stringToDouble($valorBcCofins) : $valorBcCofins;

        if ($format){
            $this->valorBcCofins = number_format($this->valorBcCofins, 2, ',', '.');
        }
    }

    public function getBcCofins($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorBcCofins = $this->valorBcCofins ?? 0;

                return number_format((double) $this->valorBcCofins, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorBcCofins != null) {

                    $num = str_replace('.', '', $this->valorBcCofins);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorBcCofins;
        }
    }

    public function setAliqCofins($aliqCofins, $format=false) { 

        $this->aliqCofins = (($aliqCofins == "0,00") or ($aliqCofins == "0.00")) ? c_tools::stringToDouble($aliqCofins) : $aliqCofins;

        if ($format){
            $this->aliqCofins = number_format($this->aliqCofins, 2, ',', '.');
        }
    }

    public function getAliqCofins($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqCofins = $this->aliqCofins ?? 0;

                return number_format((double) $this->aliqCofins, 2, ',', '.');
            break;
            case 'B':
                if ($this->aliqCofins != null) {

                    $num = str_replace('.', '', $this->aliqCofins);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqCofins;
        }
    }
    
    public function setValorCofins($valorCofins, $format=false) { 

        $this->valorCofins = (($valorCofins == "0,00") or ($valorCofins == "0.00")) ? c_tools::stringToDouble($valorCofins) : $valorCofins;

        if ($format){
            $this->valorCofins = number_format($this->valorCofins, 2, ',', '.');
        }
    }

    public function setVBCSTRet($VBCSTRet, $format=false) { 

        $this->VBCSTRet = (($VBCSTRet == "0,00") or ($VBCSTRet == "0.00")) ? c_tools::stringToDouble($VBCSTRet) : $VBCSTRet;

        if ($format){
            $this->VBCSTRet = number_format($this->VBCSTRet, 2, ',', '.');
        }
    }

    public function setVICMSSubstituto($VICMSSubstituto, $format=false) { 

        $this->VICMSSubstituto = (($VICMSSubstituto == "0,00") or ($VICMSSubstituto == "0.00")) ? c_tools::stringToDouble($VICMSSubstituto) : $VICMSSubstituto;

        if ($format){
            $this->VICMSSubstituto = number_format($this->VICMSSubstituto, 2, ',', '.');
        }
    }
    
    public function setVICMSSTRet($VICMSSTRet, $format=false) { 

        $this->VICMSSTRet = (($VICMSSTRet == "0,00") or ($VICMSSTRet == "0.00")) ? c_tools::stringToDouble($VICMSSTRet) : $VICMSSTRet;

        if ($format){
            $this->VICMSSTRet = number_format($this->VICMSSTRet, 2, ',', '.');
        }
    }
    
    

    
    public function getValorCofins($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorCofins = $this->valorCofins ?? 0;

                return number_format((double) $this->valorCofins, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorCofins != null) {

                    $num = str_replace('.', '', $this->valorCofins);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorCofins;
        }
    }
    
    public function setQuantBcProdCofins($quantBcProdCofins, $format=false) {

        $this->quantBcProdCofins = (($quantBcProdCofins == "0,00") or ($quantBcProdCofins == "0.00"))  ? c_tools::stringToDouble($quantBcProdCofins) : $quantBcProdCofins;

        if ($format){
            $this->quantBcProdCofins = number_format($this->quantBcProdCofins, 2, ',', '.');
        }
    }

    public function getQuantBcProdCofins($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->quantBcProdCofins = $this->quantBcProdCofins ?? 0;

                return number_format((double) $this->quantBcProdCofins, 2, ',', '.');
            break;
            case 'B':
                if ($this->quantBcProdCofins != null) {

                    $num = str_replace('.', '', $this->quantBcProdCofins);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }
            break;
            default:
                return $this->quantBcProdCofins;
        }
    }


    public function setCBenef($cbenef) { $this->cbenef = $cbenef; }

    public function getCBenef() { return $this->cbenef; }

    public function setBcFcpUfDest($bcFcpUfDest, $format=false) {

        $this->bcFcpUfDest = (($bcFcpUfDest == "0,00") or ($bcFcpUfDest == "0.00")) ? c_tools::stringToDouble($bcFcpUfDest) : $bcFcpUfDest;

        if ($format){
            $this->bcFcpUfDest = number_format($this->bcFcpUfDest, 2, ',', '.');            
        }
    }

    public function getBcFcpUfDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->bcFcpUfDest = $this->bcFcpUfDest ?? 0;

                return number_format((double) $this->bcFcpUfDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->bcFcpUfDest != null) {

                    $num = str_replace('.', '', $this->bcFcpUfDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->bcFcpUfDest;
        }
    }

    public function setAliqFcpUfDest($aliqFcpUfDest, $format=false) {

        $this->aliqFcpUfDest = (($aliqFcpUfDest == "0,00") or ($aliqFcpUfDest == "0.00")) ? c_tools::stringToDouble($aliqFcpUfDest) : $aliqFcpUfDest;

        if ($format){
            $this->aliqFcpUfDest = number_format($this->aliqFcpUfDest, 2, ',', '.');            
        }
    }

    public function getAliqFcpUfDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqFcpUfDest = $this->aliqFcpUfDest ?? 0;

                return number_format((double) $this->aliqFcpUfDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqFcpUfDest != null) {

                    $num = str_replace('.', '', $this->aliqFcpUfDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqFcpUfDest;
        }
    }

    public function setAliqIcmsInter($aliqIcmsInter, $format=false) {

        $this->aliqIcmsInter = (($aliqIcmsInter == "0,00") or ($aliqIcmsInter == "0.00")) ? c_tools::stringToDouble($aliqIcmsInter) : $aliqIcmsInter;

        if ($format){
            $this->aliqIcmsInter = number_format($this->aliqIcmsInter, 2, ',', '.');            
        }
    }

    public function getAliqIcmsInter($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqIcmsInter = $this->aliqIcmsInter ?? 0;

                return number_format((double) $this->aliqIcmsInter, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIcmsInter != null) {

                    $num = str_replace('.', '', $this->aliqIcmsInter);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqIcmsInter;
        }
    } 

    public function setaliqIcmsInterPart($aliqIcmsInterPart, $format=false) { 

        $this->aliqIcmsInterPart = (($aliqIcmsInterPart == "0,00") or ($aliqIcmsInterPart == "0.00")) ? c_tools::stringToDouble($aliqIcmsInterPart) : $aliqIcmsInterPart;

        if ($format){
            $this->aliqIcmsInterPart = number_format($this->aliqIcmsInterPart, 2, ',', '.');            
        }
    }

    public function getAliqIcmsInterPart($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqIcmsInterPart = $this->aliqIcmsInterPart ?? 0;

                return number_format((double) $this->aliqIcmsInterPart, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIcmsInterPart != null) {

                    $num = str_replace('.', '', $this->aliqIcmsInterPart);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->aliqIcmsInterPart;
        }
    } 

    public function setValorFcpUfDest($valorFcpUfDest, $format=false) { 

        $this->valorFcpUfDest = (($valorFcpUfDest == "0,00") or ($valorFcpUfDest == "0.00")) ? c_tools::stringToDouble($valorFcpUfDest) : $valorFcpUfDest;

        if ($format){
            $this->valorFcpUfDest = number_format($this->valorFcpUfDest, 2, ',', '.');            
        }
    }

    public function getValorFcpUfDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorFcpUfDest = $this->valorFcpUfDest ?? 0;

                return number_format((double) $this->valorFcpUfDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorFcpUfDest != null) {
                    $num = str_replace('.', '', $this->valorFcpUfDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->valorFcpUfDest;
        }
    }    

    public function setBcIcmsUfDest($bcIcmsUfDest, $format=false) { 

        $this->bcIcmsUfDest = (($bcIcmsUfDest == "0,00") or ($bcIcmsUfDest == "0.00")) ? c_tools::stringToDouble($bcIcmsUfDest) : $bcIcmsUfDest;

        if ($format){
            $this->bcIcmsUfDest = number_format($this->bcIcmsUfDest, 2, ',', '.');            
        }
    }

    public function getBcIcmsUfDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->bcIcmsUfDest = $this->bcIcmsUfDest ?? 0;

                return number_format((double) $this->bcIcmsUfDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->bcIcmsUfDest != null) {

                    $num = str_replace('.', '', $this->bcIcmsUfDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {
                    return 0;
                }

            break;
            default:
                return $this->bcIcmsUfDest;
        }
    }    

    public function setAliqIcmsUfDest($aliqIcmsUfDest, $format=false) { 

        $this->aliqIcmsUfDest = (($aliqIcmsUfDest == "0,00") or ($aliqIcmsUfDest == "0.00")) ? c_tools::stringToDouble($aliqIcmsUfDest) : $aliqIcmsUfDest;

        if ($format){
            $this->aliqIcmsUfDest = number_format($this->aliqIcmsUfDest, 2, ',', '.');            
        }
    }

    public function getAliqIcmsUfDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->aliqIcmsUfDest = $this->aliqIcmsUfDest ?? 0;

                return number_format((double) $this->aliqIcmsUfDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->aliqIcmsUfDest != null) {

                    $num = str_replace('.', '', $this->aliqIcmsUfDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->aliqIcmsUfDest;
        }
    } 
 
    public function setValorIcmsUFDest($valorIcmsUFDest, $format=false) { 

        $this->valorIcmsUFDest = (($valorIcmsUFDest == "0,00") or ($valorIcmsUFDest == "0.00")) ? c_tools::stringToDouble($valorIcmsUFDest) : $valorIcmsUFDest;

        if ($format){
            $this->valorIcmsUFDest = number_format($this->valorIcmsUFDest, 2, ',', '.');            
        }
    }

    public function getValorIcmsUFDest($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcmsUFDest = $this->valorIcmsUFDest ?? 0;

                return number_format((double) $this->valorIcmsUFDest, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcmsUFDest != null) {

                    $num = str_replace('.', '', $this->valorIcmsUFDest);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->valorIcmsUFDest;
        }
    } 

    public function setValorIcmsUFRemet($valorIcmsUFRemet, $format=false) { 

        $this->valorIcmsUFRemet = (($valorIcmsUFRemet == "0,00") or ($valorIcmsUFRemet == "0.00")) ? c_tools::stringToDouble($valorIcmsUFRemet) : $valorIcmsUFRemet;

        if ($format){
            $this->valorIcmsUFRemet = number_format($this->valorIcmsUFRemet, 2, ',', '.');            
        }
    }

    public function getValorIcmsUFRemet($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->valorIcmsUFRemet = $this->valorIcmsUFRemet ?? 0;

                return number_format((double) $this->valorIcmsUFRemet, 2, ',', '.');
            break;
            case 'B':

                if ($this->valorIcmsUFRemet != null) {

                    $num = str_replace('.', '', $this->valorIcmsUFRemet);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->valorIcmsUFRemet;
        }
    }

    public function setCstIpi($cstIpi) { $this->cstIpi = $cstIpi; }

    public function getCstIpi() { return $this->cstIpi; }

    public function setFrete($frete, $format=false) { 

        $this->frete = (($frete == "0,00") or ($frete == "0.00")) ? c_tools::stringToDouble($frete) : $frete;

        if ($format){
           $this->frete = number_format($this->frete, 4, ',', '.');
        }
    }

    public function getFrete($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->frete = $this->frete ?? 0;

                return number_format($this->frete, 4, ',', '.');
            break;
            case 'B':

                if ($this->frete != null) {

                    $num = str_replace('.', '', $this->frete);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->frete;
        }
    }
    
    public function setDespAcessorias($despAcessorias, $format=false) { 

        $this->despAcessorias = (($despAcessorias == "0,00") or ($despAcessorias == "0.00")) ? c_tools::stringToDouble($despAcessorias) : $despAcessorias;
        
        if ($format){
           $this->despAcessorias = number_format($this->despAcessorias, 4, ',', '.');
        }
    }
    
    public function getDespAcessorias($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->despAcessorias = $this->despAcessorias ?? 0;

                return number_format($this->despAcessorias, 4, ',', '.');
                break;
            case 'B':

                if ($this->despAcessorias != null) {

                    $num = str_replace('.', '', $this->despAcessorias);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->despAcessorias;
        }
    }

    public function setCodigoNota($codigonota) { $this->codigonota = $codigonota; }

    public function getCodigoNota() { return $this->codigonota; }

    public function setCsosn($csosn) { $this->csosn = $csosn; }

    public function getCsosn() { return $this->csosn; }

    public function setRFreteProd($rFrete) {

        $this->rFrete = (($rFrete == "0,00") or ($rFrete == "0.00")) ? c_tools::stringToDouble($rFrete) : $rFrete;
    }

    public function getRFreteProd($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->rFrete = $this->rFrete ?? 0;

                return number_format($this->rFrete, 2, ',', '.');
            break;
            case 'B':

                if ($this->rFrete != null) {

                    $num = str_replace('.', '', $this->rFrete);
                    $num = str_replace(',', '.', $num);
                    
                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->rFrete;
        }
    }

    public function setRDespProd($rDesp) { 

        $this->rDesp = (($rDesp == "0,00") or ($rDesp == "0.00")) ? c_tools::stringToDouble($rDesp) : $rDesp;
    }

    public function getRDespProd($format = null) 
    {
        switch ($format) {
            case 'F':
                // Operador de coalescencia versao php.8.3
                $this->rDesp = $this->rDesp ?? 0;

                return number_format($this->rDesp, 2, ',', '.');
            break;
            case 'B':

                if ($this->rDesp != null) {
                    $num = str_replace('.', '', $this->rDesp);
                    $num = str_replace(',', '.', $num);

                    return $num;
                } else {

                    return 0;
                }

            break;
            default:
                return $this->rDesp;
        }
    }
    //############### FIM SETS E GETS ###############

//---------------------------------------------------------------
//---------------------------------------------------------------
    function setNotaFiscalProduto() {

        $notaFiscal = $this->select_nota_fiscal_produto();
        $this->setId($notaFiscal[0]['ID']);
        $this->setIdNf($notaFiscal[0]['IDNF']);
        $this->setCodProduto($notaFiscal[0]['CODPRODUTO']);
        $this->setDescricao($notaFiscal[0]['DESCRICAO']);
        $this->setUnidade($notaFiscal[0]['UNIDADE']);
        $this->setQuant($notaFiscal[0]['QUANT']);
        $this->setUnitario($notaFiscal[0]['UNITARIO']);
        $this->setDesconto($notaFiscal[0]['DESCONTO']);
        $this->setTotal($notaFiscal[0]['TOTAL']);
        $this->setOrigem($notaFiscal[0]['ORIGEM']);
        $this->setTribIcms($notaFiscal[0]['TRIBICMS']);
        $this->setNcm($notaFiscal[0]['NCM']);
        $this->setCest($notaFiscal[0]['CEST']);
        $this->setCfop($notaFiscal[0]['CFOP']);
        $this->setAliqIcms($notaFiscal[0]['ALIQICMS']);
        $this->setPercReducaoBc($notaFiscal[0]['PERCREDUCAOBC']);
        $this->setModBc($notaFiscal[0]['MODBC']);
        $this->setBcIcms($notaFiscal[0]['BCICMS']);
        $this->setValorIcms($notaFiscal[0]['VALORICMS']);
        $this->setCstIpi($notaFiscal[0]['CSTIPI']);
        $this->setBaseCalculoIpi($notaFiscal[0]['BCIPI']);
        $this->setAliqIpi($notaFiscal[0]['ALIQIPI']);
        $this->setValorIpi($notaFiscal[0]['VALORIPI']);
        $this->setPercDiferido($notaFiscal[0]['PERCDIFERIDO']);
        $this->setValorIcmsDiferido($notaFiscal[0]['VALORICMSDIFERIDO']);
        $this->setValorIcmsOperacao($notaFiscal[0]['VALORICMSOPERACAO']);
        $this->setModBcSt($notaFiscal[0]['MODBCST']);
        $this->setPercMvaSt($notaFiscal[0]['PERCMVAST']);
        $this->setBcFcpSt($notaFiscal[0]['BCFCPST']);
        $this->setAliqFcpSt($notaFiscal[0]['ALIQFCPST']);
        $this->setValorFcpSt($notaFiscal[0]['VALORFCPST']);                
        $this->setPercReducaoBcSt($notaFiscal[0]['PERCREDUCAOBCST']);
        $this->setValorBcSt($notaFiscal[0]['VALORBCST']);
        $this->setAliqIcmsSt($notaFiscal[0]['ALIQICMSST']);
        $this->setValorIcmsSt($notaFiscal[0]['VALORICMSST']);
        $this->setValorTotalTributos($notaFiscal[0]['VALORTOTALTRIBUTOS']);
        $this->setCustoProduto($notaFiscal[0]['CUSTOPRODUTO']);
        $this->setNrSerie($notaFiscal[0]['NRSERIE']);
        $this->setLote($notaFiscal[0]['LOTE']);
        $this->setDataFabricacao($notaFiscal[0]['DATAFABRICACAO']);
        $this->setDataValidade($notaFiscal[0]['DATAVALIDADE']);
        $this->setDataGarantia($notaFiscal[0]['DATAGARANTIA']);
        $this->setDataConferencia($notaFiscal[0]['DATACONFERENCIA']);
        $this->setOrdem($notaFiscal[0]['ORDEM']);
        $this->setProjeto($notaFiscal[0]['PROJETO']);
        $this->setCstPis($notaFiscal[0]['CSTPIS']);
        $this->setBcPis($notaFiscal[0]['BCPIS']);
        $this->setAliqPis($notaFiscal[0]['ALIQPIS']);
        $this->setValorPis($notaFiscal[0]['VALORPIS']);
        $this->setCstCofins($notaFiscal[0]['CSTCOFINS']);
        $this->setBcCofins($notaFiscal[0]['BCCOFINS']);
        $this->setAliqCofins($notaFiscal[0]['ALIQCOFINS']);        
        $this->setValorCofins($notaFiscal[0]['VALORCOFINS']);
        $this->setPSt($notaFiscal[0]['PST']);
        $this->setCBenef($notaFiscal[0]['CBENEF']);
        $this->setBcFcpUfDest($notaFiscal[0]['BCFCPUFDEST']);        
        $this->setAliqFcpUfDest($notaFiscal[0]['ALIQFCPUFDEST']); 
        $this->setValorFcpUfDest($notaFiscal[0]['VALORFCPUFDEST']);
        $this->setBcIcmsUfDest($notaFiscal[0]['BCICMSUFDEST']);
        $this->setAliqIcmsUfDest($notaFiscal[0]['ALIQICMSUFDEST']);
        $this->setAliqIcmsInter($notaFiscal[0]['ALIQICMSINTER']);
        $this->setAliqIcmsInterPart($notaFiscal[0]['ALIQICMSINTERPART']);       
        $this->setValorIcmsUfDest($notaFiscal[0]['VALORICMSUFDEST']);
        $this->setValorIcmsUfRemet($notaFiscal[0]['VALORICMSUFREMET']);
        $this->setRFreteProd($notaFiscal[0]['FRETE']);
        $this->setRDespProd($notaFiscal[0]['DESPACESSORIAS']);

        $this->setValorIcmsStRetido($notaFiscal[0]['VALORICMSSTRETIDO']);
        $this->setValorIcmsSubstituto($notaFiscal[0]['VICMSSUBSTITUTO']);
        $this->setValorBaseCalculoStRetido($notaFiscal[0]['VALORBCSTRETIDO']);
        
    }    
        
    /**
     * <b> É responsavel por calcular o total do produto </b>
     * @name atualizaTotalNfe
     * @param int $idnf
     * @return total - valor total do produto considerando descontos.
     */
    public function atualizaTotalNfe($idnf = NULL){

        $banco = new c_banco();
        $sql = "SELECT (sum(np.total) + (nf.despacessorias + nf.frete + nf.seguro)) as tot FROM EST_NOTA_FISCAL_PRODUTO np ";
        $sql .= "inner join EST_NOTA_FISCAL nf ON np.idnf = nf.id ";
        $sql .= "where (np.idnf=".$idnf.")";
        $banco->exec_sql($sql);
        $arrTotal =  $banco->resultado;
        if ($arrTotal[0]['TOT'] == null):
            $total = 0;
        else:
            $total = $arrTotal[0]['TOT'];
        endif;
        
        
        $sql = "update EST_NOTA_FISCAL set totalnf = ".$total;
        $sql .= " WHERE (id=" . $idnf . ")";
        // echo strtoupper($sql);

        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->result;
        
    }
    

    /**
     * <b> É responsavel por calcular o total do produto </b>
     * @name calculaTotalNfe
     * @param obj $objNfProd
     * @return total - valor total do produto considerando descontos.
     */
    public function calculaTotalProduto($objNfProd = NULL){
        
        $quant = $objNfProd->getQuant('B');
        $vlUnitario = $objNfProd->getUnitario('B');
        $vlDesconto = $objNfProd->getDesconto('B');
        $aliqIpi = $objNfProd->getAliqIpi('B');
        $vlIpi = $objNfProd->getValorIpi('B');
        $vlTotal = 0;
        
        if ($quant == ''):
            $this->setQuant(1);
            $quant = 1;
        endif;

        $vlTotal = ($quant * $vlUnitario) - $vlDesconto;
        
        if ($aliqIpi == ''):
            $this->setAliqIpi(0);
            $this->setValorIpi(0);
            $aliqIpi = 0;
        else:
            $aliqIpi = $vlTotal + (($aliqIpi * $vlTotal) / 100);
        endif;
        return $vlTotal;
    }


//---------------------------------------------------------------
//---------------------------------------------------------------

    public function existeNotaFiscal() {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (idNf = '" . $this->getIdNf() . "' AND codProduto = " . $this->getCodProduto() . ")";
//	ECHO $sql;

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }
//fim existeDocumento
//---------------------------------------------------------------
//---------------------------------------------------------------

    public function existeProdutoConferencia($conn=null) {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (idNf = '" . $this->getIdNf() . "' AND dataconferencia is null)";
//	ECHO $sql;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return is_array($banco->resultado);
    }
//fim existeDocumento
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function existeDataConferencia() {

        if (($this->getDataConferencia() == '0000-00-00 00:00:00') || ($this->getDataConferencia() == ''))
            return 0;
        else
            return 1;
    }

//fim existeDocumento
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function select_nota_fiscal_produto() {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (ID = " . $this->getId() . ")";

        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
//fim select_nota_fiscal_produto
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function select_nota_fiscal_produto_nf($conn=null) {

        // $sql = "SELECT DISTINCT N.ID,N.IDNF,N.CODPRODUTO,N.DESCRICAO,N.UNIDADE,N.QUANT,N.UNITARIO,N.DESCONTO,N.TOTAL,N.ORIGEM,N.TRIBICMS,N.NCM,N.CEST,N.CFOP,N.ALIQICMS,N.PERCREDUCAOBC,N.MODBC,N.BCICMS,N.VALORICMS,N.ALIQIPI,N.VALORIPI,N.PERCDIFERIDO, ";
        // $sql .= "N.VALORICMSDIFERIDO,N.VALORICMSOPERACAO,N.MODBCST,N.PERCMVAST,N.PERCREDUCAOBCST,N.VALORBCST,N.ALIQICMSST,N.VALORICMSST,N.VALORTOTALTRIBUTOS,N.VALORBCSTRETIDO,N.VALORICMSSTRETIDO,N.CUSTOPRODUTO, ";
        // $sql .= "N.LOTE,N.DATAFABRICACAO,N.DATAVALIDADE,N.CSTPIS,N.BCPIS,N.ALIQPIS,N.VALORPIS,N.CSTCOFINS,N.BCCOFINS,N.ALIQCOFINS,N.VALORCOFINS, p.CODIGOBARRAS, p.CODPRODUTOANVISA, ";
        $sql = "SELECT DISTINCT N.*, ";
        $sql .= "P.CODIGOBARRAS, P.CODPRODUTOANVISA, P.CODFABRICANTE FROM EST_NOTA_FISCAL_PRODUTO N ";
        $sql .= "LEFT JOIN EST_PRODUTO P ON(N.CODPRODUTO = P.CODIGO) ";
        $sql .= "WHERE (N.IDNF = " . $this->getIdNf() . ") ";
        //$sql .= "ORDER BY p.codfabricante";
         // ECHO strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;

//	$this->exec_sql($sql);
//	return $this->resultado;
    }

//fim select_nota_fiscal_produto_nf

//---------------------------------------------------------------
//---------------------------------------------------------------
    public function incluiNotaFiscalProduto($conn=null) {

        $banco = new c_banco;

        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("EST_GEN_ID_NF_PRODUTO"));
            $sql = "INSERT INTO EST_NOTA_FISCAL_PRODUTO (ID,";
        } else {
            $sql = "INSERT INTO EST_NOTA_FISCAL_PRODUTO (";
        }


        $sql .= "IDNF, CODPRODUTO, DESCRICAO, UNIDADE, QUANT, UNITARIO, DESCONTO, TOTAL, ORIGEM,CFOP, TRIBICMS, PERCREDUCAOBC, ";
		$sql .= "MODBC, BCICMS, VALORICMS, CSTIPI, BCIPI, VALORIPI, PERCDIFERIDO, ALIQICMS, ALIQIPI, CSTPIS, BCPIS, ALIQPIS, VALORPIS, ";
		$sql .= "PERCMVAST, PERCREDUCAOBCST, BCFCPST, ALIQFCPST, VALORFCPST, VALORICMSDIFERIDO, ";
        $sql .= "BCCOFINS, ALIQCOFINS, VALORCOFINS, CUSTOPRODUTO, NCM, CEST, NRSERIE, LOTE, BCFCPUFDEST, ALIQFCPUFDEST, ";
        $sql .= "VALORFCPUFDEST, BCICMSUFDEST, ALIQICMSUFDEST, ALIQICMSINTER, ALIQICMSINTERPART, VALORICMSUFDEST, ";
        $sql .= "VALORICMSUFREMET, VALORBCST, VALORICMSST, ALIQICMSST, MODBCST, PCREDSN, VCREDICMSSN, CSTCOFINS, DATAFABRICACAO, DATAVALIDADE, ";
		$sql .= "DATAGARANTIA, ORDEM, PROJETO, DATACONFERENCIA, CBENEF, FRETE, CODIGONOTA, VALORICMSOPERACAO, VALORBCSTRETIDO, VALORICMSSTRETIDO, VICMSSUBSTITUTO,DESPACESSORIAS) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", ";
        } else {
            $sql .= "VALUES (";
        }
        $sql .=
                $this->getIdNf() . ", "
                . $this->getCodProduto() . ", '"
                . $this->getDescricao() . "', '"
                . $this->getUnidade() . "', "
                . $this->getQuant('B') . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDesconto('B') . ", "
                . $this->getTotal('B') . ", '"
                . $this->getOrigem() . "', '"
                . $this->getCfop() . "', '"
                . $this->getTribIcms() . "', '"
                . $this->getPercReducaoBc('B') . "', '"
                . $this->getModBc() . "', "
                . $this->getBcIcms('B') . ", "
                . $this->getValorIcms('B') . ", '"
                . $this->getCstIpi() . "', '"
                . $this->getBaseCalculoIpi('B') . "', "
                . $this->getValorIpi('B') . ", "
                . $this->getPercDiferido('B') . ", "
                . $this->getAliqIcms('B') . ", "
                . $this->getAliqIpi('B') . ", '"
                . $this->getCstPis() . "', "
                . $this->getBcPis('B') . ", "
                . $this->getAliqPis('B') . ", "
                . $this->getValorPis('B') . ", '"
                . $this->getPercMvaSt('B') . "', '"
                . $this->getPercReducaoBcSt('B') . "', '"
                . $this->getBcFcpSt('B') . "', '"
                . $this->getAliqFcpSt('B') . "', '"
                . $this->getValorFcpSt('B') . "', '"
                . $this->getValorIcmsDiferido('B') . "', '"
                . $this->getBcCofins('B') . "', "
                . $this->getAliqCofins('B') . ", "
                . $this->getValorCofins('B') . ", "
                . $this->getCustoProduto('B') . ", '"
                . $this->getNcm() . "', '"
                . $this->getCest() . "', '"
                . $this->getNrSerie() . "' , '"
                . $this->getLote() . "', '"
                . $this->getBcFcpUfDest('B') . "', '"
                . $this->getAliqFcpUfDest('B') . "', '"
                . $this->getValorFcpUfDest('B') . "', '"
                . $this->getBcIcmsUfDest('B') . "', '"
                . $this->getAliqIcmsUfDest('B') . "', '"
                . $this->getAliqIcmsInter('B') . "', '"
                . $this->getAliqIcmsInterPart('B') . "', '"                        
                . $this->getValorIcmsUfDest('B') . "', '"
                . $this->getValorIcmsUFRemet('B') . "', "       
                . $this->getValorBcSt('B') . ", "            
                . $this->getValorIcmsSt('B') . ", "        
                . $this->getAliqIcmsSt('B') . ", '"       
                . $this->getModBcSt() . "', '"                       
                . $this->getPCredSN('B') . "', '"                        
                . $this->getVCredICMSSN('B') . "', ";

        if ($this->getCstCofins() == ''):
            $sql .= "null, ";
        else:
            $sql .= "'" . $this->getCstCofins() . "', ";
        endif;
        
        if ($this->getDataFabricacao('B') == ''):
            $sql .= "null, ";
        else:
            $sql .= "'" . $this->getDataFabricacao('B') . "', ";
        endif;

        if ($this->getDataValidade('B') == ''):
            $sql .= "null, ";
        else:
            $sql .= "'" . $this->getDataValidade('B') . "', ";
        endif;

        if ( $this->getDataFabricacao('B') == ''):
            $sql .= "null, '";
        else:
            $sql .= "'" . $this->getDataGarantia('B') . "', '";
        endif;        

        $sql .= $this->getOrdem() . "', '". $this->getProjeto() . "', ";

        if ($this->getDataConferencia('B') == ''):
            $sql .= "null,  ";
        else:
            $sql .= "'" .$this->getDataConferencia('B') . "',  ";
        endif;

        $sql .= "'".$this->getCBenef() . "', ";
        $sql .= " ".$this->getFrete('B') . ", '";
        $sql .= " ".$this->getCodigoNota() . "', ";
        $sql .= " ".$this->getValorIcmsOperacao('B') . ", ";
        $sql .= " ".$this->getValorBaseCalculoStRetido('B') . ", ";
        $sql .= " ".$this->getValorIcmsStRetido('B') . ", ";
        $sql .= " ".$this->getValorIcmsSubstituto('B') . ", ";
        $sql .= " ".$this->getDespAcessorias('B') . ");";

        //ECHO strtoupper($sql)."<br>";
        $banco = new c_banco;
        $res_nf = $banco->exec_sql($sql, $conn, 'EST_PRODUTO_ESTOQUE');
        $banco->close_connection();
        return $banco->result;
       
    }

// fim incluiNotaFiscal
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function alteraDataConferencia() {
        $this->setDataConferencia(date("d/m/Y H:i:s"));
        $sql = "UPDATE est_nota_fiscal_produto ";
        $sql .= " SET dataConferencia = '" . $this->getDataConferencia('B') . "'";
        $sql .= " WHERE (IdNf = " . $this->getIdNf() . ") AND (ID = " . $this->getId() . ") ";
//	$sql .= " WHERE (IdNf = ".$this->getIdNf().") AND (codProduto = ".$this->getCodProduto().") ";
        //echo strtoupper($sql);
        $banco = new c_banco;

        $res_nf = $banco->exec_sql($sql);

        $banco->close_connection();

        if ($res_nf > 0) {
            return 'PRODUTO RECEBIDO';
        } else {
            return 'Os dados do Produto n&atilde;o foram alterados!';
        }
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    public function alteraNotaFiscalProduto($conn=null) {

        $sql = "UPDATE est_nota_fiscal_produto ";
        $sql .= "SET  ";
        $sql .= "codProduto = '" . $this->getCodProduto() . "', ";
        $sql .= "descricao = '" . $this->getDescricao() . "', ";
        $sql .= "Unidade = '" . $this->getUnidade() . "', ";
        $sql .= "Quant = " . $this->getQuant('B') . ", ";
        $sql .= "Unitario = " . $this->getUnitario('B') . ", ";
        $sql .= "Desconto = " . $this->getDesconto('B') . ", ";
        $sql .= "Total = " . $this->getTotal('B') . ", ";
        $sql .= "Origem = '" . $this->getOrigem() . "', ";
        $sql .= "Cfop = " . $this->getCfop() . ", ";
        $sql .= "tribIcms = '" . $this->getTribIcms() . "', ";
        $sql .= "modBc = " . $this->getModBc() . ", ";
        $sql .= "bcIcms = " . $this->getBcIcms('B') . ", ";
        $sql .= "ValorIcms = " . $this->getValorIcms('B') . ", ";
        $sql .= "bcipi = " . $this->getBaseCalculoIpi('B') . ", ";
        $sql .= "cstIpi = '" . $this->getCstIpi() . "', ";
        $sql .= "ValorIpi = " . $this->getValorIpi('B') . ", ";
        $sql .= "AliqIcms = " . $this->getAliqIcms('B') . ", ";
        $sql .= "percreducaobc = " . $this->getPercReducaoBc('B') . ", ";
        $sql .= "percMvaSt = " . $this->getPercMvaSt('B') . ", ";
        $sql .= "bcFcpSt = " . $this->getBcFcpSt('B') . ", ";        
        $sql .= "aliqFcpSt = " . $this->getAliqFcpSt('B') . ", ";        
        $sql .= "valorFcpSt = " . $this->getValorFcpSt('B') . ", ";                        
        $sql .= "percReducaoBcSt = " . $this->getPercReducaoBcSt('B') . ", ";
        $sql .= "aliqicmsst = " . $this->getAliqIcmsSt('B') . ", ";
        $sql .= "valorbcst = " . $this->getValorBcSt('B') . ", ";
        $sql .= "modBcSt = '".$this->getModBcSt()."', ";
        $sql .= "valoricmsst = " . $this->getValorIcmsSt('B') . ", ";
        $sql .= "AliqIpi = " . $this->getAliqIpi('B') . ", ";
        $sql .= "cstpis = '" . $this->getCstPis() . "', ";
        $sql .= "bcpis = " . $this->getBcPis('B') . ", ";
        $sql .= "aliqpis = " . $this->getAliqPis('B') . ", ";
        $sql .= "valorpis = " . $this->getValorPis('B') . ", ";
        $sql .= "cstCofins = '" . $this->getCstCofins() . "', ";
        $sql .= "bccofins = " . $this->getBcCofins('B') . ", ";
        $sql .= "aliqcofins = " . $this->getAliqCofins('B') . ", ";
        $sql .= "valorcofins = " . $this->getValorCofins('B') . ", ";
        $sql .= "bcfcpufdest = " . $this->getBcFcpUfDest('B') . ", ";
        $sql .= "aliqfcpufdest = " . $this->getAliqFcpUfDest('B') . ", ";
        $sql .= "valorfcpufdest = " . $this->getValorFcpUfDest('B') . ", ";
        $sql .= "bcicmsufdest = " . $this->getBcIcmsUfDest('B') . ", ";
        $sql .= "aliqicmsufdest = " . $this->getAliqIcmsUfDest('B') . ", ";
        $sql .= "aliqicmsinter = " . $this->getAliqIcmsInter('B') . ", ";
        $sql .= "aliqicmsinterpart = " . $this->getAliqIcmsInterPart('B') . ", ";                        
        $sql .= "valoricmsufdest   = " . $this->getValorIcmsUfDest('B') . ", ";
        $sql .= "valoricmsufremet  = " . $this->getValorIcmsUFRemet('B') . ", ";        
        $sql .= "Ncm = '" . $this->getNcm() . "', ";
        $sql .= "Cest = '" . $this->getCest() . "', ";
        $sql .= "NrSerie = '" . $this->getNrSerie() . "', ";
        $sql .= "Lote = '" . $this->getLote() . "', ";
        $sql .= "percdiferido = " . $this->getPercDiferido('B') . ", ";
        $sql .= "valorIcmsDiferido = " . $this->getValorIcmsDiferido('B') . ", ";
        $sql .= "VALORICMSOPERACAO = '" . $this->getValorIcmsOperacao('B') . "', ";

        if ($this->getDataConferencia('B') == '')
            $sql .= "DATACONFERENCIA = null, ";
        else
            $sql .= "DATACONFERENCIA = '" . $this->getDataConferencia('B') . "', ";
        if ($this->getDataFabricacao('B') == '')
            $sql .= "DATAFABRICACAO = null, ";
        else
            $sql .= "DATAFABRICACAO = '" . $this->getDataFabricacao('B') . "', ";
        if ($this->getDataValidade('B') == '')
            $sql .= "DATAVALIDADE = null, ";
        else
            $sql .= "DATAVALIDADE = '" . $this->getDataValidade('B') . "', ";
        if ($this->getDataGarantia('B') == '')
            $sql .= "DATAGARANTIA = null, ";
        else
            $sql .= "DATAGARANTIA = '" . $this->getDataGarantia('B') . "', ";
        $sql .= "ORDEM = '" . $this->getOrdem() . "', ";
        $sql .= "PROJETO = '" . $this->getProjeto() . "', ";
        $sql .= "CBENEF = '" . $this->getCBenef() . "', ";

        $sql .= "VALORBCSTRETIDO = " . $this->getValorBaseCalculoSTRetido() . ", ";
        $sql .= "VALORICMSSTRETIDO = " . $this->getValorIcmsStRetido() . ", ";
        $sql .= "VICMSSUBSTITUTO = " . $this->getValorIcmsSubstituto() . " ";
        $sql .= " WHERE id = " . $this->getId() . ";";

        //echo strtoupper($sql);
        $banco = new c_banco;

        $res_nfProduto = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

	if($res_nfProduto > 0){
            return true;
        } else {
            return 'Os dados do Produto ' . $this->getDescricao() . ' n&atilde;o foram alterados!';
        }
    }

// fim alteraNotaFiscal
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function excluiNotaFiscalProduto() {

        $sql = "DELETE FROM est_nota_fiscal_produto ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;
        $res_nf = $banco->exec_sql($sql);
        $banco->close_connection();
        // echo strtoupper($sql);
        if ($res_nf > 0) {
            return 'Produto Deletado com sucesso.';
        } else {
            return 'Os dados da Nota Fiscal ' . $this->getDescricao() . ' n&atilde;o foram excluidos!';
        }
    }

// fim excluiNotaFiscal




    
    
    
    
    
//---------------------------------------------------------------
//não utilizado
//---------------------------------------------------------------
    function mudaSituacaoChamado($id) {
        $classAtPecas = new c_atPecas();
        $classAtPecas->setId($id);
        $atPecas = $classAtPecas->select_at_pecas();
        $status = true; //flag para atualizar o status
        for ($i = 0; $i < count($atPecas); $i++) {
            $sit = $atPecas[$i]['SITUACAO'];
            if ((($sit == 'PECARECEBIDA') || ($sit == 'RESERVAR') || ($sit == 'RESERVADO') || ($sit == 'APLICADO') || ($sit == 'DESFEITO')) && ($status == true)) {
                
            } else {
                $status = false;
            }//if
        }//for
        if ($status) {
            $classOs = new c_ordemServico;
            $parametros = new c_banco;
            $parametros->setTab("CAT_PARAMETROS");
            $situacao = $parametros->getParametros("SITPECARECEBIDA");
            $parametros->close_connection();
            $classOs->setId($id);
            $classOs->setSituacao($situacao);
            $classOs->alteraOrdemServicoSituacao();
            $classOs->lancaAcompanhamento("Alteracao da Situacao - Nova Situacao: PECA RECEBIDA", "", $this->m_userid);
        }//if
    }

//mudaSituacaoChamado    
//---------------------------------------------------------------
//pedquisa pecas chamado - distribui os
//---------------------------------------------------------------

    public function select_nota_fiscal_produto_chamado() {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (ordem = '" . $this->getOrdem() . "')";

        //echo strtoupper($sql)."<br>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_nota_fiscal_produto_chamado

    /**
     * <b> select utilizado: p_relatorio_gerencial </b>
     * @name     select_consolidacao_fiscal
     * @param    date dataInicio 
     * @param    date dataFim
     */
    public function select_consolidacao_fiscal($dataIni, $dataFim, $desc=null) {
        
        $sql = "SELECT N.NUMERO,N.EMISSAO,N.NATOPERACAO,  ";//F.DATACONFERENCIA,
        $sql .= "P.CODFABRICANTE, P.DESCRICAO, O.*, ";
        $sql .= "L.NUMLOTE, L.DATAENTREGA, L.NUMNF, U.NOMEREDUZIDO ";
        $sql .= "from EST_PRODUTO_ESTOQUE O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO = P.CODIGO) ";
        $sql .= "INNER JOIN EST_NOTA_FISCAL N ON (N.ID = O.IDNFENTRADA) ";
        //$sql .= "INNER JOIN EST_NOTA_FISCAL_PRODUTO F ON (F.IDNF = O.IDNFENTRADA) ";
        $sql .= "LEFT JOIN EST_LOTE L ON (L.ID = O.IDLOTE) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = O.USERPRODUTO) ";
        $sql .= "WHERE n.emissao >= '".c_date::convertDateBd($dataIni, $this->m_banco)."' ";
        $sql .= "and n.emissao <= '".c_date::convertDateBd($dataFim, $this->m_banco)."'; ";
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_consolidacao_fiscal
    
    /**
     * <b> select utilizado: p_relatorio_gerencial </b>
     * @name     select_consolidacao_fiscal
     * @param    date dataInicio 
     * @param    date dataFim
     */
    public function select_consolidacao_produtos($letra, $grupo = true, $desc=null) {
        
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[3]);
        $dataFim = c_date::convertDateTxt($par[4]);
        
        $sql = "SELECT N.NUMERO, P.CODPRODUTO, P.DESCRICAO, P.UNITARIO, P.CFOP, ";
        $sql .= "ALIQICMS, ALIQPIS, ALIQCOFINS, N.EMISSAO, N.ORIGEM, N.DOC, C.NOME, C.NOMEREDUZIDO, ";
        if ($grupo==true):
            $sql .= "sum(P.VALORICMS) as ICMS, sum(P.VALORPIS) as PIS, sum(P.VALORCOFINS) as COFINS, ";
            $sql .= "SUM(P.QUANT) AS QUANT, sum(P.TOTAL) AS TOTAL FROM EST_NOTA_FISCAL_PRODUTO P ";
        else:
            $sql .= "P.VALORICMS as ICMS, P.VALORPIS as PIS, P.VALORCOFINS as COFINS, ";
            $sql .= "P.QUANT, P.TOTAL FROM EST_NOTA_FISCAL_PRODUTO P ";
        endif;
        $sql .= "inner JOIN EST_NOTA_FISCAL N ON (N.ID=P.IDNF) ";
        $sql .= "inner JOIN FIN_CLIENTE C ON (C.CLIENTE=N.PESSOA) ";

        $iswhere = false;

        if ($par[5] != '') { // CASO PESQUISA POR NUMERO DA NF
            $sql .= "WHERE (N.NUMERO = '" . $par[5] . "') ";
        } else {
            
            if ($par[3] != '') { // PERIODO INICIAL DE PESQUISA
                $iswhere = true;
                $sql .= "WHERE (N.EMISSAO >= '" . $dataIni . "') ";
            }
            
            if ($par[4] != '') { // PERIODO FINAL DE PESQUISA
                if ($iswhere) {
                    $sql .= "AND (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";
                } else {
                    $iswhere = true;
                    $sql .= "WHERE (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";
                }
            }
            
            if (($par[0] != '') and ( $par[0] != '0')) { // FILIAL
                if ($iswhere) {
                    $sql .= "AND (N.CENTROCUSTO= " . $par[0] . ") ";
                } else {
                    $iswhere = true;
                    $sql .= "WHERE (N.CENTROCUSTO = " . $par[0] . ") ";
                }
            }// FIM FILIAL

            if ($par[1] != '') {  //TIPO DA NF 0-ENTRADA, 1 SAIDA
                if ($iswhere) {
                    $sql .= "AND (N.TIPO= " . $par[1] . ") ";
                } else {
                    $iswhere = true;
                    $sql .= "WHERE (N.TIPO = " . $par[1] . ") ";
                }
            }// FIM TIPO DA NF

            if (($par[2] != '') and ( $par[2] != '0')) { // SITUACAO DA NOTA
                if ($iswhere) {
                    $sql .= "AND (N.SITUACAO= '" . $par[2] . "') ";
                } else {
                    $iswhere = true;
                    $sql .= "WHERE (N.SITUACAO = '" . $par[2] . "') ";
                }
            }
        } // if numero
        
        if ($grupo==true):
            $sql .= "GROUP BY P.CODPRODUTO";
        else:
            $sql .= "ORDER BY N.NUMERO";
        endif;
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_consolidacao_fiscal
    
//---------------------------------------------------------------
//---------------------------------------------------------------

    public function select_nota_fiscal_produto_letra($letra) {
        $par = explode("|", $letra);
        
        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (descricao LIKE '" . $letra . "%') ";
        $sql .= "AND (IDNF = " . $this->getIdNf() . ") ";
        $sql .= "ORDER BY descricao ";

        //echo $sql;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function select_nota_fiscal_produtos_devolucao($nfsDevolucao) {
        $par = explode("|", $nfsDevolucao);
        
        $sql = "SELECT  * ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO ";

        $count = count($par) - 1;
        $notas = '';
        for($i = 1; $i < count($par); $i++){            
            $i == $count ? $notas .= "'".$par[$i]."'" : $notas .= "'".$par[$i]."',";
             
        }
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($notas) ? '':" $cond (IDNF IN (".$notas.")) ";

        //echo $sql;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function deleteNfProdutosDevolucao($nfProdutosDevolucao, $idNf) {
        $par = explode("|", $nfProdutosDevolucao);

        $count = count($par) - 1;
        $idNfp = '';
        for($i = 1; $i < count($par); $i++){            
            $i == $count ? $idNfp .= "'".$par[$i]."'" : $idNfp .= "'".$par[$i]."',";
             
        }
        
        $sql = "DELETE FROM EST_NOTA_FISCAL_PRODUTO ";
        $sql .= "WHERE ID NOT IN (".$idNfp.") AND IDNF =  '".$idNf."'";


        //echo $sql;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Faz a soma do total de desconto da nota atraves do id
     * @name selectTotalDescoProd
     * @return ARRAY total dos descontos em produto
    */
    public function selectTotalDescProd($idNf) {

        if ($idNf != ''){
            $sql = "SELECT sum(DESCONTO) as totalnf ";
            $sql .= "FROM est_nota_fiscal_produto ";
            $sql .= "WHERE (idnf = $idNf) ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();

            if($res_pedidoVenda > 0){
                return $banco->resultado[0]['TOTALNF'];
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    //Fim selectTotalDescProd

    /**
     * Calcula o total do pedido atraves do id
     * @name selectTotalNfProduto
     * @return ARRAY total do pedido
    */
    public function selectTotalNfProduto($idNf) {

        if ($idNf != ''){
            $sql = "SELECT sum(total) as totalnf ";
            $sql .= "FROM est_nota_fiscal_produto ";
            $sql .= "WHERE (idnf = $idNf) ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();

            if($res_pedidoVenda > 0){
                return $banco->resultado[0]['TOTALNF'];
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    // fim selectTotalNfProduto

    /**
     * Seleciona todas as tupulas do registro com id
     * @name selectNotaFiscalProdutoImposto
     * @return ARRAY todas as tupulas da tabela
*/
public function selectNotaFiscalProdutoImposto($idNf) {

    $sql = "SELECT * ";
    $sql .= "FROM est_nota_fiscal_produto ";
    $sql .= "WHERE (IDNF = $idNf)";
    //echo strtoupper($sql)."<BR>";

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

public function select_nota_fiscal($idNf, $conn=null) {

    $sql = "SELECT DISTINCT * ";
    $sql .= "FROM est_nota_fiscal ";
    $sql .= "WHERE (ID = " . $idNf . ") ";

    // echo strtoupper($sql);
    $banco = new c_banco();
    $banco->exec_sql($sql, $conn);
    $banco->close_connection();
    return $banco->resultado;
}

    /**
* <b> É responsavel para calcular os impostos dos itens da nota </b>
* @name calculoRateios
* @param vazio
* @return atualiza os totais de valores adicionais
*/
function calculaRateios($idNf) {

    if ($idNf > 0) {

        //$idNf = $this->getId();
        $objNfProduto = new c_nota_fiscal_produto();
        $totalNF = $objNfProduto->selectTotalNfProduto($idNf);
        $descontoNF = $objNfProduto->selectTotalDescProd($idNf);
        $total = $totalNF;
 
 
        $objNf = $this->select_nota_fiscal($idNf);
        $despAcessorias = $objNf[0]['DESPACESSORIAS'];
        $frete = $objNf[0]['FRETE'];
        
        $despAcessoriasDist = 0;
        $freteDist = 0;
        $descontoGeralDist = 0;
        $custototal = 0;
        $despesatotal = 0; //?
        $margemliquida = 0;
        $markup = 0;            
        $lucrobruto = 0;

        $totalNF = 0;
        
        $arrItemPedido = $objNfProduto->selectNotaFiscalProdutoImposto($idNf) ?? [];
        
        for ($i = 0; $i < count($arrItemPedido); $i++) {
            $sqlFields = '';

            $sqlTotal = ""; //atualizar item com valor errado
            if ($arrItemPedido[$i]['TOTAL'] != 
                ($arrItemPedido[$i]['QUANT']*$arrItemPedido[$i]['UNITARIO'])){
                $sqlTotal = ", TOTAL = ".($arrItemPedido[$i]['QUANT']*$arrItemPedido[$i]['UNITARIO']);
            }
            
            $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANT']*$arrItemPedido[$i]['UNITARIO'];
            $totalNF += $arrItemPedido[$i]['TOTAL']; 
            
            if ($despAcessorias > 0 ) {
                $perc = ($arrItemPedido[$i]['TOTAL'] / $total) * 100;
                $vlrDespAcessorias = round(($despAcessorias * ($perc/100)),2);
                $despAcessoriasDist += $vlrDespAcessorias;
                if ($i == (count($arrItemPedido) - 1)) {
                    if ($despAcessoriasDist > $despAcessorias) {
                        $vlrDespAcessorias = $vlrDespAcessorias - ($despAcessoriasDist - $despAcessorias);
                    } else if ($despAcessoriasDist < $despAcessorias) {
                        $vlrDespAcessorias = $vlrDespAcessorias + ($despAcessorias - $despAcessoriasDist);
                    }
                }
                if ($sqlFields <> "") {
                    $sqlFields .= ' despAcessorias = '.$vlrDespAcessorias;
                } else {
                    $sqlFields .= ' despAcessorias = '.$vlrDespAcessorias; 
                }                               
            } else {
                $sqlFields .= ' despAcessorias = 0 ';
            }

            if ($frete > 0 ) {
                $perc = ( $arrItemPedido[$i]['TOTAL'] / $total) * 100;
                $vlrFrete = round(($frete * ($perc/100)),2);
                $freteDist += $vlrFrete;
                if ($i == (count($arrItemPedido) - 1)) {
                    if ($freteDist > $frete) {
                        $vlrFrete = $vlrFrete - ($freteDist - $frete);
                    } else if ($freteDist < $frete) {
                        $vlrFrete = $vlrFrete + ($frete - $freteDist);
                    }
                } 
                if ($sqlFields <> "") {
                    $sqlFields .= ', frete = '.$vlrFrete;
                } else {
                    $sqlFields .= ' frete = '.$vlrFrete; 
                }
            } else {
                $sqlFields .= ', frete = 0 ';
            }
            
            if ($sqlTotal != ""){
                $sqlFields = $sqlFields.$sqlTotal;
            }

            $banco = new c_banco;
            $sql = 'UPDATE EST_NOTA_FISCAL_PRODUTO SET '.$sqlFields." WHERE ID = ".$arrItemPedido[$i]['ID']." and CODPRODUTO = ".$arrItemPedido[$i]['CODPRODUTO'];
            //echo strtoupper($sql) . "<BR>";
            $banco->exec_sql($sql);
            $banco->close_connection();    
            
        } //for
    } 

}
// Fim calculaRateios
}
//	END OF THE CLASS
?>
