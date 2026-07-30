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


function crmDashNomeClienteFmt(nome) {
    var s = (nome == null ? "" : String(nome)).replace(/^'+|'+$/g, "").trim();
    return s ? ("'" + s + "'") : "";
}


/** Valor para inputs com máscara 99/99/9999 99:99 (igual contas_acompanhamento_cadastro). */
function crmDashDataAcompParaInput(s) {
    if (s == null || String(s).trim() === "") {
        return "";
    }
    var str = String(s).trim();
    if (/^\d{2}\/\d{2}\/\d{4}/.test(str)) {
        return str;
    }
    if (window.moment) {
        var m = moment(str.replace(" ", "T"));
        if (m.isValid()) {
            return m.format("DD/MM/YYYY HH:mm");
        }
    }
    return str;
}

function crmDashBindModalAcompDateMasks() {
    if (!window.jQuery || !$.fn.inputmask) {
        return;
    }
    $("#crmDashDataContato, #crmDashProximoContato").each(function () {
        $(this).inputmask("remove");
        $(this).inputmask("99/99/9999 99:99", { placeholder: "dd/mm/yyyy hh:mm" });
    });
}

/** Filtro local por texto (nome, labels, etc.) em listas do dashboard CRM. */
function crmDashFiltraListaPorCampo($inp, $items) {
    if (!$inp.length || !$items.length) {
        return;
    }
    var q = String($inp.val() || "")
        .toLowerCase()
        .replace(/\s+/g, " ")
        .trim();
    $items.each(function () {
        var txt = $(this)
            .text()
            .toLowerCase()
            .replace(/\s+/g, " ");
        $(this).toggle(q === "" || txt.indexOf(q) !== -1);
    });
}

function crmDashFiltraListaAcompanhamentos() {
    crmDashFiltraListaPorCampo(
        $("#crmDashBuscaAcomp"),
        $("#ulAcompanhamento > li.crm-dash-acomp-item")
    );
}

function crmDashFiltraListaClientes() {
    crmDashFiltraListaPorCampo(
        $("#crmDashBuscaCliente"),
        $("#ulCliente > li.crm-dash-cliente-item")
    );
}

function crmDashMarcaClienteSelecionado(idCliente) {
    var idSel = String(idCliente || "").trim();
    var $itens = $("#ulCliente > li.crm-dash-cliente-item");
    $itens.removeClass("crm-dash-cliente-selected");
    if (!idSel) {
        return;
    }
    $itens.filter(function () {
        return String(this.id) === idSel;
    }).addClass("crm-dash-cliente-selected");
}

function crmDashRetornoBuscaAcompanhamentos(response) {
    var $wrap = $("#acomp").closest("div.col-md-6");
    if ($wrap.length && response && response.success && response.html) {
        $wrap.replaceWith(response.html);
        crmDashFiltraListaAcompanhamentos();
    }
    var f = document.dashboardLancamento;
    if (
        f &&
        f.nomeCliente &&
        response &&
        response.nomeCliente != null &&
        String(response.nomeCliente).trim() !== ""
    ) {
        f.nomeCliente.value = String(response.nomeCliente).trim();
    }
}

function atualizaAcompanhamentosAposCrud() {
    var f = document.dashboardLancamento;
    buscaAcompanhamentos(f.idCliente.value);
}

function crmDashResetModalAcomp() {
    var f = document.getElementById("crmDashAcompForm");
    if (!f) {
        return;
    }
    f.reset();
    if (f.submenu) {
        f.submenu.value = "cadastrar";
    }
    if (f.id) {
        f.id.value = "";
    }
    if (f.pessoa) {
        f.pessoa.value = "";
    }
    if (f.pessoaNome) {
        f.pessoaNome.value = "";
    }
    if (f.vendedorAcomp && f.vendedorAcomp.getAttribute("data-default") != null) {
        f.vendedorAcomp.value = f.vendedorAcomp.getAttribute("data-default");
    }
    $("#crmDashClienteNomeMostra").text("—");
    $("#crmDashAcompSubtitulo").text("");
    $("#modalAcompTitulo").text("Acompanhamento");
    crmDashBindModalAcompDateMasks();
}

