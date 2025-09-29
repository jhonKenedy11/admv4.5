function submitAtual(id, alteraCondPgto='' ) {
    debugger;

    f = document.lancamento;
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
    debugger;

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    //f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = 'cadastrar';
    f.submit();
} // fim submit

function submitVoltar(formulario) {
    debugger;

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitVoltarNovo(formulario) {
    debugger;

    f = document.lancamento;
    if(f.t_origem.value === 'nota_fiscal'){
        f.mod.value = 'est';
        f.form.value = 'nota_fiscal';
        f.submenu.value = 'alterar';
    }else{
        f.mod.value = 'ped';
        f.form.value = 'pedido_venda_gerente_novo';
        f.submenu.value = '';
    }
    f.submit();
} // fim submitVoltar


function submitConfirmar(formulario) {
    debugger;

    f = document.lancamento;
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

function submitCadastraNf(id) {
    debugger;

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    if (confirm('Deseja realmente INCLUIR NFe e FATURAMENTO') == true) {
        f.submenu.value = 'cadastraNf';
        f.id.value = id;
    }
    else {
        f.submenu.value = '';
    } // else
    f.submit();
} // submitAlterar


//function submitCadastraFinanceiro(id) {
//    if (confirm('Deseja realmente INCLUIR PARCELAS NO FINANCEIRO') == true) {
//        f = document.lancamento;
//        f.mod.value = 'ped';
//        f.form.value = 'pedido_venda_nf_pecas_novo';
//        f.submenu.value = 'cadastraFinanceiro';
//        f.id.value = id;
//        f.submit();
//    }
//} // submitCadastraFinanceiro

function submitCadastraFinanceiro(id) {
    debugger
    f = document.lancamento;
    swal({
        title: "Atenção!",
        text: "Deseja cadastrar as parcelas no Financeiro?",
        icon: "warning",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            debugger
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_nf_pecas_novo';
            //Teste para verificar a tela origem
            if(f.t_origem.value === 'nota_fiscal'){
                f.submenu.value = 'cadastraFinanceiroNotaFiscal';
            }else{
                f.submenu.value = 'cadastraFinanceiro';
            }
            f.id.value = id;
            f.submit();
        } else {
            return false;
        }
      });
} // submitCadastraFinanceiro


function abrir(pag) {

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}