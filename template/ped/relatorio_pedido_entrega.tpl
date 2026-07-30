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
            margin: 0;
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

      .print-section {
            page-break-inside: avoid;
      }

      .total-row {
            font-weight: bold;
      }

      .vendedor-header {
            background-color: #f0f0f0;
      }

      @media print {
            @page {
                  size: auto;
                  margin: 0;
            }

            body {
                  padding: 15px;
            }

            td,
            th,
            h6 {
                  font-size: 9px;
                  line-height: 10px !important;
            }

            .no-print {
                  display: none !important;
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

            h4 {
                  font-size: 11px;
            }

            h5 {
                  font-size: 10px;
                  margin: 0;
            }

            .height100 {
                  height: auto;
            }
      }
</style>

<section class="height100">
      <div class="right_col" role="main">
            <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <h2>
                              <strong>PEDIDO VENDAS ENTREGA</strong><br>
                              Período - {$dataIni} | {$dataFim}
                        </h2>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
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
                                                                        <thead>
                                                                              <tr>
                                                                                    <th>EMISSAO</th>
                                                                                    <th>PEDIDO</th>
                                                                                    <th>CLIENTE</th>
                                                                                    <th>VENDEDOR</th>
                                                                                    <th>C.C. ORIGEM</th>
                                                                                    <th>DATA ENTREGA</th>
                                                                                    <th>C.C. ENTREGA</th>
                                                                                    <th>TIPO ENTREGA</th>
                                                                                    <th>TOTAL</th>

                                                                              </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                              {section name=i loop=$pedido}

                                                                                    <tr>
                                                                                          <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                                          </td>
                                                                                          <td> {$pedido[i].ID} </td>
                                                                                          <td> {$pedido[i].NOMECLIENTE} </td>
                                                                                          <td> {$pedido[i].NOMEVENDEDOR} </td>
                                                                                          <td> {$pedido[i].CENTROCUSTO} </td>
                                                                                          <td>
                                                                                                {if ($pedido[i].PRAZOENTREGA < $dataImp)}
                                                                                                      {$pedido[i].PRAZOENTREGA} -
                                                                                                      IMEDIATO
                                                                                                {else}
                                                                                                      {$pedido[i].PRAZOENTREGA}
                                                                                                {/if}
                                                                                          </td>                                                                                          
                                                                                          <td> {$pedido[i].CENTROCUSTOENTREGA}
                                                                                          </td>
                                                                                          <td> {$pedido[i].DESCTIPOENTREGA}
                                                                                          </td>
                                                                                          <td> {$pedido[i].TOTAL|number_format:2:",":"."}
                                                                                          </td>

                                                                                    </tr>

                                                                                    <p>


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
</section>

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_relatorio.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaParaExcel() {
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
            var ws = XLSX.utils.table_to_sheet(table, { raw: true });
            if (typeof converteColunaNumeroBR === 'function') {
                converteColunaNumeroBR(ws, 8);
            }

            XLSX.utils.book_append_sheet(wb, ws, "Pedidos Entrega");

            var dataIni = '{$dataIni}';
            var dataFim = '{$dataFim}';
            var nomeArquivo = 'Pedido_Entregas_' +
                  dataIni.replace(/\//g, '_') + '_a_' +
                  dataFim.replace(/\//g, '_') + '.xlsx';

            XLSX.writeFile(wb, nomeArquivo);
      }
</script>