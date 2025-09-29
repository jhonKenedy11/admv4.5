<style>
    .panelPrincipal {
        padding: 0;
        -webkit-transition: -webkit-transform .5s ease;
        transition: transform .5s ease;
    }

    .panelPrincipal:hover {
        -webkit-transform: scale(1.07);
        transform: scale(1.07);
    }

    .panelText {
        font-size: 10px !important;
    }

    .modal-header .close {
        margin-top: -25px;
    }

    .divRelatorios {
        width: 260px;
        max-width: 100%;
        min-height: 100px;
        display: inline-block;
        margin-bottom: 15px;
    }
    .panelPrincipal {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .panel-body.panelText {
        word-wrap: break-word;
        white-space: normal;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 10px;
    }
    
    .panel-heading {
        text-align: center;
    }

</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_relatorio.js"> </script>
<script type="text/javascript" src="{$ADMhttpBib}/bib/sweetalert2/dist/sweetalert2.all.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <!-- panel principal  -->
    <div class="x_panel">
        <h2>Relatórios
            {if $mensagem neq ''}
                <div class="container">
                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                </div>
            {/if}
        </h2>

        <div class="clearfix"></div>


        <form id="relatorios" name="relatorios" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="est">
            <input name=form type=hidden value="consultas">
            <input name=id type=hidden value="">
            <input name=opcao type=hidden value="{$opcao}">
            <input name=letra type=hidden value="{$letra}">
            <input name=submenu type=hidden value="{$subMenu}">
            <input name=dataIni type=hidden value={$dataIni}>
            <input name=dataFim type=hidden value={$dataFim}>
            <input name=pessoa type=hidden value={$pessoa}>
            <input name=fornecedor type=hidden value={$fornecedor}>
            <input name=codProduto type=hidden value={$codProduto}>
            <input name=unidade type=hidden value={$unidade}>
            <input name="report" id="report" type=hidden value={$report}>

            <div class="container">

                <div class="row text-right">

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioBonus')" data-relatorio-nome="Bônus">
                            <div class="panel-heading">
                                <h3 class="panel-title">Bônus</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar bônus disponível
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioVendas')"data-relatorio-nome="Pedido Vendas">
                            <div class="panel-heading">
                                <h3 class="panel-title">Pedido Vendas</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas do período
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioDetalhado')"data-relatorio-nome="Vendas Detalhadas">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas Detalhadas</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas detalhadas do período
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioMotivo')"data-relatorio-nome="Vendas Motivo">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas Motivo</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar motivos de venda perdida
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioItem')"data-relatorio-nome="Vendas Item">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas Item</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar venda por item
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioItemEntrega')"data-relatorio-nome="Item Entrega">
                            <div class="panel-heading">
                                <h3 class="panel-title">Item Entrega</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para relacionar itens a serem entregue
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioFaturaGeral')"data-relatorio-nome="Fatura Geral">
                            <div class="panel-heading">
                                <h3 class="panel-title">Fatura Geral</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para relacionar faturamento em geral
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioFaturaGeralA')"data-relatorio-nome="Fatura Geral em Aberto">
                            <div class="panel-heading">
                                <h3 class="panel-title">Fatura Geral em Aberto</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para relacionar faturamento em aberto
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioVendedor')"data-relatorio-nome="Vendas Vendedor">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas Vendedor</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas por vendedor
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioSemana')"data-relatorio-nome="Vendas semana">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas semana</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas por semana
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioMes')"data-relatorio-nome="Vendas mes">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas mes</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas por mes
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioFaturaSintetico')"data-relatorio-nome="Fatura Sintética">
                            <div class="panel-heading">
                                <h3 class="panel-title">Fatura Sintética</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar faturamento sintética
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioFaturaAnalitico')"data-relatorio-nome="Fatura Analítica">
                            <div class="panel-heading">
                                <h3 class="panel-title">Fatura Analítica</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar faturamento analítica
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioCondPagamento')"data-relatorio-nome="Vendas Cond Pagamento">
                            <div class="panel-heading">
                                <h3 class="panel-title">Vendas Cond Pagamento</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório para gerar vendas por condição pagamento
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioEntrega')"data-relatorio-nome="Entregas">
                            <div class="panel-heading">
                                <h3 class="panel-title">Entregas</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de entregas no periodo
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioPedNaoEntregue')"data-relatorio-nome="Pedidos não Entregue">
                            <div class="panel-heading">
                                <h3 class="panel-title">Pedidos não Entregue</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório pedidos pendentes de entrega
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorioEstoqueDisponivelVenda')" data-relatorio-nome="Estoque disponível Venda">
                            <div class="panel-heading">
                                <h3 class="panel-title">Estoque disponível Venda</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de estoque disponível venda
                            </div>
                        </div>
                    </div>

                </div> <!-- class="row -->

            </div> <!-- END container -->


            <div class="x_content"></div>

        </form>

    </div>
</div>

<!-- Funcoes JS sendo chamadas dentro da modal -->
{include file="pedido_relatorios_modal_parametros.tpl"}