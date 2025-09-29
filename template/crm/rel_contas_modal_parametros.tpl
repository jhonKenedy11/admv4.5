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

    {*responsavel pelo placeholder dentro dos selects para nao cortar*}
    .select2-search__field {
        width: 41vh !important;
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

                    <div class="row">
                        <!-- Nome -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>Nome</label>
                            <input class="form-control" id="pesNome" name="pesNome"
                                placeholder="Digite o nome do Pessoa." value={$pesNome}>
                        </div>

                        <!-- CNPJ/CPF -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>CNPJ/CPF</label>
                            <input class="form-control" type="text" id="pesCnpjCpf" name="pesCnpjCpf"
                                placeholder="Digite o CNPJ/CPF." value={$pesCnpjCpf}>
                        </div>

                        <!-- Período -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6" id="periodo_container">
                            <label class="">Período</label>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <div>
                                <input type="text" name="data_consulta" id="data_consulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cidade -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>Cidade</label>
                            <input class="form-control" type="text" id="pesCidade" name="pesCidade"
                                placeholder="Digite a cidade." value={$cidade}>
                        </div>

                        <!-- Estado -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>Estado</label>
                            <SELECT class="form-control" id="idEstado" name="idEstado">
                                {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                            </SELECT>
                        </div>

                        <!-- Filial -->
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>Filial</label>
                            <SELECT class="form-control" id="idFilial" name="idFilial">
                                {html_options values=$filial_ids output=$filial_names selected=$filial_id}
                            </SELECT>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        <!-- Tipo Pessoa -->
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label>Tipo Pessoa</label>
                            <SELECT class="form-control" id="idPessoa" name="idPessoa">
                                {html_options values=$tipoPessoa_ids output=$tipoPessoa_names selected=$tipoPessoa_id}
                            </SELECT>
                        </div>

                        <!-- Classe -->
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label>Classe</label>
                            <SELECT class="form-control" id="idClasse" name="idClasse">
                                {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                            </SELECT>
                        </div>

                        <!-- Atividade -->
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label>Atividade</label>
                            <SELECT class="form-control" id="idAtividade" name="idAtividade">
                                {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                            </SELECT>
                        </div>


                        <!-- Representante -->
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label>Representante</label>
                            <SELECT class="form-control" id="idVendedor" name="idVendedor">
                                {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                            </SELECT>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" id="idbtnCancelar" class="btn btn-secondary"
                    onclick="Cancelar()">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="limparCampos()">Limpar Campos</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">Gerar Relatório</button>
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

<!-- daterangepicker -->
<script type="text/javascript">
    $('input[name="data_consulta"]').daterangepicker({
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

        //funcao para recuperar o valor digitado        
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