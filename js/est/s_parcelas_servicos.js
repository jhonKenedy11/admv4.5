/**
 * Funções para gerenciamento de parcelas de serviços
 * Arquivo: js/est/s_parcelas_servicos.js
 */

// Variável global para armazenar as parcelas
let parcelasServicos = [];

/**
 * Calcula e gera as parcelas localmente baseado no data-value do combo
 * @param {number} valorTotal - Valor total para calcular as parcelas
 * @param {number} numeroParcelas - Número de parcelas (obtido do data-value)
 * @param {string} descricao - Descrição da opção para extrair dias das parcelas
 */
function calcularParcelas(valorTotal, numeroParcelas = 1, descricao = '') {
    
    if (!valorTotal || valorTotal <= 0) {
        console.warn('Valor total inválido para calcular parcelas');
        limparParcelas();
        return;
    }
    
    // Validar número de parcelas
    if (!numeroParcelas || numeroParcelas < 1 || isNaN(numeroParcelas)) {
        console.warn('Número de parcelas inválido, usando 1 como padrão');
        numeroParcelas = 1;
    }
    
    // Extrair dias das parcelas da descrição
    const diasParcelas = extrairDiasParcelas(descricao);
    
    // Gerar parcelas automaticamente
    gerarParcelasPadrao(valorTotal, numeroParcelas, diasParcelas);
}

/**
 * Gera parcelas padrão com cálculo correto de valores e datas baseadas nos dias
 * @param {number} valorTotal - Valor total
 * @param {number} numeroParcelas - Número de parcelas
 * @param {Array} diasParcelas - Array com os dias de cada parcela (ex: [7, 14])
 */
