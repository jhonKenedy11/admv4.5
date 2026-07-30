<style>
    .input-group {
        border-radius: 10px;
    }

    .form-control,
    .x_panel {
        border-radius: 5px !important;
    }

    .panel-default {
        border-radius: 10px;
    }

    #btnSearch {
        border-radius: 8px !important;
    }

    .swal-text {
        font-size: 22px;
    }

    .accordion .panel {
        border-radius: 5px !important;
    }
    .btnCadastraNovo{
        background-color: #4f4540 !important;
        border-color: #342f2d !important;
    }
    .btnCadastraNovo:hover{
        background-color: #282321 !important;
        border-color: #000000 !important;
    }
    #btnAddNovoContato{
        position: absolute;
        margin: -5px 0 0 6px !important;
        border-radius: 5px;
        height: 25px;
    }
    .spanBtnNovoContato{
        font-size: 12px;
        position: relative !important;
        top: -4px !important;
        margin-left: -3px;
    }
    .trContatos{
        font-size: 11px;
    }
    .bodyContatos>tr>th,
    .bodyContatos>tr>td{
        padding: 2px !important;
        vertical-align: inherit !important;
    }
    .swal-modal{
        width: 700px ;
    }
    .swal-button--btn_cancelar{
        background: rgb(158, 33, 33);
    }
    .swal-button--btn_cancelar:hover{
        background: rgb(134, 15, 15);
    }
</style>

