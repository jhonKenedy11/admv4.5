function submitCobranca() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'cobranca';
    f.submit();
}

// desenha Cadastro
function submitGerarFinanceiro() {
    debugger;
    f = document.lancamento;
    f.mod.value = "est";
    f.form.value = "nota_xml_importa";

    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    condPagamentosDesc = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
                condPagamentosDesc = condPgto[i].text;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }

    f.letra.value = f.numero.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        centroCustos + "|" + generos + "|" + condPagamentos;


    var rows = document.getElementById("datatable-buttons-1").getElementsByTagName("tr");

    var $dadosFinanceiros = "";
    var $totalFinanceiro = 0;

    for (row = 1; row < rows.length; row++) {

        var cells = rows[row].getElementsByTagName("td");
        var field0 = cells[0].childNodes[0].data;
        var field1 = cells[1].childNodes[1].value;
        var field2 = cells[2].childNodes[1].value;
        var field3 = cells[3].childNodes[1].value;
        var field4 = cells[4].childNodes[1].value;
        var field5 = cells[5].childNodes[1].value;
        var field6 = cells[6].childNodes[1].value;
        

        var $moeda = (field2).toString();

        //$moeda = $moeda.replace(".", "");

        $moeda = $moeda.replace(",", ".");

        $moeda = parseFloat($moeda);

        $totalFinanceiro = $totalFinanceiro + $moeda;

        $dadosFinanceiros = $dadosFinanceiros + "|" + field0 + "*" +
            field1 + "*" + $moeda + "*" + field3 + "*" +
            field4 + "*" + field5 + "*" + field6;

    }

    $totalFinanceiro = $totalFinanceiro.toFixed(2);

    f.dadosFinanceiros.value = $dadosFinanceiros;

    var $total = f.total.value;

    //$total = $total.replace(".", "");

    $total = $total.replace(",", ".");

    $total = parseFloat($total);

    if ($total != $totalFinanceiro) {
        alert('Soma total das parcelas, não é igual ao total da fatura!');
    } else {
        if (confirm('Deseja realmente INCLUIR FATURAMENTO') == true) {
            f.submenu.value = 'gerarfinanceiro';
        }
        else {
            f.submenu.value = '';
        }
        f.submit();
    }

}

function submitAtualPedidoCondPG(adicionar, numParcelaAdd) {
    debugger;
    f = document.lancamento;
    if (adicionar == "S") {
        if ((numParcelaAdd + 1) < 0) {
            f.numParcelaAdd.value = 0;
        } else {
            f.numParcelaAdd.value = numParcelaAdd + 1;
        }
    } else {
        f.numParcelaAdd.value = 0;
    }

    //NATURAZA DE OPERACAO
    first = true;
    naturaDeOperacoes = '';
    for (var i = 0; i < idNatop.options.length; i++) {
        if (idNatop[i].selected == true) {
            if (first == true) {
                first = false;
                naturaDeOperacoes = idNatop[i].value;
            }
            else naturaDeOperacoes = naturaDeOperacoes + "," + idNatop[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }


    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }
    f.letra.value = f.numero.value + "|" + f.data.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        naturaDeOperacoes + "|" + condPagamentos + "|" + centroCustos + "|" + generos;

    f.submenu.value = 'condpg';
    f.submit();
}

function submitConfirmar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'enviar';
    f.submit();
} // fim submitVoltar

function submitVoltar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

// mostra Cadastro
function submitPesquisa() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'pesquisa';
    f.submit();
}

// mostra Nota Fiscal
function submitVisualizar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'mostra';
    f.submit();
}


/*function submitVisualizar() {
    
    var form = $("form[name=upload]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: form,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function (response) {
            debugger
            console.log(response);
            var result = $('<div />').append(response).find('#demo').html();
            $("#demo").html(result);
            
        }
    });
    return false;
} */



// cadastrar Nota Fiscal
function submitCadastrar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'cadastrar';
    f.submit();
}

// confere Fornecedor / Produtos
function submitConfere() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'conferir';
    f.submit();
}

function abrir(pag, xml) {

    window.open("../../temp/notafiscalxml.php?xml=" + xml, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=500,scrollbars=yes');
}

//function insertConta(url, windowoption, name, params)
function submitInsertJson(params) {

    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        if (params.conta[i].campo == 'submenu') input.value = params.conta[i].valor = 'cadastrar';
        else input.value = params.conta[i].valor;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    //note I am using a post.htm page since I did not want to make double request to the page 
    //it might have some Page_Load call which might screw things up.
    window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
}

function submitSearchJson(params) {
    debugger;
    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        if (params.conta[i].campo == 'submenu') { input.value = 'pesquisar'; }
        else if (params.conta[i].campo == 'opcao') {
            input.value = 'pesquisarnfe';
        }
        else { input.value = params.conta[i].valor; }
        form.appendChild(input);
    }

    document.body.appendChild(form);
    //note I am using a post.htm page since I did not want to make double request to the page 
    //it might have some Page_Load call which might screw things up.
    window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
}

function mudaCodProdXml(nrItem) {
    f = document.upload;

    var xml = f.xml_arq.value;

    prodName = "codProd"+nrItem
    prodDefault = document.getElementsByName(prodName)[0].defaultValue;   
    prodNew = document.getElementsByName(prodName)[0].value;  
    
    prodOldTag = "<det nItem=\""+nrItem+"\"><prod><cProd>"+prodDefault+"</cProd>"
    prodNewTag = "<det nItem=\""+nrItem+"\"><prod><cProd>"+prodNew+"</cProd>"

    var xml_result = xml.replace(prodOldTag, prodNewTag);

    f.xml_arq.value = xml_result;

}