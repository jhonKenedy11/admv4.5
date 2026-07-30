<style type="text/css">
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type="number"] {
    -moz-appearance: textfield;
  }

  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .radio-group {
    display: flex;
    gap: 15px;
  }

  .radio-group label {
    display: flex;
    align-items: center;
    white-space: nowrap;
    font-weight: normal !important;
  }

  .x_title h2 {
    color: #38478b;
  }

  .x_title h2 i {
    color: #283468;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2d69;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #95a9fa;
    position: relative;
  }

  .section-title:before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 5%;
    height: 2px;
    background: #1f2d69;
  }

  .section-title i {
    color: #2d3e8a;
    margin-right: 8px;
  }

  .param-box {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
  }

  .param-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e9e4ff;
    transition: all 0.3s;
  }

  .param-item:last-child {
    margin-bottom: 0;
  }

  .param-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .param-item .param-label {
    margin: 0;
    font-weight: 500;
    color: #4b5563;
    flex: 1;
  }

  .param-item .param-label i {
    color: #1f2d69;
    margin-right: 8px;
  }

  .param-item .radio-group {
    display: flex;
    gap: 20px;
  }

  .tributos-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  .tributos-box label {
    color: #4b5563;
    font-weight: 500;
  }

  .tributo-item {
    background: linear-gradient(to bottom, rgb(243, 241, 241) 0%, rgb(216, 216, 216) 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  .tributo-item:last-child {
    margin-bottom: 0;
  }

  .tributo-item label i {
    color: #1f2d69;
  }

  .input-with-icon {
    position: relative;
  }

  .input-icon-right {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #1f2d69;
  }

  .form-control.has-icon-right {
    padding-right: 35px;
  }

  .form-control:focus,
  .form-control:focus-within {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .info-card {
    background: rgb(255, 248, 248);
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  select.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  input.form-control,
  textarea.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  input.form-control:focus,
  textarea.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .x_panel {
    border-top: 4px solid #1f2d69;
    box-shadow: 0 4px 20px rgba(0, 35, 192, 0.1);
  }

  .control-label {
    color: #414852;
    font-weight: 400;
    font-size: 14px;
  }

  .swal-modal {
    width: 550px !important;
  }

  .param-note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  table {
    border-spacing: 0;
    border-collapse: none !important;
  }

  .table-bordered>thead>tr>th {
    border-radius: 7px !important;
    padding: 5px !important;
  }

  .x_panel,
  [name=datatable-buttons_length],
  [type=search] {
    border-radius: 5px;
  }

  .param-tip {
    color: #9ca3af;
    margin-left: 4px;
    cursor: help;
    font-size: 13px;
    vertical-align: middle;
  }
  .param-tip:hover { color: #1f2d69; }
</style>
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
      <input name=submenu type=hidden value={$submenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">

              <h2>CAT - Par&acirc;metro -
                {if $submenu eq "cadastro"}
                  Cadastro
                {else}
                  Altera&ccedil;&atilde;o
                {/if}
                {include file="../bib/msg.tpl"}
              </h2>
              {if $submenu neq "cadastro"}
                <div class="col-md-1 col-sm-1 col-xs-1">
                  <input id="id" name="id" type="text" class="form-control col-md-7 col-xs-12" readonly maxlength="11"
                    title="ID" value="{$id}">
                </div>
              {/if}

              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
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

              <div class="tributos-box">
                <h4 class="section-title"><i class="fa fa-tasks"></i> Situa&ccedil;&otilde;es do Atendimento</h4>
                <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="situacaoinclusao">Situa&ccedil;&atilde;o inclus&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação ao criar um novo atendimento."></i></label>
                  <select name="situacaoinclusao" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITUACAOINCLUSAO|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>



                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitagatendimento">Situa&ccedil;&atilde;o Agendamento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação quando o atendimento é agendado."></i></label>
                  <select name="sitagatendimento" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITAGATENDIMENTO|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitematendimento">Situa&ccedil;&atilde;o Atendimento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação enquanto o atendimento está em andamento."></i></label>
                  <select name="sitematendimento" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITEMATENDIMENTO|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitsolicitarpeca">Situa&ccedil;&atilde;o Solicita Pe&ccedil;as <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação ao solicitar peças para o atendimento."></i></label>
                  <select name="sitsolicitarpeca" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITSOLICITARPECA|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitagpeca">Situa&ccedil;&atilde;o Agendamento Pe&ccedil;as <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação ao agendar a entrega de peças."></i></label>
                  <select name="sitagpeca" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITAGPECA|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitpecarecebida">Situa&ccedil;&atilde;o Pe&ccedil;a Recebida <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação quando as peças são recebidas."></i></label>
                  <select name="sitpecarecebida" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITPECARECEBIDA|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitaporcamento">Situa&ccedil;&atilde;o Apontamento Or&ccedil;amento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação ao registrar o orçamento do atendimento."></i></label>
                  <select name="sitaporcamento" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITAPORCAMENTO|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="sitfinalizado">Situa&ccedil;&atilde;o Finalizado <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação ao encerrar o atendimento."></i></label>
                  <select name="sitfinalizado" class="form-control input-sm">
                    {html_options values=$situacao_ids selected=$dados.SITFINALIZADO|default:0 output=$situacao_names}
                  </select>
                  </div>
                </div>
                </div>
              </div>

              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-comment"></i> Mensagens</h4>
                <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="msgatendimento">Mensagem Atendimento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Mensagem padrão exibida no atendimento."></i></label>
                  <textarea class="form-control" id="msgatendimento" name="msgatendimento" rows="3">{$dados.MSGATENDIMENTO}</textarea>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="msgorcamento">Mensagem Or&ccedil;amento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Mensagem padrão exibida no orçamento."></i></label>
                  <textarea class="form-control" id="msgorcamento" name="msgorcamento" rows="3">{$dados.MSGORCAMENTO}</textarea>
                  </div>
                </div>
                </div>
              </div>

              <div class="tributos-box">
                <h4 class="section-title"><i class="fa fa-money"></i> Integra&ccedil;&atilde;o Financeira</h4>
                <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="condpgto">Condi&ccedil;&atilde;o de pagamento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Condição de pagamento padrão do atendimento."></i></label>
                  <select name="condpgto" class="form-control input-sm">
                    {html_options values=$condpgto_ids selected=$dados.CONDPGTO|default:0 output=$condpgto_names}
                  </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="conta">Conta <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Conta financeira padrão do atendimento."></i></label>
                  <select name="conta" class="form-control input-sm">
                    {html_options values=$conta_ids selected=$dados.CONTA|default:0 output=$conta_names}
                  </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="genero">G&ecirc;nero <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Gênero financeiro dos lançamentos gerados."></i></label>
                  <select name="genero" class="form-control input-sm">
                    {html_options values=$genero_ids selected=$dados.GENERO|default:0 output=$genero_names}
                  </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="tributo-item">
                  <label class="control-label" for="centrocusto">Centro de Custo <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Centro de custo padrão do atendimento."></i></label>
                  <select name="centrocusto" class="form-control input-sm">
                    {html_options values=$centrocusto_ids selected=$dados.CENTROCUSTO|default:0 output=$centrocusto_names}
                  </select>
                  </div>
                </div>
                </div>
              </div>

              <div class="param-box">
                <h4 class="section-title"><i class="fa fa-sliders"></i> Configura&ccedil;&otilde;es Gerais</h4>
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label class="control-label" for="localatendimento">Local Atendimento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Código do local padrão do atendimento."></i></label>
                      <input id="localatendimento" name="localatendimento" type="text" class="form-control input-sm" maxlength="1" value="{$dados.LOCALATENDIMENTO}">
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label class="control-label" for="tipointervencao">Tipo Interven&ccedil;&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Tipo de intervenção padrão no atendimento."></i></label>
                      <input id="tipointervencao" name="tipointervencao" type="text" class="form-control input-sm" maxlength="1" value="{$dados.TIPOINTERVENCAO}">
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label class="control-label" for="controleestoque">Controle Estoque <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Define como o estoque é baixado no atendimento."></i></label>
                      <input id="controleestoque" name="controleestoque" type="number" class="form-control input-sm" value="{$dados.CONTROLEESTOQUE}">
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label class="control-label" for="tipodoccobranca">Tipo Doc. Cobran&ccedil;a <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Tipo de documento usado na cobrança do atendimento."></i></label>
                      <input id="tipodoccobranca" name="tipodoccobranca" type="number" class="form-control input-sm" value="{$dados.TIPODOCCOBRANCA}">
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>

  </div>

  {include file="template/form.inc"}
  <style>
    #id { height: 28px; width: 38px; text-align: center; }
  </style>
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip({ container: 'body' });

      {if $swalText}
      Swal.fire({
        icon: '{$swalIcon}',
        title: '{$swalTitle|escape:'javascript'}',
        text: '{$swalText|escape:'javascript'}',
        width: 510,
        {if $swalAutoClose}
        timer: 3000,
        showConfirmButton: false
        {else}
        confirmButtonText: 'OK'
        {/if}
      });
      {/if}
    });
  </script>