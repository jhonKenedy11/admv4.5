function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'importa';
    // f.submenu.value = 'extratorepassemkt';
    if (f.arqImporta.value == 'fornecedor') {
        if (f.pessoa.value == "") {
            alert("Campo Fornecedor é Obrigatório!");
            return false;
        }
        if (f.precoVenda.value == "") {
            alert("O campo margem Preço Venda é Obrigatório!");
            return false;
        }

        f.letra.value = f.pessoa.value + "|" + f.precoVenda.value;

    }
    if (confirm('Deseja realmente Importar este arquivo') == true) {
        f.submit();
    } // if
} // fim submitConfirmar

function submitConfirmarSenha() {
    f = document.password;
    f.submenu.value = 'altera';
    f.opcao.value = 'password';
    if (confirm('Deseja realmente alterar sua SENHA?') == true) {
        f.submit();
    } // if
} // fim submitConfirmarSenha

function submitConfirmarTabelaPreco() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'importa_tabela_preco';
    if (f.arqImporta.value == 'fornecedor') {
        if (f.pessoa.value == "") {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Campo de Fornecedor é Obrigatório!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (f.precoVenda.value == "") {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'O campo Margem de Preço de Venda é Obrigatório!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (f.arq.value == "") {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Selecione a Planilha!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        f.letra.value = f.pessoa.value + "|" + f.precoVenda.value + "|" + f.codTabela.value + "|" + f.descTabela.value + "|" +
            f.precoTabela.value + "|" + f.ipiTabela.value + "|" + f.ncmTabela.value + "|" + f.marcaTabela.value;
    }

    Swal.fire({
        icon: 'question',
        title: 'Confirmação',
        text: 'Deseja realmente Importar este arquivo?',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submit();
        }
    });
} // fim submitConfirmar

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}