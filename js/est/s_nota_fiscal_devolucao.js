/**
 * Wizard de devolução de NF
 */
let wizardDevolucao = null;
let contextoDevolucao = null;
let itensDevolucao = [];
let previewTributosFrete = 0;
let previewTributosDesp = 0;
let previewTributosSeguro = 0;
let urlsEspelho = { pdfUrl: '', xmlUrl: '' };
let wizardPularAsync = 0;
let wizardAsyncBusy = false;

function urlAjaxDevolucao(submenu) {
    return 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=' + encodeURIComponent(submenu) + '&opcao=blank';
}

function descricaoCenario(codigo, tipoNfOrigem) {
    if (codigo === 'DEVOLUCAO_COMPRA' || String(tipoNfOrigem) === '0') {
        return 'Devolução de compra (NF entrada → NF saída)';
    }
    return 'Devolução de venda (NF saída → NF entrada)';
}

function popularCombosTpNF() {
    const opcoes = (typeof opcoesTPNF !== 'undefined' && opcoesTPNF) ? opcoesTPNF : { credito: [], debito: [] };

    const $cred = $('#ctx_tp_nf_credito').empty().append($('<option>').val('').text('-- Selecione --'));
    (opcoes.credito || []).forEach(function(o) {
        $cred.append($('<option>').val(o.valor).text(o.label));
    });

    const $deb = $('#ctx_tp_nf_debito').empty().append($('<option>').val('').text('-- Selecione --'));
    (opcoes.debito || []).forEach(function(o) {
        $deb.append($('<option>').val(o.valor).text(o.label));
    });
}

const INFO_REF_TIPO = {
    'NENHUMA':            { icon: 'fa-ban',        cls: 'text-danger',  txt: 'Sem referência — <b>não</b> preencha a Chave NFe Referenciada.' },
    'NFREF':              { icon: 'fa-link',        cls: 'text-info',    txt: 'Referência no cabeçalho — preencha a <b>Chave NFe Referenciada</b> (44 dígitos).' },
    'NFREF_PROIBE_DFE':   { icon: 'fa-link',        cls: 'text-info',    txt: 'Referência no cabeçalho — preencha a <b>Chave NFe Referenciada</b> (44 dígitos). DFeReferenciado por item é <b>proibido</b>.' },
    'DFE_OPCIONAL':       { icon: 'fa-info-circle', cls: 'text-muted',   txt: 'Referência por item (opcional) — <b>Nº Item ref.</b> e <b>Chave ref.</b> no painel de tributação.' },
    'DFE_OBRIG_SEM_ITEM': { icon: 'fa-exclamation-circle', cls: 'text-warning', txt: 'Referência por item obrigatória — preencha a <b>Chave ref.</b> em cada item (sem Nº Item).' },
    'DFE_OBRIG_COM_ITEM': { icon: 'fa-exclamation-circle', cls: 'text-warning', txt: 'Referência por item obrigatória — preencha <b>Chave de acesso ref.</b> e <b>Nº Item ref.</b> em cada item no campo <b><IBS/CBS></b>.' }
};

function atualizarInfoTpNF(idSelect, idInfo, grupo) {
    const val = $('#' + idSelect).val();
    const $div = $('#' + idInfo).empty();
    if (!val) {
        return;
    }
    const opcoes = (typeof opcoesTPNF !== 'undefined' && opcoesTPNF) ? opcoesTPNF : {};
    const opt = ((opcoes[grupo] || [])).find(function(o) { return o.valor === val; });
    if (!opt) {
        return;
    }
    const info = INFO_REF_TIPO[opt.refTipo];
    if (!info) {
        return;
    }
    let html = '<i class="fa ' + info.icon + '"></i> <span class="' + info.cls + '">' + info.txt + '</span>';
    if (opt.cClassTrib) {
        html += ' &nbsp;<span class="label label-default" style="font-size:10px;">cClassTrib: ' + opt.cClassTrib + '</span>';
    }
    $div.html(html);
}

function obterRefTipoSelecionado() {
    const opcoes = (typeof opcoesTPNF !== 'undefined' && opcoesTPNF) ? opcoesTPNF : { credito: [], debito: [] };
    const valCred = $('#ctx_tp_nf_credito').val();
    const valDeb  = $('#ctx_tp_nf_debito').val();

    if (valCred) {
        const opt = (opcoes.credito || []).find(function(o) { return o.valor === valCred; });
        return opt ? opt.refTipo : null;
    }
    if (valDeb) {
        const opt = (opcoes.debito || []).find(function(o) { return o.valor === valDeb; });
        return opt ? opt.refTipo : null;
    }
    return null;
}

function validarRefTipoItens(refTipo) {
    if (!refTipo) {
        return true;
    }

    const erros = [];
    const chnfeCabecalho = $.trim($('#ctx_chnfe').val() || '');

    // Validação do campo de cabeçalho (Chave NFe Referenciada)
    if (refTipo === 'NFREF' || refTipo === 'NFREF_PROIBE_DFE') {
        if (chnfeCabecalho === '') {
            erros.push('Este tipo exige referência no cabeçalho. '
                + 'Preencha o campo <b>Chave NFe Referenciada</b> (44 dígitos).');
        } else if (chnfeCabecalho.length !== 44) {
            erros.push('O campo <b>Chave NFe Referenciada</b> deve ter exatamente 44 dígitos '
                + '(atual: ' + chnfeCabecalho.length + ').');
        }
    }

    if (erros.length > 0) {
        Swal.fire({
            title: 'Atenção — Chave NFe Referenciada',
            html: erros.join('<br><br>'),
            icon: 'warning'
        });
        return false;
    }

    $('#painel-tributos-itens .painel-tributo-item').each(function(idx) {
        const descricao = $(this).find('.trib-item-heading strong').text() || ('Item ' + (idx + 1));
        const chaveRef  = $.trim($(this).find('[data-campo="chaveRef"]').val() || '');
        const nItemStr  = $.trim($(this).find('[data-campo="nItem"]').val() || '');
        const temChave  = chaveRef !== '';
        const temNItem  = nItemStr !== '';

        switch (refTipo) {

            case 'DFE_OPCIONAL':
                // Opcional — se preenchida, a chave deve ter 44 dígitos
                if (temChave && chaveRef.length !== 44) {
                    erros.push('<b>' + descricao + '</b>: Chave de acesso ref. deve ter exatamente 44 dígitos.');
                }
                break;

            case 'DFE_OBRIG_SEM_ITEM':
                // Chave obrigatória
                if (!temChave) {
                    erros.push('<b>' + descricao + '</b>: Chave de acesso ref. é obrigatória para este tipo.');
                } else if (chaveRef.length !== 44) {
                    erros.push('<b>' + descricao + '</b>: Chave de acesso ref. deve ter exatamente 44 dígitos.');
                }
                break;

            case 'DFE_OBRIG_COM_ITEM':
                // Chave E nItem obrigatórios
                if (!temChave) {
                    erros.push('<b>' + descricao + '</b>: Chave de acesso ref. é obrigatória.');
                } else if (chaveRef.length !== 44) {
                    erros.push('<b>' + descricao + '</b>: Chave de acesso ref. deve ter exatamente 44 dígitos.');
                }
                if (!temNItem) {
                    erros.push('<b>' + descricao + '</b>: Nº Item ref. é obrigatório.');
                } else if (isNaN(parseInt(nItemStr, 10)) || parseInt(nItemStr, 10) < 1) {
                    erros.push('<b>' + descricao + '</b>: Nº Item ref. deve ser um número inteiro positivo.');
                }
                break;
        }
    });

    if (erros.length > 0) {
        Swal.fire({
            title: 'Atenção — Referência IBS/CBS',
            html: erros.join('<br><br>'),
            icon: 'warning'
        });
        return false;
    }
    return true;
}

