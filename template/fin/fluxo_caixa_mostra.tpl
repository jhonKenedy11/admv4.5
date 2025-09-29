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


                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>   Fluxo de Caixa</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>
                            

                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>Progress&atilde;o D&eacute;bito e Cr&eacute;dito</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <canvas id="lineChart"></canvas>
                          </div>
                        </div>
                      </div>                            

                           
                            
                            
                      <!-- info row -->
                      <div class="row invoice-info">
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                        <!-- /.col -->
                        <div class="col-sm-6 invoice-col">
                          <H3><b>Saldo Inicial:  {$saldoInicial|number_format:2:",":"."}</b></H3>
                        </div>
                        <!-- /.col -->
                          <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Docto / Série / Par</th>
                                    <th>Filial</th>
                                    <th>Pessoa</th>
                                    <th>Genero</th>
                                    <th>Movimento</th>
                                    <th>Crédito</th>
                                    <th>Débito</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>

                                {assign var="dataAnt" value=$lanc[0].PAGAMENTO}
                                {assign var="valorDebito" value=0}
                                {assign var="valorCredito" value=0}
                                {assign var="totalDebito" value=0}
                                {assign var="totalCredito" value=0}
                                {assign var="saldoParcial" value=0}
                                {assign var="totalParcial" value=$saldoInicial}
                                {section name=i loop=$lanc}

                                        {if $dataAnt neq $lanc[i].PAGAMENTO}
                                          <tr>
                                            {assign var="dataAnt" value=$lanc[i].PAGAMENTO}
                                            {$saldoParcial=$valorCredito-$valorDebito}


                                            <!--td colspan="9" align="right"><h5><b>Data: {$ultimaData|date_format:"%d/%m/%Y"} Total D&eacute;bito =  {$valorDebito|number_format:2:",":"."} / Total Cr&eacute;tido =  {$valorCredito|number_format:2:",":"."} / Saldo  {$saldoParcial|number_format:2:",":"."}</b></h5></td-->
                                            <td><h6><b>Total Data: {$ultimaData|date_format:"%d/%m/%Y"}</td>
                                            <td colspan="5" align="right" style="color:blue"><h6><b> {$valorCredito|number_format:2:",":"."}</b></h6></td-->
                                            <td align="right" style="color:red"><h6><b> {$valorDebito|number_format:2:",":"."}</b></h6></td-->
                                            <td align="right"
                                            {if $saldoParcial >= 0} style="background-color:blue" {else} style="background-color:red" {/if}
                                            ><h6><b> {$saldoParcial|number_format:2:",":"."}</b></h6></td>
                                            {assign var="valorDebito" value=0}
                                            {assign var="valorCredito" value=0}

                                          </tr>
                                          <tr>
                                                  <td><h5><b>Sub Total:</td>
                                                  <td colspan="5" align="right" style="color:blue"><h5><b> {$totalCredito|number_format:2:",":"."}</b></h5></td-->
                                                  <td align="right" style="color:red"><h5><b> {$totalDebito|number_format:2:",":"."}</b></h5></td-->
                                                  <td align="right"
                                                  {if $totalParcial >= 0} style="background-color:blue" {else} style="background-color:red" {/if}
                                                  ><h5><b> {$totalParcial|number_format:2:",":"."}</b></h5></td>
                                          </tr>
                                        {/if}   	        
                                        {assign var="total" value=$total+$lanc[i].TOTAL}
                                        {if $lanc[i].TIPOLANCAMENTO eq "RECEBIMENTO"}
                                                    {$valorCredito=$valorCredito+$lanc[i].TOTAL}
                                                    {$totalCredito=$totalCredito+$lanc[i].TOTAL}
                                                    {$totalParcial=$totalParcial+$lanc[i].TOTAL}
                                        {else}
                                                    {$valorDebito=$valorDebito+$lanc[i].TOTAL}
                                                    {$totalDebito=$totalDebito+$lanc[i].TOTAL}
                                                    {$totalParcial=$totalParcial-$lanc[i].TOTAL}
                                        {/if}

                                        <tr>
                                        <td> {$lanc[i].DOCTO} / {$lanc[i].SERIE} / {$lanc[i].PARCELA} </td>
                                        <td> {$lanc[i].CENTROCUSTO} </td>
                                        <td> {$lanc[i].NOME} </td>
                                        <td> {$lanc[i].DESCGENERO} </td>
                                        <td> {$lanc[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                        <td align="right" style="color:blue">
                                        {if $lanc[i].TIPOLANCAMENTO eq "RECEBIMENTO"}
                                          {$lanc[i].TOTAL|number_format:2:",":"."}
                                        {/if}
                                        </td>
                                        <td align="right" style="color:red">
                                        {if $lanc[i].TIPOLANCAMENTO eq "PAGAMENTO"}
                                          {$lanc[i].TOTAL|number_format:2:",":"."}
                                        {/if}
                                        <td align="right"
                                          {if $totalParcial >= 0} style="color:blue" {else} style="color:red" {/if}
                                        > {$totalParcial|number_format:2:",":"."}</td>

                                </tr>
                                        {assign var="ultimaData" value=$lanc[i].PAGAMENTO}
                                <p>
                                {sectionelse}
                                <tr>
                                    <td>n&atilde;o h&aacute; Lan&ccedil;amentos Cadastrados</td>
                                </tr>
                                {/section}

                                <tr>
                                        {assign var="dataAnt" value=$lanc[i].PAGAMENTO}
                                        {assign var="saldoParcial" value=$saldoParcial+$valorCredito}
                                        {assign var="saldoParcial" value=$saldoParcial-$valorDebito}
                                        <!--td colspan="9" align="right"><h5><b>Saldo Data: {$ultimaData|date_format:"%d/%m/%Y"} =  {$saldoParcial|number_format:2:",":"."}</b></h5></td-->
                                        <td><h5><b>TOTAL Data: {$ultimaData|date_format:"%d/%m/%Y"}</td>
                                        <td colspan="5" align="right" style="color:blue"><h5><b> {$valorCredito|number_format:2:",":"."}</b></h5></td-->
                                        <td align="right" style="color:red"><h5><b> {$valorDebito|number_format:2:",":"."}</b></h5></td-->
                                        <td align="right" 
                                        {if $saldoParcial >= 0} style="background-color:blue" {else} style="background-color:red" {/if}
                                        ><h5><b> {$saldoParcial|number_format:2:",":"."}</b></h5></td>
                                        {assign var="valorDebito" value=0}
                                        {assign var="valorCredito" value=0}
                                </tr>
                                <tr>
                                        <td><h5><b>TOTAL GERAL:</td>
                                        <td colspan="5" align="right" style="color:blue"><h5><b> {$totalCredito|number_format:2:",":"."}</b></h5></td-->
                                        <td align="right" style="color:red"><h5><b> {$totalDebito|number_format:2:",":"."}</b></h5></td-->
                                        <td align="right"
                                        {if $totalParcial >= 0} style="background-color:blue" {else} style="background-color:red" {/if}
                                        ><h5><b> {$totalParcial|number_format:2:",":"."}</b></h5></td>
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

