// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    if(formulario == 'conta_banco'){

        if(f.nomeInterno.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Nome Interno é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }

        if(f.nomeContaBanco.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Nome Conta é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }

        if(f.banco.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Banco é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }

        if(f.agencia.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Agencia é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }

        if(f.contaCorrente.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Código Conta é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }

        if(f.contato.value == ''){
            swal.fire({
                icon: 'warning',
                title: "O Campo Contato é Obrigatório!",
                timer: 1000,
                showConfirmButton: false
            });
            return false;
        }
    }

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente ' + f.submenu.value + ' este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        }
    });

    // f.submit();
    // } // if
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(formulario, id) {

    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Alterar este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = formulario;
            f.submenu.value = 'alterar';
            f.id.value = id;
            f.submit();
        }
    });
} // submitAlterar

function submitExcluir(formulario, id) {
    swal.fire({
        title: 'Confirmação',
        text: 'Deseja realmente Excluir este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = formulario;
            f.submenu.value = 'exclui';
            f.id.value = id;
            f.submit();
        }
    });
} // submitExcluir

function submitLetra(formulario, letra_pesquisa) {
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = formulario;
        f.opcao.value = '';
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}
	
// ATUALIZA TIPO DE LANCAMENTO ( RECEBIMENTO / PAGAMENTO )
function generoLancamento(id, desc, tipoLanc) {
    f = window.opener.document.lancamento;

    f.genero.value = id;
    f.descgenero.value = desc;
    
    if (window.opener.document.getElementById('divDescTipo') != null){    
        var labe1= window.opener.document.getElementById('descTipo');
        var div= window.opener.document.getElementById("divDescTipo");
        if (tipoLanc == "R"){
            labe1.innerHTML  = "RECEBIMENTO";
            div.className = "alert alert-success";}
        else {
            labe1.innerHTML  = "PAGAMENTO";
            div.className = "alert alert-danger";}
        f.tipolancamento.value = tipoLanc;
    }

        
    window.close();
}


function submitRemessaConfere() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria_confere';
    f.submenu.value = 'mostra';

    if ((f.fileArq.value != '') && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.fileArq.value;
        f.submit();
        }    
    else {
        swal.fire({
            icon: 'warning',
            title: 'Selecione as opções desejada.',
            timer: 1000,
            showConfirmButton: false
        });
    }    
   
}


function submitRetornoConfere() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario_confere';
    f.submenu.value = 'mostra';

    if ((f.fileArq.value != '') && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.fileArq.value;
        f.submit();
        }    
    else {
        swal.fire({
            icon: 'warning',
            title: 'Selecione as opções desejada.',
            timer: 1000,
            showConfirmButton: false
        });
    }    
   
}

function submitLetraRetorno() {

    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario';
    f.submenu.value = 'mostra';

    // if ((f.fileArq.value != '') && (f.contaBanco.value != '') && (f.filial.value != '')) {
    if ((f.fileArq.value != '')  && (f.filial.value != '')) {
        f.letra.value = f.filial.value + "|" + f.contaBanco.value;
        f.submit();
        }    
    else {
        swal.fire({
            icon: 'warning',
            title: 'Selecione as opções desejada.',
            timer: 1000,
            showConfirmButton: false
        });
    }    
   
}

function submitConfirmaRetorno() {
    f = document.retorno;
    f.mod.value = 'fin';
    f.form.value = 'retorno_bancario';
    swal.fire({
        title: 'Deseja realmente processar os registros de Retorno Bancário?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = 'retorno';
            f.submit();
        } else {
            f.submenu.value = 'mostra';
        }
    });
}


function submitLetraRemessa() {

    f = document.remessa;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria';
    f.submenu.value = 'mostra';
    if ((f.contaBanco.value != '') && (f.filial.value != '')) {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value + "|" + f.contaBanco.value;
        f.submit();
        }    
    else {
        swal.fire({
            icon: 'warning',
            title: 'Selecione as opções desejada.',
            timer: 1000,
            showConfirmButton: false
        });
    }    
   
}

function submitConfirmaRemessa() {
    f = document.remessa;
    f.mod.value = 'fin';
    f.form.value = 'remessa_bancaria';
    swal.fire({
        title: 'Deseja realmente gerar arquivo de remessa'+f.form.value,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f.submenu.value = 'gerar'+f.banco.value; 
        if ((f.contaBanco.value != '') && (f.filial.value != '')) {
            f.letra.value = f.dataConsulta.value + "|" + f.filial.value + "|" + f.contaBanco.value;
            f.submit();
            }    
        else {
            swal.fire({
                icon: 'warning',
                title: 'Selecione as opções desejada.',
                timer: 1000,
                showConfirmButton: false
            });
        }    
    }    
    else{
        f.submenu.value = 'mostra'; }
    });

} // fim submitConfirmarRemessa



function abrir(pag)
{

    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}