<!-- page content -->
{if $cssBootstrap eq true}
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
{/if}
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                  <img {if $cssBootstrap == true}src="{$urlImg}"{else}src="images/logo.png"{/if}  aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                <div>
                  <h4>
                      <strong>{$empresa[0].NOMEEMPRESA}</strong>
                  </h4>
                </div>
                <div>
                  <h6>
                      {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                      <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                  </h6>
                </div>
            </div>  
            <div class="col-md-3 col-sm-3 col-xs-3 form-group line">
                  <h4><strong>O.S Num: {$os[0].ID}</strong></h4>
                  Vendedor: {$os[0].USERABERTURA}
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
                  Condição Pagamento: <strong>{$os[0].DESCCONDPGTO}</strong>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  Prazo de Entrega: <strong>
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
          <div class="clearfix"></div>
                <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small ">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped container-tabela" id="tab1">
                                                      <thead id="os_head_table">
                                                        <tr>
                                                              <th>EQUIPAMENTO</th>
                                                              <th>TOTAL SERVIÇO</th>
                                                              <th>TOTAL PECAS</th>
                                                              <th>VALOR VISITA</th>
                                                              <th>VALOR DESCONTO</th>
                                                              <th>TOTAL OS</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$os}                                                            
                                                            <tr class="body_os_table">
                                                                  <td> {$os[i].DESCEQUIPAMENTO} </td>
                                                                  <td> {$os[i].VALORSERVICOS|number_format:2:",":"."} </td>
                                                                  <td> {$os[i].VALORPECAS|number_format:2:",":"."} </td>
                                                                  <td> {$os[i].VALORVISITA|number_format:2:",":"."} </td>
                                                                  <td> {$os[i].VALORDESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {$os[i].VALORTOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                            
                                                            {/section} 
                                                           
                                                      </tbody>
                                                </table>
                                                <br>
                                                <strong><th><h4>PEÇAS UTILIZADAS</h4></th></strong>
                                                 <table class="table table-striped container-tabela" id="tab1">
                                                      <thead>
                                                        <tr>
                                                              <th>CÓD PECA</th>
                                                              <th>DESCRIÇÃO</th>
                                                              <th>UNIDADE</th>
                                                              <th>QUANTIDADE</th>
                                                              <th>% DESCONTO</th>
                                                              <th>VALOR DESCONTO</th>
                                                              <th>TOTAL PEÇA</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$osItem}                                                            
                                                            <tr>
                                                                  <td> {$osItem[i].CODPRODUTO} </td>
                                                                  <td> {$osItem[i].DESCRICAO} </td>
                                                                  <td> {$osItem[i].UNIDADE} </td>
                                                                  <td> {$osItem[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                                  <td> {$osItem[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {$osItem[i].DESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {$osItem[i].VALORTOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                            
                                                            {/section} 
                                                           
                                                      </tbody>
                                                </table>

                                                <br>
                                                <strong><th><h4>SERVIÇOS</h4></th></strong>
                                                <table class="table table-striped container-tabela" id="tab1">
                                                      <thead>
                                                        <tr>
                                                              <th>CÓD SERVIÇO</th>
                                                              <th>DESCRIÇÃO</th>
                                                              <th>UNIDADE</th>
                                                              <th>QUANTIDADE</th>
                                                              <th>VALOR UNITARIO</th>
                                                              <th>TOTAL SERVIÇO</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$osServico}                                                            
                                                            <tr>
                                                                  <td> {$osServico[i].ID} </td>
                                                                  <td> {$osServico[i].DESCSERVICO} </td>
                                                                  <td> {$osServico[i].UNIDADE} </td>
                                                                  <td> {$osServico[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                                  <td> {$osServico[i].VALUNITARIO|number_format:2:",":"."} </td>
                                                                  <td> {$osServico[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                                            </tr>
                                                            
                                                            {/section} 
                                                           
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </section>
                        </div>
                </div>
          </div>
          <div class="row invoice-info">
           <div class="col-sm-4 invoice-col">
               <strong>Observa&ccedil;&otilde;es: </strong><br><br>
               {$os[0].OBS}
               <strong>____________________________________________</strong><br><br>
               <strong>____________________________________________</strong><br><br>
               <strong>Observa&ccedil;&otilde;es Serviço: </strong><br><br>
               {$os[0].OBSSERVICO}
               <strong>____________________________________________</strong><br><br>
               <strong>____________________________________________</strong>
           </div>
         </div>
         <!--div class="row invoice-info">
            <br><br>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line" align="center">
                  <strong>Observa&ccedil;&otilde;es: </strong><br><br>
                  {$os[0].OBS}
                  <br><br>
                  <br><br>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line" align="center">
                  <strong>Observa&ccedil;&otilde;es Serviço: </strong><br><br>
                  {$os[0].OBSSERVICO}
                  <br><br>
                  
           </div>
            </div-->
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

<style>
      #os_head_table{
            font-size:14px;
            font-weight:bold;
      }
      .body_os_table{
            font-size:14px;
            font-weight:bold;
      }
  @media print {
      #os_head_table{
            font-size:10px;
            font-weight:bold;
      }
      h2{
         font-size: 8px;   
         margin-bottom:3px;
      }
      h6{
         font-size: 7px;
      }
      .line{
         font-size: 7px; 
         margin-bottom: 3px;
      }
      .container-tabela{
         margin-bottom: 0px;
      }
      tr{
      font-size: 7px;
      }
      td{
      font-size: 6px;
      }
  }
  

</style>