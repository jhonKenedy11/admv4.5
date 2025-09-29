document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.key == "Enter") {
        submitLetra();
    }// fim evento enter
});// fim addEventListener


function submitSearch() {
    debugger;
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (prom == '') ){
        alert('Faça algum filtro de pesquisa.');
    }else{
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        //alert(f.pesq.value);
        f.submit();
    }
        
} 


function submitConfirmarSmart() {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione um Cliente.');
        return false;
    }
    
    
    if (f.condPgto.value == "" || f.condPgto.value == "0" ) {
        alert('Selecione uma Condição de Pagamento.');
        return false;
    }
    if (f.catEquipamentoId.value == "") {
        alert('Selecione um Equipamento.');
        return false;
    }
        
    if (confirm('Deseja realmente ' + f.submenu.value + ' este Atendimento') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } 
        
} // submitConfirmarSmart


function submitDigitacao() {
    f = document.lancamento;
    f.submenu.value = 'digita';
    f.submit();
} // fim submitVoltar


function submitVoltar() {
    f = document.lancamento;
    f.submenu.value = '';
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

function submitCadastro() {
    debugger;
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(id, situacao, pessoa) {
    f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.situacao.value = situacao;
    f.pessoa.value = pessoa;
    f.submit();
} // submitAlterar

function submitCancelar(id) {
    if (confirm('Deseja realmente Cancelar este Atendimento') == true) {
        f = document.lancamento;
        f.submenu.value = 'cancela';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitExcluirPeca(idPeca) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        debugger;
        f = document.lancamento;
        f.submenu.value = 'excluiPeca';
        f.idPecas.value = '';
        f.idPecas.value = idPeca;
        f.submit();
    } // if
} // submitExcluir

function submitExcluirServico(idServico) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        debugger;
        f = document.lancamento;
        f.submenu.value = 'excluiServico';
        f.idServicos.value = '';
        f.idServicos.value = idServico;
        f.submit();
    } // if
} // submitExcluir

