function atualizarDataEmissao(id, data) {
  debugger;
  f = document.lancamento;
  f.id.value = id;
  f.letra.value = data;
  f.submenu.value = "atualizarDataEmissao";
  if (data == "") {
    alert("Data não informada!");
  } else {
    f.submit();
  }
}

function submitConfirmarSmartKeyPedido(user, password) {
  debugger;
  f = document.lancamento;
  f.submenu.value = "cadastrarPedido";
  f.submit();
} // submitConfirmarSmartKeyPedido

function atualizarCCEntrega(id) {
  debugger;
  f = document.lancamento;
  f.id.value = id;

  // VENDEDOR
  first = true;
  ccentrega = "";
  for (var i = 0; i < f.centroCustoEntrega.options.length; i++) {
    if (f.centroCustoEntrega[i].selected == true) {
      if (first == true) {
        first = false;
        ccentrega = f.centroCustoEntrega[i].value;
      } else ccentrega = ccentrega + "," + f.centroCustoEntrega[i].value;
    }
  }

  f.letra.value = ccentrega;
  f.submenu.value = "atualizarCCEntrega";
  f.submit();
}

function submitConfirmarSmartKey(user, password) {
  debugger;

  f = document.lancamento;
  if (f.pessoa.value == "") {
    alert("Selecione uma Pessoa!");
  } else {
    if (f.id.value == "") {
      alert("Selecione uma Natureza de Operação!");
    } else {
      if (f.id.value == "") {
        alert("Pedido sem itens cadastrado!");
      } else {
        if (f.submenu.value == "cadastrar") {
          f.submenu.value = "inclui";
        } else {
          f.submenu.value = "altera";
        }
        f.submit();
      } //
    } //
  }
} // submitConfirmarSmartKey

function atualizarVendedor(id) {
  debugger;
  f = document.lancamento;
  f.id.value = id;

  // VENDEDOR
  first = true;
  usrfaturaalterar = "";
  for (var i = 0; i < f.usrfaturaalterar.options.length; i++) {
    if (f.usrfaturaalterar[i].selected == true) {
      if (first == true) {
        first = false;
        usrfaturaalterar = f.usrfaturaalterar[i].value;
      } else
        usrfaturaalterar = usrfaturaalterar + "," + f.usrfaturaalterar[i].value;
    }
  }

  f.letra.value = usrfaturaalterar;
  f.submenu.value = "atualizarVendedor";
  f.submit();
}

