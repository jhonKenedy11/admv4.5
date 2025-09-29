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
            margin-top: 0;
            margin-bottom: 0;
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

      @media print {
            @page {
                  display: none;
            }

            td,
            th,
            h6 {
                  font-size: 9px;
                  line-height: 10px !important;
            }

            .no-print {
                  display: none;
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

      }
</style>
<section class="height100">
      <!-- page content -->
      <div class="right_col" role="main">
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    {* <strong>{$empresa[0].NOMEEMPRESA}</strong><br> *}

                                    <strong>PEDIDO VENDAS ITEM ENTREGA </strong><br>
                                    Periodo - {$dataIni} | {$dataFim}
                              </h2>
                        </div>
                  </div>

                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>

            <!-- page content -->
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
                                                                  </thead>
                                                                  <tbody>


                                                                        {assign var="quant" value=$quat+$pedidoItem[i].QTSOLICITADA}

                                                                        <tr>
                                                                              <th>ITEM ESTOQUE</th>
                                                                              <th>DESCRICAO</th>
                                                                              <th>QTDE ENTREGA</th>
                                                                              <th>TIPO ENTREGA</th>
                                                                        </tr>
                                                                        {section name=k loop=$pedidoItem}
                                                                              <tr>
                                                                                    <td>{$pedidoItem[k].ITEMESTOQUE}</td>
                                                                                    <td>{$pedidoItem[k].DESCRICAO}</td>
                                                                                    <td>{$pedidoItem[k].QTSOLICITADA|number_format:2:",":"."}
                                                                                    </td>
                                                                                    <td>{$pedido[k].DESCTIPOENTREGA}</td>
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
            </div>
            <div class="row no-print">
                  <div class="col-xs-12">
                        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                              Imprimir</button>
                  </div>
            </div>

      </div>
<!-- /page content -->