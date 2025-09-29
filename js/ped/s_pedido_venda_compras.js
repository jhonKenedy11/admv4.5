// Funcoes Pedido Item Composicao

// mostra Cadastro
	function submitCadastroItemComp(pedido_id) {
                f = document.pedidovenda;
   		f.opcao.value = 'lancamento';
   		f.submenu.value = 'cadastraritemcomp';
                f.id.value = pedido_id;
                f.submit();
	}

	function submitVoltarItemComp(pedido_id) {

           f = document.lancamento;
   		   f.opcao.value = 'lancamento';
   		   f.submenu.value = 'alterar';
   		   f.id.value = pedido_id;
//   		   confirm('Pedido: ' + f.id.value)
   		   f.submit();
	}

// Confirmar Item 
	function submitConfirmarItemComp() {
        f = document.lancamento;
		if (f.item.value ==""){
	   			alert("Digite o produto.");
   			}
   		else {
			if (f.qtpedido.value ==""){
	   			alert("Digite a quantidade.");
   				}
   			else {
				if (f.custounitario.value ==""){
		   			alert("Digite o valor unitario.");
   					}
   				else {
              		if (confirm('Deseja realmente ' + f.submenu.value + ' este item') == true) {
    	         		f.opcao.value = 'pedidovenda';
   	            		if (f.submenu.value == "cadastraritemcomp") {
                			f.submenu.value = 'incluiitemcomp'; }
                    	else {
                			f.submenu.value = 'alteraitemcomp'; }

                    f.submit();
   		      		} // if
	        		} // else
        		} // else
        	} // else
    } // fim submitConfirmar

	function submitAlterarItemComp(pedidoVenda_id, item_id) {

            if (confirm('Deseja realmente Alterar este item') == true) {
                   f = document.pedidovenda;
   		   f.opcao.value = 'pedidovenda';
   		   f.submenu.value = 'alteraritemcomp';
   		   f.id.value = pedidoVenda_id;
   		   f.iditem.value = item_id;
   		   f.submit();
   		}
	}
    function submitExcluirItemComp(pedidoVenda_id, item_id) {
        if (confirm('Deseja realmente Excluir este item') == true) {
           f = document.lancamento;
           f.opcao.value = 'pedidovenda';
   		   f.submenu.value = 'excluiitemcomp';
   		   f.id.value = pedidoVenda_id;
   		   f.iditem.value = item_id;
   		   f.submit();
   		}
	}