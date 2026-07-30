/*inicio confirmar cotação */
function submitConfirmar() {
    f = document.lancamento;
    var numCotacao = f.id.value;
    f.numCotacao.value = numCotacao;

    if (f.pessoa.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Cliente.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    if (f.condPgto.value == "" || f.condPgto.value == "0" ) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione uma Condição de Pagamento.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    

    var tableProdutos = document.getElementById("datatable-buttons-produtos");
    var rowProduto = tableProdutos.rows.length;

    if (rowProduto <= 1) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Insira pelo menos um produto para realizar uma cotação.',
            confirmButtonText: 'OK'
        });
        return false;
    }
        
    Swal.fire({
        title: 'Deseja realmente ' + (f.submenu.value == "cadastrar" ? "cadastrar" : "alterar") + ' esta Cotação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                if(f.id.value != ''){
                    f.submenu.value = 'altera';
                }else{
                    f.submenu.value = 'inclui';
                }
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    }); 
}

/* fim confirmar cotação */

/*inicio pesquisa por letra */
function submitLetra() {
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numCotacao.value;
    f.submit();
}

/* fim pesquisa por letra */

/*inicio voltar */
function submitVoltar() {
    f = document.lancamento;
    f.submenu.value = 'digita';
    f.submit();
}

/* fim voltar */

/*inicio cadastro */
function submitCadastro() {
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.pessoa.value = '';
    f.numCotacao.value = '';
    f.submit();
}

/* fim cadastro */

/*inicio limpar filtros */
function submitLimparFiltros() {
    f = document.lancamento;
    // Limpa o ID da cotação
    if (f.numCotacao) f.numCotacao.value = '';
    if (f.pessoa) f.pessoa.value = '';
    if (f.nome) f.nome.value = '';
    
    var dataIni = moment().startOf('month').format('DD/MM/YYYY');
    var dataFim = moment().format('DD/MM/YYYY');
    
    if (f.dataIni) f.dataIni.value = dataIni;
    if (f.dataFim) f.dataFim.value = dataFim;
    
    // Atualiza o campo de data do daterangepicker se existir
    if ($('input[name="dataConsulta"]').length) {
        if ($('input[name="dataConsulta"]').data('daterangepicker')) {
            $('input[name="dataConsulta"]').data('daterangepicker').setStartDate(moment(dataIni, "DD/MM/YYYY"));
            $('input[name="dataConsulta"]').data('daterangepicker').setEndDate(moment(dataFim, "DD/MM/YYYY"));
        }
        $('input[name="dataConsulta"]').val(dataIni + ' - ' + dataFim);
    }
}

/* fim limpar filtros */

/*inicio alterar */
function submitAlterar(numCotacao) {
    f = document.lancamento;
    f.submenu.value = 'alterar';
    f.numCotacao.value = numCotacao;
    f.submit();
}

/* fim alterar */

/*inicio cancelar */
function submitCancelar(numCotacao) {
    Swal.fire({
        title: 'Deseja realmente Cancelar esta Cotação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'exclui';
            f.numCotacao.value = numCotacao;
            f.submit();
        }
    });
}

/* fim cancelar */

