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
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2>
                                    <strong>ENTREGA NÃO REALIZADA</strong><br>
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
                                                                        {assign var="dia" value=""}
                                                                        {assign var="pedidoAtual" value=""}
                                                                        {assign var="totalDia" value=0}
                                                                        {assign var="totalDiaCusto" value=0}
                                                                        {section name=i loop=$pedido}

                                                                              {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                                              {assign var="total" value=$total+$pedido[i].TOTAL}
                                                                              {assign var="quant" value=$quant+$pedido[i].QUANTIDADE}

                                                                              {if $pedido[i].EMISSAO neq $dia }
                                                                                    {if $dia neq ""}
                                                                                    {/if}
                                                                                    <th id="date" colspan="11">
                                                                                          {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                                    </th>
                                                                                    {assign var="dia" value=$pedido[i].EMISSAO}
                                                                              {/if}

                                                                              {if $pedido[i].ID neq $pedidoAtual}
                                                                                    <tr>
                                                                                          <th></th>
                                                                                          <th>SIT.</th>
                                                                                          <th>PED</th>
                                                                                          <th>N&#176;</th>
                                                                                          <th>DESCRI&Ccedil;&Atilde;O</th>
                                                                                          <th>QTDE</th>
                                                                                          <th style="width: 80px;">VLR UN</th>
                                                                                          <th>DESCONTO</th>
                                                                                          <th>FRETE</th>
                                                                                          <th width="130px">DESP ACESSORIAS</th>
                                                                                          <th>TOTAL ITEM</th>
                                                                                    </tr>
                                                                                    {assign var="pedidoAtual" value=$pedido[i].ID}
                                                                              {/if}

                                                                              {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                                              {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}
                                                                              
                                                                              {if $pedido[i].ID}
                                                                                    <tr>
                                                                                          <td></td>
                                                                                          <td>
                                                                                                {if $pedido[i].SITUACAO eq '9'}
                                                                                                      NFE
                                                                                                {elseif $pedido[i].SITUACAO eq '6'}
                                                                                                      PED
                                                                                                {else}
                                                                                                      -
                                                                                                {/if}
                                                                                          </td>
                                                                                          <td> {$pedido[i].ID}</td>
                                                                                          <td> {$pedido[i].NRITEM} </td>
                                                                                          <td> {$pedido[i].DESCRICAO} </td>
                                                                                          <td> {$pedido[i].QTSOLICITADA|number_format:2:",":"."}
                                                                                          </td>
                                                                                          <td> {$pedido[i].UNITARIO|number_format:2:",":"."}
                                                                                          </td>
                                                                                          <td> {$pedido[i].DESCONTO|number_format:2:",":"."}
                                                                                          </td>
                                                                                          <td> {$pedido[i].FRETE|number_format:2:",":"."}
                                                                                          </td>
                                                                                          <td> {$pedido[i].DESPACESSORIAS|number_format:2:",":"."}
                                                                                          </td>
                                                                                          <td> {$pedido[i].TOTAL|number_format:2:",":"."}
                                                                                          </td>
                                                                                    </tr>
                                                                              {/if}
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
</section>