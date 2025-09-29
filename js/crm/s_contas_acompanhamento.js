document.addEventListener('keydown', function (event) {
    // evento pressionar ENTER
    if (event.keyCode == 13) {
        submitLetra();
    }// fim evento enter
    // evento pressionar ESC
    if (event.keyCode == 27) {
        submitVoltar();
    }// fim do evento esc
  });
  
  function submitConfirmar() {
    
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas_acompanhamento';
    if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
        //f.opcao.value = 'acompanhamento';
        if ((f.submenu.value == "cadastrar") || (f.submenu.value == "inclui")) {
            f.submenu.value = 'inclui';
        }
        else {
            f.submenu.value = 'altera';
        }
        //Verifica se existe dashboard origem e add click no pesquisar
        if(f.dashboard_origem.value == 'dashboard_crm'){
          ajaxCadAcompDashboard(f.submenu.value, f.id.value);
        }else{
          f.submit();
        }
    } //  
  } // submitConfirmar
  
  function ajaxCadAcompDashboard(action, id){
  
  var form = $("form[name=lancamento]");
  $.ajax({
    type: "POST",
    url: document.URL+"mod=crm&form=contas_acompanhamento&submenu="+action+"&opcao=blank&dashboard_origem=dashboard_crm&id="+id+"",
    data: $(form).serialize(),
    dataType: "text",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Ajax-Request-Altera-Pedido-Dashboard", "true");
    },
    success: function (response) {
      
  
       swal.fire(response, {
        icon: "warning",
        buttons: {
          catch: {
            text: "Ok",
            value: "ok",
          },
        },
      })
      .then((value) => {
        switch (value) {
          default:
            window.close();
            g = window.opener.document.lancamento;
            g.btnSubLet.click();
            break;
        }
      });
      
    },
  });
  
  }
  
  function submitVoltar() {
  
    f = document.lancamento;
    document.lancamento.mod.value = 'crm';
    if (f.opcao.value == 'pessoa') {
        f.form.value = 'contas';
        //f.opcao.value = '';
        f.letra.value = f.pessoaNome.value;}
    else{
        f.form.value = 'contas_acompanhamento';
        //f.opcao.value = 'acompanhamento';
        
        //Verifica se existe dashboard origem e add click no pesquisar
        if(f.dashboard_origem.value == 'dashboard_crm'){
            f.dashboard_origem.value = null;
            g = window.opener.document.lancamento;
            window.close();
            g.btnSubLet.click();
        }
  
        if(f.data_previous.value == 'contas'){
          f.form.value = 'contas';
        }
    }
    
    f.submenu.value = '';
    f.submit();
  } // fim submitVoltar
  
  
  function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'cadastrar';
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value  + "|" + f.vendedor.value + "|" + f.nome.value;
    f.id.value = "";
    f.submit();
  } // submitCadastro
  
  function submitAlterar(acomp_id) {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'alterar';
    f.id.value = acomp_id;
    f.submit();
  } // submitAlterar
  
  function submitExcluir(acomp_id) {
    if (confirm('Deseja realmente Excluir este item') == true) {
        f = document.lancamento;
        f.mod.value = 'crm';
        f.form.value = 'contas_acompanhamento';
        //f.opcao.value = 'acompanhamento';
        f.submenu.value = 'exclui';
        f.id.value = acomp_id;
        f.submit();
    } // if
  } // submitExcluir
  
  function submitLetra() {
    f = document.lancamento;
    f.mod.value = 'crm';
    f.form.value = 'contas_acompanhamento';
    //f.opcao.value = 'acompanhamento';
    f.submenu.value = 'letra';
    // VENDEDOR
    vendedores = "0";
    for (var i = 0; i < vendedor.options.length; i++) {
        if (vendedor[i].selected == true) {
        vendedores = vendedores + "," + vendedor[i].value;
        }
    }
    
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value  + "|" + vendedores + "|" + f.nome.value + "|" + f.pesPedido.value;
    f.submit();
  } // submitLetra
  
  function abrir(pag){
    window.open(pag, 'consulta', 'toolbar=no,location=no,menubar=no,width=650,height=550,scrollbars=yes');
  }
  
  function setCliente(){
    
    f = document.lancamento;
    id =  f.clienteCombo.options[f.clienteCombo.selectedIndex].value;
    f.pessoa.value = id;
  }