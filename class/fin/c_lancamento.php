<?php

/**
 * @package   astecv3
 * @name      c_lancamento
 * @category  BUSINESS CLASS - Lancamento de receitas ou despesas financeiro
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/05/2016
 */
$dir = dirname(__FILE__);

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
//include_once("../../bib/c_mail.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../class/crm/c_conta.php");
//include_once("class.phpmailer.php");

//Class C_LANCAMENTO
class c_lancamento extends c_user
{

        // Campos tabela
        private $id = NULL;
        private $pessoa = NULL;        // integer not null,
        private $nomePessoa = NULL;        // nome pessoa, nao faz parte do cadastro
        private $emailPessoa = NULL;        // nome pessoa, nao faz parte do cadastro
        private $docto = NULL;         //integer not null,
        private $serie = NULL;         //char(3) not null,
        private $parcela = NULL;        // char(3) not null,
        private $agrupamento = NULL;        // integer,
        private $tipolancamento = NULL;         //char(1) not null,
        private $tipodocto = NULL;         //char(1) not null,
        private $sitdocto = NULL;         //char(1) not null,
        private $sitpgto = NULL;         //char(1) not null,
        private $sitpgtoAnt = NULL;         //char(1) not null,
        private $usrsitpgto = NULL;         //smallint,
        private $modopgto = NULL;         //char(1) not null,
        private $docbancario = NULL;        // varchar(40),
        private $conta = NULL;        // smallint,
        private $numlcto = NULL; //	 integer,
        private $cheque = NULL; //	 varchar(10),
        private $usraprovacao = NULL; //	 smallint,
        private $genero = NULL; //	 varchar(4) not null,
        private $generoDesc = NULL;        // genero descricao, nao faz parte do cadastro
        private $centrocusto = NULL; //	 integer not null,
        private $lancamento = NULL; //	 date,
        private $emissao = NULL; //	 date not null,
        private $vencimento = NULL; //	 date not null,
        private $pagamento = NULL; //	 date,
        private $original = 0.0; //	 numeric(11,2) not null,
        private $multa = 0; //	 numeric(9,2),
        private $juros = 0; //	 numeric(9,2),
        private $adiantamento = 0; //	 numeric(11,2),
        private $desconto = 0; //	 numeric(9,2),
        private $total = 0; //	 numeric(11,2),
        private $moeda = NULL; //	 smallint,
        private $origem = NULL; //	 varchar(3),
        private $obs = NULL; //	 blob sub_type text segment size 80,
        private $contabil = NULL; //	 char(1) not null,
        private $debito = NULL; //	 varchar(10),
        private $credito = NULL; //	 varchar(10),
        private $obscontabil = NULL; //	 blob sub_type text segment size 80,
        private $doctocontabil = NULL; //	 varchar(15),
        private $retencaoinss = NULL; //	 numeric(9,2),
        private $nossoNumero = NULL; //	 numeric(9,2),

        private $quantparc = NULL; //	 numeric(9,2),

        private $remessaNum = NULL; //	 numeric(9,2),
        private $remessaData = NULL; //	 numeric(9,2),
        private $remessaArq = NULL; //	 numeric(9,2),

        private $retornoarq = NULL;
        private $retornocod = NULL;
        private $retornocodrejeicao = NULL;
        private $retornocodbaixa = NULL;
        private $retornodataliq  = NULL;

        //conta para remessa ou retorno
        public $contaIntBancaria = NULL;
        public $idInsert = NULL;


        //construtor
        function __construct()
        {
                // Cria uma instancia variaveis de sessao
                session_start();
                c_user::from_array($_SESSION['user_array']);
        }

        /*---------------------------------------------------------------
* METODOS DE SETS E GETS
---------------------------------------------------------------*/

        public function setId($lancamento)
        {
                $this->id = $lancamento;
        }
        public function getId()
        {
                return $this->id;
        }

        public function setPessoa($pessoa)
        {
                $this->pessoa = $pessoa;
        }
        public function getPessoa()
        {
                if (is_numeric($this->pessoa))
                        return $this->pessoa;
                else
                        return 0;
        }

        public function setPessoaNome()
        {
                $cliente = new c_conta();
                $cliente->setId($this->getPessoa());
                $reg_nome = $cliente->select_conta();
                $this->nomePessoa = $reg_nome[0]['NOME'];
                $this->emailPessoa = $reg_nome[0]['EMAIL'];
        }
        public function getPessoaNome()
        {
                return $this->nomePessoa;
        }
        public function getPessoaEmail()
        {
                return $this->emailPessoa;
        }

        public function setDocto($docto)
        {
                $this->docto = $docto;
        }
        public function getDocto()
        {
                if ($this->docto == NULL) {
                        return 0;
                } else {
                        return $this->docto;
                }
        }

        public function setSerie($serie)
        {
                $this->serie = strtoupper($serie);
        }
        public function getSerie()
        {
                return $this->serie;
        }

        public function setParcela($parcela)
        {
                $this->parcela = strtoupper($parcela);
        }
        public function getParcela()
        {
                return $this->parcela;
        }

        public function setAgrupamento($agrupamento)
        {
                $this->agrupamento = $agrupamento;
        }
        public function getAgrupamento()
        {
                return $this->agrupamento;
        }

        public function setTipolancamento($tipolancamento)
        {
                $this->tipolancamento = strtoupper($tipolancamento);
        }
        public function getTipolancamento()
        {
                return $this->tipolancamento;
        }

        public function setTipodocto($tipodocto)
        {
                $this->tipodocto = strtoupper($tipodocto);
        }
        public function getTipodocto()
        {
                return $this->tipodocto;
        }

        public function setSitdocto($sitdocto)
        {
                $this->sitdocto = strtoupper($sitdocto);
        }
        public function getSitdocto()
        {
                return $this->sitdocto;
        }

        public function setSitpgtoAnt($sitpgtoAnt)
        {
                $this->sitpgtoAnt = strtoupper($sitpgtoAnt);
        }
        public function getSitpgtoAnt()
        {
                return $this->sitpgtoAnt;
        }

        public function setSitpgto($sitpgto)
        {
                $this->sitpgto = strtoupper($sitpgto);
        }
        public function getSitpgto()
        {
                return $this->sitpgto;
        }

        public function setUsrsitpgto($usrsitpgto)
        {
                $this->usrsitpgto = $usrsitpgto;
        }
        public function getUsrsitpgto()
        {
                return $this->usrsitpgto;
        }

        public function setModopgto($modopgto)
        {
                $this->modopgto = strtoupper($modopgto);
        }
        public function getModopgto()
        {
                return $this->modopgto;
        }

        public function setDocbancario($docbancario)
        {
                $this->docbancario = strtoupper($docbancario);
        }
        public function getDocbancario()
        {
                return $this->docbancario;
        }

        public function setConta($conta)
        {
                $this->conta = $conta;
        }

        public function getConta()
        {
                return $this->conta;
        }

        public function setNumlcto($numlcto)
        {
                $this->numlcto = $numlcto;
        }
        public function getNumlcto()
        {

                if (($this->numlcto == null) or ($this->numlcto == '')) {
                        return 0;
                } else {
                        return $this->numlcto;
                }
        }

        public function setCheque($cheque)
        {
                $this->cheque = strtoupper($cheque);
        }
        public function getCheque()
        {
                return $this->cheque;
        }

        public function setUsraprovacao($usraprovacao)
        {
                $this->usraprovacao = $usraprovacao;
        }
        public function getUsraprovacao()
        {
                return $this->usraprovacao;
        }


        public function setGenero($genero)
        {
                $this->genero = strtoupper($genero);
        }
        public function getGenero()
        {
                return $this->genero;
        }

        public function setDescGenero()
        {
                $consulta = new c_banco();
                $sql = "select genero as id, descricao from fin_genero where genero='" . $this->getGenero() . "'";
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $result = $consulta->resultado;
                $this->generoDesc = $result[0]['DESCRICAO'];
        }
        public function getDescGenero()
        {
                return $this->generoDesc;
        }

        public function setCentroCusto($cc)
        {
                $this->centrocusto = $cc;
        }
        public function getCentroCusto()
        {
                return $this->centrocusto;
        }

