
//--------------------------------------------------------------------------------
// desenha Cadastro
//--------------------------------------------------------------------------------
function submitVoltar() {
	
	f = document.lancamento;
	f.submenu.value = '';
	f.submit();
} 
function submitConfirmar(event) {
    event.preventDefault(); 
    
    const f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'orcamento';

    // Validação do Mês
    if (!f.mes.value || f.mes.value == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o Mês.'
        });
        return;
    }

    // Validação do Ano
    if (!f.ano.value) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Informe o Ano.'
        });
        return;
    }
	if (!f.genero.value) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o Genero.'
        });
        return;
    }
	if (!f.filial.value) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o Conta.'
        });
        return;
    }

    if (!f.valor.value || parseFloat(f.valor.value) == 0.00) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Digite o Valor.'
        });
        return;
    }

   
    const acao = f.submenu.value === 'cadastrar' ? 'cadastrar' : 'alterar';
    
    Swal.fire({
        title: 'Confirmar ação?',
        text: `Deseja realmente ${acao} este orçamento?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => { 
        if (result.isConfirmed) {
            f.submenu.value = (f.submenu.value === "cadastrar") ? 'inclui' : 'altera';
            f.submit(); 
        }
    });
}
//--------------------------------------------------------------------------------
// mostra Cadastro
//--------------------------------------------------------------------------------

function submitCadastro() {
	
	f = document.lancamento;
	f.mod.value = 'fin';
	f.form.value = 'orcamento';
	f.submenu.value = 'cadastrar';
	f.letra.value = f.mesBase.value + "|" + f.anoBase.value + "|" + f.filial.value;
	f.submit();
}

function submitGeraOrcamento(event) {
    event.preventDefault(); 

    const f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'orcamento';

    if (!f.mesBase.value ||f.mesBase.value == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o mês Base.'
        });
        return;
    }

    if (!f.anoBase.value) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Digite o ano Base.'
        });
        return;
    }
	if (!f.filial.value) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o Conta.'
        });
        return;
    }

	if (!f.media.value || f.media.value == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione a média de meses.'
        });
        return;
    }

    Swal.fire({
        title: 'Confirmar Geração',
        text: `${f.filial.value} - Deseja realmente gerar previsão para ${f.mesBase.value}/${f.anoBase.value}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, Gerar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = 'gerar';
            f.letra.value = `${f.mesBase.value}|${f.anoBase.value}|${f.filial.value}|${f.media.value}`;
            f.submit();
        }
    });
}

function submitParOrcamento(formulario) {
	f = document.lancamento;
	f.mod.value = 'fin';
	f.form.value = 'orcamento';
	f.submenu.value = 'parametros';
	f.submit();
}

function submitPesquisar() {
	
	f = document.lancamento;
	f.mod.value = 'fin';
	f.form.value = 'orcamento';
	f.submenu.value = 'consulta';
	f.letra.value = f.mesBase.value + "|" + f.anoBase.value + "|" + f.filial.value;
	f.submit();
}

function submitAlterar(mes, ano, filial, genero) {
    swal.fire({
        title: 'Confirmar Alteração?',
        text: "Você tem certeza que deseja alterar este orçamento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, alterar!',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = 'orcamento';
            f.submenu.value = 'alterar';
            f.mes.value = mes;
            f.ano.value = ano;
            f.filial.value = filial;
            f.genero.value = genero;
            f.submit();
        }
    });
}

function submitExcluir(mes, ano, filial, genero) {
	
    swal.fire({
        title: 'Confirmar Exclusão?',
        text: "Você tem certeza que deseja excluir este orçamento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, Excluir!',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.lancamento;
		f.mod.value = 'fin';
		f.form.value = 'orcamento';
		f.submenu.value = 'exclui';
		f.filial.value = filial;
		f.mes.value = mes;
		f.ano.value = ano;
		f.genero.value = genero;
		f.letra.value = f.mes.value + "|" + f.ano.value + "|" + f.filial.value + "|" + f.genero.value;
		f.submit();
	}
});
}
