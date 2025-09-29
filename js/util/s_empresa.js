// Função para submit de cadastro (usada no botão "Cadastrar Nova")
function submitCadastro() {
     
    var f = document.lancamento;
    if (f) {
        f.submenu.value = 'consulta';
        f.submit();
    }
}

function submitSalvar() {
    var f = document.lancamento;
    if (!validarEmpresa()) {
        return false;
    } else {
        if (f.empresa_id.value !== '') {
            f.submenu.value = 'alterar';
        } else {
            f.submenu.value = 'inclui';
        }
        f.submit();
    }
}

function submitPesquisa(){
    var f = document.lancamento;
    f.submenu.value = '';
    f.nome_empresa.value = $filtro_nome;
    f.submit();
}

function submitConsulta($empresa_id) {
    var f = document.lancamento;
    if (f) {
        f.submenu.value = 'consulta';
        f.empresa_id.value = $empresa_id;
        f.submit();
    }
}


// Função para submit de exclusão (caso seja liberado no futuro)
function submitExcluir(amb_empresa) {
    if (confirm('Tem certeza que deseja excluir esta empresa?')) {
        var f = document.lancamento;
        if (f) {
            f.submenu.value = 'excluir';
            f.amb_empresa.value = amb_empresa;
            f.submit();
        }
    }
}

function submitVoltar() {
     
    var f = document.lancamento;
    f.submenu.value = '';
    f.submit();
}

function validarEmpresa() {
    const obrigatorios = [
        { id: 'nome_empresa', label: 'Nome da Empresa' },
        { id: 'nome_fantasia', label: 'Nome Fantasia' },
        { id: 'cnpj', label: 'CNPJ' },
        { id: 'inscricao_estadual', label: 'Inscrição Estadual' },
        { id: 'cep', label: 'CEP' },
        { id: 'rua', label: 'Rua' },
        { id: 'numero', label: 'Número' },
        { id: 'bairro', label: 'Bairro' },
        { id: 'codigo_municipio', label: 'Código do Município' },
        { id: 'cidade', label: 'Cidade' },
        { id: 'estado', label: 'Estado' },
        { id: 'email', label: 'E-mail' },
        { id: 'telefone', label: 'Telefone' },
        { id: 'regime_tributario', label: 'Regime Tributário' },
        { id: 'casas_decimais', label: 'Casas Decimais' }
    ];
    for (let campo of obrigatorios) {
        const el = document.getElementById(campo.id);
        if (!el || !el.value.trim()) {
            swal.fire({
                icon: 'warning',
                title: 'Campo obrigatório',
                text: `Preencha o campo: ${campo.label}`
            });
            if (el) el.focus();
            return false;
        }
    }
    return true;
}

// Busca automática de endereço via CEP (padrão contas)
async function pesquisarEnderecoEmpresa(cep) {
    try {
        const cepSemMascara = cep.replace(/\D/g, '');
        const validacep = /^[0-9]{8}$/;

        if (!validacep.test(cepSemMascara)) {
            throw new Error('Formato de CEP inválido.');
        }

        const response = await fetch(`//viacep.com.br/ws/${cepSemMascara}/json/`);
        const data = await response.json();

        if (data.erro) {
            throw new Error('CEP não encontrado.');
        }

        document.getElementById('rua').value = data.logradouro || '';
        document.getElementById('bairro').value = data.bairro || '';
        document.getElementById('cidade').value = data.localidade || '';
        document.getElementById('estado').value = data.uf || '';
        document.getElementById('codigo_municipio').value = data.ibge || '';
        document.getElementById('numero').focus();
    } catch (error) {
        document.getElementById('rua').value = '';
        document.getElementById('bairro').value = '';
        document.getElementById('cidade').value = '';
        document.getElementById('estado').value = '';
        document.getElementById('codigo_municipio').value = '';
        swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: error.message
        });
    }
} 

// === LOGO EMPRESA - PADRÃO OBRAS ===

