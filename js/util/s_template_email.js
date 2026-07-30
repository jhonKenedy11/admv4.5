function submitConfirmar() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'template_email';
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            var ed = document.getElementById('editor-one');
            var body = document.getElementById('body');
            if (ed && body) {
                body.value = ed.value != null ? String(ed.value) : '';
            }
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
                f.id.value = document.getElementById('id').value;
            }
            f.submit();
        }
    });
}

function atualizarPreviewTemplateEmail() {
    var ed = document.getElementById('editor-one');
    var iframe = document.getElementById('template-preview');
    if (!ed || !iframe) {
        return;
    }
    var html = ed.value != null ? String(ed.value) : '';

    // Garante que links/imagens relativas funcionem no preview
    var baseHref = (document.baseURI || window.location.href || '').split('#')[0];

    var docHtml = "<!doctype html><html><head><meta charset='utf-8'>"
        + "<base href='" + baseHref.replace(/'/g, "%27") + "'>"
        + "</head><body>" + html + "</body></html>";

    // Preferir srcdoc quando disponível; fallback para browsers antigos
    if ('srcdoc' in iframe) {
        iframe.srcdoc = docHtml;
        return;
    }
    try {
        var d = iframe.contentWindow && iframe.contentWindow.document ? iframe.contentWindow.document : null;
        if (!d) {
            return;
        }
        d.open();
        d.write(docHtml);
        d.close();
    } catch (e) {
        // se sandbox/CSP impedir, ignora
    }
}

// Atualiza preview conforme edita/cola
document.addEventListener('DOMContentLoaded', function () {
    var ed = document.getElementById('editor-one');
    if (ed) {
        ed.addEventListener('input', atualizarPreviewTemplateEmail);
        ed.addEventListener('keyup', atualizarPreviewTemplateEmail);
        ed.addEventListener('paste', function () {
            setTimeout(atualizarPreviewTemplateEmail, 0);
        });
    }
    // Alguns layouts montam o DOM após load; tenta novamente logo após
    atualizarPreviewTemplateEmail();
    setTimeout(atualizarPreviewTemplateEmail, 50);
});

function submitVoltar() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'template_email';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'template_email';
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(template_email_id) {
    f = document.lancamento;
    f.mod.value = 'util';
    f.form.value = 'template_email';
    f.submenu.value = 'alterar';
    f.id.value = template_email_id;
    f.submit();
}

function submitExcluir(template_email_id) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente excluir este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'util';
            f.form.value = 'template_email';
            f.submenu.value = 'exclui';
            f.id.value = template_email_id;
            f.submit();
        }
    });
}
