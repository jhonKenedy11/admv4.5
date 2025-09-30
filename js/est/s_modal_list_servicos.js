/**
 * Funções para gerenciamento da modal de serviços
 * Arquivo: js/est/modal_servicos.js
 */

// Função para abrir modal de serviços
function abrirModalServicos(id, tipoDocumento, event) {
    debugger
    
    // Prevenir comportamento padrão e propagação de eventos
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    try {
        // Validar parâmetros
        if (!id || id <= 0) {
            console.error('ID inválido:', id);
            Swal.fire({ icon: 'error', title: 'Erro!', text: 'ID inválido fornecido.' });
            return false;
        }
        
        if (!tipoDocumento) {
            console.error('Tipo de documento inválido:', tipoDocumento);
            Swal.fire({ icon: 'error', title: 'Erro!', text: 'Tipo de documento inválido.' });
            return false;
        }
        
        var modal = $('#modalServicos');
        var modalBody = modal.find('.modal-body');
        
        if (!modal.length) {
            console.error('Modal #modalServicos não encontrada');
            Swal.fire({ icon: 'error', title: 'Erro!', text: 'Modal de serviços não encontrada.' });
            return false;
        }
        
        // Limpar conteúdo anterior da modal
        modalBody.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Carregando serviços...</div>');
        
        // Abrir modal
        modal.modal('show');
        
        // Fazer requisição AJAX para buscar os serviços
        $.ajax({
            url: document.URL + 'mod=est&form=faturamento_nfs&submenu=buscarServicos&opcao=ajax',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                tipo_documento: tipoDocumento
            },
            // Adicionar configurações para manter sessão
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(xhr) {
                // Adicionar headers para manter sessão
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                debugger
                console.log('Resposta recebida:', response);
                
                // Verificar se a resposta indica redirecionamento para login
                if (response && response.redirect) {
                    console.warn('Redirecionamento detectado:', response.redirect);
                    modalBody.html('<div class="alert alert-warning">Sessão expirada. Por favor, faça login novamente.</div>');
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Sessão Expirada!', 
                        text: 'Sua sessão expirou. Por favor, faça login novamente.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
                
                if (response && response.success) {
                    preencherModalServicos(response.data || []);
                    return false; // Parar execução após preencher a tabela
                } else {
                    var mensagem = 'Nenhum serviço encontrado para este documento.';
                    if (response && response.message) {
                        mensagem = response.message;
                    }
                    modalBody.html('<div class="alert alert-warning">' + mensagem + '</div>');
                    Swal.fire({ 
                        icon: 'info', 
                        title: 'Nenhum Serviço', 
                        text: mensagem,
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            },
            error: function(xhr, status, error) {
                debugger
                console.error('Erro ao buscar serviços:', error);
                console.error('Status:', status);
                console.error('XHR:', xhr);
                
                modalBody.html('<div class="alert alert-danger">Erro ao carregar serviços. Entre em contato com o suporte.</div>');
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Erro!', 
                    text: 'Erro ao carregar serviços. Entre em contato com o suporte.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });
        
        // Retornar false para evitar propagação de eventos
        return false;
        
    } catch (error) {
        debugger
        console.error('Erro ao abrir modal de serviços:', error);
        Swal.fire({ 
            icon: 'error', 
            title: 'Erro!', 
            text: 'Erro inesperado ao abrir modal de serviços.',
            confirmButtonText: 'OK'
        });
        return false;
    }
}

// Função para preencher a modal com os serviços
function preencherModalServicos(servicos) {
    debugger
    try {
        var modalBody = $('#modalServicos .modal-body');
        
        if (!modalBody.length) {
            console.error('Modal body não encontrado');
            Swal.fire({ 
                icon: 'error', 
                title: 'Erro!', 
                text: 'Modal de serviços não encontrada.',
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        var html = '';
        
        if (!servicos || servicos.length === 0) {
            html = '<div class="alert alert-info">Nenhum serviço cadastrado para este documento.</div>';
        } else {
            html = '<div class="table-responsive">' +
                   '<table class="table table-striped table-bordered table-hover">' +
                   '<thead class="thead-dark">' +
                   '<tr>' +
                   '<th>ID</th>' +
                   '<th>Descrição</th>' +
                   '<th>Quantidade</th>' +
                   '<th>Unidade</th>' +
                   '<th>Valor Unitário</th>' +
                   '<th>Total</th>' +
                   '<th>Data</th>' +
                   '<th>Horário</th>' +
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
                        valorUnitario = 'R$ ' + parseFloat(servico.VALUNITARIO).toFixed(2).replace('.', ',');
                    }
                    
                    if (servico.TOTALSERVICO && !isNaN(parseFloat(servico.TOTALSERVICO))) {
                        valorTotal = 'R$ ' + parseFloat(servico.TOTALSERVICO).toFixed(2).replace('.', ',');
                    }
                    
                    if (servico.CUSTOUSER && !isNaN(parseFloat(servico.CUSTOUSER))) {
                        custoUser = 'R$ ' + parseFloat(servico.CUSTOUSER).toFixed(2).replace('.', ',');
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
                
                // Formatação de horário
                var horarioFormatado = 'N/A';
                if (servico.HORAINI && servico.HORAFIM) {
                    horarioFormatado = (servico.HORAINI || '') + ' - ' + (servico.HORAFIM || '');
                } else if (servico.HORATOTAL) {
                    horarioFormatado = servico.HORATOTAL;
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
                       '<td class="text-center"><small>' + (servico.ID || 'N/A') + '</small></td>' +
                       '<td title="' + (servico.DESCSERVICO || servico.DESCRICAO || '') + '">' + descricao + '</td>' +
                       '<td class="text-center">' + quantidadeExibida + '</td>' +
                       '<td class="text-center">' + (servico.UNIDADE || 'N/A') + '</td>' +
                       '<td class="text-right">' + valorUnitario + '</td>' +
                       '<td class="text-right"><strong>' + valorTotal + '</strong></td>' +
                       '<td class="text-center"><small>' + dataFormatada + '</small></td>' +
                       '<td class="text-center"><small>' + horarioFormatado + '</small></td>' +
                       '<td class="text-center"><small>' + (servico.NOME_USUARIO || 'N/A') + '</small></td>' +
                       '</tr>';
                
                // Adicionar linha de observações se existir
                if (servico.OBSSERVICO && servico.OBSSERVICO.trim() !== '') {
                    html += '<tr class="table-info">' +
                           '<td colspan="9" class="text-muted">' +
                           '<small><strong>Observações:</strong> ' + servico.OBSSERVICO + '</small>' +
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
                   '<strong>Valor Total:</strong> R$ ' + totalGeral.toFixed(2).replace('.', ',') +
                   '</div>' +
                   '</div>' +
                   '</div>';
        }
        
        modalBody.html(html);
        console.log('Modal preenchida com sucesso');
        return false; // Parar execução após preencher a tabela
        
    } catch (error) {
        debugger
        console.error('Erro ao preencher modal de serviços:', error);
        var modalBody = $('#modalServicos .modal-body');
        if (modalBody.length) {
            modalBody.html('<div class="alert alert-danger">Erro ao carregar dados dos serviços.</div>');
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
