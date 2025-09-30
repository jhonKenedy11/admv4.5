
// Função para abrir modal (novo ou editar)
function abrirModal(id = null) {
    debugger
    if(id == null) {
        // Novo - limpar formulário
        limparFormulario();
        $('#modalResponsavelLabel').text('Novo Responsável Técnico');
    } else {
        $('#modalResponsavelLabel').text('Editar Responsável Técnico');
        carregarDadosDoTemplate(id);
    }
    $('#modalResponsavel').modal('show');
}

// Função para carregar dados do template (Smarty)
function carregarDadosDoTemplate(id) {
    if (id) {
        // Buscar dados do array $lanc que está disponível no template
        var lanc = window.lanc || [];
        var responsavel = lanc.find(function(item) {
            return item.ID == id;
        });
        
        if (responsavel) {
            $('#id').val(responsavel.ID);
            $('#nome').val(responsavel.NOME || '');
            $('#cpf').val(responsavel.CPF || '');
            $('#crea').val(responsavel.CREA || '');
            $('#telefone').val(responsavel.TELEFONE || '');
            $('#email').val(responsavel.EMAIL || '');
            $('#rua').val(responsavel.RUA || '');
            $('#numero').val(responsavel.NUMERO || '');
            $('#complemento').val(responsavel.COMPLEMENTO || '');
            $('#cidade').val(responsavel.CIDADE || '');
            $('#estado').val(responsavel.ESTADO || '');
            $('#cep').val(responsavel.CEP || '');
            $('#situacao').val(responsavel.SITUACAO || 'A');
        }
    }
}

// Função para limpar formulário
function limparFormulario() {
    $('#formResponsavel')[0].reset();
    $('#id').val('');
}



// Função para salvar responsável (novo ou edição)
function salvarResponsavel() {
    f = document.formResponsavel;
    f.submenu.value = (f.id.value !== '') ? 'alterar' : 'cadastrar';
    f.submit();
}


// Função para excluir responsável
function excluirResponsavel(id, nome) {
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente excluir o responsável técnico "' + nome + '"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'exclui';
            f.id.value = id;
            f.submit();
        }
    });
}




// ===== FUNÇÕES DO VIA CEP =====

// Função para limpar formulário de CEP
function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('rua').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('estado').value = ("");
}

// Função de callback do ViaCEP
function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('rua').value = (conteudo.logradouro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('estado').value = (conteudo.uf);
        document.getElementById('numero').focus();
    } else {
        //CEP não Encontrado.
        limpa_formulário_cep();
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'CEP não encontrado.',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }
}

// Função para pesquisar CEP
function pesquisacep(valor) {
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (validacep.test(cep)) {
            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua').value = "...";
            document.getElementById('cidade').value = "...";
            document.getElementById('estado').value = "...";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = '//viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);
        } else {
            //cep é inválido.
            limpa_formulário_cep();
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'CEP inválido.',
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    } else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
}
