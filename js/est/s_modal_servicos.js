/**
 * Funções para gerenciamento da modal de serviços com wizard
 * Arquivo: js/est/s_modal_servicos.js
 */

// Variável global para o wizard
let wizard;

// Controle de carregamento de estados
let carregamentoEstadosPromise = null;

// Parâmetros de impostos padrão (alíquotas percentuais)
let parametrosImpostos = {
    inss: 0,
    pis: 0,
    cofins: 0,
    ir: 0,
    contribuicao_social: 0
};

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
        
        // Resetar wizard para o primeiro step
        if (wizard && typeof wizard.goToStep === 'function') {
            wizard.goToStep(0);
        }
        
        // Limpar referência do wizard
        wizard = null;
        
        console.log('Modal fechada e campos limpos');
    });

    // Configurar parcelas
    configurarParcelas();

    // Configurar gêneros
    configurarGeneros();
});

/**
 * Função para limpar campos dependentes
 */
function limparCamposDependentes() {
    try {
        // Limpar o campo local_prestacao (Select2) - mas manter habilitado
        $('#local_prestacao').val('').trigger('change');
        
        // Limpar campo alíquota
        $('#aliquota').val('0,00');

        // Limpar campo valor_servico usando inputmask
        $('#valor_servico').inputmask('setvalue', 0);
        
        // Limpar campo tributa_municipio_prestador
        $('#tributa_municipio_prestador').val('N');
        
        console.log('Campos dependentes limpos');
    } catch (error) {
        console.error('Erro ao limpar campos dependentes:', error);
    }
}

/**
 * Função principal para alimentar toda a tela de serviços
 * Inicializa todos os campos e configurações AJAX
 */
function alimentarTelaServicos() {
    //console.log('Inicializando tela de serviços...');
    
    // Inicializar Select2 para todos os combos
    inicializarSelect2();
    
    // Carregar lista de serviços
    configurarListaServicos();
    
    // Carregar situação tributária
    configurarSituacaoTributaria();
    
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
    

    // Local da Prestação (Cidade) - Sempre habilitado, validação será feita na busca
    $('#local_prestacao').select2({
        width: "99%",
        placeholder: "Digite para buscar o município de prestação...",
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
                return "Nenhum município encontrado";
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
            data: function(params) {
                return {
                    term: params.term,
                    estado: $('#estado').val() || '',
                    estado_sigla: $('#estado').find('option:selected').text().split(' - ')[0] || '',
                };
            },
            processResults: function(response) {
                return {
                    results: response || []
                };
            }
        }
    });

    // Event listener para mudança no local de prestação
    $('#local_prestacao').on('change', function() {
        atualizarTributaMunicipioPrestador();
    });

    // Event listener para mudança na lista de serviços
$('#lista_servico').on('change', function() {
        
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
                $('#aliquota').val('0,00');
                console.log('Alíquota não encontrada para o serviço selecionado');
            }
        } else {
            // Se nenhum serviço estiver selecionado, limpar o campo alíquota
            $('#aliquota').val('0,00');
        }
    });

}

/**
 * Configura o combo da lista de serviços (carrega todos os serviços disponíveis)
 */
function configurarListaServicos() {
    
    // Mostrar loading no combo
    $('#lista_servico').html('<option value="">Carregando serviços...</option>');
    
    // Fazer requisição AJAX para buscar todos os serviços
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchListaServicosAjax&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        data: {},
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {

            
            
            if (response && response.items && Array.isArray(response.items) && response.items.length > 0) {
                // Limpar o combo
                $('#lista_servico').empty();
                
                // Adicionar opção padrão
                $('#lista_servico').append('<option value="">Selecione um serviço</option>');
                
                // Adicionar todas as opções com dados da alíquota - apenas um foreach
                response.items.forEach(function(servico) {

                    if (servico.id && servico.text) {

                        var aliquota = servico.aliquota || '0,0000';
                        var optionHtml = '<option value="' + servico.id + '" data-aliquota="' + aliquota + '">' + servico.text + '</option>';
                        $('#lista_servico').append(optionHtml);
                    }

                });
                
                // Selecionar o serviço padrão se existir (operação direta, sem foreach)
                if (response.default) {
                    $('#lista_servico').val(response.default).trigger('change');
                }
                
            } else {
                // Em caso de erro ou resposta vazia
                $('#lista_servico').html('<option value="">Nenhum serviço encontrado</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar serviços:', error);
            $('#lista_servico').html('<option value="">Erro ao carregar serviços</option>');
        }
    });
}

/**
 * Configura o combo da situação tributária (carrega todas as situações disponíveis)
 */
function configurarSituacaoTributaria() {
    
    // Mostrar loading no combo
    $('#situacao_tributaria').html('<option value="">Carregando situações tributárias...</option>');
    
    // Fazer requisição AJAX para buscar situações tributárias
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchSituacaoTributaria&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        data: {},
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            
            if (response && response.items && Array.isArray(response.items) && response.items.length > 0) {
                // Limpar o combo
                $('#situacao_tributaria').empty();
                
                // Adicionar opção padrão
                $('#situacao_tributaria').append('<option value="">Selecione uma situação tributária</option>');
                
                // Adicionar todas as opções - apenas um foreach
                response.items.forEach(function(situacao) {

                    if (situacao.id && situacao.text) {
                        $('#situacao_tributaria').append('<option value="' + situacao.id + '">' + situacao.text + '</option>');
                    }

                });
                
                // Selecionar a situação tributária padrão se existir (operação direta, sem foreach)
                if (response.default) {
                    $('#situacao_tributaria').val(response.default).trigger('change');
                }
                
            } else {
                // Em caso de erro ou resposta vazia
                $('#situacao_tributaria').html('<option value="">Nenhuma situação tributária encontrada</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar situações tributárias:', error);
            $('#situacao_tributaria').html('<option value="">Erro ao carregar situações tributárias</option>');
        }
    });
}


