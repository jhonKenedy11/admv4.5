
{if $origem eq 'atendimento_new'}
  <script type="text/javascript" src="{$pathJs}/cat/s_servico_new.js"> </script>
{else if  $origem eq 'pedido_ps'}
  <script type="text/javascript" src="{$pathJs}/cat/s_servico_new.js"> </script>
{else}
  <script type="text/javascript" src="{$pathJs}/cat/s_servico.js"> </script>
{/if}

<!-- Nas telas de pesquisas ele completa o footer da pagina -->
{if $opcao eq 'pesquisar'}
<style>
@media (min-width: 768px) {
    footer {
        margin-left: 0 !important;
    }
}
</style>
{/if}
  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod                  type=hidden value="cat">   
        <input name=form                 type=hidden value="servico">   
        <input name=id                   type=hidden value="">
        <input name=letra                type=hidden value={$letra}>
        <input name=submenu              type=hidden value={$subMenu}>
        <input name=idServicos           type=hidden value="{$idServicos}">
        <input name=quantidadeServico    type=hidden value="{$quantidadeServico}"> <!-- Atendimento -->
        <input name=vlrUnitarioServico   type=hidden value="{$vlrUnitarioServico}"> <!-- Atendimento -->
        <input name=origem               type=hidden value="{$origem}">
        <input name=opcao                type=hidden value="{$opcao}">

        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Serviço</h3>
              </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="button"  class="btn btn-primary"  onClick="javascript:submitCadastro('banco');">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  
                  
                </div> <!-- div class="x_panel" = painel principal-->
                  <div class="panel-body">
                          <div class="x_panel">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                                              <thead>
                                                  <tr class="headings">
                                                      <th style="width: 90px;"><center>Código</center></th>
                                                      <th>Descrição</th>
                                                      <th style="width: 120px;"><center>Valor Unitário</center></th>
                                                      <th style="width: 40px;"><center>Unidade</center></th>
                                                      <!--<th>Quantidade</th>-->
                                                      <!--<th>Valor Unitário</th>-->
                                                      <th class=" no-link last" style="width: 40px;">Selecionar</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  {section name=i loop=$lanc}
                                                      {assign var="total" value=$total+1}
                                                      <tr class="even pointer">
                                                          <td><center> {$lanc[i].ID} </center></td>
                                                          <td> {$lanc[i].DESCRICAO} </td>
                                                          <td><center> {$lanc[i].VALORUNITARIO|number_format:2:",":"."} </center></td>
                                                          <td><center> {$lanc[i].UNIDADE} </center></td>
                                                          <!--<td> <input class="form-control input-sm money" 
                                                                  title="Digite a qtde para este serviço." id="quant" name="quant{$lanc[i].ID}"
                                                                  value={0|number_format:2:",":"."} >
                                                          </td>-->
                                                          <!--<td> <input class="form-control input-sm money" 
                                                                  title="Digite o valor unitário para este serviço." id="unitario" name="unitario{$lanc[i].ID}" 
                                                                  value={$lanc[i].VALORUNITARIO|number_format:2:",":"."} >
                                                          </td>-->
                                                          <td class=" last"><center>
                                                          {if $origem eq 'atendimento_new'}
                                                            <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaServicoPesquisaAtendimento(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                          {else if  $origem eq 'pedido_ps'}
                                                              <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaServicoPesquisaAtendimento(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                          {else}
                                                              <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaServicoPesquisaAtendimento({$lanc[i].ID});">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                          {/if} 
                                                          </center></td>
                                                      </tr>
                                              {/section} 

                                              </tbody>

                                          </table>
                            </div>
                                        
                        </div>
                      </div>
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true,
         allowZero: true
        });        
     });
    </script> 
<script>
$(document).ready(function() {
  $("input[type=search]").focus();
});
</script>