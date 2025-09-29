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
include_once($dir . "/../../bib/c_database_pdo.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");


class c_atendimento extends c_user
{

    /**
     * TABLE NAME CAT_ATENDIMENTO
     */
    private $id = NULL;
    private $numAtendimento = NULL;  // ATENDIMENTO
    private $cliente = NULL;
    private $clienteNome = NULL;
    private $contato             = NULL;
    private $dataAberturaEnd     = NULL;
    private $dataFechamentoEnd     = NULL;
    private $usrAbertura         = NULL;
    private $prioridade         = NULL;
    private $prazoEntrega         = NULL;
    private $descEquipamento     = NULL;
    private $kmEntrada             = NULL;
    private $obs                 = NULL;
    private $obsServicos         = NULL;
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
    private $pedido_id = NULL;

    //CAT_PECAS

    private $idPecas = NULL;
    private $idAtendimentoPecas = NULL;
    private $codProduto = NULL;
    private $codFabricante = NULL;
    private $codProdutoNota = NULL;
    private $quantidadePecas = NULL;
    private $unidadePecas = NULL;
    private $valorUnitarioPecas             = NULL;
    private $descricaoPecas     = NULL;
    private $valorCustoPecas     = NULL;
    private $valorDescontoPecas         = NULL;
    private $percDescontoPecas         = NULL;
    private $acrescimoPecas         = NULL;
    private $valorTotalPecas = NULL;

    //CAT_SERVICO

    private $idServico = NULL;
    private $idAtendimentoServico = NULL;
    private $catServicoId = NULL;
    private $idUser = NULL;
    private $dataServico = NULL;
    private $horaIni = NULL;
    private $horaFim             = NULL;
    private $qtdeServico = NULL;
    private $unidadeServico = NULL;
    private $valorUnitarioServico             = NULL;
    private $horaTotal     = NULL;
    private $custoUser     = NULL;
    private $descServico         = NULL;
    private $valorTotalServico = NULL;
    private $quantidadeExecutada = NULL;


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

    function setAtendimento($numAtendimento)
    {
        $this->numAtendimento = $numAtendimento;
    }
    function getAtendimento()
    {
        return $this->numAtendimento;
    }


