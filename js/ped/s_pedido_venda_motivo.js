function submitConfirmar() {
    const f = document.forms['lancamento'];
    const descricao = document.getElementById('descricao');

    // Validação do campo descrição
    if (!descricao.value.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Campo obrigatório',
            text: 'O campo Descrição deve ser preenchido!',
            confirmButtonColor: '#3085d6'
        });
        descricao.focus();
        return;
    }

    // Configura ação conforme o tipo de operação
    const acao = f.submenu.value === 'cadastrar' ? 'incluir' : 'alterar';
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_motivo';

    Swal.fire({
        title: `Confirmar ${acao}?`,
        text: `Deseja realmente ${acao} este item?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Sim, ${acao}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Atualiza o submenu para ação correta
            f.submenu.value = f.submenu.value === 'cadastrar' ? 'inclui' : 'altera';
            f.submit();
        }
    });
}

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_motivo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_motivo';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitAlterar(motivo) {
    debugger;
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_motivo';
    f.submenu.value = 'alterar';
    f.motivo.value = motivo;
    f.submit();
} // submitAlterar

function submitExcluir(motivo) {
    Swal.fire({
        title: 'Confirmar exclusão?',
        text: "Esta ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.forms['lancamento'];
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_motivo';
            f.submenu.value = 'exclui';
            f.motivo.value = motivo;
            f.submit();
           
        }
    });
}