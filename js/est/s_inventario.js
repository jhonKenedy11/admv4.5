function abrir(pag, form='') {
    if(form == 'produto'){
        screenWidth = screen.width;
        screenHeight = screen.height;
    }else{
        screenWidth = 750;
        screenHeight = 650;
    }
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}



function submitConfirmar(){
    f = document.lancamento;
    if(f.referencia.value == '' ){
        alert('O campo Referencia é obrigatório!');
        return false;
    } 
    if(confirm("Deseja realmente cadastrar este Inventario?")){
        f.mod.value = 'est';
        f.form.value = 'inventario';
        f.submenu.value = 'inclui';
        f.submit();
    }else{
        return false;
    }
    
}

function submitConfirmarProdutoInventario(){
    f = document.lancamento;
    
    if(confirm("Deseja realmente cadastrar este item(s) ao Inventario?")){
        f.mod.value = 'est';
        f.form.value = 'inventario';
        f.submenu.value = 'incluiProdInventario';
        var produto = ''
        var prodInventario = ''
        var precoCustoBdFormat = ''
        var rows = document
        .getElementById("datatable-buttons")
        .getElementsByTagName("tr");
    
        for (row = 1; row < rows.length; row++) {
            var cells = rows[row].getElementsByTagName("td");
            var field0 = cells[0].childNodes[0].data; // ProdutoId
            var field3 = cells[3].childNodes[0].data; // PrecoCusto

            precoCustoBdFormat = parseFloat(field3.replace(".","").replace(",","."));

            produto =  field0 + "*" +  precoCustoBdFormat
            prodInventario =  produto + "|" +  prodInventario 
        }

        f.dadosInventario.value = prodInventario;
        
        f.submit();
    }else{
        return false;
    }
    
}

function submitAlteraProdutoInventario(){
    f = document.lancamento;
    
    if(confirm("Deseja realmente Alterar este item(s) do Inventario?")){
        
        f.mod.value = 'est';
        f.form.value = 'inventario';
        f.submenu.value = 'alteraProdInventario';
        var table = document.getElementById("datatable-buttons");
        var r = table.rows.length;
        var produto = ''
        var prodInventario = ''
        var qtdeBdFormat = ''
        var precoCustoBdFormat = ''
        f.dadosInventario.value = '';

        for (i = 1; i < r; i++) {
            var idPrecoCusto = '';
            var idQuantNova =  '';
            var row = table.rows.item(i).getElementsByTagName("input");   

            if (row.length > 0){
                if (row[0].checked == true) {
                    var idProdutoInventario = row[0].id;
                    idPrecoCusto = 'precoCustoNovo'+row[0].id;
                    idQuantNova = 'quantNova'+row[0].id
                    var field3 = document.getElementById(idPrecoCusto).value.trim(); // Quantidade
                    var field4 = document.getElementById(idQuantNova).value.trim(); // PrecoCusto

                    //if(field4 == '' || field4 == '0,00'){
                    //    alert("Preecher o campo de quantidade do produto selecionado.");
                    //    return false;
                    //}
                    
                    // if(field3 == '' || field3 == '0,00'){
                    //     alert("Preecher o campo de preço custo do produto selecionado.");
                    //     return false;
                    // }

                    qtdeBdFormat = parseFloat(field3.replace(".","").replace(",","."));
                    precoCustoBdFormat = parseFloat(field4.replace(".","").replace(",","."));

                    produto =  idProdutoInventario + "*" +  qtdeBdFormat  + "*" + precoCustoBdFormat
                    prodInventario =  produto + "|" +  prodInventario 
                }
            }
        }
        f.dadosInventario.value = prodInventario;
        f.gerarInventario.value = 'true'
        f.submit();
    }else{
        return false;
    }
    
}

function submitAlterar(id){
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'alterar';
    f.tela.value = 'inventarioProduto';
    f.id.value = id
    f.submit();
    
}

function submitExcluir(id){
    f = document.lancamento;
    if(confirm("Deseja realmente Excluir este item do Inventario?")){
        f.mod.value = 'est';
        f.form.value = 'inventario';
        f.submenu.value = 'excluiProdInventario';
        f.idInventarioProduto.value = id
        f.submit();
    }
    
}

function submitCadastraInventarioProd(id){
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'cadastrarProdutoInventario';
    f.id.value = id
    f.submit();
    
}

function submitLetra(){
    f = document.lancamento;

    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = '';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.status.value;
    f.submit();
}

