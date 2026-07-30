// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'usuario';
    f.submenu.value = 'cancelar';
    f.submit();
} // fim submitVoltar

function submitConfirmar() {
    f = document.lancamento;
    var ehAlteracao = document.getElementById('ehAlteracao') &&
        document.getElementById('ehAlteracao').value === '1';

    if (f.pessoa.value === '') {
        return alertaCampoUsuario('Selecione a pessoa (conta).', '#tabIdentificacao', f.nome);
    }
    if (f.nomeReduzido.value === '') {
        return alertaCampoUsuario('Digite o nome reduzido.', '#tabIdentificacao', f.nomeReduzido);
    }
    if (f.login.value === '') {
        return alertaCampoUsuario('Digite o login.', '#tabAcesso', f.login);
    }

    var painelSenhaAberto = painelSenhaExpandido('painelSenhaAcesso');
    var alterarSenha = !ehAlteracao || painelSenhaAberto;
    var senha = f.senha ? f.senha.value : '';
    var conf = f.senhaConfirm ? f.senhaConfirm.value : '';

    if (ehAlteracao && !painelSenhaAberto) {
        if (f.senha) {
            f.senha.value = '';
        }
        if (f.senhaConfirm) {
            f.senhaConfirm.value = '';
        }
    }

    if (!ehAlteracao && senha === '') {
        return alertaCampoUsuario('Digite a senha de acesso.', '#tabAcesso', f.senha);
    }
    if (alterarSenha && ehAlteracao && senha === '') {
        return alertaCampoUsuario('Informe a nova senha nos dois campos ou feche a seção de alteração de senha.', '#tabAcesso', f.senha);
    }
    if (alterarSenha && senha !== conf) {
        swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'A nova senha e a confirmação não conferem.'
        }).then(function () {
            showUsuarioTab('#tabAcesso');
            if (f.senhaConfirm) {
                f.senhaConfirm.focus();
            }
        });
        return;
    }

    if (ehAlteracao && f.emailsenha && !painelSenhaExpandido('painelSenhaEmail')) {
        f.emailsenha.value = '';
    }
    if (f.situacao.value === '') {
        return alertaCampoUsuario('Selecione a situação.', '#tabAcesso', f.situacao);
    }

    var dirInput = document.getElementById('direitos_json');
    if (dirInput) {
        dirInput.value = coletarDireitosJson();
    }

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + (ehAlteracao ? 'alterar' : 'cadastrar') + ' este usuário?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f.mod.value = 'util';
            f.form.value = 'usuario';
            if (f.submenu.value == 'cadastrar') {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });
} // fim submitConfirmar

// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'usuario';
    f.submenu.value = 'cadastrar';
    f.usuario.value = '';
    f.pessoa.value = '';
    f.submit();
} // submitCadastro

function submitAlterar(matricula) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja alterar este usuário?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'util';
            f.form.value = 'usuario';
            f.submenu.value = 'alterar';
            f.usuario.value = matricula;
            f.submit();
        }
    });
} // submitAlterar

function submitExcluir(matricula) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja excluir este usuário?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'util';
            f.form.value = 'usuario';
            f.submenu.value = 'exclui';
            f.usuario.value = matricula;
            f.submit();
        }
    });
} // submitExcluir

function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

function showUsuarioTab(tabHref) {
    if (!window.jQuery || !jQuery.fn.tab) {
        return;
    }
    var link = document.querySelector('#usuarioTabs a[href="' + tabHref + '"]');
    if (link) {
        jQuery(link).tab('show');
    }
}

function alertaCampoUsuario(texto, tabHref, campo) {
    swal.fire({
        icon: 'warning',
        title: 'Atenção',
        text: texto
    }).then(function () {
        if (tabHref) {
            showUsuarioTab(tabHref);
        }
        if (campo && campo.focus) {
            campo.focus();
        }
    });
}

function marcarLinhaMudou(tr) {
    if (tr) {
        tr.setAttribute('data-mudou', '1');
    }
}

function coletarDireitosJson() {
    var itens = [];
    document.querySelectorAll('#tabelaDireitos tbody tr.linha-direito').forEach(function (tr) {
        var programa = (tr.getAttribute('data-programa') || '').trim();
        if (!programa) {
            return;
        }
        var letras = [];
        tr.querySelectorAll('.chk-dir:checked').forEach(function (chk) {
            var lt = chk.getAttribute('data-letra');
            if (lt) {
                letras.push(lt);
            }
        });
        letras.sort();
        var direitos = letras.join('');
        var tinha = tr.getAttribute('data-tinha-direito') === '1';
        var mudou = tr.getAttribute('data-mudou') === '1';
        if (direitos !== '' || tinha || mudou) {
            itens.push({ programa: programa, direitos: direitos });
        }
    });
    return JSON.stringify(itens);
}

