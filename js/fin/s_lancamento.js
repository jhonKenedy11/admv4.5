
//************************
// UTILITARIOS ***********
//************************
// document.addEventListener('keydown', function (event) {
//     // evento pressionar ENTER
//     if (event.key == "Enter") {
//         submitLetra();
//     }// fim evento enter
// });// fim addEventListener

function toggle(obj) {
    var el = document.getElementById(obj);
    if ( el.style.display != 'none' ) {
        el.style.display = 'none';
    }else {
        el.style.display = '';
    }//if
}// function

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}


function converteColunaNumeroBR(ws, colIndex, primeiraLinhaDados) {
    if (!ws || !ws['!ref']) return;

    var range = XLSX.utils.decode_range(ws['!ref']);
    var rInicio = (typeof primeiraLinhaDados === 'number')
        ? primeiraLinhaDados
        : range.s.r + 1; // pula cabeçalho por padrão

    for (var r = rInicio; r <= range.e.r; r++) {
        var cell = ws[XLSX.utils.encode_cell({ r: r, c: colIndex })];
        if (!cell || cell.v == null) continue;

        var txt = (cell.v + '').trim();
        if (!txt) continue;

        txt = txt
            .replace(/^R\$\s*/i, '')
            .replace(/\./g, '')
            .replace(',', '.');

        var num = parseFloat(txt);
        if (!isNaN(num)) {
            cell.t = 'n';
            cell.v = num;
            cell.z = '#,##0.00';
        }
    }
}

function submitMassa(id) {
    f = document.lancamento;
    f.submenu.value = 'massa';
    f.id.value = id;
    Swal.fire({
        title: 'Lançamento em lote',
        html: 'Informe a <b>sigla</b> da ATIVIDADE (conforme <b>Cadastros Gerais → Atividade</b>, ou cadastro do cliente).',
        input: 'text',
        inputPlaceholder: 'Ex.: CL (cliente)',
        inputValue: '',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value || !String(value).trim()) return 'Informe a sigla da atividade.';
            return null;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            f.atividade.value = String(result.value).trim();
            f.submit();
        }
    });
} // fim submitParcela

//==ON CHANGE ===
function dataMovimento() {
    
    f = document.lancamento;
    if (f.submenu.value == "cadastrar" && f.situacaolancamento.value == 'A'){
        f.datamov.value = f.datavenc.value;
    }
} // fim submitParcela

function calculaTotal(){
    var f = document.lancamento;

    var original=f.original.value;
    var multa=f.multa.value;
    var juros=f.juros.value;
    var adiantamento=f.adiantamento.value;
    var desconto=f.desconto.value;
    var total=0;
    var total=parseFloat(original.replace(".","").replace(",","."))+
              parseFloat(multa.replace(".","").replace(",","."))+
              parseFloat(juros.replace(".","").replace(",","."))-
              parseFloat(adiantamento.replace(".","").replace(",","."))-
              parseFloat(desconto.replace(".","").replace(",","."));
    f.total.value = currencyFormat(total);
}

function submitParcela(id) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'parcela';
    f.id.value = id;
    Swal.fire({
        title: 'Acrescentar parcelas',
        text: 'Quantidade de parcelas para o lançamento:',
        input: 'number',
        inputValue: 1,
        inputAttributes: { min: 1, step: 1 },
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            const n = Number(value);
            if (!Number.isFinite(n) || n < 1) return 'Informe uma quantidade válida (mínimo 1).';
            return null;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            f.quantparc.value = String(result.value);
            f.submit();
        }
    });
} // fim submitParcela


//Submit Atualiza
function submitAtual(selObj, id) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = selObj.options[selObj.selectedIndex].value;
    f.id.value = id;
    if (f.submenu.value=='parcela'){
            f.quantparc.value = prompt('Quantidade de Parcelas para Lançamento', 1);
            if (f.quantparc.value != ""){
                    f.submit();
            }	
    }
} // fim submitAtual

// desenha Cadastro

function submitVoltar(formulario) {
    
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    if(formulario == 'conferencia'){
        l = window.opener.document.conferencia;
        l.submenu.value = 'cancel'
        //l.submit();
        window.close();
    }else{
        f.opcao.value = formulario;
        f.submenu.value = '';
        f.submit();
    }

} // fim submitVoltar

