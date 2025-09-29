<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<!--meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"-->
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_form.js"> </script>

<div class="right_col" role="main">
    <div class="">

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="util">
            <input name=form type=hidden value="form">
            <input name=opcao type=hidden value="">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                        <h2>
                            {if $subMenu eq "cadastrar"}
                                Formul&aacute;rios - Cadastro 
                            {else}
                                Formul&aacute;rios - Altera&ccedil;&atilde;o 
                            {/if}
                        </h2>
                            {include file="../bib/msg.tpl"}

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary" id="btnSubmit"
                                        onClick="javascript:submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form class="container" novalidate="" action="/echo" method="POST" id="myForm">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Nome
                                        Formul&aacute;rio</label>
                                    <div class="col-md-2 col-sm-4 col-xs-6">
                                        <input id="nomeForm" name="nomeForm" type="text" required="true"
                                            class="form-control col-md-7 col-xs-12"
                                            tittle="Preencha este campo com letras ou numeros, até 60 caracteres."
                                            placeholder="Nome do Formul&aacute;rio." value={$nomeForm}>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"
                                        for="descricao">Descri&ccedil;&amacr;o</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="descricao" name="descricao" type="text" required="true"
                                            class="form-control col-md-7 col-xs-12"
                                            placeholder="Digite a Descri&ccedil;&amacr;o." value={$descricao}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="help">Help</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea style="width: 40em" rows="5" id="help" name="help"
                                            value={$help}></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="ln_solid"></div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>