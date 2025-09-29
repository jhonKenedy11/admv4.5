<!-- page content -->
<section class="height100">
<div class="right_col" role="main">
      <div class="">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h5>
                      <center><strong>Relat&oacute;rio Notas Fiscais</strong></center><br>
                      <center>Per&iacute;odo - {$dataInicio} | {$dataFim}</center>
                  </h5>
                </div>
            </div>  
      </div>

      <!-- page content -->
      <div class="right_col" role="main">
          <div class="clearfix"></div-->
                  <!--<div class="x_panel" style="padding: -8px 17px !important;">-->
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12">
                                                <table class="table table-striped tabela_total" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="50px">N&#186; NFe</th>
                                                                  <th width="60px"><center>EMISS&Atilde;O</center></th>
                                                                  <th width="110px">FILIAL</th>
                                                                  <th width="250px">PESSOA</th>
                                                                  <th width="50px">SITUA&Ccedil;&Atilde;O</th>
                                                                  <th><center>TOTAL</center></th>
                                                                  <th width="120px"><center>XML</center></th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>                                                            
                                                            {section name=i loop=$lanc}
                                                                  {assign var="totalEntrada" value=$totalEntrada+$lanc[i].TOTAL}
                                                                  
                                                            <tr {if $lanc[i].XML neq true} style="background-color: rgba(250, 30, 30, 0.307);" {/if}>
                                                                        <td class="infos-line"> {$lanc[i].NUMERO} </td>
                                                                        <td class="infos-line"><center> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </center></td>
                                                                        <td class="infos-line"> {$lanc[i].FILIAL} </td>
                                                                        <td class="infos-line"> {$lanc[i].PESSOA} </td>
                                                                        <td class="infos-line"> {$lanc[i].SITUACAO} </td>
                                                                        <td class="infos-line"><center> {$lanc[i].TOTAL|number_format:2:",":"."} </center></td>
                                                                        {if $lanc[i].XML eq true}
                                                                              <td>
                                                                                    <center><a href="{$lanc[i].PATH_XML}" download><span class="glyphicon glyphicon-download-alt"></span></a></center>
                                                                              </td>
                                                                        {elseif $lanc[i].XML eq null}
                                                                              <td>
                                                                                    <center><span class="glyphicon glyphicon-remove" style="color: red;" aria-hidden="true" title="Xml nao localizado"></span></center>
                                                                              </td>
                                                                        {else}
                                                                              <td>
                                                                                    <center><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></center>
                                                                              </td>
                                                                        {/if}
                                                                  </tr>
                                                                        
                                                                  <p>
                                                            {/section} 
                                                            <tr>
                                                                <td colspan="2"><h7><b>TOTAL </b></h7></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td colspan="2"><center><h6><b>R$ {($totalEntrada - $totalSaida)|number_format:2:",":"."}</b></h6></center></td>
                                                            </tr>
                                                            {if $cartaCorrecao neq false}
                                                                  <tr>
                                                                        <th colspan="7"><h4><b><center>Carta Corre&ccedil;&atilde;o do Per&iacute;odo</center></b></h4></th>
                                                                  </tr>
                                                                  <tr>
                                                                        <th> N&#186; NFe </th>
                                                                        <th><center> CHAVE NFe </center></th>
                                                                        <th width="110px"> MÊS EMISSAO NFe</th>
                                                                        <th><center> DATA EVENTO </center></th>
                                                                        <th colspan="2"> DESCRIÇÃO EVENTO </th>
                                                                        <th> XML </th>
                                                                  </tr>
                                                                  {section name=j loop=$cartaCorrecao}
                                                                        <tr>
                                                                              <td> {$cartaCorrecao[j].NUM_NF} </td>
                                                                              <td> {$cartaCorrecao[j].CHAVE_NFE} </td>
                                                                              <td><center>
                                                                                    {if $cartaCorrecao[j].MES_EMISSAO_NF == '01'}
                                                                                          JANEIRO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '02'}
                                                                                          FEVEREIRO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '03'}
                                                                                          MARÇO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '04'}
                                                                                          ABRIL
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '05'}
                                                                                          MAIO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '06'}
                                                                                          JUNHO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '07'}
                                                                                          JULHO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '08'}
                                                                                          AGOSTO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '09'}
                                                                                          SETEMBRO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '10'}
                                                                                          OUTUBRO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '11'}
                                                                                          NOVEMBRO
                                                                                    {elseif $cartaCorrecao[j].MES_EMISSAO_NF == '12'}
                                                                                          DEZEMBRO
                                                                                    {/if}
                                                                              </center></td>                                                               
                                                                              <td><center> {$cartaCorrecao[j].DATA_EVENT|date_format:"%d/%m/%Y"} </center></td>
                                                                              <td colspan="2"> {$cartaCorrecao[j].DESC_EVENTO} </td>
                                                                              <td>...</td>
                                                                        </tr>
                                                                  {/section}
                                                            {/if}

                                                            {if $sequenciaFaltando neq false}
                                                                  <tr>
                                                                        <th colspan="10"><h4><b><center>Sequ&ecirc;ncia faltante</center></b></h4></th>
                                                                  </tr>
                                                                  <tr>
                                                                        <th colspan="10"> N&#186; NFe </th>
                                                                  </tr>
                                                                  {section name=j loop=$sequenciaFaltando}
                                                                        <tr style="background-color: rgba(255, 221, 0, 0.631);">
                                                                              <td colspan="10"> {$sequenciaFaltando[j]} </td>
                                                                        </tr>
                                                                  {/section}
                                                            {/if}
                                                            
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </section>
                        </div>
                  <!--</div>-->
          </div>
      </div>
      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
</section>
<!-- /page content -->
<style>
.table>tbody>tr>td, 
.table>tbody>tr>th, 
.table>tfoot>tr>td, 
.table>tfoot>tr>th, 
.table>thead>tr>td, 
.table>thead>tr>th{
    padding: 4px !important;
}

.table{
    background-color: #fff;
    border-style: solid;
    border-width: 1px;
    border-color: #d9d5d5;
}


a{       
    text-decoration: none;
    color: grey;     
}

.infos-line{
    font-size: 10px;
}

.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

@media print {
    a[href]:after { 
      content:"(" attr(href) ")"; 
    }
    a[href*=".xml"]:after { 
      content:""; 
    }

    @page{
      margin-top: 0;
      margin-bottom: 0;
      display: none;
    }
    .no-print{
      display: none;
    }
}
</style>

