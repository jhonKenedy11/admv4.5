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
Class c_pedidoVenda extends c_user {

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

    private $prazoEntrega = NULL; 
    
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
    private $codigonota = NULL;

    private $modFrete               = NULL; // char(1)
    private $volume                 = NULL; // varchar(45)
    private $volEspecie             = NULL; // varchar(45)
    private $volMarca               = NULL; // varchar(45)
    private $volPesoLiq             = NULL; // int(11)
    private $volPesoBruto           = NULL; // int(11)
    private $placaVeiculo           = NULL; // varchar(15)
    private $centrocustoentrega     = NULL;
    private $modbcst                = NULL; 
    private $cest                   = NULL;
    private $ncm                    = NULL;
    private $cstcofins              = NULL;
    private $cstpis                 = NULL;
    private $aliqcofins             = NULL;
    private $aliqpis                = NULL;
    private $nomeTransportador      = NULL;
    private $transpNome             = NULL;  

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
                    return number_format($this->desconto, 2, ',', '.');
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

    function setPrazoEntrega($prazoEntrega) { $this->prazoEntrega = $prazoEntrega; }
    function getPrazoEntrega() { return $this->prazoEntrega; }

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
        $this->totalItem = str_replace('.', ',', ($this->getQtSolicitada() * $this->getUnitario('B')));
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

    public function setCodigoNota($codigonota){
        $this->codigonota = $codigonota;
    }
    public function getCodigoNota(){
        return $this->codigonota;
    }

    public function setModFrete($modFrete) {$this->modFrete = strtoupper($modFrete);}
    public function getModFrete() {return $this->modFrete;}

    public function setVolume($volume) {$this->volume = strtoupper($volume);}
    public function getVolume() {
        if (is_numeric($this->volume)):
            return $this->volume;
        else:    
            return 1;
        endif;
        
    }

    public function setVolEspecie($volEspecie) {$this->volEspecie = strtoupper($volEspecie);}
    public function getVolEspecie() {
        if (!empty($this->volEspecie)){
            return $this->volEspecie;
        } else {
            return 'NULL';
        } 
    }

    public function setVolMarca($volMarca) {$this->volMarca = strtoupper($volMarca);}
    public function getVolMarca() {
        if (!empty($this->volMarca))  {
            return $this->volMarca;
        } else {
            return 'NULL';
        }  
    }

    public function setVolPesoLiq($volPesoLiq) {$this->volPesoLiq = strtoupper($volPesoLiq);}
    public function getVolPesoLiq() {
        if (is_numeric($this->volPesoLiq)):
            return $this->volPesoLiq;
        else:    
            return 0;
        endif;
       
    }

    public function setVolPesoBruto($volPesoBruto) {$this->volPesoBruto = strtoupper($volPesoBruto);}
    public function getVolPesoBruto() {
        if (is_numeric($this->volPesoBruto)):
            return $this->volPesoBruto;
        else:    
            return 0;
        endif;
        
    }

    public function setPlacaVeiculo($placaVeiculo) {$this->placaVeiculo = strtoupper($placaVeiculo);}
    public function getPlacaVeiculo() {    
        if (!empty($this->placaVeiculo)) {
            return $this->placaVeiculo;
        } else {
            return 'NULL';
        }  
    }

    function setCentroCustoEntrega($centrocustoentrega) { $this->centrocustoentrega = $centrocustoentrega; }
    function getCentroCustoEntrega() { return $this->centrocustoentrega; }
    
    public function setModBCSt($modbcst) {$this->modbcst = $modbcst;}
    public function getModBCSt() {return $this->modbcst;}
 
    public function setNCM($ncm) {$this->ncm = $ncm;}
    public function getNCM() {return $this->ncm;}

    public function setCest($cest) {$this->cest = $cest;}
    public function getCest() {return $this->cest;}

    public function setCstPIS($cstpis) {$this->cstpis = $cstpis;}
    public function getCstPIS() {return $this->cstpis;}
 
    public function setCstCofins($cstcofins) {$this->cstcofins = $cstcofins;}
    public function getCstCofins() {return $this->cstcofins;}

    public function setAliqCofins( $aliqcofins, $format=false) {
        $this-> aliqcofins =  $aliqcofins;
        if ($format):
            $this-> aliqcofins = number_format($this-> aliqcofins, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqCofins($format = null) {
        if (!empty($this-> aliqcofins)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this-> aliqcofins);
                    break;
                case 'F':
                    return number_format((double) $this-> aliqcofins, 2, ',', '.');
                    break;
                default :
                    return $this-> aliqcofins;
            }
        else:
            return 0;            
        endif;        
    }

    public function setAliqPIS( $aliqpis, $format=false) {
        $this-> aliqpis =  $aliqpis;
        if ($format):
            $this-> aliqpis = number_format($this-> aliqpis, 2, ',', '.');
        endif;
        
    }
    
    public function getAliqPIS($format = null) {
        if (!empty($this-> aliqpis)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this-> aliqpis);
                    break;
                case 'F':
                    return number_format((double) $this-> aliqpis, 2, ',', '.');
                    break;
                default :
                    return $this-> aliqpis;
            }
        else:
            return 0;            
        endif;        
    }
    public function setNomeTransportador() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getTransportador());
        $reg_nome = $pessoa->select_conta();
        $this->nomeTransportador = $reg_nome[0]['NOME'];
    }
    public function setNomeTransportadora() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getTransportadora());
        $reg_nome = $pessoa->select_conta();
        $this->transpNome = $reg_nome[0]['NOME'];
    }
    
    public function getNomeTransportador() {return $this->nomeTransportador; }
    
    public function getNomeTransportadora() {return $this->transpNome; }

    //############### FIM SETS E GETS FAT_PEDIDO_ITEM ###############     
    //###############################################################     

    /**
     * Funcao para setar todos os objetos da classe
     * @name setPedidoVenda
     * @param INT GetId chave primaria da table pedidos
     */
    public function setPedidoVenda() {

        $pedido = $this->select_pedidoVenda();
        $this->setId($pedido[0]['ID']);
        $this->setCliente($pedido[0]['CLIENTE']);
        $this->setClienteNome($pedido[0]['NOME']);
        $this->setPedido($pedido[0]['PEDIDO']);
        $this->setSituacao($pedido[0]['SITUACAO']);
        $this->setEmissao($pedido[0]['EMISSAO']);
        $this->setEntregador($pedido[0]['ENTREGADOR']);
        $this->setUsrFatura($pedido[0]['USRFATURA']);
        $this->setTabPreco($pedido[0]['TABPRECO']);
        $this->setEntradaTabPreco($pedido[0]['ENTRADATABPRECO']);
        $this->setVencimento1($pedido[0]['VENCIMENTO1']);
        $this->setDesconto($pedido[0]['DESCONTO']);
        $this->setTotal($pedido[0]['TOTAL']);
        $this->setMoeda($pedido[0]['MOEDA']);
        $this->setUsrPedido($pedido[0]['USRPEDIDO']);
        $this->setObs($pedido[0]['OBS']);

        $this->setSerie($pedido[0]['SERIE']);
        $this->setIdNatop($pedido[0]['IDNATOP']);
        $this->setEspecie($pedido[0]['ESPECIE']);
        $this->setGenero($pedido[0]['GENERO']);
        $this->setContaDeposito($pedido[0]['CONTADEPOSITO']);
        $this->setCondPg($pedido[0]['CONDPG']);
        $this->setCentroCusto($pedido[0]['CCUSTO']);
        $this->setTransportadora($pedido[0]['TRANSPORTADORA']);
        $this->setFrete($pedido[0]['FRETE']);

        $this->setOdEsferico($pedido[0]['ODESFERICO']);
        $this->setOeEsferico($pedido[0]['OEESFERICO']);
        $this->setOdCilindrico($pedido[0]['ODCILINDRICO']);
        $this->setOeCilindrico($pedido[0]['OECILINDRICO']);
        $this->setOdEixo($pedido[0]['ODEIXO']);
        $this->setOeEixo($pedido[0]['OEEIXO']);
        $this->setOdAd($pedido[0]['ODAD']);
        $this->setOeAd($pedido[0]['OEAD']);
        $this->setMedico($pedido[0]['MEDICO']);
        
        
        $this->setDataEntrega($pedido[0]['DATAENTREGA']);
        $this->setHoraEntrega($pedido[0]['HORAENTREGA']);
        $this->setTipoEntrega($pedido[0]['TIPOENTREGA']);
        $this->setDescEntrega($pedido[0]['DESCENTREGA']);

        $this->setHoraEmissao($pedido[0]['HORAEMISSAO']);
        $this->setTaxaEntrega($pedido[0]['TAXAENTREGA']);
        $this->setTotalRecebido($pedido[0]['TOTALRECEBIDO']);
        $this->setTabelaPreco($pedido[0]['TABELAPRECO']);
        $this->setIpi($pedido[0]['IPI']);
        $this->setComprador($pedido[0]['COMPRADOR']);
        $this->setTabelaVenda($pedido[0]['TABELAVENDA']);
        $this->setDtUltimoPedidoCliente($pedido[0]['DTULTIMOPEDIDOCLIENTE']);
        $this->setUsrAprovacao($pedido[0]['USRAPROVACAO']);
        $this->setPerDesconto($pedido[0]['PERDESCONTO']);
        $this->setDescontoNf($pedido[0]['DESCONTONF']);
        $this->setTotalProdutos($pedido[0]['TOTALPRODUTOS']);
        $this->setDtValidade($pedido[0]['DTVALIDADE']);
        $this->setDataAlteracao($pedido[0]['DATAALTERACAO']);
        $this->setDespAcessorias($pedido[0]['DESPACESSORIAS']);
        $this->setCredito($pedido[0]['CREDITO'], true);
        $this->setPrazoEntrega($pedido[0]['PRAZOENTREGA']);
    }


    public function select_todos_pedidos_vendedor($pesq, $vendedores, $condPagamento, $situacao, $centroCusto, $motivo){

        $dataIni = c_date::convertDateTxt($pesq[0]);
        $dataFim = c_date::convertDateTxt($pesq[1]);
       // $centroCusto = $pesq[2];
        $cliente = $pesq[4];
        $produto = $pesq[5];
    
    
        $sql = "SELECT DISTINCT P.*, C.NOME AS NOMECLIENTE, U.NOMEREDUZIDO AS NOMEVENDEDOR, CC.DESCRICAO AS CCUSTO, D.PADRAO AS SIT, M.DESCRICAO AS MOTIVO, ";
        $sql .= " CASE WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Sunday' THEN 'Domingo' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Monday' THEN 'Segunda-Feira' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Tuesday' THEN 'Terca-Feira' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Wednesday' THEN 'Quarta-Feira' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Thursday' THEN 'Quinta-Feira' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Friday' THEN 'Sexta-Feira' ";
        $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Saturday' THEN 'Sabado' END AS DIASEMANA ";
    
        $sql .= " FROM FAT_PEDIDO P  ";
        $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = P.CCUSTO) ";
        $sql .= "LEFT JOIN FAT_MOTIVO M ON (M.MOTIVO = I.MOTIVO) ";
        $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) ";
        $sqlData = " ( P.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."' )";
       // $sqlCentroCusto = " (P.CCUSTO = '".$centroCusto."') ";
        $sqlCliente = " (P.CLIENTE = '".$cliente."') ";
        $sqlProduto = " (I.ITEMESTOQUE = '".$produto."') ";
        
        if($cliente == '' && $produto == '' && sizeof($condPagamento) == 1 && sizeof($centroCusto) == 1 
        && sizeof($situacao) == 1 && sizeof($vendedores) == 1 && sizeof($motivo) == 1 ){
    
            $sql .= "WHERE ".$sqlData;
        }else{
            $sql .= "WHERE ".$sqlData;
            $sql .= " AND ";
    
            if (sizeof($centroCusto) > 1) {
                $sqlCentroCusto = "";
                for ($i = 0; $i < count($centroCusto); $i++) {
                    if ($centroCusto[$i] != "") {
                        if ($sqlCentroCusto == "") {
                            $sqlCentroCusto .= "'" . $centroCusto[$i] . "'";
                        } else {
                            $sqlCentroCusto .= ",'" . $centroCusto[$i] . "'";
                        }
                    }                
                }     
                $sqlCentroCusto = " ( P.CCUSTO IN (" . $sqlCentroCusto ;       
                $sqlCentroCusto .=")) ";
                
                if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                    $sqlCentroCusto .= " AND ";
                }
    
                $sql .= $sqlCentroCusto;
            }
    
            /*
            if ($centroCusto != ''){
                $sql .= $sqlCentroCusto;
                if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                    $sql .= " AND ";
                }
            } */
            
            if($cliente != ''){
                $sql .= $sqlCliente;
                if($produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                    $sql .= " AND ";
                }
            }
    
            if($produto != ''){
                $sql .= $sqlProduto;
                if(sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                    $sql .= " AND ";
                }
            }
    
    
            if (sizeof($situacao) > 1) {
                $sqlSituacao = "";
                for ($i = 0; $i < count($situacao); $i++) {
                    if ($situacao[$i] != "") {
                        if ($sqlSituacao == "") {
                            $sqlSituacao .= "'" . $situacao[$i] . "'";
                        } else {
                            $sqlSituacao .= ",'" . $situacao[$i] . "'";
                        }
                    }                
                }     
                $sqlSituacao = " ( P.SITUACAO IN (" . $sqlSituacao ;       
                $sqlSituacao .=")) ";
                
                if (sizeof($condPagamento) > 1 || sizeof($vendedores) > 1 || sizeof($motivo) > 1 ) {
                    $sqlSituacao .= " AND ";
                }
    
                $sql .= $sqlSituacao;
            }
    
            if (sizeof($vendedores) > 1) {
                $sqlVendedores = "";
                for ($i = 0; $i < count($vendedores); $i++) {
                    if ($vendedores[$i] != "") {
                        if ($sqlVendedores == "") {
                            $sqlVendedores .= "'" . $vendedores[$i] . "'";
                        } else {
                            $sqlVendedores .= ",'" . $vendedores[$i] . "'";
                        }
                    }                
                }     
                $sqlVendedores = " ( P.USRFATURA in (" . $sqlVendedores ;       
                $sqlVendedores .=")) ";
                
                if (sizeof($condPagamento) > 1 || sizeof($motivo) > 1) {
                    $sqlVendedores .= " AND ";
                }
    
                $sql .= $sqlVendedores;
            }
    
            if (sizeof($condPagamento) > 1) {
                $sqlCondPag = "";
                for ($i = 0; $i < count($condPagamento); $i++) {
                    if ($condPagamento[$i] != "") {
                        if ($sqlCondPag == "") {
                            $sqlCondPag .= "'" . $condPagamento[$i] . "'";
                        } else {
                            $sqlCondPag .= ",'" . $condPagamento[$i] . "'";
                        }
                    }                
                }     
                $sqlCondPag = " (P.CONDPG in (" . $sqlCondPag ;       
                $sqlCondPag .=")) ";
    
                if(sizeof($motivo) > 1){
                    $sqlCondPag .= " AND ";
                }
            
                $sql .= $sqlCondPag;
            }
           
    
            if (sizeof($motivo) > 1) {
                $sqlMotivo = "";
                for ($i = 0; $i < count($motivo); $i++) {
                    if ($motivo[$i] != "") {
                        if ($sqlMotivo == "") {
                            $sqlMotivo .= "'" . $motivo[$i] . "'";
                        } else {
                            $sqlMotivo .= ",'" . $motivo[$i] . "'";
                        }
                    }                
                }     
                $sqlMotivo = " (i.motivo <> '0' AND i.motivo in (" . $sqlMotivo ;       
                $sqlMotivo .=")) ";
            
                $sql .= $sqlMotivo;
            }
    
        }
        $sql .= " ORDER BY P.USRFATURA ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado; 
    
    }
