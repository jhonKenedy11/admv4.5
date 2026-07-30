/**
 * Modal de resumo do cupom (NFC-e) na Gerência de Pedidos.
 */

/** BR: 420,00 | BD: 420.00 | inteiro: 420 */
function cupomParseMoeda(val) {
    var s = String(val || '').trim();
    if (s === '') {
        return 0;
    }
    if (s.indexOf(',') !== -1) {
        return parseFloat(s.replace(/\./g, '').replace(',', '.')) || 0;
    }
    return parseFloat(s) || 0;
}

function cupomGerenteToggleTroco() {
    var chk = document.getElementById('temTroco');
    var bloco = document.getElementById('cupomGerenteTrocoBloco');
    var valorPago = document.getElementById('valorPago');
    var totalFixo = document.getElementById('totalPedidoFixo');
    if (!chk || !bloco) {
        return;
    }
    if (chk.checked) {
        bloco.style.display = '';
        if (valorPago && totalFixo && (!valorPago.value || valorPago.value === totalFixo.value)) {
            valorPago.value = totalFixo.value;
        }
        calculaTotalCupomGerente();
    } else {
        bloco.style.display = 'none';
        if (valorPago && totalFixo) {
            valorPago.value = totalFixo.value;
        }
        var trocoMostra = document.getElementById('trocoMostra');
        if (trocoMostra) {
            trocoMostra.value = '0,00';
        }
    }
}

function calculaTotalCupomGerente() {
    var chk = document.getElementById('temTroco');
    if (chk && !chk.checked) {
        return;
    }
    var f = document.getElementById('formCupomGerente');
    if (!f || !f.valorPago) {
        return;
    }
    var valorPago = f.valorPago.value;
    var totalFixo = document.getElementById('totalPedidoFixo');
    var totalCupomStr = totalFixo ? totalFixo.value : '0,00';
    var totalCupom = cupomParseMoeda(totalCupomStr);
    var recebido = cupomParseMoeda(valorPago);
    var troco = Math.max(0, recebido - totalCupom);
    var trocoFmt = troco.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    var trocoMostra = document.getElementById('trocoMostra');
    if (trocoMostra) {
        trocoMostra.value = trocoFmt;
    }
}

function cupomGerenteVoltar() {
    var f = document.getElementById('lancamento') || document.lancamento;
    if (!f) {
        window.location.href = 'index.php?mod=ped&form=pedido_venda_gerente_novo';
        return;
    }
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = '';
    if (f.opcao) {
        f.opcao.value = '';
    }
    if (f.id) {
        f.id.value = '';
    }
    f.submit();
}

function cupomGerenteUrl() {
    return 'index.php?mod=pdv&form=cupom&opcao=blank';
}

function cupomGerenteAtualizaTitulo(idCupom) {
    var titulo = document.getElementById('modalCupomFiscalTitulo');
    if (!titulo) {
        return;
    }
    titulo.textContent = idCupom
        ? 'Cupom fiscal — ' + idCupom
        : 'Cupom fiscal';
}

function cupomGerenteAtualizaBtnEditarPedido(visivel) {
    var btnEditar = document.getElementById('btnCupomEditarPedido');
    if (btnEditar) {
        btnEditar.style.display = visivel ? 'inline-block' : 'none';
    }
}

function cupomGerenteEditarPedidoNovaAba() {
    var fPs = document.getElementById('formCupomGerentePedidoPs');
    if (!fPs || !String(fPs.id.value || '').trim()) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Pedido não identificado.' });
        }
        return;
    }
    fPs.target = '_blank';
    fPs.submit();
}

function cupomGerenteAtualizaBotoes(jaCpm, temDanfe) {
    var btnNfce = document.getElementById('btnCupomNfce');
    if (!btnNfce) {
        return;
    }
    if (temDanfe) {
        btnNfce.style.display = 'none';
        return;
    }
    btnNfce.style.display = jaCpm ? 'none' : 'inline-block';
}

