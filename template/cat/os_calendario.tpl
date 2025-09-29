<style>
    #calendar {
        margin: 0 0 0 0;
    }
    #titleCalendario{
        font-size: 20px;
        text-align: center;
        margin-bottom: 0 !important;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
    }
    #data_inicio,
    #data_finalizacao, 
    #equipe {
        font-weight: bold;
    }
    #data_inicio,
    #data_finalizacao {
        text-align: center;
    }

    .show-calendar {
        z-index: 1000000 !important;
    }

    .modal-footer .btn+.btn {
        margin-bottom: 6px !important;
        margin-left: 5px !important;
    }

    .label_header {
        height: 65px !important;
    }

    #form_edit {
        margin-bottom: -27px;
    }

    .select2-selection--multiple {
        border-radius: 0px 5px 5px 0px !important;
    }

    .select2 {
        width: 100% !important;
    }

    .hidden {
        opacity: 0;
        transition: opacity 0.9s ease-out;
    }
</style>
<div class="right_col" role="main">
    <div class="">
        <html>

        <head>
            <meta charset='utf-8' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <link href='{$pathRaiz}/css/core/main.min.css' rel='stylesheet' />
            <link href='{$pathRaiz}/css/daygrid/main.min.css' rel='stylesheet' />
            <script src='{$pathJsCalendar}/core/main.min.js'></script>
            <script src='{$pathJsCalendar}/interaction/main.min.js'></script>
            <script src='{$pathJsCalendar}/daygrid/main.min.js'></script>
            <script src='{$pathJsCalendar}/timegrid/main.min.js'></script>
            <script src='{$pathJsCalendar}/list/main.min.js'></script>
            <script src='{$pathJsCalendar}/core/locales/pt-br.js'></script>
            <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
            <script src='{$pathJs}/cat/s_os_calendario.js'></script>

        </head>

        <body>
            <input name=user_id  type=hidden value="{$user_id}">

            <div class="row">
                <div class="col-md-12">
                    <p id="titleCalendario">Calendario Ordem de servico</p>
                </div>
            </div>

            <div class="row" {if $vertodoslancamentos == false}hidden{/if}>

                <div class="col-md-5 col-xs-5 col-sm-12 justify-content-end">
                    <div id="divVend" class="input-group">
                        <label for="vendedor" style="cursor:not-allowed; font-size:13px;"
                            class="input-group-addon">Vendedor</label>
                        <SELECT {if ($vertodoslancamentos )} enable {else} disabled {/if}
                            class="select2_multiple form-control" multiple="multiple" id="vendedor" name="vendedor">
                            {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                        </SELECT>
                    </div>
                </div>

            </div>

            <div id='calendar'></div>

            <!--Modal de Edição de Registro -->
            <div id="modalOrdemServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="form_edit" name="form_edit" class="form-horizontal calender" role="form">
                            <div class="modal-header label_header">
                                <div class="col-md-10 col-sm-4 col-xs-12">
                                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                    <h4 class="modal-title" id="myModalLabel">Ordem de servico</h4>
                                </div>

                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <div class="panel panel-default id">
                                        <input type="text" readonly class="form-control" id="id"
                                            name="id" autocomplete="off" value="{$id}" style="text-align: center;">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-body">
                                <div id="" style="padding: 5px 20px;">

                                    <!-- Linha 1 -->
                                    <div class="row row-md-12 row-sm-12 row-xs-12">
                                        <!--<div class="col-sm-1 offset-sm-1"></div>-->

                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <label for="cliente_descricao">Cliente</label>
                                            <div class="panel panel-default cliente_descricao">
                                                <input type="text" readonly class="form-control" id="cliente_descricao"
                                                    name="cliente_descricao" autocomplete="off" value="{$cliente_descricao}">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <label for="situacao_descricao">Situacao</label>
                                            <div class="panel panel-default situacao_descricao">
                                                <input type="text" readonly class="form-control" id="situacao_descricao"
                                                    name="situacao_descricao" autocomplete="off" value="{$situacao_descricao}">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Linha 2 -->
                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <label for="acao">Equipe</label>
                                            <div class="panel panel-default equipe">
                                                <SELECT class="form-control" id="equipe" name="equipe">
                                                    {html_options values=$equipe_ids output=$equipe_names selected=$equipe_id}
                                                </SELECT>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="data_inicio">Data inicio</label>
                                            <div class="panel panel-default data_finalizacao"> 
                                                <input class="form-control" id="data_inicio" title="Data de inicio" 
                                                    name="data_inicio" value="{$data_inicio}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="data_finalizacao">Data finalizacao</label>
                                            <div class="panel panel-default data_finalizacao"> 
                                                <input class="form-control" id="data_finalizacao" title="Data de finalizacao" 
                                                    name="data_finalizacao" value="{$data_finalizacao}">
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="desc_servico">Observacao Servico</label>
                                            <div class="panel panel-default panel-descricao">
                                                <textarea class="form-control" style="height:80px;" id="desc_servico"
                                                    name="desc_servico" autocomplete="off" value={$desc_servico}></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                    </div>

                                </div>
                            </div>

                        </form>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary antosubmit"
                                onClick="javascript:submitUpdateOrderService();">Atualizar</button>
                        </div>

                    </div>
                </div>
                <!--FIM Modal de Edicao de Registro -->
        </body>

        </html>
    </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
</div> <!-- div class="row "-->
{include file="template/form.inc"}
<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#equipe.select2_multiple").select2({
            allowClear: true,
            maximumSelectionLength: 1,
            width: "90%",
            language: {
                maximumSelected: function() {
                    return "Você pode selecionar apenas 1 vendedor";
                }
            }
        });

    });
</script>

<script>
$(function() {
    $('#modalOrdemServico #data_inicio').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "showDropdowns": true,
        "timePicker24Hour": true,
        "autoUpdateInput": false, // Desativa atualização automática
        "autoApply": true,
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
    $('#modalOrdemServico #data_finalizacao').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "showDropdowns": true,
        "timePicker24Hour": true,
        "autoUpdateInput": false, // Desativa atualização automática
        "autoApply": true,
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