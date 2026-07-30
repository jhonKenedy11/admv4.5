//document.addEventListener('keydown', function (event) {
//    // evento pressionar ENTER
//    if (event.key == "Enter") {
//        submitLetra();
//    }// fim evento enter
//});// fim addEventListener

function submitCadastraNf(id) {
     

    f = document.lancamento;

    var nf =  document.getElementById("numNf");
    var serie =  document.getElementById("serie");
    if (numNf.value == '') {
        swal.fire({
            text: 'Preencha campo NF',
            icon: "warning",
            confirmButtonText: "Ok"
        });
        return false
    } else if (serie.value == '') {
        swal.fire({
            text: 'Preencha campo SERIE',
            icon: "warning",
            confirmButtonText: "Ok"
        });
        return false
    } else {
    
        f.mod.value = 'coc';
        f.form.value = 'ordem_compra';

        var rows = document
            .getElementById("datatable-buttons-1")
            .getElementsByTagName("tr");

        var $dadosFinanceiros = "";

        for (row = 1; row < rows.length; row++) {
            var cells = rows[row].getElementsByTagName("td");
            var field0 = cells[0].childNodes[0].data;
            var field1 = cells[1].childNodes[1].value;
            var field2 = cells[2].childNodes[1].value;
            var field3 = cells[3].childNodes[1].value;
            var field4 = cells[4].childNodes[1].value;
            var field5 = cells[5].childNodes[1].value;
            var field6 = cells[6].childNodes[1].value;
            $dadosFinanceiros =
            $dadosFinanceiros +
            "|" +
            field0 +
            "*" +
            field1 +
            "*" +
            field2 +
            "*" +
            field3 +
            "*" +
            field4 +
            "*" +
            field5 +
            "*" +
            field6;
        }

        f.dadosFinanceiros.value = $dadosFinanceiros;    

        var rows = document
            .getElementById("datatable-buttons-2")
            .getElementsByTagName("tr");

        var $itenscotacao = "";
        var valido = "S";

        for (row = 1; row < rows.length; row++) {
            var cells = rows[row].getElementsByTagName("td");
            var field0 = cells[0].childNodes[0].data;
            var field1 = cells[1].childNodes[0].data;
            var field2 = cells[2].childNodes[0].data;
            var field3 = cells[3].childNodes[0].data;
            var field4 = cells[4].childNodes[0].value;
            if (field4 == "") {
                valido = "N";
            } 
            var field5 = cells[5].childNodes[0].value;
            var field6 = cells[6].childNodes[0].value;
            var field7 = cells[7].childNodes[0].value;
            $itenscotacao =
            $itenscotacao +
            "|" +
            field0 +
            "*" +
            field1 +
            "*" +
            field2 +
            "*" +
            field3 +
            "*" +
            field4 +
            "*" +
            field5 +
            "*" +
            field6 +
            "*" +
            field7;
        }

        f.itenscotacao.value = $itenscotacao;   
        if (valido == 'N'){
            swal.fire({
                text: 'Preencha campo CFOP',
                icon: "warning",
                confirmButtonText: "Ok"
            });
        } else {
            swal.fire({
                title: 'Confirmação',
                text: 'Deseja realmente INCLUIR NFe e FATURAMENTO?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    f.submenu.value = 'cadastraNf';
                    f.id.value = id;
                    f.submit();
                } else {
                    f.submenu.value = '';
                }
            });
        } 
    }
    
} // submitAlterar

function submitAtual(id) {
     

    f = document.lancamento;
    f.mod.value = 'coc';
    f.form.value = 'ordem_compra';
    f.id.value = id;
    f.submenu.value = 'financeiro';
    f.submit();
} // fim submit

function submitGerarNFC(id, situacao) {
     
    f = document.lancamento;
    f.submenu.value = 'financeiro';
    f.id.value = id;
    if (situacao == 9) {
        swal.fire({
            text: 'Já foi gerada nota desta cotação.',
            icon: "warning",
            confirmButtonText: "Ok"
        });
    } else {
        f.submit();
    }
} // submitGerarNFC


function submitSearch() {
     
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (prom == '') ){
        swal.fire({
            text: 'Faça algum filtro de pesquisa.',
            icon: "warning",
            confirmButtonText: "Ok"
        });
    }else{
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        //alert(f.pesq.value);
        f.submit();
    }
        
} 