/**
 * Configura o combo da situação tributária com o ID da cidade selecionada
 * @param {string|number} cidadeId - ID da cidade selecionada
 */
function configurarParcelas() {
    
    // Mostrar loading no combo
    $('#parcelas').html('<option value="">Carregando parcelas...</option>');
    
    // Fazer requisição AJAX para buscar situações tributárias da cidade
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchParcelas&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            
            if (response && Array.isArray(response) && response.length > 0) {

                // Limpar o combo
                $('#parcelas').empty();
                
                // Adicionar todas as opções
                response.forEach(function(situacao) {

                    if (situacao.id && situacao.text) {

                        // Monta o option com atributos extras
                        var option = '<option value="' + situacao.id + '" ' +
                                     'data-value="' + situacao.data_value + '">' +
                                     situacao.text +
                                     '</option>';
                                     
                        $('#parcelas').append(option);
                    }
                });
                
            } else {
                $('#parcelas').html('<option value="">Nenhuma parcela encontrada</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar situações tributárias:', error);
            $('#parcelas').html('<option value="">Erro ao carregar parcelas</option>');
            $('#parcelas').prop('disabled', true);
        }
    });
}


/**
 * Configura o combo de gêneros (carrega todos os gêneros disponíveis)
 */
function configurarGeneros() {
    
    // Mostrar loading no combo
    $('#genero').html('<option value="">Carregando gêneros...</option>');

    // Fazer requisição AJAX para buscar gêneros
    $.ajax({
        url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchGeneros&opcao=ajax',
        type: 'POST',
        dataType: 'json',
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            
            if (response && Array.isArray(response) && response.length > 0) {

                // Limpar o combo
                $('#genero').empty();
                
                // Adicionar todas as opções
                response.forEach(function(genero) {

                    if (genero.id && genero.text) {

                        var tipo = genero.tipo_lancamento || '';
                        
                        var option = '<option value="' + genero.id + '" ' +
                                     'data-tipo="' + tipo + '" ' +
                                     'class="genero-tipo-' + tipo.toLowerCase() + '">' +
                                     genero.text +
                                     '</option>';
                                     
                        $('#genero').append(option);
                    }
                });
                
                // Select2 básico
                $('#genero').select2({
                    width: '100%',
                    templateResult: function(item) {
                        if (!item.id) return item.text;
                        var tipo = $(item.element).data('tipo');
                        var cor = tipo == 'R' ? '#28a745' : (tipo == 'P' ? '#dc3545' : '#999');
                        return $('<span style="border-left: 3px solid ' + cor + '; padding-left: 8px;">' + item.text + '</span>');
                    }
                });
                
            } else {
                $('#genero').html('<option value="">Nenhum gênero encontrado</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar gêneros:', error);
            $('#genero').html('<option value="">Erro ao carregar gêneros</option>');
            $('#genero').prop('disabled', true);
        }
    });
}


/**
 * Configura máscaras e formatação dos campos
 */
function configurarMascaras() {
    // Máscaras para valores monetários - Step 3
    aplicarMascara('#valor_servico', 'monetario');
    aplicarMascara('#desc_incondicional', 'monetario');
    aplicarMascara('#valor_deducao', 'monetario');
    aplicarMascara('#base_calculo', 'monetario');
    aplicarMascara('#issqn', 'monetario');
    aplicarMascara('#issrf', 'monetario');
    
    // Máscara para alíquota (formato: 0,0000)
    aplicarMascara('#aliquota', 'percentual');
    
    // Máscaras para valores monetários - Step 4 (Novos campos)
    aplicarMascara('#valor_inss', 'monetario');
    aplicarMascara('#valor_pis', 'monetario');
    aplicarMascara('#valor_cofins', 'monetario');
    aplicarMascara('#valor_ir', 'monetario');
    aplicarMascara('#valor_contribuicao_social', 'monetario');
    aplicarMascara('#valor_total_aliquota', 'percentual');
    aplicarMascara('#valor_total_base_calculo', 'monetario');
    aplicarMascara('#valor_total_deducao', 'monetario');
    aplicarMascara('#valor_total_desconto', 'monetario');
    aplicarMascara('#valor_total_servicos', 'monetario');
    
    // Máscaras inteligentes para telefones
    aplicarMascara('#tomador_fone_comercial', 'telefone');
    aplicarMascara('#tomador_fone_residencial', 'telefone');
    aplicarMascara('#tomador_fone_fax', 'telefone');
    
    // Máscara para CEP
    aplicarMascara('#tomador_cep_formatado', 'cep');
    
    // Máscara para CPF/CNPJ (formato dinâmico)
    aplicarMascara('#tomador_cpfcnpj', 'cpfcnpj');
    
    // Máscara para DDD
    aplicarMascara('#tomador_ddd_fone_comercial', 'ddd');
    aplicarMascara('#tomador_ddd_fone_residencial', 'ddd');
    aplicarMascara('#tomador_ddd_fax', 'ddd');
    
    console.log('Máscaras configuradas com sucesso');
}

