<style>
    .daterangepicker {
        z-index: 9999 !important;
    }

    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    #data_consulta,
    #pessoa_nome,
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

    /* Estilo para campos múltiplos */
    .select2-container--default .select2-selection--multiple {
        max-height: 30px !important;
        overflow-y: auto !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin: 2px !important;
        padding: 2px 8px !important;
        border: none !important;
        border-radius: 3px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        margin-right: 5px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffcccc !important;
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

                <form id="form_report">
                    <!-- Campos hidden para datas -->
                    <input type="hidden" id="dataIni" name="dataIni" value="{$dataIni}">
                    <input type="hidden" id="dataFim" name="dataFim" value="{$dataFim}">
                    <input type="hidden" id="tipoRelatorio" name="tipoRelatorio" value="">

                    <div class="row">
                        
                        <!-- Período -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="periodo_container">
                            <label class="">Período</label>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <div>
                                <input type="text" name="data_consulta" id="data_consulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>

                        <!-- Data de Referência -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Data de Referência</label>
                            <select class="form-control" id="referencia" name="referencia">
                                {html_options values=$data_referencia_ids output=$data_referencia_names}
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Tipo de Lançamento -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Tipo de Lançamento</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="tipolanc" name="tipolanc[]">
                                {html_options values=$tipo_lancamento_ids output=$tipo_lancamento_names}
                            </select>
                        </div>

                        <!-- Situação do Lançamento -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Situação do Lançamento</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="sitlanc" name="sitlanc[]">
                                {html_options values=$situacao_lancamento_ids output=$situacao_lancamento_names}
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Situação do Documento -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Situação do Documento</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="sitdocto" name="sitdocto[]">
                                {html_options values=$situacao_documento_ids output=$situacao_documento_names}
                            </select>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Tipo de Documento</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="tipoDocto" name="tipoDocto[]">
                                {html_options values=$tipo_documento_ids output=$tipo_documento_names}
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Conta Bancária -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Conta Bancária</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="conta" name="conta[]">
                                {html_options values=$conta_bancaria_ids output=$conta_bancaria_names}
                            </select>
                        </div>

                        <!-- Centro de Custo (Filial) -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Centro de Custo</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial[]">
                                {html_options values=$centro_custo_ids output=$centro_custo_names}
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cliente/Fornecedor -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Cliente/Fornecedor</label>
                            <select class="form-control" id="pessoa" name="pessoa">
                                <option value="">Selecione...</option>
                            </select>
                        </div>

                        <!-- Gênero -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Gênero</label>
                            <select class="form-control" id="genero" name="genero">
                                <option value="">Selecione...</option>
                            </select>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="javascript:Cancelar();">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="javascript:limparCampos();">Limpar Campos</button>
                <button type="button" class="btn btn-primary" onclick="javascript:generateReport();">Gerar Relatório</button>
            </div>

        </div>
    </div>
</div>

{include file="template/database.inc"}
<!-- /Datatables -->

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>


<script type="text/javascript">
    $('input[name="data_consulta"]').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'De',
                toLabel: 'Até',
                customRangeLabel: 'Personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            },
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        },
        function(start, end) {
            $('input[name="data_consulta"]').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            $('#dataIni').val(start.format('DD/MM/YYYY'));
            $('#dataFim').val(end.format('DD/MM/YYYY'));
        });

    $(document).ready(function() {
        // Inicializar Select2 para campos múltiplos
        $('.select2_multiple').select2({
            placeholder: "Selecione...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalParametros')
        });

        // Inicializar Select2 para Cliente/Fornecedor com AJAX
        initSelect2Pessoas();
        
        // Inicializar Select2 para Gênero com AJAX
        initSelect2Genero();
        
        // Reinicializar Select2 após fechar e abrir o modal
        $('#modalParametros').on('shown.bs.modal', function() {
            // Garantir que os Select2 estejam funcionando
            $('.select2_multiple').select2({
                placeholder: "Selecione...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modalParametros')
            });
        });

        // Atualizar nome do relatório no modal
        $('#modalParametros').on('show.bs.modal', function (e) {
            var button = $(e.relatedTarget);
            var nomeRelatorio = button.data('relatorio-nome');
            if (nomeRelatorio) {
                $('#nomeRelatorio').text(nomeRelatorio);
            }
        });
    });
</script>
