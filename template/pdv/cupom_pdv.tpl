<link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet">
<style>
    .form-control, .x_panel { border-radius: 5px; }
    .pdv-area-listagem {
        display: flex;
        flex-direction: column;
    }
    #listaProdutos {
        margin-top: 8px;
        display: none;
        overflow: hidden;
        border: 1px solid #e6e9ed;
        border-radius: 4px;
        background: #fff;
    }
    #listaProdutos.pdv-lista-visivel {
        display: flex;
        flex-direction: column;
        height: 260px;
        min-height: 260px;
        max-height: 260px;
    }
    #listaProdutos .pdv-tabela-produtos {
        flex: 1 1 auto;
        min-height: 0;
        max-height: 100%;
        overflow-y: auto;
        overflow-x: hidden;
    }
    #listaProdutos .pdv-tabela-produtos thead th {
        background: #73879c;
        color: #fff;
        padding: 5px;
        font-size: 12px;
    }
    #listaProdutos tr.pdv-linha-produto {
        cursor: pointer;
    }
    #listaProdutos tr.pdv-linha-produto:hover {
        background: #f0f8ff;
    }
    #listaProdutos tr.pdv-linha-produto.pdv-linha-selecionada {
        background: #d9edf7;
        font-weight: 600;
    }
    #pdvPainelItem {
        margin-top: 10px;
        padding: 12px 14px;
        background: #f9f9f9;
        border: 1px solid #e6e9ed;
        border-radius: 5px;
    }
    #pdvPainelItem.pdv-painel-edicao {
        border-color: #337ab7;
        background: #f0f7fc;
    }
    .pdv-painel-titulo {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 600;
        color: #2a3f54;
    }
    .pdv-painel-acoes-col .pdv-botoes-inline {
        display: flex;
        align-items: center;
        gap: 4px;
        height: 30px;
    }
    .pdv-painel-acoes-col .pdv-btn-icone {
        width: 34px;
        height: 30px;
        padding: 5px 0;
        line-height: 1;
        flex-shrink: 0;
    }
    .pdv-painel-acoes-col .pdv-btn-icone .glyphicon {
        font-size: 14px;
        top: 0;
    }
    .pdv-resumo-itens .pdv-acoes-item .btn {
        margin-left: 2px;
    }
    #tblItensCupom tbody tr td {
        vertical-align: middle !important;
    }
    #statusEmissao.ok { color: #26B99A; }
    #statusEmissao.erro { color: #d9534f; }
    .pdv-busca-wrap { position: relative; }
    .pdv-busca-linha {
        margin: 0 0 10px 0;
    }
    .pdv-busca-linha .input-group {
        width: 340px;
        max-width: 100%;
    }
    .pdv-busca-linha #termoProduto {
        width: 100%;
        height: 30px;
    }
    .pdv-tela {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        gap: 12px;
        margin: 0 -4px;
    }
    .pdv-col-esquerda {
        flex: 1 1 38%;
        min-width: 300px;
        max-width: 48%;
    }
    .pdv-col-direita {
        flex: 1 1 520px;
        min-width: 420px;
        max-width: 58%;
        width: auto;
    }
    @media (max-width: 991px) {
        .pdv-col-direita {
            flex: 1 1 100%;
            width: 100%;
        }
    }
    .pdv-topbar .x_panel {
        margin-bottom: 10px;
    }
    .pdv-resumo-sidebar {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 120px);
        margin-bottom: 0;
    }
    .pdv-resumo-sidebar .x_content {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        padding: 12px 14px 14px;
    }
    .pdv-resumo-itens {
        flex: 1 1 auto;
        margin: 0 0 12px 0;
        min-height: 120px;
        max-height: none;
        overflow-y: auto;
        border: 1px solid #e6e9ed;
        border-radius: 4px;
    }
    .pdv-resumo-totais {
        flex: 0 0 auto;
        margin-top: auto;
    }
    .pdv-resumo-meta {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e6e9ed;
        font-size: 12px;
        color: #73879c;
    }
    .pdv-resumo-meta strong {
        color: #2a3f54;
        font-size: 14px;
    }
    .pdv-resumo-cliente-select {
        margin-bottom: 12px;
    }
    .pdv-resumo-acao .btn {
        padding: 10px 16px;
        font-size: 15px;
    }
    .pdv-resumo-itens table {
        font-size: 12px;
        margin-bottom: 0;
    }
    .pdv-resumo-itens thead th {
        background: #73879c;
        color: #fff;
        padding: 5px 6px;
        font-size: 11px;
        white-space: nowrap;
    }
    .pdv-resumo-itens tbody td {
        padding: 5px 6px !important;
        vertical-align: middle !important;
    }
    .pdv-resumo-totais {
        padding-top: 10px;
        border-top: 1px solid #e6e9ed;
    }
    .pdv-resumo-cliente {
        margin: 0 0 12px 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #e6e9ed;
        font-size: 13px;
        line-height: 1.35;
        word-break: break-word;
    }
    .pdv-resumo-cliente strong {
        display: block;
        font-size: 11px;
        color: #73879c;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 4px;
    }
    .pdv-resumo-linha {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 6px;
        font-size: 13px;
    }
    .pdv-resumo-linha span:first-child {
        color: #73879c;
    }
    .pdv-resumo-linha span:last-child {
        font-weight: 600;
        text-align: right;
        margin-left: 8px;
    }
    .pdv-resumo-linha-campo {
        align-items: center;
        gap: 8px;
    }
    .pdv-resumo-linha-campo label {
        flex: 0 0 auto;
        margin: 0;
        color: #73879c;
        font-weight: normal;
        font-size: 13px;
    }
    .pdv-resumo-linha-campo input {
        flex: 1 1 auto;
        max-width: 140px;
        margin-left: auto;
        font-weight: 600;
    }
    .pdv-resumo-total-box {
        margin-top: 14px;
        padding: 12px;
        background: #f7f7f7;
        border: 1px solid #e6e9ed;
        border-radius: 5px;
        text-align: right;
    }
    .pdv-resumo-total-box .pdv-resumo-total-label {
        display: block;
        font-size: 11px;
        color: #73879c;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .pdv-resumo-total-box .pdv-resumo-total-valor {
        font-size: 26px;
        font-weight: 700;
        color: #1a3a52;
        line-height: 1.1;
    }
    .pdv-resumo-acao {
        margin-top: 12px;
    }
    @media (min-width: 992px) {
        .pdv-col-direita {
            position: sticky;
            top: 10px;
            align-self: flex-start;
        }
    }
