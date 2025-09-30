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

    .title-cadastro {
        padding-left: 0;
        margin-top: 11px;
        width: 100px !important;
    }

    .title-pedido-servico {
        padding-right: 0;
        width: 208px;
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

    /* Altera cor de fundo do calendário */
    .daterangepicker {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    /* Altera cor dos botões */
    .daterangepicker .applyBtn {
        background-color: #007bff;
        color: white;
    }

    /* Altera cor das datas selecionadas */
    .daterangepicker td.active {
        background-color: #28a745 !important;
    }

    #obra {
        font-size: dpx;
    }
</style>

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_ps.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 5px 2px 2px 2px;">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="ped">
            <input name=form type=hidden value="pedido_ps">
            <input name=submenu type=hidden value={$subMenu}>
            <div id="idAtendimento">
                <input name=id type=hidden value={$id}>
            </div>
            <div id="divPesquisaProduto">
                <input name=abrePesquisa type=hidden value={$abrePesquisa}>
            </div>
            <input name=os type=hidden value={$os}>
            <input name=idPecas type=hidden value={$idPecas}>
            <input name=catEquipamentoId type=hidden value="{$catEquipamentoId}">
            <input name=letra type=hidden value={$letra}>
            <input name=letra_peca type=hidden value={$letra_peca}>
            <input name=letra_servico type=hidden value={$letra_servico}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=pesq type=hidden value={$pesq}>
            <input name=fornecedor type=hidden value="">
            <input name="pessoa" type="hidden" id="pessoa" value="{$pessoa}">
            <input name=nrItem type=hidden value={$nrItem}>
            <input name=opcao_item type=hidden value={$opcao_item}>
            <input name=centroCusto type=hidden value={$centroCusto}>
            <input name=centroCustoEntrega type=hidden value={$centroCustoEntrega}>
            <input name=endereco_entrega type=hidden value={$endereco_entrega_id}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="">
                                <div class="col-md-2 title-pedido-servico">
                                    <h3 class="title-cadastro_">Pedido Serviço &nbsp;-</h3>
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
                                            <button {if $id eq ''} disabled {/if} id="btnDuplicarPedido" type="button"
                                                class="btn btn-primary btn-xs btnRelatorios"
                                                onClick="javascript:submitDuplicarPedido({$id});">
                                                <span>Duplicar Pedido</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnSimularImpostos" type="button" class="btn btn-primary btn-xs btnRelatorios" 
                                                    onClick="javascript:abrirRelatorioImpostos({$id});">
                                                    <span> Simular Impostos</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnGerarOs" type="button"
                                                class="btn btn-primary btn-xs btnRelatorios"
                                                onClick="javascript:submitGerarOs({$id});">
                                                <span> Gerar OS</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button {if $id eq ''} disabled {/if} id="btnEstornarOs" type="button"
                                                class="btn btn-danger btn-xs btnRelatorios"
                                                onClick="javascript:submitEstornarOs({$id});">
                                                <span> Estornar OS</span>
                                            </button>
                                        </li>
                                        <li style="padding: 5px -15px;">
                                            
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
                                    <input type="text" class="form-control input-sm" id="contato" maxlength="25"
                                        name="contato" placeholder="Contato" required value="{$contato}">
                                </div>


                                <div class="col-lg-3 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Situação</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="situacao" class="form-control input-sm">
                                            {html_options values=$situacao_ids selected=$situacao output=$situacao_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="emissao">Emissao</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Emissao." id="emissao"
                                        data-inputmask="'mask': '99/99/9999'" title="Emissao" alt="Emissao"
                                        name="emissao" value="{$emissao}">
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="prazoEntrega">Prazo Entrega</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Prazo de Entrega."
                                        id="prazoEntrega" data-inputmask="'mask': '99/99/9999'" title="Prazo de Entrega"
                                        alt="Prazo de Entrega" name="prazoEntrega" value="{$prazoEntrega}">
                                </div>
                                <div class="col-lg-2 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Vendedor</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="usrAbertura" class="form-control input-sm" title="Atendente"
                                            alt="Atendente">
                                            {html_options values=$usrAbertura_ids selected=$usrAbertura output=$usrAbertura_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-6 text-left line-formated" id="div_cond_pgto">
                                    <label>Condição de Pagamento</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="condPgto" name="condPgto" class="input-sm form-control"
                                            title="Condição de Pagamento" alt="Condição de Pagamento">
                                            {html_options values=$condPgto_ids selected=$condPgto output=$condPgto_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-6 text-left line-formated" id="div_endereco_entrega_lado" style="display: none;">
                                    <label>Endereço de Entrega</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="endereco_entrega_lado" name="endereco_entrega" class="input-sm form-control" title="Endereço de Entrega"
                                            alt="Endereço de Entrega">
                                            <option value="">Selecione o Endereço de Entrega</option>
                                            {html_options values=$endereco_ids selected=$endereco_entrega_id output=$endereco_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-xs-6 text-left line-formated" id="div_obra" style="display: none;">
                                    <label>Obra</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="obra" name="obra" class="input-sm form-control" title="Obra"
                                            alt="Obra" onchange="carregarResponsaveisTecnicos(this.value)">
                                            <option value="">Selecione a Obra</option>
                                            {html_options values=$obra_ids selected=$obra_id output=$obra_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-xs-6 text-left line-formated" id="div_responsavel_tecnico" style="display: none;">
                                    <label>Responsável Técnico</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="responsavel_tecnico" name="responsavel_tecnico" class="input-sm form-control" title="Responsável Técnico"
                                            alt="Responsável Técnico">
                                            <option value="">Selecione o Responsável Técnico</option>
                                            {html_options values=$responsavel_tecnico_ids selected=$responsavel_tecnico_id output=$responsavel_tecnico_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-lg-6 col-sm-6 col-xs-6 text-left line-formated" id="div_endereco_entrega_baixo" style="display: none;">
                                    <label>Endereço de Entrega</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="endereco_entrega_baixo" name="endereco_entrega_baixo" class="input-sm form-control" title="Endereço de Entrega"
                                            alt="Endereço de Entrega">
                                            <option value="">Selecione o Endereço de Entrega</option>
                                            {html_options values=$endereco_ids selected=$endereco_entrega_id output=$endereco_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="carrega_obra" onClick="javascript:carregarObras('')">
                        </div>
                        <div class="form-group line-formated">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="obs">Observações</label>
                                <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                    rows="2">{$obs}</textarea>
                            </div>
                        </div>
                        <div id="divTotal" class="form-group line-formated">
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="valorPecas">Valor Produto</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>
                                    <input class="form-control input-sm" placeholder="Valor Produtos." id="valorPecas"
                                        name="valorPecas" value="{$valorPecas}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="valorServicos">Valor Serviço</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>
                                    <input class="form-control input-sm" placeholder="Valor Serviço." id="valorServicos"
                                        name="valorServicos" value="{$valorServicos}" readonly>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="Visita">Desp Acessorias</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>
                                    <input class="form-control money input-sm" placeholder="Desp Acessorias."
                                        id="valorDespAcessorias" name="valorDespAcessorias"
                                        value="{$valorDespAcessorias}" onchange="javascript:calculaTotal()">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="Visita">Frete</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>
                                    <input class="form-control money input-sm" placeholder="Valor Frete."
                                        id="valorFrete" name="valorFrete" value="{$valorFrete}"
                                        onchange="javascript:calculaTotal()">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="desconto">Desconto</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>
                                    <input class="form-control input-sm money" placeholder="Desconto."
                                        id="valorDesconto" name="valorDesconto" {if $situacao == 6 or $situacao == 3}
                                        readonly {else} onClick="javascript:guardaValorAnt();"
                                        onchange="javascript:atualizarInfo();" {/if} value="{$valorDesconto}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="total">T O T A L</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-sm not-active" tabindex="-1"
                                            type="button">R$</button>
                                    </span>

                                    <input class="form-control input-sm not-active" tabindex="-1"
                                        placeholder="Total Pedido." id="valorTotal" name="valorTotal"
                                        value="{$valorTotal}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation">
                                    <a href="#tab_content2" id="pecas-tab" role="tab" data-toggle="tab"
                                        aria-expanded="true">Produtos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#tab_content3" id="servicos-tab" role="tab" data-toggle="tab"
                                        aria-expanded="true">Serviços</a>
                                </li>
                                <li {if $os eq '0'} style="display:none" {/if} role="presentation">
                                    <a href="#tab_content4" id="os-tab" role="tab" data-toggle="tab"
                                        aria-expanded="true">OS</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel"
                                    class="tab-pane fade {if $tab eq 'peça'} active in {elseif $tab eq ''} active in {/if} small"
                                    id="tab_content2" aria-labelledby="profile-tab">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div id="formPedidoItem">
                                            <input name=prodExiste id="prodExiste" type=hidden value="{$prodExiste}">
                                            <div class="form-group line-formated">
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="codProduto">Cod Interno</label>
                                                    <button type="button" class="btnCp" title="Cadastro de Produto"
                                                        onClick="javascript:cadastraProduto('{$id}');">
                                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"
                                                            id="spanBTN"></span>
                                                    </button>
                                                    <input class="form-control input-sm" type="text" id="codProduto"
                                                        readonly name="codProduto" placeholder="Cod Interno"
                                                        value={$codProduto}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12">
                                                    <label for="codFabricante">Cod Fabricante</label>
                                                    <input class="form-control input-sm" type="text" id="codFabricante"
                                                        name="codFabricante" placeholder="Codigo Fabricante"
                                                        onblur="javascript:buscaProduto();" value={$codFabricante}>
                                                    <!-- onchange="javascript:submitBuscaProduto('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=pedido_ps')"-->
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="codProdutoNota">Código Nota</label>
                                                    <input class="form-control input-sm" type="text" id="codProdutoNota"
                                                        name="codProdutoNota" placeholder="Código Nota."
                                                        value={$codProdutoNota}>
                                                </div>
                                                <div class="col-md-5 col-sm-12 col-xs-12 small line-formated">
                                                    <label for="Produto">Produto</label>
                                                    <div class="input-group line-formated">
                                                        <input type="text" class="form-control input-sm"
                                                            id="descProduto" name="descProduto" placeholder="Produto"
                                                            required value="{$descProduto}">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-primary btn-sm"
                                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=pedido_ps&idPedido={$id}', 'produto');">
                                                                <span class="glyphicon glyphicon-search"
                                                                    aria-hidden="true"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 small col-sm-12 col-xs-12">
                                                    <label for="uniProduto">Unidade</label>
                                                    <input class="form-control input-sm" type="text" id="uniProduto"
                                                        maxlength="3" name="uniProduto" placeholder="Unidade"
                                                        alt="Unidade" value={$uniProduto}>
                                                </div>

                                            </div>
                                            <div class="form-group line-formated">
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="quantidadePecas">Quantidade</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="quantidadePecas" name="quantidadePecas"
                                                        placeholder="Quantidade" alt="Quantidade"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$quantidadePecas}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="vlrUnitarioPecas">Valor Unitário</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="vlrUnitarioPecas" name="vlrUnitarioPecas"
                                                        placeholder="Valor Unitário" alt="Valor Unitário"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$vlrUnitarioPecas}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="percDescontoPecas">% Desconto</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="percDescontoPecas" name="percDescontoPecas"
                                                        placeholder="% de Desconto"
                                                        onchange="javascript:calculaTotalItens('', 'pecas')"
                                                        value={$percDescontoPecas}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="vlrDescontoPecas">Valor Desconto</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="vlrDescontoPecas" name="vlrDescontoPecas"
                                                        placeholder="Valor de Desconto"
                                                        onchange="javascript:calculaTotalItens('desconto', 'pecas')"
                                                        value={$vlrDescontoPecas}>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="totalPecas">T O T A L</label>
                                                    <input class="form-control input-sm money" readonly type="text"
                                                        id="totalPecas" name="totalPecas" placeholder="0,00"
                                                        value={$totalPecas}>
                                                </div>
                                                <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                                    <label style="visibility:hidden">btn</label>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onClick="javascript:submitConfirmarPecas('{$lancPesq[i].NRITEM}');">
                                                        <span class="glyphicon glyphicon-plus"
                                                            aria-hidden="true"></span><span>
                                                            Confirmar</span></button>
                                                </div>
                                            </div>
                                        </div> <!-- FIM DIV formPedidoItem-->


                                    </div>
                                    <table id="datatable-buttons-pecas" class="table table-bordered jambo_table">
                                        <thead>
                                            <tr style="background: gray; color: white;">
                                                <th>Cód Interno</th>
                                                <th>Cód Fabricante</th>
                                                <th>Cód Nota</th>
                                                <th>Descrição</th>
                                                <th>Quantidade</th>
                                                <th>Valor Unitário</th>
                                                <th>Valor Desconto</th>
                                                <th>% Desconto</th>
                                                <th>TOTAL</th>
                                                <th style="width:120px;">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=i loop=$lancPesq}
                                                <tr>
                                                    <td hidden class="i_nr_item"> {$lancPesq[i].NRITEM} </td>
                                                    <td class="i_item_estoque"> {$lancPesq[i].ITEMESTOQUE} </td>
                                                    <td class="i_item_fabricante"> {$lancPesq[i].ITEMFABRICANTE} </td>
                                                    <td class="i_codigo_nota"> {$lancPesq[i].CODIGONOTA} </td>
                                                    <td class="i_decricao"> {$lancPesq[i].DESCRICAO} </td>
                                                    <td class="i_qtd_solicitada">
                                                        {$lancPesq[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                    <td class="i_unitario">
                                                        {$lancPesq[i].UNITARIO|number_format:2:",":"."} </td>
                                                    <td class="i_desconto">
                                                        {$lancPesq[i].DESCONTO|number_format:2:",":"."} </td>
                                                    <td class="i_perc_desconto">
                                                        {$lancPesq[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                    <td class="i_total"> {$lancPesq[i].TOTAL|number_format:2:",":"."}
                                                    </td>
                                                    <td>
                                                        <button {if $lancPesq[i].ITEMESTOQUE eq 0} disabled
                                                            {/if}type="button" class="btn btn-info btn-xs"
                                                            onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lancPesq[i].ITEMFABRICANTE}||||{$lancPesq[i].ITEMESTOQUE}', 'produto');"><span
                                                                class="glyphicon glyphicon-search"
                                                                aria-hidden="true"></span></button>
                                                        <button type="button" class="btn btn-primary btn-xs"
                                                            onclick="javascript:editarPeca(this, '{$lancPesq[i].NRITEM}')"><span
                                                                class="glyphicon glyphicon-pencil"
                                                                aria-hidden="true"></span></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            onclick="javascript:submitExcluiPeca('{$lancPesq[i].NRITEM}');"><span
                                                                class="glyphicon glyphicon-remove"
                                                                aria-hidden="true"></span></button>
                                                    </td>
                                                </tr>
                                            {/section}
                                        </tbody>
                                    </table>



                                </div>

                                <div role="tabpanel" class="tab-pane fade {if $tab eq 'serviço'} active in {/if} small"
                                    id="tab_content3" aria-labelledby="profile-tab">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group line-formated">
                                            <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="idServico">ID</label>
                                                <input class="form-control input-sm" type="text" id="idServicos"
                                                    name="idServicos" placeholder="Id Serviço" value={$idServicos}>
                                            </div>
                                            <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="idServico">Cód Serviço</label>
                                                <input class="form-control input-sm" type="text" id="codServico"
                                                    name="codServico" placeholder="Cód Serviço" value={$codServico}>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                                <label for="servico">Serviço</label>
                                                <div class="input-group line-formated">
                                                    <input type="text" class="form-control input-sm"
                                                        id="descricaoServico" name="descricaoServico"
                                                        placeholder="Serviço" required value="{$descricaoServico}"
                                                        readonly>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=cat&form=servico&opcao=pesquisar&origem=pedido_ps', 'servicos');">
                                                            <span class="glyphicon glyphicon-search"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="unidadeServico">Unidade</label>
                                                <input class="form-control input-sm" type="text" id="unidadeServico"
                                                    name="unidadeServico" placeholder="Unidade" alt="Unidade"
                                                    value={$unidadeServico}>
                                            </div>

                                        </div>
                                        <div class="form-group line-formated">
                                            <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="quantidadeServico">Quantidade</label>
                                                <input class="form-control input-sm money" type="text"
                                                    id="quantidadeServico" name="quantidadeServico"
                                                    placeholder="Quantidade" alt="Quantidade"
                                                    onchange="javascript:calculaTotalItens('', 'servico')"
                                                    value={$quantidadeServico}>
                                            </div>
                                            <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="vlrUnitarioServico">Valor Unitário</label>
                                                <input class="form-control input-sm money" type="text"
                                                    id="vlrUnitarioServico" name="vlrUnitarioServico"
                                                    placeholder="Valor Unitário" alt="Valor Unitário"
                                                    onchange="javascript:calculaTotalItens('', 'servico')"
                                                    value={$vlrUnitarioServico}>
                                            </div>
                                            <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                                                <label for="totalServico">T O T A L</label>
                                                <input class="form-control input-sm" readonly type="text"
                                                    id="totalServico" name="totalServico" placeholder="Total Produto"
                                                    value={$totalServico}>
                                            </div>
                                            <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                            </div>
                                            <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                                <label style="visibility:hidden">btn</label>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="javascript:submitConfirmarServicos();">
                                                    <span class="glyphicon glyphicon-plus"
                                                        aria-hidden="true"></span><span> Confirmar</span></button>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 0px;">
                                        <div class="panel panel-default small line-formated">
                                            <div class="panel-heading" style="cursor:pointer; background: #f5f5f5;" data-toggle="collapse" data-target="#collapseInfoAdicional" aria-expanded="false" aria-controls="collapseInfoAdicional">
                                                <span class="glyphicon glyphicon-chevron-down"></span>
                                                <strong>   Mais Informações</strong>
                                            </div>
                                            <div id="collapseInfoAdicional" class="panel-collapse collapse" style="margin-top:0px;">
                                                <div class="panel-body" style="padding: 0px;">
                                                    <textarea class="form-control input-sm" id="obsItemServico" name="obsItemServico" rows="3" placeholder="Observação dos serviços">{$obsItemServico}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="datatable-buttons-servicos" class="table table-bordered jambo_table">
                                        <thead>
                                            <tr style="background: gray; color: white;">
                                                <th>Cód</th>
                                                <th>Descrição</th>
                                                <th>Unidade</th>
                                                <th>Quantidade</th>
                                                <th>Valor Unitário</th>
                                                <th>TOTAL</th>
                                                <th>Opções</th>
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
                                                    <td style="display:none;">{$lancItens[i].OBSSERVICO}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-xs"
                                                            onclick="javascript:editarServico(this, '{$lancItens[i].CAT_SERVICOS_ID}')"><span
                                                                class="glyphicon glyphicon-pencil"
                                                                aria-hidden="true"></span></button>
                                                        <button type="button" class="btn btn-danger btn-xs"
                                                            onclick="javascript:submitExcluiServico('{$lancItens[i].ID}');"><span
                                                                class="glyphicon glyphicon-remove"
                                                                aria-hidden="true"></span></button>
                                                    </td>
                                                </tr>
                                                <p>
                                                {/section}
                                        </tbody>
                                    </table>


                                </div>
                                <div role="tabpanel" class="tab-pane fade {if $tab eq 'os'} active in {/if} small"
                                    id="tab_content4" aria-labelledby="os-tab">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group line-formated">
                                            <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                                <label for="descEquipamento">
                                                    Descrição Equipamento
                                                </label>
                                                <div class="input-group line-formated">
                                                    <input type="text" class="form-control input-sm"
                                                        id="descEquipamento" name="descEquipamento"
                                                        placeholder="Descrição do Equipamento" required
                                                        value="{$descEquipamento}">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=cat&form=equipamento&opcao=pesquisar');">
                                                            <span class="glyphicon glyphicon-search"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-xs-6">
                                                <label for="emissao">Abertura</label>
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <input class="form-control input-sm" placeholder="Data de Abertura."
                                                    id="dataAbertura" data-inputmask="'mask': '99/99/9999'"
                                                    title="Data de Abertura" alt="Data de Abertura" name="dataAbertura"
                                                    value="{$dataAbertura}">
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-xs-6">
                                                <label for="emissao">Fechamento</label>
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <input class="form-control input-sm" placeholder="Data de Fechamento."
                                                    id="dataFechamentoEnd" data-inputmask="'mask': '99/99/9999'"
                                                    title="Data de Fechamento" alt="Data de Fechamento"
                                                    name="dataFechamentoEnd" value="{$dataFechamentoEnd}">
                                            </div>
                                            <div class="col-md-2 col-sm-6 col-xs-6">
                                                <label for="prazoEntrega">Prazo Entrega OS</label>
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                <input class="form-control input-sm" placeholder="Prazo de Entrega Os."
                                                    id="prazoEntregaOs" data-inputmask="'mask': '99/99/9999'"
                                                    title="Prazo de Entrega Os" alt="Prazo de Entrega Os"
                                                    name="prazoEntregaOs" value="{$prazoEntregaOs}">
                                            </div>
                                        </div> <!-- FIM class="form-group" -->
                                        <div class="form-group line-formated">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="obsOs">Observações OS</label>
                                                <textarea class="resizable_textarea form-control input-sm" id="obsOs"
                                                    name="obsOs" rows="2">{$obsOs}</textarea>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="obsServico">Observações Serviço</label>
                                                <textarea class="resizable_textarea form-control input-sm"
                                                    id="obsServicos" name="obsServicos"
                                                    rows="2">{$obsServicos}</textarea>
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
            {include file="pedido_ps_produto_altera_modal.tpl"}
            {include file="pedido_ps_servico_altera_modal.tpl"}
    </div>
    </form>

</div> <!-- FIM class="right_col" role="main" -->

{include file="template/form.inc"}
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
   $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true,
            precision: {$casasDecimais}     
        });
    $(document).bind('DOMSubtreeModified', function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true,
            precision: {$casasDecimais}
        });
    });
</script>
<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>
<!-- daterangepicker -->

<script>
    $(function() {
        $('#emissao').daterangepicker({
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

        $('#prazoEntrega').daterangepicker({
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

        $('#prazoEntregaOs').daterangepicker({
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

        $('#dataAbertura').daterangepicker({
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


        $('#dataFechamentoEnd').daterangepicker({
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

<script>
window.addEventListener('DOMContentLoaded', function() {
    var valorTotal = document.getElementById('valorTotal');
    var valorDesconto = document.getElementById('valorDesconto');
    function check() {
        var v = valorTotal.value.replace(/\./g, '').replace(',', '.');
        valorDesconto.readOnly = (v === '' || isNaN(parseFloat(v)) || parseFloat(v) === 0);
    }
    check();
    valorTotal.addEventListener('input', check);
    valorTotal.addEventListener('change', check);
});
</script>
<script>

// Inicializa os campos quando a página carrega
$(document).ready(function() {
    inicializarCamposObra();
    
    // Adiciona evento para carregar endereços quando o cliente for selecionado
    $('#pessoa').on('change', function() {
        var clienteId = $(this).val();
        carregarEnderecos(clienteId, []);
    });
    
    // Sincroniza os valores dos campos de endereço
    $(document).on('change', '#endereco_entrega_lado', function() {
        $('#endereco_entrega_baixo').val($(this).val());
    });
    
    $(document).on('change', '#endereco_entrega_baixo', function() {
        $('#endereco_entrega_lado').val($(this).val());
    });
});
</script>