function submitVoltar(formulario) {
    f = document.ordemservico;
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.ordemservico;

    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        f.submenu.value = 'altera'; }
    else{
        f.submenu.value = ''; 
    } // else
    f.submit();
} // fim submitConfirmar