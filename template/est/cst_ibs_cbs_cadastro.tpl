<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_cst_ibs_cbs.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">      
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento" ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="est">   
            <input name=form          type=hidden value="cst_ibs_cbs">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            {if $subMenu eq "alterar"}  
                <input name=id type=hidden value={$id}> 
            {/if}

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    CST IBS/CBS - Cadastro 
                                {else}
                                    CST IBS/CBS - Altera&ccedil;&atilde;o 
                                {/if}
                            </h2>
                            {include file="../bib/msg.tpl"}
                            
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmar();">
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
                            <div class="d-flex justify-content-center">

                                <div class="form-group">
                                    <div class="col-md-2 col-sm-12 col-xs-12"></div>

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <label for="cst">CST</label>
                                        <input class="form-control" type="text" maxlength="3" required id="cst" 
                                            name="cst" {if $subMenu eq "alterar"} disabled {/if} 
                                            placeholder="Código CST" value={$cst}>
                                    </div>

                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="descricao">Descri&ccedil;&atilde;o</label>
                                        <input class="form-control" type="text" maxlength="256" required id="descricao" name="descricao" 
                                            placeholder="Digite a descrição do CST." value={$descricao}>
                                    </div>
                                </div>

                                <!-- Campo ID oculto - não relevante para o usuário -->
                                <input type="hidden" id="id" name="id" value={$id}>

                            </div>

                            <div class="ln_solid"></div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {include file="template/form.inc"}

<style type="text/css">
.form-control:focus {
    border-color: #159ce4;
    transition: all 0.7s ease;
}
</style>

