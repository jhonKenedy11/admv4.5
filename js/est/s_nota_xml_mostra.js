$(document).ready(function(){
    $('.masked-date').inputmask('dd/mm/yyyy', { placeholder: 'dd/mm/yyyy' });
});

function formatData(data) {
    var parts = data.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}

function limparCampos() {
     
  document.getElementById('m_produto').value = "";
  document.getElementById('m_codProduto').value = "";
  document.getElementById('m_unidade').value = "";
  document.getElementById('m_serie').value = "";
  document.getElementById('m_ordemServico').value = "";
  document.getElementById('m_cfop').value = "";
  document.getElementById('m_ncm').value = "";
  document.getElementById('m_cest').value = "";
  document.getElementById('m_cbenef').value = "";
  document.getElementById('m_quantidade').value = "";
  document.getElementById('m_valorUnitario').value = "";
  document.getElementById('m_desconto').value = "";
  document.getElementById('m_total').value = "";
  document.getElementById('m_lote').value = "";
  document.getElementById('m_dataFabricacao').value = "";
  document.getElementById('m_dataValidade').value = "";
  document.getElementById('m_dataGarantia').value = "";
  document.getElementById('m_frete').value = "";
  document.getElementById('m_despAcessorias').value = "";
  document.getElementById('m_quantidade').value = "";
};

