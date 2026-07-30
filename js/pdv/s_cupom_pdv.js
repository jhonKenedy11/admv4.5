function pdvAplicaResumoHtml(response) {
    if (response.indexOf('imgLogin') !== -1 || response.indexOf('Sistema de Informa') !== -1) {
        Swal.fire({ icon: 'warning', title: 'Sessão', text: 'Sessão expirada ou rota inválida. Atualize a página (F5).' });
        return false;
    }
    var wrap = $('<div />').append(response);
    if (!wrap.find('#pdvResumoBloco').length) {
        Swal.fire({ icon: 'error', title: 'Erro', text: wrap.text().trim() || 'Erro ao atualizar o resumo.' });
        return false;
    }
    $('#pdvResumoBloco').html(wrap.find('#pdvResumoBloco').html());
    var pedidoId = wrap.find('#pdvResumoSyncId').text().trim();
    document.getElementById('idPedido').value = pedidoId;
    document.getElementById('clienteHidden').value = wrap.find('#pdvResumoSyncCliente').text();
    document.getElementById('totalPedidoFixo').value = wrap.find('#pdvResumoSyncTotal').text();
    document.getElementById('badgeQtdItens').textContent = wrap.find('#pdvResumoSyncQtd').text();
    return true;
}

function pdvMsgErroResumo(response) {
    if (!response) {
        return '';
    }
    var wrap = $('<div />').append(response);
    var msg = wrap.find('.alert-danger').first().text().trim();
    if (msg) {
        return msg;
    }
    try {
        var j = JSON.parse(response);
        if (j && j.message) {
            return j.message;
        }
    } catch (e) { /* não é JSON */ }
    return '';
}

function pdvPostResumo(submenu, dados, callback) {
    f = document.lancamento;
    dados = dados || {};
    dados.mod = f.mod.value;
    dados.form = f.form.value;
    dados.submenu = submenu;
    dados.opcao = 'blank';
    dados.id = document.getElementById('idPedido').value;

    $.ajax({
        type: 'POST',
        url: f.action + '?mod=' + encodeURIComponent(f.mod.value) + '&form=' + encodeURIComponent(f.form.value)
            + '&submenu=' + encodeURIComponent(submenu) + '&opcao=blank',
        data: dados,
        dataType: 'text',
        success: function (response) {
            var msgErro = pdvMsgErroResumo(response);
            if (msgErro) {
                Swal.fire({ icon: 'error', title: 'Erro', text: msgErro });
                return;
            }
            if (pdvAplicaResumoHtml(response) && callback) {
                callback();
            }
        },
        error: function (xhr) {
            var msg = pdvMsgErroResumo(xhr.responseText);
            if (!msg && xhr.responseText && pdvAplicaResumoHtml(xhr.responseText)) {
                if (callback) {
                    callback();
                }
                return;
            }
            if (!msg && xhr.responseText) {
                var texto = $('<div />').append(xhr.responseText).text().trim();
                if (texto && texto.length < 500) {
                    msg = texto;
                }
            }
            Swal.fire({ icon: 'error', title: 'Erro', text: msg || 'Erro de comunicação.' });
        }
    });
}

