async function generateReport(report) {
    let params = {};        
    // responsible for checking the type of report
    if(isEmpty(report)){
        swal.fire({
            title: "Atenção!",
            text: "Erro ao localizar o tipo de relatorio, entre em contato com o suporte!",
            icon: "warning",
            buttons: ["Cancelar"]
        })
        return false;
    }

    // Validação específica para relatório de medição - requer id_pedido
    if(report === "relatorio_medicao"){
        const idPedido = document.getElementById('id_pedido');
        if(!idPedido || !idPedido.value || idPedido.value.trim() === ''){
            swal.fire({
                title: "Atenção!",
                text: "O relatório de medição requer o preenchimento do campo Contrato/Pedido!",
                icon: "warning",
                confirmButtonText: "OK"
            })
            return false;
        }
    }

    form = document.getElementById('form_report');
    

    switch (report){
        case "relatorio_medicao":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio_medicao&tipoRelatorio=" + report;
            break;
        case "relatorio_servico":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
            break;
        case "relatorio_usuario":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
            break;
        case "relatorio_equipamento":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
            break;
        case "relatorio_periodo":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
            break;
        default:
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio&tipoRelatorio=" + report;
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

    form.submit();
}

// Função que verifica se o objeto está vazio ou nulo
function isEmpty(obj) {
    return obj === null || Object.keys(obj).length === 0;
}


function controlInputs(report) {

    $('.form-group-col').hide();

    if(document.getElementById("report")) {
        document.getElementById("report").value = report;
    }
       switch (report) {
        case "relatorio_medicao":
            controlInputRelatorioMedicao();
            break;
        case "relatorio_servico":
            controlInputRelatorioServico();
            break;
        case "relatorio_usuario":
            controlInputRelatorioUsuario();
            break;
        case "relatorio_equipamento":
            controlInputRelatorioEquipamento();
            break;
        case "relatorio_status":
            controlInputRelatorioStatus();
            break;
        case "relatorio_cliente":
            controlInputRelatorioCliente();
            break;
        case "relatorio_centro_custo":
            controlInputRelatorioCentroCusto();
            break;
        case "relatorio_periodo":
            controlInputRelatorioPeriodo();
            break;
        default:
            console.warn("Relatório não reconhecido:", report);
    }
    
}

// Medição
function controlInputRelatorioMedicao() {
    showFormFields(['pedido']);
}

// Serviço
function controlInputRelatorioServico() {
    showFormFields(['servico', 'periodo', 'centro-custo', 'os', 'pedido', 'equipamento', 'usuario', 'cliente', 'status']);
}

// Usuário 
function controlInputRelatorioUsuario() {
    showFormFields(['periodo', 'centro-custo', 'os', 'pedido', 'usuario']);
}

// Equipamento
function controlInputRelatorioEquipamento() {
    showFormFields(['equipamento', 'centro-custo', 'periodo', 'os', 'pedido', 'cliente']);
}

// Status
function controlInputRelatorioStatus() {
    showFormFields(['status', 'periodo', 'centro-custo', 'os', 'pedido', 'usuario', 'cliente']);
}

// Cliente
function controlInputRelatorioCliente() {
    showFormFields(['cliente', 'periodo', 'centro-custo', 'os', 'pedido', 'status', 'usuario']);
}

// Centro de Custo
function controlInputRelatorioCentroCusto() {
    showFormFields(['centro-custo', 'periodo', 'os', 'pedido', 'usuario', 'status', 'cliente']);
}

// Período
function controlInputRelatorioPeriodo() {
    showFormFields(['periodo', 'centro-custo', 'os', 'pedido', 'usuario', 'status', 'cliente', 'equipamento', 'servico', 'ordenacao']);
}

// Função auxiliar para mostrar campos específicos
function showFormFields(fieldIds) {
    fieldIds.forEach(function(id) {
        $('#' + id + '-group').show();
    });
}



function Cancelar() {
    limparCampos();
    $('#modalParametros').modal('hide');
}

function limparCampos() {

    const namesSelect = ["usuario", "equipamento", "id_status", "id_servico", "centro_custo", "ordenacao"];

    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
    }
    document.getElementById("cliente_nome").value = '';
    document.getElementById("cliente_id").value = '';
    document.getElementById("id_pedido").value = '';
    document.getElementById("num_os").value = '';
    
    // Reset ordenação para padrão
    if (document.getElementById("ordenacao")) {
        document.getElementById("ordenacao").value = "1";
    }

    namesSelect.forEach(id => {
        const selectElement = document.getElementById(id);
    
        if (selectElement) {
            
            Array.from(selectElement.options).forEach(option => {
                option.selected = false;
            });

            if ($(selectElement).data('select2-single')) {
                $(selectElement).val(null).trigger('change');
            }
        } else {
            console.error(`Elemento com ID "${id}" não encontrado.`);
        }
    });
}

function abrir(pag) {
    window.open(
        pag,
        "consulta",
        "toolbar=no,location=center,menubar=no,width=950,height=750,scrollbars=yes"
    );
}

