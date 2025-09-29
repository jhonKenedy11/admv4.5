document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.key == "Enter") {
        submitLetraPesquisa();
    }// fim evento enter
});// fim addEventListener

/*$("#btnSubmit").click(function(event) {

    // Fetch form to apply custom Bootstrap validation
    var form = $("#myForm")

    if (form[0].checkValidity() === false) {
      event.preventDefault()
      event.stopPropagation()
    }
    
    form.addClass('was-validated');
    // Perform ajax submit here...
    
});*/


function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaTotal() {
    var f = document.lancamento;
    var custo = f.custoCompra.value;
    var medio = f.custoMedio.value;
    var reposicao = f.custoReposicao.value;
    var informado = f.precoInformado.value;
    var base = f.precoBase.value;
    var perc = f.percCalculo.value;
    var total = 0;

    if(custo === "NaN" || custo === Infinity || custo === undefined ||custo === ''){
        custo = '0,00';
    }
    if(medio === "NaN" || medio === Infinity || medio === undefined ||medio === ''){
        medio = '0,00';
    }
    if(reposicao === "NaN" || reposicao === Infinity || reposicao === undefined || reposicao === ''){
        reposicao = '0,00';
    }
    if(informado === "NaN" || informado === Infinity || informado === undefined || informado === ''){
        informado = '0,00';
    }
    if(perc === "NaN" || perc === Infinity || perc === undefined || perc === ''){
        perc = '0,00';
    }

    custo = parseFloat(custo.replace(".", "").replace(",", "."));
    medio = parseFloat(medio.replace(".", "").replace(",", "."));
    reposicao = parseFloat(reposicao.replace(".", "").replace(",", "."));
    informado = parseFloat(informado.replace(".", "").replace(",", "."));
    perc = parseFloat(perc.replace(".", "").replace(",", "."));
    switch (base) {
        case "C":
            var total = custo + ((custo * perc) / 100);
            break;
        case "M":
            var total = medio + ((medio * perc) / 100);
            break;
        case "R":
            var total = reposicao + ((reposicao * perc) / 100);
            break;
        case "I":
            var total = informado + ((informado * perc) / 100);
            break;
        default:
            var total = 0
    }   

    result = currencyFormat(total);
    if (result === "NaN"){
        f.venda.value = 0;
    }else if(result === "Infinity"){
        f.venda.value = 0;
    }else{
        f.venda.value = currencyFormat(total);
    }
}

function calculaPerc() {
    var f = document.lancamento;
    var custo = f.custoCompra.value;
    var medio = f.custoMedio.value;
    var reposicao = f.custoReposicao.value;
    var informado = f.precoInformado.value;
    var base = f.precoBase.value;
    var perc = f.percCalculo.value;
    var total = 0;
    custo = parseFloat(custo.replace(".", "").replace(",", "."));
    medio = parseFloat(medio.replace(".", "").replace(",", "."));
    reposicao = parseFloat(reposicao.replace(".", "").replace(",", "."));
    informado = parseFloat(informado.replace(".", "").replace(",", "."));
    perc = parseFloat(perc.replace(".", "").replace(",", "."));
    total = f.venda.value;
    total = parseFloat(total.replace(".", "").replace(",", "."));
    switch (base) {
        case "C":
            var perc = ((total - custo) / custo) * 100;
            break;
        case "M":
            var perc = ((total - medio) / medio) * 100;
            break;
        case "R":
            var perc = ((total - reposicao) / reposicao) * 100;
            break;
        case "I":
            var perc = ((total - informado) / informado) * 100;
            break;
        default:
            var total = 0
    }
    result = currencyFormat(perc);
    if (result === "NaN"){
        f.percCalculo.value = 0;
    }else if(result === "Infinity"){
        f.percCalculo.value = 0;
    }else{
        f.percCalculo.value = currencyFormat(perc);
    }
}

function submitVoltar(formulario) {
    f = document.lancamento;
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    if (f.tribIcms.value == "") {
        alert("Digite a tributação do produto.");
    } else
        if (f.origem.value == "") {
            alert("Digite a origem do produto.");
        } else
            if (f.uni.value == "") {
                alert("Digite a unidade do produto.");
            } else
            if (f.ncm.value == "") {
               alert("Digite a NCM do produto.");
            } else
            if (f.desc.value == "") {
                alert("Digite a descrição do produto.");
            } else
                if (f.pessoa.value == "") {
                    alert("Selecione o Fabricante do produto.");
                }
                else {
                    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
                        f.opcao.value = formulario;
                        if (f.submenu.value == "cadastrar") {
                            f.submenu.value = 'inclui';
                        }
                        else {
                            f.submenu.value = 'altera';
                        }

                        f.submit();
                    } // if
                } // else
            }// fim submitConfirmar

