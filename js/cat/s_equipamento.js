// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'equipamento';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    if(f.descricao.value == ''){
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Preencha o campo Descrição',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(f.origem.value == 'pesquisaEquipamento'){
        f.opcao.value = 'imprimir';
    } else {
        f.opcao.value = '';
    }
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
           else {
               f.submenu.value = 'altera'; }
               
               f.mod.value = 'cat';
               f.form.value = 'equipamento';
        f.submit(); // já estava
    } 
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    debugger
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'equipamento';
    f.submenu.value = 'cadastrar';
    if(f.origem.value == 'pesquisaEquipamento'){
        f.opcao.value = 'imprimir';
    } 
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(equipamento) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'equipamento';
        f.submenu.value = 'alterar';
        f.id.value = equipamento;
        f.submit();
    }
} // submitAlterar

function submitExcluir(equipamento) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'equipamento';
        f.submenu.value = 'exclui';
        f.id.value = equipamento;
        f.submit();
    }
} // submitExcluir

function fechaEquipamentoPesquisaAtendimento(codigo, desc) {
    debugger;
    f = window.opener.document.lancamento;
    f.catEquipamentoId.value = codigo;
    f.descEquipamento.value = desc;
    
    window.close();
}