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

    .vendedor-header {
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

        h4 {
            font-size: 11px;
        }

        h5 {
            font-size: 10px;
            margin: 0;
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
                    <strong>PEDIDO VENDAS VENDEDOR</strong><br>
                    <h4>Período - {$dataIni} | {$dataFim}</h4>
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
                                                <th></th>
                                                <th>EMISSAO</th>
                                                <th>PEDIDO</th>
                                                <th>CLIENTE</th>
                                                <th>SITUAÇÃO</th>
                                                <th>CENTRO CUSTO</th>
                                                {if $tipoUsuario neq ""}<th>CUSTO</th>{/if}
                                                <th>LUCRO BRUTO</th>
                                                <th>MARGEM LIQUIDA</th>
                                                <th>MARKUP</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {assign var="vendedor" value=""}
                                            {assign var="totalVendedor" value=0}
                                            {assign var="totalGeral" value=0}
                                            {assign var="totalVendedorCusto" value=0}
                                            {assign var="totalGeralCusto" value=0}

                                            {section name=i loop=$pedido}
                                                {if $pedido[i].NOMEVENDEDOR neq $vendedor}
                                                    {if $vendedor neq ""}
                                                        <tr class="total-row">
                                                            <td colspan="8"></td>
                                                            <td>TOTAL R$</td>
                                                            <td>{$totalVendedor|number_format:2:",":"."}</td>
                                                        </tr>
                                                        {assign var="totalVendedor" value=0}
                                                        {assign var="totalVendedorCusto" value=0}
                                                    {/if}
                                                    <tr class="vendedor-header">
                                                        <th colspan="11">{$pedido[i].NOMEVENDEDOR}</th>
                                                    </tr>
                                                    {assign var="vendedor" value=$pedido[i].NOMEVENDEDOR}
                                                {/if}

                                                <tr>
                                                    <td></td>
                                                    <td>{$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                                    <td>{$pedido[i].ID}</td>
                                                    <td>{$pedido[i].NOMECLIENTE}</td>
                                                    <td>{$pedido[i].SIT}</td>
                                                    <td>{$pedido[i].CCUSTO}</td>
                                                    {if $tipoUsuario neq ""}<td>{$pedido[i].CUSTOTOTAL|number_format:2:",":"."}
                                                    </td>{/if}
                                                    <td>{$pedido[i].LUCROBRUTO|number_format:2:",":"."}</td>
                                                    <td>{$pedido[i].MARGEMLIQUIDA|number_format:2:",":"."}</td>
                                                    <td>{$pedido[i].MARKUP|number_format:2:",":"."}</td>
                                                    <td>{$pedido[i].TOTAL|number_format:2:",":"."}</td>
                                                </tr>

                                                {assign var="totalVendedor" value=$totalVendedor+$pedido[i].TOTAL}
                                                {assign var="totalGeral" value=$totalGeral+$pedido[i].TOTAL}
                                                {assign var="totalVendedorCusto" value=$totalVendedorCusto+$pedido[i].CUSTOTOTAL}
                                                {assign var="totalGeralCusto" value=$totalGeralCusto+$pedido[i].CUSTOTOTAL}
                                            {/section}

                                            {if $vendedor neq ""}
                                                <tr class="total-row">
                                                    <td colspan="8"></td>
                                                    <td>TOTAL R$</td>
                                                    <td>{$totalVendedor|number_format:2:",":"."}</td>
                                                </tr>
                                            {/if}

                                            <tr class="total-row">
                                                <td colspan="8"></td>
                                                <td><strong>TOTAL GERAL</strong></td>
                                                <td><strong>{$totalGeral|number_format:2:",":"."}</strong></td>
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
                <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
        </div>
    </div>
</section>