function toggleProgramaDireitos(btn) {
    var tr = btn ? btn.closest('tr.linha-direito') : null;
    if (!tr) {
        return;
    }
    var checks = tr.querySelectorAll('.chk-dir');
    var algumMarcado = false;
    checks.forEach(function (chk) {
        if (chk.checked) {
            algumMarcado = true;
        }
    });
    checks.forEach(function (chk) {
        chk.checked = !algumMarcado;
    });
    marcarLinhaMudou(tr);
}

function initTooltipsDireitos() {
    if (window._tooltipsDireitosOk || !window.jQuery || !jQuery.fn.tooltip) {
        return;
    }
    var $aba = jQuery('#tabDireitos');
    if (!$aba.length) {
        return;
    }
    $aba.tooltip({
        selector: '.dir-help, .dir-prog-help',
        container: 'body',
        placement: 'left',
        trigger: 'hover',
        animation: false,
        delay: { show: 200, hide: 80 }
    });
    window._tooltipsDireitosOk = true;
}

function initMatriculaPorTipo() {
    var ehAlt = document.getElementById('ehAlteracao');
    if (ehAlt && ehAlt.value === '1') {
        return;
    }
    var tipo = document.getElementById('tipo');
    var usuario = document.getElementById('usuario');
    var proxOp = document.getElementById('proximaMatriculaOperacional');
    var proxGr = document.getElementById('proximaMatriculaGrupo');
    if (!tipo || !usuario) {
        return;
    }
    function atualizar() {
        var isGrupo = (tipo.value === 'Z');
        if (isGrupo && proxGr && proxGr.value) {
            usuario.value = proxGr.value;
        } else if (!isGrupo && proxOp && proxOp.value) {
            usuario.value = proxOp.value;
        }
    }
    tipo.addEventListener('change', atualizar);
}

function painelSenhaExpandido(painelId) {
    var painel = document.getElementById(painelId);
    return !!(painel && painel.classList.contains('in'));
}

function initPainelSenhaExpansivel(painelId, headingId, camposIds) {
    var painel = document.getElementById(painelId);
    var heading = document.getElementById(headingId);
    if (!painel || !heading) {
        return;
    }
    function limparCampos() {
        camposIds.forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.value = '';
            }
        });
    }
    function syncHeading(expanded) {
        heading.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }
    if (window.jQuery) {
        jQuery(painel)
            .on('shown.bs.collapse', function () {
                syncHeading(true);
            })
            .on('hidden.bs.collapse', function () {
                syncHeading(false);
                limparCampos();
            });
    }
}

function initSenhaPainelAlteracao() {
    initPainelSenhaExpansivel('painelSenhaAcesso', 'headingSenhaAcesso', ['senha', 'senhaConfirm']);
    initPainelSenhaExpansivel('painelSenhaEmail', 'headingSenhaEmail', ['emailsenha']);
}

function initUsuarioCadastroDom() {
    initMatriculaPorTipo();
    initSenhaPainelAlteracao();
    var tabela = document.getElementById('tabelaDireitos');
    if (!tabela) {
        return;
    }
    tabela.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('chk-dir')) {
            marcarLinhaMudou(e.target.closest('tr.linha-direito'));
        }
    });
    tabela.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-toggle-prog-dir');
        if (btn) {
            e.preventDefault();
            toggleProgramaDireitos(btn);
        }
    });
    var filtro = document.getElementById('filtroPrograma');
    if (filtro) {
        filtro.addEventListener('keyup', function () {
            var q = filtro.value.toLowerCase();
            tabela.querySelectorAll('tbody tr.linha-direito').forEach(function (tr) {
                var busca = (tr.getAttribute('data-busca') || '').toLowerCase();
                tr.style.display = busca.indexOf(q) >= 0 ? '' : 'none';
            });
        });
    }
    initTooltipsDireitos();
}

function toggleSenha(idCampo, btn) {
    var campo = document.getElementById(idCampo);
    if (!campo) {
        return;
    }
    var icone = btn ? btn.querySelector('span.glyphicon') : null;
    if (campo.type === 'password') {
        campo.type = 'text';
        if (icone) {
            icone.classList.remove('glyphicon-eye-open');
            icone.classList.add('glyphicon-eye-close');
        }
        if (btn) {
            btn.title = 'Ocultar senha';
        }
    } else {
        campo.type = 'password';
        if (icone) {
            icone.classList.remove('glyphicon-eye-close');
            icone.classList.add('glyphicon-eye-open');
        }
        if (btn) {
            btn.title = 'Mostrar senha';
        }
    }
}

document.addEventListener('DOMContentLoaded', initUsuarioCadastroDom);
