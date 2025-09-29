function bloquearRecarregamento(event) {
    // Verifica se a tecla pressionada é F5 ou se a combinação Ctrl+R foi usada
    if ((event.key === 'F5' || (event.ctrlKey && event.key === 'r')) || (event.key === 'F5' || (event.ctrlKey && event.key === 'R'))) {
      // Impede o comportamento padrão de recarregar a página
      event.preventDefault();
      // Exibe uma mensagem de aviso (opcional)
       swal.fire({
        title:"Atenção!",
        text:"Recarregamento da página desativado!",
        icon:"warning"});

    }
}

// Adiciona um ouvinte de eventos para a tecla pressionada
document.addEventListener('keydown', bloquearRecarregamento);

//funcao para criar as th da tabela de validacao ao visualizar o xml
window.onload = function() {
    let novaLinha = '';
    const tabelaDivergencia = document.getElementById('tableDisagreements');

    if(tabelaDivergencia){
        //se existir essa td é sinal que a nota fiscal foi emitida e nao podemos gerar as th
        let existeNotaFiscal = document.getElementsByName('existeNotaFiscal');
        
        if(!existeNotaFiscal){
        
            //verifica se existe botao para adicionar os titulos das colunas
            const btnForn = document.getElementById('submitFornecedor');
            if(!btnForn){
                novaLinha = tabelaDivergencia.insertRow(1);
            }else{
                novaLinha = tabelaDivergencia.insertRow(2);
            }
        
            //insere novas colunas
            const novaCelula1 = novaLinha.insertCell();
            novaCelula1.textContent = 'Desccrição';
        
            const novaCelula2 = novaLinha.insertCell();
            novaCelula2.textContent = 'Código';
        
            const novaCelula3 = novaLinha.insertCell();
            novaCelula3.textContent = 'Código barras';
        
            const novaCelula4 = novaLinha.insertCell();
            novaCelula4.textContent = 'Ação';
            
            //aplica o css de estilizacao nas colunas
            novaCelula1.style.fontWeight = 'bold';
            novaCelula2.style.fontWeight = 'bold';
            novaCelula3.style.fontWeight = 'bold';
            novaCelula4.style.fontWeight = 'bold';
        
            novaCelula1.style.fontSize = '14px';
            novaCelula2.style.fontSize = '14px';
            novaCelula3.style.fontSize = '14px';
            novaCelula4.style.fontSize = '14px';
            
            novaCelula2.style.textAlign = 'center';
            novaCelula3.style.textAlign = 'center';
            novaCelula4.style.textAlign = 'center';
        }
    }
    
    if(document.getElementById('xml_arq').value !== ''){
    
        var chevron = document.getElementsByName("btnCollapse")[0];
        if (chevron) {
            chevron.click();
        }
    }

     
    var botaoCadastrar = document.getElementById('bnt_cadastrar');
    var tableDisagreements = document.getElementById('tableDisagreements');

    if (tableDisagreements) {
        if (botaoCadastrar.style.display === 'none' || botaoCadastrar.style.display === '') {
            botaoCadastrar.style.display = 'none';
        }
    } else {
        if(document.getElementById('xml_arq').value !== '' && botaoCadastrar.style.display === ''){
            botaoCadastrar.style.display = 'block';
        }else{
            botaoCadastrar.style.display = 'none';
        }
    }
    

}

function submitCobranca() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'cobranca';
    f.submit();
}

