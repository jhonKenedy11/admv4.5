<?php

/**
 * @package   astec
 * @name      p_consultas
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);

require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda_relatorios.php");
include_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");

//Class p_rel_pedidos_lanc_fatura
class p_rel_pedidos_lanc_fatura extends c_pedido_venda_relatorios
{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    public $tpRelatorio = null;

    public $m_par = NULL;
    public $motivoSelecionados     = NULL;
    public $vendedorSelecionados   = NULL;
    public $condPagSelecionados    = NULL;
    public $situacaoSelecionados   = NULL;
    public $centroCustoSelecionados = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));

        $this->tpRelatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));
        $this->motivoSelecionados = (isset($parmGet['motivoSelected']) ? $parmGet['motivoSelected'] : '');
        $this->vendedorSelecionados = (isset($parmGet['vendedorSelected']) ? $parmGet['vendedorSelected'] : '');
        $this->condPagSelecionados = (isset($parmGet['condPagamentoSelected']) ? $parmGet['condPagamentoSelected'] : '');
        $this->situacaoSelecionados = (isset($parmGet['situacaoSelected']) ? $parmGet['situacaoSelected'] : '');
        $this->centroCustoSelecionados = (isset($parmGet['centroCustoSelected']) ? $parmGet['centroCustoSelected'] : '');
        $this->m_centro_custo = (isset($parmGet['centro_custo']) ? $parmGet['centro_custo'] : (isset($parmPost['centro_custo']) ? $parmPost['centro_custo'] : ''));
        $this->motivoSelecionados = (isset($parmGet['motivoSelected']) ? $parmGet['motivoSelected'] : '');
        $this->m_data_consulta = (isset($parmGet['data_consulta']) ? $parmGet['data_consulta'] : (isset($parmPost['data_consulta']) ? $parmPost['data_consulta'] : ''));
        $dates = explode(' - ', $this->m_data_consulta);
        $this->m_data_ini = $dates[0];
        $this->m_data_fim = $dates[1];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao == "pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
            $this->smarty->assign('disableSort', "[ 5 ]");
            $this->smarty->assign('numLine', "25");
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]");
            $this->smarty->assign('disableSort', "[ 0 ]");
            $this->smarty->assign('numLine', "25");
        endif;


        if ($this->m_par[1] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[1]);

        if ($this->m_par[2] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
            //	$data = mktime(0, 0, 0, $mes, 1, $ano);
            //	$this->smarty->assign('dataFim', date("d",$data-1).date("/m/Y"));
        } else $this->smarty->assign('dataFim', $this->m_par[2]);


        // include do javascript
        // include ADMjs . "/est/s_est.js";
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorioFaturaSintetico':
                $this->relatorioFaturaSintetico();
                break;
            default:
                $this->relatorioFaturaAnalitico();
        } //switch
    }

    // fim controle
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function relatorioFaturaSintetico()
    {
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        $this->smarty->assign('dataIni', $this->m_data_ini);
        $this->smarty->assign('dataFim', $this->m_data_fim);
        $this->smarty->assign('centro_custo', $this->m_centro_custo); 

        $lanc = $this->select_faturas_sintetico() ?? [];

        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('periodoIni', $this->m_par[1]);
        $this->smarty->assign('periodoFim', $this->m_par[2]);


        $this->smarty->display('relatorio_pedido_fatura_sintetico.tpl');
    } // fim relatorioFaturaSintetico    


    // Gera relatório de vendas
    //---------------------------------------------------------------
    function relatorioFaturaAnalitico()
    {
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        $this->smarty->assign('dataIni', $this->m_data_ini);
        $this->smarty->assign('dataFim', $this->m_data_fim);
        $this->smarty->assign('centro_custo', $this->m_centro_custo); 

        $lanc = $this->select_faturas_analitico() ?? [];

        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('periodoIni', $this->m_par[1]);
        $this->smarty->assign('periodoFim', $this->m_par[2]);

        $this->smarty->display('relatorio_pedido_fatura_analitico.tpl');
    } // fim geraRelatorioVenda

    //fim mostraConsultas
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_rel_compras(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$rel_pedidos = new p_rel_pedidos_lanc_fatura();

$rel_pedidos->controle();
