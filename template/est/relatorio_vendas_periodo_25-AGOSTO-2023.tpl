<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                      <strong>VENDAS NO PERÍODO</strong><br>
                        Periodo - {$periodoIni} | {$periodoFim}
                  </h2>
                </div>
            </div>     
      </div>
      
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
                                                              <th>EMISSAO</th>
                                                              <th>SERIE</th>
                                                              <th>NF</th>
                                                              <th>CLIENTE</th>
                                                              <th>TOTAL</th>
                                                              
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$lanc}
                                                                {assign var="total" value=$total+$lanc[i].TOTALNF}
                                                                  
                                                                  <tr>
                                                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"}</td> 
                                                                        <td> {$lanc[i].SERIE} </td> 
                                                                        <td> {$lanc[i].NUMERO} </td>
                                                                        <td> {$lanc[i].NOMECLIENTE} </td>
                                                                        <td> {$lanc[i].TOTALNF|number_format:2:",":"."}</td>
                                                                  </tr >
                                                                  <p>
                                                                  
                                                                  
                                                            {/section} 
                                                            <tr>
                                                                  <td><h3><b>TOTAL </b></h3></td> 
                                                                  <td>  </td> 
                                                                  <td>  </td>
                                                                  <td>  </td>
                                                                  <td><h3><b> {$total|number_format:2:",":"."}</b></h3></td>
                                                            </tr >
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

