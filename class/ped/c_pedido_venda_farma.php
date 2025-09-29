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
Class c_pedidoVendaFarma extends c_user {

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
    private $vlIcmsSt = NULL; // DECIMAL(11,2)
   

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


    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }


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

    function setDesconto($desconto, $format=false) {
        $this->desconto = $desconto; 
        if ($format):
                $this->desconto = number_format($this->desconto, 2, ',', '.');
        endif;
        
    }


    // function getDesconto($format = NULL) {

    //     if (!empty($this->desconto)) {
    //         if ($format == 'F') {
                
    //             return number_format((double) $this->desconto, 2, ',', '.');
    //         } else {
    //             return c_tools::moedaBd($this->desconto);
    //         }
    //     } else {
    //         return 0;
    //     }        
    // }

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

    function setUnitario($unitario) { $this->unitario = $unitario; }
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

    function setVlIcmsSt($vlIcmsSt) { $this->vlIcmsSt = $vlIcmsSt; }
    function getVlIcmsSt($format = NULL) {
        if (!empty($this->vlIcmsSt)) {
            if ($format == 'F') {
                return number_format($this->vlIcmsSt, 2, ',', '.');
            } else {
                $num = str_replace('.', '', $this->vlIcmsSt);
				$num = str_replace(',', '.', $num);
                return $num;                
            }
        } else {
            return 0;
        }
    }

    function setTotalItem() {
//        if ($this->getPrecoPromocao() != 0):
//            $this->setUnitario($this->getPrecoPromocao('B'));
//        endif;
        $qt = $this->getQtSolicitada();
        $uni = $this->getUnitario('B');
        $desc = $this->getDesconto('B');
        $st =$this->getVlIcmsSt('B');
        $tt = $qt*$uni - $desc + $st;
        $this->totalItem = str_replace('.', ',', ($this->getQtSolicitada() * $this->getUnitario('B')) - $this->getDesconto('B') + $this->getVlIcmsSt('B'));
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


    function getFrete($format = NULL) {
        if ($format == 'F') {
            return number_format($this->frete, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->frete);
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

    function setFrete($frete) {
        $this->frete = $frete;
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
    }// setPedidoVenda

     /**
     * Funcao para atualizar um campo do registro no banco de dados
     * @name atualizarField
     * @return string vazio se ocorrer com sucesso
     */
    public function atualizarField($field, $valor, $conn=null) {
        $sql = "UPDATE fat_pedido ";
        if ($valor == 'NULL') {
            $sql .= "SET ".$field." = ". $valor ." ";
        } else {
            $sql .= "SET ".$field." = '". $valor ."' ";
        }
        $sql .= "WHERE (id = '" . $this->getId() . "');";
                        
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
    }

     /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function duplicaPedidoItem($idNovo, $idAntigo, $conn=null) {
        $banco = new c_banco;        

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "id, nritem, itemestoque, itemfabricante, qtsolicitada, qtatendida, unitario, desconto, percdesconto, total, ";
        $sql .= "grupoestoque, descricao, precopromocao, qtconferida, vlrtabela) ";
        $sql .= "SELECT ".$idNovo." as ID, 
                 NRITEM, ITEMESTOQUE, ITEMFABRICANTE, QTSOLICITADA, QTATENDIDA, UNITARIO, DESCONTO, PERCDESCONTO, TOTAL, 
                 GRUPOESTOQUE, DESCRICAO, PRECOPROMOCAO, 0, VLRTABELA ";
        $sql .="  ";
        $sql .= "FROM FAT_PEDIDO_ITEM ";        
        $sql .= "WHERE ID = '".$idAntigo."'";
                
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

    public function duplicaPedido($id, $conn=null) {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $dataEmissao = date('Y-m-d'); 
        $horaEmissao = date('H:i:s');

        $sql = "INSERT INTO FAT_PEDIDO (";

        $sql .= "CLIENTE,PEDIDO,NUMOPORTUNIDADE,SITUACAO,EMISSAO,ENTREGADOR,USRFATURA,IDNATOP,TABPRECO,ENTRADATABPRECO, ";
        $sql .= "TAXAFIN,CONDPG,ENTRADACONDPG,VENCIMENTO1,DESCONTO,TOTAL,MOEDA,CONTADEPOSITO,ESPECIE,SERIE,HORAEMISSAO, ";
        $sql .= "TAXAENTREGA,TOTALRECEBIDO,GENERO,CCUSTO,TIPOENTREGA,TABELAPRECO,IPI,COMPRADOR,
                 TRANSPORTADORA,TABELAVENDA,USRPEDIDO,DTULTPEDIDOCLIENTE,PERCDESCONTO,DESCONTONF,STATUS,TOTALPRODUTOS,
                 FRETE,DTVALIDADE,PRAZOENTREGA,OBS,OS,PROTOCOLOPARCEIRO) ";
        $sql .= "SELECT 
                 CLIENTE,PEDIDO,NUMOPORTUNIDADE,'0' AS SITUACAO,'".$dataEmissao."' as EMISSAO,ENTREGADOR,USRFATURA,IDNATOP,TABPRECO,ENTRADATABPRECO,
                 TAXAFIN,CONDPG,ENTRADACONDPG,VENCIMENTO1,DESCONTO,TOTAL,MOEDA,CONTADEPOSITO,ESPECIE,SERIE,'".$horaEmissao."' as HORAEMISSAO,
                 TAXAENTREGA,TOTALRECEBIDO,GENERO,CCUSTO,TIPOENTREGA,TABELAPRECO,IPI,COMPRADOR, ";
        $sql .= "TRANSPORTADORA,TABELAVENDA,USRPEDIDO,DTULTPEDIDOCLIENTE,PERCDESCONTO,DESCONTONF,STATUS,TOTALPRODUTOS,
                 FRETE,DTVALIDADE,PRAZOENTREGA,OBS,OS,PROTOCOLOPARCEIRO ";
        $sql .= "FROM FAT_PEDIDO ";        
        $sql .= "WHERE ID = '".$id."'";
                
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
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     * @version 20161004
     */
    public function select_pedidoVenda($situacao = NULL) {

        $sql = "SELECT DISTINCT p.*, c.nome, c.nomereduzido, c.pessoa, c.fonearea, c.fone, c.celular, c.fonecontato, c.tipoend, c.tituloend, c.pessoa, G.PADRAO AS DESCENTREGA, ";
        $sql .= "if (pessoa='J',";
        $sql .= "CONCAT(SUBSTRING(cnpjcpf, 1,2), '.', SUBSTRING(cnpjcpf,3,3), '.', SUBSTRING(cnpjcpf,6,3), '/', SUBSTRING(cnpjcpf,9,4), '-', SUBSTRING(cnpjcpf,13, 2)), 
                CONCAT(SUBSTRING(cnpjcpf, 1,3), '.', SUBSTRING(cnpjcpf,4,3), '.', SUBSTRING(cnpjcpf,7,3), '-', SUBSTRING(cnpjcpf,10, 2))";
        $sql .= ") AS cnpjcpf, ";        
        $sql .= "c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.uf, c.cep, c.email, t.descricao as descpgto, ";
        $sql .="e.NfAuto, e.ModeloNF ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "LEFT join fat_cond_pgto t on t.id = p.condpg ";
        $sql .= "LEFT join est_nat_op e on (e.id = p.idnatop) "; 
        $sql .= "LEFT JOIN AMB_DDM G ON ((G.TIPO=P.TIPOENTREGA) AND (ALIAS='PED_MENU') AND (CAMPO='TIPOENTREGA')) ";
        $sql .= "WHERE (p.id = " . $this->getId() . ") ";
//        if (!empty($situacao)){
        if ($situacao == '0') {
            $sql .= "and (situacao ='" . $situacao . "') ";
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
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
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
     * @version 20161006
     */
    public function select_pedidoVenda_letra($letra, $motivos = null) {
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
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT p.*, D.PADRAO, C.NOMEREDUZIDO, C.NOME, C.USERLOGIN  ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=P.CLIENTE) ";
        if ($par[4] != '') {
            $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID=P.ID) AND (I.ITEMESTOQUE= ".$par[4].")";
            }
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
                $sql .= "where (p.cliente =" . $par[2] . ") ";
                $isWhere = false;
            } else {
                $sql .= "and (p.cliente =" . $par[2] . ") ";
            }
        }
        if ($par[3] != '') {
            if ($isWhere) {
                $sql .= "where (p.usrpedido = '" . $par[3] . "') ";
                $isWhere = false;
            } else {
                $sql .= "AND (p.usrpedido = '" . $par[3] . "') ";
            }
        }
        if (($par[7] != '') and ($par[7] != '0')) {
            if ($isWhere) {
                $sql .= "where (p.situacao in (";
                $isWhere = false;
            } else {
                $sql .= "AND (p.situacao in (";
            }
            
            for ($i = 8; $i < count($par); $i++) {
                if ($i == 8) {
                    $sql .= "'" . $par[$i] . "'";
                } else {
                    $sql .= ",'" . $par[$i] . "'";
                }
            }
            $sql .=")) ";
        }

        if (strlen($motivos) > 0) {
            $sqlMotivo = "";
            
            $par = explode("|", $motivos);
            for ($i = 0; $i < count($par); $i++) {
                if ($par[$i] != "") {
                    if ($sqlMotivo == "") {
                        $sqlMotivo .= "'" . $par[$i] . "'";
                    } else {
                        $sqlMotivo .= ",'" . $par[$i] . "'";
                    }
                }                
            }     
            $sqlMotivo = " AND (i.motivo in (" . $sqlMotivo ;       
            $sqlMotivo .=")) ";
        }

        if (strlen($sqlMotivo)>3){
            $sql .= $sqlMotivo;
        }


       // $sql .= "ORDER BY p.situacao, p.emissao, p.cliente ";
       // $sql .= "ORDER BY c.nome ";
        // $sql .= "ORDER BY p.emissao Desc";
        $sql .= "ORDER BY c.nomereduzido, p.emissao ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
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
            $sql = "SELECT sum(TOTAL) as totalpedido ";
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
        $sql .= "genero, ccusto, odesferico, oeesferico, odcilindrico, oecilindrico, odeixo, oeeixo, odad, oead, medico, obs, USERINSERT, DATEINSERT)";

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
                . $this->getObs() . "', ";
        $sql .= $this->m_userid.",'".date("Y-m-d H:i:s"). "' );";
        // echo strtoupper($sql) . "<BR>";
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
        // $sql .= "cliente = " . $this->getCliente() . ", ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "tipoEntrega = '" . $this->getTipoEntrega() . "', ";
        $sql .= "dataEntrega = '" . $this->getDataEntrega('B') . "', ";
        $sql .= "pedido = '" . $this->getPedido() . "', ";
	    $sql .= "userchange = ".$this->m_userid.", ";
	    $sql .= "datechange = '".date("Y-m-d H:i:s")."' ";        
        $sql .= "WHERE id = " . $this->getId() . ";";
        // echo strtoupper($sql)."<BR>";
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
        $sql .= "genero, ccusto, obs, USERINSERT, DATEINSERT)";
        
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
        $sql = "SELECT i.*, e.UniFracionada as UniFracionada FROM ";
        $sql .= "fat_pedido_item i ";
        $sql .= "left join est_produto e on (e.codigo=i.itemestoque) ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') AND ";
        $sql .= "(i.nritem = '" . $this->getNrItem() . "');";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
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
                
                //ALTERACAO 20-JUNHO-2024 referente a duplicidade de produto
                // $sql  = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, i.vlicmsst, I.TOTAL, ";
                // $sql .= "CASE WHEN P.Unifracionada = 'S' THEN I.QTSOLICITADA ";
                // $sql .= "ELSE E.QUANTIDADE END as QUANTIDADE, ";
                // $sql .= "I.DESCRICAO, I.UNITARIO, I.DESCONTO, E.FABLOTE, E.FABDATAFABRICACAO, E.FABDATAVALIDADE, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.CODPRODUTOANVISA FROM ";
                // $sql .= "fat_pedido_item i ";
                // $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                // $sql .= "LEFT join (SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE, FABLOTE, FABDATAFABRICACAO, FABDATAVALIDADE FROM EST_PRODUTO_ESTOQUE  ";
                // $sql .= "WHERE IDPEDIDO='" . $this->getId() . "' GROUP BY IDPEDIDO ,CODPRODUTO, FABLOTE, FABDATAFABRICACAO, FABDATAVALIDADE) E ";
                // $sql .= "ON (E.IDPEDIDO = I.ID AND E.CODPRODUTO=I.ITEMESTOQUE) ";
                // $sql .= "WHERE (i.id = '" . $this->getId() . "')  and ( i.motivo = 0)";
                $sql  = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, I.VLICMSST, I.TOTAL, ";
                $sql .= "CASE WHEN P.Unifracionada = 'S' THEN I.QTSOLICITADA ";
                $sql .= "ELSE E.QUANTIDADE END AS QUANTIDADE, ";
                $sql .= "I.DESCRICAO, I.UNITARIO, I.DESCONTO, E.FABLOTE, E.FABDATAFABRICACAO, E.FABDATAVALIDADE, P.UNIDADE, P.UNIFRACIONADA, P.ORIGEM, P.TRIBICMS, P.NCM, P.CEST, P.CODIGOBARRAS, P.CODPRODUTOANVISA ";
                $sql .= "FROM fat_pedido_item I ";
                $sql .= "INNER JOIN EST_PRODUTO P ON P.CODIGO = I.ITEMESTOQUE ";
                $sql .= "LEFT JOIN (";
                $sql .= "    SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE, ";
                $sql .= "           MIN(FABLOTE) AS FABLOTE, MIN(FABDATAFABRICACAO) AS FABDATAFABRICACAO, MIN(FABDATAVALIDADE) AS FABDATAVALIDADE ";
                $sql .= "    FROM EST_PRODUTO_ESTOQUE ";
                $sql .= "    WHERE IDPEDIDO = '" . $this->getId() . "' ";
                $sql .= "    GROUP BY IDPEDIDO, CODPRODUTO";
                $sql .= ") E ON E.IDPEDIDO = I.ID AND E.CODPRODUTO = I.ITEMESTOQUE ";
                $sql .= "WHERE I.ID = '" . $this->getId() . "' AND I.MOTIVO = 0";
                break;
            case '2': // group by sem lote e data fab
                // ADMV4.0
                // $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                // $sql .= "fat_pedido_item i ";
                // $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                // $sql .= "LEFT join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                // $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                // $sql .= "group by i.ITEMESTOQUE; ";
                $sql = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, E.QUANTIDADE, I.DESCRICAO, I.UNITARIO, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.CODPRODUTOANVISA FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT join (SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE FROM EST_PRODUTO_ESTOQUE  ";
                $sql .= "WHERE IDPEDIDO='" . $this->getId() . "' GROUP BY IDPEDIDO ,CODPRODUTO) E ";
                $sql .= "ON (E.IDPEDIDO = I.ID AND E.CODPRODUTO=I.ITEMESTOQUE) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "') and ( i.motivo = 0)";
                break;
            default: // sem lote e data fab
                $sql = "SELECT i.*, I.QTSOLICITADA AS QUANTIDADE, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras ";
                $sql .= "from fat_pedido_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
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
        $sql .= "grupoestoque, descricao, precopromocao, qtconferida, vlrtabela, vlicmsst) ";

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
                . $this->getVlIcmsSt('B') . ");";
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
        $sql .= "vlrtabela = " . $this->getVlrTabela('B') . ", ";
        $sql .= "vlicmsst = " . $this->getVlIcmsSt('B');

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
        $sql .= " WHERE (id = '" . $this->getId() . "') AND ";
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

      public function atualizarMotivoItem($motivo , $nritem) {
        $sql = "UPDATE fat_pedido_item SET MOTIVO = '".$motivo."' ";
        $sql .= "WHERE (id = '" . $this->getId() . "') and (nritem = '".$nritem."');";
                        
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
        
    public function atualizaCentroCusto($cc){
        $sql = "UPDATE FAT_PEDIDO SET ";
        $sql .= "ccusto = '" . $cc . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";"; 
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
     }

    public function atualizarTotalParam($id, $total){
        $sql = "UPDATE fat_pedido SET "; 
        $sql .= "total = '" . $total . "', ";
        $sql .= "pedido = '" . $id . "' ";
        $sql .= "WHERE (id = '" . $id . "');";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }
    
    static public function select_itens_pedido($idPedido)
    {
        $sql = "SELECT ITEMESTOQUE, ITEMFABRICANTE, QTSOLICITADA, DESCRICAO ";
        $sql .= "FROM fat_pedido_item ";
        $sql .= "WHERE id = '" . $idPedido . "';";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
}

//	END OF THE CLASS
?>