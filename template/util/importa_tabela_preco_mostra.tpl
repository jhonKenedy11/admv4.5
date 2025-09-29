<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_util.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-10 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Importa&ccedil;&otilde;es - Fornecedor
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>
                            {/if}
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-primary"
                                    onClick="javascript:submitConfirmarTabelaPreco();">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Importar</span></button></li>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <form id="lancamento" class="form-horizontal form-label-left" NAME="lancamento"
                            ACTION="{$SCRIPT_NAME}" METHOD="post" enctype="multipart/form-data">
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=id type=hidden value="">
                            <input name=letra type=hidden value="{$letra}">
                            <input name=submenu type=hidden value="{$subMenu}">
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=fornecedor type=hidden value={$fornecedor}>


                            <div class="form-group">
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <label>Arquivo </label>
                                    <select class="form-control" name=arqImporta id="arqImporta">
                                        {html_options values=$arqImporta_ids selected=$arqImporta_id output=$arqImporta_names}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-group col-md-5 col-sm-5 col-xs-5">
                                        <label class="">Fornecedor</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" readonly id="nome" name="nome"
                                                placeholder="Conta" value="{$nome}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                    <label>Margem Preço Venda(%)</label>
                                    <input class="form-control money" id="precoVenda" name="precoVenda"
                                        placeholder="Margem Preço Venda (%)" value="{$precoVenda}">
                                </div>

                                <div class="col-md-8 col-sm-12 col-xs-12">
                                    <label for="nome">Selecione a Planilha</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file"><input type="file" name="arq" /></span>
                                    </div>
                                </div>
                            </div>


                    </div>

                </div> <!-- x_panel -->
            </div> <!-- div class="tamanho -->
            <div class="col-md-2 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <h5>Sequência campos para importação</h5>
                    <div class="form-group">
                        <label>Código</label>
                        <input class="form-control" id="codTabela" name="codTabela" value={$codTabela}>
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>
                        <input class="form-control" id="descTabela" name="descTabela" value={$descTabela}>
                    </div>

                    <div class="form-group">
                        <label>Preço</label>
                        <input class="form-control" id="precoTabela" name="precoTabela" value={$precoTabela}>
                    </div>
                    <div class="form-group">
                        <label>IPI</label>
                        <input class="form-control" id="ipiTabela" name="ipiTabela" value={$ipiTabela}>
                    </div>
                    <div class="form-group">
                        <label>NCM</label>
                        <input class="form-control" id="ncmTabela" name="ncmTabela" value={$ncmTabela}>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input class="form-control" id="marcaTabela" name="marcaTabela" value={$marcaTabela}>
                    </div>

                </div>

            </div> <!-- FIM x_panel -->
        </div>

    </div> <!-- div row = painel principal-->




    </form>
</div> <!-- div class="x_panel"-->
</div> <!-- div class="x_panel" = tabela principal-->
</div> <!-- div  "-->
</div> <!-- div role=main-->



{include file="template/database.inc"}
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
<script>
    $(document).ready(function() {
        $('.money').maskMoney({
            suffix: ' %',
            allowZero: true, 
            allowNegative: false,
            affixesStay: true,
            precision: 2 
        });
    });
</script>