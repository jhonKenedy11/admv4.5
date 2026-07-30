# Guia de uso — Gerência de Pedidos

## Como abrir

1. Faça login.
2. Menu **Pedidos → Gerencia Pedido**.

A tela lista pedidos prontos para **conferência**, **financeiro** e **fiscal** (situações **Pedido** e **Emitir NF**).

---

## Filtros

| Botão | Lista |
| ----- | ----- |
| **Mostrar Pedidos Dia** | Pedidos de **hoje** (padrão) |
| **Mostrar Pedidos Mes** | Pedidos do **mês** |
| **Todos** | Todos nessas situações |

---

## Entendendo a grade

Cada linha mostra: **Cliente**, barra **Andamento**, **Pedido**, **Emissão**, **Valor**, **Financeiro** e **Fiscal**.

A barra **Andamento** segue: **Conferência → Financeiro → Emissão NF → Receita**. A etapa em laranja é a atual; as verdes já foram concluídas. **Emissão NF** em verde com nota cadastrada indica documento em aberto ou rejeitado, ainda sem autorização da Receita Federal. **Receita** em laranja significa aguardando confirmação com a Receita. Passe o mouse na barra ou em cada etapa para ver o que fazer.

---

## Conferência (romaneio)

1. Na coluna **Pedido**, clique na **impressora**.
2. Confira/imprima o romaneio na janela aberta.
3. Se o pedido ainda está em **Pedido**, a situação **avança** após a impressão (geralmente para **Emitir NF**).
4. Se já está em **Emitir NF**, a impressão **só reimprime**.

---

## Financeiro (sem emitir NF agora)

1. Coluna **Financeiro** → **Produtos** (peças) ou **Serviços** (se houver valor de serviço).
2. Ajuste **parcelas**, vencimentos, tipo de documento e conta na tela de cadastro.
3. **Confirmar Financeiro**.
4. O pedido segue para a etapa fiscal conforme regra da empresa.

---

## Emitir NF ou cupom

1. Coluna **Fiscal** → **Nota fiscal** (NF-e) ou **Cupom** (NFC-e).
   - Se no pedido foi definido o tipo de documento, aparece só o botão correspondente.
2. Revise dados do pedido no topo da tela.
3. Aba **Parcelas** — pagamento.
4. Aba **Transportador / Observação** — entrega e obs. fiscais, se usar.
5. **Confirmar NF**.

---

## Editar pedido

Coluna **Pedido** → ícone **lápis** abre o cadastro PS em nova aba (somente o que a situação permitir).

---

## Agrupar pedidos

1. Marque **checkbox** de **2 ou mais** pedidos em **Pedido** do **mesmo cliente**.
2. Ícone **chave inglesa** (canto superior direito).
3. Ajuste frete, despesas, desconto, situação e condição de pagamento no modal.
4. **Confirmar** — gera um novo pedido com itens reunidos.

---

## Cadastro de NF e financeiro — campos principais

**Topo da tela:** número do pedido, cliente, condição de pagamento, total, centro de custo, gênero, venda presencial, natureza de operação, série.

**Aba Parcelas:** parcela, vencimento, valor, tipo documento, conta recebimento, situação do lançamento, observações.

**Aba Transportador / Observação:** modalidade frete, transportador, data saída/entrada, volumes, pesos, observação no documento.

---

## Checklist

- Pedido em **Pedido** ou **Emitir NF**?
- Filtro correto (dia / mês / todos)?
- Agrupamento: **mesmo cliente**, mínimo **2** pedidos?
- Leu a mensagem de erro ao confirmar financeiro ou NF?
- Qual etapa está ativa na barra **Andamento**?
