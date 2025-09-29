<!--<script type="text/javascript" src="{$pathJs}/crm/s_classe.js"> </script>-->
<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="crm">
    <input name=form type=hidden value="classe">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Classe - Consulta</h2>
              {include file="../bib/msg.tpl"}
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();">
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
              <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>Classe</th>
                    <th>Descri&ccedil;&atilde;o</th>
                    <th>Bloqueado</th>
                    <th style="width: 40px;">Manuten&ccedil;&atilde;o</th>

                  </tr>
                </thead>
                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr>
                      <td> {$lanc[i].CLASSE} </td>
                      <td> {$lanc[i].DESCRICAO} </td>
                      {if $lanc[i].BLOQUEADO eq 'S'}
                        <td> Sim </td>
                      {else}
                        <td> Não </td>
                      {/if}

                      <td>
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$lanc[i].CLASSE}');"><span
                            class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$lanc[i].CLASSE}');"><span class="glyphicon glyphicon-trash"
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

< !-- /Datatables -->