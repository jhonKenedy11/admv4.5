async function generateReport()
{
    let report = null;
    let params = {};

    // responsible for checking the type of report
    if(document.getElementById("report")){
        report = document.getElementById("report").value;
    } else {
        swal({
            title: "Atenção!",
            text: "Erro ao localizar o tipo de relatorio, entre em contato com o suporte!",
            icon: "warning",
            buttons: ["Cancelar"]
        })

        return false;
    }

    // mount parameters
    params = await mountParameters();

    // Verifica se os parâmetros são nulos ou vazios antes de prosseguir
    if (isEmpty(params) && report !== 'relatorioCompraEncomenda') {

        swal({
            title: "Atenção!",
            text: "Erro ao localizar os parametros para pesquisa, entre em contato com o suporte!",
            icon: "warning",
            buttons: ["Cancelar"]
        })

        return false;
    }

    // Criar formulário dinamicamente
    const form = document.createElement('form');
    form.method = 'POST';
    form.target = "_blank";

    switch (report){
        case "relatorioBonus":
            form.action = "index.php?mod=ped&form=rel_bonus&opcao=imprimir&submenu=relatorioBonus&tipoRelatorio=" + report;
            break;

        case "relatorioVendas":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioVendas&tipoRelatorio=" + report;
            break;

        case "relatorioDetalhado":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioDetalhado&tipoRelatorio=" + report;
            break;

        case "relatorioItem":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioItem&tipoRelatorio=" + report;
            break;

        case "relatorioItemEntrega":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioItemEntrega&tipoRelatorio=" + report;
            break;
            
        case "relatorioMotivo":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioMotivo&tipoRelatorio=" + report;
            break;

        case "relatorioFaturaGeral":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioFaturaGeral&tipoRelatorio=" + report;
            break;

        case "relatorioFaturaGeralA":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioFaturaGeralA&tipoRelatorio=" + report;
            break;
        
        case "relatorioVendedor":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioVendedor&tipoRelatorio=" + report;
            break;
        case "relatorioSemana":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioSemana&tipoRelatorio=" + report;
            break;
        case "relatorioMes":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioMes&tipoRelatorio=" + report;
            break;
        case "relatorioFaturaSintetico":
            form.action = "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=relatorioFaturaSintetico&tipoRelatorio=" + report;
            break;
        case "relatorioFaturaAnalitico":
            form.action = "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=relatorioFaturaAnalitico&tipoRelatorio=" + report;
            break;
        case "relatorioCondPagamento":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioCondPagamento&tipoRelatorio=" + report;
            break;
        case "relatorioEntrega":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioEntrega&tipoRelatorio=" + report;
            break;    
        case "relatorioPedNaoEntregue":
            form.action = "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioPedNaoEntregue&tipoRelatorio=" + report;
            break; 
        case "relatorioEstoqueDisponivelVenda":
            form.action = "index.php?mod=ped&form=rel_estoque_disponivel_venda&opcao=imprimir&submenu=relatorioEstoqueDisponivelVenda&tipoRelatorio=" + report;
            break;
        case "relatorioCompraEncomenda":
            form.action = "index.php?mod=ped&form=rel_compra_encomenda&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
            break;
        } 

    // Adicionar os parâmetros como inputs ocultos
    for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = params[key];
            form.appendChild(input);
        }
    }

    // Adicionar o formulário ao DOM, enviá-lo e removê-lo
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Função que verifica se o objeto está vazio ou nulo
function isEmpty(obj) {
    return obj === null || Object.keys(obj).length === 0;
}


function mountParameters()
{
    return new Promise((resolve, reject) => {
        

        let params = {};
        let form = document.getElementById("form_report");

        Array.from(form.elements).forEach(element => {
            if (element.name) {
                if (element.tagName === 'SELECT' && element.multiple) {

                    const selectedOptions = Array.from(element.selectedOptions)
                        .map(option => option.value)
                        .filter(value => value !== '' && value !== 'Selecione a Obra' && value !== 'Selecione um cliente primeiro');

                    params[element.name] = selectedOptions;

                   

                } else if (element.value) {

                    params[element.name] = element.value;
                    
                   
                }
            }
        });

        resolve(params);
    });
}


