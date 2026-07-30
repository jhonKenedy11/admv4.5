function bloquearRecarregamento(event) {
    // Verifica se a tecla pressionada é F5 ou se a combinação Ctrl+R foi usada
    if ((event.key === 'F5' || (event.ctrlKey && event.key === 'r')) || (event.key === 'F5' || (event.ctrlKey && event.key === 'R'))) {
      event.preventDefault();
      Swal.fire({
        title: "Atenção!",
        text: "Recarregamento da página desativado!",
        icon: "warning"
      });
    }
}

// Adiciona um ouvinte de eventos para a tecla pressionada
document.addEventListener('keydown', bloquearRecarregamento);

//funcao para criar as th da tabela de validacao ao visualizar o xml
window.onload = function() {
    let novaLinha = '';
    const tabelaDivergencia = document.getElementById('tableDisagreements');

    if(tabelaDivergencia){
        //se existir essa td é sinal que a nota fiscal foi emitida e nao podemos gerar as th
        let existeNotaFiscal = document.getElementsByName('existeNotaFiscal');
        
        if(!existeNotaFiscal){
        
            //verifica se existe botao para adicionar os titulos das colunas
            const btnForn = document.getElementById('submitFornecedor');
            if(!btnForn){
                novaLinha = tabelaDivergencia.insertRow(1);
            }else{
                novaLinha = tabelaDivergencia.insertRow(2);
            }
        
            //insere novas colunas
            const novaCelula1 = novaLinha.insertCell();
            novaCelula1.textContent = 'Desccrição';
        
            const novaCelula2 = novaLinha.insertCell();
            novaCelula2.textContent = 'Código';
        
            const novaCelula3 = novaLinha.insertCell();
            novaCelula3.textContent = 'Código barras';
        
            const novaCelula4 = novaLinha.insertCell();
            novaCelula4.textContent = 'Ação';
            
            //aplica o css de estilizacao nas colunas
            novaCelula1.style.fontWeight = 'bold';
            novaCelula2.style.fontWeight = 'bold';
            novaCelula3.style.fontWeight = 'bold';
            novaCelula4.style.fontWeight = 'bold';
        
            novaCelula1.style.fontSize = '14px';
            novaCelula2.style.fontSize = '14px';
            novaCelula3.style.fontSize = '14px';
            novaCelula4.style.fontSize = '14px';
            
            novaCelula2.style.textAlign = 'center';
            novaCelula3.style.textAlign = 'center';
            novaCelula4.style.textAlign = 'center';
        }
    }
    
    var xmlToken = document.getElementById('xml_token').value.trim();
    if (xmlToken !== '') {
        var chevron = document.getElementsByName("btnCollapse")[0];
        if (chevron) {
            chevron.click();
        }
    }

    var botaoCadastrar = document.getElementById('bnt_cadastrar');
    var tableDisagreements = document.getElementById('tableDisagreements');

    if (tableDisagreements) {
        if (botaoCadastrar && (botaoCadastrar.style.display === 'none' || botaoCadastrar.style.display === '')) {
            botaoCadastrar.style.display = 'none';
        }
    } else {
        if (botaoCadastrar) {
            if (xmlToken !== '' && botaoCadastrar.style.display === '') {
                botaoCadastrar.style.display = 'block';
            } else {
                botaoCadastrar.style.display = 'none';
            }
        }
    }
    

}

function submitCobranca() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'cobranca';
    f.submit();
}

// desenha Cadastro
function submitGerarFinanceiro() {
     
    f = document.lancamento;
    f.mod.value = "est";
    f.form.value = "nota_xml_importa";

    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    condPagamentosDesc = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
                condPagamentosDesc = condPgto[i].text;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }

    f.letra.value = f.numero.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        centroCustos + "|" + generos + "|" + condPagamentos;


    var rows = document.getElementById("datatable-buttons-1").getElementsByTagName("tr");

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
        

        // OLD
        // var $moeda = (field2).toString();
        // //$moeda = $moeda.replace(".", "");
        // $moeda = $moeda.replace(",", ".");
        // $moeda = parseFloat($moeda);

        //NEW 31-AGOSTO-2023
        var $moeda = formatarNumero(field2);

        $totalFinanceiro = $totalFinanceiro + $moeda;

        $dadosFinanceiros = $dadosFinanceiros + "|" + field0 + "*" +
            field1 + "*" + $moeda + "*" + field3 + "*" +
            field4 + "*" + field5 + "*" + field6;

    }

    $totalFinanceiro = $totalFinanceiro.toFixed(2);

    f.dadosFinanceiros.value = $dadosFinanceiros;

    var $total = f.total.value;

    //$total = $total.replace(".", "");

    $total = $total.replace(",", ".");

    $total = parseFloat($total);

    if ($total != $totalFinanceiro) {
        alert('Soma total das parcelas, não é igual ao total da fatura!');
    } else {
        if (confirm('Deseja realmente INCLUIR FATURAMENTO') == true) {
            f.submenu.value = 'gerarfinanceiro';
        }
        else {
            f.submenu.value = '';
        }
        f.submit();
    }

}

