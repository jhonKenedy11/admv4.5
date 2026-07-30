<?php

//ALTERAR A TABELA EST_NOTA_FISCAL_EVENTOS - coluna VERAPLI para 20

/*
CREATE TABLE EST_MANIFESTO_SEFAZ_RET (
    IDMANIFESTORET INTEGER,
    IDMANIFESTO INTEGER,
    CHAVEACESSONFE VARCHAR(44),
    SITUACAOMANIFESTO CHAR(1),
    SITUACAONFE CHAR(1),
    FORMAINCLUSAO CHAR(1),
    TIPONF CHAR(1),
    VALORNFE NUMERIC(13,2),
    DATAINCLUSAO DATE,
    TIPORETORNO CHAR(1),
    CNPJCPFEMITENTE VARCHAR(14),
    NOMEEMITENTE VARCHAR(60),
    IEEMITENTE VARCHAR(14),
    EMISSAONFE DATE,
    NSU VARCHAR(15),
    DIGVAL VARCHAR(28),
    DHRECBTONFE TIMESTAMP,
    DHRECBTOCANC TIMESTAMP,
    DHRECBTOCCE TIMESTAMP,
    TIPODFE CHAR(1));

COMMIT WORK;

------------------------------------------

EST_MANIFESTO_EVENTO

-- Solução para erro errno: 150 "Foreign key constraint is incorrectly formed"
-- O erro geralmente ocorre por discrepância nos tipos de colunas ou ausência do índice primary/unique na referência.
-- 1. Garanta que EST_NOTA_FISCAL.IDNF seja PRIMARY KEY ou possua UNIQUE INDEX e seja do MESMO TIPO que o campo referenciado (INTEGER).

CREATE TABLE EST_MANIFESTO_EVENTO (
    IDMANIFESTORET  INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    IDNF            INT NOT NULL,
    TPEVENTO        VARCHAR(6),
    NSEQEVENTO      VARCHAR(2),
    DATAHORAEVENTO  DATETIME,
    XMLPED          LONGTEXT,
    XMLLOTE         LONGTEXT,
    XMLPROC         LONGTEXT,
    JUSTIFICATIVA   VARCHAR(255),
    PROTOCOLO       VARCHAR(15),
    USREMISSAO      SMALLINT,
    CHAVEACESSO     VARCHAR(44),
    XEVENTO         VARCHAR(60),
    CNPJCPF         VARCHAR(14),
    USERINSERT      INT,
    DATEINSERT      DATETIME,
    CONSTRAINT fk_manifesto_evento_nf FOREIGN KEY (IDNF) REFERENCES EST_NOTA_FISCAL(IDNF)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



------------------------------------------

CREATE TABLE EST_MANIFESTO_ORGAO (
    CORGAOUFEMI VARCHAR(2),
    CORGAOUFAUT VARCHAR(2));
*/

/**
 * @package   astecv3
 * @name      c_manifesto_fiscal
 * @version   3.0.00
 * @copyright 2022
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      19/10/2022
 */

$dir = (__DIR__);
//error_reporting(E_ALL);
//ini_set('display_errors', '0');
//ini_set('display_errors', 'On');


require_once $dir . '/../../../sped/vendor/autoload.php';
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");

use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Tools;


class c_manifesto_fiscal_sefaz extends c_user{
    
    private $id                = NULL; // integer not null
    private $idNf              = NULL; // integer not null
    private $cliente           = NULL;
    private $chaveacessomdfe   = NULL; 
    private $nomeTransportador = NULL;
    

    public function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        $this->from_array($_SESSION['user_array']);