function relatorioDeEntregas() {
  debugger;
  f = document.lancamento;
  f.letra.value = "";
  montaLetra();
  f.mod.value = "ped";
  f.submenu.value = "relatorioDeEntregas";
  window.open(
    "index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&submenu=" +
      f.submenu.value +
      "&vendedores=" +
      f.vendedorSelecionados.value +
      "&condPag=" +
      f.condPagamentoSelecionados.value +
      "&situacao=" +
      f.situacaoSelecionados.value +
      "&centroCusto=" +
      f.centroCustoSelecionados.value +
      "&letra=" +
      f.letra.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}

function devolucao(pedido, nritem, quantidade, qtdeVendida, unitario, total) {
  debugger;
  f = document.lancamento;
  f.id.value = pedido;
  f.letra.value =
    pedido + "|" + nritem + "|" + quantidade + "|" + unitario + "|" + total + "|" + qtdeVendida;
  f.submenu.value = "devolucao";
  f.submit();
}

function atualizarDataEntrega(id) {
  debugger;
  f = document.lancamento;
  f.submenu.value = "atualizarDataEntrega";
  f.id.value = id;
  f.submit();
}

function atualizarPrazoEntrega(id, data) {
  debugger;
  f = document.lancamento;
  f.id.value = id;
  f.letra.value = data;
  f.submenu.value = "atualizarPrazoEntrega";
  if (data == "") {
    alert("Data não informada!");
  } else {
    f.submit();
  }
}

function atualizaCustoNovo(
  id,
  nritem,
  custo,
  total,
  quant,
  descricao,
  codigo,
  estoque
) {
  debugger;
  f = document.lancamento;
  f.id.value = id;
  f.nrItem.value = nritem;
  f.letra.value =
    custo +
    "|" +
    total +
    "|" +
    quant +
    "|" +
    descricao +
    "|" +
    codigo +
    "|" +
    estoque;
  f.submenu.value = "atulizarInfoItem";
  f.submit();
}

function submitLetraDash() {
  debugger;
  f = document.lancamento;
  f.letra.value = "";
  f.submenu.value = "pesquisa";

  //CENTRO DE CUSTO
  first = true;
  centroCustos = "";
  for (var i = 0; i < centroCusto.options.length; i++) {
    if (centroCusto[i].selected == true) {
      if (first == true) {
        first = false;
        centroCustos = centroCusto[i].value;
      } else centroCustos = centroCustos + "," + centroCusto[i].value;
    }
  }

  f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + centroCustos;

  console.log(f.letra.value);

  f.submit();
} // fim submitVoltar

function salvarMotivoNoPedido(id) {
  debugger;
  f = document.lancamento;
  first = true;
  motivo = "";
  for (var i = 0; i < motivoPerdido.options.length; i++) {
    if (motivoPerdido[i].selected == true) {
      if (first == true) {
        first = false;
        motivo = motivoPerdido[i].value;
      } else motivo = motivo + "," + motivoPerdido[i].value;
    }
  }
  f.motivoSelected.value = motivo;
  f.id.value = id;
  f.submenu.value = "motivoGeral";
  f.submit();
}

function atualizarInfo() {
  debugger;
  f = document.lancamento;
  var desconto = parseFloat(
    f.desconto.value.replace(".", "").replace(",", ".")
  );
  var total = parseFloat(
    f.totalPedido.value.replace(".", "").replace(",", ".")
  );
  if (desconto > total) {
    alert("O desconto nao pode ser maior do que o valor total.");
    f.desconto.value = "0,00";
    return false;
  }
  if (f.desconto.value == "") {
    f.desconto.value = "0,00";
  }
  
  // p/ nao perder as casas decimais ex: 10,50
  var newFrete = parseFloat(f.frete.value.replace(".", "").replace(",", "."))
  f.frete.value = newFrete;
       
  var newDesconto = parseFloat(f.desconto.value.replace(".", "").replace(",", "."))
  f.desconto.value = newDesconto;

  var newDespAcessorias = parseFloat(f.despAcessorias.value.replace(".", "").replace(",", "."))
  f.despAcessorias.value = newDespAcessorias;
  f.submenu.value = "atualizarInfo";
  f.submit();
} // atualizarInfo

function submitNFE(id) {
  if (confirm("Deseja realmente enviar nota para a receita?") == true) {
    f = document.lancamento;
    f.submenu.value = "NFE";
    f.id.value = id;
    f.submit();
  } // if
}

function submitCadastrarPedido() {
  debugger;
  f = document.lancamento;
  f.submenu.value = "cadastrarPedido";
  f.submit();
}

function submitAlterarPedido() {
  debugger;
  f = document.lancamento;
  f.submenu.value = "alteraPedidoNew";
  f.submit();
}

function submitConfirmarSmart() {
  debugger;

  f = document.lancamento;
  if (f.pessoa.value == "") {
    alert("Selecione uma Pessoa!");
  } else {
    if (f.id.value == "") {
      alert("Selecione uma Natureza de Operação!");
    } else {
      if (f.id.value == "") {
        alert("Pedido sem itens cadastrado!");
      } else {
        if (confirm("Deseja realmente salvar este pedido") == true) {
          if (f.submenu.value == "cadastrar") {
            f.submenu.value = "inclui";
          } else {
            f.submenu.value = "altera";
          }
          f.submit();
        } //
      } //
    } //
  }
} // submitConfirmarSmart

function submitDesaprovado() {
  debugger;
  f = document.lancamento;
  f.submenu.value = "desaprovado";
  f.submit();
}

function submitAprovado() {
  debugger;
  f = document.lancamento;
  if (f.pessoa.value == "") {
    alert("Selecione uma Pessoa!");
  } else {
    if (f.id.value == "") {
      alert("Selecione uma Natureza de Operação!");
    } else {
      if (f.id.value == "") {
        alert("Pedido sem itens cadastrado!");
      } else {
        if (confirm("Deseja realmente salvar este pedido") == true) {
          f.submenu.value = "aprovado";
          f.submit();
        } //
      } //
    } //
  }
} // submitAprovado

function submitConfirmar() {
  debugger;
  f = document.lancamento;
  if (f.pessoa.value == "") {
    alert("Selecione uma pessoa!");
  } else {
    if (f.id.value == "") {
      alert("Pedido sem itens cadastrado!");
    } else {
      if (confirm("Deseja realmente FINALIZAR este pedido") == true) {
        if (f.submenu.value == "cadastrar") {
          f.submenu.value = "inclui";
        } else {
          f.submenu.value = "altera";
        }
        f.submit();
      } //
    } //
  }
} // submitConfirmar

function submitDigitacao() {
  f = document.lancamento;
  f.submenu.value = "digita";
  f.submit();
} // fim submitVoltar

function submitCalculaImpostos() {
  f = document.lancamento;
  f.submenu.value = "calculaImpostos";
  f.submit();
} // fim submitVoltar

function submitVoltar() {
  f = document.lancamento;
  f.submenu.value = "";
  f.submit();
} // fim submitVoltar

function submitLetra() {
  debugger;
  f = document.lancamento;
  f.letra.value = "";
  f.submenu.value = "pesquisa";

  //if (f.codPedido.value != "") {
   // f.letra.value = "||||||||" + f.codPedido.value;
  //} else {
    // situacao lancamento
    first = true;
    situacoes = "";
    for (var i = 0; i < situacaoCombo.options.length; i++) {
      if (situacaoCombo[i].selected == true) {
        if (first == true) {
          first = false;
          situacoes = situacaoCombo[i].value;
        } else situacoes = situacoes + "," + situacaoCombo[i].value;
      }
    }

    // VENDEDOR
    first = true;
    vendedores = "";
    for (var i = 0; i < vendedor.options.length; i++) {
      if (vendedor[i].selected == true) {
        if (first == true) {
          first = false;
          vendedores = vendedor[i].value;
        } else vendedores = vendedores + "," + vendedor[i].value;
      }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = "";
    for (var i = 0; i < condPag.options.length; i++) {
      if (condPag[i].selected == true) {
        if (first == true) {
          first = false;
          condPagamentos = condPag[i].value;
        } else condPagamentos = condPagamentos + "," + condPag[i].value;
      }
    }

    //CENTRO DE CUSTO
    first = true;
    centroCustos = "";
    for (var i = 0; i < centroCusto.options.length; i++) {
      if (centroCusto[i].selected == true) {
        if (first == true) {
          first = false;
          centroCustos = centroCusto[i].value;
        } else centroCustos = centroCustos + "," + centroCusto[i].value;
      }
    }

    // motivo lancamento
    first = true;
    motivos = "";
    /* 
        if (motivo.options.length > 0) {
            for (var i = 0; i < motivo.options.length; i++) {
                if (motivo[i].selected == true) {
                    if (first == true) {
                        first = false;
                        motivos = motivo[i].value;
                    }
                    else motivos = motivos + "," + motivo[i].value;
                }
            }
        } 
        */

    f.letra.value =
      f.dataIni.value +
      "|" +
      f.dataFim.value +
      "|" +
      f.pessoa.value +
      "|" +
      f.codProduto.value +
      "|" +
      situacoes +
      "|" +
      vendedores +
      "|" +
      condPagamentos +
      "|" +
      centroCustos +
      "|" +
      motivos +
      "|" +
      f.codPedido.value;
 // } // fim else codPedido
  console.log(f.letra.value);

  f.submit();
} // fim submitVoltar

function submitCadastro() {
  debugger;
  f = document.lancamento;
  //f.opcao.value = 'pedido_venda';
  f.submenu.value = "cadastrar";
  f.submit();
} // submitCadastro

function submitAlterar(id, situacao, pessoa) {
  debugger;
  f = document.lancamento;
  f.submenu.value = "alterar";
  f.id.value = id;
  f.situacaoCombo.value = situacao;
  f.pessoa.value = pessoa;
  f.letra_old.value = f.letra.value;
  f.submit();
} // submitAlterar

function submitExcluir(id) {
  debugger;
  if (confirm("Deseja realmente Excluir este pedido") == true) {
    f = document.lancamento;
    f.submenu.value = "exclui";
    f.id.value = id;
    f.submit();
  } // if
} // submitExcluir

function submitEstornar(id) {
  if (confirm("Deseja realmente Estornar este pedido") == true) {
    f = document.lancamento;
    f.submenu.value = "estorna";
    f.id.value = id;
    f.submit();
  } // if
} // submitEstornar

function submitBuscar() {
  debugger;
  f = document.lancamento;
  f.submenu.value = "cadastrar";
  if (
    f.pesProduto.value == "" &&
    f.pesLocalizacao.value == "" &&
    f.grupo.value == "" &&
    f.promocoes == ""
  ) {
    f.pesq.value =
      f.pesProduto.value +
      "|" +
      f.grupo.value +
      "|" +
      f.promocoes.value +
      "|" +
      f.pesLocalizacao.value;
    // alert(f.pesq.value);
    alert("Selecione um filtro para pesquisa.");
  } else {
    f.pesq.value =
      f.pesProduto.value +
      "|" +
      f.grupo.value +
      "|" +
      f.promocoes.value +
      "|" +
      f.pesLocalizacao.value;
    // alert(f.pesq.value);

    // p/ nao perder as casas decimais ex: 10,50
    var newFrete = parseFloat(f.frete.value.replace(".", "").replace(",", "."))
    f.frete.value = newFrete;
     
    var newDesconto = parseFloat(f.desconto.value.replace(".", "").replace(",", "."))
    f.desconto.value = newDesconto;

    var newDespAcessorias = parseFloat(f.despAcessorias.value.replace(".", "").replace(",", "."))
    f.despAcessorias.value = newDespAcessorias;

    f.submit();
  }
} // submitExcluir

function submitIncluirDescItem(id, nrItem) {
  debugger;

  f = document.lancamento;
  f.submenu.value = "incluiDescItem";
  f.id.value = id;
  f.nrItem.value = nrItem;
  f.desc.value = f.querySelector("#desc1").innerText;
  f.pesq.value =
    f.pesProduto.value +
    "|" +
    f.grupo.value +
    "|" +
    f.promocoes.value +
    "|" +
    f.pesLocalizacao.value;
  f.submit();
} // submitIncluirDescItem

function submitIncluirItem() {
  debugger;
  f = document.lancamento;
  // situacao lancamento
  if (f.pessoa.value == "") {
    alert("Selecione uma pessoa!");
  } else {
    if (f.natop.value == "") {
      alert("Selecione uma Natureza de Operação!");
    } else {
      submitBuscar();
      f.itensPedido.value = "";
      myCheckbox = document.lancamento.elements["itemCheckbox"];
      if (typeof myCheckbox.length == "number") {
        for (var i = 0; i < myCheckbox.length; i++) {
          if (myCheckbox[i].checked == true) {
            if (f.itensPedido.value == "") {
              f.itensPedido.value = myCheckbox[i].value;
            } else {
              f.itensPedido.value =
                f.itensPedido.value + "|" + myCheckbox[i].value;
            } //if
          } //if
        } //for
      } else {
        if (myCheckbox.checked == true) {
          f.itensPedido.value =
            document.lancamento.elements["itemCheckbox"].value;
        }
      }
      f.submenu.value = "cadastrarItem";
      f.submit();
      //alert('passou' + f.itensPedido.value);
    }
  }
}

function submitIncluirItemQuant() {
  debugger;
  f = document.lancamento;
  f.itensPedido.value = "";
  var table = document.getElementById("datatable-buttons");
  var arr = new Array();
  var r = table.rows.length;
  for (i = 1; i < r; i++) {
    var inputs = table.rows.item(i).getElementsByTagName("input");
    var x = parseFloat(inputs[1].value);
    if (typeof x === "number" && x % 1 === 0) {
      if (f.itensPedido.value == "") {
        f.itensPedido.value = inputs[0].value + "*" + inputs[1].value;
      } else {
        f.itensPedido.value =
          f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value;
      } //if
    }
  }
  f.submenu.value = "cadastrarItem";
  f.pesq.value =
    f.pesProduto.value +
    "|" +
    f.grupo.value +
    "|" +
    f.promocoes.value +
    "|" +
    f.pesLocalizacao.value;

  f.submit();
}

function submitIncluirItemQuantKit() {
  f = document.lancamento;
  f.itensPedido.value = "";
  var table = document.getElementById("datatable-buttons");
  var arr = new Array();
  var r = table.rows.length;
  for (i = 1; i < r; i++) {
    var inputs = table.rows.item(i).getElementsByTagName("input");
    var x = parseFloat(inputs[1].value);
    if (typeof x === "number" && x % 1 === 0) {
      if (f.itensPedido.value == "") {
        f.itensPedido.value = inputs[0].value + "*" + inputs[1].value;
      } else {
        f.itensPedido.value =
          f.itensPedido.value + "|" + inputs[0].value + "*" + inputs[1].value;
      } //if
    }
  }
  f.submenu.value = "kit";
  f.submit();
}

function submitIncluirItemQuantPreco() {
  debugger;
  f = document.lancamento;
  if (f.pessoa.value == "") {
    alert("Selecione uma pessoa!");
  } else {
    if (f.natop.value == "") {
      alert("Selecione uma Natureza de Operação!");
    } else {
      if (f.condPgto.value == "0") {
        alert("Selecione uma Condição Pagamento!");
      } else {
        f.itensPedido.value = "";
        var table = document.getElementById("datatable-buttons");
        var r = table.rows.length;
        for (i = 1; i < r; i++) {
          var inputs = table.rows.item(i).getElementsByTagName("input");
          var x = parseFloat(inputs[1].value);
          if (typeof x === "number" && x % 1 === 0) {
            if (f.itensPedido.value == "") {
              f.itensPedido.value =
                inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value;
            } else {
              f.itensPedido.value =
                f.itensPedido.value +
                "|" +
                inputs[1].value +
                "*" +
                inputs[0].value +
                "*" +
                inputs[2].value;
            } //if
          }
        }
        f.submenu.value = "cadastrarItem";
        f.submit();
      }
    }
  }
}

function submitExcluirItem(id, nrItem) {
  debugger;
  if (confirm("Deseja realmente Excluir este item") == true) {
    //submitBuscar();
    f = document.lancamento;
    f.submenu.value = "excluiItem";
    f.id.value = id;
    f.nrItem.value = nrItem;
    f.pesq.value =
      f.pesProduto.value +
      "|" +
      f.grupo.value +
      "|" +
      f.promocoes.value +
      "|" +
      f.pesLocalizacao.value;
      
      // p/ nao perder as casas decimais ex: 10,50
      var newFrete = parseFloat(f.frete.value.replace(".", "").replace(",", "."))
      f.frete.value = newFrete;
       
      var newDesconto = parseFloat(f.desconto.value.replace(".", "").replace(",", "."))
      f.desconto.value = newDesconto;

      var newDespAcessorias = parseFloat(f.despAcessorias.value.replace(".", "").replace(",", "."))
      f.despAcessorias.value = newDespAcessorias;

    f.submit();
  } // if
} // submitExcluir

function abrir(pag) {
  window.open(
    pag,
    "consulta",
    "toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes"
  );
}

function submitEntregue(id) {
  if (confirm("Deseja realmente colocar como entregue o pedido") == true) {
    f = document.lancamento;
    f.submenu.value = "entregue";
    f.id.value = id;
    f.submit();
  } // if
} // submitEntregue

function submitAgruparPedidos() {
  f = document.lancamento;
  var table = document.getElementById("datatable-buttons");
  var r = table.rows.length;
  var pedidos = "";
  for (i = 1; i < r; i++) {
    var row = table.rows.item(i).getElementsByTagName("input");
    if (row.pedidoChecked.checked == true) {
      pedidos = pedidos + "|" + row[0].id;
    }
  }
  if (f.nome.value == "") {
    alert("Selecione um cliente!");
    f.submenu.value = "";
  } else {
    f.agrupar_pedidos.value = "";
    f.agrupar_pedidos.value = pedidos;
    f.submenu.value = "agruparPedidos";
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
  f.submenu.value = "motivo";
  f.exibirmotivo.value = "S";
  f.submit();
} // submitAlterar

function submitPedidoPerdidoSalvar() {
  debugger;
  f = document.lancamento;
  var itensperdido = "";
  //var table = document.getElementById("datatable-buttons2");
  var box = document.getElementById("motivoselecionado");
  for (i = 0; i < box.length; i++) {
    if (box.item(i).selected == true) {
      itensperdido = box.item(i).value;
    }
  }
  if (itensperdido == "") {
    alert("Selecione um motivo!");
  } else {
    var table = document.getElementById("bodyMotivo");
    var r = table.rows.length;
    itens = "";
    for (i = 0; i < r; i++) {
      row = table.rows.item(i).getElementsByTagName("input");
      if (row.checkedPerdido.checked == true) {
        itens = itens + "|" + row[0].id;
      }
    }
    if (itens.length > 1) {
      f.itensperdido.value = itensperdido + itens;
      f.submenu.value = "itensmotivosalvar";
      f.submit();
    } else {
      alert("Selecione um item!");
    }
  }
}

function submitIncluirItemQuantPrecoPecas(str) {
  debugger;
  f = document.lancamento;

  array_produtos = str.split("|");

  if (f.pessoa.value == "") {
    alert("Selecione uma pessoa!");
    return false;
  } else {
    if (f.natop != undefined && f.natop.value == "") {
      alert("Selecione uma Natureza de Operação!");
      return false;
    } else {
      if (f.condPgto.value == "0") {
        alert("Selecione uma Condição Pagamento!");
        return false;
      } else {

        f.itensPedido.value = "";
        
        var table = document.getElementById("datatable-buttons");
        var r = table.rows.length;
        for (i = 1; i < r; i++) {
          var inputs = table.rows.item(i).getElementsByTagName("input");
          var inputsDesc = table.rows.item(i+1).getElementsByTagName("input");
          var promo = table.rows.item(i).getElementsByTagName("td");
          var x = parseFloat(inputs[2].value);
          resp = "";
          if (typeof x === "number" && x % 1 === 0) {
            var quant = parseFloat(inputs[3].value.replace(".", "").replace(",", "."))
            if (quant > 0.00) {
              if (array_produtos[0] != "") {
                for (c in array_produtos) {
                  if (array_produtos[c] == inputs[2].value) {
                    if (
                      confirm(
                        "Item já existente no pedido. Adicionar como item novo deste pedido?"
                      )
                    ) {
                      resp = "S";
                    } else {
                      resp = "N";
                    }
                  }
                }
              }
              if (f.itensPedido.value == "") {
                f.itensPedido.value =
                  inputs[0].value +
                  "$" +
                  inputs[2].value +
                  "*" +
                  inputs[1].value +
                  "*" +
                  inputs[3].value +
                  "*" +
                  promo[5].innerText +
                  "*" +
                  resp +
                  "*" +
                  inputsDesc[0].value;
              } else {
                f.itensPedido.value =
                  f.itensPedido.value +
                  "|" +
                  inputs[0].value +
                  "$" +
                  inputs[2].value +
                  "*" +
                  inputs[1].value +
                  "*" +
                  inputs[3].value +
                  "*" +
                  promo[5].innerText +
                  "*" +
                  resp +
                  "*"  +
                  inputsDesc[0].value;
              } //if
            }
            i = i + 2;
          }
        }
        f.submenu.value = "cadastrarItem";
        // p/ nao perder as casas decimais ex: 10,50
        var newFrete = parseFloat(f.frete.value.replace(".", "").replace(",", "."))
        f.frete.value = newFrete;
        
        var newDesconto = parseFloat(f.desconto.value.replace(".", "").replace(",", "."))
        f.desconto.value = newDesconto;

        var newDespAcessorias = parseFloat(f.despAcessorias.value.replace(".", "").replace(",", "."))
        f.despAcessorias.value = newDespAcessorias;
        f.submit();
      }
    }
  }
}

/*
function submitIncluirItemQuantPrecoPecas(str) {
    debugger;
    f = document.lancamento;

    array_produtos = str.split("|");

    if (f.pessoa.value == "") {
        alert('Selecione uma pessoa!');
    } else {
        if ((f.natop != undefined) && (f.natop.value == "")) {

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
                    var promo = table.rows.item(i).getElementsByTagName("td");
                    var x = parseFloat(inputs[1].value);
                    resp = "";
                    if ((typeof x === 'number') && (x % 1 === 0)) {
                        if (inputs[2].value > 0) {
                            if (array_produtos[0] != "") {
                                for (c in array_produtos) {
                                    if (array_produtos[c] == inputs[1].value) {
                                        if (confirm("Item já existente no pedido. Adicionar como item novo deste pedido?")) {
                                            resp = "S"
                                        } else {
                                            resp = "N";
                                        }
                                    }
                                }
                            }
                            if (f.itensPedido.value == '') {
                                f.itensPedido.value = inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value + "*" + promo[6].innerText + "*" + resp;
                            } else {
                                f.itensPedido.value = f.itensPedido.value + "|" + inputs[1].value + "*" + inputs[0].value + "*" + inputs[2].value + "*" + promo[6].innerText + "*" + resp;
                            }//if
                        }
                    }
                }
                f.submenu.value = 'cadastrarItem';
                f.submit();
            }
        }
    }
}
*/

function consultaPrint(form) {
  debugger;
  f = document.lancamento;
  montaLetra();
  f.mod.value = "ped";
  // f.submenu.value = 'relVenda';
  f.form.value = "pedido_venda_imp_romaneio";
  f.submenu.value = "imprime";
  window.open(
    "index.php?mod=ped&form=" + f.form.value + "&letra=" + f.letra.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}
// NEW
function relatorioVendas(tipoRel) {
  debugger;
  montaLetraRelatorio();
  f.tipoRelatorio.value = tipoRel;
  window.open(
    "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioVendas&letra=" +
      f.letra.value +
      "&situacaoSelected=" +
      f.situacaoSelected.value +
      "&centroCustoSelected=" +
      f.centroCustoSelected.value +
      "&tipoRelatorio=" +
      f.tipoRelatorio.value +
      "&motivoSelected=" +
      f.motivoSelected.value +
      "&vendedorSelected=" +
      f.vendedorSelected.value +
      "&condPagamentoSelected=" +
      f.condPagamentoSelected.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}

function relatorioFaturaSintetico() {
  debugger;
  montaLetraRelatorio();
  window.open(
    "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=relatorioFaturaSintetico&letra=" +
      f.letra.value +
      "&situacaoSelected=" +
      f.situacaoSelected.value +
      "&centroCustoSelected=" +
      f.centroCustoSelected.value +
      "&tipoRelatorio=" +
      f.tipoRelatorio.value +
      "&motivoSelected=" +
      f.motivoSelected.value +
      "&vendedorSelected=" +
      f.vendedorSelected.value +
      "&condPagamentoSelected=" +
      f.condPagamentoSelected.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}

function relatorioFaturaAnalitico() {
  debugger;
  montaLetraRelatorio();
  window.open(
    "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=&letra=" +
      f.letra.value +
      "&situacaoSelected=" +
      f.situacaoSelected.value +
      "&centroCustoSelected=" +
      f.centroCustoSelected.value +
      "&tipoRelatorio=" +
      f.tipoRelatorio.value +
      "&motivoSelected=" +
      f.motivoSelected.value +
      "&vendedorSelected=" +
      f.vendedorSelected.value +
      "&condPagamentoSelected=" +
      f.condPagamentoSelected.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}

function montaLetraRelatorio() {
  debugger;
  f = document.lancamento;
  f.letra.value =
    f.codPedido.value +
    "|" +
    f.dataIni.value +
    "|" +
    f.dataFim.value +
    "|" +
    f.codProduto.value +
    "|" +
    f.pessoa.value;
  // situacao
  f.situacaoSelected.value = concatCombo(situacaoCombo);
  // ccusto
  f.centroCustoSelected.value = concatCombo(centroCusto);
  //motivo
  f.motivoSelected.value = concatCombo(motivo);
  // vendedor
  f.vendedorSelected.value = concatCombo(vendedor);
  // condPagamento
  f.condPagamentoSelected.value = concatCombo(condPag);
}
// concatena combo com pipes
function concatCombo(combo) {
  valor = "";
  for (var i = 0; i < combo.options.length; i++) {
    if (combo[i].selected == true) {
      valor = valor + "|" + combo[i].value;
    }
  }
  return valor;
}

function montaLetra() {
  f = document.lancamento;
  f.letra.value = "";
  f.letra.value =
    f.dataIni.value +
    "|" +
    f.dataFim.value +
    "|" +
    f.pessoa.value +
    "|" +
    f.codProduto.value +
    "|";

  // SITUACAO
  //f.letra.value = f.letra.value + "|" + l;
  situacoes = "";
  for (var i = 0; i < situacaoCombo.options.length; i++) {
    if (situacaoCombo[i].selected == true) {
      situacoes = situacoes + "|" + situacaoCombo[i].value;
    }
  }
  f.situacaoSelecionados.value = situacoes;
  // VENDEDOR
  vendedores = "";
  for (var i = 0; i < vendedor.options.length; i++) {
    if (vendedor[i].selected == true) {
      vendedores = vendedores + "|" + vendedor[i].value;
    }
  }

  f.vendedorSelecionados.value = vendedores;

  // COND PAGAMENTO
  condPagamentos = "";
  for (var i = 0; i < condPag.options.length; i++) {
    if (condPag[i].selected == true) {
      condPagamentos = condPagamentos + "|" + condPag[i].value;
    }
  }

  f.condPagamentoSelecionados.value = condPagamentos;

  //CENTRO DE CUSTO
  centroCustos = "";
  for (var i = 0; i < centroCusto.options.length; i++) {
    if (centroCusto[i].selected == true) {
      centroCustos = centroCustos + "|" + centroCusto[i].value;
    }
  }

  f.centroCustoSelecionados.value = centroCustos;

  // motivo lancamento

  motivos = "";
  for (var i = 0; i < motivo.options.length; i++) {
    if (motivo[i].selected == true) {
      motivos = motivos + "|" + motivo[i].value;
    }
  }

  f.motivosSelecionados.value = motivos;
}

function setaDadosPedido() {
  document.lancamento.desc_cc.value = "";
}

function removerEspaco(string) {
  return string.replace(/^\s+|\s+$/g, "");
}

function submitModalCC(itens) {
  debugger;
  let numLinhas = document.getElementById("datatable").rows.length;
  if (numLinhas <= 1) {
    alert("Aviso! Faça a Pesquisa antes de importar dados.");
    return false;
  }

  tabela = document.getElementById("datatable");
  var produtos = "";
  var produto = "";
  for (i = 1; i < tabela.rows.length; i++) {
    colunas = tabela.rows[i].childNodes;
    var inputs = tabela.rows.item(i).getElementsByTagName("input");
    if (i > 1) {
      produtos = produtos + "|";
      produto = "";
    }
    for (j = 0; j < colunas.length - 1; j++) {
      elementos = colunas[j].childNodes;
      for (l = 0; l < elementos.length; l++) {
        if (elementos.length > 2) {
          produto = produto + "*" + removerEspaco(inputs[0].value);
          l = l + 2;
        } else if (elementos[l].data != "") {
          if (produto != "") {
            produto = produto + "*" + removerEspaco(elementos[l].data);
          } else {
            produto = produto + removerEspaco(elementos[l].data);
          }
        }
      }
    }
    produtos = produtos + produto;
  }

  f = document.lancamento;
  f.form.value = "ped";
  f.form.value = "pedido_venda_telhas";
  f.itensPedidoCC.value = produtos;
  //f.lancItens.value = itens;
  f.submenu.value = "cadastrarItem";
  f.submit();
}

function submitLetraModalCC() {
  debugger;
  if (document.lancamento.desc_cc.value == "") {
    alert("Preencha o campo para a pesquisa.");
    return false;
  }

  var form = $("form[name=lancamento]");

  $.ajax({
    type: "POST",
    url: form.action ? form.action : document.URL,
    data: $(form).serialize(),
    dataType: "text",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Ajax-Request", "true");
    },
    success: function (response) {
      var msgs_modal = $("<div />")
        .append(response)
        .find("#content_msg")
        .html();
      $("#content_msg").html(msgs_modal);
      var result = $("<div />").append(response).find("#datatable").html();
      $("#datatable").html(result);
    },
  });
  return false;
}

function limpaModalCC() {
  f = document.lancamento;
  f.submit();
}

function currencyFormat(num) {
  return num
    .toFixed(2) // always two decimal digits
    .replace(".", ",") // replace decimal point character with ,
    .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // use . as a separator
}

function buscaEmailCliente(id, idCliente){

  debugger;
  document.lancamento.id.value = id;
  document.lancamento.pessoa.value = idCliente;
  var form = $("form[name=lancamento]");

  $.ajax({
    type: "POST",
    url: form.action ? form.action : document.URL,
    data: $(form).serialize(),
    dataType: "text",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Ajax-Request-Enviar-Email", "true");
    },
    success: function (response) {
      debugger;
      var result = $("<div />").append(response).find("#modalEmail").html();
      $("#modalEmail").html(result);
     // $("#cotacao").val(id);
    },
  });
  return false;

}

function enviaEmailPedido(id) {
  
  if(document.lancamento.destinatario.value == ''){
    alert("Preencha o campo 'Para:' ao enviar Email.");
    return false;
  }
  if(document.lancamento.assunto.value == ''){
    alert("Preencha o campo Assunto para enviar Email.");
    return false;
  }
  if(document.lancamento.emailCorpo.value == ''){
    alert("Preencha o campo Corpo para enviar Email.");
    return false;
  }
  document.lancamento.id.value = id;
  document.lancamento.emailBody.value = '';
  document.lancamento.emailBody.value = document.lancamento.emailCorpo.value;
  var form = $("form[name=lancamento]");

  $.ajax({
    type: "POST",
    url: form.action ? form.action : document.URL,
    data: $(form).serialize(),
    dataType: "text",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Ajax-Request-Enviar-Email-Pedido", "true");
    },
    success: function (response) {
      debugger;      

      var msgAlert = $("<div />").append(response).find("#msgAlert").html();
      $("#msgAlert").html(msgAlert);

      $('#modalEmail').modal('hide');      
      
    },
  });
  return false;
}