<section class="height100">
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
            <div class="col-md-2 col-sm-2 col-xs-2 form-group line">
                  <h4><strong>Ordem de Compra: {$pedido[0].ID}</strong></h4>
                  Vendedor: {$pedido[0].USRFATURA_}
            </div>         
      </div>
      <div class="row invoice-info" style="visibility:hidden">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  Data:<strong>{$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO}</strong>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  
            </div>
      </div>
      
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  Data:<strong>{$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO}</strong>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group line">
                  Cliente: <strong>{$pedido[0].NOME} - 
                  {if $pedido[0].PESSOA == 'J'}
                        CNPJ:
                  {else}
                        CPF: 
                  {/if}
                  {$pedido[0].CNPJCPF}</strong>
            </div>
      </div>
      
      <div class="row invoice-info">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group line">
                  DADOS COMPRA   -   Data Emissao: <strong>{$pedido[0].DATAEMISSAO|date_format:"%d/%m/%Y"}</strong>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4 form-group line">
                  Número NF: <strong>{$pedido[0].NUMERONF}</strong>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4 form-group line">
                  Série NF: <strong>{$pedido[0].SERIENF}</strong>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group line">
                  Condição Pagamento: <strong>{$descCondPgto}</strong>
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
                                                <table class="table table-striped">
                                                      <thead>
                                                            <tr>  
                                                                  <th></th>
                                                                  <th>CÓD. INTERNO</th>
                                                                  <th>CÓD. FABRICANTE</th>
                                                                  <th>LOC.</th>
                                                                  <th>CÓD. NOTA</th>
                                                                  <th>DESCRIÇÃO</th>
                                                                  <th align="RIGHT">QUANTIDADE</th>
                                                                  <th>UNID</th>
                                                                  <th align="RIGHT">VALOR UNITÁRIO</th>
                                                                  <th align="RIGHT">VALOR TOTAL</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedidoItem}
                                                            {assign var="total" value=$pedidoItem[i].QUANTIDADE*$pedidoItem[i].UNITARIO}
                                                            {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}
                                                            <tr>
                                                                  <td> {$pedidoItem[i].NRITEM} </td>
                                                                  <td> {$pedidoItem[i].ITEMESTOQUE} </td>
                                                                  <td> {$pedidoItem[i].ITEMFABRICANTE} </td>
                                                                  <td> {$pedidoItem[i].LOCALIZACAO} </td>
                                                                  <td> {$pedidoItem[i].CODIGONOTA} </td>
                                                                  <td> {$pedidoItem[i].DESCRICAO} </td>
                                                                  <td align="RIGHT"> {$pedidoItem[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].UNIDADE} </td>
                                                                  <td align="RIGHT"> {$pedidoItem[i].UNITARIO|number_format:2:",":"."} </td>
                                                                  <td align="RIGHT"> {$pedidoItem[i].TOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section} 

                                                      </tbody>
                                                </table>
                                                <table class="table table-striped">
                                                      <tbody>
                                                           <tr>
                                                                <td><b>TOTAL</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td align="RIGHT"><b>{$pedido[0].TOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </section>
                        </div>
                </div>
            </div>
            {if $pedido[0].OBS neq '' }
            <div class="row invoice-info">
                  <div align="left" class="col-md-12 col-sm-12 col-xs-12 form-group line">
                  Observações: {$pedido[0].OBS}
                  </div>
            </div>
            {/if}
      </div>
      
      
     

      <!--div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div -->

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
  @media print {
      h2{
         font-size: 8px;   
         margin-bottom:3px;
      }
      h6{
         font-size: 9px;
      }
      .line{
         font-size: 9px; 
         margin-bottom: 3px;
      }
      .container-tabela{
         margin-bottom: 0px;
      }
      tr{
      font-size: 9px;
      }
      td{
      font-size: 8px;
      }
  }
  

</style>