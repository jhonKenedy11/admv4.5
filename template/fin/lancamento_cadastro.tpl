<style type="text/css">
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

#sifrao {
    position: absolute;
    top: 55%;
    left: 14%;
    transform: translate(-50%, -50%)
}

#sifraoCol3,
#iconCal {
    position: absolute;
    top: 55%;
    left: 10%;
    transform: translate(-50%, -50%)
}

#datamov {
    padding-right: 0;
}

#docto {
    width: 75px;
    padding: 0;
}

.form-control,
.x_panel {
    border-radius: 5px;
}

.dropMenuRel {
    right: -90% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
    width: 200px;
}

.btnRelatorios {
    width: 96% !important;
    text-align: center;
}

.li_md {
    padding: 5px 0 0 4% !important;
}

.spanMenu {
    color: #443f3f !important;
}

.title-cadastro {
    padding-left: 0;
    width: 100px !important;
}

.title-pedido-servico {
    padding-right: 0;
    width: 319px;
}

.file {
    display: none !important;
}
.descAnexo {
  border-radius: 5px;
    line-height: 30px;
    height: 30px;
    border: 1px solid #A7A7A7;
    padding: 5px;
    box-sizing: border-box;
    font-size: 15px;
    vertical-align: middle;
    width: 300px;
}
.btnSelecionar {
    border-radius: 3px;
    box-sizing: border-box;
    border: none;
    padding: 2px 10px;
    background-color: #4493c7;
    color: #FFF;
    height: 32px;
    font-size: 15px;
    vertical-align: middle;
}
.btnSelecionar:hover{
    background-color: #295c7e;
}
.objectPdf{
    max-width: 200px; 
    max-height: 200px; 
    position: absolute; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%,-50%);"
}
.imgModal{
    max-width: 70em;
}
.btnManutencao{
    text-align: center;
    width: 90% !important;
    position: absolute; 
    bottom: -10px; 
    left: 50%; 
    transform: translateX(-50%);"
}
.tagImg{
    max-width: 200px; 
    max-height: 150px; 
    position: absolute; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%,-50%);"
}
#downloadButton{
    margin-top: -3px !important
}
.descAnexo{
    width: 40em;
    height: 32px;
    margin-left: -3px;
}
.swal-button--btn_excluir{
    background: #c54d4d !important;
}
.swal-button--btn_excluir:hover{
    background: #e45b5b !important;
}
.divSelecao{
    margin-bottom: 20px; 
    margin-top:12px;
}
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>



