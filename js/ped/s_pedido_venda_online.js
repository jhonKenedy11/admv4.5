function submitConfirmarSmart() {
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma Pessoa!');
    } else {
        if (f.id.value == "") {
            alert('Selecione uma Natureza de Operação!');

        } else {
            if (f.id.value == "") {
                alert('Pedido sem itens cadastrado!');
            } else {
                if (confirm('Deseja realmente salvar este pedido') == true) {
                    if (f.submenu.value == "cadastrar") {
                        f.submenu.value = 'altera';
                    } else {
                        f.submenu.value = 'altera';
                    }
                    f.submit();
                } //  
            } //  
        } //  
    }

} // submitConfirmarSmart

function submitConfirmar() {
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma pessoa!');
    } else {
        if (f.id.value == "") {
            alert('Pedido sem itens cadastrado!');
        } else {
            if (confirm('Deseja realmente FINALIZAR este pedido') == true) {
                if (f.submenu.value == "cadastrar") {
                    f.submenu.value = 'altera';
                } else {
                    f.submenu.value = 'altera';
                }
                f.submit();
            } //  
        } //  
    }

} // submitConfirmar

function submitDigitacao() {
    f = document.lancamento;
    f.submenu.value = 'digita';
    f.submit();
} // fim submitVoltar

function submitCalculaImpostos() {
    f = document.lancamento;
    f.submenu.value = 'calculaImpostos';
    f.submit();
} // fim submitVoltar

function submitVoltar() {
    f = document.lancamento;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitLetra() {
     
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "||" + f.codProduto.value + "||";


    // situacao lancamento
    myCheckbox = document.lancamento.elements["situacao[]"];

    l = 0;
    for (var i = 0; i < situacao.options.length; i++) {
        if (situacao[i].selected == true) { l++; }
    }
    f.letra.value = f.letra.value + "|" + l;
    for (var i = 0; i < situacao.options.length; i++) {
        if (situacao[i].selected == true) {
            f.letra.value = f.letra.value + "|" + situacao[i].value;
        }
    }


    // motivo lancamento

    motivos = '';
    for (var i = 0; i < motivo.options.length; i++) {
        if (motivo[i].selected == true) {
            motivos = motivos + "|" + motivo[i].value;
        }
    }

    f.motivosSelecionados.value = motivos;

    f.submit();
} // fim submitVoltar

function submitCadastro() {
     
    // if(document.getElementsByName('pedido_bloqueia')[0].value == 'Y'){
    //     Swal.fire(
    //         'Atenção!',
    //         'Cadastro de pedido bloqueado temporariamente!',
    //         'danger'
    //       )
    //       return false;
    // }
    f = document.lancamento;
    //f.opcao.value = 'pedido_venda';
    f.submenu.value = 'cadastrar';
    f.submit();
} // submitCadastro

function submitAlterar(id, situacao, pessoa) {
    f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.situacao.value = situacao;
    f.pessoa.value = pessoa;
    f.submit();
} // submitAlterar

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitEstornar(id) {
    if (confirm('Deseja realmente Estornar este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'estorna';
        f.id.value = id;
        f.submit();
    } // if
} // submitEstornar

function submitBuscar() {
     
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if (f.pesProduto.value.length > 0 && f.pesProduto.value.length < 3) {
        alert('Digite pelo menos 3 caracteres para pesquisar um produto.');
        return false;
    }
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (f.promocoes == '')) {
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        // alert(f.pesq.value);
        alert('Selecione um filtro para pesquisa.');
    } else {
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        // alert(f.pesq.value);
        f.submit();
    }

} // submitExcluir


function submitIncluirItem() {
     
    f = document.lancamento;
    // situacao lancamento
    if (f.pessoa.value == "") {
        alert('Selecione uma pessoa!');
    } else {
        if (f.natop.value == "") {
            alert('Selecione uma Natureza de Operação!');

        } else {
            submitBuscar();
            f.itensPedido.value = '';
            myCheckbox = document.lancamento.elements["itemCheckbox"];
            if (typeof (myCheckbox.length) == "number") {
                for (var i = 0; i < myCheckbox.length; i++) {
                    if (myCheckbox[i].checked == true) {
                        if (f.itensPedido.value == '') {
                            f.itensPedido.value = myCheckbox[i].value;
                        } else {
                            f.itensPedido.value = f.itensPedido.value + "|" + myCheckbox[i].value;
                        }//if
                    }//if
                }//for
            } else {
                if (myCheckbox.checked == true) {
                    f.itensPedido.value = document.lancamento.elements["itemCheckbox"].value;
                }
            }
            f.submenu.value = 'cadastrarItem';
            f.submit();
            //alert('passou' + f.itensPedido.value);
        }
    }
}

function submitIncluirItemQuant() {
     
    f = document.lancamento;
    f.itensPedido.value = '';
    var table = document.getElementById("datatable-buttons");
    var arr = new Array();
    var r = table.rows.length;
    for (i = 1; i < r; i++) {
        var inputs = table.rows.item(i).getElementsByTagName("input");
        var x = parseFloat(inputs[1].value);
        if ((typeof x === 'number') && (x % 1 === 0)) {
            if (f.itensPedido.value == '') {
                f.itensPedido.value = inputs[0].value + "*" + inputs[1].value;
            } else {
                f.itensPedido.value = f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value;
            }//if
        }
    }
    f.submenu.value = 'cadastrarItem';
    f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;

    f.submit();
}

function submitIncluirItemQuantKit() {
    f = document.lancamento;
    f.itensPedido.value = '';
    var table = document.getElementById("datatable-buttons");
    var arr = new Array();
    var r = table.rows.length;
    for (i = 1; i < r; i++) {
        var inputs = table.rows.item(i).getElementsByTagName("input");
        var x = parseFloat(inputs[1].value);
        if ((typeof x === 'number') && (x % 1 === 0)) {
            if (f.itensPedido.value == '') {
                f.itensPedido.value = inputs[0].value + "*" + inputs[1].value;
            } else {
                f.itensPedido.value = f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value;
            }//if
        }
    }
    f.submenu.value = 'kit';
    f.submit();
}

function submitIncluirItemQuantPreco() {
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma pessoa!');
    } else {
        if (f.natop.value == "") {
            alert('Selecione uma Natureza de Operação!');
        } else {
            if (f.condPgto.value == "0") {
                alert('Selecione uma Condição Pagamento!');
            } else {
                f.itensPedido.value = '';
                var table = document.getElementById("datatable-buttons");
                var r = table.rows.length;
                for (i = 1; i < r; i++) {
                    var inputs = table.rows.item(i).getElementsByTagName("input");
                    var x = parseFloat(inputs[1].value);
                    if ((typeof x === 'number') && (x % 1 === 0)) {
                        if (f.itensPedido.value == '') {
                            f.itensPedido.value = inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value;
                        } else {
                            f.itensPedido.value = f.itensPedido.value + "|" + inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value;
                        }//if
                    }
                }
                f.submenu.value = 'cadastrarItem';
                f.submit();
            }
        }
    }
}


