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
include_once($dir . "/../../class/ped/c_pedido_venda_nf.php");



class c_pedido_ps extends c_user
{

    /**
     * TABLE NAME FAT_PEDIDO
     */
    private $id                 = NULL;
    private $os                 = NULL;
    private $numPedido          = NULL;  // Pedido
    private $cliente            = NULL;
    private $clienteNome        = NULL;
    private $contato             = NULL;
    private $emissao             = NULL;
    private $dataAbertura         = NULL;
    private $dataFechamentoEnd  = NULL;
    private $usrAbertura         = NULL;
    private $prioridade         = NULL;
    private $prazoEntrega         = NULL;
    private $descEquipamento    = NULL;
    private $kmEntrada             = NULL;
    private $obs                 = NULL;
    private $obsOs                 = NULL;
    private $obsServicos         = NULL;
    private $solucao            = NULL;
    private $valorProduto       = NULL;
    private $valorServicos      = NULL;
    private $valorVisita        = NULL;
    private $valorDesconto      = NULL;
    private $valorTotal         = NULL;
    private $tipoCobranca       = NULL;
    private $condPgto           = NULL;
    private $conta              = NULL;
    private $genero             = NULL;
    private $centroCusto        = NULL;
    private $centroCustoEntrega = NULL;
    private $situacao           = NULL;
    private $especie            = NULL;
    private $catEquipamentoId   = NULL;
    private $idNatop            = NULL;
    private $prazoEntregaOs     = NULL;

    //FAT_PEDIDO_ITEM

    private $nrItem               = NULL;
    private $idPedidoItem         = NULL;
    private $codProduto           = NULL;
    private $codFabricante        = NULL;
    private $codProdutoNota       = NULL;
    private $desconto             = NULL;
    private $quantidadeProduto    = NULL;
    private $unidadeProduto       = NULL;
    private $valorUnitarioProduto = NULL;
    private $descricaoProduto       = NULL;
    private $valorCustoProduto       = NULL;
    private $valorDescontoProduto = NULL;
    private $percDescontoProduto  = NULL;
    private $acrescimoProduto       = NULL;
    private $valorTotalProduto    = NULL;
    private $obra                  = NULL; 


    //FAT_PEDIDO_SERVICO

    private $idServico            = NULL;
    private $idPedidoServico      = NULL;
    private $catServicoId         = NULL;
    private $idUser               = NULL;
    private $dataServico          = NULL;
    private $horaIni              = NULL;
    private $horaFim               = NULL;
    private $qtdeServico          = NULL;
    private $unidadeServico       = NULL;
    private $valorUnitarioServico = NULL;
    private $horaTotal               = NULL;
    private $custoUser               = NULL;
    private $descServico           = NULL;
    private $valorTotalServico    = NULL;


