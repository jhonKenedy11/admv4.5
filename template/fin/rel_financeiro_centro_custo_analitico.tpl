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
        padding: 3px 5px !important;
        font-size: 9px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ColunaObs {
        white-space: nowrap !important;
        max-width: 100px;
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

    .totais-parciais {
        background-color: #f0f0f0 !important;
        font-weight: bold;
    }

    .totais-gerais {
        background-color: #d9d9d9 !important;
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

        .ColunaObs {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            font-size: 6.5px !important;
            max-width: 100px !important;
        }
        
        .DestacaLinha {
            border-bottom: 1px solid #ddd !important;
        }

        .DestacaLinha:hover {
            background-color: inherit !important;
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

        .totais-parciais,
        .totais-gerais {
            page-break-before: avoid !important;
            page-break-after: avoid !important;
            page-break-inside: avoid !important;
        }

        .totais-parciais {
            background-color: #f0f0f0 !important;
        }

        .totais-gerais {
            background-color: #d9d9d9 !important;
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
                            <strong>CENTRO DE CUSTO - ANALÍTICO COM RATEIO</strong><br>
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
                        <tbody>
                            {assign var="totalGeralDebito" value=0}
                            {assign var="totalGeralCredito" value=0}
                            {assign var="centroCustoAnterior" value=""}
                            {assign var="debitoCentroCusto" value=0}
                            {assign var="creditoCentroCusto" value=0}
                            {assign var="saldoCentroCusto" value=0}
                            {assign var="saldoInicialCC" value=0}
                            {assign var="primeiroRegistro" value=true}

                            {section name=i loop=$resultado}
                                
                                {* Verifica se mudou o centro de custo *}
                                {if $centroCustoAnterior != $resultado[i].CC}
                                    
                                    {* Se não for o primeiro, fecha o grupo anterior *}
                                    {if !$primeiroRegistro}
                                        {assign var="saldoFinalCC" value=$saldoInicialCC+$creditoCentroCusto-$debitoCentroCusto}
                                        <tr class="totais-parciais">
                                            <td align=right colspan="9"><b>Saldo Inicial: R$ {$saldoInicialCC|number_format:2:",":"."}</b></td>
                                            <td align=right><b>Débitos: R$ {$debitoCentroCusto|number_format:2:",":"."}</b></td>
                                            <td align=right><b>Créditos: R$ {$creditoCentroCusto|number_format:2:",":"."}</b></td>
                                            <td align=right colspan="2"><b>Saldo: R$ {$saldoFinalCC|number_format:2:",":"."}</b></td>
                                        </tr>
                                    {/if}
                                    
                                    {* Inicia novo grupo *}
                                    {assign var="centroCustoAnterior" value=$resultado[i].CC}
                                    {assign var="debitoCentroCusto" value=0}
                                    {assign var="creditoCentroCusto" value=0}
                                    {assign var="saldoInicialCC" value=$resultado[i].SALDOCC}
                                    {assign var="primeiroRegistro" value=false}
                                    
                                    {* Cabeçalho do grupo *}
                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="13"><b><big>&raquo; Centro de Custo: {$resultado[i].CC} - {$resultado[i].DESCCENTROCUSTO}</big></b></td>
                                    </tr>
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 15%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 3%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 3%">Parc</th>
                                        <th align=left class=ColunaTitulo style="width: 10%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Tipo</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Total Título</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">% Rateio</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Total Rateio</th>
                                        <th align=left class="ColunaTitulo ColunaObs" style="width: 12%">Obs</th>
                                    </tr>
                                {/if}
                                
                                {* Acumula débitos e créditos *}
                                {if $resultado[i].TIPOLANCAMENTO eq 'R'}
                                    {assign var="creditoCentroCusto" value=$creditoCentroCusto+$resultado[i].TOTALRATEIO}
                                    {assign var="totalGeralCredito" value=$totalGeralCredito+$resultado[i].TOTALRATEIO}
                                {else}
                                    {assign var="debitoCentroCusto" value=$debitoCentroCusto+$resultado[i].TOTALRATEIO}
                                    {assign var="totalGeralDebito" value=$totalGeralDebito+$resultado[i].TOTALRATEIO}
                                {/if}
                                
                                {* Linha de dados *}
                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">
                                    <td>{$resultado[i].NOME}</td>
                                    <td>{$resultado[i].DOCTO}</td>
                                    <td>{$resultado[i].SERIE}</td>
                                    <td>{$resultado[i].PARCELA}</td>
                                    <td>{$resultado[i].DESCGENERO}</td>
                                    <td>{$resultado[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].PAGAMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].TIPOLANCAMENTO_DESC}</td>
                                    <td align=right>{$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                    <td align=right>{$resultado[i].PERCENTUAL|number_format:2:",":"."}%</td>
                                    <td align=right>{$resultado[i].TOTALRATEIO|number_format:2:",":"."}</td>
                                    <td class="ColunaObs">{$resultado[i].OBS}</td>
                                </tr>

                            {sectionelse}
                                <tr>
                                    <td colspan="13" class="text-center">Não há valores cadastrados para este período</td>
                                </tr>
                            {/section}

                            {* Total do último grupo *}
                            {if !$primeiroRegistro}
                                {assign var="saldoFinalCC" value=$saldoInicialCC+$creditoCentroCusto-$debitoCentroCusto}
                                <tr class="totais-parciais">
                                    <td align=right colspan="9"><b>Saldo Inicial: R$ {$saldoInicialCC|number_format:2:",":"."}</b></td>
                                    <td align=right><b>Débitos: R$ {$debitoCentroCusto|number_format:2:",":"."}</b></td>
                                    <td align=right><b>Créditos: R$ {$creditoCentroCusto|number_format:2:",":"."}</b></td>
                                    <td align=right colspan="2"><b>Saldo: R$ {$saldoFinalCC|number_format:2:",":"."}</b></td>
                                </tr>
                            {/if}
                            
                            {* Totais gerais *}
                            {assign var="saldoGeralFinal" value=$totalGeralCredito-$totalGeralDebito}
                            <tr class="totais-gerais">
                                <td align=right colspan="9"><b><big>TOTAL GERAL</big></b></td>
                                <td align=right><b><big>Débitos: R$ {$totalGeralDebito|number_format:2:",":"."}</big></b></td>
                                <td align=right><b><big>Créditos: R$ {$totalGeralCredito|number_format:2:",":"."}</big></b></td>
                                <td align=right colspan="2"><b><big>Saldo: R$ {$saldoGeralFinal|number_format:2:",":"."}</big></b></td>
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
        link.download = 'Centro_Custo_Analitico_{$dataIni}_a_{$dataFim}.csv';
        link.click();
    }
</script>

