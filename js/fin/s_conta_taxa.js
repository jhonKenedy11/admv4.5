function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'conta_taxa';
    f.submenu.value = '';
    f.submit();
}

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'conta_taxa';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        }
        else {
            f.submenu.value = 'altera';
        }
        f.submit();
    }
}

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'conta_taxa';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(id) {
    debugger;
    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'conta_taxa';
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    }
}

function submitExcluir(banco) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'conta_taxa';
        f.submenu.value = 'exclui';
        f.id.value = banco;
        f.submit();
    }
} 