window.crmDashPostAcompForm = function (formEl) {
    var $f = $(formEl);
    if ($f.find('input[name="dashboard_origem"]').length) {
        $f.find('input[name="dashboard_origem"]').val("dashboard_crm");
    }
    var postUrl = $f.attr("action") && $f.attr("action").length ? $f.attr("action") : "index.php";
    $("#btnCrmDashSalvarAcomp").prop("disabled", true);
    $.ajax({
        type: "POST",
        url: postUrl,
        data: $f.serialize(),
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Acompanhamento", "true");
        },
        success: function () {
            crmDashResetModalAcomp();
            $("#modalNovoAcompanhamento").modal("hide");
            atualizaAcompanhamentosAposCrud();
        },
        error: function (xhr, textStatus, errorThrown) {
            swal.fire({
                icon: "error",
                title: "Erro!",
                text: "Falha ao salvar."
            });
        },
        complete: function () {
            $("#btnCrmDashSalvarAcomp").prop("disabled", false);
        }
    });
};

function crmDashSalvarAcomp() {
    var f = document.getElementById("crmDashAcompForm");
    if (!f) return;
    if (!f.pessoa || !f.pessoa.value) {
        swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Selecione um cliente na lista para registrar o acompanhamento."
        });
        return;
    }
    if (!f.acao || !f.acao.value) {
        swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Selecione a ação."
        });
        return;
    }
    if (!f.resultContato || String(f.resultContato.value).trim().length < 3) {
        swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Informe o acompanhamento (mín. 3 caracteres)."
        });
        return;
    }
    if (f.submenu.value === "cadastrar") {
        f.submenu.value = "inclui";
    } else if (f.submenu.value === "alterar") {
        f.submenu.value = "altera";
    }
    crmDashPostAcompForm(f);
}


function crmDashAbrirEmailAcomp(idAcompVal) {
    var f = document.getElementById("crmDashAcompForm");
    if (!f) {
        return;
    }

    var idPessoa = f.pessoa ? String(f.pessoa.value || "").trim() : "";
    var nomePessoa = f.pessoaNome ? String(f.pessoaNome.value || "").trim() : "";

    if (!idPessoa && document.dashboardLancamento && document.dashboardLancamento.idCliente) {
        idPessoa = String(document.dashboardLancamento.idCliente.value || "").trim();
    }
    if (!nomePessoa && document.dashboardLancamento && document.dashboardLancamento.nomeCliente) {
        nomePessoa = String(document.dashboardLancamento.nomeCliente.value || "").trim();
    }

    if (!idPessoa) {
        swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Selecione um cliente para abrir o e-mail do acompanhamento."
        });
        return;
    }

    var url = "index.php?mod=crm&form=crm_contas_acompanhamento";
    if (idAcompVal) {
        url += "&submenu=alterar&opcao=imprimir&id=" + encodeURIComponent(idAcompVal);
    } else {
        url += "&submenu=cadastrar&opcao=imprimir&pessoa=" + encodeURIComponent(idPessoa);
    }
    if (nomePessoa) {
        url += "&pessoaNome=" + encodeURIComponent(nomePessoa);
    }
    url += "&dashboard_origem=dashboard_crm";
    url += "#collapse2";
    window.open(url, "_blank");
}

function abrirAcompanhamentoModal() {
    var f = document.dashboardLancamento;
    if (!f.idCliente || !f.idCliente.value) {
        swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Selecione um cliente na lista para criar o acompanhamento."
        });
        return;
    }
    $("#modalAcompTitulo").text("Novo acompanhamento");
    var nomeRaw = f.nomeCliente && f.nomeCliente.value ? String(f.nomeCliente.value).trim() : "";
    if (!nomeRaw && window.jQuery && f.idCliente && f.idCliente.value) {
        var idC = String(f.idCliente.value).trim();
        nomeRaw = $("#ulCliente li")
            .filter(function () {
                return String(this.id) === idC;
            })
            .find("a.title")
            .first()
            .text()
            .replace(/\s+/g, " ")
            .trim();
    }
    var nomeFmt = crmDashNomeClienteFmt(nomeRaw);
    var af = document.getElementById("crmDashAcompForm");
    if (af) {
        af.pessoa.value = f.idCliente.value;
        af.pessoaNome.value = nomeFmt;
        if (af.dataContato) {
            af.dataContato.value = "";
        }
        if (af.proximoContato) {
            af.proximoContato.value = "";
        }
    }
    $("#crmDashPessoa").val(f.idCliente.value);
    $("#crmDashPessoaNome").val(nomeFmt);
    $("#crmDashAcompSubtitulo").text(nomeRaw ? "Cliente: " + nomeRaw : "");
    $("#crmDashClienteNomeMostra").text(nomeRaw || "—");
    $("#modalNovoAcompanhamento").modal("show");
}

