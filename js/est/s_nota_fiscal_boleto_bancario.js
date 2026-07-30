/**
 * s_nota_fiscal_boleto_bancario.js
 * Controla o stream SSE de recuperação de PDFs de boletos e a interação
 * do layout sidebar + iframe da tela nota_fiscal_boleto_bancario.
 */

// ---------------------------------------------------------------------------
// Estado global da tela
// ---------------------------------------------------------------------------

/** @type {EventSource|null} */
var _sseConnection = null;

/** Número de sequência do primeiro boleto finalizado (para abrir automaticamente) */
var _primeiroBoletoPronto = true;

// ---------------------------------------------------------------------------
// Ponto de entrada — chamado pelo botão "Imprimir Boleto"
// ---------------------------------------------------------------------------


/**
 * Inicia o stream SSE que recupera os PDFs dos boletos vinculados à NF.
 * @param {number} idRegistro  ID da EST_NOTA_FISCAL
 */
/**
 * Inicia o stream SSE que recupera os PDFs dos boletos vinculados à NF.
 * @param {number} idRegistro  ID da EST_NOTA_FISCAL
 */
function iniciarEmissaoBoletos(idRegistro) {
    if (!idRegistro) {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'ID da Nota Fiscal não informado.' });
        return;
    }

    // Evita múltiplas conexões simultâneas
    if (_sseConnection) {
        _sseConnection.close();
        _sseConnection = null;
    }

    _primeiroBoletoPronto = true;

    _limparContainerBoletos();
    _desabilitarBotoesEmail();
    _desabilitarBotaoImprimir();

    $path = window.location.pathname;

    var url = $path + '?mod=blt&form=sse_imprime_boleto&id=' + idRegistro + '&opcao=ajax';

    _sseConnection = new EventSource(url);

    _sseConnection.addEventListener('progresso', _onProgresso);
    _sseConnection.addEventListener('boleto',    _onBoleto);
    _sseConnection.addEventListener('erro',      _onErro);
    _sseConnection.addEventListener('concluido', _onConcluido);
    _sseConnection.addEventListener('sessao_invalida', _onSessaoInvalida);
    _sseConnection.addEventListener('registro_nao_encontrado', _onRegistroNaoEncontrado);

    _sseConnection.onerror = _onSseError;
}

// ---------------------------------------------------------------------------
// Handlers de eventos SSE
// ---------------------------------------------------------------------------

function _onProgresso(event) {
    var data = JSON.parse(event.data);

    // Insere (ou mantém) o card em estado de carregamento — não limpar o container aqui,
    // senão cada parcela apaga as anteriores e só a última permanece visível.
    var cardExistente = document.getElementById('boleto_card_' + data.id_lancamento);
    if (!cardExistente) {
        _inserirCardProcessando(data);
    }
}

function _onBoleto(event) {
    var data = JSON.parse(event.data);

    _atualizarCardFinalizado(data);

    // Abre automaticamente o primeiro boleto pronto no iframe
    if (_primeiroBoletoPronto) {
        _primeiroBoletoPronto = false;
        var card = document.getElementById('boleto_card_' + data.id_lancamento);
        if (card) _selecionarCard(card);
        _abrirPdfNoViewer(data.pdf_base64, data.id_lancamento);
    }
}

function _onErro(event) {
    var data = JSON.parse(event.data);
    _atualizarCardErro(data);
}

function _onConcluido(event) {
    var data = JSON.parse(event.data);

    if (_sseConnection) {
        _sseConnection.close();
        _sseConnection = null;
    }

    _habilitarBotaoImprimir();

    // Remove o placeholder vazio se existir
    var empty = document.getElementsByClassName('boletos_empty');
    if (empty.length > 0) {
        empty[0].remove();
    }
    

    if (data.sucesso > 0) {
        _habilitarBotoesEmail();
    }
}

function _onSseError(error) {
    console.error('Erro na conexão SSE:', error);

    if (_sseConnection) {
        _sseConnection.close();
        _sseConnection = null;
    }

    _habilitarBotaoImprimir();

    var container = document.getElementById('boletos_container');
    var semCards  = container.querySelectorAll('.boleto_card').length === 0;

    // Se nenhum card foi inserido, o stream falhou antes de começar
    // (ex: 404 sem boletos emitidos, 401 sessão expirada, erro de rede)
    if (semCards) {
        _exibirErroContainer(error.message);

        Swal.fire({
            icon: 'error',
            title: 'Erro ao buscar boletos',
            text: error.message,
            confirmButtonColor: '#d33'
        });
    }
}

