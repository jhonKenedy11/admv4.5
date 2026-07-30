<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_template_email.js"></script>
<div class="right_col" role="main">
  <form class="full" name="lancamento" method="post" novalidate action="{$SCRIPT_NAME}">
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value="{$letra|escape:'html'}">
    <input name=submenu type=hidden value="{$subMenu|escape:'html'}">

    <div class="">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Template de e-mail - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem|escape:'html'}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onclick="javascript:submitCadastro();">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>ID</th>
                    <th>Descri&ccedil;&atilde;o</th>
                    <th>Par&acirc;metro</th>
                    <th>Corpo (resumo)</th>
                    <th style="width: 90px;">Manuten&ccedil;&atilde;o</th>
                  </tr>
                </thead>
                <tbody>

                  {foreach from=$lista item=row}
                    <tr>
                      <td> {$row.ID|escape:'html'} </td>
                      <td> {$row.DESCRICAO|escape:'html'} </td>
                      <td> {$row.PARAMETRO|escape:'html'} </td>
                      <td> {$row.BODY|truncate:120|escape:'html'} </td>
                      <td>
                        <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$row.ID|escape:'javascript'}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$row.ID|escape:'javascript'}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                      </td>
                    </tr>
                  {/foreach}

                </tbody>

              </table>

            </div>
          </div>
        </div>
      </div>
    </div>

  </form>

  {include file="template/database.inc"}

</div>