/*inicio excluir item */
function submitExcluiItem(nrItem) {
    Swal.fire({
        title: 'Deseja realmente Excluir este item',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            var form = $("form[name=lancamento]");
            form.find('input[name=submenu]').val('exclui_item');
            form.find('input[name=nrItem]').val(nrItem);
            
            var idValue = document.lancamento.id ? document.lancamento.id.value : '';
            var numCotacaoInput = form.find('input[name=numCotacao]');
            if (numCotacaoInput.length) {
                numCotacaoInput.val(idValue);
            } else {
                form.append('<input type="hidden" name="numCotacao" value="' + idValue + '">');
            }

            $.ajax({
                type: "POST",
                url: "index.php?mod=ped&form=cotacao&opcao=blank",
                data: $(form).serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: response.error,
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }

                    if (response.success) {
                        if (response.id) {
                            document.lancamento.id.value = response.id;
                            document.lancamento.numCotacao.value = response.id;
                        }

                        // Atualiza o tbody da tabela com o HTML retornado
                        if (response.html) {
                            $("#datatable-buttons-produtos tbody").html(response.html);
                        }

                        // Atualiza a div de totais com o HTML retornado
                        if (response.totais) {
                            $("#divTotal").html(response.totais);

                            // Atualiza os valores dos campos do formulário após substituir o HTML
                            var valorProdutos = $("#valorProdutos").val();
                            var valorDesconto = $("#valorDesconto").val();
                            var valorTotal = $("#valorTotal").val();
                            
                            if (valorProdutos) {
                                document.lancamento.valorProdutos.value = valorProdutos;
                            }
                            if (valorDesconto) {
                                document.lancamento.valorDesconto.value = valorDesconto;
                            }
                            if (valorTotal) {
                                document.lancamento.valorTotal.value = valorTotal;
                            }
                        }

                        limpaCamposProduto();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao processar: ' + error,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
    return false;
}

/* fim excluir item */

/*inicio abrir pesquisa de produto */
function abrir(pag, form=null)
{
    screenWidth = 750;
    screenHeight = 650;
    
    if(form == 'produto'){
        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&acao='+document.lancamento.opcao_item.value;
        pag = '';
        pag = newPage;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

/* fim abrir pesquisa de produto */

/*inicio atualizar info */
function atualizarInfo() {
    var valorTotal = document.getElementById('valorTotal');
    var f = document.lancamento;
    var numCotacao = f.id.value;
    f.numCotacao.value = numCotacao;
    
    if (numCotacao === '') {
        prosseguirComDesconto();
    } else {
        prosseguirComDesconto();
    }
}

function prosseguirComDesconto() {
    var valorTotal = document.getElementById('valorTotal');
    var f = document.lancamento;
    var v = valorTotal.value.replace(/\./g, '').replace(',', '.');
    
    if (v === '' || isNaN(parseFloat(v)) || parseFloat(v) === 0) {
        return; // Não faz nada se não pode editar desconto
    }
    
    swal.fire({
        title: "Atenção?",
        text: "Ao realizar o desconto geral os descontos unitários serão recalculados!",
        icon: "warning",
        buttons: ["Cancelar", 'Continuar'],
    })
    .then((yes) => {
        if(yes){
            localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);

            var desconto = parseFloat(f.valorDesconto.value.replace(".", "").replace(",", "."));
            var total = parseFloat(f.valorTotal.value.replace(".", "").replace(",", "."));
        
            if (desconto > total) {
                 swal.fire({
                title:"Atenção!",
                text:"O desconto nao pode ser maior do que o valor total!",
                icon:"warning"});
                f.valorDesconto.value = "0,00";
                return false;
            }
            if (f.valorDesconto.value == "") {
                f.valorDesconto.value = "0,00";
            }

            var newDesconto = parseFloat(f.valorDesconto.value.replace(".", "").replace(",", "."))
            f.valorDesconto.value = newDesconto;
        
            f.submenu.value = "atualizarInfo";
            f.submit();
        }
    });
} // prosseguirComDesconto

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaTotalItens(campo = '', modal=''){
    var f = document.lancamento;
    if(f.quantidadeProduto.value == '0,00' || f.quantidadeProduto.value == ''){
        return false;
    }
    if (f.vlrUnitarioProduto.value == '0,00' || f.vlrUnitarioProduto.value ==  ''){
        return false;
    }
    var vlrQtde     = f.quantidadeProduto.value ;
    var unitario    = f.vlrUnitarioProduto.value;
    var desconto    = campo != 'desconto' ? desconto = "0,00" : desconto = f.vlrDescontoProduto.value;
    var vlrPercdesconto = campo == 'desconto' || f.percDescontoProduto.value == '' ? vlrPercdesconto  = "0,00" : vlrPercdesconto = f.percDescontoProduto.value;

    desconto         = parseFloat(desconto.replace(".","").replace(",","."))
    vlrPercdesconto  = parseFloat(vlrPercdesconto.replace(".","").replace(",","."))
    
    var total     = 0;

    vlrQtde          = parseFloat(vlrQtde.replace(".","").replace(",","."))
    unitario         = parseFloat(unitario.replace(".","").replace(",","."))
    

    totalItem     = (vlrQtde * unitario);
    if(campo == 'desconto'){
        vlrPercdesconto  = ((desconto * 100)/totalItem)
    }else{
        desconto = ((totalItem*vlrPercdesconto)/100)

        //nova logica para arredondamento
        var numeroMultiplicado = Math.round(desconto * 1000);
        var terceiraCasaDecimal = numeroMultiplicado % 10;
        if (terceiraCasaDecimal >= 5) {
            roundedValue = Math.ceil(desconto * 100) / 100;
        } else {
            roundedValue = Math.floor(desconto * 100) / 100;
        }
        
        desconto = roundedValue;
    }
    resultTotal = (totalItem - desconto);
    resultPerc = currencyFormat(vlrPercdesconto);
    resultDesc = currencyFormat(desconto);
    
    total = currencyFormat(resultTotal);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    f.totalProduto.value = total;
    f.vlrDescontoProduto.value = resultDesc;
    f.percDescontoProduto.value = resultPerc;
}

/* fim atualizar info */

/*inicio confirmar item */
function submitConfirmarItem() {
    if (document.lancamento.quantidadeProduto.value == '' || document.lancamento.quantidadeProduto.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Quantidade para incluir o Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (document.lancamento.vlrUnitarioProduto.value == '' || document.lancamento.vlrUnitarioProduto.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Valor Unitário para incluir o Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    var form = $("form[name=lancamento]");
    var submenu = document.lancamento.opcao_item.value == 'altera' ? 'altera_item' : 'inclui_item';
    form.find('input[name=submenu]').val(submenu);
    
    // Garante que numCotacao seja enviado
    var idValue = document.lancamento.id ? document.lancamento.id.value : '';
    var numCotacaoInput = form.find('input[name=numCotacao]');
    if (numCotacaoInput.length) {
        numCotacaoInput.val(idValue);
    } else {
        form.append('<input type="hidden" name="numCotacao" value="' + idValue + '">');
    }
 
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=cotacao&opcao=blank",
        data: $(form).serialize(),
        dataType: "json",
        success: function (response) {
           
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: response.error,
                    confirmButtonText: 'OK'
                });
                return false;
            }

            if (response.success) {
                if (response.id) {
                    document.lancamento.id.value = response.id;
                    document.lancamento.numCotacao.value = response.id;
                }

                if (response.html) {
                    $("#datatable-buttons-produtos tbody").html(response.html);
                }

                if (response.totais) {
                    $("#divTotal").html(response.totais);
                    var valorProdutos = $("#valorProdutos").val();
                    var valorDesconto = $("#valorDesconto").val();
                    var valorTotal = $("#valorTotal").val();
                    
                    if (valorProdutos) {
                        document.lancamento.valorProdutos.value = valorProdutos;
                    }
                    if (valorDesconto) {
                        document.lancamento.valorDesconto.value = valorDesconto;
                    }
                    if (valorTotal) {
                        document.lancamento.valorTotal.value = valorTotal;
                    }
                }

                limpaCamposProduto();
                
                $("#secaoEdicaoItem").slideUp();
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao processar: ' + error,
                confirmButtonText: 'OK'
            });
        }
    });
    return false;
}

function editarProduto(e, nrItem){
    var linha = $(e).closest("tr");

    var codigoProduto = linha.find("td.i_item_estoque").text().trim(); 
    var codFabricante = linha.find("td.i_item_fabricante").text().trim(); 
    var codNota = linha.find("td.i_codigo_nota").text().trim(); 
    var descricao = linha.find("td.i_decricao").text().trim();   
    var quantidade = linha.find("td.i_qtd_solicitada").text().trim(); 
    var vlrUnitario = linha.find("td.i_unitario").text().trim(); 
    var percDesconto = linha.find("td.i_perc_desconto").text().trim();
    var vlrDesconto = linha.find("td.i_desconto").text().trim();
    var unidade = linha.find("td.i_unidade").text().trim();
    var markup = linha.find("td.i_markup").text().trim();
    var totalitem = linha.find("td.i_total").text().trim();    
    var dataEntrega = linha.find("td.i_data_entrega_td").text().trim();
    var custoCompra = linha.find("td.i_custo").text().trim();


    document.lancamento.nrItem.value = nrItem;
    document.lancamento.opcao_item.value = 'altera';
    $("#codProduto").val(codigoProduto);
    $("#codFabricante").val(codFabricante);
    $("#codProdutoNota").val(codNota);
    $("#descProduto").val(descricao);
    $("#quantidadeProduto").val(quantidade);
    $("#vlrUnitarioProduto").val(vlrUnitario);
    $("#percDescontoProduto").val(percDesconto);
    $("#vlrDescontoProduto").val(vlrDesconto);
    $("#totalProduto").val(totalitem);
    $("#markupItem").val(markup);
    $("#markupCusto").val(custoCompra);
    $("#uniProduto").val(unidade);
    $("#dataEntregaPeca").val(dataEntrega);
}


function limpaCamposProduto(){
    document.lancamento.codProduto.value = ''
    document.lancamento.codProdutoNota.value = ''
    document.lancamento.descProduto.value = ''
    document.lancamento.uniProduto.value  = ''
    document.lancamento.quantidadeProduto.value = ''
    document.lancamento.vlrUnitarioProduto.value = ''
    document.lancamento.percDescontoProduto.value = '' 
    document.lancamento.vlrDescontoProduto.value = ''
    document.lancamento.totalProduto.value = ''
    document.lancamento.nrItem.value = ''
    document.lancamento.opcao_item.value = ''
    document.lancamento.codFabricante.value = ''
    document.lancamento.dataEntregaPeca.value = ''
    document.lancamento.markupItem.value = ''
    $("#secaoEdicaoItem").slideUp();
    $("#listaEquivalencias").empty();
}


function cadastraProduto(){
    f = document.lancamento;
    var letra = 'registerProd' + '|' + 
                f.codFabricante.value + '|' + 
                f.codProdutoNota.value + '|' + 
                f.descProduto.value + '|' + 
                f.uniProduto.value + '|' +
                f.vlrUnitarioProduto.value;

    window.open("index.php?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&letra="+letra+"&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

/* fim cadastra produto */

/*inicio duplicar cotacao */
function submitDuplicarCotacao(numCotacao) {
    Swal.fire({
        title: 'Deseja realmente Duplicar esta Cotação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'duplica';
            f.numCotacao.value = numCotacao;
            f.submit();
        }
    });
}

/* fim duplicar cotacao */

/*inicio gerar pedido */
function submitGerarPedido(id) {
    Swal.fire({
        title: 'Deseja realmente Gerar um Pedido a partir desta Cotação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'gerar_pedido';
            f.numCotacao.value = id;
            f.submit();
        }
    });
}

/* fim gerar pedido */

/* inicio busca Info Produto */

function guardaValorAntCotacao(){
    localStorage.setItem("idCotacaoServico", document.getElementsByName("id")[0] ? document.getElementsByName("id")[0].value : '');
    localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);
}

