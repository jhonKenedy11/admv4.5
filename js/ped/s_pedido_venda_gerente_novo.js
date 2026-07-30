function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitImprime(id, pag, situacao) {
    window.open(pag, 'imprime', 'toolbar=no,location=no,menubar=no,width=1200,height=650,scrollbars=yes');

    // Situação 6 (Pedido): submit para avançar fase após conferência. Situação 3: só reimprime.
    if (parseInt(situacao, 10) !== 6) {
        return;
    }

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'imprime';
    f.id.value = id;
    f.submit();
} // submitImprime


function submitEditarPedido(id) {
    window.open(
        'index.php?mod=ped&form=pedido_ps&submenu=alterar&id=' + encodeURIComponent(id),
        '_blank'
    );
}

function alertaGerenteEncomendaSemNf() {
    Swal.fire({
        icon: 'info',
        title: 'Pedido em encomenda',
        text: 'A emissão de NF está bloqueada até a entrada de estoque liberar o pedido. '
            + 'Cadastre o financeiro na coluna Financeiro.',
        confirmButtonText: 'OK'
    });
}

function submitCadastro(id, situacao) {
    if (parseInt(situacao, 10) === 13) {
        alertaGerenteEncomendaSemNf();
        return;
    }
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.opcao.value = 'pedido_venda_gerente_novo';
    f.id.value = id;
    f.submenu.value = 'notafiscal';
    f.submit();
} // fim submit

function submitCadastroCupom(id, situacao) {
    if (parseInt(situacao, 10) === 13) {
        alertaGerenteEncomendaSemNf();
        return;
    }
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.opcao.value = 'pedido_venda_gerente_novo';
    f.id.value = id;
    f.tipoDocFiscal.value = '65';
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

function submitUltimos60Dias() {
    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = 'ultimos60Dias';
    f.submit();
} // fim submit

function marcarTodosPedidosAgrupa(marcar) {
    var checks = document.querySelectorAll('#datatable-buttons input.pedido-agrupa-check');
    if (!checks.length) {
        return;
    }

    var marcarTodos;
    if (marcar === true || marcar === false) {
        marcarTodos = marcar;
    } else {
        marcarTodos = true;
        for (var i = 0; i < checks.length; i++) {
            if (!checks[i].checked) {
                marcarTodos = false;
                break;
            }
        }
        marcarTodos = !marcarTodos;
    }

    for (var j = 0; j < checks.length; j++) {
        checks[j].checked = marcarTodos;
    }

    var master = document.getElementById('pedidoAgrupaMarcarTodos');
    if (master) {
        master.checked = marcarTodos;
        master.indeterminate = false;
    }
}

function toggleMarcarTodosPedidos(checked) {
    marcarTodosPedidosAgrupa(checked);
}

function coletaPedidosAgrupamento() {
    var pedidos = '';
    var linhas = document.querySelectorAll('#datatable-buttons tbody tr');
    for (var i = 0; i < linhas.length; i++) {
        var cb = linhas[i].querySelector('input.pedido-agrupa-check');
        if (cb && cb.checked && cb.value) {
            pedidos += '|' + cb.value;
        }
    }
    return pedidos;
}

function submitAgruparPedidos(){
    f = document.lancamento;
    var pedidos = f.pedidoAgrupado.value || coletaPedidosAgrupamento();
    if (!pedidos) {
        Swal.fire({
            title: 'Atenção',
            text: 'Nenhum pedido selecionado para agrupar.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return false;
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
    var pedidosSelecionados = 0;
    var pedidos = '';
    totalFrete          = 0;
    totalDespAcessorias = 0;
    totalDesconto       = 0; 
    totalPedido         = 0;

    var idPessoaSelecionada = '';
    
    for (i = 1; i < r; i++) {
        var cb = table.rows[i].querySelector('input.pedido-agrupa-check');
        if (!cb || !cb.checked) {
            continue;
        }

        pedidosSelecionados++;
        if (cb.value) {
            pedidos += '|' + cb.value;
        }

        var cells = table.rows[i].getElementsByTagName("td");

        novaPessoa = cells[1].childNodes[0].data;    
        dados   = cells[6].childNodes[0].data; 
        arrDados = dados.split("|");

        idPessoa   = arrDados[3].trim(); 

        if (pessoa === ''){
            pessoa = novaPessoa;
            idPessoaSelecionada = idPessoa;
            f.pessoa.value = idPessoa;
        }
        if(condPg === ''){
           condPg = arrDados[4].trim();
        }
        if(novaPessoa === pessoa){
            total          = cells[5].childNodes[0].data;
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
    f.pedidoAgrupado.value  = pedidos;
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