function visualizarCalendario(){
    window.open(
        'index.php?mod=crm&form=calendar&submenu=desenha_calendario',
        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=750,scrollbars=yes");
}

function editarAcompanhamento(idPed) {
    $("#modalAcompTitulo").text("Editar acompanhamento");
    $("#modalNovoAcompanhamento").modal("show");
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=contas_acompanhamento&submenu=buscaAcompanhamentoAjax&opcao=blank",
        dataType: "json",
        data: { idAcomp: idPed },
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Acompanhamento", "true");
        },
        success: function (r) {
            if (!r) {
                return;
            }
            function g(k) {
                return r[k] != null ? r[k] : r[k.toLowerCase()];
            }
            var f = document.getElementById("crmDashAcompForm");
            if (!f) {
                return;
            }
            f.submenu.value = "alterar";
            f.id.value = g("ID");
            f.pessoa.value = g("PESSOA");
            f.pessoaNome.value = crmDashNomeClienteFmt(g("NOME_CLIENTE"));
            if (f.vendedorAcomp && g("USRVENDEDOR")) {
                f.vendedorAcomp.value = g("USRVENDEDOR");
            }
            if (f.acao) {
                f.acao.value = g("ATIVIDADE");
            }
            if (f.status) {
                f.status.value = g("STATUS");
            }
            if (f.dataContato) {
                f.dataContato.value = crmDashDataAcompParaInput(g("DATA"));
            }
            if (f.idPedido) {
                f.idPedido.value = g("PEDIDO_ID") || "";
            }
            if (f.resultContato) {
                f.resultContato.value = g("RESULTADO") || "";
            }
            if (f.proximoContato) {
                f.proximoContato.value = crmDashDataAcompParaInput(g("LIGARDIA"));
            }
            if (f.veiculo) {
                f.veiculo.value = g("VEICULO") || "";
            }
            if (f.origem) {
                f.origem.value = g("ORIGEM") || "";
            }
            if (f.destino) {
                f.destino.value = g("DESTINO") || "";
            }
            if (f.km) {
                f.km.value = g("KM") || "";
            }
            var nome = g("NOME_CLIENTE");
            $("#crmDashClienteNomeMostra").text(
                nome ? String(nome).replace(/^'+|'+$/g, "").trim() : "—"
            );
            crmDashBindModalAcompDateMasks();
        },
        error: function () {
            swal.fire({
                icon: "error",
                title: "Erro!",
                text: "Falha ao carregar (AJAX)."
            });
            $("#modalNovoAcompanhamento").modal("hide");
        }
    });
}


function buscaAcompanhamentos(idcliente) {
    var f = document.dashboardLancamento;
    f.idCliente.value = idcliente;
    crmDashMarcaClienteSelecionado(idcliente);
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_acompanhamento_dashboard&opcao=blank",
        dataType: "json",
        data: {
            submenu: "buscaAcompanhamentos",
            idCliente: idcliente,
        },
        success: function (response) {
            if (response && response.error) {
                swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: String(response.error)
                });
                return;
            }
            crmDashRetornoBuscaAcompanhamentos(response);
        },
        error: function () {
            swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Não foi possível carregar os acompanhamentos."
            });
        }
    });
}

/** Lista geral: diário (período = hoje no servidor), período filtrado, concluídos — mesmo destino na lateral. */
function buscaAcompanhamentosMetas(tipo) {
    var f = document.dashboardLancamento;
    f.parametro.value = tipo;
    $.ajax({
        type: "POST",
        url: "index.php?mod=crm&form=crm_acompanhamento_dashboard&opcao=blank",
        dataType: "json",
        data: {
            submenu: "buscaAcompanhamentos",
            dataIni: f.dataIni ? f.dataIni.value : "",
            dataFim: f.dataFim ? f.dataFim.value : "",
            parametro: f.parametro ? f.parametro.value : ""
        },
        success: function (response) {
            if (response && response.error) {
                swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: String(response.error)
                });
                return;
            }
            crmDashRetornoBuscaAcompanhamentos(response);
        },
        error: function () {
            swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Não foi possível carregar os acompanhamentos."
            });
        }
    });
}

function submitLetra() {
    f = document.dashboardLancamento;
    submenu = f.submenu.value;
    f.submit();
} // fim submit

$(function () {
    $("#modalNovoAcompanhamento").on("shown.bs.modal", crmDashBindModalAcompDateMasks);
    $("#modalNovoAcompanhamento").on("hidden.bs.modal", crmDashResetModalAcomp);
    $(document).on("input", "#crmDashBuscaAcomp", crmDashFiltraListaAcompanhamentos);
    $(document).on("input", "#crmDashBuscaCliente", crmDashFiltraListaClientes);
});
