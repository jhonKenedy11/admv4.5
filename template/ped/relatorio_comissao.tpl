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

      #alinha {
            text-align: right;
      }

      .dataHora {
            font-size: 10px;
      }

      @media print {
            @page {
                  margin-top: 0;
                  margin-bottom: 0;
                  display: none;
            }

            td {
                  font-size: 8px;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 13px
            }
      }
</style>
<section class="height100">
      <!-- page content -->
      <div class="right_col" role="main">
            <div class="">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    <strong>&emsp;&emsp;&emsp;&emsp;Relat&oacute;rio de Comiss&atilde;o</strong><br>
                                    Per&iacute;odo - {$dataIni} | {$dataFim}
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>

            <!-- page content -->
            <div class="clearfix"></div>
            <div class="x_panel">
                  <div class="x_content">
                        <!-- CONDICAO PARA VERIFICAR SE EXISTE REGISTRO PARA IMPRESSAO -->
                        {if $pedido|count > 0}
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped">
                                                      <thead>
                                                            <tr>
                                                                  <th>ITEM</th>
                                                                  <th>GRUPO</th>
                                                                  <th>ORIGEM</th>
                                                                  <th id="alinha">BASE</th>
                                                                  <th id="alinha">%</th>
                                                                  <th id="alinha">VALOR</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            {assign var="vendedor" value=""}
                                                            {assign var="pedidoAtual" value=""}
                                                            {assign var="totalVend" value=0}
                                                            {assign var="totalPed" value=0}
                                                            {assign var="totalGeral" value=0}
                                                            {section name=i loop=$pedido}

                                                                  {if $pedido[i].NVENDEDOR neq $vendedor}
                                                                        {if $pedidoAtual neq ""}
                                                                              <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td id="alinha"><b>SUBTOTAL PEDIDO R$</b></td>
                                                                                    <td id="alinha"><b>{$totalPed|number_format:2:",":"."}</b></td>
                                                                              </tr>
                                                                              {assign var="totalPed" value=0}
                                                                              {assign var="pedidoAtual" value=""}
                                                                        {/if}
                                                                        {if $vendedor neq ""}
                                                                              <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td id="alinha"><h6><b>SUBTOTAL VENDEDOR R$</b></h6></td>
                                                                                    <td id="alinha"><h6><b>{$totalVend|number_format:2:",":"."}</b></h6></td>
                                                                              </tr>
                                                                              {assign var="totalVend" value=0}
                                                                        {/if}
                                                                        <tr>
                                                                              <th id="nomeVendedor" colspan="6">{$pedido[i].NVENDEDOR}</th>
                                                                        </tr>
                                                                        {assign var="vendedor" value=$pedido[i].NVENDEDOR}
                                                                  {/if}

                                                                  {if $pedido[i].PEDIDO neq $pedidoAtual}
                                                                        {if $pedidoAtual neq ""}
                                                                              <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td id="alinha"><b>SUBTOTAL PEDIDO R$</b></td>
                                                                                    <td id="alinha"><b>{$totalPed|number_format:2:",":"."}</b></td>
                                                                              </tr>
                                                                              {assign var="totalPed" value=0}
                                                                        {/if}
                                                                        <tr style="background-color:#eef3f7;">
                                                                              <td colspan="6">
                                                                                    <b>Pedido {$pedido[i].PEDIDO}</b>
                                                                                    &nbsp;|&nbsp; Situa&ccedil;&atilde;o: <b>{$pedido[i].SITUACAODESC}</b>
                                                                                    {if $pedido[i].NUMERONF neq ''}&nbsp;|&nbsp; NF: <b>{$pedido[i].NUMERONF}</b>{/if}
                                                                                    &nbsp;|&nbsp; Cliente: {$pedido[i].NCLIENTE}
                                                                              </td>
                                                                        </tr>
                                                                        {assign var="pedidoAtual" value=$pedido[i].PEDIDO}
                                                                  {/if}

                                                                  <tr>
                                                                        <td>{$pedido[i].DESCITEM}</td>
                                                                        <td>{$pedido[i].GRUPO}</td>
                                                                        <td>{$pedido[i].ORIGEM}</td>
                                                                        <td id="alinha">{$pedido[i].BASECOMISSAO|number_format:2:",":"."}</td>
                                                                        <td id="alinha">{$pedido[i].COMISSAO|number_format:2:",":"."}</td>
                                                                        <td id="alinha">{$pedido[i].VALORCOMISSAO|number_format:2:",":"."}</td>
                                                                  </tr>

                                                                  {assign var="totalPed" value=$totalPed+$pedido[i].VALORCOMISSAO}
                                                                  {assign var="totalVend" value=$totalVend+$pedido[i].VALORCOMISSAO}
                                                                  {assign var="totalGeral" value=$totalGeral+$pedido[i].VALORCOMISSAO}

                                                            {/section}

                                                            {if $pedidoAtual neq ""}
                                                                  <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td id="alinha"><b>SUBTOTAL PEDIDO R$</b></td>
                                                                        <td id="alinha"><b>{$totalPed|number_format:2:",":"."}</b></td>
                                                                  </tr>
                                                            {/if}
                                                            <tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td id="alinha"><h6><b>SUBTOTAL VENDEDOR R$</b></h6></td>
                                                                  <td id="alinha"><h6><b>{$totalVend|number_format:2:",":"."}</b></h6></td>
                                                            </tr>
                                                            <tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td id="alinha"><h5><b>TOTAL R$</b></h5></td>
                                                                  <td id="alinha"><h5><b>{$totalGeral|number_format:2:",":"."}</b></h5></td>
                                                            </tr>
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

      <div class="row no-print">
            <div class="col-xs-12 text-center">
                  <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                        Imprimir</button>
                  <button class="btn btn-success" onclick="exportarTabelaParaExcel();">
                        <i class="fa fa-file-excel-o"></i> Exportar Excel
                  </button>
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
                  for (var c = 3; c <= 5; c++) converteColunaNumeroBR(ws, c);
            }
            XLSX.utils.book_append_sheet(wb, ws, "Relatorio Comissao");

            var dataIni = '{$dataIni}';
            var dataFim = '{$dataFim}';
            var nomeArquivo = 'Relatorio_Comissao_' +
                  dataIni.replace(/\//g, '_') + '_a_' +
                  dataFim.replace(/\//g, '_') + '.xlsx';

            XLSX.writeFile(wb, nomeArquivo);
      }
</script>
