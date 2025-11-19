
function controlInputs(report)
{
    // Definir o tipo de relatório
    if(document.getElementById("tipoRelatorio")){
        document.getElementById("tipoRelatorio").value = report;
    }

    switch (report) {
        case "lancamentos_data":
            controlInputsLancamentosData();
            break;
        case "fluxo_caixa":
            controlInputsFluxoCaixa();
            break;
        case "consolidacao":
            controlInputsConsolidacao();
            break;
        case "resumo_genero":
            controlInputsResumoGenero();
            break;
        case "centro_custo_analitico":
            controlInputsCentroCustoAnalitico();
            break;
        case "centro_custo_sintetico":
            controlInputsCentroCustoSintetico();
            break;
        case "dre_financeiro":
            controlInputsDREFinanceiro();
            break;
        case "rel_financeiro_data_entrega":
            controlInputsRelFinanceiroDataEntrega();
            break;
        }
    }
    
function controlInputsLancamentosData()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Situação Documento, Tipo Documento, Conta, Centro Custo, Pessoa, Gênero
    // Todos os campos habilitados
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#sitdocto').prop('disabled', false);
    $('#tipoDocto').prop('disabled', false);
    $('#conta').prop('disabled', false);
    $('#filial').prop('disabled', false);
    $('#pessoa').prop('disabled', false);
    $('#genero').prop('disabled', false);
}

function controlInputsFluxoCaixa()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Conta, Centro Custo
    // Desabilitar: Situação Documento, Tipo Documento, Pessoa, Gênero
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#conta').prop('disabled', false);
    $('#filial').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
    $('#genero').prop('disabled', true);
}

function controlInputsConsolidacao()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Conta, Centro Custo
    // Desabilitar: Situação Documento, Tipo Documento, Pessoa, Gênero
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#conta').prop('disabled', false);
    $('#filial').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
    $('#genero').prop('disabled', true);
}

function controlInputsResumoGenero()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Centro Custo, Gênero
    // Desabilitar: Situação Documento, Tipo Documento, Conta, Pessoa
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#filial').prop('disabled', false);
    $('#genero').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#conta').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
}

function controlInputsCentroCustoAnalitico()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Centro Custo, Gênero
    // Desabilitar: Situação Documento, Tipo Documento, Conta, Pessoa
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#filial').prop('disabled', false);
    $('#genero').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#conta').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
}

function controlInputsCentroCustoSintetico()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Centro Custo
    // Desabilitar: Situação Documento, Tipo Documento, Conta, Pessoa, Gênero
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#filial').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#conta').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
    $('#genero').prop('disabled', true);
}

function controlInputsDREFinanceiro()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Centro Custo, Gênero
    // Desabilitar: Situação Documento, Tipo Documento, Conta, Pessoa
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#filial').prop('disabled', false);
    $('#genero').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#conta').prop('disabled', true);
    $('#pessoa').prop('disabled', true);
}

function controlInputsRelFinanceiroDataEntrega()
{
    // Habilitar: Período, Referência, Tipo Lançamento, Situação Lançamento, Pessoa
    // Desabilitar: Situação Documento, Tipo Documento, Conta, Centro Custo, Gênero
    
    $('#data_consulta').prop('disabled', false);
    $('#referencia').prop('disabled', false);
    $('#tipolanc').prop('disabled', false);
    $('#sitlanc').prop('disabled', false);
    $('#pessoa').prop('disabled', false);
    
    $('#sitdocto').prop('disabled', true);
    $('#tipoDocto').prop('disabled', true);
    $('#conta').prop('disabled', true);
    $('#filial').prop('disabled', true);
    $('#genero').prop('disabled', true);
}

function Cancelar() {
    limparCampos();
    $('#modalParametros').modal('hide');
}

