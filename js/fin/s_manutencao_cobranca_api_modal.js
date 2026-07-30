//----------------------------------------------------------------------------------------
// ----------- CONSTANTES DE STATUS API PARA CONFIGURACAO DE BADGES ----------------------
//----------------------------------------------------------------------------------------

const STATUS_CONFIG = {
    A_RECEBER:         { class: 's-a_receber',         label: 'A Receber' },
    RECEBIDO:          { class: 's-recebido',          label: 'Recebido' },
    ATRASADO:          { class: 's-atrasado',          label: 'Atrasado' },
    CANCELADO:         { class: 's-cancelado',         label: 'Cancelado' },
    EXPIRADO:          { class: 's-expirado',          label: 'Expirado' },
    MARCADO_RECEBIDO:  { class: 's-marcado_recebido',  label: 'Marcado Recebido' },
    FALHA_EMISSAO:     { class: 's-falha_emissao',     label: 'Falha na Emissão' },
    EM_PROCESSAMENTO:  { class: 's-em_processamento',  label: 'Em Processamento' },
    PROTESTO:          { class: 's-protesto',          label: 'Protesto' },
};

/**
 * Garante que resíduos de padding-right deixados pela combinação
 * SweetAlert2 + Bootstrap Modal sejam removidos quando não há mais
 * nenhuma modal Bootstrap aberta. Evita o efeito de "página aumentando
 * a cada abrir/fechar".
 */
function LimparResiduoPaddingBody() {
    // Se ainda há outra modal Bootstrap aberta, não mexe
    if ($('.modal.in:visible').length > 0) {
        return;
    }

    // Se ainda há um Swal aberto, deixa o Swal cuidar do estado
    if (typeof Swal !== 'undefined' && Swal.isVisible && Swal.isVisible()) {
        return;
    }

    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}

// Listener único para garantir limpeza do body após fechar a modal
$(document).off('hidden.bs.modal.cobrancaApi').on('hidden.bs.modal.cobrancaApi', '#modalCobrancaBancaria, #modalConfirmaCancelamento', function() {
    LimparResiduoPaddingBody();
});

/**
 * Aplica o status badge na tabela API
 * @param {string} status - Status
 * @returns {void}
 */
function aplicarStatusBadge(status) {
    const config = STATUS_CONFIG[status] ?? { class: '', label: status };
    const $el = $('#api_situacao_banco');
  
    $el
      .removeClass(Object.values(STATUS_CONFIG).map(s => s.class).join(' '))
      .addClass('badge-status ' + config.class)
      .text(config.label);
}

/**
 * Abre o modal de manutenção de cobrança API
 * @param {string} id - ID do lançamento
 * @param {string} banco - Banco
 * @returns {boolean}
 */
function OpenModalManutencaoCobrancaApi(id, banco) {

    if(!id) {
        Swal.fire('Erro', 'ID do lançamento ou banco não informado', 'error');
        return false;
    }

    if(!banco) {
        Swal.fire('Erro', 'Banco não informado', 'error');
        return false;
    }

    var url = window.location.href;

    // Mostra loading
    Swal.fire({
        title: 'Processando...',
        text: 'Buscando dados da cobrança bancária API',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {
            'mod': 'fin',
            'form': 'lancamento',
            'submenu': 'dadosManutencaoCobrancaApi',
            'opcao': 'ajax',
            'id': id,
            'banco': banco,
        },
        success: (response) => {
            debugger

            // Fecha o Swal e SÓ DEPOIS abre a modal Bootstrap, para evitar
            // que o Bootstrap 3 capture o padding-right ainda aplicado pelo
            // Swal como "originalBodyPad" e gere resíduo a cada ciclo.
            Swal.close();

            setTimeout(() => {
                if(response.success) {
                    OpenModalResponseSuccess(response);
                } else {
                    ResponseError(response);
                }
            }, 200);
        },
        error: (xhr) => {
            debugger
            Swal.close();

            setTimeout(() => {
                httpCode = xhr.status;

                if(httpCode === 400) {
                    ResponseError400(xhr.responseJSON);
                } else {
                    ResponseError(xhr.responseJSON);
                }
            }, 200);
        }
    });
}


