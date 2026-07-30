export default class ApiInter {

    constructor($path, $colunas_titulos = null) {
        this.baseUrl = $path;

        // Mapeamento fixo: ordem das <th> do template → chave no JSON
        this.COLUNAS_TITULOS = $colunas_titulos;
    }

    getUrl() {
        return this.baseUrl;
    }


    /**
     * Altera a página da consulta de coleção de cobranças no Banco Inter.
     * O backend recupera o JSON da última consulta gravada em sessão,
     * calcula a nova página com base na direção informada, refaz a chamada
     * à API e devolve o mesmo formato de retorno de RecuperarColecaoCobranca.
     * O header da conta corrente é resolvido em banco a partir de
     * `conta_bancaria` (mesmo padrão do cancelamento).
     *
     * @param {string} direcao        - Direção da paginação ('previous' | 'next')
     * @param {string} conta_bancaria - Identificador da conta bancária (FIN_CONTA.CONTA)
     */
    alterarPaginaInter(direcao = null, conta_bancaria = null) {
        debugger
        let response_json = null;
        let http_code     = 0;
        let url           = this.getUrl();

        if (!url) {
            Swal.fire({
                title: 'Erro',
                text: 'URL não encontrada',
                icon: 'error',
            });
            return;
        }

        if (!direcao) {
            Swal.fire({
                title: 'Erro',
                text: 'Direção não informada',
                icon: 'error',
            });
            return;
        }

        if (!conta_bancaria) {
            Swal.fire({
                title: 'Erro',
                text: 'Conta bancária não informada',
                icon: 'error',
            });
            return;
        }

        Swal.fire({
            title: 'Consultando...',
            text: 'Aguarde enquanto a página é atualizada',
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
                'form': 'api_inter',
                'submenu': 'alterarPagina',
                'dados': {
                    'direcao': direcao,
                    'conta_bancaria': conta_bancaria
                },
                'opcao': 'ajax'
            },
            success: (response) => {
                Swal.close();

                if (response.success === true) {
                    this.ResponseColecaoCobrancaSuccess(response);
                } else {
                    this.ResponseError(response);
                }

                // Oculta o box de informações de como aplicar os filtros
                if ($('#como_aplicar_filtros').length > 0) {
                    $('#como_aplicar_filtros').hide();
                }
            },
            error: (xhr) => {
                Swal.close();

                http_code     = xhr.status;
                response_json = xhr.responseJSON;

                if (http_code === 400) {
                    this.ResponseError400(response_json);
                } else {
                    this.ResponseError(response_json);
                }
            }
        });
    }

    /**
     * s_api_inter.js
     * Módulo JavaScript para integração com a API do Banco Inter
     */

    /**
     * Emite cobrança no Banco Inter
     * @param {number} id_lancamento - ID do lançamento
     */
    EmitirCobrancaInter(id_lancamento) {
        debugger
        let http_code = 0;
        let url = this.getUrl();

        if(!url) {
            Swal.fire({
                title: 'Erro',
                text: 'URL não encontrada',
                icon: 'error',
                width: '700px',
                confirmButtonColor: '#d33'
            });
        }
        
        Swal.fire({
            title: 'Atenção!',
            text: 'Tem certeza que deseja emitir a cobrança no Banco Inter?',
            icon: 'warning',
            width: '400px',
            showCancelButton: true,
            confirmButtonText: 'Sim, emitir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {

            if (!result.isConfirmed) return;
        
            Swal.fire({
                title: 'Processando...',
                text: 'Aguarde o envio dos dados para o Banco Inter',
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
                    'form': 'api_inter',
                    'submenu': 'emitirCobranca',
                    'id_lancamento': id_lancamento,
                    'opcao': 'ajax'
                },
                success: (response) => {

                    debugger
                    
                    Swal.close();

                    if (response.success) {

                        if (typeof response == 'undefined' && response?.data) {
                            Swal.fire('Erro', response.message || 'Id do boleto não encontrado', 'error');
                        }

                        let id_tabela_api = response.data;

                        this.RecuperarCobrancaInter(id_tabela_api);

                    } else {
                        Swal.fire('Erro', response.message || 'Falha ao emitir cobrança', 'error');
                    }

                },
                error: (xhr, jqXHR, response) => {
                    debugger
                    Swal.close();
                    
                    response = xhr.responseJSON;
                    http_code = xhr.status;
                    
                    // Se o erro for interno do servidor, exibe um erro e retorna false
                    if(http_code === 500) {

                        console.log('response', xhr.responseJSON);
                        console.log('http_code', http_code);
                        console.log('response', response);
                        console.log('xhr', xhr);
                        console.log('jqXHR', jqXHR);

                        Swal.fire({
                            title: 'Erro',
                            text: 'Erro interno do servidor, entre em contato com o suporte',
                            icon: 'error',
                            width: '700px',
                            confirmButtonColor: '#d33'
                        });
                        return false;
                    }

                    if(http_code === 400) {
                        this.EmitirCobrancaInterError400(response);
                    } else {
                        this.EmitirCobrancaInterError(response);
                    }
                }
            });
        });
    }

    /**
     * Trata resposta de erro 400 da emissão de cobrança no Inter
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    EmitirCobrancaInterError400(response) {
        const title  = response.message;
        const errors = response.errors;
        const detail = response.data;

        const hasErrors = Array.isArray(errors) && errors.length > 0;

        Swal.fire({
            title: title || 'Erro não identificado',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33',
            ...(hasErrors
                ? { html: errors.map(error => `<p>${error}</p>`).join('') }
                : { text: detail || 'Erro de validação dos dados' }
            )
        });

        return false;
    }

    /**
    * Trata resposta de erro da emissão de cobrança no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    EmitirCobrancaInterError(response) {
        debugger
        let title = response.message;
        let detail = response.data;
        let errors = response.errors || [];

        // Se houver erros internos, exibe os erros
        if(errors.length > 0) {
            console.log('errors', errors);
        }

        Swal.fire({
            title: title || 'Erro',
            text: detail || 'Erro ao emitir cobrança no Banco Inter',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33'
        });

        return false;
    }

    /**
    * Recupera o boleto no Banco Inter
    * @param {number} id_tabela_api - ID da tabela da api do banco
    */
    RecuperarCobrancaInter(id_tabela_api) {
        debugger

        const url    = this.getUrl();
        const id     = id_tabela_api;
        let http_code = 0;

        if(!url) {
            Swal.fire({
                title: 'Erro',
                text: 'URL não encontrada',
                icon: 'error',
                width: '700px',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // Se não foi encontrado o identificador do boleto, exibe um erro
        if(!id) {
            Swal.fire({
                title: 'Erro',
                text: 'Identificador do boleto não encontrado',
                icon: 'error',
                width: '700px',
                confirmButtonColor: '#d33'
            });
            return;
        }

        // Se foi encontrado o identificador do boleto, continua com o processo de baixa
        Swal.fire({
            title: 'Processando...',
            text: 'Aguarde enquanto o boleto é localizado e baixado',
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
                'form': 'api_inter',
                'submenu': 'recuperarCobranca',
                'opcao': 'ajax',
                'id': id,

            },
            success: (response) => {
                Swal.close();

                if (response.success) {
                    this.RecuperarCobrancaInterSuccess(response);
                } else {
                    Swal.fire('Erro', response.message || 'Falha ao emitir cobrança', 'error');
                } 
                
            },
            error: (xhr) => {
                Swal.close();

                debugger

                http_code = xhr.status;

                if(http_code === 400) {
                    this.RecuperarCobrancaInterError400(xhr.responseJSON);
                } else {
                    this.RecuperarCobrancaInterError(xhr.responseJSON);
                }
            }
        });
    }

    /**
    * Trata resposta de erro da recuperação de cobrança no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    RecuperarCobrancaInterError(response) {
        debugger
        let title = response.message;
        let detail = response.data;
        let errors = response.errors || [];

        // Se houver erros internos, exibe os erros
        if(errors.length > 0) {
            console.log('errors', errors);
        }

        Swal.fire({
            title: title || 'Erro',
            text: detail || 'Erro ao recuperar cobrança no Banco Inter',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33'
        });

        return false;
    }

    /**
    * Trata resposta de erro 400 da recuperação de cobrança no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    RecuperarCobrancaInterError400(response) {
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
    * Trata resposta de sucesso da recuperação de cobrança no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    RecuperarCobrancaInterSuccess(response) {
        debugger
        let id = response.meta.id || null;
        let tabela_api_inter = response.meta.update_api_inter || false;
        let tabela_lancamento = response.meta.update_lancamento || false;

        // Se nao foi possível atualizar a tabela lancamento
        if(tabela_lancamento !== true) {

            Swal.fire({
                title: 'Sucesso',
                text: 'Cobrança registrada porém não foi possível atualizar as tabelas, informe ao suporte',
            });

            return false;
        }

        // Se nao foi possível obter o identificador da cobrança
        if(!id) {
            console.log('Não foi possível obter o identificador da cobrança');
            console.log('id', id);

            Swal.fire({
                title: 'Sucesso',
                text: 'Cobrança registrada e disponível para download',
                icon: 'success',
                width: '700px'
            });
        }

        // Se nao foi possível atualizar a tabela api_inter
        if(tabela_api_inter !== true) {

            Swal.fire({
                title: 'Sucesso',
                text: 'Consulta realizada com sucesso, porém não houve alterações no boleto',
                icon: 'success',
                width: '700px',
                confirmButtonColor: '#3085d6',
            });

            return true;
        }


        // Monta a URL para impressão do boleto
        // let path = this.getUrl();
        // let url  = path + '?mod=blt&submenu=imprime_api_inter&form=boleto_imprime&opcao=blank&letra=' + id;

        // // Se foi possível obter o identificador da cobrança, exibe o botao para impressao boleto
        // Swal.fire({
        //     title: 'Sucesso',
        //     text: 'Cobrança registrada e disponível para download',
        //     icon: 'success',
        //     width: '700px',
        //     confirmButtonColor: '#3085d6',
        //     html: `<a href="${url}" target="_blank" class="btn btn-success" style="width: 70%; margin-top: 10px;"> 
        //             <span class="glyphicon glyphicon-barcode" aria-hidden="true" title="Boleto"></span> 
        //                 <b>Imprimir Boleto</b>
        //            </a>`
        // });

        Swal.fire({
            title: 'Sucesso',
            text: 'Cobrança atualizada com sucesso',
            icon: 'success',
            width: '700px',
        });

        return true;
    }

    /**
     * Cancela a cobrança no Inter
     * @param {string} id_lancamento - ID do lançamento
     * @returns {boolean}
     */
    CancelarCobrancaInter(id_lancamento) {

        let url = this.getUrl();
    
        if (!url) {
            Swal.fire({
                title: 'Erro',
                text: 'URL não encontrada',
                icon: 'error',
                width: '700px',
                confirmButtonColor: '#d33'
            });
    
            return;
        }
    
        // Fecha a modal bootstrap
        $('#modalCobrancaBancaria').modal('hide');
    
        // Aguarda animação terminar
        setTimeout(() => {
    
            Swal.fire({
                title: 'Cancelar Cobrança',
                width: '500px',
    
                html: `
                    <p style="font-size: 14px; color: #555; margin-bottom: 12px;">
                        Informe o motivo do cancelamento:
                    </p>
    
                    <input
                        type="text"
                        id="motivo_cancelamento"
                        class="swal2-input"
                        maxlength="50"
                        placeholder="Motivo do cancelamento (máx. 50 caracteres)"
                        style="width: 90%;">
                `,
    
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar Cancelamento',
                cancelButtonText: 'Voltar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
    
                preConfirm: () => {
    
                    const motivo = document
                        .getElementById('motivo_cancelamento')
                        .value
                        .trim();
    
                    if (!motivo) {
                        Swal.showValidationMessage(
                            'Informe o motivo do cancelamento.'
                        );
    
                        return false;
                    }
    
                    return motivo;
                }
    
            }).then((result) => {
    
                // Se clicou em voltar/cancelar
                if (result.dismiss) {
    
                    // Reabre a modal
                    $('#modalCobrancaBancaria').modal('show');
    
                    return;
                }
    
                const motivo = result.value;
    
                Swal.fire({
                    title: 'Processando...',
                    text: 'Aguarde enquanto a cobrança é cancelada',
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
                        mod: 'fin',
                        form: 'api_inter',
                        submenu: 'cancelarCobranca',
                        opcao: 'ajax',
                        id_lancamento: id_lancamento,
                        motivo_cancelamento: motivo,
                    },
    
                    success: (response) => {
    
                        Swal.close();
    
                        if (response.success) {
    
                            Swal.fire({
                                title: 'Cancelado!',
                                text: response.message || 'Cobrança cancelada com sucesso.',
                                icon: 'success',
                                width: '500px',
                                confirmButtonColor: '#3085d6'
                            });
    
                        } else {
    
                            Swal.fire({
                                title: 'Erro',
                                text: response.message || 'Não foi possível cancelar a cobrança.',
                                icon: 'error',
                                width: '500px',
                                confirmButtonColor: '#d33'
                            });
    
                            // Reabre modal em caso de erro
                            $('#modalCobrancaBancaria').modal('show');
                        }
                    },
    
                    error: () => {
    
                        Swal.close();
    
                        Swal.fire({
                            title: 'Erro',
                            text: 'Falha na comunicação com o servidor.',
                            icon: 'error',
                            width: '500px',
                            confirmButtonColor: '#d33'
                        });
    
                        // Reabre modal em caso de erro
                        $('#modalCobrancaBancaria').modal('show');
                    }
                });
    
            });
    
        }, 300);
    }

    /**
     * Paga a cobrança no Inter
     * @param {string} id_tabela_api - ID da tabela API
     * @returns {boolean}
     */
    PagarCobrancaInter(id_tabela_api) {
        debugger

        let url = this.getUrl();
    
        if (!url) {
            Swal.fire({
                title: 'Erro',
                text: 'URL não encontrada',
                icon: 'error',
                width: '700px',
                confirmButtonColor: '#d33'
            });
    
            return;
        }
    
        // Fecha a modal bootstrap
        $('#modalCobrancaBancaria').modal('hide');
    
        // Aguarda animação terminar
        setTimeout(() => {
    
            Swal.fire({
                title: 'Pagar Cobrança',
                width: '500px',
    
                html: `
                    <p style="font-size: 14px; color: #555; margin-bottom: 12px;">
                        Selecione o método de pagamento:
                    </p>
    
                    <div>
                        <select id="metodo_pagamento" class="form-control" style="margin: 4px 0 0 0; width: 100%; font-size: 12px;">
                            <option value="PIX">PIX</option>
                            <option value="BOLETO">BOLETO</option>
                        </select>
                    </div>
                `,
    
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar Pagamento',
                cancelButtonText: 'Voltar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
    
                preConfirm: () => {
                    debugger
    
                    const metodo_pagamento = $('#metodo_pagamento').val() ?? '';
    
                    if (!metodo_pagamento) {
                        Swal.showValidationMessage(
                            'Informe o método de pagamento.'
                        );
    
                        return false;
                    }
    
                    return metodo_pagamento;
                }
    
            }).then((result) => {
    
                // Se clicou em voltar/cancelar
                if (result.dismiss) {
    
                    // Reabre a modal
                    $('#modalCobrancaBancaria').modal('show');
    
                    return;
                }
    
                const metodo_pagamento = result.value;
    
                Swal.fire({
                    title: 'Processando...',
                    text: 'Aguarde enquanto a cobrança é paga',
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
                        mod: 'fin',
                        form: 'api_inter',
                        submenu: 'pagarCobranca',
                        opcao: 'ajax',
                        id_lancamento: id_tabela_api,
                        metodo_pagamento: metodo_pagamento,
                    },
    
                    success: (response) => {
    
                        Swal.close();
    
                        if (response.success) {
    
                            Swal.fire({
                                title: 'Paga!',
                                text: response.message || 'Cobrança paga com sucesso.',
                                icon: 'success',
                                width: '500px',
                                confirmButtonColor: '#3085d6'
                            });
    
                        } else {
    
                            Swal.fire({
                                title: 'Erro',
                                text: response.message || 'Não foi possível pagar a cobrança.',
                                icon: 'error',
                                width: '500px',
                                confirmButtonColor: '#d33'
                            });
    
                            // Reabre modal em caso de erro
                            $('#modalCobrancaBancaria').modal('show');
                        }
                    },
    
                    error: () => {
    
                        Swal.close();
    
                        Swal.fire({
                            title: 'Erro',
                            text: 'Falha na comunicação com o servidor.',
                            icon: 'error',
                            width: '500px',
                            confirmButtonColor: '#d33'
                        });
    
                        // Reabre modal em caso de erro
                        $('#modalCobrancaBancaria').modal('show');
                    }
                });
    
            });
    
        }, 300);
    }


    /**
     * Consulta a coleção de cobrança no Inter
     * @param {string} situacao - Situação da consulta
     */
    RecuperarColecaoCobranca(situacao) {

        let title_swal = '';
        let http_code = 0;
        let response_json = null;
        const situacao_consulta = situacao;
        
        switch(situacao_consulta) {
            case 'RECEBIDO':
                title_swal = 'Consulta de Cobranças Recebidas';
                break;
            case 'A_RECEBER':
                title_swal = 'Consulta de Cobranças A Receber';
                break;
            case 'MARCADO_RECEBIDO':
                title_swal = 'Consulta de Cobranças Marcadas como Recebidas';
                break;
            case 'ATRASADO':
                title_swal = 'Consulta de Cobranças Atrasadas';
                break;
            case 'CANCELADO':
                title_swal = 'Consulta de Cobranças Canceladas';
                break;
            case 'EXPIRADO':
                title_swal = 'Consulta de Cobranças Expiradas';
                break;
            case 'FALHA_EMISSAO':
                title_swal = 'Consulta de Cobranças com Falha de Emissão';
                break;
            case 'EM_PROCESSAMENTO':
                title_swal = 'Consulta de Cobranças em Processamento';
                break;
            case 'PROTESTO':
                title_swal = 'Consulta de Cobranças em Protesto';
                break;
        }
        
        
        Swal.fire({
            title: title_swal,
            width: '500px',
            html: `
                <br>
                <div style="display: flex; flex-direction: column; gap: 14px; text-align: left;">

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Filtrar data por</label>
                        <select id="filtrar_data_por" class="form-control" style="margin: 4px 0 0 0; width: 100%; font-size: 12px;">
                            <option value="VENCIMENTO">VENCIMENTO</option>
                            <option value="EMISSAO">EMISSAO</option>
                            <option value="PAGAMENTO">PAGAMENTO</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Data</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_inicial"  class="swal2-input" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <input type="text" id="data_final" class="swal2-input" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <button type="button" onclick="$('#data_inicial, #data_final').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Tipo de Cobrança</label>
                        <select id="tipo_cobranca" class="form-control" style="margin: 4px 0 0 0; width: 100%; font-size: 12px;">
                            <option value="SIMPLES">SIMPLES</option>
                            <option value="PARCELADO">PARCELADO</option>
                            <option value="RECORRENTE">RECORRENTE</option>
                        </select>
                    </div>
                
                </div>
            `,
            didOpen: () => {

                let data_atual = new Date();
                let data_form = data_atual.toISOString().split('T')[0].split('-').reverse().join('/');

                $('#data_inicial').daterangepicker({ 
                    singleDatePicker: true, 
                    locale: { format: 'DD/MM/YYYY' }, 
                    autoApply: true,
                    drops: 'down' ,
                    showDropdowns: true,
                });

                $('#data_inicial').val(data_form);

                $('#data_final').daterangepicker({ 
                    singleDatePicker: true, 
                    locale: { format: 'DD/MM/YYYY' }, 
                    autoApply: true,
                    drops: 'down' ,
                    showDropdowns: true,
                });

                $('#data_final').val(data_form);

            },
            showCancelButton: true,
            confirmButtonText: 'Consultar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {

                debugger

                // Captura os dados da consulta
                const filtrar_data_por = $('#filtrar_data_por').val() ?? '';
                const tipo_cobranca = $('#tipo_cobranca').val() ?? '';
                const conta_bancaria = $('#filtro_conta_api').val() ?? '';

                // Captura as datas
                const data_inicial = $('#data_inicial').val();
                const data_final   = $('#data_final').val();

                // Converte DD/MM/YYYY → YYYY-MM-DD para comparar
                const toISO = d => d.split('/').reverse().join('-');


                if (toISO(data_inicial) > toISO(data_final)) {
                    Swal.showValidationMessage('A data inicial do movimento não pode ser maior que a data final');
                    return false;
                }

                const formatarData = d => {
                    if (!d) return null;
                
                    const [dia, mes, ano] = d.split('/');
                    return `${ano}-${mes}-${dia}`;
                };

                return {
                    filtrar_data_por: filtrar_data_por,
                    data_inicial:  formatarData(data_inicial),
                    data_final: formatarData(data_final),
                    tipo_cobranca: tipo_cobranca,
                    conta_bancaria: conta_bancaria,
                    situacao: situacao_consulta,
                };

            }
        }).then((result) => {
            
            if (!result.isConfirmed) return;
        
            const { filtrar_data_por, data_inicial, data_final, tipo_cobranca, conta_bancaria, situacao } = result.value;

            const dados = {
                'filtrar_data_por': filtrar_data_por,
                'data_inicial': data_inicial,
                'data_final': data_final,
                'tipo_cobranca': tipo_cobranca,
                'conta_bancaria': conta_bancaria,
                'situacao': situacao,
                //'mockup': 1,
            }
        
            Swal.fire({ title: 'Consultando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        
            $.ajax({
                type: 'POST',
                url: window.location.pathname,
                dataType: 'json',
                data: {
                    'mod': 'fin',
                    'form': 'api_inter',
                    'submenu': 'recuperarColecaoCobranca',
                    'dados': dados,
                    'opcao': 'ajax'
                },
                success: (response) => {
                    Swal.close();

                    // Oculta o box de informações de como aplicar os filtros
                    if($('#como_aplicar_filtros').length > 0) {
                        $('#como_aplicar_filtros').hide();
                    }

                    if (response.success === true) {
                        debugger
                        this.ResponseColecaoCobrancaSuccess(response);
                    } else {
                        this.ResponseError(response);
                    }
                },
                error: (xhr) => {
                    debugger

                    Swal.close();

                    http_code = xhr.status;
                    response_json  = xhr.responseJSON;

                    console.log('response', response_json);

                    if(http_code === 400) {
                        this.ResponseError400(response_json);
                    } else {
                        this.ResponseError(response_json);
                    }

                }
            });
        });
    }

    /**
     * Trata resposta de sucesso da consulta de títulos liquidados.
     * Espera response.data no formato { cabecalho: {}, titulos: [] }.
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseColecaoCobrancaSuccess(response) {
        debugger
        if (typeof Swal !== 'undefined') Swal.close();

        var data = response && response.data;
        if (typeof data === 'string') {
            try { data = JSON.parse(data); } catch (e) { data = null; }
        }

        // Habilita/desabilita os botões de paginação conforme a página retornada.
        // É preciso reabilitar explicitamente porque o botão pode ter sido
        // desabilitado em uma página de extremidade anterior (primeira/última).
        const primeira = !!(data && data.primeiraPagina === true);
        const ultima   = !!(data && data.ultimaPagina   === true);

        $('#btn_pagina_anterior').prop('disabled', primeira);
        $('#btn_pagina_proxima').prop('disabled', ultima);

        // Reseta o "Marcar todos" a cada nova página/consulta: como os
        // checkboxes da página anterior são descartados, o estado do
        // cabeçalho não faz mais sentido permanecer marcado.
        $('#marcar_todos_thead').prop('checked', false);

        this.PreencherTabelaCobrancas(data);

        if (typeof apiJsonPainelExibir === 'function' && apiJsonPainelExibir(response)) return;
    }

    /**
     * Renderiza o cabeçalho e a tabela de cobrancas na área do painel.
     * @param {Object} data - Objeto { cabecalho, titulos } retornado pela API
     */
    PreencherTabelaCobrancas(data) {
        debugger
        var $area = $('#api_titulos_area');

        if (!$area.length) return;

        var cobrancas = data && Array.isArray(data.cobrancas) ? data.cobrancas : [];
        this.PreencherCobrancasNaTabela(cobrancas);

        $area.show();
    }

    /**
     * Preenche a tabela de cobrancas a partir do JSON da API Inter.
     * Cada item esperado:
     *  {
     *      cobranca: { codigoSolicitacao, seuNumero, situacao, dataSituacao,
     *                  dataEmissao, dataVencimento, valorNominal, tipoCobranca,
     *                  pagador: { nome, cpfCnpj } },
     *      boleto:   { nossoNumero, codigoBarras, linhaDigitavel },
     *      pix:      { txid, pixCopiaECola }
     *  }
     * @param {Array} cobrancas - Array de cobranças retornado pela API Inter
     */
    PreencherCobrancasNaTabela(cobrancas) {
        const tbody = document.getElementById('titulos_tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!Array.isArray(cobrancas) || cobrancas.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = this.COLUNAS_TITULOS.length + 1;
            td.style.textAlign = 'center';
            td.style.padding = '12px';
            td.textContent = 'Nenhuma cobrança encontrada para os filtros informados.';
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }

        cobrancas.forEach(item => {
            if (!item || typeof item !== 'object') return;

            const row = this.NormalizarCobrancaInter(item);

            const tr = document.createElement('tr');

            // Linha azul quando já existe o nosso número (boleto registrado);
            // vermelho claro quando ainda não há correspondência no banco.
            const corOriginal = row.existe_nosso_numero === true ? '#d6eaf8' : '#fce4e4';
            const corHover    = row.existe_nosso_numero === true ? '#9ec5e8' : '#f5b8b8';

            tr.style.backgroundColor = corOriginal;
            tr.style.cursor          = 'pointer';
            tr.style.transition      = 'background-color 0.15s ease-in-out';

            // Destaca a linha ao passar o mouse e restaura a cor base ao sair.
            tr.addEventListener('mouseenter', () => {
                tr.style.backgroundColor = corHover;
            });
            tr.addEventListener('mouseleave', () => {
                tr.style.backgroundColor = corOriginal;
            });


            tr.appendChild(this.CriarCelulaCheckbox(row));

            this.COLUNAS_TITULOS.forEach(key => {
                tr.appendChild(this.CriarCelulaTexto(row[key], key));
            });

            //tr.appendChild(this.CriarCelulaDetalhes(row));

            tbody.appendChild(tr);
        });
    }

    /**
     * Normaliza o objeto aninhado da API Inter para as chaves planas usadas
     * pelas colunas da tabela (COLUNAS_TITULOS), preservando os dados crus
     * em `raw` para uso na modal de detalhes.
     * @param {Object} item - Item da lista `cobrancas` retornada pela API Inter
     * @returns {Object} Linha normalizada
     */
    NormalizarCobrancaInter(item) {
        const cob     = item.cobranca || {};
        const boleto  = item.boleto   || {};
        const pix     = item.pix      || {};
        const pagador = cob.pagador   || {};

        const situacao = cob.situacao || '';
        const recebido = situacao === 'RECEBIDO' || situacao === 'MARCADO_RECEBIDO';

        return {
            nome_pagador:               pagador.nome || '',
            data_vencimento:            this.FormatarDataBR(cob.dataVencimento),
            data_pagamento:             recebido ? this.FormatarDataBR(cob.dataSituacao) : '',
            data_movimento:             this.FormatarDataBR(cob.dataSituacao || cob.dataEmissao),
            valor_titulo:               this.FormatarValorBR(cob.valorNominal),
            valor_pagamento:            recebido ? this.FormatarValorBR(cob.valorNominal) : '',
            descricao_origem_pagamento: situacao,

            existe_nosso_numero: !!boleto.nossoNumero,
            codigo_solicitacao:  cob.codigoSolicitacao || '',
            seu_numero:          cob.seuNumero || '',
            cpf_cnpj:            pagador.cpfCnpj || '',
            situacao,
            tipo_cobranca:       cob.tipoCobranca || '',
            boleto,
            pix,
            raw: item
        };
    }

    /**
     * Converte data ISO (YYYY-MM-DD) para o formato brasileiro (DD/MM/YYYY).
     * @param {string} iso
     * @returns {string}
     */
    FormatarDataBR(iso) {
        if (!iso || typeof iso !== 'string') return '';
        const partes = iso.split('-');
        if (partes.length !== 3) return iso;
        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    /**
     * Formata valores numéricos em string como moeda BRL (R$ 1.234,56).
     * @param {string|number} valor
     * @returns {string}
     */
    FormatarValorBR(valor) {
        if (valor === null || valor === undefined || valor === '') return '';
        const num = Number(valor);
        if (Number.isNaN(num)) return String(valor);
        return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    /**
     * Cria a célula do checkbox.
     * Grava nos data-attrs do input os campos necessários para a função
     * `ProcessaTitulosSelecionados` recuperar sem precisar percorrer o <tr>.
     * @param {Object} row - Linha normalizada (ver NormalizarCobrancaInter)
     * @returns {HTMLElement}
     */
    CriarCelulaCheckbox(row) {
        debugger
        const td = document.createElement('td');
        td.style.textAlign = 'center';

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'checkbox_titulo';

        const r = row || {};
        checkbox.setAttribute('data-seu_numero',          r.seu_numero ?? '');
        checkbox.setAttribute('data-data_movimento',      r.data_movimento ?? '');
        checkbox.setAttribute('data-data_pagamento',      r.data_pagamento ?? '');
        checkbox.setAttribute('data-nome_pagador',        r.nome_pagador ?? '');
        checkbox.setAttribute('data-descricao_pagamento', r.descricao_origem_pagamento ?? '');

        td.appendChild(checkbox);
        return td;
    }

    /**
     * Cria a célula de texto para uma coluna específica.
     * O `data-${key}` permite localizar a célula pelo nome lógico da coluna
     * (útil para testes, manipulação por outros JS, etc.).
     * @param {string} valor - Valor a exibir
     * @param {string} key   - Nome da coluna (uma das chaves de COLUNAS_TITULOS)
     * @returns {HTMLElement}
     */
    CriarCelulaTexto(valor, key) {
        const td = document.createElement('td');
        td.textContent = valor ?? '';
        if (key) td.setAttribute('data-' + key, valor ?? '');
        return td;
    }

    /**
     * Cria a célula do botão de detalhes
     * @param {Object} row - Linha do título
     * @returns {HTMLElement}
     */
    // CriarCelulaDetalhes(row) {
    //     const td = document.createElement('td');
    //     td.style.cssText = 'text-align:center; white-space:nowrap;';

    //     const btn = document.createElement('button');
    //     btn.type = 'button';
    //     btn.className = 'btn btn-info btn-xs';
    //     btn.textContent = 'Detalhes';
    //     btn.addEventListener('click', () => this.AbrirDetalhesTitulo(row));

    //     td.appendChild(btn);
    //     return td;
    // }

    

    /**
    * Trata resposta de erro 400 da consulta de cobranças no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseError400(response) {
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
    * Trata resposta de erro da consulta de cobranças no Inter
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseError(response) {
        debugger
        let title = response.message;
        let detail = response.data;
        let errors = response.errors || [];

        // Se houver erros internos, exibe os erros
        if(errors.length > 0) {
            console.log('errors', errors);
        }

        Swal.fire({
            title: title || 'Erro',
            text: detail || 'Erro ao consultar cobranças no Banco Inter',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33'
        });

        return false;
    }
}
