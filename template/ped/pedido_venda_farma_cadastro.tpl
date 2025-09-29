<style>
    .form-control, select, .x_panel {
        border-radius: 5px !important;
    }
    .btnCliente{
        margin-top: 8px;
        height: 30px !important;
    }
    .switchery-default{
        margin-top: 6px !important;
    }

    .btnRelatorios{
        margin-top: 6px;
        width: 100% !important;
    }
    .dropMenuRel{
        right: -20% !important;
        border-radius: 5px;
        background-color: rgba(76, 75, 75, 0.882);
    }
    #infoError {
        display: grid;
        align-items: center;
        text-align: center;
        vertical-align: middle;
        height: 35px;
        color: rgb(43, 42, 42);
        border-radius: 10px;
        width:40rem;
        background-color: yellow;
        animation: zoom 1s ease-in-out infinite;
        transform-origin: center;
    }

    @keyframes zoom {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.03);
        }
    }
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_farma.js"> </script>
<!-- page content -->
<div class="right_col" style="padding:5px !important;" role="main">
    <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="ped">
            <input name=form type=hidden value={$form}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=id type=hidden value={$id}>
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
            <input name=pesLocalizacao type=hidden value=''>
            <input name=exibirmotivo type=hidden value={$exibirmotivo}>
            <input name=itensperdido type=hidden value={$itensperdido}>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                                            {if $mensagem neq ''}
                            <div class="row">
                                <div class="col-lg-12 text-left">
                                    <div>
                                        <div class="alert alert-danger small" role="alert">{$mensagem}</div>
                                    </div>
                                </div>
                            </div>

                        {/if}

                    <div class="x_panel">
                        <div class="row" style="margin-bottom: 16px;">
                            <div class="col-md-9">
                                <label for="nome">
                                    <h2>Pedido -
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Altera&ccedil;&atilde;o
                                        {/if}
                                    </h2>
                                </label>
                            </div>

                            {* <div class="col-md-9 offset-md-9"></div> *}

                            {* <div class="col-md-3 pull-right">
                                {if ($situacao == 0) or ($situacao == "")}
                                    <label for="natOp">

                                        <button type="button" class="btn btn-primary" onClick="javascript:submitDigitacao('');">
                                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"> </span> Voltar
                                        </button>

                                    </label>
                                    <label for="natOp">

                                        <button type="button" class="btn btn-success"
                                            onClick="javascript:submitConfirmarSmart('');">
                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Concluir
                                        </button>

                                    </label>
                                {/if}
                            </div> *}

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-danger" id="btnVoltar"  onClick="javascript:submitDigitacao();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span>
                                    </button>
                                </li>
                    
                                <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmarSmart();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Concluir</span>
                                    </button>
                                </li>
                                <li class="dropdown" style="margin-left:15px;">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        {if $subMenu neq "incluir"}
                                            <li>
                                                <button type="button" class="btn btn-primary btn-xs btnRelatorios"  onClick="javascript:submitCadastroPedidoMass({$id});"><span>Clonar pedido para clientes atividade (PM)</span></button>
                                            </li>
                                        {/if}  
                                    </ul> 
                                </li>
                            </ul>

                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label>Cliente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control btnCliente" id="nome" name="nome" placeholder="Conta" required value="{$nome}" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btnCliente" onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="natOp">
                                    <label>Natureza Operação&emsp;</label>
                                </label>
                                <SELECT class="form-control" name="natop">
                                    {html_options values=$natop_ids output=$natop_names selected=$natop_id}
                                </SELECT>
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="natOp">
                                    <label> Cond Pagamento</label>
                                </label>
                                <select name="condPgto" class="form-control">
                                    {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="centroCusto">
                                    <label>Centro de Custo</label>
                                </label>
                                <select name="centroCusto" class="form-control">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                                <label for="promocoes">Promoções</label>
                                    <input type="checkbox" class="js-switch" id="promocoes" name="promocoes"
                                    {if $promocoes eq 'S'} checked {/if} value="{$promocoes}" />
                            </div>

                            <div class="col-lg-3 col-sm-12 col-xs-12 text-left">
                                <label>Produto</label>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Digite o nome do produto para pesquisar."
                                        id="pesProduto" name="pesProduto" value="{$pesProduto}"
                                        onChange="javascript:submitBuscar('');">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-xs-6 text-left">
                                <label>Localização</label>
                                <div class="form-group">
                                    <input class="form-control"
                                        placeholder="Digite a localização do produto para pesquisar."
                                        id="pesLocalizacao" name="pesLocalizacao" value="{$pesLocalizacao}"
                                        onChange="javascript:submitBuscar('');">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-5 col-xs-5 text-left">
                                <label>Grupo</label>
                                <SELECT class="form-control" name="grupo">
                                    {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                </SELECT>
                            </div>

                            <div class="col-lg-2 col-sm-2 col-xs-2 text-left">
                                <label for="desconto">R$ Desconto</label>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Valor de Desconto." id="itensQtde"
                                        name="desconto" value={$desconto|number_format:2:",":"."}>
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-warning btn-sm"
                                    onClick="javascript:submitBuscar('');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>

                            </div>
                        </div>
                    </div><!-- x_content -->
                </div><!-- x_panel -->

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Produtos <small>Selecione produtos para o pedido</small></h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table id="datatable-buttons"
                                class="table table-striped table-condensed table-responsive small">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Estoque</th>
                                        <th>Valor Unit&aacute;rio</th>
                                        <th>Quant. Venda</th>
                                        <th>Valor Promo&ccedil;&atilde;o</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>


                                    {section name=i loop=$lancPesq}
                                        {assign var="total" value=$total+1}
                                        <tr>
                                            <td> {$lancPesq[i].CODFABRICANTE} </td>
                                            <td> {$lancPesq[i].DESCRICAO} </td>
                                            <td class="price-value"> {$lancPesq[i].QUANTIDADE|number_format:2:",":"."} </td>
                                            <td class="price-value"> {$lancPesq[i].VENDA|number_format:2:",":"."} </td>
                                            <td>
                                                <input name="{$lancPesq[i].CODIGO}" type=hidden value={$lancPesq[i].CODIGO}>
                                                <input class="form-control input-sm" title="Digite a qtde para este item."
                                                    id="quant" name=quant{$lancPesq[i].CODIGO}>
                                            </td>
                                            <td align=right>
                                                <input class="form-control input-sm" title="Digite a qtde para este item."
                                                    id="quant" name=promocao{$lancPesq[i].CODIGO}
                                                    value={$lancPesq[i].PROMOCAO|number_format:2:",":"."}>
                                            </td>
                                            {if ($situacao == 0) or ($situacao == "")}
                                                <td>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-link"
                                                            onClick="javascript:submitIncluirItemQuantPreco();">
                                                            <span class="glyphicon glyphicon-shopping-cart"
                                                                aria-top="true"></span>
                                                        </button>
                                                    </span>
                                                </td>
                                            {/if}
                                        </tr>
                                        <p>
                                        {/section}

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Compras <small>Produtos carrinho</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <h2>TOTAL: {$totalPedido|number_format:2:",":"."}</h2>
                                </li>
                            </ul>
                            <!--ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up fa-2x"></i></a>
                      </li>
                    </ul-->
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table id="datatable-buttons2"
                                class="table table-striped table-condensed table-responsive small">
                                <thead id="theadMotivo">
                                    {if ($exibirmotivo == 'S')}
                                        <tr>
                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                <SELECT ID="motivoselecionado" class="form-control btn-sm"
                                                    name="motivoselecionado">
                                                    {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                                                </SELECT>
                                            </div>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                onClick="javascript:submitPedidoPerdidoSalvar('');">
                                                <span class="glyphicon glyphicon-save"
                                                    aria-hidden="true"></span><span>Salvar</span>
                                            </button>
                                        </tr>
                                    {/if}

                                    <tr>
                                        {if ($exibirmotivo == 'S')}
                                            <th><input type="checkBox" id="checkboxmotivo" name="checkboxmotivo"
                                                    onClick="javascript:submitSelecionarTodos(this.checked);" /></th>
                                        {/if}
                                        <th>Código</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Qtde</th>
                                        <th>Valor Unit&aacute;rio</th>
                                        <th>Valor Desconto</th>
                                        <th>Valor Total</th>
                                        {if ($exibirmotivo == 'S')}
                                            <th><button type="button" class="btn btn-warning btn-xs"
                                                    onClick="javascript:submitExibirMotivo('');"><span
                                                        class="glyphicon glyphicon-remove"
                                                        aria-hidden="true"></span></button></th>
                                        {/if}
                                    </tr>
                                </thead>
                                <tbody id="bodyMotivo">
                                    {section name=i loop=$lancItens}
                                        {assign var="total" value=$total+1}
                                        <tr>{if ($exibirmotivo == 'S')}
                                                <td>
                                                    <input type="checkBox" name="checkedPerdido" id="{$lancItens[i].NRITEM}" />
                                                </td>
                                            {/if}
                                            <td>{$lancItens[i].ITEMFABRICANTE} </td>
                                            <td> {$lancItens[i].DESCRICAO} </td>
                                            <td align=right> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                            <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                            <td align=right> {$lancItens[i].DESCONTO|number_format:2:",":"."} </td>
                                            <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                            {if ($situacao == 0) or ($situacao == "")}
                                                <td> <button type="button" class="btn btn-danger btn-xs"
                                                        onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span
                                                            class="glyphicon glyphicon-remove"
                                                            aria-hidden="true"></span></button> </td>
                                            {/if}
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
</div>
</form>

</div>

{include file="template/form.inc"}