<section class="height100">
<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=60 border="1"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2><center>
                      <b>{$empresa[0].NOMEEMPRESA}</b>
                  </center></h2>
                </div>
                <div>
                  <h6><center>
                      {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                      <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                            
                  </center></h6>
                </div>
            </div>  
            <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2><b>Pedido: {$pedido[0].PEDIDO}</b></h2>
                 
            </div>         
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <h7>Data:<b>{$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO}</b></h7>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" id="divVendedor">
                  <h7>Vendedor: <b>{$pedido[0].USRFATURA_}</b></h7>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <h7>Cliente: <b>{$pedido[0].NOME} - 
                  {if $pedido[0].PESSOA == 'J'}
                        CNPJ:
                  {else}
                        CPF: 
                  {/if}
                  {$pedido[0].CNPJCPF}</b></h7>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <h7>{if $pedido[0].FONE neq ''} Fone: <b>{$pedido[0].FONE}</b> {else} &nbsp; {/if}
                  {if $pedido[0].CELULAR neq ''} Celular: <b>{$pedido[0].CELULAR}</b> {else} &nbsp; {/if}</h7>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <h7>Endereço : <b>{$pedido[0].ENDERECO}, {$pedido[0].NUMERO}, 
                           {$pedido[0].COMPLEMENTO} {$pedido[0].BAIRRO}                          
                           {$pedido[0].CIDADE}, {$pedido[0].UF} {$pedido[0].CEP}</b></h7>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 form-group">
                  <h7>Condição Pagamento: <b>{$descCondPgto}</b></h7>
            </div>
            <div class="col-md-6 form-group" id="divEntrega">
                 <h7>-> Previsão de Entrega: <b>
                 {if $pedido[0].DATAENTREGA neq ''}
                        {$pedido[0].DATAENTREGA|date_format:"%d/%m/%Y"}
                  {else if $pedido[0].PRAZOENTREGA neq ''}
                        {$pedido[0].PRAZOENTREGA}
                  {else}
                        A COMBINAR
                  {/if}
                  </b><-</h7>
            </div>
      </div>
      

      <!-- page content -->
      <div class="right_col" role="main">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                <table class="table table-striped">
                                                      <div>
                                                            <tr>
                                                                  <th></th>
                                                                  <th>CÓDIGO</th>
                                                                  <th>DESCRIÇÃO</th>
                                                                  <th>QUANTIDADE</th>
                                                                  <th>UNIDADE</th>
                                                                  <th>VALOR UNITÁRIO</th>
                                                                  <th>% DESCONTO</th>
                                                                  <th>VALOR DESCONTO</th>
                                                                  <th>VALOR TOTAL ITEM</th>
                                                            </tr>
                                                      </div>
                                                      <tbody>
                                                            {section name=i loop=$pedidoItem}
                                                            {assign var="total" value=$pedidoItem[i].QUANTIDADE*$pedidoItem[i].UNITARIO}
                                                            {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}
                                                             {assign var="pesoItem" value=0}
                                                            {assign var="pesoItem" value=$pedidoItem[i].QTSOLICITADA|number_format:0:",":"." * $pedidoItem[i].PESO}
                                                            {assign var="peso" value=$peso+$pesoItem}
                                                            <tr>  
                                                                  <td> {$pedidoItem[i].NRITEM} </td>
                                                                  <td> {$pedidoItem[i].CODIGONOTA} </td>
                                                                  <td> {$pedidoItem[i].DESCRICAO} </td>
                                                                  <td> {$pedidoItem[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].UNIDADE} </td>
                                                                  <td> {$pedidoItem[i].UNITARIO|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].DESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {($pedidoItem[i].TOTAL - $pedidoItem[i].DESCONTO)|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section} 
                                                      </tbody>
                                                </table>
                                                {if $parcelas neq ''}
                                                <table class="table table-striped">
                                                       
                                                        <tr>
                                                              <th></th>
                                                              <th></th>
                                                              <th></th>
                                                              <th></th>
                                                              <th>PARCELA</th>
                                                              <th>DATA VENC</th>
                                                              <th>VALOR</th>
                                                        </tr>
                                                      
                                                      <tbody>
                                                            {section name=p loop=$parcelas}
                                                            <tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <th></th>
                                                                  <td> {$parcelas[p].PARCELA} / {$totalParc} </td>
                                                                  <td> {$parcelas[p].VENCIMENTO|date_format:"%d/%m/%Y"} </td>
                                                                  <td> {$parcelas[p].VALOR|number_format:2:",":"."} </td>
                                                                  
                                                                 
                                                            </tr>
                                                            <p>
                                                            {/section}
                                                      </tbody>
                                                      
                                                </table>
                                                {/if}

                                                <table class="table table-striped">
                                                      <tbody>
                                                           <tr>
                                                                <th>               </th>
                                                                <th>  </th>
                                                                <th>   </th>
                                                                <th>Produto        </th>
                                                                <th>Desconto       </th>
                                                                <th>Desp Acessórias</th>
                                                                <th>Frete          </th>
                                                                <th>TOTAL PEDIDO   </th>
                                                            </tr>
                                                            <tr>
                                                                <td><h4><b>Totais</b></h4></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h4>{($pedido[0].TOTAL + $pedido[0].DESCONTO - $pedido[0].DESPACESSORIAS - $pedido[0].FRETE)|number_format:2:",":"."} </h4></td>
                                                                <td><h4>{$pedido[0].DESCONTO|number_format:2:",":"."}</h4></td>
                                                                <td><h4>{$pedido[0].DESPACESSORIAS|number_format:2:",":"."}</h4></td>
                                                                <td><h4>{$pedido[0].FRETE|number_format:2:",":"."}</h4></td>
                                                                <td><h4><b>{$pedido[0].TOTAL|number_format:2:",":"."} </b></h4></td>
                                                            </tr>

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
      </div>
       <div class="x_panel">
            <div align="left" class="col-md-12 col-sm-12 col-xs-12 form-group">
            <b>Observações</b>
             </p>{$pedido[0].OBS}
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

#divVendedor{
      text-align: right;
}

@media print {
      @page{
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
            display: none;
            }
      h2{
            font-size: 12px;   
            margin-bottom:3px;
      }
      h6{
            font-size: 10px;
      }
      h7{
            font-size: 10px; 
      }
      tr{
            font-size: 7px;
      }
      td{
            font-size: 7px;
      }
      .no-print{
            display: none;
      }
  }
  

</style>