<section class="height100">
<!-- page content -->
{if $cssBootstrap eq true}
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
{/if}
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <img {if $cssBootstrap == true}src="{$urlImg}"{else}src="images/logo.png"{/if}  aloign="right" width=180 height=75 border="0"></A>
            </div>   
            <br>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  <div>
                        <h4>
                              <center><strong>{$empresa[0].NOMEEMPRESA}</strong></center>
                        </h4>
                  </div>
            
                  <div>
                        <center><h6>
                              {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                              <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                        </h6></center>
                  </div>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-3 form-group line">
                  <h4>
                        <strong>OS N&#176;: {$os[0].ID}</strong></h4>
                        Vendedor: <strong>{$os[0].USERABERTURA}</strong>
                  </div>
            </div>
      
            <div class="row invoice-info">
                  <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                        Data:<strong>{$os[0].DATAABERATEND|date_format:"%d/%m/%Y"} </strong>
                  </div>
                  
                  <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  
                  </div>
            </div>
            
            <div class="row invoice-info">
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group line">
                        Cliente: <strong>{$os[0].NOME} - 
                        {if $os[0].PESSOA == 'J'}
                              CNPJ:
                        {else}
                              CPF: 
                        {/if}
                        {$os[0].CNPJCPF}</strong>
                  </div>
            </div>

            <div class="row invoice-info">
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group line">
                        <b> Fone: {$os[0].FONE} Celular: {$os[0].CELULAR} </b>
                  </div>
            </div>

            <div class="row invoice-info">
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group line">
                        <b> Endereço : {$os[0].ENDERECO}, {$os[0].NUMERO}, 
                              {$os[0].COMPLEMENTO} {$os[0].BAIRRO}                          
                              {$os[0].CIDADE}, {$os[0].UF} {$os[0].CEP}</b>
                  </div>
            </div>
      
            <div class="row invoice-info">
                  <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                        Condição Pagamento: 
                        <strong>
                              {$os[0].DESCCONDPGTO}
                        </strong>
                  </div>
                  
                  <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                        Prazo de Entrega: 
                        <strong>
                              {if $os[0].PRAZOENTREGA neq ''}
                              {$os[0].PRAZOENTREGA|date_format:"%d/%m/%Y"}
                              {else}
                                    A COMBINAR
                              {/if}
                        </strong>
                  </div>
            </div>
      

            <!-- page content -->
                  <div class="right_col container-tabela" role="main">
                                                <div class="row small ">
                                                      <div class="col-xs-12 table">
                                                      {if $os neq 'null'}
                                                            {if $os[0].DESCEQUIPAMENTO neq ''}
                                                                  <strong>
                                                                        <th> 
                                                                              <h5>
                                                                                    EQUIPAMENTO
                                                                              </h5>
                                                                        </th>
                                                                  </strong>
                                                            {/if}
                                                            {if $os[0].DESCEQUIPAMENTO neq ''}
                                                            {else}
                                                                  <br>
                                                            {/if}
                                                            <table class="table table-striped container-tabela" id="tab1">
                                                                  <thead id="os_head_table">
                                                                        <tr>  
                                                                              <th></th>
                                                                              <!-- <th><h5>EQUIPAMENTO</h5></th> -->
                                                                              {if $print neq "mecanico"}
                                                                                    <th>TOTAL PECAS</th>
                                                                                    <th>TOTAL SERVIÇO</th>
                                                                                    <th>VALOR VISITA</th>
                                                                                    <th>VALOR DESCONTO</th>
                                                                                    <th>TOTAL OS</th>
                                                                              {else}
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                              {/if}
                                                                        </tr>
                                                                  </thead>
                                                                  <tfoot>
                                                                        <tr>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                        </tr>
                                                                  </tfoot>
                                                                  
                                                                  <tbody>
                                                                        {section name=i loop=$os}                                                            
                                                                        <tr class="body_os_table">
                                                                              <td> {$os[i].DESCEQUIPAMENTO} </td>
                                                                              {if $print neq "mecanico"}
                                                                                    <td> {$os[i].VALORPECAS|number_format:2:",":"."} </td>
                                                                                    <td> {$os[i].VALORSERVICOS|number_format:2:",":"."} </td>
                                                                                    <td> {$os[i].VALORVISITA|number_format:2:",":"."} </td>
                                                                                    <td> {$os[i].VALORDESCONTO|number_format:2:",":"."} </td>
                                                                                    <td> {$os[i].VALORTOTAL|number_format:2:",":"."} </td>
                                                                              {else}
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                              {/if}
                                                                        </tr>
                                                                        {/section} 
                                                           
                                                                  </tbody>
                                                            </table>
                                                            {/if}
                                                            {if $osItem neq null}
                                                            <br>
                                                                  <strong>
                                                                        <th> 
                                                                              <h5>
                                                                                    PEÇAS UTILIZADAS
                                                                              </h5>
                                                                        </th>
                                                                  </strong>
                                                            
                                                            <table class="table table-striped container-tabela" id="tab1">
                                                                  <div>
                                                                        <tr>
                                                                              {if $print neq "mecanico"}
                                                                                    <th></th>
                                                                                    <th>CÓD NOTA</th>
                                                                                    <th>DESCRIÇÃO</th>
                                                                                    <th style='text-align:right'>QUANTIDADE</th>
                                                                                    <th>UNIDADE</th>
                                                                                    <th style='text-align:right'>VALOR UNITÁRIO</th>
                                                                                    <th style='text-align:right'>SUB TOTAL</th>
                                                                                    <th style='text-align:right'>% DESCONTO</th>
                                                                                    <th style='text-align:right'>VALOR DESCONTO</th>
                                                                                    <th style='text-align:right'>VALOR PEÇA</th>
                                                                                    <th></th>
                                                                                    
                                                                              {else}
                                                                                    <th></th>
                                                                                    <th>CÓD FAB</th>
                                                                                    <th>CÓD NOTA</th>
                                                                                    <th>DESCRIÇÃO</th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th><center> QUANTIDADE </center> </th>
                                                                                    <th><center> UNIDADE </center> </th>
                                                                                    <th>LOC.</th>                                                                                                                                                            
                                                                                    <th><center> ESTOQUE </center></th>
                                                                                    <th></th>
                                                                              {/if}
                                                                        </tr>
                                                                  </div>
                                                                  
                                                                  <tbody>
                                                                        {section name=i loop=$osItem}
                                                                        {assign var="totalpec" value= $totalpec + ((($osItem[i].VALORUNITARIO*$osItem[i].QUANTIDADE) - $osItem[i].DESCONTO))}
                                                                        <tr>
                                                                              {if $print neq "mecanico" }
                                                                                    <td> {$osItem[i].NRITEM} </td>
                                                                                    <td> {$osItem[i].CODPRODUTONOTA} </td>
                                                                                    <td> {$osItem[i].DESCRICAO} </td>
                                                                                    <td style='text-align:right'> {$osItem[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                                                    <td> {$osItem[i].UNIDADE} </td>
                                                                                    <td style='text-align:right'> {$osItem[i].VALORUNITARIO|number_format:2:",":"."} </td>
                                                                                    <td style='text-align:right'> {($osItem[i].VALORUNITARIO*$osItem[i].QUANTIDADE)|number_format:2:",":"."} </td>
                                                                                    <td style='text-align:right'> {$osItem[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                                                    <td style='text-align:right'> {$osItem[i].DESCONTO|number_format:2:",":"."} </td>
                                                                                    <td style='text-align:right'> {(($osItem[i].VALORUNITARIO*$osItem[i].QUANTIDADE) - $osItem[i].DESCONTO)|number_format:2:",":"."} </td>
                                                                                    <td></td>
                                                                                    
                                                                              {else}
                                                                                    <td> {$osItem[i].NRITEM} </td>
                                                                                    <td> {$osItem[i].CODFABRICANTE} </td>
                                                                                    <td> {$osItem[i].CODPRODUTONOTA} </td>
                                                                                    <td> {$osItem[i].DESCRICAO} </td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><center> {$osItem[i].QUANTIDADE|number_format:2:",":"."} </center></td>
                                                                                    <td><center> {$osItem[i].UNIDADE} </center></td>
                                                                                    <td> {$osItem[i].LOCALIZACAO} </td>                                                                                     
                                                                                    <td><center> {$osItem[i].QUANTIDADE_EST} </center></td>
                                                                                    <td></td>
                                                                                    
                                                                              {/if}
                                                                        </tr>
                                                            
                                                                        {/section}
                                                            
                                                                        <tr>
                                                                              {if $print neq "mecanico"}
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    
                                                                                    <td><h5 class="totais"><b>Total :</b></h5></td>
                                                                                    <td><h4 class="totais"> {$totalpec|number_format:2:",":"."} </h4></td>
                                                                              {else}
                                                                                    </p>
                                                                              {/if}
                                                                        </tr> 
                                                           
                                                                  </tbody>
                                                            </table>
                                                            {/if}
                                                            {if $osServico neq null}
                                                                  <br>
                                                                        <strong>
                                                                              <th>
                                                                                    <h5>
                                                                                          SERVIÇOS
                                                                                    </h5>
                                                                              </th>
                                                                        </strong>

                                                            <table class="table table-striped container-tabela" id="tab1">
                                                                  <div>
                                                                        <tr>
                                                                              <th></th>
                                                                              <th>CÓD SERVIÇO</th>
                                                                              <th>DESCRIÇÃO</th>
                                                                              <th>QUANTIDADE</th>
                                                                              <th>UNIDADE</th>
                                                                              {if $print neq "mecanico"}
                                                                                    <th>VALOR UNITARIO</th>
                                                                                    <th>TOTAL SERVIÇO</th>
                                                                                    <th></th>
                                                                              {else}
                                                                                    <th></th>
                                                                                    <th></th>
                                                                              {/if}
                                                                        </tr>
                                                                  </div>
                                                            
                                                                  <tbody>
                                                                        {section name=i loop=$osServico}                                                            
                                                                        {assign var="totalserv" value= $totalserv + $osServico[i].TOTALSERVICO}
                                                                        <tr>  
                                                                              <td> {$osServico[i].NRITEM_S} </td>
                                                                              <td> {$osServico[i].ID} </td>
                                                                              <td> {$osServico[i].DESCSERVICO} </td>
                                                                              <td> {$osServico[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                                              <td> {$osServico[i].UNIDADE} </td>
                                                                              {if $print neq "mecanico"}
                                                                                    <td> {$osServico[i].VALUNITARIO|number_format:2:",":"."} </td>
                                                                                    <td> {$osServico[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                                                                    <td></td>
                                                                              {else}
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                              {/if}
                                                                        </tr>
                                                            
                                                                        {/section}
                                                                        <tr>
                                                                              {if $print neq "mecanico"}
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td style='text-align:right'><h5 class="totais"><b>Total:</b></h5></td>
                                                                                    <td>
                                                                                          <h4 class="totais"> 
                                                                                                {$totalserv|number_format:2:",":"."} 
                                                                                          </h4>
                                                                                    </td>
                                                                              {else}
                                                                                    </p>
                                                                              {/if}
                                                                        </tr>  
                                                                  </tbody>
                                                            </table>
                                                            {/if}
                                                      </div>
                        </div>
                        
                        <div class="row invoice-info">
                              <div class="col-sm-4 invoice-col obs">
                                    {if $os[0].OBS neq ''}
                              <strong id="obs">Observa&ccedil;&otilde;es Produto: </strong><br><br> {$os[0].OBS}.
                              </br>
                              </br>
                        {else}     
                        {/if}

                        {if $os[0].OBSSERVICO neq ''}
                              <strong id="obs">Observa&ccedil;&otilde;es Serviço: </strong><br><br> {$os[0].OBSSERVICO}.
                              </br>
                              </br>
                        {else}
                        {/if}
                              </div>
                        </div>
                  </div>
            </div>
      </div>
            
      <!-- /page content -->
</div>
</section>

<style>
      #os_head_table{
            font-size:12px;
      }
      .body_os_table{
            font-size:12px;   
      }

      .line{ 
            font-size: 10px;
            margin-bottom:1px;
      }
      .height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
      }
      .obs{
            margin-left: 5px;
      }
  @media print {

      a[href]:after {
      content: none !important;
      }

      @page{
      margin-top: 0;
      margin-bottom: 0;
      display: none;
      margin: 0px auto;
      size:  auto;
      }
      .no-print{
            display: none;
            }
      h2{
         font-size: 10px;   
         margin-bottom:3px;
      }
      h6{
         font-size: 10px;
      }
      .container-tabela{
         margin-bottom: 0px;
      }
      tr{
      font-size: 10px;
      
      }
      td{
      font-size: 10px;
      line-height: 11px !important;
      }
      th{
      font-size: 10px;
      line-height: 11px !important;
      }
      .obs{
            font-size: 10px;
      }
      .totais{
            font-size: 12px; 
      }
/* avoid cutting tr's in half */
      th div, td div {
            margin-top:-8px;
            padding-top:8px;
            page-break-inside:avoid;
       }      
  }
  

</style>