function formatarNumero(numero) {
    return parseFloat(numero.replace('.', '').replace(',', '.'));
}

  
function submitAtualPedidoCondPG(adicionar, numParcelaAdd) {
     
    f = document.lancamento;
    if (adicionar == "S") {
        if ((numParcelaAdd + 1) < 0) {
            f.numParcelaAdd.value = 0;
        } else {
            f.numParcelaAdd.value = numParcelaAdd + 1;
        }
    } else {
        f.numParcelaAdd.value = 0;
    }

    //NATURAZA DE OPERACAO
    first = true;
    naturaDeOperacoes = '';
    for (var i = 0; i < idNatop.options.length; i++) {
        if (idNatop[i].selected == true) {
            if (first == true) {
                first = false;
                naturaDeOperacoes = idNatop[i].value;
            }
            else naturaDeOperacoes = naturaDeOperacoes + "," + idNatop[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }


    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }
    f.letra.value = f.numero.value + "|" + f.data.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        naturaDeOperacoes + "|" + condPagamentos + "|" + centroCustos + "|" + generos;

    f.submenu.value = 'condpg';
    f.submit();
}

function submitConfirmar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'enviar';
    f.submit();
} // fim submitVoltar

function submitVoltar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitVoltarFinanceiro() {
    Swal.fire({
        title: "Atenção!",
        text: "Nota fiscal já processada, deseja cancelar o financeiro e voltar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then(function(result) {
        if (result && result.isConfirmed) {
            var f = document.lancamento;
            if (f) {
                f.opcao.value = '';
                f.submenu.value = '';
                f.submit();
            }
        }
    });
} // fim submitVoltarFinanceiro

// mostra Cadastro
function submitPesquisa() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'pesquisa';
    f.submit();
}

// mostra Nota Fiscal
function submitVisualizar() {
    var f = document.upload;
    var inputFile = document.getElementById('input-file');

    // XML já enviado (token na sessão): reexibe a tela sem exigir novo arquivo no input
    if (document.getElementById('xml_token').value.trim() !== '') {
        f.opcao.value = '';
        f.submenu.value = 'mostra';
        f.submit();
        return false;
    }

    if (!inputFile || !inputFile.files || inputFile.files.length === 0) {
        Swal.fire({
            title: "Atenção!",
            text: "Insira um arquivo xml para visualizar!",
            icon: "warning"
        });
        return false;
    }

    var fd = new FormData();
    fd.append('file', inputFile.files[0]);
    fd.append('submenu', 'uploadXmlAjax');
    fd.append('mod', 'est');
    fd.append('form', 'nota_xml_importa');
    fd.append('opcao', 'blank');

    $.ajax({
        type: 'POST',
        url: f.action + (f.action.indexOf('?') >= 0 ? '&' : '?') + 'mod=est&form=nota_xml_importa&submenu=uploadXmlAjax&opcao=blank',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function () {
            Swal.fire({
                title: 'Aguarde',
                text: 'Enviando XML...',
                allowOutsideClick: false,
                didOpen: function () { Swal.showLoading(); }
            });
        },
        success: function (ret) {
            Swal.close();
            if (ret && ret.success && ret.token) {
                document.getElementById('xml_token').value = ret.token;
                document.getElementById('xml_file_name').value = inputFile.files[0].name;
                var resumoEl = document.getElementById('xml-resumo');
                if (resumoEl && ret.resumo) {
                    resumoEl.style.display = 'block';
                    resumoEl.innerHTML = '<strong>' + (ret.resumo.emitente || '') + '</strong> — NF '
                        + (ret.resumo.numero || '') + '/' + (ret.resumo.serie || '')
                        + ' (' + inputFile.files[0].name + ')';
                }
                f.opcao.value = '';
                f.submenu.value = 'mostra';
                f.submit();
            } else {
                Swal.fire({
                    title: 'Atenção!',
                    text: (ret && ret.message) ? ret.message : 'Falha ao carregar XML.',
                    icon: 'warning'
                });
            }
        },
        error: function () {
            Swal.close();
            Swal.fire({
                title: 'Erro',
                text: 'Falha ao enviar o arquivo XML.',
                icon: 'error'
            });
        }
    });
    return false;
}



