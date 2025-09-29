<style>
    #calendar {
        margin: 60px auto 0 0;
    }

    .all {
        background-color: #F8F8FF;
    }

    #idPedido {
        font-weight: bold;
        border-radius: 5px;
    }

    #dataReg,
    .dataReg {
        font-weight: bold;
        border-radius: 5px;
    }

    #cliente_nome,
    .cliente_nome,
    #descReg,
    #proximoContato,
    .panel-descricao,
    .panel-proximo-ctt,
    .select2-selection__choice,
    .panel-pedido,
    .evento_realizado,
    #evento_realizado_,
    .cliente_telefone,
    .cliente_celular,
    .cliente_email,
    .atividade,
    #cliente_telefone,
    #cliente_celular,
    #cliente_email,
    #atividade,
    #evento_realizado,
    #name_vendedor {
        border-radius: 5px !important;
    }

    .show-calendar {
        z-index: 1000000 !important;
    }

    .modal-footer .btn+.btn {
        margin-bottom: 6px !important;
        margin-left: 5px !important;
    }

    .label_header {
        height: 65px !important;
    }

    #form_edit {
        margin-bottom: -27px;
    }

    #evento_realizado {
        border-color: darkblue;
    }

    #evento_realizado_ {
        border-color: darkblue;
    }

    .panel-proximo-ctt {
        border-color: darkgreen;
    }

    .picker_4 {
        margin-top: -281px;
        right: 185.5px;
        border-radius: 5px;
    }

    #proximoContatoHora {
        width: 62px;
        border-color: darkgreen;
        border-radius: 5px;
        margin-left: -19px;
        margin-top: 1px;
        padding: 12px;
    }

    .panel-proximo-ctt-horas {
        margin-left: -18px;
        border-radius: 5px;
        width: 56px;
    }

    #evento_realizado_hora {
        width: 56px;
        border-color: darkblue;
        margin-left: -20px;
        border-radius: 5px;
        margin-top: 1px;
        padding: 9px;
    }

    #calendar {
        margin-top: 10px;
    }

    #periodoBloq {
        width: 200px;
    }

    .select2-selection--multiple {
        border-radius: 0px 5px 5px 0px !important;
    }

    .select2 {
        width: 100% !important;
    }

    .hidden {
        opacity: 0;
        transition: opacity 0.9s ease-out;
    }

    .legenda {
        display: inline-block;
        background-color: rgb(255, 152, 152);
        color: white;
        width: 11px;
        height: 11px;
        text-align: center;
        line-height: 20px;
    }
