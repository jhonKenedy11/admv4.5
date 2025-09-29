function submitAtualNaturezaOperacao(id) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  //f.opcao.value = '';
  f.id.value = id;
  f.submenu.value = "cadastrarPedido";
  f.submit();
} // fim submit

function submitAtualPedidoCondPGAcrescentar(id, numParcelaAdd) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  //f.opcao.value = '';
  f.id.value = id;
  if (numParcelaAdd + 1 < 0) {
    f.numParcelaAdd.value = 0;
  } else {
    f.numParcelaAdd.value = numParcelaAdd + 1;
  }

  f.submenu.value = "addParcelaCotacao";
  f.submit();
} // fim submit

function addParcelas(id, numParcelaAdd) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  //f.opcao.value = '';
  f.id.value = id;
  if (numParcelaAdd + 1 < 0) {
    f.numParcelaAdd.value = 0;
  } else {
    f.numParcelaAdd.value = numParcelaAdd + 1;
  }

  f.submenu.value = "addParcelaAlteraPED";
  f.submit();
} // fim submit

function submitAtualPedidoCondPG(id) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  //f.opcao.value = '';
  f.id.value = id;
  f.submenu.value = "cadastrarPedido";
  f.submit();
} // fim submit

function submitNFEEnviar(id) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  if (confirm("Deseja realmente INCLUIR NFe") == true) {
    f.submenu.value = "NFEEnviar";
    f.id.value = id;
    f.submit();
  } else {
    f.submenu.value = "";
    return false;
  } // else
} // submitAlterar

function submitAtualPedido(id) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";
  //f.opcao.value = '';
  f.id.value = id;
  f.submenu.value = "cadastrarCOTPed";
  f.submit();
} // fim submit