function reenviaCobranca(id) {
    f = document.lancamento;
    Swal.fire({
        title: 'Reenviar cobrança bancária',
        text: 'Deseja realmente cancelar o título atual e gerar um novo título para cobrança bancária?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, gerar novo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f.mod.value = 'fin';
            f.form.value = 'lancamento';
            f.submenu.value = 'reenvia';
            f.id.value = id;
            f.submit();
        }
    });
} // fim submitParcela


function submitConfirmar(formulario) {
    
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = "";
    var coluna = "";
    var valorRateio = 0; 
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      coluna = row.item(0).firstChild.nodeValue;
      cc = cc + coluna;
      coluna = row.item(1).firstChild.nodeValue;
      cc = cc + "-" + coluna ;
      coluna = row.item(2).getElementsByTagName("input");
      coluna = coluna.item(0).value;
      
      // Trata valores com vírgula e ponto
      const valorSemPonto = coluna.replace(/\./g, ""); // Remove todos os pontos
      const valorSemVirgula = valorSemPonto.replace(",", "."); // Substitui vírgula por ponto
      
      valorRateio = parseFloat(valorRateio) + parseFloat(valorSemVirgula) ;    
      cc = cc + "-" + coluna + "|" ;
    }
    if (valorRateio == 0 ){
        var comboCentroCusto = document.getElementById("centrocusto");
        cc = comboCentroCusto.options[comboCentroCusto.selectedIndex].value;
        cc = cc + "-" + comboCentroCusto.selectedIndex;
        ccdesc = comboCentroCusto.options[comboCentroCusto.selectedIndex].text;
        cc = cc + "-100";
        valorRateio = 100;
    } 
    f.rateioCC.value = cc;

    if (f.original.textLength == 0) {
      Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Permitido somente número inteiro positivo!' }); }
    else if (f.genero.value == "")
      Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Preencha o campo Gênero!' });
    else if (f.nome.value == "")
      Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Selecione uma Pessoa!' });
    else if (valorRateio != 100)
      Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Percentual do rateio deve totalizar 100%!' });
    else if (parseFloat(f.original.value) < 0)
      Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Digite um valor para o documento!!' });
    else {
        Swal.fire({
            title: 'Confirmar',
            text: 'Deseja realmente salvar este item?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                f.opcao.value = formulario;
                if ((f.submenu.value == "alterar") || (f.submenu.value == "altera")) {

                    f.submenu.value = 'altera';

                    // Verifica se a parcela foi informada
                    if (!f.parcela.value || String(f.parcela.value).trim() === '') {
                        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Informe o número da parcela!' });
                        return;
                    }
                } else {
                    f.submenu.value = 'inclui';
                    if (!f.parcela.value || String(f.parcela.value).trim() === '') {
                        f.parcela.value = '1';
                    }
                }
                f.submit();
            }
        });
    } // if
} // fim submitConfirmar

function submitSalvaRateio() {
    
    var valida = validaPercentualConfirma();
    if (!valida){
         return false;
    }else{
        f = document.lancamento;
        f.mod.value = 'fin';
        f.form.value = 'lancamento';

        var table = document.getElementById("datatable-cc");
        var r = table.rows.length;
        var  cc = "";
        var coluna = "";
        var valorRateio = 0; 
        for (i = 1; i < r; i++){
          var row = table.rows.item(i).getElementsByTagName("td");
          coluna = row.item(0).firstChild.nodeValue;
          cc = cc + coluna;
          coluna = row.item(1).firstChild.nodeValue;
          cc = cc + "-" + coluna ;
          coluna = row.item(2).getElementsByTagName("input");
          coluna = coluna.item(0).value;
          
          // Trata valores com vírgula e ponto
          const valorSemPonto = coluna.replace(/\./g, ""); // Remove todos os pontos
          const valorSemVirgula = valorSemPonto.replace(",", "."); // Substitui vírgula por ponto
          
          valorRateio = parseFloat(valorRateio) + parseFloat(valorSemVirgula) ;    
          cc = cc + "-" + coluna + "|" ;
        }
        if (valorRateio == 0 ){
            var comboCentroCusto = document.getElementById("centrocusto");
            cc = comboCentroCusto.options[comboCentroCusto.selectedIndex].value;
            cc = cc + "-" + comboCentroCusto.selectedIndex;
            ccdesc = comboCentroCusto.options[comboCentroCusto.selectedIndex].text;
            cc = cc + "-100";
            valorRateio = 100;
        } 
        f.rateioCC.value = cc;

        if (f.id.value == '') {
          alert("Salve o lançamento antes de salvar o rateio!"); }
        else {
            f.submenu.value = 'salvarateio';
            f.submit();
        } // if
    }
} // fim submitSalvaRateio

// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.opcao.value = formulario;
    f.submenu.value = 'cadastrar';
    f.id.value = "";
    f.submit();
}

function submitAlterar(lancamento_id) {

    Swal.fire({
        title: 'Confirmar alteração',
        text: 'Deseja realmente alterar este item?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = 'lancamento';
            f.submenu.value = 'alterar';
            f.id.value = lancamento_id;
            f.submit();
        }
    });
}

function submitExcluir(lancamento_id) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Deseja realmente excluir este item?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = 'lancamento';
            f.submenu.value = 'exclui';
            f.id.value = lancamento_id;
            f.submit();
        }
    });
}
	

// ----------------------------------------------------------------------
// ------ MONTA LETRA
// ----------------------------------------------------------------------
function montaLetra() {
    var i;
    var l;

    f = document.lancamento;

    //f.dataIniDay.valueee
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";

    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++){
            if (f.dataReferencia[i].selected){
                    f.letra.value = f.letra.value + f.dataReferencia[i].value;
            }
    }

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitlanc[i].value; }}

    // filial
    myCheckbox = document.lancamento.elements["filial[]"];

    l = 0;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){  
            f.letra.value = f.letra.value + "|" + filial[i].value;	}}

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + tipolanc[i].value; 	}}

    // situacao documento
    myCheckbox = document.lancamento.elements["sitdocto[]"];

    l = 0;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitdocto[i].value; 	}}

    // Conta
    myCheckbox = document.lancamento.elements["conta[]"];

    l = 0;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){  
            f.letra.value = f.letra.value + "|" + conta[i].value; 	}}


    // Genero Pagamaneto
    if ( f.genero != "0") {
            f.letra.value = f.letra.value + "|" + f.genero.value;
    }

    // TIPO DOCUMENTO
    myCheckbox = document.lancamento.elements["tipoDocto[]"];
    l = 0;
    for  (var i=0;i< tipoDocto.options.length;i++){  
        if (tipoDocto[i].selected == true){ l++; } }
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipoDocto.options.length;i++){  
    if (tipoDocto[i].selected == true){  
        f.letra.value = f.letra.value + "|" + tipoDocto[i].value; 	
    }
    }
    f.letra.value = f.letra.value + "|"; 	



} // MONTA LETRA	
    
    
 // ----------------------------------------------------------------------
 // ------ SUBMIT LETRA
 // ----------------------------------------------------------------------
function submitLetra() {
    
    var i;
    var l;

    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    //   		f.opcao.value = formulario;
    f.submenu.value = 'letra';
    //f.dataIniDay.valueee
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";

    // data referencia
    for (i = 0; i < f.dataReferencia.length; i++){
            if (f.dataReferencia[i].selected){
                    f.letra.value = f.letra.value + f.dataReferencia[i].value;
            }
    }

    // situacao lancamento
    myCheckbox = document.lancamento.elements["sitlanc[]"];

    l = 0;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitlanc.options.length;i++){  
        if (sitlanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitlanc[i].value; }}

    // filial
    myCheckbox = document.lancamento.elements["filial[]"];

    l = 0;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< filial.options.length;i++){  
        if (filial[i].selected == true){  
            f.letra.value = f.letra.value + "|" + filial[i].value;	}}

    // tipo lancamento
    myCheckbox = document.lancamento.elements["tipolanc[]"];

    l = 0;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipolanc.options.length;i++){  
        if (tipolanc[i].selected == true){  
            f.letra.value = f.letra.value + "|" + tipolanc[i].value; 	}}

    // situacao documento
    myCheckbox = document.lancamento.elements["sitdocto[]"];

    l = 0;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< sitdocto.options.length;i++){  
        if (sitdocto[i].selected == true){  
            f.letra.value = f.letra.value + "|" + sitdocto[i].value; 	}}

    // Conta
    myCheckbox = document.lancamento.elements["conta[]"];

    l = 0;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){ l++; }}
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< conta.options.length;i++){  
        if (conta[i].selected == true){  
            f.letra.value = f.letra.value + "|" + conta[i].value; 	}}


    // Genero Pagamaneto
    if ( f.genero != "0") {
            f.letra.value = f.letra.value + "|" + f.genero.value;
    }

    // TIPO DOCUMENTO
    myCheckbox = document.lancamento.elements["tipoDocto[]"];
    l = 0;
    for  (var i=0;i< tipoDocto.options.length;i++){  
        if (tipoDocto[i].selected == true){ l++; } }
    f.letra.value = f.letra.value + "|" + l;
    for  (var i=0;i< tipoDocto.options.length;i++){  
    if (tipoDocto[i].selected == true){  
        f.letra.value = f.letra.value + "|" + tipoDocto[i].value; 	
    }
    }

    f.submit();
}	