function limparCampos() {
    // Resetar DateRangePicker para o período padrão (primeiro e último dia do mês)
    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        
        // Atualizar o campo visual
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
        
        // Atualizar os campos hidden
        $('#dataIni').val(dataIni);
        $('#dataFim').val(dataFim);
        
        // Resetar o DateRangePicker
        try {
            $('input[name="data_consulta"]').data('daterangepicker').setStartDate(moment(dataIni, 'DD/MM/YYYY'));
            $('input[name="data_consulta"]').data('daterangepicker').setEndDate(moment(dataFim, 'DD/MM/YYYY'));
        } catch (e) {
            //console.log('Erro ao resetar DateRangePicker:', e);
        }
    }

    // Limpar campos de pessoa e gênero - padrão do pedido
    if (document.getElementById("pessoa")) {
        document.getElementById("pessoa").value = '';
        $('#pessoa').select2('destroy').select2({
            placeholder: "Digite para buscar cliente/fornecedor",
            allowClear: true,
            width: "100%",
            minimumInputLength: 2,
            closeOnSelect: true,
            selectOnClose: false,
            dropdownParent: $('#modalParametros'),
            ajax: {
                url: 'index.php?mod=fin&form=rel_financeiro&submenu=buscar_clientes&opcao=blank',
                dataType: 'json',
                type: 'POST',
                delay: 300,
                data: function(params) {
                    return { termo: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.ID, text: item.DESCRICAO };
                        })
                    };
                },
                cache: true
            }
        });
    }
    
    if (document.getElementById("genero")) {
        document.getElementById("genero").value = '';
        $('#genero').select2('destroy').select2({
            placeholder: "Digite para buscar gênero",
            allowClear: true,
            width: "100%",
            minimumInputLength: 2,
            closeOnSelect: true,
            selectOnClose: false,
            dropdownParent: $('#modalParametros'),
            ajax: {
                url: 'index.php?mod=fin&form=rel_financeiro&submenu=buscar_genero&opcao=blank',
                dataType: 'json',
                type: 'POST',
                delay: 300,
                data: function(params) {
                    return { termo: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.ID, text: item.DESCRICAO };
                        })
                    };
                },
                cache: true
            }
        });
    }

    // Limpar selects usando o padrão do pedido
    const namesSelect = ["referencia", "tipolanc", "sitlanc", "sitdocto", "tipoDocto", "conta", "filial"];
    
    namesSelect.forEach(id => {
        const selectElement = document.getElementById(id);
    
        if (selectElement) {
            // Desmarcar todas as opções
            Array.from(selectElement.options).forEach(option => {
                option.selected = false;
            });

            // Se for Select2, destruir e recriar
            if ($(selectElement).data('select2')) {
                $(selectElement).select2('destroy').select2({
                    placeholder: "Selecione...",
                    allowClear: true,
                    width: "100%",
                    dropdownParent: $('#modalParametros')
                });
            }
        } else {
            console.error(`Elemento com ID "${id}" não encontrado.`);
        }
    });

    // Reaplicar o controle de campos baseado no relatório selecionado
    const report = document.getElementById("tipoRelatorio") ? document.getElementById("tipoRelatorio").value : null;
    if (report) {
        controlInputs(report);
    }
}

async function generateReport()
{
    let report = null;
    let params = {};

    // Verificar o tipo de relatório
    if(document.getElementById("tipoRelatorio")){
        report = document.getElementById("tipoRelatorio").value;
    } else {
        Swal.fire({
                title: "Atenção!",
                text: "Erro ao localizar o tipo de relatório, entre em contato com o suporte!",
                icon: "warning",
                confirmButtonText: "Cancelar"
        })

        return false;
    }

    // Montar parâmetros
    params = await mountParameters();

    // Verifica se os parâmetros são nulos ou vazios antes de prosseguir
    if (isEmpty(params)) {

        Swal.fire({
                title: "Atenção!",
                text: "Erro ao localizar os parâmetros para pesquisa, entre em contato com o suporte!",
                icon: "warning",
                confirmButtonText: "Cancelar"
        })

        return false;
    }

    // Criar formulário dinamicamente
    const form = document.createElement('form');
    form.method = 'POST';
    form.target = "_blank";
    form.action = "index.php?mod=fin&form=rel_financeiro&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;

    // Adicionar parâmetros ao formulário
    for (let key in params) {
        if (params.hasOwnProperty(key) && key && key.trim() !== '') {
            if (Array.isArray(params[key])) {
                // Para arrays, criar múltiplos inputs apenas se tiver valores válidos
                const valoresValidos = params[key].filter(value => value && value.toString().trim() !== '');
                if (valoresValidos.length > 0) {
                    valoresValidos.forEach(value => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key + '[]';
                        input.value = value.toString().trim();
                        form.appendChild(input);
                    });
                }
            } else if (params[key] && params[key].toString().trim() !== '') {
                // Para valores simples, apenas se não estiver vazio
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = params[key].toString().trim();
                form.appendChild(input);
            }
        }
    }

    // Adicionar formulário ao DOM e submeter
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}

