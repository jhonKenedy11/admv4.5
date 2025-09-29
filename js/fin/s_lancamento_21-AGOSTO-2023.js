
//************************
// UTILITARIOS ***********
//************************
// document.addEventListener('keydown', function (event) {
//     // evento pressionar ENTER
//     if (event.key == "Enter") {
//         submitLetra();
//     }// fim evento enter
// });// fim addEventListener

function toggle(obj) {
    var el = document.getElementById(obj);
    if ( el.style.display != 'none' ) {
        el.style.display = 'none';
    }else {
        el.style.display = '';
    }//if
}// function

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function submitMassa(id) {
    f = document.lancamento;
    f.submenu.value = 'massa';
    f.id.value = id;
    f.atividade.value = prompt('Digite a código da ATIVIDADE para lançamento:', '');
    if (f.atividade.value != ""){
        f.submit();
    }
} // fim submitParcela

function submitParcela(id) {
    f = document.lancamento;
    f.submenu.value = 'parcela';
    f.id.value = id;
    f.quantparc.value = prompt('Quantidade de Parcelas para Lançamento', 1);
    if (f.quantparc.value != ""){
        f.submit();
    }
} // fim submitParcela


//==ON CHANGE ===
function dataMovimento() {
    debugger;
    f = document.lancamento;
    if (f.submenu.value == "cadastrar" && f.situacaolancamento.value == 'A'){
        f.datamov.value = f.datavenc.value;
    }
} // fim submitParcela

function calculaTotal(){
    var f = document.lancamento;
    // var original= f.original.value == '' ? '0,00' : f.original.value;
    // var multa=f.multa.value== '' ? '0,00' : f.multa.value;
    // var juros=f.juros.value== '' ? '0,00' : f.juros.value;
    // var adiantamento=f.adiantamento.value== '' ? '0,00' : f.adiantamento.value;
    // var desconto=f.desconto.value== '' ? '0,00' : f.desconto.value;


    var original=f.original.value;
    var multa=f.multa.value;
    var juros=f.juros.value;
    var adiantamento=f.adiantamento.value;
    var desconto=f.desconto.value;
    var total=0;
    var total=parseFloat(original.replace(".","").replace(",","."))+
              parseFloat(multa.replace(".","").replace(",","."))+
              parseFloat(juros.replace(".","").replace(",","."))-
              parseFloat(adiantamento.replace(".","").replace(",","."))-
              parseFloat(desconto.replace(".","").replace(",","."));
    f.total.value = currencyFormat(total);
}

function submitParcela(id) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'parcela';
    f.id.value = id;
    f.quantparc.value = prompt('Quantidade de Parcelas para Lançamento', 1);
    if (f.quantparc.value != ""){
        f.submit();
    }
} // fim submitParcela


//Submit Atualiza
function submitAtual(selObj, id) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = selObj.options[selObj.selectedIndex].value;
    f.id.value = id;
    if (f.submenu.value=='parcela'){
            f.quantparc.value = prompt('Quantidade de Parcelas para Lançamento', 1);
            if (f.quantparc.value != ""){
                    f.submit();
            }	
    }
} // fim submitAtual

// desenha Cadastro

function submitVoltar(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    if(formulario == 'conferencia'){
        l = window.opener.document.conferencia;
        l.submenu.value = 'cancel'
        //l.submit();
        window.close();
    }else{
        f.opcao.value = formulario;
        f.submenu.value = '';
        f.submit();
    }

} // fim submitVoltar

function reenviaCobranca(id) {
    f = document.lancamento;
    if (confirm('Deseja realmente CANCELAR o titulo atual e gerar novo titulo para cobrança bancária?') == true) {
        f.mod.value = 'fin';
        f.form.value = 'lancamento';
        f.submenu.value = 'reenvia';
        f.id.value = id;
        f.submit();
    }    
} // fim submitParcela


