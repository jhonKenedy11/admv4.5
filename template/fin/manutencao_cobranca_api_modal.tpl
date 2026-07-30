
<style>
#modalCobrancaBancaria .modal-content {
    position: relative;
}
#modalCobrancaBancaria .dl-horizontal dt {
    width: 100px;
    text-align: right;
}
#modalCobrancaBancaria .dl-horizontal dd {
    margin-left: 120px;
}
/* Truncamento de campos longos (Linha Digitavel / Pix Copia e Cola) com botao copiar */
#modalCobrancaBancaria .copy-field-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: 120px;
    max-width: calc(100% - 120px);
}
#modalCobrancaBancaria .copy-field-text {
    flex: 1 1 auto;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: monospace;
    font-size: 12px;
    background: #f8f9fa;
    border: 1px solid #e1e8ed;
    border-radius: 4px;
    padding: 4px 8px;
    cursor: help;
}
#modalCobrancaBancaria .copy-field-btn {
    flex: 0 0 auto;
    padding: 4px 10px;
    font-size: 12px;
    line-height: 1.4;
}
#modalCobrancaBancaria .copy-field-btn.copied {
    background-color: #5cb85c;
    border-color: #4cae4c;
    color: #fff;
}
.form-control {
    border-radius: 5px !important;
}
/* Badge de situação: largura total da coluna e texto centralizado */
#modalCobrancaBancaria .cobranca-status-badge {
    display: block;
    width: 100%;
    text-align: center;
    font-size: 14px;
    padding: 7.8px 12px;
    white-space: normal;
    box-sizing: border-box;
}

:root {
    --primary-color: #4a90e2;
    --bg-card: #ffffff;
    --text-main: #2c3e50;
    --text-muted: #7f8c8d;
    --border-color: #e1e8ed;
    --input-bg: #f8f9fa;
    --success-color: #27ae60;

    /* ── Status de cobrança ── */
    --status-a_receber-bg:        #FAEEDA;
    --status-a_receber-color:     #633806;

    --status-recebido-bg:         #EAF3DE;
    --status-recebido-color:      #27500A;

    --status-atrasado-bg:         #FCEBEB;
    --status-atrasado-color:      #501313;

    --status-cancelado-bg:        #F1EFE8;
    --status-cancelado-color:     #444441;

    --status-expirado-bg:         #FAECE7;
    --status-expirado-color:      #4A1B0C;

    --status-marcado_recebido-bg:    #E1F5EE;
    --status-marcado_recebido-color: #085041;

    --status-falha_emissao-bg:    #FBEAF0;
    --status-falha_emissao-color: #4B1528;

    --status-em_processamento-bg:    #E6F1FB;
    --status-em_processamento-color: #0C447C;

    --status-protesto-bg:         #EEEDFE;
    --status-protesto-color:      #26215C;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 500;
    margin-left: 10px !important;
    margin-top: -8px !important;
}
.badge-status::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.7;
}

.s-a_receber        { background: var(--status-a_receber-bg);        color: var(--status-a_receber-color); }
.s-recebido         { background: var(--status-recebido-bg);         color: var(--status-recebido-color); }
.s-atrasado         { background: var(--status-atrasado-bg);         color: var(--status-atrasado-color); }
.s-cancelado        { background: var(--status-cancelado-bg);        color: var(--status-cancelado-color); }
.s-expirado         { background: var(--status-expirado-bg);         color: var(--status-expirado-color); }
.s-marcado_recebido { background: var(--status-marcado_recebido-bg); color: var(--status-marcado_recebido-color); }
.s-falha_emissao    { background: var(--status-falha_emissao-bg);    color: var(--status-falha_emissao-color); }
.s-em_processamento { background: var(--status-em_processamento-bg); color: var(--status-em_processamento-color); }
.s-protesto         { background: var(--status-protesto-bg);         color: var(--status-protesto-color); }

.dashboard-card {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 0px 24px 24px 24px;
    box-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
    font-family: 'Inter', -apple-system, sans-serif;
    max-width: 900px;
    margin: 8px 0px 0px 0px;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

/* Utilitários de Grid */
.full-width { grid-column: span 2; }
.mini { grid-column: span 1; }

.field-group label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}

.modern-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background-color: var(--input-bg);
    color: var(--text-main);
    font-size: 13px;
    transition: all 0.2s ease;
}

.modern-input[readonly] {
    cursor: default;
    font-weight: 500;
}

/* Destaque para o Valor */
.highlight-value .modern-input {
    background: #eef6ff;
    color: var(--primary-color);
    font-size: 15px;
    font-weight: 700;
    border-left: 4px solid var(--primary-color);
}

/* Conta e Dígito na mesma linha */
.input-composite {
    display: flex;
    align-items: center;
    gap: 8px;
}

.digit { width: 50px; text-align: center; }

