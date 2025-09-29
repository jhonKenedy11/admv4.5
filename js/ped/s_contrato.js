function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'contrato';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitLetra() {
    
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|" + "|||||" + f.numAtendimento.value;
    f.submit();
} // fim submitLetra

function submitMesAtual() {
    f = document.lancamento;
     f.mod.value = 'ped';
    f.form.value = 'contrato';
    f.submenu.value = 'MesAtual';
    f.submit();
} // fim submit

function abrirMedicao(id) {
    let pag = document.lancamento.action + '?mod=cat&id_pedido=' + id + '&form=rel_atendimento&submenu=relatorio_medicao&tipoRelatorio=relatorio_medicao&opcao=imprimir';
    window.open(pag, '_blank');
} // fim submit

function submitTodosPedidos(submenu, opcao) {
     
    f = document.lancamento;
    f.opcao.value = 'todos';
    f.mod.value = 'ped';
    f.form.value = 'contrato';
    f.submenu.value = 'btnAtalho';
    f.submit();
} // fim submit

function submitTodosPedidosDia(submenu, opcao) {
    f = document.lancamento;
    f.opcao.value = 'dia';
    f.mod.value = 'ped';
    f.form.value = 'contrato';
    f.submenu.value = 'btnAtalho';
    f.submit();
} // fim submit

function submitTodosPedidosMes(submenu, opcao) {
    f = document.lancamento;
    f.opcao.value = 'mes';
    f.mod.value = 'ped';
    f.form.value = 'contrato';
    f.submenu.value = 'btnAtalho';
    f.submit();
} // fim submit


function aplicarMascaraMoney() {
    $('.money').maskMoney({
        thousands: '.',
        decimal: ',',
        allowZero: true,
        precision: 2,       // Define 2 casas decimais
        affixesStay: false, // Não permite prefixos/sufixos
        // Formata o valor inicial automaticamente
        formatOnBlur: true,
        allowNegative: false
    }).maskMoney('mask'); // Esta linha força a formatação imediata
}

