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
                  margin: 0.5cm;
                  size: landscape;
            }

            body {
                  font-size: 10pt;
                  font-family: Arial, sans-serif;
                  color: #000 !important;
                  background: #fff !important;
            }

            .height100 {
                  min-height: auto !important;
                  padding: 2px !important;
                  margin-top: 5px !important;
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
                  font-size: 9pt !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
                  border: 1px solid #000 !important;
            }

            .table th {
                  border-bottom: 2px solid #000 !important;
                  background: #fff !important;
                  color: #000 !important;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 9pt;
            }

            h2 {
                  font-size: 12pt;
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

            .print-container {
                  page-break-inside: avoid !important;
                  orphans: 0 !important;
                  widows: 0 !important;
            }

            .height100 {
                  page-break-before: auto !important;
                  page-break-after: avoid !important;
            }

            .header-section + .x_panel {
                  page-break-before: avoid !important;
            }

            table tbody tr {
                  page-break-inside: avoid !important;
            }

            * {
                  box-shadow: none !important;
                  text-shadow: none !important;
            }
      }
</style>

<div class="height100">
      <div class="print-container">
            <div class="header-section">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" align="left" width="180" height="45" border="0">
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2 class="text-center">
                                    <strong>BLOCO H - INVENTÁRIO</strong><br>
                                    <strong>Período - {$dataIni} - {$dataFim}</strong>
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>
            <div class="x_panel">
                  {if !empty($resultado)}
                        <div class="table-responsive">
                              <table class="table table-striped" style="margin-bottom: 0;">
                                    <thead>
                                          <tr>
                                                <th style="width: 10%;">Código (SKU)</th>
                                                <th style="width: 35%;">Produto</th>
                                                <th style="width: 10%;">NCM</th>
                                                <th style="width: 10%; text-align: right;">Quantidade</th>
                                                <th style="width: 10%;">Unidade</th>
                                                <th style="width: 12%; text-align: right;">Valor Unitário</th>
                                                <th style="width: 13%; text-align: right;">Valor Total</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          {foreach $resultado as $item}
                                                <tr>
                                                      <td>{$item.CODIGO}</td>
                                                      <td>{$item.DESCRICAO}</td>
                                                      <td>{$item.NCM}</td>
                                                      <td style="text-align: right;">{$item.QUANTIDADE|number_format:2:',':'.'}</td>
                                                      <td>{$item.UNIDADE}</td>
                                                      <td style="text-align: right;">{$item.VALOR_UNITARIO|number_format:2:',':'.'}</td>
                                                      <td style="text-align: right;">{$item.VALOR_TOTAL|number_format:2:',':'.'}</td>
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
                        <button class="btn btn-success" onclick="exportarTabelaBlocoHParaExcel();">
                              <i class="fa fa-file-excel-o"></i> Exportar Excel
                        </button>
                  </div>
            </div>
      </div>
</div>

<script type="text/javascript" src="{$pathJs}/est/s_estoque_relatorio.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaBlocoHParaExcel() {
            var table = document.querySelector('.table-striped');
            if (!table) {
                  alert('Tabela não encontrada!');
                  return;
            }

            if (typeof XLSX === 'undefined') {
                  alert('Biblioteca Excel não carregada. Por favor, recarregue a página.');
                  return;
            }

            var wb = XLSX.utils.book_new();
            var ws_data = [];
            var rows = table.querySelectorAll('tr');

            for (var i = 0; i < rows.length; i++) {
                  var row = rows[i];
                  var cells = row.querySelectorAll('td, th');
                  var rowData = [];

                  for (var j = 0; j < cells.length; j++) {
                        var cellText = cells[j].textContent.trim();
                        rowData.push(cellText);
                  }

                  ws_data.push(rowData);
            }

            var ws = XLSX.utils.aoa_to_sheet(ws_data);

            // Converter colunas numéricas do padrão BR para números (Quantidade, Valor Unitário, Valor Total)
            if (typeof converteColunaNumeroBR === 'function') {
                  converteColunaNumeroBR(ws, 3); // Quantidade
                  converteColunaNumeroBR(ws, 5); // Valor Unitário
                  converteColunaNumeroBR(ws, 6); // Valor Total
            }

            var colWidths = [];
            for (var col = 0; col < ws_data[0].length; col++) {
                  var maxLength = 0;
                  for (var row = 0; row < ws_data.length; row++) {
                        if (ws_data[row][col]) {
                              var cellLength = String(ws_data[row][col]).length;
                              if (cellLength > maxLength) {
                                    maxLength = cellLength;
                              }
                        }
                  }
                  colWidths.push({ wch: Math.min(Math.max(maxLength, 10), 50) });
            }
            ws['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(wb, ws, "Bloco H");

            var fileName = 'Bloco_H_{$dataIni}_a_{$dataFim}.xlsx';
            XLSX.writeFile(wb, fileName);
      }
</script>

