<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <h2>
                      <strong>MOVIMENTO DE ESTOQUE CLIENTE</strong><br>
                        Periodo - {$periodoIni} | {$periodoFim}
                  </h2>
            </div>              
      </div>  
      <!-- page content -->
      <div class="right_col" role="main"> <!-- just this -->
            <div class="row small">
                  <div class="col-xs-12 table">
                        <table class="table table-striped" >
                              <h4>CONTA : {$cliente}</h4>
                              <thead>
                                <!--th id="cliente" colspan="10">CONTA: {$cliente}</th-->
                                 <tr>
                                      <th>        </th>
                                      <th>TIPO    </th>
                                      <th>DOC.    </th>
                                      <th>EMISSAO </th>
                                      <th>USUARIO </th>
                                      <th>COD.    </th>
                                      <th>PRODUTO </th>
                                      <th>UNIDADE </th>
                                      <th>QTDE    </th>
                                      <th>TOTAL   </th>
                                      <th>OBS        </th>
                                  </tr>
                              </thead>
                              <tbody>           
                                    {assign var="quantEntrada" value=0}
                                    {assign var="totalEntrada" value=0}
                                    {assign var="quantSaida" value=0}
                                    {assign var="totalSaida" value=0}                                                 
                                    {section name=i loop=$pedido}
                                          {if $pedido[i].TIPO eq 'ENTRADA' }
                                                {$quantEntrada = $quantEntrada+$pedido[i].QTDE}
                                                {$totalEntrada = $totalEntrada+$pedido[i].TOTAL}
                                          {else}
                                                {$quantSaida = $quantSaida+$pedido[i].QTDE}
                                                {$totalSaida = $totalSaida+$pedido[i].TOTAL}
                                          {/if}                                                                  
                                          <tr>
                                                <td> </td>
                                                <td> {$pedido[i].TIPO} </td>
                                                <td> {$pedido[i].ID} </td>
                                                <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                <td> {$pedido[i].NOMEUSUARIO} </td>
                                                <td> {$pedido[i].CODIGO} </td>
                                                <td> {$pedido[i].DESCRICAO} </td>
                                                <td> {$pedido[i].UNIDADE} </td>
                                                <td> {$pedido[i].QTDE|number_format:0:",":"."} </td>
                                                <td> {$pedido[i].TOTAL|number_format:2:",":"."} </td>
                                                <td> {$pedido[i].OBS} </td>
                                          </tr >
                                    {/section} 
                                    <!--tr>
                                          <td><h4>TOTAL</h4></td>
                                          <td><h5>ENTRADA</h5></td>
                                          <td><h5> {$quantEntrada}</h5></td>
                                          <td><h5>R$ {$totalEntrada|number_format:2:",":"."}</h5></td>
                                          <td><h5>SAIDA</h5></td>
                                          <td><h5> {$quantSaida}</h5></td>
                                          <td><h5>R$ {$totalSaida|number_format:2:",":"."}</h5></td>
                                          <td><h5>SALDO</h5></td>
                                          <td><h5> {($quantEntrada-$quantSaida)}</h5></td>
                                          <td><h5>R$ {($totalEntrada - $totalSaida)|number_format:2:",":"."}</h5></td>
                                    </tr-->
                                    <tr><b>
                                          <td></td>
                                          <td><h4>TOTAL</h4></td>
                                          <td><h5>QUANTIDADE</h5></td>
                                          <td><h5>VALOR</h5></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                    </b></tr>
                                    <tr>
                                          <td></td>
                                          <td><h5>Entradas</h5></td>
                                          <td><h5> {$quantEntrada|number_format:2:",":"."}</h5></td>
                                          <td><h5>R$ {$totalEntrada|number_format:2:",":"."}</h5></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                    </tr>
                                    <tr>
                                          <td></td>
                                          <td><h5>Saídas</h5></td>
                                          <td><h5> {$quantSaida|number_format:2:",":"."}</h5></td>
                                          <td><h5>R$ {$totalSaida|number_format:2:",":"."}</h5></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                    </tr>
                                    <tr>
                                          <td></td>
                                          <td><h5>Saldo (S-E)</h5></td>
                                          <td><h5> {($quantEntrada-$quantSaida)|number_format:2:",":"."}</h5></td>
                                          <td><h5>R$ {($totalSaida - $totalEntrada)|number_format:2:",":"."}</h5></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                    </tr>
                              </tbody>
                        </table>
                  </div>
            </div>
      </div>
</div>
<!-- /page content -->