function mountParameters()
{
    return new Promise((resolve) => {
        let params = {};
        let form = document.getElementById("form_report");

        // Período - tratamento especial para daterangepicker
        if (document.getElementById("data_consulta")) {
            const dataConsulta = document.getElementById("data_consulta").value;
            if (dataConsulta && dataConsulta.trim() !== '') {
                const dates = dataConsulta.split(' - ');
                if (dates.length === 2 && dates[0].trim() && dates[1].trim()) {
                    params.dataIni = dates[0].trim();
                    params.dataFim = dates[1].trim();
                }
            }
        }

        // Percorrer todos os elementos do formulário
        Array.from(form.elements).forEach(element => {
            if (element.name && element.name.trim() !== '') {
                // Para selects múltiplos (Select2)
                if (element.tagName === 'SELECT' && element.multiple) {
                    const selectedOptions = Array.from(element.selectedOptions)
                        .map(option => option.value)
                        .filter(value => value && value.trim() !== '');
                    
                    if (selectedOptions.length > 0) {
                        params[element.name.replace('[]', '')] = selectedOptions;
                    }
                }
                // Para campos simples
                else if (element.value && element.value.trim() !== '' && 
                         element.name !== 'data_consulta' && 
                         element.name !== 'pessoa_nome' && 
                         element.name !== 'descgenero') {
                    params[element.name] = element.value.trim();
                }
            }
        });

        resolve(params);
    });
}

/**
 * Inicializa o select2 para pessoas (cliente/fornecedor)
 */
function initSelect2Pessoas() {
    $("#pessoa").select2({
        placeholder: "Digite para buscar cliente/fornecedor",
        allowClear: true,
        width: "100%",
        minimumInputLength: 2,
        closeOnSelect: true,
        selectOnClose: false,
        dropdownParent: $('#modalParametros'),
        ajax: {
            url: 'index.php?mod=fin&form=rel_financeiro&submenu=buscar_clientes&opcao=blank',
            dataType: 'json',
            type: 'POST',
            delay: 300,
            data: function(params) {
                return {
                    termo: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.ID,
                            text: item.DESCRICAO
                        };
                    })
                };
            },
            cache: true,
            error: function(xhr, status, error) {
                console.error('Erro AJAX pessoas:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        }
    });
}

/**
 * Inicializa o select2 para gênero
 */
function initSelect2Genero() {
    $("#genero").select2({
        placeholder: "Digite para buscar gênero",
        allowClear: true,
        width: "100%",
        minimumInputLength: 2,
        closeOnSelect: true,
        selectOnClose: false,
        dropdownParent: $('#modalParametros'),
        ajax: {
            url: 'index.php?mod=fin&form=rel_financeiro&submenu=buscar_genero&opcao=blank',
            dataType: 'json',
            type: 'POST',
            delay: 300,
            data: function(params) {
                return {
                    termo: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.ID,
                            text: item.DESCRICAO
                        };
                    })
                };
            },
            cache: true,
            error: function(xhr, status, error) {
                console.error('Erro AJAX gênero:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        }
    });
}

function mostrarSucesso(mensagem) {
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: mensagem,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(mensagem);
    }
}

function mostrarErro(mensagem) {
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: mensagem
        });
    } else {
        alert('Erro: ' + mensagem);
    }
}

function formatarMoeda(valor) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(valor);
}

function formatarNumero(numero, casasDecimais) {
    casasDecimais = casasDecimais || 2;
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: casasDecimais,
        maximumFractionDigits: casasDecimais
    }).format(numero);
}

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=950,height=900,scrollbars=yes');
} 
