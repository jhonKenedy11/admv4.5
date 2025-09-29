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
            text-align: center;
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
                                    <strong>&emsp;&emsp;&emsp;&emsp;Relat&oacute;rio B&ocirc;nus</strong><br>
                                    Per&iacute;odo - {$dataIni} | {$dataFim}
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>

            <!-- page content -->
            <div class="clearfix">
                  </div-->
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
                                                                        <th>VENDEDOR </th>
                                                                        <th>PEDIDO </th>
                                                                        <th>CLIENTE</th>
                                                                        <th>LOJA</th>
                                                                        <th>ITEM</th>
                                                                        <th id="alinha">QTD ESTORNADA</th>
                                                                        <th id="alinha">UNIT&Aacute;RIO</th>
                                                                        <th>TOTAL</th>
                                                                        <th></th>

                                                                  </tr>
                                                            </thead>
                                                            <tbody>
                                                                  {assign var="totalDia" value=0}
                                                                  {section name=i loop=$pedido}
                                                                        {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                                        {assign var="total" value=$total+$pedido[i].TOTAL}
                                                                        {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}

                                                                        {if $pedido[i].NVENDEDOR neq $vendedor }
                                                                              {if $vendedor neq ""}
                                                                                    <tr>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>

                                                                                          <td>
                                                                                                <h6><b>TOTAL R$</b></h6>
                                                                                          </td>
                                                                                          <td>
                                                                                                <h6><b>{$totalDia|number_format:2:",":"."}</b>
                                                                                                </h6>
                                                                                          </td>

                                                                                          {assign var="totalDia" value=0}

                                                                                    </tr>

                                                                              {/if}
                                                                              <th id="nomeVendedor" colspan="11">
                                                                                    {$pedido[i].NVENDEDOR}</th>
                                                                              {assign var="vendedor" value=$pedido[i].NVENDEDOR}

                                                                        {/if}
                                                                        <tr>
                                                                              <td> {$pedido[i].NVENDEDOR} </td>
                                                                              <td> {$pedido[i].PEDIDO} </td>
                                                                              <td> {$pedido[i].NCLIENTE} </td>
                                                                              <td> {$pedido[i].NOMEFANTASIA} </td>
                                                                              <td> {$pedido[i].DESCITEM} </td>
                                                                              <td id="alinha">
                                                                                    {$pedido[i].QUANTIDADE|number_format:2:",":"."}
                                                                              </td>
                                                                              <td id="alinha">
                                                                                    {$pedido[i].UNITARIO|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedido[i].VALOR|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> </td>


                                                                        </tr>
                                                                        {assign var="totalDia" value=$totalDia+$pedido[i].VALOR}

                                                                        {/section}
                                                                        <tr>

                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td>
                                                                                    <h6><b>TOTAL R$</b></h6>
                                                                              </td>
                                                                              <td>
                                                                                    <h6><b>{$totalDia|number_format:2:",":"."}</b>
                                                                                    </h6>
                                                                              </td>


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
      </div>

      <div class="row no-print">
            <div class="col-xs-12">
                  <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                        Imprimir</button>
            </div>
      </div>
</section>