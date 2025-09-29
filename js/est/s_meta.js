function submitConfirmar() {
   
    const f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta';
    
    if (f.vendedor.value == null) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o vendedor.'
        });
        return;
    }

    if (f.ano.value == 0 ||f.ano.value == '') { 
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Digite o ano.'
        });
        return;
    }

	if (f.mes.value == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'Selecione o mes.'
        });
        return;
    }

    Swal.fire({
        title: 'Confirmação',
        text: `Deseja realmente ${f.submenu.value} este item?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = f.submenu.value === "cadastrar" ? 'inclui' : 'altera';
            f.submit();
        }
    });
} // submitConfirmar

function submitVoltar() {

    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {

    f = document.lancamento;
    // f.opcao.value = formulario;
    f.mod.value = 'est';
    f.form.value = 'meta';
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(meta_id) {
    
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'meta';
    f.submenu.value = 'alterar';
    f.id.value = meta_id;
    f.submit();
} // submitAlterar

function submitExcluir(meta_id) {
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir esta meta?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'meta';
            f.submenu.value = 'exclui';
            f.id.value = meta_id;
            f.submit();
        }
    });
} // submitExcluir