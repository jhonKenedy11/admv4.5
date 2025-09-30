/**
 * Funções para gerenciamento da modal de serviços com wizard
 * Arquivo: js/est/s_modal_servicos.js
 */

// Variável global para o wizard
let wizard;

// Controle de carregamento de estados
let carregamentoEstadosPromise = null;

// Event listeners
$(document).ready(function() {
    // Event listener para mudança do tipo de pessoa (mostrar/ocultar campo estado)
    $('#tipo').on('change', function() {
        var tipo = $(this).val();
        if (tipo === 'E') {
            $('#divEstado').show();
        } else {
            $('#divEstado').hide();
            $('#estado').val('');
        }
    });
    
    // Event listener para mudança do valor de desconto (recalcular total final)
    $('#valor_desconto').on('input', function() {
        try {
            var valorTotal = parseFloat($('#valor_total_servicos').val().replace('R$ ', '').replace(',', '.')) || 0;
            var valorDesconto = parseFloat($(this).val().replace(',', '.')) || 0;
            var valorFinal = valorTotal - valorDesconto;
            
            if (valorFinal < 0) valorFinal = 0;
            
            $('#valor_total_final').inputmask('setvalue', valorFinal);
            
            // Calcular parcelas automaticamente
            var numeroParcelas = parseInt($('#numero_parcelas').val()) || 1;
            if (valorFinal > 0) {
                calcularParcelas(valorFinal, numeroParcelas);
            }
        } catch (error) {
            console.error('Erro ao calcular desconto:', error);
        }
    });

    // Estado - Select comum sem Select2 (sem controle de estado)
    $('#estado').on('change', function() {
        var estadoId = $(this).val();
        console.log('Estado selecionado:', estadoId);
        
        // Limpar campos dependentes quando estado mudar
        desabilitarCamposDependentes();
    });
    
    // Event listeners para botões do Step 4
    $('#btnEmitirNFS').on('click', function() {
        emitirNFS();
    });
    
    $('#btnVisualizar').on('click', function() {
        visualizarDados();
    });
    
    $('#btnLimpar').on('click', function() {
        limparTodosCampos();
    });
    
    // Event listener para fechar modal (destruir wizard e limpar campos)
    $('#modalServicos').on('hidden.bs.modal', function() {
        // Limpar todos os campos da modal
        limparTodosCamposModal();
        
        // Destruir wizard
        if (wizard) {
            wizard.destroy();
            wizard = null;
        }
        
        console.log('Modal fechada e campos limpos');
    });
});

/**
 * Função principal para alimentar toda a tela de serviços
 * Inicializa todos os campos e configurações AJAX
 */
function alimentarTelaServicos() {
    //console.log('Inicializando tela de serviços...');
    
    // Inicializar Select2 para todos os combos
    inicializarSelect2();
    
    // Configurar máscaras e formatação
    configurarMascaras();
    
    // Configurar eventos de cálculo
    configurarEventosCalculo();
    
    // Configurar tooltips
    //configurarTooltips();
    
    //console.log('Tela de serviços inicializada com sucesso!');
}


/**
 * Inicializa Select2 para todos os combos
 */
function inicializarSelect2() {
    debugger

    // Local da Prestação (Cidade) - Sempre habilitado, validação será feita na busca
    $('#local_prestacao').select2({
        width: "99%",
        placeholder: "Digite para buscar cidades...",
        language: {
            // Texto insuficiente
            inputTooShort: function() {
                return "Digite no mínimo 3 caracteres";
            },

            // Carregando dados
            searching: function() {
                return "Buscando...";
            },

            // Nenhum resultado
            noResults: function() {
                return "Nenhuma cidade encontrada";
            },

            // Erro na busca
            errorLoading: function() {
                return "Erro ao carregar dados";
            },
        },
        minimumInputLength: 3,
        delay: 250,
        // ADICIONAR ESTA LINHA PARA QUE O SELECT2 APAREÇA DENTRO DO MODAL
        dropdownParent: $('#modalServicos'),
        ajax: {
            dataType: "json",
            type: "POST",
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchCidadeAjax&opcao=ajax',
            beforeSend: function(xhr, settings) {
                debugger
                // Validar se existe estado selecionado ANTES de fazer a consulta
                var estadoId = $('#estado').val();
                if (!estadoId || estadoId.trim() === '') {
                    // Cancelar a requisição se não há estado selecionado
                    xhr.abort();
                    return false;
                }
            },
            data: function(params) {
                return {
                    termo: params.term,
                    estado: $('#estado').val()
                };
            },
            processResults: function(response) {
                return {
                    results: response || []
                };
            },
            error: function(xhr, status, error) {
                // Se a requisição foi cancelada (abort), mostrar mensagem
                if (status === 'abort') {
                    // Mostrar mensagem de erro no Select2
                    $('#local_prestacao').select2('open');
                    $('#local_prestacao').select2('close');
                    
                    // Mostrar toast/alert
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção!',
                            text: 'Selecione um estado primeiro',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            }
        }
    }).on('select2:select', function(e) {
        debugger
        // Quando uma cidade for selecionada, habilitar e configurar os campos dependentes
        var cidadeId = e.params.data.id;
        //var cidadeNome = e.params.data.text;
        console.log('Cidade selecionada:', cidadeId);
        
        // Habilitar os campos dependentes
        $('#lista_servico').prop('disabled', false);
        $('#situacao_tributaria').prop('disabled', false);
        
        // Configurar os combos com o ID da cidade
        configurarListaServicos(cidadeId);
        configurarSituacaoTributaria(cidadeId);
        
    }).on('select2:clear', function(e) {
        // Quando a cidade for limpa, desabilitar e limpar os campos dependentes
        console.log('Cidade desmarcada'); 
        
        // Desabilitar e limpar os campos dependentes
        desabilitarCamposDependentes();
    });

    // Lista de Serviço - Inicialmente desabilitado (será um combo simples, não select2)
    // O campo já está desabilitado no HTML

/**
 * Função para desabilitar e limpar campos dependentes
 */
function desabilitarCamposDependentes() {
    try {
        // Limpar o campo local_prestacao (Select2) - mas manter habilitado
        $('#local_prestacao').val('').trigger('change');
        
        // Desabilitar e limpar o campo lista_servico
        $('#lista_servico').prop('disabled', true).val('');
        $('#lista_servico').html('<option value="">Selecione primeiro uma cidade</option>');
        
        // Desabilitar e limpar o campo situacao_tributaria
        $('#situacao_tributaria').prop('disabled', true).val('');
        $('#situacao_tributaria').html('<option value="">Selecione primeiro uma cidade</option>');
        
        // Limpar campo alíquota
        $('#aliquota').val('0,0000');
        
        console.log('Campos dependentes desabilitados e limpos');
    } catch (error) {
        console.error('Erro ao desabilitar campos dependentes:', error);
    }
}


// /**
//  * Busca cidades por termo e estado
//  * @param {string} termo - Termo de pesquisa
//  * @param {string|number} estadoId - ID do estado
//  * @returns {Promise} Promise com as cidades encontradas
//  */
// function buscarCidades(termo, estadoId) {
//     return new Promise((resolve, reject) => {
//         if (!termo || termo.length < 3) {
//             resolve([]);
//             return;
//         }
        
//         if (!estadoId) {
//             resolve([]);
//             return;
//         }
        
//         $.ajax({
//             url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchCidadeAjax&opcao=ajax',
//             type: 'POST',
//             dataType: 'json',
//             data: {
//                 estado: estadoId,
//                 termo: termo
//             },
//             xhrFields: {
//                 withCredentials: true
//             },
//             beforeSend: function(xhr) {
//                 xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
//             },
//             success: function(response) {
//                 console.log('Cidades encontradas:', response);
//                 resolve(response || []);
//             },
//             error: function(xhr, status, error) {
//                 console.error('Erro ao buscar cidades:', error);
//                 reject(error);
//             }
//         });
//     });
// }

// Event listener para mudança na lista de serviços
$('#lista_servico').on('change', function() {
        debugger
        var servicoId = $(this).val();
        
        if (servicoId) {
            // Obter a alíquota diretamente da opção selecionada
            var aliquota = $(this).find('option:selected').data('aliquota');
            
            if (aliquota) {
                // Preencher o campo alíquota com o valor do serviço
                // Converter vírgula para ponto para compatibilidade com inputmask
                var aliquotaNumerica = aliquota.replace(',', '.');
                $('#aliquota').inputmask('setvalue', aliquotaNumerica);
                
            } else {
                // Se não encontrar alíquota, limpar o campo
                $('#aliquota').val('0,0000');
                console.log('Alíquota não encontrada para o serviço selecionado');
            }
        } else {
            // Se nenhum serviço estiver selecionado, limpar o campo alíquota
            $('#aliquota').val('0,0000');
        }
    });

}

/**
 * Configura o combo da lista de serviços com o ID da cidade selecionada
 * @param {string|number} cidadeId - ID da cidade selecionada
 */
function configurarListaServicos(cidadeId) {
    console.log('Configurando lista de serviços para cidade:', cidadeId);
    
    // Mostrar loading no combo
    $('#lista_servico').html('<option value="">Carregando serviços...</option>');
    
    // Fazer requisição AJAX para buscar todos os serviços da cidade
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchListaServicosAjax&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            codigo_municipio: cidadeId
        },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            console.log('Serviços carregados:', response);
            
            if (response && Array.isArray(response) && response.length > 0) {
                // Limpar o combo
                $('#lista_servico').empty();
                
                // Adicionar opção padrão
                $('#lista_servico').append('<option value="">Selecione um serviço</option>');
                
                // Adicionar todas as opções com dados da alíquota
                response.forEach(function(servico) {
                    if (servico.id && servico.text) {
                        var aliquota = servico.aliquota || '0,0000';
                        $('#lista_servico').append('<option value="' + servico.id + '" data-aliquota="' + aliquota + '">' + servico.text + '</option>');
                    }
                });
                
                // Habilitar o combo
                $('#lista_servico').prop('disabled', false);
                
            } else {
                // Em caso de erro ou resposta vazia
                $('#lista_servico').html('<option value="">Nenhum serviço encontrado</option>');
                $('#lista_servico').prop('disabled', true);
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar serviços:', error);
            $('#lista_servico').html('<option value="">Erro ao carregar serviços</option>');
            $('#lista_servico').prop('disabled', true);
        }
    });
}