function submitExcluirItem(id, nrItem) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        //submitBuscar();
        f = document.lancamento;
        f.submenu.value = 'excluiItem';
        f.id.value = id;
        f.nrItem.value = nrItem;
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        f.submit();
    } // if
} // submitExcluir

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function submitEntregue(id) {
    if (confirm('Deseja realmente colocar como entregue o pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'entregue';
        f.id.value = id;
        f.submit();
    } // if
} // submitEntregue

function submitAgruparPedidos() {
    f = document.lancamento;
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var pedidos = '';
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if (row.pedidoChecked.checked == true) {
            pedidos = pedidos + "|" + row[0].id;
        }
    }
    if (f.nome.value == "") {
        alert('Selecione um cliente!');
        f.submenu.value = '';
    } else {
        f.agrupar_pedidos.value = '';
        f.agrupar_pedidos.value = pedidos;
        f.submenu.value = 'agruparPedidos';
        f.submit();
    }
}

function submitSelecionarTodos(marcar) {
    f = document.lancamento;
    var itens = document.getElementsByName("checkedPerdido");
    var i = 0;
    for (i = 0; i < itens.length; i++) {
        itens[i].checked = marcar;
    }
} // submitSelecionarTodos

function submitExibirMotivo() {
    f = document.lancamento;
    f.submenu.value = 'motivo';
    f.exibirmotivo.value = 'S';
    f.submit();
} // submitAlterar

function submitPedidoPerdidoSalvar() {
     
    f = document.lancamento;
    var itensperdido = '';
    //var table = document.getElementById("datatable-buttons2");  
    var box = document.getElementById("motivoselecionado");
    for (i = 0; i < box.length; i++) {
        if (box.item(i).selected == true) {
            itensperdido = box.item(i).value;
        }
    }
    if (itensperdido == "") {
        alert('Selecione um motivo!');
    } else {
        var table = document.getElementById("bodyMotivo");
        var r = table.rows.length;
        itens = '';
        for (i = 0; i < r; i++) {
            row = table.rows.item(i).getElementsByTagName("input");
            if (row.checkedPerdido.checked == true) {
                itens = itens + "|" + row[0].id;
            }
        }
        if (itens.length > 1) {
            f.itensperdido.value = itensperdido + itens;
            f.submenu.value = 'itensmotivosalvar';
            f.submit();
        } else {
            alert('Selecione um item!');
        }

    }
}

function submitIncluirItemQuantPrecoPecas() {
     
    f = document.lancamento;
    if (f.pessoa.value == "") {
        alert('Selecione uma pessoa!');
    } else {
        if (f.natop.value == "") {
            alert('Selecione uma Natureza de Operação!');
        } else {
            if (f.condPgto.value == "0") {
                alert('Selecione uma Condição Pagamento!');
            } else {
                f.itensPedido.value = '';
                var table = document.getElementById("datatable-buttons");
                var r = table.rows.length;
                for (i = 1; i < r; i++) {
                    var inputs = table.rows.item(i).getElementsByTagName("input");
                    var x = parseFloat(inputs[1].value);
                    if ((typeof x === 'number') && (x % 1 === 0)) {
                        if (f.itensPedido.value == '') {
                            f.itensPedido.value = inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value + "*" + inputs[3].value;
                        } else {
                            f.itensPedido.value = f.itensPedido.value + "|" + inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value + "*" + inputs[3].value;
                        }//if
                    }
                }
                f.submenu.value = 'cadastrarItem';
                f.submit();
            }
        }
    }
}

