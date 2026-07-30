function formFinanceiroPecasNovo() {
    var forms = document.querySelectorAll('form[name=lancamento], form#lancamento');
    var i;
    for (i = 0; i < forms.length; i++) {
        var formInput = forms[i].elements['form'] || forms[i].querySelector('input[name=form]');
        if (formInput && formInput.value === 'pedido_venda_nf_pecas_novo') {
            return forms[i];
        }
    }
    var credEl = document.querySelector('form input[name=saldoCredito]');
    if (credEl && credEl.form) {
        return credEl.form;
    }
    var f = document.lancamento;
    return (f && f.length) ? f[0] : f;
}

function formFieldValue(form, fieldName, fallback) {
    if (!form) {
        return fallback !== undefined ? fallback : '';
    }
    var el = form.elements[fieldName] || form.querySelector('[name="' + fieldName + '"]');
    return el ? el.value : (fallback !== undefined ? fallback : '');
}

function submitAtual(id, alteraCondPgto='' ) {
     

    f = formFinanceiroPecasNovo();
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    //f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = f.submenu.value;
    if(alteraCondPgto == 'true'){
        f.alteraCondPgto.value = true;
    }
    f.submit();
} // fim submit

function submitCadastro(id) {
     

    f = formFinanceiroPecasNovo();
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    //f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = 'cadastrar';
    f.submit();
} // fim submit

