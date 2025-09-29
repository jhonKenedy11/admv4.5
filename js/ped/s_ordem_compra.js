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
        alert('Selecione uma Conta!');
    } else {
        if (f.id.value == "") {
            alert('Selecione uma Natureza de Operação!');
            
        } else {
        if (f.id.value == "") {
            alert('Pedido sem itens cadastrado!');
        }else{    
            if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
                if (f.submenu.value == "cadastrar") {
                    f.submenu.value = 'inclui';
                } else {
                    f.submenu.value = 'altera';
                }
                f.submit();
            } //  
            } //  
        } //  
    }

} // submitConfirmarSmart

function submitConfirmar() {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma Conta!');
    } else {
        if (f.id.value == "") {
            alert('Pedido sem itens cadastrado!');
        }else{    
            if (confirm('Deseja realmente FINALIZAR este pedido') == true) {
                if (f.submenu.value == "cadastrar") {
                    f.submenu.value = 'inclui';
                } else {
                    f.submenu.value = 'altera';
                }
                f.submit();
            } //  
        } //  
    }

} // submitConfirmar

function submitDigitacao() {
    f = document.lancamento;
    f.submenu.value = 'digita';
    f.submit();
} // fim submitVoltar

function submitCalculaImpostos() {
    f = document.lancamento;
    f.submenu.value = 'calculaImpostos';
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
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numOrdemCompra.value + "|";
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

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitEstornar(id) {
    if (confirm('Deseja realmente Estornar este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'estorna';
        f.id.value = id;
        f.submit();
    } // if
} // submitEstornar

function submitBuscar() {
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
        
} // submitExcluir


function submitIncluirItem(){
    f = document.lancamento;
    // situacao lancamento
    if (f.pessoa.value == "") {
        alert('Selecione uma Conta!');
    } else {
    if (f.natop.value == "") {
        alert('Selecione uma Natureza de Operação!');

    } else {
        submitBuscar();
        f.itensPedido.value = '';
        myCheckbox = document.lancamento.elements["itemCheckbox"];
        if (typeof(myCheckbox.length)=="number"){
            for (var i=0;i<myCheckbox.length;i++){  
                 if (myCheckbox[i].checked == true){  
                     if(f.itensPedido.value == ''){
                         f.itensPedido.value = myCheckbox[i].value;
                     }else{
                         f.itensPedido.value = f.itensPedido.value + "|" + myCheckbox[i].value;
                     }//if
                 }//if
             }//for
        }else{
            if (myCheckbox.checked == true){  
                f.itensPedido.value = document.lancamento.elements["itemCheckbox"].value;
            }
        }
        f.submenu.value = 'cadastrarItem';
        f.submit();
        //alert('passou' + f.itensPedido.value);
        }
    }    
}

function submitIncluirItemQuant(){
    f = document.lancamento;
    f.itensPedido.value = '';
    var table = document.getElementById("datatable-buttons");
    var arr = new Array();
    var r = table.rows.length;
    for (i = 1; i < r; i++){
        var inputs = table.rows.item(i).getElementsByTagName("input");
        var x = parseFloat(inputs[1].value);
        if ((typeof x === 'number') && (x % 1 === 0)) {
             if(f.itensPedido.value == ''){
                 f.itensPedido.value = inputs[0].value + "*" + inputs[1].value;
             }else{
                 f.itensPedido.value = f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value;
             }//if
        }    
    }
    f.submenu.value = 'cadastrarItem';
    f.submit();
}

function submitIncluirItemQuantPreco(){
    debugger;
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma Conta!');
    } else {
            if (f.condPgto.value == "") {
                alert('Selecione uma Condição Pagamento!');
            } else {
                f.itensPedido.value = '';
                var table = document.getElementById("datatable-buttons");
                var r = table.rows.length;
                for (i = 1; i < r; i++){
                    var inputs = table.rows.item(i).getElementsByTagName("input");
                    var x = parseFloat(inputs[1].value);
                    if ((typeof x === 'number') && (x % 1 === 0)) {
                         if(f.itensPedido.value == ''){
                             f.itensPedido.value = inputs[0].value + "*" + inputs[1].value + "*" + inputs[2].value;
                         }else{
                             f.itensPedido.value = f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value + "*" + inputs[2].value;
                         }//if
                    }    
                }
                f.submenu.value = 'cadastrarItem';
                f.submit();
            }
    }
}


function submitExcluirItem(id, nrItem) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        //submitBuscar();
        f = document.lancamento;
        f.submenu.value = 'excluiItem';
        f.id.value = id;
        f.nrItem.value = nrItem;
        f.submit();
    } // if
} // submitExcluir

function abrir(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function submitEntregue(id) {
    if (confirm('Deseja realmente colocar como entregue o pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'entregue';
        f.id.value = id;
        f.submit();
    } // if
} // submitEntregue

function submitAgruparPedidos(){
    f = document.lancamento;
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var pedidos = '';
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("input");
      if (row.pedidoChecked.checked == true){
        pedidos = pedidos + "|" + row[0].id; 
      }
    }
    if (f.nome.value == "") {
        alert('Selecione um cliente!');
        f.submenu.value = '';
    } else {
      f.agrupar_pedidos.value = '';
      f.agrupar_pedidos.value = pedidos;
      f.submenu.value = 'agruparPedidos';
      f.submit();
    }
}