/**
 * Configura o combo da situação tributária com o ID da cidade selecionada
 * @param {string|number} cidadeId - ID da cidade selecionada
 */
function configurarSituacaoTributaria(cidadeId) {
    console.log('Configurando situação tributária para cidade:', cidadeId);
    
    // Mostrar loading no combo
    $('#situacao_tributaria').html('<option value="">Carregando situações tributárias...</option>');
    
    // Fazer requisição AJAX para buscar situações tributárias da cidade
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchSituacaoTributaria&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            codigo_municipio: cidadeId
        },
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            console.log('Situações tributárias carregadas:', response);
            
            if (response && Array.isArray(response) && response.length > 0) {
                // Limpar o combo
                $('#situacao_tributaria').empty();
                
                // Adicionar opção padrão
                $('#situacao_tributaria').append('<option value="">Selecione uma situação tributária</option>');
                
                // Adicionar todas as opções
                response.forEach(function(situacao) {
                    if (situacao.id && situacao.text) {
                        $('#situacao_tributaria').append('<option value="' + situacao.id + '">' + situacao.text + '</option>');
                    }
                });
                
                // Habilitar o combo
                $('#situacao_tributaria').prop('disabled', false);
                
            } else {
                // Em caso de erro ou resposta vazia
                $('#situacao_tributaria').html('<option value="">Nenhuma situação tributária encontrada</option>');
                $('#situacao_tributaria').prop('disabled', true);
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar situações tributárias:', error);
            $('#situacao_tributaria').html('<option value="">Erro ao carregar situações tributárias</option>');
            $('#situacao_tributaria').prop('disabled', true);
        }
    });
}


/**
 * Configura máscaras e formatação dos campos
 */
function configurarMascaras() {
    // Máscaras para valores monetários
    aplicarMascara('#valor_servico', 'monetario');
    aplicarMascara('#desc_incondicional', 'monetario');
    aplicarMascara('#valor_deducao', 'monetario');
    aplicarMascara('#base_calculo', 'monetario');
    aplicarMascara('#issqn', 'monetario');
    aplicarMascara('#issrf', 'monetario');
    aplicarMascara('#valor_total_servicos', 'monetario');
    aplicarMascara('#valor_total_final', 'monetario');
    
    // Máscara para alíquota (formato: 0,0000)
    aplicarMascara('#aliquota', 'percentual');
    
    // Máscaras inteligentes para telefones
    aplicarMascara('#tomador_fone_comercial', 'telefone');
    aplicarMascara('#tomador_fone_residencial', 'telefone');
    aplicarMascara('#tomador_fone_fax', 'telefone');
    
    // Máscara para CEP
    aplicarMascara('#tomador_cep', 'cep');
    
    // Máscara para CPF/CNPJ (formato dinâmico)
    aplicarMascara('#tomador_cpfcnpj', 'cpfcnpj');
    
    // Máscara para DDD
    aplicarMascara('#tomador_ddd_fone_comercial', 'ddd');
    aplicarMascara('#tomador_ddd_fone_residencial', 'ddd');
    aplicarMascara('#tomador_ddd_fax', 'ddd');
    
    console.log('Máscaras configuradas com sucesso');
}

/**
 * Configura eventos de cálculo automático
 */
function configurarEventosCalculo() {
debugger

    // Calcular todos os valores quando valor_servico for alterado
    $('#valor_servico').on('input', function() {
        calcularTodosValores();
    });
    
    // Calcular quando desc_incondicional for alterado
    $('#desc_incondicional').on('input', function() {
        calcularTodosValores();
    });
    
    // Calcular quando valor_deducao for alterado
    $('#valor_deducao').on('input', function() {
        calcularTodosValores();
    });
    
    // Calcular quando aliquota for alterado
    $('#aliquota').on('input', function() {
        calcularTodosValores();
    });
    
    // Calcular parcelas quando valor total final for alterado
    $('#valor_total_final').on('change', function() {
        var valorTotal = obterValorNumerico('#valor_total_final');
        var numeroParcelas = parseInt($('#numero_parcelas').val()) || 1;
        if (valorTotal > 0) {
            calcularParcelas(valorTotal, numeroParcelas);
        }
    });
    
    // Calcular parcelas quando número de parcelas for alterado
    $('#numero_parcelas').on('change', function() {
        debugger
        var valorTotal = obterValorNumerico('#valor_total_final');
        var numeroParcelas = parseInt($(this).val()) || 1;
        console.log('Número de parcelas alterado:', numeroParcelas, 'Valor total:', valorTotal);
        if (valorTotal > 0) {
            calcularParcelas(valorTotal, numeroParcelas);
        } else {
            console.warn('Valor total é zero ou inválido:', valorTotal);
        }
    });
}

/**
 * Função auxiliar para obter valor numérico de campo com máscara
 * @param {string} selector - Seletor do campo
 * @returns {number} - Valor numérico
 */
function obterValorNumerico(selector) {
    try {
        var elemento = $(selector);
        if (!elemento.length) {
            console.warn('Campo não encontrado:', selector);
            return 0;
        }
        
        var valor = 0;
        
        // Tentar usar método do inputmask primeiro
        if (elemento.inputmask && typeof elemento.inputmask === 'function') {
            try {
                valor = parseFloat(elemento.inputmask('unmaskedvalue')) || 0;
                console.log('Valor obtido via inputmask para', selector, ':', valor);
                return valor;
            } catch (e) {
                console.warn('Erro ao obter valor via inputmask para', selector, ':', e);
            }
        }
        
        // Verificar se o elemento tem inputmask aplicado
        if (elemento.data('inputmask')) {
            try {
                valor = parseFloat(elemento.inputmask('unmaskedvalue')) || 0;
                console.log('Valor obtido via inputmask (data) para', selector, ':', valor);
                return valor;
            } catch (e) {
                console.warn('Erro ao obter valor via inputmask (data) para', selector, ':', e);
            }
        }
        
        // Fallback: obter valor via .val() e limpar formatação
        var valorFormatado = elemento.val() || '';
        valorFormatado = valorFormatado.replace(/\./g, '').replace(',', '.').replace('R$ ', '').trim();
        valor = parseFloat(valorFormatado) || 0;
        
        console.log('Valor obtido via fallback para', selector, ':', valor, '(valor original:', elemento.val(), ')');
        return valor;
        
    } catch (error) {
        console.error('Erro ao obter valor numérico para', selector, ':', error);
        return 0;
    }
}

/**
 * Formata um número como moeda brasileira (R$ 1.234,56)
 * @param {number|string} valor
 * @returns {string}
 */
function formatarMoedaBR(valor) {
    try {
        var numero = Number(valor) || 0;
        return 'R$ ' + numero.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    } catch (e) {
        return 'R$ 0,00';
    }
}

/**
 * Controla o estado dos botões baseado no preenchimento do valor_servico
 */
function controlarEstadoBotoes() {
    try {
        var valorServico = obterValorNumerico('#valor_servico');
        var btnEmitir = $('#btnEmitirNFS');
        var btnVisualizar = $('#btnVisualizar');
        
        console.log('Controlando estado dos botões. Valor do serviço:', valorServico);
        
        if (valorServico > 0) {
            // Habilitar botões
            btnEmitir.prop('disabled', false).removeClass('disabled');
            btnVisualizar.prop('disabled', false).removeClass('disabled');
            
            // Adicionar classes visuais
            btnEmitir.removeClass('btn-secondary').addClass('btn-success');
            btnVisualizar.removeClass('btn-secondary').addClass('btn-info');
            
            console.log('Botões habilitados - valor do serviço > 0');
        } else {
            // Desabilitar botões
            btnEmitir.prop('disabled', true).addClass('disabled');
            btnVisualizar.prop('disabled', true).addClass('disabled');
            
            // Adicionar classes visuais para botões desabilitados
            btnEmitir.removeClass('btn-success').addClass('btn-secondary');
            btnVisualizar.removeClass('btn-info').addClass('btn-secondary');
            
            console.log('Botões desabilitados - valor do serviço = 0');
        }
        
    } catch (error) {
        console.error('Erro ao controlar estado dos botões:', error);
    }
}

/**
 * Calcula todos os valores baseado no valor_servico
 */
