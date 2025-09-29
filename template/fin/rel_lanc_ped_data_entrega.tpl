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
                      
                      <strong>LANÇAMENTO PEDIDOS POR DATA ENTREGA</strong><br>
                        Periodo - {$dataInicio} | {$dataFim}
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
                                                              <th style="color: transparent">EMISSAO</th>
                                                              <th>PESSOA</th>
                                                              <th>DOCTO</th>
                                                              <th>PREVISÃO ENTREGA</th>
                                                              <th>DATA ENTREGA</th>
                                                              <th>ENTREGA</th>
                                                              <th>SITUACAO</th>
                                                              <th>CENTRO CUSTO</th>
                                                              <th>GENERO</th>
                                                              <th>EMISSAO</th>
                                                              <th>VENCIMENTO</th>
                                                              <th>MOVIMENTO</th>
                                                              <th>TOTAL</th>
                                                              
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {assign var="dia" value=""}
                                                            {assign var="totalDia" value=0}
                                                            {assign var="totalDiaCusto" value=0}
                                                            {section name=i loop=$pedido}
                                                            
                                                                  {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                                  {assign var="total" value=$total+$pedido[i].TOTAL}
                                                                  {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}

                                                                  {if $pedido[i].DATAENTREGA neq $dia }  
                                                                        {if $dia neq ""}  
                                                                              <!--tr>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>                                                                              
                                                                              <td><h4>TOTAL DO DIA</h4></td>
                                                                              {if $tipoUsuario neq ""}<td><h4>R$ {$totalDiaCusto|number_format:2:",":"."}</h4></td>{/if}
                                                                              <td><h4>R$ {$totalDia|number_format:2:",":"."}</h4></td>
                                                                              
                                                                              {assign var="totalDia" value=0}
                                                                              {assign var="totalDiaCusto" value=0}
                                                                              
                                                                              </tr-->                                                                      
                                                                             
                                                                        {/if}                                                          
                                                                        <th id="date" colspan="12">{$pedido[i].DATAENTREGA|date_format:"%d/%m/%Y"}</th>
                                                                        {assign var="dia" value=$pedido[i].DATAENTREGA}
                                                                  {/if}
                                                                  <!--tr>
                                                                        <th></th>
                                                                        <th>PED</th>                                                                        
                                                                        <th>EMISSAO</th>
                                                                        <th>CLIENTE</th>
                                                                        <th>SITUAÇÃO</th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        
                                                                  </tr-->
                                                                  <tr>
                                                                        <td></td> 
                                                                        <td> {$pedido[i].NOME} </td>
                                                                        <td> {$pedido[i].SERIE} - {$pedido[i].DOCTO} </td>
                                                                        <td> {$pedido[i].PRAZOENTREGA} </td>
                                                                        <td> {$pedido[i].DATAENTREGA|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].SITUACAOPED} </td>
                                                                        <td> {$pedido[i].SITUACAOPGTO}</td>
                                                                        <td> {$pedido[i].FILIAL} </td>
                                                                        <td> {$pedido[i].DESCGENERO} </td>
                                                                        <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].VENCIMENTO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].PAGAMENTO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].TOTAL} </td>
                                                                        
                                                                  </tr>
                                                                        {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                                        {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}
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
                                                                        {section name=k loop=$pedidoItem}
                                                                              {if $pedido[i].DOCTO eq $pedidoItem[k].ID}
                                                                                    <tr>
                                                                                          <td></td> 
                                                                                          <td> </td>

                                                                                          <td> {$pedidoItem[k].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                                          <td> {$pedidoItem[k].NOMECLIENTE} </td>
                                                                                          <td> {$pedidoItem[k].TPDOCTO} </td>
                                                                                          <td> {$pedidoItem[k].MODOPAG} </td>
                                                                                          <td> {$pedidoItem[k].TOTAL|number_format:2:",":"."} </td>
                                                                                          <td> {$pedidoItem[k].SITUACAOPAG} </td>
                                                                                          <td> </td>
                                                                                          <td> </td>
                                                                                          <td> </td>
                                                                                          <td> </td>
                                                                                          </td>
                                                                                          
                                                                                    </tr >
                                                                              {/if}
                                                                        {/section}
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
                                                                         <tr>
                                                                              <th><h5 style="color: transparent">space</h5></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              <th></th>
                                                                              
                                                                        </tr>
                                                                        
                                                                  <p>
                                                                  
                                                                  
                                                            {/section} 
                                                           <!-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>PRODUTOS :</h4></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>FRETE :</h6></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>DESCONTO :</h6></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>  -->
                                                            <!--tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td><h4>TOTAL DO DIA</h4></td>
                                                                  {if $tipoUsuario neq ""}<td><h4>R$ {$totalDiaCusto|number_format:2:",":"."}</h4></td>{/if}
                                                                  <td><h4>R$ {$totalDia|number_format:2:",":"."}</h4></td>
                                                            
                                                            
                                                            --><!--
                                                            <td><h4> {($pedido[0].TOTAL + $pedido[0].DESCONTO - $pedido[0].DESPACESSORIAS - $pedido[0].FRETE)|number_format:2:",":"."} </h4></td>
                                                            -->
                                                            <!--/tr-->  
                                                            <!--tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h4>TOTAL GERAL</h4></td>
                                                                {if $tipoUsuario neq ""}<td><h4>R$ {$totalCusto|number_format:2:",":"."}</h4></td>{/if}
                                                                <td><h4>R$ {$total|number_format:2:",":"."}</h4></td>
                                                                --><!--
                                                                <td><h4> {($pedido[0].TOTAL + $pedido[0].DESCONTO - $pedido[0].DESPACESSORIAS - $pedido[0].FRETE)|number_format:2:",":"."} </h4></td>
                                                                -->
                                                            <!--/tr-->
                                                      </tbody>
                                                </table>
                                                <!--
                                                <table class="table table-striped">
                                                      <tbody>
                                                           <tr>
                                                                <td>               </td>
                                                                <td>               </td>
                                                                <td>               </td>
                                                                <td>Produto        </td>
                                                                <td>Desconto       </td>
                                                                <td>Desp Acessórias</td>
                                                                <td>Frete          </td>
                                                                <td>Total          </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Totais</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{($pedido[0].TOTAL + $pedido[0].DESCONTO - $pedido[0].DESPACESSORIAS - $pedido[0].FRETE)|number_format:2:",":"."} </td>
                                                                <td>{$pedido[0].DESCONTO|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].DESPACESSORIAS|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].FRETE|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].TOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                                -->
                                          </div>
                                    </div>
                              </section>
                        </div>
                </div>
          </div>
      </div>
      <!--
      <div class="row invoice-info">
            <div align="left" class="col-md-12 col-sm-12 col-xs-12 form-group">
            Observações: {$pedido[0].OBS}
            </div>
      </div>
      <div class="row invoice-info">
            <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                  Confira seus produtos no ato da entrega. O material será descarregado aonde for possível estacionar o véiulo.(3% de perca é considerado normal - conf NBR 13.858/1e2) não aceitamos devolução.
            </div>
      </div>
      -->
      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
<!-- /page content -->