function consultaConsolidacao() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'consolidacao';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=consolidacao&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
    }

function consultaLctoData() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'data_analitico';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=data_analitico&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
         
}	

function consultaFluxoCaixa() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'fluxo_caixa';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=fluxo_caixa&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
         
}	

function consultaGenero() {
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'genero_analitico';
    g.submenu.value = 'imprime';
    window.open('index.php?mod=fin&form=genero_analitico&opcao=imprimir&letra=' + g.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}	

function consultaCentroCusto(hidden) {
    
    g = document.lancamento;
    montaLetra();
    g.mod.value = 'fin';
    g.form.value = 'centrocusto_analitico';
    g.submenu.value = 'imprime';
    
    window.open('index.php?mod=fin&form=centrocusto_analitico&opcao=imprimir&letra=' + g.letra.value + '&relHidden=' + hidden, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}	

function consultaDRE(sitlan) {
	g = document.lancamento;
	montaLetra(sitlan);
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=consulta_dre&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function consultaDREAnual(sitlan) {
	g = document.lancamento;
	montaLetra(sitlan);
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=consulta_dre_anual&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=1200,height=900,scrollbars=yes');
}
function remessaBancaria() {
	g = document.lancamento;
	montaLetra();
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=remessa_bancaria&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function lancPedDataEntrega() {
	g = document.lancamento;
	montaLetra();
      //  alert(g.letra.value);
   	window.open('index.php?mod=fin&form=rel_lanc_ped_data_entrega&opcao=imprimir&letra=' + g.letra.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

// ----------------------------------------------------------------------
// ------CONSULTA GENERO
// ----------------------------------------------------------------------
	


function abrir(pag)
{

    window.open(
        pag,
        'consulta',
        'toolbar=no,location=yes,status=no,menubar=no,scrollbars=no,resizable=yes,width=800,height=900'
    );
}
        
function abrirGenero(pag)
{
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
}
    
// ----------------------------------------------------------------------
// ------ CONFERENCIA CAIXA
// ----------------------------------------------------------------------    
         
function fechaConferencia(formulario){
     
    l = document.lancamento;
    l.dadosConf.value = l.pessoa.value + "|" + l.centrocusto.value + "|" + l.docto.value + "|" + l.serie.value + "|" + l.parcela.value + "|" + l.tipolancamento.value + "|" + l.tipodocto.value + "|" + l.situacaodocto.value + "|" + l.situacaolancamento.value + "|" + l.genero.value + "|" + l.modo.value + "|" + l.doctobancario.value + "|" + l.conta.value + "|" + l.cheque.value + "|" + l.datalanc.value + "|" + l.dataemissao.value + "|" + l.datavenc.value + "|" + l.datamov.value + "|" + l.moeda.value + "|" + l.original.value + "|" + l.multa.value + "|" + l.juros.value + "|" + l.adiantamento.value + "|" + l.desconto.value + "|" + l.desconto.value + "|" + l.total.value + "|" + l.obs.value;

    f = window.opener.document.conferencia;

    f.dadosLancamento.value = l.dadosConf.value;
    //alert(f.dadosLancamento.value);

    f.letra.value = l.letraC.value;
    f.submenu.value = 'dadosLancamento'
    f.submit();

    window.close();
}

function submitRecarregar(){
    l = document.lancamento;
    document.getElementById('ancora').click();
    //l.submit();
}

// ATUALIZA TIPO DE LANCAMENTO ( RECEBIMENTO / PAGAMENTO )
function tipoLancamento() {
    f = document.lancamento;
    if (document.getElementById('divDescTipo') != null){
        var labe1= document.getElementById('descTipo');
        var div= document.getElementById("divDescTipo");
        if (f.tipolancamento.value == "R"){
            labe1.innerHTML  = "RECEBIMENTO";
            div.className = "alert alert-success";
        }//if
        else {
            labe1.innerHTML  = "PAGAMENTO";
            div.className = "alert alert-danger";
        }//else
        //f.tipolancamento.value = tipoLanc;
    }//if
} //function

function validaPercentual(formulario) {
    
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = 0;
    var coluna = "";
    for (i = 1; i < r; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      coluna = row.item(2).getElementsByTagName("input");
      coluna = coluna.item(0).value;

      // Trata valores com vírgula e ponto
      const valorSemPonto = coluna.replace(/\./g, ""); // Remove todos os pontos
      const valorSemVirgula = valorSemPonto.replace(",", "."); // Substitui vírgula por ponto
      
      cc = parseFloat(cc) + parseFloat(valorSemVirgula) ;
    }
    
    if (cc > 100) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Percentual de rateio maior que o permitido!',
                confirmButtonColor: '#dc3545'
            });
        } else {
            alert('Percentual de rateio maior que o permitido!');
        }
    } else if (cc < 100) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Percentual de rateio menor que o permitido!',
                confirmButtonColor: '#dc3545'
            });
        } else {
            alert('Percentual de rateio menor que o permitido!');
        }
    } 
   
} // fim validaPercentual

