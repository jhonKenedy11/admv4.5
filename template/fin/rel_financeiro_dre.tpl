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

    /* Estilos específicos do DRE */
    .dre-grupo-titulo {
        background-color: #e3f2fd !important;
        font-weight: bold;
        font-size: 12px !important;
        color: #1976d2;
    }

    .dre-item-genero {
        background-color: #ffffff;
        padding-left: 30px !important;
    }

    .dre-calculo {
        background-color: #fff3e0 !important;
        font-weight: bold;
        font-size: 11px !important;
        color: #f57c00;
    }

    .dre-lucro {
        background-color: #c8e6c9 !important;
        font-weight: bold;
        font-size: 12px !important;
        color: #388e3c;
    }

    .dre-prejuizo {
        background-color: #ffcdd2 !important;
        font-weight: bold;
        font-size: 12px !important;
        color: #d32f2f;
    }

    .valor-positivo {
        color: #2e7d32;
    }

    .valor-negativo {
        color: #c62828;
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

        .dre-grupo-titulo,
        .dre-calculo,
        .dre-lucro,
        .dre-prejuizo {
            page-break-inside: avoid !important;
        }
    }
</style>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
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
                            <strong>DRE - DEMONSTRATIVO DE RESULTADO DO EXERCÍCIO</strong><br>
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
                                <th align=left style="width: 15%">Código</th>
                                <th align=left style="width: 60%">Descrição</th>
                                <th align=right style="width: 25%">Valor (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {* Variáveis de controle *}
                            {assign var="receitaOperacional" value=0}
                            {assign var="custoVariavel" value=0}
                            {assign var="custoFixo" value=0}
                            {assign var="genOld" value='99'}
                            
                            {* Primeiro loop - calcula totais *}
                            {section name=i loop=$resultado}
                                {assign var="primeiroDigito" value=$resultado[i].GENERO|truncate:1:""}
                                
                                {if $primeiroDigito eq "1"}
                                    {assign var="receitaOperacional" value=$receitaOperacional+$resultado[i].TOTAL}
                                {elseif $primeiroDigito eq "2"}
                                    {assign var="custoVariavel" value=$custoVariavel+$resultado[i].TOTAL}
                                {elseif $primeiroDigito eq "4"}
                                    {assign var="custoFixo" value=$custoFixo+$resultado[i].TOTAL}
                                {/if}
                            {/section}
                            
                            {* Segundo loop - exibe dados *}
                            {section name=i loop=$resultado}
                                {assign var="primeiroDigito" value=$resultado[i].GENERO|truncate:1:""}
                                
                                {* RECEITA OPERACIONAL *}
                                {if $primeiroDigito eq "1"}
                                    {if $genOld neq $primeiroDigito}
                                        {assign var="genOld" value=$primeiroDigito}
                                        <tr class="dre-grupo-titulo">
                                            <td colspan="2"><b>1. RECEITA OPERACIONAL TOTAL</b></td>
                                            <td align=right><b>R$ {$receitaOperacional|number_format:2:",":"."}</b></td>
                                        </tr>
                                    {/if}
                                    <tr class="dre-item-genero">
                                        <td>{$resultado[i].GENERO}</td>
                                        <td>{$resultado[i].DESCRICAO}</td>
                                        <td align=right>R$ {$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                    </tr>
                                
                                {* CUSTO VARIÁVEL *}
                                {elseif $primeiroDigito eq "2"}
                                    {if $genOld neq $primeiroDigito}
                                        {assign var="genOld" value=$primeiroDigito}
                                        <tr class="dre-grupo-titulo">
                                            <td colspan="2"><b>2. CUSTO VARIÁVEL</b></td>
                                            <td align=right><b>R$ {$custoVariavel|number_format:2:",":"."}</b></td>
                                        </tr>
                                    {/if}
                                    <tr class="dre-item-genero">
                                        <td>{$resultado[i].GENERO}</td>
                                        <td>{$resultado[i].DESCRICAO}</td>
                                        <td align=right>R$ {$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                    </tr>
                                
                                {* CUSTO FIXO *}
                                {elseif $primeiroDigito eq "4"}
                                    {if $genOld neq $primeiroDigito}
                                        {assign var="genOld" value=$primeiroDigito}
                                        
                                        {* MARGEM DE CONTRIBUIÇÃO *}
                                        {assign var="margemContribuicao" value=$receitaOperacional-$custoVariavel}
                                        <tr class="dre-calculo">
                                            <td colspan="2"><b>3. MARGEM DE CONTRIBUIÇÃO (1 - 2)</b></td>
                                            <td align=right class="{if $margemContribuicao >= 0}valor-positivo{else}valor-negativo{/if}">
                                                <b>R$ {$margemContribuicao|number_format:2:",":"."}</b>
                                            </td>
                                        </tr>
                                        
                                        {* CUSTO FIXO TÍTULO *}
                                        <tr class="dre-grupo-titulo">
                                            <td colspan="2"><b>4. CUSTO FIXO</b></td>
                                            <td align=right><b>R$ {$custoFixo|number_format:2:",":"."}</b></td>
                                        </tr>
                                    {/if}
                                    <tr class="dre-item-genero">
                                        <td>{$resultado[i].GENERO}</td>
                                        <td>{$resultado[i].DESCRICAO}</td>
                                        <td align=right>R$ {$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                    </tr>
                                
                                {* OUTROS GÊNEROS *}
                                {else}
                                    <tr class="dre-item-genero">
                                        <td>{$resultado[i].GENERO}</td>
                                        <td>{$resultado[i].DESCRICAO}</td>
                                        <td align=right>R$ {$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                    </tr>
                                {/if}
                            {/section}
                            
                            {* LUCRO OPERACIONAL *}
                            {assign var="lucroOperacional" value=$margemContribuicao-$custoFixo}
                            <tr class="{if $lucroOperacional >= 0}dre-lucro{else}dre-prejuizo{/if}">
                                <td colspan="2">
                                    <b>5. {if $lucroOperacional >= 0}LUCRO OPERACIONAL{else}PREJUÍZO OPERACIONAL{/if} (3 - 4)</b>
                                </td>
                                <td align=right>
                                    <b>R$ {$lucroOperacional|number_format:2:",":"."}</b>
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

        ws['!cols'] = [
            {ldelim}wch: 10{rdelim},
            {ldelim}wch: 60{rdelim},
            {ldelim}wch: 18{rdelim}
        ];

        if (typeof converteColunaNumeroBR === 'function') {
            converteColunaNumeroBR(ws, 2);
        }

        XLSX.utils.book_append_sheet(wb, ws, "DRE Mensal");

        var dataIni = '{$dataIni}';
        var dataFim = '{$dataFim}';
        var nomeArquivo = 'DRE_Financeiro_' + dataIni.replace(/\//g, '_') + '_a_' + dataFim.replace(/\//g, '_') + '.xlsx';
        XLSX.writeFile(wb, nomeArquivo);
    }
</script>

