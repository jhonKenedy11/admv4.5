<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_servico.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="cat">
      <input name=form type=hidden value="servico">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>
      <input name=origem type=hidden value="{$origem}">
      <input name=opcao type=hidden value="{$opcao}">

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  Servi&ccedil;os -  Cadastro
                {else}
                  Servi&ccedil;os - Altera&ccedil;&atilde;o
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descricao">Descrição <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="descricao" name="descricao" type="text" required="required"
                    class="form-control col-md-7 col-xs-12"
                    tittle="Preencha este campo com letras ou numeros, até 50 caracteres."
                    placeholder="Digite o descricao do Serviço." value={$descricao}>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unidade">Unidade <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="unidade" name="unidade" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" tittle="Preencha este campo com letras, até 3 caracteres."
                    placeholder="Digite a unidade." value={$unidade}>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quantidade">Quantidade <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="quantidade" name="quantidade" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" tittle="Preencha este campo com letras, até 3 caracteres."
                    placeholder="Digite a quantidade." value={$quantidade}>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="valorunitario">Valor Unitário <span
                    class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="valorunitario" name="valorunitario" type="text" required="required"
                    class="form-control col-md-7 col-xs-12" tittle="Preencha este campo com letras, até 3 caracteres."
                    placeholder="Digite o valor da unidade." value={$valorunitario}>
                </div>
              </div>


              <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div>
                    <label class="switch-status-label" style="margin-top: 7px;">
                      <input type="hidden" name="status" value="0" />
                      <input type="checkbox" class="js-switch" name="status" value="1" {if $status eq '1'}checked{/if} />
                      <span class="status-label-text">{if $status eq '1'}Ativo{else}Inativo{/if}</span>
                    </label>
                  </div>
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
 