/**
 *  Imagem Produto
 */


function submitExcluirImagem(id,table, path) {
    debugger;
    if (confirm('Deseja realmente Excluir esta Imagem?') == true) {
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
