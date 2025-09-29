<!-- page content -->
{if $cssBootstrap eq true}
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
{/if}

<!--head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test</title>
<style type="text/css">
    table { page-break-inside:auto }
    div   { page-break-inside:avoid; } /* This is the key */
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
</style>
</head-->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <img {if $cssBootstrap == true}src="{$urlImg}"{else}src="images/logo.png"{/if}  aloign="right" width=180 height=45 border="0"></A>
            </div>   
            
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  <div>
                        <h5>
                              <strong>{$empresa[0].NOMEEMPRESA}</strong>
                        </h5>
                  </div>
            
                  <div>
                        <h6>
                              {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                              <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                        </h6>
                  </div>

            </div>
            
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line" style="width: 250px;">
                  <h4><strong>O.S N&deg; {$os[0].ID}</strong></h4>
            </div>

            <div class=" col-md-12 col-sm-12 col-xs-12 row invoice-info" style="margin: 0 auto; text-align: center; margin-top: 20px">
                  <p><h4><strong>TERMO DE ENTREGA DE VEÍCULO/EQUIPAMENTO</h4></p></strong>
            </div>

            <!-- page content -->
                   <div class="right_col container-tabela" role="main">
                        <div class="row small ">
                              <div class="col-xs-12 table">
                                    
                                    <table class="table table-bordered container-tabela" id="tab1">
                                          <thead id="os_head_table">
                                                <tr>
                                                      <th colspan='5'><h5><strong>IDENTIFICAÇÃO DO RESPONSÁVEL PELO RECEBIMENTO</h5></th>
                                                </tr>
                                          </thead>
                                          <tbody>                                                            
                                                <tr class="body_os_table">
                                                      <td>Nome do responsável</td>
                                                      <td>Cargo/Função</td>
                                                      <td style="width: 100px">RG</td>
                                                      <td style="width: 180px">CPF</td>
                                                      <td style="width: 140px">Data do recebimento</td>

                                                </tr>          
                                          </tbody>
                                          <tfoot>
                                                <tr>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                </tr>
                                          </tfoot>
                                    </table>
                                                            
                              </div>
                        </div>
                  </div>

                  <div class="right_col container-tabela" role="main">
                        <div class="row small ">
                              <div class="col-xs-12 table">
                                    
                                    <table class="table table-bordered container-tabela" id="tab2">
                                          <thead id="os_head_table">
                                                <tr>
                                                      <th colspan='5'><h5><strong>IDENTIFICAÇÃO DO RESPONSÁVEL PELA ENTREGA</h5></th>
                                                </tr>
                                          </thead>
                                          <tbody>                                                            
                                                <tr class="body_os_table">
                                                      <td style="width: 65%">Nome</td>
                                                      <td>Cargo/Função</td>
                                                </tr>          
                                          </tbody>
                                          <tfoot>
                                                <tr>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                </tr>
                                          </tfoot>
                                    </table>
                                                            
                              </div>
                        </div>
                  </div>

                  <div class="right_col container-tabela" role="main">
                        <div class="row small ">
                              <div class="col-xs-12 table">
                                    
                                    <table class="table table-bordered container-tabela" id="tab3">
                                          <thead id="os_head_table">
                                                <tr>
                                                      <th colspan='5'><h5><strong>VEÍCULO/EQUIPAMENTO ENTREGUE</h5></th>
                                                </tr>
                                          </thead>
                                          
                                          <tbody>
                                                 <tr class="">
                                                      <td style="width: 50%">Descrição</td>
                                                      <td style="width: 12.5%">Placa</td>
                                                      <td style="width: 12.5%">Frota</td>
                                                      <td style="width: 10%">Km/Horímetro</td>
                                                      <td>Proprietário</td>
                                                </tr> 
                                                           
                                          </tbody>

                                          <tfoot>
                                                {section name=i loop=$os}                                                            
                                                <tr class="body_os_table">
                                                      <td> {$os[i].DESCEQUIPAMENTO} </td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                      <td>&nbsp;</td>
                                                </tr>
                                                {/section}
                                          </tfoot>

                                    </table>
                                                            
                              </div>
                        </div>
                  </div>
                        
                             <div class="col-md-12 col-sm-12 col-xs-12">
                                         <p>Obs:___________________________________________________________________________________________________________________________________
                                          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________________________________________________________________________________________________________________
                                          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________________________________________________________________________________________________________________
                              </div>

                              <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="formulariocheck">

                              <div class="row invoice-info">
                                    <div class="col-md-11 col-sm-11 col-xs-11 form-group line">
                                          NESTE ATO RECEBO O VEÍCULO/EQUIPAMENTO DESCRITO ACIMA, DEVIDAMENTE AUTORIZADO PELO SEU PROPRIETÁRIO 
                                          E CIENTE DA RELAÇÃO DOS SERVIÇOS EXECUTADOS E DAS PEÇAS SUBSTITUÍDAS.  
                                    </div>
                              </div>

                              <br>
                              <div class="row invoice-info">
                                    <div class="col-md-11 col-sm-11 col-xs-11 form-group line">
                                          QUANTO AOS SERVIÇOS EXECUTADOS:
                                          <p><input type="checkbox"/>&nbsp;FORAM VISTORIADOS E ACEITOS.______________(rubrica)</p>
                                    </div>
                              </div>

                              <br>
                              <div class="row invoice-info">
                                    <div class="col-md-11 col-sm-11 col-xs-11 form-group line">
                                          QUANTO ÁS PEÇAS SUBSTITUÍDAS:
                                          <p><input type="checkbox"/>&nbsp;RECEBO NESTE MOMENTO TODAS AS PEÇAS SUBSTITUÍDAS, RESPONSABILIZANDO-ME PELA SUA CORRETA DESTINAÇÃO FINAL.______________(rubrica)</p>
                                          <p><input type="checkbox"/>&nbsp;DECLARO QUE O PROPRIETÁRIO DO VEÍCULO/EQUIPAMENTO NÃO TEM INTERESSE EM PERMANECER COM AS PEÇAS SUBSTITUÍDAS,
                                                                      DE MODO QUE AUTORIZO A EMPRESA BIANCO A DAS CORRETA DESTINAÇÃO FINAL PARA ELAS, DE ACORDO COM A NORMA AMBIENTAL
                                                                      VIGENTE.______________(rubrica)
                                    </div>
                              </div>

                              </div>

                              <div class="row invoice-info">
                                    <div class="col-md-8 col-sm-8 col-xs-8 form-group line">
                                          <p style="margin-left: 100px; margin-top: 160px">_________/_________/___________</p>
                                          <p style="margin-left: 180px">Data</p>
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-4 form-group line">
                                          <p style="margin-left: -50px; margin-top: 160px;">_______________________________________</p>
                                          <p style="margin-left: 60px">Assinatura</p>
                                    </div>
                              </div>

                  </div>
            </div>
      
      <!-- /.row -->


            <!--div class="row no-print">
                  <div class="col-xs-12">
                        <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                  </div>
            </div -->
      </div>
            
      <!-- /page content -->
</div>

<style>
    #tab1 {
      margin: 0 auto; 
      margin-top: 30px
    }

    td{
      margin: 0 auto; 
      text-align: center;
      font-weight: bold;
    }

    th{
      margin: 0 auto; 
      text-align: center;
      font-weight: bold;
    }

    #formulariocheck{
      margin: 0 auto;
      margin-top: 30px
    }
  @media print {
      h6{
         font-size: 8px;
      }
      .line{ 
         margin-bottom: 3px;
      }
      .container-tabela{
         margin-bottom: 0px;
      }
/* avoid cutting tr's in half */
      th div, td div {
            margin-top:-8px;
            padding-top:8px;
            page-break-inside:avoid;
       }      
  }
  

</style>