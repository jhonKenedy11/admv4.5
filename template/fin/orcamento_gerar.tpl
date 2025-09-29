<style type="text/css">
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    .btn-action {
        min-width: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .title-cadastro {
        padding-left: 0;
        width: 100px !important;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_orcamento.js"></script>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name="mod" type="hidden" value="fin">
            <input name="form" type="hidden" value="orcamento">
            <input name=id type=hidden value="">
            <input name="opcao" type="hidden" value="">
            <input name="submenu" type="hidden" value="{$subMenu}">
            <input name="letra" type="hidden" value="{$letra}">
            <input name="mesTrabalho" type="hidden" value="">
            <input name="anoTrabalho" type="hidden" value="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="">
                                <div class="col-md-10 title-cadastro">
                                    <h2>
                                        Gerar Previsão -
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Alteração
                                        {/if}
                                    </h2>
                                </div>
                            </div>

                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="alert alert-success" role="alert">
                                        <strong>Sucesso!</strong> {$mensagem}
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Aviso!</strong> {$mensagem}
                                    </div>
                                {/if}
                            {/if}

                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button type="submit" class="btn btn-primary" name="btnSubmit"
                                        onclick="submitGeraOrcamento(event);">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        <span> Gerar</span>
                                    </button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label for="mesBase">Mês</label>
                                    <select class="form-control" name="mesBase" id="mesBase">
                                        {html_options values=$mesBase_ids output=$mesBase_names selected=$mesBase_id}
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label for="anoBase">Ano</label>
                                    <input type="text" class="form-control" id="anoBase" name="anoBase" value="{$anoBase}">
                                </div>

                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label for="filial">Conta</label>
                                    <select class="form-control" name="filial" id="filial">
                                        {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label for="media">Média de Meses</label>
                                    <select class="form-control" name="media" id="media">
                                        {html_options values=$media_ids selected=$media_id output=$media_names}
                                    </select>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>

    {include file="template/form.inc"}
</div>