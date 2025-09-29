function submitConfirmarSmart() {
    debugger
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione um Cliente.');
        return false;
    }
    
    
    if (f.condPgto.value == "" || f.condPgto.value == "0" ) {
        alert('Selecione uma Condição de Pagamento.');
        return false;
    }
        
    if (confirm('Deseja realmente ' + f.submenu.value + ' este Pedido?') == true) {
        if (f.submenu.value == "cadastrar") {

            var rows = document
            .getElementById("datatable-buttons-pecas")
            .getElementsByTagName("tr");    

            f.dadosPecas.value = "";

            for (row = 1; row < rows.length; row++) {
                var cells = rows[row].getElementsByTagName("td");
                if(cells[0].childNodes[1].checked == true){
                    var field1 = cells[1].childNodes[0].data; //CodProduto
                    var field2 = cells[2].childNodes[0].data; // CodNota
                    var field3 = cells[3].childNodes[0].data; // Descricao
                    var field4 = cells[4].childNodes[0].data;
                    var field5 = cells[5].childNodes[0].data;
                    var field6 = cells[6].childNodes[0].data;
                    var field7 = cells[7].childNodes[1].value;
                    var field8 = cells[8].childNodes[1].value;
                    var field9 = cells[9].childNodes[1].value;
                    var field10 = cells[10].childNodes[1].value;
                    var field11 = cells[11].childNodes[1].value;
                    f.dadosPecas.value =
                    f.dadosPecas.value +
                    "|" +
                    field1 +  // Num Parcela 
                    "*" +
                    field2 + // Data parcela
                    "*" +
                    field3 + // Valor Parcela
                    "*" +
                    field4 + // Tipo Docto
                    "*" +
                    field5 + // Conta Recebimento
                    "*" +
                    field6 + //Situação Lançamento
                    "*" +
                    field7 +
                    "*" +
                    field8 + // Conta Recebimento
                    "*" +
                    field9 + //Situação Lançamento
                    "*" +
                    field10 + //Situação Lançamento
                    "*" +
                    field11;
                   
                }
                
            }
            //console.log(f.dadosPecas.value);

            if(f.dadosPecas.value == ''){
                alert('Nenhum item selecionado!');
                return false;
            }

            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } 
        
} // submitConfirmarSmart

function submitConfirmaOrdemCompra() {
    debugger
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione um Cliente.');
        return false;
    }
    
    
    if (f.condPgto.value == "" || f.condPgto.value == "0" ) {
        alert('Selecione uma Condição de Pagamento.');
        return false;
    }
        
    if (confirm('Deseja realmente ' + f.submenu.value + ' este Pedido?') == true) {

            var rows = document
            .getElementById("datatable-buttons-pecas")
            .getElementsByTagName("tr");

            f.dadosPecas.value = "";

            for (row = 1; row < rows.length; row++) {
                var cells = rows[row].getElementsByTagName("td");
                if(cells[0].childNodes[1].checked == true){
                    var field1 = cells[1].childNodes[0].data; //CodProduto
                    var field2 = cells[2].childNodes[0].data; // CodNota
                    var field3 = cells[3].childNodes[0].data; // Descricao
                    var field4 = cells[4].childNodes[0].data;
                    var field5 = cells[5].childNodes[0].data;
                    var field6 = cells[6].childNodes[0].data;
                    var field7 = cells[7].childNodes[1].value;
                    var field8 = cells[8].childNodes[1].value;
                    var field9 = cells[9].childNodes[1].value;
                    var field10 = cells[10].childNodes[1].value;
                    var field11 = cells[11].childNodes[1].value;
                    f.dadosPecas.value =
                    f.dadosPecas.value +
                    "|" +
                    field1 +  // Num Parcela
                    "*" +
                    field2 + // Data parcela
                    "*" +
                    field3 + // Valor Parcela
                    "*" +
                    field4 + // Tipo Docto
                    "*" +
                    field5 + // Conta Recebimento
                    "*" +
                    field6 + //Situação Lançamento
                    "*" +
                    field7 +
                    "*" +
                    field8 + // Conta Recebimento
                    "*" +
                    field9 + //Situação Lançamento
                    "*" +
                    field10 + //Situação Lançamento
                    "*" +
                    field11;
                }
            }

            //console.log(f.dadosPecas.value);

            if(f.dadosPecas.value == ''){
                alert('Nenhum item selecionado!');
                return false;
            }

            f.submenu.value = 'geraOrdemCompra';
            
            f.submit();
    } 
        
} // submitConfirmaOrdemCompra


function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_new';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar


