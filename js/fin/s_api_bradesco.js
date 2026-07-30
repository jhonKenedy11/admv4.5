/**
 * @name ApiBradesco
 * @description Classe para a API do Bradesco
 * @param {string} $path - Path da API
 * @param {array} $colunas_titulos - Colunas das tabelas de títulos
 * @returns {Object}
 * Informações importantes:
 * - Situacao: A = Aberto, B = Baixado, C = Cancelado
 * A situação e setada e response de cada consulta, depois recuperada 
 * pelo montagem do checkbox e setada no data-attr do checkbox.
 */
export default class ApiBradesco {

    constructor($path, $colunas_titulos = null) {
        this.baseUrl = $path;
        this.situacao = '';

        // Ordem das colunas da tabela de títulos, recebida do
        // `s_consolidacao_bancaria_apis.js` para manter a sincronia
        // com o <thead> do template `consolidacao_bancaria_apis_mostra.tpl`.
        this.COLUNAS_TITULOS = $colunas_titulos;
    }


    getUrl() {
        return this.baseUrl;
    }

    ConsultaTitulosBradesco(tipo_consulta) {

        const conta = $('#filtro_conta_api').val();
        const centro_custo = $('#filtro_centro_custo_api').val();
        const banco = $('#filtro_banco_api').val();

        if (!conta) return Swal.fire({ title: 'Erro', text: 'Conta não informada', icon: 'error' });
        if (!centro_custo) return Swal.fire({ title: 'Erro', text: 'Centro de custo não informado', icon: 'error' });
        if (!banco) return Swal.fire({ title: 'Erro', text: 'Banco não informado', icon: 'error' });

        switch (tipo_consulta) {
            case 'pendentes':
                this.ConsultaTitulosPendentesBradesco(banco, conta, centro_custo);
                break;
            case 'baixados':
                this.ConsultaTitulosBaixadosBradesco(banco, conta, centro_custo);
                break;
            case 'liquidados':
                this.ConsultaTitulosLiquidadosBradesco(banco, conta, centro_custo);
                break;
        }
    }

    // ############################################################ INICIO - Consulta títulos liquidados no Bradesco

    /**
     * Consulta títulos liquidados no Bradesco
     * @param {string} banco - Código do banco
     * @param {string} conta - Conta bancária
     * @param {string} centro_custo - Centro de custo
     */
    ConsultaTitulosLiquidadosBradesco(banco, conta_bancaria, centro_custo) {

        let http_code = null;

        Swal.fire({
            title: 'Consulta de Títulos Liquidados',
            width: '500px',
            html: `
                <br>
                <div style="display: flex; flex-direction: column; gap: 14px; text-align: left;">

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Data Movimento</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_movimento_de"  class="swal2-input" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <input type="text" id="data_movimento_ate" class="swal2-input" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <button type="button" onclick="$('#data_movimento_de, #data_movimento_ate').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>
            
                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Data Pagamento</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_pagamento_de"  class="swal2-input" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <input type="text" id="data_pagamento_ate" class="swal2-input" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <button type="button" onclick="$('#data_pagamento_de, #data_pagamento_ate').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Tipo de registro</label>
                        <select id="codigo_baixa" class="form-control" style="margin: 4px 0 0 0; width: 100%; font-size: 12px;">
                            <option value="0">0 - TODOS</option>
                            <option value="1">1 - COM REGISTRO</option>
                            <option value="2">2 - SEM REGISTRO</option>
                            <option value="3">3 - POR CONTABILIDADE </option>
                        </select>
                    </div>
                
                </div>
            `,
            didOpen: () => {

                let data_atual = new Date();
                let data_form = data_atual.toISOString().split('T')[0].split('-').reverse().join('/');

                $('#data_movimento_de').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_movimento_ate').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_pagamento_de').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_pagamento_de').val('');

                $('#data_pagamento_ate').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_pagamento_ate').val('');
            },
            showCancelButton: true,
            confirmButtonText: 'Consultar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {

                debugger

                const tipo_registro = $('#tipo_registro').val() ?? 0;

                // Captura e validacao de datas de vencimento
                const data_movimento_de = $('#data_movimento_de').val();
                const data_movimento_ate = $('#data_movimento_ate').val();
                const data_pagamento_de = $('#data_pagamento_de').val();
                const data_pagamento_ate = $('#data_pagamento_ate').val();

                // Converte DD/MM/YYYY → YYYY-MM-DD para comparar
                const toISO = d => d.split('/').reverse().join('-');


                if (toISO(data_movimento_de) > toISO(data_movimento_ate)) {
                    Swal.showValidationMessage('A data inicial do movimento não pode ser maior que a data final');
                    return false;
                }

                if (toISO(data_pagamento_de) > toISO(data_pagamento_ate)) {
                    Swal.showValidationMessage('A data inicial do pagamento não pode ser maior que a data final');
                    return false;
                }

                // const formatarData = d => {
                //     if (!d) return null;

                //     const [dia, mes, ano] = d.split('/');
                //     return `${ano}${mes}${dia}`;
                // };

                // Converte para formato AAAAMMDD
                const formatarData = d => d ? d.replace(/\//g, '') : null;

                return {
                    tipo_registro: tipo_registro,
                    data_movimento_de: formatarData(data_movimento_de),
                    data_movimento_ate: formatarData(data_movimento_ate),
                    data_pagamento_de: formatarData(data_pagamento_de),
                    data_pagamento_ate: formatarData(data_pagamento_ate),
                };

            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            const { tipo_registro, data_movimento_de, data_movimento_ate, data_pagamento_de, data_pagamento_ate } = result.value;

            const dados = {
                'conta_bancaria': conta_bancaria,
                'banco': banco,
                'centro_custo': centro_custo,
                'tipo_registro': tipo_registro,
                'data_movimento_de': data_movimento_de,
                'data_movimento_ate': data_movimento_ate,
                'data_pagamento_de': data_pagamento_de,
                'data_pagamento_ate': data_pagamento_ate,
            }

            Swal.fire({ title: 'Consultando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                type: 'POST',
                url: window.location.pathname,
                dataType: 'json',
                data: {
                    'mod': 'fin',
                    'form': 'api_bradesco',
                    'submenu': 'consultaTitulosLiquidados',
                    'dados': dados,
                    'opcao': 'ajax'
                },
                success: (response) => {
                    debugger
                    Swal.close();

                    // Oculta o box de informações de como aplicar os filtros
                    if ($('#como_aplicar_filtros').length > 0) {
                        $('#como_aplicar_filtros').hide();
                    }

                    if (response.success === true) {
                        this.ResponseTitulosLiquidadosSuccess(response);
                    } else {
                        this.ResponseError(response);
                    }

                },
                error: (xhr) => {
                    debugger
                    Swal.close();

                    http_code = xhr.status;

                    if (http_code === 400 || http_code === 412 || http_code === 500 || http_code === 422) {
                        this.ResponseArrayError(xhr.responseJSON);
                    } else {
                        this.ResponseError(xhr.responseJSON);
                    }
                }
            });
        });
    }