</style>

<div class="right_col" role="main" style="padding: 14px;">
    <form id="lancamento" class="form-horizontal form-label-left" name="lancamento" action="{$SCRIPT_NAME}" method="post">
        <input name="mod" type="hidden" value="pdv">
        <input name="form" type="hidden" value="cupom">
        <input name="submenu" type="hidden" value="{$subMenu}">
        <input name="ajax" type="hidden" id="ajaxFlag" value="">
        <input name="id" type="hidden" id="idPedido" value="{$pedido.id}">
        <input name="cliente" type="hidden" id="clienteHidden" value="{$pedido.cliente}">
        <input type="hidden" id="totalPedidoFixo" value="{$pedido.totalCupom|default:'0,00'}">
        <input type="hidden" id="pdvSelCodigo" value="">
        <input type="hidden" id="pdvEditNrItem" value="">

        <div class="pdv-tela">
            <div class="pdv-col-esquerda">
                <div class="pdv-topbar">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Cupom PDV</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button type="button" class="btn btn-dark" id="btnNovoCupom" onclick="pdvNovoCupom();">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        <span> Novo cupom</span>
                                    </button>
                                </li>
                                <li>
                                    <a href="{$urlMostra|default:'index.php?mod=pdv&form=cupom'}" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                        <span> Voltar</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            {if $mensagem neq ''}
                                <div class="alert alert-{if $tipoMsg eq 'erro'}danger{elseif $tipoMsg eq 'alerta'}warning{else}info{/if} alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    {$mensagem}
                                </div>
                            {/if}
                            {if $jaExisteCpm}
                                <div class="alert alert-warning">Já existe NFC-e (CPM) para este pedido. Use Novo cupom para iniciar outra venda.</div>
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="x_panel pdv-panel-produto">
                    <div class="x_title"><h2>Produto</h2><div class="clearfix"></div></div>
                    <div class="x_content pdv-busca-wrap">
                        <div class="pdv-area-listagem">
                            <div class="pdv-busca-linha">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" id="termoProduto" name="termoProduto"
                                        placeholder="Código, descrição ou código de barras"
                                        autocomplete="off">
                                    <span class="input-group-addon" id="spinnerBusca" style="display:none;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="listaProdutos"></div>
                        </div>
                    </div>
                </div>
                <div id="pdvPainelItem" style="display:none;">
                    <p class="pdv-painel-titulo" id="pdvPainelItemTitulo">Confirmar item</p>
                    <p class="text-muted" style="margin:0 0 8px;">
                        <strong id="pdvItemCodigo"></strong> — <span id="pdvItemDescricao"></span>
                    </p>
                    <div class="row">
                        <div class="col-xs-2 col-sm-2">
                            <label for="pdvItemUnidade">Un.</label>
                            <input type="text" class="form-control input-sm" id="pdvItemUnidade" readonly>
                        </div>
                        <div class="col-xs-3 col-sm-2">
                            <label for="pdvItemQuantidade">Quantidade</label>
                            <input type="text" class="form-control input-sm" id="pdvItemQuantidade"
                                value="1" placeholder="0,000" autocomplete="off" inputmode="decimal">
                        </div>
                        <div class="col-xs-3 col-sm-3">
                            <label for="pdvItemUnitario">Valor unitário</label>
                            <input type="text" class="form-control input-sm" id="pdvItemUnitario"
                                placeholder="0,00">
                        </div>
                        <div class="col-xs-2 col-sm-2">
                            <label for="pdvItemTotal">Total</label>
                            <input type="text" class="form-control input-sm" id="pdvItemTotal" readonly>
                        </div>
                        <div class="col-xs-2 col-sm-3 pdv-painel-acoes-col">
                            <label class="pdv-label-acoes">&nbsp;</label>
                            <div class="pdv-botoes-inline">
                                <button type="button" class="btn btn-success btn-sm pdv-btn-icone" id="btnPdvConfirmarItem"
                                    title="Confirmar item" aria-label="Confirmar item">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-sm pdv-btn-icone" id="btnPdvCancelarItem"
                                    title="Cancelar" aria-label="Cancelar">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pdv-col-direita">
                <div class="x_panel pdv-resumo-sidebar">
                    <div class="x_title">
                        <h2>Resumo <span class="badge" id="badgeQtdItens">{$pedido.qtdItens|default:0}</span></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="pdv-resumo-cliente-select">
                            <label for="clientePdv">Cliente</label>
                            <select id="clientePdv" name="clientePdv" class="form-control input-sm" style="width:100%;">
                                {if $pedido.cliente neq '' && $pedido.nomeCliente neq ''}
                                    <option value="{$pedido.cliente}" selected="selected">{$pedido.nomeCliente}</option>
                                {/if}
                            </select>
                        </div>
                        {include file="cupom_pdv_resumo.tpl"}
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

{include file="cupom_pdv_modal.tpl"}

{include file="template/form.inc"}

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/pdv/s_cupom_gerente.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var urlCliente = '{$SCRIPT_NAME}?mod=pdv&form=cupom&submenu=pesquisaClienteAjax&opcao=blank';
    var optCliente = {
        placeholder: 'Digite para buscar cliente',
        allowClear: true,
        minimumInputLength: 3,
        width: '100%',
        ajax: {
            dataType: 'json',
            delay: 250,
            url: urlCliente,
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return { results: data || [] };
            }
        }
    };
    if ($('#clientePdvLista').length) {
        $('#clientePdvLista').select2(optCliente);
    }
    if ($('#clientePdv').length) {
        $('#clientePdv').select2(optCliente);
    }
});
</script>
<script type="text/javascript" src="{$pathJs}/pdv/s_cupom_pdv.js"></script>