<!-- PACOTES PARA GENTELLA -->
<!-- bootstrap-wysiwyg -->
<link href="{$bootstrap}/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="{$bootstrap}/build/css/custom.min.css" rel="stylesheet">
<!-- END GETELLA -->
<!-- jQuery + Bootstrap 3 (Gentelella: modal/collapse com data-toggle; BS5 bundle quebrava sem jQuery) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/crm/s_crm_contas_acompanhamento.js"> </script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="page-title">
            <div class="title_left">
                <h3>Contas - Acompanhamento</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="crm">
            <input name=form type=hidden value="crm_contas_acompanhamento">
            <input name=acao type=hidden value={$acao}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=letra type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>
            <input name=pessoa id="pessoa" hidden value={$pessoa}>
            <input name=pessoaNome type=hidden value="{$pessoaNome|escape:'html'}">
            <input name=vendedorAcomp type=hidden value={$vendedorAcomp_id}>
            <input name=dataContato type=hidden value={$dataContato}>
            <input name=horaContato type=hidden value={$horaContato}>
            <input name=fornecedor type=hidden value="">
            <input name=mensagem_retorno_contato type=hidden value={$mensagem_retorno_contato}>
            <input name=codigo_retorno_contato type=hidden value={$codigo_retorno_contato}>
            <!-- dashboard -->
            <input name=dashboard_origem type=hidden value={$dashboard_origem}>
            <input name=data_previous type=hidden value={$data_previous}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Cadastro
                                {else}
                                    Altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert">Aviso!&nbsp;{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span>
                                            Cancelar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content container" style="padding: 0;">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-7 col-sm-12 col-xs-12">
                                            <label for="acao">Ação</label>
                                            <div class="panel panel-default">
                                                <select class="form-control" name="acao">
                                                    {html_options values=$acao_ids output=$acao_names selected=$acao_id}
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-5 col-sm-5 col-xs-12 form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status">
                                                {html_options values=$status_ids output=$status_names selected=$status_id}
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        <div class="col-md-7 col-sm-4 col-xs-12 form-group">
                                            <label for="pessoaExibicao">Pessoa</label>
                                            <div class="panel panel-default">
                                                <input type="text" id="pessoaExibicao" class="form-control" readonly tabindex="-1"
                                                    value="{$pessoaNome|escape:'html'}{if $pessoa ne ''} ({$pessoa|escape:'html'}){/if}" />
                                            </div>
                                            <select id="clienteCombo" name="clienteCombo" style="display:none;" aria-hidden="true" tabindex="-1">
                                                {if $pessoa ne ''}
                                                <option value="{$pessoa|escape:'html'}" selected>{$pessoaNome|escape:'html'}</option>
                                                {else}
                                                <option value="" selected></option>
                                                {/if}
                                            </select>
                                        </div>

                                        <div class="col-md-5 col-sm-5 col-xs-12 form-group">
                                            <label for="status">Status Cliente</label>
                                            <select class="form-control" name="status_cli">
                                                {html_options values=$status_cli_ids output=$status_cli_names selected=$status_cli_id}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-7 col-sm-7 col-xs-6">
                                            <label for="dataContato">Data contato</label>
                                            <div class="panel panel-default">
                                                <input class="form-control" style="text-align: center;" type="text" id="dataContato" disabled name="dataContato" value={$dataContato}>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-5 col-xs-6">
                                            <label for="proximoContato">Cotação/Pedido</label>
                                            <div class="panel panel-default">
                                                <input class="form-control" readonly type="text" id="idPedido" name="idPedido"
                                                    placeholder="Número Cotação/Pedido." value={$idPedido}>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="resultContato">Acompanhamento:</label>
                                            <div class="panel panel-default">
                                                <textarea class="form-control" rows="7" id="resultContato"
                                                    placeholder="Digite acompanhamento do contato realizado."
                                                    name="resultContato">{$resultContato}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <table class="table" id="cadastroContato">
                                        <caption>
                                            Lista de contatos
                                            <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modalNovoContato" id="btnAddNovoContato">
                                                <span class="spanBtnNovoContato">Adicionar novo contato</span>
                                            </button>
                                        </caption>
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Telefone</th>
                                            <th scope="col">E-mail</th>
                                            </tr>
                                        </thead>

                                        <tbody class="bodyContatos" id="bodyContatos">
                                            <tr>
                                                <td>{$contatos_cliente[h].NOME_CONTATO}</td>
                                            </tr>
                                            {section name=h loop=$contatos_cliente}
                                                <tr class="trContatos small">
                                                    <th>
                                                        <input class="form-check-input trContatosCheck" id="{$contatos_cliente[h].ID}" value="{$contatos_cliente[h].EMAIL}" type="checkbox" onclick="updateInputDestinatario()">
                                                    </th>
                                                    <td>{$contatos_cliente[h].NOME_CONTATO}</td>
                                                    <td>{$contatos_cliente[h].TELEFONE}</td>
                                                    <td>{$contatos_cliente[h].EMAIL}</td>
                                                </tr>
                                            {/section}
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            
                            <!-- dados adicionaris -->
                            <!-- start accordion -->
                            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel">
                                    <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapse1" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <h4 class="panel-title"><i class="fa fa-chevron-down"></i>&nbsp; Próximo contato </h4>
                                    </a>

                                    <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                                        <div class="panel-body">
                                            <div class="x_panel">
                                                    <div class="row">
                                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                                            <label for="dataHoraProximoAcomp">Data/Hora próximo contato</label>
                                                            <div class="panel panel-default">
                                                                <input class="form-control" type="text" id="dataHoraProximoAcomp"
                                                                    name="dataHoraProximoAcomp" placeholder="Data proximo contato" value="{$dataHoraProxCont|escape:'html'}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                                            <label for="acao">Ação</label>
                                                            <div class="panel panel-default">
                                                                <select class="form-control" name="acaoNovoAcomp">
                                                                    {html_options values=$acao_ids output=$acao_names selected=$acao_id}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                                            <label for="descNovoAcomp">Descricao novo acompanhamento:</label>
                                                            <div class="panel panel-default">
                                                                <textarea class="form-control" rows="1" id="descNovoAcomp" placeholder="Digite acompanhamento do contato realizado." name="descNovoAcomp">
                                                                    {$descNovoAcomp}
                                                                </textarea>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;"><center>
                                                        <button type="button" class="btn btn-primary btnCadastraNovo" onClick="javascript:submitCadastraNovoAcomp('');">
                                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                                            <span>Cadastrar novo acompanhamento</span>
                                                        </center></button>
                                                    </div>

                                                </div>
                                            </div>

                                            {if $existeAcompanhamento eq 'yes'}
                                                <div class="panel-body">
                                                    <div class="x_panel">
                                                        
                                                        <table id="datatable-acompanhamento" class="table table-bordered jambo_table">
                                                            <thead>
                                                                <tr style="background: #4f4540 !important; color: white;">
                                                                    <th style="width: 140px;">Data</th>
                                                                    <th>Ação</th>
                                                                    <th>Status</th>
                                                                    <th>Colaborador</th>
                                                                    <th>Resumo descrição</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {section name=i loop=$resultAcompanhamento}
                                                                    <tr>
                                                                        <td name="total"> {$resultAcompanhamento[i].DATA|date_format:"%d/%m/%Y %H:%M:%S"} </td>
                                                                        <td name="total"> {$resultAcompanhamento[i].ATIVIDADE} </td>
                                                                        <td name="total"> {$resultAcompanhamento[i].STATUS} </td>
                                                                        <td name="total"> {$resultAcompanhamento[i].USRVENDEDOR} </td>
                                                                        <td name="total"> {$resultAcompanhamento[i].RESULTADO} </td>
        
                                                                    </tr>
                                                                    <p>
                                                                {/section}
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- div class="x_panel" = painel principal-->
                                                </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        
                                            {/if}

                                        </div> <!-- FIM panel-body -->

                                    </div> <!-- FIM collpaseTwo -->
                            

                                    {include file="crm_contas_acompanhamento_cadastro_email.tpl"}
                                </div>

                            </div> <!-- end of accordion -->
                            
                        </div>
                    </div>
        </form>
    </div>

