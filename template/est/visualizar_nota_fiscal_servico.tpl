<style>
    /* Reset e Configurações Gerais */
    .preview-nfs-container {
        max-width: 210mm;
        margin: 0px auto;
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    .preview-nfs-page {
        padding: 15mm;
        background: white;
    }

    /* Cabeçalho Principal */
    .nfs-header {
        border-radius: 4px;
        text-align: center;
        border: 1px solid #000;
        padding: 0px 15px 4px 15px;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .nfs-header h2 {
        margin: 5px 0;
        font-size: 20px;
        font-weight: bold;
        color: rgb(65, 108, 109);
    }

    /* Seções da NFS */
    .nfs-section {
        border: 0.5px solid #000;
        margin-bottom: 10px;
        page-break-inside: avoid;
        border-radius: 4px;
    }

    .nfs-section-title {
        background: rgb(65, 108, 109);
        color: white;
        padding: 8px 10px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .nfs-section-content {
        padding: 15px;
    }

    /* Grid de Informações */
    .nfs-info-grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .nfs-info-cell {
        padding: 8px;
        border: 1px solid #ddd;
        vertical-align: top;
        box-sizing: border-box;
    }

    .nfs-info-label {
        font-size: 9px;
        color: #666;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .nfs-info-value {
        font-size: 10px;
        color: #000;
        font-weight: normal;
    }

    .nfs-info-value.destaque {
        font-size: 12px;
        font-weight: bold;
        color: rgb(65, 108, 109);
    }

    /* Tabela de Serviços */
    .nfs-services-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
    }

    .nfs-services-table thead {
        background: #2A3F54;
        color: white;
    }

    .nfs-services-table th {
        padding: 8px;
        font-size: 9px;
        text-align: left;
        border: 1px solid #000;
        font-weight: bold;
    }

    .nfs-services-table td {
        padding: 8px;
        font-size: 9px;
        border: 1px solid #ddd;
    }

    .nfs-services-table tbody tr:nth-child(4n+1) {
        background: white;
    }

    .nfs-services-table tbody tr:nth-child(4n+2) {
        background: #f0f0f0;
    }

    .nfs-services-table tbody tr:nth-child(4n+3) {
        background: #f8f9fa;
    }

    .nfs-services-table tbody tr:nth-child(4n+4) {
        background: #e8f4f8;
    }

    /* Cálculos e Totais */
    .nfs-totals {
        margin-top: 15px;
        border-top: 2px solid #000;
        padding-top: 10px;
    }

    .nfs-total-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 10px;
    }

    .nfs-total-row.principal {
        font-size: 16px;
        font-weight: bold;
        color: rgb(65, 108, 109);
        border-top: 2px solid rgb(65, 108, 109);
        padding-top: 10px;
        margin-top: 10px;
    }

    .nfs-total-label {
        font-weight: bold;
    }

    .nfs-total-value {
        text-align: right;
    }

    /* Área de Discriminação */
    .nfs-discriminacao {
        min-height: 100px;
        padding: 10px;
        border: 1px solid #ddd;
        background: #fafafa;
        font-size: 10px;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    /* Informações Adicionais */
    .nfs-info-adicional {
        min-height: 50px;
        padding: 10px;
        border: 1px solid #ddd;
        background: #fffbeb;
        font-size: 10px;
        line-height: 1.5;
    }

    /* Rodapé */
    .nfs-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 9px;
        color: #666;
        padding: 15px;
        border-top: 1px solid #ddd;
    }

    /* Status Badge */
    .nfs-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 2px;
    }

    .nfs-status-badge.rascunho {
        background: #ffc107;
        color: #000;
    }

    .nfs-status-badge.preview {
        background: #17a2b8;
        color: white;
    }

    /* Botões de Ação */
    .nfs-actions {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-top: 2px solid #dee2e6;
        margin-top: 20px;
    }

    .nfs-actions .btn {
        margin: 0 5px;
        min-width: 120px;
    }

    /* Marca d'água de Pré-visualização */
    .nfs-watermark {
        padding-top: 10px !important;
        position: relative;
        overflow: hidden;
    }

    .nfs-watermark::before {
        content: 'PRÉ-VISUALIZAÇÃO';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 72px;
        color: rgba(0, 0, 0, 0.05);
        font-weight: bold;
        white-space: nowrap;
        pointer-events: none;
        z-index: 1;
    }

    /* Impressão */
    @media print {
        .nfs-actions {
            display: none;
        }
        
        .preview-nfs-container {
            box-shadow: none;
            max-width: 100%;
        }
        
        .nfs-section {
            page-break-inside: avoid;
            border-width: 0.5px !important;
        }
        
        .nfs-section-title {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .preview-nfs-container {
            margin: 0;
            box-shadow: none;
        }
        
        .preview-nfs-page {
            padding: 10px;
        }
        
        .nfs-info-grid {
            display: block;
        }
        
        .nfs-info-grid tr {
            display: block;
            margin-bottom: 10px;
        }
        
        .nfs-info-cell {
            display: block;
            width: 100% !important;
            box-sizing: border-box;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
        
        .nfs-services-table {
            font-size: 8px;
        }
        
        .nfs-services-table th,
        .nfs-services-table td {
            padding: 5px;
        }
    }
</style>

<!-- Container Principal da Pré-visualização -->
<div class="preview-nfs-container">
    <div class="preview-nfs-page nfs-watermark">
        
        <!-- Cabeçalho Principal -->
        <div class="nfs-header">
            <h2>NOTA FISCAL DE SERVIÇOS ELETRÔNICA - NFS-e</h2>
            <!--<p>Nota Fiscal nº <strong>{if $data.nota_fiscal.numero}{$data.nota_fiscal.numero}{else}A DEFINIR{/if}</strong> - Série: {$data.nota_fiscal.serie|default:"1"}</p>-->
            <span class="nfs-status-badge preview">PRÉ-VISUALIZAÇÃO</span>
        </div>

        <!-- Dados do Prestador -->
        <div class="nfs-section">
            <div class="nfs-section-title">
                <i class="fa fa-building"></i> DADOS DO PRESTADOR DE SERVIÇOS
            </div>
            <div class="nfs-section-content">
                <table class="nfs-info-grid">
                    <tr>
                        <td class="nfs-info-cell" style="width: 60%;">
                            <div class="nfs-info-label">Razão Social</div>
                            <div class="nfs-info-value">{$data.prestador.razao_social|upper|default:"[PRESTADOR]"}</div>
                        </td>
                        <td class="nfs-info-cell" style="width: 20%;">
                            <div class="nfs-info-label">CNPJ</div>
                            <div class="nfs-info-value">{$data.prestador.cpfcnpj|default:""}</div>
                        </td>
                    </tr>
                    {* <tr>
                        <td class="nfs-info-cell" style="width: 20%;">
                            <div class="nfs-info-label">Inscrição Municipal</div>
                            <div class="nfs-info-value">{$data.prestador.inscricao_municipal|default:"-"}</div>
                        </td>
                        <td class="nfs-info-cell" style="width: 20%;">
                            <div class="nfs-info-label">Inscrição Estadual</div>
                            <div class="nfs-info-value">{if $data.prestador.inscricao_estadual}{$data.prestador.inscricao_estadual}{else}ISENTO{/if}</div>
                        </td>
                        <td class="nfs-info-cell" style="width: 60%;">
                            <div class="nfs-info-label">E-mail</div>
                            <div class="nfs-info-value">{$data.prestador.email|default:"-"}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="nfs-info-cell" colspan="3">
                            <div class="nfs-info-label">Endereço Completo</div>
                            <div class="nfs-info-value">
                                {$data.prestador.logradouro|default:"-"}, {$data.prestador.numero_residencia|default:"-"} {if $data.prestador.complemento}- {$data.prestador.complemento}{/if}<br>
                                {$data.prestador.bairro|default:"-"} - {$data.prestador.cidade_nome|default:"-"}/{$data.prestador.uf|default:"-"} - CEP: {$data.prestador.cep|default:"-"}
                            </div>
                        </td>
                    </tr> *}
                </table>
            </div>
        </div>

        <!-- Dados do Tomador -->
        <div class="nfs-section">
            <div class="nfs-section-title">
                <i class="fa fa-user"></i> DADOS DO TOMADOR DE SERVIÇOS
            </div>
            <div class="nfs-section-content">
                <table class="nfs-info-grid">
                    <tr>
                        <td class="nfs-info-cell" style="width: 60%;">
                            <div class="nfs-info-label">Razão Social / Nome</div>
                            <div class="nfs-info-value">{$data.tomador.nome_razao_social|upper}</div>
                        </td>
                        <td class="nfs-info-cell" style="width: 20%;">
                            <div class="nfs-info-label">CPF/CNPJ</div>
                            <div class="nfs-info-value">{$data.tomador.cpfcnpj}</div>
                        </td>
                    </tr>
                    {* <tr>
                        <td class="nfs-info-cell" colspan="2">
                            <div class="nfs-info-label">Inscrição {if $data.tomador.tipo eq 'J'}Municipal{else}Estadual{/if}</div>
                            <div class="nfs-info-value">{if $data.tomador.ie}{$data.tomador.ie}{else}NÃO INFORMADO{/if}</div>
                        </td>
                    </tr> *}
                    <tr>
                        <td class="nfs-info-cell" colspan="2">
                            <div class="nfs-info-label">Endereço Completo</div>
                            <div class="nfs-info-value">
                                {$data.tomador.logradouro}, {$data.tomador.numero_residencia} {if $data.tomador.complemento}- {$data.tomador.complemento}{/if} - 
                                {$data.tomador.bairro} - {$data.tomador.cidade_nome|default:"[Cidade]"}/{$data.tomador.uf|default:"[UF]"} - CEP: {$data.tomador.cep}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>



        <!-- Discriminação dos Serviços -->
        <div class="nfs-section">
            <div class="nfs-section-title">
                <i class="fa fa-list"></i> DISCRIMINAÇÃO DOS SERVIÇOS
            </div>
            <div class="nfs-section-content">
                {if $data.itens}
                {section name=i loop=$data.itens}
                <div style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px;">
                    <div style="font-size: 10px; font-weight: bold; margin-bottom: 10px; color: rgb(65, 108, 109);">
                        Item {$smarty.section.i.iteration} - {$data.itens[i].descritivo}
                    </div>
                    
                    <table class="nfs-info-grid">
                        <tr>
                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Local de Prestação</div>
                                <div class="nfs-info-value">{$data.itens[i].descricao_local_prestacao_servico|default:"Não informado"}</div>
                            </td>
                            <td class="nfs-info-cell" colspan="3">
                                <div class="nfs-info-label">Situação Tributária</div>
                                <div class="nfs-info-value">{$data.itens[i].situacao_tributaria_desc|default:"Não informado"}</div>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Código do Serviço</div>
                                <div class="nfs-info-value">{$data.itens[i].codigo_item_lista_servico|default:"Não informado"}</div>
                            </td>

                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor do Serviço</div>
                                <div class="nfs-info-value">R$ {$data.itens[i].valor_servico|number_format:2:',':'.'}</div>
                            </td>

                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor Desconto</div>
                                <div class="nfs-info-value">R$ {$data.itens[i].valor_desconto_incondicional|default:0|number_format:2:',':'.'}</div>
                            </td>

                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor Total</div>
                                <div class="nfs-info-value destaque">R$ {$data.itens[i].valor_tributavel|number_format:2:',':'.'}</div>
                            </td>


                        </tr>

                        <tr>

                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Alíquota</div>
                                <div class="nfs-info-value">{$data.itens[i].aliquota_item_lista_servico}%</div>
                            </td>
                             
                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor ISSQN</div>
                                <div class="nfs-info-value">R$ {$data.itens[i].valor_issqn|default:0|number_format:2:',':'.'}</div>
                            </td>
                            
                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor ISSRF</div>
                                <div class="nfs-info-value">R$ {$data.itens[i].valor_issrf|default:0|number_format:2:',':'.'}</div>
                            </td>


                            <td class="nfs-info-cell" style="width: 25%;">
                                <div class="nfs-info-label">Valor Dedução</div>
                                <div class="nfs-info-value">R$ {$data.itens[i].valor_deducao|default:0|number_format:2:',':'.'}</div>
                            </td>

                        </tr>
                    </table>
                </div>
                {/section}
                {/if}
                
                <div style="margin-top: 5px;">
                    <div class="nfs-info-label">OBSERVAÇÕES NA NOTA FISCAL</div>
                    <div class="nfs-discriminacao">{if $data.nota_fiscal.observacao}{$data.nota_fiscal.observacao}{else}Nenhuma observação adicional.{/if}</div>
                </div>
            </div>
        </div>

        <!-- Informações de Retenção -->
        {if $total_retencoes > 0}
        <div class="nfs-section">
            <div class="nfs-section-title">
                <i class="fa fa-warning"></i> INFORMAÇÕES DE RETENÇÃO DE IMPOSTOS
            </div>
            <div class="nfs-section-content">
                <table class="nfs-info-grid">
                    <tr>
                        <td class="nfs-info-cell" colspan="2">
                            <div class="nfs-info-label">Tributos Retidos na Fonte</div>
                            <div class="nfs-info-value">
                                Total de retenções federais: <strong>R$ {$total_retencoes|number_format:2:',':'.'}</strong><br>
                                {if $data.nota_fiscal.valor_pis > 0}PIS: R$ {$data.nota_fiscal.valor_pis|number_format:2:',':'.'} | {/if}
                                {if $data.nota_fiscal.valor_cofins > 0}COFINS: R$ {$data.nota_fiscal.valor_cofins|number_format:2:',':'.'} | {/if}
                                {if $data.nota_fiscal.valor_inss > 0}INSS: R$ {$data.nota_fiscal.valor_inss|number_format:2:',':'.'} | {/if}
                                {if $data.nota_fiscal.valor_ir > 0}IR: R$ {$data.nota_fiscal.valor_ir|number_format:2:',':'.'} | {/if}
                                {if $data.nota_fiscal.valor_contribuicao_social > 0}CSLL: R$ {$data.nota_fiscal.valor_contribuicao_social|number_format:2:',':'.'}{/if}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {/if}

        <!-- Rodapé -->
        <div class="nfs-footer">
            <p><strong>Este documento é uma pré-visualização da Nota Fiscal de Serviços Eletrônica</strong></p>
            <p>Emitida por: {$data.prestador.razao_social|default:"[PRESTADOR]"} - CNPJ: {$data.prestador.cpfcnpj}</p>
            <p>Sistema ADM v4.5 - Gerado em {$smarty.now|date_format:"%d/%m/%Y às %H:%M:%S"}</p>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="nfs-actions">
        <button type="button" class="btn btn-danger btn-lg" onclick="cancelarPreview();">
            <i class="fa fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-warning btn-lg" onclick="imprimirPreview();">
            <i class="fa fa-print"></i> Imprimir Pré-visualização
        </button>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript">

function imprimirPreview() {
    window.print();
}

function cancelarPreview() {
    window.close();
}

// Atalhos de teclado
document.addEventListener('keydown', function(event) {
    // Ctrl + P = Imprimir
    if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
        imprimirPreview();
    }
});
</script>