function abrirModalLogo(empresa_id) {
    $('#ModalAnexoLogo').modal('show');
    $('#id_empresa_logo').val(empresa_id);
    carregarLogoEmpresa(empresa_id);
}

function carregarLogoEmpresa(empresa_id) {
    $('#logoExistente').html(`
        <div class="col-12 text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2">Carregando logo...</p>
        </div>
    `);
    $.ajax({
        url: 'index.php?form=empresa&mod=util&submenu=carregarLogoEmpresa&opcao=blank&id_empresa=' + empresa_id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.forEach(logo => {
                    if (logo.id && logo.id_empresa && logo.extensao && logo.caminho_completo) {
                        html += `
                            <div class="col-12 text-center" style="position: relative;">
                                ${gerarVisualizacaoLogo(logo)}
                                <div class="btnManutencao mt-2">
                                    <button type="button" class="btn btn-danger btn-xs" onClick="excluirLogoEmpresa(${logo.id})">
                                        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                        <span>Apagar</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="openLogo('${logo.caminho_completo}')">
                                        <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
                                        <span>Abrir</span>
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                });
                $('#logoExistente').html(html);
            } else {
                $('#logoExistente').html(`
                    <div class="col-12 text-center py-4">
                        <p>Nenhuma logo anexada</p>
                    </div>
                `);
            }
        },
        error: function() {
            $('#logoExistente').html(`
                <div class="col-12 text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <p class="text-danger">Erro ao carregar logo.</p>
                </div>
            `);
        }
    });
}

function gerarVisualizacaoLogo(logo) {
    const extensao = logo.extensao.toUpperCase();
    if (extensao === 'PNG') {
        return `<img src="${logo.caminho_completo}" class="img-rounded img-responsive tagImg" style="max-height: 150px; width: auto; margin: 0 auto;"/>`;
    } else {
        return `<span>Arquivo não suportado</span>`;
    }
}

function openLogo(url) {
    window.open(url, '_blank');
}

function salvarLogoEmpresa() {
    const empresaId = $('#id_empresa_logo').val();
    const fileInput = $('#logoEmpresa')[0];
    const file = fileInput.files[0];

    // validação de arquivo selecionado
    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um arquivo PNG antes de enviar.'
        });
        return;
    }
    // validação de extensão
    const fileName = file.name;
    const fileExt = fileName.split('.').pop().toLowerCase();
    if (fileExt !== 'png') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Tipo de arquivo inválido. Apenas PNG é permitido.'
        });
        return;
    }
    // validação de tamanho
    const maxSize = 2000000;
    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'O arquivo é muito grande. Tamanho máximo permitido: 2MB.'
        });
        return;
    }
    const formData = new FormData();
    formData.append('file', file);
    formData.append('id_empresa', empresaId);
    $.ajax({
        url: 'index.php?form=empresa&mod=util&submenu=salvarLogoEmpresa&opcao=blank',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('#btnSalvarLogo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
        },
        success: function(response) {
            $('#logoEmpresa').val('');
            let data;
            try {
                data = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) {
                data = {};
            }
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message || 'Logo anexada com sucesso.',
                    showConfirmButton: true,
                    timer: 4000
                }).then(() => {
                    carregarLogoEmpresa(empresaId);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: data.message || 'Falha ao anexar logo.'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: xhr.responseText || 'Falha ao anexar logo.'
            });
        },
        complete: function() {
            $('#btnSalvarLogo').prop('disabled', false).html('Anexar');
        }
    });
}

function excluirLogoEmpresa(id_logo) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você não poderá reverter isso!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'index.php?form=empresa&mod=util&submenu=excluirLogoEmpresa&id_logo=' + id_logo,
                type: 'GET',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'A logo foi excluída.',
                        'success'
                    );
                    carregarLogoEmpresa($('#id_empresa_logo').val());
                },
                error: function(error) {
                    Swal.fire(
                        'Erro!',
                        'Erro ao excluir a logo.',
                        'error'
                    );
                }
            });
        }
    });
} 