// cadastrar Nota Fiscal
function submitCadastrar() {
     
    f = document.upload;

    if (f.submenu.value !== 'entradaManifesto' && document.getElementById('xml_token').value.trim() === '') {
        Swal.fire({
            title: 'Atenção!',
            text: 'Xml não localizado. Faça o upload novamente.',
            icon: 'warning'
        });
        return false;
    }

    if(f.submenu.value === 'entradaManifesto'){
        f.param.value = 'entradaManifesto';
    }

    // A tabela de itens (OS por linha) é inserida via AJAX fora do form; copia os idOsItem_* para o form
    var existing = f.querySelectorAll('input[name^="idOsItem_"]');
    for (var e = 0; e < existing.length; e++) existing[e].remove();
    var inputs = document.querySelectorAll('input[name^="idOsItem_"]');
    for (var i = 0; i < inputs.length; i++) {
        var hid = document.createElement('input');
        hid.type = 'hidden';
        hid.name = inputs[i].getAttribute('name');
        hid.value = (inputs[i].value || '').trim();
        f.appendChild(hid);
    }

    f.opcao.value = '';
    f.submenu.value = 'cadastrar';
    f.submit();
}

// confere Fornecedor / Produtos
function submitConfere() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'conferir';
    f.submit();
}

function abrir(pag, xml) {

    window.open("../../temp/notafiscalxml.php?xml=" + xml, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=500,scrollbars=yes');
}

//function insertConta(url, windowoption, name, params)
function agendarRevalidarAoFecharPopup(win) {
    if (!win) {
        return;
    }
    var timer = setInterval(function () {
        if (win.closed) {
            clearInterval(timer);
            if (document.getElementById('xml_token').value.trim() !== '') {
                submitValidar();
            }
        }
    }, 400);
}

function submitInsertJson(params) {
     
    //add esse parametro para condicionar o novo form
    params.conta.push({ campo: 'form_old', valor: 'produtoPesquisarNfe' });

    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        if (params.conta[i].campo == 'submenu') input.value = params.conta[i].valor = 'cadastrar';
        else input.value = params.conta[i].valor;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    var win = window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
    agendarRevalidarAoFecharPopup(win);
}

function submitSearchJson(params) {
    
    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //new param search product
    var parametroManual = document.createElement('input');
    parametroManual.type = 'hidden';
    parametroManual.name = 'param';
    parametroManual.value = 'pesquisaProdutoImportaXml';
    form.appendChild(parametroManual);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;

        if (params.conta[i].campo == 'submenu'){ 
            input.value = 'pesquisar'; 
        }
        else if (params.conta[i].campo == 'opcao') {
            input.value = 'pesquisarnfe';
        }
        else { 
            input.value = params.conta[i].valor; 
        }
        form.appendChild(input);
    }

    document.body.appendChild(form);
    var win = window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();  

    document.body.removeChild(form);
    agendarRevalidarAoFecharPopup(win);
}

var bindEquivalentContext = null;

function buscarEquivalentesModal() {
    if (!bindEquivalentContext) {
        Swal.fire({ title: "Atenção!", text: "Contexto de vinculação não localizado.", icon: "warning" });
        return;
    }

    var termo = ($("#modalEquivTermo").val() || '').toString().trim();
    if (!termo) {
        termo = bindEquivalentContext.codigoXml || bindEquivalentContext.descricaoXml;
        $("#modalEquivTermo").val(termo);
    }

    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=nota_xml_importa&submenu=buscarEquivalenteAjax&opcao=blank",
        dataType: "json",
        data: {
            codigoXml: bindEquivalentContext.codigoXml,
            descricaoXml: bindEquivalentContext.descricaoXml,
            termo: termo
        },
        success: function (response) {
            if (response && response.success) {
                $("#modalEquivResultados").html(response.html || "");
                atualizarLinhaSelecionadaEquiv();
            } else {
                $("#modalEquivResultados").html("<tr><td colspan='5' style='padding:8px; text-align:left;'>Nenhum produto encontrado.</td></tr>");
            }
        },
        error: function () {
            Swal.fire({ title: "Erro", text: "Falha ao buscar produtos equivalentes.", icon: "error" });
        }
    });
}