// setPedidoVenda

public function select_todos_pedidos_motivo($pesq, $vendedores, $condPagamento, $situacao, $centroCusto, $motivo){

    $dataIni = c_date::convertDateTxt($pesq[0]);
    $dataFim = c_date::convertDateTxt($pesq[1]);
    $cliente = $pesq[4];
    $produto = $pesq[5];


    $sql = "SELECT DISTINCT P.*, C.NOME AS NOMECLIENTE, U.NOMEREDUZIDO AS NOMEVENDEDOR, CC.DESCRICAO AS CCUSTO, D.PADRAO AS SIT, M.DESCRICAO AS MOTIVO FROM FAT_PEDIDO P  ";
    $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID) ";
    $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA) ";
    $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE) ";
    $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = P.CCUSTO) ";
    $sql .= "LEFT JOIN FAT_MOTIVO M ON (M.MOTIVO = I.MOTIVO) ";
    $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) ";
    $sqlData = " ( P.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."' )";
    $sqlCliente = " (P.CLIENTE = '".$cliente."') ";
    $sqlProduto = " (I.ITEMESTOQUE = '".$produto."') ";
    
    if($cliente == '' && $produto == '' && sizeof($condPagamento) == 1 && 
    sizeof($centroCusto) == 1 && sizeof($situacao) == 1 && sizeof($vendedores) == 1 && sizeof($motivo) == 1 ){

    $sql .= "WHERE ".$sqlData;
    }else{
        $sql .= "WHERE ".$sqlData;
        $sql .= " AND ";

        if (sizeof($centroCusto) > 1) {
            $sqlCentroCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    if ($sqlCentroCusto == "") {
                        $sqlCentroCusto .= "'" . $centroCusto[$i] . "'";
                    } else {
                        $sqlCentroCusto .= ",'" . $centroCusto[$i] . "'";
                    }
                }                
            }     
            $sqlCentroCusto = " ( P.CCUSTO IN (" . $sqlCentroCusto ;       
            $sqlCentroCusto .=")) ";
            
            if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                $sqlCentroCusto .= " AND ";
            }

            $sql .= $sqlCentroCusto;
        }
        
        if($cliente != ''){
            $sql .= $sqlCliente;
            if($produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                $sql .= " AND ";
            }
        }

        if($produto != ''){
            $sql .= $sqlProduto;
            if(sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                $sql .= " AND ";
            }
        }


        if (sizeof($situacao) > 1) {
            $sqlSituacao = "";
            for ($i = 0; $i < count($situacao); $i++) {
                if ($situacao[$i] != "") {
                    if ($sqlSituacao == "") {
                        $sqlSituacao .= "'" . $situacao[$i] . "'";
                    } else {
                        $sqlSituacao .= ",'" . $situacao[$i] . "'";
                    }
                }                
            }     
            $sqlSituacao = " ( P.SITUACAO IN (" . $sqlSituacao ;       
            $sqlSituacao .=")) ";
            
            if (sizeof($condPagamento) > 1 || sizeof($vendedores) > 1 || sizeof($motivo) > 1 ) {
                $sqlSituacao .= " AND ";
            }

            $sql .= $sqlSituacao;
        }

        if (sizeof($vendedores) > 1) {
            $sqlVendedores = "";
            for ($i = 0; $i < count($vendedores); $i++) {
                if ($vendedores[$i] != "") {
                    if ($sqlVendedores == "") {
                        $sqlVendedores .= "'" . $vendedores[$i] . "'";
                    } else {
                        $sqlVendedores .= ",'" . $vendedores[$i] . "'";
                    }
                }                
            }     
            $sqlVendedores = " ( P.USRFATURA in (" . $sqlVendedores ;       
            $sqlVendedores .=")) ";
            
            if (sizeof($condPagamento) > 1 || sizeof($motivo) > 1) {
                $sqlVendedores .= " AND ";
            }

            $sql .= $sqlVendedores;
        }

        if (sizeof($condPagamento) > 1) {
            $sqlCondPag = "";
            for ($i = 0; $i < count($condPagamento); $i++) {
                if ($condPagamento[$i] != "") {
                    if ($sqlCondPag == "") {
                        $sqlCondPag .= "'" . $condPagamento[$i] . "'";
                    } else {
                        $sqlCondPag .= ",'" . $condPagamento[$i] . "'";
                    }
                }                
            }     
            $sqlCondPag = " (P.CONDPG in (" . $sqlCondPag ;       
            $sqlCondPag .=")) ";

            if(sizeof($motivo) > 1){
                $sqlCondPag .= " AND ";
            }
        
            $sql .= $sqlCondPag;
        }

    

        if (sizeof($motivo) > 1) {
            $sqlMotivo = "";
            for ($i = 0; $i < count($motivo); $i++) {
                if ($motivo[$i] != "") {
                    if ($sqlMotivo == "") {
                        $sqlMotivo .= "'" . $motivo[$i] . "'";
                    } else {
                        $sqlMotivo .= ",'" . $motivo[$i] . "'";
                    }
                }                
            }     
            $sqlMotivo = " ( i.motivo in (" . $sqlMotivo ;       
            $sqlMotivo .=")) ";
        
            $sql .= $sqlMotivo;
        }

    }
    $sql .= " AND (i.motivo <> '0') ";
    $sql .= " ORDER BY P.EMISSAO ";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado; 

}

public function select_todos_pedidos_cond_pag($pesq, $vendedores, $condPagamento, $situacao, $centroCusto){

    $dataIni = c_date::convertDateTxt($pesq[0]);
    $dataFim = c_date::convertDateTxt($pesq[1]);
   // $centroCusto = $pesq[2];
    $cliente = $pesq[4];
    $produto = $pesq[5];

    $sql = "SELECT DISTINCT P.*, C.NOME AS NOMECLIENTE, CP.DESCRICAO AS CONDPAGAMENTO, U.NOMEREDUZIDO AS NOMEVENDEDOR, CC.DESCRICAO AS CCUSTO, D.PADRAO AS SIT, M.DESCRICAO AS MOTIVO ";
    $sql .= " FROM FAT_PEDIDO P  ";
    $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID) ";
    $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA) ";
    $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE) ";
    $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = P.CCUSTO) ";
    $sql .= "LEFT JOIN FAT_MOTIVO M ON (M.MOTIVO = I.MOTIVO) ";
    $sql .= "LEFT JOIN FAT_COND_PGTO CP ON (P.CONDPG = CP.ID ) ";
    $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) ";
    $sqlData = " ( P.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."' )";
   // $sqlCentroCusto = " (P.CCUSTO = '".$centroCusto."') ";
    $sqlCliente = " (P.CLIENTE = '".$cliente."') ";
    $sqlProduto = " (I.ITEMESTOQUE = '".$produto."') ";
    
    if($cliente == '' && $produto == '' && sizeof($condPagamento) == 1 && 
    sizeof($centroCusto) == 1 &&  sizeof($situacao) == 1 && sizeof($vendedores) == 1 ){

        $sql .= "WHERE ".$sqlData;
    }else{
        $sql .= "WHERE ".$sqlData;
        $sql .= " AND ";

        if (sizeof($centroCusto) > 1) {
            $sqlCentroCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    if ($sqlCentroCusto == "") {
                        $sqlCentroCusto .= "'" . $centroCusto[$i] . "'";
                    } else {
                        $sqlCentroCusto .= ",'" . $centroCusto[$i] . "'";
                    }
                }                
            }     
            $sqlCentroCusto = " ( P.CCUSTO IN (" . $sqlCentroCusto ;       
            $sqlCentroCusto .=")) ";
            
            if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                $sqlCentroCusto .= " AND ";
            }

            $sql .= $sqlCentroCusto;
        }
        /*
        if ($centroCusto != ''){
            $sql .= $sqlCentroCusto;
            if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                $sql .= " AND ";
            }
        } */
        
        if($cliente != ''){
            $sql .= $sqlCliente;
            if($produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 ){
                $sql .= " AND ";
            }
        }

        if($produto != ''){
            $sql .= $sqlProduto;
            if(sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                $sql .= " AND ";
            }
        }


        if (sizeof($situacao) > 1) {
            $sqlSituacao = "";
            for ($i = 0; $i < count($situacao); $i++) {
                if ($situacao[$i] != "") {
                    if ($sqlSituacao == "") {
                        $sqlSituacao .= "'" . $situacao[$i] . "'";
                    } else {
                        $sqlSituacao .= ",'" . $situacao[$i] . "'";
                    }
                }                
            }     
            $sqlSituacao = " ( P.SITUACAO IN (" . $sqlSituacao ;       
            $sqlSituacao .=")) ";
            
            if (sizeof($condPagamento) > 1 || sizeof($vendedores) > 1) {
                $sqlSituacao .= " AND ";
            }

            $sql .= $sqlSituacao;
        }

        if (sizeof($vendedores) > 1) {
            $sqlVendedores = "";
            for ($i = 0; $i < count($vendedores); $i++) {
                if ($vendedores[$i] != "") {
                    if ($sqlVendedores == "") {
                        $sqlVendedores .= "'" . $vendedores[$i] . "'";
                    } else {
                        $sqlVendedores .= ",'" . $vendedores[$i] . "'";
                    }
                }                
            }     
            $sqlVendedores = " ( P.USRFATURA in (" . $sqlVendedores ;       
            $sqlVendedores .=")) ";
            
            if (sizeof($condPagamento) > 1) {
                $sqlVendedores .= " AND ";
            }

            $sql .= $sqlVendedores;
        }

        if (sizeof($condPagamento) > 1) {
            $sqlCondPag = "";
            for ($i = 0; $i < count($condPagamento); $i++) {
                if ($condPagamento[$i] != "") {
                    if ($sqlCondPag == "") {
                        $sqlCondPag .= "'" . $condPagamento[$i] . "'";
                    } else {
                        $sqlCondPag .= ",'" . $condPagamento[$i] . "'";
                    }
                }                
            }     
            $sqlCondPag = " (P.CONDPG in (" . $sqlCondPag ;       
            $sqlCondPag .=")) ";
        
            $sql .= $sqlCondPag;
        }

       
    }
    $sql .= " ORDER BY P.CONDPG ";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado; 


}