        $this->slash = '/';
    }

    //INICIO GETTERS E SETTERS est_manifesto_fiscal

    public function setId($id) {$this->id = $id;}
    public function getId() {return $this->id; }

    public function setIdNf($idNf) {$this->idNf = $idNf;}
    public function getIdNf() {return $this->idNf; }

    public function setNumMdf($mdf) {$this->mdf = $mdf;}
    public function getNumMdf() {return $this->mdf; }

    public function setChaveAcessoMdfe($chaveacessomdfe) {$this->chaveacessomdfe = $chaveacessomdfe; }
    public function getChaveAcessoMdfe() {return $this->chaveacessomdfe; }

    public function setSerie($serie) {$this->serie = strtoupper($serie); }
    public function getSerie() {return $this->serie;}

    public function setModelo($modelo) {$this->modelo = $modelo;}
    public function getModelo() {return $this->modelo;}

    public function setSituacao($situacao) {$this->situacao = $situacao;}
    public function getSituacao() {return $this->situacao;}

    public function setCentroCusto($centroCusto) {$this->centroCusto = $centroCusto;}
    public function getCentroCusto() {return $this->centroCusto;}

    public function setCDigitoVerificador($cdigitoverificador) {$this->cdigitoverificador = $cdigitoverificador;}
    public function getCDigitoVerificador() {return $this->cdigitoverificador;}

    public function setTipoTransportador($tipotransportador) {$this->tipotransportador = $tipotransportador;}
    public function getTipoTransportador() {return $this->tipotransportador;}

    public function setTransportador($transportador) {
        if($transportador == ''){
            $this->transportador = 0;
        }else{
            $this->transportador = $transportador;
        }
    }
    public function getTransportador() {return $this->transportador;}

    public function setCondutor($condutor) {$this->condutor = $condutor;}
    public function getCondutor() {return $this->condutor;}

    //modalidade de transporte
    public function setModal($modal) {$this->modal = $modal;}
    public function getModal() {return $this->modal;}
    
    public function setDataHora($datahora) {$this->datahora = $datahora;}
    public function getDataHora($format = null) {        
        $this->datahora = strtr($this->datahora, "/", "-");
        switch ($format) {
            case 'F':
                return date('d/m/Y H:i', strtotime($this->datahora));
                break;
            case 'B':
                return c_date::convertDateBd($this->datahora, $this->m_banco);
                break;
            default:
                return $this->datahora;
        }
    }

    public function setTipoEmitente($tipoemitente) {$this->tipoemitente = $tipoemitente;}
    public function getTipoEmitente() {return $this->tipoemitente;}

    public function setProcEmissao($procemissao) {$this->procemissao = $procemissao;}
    public function getProcEmissao() {return $this->procemissao;}

    public function setVerProc($verproc) {$this->verproc = $verproc;}
    public function getVerProc() {return $this->verproc;}

    public function setUfIni($ufini) {$this->ufini = $ufini;}
    public function getUfIni() {return $this->ufini;}

    public function setUfFim($uffim) {$this->uffim = $uffim;}
    public function getUfFim() {return $this->uffim;}

    public function setReciboMdfe($recibomdfe) {$this->recibomdfe = $recibomdfe;}
    public function getReciboMdfe() {return $this->recibomdfe;}

    public function setProtocoloMdfe($protocolomdfe) {$this->protocolomdfe = $protocolomdfe;}
    public function getProtocoloMdfe() {return $this->protocolomdfe;}

    public function setProtocoloCancelamento($protocolocancelamento) {$this->protocolocancelamento = $protocolocancelamento;}
    public function getProtocoloCancelamento() {return $this->protocolocancelamento;}

    public function setJustificativaCancelamento($justificativacancelamento) {$this->justificativacancelamento = $justificativacancelamento;}
    public function getJustificativaCancelamento() {return $this->justificativacancelamento;}

    public function setProtocoloEncerramento($protocoloencerramento) {$this->protocoloencerramento = $protocoloencerramento;}
    public function getProtocoloEncerramento() {return $this->protocoloencerramento;}

    public function setPathDanfe($path) {$this->pathDanfe = $path;}
    public function getPathDanfe() {return $this->pathDanfe;}

    public function setDigVal($digval) {$this->digval = $digval;}
    public function getDigVal() {return $this->digval;}

    public function setVerAplic($veraplic) {$this->veraplic = $veraplic;}
    public function getVerAplic() {return $this->veraplic;}

    public function setDhRecbto($dhRecbto) {
        $dhRecbto = str_replace("T", " ", $dhRecbto);
        $this->dhRecbto = $dhRecbto;
    }
    public function getDhRecbto() {return $this->dhRecbto;}

    public function setInfMunCarrega($infmuncarrega) {$this->infmuncarrega = $infmuncarrega;}
    public function getInfMunCarrega() {return $this->infmuncarrega;}

    public function setQuantCte($quantcte) {$this->quantcte = $quantcte;}
    public function getQuantCte() {return $this->quantcte;}

    public function setQuantNfe($quantnfe) {$this->quantnfe = $quantnfe;}
    public function getQuantNfe() {return $this->quantnfe;}

    public function setQuantMdfe($quantmdfe) {$this->quantmdfe = $quantmdfe;}
    public function getQuantMdfe() {return $this->quantmdfe;}

    public function setTotalCarga($totalcarga) {$this->totalcarga = $totalcarga;}
    public function getTotalCarga($format = null) {
        if (isset($this->totalcarga)) {
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->totalcarga);
                case 'F':
                    return number_format((float) $this->totalcarga, 2, ',', '.');
                default:
                    return $this->totalcarga;
            }
        }
        return 0;
    }

    public function setUnidadeCarga($unidadecarga) {$this->unidadecarga = $unidadecarga;}
    public function getUnidadeCarga() {return $this->unidadecarga;}

    public function setPesoCarga($pesocarga) {$this->pesocarga = $pesocarga;}
    public function getPesoCarga($format = null) {
        if (isset($this->pesocarga)) {
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->pesocarga);
                    break;
                case 'F':
                    return number_format((double) $this->pesocarga, 2, ',', '.');
                    break;
                default :
                    return $this->pesocarga;
            }
        } else {
            return 0;            
        }
    }//return $this->pesocarga;}
        
    public function setLacre($lacre) {$this->lacre = $lacre;}
    public function getLacre() {return $this->lacre;}

    public function setRodoRntrc($rodorntrc) {$this->rodorntrc = $rodorntrc;}
    public function getRodoRntrc() {return $this->rodorntrc;}

    public function setObservacao($observacao) {$this->observacao = $observacao;}
    public function getObservacao() {return $this->observacao;}

    public function setObservacaoFisco($observacaofisco) {$this->observacaofisco = $observacaofisco;}
    public function getObservacaoFisco() {return $this->observacaofisco;}

    public function setUsrSituacao($usrsituacao) {
        $this->usrsituacao = $this->m_userid;
    }
    public function getUsrSituacao() {return $this->usrsituacao;}

    public function setEmissao($emissao) {$this->emissao = $emissao;}
    public function getEmissao() {return $this->emissao;}
    // public function getEmissao($format = null) {
    //     $this->emissao = strtr($this->emissao, "/", "-");
    //     switch ($format) {
    //         case 'F':
    //             return date('d/m/Y H:i', strtotime($this->emissao));
    //             break;
    //         case 'B':
    //             return c_date::convertDateBd($this->emissao, $this->m_banco);
    //             break;
    //         default:
    //             return $this->emissao;
    //     }
    // }

    public function setHora($hora) {$this->hora = $hora;}
    public function getHora() {return $this->hora;}

    public function setUsrEmissao($usremissao) {$this->usremissao = $usremissao;}
    public function getUsrEmissao() {return $this->usremissao;}

    public function setVeiculoTracao($veiculotracao) {$this->veiculotracao = $veiculotracao;}
    public function getVeiculoTracao() {return $this->veiculotracao;}

    public function setRodoCodAgPorto($rodocodagporto) {$this->rodocodagporto = $rodocodagporto;}
    public function getRodoCodAgPorto() {return $this->rodocodagporto;}

    public function setVeiculoReboque1($veiculoreboque1) {$this->veiculoreboque1 = $veiculoreboque1;}
    public function getVeiculoReboque1() {return $this->veiculoreboque1;}

    public function setVeiculoReboque2($veiculoreboque2) {$this->veiculoreboque2 = $veiculoreboque2;}
    public function getVeiculoReboque2() {return $this->veiculoreboque2;}

    public function setVeiculoReboque3($veiculoreboque3) {$this->veiculoreboque3 = $veiculoreboque3;}
    public function getVeiculoReboque3() {return $this->veiculoreboque3;}

    public function setProdPredTipoCarga($prodpredtipocarga) {$this->prodpredtipocarga = $prodpredtipocarga;}
    public function getProdPredTipoCarga() {return $this->prodpredtipocarga;}

    public function setProdPredDescricao($prodpreddescricao) {$this->prodpreddescricao = $prodpreddescricao;}
    public function getProdPredDescricao() {return $this->prodpreddescricao;}

    public function setProdPredGtin($prodpredgtin) {$this->prodpredgtin = $prodpredgtin;}
    public function getProdPredGtin() {return $this->prodpredgtin;}

    public function setProdPredNcm($prodpredncm) {$this->prodpredncm = $prodpredncm;}
    public function getProdPredNcm() {return $this->prodpredncm;}

    public function setProdPredCepLocalCarrega($prodpredceplocalcarrega) {$this->prodpredceplocalcarrega = $prodpredceplocalcarrega;}
    public function getProdPredCepLocalCarrega() {return $this->prodpredceplocalcarrega;}

    public function setProdPredCepLocalDescarreg($prodpredceplocaldescarrega) {$this->prodpredceplocaldescarrega = $prodpredceplocaldescarrega;}
    public function getProdPredCepLocalDescarreg() {return $this->prodpredceplocaldescarrega;}


    //FIM GETTERS E SETTERS


    


    public function enviaEventoManifesto($idNf = null, $typeEvent = null, $param = '')
    {
        if ($typeEvent == 'confirma') {
            $codEvent = '210200';
            $codStatus = 'CO';
        } elseif ($typeEvent == 'desconhecimento') {
            $codEvent = '210220';
            $codStatus = 'DO';
        } elseif ($typeEvent == 'naorealizada') {
            $codEvent = '210240';
            $codStatus = 'OR';
            if (mb_strlen(trim((string) $param)) < 15) {
                return ['success' => false, 'message' => 'Justificativa obrigatória com no mínimo 15 caracteres.'];
            }
        } else {
            return ['success' => false, 'message' => 'Tipo de evento inválido.'];
        }

        $objNotaFiscal = new c_nota_fiscal();
        $objNotaFiscal->setId($idNf);
        $nfArray = $objNotaFiscal->select_nota_fiscal();
        $chNFe = $nfArray[0]['CHNFE'] ?? '';

        if (!$nfArray || $chNFe === '') {
            return ['success' => false, 'message' => 'Nota fiscal ou chave de acesso não localizada.'];
        }

        $this->slash = '/';
        if (!defined('BASE_DIR_CERT')) {
            define('BASE_DIR_CERT', ADMnfe . $this->slash . $this->m_empresaid . $this->slash . 'certs' . $this->slash);
        }

        try {
            $configJson = c_tools::buscaConfig($this->m_empresaid);
            $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
            $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);
            $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
            $tools->model('55');

            $response = $tools->sefazManifesta($chNFe, $codEvent, $param, 1);
            $stdRes = (new NFePHP\NFe\Common\Standardize($response))->toStd();

            if (!isset($stdRes->retEvento->infEvento)) {
                throw new \Exception('Resposta inválida da SEFAZ.');
            }

            $cStatEvento = (string) ($stdRes->retEvento->infEvento->cStat ?? '');
            $xMotivo = (string) ($stdRes->retEvento->infEvento->xMotivo ?? '');

            if (in_array($cStatEvento, ['135', '136'], true)) {
                $this->insertManifestoEventoSefaz($idNf, $stdRes, $param);
                $this->updateNotaFiscal($idNf, $codStatus);
            } elseif ($cStatEvento === '573') {
                $this->updateNotaFiscal($idNf, $codStatus);
            } else {
                return ['success' => false, 'message' => $xMotivo !== '' ? $xMotivo : 'Evento rejeitado pela SEFAZ.'];
            }

            if ($cStatEvento === '573') {
                $message = 'Evento já registrado anteriormente na SEFAZ.';
            } elseif ($cStatEvento === '136') {
                $message = 'Evento registrado na SEFAZ. A NF-e ainda não estava disponível para vinculação; o download do XML será tentado assim que a Receita liberar.';
            } else {
                $message = 'Evento registrado com sucesso.';
            }

            if (in_array($typeEvent, ['confirma', 'desconhecimento'], true)) {
                $xmlnf = c_nota_fiscal::select_xml_nota_fiscal($idNf);
                $temXml = !empty($xmlnf[0]['XMLCONSULTA']) && stripos($xmlnf[0]['XMLCONSULTA'], '<infNFe') !== false;
                if (!$temXml) {
                    $xmlRet = $this->downloadChaveAcesso($idNf, $chNFe);
                    if ($xmlRet && !is_array($xmlRet)) {
                        $message .= ' XML gravado no sistema.';
                    }
                }
            }

            return ['success' => true, 'message' => $message];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    //=========================FIM eventoCienciaEmissao===================================

    public function downloadChaveAcesso($idNfe=null, $chave=null, $origem=null)
    {

        // $nSeqEvento = 1;
        // $anomes = date('Ym');
        $slash = '/';
        // $path = BASE_DIR_NFE_AMB;
        // $nfExt = '-nfe.xml';

        // define( 'BASE_DIR_NFE_AMB', ADMnfe.$slash.$this->m_empresaid.$slash.ADMambDesc);
        // $path = BASE_DIR_NFE_AMB;
        // define( 'BASE_DIR_NFE_CFG', ADMnfe.$slash.$this->m_empresaid.$slash.'config'); 
        if (!defined('BASE_DIR_CERT')) {
            define('BASE_DIR_CERT', ADMnfe . $slash . $this->m_empresaid . $slash . 'certs' . $slash);
        }
        // define( 'BASE_DIR_ASSINADA', $path.$slash.'assinadas'.$slash.$anomes.$slash.$chave.$nfExt); 


        $configJson = c_tools::buscaConfig($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        //$buscaXmlBase = $this->verificaXmlBase($idNfe);

        try {

            $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
            //só funciona para o modelo 55
            $tools->model('55');
            //este serviço somente opera em ambiente de produção
            $tools->setEnvironment(1);

            $response = $tools->sefazDownload($chave);

            $stz = new NFePHP\NFe\Common\Standardize($response);
            $std = $stz->toStd();

            if ($std->cStat != 138) {
                // Retorna false em caso de erro para tratamento adequado no chamador
                return false;
            }

            $zip = $std->loteDistDFeInt->docZip;
            $xml = gzdecode(base64_decode($zip));

            // Verifica se o retorno é um resumo (resNFe) em vez do XML completo
            // Um XML completo teria tag <NFe>, enquanto resumo tem apenas <resNFe>
            // Verificação simples e direta: se tem resNFe mas não tem NFe, é resumo
            $temResNFe = (stripos($xml, '<resNFe') !== false);
            $temNFeCompleto = (stripos($xml, '<NFe') !== false);
            
            if ($temResNFe && !$temNFeCompleto) {
                return ['tipo' => 'resNFe', 'xml' => $xml, 'chave' => $chave];
            }

            $temInfNFe = (stripos($xml, '<infNFe') !== false);
            if (!$temInfNFe && $temResNFe) {
                return ['tipo' => 'resNFe', 'xml' => $xml, 'chave' => $chave];
            }

            $xmlProcessado = c_nota_fiscal::processarXmlNfe($xml);

            if (!empty($xmlProcessado)) {
                c_nota_fiscal::gravarXmlNota($idNfe, $xmlProcessado);
                return $xmlProcessado;
            }

            return false;
        } catch (\Exception $e) {
            // Retorna false em caso de exceção para tratamento adequado no chamador
            // A mensagem de erro será tratada no método chamador
            return false;
        }
    }

    public function verificaXmlBase($idNfe){
        $sql = "SELECT XMLCONSULTA FROM est_nota_fiscal_xml ";
        $sql .= "WHERE IDNF = " . $idNfe . ";";
    
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    

    function removeAcentos($string, $slug = false) {
        $conversao = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e',
            'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö" => "o",
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï' => 'I', "Ö" => "O", 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N');
        return strtr($string, $conversao);
    }
    //============================================================

    public function MostraData($data, $tipo=null) {
        $aux = explode(" ", $data);
        if ($tipo=='D'):
            return $aux[0];
        else:
            return $aux[0]."T".$aux[1]."-02:00"; // horario de verão 
            //return $aux[0]."T".$aux[1]."-03:00";
        endif;
    }
    //============================================================
    public function convertDate($data) {
        $dateTime = DateTime::createFromFormat("d/m/Y", $data);
        return $dateTime->format("Y-m-d");
    }
    //============================================================

    public function selectManifestoFiscalSefazLetra($letra) {

        // Converte a data informada para um objeto DateTime
        $stringExp = explode("|", $letra);
        $dataIni = $this->convertDate($stringExp[0]);
        $dataFim = $this->convertDate($stringExp[1]);
    
        // Subquery para pegar apenas o ID mais recente de cada chave de acesso (CHNFE)
        $sql = "SELECT nf.*, ";
        $sql .= "CASE WHEN nfx.xmlconsulta IS NULL OR nfx.xmlconsulta = '' THEN 'FALSE' ELSE 'TRUE' END as xml, ";
        $sql .= "cli.nome, ddm.padrao as desc_situacao ";
        $sql .= "FROM est_nota_fiscal nf ";
        $sql .= "INNER JOIN fin_cliente cli ON nf.pessoa = cli.cliente ";
        $sql .= "LEFT JOIN est_nota_fiscal_xml nfx ON nf.id = nfx.idnf ";
        $sql .= "JOIN amb_ddm ddm ON nf.situacao = ddm.tipo ";
        $sql .= "AND ddm.alias = 'EST_MENU' AND ddm.campo = 'SITUACAOMANIFESTOSEFAZ' ";
        $sql .= "INNER JOIN (";
        $sql .= "    SELECT CHNFE, MAX(ID) as MAX_ID ";
        $sql .= "    FROM est_nota_fiscal ";
        $sql .= "    WHERE centrocusto = " . $this->m_empresacentrocusto . " ";
        $sql .= "    AND situacao IN ('NP', 'OR', 'DO', 'CO') ";
        $sql .= "    AND emissao >= '" . $dataIni . " 00:00:00' ";
        $sql .= "    AND emissao <= '" . $dataFim . " 23:59:59' ";
        $sql .= "    GROUP BY CHNFE";
        $sql .= ") nf_uniq ON nf.CHNFE = nf_uniq.CHNFE AND nf.ID = nf_uniq.MAX_ID ";
        $sql .= "WHERE (nf.centrocusto = " . $this->m_empresacentrocusto . ") ";
        $sql .= "AND (nf.situacao IN ('NP', 'OR', 'DO', 'CO')) ";
        $sql .= "AND (nf.emissao >= '" . $dataIni . " 00:00:00') ";
        $sql .= "AND (nf.emissao <= '" . $dataFim . " 23:59:59') ";
        $sql .= "ORDER BY nf.emissao";
    
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    
    // fim select_nota_fiscal_letra
    //============================================================

    public function incluiManifestoFiscal($conn=null) {
    
        $banco = new c_banco;
        $sql = "INSERT INTO est_manifesto_fiscal (";
        $sql .= "NUM_MDF, CHAVEACESSOMDFE, SERIE, `MOD`, SITUACAO, CENTROCUSTO, CDIGITOVERIFICADOR, TIPOTRANSPORTADOR, 
                 TRANSPORTADOR, CONDUTOR, MODAL, DATAHORA, TIPOEMITENTE, PROCEMISSAO, VERPROC, UFINI, UFFIM, 
                 RECIBOMDFE, PROTOCOLOMDFE, PROTOCOLOCANCELAMENTO, JUSTIFICATIVACANCELAMENTO, PROTOCOLOENCERRAMENTO, 
                 PATHDAMDFE, DIGVAL, VERAPLIC, DHRECBTO, INFMUNCARREGA, QUANTCTE, QUANTNFE, QUANTMDFE, TOTALCARGA, 
                 UNIDADECARGA, PESOCARGA, LACRE, RODORNTRC, OBSERVACAO, OBSERVACAOFISCO, USRSITUACAO, EMISSAO, 
                 HORA, USREMISSAO, VEICULOTRACAO, RODOCODAGPORTO, VEICULOREBOQUE1, VEICULOREBOQUE2, VEICULOREBOQUE3, 
                 PRODPREDTIPOCARGA, PRODPREDDESCRICAO, PRODPREDGTIN, PRODPREDNCM, PRODPREDCEPLOCALCARREGA, PRODPREDCEPLOCALDESCARREGA) ";
    
        $sql .= "VALUES ('";
        if ($this->getNumMdf() == ''){
            $sql .= "0', '";
        }
        else{
            $sql .= $this->getNumMdf() . "', '";
        }
        $sql .= $this->getChaveAcessoMdfe() . "', '";
        $sql .= $this->getSerie() . "', '";
        $sql .= $this->getModelo() . "', '";
        $sql .= $this->getSituacao() . "', '";
        $sql .= $this->getCentroCusto() . "', '";
        $sql .= $this->getCDigitoVerificador() . "', '";
        $sql .= $this->getTipoTransportador() . "', ";
        if(($this->getTransportador() == '') or ($this->getTransportador() == null)){
            $sql .= 'null' . ", '";
        }else{
            $sql .= "'". $this->getTransportador() . "', '";
        }
        $sql .= $this->getCondutor() . "', '";
        $sql .= $this->getModal() . "', '";
        $sql .= $this->getDataHora() . "', '";
        $sql .= $this->getTipoEmitente() . "', '";
        $sql .= $this->getProcEmissao() . "', '";
        $sql .= $this->getVerProc() . "', '";
        $sql .= $this->getUfIni() . "', '";
        $sql .= $this->getUfFim() . "', '";
        $sql .= $this->getReciboMdfe() . "', '";
        $sql .= $this->getProtocoloMdfe() . "', '";
        $sql .= $this->getProtocoloCancelamento() . "', '";
        $sql .= $this->getJustificativaCancelamento() . "', '";
        $sql .= $this->getProtocoloEncerramento() . "', '";
        $sql .= $this->getPathDanfe() . "', '";
        $sql .= $this->getDigVal() . "', '";
        $sql .= $this->getVerAplic() . "', '";
        $sql .= $this->getDhRecbto() . "', '";
        $sql .= $this->getInfMunCarrega() . "', '";
        $sql .= $this->getQuantCte() . "', '";
        $sql .= $this->getQuantNfe() . "', '";
        $sql .= $this->getQuantMdfe() . "', '";
        $sql .= $this->getTotalCarga('B') . "', '";
        $sql .= $this->getUnidadeCarga() . "', '";
        $sql .= $this->getPesoCarga('B') . "', '";
        $sql .= $this->getLacre() . "', '";
        $sql .= $this->getRodoRntrc() . "', '";
        $sql .= $this->getObservacao() . "', '";
        $sql .= $this->getObservacaoFisco() . "', '";
        $sql .= $this->getUsrSituacao() . "', '";

        $sql .= $this->getEmissao() . "', '";

        $sql .= $this->getHora() . "', ";

        $sql .= 'null' . ", '";

        $sql .= $this->getVeiculoTracao() . "', '";
        $sql .= $this->getRodoCodAgPorto() . "', '";
        $sql .= $this->getVeiculoReboque1() . "', '";
        $sql .= $this->getVeiculoReboque2() . "', '";
        $sql .= $this->getVeiculoReboque3() . "', '";
        $sql .= $this->getProdPredTipoCarga() . "', '";
        $sql .= $this->getProdPredDescricao() . "', '";
        $sql .= $this->getProdPredGtin() . "', '";
        $sql .= $this->getProdPredNcm() . "', '";
        $sql .= $this->getProdPredCepLocalCarrega() . "', '";
        $sql .= $this->getProdPredCepLocalDescarreg() . "');";
    
        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;
        
        //echo strtoupper($sql)."<BR>";
        $res_nf = $banco->exec_sql($sql, $conn);
    
        if ($banco->result){
            $lastReg = mysqli_insert_id($conn);
            $banco->close_connection();
            return $lastReg;
        }else{
            $banco->close_connection();
            return 'Os dados do manifesto fiscal ' . $this->getNumMdf() . ' n&atilde;o foram cadastrados!';
        }
    }
    // fim incluiManifestoFiscal
    //============================================================

    public function alteraNotaFiscal(){

        $sql = "UPDATE EST_MANIFESTO_FISCAL ";
        $sql .= "SET emissao = '" . date("Y-m-d H:i:s") . "', ";
        $sql .= "totalcarga = " . $this->getTotalCarga('B') . ", ";
        $sql .= "centrocusto = '" . $this->getCentroCusto() . "', ";
        $sql .= "condutor = " . $this->getCondutor() . ", ";
        $sql .= "veiculotracao = " . $this->getVeiculoTracao() . ", ";
        $sql .= "condutor = " . $this->getCondutor() . ", ";
        $sql .= "unidadecarga = '" . $this->getUnidadeCarga() . "', ";
        $sql .= "pesocarga = " . $this->getPesoCarga('B') . ", ";
        $sql .= "totalcarga = " . $this->getTotalCarga('B') . ", ";
        $sql .= "observacao = '" . $this->getObservacao() . "' ";
        //$sql .= "userchange = " . $this->m_userid . ", ";
        //$sql .= "datechange = '" . date("Y-m-d H:i:s") . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        //echo strtoupper($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    function setManifestoFiscal($idMDF=null) {

        $manifestoFiscal = $this->selectManifestoFiscal($idMDF);
        $this->setId($manifestoFiscal[0]['ID']);
        $this->setModelo($manifestoFiscal[0]['MOD']);
        $this->setSerie($manifestoFiscal[0]['SERIE']);
        $this->setNumMdf($manifestoFiscal[0]['NUM_MDF']);
        $this->setEmissao($manifestoFiscal[0]['EMISSAO']);
        $this->setSituacao($manifestoFiscal[0]['SITUACAO']);
        $this->setCentroCusto($manifestoFiscal[0]['CENTROCUSTO']);
        $this->setCDigitoVerificador($manifestoFiscal[0]['CDIGITOVERIFICADOR']);
        $this->setTipoTransportador($manifestoFiscal[0]['TIPOTRANSPORTADOR']);
        $this->setTransportador($manifestoFiscal[0]['TRANSPORTADOR']);
        $this->setCondutor($manifestoFiscal[0]['CONDUTOR']);
        $this->setModal($manifestoFiscal[0]['MODAL']);
        $this->setDataHora($manifestoFiscal[0]['DATAHORA']);
        $this->setTipoEmitente($manifestoFiscal[0]['TIPOEMITENTE']);
        $this->setProcEmissao($manifestoFiscal[0]['PROCEMISSAO']);
        $this->setVerProc($manifestoFiscal[0]['VERPROC']);
        $this->setUfIni($manifestoFiscal[0]['UFINI']);
        $this->setUfFim($manifestoFiscal[0]['UFFIM']);
        $this->setReciboMdfe($manifestoFiscal[0]['RECIBOMDFE']);
        $this->setProtocoloMdfe($manifestoFiscal[0]['PROTOCOLOMDFE']);
        $this->setProtocoloCancelamento($manifestoFiscal[0]['PROTOCOLOCANCELAMENTO']);                        
        $this->setUnidadeCarga($manifestoFiscal[0]['UNIDADECARGA']);
        $this->setPesoCarga($manifestoFiscal[0]['PESOCARGA']);
        $this->setLacre($manifestoFiscal[0]['LACRE']);
        $this->setRodoRntrc($manifestoFiscal[0]['RODORNTRC']);
        $this->setObservacao($manifestoFiscal[0]['OBSERVACAO']);
        $this->setObservacaoFisco($manifestoFiscal[0]['OBSERVACAOFISCO']);
        $this->setUsrSituacao($manifestoFiscal[0]['USRSITUACAO']);
        $this->setEmissao($manifestoFiscal[0]['EMISSAO']);
        $this->setHora($manifestoFiscal[0]['HORA']);
        $this->setUsrEmissao($manifestoFiscal[0]['USREMISSAO']);
        $this->setVeiculoTracao($manifestoFiscal[0]['VEICULOTRACAO']);
        $this->setChaveAcessoMdfe($manifestoFiscal[0]['CHAVEACESSOMDFE']);
        
    }
    //============================================================

    //============================================================
    /**
     * Funcao para alterar dados autorização NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name insertManifestoEventoSefaz
     * @return NULL quando ok ou msg erro
     */
    public function insertManifestoEventoSefaz($idNf=null, $xml=null, $param=null,  $conn=null)
    {

        /*
        ALTER TABLE EST_NOTA_FISCAL_EVENTOS
        ADD COLUMN NSEQEVENTO VARCHAR(2) AFTER CSTAT,
        ADD COLUMN DATAHORAEVENTO TIMESTAMP NULL DEFAULT NULL AFTER NSEQEVENTO,
        ADD COLUMN CHAVEACESSO VARCHAR(44) AFTER DATAHORAEVENTO,
        ADD COLUMN XEVENTO VARCHAR(60) AFTER CHAVEACESSO,
        ADD COLUMN CNPJCPF VARCHAR(14) AFTER XEVENTO;
        */

        $inf = $xml->retEvento->infEvento;
        $dhEvento = (string) ($inf->dhRegEvento ?? '');
        $dhEvento = preg_replace('/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2}:\d{2}).*$/', '$1 $2', $dhEvento);
        $cnpjCpf = (string) ($inf->CNPJDest ?? $inf->CPFDest ?? '');

        $sql = "INSERT INTO EST_MANIFESTO_EVENTO (";
        $sql .= "IDNF, ";
        $sql .= "TPEVENTO, ";
        $sql .= "NSEQEVENTO, ";
        $sql .= "DATAHORAEVENTO, ";
        $sql .= "JUSTIFICATIVA, ";
        $sql .= "PROTOCOLO, ";
        $sql .= "CHAVEACESSO, ";
        $sql .= "XEVENTO, ";
        $sql .= "CNPJCPF, ";
        $sql .= "USERINSERT, ";
        $sql .= "DATEINSERT) VALUES ('";

        $sql .= (int) $idNf . "', '";
        $sql .= $inf->tpEvento . "', '";
        $sql .= $inf->nSeqEvento . "', '";
        $sql .= $dhEvento . "', '";
        $sql .= str_replace("'", "''", (string) $param) . "', '";
        $sql .= $inf->nProt . "', '";
        $sql .= $inf->chNFe . "', '";
        $sql .= str_replace("'", "''", (string) ($inf->xEvento ?? '')) . "', '";
        $sql .= $cnpjCpf . "', ";
        $sql .= (int) ($this->m_userid ?? 0) . ",'" . date("Y-m-d H:i:s") . "' );";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        //echo strtoupper($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    //============================================================ // fim insertManifestoEventoSefaz

    /**
     * Funcao para inserir xml
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name insertManifestoEventoSefaz
     * @return NULL quando ok ou msg 
     * 
    * CREATE TABLE EST_NOTA_FISCAL_XML (
    *   ID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    *  IDNF int(11) NOT NULL,
    * XMLCONSULTA MEDIUMBLOB
    * );
     */
    public function insertXml($idNota, $xml){
        return c_nota_fiscal::gravarXmlNota($idNota, $xml);
    }
    //============================================================ // fim insertXml


    /**
     * 
     * @name gravaDadosEnviaLote
     * @return NULL
     */
    public function updateNotaFiscal($idNf, $status)
    {
        $sql = "UPDATE est_nota_fiscal SET ";
        $sql .= "SITUACAO = '" . $status;
        $sql .= "' WHERE id = " . $idNf . ";";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    //============================================================ // fim updateNotaFiscal

    /**
     * Último registro EST_MANIFESTO e validação da janela de consulta (1h / cStat 589).
     *
     * @return array{cStat:string,ultimaNSU:?string,message:string}
     */
    public function obterDadosConsultaManifestoSefaz()
    {
        $cc = (int) $this->m_empresacentrocusto;
        $banco = new c_banco();
        $banco->exec_sql(
            'SELECT ULTNSU, PROXIMACONSULTA, CSTAT FROM EST_MANIFESTO WHERE CENTROCUSTO = ' . $cc
            . ' ORDER BY id DESC LIMIT 1'
        );
        $banco->close_connection();

        if (!is_array($banco->resultado) || !isset($banco->resultado[0])) {
            return ['cStat' => 'ready', 'ultimaNSU' => null, 'message' => ''];
        }

        $row = $banco->resultado[0];
        $proximaCons = $row['PROXIMACONSULTA'] ?? null;

        if ($proximaCons === null
            || ($row['CSTAT'] ?? '') == '589'
            || time() >= strtotime($proximaCons)) {
            // Evita reiniciar do zero após 589/656 com ULTNSU zerado: usa último NSU válido.
            $bancoNsu = new c_banco();
            $bancoNsu->exec_sql(
                'SELECT ULTNSU FROM EST_MANIFESTO WHERE CENTROCUSTO = ' . $cc
                . " AND ULTNSU IS NOT NULL AND ULTNSU <> '' AND ULTNSU <> '000000000000000'"
                . ' ORDER BY id DESC LIMIT 1'
            );
            $bancoNsu->close_connection();
            $ultimaNsu = null;
            if (is_array($bancoNsu->resultado) && isset($bancoNsu->resultado[0]['ULTNSU'])) {
                $ultimaNsu = $bancoNsu->resultado[0]['ULTNSU'];
            } elseif (!empty($row['ULTNSU']) && $row['ULTNSU'] !== '000000000000000') {
                $ultimaNsu = $row['ULTNSU'];
            }
            return ['cStat' => 'ready', 'ultimaNSU' => $ultimaNsu, 'message' => ''];
        }

        return [
            'cStat' => '405',
            'ultimaNSU' => null,
            'message' => 'Consulta bloqueada, próxima consulta disponivel ' . date('H:i:s', strtotime($proximaCons)) . '.'
        ];
    }
}