function submitConfirmar(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = "";
    var coluna = "";
    var valorRateio = 0; 
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      coluna = row.item(0).firstChild.nodeValue;
      cc = cc + coluna;
      coluna = row.item(1).firstChild.nodeValue;
      cc = cc + "-" + coluna ;
      coluna = row.item(2).getElementsByTagName("input");
      coluna = coluna.item(0).value;
      valorRateio = parseFloat(valorRateio) + parseFloat(coluna) ;    
      cc = cc + "-" + coluna + "|" ;
    }
    if (valorRateio == 0 ){
        var comboCentroCusto = document.getElementById("centrocusto");
        cc = comboCentroCusto.options[comboCentroCusto.selectedIndex].value;
        cc = cc + "-" + comboCentroCusto.selectedIndex;
        ccdesc = comboCentroCusto.options[comboCentroCusto.selectedIndex].text;
        cc = cc + "-100";
        valorRateio = 100;
    } 
    f.rateioCC.value = cc;

    if (f.original.textLength == 0) {
      alert("Permitido somente número inteiro positivo!"); }
    else if (f.genero.value == "")
      alert ('Preencha o campo Gênero!');
    else if (f.nome.value == "")
      alert ('Selecione uma Pessoa!');
    else if (valorRateio != 100)
      alert ('Percentual do rateio maior que o permitido!');
    else if (parseFloat(f.original.value) < 0)
        alert ('Digite um valor para o documento!!');
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

function submitSalvaRateio() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = "";
    var coluna = "";
    var valorRateio = 0; 
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      coluna = row.item(0).firstChild.nodeValue;
      cc = cc + coluna;
      coluna = row.item(1).firstChild.nodeValue;
      cc = cc + "-" + coluna ;
      coluna = row.item(2).getElementsByTagName("input");
      coluna = coluna.item(0).value;
      valorRateio = parseFloat(valorRateio) + parseFloat(coluna) ;    
      cc = cc + "-" + coluna + "|" ;
    }
    if (valorRateio == 0 ){
        var comboCentroCusto = document.getElementById("centrocusto");
        cc = comboCentroCusto.options[comboCentroCusto.selectedIndex].value;
        cc = cc + "-" + comboCentroCusto.selectedIndex;
        ccdesc = comboCentroCusto.options[comboCentroCusto.selectedIndex].text;
        cc = cc + "-100";
        valorRateio = 100;
    } 
    f.rateioCC.value = cc;

    if (f.id.value == '') {
      alert("Salve o lançamento antes de salvar o rateio!"); }
    else {
        f.submenu.value = 'salvarateio';
        f.submit();
    } // if
} // fim submitConfirmar

// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(lancamento_id) {

    if (confirm('Deseja realmente Alterar este item') == true) {
       f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'lancamento';
//   		   f.opcao.value = formulario;
        f.submenu.value = 'alterar';
        f.id.value = lancamento_id;
        f.submit();
    }
}

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
	

// ----------------------------------------------------------------------
// ------ MONTA LETRA
// ----------------------------------------------------------------------
function montaLetra() {
    var i;
    var l;

    f = document.lancamento;

    //f.dataIniDay.valueee
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";

    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++){
            if (f.dataReferencia[i].selected){
                    f.letra.value = f.letra.value + f.dataReferencia[i].value;
            }
    }

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitlanc[i].value; }}

    // filial
    myCheckbox = document.lancamento.elements["filial[]"];

    l = 0;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){  
            f.letra.value = f.letra.value + "|" + filial[i].value;	}}

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + tipolanc[i].value; 	}}

    // situacao documento
    myCheckbox = document.lancamento.elements["sitdocto[]"];

    l = 0;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitdocto[i].value; 	}}

    // Conta
    myCheckbox = document.lancamento.elements["conta[]"];

    l = 0;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){  
            f.letra.value = f.letra.value + "|" + conta[i].value; 	}}


    // Genero Pagamaneto
    if ( f.genero != "0") {
            f.letra.value = f.letra.value + "|" + f.genero.value;
    }

    // TIPO DOCUMENTO
    myCheckbox = document.lancamento.elements["tipoDocto[]"];
    l = 0;
    for  (var i=0;i< tipoDocto.options.length;i++){  
        if (tipoDocto[i].selected == true){ l++; } }
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipoDocto.options.length;i++){  
    if (tipoDocto[i].selected == true){  
        f.letra.value = f.letra.value + "|" + tipoDocto[i].value; 	
    }
    }
    f.letra.value = f.letra.value + "|"; 	



} // MONTA LETRA	
    
    
 // ----------------------------------------------------------------------
 // ------ SUBMIT LETRA
 // ----------------------------------------------------------------------