function atualizarLinhaSelecionadaEquiv() {
    $("#modalEquivResultados tr.equiv-row").removeClass("equiv-row-selected");
    var selecionado = $("#modalEquivResultados input[name='produto_equiv_sel']:checked");
    if (selecionado.length) {
        var linha = selecionado.closest("tr");
        linha.addClass("equiv-row-selected");
        var textoSelecionado = $.trim(linha.text()).replace(/\s+/g, " ");
        $("#modalEquivSelecionadoInfo").val(textoSelecionado);
    } else {
        $("#modalEquivSelecionadoInfo").val("Nenhum item selecionado");
    }
}

function setLinhaVinculoEquivalenteLoading(loading) {
    if (!bindEquivalentContext || !bindEquivalentContext.sourceButton) {
        return;
    }

    var botao = $(bindEquivalentContext.sourceButton);
    var linha = botao.closest("tr");
    var celula = botao.closest("td");

    if (loading) {
        bindEquivalentContext.sourceButtonValue = botao.val();
        botao.prop("disabled", true).val("VINCULANDO...");
        linha.css("opacity", "0.65");

        if (!celula.find(".vinculo-equivalente-loading").length) {
            celula.append("<span class='vinculo-equivalente-loading' style='margin-left:8px; color:#337ab7; font-weight:bold;'><span class='glyphicon glyphicon-refresh glyphicon-spin' aria-hidden='true'></span> Vinculando produto...</span>");
        }
    } else {
        botao.prop("disabled", false).val(bindEquivalentContext.sourceButtonValue || "VINCULAR");
        linha.css("opacity", "");
        celula.find(".vinculo-equivalente-loading").remove();
    }
}

function confirmarVinculoEquivalenteModal() {
    if (!bindEquivalentContext) {
        Swal.fire({ title: "Atenção!", text: "Contexto de vinculação não localizado.", icon: "warning" });
        return;
    }

    var selected = document.querySelector("input[name='produto_equiv_sel']:checked");
    if (!selected || !selected.value) {
        Swal.fire({ title: "Atenção!", text: "Selecione um produto para vincular.", icon: "warning" });
        return;
    }

    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=nota_xml_importa&submenu=vincularEquivalenteAjax&opcao=blank",
        dataType: "json",
        data: {
            idProduto: selected.value,
            pessoa: bindEquivalentContext.pessoa,
            codigoXml: bindEquivalentContext.codigoXml
        },
        beforeSend: function () {
            setLinhaVinculoEquivalenteLoading(true);
        },
        success: function (ret) {
            if (ret && ret.success) {
                $("#modalVincularEquivalente").modal("hide");
                Swal.fire({
                    title: "Sucesso",
                    text: ret.message || "Equivalência vinculada com sucesso.",
                    icon: "success"
                }).then(function () {
                    submitValidar();
                });
            } else {
                Swal.fire({
                    title: "Atenção!",
                    text: (ret && ret.message) ? ret.message : "Não foi possível vincular equivalência.",
                    icon: "warning"
                });
                setLinhaVinculoEquivalenteLoading(false);
            }
        },
        error: function () {
            setLinhaVinculoEquivalenteLoading(false);
            Swal.fire({ title: "Erro", text: "Falha ao vincular equivalência.", icon: "error" });
        }
    });
}

