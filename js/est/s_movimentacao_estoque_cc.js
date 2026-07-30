/**
 * Movimentação de Estoque entre Centros de Custo (encomenda).
 */
var MOV_CC_AJAX = 'index.php?mod=est&form=movimentacao_estoque_cc&opcao=ajax';
var FIN_AJAX = 'index.php?mod=fin&form=rel_financeiro&opcao=ajax';

function movCcIniciarTela() {
    var f = document.lancamento;

    $('.money').maskMoney({ decimal: ',', thousands: '.', allowNegative: true });

    $('#centroCustoOrigem, #centroCustoDestino').select2({
        placeholder: 'Selecione o Centro de Custo',
        allowClear: true,
        width: '100%'
    });

    var select2Ajax = {
        allowClear: true,
        width: '100%',
        minimumInputLength: 2,
        language: {
            inputTooShort: function () { return 'Digite 2 ou mais caracteres'; },
            noResults: function () { return 'Nenhum resultado'; },
            searching: function () { return 'Buscando...'; }
        }
    };

    function mapResults(data) {
        var lista = Array.isArray(data) ? data : (data && data.results) || [];
        return lista.map(function (item) {
            return {
                id: item.id || item.ID,
                text: item.text || item.DESCRICAO || item.descricao
            };
        }).filter(function (item) {
            return item.id && item.text;
        });
    }

    function preencherSelect($el, valor, texto) {
        if (valor) {
            $el.append(new Option(texto || valor, valor, true, true)).trigger('change');
        }
    }

    $('#movCcSelectProduto').select2($.extend({}, select2Ajax, {
        placeholder: 'Digite código ou descrição do produto',
        ajax: {
            url: MOV_CC_AJAX,
            type: 'POST',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { mod: 'est', form: 'movimentacao_estoque_cc', opcao: 'ajax', submenu: 'buscar_produtos', termo: params.term || '' };
            },
            processResults: function (data) {
                return { results: mapResults(data) };
            },
            cache: true
        }
    })).on('select2:select', function (e) {
        $.post(MOV_CC_AJAX, {
            mod: 'est', form: 'movimentacao_estoque_cc', opcao: 'ajax',
            submenu: 'detalhe_produto', codigo: e.params.data.id
        }, function (data) {
            if (!data || !data.ok) {
                return;
            }
            f.codProduto.value = data.codigo;
            f.descProduto.value = data.descricao;
            f.unidade.value = data.unidade || '';
            f.valorVenda.value = data.venda || '';
            f.uniFracionada.value = data.uniFracionada || 'N';
            f.quantAtual.value = data.estoque || '';
            $('#movCcResumoCod').text(data.codigo);
            $('#movCcResumoUn').text(data.unidade || '—');
            $('#movCcResumoEst').text(data.estoque || '—');
            if (data.uniFracionada === 'S') {
                $('#movCcResumoFrac').text('Sim');
                $('#movCcResumoFracWrap').show();
            } else {
                $('#movCcResumoFracWrap').hide();
            }
            $('#movCcResumoProduto').addClass('is-visible');
        }, 'json');
    }).on('select2:clear', function () {
        f.codProduto.value = f.descProduto.value = '';
        f.unidade.value = f.valorVenda.value = f.uniFracionada.value = f.quantAtual.value = '';
        $('#movCcResumoProduto').removeClass('is-visible');
    });

    $('#movCcSelectConta').select2($.extend({}, select2Ajax, {
        placeholder: 'Digite para buscar conta / pessoa',
        ajax: {
            url: FIN_AJAX,
            type: 'POST',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { mod: 'fin', form: 'rel_financeiro', opcao: 'ajax', submenu: 'buscar_clientes', termo: params.term || '' };
            },
            processResults: function (data) {
                return { results: mapResults(data) };
            },
            cache: true
        }
    })).on('select2:select', function (e) {
        f.pessoa.value = e.params.data.id;
    }).on('select2:clear', function () {
        f.pessoa.value = '';
    });

    $('#movCcSelectGenero').select2($.extend({}, select2Ajax, {
        placeholder: 'Digite para buscar gênero',
        ajax: {
            url: FIN_AJAX,
            type: 'POST',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { mod: 'fin', form: 'rel_financeiro', opcao: 'ajax', submenu: 'buscar_genero', termo: params.term || '' };
            },
            processResults: function (data) {
                return { results: mapResults(data) };
            },
            cache: true
        }
    })).on('select2:select', function (e) {
        f.genero.value = e.params.data.id;
    }).on('select2:clear', function () {
        f.genero.value = '';
    });

    if (f.codProduto.value) {
        preencherSelect($('#movCcSelectProduto'), f.codProduto.value,
            f.codProduto.value + (f.descProduto.value ? ' - ' + f.descProduto.value : ''));
        $('#movCcResumoCod').text(f.codProduto.value);
        $('#movCcResumoUn').text(f.unidade.value || '—');
        $('#movCcResumoEst').text(f.quantAtual.value || '—');
        if (f.uniFracionada && f.uniFracionada.value === 'S') {
            $('#movCcResumoFrac').text('Sim');
            $('#movCcResumoFracWrap').show();
        }
        $('#movCcResumoProduto').addClass('is-visible');
    }
    if (f.pessoa.value) {
        preencherSelect($('#movCcSelectConta'), f.pessoa.value, f.pessoa.value);
    }
    if (f.genero.value) {
        preencherSelect($('#movCcSelectGenero'), f.genero.value, f.genero.value);
    }

    $('#btnRomaneioMovCc').prop('disabled', !f.idEntrada.value);

    if (f.idEntrada.value) {
        $('#movCcDocEntrada').text(f.idEntrada.value);
        if (f.idSaida.value) {
            $('#movCcDocSaida').text(f.idSaida.value);
            $('#movCcBoxSaida').show();
        } else {
            $('#movCcBoxSaida').hide();
        }
        $('#movCcDocProduto').text(f.descProduto.value || f.codProduto.value || '—');
        $('#movCcPainelUltima').addClass('is-visible');
    }
}

