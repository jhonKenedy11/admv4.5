function submitConfirmar() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
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
    f.form.value = 'tabela_preco';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
} // submitAlterar

function submitExcluir(id) {
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            var f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'tabela_preco';
            f.submenu.value = 'exclui';
            f.id.value = id;
            f.submit();
        }
    });
} // submitExcluir

function submitDetalhe(id_tabela_preco) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = '';
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
} // submitExcluir

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}