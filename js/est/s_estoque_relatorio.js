
function controlInputs(report)
{
    // Definir o tipo de relatório
    if(document.getElementById("tipoRelatorio")){
        document.getElementById("tipoRelatorio").value = report;
    }

    switch (report) {
        case "movimentacao":
            controlInputsMovimentacao();
            break;
        case "curva_abc":
            controlInputsCurvaABC();
            break;
        case "kardex_sintetico":
            controlInputsKardexSintetico();
            break;
        case "kardex_analitico":
            controlInputsKardexAnalitico();
            break;
        case "estoque_geral":
            controlInputsEstoqueGeral();
            break;
        case "compras":
            controlInputsCompras();
            break;
        case "compras_sugestoes":
            controlInputsComprasSugestoes();
            break;
        case "compras_estoque_minimo":
            controlInputsComprasEstoqueMinimo();
            break;
        case "tabela_precos":
            controlInputsTabelaPrecos();
            break;
        case "estoque_localizacao":
            controlInputsEstoqueLocalizacao();
            break;
        case "movimento_cliente":
            controlInputsMovimentoCliente();
            break;
        case "consulta_preco":
            controlInputsConsultaPreco();
            break;
        }
    }
    
function controlInputsMovimentacao()
{
    // Habilitar: Período, Grupo, Localização, Produto, Tipo de Movimento, Centro de Custo
    // Desabilitar: Cliente/Fornecedor, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos básicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', false);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', false);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsCurvaABC()
{
    // Habilitar: Período, Grupo, Produto, Centro de Custo, Tipo de Curva ABC
    // Desabilitar: Cliente/Fornecedor, Localização, Tipo de Movimento, Situação da NF, Ordenação
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idProduto').prop('disabled', false);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    $('#tipoCurvaABC').prop('disabled', false);
    $('#ordenacaoEstoque').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#idLocalizacao').prop('disabled', true);
    $('#tipoMovimento').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
}

function controlInputsKardexSintetico()
{
    // Habilitar: Período, Grupo, Produto, Centro de Custo
    // Desabilitar: Cliente/Fornecedor, Localização, Tipo de Movimento, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsKardexAnalitico()
{
    // Habilitar: Período, Grupo, Produto, Centro de Custo, Situação da NF
    // Desabilitar: Cliente/Fornecedor, Localização, Tipo de Movimento, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    $('#situacaoNF').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}



function controlInputsEstoqueGeral()
{
    // Habilitar: Grupo, Localização, Produto, Ordenação
    // Desabilitar: Cliente/Fornecedor, Período, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', false);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#centroCusto').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
}


function controlInputsComprasSugestoes()
{
    // Habilitar: Período, Grupo, Produto
    // Desabilitar: Cliente/Fornecedor, Localização, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#centroCusto').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
}

function controlInputsCompras()
{
    // Habilitar: Período, Cliente/Fornecedor, Grupo, Produto
    // Desabilitar: Localização, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#clienteFornecedor').prop('disabled', false);
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#centroCusto').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsComprasEstoqueMinimo()
{
    // Habilitar: Grupo, Produto
    // Desabilitar: Cliente/Fornecedor, Período, Localização, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', true);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#centroCusto').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsTabelaPrecos()
{
    // Habilitar: Cliente/Fornecedor, Grupo, Produto
    // Desabilitar: Período, Localização, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', true);
    
    // Desabilitar campos específicos
    $('#centroCusto').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsEstoqueLocalizacao()
{
    // Habilitar: Grupo, Localização, Produto, Ordenação
    // Desabilitar: Cliente/Fornecedor, Período, Tipo de Movimento, Centro de Custo, Situação da NF, Tipo de Curva ABC
    
    // Habilitar campos específicos
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', false);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
    
    // Desabilitar campos específicos
    $('#clienteFornecedor').prop('disabled', true);
    $('#centroCusto').prop('disabled', true);
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
}

function controlInputsMovimentoCliente()
{
    // Habilitar: Período, Cliente/Fornecedor, Grupo, Produto, Centro de Custo
    // Desabilitar: Localização, Tipo de Movimento, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#clienteFornecedor').prop('disabled', false);
    $('#idGrupo').prop('disabled', false);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function controlInputsConsultaPreco()
{
    // Habilitar: Período, Cliente/Fornecedor, Produto, Centro de Custo
    // Desabilitar: Grupo, Localização, Tipo de Movimento, Situação da NF, Tipo de Curva ABC, Ordenação
    
    // Habilitar campos específicos
    $('#clienteFornecedor').prop('disabled', false);
    $('#idGrupo').prop('disabled', true);
    $('#idLocalizacao').prop('disabled', true);
    $('#idProduto').prop('disabled', false);
    $('#tipoMovimento').prop('disabled', true);
    $('#data_consulta').prop('disabled', false);
    $('#centroCusto').prop('disabled', false);
    
    // Desabilitar campos específicos
    $('#situacaoNF').prop('disabled', true);
    $('#tipoCurvaABC').prop('disabled', true);
    $('#ordenacaoEstoque').prop('disabled', true);
}

function Cancelar() {
    limparCampos();
    $('#modalParametros').modal('hide');
}

function limparCampos() {
    // Limpar campos básicos
    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
    }

    // Verificar se deve limpar cliente/fornecedor baseado no relatório
    const report = document.getElementById("tipoRelatorio") ? document.getElementById("tipoRelatorio").value : null;
    const relatoriosComCliente = ['compras', 'tabela_precos', 'movimento_cliente', 'consulta_preco'];
    
    // Limpar selects básicos (exceto cliente/fornecedor se não for necessário)
    const basicSelects = ["idGrupo", "idLocalizacao", "tipoMovimento", "centroCusto", "situacaoNF", "tipoCurvaABC", "ordenacaoEstoque"];
    
    // Adicionar cliente/fornecedor apenas se o relatório o utiliza
    if (report && relatoriosComCliente.includes(report)) {
        basicSelects.push("clienteFornecedor");
    }
    
    basicSelects.forEach(id => {
        const selectElement = document.getElementById(id);
        if (selectElement) {
            // Limpar o select nativo
            selectElement.selectedIndex = 0;
            
            // Limpar o Select2 se existir
            try {
                const $element = $(selectElement);
                if ($element.length && $element.hasClass('select2-hidden-accessible')) {
                    $element.val(null).trigger('change');
                    $element.trigger('select2:clear');
                }
            } catch (e) {
                console.log('Erro ao limpar Select2 para ' + id + ':', e);
            }
        }
    });

    // Limpar produtos (Select2 múltiplo)
    if (document.getElementById("idProduto")) {
        try {
            // Limpar o select nativo
            document.getElementById("idProduto").selectedIndex = 0;
            
            // Limpar o Select2 completamente
            const $produto = $('#idProduto');
            if ($produto.length) {
                // Limpar o valor
                $produto.val(null);
                
                // Forçar atualização da interface
                $produto.trigger('change');
                $produto.trigger('select2:clear');
                
                // Remover tags visuais se existirem
                $produto.siblings('.select2-container').find('.select2-selection__choice').remove();
                
                // Resetar o placeholder
                $produto.trigger('select2:open');
                $produto.trigger('select2:close');
            }
        } catch (e) {
            console.log('Erro ao limpar Select2 produtos:', e);
            // Fallback: limpar diretamente o elemento
            const produtoElement = document.getElementById("idProduto");
            if (produtoElement) {
                produtoElement.selectedIndex = 0;
            }
        }
    }

    // Reaplicar o controle de campos baseado no relatório selecionado
    if (report) {
        controlInputs(report);
    }
}

async function generateReport()
{
    let report = null;
    let params = {};

    // responsible for checking the type of report
    if(document.getElementById("tipoRelatorio")){
        report = document.getElementById("tipoRelatorio").value;
    } else {
        Swal.fire({
                title: "Atenção!",
                text: "Erro ao localizar o tipo de relatorio, entre em contato com o suporte!",
                icon: "warning",
                confirmButtonText: "Cancelar"
        })

        return false;
    }

    // mount parameters
    params = await mountParameters();

    // Verifica se os parâmetros são nulos ou vazios antes de prosseguir
    if (isEmpty(params)) {

        Swal.fire({
                title: "Atenção!",
                text: "Erro ao localizar os parametros para pesquisa, entre em contato com o suporte!",
                icon: "warning",
                confirmButtonText: "Cancelar"
        })

        return false;
    }

    // Criar formulário dinamicamente
    const form = document.createElement('form');
    form.method = 'POST';
    form.target = "_blank";
    form.action = "index.php?mod=est&form=rel_estoque&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;

    // Adicionar parâmetros ao formulário
    for (let key in params) {
        if (params.hasOwnProperty(key) && key && key.trim() !== '') {
            if (Array.isArray(params[key])) {
                // Para arrays (como produtos), criar múltiplos inputs apenas se tiver valores válidos
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
                // Tratamento especial para produtos (Select2 múltiplo)
                if (element.name === 'idProduto[]' || element.name === 'idProduto') {
                    const produtos = $('#idProduto').val();
                    if (produtos && Array.isArray(produtos) && produtos.length > 0) {
                        // Filtrar apenas valores válidos
                        const produtosValidos = produtos.filter(p => p && p.toString().trim() !== '');
                        if (produtosValidos.length > 0) {
                            params.idProduto = produtosValidos;
                        }
                    }
                }
                // Para outros selects múltiplos
                else if (element.tagName === 'SELECT' && element.multiple) {
                    const selectedOptions = Array.from(element.selectedOptions)
                        .map(option => option.value)
                        .filter(value => value && value.trim() !== '');
                    
                    if (selectedOptions.length > 0) {
                        params[element.name] = selectedOptions;
                    }
                }
                // Para campos simples
                else if (element.value && element.value.trim() !== '') {
                    params[element.name] = element.value.trim();
                }
            }
        });

        resolve(params);
    });
}

/**
 * Inicializa o select2 para produtos
 */
function initSelect2Produtos() {
    $("#idProduto").select2({
        placeholder: "Buscar produtos",
        allowClear: true,
        width: "100%",
        minimumInputLength: 3,
        tags: false, // Não permite criar tags personalizadas
        closeOnSelect: false,
        maximumSelectionLength: 20,
        multiple: true, // Garantir que seja múltiplo
        selectOnClose: false, // Não seleciona ao fechar
        dropdownParent: $('#modalParametros'), // Para o placeholder não cortar
        templateResult: function(data) {
            // Não mostrar itens vazios no dropdown
            if (!data.id || !data.text) return null;
            return data.text;
        },
        ajax: {
            url: 'index.php?mod=est&form=rel_estoque&submenu=buscar_produtos&opcao=blank',
            dataType: 'json',
            type: 'POST',
            delay: 300,
            data: function(params) {
                return {
                    descProduto: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.ID,
                            text: item.DESCRICAO
                        };
                    }).filter(function(item) {
                        // Filtrar itens vazios
                        return item.id && item.text && item.id.toString().trim() !== '' && item.text.trim() !== '';
                    })
                };
            },
            cache: true,
            error: function(xhr, status, error) {
                console.error('Erro AJAX:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        }
    });
}

/**
 * Inicializa o select2 para clientes
 */
function initSelect2Clientes() {
    $("#clienteFornecedor").select2({
        placeholder: "Digite para buscar clientes",
        allowClear: true,
        width: "100%",
        minimumInputLength: 1,
        closeOnSelect: true,
        selectOnClose: false,
        dropdownParent: $('#modalParametros'),
        ajax: {
            url: 'index.php?mod=est&form=rel_estoque&submenu=buscar_clientes&opcao=blank',
            dataType: 'json',
            type: 'POST',
            delay: 300,
            data: function(params) {
                return {
                    descCliente: params.term
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
                console.error('Erro AJAX clientes:', error);
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