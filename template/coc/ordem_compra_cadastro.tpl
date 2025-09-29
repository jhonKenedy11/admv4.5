<style>
    .line-formated {
        margin-bottom: 1px;
    }

    .rowFDS {
        width: 40px;
        height: 30px;
        font-size: 12px;
    }

    .btnCp {
        position: absolute;
        width: 17px !important;
        height: 17px !important;
        border-radius: 10px !important;
        margin-left: 5px;
        margin-top: 1.2px;
        display: inline-block;
        background: #26B99A;
        border: 1px solid #169F85;

    }

    .btnCp:hover {
        background: #169F85;
    }

    #spanBTN {
        position: static;
        margin-left: -9.4px !important;
        margin-top: 1px !important;
        width: 1px !important;
        height: 1px !important;
        color: white;
    }

    #codProdutoNota {
        padding-right: 0;
    }

    .form-control:focus {
        border-color: #159ce4;
        transition: all 0.7s ease;
    }

    .form-control {
        border-radius: 5px !important;
    }

    .swal-text {
        font-weight: bold;
        font-size: 19px;
    }

    .swal-button--btn_cadastrar_novo {
        background-color: #8a74f9 !important;
        transition: background-color 0.3s ease;
    }

    .swal-button--btn_cadastrar_novo:hover {
        background-color: #454886 !important;
    }
</style>

