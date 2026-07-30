function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    if (f.descricao.value.trim() === "") {
        swal.fire({
            title: 'Atenção',
            text: 'Digite a descrição do Grupo.',
            icon: 'warning',
            timer: 1500
        });
        f.descricao.focus();
    }
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente ' + f.submenu.value + ' este Grupo',
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
} // submitConfirmar

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro(grupo, nivel) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'grupo';
    f.opcao.value = 'grupo';
    f.submenu.value = 'cadastrar';
    f.grupoBase.value = grupo;
    f.nivel.value = nivel + 1;
    f.submit();
} // submitCadastro

function submitAlterar(grupo_id) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente alterar este Grupo',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'grupo';
            f.submenu.value = 'alterar';
            f.id.value = grupo_id;
            f.submit();
        }
    });
} // submitAlterar

function submitExcluir(grupo_id) {
    swal.fire({
        title: 'Atenção',
        text: 'Deseja realmente excluir este Grupo',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'grupo';
        f.submenu.value = 'exclui';
        f.id.value = grupo_id;
        f.submit();
        }
    });
} // submitExcluir