</style>
<div class="right_col" role="main">
    <div class="">
        <html>

        <head>
            <meta charset='utf-8' />
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <link href='{$pathRaiz}/css/core/main.min.css' rel='stylesheet' />
            <link href='{$pathRaiz}/css/daygrid/main.min.css' rel='stylesheet' />
            <script src='{$pathJs}/crm/calendar/core/main.min.js'></script>
            <script src='{$pathJs}/crm/calendar/interaction/main.min.js'></script>
            <script src='{$pathJs}/crm/calendar/daygrid/main.min.js'></script>
            <script src='{$pathJs}/crm/calendar/core/locales/pt-br.js'></script>
            <script src='{$pathJs}/crm/calendar/calendar_tpl.js'></script>
            <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
        </head>

        <body>
            <div class="row">
                <div class="col-md-12">
                    <span class="legenda"></span>
                    <p style="display: inline-block;">Data de entrega bloqueada</p>
                </div>
            </div>

            <div class="row" {if $vertodoslancamentos == false}hidden{/if}>

                <div class="col-md-5 col-xs-5 col-sm-12 justify-content-end">
                    <div id="divVend" class="input-group">
                        <label for="vendedor" style="cursor:not-allowed; font-size:13px;"
                            class="input-group-addon">Vendedor</label>
                        <SELECT {if ($vertodoslancamentos )} enable {else} disabled {/if}
                            class="select2_multiple form-control" multiple="multiple" id="vendedor" name="vendedor">
                            {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                        </SELECT>
                    </div>
                </div>

            </div>

            <div id='calendar'></div>

            <!--Modal de Edição de Registro -->
            <div id="editRegistro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="form_edit" name="form_edit" class="form-horizontal calender" role="form">
                            <div class="modal-header label_header">
                                <div class="col-md-3 col-sm-4 col-xs-12">
                                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                    <h4 class="modal-title" id="myModalLabel">Editar Registro</h4>
                                </div>

                                <div class="col-sm-1 offset-sm-1"></div>

                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="panel panel-default name_vendedor">
                                        <input class="form-control" type="text" id="name_vendedor" title="Nome vendedor"
                                            readonly name="name_vendedor" style="text-align: center;"
                                            value={$name_vendedor}>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <div class="panel panel-default dataReg">
                                        <input class="form-control" type="text" id="dataReg" title="Data Agendamento"
                                            readonly name="dataContato" style="text-align: center;" value={$dataReg}>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="panel panel-default panel-pedido">
                                        <input class="form-control" readonly type="text" id="idPedido" name="idPedido"
                                            title="Pedido" style="text-align: center;" value={$idPedido}>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-body">
                                <div id="testmodal" style="padding: 5px 20px;">

                                    <!--Input hidden para visualizar o id do acompanhamento -->
                                    <input name=id_reg id=id_reg type=hidden value="{$id_reg}"> </input>
                                    <input name=registerNewEvent id=registerNewEvent type=hidden
                                        value="{$registerNewEvent}"> </input>
                                    <input name=event_realizado id=event_realizado type=hidden
                                        value="{$event_realizado}"> </input>
                                    <!--FIM hidden -->

                                    <div class="row row-md-12 row-sm-12 row-xs-12">
                                        <!--<div class="col-sm-1 offset-sm-1"></div>-->
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <label for="acao">atividade</label>
                                            <div class="panel panel-default atividade">
                                                <SELECT class="form-control" id="atividade" name="atividade">
                                                    {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                                                </SELECT>
                                            </div>
                                        </div>
                                        <!--
                                    <div class="col-md-3 col-sm-4 col-xs-12">
                                        <label for="dataReg">Data Agenda</label>
                                        <div class="panel panel-default dataReg">
                                            <input class="form-control" type="text" id="dataReg" disabled name="dataContato" value={$dataReg}>
                                        </div>
                                    </div>
                                    -->
                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <label for="cliente_nome">Cliente</label>
                                            <div class="panel panel-default cliente_nome">
                                                <input type="text" readonly class="form-control" id="cliente_nome"
                                                    name="cliente_nome" autocomplete="off" value="{$cliente_nome}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <label for="cliente_telefone">Telefone</label>
                                            <div class="panel panel-default cliente_telefone">
                                                <input type="text" readonly class="form-control" id="cliente_telefone"
                                                    name="cliente_telefone" autocomplete="off"
                                                    value="{$cliente_telefone}">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <label for="cliente_celular">Celular</label>
                                            <div class="panel panel-default cliente_nome">
                                                <input type="text" readonly class="form-control" id="cliente_celular"
                                                    name="cliente_celular" autocomplete="off"
                                                    value="{$cliente_celular}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-3 col-xs-12">
                                            <label for="cliente_email">E-mail</label>
                                            <div class="panel panel-default cliente_email">
                                                <input type="text" readonly class="form-control" id="cliente_email"
                                                    name="cliente_email" autocomplete="off" value="{$cliente_email}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="descReg">Descrição</label>
                                            <div class="panel panel-default panel-descricao">
                                                <textarea class="form-control" style="height:80px;" id="descReg"
                                                    name="descReg" autocomplete="off" value={$descReg}></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row row-md-12 row-sm-12 row-xs-12">

                                        <div class="col-md-2 col-sm-4 col-xs-12">
                                            <label for="proximoContato">Próximo Contato</label>
                                            <div class="panel panel-default panel-proximo-ctt">
                                                <input class="form-control" id="proximoContato" type="text"
                                                    autocomplete="off" align="center" name="proximoContato"
                                                    placeholder="99/99/9999" value="{$proximoContato}">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-4 col-xs-12">
                                            <label for="proximoContatoHora">&nbsp;</label>
                                            <input class="form-control" id="proximoContatoHora" type="text"
                                                data-inputmask="'mask': '99:99'" name="proximoContatoHora"
                                                autocomplete="off" placeholder="12:00" value="{$proximoContatoHora}">
                                        </div>

                                        <div class="col-sm-6 offset-sm-6"></div>

                                        <div class="col-md-2 col-sm-4 col-xs-12">
                                            <label for="evento_realizado"
                                                title="Para baixar o baixar o evento deve ser preenchido esse campo">Evento
                                                realizado</label>
                                            <div class="panel panel-default evento_realizado">
                                                <input type="text" class="form-control" placeholder="99/99/9999"
                                                    id="evento_realizado" name="evento_realizado" autocomplete="off"
                                                    value="{$evento_realizado}">
                                            </div>
                                        </div>

                                        <div class="col-md-1 col-sm-4 col-xs-12">
                                            <label for="evento_realizado_hora">&nbsp;</label>
                                            <input class="form-control" id="evento_realizado_hora" type="text"
                                                data-inputmask="'mask': '99:99'" name="evento_realizado_hora"
                                                autocomplete="off" placeholder="12:00" value="{$evento_realizado_hora}">
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </form>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary antosubmit"
                                onClick="javascript:submitUpdateReg();">Atualizar</button>
                        </div>

                    </div>
                </div>
                <!--FIM Modal de Edicao de Registro -->
        </body>

        </html>
    </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
</div> <!-- div class="row "-->
{include file="template/form.inc"}
<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#vendedor.select2_multiple").select2({
            allowClear: true,
            maximumSelectionLength: 1,
            width: "90%",
            language: {
                maximumSelected: function() {
                    return "Você pode selecionar apenas 1 vendedor";
                }
            }
        });

    });
</script>

<script>
    $(function() {
        $('#proximoContato').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4",
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                    'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
            }

        });
    });

    $(function() {
        $('#evento_realizado').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4",
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                    'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
            }

        });
    });
</script>