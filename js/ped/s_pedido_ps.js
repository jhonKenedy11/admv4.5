/* UTILITÁRIOS */
window.pedidoPsEstoqueOk = false;

function pedidoPsGetId(f) {
    var form = f || document.lancamento;
    if (!form) {
        return '';
    }
    var el = document.getElementById('pedidoPsId');
    if (el && el.value) {
        return el.value;
    }
    if (form.elements && form.elements['id']) {
        return form.elements['id'].value || '';
    }
    return '';
}

function pedidoPsResetEstoqueValidado() {
    window.pedidoPsEstoqueOk = false;
    var elVal = document.getElementById('estoqueValidado');
    if (elVal) {
        elVal.value = '0';
    }
}

function pedidoPsHabilitarConfirmarPedidoEncomenda() {
    window.pedidoPsEstoqueOk = true;
    var elVal = document.getElementById('estoqueValidado');
    if (elVal) {
        elVal.value = '1';
    }
}

function pedidoPsEstoqueValidacaoOk(data) {
    return !!(data && (data.estoqueOk === true || data.estoqueOk === 1 || data.estoqueOk === '1' || data.ok === true));
}

function pedidoPsMontarHtmlValidacaoEncomenda(data) {
    var estoqueOk = pedidoPsEstoqueValidacaoOk(data);
    var html = '<p style="text-align:left;margin-bottom:10px;">' + (data.mensagem || '') + '</p>';
    if (data.itens && data.itens.length > 0) {
        html += '<div style="max-height:280px;overflow:auto;"><table class="table table-condensed table-bordered" style="font-size:12px;margin:0;">';
            html += '<thead><tr><th>Produto</th><th class="text-center">Solic.</th><th class="text-center">Disp.</th><th class="text-center">Reserv.</th><th class="text-center">Falta</th><th class="text-center">OK</th></tr></thead><tbody>';
            data.itens.forEach(function (item) {
                var sol = parseFloat(item.solicitado);
                var disp = parseFloat(item.disponivel);
                var reserv = parseFloat(item.reservar);
                var falta = parseFloat(item.qtdFalta);
                if (isNaN(sol)) { sol = 0; }
                if (isNaN(disp)) { disp = 0; }
                if (isNaN(reserv)) { reserv = 0; }
                if (isNaN(falta)) { falta = Math.max(0, sol - disp); }
                html += '<tr' + (item.ok ? '' : ' style="background:#fff3cd;"') + '>';
                html += '<td><strong>' + item.codigo + '</strong> — ' + (item.descricao || '') + '</td>';
                html += '<td class="text-center">' + sol.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                html += '<td class="text-center">' + disp.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                html += '<td class="text-center">' + reserv.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                html += '<td class="text-center">' + falta.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                html += '<td class="text-center">' + (item.ok ? '<span class="text-success">Sim</span>' : '<span class="text-danger">Não</span>') + '</td>';
                html += '</tr>';
            });
        html += '</tbody></table></div>';
    }
    if (estoqueOk) {
        html += '<p style="margin-top:10px;text-align:left;"><strong>Estoque OK — pode confirmar como pedido.</strong></p>';
    } else if (pedidoPsEncomendaAtiva(data)) {
        html += '<p style="margin-top:10px;text-align:left;"><strong>Pode colocar em encomenda — a parte disponível será reservada.</strong></p>';
    }
    return { html: html, estoqueOk: estoqueOk };
}

function pedidoPsAjaxValidarEncomenda(callback) {
    var f = document.lancamento;
    var idPedido = pedidoPsGetId(f);
    if (!idPedido) {
        callback('Salve o pedido antes de validar.');
        return;
    }
    if (f.situacao.value !== '5' && f.situacao.value !== '13') {
        callback('Validação disponível para cotação ou encomenda.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'index.php?mod=ped&form=pedido_ps&submenu=ajax_validar_encomenda&opcao=blank',
        data: { id: idPedido },
        dataType: 'json'
    }).done(function (data) {
        if (!data) {
            pedidoPsResetEstoqueValidado();
            callback('Resposta inválida do servidor.');
            return;
        }
        var parsed = pedidoPsMontarHtmlValidacaoEncomenda(data);
        if (parsed.estoqueOk) {
            pedidoPsHabilitarConfirmarPedidoEncomenda();
        } else {
            pedidoPsResetEstoqueValidado();
        }
        callback(null, data, parsed);
    }).fail(function (xhr) {
        pedidoPsResetEstoqueValidado();
        var msg = 'Falha ao validar o estoque.';
        if (xhr && xhr.status) {
            msg += ' (' + xhr.status + ')';
        }
        callback(msg);
    });
}

function pedidoPsValidarCamposConfirmarPedido(f) {
    if (f.pessoa.value == "") {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Selecione um Cliente.', confirmButtonText: 'OK' });
        return false;
    }
    if (f.condPgto.value == "" || f.condPgto.value == "0") {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Selecione uma Condição de Pagamento.', confirmButtonText: 'OK' });
        return false;
    }
    if (f.usrAbertura.value == "") {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Selecione um Vendedor.', confirmButtonText: 'OK' });
        return false;
    }
    var tableProdutos = document.getElementById("datatable-buttons-pecas");
    var rowProduto = tableProdutos ? tableProdutos.rows.length : 0;
    var tableServicos = document.getElementById("datatable-buttons-servicos");
    var rowServico = tableServicos ? tableServicos.rows.length : 0;
    if (rowProduto <= 1 && rowServico <= 1) {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Insira um produto ou um serviço para realizar um pedido.', confirmButtonText: 'OK' });
        return false;
    }
    if (f.os.value != '0' && f.catEquipamentoId.value == '') {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Selecione um Equipamento para realizar um pedido.', confirmButtonText: 'OK' });
        return false;
    }
    return true;
}

function submitSearch() {
    
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    if ((f.pesProduto.value == "") && (f.pesLocalizacao.value == "") && (f.grupo.value == "") && (prom == '') ){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Faça algum filtro de pesquisa.',
            confirmButtonText: 'OK'
        });
        return false;
    }else{
        f.pesq.value = f.pesProduto.value + '|' + f.grupo.value + '|' + f.promocoes.value + '|' + f.pesLocalizacao.value;
        f.submit();
    }
        
} 
/**  ORDEM DE SERVICO  */

function submitConfirmarSmart() {
    f = document.lancamento;
    if (f.pessoa.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Cliente.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    
    if (f.condPgto.value == "" || f.condPgto.value == "0" ) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione uma Condição de Pagamento.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(f.usrAbertura.value == ""){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Vendedor.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    var tableProdutos = document.getElementById("datatable-buttons-pecas");
    var rowProduto = tableProdutos.rows.length;

    var tableServicos = document.getElementById("datatable-buttons-servicos");
    var rowServico = tableServicos.rows.length;

    
    if (rowProduto <= 1 && rowServico <= 1 ) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Insira um produto ou um serviço para realizar um pedido.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    if(f.os.value != '0'){
        if(f.catEquipamentoId.value == ''){
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Selecione um Equipamento para realizar um pedido.',
                confirmButtonText: 'OK'
            });
            return false;
        }
    }

    Swal.fire({
        title: 'Deseja realmente ' + f.submenu.value + ' este Pedido',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            if (f.submenu.value == "cadastrar") {
                    f.submenu.value = 'altera';
                } else {
                    f.submenu.value = 'altera';
                }
            f.submit();
        }
    }); 
        
} // submitConfirmarSmart


function submitDigitacao() {
    f = document.lancamento;
    f.submenu.value = 'digita';
    f.submit();
} // fim submitVoltar


function submitVoltar() {
    f = document.lancamento;
    let id = f.id.value;

    if(id != ''){
        f.id.value = id;
        f.metodo.value = 'voltarPedidoPs';
    }
    
    f.submenu.value = '';

    f.submit();
} // fim submitVoltar

function submitGerarOs(id) {
    f = document.lancamento;
    f.submenu.value = 'gerarOs';
    f.id.value = id
    f.submit();
} // fim submitGerarOs

function submitEstornarOs(id) {
    f = document.lancamento;
    f.submenu.value = 'estornarOs';
    f.id.value = id
    f.submit();
} // fim submitGerarOs

function submitLetra() {
    
    f = document.lancamento;
    f.letra.value = '';
    f.submenu.value = 'pesquisa';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|"  + f.numAtendimento.value;
    
    // situacao Atendimento  
    f.situacoesAtendimento.value = concatCombo(situacaoCombo);
    
    // vendedor
    f.vendedorSelected.value = concatCombo(vendedor);

    // condicao de pagamento
    f.condPagamentoSelected.value = concatCombo(condPag);

    // motivo venda perdida (filtro)
    f.motivoSelected.value = concatCombo(motivo);
    
    f.submit();
} // fim submitLetra

function vendaPerdida(cotacao) {
    $("#cotacao").val(cotacao);
}

function salvarMotivoNoPedido(id) {
    f = document.lancamento;
    first = true;
    motivo = "";
    for (var i = 0; i < motivoPerdido.options.length; i++) {
        if (motivoPerdido[i].selected == true) {
            if (first == true) {
                first = false;
                motivo = motivoPerdido[i].value;
            } else motivo = motivo + "," + motivoPerdido[i].value;
        }
    }
    f.motivoSelected.value = motivo;
    f.id.value = id;
    f.submenu.value = "motivoGeral";
    f.submit();
}

function submitCadastro() {
    
    f = document.lancamento;
    f.submenu.value = 'cadastrar';
    f.pessoa.value = '';
    f.submit();
} // submitCadastro

