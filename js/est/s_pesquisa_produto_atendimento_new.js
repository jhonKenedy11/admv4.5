


function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}



function montaLetra() {
    l = document.lancamento;
    var quant = document.getElementsByName('quant');
    var fora = document.getElementsByName('fora');
    var valueQuant = "F";
    if (quant[0].checked) {
        valueQuant = "T";
    }
    var valueFora = "F";
    if (fora[0].checked) {
        valueFora = "T";
    }
    l.letra.value = l.produtoNome.value + "|" + l.grupo.value + "|" + l.codFabricante.value + "|" + l.localizacao.value + "|" + valueQuant + "|" + valueFora;
}// submitLetra


function submitLetra() {
    f = document.lancamento;
    var quant = document.getElementsByName('quant');
    var fora = document.getElementsByName('fora');
    if ((f.codFabricante.value == '') && (f.produtoNome.value == '') && (f.grupo.value == '') && (f.localizacao.value == '') && (!quant[0].checked) && (!fora[0].checked)) {
        alert('Digite algum Filtro de pesquisa.');
    } else {
        f.submenu.value = 'letra';
        montaLetra();
        f.submit();
    }


}
function submitLetraPesquisa(codigo = null, codFabricante = null) {
    debugger;
    f = document.lancamento;

    if ((codigo == null) && (f.codFabricante.value == '') && (f.produtoNome.value == '') && (f.grupo.value == '') && (f.localizacao.value == '')) {
        alert('Digite algum Filtro de pesquisa.');
    } else {
        f.submenu.value = 'letra';
        var valueQuant = "F";
        var valueFora = "F";

        if (codigo != null) {
            f.codigo.value = codigo;
        }

        if (codFabricante != null) {
            f.codFabricante.value = codFabricante;
        }

        f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "|" + f.localizacao.value + "|" + valueQuant + "|" + valueFora;
        f.submit();
    }
}




// abre janela de consulta
function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechar() {
    window.opener.location.reload();
    window.close();
}



//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaAtendimento(e) {
    debugger;
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(0)").text().trim(); 
    var codFabricante    = linha.find("td:eq(1)").text().trim(); 
    var codNota          = linha.find("td:eq(2)").text().trim(); 
    var descricaoProduto = linha.find("td:eq(3)").text().trim(); 
    var unidade          = linha.find("td:eq(4)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(5)").text().trim();
    
    if(document.lancamento.acao.value == 'alterar'){
        f.codProduto.value        = id;
        f.descProduto.value       = descricaoProduto  
    }else{
        f.codProduto.value        = id;
        f.codFabricante.value     = codFabricante;
        f.codProdutoNota.value    = codNota 
        f.uniProduto.value        = unidade
        f.descProduto.value       = descricaoProduto  
        f.vlrUnitarioPecas.value  = vlrUnitario 
    }
      
    
    window.close();
}

function calculaTotalProdutoAtendimento(codigo){
    debugger
    var unitarioId = "unitario"+codigo;
    var quantId = "quant"+codigo;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;

    quantValue = document.getElementsByName(quantId)[0].value;

    //unitarioValue = unitarioProduto;

    quantValue          = parseFloat(quantValue.replace(".", "").replace(",", "."));
    vendaValue      = parseFloat(vendaValue.replace(".", "").replace(",", "."));

    totalItem  = (vendaValue * quantValue);
    total = currencyFormat(totalItem);

    if (total === "NaN" || total === "NaN"){
        document.getElementById(quantId).value = 0;
       
    }else if(total === "Infinity" || total === "Infinity"){
        document.getElementById(quantId).value = 0;
    }else{
        document.getElementById(unitarioId).value = total;
    }
}

function calculaPercProdutoAtendimento(codigo, campo) {
    debugger
    var f = document.lancamento;    

    var quantId = "quant"+codigo;
    quantValue = document.getElementsByName(quantId)[0].value;

    var unitarioId = "unitario"+codigo;
    unitarioValue = document.getElementsByName(unitarioId)[0].value;

    var percDescontoItemId = "percDescontoItem"+codigo;
    percDescontoItemValue = document.getElementsByName(percDescontoItemId)[0].value;

    var descontoItemId = "descontoItem"+codigo;
    descontoItemValue = document.getElementsByName(descontoItemId)[0].value;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;
    
  
    descontoItemValue      = parseFloat(descontoItemValue.replace(".", "").replace(",", "."));
    percDescontoItemValue  = parseFloat(percDescontoItemValue.replace(".", "").replace(",", "."));
    quantValue          = parseFloat(quantValue.replace(".", "").replace(",", "."));
    vendaValue   = parseFloat(vendaValue.replace(".", "").replace(",", "."));
  
    totalItem  = (vendaValue * quantValue);
  
    if(campo == 'desconto'){
        percDescontoItemValue = ((descontoItemValue * 100)/totalItem)
    }else{
        descontoItemValue = ((totalItem*percDescontoItemValue)/100)
  
    }
    resultTotal = (totalItem - descontoItemValue);
    resultPerc = currencyFormat(percDescontoItemValue);
    resultDesc = currencyFormat(descontoItemValue);
    total = currencyFormat(resultTotal);
  
  
    if (resultPerc === "NaN" || resultDesc === "NaN"){
        document.getElementById(descontoItemId).value = 0;
        document.getElementById(percDescontoItemId).value = 0;
       
    }else if(resultPerc === "Infinity" || resultDesc === "Infinity"){
        document.getElementById(descontoItemId).value = 0;
        document.getElementById(percDescontoItemId).value = 0;
    }else{
        document.getElementById(descontoItemId).value = resultDesc;
        document.getElementById(percDescontoItemId).value = resultPerc;
        document.getElementById(unitarioId).value = total;
    }
  }


function submitLetraModal() {
    if (document.lancamento.desc.value == '') {
        alert('Preencha o campo para a pesquisa.');
        return false;
    }


    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {

            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function (response) {

            var result = $('<div />').append(response).find('#datatable').html();
            $("#datatable").html(result);
        }
    });
    return false;

}
