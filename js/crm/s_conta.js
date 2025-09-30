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
         swal.fire({ text: 'O campo "Nome Reduzido / Nome Fantasia" é obrigatório!',
            title: 'Atenção!',
            dangerMode: true});
        return false;
    }
    if (f.nome.value == '') {
        f.nome.style.border = 'solid 2.5px #f500009e';
        f.nome.addEventListener('blur', function () {
            f.nome.style.border = '1px solid #ccc';
        })
         swal.fire({ text: 'O campo "Nome Completo / Razão Social" é obrigatório!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    if (f.pessoa.value == '') {
        f.pessoa.style.border = 'solid 2px #f500009e';
        f.pessoa.addEventListener('blur', function () {
            f.pessoa.style.border = '1px solid #ccc';
        })
         swal.fire({ text: 'Selecione o tipo da Pessoa!',
            title: 'Atenção!',
            dangerMode: true });
        return false;
    }
    // if (f.cnpjCpf.value == '') {
    //     f.cnpjCpf.style.border = 'solid 2px #f500009e';
    //     f.cnpjCpf.addEventListener('blur', function () {
    //         f.cnpjCpf.style.border = '1px solid #ccc';
    //     })
    //     swal({ text: 'O campo CPF / CNPJ deve ser preenchido!', title: 'Atenção!', dangerMode: true });
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

    if (f.pessoa.value == 'F' && f.dataNascimento.value === "") {
    Swal.fire({
        title: "Atenção",
        text: "Data de nascimento não preenchida. Deseja prosseguir?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Prosseguir",
        cancelButtonText: "Cancelar",
    })
    .then((result) => {
            if (result.isConfirmed) {
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
    } else {
        Swal.fire({
            title: "Atenção!",
            text: "Deseja prosseguir com o cadastro?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Prosseguir",
            cancelButtonText: "Cancelar",
        })
        .then((result) => {
            if (result.isConfirmed) {
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
    }
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
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'CEP inválido.',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
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
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'CEP inválido.',
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false
            });
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
        swal.fire({
            text: 'Digite algum filtro de pesquisa.',
            title: 'Atenção!',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
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

async function fechaLancamento(id, nome, opcao, credito = '', cep='', codMunicipio='', bloqueado='', id_representante='') {
    debugger
    f = window.opener.document.lancamento;
     
    if (bloqueado == "BLOQUEADO") {
        swal.fire({
            icon: 'error',
            title: 'Atenção!',
            text: 'Conta BLOQUEADA!!!',
            timer: 1000,
            showConfirmButton: false
        });
    } else {
        if (opcao == "pesquisarequivalente") {
            f.contaEquiv.value = id;
            f.nomeEquivalente.value = nome;
            f.opcao.value = opcao;
        } else if (opcao == "pesquisarCarrinho") {
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
            } else if(f.form.value == 'contas_acompanhamento' || f.form.value == 'contrato' || f.form.value == 'apontamento_os_mobile'){  
                f.pessoa.value = id;
                f.nome.value = nome;
            } else {
                if (opcao == "pesquisarfornecedor") {
                    f.fornecedor.value = id;
                    f.fornecedorNome.value = nome;
                } else if (f.form.value =="manifesto_fiscal"){
                    f.condutor.value = id;
                    f.nomecondutor.value = nome;
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
                    
                    // Se existe id_representante e existe a combo usrAbertura, seta o valor
                    if(id_representante != '' && f.usrAbertura != undefined) {
                        f.usrAbertura.value = id_representante;
                    }
                }
            }
        }
         
        if (typeof window.opener.carregarObras === 'function') {
        window.opener.carregarObras(id);
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

function fechaPesquisaRelatorios(id, nome) {
     

    var input_cliente_id =  window.opener.document.getElementById('cliente_id');
    var input_cliente_nome =  window.opener.document.getElementById('cliente_nome');

    if(input_cliente_id){
        input_cliente_id.value = id;
    }

    if(input_cliente_nome){
        input_cliente_nome.value = nome;
    }

    window.close();
}

//fecha pesquisa de cliente e atualiza campos da form que chamou
function fechaClientePermanece(e) {
    f = window.opener.document.lancamento;
    var linha = $(e).closest("tr");

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
         swal.fire({ text: 'Não foi localizado o Histórico do cliente!',
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
         swal.fire({ text: 'Não foi localizado pedido para esse cliente!',
            title: 'Atenção!',
            dangerMode: true });
    }
}

// Função para abrir a modal de obra
function abrirModalObra(id_obra = '', art = '', projeto = '', cno = '', crea = '', responsavel_tecnico = '', status_obra = '') {
    // incluir nova obra
    if (id_obra === '') {
        limparModalObra();
    } else {
        // alterar obra existente
        $('#id_obra_modal').val(id_obra);
        $('#art_modal').val(art);
        $('#projeto_modal').val(projeto);
        $('#cno_modal').val(cno);
        $('#crea_modal').val(crea);
        $('#responsavel_tecnico_modal').val(responsavel_tecnico).trigger('change');
        $('#status_obra').prop('checked', status_obra === 'I');
        // define o título do modal e o botão de ação
        $('#modalObraTitle').text('Editar Obra');
        let btnSalvarAtualizar = $('#btnSalvarAtualizarObra');
        btnSalvarAtualizar.removeClass('btn-success').addClass('btn-info').text('Atualizar');
    }
    // Abre o modal
    $('#ModalObra').modal('show');
}


function limparModalObra() {
    $('#id_obra_modal').val('');
    $('#art_modal').val('');
    $('#projeto_modal').val('');
    $('#cno_modal').val('');
    $('#crea_modal').val('');
    $('#responsavel_tecnico_modal').val('').trigger('change');
    $('#status_obra').prop('checked', false); 
    
    // define o título do modal e o botão de ação
    $('#modalObraTitle').text('Cadastrar Obra');
    let btnSalvarAtualizar = $('#btnSalvarAtualizarObra');
    btnSalvarAtualizar.removeClass('btn-info').addClass('btn-success').text('Salvar');
}

function salvarObra() {
    let formData = {
        id_cliente: $('input[name="id"]').val(),
        id_obra: $('#id_obra_modal').val(),
        num_art: $('#art_modal').val(),
        desc_projeto: $('#projeto_modal').val(),
        num_cno: $('#cno_modal').val(),
        num_crea: $('#crea_modal').val(),
        responsavel_tecnico: $('#responsavel_tecnico_modal').val(),
        status_obra: $('#status_obra').is(':checked') ? 'I' : 'A'
    };

    let url = document.URL + "?mod=crm&form=contas&submenu=cadastraObra&opcao=blank";
    
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        success: function(data) {
            if (data === 'success') {
                $('#ModalObra').modal('hide');
                
                Swal.fire({
                    text: 'Informações da obra salvas com sucesso!',
                    title: 'Sucesso!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Atualiza lista de obras
                    carregarListaObras();
                });
            } else {
                Swal.fire({
                    text: 'Erro ao salvar informações da obra!',
                    title: 'Erro!',
                    icon: 'error'
                });
            }
        },
        error: function() {
            Swal.fire({
                text: 'Erro na requisição!',
                title: 'Erro!',
                icon: 'error'
            });
        }
    });
}

function carregarListaObras() {
    let clienteId = $('input[name="id"]').val();
    $.ajax({
        url: 'index.php?mod=crm&form=contas&submenu=listaObrasAjax&opcao=blank&id=' + clienteId,
        type: 'GET',
        dataType: 'json',
        success: function(obras) {
            $('#lista-obras').empty();

            if (obras.length > 0) {
                // alimenta a  tabela
                obras.forEach(function(obra) {
                    let responsavelSelect = document.getElementById('responsavel_tecnico_modal');
                    let responsavelText = '';

                    if (responsavelSelect) {
                        let selectedOption = responsavelSelect.querySelector(`option[value="${obra.RESPONSAVEL_TECNICO}"]`);
                        responsavelText = selectedOption ? selectedOption.text : 'Responsável não encontrado';
                    }

                    let linha = `
                        <tr id="obra-${obra.ID}" class="${obra.STATUS === 'I' ? 'text-muted' : ''}">
                            <td>${obra.CNO}</td>
                            <td>${obra.PROJETO}</td>
                            <td>${responsavelText}</td>
                            <td>${obra.CREA}</td>
                            <td>${obra.ART}</td>
                            <td>
                                    ${obra.STATUS === 'A' ? 'Ativo' : 'Inativo'}
                                </td>
                            <td>
                                <button type="button" class="btn btn-info btn-xs" style="min-width: 70px;"
                                    onclick="abrirModalObra('${obra.ID}', '${obra.ART}', '${obra.PROJETO}', '${obra.CNO}', '${obra.CREA}', '${obra.RESPONSAVEL_TECNICO}', '${obra.STATUS}')">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-warning btn-xs" style="min-width: 70px;"
                                    onclick="abrirModalAnexo('${obra.ID}')">
                                    Anexos
                                </button>                                
                            </td>
                        </tr>
                    `;

                    $('#lista-obras').append(linha);
                });
            } else {
                $('#lista-obras').append('<tr><td colspan="8">Nenhuma obra cadastrada</td></tr>');
            }
        },
        error: function() {
            Swal.fire({
                text: 'Erro ao carregar lista de obras',
                title: 'Erro!',
                icon: 'error'
            });
        }
    });
}

// Função para abrir o modal
function abrirModalAnexo(id_obra) {
     
    $('#ModalAnexo').modal('show');
    
    $('#id_obra_anexo').val(id_obra);
    
    carregarAnexos(id_obra);
}

// Função para carregar anexos 
function carregarAnexos(id_obra) {
    $('#anexosExistentes').html(`
        <div class="col-12 text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2">Carregando anexos...</p>
        </div>
    `);

    $.ajax({
    url: 'index.php?form=contas&mod=crm&submenu=carregarAnexosObra&opcao=blank&id_obra=' + id_obra,
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success) {
            if (response.data.length > 0) {
                let html = '';
                response.data.forEach(anexo => {
                    if (anexo.id && anexo.id_obra && anexo.extensao && anexo.caminho_completo) {
                        html += `
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 abrirModal" style="max-width: 250px; height: 200px !important; position: relative;">
                                ${gerarVisualizacaoAnexo(anexo)}
                                <div class="btnManutencao">
                                    <button type="button" class="btn btn-danger btn-xs" onClick="excluirAnexo(${anexo.id})">
                                        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                        <span>Apagar</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="openAnexo('${anexo.caminho_completo}')">
                                        <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                        <span>Abrir</span>
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                });
                    
                    $('#anexosExistentes').html(html || `
                        <div class="col-12 text-center py-4">
                            <p>Nenhum anexo válido encontrado</p>
                        </div>
                    `);
                } else {
                    $('#anexosExistentes').html(`
                        <div class="col-12 text-center py-4">
                            <p>Nenhum anexo encontrado</p>
                        </div>
                    `);
                }
            } else {
                $('#anexosExistentes').html(`
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        <p class="text-danger">${response.message || 'Erro ao carregar anexos'}</p>
                    </div>
                `);
            }
        },
        error: function() {
            
            $('#anexosExistentes').html(`
                <div class="col-12 text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <p class="text-danger">Erro ao carregar anexos.</p>
                </div>
            `);
        }
    });
}



// Função para controlar a visualização do anexo
function gerarVisualizacaoAnexo(anexo) {
    const extensao = anexo.extensao.toUpperCase();
    
    if (extensao === 'JPG' || extensao === 'JPEG' || extensao === 'PNG') {
        return `<img src="${anexo.caminho_completo}" class="img-rounded img-responsive tagImg" style="max-height: 150px; width: auto;"/>`;
    } else if (extensao === 'PDF') {
        return `<object data="${anexo.caminho_completo}" type="application/pdf" class="img-responsive objectPdf" style="height: 150px; width: 100%;"></object>`;
    } 
      
}

// Função para abrir anexo em nova aba
function openAnexo(url) {
    window.open(url, '_blank');
}


function salvarAnexo() {
    const id_obra = $('#id_obra_anexo').val(); 
    const fileInput = $('#arquivoAnexo')[0];
    const file = fileInput.files[0];

    // validação de arquivo selecionado
    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um arquivo antes de enviar.'
        });
        return;
    }

    // validacao de extensao e tamanho do arquivo
    const validExtensions = ['jpeg', 'jpg', 'pdf'];
    const maxSize = 2000000; 

    const fileName = file.name;
    const fileExt = fileName.split('.').pop().toLowerCase();

    if (!validExtensions.includes(fileExt)) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Tipo de arquivo inválido. Apenas JPEG, JPG e PDF são permitidos.'
        });
        return;
    }

    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'O arquivo é muito grande. Tamanho máximo permitido: 2MB.'
        });
        return;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('id_obra', id_obra);

    $.ajax({
        url: 'index.php?form=contas&mod=crm&submenu=salvarAnexoObra',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('.btn-salvar').prop('disabled', true)
                           .html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
        },
        success: function(response) {
            fileInput.value = '';
            $('.custom-file-label').text('Selecione um arquivo...');

            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Anexo salvo com sucesso.',
                showConfirmButton: true,
                timer: 4000
            }).then(() => {
                carregarAnexos(id_obra);
            });
        },
        error: function(xhr) {
            let errorMessage = 'Erro desconhecido';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMessage = response.message || errorMessage;
            } catch (e) {
                errorMessage = xhr.statusText || errorMessage;
            }

            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: `Falha ao enviar: ${errorMessage}`
            });
        },
        complete: function() {
            $('.btn-salvar').prop('disabled', false).html('Salvar');
        }
    });
}


function excluirAnexo(id_anexo) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'index.php?form=contas&mod=crm&submenu=excluiAnexoObra&id_anexo=' + id_anexo, 
                type: 'GET',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'O anexo foi excluído.',
                        'success'
                    );
                    abrirModalAnexo($('#id_obra_anexo').val()); 
                },
                error: function(error) {
                    Swal.fire(
                        'Erro!',
                        'Erro ao excluir o anexo.',
                        'error'
                    );
                }
            });
        }
    });
}


// adicione um evento de clique ao botão
function openModalAddress() {
     
    if(arguments.length > 0){

        // selecione os campos da modal onde você deseja inserir as informações da tabela
        let id          = document.querySelector('.modal-body-address #id-address');
        let cep         = document.querySelector('.modal-body-address #cep-address');
        let endereco    = document.querySelector('.modal-body-address #endereco-address');
        let numero      = document.querySelector('.modal-body-address #numero-address');
        let complemento = document.querySelector('.modal-body-address #complemento-address');
        let bairro      = document.querySelector('.modal-body-address #bairro-address');
        let cidade      = document.querySelector('.modal-body-address #cidade-address');
        let estado      = document.querySelector('.modal-body-address #estado-address');
        let descricao   = document.querySelector('.modal-body-address #descricao-address');
        let titulo      = document.querySelector('.modal-body-address #tituloEndereco-address');
        let ddd         = document.querySelector('.modal-body-address #ddd-address');
        let fone        = document.querySelector('.modal-body-address #fone-address');
        let foneContato = document.querySelector('.modal-body-address #foneContato-address');
        let status      = document.querySelector('#status-address');

        // insira as informações nos campos da modal
        id.value          = arguments[0];
        cep.value         = arguments[11];
        endereco.value    = arguments[5];
        numero.value      = arguments[6];
        complemento.value = arguments[7];
        bairro.value      = arguments[4];
        cidade.value      = arguments[9];
        estado.value      = arguments[10];
        descricao.value   = arguments[2];
        titulo.value      = arguments[4];
        ddd.value         = arguments[12];
        fone.value        = arguments[13];
        foneContato.value = arguments[15];

        if (arguments[17] === "A") {
            status.checked = false;
        } else {
            status.checked = true;
        }
        
    }else[
        console.log('erro')
    ]

}

//logica para consultar cep da modal address e preencher campos - jhon Kenedy
async function pesquisarEndereco(cep) {
     
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
     
    document.querySelector('.modal-body-address #endereco-address').value = limparTexto(endereco.logradouro);
    document.querySelector('.modal-body-address #cidade-address').value = limparTexto(endereco.localidade);
    document.querySelector('.modal-body-address #estado-address').value = limparTexto(endereco.uf);
    document.querySelector('.modal-body-address #bairro-address').value = limparTexto(endereco.bairro);
    document.querySelector('.modal-body-address #cep-address').defaultValue = endereco.cep;
    document.querySelector('.modal-body-address #numero-address').value = '';
    document.querySelector('.modal-body-address #complemento-address').value = '';
    document.querySelector('.modal-body-address #numero-address').focus();
    
}
  
async function pesquisarEnderecoECarregarFormulario(cep) {
     
    try {
        const endereco = await pesquisarEndereco(cep);
        preencherFormulario(endereco);
    } catch (error) {
        limparFormularioCep();
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: error.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
}
  
function limparFormularioCep(){
     
    document.querySelector('.modal-body-address #cep-address').value = "";
    document.querySelector('.modal-body-address #numero-address').value = "";
    document.querySelector('.modal-body-address #cidade-address').value = "";
    document.querySelector('.modal-body-address #estado-address').value = "";
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

  


function insertAddress(){
    // Validação obrigatória
    const cep = document.getElementById('cep-address').value.trim();
    const titulo = document.getElementById('tituloEndereco-address').value.trim();

    if (titulo === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'O campo "Título do Endereço" é obrigatório.'
        });
        document.getElementById('tituloEndereco-address').focus();
        return;
    }
    if (cep === ''  || cep === '0____-___') {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'O campo "CEP" é obrigatório.'
        });
        document.getElementById('cep-address').focus();
        return;
    }

    const objAddress = {
        id_address : document.getElementById('id-address').value,
        idCliente: document.querySelector('input[name="id"]').value,
        tituloEndereco : document.getElementById('tituloEndereco-address').value,
        descricao: document.getElementById('descricao-address').value,
        ddd: document.getElementById('ddd-address').value,
        fone: document.getElementById('fone-address').value,
        foneContato: document.getElementById('foneContato-address').value,
        cep: document.getElementById('cep-address').value,
        endereco: document.getElementById('endereco-address').value,
        numero: document.getElementById('numero-address').value,
        complemento: document.getElementById('complemento-address').value,
        bairro: document.getElementById('bairro-address').value,
        cidade: document.getElementById('cidade-address').value,
        estado: document.getElementById('estado-address').value,
        status: document.getElementById('status-address').checked,

    }
    
    
    //ajax responsible for updating the address
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=crm&form=contas&submenu=insertAddress&opcao=blank",
        data: objAddress,
        dataType: "json",
        success: [responseAddress],
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Erro na requisição: " + textStatus + ", " + errorThrown);
        }
    });

} 

function responseAddress(e){
     
    if(e.success === true){
        swal.fire({ text: 'Endereço atualizado!', title: 'Sucesso!' });
        setTimeout(function () {
            self.location = '';
            window.location.reload(true);
        }, 2000)

    }else{
        swal.fire({ text: 'Erro ao atualizar, contate o suporte!', title: 'Atenção!', dangerMode: true });
    }
}

function searchAdrressClient(id){
     

    var dados = { 'cliente': id }

    $.ajax({
        type: "POST",
        url: document.URL + `?mod=crm&form=contas&submenu=combo_busca_endereco&opcao=blank&id_searchAddress=${id}`,
        dataType: "json",
        data: dados,
        success: function (response) {
             
            f = window.opener.document.lancamento;
            var valorSelecionado = f.elements['enderecoEntrega'].value;

            selectEntrega.innerHTML = '';

            response.forEach(function (endereco) {
                var option = document.createElement('option');
                option.value = endereco.ID;
                option.text = endereco.TITULOEND;
                selectEntrega.appendChild(option);
            });

        },
        error: function (jqXHR, textStatus, errorThrown) {
             
            console.log("Erro na requisição: " + textStatus + ", " + errorThrown);
            return;
        }
    });
    return false;

}

function openModalAddress(id, cliente, descricao, tipoend, tituloend, endereco, numero, complemento, bairro, cidade, uf, cep, fonearea, fone, foneramal, fonecontato, endentregapadrao, status) {
    // Limpa os campos do modal
    $('#id-address').val(id || '');
    $('#descricao-address').val(descricao || '');
    $('#tituloEndereco-address').val(tituloend || '');
    $('#endereco-address').val(endereco || '');
    $('#numero-address').val(numero || '');
    $('#complemento-address').val(complemento || '');
    $('#bairro-address').val(bairro || '');
    $('#cidade-address').val(cidade || '');
    $('#estado-address').val(uf || '');
    $('#cep-address').val(cep || '');
    $('#ddd-address').val(fonearea || '');
    $('#fone-address').val(fone || '');
    $('#foneContato-address').val(fonecontato || '');

    // Define o estado do checkbox de status
    if (status === 'I') {
        $('#status-address').prop('checked', true);
    } else {
        $('#status-address').prop('checked', false);
    }
}