function submitAlterar(id, situacao, pessoa) {
    f = document.lancamento;
    f.submenu.value = 'alterar';
    f.id.value = id;
    f.situacao.value = situacao;
    f.pessoa.value = pessoa;
    f.submit();
} // submitAlterar

function submitCancelar(id) {
    Swal.fire({
        title: 'Deseja realmente Cancelar este Pedido',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'cancela';
            f.id.value = id;
            f.submit();
        }
    });
} // submitExcluir

function submitExcluir(id) {
    Swal.fire({
        title: 'Deseja realmente Excluir este pedido',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
            f = document.lancamento;
            f.submenu.value = 'exclui';
            f.id.value = id;
            f.submit();
        }
    });
} // submitExcluir

function submitExcluirPeca(idPeca) {
    Swal.fire({
        title: 'Deseja realmente Excluir este item',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
        f = document.lancamento;
        f.submenu.value = 'excluiPeca';
        f.idPecas.value = '';
        f.idPecas.value = idPeca;
        f.submit();
        }
    });
} // submitExcluir

function submitExcluirServico(idServico) {
    Swal.fire({
        title: 'Deseja realmente Excluir este item',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {        
        f = document.lancamento;
        f.submenu.value = 'excluiServico';
        f.idServicos.value = '';
        f.idServicos.value = idServico;
        f.submit();
        }
    });
} // submitExcluir

function abrir(pag, form=null)
{
    if(form === 'pedidoPS'){
        screenWidth = 900;
        screenHeight = 650;
    }else{
        screenWidth = 750;
        screenHeight = 650;
    }
    
    if(form == 'produto'){
        if(document.lancamento.pessoa.value == ''){
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Selecione o Cliente antes de fazer a pesquisa',
                confirmButtonText: 'OK'
            });
            return false;
        }
        screenWidth = screen.width;
        screenHeight = screen.height;
        newPage = pag + '&acao='+document.lancamento.opcao_item.value;
        pag = '';
        pag = newPage;
    }
    if(form == 'servicos'){
        if(document.lancamento.pessoa.value == ''){
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Selecione o Cliente antes de fazer a pesquisa',
                confirmButtonText: 'OK'
            });
            return false;
        }

        screenWidth = screen.width;
        screenHeight = screen.height;
    }
    
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width='+screenWidth+',height='+screenHeight+',scrollbars=yes');
}

function calculaTotal(){
    
    var f = document.lancamento;
    var pecas           = f.valorPecas.value == '' ? '0,00' : f.valorPecas.value;
    var servicos        = f.valorServicos.value == '' ? '0,00' : f.valorServicos.value;
    var frete           = f.valorFrete.value == '' ? '0,00' : f.valorFrete.value;
    var despAcessorias  = f.valorDespAcessorias.value == '' ? '0,00' : f.valorDespAcessorias.value;
    var desconto        = f.valorDesconto.value == '' ? '0,00' : f.valorDesconto.value;
    var total           = 0;

    pecas          = parseFloat(pecas.replace(".","").replace(",","."));
    servicos       = parseFloat(servicos.replace(".","").replace(",","."));
    frete          = parseFloat(frete.replace(".","").replace(",","."));
    despAcessorias = parseFloat(despAcessorias.replace(".","").replace(",","."));
    desconto       = parseFloat(desconto.replace(".","").replace(",","."));

    total     = ((pecas + servicos + frete + despAcessorias) - desconto); 
    if(total == NaN){
        total = 0
    }else if(total == undefined){
        total = 0
    }else if (total == Infinity){
        total = 0
    }else{

    }
    f.valorTotal.value = currencyFormat(total);
    var valorTotalInput = document.getElementById('valorTotal');
    if (valorTotalInput) {
        var event = new Event('input', { bubbles: true });
        valorTotalInput.dispatchEvent(event);
    }
}

function currencyFormat (num) {
    return num
       .toFixed(2) // always two decimal digits
       .replace(".", ",") // replace decimal point character with ,
       .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") // use . as a separator
}

/** BR ou ponto decimal (envio POST + desconto geral). */
function pedidoPsMoedaNum(s) {
    s = String(s == null ? '' : s).trim();
    if (!s) return 0;
    return parseFloat(s.indexOf(',') < 0 ? s : s.replace(/\./g, '').replace(',', '.')) || 0;
}

function bindPedidoPsMoedaNoEnvio() {
    var f = document.getElementById('lancamento');
    if (!f || f.dataset.psMoedaEnvio) return;
    f.dataset.psMoedaEnvio = '1';
    var fix = function () {
        'valorPecas valorServicos valorFrete valorDespAcessorias valorDesconto valorTotal'.split(' ').forEach(function (n) {
            var el = f.elements[n];
            if (!el || el.disabled) return;
            var t = String(el.value).trim();
            if (t) el.value = currencyFormat(pedidoPsMoedaNum(t));
        });
    };
    f.addEventListener('submit', fix);
    var nativeSubmit = HTMLFormElement.prototype.submit;
    f.submit = function () { fix(); nativeSubmit.call(f); };
}

// concatena combo com pipes
function concatCombo(combo){
    valor = '';
    for  (var i=0; i < combo.options.length; i++){  
        if (combo[i].selected == true){  
            valor = valor + "|" + combo[i].value; 	}}
    return valor;
}



  function editarModalPeca(e){
                
    var linha = $(e).closest("tr");

    var id = linha.find("td:eq(0)").text().trim(); 
    var codigo = linha.find("td:eq(1)").text().trim(); 
    var descricao = linha.find("td:eq(2)").text().trim(); 
    var unidade = linha.find("td:eq(3)").text().trim();        
    var quantidade = linha.find("td:eq(4)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(5)").text().trim(); 
    var percDesconto = linha.find("td:eq(6)").text().trim();
    var vlrDesconto = linha.find("td:eq(7)").text().trim();
    var totalitem = linha.find("td:eq(8)").text().trim();
            
    $("#mIdPeca").val(id);
    $("#mCodPeca").val(codigo);
    $("#mDescPeca").val(descricao);
    $("#mUniPeca").val(unidade);
    $("#mQtdePeca").val(quantidade);
    $("#mVlrUniPeca").val(vlrUnitario);
    $("#mPercDescPeca").val(percDesconto);
    $("#mDescontoPeca").val(vlrDesconto);
    $("#mTotalPeca").val(totalitem);  
}

function editarModalServico(e){
                
    var linha = $(e).closest("tr");

    var codigo = linha.find("td:eq(0)").text().trim(); 
    var descricao = linha.find("td:eq(1)").text().trim(); 
    var unidade = linha.find("td:eq(2)").text().trim();        
    var quantidade = linha.find("td:eq(3)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(4)").text().trim();
    var totalitem = linha.find("td:eq(5)").text().trim();
            
    $("#mIdServico").val(codigo);
    $("#mDescServico").val(descricao);
    $("#mUniServico").val(unidade);
    $("#mQtdeServico").val(quantidade);
    $("#mVlrUniServico").val(vlrUnitario);
    $("#mTotalServico").val(totalitem);  
}



function submitAlteraPeca(){
    f = document.lancamento;
    f.letra_peca.value = '';
    f.letra_peca.value = f.mIdPeca.value + "|" + f.mCodPeca.value + "|" + f.mDescPeca.value + "|" + f.mUniPeca.value + 
    "|" + f.mQtdePeca.value + "|" + f.mVlrUniPeca.value + "|" + f.mPercDescPeca.value + "|" + f.mDescontoPeca.value +
    "|" + f.mTotalPeca.value;
    f.submenu.value = 'alteraPeca';
    f.submit()
}

function submitAlteraServico(){
    f = document.lancamento;
    f.letra_servico.value = '';
    f.letra_servico.value = f.mIdServico.value + "|" + f.mDescServico.value + "|" + f.mUniServico.value + 
    "|" + f.mQtdeServico.value + "|" + f.mVlrUniServico.value +"|" + f.mTotalServico.value;
    f.submenu.value = 'alteraServico';
    f.submit()
}

function calculaTotalItens(campo = '', modal=''){
    var f = document.lancamento;
    if(modal == 'pecas'){
        if(f.quantidadePecas.value == '0,00' || f.quantidadePecas.value == ''){
            return false;
        }
        if (f.vlrUnitarioPecas.value == '0,00' || f.vlrUnitarioPecas.value ==  ''){
            return false;
        }
        var vlrQtde     = f.quantidadePecas.value ;
        var unitario    = f.vlrUnitarioPecas.value;
        var descontoStr = campo == 'desconto' ? (f.vlrDescontoPecas.value || '') : '';
        var percDescontoStr = f.percDescontoPecas.value || '';

        var desconto = descontoStr ? parseFloat(descontoStr.replace(".","").replace(",",".")) : 0;
        var vlrPercdesconto = (campo == 'desconto' || percDescontoStr === '') ? 0 : parseFloat(percDescontoStr.replace(".","").replace(",","."));
        if (isNaN(desconto)) desconto = 0;
        if (isNaN(vlrPercdesconto)) vlrPercdesconto = 0;
    }else{
        if(f.quantidadeServico.value == '0,00' || f.quantidadeServico.value == ''){
            return false;
        }
        if (f.vlrUnitarioServico.value == '0,00' || f.vlrUnitarioServico.value ==  ''){
            return false;
        }
        var vlrQtde     = f.quantidadeServico.value ;
        var unitario    = f.vlrUnitarioServico.value;
    }
    
    var total     = 0;

    vlrQtde          = parseFloat(vlrQtde.replace(".","").replace(",","."))
    unitario         = parseFloat(unitario.replace(".","").replace(",","."))
    

    totalItem     = (vlrQtde * unitario);
    if(modal == 'pecas'){
        if(campo == 'desconto'){
            vlrPercdesconto = totalItem > 0 ? ((desconto * 100) / totalItem) : 0;
        } else if (vlrPercdesconto > 0) {
            desconto = ((totalItem * vlrPercdesconto) / 100);

            //nova logica para arredondamento
            //multiplica por 1000 para mover a casa decimal para a parte inteira e utilizo o math.roun para arredondar para o inteiro mais proximo
            var numeroMultiplicado = Math.round(desconto * 1000);
            //obtem o resto do valor para obter a 3 casa decimal.
            var terceiraCasaDecimal = numeroMultiplicado % 10;
            // Arredonda para cima se a terceira casa decimal for maior ou igual a 5
            if (terceiraCasaDecimal >= 5) {
                roundedValue = Math.ceil(desconto * 100) / 100;
            } else {
                roundedValue = Math.floor(desconto * 100) / 100;
            }

            desconto = roundedValue;
        } else {
            desconto = 0;
        }
        resultTotal = (totalItem - desconto);
    }else{
        resultTotal = totalItem
    }
    
    total = currencyFormat(resultTotal);

    if(total === 'NaN' || total === undefined || total === Infinity){
        total = 0
    }

    if(modal == 'pecas'){
        f.totalPecas.value = total;
        if (desconto > 0 || vlrPercdesconto > 0) {
            f.vlrDescontoPecas.value = currencyFormat(desconto);
            f.percDescontoPecas.value = currencyFormat(vlrPercdesconto);
        } else {
            f.vlrDescontoPecas.value = '';
            f.percDescontoPecas.value = '';
        }
    }else{
        f.totalServico.value = total;
    }
}
/** PECAS */