function controlInputs(report)
{
    $('#grupo_container').hide();
    $('#idGrupo').prop('disabled', true);
    $('#periodo_container').show();
    $('#cliente_container').show();
    $('#situacao_container').show();
    $('#centro_custo_container').show();
    $('#motivo-venda-container').show();
    $('#vendedor_container').show();
    $('#condicao_pagamento_container').show();
    $('#tipo_entrega_container').show();
    $('#obra_container').show();

    switch (report) {
        case "relatorioBonus":            
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportBonus();
            break;
        case "relatorioVendas":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
            break;
        case "relatorioDetalhado":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
        break;
        case "relatorioItem":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportItem();
        break;
        
        case "relatorioItemEntrega":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportItemEntrega();
        break;

        case "relatorioMotivo":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportMotivo();
        break;
        
        case "relatorioFaturaGeral":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportBonus();
        break;
        case "relatorioFaturaGeralA":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportBonus();
        break;
        case "relatorioVendedor":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportBonus();
        break;
        
        case "relatorioSemana":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
        break;
        
        case "relatorioMes":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
        break;
        case "relatorioFaturaSintetico":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
        break;
        case "relatorioFaturaAnalitico":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportVendas();
        break;
        case "relatorioCondPagamento":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportCondPagamento();
        break;
        case "relatorioEntrega":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportItemEntrega();
        break;
        case "relatorioPedNaoEntregue":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportItemEntrega();
        break;
        case "relatorioEstoqueDisponivelVenda":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportItemEntrega();
        break;
        case "relatorioCompraEncomenda":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportCompraEncomenda();
        break;
        
    }

    habilitarCampoSituacao();
}

function controlInputsReportCompraEncomenda()
{
    $('#grupo_container').show();
    $('#idGrupo').prop('disabled', false);

    $('#periodo_container').hide();
    $('#cliente_container').hide();
    $('#situacao_container').hide();
    $('#centro_custo_container').hide();
    $('#motivo-venda-container').hide();
    $('#vendedor_container').hide();
    $('#condicao_pagamento_container').hide();
    $('#tipo_entrega_container').hide();
    $('#obra_container').hide();

    $('#buscaCliente').prop('disabled', true);
    $('#buscaProduto').prop('disabled', false);
    $('#desc_produto').show();
}

function habilitarCampoSituacao()
{
    if($('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', false);

        $("#situacao.select2_multiple").select2({
            placeholder: "Escolha a situacao do pedido",
            allowClear: true,
            width: "100%"
        });
    }
}

function controlInputsReportItemEntrega()
{
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', true);
    }
    if($('#buscaProduto').prop('disabled')){

        $('#buscaProduto').prop('disabled', true);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $("#motivo.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: false,
            width: "100%"
        });
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    } 

    // select condicao de pagamento
    if(!$('#condicao_pagamento').prop('disabled')){
        $('#condicao_pagamento').prop('disabled', true);

        $("#condicao_pagamento.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', false);
    }

    // select obra - desabilitado por padrão até selecionar cliente
    $('#obra').prop('disabled', true);
    $('#obra').html('');
    $('#obra').select2('destroy').select2({
        placeholder: "Selecione um cliente primeiro",
        allowClear: true,
        width: "100%",
        disabled: true
    });
}