function buscaProdutoAjax(){
    var codFabricante = document.lancamento.codFabricante.value;
    var pessoa = document.lancamento.pessoa ? document.lancamento.pessoa.value : document.lancamento.cliente.value;
    
    if(document.lancamento.pessoa.value == ''){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Cliente antes de fazer a pesquisa de Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(document.lancamento.condPgto.value == '' ){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione uma Condição de Pagamento antes de fazer a pesquisa de Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!codFabricante || codFabricante.trim() == '') {
        return;
    }
    
    if (codFabricante.trim().length < 3) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Digite pelo menos 3 caracteres para realizar a pesquisa.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    var $inputFabricante = $("#codFabricante");
    var $spinner = $("#spinnerPesquisa");
    $spinner.show();
    
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=cotacao&submenu=busca_produto&opcao=blank",
        data: {
            codFabricante: codFabricante.trim(),
            pessoa: pessoa
        },
        dataType: "json"
    }).done(function(response) {
        $spinner.hide();
        
        if (response && response.success && response.produto) {
            var produto = response.produto;
            var totalProdutos = response.totalProdutos || 0;
            var preencherAutomatico = response.preencherAutomatico || false;
            
            document.lancamento.codProduto.value = '';
            document.lancamento.codProdutoNota.value = '';
            document.lancamento.descProduto.value = '';
            document.lancamento.uniProduto.value = '';
            document.lancamento.vlrUnitarioProduto.value = '0,00';
            document.lancamento.percDescontoProduto.value = '0,00';
            document.lancamento.vlrDescontoProduto.value = '0,00';
            document.lancamento.totalProduto.value = '0,00';
            document.lancamento.markupCusto.value = '0,00';
            document.lancamento.markupItem.value = '0,00';
            
            if (response.htmlEquivalencias && response.htmlEquivalencias.trim() !== '') {
                exibirEquivalencias(response.htmlEquivalencias, produto, preencherAutomatico);
            } else {
                $("#secaoEdicaoItem").slideUp();
                $("#listaEquivalencias").empty();
            }
        } else {
            var urlPesquisa = document.URL + '?mod=est&form=produto&opcao=pesquisarpecas&from=cotacao&letra=||' + encodeURIComponent(codFabricante.trim());
            window.open(urlPesquisa, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
        }
    }).fail(function(xhr, status, error) {
        $("#spinnerPesquisa").hide();
        
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao buscar produto: ' + (error || 'Erro desconhecido'),
            confirmButtonText: 'OK'
        });
    });
}

