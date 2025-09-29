<?php

/**
 * @package   adm
 * @name      p_nfe_40
 * @version   4.0.00
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      19/07/2018
 * @Revision  27/05/2019
 */
$dir = (__DIR__);

//error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once $dir . '/../../../sped/vendor/autoload.php';


include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_mail.php");
include_once($dir . "/../../bib/c_database_pdo.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_nat_operacao.php");
require_once($dir . "/../../class/est/c_nat_tributos.php");

class p_nfe_40 extends c_user
{

    private $m_submenu = NULL;
    public  $arrayErro = array();

    public function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $this->m_submenu = $parmPost['submenu'];
        $this->arrayErro = array();

        // Cria uma instancia variaveis de sessao
        // session_start();
        $this->from_array($_SESSION['user_array']);

        //$this->nfePath = ADMnfe.$this->m_empresaid.$slash.ADMambDesc;
        $slash = '/';
        define('BASE_DIR_NFE_CFG', ADMnfe . $slash . $this->m_empresaid . $slash . 'config');
        define('BASE_DIR_NFE_AMB', ADMnfe . $slash . $this->m_empresaid . $slash . ADMambDesc);
        define('BASE_HTTP_NFE_AMB', ADMhttpCliente . $slash . 'nfe' . $slash . $this->m_empresaid . $slash . ADMambDesc . $slash);
        define('BASE_DIR_CERT', ADMnfe . $slash . $this->m_empresaid . $slash . 'certs' . $slash);
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
     * Funcao para formatar a data que vai estar na NFe
     * @param TIMESTAMP $data
     * @return data no formato para NFe - 2016-03-03T09:16:00-03:00, PHP.INI = date.timezone = 'UTC''
     */
    public function MostraData($data, $tipo = null)
    {
        $aux = explode(" ", $data);
        if ($tipo == 'D'):
            return $aux[0];
        else:
            return $aux[0] . "T" . $aux[1] . "-02:00"; // horario de verão 
        //return $aux[0]."T".$aux[1]."-03:00";
        endif;
    }