// Variável global para controlar o timeout do delay de cálculos
let timeoutCalculoTributos = null;

/**
 * FUNÇÃO ÚNICA PARA CALCULAR TODOS OS CAMPOS
 * Esta função é chamada sempre que os campos principais forem alterados:
 * - valor_servico
 * - desc_incondicional  
 * - valor_deducao
 */
function calcularTodosCampos() {
    try {
        
        // 1. OBTER VALORES DOS CAMPOS PRINCIPAIS
        var valor_servico = obterValorNumerico('#valor_servico');
        var desc_incondicional = obterValorNumerico('#desc_incondicional');
        var valor_deducao = obterValorNumerico('#valor_deducao');
        var aliquota = obterValorPercentual('#aliquota');
        
        // 2. VALIDAR SE HÁ VALOR NO SERVIÇO
        if (valor_servico <= 0) {
            console.log('Valor do serviço é zero ou inválido, limpando campos calculados');
            limparCamposCalculados();
            controlarEstadoBotoes();
            return false;
        }
        
        // 3. CALCULAR VALORES INTERMEDIÁRIOS
        // Valor total dos serviços = valor do serviço - desconto incondicional
        var valor_total_servicos = valor_servico - desc_incondicional;
        
        // Base de cálculo = valor total dos serviços - valor dedução
        var base_calculo = valor_total_servicos - valor_deducao;
        
        // 4. CALCULAR IMPOSTOS
        // ISSQN 
        var issqn = (base_calculo * aliquota) / 100;
        
        // ISSRF 
        var issrf = (base_calculo * aliquota) / 100;
        
        // 5. CALCULAR IMPOSTOS BASEADOS NOS PARÂMETROS
        // Aplicar alíquotas percentuais sobre a base de cálculo
        var valor_inss = (base_calculo * parametrosImpostos.inss) / 100;
        var valor_pis = (base_calculo * parametrosImpostos.pis) / 100;
        var valor_cofins = (base_calculo * parametrosImpostos.cofins) / 100;
        var valor_ir = (base_calculo * parametrosImpostos.ir) / 100;
        var valor_contribuicao_social = (base_calculo * parametrosImpostos.contribuicao_social) / 100;
        
        // 6. APLICAR VALORES NOS CAMPOS CALCULADOS
        // Usar inputmask.setvalue para garantir que o inputmask processe corretamente
        $('#base_calculo').inputmask('setvalue', base_calculo);
        $('#issqn').inputmask('setvalue', issqn);
        $('#issrf').inputmask('setvalue', issrf);
        
        // Impostos parametrizados no Step 4
        $('#valor_inss').inputmask('setvalue', valor_inss);
        $('#valor_pis').inputmask('setvalue', valor_pis);
        $('#valor_cofins').inputmask('setvalue', valor_cofins);
        $('#valor_ir').inputmask('setvalue', valor_ir);
        $('#valor_contribuicao_social').inputmask('setvalue', valor_contribuicao_social);
        
        // Valores totais no Step 4
        $('#valor_total_servicos').inputmask('setvalue', valor_servico);
        $('#valor_total_base_calculo').inputmask('setvalue', base_calculo);
        $('#valor_total_deducao').inputmask('setvalue', valor_deducao);
        $('#valor_total_desconto').inputmask('setvalue', desc_incondicional);
        $('#valor_total_aliquota').inputmask('setvalue', aliquota);
        
        // 7. CONTROLAR ESTADO DOS BOTÕES
        controlarEstadoBotoes();
        
        // 8. CALCULAR PARCELAS SE NECESSÁRIO
        var numero_parcelas = parseInt($('#parcelas').find('option:selected').data('value')) || 1;
        var descricao_parcelas = $('#parcelas').find('option:selected').text() || '';

        if (base_calculo > 0 && typeof calcularParcelas === 'function') {
            calcularParcelas(base_calculo, numero_parcelas, descricao_parcelas);
        }
    
        
        return true;
        
    } catch (error) {
        console.error('Erro ao calcular todos os campos:', error);
        return false;
    }
}

/**
 * Executa cálculos com delay para evitar múltiplas execuções
 */
function executarCalculosComDelay() {
    // Limpar timeout anterior se existir
    if (timeoutCalculoTributos) {
        clearTimeout(timeoutCalculoTributos);
    }
    
    // Definir novo timeout com delay de 500ms
    timeoutCalculoTributos = setTimeout(function() {
        calcularTodosCampos();
    }, 500);
}

/**
 * Configura eventos de cálculo automático
 * Configurado para os 4 campos principais: valor_servico, desc_incondicional, valor_deducao, aliquota
 */
