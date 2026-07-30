<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_estoque_dashboard.js"></script>

<style>
    @media (min-width: 992px) {
        .estoque-dashboard-eq {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
        }

        .estoque-dashboard-eq > [class*="col-"] {
            display: flex;
            flex-direction: column;
        }

        .estoque-dashboard-eq .x_panel {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
            min-height: 360px;
        }

        .estoque-dashboard-eq .x_panel > .x_content {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
        }

        .estoque-dashboard-eq .x_panel > .x_content {
            overflow: hidden;
        }

        .estoque-chart-wrap {
            flex: 1 1 auto;
            position: relative;
            min-height: 0;
        }

        .estoque-chart-wrap > canvas {
            position: absolute;
            inset: 0;
            width: 100% !important;
            height: 100% !important;
        }
    }
</style>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Dashboard de estoque</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="button" class="btn btn-primary" onclick="javascript:submitEstoqueDashboard();">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Atualizar
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form id="lancamentoEstoqueDash" name="lancamentoEstoqueDash" method="post" class="form-horizontal form-label-left" action="{$SCRIPT_NAME}">
                        <input name="mod" type="hidden" value="est">
                        <input name="form" type="hidden" value="estoque_dashboard">
                        <input name="opcao" type="hidden" value="">
                        <input name="submenu" type="hidden" value="{$subMenu}">
                        <input name="dataIni" type="hidden" value="{$dataIni}">
                        <input name="dataFim" type="hidden" value="{$dataFim}">

                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Per&iacute;odo</label>
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsultaEstoqueDash" class="form-control" value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label for="centroCustoEstoqueDash">Centro de custo</label>
                            <select class="select2_multiple form-group" multiple="multiple" id="centroCustoEstoqueDash" name="centroCusto[]">
                                {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                            </select>
                        </div>
                    </form>

                    {if $mensagem neq ''}
                        <div class="alert alert-info">{$mensagem}</div>
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="row top_tiles">
        {section name=k loop=$kpiCards}
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="{$kpiCards[k].icone}"></i></div>
                    <div class="count">{$kpiCards[k].valor}</div>
                    <h3>{$kpiCards[k].titulo}</h3>
                    <p>{$kpiCards[k].descricao}</p>
                </div>
            </div>
        {/section}
    </div>

    <div class="row estoque-dashboard-eq">
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Movimento por dia</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="estoque-chart-wrap">
                        <canvas id="chartMovEstoqueDia"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sa&iacute;das por grupo</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="estoque-chart-wrap">
                        <canvas id="chartSaidasGrupoPie"></canvas>
                    </div>
                    <p class="text-muted small">Participa&ccedil;&atilde;o das sa&iacute;das por grupo no per&iacute;odo (TOP + Outros).</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sem estoque</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-condensed">
                        <tr><td>Produtos sem estoque</td><td class="text-right"><strong>{$alertas.produtosSemEstoque}</strong></td></tr>
                    </table>
                    <p class="text-muted small">Considera produtos ativos e sem unidade dispon&iacute;vel no(s) centro(s) selecionado(s).</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Produtos abaixo do m&iacute;nimo</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered table-striped jambo_table">
                        <thead>
                            <tr>
                                <th>C&oacute;digo</th>
                                <th>Produto</th>
                                <th>Grupo</th>
                                <th class="text-right">M&iacute;n.</th>
                                <th class="text-right">Estoque</th>
                                <th class="text-right">Reserva</th>
                                <th class="text-right">Dispon&iacute;vel</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=a loop=$abaixoMinimoLista}
                                <tr>
                                    <td>{$abaixoMinimoLista[a].CODIGO}</td>
                                    <td>{$abaixoMinimoLista[a].DESCRICAO}</td>
                                    <td>{$abaixoMinimoLista[a].GRUPO}</td>
                                    <td class="text-right">{$abaixoMinimoLista[a].MINIMO|number_format:2:",":"."}</td>
                                    <td class="text-right">{$abaixoMinimoLista[a].ESTOQUE}</td>
                                    <td class="text-right">{$abaixoMinimoLista[a].RESERVA}</td>
                                    <td class="text-right"><strong>{$abaixoMinimoLista[a].DISPONIVEL}</strong></td>
                                </tr>
                            {sectionelse}
                                <tr><td colspan="7" class="text-center text-muted">Nenhum item abaixo do m&iacute;nimo para o filtro.</td></tr>
                            {/section}
                        </tbody>
                    </table>
                    <p class="text-muted small">Lista limitada aos 25 mais cr&iacute;ticos (maior falta para atingir o m&iacute;nimo).</p>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Produtos com mais sa&iacute;das no per&iacute;odo</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-right">Qtde</th>
                                <th class="text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=t loop=$topSaidas}
                                <tr>
                                    <td>
                                        <span class="text-muted small">{$topSaidas[t].CODIGO}</span>
                                        {$topSaidas[t].DESCRICAO}
                                        {if $topSaidas[t].UNIDADE neq ''}<span class="text-muted small">({$topSaidas[t].UNIDADE})</span>{/if}
                                    </td>
                                    <td class="text-right"><strong>{$topSaidas[t].QUANT|number_format:2:",":"."}</strong></td>
                                    <td class="text-right">{$topSaidas[t].VALOR|number_format:2:",":"."}</td>
                                </tr>
                            {sectionelse}
                                <tr><td colspan="3" class="text-center text-muted">Sem sa&iacute;das no per&iacute;odo.</td></tr>
                            {/section}
                        </tbody>
                    </table>
                    <p class="text-muted small">Base: notas fiscais de sa&iacute;da no per&iacute;odo. Limite 25.</p>
                </div>
            </div>
        </div>
    </div>

