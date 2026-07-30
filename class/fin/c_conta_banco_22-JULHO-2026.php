<?php

/**
 * @package   astecv3
 * @name      c_contaBanco
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/12/2016
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_FIN_BANCO
class c_contaBanco extends c_user
{

    /*
     * TABLE NAME FIN_BANCO
     */
    // Campos tabela
    private $id = NULL; //smallint
    private $nomeInterno = NULL;  //varchar(30)
    private $nomeContaBanco = NULL;  //varchar(40)
    private $banco = NULL;  //varchar(6)
    private $agencia = NULL;  //varchar(6)
    private $contaCorrente = NULL;  //varchar(15)
    private $contaCorrenteDigito = NULL;  //char(1)
    private $contato = NULL;  //varchar(15)
    private $descontoBonificacao = NULL; //decimal(5,2)  
    private $situacao = NULL;

    // APIs de cobrança
    private string | null $bradesco_api_client_id_sandbox = null;
    private string | null $bradesco_api_client_id_production = null;

    private string | null $bradesco_api_client_secret_sandbox = null;
    private string | null $bradesco_api_client_secret_production = null;

    private string | null $inter_api_client_id_sandbox = null;
    private string | null $inter_api_client_id_production = null;

    private string | null $inter_api_client_secret_sandbox = null;
    private string | null $inter_api_client_secret_production = null;

    private string | null $ambiente = null;

    // Mapeamento situação API Inter => SITPGTO do lançamento (JSON)
    private string | null $inter_situacao_map = null;

    // Mapeamento situação API Bradesco => SITPGTO do lançamento (JSON)
    private string | null $bradesco_situacao_map = null;

    //construtor
    function __construct() {}

    /**
     * METODOS DE SETS E GETS
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setNomeInterno($nomeInterno)
    {
        $this->nomeInterno = $nomeInterno;
    }
    public function getNomeInterno()
    {
        return $this->nomeInterno;
    }

    public function setNomeContaBanco($nomeContaBanco)
    {
        $this->nomeContaBanco = $nomeContaBanco;
    }
    public function getNomeContaBanco()
    {
        return $this->nomeContaBanco;
    }

    public function setBanco($banco)
    {
        $this->banco = $banco;
    }
    public function getBanco()
    {
        return $this->banco;
    }

    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }
    public function getAgencia()
    {
        return $this->agencia;
    }

    public function setContaCorrente($contaCorrente)
    {
        $this->contaCorrente = $contaCorrente;
    }
    public function getContaCorrente()
    {
        return $this->contaCorrente;
    }

    public function setContaCorrenteDigito($dig)
    {
        $this->contaCorrenteDigito = $dig;
    }
    public function getContaCorrenteDigito()
    {
        return $this->contaCorrenteDigito;
    }

    public function setContato($contato)
    {
        $this->contato = $contato;
    }
    public function getContato()
    {
        return $this->contato;
    }

    public function setUltimoNossoNumero($nn)
    {
        $this->nn = $nn;
    }
    public function getUltimoNossoNumero()
    {
        return $this->nn;
    }

    public function setDescontoBonificacao($descontoBonificacao)
    {
        $this->descontoBonificacao = $descontoBonificacao;
    }
    public function getDescontoBonificacao($format = NULL)
    {
        $valor = $this->descontoBonificacao;
        if ($valor === null || $valor === '') $valor = 0;

        // Sempre converte , para . após remover pontos separadores de milhar
        $num = str_replace('.', '', $valor);
        $num = str_replace(',', '.', $num);
        $num = (float)$num;

        if ($format === 'F') {
            return number_format($num, 2, ',', '.');
        }
        return $num;
    }

    public function setMulta($multa)
    {
        $this->multa = $multa;
    }
    public function getMulta($format = NULL)
    {
        if ($format == 'F') {
            return number_format($this->multa, 2, ',', '.');
        } else {
            if ($this->multa != null) {
                $num = str_replace('.', '', $this->multa);
                $num = str_replace(',', '.', $num);
                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setJuros($juros)
    {
        $this->juros = $juros;
    }
    public function getJuros($format = NULL)
    {
        if ($format == 'F') {
            return number_format($this->juros, 2, ',', '.');
        } else {
            if ($this->juros != null) {
                $num = str_replace('.', '', $this->juros);
                $num = str_replace(',', '.', $num);
                return $num;
            } else {
                return 0;
            }
        }
    }

    public function setDiaProtesto($diaProtesto)
    {
        $this->diaProtesto = $diaProtesto;
    }
    public function getDiaProtesto()
    {
        return $this->diaProtesto;
    }

    public function setCarteiraCobranca($carteiraCobranca)
    {
        $this->carteiraCobranca = $carteiraCobranca;
    }
    public function getCarteiraCobranca()
    {
        return $this->carteiraCobranca;
    }

    public function setMsgBoleto($msgBoleto)
    {
        $this->msgBoleto = $msgBoleto;
    }
    public function getMsgBoleto()
    {
        return $this->msgBoleto;
    }

    public function setNumNoBanco($numNobanco)
    {
        $this->numNobanco = $numNobanco;
    }
    public function getNumNoBanco()
    {
        return $this->numNobanco;
    }

    public function setStatus($situacao)
    {
        $this->situacao = $situacao;
    }
    public function getStatus()
    {
        return $this->situacao;
    }

    // APIs de cobrança

    public function setEnviaBoleto(string $envia_boleto)
    {
        $this->envia_boleto = $envia_boleto;
    }
    public function getEnviaBoleto()
    {
        return $this->envia_boleto;
    }

    public function setBradescoApiClientIdSandbox(string | null $bradesco_api_client_id_sandbox)
    {
        $this->bradesco_api_client_id_sandbox = $bradesco_api_client_id_sandbox;
    }
    public function getBradescoApiClientIdSandbox()
    {
        return $this->bradesco_api_client_id_sandbox;
    }

    public function setBradescoApiClientIdProduction(string | null $bradesco_api_client_id_production)
    {
        $this->bradesco_api_client_id_production = $bradesco_api_client_id_production;
    }
    public function getBradescoApiClientIdProduction()
    {
        return $this->bradesco_api_client_id_production;
    }

    public function setBradescoApiClientSecretSandbox(string | null $bradesco_api_client_secret_sandbox)
    {
        $this->bradesco_api_client_secret_sandbox = $bradesco_api_client_secret_sandbox;
    }
    public function getBradescoApiClientSecretSandbox()
    {
        return $this->bradesco_api_client_secret_sandbox;
    }

    public function setBradescoApiClientSecretProduction(string | null $bradesco_api_client_secret_production)
    {
        $this->bradesco_api_client_secret_production = $bradesco_api_client_secret_production;
    }
    public function getBradescoApiClientSecretProduction()
    {
        return $this->bradesco_api_client_secret_production;
    }

    public function setInterApiClientIdSandbox(string | null $inter_api_client_id_sandbox)
    {
        $this->inter_api_client_id_sandbox = $inter_api_client_id_sandbox;
    }
    public function getInterApiClientIdSandbox()
    {
        return $this->inter_api_client_id_sandbox;
    }

    public function setInterApiClientIdProduction(string | null $inter_api_client_id_production)
    {
        $this->inter_api_client_id_production = $inter_api_client_id_production;
    }
    public function getInterApiClientIdProduction()
    {
        return $this->inter_api_client_id_production;
    }

    public function setInterApiClientSecretSandbox(string | null $inter_api_client_secret_sandbox)
    {
        $this->inter_api_client_secret_sandbox = $inter_api_client_secret_sandbox;
    }
    public function getInterApiClientSecretSandbox()
    {
        return $this->inter_api_client_secret_sandbox;
    }

    public function setInterApiClientSecretProduction(string | null $inter_api_client_secret_production)
    {
        $this->inter_api_client_secret_production = $inter_api_client_secret_production;
    }
    public function getInterApiClientSecretProduction()
    {
        return $this->inter_api_client_secret_production;
    }

    public function setAmbiente(string | null $ambiente)
    {
        $this->ambiente = $ambiente;
    }
    public function getAmbiente()
    {
        return $this->ambiente;
    }

    /**
     * Recebe o mapeamento de situações da API Inter.
     * Aceita string JSON (vinda do POST/banco) ou array (será convertido).
     * Armazena sempre como string JSON pronta para gravar na coluna FIN_CONTA.INTER_SITUACAO_MAP.
     */
    public function setInterSituacaoMap($map)
    {
        if ($map === null || $map === '') {
            $this->inter_situacao_map = null;
            return;
        }
        if (is_array($map)) {
            $this->inter_situacao_map = json_encode($map, JSON_UNESCAPED_UNICODE);
            return;
        }
        // string: valida se é JSON; se não for, descarta
        $decoded = json_decode($map, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $this->inter_situacao_map = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        } else {
            $this->inter_situacao_map = null;
        }
    }
    public function getInterSituacaoMap()
    {
        return $this->inter_situacao_map;
    }

    /**
     * Retorna o mapeamento como array associativo (para popular selects no template).
     */
    public function getInterSituacaoMapArray()
    {
        if ($this->inter_situacao_map === null || $this->inter_situacao_map === '') {
            return array();
        }
        $arr = json_decode($this->inter_situacao_map, true);
        return is_array($arr) ? $arr : array();
    }

    /**
     * Devolve o valor pronto para concatenar no SQL: NULL ou string entre aspas com escape.
     * Mantém o INSERT/UPDATE limpos e sem lógica condicional.
     */
    private function getInterSituacaoMapSqlValue()
    {
        $json = $this->getInterSituacaoMap();
        if ($json === null || $json === '') {
            return "NULL";
        }
        return "'" . addslashes($json) . "'";
    }

    /**
     * Recebe o mapeamento de situações da API Inter.
     * Aceita string JSON (vinda do POST/banco) ou array (será convertido).
     * Armazena sempre como string JSON pronta para gravar na coluna FIN_CONTA.INTER_SITUACAO_MAP.
     */
    public function setBradescoSituacaoMap($map)
    {
        if ($map === null || $map === '') {
            $this->bradesco_situacao_map = null;
            return;
        }
        if (is_array($map)) {
            $this->bradesco_situacao_map = json_encode($map, JSON_UNESCAPED_UNICODE);
            return;
        }
        // string: valida se é JSON; se não for, descarta
        $decoded = json_decode($map, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $this->bradesco_situacao_map = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        } else {
            $this->bradesco_situacao_map = null;
        }
    }
    public function getBradescoSituacaoMap()
    {
        return $this->bradesco_situacao_map;
    }

    /**
     * Retorna o mapeamento como array associativo (para popular selects no template).
     */
    public function getBradescoSituacaoMapArray()
    {
        if ($this->bradesco_situacao_map === null || $this->bradesco_situacao_map === '') {
            return array();
        }
        $arr = json_decode($this->bradesco_situacao_map, true);
        return is_array($arr) ? $arr : array();
    }

    /**
     * Devolve o valor pronto para concatenar no SQL: NULL ou string entre aspas com escape.
     * Mantém o INSERT/UPDATE limpos e sem lógica condicional.
     */
    private function getBradescoSituacaoMapSqlValue()
    {
        $json = $this->getBradescoSituacaoMap();
        if ($json === null || $json === '') {
            return "NULL";
        }
        return "'" . addslashes($json) . "'";
    }