/** Foco nos campos de inclusão de peças (usado também pela janela de pesquisa de produto). */
function focarCampoPecas(idCampo) {
    setTimeout(function () {
        var el = document.getElementById(idCampo);
        if (!el || el.disabled || el.readOnly) {
            return;
        }
        el.focus();
        if (el.type === 'text' && typeof el.select === 'function') {
            el.select();
        }
    }, 80);
}

function sincronizarVendedorPedidoPs(idRepresentante, nomeVendedor) {
    var id = String(idRepresentante || '').trim();
    if (!id) {
        return;
    }

    var nome = nomeVendedor || '';
    var $select = $('select[name="usrAbertura"]');
    var $input = $('input[name="usrAbertura"]');
    var $disabled = $('select[name="usrAberturaDisabled"]');

    if ($disabled.length) {
        $disabled.html('<option value="' + id + '" selected>' + nome + '</option>');
    }
    if ($input.length) {
        $input.val(id);
    }
    if ($select.length) {
        if (!$select.find('option').filter(function () { return String($(this).val()) === id; }).length) {
            $select.append($('<option>', { value: id, text: nome || id }));
        }
        $select.val(id).trigger('change');
    }
}

function submitConfirmarPecas() {
    
    //validações 
    if (document.lancamento.quantidadePecas.value == '' || document.lancamento.quantidadePecas.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Quantidade para incluir o Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (document.lancamento.vlrUnitarioPecas.value == '' || document.lancamento.vlrUnitarioPecas.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Valor Unitário para incluir o Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(!document.lancamento.usrAbertura || $.trim(document.lancamento.usrAbertura.value) == ''){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione o Vendedor para incluir o Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    // Proteção contra duplo clique - desabilita o botão
    var btnConfirmar = $("#btnConfirmarPecas");
    var textoOriginal = ''; // Variável no escopo da função
    
    if (btnConfirmar.length === 0) {
        // Se o botão não existe, continua normalmente (não deve acontecer, mas previne erro)
        console.warn('Botão btnConfirmarPecas não encontrado');
    } else {
        // Verifica se já está processando
        if (btnConfirmar.prop('disabled')) {
            return false; // Já está processando, não permite novo clique
        }
        
        // Salva o texto original antes de modificar
        textoOriginal = btnConfirmar.html();
        
        // Desabilita o botão e altera o texto para indicar processamento
        btnConfirmar.prop('disabled', true);
        btnConfirmar.data('texto-original', textoOriginal); // Salva no data para garantir
        btnConfirmar.html('<span class="glyphicon glyphicon-refresh glyphicon-spin" aria-hidden="true"></span><span> Processando...</span>');
    }
    
    montaLetraPeca();

    var form = $("form[name=lancamento]");
    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Peca", "true");
        },
        success: function (response) {
            
            var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
            $("#datatable-buttons-pecas").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html();
            $("#divTotal").html(resultTotal);

            var idOs = $('<div />').append(response).find('#idAtendimento').html();
            $("#idAtendimento").html(idOs);

            limpaCamposPeca();
            
            // Reabilita o botão após sucesso
            if (btnConfirmar.length > 0) {
                btnConfirmar.prop('disabled', false);
                // Recupera o texto original do data ou usa o salvo
                var textoOriginalRestaurado = btnConfirmar.data('texto-original') || textoOriginal || '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Confirmar</span>';
                btnConfirmar.html(textoOriginalRestaurado);
            }

        },
        error: function (xhr, status, error) {
            // Reabilita o botão em caso de erro
            if (btnConfirmar.length > 0) {
                btnConfirmar.prop('disabled', false);
                // Recupera o texto original do data ou usa o salvo
                var textoOriginalRestaurado = btnConfirmar.data('texto-original') || textoOriginal || '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Confirmar</span>';
                btnConfirmar.html(textoOriginalRestaurado);
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Ocorreu um erro ao processar a requisição. Por favor, tente novamente.',
                confirmButtonText: 'OK'
            });
        }
    });
    return false;

}


function editarPeca(e, nrItem){
                
    var linha = $(e).closest("tr");

    // Usa classes específicas para evitar problemas com índices
    var codigoProduto = linha.find("td.i_item_estoque").text().trim(); 
    var codFabricante = linha.find("td.i_item_fabricante").text().trim(); 
    var codNota = linha.find("td.i_codigo_nota").text().trim(); 
    var descricao = linha.find("td.i_decricao").text().trim();   
    var quantidade = linha.find("td.i_qtd_solicitada").text().trim(); 
    var vlrUnitario = linha.find("td.i_unitario").text().trim(); 
    var vlrDesconto = linha.find("td.i_desconto").text().trim();
    var percDesconto = linha.find("td.i_perc_desconto").text().trim();
    var totalitem = linha.find("td.i_total").text().trim();    
    var numeroOc = linha.find("td.i_numero_oc").text().trim();
    var nItemPed = linha.find("td.i_n_item_ped").text().trim();
    var dataEntrega = linha.find("td.i_data_entrega").text().trim();
    var unidade = linha.find("td.i_unidade").text().trim();
    
    document.lancamento.nrItem.value = nrItem;
    document.lancamento.opcao_item.value = 'altera';
    $("#codProduto").val(codigoProduto);
    $("#codFabricante").val(codFabricante);
    $("#codProdutoNota").val(codNota);
    $("#descProduto").val(descricao);
    $("#uniProduto").val(unidade);
    $("#quantidadePecas").val(quantidade);
    $("#vlrUnitarioPecas").val(vlrUnitario);
    $("#percDescontoPecas").val(percDesconto);
    $("#vlrDescontoPecas").val(vlrDesconto);
    $("#totalPecas").val(totalitem);
    $("#numeroOcPecas").val(numeroOc);
    $("#nItemPedPecas").val(nItemPed);
    $("#dataEntregaPeca").val(dataEntrega);
    
    // Abre o painel "Mais Informações" se estiver fechado
    var collapseInfo = $("#collapseInfoAdicionalPecas");
    if (!collapseInfo.hasClass("in") && !collapseInfo.hasClass("show")) {
        collapseInfo.collapse("show");
    }
}
function submitExcluiPeca(nrItem) {
    Swal.fire({
        title: 'Deseja realmente Excluir este Item ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
        document.lancamento.letra_peca.value = document.lancamento.id.value + "|" + 
            nrItem + "|" +
            document.lancamento.situacao.value;

            var form = $("form[name=lancamento]");
            $.ajax({
                type: "POST",
                url: form.action ? form.action : document.URL,
                data: $(form).serialize(),
                dataType: "text",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Ajax-Request-Exclui-Peca", "true");
                },
                success: function (response) {
                    var result = $('<div />').append(response).find('#datatable-buttons-pecas').html();
                    $("#datatable-buttons-pecas").html(result);
  
                    var resultTotal = $('<div />').append(response).find('#divTotal').html();
                    $("#divTotal").html(resultTotal);

                    limpaCamposPeca();

                }
            });
        }
    });
    return false;
}

function montaLetraPeca(){
    document.lancamento.letra_peca.value = document.lancamento.id.value + "|" + 
    document.lancamento.pessoa.value + "|" +
    document.lancamento.codProduto.value + "|" +
    document.lancamento.codProdutoNota.value + "|" +
    document.lancamento.descProduto.value + "|" +
    document.lancamento.uniProduto.value + "|" +
    document.lancamento.quantidadePecas.value + "|" +
    document.lancamento.vlrUnitarioPecas.value + "|" +
    document.lancamento.percDescontoPecas.value + "|" +
    document.lancamento.vlrDescontoPecas.value + "|" +
    document.lancamento.totalPecas.value + "|" +
    document.lancamento.situacao.value + "|" +
    document.lancamento.nrItem.value + "|" +
    document.lancamento.codFabricante.value + "|" +
    (document.lancamento.numeroOcPecas.value || '') + "|" +
    (document.lancamento.nItemPedPecas.value || '') + "|" +
    (document.lancamento.dataEntregaPeca.value || '');

}