/**
 * Cria a API do banco
 * @param {string} banco - Banco
 * @param {string} path - Path
 * @returns {Object}
 */
async function CriarObjetoBanco($banco, $path) {
    try {
        let module;

        switch ($banco) {
            case '77':
                module = await import('./s_api_inter.js');
                break;
            case '237':
                module = await import('./s_api_bradesco.js');
                break;
            default:
                throw new Error('Banco não implementado');
        }

        return new module.default($path);

    } catch (e) {
        console.warn('Banco não implementado', e);

        return {
            get() {
                console.warn('Banco não implementado');
                Swal.fire({
                    title: 'Erro',
                    text: 'Banco não implementado',
                    icon: 'error',
                });
                return;
            }
        };
    }
}

//----------------------------------------------------------------------------------------
// ----------------------- FUNCOES DE MANUTENCAO DE COBRANCA API -------------------------
//----------------------------------------------------------------------------------------

/**
 * Envia a cobrança API
 * @param {string} id_lancamento - ID do lançamento
 * @param {string} banco - Banco
 * @returns {boolean}
 */
async function EnviarCobrancaApi(id_lancamento = null, banco = null){

    debugger

    if(id_lancamento == null || id_lancamento == undefined){
        Swal.fire({
            title: 'Erro',
            text: 'ID não encontrado',
            icon: 'error',
        });
        return;
    }
        

    if(banco == null || banco == undefined){
        Swal.fire({
            title: 'Banco não encontrado',
            text: 'Selecione um banco para continuar',
            icon: 'info',
        });
        return;
    }

    let form = '';
    let submenu = '';

    switch(banco){
        case '237': // Bradesco
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.RegistraBoleto(id_lancamento);
            return;
        }
        case '77': // Inter
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.EmitirCobrancaInter(id_lancamento);
            return;
        }
        default:
            Swal.fire({
                title: 'Erro',
                text: 'Banco não encontrado',
                icon: 'error',
            });
            return;
    }

}


/**
 * Consulta a cobrança API
 * @param {string} id_tabela_api - ID da tabela API
 * @param {string} banco - Banco
 * @returns {boolean}
 */
async function ConsultarCobrancaApi(id_tabela_api = null, banco = null, id_lancamento = null){

    if(id_tabela_api == null || id_tabela_api == undefined){
        Swal.fire({
            title: 'Erro',
            text: 'ID da tabela API não encontrado',
            icon: 'error',
        });
        return;
    }
        

    if(banco == null || banco == undefined){
        Swal.fire({
            title: 'Conta Bancária não encontrada',
            text: 'Selecione uma conta bancária para continuar',
            icon: 'info',
        });
        return;
    }

    if(id_lancamento == null || id_lancamento == undefined){
        Swal.fire({
            title: 'Erro',
            text: 'ID do lançamento não encontrado',
            icon: 'error',
        });
        return;
    }

    let form = '';
    let submenu = '';

    switch(banco){
        case '237': // Bradesco
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.ConsultaDeTituloUnitario(id_tabela_api, id_lancamento);
            return;
        }
        case '77': // Inter
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.RecuperarCobrancaInter(id_tabela_api);
            return;
        }
        default:
            Swal.fire({
                title: 'Erro',
                text: 'Banco não encontrado',
                icon: 'error',
            });
            return;
    }

}


/**
 * Cancela a cobrança API
 * @param {string} id_tabela_api - ID da tabela API
 * @param {string} banco - Banco
 * @param {string} id_lancamento - ID do lançamento
 * @returns {boolean}
 */
async function CancelarCobrancaApi(id_tabela_api = null, banco = null, id_lancamento = null){

    if(id_tabela_api == null || id_tabela_api == undefined){
        Swal.fire({
            title: 'Erro',
            text: 'ID da tabela API não encontrado',
            icon: 'error',
        });
        return;
    }
        

    if(banco == null || banco == undefined){
        Swal.fire({
            title: 'Conta Bancária não encontrada',
            text: 'Selecione uma conta bancária para continuar',
            icon: 'info',
        });
        return;
    }

    let form = '';
    let submenu = '';

    switch(banco){
        case '237': // Bradesco
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.BaixaTitulo(id_tabela_api, id_lancamento);

            return;
        }
        case '77': // Inter
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.CancelarCobrancaInter(id_tabela_api);
            return;
        }
        default:
            Swal.fire({
                title: 'Erro',
                text: 'Banco não encontrado',
                icon: 'error',
            });
            return;
    }

}


