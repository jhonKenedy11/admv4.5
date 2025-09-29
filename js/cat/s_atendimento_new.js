// document.addEventListener('keydown', function (event) {
//     // evento pressionar ENTER
//     if (event.key == "Enter") {
//         submitLetra();
//     }// fim evento enter
// });// fim addEventListener

/* UTILITÁRIOS */
function submitSearch() {
     
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (prom == '')) {
        swal.fire({
            title: "Atenção!",
            text: "Faça algum filtro de pesquisa.",
            icon: "warning"
        });
    } else {
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        //alert(f.pesq.value);
        f.submit();
    }

}
/**  ORDEM DE SERVICO  */

function submitConfirmarSmart() {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        swal.fire({
            title: "Atenção!",
            text: "Selecione um Cliente.",
            icon: "warning"
        });
        return false;
    }

    if (f.condPgto.value == "" || f.condPgto.value == "0") {
        swal.fire({
            title: "Atenção!",
            text: "Selecione uma Condição de Pagamento.",
            icon: "warning"
        });
        return false;
    }

    if (f.pedido_id.value === "") {
        if (f.catEquipamentoId.value == "") {
            swal.fire({
                title: "Atenção!",
                text: "Selecione um Equipamento.",
                icon: "warning"
            });
            return false;
        }
    }

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este Atendimento?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            var selectedUsers = [];
            $('#usuario_equipe :selected').each(function(i, selected){
              selectedUsers[i] = $(selected).val();
            });

            f.usuario_equipe_ids.value = selectedUsers.join(',');
            f.atualiza_equipe.value = 'true';

            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        } else {
            return false;
        }
    })

} // submitConfirmarSmart


function submitDigitacao() {
    f = document.lancamento;
    f.submenu.value = 'digita';
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
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|" + f.numAtendimento.value;

    // situacao Atendimento  
    f.situacoesAtendimento.value = concatCombo(situacaoAtendimento);

    f.submit();
} // fim submitVoltar

