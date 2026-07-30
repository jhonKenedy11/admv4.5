function abrir(pag) {     
    window.open(
        pag,
        "consulta",
        "toolbar=no,location=no,menubar=no,width=1240,height=900,scrollbars=yes,left="+(window.innerWidth-1240)/2+""
    );
}

function abrirNewTab(pag) {     
    window.open(
        pag,
        "toolbar=no,location=no,menubar=no,width=1240,height=900,scrollbars=yes,left="+(window.innerWidth-1240)/2+""
    );
}

function abrirAcompanhamento(pag) {     
    f = document.lancamento;
    if(f.idCotacao.value !== ''){
        let idCotacao = f.idCotacao.value;
        let idCliente = f.idCliente.value;
        let nomeCliente = f.nomeCliente.value; 
        window.open(
            'index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&dashboard_origem=dashboard_crm&submenu=cadastrar&idPedido='+idCotacao+'&pessoa='+idCliente+'&pessoaNome='+nomeCliente+' ',
            "consulta",
            "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes,left="+(window.innerWidth-950)/2+""
        );
    }else{
         swal.fire({
            title:"Atenção!",
            text:"Selecione uma Cotação!",
            icon:"warning"});
    }
}

function visualizarCalendario(){
    window.open(
        'index.php?mod=crm&form=calendar&submenu=desenha_calendario',
        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes");
}

function finalizarAcompanhamentoPainel(idAcomp) {
    f = document.lancamento;
    if (f.verSomenteInfoDaLoja.value === "" && f.vertodoslancamentos.value === "1") {
        // gestor: todos os lançamentos
    } else if (f.verSomenteInfoDaLoja.value === "1" && f.vertodoslancamentos.value === "") {
        // visão restrita à loja
    } else if (f.verSomenteInfoDaLoja.value === "" && f.vertodoslancamentos.value === "") {
        // vendedor: próprios lançamentos (filtro no backend)
    } else {
        swal.fire({title: "Atenção!", text: "Usuário sem Autorização!", icon: "warning"});
        return;
    }
    var url = f.action ? f.action : document.URL;
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            mod: "crm",
            form: "contas_acompanhamento",
            submenu: "atualizaStatusAcomp",
            opcao: "blank",
            id: idAcomp
        },
        success: function (res) {
            if (res && res.ok === true) {
                f = document.lancamento;
                if (f.idCotacao && String(f.idCotacao.value) !== "") {
                    buscaAcompanhamentos(
                        f.idCotacao.value,
                        f.idCliente ? f.idCliente.value : "",
                        f.nomeCliente ? f.nomeCliente.value : ""
                    );
                }
                if ($('#tab_cotacoes_outros').hasClass('active') || $('#cotacoes-outros-tab').parent().hasClass('active')) {
                    carregarAcompanhamentosPainel(false);
                }
            } else {
                swal.fire({title: "Atenção", text: (res && res.msg) ? res.msg : "Falha na requisição.", icon: "warning"});
            }
        },
        error: function () {
            swal.fire({title: "Atenção", text: "Falha na requisição.", icon: "error"});
        }
    });
}

function editarAcompanhamento(idPed) {
     
    f = document.lancamento;
    let id = idPed;
    if(f.verSomenteInfoDaLoja.value === '' && f.vertodoslancamentos.value === '1'){
        window.open(
            'index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&dashboard_origem=dashboard_crm&submenu=alterar&id='+id+' ',
            "consulta",
            "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes,left="+(window.innerWidth-950)/2+"");
    }else if(f.verSomenteInfoDaLoja.value === '1' && f.vertodoslancamentos.value === ''){
        window.open(
            'index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&dashboard_origem=dashboard_crm&submenu=alterar&id='+id+' ',
            "consulta",
            "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes,left="+(window.innerWidth-950)/2+"");
    }else if(f.verSomenteInfoDaLoja.value === '' && f.vertodoslancamentos.value === ''){
        window.open(
            'index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&dashboard_origem=dashboard_crm&submenu=alterar&id='+id+' ',
            "consulta",
            "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes,left="+(window.innerWidth-950)/2+"");
    }else{
         swal.fire({title:"Atenção!",
            text:"Usuário sem Autorização!",
            icon:"warning"});
    }
}


