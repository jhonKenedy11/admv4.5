function submitUpdateGeneral(formulario) {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = 'updateGeneral';
    f.submit();
}

function fieldSelected (field){
    if (f.letra.value != '') {
        f.letra.value = f.letra.value + "|" ;
    }
    for (var i = 0; i < f.field.options.length; i++) {
        if (f.field[i].selected == true) {
            f.letra.value = f.field[i].value;
        }
    }
}

function submitAtulizarInformacoes(formulario, id) {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = 'atualizarInformacoes';
    f.letra.value = '';

    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_uf_id.options.length; i++) {
        if (f.filtro_uf_id[i].selected == true) {
            f.letra.value = f.letra.value + f.filtro_uf_id[i].value;
        }
    }

    f.letra.value = f.letra.value + "|" ;        
    for (var i = 0; i < f.filtro_pessoa_id.options.length; i++) {
        if (f.filtro_pessoa_id[i].selected == true) {
            f.letra.value = f.letra.value +f.filtro_pessoa_id[i].value;
        }
    }

    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_origem_id.options.length; i++) {
        if (f.filtro_origem_id[i].selected == true) {
            f.letra.value = f.letra.value +f.filtro_origem_id[i].value;
        }
    }

    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_tribIcms_id.options.length; i++) {
        if (f.filtro_tribIcms_id[i].selected == true) {
            f.letra.value = f.letra.value + f.filtro_tribIcms_id[i].value;
        }
    }
    
    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_ncm_id.options.length; i++) {
        if (f.filtro_ncm_id[i].selected == true) {
            f.letra.value = f.letra.value + f.filtro_ncm_id[i].value;
        }
    }

    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_anp_id.options.length; i++) {
        if (f.filtro_anp_id[i].selected == true) {
            f.letra.value = f.letra.value + f.filtro_anp_id[i].value;
        }
    }
    
    f.letra.value = f.letra.value + "|" ;
    for (var i = 0; i < f.filtro_tipoNatOp_id.options.length; i++) {
        if (f.filtro_tipoNatOp_id[i].selected == true) {
            f.letra.value = f.letra.value + f.filtro_tipoNatOp_id[i].value;
        }
    }

    f.letra.value = f.letra.value + "|" ;   

    f.id.value = id;
    f.submit();
    
} // submitCopiarNatureza


function submitCopiarNatureza(formulario, id) {
    swal.fire({
        title: "Atenção!",
        text: "Deseja realmente Copiar esta configuração?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'est';
            f.form.value = formulario;
            f.submenu.value = 'copiar';
            f.id.value = id;
            f.submit();
        } else {
            return false;
        }
    });
} // submitCopiarNatureza

function submitManutencao(formulario) {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.opcao.value = '';
    f.id.value = "";
    f.submit();
} // submitCadastro

function submitCopiar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = 'automatico'; 
    f.submit();
} // fim submitCopiar

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
    if(f.uf.value == 0){
        swal.fire({
            title: "Atenção!",
            text: "Preencha o campo Estado",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return false;
    }
    if(f.pessoa.value == ''){
        swal.fire({
            title: "Atenção!",
            text: "Preencha o campo Tipo Pessoa",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return false;
    }
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
    f.opcao.value = '';
    f.id.value = "";
//    f.codFisc.value = codFisc;
    f.submit();
} // submitCadastro


function submitAlterar(formulario, id, idNatop) {
    swal.fire({
        title: "Atenção!",
        text: "Deseja realmente Alterar este item?",
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
                f.form.value = formulario;
                f.mod.value = 'est';
                f.submenu.value = 'exclui';
                f.id.value = id;
                f.submit();
            } else {
                return false;
            }
        });
} 

function submitLetra(formulario, letra_pesquisa) {
        f = document.lancamento;
        f.mod.value = 'est';
        f.form.value = formulario;
        f.opcao.value = '';
        f.submenu.value = 'letra';
        f.letra.value = letra_pesquisa;
        f.submit();
}