function calcularTodosValores() {
    debugger
    try {
        console.log('Calculando todos os valores...');
        
        // Obter valores usando função auxiliar
        var valorServico = obterValorNumerico('#valor_servico');
        var descIncondicional = obterValorNumerico('#desc_incondicional');
        var valorDeducao = obterValorNumerico('#valor_deducao');
        var aliquota = obterValorNumerico('#aliquota');
        
        console.log('Valores obtidos:', {
            valorServico: valorServico,
            descIncondicional: descIncondicional,
            valorDeducao: valorDeducao,
            aliquota: aliquota
        });
        
        // Calcular base de cálculo
        var baseCalculo = valorServico - valorDeducao;
        
        // Calcular ISSQN
        var issqn = (baseCalculo * aliquota) / 100;
        
        // Calcular ISSRF (assumindo que é 1% da base de cálculo - ajuste conforme necessário)
        var issrf = (baseCalculo * 1) / 100;
        
        // Aplicar máscaras e formatar valores
        $('#base_calculo').val(baseCalculo.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#issqn').val(issqn.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#issrf').val(issrf.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        
        // Calcular valor total dos serviços (valor do serviço - desconto incondicional)
        var valorTotalServicos = valorServico - descIncondicional;
        
        // Preencher valor total dos serviços
        $('#valor_total_servicos').inputmask('setvalue', valorTotalServicos);
        
        // Calcular valor total final (inicialmente igual ao total dos serviços)
        $('#valor_total_final').inputmask('setvalue', valorTotalServicos);
        
        // console.log('Valores calculados:', {
        //     valorServico: valorServico,
        //     descIncondicional: descIncondicional,
        //     valorDeducao: valorDeducao,
        //     aliquota: aliquota,
        //     baseCalculo: baseCalculo,
        //     issqn: issqn,
        //     issrf: issrf,
        //     valorTotalServicos: valorTotalServicos
        // });
        
        // Controlar estado dos botões baseado no valor do serviço
        controlarEstadoBotoes();
        
        return true;
        
    } catch (error) {
        console.error('Erro ao calcular valores:', error);
        return false;
    }
}

/**
 * Calcula base de cálculo (mantida para compatibilidade)
 */
function calcularBaseCalculo() {
    var valorServico = parseFloat($('#valor_servico').val().replace(/\./g, '').replace(',', '.')) || 0;
    var valorDeducao = parseFloat($('#valor_deducao').val().replace(/\./g, '').replace(',', '.')) || 0;
    
    var baseCalculo = valorServico - valorDeducao;
    
    $('#base_calculo').val(baseCalculo.toFixed(2).replace('.', ','));
}

/**
 * Calcula ISSQN (mantida para compatibilidade)
 */
function calcularISSQN() {
    var baseCalculo = parseFloat($('#base_calculo').val().replace(/\./g, '').replace(',', '.')) || 0;
    var aliquota = parseFloat($('#aliquota').val().replace(',', '.')) || 0;
    
    var issqn = (baseCalculo * aliquota) / 100;
    
    $('#issqn').val(issqn.toFixed(2).replace('.', ','));
}

/**
 * Configura tooltips
 */
function configurarTooltips() {
    $('[data-toggle="tooltip"]').tooltip();
}

/**
 * Função para aplicar máscara inteligente de telefone
 * Detecta automaticamente se é celular (9 dígitos) ou fixo (8 dígitos)
 * @param {string} selector - Seletor do campo de telefone
 */
function aplicarMascaraTelefone(selector) {
    $(selector).on('input', function() {
        var valor = $(this).val().replace(/\D/g, ''); // Remove tudo que não é dígito
        
        // Limita a 9 dígitos (máximo para celular)
        if (valor.length > 9) {
            valor = valor.substring(0, 9);
        }
        
        var valorFormatado = '';
        
        if (valor.length <= 4) {
            // Menos de 4 dígitos: apenas números
            valorFormatado = valor;
        } else if (valor.length <= 8) {
            // 5 a 8 dígitos: formato de telefone fixo (XXXX-XXXX)
            valorFormatado = valor.substring(0, 4) + '-' + valor.substring(4);
        } else {
            // 9 dígitos: formato de celular (XXXXX-XXXX)
            valorFormatado = valor.substring(0, 5) + '-' + valor.substring(5);
        }
        
        $(this).val(valorFormatado);
    });
}

/**
 * Função genérica para aplicar máscaras em campos
 * Facilita a adição de novas máscaras no futuro
 * @param {string} selector - Seletor do campo
 * @param {string} tipo - Tipo de máscara ('telefone', 'cep', 'cpf', 'cnpj', 'cpfcnpj', 'ddd', 'monetario', 'percentual', 'data')
 * @param {object} options - Opções adicionais para a máscara
 */
function aplicarMascara(selector, tipo, options) {
    if (!$(selector).length) {
        console.warn('Campo não encontrado:', selector);
        return;
    }
    
    // Garantir que options seja um objeto válido
    options = options || {};
    
    switch (tipo) {
        case 'telefone':
            aplicarMascaraTelefone(selector);
            break;
            
        case 'cep':
            $(selector).inputmask('99999-999', Object.assign({
                placeholder: '00000-000'
            }, options));
            break;
            
        case 'cpf':
            $(selector).inputmask('999.999.999-99', Object.assign({
                placeholder: '000.000.000-00'
            }, options));
            break;
            
        case 'cnpj':
            $(selector).inputmask('99.999.999/9999-99', Object.assign({
                placeholder: '00.000.000/0000-00'
            }, options));
            break;
            
        case 'cpfcnpj':
            $(selector).on('input', function() {
                var valor = $(this).val().replace(/\D/g, '');
                
                if (valor.length <= 11) {
                    // CPF: 000.000.000-00
                    $(this).inputmask('999.999.999-99', {
                        placeholder: '000.000.000-00'
                    });
                } else {
                    // CNPJ: 00.000.000/0000-00
                    $(this).inputmask('99.999.999/9999-99', {
                        placeholder: '00.000.000/0000-00'
                    });
                }
            });
            break;
            
        case 'ddd':
            $(selector).inputmask('99', Object.assign({
                placeholder: '00'
            }, options));
            break;
            
        case 'monetario':
            $(selector).inputmask('currency', Object.assign({
                prefix: 'R$ ',
                groupSeparator: '.',
                radixPoint: ',',
                autoGroup: true,
                digits: 2,
                digitsOptional: false,
                placeholder: '0,00'
            }, options));
            break;
            
        case 'percentual':
            $(selector).inputmask('9,9999', Object.assign({
                placeholder: '0,0000'
            }, options));
            break;
            
        case 'data':
            $(selector).inputmask('99/99/9999', Object.assign({
                placeholder: 'dd/mm/aaaa'
            }, options));
            break;
            
        case 'hora':
            $(selector).inputmask('99:99', Object.assign({
                placeholder: 'hh:mm'
            }, options));
            break;
            
        case 'numero':
            $(selector).inputmask('999999999', Object.assign({
                placeholder: '000000000'
            }, options));
            break;
            
        default:
            console.warn('Tipo de máscara não reconhecido:', tipo);
            break;
    }
    
    console.log('Máscara aplicada:', tipo, 'em', selector);
}

/**
 * Abre modal de serviços com carregamento sequencial e tratamento de erros
 * @param {number} id - ID do documento
 * @param {number} client_id - ID do cliente
 * @param {string} tipoDocumento - Tipo do documento
 * @param {Event} event - Evento que disparou a função
 */
async function abrirModalServicos(id, client_id, tipoDocumento, event) {
    // Prevenir comportamento padrão e propagação de eventos
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    try {
        // Validar parâmetros
        if (!validarParametrosModal(id, tipoDocumento)) {
            return false;
        }
        
        // Configurar modal
        const modal = configurarModal();
        if (!modal) {
            return false;
        }
        
        // Abrir modal
        modal.modal('show');
        
        // Inicializar wizard após a modal estar visível
        modal.on('shown.bs.modal', function() {
            inicializarWizard();
            alimentarTelaServicos();
        });
        
        // Carregar dados sequencialmente
        await carregarDadosModal(id, client_id, tipoDocumento);
        
        return true;
        
    } catch (error) {

        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Erro inesperado ao abrir modal de serviços.',
            confirmButtonText: 'OK'
        });

        return false;
    }
}

/**
 * Valida parâmetros de entrada
 * @param {number} id - ID do documento
 * @param {string} tipoDocumento - Tipo do documento
 * @returns {boolean} True se válido
 */
function validarParametrosModal(id, tipoDocumento) {
    if (!id || id <= 0) {

        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'ID inválido fornecido.',
            confirmButtonText: 'OK'
        });

        return false;
    }
    
    if (!tipoDocumento) {

        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Tipo de documento inválido.',
            confirmButtonText: 'OK'
        });

        return false;
    }
    
    return true;
}

/**
 * Configura e prepara a modal
 * @returns {jQuery} Elemento da modal ou null se erro
 */
function configurarModal() {
    const modal = $('#modalServicos');
    
    if (!modal.length) {

        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Modal de serviços não encontrada.',
            confirmButtonText: 'OK'
        });

        return null;
    }
    
    // Limpar conteúdo anterior
    const step3 = $('#step_3 .panel_servicos .panel-body');
    step3.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Carregando serviços...</div>');
    
    // Limpar step4
    const step4 = $('#step_4 .panel-body');
    if (step4.length) {
        step4.find('.text-center').remove();
    }
    
    // Limpar campos
    limparCamposTomador();
    limparCamposValores();
    
    // Desabilitar botões
    $('#btnEmitirNFS, #btnVisualizar').prop('disabled', true).addClass('disabled btn-secondary');
    $('#btnEmitirNFS').removeClass('btn-success').addClass('btn-secondary');
    $('#btnVisualizar').removeClass('btn-info').addClass('btn-secondary');
    
    return modal;
}