function submitCadastro() {
     
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.letra.value = '';
    f.pessoa.value = '';
    f.nome.value = '';
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

function submitCancelar(id) {
    if (confirm('Deseja realmente Cancelar este Atendimento') == true) {
        f = document.lancamento;
        f.submenu.value = 'cancela';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitExcluir(id) {
    if (confirm('Deseja realmente Excluir este pedido') == true) {
        f = document.lancamento;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function submitExcluirPeca(idPeca) {
    if (confirm('Deseja realmente Excluir este item') == true) {
         
        f = document.lancamento;
        f.submenu.value = 'excluiPeca';
        f.idPecas.value = '';
        f.idPecas.value = idPeca;
        f.submit();
    } // if
} // submitExcluir

function submitExcluirServico(idServico) {
    if (confirm('Deseja realmente Excluir este item') == true) {
         
        f = document.lancamento;
        f.submenu.value = 'excluiServico';
        f.idServicos.value = '';
        f.idServicos.value = idServico;
        f.submit();
    } // if
} // submitExcluir

function submitEstornarOs(id) {
    if (confirm('Deseja realmente estornar essa Ordem de serviço?') == true) {
        f = document.lancamento;
        f.submenu.value = 'estornaOs';
        f.id.value = id;
        f.submit();
    } // if 
} // submitExcluir

function abrir(pag, form = null) {
     
    screenWidth = 750;
    screenHeight = 650;
    if (form == 'produto') {
        if (document.lancamento.pessoa.value == '') {
            swal.fire({
                title: "Atenção!",
                text: "Selecione o Cliente antes de fazer a pesquisa",
                icon: "warning"
            });
            return false;
        }

        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&acao=' + document.lancamento.opcao_item.value + '&idTipoAtendimento=' + document.lancamento.catTipoId.value;
        pag = '';
        pag = newPage;
    }
    if (form == 'servicos') {
        if (document.lancamento.pessoa.value == '') {
            swal.fire({
                title: "Atenção!",
                text: "Selecione o Cliente antes de fazer a pesquisa",
                icon: "warning"
            });
            return false;
        }

        // if (document.lancamento.pedido_id.value == '' && document.lancamento.catEquipamentoId.value == '') {
        //     swal.fire({
        //         title: "Atenção!",
        //         text: "Selecione o Equipamento antes de fazer a pesquisa",
        //         icon: "warning"
        //     });
        //     return false;
        // }

        if (document.lancamento.pedido_id.value == '') {
            swal.fire({
                title: "Atenção!",
                text: "Selecione o Equipamento antes de fazer a pesquisa",
                icon: "warning"
            });
            return false;
        }

        screenWidth = screen.width;
        screenHeight = screen.height;
    }

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=' + screenWidth + ',height=' + screenHeight + ',scrollbars=yes');
}

function calculaTotal() {
     
    var f = document.lancamento;
    var pecas = f.valorPecas.value == '' ? '0,00' : f.valorPecas.value;
    var servicos = f.valorServicos.value == '' ? '0,00' : f.valorServicos.value;
    var visita = f.valorVisita.value == '' ? '0,00' : f.valorVisita.value;
    var desconto = f.valorDesconto.value == '' ? '0,00' : f.valorDesconto.value;
    var total = 0;

    pecas = parseFloat(pecas.replace(".", "").replace(",", "."));
    servicos = parseFloat(servicos.replace(".", "").replace(",", "."));
    visita = parseFloat(visita.replace(".", "").replace(",", "."));
    desconto = parseFloat(desconto.replace(".", "").replace(",", "."));



    total = ((pecas + servicos + visita) - desconto);
    if (total == NaN) {
        total = 0
    } else if (total == undefined) {
        total = 0
    } else if (total == Infinity) {
        total = 0
    } else {

    }
    f.valorTotal.value = currencyFormat(total);
}

function currencyFormat(num) {
     
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

// concatena combo com pipes
function concatCombo(combo) {
    valor = '';
    for (var i = 0; i < combo.options.length; i++) {
        if (combo[i].selected == true) {
            valor = valor + "|" + combo[i].value;
        }
    }
    return valor;
}

function confirmacaoDesconto() {
    f = document.lancamento;
    if (f.valorDesconto.value != '0' || f.valorDesconto.value != '') {
        if (confirm("AVISO. Esse desconto será dividido no desconto de todos os itens, " +
            "eliminando qualquer desconto já aplicado direto no item. " +
            "Deseja confirmar esse novo desconto geral?") == true) {
            //f.form.value = 'atendimento';
            //f.submenu.value = 'recalcularDesconto';
            //f.submit();

        } else {
            return false;
        }
    }

}

function editarModalPeca(e) {

    var linha = $(e).closest("tr");

    var id = linha.find("td:eq(0)").text().trim();
    var codigo = linha.find("td:eq(1)").text().trim();
    var descricao = linha.find("td:eq(2)").text().trim();
    var unidade = linha.find("td:eq(3)").text().trim();
    var quantidade = linha.find("td:eq(4)").text().trim();
    var vlrUnitario = linha.find("td:eq(5)").text().trim();
    var percDesconto = linha.find("td:eq(6)").text().trim();
    var vlrDesconto = linha.find("td:eq(7)").text().trim();
    var totalitem = linha.find("td:eq(8)").text().trim();

    $("#mIdPeca").val(id);
    $("#mCodPeca").val(codigo);
    $("#mDescPeca").val(descricao);
    $("#mUniPeca").val(unidade);
    $("#mQtdePeca").val(quantidade);
    $("#mVlrUniPeca").val(vlrUnitario);
    $("#mPercDescPeca").val(percDesconto);
    $("#mDescontoPeca").val(vlrDesconto);
    $("#mTotalPeca").val(totalitem);
}

function editarModalServico(e) {

    var linha = $(e).closest("tr");

    var codigo = linha.find("td:eq(0)").text().trim();
    var descricao = linha.find("td:eq(1)").text().trim();
    var unidade = linha.find("td:eq(2)").text().trim();
    var quantidade = linha.find("td:eq(3)").text().trim();
    var vlrUnitario = linha.find("td:eq(4)").text().trim();
    var totalitem = linha.find("td:eq(5)").text().trim();

    $("#mIdServico").val(codigo);
    $("#mDescServico").val(descricao);
    $("#mUniServico").val(unidade);
    $("#mQtdeServico").val(quantidade);
    $("#mVlrUniServico").val(vlrUnitario);
    $("#mTotalServico").val(totalitem);
}



function submitAlteraPeca() {
    f = document.lancamento;
    f.letra_peca.value = '';
    f.letra_peca.value = f.mIdPeca.value + "|" + f.mCodPeca.value + "|" + f.mDescPeca.value + "|" + f.mUniPeca.value +
        "|" + f.mQtdePeca.value + "|" + f.mVlrUniPeca.value + "|" + f.mPercDescPeca.value + "|" + f.mDescontoPeca.value +
        "|" + f.mTotalPeca.value;
    f.submenu.value = 'alteraPeca';
    f.submit()
}

function submitAlteraServico() {
    f = document.lancamento;
    f.letra_servico.value = '';
    f.letra_servico.value = f.mIdServico.value + "|" + f.mDescServico.value + "|" + f.mUniServico.value +
        "|" + f.mQtdeServico.value + "|" + f.mVlrUniServico.value + "|" + f.mTotalServico.value;
    f.submenu.value = 'alteraServico';
    f.submit()
}

function calculaTotalItens(campo = '', modal = '') {
     
    var f = document.lancamento;
    if (modal == 'pecas') {
        if (f.quantidadePecas.value == '0,00' || f.quantidadePecas.value == '') {
            return false;
        }
        if (f.vlrUnitarioPecas.value == '0,00' || f.vlrUnitarioPecas.value == '') {
            return false;
        }
        var vlrQtde = f.quantidadePecas.value;
        var unitario = f.vlrUnitarioPecas.value;
        var desconto = campo != 'desconto' ? desconto = "0,00" : desconto = f.vlrDescontoPecas.value;
        var vlrPercdesconto = campo == 'desconto' || f.percDescontoPecas.value == '' ? vlrPercdesconto = "0,00" : vlrPercdesconto = f.percDescontoPecas.value;

        desconto = parseFloat(desconto.replace(".", "").replace(",", "."))
        vlrPercdesconto = parseFloat(vlrPercdesconto.replace(".", "").replace(",", "."))
    } else {
        if (f.quantidadeServico.value == '0,00' || f.quantidadeServico.value == '') {
            return false;
        }
        if (f.vlrUnitarioServico.value == '0,00' || f.vlrUnitarioServico.value == '') {
            return false;
        }
        var vlrQtde = f.quantidadeServico.value;
        var unitario = f.vlrUnitarioServico.value;
    }

    var total = 0;

    vlrQtde = parseFloat(vlrQtde.replace(".", "").replace(",", "."))
    unitario = parseFloat(unitario.replace(".", "").replace(",", "."))


    totalItem = (vlrQtde * unitario);
    if (modal == 'pecas') {
        if (campo == 'desconto') {
            vlrPercdesconto = ((desconto * 100) / totalItem)
        } else {
            desconto = ((totalItem * vlrPercdesconto) / 100)

        }
        resultTotal = (totalItem - desconto);
        resultPerc = currencyFormat(vlrPercdesconto);
        resultDesc = currencyFormat(desconto);
    } else {
        resultTotal = totalItem
    }

    total = currencyFormat(resultTotal);

    if (total === 'NaN' || total === undefined || total === Infinity) {
        total = 0
    }

    if (modal == 'pecas') {
        f.totalPecas.value = total;
        f.vlrDescontoPecas.value = resultDesc;
        f.percDescontoPecas.value = resultPerc;
    } else {
        f.totalServico.value = total;
    }
}


/** PECAS */
function submitConfirmarPecas() {
     
    //validações 
    if (document.lancamento.quantidadePecas.value == '' || document.lancamento.quantidadePecas.value == '0,00') {
         swal.fire({
            title:"Atenção!",
            text:"Preencha o campo 'Quantidade' para incluir o Produto.",
            icon:"warning"});
        return false;
    }
    if (document.lancamento.vlrUnitarioPecas.value == '' || document.lancamento.vlrUnitarioPecas.value == '0,00') {
         swal.fire({
            title:"Atenção!",
            text:"Preencha o campo 'Valor Unitário' para incluir o Produto.",
            icon:"warning"});
        return false;
    }

    //implementation checks if item already exists
    let linha = null;
    let testItems = null;
    let current_item = document.getElementById('codProduto');
    let itens_estoque = document.getElementsByClassName('tab_cod_produto');

    //verify itens exists
    Array.from(itens_estoque).forEach(item_estoque => {
        if (item_estoque.innerHTML.trim() === current_item.defaultValue) {
            linha = item_estoque.parentElement;
            testItems = 1
        } //Fim if
    }) //ForEach


    if (testItems !== null && document.lancamento.opcao_item.value !== 'alterar') {
        return new Promise((resolve, reject) => {
             swal.fire({
                title: "Atenção!",
                text: "Item (" + linha.childNodes[3].innerText + " - " + linha.childNodes[9].innerText + ") já cadastrado, deseja atualizar ou incluir novo?",
                icon: "warning",
                buttons: {
                    btn_cancelar: {
                        text: "Cancelar",
                        value: '0',
                    },
                    btn_cadastrar_novo: {
                        text: "Cadastrar novo",
                        value: "1",
                    },
                    btn_atualizar_existente: {
                        text: "Atualizar existente",
                        value: "2",
                    },
                },
            })
                .then((val) => {

                    if (val === "1") { //Cadastrar novo
                        montaLetraPeca();
                    } else if (val === "2") { //Atualizar existente
                         
                        //data table
                        tab_id = linha.childNodes[1].innerText;
                        tab_quant = parseFloat(linha.childNodes[15].innerText.replace(".", "").replace(",", "."));
                        //tab_vlr_uni = parseFloat(linha.childNodes[17].innerText.replace(".","").replace(",","."));
                        tab_perc_desc = parseFloat(linha.childNodes[19].innerText.replace(".","").replace(",","."));
                        //tab_vlr_total = parseFloat(linha.childNodes[23].innerText.replace(".","").replace(",","."));

                        //data new
                        n_quant = parseFloat(document.lancamento.quantidadePecas.value.replace(".", "").replace(",", "."));
                        n_vlr_unitario = parseFloat(document.lancamento.vlrUnitarioPecas.value.replace(".", "").replace(",", "."));
                        n_perc_desconto = parseFloat(document.lancamento.percDescontoPecas.value.replace(".", "").replace(",", "."));
                        n_vlr_total = parseFloat(document.lancamento.totalPecas.value.replace(".", "").replace(",", "."));

                        //data update
                        up_quantidade = tab_quant + n_quant;
                        up_vlr_unitario = n_vlr_unitario;

                        //define onde ira vir o percentul de desconto
                        if((n_perc_desconto == '') || (n_perc_desconto == 0)){
                            up_perc_desconto = tab_perc_desc;
                        }else{
                            up_perc_desconto = n_perc_desconto;
                        }
                        
                        //calc desc
                        up_total_item = (up_quantidade * up_vlr_unitario);
                        up_valor_desconto = up_total_item * (up_perc_desconto / 100);
                        up_total_item_desc = up_total_item - up_valor_desconto;


                        document.lancamento.letra_peca.value = document.lancamento.id.value + "|" +
                                                               document.lancamento.pessoa.value + "|" +
                                                               document.lancamento.codProduto.value + "|" +
                                                               document.lancamento.codProdutoNota.value + "|" +
                                                               document.lancamento.descProduto.value + "|" +
                                                               document.lancamento.uniProduto.value + "|" +
                                                               currencyFormat(up_quantidade) + "|" +
                                                               currencyFormat(up_vlr_unitario) + "|" +
                                                               currencyFormat(up_perc_desconto)+ "|" +
                                                               currencyFormat(up_valor_desconto) + "|" +
                                                               currencyFormat(up_total_item_desc) + "|" +
                                                               document.lancamento.situacao.value + "|" +
                                                               document.lancamento.catTipoId.value + "|" +
                                                               tab_id + "|" +
                                                               document.lancamento.codFabricante.value;
                        
                    } else if (val === '0') { // Cancelar
                        return false
                    } else {
                        return false;
                    }
                    resolve(); // Resolve a promessa quando o usuário escolhe uma opção no swal
                });
        })
            .then((val) => {

                // O código aqui será executado após o usuário escolher uma opção no swal
                var form = $("form[name=lancamento]");

                $.ajax({
                    type: "POST",
                    url: form.action ? form.action : document.URL,
                    data: $(form).serialize(),
                    dataType: "text",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Ajax-Request-Cadastra-Peca", "true");
                    },
                    success: function (response) {
                        var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                        $("#datatable-buttons-pecas").html(result);

                        var resultTotal = $('<div />').append(response).find('#divTotal').html();
                        $("#divTotal").html(resultTotal);

                        var idOs = $('<div />').append(response).find('#idAtendimento').html();
                        $("#idAtendimento").html(idOs);

                        limpaCamposPeca();
                        $('#codFabricante').focus();

                    }
                });
            });

    } else {

        montaLetraPeca();

        var form = $("form[name=lancamento]");

        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Cadastra-Peca", "true");
            },
            success: function (response) {
                 
                var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                $("#datatable-buttons-pecas").html(result);

                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);

                var idOs = $('<div />').append(response).find('#idAtendimento').html();
                $("#idAtendimento").html(idOs);

                limpaCamposPeca();
                $('#codFabricante').focus();

            }
        });
    }

    return false;
}


function editarPeca(e, idPecas) {
     
    var linha = $(e).closest("tr");

    var codigo = linha.find("td:eq(1)").text().trim();
    var codigoFabricante = linha.find("td:eq(2)").text().trim();
    var codigoNota = linha.find("td:eq(3)").text().trim();
    var descricao = linha.find("td:eq(4)").text().trim();
    var unidade = linha.find("td:eq(5)").text().trim();
    var quantidade = linha.find("td:eq(7)").text().trim();
    var vlrUnitario = linha.find("td:eq(8)").text().trim();
    var percDesconto = linha.find("td:eq(9)").text().trim();
    var vlrDesconto = linha.find("td:eq(10)").text().trim();
    var totalitem = linha.find("td:eq(11)").text().trim();

    document.lancamento.idPecas.value = idPecas;
    document.lancamento.opcao_item.value = 'alterar';
    $("#codProduto").val(codigo);
    $("#codFabricante").val(codigoFabricante);
    $("#codProdutoNota").val(codigoNota);
    $("#descProduto").val(descricao);
    $("#uniProduto").val(unidade);
    $("#quantidadePecas").val(quantidade);
    $("#vlrUnitarioPecas").val(vlrUnitario);
    $("#percDescontoPecas").val(percDesconto);
    $("#vlrDescontoPecas").val(vlrDesconto);
    $("#totalPecas").val(totalitem);
}

function submitExcluiPeca(idPeca) {
    if (confirm('Deseja realmente Excluir este Item ?') == true) {
        document.lancamento.letra_peca.value = document.lancamento.id.value + "|" +
            idPeca + "|" +
            document.lancamento.situacao.value;

        var form = $("form[name=lancamento]");

        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Peca", "true");
            },
            success: function (response) {
                 
                var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                $("#datatable-buttons-pecas").html(result);

                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);



                limpaCamposPeca();

            }
        });
        return false;
    } else {
        return false
    }



}