// desenha Cadastro
function submitGerarFinanceiro() {
     
    f = document.lancamento;
    f.mod.value = "est";
    f.form.value = "nota_xml_importa";

    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    condPagamentosDesc = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
                condPagamentosDesc = condPgto[i].text;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }

    f.letra.value = f.numero.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        centroCustos + "|" + generos + "|" + condPagamentos;


    var rows = document.getElementById("datatable-buttons-1").getElementsByTagName("tr");

    var $dadosFinanceiros = "";
    var $totalFinanceiro = 0;

    for (row = 1; row < rows.length; row++) {

        var cells = rows[row].getElementsByTagName("td");
        var field0 = cells[0].childNodes[0].data;
        var field1 = cells[1].childNodes[1].value;
        var field2 = cells[2].childNodes[1].value;
        var field3 = cells[3].childNodes[1].value;
        var field4 = cells[4].childNodes[1].value;
        var field5 = cells[5].childNodes[1].value;
        var field6 = cells[6].childNodes[1].value;
        

        // OLD
        // var $moeda = (field2).toString();
        // //$moeda = $moeda.replace(".", "");
        // $moeda = $moeda.replace(",", ".");
        // $moeda = parseFloat($moeda);

        //NEW 31-AGOSTO-2023
        var $moeda = formatarNumero(field2);

        $totalFinanceiro = $totalFinanceiro + $moeda;

        $dadosFinanceiros = $dadosFinanceiros + "|" + field0 + "*" +
            field1 + "*" + $moeda + "*" + field3 + "*" +
            field4 + "*" + field5 + "*" + field6;

    }

    $totalFinanceiro = $totalFinanceiro.toFixed(2);

    f.dadosFinanceiros.value = $dadosFinanceiros;

    var $total = f.total.value;

    //$total = $total.replace(".", "");

    $total = $total.replace(",", ".");

    $total = parseFloat($total);

    if ($total != $totalFinanceiro) {
        alert('Soma total das parcelas, não é igual ao total da fatura!');
    } else {
        if (confirm('Deseja realmente INCLUIR FATURAMENTO') == true) {
            f.submenu.value = 'gerarfinanceiro';
        }
        else {
            f.submenu.value = '';
        }
        f.submit();
    }

}

function formatarNumero(numero) {
    return parseFloat(numero.replace('.', '').replace(',', '.'));
}

  
function submitAtualPedidoCondPG(adicionar, numParcelaAdd) {
     
    f = document.lancamento;
    if (adicionar == "S") {
        if ((numParcelaAdd + 1) < 0) {
            f.numParcelaAdd.value = 0;
        } else {
            f.numParcelaAdd.value = numParcelaAdd + 1;
        }
    } else {
        f.numParcelaAdd.value = 0;
    }

    //NATURAZA DE OPERACAO
    first = true;
    naturaDeOperacoes = '';
    for (var i = 0; i < idNatop.options.length; i++) {
        if (idNatop[i].selected == true) {
            if (first == true) {
                first = false;
                naturaDeOperacoes = idNatop[i].value;
            }
            else naturaDeOperacoes = naturaDeOperacoes + "," + idNatop[i].value;
        }
    }

    // COND PAGAMENTO
    first = true;
    condPagamentos = '';
    for (var i = 0; i < condPgto.options.length; i++) {
        if (condPgto[i].selected == true) {
            if (first == true) {
                first = false;
                condPagamentos = condPgto[i].value;
            }
            else condPagamentos = condPagamentos + "," + condPgto[i].value;
        }
    }


    //CENTRO DE CUSTO
    first = true;
    centroCustos = '';
    for (var i = 0; i < centroCusto.options.length; i++) {
        if (centroCusto[i].selected == true) {
            if (first == true) {
                first = false;
                centroCustos = centroCusto[i].value;
            }
            else centroCustos = centroCustos + "," + centroCusto[i].value;
        }
    }

    // GENERO
    first = true;
    generos = '';
    for (var i = 0; i < genero.options.length; i++) {
        if (genero[i].selected == true) {
            if (first == true) {
                first = false;
                generos = genero[i].value;
            }
            else generos = generos + "," + genero[i].value;
        }
    }
    f.letra.value = f.numero.value + "|" + f.data.value + "|" +
        f.total.value + "|" + f.fornecedor.value + "|" + f.serie.value + "|" +
        naturaDeOperacoes + "|" + condPagamentos + "|" + centroCustos + "|" + generos;

    f.submenu.value = 'condpg';
    f.submit();
}

function submitConfirmar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'enviar';
    f.submit();
} // fim submitVoltar

function submitVoltar() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = '';ad
    f.submit();
} // fim submitVoltar

function submitVoltarFinanceiro() {
     
     swal.fire({
        title: "Atenção!",
        text: "Nota fiscal já processada, deseja cancelar o financeiro e voltar?",
        icon: "warning",
        buttons: ["Cancelar","Sim"],
    })
    .then((yes) => {
         
        if (yes) {
            
            f = document.lancamento;
            f.opcao.value = '';
            f.submenu.value = '';
            f.submit();

        } else {
            return false
        }
    });

} // fim submitVoltar

