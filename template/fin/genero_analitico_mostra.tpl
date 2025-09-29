<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <!--div class="page-title">
              <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  {$empresa[0].NOMEEMPRESA}<br>
                  ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2>Pedido: {$pedido[0].PEDIDO}<BR>Romaneio</h2>
              </div>
                
            </div>
            <div class="clearfix"></div-->


                <!--div class="x_panel">
                  <div class="x_content"-->


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>  Financeiro G&ecirc;nero Anal&iacute;tico</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>
                            

                      <!--div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>Financeiro G&ecirc;nero</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <canvas id="barChart"></canvas>
                          </div>
                        </div>
                      </div-->                            
                           
                            
                            <br>     
                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped small">
                            <thead>
                                {assign var="totalGeral" value='0'}
                                {assign var="generoAnt" value='0'}
                                {assign var="totalParcial" value='0'}

                                {section name=i loop=$lanc}

                                    {assign var="totalGeral" value=$totalGeral+$lanc[i].TOTAL}

                                {if $generoAnt eq 0}
                                    {assign var="generoAnt" value=$lanc[i].GENERO}
                                    {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}

                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="11"><b><big>&raquo; {$lanc[i].DESCGENERO}</big></b></td>
                                    <tr>  
                                        <tr>
                                        <th align=center class=ColunaTitulo>Docto</td>
                                        <th align=center class=ColunaTitulo>Filial</td>
                                        <th align=center class=ColunaTitulo>Pessoa</td>
                                        <th align=center class=ColunaTitulo>Serie</td>
                                        <th align=center class=ColunaTitulo>Par</td>
                                        <th align=center class=ColunaTitulo>Genero</td>
                                        <th align=center class=ColunaTitulo>Emiss&atilde;o</td>
                                        <th align=center class=ColunaTitulo>Vencimento</td>
                                        <th align=center class=ColunaTitulo>Movimento</td>
                                        <th align=center class=ColunaTitulo>Tipo</td>
                                        <th align=center class=ColunaTitulo>Obs</td>
                                        <th align=center class=ColunaTitulo>Total</td>
                                </tr>
                                {elseif $lanc[i].GENERO eq $generoAnt}   
                                    {assign var="generoAnt" value=$lanc[i].GENERO}
                                    {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}

                                {else}
                                    {assign var="generoAnt" value=$lanc[i].GENERO}
                                    <tr>
                                            <td align=right class=ColunaTitulo colspan="11"><b><big>Total...: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                                        <tr>     
                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="11"><b><big>&raquo; {$lanc[i].DESCGENERO}</big></b></td>
                                    <tr> 
                                        {assign var="totalParcial" value='0'}

                                        {assign var="totalParcial" value=$totalParcial+$lanc[i].TOTAL}
                                    <tr>
                                        <th align=center class=ColunaTitulo>Docto</td>
                                        <th align=center class=ColunaTitulo>Filial</td>
                                        <th align=center class=ColunaTitulo>Pessoa</td>
                                        <th align=center class=ColunaTitulo>Serie</td>
                                        <th align=center class=ColunaTitulo>Par</td>
                                        <th align=center class=ColunaTitulo>Genero</td>
                                        <th align=center class=ColunaTitulo>Emiss&atilde;o</td>
                                        <th align=center class=ColunaTitulo>Vencimento</td>
                                        <th align=center class=ColunaTitulo>Movimento</td>
                                        <th align=center class=ColunaTitulo>Tipo</td>
                                        <th align=center class=ColunaTitulo>Obs</td>
                                        <th align=center class=ColunaTitulo>Total</td>
                                </tr>
                                {/if}    

                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">

                                        <td> {$lanc[i].DOCTO} </td>
                                        <td> {$lanc[i].FILIAL} </td>
                                        <td><a href=javascript:submitAlterarRel({$lanc[i].ID})> {$lanc[i].NOME} </a></td>
                                        <td> {$lanc[i].SERIE} </td>
                                        <td> {$lanc[i].PARCELA} </td>
                                        <td> {$lanc[i].DESCGENERO} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].VENCIMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].TIPOLANCAMENTO} </td>
                                        <td width=180 height=45> {$lanc[i].OBS} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

                                </tr>

                                <p>
                                {sectionelse}
                                <td>n&atilde;o h&aacute; valores Cadastrados para este per&iacute;odo</td>

                                {/section}

                                <tr>
                                        <td align=right class=ColunaTitulo colspan="11"><b><big>Total...: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                                </tr>
                                <tr>
                                        <td align=right class=ColunaTitulo colspan="11"><b><big>TOTAL GERAL...: R${$totalGeral|number_format:2:",":"."}</big></b></td>
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