function montaLetraPeca() {
    document.lancamento.letra_peca.value = document.lancamento.id.value + "|" +
        document.lancamento.pessoa.value + "|" +
        document.lancamento.codProduto.value + "|" +
        document.lancamento.codProdutoNota.value + "|" +
        document.lancamento.descProduto.value + "|" +
        document.lancamento.uniProduto.value + "|" +
        document.lancamento.quantidadePecas.value + "|" +
        document.lancamento.vlrUnitarioPecas.value + "|" +
        document.lancamento.percDescontoPecas.value + "|" +
        document.lancamento.vlrDescontoPecas.value + "|" +
        document.lancamento.totalPecas.value + "|" +
        document.lancamento.situacao.value + "|" +
        document.lancamento.catTipoId.value + "|" +
        document.lancamento.idPecas.value + "|" +
        document.lancamento.codFabricante.value;

}

function limpaCamposPeca() {
    document.lancamento.letra_peca.value = ''
    document.lancamento.codProduto.value = ''
    document.lancamento.codProdutoNota.value = ''
    document.lancamento.descProduto.value = ''
    document.lancamento.uniProduto.value = ''
    document.lancamento.quantidadePecas.value = ''
    document.lancamento.vlrUnitarioPecas.value = ''
    document.lancamento.percDescontoPecas.value = ''
    document.lancamento.vlrDescontoPecas.value = ''
    document.lancamento.totalPecas.value = ''
    document.lancamento.idPecas.value = ''
    document.lancamento.codFabricante.value = ''
    document.lancamento.opcao_item.value = '';
}