/**
 * Paga a cobrança API
 * @param {string} id_lancamento - ID do lançamento
 * @param {string} banco - Banco
 * @returns {boolean}
 */
async function PagarCobrancaApi(id_tabela_api = null, banco = null){

    if(id_tabela_api == null || id_tabela_api == undefined){
        Swal.fire({
            title: 'Erro',
            text: 'ID da tabela API não encontrado',
            icon: 'error',
        });
        return;
    }

    if(banco == null || banco == undefined){
        Swal.fire({
            title: 'Conta Bancária não encontrada',
            text: 'Selecione uma conta bancária para continuar',
            icon: 'info',
        });
        return;
    }

    let form = '';
    let submenu = '';

    switch(banco){
        case '237': // Bradesco
        {
            Swal.fire({
                title: 'Atenção',
                text: 'Banco não permite pagamento via API',
                icon: 'info',
                confirmButtonColor: '#d33',
            });
            return;
        }
        case '77': // Inter     
        {
            let path = window.location.pathname;
            let api = await CriarObjetoBanco(banco, path);
            api.PagarCobrancaInter(id_tabela_api);
            return;
        }
        default:
            Swal.fire({
                title: 'Erro',
                text: 'Banco não encontrado',
                icon: 'error',
            });
            return;
    }

}
//----------------------------------------------------------------------------------------
// ---------------------------- FUNCOES DE RETORNO DO BACK -------------------------------
//----------------------------------------------------------------------------------------

/**
* Trata resposta de erro 400 da recuperação de cobrança no Inter
* @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
*/
function ResponseError400(response) {
    let title  = response.message;
    let errors = response.errors;
    let detail = response.data;

    Swal.fire({
        title: title || 'Erro não identificado',
        text: detail || 'Erro de validação dos dados',
        icon: 'error',
        width: '700px',
        confirmButtonColor: '#d33',
        html: errors.map(error => `<p>${error}</p>`).join('')
    });

    return false;
}

/**
* Trata resposta de erro da recuperação de cobrança no Inter
* @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
*/
function ResponseError(response) {
    debugger
    let detail = response.message;
    Swal.fire({
        title: 'Erro',
        text: detail || 'Erro ao recuperar cobrança no Banco',
        icon: 'error',
        width: '700px',
        confirmButtonColor: '#d33'
    });

    return false;
}

