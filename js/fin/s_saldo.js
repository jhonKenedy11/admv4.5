// desenha Cadastro

	function submitVoltar() {
            f = document.lancamento;
            f.submenu.value = '';
            f.submit();
	} // fim submitVoltar

	function submitConfirmar() {
			debugger;
            f = document.lancamento;
            if (confirm('Deseja realmente  este item') == true) {
				if (f.submenu.value == "cadastrar") {
						f.submenu.value = 'inclui'; }
					else {
						f.submenu.value = 'altera'; }
				f.submit();
            } // if
    } // fim submitConfirmar


// mostra Cadastro
	
	function submitCadastro() {
   		f = document.lancamento;
                f.letra.value = f.mesSaldo.value + "|" + f.anoSaldo.value + "|" + f.contaPes.value;
   		f.submenu.value = 'cadastrar';
                f.id.value = "";
   		f.submit();
	}
	
	function submitPesquisar() {
		f = document.lancamento;
   		f.submenu.value = 'consulta';
                f.letra.value = f.mesSaldo.value + "|" + f.anoSaldo.value + "|" + f.contaPes.value;
   		f.submit();
	}

	function submitAlterar(id) {

            if (confirm('Deseja realmente Alterar este item') == true) {
                   f = document.lancamento;
   		   f.submenu.value = 'alterar';
   		   f.id.value = id;
   		   f.submit();
   		}
	}
    function submitExcluir(id) {
            if (confirm('Deseja realmente Excluir este item') == true) {
                   f = document.lancamento;
   		   f.submenu.value = 'exclui';
   		   f.id.value = id;
   		   f.submit();
   		}
	}