function configurarEventosCalculo() {    
    // Eventos para os 4 campos principais que disparam o cálculo
    $('#valor_servico').on('input', function() {
        console.log('Campo valor_servico alterado');
        executarCalculosComDelay();
    });
    
    $('#desc_incondicional').on('input', function() {
        console.log('Campo desc_incondicional alterado');
        executarCalculosComDelay();
    });
    
    $('#valor_deducao').on('input', function() {
        console.log('Campo valor_deducao alterado');
        executarCalculosComDelay();
    });
    
    $('#aliquota').on('input', function() {
        console.log('Campo aliquota alterado');
        executarCalculosComDelay();
    });
    
    // Eventos para parcelas (separados dos cálculos principais)
    $('#valor_total_servicos').on('change', function() {
        // Usar a nova função de recálculo que pega o data-value correto
        if (typeof recalcularParcelas === 'function') {
            recalcularParcelas();
        }
    });
    
    $('#parcelas').on('change', function() {

        var valor_base_calculo = obterValorNumerico('#valor_total_base_calculo');
        var numeroParcelas = parseInt($(this).find('option:selected').data('value')) || 1;
        var descricao = $(this).find('option:selected').text() || '';

        if (valor_base_calculo > 0 && typeof calcularParcelas === 'function') {
            calcularParcelas(valor_base_calculo, numeroParcelas, descricao);
        }
    });
    
}

/**
 * Função auxiliar para obter valor numérico de campo com máscara
 * Remove máscaras e converte para número. A formatação final é feita no backend.
 * @param {string|jQuery} selector - Seletor do campo ou objeto jQuery
 * @returns {number} - Valor numérico sem formatação
 */
function obterValorNumerico(selector) {
    try {
        var elemento = selector instanceof jQuery ? selector : $(selector);
        
        if (!elemento.length) {
            return 0;
        }
        
        // Sempre obter o valor formatado e processar manualmente
        // O inputmask('unmaskedvalue') pode truncar casas decimais em alguns casos
        var valorFormatado = elemento.val() || '0';
        
        // Limpar: R$, espaços, pontos de milhares, trocar vírgula por ponto
        valorFormatado = valorFormatado
            .replace(/R\$/g, '')
            .replace(/\s/g, '')
            .replace(/\./g, '')
            .replace(',', '.');
        
        // Converter e retornar (PHP fará a formatação final)
        return parseFloat(valorFormatado) || 0;
        
    } catch (error) {
        console.error('Erro ao obter valor numérico:', error);
        return 0;
    }
}

/**
 * Função auxiliar para obter valor percentual de campo
 * Remove símbolos e converte para número. A formatação final é feita no backend.
 * @param {string|jQuery} selector - Seletor do campo ou objeto jQuery
 * @returns {number} - Valor percentual sem formatação
 */
function obterValorPercentual(selector) {
    try {
        var elemento = selector instanceof jQuery ? selector : $(selector);
        
        if (!elemento.length) {
            return 0;
        }
        
        var valorFormatado = elemento.val() || '0';
        
        // Limpar: %, espaços, trocar vírgula por ponto
        valorFormatado = valorFormatado
            .replace('%', '')
            .replace(/\s/g, '')
            .replace(',', '.');
        
        // Converter e retornar (PHP fará a formatação final)
        return parseFloat(valorFormatado) || 0;
        
    } catch (error) {
        console.error('Erro ao obter valor percentual:', error);
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
        
        // Controlar estado do campo desc_incondicional
        controlarEstadoDescIncondicional(valorServico);
        
    } catch (error) {
        console.error('Erro ao controlar estado dos botões:', error);
    }
}

/**
 * Controla o estado do campo desc_incondicional baseado no valor_servico
 */
function controlarEstadoDescIncondicional(valorServico) {
    try {
        var campoDescIncondicional = $('#desc_incondicional');
        
        if (valorServico > 0) {
            // Habilitar campo desc_incondicional quando valor_servico > 0
            campoDescIncondicional.prop('disabled', false).removeClass('disabled');
            console.log('Campo desc_incondicional habilitado - valor do serviço > 0');
        } else {
            // Desabilitar campo desc_incondicional quando valor_servico = 0
            campoDescIncondicional.prop('disabled', true).addClass('disabled');
            // Limpar valor quando desabilitado
            campoDescIncondicional.val('0,00');
            console.log('Campo desc_incondicional desabilitado - valor do serviço = 0');
        }
        
    } catch (error) {
        console.error('Erro ao controlar estado do campo desc_incondicional:', error);
    }
}

/**
 * Limpa campos calculados quando valor_servico for zero ou vazio
 */