function submitConfirmarSmart() {
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        swal.fire({
            text: 'Selecione um Cliente.',
            icon: "warning",
            confirmButtonText: "Ok"
        });
        return false;
    } 
    if (f.condPgto.value == "") {
        swal.fire({
            text: 'Selecione uma Condição de Pagamento.',
            icon: "warning",
            confirmButtonText: "Ok"
        });
        return false;
    } 
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' esta Ordem de Compra?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            if(f.id.value == ''){
                f.submenu.value = 'inclui';
            }else{
                f.submenu.value == "cadastrar" ? f.submenu.value = 'inclui' : f.submenu.value = 'altera';
            }
            f.submit();
        }
    }); 

} // submitConfirmarSmart

function submitConfirmar() {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        swal.fire({
            text: 'Selecione uma Conta!',
            icon: "warning",
            confirmButtonText: "Ok"
        });
    } else {
        if (f.id.value == "") {
            swal.fire({
                text: 'Pedido sem itens cadastrado!',
                icon: "warning",
                confirmButtonText: "Ok"
            });
        }else{    
            swal.fire({
                title: 'Confirmação',
                text: 'Deseja realmente FINALIZAR este pedido?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (f.submenu.value == "cadastrar") {
                        f.submenu.value = 'inclui';
                    } else {
                        f.submenu.value = 'altera';
                    }
                    f.submit();
                }
            });  
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
    f.mod.value = 'coc';
    f.form.value = 'ordem_compra';
    f.submit();
} // fim submitVoltar

function submitLetra() {
     
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    
    situacoes = "";
    first = true;
    for (var i = 0; i < situacaoCombo.options.length; i++) {
      if (situacaoCombo[i].selected == true) {
        if (first == true) {
          first = false;
          situacoes = situacaoCombo[i].value;
        } else situacoes = situacoes + "," + situacaoCombo[i].value;
      }
    }

    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numOrdemCompra.value + "|" + situacoes + "|"  + f.numDocto.value 
    f.submit();
} // fim submitVoltar

function submitCadastro() {
     
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

function submitExcluir(id, situacao) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir esta Ordem de Compra?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'exclui';
            f.id.value = id;
            if (situacao == 9) {
                swal.fire({
                    text: 'Já foi gerada nota desta cotação.',
                    icon: "warning",
                    confirmButtonText: "Ok"
                });
            } else {
                f.submit();
            }
        }
    }); // submitExcluir
} // submitExcluir

function submitEstornar(id) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Estornar este pedido?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'estorna';
            f.id.value = id;
            f.submit();
        }
    }); // submitEstornar
} // submitEstornar

function submitBuscar() {
     
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (prom == '') ){
        swal.fire({
            text: 'Faça algum filtro de pesquisa.',
            icon: "warning",
            confirmButtonText: "Ok"
        });
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
        swal.fire({
            text: 'Selecione uma Conta!',
            icon: "warning",
            confirmButtonText: "Ok"
        });
    } else {
    if (f.natop.value == "") {
        swal.fire({
            text: 'Selecione uma Natureza de Operação!',
            icon: "warning",
            confirmButtonText: "Ok"
        });

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
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        swal.fire({
            text: 'Selecione uma Conta!',
            icon: "warning",
            confirmButtonText: "Ok"
        });
    } else {
            if (f.condPgto.value == "") {
                swal.fire({
                    text: 'Selecione uma Condição Pagamento!',
                    icon: "warning",
                    confirmButtonText: "Ok"
                });
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
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            //submitBuscar();
            f = document.lancamento;
            f.submenu.value = 'excluiItem';
            f.id.value = id;
            f.nrItem.value = nrItem;
            f.submit();
        }
    }); // submitExcluirItem
} // submitExcluirItem

function abrir(pag, form=''){
    screenWidth = 750;
    screenHeight = 650;
    if(form == 'produto'){
        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&acao='+document.lancamento.opcao_item.value;
        pag = '';
        pag = newPage;
    }
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function submitEntregue(id) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente colocar como entregue o pedido?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'entregue';
            f.id.value = id;
            f.submit();
        }
    }); // submitEntregue
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
        swal.fire({
            text: 'Selecione um cliente!',
            icon: "warning",
            confirmButtonText: "Ok"
        });
        f.submenu.value = '';
    } else {
      f.agrupar_pedidos.value = '';
      f.agrupar_pedidos.value = pedidos;
      f.submenu.value = 'agruparPedidos';
      f.submit();
    }
}


