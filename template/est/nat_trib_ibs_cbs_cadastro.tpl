<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_nat_trib_ibs_cbs.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="est">
            <input name=form type=hidden value="nat_trib_ibs_cbs">
            <input name=id type=hidden value={$id}>
            <input name=idNatOp type=hidden value={$idNatOp}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Tributos IBS/CBS - Cadastro
                                {else}
                                    Tributos IBS/CBS - Altera&ccedil;&atilde;o
                                {/if}
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('nat_trib_ibs_cbs');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                        <span> Voltar</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('nat_trib_ibs_cbs');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        <span> Confirmar</span>
                                    </button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                            <!-- Natureza de Operação (somente leitura) -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="natOperacao">Natureza Opera&ccedil;&atilde;o</label>
                                    <div class="col-md-9 col-sm-12 col-xs-12">
                                        <input id="natOperacao" name="natOperacao" type="text" disabled class="form-control" value="{$natOperacao}">
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row titleSession"><center> DESTINO </center></div>
                            <br />

                            <!-- UF Destino e Pessoa -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-2" for="uf_dest">UF Destino <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control" name="uf_dest" id="uf_dest" title="Unidade da Federa&ccedil;&atilde;o de destino.">
                                            {html_options values=$uf_ids selected=$uf_dest output=$uf_names}
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="pessoa">Pessoa <span class="required">*</span></label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control" name="pessoa" id="pessoa" title="Tipo de pessoa (F&iacute;sica ou Jur&iacute;dica).">
                                            {html_options values=$pessoa_ids selected=$pessoa output=$pessoa_names}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Município e Código Município -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-2" for="mun_dest">Munic&iacute;pio</label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control js-example-basic-single" id="mun_dest" name="mun_dest" 
                                            title="Nome do munic&iacute;pio de destino.">
                                            <option value="">Selecione a UF primeiro</option>
                                        </select>
                                        <input type="hidden" id="mun_dest_valor" value="{$mun_dest}">
                                    </div>
                                    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="cod_mun_dest">C&oacute;d. IBGE</label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <input class="form-control" id="cod_mun_dest" name="cod_mun_dest" type="text" 
                                            title="C&oacute;digo IBGE do munic&iacute;pio." value="{$cod_mun_dest}" maxlength="10" readonly>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row titleSession"><center> CLASSIFICA&Ccedil;&Atilde;O TRIBUTÁRIA </center></div>
                            <br />

                            <!-- CClassTrib e NCM -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-2" for="cclasstrib">CClassTrib</label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control js-example-basic-single" name="cclasstrib" id="cclasstrib" 
                                            title="Classe de Classifica&ccedil;&atilde;o Tribut&aacute;ria IBS/CBS.">
                                            {html_options values=$cclasstrib_ids selected=$cclasstrib output=$cclasstrib_names}
                                        </select>
                                    </div>
                                    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="ncm">NCM</label>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <select class="form-control js-example-basic-single" name="ncm" id="ncm" 
                                            title="Nomenclatura Comum do Mercosul.">
                                            {html_options values=$ncm_ids selected=$ncm output=$ncm_names}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row titleSession"><center> AL&Iacute;QUOTAS </center></div>
                            <br />

                            <!-- Alíquotas IBS e CBS -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-2" for="ibs_uf">IBS UF</label>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control has-feedback-left money" type="text" id="ibs_uf" name="ibs_uf" 
                                            title="Al&iacute;quota IBS estadual." value="{$ibs_uf}">
                                        <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span>
                                    </div>
                                    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="ibs_mun">IBS Mun</label>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control has-feedback-left money" type="text" id="ibs_mun" name="ibs_mun" 
                                            title="Al&iacute;quota IBS municipal." value="{$ibs_mun}">
                                        <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span>
                                    </div>
                                    <label class="control-label col-md-1 col-sm-1 col-xs-1" for="cbs">CBS</label>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <input class="form-control has-feedback-left money" type="text" id="cbs" name="cbs" 
                                            title="Al&iacute;quota CBS (Contribui&ccedil;&atilde;o sobre Bens e Servi&ccedil;os)." value="{$cbs}">
                                        <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span>
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

{include file="template/form.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
<script>
    $(document).ready(function(){
        initCadastroTribIbsCbs();
    });
</script>

