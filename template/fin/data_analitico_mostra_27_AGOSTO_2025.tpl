<style>
@media print {
  .no-print {
    display: none !important;
  }
  .ColunaObs {
    min-width: 120px !important;
    max-width: 200px !important;
    word-break: break-word !important;
    white-space: pre-line !important;
  }
}
.ColunaObs {
  min-width: 120px;
  max-width: 300px;
  word-break: break-word;
  white-space: pre-line;
}
</style>
<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<!-- page content -->
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

  <section class="content invoice">
    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table">
        <table class="table table-striped">
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

              <p>
                {sectionelse}
                <td>n&atilde;o h&aacute; valores Cadastrados para este per&iacute;odo</td>

              {/section}

              <tr>
                <td align=right class=ColunaTitulo colspan="14"><b><big>Total...:
                      R${$totalParcial|number_format:2:",":"."}</big></b></td>
              </tr>
              <tr>
                <td align=right class=ColunaTitulo colspan="14"><b><big>TOTAL GERAL...:
                      R${$totalGeral|number_format:2:",":"."}</big></b></td>
              </tr>


              </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->


    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
      </div>
    </div>
  </section>
  <!--/div>
              </div-->
</div>
</div>
</div>
<!-- /page content -->

<script type="text/javascript">
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