function limpaCamposPeca(){
    document.lancamento.letra_peca.value = ''
    document.lancamento.codProduto.value = ''
    document.lancamento.codProdutoNota.value = ''
    document.lancamento.descProduto.value = ''
    document.lancamento.uniProduto.value  = ''
    document.lancamento.quantidadePecas.value = ''
    document.lancamento.vlrUnitarioPecas.value = ''
    document.lancamento.percDescontoPecas.value = '' 
    document.lancamento.vlrDescontoPecas.value = ''
    document.lancamento.totalPecas.value = ''
    document.lancamento.nrItem.value = ''
    document.lancamento.opcao_item.value = ''
    document.lancamento.codFabricante.value = ''
    document.lancamento.numeroOcPecas.value = ''
    document.lancamento.nItemPedPecas.value = ''
    document.lancamento.dataEntregaPeca.value = ''
    $("#secaoEdicaoItem").slideUp();
    $("#listaEquivalencias").empty();
    limpaAbasEquivalenciasPedidoPs();
    focarCampoPecas('codFabricante');
}

/*  SERVICOS   */

function buscaServicoAjax() {
    var f = document.lancamento;
    var termo = $("#termoPesquisaServico").val();

    if (!f.pessoa || f.pessoa.value === '' || f.pessoa.value === '0') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Cliente antes de pesquisar Serviço.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!f.condPgto || f.condPgto.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione uma Condição de Pagamento antes de pesquisar Serviço.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!termo || termo.trim() === '') {
        return false;
    }
    if (termo.trim().length < 3) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Digite pelo menos 3 caracteres para realizar a pesquisa.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    $("#spinnerPesquisaServico").show();

    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_ps&submenu=busca_servico&opcao=blank",
        data: {
            termoPesquisaServico: termo.trim()
        },
        dataType: "json"
    }).done(function(response) {
        $("#spinnerPesquisaServico").hide();

        if (response && response.success) {
            f.codServico.value = '';
            f.idServicos.value = '';
            f.descricaoServico.value = '';
            f.unidadeServico.value = '';
            f.vlrUnitarioServico.value = '0,00';
            f.totalServico.value = '0,00';

            if (response.htmlServicos && response.htmlServicos.trim() !== '') {
                exibirServicosPedidoPs(response.htmlServicos, response.preencherAutomatico);
            } else {
                $("#secaoEdicaoServico").slideUp();
                $("#listaServicosEncontrados").empty();
            }
        } else {
            var urlPesquisa = document.URL + '?mod=cat&form=servico&opcao=pesquisar&origem=pedido_ps&letra=||'
                + encodeURIComponent(termo.trim());
            window.open(urlPesquisa, 'servicos', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
        }
    }).fail(function(xhr, status, error) {
        $("#spinnerPesquisaServico").hide();
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao buscar serviço: ' + (error || 'Erro desconhecido'),
            confirmButtonText: 'OK'
        });
    });
}

function atualizarLinhaServicoSelecionadoPs() {
    $("#listaServicosEncontrados tr.linhaServicoEncontrado").removeClass('info');
    $(".checkboxServico:checked").closest('tr.linhaServicoEncontrado').addClass('info');
}

function vincularCliqueLinhaServicoPs() {
    $("#listaServicosEncontrados").off('click.linhaServPs', 'tr.linhaServicoEncontrado');
    $("#listaServicosEncontrados").on('click.linhaServPs', 'tr.linhaServicoEncontrado', function(e) {
        if ($(e.target).closest('input.checkboxServico').length) {
            return;
        }
        var $cb = $(this).find('.checkboxServico').first();
        if (!$cb.length) {
            return;
        }
        if (!$cb.prop('checked')) {
            $cb.prop('checked', true);
        }
        $cb.trigger('change');
    });

    $(".checkboxServico").off('click.linhaServPs').on('click.linhaServPs', function(e) {
        e.stopPropagation();
    });
}

function preencherCamposServicoSelecionado($checkbox) {
    var f = document.lancamento;
    var codServico = $checkbox.data('cod-servico') || $checkbox.val() || '';
    var descricao = $checkbox.data('descricao') || '';
    var unidade = $checkbox.data('unidade') || '';
    var valorUnitario = $checkbox.data('valor-unitario') || '0,00';

    f.codServico.value = codServico;
    f.idServicos.value = '';
    f.descricaoServico.value = descricao;
    f.unidadeServico.value = unidade;
    f.vlrUnitarioServico.value = valorUnitario;
    f.quantidadeServico.value = f.quantidadeServico.value || '0,00';
    calculaTotalItens('', 'servico');
    $("#termoPesquisaServico").val(codServico);
}

function exibirServicosPedidoPs(htmlServicos, preencherAutomatico) {
    if (!htmlServicos || htmlServicos.trim() === '') {
        $("#secaoEdicaoServico").slideUp();
        $("#listaServicosEncontrados").empty();
        return;
    }

    $("#listaServicosEncontrados").html(htmlServicos);
    $("#secaoEdicaoServico").slideDown();
    vincularCliqueLinhaServicoPs();

    if (preencherAutomatico === true) {
        var $checkboxMarcado = $(".checkboxServico:checked").first();
        if ($checkboxMarcado.length > 0) {
            setTimeout(function() {
                $checkboxMarcado.trigger('change');
            }, 100);
        }
    }

    $(".checkboxServico").off('change.linhaServPs');
    $(".checkboxServico").on('change.linhaServPs', function() {
        if ($(this).is(':checked')) {
            $(".checkboxServico").not(this).prop('checked', false);
            preencherCamposServicoSelecionado($(this));
        } else {
            var f = document.lancamento;
            f.codServico.value = '';
            f.idServicos.value = '';
            f.descricaoServico.value = '';
            f.unidadeServico.value = '';
            f.vlrUnitarioServico.value = '0,00';
            f.totalServico.value = '0,00';
        }
        atualizarLinhaServicoSelecionadoPs();
    });

    atualizarLinhaServicoSelecionadoPs();
}

function submitConfirmarServicos() {
    //validações 
    if (document.lancamento.quantidadeServico.value == '' || document.lancamento.quantidadeServico.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Quantidade para incluir o Serviço.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (document.lancamento.vlrUnitarioServico.value == '' || document.lancamento.vlrUnitarioServico.value == '0,00') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Preencha o campo Valor Unitário para incluir o Serviço.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(!document.lancamento.usrAbertura || $.trim(document.lancamento.usrAbertura.value) == ''){
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione o Vendedor para incluir o Serviço.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    montaLetraServico();

    var form = $("form[name=lancamento]");

    $.ajax({
        type: "POST",
        url: form.action ? form.action : document.URL,
        data: $(form).serialize(),
        dataType: "text",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Ajax-Request-Cadastra-Servico", "true");
        },
        success: function (response) {
            var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
            $("#datatable-buttons-servicos").html(result);

            var resultTotal = $('<div />').append(response).find('#divTotal').html();
            $("#divTotal").html(resultTotal);

            var idOs = $('<div />').append(response).find('#idAtendimento').html();
            $("#idAtendimento").html(idOs);

            limpaCamposServicos();

        }
    });
    return false;
}

function editarServico(e, idServicos){
                
    var linha = $(e).closest("tr");

    var codigo      = linha.find("td:eq(0)").text().trim(); 
    var descricao   = linha.find("td:eq(1)").text().trim(); 
    var unidade     = linha.find("td:eq(2)").text().trim();        
    var quantidade  = linha.find("td:eq(3)").text().trim(); 
    var vlrUnitario = linha.find("td:eq(4)").text().trim(); 
    var totalitem   = linha.find("td:eq(5)").text().trim();
    var obsItemServico = linha.find("td:eq(6)").text().trim();

    
    document.lancamento.codServico.value = idServicos;
    $("#idServicos").val(codigo);
    $("#termoPesquisaServico").val(codigo);
    $("#descricaoServico").val(descricao);
    $("#unidadeServico").val(unidade);
    $("#quantidadeServico").val(quantidade);
    $("#vlrUnitarioServico").val(vlrUnitario);
    $("#totalServico").val(totalitem); 
    $("#obsItemServico").val(obsItemServico); 
}

function submitExcluiServico(idServicos) {
    Swal.fire({
        title: 'Deseja realmente Excluir este Item ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(result => {
        if (result.isConfirmed) {
        document.lancamento.letra_servico.value = document.lancamento.id.value + "|" + 
        idServicos + "|" +
        document.lancamento.situacao.value;

        var form = $("form[name=lancamento]");
        $.ajax({
            type: "POST",
            url: form.action ? form.action : document.URL,
            data: $(form).serialize(),
            dataType: "text",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Ajax-Request-Exclui-Servico", "true");
            },
            success: function (response) {
                var result = $('<div />').append(response).find('#datatable-buttons-servicos').html();
                $("#datatable-buttons-servicos").html(result);
    
                var resultTotal = $('<div />').append(response).find('#divTotal').html();
                $("#divTotal").html(resultTotal);
    
                limpaCamposServicos();
            }
        });
        }
    });
    return false;
}

function montaLetraServico(){
    document.lancamento.letra_servico.value = document.lancamento.id.value + "|" + 
    document.lancamento.pessoa.value + "|" +
    document.lancamento.codServico.value + "|" +
    document.lancamento.descricaoServico.value + "|" +
    document.lancamento.unidadeServico.value + "|" +
    document.lancamento.quantidadeServico.value + "|" +
    document.lancamento.vlrUnitarioServico.value + "|" +
    document.lancamento.totalServico.value + "|" +
    document.lancamento.situacao.value + "|" +
    document.lancamento.idServicos.value + "|" +
    document.lancamento.obsItemServico.value;
}