    /**
     * Trata resposta de sucesso da consulta de títulos liquidados no Bradesco (CBTTIAGW).
     * Apenas delega para a renderização compartilhada, já que o envelope é o
     * mesmo dos pendentes/baixados — só mudam os nomes dos campos dentro de
     * `titulos`, normalizados em `NormalizarTitulo`.
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseTitulosLiquidadosSuccess(response) {
        this.RenderizarRespostaTitulos(response);
    }

    // ############################################################ FIM - Consulta títulos liquidados no Bradesco


    // ############################################################ INICIO - Consulta títulos pendentes no Bradesco
    /**
     * Consulta títulos pendentes no Bradesco
     * @param {string} banco - Código do banco
     * @param {string} conta - Conta bancária
     * @param {string} centro_custo - Centro de custo
     */
    ConsultaTitulosPendentesBradesco(banco, conta_bancaria, centro_custo) {

        let http_code = null;
        let response = null;

        Swal.fire({
            title: 'Consulta de Títulos Pendentes',
            width: '500px',
            html: `
                <br>
                <div style="display: flex; flex-direction: column; gap: 14px; text-align: left;">
                    <div>
                        <label for="data_registro_de" style="font-size: 13px; font-weight: 600;">Data Registro</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_registro_de"  class="form-control" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <input type="text" id="data_registro_ate" class="form-control" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <button type="button" onclick="$('#data_registro_de, #data_registro_ate').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>
            
                    <div>
                        <label for="data_vencimento_de" style="font-size: 13px; font-weight: 600;">Data Vencimento</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_vencimento_de"  class="form-control" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <input type="text" id="data_vencimento_ate" class="form-control" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center;">
                            <button type="button" onclick="$('#data_vencimento_de, #data_vencimento_ate').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>

                    <div>
                        <label for="cpf_cnpj" style="font-size: 13px; font-weight: 600;">CPF/CNPJ</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="cpf_cnpj" class="form-control" placeholder="CPF/CNPJ" style="margin: 4px 0 0 0; width: 100%; text-align: center;">
                            <button type="button" onclick="$('#cpf_cnpj').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>
                </div>
            `,
            didOpen: () => {

                let data_atual = new Date();
                let data_form = data_atual.toISOString().split('T')[0].split('-').reverse().join('/');

                $('#data_registro_de').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_registro_ate').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'down',
                    showDropdowns: true,
                });

                $('#data_vencimento_de').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    autoApply: true,
                    drops: 'up',
                    showDropdowns: true,
                });

                $('#data_vencimento_de').val('');