function submitLetra() {
    var i;
    var l;

    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    //   		f.opcao.value = formulario;
    f.submenu.value = 'letra';
    //f.dataIniDay.valueee
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";

    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++){
            if (f.dataReferencia[i].selected){
                    f.letra.value = f.letra.value + f.dataReferencia[i].value;
            }
    }

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitlanc[i].value; }}

    // filial
    myCheckbox = document.lancamento.elements["filial[]"];

    l = 0;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){  
            f.letra.value = f.letra.value + "|" + filial[i].value;	}}

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + tipolanc[i].value; 	}}

    // situacao documento
    myCheckbox = document.lancamento.elements["sitdocto[]"];

    l = 0;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitdocto[i].value; 	}}

    // Conta
    myCheckbox = document.lancamento.elements["conta[]"];

    l = 0;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){  
            f.letra.value = f.letra.value + "|" + conta[i].value; 	}}


    // Genero Pagamaneto
    if ( f.genero != "0") {
            f.letra.value = f.letra.value + "|" + f.genero.value;
    }

    // TIPO DOCUMENTO
    myCheckbox = document.lancamento.elements["tipoDocto[]"];
    l = 0;
    for  (var i=0;i< tipoDocto.options.length;i++){  
        if (tipoDocto[i].selected == true){ l++; } }
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipoDocto.options.length;i++){  
    if (tipoDocto[i].selected == true){  
        f.letra.value = f.letra.value + "|" + tipoDocto[i].value; 	
    }
    }

    f.submit();
}	
	
function consultaConsolidacao() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'consolidacao';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=consolidacao&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
    }

function consultaLctoData() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'data_analitico';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=data_analitico&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
         
}	

function consultaFluxoCaixa() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'fluxo_caixa';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=fluxo_caixa&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
         
}	

function consultaGenero() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'genero_analitico';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=genero_analitico&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}	

