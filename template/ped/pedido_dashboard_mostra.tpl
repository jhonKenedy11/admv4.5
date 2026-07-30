<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_dashboard.js"></script>

<style>
  .ped-dash-row-fixed {
    display: flex;
    flex-wrap: wrap;
  }
  .ped-dash-row-fixed > [class*="col-"] {
    display: flex;
    flex-direction: column;
  }
  .ped-dash-fixed-panel {
    height: 320px; 
    display: flex;
    flex-direction: column;
  }
  .ped-dash-fixed-panel > .x_content {
    flex: 1 1 auto;
    overflow: auto;
  }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Dashboard de pedidos</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="button" class="btn btn-primary" onclick="javascript:submitPedidoDashboard();">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Atualizar
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form id="lancamentoPedidoDash" name="lancamentoPedidoDash" method="post" class="form-horizontal form-label-left" action="{$SCRIPT_NAME}">
                        <input name="mod" type="hidden" value="ped">
                        <input name="form" type="hidden" value="pedido_dashboard">
                        <input name="opcao" type="hidden" value="">
                        <input name="submenu" type="hidden" value="{$subMenu}">
                        <input name="dataIni" type="hidden" value="{$dataIni}">
                        <input name="dataFim" type="hidden" value="{$dataFim}">

                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Per&iacute;odo</label>
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsultaPedidoDash" class="form-control" value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label for="centroCustoPedidoDash">Centro de custo</label>
                            <select class="select2_multiple form-group" multiple="multiple" id="centroCustoPedidoDash" name="centroCusto[]">
                                {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                            </select>
                        </div>
                    </form>

                    {if $mensagem neq ''}
                        <div class="alert alert-info">{$mensagem}</div>
                    {/if}
                    {if isset($pedDashTelhasAviso) && $pedDashTelhasAviso neq ''}
                        <div class="alert alert-warning">{$pedDashTelhasAviso}</div>
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="row top_tiles">
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-shopping-cart blue"></i></div>
                <div class="count">{$kpis.numPedidosFat}</div>
                <h3>Pedidos faturados</h3>
                <p>Qtde no per&iacute;odo (sit. Emitir NF, Pedido, Pedido baixado e Encomenda)</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-money green"></i></div>
                <div class="count">R$ {$kpis.valorFat|number_format:2:",":"."}</div>
                <h3>Valor faturado</h3>
                <p>Soma dos pedidos (sit. Emitir NF, Pedido, Pedido baixado e Encomenda)</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-line-chart gray"></i></div>
                <div class="count">R$ {$kpis.ticketMedio|number_format:2:",":"."}</div>
                <h3>Ticket m&eacute;dio</h3>
                <p>Valor faturado / pedidos</p>
            </div>
        </div>
    </div>

    <div class="row ped-dash-row-fixed">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="x_panel ped-dash-fixed-panel">
                <div class="x_title">
                    <h2>Faturamento por dia</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="chartPedidosDia" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="x_panel ped-dash-fixed-panel">
                <div class="x_title">
                    <h2>Resumo operacional</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {section name=t loop=$total}
                        <table class="table table-striped table-condensed">
                            <tr><td>Valor de venda</td><td class="text-right"><strong>{$total[t].VALORVENDA|number_format:2:",":"."}</strong></td></tr>
                            <tr><td>Lucro bruto</td><td class="text-right">{$total[t].LUCROBRUTO|number_format:2:",":"."}</td></tr>
                            <tr><td>Custo total</td><td class="text-right">{$total[t].CUSTOTOTAL|number_format:2:",":"."}</td></tr>
                            <tr><td>Markup %</td><td class="text-right">{$total[t].MARKUP|number_format:2:",":"."}%</td></tr>
                        </table>
                    {sectionelse}
                        <p class="text-muted">Sem dados para o filtro.</p>
                    {/section}
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="x_panel ped-dash-fixed-panel">
                <div class="x_title">
                    <h2>Faturamento por vendedor</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <canvas id="chartPedidosVendedor" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div {if !isset($forecast) || $forecast eq ''}style="display:none"{/if} class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Forecast</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table table-striped table-condensed table-responsive small">
                                <tbody>
                                    {section name=i loop=$forecast}
                                        <tr><td>META DI&Aacute;RIA</td><td class="text-right">{$forecast[i].METADIARIA|number_format:2:",":"."}</td></tr>
                                        <tr><td>FALTA VOLUME ATINGIMENTO DE META</td><td class="text-right">{$forecast[i].FALTA|number_format:2:",":"."}</td></tr>
                                        <tr><td>PROJE&Ccedil;&Atilde;O DE VALOR DE VENDAS</td><td class="text-right">{$forecast[i].PROJECAOVALORVENDA|number_format:2:",":"."}</td></tr>
                                        <tr><td>PROJE&Ccedil;&Atilde;O DE DESPESAS</td><td class="text-right">{$forecast[i].PROJECAODESPESAS|number_format:2:",":"."}</td></tr>
                                        <tr><td>PROJE&Ccedil;&Atilde;O DE RECEITAS</td><td class="text-right">{$forecast[i].PROJECAORECEITAS|number_format:2:",":"."}</td></tr>
                                        <tr><td>PROJE&Ccedil;&Atilde;O DE LUCRO L&Iacute;QUIDO</td><td class="text-right">{$forecast[i].PROJECAOLUCROLIQUIDO|number_format:2:",":"."}</td></tr>
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <table class="table table-striped table-condensed table-responsive small">
                                <tbody>
                                    {section name=i loop=$forecast}
                                        <tr><td>DIAS RESTANTES FECHAMENTO M&Ecirc;S</td><td class="text-right">{$forecast[i].DIASRESTANTESDOMES|number_format:2:",":"."}</td></tr>
                                        <tr><td>TICKET M&Eacute;DIO DE VENDAS</td><td class="text-right">{$forecast[i].TICKETMEDIODEVENDAS|number_format:2:",":"."}</td></tr>
                                        <tr><td>LUCRO BRUTO M&Eacute;DIO POR VENDA</td><td class="text-right">{$forecast[i].LUCROBRUTOMEDIOPORVENDA|number_format:2:",":"."}</td></tr>
                                        <tr><td>LUCRO L&Iacute;QUIDO M&Eacute;DIO POR VENDA</td><td class="text-right">{$forecast[i].LUCROLIQUIDOMEDIOPORVENDA|number_format:2:",":"."}</td></tr>
                                        <tr><td>N&Uacute;MERO DE VENDAS PROJETADAS</td><td class="text-right">{$forecast[i].NUMERODEVENDASPROJETADAS|string_format:"%d"}</td></tr>
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div {if !isset($projecao) || $projecao eq ''}style="display:none"{/if} class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Proje&ccedil;&atilde;o por vendedor</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content table-responsive">
                    <table class="table table-bordered jambo_table">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th class="text-right">Proj vendas</th>
                                <th class="text-right">N&uacute;m. vendas</th>
                                <th class="text-right">Proj lucro bruto</th>
                                <th class="text-right">Proj lucro l&iacute;quido</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=px loop=$projecao}
                                {assign var="PROJECAOVENDASTOTAL" value=$PROJECAOVENDASTOTAL+$projecao[px].PROJECAOVENDAS}
                                {assign var="NUMERODEVENDASTOTAL" value=$NUMERODEVENDASTOTAL+$projecao[px].NUMERODEVENDAS}
                                {assign var="PROJECAOLUCROBRUTOTOTAL" value=$PROJECAOLUCROBRUTOTOTAL+$projecao[px].PROJECAOLUCROBRUTO}
                                {assign var="PROJECAOLUCROLIQUIDOTOTAL" value=$PROJECAOLUCROLIQUIDOTOTAL+$projecao[px].PROJECAOLUCROLIQUIDO}
                                <tr{if $projecao[px].VENDEDOR|trim|upper eq 'TOTAL'} class="info"{/if}>
                                    <td>{$projecao[px].VENDEDOR}</td>
                                    <td class="text-right">{$projecao[px].PROJECAOVENDAS|number_format:2:",":"."}</td>
                                    <td class="text-right">{$projecao[px].NUMERODEVENDAS|number_format:2:",":"."}</td>
                                    <td class="text-right">{$projecao[px].PROJECAOLUCROBRUTO|number_format:2:",":"."}</td>
                                    <td class="text-right">{$projecao[px].PROJECAOLUCROLIQUIDO|number_format:2:",":"."}</td>
                                </tr>
                            {/section}
                            <tr class="active">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-right"><strong>{$PROJECAOVENDASTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$NUMERODEVENDASTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$PROJECAOLUCROBRUTOTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$PROJECAOLUCROLIQUIDOTOTAL|number_format:2:",":"."}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div {if !isset($metas) || $metas eq ''}style="display:none"{/if} class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Metas</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content table-responsive">
                    <table class="table table-bordered jambo_table">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th class="text-right">Meta venda</th>
                                <th class="text-right">ICM vendas</th>
                                <th class="text-right">Valor vendido</th>
                                <th class="text-right">Custo</th>
                                <th class="text-right">Lucro bruto</th>
                                <th class="text-right">N&uacute;m. vendas</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=mx loop=$metas}
                                <tr{if $metas[mx].VENDEDOR|trim|upper eq 'TOTAL'} class="info"{/if}>
                                    <td>{$metas[mx].VENDEDOR}</td>
                                    <td class="text-right">{$metas[mx].METADEVENDAS|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[mx].ICMVENDAS|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[mx].VALORVENDIDO|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[mx].CUSTOTOTAL|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[mx].LUCROBRUTO|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[mx].NUMVENDAS|number_format:2:",":"."}</td>
                                </tr>
                            {/section}
                            <tr class="active">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.METADEVENDASTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.ICMVENDASTOTALPERC|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.VALORVENDIDOTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.CUSTOTOTALV|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.LUCROBRUTOTOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.NUMVENDASTOTAL|number_format:2:",":"."}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered jambo_table" style="margin-top:16px;">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th class="text-right">MM l&iacute;quida</th>
                                <th class="text-right">ICM</th>
                                <th class="text-right">Margem l&iacute;quida</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=my loop=$metas}
                                <tr{if $metas[my].VENDEDOR|trim|upper eq 'TOTAL'} class="info"{/if}>
                                    <td>{$metas[my].VENDEDOR}</td>
                                    <td class="text-right">{$metas[my].MMLIQUIDA|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[my].ICM|number_format:2:",":"."}</td>
                                    <td class="text-right">{$metas[my].MARGEMLIQUIDA|number_format:2:",":"."}</td>
                                </tr>
                            {/section}
                            <tr class="active">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.MMLIQUIDATOTAL|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.ICMTOTALPERC|number_format:2:",":"."}</strong></td>
                                <td class="text-right"><strong>{$pedDashMetasTotais.MARGEMLIQUIDATOTAL|number_format:2:",":"."}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Pedidos por situa&ccedil;&atilde;o (todas no per&iacute;odo)</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered table-striped jambo_table">
                        <thead>
                            <tr>
                                <th>Situa&ccedil;&atilde;o</th>
                                <th class="text-right">Quantidade</th>
                                <th class="text-right">Valor total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=s loop=$porSituacao}
                                <tr>
                                    <td>{$porSituacao[s].DESCRICAOSIT}</td>
                                    <td class="text-right">{$porSituacao[s].QTD}</td>
                                    <td class="text-right">{$porSituacao[s].VALOR|number_format:2:",":"."}</td>
                                </tr>
                            {sectionelse}
                                <tr><td colspan="3" class="text-center text-muted">Nenhum pedido no per&iacute;odo.</td></tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Top vendedores (faturado)</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Vendedor</th><th class="text-right">Pedidos</th><th class="text-right">Valor</th></tr>
                        </thead>
                        <tbody>
                            {section name=v loop=$topVendedores}
                                <tr>
                                    <td>{$topVendedores[v].VENDEDOR}</td>
                                    <td class="text-right">{$topVendedores[v].QTD}</td>
                                    <td class="text-right">{$topVendedores[v].VALOR|number_format:2:",":"."}</td>
                                </tr>
                            {sectionelse}
                                <tr><td colspan="3" class="text-muted">Sem vendas faturadas no per&iacute;odo.</td></tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Margem por vendedor (faturado)</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Vendedor</th><th class="text-right">Markup %</th><th class="text-right">Margem bruta %</th></tr>
                        </thead>
                        <tbody>
                            {section name=d loop=$totaisDet}
                                <tr>
                                    <td>{$totaisDet[d].VENDEDOR}</td>
                                    <td class="text-right">{$totaisDet[d].MARKUP|number_format:2:",":"."}</td>
                                    <td class="text-right">{$totaisDet[d].MARGEMBRUTA|number_format:2:",":"."}</td>
                                </tr>
                            {sectionelse}
                                <tr><td colspan="3" class="text-muted">Sem dados.</td></tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#centroCustoPedidoDash.select2_multiple").select2({ allowClear: true, width: "100%" });

    var iniTxt = "{$dataIni|escape:'javascript'}";
    var fimTxt = "{$dataFim|escape:'javascript'}";
    var startDate = moment(iniTxt, "DD/MM/YYYY", true);
    var endDate = moment(fimTxt, "DD/MM/YYYY", true);
    if (!startDate.isValid()) {
        startDate = moment().startOf("month");
    }
    if (!endDate.isValid()) {
        endDate = moment();
    }

    $("#dataConsultaPedidoDash").daterangepicker({
        startDate: startDate,
        endDate: endDate,
        ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ]
        },
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Confirma',
            cancelLabel: 'Limpa',
            fromLabel: 'Início',
            toLabel: 'Fim',
            customRangeLabel: 'Calendário',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
            firstDay: 1
        }
    }, function(start, end) {
        var f = document.lancamentoPedidoDash;
        f.dataIni.value = start.format('DD/MM/YYYY');
        f.dataFim.value = end.format('DD/MM/YYYY');
    });

    var f0 = document.lancamentoPedidoDash;
    if (f0) {
        f0.dataIni.value = startDate.format('DD/MM/YYYY');
        f0.dataFim.value = endDate.format('DD/MM/YYYY');
    }

    var ctx = document.getElementById('chartPedidosDia');
    if (ctx) {
        var lbl = {$chartLabels nofilter};
        var vals = {$chartValores nofilter};

        function fmtMoney(v) {
            var n = Number(v);
            if (!isFinite(n)) n = 0;
            return n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: lbl,
                datasets: [{
                    label: 'Valor faturado',
                    data: vals,
                    borderColor: '#26B99A',
                    backgroundColor: 'rgba(38, 185, 154, 0.15)',
                    fill: true,
                    lineTension: 0.2
                }]
            },
            options: {
                responsive: true,
                legend: { display: true },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'R$ ' + fmtMoney(tooltipItem.yLabel);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value) {
                                return fmtMoney(value);
                            }
                        }
                    }]
                }
            }
        });
    }

    var ctxVend = document.getElementById('chartPedidosVendedor');
    if (ctxVend) {
        var vendLbl = {$chartVendLabels|default:'[]' nofilter};
        var vendVals = {$chartVendValores|default:'[]' nofilter};

        new Chart(ctxVend.getContext('2d'), {
            type: 'pie',
            data: {
                labels: vendLbl,
                datasets: [{
                    label: 'Valor faturado',
                    data: vendVals,
                    backgroundColor: [
                        '#2ECC71', '#3498DB', '#9B59B6', '#F1C40F', '#E67E22',
                        '#E74C3C', '#1ABC9C', '#95A5A6', '#34495E', '#7F8C8D'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                legend: { display: true, position: 'bottom' },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var idx = tooltipItem.index;
                            var label = (data.labels && data.labels[idx]) ? data.labels[idx] : '';
                            var value = (data.datasets && data.datasets[0] && data.datasets[0].data) ? Number(data.datasets[0].data[idx] || 0) : 0;
                            var total = 0;
                            if (data.datasets && data.datasets[0] && data.datasets[0].data) {
                                for (var i = 0; i < data.datasets[0].data.length; i++) {
                                    total += Number(data.datasets[0].data[i] || 0);
                                }
                            }
                            var pct = total > 0 ? (value / total) * 100 : 0;
                            return label + ': R$ ' + fmtMoney(value) + ' (' + pct.toFixed(1).replace('.', ',') + '%)';
                        }
                    }
                },
                // Pie não usa scales
            }
        });
    }
});
</script>