function abrir(pag)
{
    screenWidth = 750;
    screenHeight = 650;

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function abrirOs(pag){
    window.open(pag, 'toolbar=no,location=yes,menubar=no,scrollbars=yes');
}

async function controlFunctionModal(id) {
    
    try { 

        document.getElementById('id_os').value = id;

        const acompanhamento_os = await abrirModalAcompanhamentoOS(id);

        if (acompanhamento_os && acompanhamento_os.length > 0) {
            abrirOrdensCadastradas(id); // Chama a função desejada quando houver resultados
        }

    } catch (error) {
        console.error("Error in controlFunctionModal:", error);
    }
}


function abrirOrdensCadastradas(id) {
    
    var dados = { 'id_pedido': id };
   
   $.ajax({
       type: "POST",
       url: document.URL + "?mod=ped&form=contrato&submenu=OrdemServicosCadastradas&opcao=blank",
       data: dados,
       dataType: "json",
       success: [returnOsCadastradas],
       error: function (e) {
           alert('Erro ao processar: ' + e.responseText);
       }

   });
}
function returnOsCadastradas(response) { 

    // urlBase esta sendo alimentado no contrato.tpl
   if (response !== null) {

        var trs = $("#modalResultOsCadastradas tr").length;

        if (trs != 0) {
           $("#modalResultOsCadastradas tr").remove();
        }

        let htmlTabela = [];

        for (let prop of Object.keys(response)) {
           htmlTabela +=
               `<tr>
                 <td>`+ response[prop]['ID'] + `</td>
                 <td>`+ response[prop]['DATA_INICIO_SERVICO'] + `</td>
                 <td>`+ response[prop]['DATA_FINALIZA_SERVICO'] + `</td>
                 <td>`+ (response[prop]['EQUIPE'] || 'SEM EQUIPE') + `</td>
                 <td><type="button" id="btnEditOsModal" class="btn btn-primary" onclick="javascript:abrirOs('`+urlBase+`/index.php?mod=cat&form=atendimento_new&submenu=alterar&id=` + response[prop]['ID'] + `')"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
               </tr>`
        }

        $("#modalResultOsCadastradas").append(htmlTabela);

        $("#modalAcompanhamentoOS").modal('show');

    } else {
        // Limpa a tabela existente
        $("#modalResultOsCadastradas tr").remove();
        
        // Adiciona a mensagem de nenhuma ordem encontrada
        $("#modalResultOsCadastradas").append(`
            <tr>
                <td colspan="5">
                    <div class="alert alert-warning text-center" style="color: #333; font-size: 14px;" role="alert">
                        Nenhuma ordem de serviço localizada para esse contrato/pedido.
                    </div>
                </td>
            </tr>
        `);
        
        $("#modalAcompanhamentoOS").modal('show');
        return false;
    }
}


function abrirModalAcompanhamentoOS(id) {

    return new Promise((resolve, reject) => {

        $('#id_pedido').text(id);

        var dados = { 'id_pedido': id };
        
        $.ajax({
            type: "POST",
            url: document.URL + "?mod=ped&form=contrato&submenu=mostraOsAjax&opcao=blank",
            data: dados,
            dataType: "json",
            success: (response) => {

                const result = returnServicos(response);

                window.dataInicioOriginal = $('#modalAcompanhamentoOS [name="data_inicio"]').val();
                window.prazoEntregaOriginal = $('#modalAcompanhamentoOS [name="prazo_entrega"]').val();

                resolve(result); // Resolve a Promise com o resultado

            },
            error: function (e) {
                alert('Erro ao processar: ' + e.responseText);
                reject(e); // Rejeita a Promise em caso de erro
            }
        });
    });
}


function returnServicos(response) {
    
    // se nao existir registro de servico
    if(response == null){
        swal.fire({
            title: "Atenção!",
            text: "Nenhum servico localizado para esse pedido!",
            icon: "error"
        });
        return
    }

    

    
    // se existir o erro no backend ja apresenta na tela
    if(response.error){
        swal.fire({
            title: "Atenção!",
            text: response.error,
            icon: "error"
        });
        return
    }

    if (response !== null) {

        var nome_cliente = response[0].NOME;

        document.getElementById('nome_cliente_input').value = nome_cliente;

        $("#modalResultServicos tr").remove();

        let htmlTabela = [];

        if (response.length > 0) {

            for (let servico of response) {
                // Formata os valores para terem 2 casas decimais
                const qtd_contratada = parseFloat(servico.QUANTIDADE).toFixed(2);
                let qtd_executada_os = parseFloat(servico.QUANTIDADE_EXECUTADA_OS).toFixed(2);
                let qtd_saldo = parseFloat(servico.SALDO).toFixed(2);

                // Verifica se o percentual executado é 100% ou mais, e define a quantidade a executar como 0
                if (parseFloat(servico.PERCENTUAL_EXECUTADO) >= 100) {
                    qtd_saldo = '0.00'; 
                }

                htmlTabela += `
                    <tr id="servico_${servico.ID}">
                        <td><input type="checkbox" style="transform: scale(1.2);" onchange="selecionarLinha(this, ${servico.ID})" tabindex="0"></td>
                        <td class="col-md-7"><input type="text" class="form-control tdModalListServicos" readonly value="${servico.DESCSERVICO}" tabindex="-1"></td>
                        <td class="col-md-1"><input type="text" class="form-control tdModalListServicos money" id="qtd_contratada_${servico.ID}" readonly value="${qtd_contratada}" tabindex="-1"></td>
                        <td class="col-md-1"><input type="text" class="form-control tdModalListServicos" readonly value="${servico.PERCENTUAL_EXECUTADO} %" tabindex="-1"></td>
                        <td class="col-md-1"><input type="text" class="form-control tdModalListServicos money" id="qtd_executada_${servico.ID}" readonly value="${qtd_executada_os}" tabindex="-1"></td>
                        <td class="col-md-2"><input style="font-weight: bold;" type="text" class="form-control tdModalListServicos money" id="qtdAExecutar_${servico.ID}" onchange="validarQuantidade(this, ${servico.ID})" value="${qtd_saldo}" tabindex="0"></td>
                    </tr>`;
            }
        }

        $("#modalResultServicos").append(htmlTabela);

        aplicarMascaraMoney();

        $("#modalAcompanhamentoOS").modal('show');

        $('#os_contrato').tab('show');
        
    } else {
        
        swal.fire({ 
            text: 'Não foi localizado nenhum serviço!',
            title: 'Atenção!',
            dangerMode: true 
        });
    }

    return response;
}

function validarQuantidade(input, id_servico) {
    
    const qtd_a_executar = parseFloat(input.value.replace('.', '').replace(',', '.')) || 0;
    const qtd_executada_os = parseFloat($(`#qtd_executada_${id_servico}`).val().replace('.', '').replace(',', '.')) || 0;
    const qtd_contratada = parseFloat($(`#qtd_contratada_${id_servico}`).val().replace('.', '').replace(',', '.')) || 0;

    const total = qtd_a_executar + qtd_executada_os;

    if (total > qtd_contratada) {  
        // VOlta o valor para o valor original
        const saldo_disponivel = qtd_contratada - qtd_executada_os;
        input.value = saldo_disponivel.toFixed(2).replace('.', ',');
        
        Swal.fire({ 
            text: 'A soma da quantidade a executar com a quantidade já executada não pode ser maior que a quantidade contratada!',
            title: 'Atenção!',
            icon: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: "#d33",
        });
    }
}

function gerarOs() {
    

    // Iterador de servicos selecionados
    let servicos_selecionados = [];
    let servico_100_executado = false;

    $('#modalResultServicos tr.selecionada').each(function () {
        const $linha = $(this); // Elemento <tr> atual

        const id_servico = $linha.attr('id').split('_')[1];
        const qtd_a_executar = $linha.find('#qtdAExecutar_'+id_servico).val();
        const percentual_executado = parseFloat($linha.find('td:nth-child(4) input').val().replace('%', '').trim());

        // Verifica se o percentual executado é 100%
        if (percentual_executado >= 100) {
            Swal.fire({
                text: `Serviço com OS já cadastrada, 100% executado e não pode gerar uma nova OS.`,
                title: 'Atenção!',
                icon: "warning",
                confirmButtonText: "Ok",
                confirmButtonColor: "#d33",
            });
            // Marca a variável para indicar que pelo menos um serviço foi 100% executado
            servico_100_executado = true;

            return false;
        }

        servicos_selecionados.push({
            id_servico: id_servico,
            qtd_a_executar: qtd_a_executar
        });
    });

    if (servico_100_executado) {
        return false;
    }


    //se nem um servico foi selecionado apresenta o erro
    if(servicos_selecionados.length == 0){
        Swal.fire({ 
            text: 'Selecione um ou mais servico!',
            title: 'Atencao!',
            icon: "warning",
            dangerMode: true
        });
        return false;
    }


    // validade se a data de inicio foi preenchida
    let data_inicio = $('#modalAcompanhamentoOS [name="data_inicio"]').val();
    if(data_inicio == "" || data_inicio == null){
        Swal.fire({ 
            text: 'Informe a data de inicio!',
            title: 'Atencao!',
            icon: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: "#d33",
        });
        return false;
    }

    // Validade se a data de entrega foi preenchida
    let prazo_entrega = $('#modalAcompanhamentoOS [name="prazo_entrega"]').val();
    if(prazo_entrega == "" || prazo_entrega == null){
        Swal.fire({ 
            text: 'Informe o prazo de entrega!',
            title: 'Atencao!',
            icon: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: "#d33",
        });
        return false;
    }

    // Validação da equipe
    let equipe = $('#modalAcompanhamentoOS [name="equipe"]').val();
    let usuario_equipe = $('#modalAcompanhamentoOS [name="usuario_equipe"]').val();

    if ((equipe === null || equipe === '' || equipe == 0) && usuario_equipe && usuario_equipe.length > 0) {
        Swal.fire({
            text: 'Selecione a equipe!',
            title: 'Atenção!',
            icon: "warning",
            confirmButtonText: "Ok",
            confirmButtonColor: "#d33",
        });
        return false;
    }


    // Iterado de parametros da modal
    let gerencia_ordem_servico = [];

    gerencia_ordem_servico.push({

        data_inicio: data_inicio,
        prazo_entrega: prazo_entrega,
        id_pedido : $('#id_pedido').text(),
        equipe: $('#modalAcompanhamentoOS [name="equipe"]').val(),
        usuario_equipe: $('#modalAcompanhamentoOS [name="usuario_equipe"]').val(),
        obs_servico: $('#modalAcompanhamentoOS [name="obs_servico"]').val(),
        servicos_selecionados : servicos_selecionados

    });

    const jsonAjax = JSON.stringify(gerencia_ordem_servico);  
    
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=ped&form=contrato&submenu=gerarOs&opcao=blank",
        data: {gerencia_ordem_servico: jsonAjax},
        dataType: "json",
        success: function(response) {
            // Se OS for cadastrada corretamente
            if (response.success) { 

                limpaModal();

                $('#modalAcompanhamentoOS').modal('hide');

                Swal.fire({ 
                    width: "45em",
                    title: response.success,
                    icon: "success"
                });

            //caso de algum erro na insercao da OS
            } else {

                Swal.fire({ 
                    width: "60em",
                    text: 'Erro ao cadastrar O.S., entre em contato com o suporte!',
                    title: 'Atencao!',
                    icon: "error"
                });

            }
           
        },
        error: function(e) {
            alert('Erro ao processar: ' + e.responseText);
        }
    });
}


