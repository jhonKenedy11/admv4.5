<!-- page content -->
{if $cssBootstrap eq true}
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
{/if}

<div class="right_col clearfix" role="main">

      <div class="contaider-fluid clearfix" id="infoscliente">
            
            <div class="row invoice-info">
                  <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                        <img {if $cssBootstrap == true}src="{$urlImg}"{else}src="images/logo.png"{/if}  aloign="right" width=140 height=80 border="0"></A>
                  </div>

                  <div class="col-md-7 col-sm-7 col-xs-7 form-group">
                        <h3>
                              <strong style="margin-left: 50px;">ORÇAMENTO DE PRODUTO/SERVIÇO</strong>
                        </h3>
                  </div> 

                  <div class="col-md-2 col-sm-2 col-xs-2 form-group" >
                        <h5><strong style="margin-left: 10px;">Orçamento N&ordm;: {$os[0].ID}</strong></h5>
                  </div>                   
            </div>

            <div class="row invoice-info">
                  <div class="col-md-10 col-sm-10 col-xs-10 form-group line">
                        <strong>Cliente:</strong>
                              {$os[0].NOME} - 
                              {if $os[0].PESSOA == 'J'}
                                    CNPJ:
                              {else}
                                    CPF: 
                        {/if}
                        {$os[0].CNPJCPF}
                  </div>

                  <div class="col-md-2 col-sm-2 col-xs-2 form-group line">
                        <div style="margin-left: 10px;"><strong>Data:&nbsp;</strong>{$os[0].DATAABERATEND|date_format:"%d/%m/%Y"}</div>
                  </div> 
            </div>

            <div class="row invoice-info">
                  <div class="col-md-12 col-sm-12 col-xs-12 form-group line">
                        <div><strong>Endereço:</strong>
                              {$os[0].ENDERECO}, 
                              {$os[0].NUMERO}
                              &emsp;  
                              <strong>Bairro:</strong>
                              {$os[0].BAIRRO} 
                              &emsp;                         
                              <strong>Cidade:</strong>
                              {$os[0].CIDADE} - {$os[0].UF}
                        </div>
                  </div>
            </div>

            <div class="row invoice-info">
                  <div class="col-md-10 col-sm-10 col-xs-10 form-group line">
                         <div><strong>Fone:</strong>
                                    {if $os[0].FONE neq ''}
                                          {$os[0].FONE}
                                    {else}
                                          (&emsp;) ______-_____
                                    {/if}      
                                          &emsp;
                               <strong>Celular:</strong>
                                    {if $os[0].CELULAR neq ''}
                                          {$os[0].CELULAR}
                                    {else}
                                          (&emsp;) ______-_____
                                    {/if}
                                    &emsp;
                               <strong>E-mail:</strong> 
                                    {$os[0].EMAIL}
                        </div>
                  </div>

                  <div class="col-md-2 col-sm-2 col-xs-2 form-group line">
                        <div style="margin-left: 10px;">
                              <strong>Entrega:</strong>
                              {if $os[0].PRAZOENTREGA neq ''}
                                    {$os[0].PRAZOENTREGA|date_format:"%d/%m/%Y"}
                              {else}
                                    A COMBINAR
                              {/if}
                        </div>
                  </div>     
            </div>

            <div class="row invoice-info">
                  <div class="col-md-10 col-sm-10 col-xs-10 form-group line">
                        <div id="textocabecalho">
                              Prezado(a) {$os[0].NOMEREDUZIDO}.
                              <p>Apresentamos a seguir, o nosso orçamento e as demais condições 
                              de fornecimento dos produto(s) e/ou serviço(s) relacionados.</p>
                        </div>
                  </div>
            </div>
      </div>
      </br>
      <!-- page content -->

            <div class="contaider-fluid">
            
                  <div class="x_content">
                        
                        <div class="row small">
                              
                              <table class="table table-bordered" id="tab1">

                                    <tr>
                                          <th class="n_cod" style="vertical-align: middle;">CÓD PRODUTO</th>
                                          <th class="n_desc" style="vertical-align: middle;">PRODUTO</th>
                                          <th class="n_un" style="vertical-align: middle;">CÓD NOTA</th>
                                          <th class="n_un" style="vertical-align: middle;">UN</th>
                                          <th class="n_quant" style="vertical-align: middle;">QUANT</th>
                                          <th class="n_vu" style="vertical-align: middle;">VALOR UNITARIO</th>
                                          <th class="n_vt" style="vertical-align: middle;">VALOR TOTAL</th>
                                    </tr>
                                    {section name=i loop=$osItem}                                                            
                                    <tr>
                                    <td class="n_cod" style="vertical-align: middle;"> {$osItem[i].ID} </td>
                                          <td style="vertical-align: middle;"> {$osItem[i].DESCRICAO} </td>
                                          <td class="n_un" style="vertical-align: middle;"> {$osItem[i].CODPRODUTONOTA} </td>
                                          <td class="n_un" style="vertical-align: middle;"> {$osItem[i].UNIDADE} </td>
                                          <td class="n_quant" style="vertical-align: middle;"> {$osItem[i].QUANTIDADE|number_format:2:",":"."} </td>
                                          <td class="n_vu" style="vertical-align: middle;"> {$osItem[i].VALORUNITARIO|number_format:2:",":"."} </td>
                                          <td class="n_vt" style="vertical-align: middle;"> {$osItem[i].VALORTOTAL|number_format:2:",":"."} </td>
                                    </tr>
                                    {/section} 
                              </table>
                              {if $osServico neq null}
                              <table class="table table-bordered" id="tab2">
                                          
                                    <tr>
                                          <th class="n_cod" style="vertical-align: middle;">CÓD SERVIÇO</th>
                                          <th class="n_desc" style="vertical-align: middle;">SERVI&Ccedil;O</th>
                                          <th class="n_un" style="vertical-align: middle;">UN</th>
                                          <th class="n_quant" style="vertical-align: middle;">QUANT</th>
                                          <th class="n_vu" style="vertical-align: middle;">VALOR UNITARIO</th>
                                          <th class="n_vt" style="vertical-align: middle;">VALOR TOTAL</th>
                                    </tr>
                                          
                                    {section name=i loop=$osServico}                                                            
                                    <tr>
                                          <td class="n_cod" style="vertical-align: middle;"> {$osServico[i].ID} </td>
                                          <td style="vertical-align: middle;"> {$osServico[i].DESCSERVICO} </td>
                                          <td class="n_un" style="vertical-align: middle;"> {$osServico[i].UNIDADE} </td>
                                          <td class="n_quant" style="vertical-align: middle;"> {$osServico[i].QUANTIDADE|number_format:2:",":"."} </td>
                                          <td class="n_vu" style="vertical-align: middle;"> {$osServico[i].VALUNITARIO|number_format:2:",":"."} </td>
                                          <td class="n_vt" style="vertical-align: middle;"> {$osServico[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                    </tr>
                                    {/section}
                                          
                              </table>
                              {/if}                     
                                    
                              <table class="table table-bordered" id="tab2">
                                          
                                    {section name=i loop=$os} 
                                    <tr>
                                          <th class="valores">VALOR PRODUTO:&nbsp;R$ {$os[i].VALORPECAS|number_format:2:",":"."}</th>
                                          <th class="valores">VALOR DESCONTO: R$ {$os[i].VALORDESCONTO|number_format:2:",":"."}</th> 
                                    </tr>
                                          
                                    <tr>
                                          <th class="valores">VALOR SERVIÇO: R$ {$os[i].VALORSERVICOS|number_format:2:",":"."}</th>
                                          <th class="valores">VALOR TOTAL: R$ {$os[i].VALORTOTAL|number_format:2:",":"."}</th>
                                    </tr>

                                    <tr>
                                          <th class="valores">VALOR VISITA: R$ {$os[i].VALORVISITA|number_format:2:",":"."}</th>
                                          <th class="valores">CONDIÇÃO DE PAGAMENTO: {$os[0].DESCCONDPGTO}.
                                    </tr>
                                    {/section}
                                        
                              </table>
                        </div>
                  </div>
            </div>

            <div class="col-sm-12" role="main">
                  <div class="col-sm-12 invoice-col" id="obs">
                        {if $os[0].OBS neq ''}
                              <strong id="obs">Observa&ccedil;&otilde;es Produto: </strong>
                              </br> {$os[0].OBS}.
                              </br>
                              </br>
                        {else}     
                        {/if}

                        {if $os[0].OBSSERVICO neq ''}
                              <strong id="obs">Observa&ccedil;&otilde;es Serviço: </strong>
                              </br> {$os[0].OBSSERVICO}.
                              </br>
                              </br>
                        {else}
                        {/if}
                  </div>
            </div>
      
            </br>
            </br>

            <div class="x_content" role="main" id="condicoes">
                  <p><strong>Condições para fornecimento: </p></strong>
                        - Antes de confirmar seu pedido, favor tirar todas as dúvidas, devoluções só serão aceitas por defeitos de fabricação.</br>
                        - O preço está em REAIS, baseado no dólar de venda e que será CORRIGIDO de acordo com a variação da moeda do dia anterior ao efetivo faturamento.</br>
                        - Garantia: 2(três) meses contados a partir da emissão da Nota-Fiscal.</br>
                              &emsp;A garantida limita-se a reelaboração dos serviços e substituição dos materiais aplicados comprovadamente defeituosos verificados no período mencionado acima</br> 
                              &emsp;desde que em operação dentro das características normais e de acordo com as recomendações do fabricante ou na falta destes, de acordo com a melhor prática.</br> 
                              &emsp;Com o reparo fica plenamente satisfeita e a garantia, sem qualquer outra responsabilidade para a "BIANCO".</br>
                        - Os orçamentos de serviço e o conserto dos componentes não seja aprovado, os mesmo ficarão a disposição para serem retirados
                              pelo prazo de 90(noventa) dias.</br> 
                              &emsp;Após esse prazo os materiais serão sucateados.</br>
                              &emsp;No aguardo de seu pronunciamento, estamos a sua inteira disposição para quaisquer esclarecimento que se fizerem necessária.
            </div>
      
            <div class="row invoice-info" id="assin_dat_vend">            
                  <div class="col-md-4 col-sm-4 col-xs-4 invoice-col" id="assina">
                        <strong>__________________________________________</strong></br>
                        <strong style="margin-left: 140px;">Assinatura </strong>
                  </div>
            
                  <div class="col-md-4 col-sm-4 col-xs-4 invoice-col" id="data">
                        <strong>______/______/______</strong></br>
                        <strong style="margin-left: 60px;">Data</strong>
                  </div>

                  <div class="col-md-4 col-sm-4 col-xs-4 invoice-col" id="vendedor">
                        <li>_______________________</li>
                        <strong style="margin-left: 68px;">Vendedor</strong>
                  </div>
            </div>

            <div class="footer">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <li style="font-size: 18px;"><strong>{$empresa[0].NOMEEMPRESA}</li></strong>
                        <li style="margin-left: -140px;">{$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, 
                        {$empresa[0].NUMERO}, {$emsa[0].COMPLEMENTO} {$empresa[0].BAIRRO} {$empresa[0].CIDADE}, 
                        {$empresa[0].UF} {$empresa[0].CEP} - Fone: ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}</br>
                  </div>
            </div>
      </div>

<!-- /page content -->
      
<style>

       li{
            list-style: none;
      }

       #uservend{
            font-size: 16px;
            position: absolute;
            margin: -6px 0 0 80px;
      }

      .n_cod {
            width: 75px;
            text-align: center;
      }


      .n_un {
            width: 35px;
            text-align: center;
      }

      .n_quant {
            width: 60px;
            text-align: center;
      }

      .n_vu{
            width: 70px;
            text-align: center;
      }

      .n_desc{
            text-align: center;
      }

      .n_vt{
            width: 70px;
            text-align: center;
      }

      #obs{
            font-size: 15px;
            margin-top: -8px;
            padding: 0px;
      }

      #textocabecalho{
            font-size: 16px;
            margin-top: 15px;
      }

      

      #infoscliente{
            padding: 2px;
      }

      #tab1{
            margin-top: -20px;
      } 

      #tab2{
            margin-top: -15px;
      }

      #condicoes{
            margin-top: -5px;
            padding: 0px;
            font-size: 16px;
      }

      .line{
            font-size: 14px;
      }

      #assina{
            font-size: 16px;
            margin: 60px 0px 0px 110px;
      }
      
      #data{
            font-size: 16px;
            margin: -45px 0px 0px 790px;
      }

      #vendedor{
            font-size: 16px;
            margin: -45px 0px 0px 1200px;
      }width: 100%;
            border-bottom: 1px solid #000000;

      .footer{
            position: static;
            margin: 90px 0 0 400px;
      }

      #assin_dat_vend{
            font-size: 12px;
      }

      .valores{
            font-size: 12px;
      }

  @media print {
      .footer{
            position: absolute;
            bottom: 0;
            margin: 0 0 0 32%;
      }

      a[href]:after {
      content: none !important;
      }
      .no-print{
      display: none;
      }

      #assin_dat_vend{
            margin: 900px 0 0 -10px;
      }
}

</style>


                                                      

                                                        
                                                                  