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
    if (isEmpty(params)) {

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

                    const selectedOptions = Array.from(element.selectedOptions).map(option => option.value);

                    params[element.name] = selectedOptions;

                    console.log(`${element.name}: ${selectedOptions}`);

                } else if (element.value) {

                    params[element.name] = element.value;
                    
                    console.log(`${element.name}: ${element.value}`);
                }
            }
        });

        resolve(params);
    });
}


function controlInputs(report)
{
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

    if(!$('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', true);

        $("#situacao.select2_multiple").select2({
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
}


function controlInputsReportItem()
{
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }
    if($('#buscaProduto').prop('disabled')){

        $('#buscaProduto').prop('disabled', false);
    }

    if(!$('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', true);

        $("#situacao.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
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

    if(!$('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', true);

        $("#situacao.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
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

    // select situacao
    if(!$('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', true);

        $("#situacao.select2_multiple").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
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
}

function controlInputsReportVendas()
{
    // habilitado Situacao, centro de custo, Motivo, vendedor, cond pag, cliente, produto
    // btn cliente
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select situacao
    if($('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', false);

        $(document).ready(function() {
            $("#situacao.select2_multiple").select2({
                placeholder: "Escolha a situacao do pedido",
                allowClear: true,
                width: "100%"
            });
        });
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

}

function controlInputsReportSintetica()
{

    // btn cliente
    if($('#buscaCliente').prop('disabled')){

        $('#buscaCliente').prop('disabled', false);
    }

    // select situacao
    if($('#situacao').prop('disabled')){

        $('#situacao').prop('disabled', false);

        $(document).ready(function() {
            $("#situacao.select2_multiple").select2({
                placeholder: "Escolha a situacao do pedido",
                allowClear: true,
                width: "100%"
            });
        });
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
function limparCampos() {

    const namesSelect = ["situacao", "centro_custo", "motivo", "vendedor", "condicao_pagamento", "tipo_entrega"];
   
    if (document.getElementById("cliente_nome")){
        document.getElementById("cliente_nome").value = '';
    }
    
    nomeRelatorio
    if (document.getElementById("cliente_id")){
        document.getElementById("cliente_id").value = '';
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

