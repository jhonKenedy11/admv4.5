/****
* UTILITARIOS
**/ 
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
        alert('O campo Quantidade é obrigatório!');
        return false;
    }
    

    if(f.nome.value == '' ){
        alert('O campo Conta é obrigatório!');
        return false;
    } 
    if(f.descgenero.value == '' ){
        alert('O campo Genero é obrigatório!');
        return false;
    }
    if(confirm("Deseja realmente alterar o estoque deste Produto?")){
        f.mod.value = 'est';
        f.form.value = 'baixa_estoque_new';
        f.submenu.value = 'inclui';
        f.submit();
    }else{
        return false;
    }
    
}

function submitLetra(){
    f = document.lancamento;

    if(f.nome.value == '' ){
        alert('O campo Conta é obrigatório!');
        return false;
    } 
    f.mod.value = 'est';
    f.form.value = 'baixa_estoque_new';
    f.mostraRelMatConsumoConta.value = true;
    f.submenu.value = 'relatorioMatConsumoConta';
    f.submit();
}

function submitLetraConsulta(){
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'baixa_estoque_new';
    f.letra.value = f.numNf.value + "|" + f.serieNf.value + "|" + f.dataIni.value + "|" + f.dataFim.value + "|" + f.codProduto.value;
    f.submit();
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
    f.pesProduto.value = '';
    f.quantAtual.value = '';
    f.qtdeEntrada.value = '';
    f.nome.value = '';
    f.descgenero.value = '';
    f.obs.value = '';
    f.submit();
}


function limpaDadosFormConsulta() {
    f = document.lancamento;
    f.pessoa.value = '';
    f.fornecedor.value = '';
    f.codProduto.value = '';
    f.unidade.value = '';
    f.descProduto.value = '';
    f.valorVenda.value = '';
    f.uniFracionada.value = '';
    f.pesProduto.value = '';
    f.quantAtual.value = '';
    f.numNf.value = '';
    f.serieNf.value = '';
    f.letra.value = '';
    f.submit();
}

function alterarQuantModal(e, id){
    if(confirm("Deseja Alterar a quantidade deste produto?")){
        
        var linha = $(e).closest("tr");
        var produto      = linha.find("td:eq(0)").text().trim(); 
        var quantidade   = linha.find("td:eq(5)").text().trim(); 

        $("#mProduto").val(produto);
        $("#mQuantidade").val(quantidade);
        $("#id").val(id);

        $('#modalAlteraQuantEstoque').modal('show');
    }
}

function submitAtualizarQuantidade(id){
    debugger
    f = document.lancamento;
    if(f.qtdeEntrada.value == '' || f.qtdeEntrada.value == '0,00'){
        alert("Inserir Nova Quantidade. ");
        return false;
    }
    f.mod.value = 'est';
    f.form.value = 'baixa_estoque_new';
    f.submenu.value = 'altera'
    f.id.value = f.id.value;
    f.submit();
}


function submitCadastro(){
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'baixa_estoque_new';
    f.submenu.value = 'cadastrar'
    f.submit();
    
}

function submitVoltar(){
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'baixa_estoque_new';
    f.submenu.value = ''
    f.submit();
    
}

function submitExcluir(id){
    if(confirm("Deseja Excluir esse Movimento de estoque?")){
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = 'baixa_estoque_new';
        f.submenu.value = 'exclui'
        f.id.value = id
        f.submit();
    }
    
}