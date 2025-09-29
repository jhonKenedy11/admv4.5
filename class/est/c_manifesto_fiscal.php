<?php

//ALTERAR A TABELA EST_NOTA_FISCAL_EVENTOS - coluna VERAPLI para 20

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
ini_set('display_errors', '0');
//ini_set('display_errors', 'On');


require_once $dir . '/../../../sped/vendor/autoload.php';
include_once($dir . "/../../class/fin/c_lancamento.php");
include_once($dir . "/../../class/fin/c_conta.php");
include_once($dir . "/../../class/est/c_nat_operacao.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../class/est/c_manifesto_veiculo.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");

use NFePHP\Common\Certificate;
use NFePHP\MDFe\Common\Standardize;
use NFePHP\MDFe\Tools;
use NFePHP\DA\MDFe\Damdfe;


class c_manifesto_fiscal extends c_user{
    
    private $id                         = NULL; // integer not null
    private $cliente                    = NULL;
    private $mdf                        = NULL;
    private $chaveacessomdfe            = NULL;
    private $serie                      = NULL;
    private $modelo                     = NULL; 
    private $situacao                   = NULL;
    private $centroCusto                = NULL;
    private $cdigitoverificador         = NULL;
    private $tipotransportador          = NULL;
    private $transportador              = NULL;
    private $condutor                   = NULL;
    private $modal                      = NULL;
    private $datahora                   = NULL;
    private $tipoemitente               = NULL;
    private $procemissao                = NULL;
    private $verproc                    = NULL;
    private $ufini                      = NULL;
    private $uffim                      = NULL;
    private $recibomdfe                 = NULL;
    private $protocolomdfe              = NULL;
    private $protocolocancelamento      = NULL;
    private $justificativacancelamento  = NULL;
    private $protocoloencerramento      = NULL;
    private $infmuncarrega              = NULL;
    private $quantcte                   = NULL;
    private $quantnfe                   = NULL;
    private $quantmdfe                  = NULL;
    private $totalcarga                 = NULL;
    private $unidadecarga               = NULL;
    private $pesocarga                  = NULL;
    private $lacre                      = NULL;
    private $rodorntrc                  = NULL;
    private $observacao                 = NULL;
    private $observacaofisco            = NULL;     
    private $usrsituacao                = NULL;
    private $emissao                    = NULL;
    private $hora                       = NULL;
    private $usremissao                 = NULL;
    private $veiculotracao              = NULL;
    private $rodocodagporto             = NULL;
    private $veiculoreboque1            = NULL;
    private $veiculoreboque2            = NULL;
    private $veiculoreboque3            = NULL;
    private $prodpredtipocarga          = NULL;
    private $prodpreddescricao          = NULL;
    private $prodpredgtin               = NULL;
    private $prodpredncm                = NULL;
    private $prodpredceplocalcarrega    = NULL;
    private $prodpredceplocaldescarrega = NULL;
    private $justificativa              = NULL;
    
    public $tpAmb     = NULL;
    public $anomes    = NULL;
    public $mdfExt    = NULL;
    public $mdfProt   = NULL;
    public $mdfExtPdf = NULL;
    public $path      = NULL;
    public $m_chave   = NULL;
    public $pathDanfe = NULL;
    public $chNFe     = NULL;
    public $dhRecbto  = NULL;
    public $verAplic  = NULL;
    public $digVal    = NULL;
    public $nProt     = NULL;


    
    private $nomeTransportador = NULL;
    

    public function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        $this->from_array($_SESSION['user_array']);

        $this->slash = '/';

        define( 'BASE_DIR_MDFE_CFG', ADMmdfe.$this->slash.$this->m_empresaid.$this->slash.'config'); 
        define( 'BASE_DIR_MDFE_AMB', ADMmdfe.$this->slash.$this->m_empresaid.$this->slash.ADMambDesc); 
        define( 'BASE_HTTP_MDFE_AMB', ADMhttpCliente.$this->slash.'mdfe'.$this->slash.$this->m_empresaid.$this->slash.ADMambDesc.$this->slash); 
        define( 'BASE_DIR_CERT', ADMnfe.$this->slash.$this->m_empresaid.$this->slash.'certs'.$this->slash);

        //define('BASE_DIR_CERT', ADMnfe . $this->slash . $this->m_empresaid . $this->slash . 'certs' . $this->slash);