function buscaEmailCliente(id, idCliente){

     
    document.lancamento.id.value = id;
    document.lancamento.pessoa.value = idCliente;
    var form = $("form[name=lancamento]");
  
    $.ajax({
      type: "POST",
      url: form.action ? form.action : document.URL,
      data: $(form).serialize(),
      dataType: "text",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Ajax-Request-Enviar-Email", "true");
      },
      success: function (response) {
         
        var result = $("<div />").append(response).find("#modalEmail").html();
        $("#modalEmail").html(result);
      },
    });
    return false;
  
  }

  function enviaEmail(id) {
  
    if(document.lancamento.destinatario.value == ''){
      swal.fire({
          text: "Preencha o campo 'Para:' ao enviar Email.",
          icon: "warning",
          confirmButtonText: "Ok"
      });
      return false;
    }
    if(document.lancamento.assunto.value == ''){
      swal.fire({
          text: "Preencha o campo Assunto para enviar Email.",
          icon: "warning",
          confirmButtonText: "Ok"
      });
      return false;
    }
    if(document.lancamento.emailCorpo.value == ''){
      swal.fire({
          text: "Preencha o campo Corpo para enviar Email.",
          icon: "warning",
          confirmButtonText: "Ok"
      });
      return false;
    }
    document.lancamento.id.value = id;
    var form = $("form[name=lancamento]");
  
    $.ajax({
      type: "POST",
      url: form.action ? form.action : document.URL,
      data: $(form).serialize(),
      dataType: "text",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Ajax-Request-Enviar-Email-Ordem-Compra", "true");
      },
      success: function (response) {
               
  
        var msgAlert = $("<div />").append(response).find("#msgAlert").html();
        $("#msgAlert").html(msgAlert);
  
        $('#modalEmail').modal('hide');      
        
      },
    });
    return false;
  }


  function submitVerificarNf() {
       
  
    if(document.lancamento.numNf.value == ''){
      return false;
    }
    if(document.lancamento.pessoa.value == ''){
        return false;
    }
    if(document.lancamento.serie.value == ''){
      return false;
    }

    var form = $("form[name=lancamento]");
  
    $.ajax({
      type: "POST",
      url: form.action ? form.action : document.URL,
      data: $(form).serialize(),
      dataType: "text",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Ajax-Request-Verifica-Nf", "true");
      },
      success: function (response) {
               
        var msgAlert = $("<div />").append(response).find("#modNf").html();
        $("#modNf").html(msgAlert);

        let msgNf = $("input[name=msgNf]");
        console.log(msgNf)
        if(msgNf.val() !== ''){
             swal.fire({
                text:"Nota Fiscal já cadastrada para este Fornecedor!",
                icon: "warning",
                dangerMode: "Ok",
            });
            $('.swal-modal').css("width", "550");
        }
        
      },
    });
    return false;
  }

  function calculaTotalItens(campo = ''){
     
    var f = document.lancamento;
    if(f.quant.value == '0,00' || f.quant.value == ''){
        return false;
    }
    if (f.unitario.value == '0,00' || f.unitario.value ==  ''){
        return false;
    }
    var vlrQtde     = f.quant.value ;
    var unitario    = f.unitario.value;
    var desconto    = campo != 'desconto' ? desconto = "0,00" : desconto = f.vlrDesconto.value;
    var vlrPercdesconto = campo == 'desconto' || f.percDesconto.value == '' ? vlrPercdesconto  = "0,00" : vlrPercdesconto = f.percDesconto.value;

    desconto         = parseFloat(desconto.replace(".","").replace(",","."))
    vlrPercdesconto  = parseFloat(vlrPercdesconto.replace(".","").replace(",","."))
    
    var total     = 0;

    vlrQtde          = parseFloat(vlrQtde.replace(".","").replace(",","."))
    unitario         = parseFloat(unitario.replace(".","").replace(",","."))

    totalItem     = (vlrQtde * unitario);
    if(campo == 'desconto'){
        vlrPercdesconto  = ((desconto * 100)/totalItem)
    }else{
        desconto = ((totalItem*vlrPercdesconto)/100)
    
    }
    resultTotal = (totalItem - desconto);
    resultPerc = currencyFormat(vlrPercdesconto);
    resultDesc = currencyFormat(desconto);
    
    total = currencyFormat(resultTotal);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.totalItem.value = total;
    f.vlrDesconto.value = resultDesc;
    f.percDesconto.value = resultPerc;
    
}


