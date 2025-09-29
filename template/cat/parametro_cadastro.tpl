<script type="text/javascript" src="{$pathJs}/cat/s_parametro.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="cat">
      <input name=form type=hidden value="parametro">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">

              <h2>CAT - Par&acirc;metro -
                {if $subMenu eq "cadastrar"}
                  Cadastro
                {else}
                  Altera&ccedil;&atilde;o
                {/if}
                {include file="../bib/msg.tpl"}
              </h2>
              {if $subMenu neq "cadastrar"}
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <input id="id" name="id" type="text" class="form-control col-md-7 col-xs-12" readonly maxlength="11"
                    title="ID" value={$id}>
                </div>
              {/if}

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('parametro');">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('parametro');">
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



              <div class="row col-md-12 col-sm-12 col-xs-12">

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="situacaoinclusao">Situação inclusão</label>
                  <select name="situacaoinclusao" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$situacaoinclusao output=$situacao_names}
                  </select>
                </div>



                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitagatendimento">Situação Agendamento</label>
                  <select name="sitagatendimento" class="form-control input-sm">
                    {html_options values=$sitagatendimento_ids selected=$sitagatendimento output=$sitagatendimento_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitematendimento">Situação Atendimento</label>
                  <select name="sitematendimento" class="form-control input-sm">
                    {html_options values=$sitematendimento_ids selected=$sitematendimento output=$sitematendimento_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitsolicitarpeca">Situação Solicita Peças</label>
                  <select name="sitsolicitarpeca" class="form-control input-sm">
                    {html_options values=$sitsolicitarpeca_ids selected=$sitsolicitarpeca output=$sitsolicitarpeca_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitagpeca">Situação Agendamento Peças</label>
                  <select name="sitagpeca" class="form-control input-sm">
                    {html_options values=$sitagpeca_ids selected=$sitagpeca output=$sitagpeca_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitpecarecebida">Situação Peça Recebida</label>
                  <select name="sitpecarecebida" class="form-control input-sm">
                    {html_options values=$sitpecarecebida_ids selected=$sitpecarecebida output=$sitpecarecebida_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitaporcamento">Situação Apontamento Orçamento</label>
                  <select name="sitaporcamento" class="form-control input-sm">
                    {html_options values=$sitaporcamento_ids selected=$sitaporcamento output=$sitaporcamento_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="sitfinalizado">Situação Finalizado</label>
                  <select name="sitfinalizado" class="form-control input-sm">
                    {html_options values=$sitfinalizado_ids selected=$sitfinalizado output=$sitfinalizado_names}
                  </select>
                </div>
              </div>

              <!-- 
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="localatendimento">Local Atendimento</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input  id="localatendimento" name="localatendimento" type="text" required="required" 
                                  class="form-control col-md-7 col-xs-12" maxlength="1"
                                  tittle="Preencha este campo com numeros, até 1 caractere." 
                                  placeholder="Local atendimento"  value={$localatendimento}>
                        </div>
                      </div>
                      *}


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipointervencao">Tipo Intervenção</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea id="tipointervencao" name="tipointervencao" type="text" required="required" 
                                  class="form-control col-md-7 col-xs-12" maxlength="1"
                                  tittle="Preencha este campo com numeros, até 1 caractere." 
                                  placeholder="Tipo intervenção"  value={$tipointervencao}></textarea>
                        </div>
                      </div>
                      *}


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipodoccobranca">Tipo de cobranca</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input  id="tipodoccobranca" name="tipodoccobranca" type="text" required="required" 
                                  class="form-control col-md-7 col-xs-12" 
                                  tittle="Preencha este campo com letras ou numeros, até 15 caracteres." 
                                  placeholder="Situacao do atendimento do Parametro."  value={$tipodoccobranca}>
                        </div>
                      </div>
                      -->

              <div class="row col-md-12 col-sm-12 col-xs-12">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="msgatendimento">Mensagem Atendimento</label>
                  <textarea class="resizable_textarea form-control" id="msgatendimento" name="msgatendimento" rows="3"
                    onload="javascript:tipoLancamento();">{$msgatendimento}</textarea>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label class="control-label" for="msgorcamento">Mensagem Orcamento</label>
                  <textarea class="resizable_textarea form-control" id="msgorcamento" name="msgorcamento" rows="3"
                    onload="javascript:tipoLancamento();">{$msgorcamento}</textarea>
                </div>

              </div>

              <div class="row col-md-12 col-sm-12 col-xs-12">

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="condpgto">Condicao de pagamento</label>
                  <select name="condpgto" class="form-control input-sm">
                    {html_options values=$condpgto_ids selected=$condpgto output=$condpgto_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="conta">Conta</label>
                  <select name="conta" class="form-control input-sm">
                    {html_options values=$conta_ids selected=$conta output=$conta_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="genero">Genero</label>
                  <select name="genero" class="form-control input-sm">
                    {html_options values=$genero_ids selected=$genero output=$genero_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <label class="control-label" for="centrocusto">Centro de Custo</label>
                  <select name="centrocusto" class="form-control input-sm">
                    {html_options values=$centrocusto_ids selected=$centrocusto output=$centrocusto_names}
                  </select>
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
  <style>
    .form-control:focus {
      border-color: #159ce4;
      transition: all 0.7s ease;
    }

    #id {
      height: 28px;
      width: 38px;
      text-align: center;
    }

    .form-control,
    .x_panel {
      border-radius: 5px;
    }
</style>