        $this->tpAmb = ADMnfeAmbiente; // 1 - producao / 2 homologacao
        $this->anomes = date('Ym');
        $this->mdfExt = '-mdfe.xml';
        $this->mdfProt = '-protMDFe.xml';
        $this->mdfExtPdf = '-damdfe.pdf';
        $this->path = BASE_DIR_MDFE_AMB;
        
    }

    //INICIO GETTERS E SETTERS est_manifesto_fiscal

    public function setId($id) {$this->id = $id;}
    public function getId() {return $this->id; }

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

    public function setDhRecbto ($dhRecbto) {
        $dhRecbto = str_replace("T", " ", $dhRecbto);
        $this->dhRecbto  = $dhRecbto ;}
    public function getDhRecbto () {return $this->dhRecbto ;}

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
                    break;
                case 'F':
                    return number_format((float) $this->totalcarga, 2, ',', '.');
                    break;
                default:
                    return $this->totalcarga;
            }
        }else{
            return 0;
        }
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

    //FIM GETTERS E SETTERS est_manifesto_fiscal

    public function setChMdfe($chNfe) {$this->chNFe = $chNfe;}
    public function getChMdfe() {return $this->chNFe;}
    

    public function setProtMdfe($nProt) {$this->nProt = $nProt;}
    public function getProtMdfe() {return $this->nProt;}

    public function setJustificativa($justificativa) {$this->justificativa = $justificativa;}
    public function getJustificativa() {return $this->justificativa;}

    //FIM GETTERS E SETTERS


    public function makeMdfe($idMDF=null, $empresacentrocusto, $conn=null) {
        

        // CONSULTA DE DADOS DA NOTA FISCAL
        $mdfArray = $this->selectManifestoFiscal($idMDF, $conn);
        //Busca nfs
        $NotasFiscaisMDF = c_manifesto_fiscal_nf::buscaNfsDesc($idMDF);

        //DADOS DA EMPRESA/EMITENTE
        $filialArray = $this->selectEmpresaCC($mdfArray[0]['CENTROCUSTO']);
        
        // DADOS DO DESTINATARIO
        $pessoaDestOBJ = new c_conta();     
        $pessoaDestOBJ->setId($mdfArray[0]['PESSOA']);
        $pessoaDestArray = $pessoaDestOBJ->select_conta();

        // DADOS DO TRANSPORTADOR
        $transpOBJ = new c_conta();
        $transpOBJ->setId($mdfArray[0]['TRANSPORTADOR']);
        $transpArray = $transpOBJ->select_conta();
        
        //CONSULTA DADOS CONDUTOR VEICULO
        $objCondutor = new c_conta();
        $objCondutor->setId($mdfArray[0]['CONDUTOR']);
        $dadosObjCondutor = $objCondutor->select_conta();//$objCondutor->select_conta();

        //CONSULTA VEICULO
        $objVeiculo = new c_manifesto_veiculo();
        $dadosObjVeiculo = $objVeiculo->buscaDadosVeiculo($mdfArray[0]['VEICULOTRACAO']);//$mdfArray[0]['VEICULOTRACAO']);




        //BUSCA CONFIG EMPRESA EMISS
        //$configJson = c_tools::buscaConfigMdfe($this->m_empresaid);

        //VAR's utilizadas para gerar $cDv = Digito verificador da chave de acesso do Manifesto 
        //====================================================
        $m_cMunEmit = $filialArray[0]['CODMUNICIPIO'];
        $m_cUF = substr($m_cMunEmit, 0, 2);
        $m_natOp = $this->removeAcentos($mdfArray[0]['NATOPERACAO']);
        $m_indPag = $mdfArray[0]['FORMAPGTO'];
        $m_mod = 58; //$mdfArray[0]['MODELO']; //58 MDF-e
        $m_serie = $mdfArray[0]['SERIE'];
        $m_nMDF = $mdfArray[0]['NUM_MDF'];
        $m_tpMDF = 1;//$mdfArray[0]['TIPO']; //1- Normal - 2-Contingência
        $m_cMDF = rand(1,99999999);
        $m_cnpj = $filialArray[0]['CNPJ'];

        $m_dhEmi = $this->MostraData($mdfArray[0]['DATAHORA']);//'2022-11-24T09:46:48-03:00'; //
        $m_ano = date('y', strtotime($m_dhEmi));
        $m_mes = date('m', strtotime($m_dhEmi));
        //=================FIM VAR's==========================

        //CRIA OBJ E SET DADOS ---------------------------------------------------------------------------------------------------
        $mdfe = new NFePHP\MDFe\Make();
        $mdfe->setOnlyAscii(true);

        /*
         * Grupo ide ( Identificação )
         */
        $std = new \stdClass();

        $std->cUF = $m_cUF; //'41';
        $std->tpAmb = 1; //ADMmdfeAmbiente; //1 - Produção 2 - Homologação - ADMnfeAmbiente

        /*1 - Prestador de serviço de transporte
        2 - Transportador de Carga Própria
        3 - Prestador de serviço de transporte que emitirá CT-e Globalizado
        OBS: Deve ser preenchido com 2 para
        emitentes de NF-e e pelas
        transportadoras quando estiverem
        fazendo transporte de carga própria.
        Deve ser preenchido com 3 para
        transportador de carga que emitirá à
        posteriori CT-e Globalizado relacionando
        as NF-e. */
        $std->tpEmit = '2';

        /*1-ETC- Empresa de Transporte de Cargas: toda empresa que disponha de veículos que são empregados no transporte de mercadorias e bens (próprios ou cargas de terceiros);
        3-CTC- Cooperativa de Transporte de Cargas: inclui as cooperativas e uniões de condutores que operam no mercado de transporte de mercadorias.
        2-TAC- Transportador Autônomo de Cargas: obrigatório para todo e qualquer tipo de autônomo, desde operadores de pequenos caminhões que operam em cidades, 
        até caminhoneiros que dirigem grandes distâncias. Prestando seus serviços para pequenas, médias e grandes transportadoras. 
        Existe duas espécies de transportador autônomo de cargas: o Transportador Autônomo de Cargas Agregado e o Transportador Autônomo de Cargas Independente.
            Transportador Autônomo de Cargas Agregado: aquele que coloca veículo de sua propriedade ou de sua posse, a ser dirigido por ele próprio ou por preposto seu, 
            a serviço do contratante, com exclusividade, mediante remuneração certa.

            Transportador Autônomo de Cargas Independente: é aquele que presta serviços de transporte de carga, em caráter eventual e sem exclusividade, 
            mediante frete ajustado a cada viagem. Tanto num caso como no outro não há vínculo empregatício e sim contrato de natureza civil.*/
        //$std->tpTransp = '2';

        $std->mod = $m_mod; //Utilizar o código 58 para identificação do MDF-e 
        $std->serie = $m_serie;
        $std->nMDF = $m_nMDF;

        $chave =  NFePHP\Common\Keys::build($m_cUF, $m_ano, $m_mes, $m_cnpj, $m_mod, $m_serie, $m_nMDF, $std->tpAmb, $m_cMDF);
        $this->m_chave = $chave;

        $std->cMDF = $m_cMDF;
        $std->cDV = substr($chave, -1);
        $std->modal = '1'; //1- Rodoviário; 2- Aéreo; 3- Aquaviário; 4- Ferroviário.
        $std->dhEmi = $m_dhEmi;
        $std->tpEmis = $m_tpMDF; //
        $std->procEmi = '0'; //0 - emissão de MDF-e com aplicativo do contribuinte 
        $std->verProc = '4.3.1';
        $std->UFIni = $filialArray[0]['UF'];
        $std->UFFim = $filialArray[0]['UF'];

        //Verificar se é opcional
        //$std->dhIniViagem = '2022-10-20T10:00:48-03:00';
        //$std->indCanalVerde = '1';
        //$std->indCarregaPosterior = '1';
        $mdfe->tagide($std);

        // for {
        $infMunCarrega = new \stdClass();
        $infMunCarrega->cMunCarrega = $filialArray[0]['CODMUNICIPIO'];
        $infMunCarrega->xMunCarrega = $filialArray[0]['CIDADE'];
        $mdfe->taginfMunCarrega($infMunCarrega);
        // }

        // for
        //$infPercurso = new \stdClass();
        //$infPercurso->UFPer = "PR";
        //$mdfe->taginfPercurso($infPercurso);
        // }

        /*
         * fim ide
         */

        /*
         * Grupo emit ( Emitente )
         */
        $stdEmit = new \stdClass();
        $stdEmit->CNPJ = $filialArray[0]['CNPJ'];
        $stdEmit->IE = $filialArray[0]['INSCESTADUAL'];
        $stdEmit->xNome = $this->removeAcentos($filialArray[0]['NOMEEMPRESA']);
        $stdEmit->xFant = $filialArray[0]['NOMEFANTASIA'];
        $mdfe->tagemit($stdEmit);

        /*
         * Grupo EnderEmit ( Emitente )
         */
        $stdEnderEmit = new \stdClass();
        $stdEnderEmit->xLgr = $this->removeAcentos($filialArray[0]['TIPOEND']." ".$filialArray[0]['TITULOEND']." ".$filialArray[0]['ENDERECO']);
        $stdEnderEmit->nro = $filialArray[0]['NUMERO'];
        $stdEnderEmit->xCpl = $filialArray[0]['COMPLEMENTO'];
        $stdEnderEmit->xBairro = $xBairro = $this->removeAcentos($filialArray[0]['BAIRRO']);
        $stdEnderEmit->cMun = $filialArray[0]['CODMUNICIPIO'];
        $stdEnderEmit->xMun = $filialArray[0]['CIDADE'];
        $stdEnderEmit->CEP = $CEP = $filialArray[0]['CEP'];
        $stdEnderEmit->UF = $filialArray[0]['UF'];
        $stdEnderEmit->fone = $filialArray[0]['FONEAREA'].$filialArray[0]['FONENUM'];
        $stdEnderEmit->email = $filialArray[0]['EMAIL'];
        $mdfe->tagenderEmit($stdEnderEmit);
        /*
         * fim emit
         */

        /*
         * Grupo rodo ( Rodoviário )
         */

        /* Grupo infANTT */
        //$infANTT = new \stdClass();
        //$infANTT->RNTRC = '12345678';
        //$mdfe->taginfANTT($infANTT);

        /* informações do CIOT */
        // for {
        //$infCIOT = new \stdClass();
        //$infCIOT->CIOT = '123456789012';
        //$infCIOT->CPF = '99307100004';
        //$infCIOT->CNPJ = '86104175000164';
        //$mdfe->taginfCIOT($infCIOT);
        // }

        /* informações do Vale Pedágio */
        // for {
        //$valePed = new \stdClass();
        //$valePed->CNPJForn = '11222333444455';
        //$valePed->CNPJPg = '66777888999900';
        //$valePed->CPFPg = '11122233355';
        //$valePed->nCompra = '777778888999999';
        //$valePed->vValePed = '100.00';
        //$mdfe->tagdisp($valePed);
        // }

        /* informações do contratante */
        // for {
        $infContratante = new \stdClass();
        $infContratante->CNPJ = '00000000000000';
        $mdfe->taginfContratante($infContratante);
        // }

        /* fim infANTT */

        /* Grupo veicTracao */
        $veicTracao = new \stdClass();
        $veicTracao->cInt = $dadosObjVeiculo[0]['IDVEICULO'];
        $veicTracao->placa = $dadosObjVeiculo[0]['PLACA'];
        $veicTracao->tara = $dadosObjVeiculo[0]['TARA'];
        $veicTracao->capKG = $dadosObjVeiculo[0]['CAPACIDADEKG'];
        $veicTracao->tpRod = $dadosObjVeiculo[0]['TIPORODADO'];
        $veicTracao->tpCar = $dadosObjVeiculo[0]['TIPOCARROCERIA'];
        $veicTracao->UF = $dadosObjVeiculo[0]['PLACAUF'];

        $condutor = new \stdClass();
        $condutor->xNome = $dadosObjCondutor[0]['NOME'];
        $condutor->CPF = $dadosObjCondutor[0]['CNPJCPF'];// '08221275476';//$dadosObjCondutor[0]['CNPJCPF'];
        $veicTracao->condutor = [$condutor];

        //$prop = new \stdClass();
        //$prop->CPF = '11122233344';
        //$prop->CNPJ = '';
        //$prop->RNTRC = '12345678';
        //$prop->xNome = 'JOAO DA SILVA';
        //$prop->IE = '03857164';
        //$prop->UF = 'PR';
        //$prop->tpProp = '1';
        //$veicTracao->prop = $prop;

        $mdfe->tagveicTracao($veicTracao);

        /* fim veicTracao */

        /* Grupo veicReboque */
        //$veicReboque = new \stdClass();
        //$veicReboque->cInt = '02';
        //$veicReboque->placa = 'XXX1111';
        //$veicReboque->tara = '8350';
        //$veicReboque->capKG = '15000';
        //$veicReboque->tpCar = '02';
        //$veicReboque->UF = 'SP';

        //$prop = new \stdClass();
        //$prop->CPF = '01234567890';
        //$prop->CNPJ = '';
        //$prop->RNTRC = '12345678';
        //$prop->xNome = 'JOAO DA SILVA';
        //$prop->IE = '03857164';
        //$prop->UF = 'PR';
        //$prop->tpProp = '1';
        //$veicReboque->prop = $prop;

        //$mdfe->tagveicReboque($veicReboque);
        /* fim veicReboque */

        //$lacRodo = new \stdClass();
        //$lacRodo->nLacre = '1502400';
        //$mdfe->taglacRodo($lacRodo);
        /* fim rodo */

        /*
         * Grupo infDoc ( Documentos fiscais )
         */
        //$infMunDescarga = new \stdClass();
        //$infMunDescarga->cMunDescarga = $pessoaDestArray[0]['CODMUNICIPIO'];
        //$infMunDescarga->xMunDescarga = $pessoaDestArray[0]['CIDADE'];
        //$infMunDescarga->nItem = 0;
        //$mdfe->taginfMunDescarga($infMunDescarga);

        /* infCTe */
        //$std = new \stdClass();
        //$std->chCTe = '35310800000000000372570010001999091000027765';
        //$std->SegCodBarra = '012345678901234567890123456789012345';
        //$std->indReentrega = '1';
        //$std->nItem = 0;

        /* Informações das Unidades de Transporte (Carreta/Reboque/Vagão) */
        //$stdinfUnidTransp = new \stdClass();
        //$stdinfUnidTransp->tpUnidTransp = '1';
        //$stdinfUnidTransp->idUnidTransp = 'AAA-1111';

        /* Lacres das Unidades de Transporte */
        // /$stdlacUnidTransp = new \stdClass();
        // /$stdlacUnidTransp->nLacre = ['00000001', '00000002'];

        // /$stdinfUnidTransp->lacUnidTransp = $stdlacUnidTransp;

        /* Informações das Unidades de Carga (Containeres/ULD/Outros) */
        //$stdinfUnidCarga = new \stdClass();
        //$stdinfUnidCarga->tpUnidCarga = '1';
        //$stdinfUnidCarga->idUnidCarga = '01234567890123456789';

        /* Lacres das Unidades de Carga */
        //$stdlacUnidCarga = new \stdClass();
        //$stdlacUnidCarga->nLacre = ['00000001', '00000002'];

        //$stdinfUnidCarga->lacUnidCarga = $stdlacUnidCarga;
        //$stdinfUnidCarga->qtdRat = '3.50';

        //$stdinfUnidTransp->infUnidCarga = [$stdinfUnidCarga];
        //$stdinfUnidTransp->qtdRat = '3.50';

        //$std->infUnidTransp = [$stdinfUnidTransp];

        /* transporte de produtos classificados pela ONU como perigosos */
        //$stdperi = new \stdClass();
        //$stdperi->nONU = '1234';
        //$stdperi->xNomeAE = 'testeNome';
        //$stdperi->xClaRisco = 'testeClaRisco';
        //$stdperi->grEmb = 'testegrEmb';
        //$stdperi->qTotProd = '1';
        //$stdperi->qVolTipo = '1';
        //$std->peri = [$stdperi];

        /* Grupo de informações da Entrega Parcial (Corte de Voo) */
        //$stdinfEntregaParcial = new \stdClass();
        //$stdinfEntregaParcial->qtdTotal = '1234.56';
        //$stdinfEntregaParcial->qtdParcial = '1234.56';
        //$std->infEntregaParcial = $stdinfEntregaParcial;

        //$mdfe->taginfCTe($std);
        
        $ultimaUf = null;
        $nrItemMesmaUF = null;
        for($i = 0; $i < count($NotasFiscaisMDF); $i++){

            if(($NotasFiscaisMDF[$i]['CODMUNICIPIO'] !== $ultimaUf) || ($ultimaUf == null)){

                $infMunDescarga = new \stdClass();
                $infMunDescarga->cMunDescarga = $NotasFiscaisMDF[$i]['CODMUNICIPIO']; //$pessoaDestArray[0]['CODMUNICIPIO'];
                $infMunDescarga->xMunDescarga = $NotasFiscaisMDF[$i]['CIDADE']; //$pessoaDestArray[0]['CIDADE'];
                $infMunDescarga->nItem = $i;
                $mdfe->taginfMunDescarga($infMunDescarga);

                $stdInfNfe = new \stdClass();
                $stdInfNfe->chNFe = $NotasFiscaisMDF[$i]['CHNFE'];
                $stdInfNfe->SegCodBarra = '';//'012345678901234567890123456789012345';
                $stdInfNfe->indReentrega = '1';
                $stdInfNfe->nItem = $i;
                $mdfe->taginfNFe($stdInfNfe);
                //variaveis para notas do mesmo estado
                $ultimaUf = $NotasFiscaisMDF[$i]['CODMUNICIPIO'] ;
                $nrItemMesmaUF = $stdInfNfe->nItem;
    
            }else{

                $stdInfNfe = new \stdClass();
                $stdInfNfe->chNFe = $NotasFiscaisMDF[$i]['CHNFE'];
                $stdInfNfe->SegCodBarra = '';//'012345678901234567890123456789012345';
                $stdInfNfe->indReentrega = '1';
                
                $stdInfNfe->nItem = $nrItemMesmaUF;
                $mdfe->taginfNFe($stdInfNfe);
                $ultimaUf = $NotasFiscaisMDF[$i]['CODMUNICIPIO'] ;
            }
        }

        /* infCTe */
        //$std = new \stdClass();
        //$std->chCTe = '35310800000000000372570010001998991000614492';
        //$std->nItem = 1;
        //$mdfe->taginfCTe($std);

        /* infNFe */


        //$stdInfNfe = new \stdClass();
        //$stdInfNfe->chNFe = '35310800000000000372570010001999091000099999';
        //$stdInfNfe->SegCodBarra = '';//'012345678901234567890123456789012345';
        //$stdInfNfe->indReentrega = '1';
        //$stdInfNfe->nItem = 0;
        //$mdfe->taginfNFe($stdInfNfe);

        //$stdInfNfe = new \stdClass();
        //$stdInfNfe->chNFe = '35310800000000000372570010001988091000099998';
        //$stdInfNfe->SegCodBarra = '';//'012345678901234567890123456789012345';
        //$stdInfNfe->indReentrega = '0';
        //$stdInfNfe->nItem = 1;
        //$mdfe->taginfNFe($stdInfNfe);

        
        // Informações das Unidades de Transporte (Carreta/Reboque/Vagão)
        //$stdinfUnidTransp = new \stdClass();
        //$stdinfUnidTransp->tpUnidTransp = '1';
        //$stdinfUnidTransp->idUnidTransp = 'AAA-1111';

        // Lacres das Unidades de Transporte
        //$stdlacUnidTransp = new \stdClass();
        //$stdlacUnidTransp->nLacre = ['00000001', '00000002'];

        //$stdinfUnidTransp->lacUnidTransp = $stdlacUnidTransp;

        // Informações das Unidades de Carga (Containeres/ULD/Outros)
        //$stdinfUnidCarga = new \stdClass();
        //$stdinfUnidCarga->tpUnidCarga = '1';
        //$stdinfUnidCarga->idUnidCarga = '01234567890123456789';

        // lacres das Unidades de Carga
        //$stdlacUnidCarga = new \stdClass();
        //$stdlacUnidCarga->nLacre = ['00000001', '00000002'];

        //$stdinfUnidCarga->lacUnidCarga = $stdlacUnidCarga;
        //$stdinfUnidCarga->qtdRat = '3.50';

        //$stdinfUnidTransp->infUnidCarga = [$stdinfUnidCarga];
        //$stdinfUnidTransp->qtdRat = '3.50';

        //$std->infUnidTransp = [$stdinfUnidTransp];

        // transporte de produtos classificados pela ONU como perigosos
        //$stdperi = new \stdClass();
        //$stdperi->nONU = '1234';
        //$stdperi->xNomeAE = 'testeNome';
        //$stdperi->xClaRisco = 'testeClaRisco';
        //$stdperi->grEmb = 'testegrEmb';
        //$stdperi->qTotProd = '1';
        //$stdperi->qVolTipo = '1';
        //$std->peri = [$stdperi];


        /* infMDFeTransp */

        //$std = new \stdClass();
        //$std->chMDFe = '35310800000000000372570010001999091000088888';
        //$std->indReentrega = '1';
        //$std->nItem = 0;

        // Informações das Unidades de Transporte (Carreta/Reboque/Vagão)
        //$stdinfUnidTransp = new \stdClass();
        //$stdinfUnidTransp->tpUnidTransp = '1';
        //$stdinfUnidTransp->idUnidTransp = 'AAA-1111';

        // Lacres das Unidades de Transporte
        //$stdlacUnidTransp = new \stdClass();
        //$stdlacUnidTransp->nLacre = ['00000001', '00000002'];

        //$stdinfUnidTransp->lacUnidTransp = $stdlacUnidTransp;

        // Informações das Unidades de Carga (Containeres/ULD/Outros)
        //$stdinfUnidCarga = new \stdClass();
        //$stdinfUnidCarga->tpUnidCarga = '1';
        //$stdinfUnidCarga->idUnidCarga = '01234567890123456789';

        // lacres das Unidades de Carga
        //$stdlacUnidCarga = new \stdClass();
        //$stdlacUnidCarga->nLacre = ['00000001', '00000002'];

        //$stdinfUnidCarga->lacUnidCarga = $stdlacUnidCarga;
        //$stdinfUnidCarga->qtdRat = '3.50';

        //$stdinfUnidTransp->infUnidCarga = [$stdinfUnidCarga];
        //$stdinfUnidTransp->qtdRat = '3.50';

        //$std->infUnidTransp = [$stdinfUnidTransp];

        // transporte de produtos classificados pela ONU como perigosos
        //$stdperi = new \stdClass();
        //$stdperi->nONU = '1234';
        //$stdperi->xNomeAE = 'testeNome';
        //$stdperi->xClaRisco = 'testeClaRisco';
        //$stdperi->grEmb = 'testegrEmb';
        //$stdperi->qTotProd = '1';
        //$stdperi->qVolTipo = '1';
        //$std->peri = [$stdperi];

        //$mdfe->taginfMDFeTransp($std);

        /* fim grupo infDoc */

        /* Grupo do Seguro */
        //$std = new \stdClass();
        //$std->respSeg = '1';

        /* Informações da seguradora */
        //$stdinfSeg = new \stdClass();
        //$stdinfSeg->xSeg = 'SOMPO SEGUROS';
        //$stdinfSeg->CNPJ = '11222333444455';

        //$std->infSeg = $stdinfSeg;
        //$std->nApol = '11223344555';
        //$std->nAver = ['0572012190000000000007257001000199899140', '0572012190000000000007257001000199708140'];
        //$mdfe->tagseg($std);}
        /* fim grupo Seguro */

        /* grupo de totais */
        $qtdDocs = count($NotasFiscaisMDF);

        $stdTotal = new \stdClass();
        $stdTotal->qNFe = $qtdDocs;
        $stdTotal->vCarga = $mdfArray[0]["TOTALCARGA"];
        $stdTotal->cUnid = $mdfArray[0]["UNIDADECARGA"]; 
        $stdTotal->qCarga = $mdfArray[0]["PESOCARGA"];
        $mdfe->tagtot($stdTotal);
        /* fim grupo de totais */

        /* grupo de lacres */
        // for {
        $stdLacre = new \stdClass();
        $stdLacre->nLacre = '0000001';
        $mdfe->taglacres($stdLacre);
        // }
        /* fim grupo de lacres */

        /* grupo Autorizados para download do XML do DF-e */
        // for {
        //$std = new \stdClass();
        //$std->CNPJ = '11122233344455';
        //$mdfe->tagautXML($std);
        // }

        $prodPred = new \stdClass();
        $prodPred->tpCarga = '01';
        $prodPred->xProd = 'teste';
        $prodPred->cEAN = null;
        $prodPred->NCM = null;

        //$localCarrega = new \stdClass();
        //$localCarrega->CEP = '00000000';
        //$localCarrega->latitude = null;
        //$localCarrega->longitude = null;

        //$localDescarrega = new \stdClass();
        //$localDescarrega->CEP = '00000000';
        //$localDescarrega->latitude = null;
        //$localDescarrega->longitude = null;

        //$lotacao = new \stdClass();
        //$lotacao->infLocalCarrega = $localCarrega;
        //$lotacao->infLocalDescarrega = $localDescarrega;

        //$prodPred->infLotacao = $lotacao;

        $mdfe->tagprodPred($prodPred);


        //$infPag = new \stdClass();
        //$infPag->xNome = 'JOSE';
        //$infPag->CPF = '01234567890';
        //$infPag->CNPJ = null;
        //$infPag->idEstrangeiro = null;

        //$componentes = [];
        // {
        //$Comp = new \stdClass();
        //$Comp->tpComp = '01';
        //$Comp->vComp = 10.00;
        //$Comp->xComp = 'NADA';
        //$componentes[] = $Comp;
        // }
        //$infPag->Comp = $componentes;
        //$infPag->vContrato = 10.00;
        //$infPag->indPag = 1;

        //$parcelas = [];
        // {
        //$infPrazo = new \stdClass();
        //$infPrazo->nParcela = '001';
        //$infPrazo->dVenc = '2020-04-30';
        //$infPrazo->vParcela = 10.00;
        //$parcelas[] = $infPrazo;
        // }
        //$infPag->infPrazo = $parcelas;

        //$infBanc = new \stdClass();
        //$infBanc->codBanco = '341';
        //$infBanc->codAgencia = '12345';
        //$infBanc->CNPJIPEF = null;
        //$infPag->infBanc = $infBanc;

        //$mdfe->taginfPag($infPag);


        /* grupo Informações Adicionais */
        $stdInfAdd = new \stdClass();
        $stdInfAdd->infCpl = $mdfArray[0]["OBSERVACAO"];
        $stdInfAdd->infAdFisco = 'Contrato No 007018 2 CARR';
        $mdfe->taginfAdic($stdInfAdd);
        /* fim grupo Informações Adicionais */
        
        $xml = $mdfe->getXML(); // O conteúdo do XML fica armazenado na variável $xml

        try{

            (stristr( $this->path, $this->slash )) ? '' : $this->slash = '\\'; 
            define( 'BASE_DIR_ENTRADA', $this->path.$this->slash.'entradas'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfExt); 
            define( 'BASE_DIR_ASSINADA', $this->path.$this->slash.'assinadas'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfExt); 
            define( 'BASE_DIR_ENVIADA_APROVADAS', $this->path.$this->slash.'enviadas'.$this->slash.'aprovadas'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfExt); 
            define( 'BASE_DIR_TEMP', $this->path.$this->slash.'temporarias'.$this->slash.$this->anomes.$this->slash); 
            define( 'BASE_DIR_PDF', $this->path.$this->slash.'pdf'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfExtPdf); 
            define( 'BASE_HTTP_PDF', ADMhttpCliente.$this->slash.'mdfe'.$this->slash.$this->m_empresaid.$this->slash.ADMambDesc.$this->slash.'pdf'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfExtPdf);

        }catch (Exception $e) {
            echo $e->getMessage();
        }

        if (!file_exists($this->path.$this->slash.'mdf'.$this->slash.$this->anomes.$this->slash.$gerarXML.$this->slash)) {
            mkdir($this->path.$this->slash.'mdf'.$this->slash.$this->anomes.$this->slash.$gerarXML.$this->slash, 0777,true);
        }
        $erro .= ' ERRO: '.file_put_contents($this->path.$this->slash.'mdf'.$this->slash.$this->anomes.$this->slash.$chave.$this->mdfProt,$xml).'<br>';


        //header("Content-type: text/xml");
        //echo '<pre>';
        //print($mdfe->getXML());
        //echo '<pre>';
        return $xml;

        //Fim XML ------------------------------------------------------------------------------------------------------------------
    }
    //========================FIM makeMdfe====================================


    public function MdfeTestaEnvio($idMDF=null){

        //array contendo dados do manifesto
        $mdfArray = $this->selectManifestoFiscal($idMDF, $conn);
        
        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
        
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));

            /*$xml = '<?xml version="1.0" encoding="UTF-8"?><MDFe xmlns="http://www.portalfiscal.inf.br/mdfe"><infMDFe Id="MDFe41190822545265000108580260000000081326846774" versao="3.00"><ide><cUF>41</cUF><tpAmb>2</tpAmb><tpEmit>2</tpEmit><mod>58</mod><serie>26</serie><nMDF>8</nMDF><cMDF>32684677</cMDF><cDV>4</cDV><modal>1</modal><dhEmi>2019-08-14T11:35:01-03:00</dhEmi><tpEmis>2</tpEmis><procEmi>0</procEmi><verProc>3.9.8</verProc><UFIni>PR</UFIni><UFFim>RS</UFFim><infMunCarrega><cMunCarrega>4108403</cMunCarrega><xMunCarrega>Francisco Beltrao</xMunCarrega></infMunCarrega><infPercurso><UFPer>SC</UFPer></infPercurso></ide><emit><CNPJ>22545265000108</CNPJ><IE>9069531021</IE><xNome>EMPRESA DEMONSTRACAO LTDA</xNome><xFant>FABRICA DE SOFTWARE MATRIZ</xFant><enderEmit><xLgr>AVENIDA JULIO ASSIS CAVALHEIRO</xLgr><nro>1</nro><xBairro>CENTRO</xBairro><cMun>4108403</cMun><xMun>Francisco Beltrao</xMun><CEP>85601000</CEP><UF>PR</UF><fone>4635230686</fone></enderEmit></emit><infModal versaoModal="3.00"><rodo xmlns="http://www.portalfiscal.inf.br/mdfe"><infANTT><RNTRC>12345678</RNTRC><infContratante><CPF>01234567890</CPF></infContratante></infANTT><veicTracao><placa>ABC1011</placa><RENAVAM>32132132131</RENAVAM><tara>0</tara><prop><CPF>01234567890</CPF><RNTRC>88888888</RNTRC><xNome>ALISSON</xNome><IE/><UF>PR</UF><tpProp>0</tpProp></prop><condutor><xNome>CLEITON</xNome><CPF>06844990960</CPF></condutor><tpRod>01</tpRod><tpCar>01</tpCar><UF>PR</UF></veicTracao><veicReboque><placa>ABC1012</placa><RENAVAM>12313213213</RENAVAM><tara>0</tara><capKG>20000</capKG><capM3>180</capM3><prop><CPF>01234567890</CPF><RNTRC>88888888</RNTRC><xNome>ALISSON</xNome><IE/><UF>PR</UF><tpProp>0</tpProp></prop><tpCar>03</tpCar><UF>PR</UF></veicReboque></rodo></infModal><infDoc><infMunDescarga><cMunDescarga>4314902</cMunDescarga><xMunDescarga>Porto Alegre</xMunDescarga><infNFe><chNFe>41190122545265000108550270000004491369658540</chNFe></infNFe></infMunDescarga><infMunDescarga><cMunDescarga>4300208</cMunDescarga><xMunDescarga>Ajuricaba</xMunDescarga><infNFe><chNFe>41190522545265000108550270000005731334929373</chNFe></infNFe></infMunDescarga></infDoc><tot><qNFe>2</qNFe><vCarga>72.04</vCarga><cUnid>01</cUnid><qCarga>3.0000</qCarga></tot><lacres><nLacre>3113213213213213213213</nLacre></lacres></infMDFe></MDFe>';*/
            
            $xml = $this->makeMdfe($idMDF, $this->m_empresacentrocusto);

            $erroNf = $this->m_chave."<br>";

            //Assina xml
            try {
                $xmlAssinado = $tools->signMDFe($xml);      
            } catch (\Exception $e) {
                $this->trataErro('ASSINA - ', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
            }


            if (!file_exists($this->path.$this->slash.'assinadas'.$this->slash.$this->anomes.$this->slash)) {
                mkdir($this->path.$this->slash.'assinadas'.$this->slash.$this->anomes.$this->slash, 0777, true);
            }  
            file_put_contents($this->path.$this->slash.'assinadas'.$this->slash.$this->anomes.$this->slash.$this->m_chave.$this->mdfExt, $xmlAssinado);
            
            //header('Content-type: text/plain; charset=UTF-8');
            //return $xmlAssinado;
            
            $resp = $tools->sefazEnviaLote([$xmlAssinado], rand(1, 10000));

            sleep(1);

            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);


            //$resp = $tools->sefazConsultaRecibo($std->infRec->nRec);
            //$std = $st->toStd($resp);

            // validação de timeout -  Lote em processamento
             $i=0;
             while ($i<=5){
                 $resp = $tools->sefazConsultaRecibo($std->infRec->nRec);
                 $std = $st->toStd($resp);
                 $cStatConsulta = $std->cStat;
                 if ($cStatConsulta ==105) {
                     $i++;
                     sleep(5);
                 }else{
                     $i=99;
                 }
             }
            
            switch($std->protMDFe->infProt->cStat){
                case '745': //"Rejeição: O tipo de transportador não ser informado quando não estiver informado proprietário do veículo de tração" (nao informar a tag $std->tpTransp)
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');

                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '744': //"Rejeição: O tipo de transportador deve ser ETC ou CTC quando informado CNPJ do proprietário do veículo de tração"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '743': //"Rejeição: O tipo de transportador deve ser TAC quando informado CPF do proprietário do veículo de tração" $std->tpTransp
                   $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');

                   return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '740': //"Rejeição: O proprietário do veículo quando informado deve ser diferente do emitente do MDF-e" ($prop->CNPJ)
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '686': //Rejeição: Existe MDF-e não encerrado há mais de 30 dias para o emitente
                            //[chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '680': //"Rejeição: Município de descarregamento duplicado no MDF-e" (infMunDescarga)
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '667': //"Rejeição: Quantidade informada no grupo de totalizadores não confere com a quantidade de documentos relacionada"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '645': //"Rejeição: CPF do condutor inválido" ($condutor->CPF)
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
 
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '611': //"Rejeição: Existe MDF-e não encerrado para esta placa, tipo de emitente e UF descarregamento"
                    //611 - Rejeição: Existe MDF-e não encerrado para esta placa, tipo de emitente e UF descarregamento [chMDFe Não Encerrada:41221180193204000125580000000000041017849252][NroProtocolo:941220000023473]!
                    
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
                    
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '578': //"Rejeitar se não estiver informado pelo menos um tomador de serviço" (grupoinfContratante)
                        $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
                    
                        return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '539': //"Rejeicao: Duplicidade de MDF-e, com diferença na Chave de Acesso "
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
                    
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '228': //"Rejeição: Data de Emissao muito atrasada"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
                    
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '212': //"Rejeição: Data/hora de emissão MDF-e posterior a data/hora de recebimento"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL REJEITADO');
                    
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '105': //"Arquivo em processamento"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL EM PROCESSAMENTO');
                    
                    return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                break;
                case '100': //"Autorizado o uso do MDF-e"
                    $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO AUTORIZADO');

                    // altera dados danfe na est_manifesto_fiscal
                    // inclui tabela RECIBO *****************
                    $aResposta['cDanfe'] = BASE_HTTP_PDF;
                    $this->setPathDanfe($aResposta['cDanfe']);
                    $this->setChMdfe($std->protMDFe->infProt->chMDFe);
                    $this->setDhRecbto($std->protMDFe->infProt->dhRecbto);
                    $this->setProtMdfe($std->protMDFe->infProt->nProt);
                    $this->setVerAplic($std->protMDFe->infProt->verAplic);
                    $this->setDigVal($std->protMDFe->infProt->digVal);
                    // $nfOBJ->setNumRecibo($recibo);
                    $this->alteraMDfPath();

                    // envia email
                    //if (($nfArray[0]['MODELO'] == 58) and ($aResposta['cStatus'] == '100')){
                    //    $erro = 'EMAIL line: 2367<br>'.$this->enviaEmailDANFE($nfArray[0]['MODELO'], $pessoaDestArray[0]['EMAILNFE'], 
                    //                $pessoaDestArray[0]['EMAIL'],$status->protNFe->infProt->chNFe, $dhEmi,$cNF,$serie,$xNome);
                    //}
                    
                    // DAMDFE GRAVA PDF E IMPRIME
                    try {
                        $pathLogo = ADMimg.'/logo0'.$this->m_empresaid.'.jpg';
                        //$logo = 'data://text/plain;base64,'. base64_encode(file_get_contents($pathLogo));
                        
                        if (!file_exists($this->path.$this->slash.'pdf'.$this->slash.$this->anomes.$this->slash)) {
                        mkdir($this->path.$this->slash.'pdf'.$this->slash.$this->anomes.$this->slash, 0775, true);}  
                        $pdfDamfe = BASE_DIR_PDF;

                        
                        $damdfe = new NFePHP\DA\MDFe\Damdfe($xmlAssinado, 'P', 'A4', $pathLogo, 'I', '');

                        //$damdfe->buildMDFe();
                        $erro = 'ERRO: '.$damdfe->printMDFe($pdfDamfe, 'F'); //Salva o PDF na pasta

                    } catch (InvalidArgumentException $e) {
                        trataErro('PDF', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
                    }
                    
                break;
                default:
                $incluiEvento =  $this->incluiMdfEvento($idMDF, $std->protMDFe->infProt->verAplic, $std->protMDFe->infProt->cStat ,'E', '1', $mdfArray[0]['NUM_MDF'], $mdfArray[0]['NUM_MDF'], 'MANIFESTO FISCAL EM PROCESSAMENTO');
                return $std->protMDFe->infProt->cStat." - ".$std->protMDFe->infProt->xMotivo;
                
            }
            $arrayReturn = $std->protMDFe->infProt->cStat;
            // $arrayReturn = [
            //     "xmlAssinado" => $xmlAssinado,
            //     "codStatus" => $std->protMDFe->infProt->cStat,
            // ];
            //echo '<pre>';
            //print_r($xmlAssinado);
            //echo "</pre>";
            return $arrayReturn;

        } catch (Exception $e) {
            $param= 'prodPred';
            $resultCatch = strripos($e, $param);
            if($resultCatch === false){ //erro verificacao de nf relaciondas no manifesto
                return $e->getMessage();
                
            }else{
                return 'Manifesto sem NFe relacionada';
            }
        }

    }
    //=========================FIM MdfeTestaEnvio===================================

        public function envioMdfe(){

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {

            //exemplo para teste
            /*$xml = '<?xml version="1.0" encoding="UTF-8"?><MDFe xmlns="http://www.portalfiscal.inf.br/mdfe"><infMDFe Id="MDFe41190822545265000108580260000000081326846774" versao="3.00"><ide><cUF>41</cUF><tpAmb>2</tpAmb><tpEmit>2</tpEmit><mod>58</mod><serie>26</serie><nMDF>8</nMDF><cMDF>32684677</cMDF><cDV>4</cDV><modal>1</modal><dhEmi>2019-08-14T11:35:01-03:00</dhEmi><tpEmis>2</tpEmis><procEmi>0</procEmi><verProc>3.9.8</verProc><UFIni>PR</UFIni><UFFim>RS</UFFim><infMunCarrega><cMunCarrega>4108403</cMunCarrega><xMunCarrega>Francisco Beltrao</xMunCarrega></infMunCarrega><infPercurso><UFPer>SC</UFPer></infPercurso></ide><emit><CNPJ>22545265000108</CNPJ><IE>9069531021</IE><xNome>EMPRESA DEMONSTRACAO LTDA</xNome><xFant>FABRICA DE SOFTWARE MATRIZ</xFant><enderEmit><xLgr>AVENIDA JULIO ASSIS CAVALHEIRO</xLgr><nro>1</nro><xBairro>CENTRO</xBairro><cMun>4108403</cMun><xMun>Francisco Beltrao</xMun><CEP>85601000</CEP><UF>PR</UF><fone>4635230686</fone></enderEmit></emit><infModal versaoModal="3.00"><rodo xmlns="http://www.portalfiscal.inf.br/mdfe"><infANTT><RNTRC>12345678</RNTRC><infContratante><CPF>01234567890</CPF></infContratante></infANTT><veicTracao><placa>ABC1011</placa><RENAVAM>32132132131</RENAVAM><tara>0</tara><prop><CPF>01234567890</CPF><RNTRC>88888888</RNTRC><xNome>ALISSON</xNome><IE/><UF>PR</UF><tpProp>0</tpProp></prop><condutor><xNome>CLEITON</xNome><CPF>06844990960</CPF></condutor><tpRod>01</tpRod><tpCar>01</tpCar><UF>PR</UF></veicTracao><veicReboque><placa>ABC1012</placa><RENAVAM>12313213213</RENAVAM><tara>0</tara><capKG>20000</capKG><capM3>180</capM3><prop><CPF>01234567890</CPF><RNTRC>88888888</RNTRC><xNome>ALISSON</xNome><IE/><UF>PR</UF><tpProp>0</tpProp></prop><tpCar>03</tpCar><UF>PR</UF></veicReboque></rodo></infModal><infDoc><infMunDescarga><cMunDescarga>4314902</cMunDescarga><xMunDescarga>Porto Alegre</xMunDescarga><infNFe><chNFe>41190122545265000108550270000004491369658540</chNFe></infNFe></infMunDescarga><infMunDescarga><cMunDescarga>4300208</cMunDescarga><xMunDescarga>Ajuricaba</xMunDescarga><infNFe><chNFe>41190522545265000108550270000005731334929373</chNFe></infNFe></infMunDescarga></infDoc><tot><qNFe>2</qNFe><vCarga>72.04</vCarga><cUnid>01</cUnid><qCarga>3.0000</qCarga></tot><lacres><nLacre>3113213213213213213213</nLacre></lacres></infMDFe></MDFe>';
            */

            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));

            //monta o xml
            $xml = $this->makeMdfe();

            //Assina xml
            $xmlAssinado = $tools->signMDFe($xml);
            
            header('Content-type: text/plain; charset=UTF-8');
            echo $xmlAssinado;

            //$resp = $tools->sefazEnviaLote([$xmlAssinado], rand(1, 10000));
            
            //$st = new NFePHP\MDFe\Common\Standardize();
            //$std = $st->toStd($resp);
            
            //sleep(3);
            
            //$resp = $tools->sefazConsultaRecibo($std->infRec->nRec);
            //$std = $st->toStd($resp);
            
            //echo '<pre>';
            //print_r($std);
            //echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    //========================FIM envioMdfe====================================

    public function MdfeConsultaRecibo(){

        $idMDF = 20458;

        // FALTA AJUSTAR PARA LOCALIZAR O NUMERO DO RECIBO
        $mdfArray = $this->selectManifestoFiscal($conn, $idMDF);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
        
            $recibo = '32165498754';
            $resp = $tools->sefazConsultaRecibo($recibo);
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);
        
            echo '<pre>';
            print_r($std);
            echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
    //==========================FIM MdfeConsultaRecibo==================================

    public function MdfeCancelar($idMdfe, $chaveMdfe, $protoMdfe, $justMdfe){
        $this->slash = '/';
        define('BASE_DIR_CERT', ADMnfe . $this->slash . $this->m_empresaid . $this->slash . 'certs' . $this->slash);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));

            $chave = $chaveMdfe;
            $xJust = $justMdfe;
            $nProt = $protoMdfe;
            $resp = $tools->sefazCancela($chave, $xJust, $nProt);

            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);

            switch($std->infEvento->cStat){
                case '220': //"Rejeição: MDF-e autorizado ha mais de 24 horas"
                    //grava o evento
                    $iEven = $this->incluiMdfEvento($idMdfe, $std->infEvento->verAplic, $std->infEvento->cStat, 'C', null, $idMdfe, $idMdfe, $std->infEvento->xMotivo, $conn = null);
                    if($iEven == true){
                        $returnMsg = ["msg" => $std->infEvento->xMotivo, "codStatus" => $std->infEvento->cStat];
                    }else{
                        $returnMsg = ["msg" => 'Não foi possível incluir evento, entre em contato com o suporte!', "codStatus" => $std->infEvento->cStat];
                    }
                break;
                case '135':
                    $iEven = $this->incluiMdfEvento($idMdfe, $std->infEvento->verAplic, $std->infEvento->cStat, 'C', null, $idMdfe, $idMdfe, $std->infEvento->xMotivo, $conn = null);
                    if($iEven == true){
                        //gravar em evento e atualizar manifesto 
                        $this->alteraMdfCancelamento($idMdfe, $protoMdfe, $justMdfe);
                        $returnMsg = ["msg" => $std->infEvento->xMotivo, "codStatus" => $std->infEvento->cStat];
                    }else{
                        $returnMsg = ["msg" => 'Não foi possível incluir evento, entre em contato com o suporte!', "codStatus" => $std->infEvento->cStat];
                    }
                break;
                case '609':
                    $iEven = $this->incluiMdfEvento($idMdfe, $std->infEvento->verAplic, $std->infEvento->cStat, 'C', null, $idMdfe, $idMdfe, $std->infEvento->xMotivo, $conn = null);
                    if($iEven == true){
                        $returnMsg = ["msg" => $std->infEvento->xMotivo, "codStatus" => $std->infEvento->cStat];
                    }else{
                        $returnMsg = ["msg" => $std->infEvento->xMotivo, "codStatus" => $std->infEvento->cStat];
                    }
                break;
                case '630':
                    $returnMsg = ["msg" => 'Entre em contato com o suporte! '.$std->infEvento->xMotivo, "codStatus" => $std->infEvento->cStat];
                break;
            }

            return $returnMsg;
            // echo '<pre>';
            // print_r($std);
            // echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    //==========================FIM MdfeCancelar==================================

    public function MdfeConsultarChave($idMDF, $conn=null){
        $this->slash = '/';
        define('BASE_DIR_CERT', ADMnfe . $this->slash . $this->m_empresaid . $this->slash . 'certs' . $this->slash);
        
        // CONSULTA DE DADOS DA NOTA FISCAL
        $mdfArray = $this->selectManifestoFiscal($idMDF, $conn=null );

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
            
            $chave = $mdfArray[0]['CHAVEACESSOMDFE'];

            //se nao localizar a chave informa na tela
            if(($chave == null) or ($chave == '')){
                $arrayReturn = [
                    "msgReturn" => 'Chave de acesso não localizada, ou MDFe em aberto!',
                    "codStatus" => '404',
                ];
                return $arrayReturn; 
            }
            
            $resp = $tools->sefazConsultaChave($chave);

            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);


            //grava os eventos
            switch ($std->cStat) {
                case '132': //"Encerramento de MDF-e homologado"
                    //monta array para retorno
                    $arrayReturn = [
                        "msgReturn" => 'Sefaz: ' . $std->xMotivo,
                        "codStatus" => $std->cStat,
                    ];
                    return $arrayReturn;
                break;
                case '100': //"Autorizado o uso do MDF-e"
                    //monta array para retorno
                    $arrayReturn = [
                        "msgReturn" => 'Sefaz: ' . $std->xMotivo,
                        "codStatus" => $std->cStat,
                    ];
                    return $arrayReturn;
                break;
                case '101': //"Cancelamento de MDF-e homologado"
                    //monta array para retorno
                    $arrayReturn = [
                        "msgReturn" => 'Sefaz: ' . $std->xMotivo,
                        "codStatus" => $std->cStat,
                    ];
                    return $arrayReturn;
                break;
            }
            //echo '<pre>';
            //print_r($std);
            //echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    //===========================FIM MdfeConsultarChave=================================

    public function MdfeConsultaEncerrado(){ //O objetivo é visualizar os MDF-es que ainda não foram encerrados na SEFAZ
        $this->slash = '/';
        define('BASE_DIR_CERT', ADMnfe . $this->slash . $this->m_empresaid . $this->slash . 'certs' . $this->slash);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
        
            $resp = $tools->sefazConsultaNaoEncerrados();
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);

            switch($std->cStat){
                case '111':
                    $arrayReturn = [
                        "msgReturn" => $std->xMotivo,
                        "result" => $std->infMDFe,
                    ];
                break;
                case '112':
                    $arrayReturn = [
                        "msgReturn" => $std->xMotivo,
                        "result" => $std->cStat,
                    ];
                break;
            }

            return $arrayReturn;
            // echo '<pre>';
            // print_r($std);
            // echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    //========================FIM MdfeConsultaEncerrado====================================


    public function MdfeEncerramento($idMDF=null, $conn=null){ //Adicionado o parâmetro $dtEnc no encerramento da MDFe.
        $this->slash = '/';
        define( 'BASE_DIR_CERT', ADMnfe.$this->slash.$this->m_empresaid.$this->slash.'certs'.$this->slash);

        //dados manifesto fiscal
        $mdfArray = $this->selectManifestoFiscal($idMDF, $conn);
        //dados empresa
        $filialArray = $this->selectEmpresaCC($mdfArray[0]['CENTROCUSTO']);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
            //verifica dados para encerramento
            if($mdfArray[0]['CHAVEACESSOMDFE'] !== ''){
                $chave = $mdfArray[0]['CHAVEACESSOMDFE'];
            }else{
                return 'Chave de acesso não localizada!';
            }
            if($mdfArray[0]['PROTOCOLOMDFE'] !== '' and isset($chave)){
                $nProt = $mdfArray[0]['PROTOCOLOMDFE'];
            }else{
                return 'Número do protocolo não localizado!';
            }
            if($filialArray[0]['CODMUNICIPIO'] !== '' and isset($nProt)){
                $cMun = $filialArray[0]['CODMUNICIPIO'];
            }else{
                return 'Código do município não localizado!';
            }

            //desenvolver script que grava uf da mdf no tabela manifesto_fiscal
            $cUF = '41';//$filialArray[0][''];
            $dtEnc = ''; // Opcional, caso nao seja preenchido pegara HOJE
            $resp = $tools->sefazEncerra($chave, $nProt, $cUF, $cMun, $dtEnc);
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);

            //grava os eventos
            switch($std->infEvento->cStat){
                case '135': //"Evento registrado e vinculado ao MDF-e"
                    //inclui evento na tabela nota_fiscal_eventos
                    $this->incluiMdfEvento($idMDF, $std->infEvento->verAplic, $std->infEvento->cStat, $std->infEvento->tpEvento, $std->infEvento->nSeqEvento, '', '', $std->infEvento->xEvento, $conn = null);

                    //gravar em evento e atualizar manifesto 
                    $this->alteraMdfEncerramento($idMDF, $std->infEvento->nProt, null);
                break;
                case '631': //"Rejeição: Duplicidade de evento"
                    //inclui evento na tabela nota_fiscal_eventos
                    $this->incluiMdfEvento($idMDF, $std->infEvento->verAplic, $std->infEvento->cStat, $std->infEvento->tpEvento, $std->infEvento->nSeqEvento, '', '', $std->infEvento->xEvento, $conn = null);

                    //gravar em evento e atualizar manifesto 
                    $this->alteraMdfEncerramento($idMDF, $std->infEvento->nProt, null);
                break;
            }
        
            //echo '<pre>';
            //print_r($std);
            //echo "</pre>";
            return $std->infEvento->cStat;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
    //=========================FIM MdfeEncerramento===================================



    public function MdfeIncluiCondutor(){ //O objetivo é realizar a inclusão ou substituição de condutores do manifesto eletrônico após iniciado o transporte, desde de que o documento não tenha sido encerrado ou cancelado.

        $idMDF = 20458;

        // FALTA AJUSTAR PARA LOCALIZAR O NUMERO DO RECIBO
        $mdfArray = $this->selectManifestoFiscal($conn, $idMDF);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
        
            $chave = '41190822545265000108580260000000081326846774';
            $nSeqEvento = '1';
            $xNome = 'CLEITON';
            $cpf = '01234567890';
            $resp = $tools->sefazIncluiCondutor($chave, $nSeqEvento, $xNome, $cpf);
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);
        
            echo '<pre>';
            print_r($std);
            echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
    //========================FIM MdfeIncluiCondutor====================================

    /*O objetivo é incluir documentos fiscais em um MDF-e emitido com indicação de carregamento 
    posterior. Esse procedimento é permitido quando, por ocasião do início da viagem, o emitente do MDF-e 
    de carga própria não tiver acesso aos documentos fiscais transportados e tratar-se de operação interna na UF.*/
    public function MdfeIncluiDfe(){ 

        $idMDF = 20458;

        // FALTA AJUSTAR PARA LOCALIZAR O NUMERO DO RECIBO
        $mdfArray = $this->selectManifestoFiscal($conn, $idMDF);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);
        
        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
        
            $chave = '41190822545265000108580260000000081326846774';
            $nSeqEvento = '1';
            $nProt = '950210000000205';
            $cMunCarrega = '5008305';
            $xMunCarrega = 'Três Lagoas';
            $cMunDescarga = '5008305';
            $xMunDescarga = 'Três Lagoas';
            $chNFe = '50201137182360000161550100000001051770795689';
        
            $resp = $tools->sefazIncluiDFe(
                $chave,
                $nProt,
                $cMunCarrega,
                $xMunCarrega,
                $cMunDescarga,
                $xMunDescarga,
                $chNFe,
                $nSeqEvento
            );
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);
        
            echo '<pre>';
            print_r($std);
            echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
    //=========================FIM MdfeIncluiDfe===================================

    public function MdfeConsultaStatus(){

        $idMDF = 20458;

        // FALTA AJUSTAR PARA LOCALIZAR O NUMERO DO RECIBO
        $mdfArray = $this->selectManifestoFiscal($conn, $idMDF);

        $configJson = c_tools::buscaConfigMdfe($this->m_empresaid);
        $certificadoDigital = c_tools::buscaCertificado($this->m_empresaid);
        $certificadoPW = c_tools::buscaCertificadoSenha($this->m_empresaid);

        try {
            $tools = new NFePHP\MDFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, $certificadoPW));
        
            $resp = $tools->sefazStatus();
        
            $st = new NFePHP\MDFe\Common\Standardize();
            $std = $st->toStd($resp);
        
            echo '<pre>';
            print_r($std);
            echo "</pre>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
    //==========================FIM MdfeConsultaStatus==================================

    public function selectEmpresaCC($centrocusto) {
        $sql = "SELECT * ";
        $sql .= "FROM amb_empresa ";
        $sql .= "WHERE (centrocusto = '" . $centrocusto . "') ";
        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    //============================================================ 

    public function selectManifestoFiscal($idMDF, $conn=null) {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM est_manifesto_fiscal ";
        $sql .= "WHERE (ID = " . $idMDF. ") ";

        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }
    //============================================================ 

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

    public function selectManifestoFiscalLetra($letra) {

        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[2]);
        $dataFim = c_date::convertDateTxt($par[3]);

        $sql = "SELECT DISTINCT n.*, r.descricao as filial, s.padrao as situacaonota, c.nomereduzido as nomecondutor, v.nome as veiculo ";
        $sql .= "FROM est_manifesto_fiscal n ";
        $sql .= "inner join fin_centro_custo r on n.centrocusto = r.centrocusto ";
        $sql .= "inner join amb_ddm s on ((s.alias='EST_MENU') and (s.campo='SituacaoNota') and (s.tipo = n.situacao)) ";
        $sql .= "inner join fin_cliente c on n.condutor = c.cliente ";
        $sql .= "inner join est_veiculo v on n.veiculotracao = v.idveiculo ";

        if($par[4] != ""){
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[4]) ? '':" $cond (n.NUM_MDF = '" . $par[4] . "') ";
        }else{
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[0]) ? '':" $cond (n.CENTROCUSTO = " . $par[0] . ") ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[1]) ? '':" $cond (N.SITUACAO= '" . $par[1] . "') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[2]) ? '':" $cond (N.EMISSAO >= '" . $dataIni . " 00:00:00') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[3]) ? '':" $cond (N.EMISSAO <= '" . $dataFim . " 23:59:59') ";

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[5]) ? '':" $cond (N.serie = '" . $par[5] . "') ";
            //condicao para verificar serie 0
            $sql .= $par[5] ==  '0' ? " $cond (N.serie = '" . $par[5] . "') " : '';

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($par[6]) ? '':" $cond (N.CONDUTOR = '" . $par[6] . "') ";

        }
        
        $sql .= "ORDER BY n.centrocusto, n.serie, n.num_mdf ";
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
        $this->setJustificativaCancelamento($manifestoFiscal[0]['JUSTIFICATIVACANCELAMENTO']);
        $this->setProtocoloEncerramento($manifestoFiscal[0]['PROTOCOLOENCERRAMENTO']);
        $this->setInfMunCarrega($manifestoFiscal[0]['INFMUNCARREGA']);
        $this->setQuantCte($manifestoFiscal[0]['QUANTCTE']);
        $this->setQuantNfe($manifestoFiscal[0]['QUANTNFE']);
        $this->setQuantMdfe($manifestoFiscal[0]['QUANTMDFE']);
        $this->setTotalCarga($manifestoFiscal[0]['TOTALCARGA']);
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
        $this->setRodoCodAgPorto($manifestoFiscal[0]['RODOCODAGPORTO']);
        $this->setVeiculoReboque1($manifestoFiscal[0]['VEICULOREBOQUE1']);
        $this->setVeiculoReboque2($manifestoFiscal[0]['VEICULOREBOQUE2']);
        $this->setVeiculoReboque3($manifestoFiscal[0]['VEICULOREBOQUE3']);
        $this->setProdPredTipoCarga($manifestoFiscal[0]['PRODPREDTIPOCARGA']);
        $this->setProdPredDescricao($manifestoFiscal[0]['PRODPREDDESCRICAO']);
        $this->setProdPredGtin($manifestoFiscal[0]['PRODPREDGTIN']);
        $this->setProdPredNcm($manifestoFiscal[0]['PRODPREDNCM']);
        $this->setProdPredCepLocalCarrega($manifestoFiscal[0]['PRODPREDCEPLOCALCARREGA']);
        $this->setProdPredCepLocalDescarreg($manifestoFiscal[0]['PRODPREDCEPLOCALDESCARREGA']);
        $this->setDhRecbto($manifestoFiscal[0]['DHRECBTO']);
        $this->setDigVal($manifestoFiscal[0]['DIGVAL']);
        $this->setVerAplic($manifestoFiscal[0]['VERAPLIC']);
        $this->setChaveAcessoMdfe($manifestoFiscal[0]['CHAVEACESSOMDFE']);
        
    }
    //============================================================


    public function geraNumMdf($modelo, $serie, $cc, $conn=null) {
        $numNf = 0;
        $numAtArray = null;

        $sql = "SELECT MAX(NUM_MDF) AS ULTIMAMDF FROM est_manifesto_fiscal WHERE (substr(CENTROCUSTO,1,2)=".substr($cc,0,2).") AND (`MOD`=".$modelo.") AND (SERIE=".$serie.") and (SITUACAO<>'I')";
        //ECHO $sql;
        
        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($banco->result):
            $numAtArray = $banco->resultado;
            $numNf = intval($numAtArray[0]['ULTIMAMDF']);
            do {
                $numNf = $numNf + 1;
                $result = $this->existeNotaFiscalEmp($modelo, $serie, $numNf, $cc, $conn);
            } while ($result == TRUE);
            return $numNf;
        else:
            return $banco->resultado;
        endif;
}