function submitVoltar(formulario) {
     

    f = formFinanceiroPecasNovo();
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitVoltarNovo(formulario) {
     

    f = formFinanceiroPecasNovo();

    if(f.t_origem.value === 'pedido_ps'){
        f.mod.value = 'ped';
        f.form.value = 'pedido_ps';
        f.submenu.value = '';
        f.submit();
        return;
    }

    if(f.t_origem.value === 'nota_fiscal'){
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal';
        f.submenu.value = 'alterar';
        f.submit();
        return;
    }

    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_gerente_novo';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar


function submitConfirmar(formulario) {
     

    f = formFinanceiroPecasNovo();
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo_pecas';
    f.opcao.value = formulario;
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        f.submenu.value = 'incluir';
    }
    else {
        f.submenu.value = '';
    } // else
    f.submit();
} // fim submitConfirmar

function pedidoPsEstaEmEncomenda() {
    var f = document.lancamento;
    return f && f.pedidoSituacao && parseInt(f.pedidoSituacao.value, 10) === 13;
}

function alertaEncomendaBloqueiaNf() {
    Swal.fire({
        icon: 'info',
        title: 'Pedido em encomenda',
        html: 'A emissão de NF está bloqueada enquanto o pedido aguarda entrada de estoque.<br>'
            + 'Cadastre o financeiro e aguarde a liberação do material.',
        confirmButtonText: 'OK'
    });
}

function submitCadastraNf(id, ehCupomFiscal) {
    if (pedidoPsEstaEmEncomenda()) {
        alertaEncomendaBloqueiaNf();
        return;
    }
    f = formFinanceiroPecasNovo();
    var vendaPresencial = ehCupomFiscal ? 'S' : (document.querySelector('input[name=vendaPresencial]:checked') || {}).value;
    if (vendaPresencial !== 'S') {
        prosseguirEmissaoNf(id);
        return;
    }

    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';

    var parcelasCadastrada = (String(formFieldValue(f, 'parcelasCadastrada', '0')) === '1');
    var saldoNum = parseFloat(String(formFieldValue(f, 'saldoCredito', '0')).replace(',', '.')) || 0;

    if (!parcelasCadastrada && saldoNum > 0) {
            Swal.fire({
                title: "Usar saldo de crédito",
                icon: "info",
                html: 'Saldo disponível: <b>R$ ' + saldoNum + '</b><br><br>Informe o valor que deseja usar:',
                input: "text",
                inputValue: saldoNum,
                inputAttributes: {
                    min: 0,
                    max: saldoNum,
                    step: "0.01"
                },
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Usar crédito",
                denyButtonText: "Não usar crédito",
                cancelButtonText: "Cancelar",
                preConfirm: (valorInformado) => {
                    const valor = parseFloat(String(valorInformado || '0').replace(',', '.'));
                    if (isNaN(valor) || valor <= 0) {
                        Swal.showValidationMessage("Informe um valor de crédito maior que zero.");
                        return false;
                    }
                    if (valor > saldoNum) {
                        Swal.showValidationMessage("O valor informado não pode ser maior que o saldo disponível.");
                        return false;
                    }
                    return valor;
                }
            }).then((result) => {
                if(result.isConfirmed){
                    f.credito.value = result.value;
                    f.usarCredito.value = 'S';
                    f.mod.value = 'ped';
                    f.form.value = 'pedido_venda_nf_pecas_novo';
                    f.submenu.value = 'cadastraNf';
                    f.id.value = id;
                    f.submit();
                    return;
                }
                if (result.isDenied) {
                    f.credito.value = 0;
                    f.usarCredito.value = 'N';
                    prosseguirEmissaoNf(id, ehCupomFiscal);
                    return;
                }
                f.submenu.value = '';
                return false;
            });
    } else {
        f.credito.value = 0;
        f.usarCredito.value = 'N';
        prosseguirEmissaoNf(id, ehCupomFiscal);
    }
}

function prosseguirEmissaoNf(id, ehCupomFiscal) {
    if (pedidoPsEstaEmEncomenda()) {
        alertaEncomendaBloqueiaNf();
        return;
    }
    f = formFinanceiroPecasNovo();
    var msgConfirmacao = ehCupomFiscal
        ? 'Deseja prosseguir com a emissão da NFC-e (cupom fiscal) e inclusão do faturamento?'
        : 'Deseja prosseguir com a emissão da NF-e e inclusão do faturamento?';
    var msgAguarde = ehCupomFiscal ? 'Emitindo cupom fiscal...' : 'Aguarde...';

            Swal.fire({
                title: "Atenção!",
                icon: "info",
                text: msgConfirmacao,
                showCancelButton: true,
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                customClass: {
                    popup: 'classEmitirNota',
                }
            }).then((result) => {
                 
                if (result.isConfirmed) {
                    
                var loadingIconHtml = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';

                Swal.fire({
                    html: loadingIconHtml + '<p>' + msgAguarde + '</p>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        f.mod.value = 'ped';
                        f.form.value = 'pedido_venda_nf_pecas_novo';
                        f.submenu.value = 'cadastraNf';
                        f.id.value = id;
                        f.submit();
                    }
                });
            } else {
                f.submenu.value = '';
                return false;
            }
        });
}




function validaNaturezaOperacao(cliente, natOperacao){
     
    var letra = 'letra=' + cliente + '|' + natOperacao;
    var retorno;
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=nat_operacao&submenu=validaNaturezaOperacao&opcao=blank",
        data: letra,
        dataType: "text",
        async: false,
        success: function(response){
             
            retorno = response.replace(/[\\"]/g, '');
          } 
    });//fim yes
    return retorno;
}



