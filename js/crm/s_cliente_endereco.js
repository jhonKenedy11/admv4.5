function fechaPesqEndEntrega(id, titulo_endereco) {
    debugger;
    f = window.opener.document.lancamento;

    f.cliente_endereco_entrega.value = id;
    f.titulo_endereco.value = titulo_endereco;

    window.close();
}


// desenha Cadastro
function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.opcao.value = 'imprimir';
    f.form.value = 'cliente_endereco';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar() {
    debugger
    f = document.lancamento;
    f.opcao.value = 'blank';
    f.submenu.value = 'inserir';

    var form = $("form[name=lancamento]");
    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        // beforeSend: function (xhr) {
        //     xhr.setRequestHeader("Ajax-Request-Enviar-Email", "true");
        // },
        success: function (response) {
            debugger;
            if(response === 'true'){
                swal({ text: 'Endereço cadastrado!', title: 'Sucesso!', icon: 'success'});

                setTimeout(function () {
                    f.opcao.value = 'imprimir';
                    f.btnReturn.click();
                }, 2000)
            }else{
                swal({ text: 'Endereço cadastrado!', title: 'Erro!', dangerMode: true, icon:'danger'});
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Erro na requisição: " + textStatus + ", " + errorThrown);
        }
    });
    return false;
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro() {
    debugger
    f = document.lancamento;
    f.mod.value = 'crm';
    f.opcao.value = 'imprimir';
    f.form.value = 'cliente_endereco';
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro



function submitExcluir(cliente_endereco) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'crm';
        f.form.value = 'cliente_endereco';
        f.submenu.value = 'exclui';
        f.id.value = cliente_endereco;
        f.submit();
    }
} // submitExcluir

//logica para consultar cep da modal address e preencher campos - jhon Kenedy
async function pesquisarEndereco(cep) {
    debugger
    try {
        const cepSemMascara = cep.replace(/\D/g, '');
        const validacep = /^[0-9]{8}$/;

        if (!validacep.test(cepSemMascara)) {
            throw new Error('Formato de CEP inválido.');
        }

        const response = await fetch(`//viacep.com.br/ws/${cepSemMascara}/json/`);
        const data = await response.json();

        if (data.erro) {
            throw new Error('CEP não encontrado.');
        }

        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}

function preencherFormulario(endereco) {
    debugger
    document.querySelector('#address_endereco').value = limparTexto(endereco.logradouro);
    document.querySelector('#address_cidade').value = limparTexto(endereco.localidade);
    document.querySelector('#address_estado').value = limparTexto(endereco.uf);
    document.querySelector('#address_bairro').value = limparTexto(endereco.bairro);
    document.querySelector('#address_cep').defaultValue = endereco.cep;
    document.querySelector('#address_numero').value = '';
    document.querySelector('#address_complemento').value = '';
    document.querySelector('#address_numero').focus();

}

async function pesquisarEnderecoECarregarFormulario(cep) {
    debugger
    try {
        const endereco = await pesquisarEndereco(cep);
        preencherFormulario(endereco);
    } catch (error) {
        limparFormularioCep();
        alert(error.message);
    }
}

function limparFormularioCep() {
    debugger
    document.querySelector('#address_cep').value = "";
    document.querySelector('#address_numero').value = "";
    document.querySelector('#address_cidade').value = "";
    document.querySelector('#address_estado').value = "";
}

function limparTexto(texto) {
    let textoLimpo = texto.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^\w\s]/gi, '')
        .replace(/\s+/g, ' ')
        .trim();
    return textoLimpo.toUpperCase();
}
//FIM consulta cep modal Address