function submitConfirmarPecas() {
     
    //validações
     if (document.lancamento.numNf.value == '' || document.lancamento.numNf.value == null) {
         swal.fire({
            text:"Preencha o campo Número NF para incluir o Produto.",
            icon: "warning",
            dangerMode: "Ok",
        });
        $('.swal-modal').css("width", "528");
        return false;
    }
    if (document.lancamento.serie.value == '' || document.lancamento.serie.value == null) {
         swal.fire({
            text:"Preencha o campo Série para incluir o Produto.",
            icon: "warning",
            dangerMode: "Ok",
        });
        $('.swal-modal').css("width", "470");
        return false;
    }
    if (document.lancamento.dataEmissao.value == '' || document.lancamento.dataEmissao.value == null) {
         swal.fire({
            text:"Preencha o campo Data Emissão para incluir o Produto.",
            icon: "warning",
            dangerMode: "Ok",
        });
        $('.swal-modal').css("width", "550");
        return false;
    }
    if (document.lancamento.quant.value == '' || document.lancamento.quant.value == '0,00') {
         swal.fire({
            text:"Preencha o campo Quantidade para incluir o Produto!",
            icon: "warning",
            dangerMode: "Ok",
        });
        $('.swal-modal').css("width", "530");
        return false;
    }
    if (document.lancamento.unitario.value == '' || document.lancamento.unitario.value == '0,00') {
         swal.fire({
            text:"Preencha o campo Valor Unitário para incluir o Produto.",
            icon: "warning",
            dangerMode: "Ok",
        });
        $('.swal-modal').css("width", "550");
        return false;
    }

    montaLetraItem();

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) { // area a ser executada no controle p_
            xhr.setRequestHeader("Ajax-Request-Cadastra-Item", "true");
        },
        success: function (response) { // response - tela inteira atualizada
         
            // procurta areas a atualizar da tela
            var result = $('<table></table>').append(response).find('#datatable-buttons-item').html();
            $("#datatable-buttons-item").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html(); // procura area a atualizar
            $("#divTotal").html(resultTotal); // atualiza a div do form

            var idOc = $('<div />').append(response).find('#divId').html();
            $("#divId").html(idOc);

            limpaCamposItem(); // limpa campos digitados
            $('.swal-modal').css("width", "");
            $('#codFabricante').focus();
        }
    });
    return false;

}


function editarItem(e, nrItem){
                
    var linha = $(e).closest("tr");

    var codInterno = linha.find("td:eq(0)").text().trim(); 
    var codigoFabricate = linha.find("td:eq(1)").text().trim(); 
    var codigoNota = linha.find("td:eq(2)").text().trim(); 
    var descricao = linha.find("td:eq(3)").text().trim(); 
    var unidade = linha.find("td:eq(4)").text().trim();        
    var quantidade = linha.find("td:eq(6)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(7)").text().trim(); 
    var percDesconto = linha.find("td:eq(8)").text().trim();
    var vlrDesconto = linha.find("td:eq(9)").text().trim();
    var totalitem = linha.find("td:eq(10)").text().trim();
    
    document.lancamento.nrItem.value = nrItem;
    document.lancamento.opcao_item.value = 'altera';
    $("#codProduto").val(codInterno);
    $("#codFabricante").val(codigoFabricate);
    $("#codProdutoNota").val(codigoNota);
    $("#descProduto").val(descricao);
    $("#uniProduto").val(unidade);
    $("#quant").val(quantidade);
    $("#unitario").val(vlrUnitario);
    $("#percDesconto").val(percDesconto);
    $("#vlrDesconto").val(vlrDesconto);
    $("#totalItem").val(totalitem);  
}

function submitExcluiItem(nrItem) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este Item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            document.lancamento.letra_item.value = document.lancamento.id.value + "|" +
            nrItem 

            var form = $("form[name=lancamento]");
        
            $.ajax({
                type: "POST",
                url: form.action ? form.action : document.URL,
                data: $(form).serialize(),
                dataType: "text",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Ajax-Request-Exclui-Item", "true");
                },
                success: function (response) {
                     
                    var result = $('<table></table>').append(response).find('#datatable-buttons-item').html();
                    $("#datatable-buttons-item").html(result);
            
                    var resultTotal = $('<div />').append(response).find('#divTotal').html();
                    $("#divTotal").html(resultTotal);

                    limpaCamposItem();
            
                }
            });
        } else {
            return false;
        }
    });
}

