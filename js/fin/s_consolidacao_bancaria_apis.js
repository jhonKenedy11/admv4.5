/**
 * @name s_consolidacao_bancaria_apis.js
 * @description Script para a página de consolidação bancária APIs
 * @author: Jhon Kenedy
 * @version: 1.0.0
 * @date: 2026-05-19
 * Informações importantes:
 * SelecionaBancoApi: Função que controla a exibição dos botões de ações de cada banco.
 * ProcessaTitulosSelecionados: Captura os dados que foram setados nos data-attrs do checkbox de cada script bancario.
 * 
 */

/**
 * Colunas das tabelas de titulos
 * @type {Array}
 */

// Colunas das tabelas de titulos
const COLUNAS_TITULOS = [
    'nome_pagador',
    'seu_numero',
    'data_vencimento',
    'data_pagamento',
    'data_movimento',
    'valor_titulo',
    'valor_pagamento',
    'descricao_origem_pagamento'
];


/**
 * Seleciona o banco API e controla a exibição dos botões de ações de cada banco.
 * Também filtra o select de contas bancárias para exibir apenas as contas
 * que pertencem ao banco selecionado, usando o atributo data-banco de cada option.
 *
 * @param {string} banco - Código do banco selecionado (ex.: '77', '237')
 * @returns {void}
 */
function SelecionaBancoApi(banco) {

    // Define o alvo de exibição dos botões de ações de cada banco.
    const alvo = banco === '77' ? '.btn_acoes_inter'
        : banco === '237' ? '.btn_acoes_bradesco'
            : '.bank_card_area';

    // Oculta todos os botões de ações de cada banco.
    $('.btn_acoes_inter, .btn_acoes_bradesco, .bank_card_area')
        .not(alvo)
        .addClass('hidden');

    // Quando nenhum banco está selecionado, mantém apenas a área de aviso oculta.
    if (banco) {
        $(alvo).removeClass('hidden').hide().fadeIn(500);
    } else {
        $('.btn_acoes_inter, .btn_acoes_bradesco, .bank_card_area').addClass('hidden');
    }

    FiltrarContasPorBanco(banco);
}

/**
 * Filtra o select de contas bancárias para exibir apenas as contas do
 * banco informado. As opções são comparadas pelo atributo `data-banco`
 * de cada <option> com o valor do banco selecionado.
 *
 * - Sem banco selecionado: exibe o placeholder "Selecione o banco" e oculta
 *   todas as contas.
 * - Com banco selecionado: oculta o placeholder, mostra apenas as contas
 *   daquele banco e já seleciona a primeira automaticamente.
 *
 * @param {string} banco - Código do banco selecionado
 * @returns {void}
 */
function FiltrarContasPorBanco(banco) {

    const $contaSelect = $('#filtro_conta_api');
    const $placeholder = $('#filtro_conta_api_placeholder');
    const temBanco = banco !== '' && banco != null;

    // Placeholder só aparece quando não há banco selecionado.
    $placeholder.prop('hidden', temBanco).prop('disabled', temBanco);

    let primeiraContaValor = '';

    $contaSelect.find('option').each(function () {
        const $opt = $(this);

        if ($opt.is($placeholder)) return;

        const exibir = temBanco && $opt.attr('data-banco') === banco;

        $opt.prop('hidden', !exibir).prop('disabled', !exibir);

        if (exibir && primeiraContaValor === '') {
            primeiraContaValor = $opt.val();
        }
    });

    // Sem banco: volta para o placeholder.
    // Com banco: seleciona direto a primeira conta correspondente (sem placeholder).
    $contaSelect.val(temBanco ? primeiraContaValor : '');
}

/**
 * Inicializa o estado dos filtros ao carregar a página.
 * Garante que o select de contas bancárias inicie exibindo "Selecione o banco"
 * e com todas as contas ocultas até que o usuário escolha um banco.
 */
function InicializarFiltroContasApi() {
    FiltrarContasPorBanco($('#filtro_banco_api').val() || '');
}

