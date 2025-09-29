// function checkbox periodo
function myFunction() {
  var checkbox = document.getElementById("checkPeriodo");
  var periodo = document.getElementById("dataConsulta");
  if (checkbox.checked == true) {
    periodo.disabled = false;
    checkbox.value = 1;
  } else {
    periodo.disabled = true;
    checkbox.value = 0;
  }
}

function submitLetra() {
  debugger;
  f = document.lancamento;
  f.mod.value = "ped";
  f.form.value = "pedido_aprovacao";
  if (f.checkPeriodo.checked == true) {
    f.checkPeriodo.value = 1;
    f.letra.value =
      f.vendedor.value +
      "|" +
      f.pessoa.value +
      "|" +
      f.codCotacao.value +
      "|" +
      f.ccusto.value +
      "|" +
      f.dataIni.value +
      "|" +
      f.dataFim.value;
  } else {
    f.checkPeriodo.value = 0;
    f.letra.value =
      f.vendedor.value +
      "|" +
      f.pessoa.value +
      "|" +
      f.codCotacao.value +
      "|" +
      f.ccusto.value +
      "||";
  }

  f.submit();
} // submitLetra

function abrir(pag) {
  window.open(
    pag,
    "consulta",
    "toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes"
  );
}

function salvarPedidoObsDesaprovado(id) {
  f = document.lancamento;
  f.id.value = id;
  f.form.value = "pedido_aprovacao";
  f.submenu.value = "desaprovado";
  f.submit();
}

function submitCadastrarPedido(id, usr) {
  debugger;
  f = document.lancamento;
  f.form.value = "pedido_venda_telhas";
  f.submenu.value = "cadastrarPedido";
  f.id.value = id;
  f.usrAprovacao.value = usr;
  f.submit();
}

function salvarPedidoAprovado(id) {
  f = document.lancamento;
  f.id.value = id;
  f.form.value = "pedido_aprovacao";
  f.submenu.value = "aprovado";
  f.submit();
}

function pedidoDesaprovado(id) {
  debugger;
  document.lancamento.id.value = id;
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
      debugger;
      var result = $("<div />").append(response).find("#observacao").html();
      $("#observacao").html(result);
      $("#cotacao").val(id);
    },
  });
  return false;
}
