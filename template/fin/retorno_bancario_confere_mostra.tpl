<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_fin.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Retorno Bancária - Cobrança
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-info" role="alert">{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning"
                                    onClick="javascript:submitRetornoConfere();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Consolidação</span>
                                </button>
                            </li>
                            {* <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="retorno" name="retorno" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME} enctype="multipart/form-data">
                            <input name=mod type=hidden value="fin">
                            <input name=form type=hidden value="retorno_bancario">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=lanc type=hidden value={$lanc}>
                            <input name=lancHeader type=hidden value={$lancHeader}>
                            <input name=lancTreiller type=hidden value={$lancTreiller}>
                            <input name=filePesquisa type=hidden value={$filePesquisa}>
                            <input name=retorno type=hidden value={$retorno}>


                            <div class="form-group col-md-2 col-sm-4 col-xs-4">
                                <label class="">Selecine o arquivo de Retorno</label>
                            </div>
                            <div class="form-group col-md-4 col-sm-8 col-xs-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn btn-default btn-file"><input type="file" name="fileArq" /></span>
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <select class="select2_multiple form-control" multiple="multiple" id="filial"
                                    name="filial">
                                    {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="x_content">
                        <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                        <table id="datatable-buttons" class="table table-bordered jambo_table small">

                            <thead>
                                <tr class="headings">
                                    <th colspan="7">Relatório Banco</th>
                                    <th colspan="10">Sistema</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr class="headings">
                                    <th>Nosso Numero</th>
                                    <th>Seu Numero</th>
                                    <th>Data Vencimento</th>
                                    <th>Data Pagamento</th>
                                    <th>Valor Remessa</th>
                                    <th>Valor Pago</th>
                                    <th>Valor Oscilação</th>
                                    <th>ID</th>
                                    <th>Nosso Num</th>
                                    <th>NF/Serie/parc</th>
                                    <th>Situação</th>
                                    <th>Data Emissão</th>
                                    <th>Data Vencimento</th>
                                    <th>Data Recebimento</th>
                                    <th>Valor Original</th>
                                    <th>Valor Recebido</th>
                                    <th>Diferença (Rec - Fin)</th-->
                                </tr>
                            </thead>

                            <tbody>

                                <td> </td>
                                <td> </td>
                                <td>Data Retorno:</td>
                                <td>{$dataGravaArq|date_format:"%e %b, %Y"}</td>
                                <td> Arquivo:</td>
                                <td>{$filePesquisa}</td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                </tr>
                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr class="even pointer info">
                                        {if $lanc[i].situacao eq 'Baixado'}
                                            {assign var="remessaBaixado" value=$remessaBaixado+$lanc[i].valorRecebido}
                                            {assign var="numBaixado" value=$numBaixado+1}
                                        {elseif $lanc[i].situacao eq 'Aberto'}
                                            {assign var="remessaAberto" value=$remessaAberto+$lanc[i].valorRecebido}
                                            {assign var="numAberto" value=$numAberto+1}
                                        {elseif $lanc[i].situacao eq 'Cancelado'}
                                            {assign var="remessaCancelado" value=$remessaCancelado+$lanc[i].valorRecebido}
                                            {assign var="numCancelado" value=$numCancelado+1}
                                        {else}
                                            {assign var="remessaNao" value=$remessaNao+$lanc[i].valorRecebido}
                                            {assign var="numNao" value=$numNao+1}
                                        {/if}
                                        {assign var="remessaTotal" value=$remessaTotal+$lanc[i].valorRecebido}
                                        {assign var="numReg" value=$numReg+1}

                                        <td> {$lanc[i].nn}</td>
                                        <td> {$lanc[i].seuNumero}</td>
                                        <td> {$lanc[i].dataVencimento|date_format:"%e %b, %Y"} </td>
                                        <td> {$lanc[i].dataPagamento|date_format:"%e %b, %Y"} </td>
                                        <td align=right>{$lanc[i].valorRemessa|number_format:2:",":"."} </td>
                                        <td align=right>{$lanc[i].valorPago|number_format:2:",":"."} </td>
                                        <td align=right>{$lanc[i].valorOscilacao} </td>

                                        <td> {$lanc[i].idSis}</td>
                                        <td> {$lanc[i].nnSis}</td>
                                        <td> {$lanc[i].nf}{$lanc[i].serie}{$lanc[i].parcela} </td>
                                        <td> {$lanc[i].situacao} </td>
                                        <td> {$lanc[i].dataEmissao|date_format:"%e %b, %Y"} </td>
                                        <td> {$lanc[i].dataVencimentoSis|date_format:"%e %b, %Y"} </td>
                                        <td> {$lanc[i].dataRecebimento|date_format:"%e %b, %Y"} </td>
                                        <td align=right>{$lanc[i].valorOriginal|number_format:2:",":"."} </td>
                                        <td align=right>{$lanc[i].valorRecebido|number_format:2:",":"."} </td>
                                        <td align=right>{$lanc[i].diferenca|number_format:2:",":"."} </td>
                                    </tr>
                                    <p>
                                    {/section}
                                    <tr class="even pointer danger">

                                        <td> </td>
                                        <td> </td>
                                        <td>Registros remessa:</td>
                                        <td>{$numReg}</td>
                                        <td> Total:</td>
                                        <td>{$remessaTotal|number_format:2:",":"."}</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> Baixado</td>
                                        <td> Reg:</td>
                                        <td>{$numBaixado}</td>
                                        <td> Total:</td>
                                        <td>{$remessaBaixado|number_format:2:",":"."}</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> Aberto</td>
                                        <td> Reg:</td>
                                        <td>{$numAberto}</td>
                                        <td> Total:</td>
                                        <td>{$remessaAberto|number_format:2:",":"."}</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> Não Autorizado</td>
                                        <td> Reg:</td>
                                        <td>{$numNao}</td>
                                        <td> Total:</td>
                                        <td>{$remessaNao|number_format:2:",":"."}</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td> </td>
                                        <td> Cancelado</td>
                                        <td> Reg:</td>
                                        <td>{$numCancelado}</td>
                                        <td> Total:</td>
                                        <td>{$remessaCancelado|number_format:2:",":"."}</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                    </tr>

                            </tbody>

                        </table>

                    </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->


    {include file="template/database.inc"}

    <!-- /Datatables -->
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>

    <!-- Select2 -->
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

    <!-- Select2 -->
    <script>
        $(document).ready(function() {
            $("#conta.select2_multiple").select2({
                placeholder: "Escolha a Conta",
                allowClear: true
            });
            $("#filial.select2_multiple").select2({
                placeholder: "Escolha a filial",
                allowClear: true
            });

        });
    </script>
    <!-- /Select2 -->
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
<!-- /daterangepicker -->