<!-- page content -->
<!-- page content -->
{if $cssBootstrap eq true}
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
{/if}
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <img src="../../bianco/images/logo.png"  width=180 height=45 >
            </div>   
           
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  <div>
                        <h4>
                              <strong>EMPRESA</strong>
                        </h4>
                  </div>
            
                  <div>
                        <h6>
                              {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                              <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                        </h6>
                  </div>
            </div>
      </div>
</div>