function controlInputsReportItem()
{
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }
    if($('#buscaProduto').prop('disabled')){

        $('#buscaProduto').prop('disabled', false);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', true);
    } else {

        $('#centro_custo').prop('disabled', true);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $("#motivo.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: false,
            width: "100%"
        });
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', true);
    } else {

        $('#vendedor').prop('disabled', true);
    }

    // select condicao de pagamento
    if(!$('#condicao_pagamento').prop('disabled')){
        $('#condicao_pagamento').prop('disabled', true);

        $("#condicao_pagamento.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    // select obra - desabilitado por padrão até selecionar cliente
    $('#obra').prop('disabled', true);
    $('#obra').html('');
    $('#obra').select2('destroy').select2({
        placeholder: "Selecione um cliente primeiro",
        allowClear: true,
        width: "100%",
        disabled: true
    });
}



function controlInputsReportMotivo()
    //habilitado produto, cliente, centro de custo, motivo, vendedor
{
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', true);
    }
    if($('#buscaProduto').prop('disabled')){

        $('#buscaProduto').prop('disabled', true);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    } else {

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', false);

    }else {

        $('#motivo').prop('disabled', false);
        
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    } else {

        $('#vendedor').prop('disabled', false);
    }

    // select condicao de pagamento
    if(!$('#condicao_pagamento').prop('disabled')){
        $('#condicao_pagamento').prop('disabled', true);

        $("#condicao_pagamento.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: false,
            width: "100%"
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }
}


function controlInputsReportBonus()
{
    // habilitado Centro de Custo, Vendedor
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    } else {

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $("#motivo.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    } else {

        $('#vendedor').prop('disabled', false);
    }

/*     // select condicao de pagamento
    if(!$('#condicao_pagamento').prop('disabled')){
        $('#condicao_pagamento').prop('disabled', true);

        $("#condicao_pagamento.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    } */
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }
}