function atualizarLinhaEquivalenciaSelecionada() {
    $("#listaEquivalencias tr.linhaEquivalenciaProduto").removeClass('info');
    $(".checkboxEquivalencia:checked").closest('tr.linhaEquivalenciaProduto').addClass('info');
}

function vincularCliqueLinhaEquivalencia() {
    $("#listaEquivalencias").off('click.linhaEquiv', 'tr.linhaEquivalenciaProduto');
    $("#listaEquivalencias").on('click.linhaEquiv', 'tr.linhaEquivalenciaProduto', function(e) {
        if ($(e.target).closest('input.checkboxEquivalencia').length) {
            return;
        }
        var $cb = $(this).find('.checkboxEquivalencia').first();
        if (!$cb.length) {
            return;
        }
        if (!$cb.prop('checked')) {
            $cb.prop('checked', true);
        }
        $cb.trigger('change');
    });

    $(".checkboxEquivalencia").off('click.linhaEquiv').on('click.linhaEquiv', function(e) {
        e.stopPropagation();
    });
}

function exibirEquivalencias(htmlEquivalencias, produtoPrincipal, preencherAutomatico) {
    if (!htmlEquivalencias || htmlEquivalencias.trim() === '') {
        $("#secaoEdicaoItem").slideUp();
        $("#listaEquivalencias").empty();
        return;
    }
    
    $("#listaEquivalencias").html(htmlEquivalencias);
    $("#secaoEdicaoItem").slideDown();
    vincularCliqueLinhaEquivalencia();
    
    if (preencherAutomatico === true) {
        var $checkboxMarcado = $(".checkboxEquivalencia:checked").first();
        if ($checkboxMarcado.length > 0) {
            setTimeout(function() {
                $checkboxMarcado.trigger('change');
            }, 100);
        }
    }
    
    $(".checkboxEquivalencia").off('change.linhaEquiv');
    
    $(".checkboxEquivalencia").on('change.linhaEquiv', function() {
        if ($(this).is(':checked')) {
            var codEquivalente = $(this).data('cod-equivalente') || $(this).data('cod-fabricante') || $(this).data('cod-produto') || '';
            var codProduto = $(this).data('cod-produto') || $(this).val() || '';
            var descricao = $(this).data('descricao') || '';
            var unidade = $(this).data('unidade') || '';
            var valorVenda = $(this).data('valor-venda') || '0,00';
            
            $(".checkboxEquivalencia").not(this).prop('checked', false);
            
            if (codEquivalente) {
                document.lancamento.codFabricante.value = codEquivalente ? codEquivalente : '';
                document.lancamento.codProdutoNota.value = codEquivalente ? codEquivalente : '';                
                document.lancamento.codProduto.value = codProduto ? codProduto : '';                
                document.lancamento.descProduto.value = descricao ? descricao : '';
                
                document.lancamento.uniProduto.value = unidade ? unidade : '';
                
                document.lancamento.vlrUnitarioProduto.value = valorVenda ? valorVenda : '0,00';
                
                document.lancamento.percDescontoProduto.value = '0,00';
                document.lancamento.vlrDescontoProduto.value = '0,00';
                document.lancamento.totalProduto.value = '0,00';

                if (codProduto) {
                    buscaInfoProduto(codProduto, true);
                }
            }
        } else {
            document.lancamento.codFabricante.value = '';
            document.lancamento.codProdutoNota.value = '';
            document.lancamento.codProduto.value = '';
            document.lancamento.descProduto.value = '';
            document.lancamento.uniProduto.value = '';
            document.lancamento.vlrUnitarioProduto.value = '0,00';
            document.lancamento.percDescontoProduto.value = '0,00';
            document.lancamento.vlrDescontoProduto.value = '0,00';
            document.lancamento.totalProduto.value = '0,00';
            document.lancamento.markupItem.value = '0,00';
            document.lancamento.markupCotacao.value = '0,00';
            
            $("#infoProduto").empty();
            $("#secaoInfoProduto").slideUp();
            $("#abaEstoqueEquiv").empty();
            $("#abaComprasEquiv").empty();
            $("#abaVendasEquiv").empty();
            $("#liEstoque").hide();
            $("#liCompras").hide();
            $("#liVendas").hide();
            $("#liEquivalencias").hide();
            
            var codProdutoDesmarcado = $(this).data('cod-produto') || $(this).val() || '';
            if (codProdutoDesmarcado) {
                $(".estoqueProduto[data-cod-produto='" + codProdutoDesmarcado + "']").html('<span class="text-muted" style="font-style: italic; font-size: 11px;">Selecione para mais informações</span>');
            }
        }
        atualizarLinhaEquivalenciaSelecionada();
    });

    atualizarLinhaEquivalenciaSelecionada();
}

