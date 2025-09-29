<div class="modal fade" id="modalAcompanhamentoOS" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Acompanhamento de OS - Contrato: <span id="id_pedido">{$id_pedido}</span>
                </h4>
                <div style="position: absolute; top: 10px; right: 10px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>

            <!-- Abas -->
            <div class="modal-body">
                <input type="hidden" id="id_os" value="{$id_os}">

                <!-- Estrutura de abas seguindo o padrão do exemplo -->
                <div role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#os_contrato" id="os-contrato-tab" role="tab" data-toggle="tab"
                                aria-expanded="true"
                                onclick="controlFunctionModal(document.getElementById('id_os').value)">Ordem de Serviço
                                do Contrato</a>
                        </li>
                        <li role="presentation" class="">
                            <a href="#os_cadastradas" role="tab" id="os-cadastradas-tab" data-toggle="tab"
                                aria-expanded="false">Ordem de Serviço Cadastradas</a>
                        </li>
                    </ul>
                </div>

                <!-- Conteúdo das abas -->
                <div id="myTabContent" class="tab-content" style="font-size: 12px;">
                    <!-- Aba OS Contrato -->
                    <div role="tabpanel" class="tab-pane fade active in" id="os_contrato"
                        aria-labelledby="os-contrato-tab">
                        <div class="row" style="margin-top: 20px;">
                            <!-- Primeira linha -->
                            <div class="col-md-6 col-sm-4 col-xs-12 form-group">
                                <label>Cliente</label>
                                <input type="text" id="nome_cliente_input" value="" class="form-control" readonly>
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                                <label class>Data Início servico</label>
                                <input type="text" id="data_inicio" name="data_inicio" value="{$data_inicio}"
                                    class="form-control data_inicio">
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                                <label>Data Finaliza servico</label>
                                <input type="text" id="prazo_entrega" name="prazo_entrega" value="{$prazo_entrega}"
                                    class="form-control data_finalizacao">
                            </div>

                            <!-- Segunda linha -->
                            <div class="col-md-5 col-sm-5 col-xs-12 form-group">
                                <label>Equipe</label>
                                <select id="equipe" name="equipe" class="input-sm js-example-basic-single form-control"
                                    title="Equipe" alt="Equipe">
                                    {html_options values=$equipe_ids selected=$equipe output=$equipe_names}
                                </select>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12 form-group">
                                <label>Usuários da Equipe</label>
                                <select id="usuario_equipe" name="usuario_equipe" class="form-control select2-multiple"
                                    multiple="multiple" title="Usuários da Equipe">
                                    {html_options values=$usuario_equipe_ids selected=$usuario_equipe output=$usuario_equipe_names}
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <button type="button" class="btn btn-primary btn-block" onclick="gerarOs()">Gerar
                                    OS</button>
                            </div>

                            <!-- Observações -->
                            <div class="textarea col-md-12 col-sm-12 col-xs-12 form-group">
                                <label>Observações</label>
                                <textarea name="obs_servico" class="form-control" rows="2">{$obs_servico}</textarea>
                            </div>

                            <!-- Tabela de serviços -->
                            <table class="table table-bordered" id="formOs">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;"></th>
                                        <th>Descrição Serviços</th>
                                        <th>Contratada</th>
                                        <th style="font-size: 10px;">% Executado</th>
                                        <th>Executada</th>
                                        <th>A Executar</th>
                                    </tr>
                                </thead>
                                <tbody id="modalResultServicos"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Aba OS Cadastradas -->
                    <div role="tabpanel" class="tab-pane fade" id="os_cadastradas" aria-labelledby="os-cadastradas-tab">
                        <table class="table table-bordered" id="formOs">
                            <thead>
                                <tr>
                                    <th>Ordem Servico</th>
                                    <th>Data inicio servico</th>
                                    <th>Data finalizacao servico</th>
                                    <th>Equipe</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody id="modalResultOsCadastradas"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('input[name="data_inicio"]').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "showDropdowns": true,
                "timePicker24Hour": true,
                "autoApply": true,
                "startDate": moment(),
                "locale": {
                    "format": 'DD/MM/YYYY HH:mm',
                    "daysOfWeek": ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    "monthNames": [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }
            })
            .on('apply.daterangepicker', function(ev, picker) {
                // Atualiza manualmente o input quando aplicar
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            })
            .on('hide.daterangepicker', function(ev, picker) {
                // Impede que o valor seja apagado ao fechar sem aplicar
                if (!$(this).val()) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
                }
            });
    });


    $(function() {
        $('input[name="prazo_entrega"]').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "showDropdowns": true,
                "timePicker24Hour": true,
                "autoApply": true,
                "startDate": moment(),
                "locale": {
                    "format": 'DD/MM/YYYY HH:mm',
                    "daysOfWeek": ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    "monthNames": [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }
            })
            .on('apply.daterangepicker', function(ev, picker) {
                // Atualiza manualmente o input quando aplicar
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            })
            .on('hide.daterangepicker', function(ev, picker) {
                // Impede que o valor seja apagado ao fechar sem aplicar
                if (!$(this).val()) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
                }
            });
    });
</script>
<script>
    $(document).ready(function() {
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

        // Função para atualizar o estado do select de usuários
        function atualizarEstadoUsuarioEquipe() {
            var equipeSelecionada = $("#equipe.js-example-basic-single").val();
            var usuarioEquipeSelect = $("#usuario_equipe.select2-multiple");

            if (equipeSelecionada && equipeSelecionada !== '0' && equipeSelecionada !== '') {
                usuarioEquipeSelect.prop("disabled", false); // Habilita o select
            } else {
                usuarioEquipeSelect.prop("disabled", true); // Desabilita o select
            }
        }

        // Chame a função inicialmente para definir o estado com base no valor inicial
        atualizarEstadoUsuarioEquipe();

        // Observe as mudanças no select de equipe
        $("#equipe.js-example-basic-single").on('change', function() {
            atualizarEstadoUsuarioEquipe();
        });

        // Handler para tecla Enter nos checkboxes e campos de quantidade - (DEV - CURSOR)
        $(document).on('keydown',
            '#modalResultServicos input[type="checkbox"], #modalResultServicos input[id^="qtdAExecutar_"]',
            function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if ($(this).is(':checkbox')) {
                        $(this).prop('checked', !$(this).prop('     ')).trigger('change');
                    }

                    // Encontra o próximo elemento tabulável
                    let $focusable = $(
                        '#modalResultServicos input[type="checkbox"], #modalResultServicos input[id^="qtdAExecutar_"]'
                        );
                    let index = $focusable.index(this);
                    let $next = $focusable.eq(index + 1);

                    if ($next.length) {
                        $next.focus();
                    } else {
                        // Volta para o primeiro elemento se chegou ao fim
                        $focusable.first().focus();
                    }
                }
            });

        // Desabilitar tab para campos readonly
        $(document).on('focus', '#modalResultServicos input[readonly]', function(e) {
            $(this).blur();
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#modalAcompanhamentoOS').on('hidden.bs.modal', function() {
            limpaModal();
        });
    });
</script>