/**
 * JavaScript para administração dos parâmetros de estoque
 * Arquivo: js/est/s_parametro.js
 * Padrão ADM v4.5
 */

function validarFormularioParametro() {
    var filial = document.getElementById('filial');
    var modelo = document.getElementById('modelo');
    var mensagens = [];

    if (filial && !filial.disabled && !filial.value) {
        mensagens.push('Selecione a empresa (centro de custo).');
        filial.classList.add('is-invalid');
    } else if (filial) {
        filial.classList.remove('is-invalid');
    }

    if (modelo && !modelo.disabled && !modelo.value) {
        mensagens.push('Selecione o modelo do documento fiscal.');
        modelo.classList.add('is-invalid');
    } else if (modelo) {
        modelo.classList.remove('is-invalid');
    }

    if (mensagens.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos obrigatórios',
            html: mensagens.join('<br>'),
            confirmButtonText: 'OK'
        });
        return false;
    }

    return true;
}

function sincronizarCentroCustoEmpresa() {
    var filial = document.getElementById('filial');
    var centrocusto = document.getElementById('centrocusto');

    if (!filial || !centrocusto || filial.disabled) {
        return;
    }

    if (!filial.value) {
        return;
    }

    if (!centrocusto.value) {
        centrocusto.value = filial.value;
    }
}

function submitConfirmar() {
    var f = document.parametro;

    if (!validarFormularioParametro()) {
        return;
    }

    sincronizarCentroCustoEmpresa();

    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente salvar este parâmetro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            if (f.submenu.value === 'cadastro') {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
}

function submitCadastro() {
    document.parametro.submenu.value = 'cadastro';
    document.parametro.submit();
}

function submitAlterar(filial, modelo) {
    document.parametro.submenu.value = 'alterar';
    if (filial) {
        document.parametro.filial.value = filial;
    }
    if (modelo) {
        document.parametro.modelo.value = modelo;
    }
    document.parametro.submit();
}

function submitExcluir(filial, modelo) {
    Swal.fire({
        icon: 'question',
        title: 'Confirmar Exclusão',
        text: 'Deseja realmente excluir este parâmetro?',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then(function (result) {
        if (result.isConfirmed) {
            document.parametro.submenu.value = 'excluir';
            if (filial) {
                document.parametro.filial.value = filial;
            }
            if (modelo) {
                document.parametro.modelo.value = modelo;
            }
            document.parametro.submit();
        }
    });
}

function submitConsulta() {
    document.parametro.submenu.value = 'consulta';
    document.parametro.submit();
}

function submitLimparFiltro() {
    var filtro = document.getElementById('filtro_empresa');
    if (filtro) {
        filtro.value = '';
    }
    document.parametro.submenu.value = 'consulta';
    document.parametro.submit();
}

function limparFormulario() {
    Swal.fire({
        icon: 'question',
        title: 'Limpar Formulário',
        text: 'Deseja realmente limpar todos os campos?',
        showCancelButton: true,
        confirmButtonText: 'Sim, limpar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            $('#formParametros')[0].reset();
            $('.is-invalid').removeClass('is-invalid');

            $('#modelo').val('55');
            $('#tipovalidacao').val('N');
            $('#precobase').val('C');
            $('#clientepadrao').val('1');

            marcarRadio('consultaestoquezero', 'S');
            marcarRadio('controlaestoque', 'S');
            marcarRadio('integrafin', 'S');
            marcarRadio('validanfauto', 'S');
            marcarRadio('xmlconferirestoque', 'N');
            marcarRadio('xmlmanterorigemcst', 'S');
            marcarRadio('calcula_ipi_custo_reposicao', 'N');
            marcarRadio('calcula_st_custo_reposicao', 'N');

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

function marcarRadio(nome, valor) {
    var radios = document.getElementsByName(nome);
    for (var i = 0; i < radios.length; i++) {
        radios[i].checked = (radios[i].value === valor);
    }
}

function voltarListagem() {
    window.location = '?mod=est&form=parametro';
}
