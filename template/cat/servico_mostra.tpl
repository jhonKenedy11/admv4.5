<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
.label{
  font-size: 95% !important;
}
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_servico.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="cat">
    <input name=form type=hidden value="servico">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Servi&ccedil;os - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('banco');">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
                </li>
                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                      class="fa fa-wrench"></i></a>
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
                    <th width="4%">Código</th>
                    <th><center>Descrição</center></th>
                    <th width="8%"><center>Status</center></th>
                    <th style="width: 40px;">Manutenção</th>
                  </tr>
                </thead>

                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer">
                      <td><center> {$lanc[i].ID} </center></td>
                      <td><center> {$lanc[i].DESCRICAO} </center></td>
                      <td><center> {if $lanc[i].STATUS eq '1'}<span class="label label-success">Ativo</span>{else}<span class="label label-danger">Inativo</span>{/if}</center> </td>
                      <td class=" last"><center>
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash"
                            aria-hidden="true"></span></button></center>
                      </td>
                    </tr>
                  {/section}

                </tbody>

              </table>

          </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
      </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->

  </form>

 
<!-- /Datatables -->
{include file="template/form.inc"}