function submitConfirmar() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        }
        else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } //  
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
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
} // submitAlterar

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'tabela_preco';
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if
} // submitExcluir

function submitDetalhe(id) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = '';
    f.letra.value = id;
    f.submit();
} // submitExcluir