/* Status Badges */
.badge {
    background: #919191;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 15px;
    font-weight: bold;
    text-align: left;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #f0fff4;
    color: #27ae60;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
}
.modal-body {
    padding: 0px 15px 15px 15px !important;
}
.separador {
    margin: 0 5px;
    color: #2c3e50;
}
.glyphicon-credit-card {
    color: #8d8c8c;
    margin-right: 10px;
    top: 4px !important;
}
</style>
{* Modal de Manutenção de Cobrança Bancária *}
<div class="modal fade" id="modalCobrancaBancaria" tabindex="-1" role="dialog" aria-labelledby="modalCobrancaBancariaLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalCobrancaBancariaLabel">
                    <i class="glyphicon glyphicon-credit-card"></i> Manutenção de Cobrança Bancária
                    <span class="badge pull-right" id="interno_situacao">Carregando...</span>
                </h4>
                
            </div>
            <div class="modal-body">

            <div class="dashboard-card">

                    <!-- inputs ocultos -->
                    <input type="hidden" id="id_lancamento" value="">
                    <input type="hidden" id="id_banco" value="">
                    <input type="hidden" id="id_tabela_api" value="">
            
                    <div class="grid-container">
                        <!-- Seção Principal -->
                        <div class="field-group full-width">
                            <label for="interno_nome_cliente">Nome do Cliente</label>
                            <input type="text" id="interno_nome_cliente" class="modern-input" readonly>
                        </div>
                
                        <div class="field-group">
                            <label for="interno_cnpj_cpf_cliente">CPF/CNPJ</label>
                            <input type="text" id="interno_cnpj_cpf_cliente" class="modern-input" readonly>
                        </div>
                
                        <!-- CAMPO DE VALOR TOTAL -->
                        <div class="field-group highlight-value">
                            <label for="interno_valor_total">Valor Total</label>
                            <input type="text" id="interno_valor_total" class="modern-input currency" readonly>
                        </div>
                
                        <!-- Seção Bancária -->
                        <div class="field-group">
                            <label for="interno_nome_banco">Banco</label>
                            <input type="text" id="interno_nome_banco" class="modern-input" readonly>
                        </div>
                
                        <div class="field-group mini">
                            <label for="interno_agencia">Agência</label>
                            <input type="text" id="interno_agencia" class="modern-input" readonly>
                        </div>
                
                        <div class="field-group">
                            <label for="interno_nome_conta_banco">Conta Bancária</label>
                            <input type="text" id="interno_nome_conta_banco" class="modern-input" readonly>
                        </div>
                
                        <div class="field-group">
                            <label>Conta / Dígito</label>
                            <div class="input-composite">
                                <input type="text" id="interno_conta_corrente" class="modern-input" readonly>
                                <span class="separador">-</span>
                                <input type="text" id="interno_digito_conta_corrente" class="modern-input digit" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {* Painel de Informações da Cobrança *}
                <div class="panel panel-default" id="painel_dados_cobranca">
                    <div class="panel-heading">
                        <strong><i class="glyphicon glyphicon-list-alt"></i> Dados da Cobrança no Banco</strong>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Nome do Pagador:</dt>
                                    <dd id="api_pagador_nome">-</dd>
                                    <dt>Emissão:</dt>
                                    <dd id="api_data_emissao">-</dd>
                                    <dt>Vencimento:</dt>
                                    <dd id="api_data_vencimento">-</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Situação:</dt>
                                    <dd id="api_situacao_banco">-</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Nosso Número:</dt>
                                    <dd id="api_nosso_numero">-</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Valor Total:</dt>
                                    <dd id="api_valor_total">-</dd>
                                </dl>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Linha Digitável:</dt>
                                    <dd id="api_linha_digitavel">-</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <dl class="dl-horizontal" style="margin-bottom: 0;">
                                    <dt>Pix Copia e Cola:</dt>
                                    <dd style="margin-left:0;">
                                        <div class="copy-field-wrapper">
                                            <span id="api_pix_copia_e_cola" class="copy-field-text">-</span>
                                            <button type="button" class="btn btn-default btn-xs copy-field-btn" onclick="copiarPix()">
                                                <i class="glyphicon glyphicon-duplicate"></i> Copiar
                                            </button>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                    </div>
                </div>
                {* Botões de Ação *}
                <div class="well well-sm">
                    <div class="row">
                        <div class="col-xs-12">
                            <strong><i class="glyphicon glyphicon-cog"></i> Ações da API Bancária:</strong>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-success btn-sm btn-block" id="btnEnviarCobranca" disabled onclick="EnviarCobrancaApi($('#id_lancamento').val(), $('#id_banco').val())">
                                <i class="glyphicon glyphicon-upload"></i> Enviar Cobrança
                            </button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary btn-sm btn-block" id="btnPagarCobranca" onclick="PagarCobrancaApi($('#id_tabela_api').val(), $('#id_banco').val())">
                                <i class="glyphicon glyphicon-usd"></i> Pagar Cobrança
                            </button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-sm btn-block" id="btnCancelarCobranca" disabled onclick="CancelarCobrancaApi($('#id_tabela_api').val(), $('#id_banco').val(), $('#id_lancamento').val())" >
                                <i class="glyphicon glyphicon-remove"></i> Cancelar Cobrança
                            </button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-info btn-sm btn-block" id="btnConsultarBanco" disabled onclick="ConsultarCobrancaApi($('#id_tabela_api').val(), $('#id_banco').val(), $('#id_lancamento').val())">
                                <i class="glyphicon glyphicon-refresh"></i> Consultar Cobrança e atualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>

{* Modal de Confirmação de Cancelamento *}
<div class="modal fade" id="modalConfirmaCancelamento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color: #fff; border-radius: 4px 4px 0 0;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff;">
                    <span>&times;</span>
                </button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-warning-sign"></i> Confirmar Cancelamento</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja cancelar esta cobrança?</p>
                <p><strong>Esta ação não pode ser desfeita.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-danger" id="btnConfirmaCancelamento">Sim, Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{$pathJs}/fin/s_manutencao_cobranca_api_modal.js"> </script>
