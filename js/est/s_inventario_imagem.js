/**
 *  Imagem Produto
 */

function submitDestaqueImagem(idimg, destaque) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'destaqueImagem';
    f.destaque.value = destaque;
    f.idimg.value = idimg;
    f.submit();
} // submitDestaqueImagem


function submitExcluirImagem(id, idimg) {
     
    if (confirm('Deseja realmente Excluir esta Imagem?') == true) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'inventario';
        f.submenu.value = 'excluiImagem';
        f.idInventarioProduto.value = id;
        f.idimg.value = idimg;
        f.submit();
    }
} // submitExcluirImagem

function submitVoltarImagem(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'alterar';
    f.id.value = id
    f.submit();
} // fim submitVoltarImagem

// salvar imagem
function submitSalvarImagem() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'salvarImagem';
    f.submit();
} // submitSalvarImagem    

