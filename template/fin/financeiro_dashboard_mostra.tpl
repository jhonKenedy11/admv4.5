<style>
  .select2-selection--multiple{ border-radius: 8px !important; }
  #dataConsulta, .x_panel{ border-radius: 8px !important; }
  .tile-stats{ border-radius: 8px !important; }
  .small-muted{ color:#73879C; font-size: 12px; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_financeiro_dashboard.js"></script>

<style>
  @media (min-width: 992px) {
    .fin-dash-eq {
      display: flex;
      flex-wrap: wrap;
      align-items: stretch;
    }

    .fin-dash-eq > [class*="col-"] {
      display: flex;
      flex-direction: column;
    }

    .fin-dash-eq .x_panel {
      height: 380px;
      display: flex;
      flex-direction: column;
      margin-bottom: 0;
    }

    .fin-dash-eq .x_panel > .x_content {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .fin-chart-wrap {
      flex: 1 1 auto;
      position: relative;
      min-height: 0;
    }

    .fin-chart-wrap > canvas {
      position: absolute;
      inset: 0;
      width: 100% !important;
      height: 100% !important;
    }
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
  <input name=mod type=hidden value="fin">
  <input name=form type=hidden value="financeiro_dashboard">
  <input name=submenu type=hidden value="{$subMenu}">
  <input name=dataIni type=hidden value="{$dataIni}">
  <input name=dataFim type=hidden value="{$dataFim}">

  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Dashboard financeiro</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li>
                <button type="button" class="btn btn-primary" onclick="javascript:submitFinanceiroDashboard();">
                  <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Atualizar
                </button>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            <div class="row">
              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <label>Per&iacute;odo</label>
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <div>
                  <input type="text" name="dataConsulta" id="dataConsulta" class="form-control" value="{$dataIni} - {$dataFim}">
                </div>
                <div class="small-muted">Carteira por vencimento (em aberto) e baixas por pagamento</div>
              </div>

              <div class="form-group col-md-9 col-sm-12 col-xs-12">
                <label for="centroCusto">Centro de custo</label>
                <select class="select2_multiple form-control" multiple="multiple" id="centroCusto" name="centroCusto[]">
                  {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                </select>
              </div>
            </div>

            <div class="row tile_count" style="margin-top:10px;">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-arrow-down red"></i></div>
                  <div class="count">{$kpis.pagarAberto|number_format:2:",":"."}</div>
                  <h3>A pagar em aberto</h3>
                  <p>Vencimento no per&iacute;odo</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-arrow-up green"></i></div>
                  <div class="count">{$kpis.receberAberto|number_format:2:",":"."}</div>
                  <h3>A receber em aberto</h3>
                  <p>Vencimento no per&iacute;odo</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check blue"></i></div>
                  <div class="count">{$kpis.saldoLiquidoPeriodo|number_format:2:",":"."}</div>
                  <h3>Saldo l&iacute;quido</h3>
                  <p>Recebido - pago no per&iacute;odo</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-exclamation-triangle orange"></i></div>
                  <div class="count">{$kpis.pagarAtrasadoQtd + $kpis.receberAtrasadoQtd}</div>
                  <h3>Vencidos</h3>
                  <p>{$kpis.pagarAtrasadoQtd} a pagar / {$kpis.receberAtrasadoQtd} a receber</p>
                </div>
              </div>
            </div>

            <div class="row fin-dash-eq">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Carteira em aberto por dia</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="fin-chart-wrap">
                      <canvas id="chartCarteiraDia"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>G&ecirc;neros de despesas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="fin-chart-wrap">
                      <canvas id="pieGeneroDesp"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>G&ecirc;neros de receitas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="fin-chart-wrap">
                      <canvas id="pieGeneroRec"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Baixados recentes</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Pagamento</th>
                          <th>Tipo</th>
                          <th>Pessoa</th>
                          <th>G&ecirc;nero</th>
                          <th>Centro</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$baixadosRecentes}
                          <tr>
                            <td>{$baixadosRecentes[i].PAGAMENTO|date_format:"%d/%m/%Y"}</td>
                            <td>{if $baixadosRecentes[i].TIPOLANCAMENTO eq 'R'}Receb.{else}Pag.{/if}</td>
                            <td>{$baixadosRecentes[i].PESSOA_NOME}</td>
                            <td>{$baixadosRecentes[i].GENERO_DESC}</td>
                            <td>{$baixadosRecentes[i].CENTROCUSTO_DESC}</td>
                            <td>{$baixadosRecentes[i].ORIGINAL|number_format:2:",":"."}</td>
                          </tr>
                        {/section}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Vencidos a pagar</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Venc.</th>
                          <th>Pessoa</th>
                          <th>G&ecirc;nero</th>
                          <th>Centro</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$vencidosPagar}
                          <tr>
                            <td>{$vencidosPagar[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                            <td>{$vencidosPagar[i].PESSOA_NOME}</td>
                            <td>{$vencidosPagar[i].GENERO_DESC}</td>
                            <td>{$vencidosPagar[i].CENTROCUSTO_DESC}</td>
                            <td>{$vencidosPagar[i].TOTAL|number_format:2:",":"."}</td>
                          </tr>
                        {/section}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Vencidos a receber</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Venc.</th>
                          <th>Pessoa</th>
                          <th>G&ecirc;nero</th>
                          <th>Centro</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$vencidosReceber}
                          <tr>
                            <td>{$vencidosReceber[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                            <td>{$vencidosReceber[i].PESSOA_NOME}</td>
                            <td>{$vencidosReceber[i].GENERO_DESC}</td>
                            <td>{$vencidosReceber[i].CENTROCUSTO_DESC}</td>
                            <td>{$vencidosReceber[i].TOTAL|number_format:2:",":"."}</td>
                          </tr>
                        {/section}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  {include file="template/database.inc"}
</form>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<script>
$(document).ready(function() {
  $("#centroCusto.select2_multiple").select2({ allowClear: true, width: "100%" });
});
</script>

<script type="text/javascript">
$('input[name="dataConsulta"]').daterangepicker({
    startDate: moment("{$dataIni}", "DD/MM/YYYY"),
    endDate: moment("{$dataFim}", "DD/MM/YYYY"),
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
        applyLabel: 'Confirma',
        cancelLabel: 'Limpa',
        fromLabel: 'Início',
        toLabel: 'Fim',
        customRangeLabel: 'Calendário',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        firstDay: 1
    }
  },
  function(start, end, label) {
    f = document.lancamento;
    f.dataIni.value = start.format('DD/MM/YYYY');
    f.dataFim.value = end.format('DD/MM/YYYY');
});
</script>

<script>
(function () {
  function formatDateLabelBR(s) {
    if (!s) return s;
    // Normaliza datas vindas do banco (ex.: 2026-04-01) para padrão BR.
    if (typeof s === 'string' && /^\d{4}-\d{2}-\d{2}/.test(s)) {
      var m = moment(s, ['YYYY-MM-DD', 'YYYY-MM-DD HH:mm:ss', moment.ISO_8601], true);
      if (m.isValid()) return m.format('DD/MM');
    }
    return s;
  }

  function formatBRL(value) {
    var n = Number(value);
    if (!isFinite(n)) n = 0;
    return n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
  }

  var labels = {$chartLabels|default:'[]'};
  if (typeof labels === 'string') {
    try { labels = JSON.parse(labels); } catch (e) {}
  }
  if (Array.isArray(labels)) labels = labels.map(formatDateLabelBR);
  var receb = {$chartReceb|default:'[]'};
  var pag = {$chartPag|default:'[]'};

  var ctx = document.getElementById('chartCarteiraDia');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'A receber (aberto)',
          data: receb,
          borderColor: '#2ECC71',
          backgroundColor: 'rgba(46,204,113,0.12)',
          pointRadius: 2,
          fill: true,
          lineTension: 0.25
        },{
          label: 'A pagar (aberto)',
          data: pag,
          borderColor: '#E74C3C',
          backgroundColor: 'rgba(231,76,60,0.10)',
          pointRadius: 2,
          fill: true,
          lineTension: 0.25
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          callbacks: {
            title: function(tooltipItems, data) {
              var idx = tooltipItems && tooltipItems.length ? tooltipItems[0].index : null;
              var raw = (idx != null && data.labels) ? data.labels[idx] : '';
              return formatDateLabelBR(raw);
            },
            label: function(tooltipItem, data) {
              var ds = data.datasets[tooltipItem.datasetIndex] || {};
              var lbl = ds.label ? (ds.label + ': ') : '';
              return lbl + formatBRL(tooltipItem.yLabel);
            }
          }
        },
        scales: {
          xAxes: [{
            ticks: {
              callback: function(value) { return formatDateLabelBR(value); }
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              callback: function(value) { return formatBRL(value); }
            }
          }]
        }
      }
    });
  }

  function renderPie(id, pieLabels, pieValores) {
    var el = document.getElementById(id);
    if (!el) return;
    var cores = ['#3498DB','#2ECC71','#9B59B6','#F1C40F','#E67E22','#E74C3C','#1ABC9C','#95A5A6','#34495E','#7F8C8D'];
    new Chart(el, {
      type: 'pie',
      data: {
        labels: pieLabels,
        datasets: [{
          data: pieValores,
          backgroundColor: pieLabels.map(function(_, i){ return cores[i % cores.length]; })
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
              var value = data.datasets[0].data[tooltipItem.index] || 0;
              return label + ': ' + formatBRL(value);
            }
          }
        }
      }
    });
  }

  renderPie('pieGeneroDesp', {$pieDespLabels|default:'[]'}, {$pieDespValores|default:'[]'});
  renderPie('pieGeneroRec', {$pieRecLabels|default:'[]'}, {$pieRecValores|default:'[]'});
})();
</script>