function _onSessaoInvalida(event) {
    if (_sseConnection) {
        _sseConnection.close();
        _sseConnection = null;
    }

    _habilitarBotaoImprimir();

    var data = JSON.parse(event.data);

    Swal.fire({
        icon: 'error',
        title: 'Sessão de controle inválida',
        text: data.mensagem,
    });

    //window.location.href = '<?php echo ADMhttpBib; ?>/login.php';


}

function _onRegistroNaoEncontrado(event) {
    var data = JSON.parse(event.data);
    if (_sseConnection) {
        _sseConnection.close();
        _sseConnection = null;
    }
    _habilitarBotaoImprimir();
    Swal.fire({
        icon: 'error',
        width: 700,
        fontSize: "14px",
        title: 'Erro ao gerar boletos',
        text: data.mensagem,
        confirmButtonColor: '#d33',
        customClass: {
            popup: 'swal_custom'
        }
    });
}

// ---------------------------------------------------------------------------
// Funções auxiliares para atualizar cards
// ---------------------------------------------------------------------------

/**
 * Insere um card em estado de processamento
 * @param {Object} data - Dados do progresso
 */
function _inserirCardProcessando(data) {
    var container = document.getElementById('boletos_container');
    
    var card = document.createElement('div');
    card.id = 'boleto_card_' + data.id_lancamento;
    card.className = 'boleto_card boleto_card_processando';
    card.innerHTML = `
        <div class="boleto_card_header">
            <span class="boleto_card_numero">Boleto ${data.seq}/${data.total}</span>
            <span class="boleto_card_id">ID: ${data.id_lancamento}</span>
        </div>
        <div class="boleto_card_body">
            <div class="boleto_card_spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <p class="boleto_card_mensagem">${data.mensagem}</p>
        </div>
    `;
    
    container.appendChild(card);
}

/**
 * Atualiza o card quando ocorre um erro
 * @param {Object} data - Dados do erro
 */
function _atualizarCardErro(data) {
    var card = document.getElementById('boleto_card_' + data.id_lancamento);
    
    if (card) {
        card.classList.remove('boleto_card_processando');
        card.classList.add('boleto_card_erro');
        
        card.innerHTML = `
            <div class="boleto_card_header">
                <span class="boleto_card_numero">Boleto ${data.seq}/${data.total}</span>
                <span class="boleto_card_id">ID: ${data.id_lancamento}</span>
            </div>
            <div class="boleto_card_body">
                <div class="boleto_card_status boleto_card_status_erro">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Erro ao processar</span>
                </div>
                <p class="boleto_card_mensagem_erro">
                    ${data.mensagem}
                </p>
            </div>
        `;
    } else {
        // Se o card ainda não existe, cria um novo com erro
        _inserirCardErro(data);
    }
}

/**
 * Insere um novo card já no estado de erro
 * @param {Object} data - Dados do erro
 */
function _inserirCardErro(data) {
    var container = document.getElementById('boletos_container');
    
    var card = document.createElement('div');
    card.id = 'boleto_card_' + data.id_lancamento;
    card.className = 'boleto_card boleto_card_erro';
    card.innerHTML = `
        <div class="boleto_card_header">
            <span class="boleto_card_numero">Boleto ${data.seq}/${data.total}</span>
            <span class="boleto_card_id">ID: ${data.id_lancamento}</span>
        </div>
        <div class="boleto_card_body">
            <div class="boleto_card_status boleto_card_status_erro">
                <i class="fas fa-exclamation-circle"></i>
                <span>Erro ao processar</span>
            </div>
            <p class="boleto_card_mensagem_erro">
                ${data.mensagem}
            </p>
        </div>
    `;
    
    container.appendChild(card);
}

// ---------------------------------------------------------------------------
// Manipulação de cards
// ---------------------------------------------------------------------------
/**
 * Limpa o container de boletos e exibe o template de busca
 */
function _limparContainerBoletos() {
    var container = document.getElementById('boletos_container');
    container.innerHTML = document.getElementById('tpl_buscando').innerHTML;
}

/**
 * Exibe o template de erro
 * @param {string} mensagem Mensagem de erro
 */
function _exibirErroContainer(mensagem) {
    var tpl = document.getElementById('tpl_erro_container').cloneNode(true);
    tpl.removeAttribute('id');
    tpl.querySelector('#tpl_erro_container_msg').textContent = mensagem;

    var container = document.getElementById('boletos_container');
    container.innerHTML = '';
    container.appendChild(tpl);
}

/**
 * Insere o card de processamento
 * @param {object} data Dados do card
 */
