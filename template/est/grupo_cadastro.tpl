<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_grupo.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="{$mod}">
      <input name=form type=hidden value="{$form}">
      <input name=opcao type=hidden value="">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=grupoBase type=hidden value={$grupoBase}>
      <input name=nivel type=hidden value={$nivel}>
      <input name=id type=hidden value={$id}>



      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  Grupos - Cadastro
                {else}
                  Grupos - Altera&ccedil;&atilde;o
                {/if}
                {if $mensagem neq ''}
                  {if $tipoMsg eq 'sucesso'}
                    <div class="row">
                      <div class="col-lg-12 text-left">
                        <div>
                          <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                        </div>
                      </div>
                    </div>
                  {elseif $tipoMsg eq 'alerta'}
                    <div class="row">
                      <div class="col-lg-12 text-left">
                        <div>
                          <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                        </div>
                      </div>
                    </div>
                  {/if}

                {/if}
              </h2>

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('genero');">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('genero');">
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                </li>
                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />

              {if $subMenu !== "cadastrar"}
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Grupo Base<span
                      class="required"></span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12 has-feedback">
                    <input class="form-control has-feedback" type="text" maxlength="15" name="grupoBase" disabled value={$grupoBase}>
                  </div>
                </div>
              {/if}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descricao">Descri&ccedil;&atilde;o <span
                    class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control" type="text" maxlength="40" id="descricao" name="descricao"
                    placeholder="Digite a descrição." value={$descricao}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipo">Tipo <span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="tipo" class="form-control">
                    {html_options values=$tipoGrupo_ids selected=$tipo output=$tipoGrupo_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nivel">N&iacute;vel <span
                    class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control" type="text" disabled id="nvl" name="nvl" value={$nivel}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="comissao">Comiss&atilde;o Vendas <span
                    class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control money" type="text" id="comissao" name="comissao" maxlength="11" value={$comissao}>
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


  <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".money").maskMoney({
        decimal: ",",
        thousands: ".",
        allowNegative: true,
        allowZero: true
      });
});
  </script>