<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_meta.js"> </script>
{* <script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script> *}

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="est">
      <input name=form type=hidden value="meta">
      <input name=opcao type=hidden value="">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=vendedor type=hidden value={$vendedor}>
      <input name=meta type=hidden value={$meta}>

      {if $subMenu eq "alterar"}
        <input name=id type=hidden value={$id}>
      {/if}
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Metas - 
                {if $subMenu eq "cadastrar"}
                  Cadastro
                {else}
                  Altera&ccedil;&atilde;o
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
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('');">
                    <span class="glyphicon glyphicon-flopy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                </li>
                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
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
              <br />

              {if $subMenu ne "cadastrar"}
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id"><span class="required"></span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input class="form-control" type="text" maxlength="15" name="id" disabled value={$id}>
                  </div>
                </div>
              {/if}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendedor">Vendedor<span
                    class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name="vendedor" id="vendedor">
                    {html_options values=$vendedor_ids selected=$vendedor output=$vendedor_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ano">Ano <span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control" type="text" id="ano" name="ano" value={$ano}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mes">Mês <span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name="mes" id="mes">
                    {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Meta<span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control money has-feedback-left" type="text" id="meta" name="meta"  value="{if $meta}{$meta|floatval|number_format:2:',':'.'}{else}0,00{/if}">
                  <span class="form-control-feedback left" aria-hidden="true"><b>R$</b></span>
                </div>
              </div>

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
        allowNegative: true
    });

    $(".money").blur(function() {
        var value = $(this).val();
        if (value === "") {
            $(this).val("0,00");
        }
    });
});
</script>
