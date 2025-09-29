<script language=javascript>

// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'banco';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'banco';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }

//        f.submit();
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'banco';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(banco) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'banco';
        f.submenu.value = 'alterar';
        f.id.value = banco;
        f.submit();
    }
} // submitAlterar

function submitExcluir(banco) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'banco';
        f.submenu.value = 'exclui';
        f.id.value = banco;
        f.submit();
    }
} // submitExcluir


</script>

