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
function maxLengthInt(lengthField, lengthMax=6) {
    debugger;
    if(lengthField==lengthMax) 
    return false;
}

// desenha Cadastro
function submitVoltar(acao) {
    f = document.lancamento;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar() {
    f = document.lancamento;

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            if ((f.submenu.value == "cadastrar") || (f.submenu.value == "inclui")) {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro() {
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(id) {

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Alterar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
        }
    });
} // submitAlterar

function submitExcluir(id) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
        }
    });
} // submitExcluir

function submitLetraDefault(formulario, letra_pesquisa) {
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