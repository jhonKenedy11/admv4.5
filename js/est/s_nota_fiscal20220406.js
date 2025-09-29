document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.key == "Enter") {
        submitLetra();
    }// fim evento enter
});// fim addEventListener


// ####################
// desenha Cadastro ###
// ####################

function imprimirCCe(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'cartaCNFEImprimir';
    f.id.value = id;
    f.submit();
}

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    if (confirm('Deseja realmente ' + f.submenu.value + ' esta Nota Fiscal') == true) {
        f.opcao.value = formulario;
        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        }
        else {
            f.submenu.value = 'altera';
        }
        f.submit();
    } // if
} // fim submitConfirmar

// ####################
// mostra NF ##########
// ####################

function montaLetra() {
    l = document.lancamento;
    l.letra.value = l.mfilial.value + "|" + l.mtipo.value + "|" + l.msituacao.value + "|" + l.dataIni.value + "|" + l.dataFim.value + "|" + l.numNf.value + "|" + l.serieNf.value + "|" + l.pessoa.value + "|" + l.idNatop.value + "|" + l.finalidadeEmissao.value + "|" + l.modFrete.value + "|" + l.genero.value + "|" + l.transportador.value + "|" + l.modeloNf.value;
}// submitLetra

function submitLetra() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.submenu.value = 'letra';
    montaLetra();
    f.submit();
}// submitLetra

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitProduto(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'produto';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
}// submitAlterar

function submitReceber(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'receber';
    f.submenu.value = '';
    f.idnf.value = id;
    f.submit();
}// submitAlterar

function submitGerarXML(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'produto';
    f.submenu.value = 'geraXML';
    f.id.value = id;
    f.submit();
}// submitAlterar

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'NotaFiscal';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
}// submitAlterar

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir esta NFe e seus itens?') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal';
        f.opcao.value = 'NotaFiscal';
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if
}// submitExcluir

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

//Submit Atualiza
function submitAtual(selObj, id) {
    if (selObj.options[selObj.selectedIndex].value == 'produto') {
        window.open("p_nota_fiscal_produto.php?idnf=" + id + "&opcao=produto", 'Nota_Fiscao_Produto');
    }//, 'toolbar=yes,location=yes,menubar=yes,width=1000,height=550,scrollbars=yes');}
    if (selObj.options[selObj.selectedIndex].value == 'recebimento') {
        window.open("p_nota_fiscal_produto.php?idnf=" + id + "&opcao=recebimento", 'Nota_Fiscao_Produto');
    }//, 'toolbar=yes,location=yes,menubar=yes,width=1000,height=550,scrollbars=yes');}
    if (selObj.options[selObj.selectedIndex].value == 'imprimir') {
        window.open("p_nota_xml_importa.php?idnf=" + id + "&opcao=imprimir", "&submenu=mostra", 'Nota_Fiscao_Produto');
    }
} // fim submitAtual

/**
 * NOTA FISCAL PRODUTOS
 */

function id(el) {
    return document.getElementById(el);
}

function getMoney(el) {
    var money = id(el).value.replace(',', '.');
    return parseFloat(money);
}

function soma() {
    var total = ((getMoney('unitario') * getMoney('quant')) - (getMoney('desconto')));

    id('total').value = total;
    id('total').value = id('total').value.replace('.', ',');
}

function submitCadastroProdutos(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'produtos';
    f.submenu.value = 'cadastrarProdutos';
    f.idnf.value = id;
    f.submit();
} // submitCadastro

function submitCadastroProdutosMostra(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'produtos';
    f.submenu.value = 'cadastrarProdutos';
    f.idnf.value = id;
    f.submit();
} // submitCadastro

function submitVoltarProdutos(idnf) {

    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'produto';
    f.submenu.value = 'alterar';
    f.id.value = idnf;
    f.submit();
} // fim submitVoltar

