//functions that loads afters finishing loading the page 
document.addEventListener('DOMContentLoaded', function () {
    
    //logica para limpar o campo data entrega e esconder o btn confirmar
    const limparData = document.getElementById('limparData');

    limparData.addEventListener('click', function() {
        dataConsulta.value = null;

        const confirmar = document.getElementById('confirmar');
        confirmar.classList.add('hidden');
    });

    $('input[name="dataConsulta"]').on('apply.daterangepicker', function(ev, picker) {
        debugger
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
        const confirmar = document.getElementById('confirmar');
    
        $.ajax({
            url: document.URL + "mod=crm&form=data_entrega_bloqueio&submenu=consulta_data&opcao=blank&query_date="+picker.startDate.format('DD/MM/YYYY'),
            type: 'GET',
            dataType: 'json',
            success: function (e) {
                debugger
                if(e){
                    confirmar.textContent = 'Excluir';
                    confirmar.removeAttribute('class', 'input-group-addon hidden');
                    confirmar.setAttribute('class', 'btn btn-danger input-group-addon');
                    confirmar.setAttribute('onclick', 'dateDeliveryBlockDelete(dataConsulta.value)');
                    
                }else{
                  debugger
                    confirmar.textContent = 'Confirmar';
                    confirmar.removeAttribute('class', 'input-group-addon');
                    confirmar.setAttribute('class', 'btn-success input-group-addon');
                    confirmar.setAttribute('onclick', 'dateDeliveryBlockInsert(dataConsulta.value)');
                }
            },
            error: function (e) {
                debugger
                alert('Erro: ' + e.responseText);
            }
        });
    
    });

});

//function for add due date block
function dateDeliveryBlockInsert(date){

    swal({
        title: "Atenção!",
        text: "Deseja bloquear as entregas no dia " + date + "?",
        icon: "warning",
        buttons: ["Cancelar!",true],
    })
    .then((yes) => {
        if (yes) {
            
            // post to server and update db
            $.ajax({
                url: document.URL + "mod=ped&form=data_entrega_bloqueio&submenu=date_delivery_block&opcao=blank",
                data: {date_delivery_block: date, action: 'insert'},
                type: 'POST',
                dataType: 'text',
                success: function (response) {
                    let objeto = JSON.parse(response);
                    let dataSemAspas = objeto[2].replace(/"/g, ''); // Remove as aspas da strin
                    swal("Sucesso!", `Entregas bloqueadas para o dia `+dataSemAspas+`!`, "success");

                    setTimeout(function () {
                        location.reload();
                    }, 2000)
                    
                },
                error: function (response) {
                    debugger
                    swal('Erro ao processar: ' + response.responseText, "error");
                }
            });

        } else {
            return false
        }
    });
}

function dateDeliveryBlockDelete(date){
    debugger
    swal({
        title: "Atenção!",
        text: "Deseja remover o bloqueio no dia " + date + "?",
        dangerMode: true,
        icon: "warning",
        buttons: ["Cancelar!",true],
    })
    .then((yes) => {
        if (yes) {
            debugger
            
            // post to server and update db
            $.ajax({
                url: document.URL + "mod=ped&form=data_entrega_bloqueio&submenu=date_delivery_block&opcao=blank",
                data: {date_delivery_block: date, action: 'delete'},
                type: 'POST',
                dataType: 'text',
                success: function (response) {
                    debugger
                    let objeto = JSON.parse(response);
                    let dataSemAspas = objeto[2].replace(/"/g, ''); // Remove as aspas da strin
                    swal("Sucesso!", `Bloqueio no dia `+dataSemAspas+` removido!`, "success", { dangerMode: true});

                    setTimeout(function () {
                        location.reload();
                    }, 2000)
                    
                },
                error: function (response) {
                    debugger
                    swal('Erro ao processar: ' + response.responseText, "error");
                }
            });

        } else {
            return false
        }
    });
}

//function for remove confirm button from screen
function verificaData(data){
    const confirmar = document.getElementById('confirmar');
    if (data !== '') {
        confirmar.classList.remove('hidden');
    } else {
        confirmar.classList.add('hidden');
    }
}
