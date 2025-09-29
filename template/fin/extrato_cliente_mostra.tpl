<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_extrato.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Extrato Financeiro
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span style="color:white;" class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span style="color:white;"> Consultar </span>
                            </button>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>

                        <!--form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} -->
                        <input name=mod           type=hidden value="{$mod}">   
                        <input name=form          type=hidden value="extrato_cliente">   
                        <input name=id            type=hidden value="">
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=opcao         type=hidden value="extrato_cliente">   
                        <input name=fornecedor    type=hidden value={$pessoaFornecedor}> 
                        <input name=centroCusto   type=hidden value={$centroCusto}> 
                        <input name=pessoa        type=hidden value={$pessoa}> 
                        <input name=genero        type=hidden value={$genero}> 
                        <input name=dataIni       type=hidden value={$dataIni}> 
                        <input name=dataFim       type=hidden value={$dataFim}> 
                        <input name=linhas        type=hidden value={$linhas}>  
                        <input name=vencimento    type=hidden value={$vencimento}>
                        <input name=total         type=hidden value={$total}>  
                        <input name=genero        type=hidden value={$genero}>   
                        <input name=centrocusto   type=hidden value={$centrocusto}>   
                        <input name=dataReferencia type=hidden value="">   
                        <input name=tipolanc      type=hidden value="">   
                        <select id="sitlanc" name="sitlanc" style="visibility:hidden;">
                            {html_options values=$sitLanc_ids selected=$sitLanc_id output=$sitLanc_names}
                        </select>  
                        <select id="tipolanc" name="tipolanc" style="visibility:hidden;">
                            {html_options values=$sitLanc_ids selected=$sitLanc_id output=$sitLanc_names}
                        </select>                                              
                        <input name=descgenero    type=hidden value="">   

                        <div class="form-group">

                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label class="">Per&iacute;odo</label><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                                  <select class="form-control"  id="mes" name="mes">
                                    {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                                  </select>
                            </div>  
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label class="">Farmácia</label>
                                <input class="form-control" maxlength="40" type="text" id="nome" name="nome" value={$nome}>
                            </div>  
                        </div>
                    </form>

                              
                    </div>
                          
                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->



              <!-- panel tabela dados -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="h3 col-md-7 col-sm-12 col-xs-12">
                        <label>CLIENTE: </label>
                        <label>Débito: {$totalPag|number_format:2:",":"."}</label> - 
                        <label>Crédito: {$totalRec|number_format:2:",":"."}</label> =
                        <label>Saldo: {$saldo|number_format:2:",":"."}</label>
                        {if $saldo lt 0}
                          <button type="button" class="btn btn-warning btn-md "
                              onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra={$idfin}');">
                              <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span><span> Boleto</span></button>
                        {/if}


                    </div>
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%">
                    <table id="datatable-buttons" class="table table-bordered jambo_table"-->
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th>Pessoa</th>
                                <th>Tipo Lançamento</th>
                                <th>Genero</th>
                                <th>Competência</th>
                                <th>Obs</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                {if $lanc[i].TIPOLANCAMENTO eq "PAGAMENTO"}
                                        <tr class="even pointer danger">
                                        {assign var="pagamentoTotal" value=$pagamentoTotal+$lanc[i].VALOR}
                                {else}
                                        <tr class="even pointer info">
                                        {assign var="recebimentoTotal" value=$recebimentoTotal+$lanc[i].VALOR}
                                {/if}
                                
                                    <td> {$lanc[i].NOME} </td>
                                    <td> {$lanc[i].TIPOLANCAMENTO} </td>
                                    <td> {$lanc[i].DESCGENERO} </td>
                                    <td> {$lanc[i].COMPETENCIA|date_format:"%e %b, %Y"} </td>
                                    <td> {$lanc[i].OBS} </td>
                                    <td align=right>{$lanc[i].VALOR|number_format:2:",":"."} </td>
                                </tr>
                            <p>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->



    {include file="template/database.inc"}  
    