/**
* Trata resposta de sucesso da recuperação de cobrança no Inter
* @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
*/
function OpenModalResponseSuccess(response) {
    debugger

    let detail = response.data;

    // inputs ocultos
    let id_lancamento = detail.id_lancamento ?? '';
    let id_banco      = detail.id_banco ?? '';
    let id_tabela_api = detail.id_tabela_api ?? '';
    let codigo_situacao_banco = detail.api_codigo_situacao_banco ?? '';
    
    $('#id_lancamento').val(id_lancamento);
    $('#id_banco').val(id_banco);
    $('#id_tabela_api').val(id_tabela_api);

    let interno_situacao = detail.interno_situacao ?? '';

    //trata situação interna
    let interno_situacao_cor = '#2c3e50';
    if(interno_situacao == 'A') {
        interno_situacao = 'Aberto';
        interno_situacao_cor = '#27ae60'; // Verde
    } else if(interno_situacao == 'B') {
        interno_situacao = 'Baixa';
        interno_situacao_cor = '#4a90e2'; // Azul
    } else if(interno_situacao == 'C') {
        interno_situacao = 'Cancelado';
        interno_situacao_cor = '#2c3e50'; // Cinza
    }

    

    // Controla visulizacao de DADOS DE COBRANCA
    let cobranca_enviada = detail.cobranca_enviada ?? 'N';

    if(cobranca_enviada == 'N') {
        $('#painel_dados_cobranca').hide();
    } else {
        $('#painel_dados_cobranca').show();
    }

    // ------------------------------------------------------------------
    //  ------------------- Dados da Cobrança interno -------------------
    // ------------------------------------------------------------------
    let interno_nome_cliente          = detail.interno_nome_cliente ?? '';
    let interno_cnpj_cpf_cliente      = detail.interno_cnpj_cpf_cliente ?? '';
    let interno_nome_banco            = detail.interno_nome_banco ?? '';
    let interno_nome_conta_banco      = detail.interno_nome_conta_banco ?? '';
    let interno_conta_corrente        = detail.interno_conta_corrente ?? '';
    let interno_digito_conta_corrente = detail.interno_digito_conta_corrente ?? '';
    let interno_agencia               = detail.interno_agencia ?? '';
    let interno_valor_total           = detail.interno_valor_total ?? '';

    $('#interno_nome_cliente').val(interno_nome_cliente);
    $('#interno_cnpj_cpf_cliente').val(interno_cnpj_cpf_cliente);
    $('#interno_nome_banco').val(interno_nome_banco);
    $('#interno_nome_conta_banco').val(interno_nome_conta_banco);
    $('#interno_conta_corrente').val(interno_conta_corrente);
    $('#interno_agencia').val(interno_agencia);
    $('#interno_digito_conta_corrente').val(interno_digito_conta_corrente);
    $('#interno_situacao').text(interno_situacao).css('background-color', interno_situacao_cor);
    $('#interno_valor_total').val(interno_valor_total);

    // ------------------------------------------------------------------
    //  ------------------- Dados da Cobrança API -------------------
    // ------------------------------------------------------------------
    let api_situacao_banco = detail.api_situacao_banco ?? '';

    // Aplica o status badge na tabela API
    aplicarStatusBadge(api_situacao_banco);

    ControlaBotoesCobrancaApi(id_banco, api_situacao_banco, codigo_situacao_banco, detail);

    let api_pagador_nome       = detail.api_pagador_nome ?? '';
    let api_data_emissao       = detail.api_data_emissao ?? '';
    let api_data_vencimento    = detail.api_data_vencimento ?? '';

    let api_nosso_numero       = detail.api_nosso_numero ?? '';
    let api_linha_digitavel    = detail.api_linha_digitavel ?? '';
    let api_valor_total        = detail.api_valor_total ?? '';
    let api_pix_copia_e_cola   = detail.api_pix_copia_e_cola ?? '';

    $('#api_pagador_nome').text(api_pagador_nome);
    $('#api_data_emissao').text(api_data_emissao);
    $('#api_data_vencimento').text(api_data_vencimento);
    $('#api_valor_total').text(api_valor_total);
    $('#api_nosso_numero').text(api_nosso_numero);
    $('#api_linha_digitavel').text(api_linha_digitavel);
    $('#api_pix_copia_e_cola').text(api_pix_copia_e_cola || '-');

    // Garantia: zera resíduos no body antes do Bootstrap medir/armazenar
    // o "originalBodyPad". Evita o efeito da página "crescer" a cada ciclo.
    if (!$('body').hasClass('modal-open')) {
        $('body').css('padding-right', '');
    }

    $('#modalCobrancaBancaria').modal('show');

    return true;
}

/**
 * Controla os botões de acordo com a situação do título
 * @param {string} id_banco - ID do banco
 * @param {string} api_situacao_banco - Situação do título
 * @param {string} codigo_situacao_banco - Código da situação do título
 * @returns {void}
 */
