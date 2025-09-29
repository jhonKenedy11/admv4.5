<script language=javascript>

function submitCliente(id) {
    f = document.lancamento;
    f.opcao.value = '';
    f.submenu.value = 'cliente';
    f.submit();
}// fim submit

function submitBuscar() {

    var prom;
    prom = 'N';
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.grupo.value == "") && (prom == 'N') ){
        alert('Faça algum filtro de pesquisa.');
    }else{
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + prom;
        //alert(f.pesq.value);
        f.submit();
    }
        
} // submitExcluir

function submitVoltar(id){
    
    f = document.lancamento;
    f.mod.value = 'pdv';
    f.form.value = 'cupom';
    f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = '';
    f.submit();
}

function submitEncerra(id){
    
    if (id != null && id !== undefined) {
        f = document.lancamento;
        f.opcao.value = '';
        f.id.value = id;
        f.submenu.value = 'encerra';
        f.submit();
    } else {
        alert('CUPOM sem itens cadastrados!!')
    }
}

function submitCadastraNf(id) {

    f = document.lancamento;
    f.mod.value = 'pdv';
    f.form.value = 'cupom';
    f.submenu.value = 'cadastraNf';
    f.id.value = id;
    f.submit();
} // submitAlterar
function submitCadastraNfRecibo(id) {

    f = document.lancamento;
    f.mod.value = 'pdv';
    f.form.value = 'cupom';
    f.submenu.value = 'cadastraNf';
    f.opcao.value = 'recibo';
    f.id.value = id;
    f.submit();
} // submitAlterar

function submitIncluirItem(id){
    f = document.lancamento;
    // situacao lancamento
    submitBuscar();
    f.itensPedido.value = id;
    f.submenu.value = 'cadastrarItem';
    f.submit();
    //alert('passou' + f.itensPedido.value);
}

function submitCancela(id){
    if (confirm('Deseja realmente Excluir o CUPOM e TODOS os itens') == true) {
        //submitBuscar();
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if
}

function submitExcluirItem(id, nrItem) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        //submitBuscar();
        f = document.lancamento;
        f.submenu.value = 'excluiItem';
        f.id.value = id;
        f.nrItem.value = nrItem;
        f.submit();
    } // if
} // submitExcluir

function validaCPF(strCPF) {

    f = document.lancamento;

    //var strCPF = "12345678909";
    if (TestaCPF(strCPF) == false){
        alert('CPF Inválido!!!');
        f.cpf.value = "";
        
    }
}  

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaTotal(){
    var f = document.lancamento;
    var valorPago= f.valorPago.value;
    var totalPedido=f.totalPedido.value;
    var taxa=f.taxa.value;
    var desconto=f.desconto.value;
    var totalCupom=0;
    var troco=0;
    var totalCupom =parseFloat(totalPedido.replace(".","").replace(",","."))+
              parseFloat(taxa.replace(".","").replace(",","."))-
              parseFloat(desconto.replace(".","").replace(",","."));
    var troco=parseFloat(valorPago.replace(".","").replace(",","."))-
              parseFloat(totalCupom);
    f.totalCupom.value = currencyFormat(totalCupom);
    f.troco.value = currencyFormat(troco);
    f.obs.value = "Total Recebido: R$ "+currencyFormat(parseInt(valorPago))+"  Troco: R$ "+f.troco.value;
}

function print(){
   var conteudo = document.getElementById('print');
   tela_impressao = window.open(conteudo);
   tela_impressao.document.write(conteudo);
   tela_impressao.window.print();
   tela_impressao.window.close();
}

function printTrigger() {
    var getMyFrame = document.getElementById('print');
    getMyFrame.focus();
    getMyFrame.window.print();
}

function abrir(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

</script>
