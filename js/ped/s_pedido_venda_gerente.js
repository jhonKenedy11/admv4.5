function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitImprime(id, pag) {
//    ALERT(pag);
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.submenu.value = 'imprime';
    f.id.value = id;

    print = window.open(pag, 'imprime', 'toolbar=no,location=no, menubar=no,width=1200,height=650,scrollbars=yes');
    //print.window.print();
    f.submit();

    
} // submitImprime


function submitCadastro(id) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.opcao.value = 'pedido_venda_gerente';
    f.id.value = id;
    f.submenu.value = 'cadastrar';
    f.submit();
} // fim submit

function submitMesAtual() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.submenu.value = 'MesAtual';
    f.submit();
} // fim submit


function submitAgruparPedidos(){
    debugger
    f = document.lancamento;
    f.pedidoAgrupado.value = '';
    f.dadosPed.value = '';
    var table = document.getElementById("datatable-buttons-1");
    var r = table.rows.length;

    var pedidos = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        
        if (row.pedidoChecked.checked == true) {
            pedidos = pedidos + "|" + row[0].id;
            
        }
    }
    var frete = f.mFrete.value;
    var despAcessorias = f.mDespAcessorias.value;

    frete = parseFloat(frete.replace(".","").replace(",","."));
    despAcessorias = parseFloat(despAcessorias.replace(".","").replace(",","."));

    f.mFrete.value = frete;
    f.mDespAcessorias.value  = despAcessorias;
    f.pedidoAgrupado.value = pedidos;
    f.dadosPed.value = f.pessoa.value + "|" + f.mSituacao.value + "|" + f.mFrete.value + "|" + f.mDespAcessorias.value + "|" + f.mDesconto.value + "|" + f.mTotal.value + "|" + f.condPgto.value
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.submenu.value = 'agrupaPedido';
    f.submit()
}

function agrupaPedidoModal(){
    debugger
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons-1");
    var r = table.rows.length;
    var pessoa = '';
    var condPg = '';
    totalFrete          = 0;
    totalDespAcessorias = 0;
    totalDesconto       = 0; 
    totalPedido         = 0;

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        
        if (row.pedidoChecked.checked == true) {
            var cells = table.rows[i].getElementsByTagName("td");

            novaPessoa = cells[1].childNodes[0].data;    
            idPessoa   = cells[8].childNodes[0].data; 
            idCondPgto = cells[9].childNodes[0].data;    

            if (pessoa === ''){
                pessoa = novaPessoa;
                f.pessoa.value = idPessoa;
            }
            if(condPg === ''){
               condPg = idCondPgto.trim();
            }
            if(novaPessoa === pessoa){
                

                total          = cells[4].childNodes[0].data;
                frete          = cells[5].childNodes[0].data;
                despAcessorias = cells[6].childNodes[0].data;
                desconto       = cells[7].childNodes[0].data;

                total          = parseFloat(total.replace(".","").replace(",","."));
                frete          = parseFloat(frete.replace(".","").replace(",","."));
                despAcessorias = parseFloat(despAcessorias.replace(".","").replace(",","."));
                desconto       = parseFloat(desconto.replace(".","").replace(",","."));

                totalPedido         += total;
                totalFrete          += frete;
                totalDespAcessorias += despAcessorias;
                totalDesconto       += desconto;

            }else{
                alert("Selecione a mesma Pessoa para fazer o Agrupamento de Pedido.");
                return false;
            }
        }
    }
    
    f.mPessoa.value         = pessoa
    f.mFrete.value          = currencyFormat(totalFrete);
    f.mDespAcessorias.value = currencyFormat(totalDespAcessorias);
    f.mDesconto.value       = currencyFormat(totalDesconto);
    f.mTotal.value          = currencyFormat(totalPedido);
    f.condPgto.value        = condPg
    $('#modalAgrupamentoPed').modal('show');
}


function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function abrir(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function formatDate(d) {
    // yyyy-mm-dd
    return d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
}

function submitFiltroDia() {
    var f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    var hoje = new Date();
    var data = formatDate(hoje);
    f.letra.value = data + '|' + data + '||||||';
    f.submenu.value = '';
    f.submit();
}

function submitFiltroSemana() {
    var f = document.lancamento;
    var hoje = new Date();
    var primeiro = new Date(hoje);
    primeiro.setDate(hoje.getDate() - hoje.getDay()); // domingo
    var ultimo = new Date(hoje);
    ultimo.setDate(hoje.getDate() + (6 - hoje.getDay())); // sábado
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.letra.value = formatDate(primeiro) + '|' + formatDate(ultimo) + '||||||';
    f.submenu.value = '';
    f.submit();
}

function submitFiltroMes() {
    var f = document.lancamento;
    var hoje = new Date();
    var primeiro = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
    var ultimo = new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0);
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente';
    f.letra.value = formatDate(primeiro) + '|' + formatDate(ultimo) + '||||||';
    f.submenu.value = '';
    f.submit();
}