/**
 * Carrega dados do modal de forma sequencial
 * @param {number} id - ID do documento
 * @param {number} client_id - ID do cliente
 * @param {string} tipoDocumento - Tipo do documento
 */
async function carregarDadosModal(id, client_id, tipoDocumento) {
    try {
        // 1. Buscar dados do documento
        const dadosDocumento = await buscarDadosDocumento(id, client_id, tipoDocumento);
        
        if (!dadosDocumento) {
            return;
        }
        
        // 2. Carregar estados primeiro
        await carregarEstados();
        
        // 3. Preencher dados sequencialmente
        await populatesModalData(dadosDocumento);
        
    } catch (error) {

        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Erro ao carregar dados do modal.',
            confirmButtonText: 'OK'
        });

    }
}

/**
 * Busca dados do documento via AJAX
 * @param {number} id - ID do documento
 * @param {number} client_id - ID do cliente
 * @param {string} tipoDocumento - Tipo do documento
 * @returns {Promise<Object|null>} Dados do documento ou null se erro
 */
function buscarDadosDocumento(id, client_id, tipoDocumento) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=buscarServicos&opcao=ajax',
            type: 'POST',
            dataType: 'json',
            data: { id, client_id, tipo_documento: tipoDocumento },
            xhrFields: { withCredentials: true },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                //console.log('Resposta recebida:', response);
                
                // Verificar redirecionamento
                if (response && response.redirect) {

                    $('#step_3 .panel_servicos .panel-body').html('<div class="alert alert-warning">Sessão expirada. Por favor, faça login novamente.</div>');

                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Sessão Expirada!', 
                        text: 'Sua sessão expirou. Por favor, faça login novamente.',
                        confirmButtonText: 'OK'
                    });

                    resolve(null);
                    return;
                }
                
                if (response && response.success) {

                    resolve(response.data);

                } else {

                    const mensagem = response?.message || 'Nenhum serviço encontrado para este documento.';

                    $('#step_3 .panel_servicos .panel-body').html('<div class="alert alert-warning">' + mensagem + '</div>');

                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Nenhum Serviço', 
                        text: mensagem,
                        confirmButtonText: 'OK'
                    });

                    resolve(null);
                }
                
            },
            error: function(xhr, status, error) {

                Swal.fire({ 
                    icon: 'error', 
                    title: 'Erro!', 
                    text: 'Erro ao carregar serviços. Entre em contato com o suporte.',
                    confirmButtonText: 'OK'
                });

                $('#step_3 .panel_servicos .panel-body').html('<div class="alert alert-danger">Erro ao carregar serviços. Entre em contato com o suporte.</div>');
                reject(error);
            }
        });
    });
}

/**
 * Preenche dados de forma sequencial seguindo padrão clean code
 * @param {Object} dados - Dados do documento
 */
async function populatesModalData(dados) {
    const { servicos = [], data_provider = [], data_borrower = [] } = dados;
    
    try {
        // 1. Preencher dados do prestador
        await preencherDadosPrestador(data_provider);
        
        // 2. Preencher dados do tomador
        await preencherDadosTomador(data_borrower);
        
        // 3. Preencher dados dos serviços
        await preencherDadosServicos(servicos);
        
        console.log('Dados preenchidos com sucesso!');
        
    } catch (error) {
        console.error('Erro ao preencher dados:', error);
        throw error;
    }
}

/**
 * Preenche dados do prestador com tratamento de erro
 * @param {Array} data_provider - Dados do prestador
 */
async function preencherDadosPrestador(data_provider) {
    if (!data_provider || data_provider.length === 0) {
        console.log('Dados do prestador não encontrados');
        return;
    }
    
    const prestadorData = data_provider[0];
    
    // Verificar se há erro na consulta
    if (prestadorData && prestadorData.error === true) {
        console.error('Erro na consulta searchProvider:', prestadorData.message);
        console.error('Detalhes do erro:', prestadorData.details);
        // Não preencher dados do prestador em caso de erro, mas continuar o fluxo
        return;
    }
    
    // SUCESSO: Dados válidos do banco (não tem propriedade 'error')
    if (prestadorData && typeof prestadorData.error === 'undefined') {
        preencherCamposPrestador(prestadorData);
        await aguardar(100);
    }
}

/**
 * Preenche dados do tomador com tratamento de erro
 * @param {Array} data_borrower - Dados do tomador
 */
async function preencherDadosTomador(data_borrower) {
    if (!data_borrower || data_borrower.length === 0) {
        console.log('Cliente não encontrado ou sem dados do tomador');
        return;
    }
    
    const tomadorData = data_borrower[0];
    
    // Verificar se há erro na consulta
    if (tomadorData && tomadorData.error === true) {
        console.error('Erro na consulta searchBorrower:', tomadorData.message);
        console.error('Detalhes do erro:', tomadorData.details);
        // Não preencher dados do tomador em caso de erro, mas continuar o fluxo
        return;
    }
    
    // SUCESSO: Dados válidos do banco (não tem propriedade 'error')
    if (tomadorData && typeof tomadorData.error === 'undefined') {
        preencherCamposTomador(tomadorData);
        await aguardar(100);
    }
}

/**
 * Preenche dados dos serviços com tratamento de erro
 * @param {Array} servicos - Dados dos serviços
 */
async function preencherDadosServicos(servicos) {
    if (!servicos || servicos.length === 0) {
        $('#step_3 .panel_servicos .panel-body').html('<div class="alert alert-warning">Nenhum serviço encontrado para este documento.</div>');
        return;
    }
    
    // Verificar se há erro na consulta de serviços
    if (servicos.length === 1 && servicos[0] && servicos[0].error === true) {
        console.error('Erro na consulta de serviços:', servicos[0].message);
        console.error('Detalhes do erro:', servicos[0].details);
        $('#step_3 .panel_servicos .panel-body').html('<div class="alert alert-danger">Erro ao carregar serviços. Verifique o console para mais detalhes.</div>');
        return;
    }
    
    // SUCESSO: Dados válidos dos serviços
    preencherModalServicos(servicos);
}

/**
 * Aguarda um tempo específico
 * @param {number} ms - Milissegundos para aguardar
 * @returns {Promise} Promise que resolve após o tempo
 */
