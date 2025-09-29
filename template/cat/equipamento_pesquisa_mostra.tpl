<style>
.form-control, .x_panel{
  border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/cat/s_equipamento.js"> </script>
  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod              type=hidden value="cat">   
        <input name=form             type=hidden value="equipamento">   
        <input name=id               type=hidden value="">
        <input name=letra            type=hidden value={$letra}>
        <input name=opcao            type=hidden value={$opcao}>
        <input name=submenu          type=hidden value={$subMenu}>
        <input name=origem           type=hidden value='pesquisaEquipamento'>
        <input name=catEquipamentoId type=hidden value="{$catEquipamentoId}">  
        <input name=descEquipamento  type=hidden value="{$descEquipamento}"> 
        
        <div class="">

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2> Equipamentos - consulta
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button"  class="btn btn-primary"  onClick="javascript:submitCadastro('banco');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button></li>
                        {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>Código</th>
                                <th>Descrição</th>
                                <th class=" no-link last" style="width: 40px;">Selecionar</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].ID} </td>
                                    <td> {$lanc[i].DESCRICAO} </td>
                                    <td class=" last">
                                        <button type="button" class="btn btn-success btn-xs" 
                                        onclick="javascript:fechaEquipamentoPesquisaAtendimento({$lanc[i].ID}, '{$lanc[i].DESCRICAO}');">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<script>
$(document).ready(function() {
  $("input[type=search]").focus();
});
</script>