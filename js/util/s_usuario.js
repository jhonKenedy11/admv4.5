// desenha Cadastro
function submitVoltar(formulario) {
      f = document.lancamento;
    f.opcao.value = 'usuario';
    f.submenu.value = 'cancelar';
    f.submit();
} // fim submitVoltar

function submitVoltarTeste(formulario) {
      f = document.lancamento;
    f.opcao.value = 'usuario';
    f.submenu.value = 'cancelar';
    f.submit();
} // fim submitVoltar

function submitConfirmarPerfil(){
    f = document.lancamento;
    if (f.senha.value == f.ConfimSenha.value){
        if (f.nomeReduzido.value == ''){
            swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Digite o nome reduzido.'
            }).then(function() {
                f.nomeReduzido.focus();
            });
        } else if (f.login.value == '') {
            swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Digite o login.'
            }).then(function() {
                f.login.focus();
            });
        } else {
            f.submenu.value = 'alterar';
            f.submit();
        }
    } else {
        swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'As senhas não conferem.'
        });
    }
} // submitConfirmarPerfil

function submitConfirmar(formulario) {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione o Cliente.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.pessoa.focus();
            }
        });
        return;
    }
    if (f.nomeReduzido.value == '') {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Digite o nome reduzido.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.nomeReduzido.focus();
            }
        });
        return;
    }
    if (f.usuario.value == "") {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Digite a matrícula.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.usuario.focus();
            }
        });
        return;
    }
    if (f.login.value == '') {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Digite o login.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.login.focus();
            }
        });
        return;
    }
    if (f.senha.value == "") {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Digite a Senha.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.senha.focus();
            }
        });
        return;
    }
    if (f.situacao.value == "") {
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione a situação.',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            willClose: () => {
                f.situacao.focus();
            }
        });
        return;
    }
    else {
        swal.fire({
            title: 'Confirmação',
            text: 'Deseja realmente ' + f.submenu.value + ' este item',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.isConfirmed) {
            f.opcao.value = formulario;
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui'; }
            else {
                f.submenu.value = 'altera'; }

        f.submit();
            }
        });
    }
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.mostra;
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.pessoa.value = "";
//   f.desc.value = "";
    f.submit();
}

function submitAlterar(cliente) {

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Alterar este usuário',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
           f = document.mostra;
           f.opcao.value = 'usuario';
           f.submenu.value = 'alterar';
           f.usuario.value = cliente;
           f.submit();
        }
    });
}
function submitExcluir(cliente) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
      f = document.mostra;
      f.opcao.value = 'usuario';
      f.submenu.value = 'exclui';
      f.usuario.value = cliente;
      f.submit();
        }
    });
}

function submitLetra(letra_pesquisa) {
	f = document.mostra;
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}

function abrir(pag)
{

		window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}