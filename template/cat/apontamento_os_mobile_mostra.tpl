<style>
    .apont-os-page .x_title h2 { margin: 0; font-size: 18px; }
    .apont-os-page .apont-os-filtros { margin-bottom: 12px; }
    .apont-os-page .apont-os-filtros .form-control {
        border-radius: 5px; font-size: 14px; min-height: 38px;
    }
    .apont-os-page label { font-weight: 600; margin-bottom: 4px; }
    .apont-os-page .apont-os-actions {
        display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;
    }
    .apont-os-page .apont-os-actions .btn {
        flex: 1 1 calc(50% - 4px); min-height: 42px; border-radius: 5px;
    }
    .apont-os-page .apont-os-desktop-table { display: none; }
    .apont-os-page .apont-os-mobile-list { display: block; }
    .apont-os-page .apont-os-card {
        border: 1px solid #e5e5e5; border-radius: 8px; padding: 12px; margin-bottom: 10px;
        background: #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    }
    .apont-os-page .apont-os-card-title {
        font-weight: 700; font-size: 14px; margin-bottom: 8px; word-break: break-word;
    }
    .apont-os-page .apont-os-card-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: 6px 12px; font-size: 13px; margin-bottom: 10px;
    }
    .apont-os-page .apont-os-card-meta span {
        display: block; color: #666; font-size: 11px; text-transform: uppercase;
    }
    .apont-os-page .apont-os-card-actions { display: flex; gap: 8px; }
    .apont-os-page .apont-os-card-actions .btn { flex: 1; min-height: 40px; }

    @media (min-width: 992px) {
        .apont-os-page .x_title h2 { text-align: left; font-size: 22px; }
        .apont-os-page .apont-os-actions .btn {
            flex: 0 0 auto; min-width: 140px; min-height: 34px;
        }
        .apont-os-page .apont-os-filtros .form-control { min-height: 34px; font-size: 13px; }
        .apont-os-page .apont-os-desktop-table { display: block; }
        .apont-os-page .apont-os-mobile-list { display: none; }
        .apont-os-page .x_content,
        .apont-os-page .dataTables_wrapper { overflow-x: hidden; }
        .apont-os-page .dataTables_wrapper .bottom {
            display: flex; flex-wrap: wrap; align-items: center;
            justify-content: space-between; gap: 8px;
        }
    }
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_apontamento_os_mobile.js"></script>

