<style>
    .apont-os-page .x_title h2 { margin: 0; font-size: 18px; }
    .apont-os-page .apont-os-form .form-control {
        border-radius: 5px; font-size: 14px; min-height: 38px;
    }
    .apont-os-page label { font-weight: 600; margin-bottom: 4px; }
    .apont-os-page .apont-os-toolbar {
        display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; margin-bottom: 12px;
    }
    .apont-os-page .apont-os-toolbar .btn { min-height: 38px; border-radius: 5px; }
    .apont-os-page .input-error { background-color: #ffe6e6 !important; }
    .apont-os-page .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    @media (max-width: 991px) {
        .apont-os-page .apont-os-servico-table thead { display: none; }
        .apont-os-page .apont-os-servico-table tr.even.pointer {
            display: block; border: 1px solid #e5e5e5; border-radius: 8px;
            margin-bottom: 10px; padding: 10px; background: #fff;
        }
        .apont-os-page .apont-os-servico-table tr.even.pointer td {
            display: block; width: 100% !important; border: none !important;
            padding: 4px 0 !important; text-align: left !important;
        }
        .apont-os-page .apont-os-servico-table tr.even.pointer td:first-child {
            font-weight: 600; margin-bottom: 8px;
        }
        .apont-os-page .apont-os-servico-table tr.even.pointer td:nth-child(2):before {
            content: 'Quantidade OS'; display: block; font-size: 11px; color: #777;
            text-transform: uppercase; margin-bottom: 2px;
        }
        .apont-os-page .apont-os-servico-table tr.even.pointer td:nth-child(3):before {
            content: 'Executada'; display: block; font-size: 11px; color: #777;
            text-transform: uppercase; margin-bottom: 2px;
        }
    }

    @media (min-width: 992px) {
        .apont-os-page .x_title h2 { text-align: left; font-size: 22px; }
        .apont-os-page .apont-os-form .form-control { min-height: 34px; font-size: 13px; }
        .apont-os-page .apont-os-info-grid { display: flex; flex-wrap: wrap; margin: 0 -10px; }
        .apont-os-page .apont-os-info-grid .form-group {
            flex: 0 0 50%; max-width: 50%; padding: 0 10px;
        }
        .apont-os-page .apont-os-servico-table th,
        .apont-os-page .apont-os-servico-table td {
            font-size: 13px; padding: 8px !important; vertical-align: middle !important;
        }
    }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/cat/s_apontamento_os_mobile.js"></script>

<div class="right_col apont-os-page" role="main">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left apont-os-form"
                name="lancamento" action="{$SCRIPT_NAME}" method="POST">
                <input name="mod" type="hidden" value="cat">
                <input name="form" type="hidden" value="apontamento_os_mobile">
                <input name="submenu" type="hidden" value="{$subMenu}">
                <input name="letra" type="hidden" value="{$letra}">

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Apontamento O.S. #{$numero_os}</h2>
                        <ul class="nav navbar-right panel_toolbox hidden-xs">
                            <li>
                                <button type="button" class="btn btn-danger btn-sm" onClick="javascript:submitVoltar();">
                                    <span class="glyphicon glyphicon-backward"></span> Voltar
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary btn-sm" onClick="javascript:submitConfirmar();">
                                    <span class="glyphicon glyphicon-floppy-disk"></span> Confirmar
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="apont-os-toolbar visible-xs">
                        <button type="button" class="btn btn-danger btn-sm btn-block" onClick="javascript:submitVoltar();">
                            <span class="glyphicon glyphicon-backward"></span> Voltar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm btn-block" onClick="javascript:submitConfirmar();">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Confirmar
                        </button>
                    </div>

                    <div class="x_content">
                        <div class="row apont-os-info-grid">
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>N&uacute;mero da O.S.</label>
                                <input id="numero_os" name="numero_os" type="text" readonly class="form-control" value="{$numero_os}">
                            </div>
                            <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                <label>Cliente</label>
                                <input id="nome_cliente" name="nome_cliente" type="text" readonly class="form-control" value="{$nome_cliente}">
                            </div>
                            <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                <label>Data In&iacute;cio</label>
                                <input id="data_inicio" name="data_inicio" type="date" readonly class="form-control" value="{$data_inicio}">
                            </div>
                            <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                <label>Prazo Entrega</label>
                                <input id="prazo_entrega" name="prazo_entrega" type="date" readonly class="form-control" value="{$prazo_entrega}">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Data Finaliza&ccedil;&atilde;o</label>
                                <input id="data_finalizacao" name="data_finalizacao" type="text" required class="form-control" value="{$data_finalizacao}">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                <label>Status</label>
                                <select class="form-control" name="situacao" id="situacao">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </select>
                            </div>
                        </div>

                        {if $lanc|@count > 0}
                            <div class="table-responsive apont-os-servico-table">
                                <table class="table table-bordered jambo_table">
                                    <thead>
                                        <tr>
                                            <th>Servi&ccedil;o</th>
                                            <th style="text-align:center;width:140px;">Quantidade OS</th>
                                            <th style="text-align:center;width:140px;">Executada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lanc}
                                            <tr class="even pointer">
                                                <td>{$lanc[i].DESCSERVICO}</td>
                                                <td style="text-align:center;">
                                                    <input type="text" name="qtd_exec" value="{$lanc[i].QUANTIDADE|number_format:2:',':'.'}" class="form-control" readonly>
                                                    <input type="hidden" name="qtd_saldo" value="{$lanc[i].QTD_SALDO}">
                                                    <input type="hidden" name="qtd_contratada" value="{$lanc[i].QUANTIDADE}">
                                                </td>
                                                <td style="text-align:center;">
                                                    <input type="hidden" name="id_servico" value="{$lanc[i].ID}">
                                                    <input class="form-control money-editavel" name="quantidade_executada"
                                                        data-valor="{$lanc[i].QUANTIDADE_EXECUTADA}"
                                                        onchange="validateExecutada(event)">
                                                </td>
                                            </tr>
                                        {/section}
                                    </tbody>
                                </table>
                            </div>
                        {else}
                            <div class="alert alert-info">Nenhum servi&ccedil;o cadastrado.</div>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script type="text/javascript">
    $('#data_finalizacao').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Confirma',
            cancelLabel: 'Limpa',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    }, function(start) {
        $('#data_finalizacao').val(start.format('DD/MM/YYYY'));
    });

    if (!$('#data_finalizacao').val()) {
        $('#data_finalizacao').val(moment().format('DD/MM/YYYY'));
    }

    $(function() { aplicarMascaraQuantidade(); });
</script>