function gerarParcelasPadrao(valorTotal, numeroParcelas, diasParcelas = []) {
    
    const parcelas = [];
    const dataAtual = new Date();
    
    // Calcular valor base da parcela (arredondar para 2 casas decimais)
    const valorBaseParcela = Math.floor((valorTotal / numeroParcelas) * 100) / 100;
    
    // Calcular resto para distribuir na última parcela
    const valorTotalCalculado = valorBaseParcela * numeroParcelas;
    const resto = Math.round((valorTotal - valorTotalCalculado) * 100) / 100;
    
    for (let i = 1; i <= numeroParcelas; i++) {

        const dataVencimento = new Date(dataAtual);
        
        // Calcular data de vencimento baseada nos dias extraídos
        if (diasParcelas.length > 0) {
            let diaVencimento;
            
            if (diasParcelas.length === 1) {
                // Caso especial: apenas um dia especificado (ex: "BOLETO 21")
                // Multiplicar o dia pela parcela (21, 42, 63, etc.)
                diaVencimento = diasParcelas[0] * i;
            } else {
                // Múltiplos dias especificados (ex: "BOLETO 7/14")
                // Usar o dia correspondente à parcela, ou o último se não houver
                diaVencimento = diasParcelas[i - 1] || diasParcelas[diasParcelas.length - 1] || 30;
            }
            
            dataVencimento.setDate(dataAtual.getDate() + diaVencimento);
        } else {
            // Fallback: usar 30 dias multiplicado pela parcela
            dataVencimento.setDate(dataAtual.getDate() + (i * 30));
        }
        
        // Calcular valor da parcela (última parcela recebe o resto se houver)
        let valorParcela = valorBaseParcela;
        if (i === numeroParcelas && resto !== 0) {
            valorParcela += resto;
        }
        
        // Garantir que o valor seja positivo e tenha 2 casas decimais
        valorParcela = Math.max(0, Math.round(valorParcela * 100) / 100);
        
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
}

/**
 * Monta as opções de situação do pagamento
 * @param {Array} situacoes - Array contendo objetos com ID e DESCRICAO
 * @returns {string} HTML das opções do select
 */
function montarSituacaoPagamento(situacoes) {

    let options = '';
    
    situacoes.forEach(function(situacao) {
        options += `<option value="${situacao.ID}">${situacao.DESCRICAO}</option>`;
    });
    
    return options;
}

/**
 * Monta as opções de conta de recebimento
 * @param {Array} contas - Array contendo objetos com ID e DESCRICAO
 * @returns {string} HTML das opções do select
 */
function montarContaRecebimento(contas) {
    
    let options = '';
    
    contas.forEach(function(conta) {
        options += `<option value="${conta.ID}">${conta.DESCRICAO}</option>`;
    });
    
    return options;
}

/**
 * Monta as opções de tipo de documento
 * @param {Array} tipos - Array contendo objetos com ID e DESCRICAO
 * @returns {string} HTML das opções do select
 */
function montarTipoDocumento(tipos) {
    
    let options = '';
    
    tipos.forEach(function(tipo) {
        options += `<option value="${tipo.ID}">${tipo.DESCRICAO}</option>`;
    });
    
    return options;
}

/**
 * Renderiza as parcelas na tabela
 * @param {Array} parcelas - Array de parcelas
 */
function renderizarParcelas(parcelas) {
    // Obter as situações de pagamento, contas e tipos de documento da variável global exportada do PHP
    const situacoes = window.situacoesPagamento || null;
    const contas = window.contasRecebimento || null;
    const tipos_documento = window.tiposDocumento || null;
    
    let html = '';
    
    parcelas.forEach(function(parcela) {
        html += `
            <tr>
                <td class="text-center">${parcela.parcela}</td>
                <td>
                    <input class="form-control input-sm" type="text" name="venc${parcela.parcela}" 
                           value="${parcela.vencimento}" data-mask="date">
                </td>
                <td>
                    <input class="form-control text-right input-sm" type="text" name="valor${parcela.parcela}" 
                           value="R$ ${parcela.valor}" data-mask="monetario">
                </td>
                <td>
                    <select name="tipo${parcela.parcela}" class="form-control input-sm">
                        ${tipos_documento ? montarTipoDocumento(tipos_documento) : '<option value="">Dados não localizados</option>'}
                    </select>
                </td>
                <td>
                    <select name="conta${parcela.parcela}" class="form-control input-sm">
                        ${contas ? montarContaRecebimento(contas) : '<option value="">Dados não localizados</option>'}
                    </select>
                </td>
                <td>
                    <select name="situacao${parcela.parcela}" class="form-control input-sm">
                        ${situacoes ? montarSituacaoPagamento(situacoes) : '<option value="">Dados não localizados</option>'}
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-info input-sm" onclick="toggleObservacao(${parcela.parcela})" title="Adicionar/Editar Observação">
                        <i class="fa fa-comment"></i>
                        ${parcela.obs ? '<span class="badge badge-light ml-1">1</span>' : ''}
                    </button>
                </td>
            </tr>
            <tr id="row-obs-${parcela.parcela}" style="display: none;" class="bg-light">
                <td colspan="7" style="padding: 15px;">
                    <div class="form-group mb-0">
                        <label for="obs${parcela.parcela}" style="font-weight: bold;">
                            <i class="fa fa-comment"></i> Observação da Parcela ${parcela.parcela}
                        </label>
                        <textarea class="form-control" name="obs${parcela.parcela}" id="obs${parcela.parcela}" 
                                  rows="3" maxlength="500" placeholder="Digite aqui a observação para esta parcela..."
                                  style="resize: vertical;">${parcela.obs || ''}</textarea>
                        <small class="form-text text-muted">Limite de 500 caracteres</small>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#tbody-parcelas').html(html);
    
    // Aplicar máscaras aos campos
    aplicarMascarasParcelas();

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
        // Pular linhas de observação (que têm id row-obs-*)
        if ($(this).attr('id') && $(this).attr('id').startsWith('row-obs-')) {
            return; // continue
        }
        
        const parcela = $(this).find('td:first').text().trim();
        
        if (parcela && parcela !== 'Parcela') {
            const dados = {
                parcela: parseInt(parcela),
                vencimento: $(this).find(`input[name="venc${parcela}"]`).val(),
                valor: obterValorNumerico($(this).find(`input[name="valor${parcela}"]`)),
                tipo_documento: $(this).find(`select[name="tipo${parcela}"]`).val(),
                conta_recebimento: $(this).find(`select[name="conta${parcela}"]`).val(),
                situacao: $(this).find(`select[name="situacao${parcela}"]`).val(),
                obs: $(`textarea[name="obs${parcela}"]`).val() || ''
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
        // Pular linhas de observação
        if ($(this).attr('id') && $(this).attr('id').startsWith('row-obs-')) {
            return; // continue
        }
        
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

/**
 * Extrai os dias das parcelas da descrição do combo
 * Suporta formatos:
 * - "BOLETO 7/14" retorna [7, 14] (múltiplas parcelas)
 * - "BOLETO 21" retorna [21] (parcela única)
 * - "CARTÃO 15/30/45" retorna [15, 30, 45] (múltiplas parcelas)
 * @param {string} descricao - Descrição da opção selecionada
 * @returns {Array} Array com os dias de cada parcela
 */
function extrairDiasParcelas(descricao) {

    if (!descricao || typeof descricao !== 'string') {
        console.warn('Descrição inválida para extrair dias das parcelas');
        return [];
    }
    
    const dias = [];
    
    // Primeiro, tentar capturar números separados por barra (formato: 7/14/21)
    const numerosSeparados = descricao.match(/\d+(?:\/\d+)+/);
    
    if (numerosSeparados && numerosSeparados.length > 0) {
        // Dividir pelos números separados por barra
        const numerosArray = numerosSeparados[0].split('/');

        numerosArray.forEach(num => {
            const dia = parseInt(num.trim());
            if (!isNaN(dia) && dia > 0) {
                dias.push(dia);
            }
        });
    } else {
        // Se não encontrou formato com barras, buscar número único (formato: BOLETO 21)
        const numeroUnico = descricao.match(/\b(\d+)\b/);
        
        if (numeroUnico && numeroUnico.length > 0) {
            const dia = parseInt(numeroUnico[1]);
            if (!isNaN(dia) && dia > 0) {
                dias.push(dia);
            }
        }
    }
    
    return dias;
}

/**
 * Recalcula as parcelas quando o valor total for alterado
 * Mantém o número de parcelas selecionado no combo
 */
function recalcularParcelas() {
    // Obter valor total atual
    const valorTotal = window.obterValorNumerico ? window.obterValorNumerico('#valor_total_servicos') : 0;
    
    // Obter número de parcelas do combo (data-value)
    const numeroParcelas = parseInt($('#parcelas').find('option:selected').data('value')) || 1;
    
    // Obter descrição para extrair os dias
    const descricao = $('#parcelas').find('option:selected').text() || '';
    
    console.log('Recalculando parcelas - Valor:', valorTotal, 'Parcelas:', numeroParcelas, 'Descrição:', descricao);
    
    if (valorTotal > 0) {
        calcularParcelas(valorTotal, numeroParcelas, descricao);
    } else {
        limparParcelas();
    }
}

/**
 * Mostra/esconde o campo de observação da parcela
 * @param {number} numeroParcela - Número da parcela
 */
function toggleObservacao(numeroParcela) {
    const row = $('#row-obs-' + numeroParcela);
    const botao = $('button[onclick="toggleObservacao(' + numeroParcela + ')"]');
    const icone = botao.find('i');
    
    // Toggle: mostrar/esconder
    row.slideToggle(300, function() {
        // Mudar ícone do botão
        if (row.is(':visible')) {
            icone.removeClass('fa-comment').addClass('fa-comment-o');
            // Focar no textarea quando abrir
            $('#obs' + numeroParcela).focus();
        } else {
            icone.removeClass('fa-comment-o').addClass('fa-comment');
            // Atualizar badge quando fechar
            atualizarBadgeObservacao(numeroParcela);
        }
    });
}

/**
 * Atualiza o badge do botão de observação
 * @param {number} numeroParcela - Número da parcela
 */
function atualizarBadgeObservacao(numeroParcela) {
    const observacao = $('#obs' + numeroParcela).val();
    const botao = $('button[onclick="toggleObservacao(' + numeroParcela + ')"]');
    
    // Remover badge existente
    botao.find('.badge').remove();
    
    // Adicionar badge se houver observação
    if (observacao && observacao.trim() !== '') {
        botao.append('<span class="badge badge-light ml-1">1</span>');
    }
}

// Expor funções globalmente
window.calcularParcelas = calcularParcelas;
window.recalcularParcelas = recalcularParcelas;
window.extrairDiasParcelas = extrairDiasParcelas;
window.obterDadosParcelas = obterDadosParcelas;
window.validarParcelas = validarParcelas;
window.limparParcelas = limparParcelas;
window.toggleObservacao = toggleObservacao;
window.atualizarBadgeObservacao = atualizarBadgeObservacao;
