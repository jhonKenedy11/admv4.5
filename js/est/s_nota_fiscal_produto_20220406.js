// desenha Cadastro
function submitVoltarProduto(id, opcao) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = opcao;
    f.submenu.value = '';
    f.id.value = id;
    f.submit();
} // fim submitVoltar

function submitVoltarNf(id) {
    f = document.mostra;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'NotaFiscal';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
} // fim submitVoltar

function submitVoltarNfMostra(id) {
    f = document.mostra;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal';
    f.opcao.value = 'NotaFiscal';
    f.submenu.value = '';
    f.id.value = id;
    f.submit();
} // fim submitVoltar
function submitVoltarDevolucaoNf(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'inclui';
    f.submenu.value = 'alterarDevolucao';
    f.id.value = id;
    f.submit();
} 

function submitConfirmar(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    //f.submenu.value = 'cadastrar';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        //f.opcao.value = formulario;

        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        }
        else if (f.submenu.value == "alterar") {
            f.submenu.value = 'altera';
        }
        else if (f.submenu.value == "baixar") {
            f.submenu.value = 'baixa';
        }

        f.submit();
    } // if
} // fim submitConfirmar

function submitConfirmarDevolucaoNf(id, idnf) {
    debugger;
    f = document.lancamento;
    var idnf = f.idnf.value;
    var id   = f.id.value;
    
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.id.value = id;
    f.idnf.value =  idnf;
    f.opcao.value = 'devolucao';

    var pessoaTeste = f.pessoa.value;
    //f.submenu.value = 'cadastrar';
    var submenu = f.submenu.value;
    if (confirm('Deseja realmente alterar este item') == true) {
        //f.opcao.value = formulario;

        if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui';
        }
        else if (f.submenu.value == "alterarDev") {
            f.submenu.value = 'alterarDevolucaoNf';
        }
        else if (f.submenu.value == "baixar") {
            f.submenu.value = 'baixa';
        }

        f.submit();
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(id) {

    f = document.mostra;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'produto';
    f.submenu.value = 'cadastrar';

    f.idnf.value = id;
    f.codProduto.value = "";

    f.submit();
}

function submitAlterar(id) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.mostra;
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal_produto';
        f.opcao.value = 'produto';
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    }
}

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.mostra;
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal_produto';
        f.opcao.value = 'produto';
        f.submenu.value = 'excluir';
        f.id.value = id;
        f.submit();
    }
}
function submitBaixar(id) {

    if (confirm('Deseja realmente Baixar este Produto') == true) {
        f = document.mostra;
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal_produto';
        f.opcao.value = 'receber';
        f.submenu.value = 'baixar'; // mostra tela para ajuste de campos
        f.id.value = id;
        f.submit();
    }
}

function submitCalcular() {

    f = document.lancamento;
    f.mod.value = 'est';
    f.opcao.value = f.form.value;
    f.form.value = 'nota_fiscal_produto';
    f.submit();
    //}
}

function abrir(pag, form =''){
    debugger;
    f = document.lancamento;
    f.opcao.value = f.form.value;
    f.submenu.value = 'calcular';

    if(form === ''){
        screenWidth = screen.width;
        screenHeight = screen.height;
      }else{
        screenWidth = 750;
        screenHeight = 650;
      }

    window.open(pag, 'Pesquisar', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function submitImprimir(idnf, submenu){
    window.open("p_imprime_etiqueta.php?letra="+idnf +"&submenu="+submenu, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

/*
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
}*/

function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function soma(){
    debugger;
    var f        = document.lancamento;
    var quant    = f.quant.value;
    var unitario = f.unitario.value;
    var desconto = f.desconto.value;

    quant    = parseFloat(quant.replace(".","").replace(",","."))
    unitario = parseFloat(unitario.replace(".","").replace(",","."))
    desconto = parseFloat(desconto.replace(".","").replace(",","."))

    var total = 0;

    totalValor = ((quant * unitario) - desconto);
    total      = currencyFormat(totalValor);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.total.value = total;
}

function calculaIcms(){
    var f        = document.lancamento;
    var bcIcms   = f.bcIcms.value;
    var aliqIcms = f.aliqIcms.value;

    bcIcms   = parseFloat(bcIcms.replace(".","").replace(",","."))
    aliqIcms = parseFloat(aliqIcms.replace(".","").replace(",","."))

    var total = 0;

    totalIcms = ((bcIcms * aliqIcms) / 100);
    total     = currencyFormat(totalIcms);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorIcms.value = total;

}

function calculaIcmsFcpUfDest(){
    var f             = document.lancamento;
    var bcfcpufdest   = f.bcfcpufdest.value;
    var aliqfcpufdest = f.aliqfcpufdest.value;
    
    bcfcpufdest   = parseFloat(bcfcpufdest.replace(".","").replace(",","."))
    aliqfcpufdest = parseFloat(aliqfcpufdest.replace(".","").replace(",","."))

    var total = 0;

    totalIcmsFcpUfDest = ((bcfcpufdest * aliqfcpufdest) / 100);
    total              = currencyFormat(totalIcmsFcpUfDest);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorfcpufdest.value = total;

}

function calculaIcmsFcpSt(){
    var f        = document.lancamento;
    var bcfcpst   = f.bcFcpSt.value;
    var aliqfcpst = f.aliqfcpst.value;

    bcfcpst   = parseFloat(bcfcpst.replace(".","").replace(",","."))
    aliqfcpst = parseFloat(aliqfcpst.replace(".","").replace(",","."))

    var total = 0;

    totalIcms = ((bcfcpst * aliqfcpst) / 100);
    total     = currencyFormat(totalIcms);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorfcpst.value = total;

}

function calculaIpi(){
    var f       = document.lancamento;
    var baseCalculoIpi   = f.baseCalculoIpi.value;
    var aliqIpi = f.aliqIpi.value;

    baseCalculoIpi   = parseFloat(baseCalculoIpi.replace(".","").replace(",","."))
    aliqIpi = parseFloat(aliqIpi.replace(".","").replace(",","."))

    var total = 0;

    totalipi = ((baseCalculoIpi * aliqIpi) / 100);
    total    = currencyFormat(totalipi);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorIpi.value = total;

}

function calculaPis(){
    var f       = document.lancamento;
    var bcPis   = f.bcPis.value;
    var aliqPis = f.aliqPis.value;

    bcPis   = parseFloat(bcPis.replace(".","").replace(",","."))
    aliqPis = parseFloat(aliqPis.replace(".","").replace(",","."))

    var total = 0;

    totalpis = ((bcPis * aliqPis) / 100);
    total    = currencyFormat(totalpis);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorPis.value = total;

}

function calculaCofins(){
    var f          = document.lancamento;
    var bcCofins   = f.bcCofins.value;
    var aliqCofins = f.aliqCofins.value;

    bcCofins   = parseFloat(bcCofins.replace(".","").replace(",","."))
    aliqCofins = parseFloat(aliqCofins.replace(".","").replace(",","."))

    var total = 0;

    totalCofins = ((bcCofins * aliqCofins) / 100);
    total       = currencyFormat(totalCofins);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.valorCofins.value = total;

}

$("#btnSubmit").click(function(event) {

    // Fetch form to apply custom Bootstrap validation
    var form = $("#myForm")

    if (form[0].checkValidity() === false) {
      event.preventDefault()
      event.stopPropagation()
    }
    
    form.addClass('was-validated');
    // Perform ajax submit here...
    
});
