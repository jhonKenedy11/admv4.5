<style>
  .form-control, .x_panel {
    border-radius: 5px;
  }
  .right_col{
    padding: 5px !important;
  }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_meta_mensal.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>    

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
      <input name=metaid type=hidden value={$metaid}>
      <input name=id type=hidden value={$id}>
      <input name=param type=hidden value={$param}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2><b> Meta vendedor </b> -
                {if $subMenu eq "cadastrar"}
                  cadastro
                {else}
                  altera&ccedil;&atilde;o
                {/if}
                {if $mensagem neq ''}
                  {if $tipoMsg eq 'sucesso'}
                    <div class="row">
                      <div class="col-lg-12 text-left">
                        <div>
                          <div class="alert alert-success" role="alert"><strong>{$mensagem}</div>
                        </div>
                      </div>
                    </div>
                  {elseif $tipoMsg eq 'alerta'}
                    <div class="row">
                      <div class="col-lg-12 text-left">
                        <div>
                          <div class="alert alert-danger" role="alert"><strong>{$mensagem}</div>
                        </div>
                      </div>
                    </div>
                  {/if}
                {/if}
              </h2>

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmarVendedor('');">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltarVendedor('');">
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

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendedor">Vendedor<span
                    class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name=vendedor id="vendeddor">
                    {html_options values=$vendedor_ids selected=$vendedor output=$vendedor_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Meta<span class="required"></span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control money" maxlength="14" type="text" id="meta" name="meta" value={$meta}>
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
        allowZero: true,
      });
    });
</script>