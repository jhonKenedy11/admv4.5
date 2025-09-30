<!--dev verifique a pasta templates do cliente-->
<style>
      .headtab {
            font-size: 9px !important;
      }

      .x_panel {
            padding: 0 !important;
      }

      img {
            border-radius: 7px;
      }

      @media print {
            h2 {
                  font-size: 12px;
                  margin-bottom: 0px;
            }

            h6 {
                  font-size: 11px;
            }

            .line {
                  font-size: 10px;
                  margin-bottom: 1px;
            }

            .container-tabela {
                  margin-bottom: 0px;
            }

            tr {
                  font-size: 10px;
            }

            td {
                  font-size: 9px;
            }
            .row{
                  font-size: 11px;
            }

            .no-print {
                  display: none !important;
            }
            .x_panel, .x_content, .col-xs-12, .col-md-12, .col-sm-12, .col-xs-6, .col-md-6, .col-sm-6, .col-xs-4, .col-md-4, .col-sm-4 {
                margin: 0 !important;
                padding: 0 !important;
            }
            .table, .table-striped, .table > tbody > tr > td, .table > thead > tr > th {
                margin: 0 !important;
                padding: 1px !important;
                padding-left: 1px !important;
                padding-right: 1px !important;
                border-collapse: collapse !important;
            }
            .right_col, .row, .form-group {
                margin: 0 !important;
                padding: 0 !important;
            }
      }
</style>

