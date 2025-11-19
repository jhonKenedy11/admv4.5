/**
 * JavaScript para administração dos parâmetros de estoque
 * Arquivo: js/est/s_parametro.js
 * Padrão ADM v4.5
 */

/**
 * Submete formulário para cadastro
 */
function submitCadastro() {
    document.parametro.submenu.value = 'cadastro';
    document.parametro.submit();
}

/**
 * Submete formulário para alteração
 */
function submitAlterar(id) {
    document.parametro.submenu.value = 'alterar';
    document.parametro.id.value = id;
    document.parametro.submit();
}

/**
 * Submete formulário para salvar alterações
 */
function submitSavedChanges(id) {
    document.parametro.submenu.value = 'altera';
    document.parametro.id.value = id;
    document.parametro.submit();
}

/**
 * Submete formulário para exclusão
 */
function submitExcluir(id) {
    Swal.fire({
        icon: 'question',
        title: 'Confirmar Exclusão',
        text: 'Deseja realmente excluir este parâmetro?',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            document.parametro.submenu.value = 'excluir';
            document.parametro.id.value = id;
            document.parametro.submit();
        }
    });
}

// ========== FUNÇÕES PARA CADASTRO ==========

/**
 * Limpa todos os campos do formulário
 */
function limparFormulario() {
    Swal.fire({
        icon: 'question',
        title: 'Limpar Formulário',
        text: 'Deseja realmente limpar todos os campos?',
        showCancelButton: true,
        confirmButtonText: 'Sim, limpar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#formParametros')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            
            // Resetar valores padrão
            $('#modelo').val('55');
            $('#consultaestoquezero').val('S');
            $('#controlaestoque').val('S');
            $('#integrafin').val('S');
            $('#validanfauto').val('S');
            $('#tipovalidacao').val('N');
            $('#precobase').val('C');
            $('#clientepadrao').val('1');
            
            // Resetar máscaras
            $('#percdescmaximo, #percalculo, #inss, #pis, #cofins, #ir, #contribuicao_social').inputmask('setvalue', '0,0000');
            
            Swal.fire({
                icon: 'success',
                title: 'Formulário Limpo',
                text: 'Todos os campos foram limpos e valores padrão restaurados',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Volta para a listagem
 */
function voltarListagem() {
    window.location = '?mod=est&form=parametro';
}
