function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'marca';
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
    f.form.value = 'marca';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'marca';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(marca_id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'marca';
    f.submenu.value = 'alterar';
    f.marca.value = marca_id;
    f.submit();
} // submitAlterar

function submitExcluir(marca_id) {
    if (confirm('Deseja realmente Excluir esta Marca') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'marca';
        f.submenu.value = 'exclui';
        f.marca.value = marca_id;
        f.submit();
    } // if
} // submitExcluir