function validaPercentualConfirma() {
    
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';

    var table = document.getElementById("datatable-cc");
    var r = table.rows.length;
    var  cc = 0;
    var coluna = "";
    for (i = 1; i < r; i++){
        var row = table.rows.item(i).getElementsByTagName("td");
        coluna = row.item(2).getElementsByTagName("input");
        coluna = coluna.item(0).value;

        // Trata valores com vírgula e ponto
        const valorSemPonto = coluna.replace(/\./g, ""); // Remove todos os pontos
        const valorSemVirgula = valorSemPonto.replace(",", "."); // Substitui vírgula por ponto
        
        cc = parseFloat(cc) + parseFloat(valorSemVirgula) ;
    }
    
    if (cc > 100) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Percentual de rateio maior que o permitido!',
                confirmButtonColor: '#dc3545'
            });
        } else {
            alert('Percentual de rateio maior que o permitido!');
        }
        return false;
    } else if (cc < 100) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Percentual de rateio menor que o permitido!',
                confirmButtonColor: '#dc3545'
            });
        } else {
            alert('Percentual de rateio menor que o permitido!');
        }
        return false;
    } else {
        return true;
    }
   
} // fim validaPercentualConfirma

function agrupaLancModal(){
    
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var tipoDoc = ''
    var pessoa = '';
    var count = 0;
    var lancChecked = false;
    totalMulta          = 0;
    totalJuros          = 0;
    totalDesconto       = 0;
    totalOriginal       = 0;
    totalLanc           = 0;

    dataAtual = new Date();
    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        if (row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[1].childNodes[0].data;    
                dados   = cells[10].childNodes[0].data;   
                arrDados = dados.split("|");
    
                if (pessoa === ''){
                    pessoa = novaPessoa;
                    f.pessoa.value = arrDados[0].trim();
                }
                if(tipoDoc === ''){
                    tipoDoc = arrDados[1].trim();
                }
                if(tipoDoc !== arrDados[1].trim()){
                    alert("Selecione o mesmo tipo de Documento para fazer o Agrupamento de Titulos.");
                    return false;
                }
                
                if(novaPessoa === pessoa){   
    
                    total  = cells[9].childNodes[0].data;
                    valores =  cells[10].childNodes[0].data; ;
                    arrValores = valores.split("|");

                    multa  = arrValores[2].trim();
                    juros  = arrValores[3].trim();
                    desconto  = arrValores[4].trim();
                    original  = arrValores[5].trim();
    
                    total = parseFloat(total.replace(".","").replace(",","."));
                    multa = parseFloat(multa.replace(".","").replace(",","."));
                    juros = parseFloat(juros.replace(".","").replace(",","."));
                    desconto = parseFloat(desconto.replace(".","").replace(",","."));
                    original = parseFloat(original.replace(".","").replace(",","."));
    
                    totalLanc  += total;
                    totalMulta += multa;
                    totalJuros += juros;
                    totalDesconto += desconto;
                    totalOriginal += original;
    
                }else{
                    alert("Selecione a mesma Pessoa para fazer o Agrupamento de Lançamentos.");
                    return false;
                }
                count += 1
            }

        }
        
    }
    if(lancChecked == true && count > 1){
        f.mPessoa.value  = pessoa
        f.mMulta.value   = currencyFormat(totalMulta);
        f.mJuros.value   = currencyFormat(totalJuros);
        f.mDesconto.value   = currencyFormat(totalDesconto);
        f.mOriginal.value = currencyFormat(totalOriginal);
        f.mTotal.value   = currencyFormat(totalLanc);
        f.mDataVencimento.value = dataFormatada;
        $('#modalAgrupamentoLanc').modal('show');
    }else{
        alert("Selecione mais de um Lançamento para fazer o Agrupamento de Lançamentos.");
        return false;
    }
    
}