function submitBindEquivalent(params) {
    if (!params.conta.length) {
        Swal.fire({ title: "Atenção!", text: "Dados do item não localizados.", icon: "warning" });
        return;
    }

    var pessoa = 0;
    var codigoXml = '';
    var descricaoXml = '';
    var sourceButton = document.activeElement;

    if (!sourceButton || sourceButton.name !== 'button_vincular') {
        sourceButton = null;
    }

    for (var i = 0; i < params.conta.length; i++) {
        var campo = params.conta[i].campo;
        var valor = params.conta[i].valor;

        if (campo === 'pessoa') {
            pessoa = valor || 0;
        } else if (campo === 'codFabricante') {
            codigoXml = valor || '';
        } else if (campo === 'produtoNome') {
            descricaoXml = valor || '';
        }
    }

    codigoXml = codigoXml.toString().trim();
    descricaoXml = descricaoXml.toString().trim();

    if (!codigoXml && !descricaoXml) {
        Swal.fire({ title: "Atenção!", text: "Código/descrição do item não encontrados.", icon: "warning" });
        return;
    }

    bindEquivalentContext = {
        pessoa: pessoa,
        codigoXml: codigoXml,
        descricaoXml: descricaoXml,
        sourceButton: sourceButton || null
    };

    $("#modalOrigemCodigoXml").text(codigoXml || "-");
    $("#modalOrigemDescricaoXml").text(descricaoXml || "-");
    $("#modalEquivTermo").val(codigoXml || descricaoXml);
    $("#modalEquivSelecionadoInfo").val("Nenhum item selecionado");
    $("#modalEquivResultados").html("<tr><td colspan='5' style='padding:8px; text-align:left;'>Buscando produtos...</td></tr>");

    $("#modalVincularEquivalente").modal("show");
    buscarEquivalentesModal();
}

$(document).delegate("#btnBuscarEquivModal", "click", function () {
    buscarEquivalentesModal();
});

$(document).delegate("#btnConfirmarEquivModal", "click", function () {
    confirmarVinculoEquivalenteModal();
});

$(document).delegate("#modalEquivTermo", "keypress", function (e) {
    if (e.which === 13) {
        e.preventDefault();
        buscarEquivalentesModal();
    }
});

$(document).delegate("#modalVincularEquivalente", "hidden.bs.modal", function () {
    bindEquivalentContext = null;
    $("#modalEquivSelecionadoInfo").val("Nenhum item selecionado");
    $("#modalEquivResultados").html("<tr><td colspan='5' style='padding:8px; text-align:left;'>Informe um filtro e clique em buscar.</td></tr>");
});

$(document).delegate("#modalEquivResultados input[name='produto_equiv_sel']", "change", function () {
    atualizarLinhaSelecionadaEquiv();
});

$(document).delegate("#modalEquivResultados tr.equiv-row", "click", function (e) {
    if ($(e.target).is("input[name='produto_equiv_sel']")) {
        return;
    }

    var radio = $(this).find("input[name='produto_equiv_sel']");
    if (radio.length) {
        $("#modalEquivResultados input[name='produto_equiv_sel']").each(function () {
            this.checked = false;
        });
        radio.each(function () {
            this.checked = true;
        });
        atualizarLinhaSelecionadaEquiv();
    }
});

// Atualização de código do produto no XML (1 request por item, em paralelo)
var codProdXmlRequests = {};

function setEstadoCodProd(input, estado) {
    if (!input) {
        return;
    }

    var $input = $(input);
    var $row = $input.closest('tr');

    $input.removeClass('cod-prod-atualizando cod-prod-sucesso cod-prod-erro').prop('disabled', false);
    $row.removeClass('cod-prod-linha-atualizando');

    if (estado === 'loading') {
        $input.addClass('cod-prod-atualizando').prop('disabled', true);
        $row.addClass('cod-prod-linha-atualizando');
    } else if (estado === 'success') {
        $input.addClass('cod-prod-sucesso').attr('data-cod-atual', $.trim($input.val()));
        setTimeout(function () { $input.removeClass('cod-prod-sucesso'); }, 1200);
    } else if (estado === 'error') {
        $input.addClass('cod-prod-erro');
    }
}

function verificarBotaoCadastrarXml() {
    var btn = document.getElementById('bnt_cadastrar');
    if (!btn) {
        return;
    }

    var bloqueado = $('#existeNotaFiscal, #submitFornecedor').length
        || $('#tableDisagreements tr.divergencia-produto, #tableDisagreements h4').length
        || $('tr.linha-item-xml[data-produto-ok!="1"]').length;

    btn.style.display = bloqueado ? 'none' : 'block';
    if (!bloqueado) {
        $('#tableDisagreements').remove();
    }
}

