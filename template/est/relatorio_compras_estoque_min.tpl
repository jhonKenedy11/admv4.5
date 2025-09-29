<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <div>
                        <h2>
                              <!--
                      <strong>{$empresa[0].NOMEEMPRESA}</strong>
                      -->
                              <center>
                                    <strong>COMPRAS POR ESTOQUE MINIMO</strong><br>
                                    <!-- Periodo
                        {$periodoIni} | {$periodoFim}
                        -->
                              </center>
                        </h2>
                  </div>
                  <!--
                <div>
                  <h6>
                      {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                      <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                  </h6>
                </div>
                -->
            </div>
            <!--
            <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2>Pedido: {$pedido[0].PEDIDO}</h2>
            </div>     
            -->
      </div>
      <!--
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  Data:<strong>{$pedido[0].EMISSAO|date_format:"%d/%m/%Y"}</strong>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  Vendedor: {$pedido[0].USRFATURA_}
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  Cliente: <strong>{$pedido[0].NOME}</strong>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <b> Fone: {$pedido[0].FONE} Celular: {$pedido[0].CELULAR} </b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <b> Endereço : {$pedido[0].TIPOEND} {$pedido[0].TITULOEND} {$pedido[0].ENDERECO}, {$pedido[0].NUMERO}, 
                                 {$pedido[0].COMPLEMENTO} {$pedido[0].BAIRRO}                          
                                 {$pedido[0].CIDADE}, {$pedido[0].UF} {$pedido[0].CEP}</b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  Previsão de Entrega: <strong>{$pedido[0].DATAENTREGA|date_format:"%d/%m/%Y"}</strong>
            </div>
      </div>
      -->

      <!-- page content -->
      <div class="right_col" role="main">
            <div class="clearfix">
                  </div-->
                  <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped">
                                                      <thead>
                                                            <tr>
                                                                  <th>COD</th>
                                                                  <th>DESCRICAO</th>
                                                                  <th>GRUPO</th>
                                                                  <th>QTDE DISPONIVEL</th>
                                                                  <th>ESTOQUE MINIMO</th>
                                                                  <th>QTDE ULTIMA COMPRA</th>
                                                                  <th>DATA ULTIMA COMPRA</th>
                                                                  <th>ULTIMA NEGOCIAÇÃO</th>
                                                                  <th>ULTIMA COMPRA</th>
                                                                  <th>PREÇO VENDA</th>
                                                                  <th>MARGEM</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedido}
                                                                  {if isset($pedido[i].CUSTOCOMPRA) && $pedido[i].CUSTOCOMPRA > 0}
                                                                        {math assign="margem" equation=(($pedido[i].VENDA*100)/$pedido[i].CUSTOCOMPRA)-100 format="%.2f"}
                                                                  {/if}

                                                                  <tr>

                                                                        <td> {$pedido[i].CODIGO} </td>
                                                                        <td> {$pedido[i].DESCRICAO} </td>
                                                                        <td> {$pedido[i].NOMEGRUPO} </td>
                                                                        <td> {$pedido[i].ESTOQUE}</td>
                                                                        <td> {$pedido[i].QUANTMINIMA}</td>
                                                                        <td> {$pedido[i].QUANTULTIMACOMPRA}</td>
                                                                        <td> {$pedido[i].DATAULTIMACOMPRA|date_format:"%d/%m/%Y"}
                                                                        </td>
                                                                        <td> {$pedido[i].PRECOINFORMADO}</td>
                                                                        <td> {$pedido[i].CUSTOCOMPRA}</td>
                                                                        <td> {$pedido[i].VENDA} </td>
                                                                        <td> {$margem|number_format:2:",":"."}% </td>

                                                                  </tr>

                                                                  <p>


                                                                  {/section}

                                                      </tbody>
                                                </table>

                                          </div>
                                    </div>
                              </section>
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