/**
 * Busca CEP com fallback automático entre múltiplas APIs
 * Ordem: ViaCEP -> BrasilAPI -> ApiCEP
 */
async function buscarCepComFallback(cep) {
    const apis = [
        {
            nome: 'ViaCEP',
            url: `https://viacep.com.br/ws/${cep}/json/`,
            transformar: (data) => data.erro ? null : data
        },
        {
            nome: 'BrasilAPI',
            url: `https://brasilapi.com.br/api/cep/v1/${cep}`,
            transformar: (data) => ({
                cep: data.cep,
                logradouro: data.street,
                complemento: '',
                bairro: data.neighborhood,
                localidade: data.city,
                uf: data.state,
                ibge: data.location?.coordinates?.latitude || '',
                gia: '', ddd: '', siafi: ''
            })
        },
        {
            nome: 'ApiCEP',
            url: `https://cdn.apicep.com/file/apicep/${cep}.json`,
            transformar: (data) => ({
                cep: data.code,
                logradouro: data.address,
                complemento: '',
                bairro: data.district,
                localidade: data.city,
                uf: data.state,
                ibge: data.cityIbge || '',
                gia: '', ddd: '', siafi: ''
            })
        }
    ];

    for (const api of apis) {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 4000);
            
            const response = await fetch(api.url, {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                continue;
            }
            
            const data = await response.json();
            const resultado = api.transformar(data);
            
            if (resultado) {
                return resultado;
            }
        } catch (error) {
            continue;
        }
    }
    
    return null;
}

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
// Atualizado com fallback automático de APIs
async function pesquisarEndereco(cep) {
    debugger
    try {
        const cepSemMascara = cep.replace(/\D/g, '');
        const validacep = /^[0-9]{8}$/;

        if (!validacep.test(cepSemMascara)) {
            throw new Error('Formato de CEP inválido.');
        }

        // Usa busca com fallback automático (ViaCEP -> BrasilAPI -> ApiCEP)
        const data = await buscarCepComFallback(cepSemMascara);

        if (!data) {
            throw new Error('CEP não encontrado em nenhuma API disponível.');
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

