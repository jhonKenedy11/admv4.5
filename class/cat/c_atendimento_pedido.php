<?php

/**
 * @package   astec
 * @name      c_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      29/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");


Class c_atendimento_pedido extends c_use r {

    /**
     * TABLE NAME CAT_ATENDIMENTO
     */
    private $id = NULL; 
    private $numAtendimento = NULL;  // ATENDIMENTO
    private $cliente = NULL; 
    private $clienteNome = NULL; 
    private $contato 			= NULL;
    private $dataAberturaEnd 	= NULL;
    private $dataFechamentoEnd 	= NULL;
    private $usrFatura 		= NULL;
    private $prioridade 		= NULL;
    private $prazoEntrega 		= NULL;
    private $descEquipamento 	= NULL;
    private $kmEntrada 			= NULL;
    private $obs 				= NULL;
    private $obsServicos 		= NULL;
    private $solucao = NULL;
    private $valorPecas = NULL;
    private $valorServicos = NULL;
    private $valorVisita = NULL;
    private $valorDesconto = NULL;
    private $valorTotal = NULL;
    private $tipoCobranca = NULL;
    private $condPgto = NULL;
    private $conta = NULL;
    private $genero = NULL;
    private $centroCusto = NULL;
    private $catSituacao = NULL;
    private $catEquipamentoId = NULL;
    private $catTipoId = NULL;

    //CAT_PECAS

    private $idPecas = NULL; 
    private $idAtendimentoPecas = NULL;  
    private $codProduto = NULL;  
    private $codProdutoNota = NULL;  
    private $quantidadePecas = NULL; 
    private $unidadePecas = NULL; 
    private $valorUnitarioPecas 			= NULL;
    private $descricaoPecas 	= NULL;
    private $valorCustoPecas 	= NULL;
    private $valorDescontoPecas 		= NULL;
    private $percDescontoPecas 		= NULL;
    private $acrescimoPecas 		= NULL;
    private $valorTotalPecas = NULL;
    private $especie = NULL;



    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }

    function setAtendimento($numAtendimento) { $this->numAtendimento = $numAtendimento; }
    function getAtendimento() { return $this->numAtendimento; }


    function setCliente($cliente) { $this->cliente = $cliente; }
    function getCliente() { return $this->cliente; }

    function setClienteNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->clienteNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
    function getClienteNome() { return $this->clienteNome; }   

    function setContato($contato) { $this->contato = $contato; }
    function getContato() { return $this->contato; }

    function setContatoNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->contatoNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
    function getContatoNome() { return $this->contatoNome; }   
    
     

    function setUsrFatura($usrFatura) {
        $this->usrFatura = $usrFatura;
        }
    function getUsrFatura() { 
        return $this->usrFatura; 
    }

    function setEspecie($especie) {
        $this->especie = $especie;
        }
    function getEspecie() { 
        return $this->especie; 
    }
   
    function setPrazoEntrega($prazoEntrega) { $this->prazoEntrega = $prazoEntrega; }
    function getPrazoEntrega($format = NULL) {
        if($this->prazoEntrega == ''){
            return $this->prazoEntrega;
        }else{        
            if($format == 'B'){
                if($this->prazoEntrega == ''){
                    return '';
                }else{
                    $formatedValue = c_date::convertDateTxt($this->prazoEntrega);
                    return $formatedValue;
                }
            }else if($format == 'F'){
                if($this->prazoEntrega == ''){
                    return '';
                }else{
                    $aux = strtr($this->prazoEntrega, "/","-");
                    $formatedValue = date('d/m/Y', strtotime($aux));
                    return $formatedValue;
                }
            }else{
                return $this->prazoEntrega;

            }
        }
    }   

    function setObs($obs) { $this->obs = $obs; }
    function getObs() { return $this->obs; }    

    function setTotalPecasUtilizada($valorPecas, $format=false) {
        $this->valorPecas = $valorPecas; 
        if ($format):
                $this->valorPecas = number_format($this->valorPecas, 2, ',', '.');
        endif;
        
    }
    
    function getTotalPecasUtilizada($format = NULL) {
        if (!empty($this->valorPecas)) {
            if ($format == 'F') {
                return number_format($this->valorPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorPecas);
            }
        } else {
            return 0;
        }        
    }

    function setValorFrete($frete, $format=false) {
        $this->frete = $frete; 
        if ($format):
                $this->frete = number_format($this->frete, 2, ',', '.');
        endif;
        
    }
    
    function getValorFrete($format = NULL) {
        if (!empty($this->frete)) {
            if ($format == 'F') {
                return number_format($this->frete, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->frete);
            }
        } else {
            return 0;
        }        
    }

    function setDespAcessorias($frete, $format=false) {
        $this->frete = $frete; 
        if ($format):
                $this->frete = number_format($this->frete, 2, ',', '.');
        endif;
        
    }
    
    function getDespAcessorias($format = NULL) {
        if (!empty($this->frete)) {
            if ($format == 'F') {
                return number_format($this->frete, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->frete);
            }
        } else {
            return 0;
        }        
    }
   

    function setValorTotal($valorTotal, $format=false) {
        $this->valorTotal = $valorTotal; 
        if ($format):
                $this->valorTotal = number_format($this->valorTotal, 2, ',', '.');
        endif;
        
    }
    
    function getValorTotal($format = NULL) {
        if (!empty($this->valorTotal)) {
            if ($format == 'F') {
                return number_format($this->valorTotal, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorTotal);
            }
        } else {
            return 0;
        }        
    }


    function setCondPgto($condPgto) {
        $this->condPgto = $condPgto;
        }
    function getCondPgto() { 
        return $this->condPgto; 
    }
    
    function setConta($conta) {
        $this->conta = $conta;
        }
    function getConta() { 
        return $this->conta; 
    }

    function setGenero($genero) {
        $this->genero = $genero;
        }
    function getGenero() { 
        return $this->genero; 
    }   
    
    function setCentroCusto($centroCusto) { $this->centroCusto = $centroCusto; }
    function getCentroCusto() { return $this->centroCusto; }

  

   //=================PECAS========================

   

   function setCodProduto($codProduto) { $this->codProduto = $codProduto; }
   function getCodProduto() { return $this->codProduto; }

   function setCodProdutoNota($codProdutoNota) { $this->codProdutoNota = $codProdutoNota; }
   function getCodProdutoNota() { return $this->codProdutoNota; }

   function setQuantidadePecas($quantidadePecas, $format=false) {
        $this->quantidadePecas = $quantidadePecas; 
        if ($format):
                $this->quantidadePecas = number_format($this->quantidadePecas, 2, ',', '.');
        endif;
        
    }

    function getQuantidadePecas($format = NULL) {
        if (!empty($this->quantidadePecas)) {
            if ($format == 'F') {
                return number_format($this->quantidadePecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->quantidadePecas);
            }
        } else {
            return 0;
        }        
    }

   function setUnidadePecas($unidadePecas) { $this->unidadePecas = $unidadePecas; }
   function getUnidadePecas() { return $this->unidadePecas; }

   function setVlrUnitarioPecas($valorUnitarioPecas, $format=false) {
    $this->valorUnitarioPecas = $valorUnitarioPecas; 
        if ($format):
                $this->valorUnitarioPecas = number_format($this->valorUnitarioPecas, 2, ',', '.');
        endif;
        
    }

    function getVlrUnitarioPecas($format = NULL) {
        if (!empty($this->valorUnitarioPecas)) {
            if ($format == 'F') {
                return number_format($this->valorUnitarioPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorUnitarioPecas);
            }
        } else {
            return 0;
        }        
    }

   function setDescricaoPecas($descricaoPecas) { $this->descricaoPecas = $descricaoPecas; }
   function getDescricaoPecas() { return $this->descricaoPecas; }

   function setVlrCustoPecas($valorCustoPecas, $format=false) {
    $this->valorCustoPecas = $valorCustoPecas; 
        if ($format):
                $this->valorCustoPecas = number_format($this->valorCustoPecas, 2, ',', '.');
        endif;
        
    }

    function getVlrCustoPecas($format = NULL) {
        if (!empty($this->valorCustoPecas)) {
            if ($format == 'F') {
                return number_format($this->valorCustoPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorCustoPecas);
            }
        } else {
            return 0;
        }        
    }

    function setDescontoItem($valorDescontoItem, $format=false) {
        $this->valorDescontoItem = $valorDescontoItem; 
            if ($format):
                    $this->valorDescontoItem = number_format($this->valorDescontoItem, 2, ',', '.');
            endif;
            
        }
    
    function getDescontoItem($format = NULL) {
        if (!empty($this->valorDescontoItem)) {
            if ($format == 'F') {
                return number_format($this->valorDescontoItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorDescontoItem);
            }
        } else {
            return 0;
        }        
    }

    function setPercDescontoItem($percDescontoItem, $format=false) {
        $this->percDescontoItem = $percDescontoItem; 
            if ($format):
                    $this->percDescontoItem = number_format($this->percDescontoItem, 2, ',', '.');
            endif;
            
        }
    
    function getPercDescontoItem($format = NULL) {
        if (!empty($this->percDescontoItem)) {
            if ($format == 'F') {
                return number_format($this->percDescontoItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percDescontoItem);
            }
        } else {
            return 0;
        }        
    }


    function setAcrescimoPecas($acrescimoPecas, $format=false) {
        $this->acrescimoPecas = $acrescimoPecas; 
            if ($format):
                    $this->acrescimoPecas = number_format($this->acrescimoPecas, 2, ',', '.');
            endif;
            
    }
        
    function getAcrescimoPecas($format = NULL) {
        if (!empty($this->acrescimoPecas)) {
            if ($format == 'F') {
                return number_format($this->acrescimoPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->acrescimoPecas);
            }
        } else {
            return 0;
        }        
    }
    function setTotalPecas($valorTotalPecas, $format=false) {
        $this->valorTotalPecas = $valorTotalPecas; 
            if ($format):
                    $this->valorTotalPecas = number_format($this->valorTotalPecas, 2, ',', '.');
            endif;
        
    }
            
    function getTotalPecas($format = NULL) {
        if (!empty($this->valorTotalPecas)) {
            if ($format == 'F') {
                return number_format($this->valorTotalPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorTotalPecas);
            }
        } else {
            return 0;
        }        
    }
   
   //===============FIM-PECAS=========================
   

    // PEDIDO GET SET 

    function setPedido($pedido) { $this->pedido = $pedido; }
    function getPedido() { 
        return isset($this->pedido) ? $this->pedido : 'NULL';  }

    function setSituacao($situacao) { $this->situacao = $situacao; }
    function getSituacao() { return $this->situacao; }

    function setEmissao($emissao) { $this->emissao = $emissao; }
    function getEmissao($format = NULL) {
        return c_date::formatDateTime($format, $this->emissao, false);
    }

    function setHoraEmissao($horaEmissao) { $this->horaEmissao = $horaEmissao; }
    function getHoraEmissao($format = NULL) {
        switch ($format) {
            case 'F':
                return date('H:m:s', strtotime($this->horaEmissao));
                break;
            default:
                return $this->horaEmissao;
        }
    }

    function setNrItem($nrItem) { $this->nrItem = $nrItem; }
    function getNrItem() { return $this->nrItem; }


   /**
     * METODOS DE SETS E GETS FAT_PEDIDO_ITEM
     */
    
    function setCodigoNota($codigoNota) { $this->codigoNota = $codigoNota; }
    function getCodigoNota() { return $this->codigoNota; }

    function setItemEstoque($itemEstoque) { $this->itemEstoque = $itemEstoque; }
    function getItemEstoque() { return $this->itemEstoque; }

    function setItemFabricante($itemFabricante) { $this->itemFabricante = $itemFabricante; }
    function getItemFabricante() { return $this->itemFabricante;  }

    function setQtSolicitada($qtSolicitada, $format = false) { 
        $this->qtSolicitada = $qtSolicitada;   
        if ($format):
            $this->qtSolicitada = number_format($this->qtSolicitada, 2, ',', '.');
        endif;
    }
    function getQtSolicitada($format = NULL) {
        if (!empty($this->qtSolicitada)) {
            if ($format == 'F') {
                return number_format($this->qtSolicitada, 2, ',', '.');
            } else {
                $num = str_replace('.', '', $this->qtSolicitada);
				$num = str_replace(',', '.', $num);
                return $num;                
                //return c_tools::moedaBd($this->unitario);
            }
        } else {
            return 0;
        }
    }

    function setUnitario($unitario, $format=false) { 
        $this->unitario = $unitario; 
        if ($format):
            $this->unitario = number_format($this->unitario, 2, ',', '.');
        endif; 
    }
    function getUnitario($format = NULL) {
        if (!empty($this->unitario)) {
            if ($format == 'F') {
                return number_format($this->unitario, 2, ',', '.');
            } else {
                $num = str_replace('.', '', $this->unitario);
				$num = str_replace(',', '.', $num);
                return $num;                
                //return c_tools::moedaBd($this->unitario);
            }
        } else {
            return 0;
        }
    }
    
    function setPrecoPromocao($precoPromocao) { $this->precoPromocao = $precoPromocao;  }
    function getPrecoPromocao($format = NULL) {
        if (!empty($this->precoPromocao)) {
            if ($format == 'F') {
                return number_format($this->precoPromocao, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->precoPromocao);
            }
        } else {
            return 'NULL';
        }
    }

    function setVlrTabela($vlrTabela) { $this->vlrTabela = $vlrTabela; }
    function getVlrTabela($format = NULL) {
        if (!empty($this->vlrTabela)) {
            if ($format == 'F') {
                return number_format($this->vlrTabela, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->vlrTabela);
            }
        } else {
            return 'NULL';
        }
    }

    function setTotalItem() {
//        if ($this->getPrecoPromocao() != 0):
//            $this->setUnitario($this->getPrecoPromocao('B'));
//        endif;
        $this->totalItem = str_replace('.', ',', ($this->getQtSolicitada('B') * $this->getUnitario('B')));
    }
    function getTotalItem($format = NULL) {
        if (!empty($this->totalItem)) {
            if ($format == 'F') {
                return number_format($this->totalItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->totalItem);
            }
        } else {
            return 0;
        }
    }

    function setGrupoEstoque($grupoEstoque) { $this->grupoEstoque = $grupoEstoque; }
    function getGrupoEstoque() { return $this->grupoEstoque; }


    function setDescricaoItem($descricaoItem) { $this->descricaoItem = $descricaoItem;}    
    function getDescricaoItem() { return $this->descricaoItem; }

    //===============================================

    public function setCusto($custo, $format=false) {
        $this->custo = $custo;
        if ($format):
            $this->custo = number_format($this->custo, 2, ',', '.');
        endif;        
    }
	
    public function getCusto($format = null) {
        if (isset($this->custo)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->custo);
                    break;
                case 'F':
                    return number_format((double) $this->custo, 2, ',', '.');
                    break;
                default :
                    return $this->custo;
            }
        else:
            return 0;            
        endif;        
    }     

	public function setDespesas($despesas, $format=false) {
        $this->despesas = $despesas;
        if ($format):
            $this->despesas = number_format($this->despesas, 2, ',', '.');
        endif;    
    }
	
    public function getDespesas($format = null) {
        if (!empty($this->despesas)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->despesas);
                    break;
                case 'F':
                    return number_format((double) $this->despesas, 2, ',', '.');
                    break;
                default :
                    return $this->despesas;
            }
        else:
            return 0;            
        endif;        
    } 

    public function setLucroBruto($lucrobruto, $format=false) {
        $this->lucrobruto = $lucrobruto;
        if ($format):
            $this->lucrobruto = number_format($this->lucrobruto, 2, ',', '.');
        endif;     
    }
	
    public function getLucroBruto($format = null) {
        if (!empty($this->lucrobruto)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->lucrobruto);
                    break;
                case 'F':
                    return number_format((double) $this->lucrobruto, 2, ',', '.');
                    break;
                default :
                    return $this->lucrobruto;
            }
        else:
            return 0;            
        endif;        
    }            

    public function setMargemLiquida($margemliquida, $format=false) {
        $this->margemliquida = $margemliquida;
        if ($format):
            $this->margemliquida = number_format($this->margemliquida, 2, ',', '.');
        endif;
    }

    public function getMargemLiquida($format = null) {
        if (!empty($this->margemliquida)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->margemliquida);
                    break;
                case 'F':
                    return number_format((double) $this->margemliquida, 2, ',', '.');
                    break;
                default :
                    return $this->margemLiquida;
            }
        else:
            return 0;            
        endif;        
    }        

    public function setMarkup($markup, $format=false) {
        $this->markup = $markup;
        if ($format):
            $this->markup = number_format($this->markup, 2, ',', '.');
        endif;
        
    }
    
    public function getMarkup($format = null) {
        if (!empty($this->markup)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->markup);
                    break;
                case 'F':
                    return number_format((double) $this->markup, 2, ',', '.');
                    break;
                default :
                    return $this->markup;
            }
        else:
            return 0;            
        endif;        
    }     

	public function setBcIcms($bcIcms, $format=false) {
        $this->bcIcms = $bcIcms;
        if ($format):
            $this->bcIcms = number_format($this->bcIcms, 2, ',', '.');
        endif;
        
    }
    
    public function getBcIcms($format = null) {
        if (!empty($this->bcIcms)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->bcIcms);
                    break;
                case 'F':
                    return number_format((double) $this->bcIcms, 2, ',', '.');
                    break;
                default :
                    return $this->bcIcms;
            }
        else:
            return 0;            
        endif;        
    }     
    
    public function setAliqIcms($aliqIcms, $format=false) {
        $this->aliqIcms = $aliqIcms;
        if ($format):
            $this->aliqIcms = number_format($this->aliqIcms, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqIcms($format = null) {
        if (!empty($this->aliqIcms)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->aliqIcms);
                    break;
                case 'F':
                    return number_format((double) $this->aliqIcms, 2, ',', '.');
                    break;
                default :
                    return $this->aliqIcms;
            }
        else:
            return 0;            
        endif;        
    }
    public function setValorIcms($vlIcms, $format=false) {
        $this->vlIcms = $vlIcms;
        if ($format):
            $this->vlIcms = number_format($this->vlIcms, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcms($format = null) {
        if (!empty($this->vlIcms)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcms);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcms, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcms;
            }
        else:
            return 0;            
        endif;        
    }     
	
	
    public function setValorIcmsDiferido($vlIcmsDiferido, $format=false) {
        $this->vlIcmsDiferido = $vlIcmsDiferido;
        if ($format):
            $this->vlIcmsDiferido = number_format($this->vlIcmsDiferido, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsDiferido($format = null) {
        if (!empty($this->vlIcmsDiferido)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcmsDiferido);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcmsDiferido, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcmsDiferido;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setValorIcmsOperacao($vlIcmsOperacao, $format=false) {
        $this->vlIcmsOperacao = $vlIcmsOperacao;
        if ($format):
            $this->vlIcmsOperacao = number_format($this->vlIcmsOperacao, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsOperacao($format = null) {
        if (!empty($this->vlIcmsOperacao)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcmsOperacao);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcmsOperacao, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcmsOperacao;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setValorBcSt($vlBcSt, $format=false) {
        $this->vlBcSt = $vlBcSt;
        if ($format):
            $this->vlBcSt = number_format($this->vlBcSt, 2, ',', '.');
        endif;
        
    }
    
    public function getValorBcSt($format = null) {
        if (!empty($this->vlBcSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlBcSt);
                    break;
                case 'F':
                    return number_format((double) $this->vlBcSt, 2, ',', '.');
                    break;
                default :
                    return $this->vlBcSt;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setValorIcmsSt($vlIcmsSt, $format=false) {
        $this->vlIcmsSt = $vlIcmsSt;
        if ($format):
            $this->vlIcmsSt = number_format($this->vlIcmsSt, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsSt($format = null) {
        if (!empty($this->vlIcmsSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcmsSt);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcmsSt, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcmsSt;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setMvaSt($mvaSt, $format=false) {
        $this->mvaSt = $mvaSt;
        if ($format):
            $this->mvaSt = number_format($this->mvaSt, 2, ',', '.');
        endif;
        
    }
    
    public function getMvaSt($format = null) {
        if (!empty($this->mvaSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->mvaSt);
                    break;
                case 'F':
                    return number_format((double) $this->mvaSt, 2, ',', '.');
                    break;
                default :
                    return $this->mvaSt;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setAliqIcmsSt($aliqIcmsSt, $format=false) {
        $this->aliqIcmsSt = $aliqIcmsSt;
        if ($format):
            $this->aliqIcmsSt = number_format($this->aliqIcmsSt, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqIcmsSt($format = null) {
        if (!empty($this->aliqIcmsSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->aliqIcmsSt);
                    break;
                case 'F':
                    return number_format((double) $this->aliqIcmsSt, 2, ',', '.');
                    break;
                default :
                    return $this->aliqIcmsSt;
            }
        else:
            return 0;            
        endif;        
    } 


    public function setAliqRedBCST($percReduacaoBcSt, $format=false) {
        $this->percReduacaoBcSt = $percReduacaoBcSt;
        if ($format):
            $this->percReduacaoBcSt = number_format($this->percReduacaoBcSt, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqRedBCST($format = null) {
        if (!empty($this->percReduacaoBcSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->percReduacaoBcSt);
                    break;
                case 'F':
                    return number_format((double) $this->percReduacaoBcSt, 2, ',', '.');
                    break;
                default :
                    return $this->percReduacaoBcSt;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setBcIcmsUfDest($vlbcIcmsUfDest, $format=false) {
        $this->vlbcIcmsUfDest = $vlbcIcmsUfDest;
        if ($format):
            $this->vlbcIcmsUfDest = number_format($this->vlbcIcmsUfDest, 2, ',', '.');
        endif;
        
    }
    
    public function getBcIcmsUfDest($format = null) {
        if (!empty($this->vlbcIcmsUfDest)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlbcIcmsUfDest);
                    break;
                case 'F':
                    return number_format((double) $this->vlbcIcmsUfDest, 2, ',', '.');
                    break;
                default :
                    return $this->vlbcIcmsUfDest;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setValorIcmsUfDest($vlIcmsUfDest, $format=false) {
        $this->vlIcmsUfDest = $vlIcmsUfDest;
        if ($format):
            $this->vlIcmsUfDest = number_format($this->vlIcmsUfDest, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsUfDest($format = null) {
        if (!empty($this->vlIcmsUfDest)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcmsUfDest);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcmsUfDest, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcmsUfDest;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setAliqIcmsUfDest($aliqFcpSt, $format=false) {
        $this->aliqFcpSt = $aliqFcpSt;
        if ($format):
            $this->aliqFcpSt = number_format($this->aliqFcpSt, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqIcmsUfDest($format = null) {
        if (!empty($this->aliqFcpSt)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->aliqFcpSt);
                    break;
                case 'F':
                    return number_format((double) $this->aliqFcpSt, 2, ',', '.');
                    break;
                default :
                    return $this->aliqFcpSt;
            }
        else:
            return 0;            
        endif;        
    } 	
	
    public function setAliqIcmsInter($aliqIcmsInter, $format=false) {
        $this->aliqIcmsInter = $aliqIcmsInter;
        if ($format):
            $this->aliqIcmsInter = number_format($this->aliqIcmsInter, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqIcmsInter($format = null) {
        if (!empty($this->aliqIcmsInter)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->aliqIcmsInter);
                    break;
                case 'F':
                    return number_format((double) $this->aliqIcmsInter, 2, ',', '.');
                    break;
                default :
                    return $this->aliqIcmsInter;
            }
        else:
            return 0;            
        endif;        
    } 
	
    public function setAliqIcmsInterPart($aliqIcmsInterPart, $format=false) {
        $this->aliqIcmsInterPart = $aliqIcmsInterPart;
        if ($format):
            $this->aliqIcmsInterPart = number_format($this->aliqIcmsInterPart, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqIcmsInterPart($format = null) {
        if (!empty($this->aliqIcmsInterPart)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->aliqIcmsInterPart);
                    break;
                case 'F':
                    return number_format((double) $this->aliqIcmsInterPart, 2, ',', '.');
                    break;
                default :
                    return $this->aliqIcmsInterPart;
            }
        else:
            return 0;            
        endif;        
    } 
	
	
    public function setFcpUfDest($vlFcpUfDest, $format=false) {
        $this->vlFcpUfDest = $vlFcpUfDest;
        if ($format):
            $this->vlFcpUfDest = number_format($this->vlFcpUfDest, 2, ',', '.');
        endif;
        
    }
    
    public function getFcpUfDest($format = null) {
        if (!empty($this->vlFcpUfDest)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlFcpUfDest);
                    break;
                case 'F':
                    return number_format((double) $this->vlFcpUfDest, 2, ',', '.');
                    break;
                default :
                    return $this->vlFcpUfDest;
            }
        else:
            return 0;            
        endif;        
    } 
	
    /*
    
    public function setValorIcmsUfDest($vlDifal, $format=false) {
        $this->vlDifal = $vlDifal;
        if ($format):
            $this->vlDifal = number_format($this->vlDifal, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsUfDest($format = null) {
        if (!empty($this->vlDifal)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlDifal);
                    break;
                case 'F':
                    return number_format((double) $this->vlDifal, 2, ',', '.');
                    break;
                default :
                    return $this->vlDifal;
            }
        else:
            return 0;            
        endif;        
    } 

    */
	
	
    public function setValorIcmsUFRemet($vlIcmsUFRemet, $format=false) {
        $this->vlIcmsUFRemet = $vlIcmsUFRemet;
        if ($format):
            $this->vlIcmsUFRemet = number_format($this->vlIcmsUFRemet, 2, ',', '.');
        endif;
        
    }
    
    public function getValorIcmsUFRemet($format = null) {
        if (!empty($this->vlIcmsUFRemet)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->vlIcmsUFRemet);
                    break;
                case 'F':
                    return number_format((double) $this->vlIcmsUFRemet, 2, ',', '.');
                    break;
                default :
                    return $this->vlIcmsUFRemet;
            }
        else:
            return 0;            
        endif;        
    }    
    
    function setTribIcms($tribicms) { $this->tribicms = $tribicms; }
    function getTribIcms() { return $this->tribicms; }

    function setCsosn($csosn) { $this->csosn = $csosn; }
    function getCsosn() { return $this->csosn; }
    
    function setCfop($cfop) { $this->cfop = $cfop; }
    function getCfop() { return $this->cfop; }
    
    function setOrigem($origem) { $this->origem = $origem; }
    function getOrigem() { return $this->origem; }

    //===============================   PECAS =================================== 

    public function select_pecas_atendimento() {
        $sql = "SELECT T.CODFABRICANTE, P.*, OC.SITUACAO as SITUACAO_OC FROM CAT_AT_PECAS P ";
        $sql .= "INNER JOIN EST_PRODUTO T ON  T.CODIGO=P.CODPRODUTO ";
        $sql .= "LEFT JOIN EST_ORDEM_COMPRA OC ON OC.ID=P.OC_ID ";
        $sql .= "WHERE (CAT_ATENDIMENTO_ID = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//=========================================================================
//============================== CAT_ATENDIMENTO ==========================
//=========================================================================
    
    /**
     * @author Tony
     * Consulta para o Banco atraves do id
     * @name select_atendimento_id
     * @return ARRAY todos os campos da table com seus relacionamentos
     * @version 20210316 - Ticket
     * @author Márcio Sérgio
     */
    public function select_atendimento($id) {

    $sql  = "SELECT A.*, S.DESCRICAO AS DESCSITUACAO, T.DESCRICAO AS DESCTIPO
                    ,P.DESCRICAO AS DESCCONDPGTO , A.DESCEQUIPAMENTO AS EQUIPAMENTO
                    ,C.NOME, C.NOMEREDUZIDO, C.TIPOEND, C.TITULOEND, C.ENDERECO
                    ,C.NUMERO, C.COMPLEMENTO, C.BAIRRO, C.CIDADE, C.UF, C.CEP, C.PESSOA 
                    ,C.FONEAREA, C.FONE, C.EMAIL, C.CNPJCPF,  U.NOMEREDUZIDO AS USERABERTURA, ";  
    $sql .= " IF ( CNPJCPF <> '', IF ";
    $sql .= " (PESSOA = 'J', CONCAT(SUBSTRING(cnpjcpf, 1,2), '.' , SUBSTRING(cnpjcpf, 3,3),'.', SUBSTRING(cnpjcpf, 6,3),'/',SUBSTRING(cnpjcpf, 9,4), ";
    $sql .= " '-',SUBSTRING(cnpjcpf, 13,2)), ";
    $sql .= " CONCAT(SUBSTRING(cnpjcpf, 1,3), '.' , SUBSTRING(cnpjcpf, 4,3),'.',SUBSTRING(cnpjcpf, 7,3),'-',SUBSTRING(cnpjcpf, 10,2)) ";
    $sql .= " ), '')  AS CNPJCPF ";  

    $sql .= "FROM CAT_ATENDIMENTO A ";
    $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=A.CLIENTE) ";
    $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = A.usrAbertura) ";
    $sql .= "LEFT JOIN FAT_COND_PGTO P ON (P.ID=A.CONDPGTO) ";
    $sql .= "LEFT JOIN CAT_SITUACAO S ON (S.ID=A.CAT_SITUACAO_ID) ";
    $sql .= "LEFT JOIN CAT_TIPO T ON (T.ID=A.CAT_TIPO_ID) ";
    $sql .= "WHERE (A.ID = ".$id.") ";

    //echo $sql;
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;

}

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    /**
     * Funcao para incluir atendimento
     * @param INT ID Chave primaria da table cat_atendimento
     * @param conn id da conexão com o banco no caso de trasaction
     * @name incluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function incluiPedido($conn=null) {

        $banco = new c_banco;
        // $banco->sqlStrtoupper = false;

        $sql = "INSERT INTO FAT_PEDIDO (";
        $sql .= "cliente,  situacao, especie, emissao, horaemissao, condpg,  ";
        $sql .= " frete, DESPACESSORIAS, TOTALPRODUTOS, total,   ";
        $sql .= " ccusto, centrocustoentrega,  obs,  usrfatura, USERINSERT, DATEINSERT) ";
        $sql .= "VALUES ('";
        $sql .=   $this->getCliente() . "','"
                . $this->getSituacao() . "', '"
                . $this->getEspecie() . "', '"
                . $this->getEmissao('B') . "', '"
                . date("H:i:s"). "', '"
                . $this->getCondPgto() . "', '"
                . $this->getValorFrete('B') . "', '"
                . $this->getDespAcessorias('B') . "', '"
                . $this->getTotalPecasUtilizada('B') . "', '"
                . $this->getValorTotal('B') . "', '"
                . $this->m_empresacentrocusto . "', '"
                . $this->m_empresacentrocusto . "', '"
                . $this->getObs() . "', '"
                . $this->getUsrFatura()."', '";
        $sql .= $this->m_userid."','".date("Y-m-d H:i:s"). "' );";
        //echo strtoupper($sql) . "<BR>";
        $result = $banco->exec_sql($sql, $conn);
        $lastReg = $banco->insertReg;
        $banco->close_connection();

        if ($result > 0) {
            return $lastReg;
        } else {
            return 'Os dados do atendimento ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    public function incluiPedidoItem($conn=null) {
        $banco = new c_banco;

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "id, nritem, itemestoque, itemfabricante, qtsolicitada, unitario, desconto, percdesconto, total, ";
        $sql .= "descricao, usrfatura, custo, despesas, lucrobruto, margemliquida, markup, codigonota ";
        
        $sql .=" ) ";
        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .= $this->getPedido() . "', '"
                . $this->getNrItem() . "', '"
                . $this->getItemEstoque() . "', '"
                . $this->getItemFabricante() . "', "
                . $this->getQtSolicitada('B') . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDescontoItem('B') . ", "
                . $this->getPercDescontoItem('B') . ", "
                . $this->getTotalItem('B') . ", '"
                . $this->getDescricaoItem() . "', "
                . $this->getUsrFatura() . ", "
                . $this->getCusto('B') . ", "
                . $this->getDespesas('B') . ", "
                . $this->getLucroBruto('B') . ", "                
                . $this->getMargemLiquida('B') . ", "
                . $this->getMarkUp('B') . ", '"
                . $this->getCodigoNota() . "'); ";
                
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }


    public function updateField($field, $valor, $tabela, $conn=null) {
        $sql = "UPDATE  ".$tabela;        
        $sql .= " SET ".$field." = '". $valor ."' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
    }
    
    




}

//=======================



//	END OF THE CLASS
?>