/**
 * Cria o objeto da API de cada banco
 * @param {string} banco - Banco
 * @param {string} path - Path
 * @returns {Object}
 */
async function CriarObjetoBanco($banco, $path) {

    try {
        let module;

        switch ($banco) {
            case '77':
                // Importa o script da API do banco Inter
                module = await import('./s_api_inter.js');
                break;
            case '237':
                // Importa o script da API do banco Bradesco
                module = await import('./s_api_bradesco.js');
                break;
            default:
                // Lança um erro se o banco não for implementado
                throw new Error('Banco não implementado');
        }

        // Cria o objeto da API do banco com definição de colunas das tabelas de titulos e path da API
        return new module.default($path, COLUNAS_TITULOS);

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


/**
 * Liga o checkbox do <thead> (#marcar_todos_thead) ao marcador em massa
 * das linhas da tabela de títulos.
 *
 * Os checkboxes das linhas são criados nas classes de cada banco com a
 * classe CSS `.checkbox_titulo` (ver `CriarCelulaCheckbox` em
 * `s_api_inter.js`), por isso o seletor é fixado nessa classe.
 *
 * Chamado uma única vez no carregamento da página.
 */
function InicializarBtnMarcarTodos() {
    $(document).on('change', '#marcar_todos_thead', function () {
        var marcar = this.checked === true;
        $('#titulos_tbody').find('input.checkbox_titulo').prop('checked', marcar);
    });
}

$(function () {
    InicializarBtnMarcarTodos();
    InicializarFiltroContasApi();
});

/**
 * Troca a página da consulta de cobranças.
 * Envia apenas a direção ao backend; o backend recupera da sessão o JSON
 * da última consulta para calcular qual página retornar.
 *
 * @param {string} direcao - Direção da paginação ('previous' | 'next')
 * @returns {boolean}
 */
async function alterarPagina(direcao = null) {

    debugger
    let path = window.location.pathname;
    let banco = $('#filtro_banco_api').val();
    let conta = $('#filtro_conta_api').val();

    if (!banco) return Swal.fire({ title: 'Erro', text: 'Banco não informado', icon: 'error' });
    if (!conta) return Swal.fire({ title: 'Erro', text: 'Conta bancária não informada', icon: 'error' });
    if (!direcao) return Swal.fire({ title: 'Erro', text: 'Direção não informada', icon: 'error' });

    let api = await CriarObjetoBanco(banco, path);

    switch (banco) {
        case '77': // Inter
            api.alterarPaginaInter(direcao, conta);
            break;
        case '237': // Bradesco
            api.alterarPaginaBradesco(direcao, conta);
            break;
        default:
            Swal.fire({ title: 'Erro', text: 'Banco não implementado', icon: 'error' });
            return;
    }

    return true;
}

/**
 * Consulta a coleção de cobrança inter
 * @param {string} status - Status da coleção de cobrança
 */
async function ConsultarColecaoCobrancaInter(situacao) {

    try {
        let path = window.location.pathname;
        let banco = '77'; // Codigo do banco Inter

        // Cria o objeto da API do banco
        let api = await CriarObjetoBanco(banco, path);

        // Consulta a coleção de cobrança
        api.RecuperarColecaoCobranca(situacao);

    } catch (error) {
        console.error('Erro ao consultar a coleção de cobrança inter', error);
        Swal.fire({
            title: 'Erro',
            text: 'Erro ao consultar a coleção de cobrança inter',
            icon: 'error',
        });
    }
}

/**
 * Consulta os títulos Bradesco
 * @param {string} tipo_consulta - Tipo de consulta
 * @returns {void}
 */
async function ConsultarTitulosBradesco(tipo_consulta) {

    try {
        let path = window.location.pathname;
        let banco = '237'; // Codigo do banco Bradesco

        // Cria o objeto da API do banco
        let api = await CriarObjetoBanco(banco, path);

        // Consulta os títulos
        api.ConsultaTitulosBradesco(tipo_consulta);

    } catch (error) {
        console.error('Erro ao consultar os títulos Bradesco', error);
        Swal.fire({
            title: 'Erro',
            text: 'Erro ao consultar os títulos Bradesco',
            icon: 'error',
        });
    }
}


/**
 * Consulta a coleção de cobrança no Inter
 * @param {string} situacao - Situação da consulta
 */
function ProcessaTitulosSelecionados() {
    debugger

    let titulos_selecionados = $('#titulos_tbody').find('input.checkbox_titulo:checked');
    if (titulos_selecionados.length === 0) {
        Swal.fire({
            title: 'Atenção',
            text: 'Selecione pelo menos um título.',
            icon: 'warning',
        });
        return;
    }

    // Coleta os campos pedidos diretamente dos data-attrs do checkbox
    // (setados em ApiInter.CriarCelulaCheckbox / s_api_inter.js).
    let titulos_selecionados_dados = titulos_selecionados.map(function () {
        const $cb = $(this);
        return {
            seu_numero: $cb.attr('data-seu_numero') || '',
            data_movimento: $cb.attr('data-data_movimento') || '',
            data_pagamento: $cb.attr('data-data_pagamento') || '',
            nome_pagador: $cb.attr('data-nome_pagador') || '',
            descricao_pagamento: $cb.attr('data-descricao_pagamento') || '',
            situacao: $cb.attr('data-situacao') || '',
            // Situacao utilizado no mapeamento do Bradesco
            codigo_status: $cb.attr('data-codigo_status') || '',
        };
    }).get();

    // Mantém também a lista simples de "seus números" para casos em que
    // o backend espera apenas o identificador.
    let titulos_selecionados_ids = titulos_selecionados_dados
        .map(t => t.seu_numero)
        .filter(Boolean);

    let ids_titulos = titulos_selecionados_ids;

    // Monta as linhas da tabela de pré-visualização
    const linhas_tabela = titulos_selecionados_dados.map((t, i) => `
        <tr>
            <td style="text-align:center;">${i + 1}</td>
            <td>${t.nome_pagador}</td>
            <td style="text-align:center;">${t.data_movimento}</td>
            <td style="text-align:center;">${t.data_pagamento}</td>
            <td style="text-align:center;">${t.descricao_pagamento}</td>
        </tr>
    `).join('');

    let id_banco = $('#filtro_banco_api').val() ?? '';
    let id_conta = $('#filtro_conta_api').val() ?? '';
    let id_centro_custo = $('#filtro_centro_custo_api').val() ?? '';
    let nome_banco = $('#filtro_banco_api option:selected').text() ?? '';
    let nome_conta = $('#filtro_conta_api option:selected').text() ?? '';
    let nome_centro_custo = $('#filtro_centro_custo_api option:selected').text() ?? '';

    Swal.fire({
        title: 'Processando títulos selecionados',
        width: '115em',
        html: `
            <div style="display: flex; flex-direction: column; gap: 14px; text-align: left;">

                <div style="
                    display:flex;
                    gap:12px;
                    flex-wrap:wrap;
                    font-size:11px;
                    color:#495057;
                    background:#f8f9fa;
                    padding:10px 14px;
                    border-radius:8px;
                    justify-content: center;
                ">
                    <span style="font-weight: 600;">
                        <i class="fa fa-university" style="margin-right:6px; color:#6c757d;"></i>
                        ${nome_banco}
                    </span>

                    <span style="font-weight: 600;">
                        <i class="fa fa-credit-card" style="margin-right:6px; color:#6c757d;"></i>
                        ${nome_conta}
                    </span>

                    <span style="font-weight: 600;">
                        <i class="fa fa-sitemap" style="margin-right:6px; color:#6c757d;"></i>
                        ${nome_centro_custo}
                    </span>
                </div>

                <div>
                    <div style="max-height: 240px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; margin-top: 4px;">
                        <table class="table table-striped table-bordered table-condensed" style="margin: 0; font-size: 12px;">
                            <thead style="background-color: #f5f5f5; position: sticky; top: 0;">
                                <tr>
                                    <th style="text-align:center; width: 36px;">#</th>
                                    <th>Nome pagador</th>
                                    <th style="text-align:center; width: 110px;">Data movimento</th>
                                    <th style="text-align:center; width: 110px;">Data pagamento</th>
                                    <th style="text-align:center;">Descrição pagamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${linhas_tabela}
                            </tbody>
                        </table>
                    </div>
                </div>
            
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Processar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#329ebe',
        preConfirm: () => {
            debugger

            return {
                ids_titulos: ids_titulos,
                titulos: titulos_selecionados_dados,
                banco: id_banco,
                conta: id_conta,
                centro_custo: id_centro_custo,
            };

        }
    }).then((result) => {

        if (!result.isConfirmed) return;

        const { titulos, banco, conta, centro_custo } = result.value;

        let dados = {
            'titulos': titulos,
            'banco': banco,
            'conta': conta,
            'centro_custo': centro_custo,
        }

        Swal.fire({ title: 'Processando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            type: 'POST',
            url: window.location.pathname,
            dataType: 'json',
            data: {
                'mod': 'fin',
                'form': 'consolidacao_bancaria_apis',
                'submenu': 'processaTitulosSelecionados',
                'dados': JSON.stringify(dados),
                'opcao': 'ajax'
            },
            success: (response) => {
                // Fecha o modal de progresso
                Swal.close();
                // Função para tratar o sucesso
                _reponseSucess(response);
            },
            error: (xhr) => {
                // Fecha o modal de progresso
                Swal.close();
                // Função para tratar o erro
                _reponseError(xhr);

            }
        });
    });
}


/**
 * Processa o lançamento
 * @param {Object} response - Resposta
 * @returns {void}
 */
function _reponseSucess(response) {
    debugger

    // Se a resposta for sucesso, exibe o modal de sucesso
    if (response.success === true) {
        Swal.fire({
            title: 'Sucesso',
            text: 'Os títulos foram processados com sucesso',
            icon: 'success',
        });
    } else {

        // Se a resposta for erro, exibe o modal de erro
        const title = response.message;

        // Monta a tabela de resultados
        const linhas_tabela = response.data.map((t, i) => `
            <tr>
                <td style="text-align:center;">${i + 1}</td>
                <td>${t.nome_pagador}</td>
                <td style="text-align:center;">${t.seu_numero}</td>
                <td style="text-align:center;">${t.descricao_pagamento}</td>
                <td style="text-align:center;"><b>${t.resultado_processamento}</b></td>
            </tr>
        `).join('');

        // Monta o HTML da tabela de resultados
        const html = `
            <div style="text-align: left; max-height: 300px; overflow-y: auto; margin-top: 10px;">
                <table class="table table-striped table-bordered table-condensed" style="margin: 0; font-size: 12px;">
                    <thead style="background-color: #f5f5f5; position: sticky; top: 0;">
                        <tr>
                            <th style="text-align:center; width: 36px;">#</th>
                            <th>Nome pagador</th>
                            <th style="text-align:center; width: 110px;">Seu número</th>
                            <th style="text-align:center; width: 180px;">Descrição pagamento</th>
                            <th style="text-align:center;">Resultado processamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${linhas_tabela}
                    </tbody>
                </table>
            </div>
        `;

        // Exibe o modal de resultados
        Swal.fire({
            title: title + "!",
            icon: '',
            width: '115em',
            confirmButtonColor: '#d33',
            html: html
        });
    }
}

/**
 * Trata o erro de resposta
 * @param {Object} response - Resposta
 * @returns {void}
 */
function _reponseError(response) {
    debugger

    // Exibe o modal de erro
    Swal.fire({
        title: 'Erro',
        text: 'Erro ao processar os títulos selecionados',
        icon: 'error',
    });
}