function atualizarVisibilidadeTpNF() {
    const fin = parseInt($('#ctx_finalidade_emissao').val() || '0', 10);
    const mostrarCredito = fin === 5;
    const mostrarDebito  = fin === 6;

    $('#grp_tp_nf_credito').toggle(mostrarCredito);
    $('#grp_tp_nf_debito').toggle(mostrarDebito);

    if (!mostrarCredito) {
        $('#ctx_tp_nf_credito').val('');
        $('#info_tp_nf_credito').empty();
    }
    if (!mostrarDebito) {
        $('#ctx_tp_nf_debito').val('');
        $('#info_tp_nf_debito').empty();
    }
}

function cenarioPorCodigo(codigo) {
    const compra = codigo === 'DEVOLUCAO_COMPRA';
    return {
        codigo: compra ? 'DEVOLUCAO_COMPRA' : 'DEVOLUCAO_VENDA',
        descricao: descricaoCenario(codigo),
        tipoDevolucao: compra ? '1' : '0',
        natOpTipo: compra ? 'S' : 'E'
    };
}

function cenarioPorTipoNf(tipoNf) {
    return cenarioPorCodigo(String(tipoNf) === '0' ? 'DEVOLUCAO_COMPRA' : 'DEVOLUCAO_VENDA');
}

