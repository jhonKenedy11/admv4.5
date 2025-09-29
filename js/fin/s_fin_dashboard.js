function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}


function submitVoltar(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    if (formulario == 'conferencia') {
        l = window.opener.document.conferencia;
        l.submenu.value = 'cancel'
        //l.submit();
        window.close();
    } else {
        f.opcao.value = formulario;
        f.submenu.value = '';
        f.submit();
    }

} // fim submitVoltar



function submitConfirmar(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var cc = "";
    var coluna = "";
    var valorRateio = 0;
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("td");
        coluna = row.item(0).firstChild.nodeValue;
        cc = cc + coluna;
        coluna = row.item(1).firstChild.nodeValue;
        cc = cc + "-" + coluna;
        coluna = row.item(2).getElementsByTagName("input");
        coluna = coluna.item(0).value;
        valorRateio = parseFloat(valorRateio) + parseFloat(coluna);
        cc = cc + "-" + coluna + "|";
    }
    if (valorRateio == 0) {
        var comboCentroCusto = document.getElementById("centrocusto");
        cc = comboCentroCusto.options[comboCentroCusto.selectedIndex].value;
        cc = cc + "-" + comboCentroCusto.selectedIndex;
        ccdesc = comboCentroCusto.options[comboCentroCusto.selectedIndex].text;
        cc = cc + "-100";
        valorRateio = 100;
    }
    f.rateioCC.value = cc;

    if (f.original.textLength == 0) {
        alert("Permitido somente número inteiro positivo!");
    }
    else if (f.genero.value == "")
        alert('Preencha o campo Gênero!');
    else if (f.nome.value == "")
        alert('Selecione uma Pessoa!');
    else if (valorRateio != 100)
        alert('Percentual do rateio maior que o permitido!');
    else if (parseFloat(f.original.value) < 0)
        alert('Digite um valor para o documento!!');
    else {
        if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
            f.opcao.value = formulario;
            if ((f.submenu.value == "alterar") || (f.submenu.value == "altera")) {
                f.submenu.value = 'altera';
            } else {
                f.submenu.value = 'inclui';
            }
        }
        // alert(f.opcao.value);
        f.submit();
    } // if
} // fim submitConfirmar


function submitExcluir(lancamento_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'lancamento';
        //   		   f.opcao.value = formulario;
        f.submenu.value = 'exclui';
        f.id.value = lancamento_id;
        f.submit();
    }
}


// function submitPesquisar() {
//     debugger
//     const f = document.lancamento;
//     f.letra.value = "";
//     f.submenu.value = "pesquisa";

//     const tipolancamento = getSelectedValues(tipolanc);
//     const situacaoLancamento = getSelectedValues(sitlanc);
//     const filial = getSelectedValues(filial);

//     f.letra.value = [
//         f.dataIni.value,
//         f.dataFim.value,
//         f.dataReferencia.value,
//         tipolancamento,
//         situacaoLancamento,
//         filial
//     ].join("|");

//     console.log(f.letra.value);
//     f.submit();
// }

// function getSelectedValues(selectElement) {
//     return Array.from(selectElement.selectedOptions).map(option => option.value).join(",");
// }

function submitPesquisar() {
    debugger
    var i;
    var l;

    f = document.dashboard;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    //f.dataIniDay.valueee
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";

    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++) {
        if (f.dataReferencia[i].selected) {
            f.letra.value = f.letra.value + f.dataReferencia[i].value;
        }
    }

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for (var i = 0; i < sitlanc.options.length; i++) {
        if (sitlanc[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < sitlanc.options.length; i++) {
        if (sitlanc[i].selected == true) {
            f.letra.value = f.letra.value + "|" + sitlanc[i].value;
        }
    }

    // filial
    myCheckbox = document.lancamento.elements["filial[]"];

    l = 0;
    for (var i = 0; i < filial.options.length; i++) {
        if (filial[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < filial.options.length; i++) {
        if (filial[i].selected == true) {
            f.letra.value = f.letra.value + "|" + filial[i].value;
        }
    }

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for (var i = 0; i < tipolanc.options.length; i++) {
        if (tipolanc[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < tipolanc.options.length; i++) {
        if (tipolanc[i].selected == true) {
            f.letra.value = f.letra.value + "|" + tipolanc[i].value;
        }
    }

    // situacao documento
    myCheckbox = document.lancamento.elements["sitdocto[]"];

    l = 0;
    for (var i = 0; i < sitdocto.options.length; i++) {
        if (sitdocto[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < sitdocto.options.length; i++) {
        if (sitdocto[i].selected == true) {
            f.letra.value = f.letra.value + "|" + sitdocto[i].value;
        }
    }

    // Conta
    myCheckbox = document.lancamento.elements["conta[]"];

    l = 0;
    for (var i = 0; i < conta.options.length; i++) {
        if (conta[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < conta.options.length; i++) {
        if (conta[i].selected == true) {
            f.letra.value = f.letra.value + "|" + conta[i].value;
        }
    }


    // Genero Pagamaneto
    if (f.genero != "0") {
        f.letra.value = f.letra.value + "|" + f.genero.value;
    }

    // TIPO DOCUMENTO
    myCheckbox = document.lancamento.elements["tipoDocto[]"];
    l = 0;
    for (var i = 0; i < tipoDocto.options.length; i++) {
        if (tipoDocto[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < tipoDocto.options.length; i++) {
        if (tipoDocto[i].selected == true) {
            f.letra.value = f.letra.value + "|" + tipoDocto[i].value;
        }
    }

    f.submit();
}

