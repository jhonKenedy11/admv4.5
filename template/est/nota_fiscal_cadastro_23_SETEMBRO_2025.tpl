<style type="text/css">
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    .rowInfos {
        margin-bottom: 14px;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    label {
        font-size: 11px;
    }

    .btnDes {
        pointer-events: none;
        cursor: default;
        text-decoration: none;
    }

    .swal-modal {
        width: 585px !important;
    }

    .btnWarn {
        color: rgb(61, 61, 61) !important;
    }

    .btnRelatorios {
        margin-top: 4px;
        width: 100% !important;
    }

    .dropMenuRel {
        width: 230px;
        right: -84% !important;
        border-radius: 5px;
        background-color: rgb(111 111 111 / 88%);
    }

    .linhaMenu {
        color: aliceblue;
        padding-bottom: 10px;
    }

    .fa-wrench {
        font-size: 18px;
    }

    .titleNotaFiscal {
        width: 180px;
        padding-right: 0px;
    }

    .titleCadastro {
        margin-top: 6px;
        padding-left: 0px;
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="small">

        <div class="page-title">
            <div class="container">
                <div class="row">

                </div>
            </div>

            <h2>
                {if $mensagem neq ''}
                    {if $tipoMsg eq 'sucesso'}
                        <div class="row">
                            <div class="col-lg-12 text-left">
                                <div>
                                    <div class="alert alert-success" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>--Sucesso!</strong>&nbsp;{$mensagem}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {elseif $tipoMsg eq 'alerta'}
                        <div class="row">
                            <div class="col-lg-12 text-left">
                                <div>
                                    <div class="alert alert-danger" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>--Aviso!</strong>&nbsp;{$mensagem}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}
                {/if}
            </h2>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="">
            <input name=form type=hidden value="">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=id type=hidden value={$id}>
            <input name=idnf type=hidden value={$id}>
            <input name=fornecedor type=hidden value={$id}>
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=transportador type=hidden value={$transportador}>
            <input name=letra type=hidden value={$letra}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=centroCusto type=hidden value={$filial_id}>
            <input name=email type=hidden value={$email}>
            <input name="telaOrigem" type=hidden value={$telaOrigem}>
            <input name="totalOriginal" type=hidden value={$totalOriginal}>
            <input name="totalItem" type=hidden value={$totalItem}>
            <input name="t_origem" type=hidden value={$t_origem}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Nota Fiscal - Cadastro
                                {else}
                                    Nota Fiscal - Alteração
                                {/if}
                            </h2>
                            <br>
                            </br>
                            <h2>
                                <div class="form-group">
                                    <div class="col-md-8 col-sm-9 col-xs-12">
                                        <SELECT class="form-control form-control-sm" name="situacaomostra" DISABLED>
                                            {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}</b>
                                        </SELECT>
                                    </div>
                                </div>
                            </h2>

                            {include file="../bib/msg.tpl"}

                            <ul class="nav navbar-right panel_toolbox">

                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" id="btnVoltar"
                                        onClick="javascript:submitVoltar();">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                <li class="dropdown" style="margin-left:15px;">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        {if $subMenu neq "incluir"}
                                            <li>
                                                <button type="button" class="btn btn-dark btn-xs btnRelatorios"
                                                    onClick="javascript:submitCadastroProdutos({$id});"><span>Manutenção de
                                                        Produtos</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-dark btn-xs btnRelatorios"
                                                    onClick="javascript:consultaMovimentoEstoque('movimento_estoque');"><span>
                                                        Recebimento Produto</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-dark btn-xs btnRelatorios"
                                                    onClick="javascript:calculoTributos({$id});"><span> C&aacute;lculo
                                                        Tributos</span></button>
                                            </li>
                                            <li class="linhaMenu">
                                                __________________________________
                                            </li>
                                            <li>
                                                <button type="button" {if $situacao_id neq 'A'} disabled
                                                        title="Nota baixada não habilita essa função"
                                                    style="pointer-events: all;" {/if}
                                                    class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:addParcelasFinanceiro({$id});">
                                                    <span class="btnWarn"> Baixar NF-e e Gerar Financeiro </span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" {if $situacao_id neq 'A'} disabled
                                                        title="Nota baixada não habilita essa função"
                                                    style="pointer-events: all;" {/if}
                                                    class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:submitGerarEspelho();">
                                                    <span class="btnWarn"> Gerar NF-e sem valor fiscal </span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" {if $situacao_id neq 'P'} disabled
                                                        title="Função para nota em processamento" style="pointer-events: all;"
                                                    {/if} class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:submitConsultaRecibo({$id});">
                                                    <span class="btnWarn"> Consulta Recibo </span>
                                                </button>
                                            </li>
                                            {* <li>
                                    <button type="button" class="btn btn-warning btn-xs btnRelatorios"  onClick="javascript:addParcelasFinanceiro({$id});"><span> Parcelas Financeiro </span></button>
                                </li> *}
                                            <li>
                                                <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                    data-toggle="modal" data-target="#modalCarta"><span class="btnWarn">
                                                        Carta Correção NF-e</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                    data-toggle="modal" data-target="#modalEmail"><span class="btnWarn">
                                                        Email XML/DANFE</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:geraDanfe({$id});"><span class="btnWarn"> Gera
                                                        DANFE</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:imprimirCCe({$id});"><span class="btnWarn"> Imprimir
                                                        CC-e</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                    onClick="javascript:submitGerarXML({$id});"><span
                                                        class="btnWarn"> Gerar XML</span></button>
                                            </li>
                                            <li class="linhaMenu">
                                                __________________________________
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-danger btn-xs btnRelatorios"
                                                    data-toggle="modal" data-target="#modalCancela"><span> Cancela
                                                        NF-e</span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="btn btn-danger btn-xs btnRelatorios"
                                                    onClick="javascript:submitExcluir('$id');"><span> Exclui
                                                        NF-e</span></button>
                                            </li>
                                        {/if}
                                    </ul>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                            {include file="nota_fiscal_email_modal.tpl"}

                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-1 col-sm-6 col-xs-6">
                                    <label for="id">Modelo</label>
                                    <input class="form-control input-sm" type="text" readonly id="modelo"
                                        name="modelomostra" value={$modelo}>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6">
                                    <label for="serie">S&eacute;rie</label>
                                    <input class="form-control input-sm" type="number" required="required" maxlength="3"
                                        placeholder="Serie NFe." id="serie" name="serie"
                                        onKeyPress="if(this.value.length==3) return false;" value={$serie}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6 text-left">
                                    <label for="numero">N&uacute;mero</label>
                                    <input class="form-control input-sm" type="number"
                                        onKeyPress="if(this.value.length==11) return false;" placeholder="Numero NFe."
                                        id="numero" name="numero" {if $subMenu eq "cadastrar"} readonly 
                                        {else}
                                        &nbsp;{/if} value={$numero}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="tipo">Tipo Nota</label>
                                    <SELECT class="form-control form-control-sm" name="tipo" required="required">
                                        {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                                    </SELECT>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="emissao">Emiss&atilde;o</label>
                                    <input class="form-control input-sm" type="text" name="emissao" value={$emissao}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="centroCusto">Centro de Custo</label>
                                    <select class="form-control form-control-sm" name=centroCusto>
                                        {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6" name="divTotalNf">
                                    <label for="totalnf">Total</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">R$
                                            </button>
                                        </span>
                                        <input class="form-control input money" type="money" id="totalnf" name="totalnf"
                                            maxlength="9" value={$totalnf}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="nome">Pessoa</label>
                                    <div class="input-group">
                                        <input type="text" required="true" class="form-control input-sm" id="pessoaNome"
                                            name="nome" readonly placeholder="Cliente ou Fornecedor" value="{$nome}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn-sm btn-primary btn-sm"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label for="idNatOp">Natureza Opera&ccedil;&atilde;o</label>
                                    <select class="form-control form-control-sm" name=idNatOp>
                                        {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="x_panel" style="padding: 3px;">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="dados-tab" role="tab"
                                        data-toggle="tab" aria-expanded="true">Dados Nota</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab"
                                        id="transportadora-tab" data-toggle="tab"
                                        aria-expanded="false">Transportador</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="produtos-tab"
                                        data-toggle="tab" aria-expanded="false">Produtos</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content4" role="tab" id="nfe-tab"
                                        data-toggle="tab" aria-expanded="false">Nfe</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                    aria-labelledby="home-tab">


                                    <div class="row rowInfos">
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <label for="formaEmissao">Forma Emiss&atilde;o</label>
                                            <select class="form-control form-control-sm" name=formaEmissao>
                                                {html_options values=$formaEmissao_ids selected=$formaEmissao_id output=$formaEmissao_names}
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="formaPgto">Forma Pagamento</label>
                                            <select class="form-control form-control-sm" name=formaPgto>
                                                {html_options values=$formaPagamento_ids selected=$formaPagamento_id output=$formaPagamento_names}
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <label for="condPgto">Condi&ccedil;&atilde;o Pagamento</label>
                                            <select class="form-control form-control-sm" name=condPgto>
                                                {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="dataSaidaEntrada">Data e hora Sa&iacute;da/Entrada</label>
                                            <input class="form-control" type="text" name="dataSaidaEntrada"
                                                value={$dataSaidaEntrada}>
                                        </div>

                                    </div>

                                    <div class="row rowInfos">
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="finalidadeEmissao">Finalidade Emiss&atilde;o</label>
                                            <select class="form-control form-control-sm" name=finalidadeEmissao>
                                                {html_options values=$finalidadeEmissao_ids selected=$finalidadeEmissao_id output=$finalidadeEmissao_names}
                                            </select>
                                        </div>
                                        <div class="col-md-5 col-sm-6 col-xs-6">
                                            <label for="nfeReferenciada">Nfe Referenciada</label>
                                            <input class="form-control" size="50px" type="text" name="nfeReferenciada"
                                                value={$nfeReferenciada}>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-6">
                                            <label for="genero">Genero</label>
                                            <select class="form-control form-control-sm" name=genero>
                                                {html_options values=$generoDocto_ids selected=$generoDocto_id output=$generoDocto_names}
                                            </select>
                                        </div>
                                        {if $projeto gt 0}
                                            <div class="col-md-4 col-sm-6 col-xs-6 ">
                                                <label for="contrato">Projeto</label>
                                                <select class="form-control form-control-sm" name=contrato>
                                                    {html_options values=$contrato_ids selected=$contrato_id output=$contrato_names}
                                                </select>
                                            </div>
                                        {/if}

                                    </div>

                                    <div class="row rowInfos">
                                        <div class="col-md-4 col-sm-6 col-xs-6">
                                            <label for="frete">Frete</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btnDes" tabindex="-1"
                                                        type="button">R$
                                                    </button>
                                                </span>
                                                <input class="form-control input money" type="money" maxlength="10"
                                                    name="frete" value={$frete}
                                                    onchange="javascript:calculaTotalNfAjax(frete.value, despacessorias.value, seguro.value);">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-6">
                                            <label for="despacessorias">Desp Acessórias</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btnDes" tabindex="-1"
                                                        type="button">R$
                                                    </button>
                                                </span>
                                                <input class="form-control input money" type="money" maxlength="10"
                                                    name="despacessorias" value={$despacessorias}
                                                    onchange="javascript:calculaTotalNfAjax(frete.value, despacessorias.value, seguro.value);">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-6">
                                            <label for="seguro">Seguro</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btnDes" hidden tabindex="-1"
                                                        type="button">R$
                                                    </button>
                                                </span>
                                                <input class="form-control input money" type="money" maxlength="10  "
                                                    name="seguro" value={$seguro}
                                                    onchange="javascript:calculaTotalNfAjax(frete.value, despacessorias.value, seguro.value);">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row rowInfos">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label for="obs">Observa&ccedil;&atilde;o</label>
                                            <div class="panel panel-default">
                                                <textarea class="form-control" placeholder="Digite a observação."
                                                    rows="3" id="obs" name="obs">{$obs}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade small" id="tab_content4"
                                    aria-labelledby="profile-tab">

                                    <div class="form-group">

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="dhRecbto">Data e Hora do Recebimento</label>
                                            <input class="form-control" type="text" maxlength="45"
                                                title="XML <dhRecbto> " id="dhRecbto" name="dhRecbto" value={$dhRecbto}>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="nProt">N&uacute;mero Protocolo</label>
                                            <input class="form-control" type="number" id="nProt" name="nProt"
                                                title="XML <nProt>" onKeyPress="if(this.value.length==15) return false;"
                                                value={$nProt}>
                                        </div>

                                        <div class="col-md-3 col-sm-2 col-xs-2">
                                            <label for="digVal">Digest Value da NF-e processada</label>
                                            <input class="form-control" type="text" maxlength="28" title="XML <digVal>"
                                                id="digVal" name="digVal" value={$digVal}>
                                        </div>


                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="verAplic">Vers&atilde;o do Aplicativo</label>
                                            <input class="form-control" type="text" maxlength="20"
                                                title="XML <verAplic>" id="verAplic" name="verAplic" value={$verAplic}>
                                        </div>

                                        <div class="col-md-1 col-sm-2 col-xs-2">
                                            <label for="origem">Origem</label>
                                            <input class="form-control" type="text" maxlength="3" id="origem"
                                                name="origem" value={$origem}>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="doc">Documento</label>
                                            <input class="form-control" type="number" id="doc" name="doc"
                                                onKeyPress="if(this.value.length==11) return false;" value={$doc}>
                                        </div>

                                    </div>
                                </div> <!-- tabpanel -->

                                <div role="tabpanel" class="tab-pane fade small" id="tab_content3"
                                    aria-labelledby="profile-tab">

                                    <!-- panel tabela dados -->
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                        <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                    <th>C&oacute;digo</th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>NCM</th>
                                                    <th>Uni.</th>
                                                    <th>Qtde</th>
                                                    <th>V. Unit</th>
                                                    <th>Desc.</th>
                                                    <th>Total</th>
                                                    <th>ICMS</th>
                                                    <th>IPI</th>
                                                    <th>Aliq. ICMS</th>
                                                    <th>Aliq. IPI</th>


                                                    {* <th style="width: 110px;">Manuten&ccedil;&atilde;o
                                               {if $opcao eq 'produto'}
                                                   <button type="button" title="Adicionar" class="btn btn-default btn-xs" onclick="javascript:submitExcluirProdutos('{$lanc[i].ID}');"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                               {/if}    
                                            </th> *}

                                                </tr>
                                            </thead>
                                            <tbody>

                                                {section name=i loop=$lancProd}
                                                    {assign var="total" value=$total+1}
                                                    <tr>
                                                        <td> {$lancProd[i].CODPRODUTO} | {$lancProd[i].CODFABRICANTE} </td>
                                                        <td> {$lancProd[i].DESCRICAO} </td>
                                                        <td> {$lancProd[i].NCM} </td>
                                                        <td> {$lancProd[i].UNIDADE} </td>
                                                        <td> {$lancProd[i].QUANT|number_format:1:",":"."} </td>
                                                        <td> {$lancProd[i].UNITARIO|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].DESCONTO|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].TOTAL|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].VALORICMS|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].VALORIPI|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].ALIQICMS|number_format:2:",":"."} </td>
                                                        <td> {$lancProd[i].ALIQIPI|number_format:2:",":"."} </td>
                                                        {* <td >
                                                    {if $opcao eq 'produto'}
                                                    <button type="button" title="Alterar" class="btn btn-primary btn-xs" onclick="javascript:submitAlterarProdutos('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                                    <button type="button" title="Deletar" class="btn btn-danger btn-xs" onclick="javascript:submitExcluirProdutos('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                                    {else if $opcao eq 'devolucao'}
                                                         <button type="button" title="Alterar" class="btn btn-primary btn-xs" onclick="javascript:submitAlterarNfProduto('{$lancProd[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                                         <button type="button" title="Receber" class="btn btn-warning btn-xs" onclick="javascript:submitReceber('{$lanc[i].ID}');"><span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span></button>

                                                    {else}
                                                        <button type="button" title="Receber" class="btn btn-warning btn-xs" onclick="javascript:submitAlterarNfProduto('{$lanc[i].ID}');"><span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span></button>
                                                    {/if}
                                                </td> *}
                                                    </tr>
                                                    <p>
                                                    {/section}

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane fade small" id="tab_content2"
                                    aria-labelledby="profile-tab">
                                    <div class="form-group">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="modFrete">Modalidade Frete</label>
                                            <div class="panel panel-default">
                                                <select name="modFrete" class="form-control form-control-sm"
                                                    onchange="condFornecedor()">
                                                    {html_options values=$modFrete_ids selected=$modFrete_id output=$modFrete_names}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <label for="transpNome">Transportador</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="transpNome"
                                                    name="transpNome" placeholder="Transportador que realiza o frete"
                                                    value="{$transpNome}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"
                                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisartransportador');">
                                                        <span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <label for="placaVeiculo">Placa Veículo</label>
                                            <input class="form-control" type="text" name="placaVeiculo"
                                                value={$placaVeiculo}>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="volEspecie">Volume Esp&eacute;cie</label>
                                            <input class="form-control" type="text" name="volEspecie"
                                                value={$volEspecie}>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <label for="volMarca">Volume Marca</label>
                                            <input class="form-control" type="text" name="volMarca" value={$volMarca}>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="volume">Quantidade de Volumes</label>
                                            <input class="form-control" type="text" name="volume" value={$volume}>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="volPesoLiq">Peso Liquido</label>
                                            <input class="form-control" type="text" name="volPesoLiq"
                                                value={$volPesoLiq}>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <label for="volPesoBruto">Peso Bruto</label>
                                            <input class="form-control" type="text" name="volPesoBruto"
                                                value={$volPesoBruto}>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- tabpanel -->

                    </div> <!-- panel -->


                    <!-- Modal Cancela -->
                    <div class="modal fade" id="modalCancela" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Cancelamento NFe - Justificativa</h4>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control"
                                        placeholder="Digite a justificativa para cancelamento." rows="4"
                                        id="justificativa" name="justificativa">{$justificativa}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        onClick="javascript:cancelaNFE({$id});">Confirma</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Carta -->
                    <div class="modal fade" id="modalCarta" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Carta Correção NFe - Texto</h4>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control"
                                        placeholder="Digite o texto para a carta de carreção." rows="4" id="carta"
                                        name="carta">{$carta}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        onClick="javascript:cartaCNFE({$id});">Confirma</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true,
            precision:{$casasDecimais}
        });
    });
</script>