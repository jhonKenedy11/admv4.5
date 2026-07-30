<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
  .usuario-lista-msg {
    margin: 0 0 8px 0;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_usuario.js"></script>

<div class="right_col" role="main">
  <form class="full" name="lancamento" method="post" novalidate action="{$SCRIPT_NAME}">
    <input type="hidden" name="mod" value="{$mod}">
    <input type="hidden" name="form" value="{$form}">
    <input type="hidden" name="usuario" value="">
    <input type="hidden" name="pessoa" value="">
    <input type="hidden" name="letra" value="{$letra|escape:'html'}">
    <input type="hidden" name="submenu" value="{$subMenu|escape:'html'}">

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Usuários — Consulta
            </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li>
                <button type="button" class="btn btn-primary" onclick="javascript:submitCadastro('');">
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                  <span> Cadastro</span>
                </button>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="datatable-buttons" class="table table-bordered jambo_table table-condensed">
              <thead>
                <tr class="headings">
                  <th>Tipo</th>
                  <th>Matr&iacute;cula</th>
                  <th>Nome reduzido</th>
                  <th>Nome</th>
                  <th>Grupo</th>
                  <th>Situa&ccedil;&atilde;o</th>
                  <th class="no-link last" style="width: 90px;">Manuten&ccedil;&atilde;o</th>
                </tr>
              </thead>
              <tbody>
                {section name=i loop=$lanc}
                  <tr class="even pointer">
                    <td>{$lanc[i].DESCTIPO|escape:'html'}</td>
                    <td>{$lanc[i].USUARIO|escape:'html'}</td>
                    <td>{$lanc[i].NOMEREDUZIDO|escape:'html'}</td>
                    <td>{$lanc[i].NOMEUSUARIO|escape:'html'}</td>
                    <td>{$lanc[i].NOMEGRUPO|escape:'html'}</td>
                    <td>{$lanc[i].DESCSITUACAO|escape:'html'}</td>
                    <td class="last text-center">
                      <button type="button" title="Alterar (dados e direitos)" class="btn btn-primary btn-xs"
                        onclick="javascript:submitAlterar('{$lanc[i].USUARIO|escape:'javascript'}');">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      </button>
                      <button type="button" title="Excluir" class="btn btn-danger btn-xs"
                        onclick="javascript:submitExcluir('{$lanc[i].USUARIO|escape:'javascript'}');">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                      </button>
                    </td>
                  </tr>
                {/section}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>

  {include file="template/database.inc"}
</div>
