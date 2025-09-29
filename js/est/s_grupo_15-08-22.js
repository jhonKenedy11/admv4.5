function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    if (f.id.value == "") {
        alert('Digite o Codigo do grupo.');
        f.id.focus();
    } else if (f.descricao.value == "") {
        alert('Digite a descrição do grupo.');
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
    f.form.value = 'grupo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro(grupo, nivel) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    f.opcao.value = 'grupo';
    f.submenu.value = 'cadastrar';
    f.grupoBase.value = grupo;
    f.nivel.value = nivel + 1;
    f.submit();
} // submitCadastro

function submitAlterar(grupo_id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    f.submenu.value = 'alterar';
    f.id.value = grupo_id;
    f.submit();
} // submitAlterar

function submitExcluir(grupo_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'grupo';
        f.submenu.value = 'exclui';
        f.id.value = grupo_id;
        f.submit();
    } // if
} // submitExcluir