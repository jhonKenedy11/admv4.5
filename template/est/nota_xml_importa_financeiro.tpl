<style>
.form-control, .x_panel{
    border-radius: 5px;
}
.swal-modal{
    width: 500px;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_xml_importa.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="class">

        <div class="page-title">
            <div class="title_left">
                <h3></h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="est">
            <input name=form type=hidden value="nota_xml_importa">
            <input name=opcao type=hidden value="{$opcao}">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>
            <input name=cliente type=hidden value={$cliente}>
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=fornecedor type=hidden value=''>
            <input name=descCondPgto type=hidden value="{$descCondPgto}">
            <input name=numParcelaAdd type=hidden value="{$numParcelaAdd}">
            <input name=dadosFinanceiros type=hidden value="{$dadosFinanceiros}">
            <input name=cnpj type=hidden value="{$cnpj}">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                Gerar Financeiro
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button type="button" class="btn btn-primary" onClick="javascript:submitGerarFinanceiro();">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>Confirmar</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-danger" onClick="javascript:submitVoltarFinanceiro('');">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span>Cancelar</span>
                                    </button>
                                </li>
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li>
                                    <a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>

                            <div class="clearfix"></div>

                        </div> <!-- <div class="x_title"> -->

                        <div class="x_content small">
                            <div class="row">
                                <h5>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <label for="pedido">N&uacute;mero</label>
                                        <div class="panel panel-default">
                                            <input type="text" class="form-control" id="numero" name="numero" disabled value="{$numero}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <label for="data">Data</label>
                                        <div class="panel panel-default">
                                            <input type="text" class="form-control" id="data" name="data" disabled value="{$data|date_format:"%d/%m/%Y"}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 ">
                                        <label for="total">T O T A L</label>
                                        <div class="panel panel-default left_col">
                                            <input class="form-control" type="text" id="total" name="total" readonly
                                                value={$total}>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label for="fornecedorNome">Fornecedor</label>
                                        <div class="panel panel-default">
                                            <input type="text" class="form-control" id="fornecedorNome"
                                                name="fornecedorNome" disabled value="{$fornecedorNome}">
                                        </div>
                                    </div>
                                </h5>
                            </div>

                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <label for="serie">Serie</label>
                                    <input class="form-control" type="text" id="serie" name="serie" value={$serie}>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                                    <select id="idNatop" name="idNatop" class="form-control">
                                        {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                                    <select id="condPgto" name="condPgto" class="form-control"
                                        onChange="javascript:submitAtualPedidoCondPG('N');">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="centroCusto">Centro de Custo</label>
                                    <select id="centroCusto" name="centroCusto" class="form-control"
                                        onChange="javascript:submitAtual({$id});">
                                        {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label for="genero">G&ecirc;nero</label>
                                    <select id="genero" name="genero" class="form-control">
                                        {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="x_panel">
                            <div class="tabpanel" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab_content1" id="parcelas-tab"
                                            role="tab" data-toggle="tab" aria-expanded="true">Parcelas</a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab"
                                            id="transportadora-tab" data-toggle="tab"
                                            aria-expanded="false">Transportador / Observa&ccedil;&atilde;o</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                        <!-- panel tabela dados -->
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                            <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                                <thead>
                                                    <tr style="background: gray; color: white;">
                                                        <th>Parcela</th>
                                                        <th>Data Vencimento</th>
                                                        <th>Valor</th>
                                                        <th>Tipo Documento</th>
                                                        <th>Conta Recebimento</th>
                                                        <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                                        <th>Obs</th>
                                                        <td style="width: 50px;"> <button type="button"
                                                                class="btn btn-success btn-xs"
                                                                onClick="javascript:submitAtualPedidoCondPG('S',{$numParcelaAdd});"><span
                                                                    class="glyphicon glyphicon-plus"
                                                                    aria-hidden="true"></span></button> </td>
                                                        <td style="width: 20px;"> <button type="button"
                                                                class="btn btn-warning btn-xs"
                                                                onClick="javascript:submitAtualPedidoCondPG('S',{-2}"
                                                                );"><span class="glyphicon glyphicon-minus"
                                                                    aria-hidden="true"></span></button> </td>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    {section name=i loop=$fin}
                                                        {assign var="total" value=$total+1}
                                                        <tr id="dados_financeiro">
                                                            <td> {$fin[i].PARCELA} </td>
                                                            <td>
                                                                <input class="form-control" type="text" id="venc"
                                                                    name="venc{$fin[i].PARCELA}"
                                                                    value={$fin[i].VENCIMENTO|date_format:"%d/%m/%Y"}>
                                                            </td>
                                                            <td>
                                                                <input class="form-control money" type="text" id="valor"
                                                                    name="valor{$fin[i].PARCELA}" value={$fin[i].VALOR}>

                                                            </td>
                                                            <td>
                                                                <select id="idTipoDoc" name="tipo{$fin[i].PARCELA}"
                                                                    class="form-control">
                                                                    {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select id="idConta" name="conta{$fin[i].PARCELA}"
                                                                    class="form-control">
                                                                    {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select id="idSitucao" name="situacao{$fin[i].PARCELA}"
                                                                    class="form-control">
                                                                    {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                                </select>
                                                            </td>
                                                            <td colspan="3">

                                                                <input class="form-control" type="text" id="obs"
                                                                    name="obs{$fin[i].PARCELA}" value={$fin[i].OBS}>

                                                            </td>
                                                        </tr>
                                                        <p>
                                                    {/section}
                                                </tbody>
                                            </table>
                                        </div> <!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->
                                    </div> <!-- <div role="tabpanel" -->
                                </div> <!-- <div id="myTabContent" -->
                                <div class="ln_solid"></div>
                            </div> <!-- <div class="tabpanel" -->
                        </div> <!-- <div class="x_panel"> -->
                    </div> <!-- <div class="x_panel"> -->
                </div> <!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->
            </div> <!-- <div class="row"> -->
        </form>
    </div> <!-- <div class="class"> -->
</div> <!-- <div class="right_col" role="main"> -->

{include file="template/form.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
        });
    });
</script>
<script>
    document.addEventListener('keydown', function(event) {
        // Verificar se a tecla pressionada é F5 ou Ctrl+F5
        if (event.keyCode === 116 || (event.keyCode === 116 && event.ctrlKey)) {
            event.preventDefault(); // Impedir o comportamento padrão (recarregar a página)
            console.log('O recarregamento da página está bloqueado!');
        }
    });
</script>
