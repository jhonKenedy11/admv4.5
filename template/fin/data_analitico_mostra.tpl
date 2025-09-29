<style>
      .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
      }

      .message-container h4 {
            color: #6c757d;
            font-size: 1.5rem;
            text-align: center;
      }

      .height100 {
            background-color: #F7F7F7;
            margin: 0;
            padding: 10px;
      }

      .print-container {
            display: flex;
            flex-direction: column;
      }

      .header-section {
            margin-bottom: 10px;
      }

      .dataHora {
            font-size: 9px;
      }

      .table {
            font-size: 10px;
            width: 100%;
            table-layout: fixed;
      }

      .table th {
            padding: 2px 3px !important;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
      }

      .table td {
            padding: 2px 3px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
      }

      .ColunaObs {
            min-width: 120px;
            max-width: 300px;
            word-break: break-word;
            white-space: pre-line;
      }

      .x_panel {
            margin-top: 5px;
      }

      .table-responsive {
            overflow-x: auto;
            max-width: 100%;
      }

      h2 {
            font-size: 14px;
            margin: 5px 0;
      }

      @media print {
            @page {
                  margin: 0.3cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .height100 {
                  min-height: auto !important;
                  padding: 2px !important;
            }

            .print-container {
                  page-break-inside: avoid !important;
            }

            .header-section {
                  margin-bottom: 2px !important;
                  padding: 0 !important;
            }

            .x_panel {
                  margin-top: 1px !important;
            }

            .table-responsive {
                  page-break-inside: avoid !important;
            }

            .table {
                  page-break-inside: avoid !important;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
            }

            .ColunaObs {
                  min-width: 120px !important;
                  max-width: 200px !important;
                  word-break: break-word !important;
                  white-space: pre-line !important;
            }

            .no-print {
                  display: none !important;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 10px;
                  margin: 1px 0 !important;
                  line-height: 1.2 !important;
            }

            .col-md-4, .col-md-5, .col-md-3 {
                  padding: 1px !important;
            }

            img {
                  max-width: 100px !important;
                  max-height: 25px !important;
            }

            /* Força que tudo fique junto na primeira página */
            .print-container {
                  page-break-inside: avoid !important;
                  orphans: 0 !important;
                  widows: 0 !important;
            }

            /* Regra específica para evitar quebras no início */
            .height100 {
                  page-break-before: auto !important;
                  page-break-after: avoid !important;
            }

            /* Força que o cabeçalho e a tabela fiquem juntos */
            .header-section + .x_panel {
                  page-break-before: avoid !important;
            }

            /* Evita que o total fique sozinho na última página */
            .table tbody tr:last-child {
                  page-break-after: avoid !important;
                  page-break-before: avoid !important;
            }

            /* Mantém as linhas de total junto com a tabela */
            .table tbody tr:nth-last-child(-n+2) {
                  page-break-inside: avoid !important;
                  page-break-before: avoid !important;
            }

            /* Força que as últimas linhas (totais) fiquem na mesma página */
            .table tbody tr:last-child,
            .table tbody tr:nth-last-child(2) {
                  page-break-before: avoid !important;
                  page-break-after: avoid !important;
            }

            /* Regra específica para linhas com classe ColunaTitulo (totais) */
            .table tbody tr .ColunaTitulo {
                  page-break-before: avoid !important;
                  page-break-after: avoid !important;
            }

            /* Força que a tabela inteira fique junta se possível */
            .table tbody {
                  page-break-inside: avoid !important;
            }

            /* Se a tabela for muito grande, pelo menos mantém os totais juntos */
            .table tbody tr:last-child,
            .table tbody tr:nth-last-child(2),
            .table tbody tr:nth-last-child(3) {
                  page-break-before: avoid !important;
                  page-break-after: avoid !important;
                  page-break-inside: avoid !important;
            }

            /* Regra específica para o grupo de totais */
            .totais-group {
                  page-break-before: avoid !important;
                  page-break-after: avoid !important;
                  page-break-inside: avoid !important;
            }

            /* Força que o grupo de totais fique junto */
            .totais-group:first-of-type {
                  page-break-before: avoid !important;
            }

            .totais-group:last-of-type {
                  page-break-after: avoid !important;
            }
      }
</style>
<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<!-- page content -->
<div class="height100">
  <div class="print-container">
    <div class="header-section">
      <div class="right_col" role="main">
        <div class="">
          <div class="col-md-4 col-sm-4 col-xs-4">
            <img src="images/logo.png" aloign="right" width=180 height=46 border="0">
          </div>
          <div class="col-md-5 col-sm-5 col-xs-5">
            <h2>
              <strong>FINANCEIRO GÊNERO ANALÍTICO</strong><br>
              Período - {$dataInicio} | {$dataFim}
            </h2>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-3">
            <b class="pull-right dataHora">{$dataImp}</b>
          </div>
        </div>
      </div>
    </div>

    <div class="x_panel">
      {if count($lanc) > 0}
        <div class="table-responsive">
          <table class="table table-striped" style="margin-bottom: 0;">
            <thead>
            {assign var="totalGeral" value='0'}
            {assign var="dataAnt" value='0'}
            {assign var="totalParcial" value='0'}

            {section name=i loop=$lanc}

              {assign var="totalGeral" value=$totalGeral+$lanc[i].TOTAL}

              {if $dataAnt eq 0}
                {assign var="dataAnt" value=$lanc[i].DATEORDER}
                {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}

                <tr>
                  <td align=left class=ColunaTitulo colspan="11"><b><big>&raquo; {$lanc[i].FIELDORDER}:
                        {$lanc[i].DATEORDER|date_format:"%d/%m/%Y"}</big></b></td>
                <tr>
                <tr>
                  <th align=center class=ColunaTitulo width="5px">Docto</th>
                  <th align=center class=ColunaTitulo>Filial</th>
                  <th align=center class=ColunaTitulo>Pessoa</th>
                  <th align=center class=ColunaTitulo width="5px">Serie</th>
                  <th align=center class=ColunaTitulo width="5px">Par</th>
                  <th align=center class=ColunaTitulo width="10px">Genero</th>
                  <th align=center class=ColunaTitulo width="10px">Emiss&atilde;o</th>
                  <th align=center class=ColunaTitulo width="10px">Vencimento</th>
                  <th align=center class=ColunaTitulo width="10px">Movimento</th>
                  <th align=center class=ColunaTitulo>Tipo</th>
                  <th align="center" class="ColunaTitulo ColunaObs">Obs</th>
                  <th align=center class=ColunaTitulo width="10px">U.Inclusao</th>
                  <th align=center class=ColunaTitulo width="10px">U.Alteracao</th>
                  <th align=center class=ColunaTitulo>Total</th>
                </tr>
              {elseif $lanc[i].DATEORDER eq $dataAnt}
                {assign var="dataAnt" value=$lanc[i].DATEORDER}
                {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}

              {else}
                {assign var="dataAnt" value=$lanc[i].DATEORDER}
                <tr>
                  <td align=right class=ColunaTitulo colspan="14"><b><big>Total...:
                        R${$totalParcial|number_format:2:",":"."}</big></b></td>
                <tr>
                <tr>
                  <td align=left class=ColunaTitulo colspan="14"><b><big>&raquo; {$lanc[i].FIELDORDER}:
                        {$lanc[i].DATEORDER|date_format:"%d/%m/%Y"}</big></b></td>
                <tr>
                  {assign var="totalParcial" value='0'}

                  {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}
                <tr>
                  <th align=center class=ColunaTitulo width="5px">Docto</th>
                  <th align=center class=ColunaTitulo>Filial</th>
                  <th align=center class=ColunaTitulo>Pessoa</th>
                  <th align=center class=ColunaTitulo width="5px">Serie</th>
                  <th align=center class=ColunaTitulo width="5px">Par</th>
                  <th align=center class=ColunaTitulo width="10px">Genero</th>
                  <th align=center class=ColunaTitulo width="10px">Emiss&atilde;o</th>
                  <th align=center class=ColunaTitulo width="10px">Vencimento</th>
                  <th align=center class=ColunaTitulo width="10px">Movimento</th>
                  <th align=center class=ColunaTitulo>Tipo</th>
                  <th align="center" class="ColunaTitulo ColunaObs">Obs</th>
                  <th align=center class=ColunaTitulo width="10px">U. Inclusao</th>
                  <th align=center class=ColunaTitulo width="10px">U. Alteracao</th>
                  <th align=center class=ColunaTitulo>Total</th>
                </tr>
              {/if}

              <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">

                <td width="5px"> {$lanc[i].DOCTO} </td>
                <td> {$lanc[i].FILIAL} </td>
                <td> {$lanc[i].NOME} </td>
                <td width="5px"> {$lanc[i].SERIE} </td>
                <td width="5px"> {$lanc[i].PARCELA} </td>
                <td width="10px"> {$lanc[i].DESCGENERO} </td>
                <td width="10px"> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                <td width="10px"> {$lanc[i].VENCIMENTO|date_format:"%d/%m/%Y"} </td>
                <td width="10px"> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                <td> {$lanc[i].TIPOLANCAMENTO} </td>
                <td class="ColunaObs"> {$lanc[i].OBS} </td>
                <td width="10px"> {$lanc[i].NOMEREDUZIDO_INSERT}</td>
                <td width="10px"> {$lanc[i].NOMEREDUZIDOALTERACAO}</td>
                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

              </tr>

              {sectionelse}
              <tr>
                <td colspan="14" class="text-center">Não há valores cadastrados para este período</td>
              </tr>
              {/section}

              <tr class="totais-group">
                <td align=right class=ColunaTitulo colspan="14"><b><big>Total...:
                      R${$totalParcial|number_format:2:",":"."}</big></b></td>
              </tr>
              <tr class="totais-group">
                <td align=right class=ColunaTitulo colspan="14"><b><big>TOTAL GERAL...:
                      R${$totalGeral|number_format:2:",":"."}</big></b></td>
              </tr>


            </tbody>
          </table>
        </div>
      {else}
        <div class="message-container">
          <h4>Nenhum registro localizado!</h4>
        </div>
      {/if}
    </div>

    <div class="row no-print">
      <div class="col-xs-12 text-center">
        <button class="btn btn-default" onclick="window.print();">
          <i class="fa fa-print"></i> Imprimir
        </button>
        <button class="btn btn-success" onclick="exportarTabelaParaExcel();">
          <i class="fa fa-file-excel-o"></i> Exportar Excel
        </button>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script type="text/javascript">
  function exportarTabelaParaExcel() {
    // Pega a tabela que já está sendo exibida
    var table = document.querySelector('.table-striped');
    if (!table) {
      alert('Tabela não encontrada!');
      return;
    }
    
    // Converte a tabela para CSV
    var csv = '';
    var rows = table.querySelectorAll('tr');
    
    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];
      var cells = row.querySelectorAll('td, th');
      var rowData = [];
      
      for (var j = 0; j < cells.length; j++) {
        var cellText = cells[j].textContent.trim();
        // Escapa vírgulas e aspas
        if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
          cellText = '"' + cellText.replace(/"/g, '""') + '"';
        }
        rowData.push(cellText);
      }
      
      csv += rowData.join(',') + '\n';
    }
    
    // Cria o blob e faz o download
    var blob = new Blob([csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Financeiro_Analitico_{$dataInicio}_a_{$dataFim}.csv';
    link.click();
  }

  // debitos / creditos
  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          suggestedMax: 10000,
          maxTicksLimit: 8
        }
      }]
    },
    responsive: true
  };

  var data = {
    labels: {$label},
    datasets: [{
        label: "Debitos",
        fillColor: "rgba(255,10,0,0.2)",
        strokeColor: "rgba(255,0,0,1)",
        pointColor: "rgba(255,0,0,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: {$pag}
      },
      {
        label: "Creditos",
        fillColor: "rgba(151,187,205,0.2)",
        strokeColor: "rgba(151,187,205,1)",
        pointColor: "rgba(151,187,205,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(151,187,205,1)",
        data: {$rec}
      }
    ]
  };

  // saldos
  //barShowStroke: false,
  //scaleBeginAtZero : false,
  //scaleOverride: true,
  //scaleSteps: 20,
  //scaleStepWidth: 2,
  //scaleStartValue: -20,
  //responsive: true,
  //barBeginAtOrigin: true,

  var optionsSaldo = {
    responsive: true,
  };

  var dataSaldo = {
    labels: {$labelSaldo},
    datasets: [{
      label: "Saldo",
      fillColor: "rgba(255,10,0,0.2)",
      strokeColor: "rgba(255,0,0,1)",
      pointColor: "rgba(255,0,0,1)",
      pointStrokeColor: "#fff",
      pointHighlightFill: "#fff",
      pointHighlightStroke: "rgba(220,220,220,1)",
      data: {$saldo}
    }]
  };



  window.onload = function() {

    var ctx = document.getElementById("lineChart").getContext("2d");
    var LineChart = new Chart(ctx).Line(data, options);
    var ctxBar = document.getElementById("barChart").getContext("2d");
    var BarChart = new Chart(ctxBar).Bar(dataSaldo, optionsSaldo);
  }
</script>