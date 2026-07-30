function submitVoltar() {
    var f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = '';
    f.submit();
}

function submitConfirmar() {
    var filial = document.getElementById('filial');

    if (filial && !filial.disabled && !filial.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Selecione a empresa (centro de custo).'
        });
        return false;
    }

    if (!fluxoPedidoValidarAntesSalvar()) {
        return false;
    }

    Swal.fire({
        title: 'Atenção!',
        text: 'Deseja prosseguir com o cadastro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            var f = document.lancamento;
            f.mod.value = 'ped';
            f.form.value = 'parametro';
            if (f.submenu.value === 'cadastrar' || f.submenu.value === 'cadastro') {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
}

function submitCadastro() {
    var f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = 'cadastrar';
    f.filial.value = '';
    f.submit();
}

function submitAlterar(parametro) {
    var f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = 'alterar';
    f.filial.value = parametro;
    f.submit();
}

function submitExcluir(parametro) {
    Swal.fire({
        title: 'Atenção!',
        text: 'Deseja realmente excluir esse parâmetro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            var f = document.lancamento;
            f.mod.value = 'ped';
            f.form.value = 'parametro';
            f.submenu.value = 'exclui';
            f.filial.value = parametro;
            f.submit();
        }
    });
}

function submitConsulta() {
    var f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'parametro';
    f.submenu.value = 'consulta';
    f.submit();
}

function submitLimparFiltro() {
    var filtro = document.getElementById('filtro_empresa');
    if (filtro) {
        filtro.value = '';
    }
    submitConsulta();
}

var fluxoPedidoState = null;
var fluxoPedidoSituacoes = [];
var fluxoPedidoModulos = [
    { id: 'encomenda', campo: 'encomenda', label: 'Encomenda', icon: 'fa-shopping-cart', zona: 'zona-encomenda', hint: 'Opcional — sem estoque na confirmação (padrão: Não)' },
    { id: 'controleDesconto', campo: 'controleDesconto', label: 'Desconto / Aprovação', icon: 'fa-percent', zona: 'zona-desconto', hint: 'Limite de desconto e aprovação gerencial na confirmação' },
    { id: 'faturaPedido', campo: 'faturaPedido', label: 'Faturar ao confirmar', icon: 'fa-file-text', zona: 'zona-fatura', hint: 'Financeiro ao confirmar pedido ou encomenda' },
    { id: 'fluxoPedido', campo: 'fluxoPedido', label: 'Fluxo conferência', icon: 'fa-random', zona: 'zona-fluxo', hint: 'Romaneio → conferir' },
    { id: 'lancPedBaixado', campo: 'lancPedBaixado', label: 'Lanç. pedido baixado', icon: 'fa-arrow-circle-down', zona: 'zona-lanc', hint: 'Financeiro ao baixar' }
];
var fluxoPedidoDragModulo = null;
var fluxoPedidoSitEditando = null;
var fluxoPedidoSitTitulos = {
    sitAberto: 'Cotação / digitação',
    sitEmitirNf: 'Emitir NF',
    sitBaixado: 'Baixado / pago'
};

function fluxoPedidoEsc(texto) {
    if (!texto) {
        return '';
    }
    return String(texto)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function fluxoPedidoSitTexto(id) {
    var i, sit;
    for (i = 0; i < fluxoPedidoSituacoes.length; i++) {
        sit = fluxoPedidoSituacoes[i];
        if (String(sit.id) === String(id)) {
            return sit.text;
        }
    }
    return 'Situação ' + id;
}

function fluxoPedidoModuloPorCampo(campo) {
    var i;
    for (i = 0; i < fluxoPedidoModulos.length; i++) {
        if (fluxoPedidoModulos[i].campo === campo) {
            return fluxoPedidoModulos[i];
        }
    }
    return null;
}

function fluxoPedidoAprovacaoMarcada() {
    var radios = document.getElementsByName('aprovacao');
    var i;
    for (i = 0; i < radios.length; i++) {
        if (radios[i].checked === true) {
            return radios[i].value;
        }
    }
    return 'N';
}

function fluxoPedidoResumoDescontoHtml() {
    var dm = document.getElementById('descontoMaximo');
    var td = document.getElementById('tipoDesconto');
    var aprov = fluxoPedidoAprovacaoMarcada();
    var dmVal = dm && dm.value ? dm.value : '0,00';
    var tdVal = 'T';
    if (td) {
        tdVal = td.tagName === 'SELECT'
            ? (td.options[td.selectedIndex] ? td.options[td.selectedIndex].value : 'T')
            : String(td.value || 'T').toUpperCase();
    }
    var aprovTxt = (aprov === 'S') ? 'Aprovação: Sim' : 'Aprovação: Não';
    var tipoTxt = (tdVal === 'L') ? 'Por item' : 'No total';
    return '<div class="fluxo-desconto-resumo">'
        + '<span class="fluxo-desconto-resumo-tag"><i class="fa fa-percent"></i> Máx ' + fluxoPedidoEsc(dmVal) + '%</span>'
        + '<span class="fluxo-desconto-resumo-tag"><i class="fa fa-tag"></i> ' + fluxoPedidoEsc(tipoTxt) + '</span>'
        + '<span class="fluxo-desconto-resumo-tag"><i class="fa fa-thumbs-up"></i> ' + fluxoPedidoEsc(aprovTxt) + '</span>'
        + '</div>';
}

function fluxoPedidoLimparCamposDesconto() {
    var dm = document.getElementById('descontoMaximo');
    var td = document.getElementById('tipoDesconto');
    var radios = document.getElementsByName('aprovacao');
    var i;
    if (dm) {
        dm.value = '0,00';
    }
    if (td) {
        td.value = 'T';
    }
    for (i = 0; i < radios.length; i++) {
        if (radios[i].value === 'N') {
            radios[i].checked = true;
        }
    }
}

function fluxoPedidoMountDescontoConfig() {
    var wrap = document.getElementById('fluxoDescontoConfigWrap');
    var anchor = document.getElementById('fluxoDescontoConfigAnchor');
    if (!wrap || !anchor) {
        return;
    }
    if (wrap.parentNode !== anchor) {
        anchor.appendChild(wrap);
    }
    var ativo = fluxoPedidoModuloAtivo('controleDesconto');
    wrap.style.display = ativo ? 'block' : 'none';
    anchor.classList.toggle('fluxo-config-panel-visivel', ativo);
}

function fluxoPedidoPopularSelectSituacao() {
    var select = document.getElementById('fluxoSitConfigSelect');
    if (!select) {
        return;
    }
    var valorAtual = fluxoPedidoSitEditando && fluxoPedidoState
        ? String(fluxoPedidoState[fluxoPedidoSitEditando] || '')
        : '';
    select.innerHTML = '';
    fluxoPedidoSituacoes.forEach(function (sit) {
        var opt = document.createElement('option');
        opt.value = String(sit.id);
        opt.textContent = sit.text;
        if (valorAtual === String(sit.id)) {
            opt.selected = true;
        }
        select.appendChild(opt);
    });
}

function fluxoPedidoAbrirPainelSituacao(campo) {
    fluxoPedidoSitEditando = campo;
    var anchor = document.getElementById('fluxoSitConfigAnchor');
    var titulo = document.getElementById('fluxoSitConfigTitulo');
    if (titulo) {
        titulo.textContent = fluxoPedidoSitTitulos[campo] || 'Situação';
    }
    fluxoPedidoPopularSelectSituacao();
    if (anchor) {
        anchor.classList.add('fluxo-config-panel-visivel');
    }
    fluxoPedidoRenderTree();
    if (anchor) {
        anchor.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    var select = document.getElementById('fluxoSitConfigSelect');
    if (select) {
        select.focus();
    }
}

function fluxoPedidoFecharPainelSituacao() {
    fluxoPedidoSitEditando = null;
    var anchor = document.getElementById('fluxoSitConfigAnchor');
    if (anchor) {
        anchor.classList.remove('fluxo-config-panel-visivel');
    }
    fluxoPedidoRenderTree();
}

function fluxoPedidoBindSitConfig() {
    var select = document.getElementById('fluxoSitConfigSelect');
    if (!select || select.getAttribute('data-fluxo-bind')) {
        return;
    }
    select.setAttribute('data-fluxo-bind', '1');
    select.addEventListener('change', function () {
        if (fluxoPedidoSitEditando && select.value !== '') {
            fluxoPedidoSetSituacao(fluxoPedidoSitEditando, select.value);
        }
    });
}

function fluxoPedidoHtmlZonaDescontoAtiva(mod) {
    return '<div class="fluxo-dropzone-desconto-title"><i class="fa ' + mod.icon + '"></i> ' + fluxoPedidoEsc(mod.label) + '</div>'
        + fluxoPedidoResumoDescontoHtml()
        + '<span class="fluxo-drop-remove" data-campo="' + mod.campo + '" title="Desativar"><i class="fa fa-times"></i></span>';
}

function fluxoPedidoBindDescontoInputs() {
    var ids = ['descontoMaximo', 'tipoDesconto'];
    ids.forEach(function (id) {
        var el = document.getElementById(id);
        if (!el || el.getAttribute('data-fluxo-bind')) {
            return;
        }
        el.setAttribute('data-fluxo-bind', '1');
        el.addEventListener('input', fluxoPedidoRenderTree);
        el.addEventListener('change', fluxoPedidoRenderTree);
    });
    var radios = document.getElementsByName('aprovacao');
    var i;
    for (i = 0; i < radios.length; i++) {
        if (radios[i].getAttribute('data-fluxo-bind')) {
            continue;
        }
        radios[i].setAttribute('data-fluxo-bind', '1');
        radios[i].addEventListener('change', fluxoPedidoRenderTree);
    }
}

function fluxoPedidoModuloAtivo(campo) {
    return fluxoPedidoState && fluxoPedidoState[campo] === 'S';
}

function fluxoPedidoSyncHidden() {
    var map = {
        sitAberto: 'fluxo_hf_sitAberto',
        sitBaixado: 'fluxo_hf_sitBaixado',
        sitEmitirNf: 'fluxo_hf_sitEmitirNf',
        encomenda: 'fluxo_hf_encomenda',
        fluxoPedido: 'fluxo_hf_fluxoPedido',
        faturaPedido: 'fluxo_hf_faturaPedido',
        lancPedBaixado: 'fluxo_hf_lancPedBaixado',
        controleDesconto: 'fluxo_hf_controleDesconto'
    };
    Object.keys(map).forEach(function (key) {
        var el = document.getElementById(map[key]);
        if (el && fluxoPedidoState) {
            el.value = fluxoPedidoState[key] || '';
        }
    });
}

function fluxoPedidoSetModulo(campo, ativo) {
    if (!fluxoPedidoState) {
        return;
    }
    fluxoPedidoState[campo] = ativo ? 'S' : 'N';
    if (campo === 'controleDesconto' && !ativo) {
        fluxoPedidoLimparCamposDesconto();
    }
    fluxoPedidoSyncHidden();
    fluxoPedidoRenderTree();
}

function fluxoPedidoSetSituacao(campo, id) {
    if (!fluxoPedidoState) {
        return;
    }
    fluxoPedidoState[campo] = String(id);
    fluxoPedidoSyncHidden();
    fluxoPedidoRenderTree();
}

function fluxoPedidoZonaHtml(mod) {
    var ativo = fluxoPedidoModuloAtivo(mod.campo);
    var cls = 'fluxo-dropzone';
    if (mod.campo === 'controleDesconto') {
        cls += ' fluxo-dropzone-desconto';
    }
    cls += ativo ? ' fluxo-dropzone-filled' : '';
    var inner;
    if (ativo && mod.campo === 'controleDesconto') {
        inner = fluxoPedidoHtmlZonaDescontoAtiva(mod);
    } else if (ativo) {
        inner = '<i class="fa ' + mod.icon + '"></i> ' + fluxoPedidoEsc(mod.label)
            + ' <span class="fluxo-drop-remove" data-campo="' + mod.campo + '" title="Desativar"><i class="fa fa-times"></i></span>';
    } else {
        inner = '<i class="fa fa-plus-circle"></i><span>Arraste<br><strong>' + fluxoPedidoEsc(mod.label) + '</strong></span>';
    }
    return '<div class="' + cls + '" data-zona="' + mod.zona + '" data-campo="' + mod.campo + '">' + inner + '</div>';
}

function fluxoPedidoArrow() {
    return '<div class="fluxo-arrow-right" aria-hidden="true"><i class="fa fa-long-arrow-right"></i></div>';
}

function fluxoPedidoStep(html, tipo) {
    var cls = 'fluxo-step';
    if (tipo === 'wide') {
        cls += ' fluxo-step-wide';
    } else if (tipo === 'compact') {
        cls += ' fluxo-step-compact';
    }
    return '<div class="' + cls + '">' + html + '</div>';
}

function fluxoPedidoNodeSimple(titulo, sub, ativo, info) {
    var cls = 'fluxo-node';
    if (ativo) {
        cls += ' fluxo-node-ativo';
    }
    if (info) {
        cls += ' fluxo-node-info';
    }
    var subHtml = sub ? '<span class="fluxo-node-sub">' + fluxoPedidoEsc(sub) + '</span>' : '';
    return '<div class="' + cls + '"><span class="fluxo-node-title">' + fluxoPedidoEsc(titulo) + '</span>' + subHtml + '</div>';
}

function fluxoPedidoChip(label, tipo) {
    var cls = 'fluxo-chip';
    if (tipo === 'ativo') {
        cls += ' fluxo-chip-ativo';
    } else if (tipo === 'warn') {
        cls += ' fluxo-chip-warn';
    } else if (tipo === 'info') {
        cls += ' fluxo-chip-info';
    }
    return '<span class="' + cls + '">' + fluxoPedidoEsc(label) + '</span>';
}

function fluxoPedidoChipArrow() {
    return '<span class="fluxo-chip-arrow">→</span>';
}

function fluxoPedidoPivot(enc, fat) {
    var html = '<div class="fluxo-pivot">';
    html += '<div class="fluxo-pivot-header"><i class="fa fa-cubes"></i> Controla estoque na filial</div>';

    html += '<div class="fluxo-path-row fluxo-path-row-ativo">';
    html += '<span class="fluxo-path-tag">Com produto</span>';
    html += fluxoPedidoChip('Reserva', 'ativo');
    html += fluxoPedidoChipArrow();
    html += fluxoPedidoChip('Pedido', 'ativo');
    if (fat) {
        html += fluxoPedidoChipArrow();
        html += fluxoPedidoChip('Financeiro', 'ativo');
    }
    html += '</div>';

    html += '<div class="fluxo-path-row' + (enc ? ' fluxo-path-row-ativo' : ' fluxo-path-row-off') + '">';
    html += '<span class="fluxo-path-tag">Sem produto</span>';
    if (enc) {
        html += fluxoPedidoChip('Encomenda', 'ativo');
        if (fat) {
            html += fluxoPedidoChipArrow();
            html += fluxoPedidoChip('Financeiro', 'ativo');
        }
        html += fluxoPedidoChipArrow();
        html += fluxoPedidoChip('Entrada CC', 'info');
        html += fluxoPedidoChipArrow();
        html += fluxoPedidoChip('Pedido', 'info');
    } else {
        html += fluxoPedidoChip('Bloqueia confirmação', 'warn');
    }
    html += '<div class="fluxo-path-drop">' + fluxoPedidoZonaHtml(fluxoPedidoModulos[0]) + '</div>';
    html += '</div>';

    html += '</div>';
    return html;
}

function fluxoPedidoSitNodeHtml(campo, titulo, hint) {
    var id = fluxoPedidoState[campo];
    var texto = fluxoPedidoSitTexto(id);
    var cls = 'fluxo-node fluxo-node-sit fluxo-node-ativo';
    if (fluxoPedidoSitEditando === campo) {
        cls += ' fluxo-node-sit-editando';
    }
    return ''
        + '<div class="' + cls + '" data-sit-campo="' + campo + '">'
        + '<span class="fluxo-node-title">' + fluxoPedidoEsc(titulo) + '</span>'
        + '<span class="fluxo-node-sub">' + fluxoPedidoEsc(texto) + '</span>'
        + '<span class="fluxo-node-hint">' + fluxoPedidoEsc(hint) + '</span>'
        + '</div>';
}

function fluxoPedidoRenderTree() {
    var container = document.getElementById('fluxoPedidoTree');
    if (!container || !fluxoPedidoState) {
        return;
    }

    var enc = fluxoPedidoModuloAtivo('encomenda');
    var fat = fluxoPedidoModuloAtivo('faturaPedido');
    var flux = fluxoPedidoModuloAtivo('fluxoPedido');
    var ctrlDesc = fluxoPedidoModuloAtivo('controleDesconto');
    var aprovDesc = fluxoPedidoAprovacaoMarcada() === 'S';
    var modDesc = fluxoPedidoModuloPorCampo('controleDesconto');
    var fase1 = '';
    var fase2 = '';

    var palette = '<div class="fluxo-palette"><div class="fluxo-palette-title"><i class="fa fa-puzzle-piece"></i> Módulos opcionais — arraste para a zona tracejada (ou duplo clique na paleta)</div>';
    fluxoPedidoModulos.forEach(function (mod) {
        var ativo = fluxoPedidoModuloAtivo(mod.campo);
        palette += '<span class="fluxo-modulo' + (ativo ? ' fluxo-modulo-ativo' : '') + '" draggable="true" data-modulo="' + mod.campo + '" title="' + fluxoPedidoEsc(mod.hint) + '">'
            + '<i class="fa ' + mod.icon + '"></i> ' + fluxoPedidoEsc(mod.label) + '</span>';
    });
    palette += '</div>';

    fase1 += fluxoPedidoStep(fluxoPedidoSitNodeHtml('sitAberto', 'Cotação / digitação', 'Clique para configurar'));
    fase1 += fluxoPedidoArrow();
    fase1 += fluxoPedidoStep(fluxoPedidoNodeSimple('Confirmar pedido', 'Confirma ou envia para encomenda', true), 'compact');
    fase1 += fluxoPedidoArrow();
    if (modDesc) {
        var stepCls = 'fluxo-step fluxo-step-wide fluxo-desconto-step';
        if (ctrlDesc) {
            stepCls += ' fluxo-desconto-step-ativo';
        }
        fase1 += '<div class="' + stepCls + '" id="fluxoDescontoStep">'
            + fluxoPedidoZonaHtml(modDesc)
            + '</div>';
        if (ctrlDesc && aprovDesc) {
            fase1 += fluxoPedidoArrow();
            fase1 += fluxoPedidoStep(fluxoPedidoNodeSimple('Em aprovação', 'Desconto acima do limite (sit. 10)', true), 'compact');
        }
        fase1 += fluxoPedidoArrow();
    }
    fase1 += '<div class="fluxo-step fluxo-step-pivot">' + fluxoPedidoPivot(enc, fat) + '</div>';

    fase2 += fluxoPedidoStep(fluxoPedidoZonaHtml(fluxoPedidoModulos[2]));
    if (fat) {
        fase2 += fluxoPedidoArrow();
        fase2 += fluxoPedidoStep(fluxoPedidoNodeSimple('Financeiro / NF', 'Na confirmação do pedido ou encomenda', true), 'compact');
    }
    fase2 += fluxoPedidoArrow();
    fase2 += fluxoPedidoStep(fluxoPedidoNodeSimple('Gerência', 'Romaneio / expedição', false, true), 'compact');
    fase2 += fluxoPedidoArrow();
    fase2 += fluxoPedidoStep(fluxoPedidoZonaHtml(fluxoPedidoModulos[3]));
    if (flux) {
        fase2 += fluxoPedidoArrow();
        fase2 += fluxoPedidoStep(fluxoPedidoNodeSimple('Conferir', 'Conferência de romaneio', true), 'compact');
    }
    fase2 += fluxoPedidoArrow();
    fase2 += fluxoPedidoStep(fluxoPedidoSitNodeHtml('sitEmitirNf', 'Emitir NF', 'Clique para configurar'));
    fase2 += fluxoPedidoArrow();
    fase2 += fluxoPedidoStep(fluxoPedidoZonaHtml(fluxoPedidoModulos[4]));
    fase2 += fluxoPedidoArrow();
    fase2 += fluxoPedidoStep(fluxoPedidoSitNodeHtml('sitBaixado', 'Baixado / pago', 'Clique para configurar'));

    container.innerHTML = palette
        + '<div class="fluxo-canvas">'
        + '<div class="fluxo-phase"><div class="fluxo-phase-label"><i class="fa fa-pencil-square-o"></i> 1 — Cotação, pedido e estoque</div>'
        + '<div class="fluxo-track-scroll"><div class="fluxo-row">' + fase1 + '</div></div></div>'
        + '<div class="fluxo-v-link"><i class="fa fa-long-arrow-down"></i></div>'
        + '<div class="fluxo-merge-note">Pedido confirmado ou encomenda liberada segue para faturamento</div>'
        + '<div class="fluxo-v-link"><i class="fa fa-long-arrow-down"></i></div>'
        + '<div class="fluxo-phase"><div class="fluxo-phase-label"><i class="fa fa-money"></i> 2 — Faturamento e pós-venda</div>'
        + '<div class="fluxo-track-scroll"><div class="fluxo-row">' + fase2 + '</div></div></div>'
        + '</div>';
    fluxoPedidoBindTreeEvents();
    fluxoPedidoMountDescontoConfig();
    if (fluxoPedidoSitEditando) {
        var sitAnchor = document.getElementById('fluxoSitConfigAnchor');
        if (sitAnchor) {
            sitAnchor.classList.add('fluxo-config-panel-visivel');
        }
    }
}

function fluxoPedidoBindTreeEvents() {
    var container = document.getElementById('fluxoPedidoTree');
    if (!container) {
        return;
    }

    container.querySelectorAll('.fluxo-modulo').forEach(function (chip) {
        chip.addEventListener('dragstart', function (e) {
            fluxoPedidoDragModulo = chip.getAttribute('data-modulo');
            chip.classList.add('fluxo-modulo-dragging');
            if (e.dataTransfer) {
                e.dataTransfer.setData('text/plain', fluxoPedidoDragModulo);
                e.dataTransfer.effectAllowed = 'move';
            }
        });
        chip.addEventListener('dragend', function () {
            chip.classList.remove('fluxo-modulo-dragging');
            fluxoPedidoDragModulo = null;
        });
        chip.addEventListener('dblclick', function () {
            var campo = chip.getAttribute('data-modulo');
            fluxoPedidoSetModulo(campo, !fluxoPedidoModuloAtivo(campo));
        });
    });

    container.querySelectorAll('.fluxo-dropzone').forEach(function (zona) {
        zona.addEventListener('dragover', function (e) {
            e.preventDefault();
            zona.classList.add('fluxo-dropzone-over');
        });
        zona.addEventListener('dragleave', function () {
            zona.classList.remove('fluxo-dropzone-over');
        });
        zona.addEventListener('drop', function (e) {
            e.preventDefault();
            zona.classList.remove('fluxo-dropzone-over');
            var campo = zona.getAttribute('data-campo');
            var arrastado = fluxoPedidoDragModulo || (e.dataTransfer ? e.dataTransfer.getData('text/plain') : '');
            if (arrastado === campo) {
                fluxoPedidoSetModulo(campo, true);
            }
        });
        zona.addEventListener('click', function (e) {
            if (e.target.closest('.fluxo-drop-remove')) {
                return;
            }
            var campo = zona.getAttribute('data-campo');
            if (campo === 'controleDesconto') {
                if (!fluxoPedidoModuloAtivo(campo)) {
                    fluxoPedidoSetModulo(campo, true);
                } else {
                    fluxoPedidoFecharPainelSituacao();
                    var ancDesc = document.getElementById('fluxoDescontoConfigAnchor');
                    if (ancDesc) {
                        ancDesc.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
                return;
            }
            if (fluxoPedidoModuloAtivo(campo)) {
                fluxoPedidoSetModulo(campo, false);
            }
        });
    });

    container.querySelectorAll('.fluxo-drop-remove').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            fluxoPedidoSetModulo(btn.getAttribute('data-campo'), false);
        });
    });

    container.querySelectorAll('.fluxo-node-sit').forEach(function (node) {
        node.addEventListener('click', function () {
            var campo = node.getAttribute('data-sit-campo');
            if (fluxoPedidoSitEditando === campo) {
                fluxoPedidoFecharPainelSituacao();
            } else {
                fluxoPedidoAbrirPainelSituacao(campo);
            }
        });
    });
}

function fluxoPedidoAplicarDefaultsModulos() {
    if (!fluxoPedidoState) {
        return;
    }
    var defaults = {
        encomenda: 'N',
        controleDesconto: 'N',
        faturaPedido: 'N',
        lancPedBaixado: 'N',
        fluxoPedido: 'S'
    };
    Object.keys(defaults).forEach(function (key) {
        if (fluxoPedidoState[key] !== 'S' && fluxoPedidoState[key] !== 'N') {
            fluxoPedidoState[key] = defaults[key];
        }
    });
    fluxoPedidoSyncHidden();
}

function fluxoPedidoValidarAntesSalvar() {
    fluxoPedidoAplicarDefaultsModulos();
    var box = document.querySelector('.fluxo-pedido-tree-box');
    if (!fluxoPedidoState.sitAberto || !fluxoPedidoState.sitBaixado || !fluxoPedidoState.sitEmitirNf) {
        if (box) {
            box.style.boxShadow = '0 0 0 3px #f500009e';
            setTimeout(function () { box.style.boxShadow = ''; }, 4000);
        }
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Clique nos nós de situação do fluxo e selecione Aberto, Emitir NF e Baixado.'
        });
        return false;
    }
    if (fluxoPedidoModuloAtivo('controleDesconto')) {
        var dm = document.getElementById('descontoMaximo');
        var td = document.getElementById('tipoDesconto');
        var dmTxt = dm && dm.value ? dm.value.replace(/\./g, '').replace(',', '.') : '0';
        if (parseFloat(dmTxt) <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Informe o desconto máximo (%) no módulo Desconto / Aprovação.'
            });
            if (dm) {
                dm.focus();
            }
            return false;
        }
        if (!td || !td.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'Selecione o tipo de desconto no módulo Desconto / Aprovação.'
            });
            if (td) {
                td.focus();
            }
            return false;
        }
    }
    return true;
}

function fluxoPedidoInit() {
    var cfgEl = document.getElementById('fluxoPedidoCfgData');
    if (!cfgEl) {
        return;
    }
    try {
        var cfg = JSON.parse(cfgEl.textContent);
        fluxoPedidoSituacoes = cfg.situacoes || [];
        fluxoPedidoState = Object.assign({
            sitAberto: '',
            sitBaixado: '',
            sitEmitirNf: '',
            encomenda: 'N',
            controleDesconto: 'N',
            fluxoPedido: 'S',
            faturaPedido: 'N',
            lancPedBaixado: 'N'
        }, cfg.valores || {});
    } catch (err) {
        return;
    }
    fluxoPedidoAplicarDefaultsModulos();
    fluxoPedidoBindDescontoInputs();
    fluxoPedidoBindSitConfig();
    fluxoPedidoRenderTree();
}

document.addEventListener('DOMContentLoaded', function () {
    fluxoPedidoInit();
});