function limpaCamposServicos(){
    document.lancamento.letra_servico.value = ''
    document.lancamento.idServicos.value  = ''
    document.lancamento.codServico.value  = ''
    document.lancamento.descricaoServico.value = ''
    document.lancamento.unidadeServico.value = ''
    document.lancamento.quantidadeServico.value  = ''
    document.lancamento.vlrUnitarioServico.value = '' 
    document.lancamento.totalServico.value = ''
    document.lancamento.obsItemServico.value = ''
    $("#termoPesquisaServico").val('');
    $("#secaoEdicaoServico").slideUp();
    $("#listaServicosEncontrados").empty();
    var collapseInfo = document.getElementById('collapseInfoAdicional');
    if (collapseInfo) {
        collapseInfo.classList.remove('in');
    }
}



function submitCadastrarAtendimentoNf(id){
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'atendimento_nf';
    f.submenu.value = 'cadastrarNf';
    f.id.value = id;
    f.submit();
}

function submitDuplicarPedido(id) {
    f = document.lancamento;  
    f.submenu.value = 'duplicaPedido';
    f.id.value = id 
    f.submit();
}

function submitBaixarPedidoPs() {
    var f = document.lancamento;
    var sit = (f.situacao && f.situacao.value) ? f.situacao.value.toString() : '';
    if (sit === '9') {
        Swal.fire({ icon: 'info', title: 'Pedido', text: 'Este pedido já está baixado.' });
        return false;
    }
    if (sit === '8') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Pedido cancelado não pode ser baixado.' });
        return false;
    }
    Swal.fire({
        title: 'Baixar pedido?',
        text: 'A situação será alterada para Pedido baixado.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            f.situacao.value = '9';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function cadastraProduto(){
    f = document.lancamento;
    var letra = 'registerProd' + '|' + 
                f.codFabricante.value + '|' + 
                f.codProdutoNota.value + '|' + 
                f.descProduto.value + '|' + 
                f.uniProduto.value + '|' +
                f.vlrUnitarioPecas.value;

    window.open("index.php?mod=est&form=produto&opcao=imprimir&submenu=cadastrar&letra="+letra+"&parm=toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");
}

function buscaProdutoAjax() {
    var f = document.lancamento;
    var codFabricante = f.codFabricante.value;

    var sitOrdem = f.situacao ? String(f.situacao.value) : '';
    if (sitOrdem === '6' || sitOrdem === '8' || sitOrdem === '3' || sitOrdem === '9') {
        return false;
    }

    if (!f.pessoa || f.pessoa.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione um Cliente antes de fazer a pesquisa de Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!f.condPgto || f.condPgto.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Selecione uma Condição de Pagamento antes de fazer a pesquisa de Produto.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!codFabricante || codFabricante.trim() === '') {
        return false;
    }
    if (codFabricante.trim().length < 3) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Digite pelo menos 3 caracteres para realizar a pesquisa.',
            confirmButtonText: 'OK'
        });
        return false;
    }

    var pessoa = f.pessoa ? f.pessoa.value : '';
    $("#spinnerPesquisa").show();

    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_ps&submenu=busca_produto&opcao=blank",
        data: {
            codFabricante: codFabricante.trim(),
            pessoa: pessoa
        },
        dataType: "json"
    }).done(function(response) {
        $("#spinnerPesquisa").hide();

        if (response && response.success && response.produto) {
            var produto = response.produto;
            var preencherAutomatico = response.preencherAutomatico || false;

            f.codProduto.value = '';
            f.codProdutoNota.value = '';
            f.descProduto.value = '';
            f.uniProduto.value = '';
            f.vlrUnitarioPecas.value = '0,00';
            f.percDescontoPecas.value = '0,00';
            f.vlrDescontoPecas.value = '0,00';
            f.totalPecas.value = '0,00';

            if (response.htmlEquivalencias && response.htmlEquivalencias.trim() !== '') {
                exibirEquivalenciasPedidoPs(response.htmlEquivalencias, produto, preencherAutomatico);
            } else {
                $("#secaoEdicaoItem").slideUp();
                $("#listaEquivalencias").empty();
            }
        } else {
            var idPedido = (f.id && f.id.value) ? f.id.value : '';
            var urlPesquisa = document.URL + '?mod=est&form=produto&opcao=pesquisarpecas&from=pedido_ps&idPedido='
                + encodeURIComponent(idPedido) + '&letra=||' + encodeURIComponent(codFabricante.trim());
            window.open(urlPesquisa, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
        }
    }).fail(function(xhr, status, error) {
        $("#spinnerPesquisa").hide();
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao buscar produto: ' + (error || 'Erro desconhecido'),
            confirmButtonText: 'OK'
        });
    });
}

function atualizarLinhaEquivalenciaSelecionadaPs() {
    $("#listaEquivalencias tr.linhaEquivalenciaProduto").removeClass('info');
    $(".checkboxEquivalencia:checked").closest('tr.linhaEquivalenciaProduto').addClass('info');
}

function vincularCliqueLinhaEquivalenciaPs() {
    $("#listaEquivalencias").off('click.linhaEquivPs', 'tr.linhaEquivalenciaProduto');
    $("#listaEquivalencias").on('click.linhaEquivPs', 'tr.linhaEquivalenciaProduto', function(e) {
        if ($(e.target).closest('input.checkboxEquivalencia').length) {
            return;
        }
        var $cb = $(this).find('.checkboxEquivalencia').first();
        if (!$cb.length) {
            return;
        }
        if (!$cb.prop('checked')) {
            $cb.prop('checked', true);
        }
        $cb.trigger('change');
    });

    $(".checkboxEquivalencia").off('click.linhaEquivPs').on('click.linhaEquivPs', function(e) {
        e.stopPropagation();
    });
}

function exibirEquivalenciasPedidoPs(htmlEquivalencias, produtoPrincipal, preencherAutomatico) {
    if (!htmlEquivalencias || htmlEquivalencias.trim() === '') {
        $("#secaoEdicaoItem").slideUp();
        $("#listaEquivalencias").empty();
        return;
    }

    $("#listaEquivalencias").html(htmlEquivalencias);
    $("#secaoEdicaoItem").slideDown();
    vincularCliqueLinhaEquivalenciaPs();

    if (preencherAutomatico === true) {
        var $checkboxMarcado = $(".checkboxEquivalencia:checked").first();
        if ($checkboxMarcado.length > 0) {
            setTimeout(function() {
                $checkboxMarcado.trigger('change');
            }, 100);
        }
    }

    $(".checkboxEquivalencia").off('change.linhaEquivPs');
    $(".checkboxEquivalencia").on('change.linhaEquivPs', function() {
        var f = document.lancamento;
        if ($(this).is(':checked')) {
            var codEquivalente = $(this).data('cod-equivalente') || $(this).data('cod-fabricante') || $(this).data('cod-produto') || '';
            var codProduto = $(this).data('cod-produto') || $(this).val() || '';
            var descricao = $(this).data('descricao') || '';
            var unidade = $(this).data('unidade') || '';
            var valorVenda = $(this).data('valor-venda') || '0,00';

            $(".checkboxEquivalencia").not(this).prop('checked', false);

            if (codEquivalente) {
                f.codFabricante.value = codEquivalente;
                f.codProdutoNota.value = codEquivalente;
                f.codProduto.value = codProduto;
                f.descProduto.value = descricao;
                f.uniProduto.value = unidade;
                f.vlrUnitarioPecas.value = valorVenda;
                f.percDescontoPecas.value = '0,00';
                f.vlrDescontoPecas.value = '0,00';
                f.totalPecas.value = '0,00';

                if (codProduto) {
                    buscaInfoProdutoPedidoPs(codProduto, true);
                }
                f.quantidadePecas.value = '0,00';
                focarCampoPecas('quantidadePecas');
            }
        } else {
            f.codFabricante.value = '';
            f.codProdutoNota.value = '';
            f.codProduto.value = '';
            f.descProduto.value = '';
            f.uniProduto.value = '';
            f.vlrUnitarioPecas.value = '0,00';
            f.percDescontoPecas.value = '0,00';
            f.vlrDescontoPecas.value = '0,00';
            f.totalPecas.value = '0,00';
            limpaAbasEquivalenciasPedidoPs();

            var codProdutoDesmarcado = $(this).data('cod-produto') || $(this).val() || '';
            if (codProdutoDesmarcado) {
                $(".estoqueProduto[data-cod-produto='" + codProdutoDesmarcado + "']").html('<span class="text-muted" style="font-style: italic; font-size: 11px;">Selecione para mais informações</span>');
            }
        }
        atualizarLinhaEquivalenciaSelecionadaPs();
    });

    atualizarLinhaEquivalenciaSelecionadaPs();
}

