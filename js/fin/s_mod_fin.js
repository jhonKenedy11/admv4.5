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

// desenha Cadastro
function submitVoltar(acao) {
    f = document.lancamento;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;

    if (confirm('Deseja realmente ' + f.submenu.value + '?') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro() {
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(id) {

    if (confirm('Deseja realmente Alterar?') == true) {
        f = document.lancamento;
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    }
} // submitAlterar

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir?') == true) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    }
} // submitExcluir

function submitLetra(formulario, letra_pesquisa) {
        f = document.lancamento;
        f.opcao.value = '';
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}
	
function abrir(pag)
{

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}