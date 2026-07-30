<style>
  .crm-email-only .x_panel,
  .crm-email-only .form-control {
    border-radius: 6px !important;
  }
  .crm-email-only .input-group {
    margin-bottom: 8px;
  }
  .crm-email-only .editor-wrapper {
    min-height: 260px;
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    overflow: auto;
  }
  .crm-email-only .header-title {
    margin: 0 0 14px 0;
    color: #73879C;
  }
  .crm-email-only .section-title {
    font-size: 13px;
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 8px;
  }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/crm/s_crm_contas_acompanhamento.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<div class="right_col crm-email-only" role="main">
  <form id="lancamento" name="lancamento" method="post" class="form-horizontal form-label-left">
    <input name="mod" type="hidden" value="crm">
    <input name="form" type="hidden" value="crm_contas_acompanhamento">
    <input name="submenu" type="hidden" value="emailAcompanhamento">
    <input name="opcao" type="hidden" value="imprimir">
    <input name="id" id="id" type="hidden" value="{$id}">
    <input name="pessoa" id="pessoa" type="hidden" value="{$pessoa}">
    <input name="pessoaNome" type="hidden" value="{$pessoaNome}">
    <input name="resultContato" id="resultContato" type="hidden" value="">
    <input name="dashboard_origem" type="hidden" value="{$dashboard_origem}">
    <input name="email_id" type="hidden" value="{$email_id}">

    <h3 class="header-title">E-mail do acompanhamento</h3>

    <div class="x_panel">
      <div class="x_content">
        <div class="row">
          <div class="col-md-9">
            <div class="input-group">
              <span class="input-group-addon">Remetente</span>
              <input type="text" class="form-control" readonly id="email_remetente" value="{$email_remetente}">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Destinatário(s)</span>
              <input type="text" class="form-control" id="email_destinatario" value="{$email_destinatario}">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Assunto</span>
              <input type="text" class="form-control" id="email_assunto" value="{$email_assunto}">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Anexo</span>
              <input type="text" class="form-control" readonly id="email_anexo" value="{$email_anexo}">
            </div>

            <div id="alerts"></div>
            <div id="editor-one" class="editor-wrapper">{$editorOne}</div>
            <textarea name="descr" id="descr" style="display:none;"></textarea>
          </div>

          <div class="col-md-3">
            <h4 class="section-title">Contatos do cliente</h4>
            <table class="table table-striped table-condensed">
              <tbody>
                {section name=h loop=$contatos_cliente}
                  <tr>
                    <td style="width:30px;">
                      <input class="form-check-input trContatosCheck" value="{$contatos_cliente[h].EMAIL}" type="checkbox" onclick="updateInputDestinatario()">
                    </td>
                    <td>{$contatos_cliente[h].NOME_CONTATO}</td>
                  </tr>
                {/section}
              </tbody>
            </table>

            <h4 class="section-title">Anexos</h4>
            <table class="table table-striped table-condensed">
              <tbody>
                <tr>
                  <td style="width:30px;">
                    <input class="form-check-input anexoEmail" type="checkbox" value="/file/anexo_email/tractor_venda_mais.pdf" id="venda_mais.pdf" {if $anexo1 eq 'true'} checked {/if} onclick="updateInputAnexo()">
                  </td>
                  <td>venda_mais.pdf</td>
                </tr>
              </tbody>
            </table>

            <h4 class="section-title">Templates</h4>
            <table class="table table-striped table-condensed">
              <tbody>
                {if $templates_email|@count gt 0}
                  <tr>
                    <td style="width:30px;">
                      <input class="form-check-input templateEmail" type="radio" name="crm_template_email"
                        id="crm_template_none" value="" checked="checked" onclick="verificaCheckTemplate()">
                    </td>
                    <td><em>Nenhum</em></td>
                  </tr>
                  {section name=t loop=$templates_email}
                    <tr class="crm-template-row" data-template-id="{$templates_email[t].ID|escape:'html'}" data-template-descricao="{$templates_email[t].DESCRICAO|escape:'html'}">
                      <td style="width:30px;">
                        <input class="form-check-input templateEmail" type="radio" name="crm_template_email"
                          id="tmpl_{$smarty.section.t.index}"
                          value="{$templates_email[t].ID|escape:'html'}"
                          onclick="verificaCheckTemplate()">
                      </td>
                      <td>{$templates_email[t].DESCRICAO|escape:'html'}</td>
                    </tr>
                  {/section}
                {else}
                  <tr>
                    <td colspan="2"><small>Nenhum template cadastrado.</small></td>
                  </tr>
                {/if}
              </tbody>
            </table>
            <div class="crm-templates-hidden" style="display:none" aria-hidden="true">
              {section name=ht loop=$templates_email}
              <input type="hidden" class="crmTmplById" data-template-id="{$templates_email[ht].ID|escape:'html'}" data-template-descricao="{$templates_email[ht].DESCRICAO|escape:'html'}" value="" />
              {/section}
            </div>
          </div>
        </div>

        <div class="ln_solid"></div>
        <div class="text-right">
          <button type="button" class="btn btn-default" onclick="window.close();">Fechar</button>
          <button type="button" class="btn btn-success" onclick="savedEmail(event)">Salvar</button>
          <button type="button" class="btn btn-info" onclick="sendEmail(event)">Enviar</button>
        </div>
      </div>
    </div>
  </form>
</div>

{include file="template/form.inc"}
