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
        height: auto;
        min-height: 100vh;
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
        width: 100%;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px !important;
        page-break-inside: avoid;
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

    .date-header {
        background-color: #f0f0f0;
        page-break-after: avoid;
    }

    @media print {
        @page {
            size: auto;
            margin: 5mm;
        }

        body {
            padding: 15px;
            background-color: white;
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
            min-height: 0;
        }

        .table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
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
                <div>
                    <h2>
                        <strong>PEDIDO VENDA FATURA</strong><br>
                        Período - {$dataIni} | {$dataFim}
                    </h2>
                </div>
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
                                                <th>EMISSAO</th>
                                                <th>PED</th>
                                                <th>VENCIMENTO</th>
                                                <th>CLIENTE</th>
                                                <th>VALOR</th>
                                                <th>TIPO DOCTO</th>
                                                <th>MODO PGTO/REC</th>
                                                <th>TOTAL</th>
                                                <th>SITUACAO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {assign var="dia" value=""}
                                            {assign var="totalDia" value=0}
                                            {assign var="totalDiaCusto" value=0}
                                            {section name=i loop=$pedido}

                                                {assign var="totalCusto" value=$totalCusto+$pedido[i].CUSTOTOTAL}
                                                {assign var="total" value=$total+$pedido[i].TOTAL}
                                                {assign var="quant" value=$quant+$pedidoItem[i].QUANTIDADE}

                                                {if $pedido[i].EMISSAO neq $dia}
                                                    {if $dia neq ""}
                                                        <tr class="total-row">
                                                            <td colspan="7">Subtotal do dia</td>
                                                            <td>R$ {$totalDia|number_format:2:",":"."}</td>
                                                            <td></td>
                                                        </tr>
                                                        {assign var="totalDia" value=0}
                                                        {assign var="totalDiaCusto" value=0}
                                                    {/if}
                                                    <tr class="date-header">
                                                        <th colspan="9">
                                                            {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                        </th>
                                                    </tr>
                                                    {assign var="dia" value=$pedido[i].EMISSAO}
                                                {/if}

                                                {assign var="totalDia" value=$totalDia+$pedido[i].TOTAL}
                                                {assign var="totalDiaCusto" value=$totalDiaCusto+$pedido[i].CUSTOTOTAL}

                                                {section name=k loop=$pedidoItem}
                                                    {if $pedido[i].ID eq $pedidoItem[k].ID}
                                                        <tr>
                                                            <td>{$pedidoItem[k].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                                            <td>{$pedidoItem[k].ID}</td>
                                                            <td>{$pedidoItem[k].VENC|date_format:"%d/%m/%Y"}</td>
                                                            <td>{$pedidoItem[k].NOMECLIENTE}</td>
                                                            <td>R$ {$pedidoItem[k].ORIGINAL|number_format:2:",":"."}</td>
                                                            <td>{$pedidoItem[k].TPDOCTO}</td>
                                                            <td>{$pedidoItem[k].MODOPAG}</td>
                                                            <td>R$ {$pedidoItem[k].TOTAL_FAT|number_format:2:",":"."}</td>
                                                            <td>{$pedidoItem[k].SITUACAOPAG}</td>
                                                        </tr>
                                                    {/if}
                                                {/section}

                                            {/section}

                                            {if $dia neq ""}
                                                <tr class="total-row">
                                                    <td colspan="7">Subtotal do dia</td>
                                                    <td>R$ {$totalDia|number_format:2:",":"."}</td>
                                                    <td></td>
                                                </tr>
                                            {/if}

                                            <tr class="total-row">
                                                <td colspan="7">TOTAL GERAL</td>
                                                <td>R$ {$total|number_format:2:",":"."}</td>
                                                <td></td>
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