function _inserirCardProcessando(data) {
    // Remove o placeholder vazio na primeira inserção
    var empty = document.getElementById('boletos_empty');
    if (empty) empty.remove();

    var container = document.getElementById('boletos_container');

    var label     = _formatarLabel(data.seq, data.total, null, null);
    var cardHtml  =
        '<div class="boleto_card processando" id="boleto_card_' + data.id_lancamento + '">' +
        '    <div class="boleto_card_icon">' +
        '        <i class="fa fa-spin fa-circle-o-notch"></i>' +
        '    </div>' +
        '    <div class="boleto_card_info">' +
        '        <div class="boleto_card_title">' + label + '</div>' +
        '        <div class="boleto_card_sub">Recuperando PDF...</div>' +
        '    </div>' +
        '</div>';

    container.insertAdjacentHTML('beforeend', cardHtml);
}

/**
 * Atualiza o card de boleto finalizado
 * @param {object} data Dados do card
 */
function _atualizarCardFinalizado(data) {
    var card = document.getElementById('boleto_card_' + data.id_lancamento);
    if (!card) return;

    var label = _formatarLabel(data.seq, data.total, data.vencimento, data.valor);

    card.className = 'boleto_card';
    card.setAttribute('data-pdf', data.pdf_base64 || '');
    card.setAttribute('data-id',  data.id_lancamento);

    card.innerHTML =
        '<div class="boleto_card_icon">' +
        '    <i class="fa fa-barcode"></i>' +
        '</div>' +
        '<div class="boleto_card_info">' +
        '    <div class="boleto_card_title">' + label + '</div>' +
        '    <div class="boleto_card_sub">Venc.: ' + data.vencimento + ' - Clique para visualizar o boleto</div>' +
        '</div>' +
        '<div class="boleto_card_badge">' +
        '    <span class="label label-success"><i class="fa fa-check"></i></span>' +
        '</div>';

    card.onclick = function () {
        _selecionarCard(card);
        _abrirPdfNoViewer(card.getAttribute('data-pdf'), data.id_lancamento);
    };
}

/**
 * Atualiza o card de erro
 * @param {object} data Dados do card
 */
function _atualizarCardErro(data) {
    // se o card não existe, retorna
    var card = document.getElementById('boleto_card_' + data.id_lancamento);
    if (!card) return;

    var label = 'Boleto ' + data.seq + ' de ' + data.total;

    card.className = 'boleto_card erro';
    card.innerHTML =
        '<div class="boleto_card_icon" style="color:#d9534f;">' +
        '    <i class="fa fa-exclamation-circle"></i>' +
        '</div>' +
        '<div class="boleto_card_info">' +
        '    <div class="boleto_card_title">' + label + '</div>' +
        '    <div class="boleto_card_sub" style="color:#d9534f;" title="' + _escapeHtml(data.mensagem || '') + '">' +
        '        ' + _escapeHtml(data.mensagem || 'Falha ao recuperar PDF') +
        '    </div>' +
        '</div>' +
        '<div class="boleto_card_badge">' +
        '    <span class="label label-danger"><i class="fa fa-times"></i></span>' +
        '</div>';
}

// ---------------------------------------------------------------------------
// Viewer PDF
// ---------------------------------------------------------------------------

function _abrirPdfNoViewer(pdfBase64, idLancamento) {
    var viewer = document.getElementById('nf_viewer');
    // se o viewer não existe, retorna
    if (!viewer) return;

    var tpl_viewer_placeholder_erro = document.getElementById('tpl_viewer_placeholder_erro');
    //Se o PDF não está disponível, exibe o template de erro no viewer
    if (!pdfBase64) {
        viewer.innerHTML = tpl_viewer_placeholder_erro.innerHTML;
        return;
    }

    var src = 'data:application/pdf;base64,' + pdfBase64 + '#&navpanes=0';

    viewer.innerHTML = '<iframe id="pdf_viewer" src="' + src + '"></iframe>';
}

// ---------------------------------------------------------------------------
// Enviar email com boleto e NF
// ---------------------------------------------------------------------------

