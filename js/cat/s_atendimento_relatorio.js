async function generateReport(report) {
    let params = {};
    debugger
        
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


    form = document.getElementById('form_report');
    

    switch (report){
        // case "relatorio_medicao":
        //     form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio_medicao&tipoRelatorio=" + report;
        //     break;
        case "relatorio_servico":
        form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio_servico&tipoRelatorio=" + report;
        break;
        case "relatorio_usuario":
            form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio_usuario&tipoRelatorio=" + report;
            break;
        case "relatorio_equipamento":
        form.action = "index.php?mod=cat&form=rel_atendimento&opcao=imprimir&submenu=relatorio_equipamento&tipoRelatorio=" + report;
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
        // case "relatorio_medicao":
        //     controlInputRelatorioMedicao();
        //     break;
        case "relatorio_servico":
            controlInputRelatorioServico();
            break;
        case "relatorio_usuario":
            controlInputRelatorioUsuario();
            break;
        case "relatorio_equipamento":
            controlInputRelatorioEquipamento();
            break;
        default:
            console.warn("Relatório não reconhecido:", report);
    }
    
}

// //medicao
// function controlInputRelatorioMedicao() {
//     showFormFields(['periodo', 'centro-custo', 'os', 'pedido', 'usuario', 'status','cliente']);
// }

// Serviço
function controlInputRelatorioServico() {
    showFormFields(['servico', 'periodo', 'centro-custo', 'os', 'pedido', 'equipamento', 'usuario', 'cliente', 'status']);
}

//  Usuário 
function controlInputRelatorioUsuario() {
    showFormFields(['periodo', 'centro-custo', 'os', 'pedido', 'usuario']);
}

// Equipamento
function controlInputRelatorioEquipamento() {
    showFormFields(['equipamento', 'centro-custo', 'periodo', 'os', 'pedido', 'cliente']);
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

    const namesSelect = ["usuario", "equipamento", "id_status", "id_servico", "centro_custo"];

    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
    }
    document.getElementById("cliente_nome").value = '';
    document.getElementById("cliente_id").value = '';
    document.getElementById("num_pedido").value = '';
    document.getElementById("num_os").value = '';
    

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