function baixaLoteLancModal(){
    
    f = document.lancamento;
    f.pessoa.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var tipoDoc = ''
    var count = 0;
    var lancChecked = false;
    totalMulta          = 0;
    totalJuros          = 0;
    totalDesconto       = 0;
    totalOriginal       = 0;
    totalLanc           = 0;

    dataAtual = new Date();
    dia  = dataAtual.getDate().toString().padStart(2, '0'),
    mes  = (dataAtual.getMonth()+1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
    ano  = dataAtual.getFullYear();
    dataFormatada = dia+"/"+mes+"/"+ano

    for (i = 1; i < r; i++) {
        
        var row = table.rows.item(i).getElementsByTagName("input");        
        if (row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                var cells = table.rows[i].getElementsByTagName("td");
    
                novaPessoa = cells[1].childNodes[0].data;   
                total   = cells[9].childNodes[0].data;    
                dados   = cells[10].childNodes[0].data;   
                arrDados = dados.split("|");

                total = parseFloat(total.replace(".","").replace(",","."));
                totalLanc  += total;
                
                if(tipoDoc === ''){
                    tipoDoc = arrDados[1].trim();
                }
                if(tipoDoc !== arrDados[1].trim()){
                    alert("Selecione o mesmo tipo de Documento para fazer a Baixa de Titulos.");
                    return false;
                }
                
                
                count += 1
            }

        }
        
    }
    // if(lancChecked == true && count > 1){
    if(lancChecked == true){
            f.mDataEmissao.value = dataFormatada;
        f.mTotalBaixar.value   = currencyFormat(totalLanc);
        $('#modalBaixaLote').modal('show');
    }else{
        alert("Selecione mais de um Lançamento para fazer a Baixa.");
        return false;
    }
    
}

function submitAgruparLancamento(){
    
    f = document.lancamento;
    f.dadosLancAgrupamento.value = '';
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = lancs + "|" + row[0].id;
            }
        }
    }

    

    f.dadosLancAgrupamento.value = lancs;
    f.dadosLanc.value = f.pessoa.value + "|" + f.mDataVencimento.value + "|" + f.mMulta.value + "|" + f.mJuros.value + "|" + f.mDesconto.value + "|" + f.mTotal.value + "|" + f.mOriginal.value + "|" + f.mNumDocto.value
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'agruparLanc';
    f.submit()
}

function submitBaixaLancamentoLote(){
    
    f = document.lancamento;
    f.dadosLancAgrupamento.value = '';
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = lancs + "|" + row[0].id;
            }
        }
    }

    f.dadosLanc.value = f.contaCombo.value + "|" + f.mDataEmissao.value + "|" + lancs;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'baixaLanc';
    f.submit()
}

