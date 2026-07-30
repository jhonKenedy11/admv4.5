function toggleInput() {
    
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
    }else if(typeEvent == 'desconhecimento'){
        textQuestion = 'desconhecimento da operação';
    }else if(typeEvent == 'naorealizada'){
        textQuestion = 'operação não realizada';
    }

    Swal.fire({
        title: "Atenção!",
        text: `Deseja enviar o evento de ${textQuestion}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
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
                if (xhr.status === 200) {
                    // Process the response data here if needed
                    location.reload();
                } else {
                    // Handle error if the request fails
                    console.error('Erro ao enviar o evento de ' + textQuestion + ': ', xhr.status);
                }
            }
    
            // Send the FormData object with the request
            xhr.send(formData);
    
        } else {
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

function manifestoDistNfeUrl(submenu) {
    return document.URL + '?mod=est&form=manifesto_fiscal_sefaz&submenu=' + submenu + '&opcao=blank';
}

function manifestoDistNfeHtmlProgresso(textoPrincipal, detalhe) {
    return '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>'
        + '<p><b>' + textoPrincipal + '</b></p>'
        + '<p id="manifesto-dist-progress-detail" style="margin-top:8px;font-size:0.95em;">' + (detalhe || '') + '</p>';
}

function manifestoDistNfeAtualizaProgresso(atual, total, ultNSU, maxNSU) {
    var el = document.getElementById('manifesto-dist-progress-detail');
    if (!el) return;
    var linha = 'Conectando à Receita Federal...';
    if (total && parseInt(total, 10) > 0) {
        linha = 'Lote ' + atual + ' de ' + total;
        if (ultNSU && maxNSU) linha += ' (NSU ' + ultNSU + ' / ' + maxNSU + ')';
    }
    el.textContent = linha;
}

function manifestoDistNfeTrataRespostaFinal(response) {
    var cStat = response.cStat;
    if (cStat === 'true' || cStat === true) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: response.message || 'Notas baixadas!',
            showConfirmButton: true
        }).then(function () { location.reload(); });
        return;
    }
    var bloqueio = cStat === '405';
    Swal.fire({
        icon: bloqueio ? 'error' : (cStat === 'atencao' ? 'warning' : 'error'),
        title: 'Atenção',
        text: response.message || (bloqueio ? '' : 'Não foi possível concluir a consulta na Receita Federal.'),
        customClass: bloqueio ? 'tamanho-personalizado-minutos' : 'tamanho-personalizado-erro'
    });
}

function manifestoDistNfeConsultarProximoLote(delayMs) {
    setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: manifestoDistNfeUrl('consultarDocumentosSefazLote'),
            dataType: 'json',
            timeout: 300000,
            success: function (response) {
                if (!response || typeof response !== 'object') {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Resposta inválida do servidor.' });
                    return;
                }
                if (response.cStat === 'progress') {
                    manifestoDistNfeAtualizaProgresso(response.atual, response.total, response.ultNSU, response.maxNSU);
                    manifestoDistNfeConsultarProximoLote(2000);
                    return;
                }
                Swal.close();
                manifestoDistNfeTrataRespostaFinal(response);
            },
            error: function (xhr) {
                var msg = 'Falha ao consultar notas na Receita Federal.';
                try {
                    var j = JSON.parse(xhr.responseText || '');
                    if (j.message) msg = j.message;
                } catch (e) { /* não é JSON */ }
                Swal.fire({ icon: 'error', title: 'Erro', text: msg });
            }
        });
    }, delayMs || 0);
}

function submitConsultaDocumentosSefaz() {
    Swal.fire({
        html: manifestoDistNfeHtmlProgresso('Consultando notas fiscais...', 'Conectando à Receita Federal...'),
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: function () {
            $.ajax({
                type: 'POST',
                url: manifestoDistNfeUrl('consultarDocumentosSefazPreparar'),
                dataType: 'json',
                timeout: 60000,
                success: function (prep) {
                    if (!prep || typeof prep !== 'object') {
                        Swal.fire({ icon: 'error', title: 'Erro', text: 'Resposta inválida ao iniciar consulta.' });
                        return;
                    }
                    if (prep.cStat !== 'ready') {
                        Swal.close();
                        manifestoDistNfeTrataRespostaFinal(prep);
                        return;
                    }
                    manifestoDistNfeConsultarProximoLote(0);
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível iniciar a consulta na Receita Federal.' });
                }
            });
        }
    });
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


function submitDownloadXmlExiste(id) {

    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=manifesto_fiscal_sefaz&submenu=downloadXml&opcao=blank&idNf=" + id,
        success: function(response, status, xhr) {
            var responseData;
            
            if (typeof response === 'object' && response !== null) {
                responseData = response;
            } else if (typeof response === 'string') {
                try {
                    // Tenta fazer parse do JSON se for string
                    var jsonStringSemEspacos = response.trim();
                    responseData = JSON.parse(jsonStringSemEspacos);
                } catch (e) {
                    // Se não for JSON válido, mostra erro genérico
                    var respostaTexto = response.length > 200 ? response.substring(0, 200) + '...' : response;
                    
                    Swal.fire({
                        title: "Erro",
                        html: '<p>Erro ao processar resposta do servidor.</p><p>Resposta recebida: <strong>' + 
                              respostaTexto + '</strong></p>',
                        icon: "error",
                        confirmButtonText: "OK",
                        width: '600px'
                    });
                    console.error('Erro ao fazer parse do JSON:', e);
                    console.error('Resposta recebida:', response);
                    return;
                }
            } else {
                // Tipo de resposta não esperado
                Swal.fire({
                    title: "Erro",
                    html: '<p>Resposta inválida do servidor.</p><p>Tipo recebido: ' + typeof response + '</p>',
                    icon: "error",
                    confirmButtonText: "OK",
                    width: '500px'
                });
                console.error('Tipo de resposta não esperado:', typeof response, response);
                return;
            }

            // Verifica se é um objeto válido
            if (typeof responseData !== 'object' || responseData === null) {
                Swal.fire({
                    title: "Erro",
                    text: 'Resposta inválida do servidor',
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }
                
            switch (responseData.code) {
                case 406: // Resumo retornado (resNFe)
                    var mensagem = responseData.message || 'A SEFAZ retornou apenas um resumo da nota fiscal (resNFe), não o XML completo.';
                    var htmlContent = '<p style="margin-bottom: 10px;">' + mensagem + '</p>';
                    
                    if (responseData.chave_acesso) {
                        htmlContent += '<p style="margin: 8px 0;"><strong>Chave:</strong></p>';
                        htmlContent += '<p style="word-break: break-all; background-color: #f5f5f5; padding: 8px; border-radius: 4px; font-family: monospace; font-size: 11px; margin-bottom: 8px;">' + 
                                      responseData.chave_acesso + '</p>';
                        htmlContent += '<p style="margin: 8px 0;"><a href="https://chaves.fsist.com.br/" target="_blank" style="color: #007bff; text-decoration: underline; font-size: 12px;">Consultar no FSIST</a></p>';
                    }
                    
                    Swal.fire({
                        title: "Resumo Retornado",
                        html: htmlContent,
                        icon: "info",
                        confirmButtonText: "OK",
                        width: '500px'
                    });
                    break;
                    
                case 405: // Download sefaz no fulfilled
                    
                    var mensagem = responseData.message || 'Não foi possível realizar o download do XML na SEFAZ.';
                    var htmlContent = '<p>' + mensagem + '</p>';
                    
                    if (responseData.chave_acesso) {
                        htmlContent += '<hr><p><strong>Chave de Acesso:</strong></p>';
                        htmlContent += '<p style="word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace;">' + 
                                      responseData.chave_acesso + '</p>';
                        htmlContent += '<p><strong>Consultar no FSIST:</strong></p>';
                        htmlContent += '<p><a href="https://chaves.fsist.com.br/" target="_blank" style="color: #007bff; text-decoration: underline;">';
                        htmlContent += 'https://chaves.fsist.com.br/</a></p>';
                        htmlContent += '<p><small>Cole a chave de acesso acima no site do FSIST para consultar a nota fiscal completa.</small></p>';
                    }
                    
                    Swal.fire({
                        title: "Atenção",
                        html: htmlContent,
                        icon: "warning",
                        confirmButtonText: "OK",
                        width: '700px'
                    });
                    break;
                    
                case 404: // Key not found in database
                    var mensagem404 = responseData.message || 'Chave de acesso não localizada para a consulta!';
                    Swal.fire({
                        title: "Atenção",
                        text: mensagem404,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                    break;
                    
                case 100: // Download accomplished
                    var blob = new Blob([responseData.xml], { type: 'application/xml' });
                    var url = URL.createObjectURL(blob);

                    var a = document.createElement('a');
                    a.href = url;
                    a.download = responseData.fileName + '.xml';
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    
                    // Libera a URL do objeto após o download
                    setTimeout(function() {
                        URL.revokeObjectURL(url);
                    }, 100);
                    break;
                    
                default:
                    Swal.fire({
                        title: "Atenção",
                        text: responseData.message || 'Erro desconhecido no processo',
                        icon: "error",
                        confirmButtonText: "OK"
                    });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Erro",
                html: '<p>Erro ao comunicar com o servidor.</p><p><strong>Status:</strong> ' + status + '</p><p><strong>Erro:</strong> ' + error + '</p>',
                icon: "error",
                confirmButtonText: "OK",
                width: '500px'
            });
            console.error('Erro na requisição AJAX:', error);
        }
    });

}

function submitCienciaEmissao(id){
    swal({
        title: "Atenção!",
        text: "Confirma a ciência da emissão?",
        icon: "warning",
        buttons: ["Cancelar", "Continuar"],
    })
    .then((yes) => {
        
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

// function responseCancelaMdfe(response){
//     
//     if(response['codStatus'] == 135){ //cancelamento realizado
//         swal({
//             title: "MDFe cancelado!",
//             text: response['msg'],
//             icon: "success",
//             button: "OK",
//         });
//         $('.swal-modal').css("width", "610px");
//         $('#justificativa').val('');
//     }else if(response['codStatus'] == 630){ //erro de preenchimento de xml
//         swal({
//             title: "Atenção!",
//             text: 'Cancelamento não realizado "'+response['msg']+'"',
//             icon: "warning",
//             button: "OK",
//         });
//         $('.swal-modal').css("width", "722px");
//         $('.swal-text').css("max-width", "calc(100% - 136px");
//     }else{ //defaulttypeEvent
//         swal({
//             title: "Atenção!",
//             text: 'Cancelamento não realizado "'+response['msg']+'"',
//             icon: "warning",
//             button: "OK",
//         });
//         $('.swal-modal').css("width", "610px");
//     }
// }




