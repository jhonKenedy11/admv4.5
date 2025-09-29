// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'banco';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'banco';
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
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }

        f.submit(); // já estava
    }
    });
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

        swal.fire({
            title: 'Confirmação',
            text: 'Deseja realmente Alterar este item',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'banco';
        f.submenu.value = 'alterar';
        f.id.value = banco;
        f.submit();
    }
    });
} // submitAlterar

function submitExcluir(banco) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'banco';
        f.submenu.value = 'exclui';
        f.id.value = banco;
        f.submit();
    }
    });
} // submitExcluir