function limparCamposCalculados() {
    try {
        console.log('Limpando campos calculados...');
        
        // Limpar campos intermediários calculados usando inputmask
        $('#base_calculo').inputmask('setvalue', 0);
        $('#issqn').inputmask('setvalue', 0);
        $('#issrf').inputmask('setvalue', 0);
        
        // Limpar impostos parametrizados do Step 4
        $('#valor_inss').inputmask('setvalue', 0);
        $('#valor_pis').inputmask('setvalue', 0);
        $('#valor_cofins').inputmask('setvalue', 0);
        $('#valor_ir').inputmask('setvalue', 0);
        $('#valor_contribuicao_social').inputmask('setvalue', 0);
        
        // Limpar valores totais do Step 4
        $('#valor_total_servicos').inputmask('setvalue', 0);
        $('#valor_total_base_calculo').inputmask('setvalue', 0);
        $('#valor_total_deducao').inputmask('setvalue', 0);
        $('#valor_total_desconto').inputmask('setvalue', 0);
        $('#valor_total_aliquota').inputmask('setvalue', 0);
        
        // Desabilitar campo desc_incondicional quando valor_servico = 0
        $('#desc_incondicional').prop('disabled', true).addClass('disabled').inputmask('setvalue', 0);
        
        console.log('Campos calculados limpos com sucesso');
        
    } catch (error) {
        console.error('Erro ao limpar campos calculados:', error);
    }
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
            $(selector).inputmask('9,99', Object.assign({
                placeholder: '0,00'
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
    
    try {
        // Resetar wizard anterior se existir
        if (wizard && typeof wizard.goToStep === 'function') {
            wizard.goToStep(0);
        }
        
        // Verificar se o wizard já foi inicializado
        if ($('#wizard').hasClass('sw-container')) {
            // Wizard já inicializado, apenas resetar para o primeiro step
            if (wizard && typeof wizard.goToStep === 'function') {
                wizard.goToStep(0);
            }
            return;
        }
        
        // Inicializar novo wizard
        wizard = $('#wizard').smartWizard({
            selected: 0,
            theme: 'default',
            transitionEffect: 'fade',
            showStepURLhash: false,
            keyNavigation: false, // Desabilita navegação por teclado para não interferir nos campos de input
            toolbarSettings: {
                toolbarPosition: 'top',
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
        
        
    } catch (error) {
        console.error('Erro ao inicializar wizard:', error);
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
        // Limpar novos campos monetários do Step 4
        $('#valor_inss').inputmask('setvalue', 0);
        $('#valor_pis').inputmask('setvalue', 0);
        $('#valor_cofins').inputmask('setvalue', 0);
        $('#valor_ir').inputmask('setvalue', 0);
        $('#valor_contribuicao_social').inputmask('setvalue', 0);
        $('#valor_total_aliquota').inputmask('setvalue', 0);
        $('#valor_total_base_calculo').inputmask('setvalue', 0);
        $('#valor_total_deducao').inputmask('setvalue', 0);
        $('#valor_total_desconto').inputmask('setvalue', 0);
        $('#valor_total_servicos').inputmask('setvalue', 0);
        $('#parcelas').val('');
        $('#observacoes').val('');
        
        //console.log('Campos de valores limpos com sucesso');
    } catch (error) {
        console.error('Erro ao limpar campos de valores:', error);
    }
}

// Função para preencher campos do tomador
function preencherCamposTomador(dados_tomador) {
    
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
            if (dados_tomador.TIPO === 'E') {
                $('#divEstado').show();
            } else {
                $('#divEstado').hide();
            }
        }

        if (dados_tomador.TOMADOR_TIPO_PESSOA_DESC) {
            $('#tomador_tipo_pessoa_desc').val(dados_tomador.TOMADOR_TIPO_PESSOA_DESC);
        }

        if (dados_tomador.TOMADOR_ID) {
            $('#tomador_id').val(dados_tomador.TOMADOR_ID);
        }

        if (dados_tomador.TOMADOR_CNPJCPF) {
            $('#tomador_cpfcnpj_formatado').val(dados_tomador.TOMADOR_CNPJ_FORMATADO);
            $('#tomador_cpfcnpj').val(dados_tomador.TOMADOR_CNPJCPF);
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

        if (dados_tomador.TOMADOR_ENDERECO_CODIGO_MUNICIPIO) {
            $('#tomador_codigo_municipio').val(dados_tomador.TOMADOR_ENDERECO_CODIGO_MUNICIPIO);
        }
        
        if (dados_tomador.TOMADOR_ENDERECO_CEP_FORMATADO) {
            $('#tomador_cep_formatado').val(dados_tomador.TOMADOR_ENDERECO_CEP_FORMATADO);
        }

        if (dados_tomador.TOMADOR_ENDERECO_CEP) {
            $('#tomador_cep').val(dados_tomador.TOMADOR_ENDERECO_CEP);
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
        //var estadoId = dados_tomador.TOMADOR_ENDERECO_UF_ID || null;
        //carregarEstadosStep3(estadoId);
        
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
        }

    } catch (error) {
        console.error('Erro ao carregar estados:', error);
    }
}

// Função para preencher campos do prestador
function preencherCamposPrestador(dados_prestador) {
    
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
        
        if (dados_prestador.PRESTADOR_CODIGO_MUNICIPIO) {
            $('#prestador_codigo_municipio').val(dados_prestador.PRESTADOR_CODIGO_MUNICIPIO);
        }

        // Carregar estados e setar o estado do tomador se disponível
        var estadoId = dados_prestador.PRESTADOR_CODIGO_UF || null;
        carregarEstadosStep3(estadoId);

        if (dados_prestador.PRESTADOR_NFS_SERIE) {
            $('#prestador_serie').val(dados_prestador.PRESTADOR_NFS_SERIE);
        }

        if (dados_prestador.PRESTADOR_NFS_SITUACAO_TRIBUTARIA) {
            $('#prestador_situacao_tributaria').val(dados_prestador.PRESTADOR_NFS_SITUACAO_TRIBUTARIA);
        }

        if (dados_prestador.PRESTADOR_DATA_FATO_GERADOR) {
            $('#prestador_data_fato_gerador').val(dados_prestador.PRESTADOR_DATA_FATO_GERADOR);
        }else{
            $('#prestador_data_fato_gerador').val(new Date().toLocaleDateString('pt-BR'));
        }
        
        // Armazenar parâmetros de impostos nas variáveis globais
        parametrosImpostos.inss = parseFloat(dados_prestador.PRESTADOR_NFS_INSS || 0);
        parametrosImpostos.pis = parseFloat(dados_prestador.PRESTADOR_NFS_PIS || 0);
        parametrosImpostos.cofins = parseFloat(dados_prestador.PRESTADOR_NFS_COFINS || 0);
        parametrosImpostos.ir = parseFloat(dados_prestador.PRESTADOR_NFS_IR || 0);
        parametrosImpostos.contribuicao_social = parseFloat(dados_prestador.PRESTADOR_NFS_CONTRIBUICAO_SOCIAL || 0);
        
        console.log('Parâmetros de impostos carregados:', parametrosImpostos);
        
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
    const max_caracteres = 1000;
 
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
    }
    
    // Adiciona o novo texto
    descricao.val(novo_conteudo);
    
    // Chama a validação para atualizar o visual
    validarDescricao(descricao[0]);
 }
 
 // Função para validar descrição
 function validarDescricao(elemento) {

    const max_caracteres = 1000;
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
                   '<table class="table table-bordered jambo_table table-striped">' +
                   '<thead class="thead-dark">' +
                   '<tr>' +
                   '<th>Descrição</th>' +
                   '<th class="text-center">Quantidade</th>' +
                   '<th class="text-center">Unidade</th>' +
                   '<th class="text-center">Valor Unitário</th>' +
                   '<th class="text-center">Total</th>' +
                   '<th class="text-center">Data</th>' +
                   '<th class="text-center">Usuário</th>' +
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
                       '<td class="text-center clickable" data-texto="' + (valorUnitario + '').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)">' + valorUnitario + '</td>' +
                       '<td class="text-center clickable" data-texto="' + (valorTotal + '').replace(/"/g, '&quot;') + '" onclick="adicionarADescricao(this)"><strong>' + valorTotal + '</strong></td>' +
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
        }
        
        step3.html(html);
        console.log('Modal preenchida com sucesso');
        return true;
        
    } catch (error) {
        
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
        
        if (!$('#estado').val()) {
            erros.push('Estado do tomador');
        }
        
        if (!$('#local_prestacao').val()) {
            erros.push('Local de prestação');
        }
        
        if (!$('#lista_servico').val()) {
            erros.push('Lista de serviço');
        }

        if (!$('#situacao_tributaria').val()) {
            erros.push('Situação tributária');
        }
        
        // Validar valores
        if (!$('#valor_total_servicos').val()) {
            erros.push('Valor do serviço');
        }

        if (!$('#parcelas').val()) {
            erros.push('Parcelas');
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

        var dados = getDadosNfs();

        if (!dados) {
            Swal.close();
            setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao obter dados da NFS.',
                    confirmButtonText: 'OK'
                });
            }, 100);

            return false;
        }

        var json = JSON.stringify(dados)
        
        // Fazer requisição AJAX para emissão
        $.ajax({
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=emitirNFS&opcao=ajax',
            type: 'POST',
            dataType: 'json',
            data: {
                'json': json
            },
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                returnSendNfs(response);
            },
            error: function(xhr, status, error) {
                returnSendNfsError(xhr, status, error);
            }
        });
        
    } catch (error) {
        Swal.close();
        console.error('Erro ao emitir NFS:', error);
        setTimeout(() => {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro inesperado ao emitir NFS-e.',
                confirmButtonText: 'OK'
            });
        }, 100);
    }
}