public function select_todos_pedidos($pesq, $vendedores, $condPagamento, $situacao, $centroCusto, $motivo, $data = 'EMISSAO'){

    $dataIni = c_date::convertDateTxt($pesq[0]);
    $dataFim = c_date::convertDateTxt($pesq[1]);
   // $centroCusto = $pesq[2];
    $cliente = $pesq[4];
    $produto = $pesq[5];

    $entrega = '';
    if ($data == 'ENTREGA') {
        $data = 'PRAZOENTREGA';
        $entrega = 'ENTREGA';
    }


    $sql = "SELECT DISTINCT P.*, C.CIDADE, C.BAIRRO, ";
    $sql .= "C.NOME AS NOMECLIENTE, U.NOMEREDUZIDO AS NOMEVENDEDOR, D.PADRAO AS SITUACAO, ";
    // $sql .= "CC.DESCRICAO AS CCUSTO, D.PADRAO AS SIT, 'motivo' AS MOTIVO, 'teste' AS DIASEMANA ";
    $sql .= "M.DESCRICAO AS MOTIVO, 'teste' AS DIASEMANA ";
    // $sql .= " CASE WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Sunday' THEN 'Domingo' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Monday' THEN 'Segunda-Feira' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Tuesday' THEN 'Terca-Feira' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Wednesday' THEN 'Quarta-Feira' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Thursday' THEN 'Quinta-Feira' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Friday' THEN 'Sexta-Feira' ";
    // $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Saturday' THEN 'Sabado' END AS DIASEMANA ";

    $sql .= "FROM FAT_PEDIDO P  ";
    $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID) ";
    $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA) ";
    $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE) ";
    $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = P.CCUSTO) ";
    $sql .= "LEFT JOIN FAT_MOTIVO M ON (M.MOTIVO = I.MOTIVO) ";
    $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) ";
    if ($entrega == 'ENTREGA'){
        $sqlData = "(cast(concat(SUBSTRING(P.PRAZOENTREGA, 7, 4),'-',SUBSTRING(P.PRAZOENTREGA, 4, 2),'-',SUBSTRING(P.PRAZOENTREGA, 1, 2)) as date) ";
        $sqlData .= " BETWEEN '".$dataIni."' AND '".$dataFim."' )";
    }
    else {
        $sqlData = " ( P.".$data." BETWEEN '".$dataIni."' AND '".$dataFim."' )";
    }
   // $sqlCentroCusto = " (P.CCUSTO = '".$centroCusto."') ";
    $sqlCliente = " (P.CLIENTE = '".$cliente."') ";
    $sqlProduto = " (I.ITEMESTOQUE = '".$produto."') ";
    
    if($cliente == '' && $produto == '' && sizeof($condPagamento) == 1 && sizeof($centroCusto) == 1 
    && sizeof($situacao) == 1 && sizeof($vendedores) == 1 && sizeof($motivo) == 1 ){

        $sql .= "WHERE ".$sqlData;
    }else{
        $sql .= "WHERE ".$sqlData;
        $sql .= " AND ";

        if (sizeof($centroCusto) > 1) {
            $sqlCentroCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    if ($sqlCentroCusto == "") {
                        $sqlCentroCusto .= "'" . $centroCusto[$i] . "'";
                    } else {
                        $sqlCentroCusto .= ",'" . $centroCusto[$i] . "'";
                    }
                }                
            }     
            $sqlCentroCusto = " ( P.CCUSTO IN (" . $sqlCentroCusto ;       
            $sqlCentroCusto .=")) ";
            
            if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1){
                $sqlCentroCusto .= " AND ";
            }

            $sql .= $sqlCentroCusto;
        }

        /*
        if ($centroCusto != ''){
            $sql .= $sqlCentroCusto;
            if($cliente != '' || $produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                $sql .= " AND ";
            }
        } */
        
        if($cliente != ''){
            $sql .= $sqlCliente;
            if($produto != '' || sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                $sql .= " AND ";
            }
        }

        if($produto != ''){
            $sql .= $sqlProduto;
            if(sizeof($situacao) > 1 || sizeof($vendedores) > 1 || sizeof($condPagamento) > 1 || sizeof($motivo) > 1){
                $sql .= " AND ";
            }
        }


        if (sizeof($situacao) > 1) {
            $sqlSituacao = "";
            for ($i = 0; $i < count($situacao); $i++) {
                if ($situacao[$i] != "") {
                    if ($sqlSituacao == "") {
                        $sqlSituacao .= "'" . $situacao[$i] . "'";
                    } else {
                        $sqlSituacao .= ",'" . $situacao[$i] . "'";
                    }
                }                
            }     
            $sqlSituacao = " ( P.SITUACAO IN (" . $sqlSituacao ;       
            $sqlSituacao .=")) ";
            
            if (sizeof($condPagamento) > 1 || sizeof($vendedores) > 1 || sizeof($motivo) > 1 ) {
                $sqlSituacao .= " AND ";
            }

            $sql .= $sqlSituacao;
        }

        if (sizeof($vendedores) > 1) {
            $sqlVendedores = "";
            for ($i = 0; $i < count($vendedores); $i++) {
                if ($vendedores[$i] != "") {
                    if ($sqlVendedores == "") {
                        $sqlVendedores .= "'" . $vendedores[$i] . "'";
                    } else {
                        $sqlVendedores .= ",'" . $vendedores[$i] . "'";
                    }
                }                
            }     
            $sqlVendedores = " ( P.USRFATURA in (" . $sqlVendedores ;       
            $sqlVendedores .=")) ";
            
            if (sizeof($condPagamento) > 1 || sizeof($motivo) > 1) {
                $sqlVendedores .= " AND ";
            }

            $sql .= $sqlVendedores;
        }

        if (sizeof($condPagamento) > 1) {
            $sqlCondPag = "";
            for ($i = 0; $i < count($condPagamento); $i++) {
                if ($condPagamento[$i] != "") {
                    if ($sqlCondPag == "") {
                        $sqlCondPag .= "'" . $condPagamento[$i] . "'";
                    } else {
                        $sqlCondPag .= ",'" . $condPagamento[$i] . "'";
                    }
                }                
            }     
            $sqlCondPag = " (P.CONDPG in (" . $sqlCondPag ;       
            $sqlCondPag .=")) ";

            if(sizeof($motivo) > 1){
                $sqlCondPag .= " AND ";
            }
        
            $sql .= $sqlCondPag;
        }
       

        if (sizeof($motivo) > 1) {
            $sqlMotivo = "";
            for ($i = 0; $i < count($motivo); $i++) {
                if ($motivo[$i] != "") {
                    if ($sqlMotivo == "") {
                        $sqlMotivo .= "'" . $motivo[$i] . "'";
                    } else {
                        $sqlMotivo .= ",'" . $motivo[$i] . "'";
                    }
                }                
            }     
            $sqlMotivo = " (i.motivo <> '0' AND i.motivo in (" . $sqlMotivo ;       
            $sqlMotivo .=")) ";
        
            $sql .= $sqlMotivo;
        }

    }

    if ($entrega == 'ENTREGA') {
        $sql .= " ORDER BY P.PRAZOENTREGA";        
    } else {
        $sql .= " ORDER BY P.EMISSAO ";
    } 
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado; 

}   
    /**
     * Consulta para o Banco atraves do pedido id
     * @name select_fatura_pedido_venda
     * @return ARRAY todos os campos da table
     * @version 20200903
     */
    public function select_fatura_pedido_venda($id_pedido){
        $sql = "SELECT P.*, C.NOME AS NOMECLIENTE, L.VENCIMENTO AS VENC, L.ORIGINAL, TD.PADRAO AS TPDOCTO, MP.PADRAO AS MODOPAG , L.TOTAL AS TOTAL_FAT, SP.PADRAO AS SITUACAOPAG ";
        $sql .= "FROM FAT_PEDIDO P ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (P.CLIENTE = C.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_LANCAMENTO L ON (P.ID = L.DOCTO) ";
        $sql .= "LEFT JOIN AMB_DDM TD ON ((L.TIPODOCTO = TD.TIPO) AND TD.ALIAS='FIN_MENU' AND TD.CAMPO='TipoDoctoPgto') ";
        $sql .= "LEFT JOIN AMB_DDM MP ON ((L.MODOPGTO = MP.TIPO) AND MP.ALIAS='FIN_MENU' AND MP.CAMPO='ModoPgto') ";
        $sql .= "LEFT JOIN AMB_DDM SP ON ((L.SITPGTO = SP.TIPO) AND SP.ALIAS='FIN_MENU' AND SP.CAMPO='SituacaoPgto') ";
        $sql .= "WHERE P.ID =".$id_pedido;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado; 
    }

    /**
     * Consulta para o Banco atraves do pedido id
     * @name select_todos_pedidos_item
     * @return ARRAY todos os campos da table
     * @version 20200902
     */

    public function select_todos_pedidos_item($idPedido){
        $sql = "SELECT * FROM FAT_PEDIDO_ITEM WHERE ID = ".$idPedido;

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

    }
    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     * @version 20161004
     */

    public function select_pedidoVenda($situacao = NULL, $letra = NULL) {

        
        //$sql = "SELECT DISTINCT p.*, c.nome, c.nomereduzido, c.pessoa, ";
        $sql = "SELECT p.*, c.nome, c.nomereduzido, c.pessoa, c.obs as OBSCLIENTE, c.REFERENCIA, c.TRANSVERSAL1, c.TRANSVERSAL2, ";
        $sql .= "c.fonearea, c.fone, c.celular, c.fonecontato, c.tipoend, ";
        $sql .= "c.tituloend, c.pessoa, G.PADRAO AS DESCENTREGA, ";
        $sql .= " IF ( CNPJCPF <> '', IF ";
        $sql .= " (PESSOA = 'J', CONCAT(SUBSTRING(cnpjcpf, 1,2), '.' , SUBSTRING(cnpjcpf, 3,3),'.', SUBSTRING(cnpjcpf, 6,3),'/',SUBSTRING(cnpjcpf, 9,4), ";
        $sql .= " '-',SUBSTRING(cnpjcpf, 13,2)), ";
        $sql .= " CONCAT(SUBSTRING(cnpjcpf, 1,3), '.' , SUBSTRING(cnpjcpf, 4,3),'.',SUBSTRING(cnpjcpf, 7,3),'-',SUBSTRING(cnpjcpf, 10,2)) ";
        $sql .= " ), '')  AS CNPJCPF, ";      
        $sql .= "c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.uf, c.cep, c.email, c.codmunicipio, t.descricao as descpgto, ";
        $sql .="e.NfAuto, e.ModeloNF, Coalesce(p.transportadora, p.cliente) as Transportadora, u.NOME as USRFATURA_ ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "LEFT join fat_cond_pgto t on t.id = p.condpg ";
        $sql .= "LEFT join est_nat_op e on (e.id = p.idnatop) ";
        $sql .= "LEFT join amb_usuario u on (u.usuario = p.usrfatura) "; 
        $sql .= "LEFT JOIN AMB_DDM G ON ((G.TIPO=P.TIPOENTREGA) AND (ALIAS='PED_MENU') AND (CAMPO='TIPOENTREGA')) ";
        $sql .= "WHERE (p.id = " . $this->getId() . ") ";
        if (!empty($situacao)) {
            if ($situacao == '0') {
                $sql .= "and (situacao ='" . $situacao . "') ";
            }
        }
        $sql .= "ORDER BY id;"; 
//        echo strtoupper($sql)."<BR>";
        $banco = new c_banco;        
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     * @version 20161004
     */
    public function max_pedidoVendaAberto($situacao = NULL) {

        $sql = "SELECT max(id) as id, cliente, pedido, SITUACAO, TOTAL, TAXAENTREGA, DESCONTO FROM FAT_PEDIDO  ";
        $sql .= "where (situacao=0) and (userinsert=". $this->m_userid.") and (ccusto=".$this->m_empresacentrocusto.")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name verifica_vendedor
     * @return ARRAY todos os campos da table
     * @version 20200505
     */
    public function verifica_vendedor() {

        $sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
        $sql .= "WHERE (USUARIO = ". $this->m_userid.")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
    public function verifica_vendedor() {

        $sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
        $sql .= "WHERE (USUARIO = ". $this->m_userid.")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    */

    public function select_pedidoVenda_situacao($situacao) {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM fat_pedido ";
        $sql .= "where (situacao ='" . $situacao . "') ";
        $sql .= "ORDER BY id;";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_venda_letra_situacao($letra) {
        $par = explode("|", $letra);
        $letra = implode(",", $par);
        $sql = "SELECT p.*, D.PADRAO ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (D.ALIAS='FAT_MENU') AND (D.CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "where (p.cliente = " . $par[0] . ") and (p.situacao in (";
        for ($i = 1; $i < count($par); $i++) {
            if ($i == 1) {
                $sql .= "'" . $par[$i] . "'";
            } else {
                $sql .= ",'" . $par[$i] . "'";
            }
        }
        $sql .=")) ";
        $sql .= "and (P.ccusto = '" . $this->m_empresacentrocusto . "') ";
        $sql .= "order by P.situacao, P.emissao;";
        // echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Consulta para o Banco atraves de parametros
     * @name select_pedidoVenda_letra
     * @return ARRAY todos os campos da table
     * @version 2000287
     */
    public function select_pedidoVenda_letra($letra, $motivos = null) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = vendedor
         * [4] = produto        
         * [5] = centro_custo        
         * [6] = reservado                
         * [7] = situacao        
         * [8] = id (pedido)  
         */
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);
        
        $sql = "SELECT p.*, D.PADRAO, C.NOMEREDUZIDO, C.NOME, C.USERLOGIN, V.NOMEREDUZIDO AS VENDEDOR  ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=P.CLIENTE) ";        
        $sql .= "LEFT JOIN AMB_USUARIO V ON (V.USUARIO=P.USRFATURA) ";
        if ($par[3] != '') {
            $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID=P.ID) AND (I.ITEMESTOQUE= ".$par[3].") ";
        }

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[0]) ? '':" $cond (p.emissao >= '$dataIni') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[1]) ? '':" $cond (p.emissao <= '$dataFim') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[2]) ? '':" $cond (p.cliente = $par[2])";
        
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        //$sql .= empty($par[4]) ? '':" $cond (p.situacao in ($par[4]))";
        if ($par[4] != '')  {
            $sql .=" $cond (p.situacao in ($par[4]))";
        }

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[5]) ? '':" $cond (p.usrfatura in ($par[5]))";
        //$sql .= empty($par[5]) ? " AND (p.usrfatura = '".$this->m_userid."')" :" $cond (p.usrfatura in ($par[5]))";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[6]) ? '':" $cond (p.condpg in ($par[6]))";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[7]) ? '':" $cond (p.ccusto in ($par[7]))";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[8]) ? '':" $cond (p.id  = ($par[8]))";


        // if (strlen($motivos) > 0) {
        //     $sqlMotivo = "";
            
        //     $par = explode("|", $motivos);
        //     for ($i = 0; $i < count($par); $i++) {
        //         if ($par[$i] != "") {
        //             if ($sqlMotivo == "") {
        //                 $sqlMotivo .= "'" . $par[$i] . "'";
        //             } else {
        //                 $sqlMotivo .= ",'" . $par[$i] . "'";
        //             }
        //         }                
        //     }     
        //     $sqlMotivo = " AND (i.motivo in (" . $sqlMotivo ;       
        //     $sqlMotivo .=")) ";
        // }

        // if (strlen($sqlMotivo)>3){
        //     $sql .= $sqlMotivo;
        // }

        $sql .= ' and (p.situacao <> 8)';


       // $sql .= "ORDER BY p.situacao, p.emissao, p.cliente ";
       // $sql .= "ORDER BY c.nome ";
        $sql .= "ORDER BY p.emissao Desc";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function projecao($dataIni,$dataFim,$qtdDiasUteis,$diasPassados, $wherec){
    
        $sql1  = "Select u.nome as VENDEDOR, ";  
		$sql1 .= "( Sum(p.total) / ".$diasPassados." ) *  ".$qtdDiasUteis."  "; 
        $sql1 .= " as PROJECAOVENDAS, ";    	
        $sql2 .= "(( Count(*) / ".$diasPassados." ) * ".$qtdDiasUteis.")  ";
        $sql2 .= " as NUMERODEVENDAS, ";   
        $sql3 .= "( Sum(p.lucrobruto) / ".$diasPassados." ) * ".$qtdDiasUteis."  ";
        $sql3 .= " as PROJECAOLUCROBRUTO, ";    
        $sql4 .= "( Sum(p.margemliquida) / ".$diasPassados." ) * ".$qtdDiasUteis." ";
        $sql4 .= " as PROJECAOLUCROLIQUIDO ";
        //$sql5 .= "FROM fat_metas_mensal m ";
        //$sql5 .= "LEFT JOIN fat_pedido p on (m.vendedor=p.usrfatura) ";
        $sql5 .= "FROM fat_pedido p "; 
        //$sql5 .= "LEFT JOIN fat_metas_mensal m on (m.vendedor=p.usrfatura) and (m.mes = EXTRACT(month FROM p.emissao) ) ";
        $sql5 .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql5 .= "where ".$wherec." and (p.emissao >= '".$dataIni."') AND (p.emissao <= '".$dataFim."') ";
        $sql5 .= " and ((p.situacao = 9 ) or (p.situacao = 6)) "; 
        $sql5 .= "group by u.nome asc";
        $banco = new c_banco;

        $sql = $sql1.$sql2.$sql3.$sql4.$sql5;
                        
        $banco->exec_sql($sql);

        $banco->close_connection();

        return $banco->resultado;
    }
    
    public function forecast($dataIni,$dataFim,$qtdDiasUteis,$diasPassados,$where,$mes,$whereM, $ano ){
        $sql1  = "Select ";
        //$sql1 .= "CASE extract( month from p.emissao) ";
        //$sql1 .= "WHEN extract( month from CURDATE()) THEN ";

        $sql1 .= "(((SELECT Sum(meta) from fat_meta_mensal ";
        $sql1 .= "where ".$whereM." and (mes in ('".$mes."') )) ";
        //$sql1 .= "((Sum(M.META) ";
        $sql1 .= " - Sum(p.TOTAL)) /  (".$qtdDiasUteis."-".$diasPassados.")) ";
        
        //$sql1 .= "ELSE ";    
		//$sql1 .= "0 ";
        //$sql1 .= "END as METADIARIA, ";
        $sql1 .= " as METADIARIA, ";

        $sql2 .= "((SELECT Sum(meta) from fat_meta_mensal ";
        $sql2 .= "where ".$whereM." and (mes in ('".$mes."'))) ";
        //$sql2 .= "(Sum(M.META) ";
        $sql2 .= "- Sum(p.TOTAL)) as FALTA,";
        //$sql2 .= "CASE extract( month from p.emissao) ";
        //$sql2 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql2 .= "(( Sum(p.Total) / ".$diasPassados." ) * ".$qtdDiasUteis. ") "; 
        //$sql2 .= "ELSE ";    
		//$sql2 .= "0 ";
        //$sql2 .= "END as PROJECAOVALORVENDA, ";  
        $sql2 .= " as PROJECAOVALORVENDA, ";  
        //$sql3 .= "CASE extract( month from p.emissao) "; 
        //$sql3 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql3 .= "((Select Sum(L.Total) from FIN_LANCAMENTO L where (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') and (L.TIPOLANCAMENTO = 'P') and (( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%' )) or ( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ) or (L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'T'))) ";
        $sql3 .= "/ ".$diasPassados." ) * ".$qtdDiasUteis. " "; 
        //$sql3 .= "ELSE ";    
		//$sql3 .= "0 ";
        //$sql3 .= "END as PROJECAODESPESAS, ";
        $sql3 .= " as PROJECAODESPESAS, ";
        //$sql4 .= "CASE extract( month from p.emissao) "; 
        //$sql4 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql4 .= "((Select Sum(L.Total) from FIN_LANCAMENTO L where (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') and (L.TIPOLANCAMENTO = 'R') and (( L.GENERO = (select GENERO from fin_genero where descricao like '%RECEITAS FUTURAS%' )) or (L.TIPODOCTO = 'N') or (L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'C') or (L.TIPODOCTO = 'E') or (L.TIPODOCTO = 'R') or (L.TIPODOCTO = 'K') or (L.TIPODOCTO = 'X') or (L.TIPODOCTO = 'P'))) ";
        $sql4 .= "/ ".$diasPassados." ) * ".$qtdDiasUteis. " ";  
        //$sql4 .= "ELSE ";    
		//$sql4 .= "0 ";
        //$sql4 .= "END as PROJECAORECEITAS, ";
        $sql4 .= " as PROJECAORECEITAS, ";
        //$sql5 .= "CASE extract( month from p.emissao) WHEN extract( month from CURDATE()) THEN ((( ";
        $sql5 .= "((( Sum(p.LUCROBRUTO) - ( ";
        $sql5 .= "Select Coalesce(Sum(Total),0) as TOTAL ";
        $sql5 .= "from FIN_LANCAMENTO L where ".str_replace('p.ccusto','centrocusto',$where)." and (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') and (L.TIPOLANCAMENTO = 'P') ";
        //$sql5 .= " and(( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%' )) or ";
        //$sql5 .= "( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ) or (L.TIPODOCTO = 'B') or "; 
        //$sql5 .= "(L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'T'))
        $sql5 .= " )) ";
        //$sql5 .= "/ ".$diasPassados." ) * ".$qtdDiasUteis." ELSE 0 END as PROJECAOLUCROLIQUIDO, ";
        $sql5 .= "/ ".$diasPassados." ) * ".$qtdDiasUteis.") as PROJECAOLUCROLIQUIDO, ";
        //$sql6 .= "CASE extract( month from p.emissao) ";
        //$sql6 .= "WHEN extract( month from CURDATE()) THEN ";
		$sql6 .=  $qtdDiasUteis."-".$diasPassados." "; 
        //$sql6 .= "ELSE ";    
		//$sql6 .= "0 ";
        //$sql6 .= "END as DIASRESTANTESDOMES, ";
        $sql6 .= " as DIASRESTANTESDOMES, ";
        //$sql7 .= "CASE extract( month from p.emissao) ";
        //$sql7 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql7 .= "Sum(p.TOTAL) / (( Count(*) / ".$diasPassados." ) * ".$qtdDiasUteis.") ";
        //$sql7 .= "ELSE ";    
		//$sql7 .= "0 "; 
        //$sql7 .= "END as TICKETMEDIODEVENDAS,";
        $sql7 .= " as TICKETMEDIODEVENDAS,";
        //$sql8 .= "CASE extract( month from p.emissao) ";
        //$sql8 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql8 .= "Sum(p.LucroBruto) /  (( Count(*) / ".$diasPassados." ) * ".$qtdDiasUteis.") ";
        //$sql8 .= "ELSE ";    
		//$sql8 .= "0 "; 
        //$sql8 .= "END as LUCROBRUTOMEDIOPORVENDA,";
        $sql8 .= " as LUCROBRUTOMEDIOPORVENDA,";
        //$sql9 .= "CASE extract( month from p.emissao) ";
        //$sql9 .= "WHEN extract( month from CURDATE()) THEN "; 
        $sql9 .= "(Sum(p.LucroBruto) - ";
        $sql9 .= "( Select Coalesce(Sum(Total),0)  ";
        $sql9 .= "from FIN_LANCAMENTO L where ".str_replace('p.ccusto','centrocusto',$where)." and (L.emissao >= '".$dataIni."') AND ";
        $sql9 .= "(L.emissao <= '".$dataFim."') and (L.TIPOLANCAMENTO = 'P') ";
        //$sql9 .= " and (( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%' )) or ";
        //$sql9 .= " ( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ) or ";
        //$sql9 .= " (L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'T')) ";
        $sql9 .= ")) / (( Count(*) / ".$diasPassados." ) * ".$qtdDiasUteis.") ";
        //$sql9 .= "ELSE ";   
		//$sql9 .= "0 "; 
        //$sql9 .= "END as LUCROLIQUIDOMEDIOPORVENDA, ";
        $sql9 .= " as LUCROLIQUIDOMEDIOPORVENDA, ";
        //$sql10 .= "CASE extract( month from p.emissao) ";
        //$sql10 .= "WHEN extract( month from CURDATE()) THEN "; 
		$sql10 .= "( Count(*) / ".$diasPassados.") *  ".$qtdDiasUteis." ";
        //$sql10 .= "ELSE ";    
		//$sql10 .= "0 ";
        //$sql10 .= "END as NUMERODEVENDASPROJETADAS ";
        $sql10 .= " as NUMERODEVENDASPROJETADAS ";
        $sql11 .= "FROM fat_pedido p ";
        
        //$sql11 .= "LEFT JOIN fat_metas_mensal m on (m.vendedor=p.usrfatura) and (m.mes = ".$mes." ) ";
        //$sql11 .= "LEFT JOIN fat_meta_mensal m on (m.ccusto=p.ccusto) and (m.ano = EXTRACT(year FROM p.emissao) ) and (m.mes = EXTRACT(month FROM p.emissao) ) ";
        //$sql11 .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) AND (m.ano = ".$ano.") ";
        $sql11 .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql11 .= "where ".$where." AND (p.emissao >= '".$dataIni."') AND (p.emissao <= '".$dataFim."')  and ";
        $sql11 .= " ((p.situacao = 9) or (p.situacao = 6)) ";
        $sql11 .= "GROUP BY year(p.emissao), month(p.emissao) ";
        
        $banco = new c_banco;
        
        $sql = $sql1.$sql2.$sql3.$sql4.$sql5.$sql6.$sql7.$sql8.$sql9.$sql10.$sql11;

        $banco->exec_sql($sql);
        
        $banco->close_connection();
    
        return $banco->resultado;

    }

    public function totais($dataIni,$dataFim,$where,$wherel){ 
        $sql = "select sum(Total) AS VALORVENDA, Sum(LUCROBRUTO) as LUCROBRUTO, ";
        $sql .= "(Select Sum(Total) ";
        $sql .= "from FIN_LANCAMENTO L ";
        $sql .= "where ".$wherel." AND (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') ";
        $sql .= "and (L.TIPOLANCAMENTO = 'P') and ";
        //$sql .= "(( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%' )) or ";
        //$sql .= "( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ) or ";
        //$sql .= "(L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'D') or  (L.TIPODOCTO = 'T'))) as DESPESAS, ";
        $sql .= " (L.TOTAL > 0) ) as DESPESAS, ";
        $sql .= "SUM(CUSTOTOTAL) as CUSTOTOTAL, ((SUM(LUCROBRUTO) / SUM(TOTAL)) * 100) as MARKUP, ";
        $sql .= "(( ( SUM(TOTAL) / SUM(CUSTOTOTAL) ) - 1 ) * 100) as MARGEMBRUTA ";
        $sql .= "FROM FAT_PEDIDO ";
        $sql .= "WHERE ".$where." and (emissao >= '".$dataIni."') AND (emissao <= '".$dataFim."') and ";
        $sql .= "((situacao = 6) or (situacao = 9))";

        $banco = new c_banco;
                
        $banco->exec_sql($sql);
        
        $banco->close_connection();
    
        return $banco->resultado;
    }

    public function totaisDetalhes($dataIni,$dataFim,$where){ 

        $sql  = "select (Sum(p.CUSTOTOTAL)/Count(*)) AS CUSTOVENDEDOR, ";
        $sql .= "(Sum(p.LUCROBRUTO) / Sum(p.TOTAL)) * 100 as MARKUP, ";
        $sql .= "(((Sum(p.TOTAL) / Sum(p.CUSTOTOTAL)) -1) * 100) as MARGEMBRUTA, ";
        $sql .= "u.NOME as VENDEDOR ";
        $sql .= "FROM FAT_PEDIDO p ";
        $sql .= "LEFT JOIN AMB_USUARIO u on ( p.USRFATURA = u.USUARIO) ";
        $sql .= "WHERE ".$where." AND (p.emissao >= '".$dataIni."') AND (p.emissao <= '".$dataFim."') and ";
        $sql .= "((p.situacao = 6) or (p.situacao = 9)) ";
        $sql .= "group by p.usrfatura";

        $banco = new c_banco;
                
        $banco->exec_sql($sql);
        
        $banco->close_connection();
    
        return $banco->resultado;
    }

    public function metas($dataIni, $dataFim, $where){
        $sql   = "Select Sum(p.Total) as VALORVENDIDO, u.nome as VENDEDOR, ";
        $sql  .= "v.Meta as METADEVENDAS, ((Sum(p.Total) / v.Meta)* 100) as ICMVENDAS, ";
        $sql  .= "(v.Meta * (m.metamargem / 100) ) MMLIQUIDA, (( Sum(p.MARGEMLIQUIDA) / (v.Meta * (m.metamargem / 100) ) ) * 100) as ICM , ";
        $sql  .= "Sum(p.MARGEMLIQUIDA) as MARGEMLIQUIDA, ";
        $sql  .= "Sum(p.CUSTOTOTAL) as CUSTOTOTAL, ";
        $sql  .= "Sum(p.FRETE) as FRETE, ";
        $sql  .= "Sum(p.DESPACESSORIAS) as DESPACESSORIAS, ";
        $sql  .= "Sum(p.LUCROBRUTO) as LUCROBRUTO, ";
        $sql  .= "Count(*) as NUMVENDAS ";
        $sql  .= "FROM fat_pedido p ";
        $sql  .= "LEFT JOIN fat_meta_mensal m on (p.ccusto = m.ccusto) and (m.ano = EXTRACT(year FROM p.emissao) ) and (m.mes = EXTRACT(month FROM p.emissao) )"; 
        $sql  .= "LEFT JOIN fat_meta_mensal_vendedor v on (v.vendedor=p.usrfatura) and (m.id = v.metaid) "; 
        $sql  .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql  .= "where ".$where." and ((p.situacao = 6) or (p.situacao = 9)) and (p.emissao >= '".$dataIni."') AND (p.emissao <= '".$dataFim."') "; 
        $sql  .= "group by u.nome asc"; 
        
        $banco = new c_banco;
                
        $banco->exec_sql($sql);
        
        $banco->close_connection();
    
        return $banco->resultado;
    }

    public function financeiro($dataIni, $dataFim, $where){ 
        $sql0  = "Select Sum(L.Original) as TOTAL, ";
        $sql0 .= "(select DESCRICAO from FIN_GENERO where GENERO = L.GENERO) as GENERO, ";
        $sql0 .= "CASE L.TIPODOCTO ";
        $sql0 .= "WHEN 'B' THEN 'BOLETO' ";
        $sql0 .= "WHEN 'D' THEN 'DINHEIRO' ";
        $sql0 .= "WHEN 'C' THEN 'CARTAO DEBITO'  ";
        $sql0 .= "WHEN 'K' THEN 'CARTAO CREDITO'  ";
        $sql0 .= "WHEN 'E' THEN 'CHEQUE' ";
        $sql0 .= "WHEN 'A' THEN 'TRANFERENCIA BANCARIA' ";
        $sql0 .= "WHEN 'N' THEN 'BONUS' ";
        $sql0 .= "WHEN 'X' THEN 'A RECEBER' ";
        $sql0 .= "WHEN 'P' THEN 'PIX' ";
        $sql0 .= "ELSE '' END as TIPODOCTO, "; 
        $sql0 .= "L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql0 .= "from FIN_LANCAMENTO L ";
        $sql0 .= "left join FAT_PEDIDO p on (p.pedido = L.DOCTO) ";
        //$sql0 .= "where ".$where." AND (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') ";
        $sql0 .= "where ".$where." AND (p.emissao >= '".$dataIni."') AND (p.emissao <= '".$dataFim."') ";
        $sql0 .= "and (L.TIPOLANCAMENTO = 'R') and ";
        $sql0 .= "(( L.GENERO = (select GENERO from fin_genero where descricao like '%RECEITAS FUTURAS%' )) or ";
        $sql0 .= "(L.TIPODOCTO = 'P') or (L.TIPODOCTO = 'X') or (L.TIPODOCTO = 'N') or (L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'K') or (L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'C') or (L.TIPODOCTO = 'E') or (L.TIPODOCTO = 'A')) AND ";
        $sql0 .= "(L.docto > 0) "; 
        $sql0 .= "group by L.TIPODOCTO, L.SITPGTO, L.TIPOLANCAMENTO ";
        //$sql0 .= "group by L.genero, L.TIPODOCTO, L.TIPOLANCAMENTO ";
        $sql0 .= "union ";
        $sql1 .= "Select Sum(L.Original), ";
        $sql1 .= "(select DESCRICAO from FIN_GENERO where GENERO = L.GENERO) as GENERO, ";
        $sql1 .= "L.TIPODOCTO as TIPODOCTO, L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql1 .= "from FIN_LANCAMENTO L ";
        $sql1 .= "where ".$where." AND (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') and (L.TIPOLANCAMENTO = 'R') and ";
        $sql1 .= "( L.GENERO = (select GENERO from fin_genero where descricao like '%ENTRADA%' )) ";
        $sql1 .= "union ";
        $sql2 .= "Select Sum(L.ORIGINAL) as TOTAL, ";
        //$sql1 .= "CASE L.GENERO ";
        //$sql1 .= "WHEN ((select DESCRICAO from FIN_GENERO where GENERO = L.GENERO) = 'DESPESAS COM LOGISTICA') THEN 'DESPESAS COM LOGISTICA' "; 
        //$sql1 .= "WHEN ((select DESCRICAO from FIN_GENERO where GENERO = L.GENERO) = 'DESPESAS FIXAS') THEN 'DESPESAS FIXAS' "; 
        //$sql1 .= "ELSE '' END as GENERO, ";
        //$sql1 .= "CASE L.TIPODOCTO ";
        //$sql1 .= "WHEN 'T' THEN 'TED' ";
        //$sql1 .= "WHEN 'B' THEN 'BOLETO' ";
        //$sql1 .= "WHEN 'D' THEN 'DINHEIRO' ";
        //$sql1 .= "ELSE '' END ";
        //$sql1 .= "as TIPODOCTO, ";
        $sql2 .= "g.DESCRICAO as GENERO, ";
        $sql2 .= "L.TIPODOCTO, "; 
        $sql2 .= "L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql2 .= "from FIN_LANCAMENTO L ";
        //$sql1 .= "left join FAT_PEDIDO p on (p.pedido = L.DOCTO) ";
        $sql2 .= "Left join FIN_GENERO g on (L.GENERO = g.genero) ";
        //$sql1 .= "where ".$where." AND (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') ";
        $sql2 .= "where ".$where." AND (L.emissao >= '".$dataIni."') AND (L.emissao <= '".$dataFim."') ";
        $sql2 .= "and (L.TIPOLANCAMENTO = 'P') and (L.TOTAL > 0)";
        //$sql1 .= "(( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%' )) or ";
        //$sql1 .= "( L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ) or ";
        //$sql1 .= "(L.TIPODOCTO = 'T') or (L.TIPODOCTO = 'D') or (L.TIPODOCTO = 'B')) ";
        $sql2 .= "group by 4,2,3, 5 ";
        $sql2 .= "ORDER BY 4,5 ASC";
    
        $banco = new c_banco;

        $sql = $sql0.$sql1.$sql2;
                
        $banco->exec_sql($sql);
        
        $banco->close_connection();
    
        return $banco->resultado;
    }

    /**
     * <b> Seleciona as oportunidades na tabela fat_pedido: p_pessoa_oportunidade </b>
     * @name select_oportunidade_letra
     * @param String $letra dataInicio|dataFim|nome cliente|vendedor|situacaoOprt|Numoportunidade
     * @return Array listagem conforme SQL
     */
    public function select_oportunidade_letra($letra) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = nome cliente
         * [3] = vendedor
         * [4] = numPedido        
         * [5] = situacao        
         */
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT p.*, c.nome, D.PADRAO, 0 AS ALERTA ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "inner join amb_usuario u on u.usuario = p.usrpedido ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAOOPORTUNIDADE) AND (alias='FIN_MENU') and (campo='Oportunidade')) ";
        if (($par[4] == '')) {
            if ($par[0] != '') {
                if ($isWhere) {
                    $sql .= "where (p.emissao >= '" . $dataIni . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "(p.emissao >= '" . $dataIni . "') ";
                }
            }//if
            if ($par[1] != '') {
                if ($isWhere) {
                    $sql .= "where (p.emissao <= '" . $dataFim . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
                }
            }
            if ($par[2] != '') {
                if ($isWhere) {
                    $sql .= "where (c.nome like '%" . $par[2] . "%') ";
                    $isWhere = false;
                } else {
                    $sql .= "and (c.nome like '%" . $par[2] . "%') ";
                }
            }
            if ($par[3] != '0') {
                if ($isWhere) {
                    $sql .= "where (p.usrpedido = '" . $par[3] . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.usrpedido = '" . $par[3] . "') ";
                }
            }
            if ($par[5] != '0') {
                $sitMostra = array(); // array que vai guardar as situacoes selecionadas
                for ($i = 6; $i < count($par); $i++) {
                    $sitMostra[] = "'" . $par[$i] . "'";
                }
                $sitImplode = implode(",", $sitMostra);
                if ($isWhere) {
                    $sql .= "where (p.situacaooportunidade in (" . $sitImplode . ")) ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.situacaooportunidade in (" . $sitImplode . ")) ";
                }
            }
        } else {
            if ($par[4] != '') {
                if ($isWhere) {
                    $sql .= "where (p.id = '" . $par[4] . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.id = '" . $par[4] . "') ";
                }
            }
        }



        $sql .= "ORDER BY p.DATAALTERACAO,P.SITUACAOOPORTUNIDADE ,p.cliente ";
        // echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_letra

    public function select_pedidoVenda_entrega($letra) {


        $par = explode("|", $letra);

        $sql = "SELECT p.*, c.nome FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "inner join amb_usuario u on u.usuario = p.usrpedido ";
        $sql .= " ";
        //echo 'letra'.$letra;
        if (($letra != '|||') and ( $letra != '')) {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.cliente = " . $par[0] . ") ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.situacao = '" . $par[1] . "') ";
            } else {
                $sql .= "(p.situacao = '" . $par[1] . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }
        $sql .= "ORDER BY p.dataentrega ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_entrega
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_pedidoVendaComissao($letra) {


        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT i.id, p.emissao, c.nomereduzido, e.descricao, i.total as totalvenda, i.financeiro, ";
        $sql .= "(SELECT sum(custototal) FROM fat_pedido_item_comp c WHERE ((c.id = i.id) and (c.itempedido = i.itemestoque))) as totalcusto ";
        $sql .= "FROM fat_pedido_item i ";
        $sql .= "inner join est_produto e on e.codigo = i.itemestoque ";
        $sql .= "inner join fat_pedido p on p.id = i.id ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= " ";
        if ($letra != '||||') {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.emissao >= '" . $dataIni . "') ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }

        // Multiplo
        if ($par[3] != '') {
            $situacao = true;
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $sql .= "AND (";
            }
            $sql .= "(p.situacao = '" . $par[3] . "') ";
        }

        if ($situacao == true) {
            for ($p = 4; $p < count($par); $p++) {
                if ($par[$p] != '') {
                    $sql .= "OR (p.situacao = '" . $par[$p] . "')";
                }
            }
            $sql .= ") ";
        }


        $sql .= "ORDER BY i.id ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_Comissao
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_pedidoVendaClientesPeriodo($letra) {


        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT distinct c.nome, c.fonearea, c.fone, c.fonecontato, ";
        $sql .= "(SELECT sum(total) FROM fat_pedido s WHERE (p.cliente = s.cliente) and (s.emissao >= '" . str_replace("/", ".", $par[0]) . "') AND (s.emissao <= '" . str_replace("/", ".", $par[1]) . "')) as total ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= " ";
        if ($letra != '||||') {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.emissao >= '" . $dataIni . "') ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }

        // Multiplo
        if ($par[3] != '') {
            $situacao = true;
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $sql .= "AND (";
            }
            $sql .= "(p.situacao = '" . $par[3] . "') ";
        }

        if ($situacao == true) {
            for ($p = 4; $p < count($par); $p++) {
                if ($par[$p] != '') {
                    $sql .= "OR (p.situacao = '" . $par[$p] . "')";
                }
            }
            $sql .= ") ";
        }


        $sql .= "ORDER BY TOTAL DESC ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Calcula a quantidade do do item do cliente dentro do mes corrente
     * @name selectQuantPedidoItem
     * @return quantidade total do item
     */
    public function selectQuantPedidoItem($pessoa, $produto, $conn=null) {

        if ($pessoa != ''):
            $sql = "SELECT sum(i.qtsolicitada) as quant ";
            $sql .= "FROM fat_pedido_item i ";
            $sql .= "inner join FAT_PEDIDO p on (p.id=i.id) ";
            $sql .= "WHERE (i.itemestoque =".$produto.") and (p.cliente=".$pessoa.") and ";
            $sql .= "(i.precopromocao > 0) and ";
            $sql .= "(date_format(emissao, '%Y%m')= date_format(curdate(), '%Y%m')) ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_quant = $banco->exec_sql($sql, $conn);
            $banco->close_connection();
            if (is_array($res_quant)): 
                return $banco->resultado[0]['QUANT'];
             else: 
                return 0;

            endif;
        else:
            return 0;
        endif;
    }

