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
        color: #6c757d;
        font-size: 1.5rem;
        text-align: center;
    }

    .height100 {
        background-color: #F7F7F7;
        margin: 0;
        padding: 10px;
        min-height: 100vh;
    }

    .print-container {
        display: flex;
        flex-direction: column;
    }

    .header-section {
        margin-bottom: 10px;
    }

    .dataHora {
        font-size: 9px;
    }

    .table {
        font-size: 10px;
        width: 100%;
    }

    .table th {
        font-size: 10px;
        white-space: nowrap;
    }

    .table td {
        font-size: 9px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .x_panel {
        margin-top: 5px;
    }

    .table-responsive {
        overflow-x: auto;
        max-width: 100%;
    }

    h2 {
        font-size: 14px;
        margin: 5px 0;
    }

    .saldo-positivo {
        color: #28a745;
        font-weight: bold;
    }

    .saldo-negativo {
        color: #dc3545;
        font-weight: bold;
    }

    @media print {
        @page {
            margin: 0.5cm 0.3cm;
            size: landscape;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-size: 8pt;
            margin: 0;
            padding: 0;
        }

        .height100 {
            min-height: auto !important;
            padding: 2px !important;
            background-color: white !important;
        }

        .print-container {
            width: 100%;
            margin: 0;
        }

        .header-section {
            margin-bottom: 5px !important;
            padding: 0 !important;
            page-break-after: avoid !important;
        }

        .x_panel {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            padding: 1px 2px !important;
            font-size: 7px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .table thead {
            display: table-header-group !important;
        }

        .table tbody tr {
            page-break-inside: avoid !important;
        }

        .ColunaTitulo {
            font-weight: bold !important;
            font-size: 7px !important;
        }

        .DestacaLinha {
            border-bottom: 1px solid #ddd !important;
        }

        .no-print {
            display: none !important;
        }

        .dataHora {
            font-size: 7px;
        }

        h2 {
            font-size: 9px;
            margin: 2px 0 !important;
            line-height: 1.1 !important;
        }

        img {
            max-width: 80px !important;
            max-height: 20px !important;
        }

        .totais-group {
            page-break-before: avoid !important;
            page-break-after: avoid !important;
            page-break-inside: avoid !important;
        }

        .saldo-positivo, .saldo-negativo {
            font-weight: bold !important;
        }
    }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<!-- page content -->
<div class="height100">
    <div class="print-container">
        <div class="header-section">
            <div class="right_col" role="main">
                <div class="">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" align="left" width=180 height=46 border="0">
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <h2>
                            <strong>CONSOLIDAÇÃO BANCÁRIA</strong><br>
                            Período - {$dataIni} | {$dataFim}
                        </h2>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            {if count($resultado) > 0}

                <div class="table-responsive">
                    <table class="table table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 6%">Docto</th>
                                <th style="width: 8%">Filial</th>
                                <th style="width: 25%">Pessoa</th>
                                <th style="width: 4%">Série</th>
                                <th style="width: 5%">Parc</th>
                                <th style="width: 12%">Gênero</th>
                                <th style="width: 8%">Movimento</th>
                                <th style="width: 8%">Tipo</th>
                                <th style="width: 10%; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {assign var="dataAnt" value='0'}
                            {assign var="valorDebito" value=0}
                            {assign var="valorCredito" value=0}
                            {assign var="totalDebito" value=0}
                            {assign var="totalCredito" value=0}
                            {assign var="saldoParcial" value=0}
                            {assign var="total" value=0}

                            {* Define nome do campo de referência para exibição *}
                            {if $tipoReferencia == 1}
                                {assign var="nomeCampo" value="VENCIMENTO"}
                            {elseif $tipoReferencia == 2}
                                {assign var="nomeCampo" value="EMISSÃO"}
                            {elseif $tipoReferencia == 3}
                                {assign var="nomeCampo" value="PAGAMENTO"}
                            {elseif $tipoReferencia == 4}
                                {assign var="nomeCampo" value="LANÇAMENTO"}
                            {else}
                                {assign var="nomeCampo" value="VENCIMENTO"}
                            {/if}

                            {section name=i loop=$resultado}

                                {* Pega a data do campo de referência *}
                                {if $tipoReferencia == 1}
                                    {assign var="dataAtual" value=$resultado[i].VENCIMENTO}
                                {elseif $tipoReferencia == 2}
                                    {assign var="dataAtual" value=$resultado[i].EMISSAO}
                                {elseif $tipoReferencia == 3}
                                    {assign var="dataAtual" value=$resultado[i].PAGAMENTO}
                                {elseif $tipoReferencia == 4}
                                    {assign var="dataAtual" value=$resultado[i].LANCAMENTO}
                                {else}
                                    {assign var="dataAtual" value=$resultado[i].VENCIMENTO}
                                {/if}

                                {* Se mudou a data, mostra subtotal *}
                                {if $dataAnt eq 0}
                                    {assign var="dataAnt" value=$dataAtual}
                                {elseif $dataAtual neq $dataAnt}
                                    {* Mostra subtotal da data anterior *}
                                    {assign var="saldoParcial" value=$valorCredito-$valorDebito}
                                    <tr class="totais-group">
                                        <td colspan="9" align="right">
                                            <b>Data: {$dataAnt|date_format:"%d/%m/%Y"} | 
                                            Crédito: R$ {$valorCredito|number_format:2:",":"."} | 
                                            Débito: R$ {$valorDebito|number_format:2:",":"."} | 
                                            Saldo: R$ {$saldoParcial|number_format:2:",":"."}</b>
                                        </td>
                                    </tr>

                                    {* Reinicia contadores *}
                                    {assign var="dataAnt" value=$dataAtual}
                                    {assign var="valorDebito" value=0}
                                    {assign var="valorCredito" value=0}
                                {/if}

                                {* Acumula totais *}
                                {assign var="total" value=$total+$resultado[i].TOTAL}
                                {if $resultado[i].TIPOLANCAMENTO eq "R"}
                                    {assign var="valorCredito" value=$valorCredito+$resultado[i].TOTAL}
                                    {assign var="totalCredito" value=$totalCredito+$resultado[i].TOTAL}
                                {else}
                                    {assign var="valorDebito" value=$valorDebito+$resultado[i].TOTAL}
                                    {assign var="totalDebito" value=$totalDebito+$resultado[i].TOTAL}
                                {/if}

                                {* Linha do lançamento *}
                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">
                                    <td>{$resultado[i].DOCTO}</td>
                                    <td>{$resultado[i].FILIAL}</td>
                                    <td>{$resultado[i].NOME}</td>
                                    <td>{$resultado[i].SERIE}</td>
                                    <td>{$resultado[i].PARCELA} / {$resultado[i].TOTALPARCELAS}</td>
                                    <td>{$resultado[i].DESCGENERO}</td>
                                    <td>{$dataAtual|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].TIPOLANCAMENTO_DESC}</td>
                                    <td align="right">{$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                </tr>

                            {sectionelse}
                                <tr>
                                    <td colspan="9" class="text-center">Não há lançamentos cadastrados</td>
                                </tr>
                            {/section}

                            {* Total da última data *}
                            {if count($resultado) > 0}
                                {assign var="saldoParcial" value=$valorCredito-$valorDebito}
                                <tr class="totais-group">
                                    <td colspan="9" align="right">
                                        <b>Data: {$dataAnt|date_format:"%d/%m/%Y"} | 
                                        Crédito: R$ {$valorCredito|number_format:2:",":"."} | 
                                        Débito: R$ {$valorDebito|number_format:2:",":"."} | 
                                        Saldo: R$ {$saldoParcial|number_format:2:",":"."}</b>
                                    </td>
                                </tr>

                                {* Total geral *}
                                {assign var="saldoGeral" value=$totalCredito-$totalDebito}
                                <tr class="totais-group">
                                    <td colspan="6" align="right"><b><big>TOTAL GERAL:</big></b></td>
                                    <td align="right" class="saldo-positivo"><b><big>R$ {$totalCredito|number_format:2:",":"."}</big></b></td>
                                    <td align="right" class="saldo-negativo"><b><big>R$ {$totalDebito|number_format:2:",":"."}</big></b></td>
                                    <td align="right" class="{if $saldoGeral >= 0}saldo-positivo{else}saldo-negativo{/if}">
                                        <b><big>R$ {$saldoGeral|number_format:2:",":"."}</big></b>
                                    </td>
                                </tr>
                            {/if}

                        </tbody>
                    </table>
                </div>
            {else}
                <div class="message-container">
                    <h4>Nenhum registro localizado!</h4>
                </div>
            {/if}
        </div>

        <div class="row no-print">
            <div class="col-xs-12 text-center">
                <button class="btn btn-default" onclick="window.print();">
                    <i class="fa fa-print"></i> Imprimir
                </button>
                <button class="btn btn-success" onclick="exportarTabelaParaExcel();">
                    <i class="fa fa-file-excel-o"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script type="text/javascript">
    function exportarTabelaParaExcel() {
        var table = document.querySelector('.table-striped');
        if (!table) {
            alert('Tabela não encontrada!');
            return;
        }

        var csv = '';
        var rows = table.querySelectorAll('tr');

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var cells = row.querySelectorAll('td, th');
            var rowData = [];

            for (var j = 0; j < cells.length; j++) {
                var cellText = cells[j].textContent.trim();
                if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                rowData.push(cellText);
            }

            csv += rowData.join(',') + '\n';
        }

        var blob = new Blob([csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Consolidacao_Bancaria_{$dataIni}_a_{$dataFim}.csv';
        link.click();
    }
</script>

