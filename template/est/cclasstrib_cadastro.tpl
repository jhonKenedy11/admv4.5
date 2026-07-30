<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
.panel-group {
    margin-bottom: 10px;
}
.panel-heading {
    cursor: pointer;
    background-color: #f5f5f5;
}
.panel-title {
    font-size: 14px;
    font-weight: bold;
}
.checkbox-inline {
    margin-right: 15px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_cclasstrib.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">      
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento" ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="est">   
            <input name=form          type=hidden value="cclasstrib">   
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
                                    CClassTrib - Cadastro 
                                {else}
                                    CClassTrib - Altera&ccedil;&atilde;o 
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
                            
                            <!-- Dados Principais -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Dados Principais</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <label for="cclasstrib">CClassTrib *</label>
                                            <input class="form-control" type="text" maxlength="6" required id="cclasstrib" 
                                                name="cclasstrib" {if $subMenu eq "alterar"} disabled {/if} 
                                                placeholder="Código" value={$cclasstrib}>
                                        </div>

                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <label for="nome">Nome *</label>
                                            <input class="form-control" type="text" maxlength="100" required id="nome" name="nome" 
                                                placeholder="Nome do CClassTrib" value={$nome}>
                                        </div>

                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <label for="cst">CST IBS/CBS</label>
                                            <select class="form-control" name="cst" id="cst">
                                                {html_options values=$cst_ids selected=$cst output=$cst_names}
                                            </select>
                                        </div>

                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <label for="tipo_aliquota">Tipo Alíquota</label>
                                            <input class="form-control" type="text" maxlength="20" id="tipo_aliquota" 
                                                name="tipo_aliquota" value={$tipo_aliquota}>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="descricao">Descrição</label>
                                            <textarea class="form-control" rows="2" maxlength="260" id="descricao" name="descricao" 
                                                placeholder="Descrição do CClassTrib">{$descricao|replace:"'":""}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LC e Vigência -->
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-target="#panel-lc">
                                    <h4 class="panel-title">LC e Vigência</h4>
                                </div>
                                <div id="panel-lc" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="lc_214_25">LC 214/25</label>
                                                <input class="form-control" type="text" maxlength="20" id="lc_214_25" 
                                                    name="lc_214_25" value={$lc_214_25}>
                                            </div>

                                            <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                                <label for="d_ini_vig">Início Vigência</label>
                                                <input class="form-control has-feedback-left" type="text" id="d_ini_vig" 
                                                    name="d_ini_vig" data-inputmask="'mask': '99/99/9999'" 
                                                    placeholder="DD/MM/AAAA" value="{$d_ini_vig}">
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                            </div>

                                            <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                                <label for="d_fim_vig">Fim Vigência</label>
                                                <input class="form-control has-feedback-left" type="text" id="d_fim_vig" 
                                                    name="d_fim_vig" data-inputmask="'mask': '99/99/9999'" 
                                                    placeholder="DD/MM/AAAA" value="{$d_fim_vig}">
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                            </div>

                                            <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                                <label for="data_atualizacao">Data Atualização</label>
                                                <input class="form-control has-feedback-left" type="text" id="data_atualizacao" 
                                                    name="data_atualizacao" data-inputmask="'mask': '99/99/9999'" 
                                                    placeholder="DD/MM/AAAA" value="{$data_atualizacao}">
                                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="lc_redacao">LC Redação</label>
                                                <textarea class="form-control" rows="3" id="lc_redacao" name="lc_redacao" 
                                                    placeholder="Redação da LC">{$lc_redacao}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Indicadores Gerais -->
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-target="#panel-indicadores">
                                    <h4 class="panel-title">Indicadores </h4>
                                </div>
                                <div id="panel-indicadores" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="pred_ibs">pRedIBS</label>
                                                <select class="form-control" id="pred_ibs" name="pred_ibs">
                                                    <option value="0" {if $pred_ibs eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $pred_ibs eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="pred_cbs">pRedCBS</label>
                                                <select class="form-control" id="pred_cbs" name="pred_cbs">
                                                    <option value="0" {if $pred_cbs eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $pred_cbs eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_trib_regular">Trib Regular</label>
                                                <select class="form-control" id="ind_g_trib_regular" name="ind_g_trib_regular">
                                                    <option value="0" {if $ind_g_trib_regular eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_trib_regular eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_cred_pres_oper">Cred Pres Oper</label>
                                                <select class="form-control" id="ind_g_cred_pres_oper" name="ind_g_cred_pres_oper">
                                                    <option value="0" {if $ind_g_cred_pres_oper eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_cred_pres_oper eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_mono_padrao">Mono Padrão</label>
                                                <select class="form-control" id="ind_g_mono_padrao" name="ind_g_mono_padrao">
                                                    <option value="0" {if $ind_g_mono_padrao eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_mono_padrao eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_mono_reten">Mono Reten</label>
                                                <select class="form-control" id="ind_g_mono_reten" name="ind_g_mono_reten">
                                                    <option value="0" {if $ind_g_mono_reten eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_mono_reten eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_mono_ret">Mono Ret</label>
                                                <select class="form-control" id="ind_g_mono_ret" name="ind_g_mono_ret">
                                                    <option value="0" {if $ind_g_mono_ret eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_mono_ret eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_mono_dif">Mono Dif</label>
                                                <select class="form-control" id="ind_g_mono_dif" name="ind_g_mono_dif">
                                                    <option value="0" {if $ind_g_mono_dif eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_mono_dif eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label for="ind_g_estorno_cred">Estorno Cred</label>
                                                <select class="form-control" id="ind_g_estorno_cred" name="ind_g_estorno_cred">
                                                    <option value="0" {if $ind_g_estorno_cred eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_g_estorno_cred eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Indicadores de Documentos Fiscais -->
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-target="#panel-documentos">
                                    <h4 class="panel-title">Documentos Fiscais</h4>
                                </div>
                                <div id="panel-documentos" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nfe_abi">NFe ABI</label>
                                                <select class="form-control" id="ind_nfe_abi" name="ind_nfe_abi">
                                                    <option value="0" {if $ind_nfe_abi eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nfe_abi eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nfe">NFe</label>
                                                <select class="form-control" id="ind_nfe" name="ind_nfe">
                                                    <option value="0" {if $ind_nfe eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nfe eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nf_ce">NFCe</label>
                                                <select class="form-control" id="ind_nf_ce" name="ind_nf_ce">
                                                    <option value="0" {if $ind_nf_ce eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nf_ce eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_cte">CTe</label>
                                                <select class="form-control" id="ind_cte" name="ind_cte">
                                                    <option value="0" {if $ind_cte eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_cte eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_cte_os">CTe OS</label>
                                                <select class="form-control" id="ind_cte_os" name="ind_cte_os">
                                                    <option value="0" {if $ind_cte_os eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_cte_os eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_bpe">BPe</label>
                                                <select class="form-control" id="ind_bpe" name="ind_bpe">
                                                    <option value="0" {if $ind_bpe eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_bpe eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_bpe_ta">BPe TA</label>
                                                <select class="form-control" id="ind_bpe_ta" name="ind_bpe_ta">
                                                    <option value="0" {if $ind_bpe_ta eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_bpe_ta eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_bpe_tm">BPe TM</label>
                                                <select class="form-control" id="ind_bpe_tm" name="ind_bpe_tm">
                                                    <option value="0" {if $ind_bpe_tm eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_bpe_tm eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nf_3e">NF3e</label>
                                                <select class="form-control" id="ind_nf_3e" name="ind_nf_3e">
                                                    <option value="0" {if $ind_nf_3e eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nf_3e eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nfse">NFSe</label>
                                                <select class="form-control" id="ind_nfse" name="ind_nfse">
                                                    <option value="0" {if $ind_nfse eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nfse eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nfse_via">NFSe Via</label>
                                                <select class="form-control" id="ind_nfse_via" name="ind_nfse_via">
                                                    <option value="0" {if $ind_nfse_via eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nfse_via eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nf_com">NF Com</label>
                                                <select class="form-control" id="ind_nf_com" name="ind_nf_com">
                                                    <option value="0" {if $ind_nf_com eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nf_com eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nf_ag">NF Ag</label>
                                                <select class="form-control" id="ind_nf_ag" name="ind_nf_ag">
                                                    <option value="0" {if $ind_nf_ag eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nf_ag eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_nf_gas">NF Gas</label>
                                                <select class="form-control" id="ind_nf_gas" name="ind_nf_gas">
                                                    <option value="0" {if $ind_nf_gas eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_nf_gas eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-xs-6">
                                                <label for="ind_dere">DERE</label>
                                                <select class="form-control" id="ind_dere" name="ind_dere">
                                                    <option value="0" {if $ind_dere eq '0'}selected{/if}>Não</option>
                                                    <option value="1" {if $ind_dere eq '1'}selected{/if}>Sim</option>
                                                </select>
                                            </div>
                                        </div>
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

<style type="text/css">
.form-control:focus {
    border-color: #159ce4;
    transition: all 0.7s ease;
}
</style>

<script>
$(document).ready(function() {
    // DateRangePicker para campos de data
    $('#d_ini_vig').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        calender_style: "picker_1",
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
        }
    });

    $('#d_fim_vig').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        calender_style: "picker_1",
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
        }
    });

    $('#data_atualizacao').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        calender_style: "picker_1",
        locale: {
            format: 'DD/MM/YYYY',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
        }
    });

    // Esconde o calendário ao perder foco
    $('#d_ini_vig').on('blur', function() {
        $('.daterangepicker').hide();
    });

    $('#d_fim_vig').on('blur', function() {
        $('.daterangepicker').hide();
    });

    $('#data_atualizacao').on('blur', function() {
        $('.daterangepicker').hide();
    });
});
</script>
