<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .select2-container--default .select2-selection--multiple {
    min-height: 38px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_usuario_autoriza.js"> </script>

<div class="right_col" role="main">
  <div class="">
    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" name="lancamento"
      action="{$SCRIPT_NAME}" method="post">
      <input type="hidden" name="mod" value="util">
      <input type="hidden" name="form" value="usuario_autoriza">
      <input type="hidden" name="opcao" value="{$opcao}">
      <input type="hidden" name="submenu" value="{$subMenu}">
      <input type="hidden" name="letra" value="{$letra}">
      <input type="hidden" name="pessoa" value="{$pessoa}">
      <input type="hidden" name="usuario" value="{$usuario}">
      <input type="hidden" name="direitoUser" value="">

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Usuário Autoriza -
                {if $subMenu eq "cadastrar"}Cadastro{else}Alteração{/if}
              </h2>

              {if $mensagem neq ''}
                <div class="row">
                  <div class="col-md-12">
                    {if $tipoMsg eq 'sucesso'}
                      <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <strong>Sucesso!</strong> {$mensagem}
                      </div>
                    {elseif $tipoMsg eq 'alerta'}
                      <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <strong>Aviso!</strong> {$mensagem}
                      </div>
                    {/if}
                  </div>
                </div>
              {/if}

              <ul class="nav navbar-right panel_toolbox">
                <li>
                  <button type="button" class="btn btn-primary" onClick="submitConfirmar('usuario');">
                    <span class="glyphicon glyphicon-floppy-save"></span> Confirmar
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-danger" onClick="submitVoltar('usuario');">
                    <span class="glyphicon glyphicon-backward"></span> Voltar
                  </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>

            <div class="x_content">
              <br>

              <div class="row form-group">
                <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                  <label>Usuário</label>
                  <select class="form-control" id="usuario-id" name="usuario-id" required>
                    {html_options values=$usuario_ids output=$usuario_nomes selected=$usuario_selected}
                  </select>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12 mb-3">
                  <label>Opção Menu</label>
                  <select class="form-control" name="programa" id="programa">
                    {html_options values=$form_names output=$form_descricao selected=$form_id}
                  </select>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-md-8 col-sm-12 col-xs-12 mb-3">
                  <label>Direitos</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="direitos" name="direitos[]"
                    style="width: 100%;">
                    {html_options values=$direitos_ids selected=$direitos_id output=$direitos_names}
                  </select>
                </div>
              </div>

              <div class="ln_solid"></div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  {include file="template/form.inc"}

  <script>
    $(document).ready(function() {
      $('#direitos').select2({
        placeholder: "Selecione os direitos",
        allowClear: true,
        width: '100%'
      });
    });
  </script>
</div>