function buscaInfoProdutoPedidoPs(codProduto, dentroEquivalencias) {
    codProduto = String(codProduto || '').trim();
    if (codProduto === '' || !dentroEquivalencias) {
        return;
    }

    var f = document.lancamento;
    var pessoa = f.pessoa ? f.pessoa.value : '';

    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_ps&submenu=info_produto&opcao=blank",
        data: {
            codProduto: codProduto,
            pessoa: pessoa,
            vlrUnitarioProduto: f.vlrUnitarioPecas ? f.vlrUnitarioPecas.value : '',
            quantidadeProduto: f.quantidadePecas ? f.quantidadePecas.value : ''
        },
        dataType: "json"
    }).done(function(response) {
        if (!response.success || !response.htmlInfo) {
            limpaAbasEquivalenciasPedidoPs();
            return;
        }

        var $htmlInfo = $('<div>').html(response.htmlInfo);
        var conteudoCompras = $htmlInfo.find('#abaCompras').html() || '';
        var conteudoVendas = $htmlInfo.find('#abaVendas').html() || '';
        var conteudoEquivalencias = $htmlInfo.find('#abaEquivalencias').html() || '';
        $("#abaComprasEquiv").html(conteudoCompras);
        $("#abaVendasEquiv").html(conteudoVendas);
        $("#abaEquivalencias").html(conteudoEquivalencias);

        if (response.quantidadeDisponivel !== undefined) {
            var qtdFormatada = parseFloat(response.quantidadeDisponivel || 0).toFixed(2).replace('.', ',');
            $(".estoqueProduto[data-cod-produto='" + codProduto + "']").html('<strong>' + qtdFormatada + '</strong>');
        }

        $("#liCompras").toggle(conteudoCompras.trim() !== '');
        $("#liVendas").toggle(conteudoVendas.trim() !== '');
        $("#liEquivalencias").toggle(conteudoEquivalencias !== '');
    }).fail(function() {
        limpaAbasEquivalenciasPedidoPs();
    });
}

function limpaAbasEquivalenciasPedidoPs() {
    $("#abaComprasEquiv, #abaVendasEquiv, #abaEquivalencias").empty();
    $("#liCompras, #liVendas, #liMarkup").hide();
}

//atualiza descontos
function atualizarInfo() {
    var valorTotal = document.getElementById('valorTotal');
    var f = document.lancamento;
    var id = f.id.value;
    
    // Verifica se tem ID do pedido para consultar situação no banco
    if (!id || id === '') {
        // Se não tem ID, verifica pela situação do select
        var situacaoSelect = document.querySelector('select[name="situacao"]');
        var situacaoAtual = situacaoSelect ? situacaoSelect.value : '0';
        
        if (situacaoAtual == '6' || situacaoAtual == '3') {
            swal.fire({
                title: "Atenção!",
                text: "Não é possível aplicar desconto em pedidos Faturados ou com NF a Emitir!",
                icon: "warning"
            });
            return;
        }
        prosseguirComDesconto();
    } else {
        // Consulta situação no banco via AJAX
        $.ajax({
            type: "POST",
            url: document.URL + "?mod=ped&form=pedido_ps&submenu=prosseguirComDesconto&opcao=blank",
            data: { id: id },
            dataType: "json",
            success: function(response) {
                if (response.situacao[0].SITUACAO == '6' || response.situacao[0].SITUACAO == '3') {
                    swal.fire({
                        title: "Atenção!",
                        text: "Não é possível aplicar desconto em pedidos Faturados ou com NF a Emitir!",
                        icon: "warning"
                    });
                    return;
                }else{
                    prosseguirComDesconto();
                }
            }
        });
    }
}

function prosseguirComDesconto() {
    var valorTotal = document.getElementById('valorTotal');
    var v = pedidoPsMoedaNum(valorTotal.value);

    if (isNaN(v) || v === 0) {
        return; // Não faz nada se não pode editar desconto
    }
    
    swal.fire({
        title: "Atenção?",
        text: "Ao realizar o desconto geral os descontos unitários serão recalculados!",
        icon: "warning",
        buttons: ["Cancelar", 'Continuar'],
    })
    .then((yes) => {
        if(yes){
            //salva o valor atual no localstorage
            localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);

            f = document.lancamento;

            var desconto = pedidoPsMoedaNum(f.valorDesconto.value);
            var total = pedidoPsMoedaNum(f.valorTotal.value);
        
            if (desconto > total) {
                 swal.fire({
                title:"Atenção!",
                text:"O desconto nao pode ser maior do que o valor total!",
                icon:"warning"});
                f.valorDesconto.value = "0,00";
                return false;
            }
            if (f.valorDesconto.value == "") {
                f.valorDesconto.value = "0,00";
            }

            f.submenu.value = "atualizarInfo";
            f.submit();
        }else{
            if(document.getElementsByName("id")[0].value == localStorage.getItem("idPedidoServico")){
                document.getElementById("valorDesconto").value = localStorage.getItem("vlrDescontoAnt");
            }

            return false;
        }
    });
} // prosseguirComDesconto

function guardaValorAnt(){
    localStorage.setItem("idPedidoServico", document.getElementsByName("id")[0].value);
    localStorage.setItem("vlrDescontoAnt", document.getElementById("valorDesconto").value);
  }

  function printRomaneio(id) {
    Swal.fire({
        title: 'Impressão',
        text: 'Selecione o tipo de impressão do pedido',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Loja',
        denyButtonText: 'Cliente',
        cancelButtonText: 'Cancelar'
    }).then(function(result) {
        if (result.isConfirmed) {
            window.open(
                "index.php?mod=ped&form=rel_pedido_ps&letra=loja&opcao=imprimir&id=" + id,
                "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
            );
        } else if (result.isDenied) {
            window.open(
                "index.php?mod=ped&form=rel_pedido_ps&letra=cliente&opcao=imprimir&letra=cliente&id=" + id,
                "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
            );
        }
    });
  }

function relatorioVendas(tipoRel) {
  
  montaLetraRelatorio();
  f.tipoRelatorio.value = tipoRel;
  window.open(
    "index.php?mod=ped&form=rel_pedidos&opcao=imprimir&submenu=relatorioVendas&letra=" +
      f.letra.value +
      "&situacaoSelected=" +
      f.situacaoSelected.value +
      "&centroCustoSelected=" +
      f.centroCustoSelected.value +
      "&tipoRelatorio=" +
      f.tipoRelatorio.value +
      "&motivoSelected=" +
      f.motivoSelected.value +
      "&vendedorSelected=" +
      f.vendedorSelected.value +
      "&condPagamentoSelected=" +
      f.condPagamentoSelected.value,
    "consulta",
    "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
  );
}

function montaLetraRelatorio() {
    
    f = document.lancamento;
    f.letra.value =
      f.numAtendimento.value +
      "|" +
      f.dataIni.value +
      "|" +
      f.dataFim.value +
      "|" +
      f.codProduto.value +
      "|" +
      f.pessoa.value;
    // situacao
    f.situacaoSelected.value = concatCombo(situacaoCombo);
    // ccusto
    f.centroCustoSelected.value = concatCombo(centroCusto);
    //motivo
    f.motivoSelected.value = concatCombo(motivo);
    // vendedor
    f.vendedorSelected.value = concatCombo(vendedor);
    // condPagamento
    f.condPagamentoSelected.value = concatCombo(condPag);
}

function relatorioFaturaSintetico() {
    
    montaLetraRelatorio();
    window.open(
      "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=relatorioFaturaSintetico&letra=" +
        f.letra.value +
        "&situacaoSelected=" +
        f.situacaoSelected.value +
        "&centroCustoSelected=" +
        f.centroCustoSelected.value +
        "&tipoRelatorio=" +
        f.tipoRelatorio.value +
        "&motivoSelected=" +
        f.motivoSelected.value +
        "&vendedorSelected=" +
        f.vendedorSelected.value +
        "&condPagamentoSelected=" +
        f.condPagamentoSelected.value,
      "consulta",
      "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
    );
  }
  
  function relatorioFaturaAnalitico() {
    
    montaLetraRelatorio();
    window.open(
      "index.php?mod=ped&form=rel_pedidos_lanc_fatura&opcao=imprimir&submenu=&letra=" +
        f.letra.value +
        "&situacaoSelected=" +
        f.situacaoSelected.value +
        "&centroCustoSelected=" +
        f.centroCustoSelected.value +
        "&tipoRelatorio=" +
        f.tipoRelatorio.value +
        "&motivoSelected=" +
        f.motivoSelected.value +
        "&vendedorSelected=" +
        f.vendedorSelected.value +
        "&condPagamentoSelected=" +
        f.condPagamentoSelected.value,
      "consulta",
      "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes"
    );
  }

// concatena combo com pipes
function concatCombo(combo) {
    valor = "";
    for (var i = 0; i < combo.options.length; i++) {
      if (combo[i].selected == true) {
        valor = valor + "|" + combo[i].value;
      }
    }
    return valor;
}

function aplicarSaldoCreditoPedidoPs(response, clienteId) {
    var $nomeCol = $('#div_nome_cliente_pedido_ps');
    var $box = $('#div_saldo_credito_cliente');
    var $inp = $('#saldo_credito_cliente');
    var $boxLimite = $('#div_limite_credito_cliente');
    var $inpLimite = $('#saldo_limite_credito_cliente');
    var $badgeBloqueado = $('#badge_cliente_bloqueado_ps');
    if (!clienteId || String(clienteId).trim() === '') {
        if ($nomeCol.length) {
            $nomeCol.removeClass('col-md-4').addClass('col-md-6');
        }
        if ($box.length) {
            $box.hide();
        }
        if ($boxLimite.length) {
            $boxLimite.hide();
        }
        if ($badgeBloqueado.length) {
            $badgeBloqueado.hide();
        }
        return;
    }
    var saldoNum = 0;
    if (response && response.saldo_credito !== undefined && response.saldo_credito !== null) {
        saldoNum = parseFloat(response.saldo_credito);
    }
    var limiteNum = 0;
    if (response && response.limite_credito !== undefined && response.limite_credito !== null) {
        limiteNum = parseFloat(response.limite_credito);
    }
    var clienteBloqueado = !!(response && response.cliente_bloqueado);
    var mostraCredito = !isNaN(saldoNum) && saldoNum > 0;
    var mostraLimite = !isNaN(limiteNum) && limiteNum > 0;
    if ($nomeCol.length) {
        if (mostraCredito || mostraLimite || clienteBloqueado) {
            $nomeCol.removeClass('col-md-6').addClass('col-md-4');
        } else {
            $nomeCol.removeClass('col-md-4').addClass('col-md-6');
        }
    }
    if ($box.length && $inp.length) {
        if (mostraCredito) {
            $inp.val('R$ ' + (response.saldo_credito_formatado || '0,00'));
            $box.show();
        } else {
            $box.hide();
        }
    }
    if ($boxLimite.length && $inpLimite.length) {
        if (mostraLimite) {
            $inpLimite.val('R$ ' + (response.saldo_limite_disponivel_formatado || '0,00'));
            $boxLimite.show();
        } else {
            $boxLimite.hide();
        }
    }
    if ($badgeBloqueado.length) {
        if (clienteBloqueado) {
            $badgeBloqueado.show();
        } else {
            $badgeBloqueado.hide();
        }
    }
}

