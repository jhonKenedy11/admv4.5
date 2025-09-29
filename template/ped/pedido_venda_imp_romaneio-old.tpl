        <!-- page content -->
        <div class="right_col" role="main">
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


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                              <h6>
                                  <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                              <small class="pull-right">
                              <br><strong>{$empresa[0].NOMEEMPRESA}</strong>
                              <br>{$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO}                          
                              <br>{$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                              <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
                              <br>Email: {$empresa[0].EMAIL}                                  
                              </small>
                                  
                              <h3 class="pull-left">  Romaneio
                              </h3>
                          </h6>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                          Cliente
                          <address>
                              <strong>{$pedido[0].NOME}</strong>
                              <br><strong>{$pedido[0].NOMEREDUZIDO}</strong>
                              <h6>{$pedido[0].TIPOEND} {$pedido[0].TITULOEND} {$pedido[0].ENDERECO}, {$pedido[0].NUMERO}, {$pedido[0].COMPLEMENTO} {$pedido[0].BAIRRO}                          
                              <br>{$pedido[0].CIDADE}, {$pedido[0].UF} {$pedido[0].CEP}
                              <br>Fone: ({$pedido[0].FONEAREA}) {$pedido[0].FONENUM} - Email: {$pedido[0].EMAIL}</h6>
                          </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          <H3>Pedido: {$pedido[0].PEDIDO}</H3>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          Data Emiss&atilde;o...: {$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO|date_format:"%H:%M:%S"}
                          <br>
                          Data Impress&atilde;o: {$dataImp}
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row small">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                                <tr>
                                        <th>Ref.</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Qtde</th>
                                        <th>Conferido</th>
                                        <th>Lote/Data Validade</th>
                                        <th>Valor Unit&aacute;rio</th>
                                        <th>Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                    {section name=i loop=$pedidoItem}
                                        {assign var="total" value=$pedidoItem[i].QUANTIDADE*$pedidoItem[i].UNITARIO}
                                        {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}
                                        <tr>
                                            <td> {$pedidoItem[i].ITEMESTOQUE} </td>
                                            <td> {$pedidoItem[i].DESCRICAO} </td>
                                            <td> {$pedidoItem[i].QUANTIDADE|number_format:0:",":"."} </td>
                                            <td><div class="text-left"></span>|_______|</div></td>
                                            <td> {$pedidoItem[i].FABLOTE} / {$pedidoItem[i].FABDATAVALIDADE|date_format:"%d/%m/%Y"} </td>
                                            <td> {$pedidoItem[i].UNITARIO|number_format:2:",":"."} </td>
                                            <td> {$total|number_format:2:",":"."} </td>
                                        </tr>
                                    <p>
                                    {/section} 
                                        <tr>
                                            <td></td>
                                            <td><h4>T O T A L :</h4></td>
                                            <td><h4> {$quant|number_format:0:",":"."} </h4></td>
                                            <td> </td>
                                            <td></td>
                                            <td></td>
                                            <td><h4> {$pedido[0].TOTAL|number_format:2:",":"."} </h4></td>
                                        </tr>

                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                      <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <strong>Observa&ccedil;&otilde;es: </strong><br><br>
                            <strong>____________________________________________</strong><br><br>
                            <strong>____________________________________________</strong><br><br>
                            <strong>Observa&ccedil;&otilde;es Almoxarifado: </strong><br><br>
                            <strong>____________________________________________</strong><br><br>
                            <strong>____________________________________________</strong>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                           Separador(a): __________________________________<br><br>
                           Conferente(a): _________________________________<br><br>
                           Famarceutico(a): _______________________________<br><br>
                          Data Conferencia: ______/______/______<br><br>
                          Volumes: |__________|
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->


                      <!-- this row will not appear when printing -->
                      <div class="row no-print">
                        <div class="col-xs-12">
                          <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

