/* UTILITÁRIOS */ 
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
/**  ORDEM DE SERVICO  */

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

    var tableProdutos = document.getElementById("datatable-buttons-pecas");
    var rowProduto = tableProdutos.rows.length;

    var tableServicos = document.getElementById("datatable-buttons-servicos");
    var rowServico = tableServicos.rows.length;

    
    if (rowProduto <= 1 && rowServico <= 1 ) {
        alert('Insira um produto ou um serviço para realizar um pedido.');
        return false;
    }

    if(f.os.value != '0'){
        if(f.catEquipamentoId.value == ''){
            alert('Selecione um Equipamento para realizar um pedido.');
            return false;
        }
    }
        
    if (confirm('Deseja realmente ' + f.submenu.value + ' este Pedido') == true) {
        if (f.submenu.value == "cadastrar") {
            if(f.id.value != ''){
                f.submenu.value = 'altera';
            }else{
                f.submenu.value = 'inclui';
            }

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

function submitGerarOs(id) {
    f = document.lancamento;
    f.submenu.value = 'gerarOs';
    f.id.value = id
    f.submit();
} // fim submitGerarOs

function submitEstornarOs(id) {
    f = document.lancamento;
    f.submenu.value = 'estornarOs';
    f.id.value = id
    f.submit();
} // fim submitGerarOs

function submitLetra() {
    debugger;
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numAtendimento.value;
    
    // situacao Atendimento  
    f.situacoesAtendimento.value = concatCombo(situacaoCombo);
    
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
    if (confirm('Deseja realmente Cancelar este Pedido') == true) {
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
    if(form === 'pedidoPS'){
        screenWidth = 900;
        screenHeight = 650;
    }else{
        screenWidth = 750;
        screenHeight = 650;
    }
    
    if(form == 'produto'){
        if(document.lancamento.pessoa.value == ''){
            alert("Selecione o Cliente antes de fazer a pesquisa");
            return false;
        }
        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&acao='+document.lancamento.opcao_item.value;
        pag = '';
        pag = newPage;
    }
    if(form == 'servicos'){
        if(document.lancamento.pessoa.value == ''){
            alert("Selecione o Cliente antes de fazer a pesquisa");
            return false;
        }

        screenWidth = screen.width;
        screenHeight = screen.height;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function calculaTotal(){
    debugger;
    var f = document.lancamento;
    var pecas           = f.valorPecas.value == '' ? '0,00' : f.valorPecas.value;
    var servicos        = f.valorServicos.value == '' ? '0,00' : f.valorServicos.value;
    var frete           = f.valorFrete.value == '' ? '0,00' : f.valorFrete.value;
    var despAcessorias  = f.valorDespAcessorias.value == '' ? '0,00' : f.valorDespAcessorias.value;
    var desconto        = f.valorDesconto.value == '' ? '0,00' : f.valorDesconto.value;
    var total           = 0;

    pecas          = parseFloat(pecas.replace(".","").replace(",","."));
    servicos       = parseFloat(servicos.replace(".","").replace(",","."));
    frete          = parseFloat(frete.replace(".","").replace(",","."));
    despAcessorias = parseFloat(despAcessorias.replace(".","").replace(",","."));
    desconto       = parseFloat(desconto.replace(".","").replace(",","."));

    total     = ((pecas + servicos + frete + despAcessorias) - desconto); 
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

function calculaTotalItens(campo = '', modal=''){
    debugger;
    var f = document.lancamento;
    if(modal == 'pecas'){
        if(f.quantidadePecas.value == '0,00' || f.quantidadePecas.value == ''){
            return false;
        }
        if (f.vlrUnitarioPecas.value == '0,00' || f.vlrUnitarioPecas.value ==  ''){
            return false;
        }
        var vlrQtde     = f.quantidadePecas.value ;
        var unitario    = f.vlrUnitarioPecas.value;
        var desconto    = campo != 'desconto' ? desconto = "0,00" : desconto = f.vlrDescontoPecas.value;
        var vlrPercdesconto = campo == 'desconto' || f.percDescontoPecas.value == '' ? vlrPercdesconto  = "0,00" : vlrPercdesconto = f.percDescontoPecas.value;

        desconto         = parseFloat(desconto.replace(".","").replace(",","."))
        vlrPercdesconto  = parseFloat(vlrPercdesconto.replace(".","").replace(",","."))
    }else{
        if(f.quantidadeServico.value == '0,00' || f.quantidadeServico.value == ''){
            return false;
        }
        if (f.vlrUnitarioServico.value == '0,00' || f.vlrUnitarioServico.value ==  ''){
            return false;
        }
        var vlrQtde     = f.quantidadeServico.value ;
        var unitario    = f.vlrUnitarioServico.value;
    }
    
    var total     = 0;

    vlrQtde          = parseFloat(vlrQtde.replace(".","").replace(",","."))
    unitario         = parseFloat(unitario.replace(".","").replace(",","."))
    

    totalItem     = (vlrQtde * unitario);
    if(modal == 'pecas'){
        if(campo == 'desconto'){
            vlrPercdesconto  = ((desconto * 100)/totalItem)
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

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    if(modal == 'pecas'){
        f.totalPecas.value = total;
        f.vlrDescontoPecas.value = resultDesc;
        f.percDescontoPecas.value = resultPerc;
    }else{
        f.totalServico.value = total;
    }
}
/** PECAS */
function submitConfirmarPecas() {
    debugger
    //validações 
    if (document.lancamento.quantidadePecas.value == '' || document.lancamento.quantidadePecas.value == '0,00') {
        alert('Preencha o campo Quantidade para incluir o Produto.');
        return false;
    }
    if (document.lancamento.vlrUnitarioPecas.value == '' || document.lancamento.vlrUnitarioPecas.value == '0,00') {
        alert('Preencha o campo Valor Unitário para incluir o Produto.');
        return false;
    }
    montaLetraPeca();

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Peca", "true");
        },
        success: function (response) {
            debugger;
            var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
            $("#datatable-buttons-pecas").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html();
            $("#divTotal").html(resultTotal);

            var idOs = $('<div />').append(response).find('#idAtendimento').html();
            $("#idAtendimento").html(idOs);

            limpaCamposPeca();

        }
    });
    return false;

}


function editarPeca(e, nrItem){
                
    var linha = $(e).closest("tr");

    var codigoProduto = linha.find("td:eq(0)").text().trim(); 
    var codFabricante = linha.find("td:eq(1)").text().trim(); 
    var codNota = linha.find("td:eq(2)").text().trim(); 
    var descricao = linha.find("td:eq(3)").text().trim();   
    var quantidade = linha.find("td:eq(4)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(5)").text().trim(); 
    var vlrDesconto = linha.find("td:eq(6)").text().trim();
    var percDesconto = linha.find("td:eq(7)").text().trim();
    var totalitem = linha.find("td:eq(8)").text().trim();
    
    document.lancamento.nrItem.value = nrItem;
    document.lancamento.opcao_item.value = 'altera';
    $("#codProduto").val(codigoProduto);
    $("#codFabricante").val(codFabricante);
    $("#codProdutoNota").val(codNota);
    $("#descProduto").val(descricao);
    $("#quantidadePecas").val(quantidade);
    $("#vlrUnitarioPecas").val(vlrUnitario);
    $("#percDescontoPecas").val(percDesconto);
    $("#vlrDescontoPecas").val(vlrDesconto);
    $("#totalPecas").val(totalitem);  
}

function submitExcluiPeca(nrItem) {
    if (confirm('Deseja realmente Excluir este Item ?') == true) {
        document.lancamento.letra_peca.value = document.lancamento.id.value + "|" + 
        nrItem + "|" +
        document.lancamento.situacao.value;

        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Peca", "true");
            },
            success: function (response) {
                debugger
                var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                $("#datatable-buttons-pecas").html(result);
    
                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);

                

                limpaCamposPeca();
    
            }
        });
        return false;
    }else{
        return false
    }
    
  

}

function montaLetraPeca(){
    debugger
    document.lancamento.letra_peca.value = document.lancamento.id.value + "|" + 
    document.lancamento.pessoa.value + "|" +
    document.lancamento.codProduto.value + "|" +
    document.lancamento.codProdutoNota.value + "|" +
    document.lancamento.descProduto.value + "|" +
    document.lancamento.uniProduto.value + "|" +
    document.lancamento.quantidadePecas.value + "|" +
    document.lancamento.vlrUnitarioPecas.value + "|" +
    document.lancamento.percDescontoPecas.value + "|" +
    document.lancamento.vlrDescontoPecas.value + "|" +
    document.lancamento.totalPecas.value + "|" +
    document.lancamento.situacao.value + "|" +
    document.lancamento.nrItem.value + "|" +
    document.lancamento.codFabricante.value;

}

function limpaCamposPeca(){
    document.lancamento.letra_peca.value = ''
    document.lancamento.codProduto.value = ''
    document.lancamento.codProdutoNota.value = ''
    document.lancamento.descProduto.value = ''
    document.lancamento.uniProduto.value  = ''
    document.lancamento.quantidadePecas.value = ''
    document.lancamento.vlrUnitarioPecas.value = ''
    document.lancamento.percDescontoPecas.value = '' 
    document.lancamento.vlrDescontoPecas.value = ''
    document.lancamento.totalPecas.value = ''
    document.lancamento.nrItem.value = ''
    document.lancamento.opcao_item.value = ''
    document.lancamento.codFabricante.value = ''
}

/*  SERVICOS   */


function submitConfirmarServicos() {
    //validações 
    if (document.lancamento.quantidadeServico.value == '' || document.lancamento.quantidadeServico.value == '0,00') {
        alert('Preencha o campo Quantidade para incluir o Serviço.');
        return false;
    }
    if (document.lancamento.vlrUnitarioServico.value == '' || document.lancamento.vlrUnitarioServico.value == '0,00') {
        alert('Preencha o campo Valor Unitário para incluir o Serviço.');
        return false;
    }
    montaLetraServico();

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Servico", "true");
        },
        success: function (response) {
            var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
            $("#datatable-buttons-servicos").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html();
            $("#divTotal").html(resultTotal);

            var idOs = $('<div />').append(response).find('#idAtendimento').html();
            $("#idAtendimento").html(idOs);

            limpaCamposServicos();

        }
    });
    return false;
}