function buscaInfoProduto(codProduto, dentroEquivalencias) {
    codProduto = String(codProduto || '').trim();
    if (codProduto === '') return;
    
    if (!dentroEquivalencias) return;
    
    var f = document.lancamento;
    var pessoa = f.pessoa ? f.pessoa.value : f.cliente.value;
    
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=cotacao&submenu=info_produto&opcao=blank",
        data: {
            codProduto: codProduto,
            pessoa: pessoa,
            vlrUnitarioProduto: f.vlrUnitarioProduto ? f.vlrUnitarioProduto.value : '',
            quantidadeProduto: f.quantidadeProduto ? f.quantidadeProduto.value : ''
        },
        dataType: "json"
    }).done(function(response) {
        if (!response.success || !response.htmlInfo) {
            limpaAbasEquivalencias();
            return;
        }
        
        var $htmlInfo = $('<div>').html(response.htmlInfo);
        
        var conteudoCompras = $htmlInfo.find('#abaCompras').html() || '';
        var conteudoVendas = $htmlInfo.find('#abaVendas').html() || '';
        var conteudoMarkup = $htmlInfo.find('#abaMarkup').html() || '';
        var conteudoEquivalencias = $htmlInfo.find('#abaEquivalencias').html() || '';
        $("#abaComprasEquiv").html(conteudoCompras);
        $("#abaVendasEquiv").html(conteudoVendas);
        $("#abaMarkupEquiv").html(conteudoMarkup);
        $("#abaEquivalencias").html(conteudoEquivalencias);

        if (response.quantidadeDisponivel !== undefined) {
            var qtdFormatada = parseFloat(response.quantidadeDisponivel || 0).toFixed(2).replace('.', ',');
            $(".estoqueProduto[data-cod-produto='" + codProduto + "']").html('<strong>' + qtdFormatada + '</strong>');
        }
        if (response.custo !== '0,00') {
            document.lancamento.markupCusto.value = response.custo;
        } else {
            document.lancamento.markupCusto.value = '0,00';
        }
        
        if (response.markup !== '0,00') {
            document.lancamento.markupItem.value = response.markup;
        } else {
            document.lancamento.markupItem.value = '0,00';
        }
        
        $("#liCompras").toggle(conteudoCompras.trim() !== '');
        $("#liVendas").toggle(conteudoVendas.trim() !== '');
        $("#liMarkup").toggle(conteudoMarkup.trim() !== '' && $(conteudoMarkup).find('table').length > 0);
        $("#liEquivalencias").toggle(conteudoEquivalencias !== '');

    }).fail(function(xhr, status, error) {
        console.error('Erro ao buscar informações do produto:', error);
        limpaAbasEquivalencias();
    });
}

