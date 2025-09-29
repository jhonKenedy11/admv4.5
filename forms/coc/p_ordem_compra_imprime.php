<?php

if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/coc/c_ordem_compra.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");


Class p_ordem_compra_imprime extends c_ordemCompra {

    private $m_submenu          = NULL;
    private $m_origem           = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    public $smarty              = NULL;
    private $m_motivoSelecionados = null;
    private $m_vendedorSelecionados = null;
    private $m_condPagSelecionados = null;
    private $m_situacaoSelecionados = null;
    private $m_centroCustoSelecionados = null;

    function __construct() {

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/coc";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = $parmPost['submenu'];
        $this->m_origem = $parmPost['origem'];
        $this->m_letra = $parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        
        $this->setId(isset($parmGet['parm']) ? $parmGet['parm'] : '');
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : '');
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : '');
        $this->m_motivoSelecionados = (isset($parmGet['motivos']) ? $parmGet['motivos'] : '');
        $this->m_vendedorSelecionados = (isset($parmGet['vendedores']) ? $parmGet['vendedores'] : '');
        $this->m_condPagSelecionados = (isset($parmGet['condPag']) ? $parmGet['condPag'] : '');
        $this->m_situacaoSelecionados = (isset($parmGet['situacao']) ? $parmGet['situacao'] : '');
        $this->m_centroCustoSelecionados = (isset($parmGet['centroCusto']) ? $parmGet['centroCusto'] : '');
    }

    function controle() {
        switch ($this->m_submenu) {
            default:
                $this->mostraPedidoImprime('');
        }
    }

    function mostraPedidoImprime($mensagem=NULL, $tipoMsg=NULL) {
            
            
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $lanc = $this->select_ordem_compra_id();
        $lancItem = $this->select_ordem_compra_item_id();
        $empresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);

        $condPgto = new c_cond_pgto();
        $condPgto->setId($lanc[0]['CONDPG']);
        $descPgto = $condPgto->selectCondPgto();
        $descCondPgto = $descPgto[0]['DESCRICAO'];
        

        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('fin', $fin);

        $this->smarty->display('ordem_compra_imprime.tpl');
    }

}

$pedido = new p_ordem_compra_imprime();

$pedido->controle();
?>
