<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap / Tema (necessário quando abre com opcao=blank) -->
    <link href="{$bootstrap}/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$bootstrap}/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

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
        font-size: 1.2rem;
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
        font-size: 10px;
    }

    h2 {
        font-size: 16px;
        margin: 5px 0;
    }

    .table {
        font-size: 12px;
        width: 100%;
    }

    .table th {
        font-size: 12px;
        white-space: nowrap;
        background-color: #f5f5f5;
    }

    .table td {
        padding: 3px 5px !important;
        font-size: 11px;
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

    .saldo-positivo {
        color: #2e7d32;
        font-weight: bold;
    }

    .saldo-negativo {
        color: #c62828;
        font-weight: bold;
    }

    .totais-group {
        background-color: #e8e8e8 !important;
        font-weight: bold;
    }

    /* Mantém o padrão visual do sistema:
       botões seguem o tema global (bootstrap/admin). */

    @media print {
        @page {
            margin: 0.5cm 0.3cm;
            size: portrait;
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
    }
    </style>
</head>
<body>

<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>

<!-- page content -->
<div class="height100">
    <div class="print-container">
        <div class="header-section">
            <div class="right_col" role="main">
                <div class="">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" align="left" width="180" height="46" border="0">
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <h2>
                            <strong>EXTRATO FINANCEIRO - RELATÓRIO DETALHADO</strong><br>
                            {if isset($dataIni) and $dataIni neq ''}Período - {$dataIni} | {$dataFim}{/if}
                            {if isset($nome) and $nome neq ''}<br><span style="font-weight: normal;">Cliente: {$nome}</span>{/if}
                        </h2>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            {if count($lanc) > 0}
                {assign var="totalRec" value=0}
                {assign var="totalPag" value=0}

                <div class="table-responsive">
                    <table class="table table-bordered" style="margin-bottom: 0;">
                        <tbody>
                            {assign var="pessoaAnt" value=""}
                            {assign var="cnpjAnt" value=""}
                            {assign var="subRec" value=0}
                            {assign var="subPag" value=0}

                            {section name=i loop=$lanc}
                                {if $lanc[i].NOME neq $pessoaAnt}
                                    {assign var="pessoaAnt" value=$lanc[i].NOME}
                                    {assign var="cnpjAnt" value=$lanc[i].CNPJCPF}
                                    <tr>
                                        <td colspan="5" style="background-color:#f5f5f5;"><b>Pessoa:</b> {$pessoaAnt} &nbsp;&nbsp; <b>CNPJ/CPF:</b> {$cnpjAnt}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#f5f5f5;">Competência</th>
                                        <th style="background-color:#f5f5f5;">Lançamento</th>
                                        <th style="background-color:#f5f5f5;">Tipo</th>
                                        <th style="background-color:#f5f5f5;">Obs</th>
                                        <th style="background-color:#f5f5f5; text-align: right;">Valor</th>
                                    </tr>
                                {/if}

                                {if $lanc[i].TIPOLANCAMENTO eq "PAGAMENTO"}
                                    {assign var="totalPag" value=$totalPag+$lanc[i].VALOR}
                                    {assign var="subPag" value=$subPag+$lanc[i].VALOR}
                                {else}
                                    {assign var="totalRec" value=$totalRec+$lanc[i].VALOR}
                                    {assign var="subRec" value=$subRec+$lanc[i].VALOR}
                                {/if}
                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                    <td>{$lanc[i].COMPETENCIA|date_format:"%d/%m/%Y"}</td>
                                    <td>{$lanc[i].LANCAMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$lanc[i].TIPOLANCAMENTO}</td>
                                    <td class="col-obs">{$lanc[i].OBS}</td>
                                    <td align="right">
                                        {if $lanc[i].TIPOLANCAMENTO eq "PAGAMENTO"}-{else}+{/if}
                                        {$lanc[i].VALOR|number_format:2:",":"."}
                                    </td>
                                </tr>

                                {* Ao final de cada pessoa, imprime o bloco de totais dela *}
                                {assign var="nextIdx" value=$smarty.section.i.index_next}
                                {if $smarty.section.i.last or $lanc[$nextIdx].NOME neq $pessoaAnt}
                                    {assign var="subSaldo" value=$subRec-$subPag}
                                    <tr class="totais-group">
                                        <td colspan="4" align="right">Total Crédito</td>
                                        <td align="right" class="saldo-positivo">R$ {$subRec|number_format:2:",":"."}</td>
                                    </tr>
                                    <tr class="totais-group">
                                        <td colspan="4" align="right">Total Débito</td>
                                        <td align="right" class="saldo-negativo">R$ {$subPag|number_format:2:",":"."}</td>
                                    </tr>
                                    <tr class="totais-group">
                                        <td colspan="4" align="right"><big>SALDO</big></td>
                                        <td align="right" class="{if $subSaldo >= 0}saldo-positivo{else}saldo-negativo{/if}">
                                            <big>R$ {$subSaldo|number_format:2:",":"."}</big>
                                        </td>
                                    </tr>
                                    {assign var="subRec" value=0}
                                    {assign var="subPag" value=0}
                                {/if}
                            {/section}

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

        if (typeof XLSX === 'undefined') {
            alert('Biblioteca de exportação (XLSX) não carregada!');
            return;
        }

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet(table, { raw: true });

        if (typeof converteColunaNumeroBR === 'function') {
            converteColunaNumeroBR(ws, 8);
        }

        XLSX.utils.book_append_sheet(wb, ws, "Extrato Detalhado");

        var dataIni = '{$dataIni|default:""}';
        var dataFim = '{$dataFim|default:""}';
        var nomeArquivo = 'Extrato_Detalhado_' +
            (dataIni ? dataIni.replace(/\//g, '_') : 'sem_data') +
            '_a_' +
            (dataFim ? dataFim.replace(/\//g, '_') : 'sem_data') +
            '.xlsx';

        XLSX.writeFile(wb, nomeArquivo);
    }
</script>

</body>
</html>
