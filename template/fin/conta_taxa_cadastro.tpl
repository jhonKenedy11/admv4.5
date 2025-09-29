<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_conta_taxa.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="fin">
      <input name=form type=hidden value="banco">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Taxa -
                {if $subMenu eq "cadastrar"}
                  Cadastro
                {else}
                  Altera&ccedil;&atilde;o
                {/if}
              </h2>
              {include file="../bib/msg.tpl"}
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('banco');">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('banco');">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Banco <span class="required">
                    <font color="red">*</font>
                  </span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name=conta id="conta">
                    {html_options values=$conta_ids selected=$conta output=$conta_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="condpgto">Cond Pgto <span
                    class="required">
                    <font color="red">*</font>
                  </span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select class="form-control" name=condpgto id="condpgto">
                    {html_options values=$condpgto_ids selected=$condpgto output=$condpgto_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="taxa">Taxa <span class="required">
                    <font color="red">*</font>
                  </span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input class="form-control money" maxlength="8" type="money" id="taxa" name="taxa" value={$taxa}
                    return false;">

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
        decimal: ".",
        thousands: ".",
        allowNegative: true,
        allowZero: true
      });
    });
</script>