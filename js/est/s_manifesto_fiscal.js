function setCliente(){
    f = document.lancamento;
    id =  f.condutor.options[f.condutor.selectedIndex].value;
    f.condutor.value = id;
}

function submitGerarXmlManifesto(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = 'geraXmlManifesto';
    f.id.value = id;
    f.submit();
}// submitGerarXmlManifesto

function submitEncerraMdfe(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = 'encerraMdfe';
    f.id.value = id;
    f.submit();
}// submitEncerraMdfe

function submitVoltar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar

function submitConfirmar(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';

    if((f.condutor.value == '') || (f.condutor.value == null)){
         swal.fire({
            title: "Atenção!",
            text: "Campo 'CONDUTOR' deve ser preenchido!",
            icon: "warning",
            button: "OK",
            className: "red-bg",
          });

          return false
    }
    if((f.veiculoTracao.value == '') || (f.veiculoTracao.value == null)){
         swal.fire({
            title: "Atenção!",
            text: "Campo 'VEÍCULO' deve ser preenchido!",
            icon: "warning",
            button: "OK",
            className: "red-bg",
          });

          return false
    }
     swal.fire({
        title: "Atenção!",
        text: "Deseja " + f.submenu.value + " este manifesto fiscal?",
        icon: "warning",
        buttons: ["Cancelar", "Continuar"],
    })
    .then((yes) => {
         
        if (yes) {
            f.opcao.value = formulario;
            if (f.submenu.value == "cadastrar") {
                f.submenu.value = 'inclui';
            }
            else{
                f.submenu.value = 'altera';
            }
            f.submit();
        }else{
            return false;
        }
    });
}

// ####################

function montaLetra() {
    l = document.lancamento;
    l.letra.value = l.mfilial.value + "|" + 
                    l.msituacao.value + "|" + 
                    l.dataIni.value + "|" + 
                    l.dataFim.value + "|" + 
                    l.mdf.value + "|" + 
                    l.serie.value + "|" + 
                    l.condutor.value + "|" + 
                    l.nomecondutor.value;
}// submitLetra

function submitLetra() {
     
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = 'letra';
    montaLetra();
    f.submit();
}// submitLetra

function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
} // submitCadastro


function submitGerarXML(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.opcao.value = 'produto';
    f.submenu.value = 'geraXML';
    f.id.value = id;
    f.submit();
}// submitAlterar

function submitAlterar(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.opcao.value = 'NotaFiscal';
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.submit();
}// submitAlterar

function submitExcluir(id) {
     swal.fire({
        title: "Atenção!",
        text: "Deseja excluir este pedido?",
        icon: "warning",
        buttons: ["Cancelar", "Continuar"],
    })
    .then((yes) => {
        if (yes) {
            f = document.lancamento;
            f.submenu.value = "exclui";
            f.id.value = id;
            f.submit();
        } else {
            return false;
        }
    });

    // if (confirm("Deseja realmente Excluir este pedido") == true) {
    //   f = document.lancamento;
    //   f.submenu.value = "exclui";
    //   f.id.value = id;
    //   f.submit();
    // } // if
    //}  submitExcluir
    // if (confirm('Deseja realmente Excluir este MDFe?') == true) {
    //     f = document.lancamento;
    //     f.mod.value = 'est';
    //     f.form.value = 'manifesto_fiscal';
    //     f.opcao.value = 'NotaFiscal';
    //     f.submenu.value = 'exclui';
    //     f.id.value = id;
    //     f.submit();
    //}  if
}// submitExcluir

// function abrir(pag) {
//     window.open(pag, 'toolbar=no,location=no,menubar=no,width=850,height=650,scrollbars=yes');
// }

function abrir(pag){
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=900,height=550,scrollbars=yes');
}

function getMoney(el) {
    var money = id(el).value.replace(',', '.');
    return parseFloat(money);
}

function consultaStatusMdfe(id) {
    g = document.lancamento;
    g.mod.value = 'est';
    g.form.value = 'manifesto_fiscal';
    g.submenu.value = 'consultaStatusMdfe';
    g.id.value = id;
    g.submit();
}

