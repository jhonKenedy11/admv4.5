document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.key == "Enter") {
        submitLetraPesquisa();
    }// fim evento enter
});


//loops through all buttons and all three equivalents to style buttons that contain equivalents 
document.addEventListener("DOMContentLoaded", function() {
     
    var buttons = document.querySelectorAll(".toggle-equivalent");
    var equivalenteCodes = document.querySelectorAll("#equivalent-codes");
    
    for (var i = 0; i < buttons.length; i++) {
        var button = buttons[i];
        var codigo = button.getAttribute("data-codigo");
        
        for (var j = 0; j < equivalenteCodes.length; j++) {
            var equivalenteCode = equivalenteCodes[j];
            var codigoEqui = equivalenteCode.getAttribute("data-codigo");
            
            if (codigo == codigoEqui) {
                button.disabled = false;
                button.style.background = '#19b17c66';
                button.style.borderRadius = "3px";
                button.style.borderWidth = "1px";
                button.style.borderColor = "#19b17c66";
                button.style.pointerEvents = "all";
                button.innerText = 'Mostrar Equivalente';

                break;
            }
        }
    }

    //atualiza label carrinho
    
    if(document.getElementById('carrinho') && document.getElementById('carrinho').value !== "" &&
        document.getElementById('carrinho').value !== "[]" &&
        document.getElementById('carrinho').value !== null){

        let cart   = document.getElementById('carrinho').value;
        //substitui crase por aspas dupla
        cart = cart.replace(/`/g, '"')
        //trasforma em obejto
        carTemp = JSON.parse(cart);
        document.querySelector('.quantCart').innerHTML = carTemp.length;
        document.querySelector('.quantCart').style.backgroundColor = '#e12222'; 
    }else{
        if(document.getElementById('btnCart')){
            document.getElementById('btnCart').disabled = true;
            document.querySelector('.quantCart').style.backgroundColor = '#999999'; 
        }

    }
});


function mostrarTRs(id, elemento) {
    // Seleciona todas as <tr> com a classe 'equivalent-codes' com o ID especificado
    var trElements = document.getElementsByClassName('equivalent-table-' + id);
  
    // Altera o atributo CSS 'display' para 'table-row' em todas as <tr> selecionadas
    for (var i = 0; i < trElements.length; i++) {
      var trElement = trElements[i];
      if (trElement.style.display === 'table-row') {
        trElement.style.display = 'none';
        elemento.innerText = 'Mostrar Equivalente';
      } else {
        trElement.style.display = 'table-row';
        elemento.innerText = 'Ocultar Equivalente';
      }
  
    }
}


function currencyFormat(num) {
    return num
        .toFixed(2) // always two decimal digits
        .replace(".", ",") // replace decimal point character with ,
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

function calculaTotal() {
    var f = document.lancamento;
    var custo = f.custoCompra.value;
    var medio = f.custoMedio.value;
    var reposicao = f.custoReposicao.value;
    var informado = f.precoInformado.value;
    var base = f.precoBase.value;
    var perc = f.percCalculo.value;
    var total = 0;

    if(custo === "NaN" || custo === Infinity || custo === undefined ||custo === ''){
        custo = '0,00';
    }
    if(medio === "NaN" || medio === Infinity || medio === undefined ||medio === ''){
        medio = '0,00';
    }
    if(reposicao === "NaN" || reposicao === Infinity || reposicao === undefined || reposicao === ''){
        reposicao = '0,00';
    }
    if(informado === "NaN" || informado === Infinity || informado === undefined || informado === ''){
        informado = '0,00';
    }
    if(perc === "NaN" || perc === Infinity || perc === undefined || perc === ''){
        perc = '0,00';
    }

    custo = parseFloat(custo.replace(".", "").replace(",", "."));
    medio = parseFloat(medio.replace(".", "").replace(",", "."));
    reposicao = parseFloat(reposicao.replace(".", "").replace(",", "."));
    informado = parseFloat(informado.replace(".", "").replace(",", "."));
    perc = parseFloat(perc.replace(".", "").replace(",", "."));
    switch (base) {
        case "C":
            var total = custo + ((custo * perc) / 100);
            break;
        case "M":
            var total = medio + ((medio * perc) / 100);
            break;
        case "R":
            var total = reposicao + ((reposicao * perc) / 100);
            break;
        case "I":
            var total = informado + ((informado * perc) / 100);
            break;
        default:
            var total = 0
    }   

    result = currencyFormat(total);
    if (result === "NaN"){
        f.venda.value = 0;
    }else if(result === "Infinity"){
        f.venda.value = 0;
    }else{
        f.venda.value = currencyFormat(total);
    }
}

function calculaPerc() {
    var f = document.lancamento;
    var custo = f.custoCompra.value;
    var medio = f.custoMedio.value;
    var reposicao = f.custoReposicao.value;
    var informado = f.precoInformado.value;
    var base = f.precoBase.value;
    var perc = f.percCalculo.value;
    var total = 0;
    custo = parseFloat(custo.replace(".", "").replace(",", "."));
    medio = parseFloat(medio.replace(".", "").replace(",", "."));
    reposicao = parseFloat(reposicao.replace(".", "").replace(",", "."));
    informado = parseFloat(informado.replace(".", "").replace(",", "."));
    perc = parseFloat(perc.replace(".", "").replace(",", "."));
    total = f.venda.value;
    total = parseFloat(total.replace(".", "").replace(",", "."));
    switch (base) {
        case "C":
            var perc = ((total - custo) / custo) * 100;
            break;
        case "M":
            var perc = ((total - medio) / medio) * 100;
            break;
        case "R":
            var perc = ((total - reposicao) / reposicao) * 100;
            break;
        case "I":
            var perc = ((total - informado) / informado) * 100;
            break;
        default:
            var total = 0
    }
    result = currencyFormat(perc);
    if (result === "NaN"){
        f.percCalculo.value = 0;
    }else if(result === "Infinity"){
        f.percCalculo.value = 0;
    }else{
        f.percCalculo.value = currencyFormat(perc);
    }
}

function submitVoltar(formulario) {
    f = document.lancamento;
    f.opcao.value = formulario;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar



async function pesquisarEndereco(cep) {
     
    try {
        const cepSemMascara = cep.replace(/\D/g, '');
        const validacep = /^[0-9]{8}$/;

        if (!validacep.test(cepSemMascara)) {
            throw new Error('Formato de CEP inválido.');
        }

        const response = await fetch(`//viacep.com.br/ws/${cepSemMascara}/json/`);
        const data = await response.json();

        if (data.erro) {
            throw new Error('CEP não encontrado.');
        }

        return data;
    } catch (error) {
        console.error(error);
        throw error;
    }
}




async function submitConfirmar(formulario) {
    f = document.lancamento;
    
    //VALIDA DADOS FORM
    if (f.tribIcms.value == "") {
        await Swal.fire({ 
            text: 'Selecione a tributação ICMS do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }
    
    if (f.origem.value == "") {
        await Swal.fire({ 
            text: 'Digite a origem do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }
    
    if (f.uni.value == "") {
        await Swal.fire({ 
            text: 'Digite a unidade do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }
    
    if (f.ncm.value == "") {
        await Swal.fire({ 
            text: 'Digite a NCM do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }
    
    if (f.desc.value == "") {
        await Swal.fire({ 
            text: 'Digite a descrição do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }
    
    if (f.pessoa.value == "") {
        await Swal.fire({ 
            text: 'Selecione o fabricante do produto!',
            title: 'Atenção!',
            icon: 'warning',
            dangerMode: true 
        });
        return false;
    }

    try {
        let codigo = f.codFabricante.value;
        const response = await fetch(document.URL + "?mod=est&form=produto&submenu=pesquisaCodigo&opcao=blank&pesquisaCodigo=" + codigo);
        const data = await response.json();
    
        if (data !== 'false') {
            // Verifica se é alteração ou inclusão
            const isAlteracao = f.submenu.value === "alterar";
            
            const result = await Swal.fire({
                title: "Atenção!",
                text: isAlteracao ? 
                    "Deseja realmente alterar este produto?" : 
                    "Código fabricante já cadastrado! Deseja cadastrar com o mesmo código?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: isAlteracao ? "Sim, alterar" : "Sim, cadastrar",
                cancelButtonText: "Cancelar"
            });
           
            if (result.isConfirmed) {
                f.submenu.value = (f.submenu.value === "cadastrar") ? "inclui" : "altera";
                if (f.submenu.value === "inclui") f.opcao.value = formulario;
                f.submit();
            }
            return false;
        } else {
            // Se não existe o código, mostra confirmação normal
            const isAlteracao = f.submenu.value === "alterar";
            const result = await Swal.fire({
                title: "Confirmação",
                text: isAlteracao ? 
                    "Deseja realmente alterar este produto?" : 
                    "Deseja realmente cadastrar este produto?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: isAlteracao ? "Sim, alterar" : "Sim, cadastrar",
                cancelButtonText: "Cancelar"
            });

            if (result.isConfirmed) {
                f.submenu.value = (f.submenu.value === "cadastrar") ? "inclui" : "altera";
                f.submit();
            }
        }
    } catch(error) {
        debugger
        console.error(error);
        await Swal.fire({
            title: "Erro!",
            text: "Ocorreu um erro ao processar sua solicitação.",
            icon: "error"
        });
    }
}

//REPAROS
function submitConfirmarReparo() {
     
    f = document.lancamento;

    if (f.submenu.value == "cadastrar") {
         swal.fire({
            title: "Atenção!",
            text: "Inclua o produto antes de cadastrar reparos!",
            icon: "warning",
            button: "OK",
            dangerMode: true
        });

        return false;
    } 
  
    if (f.reparoCodProduto.value == "") {
         swal.fire({
            title: "Atenção!",
            text: "Inclua o produto antes de cadastrar reparos!",
            icon: "warning",
            button: "OK",
            dangerMode: true
        });

        return false;
    }

    if (f.reparoQuant.value == "" || f.reparoQuant.value == "0,00") {
         swal.fire({
            title: "Atenção!",
            text: "Prencha o campo quantidade para cadastrar reparos!",
            icon: "warning",
            button: "OK",
            dangerMode: true
        });
        f.reparoQuant.focus();
        return false;
    } 

     swal.fire({
        title: "Atenção!",
        text: "Deseja cadastrar este item no kit de reparos",
        icon: "warning",
        buttons: {
            btn_cancelar: {
                text: "Cancelar",
                value: '0',
            },
            btn_cadastrar: {
                text: "Cadastrar",
                value: "1",
            }
        }
    })
        .then((val) => {
             
            if (val == '1') {//insert new

                f.submenu.value = 'incluiReparo';
                f.submit();

            } else if (val == '0') {//cancel
                return false;
            } else {
                return false;
            }

        });//Fim  swal.fire
} // fim submitConfirmarReparo

function submitExcluirReparo(id) {

     swal.fire({
        title: "Atenção!",
        text: "Deseja realmente excluir este item do kit reparo?",
        icon: "warning",
        buttons: {
            btn_cancelar: {
                text: "Cancelar",
                value: '0',
            },
            btn_cadastrar: {
                text: "Excluir",
                value: "1",
            }
        }
    })
    .then((val) => {
         
        if (val == '1') {
            f = document.lancamento;
            f.opcao.value = '';
            f.submenu.value = 'excluiReparo';
            f.idReparo.value = id;
            f.submit();
        } else if (val == '0') {//cancel
            return false;
        } else {
            return false;
        }

    });//Fim  swal.fire
}

function submitClear(){
    document.getElementById('reparoCodProduto').value = '';
    document.getElementById('reparoProdDesc').value = '';
    document.getElementById('reparoCodFabricante').value = '';
}


//EQUIVALENCIA            
async function submitConfirmarEquivalencia() {
    f = document.lancamento;

    if (f.submenu.value == "cadastrar"){
         swal.fire({ text: 'Inclua o produto antes de cadastrar equivalências!', title: 'Atenção!', icon: 'warning', dangerMode: true });
    }else{
        try{
             
            let codigo  = f.codEquivalente.value;
            const response = await fetch(document.URL + "?mod=est&form=produto&submenu=pesquisaCodigo&opcao=blank&pesquisaCodigo=" + codigo); 
            const data = await response.json();
            if(data === 'false'){
                if (confirm('Deseja cadastrar códico equivalencia deste item') == true) {
                    f.submenu.value = 'incluiequivalencia';
                    f.submit();
                }else{
                    return false;
                }
            }else{

                 swal.fire({
                    title: "Atenção!",
                    text: "Código equivalente já cadastrado no item " + data,
                    icon: "warning",
                    buttons: {
                        btn_cancelar: {
                            text: "Cancelar",
                            value: '0',
                        },
                        btn_cadastrar: {
                            text: "Cadastrar com o mesmo código",
                            value: "1",
                        }
                    }
                })
                .then((val) => {
                     
                    if(val == '1'){//insert new

                        f.submenu.value = 'incluiequivalencia';
                        f.submit(); 

                    }else if(val == '0'){//cancel
                        return false;
                    }else{
                        return false;
                    }

                });//Fim  swal.fire

            } //Fim if(data === 'false')

        }catch{
            console.error(error);
            throw error;
        } //Fim try

    } //Fim if (f.submenu.value == "cadastrar")

} // Fim submitConfirmar

function submitExcluirEquivalencia(id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.opcao.value = '';
        f.submenu.value = 'excluiequivalencia';
        f.idEquiv.value = id;
        f.submit();
    }
}

// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    //   f.desc.value = "";
    f.submit();
}

function submitAlterar(produto_id) {
    f = document.lancamento;
    f.opcao.value = '';
    f.submenu.value = 'alterar';
    f.id.value = produto_id;
    f.submit();

}

async function submitExcluir(produto_id) {
    const result = await Swal.fire({
        title: "Atenção!",
        text: "Deseja realmente excluir este produto?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, excluir",
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        f = document.lancamento;
        f.opcao.value = '';
        f.submenu.value = 'exclui';
        f.id.value = produto_id;
        f.submit();
    }
}

function montaLetra() {
    
    l = document.lancamento;
    var quant = document.getElementsByName('quant');
    var fora = document.getElementsByName('fora');
    var valueQuant = "F";
    if (quant[0].checked) {
        valueQuant = "T";
    }
    var valueFora = "F";
    if (fora[0].checked) {
        valueFora = "T";
    }
    l.letra.value = l.produtoNome.value + "|" + l.grupo.value + "|" + l.codFabricante.value + "|" + l.localizacao.value + "|" + valueQuant + "|" + valueFora;
}// submitLetra


// function submitLetra() {
//     
//     f = document.lancamento;
//     var quant = document.getElementsByName('quant');
//     var fora = document.getElementsByName('fora');
//     if ((f.codFabricante.value == '') && (f.produtoNome.value == '') && (f.grupo.value == '') && (f.localizacao.value == '') && (!quant[0].checked) && (!fora[0].checked)) {
//         alert('Digite algum Filtro de pesquisa.');
//     } else {
//         f.submenu.value = 'letra';
//         montaLetra();
//         f.submit();
//     }
// }

function submitLetra() {
    
    f = document.lancamento;
    var quant = document.getElementsByName('quant');
    var fora = document.getElementsByName('fora');

    if ((f.codFabricante.value == '') && (f.produtoNome.value == '') && (f.grupo.value == '') && (f.localizacao.value == '') && (!quant[0].checked) && (!fora[0].checked)) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Digite algum Filtro de pesquisa.',
            confirmButtonText: 'OK'
        });
        return false;
    } else {
        Swal.fire({
            title: 'Carregando...',
            text: 'Aguarde enquanto os dados são processados.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        f.submenu.value = 'letra';
        montaLetra();
        f.submit();
    }
}

function submitLetraPesquisa(codigo = null, codFabricante = null, checkbox = '') {
    
    f = document.lancamento;

    if ((codigo == null) && (f.codFabricante.value == '') && (f.produtoNome.value == '') && (f.grupo.value == '') && (f.localizacao.value == '')) {
        alert('Digite algum Filtro de pesquisa.');
    } else {
        f.submenu.value = 'letra';
        var valueQuant = "F";
        var valueFora = "F";

        if (codigo != null) {
            f.codigo.value = codigo;
        }

        if (codFabricante != null) {
            f.codFabricante.value = codFabricante;
        }
        if(checkbox == 'true'){
            $check = document.getElementById('pedidoChecked').checked;
            f.checkbox.value = $check;
        }else{
            if (checkbox != ''){
                f.checkbox.value = false;}
        }
        

        f.letra.value = f.produtoNome.value + "|" + f.grupo.value + "|" + f.codFabricante.value + "|" + f.localizacao.value + "|" + valueQuant + "|" + valueFora;
        f.submit();
    }
}

function submitAjustaEstoque() {
    f = document.lancamento;
    if ((f.quantNova.value == "") || (f.quantNova.value == 0)) {
        alert("Digite a NOVA Quantidade a ser ajustada para o produto.");
    } else {
        if (confirm('Deseja ajustar a quantidade') == true) {
            f.mod.value = 'est';
            f.form.value = 'produto';
            f.submenu.value = 'ajustaestoque';
        }
    }
    f.submit();
}


function consultaPrint(form) {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'est';
    g.form.value = 'produto';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}


function calculaTotalProdutoAtendimento(codigo){
     
    var unitarioId = "unitario"+codigo;
    var quantId = "quant"+codigo;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;

    quantValue = document.getElementsByName(quantId)[0].value;

    //unitarioValue = unitarioProduto;

    quantValue          = parseFloat(quantValue.replace(".", "").replace(",", "."));
    vendaValue      = parseFloat(vendaValue.replace(".", "").replace(",", "."));

    totalItem  = (vendaValue * quantValue);
    total = currencyFormat(totalItem);

    if (total === "NaN" || total === "NaN"){
        document.getElementById(quantId).value = 0;
       
    }else if(total === "Infinity" || total === "Infinity"){
        document.getElementById(quantId).value = 0;
    }else{
        document.getElementById(unitarioId).value = total;
    }
}

function calculaPercProdutoAtendimento(codigo, campo) {
     
    var f = document.lancamento;    

    var quantId = "quant"+codigo;
    quantValue = document.getElementsByName(quantId)[0].value;

    var unitarioId = "unitario"+codigo;
    unitarioValue = document.getElementsByName(unitarioId)[0].value;

    var percDescontoItemId = "percDescontoItem"+codigo;
    percDescontoItemValue = document.getElementsByName(percDescontoItemId)[0].value;

    var descontoItemId = "descontoItem"+codigo;
    descontoItemValue = document.getElementsByName(descontoItemId)[0].value;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;
    
  
    descontoItemValue      = parseFloat(descontoItemValue.replace(".", "").replace(",", "."));
    percDescontoItemValue  = parseFloat(percDescontoItemValue.replace(".", "").replace(",", "."));
    quantValue          = parseFloat(quantValue.replace(".", "").replace(",", "."));
    vendaValue   = parseFloat(vendaValue.replace(".", "").replace(",", "."));
  
    totalItem  = (vendaValue * quantValue);
  
    if(campo == 'desconto'){
        percDescontoItemValue = ((descontoItemValue * 100)/totalItem)
    }else{
        descontoItemValue = ((totalItem*percDescontoItemValue)/100)
  
    }
    resultTotal = (totalItem - descontoItemValue);
    resultPerc = currencyFormat(percDescontoItemValue);
    resultDesc = currencyFormat(descontoItemValue);
    total = currencyFormat(resultTotal);
  
  
    if (resultPerc === "NaN" || resultDesc === "NaN"){
        document.getElementById(descontoItemId).value = 0;
        document.getElementById(percDescontoItemId).value = 0;
       
    }else if(resultPerc === "Infinity" || resultDesc === "Infinity"){
        document.getElementById(descontoItemId).value = 0;
        document.getElementById(percDescontoItemId).value = 0;
    }else{
        document.getElementById(descontoItemId).value = resultDesc;
        document.getElementById(percDescontoItemId).value = resultPerc;
        document.getElementById(unitarioId).value = total;
    }
  }

//function insertConta(url, windowoption, name, params)
function submitInsertProdutoJson(params) {
    var f = document.upload;
    var url = f.url.value;
    var name = 'Produtos';
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", name);

    //Iterando json
    for (var i = 0, j = params.conta.length; i < j; i++) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = params.conta[i].campo;
        input.value = params.conta[i].valor;
        form.appendChild(input);
    }

    document.body.appendChild(form);

    //note I am using a post.htm page since I did not want to make double request to the page
    //it might have some Page_Load call which might screw things up.
    window.open("post.htm", name, 'toolbar=no,location=no,menubar=no,width=1150,height=650,scrollbars=yes');

    form.submit();

    document.body.removeChild(form);
}


function submitLetraModal() {
    if (document.lancamento.desc.value == '') {
        alert('Preencha o campo para a pesquisa.');
        return false;
    }


    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {

            xhr.setRequestHeader("Ajax-Request", "true");
        },
        success: function (response) {

            var result = $('<div />').append(response).find('#datatable').html();
            $("#datatable").html(result);
        }
    });
    return false;

}

function setaDadosPedido() {
    //validar de selecionou cliente e cond de pagamento..
    // limpar dados do form antes de abrir
    document.lancamento.desc.value = '';

}

function submitModal() {
    
    let numLinhas = document.getElementById("datatable").rows.length;
    if (numLinhas <= 1) {
        alert("Aviso! Faça a Pesquisa antes de importar dados.");
        return false
    }

    f = document.lancamento;
    f.form.value = 'produto';
    f.submenu.value = 'modal_ped_item';
    f.submit();


}

function formata_descricao(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Remove acentos
        .replace(/([^\w]+|\s+)/g, '-') // Substitui espaço e outros caracteres por hífen
        .replace(/\-\-+/g, '-')	// Substitui multiplos hífens por um único hífen
        .replace(/(^-+|-+$)/, ''); // Remove hífens extras do final ou do inicio da string
}

function submitAlterarItemTabela($id, $codigo) {
    
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'tabela_preco_item';
    f.submenu.value = 'alterar';
    f.letra.value = $id;
    f.codigo.value = $codigo;
    f.submit();
}

/**
 *  Imagem Produto
 */

function submitDestaqueImagem(idimg, destaque) {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'destaqueImagem';
    f.destaque.value = destaque;
    f.idimg.value = idimg;
    f.submit();
} // submitDestaqueImagem


function submitExcluirImagem(id, idimg) {
    
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamentoajax;
        f.mod.value = 'est';
        f.form.value = 'produto';
        f.submenu.value = 'excluiImagem';
        f.id.value = id;
        f.idimg.value = idimg;
        f.submit();
    }
} // submitExcluirImagem

function submitVoltarImagem(consulta = '') {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    if (consulta != '') {
        f.opcao.value = consulta;
    }
    f.submenu.value = '';
    f.submit();
} // fim submitVoltarImagem

// salvar imagem
function submitSalvarImagem(id) {
    f = document.lancamentoajax;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'salvarImagem';
    f.id.value = id;
    f.submit();
} // submitSalvarImagem    

// mostra Cadastro
function submitCadastrarImagem(id, titulo) {
    
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'produto';
    f.submenu.value = 'cadastrarImagem';
    f.id.value = id;
    f.tituloImg.value = titulo;
    f.submit();

} // submitCadastrarImagem


// ===========================
// FEHCA PESQUISA
// ===========================
//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechar() {
     
    window.opener.location.reload();
    window.close();
}


//fecha pesquisa de produto e atualiza campos da form que chamou
// imp = I = atualiza campos de imposto
// imp = N = não atualiza campos de imposto
function fechaProduto(imp, codigo, descProduto, unidade) {
    f = window.opener.document.lancamento;
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    f.submit;
    window.opener.location.reload();
    window.close();
}

function fechaProdutoNf(codigo, descProduto, unidade) {
    
    f = window.opener.document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_produto';
    f.opcao.value = 'incluir';
    f.submenu.value = 'calcular';
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    f.quant.value = '0,00';
    window.opener.document.getElementById("lancamento").submit();
    f.quant.focus();
    window.close();
}

//fecha pesquisa de produto e atualiza campos da form que chamou ( importa nfe )
function fechaProdutoPesquisaNfe(codigo, descProduto, unidade) {
    alert("Código equivalencia incluido com sucesso!!");
    f = document.lancamento;
    f.submenu.value = 'incluiequivalenciaPesquisa';
    f.codProduto.value = codigo;
    f.submit();
}

//ALTERACAO 08/01/2024
// //fecha pesquisa de produto e atualiza campos da form que chamou
// function fechaProdutoPesquisaParam(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
//     
//     f = window.opener.document.lancamento;
//     f.codProduto.value = codigo;
//     f.unidade.value = unidade;
//     f.descProduto.value = descProduto;
//     if(lancamento.from.value != ''){
//         f.codProduto.value = codigo;
//         f.pesProduto.value = descProduto;
//         f.descProduto.value = descProduto;
//         f.unidade.value = unidade;
//         if (f.pesProduto != undefined) { //tela de pedido 
//             f.codProduto.value = codigonota;
//             f.pesProduto.value = codigonota;
//             f.mod.value = 'ped';
//             f.form.value = window.opener.document.lancamento.form.value;
//             f.pesq.value = '||||' + codigo + '|' + codigonota;
//         } else {
//             f.mod.value = 'est';
//             f.form.value = 'nota_fiscal_produto'
//         }
//         f.submenu.value = 'cadastrar';
//         f.submit();
//     }
    
//     window.close();
// }

//fecha pesquisa de produto e atualiza campos da form que chamou
// function fechaProdutoPesquisaParam(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
//     
//     f = window.opener.document.lancamento;
//     f.codProduto.value = codigo;
//     f.unidade.value = unidade;
//     f.descProduto.value = descProduto;
//     if(lancamento.from.value != ''){
//         f.codProduto.value = codigo;
//         f.pesProduto.value = descProduto;
//         f.descProduto.value = descProduto;
//         f.unidade.value = unidade;
//         lancamento.from.value == 'baixa_estoque' ? f.quantAtual.value = quantAtual : '';
//         lancamento.from.value == 'baixa_estoque' ? f.valorVenda.value = valorVenda : '';
//         lancamento.from.value == 'baixa_estoque' ? f.uniFracionada.value = uniFracionada : '';
//         if (f.pesProduto != undefined && lancamento.from.value != 'baixa_estoque') { //tela de pedido 
//             f.codProduto.value = codigonota;
//             f.pesProduto.value = codigonota;
//             f.mod.value = 'ped';
//             f.form.value = window.opener.document.lancamento.form.value;
//             f.pesq.value = '||||' + codigo + '|' + codigonota;
//         } else {
//             f.mod.value = 'est';
//             f.form.value = 'nota_fiscal_produto'
//         }
//         f.submenu.value = 'cadastrar';
//         f.submit();
//     }
    
//     window.close();
// }

//OLD
function fechaProdutoPesquisaParam(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    
    f = window.opener.document.lancamento;
    f.codProduto.value = codigo;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;
    if (lancamento.from.value != '') {
        f.codProduto.value = codigo;
        f.pesProduto.value = descProduto;
        f.descProduto.value = descProduto;
        f.unidade.value = unidade;
        lancamento.from.value == 'baixa_estoque' ? f.quantAtual.value = quantAtual : '';
        lancamento.from.value == 'baixa_estoque' ? f.valorVenda.value = valorVenda : '';
        lancamento.from.value == 'baixa_estoque' ? f.uniFracionada.value = uniFracionada : '';
    } else {
        if (f.pesProduto != undefined) { //tela de pedido 
            f.codProduto.value = codigonota;
            f.pesProduto.value = codigonota;
            f.mod.value = 'ped';
            f.form.value = window.opener.document.lancamento.form.value;
            f.pesq.value = '||||' + codigo + '|' + codigonota;
        } else {
            f.mod.value = 'est';
            f.form.value = 'nota_fiscal_produto'
        }
        f.submenu.value = 'cadastrar';
        f.submit();
    }

    window.close();
}

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaOC(e) {
    
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(2)").text().trim(); 
    var codFabricante    = linha.find("td:eq(4)").text().trim();
    var descricaoProduto = linha.find("td:eq(6)").text().trim();
    if (lancamento.codFabricante.value == "")
        var codNota = linha.find("td:eq(3)").text().trim();
    else
        var codNota = lancamento.codFabricante.value;
    codNota = codNota.trim();
    var unidade          = linha.find("td:eq(7)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(8)").text().trim();
    
//    if(document.lancamento.acao != ''){
    if(f.codProduto.value == '0'){
        f.codProduto.value      = id;
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
    }else{
        f.codProduto.value      = id;
        f.codFabricante.value   = codFabricante 
        f.codProdutoNota.value  = codNota 
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
        f.unitario.value        = vlrUnitario   
    }
    f.quant.focus();
    window.close();
}

//Funcao ordem de compra pesquisar produto equivalente
function fechaProdutoPesquisaOcEqui(e) {
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(1)").text().trim(); 
    var codFabricante    = linha.find("td:eq(3)").text().trim();
    var codNota          = linha.find("td:eq(2)").text().trim();
    var descricaoProduto = linha.find("td:eq(4)").text().trim(); 
    var unidade          = linha.find("td:eq(5)").text().trim(); 
    var vlrUnitario      = $("td[name=vlrVenda")[0].innerText;

    f.codProduto.value     = id;
    f.codFabricante.value  = codFabricante 
    f.codProdutoNota.value = codNota 
    f.descProduto.value    = descricaoProduto  
    f.uniProduto.value     = unidade
    f.unitario.value       = vlrUnitario   

    f.quant.focus();
    window.close();
}


function consultaKit(codKitReparo){
     
    $.ajax({
        type: "POST",
        url: document.URL + "?mod=est&form=produto&submenu=pesquisaKitReparo&opcao=blank",
        data: {codKitReparo: codKitReparo},
        dataType: "text",
        success: function (response) {
             
            return  response;
        }
    });
    return false;
}

function closedModal(idModal){
    $("#"+idModal).fadeOut();
}

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisa(e, codKitReparo, descricao) {
     

    const openerDoc = window.opener.document;
    const f = openerDoc.forms.lancamento || openerDoc.getElementById('form_report');

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(2)").text().trim(); 
    var codFabricante    = linha.find("td:eq(3)").text().trim();
    if (lancamento.codFabricante.value == "")
        var codNota          = linha.find("td:eq(3)").text();
    else
        var codNota          = lancamento.codFabricante.value;
    codNota = codNota.trim();
    var descricaoProduto = linha.find("td:eq(6)").text().trim(); 
    var unidade          = linha.find("td:eq(7)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(8)").text().trim();

    if(f.codProduto.value == '0'){
        f.codProduto.value      = id;
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
    }else if(f.id === 'form_report'){
        f.codProduto.value      = id;
        f.descProduto.value     = descricaoProduto 
        f.descProduto.focus()
        window.close();
    }else{
        f.codProduto.value      = id;
        f.codFabricante.value   = codFabricante
        f.codProdutoNota.value  = codNota 
        f.descProduto.value     = descricaoProduto  
        f.uniProduto.value      = unidade
        f.vlrUnitarioPecas.value        = vlrUnitario   
    }
    f.quantidadePecas.focus();
    window.close();
    
}

function fechaProdutoPesquisaTelhas(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    
    f = window.opener.document.lancamento;
    f.codProduto.value = codigonota;
    f.unidade.value = unidade;
    f.descProduto.value = descProduto;

    if(lancamento.from.value != ''){

        f.codProduto.value = codigo;
        f.pesProduto.value = descProduto;
        f.descProduto.value = descProduto;
        f.unidade.value = unidade;
        lancamento.from.value == 'baixa_estoque' ? f.quantAtual.value = quantAtual : '';
        lancamento.from.value == 'baixa_estoque' ? f.valorVenda.value = valorVenda : '';
        lancamento.from.value == 'baixa_estoque' ? f.uniFracionada.value = uniFracionada : '';
    }else{

        if (f.pesProduto != undefined) { //tela de pedido 
            f.pesProduto.value = codigonota;
            f.mod.value = 'ped';
            f.form.value = 'pedido_venda_telhas'
            f.pesq.value = '||||' + codigo + '|' + codigonota;

            //verificar se é origem dashboard para não carregar menu lateral
            if(f.dashboard_origem.value == 'dashboard_crm'){
                f.opcao.value = 'imprimir';
            }

        } else {
            f.mod.value = 'est';
            f.form.value = 'nota_fiscal_produto'
        }

        f.submenu.value = 'cadastrar';
        f.submit();
    }
    
    window.close();
}


//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaProdutoPesquisaAtendimento_old(codigo, descProduto, unidade, codigonota = null, quantAtual = null, valorVenda = null, uniFracionada = null) {
    f = window.opener.document.lancamento;
    var quantId = "quant"+codigo;
    quantValue = document.getElementsByName(quantId)[0].value;

    var vendaId = "venda"+codigo;
    vendaValue = document.getElementsByName(vendaId)[0].value;

    

    var percDescontoItem = "percDescontoItem"+codigo;
    percDescontoItemValue = document.getElementsByName(percDescontoItem)[0].value;

    var descontoItem = "descontoItem"+codigo;
    descontoItemValue = document.getElementsByName(descontoItem)[0].value;

    if(quantValue == "0,00" || quantValue == ""){
        alert("Digite a quantidade do produto.");
        return false;
    }
    if(vendaValue == "0,00" || vendaValue == ""){
        alert("Digite a Venda do produto.");
        return false;
    }

    var codNota = "codnota"+codigo;
    f.codProdutoNota.value = document.getElementsByName(codNota)[0].value;

    f.codProduto.value = codigo;
    f.descProduto.value = descProduto;
    f.uniProduto.value = unidade;
    f.quantidadePecas.value = quantValue;
    f.vlrUnitarioPecas.value = vendaValue;
    

    f.vlrDescontoPecas.value = descontoItemValue;
    f.percDescontoPecas.value = percDescontoItemValue;
    
    f.mod.value = 'cat';
    f.form.value = 'atendimento'
    f.submenu.value = 'cadastrarPeca';
    f.submit();   
    
    window.close();
}

function buscaProdReparos() {
        f = document.lancamento;

        if(f.reparoCodProduto.value !== "" & f.reparoCodProduto.value !== 'undefined' & f.reparoCodProduto.value !== 'null' || 
           f.reparoCodFabricante.value !== "" & f.reparoCodFabricante.value !== 'undefined' & f.reparoCodFabricante.value !== 'null'){

        var form = $("form[name=lancamento]");
    
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Busca-Prod-Reparos", "true");
            },
            success: function (response) {
                 
                var result = $('<div />').append(response).find('#tab_content8').html();
                var prodExiste = $(result).find("input[name=prodExiste]").prevObject[2].defaultValue;
                
                if(prodExiste === 'yes'){
                    $("#tab_content8").html(result);
                    
                    f.reparoQuant.value = '0,00';
                    f.reparoQuant.focus();
                
                }else{
                    //Msg que Prod nao existe
                    swal.fire({
                        text: "Produto Não Localizado!",
                        icon: "warning",
                        dangerMode: "Ok",
                    });
                }
                //Aplica mascara no form 
                $(".money").maskMoney({                  
                    decimal: ",",
                    thousands: ".",
                    allowZero: true,
                   });
            }
        });
            return false;

        }else{
            return false;
        }
    }

function limpaInpReparos(){
    f = document.lancamento;
    f.reparoCodProduto.value = '';
    f.reparoCodFabricante.value = '';
    f.reparoProdDesc.value = '';
    f.reparoQuant.value = '';
}

function dadosItemsCart(codigo, descricao){
    document.getElementById('recipient-codigo').value = codigo;
    document.getElementById('recipient-name').value = descricao;
    setTimeout(function () {
        document.getElementById('recipient-quant').focus();
      }, 500); //1s
}


function addItemsCart(){
    //habilita botao do carrinho
    if(document.getElementById('btnCart')){
        document.getElementById('btnCart').disabled = false;
    }
    let carTemp;
    let codigo = document.getElementById('recipient-codigo').value;
    let name   = document.getElementById('recipient-name').value;
    let quant  = document.getElementById('recipient-quant').value;
    let cart   = document.getElementById('carrinho').value; 
    
    //verifica se a variavel e vazia ou nula e acresenta a variavel
    if(cart == '' || cart == null){
        carTemp = [{ codigo: codigo, descricao: name ,quantidade: quant }];
    }else{
        // trata a variavel para trabalhar com crase
        if(cart.includes("`")){
            cart = cart.replace(/`/g, '"')
        }

        carTemp = JSON.parse(cart);
        carTemp.push({ codigo: codigo, descricao: name, quantidade: quant });
    }
    
    //tranforma em json e atribui a variavel
    let cartString =  JSON.stringify(carTemp);
    //altera o separador json para nao gerar problema na saida de tela do php
    let cartReplace = cartString.replace(/"/g, '`');

    document.getElementById('carrinho').value = cartReplace;
    //zera os campos da modal
    document.getElementById('recipient-codigo').value = '';
    document.getElementById('recipient-name').value   = '';
    document.getElementById('recipient-quant').value  = '';
    //fecha modal ANTIGO CODIGO PAROU DE FUNCIONAR POR CAUSA DE CONLITO DE jQUERY
    //$('#modalInsertCart').modal('hide');

    var botaoCancelar = document.getElementById('closedModalproduto');
    if (botaoCancelar) {
        botaoCancelar.click();
    }

    //atualiza label carrinho
    document.querySelector('.quantCart').innerHTML = carTemp.length;
    document.querySelector('.quantCart').style.backgroundColor = '#e12222'; 
    
    console.log(carTemp)
}

function actionBtnIncluiQuantidade(){
    if(document.getElementById('addQuantidadeKitReparo')){
        setTimeout(function () {
            document.getElementById('addQuantidadeKitReparo').focus();
          }, 800); //1s
    }
}


function decodeEntities(encodedString) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}

//deleta tr da tabela e atualiza string do Json Carrinho
function deleteItemsCart(idTR){
    //apaga tr
    var trElement = document.getElementById(idTR);
    if (trElement) {
        trElement.parentNode.removeChild(trElement);
    } else {
        console.log("A linha com ID " + idTR + " não foi encontrada.");
    }

    var tbodyCart = document.querySelector(".tbodyCart"); // Suponha que tbodyCart seja a classe que contém as linhas da tabela.

    var carTemp = [];
    var cart = '';

    var rows = tbodyCart.getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName("td");

        if (cells.length >= 0) {
            var codigo = cells[0].textContent;
            var descricao = cells[1].textContent;
            var quant = cells[2].textContent;
            carTemp.push({ codigo: codigo, descricao: descricao, quantidade: quant });
        }
    }

    //verifica se existe item
    if(carTemp.length == 0){
        if(document.getElementById('btnCart')){
            document.getElementById('btnCart').disabled = true;
            document.querySelector('.quantCart').style.backgroundColor = '#999999'; 
        }
        var BtnCancelarModal = document.querySelector('.btnCancelarModal');
        if(BtnCancelarModal){
            BtnCancelarModal.click();
        }
        document.getElementById('carrinho').value = '';
        //submit para enviar o carrinho zerado para nao duplicar registros
        document.getElementById('lancamento').submit();
    }else{
        //tranforma em json e atribui a variavel
        document.getElementById('carrinho').value = JSON.stringify(carTemp);
        //atualiza label carrinho
        document.querySelector('.quantCart').innerHTML = carTemp.length;
    }


}

// //abre a modal #modalCart e gera tabela com itens, recebendo o string json $carrinho
// function abrirModalItens() {
    
//     const form = document.getElementById('item-form-cart');
//     const tabelaExistente = form.querySelector('table');
//     let jsonItens = document.getElementById('carrinho');
//     let jsonDecodificado = decodeEntities(jsonItens.value);
//     let objItensRep = jsonDecodificado.replace(/`/g, '"');
//     let objItens = JSON.parse(objItensRep);

//     if (tabelaExistente) {
//         // Se uma tabela já existe dentro do elemento com o ID "item-form-cart", deve-se remover a tabela
//         form.removeChild(tabelaExistente);
//     }

//     // Crie um elemento de tabela com classes Bootstrap
//     var table = document.createElement('table');
//     table.classList.add('table', 'table-striped', 'table-bordered', );

//     // Crie a linha de cabeçalho com classe Bootstrap
//     var thead = document.createElement('thead');
//     thead.classList.add('table-info'); // Adiciona classe de cabeçalho escuro do Bootstrap
//     var cabecalho = thead.insertRow();

//     // Crie as colunas do cabeçalho
//     var th = document.createElement('th');
//     th.textContent = 'Código';
//     th.classList.add('thCartCodigo');
//     cabecalho.appendChild(th);
//     th = document.createElement('th');
//     th.textContent = 'Desrição';
//     cabecalho.appendChild(th);
//     th = document.createElement('th');
//     th.textContent = 'Quantidade';
//     th.classList.add('thCartQuant');
//     cabecalho.appendChild(th);
//     th = document.createElement('th');
//     th.classList.add('thManutencao');
//     th.textContent = '';
//     cabecalho.appendChild(th);

//     // Adicione o cabeçalho à tabela
//     table.appendChild(thead);

//     let htmlTabela = [];
//     for (let prop of Object.keys(objItens)) {
         
//         htmlTabela +=
//             `<tr id="` + objItens[prop]['codigo'] +`">
//                 <td class="tdsCart" style="vertical-align: middle !important; text-align:center;"" >`
//                     + objItens[prop]['codigo'] + 
//                 `</td>
//                 <td class="tdsCart" style="vertical-align: middle !important;" >`
//                     + objItens[prop]['descricao'] + 
//                 `</td>
//                 <td class="tdsCart" style="vertical-align: middle !important; text-align:center;">`
//                     + objItens[prop]['quantidade'] + 
//                 `</td>
//                 <td class="tdsCart" style="vertical-align: middle !important;">
//                     <button type ="button" class="btn btn-sm btn-danger btnExcluirCart" title="Excluir item" onclick ="deleteItemsCart('`+ objItens[prop]['codigo']+`')"> 
//                         <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
//                     </button>
//                 </td>
//             </tr>`
//     }
//     // Crie o corpo da tabela
//     var tbody = document.createElement('tbody');
//     tbody.classList.add('tbodyCart');
//     tbody.innerHTML = htmlTabela;   

//     // Adicione o corpo à tabela
//     table.appendChild(tbody);

//     // Adicione a tabela Bootstrap ao formulário
//     form.appendChild(table);
// }

function abrirModalItens() {
    const form = document.getElementById('item-form-cart');
    const tabelaExistente = form.querySelector('table');
    let jsonItens = document.getElementById('carrinho');
    let jsonDecodificado = decodeEntities(jsonItens.value);
    let objItensRep = jsonDecodificado.replace(/`/g, '"');
    let objItens = JSON.parse(objItensRep);

    if (tabelaExistente) {
        form.removeChild(tabelaExistente);
    }

    // Crie um elemento de tabela com classes Bootstrap
    var table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered');

    // Crie a linha de cabeçalho com classe Bootstrap
    var thead = document.createElement('thead');
    thead.classList.add('table-info');
    var cabecalho = thead.insertRow();

    // Crie as colunas do cabeçalho
    var th = document.createElement('th');
    th.textContent = 'Código';
    th.classList.add('thCartCodigo');
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.textContent = 'Desrição';
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.textContent = 'Quantidade';
    th.classList.add('thCartQuant');
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.classList.add('thManutencao');
    th.textContent = '';
    cabecalho.appendChild(th);

    // Adicione o cabeçalho à tabela
    table.appendChild(thead);

    let htmlTabela = [];
    for (let prop of Object.keys(objItens)) {
        htmlTabela +=
            `<tr id="` + objItens[prop]['codigo'] +`">
                <td class="tdsCart" style="vertical-align: middle !important; text-align:center;">`
                    + objItens[prop]['codigo'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important;">`
                    + objItens[prop]['descricao'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important; text-align:center;">`
                    + objItens[prop]['quantidade'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important;">
                    <button type="button" class="btn btn-sm btn-danger btnExcluirCart" title="Excluir item" onclick="deleteItemsCart('`+ objItens[prop]['codigo']+`')"> 
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>`;
    }

    // Crie o corpo da tabela
    var tbody = document.createElement('tbody');
    tbody.classList.add('tbodyCart');
    tbody.innerHTML = htmlTabela;   

    // Adicione o corpo à tabela
    table.appendChild(tbody);

    // Adicione a tabela Bootstrap ao formulário
    form.appendChild(table);

    // Get the modal footer
    const modalFooter = document.querySelector('#modalCart .modal-footer');
    modalFooter.innerHTML = ''; // Clear existing buttons

    // Create main container for buttons
    const buttonsContainer = document.createElement('div');
    buttonsContainer.className = 'd-flex justify-content-end'; // Use flexbox for alignment

    // Create Cancel button

    const clearCartButton = document.createElement('button');
    clearCartButton.type = 'button';
    clearCartButton.className = 'btn btn-warning mr-2';
    clearCartButton.textContent = 'Limpar Carrinho';
    clearCartButton.onclick = clearCart;

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.className = 'btn btn-secondary btnCancelarModal mr-2'; // Add margin-right for spacing
    cancelButton.setAttribute('data-dismiss', 'modal');
    cancelButton.textContent = 'Cancelar';

    // Create Import button
    const importButton = document.createElement('button');
    importButton.type = 'button';
    importButton.className = 'btn btn-primary';
    importButton.onclick = importaCarrinhoCotacao;
    importButton.innerHTML = (document.getElementById('from').value === 'pedido_ps') ? 'Incluir no pedido' : 'Cadastrar cotação';

    // Append buttons to the container
    buttonsContainer.appendChild(clearCartButton);
    buttonsContainer.appendChild(cancelButton);
    buttonsContainer.appendChild(importButton);

    // Append the container to the modal footer
    modalFooter.appendChild(buttonsContainer);
}

//apaga a tabela no modal #modalCart assim que clica em cancelar
function deleteTableItems(){
    const form = document.getElementById('item-form-cart');
    const tabela = form.querySelector('table');
    if (tabela) {
        tabela.remove();
    }
};

function abrir(pag){
     
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=900,height=550,scrollbars=yes');
}

function importaCarrinhoCotacao(){
     
    let infoCart = document.getElementById('carrinho').value;
    let pessoaId = document.getElementById('pessoaId').value;
    let from = document.getElementById('from').value;
    let form = document.getElementById('form').value;
    const pedidoId = (document.getElementById('idPedido').value > 0 || document.getElementById('idPedido').value !== '') 
                    ? document.getElementById('idPedido').value : null;

    //teste de que form chamou para validar o cliente
    if(window.opener){

        if(form== ''){
            //testa cliente
            if(pessoaId == '' || pessoaId == null){
                 swal.fire({
                    title: "Atenção!",
                    text: "Selecione o cliente para prosseguir!",
                    icon: "warning",
                    button: "OK",
                    dangerMode: true,
                    className: "red-bg",
                });
    
                return false;
            }
        }else if(form == 'pedido_ps'){
           if(pedidoId == ''){
             swal.fire({
                title: "Atenção!",
                text: "Número do pedido não localizado, entre em contato com o suporte!",
                icon: "warning",
                button: "OK",
                dangerMode: true,
                className: "red-bg",
            });

            return false;
           } 
        }
    }else{
        //testa cliente
        if(pessoaId == '' || pessoaId == null){
             swal.fire({
                title: "Atenção!",
                text: "Selecione o cliente para prosseguir!",
                icon: "warning",
                button: "OK",
                dangerMode: true,
                className: "red-bg",
            });

            return false;
        }
    }

    //teste itens
    if(infoCart == '' || infoCart == null || infoCart == '[]'){
         swal.fire({
            title: "Atenção!",
            text: "Itens não localizados!",
            icon: "warninfromg",
            button: "OK",
            dangerMode: true,
            className: "red-bg",
          });

        return false;
    }

    //mseta pergunta quando ja existe pedido
    if(from !== 'pedido_ps'){
        pergunta = "Deseja cadastrar uma cotação com os itens informados?";
    }else{
        pergunta = "Deseja cadastrar os itens informados no pedido " + pedidoId + "?";
    }

     swal.fire({
        title: "Atenção!",
        text: pergunta,
        icon: "warning",
        buttons: ["Cancelar", 'Cadastrar'],
    })

    .then((yes) => {
        if(yes){
             

            let objItensRep = infoCart.replace(/`/g, '"');
            let objCart = JSON.parse(objItensRep);
            //condicao para saber se pedido ja existe ou nao
            if(from == 'pedido_ps'){
                objCart.unshift({ pedidoId: pedidoId });
            }else{
                objCart.unshift({ pessoaId: pessoaId });
            }
            
            objCart = JSON.stringify(objCart);

            //testa se dor do pedido ps
            if(from == 'pedido_ps'){

                return new Promise((resolve, reject) => {
                    
                    carrinhoPedidoExiste(objCart);
                    resolve(); // Resolve a Promisse quando o usuário escolhe uma opção no swal

                }); //Fim Promisse

                
                clearCart();
            }else{
                // Crie um formulario dinamico
                var formulario = document.createElement("form");
                formulario.setAttribute("method", "post");
                //condicao para saber se pedido ja existe ou nao
                if(from == 'pedido_ps'){
                    formulario.setAttribute("action", "index.php?mod=ped&form=pedido_ps&submenu=cadastrarCarrinhoPedido");
                }else{
                    formulario.setAttribute("action", "index.php?mod=ped&form=pedido_ps&submenu=cadastrarCarrinho");
                    
                }
                formulario.setAttribute("target", "_blank"); // Abre em uma nova janela
                
                // Crie um campo de entrada para cada dado que deseja enviar
                var campo1 = document.createElement("input");
                campo1.setAttribute("type", "hidden");
                campo1.setAttribute("name", "letra");
                campo1.setAttribute("value", objCart);
                formulario.appendChild(campo1);

                document.body.appendChild(formulario);

                formulario.submit();
                clearCart();
            }


        }else{
            return false;
        }
    });
}

async function carrinhoPedidoExiste(objCart){
     
    const letra = objCart;
    let cartReplace = {letra: letra, param: 'pedidoExiste'}

    $.ajax({
        type: "POST",
        url: document.URL + "?mod=ped&form=pedido_ps&submenu=cadastrarCarrinhoPedidoExiste&opcao=blank",
        data: cartReplace,
        dataType: "text",
        success: function (response) {
             
            window.opener.lancamento.submit();
            window.close(); 
        }
    });
    return false;

}

function clearCart(){
     
    if(document.getElementById('carrinho').value){
        document.getElementById('carrinho').value = '';
    }
    if(document.getElementById('pessoaId').value){
        document.getElementById('pessoaId').value = '';
    }
    if(document.getElementById('nomeCliente').value){
        document.getElementById('nomeCliente').value = '';
    }
    document.querySelector('.quantCart').innerHTML = '';
    document.getElementById('btnCart').disabled = true;
    document.querySelector('.quantCart').style.backgroundColor = '#999999';
    document.getElementById('lancamento').submit();

}


function ativaAba(divId){
     

    let produtoId = document.getElementsByName('id')[0].value;

    if(produtoId !== ''){

        let sendJson = { produtoId: produtoId , divId: divId, function: 'updateDivs',opcao: 'pesquisarpecas'}

        $.ajax({
            type: "POST",
            url: document.URL + "?mod=est&form=produto&submenu=default",
            data: sendJson,
            dataType: "text",
            success: function (response) {
                console.log(response)
                 
                var result = $('<div />').append(response).find('#'+divId).html();
                console.log(result)
                $("#"+divId).html(result);

                //aplica a mascara 
                $(document).ready(function () {
                    $(".money").maskMoney({
                        decimal: ",",
                        thousands: ".",
                        allowNegative: true,
                        allowZero: true
                    });
                });
            }
        });
        
        return false;
    }
}


function validaReparo(quantReparo){
     

    if(quantReparo.value == '' || quantReparo.value == '0,00'){
         swal.fire({
            text: "Digite a quantidade de kit/reparo que deseja!",
            title: "Atenção",
            icon: "warning",
            dangerMode: "Ok",
        });
        return false;
    }

    const tabela = document.getElementById('datatable-kitreparo');
    const linhas = tabela.querySelectorAll('tr');

    let primeiraLinha = true;
    linhas.forEach(function(linha) {
         
        if (primeiraLinha) {
            primeiraLinha = false;
        } else {
            let reparoId = linha.cells[0].textContent.trim();
            let reparoQuant = linha.cells[3].textContent.trim();
            let reparoEstoque = linha.cells[5].textContent.trim();

            //formata os valores para pode multiplicar
            let quantDigitado = quantReparo.value.replace('.', '');
            quantDigitado = quantDigitado.replace(',', '.');

            let celQuantReparo = reparoQuant.replace('.', '');
            celQuantReparo = celQuantReparo.replace(',', '.');
    
            //multiplica o valor digitado pela td Quant. Kit/Reparo
            let quantMultiplicada = quantDigitado * celQuantReparo;

            //--------------------------------------------------

            //formato o valor do estoque e subtrai
            reparoEstoque = reparoEstoque.replace('.', '');
            reparoEstoque = reparoEstoque.replace(',', '.');
            let quantAtendida = reparoEstoque -quantMultiplicada;

            //atualiza a td Qaunt. Solicitada
            quantMultiplicada = quantMultiplicada.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            let elemento = document.getElementById(reparoId).children[4];
            elemento.textContent = quantMultiplicada;
            elemento.style.textAlign = "center";

            // Verifica se quantAtendida tem sinal de negativo
            var trElement = document.getElementById(reparoId);
            if (quantAtendida < 0) {
                // Seleciona a linha (tr) pelo ID e aplica o estilo de fundo
                trElement.style.background = "#eba0a0";
            }else{
                trElement.style.background = "none";
            }

            //atualiza a td Qaunt. Atendida
            quantAtendida = quantAtendida.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            elemento = document.getElementById(reparoId).children[6];
            elemento.textContent = quantAtendida;
            elemento.style.textAlign = "center";


        }
    });

}

function EditModalReparo(){
     
    let produtoId;
    if(document.getElementsByName('id')){
        produtoId = document.getElementsByName('id')[0].value;
    }
    
    let params = {
        'submenu' : 'default',
        'opcao': 'blank',
        'mod' : 'est',
        'form' : 'produto',
        'produtoId' : produtoId,
        'function' : 'updateDivs'
    }

    let ajaxOptions = {
        type: "POST",
        url: document.URL,
        data: params,
        dataType: "json",
        success: createTableEditaRepair
    };

    // Fazer a solicitação AJAX
    $.ajax(ajaxOptions);

}

function createTableEditaRepair(response){
     

    const form = document.getElementById('item-form-cart');
    const tabelaExistente = form.querySelector('table');
    let jsonItens = document.getElementById('carrinho');
    let jsonDecodificado = decodeEntities(jsonItens.value);
    let objItensRep = jsonDecodificado.replace(/`/g, '"');
    let objItens = JSON.parse(objItensRep);

    // Crie um elemento de tabela com classes Bootstrap
    var table = document.createElement('table');
    table.classList.add('table', 'table-striped', 'table-bordered', );

    // Crie a linha de cabeçalho com classe Bootstrap
    var thead = document.createElement('thead');
    thead.classList.add('table-info'); // Adiciona classe de cabeçalho escuro do Bootstrap
    var cabecalho = thead.insertRow();

    // Crie as colunas do cabeçalho
    var th = document.createElement('th');
    th.textContent = 'Código';
    th.classList.add('thCartCodigo');
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.textContent = 'Desrição';
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.textContent = 'Quantidade';
    th.classList.add('thCartQuant');
    cabecalho.appendChild(th);
    th = document.createElement('th');
    th.classList.add('thManutencao');
    th.textContent = '';
    cabecalho.appendChild(th);

    // Adicione o cabeçalho à tabela
    table.appendChild(thead);

    let htmlTabela = [];
    for (let prop of Object.keys(objItens)) {
         
        htmlTabela +=
            `<tr id="` + objItens[prop]['codigo'] +`">
                <td class="tdsCart" style="vertical-align: middle !important; text-align:center;"" >`
                    + objItens[prop]['codigo'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important;" >`
                    + objItens[prop]['descricao'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important; text-align:center;">`
                    + objItens[prop]['quantidade'] + 
                `</td>
                <td class="tdsCart" style="vertical-align: middle !important;">
                    <button type ="button" class="btn btn-sm btn-danger btnExcluirCart" title="Excluir item" onclick ="deleteItemsCart('`+ objItens[prop]['codigo']+`')"> 
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>`
    }
    // Crie o corpo da tabela
    var tbody = document.createElement('tbody');
    tbody.classList.add('tbodyCart');
    tbody.innerHTML = htmlTabela;   

    // Adicione o corpo à tabela
    table.appendChild(tbody);

    // Adicione a tabela Bootstrap ao formulário
    form.appendChild(table);
}

function atualizarBotao() {
     
    var botaoIncluirKitReparo = document.getElementById('botaoIncluirKitReparo');
    var produtoCombo = document.getElementById('produtoCombo');
    //set id do produto no idNewKit para inclusao
    if((produtoCombo[0].value.length > 0)){
        document.getElementById('idNewKit').value = produtoCombo.selectedOptions[0].value; 
    }
    
    // Verifica se o valor selecionado no select é diferente de vazio ou nulo
    if (produtoCombo.value !== '' && produtoCombo.value !== null) {
        // Ativa o botão
        botaoIncluirKitReparo.style.pointerEvents = 'auto';
        botaoIncluirKitReparo.style.opacity = '1';
    } else {
        // Desativa o botão
        botaoIncluirKitReparo.style.pointerEvents = 'none';
        botaoIncluirKitReparo.style.opacity = '0.5';
    }

  
}

function toggleSelect(){
     

    // Obtenha a referência às divs
    var divSelectProd = document.getElementById('bloco1');
    var divSegundoBloco = document.getElementById('bloco2');
    
    // Move a segunda div abaixo da primeira pois na renderizacao da tela nao aplicava o js para montar a combo
    //divSegundoBloco.parentNode.insertBefore(divSelectProd, divSegundoBloco.nextSibling);

    var divSelectProd = document.getElementById('divSelectProd');
    // Verifica se a div está visível
    var isVisible = window.getComputedStyle(divSelectProd).display !== 'none';

    // Se estiver visível, esconde; se estiver invisível, mostra
    if (isVisible) {
        divSelectProd.style.opacity = '0';
        setTimeout(function() {
            divSelectProd.style.display = 'none';
        }, 500);
    } else {
        divSelectProd.style.display = 'block';
        setTimeout(function() {
            divSelectProd.style.opacity = '1';
        }, 0);
    }
}


function cadastraItemKitReparo() {
     
    let codNewKit = document.getElementById('idNewKit').value;
    let quantNewKit = document.getElementById('addQuantidadeKitReparo').value;
    let codKitReparo = document.getElementById('id').value;

    let params = {
        'submenu': 'adicionaNovoItemKit',
        'opcao': 'blank',
        'mod': 'est',
        'form': 'produto',
        'codKitReparo': codKitReparo,
        'produtoId': codNewKit,
        'quantNova': quantNewKit,
    }

    let ajaxOptions = {
        type: "POST",
        url: document.URL,
        data: params,
        dataType: "json",
        success: responseSucessIncluiKit,
        error: responseErrorIncluiKit
    };

    // Fazer a solicitação AJAX
    $.ajax(ajaxOptions);
}


function responseSucessIncluiKit(response){
     
    if(response.success === true){
        
         swal.fire({
            text: response.mensagem,
            title: "Sucesso",
            icon: "success",
        });

        var select = document.getElementById("produtoCombo");
        select.innerHTML = "";

        var divSelectProd = document.getElementById('divSelectProd');
        // Verifica se a div está visível
        var isVisible = window.getComputedStyle(divSelectProd).display !== 'none';
    
        // Se estiver visível, esconde; se estiver invisível, mostra
        if (isVisible) {
            divSelectProd.style.opacity = '0';
            setTimeout(function() {
                divSelectProd.style.display = 'none';
            }, 500);
        } else {
            divSelectProd.style.display = 'block';
            setTimeout(function() {
                divSelectProd.style.opacity = '1';
            }, 0);
        }

        document.getElementById('addQuantidadeKitReparo').value = '';
        document.getElementById('closedKitReparo').click();
        document.getElementById('dados-tab-reparo').click();
    }else{
        swal.fire({
            text: response.mensagem,
            title: "Atenção",
            icon: "warning",
            dangerMode: "Ok",
        });
        document.getElementById('addQuantidadeKitReparo').value = '';
        document.getElementById('closedKitReparo').click();
        document.getElementById('dados-tab-reparo').click();
    }
}

function responseErrorIncluiKit(response){
    swal.fire({
        text: response.mensagem,
        title: "Atenção",
        icon: "warning",
        dangerMode: "Ok",
    });
    document.getElementById('addQuantidadeKitReparo').value = '';
    document.getElementById('closedKitReparo').click();
    document.getElementById('dados-tab-reparo').click();
}

function submitExcluirReparoConsultaPreco(id) {

     swal.fire({
        title: "Atenção!",
        text: "Deseja realmente excluir este item do kit reparo?",
        icon: "warning",
        buttons: {
            btn_cancelar: {
                text: "Cancelar",
                value: '0',
            },
            btn_cadastrar: {
                text: "Excluir",
                value: "1",
            }
        }
    })
    .then((val) => {
         
        if (val == '1') {

            let codItem = id;
        
            let params = {
                'submenu': 'excluiItemReparo',
                'opcao': 'blank',
                'mod': 'est',
                'form': 'produto',
                'produtoId': codItem
            }
        
            let ajaxOptions = {
                type: "POST",
                url: document.URL,
                data: params,
                dataType: "json",
                success: responseSucessExcluiItem,
                error: responseErrorExcluiItem
            };
        
            // Fazer a solicitação AJAX
            $.ajax(ajaxOptions);

        } else if (val == '0') {//cancel
            return false;
        } else {
            return false;
        }

    });//Fim Swal
}

function responseSucessExcluiItem(response){
     
    if(response.success === true){
        
        swal.fire({
            text: response.mensagem,
            title: "Sucesso",
            icon: "success",
        });

        document.getElementById('addQuantidadeKitReparo').value = '';
        document.getElementById('closedKitReparo').click();
        document.getElementById('dados-tab-reparo').click();
    }else{
        swal.fire({
            text: response.mensagem,
            title: "Atenção",
            icon: "warning",
            dangerMode: "Ok",
        });
        document.getElementById('addQuantidadeKitReparo').value = '';
        document.getElementById('closedKitReparo').click();
        document.getElementById('dados-tab-reparo').click();
    }
}

function responseErrorExcluiItem(response){
     
    swal.fire({
        text: response.mensagem,
        title: "Atenção",
        icon: "warning",
        dangerMode: "Ok",
    });
    document.getElementById('addQuantidadeKitReparo').value = '';
    document.getElementById('closedKitReparo').click();
    document.getElementById('dados-tab-reparo').click();
}