<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>

<script type="text/javascript" src="{$pathJs}/util/s_util.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <form id="lancamento" class="form-horizontal form-label-left" NAME="lancamento" ACTION="{$SCRIPT_NAME}" METHOD="post"
    enctype="multipart/form-data">
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">
      
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Importa&ccedil;&otilde;es - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Importar</span></button>
                </li>
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

              <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <label>Arquivo </label>
                  <select class="form-control" name=arqImporta id="arqImporta">
                    {html_options values=$arqImporta_ids selected=$arqImporta_id output=$arqImporta_names}
                  </select>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                  <label for="nome">Selecione a Planilha</label>
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-default btn-file"><input type="file" name="arq" /></span>
                  </div>
                  <div class="form-group">
                  </div>


                </div> <!-- div class="x_content" = inicio tabela -->
              </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
          </div> <!-- div class="row "-->
        </div> <!-- class='' = controla menu user -->

  </form>


  {include file="template/database.inc"}

<!-- /Datatables -->