function rel_lanc_baixado_lote(){
    f = document.lancamento;
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancs = row[0].id + "|" +  lancs;
            }
        }
    }

    f.dadosLanc.value = lancs;
    var letraRel =  f.mDataEmissao.value + "|" + f.contaCombo.value 

    window.open('index.php?mod=fin&form=rel_lanc_baixa_lote&opcao=imprimir&letra=' +letraRel+'&dadosLanc='+f.dadosLanc.value+'&rel=D', 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function impSlipLote(){
    f = document.lancamento;
    f.dadosLanc.value = '';
    var table = document.getElementById("datatable-buttons");
    var r = table.rows.length;
    var lancChecked = false;

    var lancs = "";
    for (i = 1; i < r; i++) {
        var row = table.rows.item(i).getElementsByTagName("input");
        if(row.length > 0){
            if (row[0].checked == true) {
                lancChecked = true;
                lancs = row[0].id + "|" +  lancs;
            }
        }
    }

    f.dadosLanc.value = lancs;

    if(lancChecked == true){
        window.open('index.php?mod=fin&form=rel_slip_imprime&opcao=imprimir&letra=' +f.dadosLanc.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
    }else{
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione mais de um Lançamento para fazer a impressão do SLIP.',
            confirmButtonText: 'OK'
        });
        return false;
    }
 
}

function clonarFinanceiro(){
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'clonaFinanceiro';
    f.submit()
}

function submitSalvarAnexo(id) {
    f = document.lancamento;
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'salvarAnexo';
    f.id.value = id;
    f.submit();
} // submitAnexo 

function submitExcluirAnexo(id, idAnexo) {
    
     swal.fire({
        title: "Atenção!",
        text: "Deseja excluir esse anexo?",
        icon: "warning",
        buttons: {
            btn_cancelar: {
                text: "Cancelar",
                value: '0',
            },
            btn_excluir: {
                text: "Excluir",
                value: "1",
            }
        }})
    .then((val) => {
        
        if(val == '1'){ //excluir
            f = document.lancamento;
            f.mod.value = 'fin';
            f.form.value = 'lancamento';
            f.submenu.value = 'excluiAnexo';
            f.id.value = id;
            f.idAnexo.value = idAnexo;
            f.submit();
        }else if(val == '0'){ //cancel
            return false;
        }else{
            return false;
        }
    
    }); //Fim Swal
} // submitExcluirImagem


function openAnexo(imagePath) {
    // Verifica se o caminho é para um PDF
    if (imagePath.endsWith('.pdf')) {
        // Abre o PDF em uma nova janela
        window.open(imagePath, '_blank');
    } else {
        $('#myModal img').attr('src', imagePath); 
        $('#myModal').modal('show');
        
    }
}

function downloadImageAnexo() {
    
    // Obtém o URL da imagem a partir do atributo src da tag img
    var imageUrl = document.querySelector('.imgModal').src;

    // Cria um elemento 'a' temporário para simular o clique no botão de download
    let link = document.createElement('a');
    link.href = imageUrl;

    // Corrige a capitalização de imageUrl
    let match = imageUrl.match(/\/(\d+)\/(\d+)\.(jpeg|jpg)$/);
    let docId = match[1];
    let fileName = match[2];
    let newName = docId + "_" + fileName;
    link.download = newName; // Nome do arquivo a ser baixado
    document.body.appendChild(link);

    // Simula o clique no link
    link.click();

    // Remove o elemento 'a' temporário
    document.body.removeChild(link);
}

function imprimirBoleto(id) {

    let id_lancamento = id;

    if(!id_lancamento) {
        Swal.fire('Erro', 'ID do lançamento não informado', 'error');
        return false;
    }

    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {
            'mod': 'fin',
            'form': 'boleto_imprime',
            'submenu': 'recuperarCobranca',
            'opcao': 'ajax',
            'id': id,

        },
        success: (response) => {
            Swal.close();

            if (response.success) {
                this.RecuperarCobrancaInterSuccess(response);
            } else {
                Swal.fire('Erro', response.message || 'Falha ao emitir cobrança', 'error');
            } 
            
        },
        error: (xhr) => {
            Swal.close();

            debugger

            httpCode = xhr.status;

            if(httpCode === 400) {
                this.RecuperarCobrancaInterError400(xhr.responseJSON);
            } else {
                this.RecuperarCobrancaInterError(xhr.responseJSON);
            }
        }
    });

}

function submitAtualizaJuros() {
    f = document.lancamento;
    montaLetra();
    f.mod.value = 'fin';
    f.form.value = 'lancamento';
    f.submenu.value = 'atualizaJuros';
    f.submit();
}