// Função para obter dados da NFS
/**
 * Coleta todos os dados do formulário para envio ao backend
 * As funções de conversão já retornam valores padrão, então menos lógica aqui
 * A formatação e validação final são feitas no backend
 */
function getDadosNfs() {
    
    try {
        var dados = {
            nota_fiscal: {
                serie: $('#prestador_serie').val(),
                data_fato_gerador: $('#prestador_data_fato_gerador').val(),
                valor_total: obterValorNumerico('#valor_total_servicos'), // Backend formata
                valor_desconto: obterValorNumerico('#desc_incondicional'), // Backend formata
                valor_inss: obterValorNumerico('#valor_inss'), // Novo campo
                valor_pis: obterValorNumerico('#valor_pis'), // Novo campo
                valor_cofins: obterValorNumerico('#valor_cofins'), // Novo campo
                valor_ir: obterValorNumerico('#valor_ir'), // Novo campo
                valor_contribuicao_social: obterValorNumerico('#valor_contribuicao_social'), // Novo campo
                observacao: $('#observacoes').val() || null
            },
            prestador: {
                razao_social: $('#prestador_empresa_nome').val(),
                cpfcnpj: $('#prestador_cnpj').val(),
                cidade: $('#prestador_codigo_municipio').val()
            },
            tomador: {
                tomador_id: $('#tomador_id').val() || null,
                tipo: $('#tomador_tipo_pessoa').val(),
                cpfcnpj: $('#tomador_cpfcnpj').val() || null,
                ie: $('#tomador_inscricao_estadual_rg').val() || null,
                nome_razao_social: $('#tomador_razao_social').val() || null,
                sobrenome_nome_fantasia: $('#tomador_nome_fantasia').val() || null,
                email: $('#tomador_email').val() || null,
                logradouro: $('#tomador_logradouro').val() || null,
                numero_residencia: $('#tomador_numero_residencia').val() || null,
                complemento: $('#tomador_complemento').val() || null,
                bairro: $('#tomador_bairro').val() || null,
                cidade: $('#tomador_codigo_municipio').val() || null,
                cep: $('#tomador_cep').val() || null,
                ponto_referencia: $('#tomador_ponto_referencia').val() || null,
                ddd_fone_comercial: $('#tomador_ddd_fone_comercial').val() || null,
                fone_comercial: $('#tomador_fone_comercial').val() ? $('#tomador_fone_comercial').val().replace(/\D/g, '') : null,
                ddd_fone_residencial: $('#tomador_ddd_fone_residencial').val() || null,
                fone_residencial: $('#tomador_fone_residencial').val() ? $('#tomador_fone_residencial').val().replace(/\D/g, '') : null,
                ddd_fax: $('#tomador_ddd_fax').val() || null,
                fone_fax: $('#tomador_fone_fax').val() ? $('#tomador_fone_fax').val().replace(/\D/g, '') : null
            },
            itens: [{
                tributa_municipio_prestador: $('#tributa_municipio_prestador').val() || 'N',
                codigo_local_prestacao_servico: obterValorSelect2('#local_prestacao') || null,
                descricao_local_prestacao_servico: obterTextoSelect2('#local_prestacao') || null,
                codigo_item_lista_servico: obterValorSelect2('#lista_servico') || null,
                descritivo: $('#descricao').val() || 'Prestação de serviços',
                aliquota_item_lista_servico: obterValorPercentual('#aliquota'), // Backend formata
                situacao_tributaria: parseInt($('#situacao_tributaria').val()) || 0,
                situacao_tributaria_desc: obterTextoSelect2('#situacao_tributaria') || null,
                valor_desconto_incondicional: obterValorNumerico('#desc_incondicional'), // Backend formata
                valor_desconto_deducao: obterValorNumerico('#valor_deducao'), // Backend formata
                base_calculo: obterValorNumerico('#base_calculo'), // Backend formata
                valor_servico: obterValorNumerico('#valor_servico'), // Backend formata
                valor_tributavel: obterValorNumerico('#base_calculo'), // Backend formata
                valor_deducao: obterValorNumerico('#valor_deducao'), // Backend formata
                valor_issqn: obterValorNumerico('#issqn'), // Backend formata
                valor_issrf: obterValorNumerico('#issrf') // Backend formata
            }],
            forma_pagamento: {
                genero: obterValorSelect2('#genero') || null,
                numero_parcelas: $('#parcelas').find('option:selected').data('value') || null,
                parcelas: obterDadosParcelas()
            }
        };

        return dados;
    } catch (error) {
        console.error('Erro ao obter dados da NFS:', error);
        return false;
    }
}

