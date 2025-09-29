function submitVoltar() {
	const f = document.forms['lancamento'];
	f.submenu.value = 'cancelar';
	f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    const f = document.forms['lancamento'];
    const direitosSelect = document.getElementById('direitos');
    
    // Atualiza valores dos selects
    f.usuario.value = document.getElementById('usuario-id').value;
    f.direitoUser.value = Array.from(direitosSelect.selectedOptions)
                                .map(option => option.value)
                                .join('');

    // Validações
    if (!f.usuario.value) {
        alert("Selecione um usuário.");
        return;
    }
    
    if (!f.programa.value) {
        alert("Selecione uma opção do menu.");
        return;
    }

    // Confirmação
    const acao = f.submenu.value === 'cadastrar' ? 'incluir' : 'alterar';
    
    Swal.fire({
        title: 'Confirmar ação?',
        text: `Deseja realmente ${acao} esta autorização?`,
        icon: 'warning',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            f.opcao.value = formulario;
            f.submenu.value = f.submenu.value === 'cadastrar' ? 'inclui' : 'altera';
            f.submit();
        }
    });
}// fim submitConfirmar


// mostra Cadastro
function submitCadastro(usuario, nome) {
	const f = document.forms['mostra'];
	f.submenu.value = 'cadastrar';
	f.usuario.value = usuario;
	f.nome.value = nome;
	f.submit();
}

function submitAlterar(usuario, programa) {
	Swal.fire({
        title: 'Confirmar Alteração?',
        text: "Você tem certeza que deseja alterar esta autorização?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
		const f = document.forms['mostra'];
		f.opcao.value = 'autoriza';
		f.submenu.value = 'alterar';
		f.usuario.value = usuario;
		f.programa.value = programa;
		f.submit();
	}
	});
}
function submitExcluir(usuario, programa) {
    Swal.fire({
        title: 'Confirmar exclusão?',
        text: "Você tem certeza que deseja excluir esta autorização?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.forms['mostra'];
            f.opcao.value = 'autoriza';
            f.submenu.value = 'exclui';
            f.usuario.value = usuario;
            f.programa.value = programa;
            f.submit();
        }
    });
}

