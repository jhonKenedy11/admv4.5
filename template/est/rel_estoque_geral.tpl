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

            /* Evita quebra de linha dentro das células da tabela */
            table tbody tr {
                  page-break-inside: avoid !important;
            }

            /* Remove box-shadow e text-shadow */
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
                                    <strong>ESTOQUE GERAL</strong><br>
                                    <strong>Data: {$dataImp}</strong>
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
                                                <th style="width: 5%">Código</th>
                                                <th style="width: 22%">Descrição</th>
                                                <th style="width: 9%">Grupo</th>
                                                <th style="width: 6%">Localização</th>
                                                <th style="width: 4%">Unidade</th>
                                                <th style="width: 7%">Cód. Fabricante</th>
                                                <th style="width: 6%; text-align: center;">NCM</th>
                                                <th style="width: 4%; text-align: center;">Origem</th>
                                                <th style="width: 4%; text-align: center;">CST</th>
                                                <th style="width: 5%; text-align: center;">Estoque</th>
                                                <th style="width: 5%; text-align: center;">Reserva</th>
                                                <th style="width: 5%; text-align: center;">Disponível</th>
                                                <th style="width: 6%; text-align: center;">Custo Compra</th>
                                                <th style="width: 6%; text-align: center;">Valor Informado</th>
                                                <th style="width: 6%; text-align: center;">Preço Venda</th>
                                                <th style="width: 7%; text-align: center;">Total R$ (custo)</th>
                                                <th style="width: 7%; text-align: center;">Total R$ (venda)</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                          {assign var=_totEstoque value=0}
                                          {assign var=_totReserva value=0}
                                          {assign var=_totDisp value=0}
                                          {assign var=_totValCusto value=0}
                                          {assign var=_totValVenda value=0}
                                          {foreach $resultado as $item}
                                                {assign var=_qE value=$item.ESTOQUE+0}
                                                {assign var=_qR value=$item.RESERVA+0}
                                                {assign var=_qD value=$item.DISPONIVEL+0}
                                                {assign var=_cc value=$item.CUSTOCOMPRA+0}
                                                {assign var=_pv value=$item.VENDA+0}
                                                {math equation="a*b" a=$_qE b=$_cc assign=_linhaCusto}
                                                {math equation="a*b" a=$_qE b=$_pv assign=_linhaVenda}
                                                {math equation="x+y" x=$_totEstoque y=$_qE assign=_totEstoque}
                                                {math equation="x+y" x=$_totReserva y=$_qR assign=_totReserva}
                                                {math equation="x+y" x=$_totDisp y=$_qD assign=_totDisp}
                                                {math equation="x+y" x=$_totValCusto y=$_linhaCusto assign=_totValCusto}
                                                {math equation="x+y" x=$_totValVenda y=$_linhaVenda assign=_totValVenda}
                                                <tr>
                                                      <td>{$item.CODIGO}</td>
                                                      <td>{$item.DESCRICAO}</td>
                                                      <td>{$item.NOMEGRUPO}</td>
                                                      <td>{$item.LOCALIZACAO}</td>
                                                      <td>{$item.UNIDADE}</td>
                                                      <td>{$item.CODFABRICANTE}</td>
                                                      <td style="text-align: center;">{$item.NCM}</td>
                                                      <td style="text-align: center;">{$item.ORIGEM}</td>
                                                      <td style="text-align: center;">{$item.CST}</td>
                                                      <td style="text-align: center;">{$item.ESTOQUE|number_format:0:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.RESERVA|number_format:0:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.DISPONIVEL|number_format:0:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.CUSTOCOMPRA|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.PRECOINFORMADO|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">{$item.VENDA|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">{$_linhaCusto|number_format:2:',':'.'}</td>
                                                      <td style="text-align: center;">{$_linhaVenda|number_format:2:',':'.'}</td>
                                                </tr>
                                          {/foreach}
                                    </tbody>
                                    <tfoot>
                                          <tr style="font-weight: bold;">
                                                <td colspan="9" style="text-align: right;">Totais</td>
                                                <td style="text-align: center;">{$_totEstoque|number_format:0:',':'.'}</td>
                                                <td style="text-align: center;">{$_totReserva|number_format:0:',':'.'}</td>
                                                <td style="text-align: center;">{$_totDisp|number_format:0:',':'.'}</td>
                                                <td colspan="3"></td>
                                                <td style="text-align: center;">{$_totValCusto|number_format:2:',':'.'}</td>
                                                <td style="text-align: center;">{$_totValVenda|number_format:2:',':'.'}</td>
                                          </tr>
                                    </tfoot>
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

<script type="text/javascript" src="{$pathJs}/est/s_estoque_relatorio.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaParaExcel() {
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

            if (typeof converteColunaNumeroBR === 'function') {
                  converteColunaNumeroBR(ws, 12);
                  converteColunaNumeroBR(ws, 13);
                  converteColunaNumeroBR(ws, 14);
                  converteColunaNumeroBR(ws, 15);
                  converteColunaNumeroBR(ws, 16);
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Estoque Geral");
            
            var fileName = 'Estoque_Geral_{$dataImp}.xlsx';
            XLSX.writeFile(wb, fileName);
      }
</script> 