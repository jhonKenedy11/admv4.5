function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'unidade';
    if (f.unidade.value.trim() === "") {
        swal.fire({
            title: 'Atenção',
            text: 'Informe a sigla da unidade.',
            icon: 'warning',
            timer: 1500
        });
        f.unidade.focus();
        return;
    }
    if (f.descricao.value.trim() === "") {
        swal.fire({
            title: 'Atenção',
            text: 'Informe a descrição da unidade.',
            icon: 'warning',
            timer: 1500
        });
        f.descricao.focus();
        return;
    }
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente ' + f.submenu.value + ' esta Unidade?',
        icon: 'question',
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

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'unidade';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'unidade';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(unidade_id) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente alterar esta Unidade?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'unidade';
            f.submenu.value = 'alterar';
            f.id.value = unidade_id;
            f.submit();
        }
    });
}

function submitExcluir(unidade_id) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente excluir esta Unidade?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'unidade';
            f.submenu.value = 'exclui';
            f.id.value = unidade_id;
            f.submit();
        }
    });
}