function editarServico(e, idServicos){
                
    var linha = $(e).closest("tr");

    var codigo      = linha.find("td:eq(0)").text().trim(); 
    var descricao   = linha.find("td:eq(1)").text().trim(); 
    var unidade     = linha.find("td:eq(2)").text().trim();        
    var quantidade  = linha.find("td:eq(3)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(4)").text().trim(); 
    var totalitem   = linha.find("td:eq(5)").text().trim();
    
    document.lancamento.codServico.value = idServicos;
    $("#idServicos").val(codigo);
    $("#descricaoServico").val(descricao);
    $("#unidadeServico").val(unidade);
    $("#quantidadeServico").val(quantidade);
    $("#vlrUnitarioServico").val(vlrUnitario);
    $("#totalServico").val(totalitem);  
}

function submitExcluiServico(idServicos) {
    if (confirm('Deseja realmente Excluir este Item ?') == true) {
        document.lancamento.letra_servico.value = document.lancamento.id.value + "|" + 
        idServicos + "|" +
        document.lancamento.situacao.value;

        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Servico", "true");
            },
            success: function (response) {
                var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
                $("#datatable-buttons-servicos").html(result);
    
                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);
    
                limpaCamposServicos();
    
            }
        });
        return false;
    }else{
        return false
    }

}