// Função para carregar obras e responsáveis técnicos via AJAX
function carregarObras(clienteId) {
    if (!clienteId || clienteId === '') {
        aplicarSaldoCreditoPedidoPs(null, '');
        return;
    }
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_ps&submenu=ajax_obra&opcao=blank",
        data: {
            cliente_id: clienteId
        },
        dataType: "json",
        success: function(response) {
            var obras = response.obras || [];
            var responsaveis = response.responsaveis || [];
            aplicarSaldoCreditoPedidoPs(response, clienteId);
            // Controla visibilidade e carrega obras
            if (obras.length === 0) {
                $('#obra').html('<option value="">Nenhuma obra encontrada</option>');
                $('#div_obra').hide(); // Esconde o campo de obra
            } else {
                $('#obra').html('<option value="">Selecione a Obra</option>');
                for(var i = 0; i < obras.length; i++) {
                    $('<option>').val(obras[i].ID).text(obras[i].PROJETO).appendTo('#obra');
                }
                $('#div_obra').show(); // Mostra o campo de obra
                // Ajusta tamanho da condição de pagamento quando há obras
                $('#div_cond_pgto').removeClass('col-lg-6').addClass('col-lg-2');
            }
            
            // Carrega responsáveis técnicos (sempre carrega, mas controla visibilidade)
            if (responsaveis.length === 0) {
                $('#responsavel_tecnico').html('<option value="">Nenhum responsável técnico encontrado</option>');
            } else {
                $('#responsavel_tecnico').html('<option value="">Selecione o Responsável Técnico</option>');
                for(var i = 0; i < responsaveis.length; i++) {
                    $('<option>').val(responsaveis[i].ID).text(responsaveis[i].NOME).appendTo('#responsavel_tecnico');
                }
            }
            
            // Esconde o campo de responsável técnico inicialmente
            $('#div_responsavel_tecnico').hide();
            
            // Carrega endereços de entrega com posicionamento baseado na presença de obras
            carregarEnderecos(clienteId, obras);
        },
        error: function() {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao carregar dados. Por favor, tente novamente.',
            confirmButtonText: 'OK'
        });
        }
    });
}

// Função para controlar visibilidade do campo de responsável técnico
function carregarResponsaveisTecnicos(obraId) {
    if (obraId === '' || obraId === null) {
        // Se não há obra selecionada, esconde o campo de responsável técnico
        $('#div_responsavel_tecnico').hide();
    } else {
        // Se há obra selecionada, mostra o campo de responsável técnico
        $('#div_responsavel_tecnico').show();
    }
}

// Função para inicializar a visibilidade dos campos baseada nos dados do PHP
function inicializarCamposObra() {
    // Verifica se há obras carregadas
    var obraSelect = document.getElementById('obra');
    if (obraSelect && obraSelect.options.length > 1) {
        $('#div_obra').show();
        
        // Verifica se há obra selecionada
        var obraSelecionada = obraSelect.value;
        if (obraSelecionada && obraSelecionada !== '') {
            $('#div_responsavel_tecnico').show();
        }
    } else {
        $('#div_obra').hide();
        $('#div_responsavel_tecnico').hide();
    }
    
    // Inicializar visibilidade do campo de endereço de entrega
    var pessoaSelect = document.getElementById('pessoa');
    if (pessoaSelect && pessoaSelect.value !== '') {
        // Verifica se há obras para determinar posicionamento
        var obraSelect = document.getElementById('obra');
        var temObras = obraSelect && obraSelect.options.length > 1;
        
        // Ajusta tamanho da condição de pagamento baseado na presença de obras
        if (temObras) {
            $('#div_cond_pgto').removeClass('col-lg-6').addClass('col-lg-2');
        }
        
        carregarEnderecos(pessoaSelect.value, temObras ? [{ID: 1}] : []);
    } else {
        $('#div_endereco_entrega_lado').hide();
        $('#div_endereco_entrega_baixo').hide();
        // Restaura tamanho original da condição de pagamento
        $('#div_cond_pgto').removeClass('col-lg-2').addClass('col-lg-6');
    }
}


// Função para carregar endereços via AJAX
function carregarEnderecos(clienteId, obras) {
    if (!clienteId || clienteId === '') {
        aplicarSaldoCreditoPedidoPs(null, '');
        $('#div_endereco_entrega_lado').hide();
        $('#div_endereco_entrega_baixo').hide();
        return;
    }
    
    $.ajax({
        type: "POST",
        url: "index.php?mod=ped&form=pedido_ps&submenu=ajax_enderecos&opcao=blank",
        data: {
            cliente_id: clienteId
        },
        dataType: "json",
        success: function(response) {
            var enderecos = response.enderecos || [];
            aplicarSaldoCreditoPedidoPs(response, clienteId);
            if (enderecos.length > 0) {
                // Determina se deve mostrar ao lado (sem obras) ou embaixo (com obras)
                var temObras = obras && obras.length > 0;
                
                if (temObras) {
                    // Mostra embaixo (div_baixo)
                    $('#div_endereco_entrega_lado').hide();
                    $('#endereco_entrega_baixo').html('<option value="">Selecione o Endereço de Entrega</option>');
                    for(var i = 0; i < enderecos.length; i++) {
                        var endereco = enderecos[i];
                        $('<option>').val(endereco.ID).text(endereco.ENDERECO_ENTREGA).appendTo('#endereco_entrega_baixo');
                    }
                    // Seleciona o endereço já cadastrado se existir
                    var enderecoSelecionado = $('input[name="endereco_entrega"]').val();
                    if (enderecoSelecionado && enderecoSelecionado !== '') {
                        $('#endereco_entrega_baixo').val(enderecoSelecionado);
                    }
                    $('#div_endereco_entrega_baixo').show();
                } else {
                    // Mostra ao lado (div_lado) - ajusta condição de pagamento para col-lg-2
                    $('#div_endereco_entrega_baixo').hide();
                    $('#div_cond_pgto').removeClass('col-lg-6').addClass('col-lg-2');
                    $('#endereco_entrega_lado').html('<option value="">Selecione o Endereço de Entrega</option>');
                    for(var i = 0; i < enderecos.length; i++) {
                        var endereco = enderecos[i];
                        $('<option>').val(endereco.ID).text(endereco.ENDERECO_ENTREGA).appendTo('#endereco_entrega_lado');
                    }
                    // Seleciona o endereço já cadastrado se existir
                    var enderecoSelecionado = $('input[name="endereco_entrega"]').val();
                    if (enderecoSelecionado && enderecoSelecionado !== '') {
                        $('#endereco_entrega_lado').val(enderecoSelecionado);
                    }
                    $('#div_endereco_entrega_lado').show();
                }
            } else {
                $('#div_endereco_entrega_lado').hide();
                $('#div_endereco_entrega_baixo').hide();
                // Restaura tamanho original da condição de pagamento se não há endereços
                if (!obras || obras.length === 0) {
                    $('#div_cond_pgto').removeClass('col-lg-2').addClass('col-lg-6');
                }
            }
            // Sincroniza representante do cliente sem apagar o vendedor já definido (ex.: usuário logado).
            if (response.id_representante && response.id_representante.length > 0) {
                var representante = response.id_representante[0] || {};
                var idRep = representante.REPRESENTANTE;
                if (idRep) {
                    var $campo = $('input[name="usrAbertura"], select[name="usrAbertura"]');
                    var valorAtual = $campo.length ? String($campo.val() || '').trim() : '';
                    var modoBloqueado = $('select[name="usrAberturaDisabled"]').length > 0;
                    if (modoBloqueado || valorAtual === '') {
                        sincronizarVendedorPedidoPs(idRep, representante.NOME);
                    }
                }
            }
        },
        error: function() {
            $('#div_endereco_entrega_lado').hide();
            $('#div_endereco_entrega_baixo').hide();
        }
    });
}




function abrirRelatorioImpostos(id) {
    if (!id) return;
    window.open('index.php?mod=ped&form=pedido_ps&submenu=simulaImpostos&opcao=imprimir&id=' + id, "toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes");

}

function aplicarBloqueioPedidoPsCadastro() {
    var sit = document.lancamento.situacao.value;
    var $f = $('#lancamento');
    $f.off('submit.pedidoPsLiberaPost');
    if (sit !== '6' && sit !== '8' && sit !== '3' && sit !== '9' && sit !== '13') return;

    $f.find('#div_nome_cliente_pedido_ps button').prop('disabled', true);
    $f.find('#emissao').prop('readonly', true);
    $f.find('#datatable-buttons-pecas tbody .btn, #datatable-buttons-servicos tbody .btn').prop('disabled', true);
    $f.find('.btnCp, #btnConfirmarPecas, button[onclick*="pesquisarpecas"], button[onclick*="servico"]').prop('disabled', true);
    $f.find('#codFabricante').prop('readonly', true).addClass('not-active');
    if (sit === '8') {
        $f.find('#btnPedidoPs, #btnEstornoPedidoPs').prop('disabled', true);
    }
}