function submitConfirmarEquivalencia() {
    f = document.lancamento;
    if (f.submenu.value == "cadastrar")
        alert("Inclua o produto antes de cadastrar equivalencias.");
    else {
        if (confirm('Deseja cadastrar códico equivalencia deste item') == true) {
            f.submenu.value = 'incluiequivalencia';
            f.submit();
        } // if
    } // else
} // fim submitConfirmar

function submitExcluirEquivalencia(id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.opcao.value = '';
        f.submenu.value = 'excluiequivalencia';
        f.idEquiv.value = id;
        f.submit();
    }
}


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    //   f.desc.value = "";
    f.submit();
}

function submitAlterar(produto_id) {
    f = document.lancamento;
    f.opcao.value = '';
    f.submenu.value = 'alterar';
    f.id.value = produto_id;
    f.submit();

}

function submitExcluir(produto_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.opcao.value = '';
        f.submenu.value = 'exclui';
        f.id.value = produto_id;
        f.submit();
    }
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
    debugger;
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
function submitLetraPesquisa(codigo = null, codFabricante = null, checkbox = '') {
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
        if(checkbox == 'true'){
            $check = document.getElementById('pedidoChecked').checked;
            f.checkbox.value = $check;
        }else{
            if (checkbox != ''){
                f.checkbox.value = false;}
        }
        

        f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "|" + f.localizacao.value + "|" + valueQuant + "|" + valueFora;
        f.submit();
    }
}

function submitAjustaEstoque() {
    f = document.lancamento;
    if ((f.quantNova.value == "") || (f.quantNova.value == 0)) {
        alert("Digite a NOVA Quantidade a ser ajustada para o produto.");
    } else {
        if (confirm('Deseja ajustar a quantidade') == true) {
            f.mod.value = 'est';
            f.form.value = 'produto';
            f.submenu.value = 'ajustaestoque';
        }
    }
    f.submit();
}


function consultaPrint(form) {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'est';
    g.form.value = 'produto';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}


// abre janela de consulta
function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
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

//function insertConta(url, windowoption, name, params)
function submitInsertProdutoJson(params) {
    var f = document.upload;
    var url = f.url.value;
    var name = 'Produtos';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        input.value = params.conta[i].valor;
        form.appendChild(input);
    }

    document.body.appendChild(form);

    //note I am using a post.htm page since I did not want to make double request to the page
    //it might have some Page_Load call which might screw things up.
    window.open("post.htm", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
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

function setaDadosPedido() {
    //validar de selecionou cliente e cond de pagamento..
    // limpar dados do form antes de abrir
    document.lancamento.desc.value = '';

}

function submitModal() {
    debugger;
    let numLinhas = document.getElementById("datatable").rows.length;
    if (numLinhas <= 1) {
        alert("Aviso! Faça a Pesquisa antes de importar dados.");
        return false
    }

    f = document.lancamento;
    f.form.value = 'produto';
    f.submenu.value = 'modal_ped_item';
    f.submit();


}

function formata_descricao(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Remove acentos
        .replace(/([^\w]+|\s+)/g, '-') // Substitui espaço e outros caracteres por hífen
        .replace(/\-\-+/g, '-')	// Substitui multiplos hífens por um único hífen
        .replace(/(^-+|-+$)/, ''); // Remove hífens extras do final ou do inicio da string
}

function submitAlterarItemTabela($id, $codigo) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'alterar';
    f.letra.value = $id;
    f.codigo.value = $codigo;
    f.submit();
}

/**
 *  Imagem Produto
 */

function submitDestaqueImagem(idimg, destaque) {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'destaqueImagem';
    f.destaque.value = destaque;
    f.idimg.value = idimg;
    f.submit();
} // submitDestaqueImagem


function submitExcluirImagem(id, idimg) {
    debugger;
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamentoajax;
        f.mod.value = 'est';
        f.form.value = 'produto';
        f.submenu.value = 'excluiImagem';
        f.id.value = id;
        f.idimg.value = idimg;
        f.submit();
    }
} // submitExcluirImagem

function submitVoltarImagem(consulta = '') {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    if (consulta != '') {
        f.opcao.value = consulta;
    }
    f.submenu.value = '';
    f.submit();
} // fim submitVoltarImagem

// salvar imagem
function submitSalvarImagem(id) {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'salvarImagem';
    f.id.value = id;
    f.submit();
} // submitSalvarImagem    

// mostra Cadastro
function submitCadastrarImagem(id, titulo) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'cadastrarImagem';
    f.id.value = id;
    f.tituloImg.value = titulo;
    f.submit();

} // submitCadastrarImagem


// ===========================
// FEHCA PESQUISA
// ===========================
//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechar() {
    window.opener.location.reload();
    window.close();
}


//fecha pesquisa de produto e atualiza campos da form que chamou
// imp = I = atualiza campos de imposto
// imp = N = não atualiza campos de imposto
function fechaProduto(imp, codigo, descProduto, unidade) {
    f = window.opener.document.lancamento;
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    f.submit;
    window.opener.location.reload();
    window.close();
}