function limpaAbasEquivalencias() {
    $("#abaComprasEquiv, #abaVendasEquiv, #abaMarkupEquiv, #abaEquivalencias").empty();
    // Não esconder mais o item de aba "Equivalências" — ele deve sempre estar visível
    $("#liCompras, #liVendas, #liMarkup").hide();
}

/* fim busca Info Produto */

/* inicio modal Aplicar Percentual */

function abrirModalAplicarPercentual(idCotacao) {
    if (document.lancamento.id.value == '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Cotação não informada.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    $('#percentualAplicar').val('');
    $('#modalAplicarPercentual').data('idCotacao', idCotacao);
    $('#modalAplicarPercentual').modal('show');
}

function aplicarPercentualItens() {
    var f = document.lancamento;
    var idCotacao = $('#modalAplicarPercentual').data('idCotacao');
    var percentual = $('#percentualAplicar').val();
    
    if (idCotacao == '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'ID da cotação não informado.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    if (percentual == '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Informe o percentual a ser aplicado.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    percentual = percentual.replace(',', '.');
    percentual = parseFloat(percentual);
    
    if (isNaN(percentual)) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Percentual inválido. Informe um valor numérico.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Aplicar Percentual?',
        text: 'Deseja aplicar ' + percentual + '% em todos os itens da cotação?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, Aplicar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#modalAplicarPercentual').modal('hide');
            f.numCotacao.value = idCotacao;
            f.id.value = idCotacao;
            f.percentualAplicar.value = percentual;
            f.submenu.value = 'aplicar_percentual';
            f.submit();
        }
    });
}
/* fim modal Aplicar Percentual */