                $('#data_vencimento_ate').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    autoApply: true,
                    drops: 'up',
                    showDropdowns: true,
                });

                $('#data_vencimento_ate').val('');
            },
            showCancelButton: true,
            confirmButtonText: 'Consultar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                debugger

                // Captura e validacao de datas de registro
                const data_registro_de = $('#data_registro_de').val();
                const data_registro_ate = $('#data_registro_ate').val();
                const cpf_cnpj = $('#cpf_cnpj').val();

                // Converte DD/MM/YYYY → YYYY-MM-DD para comparar
                const toISO = d => d.split('/').reverse().join('-');

                if (toISO(data_registro_de) > toISO(data_registro_ate)) {
                    Swal.showValidationMessage('A data inicial do registro não pode ser maior que a data final');
                    return false;
                }

                // Captura e validacao de datas de vencimento
                const data_vencimento_de = $('#data_vencimento_de').val();
                const data_vencimento_ate = $('#data_vencimento_ate').val();


                if (toISO(data_vencimento_de) > toISO(data_vencimento_ate)) {
                    Swal.showValidationMessage('A data inicial do vencimento não pode ser maior que a data final');
                    return false;
                }


                // Converte para formato AAAAMMDD
                const formatarData = d => d ? d.replace(/\//g, '') : null;

                return {
                    data_registro_de: formatarData(data_registro_de),
                    data_registro_ate: formatarData(data_registro_ate),
                    data_vencimento_de: formatarData(data_vencimento_de),
                    data_vencimento_ate: formatarData(data_vencimento_ate),
                    cpf_cnpj: cpf_cnpj,

                };

            }
        }).then((result) => {
            debugger
            if (!result.isConfirmed) return;

            const { data_registro_de,
                data_registro_ate,
                data_vencimento_de,
                data_vencimento_ate,
                cpf_cnpj,
            } = result.value;

            const dados = {
                'banco': banco,
                'conta_bancaria': conta_bancaria,
                'centro_custo': centro_custo,
                'cpf_cnpj': cpf_cnpj,
                'data_registro_de': data_registro_de,
                'data_registro_ate': data_registro_ate,
                'data_vencimento_de': data_vencimento_de,
                'data_vencimento_ate': data_vencimento_ate,

            }

            Swal.fire({ title: 'Consultando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            $.ajax({
                type: 'POST',
                url: window.location.pathname,
                dataType: 'json',
                data: {
                    'mod': 'fin',
                    'form': 'api_bradesco',
                    'submenu': 'consultaTituloPendente',
                    'dados': dados,
                    'opcao': 'ajax'
                },
                success: (response) => {
                    debugger
                    Swal.close();

                    // Oculta o box de informações de como aplicar os filtros
                    if ($('#como_aplicar_filtros').length > 0) {
                        $('#como_aplicar_filtros').hide();
                    }

                    if (response.success === true) {
                        this.ResponseTitulosPendentesSuccess(response);
                    } else {
                        this.ResponseError(response);
                    }
                },
                error: (xhr) => {
                    debugger
                    Swal.close();

                    http_code = xhr.status;

                    if (http_code === 400 || http_code === 412 || http_code === 500 || http_code === 422) {
                        this.ResponseArrayError(xhr.responseJSON);
                    } else {
                        this.ResponseError(xhr.responseJSON);
                    }
                }
            });
        });
    }

    /**
     * Trata resposta de sucesso da consulta de títulos pendentes no Bradesco (CBTTIAGV).
     * Apenas delega para a renderização compartilhada, já que pendentes e
     * baixados têm o mesmo envelope (apenas mudam nomes dentro de `titulos`).
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseTitulosPendentesSuccess(response) {
        this.RenderizarRespostaTitulos(response);
    }


    // ############################################################ FIM - Consulta títulos pendentes no Bradesco


    // ############################################################ INICIO - Consulta títulos baixados no Bradesco

    /**
     * Consulta títulos baixados no Bradesco
     * @param {string} banco - Código do banco
     * @param {string} conta - Conta bancária
     * @param {string} centro_custo - Centro de custo
     */
    ConsultaTitulosBaixadosBradesco(banco, conta_bancaria, centro_custo) {

        let http_code = null;

        Swal.fire({
            title: 'Consulta de Títulos Baixados',
            width: '450px',
            html: `
                <br>

                <div style="display: flex; flex-direction: column; gap: 14px; text-align: left;">
                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Data Vencimento</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="data_vencimento_de"  class="form-control" placeholder="De"  style="margin: 4px 0 0 0; width: 50%; text-align: center; font-size: 12px;">
                            <input type="text" id="data_vencimento_ate" class="form-control" placeholder="Até" style="margin: 4px 0 0 0; width: 50%; text-align: center; font-size: 12px;">
                            <button type="button" onclick="$('#data_vencimento_de, #data_vencimento_ate').val('')" style="background: none; border: none; cursor: pointer; color: #aaa; font-size: 18px;" title="Limpar">✕</button>
                        </div>
                    </div>

                    <div>
                        <label style="font-size: 13px; font-weight: 600;">Tipo de baixa</label>
                        <select id="codigo_baixa" class="form-control" style="margin: 4px 0 0 0; width: 100%; font-size: 12px;">
                            <option value="">Selecione</option>
                            <option value="51">51 - POR ACERTO</option>
                            <option value="52">52 - POR REGISTRO DUPLICADO</option>
                            <option value="53">53 - POR DECURSO DE PRAZO</option>
                            <option value="54">54 - POR MEDIDA JUDICIAL</option>
                            <option value="55">55 - POR REMESSA (CEB)</option>
                            <option value="56">56 - COBRADO - POR RASTREAMENTO</option>
                            <option value="57">57 - CONFORME SEU PEDIDO</option>
                            <option value="58">58 - PROTESTADO</option>
                            <option value="59">59 - DEVOLVIDO</option>
                            <option value="60">60 - ENTREGUE FRANCO DE PAGAMENTO</option>
                            <option value="61">61 - PAGO</option>
                            <option value="62">62 - PAGO EM CARTÓRIO</option>
                            <option value="63">63 - SUSTADO RETIRADO DE CARTÓRIO</option>
                            <option value="64">64 - SUSTADO SEM REMESSA A CARTÓRIO</option>
                            <option value="65">65 - TRANSFERIDO PARA DESCONTO</option>
                            <option value="66">66 - CRÉDITO EXDD</option>
                            <option value="67">67 - CRÉDITO EXDD - PAGO EM CARTÓRIO</option>
                            <option value="68">68 - COBRADO - POR BAIXA MANUAL</option>
                            <option value="69">69 - COBRADO - POR BAIXA MANUAL - PAGO EM CARTÓRIO</option>
                            <option value="70">70 - TRANSF. CESSÃO CRÉDITO</option>
                            <option value="71">71 - DEV. TRANSF. CESSÃO CRÉDITO</option>
                            <option value="72">72 - TRANSF. ENTRE CEDÊNCIA</option>
                        </select>
                    </div>
            
                </div>
            `,
            didOpen: () => {

                let data_atual = new Date();
                let data_form = data_atual.toISOString().split('T')[0].split('-').reverse().join('/');

                $('#data_vencimento_de').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'up',
                    showDropdowns: true,
                });


                $('#data_vencimento_ate').daterangepicker({
                    singleDatePicker: true,
                    locale: { format: 'DD/MM/YYYY' },
                    startDate: data_form,
                    autoApply: true,
                    drops: 'up',
                    showDropdowns: true,
                });

            },
            showCancelButton: true,
            confirmButtonText: 'Consultar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {

                const codigo_baixa = $('#codigo_baixa').val() ?? 0;

                // Captura e validacao de datas de vencimento
                const data_vencimento_de = $('#data_vencimento_de').val();
                const data_vencimento_ate = $('#data_vencimento_ate').val();

                // Converte DD/MM/YYYY → YYYY-MM-DD para comparar
                const toISO = d => d.split('/').reverse().join('-');


                if (toISO(data_vencimento_de) > toISO(data_vencimento_ate)) {
                    Swal.showValidationMessage('A data inicial do vencimento não pode ser maior que a data final');
                    return false;
                }

                const formatarData = d => {
                    if (!d) return null;

                    const [dia, mes, ano] = d.split('/');
                    return `${ano}${mes}${dia}`;
                };

                return {
                    codigo_baixa: codigo_baixa,
                    data_vencimento_de: formatarData(data_vencimento_de),
                    data_vencimento_ate: formatarData(data_vencimento_ate),
                };

            }
        }).then((result) => {
            debugger
            if (!result.isConfirmed) return;

            const { codigo_baixa, data_vencimento_de, data_vencimento_ate } = result.value;

            const dados = {
                'banco': banco,
                'conta_bancaria': conta_bancaria,
                'centro_custo': centro_custo,
                'codigo_baixa': codigo_baixa,
                'data_vencimento_de': data_vencimento_de,
                'data_vencimento_ate': data_vencimento_ate,
            };

            Swal.fire({ title: 'Consultando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            // Faz a consulta de títulos baixados
            $.ajax({
                type: 'POST',
                url: window.location.pathname,
                dataType: 'json',
                data: {
                    'mod': 'fin',
                    'form': 'api_bradesco',
                    'submenu': 'consultaTitulosBaixados',
                    'dados': dados,
                    'opcao': 'ajax'
                },
                success: (response) => {
                    debugger
                    Swal.close();

                    // Oculta o box de informações de como aplicar os filtros
                    if ($('#como_aplicar_filtros').length > 0) {
                        $('#como_aplicar_filtros').hide();
                    }

                    if (response.success === true) {
                        this.ResponseTitulosBaixadosSuccess(response);
                    } else {
                        this.ResponseError(response);
                    }
                },
                error: (xhr) => {
                    debugger
                    Swal.close();

                    http_code = xhr.status;

                    if (http_code === 400 || http_code === 412 || http_code === 500 || http_code === 422) {
                        this.ResponseArrayError(xhr.responseJSON);
                    } else {
                        this.ResponseError(xhr.responseJSON);
                    }

                }
            });
        });
    }

    /**
     * Trata resposta de sucesso da consulta de títulos baixados no Bradesco (CBTTIAGZ).
     * Apenas delega para a renderização compartilhada, já que o envelope é o
     * mesmo dos pendentes — só mudam os nomes dos campos dentro de `titulos`,
     * normalizados em `NormalizarTitulo`.
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseTitulosBaixadosSuccess(response) {
        this.RenderizarRespostaTitulos(response);
    }

    // ############################################################ FIM - Consulta títulos baixados no Bradesco


    /**
     * Baixa título para o banco
     * @param {number} id_tabela_api - ID do título na API Bradesco
     * @param {number} id_lancamento - ID do lançamento
     */
    BaixaTitulo(id_tabela_api = null, id_lancamento = null) {
        let http_code = 0;
        let url = this.getUrl();

        if (id_tabela_api == null || id_tabela_api == undefined) {
            Swal.fire({
                title: 'Erro',
                text: 'ID do título não encontrado',
                icon: 'error',
            });
            return;
        }

        if (id_lancamento == null || id_lancamento == undefined) {
            Swal.fire({
                title: 'Erro',
                text: 'ID do lançamento não encontrado',
                icon: 'error',
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {
                'mod': 'fin',
                'form': 'api_bradesco',
                'submenu': 'baixaTitulo',
                'dados': {
                    'id_lancamento': id_lancamento,
                    'id_tabela_api': id_tabela_api,
                },
                'opcao': 'ajax'
            },
            success: (response) => {
                Swal.close();
                if (response.success === true) {
                    this.ResponseSuccess(response);
                } else {
                    this.ResponseError(response);
                }
            },
            error: (xhr) => {
                Swal.close();
                http_code = xhr.status;

                this.ResponseArrayError(xhr.responseJSON);
            }
        });
    }


    /**
     * Solicita baixa do boleto na API Bradesco (consolidação bancária).
     * Envia o nosso número do título e o centro de custo/conta dos filtros da tela.
     *
     * @param {Object} row - Linha normalizada retornada por `NormalizarTitulo`
     */
    BaixaBoletoConsolidacao(row) {
        if (!row) return;

        const nossoNumero = row.nosso_numero || (row.raw && row.raw.nossoNumero);
        const centroCusto = $('#filtro_centro_custo_api').val();
        const conta = $('#filtro_conta_api').val();
        const url = this.getUrl();

        if (!centroCusto) {
            Swal.fire({ title: 'Erro', text: 'Centro de custo não informado', icon: 'error' });
            return;
        }
        if (!conta) {
            Swal.fire({ title: 'Erro', text: 'Conta bancária não informada', icon: 'error' });
            return;
        }
        if (!url) {
            Swal.fire({ title: 'Erro', text: 'URL da API não encontrada', icon: 'error' });
            return;
        }

        Swal.fire({
            title: 'Baixar boleto?',
            html: 'Confirma a baixa do título com nosso número <b>' + nossoNumero + '</b>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, baixar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#f0ad4e',
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Processando baixa...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    mod: 'fin',
                    form: 'api_bradesco',
                    submenu: 'baixaTituloConsolidacao',
                    opcao: 'ajax',
                    dados: {
                        nosso_numero: nossoNumero,
                        centro_custo: centroCusto,
                        conta: conta,
                    },
                },
                success: (response) => {
                    Swal.close();
                    if (response.success === true) {
                        this.ResponseSuccess(response);
                        $('#modal_detalhe_titulo_bradesco').modal('hide');
                    } else {
                        this.ResponseError(response);
                    }
                },
                error: (xhr) => {
                    debugger
                    Swal.close();

                    this.ResponseArrayError(xhr.responseJSON);

                },
            });
        });
    }


    /**
     * Altera a página da consulta de títulos no Bradesco
     * @param {string} direcao - Direção da página (anterior ou próxima)
     */
    alterarPaginaBradesco(direcao = null, conta = null) {
        debugger

        let response_json = null;
        let http_code = 0;
        let url = this.getUrl();

        if (!direcao) return Swal.fire({ title: 'Erro', text: 'Direção não informada', icon: 'error' });
        if (!conta) return Swal.fire({ title: 'Erro', text: 'Conta não informada', icon: 'error' });
        if (!url) return Swal.fire({ title: 'Erro', text: 'URL não encontrada', icon: 'error' });

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
                'form': 'api_bradesco',
                'submenu': 'alterarPagina',
                'dados': {
                    'direcao': direcao,
                    'conta_bancaria': conta
                },
                'opcao': 'ajax'
            },
            success: (response) => {
                debugger
                Swal.close();

                if (response.success === true) {
                    if (response.data.tipo_consulta === 'titulos_pendentes') {
                        this.ResponseTitulosPendentesSuccess(response);
                    } else if (response.data.tipo_consulta === 'titulos_baixados') {
                        this.ResponseTitulosBaixadosSuccess(response);
                    } else if (response.data.tipo_consulta === 'titulos_liquidados') {
                        this.ResponseTitulosLiquidadosSuccess(response);
                    }

                } else {
                    this.ResponseError(response);
                }

                // Oculta o box de informações de como aplicar os filtros
                if ($('#como_aplicar_filtros').length > 0) {
                    $('#como_aplicar_filtros').hide();
                }
            },
            error: (xhr) => {
                debugger
                Swal.close();

                http_code = xhr.status;

                if (http_code === 400 || http_code === 412 || http_code === 500) {
                    this.ResponseArrayErrorTrocarPagina(xhr.responseJSON);
                } else {
                    this.ResponseError(xhr.responseJSON);
                }

            }
        });
    }

    /**
     * Registra boleto no banco
     * @param {number} id_lancamento - ID do lançamento
     */
    RegistraBoleto(id_tabela_api = null) {

        let http_code = 0;
        let url = this.getUrl();

        if (id_tabela_api == null || id_tabela_api == undefined) {
            Swal.fire({
                title: 'Erro',
                text: 'ID não encontrado',
                icon: 'error',
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {
                'mod': 'fin',
                'form': 'api_bradesco',
                'submenu': 'registraBoleto',
                'id_lancamento': id_tabela_api,
                'opcao': 'ajax'
            },
            success: (response) => {
                debugger
                Swal.close();
                if (response.success === true) {
                    this.ResponseSuccessRegistraBoleto(response);
                } else {
                    this.ResponseError(response);
                }
            },
            error: (xhr) => {
                debugger
                Swal.close();
                http_code = xhr.status;

                this.ResponseArrayError(xhr.responseJSON);

            }
        });
    }


    /**
     * Trata resposta de sucesso do registro de boleto no Bradesco (CBTTIAGV).
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseSuccessRegistraBoleto(response) {
        debugger
        let detail = response.data;
        let update_lancamento = response.meta.update_lancamento ? 'Sim' : 'Não, informe ao suporte';
        let update_tabela_api_bradesco = response.meta.update_tabela_api_bradesco ? 'Sim' : 'Não, informe ao suporte';

        let text_html = `
            <p>Boleto registrado com sucesso</p>
            <p>Lançamento financeiro atualizado: <b>${update_lancamento}</b></p>
            <p>Tabela API Bradesco atualizada: <b>${update_tabela_api_bradesco}</b></p>
        `;

        Swal.fire({
            title: 'Sucesso',
            text: detail || 'Processo realizado com sucesso',
            icon: 'success',
            width: '700px',
            html: text_html
        });
    }

        /**
     * Consulta de título unitário no banco
     * @param {number} id_tabela_api - ID da tabela API
     * @param {number} id_lancamento - ID do lançamento
     */
    ConsultaDeTituloUnitario(id_tabela_api = null, id_lancamento = null) {
        let http_code = 0;
        let url = this.getUrl();
        
        if (id_tabela_api == null || id_tabela_api == undefined) {
            Swal.fire({
                title: 'Erro',
                text: 'ID não encontrado',
                icon: 'error',
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: {
                'mod': 'fin',
                'form': 'api_bradesco',
                'submenu': 'consultaDeTituloUnitario',
                'dados': {
                    'id_tabela_api': id_tabela_api,
                    'id_lancamento': id_lancamento,
                },
                'opcao': 'ajax'
            },
            success: (response) => {
                debugger
                Swal.close();
                if (response.success === true) {
                    this.ResponseSuccessConsultaDeTituloUnitario(response);
                } else {
                    this.ResponseArrayError(response);
                }
            },
            error: (xhr) => {
                debugger
                Swal.close();
                http_code = xhr.status;
                this.ResponseArrayError(xhr.responseJSON);
            }
        });
    }

    /**
     * Trata resposta de sucesso do registro de boleto no Bradesco (CBTTIAGV).
     * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
     */
    ResponseSuccessConsultaDeTituloUnitario(response) {
        debugger
        let detail = response.data;
        let update_lancamento = response.meta ? 'Sim' : 'Não foi localizado nenhuma alteração, valide o status pela menu consolidação bancária.';

        let text_html = `
            <p>Consulta realizada com sucesso</p>
            <p>Lançamento financeiro atualizado: <b>${update_lancamento}</b></p>
        `;

        Swal.fire({
            title: 'Sucesso',
            text: detail || 'Processo realizado com sucesso',
            icon: 'success',
            width: '700px',
            html: text_html
        });
    }


    // ############################################################
    // ################## FUNCOES RESPONSES GERAIS ################
    // ############################################################

    /**
    * Trata resposta de erro da consulta de títulos pendentes no Bradesco
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseError(response) {
        debugger
        let title = response.message;
        let detail = response.data;
        let errors = response.errors || [];

        // Se houver erros internos, exibe os erros
        if (errors.length > 0) {
            console.log('errors', errors);
        }

        Swal.fire({
            title: title || 'Erro',
            text: detail || 'Erro ao consultar títulos pendentes no Banco Bradesco',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33'
        });

        return false;
    }

    /**
    * Trata resposta de erro 400 da consulta de títulos pendentes no Bradesco
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseArrayError(response) {
        debugger
        let title = response.message;
        let errors = response.errors;
        let detail = response.data;

        Swal.fire({
            title: title || 'Erro não identificado',
            //text: detail || 'Erro de validação dos dados',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33',
            html: errors.map(error => `<p>${error}</p>`).join('')

        });

        return false;
    }

    /**
    * Trata resposta de erro 400 da consulta de títulos pendentes no Bradesco
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseArrayErrorTrocarPagina(response) {
        debugger
        //let title     = response.message;
        let errors = response.errors;
        let consulta = response.data;
        let new_title = '';

        if (consulta === 'titulos_pendentes') {
            new_title = 'Erro ao alterar a página da consulta de títulos pendentes';
        } else if (consulta === 'titulos_baixados') {
            new_title = 'Erro ao alterar a página da consulta de títulos baixados';
        } else if (consulta === 'titulos_liquidados') {
            new_title = 'Erro ao alterar a página da consulta de títulos liquidados';
        }

        Swal.fire({
            title: new_title || 'Erro não identificado',
            //text: detail || 'Erro de validação dos dados',
            icon: 'error',
            width: '700px',
            confirmButtonColor: '#d33',
            html: errors.map(error => `<p>${error}</p>`).join('')
        });

        return false;
    }

    /**
    * Trata resposta de sucesso da consulta de títulos pendentes no Bradesco
    * @param {Object} response - Resposta padronizada { success, message, data, meta, timestamp }
    */
    ResponseSuccess(response) {
        debugger

        let detail = response.data;

        Swal.fire({
            title: 'Sucesso',
            text: detail || 'Processo realizado com sucesso',
            icon: 'success',
            width: '700px',
            confirmButtonColor: '#3085d6'
        });
        return true;
    }

    // ############################################################
    // ################## FUNCOES AUXILIARES ######################
    // ############################################################

    /**
    * Renderiza a resposta padrão de consulta de títulos do Bradesco
    * (compartilhada entre pendentes / CBTTIAGV e baixados / CBTTIAGZ).
    *
    * Espera response.data com o envelope:
    *   {
    *     status, transacao, mensagem, causa,
    *     pagina, indMaisPagina, qtdeOcorr, vtotTitulos,
    *     titulos: [ ... ]
    *   }
    *
    * A diferença entre pendentes e baixados está apenas nos nomes dos campos
    * dentro de cada item de `titulos`, tratada em `NormalizarTitulo`.
    *
    * @param {Object} response - Resposta padronizada { success, message, data, ... }
    */
    RenderizarRespostaTitulos(response) {
        debugger
        if (typeof Swal !== 'undefined') Swal.close();

        var data = response && response.data;
        if (typeof data === 'string') {
            try { data = JSON.parse(data); } catch (e) { data = null; }
        }

        // Habilita/desabilita os botões de paginação conforme a página retornada.
        // No Bradesco a paginação vem em dois campos:
        //   - `pagina`        : número da página atual (1 = primeira)
        //   - `indMaisPagina` : 'S' indica que existem mais páginas a frente
        const pagina_atual = data && data.pagina ? Number(data.pagina) : 1;
        const tem_proxima = !!(data && String(data.indMaisPagina || '').toUpperCase() === 'S');

        const primeira = pagina_atual <= 1;
        const ultima = !tem_proxima;

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
     * Renderiza a tabela de títulos na área do painel.
     * Compartilhada entre pendentes (CBTTIAGV) e baixados (CBTTIAGZ).
     * @param {Object} data - Objeto retornado pela API Bradesco (contém `titulos`)
     */
    PreencherTabelaCobrancas(data) {
        debugger
        var $area = $('#api_titulos_area');

        if (!$area.length) return;

        var titulos = data && Array.isArray(data.titulos) ? data.titulos : [];
        this.PreencherTitulosNaTabela(titulos);

        $area.show();
    }

    /**
     * Preenche a tabela de títulos a partir do JSON da API Bradesco.
     * Atende aos dois formatos planos retornados pelas transações:
     *   - CBTTIAGV (pendentes): pagador.nome, dataReg/dataEmis/dataVencto,
     *                           valTitulo, qtdeDecima, codStatus/descrStatus...
     *   - CBTTIAGZ (baixados) : nomeSacado, cpfCnpjSacado, dataRegistro/
     *                           dataEmissao/dataVencimento, valorTitulo,
     *                           quantidadeCasaDecimal, statusTitulo/
     *                           descricaoStatusTitulo, dataPagamento,
     *                           dataBaixa, valorPago...
     * @param {Array} titulos - Array de títulos retornado pela API Bradesco
     */
    PreencherTitulosNaTabela(titulos) {
        const tbody = document.getElementById('titulos_tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!Array.isArray(titulos) || titulos.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = this.COLUNAS_TITULOS.length + 2;
            td.style.textAlign = 'center';
            td.style.padding = '12px';
            td.textContent = 'Nenhum título encontrado para os filtros informados.';
            tr.appendChild(td);
            tbody.appendChild(tr);
            return;
        }

        titulos.forEach(item => {
            if (!item || typeof item !== 'object') return;

            const row = this.NormalizarTitulo(item);

            const tr = document.createElement('tr');

            // Linha azul quando já existe o nosso número (boleto registrado);
            // vermelho claro quando ainda não há correspondência no banco.
            const corOriginal = row.existe_nosso_numero === true ? '#d6eaf8' : '#fce4e4';
            const corHover = row.existe_nosso_numero === true ? '#9ec5e8' : '#f5b8b8';

            tr.style.backgroundColor = corOriginal;
            tr.style.cursor = 'pointer';
            tr.style.transition = 'background-color 0.15s ease-in-out';

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

            tr.appendChild(this.CriarCelulaDetalhes(row));

            tbody.appendChild(tr);
        });
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
        checkbox.setAttribute('data-seu_numero', r.seu_numero ?? '');
        checkbox.setAttribute('data-data_movimento', r.data_movimento ?? '');
        checkbox.setAttribute('data-data_pagamento', r.data_pagamento ?? '');
        checkbox.setAttribute('data-nome_pagador', r.nome_pagador ?? '');
        checkbox.setAttribute('data-descricao_pagamento', r.descricao_origem_pagamento ?? '');
        checkbox.setAttribute('data-codigo_status', r.codigo_status ?? '');

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

    CriarCelulaDetalhes(row) {
        const td = document.createElement('td');
        td.style.cssText = 'text-align:center; white-space:nowrap;';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-info btn-xs';
        btn.textContent = 'Detalhes';
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.AbrirDetalhesTitulo(row);
        });

        td.appendChild(btn);
        return td;
    }

    /**
     * Garante que a modal de detalhes do Bradesco exista no DOM.
     * Criada dinamicamente para não depender de alterações no template
     * compartilhado com outros bancos.
     *
     * @returns {jQuery}
     */
    GarantirModalDetalheTitulo() {
        const idModal = 'modal_detalhe_titulo_bradesco';

        if ($('#' + idModal).length) {
            return $('#' + idModal);
        }

        const html = ''
            + '<div class="modal fade" id="' + idModal + '" tabindex="-1" role="dialog"'
            + ' aria-labelledby="modal_detalhe_titulo_bradesco_label">'
            + '  <div class="modal-dialog modal-lg" role="document">'
            + '    <div class="modal-content">'
            + '      <div class="modal-header">'
            + '        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">'
            + '          <span aria-hidden="true">&times;</span>'
            + '        </button>'
            + '        <h4 class="modal-title" id="modal_detalhe_titulo_bradesco_label">'
            + '          <i class="fa fa-info-circle"></i> Detalhes do título'
            + '        </h4>'
            + '      </div>'
            + '      <div class="modal-body">'
            + '        <table class="table table-bordered table-condensed" style="margin-bottom:0;">'
            + '          <tbody id="modal_detalhe_titulo_bradesco_tbody"></tbody>'
            + '        </table>'
            + '      </div>'
            + '      <div class="modal-footer">'
            + '        <button type="button" class="btn btn-warning" id="btn_baixa_boleto_bradesco_modal">'
            + '          <i class="fa fa-download"></i> Cancelar Titulo'
            + '        </button>'
            + '        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>'
            + '      </div>'
            + '    </div>'
            + '  </div>'
            + '</div>';

        const $modal = $(html).appendTo('body');

        $modal.find('#btn_baixa_boleto_bradesco_modal').on('click', () => {
            if (this._detalheTituloAtual) {
                this.BaixaBoletoConsolidacao(this._detalheTituloAtual);
            }
        });

        return $modal;
    }

    /**
     * Abre a modal de detalhes do registro (criada dinamicamente no JS).
     * Utiliza o objeto original em `row.raw` (CBTTIAGV / CBTTIAGZ / CBTTIAGW)
     * e os campos já normalizados da linha da tabela.
     *
     * @param {Object} row - Linha normalizada retornada por `NormalizarTitulo`
     */
    AbrirDetalhesTitulo(row) {
        if (!row) return;

        this._detalheTituloAtual = row;

        const $modal = this.GarantirModalDetalheTitulo();
        const $tbody = $('#modal_detalhe_titulo_bradesco_tbody');

        if (!$tbody.length) return;

        $tbody.empty();

        const campos = this.MontarCamposDetalheTitulo(row);

        if (campos.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 2;
            td.style.textAlign = 'center';
            td.textContent = 'Nenhum detalhe disponível para este título.';
            tr.appendChild(td);
            $tbody[0].appendChild(tr);
        } else {
            campos.forEach(campo => {
                if (campo.section) {
                    $tbody[0].appendChild(this.CriarSecaoDetalheModal(campo.section));
                    return;
                }
                $tbody[0].appendChild(this.CriarLinhaDetalheModal(campo.label, campo.valor));
            });
        }

        const $label = $('#modal_detalhe_titulo_bradesco_label');
        $label.empty()
            .append($('<i class="fa fa-info-circle"></i>'))
            .append(document.createTextNode(' Detalhes — ' + (row.nome_pagador || row.seu_numero || 'Título')));

        $modal.modal('show');
    }

    /**
     * Monta a lista de campos (com seções) exibidos na modal de detalhes.
     * @param {Object} row
     * @returns {Array<{section?: string, label?: string, valor?: string}>}
     */
    MontarCamposDetalheTitulo(row) {
        const raw = row.raw || {};
        const pagador = raw.pagador || {};
        const sacador = raw.sacador || {};
        const cpfObj = raw.cpfCnpjSacado || {};
        const qtdeDecima = Number(raw.quantidadeCasaDecimal ?? raw.qtdeDecima ?? 2);

        const campos = [];
        const add = (label, valor) => {
            if (valor === null || valor === undefined || valor === '') return;
            campos.push({ label, valor: String(valor) });
        };
        const addSection = (titulo) => campos.push({ section: titulo });

        addSection('Identificação');
        add('Nosso Número', row.nosso_numero || raw.nossoNumero);
        add('Dígito Nosso Número', raw.digitoNossoNumero);
        add('Seu Número', row.seu_numero || raw.seuNumero);
        add('Código Status', row.codigo_status || raw.codStatus || raw.statusTitulo);
        add('Status', row.descricao_status || raw.descrStatus || raw.descricaoStatusTitulo || raw.descricaoOrigemPagamento);
        add('Espécie Documento', raw.especDocto);
        add('Tipo Registro', raw.tipoRegistro);
        add('Controle Participante', raw.ctrlPartic);

        addSection('Pagador');
        add('Nome', row.nome_pagador || raw.nomePagador || raw.nomeSacado || pagador.nome);
        add('CPF/CNPJ', this.FormatarCpfCnpjBradesco(cpfObj.cpfCnpj || pagador.cnpjCpf || row.cpf_cnpj));
        add('Filial', pagador.filial);
        add('Controle', pagador.controle);

        const sacadorNome = sacador.nome || raw.nomeSacadorAvalista || '';
        const sacadorDoc = sacador.cnpjCpf || raw.cnpjSacadorAvalista || '';
        if (sacadorNome || (sacadorDoc && Number(sacadorDoc) !== 0)) {
            addSection('Sacador / Avalista');
            add('Nome', sacadorNome);
            add('CPF/CNPJ', this.FormatarCpfCnpjBradesco(sacadorDoc));
            add('Filial', sacador.filial);
            add('Controle', sacador.controle);
        }

        addSection('Datas');
        add('Data Registro', this.FormatarDataBradesco(raw.dataRegistro || raw.dataReg));
        add('Data Emissão', this.FormatarDataBradesco(raw.dataEmissao || raw.dataEmis));
        add('Data Vencimento', row.data_vencimento || this.FormatarDataBradesco(raw.dataVencimento || raw.dataVencto));
        add('Data Pagamento', row.data_pagamento || this.FormatarDataBradesco(raw.dataPagamento));
        add('Data Baixa', this.FormatarDataBradesco(raw.dataBaixa));
        add('Data Movimento', row.data_movimento || this.FormatarDataBradesco(raw.dataMovimento));

        addSection('Valores');
        add('Valor Título', row.valor_titulo || this.FormatarValorBradesco(raw.valorTitulo ?? raw.valTitulo, qtdeDecima));
        add('Valor Pagamento', row.valor_pagamento || this.FormatarValorBradesco(raw.valorPagamento ?? raw.valorPago, qtdeDecima));
        add('Valor Movimento', this.FormatarValorBradesco(raw.valorMovimento, qtdeDecima));
        add('Valor Oscilação', this.FormatarValorBradesco(raw.valorOscilacao, qtdeDecima));
        add('Casas Decimais', qtdeDecima);

        addSection('Banco / Agência');
        add('Banco Depósito', raw.bcoDepos || raw.bancoRecebor);
        add('Agência Depósito', raw.agenDepos || raw.agenciaRecebora);

        addSection('Indicadores');
        add('Débito Automático', this.FormatarSimNaoBradesco(raw.debitoAuto));
        add('Aceite', this.FormatarSimNaoBradesco(raw.aceite));
        add('Rateio', this.FormatarSimNaoBradesco(raw.rateio));
        add('Título Parcelado', this.FormatarSimNaoBradesco(raw.indTitParceld));
        add('Parcela Principal', this.FormatarSimNaoBradesco(raw.indParcelaPrin));
        add('Boleto DDA', this.FormatarSimNaoBradesco(raw.indBoletoDda));

        return campos;
    }

    /**
     * Cria uma linha de seção na tabela da modal de detalhes.
     * @param {string} titulo
     * @returns {HTMLTableRowElement}
     */
    CriarSecaoDetalheModal(titulo) {
        const tr = document.createElement('tr');
        const th = document.createElement('th');
        th.colSpan = 2;
        th.style.cssText = 'background:#f5f5f5; font-size:13px; padding:8px 12px;';
        th.textContent = titulo;
        tr.appendChild(th);
        return tr;
    }

    /**
     * Cria uma linha label/valor na tabela da modal de detalhes.
     * @param {string} label
     * @param {string} valor
     * @returns {HTMLTableRowElement}
     */
    CriarLinhaDetalheModal(label, valor) {
        const tr = document.createElement('tr');

        const th = document.createElement('th');
        th.style.cssText = 'width:38%; font-weight:600; vertical-align:top;';
        th.textContent = label;

        const td = document.createElement('td');
        td.textContent = valor ?? '';

        tr.appendChild(th);
        tr.appendChild(td);
        return tr;
    }

    /**
     * Tela consolidacao_bancaria_apis: exibe resposta em #api_json_result e suprime Swal.
     * @returns {boolean} true se o painel existir e foi preenchido
     */
    ApiJsonPainelExibir(obj) {
        if (typeof jQuery === 'undefined' || !jQuery('#api_json_result').length) {
            return false;
        }
        try {
            jQuery('#api_json_result').text(JSON.stringify(obj, null, 2));
        } catch (e) {
            jQuery('#api_json_result').text(String(obj));
        }
        return true;
    }

    /**
     * Converte indicadores S/N do Bradesco para Sim/Não.
     * @param {string} valor
     * @returns {string}
     */
    FormatarSimNaoBradesco(valor) {
        if (valor === null || valor === undefined || valor === '') return '';
        const v = String(valor).trim().toUpperCase();
        if (v === 'S') return 'Sim';
        if (v === 'N') return 'Não';
        return String(valor);
    }

    /**
     * Exibe CPF/CNPJ numérico retornado pelo Bradesco (sem máscara na API).
     * @param {string|number} valor
     * @returns {string}
     */
    FormatarCpfCnpjBradesco(valor) {
        if (valor === null || valor === undefined || valor === '') return '';
        const str = String(valor).replace(/\D/g, '');
        if (!str || /^0+$/.test(str)) return '';
        return str;
    }

    /**
     * Normaliza o objeto plano retornado pela API Bradesco para as chaves
     * usadas pelas colunas da tabela (COLUNAS_TITULOS), preservando o objeto
     * original em `raw` para uso na modal de detalhes.
     *
     * Suporta os três formatos da API Bradesco por meio de fallback de nomes:
     *
     *   Coluna                     | CBTTIAGV (pendentes)   | CBTTIAGZ (baixados)        | CBTTIAGW (liquidados)
     *   ---------------------------+------------------------+----------------------------+----------------------------
     *   nome_pagador               | pagador.nome           | nomeSacado                 | nomePagador
     *   data_vencimento            | dataVencto             | dataVencimento             | dataVencimento
     *   data_pagamento             | dataReg* (se pago)     | dataPagamento (≠00000000)  | dataPagamento
     *   data_movimento             | dataReg / dataEmis     | dataBaixa / dataRegistro   | dataMovimento
     *   valor_titulo               | valTitulo / qtdeDec.   | valorTitulo / qtdCasaDec.  | valorTitulo / (2 casas)
     *   valor_pagamento            | valTitulo* (se pago)   | valorPago (>0)             | valorPagamento
     *   descricao_origem_pagamento | descrStatus            | descricaoStatusTitulo      | descricaoOrigemPagamento
     *
     *   * No formato CBTTIAGV não há campos específicos de pagamento, então
     *     usamos `codStatus === 13` ("PAGO NO DIA") como indicador.
     *
     * @param {Object} item - Item da lista `titulos` retornada pela API Bradesco
     * @returns {Object} Linha normalizada
     */
    NormalizarTitulo(item) {
        debugger
        // Coalesce de nomes entre os 3 formatos CBTTIAGV / CBTTIAGZ / CBTTIAGW.
        const pagador = item.pagador || {};
        const cpfObj = item.cpfCnpjSacado || {};

        const nomePagador = item.nomePagador || item.nomeSacado || pagador.nome || '';
        const cpfCnpj = cpfObj.cpfCnpj || pagador.cnpjCpf || '';
        const dataRegistro = item.dataRegistro || item.dataReg || '';
        const dataEmissao = item.dataEmissao || item.dataEmis || '';
        const dataVencimento = item.dataVencimento || item.dataVencto || '';
        const dataBaixa = item.dataBaixa || '';
        const dataPagamento = item.dataPagamento || '';
        const dataMovimentoRaw = item.dataMovimento || '';

        // CBTTIAGW não devolve `quantidadeCasaDecimal`/`qtdeDecima` – assume 2.
        const qtdeDecima = Number(item.quantidadeCasaDecimal ?? item.qtdeDecima ?? 2);
        const valorTit = item.valorTitulo ?? item.valTitulo ?? null;
        // `valorPagamento` (CBTTIAGW) > `valorPago` (CBTTIAGZ) > null (CBTTIAGV).
        const valorPagoBruto = item.valorPagamento ?? item.valorPago ?? null;

        const codStatus = Number(item.statusTitulo ?? item.codStatus ?? 0);
        const descrStatus = item.descricaoOrigemPagamento || item.descricaoStatusTitulo || item.descrStatus || '';

        // Indicadores de "pago":
        //   - CBTTIAGZ: dataPagamento ≠ "00000000" ou valorPago > 0
        //   - CBTTIAGW: dataPagamento sempre presente e valorPagamento > 0
        //   - CBTTIAGV: codStatus 13 (PAGO NO DIA)
        // Tratamos `valorPago === 0` como "não pago", já que o Bradesco
        // devolve 0 para títulos baixados sem pagamento (ex.: status 57 –
        // "CONFORME SEU PEDIDO"). O operador `??` precisa dessa checagem
        // explícita porque ele não substitui o valor 0.
        const temDataPagto = !!dataPagamento && String(dataPagamento) !== '00000000';
        const temValorPago = valorPagoBruto !== null && Number(valorPagoBruto) > 0;
        const pago = temDataPagto || temValorPago || codStatus === 13;

        // Quando não houver `valorPago` válido, cai no valor do título –
        // mantém a coluna preenchida em vez de mostrar "R$ 0,00" ou vazio.
        const valorPagamentoBruto = temValorPago ? valorPagoBruto : valorTit;

        const valorTituloFmt = this.FormatarValorBradesco(valorTit, qtdeDecima);
        const valorPagamentoFmt = this.FormatarValorBradesco(valorPagamentoBruto, qtdeDecima);

        // Prioridade da data de movimento:
        //   1. `dataMovimento` (CBTTIAGW – campo dedicado)
        //   2. `dataBaixa`    (CBTTIAGZ)
        //   3. `dataRegistro` / `dataEmissao` (CBTTIAGV – fallback)
        const dataMovimento = dataMovimentoRaw || dataBaixa || dataRegistro || dataEmissao;

        // Para `data_pagamento` priorizamos o campo dedicado quando existir;
        // caso contrário, em pendentes pagos usamos a data de registro como
        // aproximação (mantém o comportamento anterior).
        const dataPagamentoFmt = temDataPagto
            ? this.FormatarDataBradesco(dataPagamento)
            : (pago ? this.FormatarDataBradesco(dataRegistro) : '');

        return {
            nome_pagador: nomePagador,
            data_vencimento: this.FormatarDataBradesco(dataVencimento),
            data_pagamento: dataPagamentoFmt,
            data_movimento: this.FormatarDataBradesco(dataMovimento),
            valor_titulo: valorTituloFmt,
            valor_pagamento: valorPagamentoFmt,
            descricao_origem_pagamento: descrStatus,

            existe_nosso_numero: !!item.nossoNumero,
            nosso_numero: item.nossoNumero !== undefined && item.nossoNumero !== null ? String(item.nossoNumero) : '',
            seu_numero: item.seuNumero !== undefined && item.seuNumero !== null ? String(item.seuNumero) : '',
            cpf_cnpj: cpfCnpj,
            codigo_status: codStatus,
            descricao_status: descrStatus,
            raw: item
        };
    }

    /**
     * Converte uma data do Bradesco para DD/MM/YYYY, detectando
     * automaticamente o formato:
     *   - DDMMYYYY  → usado por CBTTIAGV (pendentes) e CBTTIAGW (liquidados)
     *   - YYYYMMDD  → usado por CBTTIAGZ (baixados)
     *
     * O Bradesco costuma devolver datas como NÚMERO no JSON (ex.: `3012020`
     * em vez de `"03012020"`), o que faz o zero à esquerda sumir. Completamos
     * com zeros à esquerda até atingir 8 caracteres antes de fatiar.
     *
     * Heurística de detecção: se os 4 primeiros dígitos formarem um ano
     * plausível (1900–2099), interpretamos como YYYYMMDD; caso contrário
     * tentamos DDMMYYYY. Valores como "00000000" / 0 retornam string vazia.
     *
     * @param {string|number} valor
     * @returns {string}
     */
    FormatarDataBradesco(valor) {
        if (valor === null || valor === undefined || valor === '') return '';

        var str = String(valor).trim();
        if (str === '' || /^0+$/.test(str)) return '';

        // Padding com zeros à esquerda até bater em 8 chars.
        while (str.length < 8) str = '0' + str;
        if (str.length !== 8) return String(valor);

        // Tentativa 1 – YYYYMMDD (formato dos baixados, CBTTIAGZ).
        var anoY = Number(str.substr(0, 4));
        var mesY = Number(str.substr(4, 2));
        var diaY = Number(str.substr(6, 2));

        if (anoY >= 1900 && anoY <= 2099 && mesY >= 1 && mesY <= 12 && diaY >= 1 && diaY <= 31) {
            return str.substr(6, 2) + '/' + str.substr(4, 2) + '/' + str.substr(0, 4);
        }

        // Tentativa 2 – DDMMYYYY (formato dos pendentes/liquidados, CBTTIAGV/W).
        var diaD = Number(str.substr(0, 2));
        var mesD = Number(str.substr(2, 2));
        var anoD = Number(str.substr(4, 4));

        if (diaD >= 1 && diaD <= 31 && mesD >= 1 && mesD <= 12 && anoD >= 1900 && anoD <= 2099) {
            return str.substr(0, 2) + '/' + str.substr(2, 2) + '/' + str.substr(4, 4);
        }

        // Formato inesperado: devolve o valor original para investigação.
        return String(valor);
    }

    /**
     * Formata valor inteiro retornado pelo Bradesco aplicando as casas
     * decimais indicadas em `qtdeDecima` (ex.: 5000 com qtdeDecima=2 → R$ 50,00).
     * @param {string|number} valor
     * @param {number} qtdeDecima
     * @returns {string}
     */
    FormatarValorBradesco(valor, qtdeDecima) {
        if (valor === null || valor === undefined || valor === '') return '';
        var num = Number(valor);
        if (Number.isNaN(num)) return String(valor);
        var div = Math.pow(10, Number(qtdeDecima || 0));
        return (num / div).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

}