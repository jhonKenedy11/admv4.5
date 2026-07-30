<style>
      .height100 {
            height: 100vh;
            background-color: #F7F7F7;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
      }

      .x_content {
            margin: 0;
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
      <div class="right_col" role="main">
            <div class="">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    <strong>&ensp;PEDIDO VENDA DETALHADO</strong><br>
                                    Per&iacute;odo - {$dataIni} | {$dataFim}
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>
            <div class="right_col" role="main">
                  <div class="clearfix">
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

                                                                                    <th id="date" colspan="8">
                                                                                          {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                                    </th>
                                                                                    {assign var="dia" value=$pedido[i].EMISSAO}
                                                                              {/if}
                                                                              <tr>
                                                                                    <th></th>
                                                                                    <th>PED</th>
                                                                                    <th>EMISSAO</th>
                                                                                    <th>CLIENTE</th>
                                                                                    <th>SITUAÇÃO</th>
                                                                                    <th>VENDEDOR</th>
                                                                                    <th> </th>

                                                                              </tr>
                                                                              <tr>
                                                                                    <td></td>
                                                                                    <td> {$pedido[i].ID} </td>
                                                                                    <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                                    </td>
                                                                                    <td> {$pedido[i].NOMECLIENTE} </td>
                                                                                    <td> {$pedido[i].SIT} </td>
                                                                                    <td> {$pedido[i].NOMEVENDEDOR}</td>
                                                                                    <td></td>

                                                                              </tr>
                                                                              {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                                              {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}
                                                                              <tr>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th>NR ITEM</th>
                                                                                    <th>DESCRICAO</th>
                                                                                    <th>QTDE</th>
                                                                                    <th>VALOR UNI</th>
                                                                                    <th>TOTAL ITEM</th>

                                                                              </tr>
                                                                              {section name=k loop=$pedidoItem}
                                                                                    {if $pedido[i].ID eq $pedidoItem[k].ID}
                                                                                          <tr>
                                                                                                <td></td>
                                                                                                <td></td>
                                                                                                <td> {$pedidoItem[k].NRITEM} </td>
                                                                                                <td> {$pedidoItem[k].DESCRICAO} </td>
                                                                                                <td> {$pedidoItem[k].QTSOLICITADA|number_format:2:",":"."}
                                                                                                </td>
                                                                                                <td> {$pedidoItem[k].UNITARIO|number_format:2:",":"."}
                                                                                                </td>

                                                                                                <td> {$pedidoItem[k].TOTAL|number_format:2:",":"."}

                                                                                                </td>

                                                                                          </tr>
                                                                                    {/if}
                                                                              {/section}
                                                                              <tr>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th>FRETE</th>
                                                                                    <th>DESCONTO</th>
                                                                                    <th width="150px">DESP ACESSORIAS</th>
                                                                                    <th>TOTAL</th>

                                                                              </tr>
                                                                              <tr>
                                                                                    <td></td>
                                                                                    <th></th>
                                                                                    <td><strong>TOTAIS</strong></td>
                                                                                    <td> {$pedido[i].FRETE|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td> {$pedido[i].DESCONTO|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td> {$pedido[i].DESPACESSORIAS|number_format:2:",":"."}
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
                for (var c = 3; c <= 6; c++) converteColunaNumeroBR(ws, c);
            }

            XLSX.utils.book_append_sheet(wb, ws, "Pedidos Detalhado");

            var dataIni = '{$dataIni}';
            var dataFim = '{$dataFim}';
            var nomeArquivo = 'Pedido_Vendas_Detalhado_' +
                  dataIni.replace(/\//g, '_') + '_a_' +
                  dataFim.replace(/\//g, '_') + '.xlsx';

            XLSX.writeFile(wb, nomeArquivo);
      }
</script>
<!-- /page content -->