function consultaPrint(form) {
    debugger;
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'est';
    g.form.value = form;
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function consultaPrintPeriodo(form) {
    debugger;
    g = document.lancamento;
    
    montaLetra();

    g = document.lancamento;
    g.mod.value = 'est';
    g.form.value = form;
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function printDanfe(id) {
    debugger
    //f = document.lancamento;
    //f.mod.value = 'est';submenu
    //f.form.value = 'nota_fiscal';
    //f.opcao.value = '';
    //f.submenu.value = 'danfe';
    //f.id.value = id;
    //f.submit();submenusubmenu
    window.open('index.php?mod=est&form=nfephp_imprime_danfe&opcao=imprimir&submenu=print&id='+id, 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function geraDanfe(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'geraDANFE';
    f.id.value = id;
    f.submit();
}

function enviaEmailDanfe(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'emailDANFE';
    f.id.value = id;
    f.submit();
}

function cancelaNFE(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'cancelaNFE';
    f.id.value = id;

    if ((f.justificativa.value == '') || (f.justificativa.value.length < 15)) {
        alert('Digite a justificativa do cancelamento com pelo menos 15 caracteres');
    }
    else
        if (confirm('Deseja realmente CANCELAR esta Nota Fiscal') == true) {
            f.submit();
        }
}

function cartaCNFE(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'cartaCNFE';
    f.id.value = id;

    if ((f.carta.value == '') || (f.carta.value.length < 15)) {
        alert('Digite o texto para correção com pelo menos 15 caracteres');
    }
    else
        if (confirm('Deseja realmente ENVIAR A CARTA DE CORREÇÃO para esta Nota Fiscal') == true) {
            f.submit();
        }
}

function inutilizaNFE() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'inutilizaNFE';
    if (f.inutModelo.value == '') {
        alert('Digite o Modelo');
    }
    else
        if (f.inutSerie.value == '') {
            alert('Digite a Serie');
        }
        else
            if (f.inutNumIni.value == '') {
                alert('Digite o Numero Inicial');
            }
            else
                if (f.inutNumFim.value == '') {
                    alert('Digite o Numero Inicial');
                }
                else
                    if (f.inutJustificativa.value == '') {
                        alert('Digite a justificativa');
                    }
                    else
                        if (confirm('Deseja realmente INUTILIZAR este intervalo de Nota Fiscal') == true) {
                            f.submit();
                        }
}

function consultarXMLNFe() {
    f = document.lancamento;
    emailContador = document.getElementById('emailContador').value;
    emailTitulo = document.getElementById('emailTitulo').value;
    emailCorpo = document.getElementById('emailCorpo').value;

    f.email.value = emailContador + "|" + emailTitulo + "|" + emailCorpo;

    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var notas = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("td");
        notas = notas + "|" + row.item(0).id;
    }
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.submenu.value = 'gerarXMLsContabilidade';
    f.letra.value = f.mfilial.value + "|" + f.mtipo.value + "|" + f.msituacao.value + "|" + f.dataIni.value + "|" + f.dataFim.value + "|" + f.numNf.value;
    f.submit();
}

function enviarEmailXmlDanfe() {
    f = document.lancamento;
    if(f.destinatario.value == ''){
        alert('Preencher o campo Para');
        return false;
    }
    if(f.comCopiaPara.value == ''){
        alert('Preencher o campo Assunto');
        return false;
    }
    if(f.emailCorpo.value == ''){
        alert('Preencher o corpo do email.');
        return false;
    }
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.submenu.value = 'emailDANFE';
    f.submit();
    $('#modalEmail').modal('hide')

}


function calculoTributos(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'calculoTributos';
    f.id.value = id;
    f.submit();
}

function consultarPrint(form) {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'est';
    g.form.value = form;
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function calculaTotalFrete() {
    debugger;
    var f = document.lancamento;
    var totalnf = f.totalnf.value;
    var frete = f.frete.value;
    var total = 0;
    var total = parseFloat(frete.replace(".", "").replace(",", ".")) +
        parseFloat(totalnf.replace(".", "").replace(",", "."));
    f.totalnf.value = currencyFormat(total);
}

function calculaTotalDespAcessorias() {
    var f = document.lancamento;
    var totalnf = f.totalnf.value;
    var despacessorias = f.despacessorias.value;
    var total = 0;
    var total =
        parseFloat(despacessorias.replace(".", "").replace(",", ".")) +
        parseFloat(totalnf.replace(".", "").replace(",", "."));
    f.totalnf.value = currencyFormat(total);
}

function calculaTotalSeguro() {
    var f = document.lancamento;
    var totalnf = f.totalnf.value;
    var seguro = f.seguro.value;
    var total = 0;
    var total = parseFloat(seguro.replace(".", "").replace(",", ".")) +
        parseFloat(totalnf.replace(".", "").replace(",", "."));
    f.totalnf.value = currencyFormat(total);
}

function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function onChangeOfValue(element) {
    var oldValue = element.defaultValue;
    var newValue = element.value;
    if (window.confirm('do you really want to change the value to ' + newValue + '?')) {
        element.defaultValue = newValue;
    } else {
        element.value = element.defaultValue;
    }
}

function calculaTotalNf(){
    f= document.lancamento
    var frete = f.frete.value == '' ? "0,00" : frete = f.frete.value;
    var despAcessorias = f.despacessorias.value == '' ? "0,00" : despAcessorias = f.despacessorias.value;
    var seguro = f.seguro.value == '' ? "0,00" : seguro = f.seguro.value;

    var totalNf = f.totalnf.value == '' ? "0,00" :  totalNf = f.totalnf.value;
    if(f.totalOriginal.value == ''){
        f.totalOriginal.value = f.totalnf.value;
    }


    frete = parseFloat(frete.replace(".", "").replace(",", "."))
    despAcessorias = parseFloat(despAcessorias.replace(".", "").replace(",", "."))
    seguro = parseFloat(seguro.replace(".", "").replace(",", "."))

    totalNf = parseFloat(f.totalOriginal.value.replace(".", "").replace(",", "."));

    novoTotal = (totalNf + frete + despAcessorias + seguro);

    f.totalnf.value = currencyFormat(novoTotal);
}

  function submitVoltarNfMostra(id) {
    f = document.mostra;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'NotaFiscal';
    f.submenu.value = 'voltarDevolucao';
    f.id.value = id;
    f.submit();
} // fim submitVoltar


function submitDevolucaoNf(){
    debugger
    f = document.lancamento;
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var pessoa = '';
    var count = 0;
    var nfChecked = false;
    var notaFiscais = "";
    

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        if (row.length > 0){
            if (row[0].checked == true) {
                nfChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[7].childNodes[0].data;    
                nfId   = cells[1].childNodes[0].data;   
    
                if (pessoa === ''){
                    pessoa = novaPessoa;
                }
                
                if(novaPessoa === pessoa){   
                    notaFiscais = notaFiscais + "|" + nfId.trim();
                }else{
                    alert("Selecione a mesma Pessoa para fazer a Devolução de Nf.");
                    return false;
                }
                count += 1
            }

        }
        
    }
    if(nfChecked == true){
        f.devolucaoNotaFiscal.value = "";
        f.devolucaoNotaFiscal.value = notaFiscais;
        f.submenu.value = "devolucaoNotaFiscal";
        f.submit();
       
    }else{
        alert("Selecione mais de uma Nf para fazer a Devolução.");
        return false;
    }
    
}

function qtdeDevolucao(id,value){
    var qtdeDevolucao = value.replace(".", "").replace(",", ".")
    var qtde = document.getElementById("qtde"+id).innerText;
    qtde = qtde.replace(".", "").replace(",", ".");

    qtdeDevolucao = parseFloat(qtdeDevolucao);
    qtde = parseFloat(qtde);

    if(qtde < qtdeDevolucao){
        alert("A Quantidade de Devolução maior que a Qtde do Produto.");
        return false;
    }
    
}

function submitDevolucao(id){
    debugger
    f = document.mostra;
    f.form.value = 'nota_fiscal';
    f.mod.value = 'est';
    var table = document.getElementById("datatable-buttons");

    var r = table.rows.length;
    var dadosNf = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        
        if (row.prodChecked.checked == true) {
            var idProd = row[0].id;
            var qtdeDev = document.getElementById("quantDevolucao"+idProd).value;
            var unitario = document.getElementById("vlrUnitario"+idProd).value;
            var cfop = document.getElementById("cfop"+idProd).value;
            
            dadosNf = dadosNf + "|" + "*" + idProd + "*"  + qtdeDev + "*" + 
                     unitario + "*" + cfop 
        }
    }
    if(dadosNf == ""){
        alert("Selecione o(s) Produtos Para devolução");
        return false;
    }
    f.nfProdutos.value = dadosNf;
    f.id.value = id;
    f.submenu.value = "alteraDevolucao";
    f.submit();
}

function submitAlterarNfProduto(id) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal_produto';
        f.opcao.value = 'produto';
        f.submenu.value = 'alterarDev';
        f.telaOrigem.value = 'alterarDevolucao'
        f.id.value = id;
        f.idnf.value = id;
        f.submit();
    }
}

function limpaDadosForm() {
    
    f = document.lancamento;
    f.letra.value = '';
    f.idnf.value = ''
    f.id.value = '';
    f.opcao.value = '';
    f.pessoa.value = '';
    f.fornecedor.value = '';
    f.notas_xml.value = '';
    f.email.value = '';
    f.devolucaoNotaFiscal.value = '';
    f.transportador.value = '';
    f.transpNome.value = '';
    f.genero.value = '';
    f.descgenero.value = '';
    f.modFrete.value = '';
    f.finalidadeEmissao.value = '';
    f.idNatop.value = '';




    f.numNf.value = '';
    f.serieNf.value = '';

    f.nome.value = '';
}

function submitSelecionarTodos(){

    var checkValue = document.mostra.todosChecked.value;
    if(checkValue == ''){
        checkValue = true;
    }else if (checkValue == 'true'){
        checkValue = false;
    }else{
        checkValue = true;
    }
    var table = document.getElementById("datatable-buttons");

    var r = table.rows.length;
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        
        if (row.prodChecked.checked != checkValue) {
            row.prodChecked.checked = checkValue;
        }
    }

    document.mostra.todosChecked.value = checkValue;

}