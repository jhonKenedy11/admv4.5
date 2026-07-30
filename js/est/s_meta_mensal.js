function submitConfirmar() {
    f = document.lancamento;

    const isCadastrar = (f.submenu.value === "cadastrar");
    const acaoTexto = isCadastrar ? "cadastrar" : "alterar";

    Swal.fire({
        title: "Atenção!",
        text: "Deseja " + acaoTexto + " esse registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: isCadastrar ? "Cadastrar" : "Alterar",
        cancelButtonText: "Cancelar",
    })
    .then((result) => {
        if (result.isConfirmed) {
            f.mod.value = "est";
            f.form.value = "meta_mensal";
            f.submenu.value = isCadastrar ? "inclui" : "altera";
            f.submit();
        }
    });
}

function submitVoltar() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar


function submitVoltarVendedor() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'alterar';
    f.param.value = 'formVendedor'
    f.submit();
} // fim submitVoltar


function submitCadastro() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(meta_id) {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'alterar';
    f.id.value = meta_id;
    f.submit();
} // submitAlterar


function submitExcluir(meta_id) {
    f = document.lancamento;

    Swal.fire({
        title: "Atenção!",
        text: "Deseja excluir esta meta?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Excluir",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = "exclui";
            f.id.value = meta_id;
            f.submit();
        }
    });
}

function submitAddMetaUsuario() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'cadastrarVendedor';
    f.submit();
} // submitAddMetaUsuario

function submitConfirmarVendedor() {
    f = document.lancamento;
    f.form.value = 'meta_mensal';
    f.mod.value = 'est';

    const isCadastrar = (f.submenu.value === "cadastrarVendedor");
    const acaoTexto = isCadastrar ? "cadastrar" : "alterar";
    const acaoBotao = isCadastrar ? "Cadastrar" : "Alterar";

    Swal.fire({
        title: "Atenção!",
        text: "Deseja " + acaoTexto + " essa meta vendedor?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: acaoBotao,
        cancelButtonText: "Cancelar",
    })
    .then((result) => {
         
        if (result.isConfirmed) {
            f.submenu.value = isCadastrar ? "incluirVendedor" : "alteraVendedor";
            f.submit();
        }
    });
} // submitConfirmarVendedor


function submitAlterarVendedor(id, metaid) {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'alterarVendedor';
    f.id.value = id;
    f.submit();
} // submitAlterar


function submitExcluirVendedor(id, metaid) {
    f = document.lancamento;

    Swal.fire({
        title: "Atenção!",
        text: "Deseja excluir esta meta?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Excluir",
        cancelButtonText: "Cancelar",
    })
    .then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = "excluiVendedor";
            f.id.value = id;
            f.metaid.value = metaid;
            f.submit();
        }
    });
}