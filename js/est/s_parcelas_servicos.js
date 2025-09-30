/**
 * Funções para gerenciamento de parcelas de serviços
 * Arquivo: js/est/s_parcelas_servicos.js
 */

// Variável global para armazenar as parcelas
let parcelasServicos = [];

/**
 * Calcula e gera as parcelas localmente
 * @param {number} valorTotal - Valor total para calcular as parcelas
 * @param {number} numeroParcelas - Número de parcelas
 */
function calcularParcelas(valorTotal, numeroParcelas = 1) {
    console.log('Calculando parcelas para valor:', valorTotal, 'parcelas:', numeroParcelas);
    
    if (!valorTotal || valorTotal <= 0) {
        console.warn('Valor total inválido para calcular parcelas');
        limparParcelas();
        return;
    }
    
    if (!numeroParcelas || numeroParcelas < 1) {
        numeroParcelas = 1;
    }
    
    // Gerar parcelas automaticamente
    gerarParcelasPadrao(valorTotal, numeroParcelas);
}

/**
 * Gera parcelas padrão com cálculo correto de valores
 * @param {number} valorTotal - Valor total
 * @param {number} numeroParcelas - Número de parcelas
 */
function gerarParcelasPadrao(valorTotal, numeroParcelas) {
    console.log('Gerando parcelas padrão para valor:', valorTotal, 'parcelas:', numeroParcelas);
    
    const parcelas = [];
    const dataAtual = new Date();
    
    // Calcular valor base da parcela
    const valorBaseParcela = valorTotal / numeroParcelas;
    
    // Calcular resto para distribuir nas primeiras parcelas
    const resto = valorTotal - (valorBaseParcela * numeroParcelas);
    
    for (let i = 1; i <= numeroParcelas; i++) {
        const dataVencimento = new Date(dataAtual);
        dataVencimento.setDate(dataAtual.getDate() + (i * 30)); // 30 dias entre parcelas
        
        // Calcular valor da parcela (primeira parcela recebe o resto se houver)
        let valorParcela = valorBaseParcela;
        if (i === 1 && resto > 0) {
            valorParcela += resto;
        }
        
        // Formatar valor para exibição
        const valorFormatado = valorParcela.toFixed(2).replace('.', ',');
        
        parcelas.push({
            parcela: i,
            vencimento: dataVencimento.toLocaleDateString('pt-BR'),
            valor: valorFormatado,
            valor_numerico: valorParcela,
            tipo_documento: '',
            conta_recebimento: '',
            situacao: '1', // Pendente
            obs: ''
        });
    }
    
    parcelasServicos = parcelas;
    renderizarParcelas(parcelas);
    
    console.log('Parcelas geradas:', parcelas);
}

/**
 * Renderiza as parcelas na tabela
 * @param {Array} parcelas - Array de parcelas
 */
function renderizarParcelas(parcelas) {
    console.log('Renderizando parcelas:', parcelas);
    
    let html = '';
    
    parcelas.forEach(function(parcela) {
        html += `
            <tr>
                <td class="text-center">${parcela.parcela}</td>
                <td>
                    <input class="form-control" type="text" name="venc${parcela.parcela}" 
                           value="${parcela.vencimento}" data-mask="date">
                </td>
                <td>
                    <input class="form-control text-right" type="text" name="valor${parcela.parcela}" 
                           value="R$ ${parcela.valor}" data-mask="monetario">
                </td>
                <td>
                    <select name="tipo${parcela.parcela}" class="form-control">
                        <option value="">Selecione...</option>
                        <option value="1" ${parcela.tipo_documento == '1' ? 'selected' : ''}>Boleto</option>
                        <option value="2" ${parcela.tipo_documento == '2' ? 'selected' : ''}>Cartão de Crédito</option>
                        <option value="3" ${parcela.tipo_documento == '3' ? 'selected' : ''}>Cartão de Débito</option>
                        <option value="4" ${parcela.tipo_documento == '4' ? 'selected' : ''}>PIX</option>
                        <option value="5" ${parcela.tipo_documento == '5' ? 'selected' : ''}>Transferência</option>
                        <option value="6" ${parcela.tipo_documento == '6' ? 'selected' : ''}>Dinheiro</option>
                        <option value="7" ${parcela.tipo_documento == '7' ? 'selected' : ''}>Cheque</option>
                    </select>
                </td>
                <td>
                    <select name="conta${parcela.parcela}" class="form-control">
                        <option value="">Selecione...</option>
                        <option value="1" ${parcela.conta_recebimento == '1' ? 'selected' : ''}>Conta Corrente</option>
                        <option value="2" ${parcela.conta_recebimento == '2' ? 'selected' : ''}>Poupança</option>
                        <option value="3" ${parcela.conta_recebimento == '3' ? 'selected' : ''}>Caixa</option>
                    </select>
                </td>
                <td>
                    <select name="situacao${parcela.parcela}" class="form-control">
                        <option value="">Selecione...</option>
                        <option value="1" ${parcela.situacao == '1' ? 'selected' : ''}>Pendente</option>
                        <option value="2" ${parcela.situacao == '2' ? 'selected' : ''}>Pago</option>
                        <option value="3" ${parcela.situacao == '3' ? 'selected' : ''}>Vencido</option>
                        <option value="4" ${parcela.situacao == '4' ? 'selected' : ''}>Cancelado</option>
                    </select>
                </td>
                <td>
                    <input class="form-control" type="text" name="obs${parcela.parcela}" 
                           value="${parcela.obs}" maxlength="100" placeholder="Obs...">
                </td>
            </tr>
        `;
    });
    
    $('#tbody-parcelas').html(html);
    
    // Aplicar máscaras aos campos
    aplicarMascarasParcelas();
    
    console.log('Parcelas renderizadas com sucesso');
}

