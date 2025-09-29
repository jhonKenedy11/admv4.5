<style>
    .daterangepicker {
        z-index: 9999 !important;
    }

    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    #data_consulta,
    #produto_nome,
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

    {*Estilo para o campo de produtos múltiplos*}
    #idProduto {
        min-height: 100px !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 100px !important;
        max-height: 200px !important;
        overflow-y: auto !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        margin: 2px !important;
        padding: 2px 8px !important;
        background-color: #337ab7 !important;
        color: white !important;
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
                        
                        <!-- Cliente/Fornecedor -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label>Cliente/Fornecedor</label>
                            <select class="form-control" id="clienteFornecedor" name="clienteFornecedor">
                                <option value="">Todos</option>
                                <!-- Será preenchido via AJAX -->
                            </select>
                        </div>
                        <!-- Período -->
                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="periodo_container">
                            <label class="">Período</label>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <div>
                                <input type="text" name="data_consulta" id="data_consulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Grupo -->
                        <div class="form-group col-md-4 col-sm-4 col-xs-4">
                            <label>Grupo</label>
                            <select class="form-control" id="idGrupo" name="idGrupo">
                                {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                            </select>
                        </div>

                        <!-- Localização -->
                        <div class="form-group col-md-4 col-sm-4 col-xs-4">
                            <label>Localização</label>
                            <select class="form-control" id="idLocalizacao" name="idLocalizacao">
                                {html_options values=$localizacao_ids output=$localizacao_names selected=$localizacao_id}
                            </select>
                        </div>

                        <!-- Tipo de Movimento -->
                        <div class="form-group col-md-4 col-sm-4 col-xs-4">
                            <label>Tipo de Movimento</label>
                            <select class="form-control" id="tipoMovimento" name="tipoMovimento">
                                <option value="">Todos</option>
                                <option value="0" {if $tipo_id == 0}selected{/if}>Entrada</option>
                                <option value="1" {if $tipo_id == 1}selected{/if}>Saída</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Centro de Custo -->
                        <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label>Centro de Custo</label>
                            <select class="form-control" id="centroCusto" name="centroCusto">
                                {html_options values=$centro_custo_ids output=$centro_custo_names}
                            </select>
                        </div>

                        <!-- Situação da NF -->
                        <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label>Situação da NF</label>
                            <select class="form-control" id="situacaoNF" name="situacaoNF">
                                <option value="">Todas</option>
                                <option value="A">Aberto</option>
                                <option value="B">Baixada</option>
                                <option value="C">Cancelada</option>
                            </select>
                        </div>

                        <!-- Tipo de Curva ABC -->
                        <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label>
                                Tipo de Curva ABC 
                                <i class="fa fa-info-circle" data-toggle="tooltip" title="Define como os produtos serão classificados na Curva ABC"></i>
                            </label>
                            <select class="form-control" id="tipoCurvaABC" name="tipoCurvaABC">
                                <option value="1">Por Valor Total (R$)</option>
                                <option value="2">Por Quantidade</option>
                                <option value="3">Por Frequência de Vendas</option>
                                <option value="4">Por Preço Unitário</option>
                            </select>
                        </div>

                        <!-- Ordenação -->
                        <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label>Ordenação</label>
                            <select class="form-control" id="ordenacaoEstoque" name="ordenacaoEstoque">
                                <option value="descricao">Por Descrição</option>
                                <option value="grupo">Por Grupo</option>
                                <option value="localizacao">Por Localização</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Produto -->
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label>Produto(s)</label>
                            <SELECT class="form-control" id="idProduto" name="idProduto[]" multiple>
                                <option></option>
                            </SELECT>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" id="idbtnCancelar" class="btn btn-secondary" onclick="Cancelar()">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="javascript:limparCampos()">Limpar Campos</button>
                <button type="button" class="btn btn-primary" onclick="javascript:generateReport()">Gerar Relatório</button>
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
            "opens": "left",
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
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
            // Atualizar os campos hidden do formulário
            $('#dataIni').val(start.format('DD/MM/YYYY'));
            $('#dataFim').val(end.format('DD/MM/YYYY'));
        });

    $(document).ready(function() {
        // Inicializar select2 para produtos e clientes
        initSelect2Produtos();
        initSelect2Clientes();
    });

    $(document).ready(function() {
        $('#modalParametros').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const nomeRelatorio = button.data('relatorio-nome');
            $(this).find('#nomeRelatorio').text(nomeRelatorio);
        });
    });
</script> 