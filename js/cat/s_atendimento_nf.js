function submitCadastraLancamentoFin(id) {
    debugger;

    f = document.lancamento;
    if(f.serieDocto.value == ''){
        alert("Preencher o campo Serie Documento.");
        return false;
    }
    if(f.numDocto.value == ''){ 
        alert("Preencher o campo Numero Documento.");
        return false;
    }

    var rows = document
            .getElementById("datatable-buttons-1")
            .getElementsByTagName("tr");

    vlrAcumulado = 0;
    for (row = 1; row < rows.length; row++) {
        var cells = rows[row].getElementsByTagName("td");
        var vlrParcela = cells[2].childNodes[1].value;

        vlrParcela = parseFloat(vlrParcela.replace(".","").replace(",","."));
        vlrAcumulado += vlrParcela
    }
    totalLanc = parseFloat(f.total.value.replace(".","").replace(",","."));

    if(vlrAcumulado > totalLanc ){
        alert("O total dos valores das parcelas difere do valor total do Lançamento.");
        return false;
    }

    if (confirm('Deseja realmente INCLUIR este FATURAMENTO ?') == true) {
        f.mod.value = 'cat';
        f.form.value = 'atendimento_nf';
        // PARCELAS 
        var rows = document
            .getElementById("datatable-buttons-1")
            .getElementsByTagName("tr");

         f.dadosParcelas.value = "";

        for (row = 1; row < rows.length; row++) {
            var cells = rows[row].getElementsByTagName("td");
            var field0 = cells[0].childNodes[0].data;
            var field1 = cells[1].childNodes[1].value;
            var field2 = cells[2].childNodes[1].value;
            var field3 = cells[3].childNodes[1].value;
            var field4 = cells[4].childNodes[1].value;
            var field5 = cells[5].childNodes[1].value;
            var field6 = cells[6].childNodes[1].value;
            f.dadosParcelas.value =
            f.dadosParcelas.value +
            "|" +
            field0 +  // Num Parcela 
            "*" +
            field1 + // Data parcela
            "*" +
            field2 + // Valor Parcela
            "*" +
            field3 + // Tipo Docto
            "*" +
            field4 + // Conta Recebimento
            "*" +
            field5 + //Situação Lançamento
            "*" +
            field6;
        }

        f.dadosFinanceiros.value = f.id.value + "|" 
        + f.serieDocto.value + "|" 
        + f.numDocto.value + "|" 
        + f.dataFechamento.value + "|" 
        + f.situacao.value + "|" 
        + f.cliente.value + "|" 
        + f.genero.value + "|" 
        + f.descCondPgto.value + "|" 
        + f.total.value + "|" 
        + f.centroCusto.value;

        f.id.value = id;
        f.submenu.value = 'lancAtendimentoFinanceiro';
        f.submit();
    }else{
        return false
    }

    
} // submitCadastraLancamentoFin

function submitCadastraNf(id) {
    debugger;

    f = document.lancamento;
    if(f.serieDoctoNf.value == ''){
        alert("Preencher o campo Serie Documento Nf.");
        return false;
    }
    if(f.numDoctoNf.value == ''){ 
        alert("Preencher o campo Numero Documento Nf.");
        return false;
    }

    if(f.modeloDocto.value == ''){ 
        alert("Preencher o campo Modelo Docto NF.");
        return false;
    }    

    if (confirm('Deseja realmente INCLUIR este FATURAMENTO ?') == true) {
        f.mod.value = 'cat';
        f.form.value = 'atendimento_nf';
        
        f.dadosNf.value = ""
        f.dadosNf.value = f.id.value + "|" 
        + f.serieDoctoNf.value + "|" 
        + f.numDoctoNf.value + "|" 
        + f.modeloDocto.value + "|" 
        + f.dataFechamento.value + "|" 
        + f.situacao.value + "|" 
        + f.cliente.value + "|"
        + f.clienteNome.value + "|" 
        + f.genero.value + "|" 
        + f.condPgto.value + "|" 
        + f.descCondPgto.value + "|" 
        + f.total.value + "|" 
        + f.idNatop.value + "|" 
        + f.centroCusto.value + "|"
        + f.obs.value;

        f.id.value = id;
        f.submenu.value = 'lancAtendimentoNf';
        
        f.submit();
    }else{
        return false
    }

    
} // submitCadastraNf

function submitAtual(id) {
    debugger;

    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.id.value = id;
    f.submenu.value = 'financeiro';
    f.submit();
} // fim submit

function submitVoltar() {
    f = document.lancamento;
    f.submenu.value = '';
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submit();
} // fim submitVoltar

function submitVoltarCadAtendimentoNf(idOs) {
    f = document.lancamento;
    f.submenu.value = '';
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.id.value = idOs;
    f.submenu.value = 'cadastrarNf';
    f.submit();
} // fim submitVoltar

function submitLetra() {
    debugger;
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numAtendimento.value;
    
    // situacao Atendimento  
    f.situacoesAtendimento.value = concatCombo(situacaoAtendimento);
    
    f.submit();
} // fim submitVoltar

// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}

function submitCadastrarAtendimentoNf(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';   
    f.id.value = id;
    f.submit();
}

function abrir(pag) {
    window.open(
      pag,
      "consulta",
      "toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes"
    );
  }
  