function atualizarCodProdXml(input) {
    var $input = $(input);
    var codigoNovo = $.trim($input.val());
    var codigoXml = $.trim($input.attr('data-cod-xml') || '');
    var codSalvo = $.trim($input.attr('data-cod-atual') || '');

    if (!codigoNovo || !codigoXml || codigoNovo === codSalvo) {
        return;
    }

    var token = $.trim($('#xml_token').val() || '');
    if (!token) {
        Swal.fire({ title: 'Atenção!', text: 'Xml não localizado. Faça o upload novamente.', icon: 'warning' });
        return;
    }

    if (codProdXmlRequests[codigoXml] && codProdXmlRequests[codigoXml].readyState !== 4) {
        codProdXmlRequests[codigoXml].abort();
    }

    setEstadoCodProd(input, 'loading');

    var f = document.upload;
    codProdXmlRequests[codigoXml] = $.ajax({
        type: 'POST',
        url: f.action + (f.action.indexOf('?') >= 0 ? '&' : '?') + 'mod=est&form=nota_xml_importa&submenu=atualizarXmlAjax&opcao=blank',
        dataType: 'json',
        data: {
            xml_token: token,
            codigoXml: codigoXml,
            codigoNovo: codigoNovo,
            submenu: 'atualizarXmlAjax'
        },
        success: function (ret) {
            if (!ret || !ret.success) {
                setEstadoCodProd(input, 'error');
                Swal.fire({
                    title: 'Atenção!',
                    text: (ret && ret.message) ? ret.message : 'Não foi possível atualizar o código no XML.',
                    icon: 'warning'
                });
                return;
            }

            var $row = $input.closest('tr.linha-item-xml');
            if (ret.corLinha) {
                $row.css('background-color', ret.corLinha);
            }
            $row.attr('data-produto-ok', ret.produtoEncontrado ? '1' : '0');
            $input.attr('data-cod-atual', codigoNovo);

            if (ret.produtoEncontrado && ret.codigoXml) {
                $("#tableDisagreements tr.divergencia-produto[data-cprod-xml='" + ret.codigoXml + "']").remove();
            }

            verificarBotaoCadastrarXml();
            setEstadoCodProd(input, 'success');
        },
        error: function (_, status) {
            if (status === 'abort') {
                return;
            }
            setEstadoCodProd(input, 'error');
            Swal.fire({ title: 'Erro', text: 'Falha ao atualizar o XML.', icon: 'error' });
        },
        complete: function () {
            delete codProdXmlRequests[codigoXml];
        }
    });
}

function mudaCodProdXmlNew(code_new, code_xml, inputElement) {
    if (inputElement) {
        atualizarCodProdXml(inputElement);
        return;
    }
    var $input = $('input.input-cod-prod-xml[data-cod-xml="' + code_xml + '"]').first();
    if ($input.length) {
        $input.val(code_new);
        atualizarCodProdXml($input[0]);
    }
}

$(document).delegate('.input-cod-prod-xml', 'blur', function () {
    atualizarCodProdXml(this);
});

$(document).delegate('.input-cod-prod-xml', 'keydown', function (e) {
    if (e.which === 13) {
        e.preventDefault();
        $(this).blur();
    }
});

function manipularXML(xml, codigoProcurado, novoCodigo) {
     
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(xml, 'text/xml');

    const namespaceURI = xmlDoc.documentElement.namespaceURI;

    // Procura pelo código dentro da tag <cProd>
    const elementosCProd = xmlDoc.getElementsByTagName('cProd');

    for (let i = 0; i < elementosCProd.length; i++) {
        const cProd = elementosCProd[i];
        const codigoAtual = cProd.innerHTML;

        if (codigoAtual == codigoProcurado) {
            // Verificar se a tag <cProdAlter> já existe
            const cProdAlter = xmlDoc.getElementsByTagName('cProdAlter')[i];

            if (cProdAlter) {
                // A tag <cProdAlter> já existe, perguntar ao usuário se deseja sobrescrever
                const resposta = confirm('Produto já possui código alterado, deseja atualizar?');

                if (resposta) {
                    // Sobrescrever o código
                    cProdAlter.innerHTML = novoCodigo;
                } else {
                    return false;
                }
            } else {
                // A tag <cProdAlter> não existe, criar e adicionar com o novo código
                const novacProdAlter = xmlDoc.createElementNS(namespaceURI, 'cProdAlter');
                novacProdAlter.innerHTML = novoCodigo;

                // Criar a tag <prod> pai, caso não exista
                const prod = cProd.parentNode;
                if (!prod.getElementsByTagName('cProdAlter').length) {
                    prod.appendChild(novacProdAlter);
                }
            }
        }
    }

    const xmlModificado = new XMLSerializer().serializeToString(xmlDoc);

    // //DONWLOAD XML 
    // // Criar um elemento <a> para fazer o download
    // const link = document.createElement('a');
    // link.setAttribute('href', 'data:text/xml;charset=utf-8,' + encodeURIComponent(xmlModificado));
    // link.setAttribute('download', 'arquivo_alterado.xml');
    // link.style.display = 'none';
    // document.body.appendChild(link);
    
    // // Clicar no link para iniciar o download
    // link.click();
    
    // // Remover o elemento <a> após o download
    // document.body.removeChild(link);

    return xmlModificado;
}

