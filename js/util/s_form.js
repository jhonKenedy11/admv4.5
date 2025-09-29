// desenha Cadastro

	function submitVoltar(formulario) {
      f = document.lancamento;
	  f.mod.value = 'util';
    	f.form.value = 'form';
   		f.submenu.value = '';
   		f.submit();
	} // fim submitVoltar

	function submitConfirmar(formulario) {
      f = document.lancamento;
	  f.mod.value = 'util';
      f.form.value = 'form';
		swal.fire({
			title: 'Confirmação',
			text: 'Deseja realmente ' + f.submenu.value + ' este item?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Sim',
			cancelButtonText: 'Não'
		}).then((result) => {
			if (result.isConfirmed) {
				if (f.submenu.value == "cadastrar") {
					f.submenu.value = 'inclui';
				} else {
					f.submenu.value = 'altera';
				}
				f.submit();
			}
		});
	}

// mostra Cadastro
	function submitCadastro(formulario) {
   		f = document.lancamento;
		f.mod.value = 'util';
		f.form.value = 'form';
   		// f.opcao.value = formulario;
   		f.submenu.value = 'cadastrar';
    	f.id.value = "";
   		f.submit();
	}

	function submitAlterar(id) {

        swal.fire({
			title: 'Confirmação',
			text: 'Deseja realmente Alterar este item',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Sim',
			cancelButtonText: 'Não'
		}).then((result) => {
			if (result.isConfirmed) {
        	f = document.lancamento;
			f.mod.value = 'util';
      		f.form.value = 'form';
   		    f.submenu.value = 'alterar';
   		    f.id.value = id;
   		    f.submit();
   		}
		});
	}
    function submitExcluir(id) {
        swal.fire({
			title: 'Confirmação',
			text: 'Deseja realmente Excluir este item',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Sim',
			cancelButtonText: 'Não'
		}).then((result) => {
			if (result.isConfirmed) {
           f = document.lancamento;
		   f.mod.value = 'util';
		   f.form.value = 'form';
   		   f.submenu.value = 'exclui';
   		   f.id.value = id;
   		   f.submit();
   		}
		});
	}


    function submitLetra(letra_pesquisa) {
            f = document.lancamento;
            f.opcao.value = '';
            f.submenu.value = 'letra';
            f.letra.value = letra_pesquisa;
            f.submit();
    }