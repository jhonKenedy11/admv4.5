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
            color: #6c767d;
            font-size: 2rem;
            text-align: center;
      }

      .height100 {
            height: 100vh;
            background-color: #F7F7F7;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
      }

      .x_panel {
            padding: 0;
      }

      .table {
            margin-bottom: 0;
            margin-top: -6px;
      }

      .table>tbody>tr>td,
      .table>tbody>tr>th,
      .table>tfoot>tr>td,
      .table>tfoot>tr>th,
      .table>thead>tr>td,
      .table>thead>tr>th {
            padding: 5px !important;
      }

      .dataHora {
            font-size: 10px;
      }

      @media print {
            @page {
                  display: none;
            }

            td,
            th,
            h6 {
                  font-size: 9px;
                  line-height: 10px !important;
            }

            .no-print {
                  display: none;
            }

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                  padding: 3px !important;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 13px;
            }

      }
</style>
<section class="height100">
      <!-- page content -->
      <div class="right_col" role="main">
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    <strong>PEDIDO VENDAS ITEM</strong><br>
                                    Periodo - {$dataIni} | {$dataFim}
                              </h2>
                        </div>
                  </div>

                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>

            <div class="right_col" role="main">
                  <div class="clearfix">
                        </div-->
                        <div class="x_panel">
                              <div class="x_content">
                                    {if $pedido|count > 0}
                                          <section class="content invoice">
                                                <div class="row small">
                                                      <div class="col-xs-12 table">
                                                            <table class="table table-striped">
                                                                  <tbody>
                                                                        {assign var="dia" value=""}
                                                                        {assign var="totalDia" value=0}
                                                                        {assign var="totalDiaCusto" value=0}
                                                                        {section name=i loop=$pedido}

                                                                              {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                                              {assign var="total" value=$total+$pedido[i].TOTAL}
                                                                              {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}

                                                                              {if $pedido[i].EMISSAO neq $dia }
                                                                                    {if $dia neq ""}
                                                                                    {/if}
                                                                                    <th id="date" colspan="8">
                                                                                          {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                                    </th>
                                                                                    {assign var="dia" value=$pedido[i].EMISSAO}
                                                                              {/if}
                                                                              {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                                              {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}
                                                                              <tr>
                                                                                    <th></th>
                                                                                    <th>PED</th>
                                                                                    <th>NR ITEM</th>
                                                                                    <th>DESCRICAO</th>
                                                                                    <th>QTDE</th>
                                                                                    <th>VALOR UNI</th>
                                                                                    <th>DESCONTO</th>
                                                                                    <th>FRETE</th>
                                                                                    <th width="130px">DESP ACESSORIAS</th>
                                                                                    <th>TOTAL ITEM</th>

                                                                              </tr>
                                                                              {section name=k loop=$pedidoItem}
                                                                                    {if $pedido[i].ID eq $pedidoItem[k].ID}
                                                                                          <tr>
                                                                                                <td></td>
                                                                                                <td> {$pedidoItem[k].ID}</td>
                                                                                                <td> {$pedidoItem[k].NRITEM} </td>
                                                                                                <td> {$pedidoItem[k].DESCRICAO} </td>
                                                                                                <td> {$pedidoItem[k].QTSOLICITADA|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].UNITARIO|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].DESCONTO|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].FRETE|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].DESPACESSORIAS|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].TOTAL|number_format:2:",":"."}
                                                                                                </td>
                                                                                          </tr>
                                                                                    {/if}
                                                                              {/section}
                                                                        {/section}
                                                                  </tbody>
                                                            </table>
                                                      </div>
                                                </div>
                                          </section>
                                    {else}
                                          <div class="message-container">
                                                <h4>Nenhum registro localizado!</h4>
                                          </div>
                                    {/if}
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row no-print">
                  <div class="col-xs-12 text-center">
                        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                              Imprimir</button>
                        <button class="btn btn-success" onclick="exportarTabelaParaExcel();">
                              <i class="fa fa-file-excel-o"></i> Exportar Excel
                        </button>
                  </div>
            </div>

</div>

<script type="text/javascript">
      function exportarTabelaParaExcel() {
            var table = document.querySelector('.table-striped');
            if (!table) {
                  alert('Tabela não encontrada!');
                  return;
            }
            
            var csv = '';
            var rows = table.querySelectorAll('tr');
            
            for (var i = 0; i < rows.length; i++) {
                  var row = rows[i];
                  var cells = row.querySelectorAll('td, th');
                  var rowData = [];
                  
                  for (var j = 0; j < cells.length; j++) {
                        var cellText = cells[j].textContent.trim();
                        if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                              cellText = '"' + cellText.replace(/"/g, '""') + '"';
                        }
                        rowData.push(cellText);
                  }
                  
                  csv += rowData.join(',') + '\n';
            }
            
            var blob = new Blob([csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Pedido_Vendas_Item_{$dataIni}_a_{$dataFim}.csv';
            link.click();
      }
</script>