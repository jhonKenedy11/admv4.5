<style>
  table {
    border-spacing: 0;
    border-collapse: none !important;
  }
  .table-bordered>thead>tr>th {
    border-radius: 7px !important;
    padding: 5px !important;
  }
  .x_panel,
  [name=datatable-buttons_length],
  [type=search] {
    border-radius: 5px;
  }
  .form-control:focus-within {
  border: solid 2px;
  border-color: rgb(68, 147, 250) !important;
}
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_parametro.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=filial type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>FAT Par&acirc;metro - Consulta</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('parametros');">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>Filial</th>
                    <th>Valor Pedido Minimo</th>
                    <th>Maximo Desconto</th>
                    <th>Aprovacao</th>
                    <th>Encomenda</th>
                    <th>Grupo Servico</th>
                    <th style="width: 70px;">Manut.</th>
                  </tr>
                </thead>

                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer">
                      <td> {$lanc[i].NOMEFANTASIA} </td>
                      <td> {$lanc[i].VALORPEDIDOMINIMO|number_format:2:",":"."} </td>
                      <td> {$lanc[i].DESCONTOMAXIMO|number_format:2:",":"."} </td>
                      <td> {$lanc[i].APROVACAO} </td>
                      <td> {$lanc[i].ENCOMENDA} </td>
                      <td> {$lanc[i].GRUPOSERVICO} </td>
                      <td class=" last">
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$lanc[i].FILIAL}');"><span class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$lanc[i].FILIAL}');"><span class="glyphicon glyphicon-trash"
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