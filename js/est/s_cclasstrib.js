function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cclasstrib';
    if (f.cclasstrib.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite o código do CClassTrib.',
            icon: 'warning',
            timer: 1500
        });
        f.cclasstrib.focus();        
    } else if (f.nome.value == "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite o nome do CClassTrib.',
            icon: 'warning',
            timer: 1500
        });
        f.nome.focus();
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
    f.form.value = 'cclasstrib';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cclasstrib';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'cclasstrib';
    f.submenu.value = 'alterar';
    f.id.value = id;
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente alterar este CClassTrib?',
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

