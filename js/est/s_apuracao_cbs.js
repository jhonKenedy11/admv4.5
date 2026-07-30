function submitSalvarCredencial() {
    var cnpj     = $('#cnpj_base').val();
    var clientId = $('#client_id').val();

    if (!cnpj || cnpj.length !== 8) {
        Swal.fire({ title: 'Atenção', text: 'Informe o CNPJ Base com 8 dígitos.', icon: 'warning' });
        $('#cnpj_base').focus();
        return;
    }
    if (!clientId) {
        Swal.fire({ title: 'Atenção', text: 'Informe o Client ID.', icon: 'warning' });
        $('#client_id').focus();
        return;
    }

    Swal.fire({ title: 'Salvando...', allowOutsideClick: false, allowEscapeKey: false, didOpen: function() { Swal.showLoading(); } });

    $.ajax({
        type: 'POST',
        url: document.lancamento.action,
        dataType: 'json',
        data: {
            mod:            'est',
            form:           'apuracao_cbs',
            submenu:        'salvar_credencial',
            opcao:          'ajax',
            cnpj_base:      cnpj,
            client_id:      clientId,
            client_secret:  $('#client_secret').val(),
            ambiente:       $('#ambiente').val(),
            webhook_url:    $('#webhook_url').val(),
            webhook_secret: $('#webhook_secret').val()
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                apuracaoAtualizarCardCredencial(response.data);
                $('#modalCredenciais').modal('hide');
                Swal.fire({ icon: 'success', title: 'Sucesso', text: response.message, timer: 3000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Erro', text: response.message });
            }
        },
        error: function(xhr) {
            Swal.close();
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erro ao salvar credencial.';
            Swal.fire({ icon: 'error', title: 'Erro', text: msg });
        }
    });
}

function submitGerarToken() {
    var cnpj     = $('#cnpj_base').val();
    var clientId = $('#client_id').val();

    if (!cnpj || cnpj.length !== 8) {
        Swal.fire({ title: 'Atenção', text: 'Informe o CNPJ Base com 8 dígitos.', icon: 'warning' });
        $('#cnpj_base').focus();
        return;
    }
    if (!clientId) {
        Swal.fire({ title: 'Atenção', text: 'Informe o Client ID antes de testar.', icon: 'warning' });
        $('#client_id').focus();
        return;
    }

    Swal.fire({ title: 'Gerando token...', allowOutsideClick: false, allowEscapeKey: false, didOpen: function() { Swal.showLoading(); } });

    $.ajax({
        type: 'POST',
        url: document.lancamento.action,
        dataType: 'json',
        data: {
            mod:           'est',
            form:          'apuracao_cbs',
            submenu:       'gerar_token',
            opcao:         'ajax',
            cnpj_base:     cnpj,
            client_id:     clientId,
            client_secret: $('#client_secret').val(),
            ambiente:      $('#ambiente').val()
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                apuracaoAtualizarCardCredencial(response.data);
                Swal.fire({ icon: 'success', title: 'Token gerado', text: response.message, timer: 4000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Falha', text: response.message });
            }
        },
        error: function(xhr) {
            Swal.close();
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Erro ao gerar token.';
            Swal.fire({ icon: 'error', title: 'Erro', text: msg });
        }
    });
}

function submitSolicitarConsulta() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';

    if (f.cnpj_base.value == '' || f.cnpj_base.value.length != 8) {
        swal.fire({
            title: 'Atenção',
            text: 'Informe o CNPJ Base com 8 dígitos.',
            icon: 'warning',
            timer: 2000
        });
        return false;
    }

    swal.fire({
        title: 'Atenção',
        text: 'Deseja solicitar a consulta na Receita Federal? (limite de 2/dia)',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, solicitar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = 'solicitar_consulta';
            f.aba.value = 'consulta';
            f.submit();
        }
    });
}

function submitDownloadDebitos(tiquete) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';

    if (tiquete) {
        f.tiquete.value = tiquete;
    }
    if (f.tiquete.value == '') {
        swal.fire({
            title: 'Atenção',
            text: 'Informe ou selecione um tíquete.',
            icon: 'warning',
            timer: 2000
        });
        return false;
    }

    f.submenu.value = 'download_debitos';
    f.aba.value = 'credito';
    f.submit();
}

function submitVerDebitos(idHistorico, tiquete) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';
    f.submenu.value = 'ver_debitos';
    f.id_historico.value = idHistorico;
    if (tiquete) {
        f.tiquete.value = tiquete;
    }
    f.aba.value = 'credito';
    f.submit();
}

