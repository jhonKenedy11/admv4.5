document.addEventListener('keydown', function (event) {
    
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

function submitConfirmar() {     
    
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    if (f.opcao.value == 'pesquisar')
        f.opcao.value = 'pesquisar';

    if(f.nomeReduzido.value == ''){
        f.nomeReduzido.style.border = 'solid 2.5px #f500009e';
        f.nomeReduzido.addEventListener('blur', function () {
            f.nomeReduzido.style.border = '1px solid #ccc';
        })
         swal.fire({ 
            text: 'O campo "Nome Reduzido / Nome Fantasia" é obrigatório!',
            title: 'Atenção!',
            dangerMode: true});
        return false;
    }
    if (f.nome.value == '') {
        f.nome.style.border = 'solid 2.5px #f500009e';
        f.nome.addEventListener('blur', function () {
            f.nome.style.border = '1px solid #ccc';
        })
         swal.fire({
            text: 'O campo "Nome Completo / Razão Social" é obrigatório!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    if (f.pessoa.value == '') {
        f.pessoa.style.border = 'solid 2px #f500009e';
        f.pessoa.addEventListener('blur', function () {
            f.pessoa.style.border = '1px solid #ccc';
        })
         swal.fire({text: 'Selecione o tipo da Pessoa!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    // if (f.cnpjCpf.value == '') {
    //     f.cnpjCpf.style.border = 'solid 2px #f500009e';
    //     f.cnpjCpf.addEventListener('blur', function () {
    //         f.cnpjCpf.style.border = '1px solid #ccc';
    //     })
    //      swal.fire({ text: 'O campo CPF / CNPJ deve ser preenchido!', title: 'Atenção!', dangerMode: true });
    //     return false;
    // }   
    if (f.cep.value == '') {
        f.cep.style.border = 'solid 2px #f500009e';
        f.cep.addEventListener('blur', function () {
            f.cep.style.border = '1px solid #ccc';
        })
         swal.fire({
            text: 'O campo CEP deve ser preenchido!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    if (f.numero.value == '') {
        f.numero.style.border = 'solid 2px #f500009e';
        f.numero.addEventListener('blur', function () {
            f.numero.style.border = '1px solid #ccc';
        })
         swal.fire({
            text: 'O campo Numero deve ser preenchido!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    if(f.codMunicipio.value == ''){
        f.codMunicipio.style.border = 'solid 2px #f500009e';
        f.codMunicipio.addEventListener('blur', function(){
            f.codMunicipio.style.border = '1px solid #ccc';
        })
         swal.fire({
            text: 'Código do municipio não preenchido, verifique o CEP para corrigir!',
            title: 'Atenção',
            dangerMode: true});
        return false;
    }

     swal.fire({
        title: "Atenção!",
        text: "Deseja prosseguir com o cadastro?",
        icon: "warning",
        buttons: true,
    })
        .then((yes) => {
            if (yes) {
                if ((f.submenu.value == "alterar") || (f.submenu.value == "altera")) {
                    f.submenu.value = 'altera';
                } else {
                    f.submenu.value = 'inclui';
                }
                f.submit();
            } else {
                return false;
            }
        });

    // if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
    //     if ((f.submenu.value == "alterar") || (f.submenu.value == "altera")) {
    //         f.submenu.value = 'altera';
    //     } else {
    //         f.submenu.value = 'inclui';
    //     }

    //     f.submit();
    // } // if
} // fim submitConfirmar

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
} // submitAcompanhamento

function submitVoltar(consulta = '') {
    
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    if (consulta !== '') {
        f.opcao.value = consulta;
    }else{
        f.opcao.value = '';
    }
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitLetra(letra_pesquisa = '') {
    
    f = document.lancamento;
    if ((f.pesNome.value == '') && (f.idClasse.value == '') && (f.idPessoa.value == '') && (f.idEstado.value == '')
        && (f.idVendedor.value == '') && (f.pesCidade.value == '') && (f.idAtividade.value == '') && (letra_pesquisa == '') && (f.pesCnpjCpf.value == '')) {
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
            f.letra.value = f.pesNome.value + "|" + f.idClasse.value + "|" + f.idPessoa.value + "|" + f.idEstado.value + "|" + f.idVendedor.value + "|" + f.pesCidade.value + "|" + f.idAtividade.value + "|" + f.pesCnpjCpf.value;
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
    
    f = window.opener.document.lancamento;

    if (bloqueado == "BLOQUEADO") {
        alert('Conta BLOQUEADA!!!');
    }else{

        if (opcao == "pesquisarequivalente") {
            f.contaEquiv.value = id;
            f.nomeEquivalente.value = nome;
            f.opcao.value = opcao;
        } else if (opcao == "pesquisarCarrinho"){
            //adiciona no carrinho da consulta de preco
            if(window.opener.document.getElementById('nomeCliente')){
                window.opener.document.getElementById('nomeCliente').value = nome;
            }
            if(window.opener.document.getElementById('pessoaId')){
                window.opener.document.getElementById('pessoaId').value = id;
            }

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


//NEWS FUNCTIONS VERSION 4.3.1
function buscaHistorico(id = null) {
    
    var dados = { 'cliente': id }
    //ajax responsavel por enviar dados ao form crm
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=crm&form=contas&submenu=buscaHistoricoCliente&opcao=blank",
        data: dados,
        dataType: "json",
        success: [returnHistorico]
    });
} // buscaHistorico

function returnHistorico(response) {
    
    if (response !== null) {
        var trs = $("#dadosHistorico tr").length;
        if (trs != 0) {
            $("#dadosHistorico tr").remove();
        }
        let htmlTabela = [];
        for (let prop of Object.keys(response)) {
            htmlTabela +=
                `<tr>
                  <td style="vertical-align: middle !important;">`+ response[prop]['DATA'] + `</td>
                  <td>`+ response[prop]['RESULTADO'] + `</td>
                </tr>`
        }

        $("#dadosHistorico").append(htmlTabela);
        $('#modalHistoricoCliente').modal('show');
    } else {
         swal.fire({
            text: 'Não foi localizado o Histórico do cliente!',
            title: 'Atenção!',
            dangerMode: true });
    }
}

function submitAlterar(pessoa_id) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas';
    f.opcao.value = 'pessoa';
    f.submenu.value = 'alterar';
    f.id.value = pessoa_id;
    f.submit();
} // submitAlterar

function buscaPedCliente(nome=null) {
    var dados = {'cliente': nome}
    //ajax responsavel por enviar dados ao form crm
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=crm&form=contas&submenu=buscaPedidosCliente&opcao=blank",
        data: dados,
        dataType: "json",
        success: [returnPedidos]
    });
} // buscaPedCliente

function returnPedidos(response){
    
    if(response !== null){
        var trs = $("#dadosPedidos tr").length;
        if (trs != 0) {
            $("#dadosPedidos tr").remove();
        }
        let htmlTabela = [];
        for (let prop of Object.keys(response)) {
            htmlTabela +=
                `<tr>
                  <td>`+ response[prop]['PEDIDO'] + `</td>
                  <td>`+ response[prop]['EMISSAO'] + `</td>
                  <td>`+ response[prop]['TOTAL'] + `</td>
                  <td align="center">`+ response[prop]['CCUSTO'] + `</td>
                </tr>`
        }

        $("#dadosPedidos").append(htmlTabela);
        $('#modalPedidosCliente').modal('show');
    }else{
         swal.fire({
            text: 'Não foi localizado pedido para esse cliente!',
            title: 'Atenção!',
            dangerMode: true });
    }
}

// new function to replace the accordion in Bootstrap version php8.3
function toggleAccordion(id) {
    var element = document.getElementById(id);

    if (element.classList.contains('show')) {
        element.classList.remove('show'); // Oculta o conteúdo
    } else {
        element.classList.add('show'); // Exibe o conteúdo
    }
}

// new function to replace the accordion in Bootstrap version php8.3
function showTabContent(event, tabContentID){
    // Impede o comportamento padrao do link
    event.preventDefault();

    // Remove a classe 'active' de todas as abas
    document.querySelectorAll('.accordion-button').forEach(function(tab){
        tab.classList.remove('active');
    });

    // Adiciona a classe 'active' à aba clicada
    event.currentTarget.parentElement.classList.add('active');

    // Remove a classe 'active' de todos os conteúdos das abas
    document.querySelectorAll('.tab-content > .tab-pane').forEach(function(pane) {
        pane.classList.remove('active', 'in');
    });

    // Adiciona a classe 'active' e 'in' ao conteudo correspondente
    let selectedPane = document.getElementById(tabContentID);

    if(selectedPane) {
        selectedPane.classList.add('active', 'in');
    }
}