<style>
.form-control, .x_panel{
    border-radius: 5px;
}
.btnRelatorios{
    width: 100% !important;
}
.dropMenuRel{
    right: -190% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
}
</style>

<script type="text/javascript" src="{$pathJs}/ped/s_cotacao.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 14px;">

    <div class="">
        <div class="row">

            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12" style="padding: 1px;">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Consulta Cotações
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-info" onClick="javascript:submitLimparFiltros();">
                                    <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary"
                                    onClick="javascript:submitCadastro('');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Nova
                                        Cotação</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" METHOD="POST" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value="cotacao">
                            <input name=submenu type=hidden value="">
                            <input name=letra type=hidden value={$letra}>
                            <input name="pessoa" type="hidden" id="pessoa" value="{$pessoa}">
                            <input name=dataIni                type=hidden value={$dataIni}>
                            <input name=dataFim                type=hidden value={$dataFim}>

                            <div class="form-group">
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="numCotacao">N° Cotação</label>
                                    <input class="form-control input-sm" placeholder="N° Cotação." id="numCotacao"
                                        name="numCotacao" value="{$numCotacao}">
                                </div>                                
                                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                <label class="">Periodo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                            value="{$dataIni} - {$dataFim}">
                                </div> 
                                <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                <label for="conta">Cliente</label>
                                <div class="input-group line-formated">
                                    <input type="text" class="form-control input-sm" id="nome" name="nome"
                                        placeholder="Cliente" value="{$nome}" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar&origem=cotacao');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </form>

                        <table id="datatable-buttons" class="table table-striped table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th>Pedido</th>
                                    <th>Emissão</th>
                                    <th>Cliente</th>
                                    <th>Cond. Pagamento</th>
                                    <th>Total</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    <tr>
                                        <td>{$lanc[i].ID}</td>
                                        <td>{$lanc[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                        <td>{$lanc[i].NOME}</td>
                                        <td>{$lanc[i].DESCCONDPGTO}</td>
                                        <td>R$ {$lanc[i].TOTAL|number_format:2:",":"."}</td>
                                        <td>
                                            <button type="button" style="align-items: center;" class="btn btn-primary btn-xs"
                                                onclick="javascript:submitAlterar({$lanc[i].ID});"><span
                                                class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                data-toggle="tooltip"></span></button>
                                            <button type="button" style="align-items: center;" class="btn btn-info btn-xs"
                                                onclick="javascript:submitImprimir({$lanc[i].ID});"><span
                                                    class="glyphicon glyphicon-print" aria-hidden="true"
                                                    data-toggle="tooltip"></span></button>
                                            <button type="button" style="align-items: center;" class="btn btn-success btn-xs"
                                                onclick="javascript:submitEnviarEmail({$lanc[i].ID});"><span
                                                    class="glyphicon glyphicon-envelope" aria-hidden="true"
                                                    data-toggle="tooltip" title="Enviar por Email"></span></button>
                                            <button type="button" style="align-items: center;" class="btn btn-warning btn-xs"
                                                onclick="javascript:submitDownloadPdf({$lanc[i].ID});"><span
                                                    class="glyphicon glyphicon-download" aria-hidden="true"
                                                    data-toggle="tooltip" title="Download PDF"></span></button>
                                            <button type="button" style="align-items: center;" class="btn btn-danger btn-xs"
                                                onclick="javascript:submitCancelar({$lanc[i].ID});"><span
                                                class="glyphicon glyphicon-remove" aria-hidden="true"
                                                data-toggle="tooltip"></span></button>
                                        </td>
                                    </tr>
                                {/section}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/form.inc"}
<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>
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
</script>
