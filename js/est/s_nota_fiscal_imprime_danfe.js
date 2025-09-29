function submitVoltar(formulario) {
    f = document.lancamento;
    switch( formulario ) {
        case 'pedido_venda_nf':
            f.form.value = 'pedido_venda_nf';
            f.mod.value = 'ped';
            break;
        case 'pedido_venda_gerente':
            f.form.value = 'pedido_venda_gerente';
            f.mod.value = 'ped';
            break;
        default:
            f.form.value = 'nota_fiscal';
            f.mod.value = 'est';
        }     
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function abrir(pag){
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}


