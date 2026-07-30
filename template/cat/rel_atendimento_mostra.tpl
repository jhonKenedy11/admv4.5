<style>
    .panelPrincipal {
        padding: 0;
        -webkit-transition: -webkit-transform .5s ease;
        transition: transform .5s ease;
        margin-bottom: 8px;
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

<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_relatorio.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<div class="right_col" role="main">

    <div class="x_panel">
        <h2>Relatórios - Atendimento
            {if $mensagem neq ''}
                <div class="container">
                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                </div>
            {/if}
        </h2>

        <div class="clearfix"></div>


        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="cat">
            <input name=form type=hidden value="rel_atendimento">
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

                    {* Serviço *}
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('relatorio_servico')"
                            data-relatorio-nome="Relatório de Serviço">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-cogs"></i> Relatório de Serviço</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório detalhado de serviços executados
                            </div>
                        </div>
                    </div>

                    {* Usuário *}
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('relatorio_usuario')"
                            data-relatorio-nome="Relatório por Usuário">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user"></i> Relatório por Usuário</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de atendimentos por usuário/equipe
                            </div>
                        </div>
                    </div>

                    {* Equipamento *}
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('relatorio_equipamento')"
                            data-relatorio-nome="Relatório por Equipamento">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-wrench"></i> Relatório por Equipamento</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de atendimentos por equipamento
                            </div>
                        </div>
                    </div>

                 

                    {* Período *}
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('relatorio_periodo')"
                            data-relatorio-nome="Relatório por Período">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar"></i> Relatório por Período</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório consolidado por período
                            </div>
                        </div>
                    </div>

                    {* Medição *}
                    <div class="col-md-3 col-lg-2 divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="javascript:controlInputs('relatorio_medicao')"
                            data-relatorio-nome="Relatório de Medição">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-clipboard"></i> Relatório de Medição</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de medição de serviços
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>

{include file="rel_atendimento_modal_parametros.tpl"}