function abrir(pag, form=null)
{
    debugger
    screenWidth = 750;
    screenHeight = 650;  
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function calculaTotal(id = '', e = '', campo = '', auto = ''){
    debugger;

    var f = document.lancamento;

    if (e != ''){
        var checkId = "check"+id;      
        var qtdePedidoId = "qtdePedido"+id;
        var vlrUnitarioId = "unitario"+id;
        var percDescontoId = "percDesconto"+id;
        var vlrDescontoId = "vlrDesconto"+id;
        var totalId = "totalItem"+id;      
        
        var qtdePedido   = document.getElementsByName(qtdePedidoId)[0].value; 
        var vlrUnitario  = document.getElementsByName(vlrUnitarioId)[0].value 
        var percDesconto = document.getElementsByName(percDescontoId)[0].value
        var vlrDesconto  = document.getElementsByName(vlrDescontoId)[0].value

        qtdePedido      = parseFloat(qtdePedido.replace(".","").replace(",","."));
        vlrUnitario     = parseFloat(vlrUnitario.replace(".","").replace(",","."));
        percDesconto    = parseFloat(percDesconto.replace(".","").replace(",","."));
        vlrDesconto     = parseFloat(vlrDesconto.replace(".","").replace(",","."));

        if(auto == true){
            if(qtdePedido == '' || qtdePedido == '0,00'){
                alert("Insira uma Qtde Pedido.");
                return false
            }
        }
        

        totalItem     = (qtdePedido * vlrUnitario);

        if(campo == 'desconto'){
            percDesconto  = ((vlrDesconto * 100)/totalItem)
    
            // calculo desconto pelo valor unitário
            percDesconto  = ((vlrDesconto * 100)/totalItem)
            
        }else{
            vlrDesconto = ((totalItem*percDesconto)/100)
        }
        totalItem     = (qtdePedido * vlrUnitario) - vlrDesconto;
        document.getElementsByName(percDescontoId)[0].value = currencyFormat(percDesconto)
        document.getElementsByName(vlrDescontoId)[0].value = currencyFormat(vlrDesconto)
        document.getElementsByName(totalId)[0].value = currencyFormat(totalItem)

        
        // auto checked 
        if(auto == true){
            document.getElementsByName(checkId)[0].checked = true;
        }

    }
   
  //  var totalPecasUtilizada = f.valorPecasUtilizada.value == '' ? '0,00' : f.valorPecasUtilizada.value;
    var frete               = f.valorFrete.value == '' ? '0,00' : f.valorFrete.value;
    var despAcessorias      = f.despAcessorias.value == '' ? '0,00' : f.despAcessorias.value;
   // var desconto            = f.valorDesconto.value == '' ? '0,00' : f.valorDesconto.value;
    var total     = 0;

    //totalPecasUtilizada = parseFloat(totalPecasUtilizada.replace(".","").replace(",","."));
    frete               = parseFloat(frete.replace(".","").replace(",","."));
    despAcessorias      = parseFloat(despAcessorias.replace(".","").replace(",","."));
  //  desconto            = parseFloat(desconto.replace(".","").replace(",","."));


    var rows = document
            .getElementById("datatable-buttons-pecas")
            .getElementsByTagName("tr");

    totalPecas = 0;
    for (row = 1; row < rows.length; row++) {
        var cells = rows[row].getElementsByTagName("td");
        if(cells[0].childNodes[1].checked == true){
            var vlrPeca = cells[11].childNodes[1].value;
            vlrPeca = parseFloat(vlrPeca.replace(".","").replace(",","."));
            totalPecas += vlrPeca
        }
        
    }
    

    total  = ((frete + despAcessorias + totalPecas)); 

    

    if(total === 'NaN'){
        total = 0
    }else if(total == undefined){
        total = 0
    }else if (total === 'Infinity'){
        total = 0
    }else{

    }
    f.valorTotal.value = currencyFormat(total);
    f.totalPecasUtilizada.value = currencyFormat(totalPecas);
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

function submitCadastrarAtendimentoNf(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';
    f.id.value = id;
    f.submit();
}

function checkboxAll(checkbox) {
    let valorTotal = 0;
    
    var checkboxes = document.querySelectorAll('.checkbox');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkbox.checked;
    }

    var cells = document.querySelectorAll('td input[type="checkbox"]:checked');

    cells.forEach(function (checkbox) {
        var trElement = checkbox.closest('tr');
        var temp = parseFloat(trElement.children[11].children[0].value.replace(".", "").replace(",", "."))
        valorTotal += temp;
        console.log(valorTotal);
    });

    var freteVlr = document.getElementById('valorFrete').value !== '' ? 
                    parseFloat(document.getElementById('valorFrete').value.replace(".", "").replace(",", ".")) : 0;

    var despAcessoriasVlr = document.getElementById('despAcessorias').value ? 
                            parseFloat(document.getElementById('despAcessorias').value.replace(".", "").replace(",", ".")) : 0;

    valorTotal = valorTotal + freteVlr + despAcessoriasVlr;

    var valorTotalFormatado = valorTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    document.getElementById('valorTotal').value = valorTotalFormatado;
    document.getElementById('totalPecasUtilizada').value = valorTotalFormatado;
}


