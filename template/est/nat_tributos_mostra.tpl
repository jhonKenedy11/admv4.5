<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_nat_tributos.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Natureza Operação Tributos
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-danger"
                                    onClick="javascript:submitVoltar('nat_operacao');">
                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                        Voltar</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary"
                                    onClick="javascript:submitCadastro('nat_tributos');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Cadastro</span>
                                </button>
                            </li>
                            {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left"
                            novalidate ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=id type=hidden value="">
                            <input name=idNatop type=hidden value={$idNatop}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=opcao type=hidden value={$opcao}>

                            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel">
                                    <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                        data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <h4 class="panel-title">Filtros <i class="fa fa-chevron-down"></i>
                                        </h4>
                                    </a>

                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="estado">Estado</label>
                                                    <SELECT class="form-control" name="filtro_uf_id">
                                                        {html_options values=$filtro_uf_ids output=$filtro_uf_names selected=$filtro_uf_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="tipo">Tipo Pessoa</label>
                                                    <SELECT class="form-control" name="filtro_pessoa_id">
                                                        {html_options values=$filtro_pessoa_ids output=$filtro_pessoa_names selected=$filtro_pessoa_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="origem">Origem</label>
                                                    <SELECT class="form-control" name="filtro_origem_id">
                                                        {html_options values=$filtro_origem_ids output=$filtro_origem_names selected=$filtro_origem_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="tribicms">Trib ICMS</label>
                                                    <SELECT class="form-control" name="filtro_tribIcms_id">
                                                        {html_options values=$filtro_tribIcms_ids output=$filtro_tribIcms_names selected=$filtro_tribIcms_id}
                                                    </SELECT>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="ncm">NCM</label>
                                                    <SELECT class="form-control" name="filtro_ncm_id">
                                                        {html_options values=$filtro_ncm_ids output=$filtro_ncm_names selected=$filtro_ncm_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="anp">ANP</label>
                                                    <SELECT class="form-control" name="filtro_anp_id">
                                                        {html_options values=$filtro_anp_ids output=$filtro_anp_names selected=$filtro_anp_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                    <label for="anp">Tipo</label>
                                                    <SELECT class="form-control" name="filtro_tipoNatOp_id">
                                                        {html_options values=$filtro_tipoNatOp_ids output=$filtro_tipoNatOp_names selected=$filtro_tipoNatOp_id}
                                                    </SELECT>
                                                </div>
                                                <div class="form-group col-md-1 col-sm-12 col-xs-12">
                                                    <button type="button" class="btn btn-warning" onClick=""
                                                        style="padding-top: 6px;margin-top: 20px;">
                                                        <span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span><span> Pesquisar</span></button>
                                                </div>
                                                <div class="form-group col-md-1 col-sm-12 col-xs-12">
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="javascript:submitAtulizarInformacoes('nat_tributos',{$id});"
                                                        style="padding-top: 6px;margin-top: 20px;margin-left: 40px;">
                                                        <span class="glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><span> Atualizar</span></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                        </form>


                    </div>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->

            <!-- panel tabela dados -->




        </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->

    <div class="responsive">
        <div class="x_panel">
            <table id="datatable-buttons" class="table table-bordered jambo_table">
                <!--table class="table table-striped jambo_table bulk_action"-->
                <thead>
                    <tr class="headings">
                        <th>Empresa</th>
                        <th>Natureza Opera&ccedil;&atilde;o</th>
                        <th>Tipo</th>
                        <th>UF</th>
                        <th>Pessoa</th>
                        <th>CST</th>
                        <th>NCM</th>
                        <th>CEST</th>
                        <th>CFOP</th>
                        <th>ICMS(%)</th>
                        <th>Redu&ccedil;&atilde;o Base ICMS(%)</th>
                        <th>PIS(%)</th>
                        <th>COFINS(%)</th>
                        <th>MVA(%)</th>
                        <th>Subst. Tribut&aacute;rio(%)</th>
                        <th>ISS</th>
                        <th>IPI</th>
                        <th class=" no-link last" style="width: 60px;">Manuten&ccedil;&atilde;o</th>
                    </tr>
                </thead>

                <tbody>

                    {section name=i loop=$lanc}
                        {assign var="total" value=$total+1}
                        <tr class="even pointer">
                            <td> {$lanc[i].CENTROCUSTO} </td>
                            <td> {$lanc[i].NATOPERACAO} </td>
                            <td> {$lanc[i].DESCTIPO} </td>
                            <td> {$lanc[i].UF} </td>
                            <td> {$lanc[i].PESSOA} </td>
                            <td> {$lanc[i].ORIGEM}{$lanc[i].TRIBICMS} </td>
                            <td> {$lanc[i].NCM} </td>
                            <td> {$lanc[i].CEST} </td>
                            <td> {$lanc[i].CFOP} </td>
                            <td> {$lanc[i].ALIQICMS|number_format:2:",":"."} </td>
                            <td> {$lanc[i].REDBASEICMS|number_format:2:",":"."} </td>
                            <td> {$lanc[i].ALIQPIS|number_format:2:",":"."} </td>
                            <td> {$lanc[i].ALIQCOFINS|number_format:2:",":"."} </td>

                            <td> {$lanc[i].MVA|number_format:2:",":"."} </td>
                            <td> {$lanc[i].ALIQSITTRIB|number_format:2:",":"."} </td>
                            <td> {$lanc[i].ISS|number_format:2:",":"."} </td>
                            <td> {$lanc[i].IPI|number_format:2:",":"."} </td>

                            <td class=" last">
                                <button type="button" class="btn btn-primary btn-xs"
                                    onclick="javascript:submitAlterar('nat_tributos','{$lanc[i].ID}','{$lanc[i].IDNATOP}');"><span
                                        class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                <button type="button" class="btn btn-danger btn-xs"
                                    onclick="javascript:submitExcluir('nat_tributos','{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                <button type="button" class="btn btn-warning btn-xs"
                                    onclick="javascript:submitCopiarNatureza('nat_tributos','{$lanc[i].ID}');"><span
                                        class="glyphicon glyphicon-copy" aria-hidden="true"></span></button>
                            </td>
                        </tr>
                    {/section}

                </tbody>

            </table>
        </div> <!-- div class="x_content" = inicio tabela -->

    </div> <!-- div class="x_panel" = painel principal-->

</div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->

{include file="template/database.inc"}
<!-- /Datatables -->