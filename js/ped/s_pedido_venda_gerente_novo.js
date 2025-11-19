function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitImprime(id, pag) {
//    ALERT(pag);
     
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'imprime';
    f.id.value = id;

    print = window.open(pag, 'imprime', 'toolbar=no,location=no, menubar=no,width=1200,height=650,scrollbars=yes');
    //print.window.print();
    f.submit();

    
} // submitImprime


function submitCadastro(id) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.opcao.value = 'pedido_venda_gerente_novo';
    f.id.value = id;
    f.submenu.value = 'notafiscal';
    f.submit();
} // fim submit

function submitCadastroFinanceiro(id) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.opcao.value = 'pedido_venda_gerente_novo';
    f.id.value = id;
    f.submenu.value = 'financeiro';
    f.submit();
} // fim submit


function submitCadastroFinanceiroServico(id) {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.opcao.value = 'pedido_venda_gerente_novo';
    f.id.value = id;
    f.submenu.value = 'financeiroServico';
    f.submit();
} // fim submit

function submitMesAtual() {
    f = document.lancamento;
     f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'MesAtual';
    f.submit();
} // fim submit

function submitTodosPedidos() {
    f = document.lancamento;
     f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'todosPedidos';
    f.submit();
} // fim submit

function submitTodosPedidosDia() {
    f = document.lancamento;
     f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = '';
    f.submit();
} // fim submit

function submitTodosPedidosMes() {
    f = document.lancamento;
     f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'todosPedidosMes';
    f.submit();
} // fim submit

function submitAgruparPedidos(){
    f = document.lancamento;
    f.pedidoAgrupado.value = '';
    f.dadosPed.value = '';
    var table = document.getElementById("datatable-buttons");
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
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'agrupaPedido';
    f.submit()
}

function agrupaPedidoModal(){
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var pessoa = '';
    var condPg = '';
    var pedidosSelecionados = 0; // Contador de pedidos selecionados
    totalFrete          = 0;
    totalDespAcessorias = 0;
    totalDesconto       = 0; 
    totalPedido         = 0;

    var idPessoaSelecionada = ''; // Armazena o ID da pessoa
    
    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        
        if (row.pedidoChecked.checked == true) {
            pedidosSelecionados++; // Incrementa contador
            var cells = table.rows[i].getElementsByTagName("td");

            novaPessoa = cells[1].childNodes[0].data;    
            dados   = cells[5].childNodes[0].data; 
            arrDados = dados.split("|");

            idPessoa   = arrDados[3].trim(); 

            if (pessoa === ''){
                pessoa = novaPessoa;
                idPessoaSelecionada = idPessoa; // Salva o ID da pessoa
                f.pessoa.value = idPessoa;
            }
            if(condPg === ''){
               condPg = arrDados[4].trim();
            }
            if(novaPessoa === pessoa){
                

                total          = cells[4].childNodes[0].data;
                frete          = arrDados[0].trim();;
                despAcessorias = arrDados[1].trim();;
                desconto       = arrDados[2].trim();;

                total          = parseFloat(total.replace(".","").replace(",","."));
                frete          = parseFloat(frete.replace(".","").replace(",","."));
                despAcessorias = parseFloat(despAcessorias.replace(".","").replace(",","."));
                desconto       = parseFloat(desconto.replace(".","").replace(",","."));

                totalPedido         += total;
                totalFrete          += frete;
                totalDespAcessorias += despAcessorias;
                totalDesconto       += desconto;

            }else{
                Swal.fire({
                    title: 'Atenção',
                    text: 'Selecione a mesma Pessoa para fazer o Agrupamento de Pedido.',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }
    }
    
    // Validação: verifica se pelo menos um pedido foi selecionado
    if (pedidosSelecionados === 0) {
        Swal.fire({
            title: 'Atenção',
            text: 'Selecione pelo menos um pedido para agrupar.',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(pedidosSelecionados < 2){
        Swal.fire({
            title: 'Atenção',
            text: 'Você selecionou menos de 2 pedidos para agrupar',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'OK'
        });
        return false; // Impede que continue executando e abra o modal
    }
    
    // f.pessoa já foi preenchido com o ID dentro do loop (linha 152)
    // Não sobrescrever aqui! O campo hidden 'pessoa' deve ter o ID, não o nome
    f.mPessoa.value         = pessoa; // Campo de exibição no modal recebe o NOME
    f.mFrete.value          = currencyFormat(totalFrete);
    f.mDespAcessorias.value = currencyFormat(totalDespAcessorias);
    f.mDesconto.value       = currencyFormat(totalDesconto);
    f.mTotal.value          = currencyFormat(totalPedido);
    f.condPgto.value        = condPg;
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
