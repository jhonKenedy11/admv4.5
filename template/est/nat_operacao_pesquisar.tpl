<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_est.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>
    <input name=opcao type=hidden value={$opcao}>

    <div class="">
      
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Natureza Opera&ccedil;&atilde;o - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                      class="fa fa-wrench"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>C&oacute;digo Fiscal</th>
                    <th>Natureza Opera&ccedil;&atilde;o</th>
                    <th>Tipo</th>
                    <th class=" no-link last" style="width: 40px;">Pesquisa</th>
                  </tr>
                </thead>

                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer">
                      <td> {$lanc[i].CODFISC} </td>
                      <td> {$lanc[i].NATOPERACAO} </td>
                      <td> {$lanc[i].DESCTIPO} </td>
                      <td class=" last">
                        <button type="button" class="btn btn-success btn-xs"
                          onclick="javascript:fechaNatOperacao('{$lanc[i].CODFISC}', '{$lanc[i].NATOPERACAO}','{$lanc[i].TIPO}');">
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

< !-- /Datatables -->