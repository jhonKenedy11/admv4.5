document.addEventListener('keydown', function (event) {
    debugger
    //busca na tabela quantos registro existem
    let reg = $('td[id=nomeCliente]').length;
    if(reg === 1){
        let idCli   = $('td[id=idCliente]')[0].innerText.trim();
        let nomeCli = $('td[id=nomeCliente]')[0].innerText.trim();
        let opcao   = $('[name=opcao]')[0].defaultValue;
        let credCli = $('td[id=creditoCliente]')[0].innerText.trim();
        let cepCli  = $('td[id=cepCliente]')[0].innerText.trim();
        let munCli  = $('td[id=munCliente]')[0].innerText.trim();
        let bloqCli = $('td[id=bloqCliente]')[0].innerText.trim();
        
        // evento pressionar ENTER
        if (event.keyCode == 13) {
            fechaLancamento(idCli, nomeCli, opcao, credCli, cepCli, munCli, bloqCli);
        }// fim evento enter
    }else{
        // evento pressionar ENTER
        if (event.keyCode == 13) {
            submitLetra();
        }// fim evento enter
    }
});

function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('endereco').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('estado').value = ("");
    document.getElementById('codMunicipio').value = ("");
    document.getElementById('tipo').value = '';
}

function meu_callback(conteudo) {

    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('endereco').value = (conteudo.logradouro);
        document.getElementById('bairro').value = (conteudo.bairro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('codMunicipio').value = (conteudo.ibge);
        document.getElementById('estado').value = (conteudo.uf);
        document.getElementById('numero').focus();

        const tipoEnd = document.getElementById('endereco').value.split(' ');
        document.getElementById('tipo').value = tipoEnd[0];

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
        if (validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('endereco').value = "...";
            document.getElementById('bairro').value = "...";
            document.getElementById('cidade').value = "...";
            document.getElementById('estado').value = "...";
            document.getElementById('codMunicipio').value = "...";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = '//viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

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

function submitAcompanhamento(pessoa_id) {
    f = document.pessoa;
    f.mod.value = 'crm';
    f.form.value = 'contas_acompanhamento';
    f.opcao.value = 'pessoa';
    f.submenu.value = 'cadastrar';
    //f.letra.value = f.dataIni.value + "|" + f.dataFim.value  + "|" + f.vendedor.value + "|" + f.nome.value;
    f.pessoa.value = pessoa_id;
    f.submit();
} // submitAlterar

function submitVoltarConta(consulta = '') {
    f = document.lancamento;
    if (consulta != '') {
        f.opcao.value = consulta;
    }
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitLetra(letra_pesquisa = '') {
    debugger;
    f = document.lancamento;
    if ((f.pesNome.value == '') && (f.idClasse.value == '') && (f.idPessoa.value == '') && (f.idEstado.value == '')
        && (f.idVendedor.value == '') && (f.pesCidade.value == '') && (f.idAtividade.value == '') && (letra_pesquisa == '') && (f.pesCnpjCpf.value == '') && (f.pesObs.value == '')) {
        alert('Digite algum filtro de pesquisa.');
        f.mod.value = 'crm';
        f.form.value = 'contas';
        f.submit();
    } else {
        //f.opcao.value = 'pessoa';
        f.mod.value = 'crm';
        f.form.value = 'contas';
        f.submenu.value = 'letra';
        if (letra_pesquisa == '')
            f.letra.value = f.pesNome.value + "|" + f.idClasse.value + "|" + f.idPessoa.value + "|" + f.idEstado.value + "|" + f.idVendedor.value + "|" + f.pesCidade.value + "|" + f.idAtividade.value + "|" + f.pesCnpjCpf.value + "|" + f.pesObs.value;
        else
            f.letra.value = letra_pesquisa + "|||||||||";
        f.submit();
    }// if
} // submitLetra

function submitLetraPesquisa(nome = null, id = null, check = null) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.submenu.value = 'letra';

    if(nome != null){
        f.pesNome.value = nome;
    }
    
    if(id != null){
        f.id.value = id
    }

    if(check != null){
        f.check.value = check;
    }
   
    f.letra.value = f.pesNome.value + '|' + f.id.value + '|' + f.check.value;
    
    f.submit();
  }

  function submitLetraPed(nome=null) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.submenu.value = 'letra';
    f.checkPedido.value = 'S';

    if(nome !== null){
        f.pesNome.value = nome;
    }
    f.letra.value = f.pesNome.value + "||||||||";
    f.submit();
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

function fechaLancamento(id, nome, opcao, credito = '', cep='', codMunicipio='', bloqueado='', from='') {
    debugger;
    f = window.opener.document.lancamento;

    if (bloqueado == "BLOQUEADO") {
        alert('Conta BLOQUEADA!!!');
    }else{

        if (opcao == "pesquisarequivalente") {
            f.contaEquiv.value = id;
            f.nomeEquivalente.value = nome;
            f.opcao.value = opcao;
        } else {
            if (opcao == "pesquisartransportador") {
                f.transportador.value = id;
                f.transpNome.value = nome;
            } else {
                if (opcao == "pesquisarfornecedor") {
                    f.fornecedor.value = id;
                    f.fornecedorNome.value = nome;
                } else {
                    f.fornecedor.value = '';
                    f.pessoa.value = id;
                    f.nome.value = nome;
                    if(f.form.value == 'pedido_venda_telhas' || f.form.value == 'pedido_venda_telhas_novo'){
                        if(cep != ''){
                            f.cep.value = cep;
                            f.codMunicipio.value = codMunicipio;
                        }
                        if (f.credito != undefined) {
                            f.credito.value = credito;
                        }
                    }
                }
            }
        }
        window.close();
    
    }
}

function fechaPesquisaAtendimento(id, nome, contato) {
    debugger;
    f = window.opener.document.lancamento;
    f.fornecedor.value = '';
    f.pessoa.value = id;
    f.nome.value = nome;
    f.contato.value = contato;        

    window.close();
}

//fecha pesquisa de cliente e atualiza campos da form que chamou
function fechaClientePermanece(e) {
    f = window.opener.document.lancamento;
    var linha = $(e).closest("tr");
    console.log(f);

    var id               = linha.find("td:eq(0)").text().trim(); 
    var descricaoCliente = linha.find("td:eq(1)").text().trim(); 
    
    f.codPermanecer.value  = id;
    f.descPermanecer.value = descricaoCliente  
    window.close();
}

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaClienteRetira(e) {
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(0)").text().trim(); 
    var descricaoCliente = linha.find("td:eq(1)").text().trim(); 
    
    f.codRetirar.value  = id;
    f.descRetirar.value = descricaoCliente  
    window.close();
}

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

function submitCadastro(opcao) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    if (opcao != "")
        f.opcao.value = "pesquisar"
    else
        f.opcao.value = 'pessoa';

    f.submit();
} // submitCadastro