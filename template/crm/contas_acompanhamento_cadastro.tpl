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

    .select2-selection--single {
        border-radius: 10px !important;
    }

    .select2-results__option {
        border-radius: 10px !important;
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
</style>
<script type="text/javascript" src="{$pathJs}/crm/s_contas_acompanhamento.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
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
            <input name=mod type=hidden value="">
            <input name=form type=hidden value="">
            <input name=acao type=hidden value={$acao}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=letra type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=pessoaNome type=hidden value={$pessoaNome}>
            <input name=vendedorAcomp type=hidden value={$vendedorAcomp_id}>
            <input name=dataContato type=hidden value={$dataContato}>
            <input name=horaContato type=hidden value={$horaContato}>
            <input name=fornecedor type=hidden value="">
            <!-- dashboard -->
            <input name=dashboard_origem type=hidden value={$dashboard_origem}>
            <input name=data_previous type=hidden value={$data_previous}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
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
                        <div class="x_content" style="padding: 0;">
                            <div class="row">
                                <div class="offset-sm-1 col-md-1"></div>
                                <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                                    <label for="vendedorAcomp">Colaborador</label>
                                    <div class="panel panel-default">
                                        <SELECT class="form-control" name="vendedorAcomp" {if $verTodosVend !== true}
                                            disabled{/if}>
                                            {html_options values=$vendedorAcomp_ids output=$vendedorAcomp_names selected=$vendedorAcomp_id}
                                        </SELECT>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                                    <label for="clienteCombo">Pessoa</label>
                                    <select class="js-data-example-ajax form-control" name="clienteCombo"
                                        id="clienteCombo" onChange="javascript:setCliente('');">
                                        {html_options values=$cliente_ids output=$cliente_names selected=$cliente_id}
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-1 offset-sm-1"></div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="acao">Ação</label>
                                    <div class="panel panel-default">
                                        <SELECT class="form-control" name="acao">
                                            {html_options values=$acao_ids output=$acao_names selected=$acao_id}
                                        </SELECT>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-4">
                                    <label for="proximoContato">Prox. Contato</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" id="proximoContato"
                                            name="proximoContato" placeholder="Data proximo contato."
                                            data-inputmask="'mask': '99/99/9999 99:99'" value={$proximoContato}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-4">
                                    <label for="dataContato">Data</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" id="dataContato" disabled
                                            name="dataContato" value={$dataContato}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-4">
                                    <label for="proximoContato">Cotação/Pedido</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" readonly type="text" id="idPedido" name="idPedido"
                                            placeholder="Número Cotação/Pedido." value={$idPedido}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-1 offset-sm-1"></div>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <label for="resultContato">Acompanhamento:</label>
                                    <div class="panel panel-default">
                                        <textarea class="form-control" rows="7" id="resultContato"
                                            placeholder="Digite acompanhamento do contato realizado."
                                            name="resultContato">{$resultContato}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- dados adicionaris -->
                            <!-- start accordion -->
                            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel">
                                    <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                                        </h4>
                                    </a>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <div class="x_panel">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                                        <label for="veiculo">Ve&iacute;culo</label>
                                                        <SELECT class="form-control" name="veiculo">
                                                            {html_options values=$veiculo_ids output=$veiculo_names selected=$veiculo_id}
                                                        </SELECT>
                                                    </div>

                                                    <div class="col-md-1 col-sm-1 col-xs-6">
                                                        <label for="km">Km</label>
                                                        <input class="form-control" maxlength="10" type="text" id="km"
                                                            name="km" value={$km}>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <label for="origem">Origem</label>
                                                        <input class="form-control" type="text" maxlength="20"
                                                            id="origem" name="origem"
                                                            placeholder="Digite a origem do trajeto." value={$origem}>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <label for="destino">Destino</label>
                                                        <input class="form-control" type="text" maxlength="20"
                                                            id="destino" name="destino"
                                                            placeholder="Digite o destino do trajeto." value={$destino}>
                                                    </div>
                                                </div>
                                                <!--FIM row -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of accordion -->
                        </div>
                    </div>
        </form>
    </div>

{include file="template/form.inc"}
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script>
$(document).ready(function() {
    debugger
    $('#clienteCombo').select2({
        placeholder: "Buscar",
        language: {
            //Descricao da quantidade de caracteres.
            inputTooShort: function() {
                return "Digite no mínimo 3 caracteres";
            }
        },
        minimumInputLength: 3,
        delay: 250,
        ajax: {
            dataType: "json",
            type: "POST",
            url: document.URL +
                "?mod=crm&form=contas_acompanhamento&submenu=pesquisaClienteAjax&opcao=blank",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Ajax-Busca-Cliente", "true");
            },
            processResults: function(response) {
                debugger
                return {
                    results: response
                };
            }
        }
    });
});
</script>
<script>
$(document).ready(function() {
    debugger
    var data = {
        id: {$pessoa},
        text: {$pessoaNome}
    };

    var newOption = new Option(data.text, data.id, false, false);
    $('#clienteCombo').append(newOption).trigger('change');
});
</script>