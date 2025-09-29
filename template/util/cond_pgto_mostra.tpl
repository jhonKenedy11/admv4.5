<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_cond_pgto.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
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
              <h2>Condição de Pagamento - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('classe');">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
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
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">

                    <th>Cond Pgto</th>
                    <th>Descri&ccedil;&atilde;o</th>
                    <th>Forma Pgto</th>
                    <th>Num Parcelas</th>
                    <th style="width: 40px;">Manuten&ccedil;&atilde;o</th>

                  </tr>
                </thead>
                <tbody>

                  {section name=i loop=$cond}
                    {assign var="total" value=$total+1}
                    <tr>
                      <td> {$cond[i].ID} </td>
                      <td> {$cond[i].DESCRICAO} </td>
                      <td> {$cond[i].FORMAPGTO} </td>
                      <td> {$cond[i].NUMPARCELAS} </td>
                      <td>
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$cond[i].ID}');"><span class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$cond[i].ID}');"><span class="glyphicon glyphicon-trash"
                            aria-hidden="true"></span></button>
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