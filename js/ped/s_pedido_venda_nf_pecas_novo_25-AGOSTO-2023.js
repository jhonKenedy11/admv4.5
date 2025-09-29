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

    let cliente = f.cliente.value;
    let natOperacao = f.idNatop.value;
    let validaNatOp = validaNaturezaOperacao(cliente, natOperacao);

    if(validaNatOp == 'null'){ //retorno null significa que os parametros foram aceitos
        f.mod.value = 'ped';
        f.form.value = 'pedido_venda_nf_pecas_novo';
        swal({
            title: "Atenção!",
            icon: "info",
            text: "Deseja prosseguir com a emissão da NF-e e inclusão do faturamento?",
            buttons: true,
          })
          .then((yes) => {
            if (yes) {
                debugger
                f.submenu.value = 'cadastraNf';
                f.id.value = id;
                f.submit();
            } else {
                f.submenu.value = '';
                return false;
            }
          });
    }else{ //se os parametros nao forem aceitos informará msg na tela
        swal({
            title: "Atenção!",
            text: "" + validaNatOp + "",
            icon: "error",
            button: "OK",
            dangerMode: true,
        });
    }
} // submitAlterar

function validaNaturezaOperacao(cliente, natOperacao){
    debugger
    var letra = 'letra=' + cliente + '|' + natOperacao;
    var retorno;
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=nat_operacao&submenu=validaNaturezaOperacao&opcao=blank",
        data: letra,
        dataType: "text",
        async: false,
        success: function(response){
            debugger
            retorno = response.replace(/[\\"]/g, '');
          } 
    });//fim yes
    return retorno;
}


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
