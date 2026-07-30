function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cst_ibs_cbs';
    if (f.cst.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite o código do CST.',
            icon: 'warning',
            timer: 1500
        });
        f.cst.focus();        
    } else if (f.descricao.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite a descrição do CST.',
            icon: 'warning',
            timer: 1500
        });
        f.descricao.focus();
    } else {
        swal.fire({
            title: 'Atenção',
            text: 'Deseja realmente ' + f.submenu.value + ' este item?',
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
}

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cst_ibs_cbs';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cst_ibs_cbs';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cst_ibs_cbs';
    f.submenu.value = 'alterar';
    f.id.value = id;
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente alterar este CST?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submit();
        }
    });
}