/*  SERVICOS   */


function submitConfirmarServicos() {
    //validações 
   if (document.lancamento.quantidadeServico.value == '' || document.lancamento.quantidadeServico.value == '0,00') {
        swal.fire({
            title: "Atenção!",
            text: "Preencha o campo Quantidade para incluir o Serviço.",
            icon: "warning"
        });
        return false;
    }
    if (document.lancamento.vlrUnitarioServico.value == '' || document.lancamento.vlrUnitarioServico.value == '0,00') {
        swal.fire({
            title: "Atenção!",
            text: "Preencha o campo Valor Unitário para incluir o Serviço.",
            icon: "warning"
        });
        return false;
    }
    if (document.lancamento.codServico.value == '' || document.lancamento.codServico.value == '0') {
        swal.fire({
            title: "Atenção!",
            text: "Selecione um Serviço para incluir.",
            icon: "warning"
        });
        return false;
    }
    montaLetraServico();

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Servico", "true");
        },
        success: function (response) {
            var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
            $("#datatable-buttons-servicos").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html();
            $("#divTotal").html(resultTotal);

            var idOs = $('<div />').append(response).find('#idAtendimento').html();
            $("#idAtendimento").html(idOs);

            limpaCamposServicos();

        }
    });
    return false;
}

function editarServico(e, idServicos) {

    var linha = $(e).closest("tr");

    var codigo = linha.find("td:eq(0)").text().trim();
    var descricao = linha.find("td:eq(1)").text().trim();
    var unidade = linha.find("td:eq(2)").text().trim();
    var quantidade = linha.find("td:eq(3)").text().trim();
    var vlrUnitario = linha.find("td:eq(4)").text().trim();
    var totalitem = linha.find("td:eq(5)").text().trim();

    document.lancamento.codServico.value = idServicos;
    $("#idServicos").val(codigo);
    $("#descricaoServico").val(descricao);
    $("#unidadeServico").val(unidade);
    $("#quantidadeServico").val(quantidade);
    $("#vlrUnitarioServico").val(vlrUnitario);
    $("#totalServico").val(totalitem);
}

