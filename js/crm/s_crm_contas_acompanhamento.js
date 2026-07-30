document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.keyCode == 13) {
        submitLetra();
    }// fim evento enter
    // evento pressionar ESC
    if (event.keyCode == 27) {
        submitVoltar();
    }// fim do evento esc
});

function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'crm_contas_acompanhamento';
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
} // submitConfirmar


function submitVoltar() {
    f = document.lancamento;
    //valida se a tela é que chamou é do dashboard para apenas fechar a tela
    if(f.dashboard_origem.value !== 'dashboard_crm'){
        if (f.opcao.value == 'pessoa') {
            document.lancamento.mod.value = 'crm';
            f.form.value = 'contas';
            //f.opcao.value = '';
            f.letra.value = f.pessoaNome.value;}
        else{
            f.form.value = 'crm_contas_acompanhamento';
            //f.opcao.value = 'acompanhamento';
            }
        f.submenu.value = '';
        f.submit();
    }else{
        window.close();
    }
} // fim submitVoltar


function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'crm_contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'cadastrar';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value  + "|" + f.vendedor.value + "|" + f.nome.value;
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(acomp_id) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'crm_contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'alterar';
    f.id.value = acomp_id;
    f.submit();
} // submitAlterar

function submitExcluir(acomp_id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Deseja realmente excluir este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'crm';
            f.form.value = 'crm_contas_acompanhamento';
            f.submenu.value = 'exclui';
            f.id.value = acomp_id;
            f.submit();
        }
    });
} // submitExcluir

function submitLetra() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'crm_contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'letra';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value  + "|" + f.vendedor.value + "|" + f.nome.value;
    f.submit();
} // submitLetra

function abrir(pag){
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

function submitCadastraNovoAcomp(){
    //referencia o form da tela na variavel f
    f = document.lancamento;
    if(f.dataHoraProximoAcomp.value == ''){
        swal({ text: 'Informe a data e a hora do próximo contato!', title: 'Atenção!', dangerMode: true });
        return false;
    }
    $('.swal-modal').css('width', '700px');
    swal({
        title: "Atenção!",
        text: "Deseja cadastrar um novo acompanhamento?",
        icon: "warning",
        buttons: {
            btn_cancelar: {
                text: "Cancelar",
                value: '0',
                dangerMode: true
            },
            btn_cadastrar: {
                text: "Cadastrar",
                value: "1",
            }
        }})
    .then((val) => {
        if(val == '1'){ //insert new

            //cria o json
            let dataNovoAcompanhamento = {
                dataHora: f.dataHoraProximoAcomp.value,
                pessoa: f.clienteCombo.value,
                acao: f.acaoNovoAcomp.value,
                descricao: f.descNovoAcomp.value.trim(),
                origem: 'dashboard'
            }
            //converte em json
            let jsonAcompanhamento  = JSON.stringify(dataNovoAcompanhamento);

            //ajax responsavel por enviar dados ao form crm
            $.ajax({
                type: "POST",
                url: document.URL + "?mod=crm&form=contas_acompanhamento&submenu=inclui",
                data: {jsonAcompanhamento: jsonAcompanhamento},
                dataType: "text",
                success: [returnNovoAcompanhamento],
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX:", textStatus, errorThrown);
                }
            });

        }else if(val == '0'){ //cancel
            return false;
        }else{
            return false;
        }
    }); //Fim Swal
}

function returnNovoAcompanhamento(response){
    if(response){
        //localiza na response a div que contem os acompanhamentos
        var result = $('<div />').append(response).find('#datatable-acompanhamento').html();
        //aplica no html a resposta
        $("#datatable-acompanhamento").html(result);
        //zera os campos para novo registro
        document.getElementById('dataHoraProximoAcomp').value = '';
        document.getElementById('descNovoAcomp').value = '';
    }
}

