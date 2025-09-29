// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'acessorio';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'acessorio';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }

        f.submit(); // já estava
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'acessorio';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(acessorio) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'acessorio';
        f.submenu.value = 'alterar';
        f.id.value = acessorio;
        f.submit();
    }
} // submitAlterar

function submitExcluir(acessorio) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'acessorio';
        f.submenu.value = 'exclui';
        f.id.value = acessorio;
        f.submit();
    }
} // submitExcluir