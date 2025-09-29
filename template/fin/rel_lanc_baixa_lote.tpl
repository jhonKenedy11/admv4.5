<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <center>
                  <h2>
                        
                      <strong>{$empresa[0].NOMEEMPRESA}</strong><br>
                      
                      <strong>LANÇAMENTO TITULOS BAIXADO</strong><br>
                        Data Movimento - {$dataInicio} 
                  </h2>
                  </center>
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
          <div class="clearfix"></div-->
                <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped" >
                                                      <thead>
                                                         <tr>
                                                              <th>DOCTO</th>
                                                              <th>PESSOA</th>
                                                              <th>CONTA BANCARIA</th>
                                                              <th>SITUACAO</th>
                                                              <th>MOVIMENTO</th>
                                                              <th>TOTAL</th>
                                                              
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedido}
                                                            
                                                                  {assign var="total" value=$total+$pedido[i].TOTAL}

                                                                  <tr>
                                                                        <td> {$pedido[i].SERIE} - {$pedido[i].DOCTO} </td>
                                                                        <td> {$pedido[i].NOME} </td>
                                                                        <td> {$pedido[i].CONTABANCARIA} </td>
                                                                        <td> {$pedido[i].SITUACAOPAG}</td>
                                                                        <td> {$pedido[i].DATAMOVIMENTO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].TOTAL} </td>
                                                                        
                                                                  </tr>
                                                                      
                                                                         <!--tr>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th>VENCIMENTO</th>
                                                                              <th>CLIENTE</th>
                                                                              <th>TIPO DOCTO</th>
                                                                              <th>MODO PGTO/REC</th>
                                                                              <th>TOTAL</th>
                                                                              <td> </td>
                                                                              <th style="width: 100px">SITUACAO</th>
                                                                              <td> </td>
                                                                              <td> </td>
                                                                              
                                                                        </tr-->
                                                                       
                                                                        <!--tr>
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
                                                                              <td> {$pedido[i].FRETE|number_format:2:",":"."} </td>
                                                                              <td> {$pedido[i].DESCONTO|number_format:2:",":"."} </td>
                                                                              <td> {$pedido[i].DESPACESSORIAS|number_format:2:",":"."} </td>                                                                        
                                                                              <td> {$pedido[i].TOTAL|number_format:2:",":"."}
                                                                              
                                                                              </td>
                                                                              
                                                                        </tr -->
                                                                        
                                                                        
                                                                  
                                                                  
                                                            {/section} 
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>T O T A L :</h4></td>
                                                                <td><h6> {$total|number_format:2:",":"."} </h6></td>
                                                            </tr>
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
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
<!-- /page content -->

