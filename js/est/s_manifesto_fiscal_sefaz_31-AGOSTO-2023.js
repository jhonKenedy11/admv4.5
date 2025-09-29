function toggleInput() {
    debugger
    var inputContainer = document.getElementById("textInputContainer");


    if (inputContainer.style.display === "none") {
        inputContainer.style.display = "block";
    } else if (inputContainer.style.display === '') {
        inputContainer.style.display = "block";
    } else {
        inputContainer.style.display = "none";
    }
}

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal_sefaz';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitEnviaEvento(idNf ,typeEvent, param='') {
    if(typeEvent == 'confirma'){
        textQuestion = 'confirmação da operação';
    }else if($typeEvent == 'desconhecimento'){
        textQuestion = 'desconhecimento da operação';
    }else if(typeEvent == 'naorealizada'){
        textQuestion = 'operação não realizada';
    }

    swal({
        title: "Atenção!",
        text: "Deseja enviar o evento de " + textQuestion + "?",
        icon: "warning",
        buttons: ["Cancelar", "Continuar"],
    })
    .then((yes) => {
        debugger
        if (yes) {
            debugger
            var url = document.URL;
            // Create a FormData object to hold your data
            var formData = new FormData();
            // Add your data parameters to the FormData object
            formData.append('idNf', idNf);
            formData.append('typeEvent', typeEvent);
            formData.append('param', param);

            formData.append('mod', 'est');
            formData.append('form', 'manifesto_fiscal_sefaz');
            formData.append('submenu', 'eventoManifestoNotaFiscal');
            formData.append('opcao', 'blank');
            // ... add more parameters as needed

            // Create the XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.responseType = 'json';

            xhr.onload = function () {
                debugger
                if (xhr.status === 200) {
                    debugger
                    // Process the response data here if needed
                } else {
                    // Handle error if the request fails
                    console.error('Erro ao enviar o evento de ' + textQuestion + ': ', xhr.status);
                }
            }

            // Send the FormData object with the request
            xhr.send(formData);

        }else{ // END if (yes)
            return false;
        }
    });
}

// ####################

function montaLetra() {
    l = document.lancamento;
    l.letra.value = l.dataIni.value + '|' + l.dataFim.value;
}// submitLetra

function submitLetra() {
    debugger;
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal_sefaz';
    f.submenu.value = 'letra';
    montaLetra();
    f.submit();
}// submitLetra

function abrir(pag) {
    window.open(pag, 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
}

function getMoney(el) {
    var money = id(el).value.replace(',', '.');
    return parseFloat(money);
}


function consultarPrint(form) {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'est';
    g.form.value = form;
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function limpaDadosForm() {
   f = document.lancamento;
   f.letra.value = '';
   f.mdf.value = ''
   f.serie.value = '';
   f.id.value = '';
   f.opcao.value = '';
   f.nomecondutor.value = '';
   f.condutor.value = '';
   f.submenu.value = '';
}

function submitConsultaDocumentosSefaz(){
    debugger
    // Cria o HTML personalizado com o ícone animado
    var loadingIconHtml = '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';

    // Mostra a mensagem de carregamento usando SweetAlert2 com o ícone animado
    var loadingMessage = Swal.fire({
        html: loadingIconHtml + '<p><b>Consultando notas fiscais...</b></p>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            debugger

            $.ajax({
                type: "POST",
                url: document.URL + "?mod=est&form=manifesto_fiscal_sefaz&submenu=consultarDocumentosSefaz&opcao=blank",
                dataType: "json",
                success: function (response) {
                    debugger
                    if (response['cStat'] == 'true'){
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Notas baixadas!',
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            swal.close(); // Fecha o alerta de carregamento
                            // Aqui você pode exibir outro alerta ou executar outras ações após o carregamento
                        }, 3000); // Tempo em milissegundos (3 segundos no exemplo)

                    } else if (response['cStat'] == '405'){ //1 hora para consulta

                        Swal.fire({
                            icon: 'error',
                            title: 'Atenção',
                            text: response['message'],
                            showConfirmButton: false,
                            customClass: 'tamanho-personalizado-minutos',
                            // footer: '<a href="">teste de direcionamento</a>'
                        });

                    } else {

                        Swal.close();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: response['message'],
                            customClass: 'tamanho-personalizado-erro'
                        });
                    }
                }//END success
                
            });// END didOpen         
        }//END didOpen
    });//END loadingMessage
}