function submitExcluiServico(idServicos) {
    if (confirm('Deseja realmente Excluir este Item ?') == true) {
        document.lancamento.letra_servico.value = document.lancamento.id.value + "|" +
            idServicos + "|" +
            document.lancamento.situacao.value;

        var form = $("form[name=lancamento]");

        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Servico", "true");
            },
            success: function (response) {
                var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
                $("#datatable-buttons-servicos").html(result);

                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);

                limpaCamposServicos();

            }
        });
        return false;
    } else {
        return false
    }

}

function montaLetraServico() {
    document.lancamento.letra_servico.value = document.lancamento.id.value + "|" +
        document.lancamento.pessoa.value + "|" +
        document.lancamento.codServico.value + "|" +
        document.lancamento.descricaoServico.value + "|" +
        document.lancamento.unidadeServico.value + "|" +
        document.lancamento.quantidadeServico.value + "|" +
        document.lancamento.vlrUnitarioServico.value + "|" +
        document.lancamento.totalServico.value + "|" +
        document.lancamento.situacao.value + "|" +
        document.lancamento.catTipoId.value + "|" +
        document.lancamento.idServicos.value;

    console.log(document.lancamento.letra_peca.value);
}

function limpaCamposServicos() {
    document.lancamento.letra_servico.value = ''
    document.lancamento.idServicos.value = ''
    document.lancamento.codServico.value = ''
    document.lancamento.descricaoServico.value = ''
    document.lancamento.unidadeServico.value = ''
    document.lancamento.quantidadeServico.value = ''
    document.lancamento.vlrUnitarioServico.value = ''
    document.lancamento.totalServico.value = ''
}