//############### FIM SETS E GETS ###############


    /**
     * @name geraNumeroRemessa
     * @param int $conta - conta bancaria para gravar nosso numero
     * @param int $nr -  numero remessa a ser incrementado
     * @description atualzia o numero de remessa de cobrrança as ser enviado.
     */
    public function geraNumeroRemessa($conta, $nr)
    {

        $nr = $nr + 1;

        // SALVA NOSSO NUMERO
        $sql  = "UPDATE fin_conta ";
        $sql .= "SET  ";
        $sql .= "NUMREMESSA = '" . $nr . "' ";
        $sql .= "WHERE conta = " . $conta . ";";

        $contaBanco = new c_banco();
        $res_contaBanco = $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();
        if ($res_contaBanco <= 0):
            $nr = 0;
        endif;
        return $nr;
    } //fim geraNossoNumero

    /**
     * @name geraNossoNumero
     * @param int $id_conta - conta bancaria para gravar nosso numero
     * @param int $nn -  nosso numero a aser incrementado
     * @param string $banco - codigo do banco (opcional, usado para Sicredi)
     * @param string $agencia - agencia (opcional, usado para Sicredi)
     * @param string $posto - posto (opcional, usado para Sicredi)
     * @param string $conta - conta (opcional, usado para Sicredi)
     * @description atualiza o noso numero de cobrrança as ser enviado.
     */
    public function geraNossoNumero($id_conta, $nn, $banco = null, $agencia = null, $posto = null, $conta = null)
    {
        if ($banco == '748') {
            /*
        AA/BXXXXX-D, onde:
        AA = Ano (pode ser diferente do ano corrente)
        B = Byte de geração (0 a 9). O Byte 1 só poderá ser informado pela Cooperativa
        XXXXX = Número livre de 00000 a 99999
        D = Dígito verificador pelo módulo 11
        */
            $ano = date('y');
            $b_nosso = intval(substr($nn, 3, 1));

            //condicao quando for o primeiro registro
            if ($b_nosso == 0) {
                $b_nosso = 2;
            }

            //captura a partir da 4 posicao da string e incrementa 1
            $s_nosso = intval(substr($nn, 4, 5) + 1);

            if ($s_nosso == 99999) {
                $b_nosso = $b_nosso + 1;
                $s_nosso = 00001;
            }

            //acrescenta zeros a esquerda para gerar nosso numero
            $s_nosso = str_pad($s_nosso, 5, 0, STR_PAD_LEFT);

            /* 
        códigos da Cooperativa (aaaa), posto beneficiário (pp), beneficiário (ccccc), ano atual (yy), byte
        de geração do Nosso Número (b) e o número sequencial do beneficiário (nnnnn):
        aaaappcccccyybnnnnn;
        */
            $teste = $agencia . $posto . $conta . $ano . $b_nosso . $s_nosso;
            $dv_nosso_numero = $this->digitoVerificador_nossonumero($agencia . $posto . $conta . $ano . $b_nosso . $s_nosso);
            $sequencial_completo = str_pad($s_nosso, 5, "0", STR_PAD_LEFT);

            $nn = $ano . "/" . $b_nosso . $sequencial_completo . $dv_nosso_numero;
        } else {
            $nn = (int) $nn + 1;
        }


        // SALVA NOSSO NUMERO
        $sql  = "UPDATE fin_conta ";
        $sql .= "SET  ";
        $sql .= "ULTIMONOSSONRO = '" . $nn . "' ";
        $sql .= "WHERE conta = " . $id_conta . ";";

        $contaBanco = new c_banco();
        $res_contaBanco = $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();
        if ($res_contaBanco <= 0):
            $nn = 0;
        endif;
        return $nn;
    } //fim geraNossoNumero

    /**
     * @name existeBanco
     * @description pesquisa se já existe código do contaBanco
     */
    public function existeContaBanco()
    {

        $sql  = "SELECT * ";
        $sql .= "FROM fin_conta ";
        $sql .= "WHERE (conta = " . $this->getId() . ")";
        //ECHO $sql;

        $contaBanco = new c_banco();
        $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();
        return is_array($contaBanco->resultado);
    } //fim existeBanco

    /**
     * @name select_Banco
     * @description pesquisa se já existe código do contaBanco cadastrado
     */
    public function select_ContaBanco()
    {

        $sql  = "SELECT DISTINCT * ";
        $sql .= "FROM fin_conta ";
        $sql .= "WHERE (conta = " . $this->getId() . ") ";


        //echo $sql;
        $contaBanco = new c_banco();
        $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();
        return $contaBanco->resultado;
    } //fim select_contaBanco

    /**
     * @name select_contaBanco_geral
     * @description pesquisa que retorna todos os registros cadastrado
     */
    public function select_contaBanco_geral()
    {
        $sql  = "SELECT DISTINCT * ";
        $sql .= "FROM fin_conta ";
        $sql .= "ORDER BY conta ";

        //	ECHO $sql;
        $contaBanco = new c_banco;
        $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();
        return $contaBanco->resultado;
    } //fim select_contaBanco_geral

    /**
     * @name incluiBanco
     * @description faz a inclusão do registro cadastrado
     */
    public function incluiContaBanco()
    {

        $sql  = "INSERT INTO FIN_CONTA (NOMEINTERNO, NOMECONTABANCO, BANCO, AGENCIA, CONTACORRENTE, CONTA_CORRENTE_DIGITO,
             CONTATO, DESCONTOBONIFICACAO, STATUS, MULTA, JUROS, PROTESTO, NUMNOBANCO, CARTEIRA,
             MSGBLOQUETO, ULTIMONOSSONRO, ENVIA_BOLETO, BRADESCO_API_CLIENT_ID_SANDBOX, BRADESCO_API_CLIENT_ID_PRODUCTION,
             BRADESCO_API_CLIENT_SECRET_SANDBOX, BRADESCO_API_CLIENT_SECRET_PRODUCTION, INTER_API_CLIENT_ID_SANDBOX, INTER_API_CLIENT_ID_PRODUCTION,
             INTER_API_CLIENT_SECRET_SANDBOX, INTER_API_CLIENT_SECRET_PRODUCTION, AMBIENTE, INTER_SITUACAO_MAP, BRADESCO_SITUACAO_MAP ) ";
        $sql .= "VALUES ('" . $this->getNomeInterno() . "',"
            . "'" . $this->getNomeContaBanco() . "',"
            . "'" . $this->getBanco() . "',"
            . "'" . $this->getAgencia() . "',"
            . "'" . $this->getContaCorrente() . "',"
            . "'" . $this->getContaCorrenteDigito() . "',"
            . "'" . $this->getContato() . "',"
            . "'" . $this->getDescontoBonificacao('B') . "',"
            . "'" . $this->getStatus() . "',"
            . "'" . $this->getMulta('B') . "',"
            . "'" . $this->getJuros('B') . "',"
            . "'" . $this->getDiaProtesto() . "',"
            . "'" . $this->getNumNoBanco() . "',"
            . "'" . $this->getCarteiraCobranca() . "',"
            . "'" . $this->getMsgBoleto() . "',"
            . "'" . $this->getUltimoNossoNumero() . "',"
            . "'" . $this->getEnviaBoleto() . "',"
            . "'" . $this->getBradescoApiClientIdSandbox() . "',"
            . "'" . $this->getBradescoApiClientIdProduction() . "',"
            . "'" . $this->getBradescoApiClientSecretSandbox() . "',"
            . "'" . $this->getBradescoApiClientSecretProduction() . "',"
            . "'" . $this->getInterApiClientIdSandbox() . "',"
            . "'" . $this->getInterApiClientIdProduction() . "',"
            . "'" . $this->getInterApiClientSecretSandbox() . "',"
            . "'" . $this->getInterApiClientSecretProduction() . "',"
            . "'" . $this->getAmbiente() . "',"
            . $this->getInterSituacaoMapSqlValue() . ","
            . $this->getBradescoSituacaoMapSqlValue() . ")";

        //echo $sql;
        $contaBanco = new c_banco;
        $res_contaBanco =  $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();

        if ($res_contaBanco > 0):
            return 'Dados ' . $this->getNomeInterno() . ' foram cadastrados!';
        else:
            return 'Os dados ' . $this->getNomeInterno() . ' não foram cadastrados!';
        endif;
    } // fim incluiBanco

    /**
     * @name alteraBanco
     * @description altera registro existente
     */
    public function alteraContaBanco()
    {

        $sql  = "UPDATE FIN_CONTA ";
        $sql .= "SET  ";
        $sql .= "NOMEINTERNO = '" . $this->getNomeInterno() . "', ";
        $sql .= "NOMECONTABANCO = '" . $this->getNomeContaBanco() . "', ";
        $sql .= "BANCO = '" . $this->getBanco() . "', ";
        $sql .= "AGENCIA = '" . $this->getAgencia() . "', ";
        $sql .= "CONTACORRENTE = '" . $this->getContaCorrente() . "', ";
        $sql .= "CONTA_CORRENTE_DIGITO = '" . $this->getContaCorrenteDigito() . "', ";
        $sql .= "CONTATO = '" . $this->getContato() . "', ";
        $sql .= "DESCONTOBONIFICACAO = '" . $this->getDescontoBonificacao('B') . "', ";
        $sql .= "STATUS = " . "'" . $this->getStatus() . "', ";
        $sql .= "MULTA = " . "'" . $this->getMulta('B') . "', ";
        $sql .= "JUROS = " . "'" . $this->getJuros('B') . "', ";
        $sql .= "PROTESTO = " . "'" . $this->getDiaProtesto() . "', ";
        $sql .= "NUMNOBANCO = " . "'" . $this->getNumNoBanco() . "', ";
        $sql .= "CARTEIRA = " . "'" . $this->getCarteiraCobranca() . "', ";
        $sql .= "MSGBLOQUETO = " . "'" . $this->getMsgBoleto() . "', ";
        $sql .= "ULTIMONOSSONRO = " . "'" . $this->getUltimoNossoNumero() . "', ";
        $sql .= "ENVIA_BOLETO = " . "'" . $this->getEnviaBoleto() . "',";
        $sql .= "BRADESCO_API_CLIENT_ID_SANDBOX = " . "'" . $this->getBradescoApiClientIdSandbox() . "',";
        $sql .= "BRADESCO_API_CLIENT_ID_PRODUCTION = " . "'" . $this->getBradescoApiClientIdProduction() . "',";
        $sql .= "BRADESCO_API_CLIENT_SECRET_SANDBOX = " . "'" . $this->getBradescoApiClientSecretSandbox() . "',";
        $sql .= "BRADESCO_API_CLIENT_SECRET_PRODUCTION = " . "'" . $this->getBradescoApiClientSecretProduction() . "',";
        $sql .= "INTER_API_CLIENT_ID_SANDBOX = " . "'" . $this->getInterApiClientIdSandbox() . "',";
        $sql .= "INTER_API_CLIENT_ID_PRODUCTION = " . "'" . $this->getInterApiClientIdProduction() . "',";
        $sql .= "INTER_API_CLIENT_SECRET_SANDBOX = " . "'" . $this->getInterApiClientSecretSandbox() . "',";
        $sql .= "INTER_API_CLIENT_SECRET_PRODUCTION = " . "'" . $this->getInterApiClientSecretProduction() . "',";
        $sql .= "AMBIENTE = " . "'" . $this->getAmbiente() . "', ";
        $sql .= "INTER_SITUACAO_MAP = " . $this->getInterSituacaoMapSqlValue() . ", ";
        $sql .= "BRADESCO_SITUACAO_MAP = " . $this->getBradescoSituacaoMapSqlValue() . " ";
        $sql .= "WHERE CONTA = " . $this->getId() . ";";

        $contaBanco = new c_banco;
        $res_contaBanco =  $contaBanco->exec_sql_lower_case($sql);
        $contaBanco->close_connection();

        if ($res_contaBanco > 0):
            return 'Dados ' . $this->getNomeInterno() . ' foram alterados!';
        else:
            return 'Os dados ' . $this->getNomeInterno() . ' não foram alterados!';
        endif;
    }  // fim alteraBanco

    /**
     * @name exlcuiBanco
     * @description esclui resgistro existe
     */
    public function excluiContaBanco()
    {

        $sql  = "DELETE FROM fin_conta ";
        $sql .= "WHERE conta = " . $this->getId();
        $contaBanco = new c_banco;
        $res_contaBanco =  $contaBanco->exec_sql($sql);
        $contaBanco->close_connection();

        if ($res_contaBanco > 0):
            return 'Os dados ' . $this->getNomeInterno() . ' foram exclu&iacute;dos!';
        else:
            return 'Os dados ' . $this->getNomeInterno() . ' não foram excluidos!';
        endif;
    }  // fim excluiBanco

    /**
     * @name mod11
     * @description calcula digito verificador com base no calculo modulo 11
     * @param int $num - numero a ser calculado
     * @return int $count - numero de parcelas geradas
     */
    public static function mod11($num, $base = 9, $r = 0)
    {
        $soma = 0;
        $fator = 2;
        $num = (int) $num;
        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; --$i) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) { // restaura fator de multiplicacao para 2
                $fator = 1;
            }
            ++$fator;
        }

        //calculo digito bradesco
        $resto = $soma % 11;
        switch ($resto) {
            case 0:
                $digito = 0;
                break;
            case 1:
                $digito = 'P';
                break;
            default:
                $digito = 11 - $resto;
        }
        return $digito;
        /* Calculo do modulo 11 
    if ($r == 0) {
        $soma *= 10;
        $digito = $soma % 11;
        if ($digito == 10) {
            $digito = 0;
        }

        return $digito;
    } elseif ($r == 1) {
        $resto = $soma % 11;

        return $resto;
    }*/
    }

    function modulo_11($num, $base = 9, $r = 0)
    {
        /**
         *   Autor:
         *           Pablo Costa <pablo@users.sourceforge.net>
         *
         *   Fun��o:
         *    Calculo do Modulo 11 para geracao do digito verificador 
         *    de boletos bancarios conforme documentos obtidos 
         *    da Febraban - www.febraban.org.br 
         *
         *   Entrada:
         *     $num: string num�rica para a qual se deseja calcularo digito verificador;
         *     $base: valor maximo de multiplicacao [2-$base]
         *     $r: quando especificado um devolve somente o resto
         *
         *   Sa�da:
         *     Retorna o Digito verificador.
         *
         *   Observa��es:
         *     - Script desenvolvido sem nenhum reaproveitamento de c�digo pr� existente.
         *     - Assume-se que a verifica��o do formato das vari�veis de entrada � feita antes da execu��o deste script.
         */

        $soma = 0;
        $fator = 2;

        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num, $i - 1, 1);
            // Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            // Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                // restaura fator de multiplicacao para 2 
                $fator = 1;
            }
            $fator++;
        }

        /* Calculo do modulo 11 */
        if ($r == 0) {
            $soma *= 10;
            $digito = $soma % 11;
            return $digito;
        } elseif ($r == 1) {
            // esta rotina sofrer algumas altera��es para ajustar no layout do SICREDI
            $r_div = (int)($soma / 11);
            $digito = ($soma - ($r_div * 11));
            return $digito;
        }
    }

    /**
     * @name digitoVerificador_nossonumero
     * @description calcula digito verificador do nosso numero para Sicredi
     * @param string $numero - numero completo para calculo do DV
     * @return int digito verificador
     */
    function digitoVerificador_nossonumero($numero)
    {
        $resto2 = $this->modulo_11($numero, 9, 1);
        // esta rotina sofrer algumas altera��es para ajustar no layout do SICREDI
        $digito = 11 - $resto2;
        if ($digito > 9) {
            $dv = 0;
        } else {
            $dv = $digito;
        }
        return $dv;
    }
}    //	END OF THE CLASS