function submitLetraCadastroInventario(){
    
    f = document.lancamento;

    f.mod.value = 'est';
    f.form.value = 'inventario';

    if (f.codProduto.value.trim() === '' && 
        f.localizacao.value.trim() === '' && 
        (!f.grupo || f.grupo.value.trim() === '')) { 
        alert("Adicione um filtro para a pesquisa.");
        return false;
    }
    Swal.fire({
        title: 'Carregando...',
        text: 'Aguarde enquanto os dados são processados.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    f.pesq.value = f.codProduto.value + "|" + f.localizacao.value;
    f.grupoSelected.value = concatCombo(grupo);
    f.submit();
}


function submitVoltar(submenu){
    f = document.lancamento;

    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = submenu;
    f.submit();
    
    
}

function limpaDadosForm() {
    f = document.lancamento;
    f.grupo.value = '';
    f.localizacao = '';
    f.pessoa.value = '';
    f.fornecedor.value = '';
    f.codProduto.value = '';
    f.unidade.value = '';
    f.descProduto.value = '';
    f.valorVenda.value = '';
    f.uniFracionada.value = '';
    f.pesq.value = '';
    f.pesProduto.value = '';
    f.quantAtual.value = '';
    f.qtdeEntrada.value = '';
    f.nome.value = '';
    f.descgenero.value = '';
    f.obs.value = '';
    f.submenu.value = '';
    f.submit();
}

// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	
        }
    }
    return valor;
}

function checkProduto(id){
    var quantNova = document.getElementById('quantNova'+id);
    if (quantNova) {
        // Remove pontos de milhar e troca vírgula por ponto
        if (quantNova.value < 0) {
            alert('Não é permitido valor negativo para quantidade.');
            quantNova.value = '';
            quantNova.focus();
            return false;
        }
    }
    if(document.getElementById(id).checked != true)
        document.getElementById(id).checked = true;
}

// mostra Cadastro Img
function submitCadastrarImagem(id, descricao) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'inventario';
    f.submenu.value = 'cadastrarImagem';
    f.idInventarioProduto.value = id;
    f.tituloImg.value = descricao;
    f.submit();

} // submitCadastrarImagem

