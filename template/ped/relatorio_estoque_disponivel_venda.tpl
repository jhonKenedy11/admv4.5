<style>
      table {
            text-indent: initial;
            border-spacing: 1px;
            font-variant: normal;
            box-sizing: border-box;
            line-height: 1.3;
      }

      #borda {
            border: 1px solid #D3D3D3;
            border-radius: 20px;
            width: 100%;
      }

      .height100 {
            height: 100vh;
            background-color: #F7F7F7;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
      }

      #tableDimensoes {
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
      }

      #printHidden {
            margin-left: 12px;
      }

      td {}

      @media print {

            @page {
                  margin-top: 0;
                  margin-bottom: 0;
            }

            tr {
                  font-size: 8px;
            }


            .codAlignPrint {
                  text-align: center;
            }

      }
</style>
<section class="height100">
      <div class="right_col" role="main">
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    <strong>RELATÓRIO ESTOQUE DISPONÍVEL</strong><br>
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
                                                            <table class="table table-hover" id="borda">
                                                                  <thead>
                                                                  </thead>
                                                                  <tbody>
                                                                        {assign var="quant" value=$quant+$pedidoItem[k].QTENTREGA}

                                                                        <tr>
                                                                              {* <th>PEDIDO<th> *}
                                                                              <th>C&Oacute;D PRODUTO</th>
                                                                              <th>DESCRI&Ccedil;&Atilde;O</th>
                                                                              <th>ENTREGA</th>
                                                                              <th>ESTOQUE</th>
                                                                              <th>RESERVADO</th>
                                                                              <th>DISPON&Iacute;VEL</th>
                                                                        </tr>
                                                                        {section name=k loop=$pedidoItem}
                                                                              {assign var="disponivel" value="`$pedidoItem[k].ESTOQUE - $pedidoItem[k].QTENTREGA`"}
                                                                              <tr>
                                                                                    {* <td> {$pedidoItem[k].ID} </td> *}
                                                                                    <td class="codAlignPrint">
                                                                                          {$pedidoItem[k].ITEMESTOQUE} </td>
                                                                                    <td> {$pedidoItem[k].DESCRICAO} </td>
                                                                                    <td class="codAlignPrint">
                                                                                          {$pedidoItem[k].QTSOLICITADA|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td class="codAlignPrint">
                                                                                          {$pedidoItem[k].ESTOQUE|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td class="codAlignPrint">
                                                                                          {$pedidoItem[k].RESERVA|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td class="codAlignPrint">
                                                                                          {$disponivel|number_format:2:",":"."}
                                                                                    </td>
                                                                              </tr>
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
                  <div class="row no-print hidden-print" id="printHidden">
                        <div class="col-xs-12 text-center">
                              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                                    Imprimir</button>
                              <button class="btn btn-success" onclick="exportarTabelaParaExcel();">
                                    <i class="fa fa-file-excel-o"></i> Exportar Excel
                              </button>
                        </div>
                  </div>

            </div>
      </div>
</section>

<script type="text/javascript">
      function exportarTabelaParaExcel() {
            var table = document.querySelector('table');
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
            link.download = 'Estoque_Disponivel_Venda_{$dataIni}_a_{$dataFim}.csv';
            link.click();
      }
</script>