function submitCadastraFinanceiro(id) {
     
    f = formFinanceiroPecasNovo();
    let saldoCredito = formFieldValue(f, 'saldoCredito', '0');
    const parcelasCadastrada = (String(formFieldValue(f, 'parcelasCadastrada', '0')) === '1');
    const saldoNum = parseFloat(String(saldoCredito || '0').replace(',', '.')) || 0;
    const isExtrato = (String(formFieldValue(f, 'financeiroCondExtrato', '0')) === '1');

    // Se já existe financeiro (parcelas cadastradas), não deve perguntar para usar o saldo novamente.
    if (!parcelasCadastrada && saldoNum > 0) {
        Swal.fire({
            title: "Usar saldo de crédito",
            icon: "info",
            html: 'Saldo disponível: <b>R$ ' + saldoNum + '</b><br><br>Informe o valor que deseja usar:',
            input: "text",
            inputValue: saldoNum,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Usar crédito",
            denyButtonText: "Não usar crédito",
            cancelButtonText: "Cancelar",
            preConfirm: (valorInformado) => {
                const valor = parseFloat(String(valorInformado || '0').replace(',', '.')) || 0;
                if (valor <= 0) {
                    Swal.showValidationMessage("Informe um valor de crédito maior que zero.");
                    return false;
                }
                if (valor > saldoNum) {
                    Swal.showValidationMessage("O valor informado não pode ser maior que o saldo disponível.");
                    return false;
                }
                return valor;
            }
        }).then((r) => {
            if (r.isConfirmed) {
                f.credito.value = r.value || 0;
                f.usarCredito.value = 'S';
            } else if (r.isDenied) {
                f.credito.value = 0;
                f.usarCredito.value = 'N';
            } else {
                return false;
            }
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_nf_pecas_novo';
            f.submenu.value = (f.t_origem.value === 'nota_fiscal') ? 'cadastraFinanceiroNotaFiscal' : 'cadastraFinanceiro';
            f.id.value = id;
            f.submit();
        });
        return;
    }

    f.credito.value = 0;
    f.usarCredito.value = 'N';

    const doSubmit = () => {
        f.mod.value = 'ped';
        f.form.value = 'pedido_venda_nf_pecas_novo';
        f.submenu.value = (formFieldValue(f, 't_origem', '') === 'nota_fiscal') ? 'cadastraFinanceiroNotaFiscal' : 'cadastraFinanceiro';
        f.id.value = id;
        f.submit();
    };

    if (isExtrato) {
        var posFinExtrato = f.elements['pos_financeiro_ps'];
        if (posFinExtrato) posFinExtrato.value = '9';
        doSubmit();
        return;
    }

    var sitPedidoFin = parseInt(formFieldValue(f, 'pedidoSituacao', '0'), 10);
    var tituloFin = sitPedidoFin === 13
        ? 'Confirmar financeiro da encomenda?'
        : 'Deseja confirmar o financeiro?';
    var textoFin = sitPedidoFin === 13
        ? 'O pedido permanecerá em encomenda até a entrada de estoque. A NF não será emitida agora.'
        : '';

    Swal.fire({
        title: tituloFin,
        html: textoFin,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
    }).then((r) => {
        if (r.isConfirmed) {
            var posFin = f.elements['pos_financeiro_ps'];
            if (posFin) {
                posFin.value = sitPedidoFin === 13 ? '13' : '3';
            }
            doSubmit();
        }
    });
} // submitCadastraFinanceiro


function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

// Wrapper global usado pelos botões de erro do espelho (abre nova janela/aba)
function openNewWin(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=800,height=600,scrollbars=yes,resizable=yes');
}

function printDanfe(id) {
    window.open('index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id='+id, 'DANFE', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function submitGeraEspelhoJson(id) {
     
    f = formFinanceiroPecasNovo();
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    f.submenu.value = 'geraEspelhoJson';
    f.id.value = id;
    f.submit();
} // fim submitGeraEspelhoJson

document.addEventListener('DOMContentLoaded', function() {
    var btnConfirma = document.getElementsByClassName('classEmitirNota');
    if (btnConfirma.length > 0 && btnConfirma[0].hasAttribute('aria-label')) {
        btnConfirma[0].removeAttribute('aria-label');
    }

    var campoData = document.getElementById('dataSaidaEntrada');
    if (campoData && !campoData.value) {
        var hoje = new Date();
        campoData.value = String(hoje.getDate()).padStart(2, '0') + '/'
            + String(hoje.getMonth() + 1).padStart(2, '0') + '/'
            + hoje.getFullYear() + ' '
            + String(hoje.getHours()).padStart(2, '0') + ':'
            + String(hoje.getMinutes()).padStart(2, '0');
    }
});