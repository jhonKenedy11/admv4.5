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
        width: 250px;
        max-width: 100%;
        min-height: 100px;
        margin-bottom: 10px;
        display: inline-block;
        margin-right: 8px;
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

    .x_panel {
        height: 540px;
    }

    .panel-heading {
        text-align: center;
    }

    .botoes-relatorios {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;

    }
</style>

<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_relatorio.js"> </script>
<script type="text/javascript" src="{$ADMhttpBib}/bib/sweetalert2/dist/sweetalert2.all.js"> </script>
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
                <div class="botoes-relatorios">
                    {* medicao *}
                    {* <div class="divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_medicao')"
                            data-relatorio-nome="Relatório de medição">
                            <div class="panel-heading">
                                <h3 class="panel-title">Relatório de medição</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de medição
                            </div>
                        </div>
                    </div> *}

                    {* servico *}
                    <div class="divRelatorios">
                        <div class="panel panel-success btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_servico')"
                            data-relatorio-nome="Relatório de servico">
                            <div class="panel-heading">
                                <h3 class="panel-title">Relatório de Serviço</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de Serviço
                            </div>
                        </div>
                    </div>

                    {* usuario *}
                    <div class="divRelatorios">
                        <div class="panel panel-warning btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_usuario')"
                            data-relatorio-nome="Relatório de usuario">
                            <div class="panel-heading">
                                <h3 class="panel-title">Relatório de usuario</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório por usuario
                            </div>
                        </div>
                    </div>

                    {* equipamento *}
                    <div class="divRelatorios">
                        <div class="panel panel-danger btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_equipamento')"
                            data-relatorio-nome="Relatório equipamento">
                            <div class="panel-heading">
                                <h3 class="panel-title">Relatório equipamento</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório equipamento
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
</div>

{include file="rel_atendimento_modal_parametros.tpl"}