function consultaCentroCusto(hidden) {
    debugger;
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'centrocusto_analitico';
    g.submenu.value = 'imprime';
    
    window.open('index.php?mod=fin&form=centrocusto_analitico&opcao=imprimir&letra=' + g.letra.value + '&relHidden=' + hidden, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}	

function consultaDRE(sitlan) {
	g = document.lancamento;
	montaLetra(sitlan);
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=consulta_dre&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function remessaBancaria() {
	g = document.lancamento;
	montaLetra();
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=remessa_bancaria&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function lancPedDataEntrega() {
	g = document.lancamento;
	montaLetra();
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=rel_lanc_ped_data_entrega&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

// ----------------------------------------------------------------------
// ------CONSULTA GENERO
// ----------------------------------------------------------------------
	


function abrir(pag)
{

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=800,height=550,scrollbars=yes');
}
        
function abrirGenero(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}
    
// ----------------------------------------------------------------------
// ------ CONFERENCIA CAIXA
// ----------------------------------------------------------------------    
         
function fechaConferencia(formulario){
     
    l = document.lancamento;
    l.dadosConf.value = l.pessoa.value + "|" + l.centrocusto.value + "|" + l.docto.value + "|" + l.serie.value + "|" + l.parcela.value + "|" + l.tipolancamento.value + "|" + l.tipodocto.value + "|" + l.situacaodocto.value + "|" + l.situacaolancamento.value + "|" + l.genero.value + "|" + l.modo.value + "|" + l.doctobancario.value + "|" + l.conta.value + "|" + l.cheque.value + "|" + l.datalanc.value + "|" + l.dataemissao.value + "|" + l.datavenc.value + "|" + l.datamov.value + "|" + l.moeda.value + "|" + l.original.value + "|" + l.multa.value + "|" + l.juros.value + "|" + l.adiantamento.value + "|" + l.desconto.value + "|" + l.desconto.value + "|" + l.total.value + "|" + l.obs.value;

    f = window.opener.document.conferencia;

    f.dadosLancamento.value = l.dadosConf.value;
    //alert(f.dadosLancamento.value);

    f.letra.value = l.letraC.value;
    f.submenu.value = 'dadosLancamento'
    f.submit();

    window.close();
}

function submitRecarregar(){
    l = document.lancamento;
    document.getElementById('ancora').click();
    //l.submit();
}

// ATUALIZA TIPO DE LANCAMENTO ( RECEBIMENTO / PAGAMENTO )
function tipoLancamento() {
    f = document.lancamento;
    if (document.getElementById('divDescTipo') != null){
        var labe1= document.getElementById('descTipo');
        var div= document.getElementById("divDescTipo");
        if (f.tipolancamento.value == "R"){
            labe1.innerHTML  = "RECEBIMENTO";
            div.className = "alert alert-success";
        }//if
        else {
            labe1.innerHTML  = "PAGAMENTO";
            div.className = "alert alert-danger";
        }//else
        //f.tipolancamento.value = tipoLanc;
    }//if
} //function

function validaPercentual(formulario) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = 0;
    var coluna = "";
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      coluna = row.item(2).getElementsByTagName("input");
      coluna = coluna.item(0).value;
      cc = parseFloat(cc) + parseFloat(coluna) ;
    }
    
    if (cc > 100) {
        alert ('Percentual de rateio maior que o permitido!');
    } else if (cc < 100) {
        alert ('Percentual de rateio não permitido!');
    } 
   
} // fim submitConfirmar

function agrupaLancModal(){
    debugger
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var tipoDoc = ''
    var pessoa = '';
    var count = 0;
    var lancChecked = false;
    totalMulta          = 0;
    totalJuros          = 0;
    totalDesconto       = 0;
    totalOriginal       = 0;
    totalLanc           = 0;

    dataAtual = new Date();
    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        if (row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[1].childNodes[0].data;    
                dados   = cells[10].childNodes[0].data;   
                arrDados = dados.split("|");
    
                if (pessoa === ''){
                    pessoa = novaPessoa;
                    f.pessoa.value = arrDados[0].trim();
                }
                if(tipoDoc === ''){
                    tipoDoc = arrDados[1].trim();
                }
                if(tipoDoc !== arrDados[1].trim()){
                    alert("Selecione o mesmo tipo de Documento para fazer o Agrupamento de Titulos.");
                    return false;
                }
                
                if(novaPessoa === pessoa){   
    
                    total  = cells[9].childNodes[0].data;
                    valores =  cells[10].childNodes[0].data; ;
                    arrValores = valores.split("|");

                    multa  = arrValores[2].trim();
                    juros  = arrValores[3].trim();
                    desconto  = arrValores[4].trim();
                    original  = arrValores[5].trim();
    
                    total = parseFloat(total.replace(".","").replace(",","."));
                    multa = parseFloat(multa.replace(".","").replace(",","."));
                    juros = parseFloat(juros.replace(".","").replace(",","."));
                    desconto = parseFloat(desconto.replace(".","").replace(",","."));
                    original = parseFloat(original.replace(".","").replace(",","."));
    
                    totalLanc  += total;
                    totalMulta += multa;
                    totalJuros += juros;
                    totalDesconto += desconto;
                    totalOriginal += original;
    
                }else{
                    alert("Selecione a mesma Pessoa para fazer o Agrupamento de Lançamentos.");
                    return false;
                }
                count += 1
            }

        }
        
    }
    if(lancChecked == true && count > 1){
        f.mPessoa.value  = pessoa
        f.mMulta.value   = currencyFormat(totalMulta);
        f.mJuros.value   = currencyFormat(totalJuros);
        f.mDesconto.value   = currencyFormat(totalDesconto);
        f.mOriginal.value = currencyFormat(totalOriginal);
        f.mTotal.value   = currencyFormat(totalLanc);
        f.mDataVencimento.value = dataFormatada;
        $('#modalAgrupamentoLanc').modal('show');
    }else{
        alert("Selecione mais de um Lançamento para fazer o Agrupamento de Lançamentos.");
        return false;
    }
    
}