function controlInputsReportVendas()
{
    // habilitado Situacao, centro de custo, Motivo, vendedor, cond pag, cliente, produto
    // btn cliente
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if($('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', false);

        $(document).ready(function() {
            $("#motivo.select2_multiple").select2({
                placeholder: "Escolha o Motivo",
                allowClear: true,
                width: "100%"
            });
        });
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    }

    // select condicao de pagamento
    if($('#condicao_pagamento').prop('disabled')){

        $('#condicao_pagamento').prop('disabled', false);

        $(document).ready(function() {
            $("#condicao_pagamento.select2_multiple").select2({
                placeholder: "Escolha o vendedor",
                allowClear: true,
                width: "100%"
            });
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    // select obra
    if($('#obra').prop('disabled')){
        
        $('#obra').prop('disabled', false);
    } else {

        $('#obra').prop('disabled', false);
    }

}
function controlInputsReportCondPagamento()
{
    // habilitado Situacao, centro de custo, Motivo, vendedor, cond pag, cliente, produto
    // btn cliente
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $("#motivo.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }


    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if($('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $("#motivo.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
        
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    }

    // select condicao de pagamento
    if($('#condicao_pagamento').prop('disabled')){

        $('#condicao_pagamento').prop('disabled', false);

        $(document).ready(function() {
            $("#condicao_pagamento.select2_multiple").select2({
                placeholder: "Escolha o vendedor",
                allowClear: true,
                width: "100%"
            });
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    // select obra
    if($('#obra').prop('disabled')){
        
        $('#obra').prop('disabled', false);
    } else {

        $('#obra').prop('disabled', false);
    }

}

function controlInputsReportSintetica()
{

    // btn cliente
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select centro custo
    if($('#centro_custo').prop('disabled')){

        $('#centro_custo').prop('disabled', false);
    }

    // select motivo
    if(!$('#motivo').prop('disabled')){

        $('#motivo').prop('disabled', true);

        $(document).ready(function() {
            $("#motivo.select2_multiple").select2({
                placeholder: "Escolha o Motivo",
                allowClear: true,
                width: "100%"
            });
        });
    }

    //select vendedor
    if ($('#vendedor').prop('disabled')) {
        
        $('#vendedor').prop('disabled', false);
    }

    // select condicao de pagamento
    if($('#condicao_pagamento').prop('disabled')){

        $('#condicao_pagamento').prop('disabled', false);

        $(document).ready(function() {
            $("#condicao_pagamento.select2_multiple").select2({
                placeholder: "Escolha o vendedor",
                allowClear: true,
                width: "100%"
            });
        });
    }
    
    // select tipo entrega
    if(!$('#tipo_entrega').prop('disabled')){
        
        $('#tipo_entrega').prop('disabled', true);

        $("#tipo_entrega.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    // select obra
    if($('#obra').prop('disabled')){
        
        $('#obra').prop('disabled', false);
    } else {

        $('#obra').prop('disabled', false);
    }

}

function Cancelar() {
    limparCampos();
    $('#modalParametros').modal('hide');
}

function abrir(pag) {
    window.open(
        pag,
        "consulta",
        "toolbar=no,location=no,menubar=no,width=950,height=750,scrollbars=yes"
    );
}
// Função para carregar obras via AJAX baseado no cliente selecionado
function carregarObrasRelatorios(clienteId) {
    if (!clienteId || clienteId === '') {
        $('#obra').html('');
        $('#obra').prop('disabled', true);
        $('#obra').select2('enable', false);
        return;
    }
    
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_relatorios&opcao=blank",
        data: {
            cliente_id: clienteId,
            submenu: 'ajax_obra'
        },
        success: function(response) {
            // Controla visibilidade e carrega obras
            if (response.obras === null || response.obras.length === 0) {
                $('#obra').html('');
                $('#obra').prop('disabled', true);
                $('#obra').select2('enable', false);
            } else {
                $('#obra').html('');
                $.each(response.obras, function(index, obra) {
                    $('#obra').append('<option value="' + obra.ID + '">' + obra.PROJETO + '</option>');
                });
                $('#obra').prop('disabled', false);
                // Re-inicializa o Select2 para habilitar corretamente
                $('#obra').select2('destroy').select2({
                    placeholder: "Selecione a Obra",
                    allowClear: true,
                    width: "100%"
                });
            }
        },
                error: function() {
                    $('#obra').html('');
                    $('#obra').prop('disabled', true);
                    $('#obra').select2('enable', false);
        }
    });
}

function limparCampos() {

    const namesSelect = ["situacao", "centro_custo", "motivo", "vendedor", "condicao_pagamento", "tipo_entrega", "obra"];
   
    if (document.getElementById("cliente_nome")){
        document.getElementById("cliente_nome").value = '';
    }
    
    nomeRelatorio
    if (document.getElementById("cliente_id")){
        document.getElementById("cliente_id").value = '';
    }

    // Limpa e desabilita o campo de obras
    if (document.getElementById("obra")){
        document.getElementById("obra").innerHTML = '';
        document.getElementById("obra").disabled = true;
        // Re-inicializa o Select2 desabilitado
        $('#obra').select2('destroy').select2({
            placeholder: "Selecione um cliente primeiro",
            allowClear: true,
            width: "100%",
            disabled: true
        });
    }

    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
    }
    
    if (document.getElementById("descProduto")){
        document.getElementById("descProduto").value = '';
    }
    if (document.getElementById("codProduto")){
        document.getElementById("codProduto").value = '';
    }
   
    namesSelect.forEach(id => {
        const selectElement = document.getElementById(id);
    
        if (selectElement) {
            
            Array.from(selectElement.options).forEach(option => {
                option.selected = false;
            });

            if ($(selectElement).data('select2')) {
                $(selectElement).val(null).trigger('change');
            }
        } else {
            console.error(`Elemento com ID "${id}" não encontrado.`);
        }
    });
   
}

function converteColunaNumeroBR(ws, colIndex, primeiraLinhaDados) {
    debugger;
    if (!ws || !ws['!ref']) return;

    var range = XLSX.utils.decode_range(ws['!ref']);
    var rInicio = (typeof primeiraLinhaDados === 'number')
        ? primeiraLinhaDados
        : range.s.r + 1;

    for (var r = rInicio; r <= range.e.r; r++) {
        var cell = ws[XLSX.utils.encode_cell({ r: r, c: colIndex })];
        if (!cell || cell.v == null) continue;

        var txt = (cell.v + '').trim();
        if (!txt) continue;

        txt = txt
            .replace(/^R\$\s*/i, '')
            .replace(/\./g, '')
            .replace(',', '.');

        var num = parseFloat(txt);
        if (!isNaN(num)) {
            cell.t = 'n';
            cell.v = num;
            cell.z = '#,##0.00';
        }
    }
}

