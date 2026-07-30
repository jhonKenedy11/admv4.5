<style>
.form-control, .x_panel { border-radius: 5px; }
.apuracao-passo { margin-right: 8px; margin-bottom: 8px; }
.apuracao-chave { font-family: monospace; font-size: 11px; word-break: break-all; }
.apuracao-tab-content { padding-top: 15px; }

/* Aviso informativo com melhor contraste que o alert-warning padrão */
.apuracao-aviso {
    margin-top: 10px;
    background-color: #eef6fc;
    border: 1px solid #b8daf0;
    border-left: 4px solid #2a7ab0;
    color: #1b4b68;
    border-radius: 5px;
    padding: 12px 15px;
    font-size: 13px;
    line-height: 1.5;
}
.apuracao-aviso .fa { color: #2a7ab0; margin-right: 4px; }
.apuracao-aviso strong { color: #123a53; }

/* Indicadores de uso diário (Consultas / Downloads) */
.apuracao-metrics { display: inline-flex; gap: 10px; align-items: center; margin-top: 4px; }
.apuracao-metric {
    display: inline-flex;
    align-items: center;
    background: #f4f7fa;
    border: 1px solid #d6dee6;
    border-radius: 20px;
    padding: 2px 4px 2px 12px;
    font-size: 13px;
    color: #4a5b6b !important;
    line-height: 1.6;
}
.apuracao-metric-label { margin-right: 8px; font-weight: 600; color: #4a5b6b !important; }
.apuracao-metric-value {
    display: inline-block;
    min-width: 46px;
    text-align: center;
    padding: 3px 10px;
    border-radius: 16px;
    font-weight: 700;
    color: #fff;
    background: #3f8f4f;
}
.apuracao-metric-value.is-warning { background: #d38a17; }
.apuracao-metric-value.is-danger { background: #c0392b; }

/* Card de status das credenciais */
.apuracao-cred-card {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e0e6ec;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 15px;
}
.apuracao-cred-info { display: flex; flex-wrap: wrap; align-items: center; gap: 18px; }
.apuracao-cred-title { font-weight: 600; color: #34495e; }
.apuracao-cred-title .fa { color: #2a7ab0; margin-right: 4px; }
.apuracao-cred-item { font-size: 13px; color: #4a5b6b; }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_apuracao_cbs.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div class="right_col" role="main">
    <form class="full form-horizontal form-label-left" name="lancamento" method="POST" novalidate action={$SCRIPT_NAME}>
        <input name=mod type=hidden value="est">
        <input name=form type=hidden value="apuracao_cbs">
        <input name=opcao type=hidden value="">
        <input name=submenu type=hidden value="{$subMenu}">
        <input name=id type=hidden value="">
        <input name=id_historico type=hidden value="{$id_historico}">
        <input name=id_debito type=hidden value="">
        <input name=chave_dfe type=hidden value="">
        <input name=tp_evento type=hidden value="">
        <input name=papel type=hidden value="">
        <input name=observacao type=hidden value="">
        <input name=aba type=hidden value="{$abaAtiva}">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Apuração Assistida CBS</h2>
                            <div class="apuracao-metrics pull-right">
                                <span class="apuracao-metric" title="Consultas solicitadas hoje para este CNPJ">
                                    <span class="apuracao-metric-label">Consultas hoje</span>
                                    <span class="apuracao-metric-value {if $limiteConsultaExcedido}is-danger{elseif $limiteConsulta.restante lte 1}is-warning{/if}">
                                        {$limiteConsulta.total} / {$limiteConsulta.limite}
                                    </span>
                                </span>
                                <span class="apuracao-metric" title="Downloads realizados hoje para este CNPJ">
                                    <span class="apuracao-metric-label">Downloads hoje</span>
                                    <span class="apuracao-metric-value {if $limiteDownload.excedido}is-danger{elseif $limiteDownload.restante lte 2}is-warning{/if}">
                                        {$limiteDownload.total} / {$limiteDownload.limite}
                                    </span>
                                </span>
                            </div>
                            {include file="../bib/msg.tpl"}
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <ul class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" {if $abaAtiva eq 'consulta'}class="active"{/if}>
                                    <a href="#aba-consulta" role="tab" data-toggle="tab" onclick="apuracaoSetAba('consulta');">
                                        <i class="fa fa-cloud-download"></i> Consulta RF
                                    </a>
                                </li>
                                <li role="presentation" {if $abaAtiva eq 'credito'}class="active"{/if}>
                                    <a href="#aba-credito" role="tab" data-toggle="tab" onclick="apuracaoSetAba('credito');">
                                        <i class="fa fa-arrow-down"></i> Pendências Crédito (destinatário)
                                    </a>
                                </li>
                                <li role="presentation" {if $abaAtiva eq 'debito'}class="active"{/if}>
                                    <a href="#aba-debito" role="tab" data-toggle="tab" onclick="apuracaoSetAba('debito');">
                                        <i class="fa fa-arrow-up"></i> Pendências Débito (emitente)
                                    </a>
                                </li>
                                <li role="presentation" {if $abaAtiva eq 'eventos'}class="active"{/if}>
                                    <a href="#aba-eventos" role="tab" data-toggle="tab" onclick="apuracaoSetAba('eventos');">
                                        <i class="fa fa-history"></i> Histórico de Eventos
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content apuracao-tab-content">

                                {* ===== ABA 1 — PENDÊNCIAS CRÉDITO (DESTINATARIO / eventos 2xxxx) ===== *}
                                <div role="tabpanel" class="tab-pane fade {if $abaAtiva eq 'credito'}in active{/if}" id="aba-credito">
                                    <p class="text-muted">
                                        Notas em que a empresa é <strong>adquirente/destinatário</strong>. Eventos disponíveis: apropriação de crédito e aceite (série 2xxxx).
                                    </p>
                                    <table class="table table-bordered jambo_table">
                                        <thead>
                                            <tr class="headings">
                                                <th>Chave DF-e</th>
                                                <th>Apuração</th>
                                                <th>Emitente</th>
                                                <th>CBS Total</th>
                                                <th>CBS Não Extinto</th>
                                                <th>Situação</th>
                                                <th>Status</th>
                                                <th class="no-link last" style="width:150px;">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=c loop=$debitosCredito}
                                                <tr class="{if $debitosCredito[c].DIVERGENTE eq 'S'}warning{/if}">
                                                    <td class="apuracao-chave">{$debitosCredito[c].CHAVE_DFE}</td>
                                                    <td>
                                                        <small>{$debitosCredito[c].TIPO_APURACAO}</small><br>
                                                        <small class="text-muted">{$debitosCredito[c].DATA_APURACAO}</small>
                                                    </td>
                                                    <td><small>{$debitosCredito[c].NI_EMITENTE}</small></td>
                                                    <td class="text-right">{$debitosCredito[c].VALOR_CBS_TOTAL|number_format:2:",":"."}</td>
                                                    <td class="text-right">
                                                        <strong>{$debitosCredito[c].VALOR_CBS_NAO_EXTINTO|number_format:2:",":"."}</strong>
                                                        {if $debitosCredito[c].DIVERGENTE eq 'S'}<span class="label label-warning">Alterado</span>{/if}
                                                    </td>
                                                    <td><small>{$debitosCredito[c].SITUACAO_DEBITO}</small></td>
                                                    <td>
                                                        {if $debitosCredito[c].STATUS_EVENTO eq 'REGISTRADO'}
                                                            <span class="label label-success">Evento OK</span>
                                                        {else}
                                                            <span class="label label-default">Pendente</span>
                                                        {/if}
                                                    </td>
                                                    <td class="last">
                                                        <button type="button" class="btn btn-default btn-xs"
                                                                onclick="apuracaoBaixarXml('{$debitosCredito[c].CHAVE_DFE}');" title="Baixar XML">
                                                            <span class="glyphicon glyphicon-file"></span> XML
                                                        </button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                Evento <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                {section name=ec loop=$catalogoCredito}
                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                           onclick="submitEmitirEvento('{$debitosCredito[c].CHAVE_DFE}', '{$catalogoCredito[ec].tp}', 'DESTINATARIO', {$debitosCredito[c].ID});">
                                                                            {$catalogoCredito[ec].tp} - {$catalogoCredito[ec].label}
                                                                        </a>
                                                                    </li>
                                                                {/section}
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            {sectionelse}
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        Nenhum crédito pendente. Selecione um histórico baixado na aba "Consulta RF".
                                                    </td>
                                                </tr>
                                            {/section}
                                        </tbody>
                                    </table>
                                </div>

                                {* ===== ABA 2 — PENDÊNCIAS DÉBITO (EMITENTE / eventos 1xxxx) ===== *}
                                <div role="tabpanel" class="tab-pane fade {if $abaAtiva eq 'debito'}in active{/if}" id="aba-debito">
                                    <p class="text-muted">
                                        Notas em que a empresa é <strong>emitente/fornecedor</strong>. Eventos disponíveis: pagamento, perda, fornecimento não realizado (série 1xxxx).
                                    </p>
                                    <table class="table table-bordered jambo_table">
                                        <thead>
                                            <tr class="headings">
                                                <th>Chave DF-e</th>
                                                <th>Apuração</th>
                                                <th>Adquirente</th>
                                                <th>CBS Total</th>
                                                <th>CBS Não Extinto</th>
                                                <th>Situação</th>
                                                <th>Status</th>
                                                <th class="no-link last" style="width:150px;">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=d loop=$debitosDebito}
                                                <tr class="{if $debitosDebito[d].DIVERGENTE eq 'S'}warning{/if}">
                                                    <td class="apuracao-chave">{$debitosDebito[d].CHAVE_DFE}</td>
                                                    <td>
                                                        <small>{$debitosDebito[d].TIPO_APURACAO}</small><br>
                                                        <small class="text-muted">{$debitosDebito[d].DATA_APURACAO}</small>
                                                    </td>
                                                    <td><small>{$debitosDebito[d].NI_ADQUIRENTE}</small></td>
                                                    <td class="text-right">{$debitosDebito[d].VALOR_CBS_TOTAL|number_format:2:",":"."}</td>
                                                    <td class="text-right">
                                                        <strong>{$debitosDebito[d].VALOR_CBS_NAO_EXTINTO|number_format:2:",":"."}</strong>
                                                        {if $debitosDebito[d].DIVERGENTE eq 'S'}<span class="label label-warning">Alterado</span>{/if}
                                                    </td>
                                                    <td><small>{$debitosDebito[d].SITUACAO_DEBITO}</small></td>
                                                    <td>
                                                        {if $debitosDebito[d].STATUS_EVENTO eq 'REGISTRADO'}
                                                            <span class="label label-success">Evento OK</span>
                                                        {else}
                                                            <span class="label label-default">Pendente</span>
                                                        {/if}
                                                    </td>
                                                    <td class="last">
                                                        <button type="button" class="btn btn-default btn-xs"
                                                                onclick="apuracaoBaixarXml('{$debitosDebito[d].CHAVE_DFE}');" title="Baixar XML">
                                                            <span class="glyphicon glyphicon-file"></span> XML
                                                        </button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                Evento <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                {section name=ed loop=$catalogoDebito}
                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                           onclick="submitEmitirEvento('{$debitosDebito[d].CHAVE_DFE}', '{$catalogoDebito[ed].tp}', 'EMITENTE', {$debitosDebito[d].ID});">
                                                                            {$catalogoDebito[ed].tp} - {$catalogoDebito[ed].label}
                                                                        </a>
                                                                    </li>
                                                                {/section}
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            {sectionelse}
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        Nenhum débito pendente. Selecione um histórico baixado na aba "Consulta RF".
                                                    </td>
                                                </tr>
                                            {/section}
                                        </tbody>
                                    </table>
                                </div>

                                {* ===== ABA 3 — HISTÓRICO DE EVENTOS ===== *}
                                <div role="tabpanel" class="tab-pane fade {if $abaAtiva eq 'eventos'}in active{/if}" id="aba-eventos">
                                    <table class="table table-bordered jambo_table">
                                        <thead>
                                            <tr class="headings">
                                                <th>Data</th>
                                                <th>Evento</th>
                                                <th>Descrição</th>
                                                <th>Papel</th>
                                                <th>Chave DF-e</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=e loop=$eventos}
                                                <tr>
                                                    <td><small>{$eventos[e].DT_INSERT}</small></td>
                                                    <td>{$eventos[e].TP_EVENTO}</td>
                                                    <td><small>{$eventos[e].DESCRICAO}</small></td>
                                                    <td><small>{$eventos[e].PAPEL}</small></td>
                                                    <td class="apuracao-chave">{$eventos[e].CHAVE_DFE}</td>
                                                    <td>
                                                        {if $eventos[e].STATUS eq 'SUCESSO'}
                                                            <span class="label label-success">{$eventos[e].STATUS}</span>
                                                        {elseif $eventos[e].STATUS eq 'ERRO'}
                                                            <span class="label label-danger">{$eventos[e].STATUS}</span>
                                                        {else}
                                                            <span class="label label-info">{$eventos[e].STATUS}</span>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {sectionelse}
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Nenhum evento registrado.</td>
                                                </tr>
                                            {/section}
                                        </tbody>
                                    </table>
                                </div>

                                {* ===== ABA 4 — CONSULTA RF / CREDENCIAIS ===== *}
                                <div role="tabpanel" class="tab-pane fade {if $abaAtiva eq 'consulta'}in active{/if}" id="aba-consulta">

                                    {* Painel de Consulta (ação principal da tela) *}
                                    <div class="panel panel-primary">
                                        <div class="panel-heading"><strong><i class="fa fa-cloud-download"></i> Consulta da Apuração (fluxo em 2 passos)</strong></div>
                                        <div class="panel-body">
                                            <div class="apuracao-aviso">
                                                <i class="fa fa-clock-o"></i>
                                                Após <strong>Solicitar Consulta</strong>, a Receita processa a apuração e devolve o
                                                tíquete <strong>automaticamente pelo webhook</strong>. Quando o retorno chega, a
                                                requisição fica <strong>Disponível</strong> e o botão de baixar libera sozinho.
                                                O arquivo JSON tem <strong>validade de 24 horas</strong>; limite de
                                                <strong>2 consultas</strong> e <strong>8 downloads</strong> por CNPJ/dia.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 col-sm-6 form-group">
                                                    <label for="tiquete">Tíquete</label>
                                                    <input type="text" class="form-control" id="tiquete" name="tiquete"
                                                           value="{$tiquete}" placeholder="Preenchido automaticamente pelo webhook">
                                                </div>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-info apuracao-passo"
                                                        onclick="javascript:submitSolicitarConsulta();"
                                                        {if $limiteConsultaExcedido}disabled{/if}>
                                                    <i class="fa fa-search"></i> 1. Solicitar Consulta
                                                </button>
                                                <button type="button" class="btn btn-success apuracao-passo"
                                                        onclick="javascript:submitDownloadDebitos();">
                                                    <i class="fa fa-download"></i> 2. Baixar Débitos
                                                </button>
                                                <button type="button" class="btn btn-default apuracao-passo"
                                                        onclick="javascript:submitAtualizarHistorico();"
                                                        title="Recarrega o histórico para verificar se o retorno já chegou">
                                                    <i class="fa fa-refresh"></i> Atualizar
                                                </button>
                                            </div>

                                            {* Explicação de cada botão *}
                                            <ul class="list-unstyled" style="margin-top:12px;">
                                                <li style="margin-bottom:6px;">
                                                    <span class="label label-info">1. Solicitar Consulta</span>
                                                    Pede à Receita Federal a apuração do CNPJ. A requisição fica <strong>Aguardando retorno</strong> até a Receita entregar o tíquete pelo webhook.
                                                </li>
                                                <li style="margin-bottom:6px;">
                                                    <span class="label label-success">2. Baixar Débitos</span>
                                                    Disponível quando o retorno chega (status <strong>Disponível</strong>). <strong>Baixa e grava</strong> o JSON; os dados aparecem nas abas <em>Pendências Crédito</em> e <em>Pendências Débito</em>.
                                                </li>
                                                <li style="margin-bottom:6px;">
                                                    <span class="label label-default">Atualizar</span>
                                                    Recarrega o histórico para conferir se o retorno automático já chegou.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    {* Credenciais — card de status compacto + edição em modal *}
                                    <div class="apuracao-cred-card">
                                        <div class="apuracao-cred-info" id="apuracao-cred-resumo">
                                            <span class="apuracao-cred-title"><i class="fa fa-lock"></i> Credenciais da API</span>
                                            {if $client_id ne ''}
                                                <span class="apuracao-cred-item"><span class="text-muted">CNPJ:</span> <strong>{$cnpj_base}</strong></span>
                                                <span class="apuracao-cred-item">
                                                    <span class="text-muted">Ambiente:</span>
                                                    <span class="label {if $ambiente eq 'PRODUCAO'}label-primary{else}label-default{/if}">{$ambiente}</span>
                                                </span>
                                                <span class="apuracao-cred-item">
                                                    <span class="text-muted">Token:</span>
                                                    <span id="apuracao-token-status">{if $token_expira}<span class="label label-success">válido até {$token_expira}</span>{else}<span class="label label-default">gerado sob demanda</span>{/if}</span>
                                                </span>
                                            {else}
                                                <span class="apuracao-cred-item text-danger">
                                                    <i class="fa fa-exclamation-triangle"></i> Nenhuma credencial configurada.
                                                </span>
                                            {/if}
                                        </div>
                                        <button type="button" id="apuracao-btn-credencial" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalCredenciais">
                                            <i class="fa fa-cog"></i> {if $client_id ne ''}Editar credenciais{else}Configurar credenciais{/if}
                                        </button>
                                    </div>

                                    {* Histórico de requisições *}
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><strong><i class="fa fa-history"></i> Histórico de Requisições</strong></div>
                                        <div class="panel-body">
                                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                                <thead>
                                                    <tr class="headings">
                                                        <th>ID</th>
                                                        <th>CNPJ Base</th>
                                                        <th>Tíquete</th>
                                                        <th>Status</th>
                                                        <th>Solicitação</th>
                                                        <th>Download</th>
                                                        <th>Débitos</th>
                                                        <th class="no-link last" style="width:120px;">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {section name=i loop=$historico}
                                                        <tr class="even pointer">
                                                            <td>{$historico[i].ID}</td>
                                                            <td>{$historico[i].CNPJ_BASE}</td>
                                                            <td><small>{$historico[i].TIQUETE}</small></td>
                                                            <td>
                                                                {if $historico[i].STATUS eq 'BAIXADO'}
                                                                    <span class="label label-success">{$historico[i].STATUS}</span>
                                                                {elseif $historico[i].STATUS eq 'DISPONIVEL'}
                                                                    <span class="label label-success" title="Retorno recebido; pronto para baixar">DISPONÍVEL</span>
                                                                {elseif $historico[i].STATUS eq 'ERRO'}
                                                                    <span class="label label-danger">{$historico[i].STATUS}</span>
                                                                {elseif $historico[i].STATUS eq 'AGUARDANDO_RETORNO'}
                                                                    <span class="label label-warning" title="Aguardando o retorno automático da Receita (webhook)">AGUARDANDO RETORNO</span>
                                                                {elseif $historico[i].STATUS eq 'PROCESSANDO'}
                                                                    <span class="label label-warning">{$historico[i].STATUS}</span>
                                                                {else}
                                                                    <span class="label label-info">{$historico[i].STATUS}</span>
                                                                {/if}
                                                            </td>
                                                            <td>{$historico[i].DT_SOLICITACAO}</td>
                                                            <td>{$historico[i].DT_DOWNLOAD}</td>
                                                            <td>{$historico[i].QTDE_DEBITOS}</td>
                                                            <td class="last">
                                                                {if $historico[i].TIQUETE}
                                                                    <button type="button" class="btn btn-primary btn-xs"
                                                                            onclick="javascript:submitUsarTiquete('{$historico[i].TIQUETE}', {$historico[i].ID});"
                                                                            title="Usar tíquete">
                                                                        <span class="glyphicon glyphicon-arrow-up"></span>
                                                                    </button>
                                                                {/if}
                                                                {if $historico[i].STATUS eq 'DISPONIVEL' && $historico[i].TIQUETE}
                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                            onclick="javascript:submitDownloadDebitos('{$historico[i].TIQUETE}');"
                                                                            title="Baixar débitos (retorno disponível)">
                                                                        <span class="glyphicon glyphicon-download"></span>
                                                                    </button>
                                                                {/if}
                                                                <button type="button" class="btn btn-default btn-xs"
                                                                        onclick="javascript:submitVerDebitos({$historico[i].ID}, '{$historico[i].TIQUETE}');"
                                                                        title="Ver débitos">
                                                                    <span class="glyphicon glyphicon-list"></span>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    {sectionelse}
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted">Nenhuma requisição registrada.</td>
                                                        </tr>
                                                    {/section}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {* ===== Modal de edição de credenciais (dentro do form para enviar os campos) ===== *}
        <div class="modal fade" id="modalCredenciais" tabindex="-1" role="dialog" aria-labelledby="modalCredenciaisTitulo" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalCredenciaisTitulo"><i class="fa fa-lock"></i> Credenciais da API (Receita Federal)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 form-group">
                                <label for="cnpj_base">CNPJ Base (8 caracteres)</label>
                                <input type="text" class="form-control" id="cnpj_base" name="cnpj_base"
                                       maxlength="8" value="{$cnpj_base}" placeholder="00000000">
                            </div>
                            <div class="col-md-5 col-sm-12 form-group">
                                <label for="client_id">Client ID</label>
                                <input type="text" class="form-control" id="client_id" name="client_id"
                                       value="{$client_id}" autocomplete="off">
                            </div>
                            <div class="col-md-4 col-sm-12 form-group">
                                <label for="ambiente">Ambiente</label>
                                <select class="form-control" id="ambiente" name="ambiente">
                                    <option value="HOMOLOGACAO" {if $ambiente eq 'HOMOLOGACAO'}selected{/if}>Homologação</option>
                                    <option value="PRODUCAO_RESTRITA" {if $ambiente eq 'PRODUCAO_RESTRITA'}selected{/if}>Produção Restrita</option>
                                    <option value="PRODUCAO" {if $ambiente eq 'PRODUCAO'}selected{/if}>Produção</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 form-group">
                                <label for="client_secret">Client Secret</label>
                                <input type="password" class="form-control" id="client_secret" name="client_secret"
                                       value="" placeholder="••••••••" autocomplete="new-password">
                                <small class="text-muted">Deixe em branco para manter o secret já salvo.</small>
                            </div>
                            <div class="col-md-8 col-sm-12 form-group">
                                <label for="webhook_url">Webhook URL (pública) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="webhook_url" name="webhook_url"
                                       value="{$webhook_url}" placeholder="https://seu-dominio.com/cliente/webhook_apuracao_cbs.php">
                                <small class="text-muted">URL de retorno onde a Receita entrega o tíquete. Obrigatória e acessível publicamente (HTTPS).</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="webhook_secret">Segredo do Webhook</label>
                                <input type="password" class="form-control" id="webhook_secret" name="webhook_secret"
                                       value="" placeholder="{if $tem_webhook_secret}••••••••{else}(opcional){/if}" autocomplete="new-password">
                                <small class="text-muted">
                                    {if $tem_webhook_secret}Segredo já salvo. Preencha para alterar.{else}Opcional. Se definido, valida o POST do webhook (envie em ?secret= ou header X-Webhook-Secret).{/if}
                                </small>
                            </div>
                        </div>
                        <div class="alert apuracao-aviso" style="margin-bottom:0;">
                            <i class="fa fa-key"></i>
                            O <strong>token</strong> de acesso é gerado e renovado automaticamente. Use "Testar credenciais" apenas para validar o client_id/secret.
                            {if $token_expira}<br>Token atual válido até <strong>{$token_expira}</strong>.{/if}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning"
                                onclick="javascript:submitGerarToken();"
                                title="Opcional: valida as credenciais gerando o token">
                            <i class="fa fa-key"></i> Testar credenciais
                        </button>
                        <button type="button" class="btn btn-primary"
                                onclick="javascript:submitSalvarCredencial();">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Salvar credenciais
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {include file="apuracao_cbs_detalhe.tpl"}
    {include file="template/database.inc"}
</div>
