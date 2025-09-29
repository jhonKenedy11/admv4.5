<style>
    .form-control {
        border-radius: 5px;
    }

    .x_panel {
        margin padding: 0 !important;
        border-radius: 5px;
    }

    .form-group {
        margin-bottom: 1px;
    }

    .btn-group .btn {
        margin-right: 5px !important;
        border-radius: 5px !important;

    }

    .btn-group {
        margin-top: 5px;
    }

    #btn_pesquisa,
    #btn_limpa {
        width: 100%;
    }

    #div_table {
        padding: 1px !important;
        flex: 1;
        max-width: 270px !important;
        font-size: 10px;
        margin-bottom: 0px;

    }

    label {
        display: inline-block;
        max-width: 100%;
        font-size: 10px;
        margin-bottom: 5px;
        font-weight: 700;
    }


    #div_btn {
        margin-bottom: 5px;
        padding-left: 0px;
        padding-right: 0px;
    }

    #div_input {
        padding-left: 0px;
        padding-right: 0px;
        margin-bottom: -5px !important;
    }

    #id_selected {
        font-size: 11px;
        padding-left: 0px;
        padding-right: 0px;
        padding: 1px !important;
        padding-top: 6px !important;
    }

    /* Add styles for action buttons */
    .btn-xs {
        display: inline-block;
        margin-right: 3px;
    }
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_apontamento_os_mobile.js"> </script>

<div class="right_col" role="main">

    <div class="">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8">
                <div class="x_panel">
                    <div>
                        <h2>
                            <center>Apontamento O.S. </center>
                        </h2>

                    </div>
                    {if $mensagem neq ''}
                        {if $tipoMsg eq 'sucesso'}
                            <div class="row">
                                <div class="col-lg-12 text-left">
                                    <div>
                                        <div class="alert alert-success" role="alert">
                                            <strong>--Sucesso!</strong>&nbsp;{$mensagem}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {elseif $tipoMsg eq 'alerta'}
                            <div class="row">
                                <div class="col-lg-d text-left">
                                    <div>
                                        <div class="alert alert-danger" role="alert">
                                            <strong>--Aviso!</strong>&nbsp;{$mensagem}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    {/if}
                    </strong>

                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="cat">
                            <input name=form type=hidden value="apontamento_os_mobile">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=origem type=hidden value="{$origem}">
                            <input name=id type=hidden value="">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=situacaoSelecionada type=hidden value="">

                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" id="id_input">
                                <div class="form-group col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <center><label>Numero O.S.</label></center>
                                    <input class="form-control input-sm" id="numAtendimento" name="numAtendimento"
                                        placeholder="O.S." value="{$numAtendimento}">
                                </div>

                                <div class="form-group col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <center><label>Periodo</label>
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </center>
                                    <div>
                                        <input type="text" name="dataConsulta" id="dataConsulta"
                                            class="form-control input-sm" value="{$dataIni} - {$dataFim}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" id="id_selected">
                                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 compact-form-group">
                                    <label class="control-label">Status</label>
                                    <select class="form-control input-sm" name="situacao" id="situacao">
                                        {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                    </select>
                                </div>
                            </div>

                            <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12" id="div_btn">
                                <div class="col-xs-6">
                                    <button type="button" id="btn_pesquisa" class="btn btn-warning btn-xs"
                                        onClick="javascript:submitLetra();">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        Pesquisa
                                    </button>
                                </div>
                                <div class="col-xs-6">
                                    <button type="button" id="btn_limpa" class="btn btn-danger btn-xs"
                                        onClick="limparCampos();">
                                        <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
                                        Limpar Campos
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div id="div_table">
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th>Pessoa</th>
                                    <th style="text-align: center;">OS</th>
                                    <th style="text-align: center;">Situaç&atilde;o</th>
                                    <th style="text-align: center;">Emiss&atilde;o</th>
                                    <th id="checkBox" style="text-align: center;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].ID_SITUACAO}
                                        {assign var="total" value=$total+1}
                                        <tr>

                                            <td> {$lanc[i].NOME} </td>
                                            <td style="text-align: center;">{$lanc[i].ID}</td>
                                            <td style="text-align: center;">{$lanc[i].SITUACAODESC}</td>
                                            <td style="text-align: center;">{$lanc[i].DATAABERATEND|date_format:"%d/%m/%Y"}
                                            </td>

                                            <td>
                                                <div style="white-space: nowrap;">
                                                    <button type="button" name="abrirFinalizacao" id="{$lanc[i].OS}"
                                                        class="btn btn-primary btn-xs"
                                                        onclick="abrirFinalizacao('{$lanc[i].ID}');">OS</button>
                                                    <button type="button" class="btn btn-dark btn-xs"
                                                        onclick="javascript:submitCadastrarImagemOS('{$lanc[i].ID}');">
                                                        <span class="glyphicon glyphicon-camera" aria-hidden="true"
                                                            data-toggle="tooltip" title="Imagem"></span>
                                                    </button>
                                                </div>
                                            </td>


                                        </tr>
                                    {/if}
                                {/section}
                                {if empty($lanc)}
                                    <tr>
                                        <td colspan="8">Nenhum resultado encontrado.</td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


{include file="template/database.inc"}

<!-- daterangepicker -->
<script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            drops: 'auto',
            opens: 'left',
            drops: 'down',
            mobile: true,
            autoApply: true,
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

        //funcao para recuperar o valor digitado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        });
</script>

<!-- /daterangepicker -->