/**
 * Aplica máscaras aos campos das parcelas
 */
function aplicarMascarasParcelas() {
    // Máscara para datas
    $('input[data-mask="date"]').inputmask('99/99/9999', {
        placeholder: 'dd/mm/aaaa'
    });
    
    // Máscara para valores monetários
    $('input[data-mask="monetario"]').inputmask('currency', {
        prefix: 'R$ ',
        groupSeparator: '.',
        radixPoint: ',',
        autoGroup: true,
        digits: 2,
        digitsOptional: false,
        placeholder: '0,00'
    });
}

/**
 * Obtém os dados das parcelas do formulário
 * @returns {Array} Array com os dados das parcelas
 */
function obterDadosParcelas() {
    const parcelas = [];
    
    $('#tbody-parcelas tr').each(function() {
        const parcela = $(this).find('td:first').text().trim();
        
        if (parcela && parcela !== 'Parcela') {
            const dados = {
                parcela: parseInt(parcela),
                vencimento: $(this).find(`input[name="venc${parcela}"]`).val(),
                valor: obterValorNumerico($(this).find(`input[name="valor${parcela}"]`)),
                tipo_documento: $(this).find(`select[name="tipo${parcela}"]`).val(),
                conta_recebimento: $(this).find(`select[name="conta${parcela}"]`).val(),
                situacao: $(this).find(`select[name="situacao${parcela}"]`).val(),
                obs: $(this).find(`input[name="obs${parcela}"]`).val()
            };
            
            parcelas.push(dados);
        }
    });
    
    return parcelas;
}

/**
 * Valida se as parcelas estão preenchidas corretamente
 * @returns {boolean} True se válido, False caso contrário
 */
function validarParcelas() {
    let valido = true;
    const mensagens = [];
    
    $('#tbody-parcelas tr').each(function() {
        const parcela = $(this).find('td:first').text().trim();
        
        if (parcela && parcela !== 'Parcela') {
            const vencimento = $(this).find(`input[name="venc${parcela}"]`).val();
            const valor = $(this).find(`input[name="valor${parcela}"]`).val();
            const tipo = $(this).find(`select[name="tipo${parcela}"]`).val();
            
            if (!vencimento) {
                mensagens.push(`Parcela ${parcela}: Data de vencimento é obrigatória`);
                valido = false;
            }
            
            if (!valor || parseFloat(valor.replace(/[^\d,]/g, '').replace(',', '.')) <= 0) {
                mensagens.push(`Parcela ${parcela}: Valor deve ser maior que zero`);
                valido = false;
            }
            
            if (!tipo) {
                mensagens.push(`Parcela ${parcela}: Tipo de documento é obrigatório`);
                valido = false;
            }
        }
    });
    
    if (!valido) {
        Swal.fire({
            icon: 'warning',
            title: 'Validação de Parcelas',
            html: mensagens.join('<br>'),
            confirmButtonText: 'OK'
        });
    }
    
    return valido;
}

/**
 * Limpa as parcelas
 */
function limparParcelas() {
    parcelasServicos = [];
    $('#tbody-parcelas').html('<tr><td colspan="7" class="text-center">Nenhuma parcela encontrada</td></tr>');
}

// Expor funções globalmente
window.calcularParcelas = calcularParcelas;
window.obterDadosParcelas = obterDadosParcelas;
window.validarParcelas = validarParcelas;
window.limparParcelas = limparParcelas;
