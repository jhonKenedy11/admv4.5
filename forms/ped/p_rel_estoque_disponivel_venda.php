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
require_once($dir . "/../../class/est/c_produto_estoque.php");

//Class p_rel_pedidos
class p_rel_estoque_disponivel_venda extends c_pedido_venda_relatorios
{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    public $m_tipo_relatorio = null;

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

        $this->m_tipo_relatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));
        $this->motivoSelecionados = (isset($parmGet['motivoSelected']) ? $parmGet['motivoSelected'] : '');
        $this->vendedorSelecionados = (isset($parmGet['vendedorSelected']) ? $parmGet['vendedorSelected'] : '');
        $this->condPagSelecionados = (isset($parmGet['condPagamentoSelected']) ? $parmGet['condPagamentoSelected'] : '');
        $this->situacaoSelecionados = (isset($parmGet['situacaoSelected']) ? $parmGet['situacaoSelected'] : '');
        $this->centroCustoSelecionados = (isset($parmGet['centroCustoSelected']) ? $parmGet['centroCustoSelected'] : '');
        $this->motivoSelecionados = (isset($parmGet['motivoSelected']) ? $parmGet['motivoSelected'] : '');
        $this->m_item_estoque = (isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
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
            case 'relatorioEstoqueDisponivelVenda':

                $this->m_object = (object) array(
                    "situacao" => $this->m_situacao,
                    "cliente_nome" => $this->m_cliente_nome,
                    "cliente_id" => $this->m_cliente_id,
                    "centro_custo" => $this->m_centro_custo,
                    "motivo" => $this->m_motivo,
                    "vendedor" => $this->m_vendedor,
                    "condicao_pagamento" => $this->m_condicao_pagamento,
                    "tipo_entrega" => $this->m_tipo_entrega,
                    "data_ini" => $this->m_data_ini,
                    "data_fim" => $this->m_data_fim,
                    "tipo_relatorio" => $this->m_tipo_relatorio,
                    "codProduto" => $this->m_item_estoque
                );
                $this->geraRelatorioVenda();
                break;
        } //switch
    }

    // fim controle

    // Gera relatório de vendas
    //---------------------------------------------------------------
    function geraRelatorioVenda()
    {
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('dataIni', $this->m_data_ini);
        $this->smarty->assign('dataFim', $this->m_data_fim);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $lanc = $this->select_pedidos_geral($this->m_object);

        if ($this->m_tipo_relatorio = 'relatorioEstoqueDisponivelVenda') {
            $lancItem = [];
            $lanc = $lanc ?? [];
            for ($i = 0; $i < count($lanc); $i++) {
                $resp = $this->m_tipo_relatorio == 'Fatura' ?
                    $this->select_fatura_pedidos_venda($lanc[$i]['PEDIDO'], $this->rel_situacao)
                    : $this->select_pedidos_item_geral($lanc[$i]['PEDIDO'], $this->m_item_estoque);
                    $resp = $resp ?? []; 
                for ($k = 0; $k < count($resp); $k++) {
                    if ($lancItem[0] == '') {
                        $lancItem[$k] = $resp[$k];
                    } else {
                        array_push($lancItem, $resp[$k]);
                    }
                }
            }
        }

        $resultProduto = [];
        $p = 0;
        $classProdutoQtde = new c_produto_estoque();
        for ($k = 0; $k < count($lancItem); $k++) {

            $produtoQuant = $classProdutoQtde->produtoQtde($lancItem[$k]['ITEMESTOQUE'], $this->m_empresacentrocusto) ?? [];
            $lancItem[$k]['ESTOQUE'] = 0;
            $lancItem[$k]['RESERVA'] = 0;
            for ($q = 0; $q < count($produtoQuant); $q++) {
                if ($produtoQuant[$q]['STATUS'] == 0):
                    $lancItem[$k]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
                else:
                    $lancItem[$k]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
                endif;
                //$produto[$i]['CENTROCUSTO'] = $produtoQuant[$q]['CCUSTO'];
            }
            $resultProduto[$p] = $lancItem[$k];
            $p++;
        }

        //var_dump($lancItem);

        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $this->m_par[1]);
        $this->smarty->assign('periodoFim', $this->m_par[2]);
        $this->smarty->assign('fin', $fin);


        switch ($this->m_tipo_relatorio) {
            case 'relatorioEstoqueDisponivelVenda':
                $this->smarty->display('relatorio_estoque_disponivel_venda.tpl');
                break;
            default:
                $this->smarty->display('relatorio_estoque_disponivel_venda.tpl');
        };
    } // fim geraRelatorioVenda

    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ids[0] = '';
        $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i + 1] = $result[$i]['ID'];
            $names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        $i = 0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }
    }


    //fim mostraConsultas
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_rel_compras(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$rel_pedidos = new p_rel_estoque_disponivel_venda();

$rel_pedidos->controle();
