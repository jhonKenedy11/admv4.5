<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_aprovacao_telhas.js"> </script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Aprovação Cotação - Consulta
                            <strong>
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success small" role="alert">{$mensagem}</div>
                                    {else}
                                        <div class="alert alert-error small" role="alert">{$mensagem}</div>
                                    {/if}
                                {/if}
                                <strong>

                                </strong>
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>

                            {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>

                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value="pedido_aprovacao">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=fornecedor type=hidden value={$pessoa}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=letra2 type=hidden value={$letra2}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=usrAprovacao type=hidden value={$usrAprovacao}>

                            <div class="form-group">
                                <div class="form-group col-md-2 col-sm-3 col-xs-3">
                                    <label>C&oacute;d. Cotação</label>
                                    <input class="form-control" id="codCotacao" name="codCotacao"
                                        placeholder="Código da Cotação." value={$codCotacao}>
                                </div>
                                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                    <label>Centro de Custo</label>
                                    <select class="js-example-basic-single form-control" name="ccusto" id="ccusto">
                                        {html_options values=$ccusto_ids output=$ccusto_names selected=$ccusto_id}
                                    </SELECT>
                                </div>
                                <div class="form-group col-md-1 col-sm-3 col-xs-3">
                                    <label> </label>
                                    <input type="checkbox" onclick="javascript:myFunction()" style="width:80px;"
                                        id="checkPeriodo" name="checkPeriodo" title="Ativar Periodo" value="{$checked}"
                                        {if $checked eq 1} checked {/if}>
                                </div>

                                <div class="form-group col-md-3 col-sm-12 col-xs-12">

                                    <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <div>
                                        <input type="text" {if $checked neq 1} disabled {/if} name="dataConsulta"
                                            id="dataConsulta" class="form-control" value="{$dataIni} - {$dataFim}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label for="idVendedor">Vendedor</label>
                                <SELECT class="form-control" name="vendedor">
                                    {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                                </SELECT>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nome">Cliente</label>
                                <div class="input-group">
                                    <input type="text" readonly class="form-control" id="nome" name="nome"
                                        placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="clearfix"></div>


                    </div>


                    <!-- Modal Desaprovado -->
                    <div class="modal fade" id="modalVendaPerdida" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input hidden type="text" name="cotacao" id="cotacao" value="{$cotacao}">
                                        <label>Observações</label>
                                        <div class="panel panel-default small">
                                            <textarea class="resizable_textarea form-control" id="observacao"
                                                name="observacao" rows="4">{$observacao}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        onClick="javascript:salvarPedidoObsDesaprovado(cotacao.value);">Salvar</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                </form>
            </div>
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr style="background: #2A3F54; color: white;">
                                <th>Cotação</th>
                                <th>Situação</th>
                                <th>Cliente</th>
                                <th>Emiss&atilde;o</th>
                                <th>Vendedor</th>
                                <th>Valor Desconto</th>
                                <th>% Desconto</th>
                                <th>Total</th>
                                <th style="width: 120px;">Manuten&ccedil;&atilde;o</th>

                            </tr>
                        </thead>
                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}

                                <tr>
                                    <td name="idNF" id="{$lanc[i].ID}"> {$lanc[i].ID} </td>
                                    <td> {$lanc[i].PADRAO} </td>
                                    <td> {$lanc[i].NOME} </td>
                                    <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                    <td> {$lanc[i].VENDEDOR} </td>
                                    <td> {$lanc[i].DESCONTO} </td>
                                    <td> {if $lanc[i].TOTAL eq 0 }0,00
                                        %{else}{(($lanc[i].DESCONTO*100)/$lanc[i].TOTAL)|number_format:2:",":"."} %
                                        {/if}
                                    </td>
                                    <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                    <td>

                                        <button type="button" title="Visualizar Itens" class="btn btn-info btn-xs"
                                            onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span
                                                class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button>
                                        <button type="button" title="Aprovar" class="btn btn-success btn-xs"
                                            onclick="javascript:salvarPedidoAprovado('{$lanc[i].ID}');"><span
                                                class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                        <!--button type="button" title="Aprovar" class="btn btn-success btn-xs" onclick="javascript:submitCadastrarPedido('{$lanc[i].ID}','{$usrfatura}');"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button-->
                                        <button type="button" title="Desaprovar" class="btn btn-danger btn-xs"
                                            data-toggle="modal" onclick="javascript:pedidoDesaprovado('{$lanc[i].ID}');"
                                            data-target="#modalVendaPerdida"><span class="glyphicon glyphicon-remove"
                                                aria-hidden="true"></span></button>

                                    </td>
                                </tr>
                            {/section}

                        </tbody>
                    </table>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->

            <!-- panel tabela dados -->



        </div> <!-- div class="x_content" = inicio tabela -->
    </div> <!-- div class="x_panel" = painel principal-->
</div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
</div> <!-- div class="row "-->



{include file="template/database.inc"}


<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<!-- /Datatables -->

<script>
    $(function() {
        $("#ccusto.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
        });


    });
</script>

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