// desenha Cadastro
function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    swal.fire({
        title: "Atenção!",
        text: "Deseja realmente " + f.submenu.value + " este item?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            } else {
                f.submenu.value = 'altera';
            }
            f.submit();
        } else {
            return false;
        }
    });
} // fim submitConfirmar


// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitAlterar(formulario, id) {

    swal.fire({
        title: "Atenção!",
        text: "Deseja realmente Alterar este item",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {   
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = formulario;
        f.submenu.value = 'alterar';
        f.id.value = id;
        f.submit();
    } else {
        return false;
    }
    });
} // submitAlterar

function submitExcluir(formulario, id) {
    swal.fire({
        title: "Atenção!",
        text: "Deseja excluir esta Natureza Operação Tributos ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {   
        if (result.isConfirmed) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = formulario;
        f.submenu.value = 'exclui';
        f.id.value = id;
        f.submit();
    } else {
        return false;
    }
    });
} // submitExcluir

function submitLetra(formulario, letra_pesquisa) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = formulario;
        f.opcao.value = '';
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}

function submitLetraCompras() {
    f = document.lancamento;
    if ((f.codFabricante.value =='') && (f.produtoNome.value=='')&&(f.grupo.value=='')){
        alert('Digite algum Filtro para pesquisa.');
    }else{
        f.submenu.value = 'letra';
        var valueQuant = "T";
        var valueFora = "F";
        f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "||" + valueQuant + "|" + valueFora;
        f.submit();
    }
        
    
}

function submitLetraMovimentoEstoque() {
     
    f = document.lancamento;
    if ((f.codFabricante.value =='') && (f.produtoNome.value=='')&&(f.grupo.value=='')&&(f.dataIni.value=='')&&(f.dataFim.value=='')){
        alert('Digite algum Filtro para pesquisa.');
    }else{
        f.submenu.value = 'letra';
        var valueQuant = "T";
        var valueFora = "F";
        f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "||" + valueQuant + "|" + valueFora + "|" + f.dataIni.value + "|" + f.dataFim.value;
        f.submit();
    }       
    
}

function montaLetra(){
    f = document.lancamento;
    var valueQuant = "T";
    var valueFora = "F";
    f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "||" + valueQuant + "|" + valueFora + "|" + f.dataIni.value + "|" + f.dataFim.value;
}

function relatorioProduto() {
     
    f = document.lancamento;
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value;
    /*
    if((f.codFabricante.value =='') && (f.produtoNome.value=='')&&(f.grupo.value=='')){
        alert('Digite algum Filtro para pesquisa.');
        return false;
    } */
    
   //  montaLetra();
  //  f.mod.value = 'est';
    f.submenu.value = 'relatorio';
    // f.form.value = 'pedido_venda_imp_romaneio';
    //f.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=relatorios&opcao=imprimir&submenu='+f.submenu.value+'&letra=' + f.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}



function submitTributos(formulario, idNatop) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = formulario;
        f.opcao.value = 'nat';
        f.submenu.value = '';
        f.idNatop.value = idNatop;
        f.submit();
}


function fechaNatOperacao(codFiscal, natOperacao, tipo) {
    f = window.opener.document.lancamento;
    
    f.codFiscal.value = codFiscal;
    f.natOperacao.value = natOperacao;
    f.tipo.value = tipo;
    window.close();
}

function submitLetraiqvia() {

    f = document.remessa;
    f.mod.value = 'est';
    f.form.value = 'remessa_iqvia';
    f.submenu.value = 'mostra';

    if (f.filial.value != '') {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }    
   
}

function submitLetraBlocoK() {

    f = document.remessa;
    f.mod.value = 'est';
    f.form.value = 'remessa_bloco_k';
    f.submenu.value = 'mostra';

    if (f.filial.value != '') {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value;
        f.submit();
        }
    else {
        alert('Selecione as opções desejada.');
    }
}

function submitLetraIqviaEMAIL() {
    f = document.remessa;
    f.mod.value = 'est';
    f.form.value = 'remessa_iqvia';
    f.submenu.value = 'email';
    
    e_mail = document.getElementById('emailEndereco').value;
    e_mailTitulo = document.getElementById('emailTitulo').value;
    e_mailCorpo = document.getElementById('emailCorpo').value;

    f.email.value = e_mail + "|" + e_mailTitulo + "|" + e_mailCorpo;
     
    if (f.filial.value != '') {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada!!!.');
    } 

}
function submitLetraIqvia() {
    f = document.remessa;
    f.mod.value = 'est';
    f.form.value = 'remessa_iqvia';
    f.submenu.value = 'mostra';

    if (f.filial.value != '') {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value;
        f.submit();
        }    
    else {
        alert('Selecione as opções desejada.');
    }   
}    
function enviarFile() {
     
    f = document.remessa;
    emailContador = document.getElementById('emailContador').value;
    emailTitulo = document.getElementById('emailTitulo').value;
    emailCorpo = document.getElementById('emailCorpo').value;

    if (f.filial.value != '') {
        f.letra.value = f.dataConsulta.value + "|" + f.filial.value;
        f.submit();
        }
    else {
        alert('Selecione as opções desejada.');
    }
        
    f.email.value = emailContador + "|" + emailTitulo + "|" + emailCorpo;
       
    f.mod.value = 'est';
    f.form.value = 'remessa_bloco_k';
    f.submenu.value = 'enviarFile';
    f.submit();
}    
