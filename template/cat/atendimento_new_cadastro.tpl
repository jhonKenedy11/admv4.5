<style>
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
        margin-left: -2.55px !important;
        padding-top: 1.89px !important;
        width: 8px !important;
        height: 8px !important;
        color: white;
    }

    .swal-text {
        margin-top: 20px;
        font-size: 18px;
    }

    .swal-modal {
        width: 1000px !important;
    }

    .form-control,
    .panel-default,
    .x_panel {
        border-radius: 5px;
    }

    .x_panel {
        margin-top: -8px !important;
    }

    .title-cadastro {
        padding-left: 0;
        margin-top: 11px;
        width: 100px !important;
    }

    .title-pedido-servico {
        padding-right: 0;
        width: 240px;
    }

    .btnRelatorios {
        margin-top: 4px;
        width: 100% !important;
    }

    .btnHelp {
        background-color: #ebd43b;
        margin-top: 4px;
        width: 100% !important;
    }

    .btnHelp:hover {
        background-color: #c7b334;
    }

    .btnHelpSpan {
        color: #5c5c5c !important;
    }

    .dropMenuRel {
        right: -97% !important;
        border-radius: 5px;
        background-color: rgba(76, 75, 75, 0.882);
    }

    .fa-wrench,
    .fa-question {
        font-size: 18px;
    }

    .swal-button--btn_atualizar_existente {
        background-color: #169F85 !important;
        transition: background-color 0.3s ease;
    }

    .swal-button--btn_atualizar_existente:hover {
        background-color: #127563 !important;
    }

    .swal-button--btn_cadastrar_novo {
        background-color: #8a74f9 !important;
        transition: background-color 0.3s ease;
    }

    .swal-button--btn_cadastrar_novo:hover {
        background-color: #454886 !important;
    }

    .swal-button--btn_cancelar {
        background-color: #fe6060 !important;
        transition: background-color 0.3s ease;
    }

    .swal-button--btn_cancelar:hover {
        background-color: #b23030 !important;
    }
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_new.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 0 2px 2px 2px;">
    <div class="">

        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="cat">
            <input name=form type=hidden value="atendimento_new">
            <input name=submenu type=hidden value={$subMenu}>
            <div id="idAtendimento">
                <input name=id type=hidden value={$id}>
            </div>
            <input name=pedido_id type=hidden value="{$pedido_id}">
            <input name=idPecas type=hidden value={$idPecas}>
            <input name=catEquipamentoId type=hidden value="{$catEquipamentoId}">
            <input name=letra type=hidden value={$letra}>
            <input name=letra_peca type=hidden value={$letra_peca}>
            <input name=letra_servico type=hidden value={$letra_servico}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=pesq type=hidden value={$pesq}>
            <input name=fornecedor type=hidden value="">
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=opcao_item type=hidden value={$opcao_item}>
            <input name=usuario_equipe_ids type=hidden value="">
            <input name=atualiza_equipe type=hidden value="false">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <div class="">
                                <div class="col-md-10 title-cadastro">
                                    {if $subMenu eq "cadastrar"}
                                        <h2>Ordem de Servi&ccedil;o&nbsp;- Cadastro</h2>
                                    {else}
                                        <h2>Ordem de Servi&ccedil;o&nbsp;- Altera&ccedil;&atilde;o  - {$id}</h2>
                                    {/if}
                                </div>
                            </div>

                            {* <h2>
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                    </h2> *}
                            {include file="../bib/msg.tpl"}
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        {if $situacao == '6' or $situacao == '10'} disabled {/if}
                                        onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li> *}
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        <li>
                                            <button {if $id eq ''} disabled {/if} type="button"
                                                class="btn btn-primary btn-xs btnRelatorios"
                                                onClick="javascript:duplicaOs('{$id}');"><span>Duplicar
                                                    OS</span></button>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-question"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnHelp"
                                                data-toggle="modal" data-target="#modalHelp"><span
                                                    class="btnHelpSpan">Atualiza produto</span></button>
                                        </li>
                                    </ul>
                                </li>
                                {* <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />

                            <div class="form-group line-formated">
                                <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                    <label for="conta">Cliente</label>
                                    <div class="input-group line-formated">
                                        <input type="text" class="form-control input-sm" id="nome" name="nome"
                                            placeholder="Conta" required value="{$nome}" readonly>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar&origem=atendimento');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="contato">Contato</label>
                                    <input type="text" class="form-control input-sm" id="contato" name="contato"
                                        placeholder="Contato" required value="{$contato}">
                                </div>

                                <div class="col-lg-3 col-sm-3 col-xs-3 text-left line-formated">
                                    <label>Situa&ccedil;&atilde;o</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="situacao" class="form-control input-sm">
                                            {html_options values=$situacao_ids selected=$situacao output=$situacao_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-lg-3 col-sm-3 col-xs-3 text-left line-formated">
                                    <label>Atendente</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="usrAbertura" class="form-control input-sm" title="Atendente"
                                            alt="Atendente">
                                            {html_options values=$usrAbertura_ids selected=$usrAbertura output=$usrAbertura_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-3 text-left line-formated">
                                    <label>Tipo Atendimento</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="catTipoId" name="catTipoId" class="form-control input-sm"
                                            title="Tipo Atendimento" alt="Tipo Atendimento">
                                            {html_options values=$catTipoId_ids selected=$catTipoId output=$catTipoId_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Condi&ccedil;&atilde;o de Pagamento</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="condPgto" name="condPgto"
                                            class="input-sm js-example-basic-single form-control"
                                            title="Condi&ccedil;&atilde;o de Pagamento"
                                            alt="Condi&ccedil;&atilde;o de Pagamento">
                                            {html_options values=$condPgto_ids selected=$condPgto output=$condPgto_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                    <label for="descEquipamento">
                                        Descri&ccedil;&atilde;o Equipamento
                                    </label>
                                    <div class="input-group line-formated">
                                        <input type="text" class="form-control input-sm" id="descEquipamento"
                                            name="descEquipamento" placeholder="Descri&ccedil;&atilde;o do Equipamento"
                                            required value="{$descEquipamento}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=cat&form=equipamento&opcao=pesquisar&origem=ordemServico');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="emissao">Abertura</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Data de Abertura."
                                        id="dataAbertura" title="Data de Abertura" alt="Data de Abertura"
                                        name="dataAbertura" value="{$dataAbertura}">
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="prazoEntrega">Prazo Entrega</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Prazo de Entrega."
                                        id="prazoEntrega" title="Prazo de Entrega" alt="Prazo de Entrega"
                                        name="prazoEntrega" value="{$prazoEntrega}">
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="emissao">Fechamento</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Data de Fechamento."
                                        id="dataFechamentoEnd" title="Data de Fechamento" alt="Data de Fechamento"
                                        name="dataFechamentoEnd" value="{$dataFechamentoEnd}">
                                </div>
                            </div> <!-- FIM class="form-group" -->
                            <div class="form-group line-formated">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obs">Observa&ccedil;&otilde;es Atendimento</label>
                                    <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                        rows="2">{$obs}</textarea>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obsServico">Observa&ccedil;&otilde;es Servi&ccedil;o</label>
                                    <textarea class="resizable_textarea form-control input-sm" id="obsServicos"
                                        name="obsServicos" rows="2">{$obsServicos}</textarea>
                                </div>
                            </div>
                            </p>
                            <div id="divTotal" class="form-group line-formated">
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="valorPecas">Valor Pe&ccedil;as</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Pe&ccedil;as."
                                            id="valorPecas" name="valorPecas" value="{$valorPecas}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="valorServicos">Valor Servi&ccedil;o</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Servi&ccedil;o."
                                            id="valorServicos" name="valorServicos" value="{$valorServicos}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="Visita">Valor Visita</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control money input-sm" placeholder="Valor Visita."
                                            id="valorVisita" name="valorVisita" value="{$valorVisita}"
                                            onchange="javascript:calculaTotal()">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="desconto">Desconto</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control money input-sm" placeholder="Desconto."
                                            id="valorDesconto" name="valorDesconto" value="{$valorDesconto}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="total">T O T A L</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>

                                        <input class="form-control input-sm" placeholder="Total Atendimento."
                                            id="valorTotal" name="valorTotal" value="{$valorTotal}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

                                    <li role="presentation"><a href="#tab_content2" id="pecas-tab" role="tab"
                                            data-toggle="tab" aria-expanded="true">Pe&ccedil;as</a>
                                    </li>
                                    <li role="presentation"><a href="#tab_content3" id="servicos-tab" role="tab"
                                            data-toggle="tab" aria-expanded="true">Servi&ccedil;os</a>
                                    </li>
                                    <li role="presentation"><a href="#tab_content4" id="equipe-tab" role="tab"
                                            data-toggle="tab" aria-expanded="false">Equipe</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel"
                                        class="tab-pane fade {if $tab eq 'pe&ccedil;a'} active in {elseif $tab eq ''} active in {/if} small"
                                        id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input name=prodExiste id="prodExiste" type=hidden value="{$prodExiste}">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="codProduto">C&oacute;digo Interno</label>
                                                    <button type="button" class="btnCp" title="Cadastro de Produto"
                                                        onClick="javascript:cadastraProduto('{$id}');">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"
                                                            id="spanBTN"></span>
                                                    </button>
                                                    <input class="form-control input-sm" type="text" readonly
                                                        id="codProduto" name="codProduto"
                                                        placeholder="C&oacute;digo Interno" value={$codProduto}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="codProduto">C&oacute;digo Fabricante</label>
                                                    <input class="form-control input-sm" type="text" id="codFabricante"
                                                        maxlength="60" name="codFabricante"
                                                        placeholder="C&oacute;digo Fabricante"
                                                        onblur="javascript:buscaProduto();" value={$codFabricante}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="codProdutoNota">C&oacute;digo Nota</label>
                                                    <input class="form-control input-sm" type="text" id="codProdutoNota"
                                                        maxlength="60" name="codProdutoNota"
                                                        placeholder="C&oacute;digo Nota" value={$codProdutoNota}>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12 small">
                                                    <label for="Produto">Produto</label>
                                                    <div class="input-group line-formated">
                                                        <input type="text" class="form-control input-sm" maxlength="60"
                                                            id="descProduto" name="descProduto" placeholder="Produto"
                                                            required value="{$descProduto}">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=atendimento_new', 'produto');">
                                                                <span class="glyphicon glyphicon-search"
                                                                    aria-hidden="true"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-sm-12 col-xs-12 small">
                                                    <label for="uniProduto">Unidade</label>
                                                    <input class="form-control input-sm" type="text" id="uniProduto"
                                                        maxlength="3" name="uniProduto" placeholder="ex.: UN"
                                                        alt="Unidade" value={$uniProduto}>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="quantidadePecas">Quantidade</label>
                                                    <input class="form-control input-sm money" type="money"
                                                        id="quantidadePecas" name="quantidadePecas"
                                                        placeholder="Quantidade" alt="Quantidade"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$quantidadePecas}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="vlrUnitarioPecas">Valor Unit&aacute;rio</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="vlrUnitarioPecas" name="vlrUnitarioPecas"
                                                        placeholder="Valor Unit&aacute;rio" alt="Valor Unit&aacute;rio"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$vlrUnitarioPecas}>
                                                </div>
                                                <div class="col-md-1 col-sm-12 col-xs-12 small">
                                                    <label for="percDescontoPecas">% Desconto</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="percDescontoPecas" name="percDescontoPecas" placeholder="%"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$percDescontoPecas}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="vlrDescontoPecas">Valor Desconto</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="vlrDescontoPecas" name="vlrDescontoPecas"
                                                        placeholder="Valor de Desconto"
                                                        onchange="javascript:calculaTotalItens('desconto', 'pecas')"
                                                        value={$vlrDescontoPecas}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 small">
                                                    <label for="totalPecas">T O T A L</label>
                                                    <input class="form-control input-sm" readonly type="text"
                                                        id="totalPecas" name="totalPecas" placeholder="0,00"
                                                        value={$totalPecas}>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <label style="visibility:hidden">btn</label>
                                                    <button type="button" style="width: 100%; margin-top:-1px;"
                                                        class="btn btn-success btn-sm"
                                                        onClick="javascript:submitConfirmarPecas();">
                                                        <span class="glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><span> Confirmar</span></button>
                                                </div>
                                                <div class="col-md-1 col-sm-12 col-xs-12">
                                                    <label style="visibility:hidden">btn</label>
                                                    <button type="button" style="width: 100%; margin-top:-1px;"
                                                        class="btn btn-warning btn-sm"
                                                        onClick="javascript:limpaCamposPeca();">
                                                        <span>Limpar</span></button>
                                                </div>
                                            </div>
                                            </p>

                                        </div>

                                        <table id="datatable-buttons-pecas" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                    <th>C&oacute;d. Interno</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th>C&oacute;d. Nota</th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>Unidade</th>
                                                    <th>Loc.</th>
                                                    <th>Quantidade</th>
                                                    <th>Valor Unit&aacute;rio</th>
                                                    <th>% Desconto</th>
                                                    <th>Valor Desconto</th>
                                                    <th>TOTAL</th>
                                                    <th style="width:120px;">Op&ccedil;&otilde;es</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$lancPesq}
                                                    <tr>
                                                        <td hidden class="tab_id"> {{$lancPesq[i].ID}} </td>
                                                        <td class="tab_cod_produto"> {$lancPesq[i].CODPRODUTO} </td>
                                                        <td class="tab_cod_fabricante"> {$lancPesq[i].CODFABRICANTE} </td>
                                                        <td class="tab_cod_produto_nota"> {$lancPesq[i].CODPRODUTONOTA}
                                                        </td>
                                                        <td class="tab_descricao"> {$lancPesq[i].DESCRICAO} </td>
                                                        <td class="tab_unidade"> {$lancPesq[i].UNIDADE} </td>
                                                        <td class="tab_localizacao"> {$lancPesq[i].LOCALIZACAO} </td>
                                                        <td class="tab_quantidade">
                                                            {$lancPesq[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                        <td class="tab_valor_unitario">
                                                            {$lancPesq[i].VALORUNITARIO|number_format:2:",":"."} </td>
                                                        <td class="tab_perc_desconto">
                                                            {$lancPesq[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                        <td class="tab_desconto">
                                                            {$lancPesq[i].DESCONTO|number_format:2:",":"."} </td>
                                                        <td class="tab_valor_total">
                                                            {$lancPesq[i].VALORTOTAL|number_format:2:",":"."} </td>
                                                        <td>
                                                            <button {if $lancPesq[i].CODPRODUTO eq 0} disabled
                                                                {/if}type="button" class="btn btn-info btn-xs"
                                                                onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lancPesq[i].CODFABRICANTE}||||{$lancPesq[i].CODPRODUTO}', 'produto');"><span
                                                                    class="glyphicon glyphicon-search"
                                                                    aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                onclick="javascript:editarPeca(this, '{$lancPesq[i].ID}')"><span
                                                                    class="glyphicon glyphicon-pencil"
                                                                    aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger btn-xs"
                                                                onclick="javascript:submitExcluiPeca('{$lancPesq[i].ID}');"><span
                                                                    class="glyphicon glyphicon-remove"
                                                                    aria-hidden="true"></span></button>
                                                        </td>
                                                    </tr>
                                                    <p>
                                                    {/section}
                                            </tbody>
                                        </table>


                                    </div>

                                    <div role="tabpanel"
                                        class="tab-pane fade {if $tab eq 'serviço'} active in {/if} small"
                                        id="tab_content3" aria-labelledby="profile-tab">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="idServico">ID</label>
                                                    <input class="form-control input-sm" type="text" id="idServicos"
                                                        name="idServicos" placeholder="Id Servi&ccedil;o"
                                                        value={$idServicos}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="idServico">C&oacute;d Servi&ccedil;o</label>
                                                    <input class="form-control input-sm" type="text" id="codServico"
                                                        name="codServico" placeholder="C&oacute;d Servi&ccedil;o"
                                                        value={$codServico}>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                                    <label for="servico">Servi&ccedil;o</label>
                                                    <div class="input-group line-formated">
                                                        <input type="text" class="form-control input-sm"
                                                            id="descricaoServico" name="descricaoServico"
                                                            placeholder="Servi&ccedil;o" required
                                                            value="{$descricaoServico}"
                                                            onClick="javascript:transDesc();" readonly>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=cat&form=servico&opcao=pesquisar&origem=atendimento_new', 'servicos');">
                                                                <span class="glyphicon glyphicon-search"
                                                                    aria-hidden="true"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback"
                                                    style="margin-top: 20px;">
                                                    <label for=""></label>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal" data-target="#modalUpdateDesc"
                                                        href="#modalUpdateDesc" onClick="javascript:transDesc();"><span
                                                            class="glyphicon glyphicon-pencil"
                                                            aria-hidden="true"></span></button>
                                                </div>
                                                <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="unidadeServico">Unidade</label>
                                                    <input class="form-control input-sm" type="text" id="unidadeServico"
                                                        name="unidadeServico" placeholder="ex.: UN"
                                                        style="padding: 3px;" alt="Unidade" value={$unidadeServico}>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="quantidadeServico">Quantidade</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="quantidadeServico" name="quantidadeServico"
                                                        placeholder="Quantidade" alt="Quantidade"
                                                        onchange="javascript:calculaTotalItens('', 'servico')"
                                                        value={$quantidadeServico}>
                                                </div>
                                                <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="vlrUnitarioServico">Valor Unit&aacute;rio</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="vlrUnitarioServico" name="vlrUnitarioServico"
                                                        placeholder="Valor Unit&aacute;rio" alt="Valor Unit&aacute;rio"
                                                        onchange="javascript:calculaTotalItens('', 'servico')"
                                                        value={$vlrUnitarioServico}>
                                                </div>
                                                <div class="col-md-4 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="totalServico">T O T A L</label>
                                                    <input class="form-control input-sm" readonly type="text"
                                                        id="totalServico" name="totalServico"
                                                        placeholder="Total serviço" value={$totalServico}>
                                                </div>

                                                <div class="col-md-2 col-sm-12 col-xs-12 has-feedback">
                                                    <label style="visibility:hidden">btn</label>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        style="width: 100%; margin-top:-3px;"
                                                        onclick="javascript:submitConfirmarServicos();">
                                                        <span class="glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><span> Confirmar</span></button>
                                                </div>
                                            </div>


                                        </div>
                                        <table id="datatable-buttons-servicos" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                    <th>C&oacute;d</th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>Unidade</th>
                                                    <th>Quantidade</th>
                                                    <th>Valor Unit&aacute;rio</th>
                                                    <th>TOTAL</th>
                                                    <th>
                                                        <center>Op&ccedil;&otilde;es</center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$lancItens}
                                                    <tr>
                                                        <td> {$lancItens[i].ID} </td>
                                                        <td> {$lancItens[i].DESCSERVICO} </td>
                                                        <td> {$lancItens[i].UNIDADE} </td>
                                                        <td> {$lancItens[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                        <td> {$lancItens[i].VALUNITARIO|number_format:2:",":"."} </td>
                                                        <td> {$lancItens[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                                        <td>
                                                            <center>
                                                                <button type="button" class="btn btn-primary btn-xs"
                                                                    onclick="javascript:editarServico(this, '{$lancItens[i].CAT_SERVICOS_ID}')"><span
                                                                        class="glyphicon glyphicon-pencil"
                                                                        aria-hidden="true"></span></button>
                                                                <button type="button" class="btn btn-danger btn-xs"
                                                                    onclick="javascript:submitExcluiServico('{$lancItens[i].ID}');"><span
                                                                        class="glyphicon glyphicon-remove"
                                                                        aria-hidden="true"></span></button>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    <p>
                                                    {/section}
                                            </tbody>
                                        </table>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content4"
                                        aria-labelledby="equipe-tab">
                                        <div class="form-group line-formated">
                                            <div class="col-lg-6 col-sm-6 col-xs-6 text-left line-formated">
                                                <label>Equipe</label>
                                                <div class="panel panel-default small line-formated">
                                                    <select id="equipeId" name="equipeId" class="form-control input-sm"
                                                        title="Equipe" alt="Equipe">
                                                        <option value="">Selecione uma equipe</option>
                                                        {html_options values=$equipe_ids selected=$equipeId output=$equipe_names}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-xs-6 text-left line-formated">
                                                <label>Usuários da Equipe</label>
                                                <div class="panel panel-default small line-formated">
                                                    <select id="usuario_equipe" name="usuario_equipe[]"
                                                        class="form-control input-sm select2" multiple="multiple"
                                                        title="Usuários da Equipe" alt="Usuários da Equipe">
                                                        {html_options values=$usuario_equipe_ids selected=$usuario_equipe output=$usuario_equipe_names}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- tabpanel -->
                        </div> <!-- panel -->

                    </div> <!-- FIM class="x_panel" -->
                </div> <!-- FIM class="col-md-12 col-sm-12 col-xs-12" -->
                <!-- INCLUDES DE MODAL -->
                {include file="atendimento_produto_altera_modal.tpl"}
                {include file="atendimento_servico_altera_modal.tpl"}
                {include file="atendimento_modal_editar_desc.tpl"}
            </div>
        </form>

    </div> <!-- FIM class="right_col" role="main" -->

    <!-- Modal Help -->
    <div class="modal fade" id="modalHelp" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atualiza produto</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-md-12" style="text-align: center;">
                                <p>Ao atualizar o item será somado a quantidade, e os valores atualizados conforme o
                                    novo valor inserido.
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    {include file="template/form.inc"}

    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>


    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".money").maskMoney({
                decimal: ",",
                thousands: ".",
                allowZero: true,
            });
        });
    </script>




    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript">
        $('input[name="dataConsulta"]').daterangepicker({
                startDate: moment("{$dataIni}", "DD/MM/YYYY"),
                endDate: moment("{$dataFim}", "DD/MM/YYYY"),
                ranges: {
                    'Hoje': [moment(), moment()],
                    'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                    'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                    'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ]
                },
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Confirma',
                    cancelLabel: 'Limpa',
                    fromLabel: 'Início',
                    toLabel: 'Fim',
                    customRangeLabel: 'Calendário',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho',
                        'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                    firstDay: 1
                }

            },
            //funcao para recuperar o valor digirado        
            function(start, end, label) {
                f = document.lancamento;
                f.dataIni.value = start.format('DD/MM/YYYY');
                f.dataFim.value = end.format('DD/MM/YYYY');
            });
    </script>

    <script>
        $(function() {
            $('#prazoEntrega').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });

            $('#dataAbertura').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });


            $('#dataFechamentoEnd').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });
        });
    </script>

    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Configuração do Select2 para múltipla seleção
            $('.select2').select2({
                theme: "classic",
                width: '100%',
                placeholder: "Selecione os usuários"
            });

            // Ativar/desativar conforme seleção de equipe
            $('#equipeId').change(function() {
                $('#usuario_equipe').prop('disabled', $(this).val() === '');
            }).trigger('change');

            $("#condPgto.js-example-basic-single").select2({});

        });
</script>