function submitCadastrarAtendimentoNf(id) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';
    f.id.value = id;
    f.submit();
}


function duplicaOs(id) {
    f = document.lancamento;
    f.submenu.value = 'duplicaOs';
    f.id.value = id
    f.submit();
}

function submitCadastrarAtendimentoPedido(id) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_pedido';
    f.submenu.value = 'cadastrar';
    f.id.value = id;
    f.submit();
}

function atualizaDesc() {
    f = document.lancamento;
    var desc = f.novaDesc.value;

    f.descricaoServico.value = desc.toUpperCase();
}

function transDesc() {
    f = document.lancamento;
    var descAtual = f.descricaoServico.value;

    f.novaDesc.value = descAtual;
}

//function printOs(id) {
//    $( "#popup" ).dialog({
//      resizable: false,
//      height: "auto",
//      width: 400,
//      modal: true,
//      buttons: {
//        "Cliente": function() {
//          $( this ).dialog( "close" );
//          window.open("index.php?mod=cat&form=os_imprime&opcao=imprimir&id="+id,
//        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
//        },
//        "Mecânico": function() {
//          $( this ).dialog( "close" );
//          window.open("index.php?mod=cat&form=os_imprime&opcao=imprimir&print=mecanico&id="+id,
//        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
//        }
//      }
//    });
//}