function montaLetraServico(){
    document.lancamento.letra_servico.value = document.lancamento.id.value + "|" + 
    document.lancamento.pessoa.value + "|" +
    document.lancamento.codServico.value + "|" +
    document.lancamento.descricaoServico.value + "|" +
    document.lancamento.unidadeServico.value + "|" +
    document.lancamento.quantidadeServico.value + "|" +
    document.lancamento.vlrUnitarioServico.value + "|" +
    document.lancamento.totalServico.value + "|" +
    document.lancamento.situacao.value + "|" +
    document.lancamento.idServicos.value;
}

function limpaCamposServicos(){
    document.lancamento.letra_servico.value = ''
    document.lancamento.idServicos.value  = ''
    document.lancamento.codServico.value  = ''
    document.lancamento.descricaoServico.value = ''
    document.lancamento.unidadeServico.value = ''
    document.lancamento.quantidadeServico.value  = ''
    document.lancamento.vlrUnitarioServico.value = '' 
    document.lancamento.totalServico.value = ''
}



function submitCadastrarAtendimentoNf(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';
    f.id.value = id;
    f.submit();
}

function submitDuplicarPedido(id) {
    f = document.lancamento;  
    f.submenu.value = 'duplicaPedido';
    f.id.value = id 
    f.submit();
}