function fechaProdutoNf(codigo, descProduto, unidade) {
    debugger;
    f = window.opener.document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'incluir';
    f.submenu.value = 'calcular';
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    f.quant.value = '0,00';
    window.opener.document.getElementById("lancamento").submit();
    f.quant.focus();
    window.close();
}

//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechaProdutoPesquisaNfe(codigo, descProduto, unidade) {
    alert("Código equivalencia incluido com sucesso!!");
    f = document.lancamento;
    f.submenu.value = 'incluiequivalenciaPesquisa';
    f.codProduto.value = codigo;
    f.submit();
}

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaParam(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    debugger;
    f = window.opener.document.lancamento;
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    if(lancamento.from.value != ''){
        f.codProduto.value = codigo;
        f.pesProduto.value = descProduto;
        f.descProduto.value = descProduto;
        f.unidade.value = unidade;
        lancamento.from.value == 'baixa_estoque' ? f.quantAtual.value = quantAtual : '';
        lancamento.from.value == 'baixa_estoque' ? f.valorVenda.value = valorVenda : '';
        lancamento.from.value == 'baixa_estoque' ? f.uniFracionada.value = uniFracionada : '';
    }else{
        if (f.pesProduto != undefined) { //tela de pedido 
            f.codProduto.value = codigonota;
            f.pesProduto.value = codigonota;
            f.mod.value = 'ped';
            f.form.value = window.opener.document.lancamento.form.value;
            f.pesq.value = '||||' + codigo + '|' + codigonota;
        } else {
            f.mod.value = 'est';
            f.form.value = 'nota_fiscal_produto'
        }
        f.submenu.value = 'cadastrar';
        f.submit();
    }
    
    window.close();
}

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaOC(e) {
    debugger;
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(1)").text().trim(); 
    var codFabricante    = linha.find("td:eq(2)").text().trim(); 
    var codNota          = linha.find("td:eq(2)").text().trim(); 
    var descricaoProduto = linha.find("td:eq(4)").text().trim(); 
    var unidade          = linha.find("td:eq(5)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(6)").text().trim();
    
//    if(document.lancamento.acao != ''){
    if(f.codProduto.value == '0'){
        f.codProduto.value      = id;
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
    }else{
        f.codProduto.value      = id;
        f.codFabricante.value   = codFabricante 
        f.codProdutoNota.value  = codNota 
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
        f.unitario.value        = vlrUnitario   
    }
    f.quant.focus();
    window.close();
}


//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisa(e) {
    debugger;
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(1)").text().trim(); 
    var codFabricante    = linha.find("td:eq(2)").text().trim(); 
    if (lancamento.codFabricante.value == "")
        var codNota          = linha.find("td:eq(2)").text();
    else
        var codNota          = lancamento.codFabricante.value;
    codNota = codNota.trim();
    var descricaoProduto = linha.find("td:eq(4)").text().trim(); 
    var unidade          = linha.find("td:eq(5)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(6)").text().trim();

    if(f.codProduto.value == '0'){
        f.codProduto.value      = id;
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
    }else{
        f.codProduto.value      = id;
        f.codFabricante.value   = codFabricante
        f.codProdutoNota.value  = codNota 
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
        f.vlrUnitarioPecas.value        = vlrUnitario   
    }    
    f.quantidadePecas.focus();
    window.close();
}


//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaAtendimento_old(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    debugger;
    f = window.opener.document.lancamento;
    var quantId = "quant"+codigo;
    quantValue = document.getElementsByName(quantId)[0].value;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;

    

    var percDescontoItem = "percDescontoItem"+codigo;
    percDescontoItemValue = document.getElementsByName(percDescontoItem)[0].value;

    var descontoItem = "descontoItem"+codigo;
    descontoItemValue = document.getElementsByName(descontoItem)[0].value;

    if(quantValue == "0,00" || quantValue == ""){
        alert("Digite a quantidade do produto.");
        return false;
    }
    if(vendaValue == "0,00" || vendaValue == ""){
        alert("Digite a Venda do produto.");
        return false;
    }

    var codNota = "codnota"+codigo;
    f.codProdutoNota.value = document.getElementsByName(codNota)[0].value;

    f.codProduto.value = codigo;
    f.descProduto.value = descProduto;
    f.uniProduto.value = unidade;
    f.quantidadePecas.value = quantValue;
    f.vlrUnitarioPecas.value = vendaValue;
    

    f.vlrDescontoPecas.value = descontoItemValue;
    f.percDescontoPecas.value = percDescontoItemValue;
    
    f.mod.value = 'cat';
    f.form.value = 'atendimento'
    f.submenu.value = 'cadastrarPeca';
    f.submit();   
    
    window.close();
}


