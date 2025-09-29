<style>
    .daterangepicker {
        z-index: 9999 !important;
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
        <!-- Classe modal-lg para tamanho grande -->
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
                        <div class="form-group col-md-3 col-sm-3 col-xs-3" id="periodo_container">
                            <label class="">Período</label>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <div>
                                <input type="text" name="data_consulta" id="data_consulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>

                        <div class="form-group col-md-9 col-sm-9 col-xs-9" id="cliente_container">
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
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 w-100 h-100 d-flex flex-column"
                            id="desc_produto">
                            <label class="">Produto</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="descProduto" name="descProduto"
                                    placeholder="Produto" value="{$descProduto}">
                                <input type="hidden" id="codProduto" name="codProduto" value="{$codProduto}">

                                <span class="input-group-btn">
                                    <button id="buscaProduto" type="button" class="btn-sm btn-primary"
                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas');">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="situacao_container">
                            <label>Situação</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="situacao"
                                name="situacao">
                                {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                            </select>
                        </div>

                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="centro_custo_container">
                            <label for="centro_custo">Centro de Custo</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="centro_custo"
                                name="centro_custo">
                                {html_options values=$centro_custo_ids output=$centro_custo_names selected=$centro_custo_id}
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="motivo-venda-container">
                            <label for="motivo">Venda Perdida - Motivo</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="motivo" name="motivo">
                                {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                            </select>
                        </div>


                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="vendedor_container">
                            <label for="vendedor">Vendedor</label>
                            <select class="select2_multiple form-control" multiple="multiple" id="vendedor"
                                name="vendedor">
                                {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="condicao_pagamento_container">
                            <label for="condicao_pagamento">Condição de Pagamento</label>
                            <SELECT class="select2_multiple form-control" multiple="multiple" id="condicao_pagamento"
                                name="condicao_pagamento">
                                {html_options values=$condicao_pagamento_ids output=$condicao_pagamento_names selected=$condicao_pagamento_id}
                            </SELECT>
                        </div>

                        <div class="form-group col-md-6 col-sm-6 col-xs-6" id="tipo_entrega_container">
                            <label for="tipo_entrega">Tipo Entrega</label>
                            <SELECT class="select2_multiple form-control" multiple="multiple" id="tipo_entrega"
                                name="tipo_entrega">
                                {html_options values=$tipo_entrega_ids output=$tipo_entrega_names selected=$tipo_entrega_id}
                            </SELECT>
                        </div>

                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="Cancelar()">Cancelar</button>
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
        //funcao para recuperar o valor digirado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        });

    $(document).ready(function() {
        $("#motivo.select2_multiple").select2({
            placeholder: "Escolha o Motivo",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#situacao.select2_multiple").select2({
            placeholder: "Escolha a situacao do pedido",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#centro_custo.select2_multiple").select2({
            placeholder: "Escolha o centro de custo",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#vendedor.select2_multiple").select2({
            placeholder: "Escolha o vendedor",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#condicao_pagamento.select2_multiple").select2({
            placeholder: "Escolha o condicao de pagamento",
            allowClear: true,
            width: "100%"
        });
    });

    $(document).ready(function() {
        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Escolha o tipo de entrega",
            allowClear: true,
            width: "100%"
        });
    });
    $(document).ready(function() {
        $('#modalParametros').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const nomeRelatorio = button.data('relatorio-nome');
            $(this).find('#nomeRelatorio').text(nomeRelatorio);
        });
    });
</script>