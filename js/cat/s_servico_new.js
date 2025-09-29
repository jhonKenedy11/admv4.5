// mostra Cadastro
function submitCadastro(formulario) {
    f = document.lancamento;
    f.mod.value = 'cat';
    f.form.value = 'servico';
    f.submenu.value = 'cadastrar';
    f.origem.value = 'atendimento_new';
    f.opcao.value = 'pesquisar';
    f.id.value = "";
    f.submit();
} // submitCadastro

//fecha pesquisa de produto e atualiza campos da form que chamou
function fechaServicoPesquisaAtendimento(e) {
    debugger;
    f = window.opener.document.lancamento;

    var linha = $(e).closest("tr");

    var id               = linha.find("td:eq(0)").text().trim(); 
    var descricaoServico = linha.find("td:eq(1)").text().trim(); 
    var vlrUnitario      = linha.find("td:eq(2)").text().trim();
    var unidade          = linha.find("td:eq(3)").text().trim(); 

    
    f.codServico.value          = id;
    f.descricaoServico.value    = descricaoServico  
    f.unidadeServico.value      = unidade
    f.vlrUnitarioServico.value  = vlrUnitario  
    
    
    window.close();
}