function duplicaPedido(id) {
    if (confirm('Deseja realmente DUPLICAR este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'duplicaPedido';
        f.id.value = id;
        f.submit();
    } 
    
}


function submitTodosEmLinha() {
    var idPedido = $('[name="id"]').val();

    $.post(
        'index.php?mod=ped&form=pedido_venda_online',
        { 
            submenu: 'todosEmLinha',
            id: idPedido
        }
    )
    .done(function(resposta) {
        document.open();
        document.write(resposta);
        document.close();
    })
    .fail(function(xhr, status, error) {
        console.error('Erro:', error);
        alert('Erro ao processar a requisição!');
    });
}

function returnIncluirItemQuant(response) {
    $('#carrinho tbody').html(response.htmlCarrinho);
    $('#totalPedidoValor').html(response.htmlTotal);

    // Atualiza os estoques dos produtos na lista de inclusão
    if (response.estoques) {
        debugger
        Object.keys(response.estoques).forEach(function(codigo) {
            var estoqueSpan = document.getElementById('estoque' + codigo);
            if (estoqueSpan) {
                estoqueSpan.textContent = response.estoques[codigo];
                estoqueSpan.style.display = '';
            }
        });
    }

    if (response.msg) {
        $('#mensagem').text(response.msg);
    }
}

async function ajaxIncluirItemQuantUnico(codigo, btn) {
    var f = document.lancamento;
    var inputQuant = document.getElementById("quant" + codigo);
    var quantidade = parseFloat(inputQuant.value);

    if (isNaN(quantidade) || quantidade <= 0) {
        alert('Informe uma quantidade válida!');
        return;
    }
    var estoque = parseInt($("#estoque" + codigo).text());
    if (!isNaN(estoque) && quantidade > estoque) {
        alert('Quantidade maior que o estoque disponível!');
        return;
    }

    $(btn).prop('disabled', true);
     

    var itensPedido = [{ codigo: codigo, quantidade: quantidade }];
    var dados = {
        itensPedido: itensPedido,
        id: f.id.value,
        pessoa: f.pessoa.value,
        pesq: f.pesq.value,
        grupo: f.grupo.value,
        promocoes: f.promocoes.value,
        pesProduto: f.pesProduto.value,
        itensQtde: f.itensQtde.value
    };

    try {
        
        let response = await $.ajax({
            type: "POST",
            url: "index.php?mod=ped&form=pedido_venda_online&submenu=cadastrarItem&opcao=blank",
            data: dados,
            dataType: "json"
        });

        // Atualiza o campo id do form se veio do backend
        if (response.id) {
            f.id.value = response.id;
        }

        returnIncluirItemQuant(response);

        inputQuant.value = '';

        if (response.estoques && response.estoques[codigo] !== undefined) {
            $("#estoque" + codigo).text(response.estoques[codigo]);
        }

        let $row = $(inputQuant).closest('tr');
        $row.addClass('success');
        setTimeout(() => $row.removeClass('success'), 1000);

        if (response.msg) {
            $('#mensagem').text(response.msg);
        }
    } catch (e) {
        alert('Erro ao adicionar item: ' + (e.responseText || e.statusText));
    } finally {
        $(btn).prop('disabled', false);
    }
}

function ajaxExcluirItem(id, nrItem, btn) {
     
    if (!confirm('Deseja realmente Excluir este item?')) return;
    $(btn).prop('disabled', true);
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_venda_online&submenu=excluiItem&opcao=blank",
        data: { id: id, nrItem: nrItem },
        dataType: "json",
        success: function(response) {
            returnIncluirItemQuant(response);
        },
        error: function(e) {
            alert('Erro ao excluir item: ' + e.responseText);
        },
        complete: function() {
            $(btn).prop('disabled', false);
        }
    });
}

function ajaxAtualizarEstoque(codigo, btn) {
    $(btn).prop('disabled', true)
     .html('<span class="glyphicon glyphicon-refresh spinning"></span>');
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_venda_online&submenu=getEstoque&opcao=blank",
        data: { codigo: codigo },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var estoque = response.estoque;
                var span = $("#estoque" + codigo);
                if (span.length) {
                    span.text(estoque).show();
                }
                // Esconde o botão e mostra o span
                $(btn).hide();
            } else {
                alert('Erro ao buscar estoque!');
                $(btn).prop('disabled', false).text('Atualizar Estoque');
            }
        },
        error: function() {
            alert('Erro ao buscar estoque!');
            $(btn).prop('disabled', false).text('Atualizar Estoque');
        }
    });
}
