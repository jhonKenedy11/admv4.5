function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'anp';
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
    f.mod.value = 'est';
    f.form.value = 'anp';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'anp';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(anp_id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'anp';
    f.submenu.value = 'alterar';
    f.anp.value = anp_id;
    f.submit();
} // submitAlterar

function submitExcluir(anp_id) {
    if (confirm('Deseja realmente Excluir este ANP') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'anp';
        f.submenu.value = 'exclui';
        f.anp.value = anp_id;
        f.submit();
    } // if
} // submitExcluir