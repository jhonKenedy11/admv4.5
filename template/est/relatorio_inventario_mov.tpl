<section class="height100">
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
                      <strong>ITENS INVENT&Aacute;RIO MOVIMENTADO</strong><br>
                        Identifica&ccedil;&atilde;o Invent&aacute;rio - {$idInventario}
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
                                <th>DESCRI&Ccedil;&Atilde;O</th>
                                <th>UNIDADE</th>
                                <th>QUANT MOVIMENTADA</th>
                                <th>QUANT SISTEMA</th>
                                <th>QUANT F&Iacute;SICA</th>
                            </tr>
                        </thead>
                        <tbody>
                              {section name=i loop=$pedido}   
                              {math assign="margem" equation=(($pedido[i].VENDA*100)/$pedido[i].CUSTOCOMPRA)-100 format="%.2f"}           
                                    <tr>
                                          <td> {$pedido[i].CODPRODUTO} </td>
                                          <td> {$pedido[i].DESCPRODUTO} </td>
                                          <td> {$pedido[i].UNIDADE} </td>
                                          <td> {$pedido[i].QUANTIDADEMOVIMENTADA|number_format:2:",":"."} </td>
                                          <td> {$pedido[i].QUANTIDADEANTERIOR|number_format:2:",":"."} </td>
                                          <td> {$pedido[i].QUANTIDADENOVA|number_format:2:",":"."} </td>
                                    </tr >
                                    
                               {$margem = ''}     
                              {/section} 
                        </tbody>
                  </table>
            </div>
      </div>
      
</div>
<!-- /page content -->
</section>


<style>
.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

@media print{
    a[href]:after {
    content: none !important;
    }
    
    @page{
    margin-top: 0;
    margin-bottom: 0;
    display: none;
    }

    .page-break { 
    page-break-after: always; 
    }
</style>

