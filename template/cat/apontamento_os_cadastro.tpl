<script type="text/javascript" src="{$pathJs}/cat/s_apontamento_os.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="cat">
            <input name=form type=hidden value="apontamento_os">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=id type=hidden value={$id}>
            <input name=codProduto type=hidden value={$codProduto}>
            <input name=idPecas type=hidden value={$idPecas}>
            <input name=idServicos type=hidden value={$idServicos}>
            <input name=catEquipamentoId type=hidden value="{$catEquipamentoId}">
            <input name=letra type=hidden value={$letra}>
            <input name=letra_peca type=hidden value={$letra_peca}>
            <input name=letra_servico type=hidden value={$letra_servico}>
            <input name=opcao type=hidden value={$opcao}>
            <input name=pesq type=hidden value={$pesq}>
            <input name=fornecedor type=hidden value="">
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=checkServ type=hidden value={$checkServ}>
            <input name=qtdeProd type=hidden value={$qtdeProd}>
            <input name=vlrUnitarioProd type=hidden value={$vlrUnitarioProd}>
            <input name=totalUtilizado type=hidden value={$totalUtilizado}>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                {if $subMenu eq "cadastrar"}
                                    Apontamento de Ordem de Serviço - Cadastro
                                {else}
                                    Apontamento de Ordem de Serviço - Altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-success" role="alert">
                                                        <strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span><span>
                                            Listar OS</span></button>
                                </li>
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">


                            <div class="form-group line-formated">

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="contato">OS</label>
                                    <input type="text" class="form-control input-sm" readonly id="atendimentoId"
                                        name="atendimentoId" placeholder="Num OS" required value="{$atendimentoId}">
                                </div>


                                <div class="col-md-5 col-sm-6 col-xs-6">
                                    <label for="contato">Cliente</label>
                                    <input type="text" class="form-control input-sm" readonly id="nome" name="nome"
                                        placeholder="Cliente" required value="{$nome}">
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="contato">Contato</label>
                                    <input type="text" class="form-control input-sm" readonly id="contato"
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
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obs">Observações Atendimento</label>
                                    <textarea class="resizable_textarea form-control input-sm" readonly id="obs"
                                        name="obs" rows="2">{$obs}</textarea>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obsServico">Observações Serviço</label>
                                    <textarea class="resizable_textarea form-control input-sm" readonly id="obsServicos"
                                        name="obsServicos" rows="2">{$obsServicos}</textarea>
                                </div>
                            </div>
                            <div id="divTotal" class="form-group line-formated">
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="valorPecas">Valor Peças</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Peças." id="valorPecas"
                                            name="valorPecas" value="{$valorPecas}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="valorServicos">Valor Serviço</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Serviço."
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
                                            id="valorVisita" name="valorVisita" value="{$valorVisita}" readonly>
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
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="valorPecas">Valor Peças Utilizado</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Peças Utilizado."
                                            id="valorPecasUtilizado" name="valorPecasUtilizado"
                                            value="{$valorPecasUtilizado}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-6 col-xs-6">
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="valorPecas">TOTAL com Peças Utilizado</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-sm" type="button">R$</button>
                                        </span>
                                        <input class="form-control input-sm" placeholder="Valor Peças Utilizado."
                                            id="totalPecasUtilizado" name="totalPecasUtilizado"
                                            value="{$totalPecasUtilizado}" readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

                                    <li role="presentation"><a href="#tab_content2" id="pecas-tab" role="tab"
                                            data-toggle="tab" aria-expanded="true">Peças</a>
                                    </li>
                                    <li role="presentation"><a href="#tab_content3" id="servicos-tab" role="tab"
                                            data-toggle="tab" aria-expanded="true">Serviços</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel"
                                        class="tab-pane fade {if $tab eq 'peça'} active in {elseif $tab eq ''} active in {/if} small"
                                        id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group line-formated">
                                                <div class="col-md-2 col-sm-12 col-xs-12 has-feedback">
                                                    <label style="visibility:hidden">btn atualizaAll</label>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onClick="javascript:submitConfirmarTodasQtdeUtilizada();">
                                                        <span class="glyphicon glyphicon-arrow-down"
                                                            aria-hidden="true"></span><span> Todos
                                                            Utilizados</span></button>
                                                </div>
                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="codProdutoNota">Código Nota</label>
                                                    <input class="form-control input-sm" type="text" id="codProdutoNota"
                                                        name="codProdutoNota" placeholder="Código Nota."
                                                        value={$codProdutoNota}>
                                                </div>
                                                <div class="col-md-5 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="descricaoProduto">Produto</label>
                                                    <input class="form-control input-sm" type="text"
                                                        id="descricaoProduto" name="descricaoProduto"
                                                        placeholder="Descrição Produto" alt="Unidade"
                                                        value={$descricaoProduto}>
                                                </div>

                                                <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                                    <label for="uniProduto">Qtde Utilizada</label>
                                                    <input class="form-control input-sm money" type="text"
                                                        id="uniProduto" name="qtdeUtilizada"
                                                        placeholder="Qtde Utilizada" alt="Quantidade Utilizada"
                                                        value={$qtdeUtilizada}>
                                                </div>


                                                <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                                    <label style="visibility:hidden">btn</label>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onClick="javascript:submitConfirmarPecas();">
                                                        <span class="glyphicon glyphicon-ok"
                                                            aria-hidden="true"></span><span> Confirmar</span></button>
                                                </div>


                                            </div>
                                        </div>
                                        <table id="datatable-buttons-pecas" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                    <th>Cód</th>
                                                    <th>Cód. Nota</th>
                                                    <th>Descrição</th>
                                                    <th>Unidade</th>
                                                    <th>Quantidade</th>
                                                    <th>Qtde. Utilizada</th>
                                                    <th>Valor Unitário</th>
                                                    <th>Valor Desconto</th>
                                                    <th>% Desconto</th>
                                                    <th>TOTAL</th>
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$lancPecas}
                                                    <tr>
                                                        <td> {$lancPecas[i].CODPRODUTO} </td>
                                                        <td> {$lancPecas[i].CODPRODUTONOTA} </td>
                                                        <td> {$lancPecas[i].DESCRICAO} </td>
                                                        <td> {$lancPecas[i].UNIDADE} </td>
                                                        <td> {$lancPecas[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                        <td> {$lancPecas[i].QUANTIDADEUTILIZADA|number_format:2:",":"."}
                                                        </td>
                                                        <td> {$lancPecas[i].VALORUNITARIO|number_format:2:",":"."} </td>
                                                        <td> {$lancPecas[i].DESCONTO|number_format:2:",":"."} </td>
                                                        <td> {$lancPecas[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                        <td> {$lancPecas[i].VALORTOTAL|number_format:2:",":"."} </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-xs"
                                                                onclick="javascript:editarPeca(this, '{$lancPecas[i].ID}')"><span
                                                                    class="glyphicon glyphicon-pencil"
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
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <h2>Serviços</h2>
                                            <table id="datatable-buttons-servicos"
                                                class="table table-bordered jambo_table">
                                                <thead>
                                                    <tr style="background: gray; color: white;">
                                                        <th>#</th>
                                                        <th>Cód</th>
                                                        <th>Descrição</th>
                                                        <th>Unidade</th>
                                                        <th>Quantidade</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {section name=i loop=$lancServicos}
                                                        <tr>
                                                            <td> <input type="checkBox" name="checkedServ"
                                                                    id="check{$lancServicos[i].ID}"
                                                                    onchange="javascript:buscaApontamentosServico('{$lancServicos[i].ID}')" />
                                                            </td>
                                                            <td> {$lancServicos[i].ID} </td>
                                                            <td> {$lancServicos[i].DESCSERVICO} </td>
                                                            <td> {$lancServicos[i].UNIDADE} </td>
                                                            <td> {$lancServicos[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary btn-xs"
                                                                    onclick="javascript:cadastrarApontamento(this)"
                                                                    data-toggle="modal"
                                                                    data-target="#modalCadastraApontamento"><span
                                                                        class="glyphicon glyphicon-plus"
                                                                        aria-hidden="true"></span></button>
                                                            </td>
                                                        </tr>
                                                        <p>
                                                        {/section}
                                                </tbody>
                                            </table>


                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <table id="datatable-buttons-apontamento"
                                                class="table table-bordered jambo_table">
                                                <h2>Apontamentos</h2>
                                                <thead>
                                                    <tr style="background:dark-blue; color: white;">
                                                        <th>Cód</th>
                                                        <th>Cód Serviço</th>
                                                        <th>Descrição</th>
                                                        <th>Data/Hr Inicio</th>
                                                        <th>Data/Hr Fim</th>
                                                        <th>TOTAL Horas</th>
                                                        <th style="width:80px">Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {section name=i loop=$lancItens}
                                                        <tr>
                                                            <td> {$lancItens[i].ID} </td>
                                                            <td> {$lancItens[i].SERVICO_ID} </td>
                                                            <td> {$lancItens[i].DESCRICAO} </td>
                                                            <td> {$lancItens[i].DATA_INICIO|date_format:"%d/%m/%Y %H:%M:%S"}
                                                            </td>
                                                            <td> {$lancItens[i].DATA_FIM|date_format:"%d/%m/%Y %H:%M:%S"}
                                                            </td>
                                                            <td> {$lancItens[i].TOTALHORAS} </td>
                                                            <td style="display:none"> {$lancItens[i].USER_ID} </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary btn-xs"
                                                                    onclick="javascript:editarApontamento(this)"
                                                                    data-toggle="modal"
                                                                    data-target="#modalCadastraApontamento"><span
                                                                        class="glyphicon glyphicon-pencil"
                                                                        aria-hidden="true"></span></button>
                                                                <button type="button" class="btn btn-danger btn-xs"
                                                                    onclick="javascript:submitExcluiApontamento('{$lancItens[i].ID}');"><span
                                                                        class="glyphicon glyphicon-remove"
                                                                        aria-hidden="true"></span></button>
                                                            </td>
                                                        </tr>
                                                        <p>
                                                        {/section}
                                                </tbody>
                                            </table>


                                        </div>



                                    </div>




                                </div>
                            </div> <!-- tabpanel -->
                        </div> <!-- panel -->

                    </div> <!-- FIM class="x_panel" -->
                </div> <!-- FIM class="col-md-12 col-sm-12 col-xs-12" -->
                <!-- INCLUDES DE MODAL -->
                {include file="apontamento_cadastra_modal.tpl"}
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
                allowZero: true,
            });
        });
</script>