
<style>
    .select2-container--default .select2-selection--single {
        border-radius: 5px !important;
        height: 34px !important;
        padding: 2px 12px;
    }

    .x_panel {
        background: #fff;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert {
        padding: 10px;
        margin: 0;
        border-radius: 4px;
    }

    .form-control {
        border-radius: 5px;
    }
</style>
<!--meta http-equiv="content-type" content="text/html; charset=utf-8"-->
<script type="text/javascript" src="{$pathJs}/fin/s_orcamento.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h2>
                    {if $subMenu eq "cadastrar"}
                        Cadastro de Orçamento
                    {else}
                        Alteração de Orçamento
                    {/if}
                </h2>
            </div>
        </div>
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="post"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="ped"> 
            <input name=form type=hidden value="pedido_orcamento">
            <input name=opcao type=hidden value="">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $mensagem neq ''}
                                    <div class="alert alert-{if $tipoMsg eq 'sucesso'}success{else}danger{/if}"
                                        role="alert">
                                        {if $tipoMsg eq 'sucesso'}✔{else}⚠{/if} {$mensagem}
                                    </div>
                                {/if}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br>
                            <div class="form-group">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Mês</label>
                                    <select name="mes" class="form-control select2_single">
                                        {html_options values=$mesBase_ids output=$mesBase_names selected=$mesBase_id}
                                    </select>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Ano</label>
                                    <input type="text" class="form-control" name="ano" value="{$ano}">
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Gênero</label>
                                    <select name="genero" class="form-control select2_single">
                                        {html_options values=$genero_ids output=$genero_names selected=$genero_id}
                                    </select>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Filial</label>
                                    <select name="filial" class="form-control select2_single">
                                        {html_options values=$filial_ids output=$filial_names selected=$filial_id}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <label>Valor</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">R$</span>
                                        <!-- Altere a linha com o erro para: -->
                                        <input type="text" class="form-control money" name="valor"
                                            value="{$valor|number_format:2:',':'.'}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- INCLUDES DE BIBLIOTECAS -->
<link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet">
<script src="{$bootstrap}/select2-master/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicialização do Select2
        $('.select2_single').select2({
            theme: "classic",
            width: '100%'
        });

        // Máscaras monetárias
        $('.money').mask('#.##0,00', {ldelim}reverse: true{rdelim});
        // Datepicker
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ]
            }
        });
    });
</script>