// Guarda a aba ativa para restaurar após o reload (submit sem AJAX)
function apuracaoSetAba(aba) {
    document.lancamento.aba.value = aba;
}

// Recarrega o histórico para verificar se o retorno do webhook já chegou
function submitAtualizarHistorico() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';
    f.submenu.value = 'ver_debitos';
    f.aba.value = 'consulta';
    f.submit();
}

function submitUsarTiquete(tiquete, idHistorico) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';
    f.tiquete.value = tiquete;
    f.id_historico.value = idHistorico;
    f.submenu.value = 'ver_debitos';
    f.aba.value = 'credito';
    f.submit();
}

// Estado do evento em emissão (preenchido ao abrir o modal)
var apuracaoEventoPendente = { chave: '', tp: '', papel: '', idDebito: '' };

/**
 * Abre o modal de confirmação de evento fiscal para uma chave.
 * A aba é preservada conforme o papel (crédito = destinatário, débito = emitente).
 */
function submitEmitirEvento(chaveDfe, tpEvento, papel, idDebito) {
    apuracaoEventoPendente = {
        chave: chaveDfe,
        tp: tpEvento,
        papel: papel,
        idDebito: idDebito || ''
    };

    document.getElementById('modalEventoTipo').innerText = tpEvento;
    document.getElementById('modalEventoChave').innerText = chaveDfe;
    document.getElementById('modalEventoPapel').innerText = papel;
    document.getElementById('modalEventoObs').value = '';

    $('#modalEventoCbs').modal('show');
}

/**
 * Confirma o registro do evento e submete o formulário.
 */
function apuracaoConfirmarEvento() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';
    f.submenu.value = 'emitir_evento';
    f.chave_dfe.value = apuracaoEventoPendente.chave;
    f.tp_evento.value = apuracaoEventoPendente.tp;
    f.papel.value = apuracaoEventoPendente.papel;
    f.id_debito.value = apuracaoEventoPendente.idDebito;
    f.observacao.value = document.getElementById('modalEventoObs').value;
    f.aba.value = (apuracaoEventoPendente.papel === 'EMITENTE') ? 'debito' : 'credito';
    f.submit();
}

/**
 * Hook para download de XML por chave (serviço único de consulta - evolução futura).
 */
function apuracaoBaixarXml(chaveDfe) {
    swal.fire({
        title: 'Baixar XML',
        html: 'Consulta do XML pela chave <br><small>' + chaveDfe + '</small><br><br>'
            + 'Integração com o serviço de consulta por chave (NfeDistribuicaoDFe) será habilitada em breve.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'apuracao_cbs';
    f.submenu.value = '';
    f.id_historico.value = '';
    f.id_debito.value = '';
    f.submit();
}

/**
 * Atualiza o card de status de credenciais no DOM após salvar/testar via AJAX,
 * sem recarregar a página.
 *
 * @param {Object} data  Objeto retornado pelo c_api_response (data)
 */
function apuracaoAtualizarCardCredencial(data) {
    if (!data) return;

    // Propaga o CNPJ base para os campos hidden do form principal
    // (usado em outros submits como solicitar_consulta / download_debitos)
    if (data.cnpj_base) {
        $('[name=cnpj_base]').val(data.cnpj_base);
    }

    // Reconstrói a área de resumo do card
    if (data.cnpj_base && data.ambiente) {
        var ambienteLabel = data.ambiente === 'PRODUCAO' ? 'label-primary' : 'label-default';
        var html =
            '<span class="apuracao-cred-title"><i class="fa fa-lock"></i> Credenciais da API</span>' +
            '<span class="apuracao-cred-item"><span class="text-muted">CNPJ:</span> <strong>' + data.cnpj_base + '</strong></span>' +
            '<span class="apuracao-cred-item"><span class="text-muted">Ambiente:</span> <span class="label ' + ambienteLabel + '">' + data.ambiente + '</span></span>' +
            '<span class="apuracao-cred-item"><span class="text-muted">Token:</span> <span id="apuracao-token-status"></span></span>';
        $('#apuracao-cred-resumo').html(html);
    }

    // Atualiza o status do token
    if (data.token_expira) {
        $('#apuracao-token-status').html('<span class="label label-success">válido até ' + data.token_expira + '</span>');
    } else {
        $('#apuracao-token-status').html('<span class="label label-default">gerado sob demanda</span>');
    }

    // Altera o rótulo do botão para "Editar credenciais"
    $('#apuracao-btn-credencial').html('<i class="fa fa-cog"></i> Editar credenciais');
}