function atualizaTabelaNotaFiscal(response) {
    //validacao quando remove nfe do editar manifesto
    var nummdf = $('input[name=idMdf]').val();
    if(nummdf !== ''){
        if((response === null) && (nummdf === '') ||(nummdf === null)){//não existe mdf
            swal("Atenção!", "Grave o manifesto antes de adicionar uma nota fiscal!", "warning");
        }else{
            //verifica se é objeto(response do tipo objeto significa ter nf inclusa na mdf)
            if(typeof response === 'object'){
                var data = response;

                var tabela = $("#datatable-buttons-nf");
                var totalmdfe = 0;
                var rows = "";
                tabela.find("tbody td").remove();
                _.each(data, function (item) {
                    debugger
                    //soma os valores das nfs
                    totalmdfe = parseFloat(item.TOTALSEMFORMAT) + parseFloat(totalmdfe);

                    rows += "<tr>";
                    rows += " <td>" + item.ID + "</td>";
                    rows += " <td>" + item.NUMERO + "</td>";
                    rows += " <td>" + item.DATA_FORMATADA + "</td>";
                    rows += " <td>" + item.CLIENTE_DESC + "</td>";
                    rows += " <td>" + item.TOTALNF_FORMATADO + "</td>";
                    rows += " <td> <button type='button' title='remove' class='btn btn-danger btn-xs btn-remover' onclick='javascript:submitRemoveNotaFiscal("+item.ID+");'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button> </td>";
                    rows += "</tr>";
                });
                //tabela.find("tbody").append(rows);
                tabela.find("tbody").html(rows);
                f = document.lancamento;
                //set o valor no input aplicando a mascara
                f.totalcarga.value = totalmdfe.toLocaleString('pt-br', { minimumFractionDigits: 2 });;
            }//fim typeof
        }//fim else
    }else{
        return false;
    }
}//fim atualizaTabelaNotaFiscal

function imprimeDamdfe(id){
    window.open('index.php?mod=est&form=mdfe_imprime&opcao=imprimir&id='+id, 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}

function submitMostraNota() {
    f = document.lancamento;

    if((f.numNotaFiscal.value === '') || (f.numNotaFiscal.value === null)){
        swal("Atenção!", "Pesquise a nota fiscal para adicionar.", "warning");
    }else{
        var dados = {
            'idMdf': f.id.value
        }
        //ajax responsavel por enviar dados ao form
        $.ajax({
            type: "POST",
            url: document.URL + "?mod=est&form=manifesto_fiscal&submenu=addNotaFiscal&opcao=blank",
            data: dados,
            dataType: "json",
            success: [atualizaTabelaNotaFiscal]
        });

        document.getElementById("numNotaFiscal").value = '';
        document.getElementById("numPedido").value = '';
        document.getElementById("filial").value = '';
        document.getElementById("descPessoa").value = '';
    }
}



function submitDownloadXml(id) {
    debugger
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=manifesto_fiscal_sefaz&submenu=downloadXml&opcao=blank&idNf=" + id,
        success: [responseSubmitDownloadXml]
    });
} // fim submitConsultaServer


// function responseSubmitDownloadXml(response, status, xhr) {
//     debugger
//     var contentType = xhr.getResponseHeader('Content-Type');

//     if (contentType == "application/json"){

//         switch (response.code) {
//             case 101:
//                 swal({
//                     title: "Atenção",
//                     text: 'Chave de acesso não localizada para a consulta!',
//                     icon: "error",
//                     button: "OK",
//                     dangerMode: true
//                 });
//                 $('.swal-modal').css("width", "610px");
//                 break;
//             case 401:
//                 nomeDia = "Terça-feira";
//                 break;
//             case 100: //Donwload e insercao no banco 

//                 swal({
//                     title: "Atenção!",
//                     text: "Download do xml realizado, deseja realizar a entrada no sistema?",
//                     icon: "warning",
//                     buttons: {
//                         cancelar: {
//                             text: "Cancelar",
//                             value: "0"
//                         },
//                         download: {
//                             text: "Entrada Xml",
//                             value: "1"
//                         }
//                     }
//                 })
//                     .then((yes) => {
//                         debugger
//                         if (yes === '1') {
//                             var url = document.URL + "?mod=est&form=nota_xml_importa&submenu=entradaManifesto&idNf=" + response.id_nota;
//                             window.open(url, '_blank');
//                         } else {
//                             return false;
//                         }
//                     });

