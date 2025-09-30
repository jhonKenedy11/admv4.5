$(document).ready(function() {
    // Inicialização do Select2 para cliente
    $('#cliente').select2({
        placeholder: "Buscar",
        language: {
            //Descricao da quantidade de caracteres.
            inputTooShort: function() {
                return "Digite no mínimo 3 caracteres";
            }
        },
        minimumInputLength: 3,
        delay: 250,
        ajax: {
            dataType: "json",
            type: "POST",
            url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=pesquisaClienteAjax&opcao=ajax',
            processResults: function(response) {
                return {
                    results: response
                };
            }
        }
    });


});



function searchDocuments() {
	debugger
	try {
		let date_temp = $('input[name="data_consulta"]').val();

		// Separete the dates
		let date_initial = null;
		let date_end = null;
		
		if (date_temp && date_temp.includes(' - ')) {
			let dates = date_temp.split(' - ');
			date_initial = dates[0].trim();
			date_end = dates[1].trim();
		}

		var client_id = $('#cliente').val();
		var document_id = $('input[name="document_id"]').val();

		// Convert the object to JSON
		var request_data = JSON.stringify({
			date_initial: date_initial,
			date_end: date_end,
			client_id: client_id || null, 
			document_id: document_id || null
		});

		var btnPesquisar = $('#btnPesquisar');
		
		$.ajax({
			url: window.location.pathname + '?mod=est&form=faturamento_nfs&submenu=searchDocuments&opcao=ajax',
			type: 'POST',
			dataType: 'json',
			data: {data: request_data},
					beforeSend: function() {
				if (btnPesquisar.length) {
					btnPesquisar
						.prop('disabled', true)
						.html('<i class="fa fa-spinner fa-spin"></i> Pesquisando...');
				}
			},
			success: function(resp) {
				if (resp && resp.success) {

					preencherTabela(resp.data || []);
                    
					atualizarInfoPaginacao(resp.total || 0);
					
				} else {

					preencherTabela([]);


					console.log(resp);
				}
			},
			error: function($xhr, status, error) {

				preencherTabela([]);
				console.log($xhr, status, error);

			},
			complete: function() {
				if (btnPesquisar.length) {
					btnPesquisar
						.prop('disabled', false)
						.html('<i class="fa fa-search"></i> Pesquisar');
				}
			}
		});
	} catch (error) {
		console.error('Erro ao pesquisar documentos:', error);
		preencherTabela([]);
	}
}

// Função para limpar filtros
function limparPesquisa() {
	try {
		// Define o mês atual como padrão ao limpar
		var inicioMes = moment().startOf('month').format('DD/MM/YYYY');
		var fimMes = moment().endOf('month').format('DD/MM/YYYY');
		$('input[name="data_consulta"]').val(inicioMes + ' - ' + fimMes);
		
		$('#cliente').val(null).trigger('change');
		$('input[name="document_id"]').val('');
		
		var tbody = $('#corpoTabela');
		if (tbody.length) {
			tbody.html('<tr><td colspan="8" class="text-center text-muted"><i class="fa fa-info-circle"></i> Utilize os filtros acima para realizar uma pesquisa</td></tr>');
		}
		
		atualizarInfoPaginacao(0);
		
	} catch (error) {

		console.error('Erro ao limpar pesquisa:', error);
	}
}

// Função para preencher a tabela com resultados
function preencherTabela(dados) {
    debugger

    var tbody = $('#corpoTabela');

    if (!tbody.length) {
        console.error('Elemento #corpoTabela não encontrado');
        return;
    }
    
    tbody.empty();
    
    if (!dados || dados.length === 0) {
        tbody.append('<tr><td colspan="8" class="text-center text-muted">Nenhum resultado encontrado</td></tr>');
        return;
    }
    
    dados.forEach(function(item) {
        if (!item) return;
        
        var tipoClass = (item.TIPO_DOCUMENTO === 'OS') ? 'status-os' : 'status-pedido';
        var id = item.ID || 0;
        var tipoDocumento = item.TIPO_DOCUMENTO || '';

        var row = '<tr>' +
            '<td class="text-center">' + (item.DATA_EMISSAO_FORMATADA || 'N/A') + '</td>' +
            '<td class="text-center">' + (item.NUMERO_DOCUMENTO || 'N/A') + '</td>' +
            '<td class="text-center"><span class="status-badge ' + tipoClass + '">' + (item.TIPO_DOCUMENTO || 'N/A') + '</span></td>' +
            '<td>' + (item.NOME_CLIENTE || 'N/A') + '</td>' +
            '<td class="text-right">' + (item.VALOR_TOTAL || 'R$ 0,00') + '</td>' +
            '<td class="text-center">' + (item.SITUACAO_DESC || 'N/A') + '</td>' +
            '<td class="text-center">' +
                //'<button class="btn btn-xs btn-primary" title="Visualizar"><i class="fa fa-eye"></i></button> ' +
                //'<button class="btn btn-xs btn-success" title="Editar"><i class="fa fa-edit"></i></button> ' +
                '<button class="btn btn-xs btn-info" title="Ver Serviços" onclick="abrirModalServicos(' + id + ',' + item.CLIENTE_ID + ', \'' + tipoDocumento + '\', event)"><i class="fa fa-list"></i></button> ' +
                //'<button class="btn btn-xs btn-warning" title="Gerar NFS"><i class="fa fa-file-text"></i></button>' +
            '</td>' +
            '</tr>';

        tbody.append(row);
    });
}

function atualizarInfoPaginacao(total) {

    var info = $('#infoPagina');

    if (!info.length) {
        console.warn('Elemento #infoPagina não encontrado');
        return;
    }
    
    total = parseInt(total) || 0;
    if (total > 0) {
        info.text('Mostrando 1 a ' + total + ' de ' + total + ' registros');
    } else {
        info.text('Mostrando 0 a 0 de 0 registros');
    }
}




