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


                <div class="x_panel">
                  <div class="x_content">


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>Lan&ccedil;amentos</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>                        
                       
                            
                      <!-- info row -->
                      <div class="row invoice-info">
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Docto</th>
                                    <th>Filial</th>
                                    <th>Pessoa</th>
                                    <th>Lan&ccedil;amento</th>
                                    <th>Movimento</th>
                                    <th>Tipo</th>
                                    <th>Tipo Docto</th>
                                    <th>Usu&aacute;rio Baixa</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                {assign var="dataAnt" value=$lanc[0].PAGAMENTO}
                                {assign var="valorDebito" value=0}
                                {assign var="valorCredito" value=0}
                                {assign var="saldoParcial" value=$saldoInicial}
                                {section name=i loop=$lanc}

                                        {if $dataAnt neq $lanc[i].PAGAMENTO}
                                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                        {assign var="dataAnt" value=$lanc[i].PAGAMENTO}
                                                        {$saldoParcial=$saldoParcial+$valorCredito}
                                                        {$saldoParcial=$saldoParcial-$valorDebito}

                                                        <td colspan="9" align="left"><h5><b>Data: {$ultimaData|date_format:"%d/%m/%Y"}</b></h5></td>
                                                        {assign var="valorDebito" value=0}
                                                        {assign var="valorCredito" value=0}

                                        </tr>
                                        {/if}   	        
                                    {assign var="total" value=$total+$lanc[i].TOTAL}
                                    {if $lanc[i].TIPOLANCAMENTO eq "RECEBIMENTO"}
                                                {$valorCredito=$valorCredito+$lanc[i].TOTAL}
                                    {else}
                                                {$valorDebito=$valorDebito+$lanc[i].TOTAL}
                                    {/if}


                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td> {$lanc[i].DOCTO} </td>
                                        <td> {$lanc[i].FILIAL} </td>
                                        <td> {$lanc[i].NOME} </td>
                                        <td> {$lanc[i].LANCAMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].TIPOLANCAMENTO} </td>
                                        <td> {$lanc[i].TIPODCTODESC} </td>
                                        <td> {$lanc[i].USRPGTO} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."}</td>

                                </tr>
                                        {assign var="ultimaData" value=$lanc[i].PAGAMENTO}
                                <p>
                                {sectionelse}
                                <tr>
                                    <td>n&atilde;o h&aacute; Lan&ccedil;amentos Cadastrados</td>
                                </tr>
                                {/section}

                                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                        {assign var="dataAnt" value=$lanc[i].PAGAMENTO}
                                                        {assign var="saldoParcial" value=$saldoParcial+$valorCredito}
                                                        {assign var="saldoParcial" value=$saldoParcial-$valorDebito}
                                                        {assign var="valorDebito" value=0}
                                                        {assign var="valorCredito" value=0}
                                                        <td colspan="9" align="right"><h5><b>Saldo Data: {$ultimaData|date_format:"%d/%m/%Y"} = R$ {$saldoParcial|number_format:2:",":"."}</b></h5></td>
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
                
                window.onload = function(){

                    var ctx = document.getElementById("lineChart").getContext("2d");
                    var LineChart = new Chart(ctx).Line(data, options);
                }  
            </script>

