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
        width: 191px;
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
</style>

<script type="text/javascript" src="{$pathJs}/ped/s_oportunidade.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 5px 2px 2px 2px;">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="ped">
            <input name=form type=hidden value="oportunidade">
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
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=nrItem type=hidden value={$nrItem}>
            <input name=opcao_item type=hidden value={$opcao_item}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="">
                                <div class="col-md-2 title-pedido-servico">
                                    <h3 class="title-cadastro_">Oportunidade &nbsp;-</h3>
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
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                <!-- <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu dropMenuRel" role="menu">
                            <li>
                                <button {if $id eq ''} disabled {/if} id="btnDuplicarPedido" type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:submitDuplicarPedido({$id});">
                                    <span>Duplicar Pedido</span>
                                </button>
                            </li>
                            <li>
                                <button {if $id eq ''} disabled {/if} id="btnGerarOs" type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:submitGerarOs({$id});">
                                        <span> Gerar OS</span>
                                </button>
                            </li>
                            <li>
                                <button {if $id eq ''} disabled {/if} id="btnEstornarOs" type="button" class="btn btn-danger btn-xs btnRelatorios" 
                                        onClick="javascript:submitEstornarOs({$id});">
                                        <span> Estornar OS</span>
                                </button>
                            </li>

                         </ul>

                      </li> 
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> -->
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

                                <div class="col-lg-3 col-sm-6 col-xs-6 text-left line-formated">
                                    <label>Vendedor</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="usrAbertura" class="form-control input-sm" title="Atendente"
                                            alt="Atendente">
                                            {html_options values=$usrAbertura_ids selected=$usrAbertura output=$usrAbertura_names}
                                        </select>
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
                                            <input class="form-control input-sm" placeholder="Valor Produtos."
                                                id="valorPecas" name="valorPecas" value="{$valorPecas}" readonly>
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


                            </div>
                            <div class="form-group line-formated">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="obs">Observações</label>
                                    <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs"
                                        rows="2">{$obs}</textarea>
                                </div>
                            </div>

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation">
                                        <a href="#tab_content2" id="pecas-tab" role="tab" data-toggle="tab"
                                            aria-expanded="true">Produtos</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel"
                                        class="tab-pane fade {if $tab eq 'peça'} active in {elseif $tab eq ''} active in {/if} small"
                                        id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div id="formPedidoItem">
                                                <input name=prodExiste id="prodExiste" type=hidden
                                                    value="{$prodExiste}">
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
                                                        <input class="form-control input-sm" type="text"
                                                            id="codFabricante" name="codFabricante"
                                                            placeholder="Codigo Fabricante"
                                                            onblur="javascript:buscaProduto();" value={$codFabricante}>
                                                        <!-- onchange="javascript:submitBuscaProduto('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=pedido_ps')"-->
                                                    </div>
                                                    <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                        <label for="codProdutoNota">Código Nota</label>
                                                        <input class="form-control input-sm" type="text"
                                                            id="codProdutoNota" name="codProdutoNota"
                                                            placeholder="Código Nota." value={$codProdutoNota}>
                                                    </div>
                                                    <div class="col-md-5 col-sm-12 col-xs-12 small line-formated">
                                                        <label for="Produto">Produto</label>
                                                        <div class="input-group line-formated">
                                                            <input type="text" class="form-control input-sm"
                                                                id="descProduto" name="descProduto"
                                                                placeholder="Produto" required value="{$descProduto}">
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

                                </div>
                            </div> <!-- tabpanel -->
                        </div> <!-- panel -->

                    </div> <!-- FIM class="x_panel" -->
                </div> <!-- FIM class="col-md-12 col-sm-12 col-xs-12" -->

            </div>
        </form>

    </div> <!-- FIM class="right_col" role="main" -->

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

        $(document).bind('DOMSubtreeModified', function() {
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