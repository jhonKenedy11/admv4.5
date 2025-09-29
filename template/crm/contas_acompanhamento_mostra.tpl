<script type="text/javascript" src="{$pathJs}/crm/s_contas_acompanhamento.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Contas - Acompanhamento - Consulta
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>
                            {/if}
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" data-target="#myModal">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Calendario</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Cadastro</span>
                                </button>
                            </li>
                            {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left"
                            novalidate ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=opcao type=hidden value="{$opcao}">
                            <input name=id type=hidden value="{$id}">
                            <input name=pessoa type=hidden value="{$pessoa}">
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=dataContato type=hidden value="{$dataContato}">
                            <input name=horaContato type=hidden value="{$horaContato}">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <div class="row">

                                <div class="col-lg-7 text-left">
                                    <label>Cliente</label>
                                    <input class="form-control" id="nome" name="nome"
                                        placeholder="Digite o nome do Cliente." value={$nome}>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="pesPedido">Pedido</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="tel" id="pesPedido" name="pesPedido"
                                            value={$pesPedido}>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                    <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <div>
                                        <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                            value="{$dataIni} - {$dataFim}">
                                    </div>
                                </div>


                                <div class="col-lg-3 text-left">
                                    <label for="vendedor">Vendedor</label>
                                    <div class="panel panel-default">
                                        <SELECT {if ($verTodosVend == false)} enable 
                                        {else} disable tabindex="-1"
                                            aria-disabled="true" readonly {/if} class="select2_multiple form-control"
                                            multiple="multiple" id="vendedor" name="vendedor">
                                            {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                                        </SELECT>
                                    </div>
                                </div>

                        </form>

                    </div>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->



            <!-- panel tabela dados -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr style="background: #2A3F54; color: white;">
                                    <th><b>Cliente</b></th>
                                    <th>Data</th>
                                    <th>Vendedor</th>
                                    <th>A&ccedil;&atilde;o</th>
                                    <th>Acompanhamento</th>
                                    <th data-field="date" data-sortable="true" data-sort-name="_date_data"
                                        data-sorter="monthSorter">Proximo Contato</th>
                                    <th style="width: 40px;">Manuten&ccedil;&atilde;o</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td> {$lanc[i].NOMEREDUZIDO} </td>
                                        <td> {$lanc[i].DATA|date_format:"%d/%m/%Y %H:%M"}</td>
                                        <td> {$lanc[i].VENDEDOR} </td>
                                        <td> {$lanc[i].DESCRICAO} </td>
                                        <td> {$lanc[i].RESULTADO} </td>
                                        <td> {$lanc[i].LIGARDIA|date_format:"%d/%m/%y %H:%M"} </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs"
                                                onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span
                                                    class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                                    class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                        </td>
                                    </tr>
                                {/section}

                            </tbody>
                        </table>

                    </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->



        {include file="template/database.inc"}
        <!-- Select2 -->
        <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
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
                            'Setembro',
                            'Outubro', 'Novembro', 'Dezembro'
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
        <script>
            $(document).ready(function() {
                $("#vendedor.select2_multiple").select2({
                    allowClear: true,
                    width: "100%"
                });

            });
        </script>
        {if ($verTodosVend == false)}
            <style>
                .vendedor {
                    pointer-events: none
                }
            </style>
        {/if}
        <style>
            .form-control,
            .x_panel {
                border-radius: 5px;
            }
</style>