    //construtor
    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }

    function setPedido($numPedido)
    {
        $this->numPedido = $numPedido;
    }
    function getPedido()
    {
        return $this->numPedido;
    }


    function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }
    function getCliente()
    {
        return $this->cliente;
    }

    function setObra($obra)
    {
        $this->obra = $obra;
    }
    function getObra()
    {
        if ($this->obra == '' || $this->obra == NULL){
           
            return 'NULL';
        }else {
            return $this->obra;
        }
    }

    function setClienteNome()
    {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->clienteNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }
    function getClienteNome()
    {
        return $this->clienteNome;
    }

    function setContato($contato)
    {
        $this->contato = $contato;
    }
    function getContato()
    {
        return $this->contato;
    }

    function setContatoNome()
    {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->contatoNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }
    function getContatoNome()
    {
        return $this->contatoNome;
    }

    function setNumPedido($numPedido)
    {
        $this->numPedido = $numPedido;
    }
    function getNumPedido()
    {
        return isset($this->numPedido) ? $this->numPedido : 'NULL';
    }

    function setEmissao($emissao)
    {
        $this->emissao = $emissao;
    }
    function getEmissao($format = NULL)
    {
        if ($format == 'B') {
            if ($this->emissao == '') {
                return '';
            } else {
                $formatedValue = c_date::convertDateBd($this->emissao);
                return $formatedValue;
            }
        } else if ($format == 'F') {
            if ($this->emissao == '') {
                return '';
            } else {
                $aux = strtr($this->emissao, "/", "-");
                $formatedValue = date('d/m/Y', strtotime($aux));
                return $formatedValue;
            }
        } else {
            return $this->emissao;
        }
    }

    function setDataAbertura($dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }
    function getDataAbertura($format = NULL)
    {
        if ($format == 'B') {
            if ($this->dataAbertura == '') {
                return '';
            } else {
                $formatedValue = c_date::convertDateBd($this->dataAbertura);
                return $formatedValue;
            }
        } else if ($format == 'F') {
            if ($this->dataAbertura == '') {
                return '';
            } else {
                $aux = strtr($this->dataAbertura, "/", "-");
                $formatedValue = date('d/m/Y', strtotime($aux));
                return $formatedValue;
            }
        } else {
            return $this->dataAbertura;
        }
    }

    function setDataFechamentoEnd($dataFechamentoEnd)
    {
        if ($dataFechamentoEnd == "0000-00-00 00:00:00") {
            $this->dataFechamentoEnd = '';
        } else {
            $this->dataFechamentoEnd = $dataFechamentoEnd;
        }
    }
    function getDataFechamentoEnd($format = NULL)
    {
        if ($format == 'B') {
            if ($this->dataFechamentoEnd == '') {
                return '';
            } else {
                $formatedValue = c_date::convertDateTxt($this->dataFechamentoEnd);
                return $formatedValue;
            }
        } else if ($format == 'F') {
            if ($this->dataFechamentoEnd == '') {
                return '';
            } else {
                $aux = strtr($this->dataFechamentoEnd, "/", "-");
                $formatedValue = date('d/m/Y', strtotime($aux));
                return $formatedValue;
            }
        } else {
            return $this->dataFechamentoEnd;
        }
    }

    function setUsrAbertura($usrAbertura)
    {
        $this->usrAbertura = $usrAbertura;
    }
    function getUsrAbertura()
    {
        return $this->usrAbertura;
    }

    function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
    }
    function getPrioridade()
    {
        return $this->prioridade;
    }

    function setPrazoEntrega($prazoEntrega)
    {
        $this->prazoEntrega = $prazoEntrega;
    }
    function getPrazoEntrega($format = NULL)
    {
        if ($this->prazoEntrega == '') {
            return $this->prazoEntrega;
        } else {
            if ($format == 'B') {
                if ($this->prazoEntrega == '') {
                    return '';
                } else {
                    $formatedValue = c_date::convertDateTxt($this->prazoEntrega);
                    return $formatedValue;
                }
            } else if ($format == 'F') {
                if ($this->prazoEntrega == '') {
                    return '';
                } else {
                    $aux = strtr($this->prazoEntrega, "/", "-");
                    $formatedValue = date('d/m/Y', strtotime($aux));
                    return $formatedValue;
                }
            } else {
                return $this->prazoEntrega;
            }
        }
    }

    function setPrazoEntregaOs($prazoEntregaOs)
    {
        $this->prazoEntregaOs = $prazoEntregaOs;
    }
    function getPrazoEntregaOs($format = NULL)
    {
        if ($this->prazoEntregaOs == '') {
            return $this->prazoEntregaOs;
        } else {
            if ($format == 'B') {
                if ($this->prazoEntregaOs == '') {
                    return '';
                } else {
                    $formatedValue = c_date::convertDateTxt($this->prazoEntregaOs);
                    return $formatedValue;
                }
            } else if ($format == 'F') {
                if ($this->prazoEntregaOs == '') {
                    return '';
                } else {
                    $aux = strtr($this->prazoEntregaOs, "/", "-");
                    $formatedValue = date('d/m/Y', strtotime($aux));
                    return $formatedValue;
                }
            } else {
                return $this->prazoEntregaOs;
            }
        }
    }
    function setCatEquipamentoId($catEquipamentoId)
    {
        $this->catEquipamentoId = $catEquipamentoId;
    }
    function getCatEquipamentoId()
    {
        return $this->catEquipamentoId;
    }

    function setDescEquipamento($descEquipamento)
    {
        $this->descEquipamento = $descEquipamento;
    }
    function getDescEquipamento()
    {
        return $this->descEquipamento;
    }

    function setKmEntrada($kmEntrada)
    {
        $this->kmEntrada = $kmEntrada;
    }
    function getKmEntrada()
    {
        return $this->kmEntrada;
    }

    function setObs($obs)
    {
        $this->obs = $obs;
    }
    function getObs()
    {
        return $this->obs;
    }

    function setObsOs($obsOs)
    {
        $this->obsOs = $obsOs;
    }
    function getObsOs()
    {
        return $this->obsOs;
    }

    function setObsServicos($obsServicos)
    {
        $this->obsServicos = $obsServicos;
    }
    function getObsServicos()
    {
        return $this->obsServicos;
    }


    function setObsItemServico($obsItemServico)
    {
        $this->obsItemServico = $obsItemServico;
    }
    function getObsItemServico()
    {
        return $this->obsItemServico;
    }

    function setSolucao($solucao)
    {
        $this->solucao = $solucao;
    }
    function getSolucao()
    {
        return $this->solucao;
    }

    function setValorProduto($valorProduto, $format = false)
    {
        $this->valorProduto = $valorProduto;
        if ($format):
            $this->valorProduto = number_format($this->valorProduto, 2, ',', '.');
        endif;
    }

    function getValorProduto($format = NULL)
    {
        if (!empty($this->valorProduto)) {
            if ($format == 'F') {
                return number_format($this->valorProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorProduto);
            }
        } else {
            return 0;
        }
    }

    function setValorServicos($valorServicos, $format = false)
    {
        $this->valorServicos = $valorServicos;
        if ($format):
            $this->valorServicos = number_format($this->valorServicos, 2, ',', '.');
        endif;
    }

    function getValorServicos($format = NULL)
    {
        if (!empty($this->valorServicos)) {
            if ($format == 'F') {
                return number_format($this->valorServicos, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorServicos);
            }
        } else {
            return 0;
        }
    }

    function setValorFrete($valorFrete, $format = false)
    {
        $this->valorFrete = $valorFrete;
        if ($format):
            $this->valorFrete = number_format($this->valorFrete, 2, ',', '.');
        endif;
    }

    function getValorFrete($format = NULL)
    {
        if (!empty($this->valorFrete)) {
            if ($format == 'F') {
                return number_format($this->valorFrete, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorFrete);
            }
        } else {
            return 0;
        }
    }

    function setValorDespAcessorias($valorDespAcessorias, $format = false)
    {
        $this->valorDespAcessorias = $valorDespAcessorias;
        if ($format):
            $this->valorDespAcessorias = number_format($this->valorDespAcessorias, 2, ',', '.');
        endif;
    }

    function getValorDespAcessorias($format = NULL)
    {
        if (!empty($this->valorDespAcessorias)) {
            if ($format == 'F') {
                return number_format($this->valorDespAcessorias, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorDespAcessorias);
            }
        } else {
            return 0;
        }
    }

    function setValorDesconto($valorDesconto, $format = false)
    {
        $this->valorDesconto = $valorDesconto;
        if ($format):
            $this->valorDesconto = number_format($this->valorDesconto, 2, ',', '.');
        endif;
    }

    function getValorDesconto($format = NULL)
    {
        if (!empty($this->valorDesconto)) {
            if ($format == 'F') {
                return number_format($this->valorDesconto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorDesconto);
            }
        } else {
            return 0;
        }
    }

    function setValorTotal($valorTotal, $format = false)
    {
        $this->valorTotal = $valorTotal;
        if ($format):
            $this->valorTotal = number_format($this->valorTotal, 2, ',', '.');
        endif;
    }

    function getValorTotal($format = NULL)
    {
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

    function setTipoCobranca($tipoCobranca)
    {
        $this->tipoCobranca = $tipoCobranca;
    }
    function getTipoCobranca()
    {
        return $this->tipoCobranca;
    }

    function setCondPgto($condPgto)
    {
        $this->condPgto = $condPgto;
    }
    function getCondPgto()
    {
        return $this->condPgto;
    }

    function setConta($conta)
    {
        $this->conta = $conta;
    }
    function getConta()
    {
        return $this->conta;
    }

    function setGenero($genero)
    {
        $this->genero = $genero;
    }
    function getGenero()
    {
        return $this->genero;
    }

    function setCentroCusto($centroCusto)
    {
        $this->centroCusto = $centroCusto;
    }
    function getCentroCusto()
    {
        return $this->centroCusto;
    }

    function setCentroCustoEntrega($centroCustoEntrega)
    {
        $this->centroCustoEntrega = $centroCustoEntrega;
    }
    function getCentroCustoEntrega()
    {
        return $this->centroCustoEntrega;
    }

    function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }
    function getSituacao()
    {
        return $this->situacao;
    }

    function setEspecie($especie)
    {
        $this->especie = $especie;
    }
    function getEspecie()
    {
        return $this->especie;
    }

    function setIdNatop($idNatop)
    {
        $this->idNatop = $idNatop;
    }
    function getIdNatop()
    {
        return $this->idNatop;
    }

    function setOs($os)
    {
        $this->os = $os;
    }
    function getOs()
    {
        return $this->os;
    }

    //=================PEDIDO_ITEM========================

    function setIdPedidoItem($idPedidoItem)
    {
        $this->idPedidoItem = $idPedidoItem;
    }
    function getIdPedidoItem()
    {
        return $this->idPedidoItem;
    }

    function setNrItem($nrItem)
    {
        $this->nrItem = $nrItem;
    }
    function getNrItem()
    {
        return $this->nrItem;
    }

    function setCodProduto($codProduto)
    {
        $this->codProduto = $codProduto;
    }
    function getCodProduto()
    {
        return $this->codProduto;
    }

    function setCodFabricante($codFabricante)
    {
        $this->codFabricante = $codFabricante;
    }
    function getCodFabricante()
    {
        return $this->codFabricante;
    }

    function setCodProdutoNota($codProdutoNota)
    {
        $this->codProdutoNota = $codProdutoNota;
    }
    function getCodProdutoNota()
    {
        return $this->codProdutoNota;
    }

    function setQuantidadeProduto($quantidadeProduto, $format = false)
    {
        $this->quantidadeProduto = $quantidadeProduto;
        if ($format):
            $this->quantidadeProduto = number_format($this->quantidadeProduto, 2, ',', '.');
        endif;
    }

    function getQuantidadeProduto($format = NULL)
    {
        if (!empty($this->quantidadeProduto)) {
            if ($format == 'F') {
                return number_format($this->quantidadeProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->quantidadeProduto);
            }
        } else {
            return 0;
        }
    }

    function setUnidadeProduto($uniProduto)
    {
        $this->uniProduto = $uniProduto;
    }
    function getUnidadeProduto()
    {
        return $this->uniProduto;
    }

    function setVlrUnitarioProduto($valorUnitarioProduto, $format = false)
    {
        $this->valorUnitarioProduto = $valorUnitarioProduto;
        if ($format):
            $this->valorUnitarioProduto = number_format($this->valorUnitarioProduto, 2, ',', '.');
        endif;
    }

    function getVlrUnitarioProduto($format = NULL)
    {
        if (!empty($this->valorUnitarioProduto)) {
            if ($format == 'F') {
                return number_format($this->valorUnitarioProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorUnitarioProduto);
            }
        } else {
            return 0;
        }
    }

    function setDescricaoProduto($descricaoProduto)
    {
        $this->descricaoProduto = $descricaoProduto;
    }
    function getDescricaoProduto()
    {
        return $this->descricaoProduto;
    }

    function setVlrCustoProduto($valorCustoProduto, $format = false)
    {
        $this->valorCustoProduto = $valorCustoProduto;
        if ($format):
            $this->valorCustoProduto = number_format($this->valorCustoProduto, 2, ',', '.');
        endif;
    }

    function getVlrCustoProduto($format = NULL)
    {
        if (!empty($this->valorCustoProduto)) {
            if ($format == 'F') {
                return number_format($this->valorCustoProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorCustoProduto);
            }
        } else {
            return 0;
        }
    }

    function setDescontoProduto($valorDescontoProduto, $format = false)
    {
        $this->valorDescontoProduto = $valorDescontoProduto;
        if ($format):
            $this->valorDescontoProduto = number_format($this->valorDescontoProduto, 2, ',', '.');
        endif;
    }

    function getDescontoProduto($format = NULL)
    {
        if (!empty($this->valorDescontoProduto)) {
            if ($format == 'F') {
                return number_format($this->valorDescontoProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorDescontoProduto);
            }
        } else {
            return 0;
        }
    }

    function setPercDescontoProduto($percDescontoProduto, $format = false)
    {
        $this->percDescontoProduto = $percDescontoProduto;
        if ($format):
            $this->percDescontoProduto = number_format($this->percDescontoProduto, 2, ',', '.');
        endif;
    }

    function getPercDescontoProduto($format = NULL)
    {
        if (!empty($this->percDescontoProduto)) {
            if ($format == 'F') {
                return number_format($this->percDescontoProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percDescontoProduto);
            }
        } else {
            return 0;
        }
    }


    function setAcrescimoProduto($acrescimoProduto, $format = false)
    {
        $this->acrescimoProduto = $acrescimoProduto;
        if ($format):
            $this->acrescimoProduto = number_format($this->acrescimoProduto, 2, ',', '.');
        endif;
    }

    function getAcrescimoProduto($format = NULL)
    {
        if (!empty($this->acrescimoProduto)) {
            if ($format == 'F') {
                return number_format($this->acrescimoProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->acrescimoProduto);
            }
        } else {
            return 0;
        }
    }
    function setTotalProduto($valorTotalProduto, $format = false)
    {
        $this->valorTotalProduto = $valorTotalProduto;
        if ($format):
            $this->valorTotalProduto = number_format($this->valorTotalProduto, 2, ',', '.');
        endif;
    }

    function getTotalProduto($format = NULL)
    {
        if (!empty($this->valorTotalProduto)) {
            if ($format == 'F') {
                return number_format($this->valorTotalProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorTotalProduto);
            }
        } else {
            return 0;
        }
    }

    public function setDesconto($desconto, $format = false)
    {
        $this->desconto = $desconto;
        if ($format):
            $this->desconto = number_format($this->desconto, 2, ',', '.');
        endif;
    }

    public function getDesconto($format = null)
    {
        if (isset($this->desconto)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->desconto);
                    break;
                case 'F':
                    return number_format($this->desconto, 2, ',', '.');
                    break;
                default:
                    return $this->desconto;
            }
        else:
            return 0;
        endif;
    }

    //===============FIM_PEDIDO_ITEM=========================
    //===============PEDIDO_SERVICO ==========================
    function setIdServico($idServico)
    {
        $this->idServico = $idServico;
    }
    function getIdServico()
    {
        return $this->idServico;
    }

    function setIdPedidoServico($idPedidoServico)
    {
        $this->idPedidoServico = $idPedidoServico;
    }
    function getIdPedidoServico()
    {
        return $this->idPedidoServico;
    }

    function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }
    function getIdUser()
    {
        return $this->idUser;
    }

    function setDataServico($dataServico)
    {
        $this->dataServico = $dataServico;
    }
    function getDataServico($format = NULL)
    {
        return c_date::formatDateTime($format, $this->dataServico, false);
    }

    function setHoraIniServico($horaIni)
    {
        $this->horaIni = $horaIni;
    }
    function getHoraIniServico()
    {
        return $this->horaIni;
    }

    function setHoraFimServico($horaFim)
    {
        $this->horaFim = $horaFim;
    }
    function getHoraFimServico()
    {
        return $this->horaFim;
    }

    function setQuantidadeServico($qtdeServico, $format = false)
    {
        $this->qtdeServico = $qtdeServico;
        if ($format):
            $this->qtdeServico = number_format($this->qtdeServico, 2, ',', '.');
        endif;
    }

    function getQuantidadeServico($format = NULL)
    {
        if (!empty($this->qtdeServico)) {
            if ($format == 'F') {
                return number_format($this->qtdeServico, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->qtdeServico);
            }
        } else {
            return 0;
        }
    }

    function setUnidadeServico($unidadeServico)
    {
        $this->unidadeServico = $unidadeServico;
    }
    function getUnidadeServico()
    {
        return $this->unidadeServico;
    }

    function setVlrUnitarioServico($valorUnitarioServico, $format = false)
    {
        $this->valorUnitarioServico = $valorUnitarioServico;
        if ($format):
            $this->valorUnitarioServico = number_format($this->valorUnitarioServico, 2, ',', '.');
        endif;
    }

    function getVlrUnitarioServico($format = NULL)
    {
        if (!empty($this->valorUnitarioServico)) {
            if ($format == 'F') {
                return number_format($this->valorUnitarioServico, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorUnitarioServico);
            }
        } else {
            return 0;
        }
    }

    function setHoraTotalServico($horaTotal)
    {
        $this->horaTotal = $horaTotal;
    }
    function getHoraTotalServico()
    {
        return $this->horaTotal;
    }

    function setCustoUser($custoUser, $format = false)
    {
        $this->custoUser = $custoUser;
        if ($format):
            $this->custoUser = number_format($this->custoUser, 2, ',', '.');
        endif;
    }

    function getCustoUser($format = NULL)
    {
        if (!empty($this->custoUser)) {
            if ($format == 'F') {
                return number_format($this->custoUser, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->custoUser);
            }
        } else {
            return 0;
        }
    }

    function setDescricaoServico($descServico)
    {
        $this->descServico = $descServico;
    }
    function getDescricaoServico()
    {
        return $this->descServico;
    }


    function setTotalServico($valorTotalServico, $format = false)
    {
        $this->valorTotalServico = $valorTotalServico;
        if ($format):
            $this->valorTotalServico = number_format($this->valorTotalServico, 2, ',', '.');
        endif;
    }

    function getTotalServico($format = NULL)
    {
        if (!empty($this->valorTotalServico)) {
            if ($format == 'F') {
                return number_format($this->valorTotalServico, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorTotalServico);
            }
        } else {
            return 0;
        }
    }
    function setCatServicoId($catServicoId)
    {
        $this->catServicoId = $catServicoId;
    }
    function getCatServicoId()
    {
        return $this->catServicoId;
    }

    //===============FIM-SERVICO=========================

    /**
     * Funcao para setar todos os objetos da classe
     * @name setPedidoVenda
     * @param INT GetId chave primaria da table pedidos
     */
    public function buscaPedido()
    {

        $atendimento = $this->select_pedido_id();
        $this->setId($atendimento[0]['ID']);
        $this->setCliente($atendimento[0]['CLIENTE']);
        $this->setClienteNome($atendimento[0]['NOME']);
        $this->setContato($atendimento[0]['CONTATO']);
        $this->setPedido($atendimento[0]['PEDIDO']);
        $this->setEmissao($atendimento[0]['EMISSAO']);
        $this->setUsrAbertura($atendimento[0]['USRFATURA']);
        $this->setPrazoEntrega($atendimento[0]['PRAZOENTREGA']);
        $this->setObs($atendimento[0]['OBS']);
        $this->setValorServicos($atendimento[0]['VALORSERVICOS']);
        $this->setValorProduto($atendimento[0]['TOTALPRODUTOS']);
        $this->setValorFrete($atendimento[0]['FRETE']);
        $this->setValorDespAcessorias($atendimento[0]['DESPACESSORIAS']);
        $this->setValorDesconto($atendimento[0]['DESCONTO']);
        $this->setValorTotal($atendimento[0]['TOTAL']);
        $this->setCondPgto($atendimento[0]['CONDPG']);
        $this->setCentroCusto($atendimento[0]['CENTROCUSTO']);
        $this->setSituacao($atendimento[0]['SITUACAO']);
        $this->setEspecie($atendimento[0]['ESPECIE']);

        $this->setObra($atendimento[0]['OBRA_ID']);

        $this->setCatEquipamentoId($atendimento[0]['CAT_EQUIPAMENTO_ID']);
        $this->setDescEquipamento($atendimento[0]['DESCEQUIPAMENTO']);
        $this->setDataAbertura($atendimento[0]['DATAABERATEND']);
        $this->setDataFechamentoEnd($atendimento[0]['DATAFECHATEND']);
        $this->setPrazoEntregaOs($atendimento[0]['PRAZOENTREGAOS']);
        $this->setObsOs($atendimento[0]['OBSOS']);
        $this->setObsServicos($atendimento[0]['OBSSERVICO']);
        $this->setOs($atendimento[0]['OS']);
    }


    /**
     * Calcula o total do pedido atraves do id
     * @name select_ordem_compra_total
     * @return ARRAY total do pedido
     */
    public function select_produto_total()
    {

        if ($this->getIdPedidoItem() != ''):
            $sql = "SELECT sum(QTSOLICITADA * UNITARIO) as totalProduto ";
            $sql .= "FROM FAT_PEDIDO_ITEM ";
            $sql .= "WHERE (ID = " . $this->getIdPedidoItem() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALPRODUTO'];
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    /**
     * Calcula o total do pedido atraves do id
     * @name select_desconto_Produto_total
     * @return ARRAY total do pedido
     */
    public function select_desconto_produto_total()
    {

        if ($this->getIdPedidoItem() != ''):
            $sql = "SELECT sum(DESCONTO) as totalDescontoProduto ";
            $sql .= "FROM FAT_PEDIDO_ITEM ";
            $sql .= "WHERE (ID = " . $this->getIdPedidoItem() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALDESCONTOPRODUTO'];
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    /**
     * Calcula o total do pedido atraves do id
     * @name select_ordem_compra_total
     * @return ARRAY total do pedido
     */
    public function select_servicos_total()
    {

        if ($this->getIdPedidoServico() != ''):
            $sql = "SELECT sum(TOTALSERVICO) as totalServicos ";
            $sql .= "FROM FAT_PEDIDO_SERVICO ";
            $sql .= "WHERE (FAT_PEDIDO_ID = " . $this->getIdPedidoServico() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALSERVICOS'];
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }


    // fim incluiPedido
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraProdutoTotalPedido()
    {

        $sql = "UPDATE FAT_PEDIDO ";
        $sql .= "SET TOTALPRODUTOS = " . $this->getValorProduto('B') . ", ";
        $sql .= "SITUACAO = '" . $this->getSituacao() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o da ordem de compra ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }


    /**
     * Funcao de consulta ao banco de dados de acordo com as chaves Primarias: ID e NRITEM
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name select_pedido_item_id_nritem
     * @return ARRAY todos as colunas da table fat_pedido_item
     */
    public function select_produto_pedido_item()
    {
        $sql = "SELECT * FROM ";
        $sql .= "FAT_PEDIDO_ITEM  ";
        $sql .= "WHERE (ID = '" . $this->getIdPedidoItem() . "' AND NRITEM = '" . $this->getNrItem() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function select_pedido_todos_itens_id($conn = null)
    {
        $sql = "SELECT PI.* FROM ";
        $sql .= "FAT_PEDIDO_ITEM as PI ";
        $sql .= "WHERE (PI.ID = '" . $this->getIdPedidoItem() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_todos_id_servico($conn = null)
    {
        $sql = "SELECT * FROM FAT_PEDIDO_SERVICO S ";
        $sql .= "WHERE (S.FAT_PEDIDO_ID = '" . $this->getIdPedidoServico() . "') ";
        if ($this->getCatServicoId() != '') {
            $sql .= "AND (S.ID='" . $this->getCatServicoId() . "') ";
        }
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }




    public function select_atendimento_Produto_produto($conn = null)
    {
        $sql = "SELECT * FROM EST_PRODUTO ";
        $sql .= "WHERE (codigo='" . $this->getCodProduto() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_atendimento_fat_pedido_servico($conn = null)
    {
        $sql = "SELECT * FROM FAT_PEDIDO_SERVICO ";
        $sql .= "WHERE (ID ='" . $this->getCatServicoId() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function select_valores_pedido($conn = null)
    {
        $sql = "SELECT TOTAL, DESCONTO, TOTALPRODUTOS, VALORSERVICOS, FRETE, DESPACESSORIAS FROM FAT_PEDIDO ";
        $sql .= "WHERE (ID ='" . $this->getId() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiServicos($conn = null)
    {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO FAT_PEDIDO_SERVICO (";

        $sql .= "CAT_SERVICOS_ID, ID_USER, DATA, QUANTIDADE, UNIDADE, VALUNITARIO, DESCSERVICO, OBSSERVICO, ";
        $sql .= " TOTALSERVICO, FAT_PEDIDO_ID, CREATED_USER, CREATED_AT ) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=
            $this->getCatServicoId() . "', '"
            . $this->m_userid          . "', '"
            . date("Y-m-d H:i:s") .  "', "
            . $this->getQuantidadeServico() . ", '"
            . $this->getUnidadeServico() . "', '"
            . $this->getVlrUnitarioServico('B') . "', '"
            . $this->getDescricaoServico() . "', '"
            . $this->getObsItemServico() . "', "
            . $this->getTotalServico('B') . ", '"
            . $this->getIdPedidoServico() . "',"
            . $this->m_userid . ",'"
            . date("Y-m-d H:i:s") .  "' ); ";

        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para inclusão de pecas duplicadas
     * @name duplicaPedidoServicos
     * @param INT idNovo novo
     * @param INT idAntigo antigo 
     * @return INT ID FAT_PEDIDO_SERVICO se ocorrer com sucesso
     */
    public function duplicaPedidoServicos($idNovo, $idAntigo, $conn = null)
    {
        $banco = new c_banco;
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO FAT_PEDIDO_SERVICO (
            FAT_PEDIDO_ID, ID_USER, DATA, HORAINI, HORAFIM, HORATOTAL, CUSTOUSER, DESCSERVICO, OBSSERVICO, UNIDADE, QUANTIDADE, 
            VALUNITARIO, TOTALSERVICO, CAT_SERVICOS_ID, CREATED_USER, CREATED_AT)
            SELECT " . $idNovo . " as CAT_ATENDIMENTO_ID, 
                ID_USER, DATA, HORAINI, HORAFIM, HORATOTAL, CUSTOUSER, DESCSERVICO, OBSSERVICO, UNIDADE, QUANTIDADE, 
                VALUNITARIO, TOTALSERVICO, CAT_SERVICOS_ID, CREATED_USER, '" . $created_at . "' AS CREATED_AT 
            FROM FAT_PEDIDO_SERVICO 
            WHERE FAT_PEDIDO_ID = '" . $idAntigo . "' ";

        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do servico ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }


    //===============================   Produto ===================================
    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiProduto($conn = null)
    {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "ID, ITEMESTOQUE, ITEMFABRICANTE, NRITEM, QTSOLICITADA, UNITARIO, DESCRICAO  , DESCONTO, PERCDESCONTO, ";
        $sql .= " CODIGONOTA, TOTAL) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=   $this->getIdPedidoItem() . "', '"
            . $this->getCodProduto() . "', '"
            . $this->getCodFabricante() . "', '"
            . $this->getNrItem() . "', "
            . $this->getQuantidadeProduto() . ", '"
            . $this->getVlrUnitarioProduto('B') . "', '"
            . $this->getDescricaoProduto() . "', "
            . $this->getDescontoProduto() . ", "
            . $this->getPercDescontoProduto() . ", '"
            . $this->getCodProdutoNota() . "', "
            . $this->getTotalProduto('B') . " ); ";

        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);


        //logica para retornar o resultado do $banco
        // $atributos = get_object_vars($banco);
        // $string = "";
        // foreach ($atributos as $nomeAtributo => $valorAtributo) {
        //     if($nomeAtributo !== 'id_connection'){
        //         $string .= "$nomeAtributo: $valorAtributo, ";
        //     }
        // }
        // $string = rtrim($string, ', ');
        // echo 'resultado do insert--->>>' . $string . 'FIM insert';
        // die;
        //fim logica


        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }


    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraProduto($conn = null)
    {

        $sql = "UPDATE FAT_PEDIDO_ITEM SET ";
        $sql .= "ITEMFABRICANTE = '" . $this->getCodFabricante() . "', ";
        $sql .= "ITEMESTOQUE    = '" . $this->getCodProduto() . "', ";
        $sql .= "DESCRICAO      = '" . $this->getDescricaoProduto() . "', ";
        $sql .= "QTSOLICITADA   = "  . $this->getQuantidadeProduto('B') . ", ";
        $sql .= "UNITARIO       = "  . $this->getVlrUnitarioProduto('B') . ", ";
        $sql .= "DESCONTO       = "  . $this->getDescontoProduto('B') . ", ";
        $sql .= "PERCDESCONTO   = "  . $this->getPercDescontoProduto('B') . ", ";
        $sql .= "CODIGONOTA     = '" . $this->getCodProdutoNota() . "', ";
        $sql .= "TOTAL          = "  . $this->getTotalProduto('B') . " ";

        $sql .= "WHERE (ID = '" . $this->getIdPedidoItem() . "' AND NRITEM = '" . $this->getNrItem() . "' ) ";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = 'Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao para inclusão do pedido Item
     * @name IncluiPedidoItem
     * @param INT IdPedido novo
     * @param INT IdPedido antigo 
     * @return INT ID PEDIDO_ITEM se ocorrer com sucesso
     */
    public function duplicaPedidoItem($idNovo, $idAntigo, $conn = null)
    {
        $banco = new c_banco;

        $sql = "INSERT INTO FAT_PEDIDO_ITEM (";

        $sql .= "id, nritem, itemestoque, itemfabricante, qtsolicitada, qtatendida, unitario, desconto, percdesconto, total, ";
        $sql .= "grupoestoque, descricao, precopromocao, qtconferida, vlrtabela, usrfatura, custo, despesas, lucrobruto, margemliquida, markup, codigonota) ";
        $sql .= "SELECT " . $idNovo . " as ID, 
                 NRITEM, ITEMESTOQUE, ITEMFABRICANTE, QTSOLICITADA, QTATENDIDA, UNITARIO, DESCONTO, PERCDESCONTO, TOTAL, 
                 GRUPOESTOQUE, DESCRICAO, PRECOPROMOCAO, QTCONFERIDA, VLRTABELA, USRFATURA, CUSTO, DESPESAS, LUCROBRUTO, MARGEMLIQUIDA, MARKUP, CODIGONOTA ";
        $sql .= "  ";
        $sql .= "FROM FAT_PEDIDO_ITEM ";
        $sql .= "WHERE ID = '" . $idAntigo . "'";

        $res_pedidoVenda = $banco->exec_sql($sql);
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
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraServicos($conn = null)
    {

        $sql = "UPDATE FAT_PEDIDO_SERVICO SET ";
        $sql .= "QUANTIDADE = " . $this->getQuantidadeServico('B') . ", ";
        $sql .= "DESCSERVICO = '" . $this->getDescricaoServico() . "', ";
        $sql .= "UNIDADE = '" . $this->getUnidadeServico() . "', ";
        $sql .= "VALUNITARIO = " . $this->getVlrUnitarioServico('B') . ", ";
        $sql .= "TOTALSERVICO = " . $this->getTotalServico('B') . ", ";
        $sql .= "OBSSERVICO = '" . $this->getObsItemServico() . "', ";
        $sql .= "UPDATED_USER = '" . $this->m_userid . "', ";
        $sql .= "UPDATED_AT = '" . date("Y-m-d H:i:s") . "' ";
        $sql .= "WHERE (ID = '" . $this->getIdServico() . "') ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = 'Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao de exclusao do item do pedido, no banco de dados
     * @name excluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiPedidoItemProduto($conn = null)
    {
        $sql = "DELETE FROM ";
        $sql .= "FAT_PEDIDO_ITEM ";
        $sql .= "WHERE (id = '" . $this->getIdPedidoItem() . "' AND NRITEM = '" . $this->getNrItem() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';

        return $msg;
    }

    /**
     * Funcao de exclusao do item do pedido, no banco de dados
     * @name excluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiServicosItemAtendimento($conn = null)
    {
        $sql = "DELETE FROM ";
        $sql .= "FAT_PEDIDO_SERVICO ";
        $sql .= "WHERE (id = '" . $this->getIdServico() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';

        return $msg;
    }

    public function select_pedido_item_nrItem($id)
    {
        $sql = "SELECT MAX(NRITEM) AS NRITEM FROM FAT_PEDIDO_ITEM WHERE ID =" . $id . "";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function select_produto_pedItem()
    {
        $sql = "SELECT T.CODFABRICANTE, P.* FROM FAT_PEDIDO_ITEM P ";
        $sql .= "INNER JOIN EST_PRODUTO T ON  T.CODIGO=P.ITEMESTOQUE ";
        $sql .= "WHERE (FAT_PEDIDO_ID = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_servicos_atendimento()
    {
        $sql = "SELECT * FROM FAT_PEDIDO_SERVICO ";
        $sql .= "WHERE (FAT_PEDIDO_ID = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function updateField($field, $valor, $tabela)
    {
        $sql = "UPDATE  " . $tabela;
        $sql .= " SET " . $field . " = '" . $valor . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function select_atendimento_produto($id)
    {
        $sql  = "SELECT P.*, T.DESCRICAO AS DESCPRODUTO FROM FAT_PEDIDO_ITEM P ";
        $sql .= "inner join EST_PRODUTO T ON (T.CODIGO = P.ITEMESTOQUE) ";
        $sql .= "WHERE (P.ID = " . $id . ") ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_servicos($id)
    {
        $sql  = "SELECT P.*, U.NOMEREDUZIDO AS PESSOA ";
        $sql .= "FROM FAT_PEDIDO P ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = SA.ID_USER) ";
        $sql .= "inner join FAT_PEDIDO_SERVICO S ON (S.ID = SA.FAT_PEDIDO_SERVICOS_ID) ";
        $sql .= "WHERE (S.ID = " . $id . ") ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    // public function busca_dadosEmpresaCC($ccusto){
    //     $sql  = "SELECT * FROM AMB_EMPRESA WHERE CENTROCUSTO = '".$ccusto."'";

    //     $banco = new c_banco;
    //     $banco->exec_sql($sql);
    //     $banco->close_connection();
    //     return $banco->resultado;
    // }

    //=========================================================================
    //============================== FAT_PEDIDO ==========================
    //=========================================================================
    /**
     * Funcao para alterar O VALOR DO SERVIÇOS
     * @param INT ID Chave primaria da table FAT_PEDIDO
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraServicoTotalPedido()
    {

        $sql = "UPDATE FAT_PEDIDO ";
        $sql .= "SET VALORSERVICOS = " . $this->getValorServicos() . ", ";
        $sql .= "SITUACAO = '" . $this->getSituacao() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o da ordem de compra ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * Consulta para o Banco atraves do id
     * @name select_pedido_id
     * @return ARRAY todos os campos da table
     * @version 20210316
     * @author Márcio Sérgio
     */
    public function select_pedido_id()
    {

        $sql = "SELECT * ";
        $sql .= "FROM FAT_PEDIDO ";
        $sql .= "WHERE (ID = " . $this->getId() . ") ";
        $sql .= "ORDER BY ID;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Calcula o total do pedido atraves do id
     * @name select_atendimento_total_geral
     * @return ARRAY total do pedido
     * @version 20210316
     * @author Márcio Sérgio
     */
    public function select_pedido_total_geral()
    {

        if ($this->getId() != ''):
            $sql = "SELECT sum(((VALORSERVICOS)+(TOTALPRODUTOS)+(FRETE)+(DESPACESSORIAS)) - DESCONTO) as TOTALGERAL ";
            $sql .= "FROM FAT_PEDIDO ";
            $sql .= "WHERE (ID = " . $this->getId() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALGERAL'];
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    /**
     * Funcao de Pesquisa de atendimentos
     * @name select_pedido_letra
     * @param ARRAY letra paramentros para filtrar a busca 
     * @param ARRAY situacoes situacoes que sera usada para filtrar a busca
     * @return ARRAY com os atendimentos selecionados.
     */
    public function select_pedido_letra($letra, $situacoes)
    {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = numPedido       
         */
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $parSit = explode("|", $situacoes);

        $sql = "SELECT A.*, C.NOME,  D.PADRAO AS SITUACAODESC , O.PROJETO AS OBRA_DESC ";
        $sql .= "FROM FAT_PEDIDO  A ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = A.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_CLIENTE_OBRA O ON (O.ID = A.OBRA_ID) ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=A.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";

        if ($par[3] != '') {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[3]) ? '' : " $cond (a.id  = ($par[3]))";
        } else {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[0]) ? '' : " $cond (a.EMISSAO >= '$dataIni') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[1]) ? '' : " $cond (a.EMISSAO <= '$dataFim') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[2]) ? '' : " $cond (a.cliente = $par[2])";

            $sit = '';
            $count = count($parSit) - 1;
            for ($i = 1; $i < count($parSit); $i++) {
                if ($i == $count) {
                    $sit .= "'" . $parSit[$i] . "'";
                } else {
                    $sit .= "'" . $parSit[$i] . "',";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($sit) ? '' : " $cond (a.SITUACAO IN (" . $sit . ")) ";
        }

        $sql .= "ORDER BY A.ID";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * @author Tony
     * Consulta para o Banco atraves do id
     * @name select_pedido_id
     * @return ARRAY todos os campos da table com seus relacionamentos
     * @version 20210316 - Ticket
     * @author Márcio Sérgio
     */
    public function select_pedido($id)
    {

        $sql  = "SELECT A.*, S.DESCRICAO AS DESCSITUACAO, P.DESCRICAO AS DESCCONDPGTO , A.DESCEQUIPAMENTO AS EQUIPAMENTO, C.NOME, C.NOMEREDUZIDO, C.TIPOEND, C.TITULOEND, C.ENDERECO, C.NUMERO, C.COMPLEMENTO, C.BAIRRO, C.CIDADE, C.UF, C.CEP, C.PESSOA, C.FONEAREA, C.FONE, C.EMAIL, C.CNPJCPF,  U.NOMEREDUZIDO AS USERABERTURA, ";
        $sql .= " IF ( CNPJCPF <> '', IF ";
        $sql .= " (PESSOA = 'J', CONCAT(SUBSTRING(cnpjcpf, 1,2), '.' , SUBSTRING(cnpjcpf, 3,3),'.', SUBSTRING(cnpjcpf, 6,3),'/',SUBSTRING(cnpjcpf, 9,4), ";
        $sql .= " '-',SUBSTRING(cnpjcpf, 13,2)), ";
        $sql .= " CONCAT(SUBSTRING(cnpjcpf, 1,3), '.' , SUBSTRING(cnpjcpf, 4,3),'.',SUBSTRING(cnpjcpf, 7,3),'-',SUBSTRING(cnpjcpf, 10,2)) ";
        $sql .= " ), '')  AS CNPJCPF ";

        $sql .= "FROM FAT_PEDIDO A ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=A.CLIENTE) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = A.USRFATURA) ";
        $sql .= "LEFT JOIN FAT_COND_PGTO P ON (P.ID=A.CONDPG) ";
        $sql .= "LEFT JOIN CAT_SITUACAO S ON (S.ID=A.SITUACAO) ";
        $sql .= "WHERE (A.ID = " . $id . ") ";

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
     * @param INT ID Chave primaria da table FAT_PEDIDO
     * @param conn id da conexão com o banco no caso de trasaction
     * @name incluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function incluiPedido($conn = null)
    {

        $banco = new c_banco;
        // $banco->sqlStrtoupper = false;

        $sql = "INSERT INTO FAT_PEDIDO (";
        $sql .= "CLIENTE, CONTATO, USRFATURA,  TOTALPRODUTOS, VALORSERVICOS, FRETE, DESPACESSORIAS, DESCONTO, EMISSAO,  PRAZOENTREGA,  OBS, CONDPG, OBRA_ID, SITUACAO, ESPECIE, CCUSTO, CENTROCUSTOENTREGA, IDNATOP, USERINSERT, DATEINSERT )";

        $sql .= "VALUES ('";
        $sql .=   $this->getCliente() . "','"
            . $this->getContato() . "', '"
            . $this->getUsrAbertura() . "', '"
            . $this->getValorProduto() . "', '"
            . $this->getValorServicos() . "', '"
            . $this->getValorFrete() . "', '"
            . $this->getValorDespAcessorias() . "', '"
            . $this->getValorDesconto() . "', '"
            . $this->getEmissao('B') . "', '";
        $sql .= $this->getPrazoEntrega('B') . "', '"
            . $this->getObs() . "', '"
            . $this->getCondPgto() . "', "
            . $this->getObra() . ", '"
            . $this->getSituacao() . "', '"
            . $this->getEspecie() . "', "
            . $this->getCentroCusto() . ", "
            . $this->getCentroCustoEntrega() . ", "
            . $this->getIdNatop() . ", '";
        $sql .= $this->m_userid . "','" . date("Y-m-d H:i:s") . "' );";
        //echo strtoupper($sql) . "<BR>";
        $result = $banco->exec_sql($sql);
        $lastReg = $banco->insertReg;
        $banco->close_connection();

        if ($result > 0) {
            return $lastReg;
        } else {
            return 'Os dados do atendimento ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar atendimento
     * @param INT ID Chave primaria da table FAT_PEDIDO
     * @param conn(1) id da conexão com o banco no caso de trasaction
     * @name alteraAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedido($conn = null)
    {

        $sql = "UPDATE FAT_PEDIDO SET ";
        $sql .= "cliente = '" . $this->getCliente() . "', ";
        $sql .= "contato = '" . $this->getContato() . "', ";
        $sql .= "condpg = '" . $this->getCondPgto() . "', ";
        $sql .= "SITUACAO = '" . $this->getSituacao() . "', ";
        $sql .= "USRFATURA = '" . $this->getUsrAbertura() . "', ";
        $sql .= "EMISSAO = '" . $this->getEmissao('B') . "', ";
        $sql .= "prazoEntrega = '" . $this->getPrazoEntrega('B') . "', ";
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "valorServicos = '" . $this->getValorServicos('B') . "', ";
        $sql .= "TOTALPRODUTOS = '" . $this->getValorProduto('B') . "', ";
        $sql .= "FRETE = '" . $this->getValorFrete('B') . "', ";
        $sql .= "DESPACESSORIAS = '" . $this->getValorDespAcessorias('B') . "', ";
        $sql .= "Desconto = '" . $this->getValorDesconto('B') . "', ";
        $sql .= "OBRA_ID = " . $this->getObra() . " ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Atendimento ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function atualizaOsPedido($conn = null)
    {

        $dataAbertura   = $this->getDataAbertura() == '' ? 'null' : "'" . $this->getDataAbertura('B') . "'";
        $dataFechamento = $this->getDataFechamentoEnd() == '' ? 'null' : "'" . $this->getDataFechamentoEnd('B') . "'";
        $prazoEntregaOs = $this->getPrazoEntregaOs() == '' ? 'null' : "'" . $this->getPrazoEntregaOs('B') . "'";

        $sql = "UPDATE FAT_PEDIDO SET ";
        $sql .= "CAT_EQUIPAMENTO_ID = " . $this->getCatEquipamentoId() . ", ";
        $sql .= "OBSSERVICO    = '" . $this->getObsServicos() . "', ";
        $sql .= "OBSOS          = '" . $this->getObsOs() . "', ";
        $sql .= "DATAFECHATEND   = "  . $dataFechamento . ", ";
        $sql .= "DATAABERATEND       = "  . $dataAbertura . ", ";
        $sql .= "DESCEQUIPAMENTO       = '"  . $this->getDescEquipamento() . "', ";
        $sql .= "PRAZOENTREGAOS   = "  . $prazoEntregaOs . " ";

        $sql .= "WHERE (ID = '" . $this->getId() . "' ) ";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);

        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = 'Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function estornaDadosOsPedido($conn = null)
    {

        $dataAbertura   = $this->getDataAbertura() == '' ? 'null' : "'" . $this->getDataAbertura('B') . "'";
        $dataFechamento = $this->getDataFechamentoEnd() == '' ? 'null' : "'" . $this->getDataFechamentoEnd('B') . "'";
        $prazoEntregaOs = $this->getPrazoEntregaOs() == '' ? 'null' : "'" . $this->getPrazoEntregaOs('B') . "'";

        $sql = "UPDATE FAT_PEDIDO SET ";
        $sql .= "CAT_EQUIPAMENTO_ID = null, ";
        $sql .= "OBSSERVICO    = null, ";
        $sql .= "OBSOS          = null, ";
        $sql .= "DATAFECHATEND   = null, ";
        $sql .= "DATAABERATEND       = null, ";
        $sql .= "DESCEQUIPAMENTO       = null, ";
        $sql .= "PRAZOENTREGAOS   = null ";

        $sql .= "WHERE (ID = '" . $this->getId() . "' ) ";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);

        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = 'Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao para duplicar pedido
     * @name duplicaPedido
     * @return INT ID PEDIDO se ocorrer com sucesso
     */
    public function duplicaPedido($conn = null)
    {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $situacao = 5; // COTAÇÂO
        $emissao = date('Y-m-d');
        $horaEmissao = date('H:i:s');

        $sql = "INSERT INTO FAT_PEDIDO (";

        $sql .= "CLIENTE,PEDIDO,NUMOPORTUNIDADE,SITUACAO,EMISSAO,ENTREGADOR,USRFATURA,IDNATOP,TABPRECO,ENTRADATABPRECO, ";
        $sql .= "TAXAFIN,CONDPG,ENTRADACONDPG,VENCIMENTO1,DESCONTO,TOTAL,MOEDA,CONTADEPOSITO,ESPECIE,SERIE,HORAEMISSAO, ";
        $sql .= "TAXAENTREGA,TOTALRECEBIDO,GENERO,CCUSTO,TIPOENTREGA,TABELAPRECO,IPI,
                 TRANSPORTADORA,TABELAVENDA,USRPEDIDO,DTULTPEDIDOCLIENTE,PERCDESCONTO,DESCONTONF,STATUS,TOTALPRODUTOS,
                 FRETE,DTVALIDADE,PRAZOENTREGA,OBS,OS,PROTOCOLOPARCEIRO,CUSTOTOTAL,CREDITO,DESPESATOTAL, ";
        $sql .= "LUCROBRUTO,MARGEMLIQUIDA,MARKUP,DESCONTOGERAL,DESPACESSORIAS,PLACAVEICULO,VOLPESOLIQ,VOLPESOBRUTO,CENTROCUSTOENTREGA,VOLMARCA,VOLESPECIE,VOLUME,MODFRETE,
                 VALORSERVICOS) ";
        $sql .= "SELECT 
                 CLIENTE,PEDIDO,NUMOPORTUNIDADE," . $situacao . " as SITUACAO,'" . $emissao . "' as EMISSAO, ENTREGADOR,USRFATURA,IDNATOP,TABPRECO,ENTRADATABPRECO,
                 TAXAFIN,CONDPG,ENTRADACONDPG,VENCIMENTO1,DESCONTO,TOTAL,MOEDA,CONTADEPOSITO,ESPECIE,SERIE,'" . $horaEmissao . "' as HORAEMISSAO,
                 TAXAENTREGA,TOTALRECEBIDO,GENERO,CCUSTO,TIPOENTREGA,TABELAPRECO,IPI, ";
        $sql .= "TRANSPORTADORA,TABELAVENDA,USRPEDIDO,DTULTPEDIDOCLIENTE,PERCDESCONTO,DESCONTONF,STATUS,TOTALPRODUTOS,
                 FRETE,DTVALIDADE,PRAZOENTREGA,OBS,OS,PROTOCOLOPARCEIRO,CUSTOTOTAL,CREDITO,DESPESATOTAL,
                 LUCROBRUTO,MARGEMLIQUIDA,MARKUP,DESCONTOGERAL,DESPACESSORIAS,PLACAVEICULO,VOLPESOLIQ,VOLPESOBRUTO,
                 CENTROCUSTOENTREGA,VOLMARCA,VOLESPECIE,VOLUME,MODFRETE, VALORSERVICOS";
        $sql .= "  ";
        $sql .= "FROM FAT_PEDIDO ";
        $sql .= "WHERE ID = '" . $this->getId() . "'";

        //echo strtoupper($sql) . "<BR>";
        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $idGerado = $banco->insertReg;
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $idGerado;
        } else {
            return 'Os dados do Pedido ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para excluir atendimento
     * @param INT ID Chave primaria da table FAT_PEDIDO
     * @param conn id da conexão com o banco no caso de trasaction
     * @name excluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function excluiPedido($conn = null)
    {
        $sql = "DELETE FROM ";
        $sql .= "FAT_PEDIDO ";
        $sql .= "WHERE (id = '" . $this->getId() . "')";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Atendimento ' . $this->getId() . ' n&atilde;o foi excluido!';
        }
    }

    /**
     * <b> É responsavel por calcular rateio dos descontos</b>
     * @name calculoImpostos
     * @param vazio
     * @return atualiza desconto
     */
    function calculaImpostos($desconto = false)
    {

        if ($this->getId() > 0) {
            if ($desconto) { // zera desconto pedido item
                $sql = "UPDATE  ";
                $sql .= " fat_pedido_item  SET DESCONTO = 0 ";
                $sql .= "WHERE (id = " . $this->getId() . ") ";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
            }

            $totalNF = $this->select_totalPedido(); // Total do pedido_item 
            $descontoNF = $this->select_totais('DESCONTO'); // Totais desconto pedido_item
            $total = $totalNF;
            $despAcessorias = $this->getValorDespAcessorias('B'); // despesas acessorias do pedido
            $frete = $this->getValorFrete('B');          // frete do pedido
            $descontoGeral = $this->getDesconto('B');  // desconto digitado no pedido form

            $despAcessoriasDist = 0;
            $freteDist = 0;
            $descontoGeralDist = 0;
            $custototal = 0;
            $margemliquida = 0;
            $markup = 0;
            $lucrobruto = null;
            $totalNF = 0;

            $arrItemPedido = $this->select_pedido_item_id();

            $totalDescontoItem = $descontoNF;
            $this->setDesconto($descontoGeral);

            for ($i = 0; $i < count($arrItemPedido); $i++) {
                $sqlFields = '';
                $custototal += $arrItemPedido[$i]['CUSTO'];
                $lucrobruto += $arrItemPedido[$i]['LUCROBRUTO'];
                $margemliquida += $arrItemPedido[$i]['MARGEMLIQUIDA'];
                $markup += $arrItemPedido[$i]['MARKUP'];

                $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QTSOLICITADA'] * $arrItemPedido[$i]['UNITARIO'];
                $totalNF += $arrItemPedido[$i]['TOTAL'];

                //if ($totalDescontoItem == 0){
                if ($descontoGeral > 0) {
                    $perc = ($arrItemPedido[$i]['TOTAL'] / $total) * 100;
                    $vlrDescontoGeral = round(($descontoGeral * ($perc / 100)), 2);
                    $descontoGeralDist += $vlrDescontoGeral;
                    if ($i == (count($arrItemPedido) - 1)) {
                        if ($descontoGeralDist > $descontoGeral) {
                            $vlrDescontoGeral = $vlrDescontoGeral - ($descontoGeralDist - $descontoGeral);
                        } else if ($descontoGeralDist < $descontoGeral) {
                            $vlrDescontoGeral = $vlrDescontoGeral + ($descontoGeral - $descontoGeralDist);
                        }
                    }
                    $percDescontoItem = (($vlrDescontoGeral * 100) / $arrItemPedido[$i]['TOTAL']);
                    $percDescontoItem = round($percDescontoItem, 2);
                    $sqlFields .= 'percdesconto = ' . $percDescontoItem . ', desconto = ' . $vlrDescontoGeral;
                    //$sqlFields .= ', Total = '.$arrItemPedido[$i]['TOTAL'].' - desconto ';   
                } else {
                    $sqlFields .= ' percdesconto = 0, desconto = 0 ';
                }

                //}
                if ($despAcessorias > 0) {
                    $perc = ($arrItemPedido[$i]['TOTAL'] / $total) * 100;
                    $vlrDespAcessorias = round(($despAcessorias * ($perc / 100)), 2);
                    $despAcessoriasDist += $vlrDespAcessorias;
                    if ($i == (count($arrItemPedido) - 1)) {
                        if ($despAcessoriasDist > $despAcessorias) {
                            $vlrDespAcessorias = $vlrDespAcessorias - ($despAcessoriasDist - $despAcessorias);
                        } else if ($despAcessoriasDist < $despAcessorias) {
                            $vlrDespAcessorias = $vlrDespAcessorias + ($despAcessorias - $despAcessoriasDist);
                        }
                    }
                    if ($sqlFields <> "") {
                        $sqlFields .= ', despAcessorias = ' . $vlrDespAcessorias;
                    } else {
                        $sqlFields .= ' despAcessorias = ' . $vlrDespAcessorias;
                    }
                } else {
                    if ($sqlFields == "") {
                        $sqlFields .= ' despAcessorias = 0 ';
                    } else {
                        $sqlFields .= ', despAcessorias = 0 ';
                    }
                }

                if ($frete > 0) {
                    $perc = ($arrItemPedido[$i]['TOTAL'] / $total) * 100;
                    $vlrFrete = round(($frete * ($perc / 100)), 2);
                    $freteDist += $vlrFrete;
                    if ($i == (count($arrItemPedido) - 1)) {
                        if ($freteDist > $frete) {
                            $vlrFrete = $vlrFrete - ($freteDist - $frete);
                        } else if ($freteDist < $frete) {
                            $vlrFrete = $vlrFrete + ($frete - $freteDist);
                        }
                    }
                    if ($sqlFields <> "") {
                        $sqlFields .= ', frete = ' . $vlrFrete;
                    } else {
                        $sqlFields .= ' frete = ' . $vlrFrete;
                    }
                } else {
                    $sqlFields .= ', frete = 0 ';
                }

                $banco = new c_banco;
                $sql = 'UPDATE FAT_PEDIDO_ITEM SET ' . $sqlFields . " WHERE ID = " . $arrItemPedido[$i]['ID'] . " and NRITEM = " . $arrItemPedido[$i]['NRITEM'];
                $banco->exec_sql($sql);
                $banco->close_connection();
            }


            $sqlField = "";
            $banco = new c_banco;
            if ($frete > 0) {
                $sqlField = ' frete = ' . $frete;
            } else {
                $sqlField = ' frete = 0 ';
            }

            if ($despAcessorias > 0) {
                if ($sqlField <> "") {
                    $sqlField .= ', despacessorias = ' . $despAcessorias;
                } else {
                    $sqlField = ' despacessorias = ' . $despAcessorias;
                }
            } else {
                $sqlField .= ', despacessorias =  0 ';
            }
            if ($descontoGeral > 0) {
                $totalDescontoItem = $descontoGeral;
            }

            if (($descontoGeral > 0)) {
                if ($sqlField <> "") {
                    $sqlField .= ', desconto = ' . $descontoGeral;
                } else {
                    $sqlField = ' desconto = ' . $descontoGeral;
                }
            } else {
                $sqlField .= ', desconto = 0 ';
            }

            //$totalPedido = ($total +$frete + $despAcessorias) - $descontoGeral;
            $totalPedido = ($totalNF + $frete + $despAcessorias) - $descontoGeral;
            if ($sqlField <> "") {
                $sqlField .= ', total = ' . $totalPedido;
            } else {
                $sqlField = ' total = ' . $totalPedido;
            }

            $sqlField .= ", obs = '" . $this->getObs() . "'" . ", prazoentrega = '" . $this->getPrazoEntrega('B') . "'";


            $lucrobruto = $totalPedido - $custototal;
            $margemliquida = $lucrobruto;

            if ($lucrobruto != 0 && $totalPedido != 0) {
                $markup = ($lucrobruto / $totalPedido) * 100;
            }

            $sqlFieldTotais = ', CUSTOTOTAL = ' . $custototal . ', LUCROBRUTO = ' . $lucrobruto . ', ';
            $sqlFieldTotais .= 'MARGEMLIQUIDA = ' . $margemliquida . ', MARKUP = ' . $markup . ' ';


            $sql = 'UPDATE FAT_PEDIDO SET ' . $sqlField . $sqlFieldTotais . ' WHERE ID = ' . $this->getId();
            $banco->exec_sql($sql);
            $banco->close_connection();
        }
    }

    /* Calcula o total do pedido atraves do id
     * @name select_totalPedido
     * @return ARRAY total do pedido*/
    public function select_totalPedido()
    {

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
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    public function select_totais($field)
    {

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
                }
            else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

    /* Funcao de consulta ao banco de dados de acordo com o id da table fat_pedido_item
     * @name select_pedido_item_id
     * @param INT ID Chave primaria da table fat_pedido
     * @return ARRAY todos as colunas da table fat_pedido_item
     * @version 20161004*/
    public function select_pedido_item_id($tipoConsulta = NULL)
    {

        switch ($tipoConsulta) {
            case '1': // group by com lote e data fab
                // ADMV4.0
                // $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                // $sql .= "fat_pedido_item i ";
                // $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                // $sql .= "LEFT join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                // $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                // // $sql .= "group by i.ITEMESTOQUE ORDER BY I.NRITEM ASC ";
                // $sql .= "group by i.ITEMESTOQUE, e.FABLOTE, e.fabdatavalidade; ";
                $sql  = "SELECT I.ITEMESTOQUE, I.QTSOLICITADA, I.TOTAL, I.PERCDESCONTO, ";
                $sql .= "CASE WHEN P.Unifracionada = 'S' THEN I.QTSOLICITADA ";
                $sql .= "ELSE E.QUANTIDADE END as QUANTIDADE, ";
                $sql .= "I.DESCRICAO, I.UNITARIO, E.FABLOTE, E.FABDATAFABRICACAO, ";
                $sql .= "E.FABDATAVALIDADE, P.unidade, P.unifracionada, p.origem, ";
                $sql .= "p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.CODPRODUTOANVISA, p.PESO, p.localizacao, P.CODFABRICANTE,";
                $sql .= "I.DESCONTO, I.FRETE, I.CODIGONOTA, I.DESPACESSORIAS, I.NRITEM, I.ITEMFABRICANTE, I.NUMEROOC FROM ";
                $sql .= "FAT_PEDIDO_ITEM I ";
                $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
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
                $sql .= "I.DESCONTO, I.CODIGONOTA, I.NUMEROOC FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT join (SELECT CODPRODUTO, IDPEDIDO, COUNT(CODPRODUTO) AS QUANTIDADE FROM EST_PRODUTO_ESTOQUE  ";
                $sql .= "WHERE IDPEDIDO='" . $this->getId() . "' GROUP BY IDPEDIDO ,CODPRODUTO) E ";
                $sql .= "ON (E.IDPEDIDO = I.ID AND E.CODPRODUTO=I.ITEMESTOQUE) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "') and ( i.motivo = 0)";
                break;
            default: // sem lote e data fab
                $sql = "SELECT i.*, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, ";
                $sql .= "p.ncm, p.cest, p.codigobarras, I.DESCONTO, p.PRECOMINIMO, I.NUMEROOC FROM ";
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

    /**
     * Consulta para o Banco atraves do id
     * @name verifica_vendedor
     * @return ARRAY todos os campos da table
     * @version 20200505
     */
    public function verifica_vendedor()
    {

        $sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
        $sql .= "WHERE (USUARIO = " . $this->m_userid . ")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Popula o combo de obras com base no cliente selecionado.
     */
    public function comboObra($cliente_id)
    {
        $consulta = new c_banco();
        $sql = "SELECT ID, PROJETO FROM FIN_CLIENTE_OBRA WHERE CLIENTE = '" . $cliente_id . "' 
        AND STATUS = 'A' ORDER BY PROJETO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        return $result; // Retorna o array diretamente
    }

    /**
     * Gera os dados do relatório de impostos do pedido
     * @param int $idPedido
     * @return array ['pedido' => ..., 'itens' => [...]]
     */
    public function getRelatorioImpostosPedido($idPedido) {
        try{
             if (!$idPedido) {
                 throw new Exception('ID do pedido não informado.');
             }
 
             $pedido = $this->select_pedido_id($idPedido);
 
             if(!isset($pedido[0])){
                 throw new Exception('Pedido nao localizado!');
             }
 
             $this->setIdPedidoItem($idPedido);
             $itens = $this->select_pedido_todos_itens_id();
 
             
             $pessoa = new c_conta();
             $pessoa->setId($pedido[0]['CLIENTE']);
             $cliente = $pessoa->select_conta();
             
 
             foreach ($itens as $item) {
                 $this->setCodProduto($item['ITEMESTOQUE']);
                 $dadosProduto = $this->select_atendimento_Produto_produto();
                 if (is_array($dadosProduto[0])) {
                     $item = array_merge($item, $dadosProduto[0]);
                 }
                 
                 $dadosItem = [
                     'despAcessorias'   => $item['DESPACESSORIAS'] ?? 0,
                     'tribIcms'         => $item['TRIBICMS'] ?? '',
                     'item_estoque'     => $item['ITEMESTOQUE'] ?? 0,
                     'desconto'         => $item['DESCONTO'] ?? 0,
                     'produto_valor'    => $item['UNITARIO'] ?? 0,
                     'total'            => $item['TOTAL'] ?? 0,
                     'frete'            => $item['FRETE'] ?? 0,
                     'origem'           => $item['ORIGEM'] ?? '',
                     'ncm'              => $item['NCM'] ?? '',
                     'cest'             => $item['CEST'] ?? '',
                     'quantidade'       => $item['QTSOLICITADA'] ?? 0,
                 ];
                 
                 $calcImpostos = new c_pedidoVendaNf();
                 $impostos = $calcImpostos->calculaImpostosNfe(
                     $dadosItem,
                     $pedido[0]['IDNATOP'],
                     $cliente[0]['UF'],
                     $cliente[0]['PESSOA'],
                     $this->m_empresacentrocusto,
                     true
                 );
                 
                 $item['impostos'] = isset($impostos['valores']) ? $impostos['valores'] : array();
                 $impostosItens[] = $item;
             }
 
             // popula novos dados do pedido
             $dados_pedido = array(
                 'PEDIDO' => $pedido[0]['PEDIDO'],
                 'EMISSAO' => $pedido[0]['EMISSAO']
             );
             
             return [
                 'status' => true,
                 'pedido' => $dados_pedido,
                 'itens' => $impostosItens
             ];
 
         } catch (Exception $e) {
             return [
                 'status' => false,
                 'erro' => $e->getMessage()];
         }
    }
} //END OF THE CLASS
