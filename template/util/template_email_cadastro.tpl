<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
    .editor-wrapper {
        min-height: 260px;
        width: 100%;
        background: #fff;
        border: 1px solid #ddd;
        padding: 10px;
        overflow: auto;
        border-radius: 5px;
        font-family: monospace;
        font-size: 12px;
        resize: vertical;
    }
    .template-preview-frame {
        width: 100%;
        min-height: 320px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #fff;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_template_email.js"></script>
<div class="right_col" role="main">
    <div class="">

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" name="lancamento"
            action="{$SCRIPT_NAME}" method="post">
            <input name=mod type=hidden value="util">
            <input name=form type=hidden value="template_email">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input type="hidden" id="id" name="id" value="{$lanc[0].ID}">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Template de e-mail - Cadastro
                                {else}
                                    Template de e-mail - Altera&ccedil;&atilde;o
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
                                        onclick="javascript:submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
                                        <span> Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger"
                                        onclick="javascript:submitVoltar();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                        <span> Voltar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="descricao">Descri&ccedil;&atilde;o
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" maxlength="30" required id="descricao"
                                        name="descricao" value="{$lanc[0].DESCRICAO}" placeholder="Descri&ccedil;&atilde;o do template de e-mail">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="parametro">Par&acirc;metro</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control" type="text" maxlength="100" id="parametro" name="parametro"
                                        value="{$lanc[0].PARAMETRO}" placeholder="Identificador / par&acirc;metro (ex.: CRM_ACOMP)">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <textarea id="editor-one" class="editor-wrapper" spellcheck="false" placeholder="Cole/edite aqui o HTML do e-mail">{$lanc[0].BODY|default:''|escape:'html'}</textarea>
                                    <textarea id="body" name="body" style="display:none;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <iframe id="template-preview" class="template-preview-frame" sandbox="allow-same-origin"></iframe>
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
