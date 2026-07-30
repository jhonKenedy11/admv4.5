# Emissão de boleto via API Bradesco
_Exported on 16/06/2026 at 18:40:49 BRT from Cursor (3.7.42)_

---

**User**

Agora voce e um programador senior PHP.

@admv4.5/class/blt/c_sse_imprime_boleto.php:206-254 Preciso ajustar a rotina de emissao de boleto atraves da API bradesco.

@admv4.5/class/blt/c_sse_imprime_boleto.php:229-235 nessa linha esta a classe para montar a pesquisa no banco @admv4.5/class/fin/atualizacao_4.5.sql:84-268 

Voce fara uma consulta buscando o codigo de barras e outras infos necessarias para montagem do boleto.

siga essas instrucoes disponibilizada na documentacao:

Montagem do código de barras e linha digitável do boleto de cobrança
Código de Barras
 Contém 44 posições, composto por:
01 a 03 (Tamanho 3): Identificação do Banco
04 a 04 (Tamanho 1): Código da Moeda (Real = 9, Outras = 0)
05 a 05 (Tamanho 1): Dígito Verificador do Código de Barras
06 a 09 (Tamanho 4): Fator de Vencimento
10 a 19 (Tamanho 10): Valor
20 a 44 (Tamanho 25): Campo Livre
As posições do campo livre ficam a critério de cada Banco emissor. Para o Bradesco, o padrão é:
20 a 23 (Tamanho 4): Agência beneficiária (sem o dígito verificador, completar com zeros à esquerda, quando necessário)
24 a 25 (Tamanho 2): Carteira
26 a 36 (Tamanho 11): Número do Nosso Número (sem o dígito verificador)
37 a 43 (Tamanho 7): Conta do beneficiário (sem o dígito verificador, completar com zeros à esquerda, quando necessário)
44 a 44 (Tamanho 1): Zero
Linha digitável:
 É composta por cinco campos:
1º Campo: composto pelo código do Banco, código da moeda, as cinco primeiras posições do campo livre e o dígito verificador desse campo.
2º Campo: composto pelas posições 6ª a 15ª do campo livre e o dígito verificador desse campo.
3º Campo: composto pelas posições 16ª a 25ª do campo livre e o dígito verificador desse campo.
4º Campo: composto pelo dígito verificador do código de barras, ou seja, a 5ª posição do código de barras.
5º Campo: composto pelo fator de vencimento com 4 (quatro) caracteres e o valor do documento com 10 (dez) caracteres, sem separadores e sem edição.
Entre cada campo deverá haver espaço equivalente a 2 (duas) posições, sendo a 1ª interpretada por um ponto (.) e a 2ª por um espaço em branco.
Modelo para boleto de cobrança
 Os boletos deverão obedecer aos seguintes parâmetros definidos pelo BACEN:
(Número de vias ou partes - 2 (duas)) Recibo do Pagador; Ficha de Compensação. Obs.: o recibo do pagador deverá conter o Valor do Documento, Nosso Número, Carteira, Agência/Código do Beneficiário e Data do Vencimento.
Gramatura do papel - mínima de 50g/m2. Dimensões: Ficha de Compensação: 95 a 104 mm X 170 a 216 mm.
Cor da via/impressão: Fundo branco / impressão azul ou Fundo branco / impressão preta.
Inserção dos dados do Banco/Linha Digitável na borda superior da Ficha de Compensação. Lado esquerdo - deverá constar o nome do Banco e o número do Banco, acrescido do dígito verificador.
 (O código do Banco deverá ser constituído com caractere de 5mm e traços ou fios de 1,2mm). Lado direito - deverá constar a Linha Digitável. As dimensões serão 3,5 a 4mm e traços ou fios de 0,3mm.
