<?php

/**
 * @package   astec
 * @name      p_consultas
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto / Jhon Kenedy 
 * @date      13/05/2020
 * @date      12/01/2025
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

//Class p_rel_pedidos
class p_rel_pedidos extends c_pedido_venda_relatorios
{
    public $m_condicao_pagamento = NULL;
    public $m_tipo_relatorio = NULL;
    public $m_data_consulta = NULL;
    public $m_cliente_nome = NULL;
    public $m_centro_custo = NULL;
    public $m_tipo_entrega = NULL;
    public $m_cliente_id = NULL;
    public $m_rel_situacao = NULL;
    public $m_situacao = NULL;
    public $m_vendedor = NULL;
    public $m_submenu = NULL;
    public $m_motivo = NULL;
    public $m_letra = NULL;
    public $m_opcao = NULL;
    public $m_par = NULL;
    public $m_object = NULL;
    public $m_report = NULL;

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

        // NEW versao 4.5
        $this->m_situacao = (isset($parmGet['situacao']) ? $parmGet['situacao'] : (isset($parmPost['situacao']) ? $parmPost['situacao'] : ''));
        $this->m_cliente_nome = (isset($parmGet['cliente_nome']) ? $parmGet['cliente_nome'] : (isset($parmPost['cliente_nome']) ? $parmPost['cliente_nome'] : ''));
        $this->m_cliente_id = (isset($parmGet['cliente_id']) ? $parmGet['cliente_id'] : (isset($parmPost['cliente_id']) ? $parmPost['cliente_id'] : ''));
        $this->m_centro_custo = (isset($parmGet['centro_custo']) ? $parmGet['centro_custo'] : (isset($parmPost['centro_custo']) ? $parmPost['centro_custo'] : ''));
        $this->m_motivo = (isset($parmGet['motivo']) ? $parmGet['motivo'] : (isset($parmPost['motivo']) ? $parmPost['motivo'] : ''));
        $this->m_vendedor = (isset($parmGet['vendedor']) ? $parmGet['vendedor'] : (isset($parmPost['vendedor']) ? $parmPost['vendedor'] : ''));
        $this->m_condicao_pagamento = (isset($parmGet['condicao_pagamento']) ? $parmGet['condicao_pagamento'] : (isset($parmPost['condicao_pagamento']) ? $parmPost['condicao_pagamento'] : ''));
        $this->m_tipo_entrega = (isset($parmGet['tipo_entrega']) ? $parmGet['tipo_entrega'] : (isset($parmPost['tipo_entrega']) ? $parmPost['tipo_entrega'] : ''));
        $this->m_data_consulta = (isset($parmGet['data_consulta']) ? $parmGet['data_consulta'] : (isset($parmPost['data_consulta']) ? $parmPost['data_consulta'] : ''));
        $dates = explode(' - ', $this->m_data_consulta);
        $this->m_data_ini = $dates[0];
        $this->m_data_fim = $dates[1];

        // OLD
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_tipo_relatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));
        $this->m_report = (isset($parmGet['report']) ? $parmGet['report'] : (isset($parmPost['report']) ? $parmPost['report'] : ''));

        $this->m_item_estoque = (isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
        // $this->motivoSelecionados=(isset($parmGet['motivoSelected']) ? $parmGet['motivoSelected'] : '');
        // $this->vendedorSelecionados=(isset($parmGet['vendedorSelected']) ? $parmGet['vendedorSelected'] : '');
        // $this->condPagSelecionados=(isset($parmGet['condPagamentoSelected']) ? $parmGet['condPagamentoSelected'] : '');
        // $this->situacaoSelecionados=(isset($parmGet['situacaoSelected']) ? $parmGet['situacaoSelected'] : '');
        // $this->centroCustoSelecionados=(isset($parmGet['centroCustoSelected']) ? $parmGet['centroCustoSelected'] : '');
        // $this->tipoEntregaSelecionados=(isset($parmGet['tipoEntregaSelected']) ? $parmGet['tipoEntregaSelected'] : '');
        // $this->rel_situacao=(isset($parmGet['rel_situacao']) ? $parmGet['rel_situacao'] : null);
        // $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Consulta");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorioVendas':

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
                    "tipo_relatorio" => $this->m_tipo_relatorio
                );
                $this->geraRelatorioVenda();
                break;

            case 'relatorioDetalhado':

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
                    "tipo_relatorio" => $this->m_tipo_relatorio
                );
                $this->geraRelatorioVenda();
                break;

            case 'relatorioItem':

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
            case 'relatorioPedNaoEntregue':
            case 'relatorioItemEntrega':
            case 'relatorioEntrega':

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

            case 'relatorioMotivo':

                $this->m_object = (object) array(
                    "cliente_nome" => $this->m_cliente_nome,
                    "cliente_id" => $this->m_cliente_id,
                    "centro_custo" => $this->m_centro_custo,
                    "motivo" => $this->m_motivo,
                    "data_ini" => $this->m_data_ini,
                    "data_fim" => $this->m_data_fim,
                    "tipo_relatorio" => $this->m_tipo_relatorio
                );
                $this->geraRelatorioVenda();
                break;
            case 'relatorioFaturaGeral':

                $this->m_object = (object) array(
                    "situacao" => $this->m_situacao,
                    "cliente_nome" => $this->m_cliente_nome,
                    "cliente_id" => $this->m_cliente_id,
                    "centro_custo" => $this->m_centro_custo,
                    "condicao_pagamento" => $this->m_condicao_pagamento,
                    "data_ini" => $this->m_data_ini,
                    "data_fim" => $this->m_data_fim,
                    "tipo_relatorio" => $this->m_tipo_relatorio
                );
                $this->geraRelatorioVenda();
                break;
            case 'relatorioFaturaGeralA':

                $this->m_object = (object) array(
                    "situacao" => $this->m_situacao,
                    "cliente_nome" => $this->m_cliente_nome,
                    "cliente_id" => $this->m_cliente_id,
                    "centro_custo" => $this->m_centro_custo,
                    "condicao_pagamento" => $this->m_condicao_pagamento,
                    "data_ini" => $this->m_data_ini,
                    "data_fim" => $this->m_data_fim,
                    "tipo_relatorio" => $this->m_tipo_relatorio,
                    $this->rel_situacao = 'A'
                );
                $this->geraRelatorioVenda();
                break;

            default:

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
                    "tipo_relatorio" => $this->m_tipo_relatorio
                );
                $this->geraRelatorioVenda();
        }
    }

    // fim controle
    //---------------------------------------------------------------

    // Gera relatório de vendas
    //---------------------------------------------------------------
    function geraRelatorioVenda()
    {
        $this->smarty->assign('dataIni', $this->m_data_ini);
        $this->smarty->assign('dataFim', $this->m_data_fim);
        $dataIni = c_date::convertDateTxt($this->m_data_ini);
        $dataFim = c_date::convertDateTxt($this->m_data_fim);
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        if ($this->m_tipo_relatorio == 'relatorioPedNaoEntregue') {
            $lanc = $this->select_pedidos_nao_entregue($this->m_data_ini, $this->m_data_fim, $this->m_tipo_entrega);
        } elseif ($this->m_tipo_relatorio !== 'relatorioPedNaoEntregue' || $this->m_tipo_relatorio !== 'relatorioItemEntrega' ) {
            $lanc = $this->select_pedidos_geral($this->m_object);
        }

        if (
            $this->m_tipo_relatorio == 'relatorioDetalhado' || $this->m_tipo_relatorio == 'relatorioItem' || $this->m_tipo_relatorio == 'relatorioFaturaGeral'
            || $this->m_tipo_relatorio == 'relatorioFaturaGeralA' || $this->m_tipo_relatorio == 'relatorioItemEntrega'
        ) {
            $lancItem = [];
            $lanc = $lanc ?? [];
            for ($i = 0; $i < count($lanc); $i++) {
                $resp = $this->m_tipo_relatorio == 'relatorioFaturaGeral' || $this->m_tipo_relatorio == 'relatorioFaturaGeralA'
                    ? $this->select_fatura_pedidos_venda($lanc[$i]['PEDIDO'], $this->rel_situacao, 'C', 'A')
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


        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;

        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
        // Calcula lancamentos de acordo com condição pagamento.
        //$fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        $lanc = $lanc ?? [];
        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('periodoIni', $this->m_par[1]);
        $this->smarty->assign('periodoFim', $this->m_par[2]);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('fin', $fin);

        $consulta = new c_banco();
        $sql = "SELECT * FROM AMB_USUARIO_AUTORIZA WHERE USUARIO = (" . $this->m_usergrupo . ") 
                AND PROGRAMA = 'RELPEDIDOVENDACOMCUSTO' AND DIREITOS LIKE '%C%'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();


        $result = $consulta->resultado ?? [];


        if (sizeof($result) > 0) {
            $this->smarty->assign('tipoUsuario', 'Consulta');
        }

        switch ($this->m_tipo_relatorio) {
            case 'relatorioMes':
                $this->smarty->display('relatorio_pedido_venda_mes.tpl');
                break;
            case 'relatorioSemana':
                $this->smarty->display('relatorio_pedido_venda_semana.tpl');
                break;
            case 'relatorioEntrega':
                $this->smarty->display('relatorio_pedido_entrega.tpl');
                break;
            case 'relatorioDetalhado':
                $this->smarty->display('relatorio_pedido_venda_detalhado.tpl');
                break;
            case 'relatorioItem':
                $this->smarty->display('relatorio_pedido_venda_item.tpl');
                break;
            case 'relatorioItemEntrega':
                $this->smarty->display('relatorio_pedido_venda_item_entrega.tpl');
                break;
            case 'relatorioFaturaGeralA':
                $this->smarty->display('relatorio_pedido_venda_fatura.tpl');
                break;
            case 'relatorioFaturaGeral':
                $this->smarty->display('relatorio_pedido_venda_fatura.tpl');
                break;
            case 'relatorioVendedor':
                $this->smarty->display('relatorio_pedido_venda_vendedor.tpl');
                break;
            case 'relatorioMotivo':
                $this->smarty->display('relatorio_pedido_venda_motivo.tpl');
                break;
            case 'relatorioCondPagamento':
                $this->smarty->display('relatorio_pedido_venda_cond_pagamento.tpl');
                break;
            case 'relatorioEstoqueDisponivelVenda':
                $this->smarty->display('relatorio_estoque_disponivel_venda.tpl');
                break;
            case 'relatorioPedNaoEntregue':
                $this->smarty->display('relatorio_pedido_venda_entrega_nao_realizada.tpl');
                break;
            case 'relatorioVendas':
                $this->smarty->display('relatorio_pedido_venda.tpl');
                break;
            default:
                $this->smarty->display('relatorio_pedido_venda.tpl');
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
$rel_pedidos = new p_rel_pedidos();

$rel_pedidos->controle();
