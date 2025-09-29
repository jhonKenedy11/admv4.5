<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
                <div class="x_panel">
                  <div class="x_content">


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>  Financeiro Centro de Custo Anal&iacute;tico</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>
                            

                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>Financeiro Centro Custo</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <canvas id="barChart"></canvas>
                          </div>
                        </div>
                      </div>                            
                           
                            
                            <br>     
                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <!-- info row -->
                          <div class="row invoice-info">
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                              <H2>Saldo Inicial: R$ {$saldoInicial|number_format:2:",":"."}</H2>
                            </div>
                            <!-- /.col -->
                          </div>
                          <!-- /.row -->

                          <table class="table table-striped small">
                            <thead>
                                {assign var="ccAnt" value='0'}
                                {assign var="valorDebito" value=0}
                                {assign var="valorCredito" value=0}
                                {assign var="saldoParcial" value=0}
                                {assign var="totalDebito" value=0}
                                {assign var="totalCredito" value=0}
                                {assign var="totalSaldo" value=0}
                                {assign var="saldoCC" value=0}

                                {section name=i loop=$lanc}

                                  {assign var="totalGeral" value=$totalGeral+$lanc[i].TOTALRATEIO}

                                  {if $ccAnt neq $lanc[i].CC}
                                    {if $ccAnt neq 0}
                                      {$saldoParcial=$saldoCC+$valorCredito-$valorDebito}
                                      <tr>
                                          <td align=right class=ColunaTitulo colspan="7"><b><big>Saldo Inicial CC...: R${$saldoCC|number_format:2:",":"."}</big></b></td>
                                          <td align=right class=ColunaTitulo><b><big>Débitos...: R${$valorDebito|number_format:2:",":"."}</big></b></td>
                                          <td align=right class=ColunaTitulo><b><big>Créditos...: R${$valorCredito|number_format:2:",":"."}</big></b></td>
                                          <td align=right class=ColunaTitulo colspan="3"><b><big>Saldo...: R${$saldoParcial|number_format:2:",":"."}</big></b></td>
                                      <tr>
                                      {$valorDebito=0}
                                      {$valorCredito=0}
                                      {$saldoParcial=0}
                                    
                                    {/if}    

                                    {$saldoCC=$lanc[i].SALDOCC}
                                    {$ccAnt=$lanc[i].CC}

                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="9"><b><big>&raquo; {$lanc[i].CC} - {$lanc[i].DESCCENTROCUSTO}</big></b></td>
                                    <tr {$hidden}>
                                        <th align=center class=ColunaTitulo>Pessoa</td>
                                        <th align=center class=ColunaTitulo>Docto</td>
                                        <th align=center class=ColunaTitulo>Serie</td>
                                        <th align=center class=ColunaTitulo>Par</td>
                                        <th align=center class=ColunaTitulo>Genero</td>
                                        <th align=center class=ColunaTitulo>Emiss&atilde;o</td>
                                        <th align=center class=ColunaTitulo>Vencimento</td>
                                        <th align=center class=ColunaTitulo>Movimento</td>
                                        <th align=center class=ColunaTitulo>Tipo</td>
                                        <th align=center class=ColunaTitulo>Total Título</td>
                                        <th align=center class=ColunaTitulo>% Rateio</td>
                                        <th align=center class=ColunaTitulo>Total Rateio</td>
                                    </tr>

                                  {/if}

                                  {if $lanc[i].TIPOLANCAMENTO eq "RECEBIMENTO"}
                                    {$valorCredito=$valorCredito+$lanc[i].TOTALRATEIO}
                                    {$totalCredito=$totalCredito+$lanc[i].TOTALRATEIO}
                                  {else}
                                    {$valorDebito=$valorDebito+$lanc[i].TOTALRATEIO}
                                    {$totalDebito=$totalDebito+$lanc[i].TOTALRATEIO}
                                  {/if}
                                  <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha" {$hidden}>

                                          <td><a href=javascript:submitAlterarRel({$lanc[i].ID})> {$lanc[i].NOME} </a></td>
                                          <td> {$lanc[i].DOCTO} </td>
                                          <td> {$lanc[i].SERIE} </td>
                                          <td> {$lanc[i].PARCELA} </td>
                                          <td> {$lanc[i].DESCGENERO} </td>
                                          <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                          <td> {$lanc[i].VENCIMENTO|date_format:"%d/%m/%Y"} </td>
                                          <td> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                          <td> {$lanc[i].TIPOLANCAMENTO} </td>
                                          <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                          <td> {$lanc[i].PERCENTUAL|number_format:2:",":"."} </td>
                                          <td> {$lanc[i].TOTALRATEIO|number_format:2:",":"."} </td>

                                  </tr>

                                  <p>
                                  {sectionelse}
                                  <td>n&atilde;o h&aacute; valores Cadastrados para este per&iacute;odo</td>

                                {/section}

                                {$saldoParcial=$saldoCC+$valorCredito-$valorDebito}
                                <tr>
                                    <td align=right class=ColunaTitulo colspan="7"><b><big>Saldo Inicial CC...: R${$saldoCC|number_format:2:",":"."}</big></b></td>
                                    <td align=right class=ColunaTitulo><b><big>Débitos...: R${$valorDebito|number_format:2:",":"."}</big></b></td>
                                    <td align=right class=ColunaTitulo><b><big>Créditos...: R${$valorCredito|number_format:2:",":"."}</big></b></td>
                                    <td align=right class=ColunaTitulo colspan="3"><b><big>Saldo...: R${$saldoParcial|number_format:2:",":"."}</big></b></td>
                                <tr>

                                {$totalSaldo=$saldoInicial+$totalCredito-$totalDebito}
                                <tr>
                                    <td align=right class=ColunaTitulo colspan="7"><b><big>TOTAL GERAL (Saldo Inicial + Créditos - Débitos)</big></b></td>
                                    <td align=right class=ColunaTitulo><b><big>Débitos...: R${$totalDebito|number_format:2:",":"."}</big></b></td>
                                    <td align=right class=ColunaTitulo><b><big>Créditos...: R${$totalCredito|number_format:2:",":"."}</big></b></td>
                                    <td align=right class=ColunaTitulo colspan="3"><b><big>Saldo...: R${$totalSaldo|number_format:2:",":"."}</big></b></td>
                                <tr>



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
                  </div>
                </div>
              </div>
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
                            suggestedMax : 10000,
                            maxTicksLimit: 8
                          }
                        }]
                      },                    
                    responsive:true
                };

                var data = {
                    labels: {$label},
                    datasets: [
                        {
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
                    datasets: [
                        {
                            label: "Saldo",
                            fillColor: "rgba(255,10,0,0.2)",
                            strokeColor: "rgba(255,0,0,1)",
                            pointColor: "rgba(255,0,0,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: {$saldo}
                        }
                    ]
                };               
                


                window.onload = function(){

                    var ctx = document.getElementById("lineChart").getContext("2d");
                    var LineChart = new Chart(ctx).Line(data, options);
                    var ctxBar = document.getElementById("barChart").getContext("2d");
                    var BarChart = new Chart(ctxBar).Bar(dataSaldo, optionsSaldo);
                }  
            </script>

