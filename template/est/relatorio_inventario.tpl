<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                        <!--
                      <strong>{$empresa[0].NOMEEMPRESA}</strong>
                      -->
                      <center>
                      <strong>ITENS INVENTARIO </strong><br>
                        Identificação Inventario - {$idInventario}
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
     

      <!-- page content -->
      <div class="right_col" role="main">
            <div class="col-xs-12 table">
                  <table class="table table-striped" >
                        <thead>
                           <tr>
                                <th>COD</th>
                                <th>DESCRICAO</th>
                                <th>EAN</th>
                                <th>UNIDADE</th>
                                <th>QUANTIDADE</th>
                            </tr>
                        </thead>
                        <tbody>
                              {section name=i loop=$pedido}
                                       
                                    {* {math assign="margem" equation=(($pedido[i].VENDA*100)/$pedido[i].CUSTOCOMPRA)-100 format="%.2f"}            *}
                                          <tr>
                                                <td> {$pedido[i].CODPRODUTO} </td>
                                                <td> {$pedido[i].DESCPRODUTO} </td>
                                                <td> {$pedido[i].EAN}</td>
                                                <td> {$pedido[i].UNIDADE} </td>
                                                <td> _____________________ </td>
                                          </tr >
                                          
                                    {$margem = ''}     
                              {/section} 
                        </tbody>
                  </table>
            </div>
      </div>
      
      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
<!-- /page content -->

