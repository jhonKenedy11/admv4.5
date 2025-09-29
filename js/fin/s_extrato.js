
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'extrato';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

// submitConfirmar
function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'extrato';
    if ((f.nome.value == '') || (f.valor.value == '') || (f.genero.value == ''))
        alert ('Existem campos com valores obrigatórios!!');
    else {
        if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
            if (f.submenu.value == "cadastrar") {
            f.submenu.value = 'inclui'; }
            else {
            f.submenu.value = 'altera'; }

    f.submit();
        } // if
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'extrato';
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(id) {

    if (confirm('Deseja realmente Alterar este item') == true) {
       f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'extrato';
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    }
}

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'extrato';
//   		   f.opcao.value = formulario;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    }
}
	
 // ----------------------------------------------------------------------
 // ------ SUBMIT LETRA
 // ----------------------------------------------------------------------
function submitLetra() {
    debugger;
    var i;
    var l;

    f = document.lancamento;
    f.mod.value = 'fin';
    // f.form.value = form;
    f.submenu.value = 'letra';
    f.letra.value = '';
    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++){
        if (f.dataReferencia[i].selected){
                f.letra.value = f.dataReferencia[i].value + "|"; }}
    f.letra.value = f.letra.value + f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|" + f.genero.value;

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitlanc[i].value; }}

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + tipolanc[i].value; 	}}

    f.submit();
}	
	
// mostra Cadastro
function submitResumo() {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'extrato';
    f.submenu.value = 'resumo';
    f.submit();
}

// insert Cadastro
function submitCadastroResumo() {
    f = document.lancamento;
    if ((f.centrocusto.value == '') || (f.datavenc.value == '') || (f.genero.value == '') || (f.conta.value == ''))
        alert ('Existem campos com valores obrigatórios!!');
    else {
        if (confirm('Deseja realmente Realizar o fechamento deste período?') == true) {
            f.mod.value = 'fin';
            f.form.value = 'extrato';
            f.submenu.value = 'cadastroresumo';
            f.submit();
        }    
    }    
}


// ----------------------------------------------------------------------
// ------CONSULTA GENERO
// ----------------------------------------------------------------------
	


function abrir(pag)
{
debugger;
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=700,height=800,scrollbars=yes');
}
        
function abrirGenero(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

function limparCampos() {
    if (document.getElementById("pessoa")){
        document.getElementById("pessoa").value = '';
    }
    if (document.getElementById("nome")){
        document.getElementById("nome").value = '';
    }
    
    
    if (document.getElementById("genero")){
        document.getElementById("genero").value = '';
    }

    if (document.getElementById("descgenero")){
        document.getElementById("descgenero").value = '';
    }

}

