<style>
img{
    border-radius: 5px;
}
#dataHora {
    font-size: 9px;
    margin-left: 78em;
    margin-top: -10px;
    position: absolute;
}
</style>
<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div> 
            <div class="col-md-1 col-sm-1 col-xs-1 form-group">
                <h1 style="visibility:hidden">space </h1>
            </div>  
            <div class="col-md-5 col-sm-5 col-xs-5 form-group">
                
                <h2><strong>
                    RECIBO
                </strong></h2>
                

            </div>  
            <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                <h2><strong>{$pedido[0].TOTAL|number_format:2:",":"."}</strong></h2>
            </div>         
      </div>

    <div class="" id="dataHora">
        {$dataImp}
    </div>
     
    
      <!-- page content -->
      <div class="right_col" role="main">
          <div class="clearfix"></div-->
                <div class="x_panel">
                    <div class="col-xs-12 table">
                         <div class="row invoice-info">
                                <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                    <b> 
                                        Recebemos de 
                                        {if $tipoLancamento == "P"}
                                            {$empresa[0].NOMEEMPRESA}
                                        {else}
                                            {$cliente} 
                                        {/if}
                                            , conforme documento {$pedido[0].PAGAMENTO|date_format:"%d/%m/%Y"}
                                            
                                        {$pedido[0].DOCTO}/{$pedido[0].SERIE} {$pedido[0].PARCELA}º parcela, a importância de 
                                        {$valorExtenso} 
                                        
                                        {if $tipoLancamento == "R"}
                                            referente à:
                                            <b>
                                                Conta {$dadosBancario[0]['NOMECONTABANCO']} Ag: {$dadosBancario[0]['AGENCIA']}
                                            </b>
                                        {/if}
                                    </b>


                                </div>
                                <div class="row invoice-info">
                                    {if $pedido[0].OBSCONTABIL neq ''}
                                            <div class="row invoice-info" id="obscontabil">
                                                Obs.: {$pedido[0].OBSCONTABIL}
                                            </div>
                                            </br>
                                    {/if}
                                    <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center">
                                        <p>{$pedido[0].PAGAMENTO|date_format:"%d/%m/%Y"} {$pedido[0].DOCTO}/{$pedido[0].SERIE} {$pedido[0].PARCELA} parc </p>
                                        <br>
                                        Para maior clareza, firmamos o presente
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center" >
                                        <p>{$pedido[0].TOTAL|number_format:2:",":"."} </p>
                                        <br>
                                        {$empresa[0].CIDADE} , {$dataAtual|date_format:"%d/%m/%Y"}
                                    </div>
                                </div>
                               
                                <div class="row invoice-info">
                                        <br><br>
                                        <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            _____________________________________________________
                                        </div>
                                </div>

                                <div class="row invoice-info">
                                        <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <div>
                                                <h4>
                                                    {if $tipoLancamento == "P"}
                                                        <strong>{$cliente}</strong>
                                                    {else}
                                                        <strong>{$empresa[0].NOMEEMPRESA}</strong>
                                                    {/if}
                                                </h4>
                                            </div>
                                            <div>
                                                <h6>
                                                    {if $tipoLancamento == "P"}
                                                        {$clienteArray[0].TIPOEND} {$clienteArray[0].TITULOEND} {$clienteArray[0].ENDERECO}, {$clienteArray[0].NUMERO}, {$clienteArray[0].COMPLEMENTO} {$clienteArray[0].BAIRRO} {$clienteArray[0].CIDADE}, {$clienteArray[0].UF} {$clienteArray[0].CEP}
                                                        {if $clienteArray[0].CNPJCPF || $clienteArray[0].INSCESTRG}
                                                            <br>
                                                            {if $clienteArray[0].CNPJCPF}
                                                                CPF/CNPJ: {$clienteArray[0].CNPJCPF}
                                                            {/if}
                                                            {if $clienteArray[0].INSCESTRG}
                                                                RG/IE: {$clienteArray[0].INSCESTRG}
                                                            {/if}
                                                        {/if}  
                                                        <br>Fone: ({$clienteArray[0].FONEAREA}) {$clienteArray[0].FONENUM} Email: {$clienteArray[0].EMAIL}  
                                                    {else}
                                                        {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                                                        <br> CNPJ: {$empresa[0].CNPJ} IE: {$empresa[0].INSCESTADUAL}
                                                        <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}  
                                                    {/if}
                                            
                                                </h6>
                                            </div>
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>
          </div>
      </div>
     

      


      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i></button>
            </div>
      </div>

</div>
<!-- /page content -->

  
<style>
    b {
        font-size:16px;
    }
    p{
        font-size:14px;
    }
    #obscontabil{
        margin-left: 20px;
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
    .no-print{
     display: none;
    }
</style>