function enviarEmail() {
    var pessoa = document.getElementById('pessoa');
    if (!pessoa) {
        console.error('Pessoa não encontrada');
        return;
    }

    var id_nota_fiscal = document.getElementById('id');
    if (!id_nota_fiscal) {
        console.error('ID da Nota Fiscal não encontrada');
        return;
    }

    var numero_nota_fiscal = document.getElementById('numero_nota_fiscal');
    if (!numero_nota_fiscal) {
        console.error('Número da Nota Fiscal não encontrada');
        return;
    }

    var numero_pedido = document.getElementById('numero_pedido');
    if (!numero_pedido) {
        console.error('Número do Pedido não encontrada');
        return;
    }

    var url = window.location.pathname;

    Swal.fire({
        title: 'Processando...',
        text: 'Aguarde o envio do email com a Nota Fiscal e Boleto',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            id_nota_fiscal: id_nota_fiscal.value,
            numero_nota_fiscal: numero_nota_fiscal.value,
            numero_pedido: numero_pedido.value,
            pessoa: pessoa.value,
            mod: 'est',
            form: 'nota_fiscal_boleto_bancario',
            submenu: 'enviar_email',
            opcao: 'ajax',
        },
        success: function (response) {
            var parsed;
            try {
                parsed = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) {
                console.error('Resposta inválida do servidor:', response);
                Swal.fire({
                    width: 800,
                    icon: 'error',
                    title: 'Erro ao enviar email',
                    confirmButtonColor: '#d33',
                    html: '<span style="font-size:13px; color: #dc3545;">Resposta inválida do servidor. Verifique o console (F12).</span>'
                });
                return;
            }

            if (parsed.success) {
                Swal.fire({
                    width: 600,
                    icon: 'success',
                    title: parsed.message,
                });
            } else {
                Swal.fire({
                    width: 600,
                    icon: 'warning',
                    title: parsed.message || 'Resposta inesperada do servidor',
                    confirmButtonColor: '#d33',
                });
            }
        },
        error: function (xhr, status, error) {
            var parsed = null;
            if (xhr.responseText) {
                try {
                    parsed = JSON.parse(xhr.responseText);
                } catch (e) {
                    parsed = null;
                }
            }

            // Erro de validação de dados
            if (xhr.status === 422 && parsed) {
                var mensagem = parsed.message;
                var erros = parsed.errors || [];

                Swal.fire({
                    width: 800,
                    icon: 'error',
                    title: mensagem,
                    confirmButtonColor: '#d33',
                    html: `<span style="font-size:13px; color: #dc3545;"><span class="fa fa-exclamation-circle"></span><b> &nbsp; ${erros[0] || ''}</b></span>`
                });
                return;
            }

            // Erro não controlado
            Swal.fire({
                width: 800,
                icon: 'error',
                title: 'Erro ao enviar email',
                confirmButtonColor: '#d33',
                html: `<span style="font-size:13px; color: #dc3545;"><span class="fa fa-exclamation-circle"></span><b> &nbsp; ${parsed && parsed.message ? parsed.message : error}</b></span>`
            });
        }
    });

}

/**
 * Abre o PDF da Nota Fiscal no viewer via URL (card de NF na sidebar).
 * Seleciona o card de NF e desseleciona os de boleto.
 */
function _abrirNfNoViewer() {
    var card = document.getElementById('nf_card_danfe');
    if (!card) return;

    var url = card.getAttribute('data-url');
    if (!url) return;

    _selecionarCard(card);

    var viewer = document.getElementById('nf_viewer');
    if (!viewer) return;

    viewer.innerHTML = '<iframe id="pdf_viewer" src="' + url + '#&navpanes=0"></iframe>';
}

/**
 * Seleciona o card de boleto
 * @param {object} cardSelecionado Card selecionado
 */
function _selecionarCard(cardSelecionado) {
    var todos = document.querySelectorAll('.boleto_card');

    todos.forEach(function (c) { c.classList.remove('active'); });

    cardSelecionado.classList.add('active');
}

// ---------------------------------------------------------------------------
// Estado dos botões
// ---------------------------------------------------------------------------

/**
 * Exibe PDF único com todos os boletos do pedido no viewer (NUMLCTO + origem PED).
 */
function imprimirTodosBoletos() {
    var numeroPedido = document.getElementById('numero_pedido');
    if (!numeroPedido || !numeroPedido.value) {
        return;
    }

    var letraPedido = '|' + numeroPedido.value + '|PED';
    var url = window.location.pathname
        + '?mod=blt&form=boleto_imprime&opcao=blank&letra=' + letraPedido;

    var viewer = document.getElementById('nf_viewer');
    if (!viewer) {
        return;
    }

    document.querySelectorAll('.boleto_card.active').forEach(function (c) {
        c.classList.remove('active');
    });

    viewer.innerHTML = '<iframe id="pdf_viewer" src="' + url + '#&navpanes=0"></iframe>';
}

/**
 * Desabilita o botão de imprimir boleto
 */
function _desabilitarBotaoImprimir() {
    var btn = document.getElementById('btn_imprimir_boleto');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spin fa-circle-o-notch"></i> Buscando...';
    }
}

/**
 * Habilita o botão de imprimir boleto
 */
function _habilitarBotaoImprimir() {
    var btn = document.getElementById('btn_imprimir_boleto');
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-barcode"></i> Gerar Boleto(s)';
    }
}

