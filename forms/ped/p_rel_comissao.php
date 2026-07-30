<?php
/**
 * @package   astec
 * @name      p_rel_comissao
 * @version   1.0.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @date      29/06/2026
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/ped/c_pedido_venda_relatorios.php");

//Class p_rel_comissao
Class p_rel_comissao extends c_pedido_venda_relatorios {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    public $tpRelatorio = null;

    public $m_par = NULL;
    public $m_data_consulta = NULL;
    public $m_vendedor = NULL;

    /**
     * <b> Função magica construct </b>
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra   = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao   = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->tpRelatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));

        // filtros do relatorio (vindos via POST do modal de parametros)
        $this->m_vendedor = (isset($parmPost['vendedor']) ? $parmPost['vendedor'] : (isset($parmGet['vendedor']) ? $parmGet['vendedor'] : ''));

        $this->m_data_consulta = (isset($parmGet['data_consulta']) ? $parmGet['data_consulta'] : (isset($parmPost['data_consulta']) ? $parmPost['data_consulta'] : ''));
        $dates = explode(' - ', $this->m_data_consulta);
        $this->m_data_ini = isset($dates[0]) ? $dates[0] : '';
        $this->m_data_fim = isset($dates[1]) ? $dates[1] : '';

        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs', ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
    }

//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'relatorioComissao':
                $this->relatorioComissao();
            break;
            default:
                $this->relatorioComissao();
        } //switch
    }
// fim controle
//---------------------------------------------------------------

// Gera relatório de comissão (faturado)
//---------------------------------------------------------------
    function relatorioComissao(){
        $this->smarty->assign('dataIni', $this->m_data_ini);
        $this->smarty->assign('dataFim', $this->m_data_fim);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        $this->smarty->assign('subMenu', $this->m_submenu);

        $lanc = $this->relComissaoFatura() ?? [];
        $this->smarty->assign('pedido', $lanc);

        $this->smarty->display('relatorio_comissao.tpl');
    }// fim relatorioComissao

//-------------------------------------------------------------
}

// Rotina principal - cria classe
$rel_comissao = new p_rel_comissao();
$rel_comissao->controle();
?>