function showProductInfo(element) {
     
    //variaveis default para montagem das combos
    let selectElement = null;
    let selectedValue = null;
    let optionToSelect = null;
    let row = element.closest('tr');
    document.getElementById('li_infos').textContent = row.querySelector('#descricao').innerText + ' | total: ' + row.querySelector('#valorTotal').innerText;
    document.getElementById('m_idProd').value = row.querySelector('#idProd').innerText;
    // PRODUTO
    document.getElementById('m_produto').value = row.querySelector('#descricao').innerText;
    document.getElementById('m_codProduto').value = row.querySelector('#codigo').innerText;
    document.getElementById('m_unidade').value = row.querySelector('#unidade').innerText;
    document.getElementById('m_serie').value = row.querySelector('#numeroSerie').innerText;
    document.getElementById('m_ordemServico').value = row.querySelector('#osParceiro').innerText;
    document.getElementById('m_cfop').value = row.querySelector('#cfop').innerText;
    document.getElementById('m_ncm').value = row.querySelector('#ncm').innerText;
    document.getElementById('m_cest').value = row.querySelector('#cest').innerText;
    //combo beneficio fiscal
    selectElement = document.getElementById('m_cbenef');
    if (selectElement) {
        selectedValue = row.querySelector('#codigoBeneficio').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_quantidade').value = row.querySelector('#quantidade').innerText;
    document.getElementById('m_valorUnitario').value = row.querySelector('#valorUnitario').innerText;
    document.getElementById('m_desconto').value = row.querySelector('#desconto').innerText;
    document.getElementById('m_total').value = row.querySelector('#valorTotal').innerText;
    document.getElementById('m_lote').value = row.querySelector('#lote').innerText;
    document.getElementById('m_dataFabricacao').value = formatData(row.querySelector('#dataFabricacao').innerText);
    document.getElementById('m_dataValidade').value = formatData(row.querySelector('#dataValidade').innerText);
    document.getElementById('m_dataGarantia').value = formatData(row.querySelector('#dataGarantia').innerText);
    document.getElementById('m_frete').value = row.querySelector('#frete').innerText;
    document.getElementById('m_despAcessorias').value = row.querySelector('#despAcessorias').innerText;
    // ICMS
    //combo origem
    selectElement = document.getElementById('m_origem');
    if (selectElement) {
        selectedValue = row.querySelector('#origem').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    //combo icms/csosn
    selectElement = document.getElementById('m_tribIcms');
    if (selectElement) {
        selectedValue = row.querySelector('#tribIcms').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    //modalidade de base de calculo
    selectElement = document.getElementById('m_modBc');
    if (selectElement) {
        selectedValue = row.querySelector('#modBc').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_bcIcms').value = row.querySelector('#bcIcms').innerText;
    document.getElementById('m_aliqIcms').value = row.querySelector('#aliqIcms').innerText;
    document.getElementById('m_valorIcms').value = row.querySelector('#valIcms').innerText;
    document.getElementById('m_percReducaoBc').value = row.querySelector('#percReducaoBc').innerText;
    document.getElementById('m_valorIcmsOperacao').value = row.querySelector('#valorIcmsOperacao').innerText;
    document.getElementById('m_percDiferimento').value = row.querySelector('#percDiferido').innerText;
    document.getElementById('m_valorIcmsDiferimento').value = row.querySelector('#valorIcmsDiferido').innerText;
    //modalidade de base de calculo st
    selectElement = document.getElementById('m_modBcSt');
    if (selectElement) {
        selectedValue = row.querySelector('#modBcSt').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_valorBcSt').value = row.querySelector('#valorBcSt').innerText;
    document.getElementById('m_aliqIcmsSt').value = row.querySelector('#aliqIcmsSt').innerText;
    document.getElementById('m_percReducaoBcSt').value = row.querySelector('#percReducaoBcSt').innerText;
    document.getElementById('m_percMvaSt').value = row.querySelector('#percMvaSt').innerText;
    document.getElementById('m_valorIcmsSt').value = row.querySelector('#valorIcmsSt').innerText;
    document.getElementById('m_bcFcpSt').value = row.querySelector('#bcFcpSt').innerText;
    document.getElementById('m_aliqFcpSt').value = row.querySelector('#aliqFcpSt').innerText;
    document.getElementById('m_valorFcpSt').value = row.querySelector('#valorFcpSt').innerText;
    document.getElementById('m_bcFcpUfDest').value = row.querySelector('#bcFcpUfDest').innerText;
    document.getElementById('m_aliqFcpUfDest').value = row.querySelector('#aliqFcpUfDest').innerText;
    document.getElementById('m_valorFcpUfDest').value = row.querySelector('#valorFcpUfDest').innerText;
    document.getElementById('m_BcIcmsUfDest').value = row.querySelector('#bcIcmsUfDest').innerText;
    document.getElementById('m_aliqIcmsUfDest').value = row.querySelector('#aliqIcmsUfDest').innerText;
    document.getElementById('m_valorIcmsUfDest').value = row.querySelector('#valorIcmsUfDest').innerText;
    document.getElementById('m_aliqIcmsInter').value = row.querySelector('#aliqIcmsInter').innerText;
    document.getElementById('m_aliqIcmsInterPart').value = row.querySelector('#aliqIcmsInterPart').innerText;
    document.getElementById('m_valorIcmsUfRemet').value = row.querySelector('#valorIcmsUfRemet').innerText;
    // IPI/PIS/CONFINS
    //cst ipi
    selectElement = document.getElementById('m_cstIpi');
    if (selectElement) {
        selectedValue = row.querySelector('#cstIpi').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_bcIpi').value = row.querySelector('#bcIpi').innerText;
    document.getElementById('m_aliqIpi').value = row.querySelector('#aliqIpi').innerText;
    document.getElementById('m_valorIpi').value = row.querySelector('#valorIpi').innerText;
    //cst pis
    selectElement = document.getElementById('m_cstPis');
    if (selectElement) {
        selectedValue = row.querySelector('#cstPis').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_bcPis').value = row.querySelector('#bcPis').innerText;
    document.getElementById('m_aliqPis').value = row.querySelector('#aliqPis').innerText;
    document.getElementById('m_valorPis').value = row.querySelector('#valorPis').innerText;
    //cst cofins
    selectElement = document.getElementById('m_cstCofins');
    if (selectElement) {
        selectedValue = row.querySelector('#cstCofins').innerText.trim();
        optionToSelect = selectElement.querySelector(`option[value="${selectedValue}"]`);
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
    document.getElementById('m_bcCofins').value = row.querySelector('#bcCofins').innerText;
    document.getElementById('m_aliqCofins').value = row.querySelector('#aliqCofins').innerText;
    document.getElementById('m_valorCofins').value = row.querySelector('#valorCofins').innerText;

    // Limpar dados existentes da modal
    // document.getElementById('nome-produto').textContent = '';
    // document.getElementById('preco-produto').textContent = '';
     
    // Mostrar modal
    var myModal = document.getElementById('produtoModal');
    $(myModal).modal('show');
}


function updateProduct(idProd){
     
    // teste cod produto
    if(!document.querySelector("#m_codProduto")){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Código do produto não localizado!",
            confirmButtonColor: "#d33"
        });
        return false;   
    }
    // testa id nota fiscal
    if(!document.querySelector('[name="id"]')){
        console.log("ID da nota fiscal não localizado, input -> document.querySelector([name='id']");
        return false;
    }
    // descricao produto
    if(!document.querySelector("#m_produto")){
        console.log("Descrição do item não localizado, input -> document.querySelector('#m_produto')");
        return false;
    }
    // modal
    if(!document.getElementById('myTabContent')){
        console.log("Modal não localizada, seletor -> document.getElementById('myTabContent')");
        return false;
    }
    const codProduto = document.querySelector('#m_codProduto').value;
    const idNota = document.querySelector('[name="id"]').value;
    const descProduto = document.querySelector('#m_produto').value;
    const modal = document.getElementById('myTabContent');
    const inputs = modal.querySelectorAll('input');
    const selects = modal.querySelectorAll('select');

    Swal.fire({
        title: "Confirma alteração do item?",
        text: codProduto +"-"+  descProduto,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar um objeto FormData
            const formData = new FormData();

            inputs.forEach(input => {
                formData.append(input.name, input.value);
            });
            selects.forEach(select => {
                formData.append(select.name, select.value);
            });

            // Adicionar outros campos fixos ao objeto FormData
            formData.append('mod', 'est');
            formData.append('form', 'nota_fiscal_xml');
            formData.append('submenu', 'updateProduto');
            formData.append('id', idNota);

            // Enviar os dados via AJAX com fetch
            fetch(document.URL, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                 
                //fecha modal produto
                var myModal = document.getElementById('produtoModal');
                $(myModal).modal('hide');
                // buscas elemento dentro da do retorno
                var resultProd = $('<div />').append(html).find('#return_alter_product');

                if(resultProd[0].value == "true"){
                    var divAllNfe = $('<div />').append(html).find('#AllNfe').html();
                    console.log(divAllNfe)
                    // aplica o a resposta ao html
                    $("#AllNfe").html(divAllNfe);
                    //aplicando a mascara de data
                    $(document).ready(function(){
                        $('.masked-date').inputmask('dd/mm/yyyy', { placeholder: 'dd/mm/yyyy' });
                    });

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Dados alterados!",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }else{
                    var resultDecode = JSON.parse(resultProd[0].value);
                    console.log(resultDecode.erro);
                    Swal.fire({
                        icon: "error",
                        confirmButtonColor: "#d33",
                        title: "Oops...",
                        text: "Erro ao atualizar item, entre em contato com o suporte!",
                      });
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    });
}

function submitValidarNf(id) {
     

    let params = {
        'submenu': 'validaNf',
        'opcao': 'blank',
        'mod' : 'est',
        'form' : 'nota_fiscal_xml',
        'id': id
    }

    let ajaxOptions = {
        type: "POST",
        url: document.URL,
        data: params,
        dataType: "text",
        success: function(response){
             
            $response = response;
            var divAllNfe = $('<div />').append(response).find('#AllNfe').html();
            console.log(divAllNfe)
            // aplica o a resposta ao html
            $("#AllNfe").html(divAllNfe);
            //aplicando a mascara de data
            $(document).ready(function(){
                $('.masked-date').inputmask('dd/mm/yyyy', { placeholder: 'dd/mm/yyyy' });
            });
        },
        error: function(error){
             
            console.log(error);
        }
    };

    // Fazer a solicitação AJAX
    $.ajax(ajaxOptions);
}

function buttonOpen(path,form,mod,submenu,varControle,param){
     
    pag = path + "/index.php?mod="+mod+"&form="+form+"&submenu="+submenu+"&"+varControle+"="+param;
    window.open(pag, 'toolbar=no,location=no,menubar=no,scrollbars=yes');
}