function submitCadastraPedido(id, integrafin) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";

  let totalCredito = parseFloat(f.totalCredito.value.replace(".", "").replace(",", "."));
  let credito = parseFloat(f.credito.value.replace(".", "").replace(",", "."))
  let totalPed = parseFloat(f.total.value.replace(".", "").replace(",", "."));
  if(credito > totalCredito){
    alert("Valor do crédito maior que o máximo de R$"+f.totalCredito.value);
    f.credito.focus();
    return false;
  }
  if(credito > totalPed){
    alert("Valor do crédito maior que o TOTAL do PEDIDO");
    f.credito.focus();
    return false;
  }

  var rows = document
    .getElementById("datatable-buttons-1")
    .getElementsByTagName("tr");

  var $dadosFinanceiros = "";
  var $totalFinanceiro = 0;

  for (row = 1; row < rows.length; row++) {
    var cells = rows[row].getElementsByTagName("td");
    var field0 = cells[0].childNodes[0].data;
    var field1 = cells[1].childNodes[1].value;
    var field2 = cells[2].childNodes[1].value;
    var field3 = cells[3].childNodes[1].value;
    var field4 = cells[4].childNodes[1].value;
    var field5 = cells[5].childNodes[1].value;
    var field6 = cells[6].childNodes[1].value;
    $dadosFinanceiros =
      $dadosFinanceiros +
      "|" +
      field0 +
      "*" +
      field1 +
      "*" +
      field2 +
      "*" +
      field3 +
      "*" +
      field4 +
      "*" +
      field5 +
      "*" +
      field6;

    var $moeda = field2.toString();

    $moeda = $moeda.replace(".", "");

    $moeda = $moeda.replace(",", ".");

    $moeda = parseFloat($moeda);

    $totalFinanceiro = $totalFinanceiro + $moeda;
  }

  $totalFinanceiro = $totalFinanceiro.toFixed(2);

  f.dadosFinanceiros.value = $dadosFinanceiros;

  var $total = f.total.value;

  $total = $total.replace(".", "");

  $total = $total.replace(",", ".");

  $total = parseFloat($total);

  if (integrafin == "N") {
    $totalFinanceiro = $total;
  }

  if ($total != $totalFinanceiro) {
    alert("Soma total das parcelas, não é igual ao total da fatura!");
    return false;
  } else {
    if (confirm("Deseja realmente INCLUIR FATURAMENTO") == true) {
      f.submenu.value = "cadastrarPed";
      f.id.value = id;
    } else {
      f.submenu.value = "";
      return false;
    } // else
    f.submit();
  }
}
function submitIncluirNf(id, integrafin) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";

  let totalCredito = parseFloat(f.totalCredito.value.replace(".", "").replace(",", "."));
  let credito = parseFloat(f.credito.value.replace(".", "").replace(",", "."))
  let totalPed = parseFloat(f.total.value.replace(".", "").replace(",", "."));
  if(credito > totalCredito){
    alert("Valor do crédito maior que o máximo de R$"+f.totalCredito.value);
    f.credito.focus();
    return false;
  }
  if(credito > totalPed){
    alert("Valor do crédito maior que o TOTAL do PEDIDO");
    f.credito.focus();
    return false;
  }

  var rows = document
    .getElementById("datatable-buttons-1")
    .getElementsByTagName("tr");

  var $dadosFinanceiros = "";
  var $totalFinanceiro = 0;

  for (row = 1; row < rows.length; row++) {
    var cells = rows[row].getElementsByTagName("td");
    var field0 = cells[0].childNodes[0].data;
    var field1 = cells[1].childNodes[1].value;
    var field2 = cells[2].childNodes[1].value;
    var field3 = cells[3].childNodes[1].value;
    var field4 = cells[4].childNodes[1].value;
    var field5 = cells[5].childNodes[1].value;
    var field6 = cells[6].childNodes[1].value;
    $dadosFinanceiros =
      $dadosFinanceiros +
      "|" +
      field0 +
      "*" +
      field1 +
      "*" +
      field2 +
      "*" +
      field3 +
      "*" +
      field4 +
      "*" +
      field5 +
      "*" +
      field6;

    var $moeda = field2.toString();

    $moeda = $moeda.replace(".", "");

    $moeda = $moeda.replace(",", ".");

    $moeda = parseFloat($moeda);

    $totalFinanceiro = $totalFinanceiro + $moeda;
  }

  $totalFinanceiro = $totalFinanceiro.toFixed(2);

  f.dadosFinanceiros.value = $dadosFinanceiros;

  var $total = f.total.value;

  $total = $total.replace(".", "");

  $total = $total.replace(",", ".");

  $total = parseFloat($total);

  if (integrafin == "N") {
    $totalFinanceiro = $total;
  }

  if ($total != $totalFinanceiro) {
    alert("Soma total das parcelas, não é igual ao total da fatura!");
    return false;
  } else {
    if (confirm("Deseja realmente INCLUIR FATURAMENTO") == true) {
      f.submenu.value = "cadastrarPedAlteracao";
      f.id.value = id;
    } else {
      f.submenu.value = "";
      return false;
    }
    f.submit();
  }
} // submitAlterar

function submitAtual(id) {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";
  //f.opcao.value = '';
  f.id.value = id;
  f.submenu.value = "cadastrar";
  f.submit();
} // fim submit

function submitCadastro(id) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";
  //f.opcao.value = '';
  f.id.value = id;
  f.submenu.value = "cadastrar";
  f.submit();
} // fim submit

function submitVoltar(formulario) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  f.submenu.value = "";
  f.submit();
} // fim submitVoltar

function submitVoltarPag(formulario) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_telhas";
  f.submenu.value = "alterar";
  f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";
  f.opcao.value = formulario;
  if (confirm("Deseja realmente " + f.submenu.value + " este item") == true) {
    f.submenu.value = "incluir";
  } else {
    f.submenu.value = "";
  } // else
  f.submit();
} // fim submitConfirmar

function submitCadastraNf(id) {
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_venda_nf";
  if (confirm("Deseja realmente INCLUIR NFe e FATURAMENTO") == true) {
    f.submenu.value = "cadastraNf";
    f.id.value = id;
  } else {
    f.submenu.value = "";
  } // else
  f.submit();
} // submitAlterar

function abrir(pag) {
  window.open(
    pag,
    "consulta",
    "toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes"
  );
}
