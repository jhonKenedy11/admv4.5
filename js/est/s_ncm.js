function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    if (f.ncm.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite o número da Ncm.',
            icon: 'warning',
            timer: 1500
        });
        f.ncm.focus();        
    } else if (f.descricao.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite a descrição da Ncm.',
            icon: 'warning',
            timer: 1500
        });
        f.descricao.focus();
    }else {
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
            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }
        f.submit();
        }
    });
    }
}// submitConfirmar

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'ncm';
    f.submenu.value = 'alterar';
    f.id.value = id;
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente alterar este Ncm',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submit();
        }
    });
} // submitAlterar

function submitExcluir(id) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente Excluir este Ncm',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'ncm';
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
        }
    });
} // submitExcluir