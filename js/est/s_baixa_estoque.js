/****
* UTILITARIOS
**/ 
function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaQuantidadeProduto() {
    debugger;
    f = document.lancamento;
    var quantEntrada = f.qtdeEntrada.value;
    var quantAtual = f.quantAtual.value;
    if(quantEntrada == '' || quantAtual == ''){
        f.novaQtdeEstoque.value = 0,00;
    }else{
        total = 0;
        quantAtual = parseFloat(quantAtual.replace(".", "").replace(",", "."))
        quantEntrada = parseFloat(quantEntrada.replace(".", "").replace(",", "."))
        total = parseFloat((quantAtual) + quantEntrada);

        f.novaQtdeEstoque.value = currencyFormat(total);
    }
    
}

function submitConfirmar(){
    f = document.lancamento;
    if(f.pesProduto.value == ''){
        alert('O campo Produto é obrigatório!');
        return false;
    }
    if(f.qtdeEntrada.value == '' || f.qtdeEntrada.value == '0' || f.qtdeEntrada.value == '0,00' ){
        alert('O campo Qtde Entrada é obrigatório!');
        return false;
    }
    if(f.centroCustoOrigem.value == ''){
        alert('O campo Centro de Custo Origem é obrigatório!');
        return false;
    }

    if(f.centroCustoDestino.value == ''){
        alert('O campo Centro de Custo Destino é obrigatório!');
        return false;
    }

    if(f.nome.value == '' ){
        alert('O campo Pessoa é obrigatório!');
        return false;
    } 
    if(f.descgenero.value == '' ){
        alert('O campo Genero é obrigatório!');
        return false;
    }
    if(confirm("Deseja realmente alterar o estoque deste Produto?")){
        f.mod.value = 'est';
        f.form.value = 'baixa_estoque';
        f.submenu.value = 'inclui';
        f.submit();
    }else{
        return false;
    }
    
}

function limpaDadosForm() {
    f = document.lancamento;
    f.pessoa.value = '';
    f.fornecedor.value = '';
    f.codProduto.value = '';
    f.unidade.value = '';
    f.descProduto.value = '';
    f.valorVenda.value = '';
    f.uniFracionada.value = '';
    f.pesq.value = '';
    f.genero.value = '';
    f.pesProduto.value = '';
    f.quantAtual.value = '';
    f.qtdeEntrada.value = '';
    f.novaQtdeEstoque.value = '';
    f.nome.value = '';
    f.descgenero.value = '';
    f.obs.value = '';
}