/* inicio modal Atualizar Markup */

function atualizarmarkup() {
    f = document.lancamento;
    var markupCotacao = f.markupCotacao.value;
    var numCotacao = f.id.value;
    Swal.fire({
        title: 'Atualizar Markup?',
        text: 'Deseja atualizar o markup da cotação?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, Atualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "index.php?mod=ped&form=cotacao&submenu=atualizar_markup&opcao=blank",
                data: {
                    markupCotacao: markupCotacao,
                    numCotacao: numCotacao
                },
                dataType: "json"
            }).done(function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message || 'Markup atualizado com sucesso!',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.error || 'Erro ao atualizar markup.',
                    });
                }
            }).fail(function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Erro ao atualizar markup. Tente novamente.',
                });
            });
        }
    });
}


function calculaValorUnitarioPorMarkup() {
    var f = document.lancamento;
    var custoStr = f.markupCusto ? f.markupCusto.value : '0,00';
    var markupStr = f.markupItem ? f.markupItem.value : '0,00';
    
    var custo = parseFloat(custoStr.replace(/\./g, '').replace(',', '.'));
    var markup = parseFloat(markupStr.replace(/\./g, '').replace(',', '.'));
    

    if (isNaN(custo)) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'O custo do produto precisa ser informado e maior que zero para calcular o markup.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    if (isNaN(markup)) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Informe um markup válido (percentual entre 0 e 100).',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    if (markup >= 100) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Markup inválido. O markup deve ser menor que 100%.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    var markupDecimal = markup / 100;
    var divisor = 1 - markupDecimal;
    
    if (divisor <= 0) {
        return false;
    }
    
    var vlrUnitario = custo / divisor;
    
    var vlrUnitarioFormatado = currencyFormat(vlrUnitario);
    
    if (f.vlrUnitarioProduto) {
        f.vlrUnitarioProduto.value = vlrUnitarioFormatado;
        
        if (f.quantidadeProduto && f.quantidadeProduto.value && f.quantidadeProduto.value !== '' && f.quantidadeProduto.value !== '0,00') {
            calculaTotalItens('', 'produto');
        }
    }
    
    return true;
}


/* inicio modal CC */