function aguardar(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Função para inicializar o wizard
function inicializarWizard() {
    debugger
    try {
        // Destruir wizard anterior se existir
        if (wizard) {
            wizard.destroy();
        }
        
        // Inicializar novo wizard
        wizard = $('#wizard').smartWizard({
            selected: 0,
            theme: 'default',
            transitionEffect: 'fade',
            showStepURLhash: false,
            toolbarSettings: {
                toolbarPosition: 'bottom',
                toolbarButtonPosition: 'right',
                showNextButton: true,
                showPreviousButton: true,
                toolbarExtraButtons: []
            },
            anchorSettings: {
                markDoneStep: true,
                markAllPreviousStepsAsDone: true,
                removeDoneStepOnNavigateBack: false,
                anchorClickable: true,
                enableAllAnchors: false,
                numbers: false,
                clickable: true,
                disablePreviousStep: false,
                removeDoneStepOnNavigateBack: false,
                enableAnchorOnDoneStep: true
            }
        });
        
        //console.log('Wizard inicializado com sucesso');
        
        // Configurar bloqueio de navegação por teclado nos campos específicos
        configurarBloqueioNavegacaoTeclado();
        
    } catch (error) {
        console.error('Erro ao inicializar wizard:', error);
    }
}

/**
 * Configura bloqueio de navegação por teclado nos campos específicos
 */
function configurarBloqueioNavegacaoTeclado() {
    debugger
    try {
        // Campos que devem bloquear a navegação do wizard
        var camposBloqueados = ['valor_servico', 'desc_incondicional', 'valor_deducao', 'aliquota'];
        
        // Adicionar event listener para interceptar teclas direita/esquerda
        $(document).on('keydown', function(e) {
            // Verificar se está em um campo de input específico
            var elementoAtivo = document.activeElement;
            
            // Se estiver em um campo bloqueado
            if (elementoAtivo && camposBloqueados.includes(elementoAtivo.id)) {
                // Bloquear apenas as teclas direita/esquerda quando estiver no início/fim do campo
                if (e.keyCode === 37 || e.keyCode === 39) { // seta esquerda ou direita
                    var campo = $(elementoAtivo);
                    var cursorPos = campo[0].selectionStart;
                    var valor = campo.val();
                    
                    // Se seta esquerda e está no início do campo, bloquear
                    if (e.keyCode === 37 && cursorPos === 0) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    
                    // Se seta direita e está no fim do campo, bloquear
                    if (e.keyCode === 39 && cursorPos === valor.length) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
            }
        });
        
        console.log('Bloqueio de navegação por teclado configurado para campos específicos');
        
    } catch (error) {
        console.error('Erro ao configurar bloqueio de navegação por teclado:', error);
    }
}

// Função para limpar campos do tomador
function limparCamposTomador() {
    try {
        // Limpar todos os campos do formulário do tomador
        $('#tipo').val('');
        $('#cpfcnpj').val('');
        $('#ie').val('');
        $('#endereco_informado').val('S');
        $('#nome_razao_social').val('');
        $('#sobrenome_nome_fantasia').val('');
        $('#email').val('');
        $('#pais').val('BRASIL');
        $('#logradouro').val('');
        $('#numero_residencia').val('');
        $('#complemento').val('');
        $('#bairro').val('');
        $('#cidade').val('');
        $('#cep').val('');
        $('#ponto_referencia').val('');
        $('#ddd_fone_comercial').val('');
        $('#fone_comercial').val('');
        $('#ddd_fone_residencial').val('');
        $('#fone_residencial').val('');
        $('#ddd_fax').val('');
        $('#fone_fax').val('');
        $('#estado').val('');
        
        // Ocultar campo estado (para estrangeiros)
        $('#divEstado').hide();
        
        //console.log('Campos do tomador limpos com sucesso');
    } catch (error) {
        console.error('Erro ao limpar campos do tomador:', error);
    }
}

// Função para limpar campos do prestador
function limparCamposPrestador() {
    try {
        // Limpar campos do prestador
        $('#prestador_cnpj').val('');
        //console.log('Campos do prestador limpos com sucesso');
    } catch (error) {
        console.error('Erro ao limpar campos do prestador:', error);
    }
}

// Função para limpar campos de valores
function limparCamposValores() {
    try {
        $('#valor_total_servicos').val('');
        $('#valor_desconto').val('0,00');
        $('#valor_total_final').val('');
        $('#forma_pagamento').val('');
        $('#natureza_operacao').val('');
        $('#regime_tributacao').val('');
        $('#observacoes').val('');
        $('#data_vencimento').val('');
        
        //console.log('Campos de valores limpos com sucesso');
    } catch (error) {
        console.error('Erro ao limpar campos de valores:', error);
    }
}

// Função para preencher campos do tomador
function preencherCamposTomador(dados_tomador) {
    debugger
    try {
        if (!dados_tomador) {
            console.warn('Dados do tomador não fornecidos');
            return false;
        }
        
        // Definir "Sim" como padrão se não houver valor
        $('#tomador_endereco_informado').val('S');
        $('#tomador_pais').val('BRASIL');
        
        // Preencher campos básicos
        if (dados_tomador.TOMADOR_TIPO_PESSOA) {

            $('#tomador_tipo_pessoa').val(dados_tomador.TOMADOR_TIPO_PESSOA);
            
            // Mostrar campo estado apenas para estrangeiros
            if (dados_tomador.TIPO === 'ESTRANGEIRO') {
                $('#divEstado').show();
            } else {
                $('#divEstado').hide();
            }
        }
        
        if (dados_tomador.TOMADOR_CNPJ_FORMATADO) {
            $('#tomador_cpfcnpj_formatado').val(dados_tomador.TOMADOR_CNPJ_FORMATADO);
            $('#tomador_cpfcnpj').val(dados_tomador.TOMADOR_CNPJCPF);
        }
        
        if (dados_tomador.TOMADOR_INSCRICAO_ESTADUAL_RG) {
            $('#tomador_inscricao_estadual_rg').val(dados_tomador.TOMADOR_INSCRICAO_ESTADUAL_RG);
        }
    
        
        if (dados_tomador.TOMADOR_NOME) {
            $('#tomador_razao_social').val(dados_tomador.TOMADOR_NOME);
        }
        
        if (dados_tomador.TOMADOR_NOME_REDUZIDO) {
            $('#tomador_nome_fantasia').val(dados_tomador.TOMADOR_NOME_REDUZIDO);
        }
        
        if (dados_tomador.TOMADOR_EMAIL) {
            $('#tomador_email').val(dados_tomador.TOMADOR_EMAIL);
        }
        
        // Dados de endereço
        if (dados_tomador.TOMADOR_ENDERECO) {
            $('#tomador_logradouro').val(dados_tomador.TOMADOR_ENDERECO);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_NUMERO) {
            $('#tomador_numero_residencia').val(dados_tomador.TOMADOR_ENDERECO_NUMERO);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_COMPLEMENTO) {
            $('#tomador_complemento').val(dados_tomador.TOMADOR_ENDERECO_COMPLEMENTO);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_BAIRRO) {
            $('#tomador_bairro').val(dados_tomador.TOMADOR_ENDERECO_BAIRRO);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_CIDADE) {
            $('#tomador_cidade').val(dados_tomador.TOMADOR_ENDERECO_CIDADE);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_CEP_FORMATADO) {
            $('#tomador_cep').val(dados_tomador.TOMADOR_ENDERECO_CEP_FORMATADO);
        }
        
        // Telefones - Separar DDD e número usando regex
        // Formatos suportados: 
        // - Telefone fixo: "(41) 3121-2233" -> DDD: "41", Número: "3121-2233"
        // - Celular: "(41) 99884-6716" -> DDD: "41", Número: "99884-6716"
        if (dados_tomador.TOMADOR_FONE) {
            var telefoneComercial = separarDDDTelefone(dados_tomador.TOMADOR_FONE);
            if (telefoneComercial) {
                $('#tomador_ddd_fone_comercial').val(telefoneComercial.ddd);
                $('#tomador_fone_comercial').val(telefoneComercial.numero);
            } else {
                $('#tomador_fone_comercial').val(dados_tomador.TOMADOR_FONE);
            }
        }
        
        if (dados_tomador.TOMADOR_CELULAR) {
            var telefoneResidencial = separarDDDTelefone(dados_tomador.TOMADOR_CELULAR);
            if (telefoneResidencial) {
                $('#tomador_ddd_fone_residencial').val(telefoneResidencial.ddd);
                $('#tomador_fone_residencial').val(telefoneResidencial.numero);
            } else {
                $('#tomador_fone_residencial').val(dados_tomador.TOMADOR_CELULAR);
            }
        }
        
        if (dados_tomador.TOMADOR_DDD_FAX) {
            var telefoneFax = separarDDDTelefone(dados_tomador.TOMADOR_DDD_FAX);
            if (telefoneFax) {
                $('#tomador_ddd_fax').val(telefoneFax.ddd);
                $('#tomador_fone_fax').val(telefoneFax.numero);
            } else {
                $('#tomador_ddd_fax').val(dados_tomador.TOMADOR_DDD_FAX);
            }
        }
        
        // Estado (apenas para estrangeiros)
        if (dados_tomador.TOMADOR_ESTADO) {
            $('#tomador_estado').val(dados_tomador.TOMADOR_ESTADO);
        }
        
        // Carregar estados e setar o estado do tomador se disponível
        var estadoId = dados_tomador.TOMADOR_ENDERECO_UF_ID || null;
        carregarEstadosStep3(estadoId);
        
        //console.log('Campos do tomador preenchidos com sucesso');
        return true;
        
    } catch (error) {
        console.error('Erro ao preencher campos do tomador:', error);
        return false;
    }
}

/**
 * Carrega os estados via AJAX (apenas uma vez)
 * @returns {Promise} Promise que resolve quando os estados são carregados
 */
function carregarEstados() {
    // Se já existe uma promise de carregamento, retorna ela
    if (carregamentoEstadosPromise) {
        return carregamentoEstadosPromise;
    }
    
    // Se já tem estados carregados, retorna promise resolvida
    if ($('#estado option').length > 1) {
        return Promise.resolve();
    }
    
    // Criar nova promise de carregamento
    carregamentoEstadosPromise = new Promise((resolve, reject) => {
        $.ajax({
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchEstadosAjax&opcao=ajax',
            type: 'POST',
            dataType: 'json',
            data: {},
            xhrFields: { withCredentials: true },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                if (response && response.length > 0) {
                    // Limpar e popular o select
                    $('#estado').empty().append('<option value="">Selecione um estado...</option>');
                    response.forEach(function(estado) {
                        $('#estado').append('<option value="' + estado.id + '">' + estado.text + '</option>');
                    });
                    resolve();
                } else {
                    $('#estado').html('<option value="">Nenhum estado encontrado</option>');
                    resolve();
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar estados:', error);
                $('#estado').html('<option value="">Erro ao carregar estados</option>');
                reject(error);
            }
        });
    });
    
    return carregamentoEstadosPromise;
}

/**
 * Carrega os estados e seta um valor específico
 * @param {string} estadoId - ID do estado para setar (opcional)
 */
async function carregarEstadosStep3(estadoId) {
    try {
        // Aguardar carregamento dos estados
        await carregarEstados();
        
        // Se foi passado um ID, setar o valor
        if (estadoId) {
            $('#estado').val(estadoId).trigger('change');
            console.log('Estado setado no step3:', estadoId);
        }
    } catch (error) {
        console.error('Erro ao carregar estados:', error);
    }
}

// Função para preencher campos do prestador
function preencherCamposPrestador(dados_prestador) {
    debugger
    try {
        if (!dados_prestador) {
            console.warn('Dados do prestador não fornecidos');
            return false;
        }

        if (dados_prestador.PRESTADOR_EMPRESA_NOME) {
            $('#prestador_empresa_nome').val(dados_prestador.PRESTADOR_EMPRESA_NOME);
        }
        
        if (dados_prestador.PRESTADOR_CNPJ_FORMATADO) {
            $('#prestador_cnpj_formatado').val(dados_prestador.PRESTADOR_CNPJ_FORMATADO);
        }

        if (dados_prestador.PRESTADOR_CNPJ) {
            $('#prestador_cnpj').val(dados_prestador.PRESTADOR_CNPJ);
        }
        
        if (dados_prestador.IE) {
            $('#prestador_ie').val(dados_prestador.IE);
        }
        
        if (dados_prestador.CIDADE) {
            $('#prestador_cidade').val(dados_prestador.CIDADE);
        }

        if (dados_prestador.PRESTADOR_NFS_SERIE) {
            $('#prestador_serie').val(dados_prestador.PRESTADOR_NFS_SERIE);
        }


        if (dados_prestador.PRESTADOR_NFS_SITUACAO_TRIBUTARIA) {
            $('#prestador_situacao_tributaria').val(dados_prestador.PRESTADOR_NFS_SITUACAO_TRIBUTARIA);
        }

        if (dados_prestador.PRESTADOR_DATA_EMISSAO) {
            $('#prestador_data_emissao').val(dados_prestador.PRESTADOR_DATA_EMISSAO);
        }else{
            $('#prestador_data_emissao').val(new Date().toLocaleDateString('pt-BR'));
        }

        if (dados_prestador.PRESTADOR_DATA_FATO_GERADOR) {
            $('#prestador_data_fato_gerador').val(dados_prestador.PRESTADOR_DATA_FATO_GERADOR);
        }else{
            $('#prestador_data_fato_gerador').val(new Date().toLocaleDateString('pt-BR'));
        }
        
        //console.log('Campos do prestador preenchidos com sucesso');
        return true;
        
    } catch (error) {
        console.error('Erro ao preencher campos do prestador:', error);
        return false;
    }
}

// Função para calcular e preencher valores automaticamente (baseada em lista de serviços - mantida para compatibilidade)
function calcularValores(servicos) {
    try {
        if (!servicos || servicos.length === 0) {
            return false;
        }
        
        var totalGeral = 0;
        
        servicos.forEach(function(servico) {
            if (servico.TOTALSERVICO && !isNaN(parseFloat(servico.TOTALSERVICO))) {
                totalGeral += parseFloat(servico.TOTALSERVICO);
            }
        });
        
        // Preencher valor total dos serviços
        $('#valor_total_servicos').val('R$ ' + totalGeral.toFixed(2).replace('.', ','));
        
        // Calcular valor total final (inicialmente igual ao total dos serviços)
        $('#valor_total_final').val('R$ ' + totalGeral.toFixed(2).replace('.', ','));
        
        console.log('Valores calculados a partir da lista de serviços:', totalGeral);
        return true;
        
    } catch (error) {
        console.error('Erro ao calcular valores:', error);
        return false;
    }
}

// Função para adicionar texto à descrição
function adicionarADescricao(elemento) {
    debugger


    let descricao = $('#descricao');
    let conteudo_atual = descricao.val().trim(); // Limpa espaços do conteúdo atual

    const texto = elemento.getAttribute('data-texto');

    let novo_conteudo = '';
    
    // Se já existe conteúdo, adiciona uma nova linha
    if (conteudo_atual && conteudo_atual !== '') { 

        novo_conteudo = conteudo_atual + '\n' + texto;

    } else {

        novo_conteudo = texto;
    }
    
    // Verifica se vai ultrapassar o limite de caracteres
    const max_caracteres = 200;
 
    if (novo_conteudo.length > max_caracteres) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: `O texto selecionado fará a descrição ultrapassar o limite de ${max_caracteres} caracteres.`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        // Notifica o usuário
        Swal.fire({
            icon: 'success',
            title: 'Adicionado!',
            text: 'Texto adicionado à descrição',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    }
    
    // Adiciona o novo texto
    descricao.val(novo_conteudo);
    
    // Chama a validação para atualizar o visual
    validarDescricao(descricao[0]);
 }
 
 // Função para validar descrição
 function validarDescricao(elemento) {

    const max_caracteres = 200;
    const texto_atual = elemento.value.trim(); // Limpa para validar corretamente
    const caracteres_restantes = max_caracteres - texto_atual.length;
    
    // Atualiza o contador de caracteres
    $('#caracteres-restantes').text(`(${caracteres_restantes} caracteres restantes)`);
    
    // Verifica se ultrapassou o limite
    if (textoAtual.length > max_caracteres) {
        $(elemento).removeClass('form-control').addClass('form-control is-invalid border-danger');
    } else {
        $(elemento).removeClass('form-control is-invalid border-danger').addClass('form-control');
    }
 }


// Função para preencher a modal com os serviços
function preencherModalServicos(servicos) {
    debugger
    try {
        var step3 = $('#step_3 .panel_servicos .panel-body');
        
        if (!step3.length) {
            console.error('Step 3 não encontrado');
            return false;
        }
        
        // Carregar os estados via AJAX
        carregarEstadosStep3();
        
        var html = '';
        
        if (!servicos || servicos.length === 0) {
            html = '<div class="alert alert-info">Nenhum serviço cadastrado para este documento.</div>';
        } else {
            html = '<div class="table-responsive">' +
                   '<table class="table table-striped table-bordered table-hover">' +
                   '<thead class="thead-dark">' +
                   '<tr>' +
                   '<th>Descrição</th>' +
                   '<th>Quantidade</th>' +
                   '<th>Unidade</th>' +
                   '<th>Valor Unitário</th>' +
                   '<th>Total</th>' +
                   '<th>Data</th>' +
                   '<th>Usuário</th>' +
                   '</tr>' +
                   '</thead>' +
                   '<tbody>';
            
            servicos.forEach(function(servico) {
                if (!servico) return;
                
                // Formatação segura de valores monetários
                var valorUnitario = 'N/A';
                var valorTotal = 'N/A';
                var custoUser = 'N/A';
                
                try {
                    if (servico.VALUNITARIO && !isNaN(parseFloat(servico.VALUNITARIO))) {
                        valorUnitario = formatarMoedaBR(parseFloat(servico.VALUNITARIO));
                    }
                    
                    if (servico.TOTALSERVICO && !isNaN(parseFloat(servico.TOTALSERVICO))) {
                        valorTotal = formatarMoedaBR(parseFloat(servico.TOTALSERVICO));
                    }
                    
                    if (servico.CUSTOUSER && !isNaN(parseFloat(servico.CUSTOUSER))) {
                        custoUser = formatarMoedaBR(parseFloat(servico.CUSTOUSER));
                    }
                } catch (e) {
                    console.warn('Erro ao formatar valores monetários:', e);
                }
                
                // Formatação de data
                var dataFormatada = 'N/A';
                if (servico.DATA) {
                    try {
                        var data = new Date(servico.DATA);
                        if (!isNaN(data.getTime())) {
                            dataFormatada = data.toLocaleDateString('pt-BR');
                        }
                    } catch (e) {
                        console.warn('Erro ao formatar data:', e);
                    }
                }
                
                
                // Truncar descrição se for muito longa
                var descricao = servico.DESCSERVICO || servico.DESCRICAO || 'N/A';
                if (descricao.length > 50) {
                    descricao = descricao.substring(0, 47) + '...';
                }
                
                // Verificar se é OS (tem quantidade executada) ou Pedido
                var quantidadeExibida = servico.QUANTIDADE || '0';
                if (servico.QUANTIDADE_EXECUTADA && servico.QUANTIDADE_EXECUTADA !== servico.QUANTIDADE) {
                    quantidadeExibida = (servico.QUANTIDADE || '0') + ' / ' + (servico.QUANTIDADE_EXECUTADA || '0');
                }
                
                html += '<tr>' +
                       '<td class="clickable" data-texto="' + (servico.DESCSERVICO || servico.DESCRICAO || 'N/A').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)" title="' + (servico.DESCSERVICO || servico.DESCRICAO || '') + '">' + descricao + '</td>' +
                       '<td class="text-center clickable" data-texto="' + String(quantidadeExibida).replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)">' + quantidadeExibida + '</td>' +
                       '<td class="text-center clickable" data-texto="' + (servico.UNIDADE || 'N/A').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)">' + (servico.UNIDADE || 'N/A') + '</td>' +
                       '<td class="text-right clickable" data-texto="' + (valorUnitario + '').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)">' + valorUnitario + '</td>' +
                       '<td class="text-right clickable" data-texto="' + (valorTotal + '').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)"><strong>' + valorTotal + '</strong></td>' +
                       '<td class="text-center clickable" data-texto="' + (dataFormatada + '').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)"><small>' + dataFormatada + '</small></td>' +
                       '<td class="text-center clickable" data-texto="' + (servico.NOME_USUARIO || 'N/A').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)"><small>' + (servico.NOME_USUARIO || 'N/A') + '</small></td>' +
                       '</tr>';
                
                // Adicionar linha de observações se existir
                if (servico.OBSSERVICO && servico.OBSSERVICO.trim() !== '') {
                    html += '<tr class="table-info">' +
                           '<td colspan="9" class="text-muted clickable" data-texto="' + servico.OBSSERVICO.replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)">' +
                           '<small><strong>Observações:</strong> ' + servico.OBSSERVICO.replace(/"/g, '&quot;') + '</small>' +
                           '</td>' +
                           '</tr>';
                }
            });
            
            html += '</tbody></table></div>';
            
            // Adicionar resumo dos totais
            var totalGeral = 0;
            var quantidadeTotal = 0;
            var quantidadeExecutadaTotal = 0;
            
            servicos.forEach(function(servico) {
                if (servico.TOTALSERVICO && !isNaN(parseFloat(servico.TOTALSERVICO))) {
                    totalGeral += parseFloat(servico.TOTALSERVICO);
                }
                if (servico.QUANTIDADE && !isNaN(parseFloat(servico.QUANTIDADE))) {
                    quantidadeTotal += parseFloat(servico.QUANTIDADE);
                }
                if (servico.QUANTIDADE_EXECUTADA && !isNaN(parseFloat(servico.QUANTIDADE_EXECUTADA))) {
                    quantidadeExecutadaTotal += parseFloat(servico.QUANTIDADE_EXECUTADA);
                }
            });
            
            var resumoQuantidade = quantidadeTotal.toFixed(2) + ' unidade(s)';
            if (quantidadeExecutadaTotal > 0 && quantidadeExecutadaTotal !== quantidadeTotal) {
                resumoQuantidade = quantidadeTotal.toFixed(2) + ' / ' + quantidadeExecutadaTotal.toFixed(2) + ' unidade(s) (contratada/executada)';
            }
            
            html += '<div class="row mt-3">' +
                   '<div class="col-md-6">' +
                   '<div class="alert alert-info">' +
                   '<strong>Resumo:</strong> ' + servicos.length + ' serviço(s), ' + resumoQuantidade +
                   '</div>' +
                   '</div>' +
                   '<div class="col-md-6">' +
                   '<div class="alert alert-success">' +
                   '<strong>Valor Total:</strong> ' + formatarMoedaBR(totalGeral) +
                   '</div>' +
                   '</div>' +
                   '</div>';
        }
        
        step3.html(html);
        console.log('Modal preenchida com sucesso');
        return true;
        
    } catch (error) {
        debugger
        console.error('Erro ao preencher modal de serviços:', error);
        var step3 = $('#step_3 .panel_servicos .panel-body');
        if (step3.length) {
            step3.html('<div class="alert alert-danger">Erro ao carregar dados dos serviços.</div>');
        }
        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Erro ao carregar dados dos serviços.',
            confirmButtonText: 'OK'
        });
        return false;
    }
}

