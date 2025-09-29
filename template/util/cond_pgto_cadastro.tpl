<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_cond_pgto.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="util">
            <input name=form type=hidden value="cond_pgto">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Condição de Pagamento - Cadastro
                                {else}
                                    Condição de Pagamento - Altera&ccedil;&atilde;o
                                {/if}
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
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>--Aviso!</strong>&nbsp;{$mensagem}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar('banco');">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger"
                                        onClick="javascript:submitVoltar('banco');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="motivo">Condição de
                                    Pagamento <span class="required">*</span>
                                </label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" required id="id" name="id" value={$id}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3"
                                    for="descricao">Descri&ccedil;&atilde;o <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" maxlength="30" required id="descricao"
                                        tittle="Preencha este campo com letras ou numeros, até 30 caracteres."
                                        name="descricao" placeholder="Digite a descrição da Atividade."
                                        value={$descricao}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="motivo">Forma Pagamento
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <select class="form-control form-control-sm" name="formaPgto" id="formaPgto">
                                        {html_options values=$formaPagamento_ids selected=$formaPagamento_id output=$formaPagamento_names}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="motivo">Num parcelas <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" required id="numparcelas" name="numparcelas"
                                        value={$numparcelas}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="motivo">Situação Lançamento
                                    Financeiro <span class="required">*</span>
                                </label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <select class="form-control" name="situacaoLcto" id="situacaoLcto">
                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                    </select>
                                </div>
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