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


function submitRemessaConfere() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria_confere';
    f.submenu.value = 'mostra';

    if ((f.fileArq.value != '') && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.fileArq.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }    
   
}


function submitRetornoConfere() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario_confere';
    f.submenu.value = 'mostra';

    if ((f.fileArq.value != '') && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.fileArq.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }    
   
}

function submitLetraRetorno() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario';
    f.submenu.value = 'mostra';

    if ((f.fileArq.value != '') && (f.contaBanco.value != '') && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.contaBanco.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }    
   
}

function submitConfirmaRetorno() {
    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario';
    if (confirm('Deseja realmente processar os registros de Retorno Bancário?') == true) {
        f.submenu.value = 'retorno'; 
        f.submit();
    }    
    else{
        f.submenu.value = 'mostra'; }

} // fim submitConfirmarRemessa


function submitLetraRemessa() {

    f = document.remessa;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria_'+f.banco.value;
    f.submenu.value = 'mostra';
    alert("passou ==>> "+f.form.value);
    if ((f.contaBanco.value != '') && (f.filial.value != '')) {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value + "|" + f.contaBanco.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }    
   
}

function submitConfirmaRemessa() {
    f = document.remessa;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria_'+f.banco.value;
    if (confirm('Deseja realmente gerar arquivo de remessa'+f.form.value) == true) {
        // f.submenu.value = 'gerar'+f.banco.value; 
        f.submenu.value = 'gerar'; 
        if ((f.contaBanco.value != '') && (f.filial.value != '')) {
            f.letra.value = f.dataConsulta.value + "|" + f.filial.value + "|" + f.contaBanco.value;
            f.submit();
            }    
        else {
            alert('Selecione as opções desejada.');
        }    
    }    
    else{
        f.submenu.value = 'mostra'; }

} // fim submitConfirmarRemessa



function abrir(pag)
{

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}