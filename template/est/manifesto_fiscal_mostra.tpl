<script type="text/javascript" src="{$pathJs}/est/s_manifesto_fiscal.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="row">
            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Manifesto Fiscal - Consulta
                            <strong>
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success" role="alert">{$mensagem}</div>
                                    {else}
                                        <div class="alert alert-error" role="alert">{$mensagem}</div>
                                    {/if}
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
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
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <button type="button" class="btn btn-success btn-xs"
                                            onClick="javascript:limpaDadosForm();">
                                            <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span>
                                                Limpar Dados Formulário</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn btn-success btn-xs"
                                            onClick="javascript:consultaNaoEncerrrados();">
                                            <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                                Consulta não encerrados</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="manifesto_fiscal">
                            <input name=id type=hidden value={$id}>
                            <input name=nome type=hidden value={$nome}>
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=condutor type=hidden value={$condutor}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>



                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>N&#186; MDF</label>
                                <input class="form-control" id="mdf" name="mdf"
                                    placeholder="N&uacute;mero Manifesto fiscal." value={$mdf}>
                            </div>
                            <div class="form-group col-md-2 col-sm-1 col-xs-1">
                                <label>Série</label>
                                <input class="form-control" id="serie" name="serie"
                                    placeholder="S&eacute;rie Manifesto fiscal" value={$serie}>
                            </div>



                            <div class="col-md-4 col-sm-12 col-xs-12 has-feedback">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>

                            </div>

                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label for="idVendedor">Situa&ccedil;&atilde;o</label>
                                <SELECT class="form-control" name="msituacao">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </SELECT>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <label for="nomecondutor">Condutor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nomecondutor" name="nomecondutor"
                                        placeholder="Condutor" value="{$nomecondutor}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label for="pesCidade">Empresa</label>
                                <select class="form-control" name=mfilial>
                                    {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                </select>
                            </div>
                    </div>

                </div>

                </form>
            </div>

        </div> <!-- div class="x_content" = inicio tabela -->

    </div> <!-- div class="x_panel" = painel principal-->

    <!-- panel tabela dados -->
    <div class="col-md-12 col-xs-12 tabelaMdf">
        <div class="x_panel small">
            <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                    <tr style="background: #2A3F54; color: white;">
                        {* <th>#</th>  *}
                        <th>ID</th>
                        <th>Emiss&atilde;o</th>
                        <th>N&#186; MDFe</th>
                        <th>S&eacute;rie</th>
                        <th>Filial</th>
                        <th>Condutor</th>
                        <th>Veículo</th>
                        <th>Situa&ccedil;&atilde;o</th>
                        <th>Total</th>
                        <th style="width:98px;">Manuten&ccedil;&atilde;o</th>

                    </tr>
                </thead>
                <tbody>

                    {section name=i loop=$lanc}
                        {assign var="total" value=$total+1}
                        <tr>
                            {* <td>  <input type="checkBox"  name="nfChecked" id="{$lanc[i].ID}"/> </td> *}
                            <td name="idNF" id="{$lanc[i].ID}"> {$lanc[i].ID} </td>
                            <td> {$lanc[i].DATAHORA|date_format:"%e %b, %Y %H:%M:%S"} </td>
                            <td> {$lanc[i].NUM_MDF} </td>
                            <td> {$lanc[i].SERIE} </td>
                            <td> {$lanc[i].FILIAL} </td>
                            <td> {$lanc[i].NOMECONDUTOR} </td>
                            <td> {$lanc[i].VEICULO} </td>
                            <td> {$lanc[i].SITUACAONOTA} </td>
                            <td> {$lanc[i].TOTALCARGA|number_format:2:",":"."} </td>
                            <td>
                                <button type="button" title="Alterar" class="btn btn-primary btn-xs"
                                    onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                <button type="button" title="Deletar" class="btn btn-danger btn-xs"
                                    onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                <button type="button" title="Imprimir" class="btn btn-warning btn-xs"
                                    onclick="javascript:submitImprime('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                <button type="button" title="Autoriza MDFe" class="btn btn-info btn-xs"
                                    onclick="javascript:submitGerarXmlManifesto('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                <button type="button" title="Consultar Status MDFe" class="btn btn-xs" id="btnConsStatus"
                                    onclick="javascript:consultaStatusMdfe('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-search iconConsStatus"
                                        aria-hidden="true"></span></button>
                                <button type="button" id="encerraMdf" title="Encerra MDFe" class="btn btn-xs"
                                    onclick="javascript:submitEncerraMdfe('{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-stop" aria-hidden="true"></span></button>
                            </td>
                        </tr>
                    {/section}

                </tbody>
            </table>


        </div> <!-- div class="x_content" = inicio tabela -->
    </div> <!-- div class="x_panel" = painel principal-->
</div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->




{include file="template/database.inc"}

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
<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    input,
    select {
        border-radius: 5px !important;
    }

    .tabelaMdf {
        padding: 0 !important;
    }

    #encerraMdf {
        background-color: #523917;
    }

    .glyphicon-stop {
        color: aliceblue;
    }

    #btnConsStatus {
        background-color: darkgreen;
    }

    .iconConsStatus {
        color: aliceblue;
    }

    #dataConsulta {
        text-align: center;
    }
</style>