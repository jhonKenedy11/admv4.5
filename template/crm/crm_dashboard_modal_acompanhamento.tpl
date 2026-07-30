{* Modal de acompanhamento no dashboard CRM — incluir via crm_dashboard.tpl *}
<style>
  /* Escopo .crm-acomp-modal: só layout e hierarquia; cores vêm do tema */
  .crm-acomp-modal .crm-acomp-modal-dialog {
    width: auto;
    max-width: 700px;
    margin: 24px auto;
  }
  @media (max-width: 700px) {
    .crm-acomp-modal .crm-acomp-modal-dialog {
      max-width: calc(100% - 24px);
      margin: 12px;
    }
  }
  .crm-acomp-modal .crm-acomp-modal-body {
    max-height: min(72vh, 620px);
    overflow: auto;
    padding-top: 12px;
    padding-bottom: 12px;
  }
  .crm-acomp-modal .crm-acomp-modal-header-main {
    padding-right: 28px;
  }
  .crm-acomp-modal .crm-acomp-modal-sub {
    margin-top: 4px;
    line-height: 1.35;
  }
  .crm-acomp-modal .modal-title .fa {
    margin-right: 6px;
    opacity: 0.9;
  }
  .crm-acomp-modal .panel {
    margin-bottom: 12px;
    box-shadow: none;
  }
  .crm-acomp-modal .panel:last-of-type {
    margin-bottom: 0;
  }
  .crm-acomp-modal .panel-heading {
    padding: 8px 12px;
  }
  .crm-acomp-modal .panel-heading .fa {
    margin-right: 6px;
    opacity: 0.85;
  }
  .crm-acomp-modal .panel-body {
    padding: 10px 12px 8px;
  }
  .crm-acomp-modal .form-group {
    margin-bottom: 10px;
  }
  .crm-acomp-modal .crm-acomp-cliente-nome {
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 500;
  }
  .crm-acomp-modal .crm-acomp-footer-actions {
    text-align: right;
  }
  .crm-acomp-modal .crm-acomp-footer-actions .btn + .btn {
    margin-left: 6px;
  }
  .crm-acomp-modal .btn .fa {
    margin-right: 4px;
  }
</style>

