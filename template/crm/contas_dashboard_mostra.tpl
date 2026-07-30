<style>
  .select2-selection--multiple{ border-radius: 8px !important; }
  #dataConsulta, .x_panel{ border-radius: 8px !important; }
  .tile-stats{ border-radius: 8px !important; }
  .table{ background: #fff; }
  .small-muted{ color:#73879C; font-size: 12px; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/crm/s_contas_dashboard.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
  <input name=mod type=hidden value="crm">
  <input name=form type=hidden value="contas_dashboard">
  <input name=submenu type=hidden value="{$subMenu}">
  <input name=dataIni type=hidden value="{$dataIni}">
  <input name=dataFim type=hidden value="{$dataFim}">

  <div class="right_col" role="main">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Dashboard de contas</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li>
                <button type="button" class="btn btn-primary" onclick="javascript:submitContasDashboard();">
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
                <div class="small-muted">Base: cadastros e acompanhamentos no per&iacute;odo</div>
              </div>

              <div class="form-group col-md-5 col-sm-12 col-xs-12">
                <label for="centroCusto">Centro de custo</label>
                <select class="select2_multiple form-control" multiple="multiple" id="centroCusto" name="centroCusto[]">
                  {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                </select>
              </div>

              <div class="form-group col-md-4 col-sm-12 col-xs-12">
                <label for="responsavel">Respons&aacute;vel</label>
                <select class="select2_multiple form-control" multiple="multiple" id="responsavel" name="responsavel">
                  {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                </select>
                <div class="small-muted">Filtra por respons&aacute;vel da conta e/ou do acompanhamento</div>
              </div>
            </div>

            <div class="row tile_count" style="margin-top:10px;">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-users blue"></i></div>
                  <div class="count">{$kpis.contasTotal}</div>
                  <h3>Contas</h3>
                  <p>Total no filtro (respons&aacute;vel/centro)</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-user-plus green"></i></div>
                  <div class="count">{$kpis.contasNovas}</div>
                  <h3>Novas contas</h3>
                  <p>Cadastradas no per&iacute;odo</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-comments-o orange"></i></div>
                  <div class="count">{$kpis.acompPeriodo}</div>
                  <h3>Acompanhamentos</h3>
                  <p>Registrados no per&iacute;odo</p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-exclamation-triangle red"></i></div>
                  <div class="count">{$kpis.contatosAtrasados}</div>
                  <h3>Contatos atrasados</h3>
                  <p>Pr&oacute;ximo contato antes de agora</p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Acompanhamentos por dia</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <canvas id="chartAcompDia" height="120"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Acompanhamentos por atividade</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <canvas id="chartAcompAtividadePie" height="256"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Novas contas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Conta</th>
                          <th>Nome</th>
                          <th>Centro</th>
                          <th>Respons&aacute;vel</th>
                          <th>Cadastro</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$novasContas}
                          <tr>
                            <td>{$novasContas[i].CLIENTE}</td>
                            <td>{$novasContas[i].NOME}</td>
                            <td>{$novasContas[i].CENTROCUSTO_DESC}</td>
                            <td>{$novasContas[i].RESPONSAVEL}</td>
                            <td>{$novasContas[i].DATEINSERT}</td>
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
                    <h2>Pr&oacute;ximos contatos</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Data</th>
                          <th>Conta</th>
                          <th>Atividade</th>
                          <th>Respons&aacute;vel</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$proximosContatos}
                          <tr>
                            <td>{$proximosContatos[i].LIGARDIA}</td>
                            <td>{$proximosContatos[i].NOME}</td>
                            <td>{$proximosContatos[i].ATIVIDADE_DESC}</td>
                            <td>{$proximosContatos[i].RESPONSAVEL}</td>
                          </tr>
                        {/section}
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
                    <h2>Acompanhamentos recentes</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Data</th>
                          <th>Conta</th>
                          <th>Atividade</th>
                          <th>Respons&aacute;vel</th>
                          <th>Resumo</th>
                        </tr>
                      </thead>
                      <tbody>
                        {section name=i loop=$acompRecentes}
                          <tr>
                            <td>{$acompRecentes[i].DATA}</td>
                            <td>{$acompRecentes[i].NOME}</td>
                            <td>{$acompRecentes[i].ATIVIDADE_DESC}</td>
                            <td>{$acompRecentes[i].RESPONSAVEL}</td>
                            <td>{$acompRecentes[i].RESULTADO}</td>
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
  $("#responsavel.select2_multiple").select2({ maximumSelectionLength: 1, allowClear: true, width: "100%" });
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
  var labels = {$chartAcompLabels|default:'[]'};
  var valores = {$chartAcompValores|default:'[]'};

  var ctx = document.getElementById('chartAcompDia');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Acompanhamentos',
          data: valores,
          borderColor: '#3498DB',
          backgroundColor: 'rgba(52,152,219,0.15)',
          pointRadius: 2,
          fill: true,
          lineTension: 0.25
        }]
      },
      options: {
        responsive: true,
        scales: {
          yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
        }
      }
    });
  }

  var pieLabels = {$pieLabels|default:'[]'};
  var pieValores = {$pieValores|default:'[]'};
  var pie = document.getElementById('chartAcompAtividadePie');
  if (pie) {
    var cores = ['#2ECC71','#3498DB','#9B59B6','#F1C40F','#E67E22','#E74C3C','#1ABC9C','#95A5A6','#34495E','#7F8C8D'];
    new Chart(pie, {
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
        legend: { position: 'bottom' },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var label = data.labels[tooltipItem.index] || '';
              var value = data.datasets[0].data[tooltipItem.index] || 0;
              return label + ': ' + value;
            }
          }
        }
      }
    });
  }
})();
</script>

