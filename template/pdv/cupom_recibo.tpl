        <!-- page content -->
        <div class="left_col" role="main" >
          <div class="">
            <!--div class="page-title">
              <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  {$empresa[0].NOMEEMPRESA}<br>
                  ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2>Pedido: {$pedido[0].PEDIDO}<BR>Romaneio</h2>
              </div>
                
            </div>
            <div class="clearfix"></div-->


                <div class="x_panel">
                  <div class="x_content">


                    <section class="">
                      <!-- title row -->
                      <div class="row">
                      <div class="row no-print">
                        <div class="col-xs-12">
                          <button class="btn btn-default" onclick="window.print();">R E C I B O</button>
                        </div>
                      </div>
                        <div class="col-md-12">
                              <h6>
                                  <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                              <small class="pull-left">
                              <br><strong>{$empresa[0].NOMEEMPRESA}</strong>
                              <br>{$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO}                          
                              <br>{$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                              <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
                              </small>
                          </h6>
                        </div>
                        <!-- /.col -->
                      </div>
                      <div class="row col-sm-12 invoice-col">
                         <small class="pull-left">
                         Pedido: {$pedido[0].ID}
                         Data: {$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO|date_format:"%H:%M:%S"}<br>
                         Cliente: {$pedido[0].NOME}
                      </div>
                  </div>
                       </small>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row small">
                        <div class="col-xs-12">
                          <table class="table-condensed">
                            <thead>
                                <tr>
                                        <th>Ref.</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Qtde</th>
                                        <th>Valor Unit&aacute;rio</th>
                                        <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                    {section name=i loop=$pedidoItem}
                                        {assign var="total" value=$pedidoItem[i].QTSOLICITADA*$pedidoItem[i].UNITARIO}
                                        {assign var="quant" value=$quant+$pedidoItem[i].QTSOLICITADA}
                                        <tr>
                                            <td> {$pedidoItem[i].ITEMESTOQUE} </td>
                                            <td> {$pedidoItem[i].DESCRICAO} </td>
                                            <td> {$pedidoItem[i].QTSOLICITADA|number_format:0:",":"."} </td>
                                            <td> {$pedidoItem[i].UNITARIO|number_format:2:",":"."} </td>
                                            <td> {$pedidoItem[i].TOTAL|number_format:2:",":"."} </td>
                                        </tr>
                                    <p>
                                    {/section} 
                                        <tr><td colspan="5"></td></tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Número Itens:</td>
                                            <td> {$quant|number_format:0:",":"."}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Sub Total:</td>
                                            <td> {$pedido[0].TOTALPRODUTOS|number_format:2:",":"."} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Desconto:</td>
                                            <td> {$pedido[0].DESCONTO|number_format:2:",":"."} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Taxa:</td>
                                            <td> {$pedido[0].TAXAENTREGA|number_format:2:",":"."} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">T O T A L:</td>
                                            <td> {$pedido[0].TOTAL|number_format:2:",":"."} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Valor Recebido:</td>
                                            <td> {$pedido[0].TOTALRECEBIDO|number_format:2:",":"."} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Troco:</td>
                                            <td> {($pedido[0].TOTALRECEBIDO - $pedido[0].TOTAL)|number_format:2:",":"."} </td>
                                        </tr>
                                        <!--tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">Forma Rec.:</td>
                                            <td> {$pedido[0].FORMARECEBIMENTO|number_format:2:",":"."} </td>
                                        </tr-->

                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->


                      <!-- this row will not appear when printing -->
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

