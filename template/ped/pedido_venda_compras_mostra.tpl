<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_compras.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Pedido - Compras
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra('');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value={$form}>
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>

                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label>Pedido</label>
                                <select class="form-control" id="pedido" name="pedido">
                                    {html_options values=$pedido_ids output=$pedido_names selected=$pedido_id}
                                </select>
                            </div>

                            <!--div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                value="{$dataIni} - {$dataFim}">
                            </div>
                        </div-->

                        </form>


                    </div>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->

            <!-- panel tabela dados -->
            <div class="responsive">
                <div class="x_panel">
                    <!--table id="datatable-buttons" class="table table-bordered jambo_table"-->
                    <table class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr class="headings">
                                <th>Item</th>
                                <th>Conta</th>
                                <th>Data</th>
                                <th>Situa&ccedil;&atilde;o</th>
                                <th>Total</th>
                                <th>Progresso</th>
                                <th style="width: 120px;">Manuten&ccedil;&atilde;o</th>

                            </tr>
                        </thead>
                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                {assign var="perc" value={$lanc[i].SITUACAO*20}+20}
                                <tr>
                                    <td> {$lanc[i].PEDIDO} </td>
                                    <td> {$lanc[i].NOMEREDUZIDO} </td>
                                    <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                    <td> {$lanc[i].PADRAO} </td>
                                    <td align=right> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" role="progressbar"
                                                aria-valuenow="{$perc}" aria-valuemin="0" aria-valuemax="100"
                                                style="min-width: 2em; width: {$perc}%;">
                                                {$perc}%
                                            </div>
                                        </div>

                                    </td>


                                    <td>
                                        <button type="button" class="btn btn-primary btn-xs"
                                            onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span
                                                class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                                class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-info btn-xs"
                                            onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span
                                                class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                                <p>
                                {/section}

                        </tbody>
                    </table>
                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
    </div> <!-- div class="row "-->
</div> <!-- class='' = controla menu user -->

<!-- /Datatables -->
{include file="template/database.inc"}


<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#situacao.select2_multiple").select2({
            placeholder: "Escolha a Situação",
            allowClear: true
        });

    });
</script>

<!-- daterangepicker -->
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
<!-- /daterangepicker -->