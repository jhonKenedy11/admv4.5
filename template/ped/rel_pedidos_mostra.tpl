<script type="text/javascript" src="{$pathJs}/ped/s_rel_pedidos.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Consultas</h3>
            </div>
        </div>

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-9 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Consulta
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>
                            {/if}
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <!--div class="x_content" style="display: none;"-->

                    <div class="x_content">

                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value="rel_pedidos">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value="{$opcao}">
                            <input name=letra type=hidden value="{$letra}">
                            <input name=submenu type=hidden value="{$subMenu}">
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=fornecedor type=hidden value={$fornecedor}>
                            <input name=codProduto type=hidden value={$codProduto}>
                            <input name=descProduto type=hidden value={$descProduto}>
                            <input name=unidade type=hidden value={$unidade}>
                            <input name=tipoRelatorio type=hidden value={$tipoRelatorio}>
                            <input name=motivoSelected type=hidden value={$motivoSelected}>
                            <input name=vendedorSelected type=hidden value={$vendedorSelected}>
                            <input name=condPagamentoSelected type=hidden value={$condPagamentoSelected}>
                            <input name=situacaoSelected type=hidden value={$situacaoSelected}>
                            <input name=centroCustoSelected type=hidden value={$centroCustoSelected}>


                            <div class="form-group">
                                <div class="form-group col-md-3 col-sm-3 col-xs-3">
                                    <label>C&oacute;d. Pedido</label>
                                    <input class="form-control" id="codPedido" name="codPedido"
                                        placeholder="Código do Pedido." value={$codPedido}>
                                </div>
                                <div class="form-group col-md-5 col-sm-6 col-xs-6">
                                    <label>Situação</label>
                                    <select class="select2_multiple form-control" multiple="multiple" id="situacao"
                                        name="situacao">
                                        {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                    </select>
                                </div>
                                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                    <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <div>
                                        <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                            value="{$dataIni} - {$dataFim}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <label class="">Cliente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly id="nome" name="nome"
                                            placeholder="Conta" value="{$nome}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <label for="descProduto">Produto</label>
                                    <div class="input-group">
                                        <input class="form-control" readonly type="text" id="pesProduto"
                                            name="pesProduto" value="{$pesProduto}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=rel_pedidos');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
                        </div>
                        -->
                            <div class="form-group">
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label>Centro de Custo</label>
                                    <select disabled class="select2_multiple form-control" multiple="multiple"
                                        name="ccusto" id="ccusto">
                                        {html_options values=$ccusto_ids output=$ccusto_names selected=$ccusto_id}
                                    </SELECT>
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label>Venda Perdida - Motivo</label>
                                    <SELECT class="select2_multiple form-control" multiple="multiple" id="motivo"
                                        name="motivo">
                                        {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                                    </SELECT>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <label for="descProduto">Vendedor</label>
                                    <select class="select2_multiple form-control" multiple="multiple" name="vendedor"
                                        id="vendedor">
                                        {html_options values=$usrfatura_ids selected=$usrfatura_id output=$usrfatura_names}
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <label for="descProduto">Condição de Pagamento</label>
                                    <select class="select2_multiple form-control" multiple="multiple" name="condPag"
                                        id="condPag">
                                        {html_options values=$condPag_ids selected=$condPag_id output=$condPag_names    }
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                            </div>

                        </form>
                    </div>

                </div> <!-- x_panel -->
            </div> <!-- div class="tamanho -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel" style="height: 100%;">
                    <div class="menu_section">
                        <!--button class="dropdown-btn" ><i class="fa fa-bar-chart"></i> Dashboard 
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container">
                            <a href="#">Pedido</a><br>
                        </div-->
                        <button class="dropdown-btn"><i class="fa fa-file"></i> Relatórios
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container">
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Detalhado');"><span>Vendas
                                    Detalhado</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Item');"><span>Vendas Item</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Fatura');"><span>Vendas Fatura</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Vendedor');"><span>Vendas
                                    Vendedor</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Motivo');"><span>Vendas Motivo</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('CondPagamento');"><span>Vendas Cond
                                    Pagamento</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('');"><span>Vendas Diario</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Semana');"><span>Vendas Semana</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Mes');"><span>Vendas Mês</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioVendas('Entregas');"><span>Entregas</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioFaturaSintetico();"><span>Vendas Fatura
                                    Sintética</span></button><br>
                            <button type="button" class="btn btn-dark btn-xs"
                                onClick="javascript:relatorioFaturaAnalitico();"><span>Vendas Fatura
                                    Analítica</span></button><br>
                        </div>

                    </div>

                </div> <!-- FIM x_panel -->
            </div>
        </div> <!-- div row = painel principal-->

    </div> <!-- div class="x_panel"-->
</div> <!-- div class="x_panel" = tabela principal-->
</div> <!-- div  "-->
</div> <!-- div role=main-->



{include file="template/database.inc"}
<!-- /Datatables -->
<!-- select 2 bootstrap -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<!-- select2 -->
<script>
    $("#condPag.select2_multiple").select2({
        placeholder: "Selecione a condição de Pagamento"
    });
    $("#vendedor.select2_multiple").select2({
        placeholder: "Selecione o Vendedor"
    });
    $("#ccusto.select2_multiple").select2({
        placeholder: "Selecione o Centro de Custo"
    });
    $("#situacao.select2_multiple").select2({
        placeholder: "Selecione a Situação"
    });
    $("#motivo.select2_multiple").select2({
        placeholder: "Selecione o Motivo"
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
<!-- /daterangepicker -->
<!-- side menu relatorios -->
<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

<!-- -->
<style>
    .dropdown-btn {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 14px;
        color: #23395d;
        display: block;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    .dropdown-container {

        padding-left: 25px;
    }
</style>