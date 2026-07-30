// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'tipo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'tipo';
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente ' + f.submenu.value + ' este item',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, confirmar!',
        cancelButtonText: 'Cancelar'
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
    f.mod.value = 'cat';
    f.form.value = 'tipo';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(tipo) {

    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente Alterar este item',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'tipo';
        f.submenu.value = 'alterar';
        f.id.value = tipo;
        f.submit();
    }
    });
} // submitAlterar

function submitExcluir(tipo) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente Excluir este item',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'tipo';
        f.submenu.value = 'exclui';
        f.id.value = tipo;
        f.submit();
    }
    });
} // submitExcluir