//============================================================

    public function existeNotaFiscalEmp($modelo, $serie, $num, $cc, $arr = false, $conn=null) {

        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        $sql .= "WHERE (CENTROCUSTO=".$cc.") and (TIPO=1) AND (MODELO=".$modelo.") AND (SERIE=".$serie.") AND (numero=".$num.")";
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

//============================================================

    /**
     * Funcao para alterar dados autorização NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name alteraNfPath
     * @return NULL quando ok ou msg erro
     */
    public function incluiMdfEvento($idMDF ,$verAplic, $cStat,$tipo, $sequencia=null, $numIni, $numFim, $justificativa, $conn=null) {

        $this->setManifestoFiscal($idMDF);

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
        $sql .= "USERINSERT, ";
        $sql .= "DATEINSERT)  value ( '";
        
        $sql .= $idMDF."', '";
        $sql .= $this->getCentroCusto()."', '";
        $sql .= $tipo."', ";
        if($sequencia == null){
            $sql .= "null, '";
        }else{
            $sql .= "'".$sequencia."', '";
        }
        $sql .= $this->getModelo()."', '";
        $sql .= $this->getSerie()."', '";
        $sql .= $numIni."', '";
        $sql .= $numFim."', '";
        $sql .= $justificativa. "', ";
        $sql .= "null, '";
        $sql .= $verAplic."', '";
        $sql .= $cStat."', ";
        $sql .= $this->m_userid.",'".date("Y-m-d H:i:s"). "' );";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        //echo strtoupper($sql);
        $banco->close_connection();
        return $banco->resultado;

    }
    //============================================================ // fim incluiMdfEvento

    /**
     * Funcao para alterar dados autorização MDFe
     * @param INT ID Chave primaria da table est_manifesto_fiscal
     * @name alteraNfPath
     * @return NULL quando ok ou msg erro
     */
    public function alteraMDFPath($conn=null) {

        $sql = "UPDATE est_manifesto_fiscal SET ";
        $sql .= "pathdamdfe = '" . $this->getPathDanfe()."', ";
        $sql .= "chaveAcessoMdfe = '" . $this->getChMdfe()."', ";
        $sql .= "dhRecbto = '" . $this->getDhRecbto()."', ";
        $sql .= "protocoloMdfe = '" . $this->getProtMdfe()."', ";
        $sql .= "digVal = '" . $this->getDigVal()."', ";
        $sql .= "verAplic = '" . $this->getVerAplic()."', ";
        $sql .= "situacao = 'B' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;

        if (!isset($conn)) :
            $conn = $banco->id_connection;
        endif;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }// fim alteraNFPath

    function trataErro($codErro, $erroSefaz, $erroNf){
        $msg = "Nota não AUTORIZADA <br> Código Mensagem: ".$codErro.": ";

        $erroNf = $msg." - ";
        if(is_array($erroSefaz)){ 
            foreach ($erroSefaz as $err){
                $erroNf .= "$err <br>";
            }
        }else{
            $erroNf .= "<br>".$erroSefaz;
        }
    
        throw new Exception( $erroNf );
        // return $erroNf;
        exit;
    }//Fim trataerro

    /**
     * Funcao para alterar Numero da NFe
     * @param INT ID Chave primaria da table est_nota_fiscal
     * @name alteraNfNumero
     * @return NULL quando ok ou msg erro
     */
    public function alteraNfNumero($conn = null){

        $sql = "UPDATE est_manifesto_fiscal SET ";
        $sql .= "num_mdf = " . $this->getNumMdf() . " ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        $banco = new c_banco;

        if (!isset($conn)) :
            $conn = $banco->id_connection;
        endif;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }// fim alteraNFNumero

    /**
     * Funcao para incluir o evento de encerramento do manifesto fiscal
     * @param  Id do manifesto, protocoloEncerramento, conexao commit
     * @name alteraMdfEncerramento
     * @return true ou msg erro
     */
    public function alteraMdfEncerramento($idMDF, $pEncerramento, $conn = null){

        $sql = "UPDATE est_manifesto_fiscal SET ";
        $sql .= "situacao = 'E', ";
        $sql .= "protocoloencerramento = '" . $pEncerramento . "', ";
        $sql .= "usrsituacao = '" . $this->m_userid . "'  ";
        $sql .= "WHERE id = " . $idMDF . ";";
        $banco = new c_banco;

        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    } // fim alteraMdfEncerramento

    /**
     * Funcao para incluir o evento de cancelamento do manifesto fiscal
     * @param  Id do manifesto, protocoloCancelamento, justificativaCancelamento
     * @name alteraMdfEncerramento
     * @return true ou msg erro
     */
    public function alteraMdfCancelamento($idMDF, $protCancelamento, $justCancelamento, $conn = null){

        $sql = "UPDATE est_manifesto_fiscal SET ";
        $sql .= "situacao = 'C', ";
        $sql .= "protocoloCancelamento = '" . $protCancelamento . "', ";
        $sql .= "justificativaCancelamento = '" . $justCancelamento . "', ";
        $sql .= "usrsituacao = '" . $this->m_userid . "'  ";
        $sql .= "WHERE id = " . $idMDF . ";";
        $banco = new c_banco;

        if (!isset($conn)):
            $conn = $banco->id_connection;
        endif;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao para excluir o manifesto fiscal
     * @param  Id do manifesto
     * @name excluiNotaFiscal
     * @return true ou msg erro
     */
    public function excluiManifestoFiscal($id=null, $conn=null) {

        $sql = "DELETE FROM est_manifesto_fiscal ";
        $sql .= "WHERE id = " . $id;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao para selecionar o nome do condutor
     * @param  Id do condutor
     * @name selectPessoa
     * @return array ou msg erro
     */
    public function selectPessoa(){
        $sql  = "SELECT DISTINCT nome ";
        $sql .= "FROM fin_cliente ";
        $sql .= "WHERE (CLIENTE = ".$this->getCondutor().") ";
        //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa

    /**
     * Funcao para gravar dados de retorno do envio de lote
     * @name gravaDadosEnviaLote
     * @return NULL true/error
     */
    public function gravaDadosEnviaLote($idMDF, $nRecibo) {

        $sql = "UPDATE est_manifesto_fiscal SET ";
        $sql .= "recibomdfe = ". $nRecibo . " ";
        $sql .= "WHERE id = " . $idMDF . ";";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    // fim alteraSituacao

}


