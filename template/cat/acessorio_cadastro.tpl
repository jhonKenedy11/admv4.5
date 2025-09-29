<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/cat/s_acessorio.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="cat">
      <input name=form type=hidden value="acessorio">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Acessórios
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
                    placeholder="Digite o descricao do Acessório." value={$descricao}>
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