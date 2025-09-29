function submitAtual(id, alteraCondPgto='' ) {
     

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
     

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    //f.opcao.value = '';
    f.id.value = id;
    f.submenu.value = 'cadastrar';
    f.submit();
} // fim submit

function submitVoltar(formulario) {
     

    f = document.lancamento;
    f.mod.value = 'ped';
    f.form.value = 'pedido_venda_nf_pecas_novo';
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitVoltarNovo(formulario) {
     

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

// function submitCadastraNf(id) {
//      
//     f = document.lancamento;
//     if(vendaPresencial){
//         let cliente = f.cliente.value;
//         let natOperacao = f.idNatop.value;
//         //let validaNatOp = validaNaturezaOperacao(cliente, natOperacao);
    
//         //if(validaNatOp == 'null'){ //retorno null significa que os parametros foram aceitos
//             f.mod.value = 'ped';
//             f.form.value = 'pedido_venda_nf_pecas_novo';
//             swal({
//                 title: "Atenção!",
//                 icon: "info",
//                 text: "Deseja prosseguir com a emissão da NF-e e inclusão do faturamento?",
//                 buttons: true,
//               })
//               .then((yes) => {
//                 if (yes) {
//                      
//                     // Cria o HTML personalizado com o ícone animado
//                     var loadingIconHtml = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';

//                     // Mostra a mensagem de carregamento usando SweetAlert2 com o ícone animado
//                     var loadingMessage = Swal.fire({
//                         html: loadingIconHtml + '<p>Aguarde...</p>',
//                         allowOutsideClick: false,
//                         showConfirmButton: false,
//                         didOpen: () => {
//                             f.submenu.value = 'cadastraNf';
//                             f.id.value = id;
//                             f.submit();
//                         }
//                     });

//                 } else {
//                     f.submenu.value = '';
//                     return false;
//                 }
//               });
//         // }else{ //se os parametros nao forem aceitos informará msg na tela
//         //     swal({
//         //         title: "Atenção!",
//         //         text: "" + validaNatOp + "",
//         //         icon: "error",
//         //         button: "OK",
//         //         dangerMode: true,
//         //     });
//         // }
//     }

// } // submitAlterar

function submitCadastraNf(id) {
     
    f = document.lancamento;
    if(vendaPresencial){
        let cliente = f.cliente.value;
        let natOperacao = f.idNatop.value;

        //if(validaNatOp == 'null'){ //retorno null significa que os parametros foram aceitos
        f.mod.value = 'ped';
        f.form.value = 'pedido_venda_nf_pecas_novo';

        Swal.fire({
            title: "Atenção!",
            icon: "info",
            text: "Deseja prosseguir com a emissão da NF-e e inclusão do faturamento?",
            showCancelButton: true,
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            customClass: {
                popup: 'classEmitirNota',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                 
                // Cria o HTML personalizado com o ícone animado
                var loadingIconHtml = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';

                // Mostra a mensagem de carregamento usando SweetAlert2 com o ícone animado
                Swal.fire({
                    html: loadingIconHtml + '<p>Aguarde...</p>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
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


// function submitCadastraFinanceiro(id) {
//      
//     f = document.lancamento;
//     swal({
//         title: "Atenção!",
//         text: "Deseja cadastrar as parcelas no Financeiro?",
//         icon: "warning",
//         buttons: true,
//       })
//       .then((willDelete) => {
//         if (willDelete) {
//              
//             f.mod.value = 'ped';
//             f.form.value = 'pedido_venda_nf_pecas_novo';
//             //Teste para verificar a tela origem
//             if(f.t_origem.value === 'nota_fiscal'){
//                 f.submenu.value = 'cadastraFinanceiroNotaFiscal';
//             }else{
//                 f.submenu.value = 'cadastraFinanceiro';
//             }
//             f.id.value = id;
//             f.submit();
//         } else {
//             return false;
//         }
//       });
// } // submitCadastraFinanceiro

function submitCadastraFinanceiro(id) {
     
    f = document.lancamento;
    Swal.fire({
        title: "Atenção!",
        text: "Deseja cadastrar as parcelas no Financeiro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
    }).then((result) => {
        if (result.isConfirmed) {
             
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_nf_pecas_novo';
            // Teste para verificar a tela origem
            if (f.t_origem.value === 'nota_fiscal') {
                f.submenu.value = 'cadastraFinanceiroNotaFiscal';
            } else {
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

function printDanfe(id) {
    window.open('index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id='+id, 'DANFE', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

 
// Suponhamos que você tenha uma referência para o elemento HTML
var btnConfirma = document.getElementsByClassName("classEmitirNota");

// Verifique se o elemento possui o atributo aria-label antes de removê-lo
if (btnConfirma.hasAttribute("aria-label")) {
    btnConfirma.removeAttribute("aria-label");
}

// Função para inicializar data atual no campo dataSaidaEntrada se estiver vazio
document.addEventListener('DOMContentLoaded', function() {
    var campoData = document.getElementById('dataSaidaEntrada');
    if (campoData && !campoData.value) {
        var hoje = new Date();
        var dia = String(hoje.getDate()).padStart(2, '0');
        var mes = String(hoje.getMonth() + 1).padStart(2, '0');
        var ano = hoje.getFullYear();
        var hora = String(hoje.getHours()).padStart(2, '0');
        var minuto = String(hoje.getMinutes()).padStart(2, '0');
        campoData.value = dia + '/' + mes + '/' + ano + ' ' + hora + ':' + minuto;
    }
});