<script type="text/javascript" src="{$pathJs}/coc/s_ordem_compra.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="coc">
            <input name=form type=hidden value="ordem_compra">
            <input name=submenu type=hidden value={$subMenu}>
            <div id="divId">
                <input name=id type=hidden value={$id}>
            </div>
            <div id="modNf">
                <input name=msgNf type=hidden value={$msgNf}>
            </div>
            <input name=nrItem type=hidden value={$nrItem}>
            <input name=totalPedido type=hidden value={$totalPedido}>
            <input name=letra type=hidden value={$letra}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=pesq type=hidden value={$pesq}>
            <input name=itensPedido type=hidden value={$itensPedido}>
            <input name=fornecedor type=hidden value="">
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=situacao type=hidden value={$situacao}>
            <input name=itensQtde type=hidden value='0'>
            <input name=letra_item type=hidden value={$letra_item}>
            <input name=opcao_item type=hidden value={$opcao_item}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Ordem De Compra {$id} -
                                {if $subMenu eq "cadastrar"}
                                    Cadastro
                                {else}
                                    Altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-success" role="alert">{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert">{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button {if $ocBaixado eq true} disabled {/if} type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <button {if $id eq ''} disabled {/if} type="button"
                                                class="btn btn-primary btn-xs"
                                                onClick="javascript:duplicaOrdemCompra('{$id}');"><span>Duplicar
                                                    Ordem de Compra</span></button>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>

                        </div>
                        <div class="x_content">

                            <div class="form-group line-formated">
                                <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                    <label for="conta">Fornecedor</label>
                                    <div class="input-group line-formated">
                                        <input type="text" class="form-control input-sm" id="nome" name="nome"
                                            placeholder="Conta" required value="{$nome}" readonly>
                                        <span class="input-group-btn">
                                            <button {if $ocBaixado eq true} disabled {/if} type="button"
                                                class="btn btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="contato">Cond Pagamento</label>
                                    <select {if $ocBaixado eq true} disabled {/if} name="condPgto"
                                        class="form-control js-example-basic-single" id="condPgto">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                </div>

                                <div class="col-lg-3 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Situação</label>
                                    <div class="panel panel-default small line-formated">
                                        <select {if $ocBaixado eq true} disabled {/if} name="situacaoCombo"
                                            class="form-control input-sm" id="situacaoCombo" readonly>
                                            {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group line-formated">
                            <div class="col-lg-2 col-sm-6 col-xs-6 small text-left">
                                <label>Num NF</label>
                                <div class="form-group">
                                    <input {if $ocBaixado eq true} readonly {/if} class="form-control input-sm"
                                        placeholder="Numero Nf." id="numNf" name="numNf" maxlength="11"
                                        onChange="javascript:submitVerificarNf('');" value="{$numNf}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-xs-6 small text-left">
                                <label>Serie</label>
                                <div class="form-group">
                                    <input {if $ocBaixado eq true} readonly {/if} class="form-control input-sm"
                                        placeholder="Serie Nf." id="serie" name="serie" value="{$serie}" maxlength="3"
                                        onChange="javascript:submitVerificarNf('');">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-xs-6 small text-left">
                                <label>Data Emissao NF</label>
                                <div class="form-group">
                                    <input {if $ocBaixado eq true} readonly {/if} class="form-control input-sm"
                                        placeholder="Data Emissao." id="dataEmissao" name="dataEmissao"
                                        value="{$dataEmissao}" data-inputmask="'mask': '99/99/9999'"
                                        onBlur="javascript:submitVerificarNf('');">
                                </div>
                            </div>
                            <div id="divTotal">
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="codProduto">Desconto</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default rowFDS" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" type="text" readonly id="descontoOc"
                                            name="descontoOc" placeholder="Desconto Oc" value={$descontoOc}>
                                    </div>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="codProduto"> T O T A L </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default rowFDS" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm money" readonly type="money" id="totalOc"
                                            name="totalOc" placeholder="Total Oc" value={$totalOc}>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-6 col-xs-6 small">
                            <label for="frete">Frete</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default rowFDS" type="button">R$</button>
                                </span>
                                <input class="form-control input-sm money" type="money" maxlength="10" name="frete"
                                    onblur="javascript:atualizaTotais()" value={$frete}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 small">
                            <label for="despacessorias">Desp Acessórias</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default rowFDS" type="button">R$</button>
                                </span>
                                <input class="form-control input-sm money" type="money" maxlength="10"
                                    name="despacessorias" onblur="javascript:atualizaTotais()" value={$despacessorias}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 small">
                            <label for="seguro">Seguro</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default rowFDS" type="button">R$</button>
                                </span>
                                <input class="form-control input-sm money" type="money" maxlength="10" name="seguro"
                                    onblur="javascript:atualizaTotais()" value={$seguro}>
                            </div>
                        </div>


                        <div class="form-group line-formated">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="obs">Observa&ccedil;&atilde;o</label>
                                <textarea {if $ocBaixado eq true} readonly {/if}
                                    class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                    rows="2">{$obs}</textarea>
                            </div>
                        </div>
                        <div class="form-group line-formated" id="div_busca_prod">
                            <input name=prodExiste id="prodExiste" type=hidden value="{$prodExiste}">
                            <div class="form-group line-formated">
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="codProduto">Cod. Interno</label>
                                    <button type="button" class="btnCp" title="Cadastro de Produto"
                                        onClick="javascript:cadastraProduto('{$id}');">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true" id="spanBTN"></span>
                                    </button>
                                    <input class="form-control input-sm" type="text" readonly id="codProduto"
                                        name="codProduto" placeholder="Código Interno" value={$codProduto}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="codProduto">Cod. Fabricante</label>
                                    <input class="form-control input-sm" type="text" id="codFabricante"
                                        name="codFabricante" placeholder="Código Fabricante"
                                        onblur="javascript:buscaProduto();" value={$codFabricante}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="codProdutoNota">Código Nota</label>
                                    <input class="form-control input-sm" type="text" id="codProdutoNota"
                                        name="codProdutoNota" placeholder="Código Nota." value={$codProdutoNota}>
                                </div>
                                <div class="col-md-5 small col-sm-12 col-xs-12 line-formated">
                                    <label for="Produto">Produto</label>
                                    <div class="input-group line-formated">
                                        <input type="text" class="form-control input-sm" readonly id="descProduto"
                                            name="descProduto" placeholder="Produto" required value="{$descProduto}">
                                        <span class="input-group-btn line-formated">
                                            <button {if $ocBaixado eq true} disabled {/if} type="button"
                                                class="btn btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=ordem_compra', 'produto');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-1 small col-sm-12 col-xs-12">
                                    <label for="uniProduto">Unidade</label>
                                    <input class="form-control input-sm" type="text" id="uniProduto" maxlength="3"
                                        name="uniProduto" placeholder="Unidade" alt="Unidade" value={$uniProduto}>
                                </div>
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="quant">Quantidade</label>
                                    <input class="form-control input-sm money" type="text" id="quant" name="quant"
                                        placeholder="Quantidade" alt="Quantidade"
                                        onchange="javascript:calculaTotalItens('')" value={$quant}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="vlrUnitarioPecas">Valor Unitário</label>
                                    <input class="form-control input-sm money" type="text" id="unitario" name="unitario"
                                        placeholder="Valor Unitário" alt="Valor Unitário"
                                        onchange="javascript:calculaTotalItens('')" value={$unitario}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="percDescontoPecas">% Desconto</label>
                                    <input class="form-control input-sm money" type="text" id="percDesconto"
                                        name="percDesconto" placeholder="% de Desconto"
                                        onchange="javascript:calculaTotalItens('')" value={$percDesconto}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="vlrDescontoPecas">Valor Desconto</label>
                                    <input class="form-control input-sm money" type="text" id="vlrDesconto"
                                        name="vlrDesconto" placeholder="Valor de Desconto"
                                        onchange="javascript:calculaTotalItens('desconto')" value={$vlrDesconto}>
                                </div>
                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                    <label for="totalPecas">T O T A L</label>
                                    <input class="form-control input-sm" readonly type="text" id="totalItem"
                                        tabindex="-1" name="totalItem" placeholder="0,00" value={$totalItem}>
                                </div>
                                <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                    <label style="visibility:hidden">btn</label>
                                    <button {if $ocBaixado eq true} disabled {/if} type="button"
                                        class="btn btn-success btn-sm" onClick="javascript:submitConfirmarPecas();">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- x_content -->
            </div><!-- x_panel -->

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel small">
                    <table id="datatable-buttons-item" class="table table-bordered jambo_table">
                        <thead>
                            <tr style="background: gray; color: white;">
                                <th>Cód. Interno</th>
                                <th>Cód. Fabricante</th>
                                <th>Cód. Nota</th>
                                <th>Descrição</th>
                                <th>Unidade</th>
                                <th>Loc.</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>% Desconto</th>
                                <th>Valor Desconto</th>
                                <th>TOTAL</th>
                                <th style="width:120px;">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$lancItens}
                                <tr>
                                    <td> {$lancItens[i].ITEMESTOQUE} </td>
                                    <td> {$lancItens[i].ITEMFABRICANTE} </td>
                                    <td> {$lancItens[i].CODIGONOTA} </td>
                                    <td> {$lancItens[i].DESCRICAO} </td>
                                    <td> {$lancItens[i].UNIDADE} </td>
                                    <td> {$lancItens[i].LOCALIZACAO} </td>
                                    <td> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                    <td> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td> {$lancItens[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                    <td> {$lancItens[i].DESCONTO|number_format:2:",":"."} </td>
                                    <td> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                    <td>
                                        <button {if $lancItens[i].ITEMESTOQUE eq 0} disabled {/if}type="button"
                                            class="btn btn-info btn-xs"
                                            onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lancItens[i].ITEMFABRICANTE}||||{$lancItens[i].ITEMESTOQUE}', 'produto');"><span
                                                class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        <button {if $ocBaixado eq true} disabled {/if} type="button"
                                            class="btn btn-primary btn-xs"
                                            onclick="javascript:editarItem(this, '{$lancItens[i].NRITEM}')"><span
                                                class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button {if $ocBaixado eq true} disabled {/if} type="button"
                                            class="btn btn-danger btn-xs"
                                            onclick="javascript:submitExcluiItem('{$lancItens[i].NRITEM}');"><span
                                                class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                                <p>
                                {/section}
                        </tbody>
                    </table>
                </div>
            </div>




    </div>
</div>
</div>
</div>
</form>

</div>

{include file="template/form.inc"}

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script>
    $(document).ready(function() {
        $("#condPgto.js-example-basic-single").select2({
            theme: "classic"
        });
    });
</script>

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowNegative: true,
            allowZero: true
        });
    });
</script>


<script>
    $(document).ready(function() {
        $("#situacaoCombo.select_multiple").select2({
            placeholder: "Escolha a Situação",
            allowClear: true,
            width: "95%",

        });

    });
</script>

<script>
    $(function() {
        $('#dataEmissao').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_1",
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                    'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
            }

        });

    });
</script>