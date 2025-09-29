<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_relatorio_gerencial.js"></script>
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Relatório Gerencial</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form name="lancamento" action="{$SCRIPT_NAME}" method="post" class="form-horizontal">
                            <input name="opcao" type="hidden" value="">
                            <input name="submenu" type="hidden" value="consolidacao">
                            <input name="letra" type="hidden" value={$letra}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>


                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>

                                <div class="form-group col-md-12 text-center">
                                    <button type="button" class="btn btn-success" onclick="submitBaixar();">
                                        <i class="fa fa-download"></i> Baixar Relatório
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="template/database.inc"}
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker({
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
</script>