function submitValidar() {
    var f = document.upload;
    var token = document.getElementById('xml_token').value.trim();
    var tabela = document.getElementById("tableDisagreements");

    if (tabela && tabela.rows && tabela.rows.length > 0) {
        while (tabela.rows.length > 0) {
            tabela.deleteRow(0);
        }
    }

    if (!token) {
        Swal.fire({
            title: "Atenção!",
            text: "Xml não localizado!",
            icon: "warning"
        });
        return false;
    }

    $("#tableItemns").addClass('xml-import-validando');
    if (!$("#xml-import-validando-msg").length) {
        $("#tableItemns").prepend("<div id='xml-import-validando-msg' class='xml-import-validando-msg'><span class='glyphicon glyphicon-refresh glyphicon-spin'></span> Validando XML...</div>");
    }

    $.ajax({
        type: "POST",
        url: f.action + (f.action.indexOf('?') >= 0 ? '&' : '?') + 'mod=est&form=nota_xml_importa&submenu=conferirAjax&opcao=blank',
        data: { xml_token: token, submenu: 'conferirAjax' },
        dataType: "json",
        success: function (response, textStatus, xhr) {
            if (response && typeof response === 'object' && response.error) {
                document.getElementById('xml_token').value = '';
                Swal.fire({
                    title: "Atenção!",
                    text: response.error,
                    icon: "warning"
                });
                return;
            }
            if (response && typeof response === 'object' && response.success === false) {
                Swal.fire({
                    title: "Atenção!",
                    text: response.message || response.error || "Falha na validação.",
                    icon: "warning"
                });
                return;
            }
            atualizaTabelaNotaFiscal(response, textStatus, xhr);
        },
        error: function () {
            Swal.fire({
                title: "Erro",
                text: "Falha ao validar o XML.",
                icon: "error"
            });
        },
        complete: function () {
            $("#tableItemns").removeClass('xml-import-validando');
            $("#xml-import-validando-msg").remove();
        }
    });
}

 
function atualizaTabelaNotaFiscal(response, textStatus, xhr) {
    //logica para recuperar o header para verificar se existe nota fiscal e habilitar botao cadastrar
    var headersStr = xhr.getAllResponseHeaders();
    var headersArr = headersStr.trim().split('\r\n');

    var headersObj = {};

    headersArr.forEach(function (header) {
        var separatorIndex = header.indexOf(':');
        var key = header.slice(0, separatorIndex).trim();
        var value = header.slice(separatorIndex + 1).trim();
        headersObj[key] = value;
    });

    if(response){
        var tableDisagreements = $("<table />").append(response).find("#tableDisagreements").html();

        if(tableDisagreements){ 
            
            //verifica se a tabela esta na tela
            const tabelaDivergencia = document.getElementById('tableDisagreements');
            if(!tabelaDivergencia && tabelaDivergencia === null){
                // Cria a tabela conforme o código fornecido
                const criatabelaDivergencia = CriaTabelaDisagreements();
                
                // Obtém a referência à div de cabeçalho
                const divCabecalho = document.getElementById('cabecalho'); // Certifique-se de ajustar o ID conforme o seu HTML

                // Insere a tabela logo após a div de cabeçalho
                divCabecalho.insertAdjacentElement('afterend', criatabelaDivergencia);
            }

            $("#tableDisagreements").html(tableDisagreements);


        } else { //se exitir divergencias habilita botao cadastrar

            if (!headersObj.existenotafiscal) {

                var botaoCadastrar = document.getElementById('bnt_cadastrar');
                if (botaoCadastrar.style.display === 'none' || botaoCadastrar.style.display === '') {
                    botaoCadastrar.style.display = 'block';
                }
            }

            // Verifica se a tabela existe no conteúdo HTML
            if ($("#tableDisagreements").length > 0) {
                // Remove a tabela caso ela exista
                $("#tableDisagreements").remove();
            }
        }

        var tableItemns = $("<div />").append(response).find("#tableItemns").html();
        $("#tableItemns").html(tableItemns);

        // Se existir essa td é sinal que a nota fiscal foi dado entrada
        var existeNotaFiscal = document.getElementById('existeNotaFiscal');
        if (!existeNotaFiscal) {
            CriaThTabela(response);
        }
    }else{
        Swal.fire({
            title: "Sucesso",
            text: "Xml sem divergências!",
            icon: "success"
        });
    }
}

