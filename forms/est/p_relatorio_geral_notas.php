<?php

/**
 * @package   astecv3
 * @name      p_relatorio_geral_notass
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author   Jhon Kenedy <jhon.kenedy@hotmail.com>
 * @date      21/12/2022
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../bib/c_date.php");

//Class p_produtos_fiscal
class p_relatorio_geral_notas extends c_nota_fiscal
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_slash = '/';
    public $smarty = NULL;


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        define('BASE_DIR_NFE_AMB', ADMnfe . $this->m_slash . $this->m_empresaid . $this->m_slash . ADMambDesc);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';

        if ($parmPost['letra'] !== null) {
            $this->m_letra = $parmPost['letra'];
        } else {
            $this->m_letra = $parmGet['letra'];
        }
        //$this->m_letra = (isset($parmPost['letra']) ? $parmPost['letra'] : $parmGet['letra'] ? $parmGet['letra'] : '');

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Consolidação Produtos Período");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6, 7, 8  ]");
        $this->smarty->assign('disableSort', "[ 8 ]");
        $this->smarty->assign('numLine', "50");
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        $this->mostraRelatorioGeral('');
    } // fim controle

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraRelatorioGeral()
    {

        $email = false;

        $par = explode("|", $this->m_letra);
        $path = BASE_DIR_NFE_AMB;
        $dataIni = $par[3];
        $dataFim = $par[4];

        $newLetra = $par[0] . '|' . $par[1] . '||' . $par[3] . '|' . $par[4];

        //busca notas do periodo pesquisado
        $notasXML = $this->select_nota_fiscal_letra($newLetra);

        //busca parametros est
        $paramEst = $this->buscaEstParametros($par[0]);

        //select para selecionar ultima nf do mes anterior
        $firstNum = $this->selectNotaSequencia($dataIni, $paramEst[0]["CENTROCUSTO"], $paramEst[0]["SERIE"], $paramEst[0]["MODELO"], 1);

        //select para selecionar ultima nf do mes anterior com parametro do mes seguinte
        $data = c_date::diaSeguinte($dataFim);
        $lastNum = $this->selectNotaSequencia($data, $paramEst[0]["CENTROCUSTO"], $paramEst[0]["SERIE"], $paramEst[0]["MODELO"], 1);

        //monta array com notas filtradas com parametros
        $notasValidasPeriodo = [];
        $cont = 0;
        foreach ($notasXML as $chave => $valor) {
            if (($valor['SERIE'] == $paramEst[0]["SERIE"])
                and ($valor['MODELO'] == $paramEst[0]["MODELO"])
                and ($valor['CENTROCUSTO'] == $paramEst[0]["CENTROCUSTO"])
                and ($valor['TIPO'] == 1)
            ) {
                $notasValidasPeriodo[$cont] = $valor['NUMERO'];
                $cont++;
            }
        }

        //monta desc do diretorio
        $param = explode('|', $this->m_letra);
        if ($param['4'] !== '') {
            $paramExp = explode('/', $param['4']);
            $anomes = $paramExp[2] . $paramExp[1];
        } else {
            $anomes = date('Ym');
        }


        /*########### MONTA PATH DIRETORIO CARTA CORRECAO xmls e realiza leitura ###########*/
        $dirCartaCorrecao = $path . $this->m_slash . 'cartacorrecao' . $this->m_slash . $anomes . $this->m_slash;
        if (file_exists($dirCartaCorrecao)) {
            $l_xml = dir($dirCartaCorrecao);
            $carta_correcao = [];
            $cont = 0;

            //while que percorre classe do diretorio de xml
            while (false !== ($strXml = $l_xml->read())) {

                $strXmlEvent = $path . $this->m_slash . 'cartacorrecao' . $this->m_slash . $anomes . $this->m_slash . $strXml;
                $xml = simplexml_load_file($strXmlEvent);

                if ($xml !== false) {

                    $nNF = ltrim(substr($xml->evento->infEvento->chNFe, 25, 9), '0');
                    $chaveNfe = strval($xml->retEvento->infEvento->chNFe);
                    $mes = substr($xml->evento->infEvento->chNFe, 4, 2);
                    $dataEvento = strval($xml->evento->infEvento->dhEvento);
                    $xCorrecao = strval($xml->evento->infEvento->detEvento->xCorrecao);

                    $carta_correcao[$cont] = array(
                        'NUM_NF' => $nNF,
                        'CHAVE_NFE' => $chaveNfe,
                        'MES_EMISSAO_NF' => $mes,
                        'DATA_EVENT' => $dataEvento,
                        'DESC_EVENTO' => $xCorrecao,
                    );

                    $cont++;
                }
            } //FIM while

            $l_xml->close(); //fecha class do diretorio do xml
            sort($carta_correcao);
        } else {
            $carta_correcao = false;
        }
        /*########### FIM CARTA CORRECAO ########### */


        /*########### MONTA PATH DIRETORIO ENVIADAS xmls e realiza leitura ###########*/
        $dirEnviadas = $path . $this->m_slash . 'enviadas' . $this->m_slash . 'aprovadas' . $this->m_slash . $anomes . $this->m_slash;
        if (file_exists($dirEnviadas)) {
            $l_nfe = dir($dirEnviadas);
            $sequencias_nf_dir = [];
            $arrayDirEnviadasNumeros = [];
            $cont = 0;

            //while que percorre classe do diretorio de xml
            while (false !== ($strXml = $l_nfe->read())) {

                $strXmlUni = $path . $this->m_slash . 'enviadas' . $this->m_slash . 'aprovadas' . $this->m_slash . $anomes . $this->m_slash . $strXml;
                $xml = simplexml_load_file($strXmlUni);

                if ($xml !== false) {
                    $sequencias_nf_dir[$cont] = array(
                        'NUMERO' => intval($xml->NFe->infNFe->ide->nNF),
                        'MODELO' => intval($xml->NFe->infNFe->ide->mod),
                        'SERIE' => intval($xml->NFe->infNFe->ide->serie),
                        'NAT_OPERACAO' => strval($xml->NFe->infNFe->ide->natOp),
                        'DATA_EMISSAO' => strval($xml->NFe->infNFe->ide->dhEmi),
                        'EMITENTE' => strval($xml->NFe->infNFe->emit->xFant),
                        'CHAVE_ACESSO' => $xml->protNFe->infProt->chNFe !== 0 ? strval($xml->protNFe->infProt->chNFe) : '',
                        'DESTINATARIO' => strval($xml->NFe->infNFe->dest->xNome),
                        'DOC_DESTINATARIO' => strval($xml->NFe->infNFe->dest->CPF),
                        'VALOR_NF' => strval($xml->NFe->infNFe->total->ICMSTot->vNF),
                        'PATH_XML' => str_replace("/home/admsis/public_html", "", $strXmlUni),
                        //'PATH_XML' => str_replace("/var/www/html", "" ,$strXmlUni), //local
                    );

                    $arrayDirEnviadasNumeros[$cont] = intval($xml->NFe->infNFe->ide->nNF);

                    $cont++;
                }
            } //FIM while
            $l_nfe->close(); //fecha class do diretorio do xml
            //ordena do menor para o maior
            sort($arrayDirEnviadasNumeros);
        } else {
            //se nao localizar o diretorio ira setar false para o template
            $arrayDirEnviadasNumeros = 0;
        }
        /*########### FIM ENVIADAS ########### */

        //script que identifica quais sequencias faltam
        $sequenciaFaltando = [];
        $cont = 0;
        $min = $firstNum + 1;
        $max = $lastNum;
        foreach (range($min, $max, 1) as $j) {
            //verifica se sequencia existe nas notas validas do periodo
            $localizaNumero = array_search($j, $notasValidasPeriodo);
            if ($localizaNumero === false) {
                $sequenciaFaltando[$cont] = $j;
                $cont++;
            }
        }

        //monta array com notas filtradas para impressao no template
        $lanc = [];
        $cont = 0;
        foreach ($notasXML as $chave => $valor) {
            if (($valor['SERIE'] == $paramEst[0]["SERIE"])
                and ($valor['MODELO'] == $paramEst[0]["MODELO"])
                and ($valor['CENTROCUSTO'] == $paramEst[0]["CENTROCUSTO"])
                and ($valor['TIPO'] == 1)
            ) {
                $numNF = intval($valor['NUMERO']);

                //VALIDA SE EXISTE XML NO ARRAY DIRETORIO XML
                $existeXml = array_search($numNF, $arrayDirEnviadasNumeros);
                if (($existeXml === false) or ($existeXml === null)) {
                    $lanc[$cont]['XML'] = false;
                } else {
                    $lanc[$cont]['XML'] = true;
                    $lanc[$cont]['PATH_XML'] = $sequencias_nf_dir[$existeXml]['PATH_XML'];
                }
                $lanc[$cont]['NUMERO'] = $numNF;
                $lanc[$cont]['EMISSAO'] = $valor['EMISSAO'];
                $lanc[$cont]['FILIAL'] = $valor['FILIAL'];
                $lanc[$cont]['PESSOA'] = $valor['NOMEREDUZIDO'];
                $lanc[$cont]['SITUACAO'] = $valor['SITUACAONOTA'];
                $lanc[$cont]['TOTAL'] = $valor['TOTALNF'];
                $cont++;
            }
        }


        if ($this->m_submenu !== "gerarXMLsContabilidade") {
            $this->smarty->assign('sequenciaFaltando', $sequenciaFaltando);
            $this->smarty->assign('pathImagem', $this->img);
            $this->smarty->assign('letra', $this->m_letra);
            $this->smarty->assign('subMenu', $this->m_submenu);
            $this->smarty->assign('dataInicio', $par[3]);
            $this->smarty->assign('dataFim', $par[4]);
            $this->smarty->assign('arrayDirEnviadasNumeros', $arrayDirEnviadasNumeros);
            $this->smarty->assign('cartaCorrecao', $carta_correcao);
            $this->smarty->assign('lanc', $lanc);

            $this->smarty->display('relatorio_geral_notas.tpl');
        } else {

            $table = $this->montaEmail($lanc, $carta_correcao, $sequenciaFaltando);
            //echo $table;
            return $table;
        }
    } //fim mostrasituacaos
    //-------------------------------------------------------------


    public function montaEmail($lanc, $cartaCorrecao, $sequenciaFaltando)
    {
        $imgApp = ADMraizFonte . "/bib/imagens/email_xml_contabilidade/app.png";
        $imgPhone = ADMraizFonte . "/bib/imagens/email_xml_contabilidade/phone.png";
        $imgMsg = ADMraizFonte . "/bib/imagens/email_xml_contabilidade/msg.png";
        $imgLocal = ADMraizFonte . "/bib/imagens/email_xml_contabilidade/local.png";
        
        $tabela = '<div style="margin: 20px 0 10px 0;">'; // Espaçamento superior e inferior
        $tabela .= '<h5 style="margin: 0; font-size: 16px;">RESUMO DO M&Ecirc;S</h5>';
        $tabela .= '</div>';
        $tabela .= '<table border="1">';
        $tabela .= '<thead>';
        $tabela .= '<tr>';
        $tabela .= '<th><h5>N&#186; NFe</h5></th>';
        $tabela .= '<th width="110px"><h5>EMISS&Atilde;O</h5></th>';
        $tabela .= '<th width="110px"><h5>FILIAL</h5></th>';
        $tabela .= '<th width="250px"><h5>PESSOA</h5></th>';
        $tabela .= '<th><h5>SITUA&Ccedil;&Atilde;O</h5></th>';
        $tabela .= '<th width="60px"><center><h5>TOTAL</h5></center></th>';
        $tabela .= '<th><center><h5>XML</h5></center></th>';
        $tabela .= '</tr>';
        $tabela .= '</thead>';
        $tabela .= '<tbody>';

        /* loop que ira popular */
        foreach ($lanc as $c => $value) {
            $tabela .= '<tr>';
            $tabela .= '<td class="infos-line"><center>' . $value['NUMERO'] . '</center></td>';
            $tabela .= '<td class="infos-line"><center>' . $value['EMISSAO'] . '</center></td>';

            $value['FILIAL'] = str_replace('PARANÁ', 'PARAN&Aacute;', $value['FILIAL']);
            $tabela .= '<td class="infos-line"><center>' . $value['FILIAL'] . '</center></td>';

            $tabela .= '<td class="infos-line">' . $value['PESSOA'] . '</td>';
            $tabela .= '<td class="infos-line"><center>' . $value['SITUACAO'] . '</center></td>';
            $tabela .= '<td class="infos-line"><center>' . $value['TOTAL'] . '</center></td>';

            /* teste se xml foi localizado */
            if ($value['XML'] == true) {
                $tabela .= '<td class="infos-line"><center>OK</center></td>';
            } else {
                $tabela .= '<td class="infos-line"><center>X</center></td>';
            }

            $tabela .= '</tr>';
            $totalGeral += $value['TOTAL'];
        } //fim loop
        $totalGeral = number_format($totalGeral, 2, ",", ".");

        $tabela .= '<tr>';
        $tabela .= '<td colspan="5"><h5><b>TOTAL </b></h5></td>';
        $tabela .= '<td colspan="2"><center><h5><b>R$ ' . $totalGeral . '</b></h5></center></td>';
        $tabela .= '</tr>';

        /* se existir carta de correcao */
        if ($cartaCorrecao !== false) {
            $tabela .= '<tr>';
            $tabela .= '<th colspan="7"><h4><b><center>Carta Corre&ccedil;&atilde;o do Per&iacute;odo</center></b></h4></th>';
            $tabela .= '</tr>';
            $tabela .= '<tr>';
            $tabela .= '<th>N&#186; NFe</th>';
            $tabela .= '<th><center><h5>CHAVE NFe</h5></center></th>';
            $tabela .= '<th><center><h5>M&Ecirc;S EMISS&Atilde;O NFe</h5></center></th>';
            $tabela .= '<th><center><h5>DATA EVENTO</center></th>';
            $tabela .= '<th colspan="3"><center><h5>DESCRI&Ccedil;&Atilde;O EVENTO</h5></center></th>';
            $tabela .= '</tr>';
            /* inicio do loop carta de correcao */
            foreach ($cartaCorrecao as $i => $value) {
                $tabela .= '<tr>';
                $tabela .= '<td class="infos-line-carta">' . $value['NUM_NF'] . '</td>';
                $tabela .= '<td class="infos-line-carta">' . $value['CHAVE_NFE'] . '</td>';
                $tabela .= '<td class="infos-line-carta"><center>';
                switch ($value['MES_EMISSAO_NF']) {
                    case '01':
                        $tabela .=  'JANEIRO';
                        break;
                    case '02':
                        $tabela .=  'FEVEREIRO';
                        break;
                    case '03':
                        $tabela .=  'MARÇO';
                        break;
                    case '04':
                        $tabela .=  'ABRIL';
                        break;
                    case '05':
                        $tabela .=  'MAIO';
                        break;
                    case '06':
                        $tabela .=  'JUNHO';
                        break;
                    case '07':
                        $tabela .=  'JULHO';
                        break;
                    case '08':
                        $tabela .=  'AGOSTO';
                        break;
                    case '09':
                        $tabela .=  'SETEMBRO';
                        break;
                    case '10':

                        $tabela .=  'OUTUBRO';
                        break;
                    case '11':
                        $tabela .=  'NOVEMBRO';
                        break;
                    case '12':
                        $tabela .=  'DEZEMBRO';
                        break;
                }
                $tabela .= '</center></td>';
                $tabela .= '<td class="infos-line-carta"><center>' . $value['DATA_EVENT'] . '</center></td>';
                $tabela .= '<td class="infos-line-carta" colspan="3">' . $value['DESC_EVENTO'] . '</td>';
                $tabela .= '</tr>';
            } //fim loop carta correcao

        } //fim existe carta correcao

        /* se existir sequencia da nota fiscal faltando */
        if (!empty($sequenciaFaltando)) {
            $tabela .= '<tr>';
            $tabela .= '<th colspan="10"><h4><b><center>Sequ&ecirc;ncia faltante</center></b></h4></th>';
            $tabela .= '</tr>';
            $tabela .= '<tr>';
            $tabela .= '<th colspan="10"> N&#186; NFe </th>';
            $tabela .= '</tr>';
            foreach ($sequenciaFaltando as $w => $value) {
                $tabela .= '<tr style="background-color: rgba(255, 221, 0, 0.631);">';
                $tabela .= '<td colspan="10">' . $value . '</td>';
                $tabela .= '</tr>';
            } //fim foreach

        } //fim sequencia faltando

        $tabela .= '</tbody>';
        $tabela .= '</table>';
        $tabela .= '</p></p>';

        // ============== CABEÇALHO COM CONTATOS E LOGO ==============
        $tabela .= '<table style="width: 100%; margin-bottom: 30px;">';

        // Linha divisória
        $tabela .= '<tr>
              <td colspan="2" style="border-bottom: 1px solid #d14646; height: 1px;"></td>
            </tr>';

        // Contatos + Logo
        $tabela .= '<tr>
              <td style="vertical-align: top; padding-top: 20px;">
                  <!-- Contatos -->
                  <table style="font-family: Arial;">
                      <tr>
                          <td style="vertical-align: middle; padding-right: 10px;">
                              <span style="background-color: #d14646; display: inline-block; padding: 4px;">
                                  <img src="' . $imgPhone . '" alt="Telefone" width="13">
                              </span>
                          </td>
                          <td style="font-size: 12px;">
                              <a href="tel:99593-0181" style="color: #000; text-decoration: none;">99593-0181</a> | 
                              <a href="tel:99593-0181" style="color: #000; text-decoration: none;">99593-0181</a>
                          </td>
                      </tr>
                      <tr>
                          <td style="vertical-align: middle; padding: 5px 10px 5px 0;">
                              <span style="background-color: #d14646; display: inline-block; padding: 4px;">
                                  <img src="' . $imgMsg . '" alt="Email" width="13">
                              </span>
                          </td>
                          <td>
                              <a href="mailto:contato@33robotics.com" style="color: #000; text-decoration: none; font-size: 12px;">
                                  contato@33robotics.com
                              </a>
                          </td>
                      </tr>
                      <tr>
                          <td style="vertical-align: middle; padding-right: 10px;">
                              <span style="background-color: #d14646; display: inline-block; padding: 4px;">
                                  <img src="' . $imgLocal . '" alt="Localização" width="13">
                              </span>
                          </td>
                          <td style="font-size: 12px;">
                              Marginal Comendador Franco | Avenida, 1341 - Jardim Bot&acirc;nico, Curitiba - PR, 80215-090
                          </td>
                      </tr>
                  </table>
              </td>
              <td style="text-align: right; vertical-align: top; padding-top: 20px;">
                  <img src="' . $imgApp . '" alt="Logo" style="max-width: 130px; height: auto;">
              </td>
            </tr>';

        $tabela .= '</table>';

        return $tabela;
    }
}    //	END OF THE CLASS

// Rotina principal - cria classe
if ($_GET["gerarObj"] == true) {
    $relatorio_geral_notas = new p_relatorio_geral_notas();

    $relatorio_geral_notas->controle();
}
