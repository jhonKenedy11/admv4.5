function submitConfirmar() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
} // submitConfirmar

function submitVoltar(id_tabela_preco) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = '';
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
} // fim submitVoltar

function voltarTabelaPreco() {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro(id_tabela_preco) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
} // submitCadastro

function submitAlterar(id, id_tabela_preco, codigo) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.id_tabela_preco.value = id_tabela_preco;
    f.codigo.value = codigo;
    f.submit();
} // submitAlterar

function submitExcluir(id, codigo) {
    Swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            var f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = 'tabela_preco_item';
            f.submenu.value = 'exclui';
            f.id.value = id;
            f.codigo.value = codigo;
            f.submit();
        }
    });
} // submitExcluir

function calcularPrecoFinal() {
    var pbEl = document.getElementById('precobase');
    var mgEl = document.getElementById('margem');
    var pfEl = document.getElementById('precofinal');
    if (!pbEl || !mgEl || !pfEl) return;
    var parse = function (v) { return Number(String(v || '').replace(/\./g, '').replace(',', '.')) || 0; };
    var pb = parse(pbEl.value);
    var mg = parse(mgEl.value);
    var pf = pb * (1 + mg / 100);
    pfEl.value = pf.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

document.addEventListener('DOMContentLoaded', function () {
    ['precobase', 'margem'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        // input pode ser alterado pelo maskMoney; delay curto garante valor final formatado
        el.addEventListener('input', function () { setTimeout(calcularPrecoFinal, 50); });
        el.addEventListener('keyup', function () { setTimeout(calcularPrecoFinal, 50); });
        el.addEventListener('blur', calcularPrecoFinal);
    });
    calcularPrecoFinal();
});

var produtoPopupIndex = null;

function abrirPesquisaProdutoPopup(index) {
    produtoPopupIndex = index;
    var url = document.URL.split('?')[0] + '?mod=est&form=produto&opcao=pesquisarpecas';
    window.open(url, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=550,scrollbars=yes');
}

window.abrirCadastrarProdutoPopup = function (index) {
    produtoPopupIndex = index;
    var rows = document.querySelectorAll('form[name="lancamento"] table tbody tr');
    var row = rows[index];
    var codFabricante = (row.cells[3] && row.cells[3].innerText) ? row.cells[3].innerText.trim() : '';
    var codigoInput = row.querySelector('input[name^="codigo_override"]');
    var codProdutoNota = codigoInput ? codigoInput.value.trim() : '';
    var descricao = (row.cells[4] && row.cells[4].innerText) ? row.cells[4].innerText.trim() : '';
    var unidade = 'PC'; // não temos unidade na importação
    var vlrUnitario = (row.cells[6] && row.cells[6].innerText) ? row.cells[6].innerText.trim() : '';

    var letra = 'registerProd|' + codFabricante + '|' + codProdutoNota + '|' + descricao + '|' + unidade + '|' + vlrUnitario;
    var url = document.URL.split('?')[0] + '?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&letra=' + encodeURIComponent(letra) + '&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes';
    window.open(url, 'cadastrar_produto', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

// quando voltar do popup, detecta e aplica código no campo correto
window.addEventListener('focus', function () {
    if (produtoPopupIndex === null) return;
    try {
        var f = document.lancamento;
        if (!f) return;
        var cod = f.codProduto && f.codProduto.value ? f.codProduto.value : '';
        if (cod !== '') {
            var inputName = 'codigo_override[' + produtoPopupIndex + ']';
            var el = document.getElementsByName(inputName)[0];
            if (el) el.value = cod;
            f.codProduto.value = '';
            produtoPopupIndex = null;
        }
    } catch (e) {
        produtoPopupIndex = null;
    }
});


function Importar(id_tabela_preco) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'importar';
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
}


function submitImportar(id_tabela_preco) {
    var name = document.lancamento.arquivo_excel.value.split('\\').pop().split('/').pop();
    var ext = name.split('.').pop().toLowerCase();
    if (ext !== 'xls') {
        Swal.fire({
            title: 'Formato inválido',
            text: 'Use o formato .xls.',
            icon: 'error'
        });
        return false;
    }
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'processar_import';
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
}



function submitConfirmarImport(id_tabela_preco) {
    var f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'confirmar_import';   
    f.id_tabela_preco.value = id_tabela_preco;
    f.submit();
}