function submitBuscaProduto(path) {
        
        document.lancamento.pesq.value =  "||" + 
        document.lancamento.codFabricante.value + "||||";

        var newPathProduto = path + '&letra='+document.lancamento.pesq.value;

        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Busca-Produto", "true");
            },
            success: function (response) {
                var result = $('<div />').append(response).find('#formPedidoItem').html();
                $("#formPedidoItem").html(result);
    
                var resultPesq = $('<div />').append(response).find('#divPesquisaProduto').html();
                $("#divPesquisaProduto").html(resultPesq);
    
                document.lancamento.pesq.value  = '';
                if(document.lancamento.abrePesquisa.value == 'true'){
                    abrir(newPathProduto, 'produto');
                }
    
            }
        });
        return false;
}

function cadastraProduto(){
    f = document.lancamento;
    var letra = 'registerProd' + '|' + 
                f.codFabricante.value + '|' + 
                f.codProdutoNota.value + '|' + 
                f.descProduto.value + '|' + 
                f.uniProduto.value + '|' +
                f.vlrUnitarioPecas.value;

    window.open("index.php?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&letra="+letra+"&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

function buscaProduto() {
    debugger
    f = document.lancamento;

    if (f.nome.value == '') {
        swal({
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
            debugger
            console.log(response)
            var result = $('<div />').append(response).find('#formPedidoItem').html();
            var prodExiste = $(result).find("input[name=prodExiste]").prevObject[0].defaultValue;
                
            if(prodExiste === 'yes'){
                $("#formPedidoItem").html(result);
                
                f.quantidadePecas.value = '0,00';
                f.quantidadePecas.focus();
                
            }else{
                //Msg que Prod nao existe
                swal({
                    text: "Produto Não Localizado!",
                    icon: "warning",
                    dangerMode: "Ok",
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
}

//atualiza descontos
function atualizarInfo() {
    swal({
        title: "Atenção?",
        text: "Ao realizar o desconto geral os descontos unitários serão recalculados!",
        icon: "warning",
        buttons: ["Cancelar", 'Continuar'],
    })
    .then((yes) => {
        if(yes){
            //salva o valor atual no localstorage
            localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);

            f = document.lancamento;

            var desconto = parseFloat(f.valorDesconto.value.replace(".", "").replace(",", "."));
            var total = parseFloat(f.valorTotal.value.replace(".", "").replace(",", "."));
        
            if (desconto > total) {
                swal("Atenção!", "O desconto nao pode ser maior do que o valor total!", "warning");
                f.valorDesconto.value = "0,00";
                return false;
            }
            if (f.valorDesconto.value == "") {
                f.valorDesconto.value = "0,00";
            }

            // p/ nao perder as casas decimais ex: 10,50
            var newFrete = parseFloat(f.valorFrete.value.replace(".", "").replace(",", "."))
            f.valorFrete.value = newFrete;
                
            var newDesconto = parseFloat(f.valorDesconto.value.replace(".", "").replace(",", "."))
            f.valorDesconto.value = newDesconto;
        
            var newDespAcessorias = parseFloat(f.valorDespAcessorias.value.replace(".", "").replace(",", "."))
            f.valorDespAcessorias.value = newDespAcessorias;
            f.submenu.value = "atualizarInfo";
            f.submit();
        }else{
            if(document.getElementsByName("id")[0].value == localStorage.getItem("idPedidoServico")){
                document.getElementById("valorDesconto").value = localStorage.getItem("vlrDescontoAnt");
            }

            return false;
        }
    });
  } // atualizarInfo

  function guardaValorAnt(){
    localStorage.setItem("idPedidoServico", document.getElementsByName("id")[0].value);
    localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);
  }