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

<script type="text/javascript" src="{$pathJs}/crm/s_contas_relatorio.js"> </script>
<script type="text/javascript" src="{$ADMhttpBib}/bib/sweetalert2/dist/sweetalert2.all.js"> </script>
<div class="right_col" role="main">

    <div class="x_panel">
        <h2>Relatórios - Contas
            {if $mensagem neq ''}
                <div class="container">
                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                </div>
            {/if}
        </h2>

        <div class="clearfix"></div>


        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
            <input name=mod type=hidden value="crm">
            <input name=form type=hidden value="rel_contas">
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
                    <div class="divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_aniversario')"
                            data-relatorio-nome="Aniversário">
                            <div class="panel-heading">
                                <h3 class="panel-title">Aniversário</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório de aniversariantes
                            </div>
                        </div>
                    </div>

                    <div class="divRelatorios">
                        <div class="panel panel-info btn panelPrincipal" role="button" data-toggle="modal"
                            data-target="#modalParametros" onclick="controlInputs('relatorio_contas')"
                            data-relatorio-nome="Relatório contas">
                            <div class="panel-heading">
                                <h3 class="panel-title">Relatório contas</h3>
                            </div>
                            <div class="panel-body panelText">
                                Relatório contas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{include file="rel_contas_modal_parametros.tpl"}