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

//Class c_pedidoVenda
Class c_pedido_aprovacao extends c_user {

    /**
     * TABLE NAME FAT_PEDIDO
     */
    private $id = NULL; // INT(11) PK, NN, AI
    private $cliente = NULL; // INT(11) NN
    private $clienteNome = NULL; // VARCHAR(50) NN
    private $pedido = NULL; // INT(11)
    private $situacao = NULL; // CHAR(11)
    private $emissao = NULL; // DATE
    private $entregador = NULL; // VARCHAR(25)
    private $usrFatura = NULL; // INT(11)
    private $idNatop = NULL; // INT(11)
    private $tabPreco = NULL; // VARCHAR(8)
    private $entradaTabPreco = NULL; // DECIMAL(9,2)
    private $taxaFin = NULL; // DECIMAL(5,2)
    private $condPg = NULL; // INT(11)
    private $entradaCondPg = NULL; // DECIMAL(9,2)
    private $vencimento1 = NULL; // DATE
    private $desconto = NULL; // DECIMAL(9,2)
    private $total = NULL; // DECIMAL(9,2)
    private $moeda = NULL; // SMALLINT(6)
    private $contaDeposito = NULL; // SMALLINT(6)
    private $especie = NULL; // CHAR(1)
    private $serie = NULL; // VARCHAR(3)
    private $horaEmissao = NULL; // TIME
    private $taxaEntrega = NULL; // DECIMAL(9,2)
    private $totalRecebido = NULL; // DECIMAL(9,2)
    private $dataEntrega = NULL; // DATE
    private $horaEntrega = NULL; // VARCHAR(20)
    private $genero = NULL; // VARCHAR(4)
    private $centroCusto = NULL; // INT(11)
    private $tipoEntrega = NULL; // CHAR(1)
    private $descEntrega = NULL; // 
    private $tabelaPreco = NULL; // VARCHAR(15)
    private $ipi = NULL; // DECIMAL(9,2)
    private $comprador = NULL; // VARCHAR(25)
    private $transportadora = NULL; // INT(11)
    private $tabelaVenda = NULL; // VARCHAR(30)
    private $usrPedido = NULL; // INT(11)
    private $dtUltimoPedidoCliente = NULL; // DATE
    private $usrAprovacao = NULL; // INT(11)
    private $perDesconto = NULL; // DECIMAL(9,2)
    private $descontoNf = NULL; // DECIMAL(11,2)
    private $totalProdutos = NULL; // DECIMAL(9,2)
    private $frete = NULL; // DECIMAL(9,2)
    private $dtValidade = NULL; // DATE
    private $dataAlteracao = NULL; // DATE
    private $obs = NULL; // TEXT
    private $odEsferico = NULL; // decimal(5,2)
    private $oeEsferico = NULL; // decimal(5,2)
    private $odCilindrico = NULL; // decimal(5,2)
    private $oeCilindrico = NULL; // decimal(5,2)
    private $odEixo = NULL; // decimal(5,2)
    private $oeEixo = NULL; // decimal(5,2)
    private $odAd = NULL; // decimal(5,2)
    private $oeAd = NULL; // decimal(5,2)
    private $medico = NULL; // varchar(60)

    private $despesaTotal = NULL; 
    private $custoTotal = NULL; 
    private $despAcessorias = NULL;
    private $descontoGeral = NULL;

    /*
     * TABLE FAT_PEDIDO_ITEM
     */
    private $nrItem = NULL; // SMALLINT(6)
    private $itemEstoque = NULL; // VARCHAR(25)
    private $itemFabricante = NULL; // VARCHAR(25)
    private $qtSolicitada = NULL; // DECIMAL(9,4)
    private $qtAtendida = NULL; // INT(11)
    private $atendimento = NULL; // DATE
    private $unitario = NULL; // DECIMAL(9,4)
    private $descontoItem = NULL; // DECIMAL(9,2)
    private $financeiro = NULL; // DECIMAL(9,2)
    private $totalItem = NULL; // DECIMAL(11,2)
    private $grupoEstoque = NULL; // VARCHAR(50)
    private $descricaoItem = NULL; // BLOB
    private $fabricanteItem = NULL; // INT(11)
    private $aliqIpi = NULL; // DECIMAL(5,2)
    private $peso = NULL; // DECIMAL(5,2)
    private $prazo = NULL; // INT(11)
    private $aliqDesconto = NULL; // DECIMAL(5,2)
    private $comissao = NULL; // DECIMAL(5,2)
    private $baseComissao = NULL; // DECIMAL(9,2)
    private $precoSelecionado = NULL; // VARCHAR(30)
    private $autorizDesconto = NULL; // VARCHAR(5)
    private $descontoMaxAConceder = NULL; // DECIMAL(11,2)
    private $percDesconto = NULL; // DECIMAL(5,2)
    private $precoPromocao = NULL; // CHAR(1)
    private $bonificado = NULL; // CHAR(1)
    private $descontoOutros = NULL; // DECIMAL(9,2)
    private $qtConferida = NULL; // DECIMAL(9,4)
    private $percFinanceiro = NULL; // DECIMAL(5,2)
    private $vlrTabela = NULL; // DECIMAL(11,4)

    private $custo = NULL;
    private $despesas = NULL;
    private $lucrobruto = NULL;
    private $margemliquida = NULL;
    private $markup = NULL;   

    private $bcIcms = NULL;
    private $aliqIcms = NULL;
    private $vlIcms = NULL;

    private $vlIcmsDiferido = NULL;
    private $vlIcmsOperacao = NULL;

    private $vlBcSt = NULL;
    private $vlIcmsSt = NULL;

    private $mvaSt = NULL;
    private $aliqIcmsSt = NULL;
    private $percReduacaoBcSt = NULL;  
    private $vlbcIcmsUfDest = NULL;
    private $bcFecoepUFDest = NULL;
    private $aliqFcpSt = NULL;          
    private $aliqIcmsInter = NULL;
    private $aliqIcmsInterPart = NULL;
    private $vlFcpUfDest = NULL;
    private $vlDifal = NULL;
    private $vlIcmsUFRemet = NULL;
    private $vlIcmsUfDest = NULL;

    private $tribicms = NULL;
    private $csosn = NULL;
    private $cfop = NULL;
    private $origem = NULL;

    private $credito = NULL;

    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);

    }

    /**
    * METODOS DE SETS E GETS
        $sql .= "cliente, pedido, situacao, emissao, entregador, idnatop, condpg, entradacondpg, ";
        $sql .= "desconto, total, moeda, contadeposito, especie, serie, horaemissao, ";
        $sql .= "genero, ccusto, obs, USERINSERT, DATEINSERT)";
    */


    public function __set($property, $value) {
        if (property_exists($this, $property)) {
          $this->$property = $value;
        }
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
    
    public function __setNumber($property, $value, $decimal, $format=false) {
        if (property_exists($this, $property)) {
            $this->$property = ($format ? number_format($value, $decimal, ',', '.') : $value);
        }    
    }
    
    public function __getNumber($property, $decimal, $format = null) {
        if (property_exists($this, $property)) {
            return ($format == null ? $this->$property : 
                ($format == 'F' ? number_format($this->$property, $decimal, ',', '.') : c_tools::moedaBd($this->$property)));
        } else { return null; }
    }

    public function __setDate($property, $value, $format=false) {
        if (property_exists($this, $property)) {
            $this->$property = ($format ? date('d/m/Y', strtotime($value)) : $value);
        }    
    }
    
    public function __getDate($property, $format = null) {
        if (property_exists($this, $property)) {
            $this->$property = strtr($this->$property, "/","-");
            return ($format == null ? $this->$property : 
                ($format == 'F' ? date('d/m/Y', strtotime($this->$property)) : c_date::convertDateBdSh($this->$property, $this->m_banco)));
        } else { return null; }
    }

    public function __setDateTime($property, $value, $format=false) {
        if (property_exists($this, $property)) {
            $this->$property = ($format ? date('d/m/Y H:i:s', strtotime($value)) : $value);
        }    
    }
    
    /**
     * <b> Funcao para retornar Data formatada para banco, apresentação ou null Usado: em todas classes GET </b>
     * @param datetime $property  valor original a ser formatado
     * @param char $format tipo do formato de retorno, F - formatação para form / B - formatação para banco / NULL - retorna conteudo original
     * @return datetime
     */
    public function __getDateTime($property, $format = null) {
        if (property_exists($this, $property)) {
            $this->$property = strtr($this->$property, "/","-");
            return ($format == null ? $this->$property : 
                ($format == 'F' ? date('d/m/Y H:i:s', strtotime($this->$property)) : c_date::convertDateBd($this->$property, $this->m_banco)));
        } else { return null; }
    }

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }


    function setCliente($cliente) { $this->cliente = $cliente; }
    function getCliente() { return $this->cliente; }

    function setClienteNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->__get('cliente'));
        $reg_nome = $pessoa->select_conta();
        $this->clienteNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
    function getClienteNome() { return $this->clienteNome; }
    function getTipoPessoa() { return $this->tipoPessoa; }
    function getUfPessoa() { return $this->ufPessoa; }

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

    function setEntregador($entregador) { $this->entregador = $entregador; }
    function getEntregador() { return $this->entregador; }

    function setIdNatop($idNatop) { $this->idNatop = $idNatop; }
    function getIdNatop()  { return isset($this->idNatop) ? $this->idNatop : 'NULL'; }

    function getNatOperacao() {
        $field = new c_banco();
        $field->setTab('EST_NAT_OP');
        return $field->getField('NATOPERACAO', 'id='.$this->getIdNatop());
    }

    function setCondPg($condPg) {
        $this->condPg = $condPg;
        }
    function getCondPg() { 
        //return $this->condPg; 
        return isset($this->condPg) ? $this->condPg : 0;
    }
    
    function setEntradaCondPg($entradaCondPg) { $this->entradaCondPg = $entradaCondPg; }
    function getEntradaCondPg($format = NULL) {
        if ($format == 'F') {
            return number_format($this->entradaCondPg, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->entradaCondPg);
        }
    }

    public function setDesconto($desconto, $format=false) {
        $this->desconto = $desconto;
        if ($format):
            $this->desconto = number_format($this->desconto, 2, ',', '.');
        endif;        
    }
    
    public function getDesconto($format = null) {
        if (isset($this->desconto)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->desconto);
                    break;
                case 'F':
                    return number_format((double) $this->desconto, 2, ',', '.');
                    break;
                default :
                    return $this->desconto;
            }
        else:
            return 0;            
        endif;        
    }

    function setDescontoGeral($descontoGeral, $format=false) {
        $this->descontoGeral = $descontoGeral; 
        if ($format):
                $this->descontoGeral = number_format($this->descontoGeral, 2, ',', '.');
        endif;
        
    }
    function getDescontoGeral($format = NULL) {
        if (!empty($this->descontoGeral)) {
            if ($format == 'F') {
                return number_format($this->descontoGeral, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->descontoGeral);
            }
        } else {
            return 0;
        }        
    }

    function setTaxaEntrega($taxaEntrega, $format=false) {
        $this->taxaEntrega = $taxaEntrega; 
        if ($format):
                $this->taxaEntrega = number_format($this->taxaEntrega, 2, ',', '.');
        endif;
        
    }
    function getTaxaEntrega($format = NULL) {
        if (!empty($this->taxaEntrega)) {
            if ($format == 'F') {
                return number_format($this->taxaEntrega, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->taxaEntrega);
            }
        } else {
            return 0;
        }        
    }

    
    function setTotal($total, $format=false) { 
        $this->total = $total; 
        if ($format):
                $this->total = number_format($this->total, 2, ',', '.');
        endif;
    }
    function getTotal($format = NULL) {
        if (!empty($this->total)) {
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->total);
                    break;
                case 'F':
                    return number_format((double) $this->total, 2, ',', '.');
                    break;
                default :
                    return $this->total;
            }
        } else {
            return 0;
        }        
    }

    function setTotalRecebido($totalRecebido, $format=false) { 
        $this->totalRecebido = $totalRecebido; 
        if ($format):
                $this->totalRecebido = number_format($this->totalRecebido, 2, ',', '.');
        endif;
    }
    function getTotalRecebido($format = NULL) {
        if (!empty($this->totalRecebido)) {
            if ($format == 'F') {
                return number_format($this->totalRecebido, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->totalRecebido);
            }
        } else {
            return 0;
        }        
    }

    function setTotalProdutos($totalProdutos, $format=false) { 
        $this->totalProdutos = $totalProdutos; 
        if ($format):
                $this->totalProdutos = number_format($this->totalProdutos, 2, ',', '.');
        endif;
    }
    function getTotalProdutos($format = NULL) {
        if (!empty($this->totalProdutos)) {
            if ($format == 'F') {
                return number_format($this->totalProdutos, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->totalProdutos);
            }
        } else {
            return 0;
        }        
    }
    
    
    function setMoeda($moeda) { $this->moeda = $moeda; }
    function getMoeda() { return isset($this->moeda) ? $this->moeda : 'NULL'; }

    function setVencimento1($vencimento1) { $this->vencimento1 = $vencimento1; }
    function getVencimento1($format = NULL) {
        return c_date::formatDateTime($format, $this->vencimento1, true);
    }

    function setContaDeposito($contaDeposito) { $this->contaDeposito = $contaDeposito; }
    function getContaDeposito() { return isset($this->contaDeposito) ? $this->contaDeposito : 'NULL'; }

    function getEspecie() { return $this->especie; }
    function setEspecie($especie) { $this->especie = $especie; }
    
    function setSerie($serie) { $this->serie = $serie; }
    function getSerie() { return $this->serie; }

    function setGenero($genero) { $this->genero = $genero; }
    function getGenero() { return $this->genero;  }

    function setCentroCusto($centroCusto) { $this->centroCusto = $centroCusto; }
    function getCentroCusto() { return $this->centroCusto; }

    function setObs($obs) { $this->obs = $obs; }
    function getObs() { return $this->obs; }
    
    function setOdEsferico($ode) {$this->odEsferico = $ode; }
    function getOdEsferico($format = NULL) {
                return $this->odEsferico;
    }
    
    function setOeEsferico($oee) {$this->oeEsferico = $oee; }
    function getOeEsferico($format = NULL) {
            return $this->oeEsferico;
    }

    function setOdCilindrico($odc) {$this->odCilindrico = $odc; }
    function getOdCilindrico($format = NULL) {
                return $this->odCilindrico;
    }
    
    function setOeCilindrico($oec) {$this->oeCilindrico = $oec; }
    function getOeCilindrico($format = NULL) {
                return $this->oeCilindrico;
    }

    function setOdEixo($odx) {$this->odEixo = $odx; }
    function getOdEixo($format = NULL) {
                return $this->odEixo;
    }
    
    function setOeEixo($oex) {$this->oeEixo = $oex; }
    function getOeEixo($format = NULL) {
                return $this->oeEixo;
    }
    
    function setOdAd($oda) {$this->odAd = $oda; }
    function getOdAd($format = NULL) {
                return $this->odAd;
    }
    
    function setOeAd($oea) {$this->oeAd = $oea; }
    function getOeAd($format = NULL) {
                return $this->oeAd;
    }
    
    function setMedico($medico) { $this->medico = $medico;}    
    function getMedico() { return $this->medico; }
    
    function setDataEntrega($dataEntrega) { $this->dataEntrega = $dataEntrega; }
    function getDataEntrega($format = NULL) {
        $this->dataEntrega = strtr($this->dataEntrega, "/","-");
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->dataEntrega)); 
				break;
			case 'B':
                return c_date::convertDateBd($this->dataEntrega, $this->m_banco);
				break;
			default:
				return $this->dataEntrega;
		}
    }

    public function setDespesaTotal($despesaTotal, $format=false) {
        $this->despesaTotal = $despesaTotal;
        if ($format):
            $this->despesaTotal = number_format($this->despesaTotal, 2, ',', '.');
        endif;
        
    }
    public function getDespesaTotal($format = null) {
        if (isset($this->despesaTotal)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->despesaTotal);
                    break;
                case 'F':
                    return number_format((double) $this->despesaTotal, 2, ',', '.');
                    break;
                default :
                    return $this->despesaTotal;
            }
        else:
            return 0;            
        endif;        
    }

    public function setCustoTotal($custoTotal, $format=false) {
        $this->custoTotal = $custoTotal;
        if ($format):
            $this->custoTotal = number_format($this->custoTotal, 2, ',', '.');
        endif;
        
    }
    public function getCustoTotal($format = null) {
        if (isset($this->custoTotal)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->custoTotal);
                    break;
                case 'F':
                    return number_format((double) $this->custoTotal, 2, ',', '.');
                    break;
                default :
                    return $this->custoTotal;
            }
        else:
            return 0;            
        endif;        
    }

    
    
    //############### FIM SETS E GETS FAT_PEDIDO ###############     
    //##########################################################     
    /**
     * METODOS DE SETS E GETS FAT_PEDIDO_ITEM
     */
    function setNrItem($nrItem) { $this->nrItem = $nrItem; }
    function getNrItem() { return $this->nrItem; }

    function setItemEstoque($itemEstoque) { $this->itemEstoque = $itemEstoque; }
    function getItemEstoque() { return $this->itemEstoque; }

    function setItemFabricante($itemFabricante) { $this->itemFabricante = $itemFabricante; }
    function getItemFabricante() { return $this->itemFabricante;  }

    function setQtSolicitada($qtSolicitada) { $this->qtSolicitada = $qtSolicitada;   }
    function getQtSolicitada($format = NULL) {
        if (!empty($this->qtSolicitada)) {
            return $this->qtSolicitada;
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
        $this->totalItem = str_replace('.', ',', ($this->getQtSolicitada() * $this->getUnitario('B')) - $this->getDesconto('B'));
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




    

//////// pedido não utilizado =================================================            
    
    function setUsrFatura($usrFatura) { $this->usrFatura = $usrFatura; }
    function getUsrFatura() {return isset($this->usrFatura) ? $this->usrFatura : 'NULL'; }

    function setTabPreco($tabPreco) { $this->tabPreco = $tabPreco; }
    function getTabPreco() { return $this->tabPreco; }

    function setEntradaTabPreco($entradaTabPreco) { $this->entradaTabPreco = $entradaTabPreco; }
    function getEntradaTabPreco($format = NULL) {
        if ($format == 'F') {
            return number_format($this->entradaTabPreco, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->entradaTabPreco);
        }
    }


    function getFormaPgto() {
        $field = new c_banco();
        $field->setTab('FAT_COND_PGTO');
        return $field->getField('FORMAPGTO', 'ID='.$this->getCondPg());
    }

    function setTaxaFin($taxaFin) { $this->taxaFin = $taxaFin; }
    function getTaxaFin($format = NULL) {
        if ($format == 'F') {
            return number_format($this->taxaFin, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->taxaFin);
        }
    }



    function getHoraEntrega() {
        return $this->horaEntrega;
    }


    function getTipoEntrega() {
        return $this->tipoEntrega;
    }

    function getDescEntrega() {
        return $this->descEntrega;
    }

    function getTabelaPreco() {
        return $this->tabelaPreco;
    }

    function getIpi($format = NULL) {
        if ($format == 'F') {
            return number_format($this->ipi, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->ipi);
        }
    }

    function getComprador() {
        return $this->comprador;
    }

    function getTransportadora() {
        return $this->transportadora;
    }

    function getTabelaVenda() {
        return $this->tabelaVenda;
    }

    function getUsrPedido() {
        return $this->usrPedido;
    }

    function getDtUltimoPedidoCliente($format = NULL) {
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dtUltimoPedidoCliente));
                break;
            case 'B':
                return c_date::convertDateBdSh($this->dtUltimoPedidoCliente, $this->m_banco);
                break;
            default:
                return $this->dtUltimoPedidoCliente;
        }
    }

    function getUsrAprovacao() { return isset($this->usrAprovacao) ? $this->usrAprovacao : 'NULL'; }

    function getPerDesconto($format = NULL) {
        if ($format == 'F') {
            return number_format($this->perDesconto, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->perDesconto);
        }
    }

    function getDescontoNf($format = NULL) {
        if ($format == 'F') {
            return number_format($this->descontoNf, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->descontoNf);
        }
    }

    function getDtValidade($format = NULL) {
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dtValidade));
                break;
            case 'B':
                return c_date::convertDateBdSh($this->dtValidade, $this->m_banco);
                break;
            default:
                return $this->dtValidade;
        }
    }

    function getDataAlteracao($format = NULL) {
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dataAlteracao));
                break;
            case 'B':
                return c_date::convertDateBdSh($this->dataAlteracao, $this->m_banco);
                break;
            default:
                return $this->dataAlteracao;
        }
    }
    public function setDespAcessorias($despAcessorias, $format=false) {
        $this->despAcessorias = $despAcessorias;
        if ($format):
            $this->despAcessorias = number_format($this->despAcessorias, 2, ',', '.');
        endif;
        
    }
    
    public function getDespAcessorias($format = null) {
        if (isset($this->despAcessorias)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->despAcessorias);
                    break;
                case 'F':
                    return number_format((double) $this->despAcessorias, 2, ',', '.');
                    break;
                default :
                    return $this->despAcessorias;
            }
        else:
            return 0;            
        endif;        
    }

    public function setCredito($credito, $format=false) {
        $this->credito = $credito;
        if ($format):
            $this->credito = number_format($this->credito, 2, ',', '.');
        endif;        
    }
    
    public function getCredito($format = null) {
        if (isset($this->credito)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->credito);
                    break;
                case 'F':
                    return number_format((double) $this->credito, 2, ',', '.');
                    break;
                default :
                    return $this->credito;
            }
        else:
            return 0;            
        endif;        
    }

    