// fim select_pedidoVenda_ClientePeriodo
    /**
     * Calcula o total do pedido atraves do id
     * @name select_totalPedido
     * @return ARRAY total do pedido
     */
    public function select_totalPedido() {

        if ($this->getId() != ''):
            $sql = "SELECT sum(total) as totalpedido ";
            $sql .= "FROM fat_pedido_item ";
            $sql .= "WHERE (MOTIVO = 0) AND (id = " . $this->getId() . ") ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALPEDIDO'];
                } else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

// fim select_pedidoVenda_ClientePeriodo
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function incluiPedido() {

        $banco = new c_banco;
        // $banco->sqlStrtoupper = false;

        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("FAT_GEN_ID_PEDIDO"));
            $sql = "INSERT INTO FAT_PEDIDO (ID, ";
        } else {
            $sql = "INSERT INTO FAT_PEDIDO (";
        }

        $sql .= "cliente, pedido, situacao, emissao, entregador, idnatop, condpg, entradacondpg, ";
        $sql .= "desconto, taxaentrega, total, totalrecebido, totalprodutos, moeda, contadeposito, especie, serie, horaemissao, ";
        $sql .= "genero, ccusto, odesferico, oeesferico, odcilindrico, oecilindrico, odeixo, oeeixo, odad, oead, medico, obs, credito, usrfatura, USERINSERT, DATEINSERT)";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .= $this->getCliente() . "', "
                . $this->getPedido() . ", '"
                . $this->getSituacao() . "', '"
                . $this->getEmissao('B') . "', '"
                . $this->getEntregador() . "', "
                . $this->getIdNatop() . ", "
                . $this->getCondPg() . ", "
                . $this->getEntradaCondPg('B') . ", "
                . $this->getDesconto('B') . ", "
                . $this->getTaxaEntrega('B') . ", "
                . $this->getTotal('B') . ", "
                . $this->getTotalRecebido('B') . ", "
                . $this->getTotalProdutos('B') . ", "
                . $this->getMoeda() . ", "
                . $this->getContaDeposito() . ", '"
                . $this->getEspecie() . "', '"
                . $this->getSerie() . "', '"
                . $this->getHoraEmissao('B') . "', '"
                . $this->getGenero() . "', '"
                . $this->getCentroCusto() . "', '"
                . $this->getOdEsferico() . "', '"
                . $this->getOeEsferico() . "', '"
                . $this->getOdCilindrico() . "', '"
                . $this->getOeCilindrico() . "', '"
                . $this->getOdEixo() . "', '"
                . $this->getOeEixo() . "', '"
                . $this->getOdAd() . "', '"
                . $this->getOeAd() . "', '"
                . $this->getMedico() . "', '"
                . $this->getObs() . "', "
                . $this->getCredito('B') . ", ";
        $sql .= $this->m_userid.", ".$this->m_userid.",'".date("Y-m-d H:i:s"). "' );";
        //echo strtoupper($sql) . "<BR>";
        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