Instrução para preenchimento dos campos do boleto
Local de pagamento: campo destinado à inserção de mensagem para indicar ao pagador onde o pagamento poderá ser efetuado.
Campo vencimento: o vencimento que constar nesse campo deverá, obrigatoriamente, ser o mesmo constante no Fator de Vencimento da linha digitável e do código de barras. Ocorrendo divergência entre essas informações, prevalecerá a data indicada no Fator de Vencimento no Código de Barras/Linha Digitável.
Beneficiário: Razão Social ou Nome Fantasia e CNPJ da empresa emissora do boleto bancário, devendo ser os mesmos do cadastro do Banco.
Agência / Código do beneficiário: deverá ser preenchido com o número da Agência com 4 (quatro caracteres) - dígito da Agência / Conta de Cobrança com 7 (sete) caracteres - Dígito da Conta. Ex. 9999-D/9999999-D. Obs.: preencher com zeros à esquerda, quando necessário.
Data de emissão: preencher com a data de emissão do documento, ou a data do faturamento.
Número do documento: poderá ser informada a identificação do número da fatura, duplicata etc.
Espécie do documento: de acordo com o ramo de atividade, poderá ser utilizada uma das siglas: DM-Duplicata Mercantil, NP-Nota Promissória, NS-Nota de Seguro, CS-Cobrança Seriada, REC-Recibo, LC-Letras de Câmbio, ND-Nota de Débito, DS-Duplicata de Serviços, Outros. Obs.: para a Espécie 31 - CC (Cartão de Crédito), não deverá haver a incidência de juros, multas, descontos ou abatimentos.
Aceite: N - Não Aceite.
Data processamento: poderá ser a mesma data de emissão.
Carteira / Nosso número: Carteira com 2 (dois) caracteres / N. Número com 11 (onze) caracteres + dígito. Ex.: 99 / 99999999999-D. Obs.: o Nosso Número é um identificador do boleto, devendo ser atribuído um número diferenciado para cada documento.
Uso do banco: não preencher.
CIP: trata-se de código utilizado para identificar mensagens específicas ao beneficiário, sendo que ele consta no cadastro do Banco, quando não houver código cadastrado, preencher com zeros “000”.
Carteira: Carteira de Cobrança que a empresa opera no Banco.
Moeda: campo destinado à informação do tipo de moeda que o documento foi emitido (R,US, IGPM etc.).
Quantidade: quando o documento for emitido em moeda indexada (US$, IGPM etc.), preencher esse campo com a quantidade correspondente.
Valor do documento: campo destinado à informação do valor do documento, devendo ser preenchido com o mesmo valor informado no código de barras e na linha digitável. Caso o valor do documento esteja zerado no código de barras e na linha digitável, deixar esse campo em branco.
Instruções: campo reservado para mensagens de responsabilidade do beneficiário.


o campo codigo de barra e para fazer a conversao:

"Utilize o array associativo abaixo, que contém a Tabela EBCDIC oficial do Bradesco, para realizar a conversão (intercalação) e a montagem final das barras do boleto:
$tabelaEBCDIC = [
    "00" => "nnWWn", "01" => "NnwwN", "02" => "nNwwN", "03" => "NNwwn", "04" => "nnWwN",
    "05" => "NnWwn", "06" => "nNWwn", "07" => "nnwWN", "08" => "NnwWn", "09" => "nNwWn",
    "10" => "wnNNw", "11" => "WnnnW", "12" => "wNnnW", "13" => "WNnnw", "14" => "wnNnW",
    "15" => "WnNnw", "16" => "wNNnw", "17" => "wnnNW", "18" => "WnnNw", "19" => "wNnNw",
    "20" => "nwNNw", "21" => "NwnnW", "22" => "nWnnW", "23" => "NWnnw", "24" => "nwNnW",
    "25" => "NwNnw", "26" => "nWNnw", "27" => "nwnNW", "28" => "NwnNw", "29" => "nWnNw",
    "30" => "wwNNn", "31" => "WwnnN", "32" => "wWnnN", "33" => "WWnnn", "34" => "wwNnN",
    "35" => "WwNnn", "36" => "wWNnn", "37" => "wwnNN", "38" => "WwnNn", "39" => "wWnNn",
    "40" => "nnWNw", "41" => "NnwnW", "42" => "nNwnW", "43" => "NNwnw", "44" => "nnWnW",
    "45" => "NnWnw", "46" => "nNWnw", "47" => "nnwNW", "48" => "NnwNw", "49" => "nNwNw",
    "50" => "wnWNn", "51" => "WnwnN", "52" => "wNwnN", "53" => "WNwnn", "54" => "wnWnN",
    "55" => "WnWnn", "56" => "wNWnn", "57" => "wnwNN", "58" => "WnwNn", "59" => "wNwNn",
    "60" => "nwWNn", "61" => "NwwnN", "62" => "nWwnN", "63" => "NWwnn", "64" => "nwWnN",
    "65" => "NwWnn", "66" => "nWWnn", "67" => "nwwNN", "68" => "NwwNn", "69" => "nWwNn",
    "70" => "nnNWw", "71" => "NnnwW", "72" => "nNnwW", "73" => "NNnww", "74" => "nnNwW",
    "75" => "NnNww", "76" => "nNNww", "77" => "nnnWW", "78" => "NnnWw", "79" => "nNnWw",
    "80" => "wnNWn", "81" => "WnnwN", "82" => "wNnwN", "83" => "WNnwn", "84" => "wnNwN",
    "85" => "WnNwn", "86" => "wNNwn", "87" => "wnnWN", "88" => "WnnWn", "89" => "wNnWn",
    "90" => "nwNWn", "91" => "NwnwN", "92" => "nWnwN", "93" => "NWnwn", "94" => "nwNwN",
    "95" => "NwNwn", "96" => "nWNwn", "97" => "nwnWN", "98" => "NwnWn", "99" => "nWnWn"
];

