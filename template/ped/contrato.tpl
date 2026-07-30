<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    .invis {
        display: none;
    }

    .checkBox {
        width: 2px;
        padding: 0;
        margin: center;
    }

    #btnEmissaoNf {
        width: 50px;
    }

    #btnFilter {
        font-size: 12px;
    }

    #modalAcompanhamentoOS .table th {
        background-color: #f8f9fa;
    }

    #modalAcompanhamentoOS input[type="number"] {
        max-width: 100px;
    }

    #modalAcompanhamentoOS .modal-body {
        max-height: 80vh;
        overflow-y: auto;
    }

    .btn-group .btn {
        margin-right: 4px !important;
        border-radius: 5px !important;
    }

    .btn-group {
        margin-top: 5px;
    }

    .contrato-page .x_content {
        overflow-x: auto;
    }

    .contrato-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }

    .contrato-toolbar h2 {
        margin: 0;
    }

    .contrato-toolbar .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .contrato-filtros .form-group {
        margin-bottom: 12px;
    }

    .modal-dialog {
        max-width: 90%;
        width: auto !important;
    }

    .show-calendar {
        z-index: 1000000 !important;
    }

    .selecionada {
        background-color: rgb(92, 170, 92);
    }

    .btn-block {
        margin-top: 24px;
    }

    #modalResultOsCadastradas>tr>td {
        text-align: center ! important;
        padding-top: 1.5px;
        padding-bottom: 1.5px;
        vertical-align: middle !important;
    }

    #formOs>thead>tr>th {
        text-align: center;
    }

    #btnEditOsModal {
        height: 30px;
        width: 50px;
        margin: 0 !important;
    }

    .tdModalListServicos {
        text-align: center !important;
        font-size: 12px !important;
    }

    .data_inicio,
    .data_finalizacao {
        text-align: center !important;
    }

    .select2-selection--single,
    .select2-selection--multiple {
        border-radius: 5px !important;
    }

    #nome_cliente_input,
    #data_inicio,
    #prazo_entrega {
        font-size: 12px !important;
    }

    input[type=checkbox],
    input[type=radio] {
        margin-top: 10px !important;
    }
</style>

<div class="right_col" role="main">

    <div class="">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <h2>Gerencia de Contratos</h2>
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark btn-xs"
                                onclick="javascript:submitTodosPedidosDia('btnAtalho', 'dia');">Contratos do
                                Dia</button>
                            <button type="button" class="btn btn-dark btn-xs"
                                onclick="javascript:submitTodosPedidosMes('btnAtalho', 'mes');">Contratos do
                                Mês</button>
                            <button type="button" class="btn btn-dark btn-xs"
                                onclick="javascript:submitTodosPedidos('btnAtalho', 'todos');">Todos
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                Pesquisa
                            </button>
                            <button type="button" class="btn btn-danger" onClick="limparCampos();">
                                <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
                                Limpar Campos
                            </button>
                        </div>
                    </div>
                    <br>


                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value="contrato">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=origem type=hidden value="{$origem}">
                            <input name=id type=hidden value="">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=dadosPed type=hidden value={$dadosPed}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input type="hidden" name="id_pedido" value="{$id_pedido}">
                            <input type="hidden" name="servicos_os" value="">
                            <input type="hidden" name="obs_servico" value="{$obs_servico}">
                            <input type="hidden" name="equipe" value="{$equipe}">
                            <input type="hidden" name="tipoRelatorio" value="">

                            <div class="form-group col-md-2 col-sm-6 col-xs-6">
                                <label>Numero Contrato</label>
                                <input class="form-control" id="numAtendimento" name="numAtendimento"
                                    placeholder="Numero Contrato." value="{$numAtendimento}">
                            </div>

                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <div class="col-md-12" style="padding-left: 0; padding-right: 0">
                                    <label class="">Periodo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <div>
                                        <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                            value="{$dataIni} - {$dataFim}">
                                    </div>
                                </div>


                            </div>

                            <div class="form-group col-md-7 col-sm-12 col-xs-12">
                                <label class="">Cliente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id="nome" name="nome"
                                        placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th>Pessoa</th>
                                    <th style="text-align: center;">Contrato</th>
                                    <th style="text-align: center;">Emiss&atilde;o</th>
                                    <th id="checkBox" style="text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].SITUACAO eq 6}
                                        <tr>

                                            <td> {$lanc[i].NOME} </td>
                                            <td style="text-align: center;">{$lanc[i].PEDIDO}</td>
                                            <td style="text-align: center;">{$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>

                                            <td>
                                                <div style="display: flex; justify-content: center; gap: 5px;">
                                                    <button type="button" name="pedidoChecked" id="{$lanc[i].PEDIDO}"
                                                        class="btn btn-primary btn-xs"
                                                        onclick="controlFunctionModal('{$lanc[i].PEDIDO}');">OS</button>
                                                    <button type="button" name="abrir_medicao" id="{$lanc[i].PEDIDO}"
                                                        class="btn btn-primary btn-xs"
                                                        onclick="abrirMedicao('{$lanc[i].PEDIDO}');">
                                                        <span class="glyphicon glyphicon-list-alt"></span>
                                                    </button>
                                                    <button type="button" name="pesquisar_contrato" id="pesquisar_{$lanc[i].PEDIDO}"
                                                        class="btn btn-info btn-xs"
                                                        onclick="javascript:pesquisarContrato('{$lanc[i].PEDIDO}');"
                                                        title="Acompanhamento do Contrato">
                                                        <span class="glyphicon glyphicon-stats"></span>
                                                    </button>
                                                </div>
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

<!-- Modal de Seleção de Período para Acompanhamento -->
<div class="modal fade" id="modalSelecaoPeriodo" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width: 40% !important; margin-top: 12% !important;">
        <div class="modal-content">
            <div class="modal-header" style="padding: 8px 12px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title" style="margin: 0; font-size: 13px;">Período</h6>
            </div>
            <div class="modal-body" style="padding: 12px;">
                <div class="form-group" style="margin-bottom: 8px;">
                    <input type="text" name="dataConsultaModal" id="dataConsultaModal" 
                           class="form-control input-sm" placeholder="Selecione o período" 
                           style="font-size: 11px; height: 28px;">
                </div>
            </div>
            <div class="modal-footer" style="padding: 8px 12px; text-align: right;">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-xs" onclick="confirmarPeriodoContrato()">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

{include file="modal_contrato.tpl"}

{include file="template/database.inc"}
<script type="text/javascript" src="{$pathJs}/ped/s_contrato.js"></script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- daterangepicker -->
<script type="text/javascript">
    $('#lancamento #dataConsulta').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
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
                    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                firstDay: 1
            }

        },

        //funcao para recuperar o valor digirado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        });

    $('#dataConsultaModal').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                firstDay: 1
            }
        });
