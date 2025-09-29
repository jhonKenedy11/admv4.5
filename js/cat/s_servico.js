function initializeSwitchery() {
    if (typeof Switchery !== 'undefined') {
        // Remove todos os switchery existentes primeiro
        document.querySelectorAll('.switchery').forEach(el => el.remove());

        var switches = document.querySelectorAll('.js-switch');
        switches.forEach(function (statusElem) {
            // Reset do elemento
            statusElem.removeAttribute('data-switchery');
            statusElem.style.display = '';

            // Inicializa novo Switchery
            var switchery = new Switchery(statusElem, { color: '#26B99A' });

            statusElem.addEventListener('change', function () {
                var label = statusElem.closest('label');
                if (label) {
                    var span = label.querySelector('.status-label-text');
                    if (span) {
                        span.textContent = statusElem.checked ? 'Ativo' : 'Inativo';
                    }
                }
            });
        });
    }
}

// Chama a função quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', initializeSwitchery);
// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'servico';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'servico';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        if (f.submenu.value == "cadastrar") {
           f.submenu.value = 'inclui'; }
        else {
           f.submenu.value = 'altera'; }

        f.submit(); // já estava
    } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'servico';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(servico) {

    if (confirm('Deseja realmente Alterar este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'servico';
        f.submenu.value = 'alterar';
        f.id.value = servico;
        f.submit();
    }
} // submitAlterar

function submitExcluir(servico) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'cat';
        f.form.value = 'servico';
        f.submenu.value = 'exclui';
        f.id.value = servico;
        f.submit();
    }
} // submitExcluir

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaServicoPesquisaAtendimento(codigo) {
    debugger;
    f = window.opener.document.lancamento;
    var quantId = "quant"+codigo;
    quantValue = document.getElementsByName(quantId)[0].value;
    var unitarioId = "unitario"+codigo;
    unitarioValue = document.getElementsByName(unitarioId)[0].value;
    if(quantValue == "0,00" || quantValue == ""){
        alert("Digite a quantidade do produto.");
        return false;
    }
    if(unitarioValue == "0,00" || unitarioValue == ""){
        alert("Digite o valor Unitário do produto.");
        return false;
    }
    f.idServicos.value = codigo;
    f.quantidadeServico.value = quantValue;
    f.vlrUnitarioServico.value = unitarioValue;
    f.mod.value = 'cat';
    f.form.value = 'atendimento'
    f.submenu.value = 'cadastrarServico';
    f.submit();   
    
    window.close();
}