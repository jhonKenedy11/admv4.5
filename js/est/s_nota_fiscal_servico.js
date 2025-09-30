function submitPesquisa() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_servico';
    f.submenu.value = 'mostra';
    f.submit();
}

function submitCadastro() {
    $('#modalServicos').modal('show');
}

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}