    /**
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeAcentos($string, $slug = false)
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
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeChar($string, $slug = false)
    {
        $conversao = array('.' => '', '/' => '', '-' => '');
        return strtr($string, $conversao);
    }

    /**
     * Funcao para CANCELAR uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function cancela_NFE($chave, $nProt, $xJust, $modelo)
    {
        //$nfeTools = new ToolsNFe(ADMraizCliente . '/nfe/config/config_'.$this->m_empresaid.'.json');

        $anomes = date('Ym');
        $cancelExt = '-CancNFe-procEvento.xml';
        $path = BASE_DIR_NFE_AMB;
        $slash = '/';
        define('BASE_DIR_CANCELADAS', $path . $slash . 'canceladas' . $slash . $anomes . $slash . $chave . $cancelExt);
        define('TESTE_NFE', [DB_HOST_NAME => 'localhost']);

        // configura JSON com dados acesso - CONFIG NF-e 
        if ($this->m_empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($this->m_empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($this->m_empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        $config = [
            "atualizacao" => $confPar[0],
            "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $confPar[2],
            "siglaUF" => $confPar[3],
            "cnpj" => $confPar[4],
            "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
            "versao" => $confPar[6],
            "tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);

        // leitura do certirficado digital
        if ($this->m_empresaid == 1) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
        } else
        if ($this->m_empresaid == 2) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
        } else
        if ($this->m_empresaid == 3) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
        }
        try {
            if ($this->m_empresaid == 1) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
            } else
            if ($this->m_empresaid == 2) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }
            $tools->model($modelo);

            $response = $tools->sefazCancela($chave, $xJust, $nProt);

            //você pode padronizar os dados de retorno atraves da classe abaixo
            //de forma a facilitar a extração dos dados do XML
            //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
            //      quando houver a necessidade de protocolos
            $stdCl = new NFePHP\NFe\Common\Standardize($response);
            //nesse caso $std irá conter uma representação em stdClass do XML retornado
            $std = $stdCl->toStd();
            //nesse caso o $arr irá conter uma representação em array do XML retornado
            $arr = $stdCl->toArray();
            //nesse caso o $json irá conter uma representação em JSON do XML retornado
            $json = $stdCl->toJson();

            //verifique se o evento foi processado
            if ($std->cStat == 128) {
                $cStat = $std->retEvento->infEvento->cStat;
                if ($cStat == '101' || $cStat == '135' || $cStat == '155') {
                    //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
                    $xml = NFePHP\NFe\Complements::toAuthorize($tools->lastRequest, $response);
                    //grave o XML protocolado e prossiga com outras tarefas de seu aplicativo
                    if (!file_exists($path . $slash . 'canceladas' . $slash . $anomes . $slash)) {
                        mkdir($path . $slash . 'canceladas' . $slash . $anomes . $slash, 0777, true);
                    }
                    file_put_contents(BASE_DIR_CANCELADAS, $xml);
                }
            }

            return $std;
        } catch (Exception $e) {
            return "Cancelamento NF NÃO realizado <br>" . $e . getMessage;
            //throw new Exception($e->getMessage() );
        }
    }

    /**
     * Funcao para enviar CARTA DE CORREÇÃO uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function carta_correcao_NFE($chave, $nProt, $xCorrecao, $modelo, $nSeqEvento = 1)
    {
        //        use NFePHP\NFe\Tools;
        //        use NFePHP\Common\Certificate;
        //        use NFePHP\NFe\Common\Standardize;
        //        use NFePHP\NFe\Complements;


        $anomes = date('Ym');
        $cancelExt = '-CCe-' . $nSeqEvento . '-procEvento.xml';
        $path = BASE_DIR_NFE_AMB;
        $slash = '/';
        $diretorioCarta = $path . $slash . 'cartacorrecao' . $slash . $anomes . $slash;
        $fileCarta = $chave . $cancelExt;

        if (!file_exists($diretorioCarta)) {
            mkdir($diretorioCarta, 0777, true);
        }
        define('BASE_DIR_CCE', $diretorioCarta . $fileCarta);
        define('TESTE_NFE', [DB_HOST_NAME => 'localhost']);

        // configura JSON com dados acesso - CONFIG NF-e
        if ($this->m_empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($this->m_empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($this->m_empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        $config = [
            "atualizacao" => $confPar[0],
            "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $confPar[2],
            "siglaUF" => $confPar[3],
            "cnpj" => $confPar[4],
            "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
            "versao" => $confPar[6],
            "tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);

        // leitura do certirficado digital
        if ($this->m_empresaid == 1) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
        } else
        if ($this->m_empresaid == 2) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
        } else
        if ($this->m_empresaid == 3) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
        }
        try {
            if ($this->m_empresaid == 1) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
            } else
            if ($this->m_empresaid == 2) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }
            $tools->model($modelo);

            $response = $tools->sefazCCe($chave, $xCorrecao, $nSeqEvento);

            //você pode padronizar os dados de retorno atraves da classe abaixo
            //de forma a facilitar a extração dos dados do XML
            //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
            //      quando houver a necessidade de protocolos
            $stdCl = new NFePHP\NFe\Common\Standardize($response);
            //nesse caso $std irá conter uma representação em stdClass do XML
            $std = $stdCl->toStd();
            //nesse caso o $arr irá conter uma representação em array do XML
            $arr = $stdCl->toArray();
            //nesse caso o $json irá conter uma representação em JSON do XML
            $json = $stdCl->toJson();

            //verifique se o evento foi processado
            if ($std->cStat != 128) {
                //houve alguma falha e o evento não foi processado
                //TRATAR
            } else {
                $cStat = $std->retEvento->infEvento->cStat;
                if ($cStat == '135' || $cStat == '136') {
                    //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
                    $xml = NFePHP\NFe\Complements::toAuthorize($tools->lastRequest, $response);
                    file_put_contents(BASE_DIR_CCE, $xml);
                    //grave o XML protocolado 
                }
            }
        } catch (\Exception $e) {
            return "Carta correção NF NÃO realizado <br>" . $e . getMessage;
        }



        /* *       try {
            //$nfeTools = new ToolsNFe(ADMraizCliente . '/nfe/config/config_'.$this->m_empresaid.'.json');
            $nfeTools = new NFePHP\NFe\Tools(BASE_DIR_NFE_CFG.'/config.json');
            $nfeTools->setModelo($modelo);
            
            // cancela nfe
            
            $aResposta = array();
            $tpAmb = ADMnfeAmbiente;
            

            $retorno = $nfeTools->sefazCCe($chave, $tpAmb, $xCorrecao, $nSeqEvento, $aResposta);

            return $aResposta;

        } catch (Exception $e) {
            return "carta Correção NF NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
  */
    }

    /**
     * Funcao para enviar VISUALIZAR CARTA DE CORREÇÃO uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function visualizar_carta_correcao_NFE($chave, $nProt, $nSeqEvento = 1, $anomes, $aEnd, &$arq = '')
    {
        try {
            // impressão carta correção
            $nfProc = '-CCe-' . $nSeqEvento . '-procEvento.xml';
            $nfExtPdf = '-CCe-' . $nSeqEvento . '.pdf';

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $http = BASE_HTTP_NFE_AMB;
            $slash = '/';
            (stristr($path, $slash)) ? '' : $slash = '\\';
            define('BASE_DIR_ENVIADA_CARTA_CORRECAO', $path . $slash . 'cartacorrecao' . $slash . $anomes . $slash . $chave . $nfProc);
            define('BASE_DIR_PDF', $path . $slash . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);
            define('BASE_HTTP_PDF', $http . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);
            define('BASE_VERIFICA', $path . $slash . 'pdf' . $slash . $anomes . $slash);

            $arq = BASE_HTTP_PDF;

            if (!file_exists(BASE_VERIFICA)) {
                mkdir(BASE_VERIFICA, 0777, true);
            }

            $pathLogo = ADMimg . '/logo0' . $this->m_empresaid . '.jpg';
            $docxml = NFePHP\DA\Legacy\FilesFolders::readFile(BASE_DIR_ENVIADA_CARTA_CORRECAO);

            $dacce = new NFePHP\DA\NFe\Dacce($docxml, 'P', 'A4', $pathLogo, 'I', $aEnd);
            $teste = $dacce->printDACCE(BASE_DIR_PDF, 'F');
        } catch (Exception $e) {
            return "carta Correção NF NÃO realizado <br>" . $e . message;
            //throw new Exception($e->getMessage() );
        }
    }

    /**
     * Funcao para INUTILIZAR numeração uma NFe
     * @param VARCHAR $chave nfe
     */
    public function inutiliza_NFE($modelo, $nSerie, $nIni, $nFim, $xJust)
    {

        // configura JSON com dados acesso - CONFIG NF-e
        if ($this->m_empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($this->m_empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($this->m_empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        $config = [
            "atualizacao" => $confPar[0],
            "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $confPar[2],
            "siglaUF" => $confPar[3],
            "cnpj" => $confPar[4],
            "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
            "versao" => $confPar[6],
            "tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);

        try {
            //   leitura do certirficado digital
            if ($this->m_empresaid == 1) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
            } else
            if ($this->m_empresaid == 2) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }

            $nSerie = $nSerie;
            $nIni = $nIni;
            $nFin = $nFim;
            $xJust = $xJust;
            $tools->model($modelo);
            $response = $tools->sefazInutiliza($nSerie, $nIni, $nFin, $xJust);


            //você pode padronizar os dados de retorno atraves da classe abaixo
            //de forma a facilitar a extração dos dados do XML
            //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
            //      quando houver a necessidade de protocolos
            $stdCl = new NFePHP\NFe\Common\Standardize($response);
            //nesse caso $std irá conter uma representação em stdClass do XML
            $std = $stdCl->toStd();
            //nesse caso o $arr irá conter uma representação em array do XML
            $arr = $stdCl->toArray();
            //nesse caso o $json irá conter uma representação em JSON do XML
            $json = $stdCl->toJson();

            // grava inutilizadas
            //verifique se o evento foi processado
            $cStat = $std->infInut->cStat;
            if ($cStat == 102) {
                $anomes = date('Ym');
                $inutExt = '-procInutNFe.xml';
                $path = BASE_DIR_NFE_AMB;
                $slash = '/';
                $chave = $anomes . '-' . $modelo . '-' . $nIni . '_' . $nFim;
                define('BASE_DIR_INUTILIZADAS', $path . $slash . 'inutilizadas' . $slash . $anomes . $slash . $chave . $inutExt);
                $caminho = $path . $slash . 'inutilizadas' . $slash . $anomes;
                if (!file_exists($caminho)) {
                    mkdir($caminho, 0777, true);
                }
                file_put_contents(BASE_DIR_INUTILIZADAS, $response);
            }

            // retorna status
            return $std;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    /* *
     * Funcao para CANCELAR uma NFe assinada
     * @param VARCHAR $chave nfe
     
    public function inutiliza_NFE($modelo, $nSerie, $nIni, $nFim, $xJust) {
        try {
            
            $nfeTools = new NFePHP\NFe\Tools(BASE_DIR_NFE_CFG.'/config.json');
            $nfeTools->setModelo($modelo);
            
            // cancela nfe
            $aResposta = array();
            $tpAmb = ADMnfeAmbiente;

            $xml = $nfeTools->sefazInutiliza($nSerie, $nIni, $nFim, $xJust, $tpAmb, $aResposta);
            
            return $aResposta;
        } catch (Exception $e) {
            return "Inutilização NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para contruir a DANFE PDF a partir dos xml assinada e protocolo
     * @param VARCHAR $chave nfe
     */
    public function gera_DANFE($chave)
    {
        try {
            $anomes = date('Ym');
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfe.pdf';

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $slash = '/';
            (stristr($path, $slash)) ? '' : $slash = '\\';
            define('BASE_DIR_ENVIADA_APROVADAS', $path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash . $chave . $nfExt);
            define('BASE_DIR_PDF', $path . $slash . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);

            $pathLogo = ADMimg . '/logo0' . $this->m_empresaid . '.jpg';
            $docxml = NFePHP\DA\Legacy\FilesFolders::readFile(BASE_DIR_ENVIADA_APROVADAS);
            $danfe = new NFePHP\DA\NFe\Danfe($docxml, 'P', 'A4', $pathLogo, 'I', '');
            $id = $danfe->montaDANFE();
            $salva = $danfe->printDocument(BASE_DIR_PDF, 'F'); //Salva o PDF na pasta

            return "Danfe gerada NFe número - ";
        } catch (Exception $e) {
            return "Danfe NÃO gerada NFe número - ";
            //throw new Exception($e->getMessage() );
        }
    }

    /**
     * Funcao para CONSULTAR O STATUS DO SERVIÇO
     */
    public function consultaStatus()
    {

        // configura JSON com dados acesso - CONFIG NF-e
        if ($this->m_empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($this->m_empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($this->m_empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        if ($this->m_empresaid == 4) {
            $confPar = explode("|", ADMnfeConfig04);
        }
        if ($this->m_empresaid == 5) {
            $confPar = explode("|", ADMnfeConfig05);
        }
        $config = [
            "atualizacao" => $confPar[0],
            "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $confPar[2],
            "siglaUF" => $confPar[3],
            "cnpj" => $confPar[4],
            "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
            "versao" => $confPar[6],
            "tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);

        try {
            //   leitura do certirficado digital
            if ($this->m_empresaid == 1) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
            } else
            if ($this->m_empresaid == 2) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }
            if ($this->m_empresaid == 4) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert04);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha04));
            }
            if ($this->m_empresaid == 5) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert05);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha05));
            }

            $tools->model(55);
            //Se não for passada a sigla do estado, o status será obtido com o modo de
            //contingência, se este estiver ativo ou seja SVCRS ou SVCAN, usando a sigla 
            //contida no config.json
            $response = $tools->sefazStatus('PR');
            header('Content-type: text/xml; charset=UTF-8');
            echo $response;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }



    /**
     * Funcao para CONSULTAR O STATUS DO SERVIÇO
     * @param VARCHAR $chave nfe
     */
    public function consultaDistNfe($ultimaNsu)
    {

        /*
            CREATE TABLE IF NOT EXISTS `EST_MANIFESTO` (
            `ID` int(11) PRIMARY KEY AUTO_INCREMENT,
            `DATAHORARESPOSTA` timestamp NULL DEFAULT NULL,
            `ULTNSU` varchar(15) DEFAULT NULL,
            `MAXNSU` varchar(15) DEFAULT NULL,
            `VERAPLIC` varchar(20) DEFAULT NULL,
            `VERSAO` varchar(4) DEFAULT NULL,
            `CSTAT` varchar(3) DEFAULT NULL,
            `XMOTIVO` varchar(255) DEFAULT NULL,
            `INDCONT` char(1) DEFAULT NULL,
            `USREMISSAO` smallint(6) DEFAULT NULL,
            `PROXIMACONSULTA` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        */

        // configura JSON com dados acesso - CONFIG NF-e
        if ($this->m_empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($this->m_empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($this->m_empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        if ($this->m_empresaid == 4) {
            $confPar = explode("|", ADMnfeConfig04);
        }
        if ($this->m_empresaid == 5) {
            $confPar = explode("|", ADMnfeConfig05);
        }
        $config = [
            "atualizacao" => $confPar[0],
            "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $confPar[2],
            "siglaUF" => $confPar[3],
            "cnpj" => $confPar[4],
            "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
            "versao" => $confPar[6],
            "tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);

        try {
            //   leitura do certirficado digital
            if ($this->m_empresaid == 1) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
                //hiperfarma
                //$certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert);
                //$tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha));
            } else
            if ($this->m_empresaid == 2) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }
            if ($this->m_empresaid == 4) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert04);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha04));
            }
            if ($this->m_empresaid == 5) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert05);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha05));
            }


            //só funciona para o modelo 55
            $tools->model('55');
            //este serviço somente opera em ambiente de produção
            $tools->setEnvironment(1);

            //este numero deverá vir do banco de dados nas proximas buscas para reduzir 
            //a quantidade de documentos, e para não baixar várias vezes as mesmas coisas.

            if ($ultimaNsu == null or $ultimaNsu == '') {
                $nova_nsu =  '000000000000000';
            } else {
                $nova_nsu = str_pad(strval(intval($ultimaNsu) + 1), strlen($ultimaNsu), "0", STR_PAD_LEFT);
            }

            $ultNSU = $nova_nsu;
            //$ultNSU = '000000000000000';

            $maxNSU = $ultNSU;
            $loopLimit = 12; //mantenha o numero de consultas abaixo de 20, cada consulta retorna até 50 documentos por vez
            $iCount = 0;

            //variaveis para inserir quando da erro na insercao no banco e grva em eventos
            $failNf = array();
            $incrementa = 0;

            //executa a busca de DFe em loop
            while ($ultNSU <= $maxNSU) {
                $iCount++;
                if ($iCount >= $loopLimit) {
                    //o limite de loops foi atingido pare de consultar
                    break;
                }
                try {
                    //executa a busca pelos documentos
                    $resp = $tools->sefazDistDFe($ultNSU);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    //pare de consultar e resolva o erro (pode ser que a SEFAZ esteja fora do ar)
                    break;
                }

                //extrair e salvar os retornos
                $dom = new \DOMDocument();
                $dom->loadXML($resp);
                $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);
                $tpAmb = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
                $verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
                $cStat = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
                $xMotivo = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
                $dhResp = $node->getElementsByTagName('dhResp')->item(0)->nodeValue;
                $ultNSU = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
                $maxNSU = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;
                $lote = $node->getElementsByTagName('loteDistDFeInt')->item(0);

                //inclui
                $resultIncluiHeader = $this->recordHeader($verAplic, $cStat, $xMotivo, $dhResp, $ultNSU, $maxNSU);

                if (in_array($cStat, ['137', '656', '589'])) {
                    // 137 - Nenhum documento localizado, a SEFAZ está te informando para consultar novamente após uma hora a contar desse momento
                    // 656 - Consumo Indevido, a SEFAZ bloqueou o seu acesso por uma hora pois as regras de consultas não foram observadas
                    // Nestes dois casos, pare as consultas imediatamente e retome apenas daqui a uma hora, pelo menos!!

                    switch ($cStat) {
                        case '137':
                            $message = "Nenhum documento localizado, a SEFAZ está te informando para consultar novamente após uma hora a contar desse momento!";
                            break;
                        case '589':
                            $message = "Rejeicao: Numero do NSU informado superior ao maior NSU da base de dados do Ambiente Nacional!";
                            break;
                        case '656':
                            $message = "Consumo Indevido, a SEFAZ bloqueou o seu acesso por uma hora pois as regras de consultas não foram observadas!";
                            break;
                    }

                    $return = array(
                        "cStat" => $cStat,
                        "message" => $message
                    );

                    return $return;
                }

                if (empty($lote)) {
                    //lote vazio
                    continue;
                }

                //essas tags irão conter os documentos zipados
                $docs = $lote->getElementsByTagName('docZip');

                //salva zip
                //$savedZip = $this->savedZip($docs, $dhResp);

                foreach ($docs as $doc) {
                    $numnsu = $doc->getAttribute('NSU');
                    $schema = $doc->getAttribute('schema');
                    //descompacta o documento e recupera o XML original
                    $xml_string = gzdecode(base64_decode($doc->nodeValue));
                    //identifica o tipo de documento
                    $tipo = substr($schema, 0, 6);
                    //processar o conteudo do NSU, da forma que melhor lhe interessar

                    $xml = new SimpleXMLElement($xml_string);

                    if ($tipo == 'procEv') { // PROCESSO FUTURO
                        /*
                        Em resumo, o arquivo "procEventoNFe_v1.00.xsd" é utilizado para validar e formatar os eventos relacionados à NFe 4.0, 
                        como parte do processo de emissão, cancelamento e controle das notas fiscais eletrônicas.


                        XML EXEMPLO
                        "<procEventoNFe xmlns="http://www.portalfiscal.inf.br/nfe" versao="1.00"><evento xmlns="http://www.portalfiscal.inf.br/nfe" versao="1.00">
                        <infEvento Id="ID2102104123051978559700015555001000003500186266649301"><cOrgao>91</cOrgao><tpAmb>1</tpAmb><CNPJ>80308166000109</CNPJ>
                        <chNFe>41230519785597000155550010000035001862666493</chNFe><dhEvento>2023-05-18T20:08:59-03:00</dhEvento><tpEvento>210210</tpEvento>
                        <nSeqEvento>1</nSeqEvento><verEvento>1.00</verEvento><detEvento versao="1.00"><descEvento>Ciencia da Operacao</descEvento></detEvento></infEvento>
                        <Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
                        <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#ID2102104123051978559700015555001000003500186266649301">
                        <Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
                        <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" /></Transforms>"
                        */
                    } else if ($tipo == 'resEve') { // PROCESSO FUTURO
                        /*
                        O Status Evento de Registro de Passagem Automático MDF-e com CT-e significa que foi emitido pela Sefaz (Fisco), 
                        um Evento de passagem de um MDF-e que consta CT-e em suas informações. Esse Evento é gerado quando o transporte/mercadoria 
                        passa por uma barreira fiscal.
                        */
                    } else if ($tipo == 'resNFe') { // PROCESSO NORMAL

                        $modelo = substr($xml->chNFe, 20, 2);
                        $serie = intval(substr($xml->chNFe, 22, 3));
                        $numeroNF = intval(substr($xml->chNFe, 25, 9));

                        //busca e seta cliente
                        $selectConta = c_conta::existeContaCnpjCpf($xml->CNPJ);

                        //verifica se já existe nota, se existir saiu do if
                        $existNfe = c_nota_fiscal::existNotaNumClient($numeroNF, $serie, $selectConta[0]['CLIENTE']);

                        if (!$existNfe) {

                            if ($selectConta == null and $selectConta == '') {
                                $insertConta = $this->insertPersonManifest($xml, $tipo);

                                if (is_int($insertConta)) {
                                    $selectConta[0]['CLIENTE'] = $insertConta;
                                } else {
                                    $selectConta[0]['CLIENTE'] = 99999; //cliente padrao
                                }
                            }

                            $objetoNotaFiscal = new c_nota_fiscal();
                            $objetoNotaFiscal->setModelo($modelo);
                            $objetoNotaFiscal->setSerie($serie);
                            $objetoNotaFiscal->setNumero($numeroNF);
                            $objetoNotaFiscal->setPessoa($selectConta[0]['CLIENTE']);
                            $objetoNotaFiscal->setCpfNota($xml->CNPJ);
                            $objetoNotaFiscal->setEmissao($xml->dhEmi);
                            $objetoNotaFiscal->setIdNatop(3); // 3 - COMPRAS DIVERSAS
                            $objetoNotaFiscal->setNatOperacao('');
                            $objetoNotaFiscal->setTipo(0); // 0 - ENTRADA | 1 - SAÍDA
                            $objetoNotaFiscal->setSituacao('NP'); // NP - NAO PROCESSADA
                            $objetoNotaFiscal->setFormaPgto(2); // 2 - OUTROS 
                            $objetoNotaFiscal->setCondPgto('');
                            $objetoNotaFiscal->setDataSaidaEntrada($xml->dhEmi);
                            $objetoNotaFiscal->setFormaEmissao('');
                            $objetoNotaFiscal->setFinalidadeEmissao('');
                            $objetoNotaFiscal->setNfeReferenciada('');
                            $objetoNotaFiscal->setCentroCusto($this->m_empresacentrocusto);
                            $objetoNotaFiscal->setGenero('');
                            $objetoNotaFiscal->setModFrete('');
                            $objetoNotaFiscal->setTransportador(null);
                            $objetoNotaFiscal->setPlacaVeiculo('');
                            $objetoNotaFiscal->setVolume('');
                            $objetoNotaFiscal->setVolEspecie('');
                            $objetoNotaFiscal->setVolMarca('');
                            $objetoNotaFiscal->setVolPesoLiq('');
                            $objetoNotaFiscal->setVolPesoBruto('');

                            $totalnf  = number_format((float) $xml->vNF, 2, ',', '.');
                            $objetoNotaFiscal->setTotalnf($totalnf);

                            $objetoNotaFiscal->setOrigem('');
                            $objetoNotaFiscal->setDoc($numeroNF);
                            $objetoNotaFiscal->setObs($xml->xNome);
                            $objetoNotaFiscal->setFrete('');
                            $objetoNotaFiscal->setDespAcessorias('');
                            $objetoNotaFiscal->setSeguro('');
                            $objetoNotaFiscal->setDhRecbto('');
                            $objetoNotaFiscal->setNProt($xml->nProt);
                            $objetoNotaFiscal->setDigVal($xml->digVal);
                            $objetoNotaFiscal->setVerAplic('');
                            $objetoNotaFiscal->setVendaPresencial('');
                            $objetoNotaFiscal->setContrato('');
                            $objetoNotaFiscal->setChNFe($xml->chNFe);

                            $resultInsert = $objetoNotaFiscal->incluiNotaFiscalManisfesto();

                            if (!is_int($resultInsert)) {
                                $failNf[$incrementa] = $xml->chNFe;
                                $incrementa++;
                            };
                        } //END !$existNfe


                    } else if ($tipo == 'procNF') {
                        //busca e seta cliente
                        $selectConta = c_conta::existeContaCnpjCpf($xml->NFe->infNFe->emit->CNPJ);

                        //verifica se já existe nota, se existir saiu do if
                        $existNfe = c_nota_fiscal::existNotaNumClient($$xml->NFe->infNFe->ide->nNF, intval($xml->NFe->infNFe->ide->serie), $selectConta[0]['CLIENTE']);

                        if (!$existNfe) {

                            if ($selectConta == null and $selectConta == '') {
                                $insertConta = $this->insertPersonManifest($xml, $tipo);

                                if (is_int($insertConta)) {
                                    $selectConta[0]['CLIENTE'] = $insertConta;
                                } else {
                                    $selectConta[0]['CLIENTE'] = 99999;
                                }
                            }

                            $objetoNotaFiscal = new c_nota_fiscal();
                            $objetoNotaFiscal->setModelo($xml->NFe->infNFe->ide->mod);
                            $objetoNotaFiscal->setSerie(intval($xml->NFe->infNFe->ide->serie));
                            $objetoNotaFiscal->setNumero($xml->NFe->infNFe->ide->nNF);
                            $objetoNotaFiscal->setPessoa($selectConta[0]['CLIENTE']);
                            $objetoNotaFiscal->setCpfNota($xml->NFe->infNFe->emit->CNPJ);
                            $objetoNotaFiscal->setEmissao($xml->NFe->infNFe->ide->dhEmi);
                            $objetoNotaFiscal->setIdNatop(3); // 3 - COMPRAS DIVERSAS
                            $objetoNotaFiscal->setNatOperacao(''); // FALTA DEFINIR O PADRAO
                            $objetoNotaFiscal->setTipo(0); // 0 - ENTRADA | 1 - SAÍDA
                            $objetoNotaFiscal->setSituacao('NP'); // NP - NAO PROCESSADA
                            $objetoNotaFiscal->setFormaPgto(2); // 2 - OUTROS 
                            $objetoNotaFiscal->setCondPgto('');
                            $objetoNotaFiscal->setDataSaidaEntrada($xml->NFe->infNFe->ide->dhEmi);
                            $objetoNotaFiscal->setFormaEmissao('');
                            $objetoNotaFiscal->setFinalidadeEmissao('');
                            $objetoNotaFiscal->setNfeReferenciada('');
                            $objetoNotaFiscal->setCentroCusto($this->m_empresacentrocusto);
                            $objetoNotaFiscal->setGenero('');
                            $objetoNotaFiscal->setModFrete($xml->NFe->infNFe->transp->modFrete);
                            $objetoNotaFiscal->setTransportador(null);
                            $objetoNotaFiscal->setPlacaVeiculo('');
                            $objetoNotaFiscal->setVolume('');
                            $objetoNotaFiscal->setVolEspecie('');
                            $objetoNotaFiscal->setVolMarca('');
                            $objetoNotaFiscal->setVolPesoLiq('');
                            $objetoNotaFiscal->setVolPesoBruto('');

                            $totalnf  = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vNF, 2, ',', '.');
                            $objetoNotaFiscal->setTotalnf($totalnf);

                            $objetoNotaFiscal->setOrigem('');
                            $objetoNotaFiscal->setDoc($xml->NFe->infNFe->ide->nNF);
                            $objetoNotaFiscal->setObs($xml->NFe->infNFe->emit->xNome);
                            $objetoNotaFiscal->setFrete($xml->NFe->infNFe->total->ICMSTot->vFrete);
                            $objetoNotaFiscal->setDespAcessorias('');
                            $objetoNotaFiscal->setSeguro($xml->NFe->infNFe->total->ICMSTot->vSeg);
                            $objetoNotaFiscal->setDhRecbto('');
                            $objetoNotaFiscal->setNProt($xml->protNFe->infProt->nProt);
                            $objetoNotaFiscal->setDigVal($xml->protNFe->infProt->digVal);
                            $objetoNotaFiscal->setVerAplic($xml->protNFe->infProt->verAplic);
                            $objetoNotaFiscal->setVendaPresencial('');
                            $objetoNotaFiscal->setContrato('');
                            $objetoNotaFiscal->setChNFe($xml->protNFe->infProt->chNFe);

                            $resultInsert = $objetoNotaFiscal->incluiNotaFiscalManisfesto();

                            if (!is_int($resultInsert)) {
                                $failNf[$incrementa] = $xml->chNFe;
                                $incrementa++;
                            };
                        } //END !$existNfe

                    }
                }
                if ($ultNSU == $maxNSU) {
                    //quando o numero máximo de NSU foi atingido não existem mais dados a buscar
                    //nesse caso a proxima busca deve ser no minimo após mais uma hora
                    break;
                }

                sleep(2);
            }
            //salve o ultNSU pesquisado em sua base pois a proxima consulta deverá iniciar a partir desse numero + 1

            if (empty($failNf)) {
                return 'true';
            } else {
                $jsonNotas = json_encode($failNf);

                $sql = "INSERT INTO est_nota_fiscal_eventos (";
                $sql .= "IDNF, TIPOEVENTO, CENTROCUSTO, MODELO, SERIE, NUMNFINI, NUMNFFIM, JUSTIFICATIVA, NPROT, VERAPLIC, CSTAT, USERINSERT, DATEINSERT) ";

                $sql .= "VALUES (0000,'M' , ";
                $sql .= $this->m_empresacentrocusto . ", '";
                $sql .= "55 ', '";
                $sql .= "000', ";
                $sql .= $ultNSU . ", ";
                $sql .= $maxNSU . ", '";
                $sql .= $jsonNotas . "', '";
                $sql .= "00000000000', '";
                $sql .= $verAplic . "', '";
                $sql .= $cStat . "', ";
                $sql .= $this->m_userid . ",'" . date("Y-m-d H:i:s") . "' );";

                // echo strtoupper($sql)."<BR>";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();

                return array(
                    'cStat'    => 'atencao',
                    'message'  => 'Notas localizadas, mas com divergências ao inserir no sistema, contate o suporte.'
                );
            }
        } catch (\Exception $e) {
            return array(
                'cStat'    => 'error',
                'message'  => $e->getMessage()
            );
        }
    }


    /**
     * Funcao para enviar email e pdf da DANFE PDF a partir dos xml assinada e protocolo
     * @param VARCHAR $chave nfe
     */
    public function enviaEmailDANFE($modelo, $email = null, $cc = null, $chave, $dhEmi, $cNF, $serie, $xNome, $assunto = null, $bodyEmail = null)
    {
        try {

            $email = strtolower($email);
            $cc = strtolower($cc);
            // $dateEmi = explode("-", $dhEmi);
            $ano = date('Y', strtotime($dhEmi));
            $mes = date('m', strtotime($dhEmi));
            $dataEmi = date('d-m-Y', strtotime($dhEmi));
            // $anomes = substr($dateEmi[2],0,4).$dateEmi[1];
            $anomes = $ano . $mes;
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfe.pdf';

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $slash = '/';
            (stristr($path, $slash)) ? '' : $slash = '\\';
            define('BASE_DIR_ENVIADA_APROVADAS', $path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash . $chave . $nfExt);
            define('BASE_DIR_PDF', $path . $slash . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);

            $pathXml = BASE_DIR_ENVIADA_APROVADAS;

            $pathPdf = BASE_DIR_PDF;

            if ((is_null($email) or ($email == '')) and ((is_null($cc))  or ($cc == ''))):
                return 'Email envio não cadastrado';
            else:
                if (is_null($email)  or ($email == '')):
                    $aMails = array($cc); //se for um array vazio a classe Mail irá pegar os emails do xml
                elseif (is_null($cc)  or ($cc == '')):
                    $aMails = array($email); //se for um array vazio a classe Mail irá pegar os emails do xml
                else:
                    $aMails = array($email, $cc); //se for um array vazio a classe Mail irá pegar os emails do xml
                endif;
                $templateFile = ''; //se vazio usará o template padrão da mensagem
                $comPdf = true; //se true, anexa a DANFE no e-mail


                $mail = new admMail;
                if ($assunto == '') {
                    $assuntoEmail =  "Nfe - envio XML/DANFE";
                } else {
                    $assuntoEmail = strtolower($assunto);
                }

                if ($bodyEmail == '') {
                    $body = "
                        Prezados<br> NF-E EMITIDA EM AMBIENTE DE " . ADMambDesc . "<br>";

                    $body .= "
                                Você está recebendo a Nota Fiscal Eletrônica emitida em " . $dataEmi . "com o número " . $cNF . ", série " . $serie . " de " . $xNome . ".<br> Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.

                                <br>Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.

                                <br>Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.

                                <br>Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.

                                <br>O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.

                                <br><br>Para mais detalhes, consulte: www.nfe.fazenda.gov.br

                                <br><br>Atenciosamente";
                } else {
                    $body = nl2br(htmlspecialchars($bodyEmail));
                }


                // $result = $mail->SendMail("mail.admservice.com.br", "nfemaxi@admservice.com.br", "email Nfe", "renemaxi578", 
                $result = $mail->SendMail(
                    $this->m_configsmtp,
                    $this->m_configemail,
                    "email Nfe",
                    $this->m_configemailsenha,
                    $body,
                    $assuntoEmail,
                    $email,
                    "",
                    $cc,
                    "",
                    $pathXml,
                    $pathPdf
                );

                if (strstr($result, 'não')):
                    if (strstr($result, 'access file') && strstr($result, 'xml')) {
                        return "email XML/DANFE NÃO enviado - Arquivo XML não localizado.";
                    } else if (strstr($result, 'pdf')) {
                        return "email XML/DANFE NÃO enviado - Arquivo PDF não localizado.";
                    } else if (strstr($result, 'Invalid address')  && strstr($result, '(From):')) {
                        return "email XML/DANFE NÃO enviado - email Remetente " . $email . " inválido.";
                    } else if (strstr($result, 'not Authenticate')) {
                        return "email XML/DANFE NÃO enviado - Email/Senha invalido.";
                    } else if (strstr($result, 'Invalid address')  && strstr($result, '(to):')) {
                        return "email XML/DANFE NÃO enviado - email Destinatário " . $email . " inválido.";
                    } else {
                        return "email XML/DANFE NÃO enviado - entre em contato com o suporte";
                    }
                //if ($result):

                else:
                    return "email XML/DANFE enviado com sucesso!!!";
                endif;
            endif;
        } catch (Exception $e) {
            return 'Erro -> ' . $e->getMessage();
        }
    }


    /**
     * @name consultaRecibo
     * Funcao para consultar o recibo da NFe
     * @author Jhon K S Meloo
     * @param string/int $numeroRecibo
     * @param string/int $idNf
     * @return array 
     */
    public function consultaRecibo($numeroRecibo, $idNf)
    {


        $codigoErroInicial = ["103", "105", "106", "656"];
        // 103 Lote ainda não foi precessado pela SEFAZ
        // 105 Lote em processamento, tente novamente mais tarde
        // 106 Lote nao localizado
        // 656 Uso indevido da API - (Diversas consultas no mesmo range de hora ou 20 erros iguais dentro da mesma hora)

        try {
            //$content = conteúdo do certificado PFX
            //$tools = new Tools($configJson, Certificate::readPfx($content, 'senha'));

            // configura JSON com dados acesso - CONFIG NF-e
            if ($this->m_empresaid == 1) {
                $confPar = explode("|", ADMnfeConfig01);
            } else
            if ($this->m_empresaid == 2) {
                $confPar = explode("|", ADMnfeConfig02);
            } else
            if ($this->m_empresaid == 3) {
                $confPar = explode("|", ADMnfeConfig03);
            }
            if ($this->m_empresaid == 4) {
                $confPar = explode("|", ADMnfeConfig04);
            }
            if ($this->m_empresaid == 5) {
                $confPar = explode("|", ADMnfeConfig05);
            }

            //set tipo ambiente
            $tpAmb = $confPar[1];

            $config = [
                "atualizacao" => $confPar[0],
                "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
                "razaosocial" => $confPar[2],
                "siglaUF" => $confPar[3],
                "cnpj" => $confPar[4],
                "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
                "versao" => $confPar[6],
                "tokenIBPT" => $confPar[7]
            ];

            $configJson = json_encode($config);

            //   leitura do certirficado digital
            if ($this->m_empresaid == 1) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
                //hiperfarma
                //$certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert);
                //$tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha));
            } else
            if ($this->m_empresaid == 2) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
            } else
            if ($this->m_empresaid == 3) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
            }
            if ($this->m_empresaid == 4) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert04);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha04));
            }
            if ($this->m_empresaid == 5) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert05);
                $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha05));
            }

            //consulta número de recibo
            $xmlResp = $tools->sefazConsultaRecibo($numeroRecibo, $tpAmb);

            //transforma o xml de retorno em um stdClass
            $st = new NFePHP\NFe\Common\Standardize();
            $std = $st->toStd($xmlResp);

            // tests opening
            if (in_array($std->cStat, $codigoErroInicial)) {
                return $this->createProcessingResponse($std, $idNf);
            }

            // Create object
            $objNotaFiscal = new c_nota_fiscal;

            //lote processado (tudo ok)
            if ($std->cStat == '104') {

                //sets parametros script

                $data = new DateTime($std->dhRecbto);
                //$anomes = '202309';
                $anomes = $data->format("Ym");
                $slash = '/';
                $chave = $std->protNFe->infProt->chNFe;
                //$chave = '41230935938009000122550010000015231063783067';
                define('BASE_HTTP_PDF', ADMhttpCliente . $slash . 'nfe' . $slash . $this->m_empresaid . $slash . ADMambDesc . $slash . 'pdf' . $slash . $anomes . $slash . $chave . '-danfe.pdf');
                define('BASE_DIR_PDF', BASE_DIR_NFE_AMB . $slash . 'pdf' . $slash . $anomes . $slash . $chave . '-danfe.pdf');
                define('BASE_DIR_NFE_AMB', ADMnfe . $slash . $this->m_empresaid . $slash . ADMambDesc);

                if ($std->protNFe->infProt->cStat == '100') { //Autorizado o uso da NF-e

                    //insert event
                    $objNotaFiscal->setId($idNf);
                    $objNotaFiscal->setNotaFiscal();
                    $result['ID']          = $objNotaFiscal->getId();
                    $result['CENTROCUSTO'] = $objNotaFiscal->getCentroCusto();
                    $result['MODELO']      = $objNotaFiscal->getModelo();
                    $result['SERIE']       = $objNotaFiscal->getSerie();
                    $result['NUMNFINI']    = $objNotaFiscal->getNumero();
                    $result['NUMNFFIM']    = $objNotaFiscal->getNumero();
                    $result['MOTIVO']      = $std->protNFe->infProt->xMotivo;
                    $result['VERAPLIC']    = $std->protNFe->infProt->verAplic;
                    $result['CSTAT']       = $std->protNFe->infProt->cStat;
                    $result['TIPOEVENTO']  = 'R';
                    $result['SEQUENCIA']   = '1';
                    $result['XML']         = null; // Note issued is already being saved in XML format

                    $objNotaFiscal->incluiNfEvento($result, null, null, null, null, null, 'recibo');

                    //update nf-e
                    $objNotaFiscal->setPathDanfe(BASE_HTTP_PDF);
                    $objNotaFiscal->setSituacao('B');
                    $objNotaFiscal->setChNFe($std->protNFe->infProt->chNFe);
                    $objNotaFiscal->setDhRecbto($std->protNFe->infProt->dhRecbto);
                    $objNotaFiscal->setNProt($std->protNFe->infProt->nProt);
                    $objNotaFiscal->setDigVal($std->protNFe->infProt->digVal);
                    $objNotaFiscal->setVerAplic($std->protNFe->infProt->verAplic);

                    $objNotaFiscal->alteraNfPath();


                    //busca xml
                    $diretorio = ADMhttpCliente . $slash . 'nfe' . $slash . $this->m_empresaid . $slash . ADMambDesc . $slash . 'assinadas' . $slash . '202309' . $slash; //$anomes;
                    $arqXml = $chave . '-nfe.xml';
                    $caminhoCompleto = $diretorio . $arqXml;

                    $xmlAssinado = file_get_contents($caminhoCompleto);

                    if (!$xmlAssinado) {
                        $erro = error_get_last();
                        echo "Ocorreu um erro ao buscar o xml no diretorio <p>Erro: " . $erro['message'];
                    } else {
                        // DANFE GRAVA
                        try {
                            $pathLogo = ADMimg . '/logo0' . $this->m_empresaid . '.jpg';
                            $logo = 'data://text/plain;base64,' . base64_encode(file_get_contents($pathLogo));

                            if (!file_exists(BASE_DIR_NFE_AMB . $slash . 'pdf' . $slash . $anomes . $slash)) {
                                mkdir(BASE_DIR_NFE_AMB . $slash . 'pdf' . $slash . $anomes . $slash, 0777, true);
                            }

                            $danfe = new NFePHP\DA\NFe\Danfe($xmlAssinado, 'P', 'A4', $pathLogo, 'I', '');
                            $danfe->montaDANFE();
                            $danfe->printDocument(BASE_DIR_PDF, 'F'); //Salva o PDF na pasta

                        } catch (InvalidArgumentException $e) {
                            trataErro('PDF', str_replace("\n", "<br/>", $e->getMessage()), '');
                        }
                    }

                    $response = [
                        'status' => 'success',
                        'code' => 100,
                        'message' => 'Nota emitida!',
                        'idNf' => $idNf,
                        'errors' => []
                    ];

                    return $response;
                } elseif (in_array($std->protNFe->infProt->cStat, ["110", "301", "302"])) { //DENEGADAS

                    //insert event
                    $objNotaFiscal->setId($idNf);
                    $objNotaFiscal->setNotaFiscal();
                    $result['ID']          = $objNotaFiscal->getId();
                    $result['CENTROCUSTO'] = $objNotaFiscal->getCentroCusto();
                    $result['MODELO']      = $objNotaFiscal->getModelo();
                    $result['SERIE']       = $objNotaFiscal->getSerie();
                    $result['NUMNFINI']    = $objNotaFiscal->getNumero();
                    $result['NUMNFFIM']    = $objNotaFiscal->getNumero();
                    $result['MOTIVO']      = $std->protNFe->infProt->xMotivo;
                    $result['VERAPLIC']    = $std->protNFe->infProt->verAplic;
                    $result['CSTAT']       = $std->protNFe->infProt->cStat;
                    $result['TIPOEVENTO']  = 'R';
                    $result['SEQUENCIA']   = '1';
                    $result['XML']         = null; // Denied note does not nedd to save xml
                    $objNotaFiscal->incluiNfEvento($result, null, null, null, null, null, 'recibo');

                    //update nf-e
                    $objNotaFiscal->setPathDanfe(BASE_HTTP_PDF);
                    $objNotaFiscal->setSituacao("D");
                    $objNotaFiscal->setChNFe($std->protNFe->infProt->chNFe);
                    $objNotaFiscal->setDhRecbto($std->protNFe->infProt->dhRecbto);
                    $objNotaFiscal->setNProt($std->protNFe->infProt->nProt);
                    $objNotaFiscal->setDigVal($std->protNFe->infProt->digVal);
                    $objNotaFiscal->setVerAplic($std->protNFe->infProt->verAplic);

                    $objNotaFiscal->alteraNfPath();

                    $response = [
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Operação não realizada!',
                        'errors' => [
                            'eDescription' => 'Nota Denegada!',
                            'eReason' => $std->xMotivo,
                            'eFooter' => '<i><b>Aperte F12 para capturar a tela e enviar para o suporte.</b><i/>'
                        ]
                    ];

                    return $response;
                } else if ($std->protNFe->infProt->cStat == "777") {

                    //insert event
                    $objNotaFiscal->setId($idNf);
                    $objNotaFiscal->setNotaFiscal();
                    $result['ID']          = $objNotaFiscal->getId();
                    $result['CENTROCUSTO'] = $objNotaFiscal->getCentroCusto();
                    $result['MODELO']      = $objNotaFiscal->getModelo();
                    $result['SERIE']       = $objNotaFiscal->getSerie();
                    $result['NUMNFINI']    = $objNotaFiscal->getNumero();
                    $result['NUMNFFIM']    = $objNotaFiscal->getNumero();
                    $result['MOTIVO']      = $std->protNFe->infProt->xMotivo;
                    $result['VERAPLIC']    = $std->protNFe->infProt->verAplic;
                    $result['CSTAT']       = $std->protNFe->infProt->cStat;
                    $result['TIPOEVENTO']  = 'R';
                    $result['SEQUENCIA']   = '1';
                    $result['XML']         = $xmlResp;
                    $objNotaFiscal->incluiNfEvento($result, null, null, null, null, null, 'recibo');

                    //update nf-e
                    $objNotaFiscal->alteraSituacao('A');
                } else { //other erros

                    //insert event
                    $objNotaFiscal->setId($idNf);
                    $objNotaFiscal->setNotaFiscal();
                    $result['ID']          = $objNotaFiscal->getId();
                    $result['CENTROCUSTO'] = $objNotaFiscal->getCentroCusto();
                    $result['MODELO']      = $objNotaFiscal->getModelo();
                    $result['SERIE']       = $objNotaFiscal->getSerie();
                    $result['NUMNFINI']    = $objNotaFiscal->getNumero();
                    $result['NUMNFFIM']    = $objNotaFiscal->getNumero();
                    $result['MOTIVO']      = $std->protNFe->infProt->xMotivo;
                    $result['VERAPLIC']    = $std->protNFe->infProt->verAplic;
                    $result['CSTAT']       = $std->protNFe->infProt->cStat;
                    $result['TIPOEVENTO']  = 'R';
                    $result['SEQUENCIA']   = '1';
                    $result['XML']         = $xmlResp;
                    $objNotaFiscal->incluiNfEvento($result, null, null, null, null, null, 'recibo');


                    // COMENTADO POIS A CONSULTA PODE TRAZER OUTROS INFORMACOES, VALIDAR
                    //update nf-e
                    // $objNotaFiscal->setPathDanfe(BASE_HTTP_PDF);
                    // $objNotaFiscal->setChNFe($std->protNFe->infProt->chNFe);
                    // $objNotaFiscal->setDhRecbto($std->protNFe->infProt->dhRecbto);
                    // $objNotaFiscal->setNProt($std->protNFe->infProt->nProt);
                    // $objNotaFiscal->setDigVal($std->protNFe->infProt->digVal);
                    // $objNotaFiscal->setVerAplic($std->protNFe->infProt->verAplic);
                    // $objNotaFiscal->alteraNfPath();

                    $response = [
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Operação não realizada!',
                        'errors' => [
                            'eDescription' => 'Unmapped error in function consultaRecibo()!',
                            'eReason' => $std->xMotivo,
                            'eFooter' => '<i><b>Aperte F12 para capturar a tela e enviar para o suporte.</b><i/>'
                        ]
                    ];

                    return $response;
                }
            } else { //outros erros possíveis


                $objNotaFiscal->setId($idNf);
                $objNotaFiscal->setNotaFiscal();
                $result['ID']          = $objNotaFiscal->getId();
                $result['CENTROCUSTO'] = $objNotaFiscal->getCentroCusto();
                $result['MODELO']      = $objNotaFiscal->getModelo();
                $result['SERIE']       = $objNotaFiscal->getSerie();
                $result['NUMNFINI']    = $objNotaFiscal->getNumero();
                $result['NUMNFFIM']    = $objNotaFiscal->getNumero();
                $result['MOTIVO']      = $std->protNFe->infProt->xMotivo;
                $result['VERAPLIC']    = $std->protNFe->infProt->verAplic;
                $result['CSTAT']       = $std->protNFe->infProt->cStat;
                $result['TIPOEVENTO']  = 'R';
                $result['SEQUENCIA']   = '1';
                $result['XML']         = $xmlResp;

                $objNotaFiscal->incluiNfEvento($result, null, null, null, null, null, 'recibo');

                $response = [
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Operação não realizada!',
                    'errors' => [
                        'eDescription' => 'Unmapped error in function consultaRecibo()!',
                        'eReason' => $std->xMotivo,
                        'eFooter' => '<i><b>Aperte F12 para capturar a tela e enviar para o suporte.</b><i/>'
                    ]
                ];

                return $response;
            }
        } catch (\Exception $e) {

            $response = [
                'status' => 'error',
                'code' => 401,
                'message' => 'Operação não realizada!',
                'errors' => [
                    'eDescription' => 'Unmapped error in function consultaRecibo()!',
                    'eReason' => $e->getMessage(),
                    'eFooter' => ''
                ]
            ];

            return $response;
        }
    }


    /**
     * @name createProcessingResponse
     * Funcao para criar a resposta e enviar email de suporte se necessario
     * @author Jhon K S Meloo
     * @param object $std 
     * @param string/int $idNf
     * @return array 
     */
    function createProcessingResponse($std, $idNf)
    {
        switch ($std->cStat) {
            case "103": //Lote ainda não foi precessado pela SEFAZ

                return [
                    'status' => 'warning',
                    'code' => 103,
                    'message' => 'Lote ainda não foi processado pela SEFAZ',
                    'idNf' => $idNf,
                    'footer' => '<i><b>Lote ainda não foi processado pela SEFAZ</b><i/>'
                ];

                break;
            case "105": //lote em processamento, tente novamente mais tarde

                return [
                    'status' => 'warning',
                    'code' => 105,
                    'message' => 'Lote em processamento, tente novamente mais tarde!',
                    'idNf' => $idNf,
                    'footer' => '<i><b>Lote em processamento, tente novamente mais tarde</b><i/>'
                ];

                break;
            case "106": //lote nao localizado

                $sendLog = new admMail;

                // Mount log and send log for email
                $log = [
                    "typeError" => "CRITICO",
                    "descriptionError" => $std->xMotivo,
                    "codigoError" => 999,
                    "Message" => "",
                    "process" => "p_nfephp_40.php->consultaRecibo()",
                    "modulo" => "form/est",
                    "dateTime" => $std->dhRecbto,
                    "extra" => "ID Nota Fiscal:" . $idNf
                ];

                $sendLog->sendLogEmail($log);

                // Mount response
                return [
                    'status' => 'warning',
                    'code' => 106,
                    'message' => 'Lote nao localizado, verifique o ambiente!',
                    'idNf' => $idNf,
                    'footer' => '<i><b>Lote não localizado, entre em contato com o suporte.</b><i/>'
                ];
                break;

            case "656":

                // Mount response
                return [
                    'status' => 'warning',
                    'code' => 656,
                    'message' => 'Consumo indevido do serviço!',
                    'idNf' => $idNf,
                    'footer' => '<i><b>Aguarde 1 hora para realizar uma nova tentativa.</b><i/>'
                ];
                break;

            case "777":

                // Mount response
                return [
                    'status' => 'warning',
                    'code' => 777,
                    'message' => 'Consumo indevido do serviço!',
                    'idNf' => $idNf,
                    'footer' => '<i><b>Aguarde 1 hora para realizar uma nova tentativa.</b><i/>'
                ];
                break;
        }
    }


    // ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024
    /*
    @name trataErro
    @param int $codErro código do erro
    @param string $erroSefaz mensagem do erro
    @param string $erroNf mensagem erro
    @param int||string $param paramentro que sera incluso no botao
    @return array
    @description faz o tratamento das mensagens de erros montando o botacao para correcao
    

    function trataErro($codErro, $erroSefaz, $erroNf, $parametro=null){
        $returnButton = $this->createButton($codErro, $parametro);
        $msg = $this->quebraLinhaPorChave($erroSefaz);
        $msg = "NOTA NÃO AUTORIZADA - Código: ".$codErro." <br />VERIFIQUE OS CAMPOS:" . "<br \><br \>" . $msg;

        $arrayErro = array(
            'cStatus' => $codErro,
            'codErro' => $codErro,
            'erroSefaz' => $erroSefaz,
            'erroInterno' => $erroNf,
            'msgCompleta' => $msg,
            'button' => $returnButton
        );
        return $arrayErro;
        //throw new Exception( $erroNf );
        //exit;
    }

    /** 
    * @name createButton
    * @description faz o tratamento das mensagens de erros montando o botacao para correcao
    * @param int $codErro código do erro
    * @param int||varchar $param $codErro código do erro
    * @param string $fontSize tamanho da fonte do botao
    * @return array
    
    function createButton($codErro,$param=null,$fontSize='12px'){
        switch($codErro){
            //cases tributos EST
            case 990: //Tributos
                $button = '<button type="button" style="font-size:'.$fontSize.' !important;" class="btn btn-dark" onclick="openNewWin(\''.ADMhttpCliente.
                          '/index.php?mod=est&submenu=alterar&form=nota_fiscal&id='.$param.'\')">Abrir nota fiscal em nova guia?</button>';
                return $button;
            break;
            //cases conta CRM
            case 998: //Código município destinatário não localizado
            case 997: //CNPJ destinatário não localizado!
            case 996: //CEP destinatário não localizado
            case 995: //CNPJ do transportador não localizado
                $button = '<button type="button" style="font-size:'.$fontSize.' !important;" class="btn btn-dark" onclick="openNewWin(\''.ADMhttpCliente.
                          '/index.php?mod=crm&submenu=alterar&form=contas&param='.$param.'\')">Abrir cadastro de pessoa em nova guia?</button>';
                return $button;
            break;
        }
    }

    /**
     * @description Verifica se uma string contém algum dos termos especificados.
     * @param string $text A string a ser verificada.
     * @param array $terms Um array de termos a serem verificados na string.
     * @return bool Retorna true se algum dos termos for encontrado, caso contrário false.
     
    function containsTerms($text, $terms) {
        // Percorre cada termo do array
        foreach ($terms as $term) {
            if (strpos($text, $term) !== false) {
                return true;
            }
        }
        return false;
    }

    function quebraLinhaPorChave($string) {
        // Dividir a string em palavras
        $palavras = explode(' ', $string);
        $resultado = '';

        foreach ($palavras as $palavra) {
            // Verificar se a palavra contém chaves { }
            if (preg_match('/\{http:\/\/www\.portalfiscal\.inf\.br\/nfe\}/', $palavra)) {
                $resultado .= $palavra . '<br \> ';
            }
        }
        return rtrim($resultado);
    }*/
    // FIM -  ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024

    /**
     * Funcao para contruir a nota fiscal XML e gerar o arquivo no diretorio raiz
     * @param INT $idNf Chave primaria na table nota_fiscal
     * @param INT $filial filial logado pelo sistema
     * @param INT $tipoNf tipo da NF 0 - Entrada / 1 - Saida
     * https://blog.tecnospeed.com.br/como-calcular-o-icms-na-nf-e-e-nfc-e/
     */
    public function gera_XML($idNf, $filial, $tipoNf, $conn = null, $gerarXML = null)
    {
        $dir = (__DIR__);

        $nfe = new NFePHP\NFe\Make();


        // variavies totais
        $vBCTotal = 0;
        $vICMSTotal = 0;
        $vICMSDesonTotal = 0;
        $vFCPUFDestTotal = 0;
        $vICMSUFDestTotal = 0;
        $vICMSUFRemetTotal = 0;
        $vBCSTTotal = 0;
        $vSTTotal = 0;
        $vProdTotal = 0;
        $vFreteTotal = 0;
        $vSegTotal = 0;
        $vDescTotal = 0;
        $vIITotal = 0;
        $vIPITotal = 0;
        $vPISTotal = 0;
        $vCOFINSTotal = 0;
        $vOutroTotal = 0;
        $vNFTotal = 0;
        $vTotTribTotal = 0;

        // CONSULTA DE DADOS DA NOTA FISCAL
        $nfOBJ = new c_nota_fiscal();
        $nfOBJ->setId($idNf);
        $nfArray = $nfOBJ->select_nota_fiscal($conn);

        //DADOS DA EMPRESA/EMITENTE
        $filialArray = $this->select_empresa_centro_custo($filial);

        // DADOS DO DESTINATARIO
        $pessoaDestOBJ = new c_conta();
        $pessoaDestOBJ->setId($nfArray[0]['PESSOA']);
        $pessoaDestArray = $pessoaDestOBJ->select_conta();

        // DADOS DO TRANSPORTADOR
        $transpOBJ = new c_conta();
        $transpOBJ->setId($nfArray[0]['TRANSPORTADOR']);
        $transpArray = $transpOBJ->select_conta();

        // DADOS NF PRODUTO
        $nfProdutoOBJ = new c_nota_fiscal_produto();
        $nfProdutoOBJ->setIdNf($idNf);
        $produtoArray = $nfProdutoOBJ->select_nota_fiscal_produto_nf($conn);

        // DADOS FINANCEIRO
        $lancamento = new c_lancamento();
        $financeiro = $lancamento->select_lancamento_doc('PED', $nfArray[0]['DOC'], $conn);

        // DADOS NAT OPERACAO TRIBUTOS
        $ObjNatOperTrib = new c_nat_tributos();
        $ObjNatOperTrib->setIdNatop($nfArray[0]['IDNATOP']);
        $natOperTrib = $ObjNatOperTrib->selectTributos();

        // Reordene o array usando a função usort() e a função de comparação
        if ($financeiro !== null) {
            usort($financeiro, array("p_nfe_40", "compararValores"));
        }

        $financeiroAgrupado = $lancamento->select_lancamento_doc_tipodocto('PED', $nfArray[0]['DOC'], $conn);

        // incluir codigo e desc pais na tabela cidade.
        // codigo do municipio emitente
        $cMunEmit = $filialArray[0]['CODMUNICIPIO']; // pg 175 -incluir código do municipio na tabela amb_empresa, buscas os 2 primeiros digitos do codigo
        // codigo do municipio destinatario
        $cMunDest = $pessoaDestArray[0]['CODMUNICIPIO']; // pg 181 -incluir código do municipio na tabela fin_cliente 
        // pag 180 = CRT = Codigo de Regime Tributario 1=Simples Nacional;2=Simples Nacional, excesso sublimite de receita bruta;3=Regime Normal. (v2.0). 
        // incluir na amb_empresa
        $crt = $filialArray[0]['REGIMETRIBUTARIO'];  // ok código regime tributário 1=Simples Nacional; 2=Simples Nacional, excesso sublimite de receita bruta; 3=Regime Normal. (v2.0).

        // indPres = OK pag 177 = verificar calculo de tipo venda   fin_fat_pedido
        // Indicador de presença do comprador no estabelecimento comercial no momento da operação
        // 0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
        // 1=Operação presencial;
        // 2=Operação não presencial, pela Internet;
        // 3=Operação não presencial, Teleatendimento;
        // 4=NFC-e em operação com entrega a domicílio;
        // 9=Operação não presencial, outros.
        // 
        // 
        // pag 177 = indFinal = indicacao de venda consumidor final
        // se pessoa fisica ou IE não for preenchida, sistema considera venda consumidor final
        // PAG 181 = indIEDest = Indicador da IE do Destinatário
        /*  1=Contribuinte ICMS (informar a IE do destinatário);
            2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
            9=Não Contribuinte, que pode ou não possuir Inscrição
            Estadual no Cadastro de Contribuintes do ICMS.
            Nota 1: No caso de NFC-e informar indIEDest=9 e não informar
            a tag IE do destinatário;
            Nota 2: No caso de operação com o Exterior informar
            indIEDest=9 e não informar a tag IE do destinatário;
            Nota 3: No caso de Contribuinte Isento de Inscrição
            (indIEDest=2), não informar a tag IE do destinatário. */
        $tipoPessoa = $pessoaDestArray[0]['PESSOA'];
        $ie = $pessoaDestArray[0]['INSCESTRG'];
        if (($tipoPessoa == "J") and (strlen($ie) > 0)):
            //NEW CONDITION FOR SALE WITHIN THE STATE
            if ($nfArray[0]['VENDAPRESENCIAL'] == 'S') {
                $indFinal = 1; // normal
                $indIEDest = 1;
            } else {
                $indFinal = 0; // normal
                $indIEDest = 1;
            }
        else:
            $indFinal = 1; // consumidor final
            if (($tipoPessoa == "F") or (strlen($ie) <= 0) or ($ie == 'ISENTO')):
                $indIEDest = 9;
            else:
                $indIEDest = 2;
            endif;
        endif;
        // pag 182 = OK - suframa = codigo SUFRAMA, incluir fin_cliente
        // pag 182 = OK - email = email do destinatario para receber nf, incluir fin_cliente


        // GERA XML NFEPHP
        //Dados da NFe - infNFe
        $cUF = substr($cMunEmit, 0, 2); // pg 175 - buscas os 2 primeiros digitos do codigo do municipio '52'; //codigo numerico do estado
        $cNF = rand(1, 99999999);
        //$cNF = str_pad($nfArray[0]['NUMERO'], 8, "0",STR_PAD_LEFT); //'00000010'; //numero aleatório da NF
        $natOp = $this->removeAcentos($nfArray[0]['NATOPERACAO']); //'Venda de Produto'; //natureza da operação
        $indPag = $nfArray[0]['FORMAPGTO']; //0=Pagamento à vista; 1=Pagamento a prazo; 2=Outros
        $mod = $nfArray[0]['MODELO']; //modelo da NFe 55 ou 65 essa última NFCe
        $serie = $nfArray[0]['SERIE']; //serie da NFe
        $nNF = $nfArray[0]['NUMERO']; // numero da NFe
        $dhEmi = $this->MostraData($nfArray[0]['EMISSAO']); //date("Y-m-d\TH:i:sP");//Formato: “AAAA-MM-DDThh:mm:ssTZD” (UTC - Universal Coordinated Time).
        $tpNF = $nfArray[0]['TIPO']; // 0=Entrada; 1=Saída;
        
        //new validation referent the sale within the state
        if ($nfArray[0]['VENDAPRESENCIAL'] == 'S') {
            $idDest = '1';
        } else {
            if ($filialArray[0]['UF'] == $pessoaDestArray[0]['UF']) {
                $idDest = '1'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
            } else {
                $idDest = '2'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
            }
        }
        $cMunFG = $cMunEmit;
        // $cMunFG = '';
        if ($nfArray[0]['MODELO'] == 55):
            $tpImp = '1';
            if (!empty($gerarXML)) {
                $dhSaiEnt = $this->MostraData($nfArray[0]['DATASAIDAENTRADA']); //Não informar este campo para a NFC-e.                  
            } else {
                $dhSaiEnt = date("Y-m-d\TH:i:sP"); //Não informar este campo para a NFC-e.
            }
        else:
            $tpImp = '4';
            $dhSaiEnt = ''; //Não informar este campo para a NFC-e.
        endif;
        //0=Sem geração de DANFE; 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem;
        //3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletrônica
        //(o envio de mensagem eletrônica pode ser feita de forma simultânea com a impressão do DANFE;
        //usar o tpImp=5 quando esta for a única forma de disponibilização do DANFE).
        $tpEmis = '1'; //1=Emissão normal (não em contingência);
        //2=Contingência FS-IA, com impressão do DANFE em formulário de segurança;
        //3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional);
        //4=Contingência DPEC (Declaração Prévia da Emissão em Contingência);
        //5=Contingência FS-DA, com impressão do DANFE em formulário de segurança;
        //6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN);
        //7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
        //9=Contingência off-line da NFC-e (as demais opções de contingência são válidas também para a NFC-e);
        //Nota: Para a NFC-e somente estão disponíveis e são válidas as opções de contingência 5 e 9.
        $tpAmb = ADMnfeAmbiente; //1=Produção; 2=Homologação
        $finNFe = $nfArray[0]['FINALIDADEEMISSAO']; //1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolução/Retorno.

        //$indFinal = $indFinal; //0=Normal; 1=Consumidor final;

        // IndIntermed = 0 - sem intermediador 1 - com intermediador ( cnpj e id do usuário de quem vendeu ( ex: mercado livre ))
        $indIntermed = 0;

        //0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
        //1=Operação presencial;
        //2=Operação não presencial, pela Internet;
        //3=Operação não presencial, Teleatendimento;
        //4=NFC-e em operação com entrega a domicílio;
        //9=Operação não presencial, outros.

        //NEW VALIDATION FOR SALE WITHIN THE STATE
        if ($nfArray[0]['VENDAPRESENCIAL'] == 'S') {
            $indPres = '1';
        } else {
            $indPres = '0';
        }
        $procEmi = '0'; //0=Emissão de NF-e com aplicativo do contribuinte;
        //1=Emissão de NF-e avulsa pelo Fisco;
        //2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco;
        //3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
        $verProc = '4.0.43'; //versão do aplicativo emissor
        $dhCont = ''; //entrada em contingência AAAA-MM-DDThh:mm:ssTZD
        $xJust = ''; //Justificativa da entrada em contingência

        //Numero e versão da NFe (infNFe)
        $ano = date('y', strtotime($dhEmi));
        $mes = date('m', strtotime($dhEmi));
        $cnpj = $filialArray[0]['CNPJ'];
        if (($nfArray[0]["SITUACAO"] == 'A') or ($nfArray[0]["SITUACAO"] == 'P')) {
            $chave =  NFePHP\Common\Keys::build($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);
        } else {
            $chave =  $nfArray[0]["CHNFE"];
        }
        //nfe40  $chave = $nfe->montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);
        //nfe40  $versao = '3.10';
        //nfe40  $resp = $nfe->taginfNFe($chave, $versao);
        $std = new \stdClass();
        $std->versao = '4.00';
        $std->Id = $chave;
        $elem = $nfe->taginfNFe($std);

        $cDV = substr($chave, -1); //Digito Verificador da Chave de Acesso da NF-e, o DV é calculado com a aplicação do algoritmo módulo 11 (base 2,9) da Chave de Acesso.

        //tag IDE
        //nfe40 $resp = $nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);
        $std = new \stdClass();
        $std->cUF = $cUF;
        $std->cNF = $cNF;
        $std->natOp = $natOp;
        $std->indPag = $indPag; //NÃO EXISTE MAIS NA VERSÃO 4.00
        $std->mod = $mod;
        $std->serie = $serie;
        $std->nNF = $nNF;
        $std->dhEmi = $dhEmi;
        $std->dhSaiEnt = $dhSaiEnt;
        $std->tpNF = $tpNF;
        $std->idDest = $idDest;
        $std->cMunFG = $cMunFG;
        $std->tpImp = $tpImp;
        $std->tpEmis = $tpEmis;
        $std->cDV = $cDV;
        $std->tpAmb = $tpAmb; // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
        $std->finNFe = $finNFe;
        $std->indFinal = $indFinal;
        $std->indPres = $indPres;
        $std->indIntermed = $indIntermed;
        $std->procEmi = $procEmi;
        $std->verProc = $verProc;
        $elem = $nfe->tagide($std);

        //refNFe NFe referenciada  -- verificar
        if (($finNFe == 2) or ($finNFe == 4)):
            $std = new stdClass();
            $std->refNFe = $nfArray[0]['NFEREFERENCIADA'];
            $elem = $nfe->tagrefNFe($std);
        endif;

        //refNF Nota Fiscal 1A referenciada
        //$cUF = '35';
        //$AAMM = '1312';
        //$CNPJ = '12345678901234';
        //$mod = '1A';
        //$serie = '0';
        //$nNF = '1234';
        //$resp = $nfe->tagrefNF($cUF, $AAMM, $CNPJ, $mod, $serie, $nNF);

        //NFPref Nota Fiscal Produtor Rural referenciada
        //$cUF = '35';
        //$AAMM = '1312';
        //$CNPJ = '12345678901234';
        //$CPF = '123456789';
        //$IE = '123456';
        //$mod = '1';
        //$serie = '0';
        //$nNF = '1234';
        //$resp = $nfe->tagrefNFP($cUF, $AAMM, $CNPJ, $CPF, $IE, $mod, $serie, $nNF);

        //CTeref CTe referenciada
        //$refCTe = '12345678901234567890123456789012345678901234';
        //$resp = $nfe->tagrefCTe($refCTe);

        //ECFref ECF referenciada
        //$mod = '90';
        //$nECF = '12243';
        //$nCOO = '111';
        //$resp = $nfe->tagrefECF($mod, $nECF, $nCOO);

        //Dados do emitente - (Importando dados do config.json)
        $CNPJ = $filialArray[0]['CNPJ'];
        $CPF = ''; // Utilizado para CPF na nota
        $xNome = $this->removeAcentos($filialArray[0]['NOMEEMPRESA']);
        $xFant = $filialArray[0]['NOMEFANTASIA'];
        $IE = $filialArray[0]['INSCESTADUAL'];
        $IEST = '';
        $IM = $filialArray[0]['INSCMUNICIPAL'];
        $CNAE = '';
        $CRT = $filialArray[0]['REGIMETRIBUTARIO'];
        //nfe40  $resp = $nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);

        $std = new stdClass();
        $std->xNome = $xNome;
        $std->xFant = $xFant;
        $std->IE = $IE;
        $std->IEST = $IEST;
        $std->IM = $IM;
        $std->CNAE = $CNAE;
        $std->CRT = $CRT;
        $std->CNPJ = $CNPJ; //indicar apenas um CNPJ ou CPF
        $std->CPF = $CPF;

        $elem = $nfe->tagemit($std);

        //endereço do emitente
        $xLgr = $this->removeAcentos($filialArray[0]['TIPOEND'] . " " . $filialArray[0]['TITULOEND'] . " " . $filialArray[0]['ENDERECO']); //'Av. Rio de Janeiro';
        $nro = $filialArray[0]['NUMERO'];
        $xCpl = '';
        if (!$filialArray[0]['COMPLEMENTO'] == ''):
            $xCpl = "<xCpl>{$this->removeAcentos($filialArray[0]['COMPLEMENTO'])}</xCpl>";
        endif;
        $xBairro = $this->removeAcentos($filialArray[0]['BAIRRO']);
        $cMun = $cMunEmit;
        $xMun = $this->removeAcentos($filialArray[0]['CIDADE']);
        $UF = $filialArray[0]['UF'];
        $CEP = $filialArray[0]['CEP'];
        if (strlen($CEP) == 7) {
            $CEP = '0' . $CEP;
        }
        $cPais = '1058';
        $xPais = 'Brasil';
        $fone = $filialArray[0]['FONEAREA'] . $filialArray[0]['FONENUM'];
        //nfe40 $resp = $nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
        $std = new \stdClass();
        $std->xLgr = $xLgr;
        $std->nro = $nro;
        $std->xBairro = $xBairro;
        $std->cMun = $cMun;
        $std->xMun = $xMun;
        $std->UF = $UF;
        $std->CEP = $CEP;
        if (strlen($CEP) == 7) {
            $CEP = '0' . $CEP;
        }
        $std->cPais = $cPais;
        $std->xPais = $xPais;
        $std->fone = $fone;
        $elem = $nfe->tagenderEmit($std);

        //destinatário
        if ($nfArray[0]['MODELO'] == 55):
            if ($tipoPessoa == "J"):
                $CNPJ = $pessoaDestArray[0]['CNPJCPF'];
                $CPF = '';
            else:
                $CNPJ = '';
                $CPF = $this->removeChar($pessoaDestArray[0]['CNPJCPF']);
            endif;
            $idEstrangeiro = '';
            $xNome = $this->removeAcentos($pessoaDestArray[0]['NOME']);
            //$indIEDest = '1';  //acima
            $IE = '';
            if ($indIEDest == 1):
                $IE = $pessoaDestArray[0]['INSCESTRG'];
            endif;
            $ISUF = '';
            if (strlen($pessoaDestArray[0]['SUFRAMA']) > 0):
                $ISUF = $pessoaDestArray[0]['SUFRUMA'];
            endif;
            $IM = $pessoaDestArray[0]['IM'];
            if (strlen($pessoaDestArray[0]['EMAILNFE']) > 0):
                $email = $pessoaDestArray[0]['EMAILNFE'];
            else:
                $email = $pessoaDestArray[0]['EMAIL'];
            endif;
            //nfe40            $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
            $std = new \stdClass();
            $std->xNome = $xNome;
            $std->indIEDest = $indIEDest;
            $std->IE = $IE;
            $std->CNPJ = $CNPJ;
            $std->CPF = $CPF;
            $std->IM = $IM;
            $std->ISUF = $ISUF;
            $std->email = $email;
            $elem = $nfe->tagdest($std);

            //Endereço do destinatário
            $xLgr = $this->removeAcentos($pessoaDestArray[0]['ENDERECO']); //'Av. Vila Alpes';
            $nro = $this->removeAcentos($pessoaDestArray[0]['NUMERO']);
            $xCpl = '';
            if (!$pessoaDestArray[0]['COMPLEMENTO'] == ''):
                $xCpl = $this->removeAcentos($pessoaDestArray[0]['COMPLEMENTO']);
            endif;
            $xBairro = $this->removeAcentos($pessoaDestArray[0]['BAIRRO']);
            $cMun = $cMunDest;
            $xMun = $this->removeAcentos($pessoaDestArray[0]['CIDADE']);
            $UF = $this->removeAcentos($pessoaDestArray[0]['UF']);
            $CEP = $this->removeAcentos($pessoaDestArray[0]['CEP']);
            if (strlen($CEP) == 7) {
                $CEP = '0' . $CEP;
            }
            $cPais = '1058';
            $xPais = 'Brasil';
            $fone = preg_replace("/[^0-9]/", "", $pessoaDestArray[0]['FONE']);
            //nfe40            $resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
            $std = new \stdClass();
            $std->xLgr = $xLgr;
            $std->nro = $nro;
            $std->xBairro = $xBairro;
            $std->cMun = $cMun;
            $std->xMun = $xMun;
            $std->UF = $UF;
            $std->CEP = $CEP;
            $std->fone = $fone;
            $std->cPais = '1058';
            $std->xPais = 'BRASIL';
            $elem = $nfe->tagenderDest($std);

        else:
            //destinatário
            if ($nfArray[0]['CPFNOTA'] != ''):
                if (strlen($nfArray[0]['CPFNOTA']) > 11):
                    //$CNPJ = '22886247000190';
                    $CNPJ = $nfArray[0]['CPFNOTA'];
                    $CPF = '';
                else:
                    $CNPJ = '';
                    $CPF = $this->removeChar($nfArray[0]['CPFNOTA']);
                endif;

                $idEstrangeiro = '';
                $xNome = '';
                $indIEDest = '9';
                $IE = '';
                $ISUF = '';
                $IM = '';
                $email = '';
                //nfe40                $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
                $std = new \stdClass();
                $std->xNome = $xNome;
                $std->indIEDest = $indIEDest;
                $std->IE = $IE;
                $std->CNPJ = $CNPJ;
                $std->CPF = $CPF;
                $std->IM = $IM;
                $std->ISUF = $ISUF;
                $std->email = $email;
                $elem = $nfe->tagdest($std);
            endif;
        endif;

        //Identificação do local de retirada (se diferente do emitente)
        //$CNPJ = '12345678901234';
        //$CPF = '';
        //$xLgr = 'Rua Vanish';
        //$nro = '000';
        //$xCpl = 'Ghost';
        //$xBairro = 'Assombrado';
        //$cMun = '3509502';
        //$xMun = 'Campinas';
        //$UF = 'SP';
        //$resp = $nfe->tagretirada($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

        //Identificação do local de Entrega (se diferente do destinatário)
        //$CNPJ = '12345678901234';
        //$CPF = '';
        //$xLgr = 'Viela Mixuruca';
        //$nro = '2';
        //$xCpl = 'Quabrada do malandro';
        //$xBairro = 'Favela Mau Olhado';
        //$cMun = '3509502';
        //$xMun = 'Campinas';
        //$UF = 'SP';
        //$resp = $nfe->tagentrega($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

        //Identificação dos autorizados para fazer o download da NFe (somente versão 3.1)
        /*$aAut = array('23401454000170');
        foreach ($aAut as $aut) {
            if (strlen($aut) == 14) {
                $resp = $nfe->tagautXML($aut);
            } else {
                $resp = $nfe->tagautXML('', $aut);
            }
        }*/

        for ($i = 0; $i < count($produtoArray); $i++) {
            $nItem = $i + 1;
            $cProd = $produtoArray[$i]['CODIGONOTA'];
            $cProd = trim($cProd);
            if ($cProd == "") {
                $cProd = $produtoArray[$i]['CODPRODUTO'];
            }
            $prefixo = substr($produtoArray[$i]['CODIGOBARRAS'], 0, 3);
            if ((strlen($produtoArray[$i]['CODIGOBARRAS']) > 0) and ($prefixo != '047')):
                // COMPLETA COM ZEROS PARA OS TAMANHOS 8, 12, 13, 14
                $cEAN = $produtoArray[$i]['CODIGOBARRAS'];
                $cEANTrib = $produtoArray[$i]['CODIGOBARRAS'];
            else:
                $cEAN = 'SEM GTIN';
                $cEANTrib = 'SEM GTIN';
            endif;
            $xProd = $this->removeAcentos($produtoArray[$i]['DESCRICAO']);
            if (!$produtoArray[$i]['NCM'] == ''):
                $NCM = $produtoArray[$i]['NCM'];
            else:
                $NCM = '00';
            endif;
            $EXTIPI = '';
            $CFOP = $produtoArray[$i]['CFOP'];
            $uCom = $produtoArray[$i]['UNIDADE'];
            $qCom = $produtoArray[$i]['QUANT'];
            $vUnCom = $produtoArray[$i]['UNITARIO'];
            $vProd = $produtoArray[$i]['TOTAL'];
            $uTrib = $produtoArray[$i]['UNIDADE'];
            $qTrib = $produtoArray[$i]['QUANT'];
            $vUnTrib = $produtoArray[$i]['UNITARIO'];
            $cBenef = $produtoArray[$i]['CBENEF'];
            if ($cBenef == '0') {
                $cBenef = '';
            }
            if ($produtoArray[$i]['FRETE'] > 0) {
                $vFrete = number_format($produtoArray[$i]['FRETE'], 2, '.', '');
            } else {
                $vFrete = '';
            }

            $vSeg = '';
            $vDesc = number_format($produtoArray[$i]['DESCONTO'], 2, '.', '');
            $vDescTotal += $vDesc;
            if ($produtoArray[$i]['DESPACESSORIAS'] > 0) {
                $vOutro = number_format($produtoArray[$i]['DESPACESSORIAS'], 2, '.', '');
                $vOutroTotal += $vOutro;
            }
            $indTot = '1';
            $xPed = $produtoArray[$i]['ORDEM'];
            $nItemPed = '';
            $nFCI = '';
            //nfe40 $resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);

            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->cProd = $cProd;
            $std->cEAN = $cEAN;
            $std->xProd = $xProd;
            $std->NCM = $NCM;

            $std->cBenef = $cBenef; //incluido no layout 4.00

            $std->EXTIPI = $EXTIPI;
            $std->CFOP = $CFOP;
            $std->uCom = $uCom;
            $std->qCom = $qCom;
            $std->vUnCom = $vUnCom;
            $std->vProd = $vProd;
            $std->cEANTrib = $cEANTrib;
            $std->uTrib = $uTrib;
            $std->qTrib = $qTrib;
            $std->vUnTrib = $vUnTrib;
            $std->vFrete = $vFrete;
            $std->vSeg = $vSeg;

            if ($vDesc != '0.00'):
                $std->vDesc = $vDesc;
            endif;

            $std->vOutro = $vOutro;
            $std->indTot = $indTot;
            $std->xPed = $xPed;
            $std->nItemPed = $nItemPed;
            $std->nFCI = $nFCI;


            $consultaAnp = new c_banco();
            $consultaAnp->setTab('EST_PRODUTO');
            $selectAnpProd = $consultaAnp->getField('ANP', 'CODIGO = ' . $produtoArray[$i]['CODPRODUTO']);

            //inclusao tag combustivel
            if ($selectAnpProd !== '' and $selectAnpProd !== null and $selectAnpProd !== " ") {
                //consulta os dados anp
                $sql = "select * from est_anp where anp = '" . $selectAnpProd . "';";

                $consultaAnp->exec_sql($sql);
                $consultaAnp->close_connection();
                $resultAnp = $consultaAnp->resultado;

                //mandatory
                $std->cProdANP = $resultAnp[0]['ANP'];
                $std->descANP = $resultAnp[0]['DESCRICAO']; //incluido no layout 4.00
                $std->UFCons = $pessoaDestArray[0]['UF'];
                //end mandatory
                //$std->pGLP; //incluido no layout 4.00
                //$std->pGNn; //incluido no layout 4.00
                //$std->pGNi; //incluido no layout 4.00
                //$std->vPart; //incluido no layout 4.00
                //$std->CODIF;
                //$std->qTemp;
                //$std->qBCProd;
                //$std->vAliqProd;
                //$std->vCIDE;
                $elem = $nfe->tagcomb($std);
            }


            $elem = $nfe->tagprod($std);

            if (!$produtoArray[$i]['CEST'] == ''):
                $std = new stdClass();
                $std->item = $nItem; //item da NFe
                $std->CEST = $produtoArray[$i]['CEST'];
                $std->indEscala = 'S'; //incluido no layout 4.00
                //$std->CNPJFab = '12345678901234'; //incluido no layout 4.00
                $elem = $nfe->tagCEST($std);
            endif;

            ### function tagRastro($std):DOMElement
            //Node com os dados de rastreabilidade do item da NFe
            //*Método Incluso para atender layout 4.00*
            //| Parametro | Tipo | Descrição |
            //| :--- | :---: | :--- |
            //| $std | stcClass | contêm os dados dos campos, nomeados conforme manual |

            //$elem = $nfe->tagRastro($std);            
            if (!$produtoArray[$i]['LOTE'] == ''):
                $nLote = $produtoArray[$i]['LOTE'];
                $qLote = number_format($produtoArray[$i]['QUANT'], 3, '.', '');
                $dFab = $produtoArray[$i]['DATAFABRICACAO'];
                $dVal = $produtoArray[$i]['DATAVALIDADE'];
                $std = new stdClass();
                $std->item = $nItem; //item da NFe
                $std->nLote = $nLote;
                $std->qLote = $qLote;
                $std->dFab = $dFab;
                $std->dVal = $dVal;

                $std->cAgreg = '1234';
                $elem = $nfe->tagRastro($std);
            endif;

            //impostos ============================================
            switch ($crt):
                case '1': //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
                    $orig = $produtoArray[$i]['ORIGEM'];
                    $csosn = '';
                    $modBC  = '';
                    $vBC = '';
                    $pRedBC = '';
                    $pICMS = '';
                    $vICMS = '';
                    $pCredSN = '';
                    $vCredICMSSN = '';
                    $modBCST = '';
                    $pMVAST = '';
                    $pRedBCST = '';
                    $vBCST = '';
                    $pICMSST = '';
                    $vICMSST = '';
                    $vBCSTRet = '';
                    $vICMSSTRet = '';
                    $pCredSN = '';
                    $vCredICMSSN = '';

                    switch ($produtoArray[$i]['TRIBICMS']) {
                        case '101':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '101'; //101=Tributada pelo Simples Nacional com permissão de crédito. (v2.0)
                            // $modBC = $produtoArray[$i]['MODBC'];
                            // $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                            // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                            // $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                            // $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                            // $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                            $pCredSN = $produtoArray[$i]['PCREDSN']; //Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                            $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)

                            // $modBC = $produtoArray[$i]['MODBC'];
                            // $vBC = $produtoArray[$i]['BCICMS'];
                            // $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            // $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            // $vICMS = $produtoArray[$i]['VALORICMS'];

                            // $vICMS = number_format(($vBC * ($pICMS/100)), 2, '.', ''); 
                            // $produtoArray[$i]['VALORICMS'] = $vICMS;


                            // $modBC = $produtoArray[$i]['MODBC'];
                            // $vBC = $produtoArray[$i]['BCICMS'];
                            // $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            // $vICMS = $produtoArray[$i]['VALORICMS'];

                            // $pCredSN = $produtoArray[$i]['ALIQICMS'];//Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                            // $vCredICMSSN = $produtoArray[$i]['VALORICMS']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)
                            break;
                        case '102':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '102';
                            break;
                        case '103':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '103';
                            break;
                        case '300':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '300';
                            break;
                        case '400':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '400';
                            break;
                        case '201':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '201'; //201=Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por Substituição Tributária (v2.0) 
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor); (v2.0)
                            // $modBC = $produtoArray[$i]['MODBC'];
                            // $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                            // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                            // $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                            // $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                            // $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                            $pCredSN = $produtoArray[$i]['PCREDSN']; //Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                            $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)
                            break;
                        case '202':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            //202=Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por Substituição Tributária;
                            $csosn = '202';
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor); (v2.0)
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                            $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                            $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                            break;
                        case '203':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            //203- Isenção do ICMS nos Simples Nacional para faixa de receita bruta e com cobrança do ICMS por Substituição Tributária (v2.0)
                            $csosn = '203';
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor); (v2.0)
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                            $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                            $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                            break;
                        case '500':
                            $orig = $produtoArray[$i]['ORIGEM'];
                            //500=ICMS cobrado anteriormente por substituição tributária(substituído) ou por antecipação. (v2.0)
                            $csosn = '500';
                            //Valor da BC do ICMS ST cobrado anteriormente por ST (v2.0).
                            //O valor pode ser omitido quando a legislação não exigir a sua informação. (NT 2011/004)
                            $pST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                            //Valor do ICMS ST cobrado anteriormente por ST (v2.0). O valor pode ser omitido quando a legislação não exigir a sua informação. (NT 2011/004)
                            $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                            break;
                        case '900': // Tributação ICMS: Outros
                            if (($nfArray[0]['FINALIDADEEMISSAO'] == 10) and ($crt == '1')) {
                                $orig = $produtoArray[$i]['ORIGEM'];
                                $csosn = '900'; //900=Outros (v2.0)
                            } else {
                                $orig = $produtoArray[$i]['ORIGEM'];
                                $csosn = '900'; //900=Outros (v2.0)


                                //0=Margem Valor Agregado (%);
                                //1=Pauta (Valor);
                                //2=Preço Tabelado Máx. (valor);
                                //3=Valor da operação. (v2.0)
                                $modBC = $produtoArray[$i]['MODBC'];
                                $vBC = $produtoArray[$i]['BCICMS'];
                                $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                                $pICMS = $produtoArray[$i]['ALIQICMS'];
                                $vICMS = $produtoArray[$i]['VALORICMS'];

                                //$vICMS = number_format(($vBC * ($pICMS/100)), 2, '.', ''); 
                                $produtoArray[$i]['VALORICMS'] = $vICMS;


                                //0=Preço tabelado ou máximo sugerido;
                                //1=Lista Negativa (valor);
                                //2=Lista Positiva (valor);
                                //3=Lista Neutra (valor);
                                //4=Margem Valor Agregado (%);
                                //5=Pauta (valor); (v2.0)

                                if (($produtoArray[$i]['VALORICMSST'] > 0) and ($produtoArray[$i]['MODBCST'] != '')) {
                                    $modBCST = $produtoArray[$i]['MODBCST'];
                                    $pMVAST = $produtoArray[$i]['PERCMVAST'];
                                    // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                                    $vBCST = $produtoArray[$i]['VALORBCST'];
                                    $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                                    $vICMSST = $produtoArray[$i]['VALORICMSST'];

                                    $pCredSN = $produtoArray[$i]['PCREDSN'];
                                    $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN'];
                                }
                            }
                            break;
                    }

                    $std = new stdClass();
                    $std->item = $nItem;
                    $std->orig = $orig;

                    if ($orig != '') {
                        $std->orig = $orig;
                    }
                    if ($csosn != '') {
                        $std->CSOSN = $csosn;
                    }

                    //nova validacao de quando for 500 é obrigatorio essas 3 tags
                    if ($csosn == '500') {
                        if ($pST != '') {
                            $std->pST = $pST;
                        };
                        if ($vBCSTRet != '') {
                            $std->vBCSTRet = $vBCSTRet;
                        };
                        if ($vICMSSTRet != '') {
                            $std->vICMSSTRet = $vICMSSTRet;
                        }
                    }
                    if ($modBC != '') {
                        $std->modBC = $modBC;
                    }
                    if ($vBC != '') {
                        $std->vBC = $vBC;
                    }
                    if ($pRedBC != '') {
                        $std->pRedBC = $pRedBC;
                    }
                    if ($pICMS != '') {
                        $std->pICMS = $pICMS;
                    }
                    if ($vICMS != '') {
                        $std->vICMS = $vICMS;
                    }
                    if ($modBCST != '') {
                        $std->modBCST = $modBCST;
                    }
                    if ($pMVAST != '') {
                        $std->pMVAST = $pMVAST;
                    }
                    if ($pRedBCST != '') {
                        $std->pRedBCST = $pRedBCST;
                    }
                    if ($vBCST != '') {
                        $std->vBCST = $vBCST;
                    }
                    if ($pICMSST != '') {
                        $std->pICMSST = $pICMSST;
                    }
                    if ($vICMSST != '') {
                        $std->vICMSST = $vICMSST;
                    }
                    if ($pCredSN != '') {
                        $std->pCredSN = $pCredSN;
                    }
                    if ($vCredICMSSN != '') {
                        $std->vCredICMSSN = $vCredICMSSN;
                    }


                    //vBCFCPST
                    //pFCPST
                    //vFCPST

                    /*
                if ($pCredSN != '') {$std->pCredSN = $pCredSN;}
                
                if ($produtoArray[$i]['TRIBICMS'] == '00'){
                    if ($cst != '')  {$std->CST = $cst;} 
                    $elem = $nfe->tagICMS($std);  
                } else {
                    $elem = $nfe->tagICMSSN($std); 
                } */

                    $elem = $nfe->tagICMSSN($std);
                    // $elem = $nfe->tagICMS($std);   

                    break;
                case '2': //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
                    $orig = $produtoArray[$i]['ORIGEM'];
                    $cst = '';
                    $modBC  = '';
                    $vBC = '';
                    $pRedBC = '';
                    $pICMS = '';
                    $vICMS = '';
                    $pCredSN = '';
                    $vCredICMSSN = '';
                    $modBCST = '';
                    $pMVAST = '';
                    $pRedBCST = '';
                    $vBCST = '';
                    $pICMSST = '';
                    $vICMSST = '';
                    $vBCSTRet = '';
                    $vICMSSTRet = '';
                    $pCredSN = '';
                    $vCredICMSSN = '';

                    $std = new stdClass();
                    $std->item = $nItem;
                    $std->orig = $orig;
                    $std->CST = null;
                    $std->pCredSN = null;
                    $std->vCredICMSSN = null;
                    $std->modBCST = null;
                    $std->pMVAST = null;
                    $std->pRedBCST = null;
                    $std->vBCST = null;
                    $std->pICMSST = null;
                    $std->vICMSST = null;
                    $std->vBCFCPST = null;
                    $std->pFCPST = null;
                    $std->vFCPST = null;
                    $std->vBCSTRet = null;
                    $std->pST = null;
                    $std->vICMSSTRet = null;
                    $std->vBCFCPSTRet = null;
                    $std->pFCPSTRet = null;
                    $std->vFCPSTRet = null;
                    $std->modBC = null;
                    $std->vBC = null;
                    $std->pRedBC = null;
                    $std->pICMS = null;
                    $std->vICMS = null;
                    $std->pRedBCEfet = null;
                    $std->vBCEfet = null;
                    $std->pICMSEfet = null;
                    $std->vICMSEfet = null;
                    $std->pCredSN = null;
                    $std->vCredICMSSN = null;

                    switch ($produtoArray[$i]['TRIBICMS']) {
                        case '00': // tributado integralmente
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '00';
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação 
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = number_format($produtoArray[$i]['BCICMS'], 2, '.', '');
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMS = $produtoArray[$i]['VALORICMS'];
                            break;
                        case '10': // Tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '10';
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMS = $produtoArray[$i]['VALORICMS'];
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor)
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST'];
                            break;
                        case '20': // Tributação com redução de base de cálculo
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '20';
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMS = $produtoArray[$i]['VALORICMS'];
                            //$vICMSDeson = //Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário
                            //motDesICMS = 
                            break;
                        case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '30';
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST'];
                            //$vICMSDeson = Informar apenas nos motivos de desoneração documentados abaixo.
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //6=Utilitários e Motocicletas da Amazônia Ocidental e Áreas de Livre Comércio (Resolução 714/88 e 790/94 – CONTRAN e suas alterações);
                            //7=SUFRAMA;
                            //9=Outros;
                            //motDesICMS =  
                            break;
                        case '40': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '40'; //40=Isenta;
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS                            
                            break;
                        case '41': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '40'; //41=Não tributada;
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS                            
                            break;
                        case '50': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '50'; //50=Suspensão.                            
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS       
                            break;
                        case '51': // Tributação com Diferimento (a exigência do preenchimento das
                            //informações do ICMS diferido fica a critério de cada UF).
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '51'; //51=Diferimento 
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMSOp = $produtoArray[$i]['VALORICMSOPERACAO']; //Valor como se não tivesse o diferimento
                            $pDif = $produtoArray[$i]['PERCDIFERIDO']; //No caso de diferimento total, informar o percentual de diferimento "100".
                            $vICMSDif = $produtoArray[$i]['VALORICMSDIFERIDO'];
                            $vICMS = $produtoArray[$i]['VALORICMS']; // Informar o valor realmente devido
                            break;
                        case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '60'; //60=ICMS cobrado anteriormente por substituição tributária
                            $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                            $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                            break;
                        case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                            // do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '70'; //70=Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBC = is_null($produtoArray[$i]['PERCREDUCAOBC']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMS = $produtoArray[$i]['VALORICMS'];
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = is_null($produtoArray[$i]['PERCMVAST']) ? '0.00' : $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = is_null($produtoArray[$i]['PERCREDUCAOBCST']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = is_null($produtoArray[$i]['VALORBCST']) ? '0.00' : $produtoArray[$i]['VALORBCST'];
                            $pICMSST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = is_null($produtoArray[$i]['VALORICMSST']) ? '0.00' : $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            //$vICMSDeson Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário.
                            //$motDesICMS
                            break;
                        case '90': // Tributação ICMS: Outros
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '90'; //90=Outros
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $pICMS = $produtoArray[$i]['ALIQICMS'];
                            $vICMS = $produtoArray[$i]['VALORICMS'];
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            //$vICMSDeson Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário.
                            //$motDesICMS
                            break;
                    } //switch

                    if ($orig != '') {
                        $std->orig = $orig;
                    }
                    if ($cst != '') {
                        $std->CST = $cst;
                    }
                    if ($modBC != '') {
                        $std->modBC = $modBC;
                    }
                    if ($vBC != '') {
                        $std->vBC = $vBC;
                    }
                    if ($pRedBC != '') {
                        $std->pRedBC = $pRedBC;
                    }
                    if ($pICMS != '') {
                        $std->pICMS = $pICMS;
                    }
                    if ($vICMS != '') {
                        $std->vICMS = $vICMS;
                    }
                    if ($modBCST != '') {
                        $std->modBCST = $modBCST;
                    }
                    if ($pMVAST != '') {
                        $std->pMVAST = $pMVAST;
                    }
                    if ($pRedBCST != '') {
                        $std->pRedBCST = $pRedBCST;
                    }
                    if ($vBCST != '') {
                        $std->vBCST = $vBCST;
                    }
                    if ($pICMSST != '') {
                        $std->pICMSST = $pICMSST;
                    }
                    if ($vICMSST != '') {
                        $std->vICMSST = $vICMSST;
                    }
                    if ($pCredSN != '') {
                        $std->pCredSN = $pCredSN;
                    }
                    if ($vCredICMSSN != '') {
                        $std->vCredICMSSN = $vCredICMSSN;
                    }

                    $elem = $nfe->tagICMS($std);

                    break;

                case '3':
                    //ICMS - Imposto sobre Circulação de Mercadorias e Serviços
                    $orig = '';
                    $cst = '';
                    $modBC = '';
                    $pRedBC = '0';
                    $vBC = ''; // = $qTrib * $vUnTrib
                    $pICMS = ''; // Alíquota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
                    $vICMS = ''; // = $vBC * ( $pICMS / 100 )
                    $vICMSDeson = '';
                    $motDesICMS = '';
                    $modBCST = '';
                    $pMVAST = '';
                    $pRedBCST = '';
                    $vBCST = '0.00';
                    $pICMSST = '';
                    $vICMSST = '';
                    $pDif = '';
                    $vICMSDif = '';
                    $vICMSOp = '';
                    $vBCSTRet = '';
                    $vICMSSTRet = '';

                    switch ($produtoArray[$i]['TRIBICMS']) {
                        case '00': // tributado integralmente
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '00';
                            $modBC = '3';
                            $vBC = number_format($produtoArray[$i]['BCICMS'], 2, '.', '');
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '10': // Tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '10';
                            $modBC = '3';
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '20': // Tributação com redução de base de cálculo
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '20';
                            $modBC = '3';
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '30';
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '40': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                        case '41': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                        case '50': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = $produtoArray[$i]['TRIBICMS'];
                            break;
                        case '51': // Tributação com Diferimento (a exigência do preenchimento das
                            //informações do ICMS diferido fica a critério de cada UF).
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '51';
                            $modBC = '3';
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMSOp = $produtoArray[$i]['VALORICMSOPERACAO'];
                            $pDif = $produtoArray[$i]['PERCDIFERIDO'];
                            $vICMSDif = $produtoArray[$i]['VALORICMSDIFERIDO'];
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '60';
                            $modBC = '3';
                            $pST = is_null($produtoArray[$i]['ALIQICMSST']) ? '18.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                            $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                            $vICMSSubstituto = is_null($produtoArray[$i]['VICMSSUBSTITUTO']) ? '0.00' : $produtoArray[$i]['VICMSSUBSTITUTO'];
                            break;
                        case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                            // do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '70';
                            $modBC = '3';
                            $pRedBC = is_null($produtoArray[$i]['PERCREDUCAOBC']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = is_null($produtoArray[$i]['PERCMVAST']) ? '0.00' : $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = is_null($produtoArray[$i]['PERCREDUCAOBCST']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = is_null($produtoArray[$i]['VALORBCST']) ? '0.00' : $produtoArray[$i]['VALORBCST'];
                            $pICMSST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = is_null($produtoArray[$i]['VALORICMSST']) ? '0.00' : $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '90': // Tributação ICMS: Outros
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '90';
                            $modBC = '3';
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                    } //switch 
                    //nfe40 $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);
                    $std = new stdClass();
                    $std->item = $nItem; //item da NFe
                    $std->orig = $orig;
                    $std->CST = $cst;
                    $std->modBC = $modBC;
                    $std->vBC = $vBC;
                    $std->pICMS = $pICMS;
                    $std->vICMS = $vICMS;
                    $std->pFCP = null; // inserido nfe 40
                    $std->vFCP = null; // inserido nfe 40
                    $std->vBCFCP = null; // inserido nfe 40
                    $std->modBCST = $modBCST;
                    $std->pMVAST = $pMVAST;
                    $std->pRedBCST = $pRedBCST;
                    $std->vBCST = $vBCST;
                    $std->pICMSST = $pICMSST;
                    $std->vICMSST = $vICMSST;
                    $std->vBCFCPST = null; // inserido nfe 40
                    $std->pFCPST = null; // inserido nfe 40
                    $std->vFCPST = null; // inserido nfe 40
                    $std->vICMSDeson = $vICMSDeson;
                    $std->motDesICMS = $motDesICMS;
                    $std->pRedBC = $pRedBC;
                    $std->vICMSOp = $vICMSOp;
                    $std->pDif = $pDif;
                    $std->vICMSDif = $vICMSDif;
                    $std->vBCSTRet = $vBCSTRet;
                    $std->pST = $pST; // inserido nfe 40
                    $std->vICMSSTRet = $vICMSSTRet;
                    $std->vBCFCPSTRet = null; // inserido nfe 40
                    $std->pFCPSTRet = null; // inserido nfe 40
                    $std->vFCPSTRet = null; // inserido nfe 40
                    $std->pRedBCEfet = null; // inserido nfe 40
                    $std->vBCEfet = null; // inserido nfe 40
                    $std->pICMSEfet = null; // inserido nfe 40
                    $std->vICMSEfet = null; // inserido nfe 40

                    $elem = $nfe->tagICMS($std);
            endswitch;

            if ($crt == '1') {
                $clEnq = '';    // Classe de enquadramento do IPI para Cigarros e Bebidas
                $CNPJProd = ''; // CNPJ do produtor da mercadoria, quando diferente do emitente. Somente para os casos de exportação direta ou indireta.
                $cSelo = '';
                $qSelo = 0;
                $cEnq = '';
                $CST = '';
                $vIPI = '';
                $vBC = '';
                $pIPI = '';
                $qUni = '';
                $vUnid = '';

                $CST = $produtoArray[$i]['CSTIPI'];
                if ($produtoArray[$i]['CSTIPI'] == '') {
                    if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10) and ($crt == '1')) or
                        (($nfArray[0]['FINALIDADEEMISSAO'] == 4) and ($produtoArray[$i]['ALIQIPI'] > 0)) or
                        (($nfArray[0]['FINALIDADEEMISSAO'] == 2) and ($produtoArray[$i]['ALIQIPI'] > 0))
                    ) {
                        $CST = '99';
                    } else {
                        $CST = '53';
                    }
                }
                //if ($produtoArray[$i]['CSTIPI'] != '') {
                //    $CST = $produtoArray[$i]['CSTIPI'];
                if (($CST == '00') || ($CST == '49') or ($CST == '50') or ($CST == '99')) {
                    $cEnq = '999';     // O06 - Código de Enquadramento Legal do IPI (Tabela a ser criada pela RFB, informar 999 enquanto a tabela não for criada)
                    $vBC = $produtoArray[$i]['BCIPI'];
                    $pIPI = $produtoArray[$i]['ALIQIPI'];
                    $vIPI = $produtoArray[$i]['VALORIPI'];
                } else {
                    $cEnq = '999';
                    $CST = '53';
                }

                $std = new stdClass();
                if ($nItem != '') {
                    $std->item = $nItem;
                }
                if ($clEnq != '') {
                    $std->clEnq = $clEnq;
                }
                if ($CNPJProd != '') {
                    $std->CNPJProd = $CNPJProd;
                }
                if ($cSelo != '') {
                    $std->cSelo = $cSelo;
                }
                if ($qSelo != 0) {
                    $std->qSelo = $qSelo;
                }
                if ($cEnq != '') {
                    $std->cEnq = $cEnq;
                }
                if ($CST != '') {
                    $std->CST = $CST;
                }
                if ($vIPI != '') {
                    $std->vIPI = $vIPI;
                }
                if ($vBC != '') {
                    $std->vBC = $vBC;
                }
                if ($pIPI != '') {
                    $std->pIPI = $pIPI;
                }
                if ($qUnid != '') {
                    $std->qUnid = $qUnid;
                }
                if ($vUnid != '') {
                    $std->vUnid = $vUnid;
                }

                $elem = $nfe->tagIPI($std);
                //}
            }

            // TAG medicamentos
            $cProdANVISA = $produtoArray[$i]['CODPRODUTOANVISA'];
            if (($nfArray[0]['MODELO'] == 55) and ($cProdANVISA != null)):
                $vPMC = number_format($produtoArray[$i]['UNITARIO'], 2, '.', '');
                $std = new stdClass();
                $std->item = $nItem; //item da NFe

                $std->vPMC = $vPMC;

                $std->cProdANVISA = $cProdANVISA; //incluido no layout 4.00.00

                $elem = $nfe->tagmed($std);

            endif;

            if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10) or ($nfArray[0]['FINALIDADEEMISSAO'] == 4))
                and ($crt == '1')
            ) {
                $produtoArray[$i]['CSTPIS'] = 49;
            }

            // TAG PIS
            //PIS - Programa de Integração Social   ************* CALCULO PIS POSTERIORMENTE
            switch ($produtoArray[$i]['CSTPIS']) {
                case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS'];
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '';
                    $pPIS = '';
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = $produtoArray[$i]['BCPIS'];
                    $vAliqProd = $produtoArray[$i]['ALIQPIS'];
                    break;
                case '04':
                case '05':
                case '06':
                case '07':
                case '08':
                case '09':
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0';
                    $pPIS = '0';
                    $vPIS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                case '49':
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS'];
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    break;
                default:
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS'];
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = '0';
                    $vAliqProd = '0';
            }
            $cst = sprintf("%02d", $produtoArray[$i]['CSTPIS']); //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
            //nef40            $resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->CST = $cst;
            $std->vBC = $vBC;
            $std->pPIS = $pPIS;
            $std->vPIS = $vPIS;
            $std->qBCProd = $qBCProd;
            $std->vAliqProd = $vAliqProd;
            $elem = $nfe->tagPIS($std);

            if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10) or ($nfArray[0]['FINALIDADEEMISSAO'] == 4)) and ($crt == '1')) {
                $produtoArray[$i]['CSTCOFINS'] = 49;
            }
            // TAG COFINS
            //COFINS - Contribuição para o Financiamento da Seguridade Social
            switch ($produtoArray[$i]['CSTCOFINS']) {
                case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS'];
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '';
                    $pCOFINS = '';
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = $produtoArray[$i]['BCCOFINS'];
                    $vAliqProd = $produtoArray[$i]['ALIQCOFINS'];
                    break;
                case '04':
                case '05':
                case '06':
                case '07':
                case '08':
                case '09':
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0';
                    $pCOFINS = '0';
                    $vCOFINS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                case '49':
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS'];
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    break;
                default:
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS'];
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = '0';
                    $vAliqProd = '0';
            }
            $cst = sprintf("%02d", $produtoArray[$i]['CSTCOFINS']); //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
            //nfe40            $resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);

            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->CST = $cst;
            $std->vBC = $vBC;
            $std->pCOFINS = $pCOFINS;
            $std->vCOFINS = $vCOFINS;
            $std->qBCProd = $qBCProd;
            $std->vAliqProd = $vAliqProd;
            $elem = $nfe->tagCOFINS($std);

            //Total Impostos
            // Converte as variáveis para números válidos antes da soma
            $vICMS = is_numeric($vICMS) ? (float)$vICMS : 0;
            $vICMSST = is_numeric($vICMSST) ? (float)$vICMSST : 0;
            $vIPI = is_numeric($vIPI) ? (float)$vIPI : 0;
            $vPIS = is_numeric($vPIS) ? (float)$vPIS : 0;
            $vCOFINS = is_numeric($vCOFINS) ? (float)$vCOFINS : 0;
            $vTotTrib = number_format($vICMS + $vICMSST + $vIPI + $vPIS + $vCOFINS, 2, '.', ''); // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS

            //nfe40            $resp = $nfe->tagimposto($nItem, $vTotTrib);
            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->vTotTrib = $vTotTrib;

            $elem = $nfe->tagimposto($std);

            $vST += $vICMSST; // Total de ICMS ST

            $vBCTotal += $produtoArray[$i]['BCICMS'];
            $vICMSTotal += $produtoArray[$i]['VALORICMS'];
            $vICMSDesonTotal = 0;
            $vFCPUFDestTotal = 0;
            $vICMSUFDestTotal = 0;
            $vICMSUFRemetTotal = 0;
            $vBCSTTotal += $produtoArray[$i]['VALORBCST'];
            $vSTTotal += $produtoArray[$i]['VALORICMSST'];
            $vProdTotal += $produtoArray[$i]['TOTAL'];
            $vSegTotal = 0;

            $vIITotal = 0;
            $vIPITotal += $vIPI;
            $vPISTotal += $vPIS;
            $vCOFINSTotal += $vCOFINS;
            //$vOutroTotal=0;
            $vNFTotal = $nfArray[0]['TOTALNF'];
            $vTotTribTotal = 0;
        } //for produtos        


        //Inicialização de váriaveis não declaradas...
        $vII = isset($vII) ? $vII : 0;
        $vIPI = isset($vIPI) ? $vIPI : 0;
        $vIOF = isset($vIOF) ? $vIOF : 0;
        $vPIS = isset($vPIS) ? $vPIS : 0;
        $vCOFINS = isset($vCOFINS) ? $vCOFINS : 0;
        $vICMS = isset($vICMS) ? $vICMS : 0;
        $vBCST = isset($vBCST) ? $vBCST : 0;
        $vST = isset($vST) ? $vST : 0;
        $vISS = isset($vISS) ? $vISS : 0;

        //total
        $vBC = number_format($vBCTotal, 2, '.', '');
        //$vICMS = 226.12;//number_format($vICMSTotal, 2, '.', '');
        $vICMS = number_format($vICMSTotal, 2, '.', '');
        $vICMSDeson = '0.00';
        $vFCP = '0.00';
        $vFCPST = '0.00';
        $vFCPSTRet = '0.00';
        $vBCST = number_format($vBCSTTotal, 2, '.', '');
        $vST = number_format($vSTTotal, 2, '.', '');
        $vProd = number_format($vProdTotal, 2, '.', '');
        if ($nfArray[0]['FRETE'] != ' ') {
            $vFrete = number_format($nfArray[0]['FRETE'], 2, '.', '');
        } else {
            $vFrete = '';
        }
        $vSeg = '0.00';
        $vDesc = $vDescTotal;
        $vII = '0.00';
        $vIPI = number_format($vIPITotal, 2, '.', '');
        // testar se é nf de devolução 
        $vIPIDevol = '0.00';
        $vPIS = number_format($vPISTotal, 2, '.', '');
        $vCOFINS = number_format($vCOFINSTotal, 2, '.', '');
        $vOutro = '0.00';
        if ($vOutroTotal > 0) {
            $vOutro = number_format($vOutroTotal, 2, '.', '');
        }

        $vNF = number_format($vProd - $vDesc - $vICMSDeson + $vST + $vFrete + $vSeg + $vOutro + $vII + $vIPI, 2, '.', '');
        $vTotTrib = number_format($vICMS + $vST + $vII + $vIPI + $vPIS + $vCOFINS + $vIOF + $vISS, 2, '.', '');
        //nfe40        $resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);
        $std = new stdClass();
        $std->vBC = $vBC;
        $std->vICMS = $vICMS;
        $std->vICMSDeson = $vICMSDeson;
        $std->vFCP = $vFCP; //incluso no layout 4.00
        $std->vBCST = $vBCST;
        $std->vST = $vST;
        $std->vFCPST = $vFCPST; //incluso no layout 4.00
        $std->vFCPSTRet = $vFCPSTRet; //incluso no layout 4.00
        $std->vProd = $vProd;
        $std->vFrete = $vFrete;
        $std->vSeg = $vSeg;
        $std->vDesc = $vDesc;
        $std->vII = $vII;
        $std->vIPI = $vIPI;
        $std->vIPIDevol = $vIPIDevol; //incluso no layout 4.00
        $std->vPIS = $vPIS;
        $std->vCOFINS = $vCOFINS;
        $std->vOutro = $vOutro;
        $std->vNF = $vNF;
        $std->vTotTrib = $vTotTrib;

        $elem = $nfe->tagICMSTot($std);

        //FRETE
        //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros; 9=Sem Frete;
        //NEW CONDITION FOR SALE WITHIN THE STATE
        $std = new stdClass();
        if ($nfArray[0]['VENDAPRESENCIAL'] == 'S') {
            $modFrete = '9';
        } else {
            $modFrete = $nfArray[0]['MODFRETE'];
        }

        $std->modFrete = $modFrete;

        $elem = $nfe->tagtransp($std);
        //nfe40        $resp = $nfe->tagtransp($modFrete);
        /*
        0 - Contratação do Frete por conta do Remetente (CIF)
        1 - Contratação do Frete por conta do Destinatário (FOB)
        2 - Contratação do Frete por conta de Terceiros
        3 - Transporte Próprio por conta do Remetente
        4 - Transporte Próprio por conta do Destinatário
        9 - Sem Ocorrência de Transporte
        */
        if ($modFrete != 9) {
            switch ($modFrete) {
                case '0': // emitente/remetente
                case '3': // proprio - emitente/remetente
                    if (is_array($transpArray)) {

                        /* ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024
                    //teste transportador
                    if (($transpArray[0]['CNPJCPF'] == "") or ($transpArray[0]['CNPJCPF'] == "0") OR
                        ($transpArray[0]['CNPJCPF'] == null)){
                        $result = $this->trataErro(995, 'CNPJ do transportador não localizado!', "", "");
                        $this->arrayErro = $result;
                        $this->arrayErro['pessoa'] = $transpArray[0]['CLIENTE'];
                        return $this->arrayErro;
                    }*/

                        if ($transpArray[0]['PESSOA'] == "J"):
                            $CNPJ = str_pad($transpArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                            $CPF = '';
                        else:
                            $CNPJ = '';
                            $CPF = str_pad($transpArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                        endif;
                        $xNome = $transpArray[0]['NOME'];
                        if ($transpArray[0]['INSCESTRG'] != ""):
                            $IE = $transpArray[0]['INSCESTRG'];
                        endif;
                        $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO'] . ", " . $transpArray[0]['NUMERO'] . " - " . $transpArray[0]['COMPLEMENTO'] . " - " . $transpArray[0]['BAIRRO']);
                        $xMun = $transpArray[0]['CIDADE'];
                        $UF = $transpArray[0]['UF'];
                        //nfe40                    $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    } else {
                        $CNPJ = str_pad($filialArray[0]['CNPJ'], 14, "0", STR_PAD_LEFT);
                        $CPF = '';
                        $xNome = $filialArray[0]['NOMEEMPRESA'];

                        if ($filialArray[0]["INSCESTADUAL"] != "") {
                            $IE = $filialArray[0]["INSCESTADUAL"];
                        }

                        $xEnder = $this->removeAcentos($filialArray[0]['ENDERECO'] . ", " . $filialArray[0]['NUMERO'] . " - " . $filialArray[0]['COMPLEMENTO'] . " - " . $filialArray[0]['BAIRRO']);
                        $xMun = $filialArray[0]['CIDADE'];
                        $UF = $filialArray[0]['UF'];
                    }
                    //nfe40                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    break;
                case '1': // destinatário
                case '4': // emitente/remetente

                    /* ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024
                    //teste transportador
                    if (($transpArray[0]['CNPJCPF'] == "") or ($transpArray[0]['CNPJCPF'] == "0") OR
                        ($transpArray[0]['CNPJCPF'] == null)){
                        $result = $this->trataErro(995, 'CNPJ do transportador não localizado!', "", "");
                        $this->arrayErro = $result;
                        $this->arrayErro['pessoa'] = $transpArray[0]['CLIENTE'];
                        return $this->arrayErro;
                    }
                    */


                    if ($nfArray[0]['TRANSPORTADOR'] > 0):
                        if ($transpArray[0]['PESSOA'] == "J"):
                            $CNPJ = str_pad($transpArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                            $CPF = '';
                        else:
                            $CNPJ = '';
                            $CPF = str_pad($transpArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                        endif;
                        $xNome = $transpArray[0]['NOME'];
                        if ($transpArray[0]['INSCESTRG'] != ""):
                            $IE = $transpArray[0]['INSCESTRG'];
                        endif;
                        $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO'] . " " . $transpArray[0]['NUMERO'] . "-" . $transpArray[0]['COMPLEMENTO'] . "-" . $transpArray[0]['BAIRRO']);
                        $xMun = $transpArray[0]['CIDADE'];
                        $UF = $transpArray[0]['UF'];
                    else:
                        if ($pessoaDestArray[0]['PESSOA'] == "J"):
                            $CNPJ = str_pad($pessoaDestArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                            $CPF = '';
                        else:
                            $CNPJ = '';
                            $CPF = str_pad($pessoaDestArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                        endif;
                        $xNome = $pessoaDestArray[0]['NOME'];
                        if ($pessoaDestArray[0]['INSCESTRG'] != ""):
                            $IE = $pessoaDestArray[0]['INSCESTRG'];
                        endif;
                        $xEnder = $this->removeAcentos($pessoaDestArray[0]['ENDERECO'] . ", " . $pessoaDestArray[0]['NUMERO'] . " - " . $pessoaDestArray[0]['COMPLEMENTO'] . " - " . $pessoaDestArray[0]['BAIRRO']);
                        $xMun = $pessoaDestArray[0]['CIDADE'];
                        $UF = $pessoaDestArray[0]['UF'];
                    endif;
                    //nfe40                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    break;
                case '2': // terceiros

                    /* ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024
                    //teste transportador
                    if (($transpArray[0]['CNPJCPF'] == "") or ($transpArray[0]['CNPJCPF'] == "0") OR
                        ($transpArray[0]['CNPJCPF'] == null)){
                        $result = $this->trataErro(995, 'CNPJ do transportador não localizado!', "", "");
                        $this->arrayErro = $result;
                        $this->arrayErro['pessoa'] = $transpArray[0]['CLIENTE'];
                        return $this->arrayErro;
                    }*/

                    if (is_array($transpArray)):
                        if ($transpArray[0]['PESSOA'] == "J"):
                            $CNPJ = str_pad($transpArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                            $CPF = '';
                        else:
                            $CNPJ = '';
                            $CPF = str_pad($transpArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                        endif;
                        $xNome = $transpArray[0]['NOME'];
                        if ($transpArray[0]['INSCESTRG'] != ""):
                            $IE = $transpArray[0]['INSCESTRG'];
                        endif;
                        $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO'] . ", " . $transpArray[0]['NUMERO'] . " - " . $transpArray[0]['COMPLEMENTO'] . " - " . $transpArray[0]['BAIRRO']);
                        $xMun = $transpArray[0]['CIDADE'];
                        $UF = $transpArray[0]['UF'];
                    //nfe40  $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    endif;
                    break;
            } //switch
            $std = new stdClass();
            $std->xNome = $xNome;
            $std->IE = $IE;
            $std->xEnder = $xEnder;
            $std->xMun = $xMun;
            $std->UF = $UF;
            $std->CNPJ = $CNPJ; //só pode haver um ou CNPJ ou CPF, se um deles é especificado o outro deverá ser null
            $std->CPF = $CPF;

            $elem = $nfe->tagtransporta($std);


            $qVol = $nfArray[0]["VOLUME"]; //Quantidade de volumes transportados
            $esp = $this->removeAcentos($nfArray[0]["VOLESPECIE"]); //Espécie dos volumes transportados
            $marca = $this->removeAcentos($nfArray[0]["VOLMARCA"]); //Marca dos volumes transportados
            $nVol = $nfArray[0]["VOLUME"]; //Numeração dos volume
            $pesoL = intval($nfArray[0]["VOLPESOLIQ"]); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
            $pesoB = intval($nfArray[0]["VOLPESOBRUTO"]); //...se colocar Float não vai passar na expressão regular do Schema. =\
            $aLacres = '';
            //nfe40        $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);

            $std = new stdClass();
            $std->item = 1; //indicativo do numero do volume  *****verificar
            $std->qVol = $qVol;
            $std->esp = $esp;
            $std->marca = $marca;
            $std->nVol = $nVol;
            $std->pesoL = $pesoL;
            $std->pesoB = $pesoB;

            $elem = $nfe->tagvol($std);
        }

        $financeiro = $financeiro ?? [];

        if (count($financeiro) > 0) {
            $detFinanceiro = true;
        } else {
            $detFinanceiro = false;
        }

        for ($i = 0; $i < count($financeiro); $i++) {
            if ($financeiro[$i]['VENCIMENTO'] < date("Y-m-d")) {
                $financeiro[$i]['VENCIMENTO'] = date("Y-m-d");
            }
        }

        for ($i = 0; $i < count($financeiro); $i++) {
            if ($financeiro[$i]['VENCIMENTO'] < date("Y-m-d")) {
                $detFinanceiro = false;
            }
        }

        //if(count($financeiro) > 0) {
        if ($detFinanceiro) {



            /*### function tagfat($std):DOMElement
        Node com os dados da fatura

        | Parametro | Tipo | Descrição |
        | :--- | :---: | :--- |
        | $std | stcClass | contêm os dados dos campos, nomeados conforme manual |

        $std = new stdClass();
        $std->nFat = '1233';
        $std->vOrig = 1254.22;
        $std->vDesc = null;
        $std->vLiq = 1254.22;
        */

            $std = new stdClass();
            $std->nFat = $nNF;
            $std->vOrig = $vNF;
            $std->vDesc = null;
            $std->vLiq = $vNF;
            $elem = $nfe->tagfat($std);

            /*### function tagdup($std):DOMElement
        Node de informações das duplicatas

        | Parametro | Tipo | Descrição |
        | :--- | :---: | :--- |
        | $std | stcClass | contêm os dados dos campos, nomeados conforme manual |
        
        $std = new stdClass();
        $std->nDup = '1233-1';
        $std->dVenc = '2017-08-22';
        $std->vDup = 1254.22;

        $elem = $nfe->tagdup($std);

        */

            // $vNF = 0;
            // for ($i = 0; $i<count($financeiro); $i++){
            //     $std = new stdClass();
            //     $std->nDup = str_pad($financeiro[$i]['PARCELA'], 3, "0", STR_PAD_LEFT);
            //     //$std->nDup = '00'.$financeiro[$i]['PARCELA']; //Código da Duplicata
            //     $std->dVenc = $financeiro[$i]['VENCIMENTO']; //Vencimento
            //     $std->vDup = $financeiro[$i]['VALOR']; // Valor
            //     $vNF += $financeiro[$i]['VALOR'];
            //     $elem = $nfe->tagdup($std);        
            // }



            
            /*
                Nota Técnica 2025.001 v.1.00 - Publicada em 25/03/2025

                *02.6 Dados de Cobrança: Novas Regras de Validação

                Melhorado o controle sobre os dados de Cobrança (Grupo de Parcelas, id:”Y07”, tag:”dup”), não
                permitindo seu preenchimento em casos de pagamento à vista (indPag=0) e limitando a Data de
                Vencimento a um máximo de 10 anos a partir da data atual.
            */

            if($indPag !== "0"){ // PAGAMENTO A VISTA NAO GERA nDup
                if ($finNFe != 2) { // nfe comnplementar
                    if ($finNFe == 4) { // devolução
                        $std = new stdClass();
                        $std->nDup = '001';
                        //$std->nDup = '00'.$financeiro[$i]['PARCELA']; //Código da Duplicata
                        $std->dVenc = substr($dhEmi, 0, 10); //Vencimento
                        $std->vDup = $vNF; // Valor
                        $elem = $nfe->tagdup($std);
                    } else {
                        $vNF = 0;
                        for ($i = 0; $i < count($financeiro); $i++) {
                            $std = new stdClass();
                            $std->nDup = str_pad($financeiro[$i]['PARCELA'], 3, "0", STR_PAD_LEFT);
                            //$std->nDup = '00'.$financeiro[$i]['PARCELA']; //Código da Duplicata
                            $std->dVenc = $financeiro[$i]['VENCIMENTO']; //Vencimento
                            $std->vDup = $financeiro[$i]['VALOR']; // Valor
                            $vNF += $financeiro[$i]['VALOR'];
                            $elem = $nfe->tagdup($std);
                        }
                    }
                }
            }
            /*### function tagpag($std):DOMElement
        Node referente as formas de pagamento **OBRIGATÓRIO para NFCe a partir do layout 3.10**
        e também **obrigatório para NFe (modelo 55)** a partir do layout 4.00

        | Parametro | Tipo | Descrição |
        | :--- | :---: | :--- |
        | $std | stcClass | contêm os dados dos campos, nomeados conforme manual |
        */

            $std = new stdClass();
            $std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

            $elem = $nfe->tagpag($std);

            $std = new stdClass();


            // $std = new stdClass();


            if (($nfArray[0]['FINALIDADEEMISSAO'] == 4) or //devolução
                ($nfArray[0]['FINALIDADEEMISSAO'] == 2) or // complementar
                ($nfArray[0]['FINALIDADEEMISSAO'] == 10)
            ) {
                $std->tPag = '90';
                $std->vPag = 0; //Obs: deve ser informado o valor pago pelo cliente            
            } else {
                for ($i = 0; $i < count($financeiroAgrupado); $i++) {
                    $std = new stdClass();
                    if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'D') {
                        $std->tPag = '01'; //dinheiro
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '0'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'E') {
                        $std->tPag = '02'; //cheque
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'K') {
                        $std->tPag = '03'; //cartao de crédito
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                        $std->tpIntegra = '2';
                        $std->tBand = '99';
                    } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'P') {
                        $std->tPag = '17'; //pix
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'A') {
                        $std->tPag = '18'; //transferencia bancaria
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'C') {
                        $std->tPag = '04'; //cartao de debito
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                        $std->tpIntegra = '2';
                        $std->tBand = '99';
                    } else {
                        $std->tPag = '15'; //boleto
                        $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                        $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    }
                }

                /*
          $std->tPag = '15';
          $std->vPag = $vNF; //Obs: deve ser informado o valor pago pelo cliente
          $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo
        */
            }
            $elem = $nfe->tagdetPag($std);
        } else {

            $std = new stdClass();
            $std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

            $elem = $nfe->tagpag($std);

            $std = new stdClass();

            if ($nfArray[0]['FINALIDADEEMISSAO'] = 4) {
                $std->tPag = '90';
                $std->vPag = 0; //Obs: deve ser informado o valor pago pelo cliente            
            } else {
                $std->tPag = '15';
                $std->vPag = $vNF; //Obs: deve ser informado o valor pago pelo cliente
                $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo
            }
            $elem = $nfe->tagdetPag($std);
        }


        // Calculo de carga tributária similar ao IBPT - Lei 12.741/12
        $federal = number_format($vII + $vIPI + $vIOF + $vPIS + $vCOFINS, 2, ',', '.');
        $estadual = number_format($vICMS + $vST, 2, ',', '.');
        $municipal = number_format($vISS, 2, ',', '.');
        $totalT = number_format($federal + $estadual + $municipal, 2, ',', '.');
        $textoIBPT = "Valor Aprox. Tributos R$ {$totalT} - {$federal} Federal, {$estadual} Estadual e {$municipal} Municipal.";


        //Informações Adicionais
        //$infAdFisco = "SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
        $infAdFisco = "";
        $infCpl = $nfArray[0]['OBS'] . ' ';
        $infCpl .= $textoIBPT;
        $std = new stdClass();


        //Mensagem informacao complementar do regime
        $sql = "SELECT MSG_INFORMACAO_COMPLEMENTAR FROM AMB_EMPRESA WHERE EMPRESA = :empresa";
        $this->banco = new c_banco_pdo();
        $this->banco->prepare($sql);
        $this->banco->bindValue(":empresa", $this->m_empresaid);
        $this->banco->execute();
        $msg_complementar = $this->banco->fetch();
        //FIM MSG

        $std->infCpl = $infCpl . ' ' . $msg_complementar["MSG_INFORMACAO_COMPLEMENTAR"] . ' ';
        $std->infAdFisco = '';

        $elem = $nfe->taginfAdic($std);

        //        function taginfRespTec($std):DOMElement
        //        Node da informação referentes ao Responsável Técnico NT 2018.005 Esta tag é OPCIONAL mas se for passada todos os campos devem ser passados para a função.

        if ($tpAmb == 1) { // produção

            $std->CNPJ = '05959674000131'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
            $std->xContato = 'Leonardo Ramon Bermudez Alvarez'; //Nome da pessoa a ser contatada
            $std->email = 'leonardobermudez@yahoo.com'; //E-mail da pessoa jurídica a ser contatada
            $std->fone = '41995930181'; //Telefone da pessoa jurídica/física a ser contatada
            $std->CSRT = '7AHVWFN8ZQ6ID4V8YJRSWC8MQ3XRJ5YFYHAU'; //Código de Segurança do Responsável Técnico
            $std->idCSRT = '02'; //Identificador do CSRT

        } else if ($tpAmb == 2) { //homologação
            $std->CNPJ = '05959674000131'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
            $std->xContato = 'Leonardo Ramon Bermudez Alvarez'; //Nome da pessoa a ser contatada
            $std->email = 'leonardobermudez@yahoo.com'; //E-mail da pessoa jurídica a ser contatada
            $std->fone = '41995930181'; //Telefone da pessoa jurídica/física a ser contatada
            $std->CSRT = 'AETWXIADOXYKTWDMFTWHRQ0VZQHTFIR1VF1D'; //Código de Segurança do Responsável Técnico
            $std->idCSRT = '01'; //Identificador do CSRT
        }

        // if (ADMcliente == "tratorvally" or ADMcliente == "bianco") {

        //     $std->CNPJ = '05959674000131'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
        //     $std->xContato = 'Leonardo Ramon Bermudez Alvarez'; //Nome da pessoa a ser contatada
        //     $std->email = 'leonardobermudez@yahoo.com'; //E-mail da pessoa jurídica a ser contatada
        //     $std->fone = '41995930181'; //Telefone da pessoa jurídica/física a ser contatada
        //     //CSRT HOMOLOGACAO lince
        //     //$std->CSRT = 'AETWXIADOXYKTWDMFTWHRQ0VZQHTFIR1VF1D'; //Código de Segurança do Responsável Técnico
        //     //$std->idCSRT = '01'; //Identificador do CSRT
        // } else {

        //     $std->CNPJ = '22886247000190'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
        //     $std->xContato = 'Marcio Sergio da Silva'; //Nome da pessoa a ser contatada
        //     $std->email = 'marcio.sergio@admservice.com.br'; //E-mail da pessoa jurídica a ser contatada
        //     $std->fone = '4120180804'; //Telefone da pessoa jurídica/física a ser contatada
        //     //      $std->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
        //     //      $std->idCSRT = '01'; //Identificador do CSRT
        // }

        $nfe->taginfRespTec($std);


        //*************************************************************
        // tartamento de erro nf-e    
        if (empty($gerarXML)) {
            function trataErro($codErro, $erroSefaz, $erroNf)
            {
                $msg = "Nota não AUTORIZADA <br> Código Mensagem: " . $codErro . ": ";

                $erroNf .= $msg . " - ";
                if (is_array($erroSefaz)) {
                    foreach ($erroSefaz as $err) {
                        $erroNf .= "$err <br>";
                    }
                } else {
                    $erroNf .= $erroSefaz;
                }
                throw new Exception($erroNf);
                exit;
            }
        }

        // validacoes
        if (($cMunDest == "") or ($cMunDest == "0") or ($cMunDest == null)) {
            trataErro('CÓDIGO MUNICIPIO DESTINATÁRIO NÃO CADASTRADO', "", "");
        }

        if (($pessoaDestArray[0]['CNPJCPF'] == "") or ($pessoaDestArray[0]['CNPJCPF'] == "0") or ($pessoaDestArray[0]['CNPJCPF'] == null)) {
            trataErro('CNPJ DESTINATÁRIO NÃO CADASTRADO', "", "");
        }

        if (($pessoaDestArray[0]['CEP'] == "") or ($pessoaDestArray[0]['CEP'] == "0") or
            ($pessoaDestArray[0]['CEP'] == null) or ($pessoaDestArray[0]['CEP'] == "80000000")
        ) {
            trataErro('CEP DESTINATÁRIO NÃO CADASTRADO', "", "");
        }

        try {

            $tpAmb = ADMnfeAmbiente; // 1 - producao / 2 homologacao
            $anomes = date('Ym');
            if ($nfArray[0]['MODELO'] == 55):
                $nfExt = '-nfe.xml';
                $nfProt = '-protNFe.xml';
                $nfExtPdf = '-danfe.pdf';
            else:
                $nfExt = '-nfe.xml';
                $nfProt = '-protNFe.xml';
                $nfExtPdf = '-danfce.pdf';
            endif;

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $slash = '/';
            (stristr($path, $slash)) ? '' : $slash = '\\';
            define('BASE_DIR_ENTRADA', $path . $slash . 'entradas' . $slash . $anomes . $slash . $chave . $nfExt);
            define('BASE_DIR_ASSINADA', $path . $slash . 'assinadas' . $slash . $anomes . $slash . $chave . $nfExt);
            // define( 'BASE_DIR_ENVIADA_APROVADAS', $path.$slash.'enviadas'.$slash.'aprovadas'.$slash.$anomes.$slash.$chave.$nfProt); 
            define('BASE_DIR_ENVIADA_APROVADAS', $path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash . $chave . $nfExt);
            define('BASE_DIR_TEMP', $path . $slash . 'temporarias' . $slash . $anomes . $slash);
            define('BASE_DIR_PDF', $path . $slash . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);
            define('BASE_HTTP_PDF', ADMhttpCliente . $slash . 'nfe' . $slash . $this->m_empresaid . $slash . ADMambDesc . $slash . 'pdf' . $slash . $anomes . $slash . $chave . $nfExtPdf);

            // variável em JSON com os dados que o framework vai precisar para os próximos passos.
            if ($this->m_empresaid == 1) {
                $confPar = explode("|", ADMnfeConfig01);
            } else
        if ($this->m_empresaid == 2) {
                $confPar = explode("|", ADMnfeConfig02);
            } else
        if ($this->m_empresaid == 3) {
                $confPar = explode("|", ADMnfeConfig03);
            }
            $config = [
                "atualizacao" => $confPar[0],
                "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
                "razaosocial" => $confPar[2],
                "siglaUF" => $confPar[3],
                "cnpj" => $confPar[4],
                "schemes" => $confPar[5], //    PL_009_V4 - 4.0,PL_008i2 - 3.10
                "versao" => $confPar[6],
                "tokenIBPT" => $confPar[7]
            ];

            $configJson = json_encode($config);

            // leitura do certirficado digital 
            if ($this->m_empresaid == 1) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert01);
            } else
        if ($this->m_empresaid == 2) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert02);
            } else
        if ($this->m_empresaid == 3) {
                $certificadoDigital = file_get_contents(BASE_DIR_CERT . ADMnfeCert03);
            }

            //monta a NFe e retorna na tela
            //===============================================
            // $nfeTools->zGravaFile ( verificar gravação ) entradas/{$chave}-nfe.xml ***********
            // O conteúdo do XML fica armazenado na variável $xml
            $erroNf = $chave . "<br>";
            $erro = 'grava NF line: 2217 - ' . $path . $slash . 'nf' . $slash . $anomes . $slash . $chave . $nfProt . '<br>';
            $erro .= ' ERRO: ' . file_put_contents($path . $slash . 'nf' . $slash . $anomes . $slash . $chave . $nfProt, $xml) . '<br>';
            $xml = $nfe->getXML();

            //    file_put_contents($path.$slash.'nf'.$slash.$anomes.$slash.$gerarXML.$slash.$chave.$nfProt,$xml);
           // exit;
            //===============================================
            // ASSINA E GRAVA
            // se modelo 65 inclui qrcode
            //===============================================
            // /assinadas/{$chave}-nfe.xml
            try {
                if ($this->m_empresaid == 1) {
                    $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha01));
                } else
            if ($this->m_empresaid == 2) {
                    $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha02));
                } else
            if ($this->m_empresaid == 3) {
                    $tools = new NFePHP\NFe\Tools($configJson, NFePHP\Common\Certificate::readPfx($certificadoDigital, ADMnfeSenha03));
                }
                $xmlAssinado = $tools->signNFe($xml); // O conteúdo do XML assinado fica armazenado na variável $xmlAssinado        
            } catch (\Exception $e) {
                trataErro('ASSINA - ', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);

                /* ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024

            //termos de tributos
            $termos = ["PIS", "COFINS", "ICMS", "ST"];
            $resultTerms = $this->containsTerms($e->getMessage(), $termos);

            //se existir erro de tributos
            if($resultTerms){
                $result = $this->trataErro(990, str_replace("\n", "<br/>", $e->getMessage()), 'Erro ao assinar documento', $nfArray[0]["ID"]);
                $this->arrayErro = $result;
                $this->arrayErro['idNotaFiscal'] = $idNf;
                //sai do form para apresentar a msg na tela
                return $this->arrayErro;
            }else{
                $result = $this->trataErro(9999, str_replace("\n", "<br/>", $e->getMessage()), 'Erro ao assinar documento', $nfArray[0]["ID"]);
                $this->arrayErro = $result;
                $this->arrayErro['pessoa'] = $transpArray[0]['CLIENTE'];
                $this->arrayErro['idNotaFiscal'] = $idNf;
                //sai do form para apresentar a msg na tela
                return $this->arrayErro;
            }
            */
            }

            if (!empty($gerarXML)) {
                /*        try {
            $protocolo = $tools->sefazConsultaRecibo($nfArray[0]['NPROT']);          
            $protocol = new NFePHP\NFe\Factories\Protocol();
            $protocol->add($xmlAssinado,$protocolo);
        } catch (Exception $e) {
            trataErro('PROT', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
        }
*/
                $erro = 'ASSINA E GRAVA line: 2244 - ' . $path . $slash . 'enviadas' . $slash . 'temporarias' . $slash . $anomes . $slash . $gerarXML . $slash . $chave . $nfProt . '<br>';
                $erro .= '<br>' . file_put_contents($path . $slash . 'enviadas' . $slash . 'temporarias' . $slash . $anomes . $slash . $gerarXML . $slash . $chave . $nfProt, $xmlProtocolado);
                if (!file_exists($path . $slash . 'enviadas' . $slash . 'temporarias' . $slash . $anomes . $slash . $gerarXML . $slash)) {
                    mkdir($path . $slash . 'enviadas' . $slash . 'temporarias' . $slash . $anomes . $slash . $gerarXML . $slash, 0777, true);
                }
                $aResposta = $path . $slash . 'enviadas' . $slash . 'temporarias' . $slash . $anomes . $slash . $gerarXML;
            } else {
                //===============================================
                // ENVIA NF 
                // Solicita a autorização de uso de Lote de NFe
                // GRAVA pasta temporarias ******************
                // $idLote-enviNFe.xml";
                // $idLote-retEnviNFe.xml";
                //===============================================
                if ($nfArray[0]["SITUACAO"] == 'A') {
                    try {

                        // grava xml pasta nf
                        if (!file_exists($path . $slash . 'nf' . $slash . $anomes . $slash . $gerarXML . $slash)) {
                            mkdir($path . $slash . 'nf' . $slash . $anomes . $slash . $gerarXML . $slash, 0777, true);
                        }
                        file_put_contents($path . $slash . 'nf' . $slash . $anomes . $slash . $gerarXML . $slash . $chave . $nfProt, $xml);


                        // grava xml assinado
                        if (!file_exists($path . $slash . 'assinadas' . $slash . $anomes . $slash)) {
                            mkdir($path . $slash . 'assinadas' . $slash . $anomes . $slash, 0777, true);
                        }
                        file_put_contents(BASE_DIR_ASSINADA, $xmlAssinado);

                        $aResposta = array();

                        $idLote = str_pad($nfArray[0]['NUMERO'], 15, '0', STR_PAD_LEFT); // Identificador do lote
                        $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote, 1);

                        $st = new NFePHP\NFe\Common\Standardize();
                        $std = $st->toStd($resp);
                        if ($std->cStat !== '103' && $std->cStat !== '104') {
                            //erro registrar e voltar
                            $return = [
                                "situacao" => "rejeitada",
                                "codSituacao" => "R",
                                "motivo" => $std->xMotivo,
                                "cstat" => $std->cStat,
                                "cStatus" => $std->cStat,
                                "chave" => $chave,
                                "recibo" => ''
                            ];
                            return $return;
                            //$aResposta['cStatus'] = $std->cStat;
                            //trataErro($std->cStat, $std->xMotivo, '');
                        }

                        if ($std->cStat == '103') {
                            $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota


                            // grava recibo
                            if (!file_exists($path . $slash . 'enviadas' . $slash . 'recibos' . $slash . $anomes . $slash . $gerarXML . $slash)) {
                                mkdir($path . $slash . 'enviadas' . $slash . 'recibos' . $slash . $anomes . $slash . $gerarXML . $slash, 0777, true);
                            }
                            $erro = 'RECIBO line: 2272 - ' . $path . $slash . 'enviadas' . $slash . 'recibos' . $slash . $anomes . $slash . $gerarXML . $slash . $chave . '-rec' . '<br>';
                            $erro .= 'ERRO: ' . file_put_contents($path . $slash . 'enviadas' . $slash . 'recibos' . $slash . $anomes . $slash . $gerarXML . $slash . $chave . '-rec', $recibo);
                        }
                        //$std->cStat !== teste 
                        // $return = [
                        //     "situacao" => "lote em processamento",
                        //     "codSituacao" => "P",
                        //     "motivo" => $std->xsMotivo,
                        //     "cStatus" => $std->cStat,
                        //     "chave"=> $chave,
                        //     "recibo" => $recibo
                        // ];
                        // return $return;

                    } catch (\Exception $e) {
                        $return = [
                            "situacao" => "rejeitada",
                            "codSituacao" => "R",
                            "motivo" => $std->xMotivo . " - " . str_replace("\n", "<br/>", $e->getMessage()),
                            "cstat" => $std->cStat,
                            "cStatus" => $std->cStat,
                            "chave" => $chave,
                            "recibo" => ''
                        ];
                        return $return;
                        //trataErro('Envia - '.$std->cStat, str_replace("\n", "<br/>", $e->getMessage()), $std->xMotivo);
                    }
                } else {
                    $recibo = $nfArray[0]["NUMRECIBO"];
                    $chave = $nfArray[0]["CHNFE"];
                    $xmlAssinado = file_get_contents(BASE_DIR_ASSINADA);
                }

                //===============================================
                // CONSULTA NF PELO NUMERO DO RECIBO
                //===============================================
                if (!empty($recibo)) {


                    try {
                        //consulta número de recibo
                        //$numeroRecibo = número do recíbo do envio do lote
                        //$xmlResp = $tools->sefazConsultaRecibo($recibo);
                        //validação de timeout -  Lote em processamento
                        $i = 0;
                        sleep(5);
                        while ($i <= 5) {
                            $xmlResp = $tools->sefazConsultaRecibo($recibo);
                            $protocolo = $xmlResp;
                            if (isset($xmlResp)) {
                                break;
                            }
                            $i++;
                            sleep(10);
                            //}
                        }
                        //$status = $st->toStd($protocolo);
                        //$cStatConsulta = $status->cStat;

                        //transforma o xml de retorno em um stdClass
                        $st = new NFePHP\NFe\Common\Standardize();
                        $std = $st->toStd($xmlResp);
                        $status = $std;

                        // teste consulta chave
                        // $tools->model('55');

                        // $chave = '52170522555994000145550010000009651275106690';
                        // $chave = $std->protNFe->infProt->chNFe;
                        // $response = $tools->sefazConsultaChave($chave);

                        // //você pode padronizar os dados de retorno atraves da classe abaixo
                        // //de forma a facilitar a extração dos dados do XML
                        // //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
                        // //      quando houver a necessidade de protocolos
                        // $stdCl = new NFePHP\NFe\Common\Standardize($response);
                        // //nesse caso $std irá conter uma representação em stdClass do XML
                        // $std = $stdCl->toStd();
                        // //nesse caso o $arr irá conter uma representação em array do XML
                        // $arr = $stdCl->toArray();
                        // //nesse caso o $json irá conter uma representação em JSON do XML
                        // $json = $stdCl->toJson();            

                        if ($std->cStat == '103') { //lote enviado
                            //Lote ainda não foi precessado pela SEFAZ;
                            $return = [
                                "situacao" => "lote enviado",
                                "codSituacao" => "E",
                                "numeroProtocolo" => $std->protNFe->infProt->nProt,
                                "xmlProtocolo" => $xmlResp,
                                "cStatus" => $std->cStat,
                                "chave" => $chave,
                                "recibo" => $recibo
                            ];
                            return $return;
                        }

                        if ($std->cStat == '105') { //lote em processamento

                            // mount log to add the nota_fiscal_eventos table
                            $log = [
                                "IDNF" => $idNf,
                                "CENTROCUSTO" => $filial,
                                "TIPOEVENTO" => "E",
                                "SEQUENCIA" => 1,
                                "MODELO" => $mod,
                                "SERIE" => $serie,
                                "NUMNFINI" => $nNF,
                                "NUMNFFIM" => $nNF,
                                "JUSTIFICATIVA" => "LOTE EM PROCESSAMENTO ",
                                "NPROT" => $std->protNFe->infProt->nProt,
                                // Update variable coming from $std object when tests are performed
                                "VERAPLIC" => "V_INTERNO",
                                "CSTAT" => $std->cStat,
                                "XML" =>  $xmlResp
                            ];

                            //insert log
                            $objetoNotaFiscal = new c_nota_fiscal();
                            $result = $objetoNotaFiscal->incluiNfEvento($log, null, null, null, null, null, "nota_fiscal", null);

                            $msg_log = null;

                            if ($result !== " ") {
                                $msg_log = "DEV - Erro ao incluir o log na funcao incluiNfEvento()";
                            }


                            //tente novamente mais tarde
                            $return = [
                                "situacao" => "lote em processamento",
                                "codSituacao" => "P",
                                "numeroProtocolo" => $std->protNFe->infProt->nProt,
                                "xmlProtocolo" => $xmlResp,
                                "cStatus" => $std->cStat,
                                "chave" => $chave,
                                "recibo" => $recibo,
                                "msg_log" => $msg_log
                            ];
                            return $return;
                        }

                        if ($std->cStat == '104') { //lote processado (tudo ok)
                            if ($std->protNFe->infProt->cStat == '100') { //Autorizado o uso da NF-e
                                //                    $return = ["situacao"=>"autorizada",
                                //                                "numeroProtocolo"=>$std->protNFe->infProt->nProt,
                                //                                "xmlProtocolo"=>$xmlResp,
                                //                                "cStatus" => $std->protNFe->infProt->cStat,
                                //                                "recibo" => $recibo];

                                $aResposta['cStatus'] = $std->protNFe->infProt->cStat;
                            } elseif (in_array($std->protNFe->infProt->cStat, ["110", "301", "302"])) { //DENEGADAS
                                $return = [
                                    "situacao" => "denegada",
                                    "codSituacao" => "D",
                                    "numeroProtocolo" => $std->protNFe->infProt->nProt,
                                    "motivo" => $std->protNFe->infProt->xMotivo,
                                    "cstat" => $std->protNFe->infProt->cStat,
                                    "cStatus" => $std->protNFe->infProt->cStat,
                                    "xmlProtocolo" => $xmlResp,
                                    "chave" => $chave,
                                    "recibo" => $recibo
                                ];
                                return $return;
                            } elseif (in_array($std->protNFe->infProt->cStat, ["539"])) { //DUPLICADO
                                $return = [
                                    "situacao" => "Duplicidade",
                                    "codSituacao" => "U",
                                    "numeroProtocolo" => $std->protNFe->infProt->nProt,
                                    "motivo" => $std->protNFe->infProt->xMotivo,
                                    "cstat" => $std->protNFe->infProt->cStat,
                                    "cStatus" => $std->protNFe->infProt->cStat,
                                    "xmlProtocolo" => $xmlResp,
                                    "chave" => $chave,
                                    "recibo" => $recibo
                                ];
                                return $return;
                            } else { //não autorizada (rejeição)
                                $return = [
                                    "situacao" => "rejeitada",
                                    "codSituacao" => "R",
                                    "motivo" => $std->protNFe->infProt->xMotivo,
                                    "cstat" => $std->protNFe->infProt->cStat,
                                    "cStatus" => $std->protNFe->infProt->cStat,
                                    "chave" => $chave,
                                    "recibo" => $recibo
                                ];
                                return $return;
                            }
                        } else { //outros erros possíveis
                            $return = [
                                "situacao" => "rejeitada",
                                "codSituacao" => "R",
                                "motivo" => $std->xMotivo,
                                "cstat" => $std->cStat,
                                "cStatus" => $std->cStat,
                                "chave" => $chave,
                                "recibo" => $recibo
                            ];
                            return $return;
                        }
                    } catch (\Exception $e) {
                        $return = [
                            "situacao" => "lote enviado",
                            "motivo" => $std->xMotivo . " - " . str_replace("\n", "<br/>", $e->getMessage()),
                            "numeroProtocolo" => $std->protNFe->infProt->nProt,
                            "xmlProtocolo" => $xmlResp,
                            "cstat" => 103,
                            "cStatus" => 103,
                            "chave" => $chave,
                            "recibo" => $recibo
                        ];
                        return $return;
                        // trataErro('PROT', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
                        //echo str_replace("\n", "<br/>", $e->getMessage());
                    }
                }
                if ($std->cStat == '104') {

                    $aResposta['cStatus'] = $std->protNFe->infProt->cStat;                    

                    preg_match('/<protNFe[^>]*>.*?<\/protNFe>/s', $resp, $matches);

                    if (isset($matches[0])) {
                        $conteudoInfProt = trim($matches[0]);
                        $protocolo = $conteudoInfProt;
                    }
                }


                // consulta protocolo através do recibo
                // 20210629 $protocolo = $tools->sefazConsultaRecibo($recibo);
                //caso o envio seja recebido com sucesso mover a NFe da pasta
                //das assinadas para a pasta das enviadas
                // Códigos resultado de processamento da solicitação NF-e e NFC-e
                // https://atendimento.tecnospeed.com.br/hc/pt-br/articles/360012426534-C%C3%B3digos-resultado-de-processamento-da-solicita%C3%A7%C3%A3o-NF-e-e-NFC-e
                // https://github.com/nfephp-org/sped-nfe/blob/master/docs/metodos/ConsultaRecibo.md
                //  20210629 $status = $st->toStd($protocolo);
                //  20210629 $cStatConsulta = $status->cStat;


                //ANTIGO FLUXO DE CONSULTA DE RECIBO - NOVO METODO INICIA NA LINHA 2923

                // validação de timeout -  Lote em processamento
                // $i=0;
                // sleep(5);
                // while ($i<=10){
                //     $protocolo = $tools->sefazConsultaRecibo($recibo);
                //     $status = $st->toStd($protocolo);
                //     $cStatConsulta = $status->cStat;
                //     if ($cStatConsulta != 104) {
                //         $i++;
                //         sleep(5);
                //     }else{
                //         $i=99;
                //     }
                // }

                // $cStatProtocolo = $status->protNFe->infProt->cStat;
                // $aResposta['cStatus'] = $cStatConsulta;
                // switch ($cStatConsulta) {
                //     case '100':
                //         break;
                //     case '105': // Lote em processamento
                //         trataErro($cStatConsulta, $xMotivo,  $erroNf);
                //         break;
                //     case '104':
                //         $aResposta['cStatus'] = $cStatProtocolo;
                //         if ($cStatProtocolo != '100'):
                //             $xMotivo = $status->protNFe->infProt->xMotivo;
                //             trataErro('104 - '.$cStatProtocolo, $xMotivo,  $erroNf);
                //         endif;
                //         break;
                //    case '110': // Uso Denegado
                //         trataErro($cStatConsulta, $xMotivo,  $erroNf);
                //         break;
                //     default :        
                //         $xMotivo = $status->xMotivo;
                //         trataErro('DEFAULT - '.$cStatConsutla, $xMotivo,  $erroNf);
                // }   


                // add protocolo e grava APROVADAS
                try {
                    // ADMV4.0
                    // $protocol = new NFePHP\NFe\Factories\Protocol();
                    // $xmlProtocolado = $protocol->add($xmlAssinado,$protocolo);
                    $xmlProtocolado = NFePHP\NFe\Complements::toAuthorize($xmlAssinado, $protocolo);
                    $saveFile = true;
                    $pathNFefile = BASE_DIR_ASSINADA;
                    $pathProtfile = BASE_DIR_TEMP . $idLote . "-retEnviNFe.xml";

                    //20230301
                    // if (!file_exists($path.$slash.'assinadas'.$slash.$anomes.$slash)) {
                    //   mkdir($path.$slash.'assinadas'.$slash.$anomes.$slash, 0777, true);}  
                    // // file_put_contents(BASE_DIR_ASSINADA , $xmlAssinado);        
                    // file_put_contents($path.$slash.'assinadas'.$slash.$anomes.$slash.$chave.$nfExt, $xmlAssinado);

                    if (!file_exists($path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash)) {
                        mkdir($path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash, 0777, true);
                    }
                    // file_put_contents(BASE_DIR_ENVIADA_APROVADAS , $xmlProtocolado);
                    $erro = 'ENVIADAS - APROVADAS line: 2319 - ' . $path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash . $chave . $nfExt . '<br>';
                    $erro .= 'ERRO: ' . file_put_contents($path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash . $chave . $nfExt, $xmlProtocolado);
                } catch (\Exception $e) {
                    //aqui você trata possíveis exceptions ao adicionar protocolo
                    trataErro('PROT', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
                }

                //echo $docProt;
                // DANFE GRAVA PDF E IMPRIME
                try {
                    $erro = 'PDF line: 2334 - ' . $pdfDanfe . '<br>';
                    $pathLogo = ADMimg . '/logo0' . $this->m_empresaid . '.jpg';
                    $logo = 'data://text/plain;base64,' . base64_encode(file_get_contents($pathLogo));
                    if (!file_exists($path . $slash . 'pdf' . $slash . $anomes . $slash)) {
                        mkdir($path . $slash . 'pdf' . $slash . $anomes . $slash, 0777, true);
                    }
                    $pdfDanfe = BASE_DIR_PDF;
                    //$pdfDanfe = $path.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf;

                    if ($nfArray[0]['MODELO'] == 55):
                        $danfe = new NFePHP\DA\NFe\Danfe($xmlProtocolado, 'P', 'A4', $pathLogo, 'I', '');
                        $erro = 'PDF DANFE line: 2343 - ' . $pdfDanfe . '<br>';
                        $id = $danfe->montaDANFE();
                        $erro .= 'ERRO: ' . $danfe->printDocument($pdfDanfe, 'F'); //Salva o PDF na pasta
                    else:
                        $danfce = new NFePHP\DA\NFe\Danfce($xmlAssinada, $pathLogo, 2);

                        $ecoNFCe = false; //false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
                        $id = $danfce->montaDANFCE($ecoNFCe);

                        $erro = 'PDF line: 2352 - ' . $pdfDanfe . '<br>';
                        $erro .= 'ERRO: ' . $danfce->printDocument('pdf', $pdfDanfe, 'F'); //Salva na pasta pdf
                    endif;

                    $aResposta['cDanfe'] = BASE_HTTP_PDF;
                } catch (InvalidArgumentException $e) {
                    trataErro('PDF', str_replace("\n", "<br/>", $e->getMessage()), $erroNf);
                }

                // altera dados danfe na est_nota_fiscal
                // inclui tabela RECIBO *****************
                if (($aResposta['cStatus'] == '100') or ($aResposta['cStatus'] == '105')):
                    $nfOBJ->setPathDanfe($aResposta['cDanfe']);
                    $nfOBJ->setSituacao('B');
                    $nfOBJ->setChNFe($std->protNFe->infProt->chNFe);
                    $nfOBJ->setDhRecbto($std->dhRecbto);
                    $nfOBJ->setNProt($std->protNFe->infProt->nProt);
                    $nfOBJ->setDigVal($std->protNFe->infProt->digVal);
                    $nfOBJ->setVerAplic($std->verAplic);
                    // $nfOBJ->setNumRecibo($recibo);
                    $nfOBJ->alteraNfPath($conn);

                    // envia email
                    if (($nfArray[0]['MODELO'] == 55) and ($aResposta['cStatus'] == '100')):
                        $erro = 'EMAIL line: 2367<br>' . $this->enviaEmailDANFE(
                            $nfArray[0]['MODELO'],
                            $pessoaDestArray[0]['EMAILNFE'],
                            $pessoaDestArray[0]['EMAIL'],
                            $std->protNFe->infProt->chNFe,
                            $dhEmi,
                            $cNF,
                            $serie,
                            $xNome
                        );
                    endif;
                endif;
            }
            return $aResposta;

            /*
         *  pedencias
         *  - FCP
            - TRANSPORTADORA
            - TROCO – pg 1205
            - INDICADOR DE PAGAMENTO
         * https://github.com/nfephp-org/sped-nfe
         */
        } catch (Exception $e) {
            throw new Exception($erro . '<br>' . $e->getMessage());
        }
    } //geraXML

    //============================================================ //
    // Função de comparação para ordenar pelo valor
    public static function compararValores($a, $b)
    {
        if ($a['PARCELA'] == $b['PARCELA']) {
            return 0;
        }
        return ($a['PARCELA'] < $b['PARCELA']) ? -1 : 1;
    }

    function recordHeader($verAplic = null, $cStat = null, $xMotivo = null, $dhResp = null, $ultNSU = null, $maxNSU = null)
    {
        // Criar um objeto DateTime a partir da string
        $dataFormat = new DateTime($dhResp);
        $dataHoraFormatada = $dataFormat->format('Y-m-d H:i:s');

        // Supondo que $proxima contenha a data da próxima consulta
        $proximaConsulta = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($dataHoraFormatada)));

        $sql  = "INSERT INTO EST_MANIFESTO (";
        $sql .= "DATAHORARESPOSTA, ULTNSU, MAXNSU, VERAPLIC, CSTAT, XMOTIVO, CENTROCUSTO ,PROXIMACONSULTA, USREMISSAO)";
        $sql .= "VALUES ('";
        $sql .= $dataHoraFormatada . "', '" . $ultNSU . "', '" . $maxNSU . "', '" . $verAplic . "', '" . $cStat . "', '";
        $sql .= $xMotivo . "', '" . $this->m_empresacentrocusto . "', '" . $proximaConsulta . "', " . $this->m_userid . ");";

        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->result;
    }

    function insertPersonManifest($xml, $tipo = null)
    {

        $objConta = new c_conta();

        if ($tipo == 'resNFe') {
            $objConta->setNome($xml->xNome);
            $objConta->setCnpjCpf($xml->CNPJ);
            $objConta->setNomeReduzido($xml->xNome);
        } else {
            $objConta->setNome($xml->NFe->infNFe->emit->xNome);
            $objConta->setCnpjCpf($xml->NFe->infNFe->emit->CNPJ);
            $objConta->setNomeReduzido($xml->NFe->infNFe->emit->xFant);
            $objConta->setIeRg($xml->NFe->infNFe->emit->IE);
        }

        $objConta->setPessoa('J');
        $objConta->setRepresentante(0);
        $objConta->setRegimeEspecialSTMTAliq(0.00);
        $objConta->setRegimeEspecialSTAliq(0.00);
        $objConta->setCentroCusto($this->m_empresacentrocusto);

        $result = $objConta->incluiConta('manifesto');

        return $result;
    }
    /* FALTA INSERIR A IE NO  insertPersonManifest*/

    function savedZip($docs, $name)
    {
        // Cria um objeto ZipArchive para teste
        // $nomeDoArquivoZIP = 'arquivo_teste.zip';
        // $zip = new ZipArchive();

        // // Cria o arquivo ZIP
        // if ($zip->open($nomeDoArquivoZIP, ZipArchive::CREATE) === TRUE) {
        //     // Adiciona um arquivo de texto de exemplo ao ZIP
        //     $textoDeExemplo = "Este é um arquivo de exemplo para teste.";
        //     $zip->addFromString('exemplo.txt', $textoDeExemplo);

        //     // Fecha o arquivo ZIP
        //     $zip->close();

        //     // Obtém os dados do arquivo ZIP no formato de string
        //     $zipContents = file_get_contents($nomeDoArquivoZIP);
        // }else{
        //     echo 'erro';
        // }

        //$docs = $zipContents;

        // monta dir files
        $slash = '/';
        $caminho = BASE_DIR_NFE_AMB . $slash . 'sefazZip' . $slash;

        if (!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }

        $string_name = preg_replace('/[^0-9T]/', '', $name);
        $caminhoCompleto = $caminho . $string_name . '.zip';

        // salva o conteúdo no arquivo ZIP
        if (file_put_contents($caminhoCompleto, $docs) !== false) {
            return true;
        } else {
            $error = error_get_last();
            return false;
        }
    }

    // Função para consultar um CNPJ via API
    function consultarCNPJ($cnpj)
    {
        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para permitir conexões HTTPS sem certificado válido

        $response = curl_exec($ch);

        if ($response === false) {
            return "Erro na consulta: " . curl_error($ch);
        } else {
            $data = json_decode($response, true);

            if ($data && isset($data['status']) && $data['status'] == 'OK') {
                return $data; // Retorna os dados do CNPJ
            } else {
                return  "CNPJ não encontrado ou erro na consulta.";
            }
        }

        curl_close($ch);
    }
} //class
$xml = new p_nfe_40();
