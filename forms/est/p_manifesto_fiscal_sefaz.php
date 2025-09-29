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
                $chave = $nfArray[0]['CHNFE'];

                if (empty($chave)) {
                    $return = [
                        'code' => 404, // Key not found in database
                        'id_nota' => $this->getIdNf()
                    ];
                    $this->respondWithJson($return);
                } else {
                    //$xmlnf = c_nota_fiscal::select_xml_nota_fiscal($this->getIdNf());
                    //if xml no does not exist will download in sefaz
                    $xmlRet = $xmlnf[0]['XMLCONSULTA'] ?? $this->downloadChaveAcesso($this->getIdNf(), $chave);
                    if ($xmlRet) {
                        $return = [
                            'code' => 100, //Download accomplished
                            'id_nota' => $this->getIdNf(),
                            'xml' => $xmlRet,
                            'fileName' => $chave
                        ];
                        $this->respondWithJson($return);
                    } else {
                        $this->respondWithJson(405); // Donwload sefaz no fulfilled
                    }
                }
                
            break;
            case 'eventoManifestoNotaFiscal':

                $this->m_msg = $this->enviaEventoManifesto($this->getIdNf(), $this->typeEvent, $this->param);

                if (is_numeric($this->m_msg)){
                    $return = $this->m_msg;
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }else{
                    $return = 'Erro ao enviar o evento, entre em contato com o suporte';
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }
            break;
            case 'consultarDocumentosSefaz':
                //$teste= date('Y-m-d H:i:s');
                $atual = new DateTime();
                $consulta = new c_banco();
                $sql = "SELECT * FROM EST_MANIFESTO WHERE CENTROCUSTO = ".$this->m_empresacentrocusto." ORDER BY id DESC LIMIT 1;";
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $result = $consulta->resultado;
                $proximaCons = $result[0]['PROXIMACONSULTA'];
                $proximaConsExplode = explode(' ', $proximaCons);

                if(!isset($result[0]['ULTNSU'])){
                    $ultimaNSU = null;
                }else{
                    $ultimaNSU = $result[0]['ULTNSU'];
                }

                //$diferenca = $atual - $proximaCons;

                //$ultimaNSU = null;
                //checks the time difference or if rejected because of NSU
                if(($atual > $proximaCons or $atual->format('H:i:s') == $proximaConsExplode[1]) or $result[0]['CSTAT'] == '589'){
                    $danfe = new p_nfe_40();
                    $msg = $danfe->consultaDistNfe($ultimaNSU);

                    if($msg == null){
                        $msg = array(
                            "cStat" => false,
                            "message" => 'Realizado consulta, mas não localizado eventos recentes de NF-e'
                        );
                    } else if($msg == 'true'){
                        $msg = array(
                            "cStat" => 'true',
                            "message" => ''
                        );
                    }

                    header('Content-type: application/json');
                    echo json_encode($msg, JSON_FORCE_OBJECT);
                } else {
                    
                    $msg = 'Consulta bloqueada, próxima consulta disponivel  '.$proximaConsExplode[1].'.';
                    $return = array(
                        "cStat" => '405',
                        "message" => $msg
                    );
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
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
        if ((!is_null($this->m_par[0])) and ($this->m_par[0]!='')) {
            $this->smarty->assign('filial_id', $this->m_par[0]);
        } else {
            $this->smarty->assign('filial_id',  $this->m_empresacentrocusto);
        }        

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
        if ($this->m_par[1] == ""){
            $this->smarty->assign('situacao_id', 'B');
        }else{
            $this->smarty->assign('situacao_id', $this->m_par[1]);
        }

        $this->smarty->display('manifesto_fiscal_sefaz_mostra.tpl');
        
    }

//fim mostraManifestoFiscal


function respondWithJson($data) {
    header('Content-type: application/json');
    echo json_encode($data);
}
//-------------------------------------------------------------
}



//	END OF THE CLASS
// Rotina principal - cria classe
$manifesto_fiscal = new p_manifesto_fiscal_sefaz();

$manifesto_fiscal->controle();
?>
