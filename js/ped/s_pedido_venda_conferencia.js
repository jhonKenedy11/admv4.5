document.addEventListener('keydown', function (event) {
    if (event.keyCode !== 13) return;
        submitConferir();
    });

function submitVoltarConferencia() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = f.origem.value;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

// mostra form conferencia
function submitCadastroConferencia(id, pedido, origem) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_conferencia_cadastro';
    f.letra.value = id + "|" + pedido;
    f.origem.value = origem;
    f.submenu.value = '';
    f.submit();
    
} // fim submitVoltar

function submitConferir() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_conferencia_cadastro';
    f.letra.value = f.id.value + "|" + f.pedido.value;
    f.submenu.value = 'conferir';
    f.submit();
    
} // fim submitVoltar