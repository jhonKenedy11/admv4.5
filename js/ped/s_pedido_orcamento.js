function submitVoltar() {
		f = document.lancamento;
		f.opcao.value = formulario;
		f.submenu.value = '';
		f.submit();
} // fim submitVoltar

function submitConfirmar() {
		f = document.lancamento;
		if (f.mes.value ==""){
		alert("Digite a Mes.");
		}else
		if (f.ano.value ==""){
		alert("Digite a Ano.");
		}else
		
		if (f.valor.value ==""){
		alert("Digite o Valor.");
		}
	else {
		if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
				f.opcao.value = formulario;
			if (f.submenu.value == 'cadastrar') {
				f.submenu.value = 'inclui'; 
				}
				else {
				f.submenu.value = 'altera'; 
				}

				f.submit();
			} // if
	} // else
		
} // fim submitConfirmar	

//--------------------------------------------------------------------------------
// mostra Cadastro
//--------------------------------------------------------------------------------

function submitCadastro() {
	 
	f = document.lancamento;
	f.submenu.value = 'cadastrar';
			f.letra.value = f.mesBase.value + "|" + f.anoBase.value + "|" + f.filial.value;
			f.submit();
}

function submitGeraOrcamento() {
	f = document.lancamento;
	if (f.mesBase.value ==""){
		alert("Selecione o mes Base.");
	}else
		
		if (f.anoBase.value ==""){
			alert("Digite o ano Base.");
	}else{
				if (confirm(f.filial.value+' Deseja realmente Gerar Previsão para o mes '+f.mesBase.value + "/" + f.anoBase.value) == true) {
			f.submenu.value = 'gerar';
				f.letra.value = f.mesBase.value + "|" + f.anoBase.value + "|" + f.filial.value + "|" + f.media.value;
			f.submit();
				}    
	}
}

	function submitParOrcamento() {
	f = document.lancamento;
	f.submenu.value = 'parametros';
	f.submit();
}

function submitPesquisar() {
	 
	f = document.lancamento;
	f.submenu.value = 'consulta';
	f.letra.value = f.mesBase.value + "|" + f.anoBase.value + "|" + f.filial.value;
	f.submit();
}

function submitAlterar(mes, ano, filial, genero) {

	if (confirm('Deseja realmente Alterar este item') == true) {
		f = document.lancamento;
		f.submenu.value = 'alterar';
		f.mes.value = mes;
		f.ano.value = ano;
		f.filial.value = filial;
		f.genero.value = genero;
		f.submit();
	}
}

function submitExcluir(mes, ano, filial, genero) {
	if (confirm('Deseja realmente Excluir este item') == true) {
		f = document.lancamento;
		f.submenu.value = 'exclui';
		f.filial.value = filial;
		f.mes.value = mes;
		f.ano.value = ano;
		f.genero.value = genero;
		f.submit();
	}
}
