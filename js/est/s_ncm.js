function submitConfirmar() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    if (f.ncm.value == "") {
        alert('Digite o número da Ncm.');
        f.ncm.focus();        
    } else if (f.descricao.value == "") {
        alert('Digite a descrição da Ncm.');
        f.descricao.focus();
    } else {
        if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        } //  
    }

} // submitConfirmar

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(id) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
} // submitAlterar

function submitExcluir(id) {
    debugger;
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'ncm';
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if
} // submitExcluir