function setaDadosCotacao() {
    var f = document.lancamento;
    if (f.pessoa.value == "") {
      Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: 'Selecione um Cliente antes de usar Copiar e Colar.',
        confirmButtonText: 'OK'
      });
      return false;
    }
    if (f.condPgto.value == "") {
      Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: 'Selecione uma Condição de Pagamento antes de usar Copiar e Colar.',
        confirmButtonText: 'OK'
      });
      return false;
    }
    
    $('#desc_cc_modal').val("");
    $("form[name=lancamento]").find('input[name=desc_cc]').val("");
    $("form[name=lancamento]").find('input[name=pesq_cc]').val("");
    
    $('#modalCC').modal('show');
    
    return true;
  }
  
  function removerEspaco(string) {
    return string.replace(/^\s+|\s+$/g, "");
  }
  
  function submitModalCC(itens) {
    
    let numLinhas = document.getElementById("datatable").rows.length;
    if (numLinhas <= 1) {
      Swal.fire({
        icon: 'warning',
        title: 'Aviso!',
        text: 'Faça a Pesquisa antes de importar dados.',
        confirmButtonText: 'OK'
      });
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
    f.itensPedidoCC.value = produtos;
    f.submenu.value = "cadastrarItemCC";
    
    // Garante que o ID da cotação seja enviado (se existir)
    var idValue = document.lancamento.id;
    var numCotacaoInput = $("form[name=lancamento]").find('input[name=numCotacao]');
    f.submit();
  }
  
  function submitLetraModalCC() {
    var descCc = $('#desc_cc_modal').val();
  
    if (descCc == "" || descCc == null || descCc.trim() == "") {
      Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: 'Preencha o campo para a pesquisa.',
        confirmButtonText: 'OK'
      });
      return false;
    }
  
    var form = $("form[name=lancamento]");
  
    form.find('input[name=desc_cc]').val(descCc); 
    form.find('input[name=submenu]').val('copiar_colar');
    
    var idValue = document.lancamento.id;
    var numCotacaoInput = form.find('input[name=numCotacao]');
    
    // Mostra loading
    Swal.fire({
      title: 'Pesquisando...',
      text: 'Aguarde enquanto buscamos os produtos.',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
  
    $.ajax({
      type: "POST",
      url: form.attr('action') ? form.attr('action') : document.URL,
      data: form.serialize(),
      dataType: "text",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Ajax-Request", "true");
      },
      success: function (response) {
        // Fecha o loading
        Swal.close();
        
        var msgs_modal = $("<div />")
          .append(response)
          .find("#content_msg")
          .html();
        $("#content_msg").html(msgs_modal);
        var result = $("<div />").append(response).find("#datatable").html();
        $("#datatable").html(result);
      },
      error: function(xhr, status, error) {
        // Fecha o loading
        Swal.close();
        
        console.error('Erro no AJAX:', error);
        Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: 'Erro ao processar a pesquisa. Tente novamente.',
          confirmButtonText: 'OK'
        });
      }
    });
    return false;
  }
  
  function limpaModalCC() {
    $('#desc_cc_modal').val("");
    $("form[name=lancamento]").find('input[name=desc_cc]').val("");
    $("#content_msg").empty();
    $("#datatable tbody").empty();
  }
/* fim modal CC */  

/* inicio funcoes adicionais para a cotação */

function submitImprimir(id) {
    window.open("index.php?mod=ped&form=rel_pedido_ps&submenu=imprimir&opcao=imprimir&id="+id,
        "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

function submitEnviarEmail(id) {
    Swal.fire({
        title: 'Enviar Cotação por Email?',
        text: 'Deseja enviar a cotação N° ' + id + ' por email para o cliente?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, Enviar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Enviando...',
                text: 'Aguarde enquanto enviamos o email.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: "POST",
                url: "index.php?mod=ped&form=cotacao&submenu=enviar_email&opcao=blank",
                data: {
                    numCotacao: id
                },
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Ajax-Request", "true");
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Enviado!',
                            text: response.message || 'Cotação enviada por email com sucesso!',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: response.message || 'Erro ao enviar email.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao enviar email: ' + error,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}



function submitDownloadPdf(id) {
    Swal.fire({
        title: 'Gerando PDF...',
        text: 'Aguarde enquanto o PDF é gerado.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Cria um formulário temporário para fazer o download
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?mod=ped&form=cotacao&submenu=download_pdf&opcao=blank';
    // Remove target para não abrir nova janela
    
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'numCotacao';
    input.value = id;
    form.appendChild(input);
    
    document.body.appendChild(form);
    form.submit();
    
    // Fecha o loading após um tempo
    setTimeout(function() {
        Swal.close();
    }, 1000);
}
/* fim funcoes adicionais para a cotação */