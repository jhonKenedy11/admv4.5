<style>
    .message-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .message-container h4 {
        color: #6c767d;
        font-size: 2rem;
        text-align: center;
    }

    .height100 {
        height: 100vh;
        background-color: #F7F7F7;
        margin: 0;
        padding: 0;
    }

    .x_panel {
        padding: 0;
    }

    .table {
        margin-bottom: 0;
        margin-top: -6px;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px !important;
    }

    .dataHora {
        font-size: 10px;
    }

    .print-section {
        page-break-inside: avoid;
    }

    .total-row {
        font-weight: bold;
    }

    .motivo-header {
        background-color: #f0f0f0;
    }

    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        body {
            padding: 15px;
        }

        td,
        th,
        h6 {
            font-size: 9px;
            line-height: 10px !important;
        }

        .no-print {
            display: none !important;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 3px !important;
        }

        .dataHora {
            font-size: 8px;
        }

        h2 {
            font-size: 13px;
        }

        .height100 {
            height: auto;
        }
    }
</style>

<section class="height100">
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5">
                <h2>
                    <strong>PEDIDO VENDAS - MOTIVO</strong><br>
                    <h4>Período: {$dataIni} | {$dataFim}</h4>
                </h2>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <b class="pull-right dataHora">{$dataImp}</b>
            </div>
        </div>

        <div class="clearfix">
            <div class="x_panel">
                <div class="x_content print-section">
                    {if $pedido|count > 0}
                        <section class="content invoice">
                            <div class="row small">
                                <div class="col-xs-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>PEDIDO</th>
                                                <th>CLIENTE</th>
                                                <th>VENDEDOR</th>
                                                <th>SITUAÇÃO</th>
                                                <th>CENTRO CUSTO</th>
                                                <th>EMISSÃO</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {assign var="grupoAtual" value=""}
                                            {assign var="totalGrupo" value=0}
                                            {assign var="totalGeral" value=0}

                                            {section name=i loop=$pedido}
                                                {assign var="motivo" value=$pedido[i].MOTIVO|default:'MOTIVO NÃO INFORMADO'}
                                                {assign var="emissao" value=$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                {assign var="chaveGrupo" value="`$motivo`-`$emissao`"}

                                                {if $chaveGrupo neq $grupoAtual}
                                                    {if $grupoAtual neq ""}
                                                        <tr class="total-row">
                                                            <td colspan="5"></td>
                                                            <td colspan="1">Subtotal</td>
                                                            <td colspan="">R$ {$totalGrupo|number_format:2:",":"."}</td>
                                                        </tr>
                                                        {assign var="totalGrupo" value=0}
                                                    {/if}

                                                    <tr class="motivo-header">
                                                        <td colspan="7">
                                                            <strong>{$motivo} - {$emissao}</strong>
                                                        </td>
                                                    </tr>
                                                    {assign var="grupoAtual" value=$chaveGrupo}
                                                {/if}

                                                <tr>
                                                    <td>{$pedido[i].ID}</td>
                                                    <td>{$pedido[i].NOMECLIENTE}</td>
                                                    <td>{$pedido[i].NOMEVENDEDOR}</td>
                                                    <td>{$pedido[i].SIT}</td>
                                                    <td>{$pedido[i].CCUSTO}</td>
                                                    <td>{$emissao}</td>
                                                    <td>R$ {$pedido[i].TOTAL|number_format:2:",":"."}</td>
                                                </tr>

                                                {assign var="totalGrupo" value=$totalGrupo+$pedido[i].TOTAL}
                                                {assign var="totalGeral" value=$totalGeral+$pedido[i].TOTAL}
                                            {/section}


                                            <tr class="total-row">
                                                <td colspan="6">TOTAL GERAL</td>
                                                <td colspan="">R$ {$totalGeral|number_format:2:",":"."}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    {else}
                        <div class="message-container">
                            <h4>Nenhum registro localizado!</h4>
                        </div>
                    {/if}
                </div>
            </div>
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i>
                    Imprimir</button>
            </div>
        </div>
    </div>
</section>