/**
 * Habilita os botões de email
 */
function _habilitarBotoesEmail() {
    let btns_habilitados = ['btn_email_boleto_e_nf'];   // btn_email_nf

    btns_habilitados.forEach(function (id) {
        var btn = document.getElementById(id);
        if (btn) btn.disabled = false;
    });
}

/**
 * Desabilita os botões de email
 */
function _desabilitarBotoesEmail() {
    let btns_desativados = ['btn_email_boleto_e_nf'];   // btn_email_nf

    btns_desativados.forEach(function (id) {
        var btn = document.getElementById(id);
        if (btn) btn.disabled = true;
    });
}

// ---------------------------------------------------------------------------
// Utilitários
// ---------------------------------------------------------------------------

function _formatarLabel(seq, total, vencimento, valor) {
    var base = 'Boleto ' + seq + ' de ' + total;
    if (valor) {
        base += ' — R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
    }
    return base;
}

function _escapeHtml(str) {
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;')
        .replace(/'/g,  '&#39;');
}

// ---------------------------------------------------------------------------
// Restrição de boleto para NF de Devolução / Crédito / Débito (finalidade 4, 5, 6)
// ---------------------------------------------------------------------------

var _FINALIDADES_SEM_BOLETO = [4, 5, 6];

var _LABELS_FINALIDADE = {
    4: 'NF de Devolução',
    5: 'NF de Crédito',
    6: 'NF de Débito'
};

/**
 * Verifica a finalidade da NF e, se for 4, 5 ou 6, desabilita todos os
 * botões relacionados a boleto e exibe um banner informativo.
 */
function _aplicarRestricoesFinalidade() {
    var el = document.getElementById('finalidade_emissao');
    if (!el) return;

    var finalidade = parseInt(el.value, 10);
    if (_FINALIDADES_SEM_BOLETO.indexOf(finalidade) === -1) return;

    var label  = _LABELS_FINALIDADE[finalidade] || ('Finalidade ' + finalidade);
    var motivo = 'Não disponível para ' + label;

    // Botão Gerar Boleto(s)
    var btnGerar = document.getElementById('btn_imprimir_boleto');
    if (btnGerar) {
        btnGerar.disabled = true;
        btnGerar.title    = motivo;
    }

    // Botão Enviar NF e Boleto por e-mail
    var btnEmailBoleto = document.getElementById('btn_email_boleto_e_nf');
    if (btnEmailBoleto) {
        btnEmailBoleto.disabled = true;
        btnEmailBoleto.title    = motivo;
    }

    // Botão Imprimir todos os boletos
    var btnImprimir = document.getElementById('btn_imprimir_todos_boletos');
    if (btnImprimir) {
        btnImprimir.disabled = true;
        btnImprimir.title    = motivo;
    }

    // Banner informativo
    var banner = document.getElementById('banner_restricao_boleto');
    if (banner) {
        banner.innerHTML = '<i class="fa fa-exclamation-triangle" style="color:#ffa000;"></i> '
            + '<strong>' + label + '</strong> — emissão de boleto não permitida para esta finalidade.';
        banner.style.display = 'block';
    }

    // Área de boletos: substituir mensagem padrão
    var empty = document.getElementById('boletos_empty');
    if (empty) {
        empty.style.pointerEvents = 'none';
        empty.innerHTML = '<i class="fa fa-ban" style="color:#ffa000;font-size:28px;display:block;margin-bottom:8px;"></i>'
            + 'Boleto não aplicável a<br><strong>' + label + '</strong>';
    }
}

// ---------------------------------------------------------------------------
// Inicialização
// ---------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function () {
    // Abre automaticamente o PDF da NF se estiver disponível
    if (document.getElementById('nf_card_danfe')) {
        _abrirNfNoViewer();
    }

    // Aplica restrições visuais para NF de Devolução / Crédito / Débito
    _aplicarRestricoesFinalidade();

    // Verifica se o boleto será gerado automaticamente - Cadastro de CONTA
    var gera_boleto_automatico = document.getElementById('gera_boleto_automatico');

    if (gera_boleto_automatico && gera_boleto_automatico.value == 'S') {
        var finalidade = parseInt((document.getElementById('finalidade_emissao') || {}).value || '0', 10);

        // Só dispara a geração automática se a finalidade permitir boleto
        if (_FINALIDADES_SEM_BOLETO.indexOf(finalidade) === -1) {
            var numero_pedido = document.getElementById('numero_pedido');
            if (numero_pedido && numero_pedido.value) {
                iniciarEmissaoBoletos(numero_pedido.value);
            }
        }
    }
});