document.addEventListener('keydown', function (event) {
if (event.keyCode !== 13) return;
    submitLetra();
});
    
function submitLetra() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_granulacao';
    f.letra.value = f.numNf.value;
    f.submenu.value = '';
    f.submit();
} // fim submitVoltar
    
function submitCadastro() {
    f = document.lancamento;
    f.mod.value = 'est';
    f.form.value = 'nota_fiscal_granulacao';
    f.letra.value = f.numNf.value;
    f.pesquisa.value = f.fator.value + "|" + f.origemNatOp.value + "|" + f.origemCfop.value + "|" + f.destinoNatOp.value + "|" + f.destinoCfop.value;
    f.submenu.value = 'cadastrar';
    
    myCheckbox = document.lancamento.elements["prodCheckbox"];
    
    if (typeof(myCheckbox.length)=="number"){
        for (var i=0;i<myCheckbox.length;i++){  
             if (myCheckbox[i].checked == true){  
                 if(f.produtos.value == ''){
                     f.produtos.value = myCheckbox[i].value;
                 }else{
                     f.produtos.value = f.produtos.value + "|" + myCheckbox[i].value;
                 }//if
             }//if
         }//for
    }else{
        if (myCheckbox.checked == true){  
            f.produtos.value = document.lancamento.elements["prodCheckbox"].value;
        }
    }
    
    
    f.submit();
} // fim submitVoltar