// Função para retornar SUCESSO ao emitir NFS
function returnSendNfs(response) {
    Swal.close();
    
    // Parsear JSON se necessário
    if (typeof response === 'string') {
        response = JSON.parse(response);
    }
    
    var dados = response.data || {};
    var numeroNota = dados.numero_nfse || 'N/A';
    var linkNfse = dados.link_nfse || null;
    
    // Mensagem simples
    var mensagem = '<p style="font-size: 16px; margin-bottom: 15px;">NFS-e <strong>#' + numeroNota + '</strong> emitida com sucesso!</p>';
    
    // Configurar botões
    var config = {
        icon: 'success',
        title: 'Sucesso!',
        html: mensagem,
        confirmButtonText: 'Fechar',
        confirmButtonColor: '#3085d6',
        allowOutsideClick: false
    };
    
    // Adicionar botão para visualizar se houver link
    if (linkNfse) {
        config.showDenyButton = true;
        config.denyButtonText = 'Visualizar NFS-e';
        config.denyButtonColor = '#28a745';
    }
    
    setTimeout(() => {
        Swal.fire(config).then((result) => {
            if (result.isDenied && linkNfse) {
                window.open(linkNfse, '_blank');
            }
            $('#modalServicos').modal('hide');
            if (typeof carregarListagemNfs === 'function') {
                carregarListagemNfs();
            }
        });
    }, 100);
}

// Função para retornar erro ao emitir NFS
function returnSendNfsError($xhr, $status, $error) {
    
    Swal.close();
    console.error('Erro ao emitir NFS:', $error);
    console.error('XHR Response:', $xhr.responseText);
    
    
    // Tentar extrair mensagem de erro do responseText
    if ($xhr.responseText) {
        var errorResponse = JSON.parse($xhr.responseText);
        if (errorResponse.message) {
            mensagemErro = errorResponse.message;
        }
    }
    
    setTimeout(() => {
        Swal.fire({
            icon: 'error',
            title: 'Erro ao emitir NFS-e!',
            html: mensagemErro ? '<small style="color: #dc3545; font-size: 14px;">' + mensagemErro.replace(/\n/g, '<br>') + '</small>' : null,
            footer: null,
            confirmButtonText: 'OK',
            confirmButtonColor: 'rgb(122, 41, 49)',
            width: '65rem',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowCloseModal: false,
            allowEscapeKey: false,
        });
    }, 100);
}