        public function setLancamento($lancamento)
        {
                $this->lancamento = $lancamento;
        }
        public function getLancamento($format = null)
        {
                $this->lancamento = strtr($this->lancamento, "/", "-");
                switch ($format) {
                        case 'F':
                                return date('d/m/Y', strtotime($this->lancamento));
                                break;
                        case 'B':
                                return c_date::convertDateBd($this->lancamento, $this->m_banco);
                                break;
                        default:
                                return $this->lancamento;
                }
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
                                return date('d/m/Y', strtotime($this->emissao));
                                break;
                        case 'B':
                                return c_date::convertDateBd($this->emissao, $this->m_banco);
                                break;
                        default:
                                return $this->emissao;
                }
        }


        public function setVencimento($venc)
        {
                $this->vencimento = $venc;
        }
        public function getVencimento($format = null)
        {
                //if ($this->sitpgto != 'B') {
                //	$this->setMovimento($this->vencimento);
                //}
                $this->vencimento = strtr($this->vencimento, "/", "-");
                switch ($format) {
                        case 'F':
                                return date('d/m/Y', strtotime($this->vencimento));
                                break;
                        case 'B':
                                return c_date::convertDateBd($this->vencimento, $this->m_banco);
                                break;
                        default:
                                return $this->vencimento;
                }
        }

        public function setMovimento($pag)
        {
                $this->pagamento = $pag;
        }
        public function getMovimento($format = null)
        {
                $this->pagamento = strtr($this->pagamento, "/", "-");
                switch ($format) {
                        case 'F':
                                return date('d/m/Y', strtotime($this->pagamento));
                                break;
                        case 'B':
                                return c_date::convertDateBd($this->pagamento, $this->m_banco);
                                break;
                        default:
                                return $this->pagamento;
                }
        }

        public function setOriginal($original, $format = null)
        {
                $original = strtr($original, "_", "0");
                $this->original = $original;
                if ($format):
                        $this->original = number_format($this->original, 2, ',', '.');
                endif;
        }
        public function getOriginal($format = null)
        {
                if ($format == 'F') {
                        //return number_format($this->original, 2, ',', '.'); }
                        return number_format((float)$this->original, 2, ',', '.');
                } else if ($format == 'B') {
                        $this->original = c_tools::moedaBd($this->original);
                        return $this->original;
                } else {
                        return $this->original == null ? 0 : $this->original;
                }
        }
        public function setMulta($multa)
        {
                $multa = strtr($multa, "_", "0");
                $this->multa = $multa;
        }
        public function getMulta($format = null)
        {
                if ($format == 'F') {

                        return number_format((float)$this->multa, 2, ',', '.');
                } elseif ($format == 'B') {
                        $this->multa = c_tools::moedaBd($this->multa);
                        return $this->multa;
                } else {
                        return $this->multa == null ? 0 : $this->multa;
                }
        }

        public function setJuros($juros)
        {
                $juros = strtr($juros, "_", "0");
                $this->juros = $juros;
        }
        public function getJuros($format = null)
        {
                if ($format == 'F') {
                        return number_format((float)$this->juros, 2, ',', '.');
                } elseif ($format == 'B') {
                        $this->juros = c_tools::moedaBd($this->juros);
                        return $this->juros;
                } else {
                        return $this->juros == null ? 0 : $this->juros;
                }
        }

        public function setAdiantamento($adiantamento)
        {
                $adiantamento = strtr($adiantamento, "_", "0");
                $this->adiantamento = $adiantamento;
        }
        public function getAdiantamento($format = null)
        {
                if ($format == 'F') {
                        return number_format((float)$this->adiantamento, 2, ',', '.');
                } elseif ($format == 'B') {
                        $this->adiantamento = c_tools::moedaBd($this->adiantamento);
                        return $this->adiantamento;
                } else {
                        return $this->adiantamento == null ? 0 : $this->adiantamento;
                }
        }

        public function setDesconto($desconto)
        {
                $desconto = strtr($desconto, "_", "0");
                $this->desconto = $desconto;
        }
        public function getDesconto($format = null)
        {
                if ($format == 'F') {
                        return number_format((float)$this->desconto, 2, ',', '.');
                } elseif ($format == 'B') {
                        $this->desconto = c_tools::moedaBd($this->desconto);
                        return $this->desconto;
                } else {
                        return $this->desconto == null ? 0 : $this->desconto;
                }
        }

        public function setTotal($total, $format = null)
        {
                $this->total = $total;
                if ($format):
                        $this->total = number_format($this->total, 2, ',', '.');
                endif;
        }
        public function getTotal($format = null, $param = null)
        {
                if ($param == null) {
                        if ($format == 'F') {

                                $this->total =

                                        $this->getOriginal() +
                                        $this->getMulta() +
                                        $this->getJuros() -
                                        ($this->getAdiantamento() + $this->getDesconto());
                                //  echo "origianl".$this->getOriginal()."multa".$this->getMulta()."juros".
                                //          $this->getJuros()."adi".$this->getAdiantamento()."desc".
                                //          $this->getDesconto();
                                return number_format(doubleval($this->total), 2, ',', '.');
                        } else if ($format == 'B') {
                                $this->total =
                                        $this->getOriginal() +
                                        $this->getMulta() +
                                        $this->getJuros() -
                                        ($this->getAdiantamento() + $this->getDesconto());

                                if ($this->total != null) {
                                        return $this->total;
                                } else {
                                        return 0;
                                }
                        } else {
                                $this->total =
                                        $this->getOriginal('B') +
                                        $this->getMulta('B') +
                                        $this->getJuros('B') -
                                        ($this->getAdiantamento('B') + $this->getDesconto('B'));

                                if ($this->total != null) {
                                        return $this->total;
                                } else {
                                        return 0;
                                }
                        }
                } else {
                        $this->total = $this->getOriginal() +
                                $this->getMulta() +
                                $this->getJuros() -
                                ($this->getAdiantamento() +
                                        $this->getDesconto());
                        return $this->total;
                }
        }

        public function setMoeda($moeda)
        {
                $this->moeda = 0;
        }
        public function getMoeda()
        {
                return $this->moeda;
        }

        public function setOrigem($origem)
        {
                $this->origem = $origem;
        }
        public function getOrigem()
        {
                return $this->origem;
        }

        public function setObs($obs)
        {
                $this->obs = strtoupper($obs);
        }
        public function getObs()
        {
                return $this->obs;
        }

        public function setObsContabil($obscontabil)
        {
                $this->obscontabil = strtoupper($obscontabil);
        }
        public function getObsContabil()
        {
                return $this->obscontabil;
        }

        public function setContabil($contabil)
        {
                $this->contabil = 'N';
        }
        public function getContabil()
        {
                return $this->contabil;
        }

        public function setQuantParc($quantparc)
        {
                $this->quantparc = $quantparc;
        }
        public function getQuantParc()
        {
                return $this->quantparc;
        }

        public function setRemessaNum($remessanum)
        {
                $this->remessanum = $remessanum;
        }
        public function getRemessaNum()
        {
                return $this->remessanum;
        }

        public function setRemessaArq($remessaarq)
        {
                $this->remessaarq = $remessaarq;
        }
        public function getRemessaArq()
        {
                return $this->remessaarq;
        }

        public function setRemessaData($remessadata)
        {
                $this->remessadata = $remessadata;
        }
        public function getRemessaData()
        {
                return $this->remessadata;
        }

        public function setNossoNumero($nossonumero)
        {
                $this->nossonumero = $nossonumero;
        }
        public function getNossoNumero()
        {
                return $this->nossonumero;
        }

        public function setRetornoArq($retornoarq)
        {
                $this->retornoarq = $retornoarq;
        }
        public function getRetornoArq()
        {
                return $this->retornoarq;
        }

        public function setRetornoCod($retornocod)
        {
                $this->retornocod = $retornocod;
        }
        public function getRetornoCod()
        {
                return $this->retornocod;
        }

        public function setRetornoCodRejeicao($retornocodrejeicao)
        {
                $this->retornocodrejeicao = $retornocodrejeicao;
        }
        public function getRetornoCodRejeicao()
        {
                return $this->retornocodrejeicao;
        }

        public function setRetornoCodBaixa($retornocodbaixa)
        {
                $this->retornocodbaixa = $retornocodbaixa;
        }
        public function getRetornoCodBaixa()
        {
                return $this->retornocodbaixa;
        }

        public function setRetornoDataLiq($retornodataliq)
        {
                $this->retornodataliq = $retornodataliq;
        }
        public function getRetornoDataLiq()
        {
                return $this->retornodataliq;
        }
        //############### FIM SETS E GETS ###############

        /**
         * @name gravaNossoNumero
         * @param int $id - id lancamento para gravar nosso numero
         * @param int $nn -  nosso numero a ser gravado
         * @description atualiza nosso numero no lancamento a receber.
         */
        public static function gravaNossoNumero($id, $nn)
        {

                // SALVA NOSSO NUMERO
                $sql  = "UPDATE fin_lancamento ";
                $sql .= "SET  ";
                $sql .= "NOSSONUMERO = '" . $nn . "' ";
                $sql .= "WHERE id = " . $id . ";";

                $contaBanco = new c_banco();
                $res_contaBanco = $contaBanco->exec_sql($sql);
                $contaBanco->close_connection();
                if ($res_contaBanco <= 0):
                        $nn = 0;
                endif;
                return $nn;
        } //fim gravaNossoNumero

        /**
         * @name atualizaRemessa
         * @param int $id - id lancamento para gravar nosso numero
         * @param int $nn -  nosso numero a ser gravado
         * @param int $nr -  numero remessa a ser gravado
         * @param int $data -  data da remessa a ser gravado
         * @description atualiza dados de remessa no lancamento a receber.
         */
        public function atualizaRemessa($id, $nn, $nr, $data, $arq)
        {

                // SALVA NOSSO NUMERO
                $sql  = "UPDATE fin_lancamento ";
                $sql .= "SET  ";
                $sql .= "COBRANCASTATUS = 'R', ";
                $sql .= "NOSSONUMERO = " . $nn . ", ";
                $sql .= "REMESSANUM = " . $nr . ", ";
                $sql .= "REMESSADATA = '" . $data . "', ";
                $sql .= "REMESSAARQ = '" . $arq . "' ";
                $sql .= "WHERE id = " . $id . ";";
                $contaBanco = new c_banco();
                $res_contaBanco = $contaBanco->exec_sql($sql);
                $contaBanco->close_connection();

                // SALVA historico T-RETORNO M-REMESSA
                $sql  = "INSERT INTO fin_lancamento_cob (ID_LANCAMENTO, REMESSANUM, REMESSADATA, REMESSAARQ, TIPOENVIO) VALUES (";
                $sql .= $id . ", " . $nr . ", '" . $data . "', '" . $arq . "','M') ";

                $contaBanco = new c_banco();
                $res_contaBanco = $contaBanco->exec_sql($sql);
                $contaBanco->close_connection();
                if ($res_contaBanco <= 0):
                        $nn = 0;
                endif;
                return $nn;
        } //fim gravaNossoNumero


        /**
         * @name existeDocumento
         * @description pesquisa se existe o nome do arquivode retorno em lancamento
         * @return bool $banco->resultado com registros encontrados
         */
        public function retornoProcessado($arq, $data)
        {

                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (RETORNOARQ like '%" . $arq . "%') and (RETORNODATALIQ='" . $data . "')";
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return is_array($banco->resultado);
        } //fim retornoProcessado


        /**
         * @name atualizaRetorno
         * @param array $lanc - id lancamento para gravar nosso numero
         * @param string $arq -  nome do arquivo de retorno
         * @description atualiza dados de retorno no lancamento a receber.
         */
        public function atualizaRetorno($lanc, $arq)
        {
                try {
                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);

                        // ATUALIZA FINANCEIRO PELO ARQUIVO DE RETORNO..
                        for ($i = 0; $i < count($lanc); $i++):
                                $sql  = "UPDATE fin_lancamento ";
                                $sql .= "SET  ";
                                switch ($lanc[$i]['numOcorrencia']):
                                        case '02': //Entrada Confirmada
                                                $sql .= "COBRANCASTATUS = 'C', ";
                                                $sql .= "RETORNOCOD = '02', ";
                                                break;
                                        case '03': //Entrada Rejeitada
                                                $sql .= "SITPGTO = 'N', ";
                                                $sql .= "COBRANCASTATUS = 'R', ";
                                                $sql .= "RETORNOCOD = '03', ";
                                                $sql .= "RETORNOCODREJEICAO = '03', ";
                                                break;
                                        case '06': // liquidação normal
                                                $sql .= "SITPGTO = 'B', ";
                                                $sql .= "PAGAMENTO = '" . c_date::convertDateBd($lanc[$i]['dataCreditoBD']) . "', ";
                                                $sql .= "RETORNODATALIQ = '" . $lanc[$i]['dataOcorrenciaBD'] . "', ";
                                                $sql .= "JUROS = " . $lanc[$i]['juros'] . ", ";
                                                $sql .= "TOTAL = " . $lanc[$i]['valorPago'] . ", ";
                                                $sql .= "COBRANCASTATUS = 'L', ";
                                                $sql .= "RETORNOCOD = '06', ";

                                                $vt = $lanc[$i]['valorTitulo'];
                                                $bc = $lanc[$i]['bancoCobrador'];
                                                $ac = $lanc[$i]['agenciaCobrador'];
                                                $dc = $lanc[$i]['despesaCobranca'];
                                                $od = $lanc[$i]['outrasDespesa'];
                                                $ja = $lanc[$i]['jurosAtraso'];
                                                $iof = $lanc[$i]['iof'];
                                                $ab = $lanc[$i]['abatimento'];
                                                $dt = $lanc[$i]['desconto'];
                                                $vp = $lanc[$i]['valorPago'];
                                                $js = $lanc[$i]['juros'];
                                                $oc = $lanc[$i]['outroCredito'];
                                                $mc = $lanc[$i]['motivoCodOcorrencia'];

                                                break;
                                        case '10': // Baixado conforme instruções da Agência(entrar em contato com a agencia)
                                                $sql .= "SITPGTO = 'N', ";
                                                $sql .= "COBRANCASTATUS = 'B', ";
                                                $sql .= "RETORNOCOD = '10', ";
                                                $sql .= "RETORNOCODBAIXA = '10', ";
                                                break;
                                        case '12': // Abatimento Concedido
                                                $sql .= "SITPGTO = 'A', ";
                                                $sql .= "PAGAMENTO = '" . c_date::convertDateBd($lanc[$i]['dataCreditoBD']) . "', ";
                                                // $sql .= "RETORNODATALIQ = '".$lanc[$i]['dataOcorrenciaBD']."', " ;
                                                $sql .= "ADIANTAMENTO = " . $lanc[$i]['abatimento'] . ", ";
                                                // $sql .= "JUROS = ".$lanc[$i]['juros'].", " ;
                                                $sql .= "TOTAL = " . ($lanc[$i]['valorTitulo'] - $lanc[$i]['abatimento']) . ", ";
                                                $sql .= "COBRANCASTATUS = 'A', "; // abatimento
                                                $sql .= "RETORNOCOD = '12', ";

                                                $vt = $lanc[$i]['valorTitulo'];
                                                $bc = $lanc[$i]['bancoCobrador'];
                                                $ac = $lanc[$i]['agenciaCobrador'];
                                                $dc = $lanc[$i]['despesaCobranca'];
                                                $od = $lanc[$i]['outrasDespesa'];
                                                $ja = $lanc[$i]['jurosAtraso'];
                                                $iof = $lanc[$i]['iof'];
                                                $ab = $lanc[$i]['abatimento'];
                                                $dt = $lanc[$i]['desconto'];
                                                $vp = $lanc[$i]['valorPago'];
                                                $js = $lanc[$i]['juros'];
                                                $oc = $lanc[$i]['outroCredito'];
                                                $mc = $lanc[$i]['motivoCodOcorrencia'];

                                                break;
                                        case '17': //Liquidação após baixa ou Título não registrado
                                                $sql .= "SITPGTO = 'B', ";
                                                $sql .= "PAGAMENTO = '" . c_date::convertDateBd($lanc[$i]['dataCreditoBD']) . "', ";
                                                $sql .= "RETORNODATALIQ = '" . $lanc[$i]['dataOcorrenciaBD'] . "', ";
                                                $sql .= "TOTAL = " . $lanc[$i]['valorPago'] . ", ";
                                                $sql .= "COBRANCASTATUS = 'N', ";
                                                $sql .= "RETORNOCOD = '17', ";
                                                break;
                                        default: //outros
                                                $sql .= "SITPGTO = 'N', ";
                                                $sql .= "COBRANCASTATUS = 'O', ";
                                                $sql .= "RETORNOCOD = '" . $lanc[$i]['numOcorrencia'] . "' , ";
                                endswitch;
                                $sql .= "RETORNOARQ = '" . $arq . "', ";
                                $sql .= "userchange = " . $this->m_userid . ", ";
                                $sql .= "datechange = '" . date("Y-m-d H:i:s") . "' ";
                                $sql .= "WHERE id = " . $lanc[$i]['id'] . ";";

                                if ($lanc[$i]['id'] != '0'):
                                        if (($lanc[$i]['sitant'] == 'B') or ($lanc[$i]['sitant'] == 'C')):
                                                $teste = $lanc[$i]['nf'];
                                                $testesitant = $lanc[$i]['sitant'];
                                        endif;
                                        if (($lanc[$i]['sitant'] != 'B') and ($lanc[$i]['sitant'] != 'C')): // altera situação somente se for <> de baixado
                                                $contaBanco = new c_banco();
                                                $res_contaBanco = $contaBanco->exec_sql($sql, $transaction->id_connection);
                                                $contaBanco->close_connection();
                                        endif;

                                        // SALVA historico T-RETORNO M-REMESSA
                                        $sql  = "INSERT INTO fin_lancamento_cob (ID_LANCAMENTO, RETORNOCOD, RETORNODATA, REMESSAARQ, TIPOENVIO) VALUES (";
                                        $sql .= $lanc[$i]['id'] . ", " . $lanc[$i]['numOcorrencia'] . ", '" . c_date::convertDateBd($lanc[$i]['dataOcorrenciaBD']) . "', '" . $arq . "','T') ";

                                        $contaBanco = new c_banco();
                                        $res_contaBanco = $contaBanco->exec_sql($sql, $transaction->id_connection);
                                        $contaBanco->close_connection();

                                endif;

                        endfor;
                        // commit transação
                        $transaction->commit($transaction->id_connection);
                        return true;
                } catch (Exception $e) {
                        $transaction->rollback($transaction->id_connection);
                        return false;
                }
        } //fim atualizaRetorno

        /**
         * @name alteraParcelaPedidoNf
         * @param int $pedido - pedido a ser alterado no lancamento
         * @param int $nf - numero da nf a ser atualizada no lancamento
         * @description altera o lançamento financeiro com numero de pedido para o numero da nf gerada
         */
        public function alteraParcelaPedidoNf($pedido, $nf, $conn = null)
        {

                $sql  = "UPDATE fin_lancamento ";
                $sql .= "SET  ";
                $sql .= "DOCTO = '" . $nf . "', ";
                $sql .= "SERIE = 'NFS' ";
                $sql .= "WHERE  (ORIGEM='PED') AND (NUMLCTO = " . $pedido . ");";

                $contaBanco = new c_banco();
                $contaBanco->exec_sql($sql, $conn);
                $contaBanco->close_connection();
                return is_array($contaBanco->resultado);
        } //fim gravaNossoNumero


        /**
         * @name addParcelasNf
         * @description adiciona parcelas referente a Nf
         * @param int $this->getQuantParc - quantidade de parcelas adicionais
         * @return int $count - numero de parcelas adicionadas
         */
        public function addParcelas($arrParFin = NULL, $arrParcelas = NULL, $conn = null)
        {

                $this->setPessoa($arrParFin['PESSOA']);
                $this->setDocto($arrParFin['DOCTO']);
                $this->setSerie($arrParFin['SERIE']);
                $this->setTipolancamento($arrParFin['TIPOLANCAMENTO']);
                $this->setSitdocto('N'); // normal
                $this->setUsrsitpgto($arrParFin['USER']); //usuario
                $this->setModopgto('B'); // bancario
                $this->setOrigem($arrParFin['ORIGEM']); // ??/
                $this->setNumlcto($arrParFin['NUMLCTO']); // ??/
                $this->setGenero($arrParFin['GENERO']); // array
                $this->setCentroCusto($arrParFin['CENTROCUSTO']);        // centro custo atual
                $this->setLancamento(date("d/m/Y"));
                $this->setEmissao(date("d/m/Y"));
                $this->setMulta(0);
                $this->setJuros(0);
                $this->setAdiantamento(0);
                $this->setDesconto(0);
                $this->setMoeda(0);

                for ($i = 0; $i < count($arrParcelas); $i++) {

                        $this->setParcela($i + 1);
                        $this->setTipodocto($arrParcelas[$i]['TIPO']); // boleto
                        $this->setSitpgto($arrParcelas[$i]['SITUACAO']); // aberto
                        $this->setConta($arrParcelas[$i]['CONTA']); //array
                        $this->setVencimento($arrParcelas[$i]['VENCIMENTO']); //arry
                        $this->setMovimento($arrParcelas[$i]['VENCIMENTO']);
                        $this->setOriginal($arrParcelas[$i]['VALOR'], true);
                        $this->setTotal($arrParcelas[$i]['VALOR'], true); //array
                        $this->setObs($arrParcelas[$i]['OBS'] . " / " . $arrParFin['OBS']); //array
                        $this->setDesconto($arrParcelas[$i]['DESCONTO'], true); //array

                        if ($this->existeDocumento()) {
                                return false;
                        }

                        $result = $this->incluiLancamento($conn);
                }

                return $result;
        } //fim addParcelasNf


        /**
         * @name existeDocumento
         * @description pesquisa se já existe código cadastrado
         * @return bool $banco->resultado com registros encontrados
         */
        public function existeDocumento($array = false)
        {

                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (pessoa = " . $this->getPessoa() . ") and (docto = " . $this->getDocto() . ") and ";
                $sql .= "(vencimento = '" . $this->getVencimento('B') . "')";
                // $sql .= "(serie = '".$this->getSerie()."') and (parcela = '".$this->getParcela()."')";
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                // if ($array):
                //     return $banco->resultado;
                // else:    
                return is_array($banco->resultado);
                // endif;
        } //fim existeDocumento


        /**
         * @name verificaPendenciaFinanceira
         * @description pesquisa se existe documentos a receber vencidos
         * @return bool $banco->resultado - True ou False se encontrou algum registro ou nao
         */
        public function verificaPendenciaFinanceira($pessoa, $data)
        {


                function isWeekend($date)
                {
                        return (date('N', strtotime($date)) >= 6);
                }

                $dateTools = new c_date();

                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (pessoa = " . $pessoa . ") AND ";
                $sql .= "(vencimento < '" . $data . "') and (sitpgto ='A') ";
                $sql .= "order by vencimento DESC";
                //$sql .= "(vencimento < '".$data."') and (sitpgto in ('A','N'))";
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                $result = $banco->resultado;
                $diaSemana = date('N', strtotime($data));
                $return = false;
                if (is_array($result)):
                        for ($i = 0; $i < count($result); $i++):
                                $difDias = $dateTools->DataDif($result[$i]['VENCIMENTO'], $data, 'd');
                                // if (!(isWeekend($result[$i]['VENCIMENTO']) 
                                //         and ($diaSemana == 1 || $diaSemana == 6 || $diaSemana == 7)
                                //         and ($difDias <=3))):
                                if (($diaSemana == 1 || $diaSemana == 6 || $diaSemana == 7) and isWeekend($result[$i]['VENCIMENTO']) and ($difDias <= 3)):
                                        $return = false;
                                else:
                                        $return = true;
                                endif;
                        endfor;
                endif;
                return $return;
        } //fim verificaPendenciaFinanceira

        /**
         * @name verificaPendenciaFinanceira
         * @description pesquisa se existe documentos a receber vencidos
         * @return bool $banco->resultado - True ou False se encontrou algum registro ou nao
         */
        static public function verificaDocBaixado($pessoa, $doc, $origem)
        {

                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (pessoa = " . $pessoa . ") AND ";
                $sql .= "(docto = '" . $doc . "') and (serie = '" . $origem . "') and (sitpgto = 'B')";
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return is_array($banco->resultado);
        } //fim existeDocumento

        /**
         * @name alteraSituacaoFinanceiro
         * @description altera sistuação dos lançamentos financeiro de um docuemnto
         * @return bool $banco->resultado - True ou False se operação bem sucedida
         */
        public static function alteraSituacaoFinanceiro($pessoa, $doc, $origem, $situacao)
        {

                $sql  = "update FIN_LANCAMENTO set ";
                $sql .= "sitpgto = '" . $situacao . "', ";
                $sql .= "obs = 'Cancelamento título devido ao cancelamento da Nota Fiscal de Saída' ";
                if ($origem == 'FIN'):
                        $sql .= "WHERE (id = " . $doc . ") ";
                        $sql .= "and (sitpgto <> 'B')";
                else:
                        $sql .= "WHERE (pessoa = " . $pessoa . ") AND ";
                        $sql .= "(docto = '" . $doc . "') and (serie = '" . $origem . "') ";
                        $sql .= "and (sitpgto <> 'B')";
                endif;
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return ($res_nf > 0) ? true : false;
        } //fim alteraSituacaoFinanceiro

        /**
         * @name select_lancamento_nossonumero
         * @description busca na tabela lancamentos um documento pelo nosso numero
         * @param string $this->getNossoNumero() - num do documento a ser pesquisado
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function select_lancamento_nossonumero($nn, $conta)
        {


                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (nossonumero = " . $nn . ") and (conta=" . $conta . ")";
                //	ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_lancamento

        public function select_lancamento_nossonumero_748($nn, $conta)
        {
                $sql  = "SELECT * ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (nossonumero = '" . $nn . "') and (conta='" . $conta . "')";
                //	ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        /**
         * @name select_lancamento
         * @description busca na tabela lancamentos um documento
         * @param string $this->getId() - num do documento a ser pesquisado
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function select_lancamento()
        {

                $sql  = "SELECT L.*, G.DESCRICAO AS DESCGENERO, P.NOME AS NOMEPESSOA, ";
                $sql .= "(select count(F.DOCTO) from FIN_LANCAMENTO F WHERE F.DOCTO = L.DOCTO AND F.PESSOA=L.PESSOA)  as totalparcelas ";
                $sql .= "FROM FIN_LANCAMENTO L ";
                $sql .= "LEFT JOIN FIN_CLIENTE P ON P.CLIENTE=L.PESSOA ";
                $sql .= "LEFT JOIN FIN_GENERO G ON G.GENERO=L.GENERO ";
                $sql .= "WHERE (L.id = " . $this->getId() . ") ";
                // ECHO $sql;
                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_lancamento


        /**
         * @name select_lancamento
         * @description busca na tabela lancamentos um documento
         * @param string $this->getId() - num do documento a ser pesquisado
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function selectRemessaBancaria($letra)
        {

                $par = explode("|", $letra);
                $data = explode("-", $par[0]);
                $dataIni = c_date::convertDateTxt($data[0]);
                $dataFim = c_date::convertDateTxt($data[1]);

                $sql  = "SELECT a.*, c.nomereduzido, c.nome, c.cidade, s.padrao as situacaopgto, g.descricao as descgenero, ";
                $sql .= "c.pessoa, c.pessoa as TIPOPESSOA, c.cnpjcpf, c.endereco,c.numero, C.CEP, c.bairro, c.uf ";
                $sql .= "FROM FIN_LANCAMENTO a ";
                $sql .= "inner join fin_cliente c on c.cliente = a.pessoa ";
                $sql .= "inner join fin_genero g on g.genero = a.genero ";
                $sql .= "inner join amb_ddm s on ((s.alias='FIN_MENU') and (s.campo='SituacaoPgto') and (s.tipo = a.sitpgto)) ";

                if (array_sum($data) > 0) {
                        $sql .= "WHERE (SITPGTO='A') and (MODOPGTO='B')  and (TIPODOCTO='B')  and (A.TIPOLANCAMENTO='R') AND (SITDOCTO='N') AND ";
                        $sql .= "(REMESSANUM is null) and ";
                        //$sql .= "(REMESSANUM > 0) and ";
                        $sql .= "(a.emissao >= '" . $dataIni . "') and (a.emissao <= '" . $dataFim . "') ";

                        if (($par[1] != '') and ($par[1] != '0')) {
                                $sql .= " AND (a.centrocusto = " . $par[1] . ") ";
                        }
                        if ($par[2] != '') {
                                $sql .= " AND (a.conta = " . $par[2] . ") ";
                        }
                }
                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim selectRemessaBancaria


        /**
         * @name add_mass_lancamento
         * @description adiciona lançamentos adcionais na tabela lancamento com os dados do lancamento atual, alterando a pessoa
         * @param string $this->m_atividade - ramo de atividades para buscar clientes para lançamento
         * @return int $count - numero de lançamentos adicionados
         */
        public function add_massa_lancamento($atividade)
        {

                // busca lançamento
                $lanc = $this->select_lancamento();
                $lancPessoa = $lanc[0]['PESSOA'];

                $original = number_format((float)$lanc[0]['ORIGINAL'], 2, ',', '.');
                $multa = number_format((float)$lanc[0]['MULTA'], 2, ',', '.');
                $juros = number_format((float)$lanc[0]['JUROS'], 2, ',', '.');
                $adiantamento = number_format((float)$lanc[0]['ADIANTAMENTO'], 2, ',', '.');
                $desconto = number_format((float)$lanc[0]['DESCONTO'], 2, ',', '.');
                $this->setOriginal($original);
                $this->setMulta($multa);
                $this->setJuros($juros);
                $this->setAdiantamento($adiantamento);
                $this->setDesconto($desconto);

                // busca pessoas por atividade
                $pessoa = new c_conta;
                $letra = "||||||" . $atividade;
                $arrPessoa = $pessoa->select_pessoa_letra($letra);
                $count = 0;
                for ($i = 0; $i < count($arrPessoa); $i++) {
                        if ($arrPessoa[$i]['CLIENTE'] != $lancPessoa) {
                                $count++;
                                $this->setPessoa($arrPessoa[$i]['CLIENTE']);
                                $this->setOriginal($original);
                                $this->setMulta($multa);
                                $this->setJuros($juros);
                                $this->setAdiantamento($adiantamento);
                                $this->setDesconto($desconto);
                                $this->incluiLancamento();
                        }
                }
                return $count;
        } //fim add_massa_lancamento

        /**
         * @name add_parc_lancamento
         * @description adiciona parcelas adcionais na tabela lancamento com os dados do lancamento atual
         * @param int $this->getQuantParc - quantidade de parcelas adicionais
         * @return int $count - numero de parcelas adicionadas
         */
        public function add_parc_lancamento()
        {


                list($dia, $mes, $ano) = explode("/", $this->getVencimento("F"));

                //set para as var local para o for
                // $original = number_format((float)$this->getOriginal('B'), 2, ',', '.');
                // $multa = number_format((float)$this->getMulta('B'), 2, ',', '.');
                // $juros = number_format((float)$this->getJuros('B'), 2, ',', '.');
                // $adiantamento = number_format((float)$this->getAdiantamento('B'), 2, ',', '.');
                // $desconto = number_format((float)$this->getDesconto('B'), 2, ',', '.');
                //  echo "original".$this->getOriginal('B')."multa".$this->getMulta('F')."juros".$this->getJuros('F').
                //        "adiantamento".$this->getAdiantamento('F')."desconto".$this->getDesconto('F');

                $original = $this->getOriginal();
                $multa = $this->getMulta();
                $juros = $this->getJuros();
                $adiantamento = $this->getAdiantamento();
                $desconto = $this->getDesconto();

                $count = 0;
                for ($i = 0; $i < $this->getQuantParc(); $i++) {
                        $count++;
                        $mes = $mes + 1;
                        if (($mes == 2) and ($dia >= 30)) {
                                $diatemp = 28;
                        } else {
                                $diatemp = $dia;
                        }
                        if ($mes == 13) {
                                $mes = '01';
                                $ano = $ano + 1;
                        }
                        $this->setVencimento($diatemp . "/" . $mes . "/" . $ano);
                        $this->setMovimento($diatemp . "/" . $mes . "/" . $ano);
                        $this->setParcela($this->getParcela() + 1);
                        // $this->setSitdocto("V");
                        $this->setOriginal($original, false);
                        $this->setMulta($multa, false);
                        $this->setJuros($juros, false);
                        $this->setAdiantamento($adiantamento, false);
                        $this->setDesconto($desconto, false);

                        $this->incluiLancamento(null, 'addparcela');
                }
                return $count;
        } //fim add_parc_lancamento

        /**
         * @name select_lancamento_geral
         * @description busca todos os lancamento independente de parametros digitados no form
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function select_lancamento_geral()
        {
                $sql  = "SELECT DISTINCT * ";
                $sql .= "FROM FIN_LANCAMENTO where emissao > '01/01/2010' ";
                $sql .= "ORDER BY lancamento ";
                //	ECHO $sql;
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_lancamento_geral

        /**
         * @name select_lancamento_geral
         * @description busca todos os lancamento independente de parametros digitados no form
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        static public function select_lancamento_doc($origem, $doc, $conn = null)
        {
                $sql  = "SELECT PARCELA, VENCIMENTO, TOTAL AS VALOR, CONTA, TIPODOCTO, SITPGTO, OBS ";
                $sql .= "FROM FIN_LANCAMENTO where (origem = '" . $origem . "') and (numlcto='" . $doc . "') and (sitpgto <> 'C') ";
                //MARCIO   	$sql .= "FROM FIN_LANCAMENTO where (origem = '".$origem."') and (docto='".$doc."') ";
                $sql .= "ORDER BY parcela ";

                //	ECHO $sql;
                $banco = new c_banco;
                $banco->exec_sql($sql, $conn);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_lancamento_geral

        public function select_lancamento_doc_tipodocto($origem, $doc, $conn = null)
        {
                $sql  = "SELECT Sum(TOTAL) AS VALOR, ";
                $sql .= "CASE TIPODOCTO WHEN 'D' THEN 'D' WHEN 'A' THEN 'D' ELSE TIPODOCTO END as TIPODOCTO ";
                // MARCIO   	$sql .= "FROM FIN_LANCAMENTO where (origem = '".$origem."') and (docto='".$doc."') ";
                $sql .= "FROM FIN_LANCAMENTO where (origem = '" . $origem . "') and (NUMLCTO='" . $doc . "') and sitpgto <> 'C' ";
                $sql .= "GROUP BY TIPODOCTO ";

                $banco = new c_banco;
                $banco->exec_sql($sql, $conn);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_lancamento_geral

        /**
         * @name select_lancamento_x_pedido_letra
         * @description busca lancamento de acrodo com informacoes digitados no form
         * @param string $letra - parametros digitados no form para consulta sql
         *        int total = 0 resumo por genero e descricao
         *            total = 1 classifica pela data escolhida  
         *            total = 2 classifca por genero e data de emissao
         *            total = 3 consulta centro custo rateio
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function select_lancamento_x_pedido_letra($letra, $total = 0, $conta = 0)
        {

                $par = explode("|", $letra);
                $dataIni = c_date::convertDateTxt($par[0]);
                $dataFim = c_date::convertDateTxt($par[1]);

                //echo "letra: ".$letra;
                $sqlGenero = "SELECT g.genero, g.descricao, sum(a.total) as total FROM FIN_LANCAMENTO a ";
                $sqlGenero .= "inner join fin_genero g on g.genero = a.genero ";
                // $sqlCentrocusto = "SELECT r.Centrocusto, r.descricao,  sum(a.total) as total FROM FIN_LANCAMENTO a ";
                // $sqlCentrocusto .= "inner join fin_Centrocusto r on r.Centrocusto = a.Centrocusto ";
                if ($total == 3) {
                        $sqln  = "SELECT X.CENTROCUSTO AS CC, Y.SALDO AS SALDOCC, (a.total * (x.percentual / 100)) as totalrateio, x.percentual, a.*, c.nomereduzido, c.nome, c.cidade, s.padrao as situacaopgto, r.descricao as filial, t.padrao as tipolancamento, g.descricao as descgenero, r.descricao as desccentrocusto, ";
                        $sqln .= "c.pessoa, c.cnpjcpf, CONCAT(c.tipoend,' ',c.tituloend,' ',c.endereco,',',c.numero) as endereco, C.CEP ";
                        $sqln .= "FROM FIN_LANCAMENTO a ";
                        $sqln .= "inner join fin_lancamento_rateio x on (a.id = x.id) and (x.percentual > 0) ";
                        $sqln .= "inner join fin_centro_custo r on x.centrocusto = r.centrocusto ";
                        $sqln .= "left join fin_centro_custo_saldo y on (y.centrocusto = x.centrocusto) and (y.data ='" . $dataIni . "') ";
                } else {
                        $sqln  = "SELECT a.*, p.DATAENTREGA, p.PRAZOENTREGA, p.PRAZOENTREGA, ";
                        $sqln .= "IF(p.DATAENTREGA <> NULL, 'ENTREGUE', 'A ENTREGAR') AS SITUACAOPED, ";
                        $sqln .= "c.nomereduzido, c.nome, c.cidade, s.padrao as situacaopgto, r.descricao as filial, t.padrao as tipolancamento, g.descricao as descgenero, r.descricao as desccentrocusto, ";
                        $sqln .= "c.pessoa, c.cnpjcpf, CONCAT(c.tipoend,' ',c.tituloend,' ',c.endereco,',',c.numero) as endereco, c.CEP ";
                        $sqln .= "FROM FIN_LANCAMENTO a ";
                        $sqln .= "inner join fin_centro_custo r on a.centrocusto = r.centrocusto ";
                }
                $sqln .= "inner join fin_cliente c on c.cliente = a.pessoa ";
                $sqln .= "left join fin_genero g on g.genero = a.genero ";
                $sqln .= "left join fat_pedido p on (a.DOCTO = p.ID) ";
                $sqln .= "inner join amb_ddm s on ((s.alias='FIN_MENU') and (s.campo='SituacaoPgto') and (s.tipo = a.sitpgto)) ";
                $sqln .= "inner join amb_ddm t on ((t.alias='FIN_MENU') and (t.campo='TipoLanc') and (t.tipo = a.tipolancamento)) ";
                $sqln .= " ";
                if (array_sum($par) > 0) {
                        $sql .= "WHERE ";
                        if ($par[3] != 'nao') {
                                $sql .= "(a." . $par[3] . " >= '" . $dataIni . "') and (a." . $par[3] . " <= '" . $dataFim . "') AND (a.SERIE = 'PED') ";
                        }

                        if (($par[2] != '') and ($par[2] != 0)) {
                                if ($par[3] != 'nao') {
                                        $sql .= " AND ";
                                }
                                $sql .= "(a.pessoa = " . $par[2] . ") ";
                        }

                        // sit lancamento
                        if ($par[4] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '')) {
                                        $sql .= " AND ";
                                }
                                $i = 5;
                                $sql .= "(a.sitpgto in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[4] + 4)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // filial
                        $posFilial = 5 + $par[4];
                        if ($par[$posFilial] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posFilial + 1;
                                if ($total == 3) {
                                        $sql .= "(x.centrocusto in (" . $par[$i];
                                } else {
                                        $sql .= "(a.centrocusto in (" . $par[$i];
                                }
                                $i++;
                                while ($i <= ($par[$posFilial] + $posFilial)) {
                                        $sql .= "," . $par[$i];
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // tipo lancamento
                        $posTipoLanc = $posFilial + $par[$posFilial] + 1;
                        if ($par[$posTipoLanc] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[$posFilial] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posTipoLanc + 1;
                                $sql .= "(a.tipolancamento in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[$posTipoLanc] + $posTipoLanc)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // situacao documento
                        $posSitDocto = $posTipoLanc + $par[$posTipoLanc] + 1;
                        if ($par[$posSitDocto] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posTipoLanc] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posSitDocto + 1;
                                $sql .= "(a.sitdocto in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[$posSitDocto] + $posSitDocto)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // Conta
                        $posConta = $posSitDocto + $par[$posSitDocto] + 1;
                        if ($par[$posConta] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posSitDocto] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posConta + 1;
                                $this->contaIntBancaria = $par[$i];
                                $sql .= "(a.conta in (" . $par[$i];
                                $i++;
                                while ($i <= ($par[$posConta] + $posConta)) {
                                        $sql .= "," . $par[$i] . " ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // Genero
                        $posGenero = $posConta + $par[$posConta] + 1;
                        if (($par[$posGenero] != '') && ($par[$posGenero] != '0')) {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posConta] != '0')) {
                                        $sql .= " AND ";
                                }
                                $sql .= "(a.genero = " . $par[$posGenero];
                                $sql .= ") ";
                        }
                }

                //TIPO DOCUMENTO
                $posTipoDocto = $posGenero + 1;
                if ($par[$posTipoDocto] != '0') {
                        if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posGenero] != '0')) {
                                $sql .= " AND ";
                        }
                        $i = $posTipoDocto + 1;
                        $sql .= "(a.tipodocto in ('" . $par[$i] . "'";
                        $i++;
                        while ($i <= ($par[$posTipoDocto] + $posTipoDocto)) {
                                $sql .= ",'" . $par[$i] . "' ";
                                $i++;
                        }
                        $sql .= ")) ";
                }

                // if ($total==3) { //consulta centro custo
                //         $sqlBanco = $sqln.$sql.= "ORDER BY r.centrocusto, a.genero, a.emissao";
                //         $sqlBanco = $sqlCentrocusto."group by r.centrocusto, g.genero;"; 
                //     }
                //     elseif ($total==2) { //consulta genero
                //         $sqlBanco = $sqln.$sql.= "ORDER BY a.genero, a.emissao";
                //         $sqlBanco = $sqlGenero."group by g.genero, g.descricao;"; }
                //     else{
                //             if ($par[3] != 'nao'){
                //                     $sqlBanco = $sqln.$sql.= "ORDER BY a.".$par[3];}
                //             else{	 
                //                     $sqlBanco = $sqln.$sql.= "ORDER BY a.pagamento";}
                //     }

                if ($total == 3) { //consulta genero
                        $sqlBanco = $sqln . $sql .= "ORDER BY x.centrocusto, a.genero, a.emissao";
                } elseif ($total == 2) { //consulta genero
                        $sqlBanco = $sqln . $sql .= "ORDER BY a.genero, a.emissao";
                } elseif ($total != 0) {
                        $sqlBanco = $sqlGenero . $sql . "group by g.genero, g.descricao;";
                } else {
                        if ($par[3] != 'nao') {
                                // $sqlBanco = $sqln.$sql.= "ORDER BY a.".$par[3];
                                $sqlBanco = $sqln . $sql .= "ORDER BY p.DATAENTREGA, a." . $par[3];
                        } else {
                                $sqlBanco = $sqln . $sql .= "ORDER BY a.pagamento";
                        }
                }

                //ECHO strtoupper($sqlBanco);

                $banco = new c_banco;
                $banco->exec_sql($sqlBanco);
                $banco->close_connection();
                return $banco->resultado;
        } // fim select_lancamento_letra

        /**
         * @name select_lancamento_letra
         * @description busca lancamento de acrodo com informacoes digitados no form
         * @param string $letra - parametros digitados no form para consulta sql
         *        int total = 0 resumo por genero e descricao
         *            total = 1 classifica pela data escolhida  
         *            total = 2 classifca por genero e data de emissao
         *            total = 3 consulta centro custo rateio
         * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
         */
        public function select_lancamento_letra($letra, $total = 0, $conta = 0)
        {

                $par = explode("|", $letra) ?? [];
                $dataIni = c_date::convertDateTxt($par[0]);
                $dataFim = c_date::convertDateTxt($par[1]);
                if ($par[3] != 'nao')
                        $dateOrder = $par[3];
                else
                        $dateOrder = 'VENCIMENTO';

                //echo "letra: ".$letra;
                $sqlGenero = "SELECT g.genero, g.descricao,  sum(a.total) as total FROM FIN_LANCAMENTO a ";
                $sqlGenero .= "inner join fin_genero g on g.genero = a.genero ";
                // $sqlCentrocusto = "SELECT r.Centrocusto, r.descricao,  sum(a.total) as total FROM FIN_LANCAMENTO a ";
                // $sqlCentrocusto .= "inner join fin_Centrocusto r on r.Centrocusto = a.Centrocusto ";
                if ($total == 3) {
                        $sqln  = "SELECT X.CENTROCUSTO AS CC, Y.SALDO AS SALDOCC, (a.total * (x.percentual / 100)) as totalrateio, x.percentual, a.*, c.nomereduzido, c.nome, c.cidade, s.padrao as situacaopgto, r.descricao as filial, t.padrao as tipolancamento, g.descricao as descgenero, r.descricao as desccentrocusto, ";
                        $sqln .= "c.pessoa, c.cnpjcpf, CONCAT(c.tipoend,' ',c.tituloend,' ',c.endereco,',',c.numero) as endereco, C.CEP ";
                        $sqln .= "FROM FIN_LANCAMENTO a ";
                        $sqln .= "inner join fin_lancamento_rateio x on (a.id = x.id) and (x.percentual > 0) ";
                        $sqln .= "inner join fin_centro_custo r on x.centrocusto = r.centrocusto ";
                        $sqln .= "left join fin_centro_custo_saldo y on (y.centrocusto = x.centrocusto) and (y.data ='" . $dataIni . "') ";
                } else {
                        $sqln  = "SELECT a.*, a.pessoa as pessoaId, c.nomereduzido, c.nome, c.cidade, s.padrao as situacaopgto, r.descricao as filial, t.padrao as tipolancamento, g.descricao as descgenero, r.descricao as desccentrocusto, ";
                        $sqln .= "c.pessoa, c.cnpjcpf, CONCAT(c.tipoend,' ',c.tituloend,' ',c.endereco,',',c.numero) as endereco, C.CEP, a." . $dateOrder . " AS DATEORDER, '" . $dateOrder . "' AS FIELDORDER, u_insert.nomereduzido AS NOMEREDUZIDO_INSERT, u_alter.nomereduzido AS NOMEREDUZIDOALTERACAO  ";
                        $sqln .= "FROM FIN_LANCAMENTO a ";
                        $sqln .= "inner join fin_centro_custo r on a.centrocusto = r.centrocusto ";
                }
                $sqln .= "inner join fin_cliente c on c.cliente = a.pessoa ";
                $sqln .= "left join fin_genero g on g.genero = a.genero ";
                $sqln .= "inner join amb_ddm s on ((s.alias='FIN_MENU') and (s.campo='SituacaoPgto') and (s.tipo = a.sitpgto)) ";
                $sqln .= "inner join amb_ddm t on ((t.alias='FIN_MENU') and (t.campo='TipoLanc') and (t.tipo = a.tipolancamento)) ";
                $sqln .= "inner join amb_usuario u_insert on a.userinsert = u_insert.usuario ";
                $sqln .= "LEFT JOIN amb_usuario u_alter ON a.userchange = u_alter.usuario ";
                $sqln .= " ";
                if (array_sum($par) > 0) {
                        $sql .= "WHERE ";
                        if ($par[3] != 'nao') {
                                $sql .= "(a." . $par[3] . " >= '" . $dataIni . "') and (a." . $par[3] . " <= '" . $dataFim . "') ";
                        }

                        if (($par[2] != '') and ($par[2] != 0)) {
                                if ($par[3] != 'nao') {
                                        $sql .= " AND ";
                                }
                                $sql .= "(a.pessoa = " . $par[2] . ") ";
                        }

                        // sit lancamento
                        if ($par[4] != '0') {
                                if (($par[3] != 'nao') or (($par[2] != '') and ($par[2] != 0))) {
                                        $sql .= " AND ";
                                }
                                $i = 5;
                                $sql .= "(a.sitpgto in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[4] + 4)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // filial
                        $posFilial = 5 + $par[4];
                        if ($par[$posFilial] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posFilial + 1;
                                if ($total == 3) {
                                        $sql .= "(x.centrocusto in (" . $par[$i];
                                } else {
                                        $sql .= "(a.centrocusto in (" . $par[$i];
                                }
                                $i++;
                                while ($i <= ($par[$posFilial] + $posFilial)) {
                                        $sql .= "," . $par[$i];
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // tipo lancamento
                        $posTipoLanc = $posFilial + $par[$posFilial] + 1;
                        if ($par[$posTipoLanc] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[$posFilial] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posTipoLanc + 1;
                                $sql .= "(a.tipolancamento in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[$posTipoLanc] + $posTipoLanc)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // situacao documento
                        $posSitDocto = $posTipoLanc + $par[$posTipoLanc] + 1;
                        if ($par[$posSitDocto] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posTipoLanc] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posSitDocto + 1;
                                $sql .= "(a.sitdocto in ('" . $par[$i] . "'";
                                $i++;
                                while ($i <= ($par[$posSitDocto] + $posSitDocto)) {
                                        $sql .= ",'" . $par[$i] . "' ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // Conta
                        $posConta = $posSitDocto + $par[$posSitDocto] + 1;
                        if ($par[$posConta] != '0') {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posSitDocto] != '0')) {
                                        $sql .= " AND ";
                                }
                                $i = $posConta + 1;
                                $this->contaIntBancaria = $par[$i];
                                $sql .= "(a.conta in (" . $par[$i];
                                $i++;
                                while ($i <= ($par[$posConta] + $posConta)) {
                                        $sql .= "," . $par[$i] . " ";
                                        $i++;
                                }
                                $sql .= ")) ";
                        }

                        // Genero
                        $posGenero = $posConta + $par[$posConta] + 1;
                        if (($par[$posGenero] != '') && ($par[$posGenero] != '0')) {
                                if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posConta] != '0')) {
                                        $sql .= " AND ";
                                }
                                $sql .= "(a.genero = " . $par[$posGenero];
                                $sql .= ") ";
                        }
                }

                //TIPO DOCUMENTO
                $posTipoDocto = $posGenero + 1;
                if ($par[$posTipoDocto] != '0') {
                        if (($par[3] != 'nao') or ($par[2] != '') or ($par[4] != '0') or ($par[5] != '0') or ($par[$posGenero] != '0')) {
                                $sql .= " AND ";
                        }
                        $i = $posTipoDocto + 1;
                        $sql .= "(a.tipodocto in ('" . $par[$i] . "'";
                        $i++;
                        while ($i <= ($par[$posTipoDocto] + $posTipoDocto)) {
                                $sql .= ",'" . $par[$i] . "' ";
                                $i++;
                        }
                        $sql .= ")) ";
                }

                // if ($total==3) { //consulta centro custo
                //         $sqlBanco = $sqln.$sql.= "ORDER BY r.centrocusto, a.genero, a.emissao";
                //         $sqlBanco = $sqlCentrocusto."group by r.centrocusto, g.genero;"; 
                //     }
                //     elseif ($total==2) { //consulta genero
                //         $sqlBanco = $sqln.$sql.= "ORDER BY a.genero, a.emissao";
                //         $sqlBanco = $sqlGenero."group by g.genero, g.descricao;"; }
                //     else{
                //             if ($par[3] != 'nao'){
                //                     $sqlBanco = $sqln.$sql.= "ORDER BY a.".$par[3];}
                //             else{	 
                //                     $sqlBanco = $sqln.$sql.= "ORDER BY a.pagamento";}
                //     }

                if ($total == 3) { //consulta genero
                        if ($par[3] != 'nao')
                                $sqlBanco = $sqln . $sql .= "ORDER BY x.centrocusto, " . $par[3];
                        else
                                $sqlBanco = $sqln . $sql .= "ORDER BY x.centrocusto, a.vencimento";
                } elseif ($total == 2) { //consulta genero
                        if ($par[3] != 'nao')
                                $sqlBanco = $sqln . $sql .= "ORDER BY a.genero, " . $par[3];
                        else
                                $sqlBanco = $sqln . $sql .= "ORDER BY a.genero, a.vencimento";
                } elseif ($total != 0) {
                        $sqlBanco = $sqlGenero . $sql . "group by g.genero, g.descricao;";
                } else {
                        if ($par[3] != 'nao') {
                                $sqlBanco = $sqln . $sql .= "ORDER BY a." . $par[3];
                        } else {
                                $sqlBanco = $sqln . $sql .= "ORDER BY a.pagamento";
                        }
                }

                //ECHO strtoupper($sqlBanco);

                $banco = new c_banco;
                $banco->exec_sql($sqlBanco);
                $banco->close_connection();
                return $banco->resultado;
        } // fim select_lancamento_letra

        /**
         * @name incluiLancamento
         * @description faz a inclusão do registro cadastrado
         * @return bool true se inclusao ocorreu com sucesso
         *         string mensagem informando que não foi realizado a inclusao
         */
        public function incluiLancamento($conn = null, $param = null)
        {

                //  echo "passou aqui = ".  $this->getOriginal('F');
                $banco = new c_banco;
                // echo "passou";
                $this->setUsrsitpgto($this->m_userid);
                $this->setUsraprovacao($this->m_userid);
                $cc = $this->getCentroCusto();
                $origem = $this->getOrigem();


                if ($banco->gerenciadorDB == 'interbase') {
                        $this->setId($banco->geraID("FIN_GEN_ID_DOCTO_PAG"));
                        $sql  = "INSERT INTO FIN_LANCAMENTO (ID, ";
                } else {
                        $sql  = "INSERT INTO FIN_LANCAMENTO (";
                }


                $sql  .= "PESSOA,
			DOCTO,
  			SERIE,
  			PARCELA,
  			TIPODOCTO,
  			SITDOCTO,
  			SITPGTO,
  			USRSITPGTO,
  			MODOPGTO,
  			DOCBANCARIO,
  			CONTA,
  			CHEQUE,
  			USRAPROVACAO,
  			GENERO,
  			CENTROCUSTO,
  			LANCAMENTO,
  			EMISSAO,
  			VENCIMENTO,
  			PAGAMENTO,
  			ORIGINAL,
  			MULTA,
  			JUROS,
  			ADIANTAMENTO,
  			DESCONTO,
  			TOTAL,
  			MOEDA,
  			ORIGEM,
  			NUMLCTO,
  			OBS,
                        OBSCONTABIL,
                        REMESSANUM,
                        NOSSONUMERO,
                        REMESSADATA,
                        REMESSAARQ,
                        RETORNOARQ,
                        RETORNOCOD,
  			TIPOLANCAMENTO, USERINSERT, DATEINSERT
		
		)";

                if ($banco->gerenciadorDB == 'interbase') {
                        $sql .= "VALUES (" . $this->getId() . ", ";
                } else {
                        $sql .= "VALUES (";
                }

                if ($param == null) {
                        $sql .= $this->getPessoa() . ", "
                                . $this->getDocto() . ", '"
                                . $this->getSerie() . "', '"
                                . $this->getParcela() . "', '"
                                . $this->getTipodocto() . "', '"
                                . $this->getSitdocto() . "', '"
                                . $this->getSitpgto() . "', "
                                . $this->getUsrsitpgto() . ", '"
                                . $this->getModopgto() . "', '"
                                . $this->getDocbancario() . "', "
                                . $this->getConta() . ", '"
                                . $this->getCheque() . "', "
                                . $this->getUsraprovacao() . ", '"
                                . $this->getGenero() . "', "
                                . $this->getCentroCusto() . ", '"
                                . $this->getLancamento('B') . "', '"
                                . $this->getEmissao('B') . "', '"
                                . $this->getVencimento('B') . "', '"
                                . $this->getMovimento('B') . "', '"
                                . $this->getOriginal('B') . "', "
                                . $this->getMulta('B') . ", "
                                . $this->getJuros('B') . ", "
                                . $this->getAdiantamento('B') . ", "
                                . $this->getDesconto('B') . ", "
                                . $this->getTotal('B') . ", "
                                . $this->getMoeda() . ", '"
                                . $this->getOrigem() . "', "
                                . $this->getNumlcto() . ", '"
                                . $this->getObs() . "', '"
                                . $this->getObsContabil() . "', ";
                } else {
                        $sql .= $this->getPessoa() . ", "
                                . $this->getDocto() . ", '"
                                . $this->getSerie() . "', '"
                                . $this->getParcela() . "', '"
                                . $this->getTipodocto() . "', '"
                                . $this->getSitdocto() . "', '"
                                . $this->getSitpgto() . "', "
                                . $this->getUsrsitpgto() . ", '"
                                . $this->getModopgto() . "', '"
                                . $this->getDocbancario() . "', "
                                . $this->getConta() . ", '"
                                . $this->getCheque() . "', "
                                . $this->getUsraprovacao() . ", '"
                                . $this->getGenero() . "', "
                                . $this->getCentroCusto() . ", '"
                                . $this->getLancamento('B') . "', '"
                                . $this->getEmissao('B') . "', '"
                                . $this->getVencimento('B') . "', '"
                                . $this->getMovimento('B') . "', '"
                                . $this->getOriginal() . "', "
                                . $this->getMulta() . ", "
                                . $this->getJuros() . ", "
                                . $this->getAdiantamento() . ", "
                                . $this->getDesconto() . ", "
                                . $this->getTotal(null, 'addparcela') . ", "
                                . $this->getMoeda() . ", '"
                                . $this->getOrigem() . "', "
                                . $this->getNumlcto() . ", '"
                                . $this->getObs() . "', '"
                                . $this->getObsContabil() . "', ";
                }



                if ($this->getRemessaNum() == '0' || $this->getRemessaNum() == '') {
                        $sql .= "null, null, null, null, null, null, '";
                } else {
                        $sql .= $this->getRemessaNum() . ", " . $this->getNossoNumero() . ", ";
                        $sql .= $this->getRemessaData('B') . ", " . $this->getRemessaArq() . ", ";
                        $sql .= $this->getRetornoArq() . ", " . $this->getRetornoCod() . ", '";
                }
                $sql .= $this->getTipolancamento() . "'," . $this->m_userid . ",'" . date("Y-m-d H:i:s") . "'); ";
                //    echo $this->getID.$sql;

                // echo strtoupper($sql)."<BR>";
                $res_pessoa =  $banco->exec_sql($sql, $conn);
                $this->idInsert = $banco->insertReg;

                if ($banco->result):
                        $lastReg = $banco->insertReg;
                        $banco->close_connection();
                        if ($origem != 'FIN' or $_POST["submenu"] == 'addparcela') {
                                $this->m_rateioCC = $cc . ' - 1 - 100';
                                $this->incluirRateio($lastReg, $this->m_rateioCC, $conn);
                        }
                        return $lastReg;
                else:
                        $banco->close_connection();
                        return 'Os dados do Lançamento ' . $this->getDocto() . ' não foram cadastrados!';
                endif;


                // 		if($res_pessoa > 0){
                // 			/*if (($this->getSitdocto() != 'V') and ($this->getSitpgto() == 'B')){
                // 				$this->setPessoaNome();

                // 				$body  = "Confirma&ccedil;&atilde;o de Pagamento ".$this->getPessoaNome()."<font size=\"4\"> </font>, <p>";
                // 				$body .= "<i>Pagamento no Valor de </i>".$this->getTotal('F')."   <p>";
                // 				$body .= "Referente a ".$this->getGenero()." - ".$this->getDescGenero()." na data de ".$this->getMovimento('F');
                // 				$body .= "<br> Obs: ".$this->getObs();

                // 				$mail = new Mail;
                // //				$mail->SendMail("mail.admservice.com.br", "adm@admservice.com.br", $this->$m_empresanome, "adm=2013#-", 
                // 				$mail->SendMail("mail.admservice.com.br", "adm@admservice.com.br", $this->m_empresanome, "adm=2013#-", 
                // 						$body, "Confirmacao Pagamento",	$this->getPessoaEmail(), $this->getPessoaNome(), "", "");
                // 			}  */
                // 			return true;
                // 		}
                // 		else{
                // 			return 'Os dados do Lan&ccedil;amento '.$this->getNome().' no foram cadastrados!';
                // 	}
        } // fim incluiLancamento

        /**
         * @name alteraLancamento
         * @description altera registro existente
         * @param int $this->getId() Identificação do registro a ser alterado
         * @return string Null se alteração ocorreu com sucesso
         *         string mensagem informando que não foi realizado a alteração
         */
        public function alteraLancamento()
        {

                $this->setUsrsitpgto($this->m_userid);
                $this->setUsraprovacao($this->m_userid);

                $sql  = "UPDATE FIN_LANCAMENTO ";
                $sql .= "SET pessoa = " . $this->getPessoa() . ", ";
                $sql .= "docto = " . $this->getDocto() . ", ";
                $sql .= "docto = " . $this->getDocto() . ", ";
                $sql .= "serie = '" . $this->getSerie() . "', ";
                $sql .= "parcela = '" . $this->getParcela() . "', ";
                $sql .= "tipodocto = '" . $this->getTipodocto() . "', ";
                $sql .= "sitdocto = '" . $this->getSitdocto() . "', ";
                $sql .= "sitpgto = '" . $this->getSitpgto() . "', ";
                $sql .= "tipolancamento = '" . $this->getTipolancamento() . "', ";
                $sql .= "usrsitpgto = " . $this->getUsrsitpgto() . ", ";
                $sql .= "modopgto = '" . $this->getModopgto() . "', ";
                $sql .= "docbancario = '" . $this->getDocbancario() . "', ";
                $sql .= "conta = " . $this->getConta() . ", ";
                $sql .= "cheque = '" . $this->getCheque() . "', ";
                $sql .= "usraprovacao = " . $this->getUsraprovacao() . ", ";
                $sql .= "genero = '" . $this->getGenero() . "', ";
                $sql .= "centrocusto = " . $this->getCentroCusto() . ", ";
                $sql .= "lancamento = '" . $this->getLancamento('B') . "', ";
                $sql .= "emissao = '" . $this->getEmissao('B') . "', ";
                $sql .= "vencimento = '" . $this->getVencimento('B') . "', ";
                $sql .= "pagamento = '" . $this->getMovimento('B') . "', ";
                $sql .= "original = " . $this->getOriginal("B") . ", ";
                $sql .= "multa = '" . $this->getMulta("B") . "', ";
                $sql .= "juros = '" . $this->getJuros("B") . "', ";
                $sql .= "adiantamento = " . $this->getAdiantamento("B") . ", ";
                $sql .= "desconto = " . $this->getDesconto("B") . ", ";
                $sql .= "total = " . $this->getTotal('B') . ", ";
                $sql .= "moeda = " . $this->getMoeda() . ", ";
                $sql .= "obs = '" . $this->getObs() . "', ";
                $sql .= "obscontabil = '" . $this->getObsContabil() . "', ";
                if ($this->getRemessaNum() == '0' || $this->getRemessaNum() == '') {
                        $sql .= "REMESSANUM = null, ";

                        $sql .= "NOSSONUMERO = null, ";
                        $sql .= "REMESSADATA = null, ";
                        $sql .= "REMESSAARQ  = null, ";
                        $sql .= "RETORNOARQ  = null, ";
                        $sql .= "RETORNOCOD  = null, ";
                } else {
                        $sql .= "REMESSANUM ='" . $this->getRemessaNum() . "', ";

                        $sql .= "NOSSONUMERO ='" . $this->getRemessaNum() . "', ";
                        $sql .= "REMESSADATA ='" . $this->getRemessaData() . "', ";
                        $sql .= "REMESSAARQ ='" . $this->getRemessaArq() . "', ";
                        $sql .= "RETORNOARQ ='" . $this->getRetornoArq() . "', ";
                        $sql .= "RETORNOCOD ='" . $this->getRetornoCod() . "', ";
                }
                $sql .= "userchange = " . $this->m_userid . ", ";
                $sql .= "datechange = '" . date("Y-m-d H:i:s") . "' ";
                $sql .= "WHERE id = " . $this->getId() . ";";

                //echo strtoupper($sql)."<BR>";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();

                return $banco->result;
        }  // fim alteraLancamento

        /**
         * @name excluiLancamento
         * @description Exclui registro existente
         * @param int $this->getId() Identificação do registro a ser excluido
         * @return string Null se alteração ocorreu com sucesso
         *         string mensagem informando que não foi realizado a alteração
         */
        public function excluiLancamento()
        {

                $banco = new c_banco;

                $sql  = "DELETE FROM FIN_LANCAMENTO_RATEIO ";
                $sql  .= "WHERE (ID = " . $this->getId() . ")";
                $banco->exec_sql($sql);

                $sql  = "DELETE FROM FIN_LANCAMENTO ";
                $sql .= "WHERE id = " . $this->getId();
                //echo $sql;
                $res_lancamento =  $banco->exec_sql($sql);
                $banco->close_connection();


                if ($res_lancamento > 0) {

                        return '';
                } else {
                        return 'Os dados do Lan&ccedil;amento ' . $this->getId() . ' n&atilde;o foram excluidos!';
                }
        }  // fim excluiLancamento

        public function incluirRateio($ID, $rateioCC, $conn = null)
        {
                $banco = new c_banco;
                $centros_de_custo = explode("|", $rateioCC);
                foreach ($centros_de_custo as $centro_de_custo) {
                        $centro = explode("-", $centro_de_custo);
                        $perc = str_replace(",", ".", trim($centro[2]));
                        if (($centro[0] != '') and ($perc > 0)) {
                                $cc = "'" . $ID . "',";
                                $cc .= "'" . trim($centro[0]) . "',";
                                //$cc .= "'".trim($centro[1])."',";
                                $cc .= "'" . $perc . "'";
                                $sql  = "INSERT INTO FIN_LANCAMENTO_RATEIO (";
                                $sql  .= "ID, CENTROCUSTO,PERCENTUAL )";
                                $sql .= "VALUES (";
                                $sql .= $cc . ")";
                                $banco->exec_sql($sql, $conn);
                        }
                }
                return '';
        } // fim incluirRateio

        public function select_rateio_id($tipoConsulta = NULL)
        {

                $sql  = "SELECT distinct(C.CENTROCUSTO), C.Descricao, CC.Percentual, " . $this->getId() . " as ID ";
                $sql  .= "FROM FIN_CENTRO_CUSTO C LEFT JOIN FIN_LANCAMENTO_RATEIO CC ON (C.centrocusto=CC.centrocusto) ";
                $sql  .= "WHERE CC.id = " . $this->getId() . " UNION ";
                $sql  .= "SELECT distinct(C.CENTROCUSTO), C.Descricao, 0 as Percentual, " . $this->getId() . " as ID ";
                $sql  .= "FROM FIN_CENTRO_CUSTO C ";
                $sql  .= "where c.centrocusto not in (";
                $sql  .= "SELECT distinct(CC.CENTROCUSTO) FROM FIN_LANCAMENTO_RATEIO CC WHERE CC.id = " . $this->getId() . ");";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        /**
         * @name alteraLancBaixado
         * @description altera registro existente para baixado
         * @param int $this->getId() Identificação do registro a ser alterado
         * @return string Null se alteração ocorreu com sucesso
         *         string mensagem informando que não foi realizado a alteração
         */
        public function alteraLancBaixado()
        {

                $sql  = "UPDATE FIN_LANCAMENTO ";
                $sql .= "SET ";
                $sql .= "conta = " . $this->getConta() . ", ";
                $sql .= "pagamento = '" . $this->getMovimento('B') . "', ";
                $sql .= "SITPGTO = 'B', ";
                $sql .= "OBSCONTABIL = 'BAIXA EM LOTE DATA: " . date("Y-m-d H:i:s") . " - USUÁRIO: " . $this->m_userid . "', ";
                $sql .= "userchange = " . $this->m_userid . ", ";
                $sql .= "datechange = '" . date("Y-m-d H:i:s") . "' ";
                $sql .= "WHERE id = " . $this->getId() . ";";

                $banco = new c_banco;
                $res_lancamento =  $banco->exec_sql($sql);
                $banco->close_connection();

                if ($res_lancamento > 0) {
                        return '';
                } else {
                        return 'Os dados do Lan&ccedil;amento ' . $this->getDesc() . ' n&atilde;o foram alterados!';
                }
        }  // fim alteraLancBaixado

        public function select_titulos_agrupados($docto)
        {

                $sql  = "SELECT * ";
                $sql  .= "FROM FIN_LANCAMENTO  ";
                $sql  .= "WHERE AGRUPAMENTO = '" . $docto . "'  ";


                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }


        public function select_rateio_cc($tipoConsulta = NULL)
        {
                $sql  = "SELECT CentroCusto, Descricao, 0 as Percentual ";
                $sql .= "FROM FIN_CENTRO_CUSTO WHERE NIVEL = 2";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        public function deletarRateioCC()
        {

                $banco = new c_banco;

                $sql  = "DELETE FROM FIN_LANCAMENTO_RATEIO ";
                $sql  .= "WHERE (ID = " . $this->getId() . ")";
                $banco->exec_sql($sql, $conn);


                return $this->getId();
        }  // fim deletarRateioCC

        public function selecTableCredito($cliente)
        {

                $sql  = "SELECT * FROM FIN_CLIENTE_CREDITO ";
                $sql .= "WHERE (UTILIZADO <> VALOR) and (CLIENTE = '" . $cliente . "') ;";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        public function selectSaldoCliente($cliente)
        {

                $sql  = "SELECT * FROM FIN_CLIENTE_CREDITO ";
                $sql .= "WHERE ISNULL(PEDIDOUTILIZADO) and (CLIENTE = '" . $cliente . "') ;";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        public function updateTableCredito($cliente, $valor, $pedido, $arrCredito, $conn)
        {
                for ($i = 0; $i < count($arrCredito); $i++) {

                        $utilizado = ($arrCredito[$i]['VALOR'] - $arrCredito[$i]['UTILIZADO']);
                        if ($valor < $utilizado) {
                                $utilizado = $valor;
                        }


                        $sql  = "UPDATE FIN_CLIENTE_CREDITO ";
                        $sql .= "SET UTILIZADO = UTILIZADO + " . $utilizado . ", ";
                        $sql .= "PEDIDOUTILIZADO = CONCAT(IFNULL(PEDIDOUTILIZADO,''), ';', '" . $pedido . "') ";
                        $sql .= "WHERE (ID = '" . $arrCredito[$i]['ID'] . "') ;";
                        $banco = new c_banco;
                        $res =  $banco->exec_sql($sql, $conn);
                        $banco->close_connection();
                        $valor = $valor - $utilizado;
                }
        }

        public function newUpdateTableCredito($cliente, $valor, $pedido, $arrCredito, $conn, $utilizado)
        {

                for ($i = 0; $i < count($arrCredito); $i++) {
                        $vlrRestante = false;

                        if ($utilizado > $arrCredito[$i]['VALOR']) {
                                $vlrUtilizado = $arrCredito[$i]['VALOR'];
                                $utilizado = $utilizado - $arrCredito[$i]['VALOR'];
                        } else if ($utilizado < $arrCredito[$i]['VALOR']) {
                                $vlrUtilizado =  $utilizado;
                                $utilizado = $arrCredito[$i]['VALOR'] - $utilizado;
                                $vlrRestante = true;
                        } else {
                                $vlrUtilizado =  $utilizado;
                        }


                        $sql  = "UPDATE FIN_CLIENTE_CREDITO ";
                        $sql .= "SET UTILIZADO = '" . $vlrUtilizado . "', ";
                        $sql .= "PEDIDOUTILIZADO = '" . $pedido . "' ";
                        $sql .= "WHERE (ID = '" . $arrCredito[$i]['ID'] . "') ;";
                        $banco = new c_banco;
                        $banco->exec_sql($sql, $conn);
                        $banco->close_connection();

                        if ($vlrRestante == true) {
                                $sql = "INSERT INTO FIN_CLIENTE_CREDITO ";
                                $sql .= "(CLIENTE, PEDIDO, NRITEM, QUANTIDADE, UNITARIO, VALOR) VALUES ";
                                $sql .= "('" . $arrCredito[$i]['CLIENTE'] . "', '" . $arrCredito[$i]['PEDIDO'] . "', '";
                                $sql .= $arrCredito[$i]['NRITEM'] . "', '" . $arrCredito[$i]['QUANTIDADE'] . "', '";
                                $sql .= $arrCredito[$i]['UNITARIO'] . "', '" . $utilizado . "' )";
                                $banco = new c_banco;
                                $banco->exec_sql($sql, $conn);
                                $banco->close_connection();

                                // encerrar o laço 
                                $i = count($arrCredito);
                        }
                }
        }

        static public function somaTotalDocBaixado($pessoa, $doc, $origem)
        {

                $sql  = "SELECT Sum(Total) as TOTAL ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (pessoa = " . $pessoa . ") AND ";
                $sql .= "(docto = '" . $doc . "') and (serie = '" . $origem . "') and (sitpgto = 'B')";
                //ECHO $sql;

                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim existeDocumento

        public function atualizarField($field, $valor, $tabela, $conn = null)
        {
                $sql = "UPDATE  " . $tabela . " ";
                if ($valor == 'NULL') {
                        $sql .= "SET " . $field . " = " . $valor . " ";
                } else {
                        $sql .= "SET " . $field . " = '" . $valor . "' ";
                }
                $sql .= "WHERE (id = '" . $this->getId() . "');";

                $banco = new c_banco;
                $banco->exec_sql($sql, $conn);
                $banco->close_connection();
        }

        public function buscaCadastroLancamentoAdd()
        {
                $lanc = $this->select_lancamento();

                $this->setPessoa($lanc[0]['PESSOA']);
                $this->setPessoaNome();
                $this->setDocto($lanc[0]['DOCTO']);
                $this->setSerie($lanc[0]['SERIE']);
                $this->setParcela($lanc[0]['PARCELA']);
                $this->setAgrupamento($lanc[0]['AGRUPAMENTO']);
                $this->setTipolancamento($lanc[0]['TIPOLANCAMENTO']);
                $this->setTipodocto($lanc[0]['TIPODOCTO']);
                $this->setSitdocto($lanc[0]['SITDOCTO']);
                $this->setSitpgto('A');
                $this->setSitpgtoAnt($lanc[0]['SITPGTO']);
                $this->setUsrsitpgto($lanc[0]['USRSITPGTO']);
                $this->setModopgto($lanc[0]['MODOPGTO']);
                $this->setDocbancario($lanc[0]['DOCBANCARIO']);
                $this->setConta($lanc[0]['CONTA']);
                $this->setNumlcto($lanc[0]['NUMLCTO']);
                $this->setCheque($lanc[0]['CHEQUE']);
                $this->setUsraprovacao($lanc[0]['USRAPROVACAO']);
                $this->setGenero($lanc[0]['GENERO']);
                $this->setDescGenero();
                $this->setCentroCusto($lanc[0]['CENTROCUSTO']);
                $this->setLancamento($lanc[0]['LANCAMENTO']);
                $this->setEmissao($lanc[0]['EMISSAO']);
                $this->setVencimento($lanc[0]['VENCIMENTO']);
                $this->setMovimento('');
                $this->setOriginal($lanc[0]['ORIGINAL']);
                $this->setMulta($lanc[0]['MULTA']);
                $this->setJuros($lanc[0]['JUROS']);
                $this->setAdiantamento($lanc[0]['ADIANTAMENTO']);
                $this->setDesconto($lanc[0]['DESCONTO']);
                $this->setTotal($lanc[0]['TOTAL']);
                $this->setMoeda($lanc[0]['MOEDA']);
                $this->setOrigem($lanc[0]['ORIGEM']);
                $this->setObs($lanc[0]['OBS']);
                $this->setObsContabil($lanc[0]['OBSCONTABIL']);
                $this->setContabil($lanc[0]['CONTABIL']);
                $this->setNossoNumero($lanc[0]['NOSSONUMERO']);
                $this->setRemessaArq($lanc[0]['REMESSAARQ']);
                $this->setRemessaNum($lanc[0]['REMESSANUM']);
                $this->setRemessaData($lanc[0]['REMESSADATA']);
                $this->setRetornoArq($lanc[0]['RETORNOARQ']);
                $this->setRetornoCod($lanc[0]['RETORNOCOD']);
                $this->setRetornoCodRejeicao($lanc[0]['RETORNOCODREJEICAO']);
                $this->setRetornoCodBaixa($lanc[0]['RETORNOCODBAIXA']);
                $this->setRetornoDataLiq($lanc[0]['RETORNODATALIQ']);
        }

        /**
         * Funcao para selecionar imagem do produto estoque
         * @name select_produto_imagem
         * @param INT $id
         * @return array com as imagens do produto selecionado
         */
        public function selectAnexo($id = null)
        {

                if ($id == null):
                        $id = $this->getId();
                endif;

                $sql  = "SELECT * ";
                $sql .= "FROM AMB_IMAGEM ";
                $sql .= "WHERE (ID_DOC = " . $id . ") AND (MODULO = 'FIN') ; ";
                //echo strtoupper($sql);
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim select_conta_geral

        /**
         * Funcao para gravar anexo do lancamento financeiro
         * @name gravaAnexoProduto
         * @param String $mod
         * @param String $destaque
         * @param String $ext
         * @return int id da imagem gravada
         */
        public function gravaAnexoProduto($mod, $destaque, $ext)
        {
                $sql  = "INSERT INTO AMB_IMAGEM (ID_DOC, DESTAQUE, MODULO, EXTENSAO ,USERINSERT, CREATED_AT)";
                $sql .= "VALUES (" . $this->getId() . ", '" . $destaque . "', '" . $mod . "', '" . $ext . "', " . $this->m_userid . ", CURRENT_TIMESTAMP())";
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
        } //fim gravaAnexoProduto

        /**
         * Funcao para excluir anexo
         * @name excluiAnexo
         * @param int $id
         * @return string vazio se ocorrer com sucesso
         */
        public function excluiAnexo($id)
        {
                $sql  = "DELETE FROM AMB_IMAGEM ";
                $sql .= "WHERE (ID = " . $id . ");";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->result;
        } //fim excluiAnexo

        /**
         * Funcao para selecionar anexo pelo id e id_doc
         * @name searchAnexo
         * @param VARCHAR $idAnexo, INT $this->getId
         * @return array com o anexo selecionado
         */
        public function searchAnexo($idAnexo = null)
        {
                $sql  = "SELECT * ";
                $sql .= "FROM AMB_IMAGEM ";
                $sql .= "WHERE (ID_DOC = " . $this->getId() . ") AND (ID = " . $idAnexo . ") AND (MODULO = 'FIN') ; ";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim searchAnexo


        /**
         * Funcao buscar totais do centro de custo por centro de custo e periodo
         * @name searchTotalReceiptPayment
         * @param VARCHAR $centroCusto, VARCHAR $dataIni, VARCHAR $dataFim
         * @return array os totais de por centro de custo
         */
        public function searchTotalReceiptPayment($centroCusto = null, $dataIni = null, $dataFim = null)
        {
                $dataIni = c_date::convertDateTxt($dataIni);
                $dataFim = c_date::convertDateTxt($dataFim);
                // $sql  = "select f.CENTROCUSTO, c.DESCRICAO, SUM(f.TOTAL), f.TIPOLANCAMENTO from FIN_LANCAMENTO f ";
                // $sql .= "inner join FIN_CENTRO_CUSTO c ON f.CENTROCUSTO = c.CENTROCUSTO ";
                // $sql .= "where f.LANCAMENTO BETWEEN '".$dataIni."' and '".$dataFim."' ";
                // if($centroCusto !== null){
                //         $sql .= "and f.CENTROCUSTO in ('".$centroCusto."') ";
                // }
                // $sql .= "group by f.TIPOLANCAMENTO, f.CENTROCUSTO ORDER BY f.CENTROCUSTO;";

                $sql  = "SELECT ";
                $sql .= "f.CENTROCUSTO, ";
                $sql .= "c.DESCRICAO, ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'R' THEN f.TOTAL ELSE 0 END) AS recebimento, ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'P' THEN f.TOTAL ELSE 0 END) AS pagamento ";
                $sql .= "FROM ";
                $sql .= "    FIN_LANCAMENTO f ";
                $sql .= "INNER JOIN ";
                $sql .= "    FIN_CENTRO_CUSTO c ON f.CENTROCUSTO = c.CENTROCUSTO ";
                $sql .= "WHERE ";
                $sql .= "    f.LANCAMENTO BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' AND f.SITPGTO <> 'C' ";
                if ($centroCusto !== null) {
                        $sql .= "AND f.CENTROCUSTO IN ('" . $centroCusto . "') ";
                }
                $sql .= "GROUP BY ";
                $sql .= "    f.CENTROCUSTO, c.DESCRICAO ";
                $sql .= "ORDER BY ";
                $sql .= "    f.CENTROCUSTO;";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim searchTotalReceiptPayment

        /**
         * Funcao buscar totais do centro de custo por centro de custo e periodo
         * @name searchTotalReceiptPayment
         * @param VARCHAR $centroCusto, VARCHAR $dataIni, VARCHAR $dataFim
         * @return array os totais de por centro de custo
         */
        public function searchTotalCD($centroCusto = null, $dataIni = null, $dataFim = null)
        {
                $dataIni = c_date::convertDateTxt($dataIni);
                $dataFim = c_date::convertDateTxt($dataFim);

                $sql  = "SELECT ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'R' AND f.SITPGTO = 'A' THEN f.TOTAL ELSE 0 END) AS recebimento_aberto, ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'R' AND f.SITPGTO = 'B' THEN f.TOTAL ELSE 0 END) AS recebimento_baixado, ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'P' AND f.SITPGTO = 'A' THEN f.TOTAL ELSE 0 END) AS pagamento_aberto, ";
                $sql .= "SUM(CASE WHEN f.TIPOLANCAMENTO = 'P' AND f.SITPGTO = 'B' THEN f.TOTAL ELSE 0 END) AS pagamento_baixado ";
                $sql .= "FROM ";
                $sql .= "    FIN_LANCAMENTO f ";
                $sql .= "INNER JOIN ";
                $sql .= "    FIN_CENTRO_CUSTO c ON f.CENTROCUSTO = c.CENTROCUSTO ";
                $sql .= "WHERE ";
                $sql .= "    f.LANCAMENTO BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' ";
                if ($centroCusto !== null) {
                        $sql .= "AND f.CENTROCUSTO IN ('" . $centroCusto . "') ";
                }

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        } //fim searchTotalReceiptPayment

        /**
         * Function to search for open and donwloaded invoices.
         * @name update_itemns_order
         * @param VARCHAR|INT idOrder
         * @return ARRAY Bank details 
         */
        public static function search_invoices_docto($idOrder)
        {
                $sql = "select ";
                $sql .= "    SUM(CASE WHEN SITPGTO = 'A' THEN 1 ELSE 0 END) AS fatura_aberta, ";
                $sql .= "    SUM(CASE WHEN SITPGTO = 'B' THEN 1 ELSE 0 END) AS fatura_baixada ";
                $sql .= "from FIN_LANCAMENTO ";
                $sql .= "where ORIGEM = 'PED' and docto = " . $idOrder . ";";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        /**
         * Function to update order status by id and person.
         * @name update_invoices_docto
         * @param VARCHAR|INT idOrder
         * @param VARCHAR|INT idperson
         * @param VARCHAR|INT sitPgto
         * @return ARRAY Bank details 
         */
        public static function update_invoices_docto($idOrder, $idPerson, $sitPgto)
        {
                $sql  = "UPDATE fin_lancamento ";
                $sql .= "SET ";
                $sql .= "sitpgto = '" . $sitPgto . "' ";
                $sql .= "WHERE pessoa = " . $idPerson . " and docto = " . $idOrder . ";";

                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
                return $banco->resultado;
        }

        /**
         * Function to search for last added file.
         * @name getMostRecentFile
         * @param VARCHAR directory
         * @return STRING details 
         */
        public static function getMostRecentFile($directory)
        {
                if (!is_dir($directory)) {
                        return "Diretório não localizado!";
                }

                // Lista todos os arquivos 
                $files = scandir($directory, SCANDIR_SORT_NONE);

                $latestFile = null;
                $latestTime = 0;

                foreach ($files as $file) {
                        $filePath = $directory . DIRECTORY_SEPARATOR . $file;

                        if ($file !== "." && $file !== ".." && is_file($filePath)) {
                                $fileModifiedTime = filemtime($filePath);

                                if ($fileModifiedTime > $latestTime) {
                                        $latestTime = $fileModifiedTime;
                                        $latestFile = $filePath;
                                }
                        }
                }

                return $latestFile ? $latestFile : false;
        }
} //END OF THE CLASS
