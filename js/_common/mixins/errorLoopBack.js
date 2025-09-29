export default {
  //monta string mostrando todos os erros
  msgerro(value) {
    var listErros = "Corrija os seguintes erros: ";
    var msg = value["response"]["data"]["error"]["details"];

    //percorre os erros e vai concatenando na string
    for (var i = 0; i < msg.length; i++) {
      listErros = listErros + "\n" + msg[i]["message"];
    }
    //retorna a lista dos erros
    console.log(value["response"]);
    return listErros;
  }
};