function abrir(pag, form=null)
{
    debugger
    screenWidth = 750;
    screenHeight = 650;
    if(form == 'produto'){
        if(document.lancamento.pessoa.value == ''){
            alert("Selecione o Cliente antes de fazer a pesquisa");
            return false;
        }
        if(document.lancamento.catEquipamentoId.value == ''){
            alert("Selecione o Equipamento antes de fazer a pesquisa");
            return false;
        }
        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&idTipoAtendimento='+document.lancamento.catTipoId.value;
        pag = '';
        pag = newPage;
    }
    if(form == 'servicos'){
        if(document.lancamento.pessoa.value == ''){
            alert("Selecione o Cliente antes de fazer a pesquisa");
            return false;
        }
        if(document.lancamento.catEquipamentoId.value == ''){
            alert("Selecione o Equipamento antes de fazer a pesquisa");
            return false;
        }

        screenWidth = 930;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function calculaTotal(){
    debugger;
    var f = document.lancamento;
    var pecas     = f.valorPecas.value == '' ? '0,00' : f.valorPecas.value;
    var servicos  = f.valorServicos.value == '' ? '0,00' : f.valorServicos.value;
    var visita     = f.valorVisita.value == '' ? '0,00' : f.valorVisita.value;
    var desconto  = f.valorDesconto.value == '' ? '0,00' : f.valorDesconto.value;
    var total     = 0;

    pecas     = parseFloat(pecas.replace(".","").replace(",","."));
    servicos  = parseFloat(servicos.replace(".","").replace(",","."));
    visita    = parseFloat(visita.replace(".","").replace(",","."));
    desconto  = parseFloat(desconto.replace(".","").replace(",","."));



    total     = ((pecas + servicos + visita) - desconto); 
    if(total == NaN){
        total = 0
    }else if(total == undefined){
        total = 0
    }else if (total == Infinity){
        total = 0
    }else{

    }
    f.valorTotal.value = currencyFormat(total);
}

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}

function confirmacaoDesconto(){
    f = document.lancamento;
    if(f.valorDesconto.value != '0' || f.valorDesconto.value != ''){
      if(confirm("AVISO. Esse desconto será dividido no desconto de todos os itens, "+
              "eliminando qualquer desconto já aplicado direto no item. "+
              "Deseja confirmar esse novo desconto geral?")== true){
                //f.form.value = 'atendimento';
                //f.submenu.value = 'recalcularDesconto';
                //f.submit();
  
      }else{
        return false;
      }
    }
    
  }

  function editarModalPeca(e){
                
    var linha = $(e).closest("tr");

    var id = linha.find("td:eq(0)").text().trim(); 
    var codigo = linha.find("td:eq(1)").text().trim(); 
    var descricao = linha.find("td:eq(2)").text().trim(); 
    var unidade = linha.find("td:eq(3)").text().trim();        
    var quantidade = linha.find("td:eq(4)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(5)").text().trim(); 
    var percDesconto = linha.find("td:eq(6)").text().trim();
    var vlrDesconto = linha.find("td:eq(7)").text().trim();
    var totalitem = linha.find("td:eq(8)").text().trim();
            
    $("#mIdPeca").val(id);
    $("#mCodPeca").val(codigo);
    $("#mDescPeca").val(descricao);
    $("#mUniPeca").val(unidade);
    $("#mQtdePeca").val(quantidade);
    $("#mVlrUniPeca").val(vlrUnitario);
    $("#mPercDescPeca").val(percDesconto);
    $("#mDescontoPeca").val(vlrDesconto);
    $("#mTotalPeca").val(totalitem);  
}

function editarModalServico(e){
                
    var linha = $(e).closest("tr");

    var codigo = linha.find("td:eq(0)").text().trim(); 
    var descricao = linha.find("td:eq(1)").text().trim(); 
    var unidade = linha.find("td:eq(2)").text().trim();        
    var quantidade = linha.find("td:eq(3)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(4)").text().trim();
    var totalitem = linha.find("td:eq(5)").text().trim();
            
    $("#mIdServico").val(codigo);
    $("#mDescServico").val(descricao);
    $("#mUniServico").val(unidade);
    $("#mQtdeServico").val(quantidade);
    $("#mVlrUniServico").val(vlrUnitario);
    $("#mTotalServico").val(totalitem);  
}



function submitAlteraPeca(){
    f = document.lancamento;
    f.letra_peca.value = '';
    f.letra_peca.value = f.mIdPeca.value + "|" + f.mCodPeca.value + "|" + f.mDescPeca.value + "|" + f.mUniPeca.value + 
    "|" + f.mQtdePeca.value + "|" + f.mVlrUniPeca.value + "|" + f.mPercDescPeca.value + "|" + f.mDescontoPeca.value +
    "|" + f.mTotalPeca.value;
    f.submenu.value = 'alteraPeca';
    f.submit()
}

function submitAlteraServico(){
    f = document.lancamento;
    f.letra_servico.value = '';
    f.letra_servico.value = f.mIdServico.value + "|" + f.mDescServico.value + "|" + f.mUniServico.value + 
    "|" + f.mQtdeServico.value + "|" + f.mVlrUniServico.value +"|" + f.mTotalServico.value;
    f.submenu.value = 'alteraServico';
    f.submit()
}

function calculaTotalModal(campo = '', modal=''){
    debugger;
    var f = document.lancamento;
    if(modal == 'pecas'){
        var vlrQtde     = f.mQtdePeca.value ;
        var unitario    = f.mVlrUniPeca.value;
        var desconto    = campo != 'desconto' ? desconto = "0,00" : desconto = f.mDescontoPeca.value;
        var vlrPercdesconto = campo == 'desconto' ? vlrPercdesconto  = "0,00" : vlrPercdesconto = f.mPercDescPeca.value;

        desconto         = parseFloat(desconto.replace(".","").replace(",","."))
        vlrPercdesconto  = parseFloat(vlrPercdesconto.replace(".","").replace(",","."))
    }else{
        var vlrQtde     = f.mQtdeServico.value ;
        var unitario    = f.mVlrUniServico.value;
    }
    
    var total     = 0;

    vlrQtde          = parseFloat(vlrQtde.replace(".","").replace(",","."))
    unitario         = parseFloat(unitario.replace(".","").replace(",","."))
    

    totalItem     = (vlrQtde * unitario);
    if(modal == 'pecas'){
        if(campo == 'desconto'){
            vlrPercdesconto  = ((desconto * 100)/totalItem)

            // calculo desconto pelo valor unitário
            vlrPercdescontoItem  = ((desconto * 100)/totalItem)
            
        }else{
            desconto = ((totalItem*vlrPercdesconto)/100)
        
        }
        resultTotal = (totalItem - desconto);
        resultPerc = currencyFormat(vlrPercdesconto);
        resultDesc = currencyFormat(desconto);
    }else{
        resultTotal = totalItem
    }
    
    total = currencyFormat(resultTotal);

    if(total === NaN){
        total = 0
    }else if(total === undefined){
        total = 0
    }else if (total === Infinity){
        total = 0
    }else{

    }
    if(modal == 'pecas'){
        f.mTotalPeca.value = total;
        f.mDescontoPeca.value = resultDesc;
        f.mPercDescPeca.value = resultPerc;
    }else{
        f.mTotalServico.value = total;
    }
}

function submitCadastrarAtendimentoNf(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';
    f.id.value = id;
    f.submit();
}

function submitCadastrarAtendimentoPedido(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_pedido';
    f.submenu.value = 'cadastrar';
    f.id.value = id;
    f.submit();
}
