function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'atividade';
         if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            }
            else {
                f.submenu.value = 'altera';
            }
            f.submit();
    } //  
} // submitConfirmar

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'atividade';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'atividade';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(atividade_id) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'atividade';
    f.submenu.value = 'alterar';
    f.atividade.value = atividade_id;
    f.submit();
} // submitAlterar

function submitExcluir(atividade_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'crm';
        f.form.value = 'atividade';
        f.submenu.value = 'exclui';
        f.atividade.value = atividade_id;
        f.submit();
    } // if
} // submitExcluir