// Função para visualizar dados
function visualizarDados() {
    

    $data = getDadosNfs();

    if (!$data) {
        return false;
    }

    var json = JSON.stringify($data);

    const form = document.createElement('form');
    form.method = 'POST';
    form.target = '_blank';
    form.action = 'index.php';

    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'mod';
    input.value = 'est';
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'form';
    input.value = 'faturamento_nfs';
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'submenu';
    input.value = 'preVisualizationInvoice';
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'json';
    input.value = json;
    form.appendChild(input);

    input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'opcao';
    input.value = 'ajax';
    form.appendChild(input);


    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Função para limpar todos os campos da modal
function limparTodosCamposModal() {
    try {
        console.log('Limpando todos os campos da modal...');
        
        // Resetar parâmetros de impostos
        parametrosImpostos = {
            inss: 0,
            pis: 0,
            cofins: 0,
            ir: 0,
            contribuicao_social: 0
        };
        
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
        $('#prestador_data_fato_gerador').val('');
        
        // Limpar campos do tomador (Step 2)
        $('#tomador_id').val('');
        $('#tomador_tipo_pessoa_desc').val('');
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
        $('#tomador_codigo_municipio').val('');
        $('#tomador_cep_formatado').val('');
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
        
        // Limpar todos os campos dependentes
        limparCamposDependentes();
        
        // Limpar campos monetários usando inputmask para garantir valores corretos
        $('#valor_servico').inputmask('setvalue', 0);
        $('#desc_incondicional').inputmask('setvalue', 0);
        $('#valor_deducao').inputmask('setvalue', 0);
        $('#base_calculo').inputmask('setvalue', 0);
        $('#aliquota').val('0,00');
        $('#issqn').inputmask('setvalue', 0);
        $('#issrf').inputmask('setvalue', 0);
        $('#tributa_municipio_prestador').val('N');
        $('#descricao').val('');
        
        // Resetar contador de caracteres da descrição
        $('#caracteres-restantes').text('(200 caracteres restantes)');
        
        // Limpar novos campos de valores (Step 4)
        $('#valor_inss').inputmask('setvalue', 0);
        $('#valor_pis').inputmask('setvalue', 0);
        $('#valor_cofins').inputmask('setvalue', 0);
        $('#valor_ir').inputmask('setvalue', 0);
        $('#valor_contribuicao_social').inputmask('setvalue', 0);
        $('#valor_total_aliquota').inputmask('setvalue', 0);
        $('#valor_total_base_calculo').inputmask('setvalue', 0);
        $('#valor_total_deducao').inputmask('setvalue', 0);
        $('#valor_total_desconto').inputmask('setvalue', 0);
        $('#valor_total_servicos').inputmask('setvalue', 0);
        $('#parcelas').val('');
        $('#observacoes').val('');
        
        // Limpar step 3 (lista de serviços)
        var step3 = $('#step_3 .panel_servicos .panel-body');
        step3.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Carregando serviços...</div>');
        
        // Limpar parcelas
        if (typeof limparParcelas === 'function') {
            limparParcelas();
        }
        
        // Voltar para o primeiro step
        if (wizard && typeof wizard.goToStep === 'function') {
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

/**
 * Atualiza o campo tributa_municipio_prestador comparando o município do prestador com o local de prestação
 * Se forem iguais: S, se diferentes: N
 */
function atualizarTributaMunicipioPrestador() {
    try {
        // Obter código do município do prestador
        var codigoMunicipioPrestador = $('#prestador_codigo_municipio').val();
        
        // Obter código do local de prestação (valor selecionado no select2)
        var codigoLocalPrestacao = $('#local_prestacao').val();
        
        
        // Validar se ambos os valores existem
        if (!codigoMunicipioPrestador || !codigoLocalPrestacao) {
            $('#tributa_municipio_prestador').val('N');
            return;
        }
        
        // Comparar os códigos dos municípios
        if (codigoMunicipioPrestador === codigoLocalPrestacao) {
            // Se forem iguais, tributa no município do prestador
            $('#tributa_municipio_prestador').val('S');
        } else {
            // Se forem diferentes, não tributa no município do prestador
            $('#tributa_municipio_prestador').val('N');
        }
        
    } catch (error) {
        console.error('Erro ao atualizar tributa_municipio_prestador:', error);
        $('#tributa_municipio_prestador').val('N');
    }
}

// Expor funções globalmente para uso externo
window.abrirModalServicos = abrirModalServicos;
window.alimentarTelaServicos = alimentarTelaServicos;
window.emitirNFS = emitirNFS;
window.visualizarDados = visualizarDados;
window.limparTodosCampos = limparTodosCampos;
window.limparTodosCamposModal = limparTodosCamposModal;

// Função principal de cálculo (NOVA)
window.calcularTodosCampos = calcularTodosCampos;

// Funções auxiliares
window.limparCamposCalculados = limparCamposCalculados;
window.obterValorNumerico = obterValorNumerico;
window.obterValorPercentual = obterValorPercentual;
window.controlarEstadoBotoes = controlarEstadoBotoes;
window.aplicarMascara = aplicarMascara;
window.aplicarMascaraTelefone = aplicarMascaraTelefone;
window.configurarMascaras = configurarMascaras;
window.obterValorSelect2 = obterValorSelect2;
window.obterTextoSelect2 = obterTextoSelect2;
window.configurarListaServicos = configurarListaServicos;
window.configurarSituacaoTributaria = configurarSituacaoTributaria;
window.limparCamposDependentes = limparCamposDependentes;
window.configurarParcelas = configurarParcelas;
window.atualizarTributaMunicipioPrestador = atualizarTributaMunicipioPrestador;


