<style>
    .daterangepicker {
        z-index: 9999 !important;
    }

    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    #data_consulta,
    #cliente_nome,
    .select2-selection--multiple,
    .select2-selection__choice {
        border-radius: 5px !important;
    }

    #data_consulta {
        text-align: center;
    }

    #idbtnCancelar {
        margin-bottom: 0px !important;
    }

    .disabled {
        pointer-events: none;
        opacity: 3;
    }

    .select2-search__field {
        width: 41vh !important;
    }

    .form-group-container {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .form-group-col {
        display: none;
        flex: 1 0 calc(33.33% - 20px);
        min-width: 250px;
        padding: 0 10px;
        box-sizing: border-box;
        margin-bottom: 15px;
    }

    .form-group-col:only-child {
        flex: 0 0 calc(100% - 20px);
    }

    .form-group-col:nth-last-child(2):first-child,
    .form-group-col:nth-last-child(2):first-child~.form-group-col {
        flex: 0 0 calc(50% - 20px);
    }

    .daterangepicker {
        z-index: 9999 !important;
    }
</style>

<!-- Modal -->

<div class="modal fade" id="modalParametros" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Parâmetros - <span id="nomeRelatorio"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="form_report" target="_blank" data-parsley-validate METHOD="POST" ACTION={$SCRIPT_NAME}>
                    <div class="form-group-container">


                        <!-- Representante -->
                        <div class="form-group form-group-col col-md-4" id="usuario-group">
                            <label>Usuario/Usuarios Equipe</label>
                            <SELECT class="form-control" id="usuario" name="usuario">
                                {html_options values=$usuario_ids output=$usuario_names selected=$usuario_id}
                            </SELECT>
                        </div>



                        <!-- Serviços -->
                        <div class="form-group form-group-col col-md-4" id="servico-group">
                            <label>Serviços</label>
                            <SELECT class="form-control" id="id_servico" name="id_servico">
                                {html_options values=$id_servico_ids output=$id_servico_names selected=$id_servico_id}
                            </SELECT>
                        </div>

                        <!-- Equipamento/Equipe -->
                        <div class="form-group form-group-col col-md-4" id="equipamento-group">
                            <label>Equipamento/Equipe</label>
                            <SELECT class="form-control" id="equipamento" name="equipamento">
                                {html_options values=$equipamento_ids output=$equipamento_names selected=$equipamento_id}
                            </SELECT>
                        </div>

                        <!-- Status -->
                        <div class="form-group form-group-col col-md-4" id="status-group">
                            <label>Status</label>
                            <SELECT class="form-control" id="id_status" name="id_status">
                                {html_options values=$id_status_ids output=$id_status_names selected=$id_status_id}
                            </SELECT>
                        </div>

                        <!-- Centro Custo -->
                        <div class="form-group form-group-col col-md-4" id="centro-custo-group">
                            <label>Centro Custo</label>
                            <SELECT class="form-control" id="centro_custo" name="centro_custo">
                                {html_options values=$centro_custo_ids output=$centro_custo_names selected=$centro_custo_id}
                            </SELECT>
                        </div>
                        <!-- Período -->
                        <div class="form-group form-group-col col-md-4" id="periodo-group">
                            <label class="">Período</label>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <div>
                                <input type="text" name="data_consulta" id="data_consulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>

                        <!-- Nome -->
                        <div class="form-group form-group-col col-md-4" id="pedido-group">
                            <label>Contrato/Pedido</label>
                            <input class="form-control" id="num_pedido" name="num_pedido" placeholder="Digite o numero."
                                value={$num_pedido}>
                        </div>

                        <!-- OS -->
                        <div class="form-group form-group-col col-md-4" id="os-group">
                            <label>Ordem Serviço</label>
                            <input class="form-control" type="text" id="num_os" name="num_os" placeholder="Digite a OS."
                                value={$num_os}>
                        </div>
                        <div class="form-group form-group-col col-md-4" id="cliente-group">
                            <label class="">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="cliente_nome" name="cliente_nome"
                                    placeholder="Pessoa" value="{$cliente_nome}">
                                <input type="hidden" id="cliente_id" name="cliente_id" value="{$cliente_id}">
                                <span class="input-group-btn">
                                    <button id="buscaCliente" type="button" class="btn btn-primary"
                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarRelatorios');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" id="idbtnCancelar" class="btn btn-secondary"
                    onclick="Cancelar()">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="limparCampos()">Limpar Campos</button>
                <button type="button" class="btn btn-primary"
                    onclick="generateReport(document.getElementById('report').value)">Gerar Relatório</button>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<script type="text/javascript">
    $('input[name="data_consulta"]').daterangepicker({
            startDate: moment("{$data_ini}", "DD/MM/YYYY"),
            endDate: moment("{$data_fim}", "DD/MM/YYYY"),
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

        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        });
</script>

<script>
    $('#modalParametros').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const nomeRelatorio = button.data('relatorio-nome');
        $(this).find('#nomeRelatorio').text(nomeRelatorio);
    });
</script>

<script>
    $(document).ready(function() {
        $("#idVendedor.select2_single").select2({
            placeholder: "Escolha o Vendedor",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#idAtividade.select2_single").select2({
            placeholder: "Escolha a Atividade",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#idClasse.select2_single").select2({
            placeholder: "Escolha a Classe",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#idPessoa.select2_single").select2({
            placeholder: "Tipo Pessoa",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#idFilial.select2_single").select2({
            placeholder: "Escolha a Filial",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#idEstado.select2_single").select2({
            placeholder: "Escolha o Estado",
            allowClear: true,
            width: "100%"
        });
    });
</script>
<script>
    // Função para selecionar quais campos devem ser exibidos
    function showFormFields(fieldIds) {
        // Esconde os campos como padrão
        $('.form-group-col').hide();

        // Mostra apenas os campos especificados
        fieldIds.forEach(function(id) {
            $('#' + id + '-group').show();
        });
    }

    // captura o relatorio de acordo com o botão
    $('#modalParametros').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const nomeRelatorio = button.data('relatorio-nome');
        $(this).find('#nomeRelatorio').text(nomeRelatorio);

        const onclickAttr = button.attr('onclick');
        if (onclickAttr) {
            const match = onclickAttr.match(/controlInputs\('([^']+)'\)/);
            if (match && match[1]) {
                controlInputs(match[1]);
            }
        }
    });
</script>