<div class="modal fade crm-acomp-modal" id="modalNovoAcompanhamento" role="dialog" data-backdrop="static" aria-labelledby="modalAcompTitulo" aria-hidden="true">
  <div class="modal-dialog crm-acomp-modal-dialog">
    <div class="modal-content">
      <div class="modal-header clearfix">
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <div class="crm-acomp-modal-header-main">
          <h5 class="modal-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span id="modalAcompTitulo">Acompanhamento</span></h5>
          <div id="crmDashAcompSubtitulo" class="small text-muted crm-acomp-modal-sub"></div>
        </div>
      </div>
      <div class="modal-body crm-acomp-modal-body">
        <form id="crmDashAcompForm" name="lancamento" method="post" action="{$SCRIPT_NAME}" autocomplete="off">
          <input type="hidden" name="mod" value="crm">
          <input type="hidden" name="form" value="contas_acompanhamento">
          <input type="hidden" name="submenu" value="cadastrar">
          <input type="hidden" name="opcao" value="imprimir">
          <input type="hidden" name="id" id="crmDashAcompId" value="" data-acomp-col="ID">
          <input type="hidden" name="pessoa" id="crmDashPessoa" value="" data-acomp-col="PESSOA">
          <input type="hidden" name="pessoaNome" id="crmDashPessoaNome" value="">
          <input type="hidden" name="vendedorAcomp" id="crmDashVendedorAcomp" value="{$crm_dash_vendedor_id}" data-default="{$crm_dash_vendedor_id}" data-acomp-col="USRVENDEDOR">
          <input type="hidden" name="horaContato" value="">
          <input type="hidden" name="fornecedor" value="">
          <input type="hidden" name="mensagem_retorno_contato" value="">
          <input type="hidden" name="codigo_retorno_contato" value="">
          <input type="hidden" name="dashboard_origem" value="dashboard_crm">
          <input type="hidden" name="data_previous" value="">

          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-sliders" aria-hidden="true"></i><strong>Resumo</strong></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashAcompAcao">Ação</label>
                  <select class="form-control" name="acao" id="crmDashAcompAcao">
                    {html_options values=$acao_ids output=$acao_names selected=$acao_id}
                  </select>
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashAcompStatus">Status</label>
                  <select class="form-control" name="status" id="crmDashAcompStatus">
                    {html_options values=$acomp_status_ids output=$acomp_status_names selected=$acomp_status_selected}
                  </select>
                </div>
                <div class="col-md-4 col-sm-12 form-group">
                  <label for="crmDashAcompStatusCli">Status cliente</label>
                  <input type="hidden" name="status_cli" value="{$crm_modal_classe_sel}">
                  <select class="form-control" id="crmDashAcompStatusCli" disabled tabindex="-1" title="Somente leitura" aria-readonly="true">
                    {html_options values=$classe_ids output=$classe_names selected=$crm_modal_classe_sel}
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-user" aria-hidden="true"></i><strong>Cliente</strong></div>
            <div class="panel-body">
              <div class="well well-sm" style="margin-bottom:0;">
                <p class="form-control-static crm-acomp-cliente-nome" id="crmDashClienteNomeMostra">—</p>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-phone" aria-hidden="true"></i><strong>Contato</strong></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashDataContato">Data contato</label>
                  <input class="form-control" type="text" name="dataContato" id="crmDashDataContato" placeholder="dd/mm/aaaa hh:mm" value="" data-inputmask="'mask': '99/99/9999 99:99'">
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashIdPedido">Cotação / pedido</label>
                  <input class="form-control" type="text" name="idPedido" id="crmDashIdPedido" placeholder="Número" value="">
                </div>
                <div class="col-md-12 form-group">
                  <label for="crmDashResultContato">Acompanhamento (descrição)</label>
                  <textarea class="form-control" rows="3" name="resultContato" id="crmDashResultContato" placeholder="Descreva o contato realizado."></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-calendar" aria-hidden="true"></i><strong>Próximo contato e logística</strong></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashProximoContato">Próximo contato</label>
                  <input class="form-control" type="text" name="proximoContato" id="crmDashProximoContato" placeholder="dd/mm/aaaa hh:mm" value="" data-inputmask="'mask': '99/99/9999 99:99'">
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashAcaoNovoAcomp">Ação (planejamento)</label>
                  <select class="form-control" name="acaoNovoAcomp" id="crmDashAcaoNovoAcomp">
                    {html_options values=$acao_ids output=$acao_names selected=$acao_id}
                  </select>
                </div>
                <div class="col-md-12 form-group">
                  <label for="crmDashDescNovoAcomp">Descrição próximo acompanhamento</label>
                  <textarea class="form-control" rows="2" name="descNovoAcomp" id="crmDashDescNovoAcomp" placeholder="Opcional — fluxo de novo agendamento na tela cheia."></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashVeiculo">Veículo</label>
                  <select class="form-control" name="veiculo" id="crmDashVeiculo">
                    {html_options values=$acomp_veiculo_ids output=$acomp_veiculo_names selected=$acomp_veiculo_selected}
                  </select>
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashOrigem">Origem</label>
                  <input class="form-control" type="text" name="origem" id="crmDashOrigem" value="">
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashDestino">Destino</label>
                  <input class="form-control" type="text" name="destino" id="crmDashDestino" value="">
                </div>
                <div class="col-md-4 col-sm-6 form-group">
                  <label for="crmDashKm">KM</label>
                  <input class="form-control" type="text" name="km" id="crmDashKm" value="" placeholder="0">
                </div>
              </div>
            </div>
          </div>

          <p class="help-block text-muted" style="margin-top:4px;margin-bottom:0;">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            Lista de contatos, e-mail e histórico detalhado permanecem na tela completa de acompanhamento.
          </p>
        </form>
      </div>
      <div class="modal-footer">
        <div class="crm-acomp-footer-actions">
          <button type="button" class="btn btn-info" onclick="crmDashAbrirEmailAcomp(('crmDashAcompId').value);"><i class="fa fa-envelope" aria-hidden="true"></i> E-mail</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnCrmDashSalvarAcomp" onclick="crmDashSalvarAcomp();"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        </div>
      </div>
    </div>
  </div>
</div>
