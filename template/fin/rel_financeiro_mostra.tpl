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

<script type="text/javascript" src="{$pathJs}/fin/s_financeiro_relatorio.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">

    <!-- panel principal  -->
    <div class="x_panel">
        <h2>Relatórios Financeiros
            {if $mensagem neq ''}
                <div class="container">
                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                </div>
            {/if}
        </h2>

        <div class="clearfix"></div>

        <form id="relatorios" name="relatorios" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="fin">
            <input name=form type=hidden value="rel_financeiro">
            <input name=id type=hidden value="">
            <input name=opcao type=hidden value="{$opcao}">
            <input name=letra type=hidden value="{$letra}">
            <input name=submenu type=hidden value="{$subMenu}">
            <input name=dataIni type=hidden value={$dataIni}>
            <input name=dataFim type=hidden value={$dataFim}>
            <input name=referencia type=hidden value={$referencia}>
            <input name="report" id="report" type=hidden value={$report}>

            <div class="container">

                <div class="row text-right">

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('lancamentos_data')" data-relatorio-nome="Lançamentos por Data">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar"></i> Lançamentos por Data</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de lançamentos financeiros por período
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('fluxo_caixa')" data-relatorio-nome="Fluxo de Caixa">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-line-chart"></i> Fluxo de Caixa</h3>
                            </div>
                            <div class="panel-body panelText">
                                Projeção e realizado do fluxo de caixa
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('consolidacao')" data-relatorio-nome="Consolidação">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bank"></i> Consolidação</h3>
                            </div>
                            <div class="panel-body panelText">
                                Consolidação de movimentações bancárias
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-primary btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('resumo_genero')" data-relatorio-nome="Resumo Gênero">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-folder-open"></i> Resumo Gênero</h3>
                            </div>
                            <div class="panel-body panelText">
                                Resumo por gênero/categoria
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('centro_custo_analitico')" data-relatorio-nome="Centro de Custo Analítico">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-building"></i> Centro de Custo Analítico</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório analítico por centro de custo
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('centro_custo_sintetico')" data-relatorio-nome="Centro de Custo Sintético">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-building-o"></i> Centro de Custo Sintético</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório sintético por centro de custo
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('dre_financeiro')" data-relatorio-nome="DRE Financeiro">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart"></i> DRE Financeiro</h3>
                            </div>
                            <div class="panel-body panelText">
                                Demonstração do Resultado do Exercício
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('rel_financeiro_data_entrega')" data-relatorio-nome="Rel. Financeiro Data Entrega">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-truck"></i> Rel. Data Entrega</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório financeiro por data de entrega
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- Modal de Parâmetros -->
{include file="rel_financeiro_modal_parametros.tpl"}