// Função para validar dados antes de emitir NFS
function validarDadosEmissao() {
    try {
        var erros = [];
        
        // Validar prestador
        if (!$('#prestador_cnpj').val()) {
            erros.push('CNPJ do prestador é obrigatório');
        }
        
        // Validar tomador
        if (!$('#tipo').val()) {
            erros.push('Tipo de pessoa é obrigatório');
        }
        if (!$('#cpfcnpj').val()) {
            erros.push('CPF/CNPJ do tomador é obrigatório');
        }
        if (!$('#nome_razao_social').val()) {
            erros.push('Nome/Razão social do tomador é obrigatório');
        }
        
        // Validar valores
        if (!$('#valor_total_servicos').val()) {
            erros.push('Valor total dos serviços é obrigatório');
        }
        
        if (erros.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validação',
                html: '<strong>Os seguintes campos são obrigatórios:</strong><br>' + erros.join('<br>'),
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        return true;
    } catch (error) {
        console.error('Erro ao validar dados:', error);
        return false;
    }
}

// Função para emitir NFS
function emitirNFS() {
    try {
        if (!validarDadosEmissao()) {
            return false;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Emitindo NFS-e...',
            text: 'Aguarde, processando solicitação',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Coletar dados do formulário
        var dados = {
            prestador: {
                // CAMPOS QUE ESTÃO NA MODAL MAS NÃO NA FUNÇÃO:
                // prestador_empresa_nome - Nome da empresa prestadora (readonly) - Nao irei utilizar apenas visualizar
                // prestador_cnpj_formatado - CNPJ formatado do prestador (readonly) - Nao irei utilizar apenas visualizar
                // prestador_data_emissao - Data de emissão da nota (readonly) - Irei utilizar para preencher o campo data de emissao da nota fiscal
                // prestador_data_fato_gerador - Data do fato gerador (readonly) - Irei utilizar para preencher o campo data do fato gerador da nota fiscal
                
                // CAMPOS ATUAIS DA FUNÇÃO:
                cnpj: $('#prestador_cnpj').val(), // CAMPO NÃO ENCONTRADO NA MODAL - Fica como campo hidden
                ie: $('#prestador_serie').val(),
                endereco: $('#prestador_endereco').val(), // CAMPO NÃO ENCONTRADO NA MODAL - Incluir na consulta no back e incluir no form
                cidade: $('#prestador_cidade').val(), // CAMPO NÃO ENCONTRADO NA MODAL - Incluir na consulta no back e incluir no form
                uf: $('#prestador_uf').val(), // CAMPO NÃO ENCONTRADO NA MODAL - Incluir na consulta no back e incluir no form
                cep: $('#prestador_cep').val(), // Incluir na consulta no back e incluir no form
                ddd: $('#prestador_ddd').val(), // Incluir na consulta no back e incluir no form
                telefone: $('#prestador_telefone').val() // Incluir na consulta no back e incluir no form
            },
            tomador: {

                tipo: $('#tomador_tipo_pessoa').val(),
                cpfcnpj: $('#tomador_cpfcnpj').val(),
                ie: $('#tomador_inscricao_estadual_rg').val(),
                endereco_informado: $('#tomador_endereco_informado').val(),
                nome_razao_social: $('#tomador_razao_social').val(), 
                sobrenome_nome_fantasia: $('#tomador_nome_fantasia').val(), 
                email: $('#tomador_email').val(), 
                pais: $('#tomador_pais').val(), 
                logradouro: $('#tomador_logradouro').val(), 
                numero_residencia: $('#tomador_numero_residencia').val(), 
                complemento: $('#tomador_complemento').val(), 
                bairro: $('#tomador_bairro').val(), 
                cidade: $('#tomador_cidade').val(), 
                cep: $('#tomador_cep').val(), 
                ponto_referencia: $('#tomador_ponto_referencia').val(), 
                ddd_fone_comercial: $('#tomador_ddd_fone_comercial').val(), 
                fone_comercial: $('#tomador_fone_comercial').val(), 
                ddd_fone_residencial: $('#tomador_ddd_fone_residencial').val(), 
                fone_residencial: $('#tomador_fone_residencial').val(), 
                ddd_fax: $('#tomador_ddd_fax').val(), 
                fone_fax: $('#tomador_fone_fax').val(), 
                estado: $('#tomador_estado').val() 
            },
            servicos: {
                // CAMPOS QUE ESTÃO NA MODAL MAS NÃO NA FUNÇÃO:
                // local_prestacao - Local da prestação do serviço (select2)
                // lista_servico - Lista de serviços disponíveis (select2)
                // situacao_tributaria - Situação tributária do serviço
                // valor_servico - Valor do serviço (campo principal)
                // desc_incondicional - Desconto incondicional
                // valor_deducao - Valor da dedução (readonly)
                // base_calculo - Base de cálculo do imposto (readonly)
                // aliquota - Alíquota do imposto (readonly)
                // issqn - Valor do ISSQN (readonly)
                // issrf - Valor do ISSRF (readonly)
                // descricao - Descrição detalhada do serviço
                
                // Recuperar valores dos campos select2
                local_prestacao: obterValorSelect2('#local_prestacao'), // ID do local selecionado
                local_prestacao_texto: obterTextoSelect2('#local_prestacao'), // Texto do local selecionado
                lista_servico: obterValorSelect2('#lista_servico'), // ID do serviço selecionado
                lista_servico_texto: obterTextoSelect2('#lista_servico'), // Texto do serviço selecionado
                situacao_tributaria: $('#situacao_tributaria').val(),
                valor_servico: obterValorNumerico('#valor_servico'), // Usar função auxiliar para pegar valor numérico
                desc_incondicional: obterValorNumerico('#desc_incondicional'),
                valor_deducao: obterValorNumerico('#valor_deducao'),
                base_calculo: obterValorNumerico('#base_calculo'),
                aliquota: obterValorNumerico('#aliquota'),
                issqn: obterValorNumerico('#issqn'),
                issrf: obterValorNumerico('#issrf'),
                descricao: $('#descricao').val()
            },
            valores: {
                // CAMPOS ATUAIS DA FUNÇÃO (CONFEREM COM A MODAL):
                valor_total_servicos: $('#valor_total_servicos').val(), // ✅ Campo existe na modal
                valor_desconto: $('#valor_desconto').val(), // ✅ Campo existe na modal
                valor_total_final: $('#valor_total_final').val(), // ✅ Campo existe na modal
                forma_pagamento: $('#forma_pagamento').val(), // ✅ Campo existe na modal
                natureza_operacao: $('#natureza_operacao').val(), // ✅ Campo existe na modal
                regime_tributacao: $('#regime_tributacao').val(), // ✅ Campo existe na modal
                observacoes: $('#observacoes').val(), // ✅ Campo existe na modal
                data_vencimento: $('#data_vencimento').val() // ✅ Campo existe na modal
            }
        };
        
        // Fazer requisição AJAX para emissão
        $.ajax({
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=emitirNFS&opcao=ajax',
            type: 'POST',
            dataType: 'json',
            data: dados,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                Swal.close();
                
                if (response && response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'NFS-e emitida com sucesso!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // Fechar modal após sucesso
                        $('#modalServicos').modal('hide');
                    });
                } else {
                    var mensagem = 'Erro ao emitir NFS-e.';
                    if (response && response.message) {
                        mensagem = response.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: mensagem,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Erro ao emitir NFS:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao emitir NFS-e. Entre em contato com o suporte.',
                    confirmButtonText: 'OK'
                });
            }
        });
        
    } catch (error) {
        Swal.close();
        console.error('Erro ao emitir NFS:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro inesperado ao emitir NFS-e.',
            confirmButtonText: 'OK'
        });
    }
}

