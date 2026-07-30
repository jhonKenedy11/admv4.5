function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = '';
    f.submit();
}

function submitVoltarNatOp() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nat_operacao';
    f.submenu.value = '';
    f.submit();
}

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = formulario;
    
    if(f.uf_dest.value == ''){
        Swal.fire({
            title: "Atenção!",
            text: "Preencha o campo UF Destino",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return false;
    }
    if(f.pessoa.value == ''){
        Swal.fire({
            title: "Atenção!",
            text: "Preencha o campo Tipo Pessoa",
            icon: "warning",
            confirmButtonText: "OK"
        });
        return false;
    }
    
    Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente " + (f.submenu.value == "cadastrar" ? "incluir" : "alterar") + " este item?",
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
}

function submitAlterar(formulario, id) {
    Swal.fire({
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
}

function submitExcluir(formulario, id) {
    Swal.fire({
        title: "Atenção!",
        text: "Deseja excluir este Tributo IBS/CBS?",
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

function submitCopiar(formulario, id) {
    Swal.fire({
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
}

function carregarMunicipios(uf, municipioSelecionado, codigoSelecionado) {
    if (!uf) {
        $("#mun_dest").empty().append('<option value="">Selecione a UF primeiro</option>').trigger('change');
        $("#cod_mun_dest").val('');
        return;
    }
    
    $("#mun_dest").empty().append('<option value="">Carregando...</option>');
    
    $.ajax({
        url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $("#mun_dest").empty().append('<option value="">Selecione o município</option>');
            
            $.each(data, function(index, municipio) {
                var selected = '';
                if (municipioSelecionado && municipio.nome.toUpperCase() == municipioSelecionado.toUpperCase()) {
                    selected = 'selected';
                }
                $("#mun_dest").append('<option value="' + municipio.nome + '" data-codigo="' + municipio.id + '" ' + selected + '>' + municipio.nome + '</option>');
            });
            
            $("#mun_dest").trigger('change');
            
            // Se tiver valor selecionado, atualiza o código
            if (municipioSelecionado && codigoSelecionado) {
                $("#cod_mun_dest").val(codigoSelecionado);
            }
        },
        error: function() {
            $("#mun_dest").empty().append('<option value="">Erro ao carregar municípios</option>');
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Não foi possível carregar os municípios. Verifique sua conexão.',
                confirmButtonText: 'OK'
            });
        }
    });
}

function initCadastroTribIbsCbs() {
    $(".money").maskMoney({            
        decimal: ",",
        thousands: ".",
        allowNegative: false,
        allowZero: true
    });
    
    $("#cclasstrib.js-example-basic-single").select2({});
    $("#ncm.js-example-basic-single").select2({});
    
    $("#mun_dest").select2({
        placeholder: "Selecione a UF primeiro",
        allowClear: true
    });
    
    // Evento de mudança da UF
    $("#uf_dest").on('change', function() {
        var uf = $(this).val();
        carregarMunicipios(uf, null, null);
    });
    
    // Evento de mudança do Município - preenche código IBGE
    $("#mun_dest").on('change', function() {
        var option = $(this).find('option:selected');
        var codigo = option.data('codigo');
        if (codigo) {
            $("#cod_mun_dest").val(codigo);
        } else {
            $("#cod_mun_dest").val('');
        }
    });
    
    // Carregar municípios na inicialização se já tiver UF selecionada
    var ufInicial = $("#uf_dest").val();
    var munInicial = $("#mun_dest_valor").val();
    var codMunInicial = $("#cod_mun_dest").val();
    
    if (ufInicial) {
        carregarMunicipios(ufInicial, munInicial, codMunInicial);
    }
}