function submitConfirmarMovCc() {
    var f = document.lancamento;
    var msg = '';
    if (!f.codProduto.value) {
        msg = 'Selecione o produto.';
    } else if (!f.qtdeEntrada.value || f.qtdeEntrada.value === '0' || f.qtdeEntrada.value === '0,00') {
        msg = 'Informe a quantidade de entrada.';
    } else if (!f.centroCustoOrigem.value || !f.centroCustoDestino.value) {
        msg = 'Selecione origem e destino.';
    } else if (!f.pessoa.value) {
        msg = 'Selecione a conta / pessoa.';
    } else if (!f.genero.value) {
        msg = 'Selecione o gênero.';
    }
    if (msg) {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: msg });
        return false;
    }

    Swal.fire({
        icon: 'question',
        title: 'Confirmar movimentação',
        html: 'Será gerada NF de ajuste (TFF) e as peças serão movimentadas no estoque.<br><br>Deseja continuar?',
        showCancelButton: true,
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a'
    }).then(function (result) {
        if (!result.isConfirmed) {
            return;
        }

        f.submenu.value = 'ajax_entrada';
        if (f.opcao) {
            f.opcao.value = 'ajax';
        }

        Swal.fire({ title: 'Processando entrada...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

        $.ajax({
            type: 'POST',
            url: MOV_CC_AJAX,
            data: $(f).serialize(),
            dataType: 'json'
        }).done(function (data) {
            Swal.close();
            if (!data || !data.ok) {
                Swal.fire({
                    icon: data && data.tipo === 'warning' ? 'warning' : 'error',
                    title: 'Atenção',
                    html: (data && data.mensagem) ? data.mensagem : 'Não foi possível registrar a entrada.'
                });
                return;
            }

            f.idEntrada.value = data.idEntrada || '';
            f.idSaida.value = data.idSaida || '';
            f.codProduto.value = data.codProduto || '';
            f.descProduto.value = data.produto || '';

            $('#movCcDocEntrada').text(data.idEntrada);
            if (data.idSaida) {
                $('#movCcDocSaida').text(data.idSaida);
                $('#movCcBoxSaida').show();
            } else {
                $('#movCcBoxSaida').hide();
            }
            $('#movCcDocProduto').text(data.produto || data.codProduto || '—');
            $('#movCcDocQuantidade').text(data.quantidade || '—');
            $('#movCcPainelUltima').addClass('is-visible');
            $('#btnRomaneioMovCc').prop('disabled', false);

            $('#movCcSelectProduto, #movCcSelectConta, #movCcSelectGenero').val(null).trigger('change');
            f.unidade.value = f.valorVenda.value = f.uniFracionada.value = f.quantAtual.value = '';
            f.qtdeEntrada.value = '';
            f.pessoa.value = '';
            f.genero.value = '';
            f.obs.value = '';
            $('#movCcResumoProduto').removeClass('is-visible');
            $('.money').maskMoney({ decimal: ',', thousands: '.', allowNegative: true });

            Swal.fire({
                icon: 'success',
                title: 'Entrada registrada',
                html: data.mensagem,
                confirmButtonText: 'OK'
            }).then(function () {
                if (data.encomendas && data.encomendas.length > 0) {
                    renderModalEncomenda(data.encomendas);
                    $('#myModal').modal('show');
                }
                romaneio_mov_est_cc_imprime();
            });
        }).fail(function () {
            Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha na comunicação com o servidor.' });
        });
    });
    return false;
}

function romaneio_mov_est_cc_imprime() {
    var f = document.lancamento;
    if (!f.idEntrada.value) {
        Swal.fire({ icon: 'info', title: 'Romaneio', text: 'Nenhuma entrada registrada para impressão.' });
        return;
    }
    window.open(
        'index.php?mod=est&form=movimentacao_estoque_cc_imprime&opcao=imprimir&letra=' +
            encodeURIComponent(f.idEntrada.value + '|' + (f.idSaida.value || '')),
        'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes'
    );
}

function renderModalEncomenda(encomendas) {
    var tbody = $('#tbodyEncomendaModal');
    var centros = window.movCcCentroCustos || [];
    var dataPadrao = window.movCcDataEntregaPadrao || '';

    tbody.empty();
    $('#movCcModalBadge').text(encomendas.length);

    encomendas.forEach(function (item) {
        var pedido = item.pedido;
        var selectCc = '<select class="form-control input-sm" id="modalCentroCusto' + pedido + '">';
        centros.forEach(function (cc) {
            var sel = String(cc.id) === String(item.centroCustoEntrega) ? ' selected' : '';
            selectCc += '<option value="' + cc.id + '"' + sel + '>' + cc.nome + '</option>';
        });
        selectCc += '</select>';

        var qtde = parseFloat(item.qtde);
        if (isNaN(qtde)) {
            qtde = 0;
        }

        tbody.append(
            '<tr id="pedEncomenda' + pedido + '">' +
            '<td class="text-center"><strong>' + pedido + '</strong></td>' +
            '<td>' + (item.cliente || '') + '</td>' +
            '<td class="text-center">' + qtde.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
            '<td>' + (item.descricao || '') + '</td>' +
            '<td class="text-center">' + (item.ccusto || '') + '</td>' +
            '<td class="text-center"><input class="form-control input-sm" id="modalDataEntrega' + pedido + '" type="text" maxlength="10" data-mask="00/00/0000" value="' + (item.prazoEntrega || dataPadrao) + '"></td>' +
            '<td class="text-center">' + selectCc + '</td>' +
            '<td class="text-center"><button type="button" class="btn btn-success btn-xs" onclick="atualizaPedidoEncomenda(' + pedido + ')" title="Liberar pedido"><i class="fa fa-check"></i></button></td>' +
            '</tr>'
        );
    });

    if ($.fn.mask) {
        tbody.find('[data-mask]').mask('00/00/0000');
    }
    if ($.fn.select2) {
        tbody.find('select').select2({ placeholder: 'CC entrega', allowClear: true, width: 'resolve', dropdownParent: $('#myModal') });
    }
}

function atualizaPedidoEncomenda(id) {
    Swal.fire({
        icon: 'question',
        title: 'Liberar pedido ' + id + '?',
        text: 'O pedido será validado e liberado para conferência.',
        showCancelButton: true,
        confirmButtonText: 'Sim, liberar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a'
    }).then(function (confirmacao) {
        if (!confirmacao.isConfirmed) {
            return;
        }

        var f = document.lancamento;
        f.idPedido.value = id;
        f.mDataEntrega.value = $('#modalDataEntrega' + id).val();
        f.mCentroCusto.value = $('#modalCentroCusto' + id).val();
        f.submenu.value = 'ajax_liberar_encomenda';
        if (f.opcao) {
            f.opcao.value = 'ajax';
        }

        Swal.fire({ title: 'Processando pedido...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });

        $.ajax({
            type: 'POST',
            url: MOV_CC_AJAX,
            data: $(f).serialize(),
            dataType: 'json'
        }).done(function (data) {
            var icon = 'info';
            if (data.status === 'liberado') {
                icon = 'success';
            } else if (data.status === 'encomenda' || data.status === 'sem_financeiro' || data.status === 'erro') {
                icon = data.ok ? 'info' : 'warning';
            }

            var html = (data.mensagem || '').replace(/\n/g, '<br>');
            if (data.detalhe) {
                html += '<br><br><pre style="text-align:left;white-space:pre-wrap;font-size:12px;max-height:200px;overflow:auto;">' + data.detalhe + '</pre>';
            }

            Swal.fire({ icon: icon, title: data.titulo || 'Resultado', html: html }).then(function () {
                if (data.encomendas && data.encomendas.length > 0) {
                    renderModalEncomenda(data.encomendas);
                    $('#myModal').modal('show');
                } else {
                    $('#myModal').modal('hide');
                }
            });
        }).fail(function () {
            Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha ao liberar o pedido.' });
        });
    });
    return false;
}

function limpaDadosForm() {
    Swal.fire({
        icon: 'question',
        title: 'Limpar formulário?',
        text: 'Os dados preenchidos serão removidos.',
        showCancelButton: true,
        confirmButtonText: 'Sim, limpar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (!result.isConfirmed) {
            return;
        }
        var f = document.lancamento;
        $('#movCcSelectProduto, #movCcSelectConta, #movCcSelectGenero').val(null).trigger('change');
        f.codProduto.value = f.descProduto.value = '';
        f.unidade.value = f.valorVenda.value = f.uniFracionada.value = f.quantAtual.value = '';
        f.qtdeEntrada.value = '';
        f.pessoa.value = '';
        f.genero.value = '';
        f.obs.value = '';
        f.idEntrada.value = f.idSaida.value = '';
        $('#movCcResumoProduto, #movCcPainelUltima').removeClass('is-visible');
        $('#btnRomaneioMovCc').prop('disabled', true);
        $('.money').maskMoney({ decimal: ',', thousands: '.', allowNegative: true });
    });
}
