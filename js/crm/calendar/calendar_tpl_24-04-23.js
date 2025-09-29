document.addEventListener('DOMContentLoaded', function () {
    debugger
    let calendarEl = document.getElementById('calendar');
    //METODO DESATIVADO POIS REALIZAVA VARIAS REQUISICOES SENDO QUANDO O OBJETO E CRIADO
    //UTILIZA O datesRender PARA POPULAR O CALENDARIO
    //let periodo = paramData();
    //let new_url = document.URL.replace('form=calendar', 'form=calendar_list_events.php&primeiroDia=' + periodo[0] + '&ultimoDia=' + periodo[1] +'&opcao=blank');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        plugins: ['dayGrid'],
        hiddenDays: [0],
        editable: true,
        eventLimit: true,
        //events: new_url,
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
        },
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },
        eventClick: function (info) {
            debugger
            info.jsEvent.preventDefault(); // don't let the browser navigate
            
            //set dados modal
            $("#atividade").val(info.event._def.extendedProps.atividade_id).change();
            $('#editRegistro #id_reg').val(info.event._def.extendedProps.id_reg);
            $('#editRegistro #cliente_nome').val(info.event._def.extendedProps.cliente_nome);
            $('#editRegistro #cliente_telefone').val(info.event._def.extendedProps.cliente_telefone);
            $('#editRegistro #cliente_celular').val(info.event._def.extendedProps.cliente_celular);
            $('#editRegistro #cliente_email').val(info.event._def.extendedProps.cliente_email);
            //dates
            $('#editRegistro #proximoContato').val(info.event._def.extendedProps.data_ligar_dia);
            $('#editRegistro #proximoContatoHora').val(info.event._def.extendedProps.hora_ligar_dia);

            //var hidden para condicao de div
            $('#editRegistro #evento_realizado').val(info.event._def.extendedProps.evento_realizado);

            //teste de data e hora para setar readonly
            if ((info.event._def.extendedProps.evento_realizado !== null) & (info.event._def.extendedProps.evento_realizado !== '')){
                $('#editRegistro #evento_realizado').val(info.event._def.extendedProps.evento_realizado)
                                                    .prop('readonly', true)//set readonly
                                                    .css("pointer-events", "none");//remove event click
            }else{
                $('#editRegistro #evento_realizado').val(info.event._def.extendedProps.evento_realizado)
                                                    .prop('readonly', false)//remove readonly
                                                    .css("pointer-events", "all");//add event click
            }
            //hora
            if ((info.event._def.extendedProps.evento_realizado_hora !== null) & (info.event._def.extendedProps.evento_realizado_hora !== '')) {
                $('#editRegistro #evento_realizado_hora').val(info.event._def.extendedProps.evento_realizado_hora)
                                                         .prop('readonly', true)//set readonly 
                                                         .css("pointer-events", "none"); //remove event click
            } else {
                $('#editRegistro #evento_realizado_hora').val(info.event._def.extendedProps.evento_realizado_hora)
                                                         .prop('readonly', false)//remove readonly
                                                         .css("pointer-events", "all");//add event click
            }
            
            //default frame
            $('#editRegistro #idPedido').val(info.event.id);
            $('#editRegistro #descReg').val(info.event.title.substr(8));
            $('#editRegistro #dataReg').val(info.event.start.toLocaleString());
            $('#editRegistro').modal('show');
            
        },
        datesRender: function(info) {
            debugger
            
            let dataAnt = info.view.activeStart;
            let dataPost = info.view.activeEnd;
            const primeiroDia = new Date(dataAnt.getFullYear(), dataAnt.getMonth(), 1);
            const ultimoDia = new Date(dataPost.getFullYear(), dataPost.getMonth() + 1, 0);
            var FprimeiroDia = primeiroDia.toLocaleDateString('en-CA');
            var FultimoDia = ultimoDia.toLocaleDateString('en-CA');
            
            $.ajax({
                url: document.URL + "mod=crm&form=calendar_list_events&opcao=blank&primeiroDia=" + FprimeiroDia + "&ultimoDia=" + FultimoDia,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    debugger
                    if(response.length == 0){
                        swal("Atenção!", "Não foi localizado registros, verifique o usuário!", "warning");
                    }else{
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                    }
                },
                error: function (e) {
                    alert('Erro ao processar: ' + e.responseText);
                }
            });
            
        }
    });

    calendar.render();
});

function paramData(){
    const dataAtual = new Date();
    const primeiroDia = new Date(dataAtual.getFullYear(), dataAtual.getMonth(), 1);
    const ultimoDia = new Date(dataAtual.getFullYear(), dataAtual.getMonth() + 1, 0);
    let FprimeiroDia = primeiroDia.toLocaleDateString('en-CA');
    let FultimoDia = ultimoDia.toLocaleDateString('en-CA');
    return [FprimeiroDia, FultimoDia];
}

//Busca os tipos de atividades
document.addEventListener('DOMContentLoaded', function () {
    $.ajax({
        url: document.URL + "mod=crm&form=calendar&submenu=query_combo_atividade&opcao=blank",
        data: `param=teste&submenu=queryCombo`,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            for(var i= 0; i < response.length; i++){
                //popula o campo acao
                $('<option>').val(response[i].ID).text(response[i].DESCRICAO).appendTo('#atividade');
            }
        },
        error: function (e) {
            alert('Erro ao processar: ' + e.responseText);
        }
    });

});

//Funcao para atualizar o registro
function submitUpdateReg() {
    //let data_contato_anterior = $('#data_contato_anterior').val();
    var proximo_contato = $('#proximoContato').val();

    if ((proximo_contato !== '') & (proximo_contato !== null) & (proximo_contato !== NaN)){
        swal({
            title: "Atenção?",
            text: "Deseja cadastrar novo evento na data " + proximo_contato + "!",
            icon: "warning",
            buttons: ["Apenas atualizar evento!",true],
        })
            .then((willDelete) => {
                if (willDelete) {
                    $('#registerNewEvent').val('S')
                    ajaxAtualizaEvento();
                } else {
                    $('#registerNewEvent').val('N')
                    ajaxAtualizaEvento();
                }
            });   
    }else{
        $('#registerNewEvent').val('N')
        ajaxAtualizaEvento();
    }
}

function ajaxAtualizaEvento(){
    var form = $("form[name=form_edit]");
    // post to server and update db
    $.ajax({
        url: document.URL + "mod=crm&form=calendar&submenu=atualiza_acomp",
        data: $(form).serialize(),
        type: 'POST',
        dataType: 'text',
        success: function (response) {
            if (response.indexOf("atualizado") !== -1) {
                swal("Dados do evento atual Alterados!", "", "success");

                setTimeout(function () {
                    location.reload();
                }, 1500)
                //$('#editRegistro').modal('hide');
            } else {
                swal("Atenção!", "Dados NÃO alterados!", "warning");
            }
        },
        error: function (e) {
            alert('Erro ao processar: ' + e.responseText);
        }
    });
}


function trataData(data){
    var string_split = data.split("-");

    var ano = string_split[0];
    var mes = string_split[1];
    var dia = string_split[2];

    var new_date = dia + "/" + mes + "/" + ano;
    return new_date;
}





