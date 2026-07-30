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

    .proj-cell { white-space: normal !important; word-break: break-word; max-width: 400px; }

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
            border: 1px solid #000 !important;
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

        .print-container {
            page-break-inside: avoid !important;
            orphans: 0 !important;
            widows: 0 !important;
        }

        .height100 {
            page-break-before: auto !important;
            page-break-after: avoid !important;
        }

        .header-section + .x_panel {
            page-break-before: avoid !important;
        }

        table tbody tr {
            page-break-inside: avoid !important;
        }

        * {
            box-shadow: none !important;
            text-shadow: none !important;
        }
    }
</style>

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
                            <strong>RELATÓRIO DE OBRAS</strong><br>
                            <strong>{$dataImp}</strong>
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
                <div class="table-responsive">
                    <table class="table table-striped" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 30%">Cliente</th>
                                <th style="width: 10%">CNPJ/CPF</th>
                                <th style="width: 40%">Obras</th>
                                <th style="width: 10%">Responsável</th>
                                <th style="width: 10%">CEP</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $lanc as $item}
                                <tr>
                                    <td>{$item.NOME}</td>
                                    <td>{$item.CNPJCPF}</td>
                                    <td class="proj-cell">{$item.PROJETOS|replace:'; ':'<br/>' nofilter}</td>
                                    <td>{$item.RESPONSAVEL}</td>
                                    <td>{$item.CEP}</td>
                                </tr>
                            {/foreach}
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
                <button class="btn btn-default btn-sm" onclick="window.print();">
                    <i class="fa fa-print"></i> Imprimir
                </button>
                <button class="btn btn-success btn-sm" onclick="exportarTabelaParaExcel();">
                    <i class="fa fa-file-excel-o"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript">
      function exportarTabelaParaExcel() {
            var table = document.querySelector('.table-striped');
            if (!table) { alert('Tabela não encontrada!'); return; }
            if (typeof XLSX === 'undefined') { alert('Biblioteca de exportação (XLSX) não carregada!'); return; }
            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.table_to_sheet(table, { raw: true });
            XLSX.utils.book_append_sheet(wb, ws, "Obras");
            var dataImp = '{$dataImp}';
            var nomeArquivo = 'Relatorio_Obras_' + dataImp.replace(/\//g, '_') + '.xlsx';
            XLSX.writeFile(wb, nomeArquivo);
      }
</script>

