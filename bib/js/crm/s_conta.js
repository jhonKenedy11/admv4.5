function abrirModalAnexo(idObra) {
    $('#id_obra_anexo').val(idObra);

    // Carregar anexos existentes via AJAX
    $.ajax({
        url: 'index.php?form=contas&mod=crm&submenu=carregarAnexosObra&idObra=' + idObra,
        type: 'GET',
        success: function(response) {
            $('#anexosExistentes').html(response);
        },
        error: function(error) {
            $('#anexosExistentes').html('<p>Erro ao carregar anexos.</p>');
        }
    });

    $('#ModalAnexo').modal('show');
}