//                 break;
//         }
//     }else{
//         swal({
//             title: "Atenção",
//             text: 'Erro no processo, entre em contato com o suporte!',
//             icon: "error",
//             button: "OK",
//             dangerMode: true
//         });
//         $('.swal-modal').css("width", "610px");
//     }

// } //END responseSubmitDownloadXml

function responseSubmitDownloadXml(response, status, xhr) {
    debugger
    var contentType = xhr.getResponseHeader('Content-Type');

    if (contentType == "application/json") {

        switch (response.code) {
            case 101:
                Swal.fire({
                    title: "Atenção",
                    text: 'Chave de acesso não localizada para a consulta!',
                    icon: "error",
                    confirmButtonText: "OK",
                    dangerMode: true
                });
                break;
            case 401:
                Swal.fire({
                    title: "Atenção",
                    text: 'Chave de acesso não localizada para a consulta!',
                    icon: "error",
                    confirmButtonText: "OK",
                    dangerMode: true
                });
                break;
            case 100: // Download e inserção no banco 

                Swal.fire({
                    title: "Atenção!",
                    text: "Download do xml realizado, deseja realizar a entrada no sistema?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Entrada Xml",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    debugger
                    if (result.isConfirmed) {
                        var url = document.URL + "?mod=est&form=nota_xml_importa&submenu=entradaManifesto&idNf=" + response.id_nota;
                        window.open(url, '_blank');
                    } else {
                        location.reload();
                        return false;
                    }
                });

                break;
        }
    } else {
        Swal.fire({
            title: "Atenção",
            text: 'Erro no processo, entre em contato com o suporte!',
            icon: "error",
            confirmButtonText: "OK",
            dangerMode: true
        });
        $('.swal-modal').css("width", "610px");
    }

    //redireciona para o login
    //location.href = location.href; = location.href;
}

// function submitDownloadXmlExiste(id, fileName) {
//     debugger

//     var xmlString = document.getElementById('xml'+id).innerHTML;

//     if (xmlString === ''){

//         swal({
//             title: "Atenção",
//             text: 'XML não localizado, entre em contato com o suporte!',
//             icon: "error",
//             button: "OK",
//             dangerMode: true
//         });
//         $('.swal-modal').css("width", "610px");
//         return false;

//     }else{

//         swal({
//             title: "Atenção!",
//             text: "Download do xml realizado, o que deseja realizar?",
//             icon: "warning",
//             buttons: {
//                 cancelar: {
//                     text: "Fazer o download local",
//                     value: "0"
//                 },
//                 download: {
//                     text: "Entrada Xml no sistema",
//                     value: "1"
//                 }
//             }
//         })
//             .then((yes) => {
//                 debugger
//                 if (yes === '1') {

//                     var url = document.URL + "?mod=est&form=nota_xml_importa&submenu=entradaManifesto&idNf=" + id;
//                     window.open(url, '_blank');

//                 } else {

//                     var blob = new Blob([xmlString], { type: 'application/xml' });
//                     var url = URL.createObjectURL(blob);
            
//                     var a = document.createElement('a');
//                     a.href = url;
//                     a.download = fileName + '.xml';
//                     a.style.display = 'none';
//                     document.body.appendChild(a);
//                     a.click();
//                     document.body.removeChild(a);

//                 }
//             });
//     }
// }

function submitDownloadXmlExiste(id, fileName) {
    debugger;

    var xmlString = document.getElementById('xml'+id).innerHTML;

    if (xmlString === '') {
        Swal.fire({
            title: "Atenção",
            text: 'XML não localizado, entre em contato com o suporte!',
            icon: "error",
            confirmButtonText: "OK",
            dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.getPopup().querySelector('.swal2-modal').style.width = "610px";
            }
        });
        return false;

    } else {
        Swal.fire({
            title: "Atenção!",
            text: "Download do xml realizado, o que deseja realizar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Entrada Xml no sistema",
            cancelButtonText: "Fazer o download local",
            reverseButtons: true
        }).then((result) => {
            debugger;
            if (result.isConfirmed) {
                var url = document.URL + "?mod=est&form=nota_xml_importa&submenu=entradaManifesto&idNf=" + id;
                window.open(url, '_blank');

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                var blob = new Blob([xmlString], { type: 'application/xml' });
                var url = URL.createObjectURL(blob);

                var a = document.createElement('a');
                a.href = url;
                a.download = fileName + '.xml';
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        });
    }
}



