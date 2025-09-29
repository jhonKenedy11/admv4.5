document.addEventListener('DOMContentLoaded', function () {

    //funcao para inicializar os selects
    loadSelectsDateRange();

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
            //console.log('Data selecionada: ' + info.dateStr);
        },
        datesRender: function (info) {
            let data_anterior = info.view.activeStart;
            let data_posterior = info.view.activeEnd;
            const data_ini = new Date(data_anterior.getFullYear(), data_anterior.getMonth(), 1);
            const data_fim = new Date(data_posterior.getFullYear(), data_posterior.getMonth() + 1, 0);
            let f_data_ini = data_ini.toLocaleDateString('en-CA');
            let f_data_fim = data_fim.toLocaleDateString('en-CA');

            
            let equipe = document.getElementById('equipe').value;

            // Verifica se equipe é um array e se contém "todos"
            let equipeParam = null;
            if (Array.isArray(equipe)) {
                if (equipe.includes("todos")) {
                    equipeParam = null; // Não filtra por equipe se "todos" estiver selecionado
                } else {
                    equipeParam = equipe.join(','); // Envia os IDs das equipes selecionadas como string separada por vírgulas
                }
            } else if (equipe !== "todos") {
                equipeParam = equipe;
            }

            var dadosAjax = { 
                'data_ini': f_data_ini, 
                'data_fim': f_data_fim
            };

            // Só adiciona equipe aos parâmetros se não for null
            if (equipeParam !== null) {
                dadosAjax.equipe = equipeParam;
            }

            //Ajax de busca de registro
            $.ajax({
                url: document.URL + "mod=cat&form=os_calendario_equipe&submenu=searchOrderService&opcao=blank",
                type: 'POST',
                dataType: 'json',
                data: dadosAjax,
                success: function (response) {
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
                            text: "Não foi localizado registros para esse período ou equipe!",
                            icon: "warning"
                        });
                    } else {
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Erro ao buscar os agendamentos: " + xhr.responseText,
                        timer: 2000
                    });
                }
            });
            //fim ajax busca registro
        },
        customButtons: {
            myCustomButton: {
                text: 'Atualizar calendário',
                click: function () {
                    let currentDate = calendar.getDate(); // Obter a data atual do calendário
                    let prevMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1); // Obter a data do mês anterior
                    let nextMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 2, 0); // Obter a data do último dia do próximo mês
                    let FprimeiroDia = prevMonthDate.toLocaleDateString('en-CA');
                    let FultimoDia = nextMonthDate.toLocaleDateString('en-CA');
                    let equipe = document.getElementById('equipe').value;

                    // Verifica se equipe é um array e se contém "todos"
                    let equipeParam = null;
                    if (Array.isArray(equipe)) {
                        if (equipe.includes("todos")) {
                            equipeParam = null; // Não filtra por equipe se "todos" estiver selecionado
                        } else {
                            equipeParam = equipe.join(','); // Envia os IDs das equipes selecionadas como string separada por vírgulas
                        }
                    } else if (equipe !== "todos") {
                        equipeParam = equipe;
                    }

                    var dadosAjax = {
                        'data_ini': FprimeiroDia,
                        'data_fim': FultimoDia
                    };

                    // Só adiciona equipe aos parâmetros se não for null
                    if (equipeParam !== null) {
                        dadosAjax.equipe = equipeParam;
                    }

                    $.ajax({
                        url: document.URL + "mod=cat&form=os_calendario_equipe&submenu=searchOrderService&opcao=blank",
                        type: 'POST',
                        dataType: 'json',
                        data: dadosAjax,
                        success: function (response) {
                            if (response.length == 0) {
                                calendar.removeAllEvents();
                                swal.fire({
                                    title: "Atenção!",
                                    text: "Não foi localizado registros para esse período ou equipe!",
                                    icon: "warning"
                                });
                            } else {
                                calendar.removeAllEvents();
                                calendar.addEventSource(response);
                            }
                        },
                        error: function () {
                            alert('Não foi possível atualizar o calendário.');
                        }
                    });
                }
            }
        },
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },
        eventClick: function (info) {
            
            info.jsEvent.preventDefault(); // don't let the browser navigate

            //set dados modal
            $("#modalOrdemServico #equipe").val(info.event.extendedProps.details.equipe_id).change();
            
            if (info.event.extendedProps.details.cliente_descricao) {
                $('#modalOrdemServico #cliente_descricao').val(info.event.extendedProps.details.cliente_descricao);
            }
            
            if (info.event.extendedProps.details.situacao_descricao) {
                $('#modalOrdemServico #situacao_descricao').val(info.event.extendedProps.details.situacao_descricao);
            }
            
            // Set users if available
            $("#usuario_equipe").val(null).trigger('change');
            if (info.event.extendedProps.details.usuario_equipe) {
                const userIds = info.event.extendedProps.details.usuario_equipe.split(',');
                $("#usuario_equipe").val(userIds).trigger('change');
            }

            //dates
            if (info.event.start) {
                const dateFormatStart = moment(info.event.start).format('DD/MM/YYYY HH:mm');
                $('#modalOrdemServico #data_inicio').val(dateFormatStart);
            }

            if (info.event.end) {
                const dateFormatEnd = moment(info.event.end).format('DD/MM/YYYY HH:mm');
                $('#modalOrdemServico #data_finalizacao').val(dateFormatEnd);
            }

            //default frame
            if (info.event.id) {
                $('#modalOrdemServico #id').val(info.event.id);
            }
            
            if (info.event.title) {
                $('#modalOrdemServico #desc_servico').val(info.event.title);
            }
            
            $('#modalOrdemServico').modal('show');

            $('#modalOrdemServico #equipe').trigger('change');
        },
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
        }
    });

    calendar.render();
});