<div class="right_col apont-os-page" role="main">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Apontamento O.S.</h2>
                    <div class="clearfix"></div>
                </div>

                {if $mensagem neq ''}
                    {if $tipoMsg eq 'sucesso'}
                        <div class="alert alert-success" role="alert"><strong>Sucesso!</strong> {$mensagem}</div>
                    {elseif $tipoMsg eq 'alerta'}
                        <div class="alert alert-danger" role="alert"><strong>Aviso!</strong> {$mensagem}</div>
                    {/if}
                {/if}

                <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate method="POST"
                        class="form-horizontal form-label-left apont-os-filtros" action="{$SCRIPT_NAME}">
                        <input name="mod" type="hidden" value="cat">
                        <input name="form" type="hidden" value="apontamento_os_mobile">
                        <input name="opcao" type="hidden" value="{$opcao}">
                        <input name="origem" type="hidden" value="{$origem}">
                        <input name="id" type="hidden" value="">
                        <input name="letra" type="hidden" value="{$letra}">
                        <input name="submenu" type="hidden" value="{$subMenu}">
                        <input name="dataIni" type="hidden" value="{$dataIni}">
                        <input name="dataFim" type="hidden" value="{$dataFim}">
                        <input name="pessoa" type="hidden" value="{$pessoa}">
                        <input name="situacaoSelecionada" type="hidden" value="">

                        <div class="row">
                            <div class="col-md-2 col-sm-4 col-xs-12 form-group">
                                <label>N&uacute;mero O.S.</label>
                                <input class="form-control input-sm" id="numAtendimento" name="numAtendimento"
                                    placeholder="O.S." value="{$numAtendimento}">
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                                <label>Per&iacute;odo</label>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control input-sm"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                                <label>Status</label>
                                <select class="form-control input-sm" name="situacao" id="situacao">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 form-group apont-os-actions">
                                <label class="hidden-xs">&nbsp;</label>
                                <button type="button" class="btn btn-warning btn-sm" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-search"></span> Pesquisa
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onClick="limparCampos();">
                                    <span class="glyphicon glyphicon-erase"></span> Limpar
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="apont-os-mobile-list">
                        {assign var="temOs" value=false}
                        {section name=i loop=$lanc}
                            {if $lanc[i].ID_SITUACAO}
                                {assign var="temOs" value=true}
                                <div class="apont-os-card">
                                    <div class="apont-os-card-title">{$lanc[i].NOME}</div>
                                    <div class="apont-os-card-meta">
                                        <div><span>OS</span>{$lanc[i].ID}</div>
                                        <div><span>Situa&ccedil;&atilde;o</span>{$lanc[i].SITUACAODESC}</div>
                                        <div><span>Emiss&atilde;o</span>{$lanc[i].DATAABERATEND|date_format:"%d/%m/%Y"}</div>
                                    </div>
                                    <div class="apont-os-card-actions">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="abrirFinalizacao('{$lanc[i].ID}');">Apontar</button>
                                        <button type="button" class="btn btn-dark btn-sm"
                                            onclick="javascript:submitCadastrarImagemOS('{$lanc[i].ID}');">
                                            <span class="glyphicon glyphicon-camera"></span> Imagem
                                        </button>
                                    </div>
                                </div>
                            {/if}
                        {/section}
                        {if !$temOs}
                            <div class="alert alert-info">Nenhum resultado encontrado.</div>
                        {/if}
                    </div>

                    <div class="apont-os-desktop-table">
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th>Pessoa</th>
                                    <th style="text-align: center;">OS</th>
                                    <th style="text-align: center;">Situa&ccedil;&atilde;o</th>
                                    <th style="text-align: center;">Emiss&atilde;o</th>
                                    <th style="text-align: center;">A&ccedil;&otilde;es</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].ID_SITUACAO}
                                        <tr>
                                            <td>{$lanc[i].NOME}</td>
                                            <td style="text-align: center;">{$lanc[i].ID}</td>
                                            <td style="text-align: center;">{$lanc[i].SITUACAODESC}</td>
                                            <td style="text-align: center;">{$lanc[i].DATAABERATEND|date_format:"%d/%m/%Y"}</td>
                                            <td style="text-align: center; white-space: nowrap;">
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="abrirFinalizacao('{$lanc[i].ID}');">Apontar</button>
                                                <button type="button" class="btn btn-dark btn-xs"
                                                    onclick="javascript:submitCadastrarImagemOS('{$lanc[i].ID}');"
                                                    title="Imagem">
                                                    <span class="glyphicon glyphicon-camera"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    {/if}
                                {/section}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<script type="text/javascript">
    $(function() {
        if (window.matchMedia('(min-width: 992px)').matches && $.fn.DataTable.isDataTable('#datatable-buttons')) {
            var dt = $('#datatable-buttons').DataTable();
            if (dt.responsive) {
                dt.responsive.disable();
            }
        }
    });

    $('#dataConsulta').daterangepicker({
        startDate: moment("{$dataIni}", "DD/MM/YYYY"),
        endDate: moment("{$dataFim}", "DD/MM/YYYY"),
        autoApply: true,
        ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Confirma',
            cancelLabel: 'Limpa',
            fromLabel: 'Início',
            toLabel: 'Fim',
            customRangeLabel: 'Calendário',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    }, function(start, end) {
        var f = document.lancamento;
        f.dataIni.value = start.format('DD/MM/YYYY');
        f.dataFim.value = end.format('DD/MM/YYYY');
    });
</script>