</script>

<script type="text/javascript">
    $(function() {
        $('#modalAcompanhamentoOS input[name="data_inicio"]').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                showDropdowns: true,
                timePicker24Hour: true,
                autoApply: true,
                startDate: moment(),
                locale: {
                    format: 'DD/MM/YYYY HH:mm',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ]
                }
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            })
            .on('hide.daterangepicker', function(ev, picker) {
                if (!$(this).val()) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
                }
            });

        $('#modalAcompanhamentoOS input[name="prazo_entrega"]').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                showDropdowns: true,
                timePicker24Hour: true,
                autoApply: true,
                startDate: moment(),
                locale: {
                    format: 'DD/MM/YYYY HH:mm',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ]
                }
            })
            .on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            })
            .on('hide.daterangepicker', function(ev, picker) {
                if (!$(this).val()) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
                }
            });

        $("#usuario_equipe.select2-multiple").select2({
            placeholder: "Selecione",
            allowClear: true,
            width: "100%"
        });

        $("#equipe.js-example-basic-single").select2({
            placeholder: "Selecione",
            allowClear: true,
            width: "100%"
        });

        function atualizarEstadoUsuarioEquipe() {
            var equipeSelecionada = $("#equipe.js-example-basic-single").val();
            var usuarioEquipeSelect = $("#usuario_equipe.select2-multiple");

            if (equipeSelecionada && equipeSelecionada !== '0' && equipeSelecionada !== '') {
                usuarioEquipeSelect.prop("disabled", false);
            } else {
                usuarioEquipeSelect.prop("disabled", true);
            }
        }

        atualizarEstadoUsuarioEquipe();

        $("#equipe.js-example-basic-single").on('change', function() {
            atualizarEstadoUsuarioEquipe();
        });

        $(document).on('keydown',
            '#modalResultServicos input[type="checkbox"], #modalResultServicos input[id^="qtdAExecutar_"]',
            function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if ($(this).is(':checkbox')) {
                        $(this).prop('checked', !$(this).prop('checked')).trigger('change');
                    }

                    let $focusable = $(
                        '#modalResultServicos input[type="checkbox"], #modalResultServicos input[id^="qtdAExecutar_"]'
                    );
                    let index = $focusable.index(this);
                    let $next = $focusable.eq(index + 1);

                    if ($next.length) {
                        $next.focus();
                    } else {
                        $focusable.first().focus();
                    }
                }
            });

        $(document).on('focus', '#modalResultServicos input[readonly]', function() {
            $(this).blur();
        });

        $('#modalAcompanhamentoOS').on('hidden.bs.modal', function() {
            limpaModal();
        });
    });
</script>

<script>
    const urlBase = "{$pathCliente}";
</script>