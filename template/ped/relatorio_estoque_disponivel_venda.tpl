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

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_relatorio.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaParaExcel() {
            var table = document.querySelector('table');
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
                for (var c = 5; c <= 11; c++) converteColunaNumeroBR(ws, c);
            }

            XLSX.utils.book_append_sheet(wb, ws, "Estoque Disponivel");

            var dataIni = '{$dataIni}';
            var dataFim = '{$dataFim}';
            var nomeArquivo = 'Estoque_Disponivel_Venda_' +
                  dataIni.replace(/\//g, '_') + '_a_' +
                  dataFim.replace(/\//g, '_') + '.xlsx';

            XLSX.writeFile(wb, nomeArquivo);
      }
</script>