function formatMoeda(v) {
    const n = parseFloat(v) || 0;
    return n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function parseMoeda(v) {
    if (typeof v === 'number') return v;
    return parseFloat(String(v).replace(/\./g, '').replace(',', '.')) || 0;
}

function irParaDevolucaoWizard(idNfOrigem, origem) {
    origem = origem || 'nota_fiscal';
    window.location.href = 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=wizard&idNfOrigem=' + encodeURIComponent(idNfOrigem) + '&origem=' + encodeURIComponent(origem);
}

function cadastrarDevolucao() {
    window.location.href = 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=cadastrar&origem=nota_fiscal_devolucao';
}

function isCadastroManual() {
    return $('#manual').val() === '1' || $('#submenuTela').val() === 'cadastrar';
}

function paramsAjaxTela() {
    return {
        submenuTela: $('#submenuTela').val() || 'wizard'
    };
}

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

function urlPesquisaContaDevolucao() {
    return (typeof PATH_CLIENTE !== 'undefined' ? PATH_CLIENTE : '') + '/index.php?mod=crm&form=contas&opcao=pesquisar';
}

function abrirContaDevolucao(onClose) {
    const w = window.open(urlPesquisaContaDevolucao(), 'consulta', 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
    const timer = setInterval(function() {
        if (!w || w.closed) {
            clearInterval(timer);
            if (typeof onClose === 'function') {
                onClose();
            }
        }
    }, 400);
}

function abrirPessoaDevolucao() {
    abrirContaDevolucao(sincronizarPessoaDevolucao);
}

function sincronizarPessoaDevolucao() {
    const f = document.lancamento;
    if (!f || !f.pessoa || !f.pessoa.value) {
        return;
    }
    $('#ctx_id_pessoa').val(f.pessoa.value);
    $('#ctx_pessoa_nome').val(f.nome ? f.nome.value : '');
    if (contextoDevolucao && contextoDevolucao.pessoa) {
        contextoDevolucao.pessoa.id = parseInt(f.pessoa.value, 10);
        contextoDevolucao.pessoa.nome = f.nome ? f.nome.value : '';
    }
}

function abrirTransportadorDevolucao() {
    abrirContaDevolucao(sincronizarTransportadorDevolucao);
}

function sincronizarTransportadorDevolucao() {
    const f = document.lancamento;
    if (!f || !f.pessoa || !f.pessoa.value) {
        return;
    }
    $('#ctx_transportador').val(f.pessoa.value);
    $('#ctx_transportador_nome').val(f.nome ? f.nome.value : '');
    if (contextoDevolucao) {
        contextoDevolucao.transporte = contextoDevolucao.transporte || {};
        contextoDevolucao.transporte.transportador = parseInt(f.pessoa.value, 10);
        contextoDevolucao.transporte.transportadorNome = f.nome ? f.nome.value : '';
    }
}

function preencherTransporte(t) {
    if (!t) {
        return;
    }
    $('#ctx_mod_frete').val(t.modFrete != null && t.modFrete !== '' ? String(t.modFrete) : '9');
    $('#ctx_transportador').val(t.transportador || '');
    $('#ctx_transportador_nome').val(t.transportadorNome || '');
    $('#ctx_placa_veiculo').val(t.placaVeiculo || '');
    $('#ctx_cod_antt').val(t.codAntt || '');
    $('#ctx_uf_veiculo').val(t.uf || '');
    $('#ctx_volume').val(t.volume || '');
    $('#ctx_vol_especie').val(t.volEspecie || '');
    $('#ctx_vol_marca').val(t.volMarca || '');
    $('#ctx_vol_peso_liq').val(t.volPesoLiq || '');
    $('#ctx_vol_peso_bruto').val(t.volPesoBruto || '');
    if (document.lancamento && t.transportador) {
        document.lancamento.pessoa.value = t.transportador;
        document.lancamento.nome.value = t.transportadorNome || '';
    }
}

function preencherCabecalhoFinanceiro(f) {
    if (!f) {
        return;
    }
    $('#ctx_obs').val(f.obs || '');
    $('#ctx_frete').val(formatMoeda(f.frete || 0));
    $('#ctx_seguro').val(formatMoeda(f.seguro || 0));
    $('#ctx_desp_acessorias').val(formatMoeda(f.despAcessorias || 0));
    if (f.tpNFCredito) {
        $('#ctx_tp_nf_credito').val(f.tpNFCredito);
        atualizarInfoTpNF('ctx_tp_nf_credito', 'info_tp_nf_credito', 'credito');
    }
    if (f.tpNFDebito) {
        $('#ctx_tp_nf_debito').val(f.tpNFDebito);
        atualizarInfoTpNF('ctx_tp_nf_debito', 'info_tp_nf_debito', 'debito');
    }
}

function coletarCabecalhoFinanceiro() {
    return {
        obs: ($('#ctx_obs').val() || '').trim(),
        frete: parseMoeda($('#ctx_frete').val()),
        seguro: parseMoeda($('#ctx_seguro').val()),
        despAcessorias: parseMoeda($('#ctx_desp_acessorias').val())
    };
}

function lerTotaisCabecalhoFinanceiro() {
    return {
        frete: parseMoeda($('#ctx_frete').val()),
        seguro: parseMoeda($('#ctx_seguro').val()),
        desp: parseMoeda($('#ctx_desp_acessorias').val())
    };
}

function coletarTransporteCabecalho() {
    return $.extend({
        modFrete: $('#ctx_mod_frete').val() || '9',
        transportador: $('#ctx_transportador').val() || '0',
        placaVeiculo: $('#ctx_placa_veiculo').val() || '',
        codAntt: $('#ctx_cod_antt').val() || '',
        uf: $('#ctx_uf_veiculo').val() || '',
        volume: $('#ctx_volume').val() || '0',
        volEspecie: $('#ctx_vol_especie').val() || '',
        volMarca: $('#ctx_vol_marca').val() || '',
        volPesoLiq: $('#ctx_vol_peso_liq').val() || '0',
        volPesoBruto: $('#ctx_vol_peso_bruto').val() || '0'
    }, coletarCabecalhoFinanceiro());
}

function continuarDevolucao(idNfDev) {
    window.location.href = 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=wizard&idNfDev=' + encodeURIComponent(idNfDev) + '&origem=nota_fiscal_devolucao';
}

function abrirNotaFiscalBoletoBancario(id) {
    window.open(
        'index.php?mod=est&opcao=imprimir&form=nota_fiscal_boleto_bancario&id=' + encodeURIComponent(id),
        '_blank'
    );
}

function submitPesquisaDevolucao() {
    submitLetraDevolucao();
}

function montaLetraDevolucao() {
    const f = document.formDevolucaoMostra;
    f.letra.value = f.mfilial.value + '|' + f.mtipo.value + '|' + f.msituacao.value + '|' + f.dataIni.value + '|' + f.dataFim.value + '|' + f.numNf.value + '|' + f.serieNf.value + '|' + f.pessoa.value + '|' + f.idNatop.value + '|' + f.finalidadeEmissao.value + '|' + f.modFrete.value + '|' + f.genero.value + '|' + f.transportador.value + '|' + f.modeloNf.value;
}

function submitLetraDevolucao() {
    const f = document.formDevolucaoMostra;
    f.submenu.value = 'mostra';
    montaLetraDevolucao();
    f.submit();
}

function urlMostraDevolucao() {
    return 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=mostra';
}

function urlSairWizard() {
    const origem = $('#origemTela').val() || 'nota_fiscal_devolucao';
    return origem === 'nota_fiscal_devolucao'
        ? urlMostraDevolucao()
        : 'index.php?mod=est&form=nota_fiscal&submenu=mostra';
}

function salvarESairWizard() {
    let itensCount = 0;
    try {
        itensCount = coletarItensSelecionados().length;
    } catch (e) {
        itensCount = 0;
    }

    if (itensCount === 0) {
        window.location.href = urlSairWizard();
        return;
    }

    salvarRascunho().done(function(ok) {
        if (ok) {
            window.location.href = urlSairWizard();
        }
    });
}

function cancelarWizardDevolucao() {
    const idNfDev = $('#idNfDev').val();
    const origem = $('#origemTela').val() || 'nota_fiscal_devolucao';
    const urlMostra = origem === 'nota_fiscal_devolucao'
        ? urlMostraDevolucao()
        : 'index.php?mod=est&form=nota_fiscal&submenu=mostra';

    if (idNfDev && parseInt(idNfDev, 10) > 0) {
        Swal.fire({
            title: 'Sair do assistente?',
            text: 'Você pode salvar o rascunho e sair ou excluí-lo.',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Salvar',
            denyButtonText: 'Excluir nota',
            cancelButtonText: 'Continuar editando',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success btn-sm',
                denyButton: 'btn btn-danger btn-sm',
                cancelButton: 'btn btn-default btn-sm'
            }
        }).then((r) => {
            if (r.isConfirmed) {
                salvarESairWizard();
            } else if (r.isDenied) {
                ajaxGet('cancelar', {
                    idNfDev: idNfDev,
                    origem: origem
                }).done(function(resp) {
                    window.location.href = (resp && resp.redirectUrl) ? resp.redirectUrl : urlMostra;
                }).fail(function() {
                    window.location.href = urlMostra;
                });
            }
        });
        return;
    }
    window.location.href = urlMostra;
}

function ajaxGet(submenu, params) {
    return $.ajax({
        type: 'GET',
        url: urlAjaxDevolucao(submenu),
        data: params,
        dataType: 'json'
    });
}

function ajaxPost(submenu, data) {
    return $.ajax({
        type: 'POST',
        url: urlAjaxDevolucao(submenu),
        data: data,
        dataType: 'json'
    });
}

function iniciarWizardDevolucao() {
    const idNfOrigem = $('#idNfOrigem').val();
    const idNfDev = $('#idNfDev').val();
    wizardPularAsync = 0;
    wizardAsyncBusy = false;

    popularCombosTpNF();
    atualizarInfoTpNF('ctx_tp_nf_credito', 'info_tp_nf_credito', 'credito');
    atualizarInfoTpNF('ctx_tp_nf_debito',  'info_tp_nf_debito',  'debito');

    wizardDevolucao = $('#wizard-devolucao').smartWizard({
        selected: 0,
        theme: 'default',
        transitionEffect: 'fade',
        showStepURLhash: false,
        keyNavigation: false,
        toolbarSettings: {
            toolbarPosition: 'top',
            showNextButton: true,
            showPreviousButton: true
        },
        anchorSettings: {
            enableAllAnchors: false,
            markDoneStep: true,
            markAllPreviousStepsAsDone: true
        },
        onLeaveStep: function(anchor, context) {
            if (context.toStep < context.fromStep) {
                return true;
            }
            if (wizardAsyncBusy) {
                return false;
            }

            switch (context.fromStep) {
                case 1:
                    if (!$('#ctx_id_natop').val()) {
                        Swal.fire('Atenção', 'Selecione a natureza de operação.', 'warning');
                        return false;
                    }
                    if (isCadastroManual()) {
                        if (!$('#ctx_id_pessoa').val()) {
                            Swal.fire('Atenção', 'Selecione a pessoa (cliente/fornecedor).', 'warning');
                            return false;
                        }
                        const chnfe = ($('#ctx_chnfe').val() || '').trim();
                        const nfNum = ($('#ctx_nf_numero').val() || '').trim();
                        if (!chnfe && !nfNum) {
                            Swal.fire('Atenção', 'Informe a chave NFe ou o número da NF de origem.', 'warning');
                            return false;
                        }
                    }
                    // Validação da Chave NFe Referenciada conforme o tipo selecionado
                    const refTipoCtx = obterRefTipoSelecionado();
                    if (refTipoCtx) {
                        const chnfeAtual = $.trim($('#ctx_chnfe').val() || '');
                        if ((refTipoCtx === 'NFREF' || refTipoCtx === 'NFREF_PROIBE_DFE') && chnfeAtual.length !== 44) {
                            Swal.fire({
                                title: 'Atenção — Chave NFe Referenciada',
                                html: 'O tipo selecionado exige referência no cabeçalho.<br>'
                                    + 'Preencha o campo <b>Chave NFe Referenciada</b> com os 44 dígitos.',
                                icon: 'warning'
                            });
                            return false;
                        }
                    }
                    if (wizardPularAsync === 1) {
                        wizardPularAsync = 0;
                        return true;
                    }
                    if (wizardAsyncBusy) {
                        return false;
                    }
                    wizardAsyncBusy = true;
                    if (isCadastroManual() && itensDevolucao.length === 0) {
                        wizardAsyncBusy = false;
                        return true;
                    }
                    carregarItens(true).done(function(ok) {
                        wizardAsyncBusy = false;
                        if (ok) {
                            wizardPularAsync = 1;
                            $('#wizard-devolucao').smartWizard('goForward');
                        }
                    }).fail(function() {
                        wizardAsyncBusy = false;
                    });
                    return false;

                case 2:
                    if (wizardPularAsync === 2) {
                        wizardPularAsync = 0;
                        return true;
                    }
                    try {
                        if (coletarItensSelecionados().length === 0) {
                            Swal.fire('Atenção', 'Selecione ao menos um produto.', 'warning');
                            return false;
                        }
                    } catch (e) {
                        Swal.fire('Atenção', e.message, 'warning');
                        return false;
                    }
                    if (wizardAsyncBusy) {
                        return false;
                    }
                    wizardAsyncBusy = true;
                    Swal.fire({ title: 'Calculando tributos...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    atualizarPreviewTributos()
                        .done(function(ok) {
                            Swal.close();
                            wizardAsyncBusy = false;
                            if (ok) {
                                wizardPularAsync = 2;
                                $('#wizard-devolucao').smartWizard('goForward');
                            }
                        })
                        .fail(function() {
                            Swal.close();
                            wizardAsyncBusy = false;
                        });
                    return false;

                case 3:
                    if (wizardPularAsync === 3) {
                        wizardPularAsync = 0;
                        return true;
                    }
                    if (!validarRefTipoItens(obterRefTipoSelecionado())) {
                        return false;
                    }
                    if (wizardAsyncBusy) {
                        return false;
                    }
                    wizardAsyncBusy = true;
                    salvarRascunho()
                        .done(function(ok) {
                            wizardAsyncBusy = false;
                            if (ok) {
                                wizardPularAsync = 3;
                                $('#wizard-devolucao').smartWizard('goForward');
                            }
                        })
                        .fail(function() {
                            wizardAsyncBusy = false;
                        });
                    return false;

                default:
                    return true;
            }
        }
    });

    $('#chk_todos_itens').on('change', function() {
        $('.chk-item-dev').prop('checked', $(this).is(':checked'));
    });

    $('#ctx_id_natop').on('change', function() {
        if ($('#idNfOrigem').val() && !isCadastroManual()) {
            carregarItens(false);
        }
    });

    $('#ctx_cenario_codigo').on('change', function() {
        if (!isCadastroManual()) return;
        recarregarNatOpManual();
    });

    $('#ctx_finalidade_emissao').on('change', function() {
        atualizarVisibilidadeTpNF();
    });

    $('#ctx_tp_nf_credito').on('change', function() {
        if ($(this).val()) {
            $('#ctx_tp_nf_debito').val('');
            atualizarInfoTpNF('ctx_tp_nf_debito', 'info_tp_nf_debito', 'debito');
        }
        atualizarInfoTpNF('ctx_tp_nf_credito', 'info_tp_nf_credito', 'credito');
    });

    $('#ctx_tp_nf_debito').on('change', function() {
        if ($(this).val()) {
            $('#ctx_tp_nf_credito').val('');
            atualizarInfoTpNF('ctx_tp_nf_credito', 'info_tp_nf_credito', 'credito');
        }
        atualizarInfoTpNF('ctx_tp_nf_debito', 'info_tp_nf_debito', 'debito');
    });

    $('#btn_add_produto_manual').on('click', adicionarProdutoManual);

    $(document).on('input change', '#painel-tributos-itens .inp-trib', recalcularTotaisTributacao);

    $(document).on('click', '#painel-tributos-itens .trib-item-heading', function(e) {
        e.preventDefault();
        const $box = $(this).closest('.painel-tributo-item');
        const $body = $box.find('> .trib-item-body');
        const $icon = $(this).find('.trib-chevron');
        const aberto = $body.is(':visible');
        if (aberto) {
            $body.slideUp(200);
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $(this).attr('aria-expanded', 'false');
        } else {
            $body.slideDown(200);
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            $(this).attr('aria-expanded', 'true');
        }
    });

    $(document).on('click', '#painel-tributos-itens .trib-grupo-heading', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $grupo = $(this).closest('.trib-grupo-panel');
        const $body = $grupo.find('> .trib-grupo-body');
        const $icon = $(this).find('.trib-grupo-chevron');
        const aberto = $body.is(':visible');
        if (aberto) {
            $body.slideUp(150);
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $(this).attr('aria-expanded', 'false');
        } else {
            $body.slideDown(150);
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            $(this).attr('aria-expanded', 'true');
        }
    });

    $(document).on('input change', '#ctx_frete, #ctx_seguro, #ctx_desp_acessorias', function() {
        if ($('#step_trib').is(':visible')) {
            recalcularTotaisTributacao();
        }
    });

    configurarUiManual();

    $('#btn_baixar_xml, #btn_visualizar_espelho, #btn_regenerar_espelho').on('click', gerarEspelhoDevolucao);
    $('#btn_confirmar_devolucao').on('click', salvarESairWizard);
    $('#btn_emitir_nfe').on('click', emitirNfeDevolucao);

    carregarContexto(idNfOrigem, idNfDev);
}

function configurarUiManual() {
    if (!isCadastroManual()) {
        $('#ctx_chnfe').prop('readonly', true);
        return;
    }
    $('#grp_cenario_manual, #grp_pessoa_manual, #painel-add-produto-manual').show();
    $('#ctx_pessoa').hide();
    $('#ctx_nf_numero, #ctx_nf_serie, #ctx_chnfe').prop('readonly', false);
    $('#tabela-itens-devolucao thead th:nth-child(3)').text('—');
}

function recarregarNatOpManual() {
    const idNfDev = $('#idNfDev').val();
    ajaxGet('buscarContexto', $.extend({
        idNfOrigem: 0,
        idNfDev: idNfDev,
        cenarioCodigo: $('#ctx_cenario_codigo').val()
    }, paramsAjaxTela())).done(function(resp) {
        if (!resp.ok) return;
        const $nat = $('#ctx_id_natop').empty();
        (resp.natOps || []).forEach(function(n) {
            $nat.append($('<option>').val(n.ID).text(n.DESCRICAO));
        });
        if ((resp.natOps || []).length > 0) {
            $nat.val(String(resp.natOps[0].ID));
        }
        if (contextoDevolucao) {
            const cen = cenarioPorCodigo($('#ctx_cenario_codigo').val());
            contextoDevolucao.cenario = $.extend({}, resp.cenario || {}, cen);
            contextoDevolucao.cenarioCodigo = resp.cenarioCodigo;
            contextoDevolucao.natOps = resp.natOps;
        }
    });
}

function adicionarProdutoManual() {
    const cod = parseInt($('#inp_cod_produto').val(), 10);
    if (!cod) {
        Swal.fire('Atenção', 'Informe o código do produto.', 'warning');
        return;
    }
    if (!$('#ctx_id_pessoa').val()) {
        Swal.fire('Atenção', 'Selecione a pessoa antes de incluir produtos.', 'warning');
        return;
    }
    ajaxGet('buscarProduto', $.extend({
        codProduto: cod,
        idNatop: $('#ctx_id_natop').val(),
        cenarioCodigo: $('#ctx_cenario_codigo').val(),
        idPessoa: $('#ctx_id_pessoa').val()
    }, paramsAjaxTela())).done(function(resp) {
        if (!resp.ok) {
            Swal.fire('Erro', resp.erro || 'Produto não encontrado.', 'error');
            return;
        }
        const dup = itensDevolucao.findIndex(function(x) {
            return x.codProduto == resp.item.codProduto;
        });
        if (dup >= 0) {
            itensDevolucao[dup] = resp.item;
        } else {
            itensDevolucao.push(resp.item);
        }
        $('#inp_cod_produto').val('');
        renderTabelaItens();
    });
}

function carregarContexto(idNfOrigem, idNfDev) {
    Swal.fire({ title: 'Carregando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    ajaxGet('buscarContexto', $.extend({
        idNfOrigem: idNfOrigem,
        idNfDev: idNfDev
    }, paramsAjaxTela()))
        .done(function(resp) {
            Swal.close();
            if (!resp.ok) {
                Swal.fire('Erro', resp.erro || 'Falha ao carregar contexto.', 'error').then(() => history.back());
                return;
            }
            contextoDevolucao = resp;
            if (typeof combosTributacao === 'undefined' || !combosTributacao) {
                combosTributacao = {};
            }
            if (resp.manual) {
                $('#manual').val('1');
                configurarUiManual();
            }
            if (resp.idNfOrigem) $('#idNfOrigem').val(resp.idNfOrigem);
            if (resp.idNfDev) {
                $('#idNfDev').val(resp.idNfDev);
                $('#lbl_id_nf_dev').text(resp.idNfDev);
            }
            if (resp.manual) {
                $('#ctx_cenario_codigo').val(resp.cenarioCodigo || 'DEVOLUCAO_VENDA');
                $('#ctx_id_pessoa').val(resp.pessoa.id || '');
                $('#ctx_pessoa_nome').val(resp.pessoa.nome || '');
                if (document.lancamento) {
                    document.lancamento.pessoa.value = resp.pessoa.id || '';
                    document.lancamento.nome.value = resp.pessoa.nome || '';
                }
                $('#ctx_nf_numero').val(resp.nfOrigem.numero || '');
                $('#ctx_nf_serie').val(resp.nfOrigem.serie || '');
            } else {
                const cen = cenarioPorTipoNf(resp.nfOrigem && resp.nfOrigem.tipo);
                $('#ctx_cenario').val(cen.descricao);
                if (resp.cenario) {
                    resp.cenario.descricao = cen.descricao;
                }
                $('#ctx_pessoa').val(resp.pessoa.nome);
                $('#ctx_nf_numero').val(resp.nfOrigem.numero || '');
                $('#ctx_nf_serie').val(resp.nfOrigem.serie || '');
            }
            $('#ctx_chnfe').val(resp.nfOrigem.chnfe);
            $('#ctx_emissao').val(resp.emissao);
            if (resp.finNFe) {
                $('#ctx_finalidade_emissao').val(String(resp.finNFe));
            }
            atualizarVisibilidadeTpNF();
            preencherTransporte(resp.transporte);
            preencherCabecalhoFinanceiro(resp.financeiro);

            const $nat = $('#ctx_id_natop').empty();
            (resp.natOps || []).forEach(function(n) {
                $nat.append($('<option>').val(n.ID).text(n.DESCRICAO));
            });
            if (resp.idNatop) {
                $nat.val(String(resp.idNatop));
            } else if ((resp.natOps || []).length > 0) {
                $nat.val(String(resp.natOps[0].ID));
            }

            if (resp.idNfDev && resp.rascunhoGravado) {
                $('#resumo-final-devolucao').html('<p>NF devolução <strong>#' + resp.idNfDev + '</strong> (rascunho).</p>');
            }

            if (resp.manual && resp.idNfDev) {
                carregarItens(false);
            } else if (resp.idNfOrigem) {
                carregarItens(false);
            }
        })
        .fail(function(xhr) {
            Swal.close();
            let msg = 'Falha na comunicação com o servidor.';
            if (xhr.responseJSON && xhr.responseJSON.erro) {
                msg = xhr.responseJSON.erro;
            }
            Swal.fire('Erro', msg, 'error');
        });
}

function carregarItens(silencioso) {
    const idNfOrigem = $('#idNfOrigem').val();
    const idNfDev = $('#idNfDev').val();
    const idNatop = $('#ctx_id_natop').val();
    if (!silencioso) {
        Swal.fire({ title: 'Carregando produtos...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    }
    return ajaxGet('buscarItens', $.extend({
        idNfOrigem: idNfOrigem,
        idNfDev: idNfDev,
        idNatop: idNatop
    }, paramsAjaxTela()))
        .then(function(resp) {
            if (!silencioso) {
                Swal.close();
            }
            if (!resp.ok) {
                if (!silencioso) {
                    Swal.fire('Erro', resp.erro || 'Falha ao carregar itens.', 'error');
                }
                return false;
            }
            itensDevolucao = resp.itens || [];
            if (!itensDevolucao.length) {
                if (isCadastroManual()) {
                    renderTabelaItens();
                    return true;
                }
                if (!silencioso) {
                    Swal.fire('Atenção', 'Nenhum produto encontrado na NF de origem.', 'warning');
                }
                return false;
            }
            renderTabelaItens();
            return true;
        })
        .fail(function(xhr) {
            if (!silencioso) {
                Swal.close();
                let msg = 'Falha ao carregar produtos da NF de origem.';
                if (xhr.responseJSON && xhr.responseJSON.erro) {
                    msg = xhr.responseJSON.erro;
                }
                Swal.fire('Erro', msg, 'error');
            }
            return false;
        });
}

function renderTabelaItens() {
    const $tb = $('#tbody-itens-devolucao').empty();
    let todosSelecionados = true;
    itensDevolucao.forEach(function(item, idx) {
        const checked = item.selecionado !== false;
        if (!checked) todosSelecionados = false;
        const cfopTitle = 'CFOP origem: ' + (item.cfopOriginal || '-') +
            (item.cfopSugerido ? ' | Sugerido: ' + item.cfopSugerido : '');
        const $tr = $('<tr>').attr('data-idx', idx);
        $tr.append($('<td>').append($('<input>', {
            type: 'checkbox',
            class: 'chk-item-dev',
            checked: checked,
            'data-idx': idx
        })));
        $tr.append($('<td>').text(item.codProduto + ' - ' + item.descricao));
        $tr.append($('<td>', { class: 'text-right' }).text(isCadastroManual() ? '—' : formatMoeda(item.qtdeOriginal)));
        $tr.append($('<td>').append($('<input>', {
            type: 'text',
            class: 'form-control input-sm text-right inp-qtde',
            value: formatMoeda(item.qtdeDevolver)
        })));
        $tr.append($('<td>').append($('<input>', {
            type: 'text',
            class: 'form-control input-sm text-right inp-unit',
            value: formatMoeda(item.unitario)
        })));
        $tr.append($('<td>').append($('<input>', {
            type: 'text',
            class: 'form-control input-sm inp-cfop',
            value: item.cfop || '',
            maxlength: 4,
            title: cfopTitle
        })));
        $tb.append($tr);
    });
    $('#chk_todos_itens').prop('checked', todosSelecionados && itensDevolucao.length > 0);
}

function coletarItensSelecionados() {
    const selecionados = [];
    $('#tbody-itens-devolucao tr').each(function() {
        const $chk = $(this).find('.chk-item-dev');
        if (!$chk.is(':checked')) return;
        const idx = parseInt($chk.data('idx'), 10);
        const item = itensDevolucao[idx];
        if (!item) return;
        const qtde = parseMoeda($(this).find('.inp-qtde').val());
        const unit = parseMoeda($(this).find('.inp-unit').val());
        if (!isCadastroManual() && qtde > item.qtdeOriginal) {
            throw new Error('Qtde devolução maior que a original: ' + item.descricao);
        }
        const row = {
            qtdeDevolver: qtde,
            unitario: unit,
            cfop: $(this).find('.inp-cfop').val()
        };
        if (item.idNfpOrigem) {
            row.idNfpOrigem = item.idNfpOrigem;
        }
        if (item.codProduto) {
            row.codProduto = item.codProduto;
        }
        selecionados.push(row);
    });
    return selecionados;
}

function itemKeyDevolucao(item) {
    if (item.idNfpOrigem) {
        return 'n' + item.idNfpOrigem;
    }
    if (item.codProduto) {
        return 'c' + item.codProduto;
    }
    return 'x0';
}

const GRUPOS_TRIBUTOS = [
    {
        titulo: 'ICMS',
        campos: [
            { key: 'tribIcms', label: 'CST/CSOSN ICMS', tipo: 'combo', comboKey: 'tribIcms' },
            { key: 'origem', label: 'Origem', tipo: 'combo', comboKey: 'origem' },
            { key: 'modBc', label: 'Mod. BC', tipo: 'combo', comboKey: 'modBc' },
            { key: 'bcIcms', label: 'BC ICMS', tipo: 'moeda' },
            { key: 'aliqIcms', label: 'Alíq. ICMS %', tipo: 'moeda' },
            { key: 'valorIcms', label: 'Valor ICMS', tipo: 'moeda' },
            { key: 'percReducaoBc', label: 'Red. BC %', tipo: 'moeda' },
            { key: 'valorIcmsOperacao', label: 'Vlr ICMS Op.', tipo: 'moeda' },
            { key: 'percDiferido', label: 'Diferimento %', tipo: 'moeda' },
            { key: 'valorIcmsDiferido', label: 'Vlr ICMS Dif.', tipo: 'moeda' },
            { key: 'pCredSn', label: 'Alíq. Créd. SN %', tipo: 'moeda' },
            { key: 'creditoSn', label: 'Crédito SN', tipo: 'moeda' }
        ]
    },
    {
        titulo: 'ICMS ST',
        campos: [
            { key: 'modBcSt', label: 'Mod. BC ST', tipo: 'combo', comboKey: 'modBcSt' },
            { key: 'valorbcst', label: 'BC ICMS ST', tipo: 'moeda' },
            { key: 'aliqicmsst', label: 'Alíq. ICMS ST %', tipo: 'moeda' },
            { key: 'percMvaSt', label: 'MVA ST %', tipo: 'moeda' },
            { key: 'percReducaoBcSt', label: 'Red. BC ST %', tipo: 'moeda' },
            { key: 'valoricmsst', label: 'Valor ICMS ST', tipo: 'moeda' },
            { key: 'valorBaseCalculoStRetido', label: 'BC ST Retido', tipo: 'moeda' },
            { key: 'valorIcmsStRetido', label: 'ICMS ST Retido', tipo: 'moeda' },
            { key: 'valorIcmsSubstituto', label: 'ICMS Substituto', tipo: 'moeda' }
        ]
    },
    {
        titulo: 'IPI',
        campos: [
            { key: 'cstIpi', label: 'CST IPI', tipo: 'combo', comboKey: 'cstIpi' },
            { key: 'baseCalculoIpi', label: 'BC IPI', tipo: 'moeda' },
            { key: 'aliqIpi', label: 'Alíq. IPI %', tipo: 'moeda' },
            { key: 'valorIpi', label: 'Valor IPI', tipo: 'moeda' }
        ]
    },
    {
        titulo: 'PIS',
        campos: [
            { key: 'cstPis', label: 'CST PIS', tipo: 'combo', comboKey: 'pisCofins' },
            { key: 'bcPis', label: 'BC PIS', tipo: 'moeda' },
            { key: 'aliqPis', label: 'Alíq. PIS %', tipo: 'moeda' },
            { key: 'valorPis', label: 'Valor PIS', tipo: 'moeda' }
        ]
    },
    {
        titulo: 'COFINS',
        campos: [
            { key: 'cstCofins', label: 'CST COFINS', tipo: 'combo', comboKey: 'pisCofins' },
            { key: 'bcCofins', label: 'BC COFINS', tipo: 'moeda' },
            { key: 'aliqCofins', label: 'Alíq. COFINS %', tipo: 'moeda' },
            { key: 'valorCofins', label: 'Valor COFINS', tipo: 'moeda' }
        ]
    },
    {
        titulo: 'IBS/CBS - Referência',
        campos: [
            { key: 'nItem',    label: 'Nº Item ref.',        tipo: 'text', max: 3,  colClass: 'col-md-2 col-sm-3 col-xs-4' },
            { key: 'chaveRef', label: 'Chave de acesso ref.', tipo: 'text', max: 44, colClass: 'col-md-10 col-sm-9 col-xs-12' }
        ]
    }
];

function campoTributoEl(campo, valor) {
    if (campo.tipo === 'combo') {
        const $sel = $('<select>', { class: 'form-control input-sm inp-trib', 'data-campo': campo.key });
        const opts = combosTributacao[campo.comboKey] || [];
        let valAtual = String(valor || '');
        if (['pisCofins', 'cstIpi'].indexOf(campo.comboKey) !== -1 && /^\d+$/.test(valAtual)) {
            valAtual = String(parseInt(valAtual, 10));
        }
        let temSelecionado = false;
        opts.forEach(function(o) {
            const id = String(o.ID != null ? o.ID : '');
            const sel = id === valAtual;
            if (sel) temSelecionado = true;
            $sel.append($('<option>', { value: id, text: o.LABEL || id, selected: sel }));
        });
        if (valAtual && !temSelecionado) {
            $sel.val('');
        }
        return $sel;
    }
    const v = campo.tipo === 'text' ? (valor || '') : formatMoeda(valor);
    const cls = 'form-control input-sm inp-trib' + (campo.tipo !== 'text' ? ' text-right' : '');
    const $el = $('<input>', { type: 'text', class: cls, 'data-campo': campo.key, value: v });
    if (campo.max) {
        $el.attr('maxlength', campo.max);
    }
    return $el;
}

function renderPreviewTributos(itens, results) {
    const $painel = $('#painel-tributos-itens').empty();

    results.forEach(function(r, i) {
        if (!r || !r.ok) {
            return;
        }
        const p = r.preview;
        const trib = p.tributos || {};
        const sel = itens[i];
        const item = itensDevolucao.find(function(x) {
            return (sel.idNfpOrigem && x.idNfpOrigem == sel.idNfpOrigem)
                || (sel.codProduto && x.codProduto == sel.codProduto);
        });

        // Pré-preenche nItem e chaveRef no objeto trib para o grupo IBS/CBS
        if (trib.nItem == null || trib.nItem === '') {
            trib.nItem = (item && item.nItem > 0) ? String(item.nItem) : '';
        }
        if (trib.chaveRef == null || trib.chaveRef === '') {
            trib.chaveRef = (item && item.chaveRef) ? item.chaveRef : '';
        }
        const descricao = item ? ((item.codProduto ? item.codProduto + ' - ' : '') + item.descricao) : 'Produto';
        const resumoTrib = 'ICMS R$ ' + formatMoeda(trib.valorIcms || 0) +
            ' | IPI R$ ' + formatMoeda(trib.valorIpi || 0) +
            ' | ST R$ ' + formatMoeda((parseFloat(trib.valoricmsst) || 0) + (parseFloat(trib.valorIcmsStRetido) || 0));

        const $box = $('<div>', { class: 'painel-tributo-item' })
            .attr('data-item-key', itemKeyDevolucao(sel))
            .attr('data-total-prod', parseFloat(p.total) || 0);

        const $heading = $('<div>', {
            class: 'panel-heading trib-item-heading',
            role: 'button',
            tabindex: 0,
            'aria-expanded': 'false'
        });
        $heading.append($('<i>', { class: 'fa fa-chevron-right trib-chevron' }));
        $heading.append(' ');
        $heading.append($('<strong>').text(descricao));
        $heading.append($('<span>', { class: 'text-muted small', css: { marginLeft: '10px' } }).text(resumoTrib));

        const $bodyWrap = $('<div>', { class: 'trib-item-body', css: { display: 'none' } });
        const $corpo = $('<div>', { class: 'panel-body trib-corpo' });
        $corpo.append($('<div>', { class: 'row' }).append(
            $('<div>', { class: 'col-md-12' }).html('<strong>Total produto:</strong> R$ ' + formatMoeda(p.total))
        ));

        GRUPOS_TRIBUTOS.forEach(function(grupo) {
            const $grupo = $('<div>', { class: 'trib-grupo-panel' });
            const $gHead = $('<div>', {
                class: 'trib-grupo-heading',
                role: 'button',
                tabindex: 0,
                'aria-expanded': 'false'
            });
            $gHead.append($('<i>', { class: 'fa fa-chevron-right trib-grupo-chevron' }));
            $gHead.append(' ').append($('<span>').text(grupo.titulo));

            const $gBody = $('<div>', { class: 'trib-grupo-body', css: { display: 'none' } });
            const $row = $('<div>', { class: 'row' });
            grupo.campos.forEach(function(campo) {
                const colCls = campo.colClass || 'col-md-3 col-sm-4 col-xs-6';
                const $campo = $('<div>', { class: colCls + ' trib-campo' });
                $campo.append($('<label>', { text: campo.label }));
                $campo.append(campoTributoEl(campo, trib[campo.key]));
                $row.append($campo);
            });
            $gBody.append($row);
            $grupo.append($gHead).append($gBody);
            $corpo.append($grupo);
        });

        $bodyWrap.append($corpo);
        $box.append($heading).append($bodyWrap);
        $painel.append($box);
    });

    const cabFin = lerTotaisCabecalhoFinanceiro();
    previewTributosFrete = cabFin.frete;
    previewTributosDesp = cabFin.desp;
    previewTributosSeguro = cabFin.seguro;
    $('#box-totais-tributacao').data('frete', previewTributosFrete).data('desp', previewTributosDesp).data('seguro', previewTributosSeguro);
    recalcularTotaisTributacao();
}

function recalcularTotaisTributacao() {
    let produtos = 0;
    let icms = 0;
    let ipi = 0;
    let st = 0;
    let pis = 0;
    let cofins = 0;
    let credSn = 0;

    $('#painel-tributos-itens .painel-tributo-item').each(function() {
        produtos += parseFloat($(this).data('total-prod') || 0);
        icms += parseMoeda($(this).find('[data-campo="valorIcms"]').val());
        ipi += parseMoeda($(this).find('[data-campo="valorIpi"]').val());
        st += parseMoeda($(this).find('[data-campo="valoricmsst"]').val());
        st += parseMoeda($(this).find('[data-campo="valorIcmsStRetido"]').val());
        pis += parseMoeda($(this).find('[data-campo="valorPis"]').val());
        cofins += parseMoeda($(this).find('[data-campo="valorCofins"]').val());
        credSn += parseMoeda($(this).find('[data-campo="creditoSn"]').val());
    });

    $('#tot_produtos').text(formatMoeda(produtos));
    $('#tot_icms').text(formatMoeda(icms));
    $('#tot_ipi').text(formatMoeda(ipi));
    $('#tot_st').text(formatMoeda(st));
    $('#tot_cred_sn').text(formatMoeda(credSn));

    const cabFin = lerTotaisCabecalhoFinanceiro();
    previewTributosFrete = cabFin.frete;
    previewTributosDesp = cabFin.desp;
    previewTributosSeguro = cabFin.seguro;
    $('#tot_nf').text(formatMoeda(produtos + previewTributosFrete + previewTributosDesp + previewTributosSeguro));
}

function mergeTributosItens(itens) {
    const map = {};
    $('#painel-tributos-itens .painel-tributo-item').each(function() {
        const key = $(this).attr('data-item-key');
        if (!key) {
            return;
        }
        const trib = {};
        $(this).find('.inp-trib[data-campo]').each(function() {
            const campo = $(this).data('campo');
            if ($(this).hasClass('text-right')) {
                trib[campo] = parseMoeda($(this).val());
            } else {
                trib[campo] = $(this).val();
            }
        });
        map[key] = trib;
    });

    return itens.map(function(item) {
        const key = itemKeyDevolucao(item);
        if (!map[key]) {
            return item;
        }
        return $.extend({}, item, map[key]);
    });
}

function coletarItensComTributos() {
    return mergeTributosItens(coletarItensSelecionados());
}

function atualizarPreviewTributos() {
    let itens;
    try {
        itens = coletarItensSelecionados();
    } catch (e) {
        Swal.fire('Atenção', e.message, 'warning');
        return $.Deferred().reject().promise();
    }

    const deferred = $.Deferred();

    ajaxPost('previewTotais', $.extend({
        itens: JSON.stringify(itens),
        idNfOrigem: $('#idNfOrigem').val(),
        idNatop: $('#ctx_id_natop').val(),
        cenarioCodigo: $('#ctx_cenario_codigo').val(),
        idPessoa: $('#ctx_id_pessoa').val(),
        idNfDev: $('#idNfDev').val() || ''
    }, paramsAjaxTela())).done(function(resp) {
        if (!resp || !resp.ok) {
            Swal.fire('Erro', (resp && resp.erro) ? resp.erro : 'Falha ao calcular tributos.', 'error');
            deferred.reject();
            return;
        }

        const t = resp.totais;
        const cabFin = lerTotaisCabecalhoFinanceiro();
        previewTributosFrete = cabFin.frete;
        previewTributosDesp = cabFin.desp;
        previewTributosSeguro = cabFin.seguro;
        $('#box-totais-tributacao').data('frete', previewTributosFrete).data('desp', previewTributosDesp).data('seguro', previewTributosSeguro);

        if (!itens.length) {
            $('#painel-tributos-itens').empty();
            $('#tot_produtos, #tot_icms, #tot_ipi, #tot_st, #tot_cred_sn, #tot_nf').text('0,00');
            deferred.resolve(true);
            return;
        }

        renderPreviewTributos(itens, resp.itens || []);
        deferred.resolve(true);
    }).fail(function() {
        Swal.fire('Erro', 'Falha na comunicação ao calcular tributos.', 'error');
        deferred.reject();
    });

    return deferred.promise();
}

function salvarRascunho() {
    let itens;
    try {
        itens = coletarItensComTributos();
    } catch (e) {
        Swal.fire('Atenção', e.message, 'warning');
        return $.Deferred().resolve(false).promise();
    }

    Swal.fire({ title: 'Gravando rascunho...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const cabecalho = {
        idNfOrigem: $('#idNfOrigem').val(),
        idNatop: $('#ctx_id_natop').val(),
        emissao: $('#ctx_emissao').val(),
        centroCusto: contextoDevolucao ? contextoDevolucao.centroCusto : '',
        finNFe: parseInt($('#ctx_finalidade_emissao').val() || '4', 10),
        tpNFCredito: $('#ctx_tp_nf_credito').val() || '',
        tpNFDebito: $('#ctx_tp_nf_debito').val() || ''
    };
    $.extend(cabecalho, coletarTransporteCabecalho());
    if (isCadastroManual()) {
        cabecalho.manual = true;
        cabecalho.idPessoa = $('#ctx_id_pessoa').val();
        cabecalho.chnfe = $('#ctx_chnfe').val();
        cabecalho.nfNumero = $('#ctx_nf_numero').val();
        cabecalho.nfSerie = $('#ctx_nf_serie').val();
        cabecalho.cenarioCodigo = $('#ctx_cenario_codigo').val();
    }

    return ajaxPost('salvarRascunho', {
        cabecalho: JSON.stringify(cabecalho),
        itens: JSON.stringify(itens),
        idNfDev: $('#idNfDev').val() || ''
    }).then(function(resp) {
        Swal.close();
        if (!resp.ok) {
            Swal.fire('Erro', resp.erro || 'Falha ao gravar rascunho.', 'error');
            return false;
        }
        $('#idNfDev').val(resp.idNfDev);
        $('#lbl_id_nf_dev').text(resp.idNfDev);
        $('#resumo-final-devolucao').html('<p>NF devolução <strong>#' + resp.idNfDev + '</strong> gravada. Total: <strong>R$ ' + formatMoeda(resp.totalNf) + '</strong></p>');
        return true;
    }, function() {
        Swal.close();
        Swal.fire('Erro', 'Falha ao gravar rascunho.', 'error');
        return false;
    });
}

function gerarEspelhoDevolucao() {
    const idNfDev = $('#idNfDev').val();
    if (!idNfDev) {
        Swal.fire('Atenção', 'Grave o rascunho antes de gerar o espelho.', 'warning');
        return;
    }
    Swal.fire({ title: 'Gerando espelho...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    ajaxPost('gerarEspelhoAjax', { idNfDev: idNfDev })
        .done(function(resp) {
            Swal.close();
            if (!resp.ok) {
                Swal.fire('Erro', resp.erro || 'Falha ao gerar espelho.', 'error');
                return;
            }
            urlsEspelho = resp;
            $('#msg_espelho').text('Espelho gerado com sucesso.');
            if (resp.pdfUrl) window.open(resp.pdfUrl, 'DANFE_ESPELHO', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
            if (resp.xmlUrl) {
                const a = document.createElement('a');
                a.href = resp.xmlUrl;
                a.download = '';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        })
        .fail(function() {
            Swal.close();
            Swal.fire('Erro', 'Falha na comunicação.', 'error');
        });
}

function confirmarDevolucao() {
    const idNfDev = $('#idNfDev').val();
    const origem = $('#origemTela').val() || 'nota_fiscal';
    if (!idNfDev) {
        Swal.fire('Atenção', 'Nenhuma NF de devolução gravada.', 'warning');
        return;
    }
    ajaxPost('confirmar', { idNfDev: idNfDev, origem: origem })
        .done(function(resp) {
            if (!resp.ok) {
                Swal.fire('Erro', resp.erro || 'Falha ao confirmar.', 'error');
                return;
            }
            Swal.fire('Sucesso', 'Devolução concluída.', 'success').then(function() {
                window.location.href = resp.redirectUrl;
            });
        });
}

function emitirNfeDevolucao() {
    const idNfDev = $('#idNfDev').val();
    const origem = $('#origemTela').val() || 'nota_fiscal';
    if (!idNfDev) {
        Swal.fire('Atenção', 'Nenhuma NF de devolução gravada.', 'warning');
        return;
    }
    Swal.fire({
        title: 'Emitir NFe?',
        text: 'A nota será enviada à SEFAZ.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Emitir'
    }).then(function(r) {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Emitindo...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        ajaxPost('emitir', { idNfDev: idNfDev, origem: origem })
            .done(function(resp) {
                Swal.close();
                if (!resp.ok) {
                    Swal.fire('Erro na emissão', resp.erro || 'Falha.', 'error');
                    return;
                }
                if (resp.danfeUrl) window.open(resp.danfeUrl, 'DANFE', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
                Swal.fire('Sucesso', 'NFe emitida com sucesso.', 'success').then(function() {
                    window.location.href = resp.redirectUrl;
                });
            });
    });
}

/** Listagem NF - uma NF por wizard */
function submitDevolucaoNfFromTable(tableId, origem) {
    const table = document.getElementById(tableId);
    if (!table) return false;
    let count = 0;
    let nfId = '';
    for (let i = 1; i < table.rows.length; i++) {
        const row = table.rows.item(i).getElementsByTagName('input');
        if (row.length > 0 && row[0].checked === true) {
            count++;
            nfId = String(row[0].id || '').trim();
            if (!nfId) {
                const cells = table.rows[i].getElementsByTagName('td');
                if (cells.length > 1) {
                    nfId = (cells[1].getAttribute('id') || cells[1].textContent || '').trim();
                }
            }
        }
    }
    if (count === 0) {
        Swal.fire('Atenção', 'Selecione uma NF para devolução.', 'warning');
        return false;
    }
    if (count > 1) {
        Swal.fire('Atenção', 'Selecione apenas uma NF por devolução.', 'warning');
        return false;
    }
    irParaDevolucaoWizard(nfId, origem || 'nota_fiscal');
}

function devolverNfLinha(idNfOrigem) {
    if (!idNfOrigem) {
        Swal.fire('Atenção', 'NF de origem não informada.', 'warning');
        return;
    }
    irParaDevolucaoWizard(idNfOrigem, 'nota_fiscal_devolucao');
}

function submitDevolucaoNf() {
    return submitDevolucaoNfFromTable('datatable-buttons', 'nota_fiscal');
}

window.devolverNfLinha = devolverNfLinha;
window.irParaDevolucaoWizard = irParaDevolucaoWizard;
window.submitDevolucaoNf = submitDevolucaoNf;
window.submitLetraDevolucao = submitLetraDevolucao;
window.continuarDevolucao = continuarDevolucao;
window.abrirNotaFiscalBoletoBancario = abrirNotaFiscalBoletoBancario;
window.cancelarWizardDevolucao = cancelarWizardDevolucao;
window.abrirTransportadorDevolucao = abrirTransportadorDevolucao;
window.submitPesquisaDevolucao = submitPesquisaDevolucao;