Depois entrara na rotina atual para realizar a impressao do boleto.

Faca uma analise da classe 
@admv4.5/class/fin/c_api_bradesco_barcode.php pois pode ser usada para conversao, faca os ajustes necessarios.

---

**Cursor**

Analisando o fluxo atual de impressão e as classes Bradesco para integrar a consulta e montagem do boleto.


Implementando: consulta em `FIN_API_BRADESCO`, conversão do código de barras via `c_api_bradesco_barcode` e integração com a rotina de PDF existente.


Corrigindo o parse do código numérico: `decodeBoleto()` espera I2of5, não 44 dígitos.


Removendo o método `encode` com roundtrip incorreto — não é necessário para impressão; a decodificação I2of5 da API permanece intacta.


Implementei o fluxo completo de impressão de boleto Bradesco via API. Resumo do que foi feito:

## Fluxo implementado

Para banco **237** com `ENVIA_BOLETO = 'A'`:

1. **`getDadosImpressaoBoleto()`** em `c_api_bradesco_repository.php` — consulta `FIN_API_BRADESCO` (por `REMESSANUM` / `FAB.ID`) com join em `FIN_LANCAMENTO` e `FIN_CONTA`, trazendo código de barras, linha digitável, beneficiário, pagador, valores e datas.

2. **`c_api_bradesco_barcode`** — ajustes:
   - Tabela EBCDIC Bradesco (00–99) documentada
   - `resolveCodigoBarrasNumerico()` — aceita I2of5 (N/n/W/w) da API **ou** 44 dígitos numéricos
   - `parseCodigoBarrasNumerico()` — extrai agência, carteira, nosso número, conta, vencimento e valor do campo livre Bradesco

3. **`gerarPdfBoletoApiBradesco()`** em `c_sse_imprime_boleto.php` — orquestra consulta, conversão do código de barras, montagem da linha digitável (via `monta_linha_digitavel()` se não vier do banco) e geração do PDF.

4. **`p_boleto_pdf.php`** — novos métodos:
   - `geraPdfBoletoApiBradesco()` — gera PDF usando dados da API
   - `aplicaDadosApiBradesco()` — preenche beneficiário, pagador, agência/conta, nosso número etc.
   - `geraHtmlBradesco()` — quando `usar_codigo_api = true`, usa código de barras e linha digitável do banco **sem recalcular** (evita divergência com o título registrado)

## Comportamento

```229:233:class/blt/c_sse_imprime_boleto.php
            case '237':
                if ($lancamento['ENVIA_BOLETO'] == 'A') {
                    return $this->gerarPdfBoletoApiBradesco($lancamento, $id_lancamento);
                }
                return $this->gerarPdfBoletoRemessa($lancamento, $id_lancamento);
```

- `$id_lancamento` aqui é o `REMESSANUM` = `FIN_API_BRADESCO.ID`
- O PDF segue o layout Bradesco existente (`layout_bradesco_email.php` + `fbarcode()` com os 44 dígitos numéricos)
- Retorno SSE no mesmo formato do Inter: `{ sucesso: true, data: { boleto_Base64: ... } }`

## Pré-requisitos para funcionar

O boleto precisa estar registrado na API com `CD_BARRAS` preenchido em `FIN_API_BRADESCO`. Se estiver vazio, o SSE retorna erro claro: *"Código de barras não disponível para este boleto."*

