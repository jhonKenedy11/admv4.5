 
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('estado').value=("");
            document.getElementById('codMunicipio').value=("");
    }

    function meu_callback(conteudo) {
        
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('codMunicipio').value=(conteudo.ibge);
            document.getElementById('estado').value=(conteudo.uf);
            document.getElementById('numero').focus();
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {
        
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
                document.getElementById('codMunicipio').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                
                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    }
        
        
        function submitVoltar() {
            f = document.pessoa;
            f.mod.value = 'crm';
            f.form.value = 'contas';
            f.submenu.value = '';
            f.submit();
        } // fim submitVoltar


function submitConfirmar() {
    f = document.pessoa;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }

        f.submit();
    } // if
} // fim submitConfirmar

function submitCadastro() {
    f = document.pessoa;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.opcao.value = 'pessoa';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(pessoa_id) {
    f = document.pessoa;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.opcao.value = 'pessoa';
    f.submenu.value = 'alterar';
    f.id.value = pessoa_id;
    f.submit();
} // submitAlterar

function submitExcluir(pessoa_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.pessoa;
        f.mod.value = 'crm';
        f.form.value = 'contas';
        f.opcao.value = 'pessoa';
        f.submenu.value = 'exclui';
        f.id.value = pessoa_id;
        f.submit();
    } // if
} // submitExcluir

function submitLetra() {
    f = document.pessoa;
    if ((f.pesNome.value == '') && (f.idClasse.value == '') && (f.idPessoa.value == '') && (f.idEstado.value == '') && (f.idVendedor.value == '') && (f.pesCidade.value == '') && (f.idAtividade.value == '')) {
        alert('Digite algum filtro de pesquisa.');
    } else {
        //f.opcao.value = 'pessoa';
        f.mod.value = 'crm';
        f.form.value = 'contas';
        f.submenu.value = 'letra';
        f.letra.value = f.pesNome.value + "|" + f.idClasse.value + "|" + f.idPessoa.value + "|" + f.idEstado.value + "|" + f.idVendedor.value + "|" + f.pesCidade.value + "|" + f.idAtividade.value;
        f.submit();
    }// if
} // submitLetra



// Pessoa parque	
function fechaPessoaParque(id, desc) {
    f = window.opener.document.pessoa_parque;
    f.cliente.value = id;
    f.nome.value = nome;
    window.close();
}

function fechaPedidoVenda(id, nome) {
    f = window.opener.document.pedidovenda;
    f.cliente.value = id;
    f.nome.value = nome;
    window.close();
}

function fechaOrdemServico(id, nome, fone, contato, cidade) {
    f = window.opener.document.ordemservico;
    f.cliente.value = id;
    f.nome.value = nome;
    f.fone.value = fone;
    f.contato.value = contato;
    f.cidade.value = cidade;
    window.close();
}

function fechaProduto(id, nome) {
    f = window.opener.document.produto;
    f.fabricante.value = id;
    f.nomeFab.value = nome;
    window.close();
}

function fechaOrdemServicoSolicitante(id, nome) {
    f = window.opener.document.ordemservico;
    f.solicitante.value = id;
    f.nomeSolicitante.value = nome;
    window.close();
}

function fechaOrdemServicoSolicitanteConsulta(id, nome) {
    f = window.opener.document.ordemservico;
    f.cliente.value = id;
    f.nome.value = nome;
    window.close();
}

function fechaRelHoras(id, nome, campo) {
    f = window.opener.document.relhoras;
    if (campo == "I") {
        f.clienteini.value = id;
        f.nomeini.value = nome;
    } else {
        f.clientefim.value = id;
        f.nomefim.value = nome;
    }
    window.close();
}

function fechaFuncionario(id, nome) {
    f = window.opener.document.funcionario;
    f.empresa.value = id;
    f.nomeempresa.value = nome;
    window.close();
}

function fechaClientePar(id, nome, form) {
    if (form == 'movimentoConsulta') {
        f = window.opener.document.movimentoConsulta;
    } else {
        f = window.opener.document.clientePar;
    }
    f.cliente.value = id;
    f.nome.value = nome;
    window.close();
}

function fechaFornecedorPar(id, nome) {
    f = window.opener.document.fornecedorPar;
    f.fornecedor.value = id;
    f.nome.value = nome;
    window.close();
}

function fechaLancamento(id, nome) {
    f = window.opener.document.lancamento;
    f.fornecedor.value = id;
    f.pessoa.value = id;
    f.nome.value = nome;
    window.close();
}
