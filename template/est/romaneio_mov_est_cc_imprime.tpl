<section class="height100">
<!-- page content -->
<div class="right_col" role="main" height=100%>
      <div class="" align="center">
            <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-7 col-sm-7 col-xs-7 form-group" >
                <div>
                  <h2>
                      <strong>{$empresa[0].NOMEEMPRESA}</strong>
                  </h2>
                </div>
                <div>
                  <h6 id="empresaCab">
                      {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                      <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                  </h6>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 form-group" id="datahora">
                  Emisso&atilde;o: {$dataHora}
            </div>          
      </div>
      <div class="">   
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" align="center">
                	&nbsp;
            </div>           
      </div>

      <div>   
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" align="center">
                <h4><b>ROMANEIO MOVIMENTA&Ccedil;&Atilde;O DE ESTOQUE</b></h4>
            </div>           
      </div>

      <div class="">   
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" align="center">
                	&nbsp;
            </div>           
      </div>

      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <i><b>ORIGEM</b></i>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                 <i><b>DESTINO</b></i>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" id="divTop">
                  CENTRO CUSTO: <b>{$centroCustoSaida}</b>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" id="divTop">
                  CENTRO CUSTO: <b>{$centroCustoEntrada}</b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" id="divTop">
                   <i>N&deg;</i> NOTA: <b>{$notaSaida}</b> 
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" id="divTop">
                   <i>N&deg;</i> NOTA: <b>{$notaEntrada}</b> 
            </div>
      </div>


      <!-- page content -->
      <div class="right_col" role="main" id="tabG">
          <div class="clearfix"></div-->
                <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped" >
                                                      <thead>
                                                        <tr>
                                                              <th>DESCRI&Ccedil;&Atilde;O</th>
                                                              <th>C&Oacute;DIGO</th>
                                                              <th>UNIT&Aacute;RIO</th>
                                                              <th>QTD</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            <tr class="prod"> 
                                                                  <td class="prod">{$produto}</td>
                                                                  <td class="prod">{$codProduto}</td>
                                                                  <td class="prod">R$ {$valorProd|number_format:2:",":"."}</td>
                                                                  <td class="prod">{$qtd|number_format:2:",":"."}</td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                                {if $obs neq ""}
                                                <table class="table table-striped">
                                                      <tbody>
                                                            <tr class="observacao">
                                                                <td><i>Obs: {$obs}</i></td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                                {else}
                                                      &nbsp;
                                                {/if}
                                          </div>
                                    </div>
                        </div>
                </div>
          </div>
</div>

<!-- /page content -->
</section>
<style>

#empresaCab{
      font-size: 10px;
}

.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

.observacao{
      font-size: 13px;
}

#datahora{
      font-size: 8px;
      position: fixed;
      margin-left: 82%;
      padding: 0;
}

tr .prod{
    font-size: 15px;
}

th{
    font-size: 15px;
}

td .prod{
    font-size: 15px;
}

.table > thead > tr > th,
.table > tbody > tr > th,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > tbody > tr > td,
.table > tfoot > tr > td {
padding: 8px;
line-height: 0.9;
vertical-align: top;
border-top: 1px solid #ddd;
}

#tabG{
      margin-top: -5px;
      margin-left: -12px;
}

#divTop{
      margin-top: -10px;
}

.row invoice-info{
      margin-top: -20px;
}

@media print{
    a[href]:after {
    content: none !important;
    }
    
    @page{
    padding: 0;
    margin-top: 0;
    margin-bottom: 0;
    display: none;
    }

    .page-break { 
    page-break-after: always; 
    }

</style>