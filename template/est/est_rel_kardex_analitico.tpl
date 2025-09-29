<section class="height100">
<!-- page content -->
<div class="right_col" role="main">
      <div class="col-md-12 col-sm-12 col-xs-12 form-group">

            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" onclick="window.print();" aloign="right" width=180 height=45 border="0"></A>
            </div>  
             
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                      <strong>Relatório Kardex Analítico</strong><br>
                        Periodo - {$periodoIni} | {$periodoFim}
                  </h2>
                </div>
            </div> 
      
            <div class="col-md-2 col-sm-2 col-xs-2 form-group" align="right" id="date">
                  {$dataAtual|date_format:"%d/%m/%Y"}
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
                                                              <th>OPERAÇÃO</th>
                                                              <th>USUARIO</th>
                                                              <th>EMISSÃO</th>
                                                              <th>COD. DOC</th>
                                                              <th>COD. PRODUTO</th>
                                                              <th>PRODUTO</th>
                                                              <th>QTDE</th>
                                                              <th>SALDO</th>
                                                              
                                                          </tr>
                                                      </thead>
                                                      <tbody>      
                                                             
                                                            <tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td><strong>SALDO INICIAL</strong></td>
                                                                  <td>{($saldoIni-$saldoIniSaida)|number_format:2:",":"."}</td>
                                                            </tr>  
                                                            {assign var="saldo" value=($saldoIni-$saldoIniSaida)}                                                 
                                                            {section name=i loop=$pedido}
                                                                  {assign var="quantEntrada" value=$quantEntrada+$pedido[i].ENTRADA}
                                                                  {if $pedido[i].TIPO eq 'ENTRADA' }
                                                                        {assign var="totalEntrada" value=$totalEntrada+$pedido[i].QTDE}
                                                                        {assign var="saldo" value=$saldo+$pedido[i].QTDE}
                                                                  {else}
                                                                        {assign var="saldo" value=$saldo-$pedido[i].QTDE}
                                                                  {/if}
                                                                  
                                                                  
                                                                  
                                                                  <tr>
                                                                        <td> {$pedido[i].TIPO}</td>
                                                                        <td> {$pedido[i].NOMEUSUARIO}</td>
                                                                        <td> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                        <td> {$pedido[i].NUMERO} </td>
                                                                        <td> {$pedido[i].CODIGO} </td>
                                                                        <td> {$pedido[i].DESCRICAO} </td>                                                                        
                                                                        <td> {$pedido[i].QTDE|number_format:0:",":"."} </td>
                                                                        <td>{$saldo|number_format:2:",":"."}</td>
                                                                        
                                                                       
                                                                  </tr>
                                                                        
                                                            {/section} 
                                                            
                                                            <tr>
                                                                <td></td>
                                                                <td></td> 
                                                                <td></td>                                                                
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h5>TOTAL:</h5></td>
                                                                <td><h5>{$saldo|number_format:2:",":"."}</h5></td>
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
      </p no-print>
      </p no-print>

      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>

</div>
</section>
<!-- /page content -->
<style>
.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}
#date{
      font-size: 9px;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

.x_content{
      margin: 0 0 0 0 !important;
      padding: 0 !important;
}
.table{
      margin: 0 0 0 0 !important;
}

@media print {
      @page{
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
            display: none;
            }

      .no-print{
            display: none;
      }
      .table{
            border-style: 1px solid !important;
            margin: -5px -2px -5px 0px !important;
}
  }
</style>