//AJAX ATUALIZA ACOMPANHAMENTO
function buscaAcompanhamentos(idCotacao, idCliente, nomeCliente) {
    submitLetraParam();
    f = document.lancamento;
    f.idCotacao.value = idCotacao;
    f.idCliente.value = idCliente;
    f.nomeCliente.value = nomeCliente;
    f.id.value = idCotacao;
    f.submenu.value = "buscaAcompanhamentos";
    var form = $("form[name=lancamento]");
    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        success: [atualizaViewAcomp],
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Ajax-Request-Busca-Acompanhamentos", "true");
        },
    });
    return false;
}

function atualizaViewAcomp(response) {
    f = document.lancamento;
    var idLi = f.idCotacao.value;
    var objCot = $('#ulCotacao').children('li');
    //For para percorrer cada li e muda a cor do destaque
    for(i=0; i < objCot.length; i++){
        var liId = objCot[i]['id'] || '';
        if(liId === 'cot-' + idLi || objCot[i]['id'] == idLi){
            $('#' + objCot[i]['id']).toggleClass('destaque');
        }else{
            $('#' + objCot[i]['id']).removeClass('destaque');
        }
    }
    //localiza no restorno se gerou resultado
    var objMov = $("<ul />").append(response).find("#ulAcompanhamento").html();
    //Atualiza a tela
    $("#ulAcompanhamento").html(objMov);
    //Posiciona tela de acordo com scroll realizado
    let scrollDistance = $("#divs").offset().top - $(window).scrollTop();
    if(scrollDistance < -270){
        $('html,body').scrollTop(270);
    }
}

function carregarAcompanhamentosPainel(force) {
    submitLetraParam();
    f = document.lancamento;
    f.submenu.value = "buscaAcompPainel";

    var form = $("form[name=lancamento]");
    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        success: function (response) {
            var $wrap = $("<div />").append(response);
            var hoje = $wrap.find("#ulAcompHoje").html();
            var atras = $wrap.find("#ulAcompAtrasados").html();
            var prox = $wrap.find("#ulAcompProximos").html();
            var sug = $wrap.find("#ulAcompSugestoes").html();

            if (typeof hoje !== "undefined") $("#ulAcompHoje").html(hoje);
            if (typeof atras !== "undefined") $("#ulAcompAtrasados").html(atras);
            if (typeof prox !== "undefined") $("#ulAcompProximos").html(prox);
            if (typeof sug !== "undefined") $("#ulAcompSugestoes").html(sug);

            var bh = $wrap.find("#badgeAcompHoje").first().text();
            var ba = $wrap.find("#badgeAcompAtrasados").first().text();
            var bp = $wrap.find("#badgeAcompProximos").first().text();
            var bs = $wrap.find("#badgeAcompSugestoes").first().text();
            if (bh !== "") $("#badgeAcompHoje").text(bh);
            if (ba !== "") $("#badgeAcompAtrasados").text(ba);
            if (bp !== "") $("#badgeAcompProximos").text(bp);
            if (bs !== "") $("#badgeAcompSugestoes").text(bs);
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Acomp-Painel", "true");
        }
    });
}

$(document).ready(function() {
    $('#cotacoes-outros-tab').on('shown.bs.tab', function () {
        carregarAcompanhamentosPainel(false);
    });
});


function submitLetra() {
     
    f = document.lancamento;
    f.letra.value = "";
    f.submenu.value = "pesquisa";

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

    //MONTA LETRA
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + vendedores + "|" + centroCustos;

    f.submit();
} // fim submit

function salvarMotivoNoPedido(id) {
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
  f.idVendaPerdida.value = id;
  f.submenu.value = "motivoGeral";
  f.submit();
}


function submitLetraParam() {
     
    f = document.lancamento;
    f.letra.value = "";

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

    //MONTA LETRA
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + vendedores + "|" + centroCustos;

    return f.letra.value;
} // fim submit