<!-- page content -->
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img src="images/logo.jpg" aloign="right" width=180 height=45 border="0"></A>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  <div>
                        <h2>
                              <strong>{$empresa[0].NOMEEMPRESA}</strong>
                        </h2>
                  </div>
                  <div>
                        <h6>
                              {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO},
                              {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE},
                              {$empresa[0].UF} {$empresa[0].CEP}
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
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  Cliente: <strong>{$pedido[0].NOME}</strong>
                  <div style="display:inline-block; width:20px;"></div>
                  {if $pedido[0].PESSOA == 'J'}
                        CNPJ:
                  {else}
                        CPF:
                  {/if}
                  <div style="display:inline-block;">
                        <strong>{$pedido[0].CNPJCPF}</strong>
                  <div style="display:inline-block; width:20px;"></div>

                        
                  </div>
                  <div style="display:inline-block; width:20px;"></div>
                  {if !empty($pedido[0].INSCESTRG)}
                        <div style="display:inline-block;">
                              {if $pedido[0].PESSOA == 'J'}
                                    Inscrição Estadual:
                              {else}
                                    RG:
                              {/if}
                              <strong>{$pedido[0].INSCESTRG}</strong>
                              
                        </div>
                  {/if}
            </div>
      </div>
      <div class="row invoice-info">
            {if !empty($pedido[0].EMAIL)}
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        Email: <strong>{$pedido[0].EMAIL}</strong>
                  </div>
            {/if}
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                  {if empty($pedido[0].FONE) && empty($pedido[0].CELULAR)}                        
                  {else}
                        <div style="display:inline-block;">
                              Fone: <strong>{$pedido[0].FONE}</strong>
                        </div>
                        <div style="display:inline-block; width:20px;"></div>
                        <div style="display:inline-block;">
                              Celular: <strong>{$pedido[0].CELULAR}</strong>
                        </div>
                        <div style="display:inline-block; width:20px;"></div>
                        {if !empty($pedido[0].CONTATO) || !empty($pedido[0].FONECONTATO)}
                              <div style="display:inline-block;">
                                    Contato: <strong>
                                          {if !empty($pedido[0].CONTATO)}
                                                {$pedido[0].CONTATO}
                                          {elseif !empty($pedido[0].FONECONTATO)}
                                                {$pedido[0].FONECONTATO}
                                          {/if}
                                    </strong>
                              </div>
                        {/if}
                  {/if}
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            {if $enderecoEntrega neq ''}
                  Endereço: <strong>{$enderecoEntrega[0].ENDERECO_ENTREGA}</strong>
            {else}
                  Endereço: <strong>{$pedido[0].ENDERECO}</strong>, <strong>{$pedido[0].NUMERO}</strong>,
                        <strong>{$pedido[0].COMPLEMENTO}</strong> <strong>{$pedido[0].BAIRRO}</strong>
                        <strong>{$pedido[0].CIDADE}</strong>, <strong>{$pedido[0].UF}</strong> <strong>{$pedido[0].CEP}</strong>
            {/if}
            </div>
      </div>
      <div class="row invoice-info">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  Condição Pagamento: <strong>{$descCondPgto}</strong>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">      
            {if $pedido[0].PROJETO neq ''}
                  CONTRATO: <strong>{$pedido[0].PROJETO}
            {/if}</strong>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  Previsão de Entrega: <strong>
                        {if $pedido[0].DATAENTREGA neq ''}
                              {$pedido[0].DATAENTREGA|strtotime|date_format:"%d/%m/%Y"}
                        {else if $pedido[0].PRAZOENTREGA neq ''}
                              {$pedido[0].PRAZOENTREGA|strtotime|date_format:"%d/%m/%Y"}
                        {else}
                              A COMBINAR
                        {/if}
                        </strong>
            </div>
      </div>

      <!-- page content -->
      <div class="right_col" role="main">
            <div class="clearfix">
                  </div-->
                  <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                {if $pedidoItem neq ''}
                                                      <h4>Produtos</h4>
                                                      <table class="table table-striped">
                                                            <thead>
                                                                  <tr>
                                                                        <th style="width:109px !important;">CÓD CADASTRO</th>

                                                                        {if $letra eq 'loja'}
                                                                              <th>CÓD FABRICANTE</th>
                                                                        {/if}

                                                                        <th>CÓD NOTA</th>
                                                                        <th>NCM</th>
                                                                        <th>DESC PEDIDO</th>
                                                                        <th>UNID</th>
                                                                        <th>QTD</th>
                                                                        <th>UNITÁRIO</th>
                                                                        <th>TOTAL PRODUTO</th>
                                                                        <th>VLR DESCONTO</th>
                                                                        <th>% DESC</th>
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

                                                                              {if $letra eq 'loja'}
                                                                                    <td> {$pedidoItem[i].ITEMFABRICANTE} </td>
                                                                              {/if}

                                                                              <td> {$pedidoItem[i].CODIGONOTA} </td>
                                                                              <td> {$pedidoItem[i].NCM} </td>
                                                                              <td> {$pedidoItem[i].DESCRICAO} </td>
                                                                              <td> {$pedidoItem[i].UNIDADE} </td>
                                                                              <td> {$pedidoItem[i].QTSOLICITADA|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoItem[i].UNITARIO|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoItem[i].TOTAL|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoItem[i].DESCONTO|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoItem[i].PERCDESCONTO|number_format:2:",":"."}
                                                                                    % </td>
                                                                              {* <td> {($pedidoItem[i].TOTAL-$pedidoItem[i].DESCONTO)|number_format:2:",":"."} </td> *}
                                                                        </tr>
                                                                  {/section}                                                                 
                                                            </tbody>
                                                      </table>
                                                {/if}
                                                {if $pedidoServicos neq ''}
                                                      <h4>Serviços</h4>
                                                      <table class="table table-striped">
                                                            <thead>
                                                                  <tr>
                                                                        <th>CÓD SERVIÇO</th>
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
                                                                              <td>
                                                                                    {$pedidoServicos[i].DESCSERVICO}
                                                                                    {if $pedidoServicos[i].OBSSERVICO}
                                                                                    <br><small>{$pedidoServicos[i].OBSSERVICO}</small>
                                                                                    {/if}
                                                                              </td>
                                                                              <td> {$pedidoServicos[i].UNIDADE} </td>
                                                                              <td> {$pedidoServicos[i].QUANTIDADE|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoServicos[i].VALUNITARIO|number_format:2:",":"."}
                                                                              </td>
                                                                              <td> {$pedidoServicos[i].TOTALSERVICO|number_format:2:",":"."}
                                                                              </td>
                                                                        </tr>
                                                                  {/section}
                                                            </tbody>
                                                      </table>
                                                {/if}
                                                {if $pedidoItem eq '' && $pedidoServicos neq ''}
                                                      <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                                                            Nenhum produto cadastrado.
                                                      </div>
                                                {/if}
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
                                                                              <td> {$parcelas[p].VENCIMENTO|date_format:"%d/%m/%Y"}
                                                                              </td>
                                                                              <td> {$parcelas[p].VALOR|number_format:2:",":"."}
                                                                              </td>
                                                                        </tr>
                                                                        <p>
                                                                        {/section}
                                                            </tbody>
                                                      </table>
                                                {/if}

                                                <table class="table table-striped">
                                                      <tbody>
                                                            <tr>
                                                                  <td></td>
                                                                  <td></td>
                                                                  <td>Total Produto </td>
                                                                  <td> Total Serviços </td>
                                                                  <td>Valor Desconto </td>
                                                                  <td>Valor Desp Acessórias</td>
                                                                  <td>Valor Frete </td>
                                                                  <td><strong>Total Pedido </strong></td>
                                                            </tr>
                                                            <tr>
                                                                  <td>Totais</td>
                                                                  <td></td>
                                                                  <td>{$pedido[0].TOTALPRODUTOS|number_format:2:",":"."}
                                                                  </td>
                                                                  <td>{$pedido[0].VALORSERVICOS|number_format:2:",":"."}
                                                                  </td>
                                                                  <td>{$pedido[0].DESCONTO|number_format:2:",":"."}</td>
                                                                  <td>{$pedido[0].DESPACESSORIAS|number_format:2:",":"."}
                                                                  </td>
                                                                  <td>{$pedido[0].FRETE|number_format:2:",":"."}</td>
                                                                  <td><strong>{$pedido[0].TOTAL|number_format:2:",":"."}</strong>
                                                                  </td>
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
      {if $m_empresa neq 'REQUEMAQ'}
            <div class="row invoice-info">
                  <div align="center" class="col-md-12 col-sm-12 col-xs-12 form-group">
                        Confira seus produtos no ato da entrega. O material será descarregado aonde for possível estacionar o
                        veículo.(3% de perca é considerado normal - conf NBR 13.858/1e2) não aceitamos devolução.
                  </div>
            </div>
      {/if}
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
                  <button class="btn btn-default no-print" onclick="window.print();"><i
                              class="fa fa-print"></i></button>
            </div>
      </div>

</div>