function submitReativarCotacaoPs() {
    var f = document.lancamento;
    var $f = $(f);
    if (f.situacao.value !== '8') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Reativação só está disponível para pedidos cancelados.' });
        return false;
    }
    Swal.fire({
        title: 'Reativar para cotação?',
        text: 'O pedido voltará ao estado de cotação.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo').prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '5';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function submitVoltarEmitirNfPs() {
    var f = document.lancamento;
    var $f = $(f);
    if (f.situacao.value !== '9') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Esta ação só está disponível para pedidos baixados.' });
        return false;
    }
    Swal.fire({
        title: 'Voltar para Emitir NF?',
        text: 'A situação será alterada para Emitir NF.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo').prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '3';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function submitVoltarPedidoPs() {
    var f = document.lancamento;
    var $f = $(f);
    if (f.situacao.value !== '3') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Esta ação só está disponível quando a situação é Emitir NF.' });
        return false;
    }
    Swal.fire({
        title: 'Voltar para Pedido?',
        text: 'A situação será alterada para Pedido.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo').prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '6';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function pedidoPsGetDadosDescontoFront() {
    var elAprov = document.getElementById('pedidoPsAprovacaoParam');
    var elValid = document.getElementById('pedidoPsValidarDescontoGeral');
    var elMax = document.getElementById('pedidoPsDescontoMaximo');
    var elPer = document.getElementById('pedidoPsPerDesconto');
    return {
        aprovacao: elAprov ? elAprov.value : 'N',
        validarDescontoGeral: elValid ? elValid.value : 'N',
        descontoMaximo: elMax ? elMax.value : '0',
        perDesconto: elPer ? elPer.value : '0'
    };
}

function pedidoPsValidarDescontoMaximoFront() {
    var d = pedidoPsGetDadosDescontoFront();
    if (d.validarDescontoGeral !== 'S') {
        return true;
    }
    if (d.aprovacao === 'N') {
        Swal.fire({
            icon: 'warning',
            title: 'Desconto não permitido',
            text: 'Desconto máximo permitido: ' + d.descontoMaximo + '%. Desconto aplicado: ' + d.perDesconto + '%. Não é possível confirmar o pedido.'
        });
        return false;
    }
    return true;
}

function pedidoPsConfirmarTextoPedido() {
    var d = pedidoPsGetDadosDescontoFront();
    var flagEnc = document.getElementById('encomendaAtivaFlag');
    var notaEnc = (flagEnc && flagEnc.value === '1')
        ? ' Se houver itens sem estoque, após aprovação o pedido poderá seguir como encomenda.'
        : '';
    if (d.validarDescontoGeral === 'S' && (d.aprovacao === 'S' || d.aprovacao === 'O')) {
        return 'Desconto acima do permitido (' + d.perDesconto + '% / máx. ' + d.descontoMaximo + '%). '
            + 'O pedido será enviado para aprovação gerencial.' + notaEnc + ' Deseja continuar?';
    }
    return 'Confirmar como Pedido?';
}

function submitPedidoPs() {
    f = document.lancamento;
    if (f.situacao.value !== '5') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Só é possível confirmar como Pedido a partir da cotação.' });
        return false;
    }
    var flagEnc = document.getElementById('encomendaAtivaFlag');
    if (flagEnc && flagEnc.value === '1') {
        Swal.fire({
            icon: 'info',
            title: 'Encomenda ativa',
            text: 'Use Ferramentas → Validar Estoque e depois Confirmar como Pedido.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if (!pedidoPsValidarCamposConfirmarPedido(f)) {
        return false;
    }
    if (!pedidoPsValidarDescontoMaximoFront()) {
        return false;
    }
    Swal.fire({
        title: pedidoPsConfirmarTextoPedido(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            f.situacao.value = '6';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function submitVoltarCotacaoCupomGerentePs() {
    var f = document.lancamento;
    var $f = $(f);
    if (!f.origem || f.origem.value !== 'cupomGerente') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Esta ação só está disponível ao abrir o pedido pelo cupom fiscal.' });
        return false;
    }
    if (f.situacao.value !== '3') {
        Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Só é possível voltar para cotação quando a situação é Emitir NF.' });
        return false;
    }
    Swal.fire({
        title: 'Voltar para cotação?',
        text: 'O pedido voltará ao estado de cotação.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo').prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '5';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function validarEncomendaPedidoPs() {
    pedidoPsResetEstoqueValidado();

    Swal.fire({
        title: 'Validando estoque...',
        allowOutsideClick: false,
        didOpen: function () { Swal.showLoading(); }
    });

    pedidoPsAjaxValidarEncomenda(function (err, data, parsed) {
        if (err) {
            Swal.fire({ icon: 'warning', title: 'Atenção', text: err });
            return;
        }
        Swal.fire({
            icon: parsed.estoqueOk ? 'success' : 'warning',
            title: data.titulo || 'Validação',
            html: parsed.html,
            width: 640
        });
    });

    return false;
}

function pedidoPsEncomendaAtiva(data) {
    if (data && data.encomendaAtiva) {
        return true;
    }
    var el = document.getElementById('encomendaAtivaFlag');
    return el && el.value === '1';
}

function pedidoPsPrepararSubmitPedidoPs($f) {
    $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo')
        .prop('disabled', false);
    $f.find('input[name="usrAbertura"]').prop('disabled', false);
}

function pedidoPsExecutarConfirmarPedidoEncomenda() {
    var f = document.lancamento;
    var $f = $(f);

    Swal.fire({
        title: pedidoPsConfirmarTextoPedido(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            pedidoPsPrepararSubmitPedidoPs($f);
            var elVal = document.getElementById('estoqueValidado');
            if (elVal) {
                elVal.value = '1';
            }
            if (!pedidoPsValidarDescontoMaximoFront()) {
                return;
            }
            f.situacao.value = '6';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function pedidoPsExecutarEncomendaPedidoPs() {
    var f = document.lancamento;
    var $f = $(f);

    if (!pedidoPsValidarDescontoMaximoFront()) {
        return;
    }

    pedidoPsResetEstoqueValidado();
    pedidoPsPrepararSubmitPedidoPs($f);
    f.situacao.value = '6';
    f.submenu.value = 'altera';
    f.submit();
}

function submitConfirmarPedidoEncomendaPs() {
    var f = document.lancamento;

    if (f.situacao.value !== '5' && f.situacao.value !== '13') {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Confirmação disponível para cotação ou encomenda.'
        });
        return false;
    }
    if (!pedidoPsValidarCamposConfirmarPedido(f)) {
        return false;
    }
    if (!pedidoPsValidarDescontoMaximoFront()) {
        return false;
    }

    if (window.pedidoPsEstoqueOk) {
        pedidoPsExecutarConfirmarPedidoEncomenda();
        return false;
    }

    Swal.fire({
        title: 'Validando estoque...',
        allowOutsideClick: false,
        didOpen: function () { Swal.showLoading(); }
    });

    pedidoPsAjaxValidarEncomenda(function (err, data, parsed) {
        if (err) {
            Swal.fire({ icon: 'error', title: 'Erro', text: err });
            return;
        }
        if (!parsed.estoqueOk) {
            if (pedidoPsEncomendaAtiva(data)) {
                Swal.fire({
                    icon: 'info',
                    title: data.titulo || 'Estoque parcial — encomenda',
                    html: parsed.html,
                    width: 640,
                    showCancelButton: true,
                    confirmButtonText: 'Colocar em Encomenda',
                    cancelButtonText: 'Cancelar'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        pedidoPsExecutarEncomendaPedidoPs();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: data.titulo || 'Estoque insuficiente',
                    html: parsed.html,
                    width: 640
                });
            }
            return;
        }
        pedidoPsExecutarConfirmarPedidoEncomenda();
    });

    return false;
}

function submitVoltarCotacaoEncomendaPs() {
    var f = document.lancamento;
    var $f = $(f);
    if (f.situacao.value !== '13') {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Esta ação só está disponível para pedidos em encomenda.'
        });
        return false;
    }
    Swal.fire({
        title: 'Voltar para cotação?',
        text: 'O pedido sairá da encomenda e voltará ao estado de cotação para edição.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {
            pedidoPsResetEstoqueValidado();
            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo')
                .prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '5';
            f.submenu.value = 'altera';
            f.submit();
        }
    });
}

function submitEstornoCotacao() {
    let f = document.lancamento;
    let $f = $(f);

    if (f.situacao.value !== '6') {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Estorno só está disponível para pedidos já confirmados.'
        });
        return false;
    }

    Swal.fire({
        title: 'Estornar para cotação?',
        text: 'O registro voltará ao estado de cotação.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then(function (result) {
        if (result.isConfirmed) {

            $f.find('select[name="condPgto"], select[name="usrAbertura"], select[name="usrAberturaDisabled"], #endereco_entrega_lado, #endereco_entrega_baixo')
              .prop('disabled', false);
            $f.find('input[name="usrAbertura"]').prop('disabled', false);
            f.situacao.value = '5';
            f.submenu.value = 'altera';

            f.submit();
        }
    });
}

if (typeof window.addEventListener !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            aplicarBloqueioPedidoPsCadastro();
            bindPedidoPsMoedaNoEnvio();
        });
    } else {
        aplicarBloqueioPedidoPsCadastro();
        bindPedidoPsMoedaNoEnvio();
    }
}
