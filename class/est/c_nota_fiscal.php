<?php

/**
 * @package   astec
 * @name      c_nota_fiscal_
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
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../bib/c_date.php");


//Class C_NOTA_FISCAL
class c_nota_fiscal extends c_user
{
    /**
     * TABLE NAME EST_NOTA_FISCAL
     */

    // Campos tabela
    private $id                     = NULL; // integer not null
    private $modelo                 = NULL; // char(2) not null
    private $serie                  = NULL; // char(3) not null
    private $numero                 = NULL; // integer not null
    private $pessoa                 = NULL; // integer not null
    private $cpfNota                = NULL; // integer not null
    private $nomePessoa             = NULL; // integer not null
    private $tipoPessoa             = NULL; // char
    private $ufPessoa               = NULL; // varchar
    private $emissao                = NULL; // date not null
    private $idNatop                = NULL; // int not null
    private $natOperacao            = NULL; // varchar(20) not null
    private $tipo                   = NULL; // char (1) not null
    private $situacao               = NULL; // char (1) not null
    private $formaPgto              = NULL; // integer not null 
    private $condPgto              = NULL; // integer not null 
    private $dataSaidaEntrada       = NULL; // date not null
    private $formaEmissao           = NULL; // char(1) not null
    private $finalidadeEmissao      = NULL; // char(1) not null
    private $nfeReferenciada        = NULL; // varchar(45)  null
    private $centroCusto            = NULL; // integer not null
    private $genero                 = NULL; // varchar(4) not null
    private $totalnf                = NULL; // numeric(11,2) not null
    private $obs                    = NULL; // text
    private $frete                  = NULL; // numeric(11,2) not null
    private $despacessorias         = NULL; // numeric(11,2) not null
    private $seguro                 = NULL; // numeric(11,2) not null            
    private $usuarioConferencia     = NULL; //smallint
    private $dataConferencia        = NULL; // date
    private $modFrete               = NULL; // char(1)
    private $transportador          = NULL; // int(11)
    private $nomeTransportador      = NULL;
    private $volume                 = NULL; // int(11)
    private $placaVeiculo           = NULL; // varchar(15)
    private $codAntt                = NULL; // varchar(20)
    private $uf                     = NULL; // varchar(2)
    private $volEspecie             = NULL; // varchar(45)
    private $volMarca               = NULL; // varchar(45)
    private $volPesoLiq             = NULL; // int(11)
    private $volPesoBruto           = NULL; // int(11)
    private $origem                 = NULL; // varchar(3)
    private $doc                    = NULL; // int(11)
    private $pathDanfe              = NULL; // varchar(255)
    private $chNFe                  = NULL; // varchar(100)
    private $dhRecbto               = NULL; // varchar(45)
    private $nProt                  = NULL; // varchar(45)
    private $digVal                 = NULL; // varchar(45)
    private $verAplic               = NULL; // varchar(15)
    private $descontoGeral          = NULL; // decimal(11,4)

    private $endereco               = NULL;
    private $numEndereco            = NULL;
    private $complemento            = NULL;
    private $bairro                 = NULL;
    private $cidade                 = NULL;
    private $cep                    = NULL;
    private $fone                   = NULL;
    private $email                  = NULL;
    private $vendaPresencial        = NULL;
    //construtor
    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        //session_start();
        c_user::from_array($_SESSION['user_array']);
    }

    /**
     * METODOS DE SETS E GETS
     */


    function getContrato()
    {
        return $this->contrato;
    }
    function setContrato($contrato)
    {
        $this->contrato = $contrato;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }
    public function getModelo()
    {
        return $this->modelo;
    }

    public function setSerie($serie)
    {
        $this->serie = strtoupper($serie);
    }
    public function getSerie()
    {
        return $this->serie;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    public function getNumero()
    {
        return $this->numero;
    }

    public function setPessoa($pessoa)
    {
        $this->pessoa = $pessoa;
    }
    public function getPessoa()
    {
        return $this->pessoa;
    }

    public function setNomePessoa()
    {
        $pessoa = new c_conta();
        $pessoa->setId($this->getPessoa());
        $reg_nome = $pessoa->select_conta();
        $this->nomePessoa = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->endereco = $reg_nome[0]['ENDERECO'];
        $this->numEndereco = $reg_nome[0]['NUMERO'];
        $this->complemento = $reg_nome[0]['COMPLEMENTO'];
        $this->bairro = $reg_nome[0]['BAIRRO'];
        $this->cidade = $reg_nome[0]['CIDADE'];
        $this->cep = $reg_nome[0]['CEP'];
        $this->ufPessoa = $reg_nome[0]['UF'];
        $this->fone = $reg_nome[0]['FONE'];
        $this->email = $reg_nome[0]['EMAIL'];
    }
    public function getNomePessoa()
    {
        return $this->nomePessoa;
    }
    public function getEndereco()
    {
        return $this->endereco;
    }
    public function getNumEndereco()
    {
        return $this->numEndereco;
    }
    public function getComplemento()
    {
        return $this->complemento;
    }
    public function getBairro()
    {
        return $this->bairro;
    }
    public function getCidade()
    {
        return $this->cidade;
    }
    public function getCep()
    {
        return $this->cep;
    }
    public function getFone()
    {
        return $this->fone;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getTipoPessoa()
    {
        return $this->tipoPessoa;
    }
    public function getUfPessoa()
    {
        return $this->ufPessoa;
    }

    public function setCpfNota($cpf)
    {
        $this->cpfNota = $cpf;
    }
    public function getCpfNota()
    {
        return $this->cpfNota;
    }

    public function setEmissao($emissao)
    {
        $this->emissao = $emissao;
    }
    public function getEmissao($format = null)
    {
        $this->emissao = strtr($this->emissao, "/", "-");
        switch ($format) {
            case 'F':
                return date('d/m/Y H:i', strtotime($this->emissao));
                break;
            case 'B':
                return c_date::convertDateBd($this->emissao, $this->m_banco);
                break;
            default:
                return $this->emissao;
        }
    }

    public function setIdNatop($idNatop)
    {
        $this->idNatop = $idNatop;
    }
    public function getIdNatop()
    {
        return $this->idNatop;
    }

    public function setNatOperacao($natOperacao)
    {
        $this->natOperacao = strtoupper($natOperacao);
    }
    public function getNatOperacao()
    {
        $idNat = $this->getIdNatop();
        if (isset($idNat) and ($idNat != '') and ($idNat != 0)):
            $field = new c_banco();
            $field->setTab('EST_NAT_OP');
            return $field->getField('NATOPERACAO', 'id=' . $this->getIdNatop());
        else:
            return $this->natOperacao;
        endif;
    }

    public function setTipo($tipo)
    {
        $this->tipo = strtoupper($tipo);
    }
    public function getTipo()
    {
        return $this->tipo;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = strtoupper($situacao);
    }
    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setFormaPgto($formaPgto)
    {
        $this->formaPgto = $formaPgto;
    }
    public function getFormaPgto()
    {
        return $this->formaPgto;
    }

    public function setCondPgto($condPgto)
    {
        $this->condPgto = $condPgto;
    }
    public function getCondPgto()
    {
        if (is_numeric($this->condPgto)):
            return $this->condPgto;
        else:
            return 0;
        endif;
    }

    public function setDataSaidaEntrada($dataSaidaEntrada)
    {
        $this->dataSaidaEntrada = $dataSaidaEntrada;
    }
    public function getDataSaidaEntrada($format = null)
    {
        $this->dataSaidaEntrada = strtr($this->dataSaidaEntrada, "/", "-");
        switch ($format) {
            case 'F':
                return date('d/m/Y H:i', strtotime($this->dataSaidaEntrada));
                break;
            case 'B':
                return c_date::convertDateBd($this->dataSaidaEntrada, $this->m_banco);
                break;
            default:
                return $this->dataSaidaEntrada;
        }
    }

    public function setFormaEmissao($formaEmissao)
    {
        $this->formaEmissao = strtoupper($formaEmissao);
    }
    public function getFormaEmissao()
    {
        return $this->formaEmissao;
    }

    public function setFinalidadeEmissao($finalidadeEmissao)
    {
        $this->finalidadeEmissao = strtoupper($finalidadeEmissao);
    }
    public function getFinalidadeEmissao()
    {
        return $this->finalidadeEmissao;
    }

    public function setNfeReferenciada($nfeReferenciada)
    {
        $this->nfeReferenciada = strtoupper($nfeReferenciada);
    }
    public function getNfeReferenciada()
    {
        return $this->nfeReferenciada;
    }

    public function setCentroCusto($centroCusto)
    {
        $this->centroCusto = $centroCusto;
    }
    public function getCentroCusto()
    {
        return $this->centroCusto;
    }

    public function setGenero($genero)
    {
        $this->genero = strtoupper($genero);
    }
    public function getGenero()
    {
        return $this->genero;
    }

    public function setModFrete($modFrete)
    {
        $this->modFrete = strtoupper($modFrete);
    }
    public function getModFrete()
    {
        return $this->modFrete;
    }

    public function setTransportador($transportador)
    {
        $this->transportador = $transportador;
    }
    public function getTransportador()
    {
        return $this->transportador;
    }

    public function setNomeTransportador()
    {
        $pessoa = new c_conta();
        $pessoa->setId($this->getTransportador());
        $reg_nome = $pessoa->select_conta();
        $this->nomeTransportador = $reg_nome[0]['NOME'];
    }
    public function getNomeTransportador()
    {
        return $this->nomeTransportador;
    }

    public function setPlacaVeiculo($placaVeiculo)
    {
        $this->placaVeiculo = strtoupper($placaVeiculo);
    }
    public function getPlacaVeiculo()
    {
        return $this->placaVeiculo;
    }

    public function setCodAntt($codAntt)
    {
        $this->codAntt = strtoupper($codAntt);
    }
    public function getCodAntt()
    {
        return $this->codAntt;
    }

    public function setUf($uf)
    {
        $this->uf = strtoupper($uf);
    }
    public function getUf()
    {
        return $this->uf;
    }

    public function setVolume($volume)
    {
        $this->volume = strtoupper($volume);
    }
    public function getVolume()
    {
        if (is_numeric($this->volume)):
            return $this->volume;
        else:
            return 1;
        endif;
    }

    public function setVolEspecie($volEspecie)
    {
        $this->volEspecie = strtoupper($volEspecie);
    }
    public function getVolEspecie()
    {
        return $this->volEspecie;
    }

    public function setVolMarca($volMarca)
    {
        $this->volMarca = strtoupper($volMarca);
    }
    public function getVolMarca()
    {
        return $this->volMarca;
    }

    public function setVolPesoLiq($volPesoLiq)
    {
        $this->volPesoLiq = strtoupper($volPesoLiq);
    }
    public function getVolPesoLiq()
    {
        if (is_numeric($this->volPesoLiq)):
            return $this->volPesoLiq;
        else:
            return 0;
        endif;
    }

    public function setVolPesoBruto($volPesoBruto)
    {
        $this->volPesoBruto = strtoupper($volPesoBruto);
    }
    public function getVolPesoBruto()
    {
        if (is_numeric($this->volPesoBruto)):
            return $this->volPesoBruto;
        else:
            return 0;
        endif;
    }


    public function setTotalnf($totalnf, $format = false)
    {
        $this->totalnf = $totalnf;
        if ($format):
            $this->totalnf = number_format($this->totalnf, 2, ',', '.');
        endif;
    }
    public function getTotalnf($format = null)
    {
        if (isset($this->totalnf)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->totalnf);
                    break;
                case 'F':
                    return number_format((float) $this->totalnf, 2, ',', '.');
                    break;
                default:
                    return $this->totalnf;
            }
        else:
            return 0;

        endif;
    }

    public function setObs($obs)
    {
        $this->obs = $obs;
    }
    public function getObs()
    {
        return $this->obs;
    }

    public function setFrete($frete, $format = false)
    {
        $this->frete = $frete;
        if ($format):
            $this->frete = number_format($this->frete, 2, ',', '.');
        endif;
    }
    public function getFrete($format = null)
    {
        if (isset($this->frete)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->frete);
                    break;
                case 'F':
                    return number_format((float) $this->frete, 2, ',', '.');
                    break;
                default:
                    return $this->frete;
            }
        else:
            return 0;
        endif;
    }
    public function setDespAcessorias($despAcessorias, $format = false)
    {
        $this->despAcessorias = $despAcessorias;
        if ($format):
            $this->despAcessorias = number_format($this->despAcessorias, 2, ',', '.');
        endif;
    }
    public function getDespAcessorias($format = null)
    {
        if (isset($this->despAcessorias)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->despAcessorias);
                    break;
                case 'F':
                    return number_format((float) $this->despAcessorias, 2, ',', '.');
                    break;
                default:
                    return $this->despAcessorias;
            }
        else:
            return 0;
        endif;
    }

    public function setSeguro($seguro, $format = false)
    {
        $this->seguro = $seguro;
        if ($format):
            $this->seguro = number_format($this->seguro, 2, ',', '.');
        endif;
    }
    public function getSeguro($format = null)
    {
        if (isset($this->seguro)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->seguro);
                    break;
                case 'F':
                    return number_format((float) $this->seguro, 2, ',', '.');
                    break;
                default:
                    return $this->seguro;
            }
        else:
            return 0;
        endif;
    }

    public function setUsuarioConferencia($usuarioConferencia)
    {
        $this->usuarioConferencia = $usuarioConferencia;
    }
    public function getUsuarioConferencia()
    {
        return $this->usuarioConferencia;
    }

    public function setDataConferencia($dataConferencia)
    {
        $this->dataConferencia = strtoupper($dataConferencia);
    }
    public function getDataConferencia($format = null)
    {
        $this->dataConferencia = strtr($this->dataConferencia, "/", "-");
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->dataConferencia));
                break;
            case 'B':
                return c_date::convertDateBd($this->dataConferencia, $this->m_banco);
                break;
            default:
                return $this->dataConferencia;
        }
    }

    public function setOrigem($origem)
    {
        $this->origem = $origem;
    }
    public function getOrigem()
    {
        return $this->origem;
    }

    public function setDoc($doc)
    {
        $this->doc = $doc;
    }
    public function getDoc()
    {
        return $this->doc;
    }

    public function setPathDanfe($path)
    {
        $this->pathDanfe = $path;
    }
    public function getPathDanfe()
    {
        return $this->pathDanfe;
    }

    public function setChNFe($chNfe)
    {
        $this->chNFe = $chNfe;
    }
    public function getChNfe()
    {
        return $this->chNFe;
    }

    public function setDhRecbto($dhRecbto)
    {
        $this->dhRecbto  = $dhRecbto;
    }
    public function getDhRecbto()
    {
        return $this->dhRecbto;
    }

    public function setNProt($nProt)
    {
        $this->nProt = $nProt;
    }
    public function getNProt()
    {
        return $this->nProt;
    }

    public function setDigVal($digVal)
    {
        $this->digVal = $digVal;
    }
    public function getDigVal()
    {
        return $this->digVal;
    }

    public function setVerAplic($verAplic)
    {
        $this->verAplic = $verAplic;
    }
    public function getVerAplic()
    {
        return $this->verAplic;
    }

    public function setDescontoGeral($descontoGeral, $format = false)
    {
        $this->descontoGeral = $descontoGeral;
        if ($format):
            $this->descontoGeral = number_format($this->descontoGeral, 2, ',', '.');
        endif;
    }
    public function getDescontoGeral($format = null)
    {
        if (isset($this->descontoGeral)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->descontoGeral);
                    break;
                case 'F':
                    return number_format((float) $this->descontoGeral, 2, ',', '.');
                    break;
                default:
                    return $this->descontoGeral;
            }
        else:
            return 0;
        endif;
    }

    public function setVendaPresencial($vendaPresencial)
    {
        $this->vendaPresencial = $vendaPresencial;
    }
    public function getVendaPresencial()
    {
        return $this->vendaPresencial;
    }


    //############### FIM SETS E GETS ###############

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function setNotaFiscal()
    {

        $notaFiscal = $this->select_nota_fiscal();
        $this->setId($notaFiscal[0]['ID']);
        $this->setModelo($notaFiscal[0]['MODELO']);
        $this->setSerie($notaFiscal[0]['SERIE']);
        $this->setNumero($notaFiscal[0]['NUMERO']);
        $this->setPessoa($notaFiscal[0]['PESSOA']);
        $this->setNomePessoa();
        $this->setCpfNota($notaFiscal[0]['CPFNOTA']);
        $this->setEmissao($notaFiscal[0]['EMISSAO']);
        $this->setIdNatop($notaFiscal[0]['IDNATOP']);
        $this->setNatOperacao($notaFiscal[0]['NATOPERACAO']);
        $this->setTipo($notaFiscal[0]['TIPO']);
        $this->setSituacao($notaFiscal[0]['SITUACAO']);
        $this->setFormaPgto($notaFiscal[0]['FORMAPGTO']);
        $this->setCondPgto($notaFiscal[0]['CONDPGTO']);
        $this->setDataSaidaEntrada($notaFiscal[0]['DATASAIDAENTRADA']);
        $this->setFormaEmissao($notaFiscal[0]['FORMAEMISSAO']);
        $this->setFinalidadeEmissao($notaFiscal[0]['FINALIDADEEMISSAO']);
        $this->setNfeReferenciada($notaFiscal[0]['NFEREFERENCIADA']);
        $this->setCentroCusto($notaFiscal[0]['CENTROCUSTO']);
        $this->setGenero($notaFiscal[0]['GENERO']);
        $this->setTotalnf($notaFiscal[0]['TOTALNF']);
        $this->setObs($notaFiscal[0]['OBS']);
        $this->setFrete($notaFiscal[0]['FRETE'], True);
        $this->setDespAcessorias($notaFiscal[0]['DESPACESSORIAS'], True);
        $this->setSeguro($notaFiscal[0]['SEGURO'], True);
        $this->setUsuarioConferencia($notaFiscal[0]['USUARIOCONFERENCIA']);
        $this->setDataConferencia($notaFiscal[0]['DATACONFERENCIA']);
        $this->setContrato($notaFiscal[0]['CONTRATO']);
        $this->setModFrete($notaFiscal[0]['MODFRETE']);
        $this->setTransportador($notaFiscal[0]['TRANSPORTADOR']);
        $this->setPlacaVeiculo($notaFiscal[0]['PLACAVEICULO']);
        $this->setCodAntt($notaFiscal[0]['CODANTT']);
        $this->setUf($notaFiscal[0]['UF']);
        $this->setVolume($notaFiscal[0]['VOLUME']);
        $this->setVolEspecie($notaFiscal[0]['VOLESPECIE']);
        $this->setVolMarca($notaFiscal[0]['VOLMARCA']);
        $this->setVolPesoLiq($notaFiscal[0]['VOLPESOLIQ']);
        $this->setVolPesoBruto($notaFiscal[0]['VOLPESOBRUTO']);
        $this->setChNFe($notaFiscal[0]['CHNFE']);
        $this->setNProt($notaFiscal[0]['NPROT']);
        $this->setPathDanfe($notaFiscal[0]['PATHDANFE']);
        $this->setDhRecbto($notaFiscal[0]['DHRECBTO']);
        $this->setDigVal($notaFiscal[0]['DIGVAL']);
        $this->setVerAplic($notaFiscal[0]['VERAPLIC']);
        $this->setOrigem($notaFiscal[0]['ORIGEM']);
        $this->setDoc($notaFiscal[0]['DOC']);
        $this->setCentroCusto($notaFiscal[0]['CENTROCUSTO']);
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalEntrada()
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (serie = '" . $this->getSerie() . "') and (numero = " . $this->getNumero() . ")" .
            " and (pessoa = " . $this->getPessoa() . ") and (tipo = '0') and (situacao IN ('B','A'))";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }

    //---------------------------------------------------------------

    /**
     * Funcao para consultar se existe nota nao processada (NP)
     * @param NULL
     * @name existeNotaFiscalNaoProcessada
     * @return ID or FALSE
     */
    public function existeNotaFiscalNaoProcessada()
    {

        //serie com zeros a esquerda, pois pelo manifesto grava nesse formato
        $serieFormatada = str_pad($this->getSerie(), 3, '0', STR_PAD_LEFT);

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (serie = '" . $this->getSerie() . "' OR serie = '" . $serieFormatada . "') AND (numero = " . $this->getNumero() . ") " .
            "AND (pessoa = '" . $this->getPessoa() . "' ) AND (tipo = '0') AND (situacao = 'NP')";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado) ? $banco->resultado[0]['ID'] : false;
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalPedido($pedido)
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (ORIGEM= 'PED' and doc = " . $pedido . ")";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscal()
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (serie = '" . $this->getSerie() . "' and numero = " . $this->getNumero() . ")";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }

    //fim existeDocumento]

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalEmp($modelo, $serie, $num, $cc, $arr = false, $conn = null)
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (CENTROCUSTO=" . $cc . ") and (TIPO=1) AND (MODELO=" . $modelo . ") AND (SERIE=" . $serie . ") AND (numero=" . $num . ")";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        if ($arr == true):
            return $banco->resultado;
        else:
            return is_array($banco->resultado);
        endif;
    }

    //fim existeDocumento]


    public function geraNumNf($modelo, $serie, $cc, $conn = null)
    {
        $numNf = 0;
        $numAtArray = null;

        $sql = "SELECT MAX(NUMERO) AS ULTIMANF FROM EST_NOTA_FISCAL WHERE (substr(CENTROCUSTO,1,2)=" . substr($cc, 0, 2) . ") and (TIPO=1) AND (MODELO=" . $modelo . ") AND (SERIE=" . $serie . ") and (SITUACAO<>'I')";
        //ECHO $sql;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($banco->result):
            $numAtArray = $banco->resultado;
            $numNf = intval($numAtArray[0]['ULTIMANF']);
            do {
                $numNf = $numNf + 1;
                $result = $this->existeNotaFiscalEmp($modelo, $serie, $numNf, $cc, $conn);
            } while ($result == TRUE);
            return $numNf;
        else:
            return $banco->resultado;
        endif;
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_nota_fiscal($conn = null)
    {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (ID = " . $this->getId() . ") ";

        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    //============================================================  




    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalNum()
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE numero = '" . $this->getNumero() . "'";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalBaixa($id)
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (id = '" . $id . "') ";
        $sql .= "and (situacao = 'B') ";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function existeNotaFiscalProduto($id)
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE (idnf = '" . $id . "') ";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    //fim existeDocumento

    //fim select_nota_fiscal
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function select_nota_fiscal_primeira($codProduto)
    {

        $sql = "SELECT N.ID AS IDNF, P.ID AS IDNFPRODUTO, O.* ";
        $sql .= "FROM EST_NOTA_FISCAL N ";
        $sql .= "INNER JOIN EST_NOTA_FISCAL_PRODUTO P ON (P.IDNF=N.ID) ";
        $sql .= "INNER JOIN EST_NOTA_FISCAL_PRODUTO_OS O ON (O.IDNFENTRADA=N.ID)";
        $sql .= "WHERE (O.APLICADO <> 'S') AND (P.CODPRODUTO = '" . $codProduto . "') ";
        $sql .= "ORDER BY N.EMISSAO";

        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    //fim select_nota_fiscal
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_nota_fiscal_geral()
    {
        $sql = "SELECT DISTINCT n.*, c.nomereduzido, g.descricao as descgenero, r.descricao as filial, s.padrao as situacaoNota, t.padrao as tipoNota ";
        $sql .= "FROM est_nota_fiscal n ";
        $sql .= "inner join fin_genero g on g.genero = n.genero ";
        $sql .= "inner join fin_cliente c on c.cliente = n.pessoa ";
        $sql .= "inner join fin_centro_custo r on n.centrocusto = r.centrocusto ";
        $sql .= "inner join amb_ddm s on ((s.alias='EST_MENU') and (s.campo='SituacaoNota') and (s.tipo = n.situacao)) ";
        $sql .= "inner join amb_ddm t on ((t.alias='EST_MENU') and (t.campo='TipoNotaFiscal') and (t.tipo = n.tipo)) ";
        $sql .= "ORDER BY n.centrocusto, n.serie, n.numero ";
        // echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

        //	$this->exec_sql($sql);
        //	return $this->resultado;
    }

    //fim select_nota_fiscal_geral
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function select_nota_fiscal_letra($letra, $joinNfProduto = false)
    {

        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[3]);
        $dataFim = c_date::convertDateTxt($par[4]);

        $sql = "SELECT DISTINCT n.*, c.nomereduzido, g.descricao as descgenero, r.descricao as filial, s.padrao as situacaoNota, t.padrao as tipoNota ";
        if ($joinNfProduto == true) {
            $sql .= " , u.nome as nomeUsuario, p.descricao as nomeProduto, p.unidade, p.quant ";
        }
        $sql .= "FROM est_nota_fiscal n ";
        if ($joinNfProduto == true) {
            $sql .= "left join est_nota_fiscal_produto p on (n.id = p.idnf) ";
            $sql .= "left join amb_usuario u on (n.userinsert = u.usuario) ";
        }
        $sql .= "inner join fin_genero g on g.genero = n.genero ";
        $sql .= "inner join fin_cliente c on c.cliente = n.pessoa ";
        $sql .= "inner join fin_centro_custo r on n.centrocusto = r.centrocusto ";
        $sql .= "inner join amb_ddm s on ((s.alias='EST_MENU') and (s.campo='SituacaoNota') and (s.tipo = n.situacao)) ";
        $sql .= "inner join amb_ddm t on ((t.alias='EST_MENU') and (t.campo='TipoNotaFiscal') and (t.tipo = n.tipo)) ";

        if ($par[5] != "") {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[5]) ? '' : " $cond (N.NUMERO = '" . $par[5] . "') ";
        } else {
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[0]) ? '' : " $cond (N.CENTROCUSTO = " . $par[0] . ") ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= $par[1] == '' ? '' : " $cond (N.TIPO= " . $par[1] . ") ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[2]) ? '' : " $cond (N.SITUACAO= '" . $par[2] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[3]) ? '' : " $cond (N.EMISSAO >= '" . $dataIni . " 00:00:00') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[4]) ? '' : " $cond (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[6]) ? '' : " $cond (N.serie = '" . $par[6] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[13]) ? '' : " $cond (N.modelo = '" . $par[13] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[7]) ? '' : " $cond (N.PESSOA = '" . $par[7] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[8]) ? '' : " $cond (N.IDNATOP = '" . $par[8] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[9]) ? '' : " $cond (N.FINALIDADEEMISSAO = '" . $par[9] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[10]) ? '' : " $cond (N.MODFRETE = '" . $par[10] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[11]) ? '' : " $cond (N.GENERO = '" . $par[11] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[12]) ? '' : " $cond (N.TRANSPORTADOR = '" . $par[12] . "') ";

            if ($joinNfProduto == true) {
                $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
                $sql .= empty($par[13]) ? '' : " $cond (N.ORIGEM = '" . $par[13] . "') ";

                $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
                $sql .= empty($par[14]) ? '' : " $cond (P.CODPRODUTO = '" . $par[14] . "') ";
            }
        }

        $sql .= "ORDER BY n.centrocusto, n.serie, n.numero ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    // fim select_nota_fiscal_letra
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function selectNfRemessaiqvia($letra, $tipo = null)
    {

        $par = explode("|", $letra);
        $where = '';
        $arrData = explode("-", $par[0]);
        $dataIni = c_date::convertDateTxt($arrData[0]);
        $dataFim = c_date::convertDateTxt($arrData[1]);
        $iswhere = false;
        switch ($tipo) {
            case 'C': //cliente
                $sql = "SELECT distinct ";
                $sql .= "N.PESSOA, N.NATOPERACAO, ";
                $sql .= "C.CLIENTE, C.CNPJCPF, C.NOMEREDUZIDO, C.NOME, C.ENDERECO, C.COMPLEMENTO, C.CEP, C.CIDADE, C.UF, C.DATEINSERT, C.FONE, C.EMAIL, C.HOMEPAGE, C.DATEINSERT ";
                $sql .= "FROM EST_NOTA_FISCAL N ";
                $sql .= "inner join FIN_CLIENTE C on (C.CLIENTE=N.PESSOA) and (C.PESSOA='J') ";
                $orderby = "ORDER BY C.NOME ";
                break;
            case 'P': //produto
                $sql = "SELECT distinct ";
                $sql .= "T.NOME as NOMEFABRICANTE, G.DESCRICAO as DESCGRUPO, ";
                $sql .= "P.CODIGO, P.CODIGOBARRAS, P.DESCRICAO, P.CODFABRICANTE, P.CUSTOCOMPRA, P.GRUPO, P.DATEINSERT ";
                $sql .= "FROM EST_PRODUTO P  ";
                $sql .= "inner join EST_NOTA_FISCAL_PRODUTO F on (P.CODIGO=F.CODPRODUTO) ";
                $sql .= "inner join EST_NOTA_FISCAL N on (N.ID=F.IDNF)  ";
                $sql .= "left join FIN_CLIENTE T on (T.CLIENTE=P.FABRICANTE) ";
                $sql .= "inner join EST_GRUPO G on (P.GRUPO=G.GRUPO) ";
                $orderby = "group by p.codigo ORDER BY p.codigo ";
                break;
            case 'V':  //venda
                $sql = "SELECT ";
                $sql .= "N.EMISSAO, N.PESSOA, F.CODPRODUTO, N.NATOPERACAO, F.QUANT ";
                $sql .= "FROM EST_NOTA_FISCAL N ";
                $sql .= "inner join EST_NOTA_FISCAL_PRODUTO F on (N.ID = F.IDNF) ";
                $sql .= "inner join EST_PRODUTO P on (p.codigo = F.CODPRODUTO) ";
                $sql .= "inner join FIN_CLIENTE C on (C.CLIENTE=N.PESSOA) and (C.PESSOA='J') ";
                $orderby = "ORDER BY  N.ID ";
                break;
            case 'E': //estoque
                $sql = "SELECT ";
                $sql .= "p.descricao, P.CODIGOBARRAS, P.UNIDADE, E.STATUS, COUNT(E.CODPRODUTO) as quant from EST_PRODUTO_ESTOQUE e ";
                $sql .= "inner join EST_PRODUTO P on (p.codigo = e.CODPRODUTO) ";
                $sql .= "WHERE (E.STATUS = 0 OR E.STATUS =1) and (P.FABRICANTE >0) AND (P.CODIGOBARRAS <>'')";
                $where = "group by E.CODPRODUTO, E.STATUS ";
                $orderby = "ORDER BY  E.CODPRODUTO ";
                break;
        }

        if ($where == ''):

            if ((!$iswhere) and ($tipo <> 'C')) {
                $where = "WHERE (P.FABRICANTE >0) AND (P.CODIGOBARRAS <>'') ";
                $iswhere = true;
            }


            if ($tipo == 'V') { // venda
                if ($iswhere) {
                    $where .= "and (N.TIPO = '1') and (N.PESSOA<>151) and (N.PESSOA<>1)";
                } else {
                    $iswhere = true;
                    $where .= "where (N.TIPO = '1') and (N.PESSOA<>151) and (N.PESSOA<>1) ";
                }
            }

            if ($par[0] != '') { // PERIODO INICIAL DE PESQUISA
                if ($iswhere) {
                    $where .= "and (N.EMISSAO >= '" . $dataIni . "') ";
                } else {
                    $iswhere = true;
                    $where .= "where (N.EMISSAO >= '" . $dataIni . "') ";
                }
            }

            if ($par[1] != '') { // PERIODO FINAL DE PESQUISA
                if ($iswhere) {
                    $where .= "AND (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";
                } else {
                    $iswhere = true;
                    $where .= "WHERE (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";
                }
            }

            if (($par[2] != '') and ($par[0] != '0')) { // FILIAL
                if ($iswhere) {
                    $where .= "AND (N.CENTROCUSTO= " . $par[2] . ") ";
                } else {
                    $iswhere = true;
                    $where .= "WHERE (N.CENTROCUSTO = " . $par[2] . ") ";
                }
            } // FIM FILIAL
        endif;


        $sql .= $where;
        $sql .= $orderby;
        // echo strtoupper($sql);

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    // fim selectNfRemessaiqvia

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function salvaJustificativa($msg, $modelo, $serie, $numIni, $numFim, $just)
    {

        // insere nf inutilizada
        $this->setModelo($modelo);
        $this->setSerie($serie);
        $this->setCentroCusto($this->m_empresacentrocusto);
        //$numNf = $this->geraNumNf($this->getModelo(), $this->getSerie(), $this->getCentroCusto());
        $numNf = $numIni;
        $this->setNumero($numNf);
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $this->setPessoa($parametros->getField(
            "CLIENTEPADRAO",
            "(FILIAL = " . $this->m_empresacentrocusto . ") AND (MODELO=" . $modelo . ")"
        ));
        $this->setCpfNota('');
        $this->setEmissao(date("Y/m/d H:i"));
        $this->setIdNatop($parametros->getField(
            "NATOPERACAO",
            "(FILIAL = " . $this->m_empresacentrocusto . ") AND (MODELO=" . $modelo . ")"
        ));
        $this->setNatOperacao('INUTILIZA');
        $this->setTipo('1');
        $this->setSituacao('I');
        $this->setFormaPgto('0');
        $this->setCondPgto('0');
        $this->setDataSaidaEntrada(date("Y/m/d H:i"));
        $this->setFormaEmissao('N');
        $this->setFinalidadeEmissao('1');
        $this->setGenero($parametros->getField(
            "GENERO",
            "(FILIAL = " . $this->m_empresacentrocusto . ") AND (MODELO=" . $modelo . ")"
        ));
        $this->setModFrete('0');
        $this->setTransportador(0);
        $this->setVolume(0);
        $this->setVolEspecie('');
        $this->setVolMarca('');
        $this->setVolPesoLiq(0);
        $this->setVolPesoBruto(0);
        $this->setTotalnf('0');
        $this->setOrigem('NFE');
        $this->setDoc(0);
        $this->setObs("Inutilização NF Modelo: " . $modelo . ", Serie: " . $serie . ", Número Inicio: " . $numIni . " - Número Fim: " . $numFim . ", Justificativa: " . $just . ", Protocolo: " . $msg->infInut->nProt);
        $this->setFrete('0');
        $this->setDespAcessorias('0');
        $this->setSeguro('0');
        $this->setContrato('');
        $arrNf = $this->existeNotaFiscalEmp($modelo, $serie, $numNf, $this->m_empresacentrocusto, true);
        if (is_array($arrNf)):
            $this->setId($arrNf[0][ID]);
            $this->alteraSituacao('I');
        else:
            $idNf = $this->incluiNotaFiscal();
        endif;
        $sql = "INSERT INTO est_nota_fiscal_eventos (";
        $sql .= "IDNF, TIPOEVENTO, CENTROCUSTO, MODELO, SERIE, NUMNFINI, NUMNFFIM, JUSTIFICATIVA, NPROT, VERAPLIC, CSTAT, USERINSERT, DATEINSERT) ";

        $sql .= "VALUES (" . $idNf . ",'I' , ";
        $sql .= $this->m_empresacentrocusto . ", '";
        $sql .= $modelo . "', '";
        $sql .= $serie . "', ";
        $sql .= $numIni . ", ";
        $sql .= $numFim . ", '";
        $sql .= $just . "', '";
        $sql .= $msg->infInut->nProt . "', '";
        $sql .= $msg->infInut->verAplic . "', '";
        $sql .= $msg->infInut->cStat . "', ";
        $sql .= $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";


        // echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_nf = $banco->exec_sql($sql);

        if ($banco->result):
            $banco->close_connection();
            return 'Inutilização realizada com sucesso';
        else:
            $banco->close_connection();
            return 'Os dados de Inutilização  n&atilde;o foram cadastrados!';
        endif;

        //$this->alteraSituacao('I'); // altera situação da nfe

    }


    /**
     * Funcao para gerar o numero da sequencia do numero do evento da NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name geraNumEvento
     * @return NULL quando ok ou msg erro
     */
    public function geraNumEvento($modelo, $serie, $numero, $cc, $tipo)
    {
        $numEvento = 0;
        $numAtArray = null;

        $sql = "SELECT MAX(SEQUENCIA) AS ULTIMOEVENTO FROM EST_NOTA_FISCAL_EVENTOS WHERE (CENTROCUSTO=" . $cc . ") ";
        $sql .= "AND (MODELO=" . $modelo . ") AND (SERIE=" . $serie . ") AND (NUMNFINI=" . $numero . ") and (TIPOEVENTO='" . $tipo . "')";
        //ECHO $sql;

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

        if ($banco->result):
            $numAtArray = $banco->resultado;
            $numEvento = intval($numAtArray[0]['ULTIMOEVENTO']);
        endif;
        $numEvento = $numEvento + 1;
        return $numEvento;
    }


    /**
     * Funcao para alterar dados autorização NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name incluiNfEvento
     * @return NULL quando ok ou msg erro
     */
    public function incluiNfEvento($msg, $tipo, $sequencia, $numIni, $numFim, $justificativa, $param = null, $conn = null)
    {

        $sql = "INSERT INTO est_nota_fiscal_eventos (";
        $sql .= "IDNF, ";
        $sql .= "CENTROCUSTO, ";
        $sql .= "TIPOEVENTO, ";
        $sql .= "SEQUENCIA, ";
        $sql .= "MODELO, ";
        $sql .= "SERIE, ";
        $sql .= "NUMNFINI, ";
        $sql .= "NUMNFFIM, ";
        $sql .= "JUSTIFICATIVA, ";
        $sql .= "nProt, ";
        $sql .= "verAplic, ";
        $sql .= "cSTAT, ";
        $sql .= "XML, ";
        $sql .= "USERINSERT, ";
        $sql .= "DATEINSERT)  value ( '";

        if ($param == 'recibo') {
            $sql .= $msg["ID"] . "', '";
            $sql .= $msg["CENTROCUSTO"] . "', '";
            $sql .= $msg["TIPOEVENTO"] . "', '";
            $sql .= $msg["SEQUENCIA"] . "', '";
            $sql .= $msg["MODELO"] . "', '";
            $sql .= $msg["SERIE"] . "', '";
            $sql .= $msg["NUMNFINI"] . "', '";
            $sql .= $msg["NUMNFFIM"] . "', '";
            $sql .= $msg["MOTIVO"] . "', '";
            $sql .= "consulta recibo', '";
            $sql .= $msg["VERAPLIC"] . "', '";
            $sql .= $msg["CSTAT"] . "', '";
            $sql .= mysqli_real_escape_string($conn, $msg["XML"]) . "', ";
            $sql .= $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";
        } else {
            $sql .= $this->getId() . "', '";
            $sql .= $this->getCentroCusto() . "', '";
            $sql .= $tipo . "', '";
            $sql .= $sequencia . "', '";
            $sql .= $this->getModelo() . "', '";
            $sql .= $this->getSerie() . "', '";
            $sql .= $numIni . "', '";
            $sql .= $numFim . "', '";
            $sql .= $justificativa . "', '";
            $sql .= $msg->retEvento->infEvento->nProt . "', '";
            $sql .= $msg->retEvento->infEvento->verAplic . "', '";
            $sql .= $msg->retEvento->infEvento->cStat . "', ";

            //$sql .= $msg->retEvento->infEvento->cStat."', "; XML

            $sql .= $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";
        }

        $banco = new c_banco;
        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $res_nf = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        if ($res_nf > 0) {
            return '';
        } else {
            return ' Evento  NFe: ' . $numIni . ' n&atilde;o foi alterado!';
        }
    }
    // fim incluiNFEvento

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function incluiNotaFiscal($conn = null)
    {
        /* INCLUSAO DE COLUNA PARA EMISSAO DE NF PRESENCIAL
        ALTER TABLE `admsis_requemaq`.`EST_NOTA_FISCAL` 
        ADD COLUMN `VENDAPRESENCIAL` CHAR(1) NULL DEFAULT 'N' AFTER `DESCONTOGERAL`*/
        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("EST_GEN_ID_NF"));
            $sql = "INSERT INTO EST_NOTA_FISCAL (ID,";
        } else {
            $sql = "INSERT INTO EST_NOTA_FISCAL (";
        }

        $sql .= "MODELO, SERIE, NUMERO, PESSOA, CPFNOTA, EMISSAO, IDNATOP, NATOPERACAO, TIPO, SITUACAO, FORMAPGTO, CONDPGTO, "
            . "DATASAIDAENTRADA, FORMAEMISSAO, FINALIDADEEMISSAO, NFEREFERENCIADA, CENTROCUSTO, GENERO, "
            . "MODFRETE, TRANSPORTADOR, PLACAVEICULO, CODANTT, UF, VOLUME, VOLESPECIE, VOLMARCA, VOLPESOLIQ, VOLPESOBRUTO, "
            . "TOTALNF, ORIGEM, DOC, OBS, FRETE, DESPACESSORIAS, SEGURO, DHRECBTO, NPROT, DIGVAL, VERAPLIC, VENDAPRESENCIAL, CONTRATO, USERINSERT, DATEINSERT) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .= $this->getModelo() . "', '";
        $sql .= $this->getSerie() . "', ";
        if ($this->getNumero() == '') {
            $sql .= "0, '";
        } else {
            $sql .= $this->getNumero() . ", '";
        }
        $sql .= $this->getPessoa() . "', '";
        $sql .= $this->getCpfNota() . "', '";
        $sql .= $this->getEmissao('B') . "', '";
        $sql .= $this->getIdNatop() . "', '";
        $sql .= $this->getNatOperacao() . "', '";
        $sql .= $this->getTipo() . "', '";
        $sql .= $this->getSituacao() . "', '";
        $sql .= $this->getFormaPgto() . "', ";
        $sql .= $this->getCondPgto() . ", '";
        $sql .= $this->getDataSaidaEntrada('B') . "', '";
        $sql .= $this->getFormaEmissao() . "', '";
        $sql .= $this->getFinalidadeEmissao() . "', '";
        $sql .= $this->getNfeReferenciada() . "', '";
        $sql .= $this->getCentroCusto() . "', '";
        $sql .= $this->getGenero() . "', '";
        $sql .= $this->getModFrete() . "', ";
        if ($this->getTransportador() == '') {
            $sql .= "null, '";
        } else {
            $sql .= $this->getTransportador() . ", '";
        }
        $sql .= $this->getPlacaVeiculo() . "', '";
        $sql .= $this->getCodAntt() . "', '";
        $sql .= $this->getUf() . "', ";
        $sql .= $this->getVolume() . ", '";
        $sql .= $this->getVolEspecie() . "', '";
        $sql .= $this->getVolMarca() . "', ";
        $sql .= $this->getVolPesoLiq() . ", ";
        $sql .= $this->getVolPesoBruto() . ", ";
        $sql .= $this->getTotalnf('B') . ", '";
        $sql .= $this->getOrigem() . "', '";
        $sql .= $this->getDoc() . "', '";
        $sql .= $this->getObs() . "', '";
        $sql .= $this->getFrete('B') . "', '";
        $sql .= $this->getDespAcessorias('B') . "', '";
        $sql .= $this->getSeguro('B') . "', '";
        $sql .= $this->getDhRecbto() . "', '";
        $sql .= $this->getNProt() . "', '";
        $sql .= $this->getDigVal() . "', '";
        $sql .= $this->getVerAplic() . "', '";
        $sql .= $this->getVendaPresencial() . "', '";

        $sql .= $this->getContrato() . "'," . $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";


        if (!isset($conn)) {
            $conn = $banco->id_connection;
        }

        //echo strtoupper($sql)."<BR>";
        $res_nf = $banco->exec_sql($sql, $conn);

        if ($banco->result) {
            $lastReg = mysqli_insert_id($conn);
            $banco->close_connection();
            return $lastReg;
        } else {
            $banco->close_connection();
            return 'Os dados da Nota Fiscal ' . $this->getNumero() . ' n&atilde;o foram cadastrados!';
        }
    }

    public function incluiNotaFiscalManisfesto($conn = null)
    {
        /* INCLUSAO DE COLUNA PARA EMISSAO DE NF PRESENCIAL
        ALTER TABLE `admsis_requemaq`.`EST_NOTA_FISCAL` 
        ADD COLUMN `VENDAPRESENCIAL` CHAR(1) NULL DEFAULT 'N' AFTER `DESCONTOGERAL`*/
        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("EST_GEN_ID_NF"));
            $sql = "INSERT INTO EST_NOTA_FISCAL (ID,";
        } else {
            $sql = "INSERT INTO EST_NOTA_FISCAL (";
        }

        $sql .= "MODELO, SERIE, NUMERO, PESSOA, CPFNOTA, EMISSAO, IDNATOP, NATOPERACAO, TIPO, SITUACAO, FORMAPGTO, CONDPGTO, "
            . "DATASAIDAENTRADA, FORMAEMISSAO, FINALIDADEEMISSAO, NFEREFERENCIADA, CENTROCUSTO, GENERO, "
            . "MODFRETE, TRANSPORTADOR, PLACAVEICULO, CODANTT, UF, VOLUME, VOLESPECIE, VOLMARCA, VOLPESOLIQ, VOLPESOBRUTO, "
            . "TOTALNF, ORIGEM, DOC, OBS, FRETE, DESPACESSORIAS, SEGURO, DHRECBTO, NPROT, DIGVAL, VERAPLIC, VENDAPRESENCIAL, 
                    CHNFE, CONTRATO, USERINSERT, DATEINSERT) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .= $this->getModelo() . "', '";
        $sql .= $this->getSerie() . "', ";
        if ($this->getNumero() == '') {
            $sql .= "0, '";
        } else {
            $sql .= $this->getNumero() . ", '";
        }
        $sql .= $this->getPessoa() . "', '";
        $sql .= $this->getCpfNota() . "', '";
        $sql .= $this->getEmissao('B') . "', '";
        $sql .= $this->getIdNatop() . "', '";
        $sql .= $this->getNatOperacao() . "', '";
        $sql .= $this->getTipo() . "', '";
        $sql .= $this->getSituacao() . "', '";
        $sql .= $this->getFormaPgto() . "', ";
        $sql .= $this->getCondPgto() . ", '";
        $sql .= $this->getDataSaidaEntrada('B') . "', '";
        $sql .= $this->getFormaEmissao() . "', '";
        $sql .= $this->getFinalidadeEmissao() . "', '";
        $sql .= $this->getNfeReferenciada() . "', '";
        $sql .= $this->getCentroCusto() . "', '";
        $sql .= $this->getGenero() . "', '";
        $sql .= $this->getModFrete() . "', ";
        if ($this->getTransportador() == '') {
            $sql .= "null, '";
        } else {
            $sql .= $this->getTransportador() . ", '";
        }
        $sql .= $this->getPlacaVeiculo() . "', '";
        $sql .= $this->getCodAntt() . "', '";
        $sql .= $this->getUf() . "', ";
        $sql .= $this->getVolume() . ", '";
        $sql .= $this->getVolEspecie() . "', '";
        $sql .= $this->getVolMarca() . "', ";
        $sql .= $this->getVolPesoLiq() . ", ";
        $sql .= $this->getVolPesoBruto() . ", ";
        $sql .= $this->getTotalnf('B') . ", '";
        $sql .= $this->getOrigem() . "', '";
        $sql .= $this->getDoc() . "', '";
        $sql .= $this->getObs() . "', '";
        $sql .= $this->getFrete('B') . "', '";
        $sql .= $this->getDespAcessorias('B') . "', '";
        $sql .= $this->getSeguro('B') . "', '";
        $sql .= $this->getDhRecbto() . "', '";
        $sql .= $this->getNProt() . "', '";
        $sql .= $this->getDigVal() . "', '";
        $sql .= $this->getVerAplic() . "', '";
        $sql .= $this->getVendaPresencial() . "', '";
        $sql .= $this->getChNFe() . "', '";

        $sql .= $this->getContrato() . "'," . $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";


        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        //echo strtoupper($sql)."<BR>";
        $res_nf = $banco->exec_sql($sql, $conn);

        if ($banco->result):
            $lastReg = mysqli_insert_id($conn);
            $banco->close_connection();
            return $lastReg;
        else:
            $banco->close_connection();
            return 'Os dados da Nota Fiscal ' . $this->getNumero() . ' n&atilde;o foram cadastrados!';
        endif;
    }




    /**
     * Funcao para alterar Numero da NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name alteraNfNumero
     * @return NULL quando ok ou msg erro
     */
    public function alteraNfNumero($conn = null, $recibo = null, $chave = null, $situacaoNf = null)
    {

        $sql = "UPDATE est_nota_fiscal SET ";
        $sql .= "numero = " . $this->getNumero() . ", ";
        //testa se recibo foi enviado como parametro
        if ($recibo !== null) {
            $sql .= "numrecibo = '" . $recibo . "', ";
        }
        if ($chave !== null) {
            $sql .= "chnfe = '" . $chave . "', ";
        }
        if ($situacaoNf !== null) {
            $sql .= "situacao = '" . $situacaoNf . "', ";
        }
        $sql .= "datechange = current_timestamp(), ";
        $sql .= "userchange = " . $this->m_userid . " ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;


        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $res_nf = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        if ($res_nf > 0) {
            return '';
        } else {
            return 'Número NF ' . $this->getNumero() . ' n&atilde;o foi alterado!';
        }
    }
    // fim alteraNFNumero

    /**
     * Funcao para alterar dados autorização NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name alteraNfPath
     * @return NULL true ou mensagem contendo o erro
     */
    public function alteraNfPath($conn = null)
    {

        $sql = "UPDATE est_nota_fiscal SET ";
        $sql .= "pathdanfe = '" . $this->getPathDanfe() . "', ";
        $sql .= "chNFE = '" . $this->getChNfe() . "', ";
        $sql .= "dhRecbto = '" . $this->getDhRecbto() . "', ";
        $sql .= "nProt = '" . $this->getNProt() . "', ";
        $sql .= "digVal = '" . $this->getDigVal() . "', ";
        $sql .= "verAplic = '" . $this->getVerAplic() . "', ";
        $sql .= "situacao = '" . $this->getSituacao() . "' ,";
        $sql .= "userchange = '" . $this->m_userid . "', ";
        $sql .= "datechange = current_timestamp() ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;

        if (!isset($conn)) {
            $conn = $banco->id_connection;
        }

        $banco->exec_sql($sql, $conn);
        return $banco->resultado;
    }
    // fim alteraNFPath


    /**
     * Funcao para alterar da situação da NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name alteraSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraSituacao($situacao, $justificativa = null, $conn = null)
    {

        $sql = "UPDATE est_nota_fiscal SET ";
        $sql .= "situacao = '" . $situacao . "', ";
        $sql .= "userchange = '" . $this->m_userid . "', ";
        $sql .= "datechange = current_timestamp() ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;


        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $res_nf = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        if ($res_nf > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Nota Fiscal ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    // fim alteraSituacao

    /**
     * Funcao para estornar a NF limpando os campos DOC e ORIGEM da tabela de EST_NOTA_FISCAL 
     * @name estornaNf
     * @return NULL quando ok ou msg erro
     */
    public function estornaNf()
    {

        $sql = "UPDATE est_nota_fiscal SET ";
        $sql .= "doc = NULL, ";
        $sql .= "origem = NULL ,";
        $sql .= "userchange = '" . $this->m_userid . "', ";
        $sql .= "datechange = current_timestamp() ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;


        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $res_nf = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        if ($res_nf > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Nota Fiscal ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    // fim alteraSituacao

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function alteraNotaFiscal()
    {

        $sql = "UPDATE EST_NOTA_FISCAL ";
        $sql .= "SET ";
        $sql .= "modelo = '" . $this->getModelo() . "', ";
        $sql .= "serie = '" . $this->getSerie() . "', ";
        $sql .= "numero = " . $this->getNumero() . ", ";
        $sql .= "pessoa = " . $this->getPessoa() . ", ";
        $sql .= "cpfnota = '" . $this->getCpfNota() . "', ";
        $sql .= "emissao = '" . $this->getEmissao('B') . "', ";
        $sql .= "IDNATOP = " . $this->getIdNatop() . ", ";
        $sql .= "NatOperacao = '" . $this->getNatOperacao() . "', ";
        $sql .= "tipo = '" . $this->getTipo() . "', ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "formaPgto = " . $this->getFormaPgto() . ", ";
        $sql .= "condPgto = " . $this->getCondPgto() . ", ";
        $sql .= "dataSaidaEntrada = '" . $this->getDataSaidaEntrada('B') . "', ";
        $sql .= "formaEmissao = '" . $this->getFormaEmissao() . "', ";
        $sql .= "finalidadeEmissao = '" . $this->getFinalidadeEmissao() . "', ";
        $sql .= "NfeReferenciada = '" . $this->getNfeReferenciada() . "', ";
        $sql .= "centroCusto = " . $this->getCentroCusto() . ", ";
        $sql .= "genero = '" . $this->getGenero() . "', ";
        $sql .= "modFrete = '" . $this->getModFrete() . "', ";
        if ($this->getTransportador() != '') {
            $sql .= "transportador = " . $this->getTransportador() . ", ";
        } else {
            $sql .= "transportador = null, ";
        }
        $sql .= "placaveiculo = '" . $this->getPlacaVeiculo() . "', ";
        $sql .= "codantt = '" . $this->getCodAntt() . "', ";
        $sql .= "uf = '" . $this->getUf() . "', ";
        $sql .= "volume = " . $this->getVolume() . ", ";
        $sql .= "volEspecie = '" . $this->getVolEspecie() . "', ";
        $sql .= "volMarca = '" . $this->getVolMarca() . "', ";
        $sql .= "volPesoLiq = " . $this->getVolPesoLiq() . ", ";
        $sql .= "volPesoBruto = " . $this->getVolPesoBruto() . ", ";
        $sql .= "totalnf = " . $this->getTotalnf('B') . ", ";
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "frete = '" . $this->getFrete('B') . "', ";
        $sql .= "despacessorias = '" . $this->getDespAcessorias('B') . "', ";
        $sql .= "seguro = '" . $this->getSeguro('B') . "', ";
        $sql .= "nProt = '" . $this->getNProt() . "', ";
        $sql .= "dhRecbto = '" . $this->getDhRecbto() . "', ";
        $sql .= "digVal = '" . $this->getDigVal() . "', ";
        $sql .= "verAplic = '" . $this->getVerAplic() . "', ";
        $sql .= "origem = '" . $this->getOrigem() . "', ";
        $sql .= "doc = " . $this->getDoc() . ", ";
        $sql .= "contrato = '" . $this->getContrato() . "', ";
        $sql .= "userchange = " . $this->m_userid . ", ";
        $sql .= "datechange = '" . date("Y-m-d H:i:s") . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        // echo strtoupper($sql);
        $res_nf = $banco->exec_sql($sql);
        $banco->close_connection();

        if (!$banco->result) {
            return false;
        } else {
            return 'Os dados da Nota Fiscal ' . $this->getNumero() . ' n&atilde;o foram alterados!';
        }
    }

    // fim alteraNotaFiscal
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function excluiNotaFiscal()
    {

        $sql = "DELETE FROM est_nota_fiscal ";
        $sql .= "WHERE id = " . $this->getId();
        $banco = new c_banco;
        $res_nf = $banco->exec_sql($sql);
        $banco->close_connection();
        // echo strtoupper($sql);
        if ($res_nf > 0) {
            return '';
        } else {
            return 'Os dados da Nota Fiscal ' . $this->getNumero() . ' n&atilde;o foram excluidos!';
        }
    }

    // fim excluiNotaFiscal
    public function selectNfEvento($id)
    {

        $sql = "Select * from est_nota_fiscal_eventos where IDNF = " . $id;
        $banco = new c_banco;
        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $res_nf = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        if ($res_nf > 0) {
            return $res_nf;
        } else {
            return ' Evento  NFe: não encontrado!';
        }
    }

    function select_vendas($par)
    {

        $dataIni = c_date::convertDateTxt($par[3]);
        $dataFim = c_date::convertDateTxt($par[4]);

        $sql  = "SELECT N.*, C.NOME AS NOMECLIENTE ";
        $sql .= "FROM EST_NOTA_FISCAL N ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=N.PESSOA) ";
        $sql .= "where (N.CENTROCUSTO = '" . $par[0] . "') and ";
        $sql .= " ((N.EMISSAO >= '" . $dataIni . "') and (N.EMISSAO <= '" . $dataFim . " 23:59:59')) and ";
        $sql .= "(N.SITUACAO = '" . $par[2] . "') and (N.TIPO = '" . $par[1] . "') and (N.IDNATOP<>14)";
        if ($par[6] != '') {
            $sql .= " AND (N.SERIE = '" . $par[6] . "') ";
        }

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[8]) ? '' : " $cond (N.IDNATOP = '" . $par[8] . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[7]) ? '' : " $cond (N.PESSOA = '" . $par[7] . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[9]) ? '' : " $cond (N.FINALIDADEEMISSAO = '" . $par[9] . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[10]) ? '' : " $cond (N.MODFRETE = '" . $par[10] . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[11]) ? '' : " $cond (N.GENERO = '" . $par[11] . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($par[12]) ? '' : " $cond (N.TRANSPORTADOR = '" . $par[12] . "') ";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function incluiNotaFiscalOC($conn = null)
    {

        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("EST_GEN_ID_NF"));
            $sql = "INSERT INTO est_nota_fiscal (ID,";
        } else {
            $sql = "INSERT INTO est_nota_fiscal (";
        }

        $sql .= "MODELO, SERIE, NUMERO, PESSOA, CPFNOTA, EMISSAO, IDNATOP, NATOPERACAO, TIPO, SITUACAO, FORMAPGTO, CONDPGTO, "
            . "DATASAIDAENTRADA, FORMAEMISSAO, FINALIDADEEMISSAO, NFEREFERENCIADA, CENTROCUSTO, GENERO, "
            . "MODFRETE, TRANSPORTADOR, PLACAVEICULO, CODANTT, UF, VOLUME, VOLESPECIE, VOLMARCA, VOLPESOLIQ, VOLPESOBRUTO, "
            . "TOTALNF, ORIGEM, DOC, OBS, FRETE, DESPACESSORIAS, SEGURO, DESCONTOGERAL, DATACONFERENCIA, CONTRATO, USERINSERT, DATEINSERT) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .= $this->getModelo() . "', '";
        $sql .= $this->getSerie() . "', ";
        $sql .= $this->getNumero() . ", '";
        $sql .= $this->getPessoa() . "', '";
        $sql .= $this->getCpfNota() . "', '";
        $sql .= $this->getEmissao('B') . "', ";
        $sql .= $this->getIdNatop() . ", '";
        $sql .= $this->getNatOperacao() . "', '";
        $sql .= $this->getTipo() . "', '";
        $sql .= $this->getSituacao() . "', '";
        $sql .= $this->getFormaPgto() . "', ";
        $sql .= $this->getCondPgto() . ", '";
        $sql .= $this->getDataSaidaEntrada('B') . "', '";
        $sql .= $this->getFormaEmissao() . "', '";
        $sql .= $this->getFinalidadeEmissao() . "', '";
        $sql .= $this->getNfeReferenciada() . "', ";
        $sql .= $this->getCentroCusto() . ", '";
        $sql .= $this->getGenero() . "', '";
        $sql .= $this->getModFrete() . "', ";
        $sql .= $this->getTransportador() . ", '";
        $sql .= $this->getPlacaVeiculo() . "', '";
        $sql .= $this->getCodAntt() . "', '";
        $sql .= $this->getUf() . "', ";
        $sql .= $this->getVolume() . ", '";
        $sql .= $this->getVolEspecie() . "', '";
        $sql .= $this->getVolMarca() . "', ";
        $sql .= $this->getVolPesoLiq() . ", ";
        $sql .= $this->getVolPesoBruto() . ", ";
        $sql .= $this->getTotalnf('B') . ", '";
        $sql .= $this->getOrigem() . "', ";
        $sql .= $this->getDoc() . ", '";
        $sql .= $this->getObs() . "', '";
        $sql .= $this->getFrete() . "', '";
        $sql .= $this->getDespAcessorias() . "', '";
        $sql .= $this->getSeguro() . "', '";
        $sql .= $this->getDescontoGeral() . "', '";
        $sql .= $this->getDataConferencia('B') . "', '";
        $sql .= $this->getContrato() . "'," . $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";


        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        // echo strtoupper($sql)."<BR>";
        $res_nf = $banco->exec_sql($sql, $conn);

        if ($banco->result):
            $lastReg = mysqli_insert_id($conn);
            $banco->close_connection();
            return $lastReg;
        else:
            $banco->close_connection();
            return 'Os dados da Nota Fiscal ' . $this->getNumero() . ' n&atilde;o foram cadastrados!';
        endif;
    }

    /**
     * @name     alteraMovEstoqueSaida
     * @param   INT id
     * @param   FLOAT QUANT
     * @param   STRING obs
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraMovEstoqueSaida($id, $quant)
    {
        $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO ";
        $sql .= "SET  ";
        $sql .= "QUANT = '" . $quant . "' ";
        $sql .= "WHERE (IDNF = '" . $id . "');";
        // echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        } //if
    }

    /**
     * <b> É responsavel para calcular os impostos dos itens da nota </b>
     * @name calculoRateios
     * @param vazio
     * @return atualiza os totais de valores adicionais
     */
    function calculaRateios()
    {

        if ($this->getId() > 0) {

            $idNf = $this->getId();
            $objNfProduto = new c_nota_fiscal_produto();

            $totalNF = $objNfProduto->selectTotalNfProduto($idNf);
            $descontoNF = $objNfProduto->selectTotalDescProd($idNf);
            $total = $totalNF;
            $despAcessorias = $this->getDespAcessorias('B');
            $frete = $this->getFrete('B');

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
                if (
                    $arrItemPedido[$i]['TOTAL'] !=
                    ($arrItemPedido[$i]['QUANT'] * $arrItemPedido[$i]['UNITARIO'])
                ) {
                    $sqlTotal = ", TOTAL = " . ($arrItemPedido[$i]['QUANT'] * $arrItemPedido[$i]['UNITARIO']);
                }

                $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANT'] * $arrItemPedido[$i]['UNITARIO'];
                $totalNF += $arrItemPedido[$i]['TOTAL'];

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
                        $sqlFields .= ' despAcessorias = ' . $vlrDespAcessorias;
                    } else {
                        $sqlFields .= ' despAcessorias = ' . $vlrDespAcessorias;
                    }
                } else {
                    $sqlFields .= ' despAcessorias = 0 ';
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

                if ($sqlTotal != "") {
                    $sqlFields = $sqlFields . $sqlTotal;
                }

                $banco = new c_banco;
                $sql = 'UPDATE EST_NOTA_FISCAL_PRODUTO SET ' . $sqlFields . " WHERE ID = " . $arrItemPedido[$i]['ID'] . " and CODPRODUTO = " . $arrItemPedido[$i]['CODPRODUTO'];
                //echo strtoupper($sql) . "<BR>";
                $banco->exec_sql($sql);
                $banco->close_connection();
            } //for
        }
    } // Fim calculaRateios

    public function selectNotaSequencia($data = null, $centroCusto = null, $serie = null, $modelo = null, $tipo = null)
    {
        $data = c_date::convertDateTxt($data);

        //SELECT MAX(NUMERO) FROM EST_NOTA_FISCAL WHERE EMISSAO < '2022-11-01 00:00:00' AND MODELO = 55 AND TIPO = 1 AND SERIE = 1;

        $sql = "SELECT MAX(NUMERO) AS NUMSEQ FROM EST_NOTA_FISCAL N ";
        $sql .= "where N.EMISSAO < ('" . $data . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($serie) ? '' : " $cond (N.SERIE = '" . $serie . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($centroCusto) ? '' : " $cond (N.CENTROCUSTO = '" . $centroCusto . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($modelo) ? '' : " $cond (N.MODELO = '" . $modelo . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($tipo) ? '' : " $cond (N.TIPO = '" . $tipo . "') ";

        //echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado[0]["NUMSEQ"];
    }

    public function buscaEstParametros($centroCusto)
    {
        $sql  = "SELECT * FROM EST_PARAMETRO ";
        $sql .= "WHERE CENTROCUSTO = '" . $centroCusto . "' ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    function removeAcentos($string)
    {
        $conversao = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ï' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            "ö" => "o",
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ï' => 'I',
            "Ö" => "O",
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N'
        );
        return strtr($string, $conversao);
    }
    /**
     * <b> É responsavel por calcular o total do produto </b>
     * @name atualizaTotalNfe
     * @param int $idnf
     * @return total - valor total do produto considerando descontos.
     */
    public function atualizaTotalNfe($idnf = NULL)
    {

        $banco = new c_banco();
        $sql = "SELECT (sum(np.total) + (nf.despacessorias + nf.frete + nf.seguro)) as tot FROM EST_NOTA_FISCAL_PRODUTO np ";
        $sql .= "inner join EST_NOTA_FISCAL nf ON np.idnf = nf.id ";
        $sql .= "where (np.idnf=" . $idnf . ")";
        $banco->exec_sql($sql);
        $arrTotal =  $banco->resultado;
        if ($arrTotal[0]['TOT'] == null) :
            $total = 0;
        else :
            $total = $arrTotal[0]['TOT'];
        endif;

        $sql = "update EST_NOTA_FISCAL set totalnf = " . $total;
        $sql .= " WHERE (id=" . $idnf . ")";
        // echo strtoupper($sql);

        $banco->exec_sql($sql);
        $banco->close_connection();
        return $total;
    }

    public function alteraTotalNf()
    {

        $sql = "UPDATE EST_NOTA_FISCAL SET ";
        $sql .= "frete = '" . $this->getFrete('B') . "', ";
        $sql .= "despacessorias = '" . $this->getDespAcessorias('B') . "', ";
        $sql .= "seguro = '" . $this->getSeguro('B') . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        // echo strtoupper($sql);
        $res_nf = $banco->exec_sql($sql);
        $banco->close_connection();

        if (!$banco->result) {
            return false;
        } else {
            $banco->result;
        }
    }

    //---------------------------------------------------------------
    public function select_xml_nota_fiscal($id = null)
    {
        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal_xml ";
        $sql .= "WHERE (IDNF = " . $id . ") ";

        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    //============================================================  

    public static function select_nota_fiscal_id($id = null)
    {
        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (ID = " . $id . ") ";

        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    //============================================================ 

    /**
     * <b> Is responsible for checking the invoice </b>
     * @name existNotaNumClient
     * @param int $num, $client
     * @return array - array with the record containing all columns
     */
    public function existNotaNumClient($num, $serie, $client)
    {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (NUMERO = '" . $num . "' and SERIE = '" . $serie . "' and PESSOA = " . $client . ") and TIPO = 0;";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao de consulta ao BD para pegar dados da empresa de acordo
     * com o centro de custo logado.
     * @param INT $centrocusto Filial que esta logado
     * @return ARRAY todos os campos da table amb_empresa
     */
    public function select_empresa_centro_custo($centrocusto)
    {
        $sql = "SELECT * ";
        $sql .= "FROM amb_empresa ";
        $sql .= "WHERE (centrocusto = '" . $centrocusto . "') ";
        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao de consultar dados da nota fiscal atraves do ID
     * @param INT $id nota fiscal
     * @return ARRAY todos os campos da table est_nota_fiscal
     */
    public function validaNotaFiscal($id)
    {
        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE id = '" . $id . "'; ";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

        //valida se existe nota fiscal
        if (!is_array($banco->resultado)) {
            $response = array(
                'status' => false,
                'codErro' => 1000,
                'msgErro' => 'Dados da nota fiscal não localizada'
            );
            return $response;
        }

        $arrayNotaFiscal =  $banco->resultado;

        //busca e valida dados do emitente
        $empresa = $this->select_empresa_centro_custo($arrayNotaFiscal[0]["CENTROCUSTO"]);
        if (!is_array($empresa)) {
            $response = array(
                'status' => false,
                'codErro' => 1001,
                'msgErro' => 'Dados do emitente não localizado'
            );
            return $response;
        }

        //busca e valida dados do destinatario
        $destinatario = $this->buscaDadosCliente($arrayNotaFiscal[0]["PESSOA"]);

        //funcao recursiva
        if (!is_array($destinatario)) {
            $response = array(
                'status' => false,
                'codErro' => 1002,
                'msgErro' => 'Dados do destinatário não localizado',
                '_form' => 'nota_fiscal',
                '_mod' => 'est',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $id
            );
            return $response;
        }

        ######################### VALIDA CNPJ/CPF #########################
        if (isset($destinatario[0]["PESSOA"])) {
            if ($destinatario[0]["PESSOA"] == "J") {
                if (strlen($destinatario[0]["CNPJCPF"]) < 14) {
                    $response = array(
                        'status' => false,
                        'codErro' => 1003,
                        'erro' => 'CNPJ inválido',
                        '_form' => 'contas',
                        '_mod' => 'crm',
                        '_submenu' => 'alterar',
                        '_varControle' => 'param',
                        '_id' => $destinatario[0]["CLIENTE"]
                    );
                    return $response;
                } else {
                    //consulta cnpj
                    //$consultaCnpj = $this->consultarCNPJ($destinatario[0]["CNPJCPF"]);
                    $consultaCnpj["situacao"] = "ativa";
                    if ($consultaCnpj["situacao"] !== "ativa" && $consultaCnpj["situacao"] !== "ATIVA") {
                        $response = array(
                            'status' => false,
                            'codErro' => 1004,
                            'erro' => 'CPF inválido na receita',
                            'consulta' => $consultaCnpj,
                            '_form' => 'contas',
                            '_mod' => 'crm',
                            '_submenu' => 'alterar',
                            '_varControle' => 'param',
                            '_id' => $destinatario[0]["CLIENTE"]
                        );
                        return $response;
                    }
                }
            } elseif ($destinatario[0]["PESSOA"] == "F") {
                if (strlen($destinatario[0]["CNPJCPF"]) < 11 || strlen($destinatario[0]["CNPJCPF"]) > 11) {
                    $response = array(
                        'status' => false,
                        'codErro' => 1005,
                        'erro' => 'CPF inválido',
                        '_form' => 'contas',
                        '_mod' => 'crm',
                        '_submenu' => 'alterar',
                        '_varControle' => 'param',
                        '_id' => $destinatario[0]["CLIENTE"]
                    );
                    return $response;
                }
            }
        } ######################### END VALIDA CNPN/CPF #######################

        ######################### VALIDA ENDERECO #####################
        if (!isset($destinatario[0]["CEP"]) || $destinatario[0]["CEP"] == null || $destinatario[0]["CEP"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1006,
                'erro' => 'CEP não localizado',
                '_dica' => 'Assim que informar o CEP mude o foco do campo para realizar a consulta do CEP',
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        }

        ############   FALTA AJUSTAR O CADASTRO PARA CAPTURAR O TIPO NO ALTERAR E NO INSERT DO CADASTRO DE CONTA
        // if(!isset($destinatario[0]["TIPOENDERECO"]) || $destinatario[0]["TIPOENDERECO"] == null || $destinatario[0]["TIPOENDERECO"] == ""){
        //     $response = array(
        //         'status' => false,
        //         'codErro' => 1007,
        //         'erro' => 'Tipo de endereço não informado',
        //         '_dica' => 'Preencher o campo ENDEREÇO iniciando com RUA, AV, ROD, etc...',
        //         '_form' => 'contas',
        //         '_mod' => 'crm',
        //         '_submenu' => 'alterar',
        //         '_varControle' => 'param',
        //         '_id' => $arrayNotaFiscal[0]["PESSOA"]
        //     );
        //     return $response;
        // }

        if (!isset($destinatario[0]["ENDERECO"]) || $destinatario[0]["ENDERECO"] == null || $destinatario[0]["ENDERECO"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1006,
                'erro' => 'Endereço não localizado',
                '_dica' => 'Campo preenchido automaticamente através do CEP, se necessário edite o campo antes de concluir',
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        }

        if (!isset($destinatario[0]["BAIRRO"]) || $destinatario[0]["BAIRRO"] == null || $destinatario[0]["BAIRRO"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1006,
                'erro' => 'Bairro não localizado',
                '_dica' => 'Campo preenchido automaticamente através do CEP, se necessário edite o campo antes de concluir',
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        }

        if (!isset($destinatario[0]["CIDADE"]) || $destinatario[0]["CIDADE"] == null || $destinatario[0]["CIDADE"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1006,
                'erro' => 'Cidade não localizada',
                '_dica' => 'Campo preenchido automaticamente através do CEP, se necessário edite o campo antes de concluir',
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        }

        if (!isset($destinatario[0]["UF"]) || $destinatario[0]["UF"] == null || $destinatario[0]["UF"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1006,
                'erro' => 'UF não localizada',
                '_dica' => 'Selecione a UF do destinatario e prossiga com o cadastro',
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        }

        if (!isset($destinatario[0]["CODMUNICIPIO"]) || $destinatario[0]["CODMUNICIPIO"] == null || $destinatario[0]["CODMUNICIPIO"] == "") {
            $response = array(
                'status' => false,
                'codErro' => 1007,
                'erro' => 'Código do município não localizado',
                '_dica' => "Código no municipio é inserido automaticamente assim que o CEP for preenchido",
                '_form' => 'contas',
                '_mod' => 'crm',
                '_submenu' => 'alterar',
                '_varControle' => 'param',
                '_id' => $arrayNotaFiscal[0]["PESSOA"]
            );
            return $response;
        } #########################  END VALIDA CODIGO DO MUNICIPIO ####################      
    }

    /**
     * Funcao para validar os dados dos produtos
     * @param INT $id nota fiscal
     * @return ARRAY todos os campos da table est_nota_fiscal_produto
     */
    public function validaProdutos($id)
    {
        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal_produto ";
        $sql .= "WHERE idnf = '" . $id . "'; ";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $arrayProd = $banco->resultado;

        if (!is_array($arrayProd)) {
            $response = array(
                'status' => false,
                'codErro' => 1100,
                'erro' => 'Produto não localizado para essa nota fiscal',
                '_dica' => 'Verifique os produtos da nota fiscal',
                '_form' => 'telaAtual',
                '_mod' => '',
                '_submenu' => '',
                '_varControle' => '',
                '_id' => $id
            );
            return $response;
        }

        foreach ($arrayProd as $produto) {

            if ($produto["UNIDADE"] == "" || $produto["UNIDADE"] == null) {
                $response = array(
                    'status' => false,
                    'codErro' => 1101,
                    'erro' => 'Tipo da unidade do produto não preenchida',
                    '_dica' => 'Verifique o cadastro do produto na nota fiscal' . $produto["CODPRODUTO"] . " - " . $produto["DESCRICAO"],
                    '_form' => 'telaAtual',
                    '_mod' => '',
                    '_submenu' => '',
                    '_varControle' => '',
                    '_id' => $produto["ID"]
                );
                return $response;
            }

            if ($produto["ORIGEM"] == "" || $produto["ORIGEM"] == null) {
                $response = array(
                    'status' => false,
                    'codErro' => 1102,
                    'erro' => 'Origem do produto não localizado',
                    '_dica' => 'Verifique o cadastro do produto na nota fiscal' . $produto["CODPRODUTO"] . " - " . $produto["DESCRICAO"],
                    '_form' => 'telaAtual',
                    '_mod' => '',
                    '_submenu' => '',
                    '_varControle' => '',
                    '_id' => $produto["ID"]
                );
                return $response;
            }

            if ($produto["TRIBICMS"] == "" || $produto["TRIBICMS"] == null || $produto["TRIBICMS"] == "0") {
                $response = array(
                    'status' => false,
                    'codErro' => 1103,
                    'erro' => 'ICMS/CSOSN do produto não localizado',
                    '_dica' => 'Verifique o cadastro do produto na nota fiscal: ' . $produto["CODPRODUTO"] . " - " . $produto["DESCRICAO"],
                    '_form' => 'telaAtual',
                    '_mod' => '',
                    '_submenu' => '',
                    '_varControle' => '',
                    '_id' => $produto["ID"]
                );
                return $response;
            }

            if ($produto["NCM"] == "" || $produto["NCM"] == null || $produto["TRIBICMS"] == "0") {
                $response = array(
                    'status' => false,
                    'codErro' => 1104,
                    'erro' => 'NCM do produto não localizado',
                    '_dica' => 'Verifique o cadastro do produto na nota fiscal ' . $produto["CODPRODUTO"] . " - " . $produto["DESCRICAO"],
                    '_form' => 'telaAtual',
                    '_mod' => '',
                    '_submenu' => '',
                    '_varControle' => '',
                    '_id' => $produto["ID"]
                );
                return $response;
            }

            if ($produto["CFOP"] == "" || $produto["CFOP"] == null || $produto["TRIBICMS"] == "0") {
                $response = array(
                    'status' => false,
                    'codErro' => 1105,
                    'erro' => 'CFOP do produto não localizado',
                    '_dica' => 'Verifique o cadastro do produto na nota fiscal ' . $produto["CODPRODUTO"] . " - " . $produto["DESCRICAO"],
                    '_form' => 'telaAtual',
                    '_mod' => '',
                    '_submenu' => '',
                    '_varControle' => '',
                    '_id' => $produto["ID"]
                );
                return $response;
            }
        }
    }

    /**
     * Funcao para realizar consulta de dados do cliente
     * @param INT $id nota fiscal
     * @return ARRAY todos os campos da table est_nota_fiscal
     */
    public function buscaDadosCliente($id)
    {
        $sql = "SELECT * ";
        $sql .= "FROM fin_cliente ";
        $sql .= "WHERE cliente = '" . $id . "'; ";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta o CNPJ na API ReceitaWS.
     *
     * @param string $cnpj
     * @return array|null
     */
    function consultarCNPJ($cnpj)
    {
        $url = "https://www.receitaws.com.br/v1/cnpj/" . $cnpj;

        $ch = curl_init();

        // Configurações do cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);
        if ($response === FALSE) {
            die('Erro ao consultar o CNPJ: ' . curl_error($ch));
        }

        // Fecha a conexão cURL
        curl_close($ch);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);

        // Verifica se a decodificação foi bem-sucedida
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Erro ao decodificar a resposta JSON.');
        }

        // Verifica se a resposta contém erro
        if (isset($data['status']) && $data['status'] == 'ERROR') {
            echo "Erro: " . $data['message'] . "\n";
            return null;
        }

        return $data;
    }
}

//	END OF THE CLASS
