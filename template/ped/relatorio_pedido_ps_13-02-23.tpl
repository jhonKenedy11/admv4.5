<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.jpg" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                      <strong>{$empresa[0].NOMEEMPRESA}</strong>
                  </h2>
                </div>
                <div>
                  <h6>
                      {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, {$empresa[0].UF} {$empresa[0].CEP}
                      <br>Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} Email: {$empresa[0].EMAIL}                                              
                  </h6>
                </div>
            </div>  
            <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2><strong>{if $pedido[0].SITUACAO eq 5}Cotação: {else}Pedido: {/if} {$pedido[0].ID}</strong></h2>
                  Vendedor: {$pedido[0].USRFATURA_}
            </div>         
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  Data:<strong>{$pedido[0].EMISSAO|date_format:"%d/%m/%Y"} {$pedido[0].HORAEMISSAO}</strong>
            </div>
            <!--div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  Vendedor: {$pedido[0].USRFATURA_}
            </div-->
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
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
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <b> Contato: {$pedido[0].FONECONTATO}  </b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <b> Fone: {$pedido[0].FONE} Celular: {$pedido[0].CELULAR} </b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  <b> Endereço : {$pedido[0].ENDERECO}, {$pedido[0].NUMERO}, 
                                 {$pedido[0].COMPLEMENTO} {$pedido[0].BAIRRO}                          
                                 {$pedido[0].CIDADE}, {$pedido[0].UF} {$pedido[0].CEP}</b>
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  Condição Pagamento: <strong>{$descCondPgto}</strong>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                 -> Previsão de Entrega: <strong>
                 {if $pedido[0].DATAENTREGA neq ''}
                        {$pedido[0].DATAENTREGA|date_format:"%d/%m/%Y"}
                  {else if $pedido[0].PRAZOENTREGA neq ''}
                        {$pedido[0].PRAZOENTREGA}
                  {else}
                        A COMBINAR
                  {/if}
                  <- </strong>
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
                                                <h4>Produtos</h4>
                                                <table class="table table-striped">
                                                      <thead>
                                                        <tr>  
                                                              <th>CÓDIGO CADASTRO</th>
                                                              <th>CÓDIGO FABRICANTE</th>
                                                              <th>CÓDIGO NOTA</th>
                                                              <th>NCM</th>
                                                              <th>DESCRIÇÃO PEDIDO</th>
                                                              <th>UNID</th>                                                              
                                                              <th>QTD</th>   
                                                              <th>UNITÁRIO</th>
                                                              <th>TOTAL PRODUTO</th>
                                                              <th>VALOR DESCONTO</th>
                                                              <th>% DESCONTO</th>                                                              
                                                              <th>TOTAL ITEM</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedidoItem}
                                                            {assign var="total" value=$pedidoItem[i].QUANTIDADE*$pedidoItem[i].UNITARIO}
                                                            {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}
                                                             {assign var="pesoItem" value=0}
                                                            {assign var="pesoItem" value=$pedidoItem[i].QTSOLICITADA|number_format:0:",":"." * $pedidoItem[i].PESO}
                                                            {assign var="peso" value=$peso+$pesoItem}
                                                            <tr>
                                                                  <td> {$pedidoItem[i].ITEMESTOQUE} </td>
                                                                  <td> {$pedidoItem[i].ITEMFABRICANTE} </td>
                                                                  <td> {$pedidoItem[i].CODIGONOTA} </td>
                                                                  <td> {$pedidoItem[i].NCM} </td>
                                                                  <td> {$pedidoItem[i].DESCRICAO} </td>
                                                                  <td> {$pedidoItem[i].UNIDADE} </td>
                                                                  <td> {$pedidoItem[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].UNITARIO|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].TOTAL|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].DESCONTO|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoItem[i].PERCDESCONTO|number_format:2:",":"."} % </td>
                                                                  <td> {($pedidoItem[i].TOTAL-$pedidoItem[i].DESCONTO)|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section} 
                                                           <!-- <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>PRODUTOS :</h4></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>FRETE :</h6></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h6>DESCONTO :</h6></td>
                                                                <td><h6> {$pedido[0].TOTAL|number_format:2:",":"."} </h6></td>
                                                            </tr>  
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><h4> {($pedido[0].TOTAL + $pedido[0].DESCONTO - $pedido[0].DESPACESSORIAS - $pedido[0].FRETE)|number_format:2:",":"."} </h4></td>
                                                            </tr>  -->
                                                      </tbody>
                                                </table>
                                                <h4>Serviços</h4>
                                                 <table class="table table-striped">
                                                      <thead>
                                                        <tr>  
                                                              <th>CÓDIGO SERVIÇO</th>
                                                              <th>DESCRIÇÃO SERVIÇO</th>
                                                              <th>UNIDADE</th>                                                              
                                                              <th>QTD</th>   
                                                              <th>UNITÁRIO</th>
                                                              <th>TOTAL SERVIÇO</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                            {section name=i loop=$pedidoServicos}
                                                            <tr>
                                                                  <td> {$pedidoServicos[i].CAT_SERVICOS_ID} </td>
                                                                  <td> {$pedidoServicos[i].DESCSERVICO} </td>
                                                                  <td> {$pedidoServicos[i].UNIDADE} </td>
                                                                  <td> {$pedidoServicos[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoServicos[i].VALUNITARIO|number_format:2:",":"."} </td>
                                                                  <td> {$pedidoServicos[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                                            </tr>
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
                                                                <td>               </td>
                                                                <td>  </td>
                                                                <td>Total Produto  </td>
                                                                <td> Total Serviços  </td>
                                                                <td>Valor Desconto       </td>
                                                                <td>Valor Desp Acessórias</td>
                                                                <td>Valor Frete          </td>
                                                                <td><strong>Total Pedido </strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Totais</td>
                                                                <td></td>
                                                                <td>{$pedido[0].TOTALPRODUTOS|number_format:2:",":"."} </td>
                                                                <td>{$pedido[0].VALORSERVICOS|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].DESCONTO|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].DESPACESSORIAS|number_format:2:",":"."}</td>
                                                                <td>{$pedido[0].FRETE|number_format:2:",":"."}</td>
                                                                <td><strong>{$pedido[0].TOTAL|number_format:2:",":"."}</strong> </td>
                                                            </tr>

                                                            <tr>
                                                                <td></td>
                                                                <td> </td>
                                                                <td>Peso Bruto</td>
                                                                <td>{$peso|number_format:3:",":"."} </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr> 
                                                                <td> </td>
                                                                <td></td>
                                                                <td>Peso Liquido</td>
                                                                <td>{$peso|number_format:3:",":"."} </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
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
      <div class="row invoice-info">
            <div align="left" class="col-md-12 col-sm-12 col-xs-12 form-group">
            Observações: {$pedido[0].OBS}
            </div>
      </div>
      {if $pedido[0].REFERENCIA neq ''}
            <div class="row invoice-info">
            <div align="left" class="col-md-12 col-sm-12 col-xs-12 form-group">
            Ponto de Referencia: {$pedido[0].REFERENCIA}
            </div>
      </div>
      {/if}
      <div class="row invoice-info">
            <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                  Confira seus produtos no ato da entrega. O material será descarregado aonde for possível estacionar o veículo.(3% de perca é considerado normal - conf NBR 13.858/1e2) não aceitamos devolução.
            </div>
      </div>

      <div class="row invoice-info">
            <br><br>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center">
                  ---------------------------------------
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center">
                  ---------------------------------------
            </div>
      </div>

      <div class="row invoice-info">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center">
                  <tr>Assina</tr>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group" align="center">
                  Vendedor
            </div>
      </div>


      <div class="row no-print">
            <div class="col-xs-12 no-print">
              <button class="btn btn-default no-print" onclick="window.print();"><i class="fa fa-print"></i></button>
            </div>
      </div>

</div>
<!-- /page content -->

<style>
  <style>
  .headtab{
      font-size: 9px !important;
  }
  @media print {
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
      .no-print{
            display: none !important;
      }
  }
  

</style>

</style>