function pdvBuscaProduto(silent, autoUnico) {
    var termo = document.getElementById('termoProduto');
    var lista = document.getElementById('listaProdutos');
    var val = termo.value.trim();

    if (val.length < 3 && !/^\d+$/.test(val)) {
        pdvOcultarListaProdutos();
        if (document.getElementById('pdvEditNrItem').value === '') {
            document.getElementById('pdvPainelItem').style.display = 'none';
            document.getElementById('pdvSelCodigo').value = '';
        }
        return;
    }

    document.getElementById('spinnerBusca').style.display = 'table-cell';
    if (termo.pdvXhr) {
        termo.pdvXhr.abort();
    }

    f = document.lancamento;
    termo.pdvXhr = $.post(
        f.action + '?mod=pdv&form=cupom&submenu=busca_produto&opcao=blank',
        { termo: val, codFabricante: val, submenu: 'busca_produto', opcao: 'blank' },
        function (res) {
            document.getElementById('spinnerBusca').style.display = 'none';
            if (autoUnico && res.autoIncluir && res.itens && res.itens.length === 1) {
                pdvSelecionarProduto(res.itens[0]);
                termo.value = '';
                termo.focus();
                return;
            }
            if (!res.itens || !res.itens.length) {
                if (silent) {
                    lista.innerHTML = '<div class="alert alert-info" style="margin:0;padding:8px;">' + (res.message || 'Nenhum produto encontrado.') + '</div>';
                    lista.className = 'pdv-lista-visivel';
                } else {
                    Swal.fire({ icon: 'info', title: 'Pesquisa', text: res.message || 'Produto não localizado.' });
                }
                return;
            }

            var html = '';
            if (res.total >= 50) {
                html += '<div class="alert alert-info" style="margin:0 0 6px;padding:8px;"><strong>Atenção!</strong> Refine a pesquisa (50+ itens).</div>';
            }
            html += '<div class="pdv-tabela-produtos"><table class="table table-bordered table-condensed jambo_table" style="margin-bottom:0;"><thead><tr>' +
                '<th></th><th>Cód.</th><th>Fabric.</th><th>Descrição</th><th class="text-right">Venda</th></tr></thead><tbody>';
            res.itens.forEach(function (p) {
                html += '<tr class="pdv-linha-produto" data-codigo="' + p.codigo + '" data-descricao="' + (p.descricao || '') + '" data-unidade="' + (p.unidade || '') + '" data-promocao="' + (p.promocao || '') + '" data-venda="' + (p.venda || '') + '">' +
                    '<td><i class="fa fa-hand-pointer-o text-primary"></i></td>' +
                    '<td>' + p.codigo + '</td><td>' + (p.codFabricante || '') + '</td><td>' + (p.descricao || '') + '</td>' +
                    '<td class="text-right">R$ ' + (p.venda || '') + '</td></tr>';
            });
            html += '</tbody></table></div>';
            lista.innerHTML = html;
            lista.className = 'pdv-lista-visivel';
            lista.querySelectorAll('.pdv-linha-produto').forEach(function (el) {
                el.onmousedown = function (ev) {
                    ev.preventDefault();
                    pdvSelecionarProduto({
                        codigo: el.getAttribute('data-codigo'),
                        descricao: el.getAttribute('data-descricao'),
                        unidade: el.getAttribute('data-unidade'),
                        promocao: el.getAttribute('data-promocao'),
                        venda: el.getAttribute('data-venda')
                    });
                };
            });
        },
        'json'
    ).fail(function (xhr, status) {
        if (status === 'abort') {
            return;
        }
        document.getElementById('spinnerBusca').style.display = 'none';
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Erro na busca de produto.' });
    });
}

function pdvParseNumero(val) {
    var s = String(val || '').trim();
    if (s === '') {
        return 0;
    }
    if (s.indexOf(',') !== -1) {
        return parseFloat(s.replace(/\./g, '').replace(',', '.')) || 0;
    }
    return parseFloat(s) || 0;
}

function pdvFormatQtde(val) {
    var n = typeof val === 'number' ? val : pdvParseNumero(val);
    return n.toFixed(3).replace('.', ',');
}

function pdvFormatMoeda(val) {
    var n = typeof val === 'number' ? val : pdvParseNumero(val);
    return n.toFixed(2).replace('.', ',');
}

function pdvSalvaDescontoFrete() {
    var elDesc = document.getElementById('pdvDesconto');
    var elFrete = document.getElementById('pdvFrete');
    if (!elDesc || !elFrete) {
        return;
    }
    pdvPostResumo('salvaDescontoFrete', {
        desconto: elDesc.value,
        frete: elFrete.value
    });
}

/** Quantidade para POST: ponto decimal, sem separador de milhar (ex.: 1.000). */
function pdvQuantidadeParaEnvio() {
    var n = pdvParseNumero(document.getElementById('pdvItemQuantidade').value);
    if (n <= 0) {
        n = 1;
    }
    return n.toFixed(3);
}

function pdvOcultarListaProdutos() {
    var lista = document.getElementById('listaProdutos');
    if (!lista) {
        return;
    }
    lista.innerHTML = '';
    lista.className = '';
}

function pdvLimparModoItem() {
    document.getElementById('pdvEditNrItem').value = '';
    document.getElementById('pdvSelCodigo').value = '';
    var painel = document.getElementById('pdvPainelItem');
    painel.style.display = 'none';
    painel.classList.remove('pdv-painel-edicao');
    document.getElementById('pdvPainelItemTitulo').textContent = 'Confirmar item';
    var btnOk = document.getElementById('btnPdvConfirmarItem');
    if (btnOk) {
        btnOk.title = 'Confirmar item';
        btnOk.setAttribute('aria-label', 'Confirmar item');
    }
}

