<style>
    .form-control,
    .x_panel {
      border-radius: 5px;
}
</style>
<!-- <script type="text/javascript" src="{$pathJs}/crm/s_classe.js"> </script> -->
<script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="crm">
      <input name=form type=hidden value="classe">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  Classe - Cadastro
                {else}
                  Classe - Altera&ccedil;&atilde;o
                {/if}
              </h2>
              {include file="../bib/msg.tpl"}
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" id="btnSubmit"
                    onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
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

              <form class="container" novalidate="" action="/echo" method="POST" id="myForm">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Classe</label>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <input class="form-control" type="text" maxlength="2" required="true" id="id"
                      title="Preencha este campo com letras ou numeros, até 4 caracteres." name="id"
                      placeholder="Digite a Classe." value={$id}>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descricao">Descri&ccedil;&atilde;o
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input class="form-control" type="text" maxlength="15" required="true" id="descricao"
                      tittle="Preencha este campo com letras ou numeros, até 15 caracteres." name="descricao"
                      placeholder="Digite a descrição da Atividade." value={$descricao}>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bloqueado">Bloqueado
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="checkbox" class="js-switch" id="bloqueado" name="bloqueado" {if $bloqueado eq 'S'}
                      checked {/if} value="{$bloqueado}" />
                  </div>
                </div>
                <div class="ln_solid"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>

  {include file="template/form.inc"}