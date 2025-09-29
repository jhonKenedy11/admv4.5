function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'cond_pgto';
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
                }
                else {
                    f.submenu.value = 'altera';
                }
                f.submit();
            }
         });
} // submitConfirmar

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'cond_pgto';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'cond_pgto';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(cond_pgto_id) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'cond_pgto';
    f.submenu.value = 'alterar';
    f.id.value = cond_pgto_id;
    f.submit();
} // submitAlterar

function submitExcluir(cond_pgto_id) {
    debugger;
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
        f.mod.value = 'util';
        f.form.value = 'cond_pgto';
        f.submenu.value = 'exclui';
        f.id.value = cond_pgto_id;
        f.submit();
        }
    });
} // submitExcluir