/// PEDIDO - SET   




    function setHoraEntrega($horaEntrega) {
        $this->horaEntrega = $horaEntrega;
    }

    function setTipoEntrega($tipoEntrega) {
        $this->tipoEntrega = $tipoEntrega;
    }

    function setDescEntrega($descEntrega) {
        $this->descEntrega = $descEntrega;
    }

    function setTabelaPreco($tabelaPreco) {
        $this->tabelaPreco = $tabelaPreco;
    }

    function setIpi($ipi) {
        $this->ipi = $ipi;
    }

    function setComprador($comprador) {
        $this->comprador = $comprador;
    }

    function setTransportadora($transportadora) {
        $this->transportadora = $transportadora;
    }

    function setTabelaVenda($tabelaVenda) {
        $this->tabelaVenda = $tabelaVenda;
    }

    function setUsrPedido($usrPedido) {
        $this->usrPedido = $usrPedido;
    }

    function setDtUltimoPedidoCliente($dtUltimoPedidoCliente) {
        $this->dtUltimoPedidoCliente = $dtUltimoPedidoCliente;
    }

    function setUsrAprovacao($usrAprovacao) {
        $this->usrAprovacao = $usrAprovacao;
    }

    function setPerDesconto($perDesconto) {
        $this->perDesconto = $perDesconto;
    }

    function setDescontoNf($descontoNf) {
        $this->descontoNf = $descontoNf;
    }

    public function setFrete($frete, $format=false) {
        $this->frete = $frete;
        if ($format):
            $this->frete = number_format($this->frete, 2, ',', '.');
        endif;
        
    }
    
    public function getFrete($format = null) {
        if (isset($this->frete)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->frete);
                    break;
                case 'F':
                    return number_format((double) $this->frete, 2, ',', '.');
                    break;
                default :
                    return $this->frete;
            }
        else:
            return 0;            
        endif;        
    }

    function setDtValidade($dtValidade) {
        $this->dtValidade = $dtValidade;
    }

    function setDataAlteracao($dataAlteracao) {
        $this->dataAlteracao = $dataAlteracao;
    }

    function setQtConferida($qtConferida) { $this->qtConferida = $qtConferida; }
    function getQtConferida($format = NULL) {
        if (!empty($this->qtConferida)) {
            if ($format == 'F') {
                return number_format($this->qtConferida, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->qtConferida);
            }
        } else {
            return 'NULL';
        }
    }

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
    