function loadSelectsDateRange() {
    // Initialize select2 for main screen team filter
    $("#equipe.select2_multiple").select2({
        allowClear: true,
        width: "90%",
        language: {
            noResults: function() {
                return "Nenhum resultado encontrado";
            }
        }
    });

    // Add event handler for main screen team filter
    $("#equipe.js-example-basic-multiple").on("select2:select select2:unselect", function(e) {
        var values = $(this).val() || [];
        
        if (e.params.data.id === "todos") {
            // If "Todos" was selected, unselect other options
            if (values.includes("todos")) {
                $(this).val(["todos"]).trigger('change');
            }
        } else {
            // If another option was selected, remove "Todos"
            if (values.includes("todos")) {
                values = values.filter(v => v !== "todos");
                $(this).val(values).trigger('change');
            }
        }
    });

    // Initialize select2 for modal team users
    $("#usuario_equipe").select2({
        width: "100%",
        language: {
            noResults: function() {
                return "Nenhum resultado encontrado";
            }
        }
    });

    // Initialize daterangepicker for start date
    $('#modalOrdemServico #data_inicio').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "showDropdowns": true,
        "timePicker24Hour": true,
        "autoUpdateInput": false,
        "autoApply": true,
        "locale": {
            "format": 'DD/MM/YYYY HH:mm',
            "daysOfWeek": ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            "monthNames": [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
        }
    })
    .on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
    })
    .on('hide.daterangepicker', function(ev, picker) {
        if (!$(this).val()) {
            $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
        }
    });

    // Initialize daterangepicker for end date
    $('#modalOrdemServico #data_finalizacao').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        "showDropdowns": true,
        "timePicker24Hour": true,
        "autoUpdateInput": false,
        "autoApply": true,
        "locale": {
            "format": 'DD/MM/YYYY HH:mm',
            "daysOfWeek": ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            "monthNames": [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ],
        }
    })
    .on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
    })
    .on('hide.daterangepicker', function(ev, picker) {
        if (!$(this).val()) {
            $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
        }
    });

    // Select "Todos" by default in main screen team filter
    $("#equipe.select2_multiple").val(['todos']).trigger('change');
}

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
    const usuarioEquipe = $('#modalOrdemServico #usuario_equipe').val() || [];

    const dados = {
        data_inicio: $('#modalOrdemServico #data_inicio').val(),
        data_finalizacao: $('#modalOrdemServico #data_finalizacao').val(),
        desc_servico: $('#modalOrdemServico #desc_servico').val(),
        equipe: $('#modalOrdemServico #equipe').val(),
        usuario_equipe: usuarioEquipe,
        id: $('#modalOrdemServico #id').val()
    };

    const jsonData = JSON.stringify(dados);

    // post to server and update db
    $.ajax({
        url: document.URL + "mod=cat&form=os_calendario_equipe&opcao=blank&submenu=updateOrderService",
        data: {json : jsonData},
        type: 'POST',
        dataType: 'json',
        success: [responseAjax],
        error: function (e) {
            alert('Erro ao processar: ' + e.responseText);
        }
    });
}

function responseAjax(response) {
    if (response.status = "success") {
        // swal.fire({
        //     title: "Sucesso",
        //     text: "Dados do evento atual Alterados!",
        //     icon: "success"
        // });

        setTimeout(function () {
            location.reload();
        }, 1300)
    } else {
        swal.fire({
            title: "Atenção!",
            text: response.message,
            icon: response.status
        });
    }
}

