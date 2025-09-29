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
            min-height: 100vh;
            background-color: #F7F7F7;
            margin: 0;
            padding: 10px;
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

      .entradas {
            color: #28a745;
            font-weight: bold;
      }

      .saidas {
            color: #dc3545;
            font-weight: bold;
      }

      .valor-entradas {
            color: #28a745;
            font-weight: bold;
      }

      .valor-saidas {
            color: #dc3545;
            font-weight: bold;
      }

      .saldo {
            font-weight: bold;
            color: #007bff;
      }

      @media print {
            @page {
                  margin: 0.5cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 12px;
            }
      }
</style>

<section class="height100">
      <div class="right_col" role="main">
            <div class="">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2 class="text-center">
                                    <strong>Relatório Resumido de Estoque</strong><br>
                                    <strong>Período - {$dataIni} | {$dataFim}</strong>
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>

            <div class="clearfix"></div>
            <div class="x_panel">
                  <div class="x_content">
                        {if count($resultado) > 0}
                              <div class="table-responsive">
                                    <table class="table table-striped" style="margin-bottom: 0;">
                                          <thead>
                                                <tr>
                                                      <th style="width: 20%">Produto</th>
                                                      <th style="width: 15%">Grupo</th>
                                                      <th style="width: 15%">Localização</th>
                                                      <th style="width: 10%">Entradas</th>
                                                      <th style="width: 10%">Saídas</th>
                                                      <th style="width: 10%">Saldo</th>
                                                      <th style="width: 10%">Valor Entradas</th>
                                                      <th style="width: 10%">Valor Saídas</th>
                                                      <th style="width: 10%">Saldo Valor</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                {foreach $resultado as $item}
                                                      {assign var="saldo_qtd" value=$item.ENTRADAS-$item.SAIDAS}
                                                      {assign var="saldo_valor" value=$item.VALOR_ENTRADAS-$item.VALOR_SAIDAS}
                                                      <tr>
                                                            <td>{$item.PRODUTO}</td>
                                                            <td>{$item.GRUPO}</td>
                                                            <td>{$item.LOCALIZACAO}</td>
                                                            <td class="entradas">{$item.ENTRADAS|number_format:2:',':'.'}</td>
                                                            <td class="saidas">{$item.SAIDAS|number_format:2:',':'.'}</td>
                                                            <td class="saldo">{$saldo_qtd|number_format:2:',':'.'}</td>
                                                            <td class="valor-entradas">R$ {$item.VALOR_ENTRADAS|number_format:2:',':'.'}</td>
                                                            <td class="valor-saidas">R$ {$item.VALOR_SAIDAS|number_format:2:',':'.'}</td>
                                                            <td class="saldo">R$ {$saldo_valor|number_format:2:',':'.'}</td>
                                                      </tr>
                                                {/foreach}
                                          </tbody>
                                    </table>
                              </div>
                        {else}
                              <div class="message-container">
                                    <h4>Nenhum registro localizado!</h4>
                              </div>
                        {/if}
                  </div>
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
</section>

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
            link.download = 'Resumo_Estoque_{$dataIni}_a_{$dataFim}.csv';
            link.click();
      }
</script>