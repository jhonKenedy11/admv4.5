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
            min-height: 100vh;
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

      .table td:nth-child(4) {
            max-width: 220px;
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

      .col-compra {
            font-weight: bold;
            color: #c0392b;
      }

      .pedido-bloco {
            margin-bottom: 18px;
            border: 1px solid #dde2e8;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
      }

      .pedido-bloco:last-child {
            margin-bottom: 0;
      }

      .pedido-cabecalho {
            background: #2a3f54;
            color: #fff;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
      }

      .pedido-cabecalho small {
            font-weight: normal;
            opacity: 0.9;
      }

      .pedido-bloco .table {
            margin-bottom: 0;
      }

      .pedido-total td {
            background: #f5f5f5;
            font-weight: bold;
            border-top: 2px solid #dde2e8 !important;
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

            .table td:nth-child(4) {
                  max-width: 150px !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
            }

            .pedido-bloco {
                  page-break-inside: avoid !important;
                  margin-bottom: 8px !important;
            }

            .pedido-cabecalho {
                  font-size: 9px !important;
                  padding: 4px 6px !important;
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
      }
</style>
</head>
<body>
<div class="height100">
      <div class="print-container">
            <div class="header-section">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" align="left" width="180" height="45" border="0" alt="">
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2 class="text-center">
                                    <strong>COMPRA POR ENCOMENDA</strong><br>
                                    <small>Itens em falta por pedido e cliente</small>
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>
            <div class="x_panel">
                  {if !empty($pedidos)}
                        {foreach $pedidos as $ped}
                              <div class="pedido-bloco">
                                    <div class="pedido-cabecalho">
                                          Pedido {$ped.PEDIDO} &mdash; {$ped.CLIENTE}
                                          {if $ped.PRAZOENTREGA neq ''}
                                                <small> | Prazo entrega: {$ped.PRAZOENTREGA}</small>
                                          {/if}
                                    </div>
                                    <div class="table-responsive">
                                          <table class="table table-striped">
                                                <thead>
                                                      <tr>
                                                            <th style="width: 4%; text-align: center;">#</th>
                                                            <th style="width: 8%; text-align: center;">Código</th>
                                                            <th style="width: 10%; text-align: center;">Cód. Fabricante</th>
                                                            <th style="width: 30%; text-align: left;">Descrição</th>
                                                            <th style="width: 14%; text-align: left;">Grupo</th>
                                                            <th style="width: 10%; text-align: center;">Solic.</th>
                                                            <th style="width: 10%; text-align: center;">Disp.</th>
                                                            <th style="width: 10%; text-align: center;">Falta</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      {foreach $ped.ITENS as $item}
                                                            <tr>
                                                                  <td style="text-align: center;">{$item.NRITEM}</td>
                                                                  <td style="text-align: center;">{$item.CODIGO}</td>
                                                                  <td style="text-align: center;">{$item.CODFABRICANTE}</td>
                                                                  <td>{$item.DESCRICAO}</td>
                                                                  <td>{$item.NOMEGRUPO}</td>
                                                                  <td style="text-align: center;">{$item.QTSOLICITADA|number_format:2:',':'.'}</td>
                                                                  <td style="text-align: center;">{$item.DISPONIVEL|number_format:2:',':'.'}</td>
                                                                  <td style="text-align: center;" class="col-compra">{$item.QTD_FALTA|number_format:2:',':'.'}</td>
                                                            </tr>
                                                      {/foreach}
                                                      <tr class="pedido-total">
                                                            <td colspan="7" style="text-align: right;">Total em falta no pedido</td>
                                                            <td style="text-align: center;" class="col-compra">{$ped.TOTAL_FALTA|number_format:2:',':'.'}</td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        {/foreach}

                        <table id="exportCompraEncomenda" class="table table-striped" style="display: none;">
                              <thead>
                                    <tr>
                                          <th>Pedido</th>
                                          <th>Cliente</th>
                                          <th>Código</th>
                                          <th>Cód. Fabricante</th>
                                          <th>Descrição</th>
                                          <th>Grupo</th>
                                          <th>Solic.</th>
                                          <th>Disp.</th>
                                          <th>Falta</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    {foreach $pedidos as $ped}
                                          {foreach $ped.ITENS as $item}
                                                <tr>
                                                      <td>{$ped.PEDIDO}</td>
                                                      <td>{$ped.CLIENTE}</td>
                                                      <td>{$item.CODIGO}</td>
                                                      <td>{$item.CODFABRICANTE}</td>
                                                      <td>{$item.DESCRICAO}</td>
                                                      <td>{$item.NOMEGRUPO}</td>
                                                      <td>{$item.QTSOLICITADA|number_format:2:',':'.'}</td>
                                                      <td>{$item.DISPONIVEL|number_format:2:',':'.'}</td>
                                                      <td>{$item.QTD_FALTA|number_format:2:',':'.'}</td>
                                                </tr>
                                          {/foreach}
                                    {/foreach}
                              </tbody>
                        </table>
                  {else}
                        <div class="message-container">
                              <h4>Nenhum registro localizado!</h4>
                        </div>
                  {/if}
            </div>

            <div class="row no-print">
                  <div class="col-xs-12 text-center">
                        <button type="button" class="btn btn-default" onclick="window.print();">
                              <i class="fa fa-print"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportarTabelaParaExcel();">
                              <i class="fa fa-file-excel-o"></i> Exportar Excel
                        </button>
                  </div>
            </div>
      </div>
</div>

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_relatorio.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaParaExcel() {
            var table = document.getElementById('exportCompraEncomenda');
            if (!table) {
                  alert('Tabela não encontrada!');
                  return;
            }

            if (typeof XLSX === 'undefined') {
                  alert('Biblioteca de exportação (XLSX) não carregada!');
                  return;
            }

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.table_to_sheet(table, { raw: true });

            if (typeof converteColunaNumeroBR === 'function') {
                  converteColunaNumeroBR(ws, 6);
                  converteColunaNumeroBR(ws, 7);
                  converteColunaNumeroBR(ws, 8);
            }

            XLSX.utils.book_append_sheet(wb, ws, "Compra Encomenda");

            var dataImp = '{$dataImp}';
            var nomeArquivo = 'Compra_Encomenda_' + dataImp.replace(/[\/: ]/g, '_') + '.xlsx';
            XLSX.writeFile(wb, nomeArquivo);
      }
</script>

</body>