    function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }
    function getCliente()
    {
        return $this->cliente;
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

    function setNumAtendimento($numAtendimento)
    {
        $this->numAtendimento = $numAtendimento;
    }
    function getNumAtendimento()
    {
        return isset($this->numAtendimento) ? $this->numAtendimento : 'NULL';
    }



    function setDataAberturaEnd($dataAberturaEnd)
    {
        $this->dataAberturaEnd = $dataAberturaEnd;
    }
    function getDataAberturaEnd($format = NULL)
    {
        // $this->$dataAberturaEnd = strtr($this->$dataAberturaEnd, "/","-");
        // return ($format == null ? $this->$dataAberturaEnd : 
        //     ($format == 'F' ? date('d/m/Y H:i:s', strtotime($this->$dataAberturaEnd)) 
        //                     : c_date::convertDateBd($this->$dataAberturaEnd, $this->m_banco)));
        if ($format == 'B') {
            if ($this->dataAberturaEnd == '') {
                return '';
            } else {
                $formatedValue = c_date::convertDateBd($this->dataAberturaEnd);
                return $formatedValue;
            }
        } else if ($format == 'F') {
            if ($this->dataAberturaEnd == '') {
                return '';
            } else {
                $aux = strtr($this->dataAberturaEnd, "/", "-");
                $formatedValue = date('d/m/Y', strtotime($aux));
                return $formatedValue;
            }
        } else {
            return $this->dataAberturaEnd;
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
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
    }
    function getPrioridade()
    {
        return $this->prioridade;
        // return isset($this->condPg) ? $this->condPg : 0;
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
                    $formatedValue = c_date::convertDateBd($this->prazoEntrega);
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

    function setDescEquipamento($descEquipamento)
    {
        $this->descEquipamento = $descEquipamento;
    }
    function getDescEquipamento()
    {
        return $this->descEquipamento;
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setKmEntrada($kmEntrada)
    {
        $this->kmEntrada = $kmEntrada;
    }
    function getKmEntrada()
    {
        return $this->kmEntrada;
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setObs($obs)
    {
        $this->obs = $obs;
    }
    function getObs()
    {
        return $this->obs;
    }

    function setObsServicos($obsServicos)
    {
        $this->obsServicos = $obsServicos;
    }
    function getObsServicos()
    {
        return $this->obsServicos;
    }

    function setSolucao($solucao)
    {
        $this->solucao = $solucao;
    }
    function getSolucao()
    {
        return $this->solucao;
    }

    function setValorPecas($valorPecas, $format = false)
    {
        $this->valorPecas = $valorPecas;
        if ($format):
            $this->valorPecas = number_format($this->valorPecas, 2, ',', '.');
        endif;
    }

    function getValorPecas($format = NULL)
    {
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

    function setValorVisita($valorVisita, $format = false)
    {
        $this->valorVisita = $valorVisita;
        if ($format):
            $this->valorVisita = number_format($this->valorVisita, 2, ',', '.');
        endif;
    }

    function getValorVisita($format = NULL)
    {
        if (!empty($this->valorVisita)) {
            if ($format == 'F') {
                return number_format($this->valorVisita, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorVisita);
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
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setCondPgto($condPgto)
    {
        $this->condPgto = $condPgto;
    }
    function getCondPgto()
    {
        return $this->condPgto;
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setConta($conta)
    {
        $this->conta = $conta;
    }
    function getConta()
    {
        return $this->conta;
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setGenero($genero)
    {
        $this->genero = $genero;
    }
    function getGenero()
    {
        return $this->genero;
        // return isset($this->condPg) ? $this->condPg : 0;
    }

    function setCentroCusto($centroCusto)
    {
        $this->centroCusto = $centroCusto;
    }
    function getCentroCusto()
    {
        return $this->centroCusto;
    }

    function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }
    function getSituacao()
    {
        return $this->situacao;
    }

    function setCatEquipamentoId($catEquipamentoId)
    {
        $this->catEquipamentoId = $catEquipamentoId;
    }
    function getCatEquipamentoId()
    {
        return $this->catEquipamentoId;
    }

    function setCatTipoId($catTipoId)
    {
        $this->catTipoId = $catTipoId;
    }
    function getCatTipoId()
    {
        return $this->catTipoId;
    }

    //=================PECAS========================

    function setIdPecas($idPecas)
    {
        $this->idPecas = $idPecas;
    }
    function getIdPecas()
    {
        return $this->idPecas;
    }

    function setIdAtendimentoPecas($idAtendimentoPecas)
    {
        $this->idAtendimentoPecas = $idAtendimentoPecas;
    }
    function getIdAtendimentoPecas()
    {
        return $this->idAtendimentoPecas;
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

    function setQuantidadePecas($quantidadePecas, $format = false)
    {
        $this->quantidadePecas = $quantidadePecas;
        if ($format):
            $this->quantidadePecas = number_format($this->quantidadePecas, 2, ',', '.');
        endif;
    }

    function getQuantidadePecas($format = NULL)
    {
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

    function setUnidadePecas($unidadePecas)
    {
        $this->unidadePecas = $unidadePecas;
    }
    function getUnidadePecas()
    {
        return $this->unidadePecas;
    }

    function setVlrUnitarioPecas($valorUnitarioPecas, $format = false)
    {
        $this->valorUnitarioPecas = $valorUnitarioPecas;
        if ($format):
            $this->valorUnitarioPecas = number_format($this->valorUnitarioPecas, 2, ',', '.');
        endif;
    }

    function getVlrUnitarioPecas($format = NULL)
    {
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

    function setDescricaoPecas($descricaoPecas)
    {
        $this->descricaoPecas = $descricaoPecas;
    }
    function getDescricaoPecas()
    {
        return $this->descricaoPecas;
    }

    function setVlrCustoPecas($valorCustoPecas, $format = false)
    {
        $this->valorCustoPecas = $valorCustoPecas;
        if ($format):
            $this->valorCustoPecas = number_format($this->valorCustoPecas, 2, ',', '.');
        endif;
    }

    function getVlrCustoPecas($format = NULL)
    {
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

    function setDescontoPecas($valorDescontoPecas, $format = false)
    {
        $this->valorDescontoPecas = $valorDescontoPecas;
        if ($format):
            $this->valorDescontoPecas = number_format($this->valorDescontoPecas, 2, ',', '.');
        endif;
    }

    function getDescontoPecas($format = NULL)
    {
        if (!empty($this->valorDescontoPecas)) {
            if ($format == 'F') {
                return number_format($this->valorDescontoPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->valorDescontoPecas);
            }
        } else {
            return 0;
        }
    }

    function setPercDescontoPecas($percDescontoPecas, $format = false)
    {
        $this->percDescontoPecas = $percDescontoPecas;
        if ($format):
            $this->percDescontoPecas = number_format($this->percDescontoPecas, 2, ',', '.');
        endif;
    }

    function getPercDescontoPecas($format = NULL)
    {
        if (!empty($this->percDescontoPecas)) {
            if ($format == 'F') {
                return number_format($this->percDescontoPecas, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percDescontoPecas);
            }
        } else {
            return 0;
        }
    }


    function setAcrescimoPecas($acrescimoPecas, $format = false)
    {
        $this->acrescimoPecas = $acrescimoPecas;
        if ($format):
            $this->acrescimoPecas = number_format($this->acrescimoPecas, 2, ',', '.');
        endif;
    }

    function getAcrescimoPecas($format = NULL)
    {
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
    function setTotalPecas($valorTotalPecas, $format = false)
    {
        $this->valorTotalPecas = $valorTotalPecas;
        if ($format):
            $this->valorTotalPecas = number_format($this->valorTotalPecas, 2, ',', '.');
        endif;
    }

    function getTotalPecas($format = NULL)
    {
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

    function setPedidoId($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }
    function getPedidoId()
    {
        return $this->pedido_id;
    }


    //===============FIM-PECAS=========================
    //===============SERVICO ==========================
    function setIdServico($idServico)
    {
        $this->idServico = $idServico;
    }
    function getIdServico()
    {
        return $this->idServico;
    }

    function setIdAtendimentoServico($idAtendimentoServico)
    {
        $this->idAtendimentoServico = $idAtendimentoServico;
    }
    function getIdAtendimentoServico()
    {
        return $this->idAtendimentoServico;
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

        if ($format) {
            $this->qtdeServico = number_format($this->qtdeServico, 2, ',', '.');
        }
    }

    function getQuantidadeServico($format = NULL)
    {
        if (!empty($this->qtdeServico)) {
            if ($format == 'F') {
                return number_format($this->qtdeServico, 2, ',', '.');
            } elseif ($format == 'N') {
                return $this->qtdeServico;
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
            } elseif ($format == 'N') {

                return $this->valorUnitarioServico;
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
            } elseif ($format == 'N') {

                return $this->valorTotalServico;
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

    function setIdOs($idOs)
    {
        $this->idOs = $idOs;
    }
    function getIdOs()
    {
        return $this->idOs;
    }

    function setQuantidadeExecutada($quantidadeExecutada, $format = false)
    {
        $this->quantidadeExecutada = $quantidadeExecutada;
        if ($format):
            $this->quantidadeExecutada = number_format($this->quantidadeExecutada, 2, ',', '.');
        endif;
    }

    function getQuantidadeExecutada($format = NULL)
    {
        if (!empty($this->quantidadeExecutada)) {
            if ($format == 'F') {
                return number_format($this->quantidadeExecutada, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->quantidadeExecutada);
            }
        } else {
            return 0;
        }
    }

    //===============FIM-SERVICO=========================


    //===============EQUIPE=========================

    function setEquipeId($equipeId)
    {
        $this->equipeId = $equipeId;
    }

    function getEquipeId()
    {
        return $this->equipeId;
    }

    function setUsuarioEquipe($usuarioEquipe)
    {
        $this->usuarioEquipe = $usuarioEquipe;
    }

    function getUsuarioEquipe()
    {
        return $this->usuarioEquipe;
    }
    //==============FIM-EQUIPE=========================

    /**
     * Funcao para setar todos os objetos da classe
     * @name setPedidoVenda
     * @param INT GetId chave primaria da table pedidos
     */
    public function buscaAtendimento()
    {

        $atendimento = $this->select_atendimento_id();
        $this->setId($atendimento[0]['ID']);
        $this->setCliente($atendimento[0]['CLIENTE']);
        $this->setContato($atendimento[0]['CONTATO']);
        $this->setClienteNome($atendimento[0]['NOME']);
        $this->setAtendimento($atendimento[0]['NUMATENDIMENTO']);
        $this->setDataAberturaEnd($atendimento[0]['DATAABERATEND']);
        $this->setDataFechamentoEnd($atendimento[0]['DATAFECHATEND']);
        $this->setUsrAbertura($atendimento[0]['USRABERTURA']);
        $this->setPrioridade($atendimento[0]['PRIORIDADE']);
        $this->setPrazoEntrega($atendimento[0]['PRAZOENTREGA']);
        $this->setDescEquipamento($atendimento[0]['DESCEQUIPAMENTO']);
        $this->setKmEntrada($atendimento[0]['KMENTRADA']);
        $this->setObs($atendimento[0]['OBS']);
        $this->setObsServicos($atendimento[0]['OBSSERVICO']);
        $this->setSolucao($atendimento[0]['SOLUCAO']);
        $this->setValorServicos($atendimento[0]['VALORSERVICOS']);
        $this->setValorPecas($atendimento[0]['VALORPECAS']);
        $this->setValorVisita($atendimento[0]['VALORVISITA']);
        $this->setValorDesconto($atendimento[0]['VALORDESCONTO']);
        $this->setValorTotal($atendimento[0]['VALORTOTAL']);
        $this->setTipoCobranca($atendimento[0]['TIPOCOBRANCA']);
        $this->setCondPgto($atendimento[0]['CONDPGTO']);

        $this->setConta($atendimento[0]['CONTA']);
        $this->setGenero($atendimento[0]['GENERO']);
        $this->setCentroCusto($atendimento[0]['CENTROCUSTO']);
        $this->setSituacao($atendimento[0]['CAT_SITUACAO_ID']);

        $this->setCatEquipamentoId($atendimento[0]['CAT_EQUIPAMENTO_ID']);
        $this->setCatTipoId($atendimento[0]['CAT_TIPO_ID']);
        $this->setEquipeId($atendimento[0]['EQUIPE_ID']);
        $this->setPedidoId($atendimento[0]['PEDIDO_ID']);

        // Get the users associated with the atendimento
        $usuarios_equipe = $this->select_usuarios_equipe($atendimento[0]['ID']);
        $this->setUsuarioEquipe($usuarios_equipe);
    }

    /**
     * Select users associated with the atendimento
     * @param int $atendimentoId
     * @return array
     */
    public function select_usuarios_equipe($atendimentoId)
    {
        $sql = "SELECT ID_USUARIO 
        FROM CAT_AT_EQUIPE_USUARIO 
        WHERE CAT_ATENDIMENTO_ID = " . $atendimentoId;
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $result = $consulta->resultado;
        $consulta->close_connection();

        $usuarios = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $usuarios[] = $row['ID_USUARIO'];
            }
        }
        return $usuarios;
    }

    /**
     * Atualiza a equipe e usuários associados a um atendimento
     * @name atualiza_equipe_atendimento
     * @param int $atendimentoId ID do atendimento
     * @param int $equipeId ID da equipe (opcional - se vazio, apenas remove as associações)
     * @param array $usuarioEquipe Array de IDs de usuários (opcional)
     * @return string String vazia se sucesso, mensagem de erro caso contrário
     */
    public function atualizaEquipeAtendimento($atendimentoId, $equipeId = null, $usuarioEquipe = [])
    {
        $banco = new c_banco;
        $conn = $banco->id_connection;

        try {
            // 1. Remove todas as associações existentes
            $sql = "DELETE FROM CAT_AT_EQUIPE_USUARIO ";
            $sql .= "WHERE CAT_ATENDIMENTO_ID = " . $atendimentoId;

            $result = $banco->exec_sql($sql, $conn);
            if ($banco->result === false) {
                throw new Exception("Erro ao remover usuários da equipe");
            }

            // 2. Se foi informada uma equipe, atualiza e insere os novos usuários
            if (!empty($equipeId)) {
                // Atualiza a equipe no atendimento
                $sql = "UPDATE CAT_ATENDIMENTO ";
                $sql .= "SET EQUIPE_ID = " . $equipeId . " ";
                $sql .= "WHERE ID = " . $atendimentoId;

                $result = $banco->exec_sql($sql, $conn);
                if ($banco->result === false) {
                    throw new Exception("Erro ao atualizar equipe do atendimento");
                }

                // Insere os novos usuários se informados
                if (!empty($usuarioEquipe)) {
                    foreach ($usuarioEquipe as $usuarioId) {
                        $sql = "INSERT INTO CAT_AT_EQUIPE_USUARIO ";
                        $sql .= "(CAT_ATENDIMENTO_ID, ID_EQUIPE, ID_USUARIO, CREATED_USER, CREATED_AT) ";
                        $sql .= "VALUES (" . $atendimentoId . ", " . $equipeId . ", ";
                        $sql .= $usuarioId . ", " . $this->m_userid . ", NOW())";

                        $result = $banco->exec_sql($sql, $conn);
                        if ($banco->result === false) {
                            throw new Exception("Erro ao associar usuário à equipe");
                        }
                    }
                }
            } else {
                // Se não tem equipe, também remove a associação do atendimento
                $sql = "UPDATE CAT_ATENDIMENTO ";
                $sql .= "SET EQUIPE_ID = NULL ";
                $sql .= "WHERE ID = " . $atendimentoId;

                $result = $banco->exec_sql($sql, $conn);
                if ($banco->result === false) {
                    throw new Exception("Erro ao remover equipe do atendimento");
                }
            }

            return '';
        } catch (Exception $e) {
            return 'Erro ao atualizar equipe: ' . $e->getMessage();
        } finally {
            $banco->close_connection($conn);
        }
    }

    /**
     * Calcula o total do pedido atraves do id
     * @name select_ordem_compra_total
     * @return ARRAY total do pedido
     */
    public function select_pecas_total()
    {

        if ($this->getIdAtendimentoPecas() != ''):
            $sql = "SELECT sum(VALORUNITARIO * QUANTIDADE) as totalPecas ";
            $sql .= "FROM CAT_AT_PECAS ";
            $sql .= "WHERE (CAT_ATENDIMENTO_ID = " . $this->getIdAtendimentoPecas() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALPECAS'];
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
     * @name select_desconto_pecas_total
     * @return ARRAY total do pedido
     */
    public function select_desconto_pecas_total()
    {

        if ($this->getIdAtendimentoPecas() != ''):
            $sql = "SELECT sum(DESCONTO) as totalDescontoPecas ";
            $sql .= "FROM CAT_AT_PECAS ";
            $sql .= "WHERE (CAT_ATENDIMENTO_ID = " . $this->getIdAtendimentoPecas() . ") ";

            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALDESCONTOPECAS'];
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

        if ($this->getIdAtendimentoServico() != ''):
            $sql = "SELECT sum(TOTALSERVICO) as totalServicos ";
            $sql .= "FROM CAT_AT_SERVICOS ";
            $sql .= "WHERE (CAT_ATENDIMENTO_ID = " . $this->getIdAtendimentoServico() . ") ";

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
    public function alteraPecasTotalAtendimento()
    {

        $sql = "UPDATE CAT_ATENDIMENTO ";
        $sql .= "SET VALORPECAS = " . $this->getValorPecas('B') . ", ";
        $sql .= "CAT_SITUACAO_ID = '" . $this->getSituacao() . "' ";
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
    public function select_pecas_atendimento_item()
    {
        $sql = "SELECT * FROM ";
        $sql .= "cat_at_pecas  ";
        $sql .= "WHERE (ID = '" . $this->getIdPecas() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function select_atendimento_item_id_pecas($conn = null)
    {
        $sql = "SELECT p.* FROM ";
        $sql .= "CAT_AT_PECAS as p ";
        $sql .= "WHERE (p.cat_atendimento_id = '" . $this->getIdAtendimentoPecas() . "') ";
        $sql .= "and (p.codProduto='" . $this->getCodProduto() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_atendimento_item_id_servico($conn = null)
    {
        $sql = "SELECT S.* FROM ";
        $sql .= "CAT_AT_SERVICOS as S ";
        $sql .= "WHERE (S.CAT_ATENDIMENTO_ID = '" . $this->getIdAtendimentoServico() . "') ";
        $sql .= "and (S.CAT_SERVICOS_ID='" . $this->getCatServicoId() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }




    public function select_atendimento_pecas_produto($conn = null)
    {
        $sql = "SELECT * FROM EST_PRODUTO ";
        $sql .= "WHERE (codigo='" . $this->getCodProduto() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_atendimento_cat_servico($conn = null)
    {
        $sql = "SELECT * FROM CAT_SERVICO ";
        $sql .= "WHERE (ID ='" . $this->getCatServicoId() . "') ";
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

        $sql = "INSERT INTO CAT_AT_SERVICOS (";

        $sql .= "CAT_SERVICOS_ID, ID_USER, QUANTIDADE, UNIDADE, VALUNITARIO, DESCSERVICO  , ";
        $sql .= " TOTALSERVICO, CAT_ATENDIMENTO_ID, CREATED_USER, CREATED_AT ) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=
            $this->getCatServicoId() . "', '"
            . $this->m_userid          . "', "
            . $this->getQuantidadeServico() . ", '"
            . $this->getUnidadeServico() . "', '"
            . $this->getVlrUnitarioServico('B') . "', '"
            . $this->getDescricaoServico() . "', "
            . $this->getTotalServico('B') . ", '"
            . $this->getIdAtendimentoServico() . "',"
            . $this->m_userid . ",'"
            . date("Y-m-d H:i:s") .  "' ); ";

        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = $banco->insertReg;
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiServicosPdo()
    {

        $banco = new c_banco_pdo();

        // coletar dados
        $dados = [
            'CAT_SERVICOS_ID'      => $this->getCatServicoId(),
            'ID_USER'              => $this->getIdUser(),
            'QUANTIDADE'           => $this->getQuantidadeExecutada(),
            'QUANTIDADE_EXECUTADA' => $this->getQuantidadeExecutada(),
            'UNIDADE'              => $this->getUnidadeServico(),
            'VALUNITARIO'          => $this->getVlrUnitarioServico('N'),
            'DESCSERVICO'          => $this->getDescricaoServico(),
            'TOTALSERVICO'         => $this->getTotalServico('N'),
            'CAT_ATENDIMENTO_ID'   => $this->getIdAtendimentoServico(),
            'CREATED_USER'          => $this->m_userid
        ];

        // Montar SQL
        $colunas = '`' . implode('`, `', array_keys($dados)) . '`';
        $placeholders = ':' . implode(', :', array_keys($dados));

        $sql = "INSERT INTO CAT_AT_SERVICOS ($colunas) VALUES ($placeholders)";

        try {
            $banco->prepare($sql);

            // Bind dos parâmetros
            foreach ($dados as $campo => $valor) {
                $banco->bindValue(":$campo", $valor);
            }

            //$query = $banco->queryString();
            $banco->execute();
            $result = $banco->rowCount();

            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            //return 'Erro ao cadastrar: ' . $e->getMessage();
            return false;
        }
    }


    //===============================   PECAS ===================================
    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiPecas($conn = null)
    {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO CAT_AT_PECAS (";

        $sql .= "CODPRODUTO, CODFABRICANTE, CODPRODUTONOTA, QUANTIDADE, UNIDADE, VALORUNITARIO, DESCRICAO  , DESCONTO, PERCDESCONTO, ";
        $sql .= " VALORTOTAL, CAT_ATENDIMENTO_ID, CREATED_USER, CREATED_AT ) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=
            $this->getCodProduto() . "', '"
            . $this->getCodFabricante() . "', '"
            . $this->getCodProdutoNota() . "', "
            . $this->getQuantidadePecas() . ", '"
            . $this->getUnidadePecas() . "', '"
            . $this->getVlrUnitarioPecas('B') . "', '"
            . $this->getDescricaoPecas() . "', "
            . $this->getDescontoPecas() . ", "
            . $this->getPercDescontoPecas() . ", "
            . $this->getTotalPecas('B') . ", '"
            . $this->getIdAtendimentoPecas() . "',"
            . $this->m_userid . ",'"
            . date("Y-m-d H:i:s") .  "' ); ";

        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
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
    public function alteraPecas($conn = null)
    {

        $sql = "UPDATE CAT_AT_PECAS SET ";
        $sql .= "DESCRICAO = '" . $this->getDescricaoPecas() . "', ";
        $sql .= "CODPRODUTO = '" . $this->getCodProduto() . "', ";
        $sql .= "CODFABRICANTE = '" . $this->getCodFabricante() . "', ";
        $sql .= "CODPRODUTONOTA = '" . $this->getCodProdutoNota() . "', ";
        $sql .= "QUANTIDADE = " . $this->getQuantidadePecas('B') . ", ";
        $sql .= "UNIDADE = '" . $this->getUnidadePecas() . "', ";
        $sql .= "VALORUNITARIO = " . $this->getVlrUnitarioPecas('B') . ", ";
        $sql .= "DESCONTO = " . $this->getDescontoPecas('B') . ", ";
        $sql .= "PERCDESCONTO = " . $this->getPercDescontoPecas('B') . ", ";
        $sql .= "VALORTOTAL = " . $this->getTotalPecas('B') . ", ";
        $sql .= "UPDATED_USER = '" . $this->m_userid . "', ";
        $sql .= "UPDATED_AT = '" . date("Y-m-d H:i:s") . "' ";


        $sql .= "WHERE (ID = '" . $this->getIdPecas() . "') ";

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
     * Funcao para inclusão de pecas duplicadas
     * @name duplicaPecas
     * @param INT IdPedido novo
     * @param INT IdPedido antigo 
     * @return INT ID PEDIDO_ITEM se ocorrer com sucesso
     */
    public function duplicaPecas($idNovo, $idAntigo, $conn = null)
    {
        $banco = new c_banco;
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO CAT_AT_PECAS (
            CAT_ATENDIMENTO_ID, CODPRODUTO, CODFABRICANTE, CODPRODUTONOTA, QUANTIDADE, QUANTIDADEUTILIZADA, UNIDADE, VALORUNITARIO, DESCRICAO, VALORCUSTO, 
            DESCONTO, PERCDESCONTO, ACRESCIMO, VALORTOTAL, CREATED_USER, CREATED_AT)
            SELECT " . $idNovo . " as CAT_ATENDIMENTO_ID, 
                CODPRODUTO, CODFABRICANTE, CODPRODUTONOTA,QUANTIDADE, QUANTIDADEUTILIZADA, UNIDADE, VALORUNITARIO, DESCRICAO, VALORCUSTO, 
                DESCONTO, PERCDESCONTO, ACRESCIMO, VALORTOTAL, CREATED_USER, '" . $created_at . "' AS CREATED_AT 
            FROM CAT_AT_PECAS 
            WHERE CAT_ATENDIMENTO_ID = '" . $idAntigo . "' ";

        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados das pecas ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para inclusão de pecas duplicadas
     * @name duplicaPecas
     * @param INT IdPedido novo
     * @param INT IdPedido antigo 
     * @return INT ID PEDIDO_ITEM se ocorrer com sucesso
     */
    public function duplicaServicos($idNovo, $idAntigo, $conn = null)
    {
        $banco = new c_banco;
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO CAT_AT_SERVICOS (
            CAT_ATENDIMENTO_ID, ID_USER, DATA, HORAINI, HORAFIM, HORATOTAL, CUSTOUSER, DESCSERVICO, UNIDADE, QUANTIDADE, 
            VALUNITARIO, TOTALSERVICO, CAT_SERVICOS_ID, CREATED_USER, CREATED_AT)
            SELECT " . $idNovo . " as CAT_ATENDIMENTO_ID, 
                ID_USER, DATA, HORAINI, HORAFIM, HORATOTAL, CUSTOUSER, DESCSERVICO, UNIDADE, QUANTIDADE, 
                VALUNITARIO, TOTALSERVICO, CAT_SERVICOS_ID, CREATED_USER, '" . $created_at . "' AS CREATED_AT 
            FROM CAT_AT_SERVICOS 
            WHERE CAT_ATENDIMENTO_ID = '" . $idAntigo . "' ";

        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados do servico ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraServicos($conn = null)
    {

        $sql = "UPDATE CAT_AT_SERVICOS SET ";
        $sql .= "QUANTIDADE = " . $this->getQuantidadeServico('B') . ", ";
        $sql .= "DESCSERVICO = '" . $this->getDescricaoServico() . "', ";
        $sql .= "UNIDADE = '" . $this->getUnidadeServico() . "', ";
        $sql .= "VALUNITARIO = " . $this->getVlrUnitarioServico('B') . ", ";
        $sql .= "TOTALSERVICO = " . $this->getTotalServico('B') . ", ";
        $sql .= "UPDATED_USER = '" . $this->m_userid . "', ";
        $sql .= "UPDATED_AT = '" . date("Y-m-d H:i:s") . "' ";
        $sql .= "WHERE (ID = '" . $this->getIdServico() . "') ";

        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
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
    public function excluiPecasItemAtendimento($conn = null)
    {
        $sql = "DELETE FROM ";
        $sql .= "CAT_AT_PECAS ";
        $sql .= "WHERE (id = '" . $this->getIdPecas() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
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
        $sql .= "CAT_AT_SERVICOS ";
        $sql .= "WHERE (id = '" . $this->getIdServico() . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        $msg = '';

        return $msg;
    }




    public function select_pecas_atendimento()
    {
        $sql = "SELECT T.CODFABRICANTE, T.LOCALIZACAO, P.* FROM CAT_AT_PECAS P ";
        $sql .= "LEFT JOIN EST_PRODUTO T ON  T.CODIGO=P.CODPRODUTO ";
        $sql .= "WHERE (CAT_ATENDIMENTO_ID = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_servicos_atendimento()
    {
        $sql = "SELECT * FROM CAT_AT_SERVICOS ";
        $sql .= "WHERE (CAT_ATENDIMENTO_ID = '" . $this->getId() . "') ";
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

    public function select_atendimento_pecas($id)
    {
        $sql = "SELECT @n := @n+1 AS NRITEM, P.*, T.DESCRICAO AS DESCPRODUTO, T.LOCALIZACAO FROM (SELECT @n := 0) AS NADA, CAT_AT_PECAS P ";
        $sql .= "left join EST_PRODUTO T ON (T.CODIGO = P.CODPRODUTO) ";
        $sql .= "WHERE (P.CAT_ATENDIMENTO_ID = " . $id . ") ";

        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_atendimento_servicos($id)
    {
        $sql  = "SELECT @n := @n+1 AS NRITEM_S, SA.*, U.NOMEREDUZIDO AS PESSOA ";
        $sql .= "FROM (SELECT @n := 0) AS NADA, CAT_AT_SERVICOS SA ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = SA.ID_USER) ";
        $sql .= "inner join CAT_SERVICO S ON (S.ID = SA.CAT_SERVICOS_ID) ";
        $sql .= "WHERE (SA.CAT_ATENDIMENTO_ID = " . $id . ") ";

        //echo strtoupper($sql)."<BR>";
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
    //============================== CAT_ATENDIMENTO ==========================
    //=========================================================================
    /**
     * Funcao para alterar O VALOR DO SERVIÇOS
     * @param INT ID Chave primaria da table cat_atendimento
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraServicoTotalAtendimento()
    {

        $sql = "UPDATE CAT_ATENDIMENTO ";
        $sql .= "SET VALORSERVICOS = " . $this->getValorServicos() . ", ";
        $sql .= "CAT_SITUACAO_ID = '" . $this->getSituacao() . "' ";
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
     * @name select_atendimento_id
     * @return ARRAY todos os campos da table
     * @version 20210316
     * @author Márcio Sérgio
     */
    public function select_atendimento_id()
    {

        $sql = "SELECT * ";
        $sql .= "FROM CAT_ATENDIMENTO ";
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
    public function select_atendimento_total_geral()
    {

        if ($this->getId() != ''):
            $sql = "SELECT sum(((VALORSERVICOS)+(VALORPECAS)+(VALORVISITA))-VALORDESCONTO) as TOTALGERAL ";
            $sql .= "FROM CAT_ATENDIMENTO ";
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
     * @name select_atendimento_letra
     * @param ARRAY letra paramentros para filtrar a busca 
     * @param ARRAY situacoes situacoes que sera usada para filtrar a busca
     * @return ARRAY com os atendimentos selecionados.
     */
    public function select_atendimento_letra($letra, $situacoes)
    {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = numAtendimento       
         */
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $parSit = explode("|", $situacoes);

        $sql = "SELECT A.*, C.NOME, T.DESCRICAO AS TIPODESC, S.DESCRICAO AS SITUACAODESC, S.ID as ID_SITUACAO ";
        $sql .= "FROM CAT_ATENDIMENTO  A ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = A.CLIENTE) ";
        $sql .= "LEFT JOIN CAT_TIPO T ON (T.ID = A.CAT_TIPO_ID) ";
        $sql .= "LEFT JOIN CAT_SITUACAO S ON (S.ID = A.CAT_SITUACAO_ID) ";

        if ($par[3] != '') {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[3]) ? '' : " $cond (a.id  = ($par[3]))";
        } else {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[0]) ? '' : " $cond (a.DATAABERATEND >= '" . $dataIni . " 00:00:00') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[1]) ? '' : " $cond (a.DATAABERATEND <= '" . $dataFim . " 23:59:59') ";

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
            $sql .= empty($sit) ? '' : " $cond (a.CAT_SITUACAO_ID IN (" . $sit . ")) ";
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
     * @name select_atendimento_id
     * @return ARRAY todos os campos da table com seus relacionamentos
     * @version 20210316 - Ticket
     * @author Márcio Sérgio
     */
    public function select_atendimento($id)
    {

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
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = A.USRABERTURA) ";
        $sql .= "LEFT JOIN FAT_COND_PGTO P ON (P.ID=A.CONDPGTO) ";
        $sql .= "LEFT JOIN CAT_SITUACAO S ON (S.ID=A.CAT_SITUACAO_ID) ";
        $sql .= "LEFT JOIN CAT_TIPO T ON (T.ID=A.CAT_TIPO_ID) ";
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
     * @param INT ID Chave primaria da table cat_atendimento
     * @param conn id da conexão com o banco no caso de trasaction
     * @name incluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function incluiAtendimento($conn = null)
    {
        $banco = new c_banco;

        $sql = "INSERT INTO CAT_ATENDIMENTO (";
        $sql .= "CLIENTE, CONTATO, USRABERTURA, VALORPECAS, VALORSERVICOS, VALORVISITA, VALORDESCONTO, DATAABERATEND, DATAFECHATEND, PRAZOENTREGA, OBS, OBSSERVICO, CAT_EQUIPAMENTO_ID, EQUIPE_ID, CENTROCUSTO, DESCEQUIPAMENTO, CONDPGTO, CAT_TIPO_ID, CAT_SITUACAO_ID, PEDIDO_ID, CREATED_USER, CREATED_AT)";

        $sql .= " VALUES ('";
        $sql .= $this->getCliente() . "','"
            . $this->getContato() . "','"
            . $this->getUsrAbertura() . "','"
            . $this->getValorPecas() . "','"
            . $this->getValorServicos() . "','"
            . $this->getValorVisita() . "','"
            . $this->getValorDesconto() . "','"
            . $this->getDataAberturaEnd('B') . "',";

        // Data Fechamento
        $dataFechamento = $this->getDataFechamentoEnd('B');
        $sql .= ($dataFechamento == '') ? "null," : "'" . $dataFechamento . "',";

        $sql .= "'" . $this->getPrazoEntrega('B') . "','"
            . $this->getObs() . "','"
            . $this->getObsServicos() . "',";

        // CAT_EQUIPAMENTO_ID
        $sql .= ($this->getCatEquipamentoId() == '') ? "null," : "'" . $this->getCatEquipamentoId() . "',";

        // EQUIPE_ID
        $sql .= ($this->getEquipeId() == '') ? "null," : $this->getEquipeId() . ",";
        $sql .= ($this->getCentroCusto() == '') ? "null," : $this->getCentroCusto() . ",";
        $sql .= "'" . $this->getDescEquipamento() . "','"
            . $this->getCondPgto() . "','"
            . $this->getCatTipoId() . "','"
            . $this->getSituacao() . "',";

        // PEDIDO_ID
        $sql .= ($this->getPedidoId() == '' || $this->getPedidoId() == null) ? "null," : $this->getPedidoId() . ",";

        $sql .= "'" . $this->m_userid . "','" . date("Y-m-d H:i:s") . "')";

        $result = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();

        if ($result > 0) {
            return $lastReg;
        } else {
            return 'Os dados do atendimento ' . $this->getId() . ' não foram cadastrados!';
        }
    }

    /**
     * Funcao para alterar atendimento
     * @param INT ID Chave primaria da table cat_atendimento
     * @param conn(1) id da conexão com o banco no caso de trasaction
     * @name alteraAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function alteraAtendimento($conn = null)
    {
        $sql = "UPDATE CAT_ATENDIMENTO SET ";
        $sql .= "cliente = '" . $this->getCliente() . "', ";
        $sql .= "contato = '" . $this->getContato() . "', ";
        $sql .= "condpgto = '" . $this->getCondPgto() . "', ";
        $sql .= "cat_situacao_id = '" . $this->getSituacao() . "', ";
        $sql .= "cat_tipo_id = '" . $this->getCatTipoId() . "', ";
        $sql .= "usrAbertura = '" . $this->getUsrAbertura() . "', ";
        $sql .= "DATAABERATEND = '" . $this->getDataAberturaEnd('B') . "', ";
        $dataFechamento = $this->getDataFechamentoEnd('B');
        if ($dataFechamento == '') {
            $sql .=  "DATAFECHATEND = null, ";
        } else {
            $sql .= "DATAFECHATEND = '" . $this->getDataFechamentoEnd('B') . "', ";
        }
        $sql .= "prazoEntrega = '" . $this->getPrazoEntrega('B') . "', ";
        $sql .= "descEquipamento = '" . $this->getDescEquipamento() . "', ";
        
        $catEquipamentoId = $this->getCatEquipamentoId();
        if (empty($catEquipamentoId)) {
            $sql .= "cat_equipamento_id = NULL, ";
        } else {
            $sql .= "cat_equipamento_id = '" . $catEquipamentoId . "', ";
        }
        
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "obsServico = '" . $this->getObsServicos() . "', ";
        $sql .= "valorServicos = '" . $this->getValorServicos('B') . "', ";
        $sql .= "valorPecas = '" . $this->getValorPecas('B') . "', ";
        $sql .= "valorVisita = '" . $this->getValorVisita('B') . "', ";
        $sql .= "valorDesconto = '" . $this->getValorDesconto('B') . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Atendimento ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * Funcao para duplicar Ordem de Servico
     * @name duplicaOs
     * @return INT ID PEDIDO se ocorrer com sucesso
     */
    public function duplicaOs($conn = null)
    {
        $banco = new c_banco;

        $situacao = 2; // Em atendimento
        $dataAbertura = date('Y-m-d H:i:s');

        $sql = "INSERT INTO CAT_ATENDIMENTO (
            CLIENTE,CONTATO,DATAABERATEND,USRABERTURA,PRIORIDADE,PRAZOENTREGA,DESCEQUIPAMENTO,
            KMENTRADA,OBS,OBSSERVICO,SOLUCAO,VALORSERVICOS,VALORPECAS,VALORUTILIZADOPECAS,TOTALUTILIZADOPECAS,VALORVISITA,VALORDESCONTO,VALORTOTAL,
            TIPOCOBRANCA,CONDPGTO,CONTA,GENERO,CENTROCUSTO,CAT_SITUACAO_ID,CAT_EQUIPAMENTO_ID,CAT_TIPO_ID,
            CREATED_USER,CREATED_AT) ";
        $sql .= "SELECT CLIENTE,CONTATO,'" . $dataAbertura . "' as DATAABERATEND, USRABERTURA,PRIORIDADE,PRAZOENTREGA,DESCEQUIPAMENTO,
            KMENTRADA,OBS,OBSSERVICO,SOLUCAO,VALORSERVICOS,VALORPECAS,VALORUTILIZADOPECAS,TOTALUTILIZADOPECAS,VALORVISITA,VALORDESCONTO,VALORTOTAL,
            TIPOCOBRANCA,CONDPGTO,CONTA,GENERO,CENTROCUSTO, " . $situacao . " as CAT_SITUACAO_ID,CAT_EQUIPAMENTO_ID,CAT_TIPO_ID,
            CREATED_USER,CREATED_AT  FROM CAT_ATENDIMENTO ";
        $sql .= "WHERE ID = '" . $this->getId() . "'";

        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da OS ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para excluir atendimento
     * @param INT ID Chave primaria da table cat_atendimento
     * @param conn id da conexão com o banco no caso de trasaction
     * @name excluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function excluiAtendimento($conn = null)
    {
        $sql = "DELETE FROM ";
        $sql .= "CAT_ATENTIMENTO ";
        $sql .= "WHERE (id = '" . $this->getId() . "')";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Atendimento ' . $this->getId() . ' n&atilde;o foi excluido!';
        }
    }

    public function buscaParametros()
    {
        $consulta = new c_banco();
        $sql = "SELECT * FROM cat_parametros;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $this->setCondPgto($result[0]['CONDPGTO']);
        $this->setSituacao($result[0]['SITUACAOINCLUSAO']);
        $this->setObs($result[0]['MSGATENDIMENTO']);
        $this->setObsServicos($result[0]['MSGORCAMENTO']);
    }

    /**
     * Funcao para selecionar imagem da ordem de serviço
     * @name select_os_imagem
     * @param INT $id
     * @return array com as imagens da ordem selecionada
     */
    public function select_os_imagem($id = null)
    {

        if ($id == null):
            $id = $this->getId();
        endif;

        $sql  = "SELECT `ID`,`TABLE`, `TABLE_ID`, SUBSTRING_INDEX(`PATH`, '/', -4) AS `PATH` ";
        $sql .= "FROM AMB_GED ";
        $sql .= "WHERE (`TABLE_ID` = " . $id . ") AND (`TABLE` = 'cat_atendimento') ; ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_os_imagem

    /**
     * Funcao para gravar imagem da ordem de serviço
     * @name gravaImagemOS
     * @param String $mod
     * @param String $destaque
     * @return int id da imagem gravada
     */
    public function gravaImagemOS($id, $path, $destaque)
    {
        $sql  = "INSERT INTO AMB_GED (`TABLE`, `TABLE_ID`, `PATH`, `DESTAQUE`, `USER_INSERT`) ";
        $sql .= "VALUES ('cat_atendimento'," . $id . ",'" . $path . "','" . $destaque . "', " . $this->m_userid . ")";

        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql_lower_case($sql);
        if ($banco->result):
            $lastReg = $banco->insertReg;
            $banco->close_connection();
            return $lastReg;
        else:
            $banco->close_connection();
            return '';
        endif;
    } //fim gravaImagemOS

    /**
     * Funcao para excluir imagem da ordem de serviço
     * @name excluiImagemOS
     * @param int $id
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiImagemOS($id)
    {
        $sql  = "DELETE FROM AMB_GED ";
        $sql .= "WHERE (`id` = " . $id . ")";

        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        //echo($sql);
        if ($res_imagem > 0) {
            return '';
        } else {
            return 'A Imagem não foi excluida!';
        }
    } //fim excluiImagemOS

    /**
     * Funcao para alterar o situação da OS
     * @param INT ID Chave primaria da table cat_atendimento
     * @name estornaOs
     * @return NULL quando ok ou msg erro
     */
    public function estornaOs($id)
    {
        $sql = "UPDATE CAT_ATENDIMENTO SET ";
        $sql .= "CAT_SITUACAO_ID = 2 ";
        $sql .= "WHERE id = " . $id . ";";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
} //	END OF THE CLASS
