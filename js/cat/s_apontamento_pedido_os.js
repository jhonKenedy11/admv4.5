/**  APONTAMENTO  */

function submitConfirmarSmart() {
    f = document.lancamento;

    if (f.atendimentoId.value == "") {
        alert('Selecione uma Ordem de Serviço.');
        return false;
    }
    
        
    if (confirm('Deseja realmente ' + f.submenu.value + ' este Apontamento?') == true) {
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        } else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } 
        
} // submitConfirmarSmart

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
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numPedido.value;
    
    // situacao Pedido  
    f.situacoesPedido.value = concatCombo(situacaoPedido);
    
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    debugger;
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(id) {
    f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
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

        screenWidth = screen.width;
        screenHeight = screen.height;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}


// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}


/** APONTAMENTO */
function submitConfirmarApontamento() {
    //validações 
    
    

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Apontamento", "true");
        },
        success: function (response) {
            var result = $('<div />').append(response).find('#datatable-buttons-apontamento').html();
            $("#datatable-buttons-apontamento").html(result);

            limpaCamposApontamento();
        }
    });
    return false;

}

function cadastrarApontamento(e){
    dataAtual = new Date();
    h = dataAtual.getHours();
    m = dataAtual.getMinutes();
    s = dataAtual.getSeconds();
    
    horas = h + ":" + m + ":" + s

    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano
    var linha = $(e).closest("tr");

    var codServico      = linha.find("td:eq(1)").text().trim(); 
    
    $("#mCodServico").val(codServico);
    $("#mDataInicio").val(horas);
    $("#mData").val(dataFormatada);

    $("#mIdApontamento").val('');
    $("#mDescricaoApontamento").val('');
    $("#mDataFim").val('');
    $("#mTotalHoras").val('00:00:00'); 
    
}


function editarApontamento(e){
                
    var linha = $(e).closest("tr");

    var codigo          = linha.find("td:eq(0)").text().trim(); 
    var codServico      = linha.find("td:eq(1)").text().trim(); 
    var descricao       = linha.find("td:eq(2)").text().trim(); 
    var dataIni         = linha.find("td:eq(3)").text().trim();        
    var dataFim         = linha.find("td:eq(4)").text().trim(); 
    var totalHoras      = linha.find("td:eq(5)").text().trim(); 
    var userId          = linha.find("td:eq(6)").text().trim(); 

    var data = dataIni.split(" ");
    var dataHoraFim = dataFim.split(" ");
    
    $("#mIdApontamento").val(codigo);
    $("#mCodServico").val(codServico);
    $("#mDescricaoApontamento").val(descricao);
    $("#mData").val(data[0]);
    $("#mDataInicio").val(data[1]);
    $("#mDataFim").val(dataHoraFim[1]);
    $("#mTotalHoras").val(totalHoras); 
    $("#idUser").val(userId); 
}

function submitExcluiApontamento(idApontamento) {
    if (confirm('Deseja realmente Excluir este Item ?') == true) {
        document.lancamento.mIdApontamento.value =idApontamento 

        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Apontamento", "true");
            },
            success: function (response) {
                debugger
                var result = $('<div />').append(response).find('#datatable-buttons-apontamento').html();
                $("#datatable-buttons-apontamento").html(result);
    

                limpaCamposApontamento();
    
            }
        });
        return false;
    }

}



function limpaCamposApontamento(){
    dataAtual = new Date();
    h = dataAtual.getHours();
    m = dataAtual.getMinutes();
    s = dataAtual.getSeconds();
    
    horas = h + ":" + m + ":" + s

    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano

    document.lancamento.mIdApontamento.value = ''
    document.lancamento.mCodServico.value = ''
    document.lancamento.mDescricaoApontamento.value = ''
    document.lancamento.mData.value  = dataFormatada;
    document.lancamento.mDataInicio.value  = horas;
    document.lancamento.mDataFim.value  = ''
    document.lancamento.mTotalHoras.value = ''
    document.lancamento.idServicos.value = ''
    document.lancamento.idUser.value = ''
}


function validaTotalHoras(){
    debugger
    horaFim = document.lancamento.mDataFim.value;
    horaInicio = document.lancamento.mDataInicio.value;
    data = document.lancamento.mData.value;

    dataHrFim = data+" "+horaFim 
    dataHrInicio = data+" "+horaInicio 

    hrFim =  moment(dataHrFim, "DD/MM/YYYY H:mm:ss").valueOf(); 
    hrInicio = moment(dataHrInicio, "DD/MM/YYYY H:mm:ss").valueOf(); 
    if(hrFim < hrInicio){
        alert("Fim/Hr(s) inválida");
        document.lancamento.mDataFim.value = '';
        return false;
    }
    
    var ms = moment(horaFim,"HH:mm:ss").diff(moment(horaInicio,"HH:mm:ss"));
  

    // execution
    let res = moment.utc(ms).format("HH:mm:ss");

    document.lancamento.mTotalHoras.value = res;

}




