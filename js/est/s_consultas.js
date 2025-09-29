/****
* UTILITARIOS
**/ 
function abrir(pag) {
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=750,height=650,scrollbars=yes');
}

// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}
function montaLetraConsulta(){
    debugger;
    f = document.lancamento;
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.dataReferencia.value + "|" + f.codProduto.value + "|" + f.pessoa.value + "|" + f.numNf.value + "|" + f.ccusto.value + "|" + f.tipoCurva.value + "|" + f.tipoGrupo.value + "|" + f.localizacao.value;
    
    // grupo  
    f.grupoSelected.value = concatCombo(grupo);
    // situacao lancamento
    f.sitLSelected.value = concatCombo(sitlanc);
    // tipo lancamento
    f.tipoLSelected.value = concatCombo(tipolanc);
    // tipo lancamento
    f.localizacaoSelected.value = concatCombo(localizacao);
}

function relatorioKardex() {
    debugger;
    if(document.lancamento.codProduto.value == ''){
        alert('Informe o Produto para gerar o relatório.');
        return false;
    }
    if(document.lancamento.ccusto.value == ''){
        alert('Informe o Centro de Custo para gerar o relatório.');
        return false;
    }
    
    montaLetraConsulta();
    window.open('index.php?mod=est&form=est_rel_kardex&opcao=imprimir&submenu=relatorioKardex&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioKardexSintetico() {
    if(document.lancamento.codProduto.value == '' && document.lancamento.grupo.value == ''){
        alert('Informe um Produto ou um Grupo para gerar o relatório.');
        return false;
    }
    if(document.lancamento.ccusto.value == ''){
        alert('Informe o Centro de Custo para gerar o relatório.');
        return false;
    }

    montaLetraConsulta();
    window.open('index.php?mod=est&form=est_rel_kardex&opcao=imprimir&submenu=relatorioKardexSintetico&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioCurvaABC() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_curva_ABC&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioMovimentoEstoque() {
    
    if(document.lancamento.ccusto.value == ''){
        alert('Informe o Centro de Custo para gerar o relatório.');
        return false;
    }
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_movimento_estoque&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function relatorioMovimentoEstoqueCliente() {

    if(document.lancamento.pessoa.value == ''){
        alert('Selecione a Conta para gerar o relatório.');
        return false;
    }

    if(document.lancamento.ccusto.value == ''){
        alert('Informe o Centro de Custo para gerar o relatório.');
        return false;
    }
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_movimento_estoque_cliente&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function relatorioMovimentoEstoqueLocalizacao() {
    if(document.lancamento.localizacao.value == ''){
        swal({title: "Atenção!", text: "Selecione a localização.", icon: "warning", dangerMode: true });
        return false;
    }
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_movimento_estoque_localizacao&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value +'&localizacaoSelected=' + f.localizacaoSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioLocalizacaoGeral() {
    debugger
    if(document.lancamento.localizacao.value == ''){
        swal({title: "Atenção!", text: "Selecione a localização.", icon: "warning", dangerMode: true });
        return false;
    }

    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_movimento_estoque_localizacao&opcao=imprimir&submenu=geral&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value +'&localizacaoSelected=' + f.localizacaoSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
} 

function relatorioMaterialConsumoConta() {

    if(document.lancamento.pessoa.value == ''){
        alert('Selecione a Conta para gerar o relatório.');
        return false;
    }

    if(document.lancamento.ccusto.value == ''){
        alert('Informe o Centro de Custo para gerar o relatório.');
        return false;
    }
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_material_consumo_conta&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioEstoqueGeral() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_estoque_geral&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioCompras() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_compras_consultas&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioComprasEstoqueMin() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_compras_estoque_min&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function relatorioSugestoesCompras() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_compras_sugestoes&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function tabelaPrecos() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_tabela_precos&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}

function tabelaPrecosGrupo() {
    montaLetraConsulta();
    window.open('index.php?mod=est&form=rel_tabela_precos_grupo&opcao=imprimir&&letra=' + f.letra.value + 
    '&grupoSelected='+ f.grupoSelected.value +'&sitLSelected='+ f.sitLSelected.value +'&tipoLSelected=' + f.tipoLSelected.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
function submitBuscaGrupo() {
    document.lancamento.tipoGrupo.value = document.lancamento.tipoGrupo.value; 

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Tipo-Grupo", "true");
        },
        success: function (response) {
            debugger
            var result = $('<select></select>').append(response).find('#grupo').html();
            
            $("#grupo").html(result);

        }
    });
    return false;

}

function limpaDadosForm() {
    debugger
    f = document.lancamento;
    f.letra.value       = "";
    f.pessoa.value      = '';
    f.fornecedor.value  = '';
    f.nome.value        = '';
    f.codProduto.value  = '';
    f.unidade.value     = '';
    f.descProduto.value = '';
    f.dataReferencia.value = '1';
    f.tipoCurva.value = 'QUANT';
    f.tipoGrupo.value = "";
    f.grupoSelected.value = '';
    f.tipoLSelected.value = '';
    f.sitLSelected.value  = '';
    f.tipolanc.value      = '';
    f.sitlanc.value       = '';
    f.numNf.value         = '';

}