function montaLetraItem(){
    document.lancamento.letra_item.value = document.lancamento.id.value + "|" + 
    document.lancamento.pessoa.value + "|" +
    document.lancamento.codProduto.value + "|" +
    document.lancamento.codFabricante.value + "|" +
    document.lancamento.codProdutoNota.value + "|" +
    document.lancamento.descProduto.value + "|" +
    document.lancamento.uniProduto.value + "|" +
    document.lancamento.quant.value + "|" +
    document.lancamento.unitario.value + "|" +
    document.lancamento.percDesconto.value + "|" +
    document.lancamento.vlrDesconto.value + "|" +
    document.lancamento.totalItem.value + "|" +
    document.lancamento.situacao.value + "|" +
    document.lancamento.nrItem.value + "|" +
    document.lancamento.opcao_item.value;

}

function limpaCamposItem(){
    document.lancamento.letra_item.value = ''
    document.lancamento.opcao_item.value = ''
    document.lancamento.codProduto.value = ''
    document.lancamento.codFabricante.value = ''
    document.lancamento.codProdutoNota.value = ''
    document.lancamento.descProduto.value = ''
    document.lancamento.uniProduto.value  = ''
    document.lancamento.quant.value = ''
    document.lancamento.unitario.value = ''
    document.lancamento.percDesconto.value = '' 
    document.lancamento.vlrDesconto.value = ''
    document.lancamento.totalItem.value = ''
    document.lancamento.nrItem.value = ''
}


function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
} // currencyFormat

function duplicaOrdemCompra(id) {
    f = document.lancamento;  
    f.submenu.value = 'duplicaOrdemCompra';
    f.id.value = id 
    f.submit();
} // duplicaOrdemCompra

function atualizaTotais() {
     
    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) { // area a ser executada no controle p_
            xhr.setRequestHeader("Ajax-Request-Atualiza-Totais", "true");
        },
        success: function (response) { // response - tela inteira atualizada

            var resultTotal = $('<div />').append(response).find('#divTotal').html(); // procura area a atualizar
            $("#divTotal").html(resultTotal); // atualiza a div do form

        }
    });
    return false;

} // atualizaTotais


function cadastraProduto(){
    window.open("index.php?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
} // cadastraProduto


function buscaProduto() {
     
        f = document.lancamento;

        if (f.nome.value == '') {
             swal.fire({
                text: "Informe o Cliente!",
                icon: "warning",
                dangerMode: "Ok",
            });
            $('.swal-modal').css("width", "355");
            return false;
        }

        if(f.codFabricante.value !== "" & f.codFabricante.value !== 'undefined' & f.codFabricante.value !== 'null'){
        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Busca-prod", "true");
            },
            success: function (response) {
                 
                var result = $('<div />').append(response).find('#div_busca_prod').html();
                var prodExiste = $(result).find("input[name=prodExiste]").prevObject[0].defaultValue;
                
                if(prodExiste === 'yes'){
                    $("#div_busca_prod").html(result);
                    
                    f.quant.value = '0,00';
                    f.quant.focus();
                
                }else{
                    //Msg que Prod nao existe
                     swal.fire({
                        title: "Atenção!",
                        text: "Produto Não Localizado!",
                        icon: "warning",
                        buttons: {
                            btn_cadastrar_novo: {
                                text: "Pesquisar",
                                value: "1",
                            },
                            btn_cancelar: {
                                text: "OK",
                                value: '0',
                            }
                        }
                    })
                    .then((val) => {
                         
                        if (val == 0) {
                            return false;
                        } else {
                            window.open(document.URL + '?mod=est&form=produto&opcao=pesquisarpecas&from=ordem_compra&letra=||' + document.getElementById('codFabricante').value,
                                'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
                        }
                    });
                }
                //Aplica mascara no form 
                $(".money").maskMoney({                  
                 decimal: ",",
                 thousands: ".",
                 allowZero: true,
                });
            }
        });
        return false;

        }else{
            return false;
        }
    } // buscaProduto
