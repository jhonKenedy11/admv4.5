<style type="text/css">
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type="number"] {
    -moz-appearance: textfield;
  }

  .form-control {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_fin.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script>
  $('.money').mask('000.000.000.000.000,00', { reverse: true });
  $(".money").change(function() {
    $("#value").html($(this).val().replace(/\D/g, ''))
  })
  $('.money').on('keyUp', function() {
    if ($(this).val().length > 3) {
      mascara = '####00,00';
    } else {
      mascara = '####0,00';
    }

    $('.money').mask(mascara, { reverse: true });
  });
</script>
<script type="text/javascript" src="{$pathJs}/fin/s_fin.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="fin">
      <input name=form type=hidden value="banco">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  Contas Banc&aacute;rias - Cadastro
                {else}
                  Contas Banc&aacute;rias - Altera&ccedil;&atilde;o
                {/if}
              </h2>
              {include file="../bib/msg.tpl"}

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('conta_banco');">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('conta_banco');">
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

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">C&oacute;digo <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="id" name="id" type="text" required="required" maxlength="6"
                    class="form-control col-md-7 col-xs-12" readOnly placeholder="C&oacute;digo interno de controle."
                    value={$id}>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Nome Interno <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="nomeInterno" name="nomeInterno" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="30"
                    placeholder="Nome que a conta &eacute; conhecida internamente na Empresa." value={$nomeInterno}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeContaBanco">Nome Conta <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="nomeContaBanco" name="nomeContaBanco" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="30" placeholder="Nome da conta no Banco."
                    value={$nomeContaBanco}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Banco <span
                    class="required">*</span>
                </label>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <select class="form-control" name="banco" id="banco">
                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agencia">Ag&ecirc;ncia <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="agencia" name="agencia" type="number" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="6"
                    title="Digite o código da agência sem o digito verificador."
                    onKeyPress="if(this.value.length==6) return false;" value={$agencia}>
                </div>
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="contaCorrente">C&oacute;digo Conta <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="contaCorrente" name="contaCorrente" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="15"
                    title="Conta Corrente no Banco, utilize o formato 99999-9." placeholder="99999-9"
                    value={$contaCorrente}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contato">Contato <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="contato" name="contato" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="15"
                    placeholder="Digite o Nome do Contato no Banco." value={$contato}>
                </div>
              </div>
              <span class="section"></span>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <select class="form-control" name="situacao" id="situacao">
                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                  </select>
                </div>
                <!-- -->
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="UltimoNossoNro"><span
                    class="required"></span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="UltimoNossoNro" name="UltimoNossoNro" type="text" readOnly
                    class="form-control col-md-7 col-xs-12" value={$UltimoNossoNro}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descontoBonificacao">Desconto <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="descontoBonificacao" name="descontoBonificacao" type="text" required="required"
                    class="form-control money col-md-6 col-xs-12"
                    placeholder="Digite a alíquota para pagamento antes do vencimento." value={$descontoBonificacao}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="multa">Multa <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="multa" name="multa" type="text" required="required"
                    class="form-control money col-md-7 col-xs-12" value={$multa}>
                </div>
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="juros">Juros <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="juros" name="juros" type="text" required="required"
                    class="form-control money col-md-7 col-xs-12" value={$juros}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descontoBonificacao">Num Cobrança Banco
                  <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="numNoBanco" name="numNoBanco" type="text" required="required"
                    class="form-control col-md-6 col-xs-12" maxlength="20"
                    placeholder="Digite o numero de identificação de cobrança no Banco." value={$numNoBanco}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="diaProtesto">Dia(s) Protesto</label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="diaProtesto" name="diaProtesto" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="6" value={$diaProtesto}>
                </div>
                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="carteiraCobranca">Carteira Cobrança <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <input id="carteiraCobranca" name="carteiraCobranca" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" maxlength="4" value={$carteiraCobranca}>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="msgBoleto">Mensagem Boleto</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea class="resizable_textarea form-control col-md-6 col-xs-12" id="msgBoleto" name="msgBoleto"
                    rows="3">{$msgBoleto}</textarea>
                </div>
              </div>
              <div class="form-group">

              </div>


              <div class="ln_solid"></div>

            </div>
          </div>
        </div>
      </div>
    </form>

  </div>

{include file="template/form.inc"}