<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_orcamento.js"> </script>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="fin">
            <input name=form type=hidden value="orcamento">
            <input name=id type=hidden value="">
            <input name=opcao type=hidden value="">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <h2>Cadastrar Previsão -
                                {if $subMenu eq "cadastrar"}
                                    Cadastro
                                {else}
                                    Altera&ccedil;&atilde;o
                                {/if}
                            </h2>
                            {include file="../bib/msg.tpl"}

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="submit" class="btn btn-primary" id="btnSubmit"
                                        onClick="javascript:submitConfirmar(event);">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div><!-- x_panel  -->

                        <div class="x_content">

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label for="mes">M&ecirc;s</label>
                                <select class="form-control" name=mes id="mes">
                                    {html_options values=$mesBase_ids output=$mesBase_names selected=$mesBase_id}
                                </select>
                            </div>

                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <label for="ano">Ano</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="ano" name="ano" value="{$ano}">
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label for="genero">G&ecirc;nero</label>
                                <select class="form-control" name="genero" id="genero">
                                    {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                </select>
                            </div>

                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label for="filial">Conta</label>
                                <select class="form-control" name="filial" id="filial">
                                    {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <input class="form-control money" maxlength="10" type="money" id="valor"
                                        name="valor" value="{$valor}">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

{include file="template/form.inc"}
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
$(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true
        });
    });
</script>