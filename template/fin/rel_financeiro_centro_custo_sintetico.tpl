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
        font-size: 11px;
        width: 100%;
    }

    .table th {
        font-size: 11px;
        white-space: nowrap;
        background-color: #f5f5f5;
    }

    .table td {
        padding: 8px 5px !important;
        font-size: 10px;
        white-space: nowrap;
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

    .linha-centro-custo {
        background-color: #ffffff;
    }

    .linha-centro-custo:hover {
        background-color: #f0f0f0;
    }

    .totais-gerais {
        background-color: #d9d9d9 !important;
        font-weight: bold;
        font-size: 11px !important;
    }

    .text-right {
        text-align: right;
    }

    @media print {
        @page {
            margin: 0.5cm 0.5cm;
            size: portrait;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-size: 10pt;
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
            margin-bottom: 8px !important;
            padding: 0 !important;
            page-break-after: avoid !important;
        }

        .header-section .col-md-4,
        .header-section .col-md-5,
        .header-section .col-md-3 {
            float: left;
            padding: 2px !important;
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
            padding: 3px 4px !important;
            font-size: 9px !important;
        }

        .table thead {
            display: table-header-group !important;
        }

        .table tbody tr {
            page-break-inside: avoid !important;
        }

        .no-print {
            display: none !important;
        }

        .dataHora {
            font-size: 8px;
        }

        h2 {
            font-size: 10px;
            margin: 2px 0 !important;
            line-height: 1.2 !important;
        }

        img {
            max-width: 100px !important;
            max-height: 25px !important;
        }

        .totais-gerais {
            page-break-before: avoid !important;
            page-break-inside: avoid !important;
            background-color: #d9d9d9 !important;
        }

        .linha-centro-custo {
            background-color: #ffffff !important;
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
                            <strong>CENTRO DE CUSTO - SINTÉTICO</strong><br>
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
                    <table class="table table-bordered" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th align=center style="width: 10%">Código</th>
                                <th align=left style="width: 30%">Centro de Custo</th>
                                <th align=center style="width: 15%">Saldo Inicial</th>
                                <th align=center style="width: 15%">Débitos</th>
                                <th align=center style="width: 15%">Créditos</th>
                                <th align=center style="width: 15%">Saldo Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            {assign var="totalGeralDebito" value=0}
                            {assign var="totalGeralCredito" value=0}
                            {assign var="totalSaldoInicial" value=0}
                            {assign var="totalSaldoFinal" value=0}
                            {assign var="centroCustoAnterior" value=""}
                            {assign var="debitoCentroCusto" value=0}
                            {assign var="creditoCentroCusto" value=0}
                            {assign var="saldoInicialCC" value=0}
                            {assign var="codigoCC" value=""}
                            {assign var="nomeCC" value=""}
                            
                            {* Array para armazenar os dados consolidados por centro de custo *}
                            {assign var="dadosConsolidados" value=array()}

                            {section name=i loop=$resultado}
                                
                                {* Verifica se mudou o centro de custo *}
                                {if $centroCustoAnterior != $resultado[i].CC}
                                    
                                    {* Se não for o primeiro, salva os dados do anterior *}
                                    {if $centroCustoAnterior != ""}
                                        {assign var="saldoFinalCC" value=$saldoInicialCC+$creditoCentroCusto-$debitoCentroCusto}
                                        
                                        <tr class="linha-centro-custo">
                                            <td align=center>{$codigoCC}</td>
                                            <td>{$nomeCC}</td>
                                            <td align=right>R$ {$saldoInicialCC|number_format:2:",":"."}</td>
                                            <td align=right>R$ {$debitoCentroCusto|number_format:2:",":"."}</td>
                                            <td align=right>R$ {$creditoCentroCusto|number_format:2:",":"."}</td>
                                            <td align=right>R$ {$saldoFinalCC|number_format:2:",":"."}</td>
                                        </tr>
                                        
                                        {assign var="totalSaldoInicial" value=$totalSaldoInicial+$saldoInicialCC}
                                        {assign var="totalSaldoFinal" value=$totalSaldoFinal+$saldoFinalCC}
                                    {/if}
                                    
                                    {* Inicia novo grupo *}
                                    {assign var="centroCustoAnterior" value=$resultado[i].CC}
                                    {assign var="codigoCC" value=$resultado[i].CC}
                                    {assign var="nomeCC" value=$resultado[i].DESCCENTROCUSTO}
                                    {assign var="debitoCentroCusto" value=0}
                                    {assign var="creditoCentroCusto" value=0}
                                    {assign var="saldoInicialCC" value=$resultado[i].SALDOCC}
                                {/if}
                                
                                {* Acumula débitos e créditos *}
                                {if $resultado[i].TIPOLANCAMENTO eq 'R'}
                                    {assign var="creditoCentroCusto" value=$creditoCentroCusto+$resultado[i].TOTALRATEIO}
                                    {assign var="totalGeralCredito" value=$totalGeralCredito+$resultado[i].TOTALRATEIO}
                                {else}
                                    {assign var="debitoCentroCusto" value=$debitoCentroCusto+$resultado[i].TOTALRATEIO}
                                    {assign var="totalGeralDebito" value=$totalGeralDebito+$resultado[i].TOTALRATEIO}
                                {/if}

                            {/section}

                            {* Imprime o último centro de custo *}
                            {if $centroCustoAnterior != ""}
                                {assign var="saldoFinalCC" value=$saldoInicialCC+$creditoCentroCusto-$debitoCentroCusto}
                                
                                <tr class="linha-centro-custo">
                                    <td align=center>{$codigoCC}</td>
                                    <td>{$nomeCC}</td>
                                    <td align=right>R$ {$saldoInicialCC|number_format:2:",":"."}</td>
                                    <td align=right>R$ {$debitoCentroCusto|number_format:2:",":"."}</td>
                                    <td align=right>R$ {$creditoCentroCusto|number_format:2:",":"."}</td>
                                    <td align=right>R$ {$saldoFinalCC|number_format:2:",":"."}</td>
                                </tr>
                                
                                {assign var="totalSaldoInicial" value=$totalSaldoInicial+$saldoInicialCC}
                                {assign var="totalSaldoFinal" value=$totalSaldoFinal+$saldoFinalCC}
                            {/if}
                            
                            {* Totais gerais *}
                            <tr class="totais-gerais">
                                <td align=center colspan="2"><b>TOTAL GERAL</b></td>
                                <td align=right><b>R$ {$totalSaldoInicial|number_format:2:",":"."}</b></td>
                                <td align=right><b>R$ {$totalGeralDebito|number_format:2:",":"."}</b></td>
                                <td align=right><b>R$ {$totalGeralCredito|number_format:2:",":"."}</b></td>
                                <td align=right><b>R$ {$totalSaldoFinal|number_format:2:",":"."}</b></td>
                            </tr>

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
        var table = document.querySelector('.table-bordered');
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
        link.download = 'Centro_Custo_Sintetico_{$dataIni}_a_{$dataFim}.csv';
        link.click();
    }
</script>

