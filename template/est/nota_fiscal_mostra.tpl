<style>
    .form-control,
    .c_panel {
        border-radius: 5px;
    }

    #msgRetorno {
        font-size: 12px !important;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        {* <div class="page-title">
              <div class="title_left">
                <h3>Nota Fiscal</h3>
              </div>
            </div> *}

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Nota Fiscal - Consulta
                            <strong>
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success" role="alert" id="msgRetorno">{$mensagem}</div>
                                    {else}
                                        <div class="alert alert-error" role="alert" id="msgRetorno">{$mensagem}</div>
                                    {/if}
                                {/if}
                                <strong>
                                    {if $nomeArq neq ''}
                                        <a href="{$arquivo}" download>Download Carta Carreção -> {$nomeArq}</a>

                                    {/if}
                                </strong>
                            </strong>
                            </p>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            {* Se a tela que chamou for do manifesto fiscal *}
                            {if $from neq 'manifesto_fiscal'}
                                <li>
                                    <button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                            Cadastro</span>
                                    </button>
                                </li>
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <button type="button" class="btn btn-dark btn-xs" data-toggle="modal"
                                                data-target="#modalTeste"><span>Enviar XML's Contabilidade</span></button>

                                        <!--  <button type="button" class="btn btn-dark btn-xs"  onClick="javascript:consultarXMLNFe();">Enviar XML's Contabilidade</button> -->
                                        </li>

                                        <li>
                                            ____________________
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-dark btn-xs"
                                                onClick="javascript:consultaPrint('consolidacao_produtos');"><span>
                                                    Consolida&ccedil;&atilde;o Produtos</span></button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-dark btn-xs"
                                                onClick="javascript:consultaPrint('consolidacao_fiscal');"><span>
                                                    Consolida&ccedil;&atilde;o Fiscal</span></button>
                                        </li>
                                        <!--li>
                                      <button type="button" class="btn btn-dark btn-xs"  onClick="javascript:consultaMovimentoEstoque('movimento_estoque');"><span> Desmostrativo de Movimento de Estoque</span></button>
                                  </li-->
                                        <li>
                                            <button type="button" class="btn btn-dark btn-xs"
                                                onClick="javascript:consultaPrintPeriodo('nota_fiscal_periodo');"><span>
                                                    Vendas no período</span></button>
                                        </li>
                                        <li>
                                            ____________________
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#modalInutiliza"><span> Inutiliza NFe</span></button>

                                            <!-- <button  type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalTeste"><span> Teste NFe</span></button> -->

                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                onClick="javascript:submitDevolucaoNf();"><span> Devolução de
                                                    NF</span></button>
                                        </li>


                                        <li>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                onClick="javascript:submitConsultaDocumentos();"><span> Consulta
                                                    Documentos</span></button>
                                        </li>


                                        <li>
                                            ____________________
                                        </li>
                                        <li><button type="button" class="btn btn-warning btn-xs"
                                                onClick="javascript:limpaDadosForm();">
                                                <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span>
                                                    Limpar Dados Formulário</span>
                                            </button>
                                        </li>

                                    </ul>

                                </li>
                                <li>
                                    <a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            {/if}

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="nota_fiscal">
                            <input name=id type=hidden value="">
                            <input name=idnf type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=fornecedor type=hidden value={$pessoa}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=notas_xml type=hidden value={$notas_xml}>
                            <input name=email type=hidden value={$email}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=devolucaoNotaFiscal type=hidden value={$devolucaoNotaFiscal}>
                            <input name=genero type=hidden value={$genero}>
                            <input name=transportador type=hidden value={$transportador}>
                            <input name=from type=hidden value={$from}>

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>N&uacute;mero NF</label>
                                <input class="form-control" id="numNf" name="numNf"
                                    placeholder="N&uacute;mero da nota fiscal a pesquisar." value={$numNf}>
                            </div>
                            <div class="form-group col-md-2 col-sm-1 col-xs-1">
                                <label>Série</label>
                                <input class="form-control" id="serieNf" name="serieNf"
                                    placeholder="Série da nota fiscal a pesquisar." value={$serieNf}>
                            </div>
                            <div class="form-group col-md-2 col-sm-1 col-xs-1">
                                <label>Modelo</label>
                                <input class="form-control" id="modeloNf" name="modeloNf"
                                    placeholder="Modelo da nota fiscal a pesquisar." value={$modeloNf}>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>

                            </div>

                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label for="idVendedor">Situa&ccedil;&atilde;o</label>
                                <SELECT class="form-control" name="msituacao">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </SELECT>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nome">Pessoa</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Conta"
                                        value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label for="nome">Tipo Nota Fiscal</label>
                                <SELECT class="form-control" name="mtipo">
                                    {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                                </SELECT>
                            </div>
                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label for="pesCidade">Empresa</label>
                                <select class="form-control" name=mfilial>
                                    {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                </select>
                            </div>



                    </div>


                    <!-- Modal Cancela -->
                    <div class="modal fade" id="modalInutiliza" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Inutilização NFe</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="inutModelo">Modelo Nfe</label>
                                                <input class="form-control" min="1" step="1" type="number"
                                                    title="Digite a qtde para este item." id="inutModelo"
                                                    name="inutModelo" value={$inutModelo}>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="inutSerie">Série Nfe</label>
                                                <input class="form-control" min="1" step="1" type="text"
                                                    title="Digite a qtde para este item." id="inutSerie"
                                                    name="inutSerie" value={$inutSerie}>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="inutNumIni">Número Nfe In&iacute;cio</label>
                                                <input class="form-control" min="1" step="1" type="number"
                                                    title="Digite a qtde para este item." id="inutNumIni"
                                                    name="inutNumIni" value={$inutNumIni}>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="inutNumFim">Número Nfe Fim</label>
                                                <input class="form-control" min="1" step="1" type="number"
                                                    title="Digite a qtde para este item." id="inutNumFim"
                                                    name="inutNumFim" value={$inutNumFim}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control"
                                        placeholder="Digite a justificativa para Inutilização, minimo 15 characteres."
                                        rows="4" id="inutJustificativa"
                                        name="inutJustificativa">{$inutJustificativa}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        onClick="javascript:inutilizaNFE();">Confirma</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Modal TESTE -->
                    <div class="modal fade" id="modalTeste" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Para...</h6>
                                    <textarea class="form-control" placeholder="Digite o email." rows="1"
                                        id="emailContador" name="emailContador">{$emailContador}</textarea>
                                    <h6 class="modal-title">Assunto</h6>
                                    <textarea class="form-control" placeholder="Digite o assunto." rows="1"
                                        id="emailTitulo" name="emailTitulo">{$emailTitulo}</textarea>
                                </div>
                                <div class="modal-body">
                                    <textarea class="form-control" placeholder="Digite o corpo do email." rows="4"
                                        id="emailCorpo" name="emailCorpo">{$emailCorpo}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                        onClick="javascript:consultarXMLNFe();">Enviar</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo">
                                <h4 class="panel-title">Filtros <i class="fa fa-chevron-down"></i></h4>
                            </a>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingTwo">
                                <div class="x_panel">
                                    <div class="form-group">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                                            <div class="panel panel-default">
                                                <select id="idNatop" name="idNatop" class="form-control">
                                                    {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="modFrete">Finalidade Emissão</label>
                                            <div class="panel panel-default">
                                                <select name=finalidadeEmissao class="form-control form-control-sm">
                                                    {html_options values=$finalidadeEmissao_ids selected=$finalidadeEmissao_id output=$finalidadeEmissao_names}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="modFrete">Modalidade Frete</label>
                                            <div class="panel panel-default">
                                                <select name="modFrete" class="form-control form-control-sm">
                                                    {html_options values=$modFrete_ids selected=$modFrete_id output=$modFrete_names}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <label for="genero">G&ecirc;nero</label>
                                            <div class="input-group">
                                                <input readonly type="text" class="form-control" id="descgenero"
                                                    name="descgenero" placeholder="Genero" required="required"
                                                    value="{$descGenero}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary"
                                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                                        <span class="glyphicon glyphicon-search"
                                                            aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <label for="transpNome">Transportador</label>
                                            <div class="input-group">
                                                <input readonly type="text" class="form-control" id="transpNome"
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

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                </form>
            </div>

        </div> <!-- div class="x_content" = inicio tabela -->

    </div> <!-- div class="x_panel" = painel principal-->

    <!-- panel tabela dados -->
    <div class="col-md-12 col-xs-12">
        <div class="x_panel small">
            <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                    <tr style="background: #2A3F54; color: white;">
                        {if $from neq 'manifesto_fiscal'}
                            <th>#</th>
                        {/if}
                        <th>ID</th>
                        <th>Emiss&atilde;o</th>
                        <th>Doc</th>
                        <th>Origem</th>
                        <th>NF</th>
                        <th>Filial</th>
                        <th>Pessoa</th>
                        <th>Nat. Opera&ccedil;&atilde;o</th>
                        <th>Tipo</th>
                        <th>Situa&ccedil;&atilde;o</th>
                        <th>Total</th>
                        {* Se a tela que chamou for do manifesto fiscal *}
                        {if $from eq 'manifesto_fiscal'}
                            <th style="width: 30px;">Selecionar</th>
                        {else}
                            <th style="width: 120px;">Manuten&ccedil;&atilde;o</th>
                        {/if}
                    </tr>
                </thead>
                <tbody>

                    {section name=i loop=$lanc}
                        {assign var="total" value=$total+1}
                        <tr>
                            {if $from neq 'manifesto_fiscal'}
                                <td> <input type="checkBox" name="nfChecked" id="{$lanc[i].ID}" /> </td>
                            {/if}
                            <td name="idNF" id="{$lanc[i].ID}"> {$lanc[i].ID} </td>
                            <td> {$lanc[i].EMISSAO|date_format:"%e %b, %Y %H:%M:%S"} </td>
                            <td> {$lanc[i].DOC} </td>
                            <td> {$lanc[i].ORIGEM} </td>
                            <td> {$lanc[i].NUMERO} </td>
                            <td> {$lanc[i].FILIAL} </td>
                            <td> {$lanc[i].NOMEREDUZIDO} </td>
                            <td> {$lanc[i].NATOPERACAO} </td>
                            <td> {$lanc[i].TIPONOTA} </td>
                            <td> {$lanc[i].SITUACAONOTA} </td>
                            <!--<td> {$lanc[i].TOTALNF|number_format:2:",":"."} </td>-->
                            <td> {$lanc[i].TOTALNF} </td>

                            {if $from eq 'manifesto_fiscal'}
                                <td>
                                    <center>
                                        <button type="button" class="btn btn-success btn-xs"
                                            onclick="javascript:fechaPesquisaParamManifesto({$lanc[i].ID}, '{$lanc[i].NUMERO}', '{$lanc[i].NOMEREDUZIDO}','{$lanc[i].DOC}', '{$lanc[i].FILIAL}', '{$lanc[i].ID_MDF}', '{$lanc[i].ORIGEM}');">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        </button>
                                    </center>
                                </td>
                            {else}
                                <td>
                                    <button type="button" title="Alterar" class="btn btn-primary btn-xs"
                                        onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                    <button type="button" title="Deletar" class="btn btn-danger btn-xs"
                                        onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                    <button type="button" title="Produtos" class="btn btn-warning btn-xs"
                                        onclick="javascript:submitCadastroProdutosMostra('{$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-barcode" aria-hidden="true"></span></button>
                                    <!--    {if $lanc[i].TIPONOTA eq '0 - ENTRADA'}
                                            <button type="button" title="Receber Produto" class="btn btn-warning btn-xs" onclick="javascript:submitReceber('{$lanc[i].ID}');"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></button>
                                        {else}    
                                            <button type="button" title="Autoriza NFe" class="btn btn-info btn-xs" onclick="javascript:submitGerarXML('{$lanc[i].ID}');"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                            <button type="button" title="Imprime Danfe" class="btn btn-success btn-xs" onclick="javascript:printDanfe('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                            <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-xs" onclick="javascript:abrir('index.php?mod=est&form=nota_fiscal_imp_etiqueta&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></button>
                                        {/if}    -->
                                    <!--button type="button" title="Imprimir" class="btn btn-warning btn-xs" onclick="javascript:submitImprimir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button-->
                                    <button type="button" class="btn btn-info btn-xs" {if $lanc[i].SITUACAO eq 'P'} disabled
                                        title="Nota fiscal com status em processamento" {else} title="Autoriza NFe" 
                                        {/if}
                                        onclick="javascript:submitGerarXML('{$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                    <button type="button" title="Imprime Danfe" class="btn btn-success btn-xs" target="_blank"
                                        onclick="javascript:printDanfe('{$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                    <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-xs"
                                        onclick="javascript:abrir('index.php?mod=est&form=nota_fiscal_imp_etiqueta&opcao=imprimir&parm={$lanc[i].ID}');"><span
                                            class="glyphicon glyphicon-tag" aria-hidden="true"></span></button>

                                    {$xml_trata1 = $lanc[i].PATHDANFE|replace:"/PDF/":"/ENVIADAS/APROVADAS/"}
                                    {$xml_trata2 = $xml_trata1|replace:"-DANFE.PDF":"-NFE.XML"}
                                    {$xml_path = $xml_trata2|replace:"HTTPS://ADMSISTEMA.COM.BR/": "/"|lower}
                                    <button type="button" title="Download XML" {if $lanc[i].PATHDANFE eq ''} disabled {/if}
                                        class="btn btn-success btn-xs">{if $lanc[i].PATHDANFE neq ''}<a href="{$xml_path}"
                                            download>{/if}<span class="glyphicon glyphicon-list"
                                                aria-hidden="true"></span></button>
                                </td>
                            {/if}
                        </tr>
                    {/section}

                </tbody>
            </table>


        </div> <!-- div class="x_content" = inicio tabela -->
    </div> <!-- div class="x_panel" = painel principal-->
</div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->




{include file="template/database.inc"}

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
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
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