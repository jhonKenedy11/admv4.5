<style>
    .line-formated {
        margin-bottom: 1px;
    }

    .form-control,
    .x_panel {
        border-radius: 3px;
    }

    .table.jambo_table {
        border: 0.5px solid rgba(221, 221, 221, 0.78) !important;
    }

    .legenda {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 5px;
    }
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_pedido.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="page-title" style="margin-left: -1% !important;">
            <div class="title_left">
                <h3>Manutenção - Ordem de Serviço</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="cat">
            <input name=form type=hidden value="atendimento_pedido">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=id type=hidden value={$id}>
            <input name=idPecas type=hidden value={$idPecas}>
            <input name=idServicos type=hidden value="{$idServicos}">
            <input name=catEquipamentoId type=hidden value="{$catEquipamentoId}">
            <input name=letra type=hidden value={$letra}>
            <input name=letra_peca type=hidden value={$letra_peca}>
            <input name=letra_servico type=hidden value={$letra_servico}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=pesq type=hidden value={$pesq}>
            <input name=fornecedor type=hidden value="">
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=situacao type=hidden value={$situacao}>
            <input name=codProduto type=hidden value={$codProduto}>
            <input name=codProdutoNota type=hidden value={$codProdutoNota}>
            <input name=descProduto type=hidden value={$descProduto}>
            <input name=uniProduto type=hidden value={$uniProduto}>
            <input name=quantidadePecas type=hidden value={$quantidadePecas}>
            <input name=vlrUnitarioPecas type=hidden value={$vlrUnitarioPecas}>
            <input name=vlrDescontoPecas type=hidden value={$vlrDescontoPecas}>
            <input name=percDescontoPecas type=hidden value={$percDescontoPecas}>
            <input name=quantidadeServico type=hidden value="{$quantidadeServico}"> <!-- Atendimento -->
            <input name=vlrUnitarioServico type=hidden value="{$vlrUnitarioServico}"> <!-- Atendimento -->
            <input name=itensQtde type=hidden value='0'>
            <input name=dadosPecas type=hidden value=''>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;">
                    <div class="x_panel" style="padding: 8px;">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Cadastro
                                {else}
                                    Altera&ccedil;&atilde;o
                                {/if}
                                {include file="../bib/msg.tpl"}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-book" aria-hidden="true"></span><span> Gerar
                                            pedido</span></button>
                                </li>
                                <li><button type="button" class="btn btn-success"
                                        onClick="javascript:submitConfirmaOrdemCompra('');">
                                        <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span><span>
                                            Gerar Ordem de compra</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span>
                                            Voltar </span></button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li> *}
                                {* <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

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
                                <div class="col-lg-3 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Condição de Pagamento</label>
                                    <div class="panel panel-default small line-formated">
                                        <select id="condPgto" name="condPgto"
                                            class="input-sm js-example-basic-single form-control"
                                            title="Condição de Pagamento" alt="Condição de Pagamento">
                                            {html_options values=$condPgto_ids selected=$condPgto output=$condPgto_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group line-formated" style="margin-bottom: 0;">
                                <div class="col-lg-2 col-sm-2 col-xs-2 text-left line-formated"
                                    style="padding-top: 21px;">
                                    <label>Vendedor</label>
                                    <select name="usrFatura" class="form-control input-sm" title="usrFatura"
                                        alt="usrFatura">
                                        {html_options values=$usrFatura_ids selected=$usrFatura output=$usrFatura_names}
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6" style="padding-top: 21px;">
                                    <label for="prazoEntrega">Emissão</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Emissão." id="emissao"
                                        title="Emissão" alt="Emissão" name="emissao" value="{$emissao}">
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6" style="padding-top: 21px;">
                                    <label for="prazoEntrega">Prazo Entrega</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control input-sm" placeholder="Prazo de Entrega."
                                        id="prazoEntrega" title="Prazo de Entrega" alt="Prazo de Entrega"
                                        name="prazoEntrega" value="{$prazoEntrega}">
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="control-label text-left col-md-2 col-sm-3 col-xs-12" for="id">
                                        Peças Utilizada OS
                                    </label>
                                    <div class="col-md-4 col-sm-6 col-xs-12"
                                        style="margin-bottom: 9px;padding-top: 8px; ">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-sm" type="button">R$</button>
                                            </span>

                                            <input class="form-control input-sm" placeholder="Valor Peças Utilizada."
                                                id="totalPecasUtilizada" name="totalPecasUtilizada"
                                                value="{$totalPecasUtilizada}" readonly>
                                        </div>
                                    </div>

                                    <label class="control-label text-left col-md-2 col-sm-3 col-xs-12" for="id">
                                        Frete
                                    </label>
                                    <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-sm" type="button">R$</button>
                                            </span>

                                            <input class="form-control input-sm money" placeholder="Valor Frete."
                                                id="valorFrete" name="valorFrete" onchange="javascript:calculaTotal()"
                                                value="{$valorFrete}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group line-formated">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obs">Observações</label>
                                    <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                        rows="3">{$obsServicos}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label text-left col-md-2 col-sm-3 col-xs-12" for="id">
                                        Desp. Acessorias
                                    </label>
                                    <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-sm" type="button">R$</button>
                                            </span>

                                            <input class="form-control money input-sm"
                                                placeholder="Valor Desp Acessorias." id="despAcessorias"
                                                name="despAcessorias" onchange="javascript:calculaTotal()"
                                                value="{$despAcessorias}">
                                        </div>
                                    </div>
                                    <label class="control-label text-left col-md-2 col-sm-3 col-xs-12" for="id">
                                        <b>Total</b>
                                    </label>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-sm" type="button">R$</button>
                                            </span>

                                            <input class="form-control input-sm" placeholder="Total Atendimento."
                                                id="valorTotal" name="valorTotal" value="{$valorTotal}" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 small">

                                <table id="datatable-buttons-pecas" class="table table-sm table-bordered jambo_table"
                                    style="border-collapse: inherit !important; border-radius:7px 7px 7px 7px !important;">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th style="padding:5px;width: 28px;border-radius: 6px 0 0 0 !important;">
                                                <input type="checkBox" id="checkAll" name="checkAll"
                                                    title="Marcar todos" onchange="javascript:checkboxAll(this)" />
                                            </th>
                                            <th
                                                style="padding:5px;width: 78px; text-align:center;vertical-align:middle;">
                                                Código</th>
                                            <th
                                                style="padding:5px;width: 78px; text-align:center;vertical-align:middle;">
                                                Cód. Nota</th>
                                            <th style="vertical-align:middle;text-align:center;">Descrição</th>
                                            <th
                                                style="padding:5px;width: 40px; text-align:center;vertical-align:middle;">
                                                Un</th>
                                            <th
                                                style="padding:5px;width: 60px; text-align:center;vertical-align:middle;">
                                                Quant.</th>
                                            <th
                                                style="padding:5px;width: 60px; text-align:center;vertical-align:middle;">
                                                Qtde Utilizada</th>
                                            <th
                                                style="padding:5px;width: 91px; text-align:center;vertical-align:middle;">
                                                Qtde Pedido</th>
                                            <th
                                                style="padding:5px;width: 91px; text-align:center;vertical-align:middle;">
                                                Valor Unitário</th>
                                            <th
                                                style="padding:5px;width: 88px; text-align:center;vertical-align:middle;">
                                                % Desconto</th>
                                            <th
                                                style="padding:5px;width: 88px; text-align:center;vertical-align:middle;">
                                                Valor Desc</th>
                                            <th style="padding:5px;width: 88px;border-radius: 0 6px 0 0 !important;">
                                                TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancPesq}
                                            <tr id="trsitens" {if $lancPesq[i].SITUACAO_OC == '9'}
                                                style="background-color:#cedfc3;" {elseif $lancPesq[i].SITUACAO_OC == '5'}
                                                style="background-color:#f4d4d4;" {/if}>

                                                <td
                                                    style="border-radius: 0 0 0 6px !important;padding:5px;text-align:center;vertical-align:middle;">
                                                    <input type="checkBox" class="checkbox" id="check{$lancPesq[i].ID}"
                                                        name="check{$lancPesq[i].ID}"
                                                        {if $lancPesq[i].OC_ID neq '' or $lancPesq[i].OC_ID neq null}
                                                        disabled title="ORDEM DE COMPRA - {$lancPesq[i].OC_ID}" {/if}
                                                        onchange="javascript:calculaTotal('{$lancPesq[i].ID}',this)" />
                                                </td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].CODPRODUTO} </td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].CODPRODUTONOTA} </td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].DESCRICAO} </td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].UNIDADE} </td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].QUANTIDADE|number_format:2:",":"."}</td>
                                                <td style="padding:3px;text-align:center;vertical-align:middle;">
                                                    {$lancPesq[i].QUANTIDADEUTILIZADA|number_format:2:",":"."}</td>
                                                <td> <input class="form-control input-sm money" style="padding:5px;"
                                                        id="qtdePedido{$lancPesq[i].ID}" name="qtdePedido{$lancPesq[i].ID}"
                                                        onchange="javascript:calculaTotal('{$lancPesq[i].ID}',this, '', true)"
                                                        value={$lancPesq[i].QUANTIDADE|number_format:2:",":"."}>
                                                </td>
                                                <td> <input class="form-control input-sm money" style="padding:3px;"
                                                        id="unitario{$lancPesq[i].ID}" name="unitario{$lancPesq[i].ID}"
                                                        value={$lancPesq[i].VALORUNITARIO|number_format:2:",":"."} readonly>
                                                </td>
                                                <td> <input class="form-control input-sm money"
                                                        id="percDesconto{$lancPesq[i].ID}" style="padding:3px;"
                                                        name="percDesconto{$lancPesq[i].ID}"
                                                        onchange="javascript:calculaTotal('{$lancPesq[i].ID}', this, '', true)"
                                                        value={$lancPesq[i].PERCDESCONTO|number_format:2:",":"."}>
                                                </td>
                                                <td> <input class="form-control input-sm money"
                                                        id="vlrDesconto{$lancPesq[i].ID}" style="padding:3px;"
                                                        name="vlrDesconto{$lancPesq[i].ID}"
                                                        onchange="javascript:calculaTotal('{$lancPesq[i].ID}',this, 'desconto')"
                                                        value={$lancPesq[i].DESCONTO|number_format:2:",":"."}>
                                                </td>
                                                <td style="border-radius: 0 0 6px 0"> <input
                                                        class="form-control input-sm money" id="totalItem{$lancPesq[i].ID}"
                                                        name="totalItem{$lancPesq[i].ID}" style="padding:3px;"
                                                        value={$lancPesq[i].VALORTOTAL|number_format:2:",":"."}>
                                                </td>

                                            </tr>
                                            <p>
                                            {/section}
                                    </tbody>
                                </table>

                                <div class="col-md-12 col-sm-12 col-xs-12 small">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <p><span class="legenda"
                                                        style="background-color: #cedfc3;"></span><b>Ordem de compra
                                                        atendida</b></p>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <p><span class="legenda"
                                                        style="background-color: #f4d4d4;"></span><b>Ordem de compra não
                                                        atendida</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- FIM class="col-md-6 col-sm-6 col-xs-12" -->

                        </div> <!-- FIM class="x_panel" -->
                    </div> <!-- FIM class="col-md-12 col-sm-12 col-xs-12" -->
                </div>
        </form>

    </div> <!-- FIM class="right_col" role="main" -->

    {include file="template/form.inc"}

    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#condPgto.js-example-basic-single").select2({});
        });
    </script>

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".money").maskMoney({
                decimal: ",",
                thousands: ".",
                allowZero: true
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

    <script>
        $(function() {
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

        });
    </script>

    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#condPgto.js-example-basic-single").select2({
                theme: "classic"
            });
        });
</script>