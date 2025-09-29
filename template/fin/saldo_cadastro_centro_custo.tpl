<script type="text/javascript" src="{$pathJs}/fin/s_saldo_centro_custo.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="clearfix"></div>

      <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
        ACTION="{$SCRIPT_NAME}" METHOD="post">
        <input name=mod type=hidden value="fin">
        <input name=form type=hidden value="saldo_centro_custo">
        <input name=submenu type=hidden value={$subMenu}>
        <input name=letra type=hidden value={$letra}>
        <input name=id type=hidden value={$id}>
        <input name=mesSaldo type=hidden value={$mesSaldo}>
        <input name=anoSaldo type=hidden value={$anoSaldo}>
        <input name=contaPes type=hidden value={$contaPes}>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Saldo Banc&aacute;rio Centro Custo -
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
                            <div class="alert alert-success" role="alert">{$mensagem}</div>
                          </div>
                        </div>
                      </div>
                    {elseif $tipoMsg eq 'alerta'}
                      <div class="row">
                        <div class="col-lg-12 text-left">
                          <div>
                            <div class="alert alert-danger" role="alert">{$mensagem}</div>
                          </div>
                        </div>
                      </div>
                    {/if}

                  {/if}
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                  <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                      <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                        Confirmar</span></button>
                  </li>
                  <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
                      <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Cancelar</span></button>
                  </li>
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                        class="fa fa-wrench"></i></a>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <br />

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="conta">Conta <span
                      class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <SELECT name="centrocusto" class="form-control">
                      {html_options values=$centrocusto_ids output=$centrocusto_names selected=$centrocusto_id}
                    </SELECT>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="data">Data <span
                      class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="data" name="data" type="text" required="required" class="form-control col-md-7 col-xs-12"
                      tittle="Selecione a Data." placeholder="Data do Saldo a ser cadastrado." value={$data}>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="saldo">Saldo <span
                      class="required">*</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="saldo" name="saldo" type="text" required="required"
                      class="form-control col-md-7 col-xs-12 money" title="Valor do Saldo da Conta."
                      placeholder="Valor do Saldo da Conta do Final do Dia."
                      value="{if $saldo != ''}{$saldo|replace:'.':''|replace:',':'.'|number_format:2:",":"."}{else}0,00{/if}">
                  </div>
                </div>
                <div class="ln_solid"></div>

              </div>
            </div>
          </div>
        </div>
      </form>

    </div>

  </div>

</div>


{include file="template/form.inc"}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script>
  $(".money").maskMoney({
    decimal: ",",
    thousands: ".",
    allowNegative: true,
    allowZero: true,
    prefix: "R$ ",
    affixesStay: false
  });
</script>