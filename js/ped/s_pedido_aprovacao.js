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

function abrirPedidoAprovacao(id, especie, idNatop) {
  var formPedido = "pedido_venda_imp_romaneio";
  var submenu = "";
  var extra = "&opcao=imprimir&parm=" + id;
  if (String(especie).toUpperCase() === "D" && parseInt(idNatop, 10) === 1) {
    formPedido = "pedido_ps";
    submenu = "&submenu=alterar";
    extra = "&id=" + id;
  }
  abrir("index.php?mod=ped&form=" + formPedido + submenu + extra);
}

function salvarPedidoObsDesaprovado(id) {
  f = document.lancamento;
  f.id.value = id;
  f.form.value = "pedido_aprovacao";
  f.submenu.value = "desaprovado";
  f.submit();
}

function salvarPedidoAprovado(id) {
  Swal.fire({
    title: 'Confirmação',
    text: 'Aprovar pedido/cotação Nº ' + id + '?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sim',
    cancelButtonText: 'Não'
  }).then(function (result) {
    if (!result.isConfirmed) {
      return;
    }
    var f = document.lancamento;
    f.id.value = id;
    f.form.value = 'pedido_aprovacao';
    f.submenu.value = 'aprovado';
    f.submit();
  });
}

function pedidoDesaprovado(id) {
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
      var result = $("<div />").append(response).find("#observacao").html();
      $("#observacao").html(result);
      $("#cotacao").val(id);
    },
  });
  return false;
}