// Função para visualizar dados
function visualizarDados() {
    try {
        // Ir para o último step para visualizar todos os dados
        if (wizard) {
            wizard.goToStep(3); // Step 4 (índice 3)
        }
    } catch (error) {
        console.error('Erro ao visualizar dados:', error);
    }
}

// Função para limpar todos os campos da modal
function limparTodosCamposModal() {
    try {
        console.log('Limpando todos os campos da modal...');
        
        // Limpar campos do prestador (Step 1)
        $('#prestador_empresa_nome').val('');
        $('#prestador_cnpj_formatado').val('');
        $('#prestador_cnpj').val('');
        $('#prestador_ie').val('');
        $('#prestador_cidade').val('');
        $('#prestador_endereco').val('');
        $('#prestador_uf').val('');
        $('#prestador_cep').val('');
        $('#prestador_ddd').val('');
        $('#prestador_telefone').val('');
        $('#prestador_serie').val('');
        $('#prestador_data_emissao').val('');
        $('#prestador_data_fato_gerador').val('');
        
        // Limpar campos do tomador (Step 2)
        $('#tomador_tipo_pessoa').val('');
        $('#tomador_cpfcnpj_formatado').val('');
        $('#tomador_cpfcnpj').val('');
        $('#tomador_inscricao_estadual_rg').val('');
        $('#tomador_endereco_informado').val('S');
        $('#tomador_razao_social').val('');
        $('#tomador_nome_fantasia').val('');
        $('#tomador_email').val('');
        $('#tomador_pais').val('BRASIL');
        $('#tomador_logradouro').val('');
        $('#tomador_numero_residencia').val('');
        $('#tomador_complemento').val('');
        $('#tomador_bairro').val('');
        $('#tomador_cidade').val('');
        $('#tomador_cep').val('');
        $('#tomador_ponto_referencia').val('');
        $('#tomador_ddd_fone_comercial').val('');
        $('#tomador_fone_comercial').val('');
        $('#tomador_ddd_fone_residencial').val('');
        $('#tomador_fone_residencial').val('');
        $('#tomador_ddd_fax').val('');
        $('#tomador_fone_fax').val('');
        $('#tomador_estado').val('');
        
        // Ocultar campo estado (para estrangeiros)
        $('#divEstado').hide();
        
        // Limpar campos de serviços (Step 3)
        $('#estado').val('').trigger('change');
        
        // Desabilitar e limpar todos os campos dependentes
        desabilitarCamposDependentes();
        
        $('#valor_servico').val('0,00');
        $('#desc_incondicional').val('0,00');
        $('#valor_deducao').val('0,00');
        $('#base_calculo').val('0,00');
        $('#aliquota').val('0,0000');
        $('#issqn').val('0,00');
        $('#issrf').val('0,00');
        $('#descricao').val('');
        
        // Resetar contador de caracteres da descrição
        $('#caracteres-restantes').text('(200 caracteres restantes)');
        
        // Limpar campos de valores (Step 4)
        $('#valor_total_servicos').inputmask('setvalue', '');
        $('#valor_desconto').val('0,00');
        $('#valor_total_final').inputmask('setvalue', '');
        $('#forma_pagamento').val('');
        $('#numero_parcelas').val('1');
        $('#natureza_operacao').val('');
        $('#regime_tributacao').val('');
        $('#observacoes').val('');
        $('#data_vencimento').val('');
        
        // Limpar step 3 (lista de serviços)
        var step3 = $('#step_3 .panel_servicos .panel-body');
        step3.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Carregando serviços...</div>');
        
        // Limpar parcelas
        if (typeof limparParcelas === 'function') {
            limparParcelas();
        }
        
        // Voltar para o primeiro step
        if (wizard) {
            wizard.goToStep(0);
        }
        
        // Limpar classes de validação
        $('#descricao').removeClass('form-control is-invalid border-danger').addClass('form-control');
        
        // Desabilitar botões após limpeza
        controlarEstadoBotoes();
        
        console.log('Todos os campos da modal foram limpos com sucesso');
        
    } catch (error) {
        console.error('Erro ao limpar campos da modal:', error);
    }
}