function buscaApontamentosServico(idServico){
    debugger
    document.lancamento.checkServ.value = '';
    document.lancamento.idServicos.value = idServico
    checkId = 'check'+idServico;
    check = document.getElementById(checkId).checked
    if(check == true){
        document.lancamento.checkServ.value = true;
    }else{
        document.lancamento.checkServ.value = false;
    }
    

    var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Pesq-Ap-Serv", "true");
            },
            success: function (response) {
                debugger
                var result = $('<div />').append(response).find('#datatable-buttons-apontamento').html();
                $("#datatable-buttons-apontamento").html(result);
    
    
            }
        });
        return false;
}
/** PEÇAS / PRODUTOS */


function editarPeca(e, idPecas){
                
    var linha = $(e).closest("tr");

    var codigo          = linha.find("td:eq(0)").text().trim(); 
    var nrItem          = linha.find("td:eq(1)").text().trim(); 
    var descricao       = linha.find("td:eq(2)").text().trim(); 
    var quantidade      = linha.find("td:eq(3)").text().trim(); 
    var vlrUnitario     = linha.find("td:eq(5)").text().trim();
    var vlrDesconto     = linha.find("td:eq(6)").text().trim(); 
    

    document.lancamento.idPecas.value = idPecas;
    document.lancamento.nrItem.value = nrItem;
    document.lancamento.qtdeProd.value = quantidade;
    document.lancamento.codProduto.value = codigo;
    document.lancamento.vlrUnitarioProd.value = vlrUnitario
    $("#codProduto").val(codigo);
    $("#descricaoProduto").val(descricao);
}

function submitConfirmarPecas() {
    debugger
    //validações 
    //if (document.lancamento.qtdeUtilizada.value == '' || document.lancamento.qtdeUtilizada.value == '0,00') {
    //    alert('Preencha o campo Qtde Utilizada para incluir no Produto.');
    //    return false;
    //}
    qtdeUtilizada = document.lancamento.qtdeUtilizada.value;
    qtde          = document.lancamento.qtdeProd.value;
    qtdeUtilizada = parseFloat(qtdeUtilizada.replace(".","").replace(",","."))
    qtde          = parseFloat(qtde.replace(".","").replace(",","."))

    vlrUnitario = document.lancamento.vlrUnitarioProd.value;    
    vlrUnitario = parseFloat(vlrUnitario.replace(".","").replace(",",".")) 

    totalUtilizado = (qtdeUtilizada * vlrUnitario)
    document.lancamento.totalUtilizado.value = totalUtilizado
    document.lancamento.qtdeUtilizada.value = qtdeUtilizada

    if(qtdeUtilizada > qtde){
        if(confirm("AVISO. A Quantidade Utilizada ultrapassa a Quantidade do Produto "+
            "Deseja confirmar essa Quantidade Utilizada?")== true){

            var form = $("form[name=lancamento]");

            $.ajax({
                type: "POST",
                url: form.action ? form.action : document.URL,
                data: $(form).serialize(),
                dataType: "text",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Ajax-Request-Add-QtdeUtilizada-Peca", "true");
                },
                success: function (response) {
                    var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                    $("#datatable-buttons-pecas").html(result);  
                    
                    var resultTotal = $('<div />').append(response).find('#divTotal').html();
                    $("#divTotal").html(resultTotal);
            
                    limpaCamposPeca();
            
                }
            });
            return false;
      }else{
        return false;
      }
    }else{
        var form = $("form[name=lancamento]");
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Add-QtdeUtilizada-Peca", "true");
            },
            success: function (response) {
                var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                $("#datatable-buttons-pecas").html(result);            
                
                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);

                limpaCamposPeca();
        
            }
        });
        return false;
    }
}

function limpaCamposPeca(){
    document.lancamento.idPecas.value = ''
    document.lancamento.codProduto.value = ''
    document.lancamento.nrItem.value = ''
    document.lancamento.descricaoProduto.value  = '';
    document.lancamento.qtdeUtilizada.value  = ''
    document.lancamento.qtdeProd.value  = ''
    document.lancamento.vlrUnitarioProd.value  = ''
    document.lancamento.totalUtilizado.value  = ''
}

function submitConfirmarTodasQtdeUtilizada() {
    
  
        if(confirm("ATENÇÃO.  "+
            "Deseja preencher a Quantidade Utilizada de todos itens?")== true){
            var form = $("form[name=lancamento]");

            $.ajax({
                type: "POST",
                url: form.action ? form.action : document.URL,
                data: $(form).serialize(),
                dataType: "text",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Ajax-Request-Todos-QtdeUtilizada-Peca", "true");
                },
                success: function (response) {
                    var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                    $("#datatable-buttons-pecas").html(result);   
                    
                    var resultTotal = $('<div />').append(response).find('#divTotal').html();
                    $("#divTotal").html(resultTotal);
            
                    limpaCamposPeca();
            
                }
            });
            return false;
      }else{
        return false;
      }
    
}