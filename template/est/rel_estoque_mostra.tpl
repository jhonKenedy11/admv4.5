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
<script type="text/javascript" src="{$pathJs}/est/s_estoque_relatorio.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">

    <!-- panel principal  -->
    <div class="x_panel">
        <h2>Relatórios de Estoque
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
            <input name=form type=hidden value="rel_estoque">
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
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('movimentacao')" data-relatorio-nome="Movimentação de Estoque">
                            <div class="panel-heading">
                                <h3 class="panel-title">Movimentação</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de movimentação de estoque
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('curva_abc')" data-relatorio-nome="Curva ABC">
                            <div class="panel-heading">
                                <h3 class="panel-title">Curva ABC</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de curva ABC por valor/quantidade
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('kardex_sintetico')" data-relatorio-nome="Kardex Sintético">
                            <div class="panel-heading">
                                <h3 class="panel-title">Kardex Sintético</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de kardex sintético
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('kardex_analitico')" data-relatorio-nome="Kardex Analítico">
                            <div class="panel-heading">
                                <h3 class="panel-title">Kardex Analítico</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de kardex analítico detalhado
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-default btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('estoque_geral')" data-relatorio-nome="Estoque Geral">
                            <div class="panel-heading">
                                <h3 class="panel-title">Estoque Geral</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de estoque geral
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('compras')" data-relatorio-nome="Relatório de Compras">
                            <div class="panel-heading">
                                <h3 class="panel-title">Compras</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de compras
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('compras_sugestoes')" data-relatorio-nome="Sugestões de Compras">
                            <div class="panel-heading">
                                <h3 class="panel-title">Sugestões</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de sugestões de compras
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('compras_estoque_minimo')" data-relatorio-nome="Compras Estoque Mínimo">
                            <div class="panel-heading">
                                <h3 class="panel-title">Estoque Mínimo</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de compras por estoque mínimo
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('tabela_precos')" data-relatorio-nome="Tabela de Preços">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabela de Preços</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de tabela de preços
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('estoque_localizacao')" data-relatorio-nome="Estoque por Localização">
                            <div class="panel-heading">
                                <h3 class="panel-title">Estoque Localização</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de estoque por localização
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('movimento_cliente')" data-relatorio-nome="Movimento por Cliente">
                            <div class="panel-heading">
                                <h3 class="panel-title">Movimento Cliente</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de movimento por cliente
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('consulta_preco')" data-relatorio-nome="Consulta de Preços">
                            <div class="panel-heading">
                                <h3 class="panel-title">Consulta Preços</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de consulta de preços
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

{include file="rel_estoque_modal_parametros.tpl"}