// Função para limpar todos os campos (usada pelo botão Limpar)
function limparTodosCampos() {
    try {
        limparTodosCamposModal();
        
        Swal.fire({
            icon: 'success',
            title: 'Limpo!',
            text: 'Todos os campos foram limpos.',
            confirmButtonText: 'OK'
        });
        
    } catch (error) {
        console.error('Erro ao limpar campos:', error);
    }
}

/**
 * Função auxiliar para obter o valor (ID) de um campo select2 ou combo simples
 * @param {string} selector - Seletor do campo
 * @returns {string|number|null} - Valor do campo ou null se não houver seleção
 */
function obterValorSelect2(selector) {
    try {
        var elemento = $(selector);
        if (!elemento.length) {
            console.warn('Campo não encontrado:', selector);
            return null;
        }
        
        var valor = elemento.val();
        console.log('Valor obtido do campo', selector, ':', valor);
        return valor;
        
    } catch (error) {
        console.error('Erro ao obter valor do campo', selector, ':', error);
        return null;
    }
}

/**
 * Função auxiliar para obter o texto de um campo select2 ou combo simples
 * @param {string} selector - Seletor do campo
 * @returns {string|null} - Texto do campo ou null se não houver seleção
 */
function obterTextoSelect2(selector) {
    try {
        var elemento = $(selector);
        if (!elemento.length) {
            console.warn('Campo não encontrado:', selector);
            return null;
        }
        
        var texto = elemento.find('option:selected').text();
        
        console.log('Texto obtido do campo', selector, ':', texto);
        return texto;
        
    } catch (error) {
        console.error('Erro ao obter texto do campo', selector, ':', error);
        return null;
    }
}


/**
 * Função para separar DDD e número de telefone/celular
 * @param {string} telefone - Telefone no formato "(41) 3121-2233" ou "(41) 99884-6716"
 * @returns {object|null} - Objeto com ddd e numero, ou null se não conseguir separar
 */
function separarDDDTelefone(telefone) {
    if (!telefone || typeof telefone !== 'string') {
        return null;
    }
    
    // Regex para telefone fixo: "(41) 3121-2233" (8 dígitos)
    var regexTelefone = /^\((\d{2})\)\s*(\d{4}-\d{4})$/;
    var match = telefone.match(regexTelefone);
    
    if (match) {
        return {
            ddd: match[1],        // "41"
            numero: match[2]       // "3121-2233"
        };
    }
    
    // Regex para celular: "(41) 99884-6716" (9 dígitos)
    var regexCelular = /^\((\d{2})\)\s*(\d{5}-\d{4})$/;
    match = telefone.match(regexCelular);
    
    if (match) {
        return {
            ddd: match[1],        // "41"
            numero: match[2]       // "99884-6716"
        };
    }
    
    // Fallback: formatos sem parênteses
    // Telefone fixo: "41 3121-2233"
    var regexTelefone2 = /^(\d{2})\s*(\d{4}-\d{4})$/;
    match = telefone.match(regexTelefone2);
    
    if (match) {
        return {
            ddd: match[1],
            numero: match[2]
        };
    }
    
    // Celular: "41 99884-6716"
    var regexCelular2 = /^(\d{2})\s*(\d{5}-\d{4})$/;
    match = telefone.match(regexCelular2);
    
    if (match) {
        return {
            ddd: match[1],
            numero: match[2]
        };
    }
    
    // Fallback: apenas números
    // Telefone fixo: "4131212233" (10 dígitos total)
    var regexTelefone3 = /^(\d{2})(\d{8})$/;
    match = telefone.match(regexTelefone3);
    
    if (match) {
        return {
            ddd: match[1],
            numero: match[2].replace(/(\d{4})(\d{4})/, '$1-$2') // Formatar como 3121-2233
        };
    }
    
    // Celular: "41998846716" (11 dígitos total)
    var regexCelular3 = /^(\d{2})(\d{9})$/;
    match = telefone.match(regexCelular3);
    
    if (match) {
        return {
            ddd: match[1],
            numero: match[2].replace(/(\d{5})(\d{4})/, '$1-$2') // Formatar como 99884-6716
        };
    }
    
    // Se não conseguir separar, retorna null
    return null;
}

// Expor funções globalmente para uso externo
window.abrirModalServicos = abrirModalServicos;
window.alimentarTelaServicos = alimentarTelaServicos;
window.emitirNFS = emitirNFS;
window.visualizarDados = visualizarDados;
window.limparTodosCampos = limparTodosCampos;
window.limparTodosCamposModal = limparTodosCamposModal;
window.calcularTodosValores = calcularTodosValores;
window.obterValorNumerico = obterValorNumerico;
window.controlarEstadoBotoes = controlarEstadoBotoes;
window.configurarBloqueioNavegacaoTeclado = configurarBloqueioNavegacaoTeclado;
window.aplicarMascara = aplicarMascara;
window.aplicarMascaraTelefone = aplicarMascaraTelefone;
window.configurarMascaras = configurarMascaras;
window.obterValorSelect2 = obterValorSelect2;
window.obterTextoSelect2 = obterTextoSelect2;
window.configurarListaServicos = configurarListaServicos;
window.configurarSituacaoTributaria = configurarSituacaoTributaria;
window.desabilitarCamposDependentes = desabilitarCamposDependentes;


