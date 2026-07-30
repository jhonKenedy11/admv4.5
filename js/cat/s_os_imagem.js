/**
 *  Imagem Produto
 */


function submitExcluirImagem(id,table, path) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja realmente excluir esta imagem?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'atendimento_new';
        f.submenu.value = 'excluiImagem';
        f.opcao.value = 'imprimir';
        f.idImg.value = id;
        f.table.value = table;
        f.path.value = path;
        f.submit();
        }
    });
} // submitExcluirImagem


// salvar imagem
function submitSalvarImagem(idOs) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_new';
    f.submenu.value = 'salvarImagem';
    f.idOs.value = idOs;
    f.opcao.value = 'imprimir';
    f.submit();
} // submitSalvarImagem    
