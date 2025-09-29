<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_consolidacao_mostra.js"> </script>

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


                <div class="x_panel">
                  <div class="x_content">


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>   Consolida&ccedil;&atilde;o Banc&aacute;ria</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>
                            

                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>D&eacute;bito X Cr&eacute;dito</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <canvas id="lineChart"></canvas>
                          </div>
                        </div>
                      </div>                            

                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>Progress&atilde;o Saldo</h2>
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
                      <!-- info row -->
                      <div class="row invoice-info">
                        <!-- /.col -->
                        <div class="col-sm-6 invoice-col">
                          <H2>Saldo Inicial: {$saldoInicial}</H2>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped small">
                            <thead>
                                <tr>
                                    <th>Docto</th>
                                    <th>Filial</th>
                                    <th>Pessoa</th>
                                    <th>Serie</th>
                                    <th>Par</th>
                                    <th>Genero</th>
                                    <th>Movimento</th>
                                    <th>Tipo</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {assign var="dataAnt" value=$lanc[0].PAGAMENTO}
                                {assign var="valorDebito" value=0}
                                {assign var="valorCredito" value=0}
                                {assign var="saldoParcial" value=$saldoInicial}
                                {assign var="total" value=0}
                                {section name=i loop=$lanc}

                                    {if $dataAnt neq $lanc[i].PAGAMENTO}
                                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                        <!-- busca saldo do dia, fazer lancamento do final do dia -->
                                                        {assign var="saldoBanco" value=0}
                                                        {section name=s loop=$saldo}
                                                            {if $saldo[s].DATA eq $dataAnt}
                                                                        {assign var="saldoBanco" value=$saldo[s].SALDO}
                                                            {/if}
                                                        {/section}

                                                        {assign var="saldoParcial" value=$saldoParcial+$valorCredito}
                                                        {assign var="saldoParcial" value=$saldoParcial-$valorDebito}

                                                        <td colspan="9" align="right"><h6><b>
                                                        Data: {$dataAnt|date_format:"%d/%m/%Y"} = Saldo Banco R$ {$saldoBanco|number_format:2:",":"."}
                                                        / ( Credito: R$ {$valorCredito|number_format:2:",":"."}
                                                                Debito: R$ {$valorDebito|number_format:2:",":"."} ) ==>
                                                        Saldo Calculado: R$ {$saldoParcial|number_format:2:",":"."} / 
                                                        {if $saldoBanco ge $saldoParcial}
                                                                {assign var="diferenca" value=$saldoBanco-$saldoParcial}
                                                                Diferen&ccedil;a: R$ {$diferenca|number_format:2:",":"."}
                                                        {else}	
                                                                {assign var="diferenca" value=$saldoParcial-$saldoBanco}
                                                                Diferen&ccedil;a: R$ -{$diferenca|number_format:2:",":"."}
                                                        {/if}	

                                                            </h6></b></td>

                                        </tr>
                                                        {assign var="dataAnt" value=$lanc[i].PAGAMENTO}
                                                        {assign var="valorDebito" value=0}
                                                        {assign var="valorCredito" value=0}

                                        {/if}   	        

                                        {assign var="total" value=$total+$lanc[i].TOTAL}
                                        {if $lanc[i].TIPOLANCAMENTO eq "RECEBIMENTO"}
                                                    {assign var="valorCredito" value=$valorCredito+$lanc[i].TOTAL}
                                        {else}
                                                    {assign var="valorDebito" value=$valorDebito+$lanc[i].TOTAL}
                                        {/if}

                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td> {$lanc[i].DOCTO} </td>
                                        <td> {$lanc[i].CENTROCUSTO} </td>
                                        <td> {$lanc[i].NOME} </td>
                                        <td> {$lanc[i].SERIE} </td>
                                        <td> {$lanc[i].PARCELA} </td>
                                        <td> {$lanc[i].GENERO} </td>
                                        <td> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].TIPOLANCAMENTO} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

                                </tr>
                                        {assign var="ultimaData" value=$lanc[i].PAGAMENTO}
                                <p>
                                {sectionelse}
                                <tr>
                                        <td>n&atilde;o h&aacute; Lan&ccedil;amentos Cadastrados</td>
                                </tr>
                                {/section}

                                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">

                                                        {assign var="saldoBanco" value=0}
                                                        {section name=s loop=$saldo}
                                                            {if $saldo[s].DATA eq $dataAnt}
                                                                        {assign var="saldoBanco" value=$saldo[s].SALDO}
                                                            {/if}
                                                        {/section}

                                                        {assign var="saldoParcial" value=$saldoParcial+$valorCredito}
                                                        {assign var="saldoParcial" value=$saldoParcial-$valorDebito}

                                                        <td colspan="9" align="right"><h6><b>
                                                        Data: {$dataAnt|date_format:"%d/%m/%Y"} = Saldo Banco R$ {$saldoBanco|number_format:2:",":"."}
                                                        / ( Credito: R$ {$valorCredito|number_format:2:",":"."}
                                                                Debito: R$ {$valorDebito|number_format:2:",":"."} ) ==>
                                                        Saldo Calculado: R$ {$saldoParcial|number_format:2:",":"."} / 

                                                        {if $saldoBanco ge $saldoParcial}
                                                                {assign var="diferenca" value=$saldoBanco-$saldoParcial}
                                                                Diferen&ccedil;a: R$ {$diferenca|number_format:2:",":"."}
                                                        {else}	
                                                                {assign var="diferenca" value=$saldoParcial-$saldoBanco}
                                                                Diferen&ccedil;a: R$ -{$diferenca|number_format:2:",":"."}
                                                        {/if}	
                                                            </b></h6></b></td>
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
                            fillColor: "rgba(0,102,255,0.2)",
                            strokeColor: "rgba(0,102,255,1)",
                            pointColor: "rgba(255,0,0,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: {$graSaldo}
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

