function submitCadastro(id) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_entrega';
    f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = 'cadastrar';
    f.submit();
} // fim submitVoltar

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_entrega';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_entrega';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        f.submenu.value = 'incluir'; }
    else{
        f.submenu.value = ''; 
    } // else
    f.submit();
} // fim submitConfirmar