////// pedidor item não utilziado ===========================================    

    
    function setQtAtendida($qtAtendida) { $this->qtAtendida = $qtAtendida; }
    function getQtAtendida() {return isset($this->qtAtendida) ? $this->qtAtendida : 'NULL'; }

    function getAtendimento($format = NULL) {
        if (!empty($this->atendimento) && ($this->atendimento != '0000-00-00')) {
            switch ($format) {
                case 'F':
                    return date('d/m/Y', strtotime($this->atendimento));
                    break;
                case 'B':
                    return c_date::convertDateBdSh($this->atendimento, $this->m_banco);
                    break;
                default:
                    return $this->atendimento;
            }
        } else {
            return '';
        }
    }


    function getDescontoItem($format = NULL) {
        if (!empty($this->descontoItem)) {
            if ($format == 'F') {
                return number_format($this->descontoItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->descontoItem);
            }
        } else {
            return '';
        }
    }

    function getFinanceiro($format = NULL) {
        if (!empty($this->financeiro)) {
            if ($format == 'F') {
                return number_format($this->financeiro, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->financeiro);
            }
        } else {
            return '';
        }
    }


    function getFabricanteItem() {
        return $this->fabricanteItem;
    }

    function getAliqIpi($format = NULL) {
        if (!empty($this->aliqIpi)) {
            if ($format == 'F') {
                return number_format($this->aliqIpi, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->aliqIpi);
            }
        } else {
            return '';
        }
    }

    function getPeso($format = NULL) {
        if (!empty($this->peso)) {
            if ($format == 'F') {
                return number_format($this->peso, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->peso);
            }
        } else {
            return '';
        }
    }

    function getPrazo() {
        return $this->prazo;
    }

    function getAliqDesconto($format = NULL) {
        if (!empty($this->aliqDesconto)) {
            if ($format == 'F') {
                return number_format($this->aliqDesconto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->aliqDesconto);
            }
        } else {
            return '';
        }
    }

    function getComissao($format = NULL) {
        if (!empty($this->comissao)) {
            if ($format == 'F') {
                return number_format($this->comissao, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->comissao);
            }
        } else {
            return '';
        }
    }

    function getBaseComissao($format = NULL) {
        if (!empty($this->baseComissao)) {
            if ($format == 'F') {
                return number_format($this->baseComissao, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->baseComissao);
            }
        } else {
            return '';
        }
    }

    function getPrecoSelecionado() {
        return $this->precoSelecionado;
    }

    function getAutorizDesconto() {
        return $this->autorizDesconto;
    }

    function getDescontoMaxAConceder($format = NULL) {
        if (!empty($this->descontoMaxAConceder)) {
            if ($format == 'F') {
                return number_format($this->descontoMaxAConceder, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->descontoMaxAConceder);
            }
        } else {
            return '';
        }
    }

    function getPercDesconto($format = NULL) {
        if (!empty($this->percDesconto)) {
            if ($format == 'F') {
                return number_format($this->percDesconto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percDesconto);
            }
        } else {
            return '';
        }
    }


    function getBonificado() {
        return $this->bonificado;
    }

    function getDescontoOutros($format = NULL) {
        if (!empty($this->descontoOutros)) {
            if ($format == 'F') {
                return number_format($this->descontoOutros, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->descontoOutros);
            }
        } else {
            return '';
        }
    }

    function getPercFinanceiro($format = NULL) {
        if (!empty($this->percFinanceiro)) {
            if ($format == 'F') {
                return number_format($this->percFinanceiro, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percFinanceiro);
            }
        } else {
            return '';
        }
    }


    function setAtendimento($atendimento) {
        $this->atendimento = $atendimento;
    }

    function setDescontoItem($descontoItem) {
        $this->descontoItem = $descontoItem;
    }

    function setFinanceiro($financeiro) {
        $this->financeiro = $financeiro;
    }

 
    function setFabricanteItem($fabricanteItem) {
        $this->fabricanteItem = $fabricanteItem;
    }

    function setAliqIpi($aliqIpi) {
        $this->aliqIpi = $aliqIpi;
    }

    function setPeso($peso) {
        $this->peso = $peso;
    }

    function setPrazo($prazo) {
        $this->prazo = $prazo;
    }

    function setAliqDesconto($aliqDesconto) {
        $this->aliqDesconto = $aliqDesconto;
    }

    function setComissao($comissao) {
        $this->comissao = $comissao;
    }

    function setBaseComissao($baseComissao) {
        $this->baseComissao = $baseComissao;
    }

    function setPrecoSelecionado($precoSelecionado) {
        $this->precoSelecionado = $precoSelecionado;
    }

    function setAutorizDesconto($autorizDesconto) {
        $this->autorizDesconto = $autorizDesconto;
    }

    function setDescontoMaxAConceder($descontoMaxAConceder) {
        $this->descontoMaxAConceder = $descontoMaxAConceder;
    }

    function setPercDesconto($percDesconto) {
        $this->percDesconto = $percDesconto;
    }


    function setBonificado($bonificado) {
        $this->bonificado = $bonificado;
    }

    function setDescontoOutros($descontoOutros) {
        $this->descontoOutros = $descontoOutros;
    }


    function setPercFinanceiro($percFinanceiro) {
        $this->percFinanceiro = $percFinanceiro;
    }
    

    //############### FIM SETS E GETS FAT_PEDIDO_ITEM ###############     
    //###############################################################     

    

    /**
     * Consulta  o Banco atraves de parametros verificando as cotações para aprovar ou desaprovado
     * @name select_pedido_aprovacao_letra
     * @return ARRAY todos os campos da table
     * @version 2000287
     */
    public function select_pedido_aprovacao_letra($letra) {
        /*
         * [0] = Vendedor
         * [1] = Cliente
         * [2] = CodCotação
         * [3] = Centro de Custo
         * [4] = data Inicio
         * [5] = data Fim
         */
        $par = explode("|", $letra);

        $par[4] != "" ? $dataIni = c_date::convertDateTxt($par[4]) : $dataIni = "";
        $par[5] != "" ? $dataFim = c_date::convertDateTxt($par[5]) : $dataFim = "";
        
        $sql = "SELECT p.*, D.PADRAO,  C.NOME,  V.NOMEREDUZIDO AS VENDEDOR  ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=P.CLIENTE) ";        
        $sql .= "LEFT JOIN AMB_USUARIO V ON (V.USUARIO=P.USRFATURA) ";
        $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO=P.CCUSTO) ";

        if($par[2] == ''){
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[0]) ? '':" $cond (p.usrfatura = '$par[0]') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[1]) ? '':" $cond (p.cliente = '$par[1]') ";        

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[3]) ? '':" $cond (p.ccusto = '$par[3]') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[4]) && empty($par[5]) ? '':" $cond ((p.emissao >= '".$dataIni ."') AND (p.emissao <= '".$dataFim."'))  ";

        }else{
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[2]) ? '':" $cond (p.id = '$par[2]') ";
        }

         
        if($par[0]  == '' AND $par[1] == '' AND $par[2] == '' AND $par[3] == '' AND $par[4] == '' AND $par[5] == ''){
            $sql .= 'WHERE (p.situacao = 10 ) AND (ISNULL(p.USRAPROVACAO)) ';
        } else {
            $sql .= 'AND (p.situacao = 10 ) AND (ISNULL(p.USRAPROVACAO)) ';
        }    
        
        $sql .= "ORDER BY p.emissao Desc";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Update dos campos (situação, usrAprovação e dataAprovação) aprovando a cotação para realizar o pedido
     * @name pedido_aprovado
     * @return update dos campos situação, usrAprovação e dataAprovação da tabela FAT_PEDIDO
     * @version 2000287
     */
    public function pedido_aprovado($id){

        $sql = "UPDATE FAT_PEDIDO ";        
        $sql .= "SET  DATAAPROVACAO = '".date("Y-m-d H:i:s") ."', ";
        $sql .= "SITUACAO = 6 , ";
        $sql .= "USRAPROVACAO = ".$this->m_userid." ";
        $sql .= "WHERE ID =".$id .";";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Update do campos (observação, situação) e dando o motivo pelo qual está desaprovado
     * @name pedido_desaprovado
     * @return update dos campos situação e obs da tabela FAT_PEDIDO
     * @version 2000287
     */
    public function pedido_desaprovado($id, $obs){

        $sql = "UPDATE FAT_PEDIDO ";        
        $sql .= "SET  SITUACAO = 0 , ";
        $sql .= "OBS = '".$obs."' ";
        $sql .= "WHERE ID = " . $id. ";";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

}

//	END OF THE CLASS
?>