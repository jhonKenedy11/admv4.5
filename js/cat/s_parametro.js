/**
 * JavaScript para administração dos parâmetros CAT
 * Padrão ADM v4.5 — alinhado ao módulo EST
 */

function submitVoltar() {
    document.lancamento.submenu.value = '';
    document.lancamento.submit();
}

function submitConfirmar() {
    var f = document.lancamento;
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente salvar este parâmetro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            if (f.submenu.value === 'cadastro' || f.submenu.value === 'cadastrar') {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
}

function submitCadastro() {
    var f = document.lancamento;
    f.submenu.value = 'cadastro';
    f.id.value = '';
    f.submit();
}

function submitAlterar(id) {
    var f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
}

function submitExcluir(id) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: 'Deseja realmente excluir este parâmetro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then(function (result) {
        if (result.isConfirmed) {
            var f = document.lancamento;
            f.submenu.value = 'excluir';
            f.id.value = id;
            f.submit();
        }
    });
}

function submitConsulta() {
    document.lancamento.submenu.value = 'consulta';
    document.lancamento.submit();
}

function submitLimparFiltro() {
    var filtro = document.getElementById('filtro_busca');
    if (filtro) {
        filtro.value = '';
    }
    document.lancamento.submenu.value = 'consulta';
    document.lancamento.submit();
}
