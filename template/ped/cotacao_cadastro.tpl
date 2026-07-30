<style>
    .x_panel {
        margin-top: -8px !important;
    }

    .line-formated {
        margin-bottom: 1px;
    }

    .btnCp {
        position: absolute;
        width: 17px !important;
        height: 17px !important;
        border-radius: 10px !important;
        margin-left: 5px;
        margin-top: -2px;
        display: inline-block;
        background: #26B99A;
        border: 1px solid #169F85;
    }

    .btnCp:hover {
        background: #169F85;
    }

    #spanBTN {
        position: static;
        margin-top: 2px !important;
        margin-left: -3px !important;
        width: 10px !important;
        height: 10px !important;
        color: white;
    }

    .form-control,
    .x_panel {
        border-radius: 5px !important;
    }

    .not-active {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
    }

    .swal-modal {
        width: 600px !important;
    }

    .col-md-1-5 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    @media (min-width: 992px) {
        .col-md-1-5 {
            width: 12.5%; /* Entre col-md-1 (8.33%) e col-md-2 (16.66%) */
            float: left;
        }
    }

    /* Remove padding dos inputs de todas as linhas de produtos */
    #formCotacaoItem .form-group.line-formated > div {
        padding-right: 3px !important;
        padding-left: 3px !important;
    }
    
    #formCotacaoItem .form-group.line-formated > div:first-child {
        padding-left: 15px !important;
    }
    
    #formCotacaoItem .form-group.line-formated > div:last-child {
        padding-right: 15px !important;
    }

    .title-cadastro {
        padding-left: 0;
        margin-top: 11px;
        width: 100px !important;
    }

    .title-cotacao {
        padding-right: 0;
        width: 150px;
    }

    .fa-wrench {
        font-size: 18px;
    }

    .btnRelatorios {
        margin-top: 4px;
        width: 100% !important;
    }

    .dropMenuRel {
        right: -84% !important;
        border-radius: 5px;
        background-color: rgba(76, 75, 75, 0.882);
    }

    .swal-button--btn_cadastrar_novo {
        background-color: #8a74f9 !important;
        transition: background-color 0.3s ease;
    }

    .swal-button--btn_cadastrar_novo:hover {
        background-color: #454886 !important;
    }

    .daterangepicker {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .daterangepicker .applyBtn {
        background-color: #007bff;
        color: white;
    }

    .daterangepicker td.active {
        background-color: #28a745 !important;
    }
    
    /* Spinner de carregamento */
    #spinnerPesquisa {
        background-color: transparent;
        border: none;
        padding: 0 8px;
    }
    
    .spinner-rotate {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .ajuda-flutuante-icon {
        cursor: help;
        margin-left: 4px;
        font-size: 14px;
        vertical-align: middle;
    }

    .ajuda-flutuante-tip {
        display: none;
        position: fixed;
        z-index: 10050;
        max-width: 360px;
        padding: 8px 10px;
        font-size: 12px;
        line-height: 1.45;
        font-weight: normal;
        color: #fff;
        background: rgba(51, 51, 51, 0.95);
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        pointer-events: none;
    }
</style>

<script type="text/javascript" src="{$pathJs}/ped/s_cotacao.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 5px 2px 2px 2px;">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="ped">
            <input name=form type=hidden value="cotacao">
            <input name=submenu type=hidden value={$subMenu}>
            <div id="idCotacao">
                <input name=id type=hidden value={$id}>
            </div>
            <div id="divPesquisaProduto">
                <input name=prodExiste id="prodExiste" type=hidden value="{$prodExiste|default:'no'}">
            </div>
            <div id="numCotacao">
                <input name=numCotacao type=hidden value={$numCotacao}>
            </div>
            <input name=percentualAplicar type=hidden id="percentualAplicarHidden" value="">
            <input name=itensPedidoCC type=hidden id="itensPedidoCC" value="">
            <input name=pesq_cc type=hidden id="pesq_cc" value="">
            <input name=desc_cc type=hidden id="desc_cc" value="">
            <input name=letra type=hidden value={$letra}>
            <input name=letra_peca type=hidden value={$letra_peca}>
            <input name=pesq type=hidden value={$pesq}>
            <input name="pessoa" type="hidden" id="pessoa" value="{$pessoa}">
            <input name="emissao" type="hidden" id="emissao" value="{$emissao}">
            <input name=nrItem type=hidden value={$nrItem}>
            <input name=opcao_item type=hidden value={$opcao_item}>
            <input name=centroCusto type=hidden value={$centroCusto}>
            <input id="markupCusto" name="markupCusto" type="hidden" value="{$markupCusto}">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="">
                                <div class="col-md-2 title-cotacao">
                                    <h3 class="title-cadastro_">Cotação &nbsp;-</h3>
                                </div>
                                <div class="col-md-10 title-cadastro">
                                    {if $subMenu eq "cadastrar"}
                                        <h2>Cadastro</h2>
                                    {else}
                                        <h2><i>Altera&ccedil;&atilde;o</i></h2>
                                    {/if}
                                </div>
                            </div>
                            {include file="../bib/msg.tpl"}
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-dark" onclick="javascript:setaDadosCotacao()" style="margin-right: 5px;">
                                        <span aria-hidden="true" data-toggle="tooltip" title="Copiar e Colar">C.C.</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnDuplicarCotacao" type="button"
                                                class="btn btn-primary btn-xs btnRelatorios"
                                                onClick="javascript:submitDuplicarCotacao({$id});">
                                                <span>Duplicar Cotação</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnGerarPedido" type="button"
                                                class="btn btn-success btn-xs btnRelatorios"
                                                onClick="javascript:submitGerarPedido({$id});">
                                                <span> Gerar Pedido</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnImprimir" type="button"
                                                class="btn btn-info btn-xs btnRelatorios"
                                                onClick="javascript:submitImprimir({$id});">
                                                <span> Imprimir</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnAplicarPercentual" type="button"
                                                class="btn btn-warning btn-xs btnRelatorios"
                                                onClick="javascript:abrirModalAplicarPercentual({$id});">
                                                <span> Aplicar Percentual</span>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                            <div class="form-group line-formated">
                                <div class="col-md-8 col-sm-12 col-xs-12 line-formated">
                                    <label for="conta">Cliente</label>
                                    <div class="input-group line-formated">
                                        <input type="text" class="form-control input-sm" id="nome" name="nome"
                                            placeholder="Cliente" required value="{$lanc[0].NOME}" readonly>
                                            <input type="hidden" id="cliente" name="cliente" value="{$lanc[0].CLIENTE}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar&origem=cotacao');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-xs-6 text-left line-formated" id="div_cond_pgto">
                                    <label>Condição de Pagamento</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="condPgto" name="condPgto" class="input-sm form-control"
                                            title="Condição de Pagamento" alt="Condição de Pagamento" required>
                                            {if $lanc[0].CONDPG != ''}
                                                {html_options values=$condPgto_ids selected=$lanc[0].CONDPG output=$condPgto_names}
                                            {else}
                                                {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                            {/if}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="divTotal" class="form-group line-formated">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="valorProdutos">Valor Produtos</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                                type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Produtos." id="valorProdutos"
                                            name="valorProdutos" value="{$valorProduto}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="desconto">Desconto</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                                type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm money" placeholder="Desconto."
                                            id="valorDesconto" name="valorDesconto"
                                            onClick="javascript:guardaValorAntCotacao();"
                                            onchange="javascript:atualizarInfo();" value="{$valorDesconto}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="total">T O T A L</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                                type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm not-active" tabindex="-1"
                                            placeholder="Total Cotação." id="valorTotal" name="valorTotal"
                                            value="{$valorTotal}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="markupCotacao">
                                        Markup (%)
                                        <span class="glyphicon glyphicon-info-sign text-info" 
                                              style="cursor: help; margin-left: 5px; font-size: 14px;"
                                              data-toggle="tooltip" 
                                              data-placement="down" 
                                              data-html="true"
                                              title="<strong>Cálculo do Markup:</strong><br/>
                                                    <strong>Só atualiza itens que tem Custo de Compra</strong><br>
                                                    <em>O markup representa a margem de lucro desejada sobre o custo.</em>">
                                        </span>
                                    </label>
                                    <input class="form-control input-sm money" type="text"
                                        id="markupCotacao" name="markupCotacao" placeholder="0,00%"
                                        onchange="javascript:atualizarmarkup();"
                                        {if $lanc[0].MARKUP != ''}
                                            value="{$lanc[0].MARKUP|number_format:2:",":"."}"
                                        {else}
                                            value="{$markupCotacao}"
                                        {/if}>
                                </div>
                            </div>

                            <hr style="border-top: 2px solid #ddd; margin: 20px 0;">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div id="formCotacaoItem">
                                    <div class="form-group line-formated">
                                        <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="codFabricante">Pesquisa
                                                <span id="tooltipPesquisaProduto" class="glyphicon glyphicon-info-sign text-info ajuda-flutuante-icon"
                                                      title=""></span>
                                            </label>
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" id="codFabricante"
                                                    name="codFabricante" placeholder="insira pelo menos 3 digitos"
                                                    onblur="javascript:buscaProdutoAjax();" 
                                                    onkeypress="javascript:if(event.keyCode == 13) { buscaProdutoAjax(); return false; }"
                                                    value={$codFabricante}>
                                                <span class="input-group-addon" id="spinnerPesquisa" style="display: none;">
                                                    <span class="glyphicon glyphicon-refresh spinner-rotate" aria-hidden="true"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="codProduto">Cod Interno</label>
                                            <button type="button" class="btnCp" title="Cadastro de Produto"
                                                onClick="javascript:cadastraProduto();">
                                                <span class="glyphicon glyphicon-plus" aria-hidden="true"
                                                    id="spanBTN"></span>
                                            </button>
                                            <input class="form-control input-sm" type="text" id="codProduto"
                                                readonly name="codProduto" placeholder="Cod Interno"
                                                value={$codProduto}>
                                        </div>
                                        <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="codProdutoNota">Código Nota</label>
                                            <input class="form-control input-sm" type="text" id="codProdutoNota"
                                                name="codProdutoNota" placeholder="Código Nota."
                                                value={$codProdutoNota}>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 small line-formated">
                                            <label for="Produto">Descrição do Produto</label>
                                            <div class="input-group line-formated">
                                                <input type="text" class="form-control input-sm"
                                                    id="descProduto" name="descProduto" placeholder="Descrição do Produto"
                                                    required value="{$descProduto}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=cotacao&idCotacao={$id}', 'produto');"
                                                        title="Pesquisar produtos">
                                                        <span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 small col-sm-12 col-xs-12">
                                            <label for="dataEntregaPeca">Prazo Entrega</label>
                                            <input class="form-control input-sm" type="text" id="dataEntregaPeca"
                                                name="dataEntregaPeca" placeholder="Prazo Entrega"
                                                alt="Prazo Entrega" value="{$dataEntregaPeca}">
                                        </div>
                                    </div>
                                    <div class="form-group line-formated">
                                        <div class="col-md-1 small col-sm-12 col-xs-12 line-formated">
                                            <label for="uniProduto">Unidade</label>
                                            <input class="form-control input-sm" type="text" id="uniProduto"
                                                maxlength="3" name="uniProduto" placeholder="Un."
                                                alt="Unidade" value={$UNIDADE}>
                                        </div>
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="quantidadeProduto">Quantidade</label>
                                            <input class="form-control input-sm money" type="text"
                                                id="quantidadeProduto" name="quantidadeProduto"
                                                placeholder="Quantidade" alt="Quantidade"
                                                onchange="javascript:calculaTotalItens('', 'produto')"
                                                value={$quantidadeProduto}>
                                        </div>
                                        
                                
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="vlrUnitarioProduto">Valor Unitário</label>
                                            <input class="form-control input-sm money" type="text"
                                                id="vlrUnitarioProduto" name="vlrUnitarioProduto"
                                                placeholder="R$ 0,00" alt="Valor Unitário"
                                                onchange="javascript:calculaTotalItens('', 'produto')"
                                                value={$vlrUnitarioProduto}>
                                        </div>
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="percDescontoProduto">Percentual Desconto</label>
                                            <input class="form-control input-sm money" type="text"
                                                id="percDescontoProduto" name="percDescontoProduto"
                                                placeholder="10,00%"
                                                onchange="javascript:calculaTotalItens('', 'produto')"
                                                value={$percDescontoProduto}>
                                        </div>
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="vlrDescontoProduto">Valor Desconto</label>
                                            <input class="form-control input-sm money" type="text"
                                                id="vlrDescontoProduto" name="vlrDescontoProduto"
                                                placeholder="Valor de Desconto"
                                                onchange="javascript:calculaTotalItens('desconto', 'produto')"
                                                value={$vlrDescontoProduto}>
                                        </div>
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="markupItem">Markup (%)</label>
                                            <input class="form-control input-sm money" type="text"
                                                id="markupItem" name="markupItem" placeholder="0,00%"
                                                onchange="javascript:calculaValorUnitarioPorMarkup(); calculaTotalItens('', 'produto');"
                                                value={$markupItem}>
                                        </div>
                                        <div class="col-md-1-5 small col-sm-12 col-xs-12 has-feedback">
                                            <label for="totalProduto">T O T A L</label>
                                            <input class="form-control input-sm money" readonly type="text"
                                                id="totalProduto" name="totalProduto" placeholder="0,00"
                                                value={$totalProduto}>
                                        </div>
                                        <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                            <label style="visibility:hidden">btn</label>
                                            <button type="button" class="btn btn-success btn-xs"
                                                onClick="javascript:submitConfirmarItem();">
                                                <span class="glyphicon glyphicon-plus"
                                                    aria-hidden="true"></span><span>
                                                    Confirmar</span></button>
                                        </div>
                                        <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                            <label style="visibility:hidden">btn</label>
                                            <button type="button" class="btn btn-warning btn-xs"
                                                onClick="javascript:limpaCamposProduto();">
                                                <span class="glyphicon glyphicon-remove"
                                                    aria-hidden="true"></span><span>
                                                    Cancelar</span></button>
                                        </div>
                                    </div>
                                </div> <!-- FIM DIV formCotacaoItem-->

                                <!-- Seção que aparece quando houver equivalências ou ao editar item -->
                                <div id="secaoEdicaoItem" style="display: none; margin-top: 15px; margin-bottom: 15px;">
                                    <div class="panel panel-info">
                                        <div class="panel-body">
                                            <div id="listaEquivalencias">
                                                <!-- As equivalências serão inseridas aqui via JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seção que aparece com informações do produto selecionado -->
                                <div id="secaoInfoProduto" style="display: none; margin-top: 15px; margin-bottom: 15px;">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Informações do Produto</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div id="infoProduto">
                                                <!-- As informações do produto serão inseridas aqui via JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table id="datatable-buttons-produtos" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Cód Interno</th>
                                            <th>Cód Fabricante</th>
                                            <th>Cód Nota</th>
                                            <th>Descrição</th>
                                            <th>Quantidade</th>
                                            <th>Valor Unitário</th>
                                            <th>% Desconto</th>
                                            <th>Valor Desconto</th>
                                            <th>Prazo Entrega</th>
                                            <th>TOTAL</th>
                                            <th>Markup (%)</th>
                                            <th style="width:120px;">Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancPesq}
                                            <tr>
                                                <td hidden class="i_nr_item"> {$lancPesq[i].NRITEM} </td>
                                                <td hidden class="i_data_entrega"> {$lancPesq[i].DATAENTREGAPECA|date_format:"%d/%m/%Y"} </td>
                                                <td class="i_item_estoque"> {$lancPesq[i].ITEMESTOQUE} </td>
                                                <td class="i_item_fabricante"> {$lancPesq[i].ITEMFABRICANTE} </td>
                                                <td class="i_codigo_nota"> {$lancPesq[i].CODIGONOTA} </td>
                                                <td class="i_decricao"> {$lancPesq[i].DESCRICAO} </td>
                                                <td class="i_qtd_solicitada">
                                                    {$lancPesq[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                <td class="i_unitario">
                                                    {$lancPesq[i].UNITARIO|number_format:2:",":"."} </td>
                                                <td class="i_perc_desconto">
                                                    {$lancPesq[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                <td class="i_desconto">
                                                    {$lancPesq[i].DESCONTO|number_format:2:",":"."} </td>
                                                    <td class="i_data_entrega_td"> {$lancPesq[i].DATAENTREGAPECA|date_format:"%d/%m/%Y"} </td>
                                                    <td class="i_total"> {$lancPesq[i].TOTAL|number_format:2:",":"."} </td>
                                                    <td hidden class="i_custo"> {$lancPesq[i].CUSTOCOMPRA|number_format:2:",":"."|default:'0,00'} </td>
                                                    <td hidden class="i_unidade"> {$lancPesq[i].UNIDADE} </td>
                                                    <td class="i_markup"> {$lancPesq[i].MARKUP|number_format:2:",":"."} </td>
                                                </td>
                                                <td>
                                                    <button {if $lancPesq[i].ITEMESTOQUE eq 0} disabled
                                                        {/if}type="button" class="btn btn-info btn-xs"
                                                        onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lancPesq[i].ITEMFABRICANTE}||||{$lancPesq[i].ITEMESTOQUE}', 'produto');"><span
                                                            class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-primary btn-xs"
                                                        onclick="javascript:editarProduto(this, '{$lancPesq[i].NRITEM}')"><span
                                                            class="glyphicon glyphicon-pencil"
                                                            aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="javascript:submitExcluiItem('{$lancPesq[i].NRITEM}');"><span
                                                            class="glyphicon glyphicon-remove"
                                                            aria-hidden="true"></span></button>
                                                </td>
                                            </tr>
                                        {/section}
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- panel -->
                    </div> <!-- FIM class="x_panel" -->
                </div> <!-- FIM class="col-md-12 col-sm-12 col-xs-12" -->
            </div>
    </form>

    <div id="pesquisaProdutoHelpTip" class="ajuda-flutuante-tip">
        <strong>Pesquisa de produtos</strong><br>
        Mínimo de 3 caracteres; use Enter ou saia do campo para buscar. Exige cliente e condição de pagamento.<br><br>
        <strong>Busca em:</strong> código fabricante, código interno, código de barras (4+ dígitos), descrição e equivalências.<br><br>
        <strong>Ordem dos resultados:</strong><br>
        1. Código fabricante ou interno <em>igual</em> ao digitado (ex.: 161 antes de 16103)<br>
        2. Códigos que <em>começam</em> com o termo<br>
        3. Demais (descrição, equivalência, etc.)<br><br>
        Clique na <strong>linha</strong> ou no <strong>checkbox</strong> para selecionar; o estoque aparece após a seleção.
    </div>

</div> <!-- FIM class="right_col" role="main" -->

{include file="template/form.inc"}

<!-- Modal para Aplicar Percentual -->
<div class="modal fade" id="modalAplicarPercentual" tabindex="-1" role="dialog" aria-labelledby="modalLabelPercentual"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelPercentual">Aplicar Percentual nos Itens</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label for="percentualAplicar">Percentual a Aplicar (%)</label>
                        <input type="text" class="form-control" id="percentualAplicar" 
                               name="percentualAplicar" placeholder="Ex: 10" 
                               required pattern="[0-9]+([,\.][0-9]+)?">
                        <small class="help-block">Informe o percentual (ex: 10 para aumentar 10%, -5 para reduzir 5%)</small>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-info">
                            <strong>Exemplo:</strong> Se o valor atual é R$ 100,00 com 4 itens de R$ 25,00 cada,<br>
                            aplicando 10% ficará R$ 110,00 com 4 itens de R$ 27,50 cada.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="javascript:aplicarPercentualItens();">
                    Aplicar Percentual
                </button>
            </div>
        </div>
    </div>
</div>

{include file="cotacao_cadastro_cc.tpl"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
   $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true,
            precision: {$casasDecimais}     
        });
   
   // Inicializa tooltips do Bootstrap
   $(document).ready(function() {
       $('[data-toggle="tooltip"]').tooltip();

       function vincularAjudaFlutuante(iconId, tipId) {
           var icon = document.getElementById(iconId);
           var tip = document.getElementById(tipId);
           if (!icon || !tip) {
               return;
           }
           function posicionar(e) {
               tip.style.left = (e.clientX + 14) + 'px';
               tip.style.top = (e.clientY + 14) + 'px';
           }
           icon.addEventListener('mouseenter', function (e) {
               tip.style.display = 'block';
               posicionar(e);
           });
           icon.addEventListener('mousemove', posicionar);
           icon.addEventListener('mouseleave', function () {
               tip.style.display = 'none';
           });
       }
       vincularAjudaFlutuante('tooltipPesquisaProduto', 'pesquisaProdutoHelpTip');
   });
</script>

