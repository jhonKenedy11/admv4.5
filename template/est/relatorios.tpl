<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_est.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Relatórios - Consulta
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>
                            {/if}
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning"
                                    onClick="javascript:submitLetraMovimentoEstoque();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <button type="button" class="btn btn-dark btn-xs" data-toggle="modal"
                                            data-target="#modalInutiliza"
                                            onClick="javascript:relatorioProduto();"><span> Relatório
                                                Produto</span></button>

                                    </li>
                                </ul>
                                {* </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!--div class="x_content" style="display: none;"-->
                    <div class="x_content">

                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="relatorios">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value="{$opcao}">
                            <input name=letra type=hidden value="{$letra}">
                            <input name=submenu type=hidden value="{$subMenu}">
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>

                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>
                            </div>
                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>C&oacute;d. Fabricante</label>
                                <input class="form-control" id="codFabricante" name="codFabricante"
                                    placeholder="Código do Fabricante." value={$codFabricante}>
                            </div>
                            <div class="form-group col-md-8 col-sm-12 col-xs-12">
                                <label>Descri&ccedil;&atilde;o</label>
                                <input class="form-control" id="produtoNome" name="produtoNome" autofocus
                                    placeholder="Digite a descrição." value="{$produtoNome}">
                            </div>

                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label>Grupo</label>
                                <SELECT class="form-control" name="grupo">
                                    {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                </SELECT>
                            </div>
                        </form>
                    </div>

                </div> <!-- x_panel -->

            </div> <!-- div class="tamanho -->
        </div> <!-- div row = painel principal-->



        <!-- panel tabela dados -->
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>C&oacute;digo</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Grupo</th>
                            <th>Qtde Dispon&iacute;vel</th>
                            <th>Qtde Ultima Compra</th>
                            <th>Data Ultima Compra</th>
                            <th>Ultima Negociação</th>
                            <th>Ultima Compra</th>
                            <th>Preço Venda</th>
                            <th>Margem</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            {if $lanc[i].CUSTOCOMPRA > 0}
                                {assign var="margem" value=(($lanc[i].VENDA*100)/$lanc[i].CUSTOCOMPRA)-100}
                            {else}
                                {assign var="margem" value=0}
                            {/if}
                            <tr>
                                <td> {$lanc[i].CODIGO} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].NOMEGRUPO} </td>
                                <td> {$lanc[i].ESTOQUE} </td>
                                <td> {$lanc[i].QUANTULTIMACOMPRA} </td>
                                <td> {$lanc[i].DATAULTIMACOMPRA|date_format:"%e %b, %Y"} </td>
                                <td> {$lanc[i].PRECOINFORMADO} </td>
                                <td> {$lanc[i].CUSTOCOMPRA} </td>
                                <td> {$lanc[i].VENDA} </td>
                                <td> {$margem|number_format:2:",":"."}% </td>
                            </tr>
                        {/section}

                    </tbody>
                </table>

            </div> <!-- div class="x_panel"-->
        </div> <!-- div class="x_panel" = tabela principal-->
    </div> <!-- div  "-->
</div> <!-- div role=main-->



{include file="template/database.inc"}
<!-- /Datatables -->


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