// fim incluiPedido
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoTotal() {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET total = " . $this->getTotal() . ", ";
        //$sql .= "cliente = " . $this->getCliente() . ", ";
        if ($this->getSituacao() != '') {
            $sql .= "situacao = '" . $this->getSituacao() . "', ";
        }
        //$sql .= "tipoEntrega = '" . $this->getTipoEntrega() . "', '";
        //$sql .= "dataEntrega = '" . $this->getDataEntrega('B') . "', ";
        $sql .= "prazoEntrega = '" . $this->getPrazoEntrega() . "', ";
        $sql .= "pedido = '" . $this->getPedido() . "' ";
        
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoOticaTotal() {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET total = " . $this->getTotal() . ", ";
        $sql .= "cliente = " . $this->getCliente() . ", ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "dataentrega = '" . $this->getDataEntrega('B') . "', ";
        $sql .= "condpg = " . $this->getCondPg() . ", ";
        $sql .= "pedido = '" . $this->getPedido() . "', ";
        $sql .= "odesferico = '" . $this->getOdEsferico() . "', ";
        $sql .= "oeesferico = '" . $this->getOeEsferico() . "', ";
        $sql .= "odcilindrico = '" . $this->getOdCilindrico() . "', ";
        $sql .= "oecilindrico = '" . $this->getOeCilindrico() . "', ";
        $sql .= "odeixo = '" . $this->getOdEixo() . "', ";
        $sql .= "oeeixo = '" . $this->getOeEixo() . "', ";
        $sql .= "odad = '" . $this->getOdAd() . "', ";
        $sql .= "oead = '" . $this->getOeAd() . "', ";
        $sql .= "medico = '" . $this->getMedico() . "', ";
        $sql .= "obs = '" . $this->getObs() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoOticaReceita() {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET ";
        $sql .= "dataentrega = '" . $this->getDataEntrega('B') . "', ";
        $sql .= "odesferico = '" . $this->getOdEsferico() . "', ";
        $sql .= "oeesferico = '" . $this->getOeEsferico() . "', ";
        $sql .= "odcilindrico = '" . $this->getOdCilindrico() . "', ";
        $sql .= "oecilindrico = '" . $this->getOeCilindrico() . "', ";
        $sql .= "odeixo = '" . $this->getOdEixo() . "', ";
        $sql .= "oeeixo = '" . $this->getOeEixo() . "', ";
        $sql .= "odad = '" . $this->getOdAd() . "', ";
        $sql .= "oead = '" . $this->getOeAd() . "', ";
        $sql .= "medico = '" . $this->getMedico() . "', ";
        $sql .= "obs = '" . $this->getObs() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoSituacao($condPgto = null, $conn=null) {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET situacao = '" . $this->getSituacao() . "' ";
        if ($this->getCliente() > 0) {
            $sql .= ", cliente = '".$this->getCliente()."' ";
        }
        if ($condPgto != '') {
            $sql .= ", condpg = '".$this->getCondPg()."' ";
        }
        $sql .= ", obs = '" . $this->getObs() . "' ";
        $sql .= ", emissao = '" . $this->getEmissao('B') . "' ";
        if (($this->getTotal() < $this->getCredito()) and
           ($this->getTotal() > 0) and ($this->getCredito() > 0)) {
            $sql .= ",credito = " . $this->getCredito('B') . " ";           
        
        } else {
            $sql .= ",credito = " . $this->getCredito('B') . " ";            
        }
        

        if (($this->getSituacao() == 10)or($this->getSituacao() == 5)or($this->getSituacao() == 3)) {
            $sql .= ",pedido = '" . $this->getId() . "' ";
        }

        if ($this->getPrazoEntrega() != '') {
            $sql .= ",prazoEntrega = '" . $this->getPrazoEntrega() . "' ";
        }

        if (($this->getUsrAprovacao() != '') and ($this->getUsrAprovacao() != NULL) ) {
            $sql .= ",usraprovacao = " . $this->getUsrAprovacao() . " ";
        }
        
        if (!is_null($condPgto)):
            $sql .= ", condpg = " . $this->getCondPg() . " ";
        endif;

        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoNf
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoNf() {

        $sql = "UPDATE fat_pedido SET ";
        $sql .= "serie = '" . $this->getSerie() . "', ";
        $sql .= "idnatop = '" . $this->getIdNatop() . "', ";
        $sql .= "condpg = " . $this->getCondPg() . ", ";
        $sql .= "genero = '" . $this->getGenero() . "', ";
        $sql .= "contadeposito = '" . $this->getContaDeposito() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function alteraPedidoRecebimentoCupom() {

        $sql .= "cliente, pedido, situacao, emissao, entregador, idnatop, condpg, entradacondpg, ";
        $sql .= "desconto, total, moeda, contadeposito, especie, serie, horaemissao, ";
        $sql .= "genero, ccusto, obs, USERINSERT, DATEINSERT)";
        
        $sql = "UPDATE fat_pedido ";
        $sql .= "SET desconto = '" . $this->getDesconto('B') . "', ";
        $sql .= "taxaentrega = '" . $this->getTaxaEntrega('B') . "', ";
        $sql .= "totalrecebido = '" . $this->getTotalRecebido('B') . "', ";
        $sql .= "total = '" . $this->getTotal('B') . "', ";
        $sql .= "totalprodutos = '" . $this->getTotalProdutos('B') . "', ";
        $sql .= "obs = '" . $this->getObs() . "', ";
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function alteraPedido() {

        $sql .= "cliente, pedido, situacao, emissao, entregador, idnatop, condpg, entradacondpg, ";
        $sql .= "desconto, total, moeda, contadeposito, especie, serie, horaemissao, ";
        $sql .= "genero, ccusto, obs, credito, USERINSERT, DATEINSERT)";
        
        $sql = "UPDATE fat_pedido ";
        $sql .= "SET cliente = " . $this->getCliente() . ", ";
        $sql .= "pedido = " . $this->getPedido() . ", ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "emissao = '" . $this->getEmissao('B') . "', ";
        $sql .= "dataentrega = '" . $this->getDataEntrega('B') . "', ";
        $sql .= "entregador = '" . $this->getEntregador() . "', ";
        $sql .= "idnatop = " . $this->getIdNatop() . ", ";
        $sql .= "condpg = " . $this->getCondPg() . ", ";
        $sql .= "entradacondpg = '" . $this->getEntradaCondPg('B') . "', ";
        $sql .= "desconto = '" . $this->getDesconto('B') . "', ";
        $sql .= "taxaentrega = '" . $this->getTaxaEntrega('B') . "', ";
        $sql .= "total = '" . $this->getTotal('B') . "', ";
        $sql .= "totalrecebido = '" . $this->getTotalRecebido('B') . "', ";
        $sql .= "totalprodutos = '" . $this->getTotalProdutos('B') . "', ";
        $sql .= "moeda = " . $this->getMoeda() . ", ";
        $sql .= "contadeposito = " . $this->getContaDeposito() . ", ";
        $sql .= "especie = '" . $this->getEspecie() . "', ";
        $sql .= "serie = '" . $this->getSerie() . "', ";
        $sql .= "horaemissao = '" . $this->getHoraEmissao('B') . "', ";
        $sql .= "genero = '" . $this->getGenero() . "', ";
        $sql .= "ccusto = '" . $this->getCentroCusto() . "', ";
/*        $sql .= "odesferico = " . $this->getOdEsferico('B') . ", ";
        $sql .= "oeesferico = " . $this->getOeEsferico('B') . ", ";
        $sql .= "odcilindrico = " . $this->getOdCilindrico('B') . ", ";
        $sql .= "oecilindrico = " . $this->getOeCilindrico('B') . ", ";
        $sql .= "odeixo = " . $this->getOdEixo('B') . ", ";
        $sql .= "oeeixo = " . $this->getOeEixo('B') . ", ";
        $sql .= "odad = " . $this->getOdAd('B') . ", ";
        $sql .= "oead = " . $this->getOeAd('B') . ", ";
        $sql .= "medico = '" . $this->getMedico() . "', ";*/
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "credito = '" . $this->getCredito() . "', ";
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    public function excluiPedido() {

        $sql = "DELETE FROM ";
        $sql .= "fat_pedido ";
        $sql .= "WHERE (id = " . $this->getId() . "); ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_venda_item_letra($letra) {
        $par = explode("|", $letra);
        $data = date("Y-m-d");
        $isWhere = false;
        $sql = "SELECT * ";
        $sql .= "FROM est_produto ";
        if (!empty($par[0])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
//            $sql .= "(descricao like '%" . $par[0] . "%') ";
            $sql .= "(descricao like '" . $par[0] . "%') ";
        }
        if (!empty($par[1])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "(grupo = '" . $par[1] . "') ";
        }

        if ($par[2] == 'S') {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "((iniciopromocao <= '" . $data . "') and (fimpromocao >= '" . $data . "'))";
        }
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * ****************************************
     * ******* Funções Pedido Itens ***********
     * ****************************************
     */

    /**
     * Funcao para setar todos os objetos da classe de acordo com consulta no banco
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name pedido_venda_item
     * @return Todos os objetos da classe setados
     */
    public function pedido_venda_item($select=true, $arr='') {
        if ($select):
            $item = $this->select_pedido_item_id_nritem();
        else:
            $item = $arr;
        endif;
        $this->setId($item[0]['ID']);
        $this->setNrItem($item[0]['NRITEM']);
        $this->setItemEstoque($item[0]['ITEMESTOQUE']);
        $this->setItemFabricante($item[0]['ITEMFABRICANTE']);
        $this->setQtSolicitada(number_format($item[0]['QTSOLICITADA'], 4, ',', '.'));
        $this->setUnitario(number_format($item[0]['UNITARIO'], 4, ',', '.'));
        $this->setPrecoPromocao($item[0]['PRECOPROMOCAO']);
        $this->setVlrTabela(number_format($item[0]['VLRTABELA'], 2, ',', '.'));
        $this->setTotalItem(number_format($item[0]['TOTAL'], 2, ',', '.'));
        $this->setGrupoEstoque($item[0]['GRUPOESTOQUE']);
        $this->setDescricaoItem($item[0]['DESCRICAO']);

        return $item;
/*        $this->setQtAtendida(number_format($item[0]['QTATENDIDA'], 4, ',', '.'));
        $this->setAtendimento($item[0]['ATENDIMENTO']);
        $this->setDescontoItem(number_format($item[0]['DESCONTO'], 2, ',', '.'));
        $this->setFinanceiro(number_format($item[0]['FINANCEIRO'], 2, ',', '.'));
        $this->setFabricanteItem($item[0]['FABRICANTE']);
        $this->setAliqIpi(number_format($item[0]['ALIQIPI'], 2, ',', '.'));
        $this->setPeso(number_format($item[0]['PESO'], 4, ',', '.'));
        $this->setPrazo($item[0]['PRAZO']);
        $this->setAliqDesconto(number_format($item[0]['ALIQDESCONTO'], 2, ',', '.'));
        $this->setComissao(number_format($item[0]['COMISSAO'], 2, ',', '.'));
        $this->setBaseComissao(number_format($item[0]['BASECOMISSAO'], 2, ',', '.'));
        $this->setPrecoSelecionado($item[0]['PRECOSELECIONADO']);
        $this->setAutorizDesconto($item[0]['AUTORIZDESCONTO']);
        $this->setDescontoMaxAConceder(number_format($item[0]['DESCONTOMAXACONCEDER'], 2, ',', '.'));
        $this->setPerDesconto(number_format($item[0]['PERCDESCONTO'], 2, ',', '.'));
        $this->setDescontoOutros(number_format($item[0]['DESCONTOOUTROS'], 2, ',', '.'));
        $this->setQtConferida(number_format($item[0]['QTCONFERENCIA'], 4, ',', '.'));
        $this->setPercFinanceiro(number_format($item[0]['PERCFINANCEIRO'], 2, ',', '.'));*/
    }

    /**
     * Funcao de consulta ao banco de dados de acordo com as chaves Primarias: ID e NRITEM
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name select_pedido_item_id_nritem
     * @return ARRAY todos as colunas da table fat_pedido_item
     */
    public function select_pedido_item_id_nritem() {
        $sql = "SELECT i.*, e.UniFracionada as UniFracionada, p.Situacao FROM ";
        $sql .= "fat_pedido_item i ";
        $sql .= "left join fat_pedido p on (p.id=i.id) ";
        $sql .= "left join est_produto e on (e.codigo=i.itemestoque) ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') AND ";
        $sql .= "(i.nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_item() {
        $sql = "SELECT i.*, e.UniFracionada as UniFracionada FROM ";
        $sql .= "fat_pedido_item i ";
        $sql .= "left join est_produto e on (e.codigo=i.itemestoque) ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name verifica_aprovacao_pedido
     *
     * @return ARRAY  da table
     */
    public function verifica_aprovacao_pedido($conn=null) {

        $sql = "SELECT APROVACAO ";
        $sql .= "FROM FAT_PARAMETRO ";
        $sql .= "WHERE (FILIAL = " . $this->m_usercusto . ") ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @param INT GetId Chave primaria da tabela fat_pedido_item
     * @return ARRAY todos os campos da table
     */
    public function select_pedidoVenda_item_max_nritem($conn=null) {

        $sql = "SELECT max(nritem) as maxnritem ";
        $sql .= "FROM fat_pedido_item ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        $sql .= "ORDER BY id";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao de consulta ao banco de dados de acordo com o id da table fat_pedido_item
     * @name select_pedido_item_id
     * @param INT ID Chave primaria da table fat_pedido
     * @return ARRAY todos as colunas da table fat_pedido_item
     * @version 20161004
     */
    public function select_pedido_item_id($tipoConsulta=NULL) {
        
        switch ($tipoConsulta){
            case '1': // group by com lote e data fab
                // ADMV4.0
                // $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                // $sql .= "fat_pedido_item i ";
                // $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                // $sql .= "LEFT join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                // $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                // // $sql .= "group by i.ITEMESTOQUE ORDER BY I.NRITEM ASC ";
                // $sql .= "group by i.ITEMESTOQUE, e.FABLOTE, e.fabdatavalidade; ";
                $sql  = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, I.TOTAL, ";
                $sql .= "CASE WHEN P.Unifracionada = 'S' THEN I.QTSOLICITADA ";
                $sql .= "ELSE E.QUANTIDADE END as QUANTIDADE, ";
                $sql .= "I.DESCRICAO, I.UNITARIO, E.FABLOTE, E.FABDATAFABRICACAO, ";
                $sql .= "E.FABDATAVALIDADE, P.unidade, P.unifracionada, p.origem, ";
                $sql .= "p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.CODPRODUTOANVISA, p.PESO, ";
                $sql .= "I.DESCONTO, I.FRETE, I.CODIGONOTA, I.DESPACESSORIAS  FROM ";
                $sql .= "FAT_PEDIDO_ITEM I ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT join (SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE, FABLOTE, FABDATAFABRICACAO, FABDATAVALIDADE FROM EST_PRODUTO_ESTOQUE  ";
                $sql .= "WHERE IDPEDIDO='" . $this->getId() . "' GROUP BY IDPEDIDO ,CODPRODUTO, FABLOTE, FABDATAFABRICACAO, FABDATAVALIDADE) E ";
                $sql .= "ON (E.IDPEDIDO = I.ID AND E.CODPRODUTO=I.ITEMESTOQUE) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "')  and ( i.motivo = 0)";
                break;
            case '2': // group by sem lote e data fab
                // ADMV4.0
                // $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                // $sql .= "fat_pedido_item i ";
                // $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                // $sql .= "LEFT join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                // $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                // $sql .= "group by i.ITEMESTOQUE; ";
                $sql = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, I.TOTAL, E.QUANTIDADE, ";
                $sql .= "I.DESCRICAO, I.UNITARIO, P.unidade, P.unifracionada, p.origem, ";
                $sql .= "p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.CODPRODUTOANVISA, ";
                $sql .= "I.DESCONTO, I.CODIGONOTA FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT join (SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE FROM EST_PRODUTO_ESTOQUE  ";
                $sql .= "WHERE IDPEDIDO='" . $this->getId() . "' GROUP BY IDPEDIDO ,CODPRODUTO) E ";
                $sql .= "ON (E.IDPEDIDO = I.ID AND E.CODPRODUTO=I.ITEMESTOQUE) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "') and ( i.motivo = 0)";
                break;
            default: // sem lote e data fab
                $sql = "SELECT i.*, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, ";
                $sql .= "p.ncm, p.cest, p.codigobarras, I.DESCONTO, p.PRECOMINIMO FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO)  ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "')  and ( i.motivo = 0)";
        }
        
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_item_id_itemestoque($conn=null) {
        $sql = "SELECT i.* FROM ";
        $sql .= "fat_pedido_item as i ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
        $sql .= "and ((itemestoque='" . $this->itemEstoque . "') or (p.codigobarras=".$this->itemEstoque."));";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function IncluiPedidoItem($conn=null) {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "id, nritem, itemestoque, itemfabricante, qtsolicitada, qtatendida, unitario, desconto, total, ";
        $sql .= "grupoestoque, descricao, precopromocao, qtconferida, vlrtabela) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .= $this->getId() . "', '"
                . $this->getNrItem() . "', '"
                . $this->getItemEstoque() . "', '"
                . $this->getItemFabricante() . "', "
                . $this->getQtSolicitada('B') . ", "
                . $this->getQtAtendida() . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDesconto('B') . ", "
                . $this->getTotalItem('B') . ", '"
                . $this->getGrupoEstoque() . "', '"
                . $this->getDescricaoItem() . "', "
                . $this->getPrecoPromocao('B') . ", "
                . $this->getQtConferida('B') . ", "
                . $this->getVlrTabela('B') . "); ";
 //       echo strtoupper($sql) . "<BR>";
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar um registro no banco de dados na conferencia do produto
     * @name alteraPedidoItemConferencia
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraPedidoItemConferencia() {

        $sql = "UPDATE fat_pedido_item ";
        $sql .= "SET ";
        $sql .= "qtconferida = " . $this->getQtConferida('B');
        $sql .= " WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' - Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraPedidoItem($conn=null) {

        $sql = "UPDATE fat_pedido_item ";
        $sql .= "SET id = '" . $this->getId() . "', ";
        $sql .= "nritem = '" . $this->getNrItem() . "', ";
        $sql .= "itemestoque = '" . $this->getItemEstoque() . "', ";
        $sql .= "itemfabricante = '" . $this->getItemFabricante() . "', ";
        $sql .= "qtsolicitada = " . $this->getQtSolicitada('B') . ", ";
        $sql .= "qtatendida = " . $this->getQtAtendida() . ", ";
        $sql .= "unitario = " . $this->getUnitario('B') . ", ";
        $sql .= "desconto = " . $this->getDesconto('B') . ", ";
        $sql .= "total = " . $this->getTotalItem('B') . ", ";
        $sql .= "grupoestoque = '" . $this->getGrupoEstoque() . "', ";
        $sql .= "descricao = '" . $this->getDescricaoItem() . "', ";
        $sql .= "precopromocao = " . $this->getPrecoPromocao() . ", ";
        $sql .= "qtconferida = " . $this->getQtConferida('B') . ", ";
        $sql .= "vlrtabela = " . $this->getVlrTabela('B') . " ";

/*        $sql .= "atendimento = '" . $this->getAtendimento('B') . "', ";
        $sql .= "financeiro = '" . $this->getFinanceiro('B') . "', ";
        $sql .= "fabricante = '" . $this->getFabricanteItem() . "', ";
        $sql .= "aliqipi = '" . $this->getAliqIpi('B') . "', ";
        $sql .= "peso = '" . $this->getPeso('B') . "', ";
        $sql .= "prazo = '" . $this->getPrazo() . "', ";
        $sql .= "aliqdesconto = '" . $this->getAliqDesconto('B') . "', ";
        $sql .= "comissao = '" . $this->getComissao('B') . "', ";
        $sql .= "basecomissao = '" . $this->getBaseComissao('B') . "', ";
        $sql .= "precoselecionado = '" . $this->getPrecoSelecionado() . "', ";
        $sql .= "autorizdesconto = '" . $this->getAutorizDesconto() . "', ";
        $sql .= "descontomaxaconceder = '" . $this->getDescontoMaxAConceder('B') . "', ";
        $sql .= "percdesconto = '" . $this->getPerDesconto('B') . "', ";
        $sql .= "bonificado = '" . $this->getBonificado() . "', ";
        $sql .= "descontooutros = '" . $this->getDescontoOutros('B') . "', ";
        $sql .= "percfinanceiro = '" . $this->getPercFinanceiro('B') . "', ";
*/
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' - Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao de exclusao do item do pedido, no banco de dados
     * @name excluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiPedidoItem($conn=null) {
        $sql = "DELETE FROM ";
        $sql .= "fat_pedido_item ";
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' Item não localizado para Exclusão!!!';
        endif;
        return $msg;
    }
    /**
     * Funcao de exclusao de todos os itens do pedido, no banco de dados
     * @name excluiPedidoItemGeral
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiPedidoItemGeral() {
        $sql = "DELETE FROM ";
        $sql .= "fat_pedido_item ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' Item não localizado para Exclusão!!!';
        endif;
        return $msg;
    }
    
    public function agruparPedidos($pedidosSelecionados) {
        $sql  = "Select id, nritem, itemestoque, itemfabricante, qtsolicitada, ";
        $sql .= "unitario, desconto, total, grupoestoque, descricao, ";
        $sql .= "precopromocao, vlrtabela ";
        $sql .= "from fat_pedido_item ";
        
        $pedidos = '';
        $agruparPedidos = explode("|", $pedidosSelecionados); 
        for ($i=0;$i<count($agruparPedidos);$i++){
          if ($agruparPedidos[$i] > 0) {
            $pedidos .= "( ID = ".$agruparPedidos[$i]." )";
          }                      
          if (($agruparPedidos[$i+1] > 0) and ($pedidos <> "")) {
            $pedidos .= " OR ";
          }   
        }
                
        $sql .= " WHERE " .$pedidos." ";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    
    public function montaWhere($par){
        $isWhere = true;

        if ($par[4] != '') {
            $cWhere .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID=P.ID) AND (I.ITEMESTOQUE= ".$par[4].")";
            }
        if ($par[0] != '') {
            if ($isWhere) {
                $cWhere .= "where (p.emissao >= '" . $par[0] . "') ";
                $isWhere = false;
            } else {
                $cWhere .= "(p.emissao >= '" . $par[0] . "') ";
            }
        }//if
        if ($par[1] != '') {
            if ($isWhere) {
                $cWhere .= "where (p.emissao <= '" . $par[1] . "') ";
                $isWhere = false;
            } else {
                $cWhere .= "AND (p.emissao <= '" . $par[1] . "') ";
            }
        }
        if ($par[2] != '') {
            if ($isWhere) {
                $cWhere .= "where (p.cliente =" . $par[2] . ") ";
                $isWhere = false;
            } else {
                $cWhere .= "and (p.cliente =" . $par[2] . ") ";
            }
        }
        if ($par[3] != '') {
            if ($isWhere) {
                $cWhere .= "where (p.usrpedido = '" . $par[3] . "') ";
                $isWhere = false;
            } else {
                $cWhere .= "AND (p.usrpedido = '" . $par[3] . "') ";
            }
        }
        if (($par[7] != '') and ($par[7] != '0')) {
            if ($par[7] == 'N') { //considerar todos exceto situacao do parametro
                if ($isWhere) {
                  $cWhere .= "where (p.situacao <> (";
                  $isWhere = false;
                } else {
                  $cWhere .= "AND (p.situacao <> (";
                }
            } else  if ($par[7] == 'A') { //buscar mês atual
                if ($isWhere) {
                  $cWhere .= "where (p.situacao = (";
                  $isWhere = false;
                } else {
                  $cWhere .= "AND (p.situacao = (";
                }
            } else  if ($isWhere) {
                $cWhere .= "where (p.situacao in (";
                $isWhere = false;
            } else {
                $cWhere .= "AND (p.situacao in (";
            }
            
            for ($i = 8; $i < count($par); $i++) {
                if ($i == 8) {
                    $cWhere .= "'" . $par[$i] . "'";
                } else {
                    $cWhere .= ",'" . $par[$i] . "'";
                }
            }
            $cWhere .=")) ";
        }
        return $cWhere;
    }  //montaWhere
    
    public function select_pedidoVenda_letra_atual($letra) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = vendedor
         * [4] = produto        
         * [5] = reservado        
         * [6] = reservado        
         * [7] = situacao        
         */
        $par = explode("|", '|||||||N|3');
       
        $sql = "SELECT p.*, D.PADRAO, C.NOMEREDUZIDO, C.NOME, C.USERLOGIN ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=P.CLIENTE) ";
        
        $where = $this->montaWhere($par);
        
        $sql .= $where.' union '.$sql;
        
        $primeiro = date("01/m/Y");
        $ultimoDiaDoMes = date("t/m/Y");

        $dataIni = c_date::convertDateTxt($primeiro);
        $dataFim = c_date::convertDateTxt($ultimoDiaDoMes);
        
        $par = explode("|",$dataIni.'|'.$dataFim.'||||||A|3');
        $where = $this->montaWhere($par);

        $sql .= $where;        
        
        $sql = 'select * FROM ( '.$sql.') as resultado ORDER BY resultado.emissao desc';

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
      }
      
      public function verificarPedidoItem($nritem) {
        $sql = "SELECT * FROM ";
        $sql .= "fat_pedido_item ";
        $sql .= "WHERE (id = '" . $this->getId() . "') and (nritem = '".$nritem."');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';
        if ($banco->result->num_rows <= 0 ):
            $msg = 'Item não localizado!!!';
        endif;
        return $msg;
      }

      public function atualizarMotivoItem($motivo , $nritem = NULL) {
        $sql = "UPDATE fat_pedido_item SET MOTIVO = '".$motivo."' ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        
        if ($nritem > 0){
            $sql .= " and (nritem = '".$nritem."');";
        }
                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
      }

      public function atualizarTotal($total) {
        $sql = "UPDATE fat_pedido SET Total = '".$total."' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
      }

      public function atualizarPedido() {
        $sql = "UPDATE fat_pedido SET ";
        if ($this->getId() > 0 ){
          $sql .= " PEDIDO = ".$this->getId()." ";       
        }
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
      }

      /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function IncluiPedidoItemTelhas($conn=null) {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "id, nritem, itemestoque, itemfabricante, qtsolicitada, qtatendida, unitario, desconto, total, ";
        $sql .= "grupoestoque, descricao, precopromocao, qtconferida, vlrtabela, usrfatura, custo, despesas, lucrobruto, margemliquida, markup, codigonota ";
        //$sql .= "BASESUBTRIB, SUBTRIB, MVAST, BASEICMSUFDEST, ALIQFECOEPUFDEST, ";
        //$sql .= "ALIQICMSUFDEST, ALIQICMSINTER, ALIQICMSINTERPART, FECOEPUFDEST, ";
        //$sql .= "ICMSUFDEST, ICMSUFREMET, CFOP, ORIGEM, TRIBICMS, CSOSN ";
        $sql .=" ) ";
        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .= $this->getId() . "', '"
                . $this->getNrItem() . "', '"
                . $this->getItemEstoque() . "', '"
                . $this->getItemFabricante() . "', "
                . $this->getQtSolicitada('B') . ", "
                . $this->getQtAtendida() . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDesconto('B') . ", "
                . $this->getTotalItem('B') . ", '"
                . $this->getGrupoEstoque() . "', '"
                . $this->getDescricaoItem() . "', "
                . $this->getPrecoPromocao('B') . ", "
                . $this->getQtConferida('B') . ", "
                . $this->getVlrTabela('B') . ", "
                . $this->getUsrFatura() . ", "
                . $this->getCusto('B') . ", "
                . $this->getDespesas('B') . ", "
                . $this->getLucroBruto('B') . ", "                
                . $this->getMargemLiquida('B') . ", "
                . $this->getMarkUp('B') . ", '"
                . $this->getCodigoNota() . "'); ";
                /*
                . $this->getValorBcSt('B') . ", "
                . $this->getValorIcmsSt('B') . ", "
                . $this->getMvaSt('B') . ", "
                . $this->getAliqIcmsSt('B') . ", "
                . $this->getAliqRedBCST('B') . ", "  
                . $this->getAliqIcmsUfDest('B') . ", "   
                . $this->getAliqIcmsInter('B') . ", "
                . $this->getAliqIcmsInterPart('B') . ", "
                . $this->getFcpUfDest('B') . ", "
                . $this->getValorIcmsUfDest('B') . ", " 
                . $this->getValorIcmsUFRemet('B'). ", '" 
                . $this->getCFOP(). "', '" 
                . $this->getOrigem(). "', '" 
                . $this->getTribIcms(). "', '" 
                . $this->getCsosn(). "'); "; 
                */
                
 //       echo strtoupper($sql) . "<BR>";
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItemDescricao
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraPedidoItemDescricao($conn=null, $descricaoItem, $id, $nrItem) {
        $sql = "UPDATE ";
        $sql .= "fat_pedido_item ";
        $sql .= "SET ";
        $sql .= "descricao = '" . $descricaoItem . "' ";
        $sql .= "WHERE (id = '" . $id . "') AND ";
        $sql .= "(nritem = '" . $nrItem . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' Item não localizado para Alteração!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraPedidoItemTelhas($conn=null) {

        $sql = "UPDATE fat_pedido_item ";
        $sql .= "SET id = '" . $this->getId() . "', ";
        $sql .= "nritem = '" . $this->getNrItem() . "', ";
        $sql .= "itemestoque = '" . $this->getItemEstoque() . "', ";
        $sql .= "itemfabricante = '" . $this->getItemFabricante() . "', ";
        $sql .= "qtsolicitada = " . $this->getQtSolicitada('B') . ", ";
        $sql .= "qtatendida = " . $this->getQtAtendida() . ", ";
        $sql .= "unitario = " . $this->getUnitario('B') . ", ";
        $sql .= "desconto = " . $this->getDesconto('B') . ", ";
        $sql .= "total = " . $this->getTotalItem('B') . ", ";
        $sql .= "grupoestoque = '" . $this->getGrupoEstoque() . "', ";
        $sql .= "descricao = '" . $this->getDescricaoItem() . "', ";
        $sql .= "precopromocao = " . $this->getPrecoPromocao() . ", ";
        $sql .= "qtconferida = " . $this->getQtConferida('B') . ", ";
        $sql .= "vlrtabela = " . $this->getVlrTabela('B') . ", ";
        $sql .= "usrfatura = " . $this->getUsrFatura() . ", ";
        $sql .= "custo = " . $this->getCusto('B') . ", ";
        $sql .= "despesas = " . $this->getDespesas('B') . ", ";
        $sql .= "lucrobruto = " . $this->getLucroBruto('B') . ", ";
        $sql .= "margemliquida = " . $this->getMargemLiquida('B') . ", ";
        $sql .= "markup = " . $this->getMarkUp('B') . ", ";
        $sql .= "codigonota = '" . $this->getCodigoNota() . "' ";
        /*
        $sql .= "BASESUBTRIB = ". $this->getValorBcSt('B') . ", "; 
        $sql .= "SUBTRIB = " . $this->getValorIcmsSt('B') . ", "; 
        $sql .= "MVA = ".$this->getMvaSt('B') . ", ";
        $sql .= "BASEICMSUFDEST = ". $this->getAliqIcmsSt('B') . ", "; 
        $sql .= "ALIQFECOEPUFDEST = ". $this->getAliqRedBCST('B') . ", "; 
        $sql .= "ALIQICMSUFDEST = ". $this->getAliqIcmsUfDest('B') . ", ";  
        $sql .= "ALIQICMSINTER = ". $this->getAliqIcmsInter('B') . ", "; 
        $sql .= "ALIQICMSINTERPART = ". $this->getAliqIcmsInterPart('B') . ", "; 
        $sql .= "FECOEPUFDEST = ". $this->getFcpUfDest('B') . ", "; 
        $sql .= "ICMSUFDEST = ". $this->getValorIcmsUfDest('B') . ", ";  
        $sql .= "ICMSUFREMET = ". $this->getValorIcmsUFRemet('B'). " ";
        */
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' - Item não Alterado!!!';
        endif;
        return $msg;
    }

    public function select_totais($field) {

        if ($this->getId() != ''):
            $sql = "SELECT sum($field) as totalpedido ";
            $sql .= "FROM fat_pedido_item ";
            $sql .= "WHERE (MOTIVO = 0) AND (id = " . $this->getId() . ") ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALPEDIDO'];
                } else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    public function alteraPedidoTotalTelhas() {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET total = " . $this->getTotal() . ", ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        if (($this->getDataEntrega() != "") and ($this->getDataEntrega() != null)){
            $sql .= "dataEntrega = '" . $this->getDataEntrega('B') . "', ";
        } else {
            $sql .= "dataEntrega = NULL, ";
        }               
        $sql .= "pedido = '" . $this->getPedido() . "', ";
        $sql .= "custototal = " . $this->getCustoTotal('B') . ", ";
        $sql .= "despesatotal = " . $this->getDespesaTotal('B') . ", ";
        $sql .= "lucrobruto = " . $this->getLucroBruto('B') . ", ";
        $sql .= "margemliquida = " . $this->getMargemLiquida('B') . ", ";
        $sql .= "markup = " . $this->getMarkUp('B') . ", ";        
        $sql .= "obs = '" . $this->getObs() . "', ";        
        $sql .= "usrfatura = " . $this->getUsrFatura() . ", "; 
        $sql .= "credito = " . $this->getCredito() . " ";        
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    public function atualizarFieldPedido($situacaoEmitirNf = '', $condPg = '', $obs = '', $genero = '', $idnatop = '', $entrega = '') {
        $sql = "UPDATE fat_pedido ";
        $sql .= "SET pedido = '". $this->getId() ."' ";
        if ($situacaoEmitirNf != ''){
            $sql .= ",SITUACAO = ".$situacaoEmitirNf." ";
        }
        if ($obs != ''){
            $sql .= ",OBS = '".$obs."' ";
        }
        if ($condPg != ''){
            $sql .= ",CONDPG = '".$condPg."'  ";
        }
        if ($idnatop != ''){
            $sql .= ",IDNATOP = '".$idnatop."'  ";
        }
        if ($genero != ''){
            $sql .= ",GENERO = '".$genero."'  ";
        }
        if ($entrega != ''){
            $sql .= ",DATAENTREGA = '".$entrega."'  ";
        } else {
            if ($situacaoEmitirNf != '9') {
                $sql .= ",EMISSAO = '".date("Y-m-d")."'  ";
                $sql .= ",HORAEMISSAO = '".date("H:i:s")."'  ";    
            }
        } 
        
        
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function atualizarField($field, $valor) {
        $sql = "UPDATE fat_pedido ";
        if ($valor == 'NULL') {
            $sql .= "SET ".$field." = ". $valor ." ";
        } else {
            $sql .= "SET ".$field." = '". $valor ."' ";
        }
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function atualizaCampos() {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET despacessorias = " . $this->getDespAcessorias('B') . ", ";
        $sql .= "desconto = " . $this->getDesconto('B') . ", ";
        $sql .= "frete = " . $this->getFrete('B') . ", ";
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "prazoentrega = '" . $this->getPrazoEntrega() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        return '';
    }

    public function estornarFinanceiroTelhas($situacao = null) {
        $sql = "DELETE FROM FIN_LANCAMENTO ";
        $sql .= "WHERE (DOCTO = '" . $this->getId() . "') AND ";
        $sql .= "(SERIE = 'PED') "; 
        
        if ($situacao != "") {
          $sql .= " AND (SITPGTO = '".$situacao."')"; 
        }
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function alteraPedidoItemTelhasDash($descricao = '') {

        $sql = "UPDATE fat_pedido_item ";
        $sql .= "SET descricao = '" . $this->getDescricaoItem() . "' ";
        if ($this->getCusto('B') > 0 ){
            $sql .= ", custo = " . $this->getCusto() . ", ";
            $sql .= "despesas = " . $this->getDespesas('B') . ", ";
            $sql .= "lucrobruto = " . $this->getLucroBruto('B') . ", ";
            $sql .= "margemliquida = " . $this->getMargemLiquida('B') . ", ";
            $sql .= "markup = " . $this->getMarkUp('B') . " ";        
        }
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return '';
    }

    public function alteraPedidoTotalTelhasDash() {
        $sql = "SELECT Coalesce(Sum(custo),0) as custo, ";
        $sql .= "Coalesce(Sum(despesas),0) as despesas, ";
        $sql .= "Coalesce(Sum(lucrobruto),0) as lucrobruto, ";
        $sql .= "Coalesce(Sum(margemliquida),0) as margemliquida, ";
        $sql .= "Coalesce(Sum(markup),0) as markup  ";
        $sql .= "from FAT_PEDIDO_ITEM  ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            
            $sql = "UPDATE fat_pedido ";
            $sql .= "SET custototal = " . $res[0]['CUSTO'] . ", ";
            $sql .= "despesatotal = " . $res[0]['DESPESAS'] . ", ";
            $sql .= "lucrobruto = " . $res[0]['LUCROBRUTO'] . ", ";
            $sql .= "margemliquida = " . $res[0]['MARGEMLIQUIDA'] . ", ";
            $sql .= "markup = " . $res[0]['MARKUP'] . " ";        
            $sql .= "WHERE id = " . $this->getId() . ";";

            $banco = new c_banco;
            $res_up = $banco->exec_sql($sql);
            $banco->close_connection();
        
        }
        return '';
    }

    public function select_pedidoVenda_usuario($usuario, $dataIni, $dataFim) {
        
        
        $dataIni = c_date::convertDateTxt($dataIni);
        $dataFim = c_date::convertDateTxt($dataFim);
        
        $sql = "SELECT SUM(P.total) as TOTAL, D.PADRAO ";
        $sql .= "FROM FAT_PEDIDO P ";
        $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) "; 
        $sql .= "WHERE (usrfatura = ".$usuario.") and (emissao between '".$dataIni."' and '".$dataFim."') ";
        $sql .= "GROUP BY SITUACAO";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function atualizarVendedor($usrfatura, $table = 'fat_pedido') {
        $sql = "UPDATE ".$table." ";
        $sql .= "SET usrfatura = '". $usrfatura ."' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function atualizarFieldPedidoNEW($situacaoEmitirNf = '', $condPg = '', $obs = '', $genero = '', $idnatop = '', $entrega = '', $conn = null) {
        $sql = "UPDATE fat_pedido ";
        $sql .= "SET pedido = '". $this->getId() ."' ";
        if ($situacaoEmitirNf != ''){
            if ($situacaoEmitirNf== '11'){
                $sql .= ",SITUACAO = '6' ";
            } else {
                $sql .= ",SITUACAO = ".$situacaoEmitirNf." ";
            }
        }
        if ($obs != ''){
            $sql .= ",OBS = '".$obs."' ";
        }
        if ($condPg != ''){
            $sql .= ",CONDPG = '".$condPg."'  ";
        }
        if ($idnatop != ''){
            $sql .= ",IDNATOP = '".$idnatop."'  ";
        }
        if ($genero != ''){
            $sql .= ",GENERO = '".$genero."'  ";
        }
        if ($entrega != ''){
            $sql .= ",DATAENTREGA = '".$entrega."'  ";
        } else {
            if  (($situacaoEmitirNf != '9') and ($situacaoEmitirNf != '11')) {
                $sql .= ",EMISSAO = '".date("Y-m-d")."'  ";
                $sql .= ",HORAEMISSAO = '".date("H:i:s")."'  ";    
            }
        } 
        
        
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
    }

    public function atualizaPedidoVenda($total) {
        $sql = "UPDATE FAT_PEDIDO ";
        $sql .= "SET CLIENTE = '". $this->getCliente() ."',  ";
        $sql .= "SITUACAO       = '".$this->getSituacao()."', ";
        $sql .= "CONDPG         = '".$this->getCondPg()."', ";
        $sql .= "CCUSTO         = '".$this->getCentroCusto()."', ";
        $sql .= "USRFATURA      = '".$this->getUsrFatura()."', ";
        $sql .= "EMISSAO        = '".date("Y-m-d")."', ";
        $sql .= "HORAEMISSAO    = '".date("H:i:s")."', ";
        $sql .= "PRAZOENTREGA   = '".$this->getPrazoEntrega()."', ";
        $sql .= "DESPACESSORIAS = '".$this->getDespAcessorias('B')."', ";
        $sql .= "DESCONTO       = '".$this->getDesconto('B')."', ";
        $sql .= "FRETE          = '".$this->getFrete('B')."', ";
        $sql .= "OBS            = '".$this->getObs()."', ";
        $sql .= "TOTAL          = '".$total."' ";

        $sql .= "WHERE (ID = '" . $this->getId() . "');";                        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }
    public function select_todos_pedido_item(){
        $sql = "SELECT * FROM FAT_PEDIDO_ITEM ";
        $sql .=" WHERE ID = '".$this->getId()."'; ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
}

//	END OF THE CLASS
?>