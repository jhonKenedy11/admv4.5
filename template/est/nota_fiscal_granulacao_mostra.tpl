<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_granulacao.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Nota Fiscal - Transfer&ecirc;ncia Granulação de Produtos
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Cadastro</span>
                                </button>
                            </li>
                            {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">


                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=pesquisa type=hidden value="">
                            <input name=produtos type=hidden value="">
                            <input name=submenu type=hidden value={$subMenu}>

                            <div class="row">
                                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                    <label>N&uacute;mero NF</label>
                                    <input class="form-control" id="numNf" name="numNf"
                                        placeholder="N&uacute;mero da nota fiscal a pesquisar." value={$numNf}>
                                </div>
                            </div>
                            {if $letra neq ''}
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <small>Origem</small>
                                                <ul class="nav navbar-right panel_toolbox">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                                    </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <div class="row">
                                                    <div class="col-lg-4 text-left">
                                                        <label for="fator">Fator</label>
                                                        <div class="panel panel-default">
                                                            <input class="form-control" type="number" required id="fator"
                                                                name="fator" placeholder="Digite o fator." pattern="[0-9]"
                                                                value={$fator}>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 text-left">
                                                        <label for="origemNatOp">Natureza Operação</label>
                                                        <div class="panel panel-default">
                                                            <input class="form-control" type="text" required
                                                                id="origemNatOp" name="origemNatOp"
                                                                placeholder="Digite a localização." value={$origemNatOp}>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 text-left">
                                                        <label for="origemCfop">CFOP</label>
                                                        <div class="panel panel-default">
                                                            <input class="form-control" type="text" required id="origemCfop"
                                                                name="origemCfop" placeholder="Digite a localização."
                                                                value={$origemCfop}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_title">
                                                <small>Destino</small>
                                                <ul class="nav navbar-right panel_toolbox">
                                                    <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                                    </li>
                                                </ul>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <div class="row">
                                                    <div class="col-lg-6 text-left">
                                                        <label for="destinoNatOp">Natureza Operação</label>
                                                        <div class="panel panel-default">
                                                            <input class="form-control" type="text" required
                                                                id="destinoNatOp" name="destinoNatOp"
                                                                placeholder="Digite nat. operação destino."
                                                                value={$destinoNatOp}>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 text-left">
                                                        <label for="destinoCfop">CFOP</label>
                                                        <div class="panel panel-default">
                                                            <input class="form-control" type="text" required
                                                                id="destinoCfop" name="destinoCfop"
                                                                placeholder="Digite o CFOP da NF destino."
                                                                value={$destinoCfop}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        </form>





                        {if $letra neq ''}


                            <div class="col-md-12 col-xs-12">
                                <div class="x_panel small">
                                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                                        <thead>
                                            <tr style="background: #2A3F54; color: white;">
                                                <th style="width: 30px;">Selecionar</th>
                                                <th>Código</th>
                                                <th>Descrição</th>
                                                <th>NCM</th>
                                                <th>Unidade</th>
                                                <th>Qtde</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            {section name=i loop=$lanc}
                                                {assign var="total" value=$total+1}
                                                <tr>
                                                    <td align="center"> <input type="checkbox" name="prodCheckbox"
                                                            id="{$lanc[i].ID}" value="{$lanc[i].ID}"> </td>
                                                    <td> {$lanc[i].CODPRODUTO} | {$lanc[i].CODFABRICANTE} </td>
                                                    <td> {$lanc[i].DESCRICAO} </td>
                                                    <td> {$lanc[i].NCM} </td>
                                                    <td> {$lanc[i].UNIDADE} </td>
                                                    <td> {$lanc[i].QUANT} </td>

                                                </tr>
                                                <p>
                                                    {sectionelse}
                                                    <td>n&atilde;o h&aacute; Contas Cadastradas</td>
                                                {/section}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        {/if}
                    </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->


{include file="template/database.inc"}