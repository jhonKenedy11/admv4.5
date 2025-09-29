
function submitBaixar() {
    console.log("submitBaixar foi chamado"); // Adicione isso para testar
    debugger
    let f = document.lancamento;
    if (f.dataIni.value !== '' && f.dataFim.value !== '') {
        f.letra.value = f.dataIni.value + "|" + f.dataFim.value;
        f.submenu.value = 'consolidacao';
        f.submit();
    } else {
        alert('Digite o período de início e fim.');
    }
}
