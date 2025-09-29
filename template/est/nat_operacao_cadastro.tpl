<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
  $(document).ready(function() {
    $(".money").maskMoney({
      decimal: ",",
      thousands: ".",
      allowZero: true
    });
  });
</script>
<script type="text/javascript" src="{$pathJs}/est/s_est.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="est">
      <input name=form type=hidden value="nat_operacao">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>
      <input name=idNatop type=hidden value={$idNatop}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  Natureza de Opera&ccedil;&atilde;o - Cadastro
                {else}
                  Natureza de Opera&ccedil;&atilde;o - Altera&ccedil;&atilde;o
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
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('nat_operacao');">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('nat_operacao');">
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


            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="natOperacao">Natureza Opera&ccedil;&atilde;o
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="natOperacao" name="natOperacao" type="text" required="required"
                  class="form-control col-md-7 col-xs-12"
                  tittle="Preencha este campo com letras ou numeros, até 15 caracteres."
                  placeholder="Descri&ccedil;&atilde;o Natureza de Opera&ccedil;&atilde;o." value={$natOperacao}>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipo">Tipo <span class="required">*</span>
              </label>
              <div class="col-md-3 col-sm-3 col-xs-6">
                <select class="form-control" name="tipo" id="tipo">
                  {html_options values=$tipoNatOp_ids selected=$tipoNatOp_id output=$tipoNatOp_names}
                </select>
              </div>
              <label class="control-label col-md-1 col-sm-1 col-xs-4" for="modeloNF">Modelo<span
                  class="required">*</span>
              </label>
              <div class="col-md-2 col-sm-2 col-xs-4">
                <input id="modeloNf" name="modeloNf" type="text" required="required"
                  class="form-control col-md-1 col-xs-2" tittle="Preencha este campo com modelo da nota fiscal."
                  placeholder="Modelo da nota fiscal" value={$modeloNf}>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="codFiscOrigem">C&oacute;digo Fiscal Origem
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="codFiscOrigem" name="codFiscOrigem" type="text" class="form-control col-md-7 col-xs-12"
                  placeholder="Digite o C&oacute;digo Fiscal Origem." value={$codFiscOrigem}>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Parametros
              </label>
              <label class="control-label col-md-3 col-sm-3 col-xs-6">Altera Pre&ccedil;os
                {html_radios class="flat" name="alteraPrecos" values=$boolean_ids output=$boolean_names selected=$alteraPrecos separator=""}
              </label>
              <label class="control-label col-md-3 col-sm-3 col-xs-6">Altera Quantidade
                {html_radios class="flat" name="alteraQuant" values=$boolean_ids output=$boolean_names selected=$alteraQuant separator=""}
              </label>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">
              </label>
              <label class="control-label col-md-3 col-sm-3 col-xs-6">Integra Financeiro
                {html_radios class="flat" name="integraFin" values=$boolean_ids output=$boolean_names selected=$integraFin separator=""}
              </label>
              <label class="control-label col-md-3 col-sm-3 col-xs-6">Posi&ccedil;&atilde;o Tributos
                {html_radios class="flat" name="posicaoTributos" values=$boolean_ids output=$boolean_names selected=$posicaoTributos separator=""}
              </label>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">
              </label>
              <label class="control-label col-md-3 col-sm-3 col-xs-6">NF Auto
                {html_radios class="flat" name="nfAuto" values=$boolean_ids output=$boolean_names selected=$nfAuto separator=""}
              </label>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-6">Simples Federal
              </label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <!--input id="tribSimples" name="tribSimples" type="text" data-inputmask="'mask': '999,99'" -->
                <input id="tribSimples" name="tribSimples" type="text" class="form-control  has-feedback-left money"
                  placeholder="Percentual de tributo do Simples Federal." value={$tribSimples}>
                <span class="form-control-feedback left" aria-hidden="true"><b>R$</b></span></b>

              </div>
              <label class="control-label col-md-2 col-sm-2 col-xs-6" for="modeloNF">Crédito Simples
              </label>
              <div class="col-md-2 col-sm-2 col-xs-4">
                <input id="percCreditoSimples" name="percCreditoSimples" type="text"
                  class="form-control  has-feedback-right money" placeholder="Percentual de tributo do Simples Federal."
                  value={$percCreditoSimples}>
                <span class="form-control-feedback right" aria-hidden="true"><b>%</b></span></b>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="obs">Observa&ccedil;&atilde;o Nota
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="2">{$obs}</textarea>
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