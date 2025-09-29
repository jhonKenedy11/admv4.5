<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    td {
        font-size: 10px;
    }

    tr {
        font-size: 10px;
    }

    .NoProd {
        color: #022f51;
        text-shadow: 0 1px 0 #ccc,
            0 2px 0 #c9c9c9,
            0 3px 0 #bbb,
            0 4px 0 #b9b9b9,
            0 5px 0 #aaa,
            0 6px 4px rgba(0, 0, 0, .1),
            0 0 5px rgba(0, 0, 0, .1),
            0 1px 3px rgba(0, 0, 0, .3),
            0 3px 5px rgba(0, 0, 0, .2),
            0 5px 10px rgba(0, 0, 0, .25),
            0 10px 10px rgba(0, 0, 0, .2),
            0 20px 20px rgba(0, 0, 0, .15);
    }

    .panel-body {
        padding: 0;
    }

    /* Adicione o CSS para ocultar inicialmente as linhas de código equivalentes */
    #equivalent-codes {
        display: none;
    }

    /* Adicione um efeito de sombra quando o mouse passar por cima */
    .toggle-equivalent:hover {
        transform: translateY(1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    /* Retorne ao estado original quando o mouse sair */
    .toggle-equivalent:active {
        transform: translateY(0);
        box-shadow: none;
    }

    .spanCart {
        color: #022f51 !important;
        font-size: 30px;
        transition: all 0.3s
    }

    .spanCart:hover {
        color: #044f89 !important;
        transform: scale(1.2);
        transition: all 0.1s;

    }

    .quantCart {
        height: 16px;
        width: 16px;
        position: absolute;
        font-size: 11px;
        top: 6px;
        right: 24px;
        border-radius: 50%;
        padding: 0;
    }

    .toggle-equivalent {
        height: 31px;
        margin-bottom: 0 !important;
        margin-right: 0 !important;
    }

    .tdsCart {
        padding: 3px !important;
    }

    .btnExcluirCart {
        height: 25px;
        width: 35px;
        text-align: center;
        line-height: 0px;
        margin: 0;
        cursor: pointer !important;
    }

    .thManutencao {
        width: 1rem;
    }

    #btnCart {
        border: none;
    }

    .thCartQuant,
    .thCartCodigo {
        text-align: center;
    }

    /* CSS HTML PARA DIV DE ERROS */
    .main {
        background: #e0e0e0;
        overflow: hidden;
        border-radius: 16px;
    }

    .section {
        padding: 87px;
        height: 48vh;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .left {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .left #paraConsultar {
        font-size: 3rem;
        font-family: system-ui, 'Open Sans', 'Helvetica Neue', sans-serif;
        text-shadow: 0 1px 0 #cccccc,
            0 2px 0 #c9c9c9, 0 1px 0 #bbbbbb,
            0 3px 0 #b9b9b9, 0 2px 0 #aaaaaa,
            0 4px 1px rgba(0, 0, 0, 0.1),
            0 0 3px rgba(0, 0, 0, 0.1),
            0 1px 2px rgba(0, 0, 0, 0.3),
            0 2px 3px rgba(0, 0, 0, 0.2),
            0 3px 6px rgba(0, 0, 0, 0.25),
            0 5px 5px rgba(0, 0, 0, 0.2),
            0 12px 12px rgba(0, 0, 0, 0.15);
    }

    .right img {
        width: 245px;
        margin-left: 1rem;
        margin-bottom: -2rem;
        animation: float 1.8s infinite alternate;
    }

    .shadow {
        width: 270px;
        height: 45px;
        background: hsla(38, 21%, 19%, .16);
        border-radius: 50%;
        margin: 0 auto;
        filter: blur(5px);
        animation: shadow 1.8s infinite alternate;
    }

    .divImgErro {
        margin-top: -49px !important;
    }

    @keyframes float {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(15px);
        }
    }

    @keyframes shadow {
        0% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(.85, .85);
        }
    }

    .form-control:focus {
        border-width: 1.5px;
        border-color: #159ce4;
        transition: all 0.5s ease;
    }

    .select2 {
        border-radius: 5px;
        width: 100% !important;
    }

    #divSelectProd {
        margin-bottom: 10px;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .select2-selection--single {
        border-radius: 5px 0 0 5px !important;
    }

    .inputKit {
        text-align: center;
    }

    .tdExcluir {
        padding: 1px !important;
        text-align: center !important;
        margin-top: 2px !important;
    }

    .btnExcluiReparo {
        margin-top: 3px !important;
    }

    .swal-button--btn_cancelar {
        background-color: #cf3d3d !important;
    }

    .swal-button--btn_cancelar:hover {
        background-color: #a33232 !important;
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div class="right_col" role="main">

    <div class="">

        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Consulta produtos
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong></strong> {$mensagem}</div>
                                </div>
                            {/if}
                        </h2>

                        <ul class="nav navbar-right">
                            {if $from !== 'nota' && $from !=='pedido_ps'} <button type="button" id="btnCart"
                                    data-toggle="modal" data-target="#modalCart" onclick="abrirModalItens()">
                                    <span class="fa fa-shopping-cart spanCart" aria-hidden="true"></span>
                                    <span class="quantCart">0</span>
                                </button>
                            {/if}
                            {* <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraPesquisa();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li> *}
                        </ul>
                        <div class="clearfix"></div>
                    </div>


                    <div class="x_content">

                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden id="form" value="produto">
                            <input name=id type=hidden id="id" value="{$id}">
                            <input name=idNewKit type=hidden id="idNewKit" value="{$idNewKit}">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=quantArray type=hidden value={$quantArray}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=grupo type=hidden value="">
                            <input name=localizacao type=hidden value="">
                            <input name=marca type=hidden value="">
                            <input name=quant type=hidden value="false">
                            <input name=codigo type=hidden value="">
                            <input name=from type=hidden id="from" value="{$from}">
                            <input name=quantAtual type=hidden value="{$quantAtual}"> <!-- baixa estoque -->
                            <input name=valorVenda type=hidden value="{$valorVenda}"> <!-- baixa estoque -->
                            <input name=uniFracionada type=hidden value="{$uniFracionada}"> <!-- baixa estoque -->
                            <input name=checkbox type=hidden value="{$checkbox}">
                            <input name=carrinho type=hidden id=carrinho value="{$carrinho}">
                            <input name=idPedido type=hidden id="idPedido" value={$idPedido}>

                            <div class="row">
                                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                                    <label for="codFabricante">C&oacute;digo</label>
                                    <input class="form-control" id="codFabricante" name="codFabricante" autofocus
                                        placeholder="Código Fabricante." value={$codFabricante}>
                                </div>
                                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                    <label for="produtoNome">Descri&ccedil;&atilde;o</label>
                                    <input class="form-control" id="produtoNome" name="produtoNome"
                                        placeholder="Digite a descrição" value="{$produtoNome}">
                                </div>

                                <div class="form-group col-md-2 col-sm-2 col-xs-2">
                                    <label for="btnPesquisa">&nbsp </label>
                                    <button type="button" id="btnPesquisa" class="btn btn-warning form-control"
                                        onClick="javascript:submitLetraPesquisa();">
                                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                            Pesquisa</span>
                                    </button>
                                </div>
                            </div>


                            <div class="row" id="bloco1">
                                <div class="col col-md-12 col-sm-12 col-xs-12" id="divSelectProd"
                                    style="display: none;">
                                    <div class="input-group">
                                        <select class="js-data-example-ajax form-control input-group"
                                            name="produtoCombo" id="produtoCombo"
                                            onChange="javascript:atualizarBotao('');">
                                            {html_options values=$produtoCombo_ids output=$produtoCombo_names selected=$produtoCombo_id}
                                        </select>
                                        <span class="input-group-btn">
                                            <button id="botaoIncluirKitReparo"
                                                style="height:38px;pointer-events:none;opacity: 0.5;" type="button"
                                                class="btn btn-dark" data-toggle="modal"
                                                data-target="#modalAddQuantidade">
                                                <span aria-hidden="true" title="Adicionar item">Confirma</span>
                                            </button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </form>


                    </div>

                </div> <!-- x_panel -->

            </div> <!-- div row = painel principal-->


            <!--{if isset($lanc) && $lanc|@count !== 0}-->
                {if isset($lanc)}
                    <!-- panel tabela dados -->
                    <div class="col-md-12 col-xs-12" style="padding: 0;">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="{$active01}"><a href="#tab_content1" id="dados-tab" role="tab"
                                        data-toggle="tab" aria-expanded="true">Pesquisa</a>
                                </li>
                                <li role="presentation" class="{$active02}">
                                    <a href="#tab_content2" onclick="ativaAba('divNotas')" role="tab" id="rateio-tab"
                                        data-toggle="tab" aria-expanded="true">Notas</a>
                                </li>
                                {* <li role="presentation" class="{$active03}">
                                <a href="#tab_content3" onclick="ativaAba('divTabela')" role="tab" id="importacao-tabela-preco-tab" data-toggle="tab" aria-expanded="true">Tabela</a>
                            </li>    *}
                                <li role="presentation" class="{$active04}">
                                    <a href="#tab_content4" onclick="ativaAba('divEstoque')" role="tab" id="dados-tab-estoque"
                                        data-toggle="tab" aria-expanded="true">Estoque</a>
                                </li>
                                <li role="presentation" class="{$active05}">
                                    <a href="#tab_content5" onclick="ativaAba('divCotacao')" role="tab" id="dados-tab-cotacao"
                                        data-toggle="tab" aria-expanded="true">Cota&ccedil;&atilde;o</a>
                                </li>
                                <li role="presentation" class="{$active06}">
                                    <a href="#tab_content6" onclick="ativaAba('divPedido')" role="tab" id="dados-tab-pedido"
                                        data-toggle="tab" aria-expanded="true">Pedido</a>
                                </li>
                                <li role="presentation" class="{$active07}">
                                    <a href="#tab_content7" onclick="ativaAba('divReparo')" role="tab" id="dados-tab-reparo"
                                        data-toggle="tab" aria-expanded="true">Reparo</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade {$activeTab01} small" id="tab_content1"
                                    aria-labelledby="home-tab">
                                    <div class="panel-body">
                                        <div class="x_panel small" style="padding: 8px 2px 2px 2px;">

                                            <div class="col-md-12 col-sm-12 col-xs-12 tabPrincipal">

                                                {*{if isset($lanc)}*}
                                                    <table id="datatable" class="table table-bordered jambo_table">
                                                        <thead>
                                                            <tr style="background: #2A3F54; color: white;">
                                                                <th style="width: 10px;"></th>
                                                                <th style="width: 10px;"></th>
                                                                <th style="width: 40px;">
                                                                    <center>C&oacute;digo</center>
                                                                </th>
                                                                <th style="width: 40px;">
                                                                    <center>C&oacute;d. nota</center>
                                                                </th>
                                                                <th style="width: 40px;">
                                                                    <center>C&oacute;d. Fabricante</center>
                                                                </th>
                                                                <th style="width: 50px;">Local</th>
                                                                {* <th style="width: 40px;">Marca</th> *}
                                                                <th>
                                                                    <center> Descri&ccedil;&atilde;o</center>
                                                                </th>
                                                                <th style="width: 30px;">
                                                                    <center>Un</center>
                                                                </th>
                                                                <th>
                                                                    <center>Venda</center>
                                                                </th>
                                                                <th>
                                                                    <center>Qtd Disp</center>
                                                                </th>

                                                                <th style="width: 71px;">Sele&ccedil;&atilde;o</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {section name=i loop=$lanc}
                                                                <!-- NAO ALTERAR OS ATRIBUTOS DOS ELEMENTOS DESSE BLOCO SEM VERIFICAR O JS -->

                                                                <tr id="row{$lanc[i].CODIGO}">
                                                                    <td>
                                                                        <input type="checkBox" name="pedidoChecked" id="pedidoChecked"
                                                                            {if ($pedidoChecked eq 'true')} checked {/if}
                                                                            onClick="javascript:submitLetraPesquisa({$lanc[i].CODIGO},'{$lanc[i].CODFABRICANTE}', 'true');" />
                                                                    </td>
                                                                    <td style="padding: 3px;">
                                                                        <!-- Adicione um botão para mostrar/ocultar as linhas de código equivalente -->
                                                                        <button class="toggle-equivalent"
                                                                            style="width: 65px; pointer-events: none;"
                                                                            data-codigo="{$lanc[i].CODIGO}"
                                                                            onclick="javascript:mostrarTRs({$lanc[i].CODIGO}, this)"
                                                                            disabled>
                                                                            <!-- Botão sem equivalente pois no js ira alterar se existir o atributo disabled tambem e alterado -->
                                                                            Sem Equivalente
                                                                        </button>
                                                                    </td>
                                                                    <td style="width: 70px;">
                                                                        <center> {$lanc[i].CODIGO} </center>
                                                                    </td>
                                                                    <td>
                                                                        <center> {$lanc[i].CODPRODUTONOTA} </center>
                                                                    </td>
                                                                    <td>
                                                                        <center> {$lanc[i].CODFABRICANTE} </center>
                                                                    </td>
                                                                    <td>
                                                                        <center> {$lanc[i].LOCALIZACAO} </center>
                                                                    </td>
                                                                    {* <td><center> {$lanc[i].NOMEMARCA} </center></td> *}
                                                                    <td> {$lanc[i].DESCRICAO} </td>
                                                                    <td>
                                                                        <center> {$lanc[i].UNIDADE} </center>
                                                                    </td>
                                                                    <td name="vlrVenda">
                                                                        <center> {$lanc[i].VENDA|number_format:2:",":"."} </center>
                                                                    </td>
                                                                    <td>
                                                                        <center> {$lanc[i].ESTOQUE|number_format:2:",":"."} </center>
                                                                    </td>
                                                                    <td class="last" style="padding: 5px !important;">
                                                                        <center>


                                                                            {if $from eq 'nota'}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoNf({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}');">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {else if ($from == 'baixa_estoque' or $from == 'baixa_estoque_new')}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoPesquisaParam({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','{$lanc[i].CODIGO}', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {else if $from == 'ped_telhas_novo'}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoPesquisaParam({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','null', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {else if $from == 'produto_ml'}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoPesquisaParam('{$lanc[i].CODIGO}', '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','null', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {else if $from == 'ordem_compra'}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoPesquisaOC(this);">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {else}
                                                                                <button type="button" class="btn btn-success btn-xs"
                                                                                    onclick="javascript:fechaProdutoPesquisa(this);">
                                                                                    <span class="glyphicon glyphicon-ok"
                                                                                        aria-hidden="true"></span></button>
                                                                            {/if}
                                                                            {if $from !== 'nota' && $from !=='pedido_ps'}
                                                                                <button type="button" class="btn btn-primary btn-xs"
                                                                                    data-toggle="modal" data-target="#modalInsertCart"
                                                                                    onclick="javascript:dadosItemsCart('{$lanc[i].CODIGO}', '{$lanc[i].DESCRICAO}');">
                                                                                    <span class="fa fa-cart-plus" aria-hidden="true"></span>
                                                                                </button>
                                                                            {/if}
                                                                        </center>
                                                                    </td>
                                                                </tr>

                                                                <!-- ... células da tabela de equivalentes ... -->
                                                                {section name=j loop=$equi}

                                                                    {if $lanc[i].CODIGO ==$equi[j].CODIGO}

                                                                        <tr class="equivalent-table-{$equi[j].CODIGO}" id="equivalent-codes"
                                                                            style="background-color: #f1d6f8a8;"
                                                                            data-codigo="{$equi[j].CODIGO}">

                                                                            <td style="width: 10px;">
                                                                                <input type="checkBox" name="pedidoChecked" id="pedidoChecked"
                                                                                    onClick="javascript:submitLetraPesquisa({$equi[j].CODIGO},'{$equi[j].CODFABRICANTE}');" />
                                                                            </td>
                                                                            <td style="color: #169F85;"> Equivalente </td>
                                                                            <td style="width: 40px;">
                                                                                <center> {$equi[j].CODEQUIVALENTE} <center>
                                                                            </td>
                                                                            <td colspan="3" style="width: 40px;">
                                                                                <center> {$equi[j].CODPRODUTONOTA} <center>
                                                                            </td>
                                                                            <td style="display:none;"> {$equi[i].LOCALIZACAO} </td>
                                                                            <td colspan="1">
                                                                                <center> {$equi[j].DESCRICAO} </center>
                                                                            </td>
                                                                            <td> {$equi[j].UNIDADE} </td>
                                                                            <td>
                                                                                <center> {$equi[j].VENDA|number_format:2:",":"."} </center>
                                                                            </td>
                                                                            <td>
                                                                                <center> {$equi[j].ESTOQUE|number_format:2:",":"."} </center>
                                                                            </td>
                                                                            <td style="width: 30px; padding:6px 0 2px 5px;" class="last">
                                                                                {if $from eq 'nota'}
                                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                                        onclick="javascript:fechaProdutoNf({$equi[i].CODIGO}, '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}');">
                                                                                        <span class="glyphicon glyphicon-ok"
                                                                                            aria-hidden="true"></span></button>
                                                                                {else if $from == 'baixa_estoque'}
                                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                                        onclick="javascript:fechaProdutoPesquisaParam({$equi[i].CODIGO}, '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}','null', '{$equi[i].ESTOQUE|number_format:2:",":"."}', '{$equi[i].VENDA|number_format:2:",":"."}', '{$equi[i].UNIFRACIONADA}');">
                                                                                        <span class="glyphicon glyphicon-ok"
                                                                                            aria-hidden="true"></span></button>
                                                                                {else if $from == 'produto_ml'}
                                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                                        onclick="javascript:fechaProdutoPesquisaParam('{$equi[i].CODIGO}', '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}','null', '{$equi[i].ESTOQUE|number_format:2:",":"."}', '{$equi[i].VENDA|number_format:2:",":"."}', '{$equi[i].UNIFRACIONADA}');">
                                                                                        <span class="glyphicon glyphicon-ok"
                                                                                            aria-hidden="true"></span></button>
                                                                                {else if $from == 'ordem_compra'}
                                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                                        onclick="javascript:fechaProdutoPesquisaOcEqui(this);">
                                                                                        <span class="glyphicon glyphicon-ok"
                                                                                            aria-hidden="true"></span></button>
                                                                                {else}
                                                                                    <button type="button" class="btn btn-success btn-xs"
                                                                                        onclick="javascript:fechaProdutoPesquisa(this, '{$lanc[i].CODPRODUTOREPARO}', '{$lanc[i].DESCRICAO}');">
                                                                                        <span class="glyphicon glyphicon-ok"
                                                                                            aria-hidden="true"></span></button>
                                                                                {/if}


                                                                                {if $from !== 'nota'}
                                                                                    <button type="button" class="btn btn-primary btn-xs"
                                                                                        data-toggle="modal" data-target="#modalInsertCart"
                                                                                        onclick="javascript:dadosItemsCart('{$lanc[i].CODIGO}', '{$lanc[i].DESCRICAO}');">
                                                                                        <span class="fa fa-cart-plus" aria-hidden="true"></span>
                                                                                    </button>
                                                                                {/if}
                                                                            </td>
                                                                        </tr>
                                                                    {/if}

                                                                {/section}

                                                            {/section}
                                                            <!-- NAO ALTERAR OS ATRIBUTOS DOS ELEMENTOS DESSE BLOCO SEM VERIFICAR O JS -->
                                                        </tbody>
                                                    </table>
                                                {*{else}

                                                                                                                                                    <main class="main">
                                                                                                                                                        <section class="section">
                                                                                                                                                            <div class="left">
                                                                                                                                                                <p id="paraConsultar" style="color: #43401ad6;">Não foi localizado nota fiscal <br> para esse item !</p>
                                                                                                                                                            </div>

                                                                                                                                                        </section>
                                                                                                                                                    </main>








                                                {/if}*}

                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <!--##################################  DIV NOTAS FISCAIS ##################################-->
                                <div role="tabpanel" class="tab-pane fade small {$activeTab02} small" id="tab_content2"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" id="divNotas">

                                        {if $existeNota == 'yes'}

                                            <div class="x_panel" style="padding:8px;">
                                                <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                                    <thead>
                                                        <tr style="background: gray; color: white;">
                                                            <th style="width:100px">Código Nota</th>
                                                            <th style="width:82px">
                                                                <center> Tipo Doc </center>
                                                            </th>
                                                            <th style="width:60px">
                                                                <center> Número </center>
                                                            </th>
                                                            <th style="width:50px">
                                                                <center> Origem </center>
                                                            </th>
                                                            <th style="width:70px">
                                                                <center> Documento </center>
                                                            </th>
                                                            <th style="width:50px">
                                                                <center> OS </center>
                                                            </th>
                                                            <th style="width:70px">
                                                                <center> Emissão </center>
                                                            </th>
                                                            <th>Pessoa</th>
                                                            <th style="width:70px">
                                                                <center> Quantidade </center>
                                                            </th>
                                                            <th style="width:84px">
                                                                <center> Valor Unitário </center>
                                                            </th>
                                                            {* <th style="width:60px">Desconto</th> *}
                                                            <th style="width:90px">Vlr Uni Líquido</th>
                                                            {* <th style="width:100px">Vlr ST</th> *}
                                                            <th style="width:90px">
                                                                <center> Total Produto </center>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {section name=i loop=$notas}
                                                            {if $notas[i].NUMERO > 0}
                                                                <tr>
                                                                    <td name="codigoNota"> {$notas[i].CODIGONOTA} </td>
                                                                    <td name="tipoDoc">
                                                                        <center> {$notas[i].TIPO} </center>
                                                                    </td>
                                                                    <td name="docto">
                                                                        <center> {$notas[i].NUMERO} </center>
                                                                    </td>
                                                                    <td name="tipo">
                                                                        <center> {$notas[i].ORIGEM} </center>
                                                                    </td>
                                                                    <td name="docto">
                                                                        <center> {$notas[i].DOC} </center>
                                                                    </td>
                                                                    <td name="os">
                                                                        <center> {$notas[i].OS} </center>
                                                                    </td>
                                                                    <td name="emissao">
                                                                        <center> {$notas[i].EMISSAO|date_format:"%d/%m/%Y"} </center>
                                                                    </td>
                                                                    <td name="cliente"> {$notas[i].NOME}</td>
                                                                    {if $notas[i].TIPO !== "NF ENTRADA"}
                                                                        <td name="qtsolicitada">
                                                                            <center> {$notas[i].QTSOLICITADA|number_format:2:",":"."} </center>
                                                                        </td>
                                                                    {else}
                                                                        <td name="qtsolicitada">
                                                                            <center> {$notas[i].QUANT|number_format:2:",":"."} </center>
                                                                        </td>
                                                                    {/if}

                                                                    {if $notas[i].TIPO !== "NF ENTRADA"}
                                                                        <td name="unitario">
                                                                            <center> R$ {$notas[i].UNITARIOPEDIDO|number_format:2:",":"."}
                                                                            </center>
                                                                        </td>
                                                                    {else}
                                                                        <td name="unitario">
                                                                            <center> R$ {$notas[i].UNITARIO|number_format:2:",":"."} </center>
                                                                        </td>
                                                                    {/if}

                                                                    {* <td name="percDesconto"> <center>{$notas[i].PERCDESCONTO|number_format:2:",":"."} % </center></td> *}
                                                                    <td name="unitario">
                                                                        <center> R$ {$notas[i].UNITARIOLIQUIDO|number_format:2:",":"."}
                                                                        </center>
                                                                    </td>
                                                                    {* <td name="totalItem"> {$notas[i].ST|number_format:2:",":"."} </td> *}
                                                                    {if $notas[i].TIPO !== "NF ENTRADA"}
                                                                        <td title="Desconto: {$notas[i].PERCDESCONTO}" name="total">
                                                                            <center> R$ {$notas[i].TOTALITEM|number_format:2:",":"."} </center>
                                                                        </td>
                                                                    {else}
                                                                        <td title="Desconto: {$notas[i].PERCDESCONTO}" name="total">
                                                                            <center> R$ {$notas[i].TOTAL|number_format:2:",":"."} </center>
                                                                        </td>
                                                                    {/if}

                                                                </tr>
                                                                <p>
                                                                {/if}
                                                            {/section}
                                                    </tbody>
                                                </table>
                                            </div>


                                        {else if $existeNota == 'no'}

                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #43401ad6;">Não foi localizado nota fiscal <br> para esse item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/notfound.png" alt="imagem registros não localizados">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}

                                        {else}

                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #1c683f;">Para consulta de nota fiscal <br> selecione apenas um produto !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/error404.png" alt="imagem de erro 404">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}

                                        {/if}
                                    </div>
                                </div>
                                <!--################################## FIM DIV NOTAS FISCAIS ##################################-->


                                <!--##################################  DIV TABELA ##################################-->
                                {* <div role="tabpanel" class="tab-pane fade small {$activeTab03}" id="tab_content3" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th width="25%">Fornecedor</th>
                                                <th width="15%">Cod Original</th>
                                                <th width="30%">Descricao</th>
                                                <th width="10%">Preço</th>
                                                <th width="10%">IPI</th>
                                                <th width="10%">Preço Venda</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$tabela}
                                                <tr>
                                                    <td name="total"> {$tabela[i].NOME} </td>
                                                    <td name="total"> {$tabela[i].CODORIGINAL} </td>
                                                    <td name="total"> {$tabela[i].DESCRICAO} </td>
                                                    <td name="total"> {$tabela[i].PRECO|number_format:2:",":"."} </td>
                                                    <td name="total"> {$tabela[i].IPI} </td>
                                                    <td name="total"> {$tabela[i].PRECOVENDA|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div> *}
                                <!--################################## FIM DIV TABELA ##################################-->


                                <!--##################################  DIV ESTOQUE ##################################-->
                                <div role="tabpanel" class="tab-pane fade small {$activeTab04}" id="tab_content4"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" id="divEstoque">
                                        {if $existeEstoque eq 'yes'}
                                            <div class="x_panel">
                                                <table id="datatable-est" class="table table-bordered jambo_table col-md-8">
                                                    <thead>
                                                        <tr style="background: gray; color: white;">
                                                            <th>Filial</th>
                                                            <th>Estoque</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {section name=i loop=$estoque}
                                                            <tr>
                                                                <td name="centroCusto"> {$estoque[i].CENTROCUSTO} </td>
                                                                <td name="estoque"> {$estoque[i].ESTOQUE|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section}
                                                    </tbody>
                                                </table>
                                            </div>
                                        {else}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #1c683f;">Para consulta de estoque <br> selecione apenas um item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/error404.png" alt="imagem de erro 404">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {/if}

                                    </div>
                                </div>
                                <!--################################## FIM DIV ESTOQUE ##################################-->


                                <!--##################################  DIV COTACAO ##################################-->
                                <div role="tabpanel" class="tab-pane fade small {$activeTab05}" id="tab_content5"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" id="divCotacao">

                                        {if $existeCotacao eq 'yes'}
                                            <div class="x_panel">
                                                <table id="datatable-cot" class="table table-bordered jambo_table">
                                                    <thead>
                                                        <tr style="background: #2A3F54; color: white;">
                                                            <th>Cota&ccedil;&atilde;o</th>
                                                            <th>Cliente</th>
                                                            <th>C&oacute;digo</th>
                                                            <th>C&oacute;d Fabricante</th>
                                                            <th>Qtd Solicitada</th>
                                                            <th>Emiss&atilde;o</th>
                                                            <th>Total Ped</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {section name=i loop=$cotacao}
                                                            <tr>
                                                                <td name="total"> {$cotacao[i].ID} </td>
                                                                <td name="total"> {$cotacao[i].NOME} </td>
                                                                <td name="total"> {$cotacao[i].ITEMESTOQUE} </td>
                                                                <td name="total"> {$cotacao[i].ITEMFABRICANTE} </td>
                                                                <td name="total"> {$cotacao[i].QTSOLICITADA|number_format:2:",":"."}
                                                                </td>
                                                                <td name="total"> {$cotacao[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                <td name="total"> {$cotacao[i].TOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section}
                                                    </tbody>
                                                </table>
                                            </div>
                                        {elseif $existeCotacao eq 'no'}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #43401ad6;">Nenhuma cotação foi localizada<br> para esse item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/notfound.png" alt="imagem registros não localizados">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {else}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #1c683f;">Para consulta de cotações <br> selecione apenas um item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/error404.png" alt="imagem de erro 404">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {/if}

                                    </div>
                                </div>
                                <!--################################## FIM DIV COTACAO ##################################-->


                                <!--##################################  DIV PEDIDO ##################################-->
                                <div role="tabpanel" class="tab-pane fade small {$activeTab06}" id="tab_content6"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" id="divPedido">

                                        {if $existePedido eq 'yes'}

                                            <div class="x_panel">
                                                <table id="datatable-ped" class="table table-bordered jambo_table">
                                                    <thead>
                                                        <tr style="background: #2A3F54; color: white;">
                                                            <th>Cota&ccedil;&atilde;o</th>
                                                            <th>Cliente</th>
                                                            <th>C&oacute;digo</th>
                                                            <th>C&oacute;d Fabricante</th>
                                                            <th>Qtd Solicitada</th>
                                                            <th>Emiss&atilde;o</th>
                                                            <th>Total Ped</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {section name=i loop=$pedido}
                                                            <tr>
                                                                <td name="total"> {$pedido[i].ID} </td>
                                                                <td name="total"> {$pedido[i].NOME} </td>
                                                                <td name="total"> {$pedido[i].ITEMESTOQUE} </td>
                                                                <td name="total"> {$pedido[i].ITEMFABRICANTE} </td>
                                                                <td name="total"> {$pedido[i].QTSOLICITADA|number_format:2:",":"."}
                                                                </td>
                                                                <td name="total"> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                                <td name="total"> {$pedido[i].TOTAL|number_format:2:",":"."} </td>
                                                            </tr>
                                                            <p>
                                                            {/section}
                                                    </tbody>
                                                </table>
                                            </div>

                                        {elseif $existePedido eq 'no'}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #43401ad6;">Nenhum pedido foi localizado<br> para esse item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/notfound.png" alt="imagem registros não localizados">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {else}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #1c683f;">Para consulta de pedidos <br> selecione apenas um item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/error404.png" alt="imagem de erro 404">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {/if}

                                    </div>
                                </div>
                                <!--################################## FIM DIV PEDIDO ##################################-->


                                <!--##################################  DIV REPARO ##################################-->
                                <div role="tabpanel" class="tab-pane fade small {$activeTab07}" id="tab_content7"
                                    aria-labelledby="profile-tab">

                                    <div class="panel-body" id="divReparo">

                                        {if $existeReparo eq 'yes'}

                                            <div class="row" id="bloco2">
                                                <div class="col-md-8 col-sm-8 col-xs-8"
                                                    style="text-align: center; margin-left: 15px;">
                                                    <label style="font-family: comic sans ms, cursive; font-size: 15px;"> Código -
                                                        Descrição </label>
                                                    <div class="input-group">
                                                        <input type="text" style="text-align: center; font-size: 11px;" readonly
                                                            class="form-control col-md-3" id="kitCodDesc" name="kitCodDesc"
                                                            value="{$kitCodDesc}">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-dark" onclick="toggleSelect()">
                                                                <span aria-hidden="true" title="Adicionar item">adicionar
                                                                    item</span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>

                                                {* <div class="col-md-3 offset-md-3 col-sm-3 offset-sm-3 col-xs-3 offset-xs-3"></div> *}

                                                <div class="form-group col-md-3 col-sm-3 col-xs-3" style="text-align: center;">
                                                    <label style="font-family: comic sans ms, cursive; font-size: 15px;"> Informe a
                                                        quantidade </label>
                                                    <div class="input-group">
                                                        <input type="money" style="text-align: center;" class="form-control money"
                                                            id="quantValidaReparo" name="quantValidaReparo" placeholder="0,00"
                                                            value="{$quantValidaReparo}">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-primary"
                                                                onClick="validaReparo(document.getElementById('quantValidaReparo'))">
                                                                <span class="" aria-hidden="true"> Validar </span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="x_panel">
                                                <table id="datatable-kitreparo" class="table table-bordered jambo_table">
                                                    <thead>
                                                        <tr style="background: #cdcdcd; color: rgb(46, 46, 46); font-size: 11px;">
                                                            <th>
                                                                <center> Código Produto </center>
                                                            </th>
                                                            <th>
                                                                <center> Código Fabrcante </center>
                                                            </th>
                                                            <th> Descrição </th>
                                                            <th>
                                                                <center> Quant. Kit / Reparo </center>
                                                            </th>
                                                            <th>
                                                                <center> Quant. Solicitada </center>
                                                            </th>
                                                            <th>
                                                                <center> Quant. Estoque </center>
                                                            </th>
                                                            <th>
                                                                <center> Quant. atendida </center>
                                                            </th>
                                                            <th>
                                                                <center> Excluir </center>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {section name=i loop=$reparo}
                                                            <tr id="{$reparo[i].PRODUTO_ID}">
                                                                <td id="reparoId">
                                                                    <center> {$reparo[i].PRODUTO_ID} </center>
                                                                </td>
                                                                <td id="reparoCodFad">
                                                                    <center> {$reparo[i].CODFABRICANTE} </center>
                                                                </td>
                                                                <td id="reparoDesd"> {$reparo[i].DESCRICAO} </td>
                                                                <td id="reparoQuand">
                                                                    <center> {$reparo[i].QUANT|number_format:2:",":"."} </center>
                                                                </td>
                                                                <td id="reparoSolicitada">
                                                                    <center> {0|number_format:2:",":"."} </center>
                                                                </td>
                                                                <td id="reparoEstoque">
                                                                    <center> {$reparo[i].ESTOQUE|number_format:2:",":"."} </center>
                                                                </td>
                                                                <td id="reparoAtendida">
                                                                    <center> {0|number_format:2:",":"."} </center>
                                                                </td>
                                                                <td class="tdExcluir">
                                                                    <button type="button" title="Deletar"
                                                                        class="btn btn-danger btn-xs btnExcluiReparo"
                                                                        onclick="javascript:submitExcluirReparoConsultaPreco('{$reparo[i].ID}');">
                                                                        <span class="glyphicon glyphicon-trash"
                                                                            aria-hidden="true"></span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <p>
                                                            {/section}
                                                    </tbody>
                                                </table>
                                            </div>

                                        {elseif $existeReparo eq 'no'}

                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #43401ad6;">Nenhum reparo foi localizado<br> para esse item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/notfound.png" alt="imagem registros não localizados">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {else}
                                            {* <main class="main">
                                            <section class="section">
                                                <div class="left">
                                                    <p id="paraConsultar" style="color: #1c683f;">Para consulta de kit/reparo <br> selecione apenas um item !</p>
                                                </div>
                                                <div class="right divImgErro">
                                                    <img src="{$pathBibImagens}/error404.png" alt="imagem de erro 404">
                                                    <div class="shadow"></div>
                                                </div>
                                            </section>
                                        </main> *}
                                        {/if}

                                    </div>
                                </div>
                                <!--################################## FIM DIV REPARO ##################################-->

                            </div>
                        </div>
                    </div> <!-- tabpanel -->
                </div> <!-- div class="x_panel" = tabela principal-->
            </div> <!-- div  "-->
        </div> <!-- div role=main-->

    {/if}

    <!-- MODAL ADD PRODUTO -->
    <div class="modal fade" id="modalInsertCart" tabindex="-1" role="dialog" aria-labelledby="modalInsertLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar ao carrinho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="item-form">
                        <input type="text" hidden id="recipient-codigo" value="{$recipientCodigo}">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Item</label>
                            <input type="text" class="form-control" id="recipient-name" value="{$recipientName}">
                        </div>
                        <div class="col align-self-center">
                            <label for="recipient-quant" class="col-form-label">Quantidade</label>
                            <input type="money" class="form-control money" id="recipient-quant" value="{$recipientQuant}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="closedModalproduto">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="javascript:addItemsCart()">Adicionar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL CARRINHO -->
    <div class="modal fade" id="modalCart" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <center>Carrinho</center>
                    </h5>
                    <button type="button" class="close" aria-label="Close"> </button>
                    <input name=pessoaId id="pessoaId" type=hidden value={$pessoaId}>

                    <div class="col-md-12 col-sm-12 col-xs-12" {if $from == 'pedido_ps'}
                        style="margin-bottom: 0 !important; display:none;" {else} style="margin-bottom: 0 !important;"
                        {/if}>
                        <div class="input-group input-sm" style="margin: 0; padding:0;">
                            <input type="text" class="form-control input-sm" readonly id="nomeCliente" name="nomeCliente"
                                placeholder="Cliente" value="{$nomeCliente}">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" style="height: 30px;"
                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarCarrinho');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true" style="top: 0;"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                {/if}
            </div>
            <div class="modal-body">
                <form id="item-form-cart">
                    <!--Area preenchida por js -->
                </form>
            </div>
            <div class="modal-footer">

                {* <div class="col-md-2 col-sm-2 col-xs-2" style="text-align-last: justify !important;">
                    <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="clearCart()">
                        Limpar Carrinho &nbsp;<i class="fa fa-eraser" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="col-md-6 offset-md-6 col-sm-6 offset-sm-6"></div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <button type="button" class="btn btn-secondary btnCancelarModal"
                        data-dismiss="modal">Cancelar</button>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <button type="button" class="btn btn-primary" onclick="importaCarrinhoCotacao()">
                        {if $from == 'pedido_ps'}
                            Incluir no pedido
                        {else}
                            Cadastrar cotação
                        {/if}
                    </button>
                </div> *}

            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD QUANTIDADE  -->
<div class="modal fade" id="modalAddQuantidade" tabindex="-1" role="dialog" aria-labelledby="modalAddQuantidadeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align: center;">Informe a quantidade do item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="item-form">
                    <div class="col align-self-center">
                        <input type="money" class="form-control money" style="text-align: center;"
                            id="addQuantidadeKitReparo" value="{$addQuantidadeKitReparo}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closedKitReparo"
                    data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary"
                    onclick="javascript:cadastraItemKitReparo()">Cadastrar</button>
            </div>
        </div>
    </div>
</div>


{include file="template/database.inc"}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>


<script>
    document.addEventListener("keypress", function(e) {
        if (e.keyCode === 13) {
            submitLetraPesquisa();
        }
    });
</script>


<script>
    $(document).ready(function() {
        $('#produtoCombo').select2({
            placeholder: "Buscar",
            language: {
                //Descricao da quantidade de caracteres.
                inputTooShort: function() {
                    return "Digite no mínimo 3 caracteres";
                }
            },
            minimumInputLength: 3,
            delay: 250,
            ajax: {
                dataType: "json",
                type: "POST",
                url: document.URL + "?mod=est&form=produto&submenu=pesquisaProdutoComboKit&opcao=blank",
                processResults: function(response) {
                    return {
                        results: response
                    };
                }
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var newOption = new Option(data.text, data.id, false, false);
        $('#produtoCombo').append(newOption).trigger('change');
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