//funcao para criar as th da tabela ao validar os dados 
function CriaThTabela(response){
     
    let novaLinha = '';
    const tabelaDivergencia = document.getElementById('tableDisagreements');

    if(!tabelaDivergencia && tabelaDivergencia === null){
        CriaTabelaDisagreements();
    }else{
        
        //varifica se existe botao para adicionar os titulos das colunas
        const btnForn = document.getElementById('submitFornecedor');

        if(!btnForn){
            novaLinha = tabelaDivergencia.insertRow(1);
        }else{
            novaLinha = tabelaDivergencia.insertRow(2);
        }      
    }
}

function CriaTabelaDisagreements() {
    const tabelaDivergencia = document.createElement('table');
    tabelaDivergencia.id = 'tableDisagreements';
    tabelaDivergencia.className = 'table tableProd table-bordered';
    tabelaDivergencia.width = '100%';
    tabelaDivergencia.style.borderRadius = '8px';
    tabelaDivergencia.style.borderCollapse = 'inherit';

    const divDivergencia = document.createElement('td');
    divDivergencia.id = 'divergencia';
    divDivergencia.colSpan = 4;
    divDivergencia.align = 'center';
    divDivergencia.innerHTML = "<h5>Divergências !</h5>";

    const linhaDiv = tabelaDivergencia.insertRow();
    linhaDiv.appendChild(divDivergencia);

    return tabelaDivergencia;
}


function submitAddXml(){
    var f = document.upload;

    $.ajax({
        type: 'POST',
        url: f.action + (f.action.indexOf('?') >= 0 ? '&' : '?') + 'mod=est&form=nota_xml_importa&submenu=limparXmlAjax&opcao=blank',
        dataType: 'json',
        data: { submenu: 'limparXmlAjax' },
        complete: function () {
            var tokenEl = document.getElementById('xml_token');
            if (tokenEl) {
                tokenEl.value = '';
            }
            var nameEl = document.getElementById('xml_file_name');
            if (nameEl) {
                nameEl.value = '';
            }
            var resumoEl = document.getElementById('xml-resumo');
            if (resumoEl) {
                resumoEl.style.display = 'none';
                resumoEl.innerHTML = '';
            }
            var inputFile = document.getElementById('input-file');
            if (inputFile) {
                inputFile.value = '';
            }
            var chevron = document.getElementsByName("btnCollapse")[0];
            if (chevron) {
                chevron.click();
            }
            submitAddXmlLimparTela();
        }
    });
}

function submitAddXmlLimparTela() {

    //limpa as tables da tela  tableItemns  legendas
    var formulario = document.querySelectorAll('table');
    formulario[0].innerHTML = '';
    formulario[1].innerHTML = '';
    formulario[2].innerHTML = '';
    formulario[3].innerHTML = '';
    formulario[4].innerHTML = '';
    formulario[5].innerHTML = '';
    formulario[6].innerHTML = '';
    formulario[7].innerHTML = '';
    formulario[8].innerHTML = '';
    formulario[9].innerHTML = '';
    //formulario[10].innerHTML = '';
    //formulario[11].innerHTML = '';

    //remove bts and tables
    if ($("#btnAddXml").length > 0) {
        // Remove a tabela caso ela exista
        $("#btnAddXml").remove();
    }

    if ($("#btnValidar").length > 0) {
        // Remove a tabela caso ela exista
        $("#btnValidar").remove();
    }

    if ($("#bnt_cadastrar").length > 0) {
        // Remove a tabela caso ela exista
        $("#bnt_cadastrar").remove();
    }

    if ($("#informacoesComplementares").length > 0) {
        // Remove a tabela caso ela exista
        $("#informacoesComplementares").remove();
    }

    if ($("#legendas").length > 0) {
        // Remove a tabela caso ela exista
        $("#legendas").remove();
    }

    if ($("#tableItemns").length > 0) {
        // Remove a tabela caso ela exista
        $("#tableItemns").remove();
    }
    
}
