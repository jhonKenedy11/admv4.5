function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'apontamento_os_mobile';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitLetra() {
    
    f = document.lancamento;
    f.submenu.value = 'pesquisa';
    f.opcao.value= 'pesquisa';
    //concantenação necessaria para realizar a consulta com o parametro.
    f.situacaoSelecionada.value = "|" + $('#situacao').val();
    
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|" + f.numAtendimento.value;

    f.submit();
} // fim submitLetra

// funcao com validação de campos
// function submitConfirmar() {
//     const f = document.forms.lancamento;
//     const linhas = f.querySelectorAll('tr.even.pointer');
//     let erros = [];

//     // Validar cada linha antes de prosseguir
//     linhas.forEach(linha => {
//         const inputExec = linha.querySelector('[name="quantidade_executada"]');
//         const qtdExec = parseFloat(linha.querySelector('[name="qtd_exec"]').value) || 0;
        
//         // Converter valor mascarado para número usando o plugin
//         const executada = $(inputExec).maskMoney('unmasked')[0] || 0;
        
//         // Pegar descrição do serviço da linha anterior
//         const descricao = linha.previousElementSibling.querySelector('td').textContent.trim();

//         // Validar valores
//         if (executada > qtdExec) {
//             erros.push(`
//                 <div style="margin-bottom: 5px;">
//                     <strong>Quantidade informada:</strong> ${executada}<br>
//                     <strong>Quantidade disponível:</strong> ${qtdExec}
//                 </div>
//             `);
//             inputExec.classList.add('input-error');
//         } else {
//             inputExec.classList.remove('input-error');
//         }
//     });

//     if (erros.length > 0) {
//         Swal.fire({
//             title: '<span style="font-size: 12px">Valor inválido</span>',
//             html: `<div style="font-size: 10px; max-height: 130px; overflow-y: auto; padding: 5px;">
//                  ${erros.map(erro => `${erro}`).join('')}
//                </div>`,
//             icon: 'warning',
//             confirmButtonText: 'OK',
//             width: 200,
//             padding: '5px',
//             background: '#fffdf6',
//             grow: false
//         });
//         return false;
//     }

//     // Prosseguir com o envio (código original)
//     const dados = Array.from(linhas, linha => ({
//         id_servico: linha.querySelector('[name="id_servico"]').value,
//         quantidade_executada: $(linha.querySelector('[name="quantidade_executada"]')).maskMoney('unmasked')[0] || 0,
//         qtd_exec: linha.querySelector('[name="qtd_exec"]')?.value || '', // Verifica se existe
//     }));

//     if (!f.json_data) {
//         const input = document.createElement('input');
//         input.type = 'hidden';
//         input.name = 'json_data';
//         f.appendChild(input);
//     }
    
//     f.json_data.value = JSON.stringify(dados);
    
//     f.submenu.value = 'inclui';
//     f.mod.value = 'cat';
//     f.form.value = 'apontamento_os_mobile';
    
//     f.submit();
//     return true;
// }

function submitConfirmar() {
    const f = document.forms.lancamento;
    const linhas = f.querySelectorAll('tr.even.pointer');
    let erros = [];
    let temExcesso = false;

    // Validar cada linha antes de prosseguir
    linhas.forEach(linha => {
        const inputExec = linha.querySelector('[name="quantidade_executada"]');
        const qtdExec = parseFloat(linha.querySelector('[name="qtd_exec"]').value) || 0;
        
        // Converter valor mascarado para número usando o plugin
        const executada = $(inputExec).maskMoney('unmasked')[0] || 0;
        
        // Pegar descrição do serviço da linha anterior
        const descricao = linha.previousElementSibling.querySelector('td').textContent.trim();

        // Verificar se há excesso
        if (executada > qtdExec) {
            temExcesso = true;
            erros.push(`
                <div style="margin-bottom: 5px;">
                    <strong>Quantidade informada:</strong> ${executada}<br>
                    <strong>Quantidade disponível:</strong> ${qtdExec}
                </div>
            `);
            inputExec.classList.add('input-error');
        } else {
            inputExec.classList.remove('input-error');
        }
    });

    if (temExcesso) {
        return Swal.fire({
            title: '<span style="font-size: 12px">Atenção: Quantidade excedente</span>',
            html: `<div style="font-size: 10px; max-height: 130px; overflow-y: auto; padding: 5px;">
                <p>Você está informando quantidades maiores que as disponíveis:</p>
                ${erros.map(erro => `${erro}`).join('')}
                <p>Deseja prosseguir mesmo assim?</p>
               </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, prosseguir',
            cancelButtonText: 'Não, corrigir',
            width: 300,
            padding: '5px',
            background: '#fffdf6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Prosseguir com o envio
                enviarFormulario(f, linhas);
            }
            return result.isConfirmed;
        });
    } else {
        // Prosseguir com o envio diretamente se não houver excesso
        enviarFormulario(f, linhas);
        return true;
    }
}

function enviarFormulario(f, linhas) {
    // Coletar os dados para envio
    const dados = Array.from(linhas, linha => ({
        id_servico: linha.querySelector('[name="id_servico"]').value,
        quantidade_executada: $(linha.querySelector('[name="quantidade_executada"]')).maskMoney('unmasked')[0] || 0,
        qtd_exec: linha.querySelector('[name="qtd_exec"]')?.value || '',
    }));

    // Criar campo oculto para o JSON se não existir
    if (!f.json_data) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'json_data';
        f.appendChild(input);
    }
    
    f.json_data.value = JSON.stringify(dados);
    
    f.submenu.value = 'inclui';
    f.mod.value = 'cat';
    f.form.value = 'apontamento_os_mobile';
    
    f.submit();
}


function abrir(pag, form=null)
{
    if(form == 'apontamento_os_mobile'){
        if(document.lancamento.pessoa.value == ''){
            alert("Selecione o Cliente antes de fazer a pesquisa");
            return false;
        }
        

        screenWidth = screen.width;
        screenHeight = screen.height;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
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

}

function abrirFinalizacao (numAtendimento){
    f = document.lancamento;
    f.numAtendimento.value = numAtendimento;
    f.mod.value = 'cat';
    f.form.value = 'apontamento_os_mobile';
    f.submenu.value = 'cadastro';
    f.submit();
}

// mostra Cadastro Img
function submitCadastrarImagemOS(id) {

    window.open("index.php?mod=cat&form=atendimento_new&opcao=imprimir&submenu=cadastrarImagem&idOs=" + id,
        "toolbar=no,location=no,resizable=yes,menubar=yes,scrollbars=yes");

} // submitCadastrarImagem
