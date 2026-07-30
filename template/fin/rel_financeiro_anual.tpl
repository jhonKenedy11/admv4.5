<style>
    .ColunaTitulo { font-weight: bold; }
    .ColunaObs { max-width: 120px; overflow: hidden; text-overflow: ellipsis; }
    .totais-group { background-color: #f5f5f5 !important; font-weight: bold; }
    @media print { .no-print { display: none !important; } }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>

<!-- page content (visual igual ao FIN_LANCAMENTO - consulta_dre_anual) -->
<div class="right_col" role="main">
    <div class="">
        <div class="x_panel">
            <div class="x_content">
                <section class="content invoice">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img src="images/logo.png" align="right" width="180" height="45" border="0"></i>
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span> DRE Financeiro - Anual</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataIni} - Fim: {$dataFim}</h2>
                        </div>
                    </div>

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table">
            {if count($resultado) > 0}
                    <table class="table table-striped" style="margin-bottom: 0;" id="tabelaRelatorio">
                        <thead>
                            {assign var="totalGeral" value='0'}
                            {assign var="totalReceitas" value='0'}
                            {assign var="totalDespesas" value='0'}
                            {assign var="anoAnt" value='0'}
                            {assign var="totalParcial" value='0'}
                            
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

                                {assign var="totalGeral" value=$totalGeral+$resultado[i].TOTAL}
                                
                                {* Soma receitas e despesas *}
                                {if $resultado[i].TIPOLANCAMENTO eq 'R'}
                                    {assign var="totalReceitas" value=$totalReceitas+$resultado[i].TOTAL}
                                {else}
                                    {assign var="totalDespesas" value=$totalDespesas+$resultado[i].TOTAL}
                                {/if}
                                
                                {* Pega o ano do campo de referência *}
                                {if $tipoReferencia == 1}
                                    {assign var="anoAtual" value=$resultado[i].ANO}
                                {elseif $tipoReferencia == 2}
                                    {assign var="anoAtual" value=$resultado[i].ANO}
                                {elseif $tipoReferencia == 3}
                                    {assign var="anoAtual" value=$resultado[i].ANO}
                                {elseif $tipoReferencia == 4}
                                    {assign var="anoAtual" value=$resultado[i].ANO}
                                {else}
                                    {assign var="anoAtual" value=$resultado[i].ANO}
                                {/if}

                                {if $anoAnt eq 0}
                                    {* Primeiro registro - cria cabeçalho do grupo *}
                                    {assign var="anoAnt" value=$anoAtual}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="13"><b><big>&raquo; ANO: {$anoAtual}</big></b></td>
                                    </tr>
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 2%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Filial</th>
                                        <th align=left class=ColunaTitulo style="width: 15%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 3%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 4%">Parc</th>
                                        <th align=left class=ColunaTitulo style="width: 10%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Tipo</th>
                                        <th align=left class="ColunaTitulo ColunaObs" style="width: 12%">Obs</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Usuário</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Total</th>
                                    </tr>
                                {elseif $anoAtual eq $anoAnt}
                                    {* Mesmo ano - só acumula total *}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}

                                {else}
                                    {* Mudou o ano - mostra total anterior e cria novo grupo *}
                                    <tr>
                                        <td align=right class=ColunaTitulo colspan="13"><b><big>Total do Ano {$anoAnt}: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                                    </tr>
                                    <tr>
                                        <td align=left class=ColunaTitulo colspan="13"><b><big>&raquo; ANO: {$anoAtual}</big></b></td>
                                    </tr>
                                    {assign var="totalParcial" value='0'}
                                    {assign var="anoAnt" value=$anoAtual}
                                    {assign var="totalParcial" value=$totalParcial+$resultado[i].TOTAL}
                                    <tr>
                                        <th align=left class=ColunaTitulo style="width: 2%">Docto</th>
                                        <th align=left class=ColunaTitulo style="width: 7%">Filial</th>
                                        <th align=left class=ColunaTitulo style="width: 15%">Pessoa</th>
                                        <th align=left class=ColunaTitulo style="width: 3%">Série</th>
                                        <th align=left class=ColunaTitulo style="width: 4%">Parc</th>
                                        <th align=left class=ColunaTitulo style="width: 10%">Gênero</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Emissão</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Vencimento</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Pagamento</th>
                                        <th align=left class=ColunaTitulo style="width: 5%">Tipo</th>
                                        <th align=left class="ColunaTitulo ColunaObs" style="width: 12%">Obs</th>
                                        <th align=left class=ColunaTitulo style="width: 8%">Usuário</th>
                                        <th align=left class=ColunaTitulo style="width: 6%">Total</th>
                                    </tr>
                                {/if}

                                <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}" class="DestacaLinha">
                                    <td>{$resultado[i].DOCTO}</td>
                                    <td>{$resultado[i].FILIAL}</td>
                                    <td>{if isset($resultado[i].NOME) && $resultado[i].NOME}{$resultado[i].NOME}{elseif isset($resultado[i].NOMEREDUZIDO)}{$resultado[i].NOMEREDUZIDO}{else}{$resultado[i].PESSOAID}{/if}</td>
                                    <td>{$resultado[i].SERIE}</td>
                                    <td>{$resultado[i].PARCELA} / {$resultado[i].TOTALPARCELAS}</td>
                                    <td>{$resultado[i].DESCGENERO}</td>
                                    <td>{$resultado[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].VENCIMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].PAGAMENTO|date_format:"%d/%m/%Y"}</td>
                                    <td>{$resultado[i].TIPOLANCAMENTO}</td>
                                    <td class="ColunaObs">{$resultado[i].OBS}</td>
                                    <td>{if isset($resultado[i].NOMEREDUZIDO_INSERT)}{$resultado[i].NOMEREDUZIDO_INSERT}{elseif isset($resultado[i].NOMEREDUZIDO)}{$resultado[i].NOMEREDUZIDO}{else}-{/if}</td>
                                    <td>{$resultado[i].TOTAL|number_format:2:",":"."}</td>
                                </tr>

                            {sectionelse}
                                <tr>
                                    <td colspan="13" class="text-center">Não há valores cadastrados para este período</td>
                                </tr>
                            {/section}

                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="13"><b><big>Total do Ano {$anoAnt}: R${$totalParcial|number_format:2:",":"."}</big></b></td>
                            </tr>
                            <tr class="totais-group">
                                <td align=right class=ColunaTitulo colspan="13"><b><big>TOTAL GERAL: R${$totalGeral|number_format:2:",":"."}</big></b></td>
                            </tr>
                        </tbody>
                    </table>
            {else}
                <div class="alert alert-info">Nenhum registro localizado!</div>
            {/if}
                        </div>
                    </div>

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            <button class="btn btn-success" onclick="exportarTabelaParaXLS();"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script type="text/javascript">
    function exportarTabelaParaXLS() {
        var table = document.getElementById('tabelaRelatorio');
        if (!table) {
            alert('Tabela não encontrada!');
            return;
        }

        // Criar workbook
        var wb = XLSX.utils.book_new();
        
        // Converter tabela para worksheet
        var ws = XLSX.utils.table_to_sheet(table);
        
        // Ajustar largura das colunas
        var colWidths = [
            {ldelim}wch: 8{rdelim},   // Docto
            {ldelim}wch: 15{rdelim}, // Filial
            {ldelim}wch: 30{rdelim}, // Pessoa
            {ldelim}wch: 8{rdelim},  // Série
            {ldelim}wch: 8{rdelim},  // Parc
            {ldelim}wch: 20{rdelim}, // Gênero
            {ldelim}wch: 12{rdelim}, // Emissão
            {ldelim}wch: 12{rdelim}, // Vencimento
            {ldelim}wch: 12{rdelim}, // Pagamento
            {ldelim}wch: 10{rdelim}, // Tipo
            {ldelim}wch: 30{rdelim}, // Obs
            {ldelim}wch: 15{rdelim}, // Usuário
            {ldelim}wch: 12{rdelim}  // Total
        ];
        ws['!cols'] = colWidths;
        
        // Adicionar worksheet ao workbook
        XLSX.utils.book_append_sheet(wb, ws, "DRE Anual");
        
        // Gerar nome do arquivo
        var dataIni = '{$dataIni}';
        var dataFim = '{$dataFim}';
        var nomeArquivo = 'Financeiro_Lancamentos_Anuais_' + dataIni.replace(/\//g, '_') + '_a_' + dataFim.replace(/\//g, '_') + '.xlsx';
        
        // Salvar arquivo
        XLSX.writeFile(wb, nomeArquivo);
    }
</script>