<!-- MODAL NOVO CONTATO -->
<div class="modal fade" tabindex="-1" id="modalNovoContato" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de contato</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                        <span class="fa fa-asterisk" aria-hidden="true"></span>
                        <label for="nome_contato" class="col-form-label">Nome do contato</label>
                        <input class="form-control" autocomplete="off" maxlength="100" type="text" id="nome_contato"
                            name="nome_contato" placeholder="Nome do contato" value={$nome_contato}>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7 col-sm-7 col-xs-7 form-group">
                        <span class="fa fa-globe" aria-hidden="true"></span>
                        <label for="email_contato" class="col-form-label">E-mail</label>
                        <input class="form-control" autocomplete="off" maxlength="50" type="text" id="email_contato" name="email_contato"
                            placeholder="E-mail do contato" value={$email_contato}>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5 form-group">
                        <span class="fa fa-phone-square" aria-hidden="true"></span>
                        <label for="telefone_contato" class="col-form-label">Telefone/Celular</label>
                        <input class="form-control" autocomplete="off" maxlength="20" type="text" id="telefone_contato" name="telefone_contato"
                            placeholder="Telefone ou celular do contato" value={$telefone_contato}>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCancelarContato">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick="javascript:submitSalvaContato();">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL NOVO CONTATO -->


<!-- IMPORTS GENTELLA -->

    <!-- bootstrap-wysiwyg  
	<script src="{$bootstrap}/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
	<script src="{$bootstrap}/jquery.hotkeys/jquery.hotkeys.js"></script>
	<script src="{$bootstrap}/google-code-prettify/src/prettify.js"></script> -->
    <!--Switchery-->
	<script src="{$bootstrap}/switchery/dist/switchery.min.js"></script>
    <!-- starrr -->
	<script src="{$bootstrap}/starrr/dist/starrr.js"></script>
    <!-- moment + daterangepicker ANTES do custom.min (Gentelella usa o plugin; precisa de window.moment) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <!-- Custom Theme Scripts -->
	<script src="{$bootstrap}/build/js/custom.min.js"></script>

<!-- FIM IMPORTS GENTELLA -->

<script type="text/javascript">
jQuery(function ($) {
    var $inp = $('#dataHoraProximoAcomp');
    if (!$inp.length || typeof window.moment === 'undefined' || !$.fn.daterangepicker) {
        return;
    }
    if ($inp.data('daterangepicker')) {
        $inp.data('daterangepicker').remove();
    }
    var startM = window.moment();
    var v = String($inp.val() || '').trim();
    if (v) {
        var parsed = window.moment(v, 'DD/MM/YYYY HH:mm', true);
        if (parsed.isValid()) {
            startM = parsed;
        }
    }
    $inp.daterangepicker(
        {
            startDate: startM,
            endDate: startM,
            parentEl: 'body',
            format: 'DD/MM/YYYY HH:mm',
            singleDatePicker: true,
            autoApply: true,
            timePicker: true,
            timePicker24Hour: false,
            timePickerIncrement: 1,
            timePickerSeconds: false,
            locale: {
                format: 'DD/MM/YYYY HH:mm',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
        },
        function (start) {
            if (document.lancamento && document.lancamento.dataHoraProximoAcomp) {
                document.lancamento.dataHoraProximoAcomp.value = start.format('DD/MM/YYYY HH:mm');
            }
        }
    );
});
</script>