function submitSalvaContato() {
    //recupera o valores das variaveis
    let nome_contato = document.getElementById("nome_contato").value;
    let email_contato = document.getElementById("email_contato").value;
    let telefone_contato = document.getElementById("telefone_contato").value;
    let id_pessoa = document.getElementById("pessoa").value;

    if (nome_contato == '' || nome_contato == null) {
        swal({ text: 'Preencha o campo nome do contato!', title: 'Atenção!', dangerMode: true });
        return false;
    }

    //cria o json
    let dados_contato = {
        nome_contato: nome_contato,
        email_contato: email_contato,
        telefone_contato: telefone_contato,
        id_pessoa: id_pessoa,
        origem: 'dashboard'
    }

    //converte   em json
    let json_dados_contato = JSON.stringify(dados_contato);

    //ajax responsavel por enviar dados ao form crm
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_contas_acompanhamento&opcao=blank&submenu=incluiContato",
        data: { json_dados_contato: json_dados_contato },
        dataType: "text",
        success: returnSalvaContato,
        error: function (jqXHR, textStatus, errorThrown, response) {
            console.log(response)
            console.error("Erro na requisição AJAX:", textStatus, errorThrown);
        }
    });
}

function returnSalvaContato(response) {
    //console.log(response)
    if (response) {
        let codigo_retorno = $(response).find('input[name="codigo_retorno_contato"]').val();
        let mensagem_retorno = $(response).find('input[name="mensagem_retorno_contato"]').val();
        if(codigo_retorno == '100'){
            //localiza na response a div que contem os acompanhamentos
            let result = $('<tbody />').append(response).find('#bodyContatos').html();
            //aplica no html a resposta
            $("#bodyContatos").html(result);
            //mensagem na tela
            swal({ text: 'Contato adicionado!', title: 'Sucesso!', icon: 'success',button: 'Ok'});
        }else{
            //mensagem na tela
            swal({ text: 'Erro ao adicionar contato, entre em contato com o suporte!', title: 'Atenção!', dangerMode: true });
            $('.swal-modal').css('width', '670px');
            console.log(mensagem_retorno)
        }

        //aplica o css 
        $('.bodyContatos > tr > th, .bodyContatos > tr > td').css({
            'padding': '2px',
            'vertical-align': 'inherit'
        });
        //zera os campos para novo registro
        document.getElementById("nome_contato").value = '';
        document.getElementById("email_contato").value = '';
        document.getElementById("telefone_contato").value = '';
        $('#btnCancelarContato').click();
    }
} 

