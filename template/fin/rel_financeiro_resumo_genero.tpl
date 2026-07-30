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
                            <strong>RESUMO POR GÊNERO</strong><br>
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
                            {assign var="totalGeral" value='0'}
                            {assign var="generoAnt" value='0'}
                            {assign var="totalParcial" value='0'}

                            {section name=i loop=$resultado}

                                {assign var="totalGeral" value=$totalGeral+$resultado[i].TOTAL}

                                {if $generoAnt eq 0}
                                    {* Primeiro registro - cria cabeçalho do grupo *}
                                    {assign var="generoAnt" value=$resultado[i].GENERO}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="11"><b><big>&raquo; {$resultado[i].DESCGENERO}</big></b></td>
                                    </tr>
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 5%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 24%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 4%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Parc</th>
                                        <th align=left class=ColunaTitulo style="width: 12%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Tipo</th>
                                        <th align=left class="ColunaTitulo ColunaObs" style="width: 13%">Obs</th>
                                        <th align=left class=ColunaTitulo style="width: 8%">Total</th>
                                    </tr>
                                {elseif $resultado[i].GENERO eq $generoAnt}
                                    {* Mesmo gênero - só acumula total *}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                {else}
                                    {* Mudou o gênero - mostra total anterior e cria novo grupo *}
                                    <tr>
                                        <td align=right class=ColunaTitulo colspan="11"><b><big>Total...: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                                    </tr>
                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="11"><b><big>&raquo; {$resultado[i].DESCGENERO}</big></b></td>
                                    </tr>
                                    {assign var="totalParcial" value='0'}
                                    {assign var="generoAnt" value=$resultado[i].GENERO}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 5%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 24%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 4%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Parc</th>
                                        <th align=left class=ColunaTitulo style="width: 12%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Tipo</th>
                                        <th align=left class="ColunaTitulo ColunaObs" style="width: 13%">Obs</th>
                                        <th align=left class=ColunaTitulo style="width: 8%">Total</th>
                                    </tr>
                                {/if}

                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">
                                    <td>{$resultado[i].DOCTO}</td>
                                    <td>{$resultado[i].NOME}</td>
                                    <td>{$resultado[i].SERIE}</td>
                                    <td>{$resultado[i].PARCELA} / {$resultado[i].TOTALPARCELAS}</td>
                                    <td>{$resultado[i].DESCGENERO}</td>
                                    <td>{$resultado[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].PAGAMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].TIPOLANCAMENTO_DESC}</td>
                                    <td class="ColunaObs">{$resultado[i].OBS}</td>
                                    <td>{$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                </tr>

                            {sectionelse}
                                <tr>
                                    <td colspan="11" class="text-center">Não há valores cadastrados para este período</td>
                                </tr>
                            {/section}

                            {* Total do último grupo *}
                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="11"><b><big>Total...: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                            </tr>
                            
                            {* Total geral *}
                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="11"><b><big>TOTAL GERAL: R${$totalGeral|number_format:2:",":"."}</big></b></td>
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

<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
    function exportarTabelaParaExcel() {
        var table = document.querySelector('.table-striped');
        if (!table) {
            alert('Tabela não encontrada!');
            return;
        }

        if (typeof XLSX === 'undefined') {
            alert('Biblioteca de exportação (XLSX) não carregada!');
            return;
        }

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet(table, { raw: true });

        if (typeof converteColunaNumeroBR === 'function') {
            converteColunaNumeroBR(ws, 10);
        }

        XLSX.utils.book_append_sheet(wb, ws, "Resumo Gênero");

        var dataIni = '{$dataIni}';
        var dataFim = '{$dataFim}';
        var nomeArquivo = 'Resumo_Genero_' +
            dataIni.replace(/\//g, '_') + '_a_' +
            dataFim.replace(/\//g, '_') + '.xlsx';

        XLSX.writeFile(wb, nomeArquivo);
    }
</script>

