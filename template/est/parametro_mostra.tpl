<style>
.right_col {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

.x_panel {
    padding-top: 5px !important;
}

.form-control,
.x_panel {
  border-radius: 5px;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_parametro.js"></script>
<script src="{$bootstrap}/sweetalert2/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
  <form class="full" name="parametro" method="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="est">
    <input name=form type=hidden value="parametro">
    <input name=filial type=hidden value="">
    <input name=modelo type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>
    <input name=id type=hidden value={$id}>

    <div class="">
     
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Parâmetros de Estoque</h2>

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>Empresa</th>
                    <th>CNPJ</th>
                    <th>Série</th>
                    <th>CFOP</th>
                    <th class=" no-link last" style="width: 80px;">Manutenção</th>
                  </tr>
                </thead>

                <tbody>

                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer">
                      <td>{$lanc[i].NOMEEMPRESA}</td>
                      <td>{$lanc[i].CNPJ}</td>
                      <td>{$lanc[i].SERIE}</td>
                      <td>{$lanc[i].CFOP}</td>
                      <td>
                        <button type="button" class="btn btn-primary btn-xs"
                          onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil"
                            aria-hidden="true"></span></button>
                        <button type="button" class="btn btn-danger btn-xs"
                          onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash"
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
