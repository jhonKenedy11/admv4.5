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
            background-color: #F7F7F7;
            margin: 0;
            padding: 10px;
      }

      .print-container {
            display: flex;
            flex-direction: column;
      }

      .header-section {
            margin-bottom: 10px;
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

      .table td:nth-child(2) {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

      @media print {
            @page {
                  margin: 0.3cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .height100 {
                  min-height: auto !important;
                  padding: 2px !important;
            }

            .print-container {
                  page-break-inside: avoid !important;
            }

            .header-section {
                  margin-bottom: 2px !important;
                  padding: 0 !important;
            }

            .x_panel {
                  margin-top: 1px !important;
            }

            .table-responsive {
                  page-break-inside: avoid !important;
            }

            .table {
                  page-break-inside: avoid !important;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
            }

            .table td:nth-child(2) {
                  max-width: 150px !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 10px;
                  margin: 1px 0 !important;
                  line-height: 1.2 !important;
            }

            .col-md-4, .col-md-5, .col-md-3 {
                  padding: 1px !important;
            }

            img {
                  max-width: 100px !important;
                  max-height: 25px !important;
            }

            /* Força que tudo fique junto na primeira página */
            .print-container {
                  page-break-inside: avoid !important;
                  orphans: 0 !important;
                  widows: 0 !important;
            }

            /* Regra específica para evitar quebras no início */
            .height100 {
                  page-break-before: auto !important;
                  page-break-after: avoid !important;
            }

            /* Força que o cabeçalho e a tabela fiquem juntos */
            .header-section + .x_panel {
                  page-break-before: avoid !important;
            }
      }
</style>

<div class="height100">
      <div class="print-container">
            <div class="header-section">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" align="right" width="180" height="45" border="0">
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2 class="text-center">
                                    <strong>SUGESTÕES DE COMPRAS</strong><br>
                                    <strong>Período - {$dataIni} - {$dataFim}</strong>
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>
            <div class="x_panel">
                  {if count($resultado) > 0}
                        <div class="table-responsive">
                              <table class="table table-striped" style="margin-bottom: 0;">
                                    <thead>
                                          <tr>
                                                <th style="width: 5%; text-align: center;">Código</th>
                                                <th style="width: 35%; text-align: left;">Descrição</th>
                                                <th style="width: 15%; text-align: left;">Grupo</th>
                                                <th style="width: 12%; text-align: center;">Cód. Fabricante</th>
                                                <th style="width: 8%; text-align: center;">Qtde Mín</th>
                                                <th style="width: 8%; text-align: center;">Qtde Máx</th>
                                                <th style="width: 8%; text-align: center;">Qtde Vendida</th>
                                                <th style="width: 8%; text-align: center;">Valor Total</th>
                                                <th style="width: 8%; text-align: center;">Num. Vendas</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          {foreach $resultado as $item}
                                                <tr>
                                                      <td style="text-align: center;">{$item.ITEMESTOQUE}</td>
                                                      <td>{$item.DESCRICAO}</td>
                                                      <td>{$item.NOMEGRUPO}</td>
                                                      <td style="text-align: center;">{$item.CODFABRICANTE}</td>
                                                      <td style="text-align: center;">{$item.QUANTMINIMA}</td>
                                                      <td style="text-align: center;">{$item.QUANTMAXIMA}</td>
                                                      <td style="text-align: center;">{$item.QUANT|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">R$ {$item.VALOR|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.NUMVENDAS}</td>
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
</div>

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
            link.download = 'Sugestoes_Compras_{$dataIni}_a_{$dataFim}.csv';
            link.click();
      }
</script> 