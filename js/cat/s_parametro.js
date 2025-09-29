// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'parametro';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'parametro';
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'parametro';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(parametro) {

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Alterar este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'parametro';
        f.submenu.value = 'alterar';
        f.id.value = parametro;
        f.submit();
        }
    });
} // submitAlterar

function submitExcluir(parametro) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'parametro';
        f.submenu.value = 'exclui';
        f.id.value = parametro;
        f.submit();
        }
    });
} // submitExcluir