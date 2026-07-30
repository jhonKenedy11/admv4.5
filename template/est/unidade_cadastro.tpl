<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_unidade.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" name="lancamento"
            action="{$SCRIPT_NAME}" method="post">
            <input name=mod type=hidden value="est">
            <input name=form type=hidden value="unidade">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Unidade - Cadastro
                                {else}
                                    Unidade - Altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'Sucesso' || $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div class="alert alert-success" role="alert"><strong>Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta' || $tipoMsg eq 'Alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div class="alert alert-danger" role="alert"><strong>Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    {/if}
                                {/if}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unidade">Sigla <span class="required"></span></label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" maxlength="3" id="unidade" name="unidade"
                                        placeholder="Ex.: UN" style="text-transform:uppercase;"
                                        {if $subMenu neq "cadastrar"}disabled{/if} value={$unidade}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descricao">Descri&ccedil;&atilde;o <span class="required"></span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" maxlength="40" id="descricao" name="descricao"
                                        placeholder="Digite a descrição." value={$descricao}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ativo">Ativo <span class="required"></span></label>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <select name="ativo" id="ativo" class="form-control">
                                        <option value="S" {if $ativo eq 'S'}selected{/if}>Sim</option>
                                        <option value="N" {if $ativo eq 'N'}selected{/if}>N&atilde;o</option>
                                    </select>
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
</div>