{if $subMenu neq "cadastrar" || $clonar eq "true" }

    <body onload="tipoLancamento()">
    {/if}

    <!-- page content -->
    <div class="right_col" style="padding: 5px;" role="main">
        <div class="">

            <div class="clearfix"></div>
            <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
                ACTION="{$SCRIPT_NAME}" METHOD="post" enctype="multipart/form-data">
                <input name=mod type=hidden value="fin">
                <input name=form type=hidden value="lancamento">
                <input name=submenu type=hidden value={$subMenu}>
                <input name=letra type=hidden value={$letra}>
                <input name=opcao type=hidden value="">
                <input name=id type=hidden value={$id}>
                <input name=quantparc type=hidden value="">
                <input name=atividade type=hidden value="">
                <input name=fornecedor type=hidden value={$pessoa}>
                <input name=pessoa type=hidden value={$pessoa}>
                <input name=genero type=hidden value={$genero}>
                <input name=tipolancamento type=hidden value={$tipolancamento}>
                <input name=sitlancAnt type=hidden value={$sitlancAnt}>
                <input name=rateioCC type=hidden value={$rateioCC}>
                <input name=centrocusto type=hidden value={$centrocusto}>
                <input name=clonar type=hidden value={$clonar}>
                <input name=idAnexo type=hidden value={$idAnexo}>
                

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            {include file="../bib/msg.tpl"}
                            <div class="x_title">
                                <div class="">
                                    <div class="col-md-10 title-cadastro">
                                        <h2>
                                                {if $clonar == 'true'}
                                                    Lan&ccedil;amentos Financeiros - Cópia
                                                {elseif $subMenu eq "cadastrar"}
                                                    Lan&ccedil;amentos Financeiros - Cadastro
                                                {else}
                                                    Lan&ccedil;amentos Financeiros - Altera&ccedil;&atilde;o
                                                {/if}
                                            </h2>
                                    </div>
                                </div>
                                {* <div class="title_left">
                        <h3>Lan&ccedil;amentos Financeiros</h3>
                    </div>
                    <h2>
                        {if $clonar == 'true'}
                            Cópia 
                        {elseif $subMenu eq "cadastrar"}
                            Cadastro
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                    </h2> *}
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><button type="button" class="btn btn-primary"
                                            onClick="javascript:submitConfirmar('');">
                                            <span class="glyphicon glyphicon-floppy-save"
                                                aria-hidden="true"></span><span> Confirmar</span></button>
                                    </li>
                                    <li><button type="button" class="btn btn-danger"
                                            onClick="javascript:submitVoltar('');">
                                            <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                                Voltar</span></button>
                                    </li>
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                            aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <ul class="dropdown-menu dropMenuRel" role="menu">
                                            {if $subMenu neq "cadastrar"}
                                                <li class="li_md">
                                                    <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                        onClick="javascript:submitParcela({$id})">
                                                        <span class="spanMenu">Acrescentar parcelas</span>
                                                    </button>
                                                </li>
                                                <li class="li_md">
                                                    <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                        onClick="javascript:submitMassa({$id})">
                                                        <span class="spanMenu">Lançamento em lote</span></button>
                                                </li>
                                                <li class="li_md">
                                                    <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                        onClick="javascript:reenviaCobranca({$id});">
                                                        <span class="spanMenu">Reenviar Cobrança Bancária</span></button>
                                                </li>
                                            {/if}
                                            {if $clonar !== 'true'}
                                                <li class="li_md">
                                                    <button type="button" class="btn btn-warning btn-xs btnRelatorios"
                                                        onClick="javascript:clonarFinanceiro({$id});">
                                                        <span class="spanMenu">Clonar financeiro</span></button>
                                                </li>
                                            {/if}
                                        </ul>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <div class="form-group">
                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <label for="genero">G&ecirc;nero</label>
                                        <div class="input-group">
                                            <input type="text" readonly class="form-control" id="descgenero"
                                                name="descgenero" placeholder="Genero" required="required"
                                                value="{$descGenero}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <label for="nome">Pessoa</label>
                                        <div class="input-group">
                                            <input type="text" readonly class="form-control" id="nome" name="nome"
                                                placeholder="Conta" required="required" value="{$pessoaNome}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div id="divDescTipo" class="" align="center">
                                            <b><label id="descTipo"></label></b>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <div class="col-md-3 col-sm-12 col-xs-12  has-feedback">
                                        <label for="datavenc">Data Vencimento:</label>
                                        <input class="form-control has-feedback-left" type="text" id="datavenc"
                                            name="datavenc" required="required" value={$datavenc}>
                                        <span class="fa fa-calendar-o form-control-feedback left" id="iconCal"
                                            aria-hidden="true"></span>
                                    </div>

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <label for="modo">Modo Pag/Rec</label>
                                        <select class="form-control" name="modo" id="modo">
                                            {html_options values=$modo_ids selected=$modo_id output=$modo_names}
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <label for="conta">Conta Banc&aacute;ria</label>
                                        <select class="form-control" name="conta" id="conta">
                                            {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <label for="tipodocto">Tipo Documento</label>
                                        <select class="form-control" name="tipodocto" id="tipodocto">
                                            {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                        <label for="original">Valor Original:</label>

                                        <input class="form-control money has-feedback-left" type="money" id="original"
                                            name="original" required="required" onchange="javascript:calculaTotal();"
                                            value={$original} maxlength="14">
                                        <span class="form-control-feedback left" aria-hidden="true"
                                            id="sifraoCol3"><b>R$</b></span>
                                    </div>

                                </div>

                                <div class="clearfix"></div>

                                <div class="form-group">

                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <label for="centrocusto">Centro de Custo</label>
                                        <select class="form-control" name=centrocusto id="centrocusto">
                                            {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                        </select>
                                    </div>

                                    <div class="col-md-7 col-sm-6 col-xs-6">
                                        <label for="obs">Observa&ccedil;&atilde;o</label>
                                        <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3"
                                            onload="javascript:tipoLancamento();">{$obs}</textarea>
                                    </div>

                                </div>
                                <div class="form-group">

                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <label for="situacaolancamento">Situa&ccedil;&atilde;o</label>
                                        <select class="form-control" name="situacaolancamento" id="situacaolancamento">
                                            {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-12 col-xs-12  has-feedback">
                                        <label for="datamov">Data Movimento:</label>
                                        <input class="form-control has-feedback-left" type="text" id="datamov"
                                            name="datamov" required="required" value={$datamov}>
                                        <span class="fa fa-calendar-o form-control-feedback left" id="iconCal"
                                            aria-hidden="true"></span>
                                    </div>

                                    <div class="col-md-2 col-sm-4 col-xs-4">
                                        <label for="cheque">N&uacute;mero Cheque</label>
                                        <input class="form-control" id="cheque" type="number" name="cheque"
                                            onKeyPress="if(this.value.length==10) return false;"
                                            placeholder="Num. Cheque" value={$cheque}>
                                    </div>

                                    <div class="col-md-5 col-sm-6 col-xs-6">
                                        <label for="doctobancario">C&oacute;digo Barras</label>
                                        <input class="form-control" maxlength="40" id="doctobancario" type="text"
                                            name="doctobancario" placeholder="C&oacute;digo Barras"
                                            value={$doctobancario}>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="col-md-2 col-sm-6 col-xs-6 has-feedback">
                                        <label for="multa">Valor Multa</label>
                                        <input class="form-control money has-feedback-left" maxlength="11" type="money"
                                            id="multa" name="multa" required="required" value={$multa}
                                            onblur="calculaTotal();">
                                        <span class="form-control-feedback left" aria-hidden="true"
                                            id="sifrao"><b>R$</b></span>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-6 has-feedback">
                                        <label for="juros">Valor Juros</label>
                                        <input class="form-control money has-feedback-left" maxlength="11" type="money"
                                            id="juros" name="juros" value={$juros}
                                            onchange="javascript:calculaTotal();">
                                        <span class="form-control-feedback left" aria-hidden="true"
                                            id="sifrao"><b>R$</b></span>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-6 has-feedback">
                                        <label for="adiantamento">Valor Adiantamento</label>
                                        <input class="form-control money has-feedback-left" maxlength="14" type="money"
                                            id="adiantamento" name="adiantamento" value={$adiantamento}
                                            onchange="javascript:calculaTotal();">
                                        <span class="form-control-feedback left" aria-hidden="true"
                                            id="sifrao"><b>R$</b></span>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-6 has-feedback">
                                        <label for="desconto">Valor Desconto</label>
                                        <input class="form-control money has-feedback-left" maxlength="11" type="money"
                                            id="desconto" name="desconto" value={$desconto}
                                            onchange="javascript:calculaTotal();">
                                        <span class="form-control-feedback left" aria-hidden="true"
                                            id="sifrao"><b>R$</b></span>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6 col-md-offset-1 has-feedback"><b>
                                            <label for="total">Valor TOTAL</label>
                                            <input class="form-control has-feedback-left" type="text" id="total"
                                                name="total" value={$total}>
                                            <span class="form-control-feedback left" aria-hidden="true"
                                                id="sifraoCol3"><b>R$</b></span></b>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="dados-tab" role="tab"
                                        data-toggle="tab" aria-expanded="true">Dados Nota</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="rateio-tab"
                                        data-toggle="tab" aria-expanded="true">Rateio</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab"
                                        id="dados-bancario-tab" data-toggle="tab" aria-expanded="true">Dados
                                        bancários</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content4" role="tab"
                                        id="titulos-agrupados-tab" data-toggle="tab" aria-expanded="true">Titulos
                                        Agrupados</a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content5" role="tab"
                                        id="titulos-agrupados-tab" data-toggle="tab" aria-expanded="true">Anexos</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                    aria-labelledby="home-tab">
                                    <div class="panel-body" style="padding: 4px;">
                                        <div class="x_panel">
                                            <div class="form-group">
                                                <div class="col-md-1 col-sm-4 col-xs-4">
                                                    <label for="docto">Documento</label>
                                                    <input class="form-control" id="docto" name="docto" type="number"
                                                        onKeyPress="if(this.value.length==10) return false;"
                                                        placeholder="Documento" value={$docto}>
                                                </div>

                                                <div class="col-md-1 col-sm-2 col-xs-2">
                                                    <label for="serie">S&eacute;rie</label>
                                                    <input class="form-control" maxlength="3" type="text" id="serie"
                                                        name="serie" placeholder="S&eacute;rie" value={$serie}>
                                                </div>

                                                <div class="col-md-1 col-sm-2 col-xs-2">
                                                    <label for="parcela">Parcela</label>
                                                    <input class="form-control" maxlength="3" type="text" id="parcela"
                                                        name="parcela" placeholder="Parcela" value={$parcela}>
                                                </div>
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <label for="situacaodocto">Situa&ccedil;&atilde;o Documento</label>
                                                    <select class="form-control" name="situacaodocto"
                                                        id="situacaodocto">
                                                        {html_options values=$situacaoDocto_ids selected=$situacaoDocto_id output=$situacaoDocto_names}
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <label for="obscontabil">Observa&ccedil;&atilde;o Recibo</label>
                                                    <textarea class="resizable_textarea form-control" id="obscontabil"
                                                        name="obscontabil" rows="3">{$obscontabil}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-3 col-sm-2 col-xs-2">
                                                    <label for="datalanc">Data Lan&ccedil;amento</label>
                                                    <input class="form-control" type="text" id="datalanc"
                                                        name="datalanc" required="required" readonly value={$datalanc}>
                                                </div>

                                                <div class="col-md-3 col-sm-2 col-xs-2">
                                                    <label for="dataemissao">Data Emiss&atilde;o</label>
                                                    <input class="form-control" type="text" id="dataemissao"
                                                        name="dataemissao" required="required" value={$dataemissao}>
                                                </div>


                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <label for="obsped">Observação Nota/Pedido</label>
                                                    <textarea class="resizable_textarea form-control" readonly
                                                        id="obsped" name="obsped" rows="3">{$obsped}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade small" id="tab_content3"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" style="padding: 4px;">
                                        <div class="x_panel">
                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="docto">Nosso Número</label>
                                                    <input class="form-control" type="text" id="nossonumero" readonly
                                                        name="nossonumero" value={$nossonumero}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="remessaNum">Número da Remessa</label>
                                                    <input class="form-control" type="text" id="remessanum"
                                                        name="remessanum" value={$remessanum}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="remessaData">Data Remessa</label>
                                                    <input class="form-control" type="text" id="remessadata"
                                                        name="remessadata" value={$remessadata}>
                                                </div>

                                                <div class="col-md-4 col-sm-3 col-xs-3">
                                                    <label for="remessaarq">Arquivo Remessa</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" id="remessaarq"
                                                            name="remessaarq" value={$remessaarq}>
                                                        <span class="input-group-btn">

                                                            {if $nomeArq neq ''}
                                                                <button type="button" class="btn btn-primary">
                                                                    <a href="{$arquivo}" download>
                                                                        <span class="glyphicon glyphicon-download-alt"
                                                                            aria-hidden="true">
                                                                        </span>
                                                                    </a>
                                                                </button>
                                                            {/if}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="retornoarq">Arquivo Retorno</label>
                                                    <input class="form-control" type="text" id="retornoarq"
                                                        name="retornoarq" value={$retornoarq}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="retornocod">Cód Retorno</label>
                                                    <input class="form-control" type="text" id="retornocod"
                                                        name="retornocod" value={$retornocod}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="retornocodrejeicao">Retorno Cód Rejeição</label>
                                                    <input class="form-control" type="text" id="retornocodrejeicao"
                                                        name="retornocodrejeicao" value={$retornocodrejeicao}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="retornocodbaixa">Retorno Cód Rejeição</label>
                                                    <input class="form-control" type="text" id="retornocodbaixa"
                                                        name="retornocodbaixa" value={$retornocodbaixa}>
                                                </div>

                                                <div class="col-md-2 col-sm-3 col-xs-3">
                                                    <label for="retornodataliq">Retorno Cód Rejeição</label>
                                                    <input class="form-control" type="text" id="retornodataliq"
                                                        name="retornodataliq" value={$retornodataliq}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade small" id="tab_content2"
                                    aria-labelledby="profile-tab">
                                    <div class="panel-body" style="padding: 4px;">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:submitSalvaRateio('');">Salvar Rateio</button>
                                        <div class="x_panel">
                                            <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                                <thead>
                                                    <tr style="background: gray; color: white;">
                                                        <th width="30%">Centro Custo</th>
                                                        <th width="40%">Descrição</th>
                                                        <th width="20%" class="text-center"> % </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {section name=i loop=$rateioCC}
                                                        <tr>
                                                            <td name="centrocusto"> {$rateioCC[i].CENTROCUSTO} </td>
                                                            <td name="descricao"> {$rateioCC[i].DESCRICAO} </td>
                                                            <td name="percentual">
                                                                <input class="form-control text-right" type="text" id="perc"
                                                                    name="perc{$rateioCC[i].CENTROCUSTO}"
                                                                    value={$rateioCC[i].PERCENTUAL|number_format:2:",":"."}
                                                                    onchange="javascript:validaPercentual();">
                                                            </td>
                                                        </tr>
                                                        <p>
                                                        {/section}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade small" id="tab_content4"
                                    aria-labelledby="titulos-agrupados-tab">
                                    <div class="panel-body" style="padding: 4px;">
                                        <div class="x_panel">
                                            <table id="datatable-agrupados"
                                                class="table table-bordered jambo_table col-md-8">
                                                <thead>
                                                    <tr style="background: gray; color: white;">
                                                        <th>Docto</th>
                                                        <th>Serie</th>
                                                        <th>Origem</th>
                                                        <th>Parcela</th>
                                                        <th>Emissão</th>
                                                        <th>Data Vencimento</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {section name=i loop=$agrupados}
                                                        <tr>
                                                            <td name="centrocusto"> {$agrupados[i].DOCTO} </td>
                                                            <td name="descricao"> {$agrupados[i].SERIE} </td>
                                                            <td name="descricao"> {$agrupados[i].ORIGEM} </td>
                                                            <td name="descricao"> {$agrupados[i].PARCELA} </td>
                                                            <td name="descricao">
                                                                {$agrupados[i].EMISSAO|date_format:"%e %b, %Y"} </td>
                                                            <td name="descricao">
                                                                {$agrupados[i].VENCIMENTO|date_format:"%e %b, %Y"}</td>
                                                            <td name="descricao">
                                                                {$agrupados[i].TOTAL|number_format:2:",":"."} </td>
                                                        </tr>
                                                        <p>
                                                        {/section}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                 <div role="tabpanel" class="tab-pane fade small" id="tab_content5" aria-labelledby="profile-tab">
                                    <div class="panel-body" style="padding: 4px;">
                        
                                        <div class="x_panel">
                                            <div class="row divSelecao">
                                                <div class="col-md-10 col-sm-6 col-xs-12">
                                                    <input type="button" class="btnSelecionar" value="SELECIONAR" />
                                                    <input type="file" name="file" id="file" class="file" accept="image/*, application/pdf" />
                                                    <input type="text" name="descAnexo" id="descAnexo" class="descAnexo" placeholder="Arquivo" readonly="readonly" />
                                                </div>
                                                
                                                <div class="col-md-2 col-sm-6 col-xs-12" >
                                                    <button type="button" class="btn btn-dark pull-right" onClick="javascript:submitSalvarAnexo({$id});">
                                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Salvar </span>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            
                                            {assign var="contador" value=0}
                                            
                                            {section name=i loop=$lancAnexo}
                                            
                                                {* {if $contador % 6 == 0}
                                                    <div class="row">
                                                {/if} *}
                                            
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 abrirModal" style="max-width: 250px; height: 200px !important; position: relative;">
                                                {if $lancAnexo[i].EXTENSAO == 'JPG'}
                                                    <img src="images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.jpg" class="img-rounded img-responsive tagImg"/>
                                                {elseif $lancAnexo[i].EXTENSAO == 'JPEG'}
                                                    <img src="images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.jpeg" class="img-rounded img-responsive tagImg"/>
                                                {elseif $lancAnexo[i].EXTENSAO == 'PDF'}
                                                    <object data="images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.pdf" type="application/pdf" class="img-responsive objectPdf"
                                                        ></object>
                                                {/if}
                                            
                                                <div class="btnManutencao">
                                                    <button type="button" class="btn-xs btn-danger" onClick="javascript:submitExcluirAnexo({$lancAnexo[i].ID_DOC}, {$lancAnexo[i].ID});">
                                                        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                        <span>Apagar</span>
                                                    </button>

                                                    {if $lancAnexo[i].EXTENSAO == 'JPG'}
                                                        <button type="button" class="btn-xs btn-primary" onclick="openAnexo('images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.jpg')">
                                                            <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                                            <span>Abrir</span>
                                                        </button>
                                                    {elseif $lancAnexo[i].EXTENSAO == 'JPEG'}
                                                        <button type="button" class="btn-xs btn-primary" onclick="openAnexo('images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.jpeg')">
                                                            <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                                            <span>Abrir</span>
                                                        </button>
                                                    {elseif $lancAnexo[i].EXTENSAO == 'PDF'}
                                                        <button type="button" class="btn-xs btn-primary" onclick="openAnexo('images/doc/fin/{$lancAnexo[i].ID_DOC}/{$lancAnexo[i].ID}.pdf')">
                                                            <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                                            <span>Abrir</span>
                                                        </button>
                                                    {/if}
                                                </div>
                                            </div>
                                            
                                            
                                                {assign var="contador" value=($contador+1)}
                                            
                                                {* {if $contador % 6 == 0 or $i == $smarty.section.i.total-1}
                                                    </div>
                                                {/if} *}
                                            
                                            {/section}

                                            </div>

                                        </div> <!-- class="x_panel" -->
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div> <!-- tabpanel -->

                </div>
        </div>


        </form>
    </div>

    {include file="template/form.inc"}

<!-- MODAL IMAGEM --> 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width:100em !important;" role="document">
        <div class="modal-content" style="background: #f2f5f5;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body text-center">
                <img class="imgModal" src="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" id="downloadButton" class="btn btn-success"  onclick="downloadImageAnexo()">Download</a>
            </div>
        </div>
    </div>
</div>

    <!-- /Datatables -->

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

        $(document).ready(function() {
            $('#datavenc').on("input", function() {
                let selectedValue = $(this).val();
                $('#datamov').val(selectedValue);
            });

        });

        $(function() {
            $('#datavenc').daterangepicker({
                singleDatePicker: true,
                calendar_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                        'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });
            $('#datamov').daterangepicker({
                singleDatePicker: true,
                calendar_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                        'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });
            $('#dataemissao').daterangepicker({
                singleDatePicker: true,
                calendar_style: "picker_1",
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
$('.btnSelecionar').on('click', function() {
    $('.file').trigger('click');
});

$('.file').on('change', function() {
    var fileName = $(this)[0].files[0].name;
    $('#descAnexo').val(fileName);
});
</script>
<script>
    $(document).ready(function() {
        // Substitua 'URL_DA_IMAGEM' pelo URL real da imagem
        var imageUrl = 'URL_DA_IMAGEM';

        // Atualize o atributo 'href' do botão de download
        $('#downloadButton').attr('href', imageUrl);

        // Evento para atualizar o botão de download quando a modal for exibida
        $('#myModal').on('show.bs.modal', function() {
            $('#downloadButton').attr('href', imageUrl);
        });
    });
</script>
