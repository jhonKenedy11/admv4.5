function submitConfirmar() {
    
    var chkBox = document.getElementById('bloqueado');
    var status;
    if (chkBox.checked) {
        status = 'S';
    } else {
        status = 'N';
    }
    f = document.lancamento;
    f.bloqueado.value = status;
    f.mod.value = 'crm';
    f.form.value = 'classe';
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
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'classe';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'classe';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(classe_id) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'classe';
    f.submenu.value = 'alterar';
    f.classe.value = classe_id;
    f.submit();
} // submitAlterar

function submitExcluir(classe_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'crm';
        f.form.value = 'classe';
        f.submenu.value = 'exclui';
        f.classe.value = classe_id;
        f.submit();
    } // if
} // submitExcluir