function imprimeOs(id) {
    window.open("index.php?mod=cat&form=os_imprime&opcao=imprimir&id=" + id,
        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

function imprimeOsMecanico(id) {

    window.open("index.php?mod=cat&form=os_imprime&opcao=imprimir&print=mecanico&id=" + id,
        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

function buscaProduto() {
     
    f = document.lancamento;

    if (f.nome.value == '') {
         swal.fire({
            text: "Informe o Cliente!",
            icon: "warning",
            dangerMode: "Ok",
        });
        return false;
    }

    if (f.codFabricante.value !== "" & f.codFabricante.value !== 'undefined' & f.codFabricante.value !== 'null') {

        montaLetraPeca();

        var form = $("form[name=lancamento]");

        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Busca-prod", "true");
            },
            success: function (response) {
                 
                var result = $('<div />').append(response).find('#tab_content2').html();
                var prodExiste = $(result).find("input[name=prodExiste]")[0].attributes[3].nodeValue;

                if (prodExiste === 'yes') {
                    $("#tab_content2").html(result);

                    f.quantidadePecas.value = '0,00';
                    f.quantidadePecas.focus();
                } else {
                     swal.fire({
                        title: "Atenção!",
                        text: "Produto Não Localizado!",
                        icon: "warning",
                        buttons: {
                            btn_cadastrar_novo: {
                                text: "pesquisar",
                                value: "1",
                            },
                            btn_cancelar: {
                                text: "OK",
                                value: '0',
                            }
                        }
                    })
                    .then((val) => {
                         
                        if(val == 0){
                            return false;
                        }else{
                            window.open(document.URL + '?mod=est&form=produto&opcao=pesquisarpecas&from=atendimento_new&letra=||'+document.getElementById('codFabricante').value, 
                            'consulta','toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
                        }
                    });
                }

                //Aplica mascara no form 
                $(".money").maskMoney({
                    decimal: ",",
                    thousands: ".",
                    allowZero: true,
                });
            }
        });
        return false;
    } else {
        return false;
    }
}

// mostra Cadastro Img
function submitCadastrarImagemOS(id) {

    window.open("index.php?mod=cat&form=atendimento_new&opcao=imprimir&submenu=cadastrarImagem&idOs=" + id,
        "toolbar=no,location=no,resizable=yes,menubar=yes,scrollbars=yes");

} // submitCadastrarImagem

function cadastraProduto() {
    window.open("index.php?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}