function pdvAtualizarModoPainelItem() {
    var editando = document.getElementById('pdvEditNrItem').value !== '';
    var painel = document.getElementById('pdvPainelItem');
    var btnOk = document.getElementById('btnPdvConfirmarItem');
    if (editando) {
        painel.classList.add('pdv-painel-edicao');
        document.getElementById('pdvPainelItemTitulo').textContent = 'Editar item';
        if (btnOk) {
            btnOk.title = 'Salvar alterações';
            btnOk.setAttribute('aria-label', 'Salvar alterações');
        }
    } else {
        painel.classList.remove('pdv-painel-edicao');
        document.getElementById('pdvPainelItemTitulo').textContent = 'Confirmar item';
        if (btnOk) {
            btnOk.title = 'Confirmar item';
            btnOk.setAttribute('aria-label', 'Confirmar item');
        }
    }
}

function pdvSelecionarProduto(prod) {
    var termo = document.getElementById('termoProduto');
    clearTimeout(termo.pdvTimer);
    if (termo.pdvXhr) {
        termo.pdvXhr.abort();
    }

    document.getElementById('pdvEditNrItem').value = '';
    document.getElementById('pdvSelCodigo').value = prod.codigo;
    document.getElementById('pdvItemCodigo').textContent = prod.codigo;
    document.getElementById('pdvItemDescricao').textContent = prod.descricao || '';
    document.getElementById('pdvItemUnidade').value = prod.unidade || '';
    document.getElementById('pdvItemQuantidade').value = '1';

    var promo = parseFloat(String(prod.promocao || '0').replace(/\./g, '').replace(',', '.')) || 0;
    var venda = parseFloat(String(prod.venda || '0').replace(/\./g, '').replace(',', '.')) || 0;
    var preco = promo > 0 ? promo : venda;
    document.getElementById('pdvItemUnitario').value = preco.toFixed(2).replace('.', ',');
    var lista = document.getElementById('listaProdutos');
    if (lista) {
        lista.querySelectorAll('.pdv-linha-produto').forEach(function (linha) {
            linha.classList.toggle('pdv-linha-selecionada', linha.getAttribute('data-codigo') === String(prod.codigo));
        });
    }
    document.getElementById('pdvPainelItem').style.display = 'block';
    pdvAtualizarModoPainelItem();
    pdvCalculaTotalItemPainel();
    document.getElementById('pdvItemQuantidade').focus();
}

function pdvEditarItem(el) {
    document.getElementById('pdvEditNrItem').value = el.getAttribute('data-nritem') || '';
    document.getElementById('pdvSelCodigo').value = el.getAttribute('data-codigo') || '';
    document.getElementById('pdvItemCodigo').textContent = el.getAttribute('data-codigo') || '';
    document.getElementById('pdvItemDescricao').textContent = el.getAttribute('data-descricao') || '';
    document.getElementById('pdvItemUnidade').value = el.getAttribute('data-unidade') || '';

    document.getElementById('pdvItemQuantidade').value = pdvFormatQtde(el.getAttribute('data-quantidade') || '1');

    var unit = String(el.getAttribute('data-unitario') || '0');
    document.getElementById('pdvItemUnitario').value = unit;

    pdvOcultarListaProdutos();
    document.getElementById('pdvPainelItem').style.display = 'block';
    pdvAtualizarModoPainelItem();
    pdvCalculaTotalItemPainel();
    document.getElementById('pdvItemQuantidade').focus();
    document.getElementById('pdvItemQuantidade').select();
}

function pdvCalculaTotalItemPainel() {
    var qtd = pdvParseNumero(document.getElementById('pdvItemQuantidade').value);
    var unit = pdvParseNumero(document.getElementById('pdvItemUnitario').value);
    document.getElementById('pdvItemTotal').value = (qtd * unit).toFixed(2).replace('.', ',');
}

