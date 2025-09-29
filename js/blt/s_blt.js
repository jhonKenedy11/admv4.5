// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }

    f.submit();
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(formulario, id) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = formulario;
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    }
} // submitAlterar

function submitExcluir(formulario, id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = formulario;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    }
} // submitExcluir

function submitLetra(formulario, letra_pesquisa) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = formulario;
        f.opcao.value = '';
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}
	
// ATUALIZA TIPO DE LANCAMENTO ( RECEBIMENTO / PAGAMENTO )
function generoLancamento(id, desc, tipoLanc) {
    f = window.opener.document.lancamento;

    f.genero.value = id;
    f.descgenero.value = desc;
    
    if (window.opener.document.getElementById('divDescTipo') != null){    
        var labe1= window.opener.document.getElementById('descTipo');
        var div= window.opener.document.getElementById("divDescTipo");
        if (tipoLanc == "R"){
            labe1.innerHTML  = "RECEBIMENTO";
            div.className = "alert alert-success";}
        else {
            labe1.innerHTML  = "PAGAMENTO";
            div.className = "alert alert-danger";}
        f.tipolancamento.value = tipoLanc;
    }

        
    window.close();
}

function abrir(pag)
{

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}
