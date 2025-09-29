<section class="height100">
<!-- page content -->
<div class="right_col" role="main">
      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                        <center>
                        <strong>BALANCEAMENTO DE ESTOQUE </strong><br>
                        <h5>Data
                        <br>{$dataImp}
                        </h5>
                      </center>
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
                                                              <th>C&Oacute;DIGO</th>
                                                              <th>DESCRI&Ccedil;&Atilde;O</th>
                                                              <th>GRUPO</th>
                                                              <th>ESTOQUE</th>
                                                              <th>QTD M&Iacute;NIMA</th>
                                                              <th>ENCOMENDA</th>
                                                              <th>RESERVADO</th>
                                                              <th>DISPON&Iacute;VEL VENDA</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedido}                                                    
                                                                  <tr>
                                                                        
                                                                        <td> {$pedido[i].CODIGO} </td>
                                                                        <td> {$pedido[i].DESCRICAO} </td>
                                                                        <td> {$pedido[i].NOMEGRUPO} </td>
                                                                        <td> {$pedido[i].ESTOQUE|number_format:2:",":"."}</td>
                                                                        <td> {$pedido[i].QUANTMINIMA|number_format:2:",":"."}</td>
                                                                        <td> {$pedido[i].ENCOMENDA|number_format:2:",":"."}</td>
                                                                        <td> {$pedido[i].RESERVA|number_format:2:",":"."}</td>
                                                                        <td> {$pedido[i].DISPONIVELVENDA|number_format:2:",":"."}</td>
                                                                        
                                                                  </tr >                                                           
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
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
<!-- /page content -->
<style>
.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

@media print{
      @page{
            margin-top: 0;
            margin-bottom: 0;
            display: none;
            }
    
      .no-print{
      display: none;
      }

      td{
            font-size: 9px;
      }
      tr{
            font-size: 9px;
      }

}

</style>

