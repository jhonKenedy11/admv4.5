function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaTotal() {
    debugger
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
    debugger;
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
                        //f.opcao.value = formulario;
                        if (f.submenu.value == "cadastrar") {
                            f.submenu.value = 'inclui';
                        }
                        else {
                            f.submenu.value = 'altera';
                        }

                        f.submit();
                    } // if
                } // else
} // fim submitConfirmar

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

//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechar() {
    window.opener.location.reload();
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
function fechaProdutoPesquisa(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    debugger;
    f = window.opener.document.lancamento;
    f.codProduto.value = codigonota;
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
            f.pesProduto.value = codigonota;
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_telhas'
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
    debugger
    f = window.opener.document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'incluir';
    f.submenu.value = 'calcular';
    f.codProduto.value = codigo;
    f.quant.value = '0,00';
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    window.opener.document.getElementById("lancamento").submit();

    window.close();
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