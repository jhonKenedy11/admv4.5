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
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../class/ped/c_pedido_venda_farma.php");
include_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
include_once($dir . "/../../class/fin/c_lancamento.php");



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
    private $serie              = NULL;
    private $especie            = NULL;
    private $catEquipamentoId   = NULL;
    private $idNatop            = NULL;
    private $prazoEntregaOs     = NULL;
    private $responsavelTecnico = NULL;
    private $enderecoEntrega = NULL;
    private $usrAprovacao       = NULL;

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
    private $numeroOc               = NULL; // Número da ordem de compra
    private $nItemPed               = NULL; // Número do item da ordem de compra
    private $dataEntregaPeca        = ''; // Data de entrega da peça
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
        if ($this->obra == '' || $this->obra == NULL) {

            return 'NULL';
        } else {
            return $this->obra;
        }
    }

    function setResponsavelTecnico($responsavelTecnico)
    {
        $this->responsavelTecnico = $responsavelTecnico;
    }

    function getResponsavelTecnico()
    {
        if ($this->responsavelTecnico == '' || $this->responsavelTecnico == NULL) {
            return 'NULL';
        } else {
            return $this->responsavelTecnico;
        }
    }

    function setEnderecoEntrega($enderecoEntrega)
    {
        $this->enderecoEntrega = $enderecoEntrega;
    }
    function getEnderecoEntrega()
    {
        if ($this->enderecoEntrega == '' || $this->enderecoEntrega == NULL) {
            return 'NULL';
        } else {
            return $this->enderecoEntrega;
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
                return number_format(c_tools::parseMoedaValor($this->valorFrete), 2, ',', '.');
            } else {
                return c_tools::parseMoedaValor($this->valorFrete);
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
                return number_format(c_tools::parseMoedaValor($this->valorDespAcessorias), 2, ',', '.');
            } else {
                return c_tools::parseMoedaValor($this->valorDespAcessorias);
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
                // Pode vir como "355,94" (BR), "355.94" (float/string) ou numérico.
                $v = $this->valorDesconto;
                if (is_numeric($v)) {
                    return round((float) $v, 2);
                }
                $s = trim((string) $v);
                if ($s === '') {
                    return 0;
                }
                if (strpos($s, ',') !== false) {
                    return round((float) c_tools::moedaBd($s), 2);
                }
                // Se não tem vírgula, assume decimal com ponto (não remove ponto como milhar)
                return round((float) str_replace(' ', '', $s), 2);
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

    function setSerie($serie)
    {
        if (empty($serie)) {
            $serie = NULL;
        } else {
            $this->serie = $serie;
        }
    }
    function getSerie()
    {
        return $this->serie;
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

    function setNumeroOc($numeroOc)
    {
        $this->numeroOc = $numeroOc;
    }
    function getNumeroOc()
    {
        if (empty($this->numeroOc))
            return '';
        else
            return $this->numeroOc;
    }

    function setNItemPed($nItemPed)
    {
        $this->nItemPed = $nItemPed;
    }
    function getNItemPed()
    {
        if (empty($this->nItemPed))
            return '';
        else
            return $this->nItemPed;
    }

    function setDataEntregaPeca($dataEntregaPeca)
    {
        $this->dataEntregaPeca = $dataEntregaPeca;
    }
    function getDataEntregaPeca()
    {
        if (empty($this->dataEntregaPeca)) {
            return 'NULL';
        } else {
            return "'" . c_date::convertDateTxt($this->dataEntregaPeca) . "'";
        }
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
                    // Pode vir como "355,94" (BR), "355.94" (float/string) ou numérico.
                    $v = $this->desconto;
                    if (is_numeric($v)) {
                        return round((float) $v, 2);
                    }
                    $s = trim((string) $v);
                    if ($s === '') {
                        return 0;
                    }
                    if (strpos($s, ',') !== false) {
                        return round((float) c_tools::moedaBd($s), 2);
                    }
                    // Se não tem vírgula, assume decimal com ponto (não remove ponto como milhar)
                    return round((float) str_replace(' ', '', $s), 2);
                    break;
                case 'F':
                    $v = $this->desconto;
                    if (is_numeric($v)) {
                        return number_format((float) $v, 2, ',', '.');
                    }
                    return number_format((float) c_tools::moedaBd($v), 2, ',', '.');
                    break;
                default:
                    return $this->desconto;
            }
        else:
            return 0;
        endif;
    }

    public function getUsrAprovacao()
    {
        if ($this->usrAprovacao === null || $this->usrAprovacao === '' || $this->usrAprovacao === 'NULL') {
            return null;
        }
        return $this->usrAprovacao;
    }

    public function setUsrAprovacao($usrAprovacao)
    {
        $this->usrAprovacao = $usrAprovacao;
    }

    public function possuiUsrAprovacaoValido(): bool
    {
        $usr = $this->getUsrAprovacao();
        return $usr !== null && $usr !== '' && $usr !== 'NULL' && (int) $usr !== 0;
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
    function setDataIni($dataIni)
    {
        $this->dataIni = $dataIni;
    }
    function getDataIni()
    {
        return $this->dataIni;
    }
    function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }
    function getDataFim()
    {
        return $this->dataFim;
    }
    public function setPercentualAplicar($percentual)
    {
        $this->percentualAplicar = $percentual;
    }

    public function getPercentualAplicar()
    {
        return $this->percentualAplicar;
    }


    public function setMarkupProduto($markupProduto)
    {
        $this->m_markupProduto = $markupProduto;
    }

    public function getMarkupProduto($format = NULL)
    {
        if (!empty($this->m_markupProduto)) {
            if ($format == 'F') {
                return number_format($this->m_markupProduto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->m_markupProduto);
            }
        } else {
            return 0;
        }
    }

    public function setMarkup($markup)
    {
        $this->m_markup = $markup;
    }

    public function getMarkup()
    {
        return $this->m_markup;
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
        $this->setDesconto($atendimento[0]['DESCONTO']);
        $this->setValorTotal($atendimento[0]['TOTAL']);
        $this->setCondPgto($atendimento[0]['CONDPG']);
        $this->setCentroCusto($atendimento[0]['CCUSTO'] ?? $atendimento[0]['CENTROCUSTO'] ?? null);
        $this->setCentroCustoEntrega($atendimento[0]['CENTROCUSTOENTREGA'] ?? null);
        $this->setSituacao($atendimento[0]['SITUACAO']);
        $this->setSerie($atendimento[0]['SERIE']);
        $this->setEspecie($atendimento[0]['ESPECIE']);
        $this->setIdNatop($atendimento[0]['IDNATOP'] ?? null);

        $this->setObra($atendimento[0]['OBRA_ID']);
        $this->setResponsavelTecnico($atendimento[0]['RESP_TECNICO']);
        $this->setEnderecoEntrega($atendimento[0]['ENDERECOENTREGA']);

        $this->setCatEquipamentoId($atendimento[0]['CAT_EQUIPAMENTO_ID']);
        $this->setDescEquipamento($atendimento[0]['DESCEQUIPAMENTO']);
        $this->setDataAbertura($atendimento[0]['DATAABERATEND']);
        $this->setDataFechamentoEnd($atendimento[0]['DATAFECHATEND']);
        $this->setPrazoEntregaOs($atendimento[0]['PRAZOENTREGAOS']);
        $this->setObsOs($atendimento[0]['OBSOS']);
        $this->setObsServicos($atendimento[0]['OBSSERVICO']);
        $this->setOs($atendimento[0]['OS']);
        $this->setUsrAprovacao($atendimento[0]['USRAPROVACAO'] ?? null);
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
        
        $sql = "SELECT PI.*, P.UNIDADE AS UNIDADE FROM ";
        $sql .= "FAT_PEDIDO_ITEM AS PI ";
        $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO = PI.ITEMESTOQUE) ";
        $sql .= "WHERE (PI.ID = '" . $this->getId() . "') AND (PI.MOTIVO = 0) ";
        $sql .= "ORDER BY PI.NRITEM ASC";
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
        $sql .= " CODIGONOTA, NUMEROOC, NITEMPED, DATAENTREGAPECA, MARKUP,TOTAL) ";

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
            . $this->getCodProdutoNota() . "', '"
            . $this->getNumeroOc() . "', '"
            . $this->getNItemPed() . "', "
            . $this->getDataEntregaPeca() . ", "
            . $this->getMarkupProduto('B') . ", "
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
        $sql .= "NUMEROOC       = '" . $this->getNumeroOc() . "', ";
        $sql .= "NITEMPED       = '" . $this->getNItemPed() . "', ";
        $sql .= "MARKUP         = "  . $this->getMarkupProduto('B') . ", ";
        $sql .= "DATAENTREGAPECA = " . $this->getDataEntregaPeca() . ", ";
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

    public function updateField($field, $valor, $tabela, $conn = null)
    {
        $sql = "UPDATE  " . $tabela;
        $sql .= " SET " . $field . " = '" . $valor . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";

        $banco = new c_banco;
        $execConn = ($conn !== null) ? $conn : $banco->id_connection;
        $banco->exec_sql($sql, $execConn);
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
     * Última cotação em aberto (SITUACAO = 5) do cliente — link do dashboard CRM (param = cliente).
     * @param int $idCliente
     * @return int ID do pedido ou 0
     */
    public function selectIdUltimaCotacaoAbertaCliente($idCliente)
    {
        $idCliente = (int) $idCliente;
        if ($idCliente <= 0) {
            return 0;
        }
        $sql = "SELECT ID FROM FAT_PEDIDO WHERE CLIENTE = " . $idCliente . " AND SITUACAO = 5 ORDER BY EMISSAO DESC, ID DESC LIMIT 1";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        if (empty($banco->resultado[0]['ID'])) {
            return 0;
        }
        return (int) $banco->resultado[0]['ID'];
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
     * @param STRING vendedores vendedores selecionados para filtrar a busca
     * @return ARRAY com os atendimentos selecionados.
     */
    public function select_pedido_letra($letra, $situacoes, $vendedores = '', $condPag = '')
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

        $sql = "SELECT A.*, C.NOME,  D.PADRAO AS SITUACAODESC , O.PROJETO AS OBRA_DESC, V.NOME AS VENDEDOR_NOME ";
        $sql .= "FROM FAT_PEDIDO  A ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = A.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_CLIENTE_OBRA O ON (O.ID = A.OBRA_ID) ";
        $sql .= "LEFT JOIN AMB_USUARIO V ON (V.USUARIO = A.USRFATURA) ";
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

            // Filtro de vendedor
            if (!empty($vendedores)) {
                $parVend = explode("|", $vendedores);
                $vend = '';
                $count = count($parVend) - 1;
                for ($i = 1; $i < count($parVend); $i++) {
                    if ($i == $count) {
                        $vend .= $parVend[$i];
                    } else {
                        $vend .= $parVend[$i] . ",";
                    }
                }
                $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
                $sql .= empty($vend) ? '' : " $cond (a.USRFATURA IN (" . $vend . ")) ";
            }

            // Filtro de condicao de pagamento
            if (!empty($condPag)) {
                $parCondPag = explode("|", $condPag);
                $condPag = '';
                $count = count($parCondPag) - 1;
                for ($i = 1; $i < count($parCondPag); $i++) {
                    if ($i == $count) {
                        $condPag .= $parCondPag[$i];
                    } else {
                        $condPag .= $parCondPag[$i] . ",";
                    }
                }
                $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
                $sql .= empty($condPag) ? '' : " $cond (a.CONDPG IN (" . $condPag . ")) ";
            }
        }

        $sql .= " ORDER BY A.EMISSAO DESC, A.ID DESC";

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
        $sql .= "CLIENTE, CONTATO, USRFATURA,  TOTALPRODUTOS, VALORSERVICOS, FRETE, DESPACESSORIAS, DESCONTO, EMISSAO,  PRAZOENTREGA,  OBS, CONDPG, OBRA_ID, RESP_TECNICO, SITUACAO, ESPECIE, SERIE, CCUSTO, CENTROCUSTOENTREGA, IDNATOP, ENDERECOENTREGA, USERINSERT, DATEINSERT )";

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
            . $this->getObra() . ", "
            . $this->getResponsavelTecnico() . ", '"
            . $this->getSituacao() . "', '"
            . $this->getEspecie() . "', '"
            . $this->getSerie() . "', "
            . $this->getCentroCusto() . ", "
            . $this->getCentroCustoEntrega() . ", "
            . $this->getIdNatop() . ", "
            . $this->getEnderecoEntrega() . ", '";
        $sql .= $this->m_userid . "','" . date("Y-m-d H:i:s") . "' );";
        //echo strtoupper($sql) . "<BR>";
        $result = $banco->exec_sql($sql);
        $lastReg = $banco->insertReg;
        $banco->close_connection();

        if ($result > 0) {
            return $lastReg;
        } else {
            return 'Os dados do atendimento ' . $this->getId() . ' n&atilde;o foi cadastrado!' . $result;
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
        $sql .= "OBRA_ID = " . $this->getObra() . ", ";
        $sql .= "RESP_TECNICO = " . $this->getResponsavelTecnico() . ", ";
        $sql .= "ENDERECOENTREGA = " . $this->getEnderecoEntrega() . ", ";
        $sql .= "SERIE = '" . $this->getSerie() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $execConn = ($conn !== null) ? $conn : $banco->id_connection;
        $result = $banco->exec_sql($sql, $execConn);
        $banco->close_connection();

        if ($result > 0) {
            return true;
        } else {
            return 'Pedido ' . $this->getId() . ' não foi alterado!';
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
                 VALORSERVICOS, USERINSERT, DATEINSERT) ";
        $sql .= "SELECT 
                 CLIENTE,PEDIDO,NUMOPORTUNIDADE," . $situacao . " as SITUACAO,'" . $emissao . "' as EMISSAO, ENTREGADOR,USRFATURA,IDNATOP,TABPRECO,ENTRADATABPRECO,
                 TAXAFIN,CONDPG,ENTRADACONDPG,VENCIMENTO1,DESCONTO,TOTAL,MOEDA,CONTADEPOSITO,ESPECIE,SERIE,'" . $horaEmissao . "' as HORAEMISSAO,
                 TAXAENTREGA,TOTALRECEBIDO,GENERO,CCUSTO,TIPOENTREGA,TABELAPRECO,IPI, ";
        $sql .= "TRANSPORTADORA,TABELAVENDA,USRPEDIDO,DTULTPEDIDOCLIENTE,PERCDESCONTO,DESCONTONF,STATUS,TOTALPRODUTOS,
                 FRETE,DTVALIDADE,PRAZOENTREGA,OBS,0 AS OS,PROTOCOLOPARCEIRO,CUSTOTOTAL,CREDITO,DESPESATOTAL,
                 LUCROBRUTO,MARGEMLIQUIDA,MARKUP,DESCONTOGERAL,DESPACESSORIAS,PLACAVEICULO,VOLPESOLIQ,VOLPESOBRUTO,
                 CENTROCUSTOENTREGA,VOLMARCA,VOLESPECIE,VOLUME,MODFRETE, VALORSERVICOS, ";
        $sql .= $this->m_userid . " as USERINSERT, '" . date("Y-m-d H:i:s") . "' as DATEINSERT ";
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
     * @return float|null Total do pedido gravado (produtos - desconto + frete + despesas) ou null se sem ID
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

            $descontoNF = $this->select_totais('DESCONTO'); // Totais desconto pedido_item
            $totalProdutosComDesconto = 0; // Total dos produtos após aplicar desconto
            $despAcessorias = round((float) $this->getValorDespAcessorias('B'), 2); // despesas acessorias do pedido
            $frete = round((float) $this->getValorFrete('B'), 2);          // frete do pedido
            $descontoGeral = round((float) $this->getDesconto('B'), 2);  // desconto digitado no pedido form

            $despAcessoriasDist = 0;
            $freteDist = 0;
            $descontoGeralDist = 0;
            $custototal = 0;
            $margemliquida = 0;
            $markup = 0;
            $lucrobruto = null;
            $totalNF = 0;

            $arrItemPedido = $this->select_pedido_item_id() ?? [];

            $totalDescontoItem = $descontoNF;
            $this->setDesconto($descontoGeral);
            $this->setValorDesconto($descontoGeral);

            // Primeiro, calcula o total SEM desconto (QTSOLICITADA * UNITARIO) de todos os itens
            $total = 0;
            for ($i = 0; $i < count($arrItemPedido); $i++) {
                $arrItemPedido[$i]['TOTAL'] = round(
                    (float) $arrItemPedido[$i]['QTSOLICITADA'] * (float) $arrItemPedido[$i]['UNITARIO'],
                    2
                );
                $total += $arrItemPedido[$i]['TOTAL'];
            }
            $total = round($total, 2);

            // Agora processa cada item para aplicar o desconto
            for ($i = 0; $i < count($arrItemPedido); $i++) {
                $sqlFields = '';
                $custototal += $arrItemPedido[$i]['CUSTO'];
                $lucrobruto += $arrItemPedido[$i]['LUCROBRUTO'];
                $margemliquida += $arrItemPedido[$i]['MARGEMLIQUIDA'];
                $markup += $arrItemPedido[$i]['MARKUP'];

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
                        $vlrDescontoGeral = round($vlrDescontoGeral, 2);
                    }
                    $percDescontoItem = $arrItemPedido[$i]['TOTAL'] > 0
                        ? round(($vlrDescontoGeral * 100) / $arrItemPedido[$i]['TOTAL'], 2)
                        : 0;
                    $novoTotalItem = round($arrItemPedido[$i]['TOTAL'] - $vlrDescontoGeral, 2);
                    $totalProdutosComDesconto += $novoTotalItem; // Soma o total do item com desconto aplicado
                    $sqlFields .= 'percdesconto = ' . $percDescontoItem . ', desconto = ' . $vlrDescontoGeral . ', total = ' . $novoTotalItem;
                } else {
                    // Quando não há desconto, o total volta a ser quantidade * unitário
                    $totalItemSemDesconto = round($arrItemPedido[$i]['TOTAL'], 2);
                    $totalProdutosComDesconto += $totalItemSemDesconto; // Soma o total do item sem desconto
                    $sqlFields .= ' percdesconto = 0, desconto = 0, total = ' . $totalItemSemDesconto;
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

            // TOTALPRODUTOS deve ser o valor FIXO sem desconto (soma de QTSOLICITADA * UNITARIO)
            // O desconto é aplicado apenas no cálculo do TOTAL final
            if ($sqlField <> "") {
                $sqlField .= ', totalprodutos = ' . $total;
            } else {
                $sqlField = ' totalprodutos = ' . $total;
            }

            // Total = Produtos (sem desconto) - Desconto + Frete + Despesas Acessórias
            $totalPedido = round(($total - $descontoGeral) + $frete + $despAcessorias, 2);
            if ($sqlField <> "") {
                $sqlField .= ', total = ' . $totalPedido;
            } else {
                $sqlField = ' total = ' . $totalPedido;
            }

            $sqlField .= ", obs = '" . $this->getObs() . "'" . ", prazoentrega = '" . $this->getPrazoEntrega('B') . "'";

            $this->setDesconto($descontoGeral);
            $this->setValorDesconto($descontoGeral);

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

            return $totalPedido;
        }

        return null;
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
                $sql .= "I.DESCRICAO, I.UNITARIO, I.MARKUP, I.DATAENTREGAPECA, E.FABLOTE, E.FABDATAFABRICACAO, ";
                $sql .= "E.FABDATAVALIDADE, P.unidade, P.unifracionada, P.CUSTOCOMPRA, P.UNIDADE, p.origem, ";
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
                $sql .= "WHERE (i.id = '" . $this->getId() . "')  and ( i.motivo = 0) ";
                $sql .= "ORDER BY I.NRITEM ASC";
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

    public function comboCondPgto()
    {
        $sql = "SELECT ID, DESCRICAO FROM FAT_COND_PGTO ORDER BY ID";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;

        $condPgto_ids = array('');
        $condPgto_names = array('Selecione uma condição de pagamento');

        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[] = $result[$i]['ID'];
            $condPgto_names[] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());
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

        return is_array($result) ? $result : [];
    }

    /**
     * Saldo total de créditos do cliente (FIN_CLIENTE_CREDITO) para JSON em telas do pedido.
     *
     * @param mixed $cliente_id
     * @param int $idPedidoExcluir
     * @return array{saldo_credito: float, saldo_credito_formatado: string}
     */
    public function saldoCreditoClienteParaJson($cliente_id, $idPedidoExcluir = 0)
    {
        $cid = (int) $cliente_id;
        $obj = new c_conta();
        $saldo = $cid > 0 ? $obj->selectSaldoCreditoCliente($cid) : 0.0;
        $bloqueado = $cid > 0 && strtoupper((string) $obj->contaBloqueada($cid)) === 'S';
        return array_merge([
            'saldo_credito' => $saldo,
            'saldo_credito_formatado' => number_format($saldo, 2, ',', '.'),
            'cliente_bloqueado' => $bloqueado,
        ], c_lancamento::limiteCreditoClienteParaJson($cid, (int) $idPedidoExcluir));
    }

    /**
     * Popula o combo de responsáveis técnicos (todos os responsáveis cadastrados).
     */
    public function comboResponsavelTecnico()
    {
        $consulta = new c_banco();
        $sql = "SELECT ID, NOME 
                FROM AMB_RESPONSAVEL_TECNICO
                ORDER BY NOME";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        return is_array($result) ? $result : [];
    }

    /**
     * Busca endereços de entrega do cliente
     * @param int $clienteId
     * @return array endereços do cliente
     */
    public function buscarEnderecosCliente($clienteId)
    {
        if (!$clienteId) {
            return [];
        }

        $sql = "SELECT ID, CONCAT(TITULOEND, ' - ', ENDERECO, ' ', NUMERO, ' ', CIDADE) AS ENDERECO_ENTREGA FROM FIN_CLIENTE_ENDERECO 
                WHERE CLIENTE = " . $clienteId . " 
                AND STATUS = 'A'
                ORDER BY ENDENTREGAPADRAO DESC, ENDERECO_ENTREGA";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }
    public function busca_representante_cliente($cliente_id)
    {
        $sql = "SELECT c.REPRESENTANTE, u.NOME 
                FROM FIN_CLIENTE c 
                LEFT JOIN AMB_USUARIO u ON u.USUARIO = c.REPRESENTANTE 
                WHERE c.CLIENTE = " . intval($cliente_id);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Busca endereço de entrega do pedido para a nota fiscal
     * @param int $idPedido
     * @return array endereço de entrega do pedido
     */
    public function enderecoEntregaNf($idPedido)
    {
        $sql = "SELECT 
                P.ENDERECOENTREGA,  
                E.ENDERECO,
                E.NUMERO,
                E.COMPLEMENTO,
                E.BAIRRO,
                E.CIDADE,
                E.UF,
                E.CEP,
                E.CODMUNICIPIO
            FROM FAT_PEDIDO P
            LEFT JOIN FIN_CLIENTE_ENDERECO E ON (E.ID = P.ENDERECOENTREGA)
            WHERE P.ID = " . $idPedido . " ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        if (empty($banco->resultado)) {
            return null;
        } else {
            return $banco->resultado;
        }
    }

    /**
     * Gera os dados do relatório de impostos do pedido
     * @param int $idPedido
     * @return array ['pedido' => ..., 'itens' => [...]]
     */
    public function getRelatorioImpostosPedido($idPedido)
    {
        try {
            if (!$idPedido) {
                throw new Exception('ID do pedido não informado.');
            }

            $pedido = $this->select_pedido_id($idPedido);

            if (!isset($pedido[0])) {
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
                'erro' => $e->getMessage()
            ];
        }
    }

    public function prosseguirComDesconto()
    {
        $sql = "SELECT SITUACAO FROM FAT_PEDIDO WHERE ID = " . $this->getId() . " ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function getFatParametrosFilial(): array
    {
        $defaults = [
            'encomenda' => 'N',
            'fluxoPedido' => 'S',
            'faturaPedido' => 'N',
            'sitAberto' => null,
            'sitBaixado' => null,
            'sitEmitirNf' => null,
            'aprovacao' => 'N',
            'descontoMaximo' => 0.0,
            'tipoDesconto' => null,
        ];
        $filial = (int) $this->m_empresacentrocusto;
        try {
            $banco = new c_banco_pdo();
            $sql = 'SELECT ENCOMENDA, FLUXOPEDIDO, FATURAPEDIDO, SITABERTO, SITBAIXADO, SITEMITIRNF,
                           APROVACAO, DESCONTOMAXIMO, TIPODESCONTO
                    FROM FAT_PARAMETRO WHERE FILIAL = :filial';
            $banco->prepare($sql);
            $banco->bindValue(':filial', $filial);
            $banco->execute();
            $row = $banco->fetch();
            if (is_array($row)) {
                $u = array_change_key_case($row, CASE_UPPER);
                return [
                    'encomenda' => (string) ($u['ENCOMENDA'] ?? $defaults['encomenda']),
                    'fluxoPedido' => (string) ($u['FLUXOPEDIDO'] ?? $defaults['fluxoPedido']),
                    'faturaPedido' => (string) ($u['FATURAPEDIDO'] ?? $defaults['faturaPedido']),
                    'sitAberto' => $u['SITABERTO'] ?? $defaults['sitAberto'],
                    'sitBaixado' => $u['SITBAIXADO'] ?? $defaults['sitBaixado'],
                    'sitEmitirNf' => $u['SITEMITIRNF'] ?? $defaults['sitEmitirNf'],
                    'aprovacao' => strtoupper((string) ($u['APROVACAO'] ?? $defaults['aprovacao'])),
                    'descontoMaximo' => (float) ($u['DESCONTOMAXIMO'] ?? $defaults['descontoMaximo']),
                    'tipoDesconto' => $u['TIPODESCONTO'] ?? $defaults['tipoDesconto'],
                ];
            }
        } catch (Exception $e) {
            error_log('[c_pedido_ps] getFatParametrosFilial: ' . $e->getMessage());
        }
        return $defaults;
    }

    /**
     * Percentual de desconto geral sobre o total (mesma fórmula Telhas).
     */
    public function calcularPercentualDescontoPedido(): float
    {
        $total = (float) $this->select_totalPedido()
            + (float) $this->getValorFrete('B')
            + (float) $this->getValorDespAcessorias('B');
        $desconto = (float) $this->getDesconto('B');
        if ($desconto <= 0 || $total <= 0) {
            return 0.0;
        }
        return round(($desconto / $total) * 100, 4);
    }

    /**
     * Atualiza situação de aprovação gerencial (10 = enviar; 6|13 = registrar aprovação).
     */
    public function alteraSituacaoAprovacaoPedido(int $situacao, ?int $usrAprovacao = null, $conn = null): bool
    {
        if ($situacao === 10) {
            $setClause = 'SITUACAO = 10, USRAPROVACAO = NULL, DATAAPROVACAO = NULL';
            $sitLocal = 10;
            $usrLocal = null;
        } else {
            $sitLocal = in_array($situacao, [6, 13], true) ? $situacao : 6;
            $setClause = 'SITUACAO = ' . $sitLocal
                . ', USRAPROVACAO = ' . (int) $usrAprovacao
                . ', DATAAPROVACAO = CURRENT_TIMESTAMP()';
            $usrLocal = (int) $usrAprovacao;
        }

        $sql = 'UPDATE FAT_PEDIDO SET '
            . $setClause
            . ', USERCHANGE = ' . (int) $this->m_userid
            . ', DATECHANGE = CURRENT_TIMESTAMP() '
            . 'WHERE ID = ' . (int) $this->getId();
        $banco = new c_banco();
        $execConn = ($conn !== null) ? $conn : $banco->id_connection;
        $banco->exec_sql($sql, $execConn);
        $banco->close_connection();
        $this->setSituacao($sitLocal);
        $this->setUsrAprovacao($usrLocal);
        return true;
    }

    /**
     * Reserva estoque, recalcula total e dispara financeiro após confirmação ou aprovação.
     *
     * @return array{ok:bool,erro:string,res:mixed,gerouFinanceiro:bool}
     */
    public function pedidoPsFinalizarPosConfirmacao(int $situacaoDb, int $situacaoNova): array
    {
        $parametros = new c_banco();
        $parametros->setTab('EST_PARAMETRO');
        $controlaEstoque = $parametros->getField('CONTROLAESTOQUE', 'FILIAL=' . $this->m_empresacentrocusto);
        $parametros->close_connection();
        $precisaTransacaoReserva = in_array((int) $situacaoNova, [6, 13], true)
            && (int) $situacaoDb !== (int) $situacaoNova
            && $controlaEstoque === 'S';
        $res = true;

        if ($precisaTransacaoReserva) {
            $transaction = new c_banco();
            $transaction->inicioTransacao($transaction->id_connection);
            try {
                $res = $this->alteraPedido($transaction->id_connection);
                if (is_string($res)) {
                    throw new Exception($res);
                }
                $msgReserva = $this->pedidoPsExecutarReservaEstoqueFarma($transaction->id_connection, $situacaoDb);
                if ($msgReserva !== '') {
                    throw new Exception($msgReserva);
                }
                $transaction->commit($transaction->id_connection);
            } catch (Exception $e) {
                $transaction->rollback($transaction->id_connection);
                return ['ok' => false, 'erro' => $e->getMessage(), 'res' => null, 'gerouFinanceiro' => false];
            }
        } else {
            $res = $this->alteraPedido();
            if (is_string($res)) {
                return ['ok' => false, 'erro' => $res, 'res' => $res, 'gerouFinanceiro' => false];
            }
        }

        $totalPedidoCalc = $this->calculaImpostos();
        if ($totalPedidoCalc !== null) {
            $this->setValorTotal(round((float) $totalPedidoCalc, 2, PHP_ROUND_HALF_EVEN), true);
        }
        $result = $this->getValorTotal();
        $this->updateField('TOTAL', $result, 'FAT_PEDIDO');
        if ($this->getOs() != '0') {
            $this->atualizaOsPedido();
        }

        $paramFat = new c_banco();
        $paramFat->setTab('FAT_PARAMETRO');
        $faturaPedido = $paramFat->getField('FATURAPEDIDO', 'FILIAL=' . $this->m_empresacentrocusto);
        $paramFat->close_connection();

        $disparaFinanceiro = in_array((int) $situacaoNova, [6, 13], true) && $faturaPedido === 'S';
        if (!$disparaFinanceiro) {
            $cond_pagamento = $this->getCondPgto();
            $busca_cond_pgto = new c_banco();
            $busca_cond_pgto->setTab('FAT_COND_PGTO');
            $array_cond_pgto = $busca_cond_pgto->getRecord('ID=' . $cond_pagamento);
            $busca_cond_pgto->close_connection();
            $situacao_lcto = isset($array_cond_pgto[0]['SITUACAOLCTO']) ? $array_cond_pgto[0]['SITUACAOLCTO'] : '';
            $disparaFinanceiro = ($situacao_lcto == 'E');
        }

        if ($disparaFinanceiro) {
            $objContaBloq = new c_conta();
            if (strtoupper((string) $objContaBloq->contaBloqueada((int) $this->getCliente())) === 'S') {
                return [
                    'ok' => false,
                    'erro' => 'Cliente bloqueado. Verifique com o financeiro.',
                    'res' => $res,
                    'gerouFinanceiro' => false,
                ];
            }
            $dir = dirname(__FILE__);
            require_once($dir . '/../../forms/ped/p_pedido_venda_nf_pecas_novo.php');
            $objeto_pedido_nf_pecas_novo = new p_pedido_venda_nf_pecas_novo($this->getId(), 'financeiro');
            $objeto_pedido_nf_pecas_novo->t_origem = 'pedido_ps';
            $objeto_pedido_nf_pecas_novo->controle();
            return ['ok' => true, 'erro' => '', 'res' => $res, 'gerouFinanceiro' => true];
        }

        return ['ok' => true, 'erro' => '', 'res' => $res, 'gerouFinanceiro' => false];
    }

    /**
     * Valida estoque para encomenda / confirmação (ajax e ferramentas do Pedido PS).
     * Respeita FAT_PARAMETRO.ENCOMENDA na mensagem quando há falta parcial.
     *
     * @return array{ok:bool,estoqueOk:bool,temFinanceiro:bool,encomendaAtiva:bool,titulo:string,mensagem:string,itens:array}
     */
    public function validarEncomendaPedido(int $idPedido, int $ccEntrega): array
    {
        $idAnterior = $this->getId();
        $this->setId($idPedido);

        $est = new c_produto_estoque();
        $itens = [];
        $estoqueOk = true;

        foreach ((array) $this->select_pedido_item_id() as $row) {
            if (isset($row['MOTIVO']) && (int) $row['MOTIVO'] === 8) {
                continue;
            }

            $cod = $row['ITEMESTOQUE'];
            $solicitado = (float) ($row['QTSOLICITADA'] ?? 0);
            $atendida = (float) ($row['QTATENDIDA'] ?? 0);
            $pendente = max(0, $solicitado - $atendida);
            $uniFrac = strtoupper((string) ($row['UNIFRACIONADA'] ?? $row['unifracionada'] ?? 'N'));
            $dados = $this->pedidoPsDadosEstoqueItem($est, $cod, $ccEntrega, $uniFrac);
            $r = $est->produtoQtdeCC($cod, $ccEntrega)[0] ?? [];
            $disponivel = $dados['disponivel'];
            $qtdFalta = max(0, $pendente - $disponivel);
            $itemOk = ($pendente <= $disponivel);
            $qtdReservar = $dados['controlePeca'] ? min($pendente, $disponivel) : 0;

            if (!$itemOk) {
                $estoqueOk = false;
            }

            $itens[] = [
                'codigo' => $cod,
                'descricao' => $row['DESCRICAO'] ?? '',
                'solicitado' => $solicitado,
                'disponivel' => $disponivel,
                'qtdFalta' => $qtdFalta,
                'reservar' => $qtdReservar,
                'estoque' => (float) ($r['ESTOQUE'] ?? 0),
                'reserva' => (float) ($r['RESERVA'] ?? 0),
                'peca' => $dados['controlePeca'],
                'ok' => $itemOk,
            ];
        }

        if ($idAnterior !== null && $idAnterior !== '') {
            $this->setId($idAnterior);
        }

        $encomendaAtiva = strtoupper((string) ($this->getFatParametrosFilial()['encomenda'] ?? 'N')) === 'S';
        $temFinanceiro = (new c_produto())->select_lancamento($idPedido) !== null;

        if ($estoqueOk) {
            $titulo = 'Estoque disponível';
            $mensagem = 'Todos os itens têm saldo suficiente. Pode confirmar como pedido.';
        } elseif ($encomendaAtiva) {
            $titulo = 'Estoque parcial — encomenda';
            $mensagem = 'A <strong>quantidade disponível</strong> será reservada para este pedido. '
                . 'Apenas a <strong>quantidade em falta</strong> entra em encomenda. Após entrada de NF, valide o pedido '
                . 'ou consulte o relatório de compra por encomenda.';
        } else {
            $titulo = 'Estoque insuficiente';
            $mensagem = 'Não há saldo suficiente para confirmar o pedido. '
                . 'Ajuste as quantidades ou aguarde entrada de estoque.';
        }

        if (!$temFinanceiro) {
            $mensagem .= ' O pedido ainda não possui financeiro (ORIGEM PED) — necessário para a liberação definitiva.';
        }

        return [
            'ok' => $estoqueOk,
            'estoqueOk' => $estoqueOk,
            'temFinanceiro' => $temFinanceiro,
            'encomendaAtiva' => $encomendaAtiva,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'itens' => $itens,
        ];
    }

    /**
     * Valida estoque do Pedido PS (contábil + peças físicas em EST_PRODUTO_ESTOQUE).
     *
     * @return string|null mensagem de inconsistência ou null se OK
     */
    public function validaEstoquePedidoPs(int $idPedido, int $cce): ?string
    {
        $paramEst = new c_banco();
        $paramEst->setTab('EST_PARAMETRO');
        $controla = $paramEst->getField('CONTROLAESTOQUE', 'FILIAL=' . $this->m_empresacentrocusto);
        $paramEst->close_connection();
        if ($controla !== 'S') {
            return null;
        }

        $idAnterior = $this->getId();
        $this->setId($idPedido);
        $est = new c_produto_estoque();
        $msg = null;

        foreach ((array) $this->select_pedido_item_id() as $row) {
            if (isset($row['MOTIVO']) && (int) $row['MOTIVO'] === 8) {
                continue;
            }

            $cod = $row['ITEMESTOQUE'] ?? '';
            if ($cod === '') {
                continue;
            }

            $uniFrac = strtoupper((string) ($row['UNIFRACIONADA'] ?? $row['unifracionada'] ?? 'N'));
            $dados = $this->pedidoPsDadosEstoqueItem($est, $cod, $cce, $uniFrac);
            $solicitado = (float) ($row['QTSOLICITADA'] ?? 0);
            $pendente = max(0, $solicitado - (float) ($row['QTATENDIDA'] ?? 0));
            $contabil = $dados['disponivelContabil'];
            $disponivel = $dados['disponivel'];

            if ($pendente > $disponivel) {
                $msg .= 'Item ' . $cod . ' -> ' . ($row['DESCRICAO'] ?? '')
                    . ' com quantidade indisponível — pendente: ' . $pendente
                    . ', disponível: ' . $disponivel
                    . ' (estoque: ' . $contabil . ', reserva: ' . ($dados['reservaContabil'] ?? 0);
                if ($dados['controlePeca'] && $dados['disponivelFisico'] !== null) {
                    $msg .= ', peças físicas: ' . $dados['disponivelFisico'];
                }
                $msg .= ')<BR>';
            }
        }

        if ($idAnterior !== null && $idAnterior !== '') {
            $this->setId($idAnterior);
        }

        return ($msg === null || $msg === '') ? null : $msg;
    }

    /**
     * Saldo do item no CC de entrega: contábil (produtoQtdeCC) e, para peça com controle, limitado às unidades físicas livres.
     *
     * @return array{controlePeca:bool,disponivel:float,disponivelContabil:float,disponivelFisico:?int,reservaContabil:float}
     */
    private function pedidoPsDadosEstoqueItem(c_produto_estoque $est, $cod, int $cce, $unifracionada = 'N'): array
    {
        $row = $est->produtoQtdeCC($cod, (string) $cce)[0] ?? [];
        $contabil = max(0, (float) ($row['DISPONIVEL'] ?? 0));
        $reserva = (float) ($row['RESERVA'] ?? 0);
        $controlePeca = strtoupper((string) $unifracionada) !== 'S';
        $disponivel = $contabil;
        $fisico = null;

        if ($controlePeca) {
            $paramEst = new c_banco();
            $paramEst->setTab('EST_PARAMETRO');
            $controla = $paramEst->getField('CONTROLAESTOQUE', 'FILIAL=' . $cce);
            $paramEst->close_connection();

            if ($controla === 'S') {
                $banco = new c_banco();
                $sql = 'SELECT COUNT(*) AS QTD FROM EST_PRODUTO_ESTOQUE '
                    . 'WHERE centrocusto = ' . $cce . " AND codproduto = '" . $cod . "' AND status = 0";
                $banco->exec_sql($sql);
                $banco->close_connection();
                $fisico = (int) ($banco->resultado[0]['QTD'] ?? 0);
                // Peça controlada: saldo operacional = unidades físicas livres (alinhado à reserva PS).
                // Se contábil estiver zerado/defasado, não bloqueia quando há peça livre no depósito.
                $disponivel = $fisico;
                if ($contabil > 0) {
                    $disponivel = min($contabil, $fisico);
                }
            }
        }

        return [
            'controlePeca' => $controlePeca,
            'disponivel' => $disponivel,
            'disponivelContabil' => $contabil,
            'disponivelFisico' => $fisico,
            'reservaContabil' => $reserva,
        ];
    }

    /**
     * Reserva estoque do pedido PS na transação: zera reserva anterior do pedido, aplica UNIFRACIONADA N, confere quantidade.
     *
     * @param resource $conn mysqli
     * @return string vazio se OK ou mensagem curta de erro
     */
    public function pedidoPsExecutarReservaEstoqueFarma($conn, $situacaoDb = 0)
    {
        $cce = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
        $idPedido = (int) $this->getId();
        $parcial = ((int) $this->getSituacao() === 13);

        if ((int) $situacaoDb !== 13) {
            c_produto_estoque::liberaReservaPedido($cce, $idPedido, $conn);
            $bancoZera = new c_banco();
            $bancoZera->exec_sql('UPDATE FAT_PEDIDO_ITEM SET QTATENDIDA = 0 WHERE ID = ' . $idPedido, $conn);
            $bancoZera->close_connection();
        }

        $est = new c_produto_estoque();
        $qtAtendidaPorItem = [];
        $reiniciaAtendida = ((int) $situacaoDb !== 13);

        foreach ((array) $this->select_pedido_item_id() as $row) {
            if (isset($row['MOTIVO']) && (int) $row['MOTIVO'] === 8) {
                continue;
            }

            $cod = $row['ITEMESTOQUE'] ?? $row['itemestoque'] ?? '';
            if ($cod === '') {
                continue;
            }

            $nritem = (int) ($row['NRITEM'] ?? 0);
            $uniFrac = strtoupper((string) ($row['UNIFRACIONADA'] ?? $row['unifracionada'] ?? 'N'));
            $dadosEstoque = $this->pedidoPsDadosEstoqueItem($est, $cod, $cce, $uniFrac);
            $ped = (float) ($row['QTSOLICITADA'] ?? $row['qtsolicitada'] ?? 0);
            $atendida = $reiniciaAtendida ? 0 : (float) ($row['QTATENDIDA'] ?? 0);
            $pendente = max(0, $ped - $atendida);

            if (!$dadosEstoque['controlePeca']) {
                if (!$parcial && $pendente > $dadosEstoque['disponivel']) {
                    return $cod . ': pendente ' . $pendente . ', disponível ' . $dadosEstoque['disponivel'] . '.';
                }
                $qtAtendidaPorItem[$nritem] = $atendida;
                continue;
            }

            $qtdReservar = (int) min($pendente, $dadosEstoque['disponivel']);
            if ($qtdReservar > 0) {
                $est->produtoReserva($cce, 'PED', $idPedido, $cod, $qtdReservar, $conn);
            }

            $novaAtendida = $atendida + $qtdReservar;
            $qtAtendidaPorItem[$nritem] = $novaAtendida;

            if ($novaAtendida > $atendida) {
                $sqlUpd = 'UPDATE FAT_PEDIDO_ITEM SET QTATENDIDA = ' . $novaAtendida
                    . ' WHERE ID = ' . $idPedido . ' AND NRITEM = ' . $nritem;
                $bancoUpd = new c_banco();
                $bancoUpd->exec_sql($sqlUpd, $conn);
                $bancoUpd->close_connection();
            }

            if (!$parcial && $qtdReservar < $pendente) {
                return $cod . ': pendente ' . $pendente . ', disponível ' . $dadosEstoque['disponivel'] . '.';
            }
        }

        foreach ((array) $this->select_pedido_item_id() as $item) {
            if (isset($item['MOTIVO']) && (int) $item['MOTIVO'] === 8) {
                continue;
            }

            $cod = $item['ITEMESTOQUE'] ?? $item['itemestoque'] ?? '';
            if ($cod === '') {
                continue;
            }

            $nritem = (int) ($item['NRITEM'] ?? 0);
            $uniFrac = strtoupper((string) ($item['UNIFRACIONADA'] ?? $item['unifracionada'] ?? 'N'));
            $dadosEstoque = $this->pedidoPsDadosEstoqueItem($est, $cod, $cce, $uniFrac);
            $ped = (float) ($item['QTSOLICITADA'] ?? $item['qtsolicitada'] ?? 0);
            $atendida = (float) ($qtAtendidaPorItem[$nritem] ?? $item['QTATENDIDA'] ?? 0);
            $pendente = max(0, $ped - $atendida);

            if (!$dadosEstoque['controlePeca']) {
                if (!$parcial && $pendente > $dadosEstoque['disponivel']) {
                    return $cod . ': pendente ' . $pendente . ', disponível ' . $dadosEstoque['disponivel'] . '.';
                }
                continue;
            }

            $qtdPeca = (int) $atendida;
            if ($qtdPeca <= 0) {
                continue;
            }

            $res = c_produto_estoque::verify_itemns_order_product($idPedido, $cod, 1, $conn);
            $ok = is_array($res) ? count($res) : 0;
            if ($ok < $qtdPeca) {
                return $cod . ': reservado ' . $qtdPeca . ', disponível ' . $ok . '.';
            }
        }

        return '';
    }

    /**
     * Cancelamento: devolve ao estoque disponível reservas e baixas financeiras sem NF.
     *
     * @param resource|null $conn mysqli
     * @return string vazio se OK
     */
    public function pedidoPsLiberarEstoqueCancelamento($conn = null)
    {
        $p = new c_banco();
        $p->setTab("EST_PARAMETRO");
        $ctrl = $p->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
        $p->close_connection();
        if ($ctrl !== 'S') {
            return '';
        }

        c_produto_estoque::liberaEstoquePedidoCancelamento(
            $this->m_empresacentrocusto,
            (int) $this->getId(),
            $conn
        );

        return '';
    }

    /**
     * NF do pedido com parcela/lançamento financeiro baixado (SITPGTO = 'B').
     * Usado para não permitir Baixado (9) → Emitir NF (3) quando o faturamento já foi quitado.
     *
     * @param int $idPedido
     * @return bool
     */
    public function pedidoPossuiNotaComFinanceiroBaixado($idPedido)
    {
        $idPedido = (int) $idPedido;
        if ($idPedido <= 0) {
            return false;
        }
        // NF do pedido com financeiro quitado: vínculo por NUMLCTO (PED) ou ORIGEM NFE/NFC na NF.
        $sql = "
            SELECT 1
            FROM EST_NOTA_FISCAL n
            WHERE n.DOC = {$idPedido}
              AND n.ORIGEM = 'PED'
              AND EXISTS (
                SELECT 1
                FROM FIN_LANCAMENTO f
                WHERE f.SITPGTO = 'B'
                  AND (
                    (f.ORIGEM = 'PED' AND f.NUMLCTO = {$idPedido})
                    OR (
                        f.ORIGEM IN ('NFE', 'NFC')
                        AND (
                            f.NUMLCTO = n.ID
                            OR f.DOCTO = n.NUMERO
                            OR f.DOCTO = CAST(n.ID AS CHAR)
                        )
                    )
                  )
              )
            LIMIT 1
        ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return !empty($banco->resultado);
    }

    /**
     * Verifica se o pedido tem nota fiscal ou financeiro baixado relacionado.
     * @param int $idPedido
     * @return bool true se não tem bloqueio, false se tem bloqueio
     */
    public function verificaFinanceiroNota($idPedido)
    {
        $idPedido = (int) $idPedido;
        if ($idPedido <= 0) {
            return true;
        }

        $sql = "
            SELECT 1
            FROM EST_NOTA_FISCAL n
            WHERE n.DOC = {$idPedido}
              AND n.ORIGEM = 'PED'

            UNION

            SELECT 1
            FROM FIN_LANCAMENTO f
            WHERE f.ORIGEM = 'PED'
              AND f.NUMLCTO = {$idPedido}
              AND f.SITPGTO = 'B'

            UNION

            SELECT 1
            FROM FIN_LANCAMENTO f
            INNER JOIN EST_NOTA_FISCAL n
                ON n.DOC = {$idPedido}
               AND n.ORIGEM = 'PED'
            WHERE f.SITPGTO = 'B'
              AND f.ORIGEM IN ('NFE', 'NFC')
              AND (
                    f.NUMLCTO = n.ID
                 OR f.DOCTO = n.NUMERO
                 OR f.DOCTO = CAST(n.ID AS CHAR)
              )
        ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $temBloqueio = $banco->resultado;

        return empty($temBloqueio);
    }
} //END OF THE CLASS
