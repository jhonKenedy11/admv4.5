function submitConfirmar() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } //  
} // submitConfirmar

function submitVoltar() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(meta_id) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'alterar';
    f.id.value = meta_id;
    f.submit();
} // submitAlterar

function submitExcluir(meta_id) {
    debugger;
    if (confirm('Deseja realmente Excluir esta meta ') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'meta_mensal';
        f.submenu.value = 'exclui';
        f.id.value = meta_id;
        f.submit();
    } // if
} // submitExcluir

function submitAddMetaUsuario() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'cadastrarVendedor';
    f.submit();
} // submitAddMetaUsuario

function submitConfirmarVendedor() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrarVendedor") {
            f.submenu.value = 'incluirVendedor';
        } else {
            f.submenu.value = 'alteraVendedor';
        }
        f.submit();
    } //  
} // submitConfirmar

function submitAlterarVendedor(id, metaid) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta_mensal';
    f.submenu.value = 'alterarVendedor';
    f.id.value = id;
    f.submit();
} // submitAlterar

function submitExcluirVendedor(id, metaid) {
    debugger;
    if (confirm('Deseja realmente Excluir esta meta ') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'meta_mensal';
        f.submenu.value = 'excluiVendedor';
        f.id.value = id;
        f.metaid.value = metaid;
        f.submit();
    } // if
} // submitExcluir