function sendEmail(event){
     
    event.preventDefault(); // Evitar o envio padrão do formulário
    //capture the content
    let email = "";
    let remetente = "";
    let destinatario = "";
    let assunto = "";
    let body = "";
    let id_acomp = "";
    let id_pessoa = "";
    let anexos = "";
    let template = "";
    let descAcompanhamento = "";


    // Verification if elements exists
    if (document.getElementById("email_remetente")) {
        remetente = document.getElementById("email_remetente").value;
    }

    if (document.getElementById("email_destinatario")) {
        destinatario = document.getElementById("email_destinatario").value;
    }

    if (document.getElementById("email_assunto")) {
        assunto = document.getElementById("email_assunto").value;
    }

    if (document.getElementById("editor-one")) {
        body = document.getElementById("editor-one").innerHTML;
    }

    if (document.getElementById("id")) {
        id_acomp = document.getElementById("id").value;
    }

    if (document.getElementById("pessoa")) {
        id_pessoa = document.getElementById("pessoa").value;
    }

    if (document.getElementById("resultContato")) {
        descAcompanhamento = document.getElementById("resultContato").value;
    }

    
    //check if there is content
    if(remetente == '' || remetente == null){
        swal({ text: 'Remetente não localizado!', title: 'Atenção!', dangerMode: true });
        return false
    }
    
    if(destinatario == '' || destinatario == null){
        swal({ text: 'Destinatário não localizado!', title: 'Atenção!', dangerMode: true });
        return false
    }
    
    if(assunto == '' || assunto == null){
        swal({ text: 'Digite o assunto do e-mail!', title: 'Atenção!', dangerMode: true });
        return false
    }
    
    if(body == '' || body == null){
        swal({ text: 'É necessário conteúdo para realizar o envio do e-mail!', title: 'Atenção!', dangerMode: true });
        //if necessary change the size of the modal
        //$('.swal-modal').css('width', '670px');
        return false
    }

    //captura anexos
    anexos = capturarCheckboxMarcadosAnexo();

    //captura template
    template = capturarCheckboxMarcadosTemplate();

    //create the json
    email = {
        remetente: remetente,
        destinatario: destinatario,
        assunto: assunto,
        body: body,
        id_acomp: id_acomp,
        id_pessoa: id_pessoa,
        anexos: anexos,
        descAcompanhamento: descAcompanhamento,
        template: template
    }

    //flow ajax
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_contas_acompanhamento&opcao=blank&submenu=sendEmail",
        data: { email: email },
        dataType: "json",
        preventDefault: false,
        success:  function (response) {
            if (!response) {
                swal({ text: 'Retorno inválido no envio.', title: 'Erro!', dangerMode: true });
                return false;
            }

            if (response.codigo === '100') {
                swal({ text: response.msg || 'E-mail enviado!', title: 'Sucesso!' });
                return false;
            }

            if (response.codigo === '404' || response.codigo === '403') {
                swal({ text: response.msg || 'Falha ao enviar e-mail.', title: 'Atenção!', dangerMode: true });
                return false;
            }

            swal({ text: response.msg || 'E-mail processado.', title: 'Informação' });
            return false;
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error("Erro no envio de e-mail:", textStatus, errorThrown, xhr && xhr.responseText ? xhr.responseText : "");
            swal({ text: 'Não foi possível enviar o e-mail.', title: 'Erro!', dangerMode: true });
            return false;
        }
    });
    return false;
}


function capturarCheckboxMarcadosAnexo() {
     
    let checkboxes = document.querySelectorAll('.anexoEmail');
    let valoresMarcados = [];
    
    checkboxes.forEach(function(checkbox) {
         
        if (checkbox.checked) {
            valoresMarcados.push(checkbox.id);
        }
    });
    
    return valoresMarcados;
}

function capturarCheckboxMarcadosTemplate() {
    let selected = document.querySelector('.templateEmail:checked');
    if (!selected) {
        return '';
    }
    return selected.value ? String(selected.value) : '';
}

/** Radio template: um grupo (name=crm_template_email), opção "Nenhum" com value vazio. */
function verificaCheckTemplate() {
    var el = event && event.target ? event.target : null;
    if (!el || !(el.classList && el.classList.contains('templateEmail'))) {
        return false;
    }
    var v = el.value != null ? String(el.value) : '';
    if (v === '') {
        var ed = document.getElementById('editor-one');
        if (ed) {
            ed.innerHTML = ' ';
        }
        return false;
    }
    buscaTemplate(v);
    return false;
}

function buscaTemplate(id) {
    if (id === undefined || id === null || String(id).trim() === '') {
        var ed0 = document.getElementById('editor-one');
        if (ed0) {
            ed0.innerHTML = ' ';
        }
        return false;
    }

    //flow ajax
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_contas_acompanhamento&opcao=blank&submenu=buscaTemplate",
        data: { template: id },
        dataType: "json",
        success: returnTemplate,
        error: function (jqXHR, textStatus, errorThrown) {
             
            console.error("Erro na requisição AJAX:", textStatus, errorThrown);
            swal({ text: 'Erro na requisição AJAX. Consulte o console para obter mais detalhes.', title: 'Erro!', dangerMode: true });
        }
    });
    return false;
}

