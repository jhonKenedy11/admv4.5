<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
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
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>   DRE Financeiro</h3>
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

                           
                            
                            

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Genero</th>
                                    <th>Descri&ccedil;&atilde;o Genero</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}

                                        {assign var="gen" value=$lanc[i].GENERO|truncate:1:""}
                                        {if $gen eq "1"}
                                                {assign var="recOper" value=$recOper+$lanc[i].TOTAL}
                                        {elseif $gen eq "2"}
                                                {assign var="custoVar" value=$custoVar+$lanc[i].TOTAL}
                                        {elseif $gen eq "4"}
                                                {assign var="custoFixo" value=$custoFixo+$lanc[i].TOTAL}
                                        {elseif $gen eq "5"}
                                                {assign var="custoFixo" value=$custoFixo+$lanc[i].TOTAL}
                                        {elseif $gen eq "6"}
                                                {assign var="receitaFin" value=$receitaFin+$lanc[i].TOTAL}
                                        {/if}

                                {/section}

                                {assign var="genOld" value='99'}
                                {section name=i loop=$lanc}
                                        {assign var="total" value=$total+$lanc[i].TOTAL}

                                        {assign var="gen" value=$lanc[i].GENERO|truncate:1:""}
                                        {if $gen eq "1"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>1</b> </td>
                                                        <td class=ColunaTitulo> <b>Receita Operacional Total</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOper|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}
                                        {elseif $gen eq "2"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>2</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Variavel</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVar|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}
                                        {elseif $gen eq "4"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        {assign var="margem" value=$recOper-$custoVar}

                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>3</b> </td>
                                                        <td class=ColunaTitulo> <b>Margem de Contribui&ccedil;&atilde;o (1-2)</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margem|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>4</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Fixo</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixo|number_format:2:",":"."}</b> </td>
                                                </tr>

                                                {/if}
                                        {elseif $gen eq "5"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>4.1</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Fixo Adicional (Grupo 5)</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixo|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}

                                        {/if}


                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td> {$lanc[i].GENERO} </td>
                                        <td> {$lanc[i].DESCRICAO} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                </tr>
                                <p>
                                {/section}

                                <tr>
                                        <td> <b>5</b> </td>
                                        <td> <b>Lucro Operacional </b></td>
                                        <td><b>{($margem-$custoFixo+$receitaFin)|number_format:2:",":"."}</b></td>
                                        </td>
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
                          <button class="btn btn-success" onclick="exportarTabelaParaExcel();"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
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

            <script type="text/javascript">
                function exportarTabelaParaExcel() {
                    // Pega a tabela que já está sendo exibida
                    var table = document.querySelector('.table-striped');
                    if (!table) {
                        alert('Tabela não encontrada!');
                        return;
                    }

                    if (typeof XLSX === 'undefined') {
                        alert('Biblioteca de exportação (XLSX) não carregada!');
                        return;
                    }

                    var wb = XLSX.utils.book_new();
                    var ws = XLSX.utils.table_to_sheet(table);

                    // Larguras (Genero / Descrição / Total)
                    ws['!cols'] = [
                        {ldelim}wch: 10{rdelim},
                        {ldelim}wch: 60{rdelim},
                        {ldelim}wch: 18{rdelim}
                    ];

                    XLSX.utils.book_append_sheet(wb, ws, "DRE Mensal");

                    var dataIni = '{$dataInicio}';
                    var dataFim = '{$dataFim}';
                    var nomeArquivo = 'DRE_Financeiro_' + dataIni.replace(/\//g, '_') + '_a_' + dataFim.replace(/\//g, '_') + '.xlsx';
                    XLSX.writeFile(wb, nomeArquivo);
                }
            </script>

