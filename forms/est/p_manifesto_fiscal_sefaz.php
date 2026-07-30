<?php

/**
 * @package   astec
 * @name      p_manisfesto_fiscal_sefaz
 * @version   3.0.00
 * @copyright 2022
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@gmail.com.br>
 * @date     01/08/2023
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_manifesto_fiscal_sefaz.php");
require_once($dir . "/../../class/est/c_manifesto_fiscal_nf.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../forms/est/p_nfephp_40.php");
//Class p_manifesto_Fiscal
Class p_manifesto_fiscal_sefaz extends c_manifesto_fiscal_sefaz {

    private $m_submenu = NULL;
    private $m_letra   = NULL;
    private $m_opcao   = NULL;
    private $m_msg     = NULL;
    public  $smarty    = NULL;
    private $m_idNF    = NULL;
    private $parmGet   = NULL;
    private $parmPost  = NULL;
    private $param     = NULL;
    private $typeEvent = NULL;

    
//---------------------------------------------------------------
//---------------------------------------------------------------
    function __construct() {
        // @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
        //$this->smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATE ;
        // $this->smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_STRICT & E_DEPRECATED ;
        // $this->smarty->setErrorReporting( E_ALL & ~E_NOTICE );
        
        // inicializa variaveis de controle

        //$this->m_submenu = (isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : $this->parmGet['submenu']) ? $this->parmGet['submenu'] : '';
        if((isset($this->parmPost['submenu'])) && ($this->parmPost['submenu']) !== ''){
            $this->m_submenu = $this->parmPost['submenu'];
        }elseif((isset($this->parmGet['submenu'])) && ($this->parmGet['submenu']) !== ''){
            $this->m_submenu = $this->parmGet['submenu'];
        }else{
            $this->m_submenu = '';
        }

        //$this->setIdNf(isset($this->parmGet['idNf']) !== '' ? $this->parmGet['idNf'] : $this->parmPost['idNf']);
        if ((isset($this->parmPost['idNf'])) && ($this->parmPost['idNf']) !== '') {
            $this->setIdNf($this->parmPost['idNf']);
        } elseif ((isset($this->parmGet['idNf'])) && ($this->parmGet['idNf']) !== '') {
             $this->setIdNf($this->parmGet['idNf']);
        } else {
             $this->setIdNf(null);
        }

        if ((isset($this->parmPost['param'])) && ($this->parmPost['param']) !== '') {
            $this->param = $this->parmPost['param'];
        } elseif ((isset($this->parmGet['param'])) && ($this->parmGet['param']) !== '') {
            $this->param = $this->parmGet['param'];
        } else {
            $this->param = '';
        }

        //$this->param = isset($this->parmGet['param']) ? $this->parmGet['param'] : isset($this->parmPost['param']) ? $this->parmPost['param'] : '';
        $this->typeEvent = $this->parmGet['typeEvent'] ?? $this->parmPost['typeEvent'] ?? '';

        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Manifesto Fiscal");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8,9]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
         switch ($this->m_submenu) {
            case 'cadastrar':
                $this->desenhaCadastroManifestoFiscalSefaz('');
            break;
            case 'downloadXml':
                $nfArray = c_nota_fiscal::select_nota_fiscal_id($this->getIdNf());
                $chave = $nfArray[0]['CHNFE'] ?? '';

                if (empty($chave)) {
                    $return = [
                        'code' => 404, // Key not found in database
                        'id_nota' => $this->getIdNf(),
                        'message' => 'Chave de acesso não localizada no banco de dados',
                        'chave_acesso' => ''
                    ];
                    $this->respondWithJson($return);
                } else {
                    // Verifica se já existe XML no banco
                    $xmlnf = c_nota_fiscal::select_xml_nota_fiscal($this->getIdNf());
                    if (!empty($xmlnf) && !empty($xmlnf[0]['XMLCONSULTA'])) {
                        $xmlRet = $xmlnf[0]['XMLCONSULTA'];
                        
                        // Verifica se o XML do banco é resumo (resNFe) em vez de XML completo
                        if (stripos($xmlRet, '<resNFe') !== false && stripos($xmlRet, '<NFe') === false) {
                            // É resumo - retorna código especial
                            $return = [
                                'code' => 406, // Resumo retornado (resNFe)
                                'id_nota' => $this->getIdNf(),
                                'chave_acesso' => $chave,
                                'message' => 'O XML armazenado no banco de dados é apenas um resumo da nota fiscal (resNFe), não o XML completo. É necessário consultar a nota fiscal completa em outro sistema.'
                            ];
                            $this->respondWithJson($return);
                        } else {
                            // XML completo recuperado do banco
                            $return = [
                                'code' => 100, //Download accomplished
                                'id_nota' => $this->getIdNf(),
                                'xml' => $xmlRet,
                                'fileName' => $chave,
                                'message' => 'XML recuperado do banco de dados'
                            ];
                            $this->respondWithJson($return);
                        }
                    } else {
                        // Tenta fazer download na SEFAZ
                        $xmlRet = $this->downloadChaveAcesso($this->getIdNf(), $chave);
                        
                        // Verifica se retornou resumo (resNFe) em vez de XML completo
                        if (is_array($xmlRet) && isset($xmlRet['tipo']) && $xmlRet['tipo'] === 'resNFe') {
                            // Retorna código especial para resumo
                            $return = [
                                'code' => 406, // Resumo retornado (resNFe)
                                'id_nota' => $this->getIdNf(),
                                'chave_acesso' => $chave,
                                'message' => 'A SEFAZ retornou apenas um resumo da nota fiscal (resNFe), não o XML completo. É necessário consultar a nota fiscal completa em outro sistema.'
                            ];
                            $this->respondWithJson($return);
                        } elseif ($xmlRet && $xmlRet !== false && !is_array($xmlRet)) {
                            // Verifica se é string mas contém resNFe (caso a verificação anterior não tenha funcionado)
                            if (stripos($xmlRet, '<resNFe') !== false && stripos($xmlRet, '<NFe') === false) {
                                // É resumo mesmo sendo string
                                $return = [
                                    'code' => 406, // Resumo retornado (resNFe)
                                    'id_nota' => $this->getIdNf(),
                                    'chave_acesso' => $chave,
                                    'message' => 'A SEFAZ retornou apenas um resumo da nota fiscal (resNFe), não o XML completo. É necessário consultar a nota fiscal completa em outro sistema.'
                                ];
                                $this->respondWithJson($return);
                            } else {
                                // XML completo baixado com sucesso
                                $return = [
                                    'code' => 100, //Download accomplished
                                    'id_nota' => $this->getIdNf(),
                                    'xml' => $xmlRet,
                                    'fileName' => $chave,
                                    'message' => 'XML baixado da SEFAZ com sucesso'
                                ];
                                $this->respondWithJson($return);
                            }
                        } else {
                            // Erro no download - retorna JSON com chave de acesso para o usuário
                            $return = [
                                'code' => 405, // Download sefaz no fulfilled
                                'id_nota' => $this->getIdNf(),
                                'chave_acesso' => $chave,
                                'message' => 'Não foi possível realizar o download do XML na SEFAZ. Verifique a chave de acesso ou tente novamente mais tarde.'
                            ];
                            $this->respondWithJson($return);
                        }
                    }
                }
                
            break;
            case 'eventoManifestoNotaFiscal':
                $return = $this->enviaEventoManifesto($this->getIdNf(), $this->typeEvent, $this->param);
                $this->respondWithJson($return);
            break;
            case 'consultarDocumentosSefazPreparar':
                try {
                    $prep = $this->obterDadosConsultaManifestoSefaz();
                    if ($prep['cStat'] === '405') {
                        $this->respondWithJson($prep);
                        break;
                    }
                    $nfe = new p_nfe_40();
                    $this->respondWithJson($nfe->consultaDistNfeIniciar($prep['ultimaNSU']));
                } catch (\Throwable $e) {
                    $this->respondWithJson(['cStat' => 'error', 'message' => $e->getMessage()]);
                }
            break;
            case 'consultarDocumentosSefazLote':
                try {
                    $msg = (new p_nfe_40())->consultaDistNfeUmLote();
                    $this->respondWithJson(is_array($msg) ? $msg : ['cStat' => 'error', 'message' => 'Resposta inválida da consulta SEFAZ.']);
                } catch (\Throwable $e) {
                    $this->respondWithJson(['cStat' => 'error', 'message' => $e->getMessage()]);
                }
            break;
            default:
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'C')) {
                    $this->mostraManifestoFiscalSefaz('');
                }
        }
    }
    
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaCadastroManifestoFiscalSefaz($mensagem = NULL, $tipoMsg = NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCentroCusto());


        // ########## CENTROCUSTO ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i] = $result[$i]['ID'];
            $centroCusto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);        
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());

        $this->smarty->display('manifesto_fiscal_sefaz_cadastro.tpl');
    }

//fim desenhaCadastroManifestoFiscal
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraManifestoFiscalSefaz($mensagem=NULL,  $tipoMsg = NULL, $file='') {        
        
        if ($this->m_letra != '') {
            $lanc = $this->selectManifestoFiscalSefazLetra($this->m_letra);
        }

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);

        if ($this->m_par[0] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[0]);

        if ($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
        } else {
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        // Corrigido: filial_id deve usar o centro de custo padrão, não m_par[0] que contém data
        $this->smarty->assign('filial_id', $this->m_empresacentrocusto);

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacao_ids[0] = 0;
        $situacao_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        // Corrigido: situacao_id deve ter valor padrão 'B', não usar m_par[1] que contém data
        $this->smarty->assign('situacao_id', 'B');

        $this->smarty->display('manifesto_fiscal_sefaz_mostra.tpl');
        
    }

//fim mostraManifestoFiscal

    function respondWithJson($data) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
//-------------------------------------------------------------
}



//	END OF THE CLASS
// Rotina principal - cria classe
$manifesto_fiscal = new p_manifesto_fiscal_sefaz();

$manifesto_fiscal->controle();
?>
