// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    var result = false;
    //testa se tem radio marcado lancPedBaixado
    var lancPedBaixado = document.getElementsByName('lancPedBaixado');
    for (let i = 0; i < lancPedBaixado.length; i++) {
        if (lancPedBaixado[i].checked === true) {
            result = true;
        }
    }
    if (!result) {
        document.getElementsByName('lancPedBaixadoB')[0].style.borderRadius = '5px';
        document.getElementsByName('lancPedBaixadoB')[0].style.border = 'solid 2.5px #f500009e';
        setTimeout(function () {
            document.getElementsByName('lancPedBaixadoB')[0].style.border = "none";
        }, 5000);
        swal({ text: 'Marque SIM ou NÃO no campo "Lançamento Pedido Baixado".', title: 'Atenção!', dangerMode: true });
        return false;
    }//FIM lancPedBaixado

    //testa se tem radio marcado lancPedBaixado
    result = false;
    var fluxoPedido = document.getElementsByName('fluxoPedido');
    for (let i = 0; i < fluxoPedido.length; i++) {
        if (fluxoPedido[i].checked === true) {
            result = true;
        }
    }
    if (!result) {
        document.getElementsByName('fluxoPedidoB')[0].style.borderRadius = '5px';
        document.getElementsByName('fluxoPedidoB')[0].style.border = 'solid 2.5px #f500009e';
        setTimeout(function () {
            document.getElementsByName('fluxoPedidoB')[0].style.border = "none";
        }, 5000);
        swal({ text: 'Marque SIM ou NÃO no campo "Fluxo Pedido".', title: 'Atenção!', dangerMode: true });
        return false;
    }//FIM fluxoPedido

    //testa se tem radio marcado aprovacao
    result = false;
    var aprovacao = document.getElementsByName('aprovacao');
    for (let i = 0; i < aprovacao.length; i++) {
        if (aprovacao[i].checked === true) {
            result = true;
        }
    }
    if (!result) {
        document.getElementsByName('aprovacaoB')[0].style.borderRadius = '5px';
        document.getElementsByName('aprovacaoB')[0].style.border = 'solid 2.5px #f500009e';
        setTimeout(function () {
            document.getElementsByName('aprovacaoB')[0].style.border = "none";
        }, 5000);
        swal({ text: 'Marque SIM ou NÃO no campo "Aprovação".', title: 'Atenção!', dangerMode: true });
        return false;
    }//FIM aprovacao

    //testa se tem radio marcado encomenda
    result = false;
    var encomenda = document.getElementsByName('encomenda');
    for (let i = 0; i < encomenda.length; i++) {
        if (encomenda[i].checked === true) {
            result = true;
        }
    }
    if (!result) {
        document.getElementsByName('encomendaB')[0].style.borderRadius = '5px';
        document.getElementsByName('encomendaB')[0].style.border = 'solid 2.5px #f500009e';
        setTimeout(function () {
            document.getElementsByName('encomendaB')[0].style.border = "none";
        }, 5000);
        swal({ text: 'Marque SIM ou NÃO no campo "Encomenda".', title: 'Atenção!', dangerMode: true });
        return false;
    }//FIM encomenda

    swal({
        title: "Atenção!",
        text: "Deseja prosseguir com o cadastro?",
        icon: "warning",
        buttons: ["Cancelar", 'Continuar'],
    })
    .then((yes) => {
        if (yes) {
            f = document.lancamento;
            f.mod.value = 'ped';
            f.form.value = 'parametro';
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        } else {
            return false;
        }
    });
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = 'cadastrar';
    f.filial.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(parametro) {
    debugger
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = 'alterar';
    f.filial.value = parametro;
    f.submit();
} // submitAlterar

function submitExcluir(parametro) {
    swal({
        title: "Atenção!",
        text: "Deseja realmente excluir esse parâmetro?",
        icon: "warning",
        buttons: ["Cancelar", 'Continuar'],
    })
    .then((yes) => {
        if (yes) {
            f = document.lancamento;
            f.mod.value = 'ped';
            f.form.value = 'parametro';
            f.submenu.value = 'exclui';
            f.filial.value = parametro;
            f.submit();
        } else {
            return false;
        }
    });
} // submitExcluir