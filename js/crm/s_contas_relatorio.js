async function generateReport() {
    let report = null;
    let params = {};

    // responsible for checking the type of report
    if(document.getElementById("report")){
        report = document.getElementById("report").value;
    } else {
        swal.fire({
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
        case "relatorio_aniversario":
            form.action = "index.php?mod=crm&form=rel_contas&opcao=imprimir&submenu=relatorio_aniversario&tipoRelatorio=" + report;
            break;

        case "relatorio_contas":
            form.action = "index.php?mod=crm&form=rel_contas&opcao=imprimir&submenu=relatorio_contas&tipoRelatorio=" + report;
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
                if (element.tagName === 'SELECT') {

                    const selectedOptions = Array.from(element.selectedOptions).map(option => option.value);

                    params[element.name] = selectedOptions;

                   
                } else if (element.value) {

                    params[element.name] = element.value;

                }
            }
        });

        resolve(params);
    });
}

function controlInputs(report) {
    
    switch (report) {

        case "relatorio_aniversario":            
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportAniversario();
            break;
        case "relatorio_contas":
            if(document.getElementById("report")){
                document.getElementById("report").value = report;
            }
            controlInputsReportContas();
            break;
    }
    
}


function controlInputsReportAniversario()
{
    if(!$('#pesNome').prop('disabled')) {
        $('#pesNome').prop('disabled', true);
    }
    if($('#data_consulta').prop('disabled')) {
        $('#data_consulta').prop('disabled', false);
    }

    if(!$('#pesCnpjCpf').prop('disabled')) {
        $('#pesCnpjCpf').prop('disabled', true);
    }

    if(!$('#idPessoa').prop('disabled')){

        $('#idPessoa').prop('disabled', true);

        $("#idPessoa.select2_single").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    if(!$('#idVendedor').prop('disabled')){

        $('#idVendedor').prop('disabled', true);

        $("#idVendedor.select2_single").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }   

}

function controlInputsReportContas()
{
    if(!$('#data_consulta').prop('disabled')) {
        $('#data_consulta').prop('disabled', true);
    }

    if($('#pesNome').prop('disabled')) {
        $('#pesNome').prop('disabled', false);
    }

    if($('#pesCnpjCpf').prop('disabled')) {
        $('#pesCnpjCpf').prop('disabled', false);
    }

    if($('#idPessoa').prop('disabled')){

        $('#idPessoa').prop('disabled', false);

        $("#idPessoa.select2_single").select2({
            placeholder: "Desabilitado para o relatório selecionado",
            allowClear: true,
            width: "100%"
        });
    }

    if($('#idVendedor').prop('disabled')){

        $('#idVendedor').prop('disabled', false);

        $("#idVendedor.select2_single").select2({
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

function limparCampos() {

    const namesSelect = ["idVendedor", "idAtividade", "idClasse", "idPessoa", "idFilial", "idEstado"];

    if (document.getElementById("data_consulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("data_consulta").value = `${dataIni} - ${dataFim}`;
    }

    document.getElementById("pesNome").value = '';
    document.getElementById("pesCnpjCpf").value = '';
    document.getElementById("pesCidade").value = '';

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