function printDanfe(id) {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'manifesto_fiscal';
    f.opcao.value = '';
    f.submenu.value = 'danfe';
    f.id.value = id;
    f.submit();

    //    window.open('../astecv3/forms/est/p_nfephp_imprime_danfe.php?id='+id, 'DANFE', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
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

// function calculaTotalNfAjax() {
//      
//      swal.fire({
//       title: "Deseja alterar o valor?",
//       text: "Será alterado o valor total da nf e realizado novo rateio entre o(s) item(ns)!",
//       icon: "warning",
//       buttons: true,
//       dangerMode: true,
//     })
//     .then((willDelete) => {
//         if (willDelete) {
//             f = document.lancamento;
//             f.mod.value = 'est';
//             f.form.value = 'manifesto_fiscal';
//             f.submenu.value = 'alterarAjax';
//             var form = $("form[name=lancamento]");
//             $.ajax({
//                 type: "POST",
//                 url: form.action ? form.action : document.URL,
//                 data: $(form).serialize(),
//                 dataType: "text",
//                 success: [atualizaView],
//                 beforeSend: function(xhr) {
//                     xhr.setRequestHeader("Ajax-Request-Atualiza-Total", "true");
//                 },
//             });
//         } else {
//            return false;
//         }
//     });
// }
// function atualizaView(response){
//      
//     var result = $('<div />').append(response).find('[name=divTotalNf]').html();
//     $('[name=divTotalNf]').html(result);
//      swal.fire("", {
//       title: "Alterado!",
//       icon: "success",
//     });
// }

function calculaTotalMdfe(){
    f = document.lancamento;
    var table = document.getElementById("datatable-buttons-nf");
    var r = table.rows.length;
    var count = 0;
    var notaFiscais = "";
    
    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("rowsMdfe");        
        if (row.length > 0){
            if (row[0].checked == true) {
                nfChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[7].childNodes[0].data;    
                nfId   = cells[1].childNodes[0].data;   
    
                if (pessoa === ''){
                    pessoa = novaPessoa;
                }
                
                if(novaPessoa === pessoa){   
                    notaFiscais = notaFiscais + "|" + nfId.trim();
                }else{
                    alert("Selecione a mesma Pessoa para fazer a Devolução de Nf.");
                    return false;
                }
                count += 1
            }
        }
    }

    if(nfChecked == true){
        f.devolucaoNotaFiscal.value = "";
        f.devolucaoNotaFiscal.value = notaFiscais;
        f.submenu.value = "devolucaoNotaFiscal";
        f.submit();
    }else{
        alert("Selecione mais de uma Nf para fazer a Devolução.");
        return false;
    }
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

// function submitSelecionarTodos(){

//     var checkValue = document.mostra.todosChecked.value;
//     if(checkValue == ''){
//         checkValue = true;
//     }else if (checkValue == 'true'){
//         checkValue = false;
//     }else{
//         checkValue = true;
//     }
//     var table = document.getElementById("datatable-buttons");

//     var r = table.rows.length;
//     for (i = 1; i < r; i++) {
//         var row = table.rows.item(i).getElementsByTagName("input");
        
//         if (row.prodChecked.checked != checkValue) {
//             row.prodChecked.checked = checkValue;
//         }
//     }

//     document.mostra.todosChecked.value = checkValue;

// }

function submitConfirmarNota() {
    f = document.lancamento;

    if((f.numNotaFiscal.value === '') || (f.numNotaFiscal.value === null)){
         swal.fire({
            title:"Atenção!",
            text:"Pesquise a nota fiscal para adicionar.",
            icon:"warning"});
    }else{
        var dados = {
            'idNF' : f.idNotaFiscal.value,
            'idMdf': f.id.value,
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

function submitRemoveNotaFiscal(notaFiscal){
    f = document.lancamento;
    //msg
     swal.fire({
        title: "Atenção!",
        text: "Deseja remover a nota fiscal?",
        icon: "warning",
        buttons: ["Cancelar", 'Remover'],
    })
    .then((yes) => {
        if(yes){
            var dados = {
                'idNF' : notaFiscal,
                'idMdf': f.id.value,
            }
            //ajax responsavel por enviar dados ao form
            $.ajax({
                type: "POST",
                url: document.URL + "?mod=est&form=manifesto_fiscal&submenu=removeNotaFiscal&opcao=blank",
                data: dados,
                dataType: "json",
                success: [atualizaTabelaNotaFiscal]
            });//fim yes
        }else{
            return false;
        }
    });
}

function atualizaTabelaNotaFiscal(response) {
    //validacao quando remove nfe do editar manifesto
    var nummdf = $('input[name=idMdf]').val();
    if(nummdf !== ''){
        if((response === null) && (nummdf === '') ||(nummdf === null)){//não existe mdf
             swal.fire({
                title:"Atenção!",
                text:"Grave o manifesto antes de adicionar uma nota fiscal!",
                icon:"warning"});
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
         swal.fire({
            title:"Atenção!",
            text:"Pesquise a nota fiscal para adicionar.",
            icon:"warning"});
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

//imprimir MDFe
function submitImprime(id) {
    f = document.lancamento;
    f.id.value = id;
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = 'imprimir';
    f.submit();
} // fim submitConsultaServer

function cancelaMdfe(id){
     
    var justificativa = document.getElementById("justificativa").value;
    if(justificativa.length > 15){
        var data = {
            'justificativa': document.getElementById("justificativa").value, 
            'idMdf': id
        }
        $.ajax({
            type: "POST",
            url: document.URL + "?mod=est&form=manifesto_fiscal&submenu=cancelarMdfe&opcao=blank",
            data: data,
            dataType: "json",
            success: [responseCancelaMdfe]
        });
    }else{
         swal.fire({
            title: "Atenção!",
            text: 'Digite no mínimo 15 caracteres!',
            icon: "warning",
            button: "OK",
        });
    }

}

function responseCancelaMdfe(response){
     
    if(response['codStatus'] == 135){ //cancelamento realizado
         swal.fire({
            title: "MDFe cancelado!",
            text: response['msg'],
            icon: "success",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
        $('#justificativa').val('');
    }else if(response['codStatus'] == 630){ //erro de preenchimento de xml
         swal.fire({
            title: "Atenção!",
            text: 'Cancelamento não realizado "'+response['msg']+'"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "722px");
        $('.swal-text').css("max-width", "calc(100% - 136px");
    }else{ //default
         swal.fire({
            title: "Atenção!",
            text: 'Cancelamento não realizado "'+response['msg']+'"',
            icon: "warning",
            button: "OK",
        });
        $('.swal-modal').css("width", "610px");
    }
}

function consultaNaoEncerrrados(){
    f = document.lancamento;
    f.form.value = 'manifesto_fiscal';
    f.submenu.value = 'consultaNaoEncerrados';
    f.submit();
}


