<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    .invis {
        display: none;
    }

    .checkBox {
        width: 2px;
        padding: 0;
        margin: center;
    }

    #btnEmissaoNf {
        width: 50px;
    }

    #btnFilter {
        font-size: 12px;
    }

    #datatable-buttons th.gerente-col-cliente,
    #datatable-buttons td.gerente-cliente-cell {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle !important;
    }

    #datatable-buttons th.gerente-col-pedido,
    #datatable-buttons td.gerente-pedido-cell {
        width: 160px;
        min-width: 160px;
        max-width: 160px;
        overflow: hidden;
    }

    .gerente-pedido-cell {
        padding: 4px 6px !important;
        vertical-align: middle !important;
    }

    .gerente-pedido-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
        width: 100%;
        max-width: 148px;
    }

    .gerente-pedido-num {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12px;
        line-height: 22px;
    }

    .gerente-pedido-btns {
        flex: 0 0 52px;
        display: flex;
        justify-content: flex-end;
        gap: 2px;
    }

    .gerente-pedido-btns .btn {
        flex: 0 0 24px;
        width: 24px;
        height: 22px;
        padding: 0;
        margin: 0;
        line-height: 20px;
    }

    .gerente-fin-cell,
    .gerente-fiscal-cell {
        white-space: nowrap;
        vertical-align: middle !important;
    }

    .gerente-fin-cell .btn,
    .gerente-fiscal-cell .btn {
        margin: 0 1px;
    }

    .gerente-btn-doc-cupom {
        background-color: #f0ad4e;
        border-color: #eea236;
        color: #fff;
    }

    .gerente-btn-doc-nf {
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }

    #datatable-buttons th.gerente-col-andamento,
    #datatable-buttons td.gerente-fluxo-cell {
        width: 280px;
        min-width: 280px;
        max-width: 280px;
        padding: 4px 4px !important;
        vertical-align: middle !important;
    }

    .gerente-progress {
        display: flex;
        align-items: flex-start;
        width: 100%;
    }

    .gerente-progress-item {
        flex: 1 1 0;
        min-width: 0;
        position: relative;
        text-align: center;
        padding: 0 1px;
    }

    .gerente-progress-item + .gerente-progress-item::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        height: 3px;
        background: #c8c8c8;
        z-index: 0;
        transform: translateX(-50%);
    }

    .gerente-progress-item.done + .gerente-progress-item::before {
        background: #44cc11;
    }

    .gerente-progress-item.done + .gerente-progress-item.active::before {
        background: linear-gradient(90deg, #44cc11 0%, #44cc11 45%, #f0ad4e 55%, #f0ad4e 100%);
    }

    .gerente-progress-item.active + .gerente-progress-item::before {
        background: #c8c8c8;
    }

    .gerente-progress-title {
        font-size: 8px;
        font-weight: 700;
        color: #555;
        line-height: 1.15;
        margin-bottom: 2px;
        padding: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .gerente-progress-node {
        position: relative;
        z-index: 1;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gerente-progress-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #c8c8c8;
        background: #e8e8e8;
        color: #fff;
        line-height: 1;
    }

    .gerente-progress-circle .glyphicon {
        top: 0;
        font-size: 10px;
    }

    .gerente-progress-item.done .gerente-progress-circle {
        border-color: #3db810;
        background: #44cc11;
    }

    .gerente-progress-item.done .gerente-progress-title {
        color: #333;
    }

    .gerente-progress-item.active .gerente-progress-circle {
        border-color: #ec971f;
        background: #f0ad4e;
    }

    .gerente-progress-item.active .gerente-progress-title {
        color: #333;
    }

    .gerente-progress-item.pending .gerente-progress-circle {
        border-color: #bdbdbd;
        background: #9e9e9e;
    }

    .gerente-progress-item.pending .gerente-progress-title {
        color: #999;
    }

    .gerente-progress[title] {
        cursor: help;
    }

    .gerente-col-andamento .glyphicon-info-sign {
        font-size: 11px;
        color: #5bc0de;
        margin-left: 2px;
        cursor: help;
    }

    .gerente-pedidos-titulo .panel_toolbox {
        min-width: auto;
        float: right;
        margin: 5px 0 0;
    }

    .gerente-pedidos-filtros {
        padding: 0 10px 10px;
    }

    .gerente-pedidos-filtros .btn {
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .gerente-pedidos-filtros .gerente-filtro-cliente {
        display: inline-block;
        vertical-align: middle;
        width: 260px;
        margin-left: 4px;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .gerente-pedidos-filtros .gerente-filtro-cliente .select2-container {
        vertical-align: middle;
    }

    #checkBox input[type="checkbox"] {
        cursor: pointer;
    }
</style>
<link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet">
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente_novo.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title gerente-pedidos-titulo">
                        <h2>Gerencia de Pedidos</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <a href="javascript:void(0);" title="Agrupar pedidos"
                                    onclick="javascript:agrupaPedidoModal();">
                                    <i class="fa fa-wrench"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                        class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                    <div class="gerente-pedidos-filtros">
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs"
                            onclick="javascript:submitTodosPedidosDia();">Mostrar Pedidos Dia</button>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs"
                            onclick="javascript:submitTodosPedidosMes();">Mostrar Pedidos Mes</button>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs"
                            onclick="javascript:submitUltimos60Dias();">&Uacute;ltimos 60 Dias</button>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs"
                            onclick="javascript:submitTodosPedidos();">Todos</button>
                        <div class="gerente-filtro-cliente">
                            <select id="clienteFiltro" name="pessoaFiltro" class="form-control input-sm" style="width:100%;">
                                {if $pessoaFiltro neq '' && $nomeClienteFiltro neq ''}
                                    <option value="{$pessoaFiltro}" selected="selected">{$nomeClienteFiltro|escape:'html'}</option>
                                {/if}
                            </select>
                        </div>
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="alert alert-success" role="alert" style="margin-top:8px;">
                                    <strong>--Sucesso!</strong>&nbsp;{$mensagem}
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="alert alert-danger" role="alert" style="margin-top:8px;">
                                    <strong>--Aviso!</strong>&nbsp;{$mensagem}
                                </div>
                            {/if}
                        {/if}
                    </div>

                    <div class="x_content">
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=origem type=hidden value="{$origem}">
                            <input name=opcao type=hidden value="">
                            <input name=id type=hidden value="">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=pedidoAgrupado type=hidden value={$pedidoAgrupado}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=dadosPed type=hidden value={$dadosPed}>
                            <input name=data_history type=hidden value={$data_history}>
                            <input name=tipoDocFiscal type=hidden value="">
                            {include file="pedido_venda_gerente_agrupa_ped_modal.tpl"}
                        </form>
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th id="checkBox">
                                        {if $pessoaFiltro neq ''}
                                            <center>
                                                <input type="checkbox" id="pedidoAgrupaMarcarTodos"
                                                    title="Marcar/desmarcar todos os pedidos do cliente"
                                                    onclick="javascript:toggleMarcarTodosPedidos(this.checked);" />
                                            </center>
                                        {/if}
                                    </th>
                                    <th class="gerente-col-cliente">Cliente</th>
                                    <th class="gerente-col-andamento"
                                        title="Laranja = etapa atual; verde = conclu&iacute;da; cinza = pendente. Passe o mouse na barra ou em cada etapa para ver o que fazer.">
                                        Andamento
                                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                    </th>
                                    <th class="gerente-col-pedido">Pedido</th>
                                    <th>Emiss&atilde;o</th>
                                    <th>Valor</th>
                                    <th class='invis'></th>
                                    <th style="width:130px;">
                                        <center>Financeiro</center>
                                    </th>
                                    <th style="width:90px;">
                                        <center>Fiscal</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].SITUACAO eq 3 or $lanc[i].SITUACAO eq 6 or $lanc[i].SITUACAO eq 13}
                                        {assign var="total" value=$total+1}
                                        <tr>
                                            <td>
                                                <center>
                                                    {if $lanc[i].SITUACAO eq 6}
                                                        <input type="checkBox" name="pedidoChecked" class="pedido-agrupa-check" value="{$lanc[i].ID}" />
                                                    {/if}
                                                </center>
                                            </td>
                                            <td class="gerente-cliente-cell" title="{$lanc[i].NOME|escape:'html'}">{$lanc[i].NOME}</td>
                                            <td class="gerente-fluxo-cell">
                                                {if $lanc[i].SITUACAO eq 13}
                                                    {if $lanc[i].TEM_NOTA_ABERTA || $lanc[i].TEM_NOTA_REJEITADA}
                                                        {assign var="fluxoAtual" value="fiscal"}
                                                    {elseif $lanc[i].TEM_FINANCEIRO}
                                                        {assign var="fluxoAtual" value="encomenda_fin"}
                                                    {else}
                                                        {assign var="fluxoAtual" value="encomenda"}
                                                    {/if}
                                                {elseif $lanc[i].SITUACAO eq 6}
                                                    {if $lanc[i].TEM_NOTA_ABERTA || $lanc[i].TEM_NOTA_REJEITADA}
                                                        {assign var="fluxoAtual" value="fiscal"}
                                                    {elseif $lanc[i].TEM_FINANCEIRO}
                                                        {assign var="fluxoAtual" value="pedido_fin"}
                                                    {else}
                                                        {assign var="fluxoAtual" value="pedido"}
                                                    {/if}
                                                {elseif $lanc[i].SITUACAO eq 3}
                                                    {if $lanc[i].TEM_NOTA_ABERTA || $lanc[i].TEM_NOTA_REJEITADA}
                                                        {assign var="fluxoAtual" value="fiscal"}
                                                    {else}
                                                        {assign var="fluxoAtual" value="emitir"}
                                                    {/if}
                                                {else}
                                                    {assign var="fluxoAtual" value="pedido"}
                                                {/if}
                                                {if $fluxoAtual eq 'encomenda' || $fluxoAtual eq 'encomenda_fin'}
                                                    {assign var="prog1" value="pending"}
                                                {elseif $fluxoAtual eq 'pedido'}
                                                    {assign var="prog1" value="active"}
                                                {else}
                                                    {assign var="prog1" value="done"}
                                                {/if}
                                                {if $fluxoAtual eq 'encomenda'}
                                                    {assign var="prog2" value="active"}
                                                {elseif $fluxoAtual eq 'fin'}
                                                    {assign var="prog2" value="active"}
                                                {elseif $lanc[i].TEM_FINANCEIRO}
                                                    {assign var="prog2" value="done"}
                                                {else}
                                                    {assign var="prog2" value="pending"}
                                                {/if}
                                                {if $fluxoAtual eq 'emitir'}
                                                    {assign var="prog3" value="active"}
                                                {elseif $lanc[i].SITUACAO eq 3}
                                                    {assign var="prog3" value="done"}
                                                {else}
                                                    {assign var="prog3" value="pending"}
                                                {/if}
                                                {if $fluxoAtual eq 'fiscal'}
                                                    {assign var="prog4" value="active"}
                                                {elseif $lanc[i].TEM_NOTA_ABERTA || $lanc[i].TEM_NOTA_REJEITADA}
                                                    {assign var="prog4" value="done"}
                                                {else}
                                                    {assign var="prog4" value="pending"}
                                                {/if}
                                                {if $fluxoAtual eq 'encomenda'}
                                                    {assign var="fluxoTitulo" value="Aguardando entrada de estoque — cadastre o financeiro e aguarde a libera&ccedil;&atilde;o do material."}
                                                {elseif $fluxoAtual eq 'encomenda_fin'}
                                                    {assign var="fluxoTitulo" value="Encomenda com financeiro — aguardando entrada de estoque para liberar confer&ecirc;ncia e baixa."}
                                                {elseif $fluxoAtual eq 'pedido'}
                                                    {assign var="fluxoTitulo" value="Etapa atual: Confer&ecirc;ncia — imprima o romaneio (coluna Pedido) para conferir itens e avan&ccedil;ar o pedido."}
                                                {elseif $fluxoAtual eq 'pedido_fin'}
                                                    {assign var="fluxoTitulo" value="Financeiro j&aacute; cadastrado — imprima o romaneio (coluna Pedido) para avan&ccedil;ar &agrave; emiss&atilde;o da NF."}
                                                {elseif $fluxoAtual eq 'fin'}
                                                    {if $lanc[i].TEM_FINANCEIRO}
                                                        {assign var="fluxoTitulo" value="Etapa atual: Financeiro — j&aacute; h&aacute; lan&ccedil;amentos; revise as parcelas ou avance para a emiss&atilde;o da NF."}
                                                    {else}
                                                        {assign var="fluxoTitulo" value="Etapa atual: Financeiro — cadastre as parcelas em Produtos e/ou Servi&ccedil;os (coluna Financeiro)."}
                                                    {/if}
                                                {elseif $fluxoAtual eq 'emitir'}
                                                    {assign var="fluxoTitulo" value="Etapa atual: Emiss&atilde;o da NF — use Nota fiscal ou Cupom na coluna Fiscal."}
                                                {elseif $lanc[i].TEM_NOTA_REJEITADA}
                                                    {assign var="fluxoTitulo" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal (nota rejeitada — corrija e reenvie pela coluna Fiscal)."}
                                                {elseif $lanc[i].TEM_NOTA_ABERTA}
                                                    {assign var="fluxoTitulo" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal (nota em aberto — conclua ou corrija pela coluna Fiscal)."}
                                                {else}
                                                    {assign var="fluxoTitulo" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal."}
                                                {/if}
                                                {if $prog1 eq 'active'}
                                                    {assign var="tipConferencia" value="Etapa atual: imprima o romaneio na coluna Pedido para conferir itens e avan&ccedil;ar."}
                                                {elseif $prog1 eq 'done'}
                                                    {assign var="tipConferencia" value="Confer&ecirc;ncia conclu&iacute;da (romaneio)."}
                                                {else}
                                                    {assign var="tipConferencia" value="Pendente: confer&ecirc;ncia do pedido via romaneio."}
                                                {/if}
                                                {if $prog2 eq 'active'}
                                                    {if $lanc[i].TEM_FINANCEIRO}
                                                        {assign var="tipFinanceiro" value="Etapa atual: h&aacute; lan&ccedil;amentos financeiros; revise parcelas ou prossiga no fluxo."}
                                                    {else}
                                                        {assign var="tipFinanceiro" value="Etapa atual: cadastre parcelas em Produtos e/ou Servi&ccedil;os."}
                                                    {/if}
                                                {elseif $prog2 eq 'done'}
                                                    {assign var="tipFinanceiro" value="Financeiro cadastrado (parcelas/lan&ccedil;amentos)."}
                                                {else}
                                                    {assign var="tipFinanceiro" value="Pendente: cadastro de parcelas no financeiro."}
                                                {/if}
                                                {if $prog3 eq 'active'}
                                                    {assign var="tipEmissao" value="Etapa atual: pedido liberado para NF-e ou NFC-e (coluna Fiscal)."}
                                                {elseif $prog3 eq 'done'}
                                                    {if $lanc[i].TEM_NOTA_REJEITADA && $lanc[i].TEM_NOTA_ABERTA}
                                                        {assign var="tipEmissao" value="Emiss&atilde;o conclu&iacute;da: h&aacute; nota em aberto e rejeitada cadastrada, sem autoriza&ccedil;&atilde;o da Receita Federal."}
                                                    {elseif $lanc[i].TEM_NOTA_REJEITADA}
                                                        {assign var="tipEmissao" value="Emiss&atilde;o conclu&iacute;da: nota rejeitada j&aacute; cadastrada, sem autoriza&ccedil;&atilde;o da Receita Federal."}
                                                    {elseif $lanc[i].TEM_NOTA_ABERTA}
                                                        {assign var="tipEmissao" value="Emiss&atilde;o conclu&iacute;da: nota em aberto j&aacute; cadastrada, sem autoriza&ccedil;&atilde;o da Receita Federal."}
                                                    {else}
                                                        {assign var="tipEmissao" value="Emiss&atilde;o da NF conclu&iacute;da no sistema."}
                                                    {/if}
                                                {else}
                                                    {assign var="tipEmissao" value="Pendente: emiss&atilde;o da nota ou cupom fiscal."}
                                                {/if}
                                                {if $prog4 eq 'active'}
                                                    {if $lanc[i].TEM_NOTA_REJEITADA}
                                                        {assign var="tipReceita" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal (nota rejeitada — corrija e reenvie pela coluna Fiscal)."}
                                                    {elseif $lanc[i].TEM_NOTA_ABERTA}
                                                        {assign var="tipReceita" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal (nota em aberto — conclua ou corrija pela coluna Fiscal)."}
                                                    {else}
                                                        {assign var="tipReceita" value="Etapa atual: aguardando confirma&ccedil;&atilde;o com a Receita Federal."}
                                                    {/if}
                                                {elseif $prog4 eq 'done'}
                                                    {assign var="tipReceita" value="Aguardando confirma&ccedil;&atilde;o com a Receita Federal."}
                                                {else}
                                                    {assign var="tipReceita" value="Pendente: ap&oacute;s cadastrar a NF, aguardar confirma&ccedil;&atilde;o com a Receita Federal."}
                                                {/if}
                                                <div class="gerente-progress" title="{$fluxoTitulo}">
                                                    <div class="gerente-progress-item {$prog1}" data-toggle="tooltip" data-placement="top"
                                                        title="{$tipConferencia}">
                                                        <div class="gerente-progress-title">Confer&ecirc;ncia</div>
                                                        <div class="gerente-progress-node">
                                                            <span class="gerente-progress-circle">{if $prog1 eq 'done'}<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>{/if}</span>
                                                        </div>
                                                    </div>
                                                    <div class="gerente-progress-item {$prog2}" data-toggle="tooltip" data-placement="top"
                                                        title="{$tipFinanceiro}">
                                                        <div class="gerente-progress-title">Financeiro</div>
                                                        <div class="gerente-progress-node">
                                                            <span class="gerente-progress-circle">{if $prog2 eq 'done'}<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>{/if}</span>
                                                        </div>
                                                    </div>
                                                    <div class="gerente-progress-item {$prog3}" data-toggle="tooltip" data-placement="top"
                                                        title="{$tipEmissao}">
                                                        <div class="gerente-progress-title">Emiss&atilde;o NF</div>
                                                        <div class="gerente-progress-node">
                                                            <span class="gerente-progress-circle">{if $prog3 eq 'done'}<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>{/if}</span>
                                                        </div>
                                                    </div>
                                                    <div class="gerente-progress-item {$prog4}" data-toggle="tooltip" data-placement="top"
                                                        title="{$tipReceita}">
                                                        <div class="gerente-progress-title">Receita</div>
                                                        <div class="gerente-progress-node">
                                                            <span class="gerente-progress-circle">{if $prog4 eq 'done'}<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>{/if}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="gerente-pedido-cell">
                                                <div class="gerente-pedido-wrap">
                                                    <span class="gerente-pedido-num"
                                                        title="Pedido {$lanc[i].PEDIDO}">Ped: {$lanc[i].PEDIDO}</span>
                                                    <span class="gerente-pedido-btns">
                                                        <button type="button" class="btn btn-default btn-xs"
                                                            title="Editar pedido"
                                                            onclick="javascript:submitEditarPedido('{$lanc[i].ID}');">
                                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-xs"
                                                            title="{if $lanc[i].SITUACAO eq 6}Imprimir romaneio e avan&ccedil;ar situa&ccedil;&atilde;o{else}Reimprimir romaneio{/if}"
                                                            onclick="javascript:submitImprime('{$lanc[i].ID}', 'index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}', '{$lanc[i].SITUACAO}');">
                                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>
                                            <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                            <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                            <td class='invis'> {$lanc[i].FRETE|number_format:2:",":"."} |
                                                {$lanc[i].DESPACESSORIAS|number_format:2:",":"."} |
                                                {$lanc[i].DESCONTO|number_format:2:",":"."} | {$lanc[i].CLIENTE} |
                                                {$lanc[i].CONDPG}</td>
                                            <td class="gerente-fin-cell">
                                                <center>
                                                    <button type="button" class="btn btn-info btn-xs"
                                                        title="Financeiro produtos"
                                                        onclick="javascript:submitCadastroFinanceiro('{$lanc[i].ID}');">
                                                        Produtos
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-xs"
                                                        title="Financeiro servi&ccedil;os"
                                                        {if $lanc[i].VALORSERVICOS|number_format:2:",":"." eq "0,00"}disabled{/if}
                                                        onclick="javascript:submitCadastroFinanceiroServico('{$lanc[i].ID}');">
                                                        Servi&ccedil;os
                                                    </button>
                                                </center>
                                            </td>
                                            <td class="gerente-fiscal-cell">
                                                <center>
                                                    {if $lanc[i].SITUACAO eq 13}
                                                        <span class="text-muted small"
                                                            title="NF bloqueada — aguardando entrada de estoque">Encomenda</span>
                                                    {elseif $lanc[i].SERIE eq '65'}
                                                        <button type="button" class="btn btn-xs gerente-btn-doc-cupom"
                                                            title="Emitir cupom fiscal (NFC-e)"
                                                            onclick="javascript:submitCadastroCupom('{$lanc[i].ID}', '{$lanc[i].SITUACAO}');">
                                                            Cupom
                                                        </button>
                                                    {elseif $lanc[i].SERIE eq '55'}
                                                        <button type="button" class="btn btn-xs gerente-btn-doc-nf"
                                                            title="Emitir nota fiscal"
                                                            onclick="javascript:submitCadastro('{$lanc[i].ID}', '{$lanc[i].SITUACAO}');">
                                                            Nota fiscal
                                                        </button>
                                                    {else}
                                                        <button type="button" class="btn btn-xs gerente-btn-doc-nf"
                                                            title="Emitir nota fiscal"
                                                            onclick="javascript:submitCadastro('{$lanc[i].ID}', '{$lanc[i].SITUACAO}');">
                                                            Nota fiscal
                                                        </button>
                                                        <button type="button" class="btn btn-xs gerente-btn-doc-cupom"
                                                            title="Emitir cupom fiscal (NFC-e)"
                                                            onclick="javascript:submitCadastroCupom('{$lanc[i].ID}', '{$lanc[i].SITUACAO}');">
                                                            Cupom
                                                        </button>
                                                    {/if}
                                                </center>
                                            </td>
                                        </tr>
                                    {/if}
                                {/section}
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>

    {include file="template/database.inc"}
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
    $(function () {
        if (typeof $.fn.tooltip === 'function') {
            $('.gerente-progress-item[data-toggle="tooltip"]')
                .tooltip({ container: 'body', trigger: 'hover' });
        }

        var urlCliente = '{$SCRIPT_NAME}?mod=ped&form=pedido_venda_gerente_novo&submenu=pesquisaClienteAjax&opcao=ajax';
        $('#clienteFiltro').select2({
            placeholder: 'buscar cliente (min. 3 caracteres)',
            allowClear: true,
            minimumInputLength: 3,
            width: '100%',
            ajax: {
                dataType: 'json',
                type: 'POST',
                delay: 250,
                url: urlCliente,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data || [] };
                }
            }
        });

        $(document).on('change', '#datatable-buttons input.pedido-agrupa-check', function () {
            var master = document.getElementById('pedidoAgrupaMarcarTodos');
            if (!master) {
                return;
            }
            var checks = document.querySelectorAll('#datatable-buttons input.pedido-agrupa-check');
            if (!checks.length) {
                master.checked = false;
                master.indeterminate = false;
                return;
            }
            var marcados = 0;
            for (var i = 0; i < checks.length; i++) {
                if (checks[i].checked) {
                    marcados++;
                }
            }
            master.checked = marcados === checks.length;
            master.indeterminate = marcados > 0 && marcados < checks.length;
        });
    });
</script>