function returnTemplate(response){
    if (response == '404') {
        swal({ text: 'Template não encontrado.', title: 'Erro!', dangerMode: true });
        return false;
    }
    document.getElementById("editor-one").innerHTML = response;
}


function savedEmail(event){
     
    event.preventDefault(); // Evitar o envio padrão do formulário
    //capture the content
    let email = "";
    let remetente = "";
    let destinatario = "";
    let assunto = "";
    let body = "";
    let id_acomp = "";
    let id_pessoa = "";
    let anexos = "";
    let template = "";
    let descAcompanhamento = "";


    // Verification if elements exists
    if (document.getElementById("email_remetente")) {
        remetente = document.getElementById("email_remetente").value;
    }

    if (document.getElementById("email_destinatario")) {
        destinatario = document.getElementById("email_destinatario").value;
    }

    if (document.getElementById("email_assunto")) {
        assunto = document.getElementById("email_assunto").value;
    }

    if (document.getElementById("editor-one")) {
        body = document.getElementById("editor-one").innerHTML;
    }

    if (document.getElementById("id")) {
        id_acomp = document.getElementById("id").value;
    }

    if (document.getElementById("pessoa")) {
        id_pessoa = document.getElementById("pessoa").value;
    }

    if (document.getElementById("resultContato")) {
        descAcompanhamento = document.getElementById("resultContato").value;
    }


    //captura anexos
    anexos = capturarCheckboxMarcadosAnexo();

    //captura template
    template = capturarCheckboxMarcadosTemplate();

    //create the json
    email = {
        remetente: remetente,
        destinatario: destinatario,
        assunto: assunto,
        body: body,
        id_acomp: id_acomp,
        id_pessoa: id_pessoa,
        anexos: anexos,
        descAcompanhamento: descAcompanhamento,
        template: template
    }

    //flow ajax
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_contas_acompanhamento&opcao=blank&submenu=savedEmail",
        data: { email: email },
        dataType: "json",
        success: function (response) {
             
            //let objeto = JSON.parse(response);
            if(response['resultInsertEmail'] == true){
                swal({ text: "E-mail salvo", title: 'Sucesso!' });
            }else{
                swal({ text: "Não foi possível salvar o e-mail.", title: 'Atenção!', dangerMode: true});
            }
            //console.log(response)
        },
        error: function (response) {
             
            console.log("Erro na requisição AJAX:", response.responseText);
            swal({ text: 'Erro na requisição AJAX. Consulte o console para obter mais detalhes.', title: 'Erro!', dangerMode: true });
        }
    });
    return false;
}


//monta o campo anexo atraves dos checkboxs da lista de anexos
function updateInputAnexo(){
    var checkboxes = document.querySelectorAll('.anexoEmail:checked');
    var descricaoAnexos = '';

    // Itera sobre cada checkbox marcado
    checkboxes.forEach(function(checkbox) {
        // Obtém a descrição do anexo associado ao checkbox
        var descricao = checkbox.parentNode.nextElementSibling.textContent;

        // Adiciona a descrição do anexo à string com separador ';'
        descricaoAnexos += descricao + '; ';
    });

    // Remove o último ';' da string, se houver
    descricaoAnexos = descricaoAnexos.replace(/;\s*$/, '');
    document.getElementById('email_anexo').value = descricaoAnexos;
}

//monta campo do destinatario atraves dos checkbox da lista de contatos
function updateInputDestinatario() {
     
    var checkboxes = document.querySelectorAll('.trContatosCheck');
    var descricaoEmails = "";

    checkboxes.forEach(function(checkbox) {
         
        if (checkbox.checked) {
            var email = checkbox.value.trim();
            descricaoEmails += email + "; ";
        }
    });

    // Remova o último ponto e vírgula e espaço
    descricaoEmails = descricaoEmails.slice(0, -2);
    document.getElementById('email_destinatario').value = descricaoEmails;
}

function setCliente(){
    f = document.lancamento;
    f.pessoa.value = f.clienteCombo.value;
}