//OLD
// function responseSubmitDownloadXml(response, status, xhr){
//     debugger
//     var contentType = xhr.getResponseHeader('Content-Type');
    

//     if (contentType == "application/json"){
        
//         if(response == '401'){
//             swal({
//                 title: "Atenção",
//                 text: 'Chave de acesso não localizada para a consulta!',
//                 icon: "error",
//                 button: "OK",
//                 dangerMode: true
//             });
//             $('.swal-modal').css("width", "610px");
//         }else if(reponse == '402'){
//             console.log(response);
//         }else{
//             console.log(response);
//         }

//     }else if(contentType == "application/xml"){

//         var nameArqTemp = xhr.getResponseHeader('Content-Disposition');
//         const regex = /filename="(.+?)"/;
//         const resultadoRegex = regex.exec(nameArqTemp);

//         if (resultadoRegex && resultadoRegex.length > 1) {
//             var nomeArquivo = resultadoRegex[1];
//         } else {
//             var nomeArquivo = 'nameTemp.xml'
//         }

//         //Criar um Blob com o conteúdo XML
//         const blob = new Blob([response], { type: "application/xml" });

//         // Criar um link temporário para fazer o download
//         const url = window.URL.createObjectURL(blob);
//         const a = document.createElement("a");
//         a.href = url;
//         a.download = nomeArquivo;

//         // Disparar o clique no link para iniciar o download
//         a.click();

//         swal({
//             title: "Atenção!",
//             text: "Download do xml realizado, deseja abrir a tela de entrada de xml?",
//             icon: "warning",
//             buttons: {
//                 cancelar: {
//                     text: "Cancelar",
//                     value: "0"
//                 },
//                 download: {
//                     text: "Abrir nova guia",
//                     value: "1"
//                 }
//             }
//         })
//             .then((yes) => {
//                 debugger
//                 if (yes === '1') {
//                     var url = document.URL + "?mod=est&form=nota_xml_importa";
//                     window.open(url, '_blank');
//                 } else {
//                     return false;
//                 }
//             });

//     }else{
//         debugger
//         swal({
//             title: "Atenção",
//             text: response,
//             icon: "info",
//             button: "OK",
//             dangerMode: true
//         });
//         $('.swal-modal').css("width", "848px");
//     }
    

// }
function submitCienciaEmissao(id){
    debugger

    swal({
        title: "Atenção!",
        text: "Confirma a ciência da emissão?",
        icon: "warning",
        buttons: ["Cancelar", "Continuar"],
    })
    .then((yes) => {
        debugger
        if (yes) {

            $.ajax({
                type: "POST",
                url: document.URL + "?mod=est&form=manifesto_fiscal_sefaz&submenu=cienciaEmissao&opcao=blank",
                dataType: "json",
                success: [responseCienciaEmissao]
            });


        } else {
            return false;
        }
    });
}

function responseCienciaEmissao(response) {
    debugger
    if (response['codStatus'] == 135) { //cancelamento realizado
        swal({
            title: "MDFe cancelado!",
            text: response['msg'],
            icon: "success",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
        $('#justificativa').val('');
    } else if (response['codStatus'] == 630) { //erro de preenchimento de xml
        swal({
            title: "Atenção!",
            text: 'Cancelamento não realizado "' + response['msg'] + '"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "722px");
        $('.swal-text').css("max-width", "calc(100% - 136px");
    } else { //default
        swal({
            title: "Atenção!",
            text: 'Cancelamento não realizado "' + response['msg'] + '"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
    }
}


function responseCancelaMdfe(response){
    debugger
    if(response['codStatus'] == 135){ //cancelamento realizado
        swal({
            title: "MDFe cancelado!",
            text: response['msg'],
            icon: "success",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
        $('#justificativa').val('');
    }else if(response['codStatus'] == 630){ //erro de preenchimento de xml
        swal({
            title: "Atenção!",
            text: 'Cancelamento não realizado "'+response['msg']+'"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "722px");
        $('.swal-text').css("max-width", "calc(100% - 136px");
    }else{ //default
        swal({
            title: "Atenção!",
            text: 'Cancelamento não realizado "'+response['msg']+'"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
    }
}



