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
            max-width: 80px !important;
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

        .totais-group {
            page-break-before: avoid !important;
            page-break-after: avoid !important;
            page-break-inside: avoid !important;
            background-color: #e8e8e8 !important;
        }

        .table tbody tr.ColunaTitulo {
            page-break-before: avoid !important;
            page-break-after: avoid !important;
        }

        .table tbody tr:nth-child(-n+3) {
            page-break-inside: avoid !important;
            page-break-after: avoid !important;
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
                            <strong>LANÇAMENTOS POR DATA DE ENTREGA</strong><br>
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
                            {assign var="totalGeral" value='0'}
                            {assign var="totalReceitas" value='0'}
                            {assign var="totalDespesas" value='0'}
                            {assign var="dataAnt" value='0'}
                            {assign var="totalParcial" value='0'}
                            
                            {* Define nome do campo de referência para exibição *}
                            {assign var="nomeCampo" value="DATA ENTREGA"}

                            {section name=i loop=$resultado}

                                {assign var="totalGeral" value=$totalGeral+$resultado[i].TOTAL}
                                
                                {* Soma receitas e despesas *}
                                {if $resultado[i].TIPOLANCAMENTO eq 'R'}
                                    {assign var="totalReceitas" value=$totalReceitas+$resultado[i].TOTAL}
                                {else}
                                    {assign var="totalDespesas" value=$totalDespesas+$resultado[i].TOTAL}
                                {/if}
                                
                                {* Pega a data de entrega do pedido *}
                                {assign var="dataAtual" value=$resultado[i].DATAENTREGA}

                                {if $dataAnt eq 0}
                                    {* Primeiro registro - cria cabeçalho do grupo *}
                                    {assign var="dataAnt" value=$dataAtual}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="14"><b><big>&raquo; {$nomeCampo}: {if $dataAtual}{$dataAtual|date_format:"%d/%m/%Y"}{else}SEM DATA{/if}</big></b></td>
                                    </tr>
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 3%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 2%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 12%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Filial</th>
                                        <th align=left class=ColunaTitulo style="width: 8%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Prazo Entrega</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Data Entrega</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Situação Ped</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Situação Pgto</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Total</th>
                                    </tr>
                                {elseif $dataAtual eq $dataAnt}
                                    {* Mesma data - só acumula total *}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                {else}
                                    {* Mudou a data - mostra total anterior e cria novo grupo *}
                                    <tr class="totais-group">
                                        <td align=right class=ColunaTitulo colspan="14"><b><big>Total...: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                                    </tr>
                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="14"><b><big>&raquo; {$nomeCampo}: {if $dataAtual}{$dataAtual|date_format:"%d/%m/%Y"}{else}SEM DATA{/if}</big></b></td>
                                    </tr>
                                    {assign var="totalParcial" value='0'}
                                    {assign var="dataAnt" value=$dataAtual}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 3%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 2%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 12%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Filial</th>
                                        <th align=left class=ColunaTitulo style="width: 8%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Prazo Entrega</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Data Entrega</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Situação Ped</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Situação Pgto</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Total</th>
                                    </tr>
                                {/if}

                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">
                                    <td>{$resultado[i].DOCTO}</td>
                                    <td>{$resultado[i].SERIE}</td>
                                    <td>{$resultado[i].NOME}</td>
                                    <td>{$resultado[i].FILIAL}</td>
                                    <td>{$resultado[i].DESCGENERO}</td>
                                    <td>{$resultado[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{if $resultado[i].PAGAMENTO}{$resultado[i].PAGAMENTO|date_format:"%d/%m/%Y"}{else}-{/if}</td>
                                    <td>{$resultado[i].PRAZOENTREGA}</td>
                                    <td>{if $resultado[i].DATAENTREGA}{$resultado[i].DATAENTREGA|date_format:"%d/%m/%Y"}{else}-{/if}</td>
                                    <td>{$resultado[i].SITUACAOPED}</td>
                                    <td>{$resultado[i].SITUACAOPGTO}</td>
                                    <td>{$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                </tr>

                            {sectionelse}
                                <tr>
                                    <td colspan="14" class="text-center">Não há valores cadastrados para este período</td>
                                </tr>
                            {/section}

                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="14"><b><big>Total do Período: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                            </tr>
                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="14"><b><big>TOTAL GERAL: R${$totalGeral|number_format:2:",":"."}</big></b></td>
                            </tr>
                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="14">
                                    <b>Total Receitas: R${$totalReceitas|number_format:2:",":"."} | Total Despesas: R${$totalDespesas|number_format:2:",":"."}</b>
                                </td>
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
                // Remove caracteres especiais e escapa vírgulas/aspas
                cellText = cellText.replace(/[»&raquo;]/g, '').trim();
                if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                rowData.push(cellText);
            }

            csv += rowData.join(',') + '\n';
        }

        // Adiciona BOM para UTF-8
        var BOM = '\uFEFF';
        var blob = new Blob([BOM + csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Financeiro_DataEntrega_{$dataIni}_a_{$dataFim}.csv';
        link.click();
    }
</script>

