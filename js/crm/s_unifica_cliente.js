/****
* UTILITARIOS
**/ 
function abrir(pag) {
    debugger;
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function submitConfirmar() {
    debugger;
    f = document.lancamento;
    f.submenu.value = '';
    if (confirm('Deseja realmente unifar esse cliente?') == true) {
        f.submenu.value = 'inclui';
    } // if
    f.submit();
} // fim submitConfirmar