function relatorioInventario(id) {
    window.open('index.php?mod=est&form=rel_inventario&opcao=imprimir&id=' + id + '&consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function pesquisarItensInventarioModal() {

    $('#modalPesquisarItens').on('show.bs.modal', function () {
        limparCamposPesquisaItensInventarioModal();
        $('#tbodyItensPesquisaModal').empty();
    });
    var idInventario = document.lancamento.id.value;
    var f = document.formPesquisaItensInventario;
    var codigo = f.pesqCodigo ? f.pesqCodigo.value : '';
    var nome = f.pesqNome ? f.pesqNome.value : '';
    var grupo = f.pesqGrupo ? f.pesqGrupo.value : '';
    var localizacao = f.pesqLocalizacao ? f.pesqLocalizacao.value : '';
    var foraLinha = f.pesqForaLinha && f.pesqForaLinha.value === "1" ? 1 : null;

    var filtros = {
        idInventario: idInventario,
        codigo: codigo,
        nome: nome,
        grupo: grupo,
        localizacao: localizacao,
        foraLinha: foraLinha
    };

    $.ajax({
        url: "index.php?mod=est&form=inventario&submenu=pesquisaItensInventarioModal&opcao=blank",
        type: 'POST',
        data: { filtros: filtros },
        dataType: 'json',
        beforeSend: function() {
            // Pode adicionar loading aqui
        },
        success: function(response) {
            $('#tbodyItensPesquisaModal').html(response.html);
        },
        error: function(xhr, status, error) {
            // Tratar erro depois
        }
    });
}

function limparCamposPesquisaItensInventarioModal() {
    var f = document.formPesquisaItensInventario;
    if (!f) return;
    if (f.pesqCodigo) f.pesqCodigo.value = '';
    if (f.pesqNome) f.pesqNome.value = '';
    if (f.pesqGrupo) f.pesqGrupo.value = '';
    if (f.pesqLocalizacao) f.pesqLocalizacao.value = '';
    if (f.pesqForaLinha) f.pesqForaLinha.value = '';
}


function adicionarItensSelecionadosInventario() {
    var selecionados = [];
    var tbody = document.getElementById('tbodyItensPesquisaModal');
    var checkboxes = tbody.querySelectorAll('input[type="checkbox"][name="itensSelecionados[]"]:checked');
    for (var i = 0; i < checkboxes.length; i++) {
        selecionados.push(checkboxes[i].value);
    }

    if (selecionados.length === 0) {
        alert('Selecione ao menos um item!');
        return;
    }

    var idInventario = document.lancamento.id.value;

    $.ajax({
        url: "index.php?mod=est&form=inventario&opcao=blank",
        type: 'POST',
        data: {
            submenu: 'incluiItensInventarioModal',
            id: idInventario,
            itens: selecionados
        },
        dataType: 'json',
        success: function(resp) {
            if (resp && resp.redirect) {
                $('#modalPesquisarItens').modal('hide');
                window.location.href = resp.redirect;
            } else {
                location.reload();
            }
        }
    });
}

function excluirInventario(id) {
    if (confirm("Deseja realmente excluir este inventário? Esta ação não poderá ser desfeita!")) {
        window.location.href = 'index.php?mod=est&form=inventario&submenu=excluirInventario&id=' + encodeURIComponent(id);
    }
}

function ajaxGerarInventario() {
    var f = document.lancamento;
    if(!confirm("Deseja realmente gerar Inventario?")) return false;
    var inventarioNfSaida = '', produtoSaida= '', totalNfSai = 0;
    var inventarioNfEnt = '', produtoEnt = '', totalNfEnt = 0;
    var qtdeBdFormat = '', precoCustoBdFormat = '', estoqueBdFormat = '', tipoNf = '';
    var table = document.getElementById("datatable-buttons");
    var rows = table.getElementsByTagName("tr");
    for (var i = 1; i < rows.length; i++) {
        var idPrecoCusto = '', idQuantNova = '', qtdeProdutoNf = '', totalNfProduto = '';
        var row = table.rows.item(i).getElementsByTagName("input");   
        idPrecoCusto = 'precoCustoNovo'+row[0].id;
        idQuantNova = 'quantNova'+row[0].id;
        var cells = rows[i].getElementsByTagName("td");
        var field7 = cells[1].childNodes[0].data.trim(); // Cod Produto
        var field1 = cells[2].childNodes[0].data.trim(); // Desc Produto
        var field8 = cells[9].childNodes[0].data.trim(); // Unidade Produto
        var field9 = cells[10].childNodes[0].data.trim(); // Unidade Fracionada
        var field4 = document.getElementById(idQuantNova).value.trim(); // Quantidade
        var field6 = document.getElementById(idPrecoCusto).value.trim(); // PrecoCusto
        var field3 = cells[4].childNodes[0].data; // Estoque
        qtdeBdFormat = parseFloat(field4.replace(".","").replace(",","."));
        precoCustoBdFormat = parseFloat(field6.replace(".","").replace(",","."));
        estoqueBdFormat = parseFloat(field3.replace(".","").replace(",","."));
        
        if(estoqueBdFormat > qtdeBdFormat ){
            tipoNf = 1;
            qtdeProdutoNf = estoqueBdFormat - qtdeBdFormat 
            totalNfProduto = qtdeProdutoNf * precoCustoBdFormat;
            produtoSaida != '' ? produtoSaida += "|" + row[0].id + "*"  + field7 + "*" + field1 + "*" + field8 + "*" + field9 + "*" + currencyFormat(qtdeProdutoNf)  + "*" + currencyFormat(precoCustoBdFormat)  + "*" + currencyFormat(totalNfProduto)  + "*" + tipoNf : 
            produtoSaida = row[0].id + "*" + field7 + "*" + field1 + "*" + field8 + "*" + field9 + "*" + currencyFormat(qtdeProdutoNf)  + "*" + currencyFormat(precoCustoBdFormat)  + "*" + currencyFormat(totalNfProduto)  + "*" + tipoNf;
            totalNfSai += totalNfProduto;
        }else{
            tipoNf = 0;
            qtdeProdutoNf = qtdeBdFormat - estoqueBdFormat
            totalNfProduto = qtdeProdutoNf * precoCustoBdFormat;
            produtoEnt += (produtoEnt != '' ? "|" : "") + row[0].id + "*" + field7 + "*" + field1 + "*" + field8 + "*" + field9 + "*" + currencyFormat(qtdeProdutoNf) + "*" + currencyFormat(precoCustoBdFormat) + "*" + currencyFormat(totalNfProduto) + "*" + tipoNf;
            totalNfEnt += totalNfProduto;
        }
    }
    if(produtoSaida != ''){
        inventarioNfSaida = currencyFormat(totalNfSai) + "|" + produtoSaida
    }
    if(produtoEnt != ''){
        inventarioNfEnt = currencyFormat(totalNfEnt) + "|" + produtoEnt
    }
    var postData = {
        mod: 'est',
        form: 'inventario',
        submenu: 'gerarInventarioAjax',
        opcao: 'blank',
        id: f.id.value,
        dadosInventarioSaida: inventarioNfSaida,
        dadosInventarioEnt: inventarioNfEnt
    };
    $.ajax({
        url: 'index.php?',
        type: 'POST',
        data: postData,
        dataType: 'html',
        success: function(html) {
            window.location.reload();
        },
        error: function(xhr, status, error) {
            alert('Erro na comunicação com o servidor.');
        }
    });
}
