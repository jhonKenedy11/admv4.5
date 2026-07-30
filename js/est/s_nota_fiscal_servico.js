function logOpenNewWindow(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

function submitSearch() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_servico';
    f.submenu.value = 'pesquisa';
    f.submit();
}

function submitRegister() {
    $('#modalServicos').modal('show');
}

/**
 * Exclui uma Nota Fiscal de Serviço via AJAX
 * Utiliza c_nfs_response para receber resposta padronizada do backend
 * 
 * @param {number} id - ID da nota fiscal a ser excluída
 */
function deletInvoiceFromModal() {
    


    var id = document.querySelector('#id_modal').value;

    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja excluir a Nota Fiscal de Serviço?',
        icon: 'warning',
        width: '400px',
        showCancelButton: true,
        confirmButtonText: 'Sim, Excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Processando...',
                text: 'Aguarde a exclusão da NFS',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Envia requisição AJAX
            $.ajax({
                type: "POST",
                url: window.location.pathname,
                data: {
                    'mod': 'est',
                    'form': 'nota_fiscal_servico',
                    'submenu': 'deletInvoice',
                    'id': id,
                    'opcao': 'ajax'
                },
                dataType: "json",
                complete: function(xhr) {
                    Swal.close();
                    const response = xhr.responseJSON || {};
                    const status = xhr.status;
                    
                    // Status 200-299 = Sucesso na comunicação
                    if (status >= 200 && status < 300) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: response.message || 'Nota Fiscal excluída com sucesso!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Recarrega a página para atualizar a listagem
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: response.message || 'Erro ao excluir nota fiscal',
                                confirmButtonText: 'OK'
                            });
                        }
                        return;
                    }
                    
                    // Status 422 = Erro de validação
                    if (status === 422) {
                        const msg = response.message || 'Erro de validação dos dados';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validação',
                            text: msg,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Status 400 = Erro do servidor
                    if (status === 400) {
                        const msg = response.message || 'Erro ao processar a solicitação';
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: msg,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Status 500 = Erro interno do servidor
                    if (status === 500) {
                        const msg = response.message || 'Erro interno do servidor';
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro no Servidor',
                            text: msg,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    // Outros status = Erro de comunicação
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro de Comunicação',
                        text: 'Não foi possível conectar ao servidor',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

function viewLogNFS() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_servico';
    f.submenu.value = 'log';
    f.opcao.value = '';
    f.submit();
}


function logSubmitSearch() {
    var f = document.lancamento;
    f.submenu.value = 'log';
    f.opcao.value = 'pesquisa';
    f.submit();
}

function logBackNFS() {
    var f = document.lancamento;
    f.submenu.value = '';
    f.opcao.value = '';
    f.submit();
}

function printInvoice(linkNfse) {
    Swal.fire({
        title: 'Imprimir Nota Fiscal',
        text: 'Deseja abrir a nota fiscal para impressão?',
        icon: 'question',
        width: '400px',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sim, Imprimir',
        confirmButtonColor: '#5cb85c',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(linkNfse, '_blank');
        }
    });
}

function editInvoice(id) {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_servico';
    f.submenu.value = 'edit';
    f.id.value = id;
    f.submit();
}

function viewInvoice(id) {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_servico';
    f.submenu.value = 'view';
    f.id.value = id;
    f.submit();
}

function LogViewXML(id) {
    Swal.fire({
        title: 'Carregando XML...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: window.location.pathname,
        type: 'POST',
        data: {
            'mod': 'est',
            'form': 'nota_fiscal_servico',
            'submenu': 'logSearchXMLLog',
            'opcao': 'ajax',
            'id': id
        },
        success: function(response) {
            try {
                // Tenta fazer parse manual para ter mais controle
                var data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (data.success) {
                    Swal.fire({
                        title: 'XML Retorno - Log #' + id,
                        html: '<textarea class="form-control" rows="15" readonly style="font-family: monospace; font-size: 11px; white-space: pre-wrap;">' + 
                              data.xml + '</textarea>',
                        width: '800px',
                        confirmButtonText: 'Fechar',
                        customClass: {
                            popup: 'swal-wide'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Erro ao buscar XML'
                    });
                }
            } catch (e) {
                console.error('Erro ao fazer parse do JSON:', e);
                console.error('Response recebida:', response);
                console.error('Tipo da response:', typeof response);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Parse',
                    html: 'Resposta inválida do servidor.<br><small>Verifique o console (F12) para mais detalhes.</small>'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('===== ERRO AJAX =====');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Status Code:', xhr.status);
            console.error('Response Text:', xhr.responseText);
            console.error('Response Headers:', xhr.getAllResponseHeaders());
            
            Swal.fire({
                icon: 'error',
                title: 'Erro na Requisição',
                html: 'Status: ' + status + '<br>Erro: ' + error + '<br><small>Verifique o console (F12)</small>'
            });
        }
    });
}

function cancelInvoice(id) {
    // Fecha a modal Bootstrap
    $('#modalManutencao').modal('hide');

    // Abre o SweetAlert
    Swal.fire({
        title: "Cancelar Nota Fiscal",
        text: "Informe o motivo do cancelamento:",
        input: "textarea",
        width: '700px',
        height: '200px',
        inputAttributes: {
            autocapitalize: "off",
            maxLength: 1000

        },
        showCancelButton: true,
        confirmButtonText: "Cancelar NFS",
        cancelButtonText: "Voltar",
        allowOutsideClick: false,
        customClass: {
            input: 'swal-input-custom',
            title: 'swal-title-custom',
            htmlContainer: 'swal-text-custom',
            confirmButton: 'swal-confirm-custom',
            cancelButton: 'swal-cancel-custom',
        }
    }).then((result) => {

        
        if (result.isConfirmed) {
            const motivo = result.value.trim();
            
            if (!motivo) {
                Swal.fire('Erro', 'Informe o motivo do cancelamento', 'error');
                return;
            }

            // Mostra loading
            Swal.fire({
                title: 'Processando...',
                text: 'Aguarde o cancelamento da NFS-e',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Envia para o servidor
            $.ajax({
                type: "POST",
                url: window.location.pathname,
                data: {
                    'mod': 'est',
                    'form': 'nota_fiscal_servico',
                    'submenu': 'cancelInvoice',
                    'id': id,
                    'motivo': motivo,
                    'opcao': 'ajax'
                },
                dataType: "json",
                complete: function(xhr) {

                    
                    Swal.close();
                    const response = xhr.responseJSON || {};
                    const status = xhr.status;
                    
                    // Status 200-299 = Sucesso na comunicação
                    if (status >= 200 && status < 300) {
                        if (response.success) {
                            Swal.fire('Sucesso!', 'Nota Fiscal cancelada com sucesso!', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Erro', response.message || 'Erro ao cancelar', 'error');
                        }
                        return;
                    }
                    
                    // Status 422 = Erro de validação
                    if (status === 422) {
                        const msg = response.message || 'Erro de validação dos dados';
                        Swal.fire('Validação', msg, 'warning');
                        return;
                    }
                    
                    // Status 400 = Erro do servidor
                    if (status === 400) {
                        const msg = response.message || 'Erro ao processar a solicitação';
                        Swal.fire('Erro', msg, 'error');
                        return;
                    }
                    
                    // Outros status = Erro de comunicação
                    Swal.fire('Erro de Comunicação', 'Não foi possível conectar ao servidor', 'error');
                }
            });
        } else {
            // Se cancelar, reabre a modal
            $('#modalManutencao').modal('show');
        }
    });
}   


// Variáveis globais para armazenar dados da nota fiscal na modal
var currentInvoiceId = null;
var currentInvoiceSituacao = null;
var currentInvoiceLink = null;

/**
 * Abre a modal de manutenção e configura os botões de acordo com a situação da nota
 * @param {string} id - ID da nota fiscal
 * @param {string} situacao - Situação da nota (0=Aberta, 1=Emitida, 2=Cancelada)
 */
function openMaintenanceModal(id, situacao) {
    // Valida ID
    if (!id) {
        Swal.fire({
            title: 'Erro!',
            text: 'ID da nota fiscal não encontrado!',
            icon: 'error',
        });
        return false;
    }

    // Armazena os dados globalmente
    currentInvoiceId = id;
    currentInvoiceSituacao = situacao;
    
    // Define o ID no campo hidden
    document.querySelector('#id_modal').value = id;
    
    // Obtém referências aos botões
    var btnView = document.getElementById('btnView');
    var btnDelete = document.getElementById('btnDelete');
    var btnConsulta = document.getElementById('btnConsulta');
    var btnCancelar = document.getElementById('btnCancelar');
    
    // Configura o estado dos botões baseado na situação
    btnView.disabled = true; // Sempre desabilitado
    
    // Situação 0 = Aberta (pode deletar, não pode consultar/cancelar)
    // Situação 1 = Emitida (não pode deletar, pode consultar/cancelar)
    // Situação 2+ = Cancelada/Outras (não pode deletar, não pode consultar/cancelar)
    
    if (situacao == '0') {
        // Nota aberta: permite deletar
        btnDelete.disabled = false;
        btnConsulta.disabled = true;
        btnCancelar.disabled = true;
    } else if (situacao == '1') {
        // Nota emitida: permite consultar e cancelar, não permite deletar
        btnDelete.disabled = true;
        btnConsulta.disabled = false;
        btnCancelar.disabled = false;
    } else {
        // Outras situações: desabilita tudo
        btnDelete.disabled = true;
        btnConsulta.disabled = true;
        btnCancelar.disabled = true;
    }

    // Abre a modal
    $('#modalManutencao').modal('show');
}
