function montaLetraConsulta(){
    debugger;
    f = document.lancamento;
    f.letra.value = f.codPedido.value + "|" + f.dataIni.value + "|" + f.dataFim.value + "|"  + f.codProduto.value + "|" + f.pessoa.value;
    // situacao  
    f.situacaoSelected.value = concatCombo(situacao);
    // ccusto
    f.centroCustoSelected.value = concatCombo(ccusto);
    //motivo
    f.motivoSelected.value = concatCombo(motivo);
     // vendedor
    f.vendedorSelected.value = concatCombo(vendedor);
    // condPagamento
    f.condPagamentoSelected.value = concatCombo(condPag);
}
// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}

function relatorioVendas(tipoRel){
    debugger;
    montaLetraConsulta();
    f.tipoRelatorio.value = tipoRel;
    window.open('index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioVendas&letra=' + f.letra.value + 
                '&situacaoSelected='+f.situacaoSelected.value+'&centroCustoSelected='+f.centroCustoSelected.value + '&tipoRelatorio='+f.tipoRelatorio.value+
                '&motivoSelected='+f.motivoSelected.value+'&vendedorSelected='+f.vendedorSelected.value+'&condPagamentoSelected='+f.condPagamentoSelected.value, 
                'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function relatorioFaturaSintetico(){
    debugger;
    montaLetraConsulta();
    window.open('index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=relatorioFaturaSintetico&letra=' + f.letra.value + 
                '&situacaoSelected='+f.situacaoSelected.value+'&centroCustoSelected='+f.centroCustoSelected.value + '&tipoRelatorio='+f.tipoRelatorio.value+
                '&motivoSelected='+f.motivoSelected.value+'&vendedorSelected='+f.vendedorSelected.value+'&condPagamentoSelected='+f.condPagamentoSelected.value, 
                'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function relatorioFaturaAnalitico(){
    debugger;
    montaLetraConsulta();
    window.open('index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=&letra=' + f.letra.value + 
                '&situacaoSelected='+f.situacaoSelected.value+'&centroCustoSelected='+f.centroCustoSelected.value + '&tipoRelatorio='+f.tipoRelatorio.value+
                '&motivoSelected='+f.motivoSelected.value+'&vendedorSelected='+f.vendedorSelected.value+'&condPagamentoSelected='+f.condPagamentoSelected.value, 
                'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}