function abrirModalCupomFiscal(idPedido) {
    var $modal = $('#modalCupomFiscal');
    var $body = $('#modalCupomFiscalBody');
    cupomGerenteAtualizaTitulo(idPedido);
    $body.html('<p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Carregando...</p>');
    cupomGerenteAtualizaBtnEditarPedido(false);
    cupomGerenteAtualizaBotoes(true, false);
    $modal.modal('show');

    $.ajax({
        type: 'POST',
        url: cupomGerenteUrl(),
        data: { submenu: 'resumoCupomGerente', id: idPedido, opcao: 'gerente' },
        success: function (html) {
            $body.html(html);
            var temForm = document.getElementById('formCupomGerente');
            if (temForm) {
                var flags = document.getElementById('cupomGerenteFlags');
                var idCupom = flags ? flags.getAttribute('data-id') : '';
                if (!idCupom) {
                    idCupom = idPedido;
                }
                cupomGerenteAtualizaTitulo(idCupom);
                var jaCpm = flags && flags.getAttribute('data-ja-cpm') === '1';
                var temDanfe = $body.find('iframe').length > 0;
                cupomGerenteAtualizaBotoes(jaCpm, temDanfe);
                cupomGerenteAtualizaBtnEditarPedido(true);
                cupomGerenteToggleTroco();
            } else {
                cupomGerenteAtualizaTitulo(idPedido);
                var btnNfce = document.getElementById('btnCupomNfce');
                if (btnNfce) {
                    btnNfce.style.display = 'none';
                }
                cupomGerenteAtualizaBtnEditarPedido(false);
            }
        },
        error: function () {
            $body.html('<div class="alert alert-danger">Não foi possível carregar o resumo do pedido.</div>');
            cupomGerenteAtualizaBtnEditarPedido(false);
        }
    });
}

function cupomGerenteEmitir(submenu) {
    var f = document.getElementById('formCupomGerente');
    if (!f) {
        return;
    }
    var fd = new FormData(f);
    fd.set('submenu', submenu || 'cadastraNf');
    var chkTroco = document.getElementById('temTroco');
    var totalFixo = document.getElementById('totalPedidoFixo');
    if (chkTroco && chkTroco.checked) {
        fd.set('temTroco', '1');
        calculaTotalCupomGerente();
    } else {
        fd.set('temTroco', '0');
        if (totalFixo) {
            fd.set('valorPago', totalFixo.value);
        }
    }

    var btnNfce = document.getElementById('btnCupomNfce');
    if (btnNfce) {
        btnNfce.disabled = true;
    }

    $.ajax({
        type: 'POST',
        url: cupomGerenteUrl(),
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (res) {
            if (btnNfce) {
                btnNfce.disabled = false;
            }
            if (!res || typeof res !== 'object') {
                Swal.fire({ icon: 'error', title: 'Erro', text: 'Resposta inválida do servidor.' });
                return;
            }
            if (!res.success) {
                Swal.fire({ icon: 'error', title: 'Atenção', text: res.message || 'Não foi possível concluir.' });
                return;
            }
            if (res.tipo === 'nfce') {
                var msg = res.message || 'NFC-e emitida com sucesso.';
                if (res.danfe) {
                    Swal.fire({
                        icon: 'success',
                        title: 'NFC-e',
                        text: msg,
                        showCancelButton: true,
                        confirmButtonText: 'Imprimir DANFE',
                        cancelButtonText: 'Fechar'
                    }).then(function (r) {
                        if (r.isConfirmed && res.idNf) {
                            printCupomDanfe(res.idNf);
                        }
                        $('#modalCupomFiscal').modal('hide');
                        cupomGerenteVoltar();
                    });
                } else {
                    Swal.fire({ icon: 'success', title: 'NFC-e', text: msg }).then(function () {
                        $('#modalCupomFiscal').modal('hide');
                        cupomGerenteVoltar();
                    });
                }
                return;
            }
            Swal.fire({ icon: 'success', title: 'OK', text: res.message || 'Concluído.' });
        },
        error: function (xhr) {
            if (btnNfce) {
                btnNfce.disabled = false;
            }
            var msg = 'Falha ao processar a emissão.';
            try {
                var j = JSON.parse(xhr.responseText);
                if (j.message) {
                    msg = j.message;
                }
            } catch (e) { /* não é JSON */ }
            Swal.fire({ icon: 'error', title: 'Erro', text: msg });
        }
    });
}

function submitCupomFiscal(id) {
    abrirModalCupomFiscal(id);
}
