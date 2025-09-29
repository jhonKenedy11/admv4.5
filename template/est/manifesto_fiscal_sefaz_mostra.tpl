<style>
    input,
    select,
    .x_panel {
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

    .btnAcao {
        height: 30px !important;
    }

    .btnAcaoText {
        padding-top: 2px !important;
        line-height: 0px !important;
    }

    .swal-modal {
        width: 562px !important;
    }

    .input-container {
        display: none;
        animation: fade 0.3s ease-in-out;
    }

    @keyframes fade {
        0% {
            opacity: 0;
            transform: translateY(-5px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .lds-ring {
        display: inline-block;
        position: relative;
        width: 64px;
        height: 64px;
    }

    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 51px;
        height: 51px;
        margin: 6px;
        border: 6px solid #ccc;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #ccc transparent transparent transparent;
    }

    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .tamanho-personalizado-erro {
        width: 650px !important;
    }

    .tamanho-personalizado-minutos {
        width: 38em !important;
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_manifesto_fiscal_sefaz.js"> </script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="geral">

        <div class="row">

            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><b><i>Manifesto Sefaz</i></b>
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
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" enctype="multipart/form-data" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="manifesto_fiscal">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=condutor type=hidden value="">
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>


                            <div class="col-md-4 col-sm-12 col-xs-12 has-feedback">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="periodo" id="periodo" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>

                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12 has-feedback">
                                <label class="">&nbsp</label>
                                <div>
                                    <button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                            Pesquisa</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12 has-feedback text-right">
                                <label class="">&nbsp</label>
                                <div>
                                    <button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConsultaDocumentosSefaz();">
                                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                            Consulta notas fiscais na receita</span>
                                    </button>
                                </div>
                            </div>

                            {* <div class="col-md-4 col-sm-12 col-xs-12 has-feedback">
                                <label class="">&nbsp</label>
                                <div>
                                    <button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                            Consultar notas no sefaz</span>
                                    </button>
                                </div>
                            </div> *}

                            <div class="clearfix"></div>
                    </div>

                </div> <!-- div class="x_panel" = painel principal-->

                </form>
            </div>

            <!-- panel tabela dados -->
            <div class="col-md-12 col-xs-12 tabelaMdf">
                <div class="x_panel small">
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr style="background: #795858;color: white;">
                                <th>ID</th>
                                <th>N&#176;</th>
                                <th>Pessoa</th>
                                <th>Emiss&atilde;o</th>
                                <th>Situa&ccedil;&atilde;o</th>
                                <th>Total</th>
                                <th>
                                    <center>Xml</center>
                                </th>
                                <th style="width:145px;">
                                    <center>Ação</center>
                                </th>

                            </tr>
                        </thead>
                        <tbody>

                            {section name=i loop=$lanc}
                                <tr>
                                    <td> {$lanc[i].ID} </td>
                                    <td> {$lanc[i].NUMERO} </td>
                                    <td> {$lanc[i].NOME} </td>
                                    <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                    <td> {$lanc[i].DESC_SITUACAO} </td>
                                    <td> {$lanc[i].TOTALNF|number_format:2:",":"."} </td>
                                    <td>
                                        <center>
                                            <button type="button" title="Autoriza NFe"
                                                class="btn btn-info btn-xs downloadXml"
                                                onclick="javascript:submitDownloadXmlExiste('{$lanc[i].ID}');">
                                                <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
                                            </button>
                                        </center>
                                    </td>

                                    <td>
                                        <center>
                                            <button type="button" style="padding: 4px;" class="btn btn-success btnAcao"
                                                {if $lanc[i].SITUACAO !== 'NP'} disabled {/if}
                                                onclick="javascript:submitEnviaEvento('{$lanc[i].ID}', 'confirma');">
                                                <span class="btnAcaoText">Confirma</span>
                                            </button>
                                            <button type="button" style="padding: 4px;" class="btn btn-danger btnAcao"
                                                disabled data-toggle="modal" data-target="#modalCancel">
                                                <span class="btnAcaoText">Cancela</span>
                                            </button>

                                        </center>
                                    </td>
                                </tr>
                            {/section}

                        </tbody>
                    </table>


                </div> <!-- x_panel small -->
            </div> <!-- col-md-12 col-xs-12 tabelaMd-->

        </div> <!-- div class="x_panel" = painel principal-->

        <div class="modal fade" id="modalCancel" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Atualiza produto</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <button type="button" class="btn btn-danger" onclick="submitCienciaEmissao()">
                                        Desconhecimento da operação
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="toggleInput()">
                                        Operação não realizada
                                    </button>

                                    <div id="textInputContainer" class="input-container">
                                        <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                            rows="2"></textarea>
                                        <br />
                                        <div>
                                            <button type="button" class="btn btn-info" onclick="toggleInput()">
                                                Confirma
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div> {* class="modal fade"*}
    </div> {*class="row"*}
</div> {*class="geral"*}
</div> {*class="right_col"*}

{include file="template/database.inc"}

<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>


<!-- daterangepicker -->
<script type="text/javascript">
    $('#periodo').daterangepicker({
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