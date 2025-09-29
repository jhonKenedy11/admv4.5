document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        plugins: ['dayGrid'],
        hiddenDays: [0],
        editable: true,
        eventLimit: true,
        header: {
            left: 'myCustomButton',
            center: 'title',
            right: 'today prev,next'
        },
        dateClick: function (info) {
            console.log('Data selecionada: ' + info.dateStr);
            // Executar ação desejada aqui
        },
        datesRender: function (info) {
            let data_anterior = info.view.activeStart;
            let data_posterior = info.view.activeEnd;
            const data_ini = new Date(data_anterior.getFullYear(), data_anterior.getMonth(), 1);
            const data_fim = new Date(data_posterior.getFullYear(), data_posterior.getMonth() + 1, 0);
            let f_data_ini = data_ini.toLocaleDateString('en-CA');
            let f_data_fim = data_fim.toLocaleDateString('en-CA');
            let vendedor = document.getElementById('vendedor').value;

            var dadosAjax = { 
                'data_ini': f_data_ini, 
                'data_fim': f_data_fim, 
                'vendedor': vendedor 
            };

            //Ajax de busca de registro
            $.ajax({
                url: document.URL + "mod=cat&form=os_calendario&submenu=searchOrderService&opcao=blank",
                type: 'POST',
                dataType: 'json',
                data: dadosAjax,
                success: function (response) {
                    debugger

                    // se existir o erro no backend ja apresenta na tela
                    if(response.error){
                        swal.fire({
                            title: "Atenção!",
                            text: response.error,
                            icon: "error"
                        });
                        return
                    }

                    if (response.length == 0) {

                        swal.fire({
                            title: "Atenção!",
                            text: "Não foi localizado registros para esse período ou usuário!",
                            icon: "warning"
                        });

                    } else {

                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                        
                    }
                },
                error: function (xhr) {
                    alert('Erro ao processar: ' + xhr.responseText);
                }
            });
            //fim ajax busca registro
        },
        customButtons: {
            myCustomButton: {
                text: 'Atualizar calendário',
                click: function () {
                    debugger
                    let currentDate = calendar.getDate(); // Obter a data atual do calendário
                    let prevMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1); // Obter a data do mês anterior
                    let nextMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 2, 0); // Obter a data do último dia do próximo mês
                    let FprimeiroDia = prevMonthDate.toLocaleDateString('en-CA');
                    let FultimoDia = nextMonthDate.toLocaleDateString('en-CA');
                    //let vendedor = document.getElementById('vendedor').value;

                    var dadosAjax = {
                        'data_ini': FprimeiroDia,
                        'data_fim': FultimoDia,
                        //'vendedor': vendedor
                    };

                    $.ajax({
                        url: document.URL + "mod=cat&form=os_calendario&submenu=searchOrderService&opcao=blank",
                        type: 'POST',
                        dataType: 'json',
                        data: dadosAjax,
                        success: function (response) {
                            if (response.length == 0) {
                                calendar.removeAllEvents();
                                swal.fire({
                                    title: "Atenção!",
                                    text: "Não foi localizado registros para esse período ou usuário!",
                                    icon: "warning"
                                });
                            } else {
                                calendar.removeAllEvents();
                                calendar.addEventSource(response);
                            }
                        },
                        error: function () {
                            alert('Não foi possível atualizar o calendário.'); // exibe uma mensagem de erro se ocorrer um erro na solicitação
                        }
                    });
                }
            }
        },
        //events: new_url,
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },
        eventClick: function (info) {

            info.jsEvent.preventDefault(); // don't let the browser navigate

            //set dados modal
            $("#equipe").val(info.event._def.extendedProps.equipe_id).change();
            $('#modalOrdemServico #cliente_descricao').val(info.event._def.extendedProps.cliente_descricao);
            $('#modalOrdemServico #situacao_descricao').val(info.event._def.extendedProps.situacao_descricao);


            //dates
            // Converte para Moment.js antes de formatar
            const dateFormatStart = moment(info.event.start).format('DD/MM/YYYY HH:mm');
            $('#modalOrdemServico #data_inicio').val(dateFormatStart);

            const dateFormatEnd = moment(info.event.end).format('DD/MM/YYYY HH:mm');
            $('#modalOrdemServico #data_finalizacao').val(dateFormatEnd);

            //default frame
            $('#modalOrdemServico #id').val(info.event.id);
            $('#modalOrdemServico #desc_servico').val(info.event.title.substr(8));
            $('#modalOrdemServico').modal('show');

        },
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
        }
    });

    calendar.render();
});



//Funcao para atualizar ordem de servico
function submitUpdateOrderService() 
{
    swal.fire({
        title: "Atenção?",
        confirmButtonText: "Atualizar O.S.",
        showCancelButton: true,
        text: "Deseja atualizar os dados da O.S. ?",
        icon: "warning"
    })
    .then((result) => {
        if (result.isConfirmed) {
            ajaxUpdateOS();
        } else {
            return false;
        }
    });
}

function ajaxUpdateOS() 
{
    debugger
    const dados = {
        data_inicio: $('#data_inicio').val(),
        data_finalizacao: $('#data_finalizacao').val(),
        equipe: $('#equipe').val(),
        id: $('#id').val()
    };

    const jsonData = JSON.stringify(dados);

    // post to server and update db
    $.ajax({
        url: document.URL + "mod=cat&form=os_calendario&opcao=blank&submenu=updateOrderService",
        data: {json : jsonData},
        type: 'POST',
        dataType: 'json',
        success: [responseAjax],
        error: function (e) {
            debugger
            alert('Erro ao processar: ' + e.responseText);
        }
    });
}

function responseAjax(response) {
    debugger

    if (response.status = "success") {
        swal.fire({
            title: "Sucesso",
            text: "Dados do evento atual Alterados!",
            icon: "success"
        });

        setTimeout(function () {
            location.reload();
        }, 1300)
        //$('#editRegistro').modal('hide');
    } else {
        swal.fire({
            title: "Atenção!",
            text: response.message,
            icon: response.status
        });
    }
}