// mostra Cadastro
function submitPesquisa() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'pesquisa';
    f.submit();
}

// mostra Nota Fiscal
function submitVisualizar() {
     
    
    f = document.upload;
    //salva descricao do xml
    const inputFile = document.getElementById('input-file');

    if(inputFile == ''){
         swal.fire({
            title:"Atenção!",
            text:"Insira um arquivo xml para visualizar!",
            icon:"warning"});
        return false;
    }

    f.opcao.value = '';
    f.submenu.value = 'mostra';
    f.submit();
}


/*function submitVisualizar() {
    
    var form = $("form[name=upload]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: form,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function (response) {
             
            console.log(response);
            var result = $('<div />').append(response).find('#demo').html();
            $("#demo").html(result);
            
        }
    });
    return false;
} */



// cadastrar Nota Fiscal
function submitCadastrar() {
     
    f = document.upload;

    if(f.submenu.value === 'entradaManifesto'){
        f.param.value = 'entradaManifesto';
    }
    
    f.opcao.value = '';
    f.submenu.value = 'cadastrar';
    f.submit();
}

// confere Fornecedor / Produtos
function submitConfere() {
    f = document.upload;
    f.opcao.value = '';
    f.submenu.value = 'conferir';
    f.submit();
}

function abrir(pag, xml) {

    window.open("../../temp/notafiscalxml.php?xml=" + xml, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=500,scrollbars=yes');
}

//function insertConta(url, windowoption, name, params)
function submitInsertJson(params) {
     
    //add esse parametro para condicionar o novo form
    params.conta.push({ campo: 'form_old', valor: 'produtoPesquisarNfe' });

    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        if (params.conta[i].campo == 'submenu') input.value = params.conta[i].valor = 'cadastrar';
        else input.value = params.conta[i].valor;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    //note I am using a post.htm page since I did not want to make double request to the page 
    //it might have some Page_Load call which might screw things up.
    window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
}

function submitSearchJson(params) {
     
    var f = document.upload;
    var url = f.url.value;
    var name = 'Cadastro';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //new param search product
    var parametroManual = document.createElement('input');
    parametroManual.type = 'hidden';
    parametroManual.name = 'param';
    parametroManual.value = 'pesquisaProdutoImportaXml';
    form.appendChild(parametroManual);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;

        if (params.conta[i].campo == 'submenu'){ 
            input.value = 'pesquisar'; 
        }
        else if (params.conta[i].campo == 'opcao') {
            input.value = 'pesquisarnfe';
        }
        else { 
            input.value = params.conta[i].valor; 
        }
        form.appendChild(input);
    }

    document.body.appendChild(form);
    //note I am using a post.htm page since I did not want to make double request to the page 
    //it might have some Page_Load call which might screw things up.
    window.open("post.html", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
}

//OLD FUNCTION ALTER TAG - 10-julho-2023 - jhon k.
// function mudaCodProdXml(nrItem) {
//     f = document.upload;

//     var xml = f.xml_arq.value;

//     prodName = "codProd"+nrItem
//     prodDefault = document.getElementsByName(prodName)[0].defaultValue;   
//     prodNew = document.getElementsByName(prodName)[0].value;  
    
//     prodOldTag = "<det nItem=\""+nrItem+"\"><prod><cProd>"+prodDefault+"</cProd>"
//     prodNewTag = "<det nItem=\""+nrItem+"\"><prod><cProd>"+prodNew+"</cProd>"

//     var xml_result = xml.replace(prodOldTag, prodNewTag);

//     f.xml_arq.value = xml_result;

// }

//NEW FUNCTION ALTER XML
function mudaCodProdXmlNew(code_new ,code_xml) {
     

    f = document.upload;
    var xml = f.xml_arq.value;
    
    let newXml = manipularXML(xml, code_xml, code_new)

    if(newXml){
        f.xml_arq.value = newXml;
    }else{
         swal.fire({
            title:"Atenção!",
            text:"Atualização do código cancelado ou ocorreu um erro ao atualizar xml!",
            icon:"warning"});
    }
    console.log(newXml)

}

function manipularXML(xml, codigoProcurado, novoCodigo) {
     
    const parser = new DOMParser();
    const xmlDoc = parser.parseFromString(xml, 'text/xml');

    const namespaceURI = xmlDoc.documentElement.namespaceURI;

    // Procura pelo código dentro da tag <cProd>
    const elementosCProd = xmlDoc.getElementsByTagName('cProd');

    for (let i = 0; i < elementosCProd.length; i++) {
        const cProd = elementosCProd[i];
        const codigoAtual = cProd.innerHTML;

        if (codigoAtual == codigoProcurado) {
            // Verificar se a tag <cProdAlter> já existe
            const cProdAlter = xmlDoc.getElementsByTagName('cProdAlter')[i];

            if (cProdAlter) {
                // A tag <cProdAlter> já existe, perguntar ao usuário se deseja sobrescrever
                const resposta = confirm('Produto já possui código alterado, deseja atualizar?');

                if (resposta) {
                    // Sobrescrever o código
                    cProdAlter.innerHTML = novoCodigo;
                } else {
                    return false;
                }
            } else {
                // A tag <cProdAlter> não existe, criar e adicionar com o novo código
                const novacProdAlter = xmlDoc.createElementNS(namespaceURI, 'cProdAlter');
                novacProdAlter.innerHTML = novoCodigo;

                // Criar a tag <prod> pai, caso não exista
                const prod = cProd.parentNode;
                if (!prod.getElementsByTagName('cProdAlter').length) {
                    prod.appendChild(novacProdAlter);
                }
            }
        }
    }

    const xmlModificado = new XMLSerializer().serializeToString(xmlDoc);

    // //DONWLOAD XML 
    // // Criar um elemento <a> para fazer o download
    // const link = document.createElement('a');
    // link.setAttribute('href', 'data:text/xml;charset=utf-8,' + encodeURIComponent(xmlModificado));
    // link.setAttribute('download', 'arquivo_alterado.xml');
    // link.style.display = 'none';
    // document.body.appendChild(link);
    
    // // Clicar no link para iniciar o download
    // link.click();
    
    // // Remover o elemento <a> após o download
    // document.body.removeChild(link);

    return xmlModificado;
}

function submitValidar() {
     debugger;
    f = document.upload;
    var xml = f.xml_arq.value;

    var tabela = document.getElementById("tableDisagreements"); //obtém a referência da tabela

    if((tabela == '' )){
        while (tabela.rows.length > 0) { //verifica se há linhas na tabela
            tabela.deleteRow(0); //remove a primeira linha da tabela
          }
    }

    if(xml){
        //ajax responsavel por enviar dados ao form
        $.ajax({
            type: "POST",
            url: document.URL + "?mod=est&form=nota_xml_importa&submenu=conferirAjax&opcao=blank",
            data: {xml_arq: xml, 'submenu':'conferirAjax'},
            dataType: "json",
            success: [atualizaTabelaNotaFiscal]
        });

    }else{
         swal.fire({
            title:"Atenção!",
            text:"Xml não localizado!",
            icon:"warning"});
    }
}

 
function atualizaTabelaNotaFiscal(response, textStatus, xhr) {
    console.log(response)
     


    //logica para recuperar o header para verificar se existe nota fiscal e habilitar botao cadastrar
    var headersStr = xhr.getAllResponseHeaders();
    var headersArr = headersStr.trim().split('\r\n');

    var headersObj = {};

    headersArr.forEach(function (header) {
        var separatorIndex = header.indexOf(':');
        var key = header.slice(0, separatorIndex).trim();
        var value = header.slice(separatorIndex + 1).trim();
        headersObj[key] = value;
    });

    if(response){
        var tableDisagreements = $("<table />").append(response).find("#tableDisagreements").html();

        if(tableDisagreements){ 
            
            //verifica se a tabela esta na tela
            const tabelaDivergencia = document.getElementById('tableDisagreements');
            if(!tabelaDivergencia && tabelaDivergencia === null){
                // Cria a tabela conforme o código fornecido
                const criatabelaDivergencia = CriaTabelaDisagreements();
                
                // Obtém a referência à div de cabeçalho
                const divCabecalho = document.getElementById('cabecalho'); // Certifique-se de ajustar o ID conforme o seu HTML

                // Insere a tabela logo após a div de cabeçalho
                divCabecalho.insertAdjacentElement('afterend', criatabelaDivergencia);
            }

            $("#tableDisagreements").html(tableDisagreements);


        } else { //se exitir divergencias habilita botao cadastrar

            if (!headersObj.existenotafiscal) {

                var botaoCadastrar = document.getElementById('bnt_cadastrar');
                if (botaoCadastrar.style.display === 'none' || botaoCadastrar.style.display === '') {
                    botaoCadastrar.style.display = 'block';
                }
            }

            // Verifica se a tabela existe no conteúdo HTML
            if ($("#tableDisagreements").length > 0) {
                // Remove a tabela caso ela exista
                $("#tableDisagreements").remove();
            }
        }

        var tableItemns = $("<div />").append(response).find("#tableItemns").html();
        $("#tableItemns").html(tableItemns);

        // Se existir essa td é sinal que a nota fiscal foi dado entrada
        var existeNotaFiscal = document.getElementById('existeNotaFiscal');
        if (!existeNotaFiscal) {
            CriaThTabela(response);
        }
    }else{
         swal.fire({
            title:"Sucesso",
            text:"Xml sem divergências!",
            icon:"success"});
    }
}

//funcao para criar as th da tabela ao validar os dados 
function CriaThTabela(response){
     
    let novaLinha = '';
    const tabelaDivergencia = document.getElementById('tableDisagreements');

    if(!tabelaDivergencia && tabelaDivergencia === null){
        CriaTabelaDisagreements();
    }else{
        
        //varifica se existe botao para adicionar os titulos das colunas
        const btnForn = document.getElementById('submitFornecedor');

        if(!btnForn){
            novaLinha = tabelaDivergencia.insertRow(1);
        }else{
            novaLinha = tabelaDivergencia.insertRow(2);
        }      
    }
}

function CriaTabelaDisagreements() {
    const tabelaDivergencia = document.createElement('table');
    tabelaDivergencia.id = 'tableDisagreements';
    tabelaDivergencia.className = 'table tableProd table-bordered';
    tabelaDivergencia.width = '100%';
    tabelaDivergencia.style.borderRadius = '8px';
    tabelaDivergencia.style.borderCollapse = 'inherit';

    const divDivergencia = document.createElement('td');
    divDivergencia.id = 'divergencia';
    divDivergencia.colSpan = 4;
    divDivergencia.align = 'center';
    divDivergencia.innerHTML = "<h5>Divergências !</h5>";

    const linhaDiv = tabelaDivergencia.insertRow();
    linhaDiv.appendChild(divDivergencia);

    return tabelaDivergencia;
}


function submitAddXml(){
     
    
    //verifica se existe a var xml_arq esta vazia e enviar o evento de clique para esconder os inputs
    if(document.getElementById('xml_arq').value !== ''){
        document.getElementById('xml_arq').value = '';
        var chevron = document.getElementsByName("btnCollapse")[0];
        chevron.click();
    }

    //limpa as tables da tela  tableItemns  legendas
    var formulario = document.querySelectorAll('table');
    formulario[0].innerHTML = '';
    formulario[1].innerHTML = '';
    formulario[2].innerHTML = '';
    formulario[3].innerHTML = '';
    formulario[4].innerHTML = '';
    formulario[5].innerHTML = '';
    formulario[6].innerHTML = '';
    formulario[7].innerHTML = '';
    formulario[8].innerHTML = '';
    formulario[9].innerHTML = '';
    //formulario[10].innerHTML = '';
    //formulario[11].innerHTML = '';

    //remove bts and tables
    if ($("#btnAddXml").length > 0) {
        // Remove a tabela caso ela exista
        $("#btnAddXml").remove();
    }

    if ($("#btnValidar").length > 0) {
        // Remove a tabela caso ela exista
        $("#btnValidar").remove();
    }

    if ($("#bnt_cadastrar").length > 0) {
        // Remove a tabela caso ela exista
        $("#bnt_cadastrar").remove();
    }

    if ($("#informacoesComplementares").length > 0) {
        // Remove a tabela caso ela exista
        $("#informacoesComplementares").remove();
    }

    if ($("#legendas").length > 0) {
        // Remove a tabela caso ela exista
        $("#legendas").remove();
    }

    if ($("#tableItemns").length > 0) {
        // Remove a tabela caso ela exista
        $("#tableItemns").remove();
    }
    
}