</div>

{include file="template/database.inc"}

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#centroCustoEstoqueDash.select2_multiple").select2({ allowClear: true, width: "100%" });

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

    $("#dataConsultaEstoqueDash").daterangepicker({
        startDate: startDate,
        endDate: endDate,
        ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    }, function(start, end) {
        var f = document.lancamentoEstoqueDash;
        f.dataIni.value = start.format('DD/MM/YYYY');
        f.dataFim.value = end.format('DD/MM/YYYY');
    });

    var f0 = document.lancamentoEstoqueDash;
    if (f0) {
        f0.dataIni.value = startDate.format('DD/MM/YYYY');
        f0.dataFim.value = endDate.format('DD/MM/YYYY');
    }

    var ctx = document.getElementById('chartMovEstoqueDia');
    if (ctx) {
        var lbl = {$chartLabels nofilter};
        var ent = {$chartEntradas nofilter};
        var sai = {$chartSaidas nofilter};
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: lbl,
                datasets: [{
                    label: 'Entradas (quant.)',
                    data: ent,
                    borderColor: '#3e95cd',
                    backgroundColor: 'rgba(62, 149, 205, 0.12)',
                    fill: true,
                    lineTension: 0.2
                },{
                    label: 'Saídas (quant.)',
                    data: sai,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.10)',
                    fill: true,
                    lineTension: 0.2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: true },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value) {
                                return Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 0 });
                            }
                        }
                    }]
                }
            }
        });
    }

    var pie = document.getElementById('chartSaidasGrupoPie');
    if (pie) {
        var pl = {$pieLabels nofilter};
        var pv = {$pieValores nofilter};
        var colors = ['#3e95cd','#8e5ea2','#3cba9f','#e8c3b9','#c45850','#26B99A','#f39c12','#9b59b6','#3498db','#95a5a6'];
        new Chart(pie.getContext('2d'), {
            type: 'pie',
            data: {
                labels: pl,
                datasets: [{
                    data: pv,
                    backgroundColor: colors.slice(0, Math.max(pl.length, 1))
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom' },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var val = data.datasets[0].data[tooltipItem.index] || 0;
                            return label + ': ' + Number(val).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        }
                    }
                }
            }
        });
    }
});
</script>