function pdvConfirmarItemSelecionado() {
    var nrEdit = document.getElementById('pdvEditNrItem').value;
    var submenu = nrEdit !== '' ? 'altera_item_ajax' : 'inclui_item_ajax';
    var dados = {
        quantidade: pdvQuantidadeParaEnvio(),
        unitario: document.getElementById('pdvItemUnitario').value
    };
    if (nrEdit !== '') {
        dados.nrItem = nrEdit;
    } else {
        dados.codigo = document.getElementById('pdvSelCodigo').value;
        if (!dados.codigo) {
            Swal.fire({ icon: 'warning', title: 'Item', text: 'Selecione um produto na lista antes de confirmar.' });
            return;
        }
    }
    pdvPostResumo(submenu, dados, function () {
        if (submenu === 'inclui_item_ajax' && document.getElementById('idPedido').value && document.lancamento) {
            document.lancamento.submenu.value = 'alterar';
        }
        document.getElementById('termoProduto').value = '';
        pdvOcultarListaProdutos();
        document.getElementById('termoProduto').focus();
        pdvLimparModoItem();
    });
}

function pdvExcluirItem(nrItem) {
    Swal.fire({
        icon: 'question',
        title: 'Remover item',
        text: 'Remover este item do cupom?',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (r) {
        if (!r.isConfirmed) {
            return;
        }
        pdvPostResumo('exclui_item_ajax', { nrItem: nrItem });
    });
}

function pdvAbrirModalEmitir() {
    var idPedido = document.getElementById('idPedido').value;
    $('#modalCupomPdvTitulo').text('Emitir cupom fiscal — #' + idPedido);
    $('#btnPdvModalEmitir').hide();
    $('#modalCupomPdvBody').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i></p>');
    $('#modalCupomPdv').modal('show');

    f = document.lancamento;
    $.post(
        f.action + '?mod=pdv&form=cupom&submenu=resumoCupomPdv&opcao=blank',
        { id: idPedido, cliente: document.getElementById('clienteHidden').value },
        function (html) {
            $('#modalCupomPdvBody').html(html);
            if (!$('#modalCupomPdvBody iframe').length && $('#cupomGerenteFlags').attr('data-ja-cpm') !== '1') {
                $('#btnPdvModalEmitir').show();
            }
            if (typeof cupomGerenteToggleTroco === 'function') {
                cupomGerenteToggleTroco();
            }
        }
    ).fail(function () {
        $('#modalCupomPdvBody').html('<div class="alert alert-danger">Não foi possível carregar.</div>');
    });
}

function pdvModalEmitirNfce() {
    Swal.fire({
        icon: 'question',
        title: 'Emitir NFC-e',
        text: 'Confirmar emissão?',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (r) {
        if (!r.isConfirmed) {
            return;
        }
        var fd = new FormData(document.getElementById('formCupomGerente'));
        fd.set('submenu', 'cadastraNf');
        fd.set('opcao', 'blank');
        var elDesc = document.getElementById('pdvDesconto');
        var elFrete = document.getElementById('pdvFrete');
        if (elDesc) {
            fd.set('desconto', elDesc.value);
        }
        if (elFrete) {
            fd.set('frete', elFrete.value);
        }
        var chkTroco = document.getElementById('temTroco');
        if (!chkTroco || !chkTroco.checked) {
            fd.set('temTroco', '0');
            fd.set('valorPago', document.getElementById('totalPedidoFixo').value);
        }

        f = document.lancamento;
        $.ajax({
            type: 'POST',
            url: f.action,
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function (res) {
            if (res.success) {
                $('#modalCupomPdv').modal('hide');
                document.getElementById('btnEmitirCupom').disabled = true;
                var idNf = res.idNf || 0;
                if (idNf) {
                    window.open(
                        'index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id=' + idNf,
                        'DANFCE',
                        'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes'
                    );
                }
                window.location.href = 'index.php?mod=pdv&form=cupom';
                return;
            }
            var idPed = res.pedidoId || document.getElementById('idPedido').value;
            if (idPed && parseInt(idPed, 10) > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Falha na emissão',
                    text: res.message || 'Falha na emissão.',
                    showCancelButton: true,
                    confirmButtonText: 'Abrir na gerência',
                    cancelButtonText: 'Fechar'
                }).then(function (s) {
                    if (s.isConfirmed) {
                        window.location.href = f.action + '?mod=ped&form=pedido_venda_gerente_novo&id=' + idPed;
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Falha na emissão', text: res.message || 'Falha na emissão.' });
            }
        }).fail(function (xhr) {
            var msg = 'Erro de comunicação.';
            try {
                var j = JSON.parse(xhr.responseText);
                if (j.message) {
                    msg = j.message;
                }
            } catch (e) { /* ignore */ }
            Swal.fire({ icon: 'error', title: 'Erro', text: msg });
        });
    });
}

function pdvListaNovo() {
    var f = document.lancamento;
    f.submenu.value = 'cadastro';
    f.id.value = '';
    f.submit();
}

function pdvListaEditar(id) {
    var f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
}

function pdvListaExcluir(id) {
    if (!confirm('Excluir este cupom PDV e todos os itens?')) {
        return;
    }
    f = document.lancamento;
    f.id.value = id;
    f.submenu.value = 'excluirPdv';
    f.submit();
}

function pdvNovoCupom() {
    Swal.fire({
        icon: 'question',
        title: 'Novo cupom',
        text: 'Iniciar um novo cupom?',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (r) {
        if (!r.isConfirmed) {
            return;
        }
        f = document.lancamento;
        $.post(
            f.action + '?mod=pdv&form=cupom&submenu=novoCupom&opcao=blank',
            { id: document.getElementById('idPedido').value },
            function (res) {
                f.submenu.value = 'cadastro';
                f.id.value = '';
                if (res.redirect) {
                    window.location.href = res.redirect;
                } else {
                    f.submit();
                }
            },
            'json'
        ).fail(function () {
            f.submenu.value = 'cadastro';
            f.id.value = '';
            f.submit();
        });
    });
}

$(document).ready(function () {
    $('#pdvResumoBloco').on('click', '.btn-excluir-item', function () {
        pdvExcluirItem($(this).data('nritem'));
    });

    $('#pdvResumoBloco').on('click', '.btn-editar-item', function () {
        pdvEditarItem(this);
    });

    $('#clientePdv').on('select2:select select2:clear', function (e) {
        var clienteId = e.type === 'select2:clear' ? '' : e.params.data.id;
        pdvPostResumo('salvaCliente', {
            cliente: clienteId,
            id: document.getElementById('idPedido').value
        });
    });

    $('#pdvResumoBloco').on('blur', '#pdvDesconto, #pdvFrete', function () {
        this.value = pdvFormatMoeda(this.value);
        pdvSalvaDescontoFrete();
    });

    $('#pdvResumoBloco').on('keydown', '#pdvDesconto, #pdvFrete', function (ev) {
        if (ev.key === 'Enter') {
            ev.preventDefault();
            this.blur();
        }
    });

    var termo = document.getElementById('termoProduto');
    if (termo) {
        termo.oninput = function () {
            if (document.getElementById('pdvEditNrItem').value === '') {
                document.getElementById('pdvPainelItem').style.display = 'none';
                document.getElementById('pdvSelCodigo').value = '';
            }
            clearTimeout(termo.pdvTimer);
            var val = termo.value.trim();
            if (val.length < 3 && !/^\d+$/.test(val)) {
                pdvOcultarListaProdutos();
                return;
            }
            termo.pdvTimer = setTimeout(function () {
                pdvBuscaProduto(true, false);
            }, 350);
        };
        termo.onkeydown = function (ev) {
            if (ev.key === 'Enter') {
                ev.preventDefault();
                clearTimeout(termo.pdvTimer);
                pdvBuscaProduto(false, true);
            }
        };
    }

    if (document.getElementById('pdvItemQuantidade')) {
        document.getElementById('pdvItemQuantidade').oninput = pdvCalculaTotalItemPainel;
        document.getElementById('pdvItemUnitario').oninput = pdvCalculaTotalItemPainel;
        document.getElementById('pdvItemQuantidade').onkeydown = function (ev) {
            if (ev.key === 'Enter') {
                pdvConfirmarItemSelecionado();
            }
        };
        document.getElementById('pdvItemUnitario').onkeydown = function (ev) {
            if (ev.key === 'Enter') {
                pdvConfirmarItemSelecionado();
            }
        };
        document.getElementById('btnPdvConfirmarItem').onclick = pdvConfirmarItemSelecionado;
        document.getElementById('btnPdvCancelarItem').onclick = function () {
            pdvLimparModoItem();
            document.getElementById('termoProduto').focus();
        };
    }
});