function baixaLoteLancModal(){
    debugger
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var tipoDoc = ''
    var count = 0;
    var lancChecked = false;
    totalMulta          = 0;
    totalJuros          = 0;
    totalDesconto       = 0;
    totalOriginal       = 0;
    totalLanc           = 0;

    dataAtual = new Date();
    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        if (row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[1].childNodes[0].data;   
                total   = cells[9].childNodes[0].data;    
                dados   = cells[10].childNodes[0].data;   
                arrDados = dados.split("|");

                total = parseFloat(total.replace(".","").replace(",","."));
                totalLanc  += total;
                
                if(tipoDoc === ''){
                    tipoDoc = arrDados[1].trim();
                }
                if(tipoDoc !== arrDados[1].trim()){
                    alert("Selecione o mesmo tipo de Documento para fazer a Baixa de Titulos.");
                    return false;
                }
                
                
                count += 1
            }

        }
        
    }
    // if(lancChecked == true && count > 1){
    if(lancChecked == true){
            f.mDataEmissao.value = dataFormatada;
        f.mTotalBaixar.value   = currencyFormat(totalLanc);
        $('#modalBaixaLote').modal('show');
    }else{
        alert("Selecione mais de um Lançamento para fazer a Baixa.");
        return false;
    }
    
}

function submitAgruparLancamento(){
    debugger
    f = document.lancamento;
    f.dadosLancAgrupamento.value = '';
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = lancs + "|" + row[0].id;
            }
        }
    }

    

    f.dadosLancAgrupamento.value = lancs;
    f.dadosLanc.value = f.pessoa.value + "|" + f.mDataVencimento.value + "|" + f.mMulta.value + "|" + f.mJuros.value + "|" + f.mDesconto.value + "|" + f.mTotal.value + "|" + f.mOriginal.value + "|" + f.mNumDocto.value
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'agruparLanc';
    f.submit()
}

function submitBaixaLancamentoLote(){
    debugger
    f = document.lancamento;
    f.dadosLancAgrupamento.value = '';
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = lancs + "|" + row[0].id;
            }
        }
    }

    f.dadosLanc.value = f.contaCombo.value + "|" + f.mDataEmissao.value + "|" + lancs;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'baixaLanc';
    f.submit()
}

function rel_lanc_baixado_lote(){
    f = document.lancamento;
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = row[0].id + "|" +  lancs;
            }
        }
    }

    f.dadosLanc.value = lancs;
    var letraRel =  f.mDataEmissao.value + "|" + f.contaCombo.value 

    window.open('index.php?mod=fin&form=rel_lanc_baixa_lote&opcao=imprimir&letra=' +letraRel+'&dadosLanc='+f.dadosLanc.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function impSlipLote(){
    f = document.lancamento;
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var lancChecked = false;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                lancs = row[0].id + "|" +  lancs;
            }
        }
    }

    f.dadosLanc.value = lancs;

    if(lancChecked == true){
        window.open('index.php?mod=fin&form=rel_slip_imprime&opcao=imprimir&letra=' +f.dadosLanc.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
    }else{
        alert("Selecione mais de um Lançamento para fazer a impressão do SLIP.");
        return false;
    }
 
}