function ControlaBotoesCobrancaApi(id_banco,api_situacao_banco, codigo_situacao_banco, retorno = null) {
    debugger
    switch(id_banco){
        case 237: // Bradesco
        {   

            let situacoes_nao_enviadas = [
                '', '08', '10', '11', '19', '20', '21', 
                '51', '52', '53', '54', '55', '57', 
                '59', '60', '63', '64', '68', '71', '98'
            ];


            if(situacoes_nao_enviadas.includes(codigo_situacao_banco)) {
                // Botoes para titulos nao enviados
                $('#btnCancelarCobranca').prop('disabled', true);
                $('#btnConsultarBanco').prop('disabled', true);
                $('#btnEnviarCobranca').prop('disabled', false);
                $('#btnPagarCobranca').prop('disabled', true)
                                      .attr('title', 'O banco não permite esta ação para serviços de API.');
            } else {
                // Botoes para titulos enviados
                $('#btnCancelarCobranca').prop('disabled', false);
                $('#btnConsultarBanco').prop('disabled', false);
                $('#btnEnviarCobranca').prop('true', false);
                $('#btnPagarCobranca').prop('disabled', true)
                                      .attr('title', 'O banco não permite esta ação para serviços de API.');
            }
            return;
        }
        case 77: // Inter
        {   
            if(api_situacao_banco == 'CANCELADO') {
                $('#btnCancelarCobranca').prop('disabled', true);
                $('#btnConsultarBanco').prop('disabled', true);
                $('#btnEnviarCobranca').prop('disabled', false);
                $('#btnPagarCobranca').prop('disabled', true);
            }
        
            if(api_situacao_banco == 'RECEBIDO') {
                $('#btnPagarCobranca').prop('disabled', true);
                $('#btnConsultarBanco').prop('disabled', false);
                $('#btnEnviarCobranca').prop('disabled', false);
                $('#btnPagarCobranca').prop('disabled', false);
                $('#btnCancelarCobranca').prop('disabled', true);
            }
        
            if(api_situacao_banco == 'EMITIDO') {
                $('#btnPagarCobranca').prop('disabled', true);
                $('#btnConsultarBanco').prop('disabled', false);
                $('#btnEnviarCobranca').prop('disabled', false);
                $('#btnPagarCobranca').prop('disabled', true);
                $('#btnCancelarCobranca').prop('disabled', false);
            }

            if(api_situacao_banco == 'A_RECEBER') {
                $('#btnPagarCobranca').prop('disabled', false);
                $('#btnConsultarBanco').prop('disabled', false);
                $('#btnEnviarCobranca').prop('disabled', true);
                $('#btnPagarCobranca').prop('disabled', false);
                $('#btnCancelarCobranca').prop('disabled', false);
            }

            if(api_situacao_banco == 'EM_PROCESSAMENTO') {
                $('#btnPagarCobranca').prop('disabled', true);
                $('#btnConsultarBanco').prop('disabled', false);
                $('#btnEnviarCobranca').prop('disabled', true);
                $('#btnPagarCobranca').prop('disabled', true);
                $('#btnCancelarCobranca').prop('disabled', false);
            }

            // SE nao existe situacao, habilita o envio de boleto e a consulta de cobrança
            if(!api_situacao_banco) {

                // SE a cobrança já foi enviada, desabilita o envio de boleto e habilita a consulta de cobrança
                if(retorno.cobranca_enviada == 'S' ) {
                    $('#btnEnviarCobranca').prop('disabled', true);
                    $('#btnCancelarCobranca').prop('disabled', false);
                    $('#btnConsultarBanco').prop('disabled', false);
                    $('#btnPagarCobranca').prop('disabled', true);

                    Swal.fire({
                        title: 'Atenção',
                        text: 'A cobrança já foi enviada para o banco, realize a consulta para atualiza os dados da cobrança',
                        icon: 'info',
                        confirmButtonColor: '#d33',
                    });

                } else {
                    $('#btnEnviarCobranca').prop('disabled', false);
                    $('#btnCancelarCobranca').prop('disabled', true);
                    $('#btnConsultarBanco').prop('disabled', true);
                    $('#btnPagarCobranca').prop('disabled', true);
                }
            }

            return;
        }
        default:
            // Botoes padrão
            $('#btnCancelarCobranca').prop('disabled', true);
            $('#btnConsultarBanco').prop('disabled', true);
            $('#btnEnviarCobranca').prop('disabled', true);
            $('#btnPagarCobranca').prop('disabled', true);

            Swal.fire({
                title: 'Erro',
                text: 'Situação do título não encontrada',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
            return;
    }
}

function copiarPix() {
    var texto = $('#api_pix_copia_e_cola').text();
    if (!texto || texto === '-') return;
    navigator.clipboard.writeText(texto);
}