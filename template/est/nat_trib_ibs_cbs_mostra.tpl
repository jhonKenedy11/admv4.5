<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_nat_trib_ibs_cbs.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <!-- panel principal -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Tributos IBS/CBS - {$natOperacao}</h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-danger"
                                    onClick="javascript:submitVoltarNatOp();">
                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                    <span> Voltar</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary"
                                    onClick="javascript:submitCadastro('nat_trib_ibs_cbs');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <span> Cadastro</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left"
                            novalidate ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=id type=hidden value="">
                            <input name=idNatOp type=hidden value={$idNatOp}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="responsive">
            <div class="x_panel">
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr class="headings">
                            <th>UF Dest.</th>
                            <th>Munic&iacute;pio</th>
                            <th>C&oacute;d. Mun.</th>
                            <th>Pessoa</th>
                            <th>CClassTrib</th>
                            <th>NCM</th>
                            <th>IBS UF (%)</th>
                            <th>IBS Mun (%)</th>
                            <th>CBS (%)</th>
                            <th class="no-link last" style="width: 100px;">Manuten&ccedil;&atilde;o</th>
                        </tr>
                    </thead>

                    <tbody>
                        {section name=i loop=$lanc}
                            <tr class="even pointer">
                                <td>{$lanc[i].UF_DEST}</td>
                                <td>{$lanc[i].MUN_DEST}</td>
                                <td>{$lanc[i].COD_MUN_DEST}</td>
                                <td>{if $lanc[i].TIPO_PESSOA == 'F'}Física{else}Jurídica{/if}</td>
                                <td>{$lanc[i].CCLASSTRIB}</td>
                                <td>{$lanc[i].NCM}</td>
                                <td>{$lanc[i].ALIQUOTA_IBS_UF|number_format:2:",":"."}</td>
                                <td>{$lanc[i].ALIQUOTA_IBS_MUN|number_format:2:",":"."}</td>
                                <td>{$lanc[i].ALIQUOTA_CBS|number_format:2:",":"."}</td>
                                <td class="last">
                                    <button type="button" class="btn btn-primary btn-xs"
                                        onclick="javascript:submitAlterar('nat_trib_ibs_cbs','{$lanc[i].ID}');">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs"
                                        onclick="javascript:submitExcluir('nat_trib_ibs_cbs','{$lanc[i].ID}');">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs"
                                        onclick="javascript:submitCopiar('nat_trib_ibs_cbs','{$lanc[i].ID}');">
                                        <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
                                    </button>
                                </td>
                            </tr>
                        {/section}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

