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
      <div class="right_col" role="main">
            <div class="col-md-4 col-sm-4 col-xs-4">
                  <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5">
                  <h2>
                        <strong>PEDIDO VENDAS MENSAL</strong><br>
                        Período - {$dataIni} | {$dataFim}
                  </h2>
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
                                                      <table class="table table-striped">
                                                            <thead>
                                                                  <tr>
                                                                        <th>MÊS/EMISSAO</th>
                                                                        <th>PEDIDO</th>
                                                                        <th>CLIENTE</th>
                                                                        <th>VENDEDOR</th>
                                                                        <th>SITUAÇÃO</th>
                                                                        <th>CENTRO CUSTO</th>
                                                                        {if $tipoUsuario neq ""}<th>CUSTO</th>{/if}
                                                                        <th>TOTAL</th>

                                                                  </tr>
                                                            </thead>
                                                            <tbody>
                                                                  {assign var="dia" value=""}
                                                                  {assign var="mes" value=""}
                                                                  {assign var="totalDia" value=0}
                                                                  {assign var="totalDiaCusto" value=0}
                                                                  {section name=i loop=$pedido}
                                                                        {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                                        {assign var="total" value=$total+$pedido[i].TOTAL}
                                                                        {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}

                                                                        {if $pedido[i].EMISSAO|date_format:"%m/%Y" neq $mes }
                                                                              {if $mes neq ""}
                                                                                    <tr>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td></td>
                                                                                          <td>
                                                                                                <h5>TOTAL MENSAL</h5>
                                                                                          </td>
                                                                                          {if $tipoUsuario neq ""}<td>
                                                                                                      <h4>R$ {$totalDiaCusto|number_format:2:",":"."}
                                                                                                      </h4>
                                                                                          </td>{/if}
                                                                                          <td>
                                                                                                <h4>R$ {$totalDia|number_format:2:",":"."}
                                                                                                </h4>
                                                                                          </td>

                                                                                          {assign var="totalDia" value=0}
                                                                                          {assign var="totalDiaCusto" value=0}

                                                                                    </tr>

                                                                              {/if}
                                                                              <th id="date" colspan="8">
                                                                                    {$pedido[i].EMISSAO|date_format:"%m/%Y"}</th>
                                                                              {assign var="mes" value=$pedido[i].EMISSAO|date_format:"%m/%Y"}
                                                                        {/if}
                                                                        <tr>
                                                                              <td>{$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                              </td>
                                                                              <td> {$pedido[i].ID} </td>
                                                                              <td> {$pedido[i].NOMECLIENTE} </td>
                                                                              <td> {$pedido[i].NOMEVENDEDOR} </td>
                                                                              <td> {$pedido[i].SIT} </td>
                                                                              <td> {$pedido[i].CCUSTO} </td>
                                                                              {if $tipoUsuario neq ""}<td>
                                                                                          {$pedido[i].CUSTOTOTAL|number_format:2:",":"."}
                                                                              </td>{/if}
                                                                              <td> {$pedido[i].TOTAL|number_format:2:",":"."}

                                                                              </td>

                                                                        </tr>
                                                                        {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                                        {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}

                                                                        <p>


                                                                        {/section}

                                                                        <tr>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td>
                                                                                    <h5>TOTAL MENSAL</h5>
                                                                              </td>
                                                                              {if $tipoUsuario neq ""}<td>
                                                                                          <h4>R$ {$totalDiaCusto|number_format:2:",":"."}
                                                                                          </h4>
                                                                              </td>{/if}
                                                                              <td>
                                                                                    <h4>R$ {$totalDia|number_format:2:",":"."}
                                                                                    </h4>
                                                                              </td>
                                                                        </tr>
                                                                        <tr>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td>
                                                                                    <h5>TOTAL GERAL</h5>
                                                                              </td>
                                                                              {if $tipoUsuario neq ""}<td>
                                                                                          <h4>R$ {$totalCusto|number_format:2:",":"."}
                                                                                          </h4>
                                                                              </td>{/if}
                                                                              <td>
                                                                                    <h4>R$ {$total|number_format:2:",":"."}
                                                                                    </h4>
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

      </div>
</section>