function selecionarLinha(checkbox, id) {
    if (checkbox.checked) {
        document.getElementById('servico_' + id).classList.add('selecionada');
    } else {
        document.getElementById('servico_' + id).classList.remove('selecionada');
    }
}


function limparCampos() {

    if (document.getElementById("numAtendimento")){
        document.getElementById("numAtendimento").value = '';
    }
    if (document.getElementById("pessoa")){
        document.getElementById("pessoa").value = '';
    }
    if (document.getElementById("nome")){
        document.getElementById("nome").value = '';
    }

    if (document.getElementById("dataConsulta")) {
        const hoje = new Date();
        const dataIni = `01/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        const dataFim = `${new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0).getDate()}/${String(hoje.getMonth() + 1).padStart(2, '0')}/${hoje.getFullYear()}`;
        document.getElementById("dataConsulta").value = `${dataIni} - ${dataFim}`;
    }
   
}

function limpaModal(){
    if (document.getElementsByName("obs_servico")){
        document.getElementsByName("obs_servico")[0].value = '';
    }

    // Limpa os campos Equipe e Usuários da Equipe
    $('#modalAcompanhamentoOS [name="equipe"]').val('').trigger('change');
    $('#modalAcompanhamentoOS [name="usuario_equipe"]').val('').trigger('change');
    $('#modalAcompanhamentoOS [name="obs_servico"]').val('');
}
