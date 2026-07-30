# Manual de Uso — Apuração Assistida CBS

Guia simples, para o dia a dia. Não é necessário conhecimento técnico.

---

## O que é esta tela

É a tela que conversa com a **Receita Federal** para trazer os **débitos de CBS** da sua empresa (nota por nota) e permitir que você **emita eventos** em cada nota (por exemplo, aceitar um débito ou solicitar um crédito).

Pense nela como um "extrato" das notas com CBS em aberto, onde você também toma providências.

---

## As 4 abas da tela

| Aba | Para que serve |
|-----|----------------|
| **Consulta RF** | Onde você busca os dados na Receita (é por aqui que se começa) |
| **Pendências Crédito** | Notas em que a sua empresa é **quem compra** (destinatário) |
| **Pendências Débito** | Notas em que a sua empresa é **quem vende** (emitente) |
| **Histórico de Eventos** | Lista de tudo que você já enviou/registrou |

---

## Passo a passo do uso normal

### 1. Buscar os dados (aba "Consulta RF")

Você verá três botões:

1. **Solicitar Consulta** — pede os dados à Receita. A requisição fica com o status **Aguardando retorno**. A Receita processa e **avisa o sistema automaticamente** quando o arquivo está pronto (você não precisa ficar tentando baixar).
2. **Baixar Débitos** — fica disponível quando o retorno chega (a requisição passa para o status **Disponível**). Traz o arquivo para dentro do sistema; as notas aparecem nas abas de pendências.
3. **Atualizar** — recarrega a lista para conferir se o retorno da Receita já chegou.

> Depois de "Solicitar Consulta", acompanhe o status na tabela **Histórico de Requisições**:
> **Aguardando retorno** (esperando a Receita) → **Disponível** (pode baixar) → **Baixado** (pronto).
> Se ainda estiver "Aguardando retorno", clique em **Atualizar** de tempos em tempos.

Você não precisa se preocupar com "token", "tíquete" nem com senhas técnicas — o sistema cuida disso sozinho.

### 2. Analisar as notas (abas de pendências)

- Abra **Pendências Crédito** (quando você comprou) ou **Pendências Débito** (quando você vendeu).
- Cada linha é uma nota, com:
  - **Chave** da nota
  - **Contraparte** (quem emitiu ou quem comprou)
  - **CBS Não Extinto** = o valor que ainda está em aberto
  - **Situação** e **Status**

### 3. Ver o XML da nota (opcional)

- Clique no botão **XML** da linha para consultar o documento completo (itens, produtos).
  - Observação: a busca do XML pela chave será habilitada em breve.

### 4. Emitir um evento na nota

- Clique no botão **Evento** da linha e escolha a ação desejada na lista.
- Confirme na janela que abrir (pode escrever uma observação).
- O evento fica registrado e aparece na aba **Histórico de Eventos**.

Os eventos oferecidos dependem do seu papel na nota:

- **Comprou (crédito):** aceitar débito, solicitar créditos, etc.
- **Vendeu (débito):** informar pagamento, informar perda, fornecimento não realizado, etc.

---

## Limites diários (importante)

A Receita limita o uso por dia, por CNPJ:

- **2 consultas por dia**
- **8 downloads por dia**

No topo da tela há dois indicadores mostrando quanto você já usou hoje:

- **Verde** = tudo certo
- **Amarelo** = está chegando perto do limite
- **Vermelho** = limite atingido (aguarde o próximo dia)

E lembre-se: o arquivo baixado vale por **24 horas**.

---

## Perguntas frequentes

**Preciso clicar em "Gerar Token"?**
Não. O acesso é renovado automaticamente. O botão "Testar credenciais" (dentro de "Editar credenciais") é só para verificar se o cadastro está correto.

**Uma nota guarda todos os eventos que já emiti?**
Sim. Cada nota mantém o histórico completo dos eventos emitidos, mesmo que você baixe os dados novamente.

**Baixei os dados de novo e os números mudaram. É problema?**
Não. Os valores são atualizados a cada consulta. Quando algo muda em relação à consulta anterior, a nota fica marcada como **Alterado**.

**O botão "Baixar Débitos" não baixa / a requisição está "Aguardando retorno".**
A Receita ainda não devolveu o arquivo. O aviso chega automaticamente; quando a requisição ficar **Disponível**, o download é liberado. Clique em **Atualizar** para conferir.

**Onde configuro o acesso (credenciais)?**
Na aba "Consulta RF", botão **Editar credenciais**. Normalmente é feito uma única vez. Além do CNPJ e das credenciais, é necessária uma **URL de retorno (webhook)** — o endereço onde a Receita avisa que o arquivo está pronto. Essa configuração é feita pela equipe técnica na instalação.

---

## Resumo em uma frase

**Solicitar Consulta → aguardar ficar "Disponível" → Baixar Débitos → abrir a aba de pendências → emitir o evento na nota.**
