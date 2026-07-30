# Roteiro — Gerência de Pedidos

Documento para montagem do manual em PDF (usuário final). Ordem de financeiro e NF pode variar por empresa e parâmetro de filial.

**Referência técnica:** `forms/ped/p_pedido_venda_gerente_novo.php`, `js/ped/s_pedido_venda_gerente_novo.js`, `template/ped/pedido_venda_gerente_novo.tpl`, `forms/ped/p_pedido_venda_nf_pecas_novo.php`.

---

## Objetivo

Orientar o uso da tela **Gerência de Pedidos** para **conferir pedidos confirmados**, **cadastrar financeiro** (produtos e/ou serviços), **imprimir romaneio** e **emitir documento fiscal** (NF-e ou cupom NFC-e).

---

## Público

Expedição, faturamento, financeiro, fiscal.

---

## Pré-requisitos

- O pedido deve estar em situação **Pedido** (fechado no cadastro PS) ou **Emitir NF** para aparecer na lista.
- Usuário com direito **Gerência de Pedidos** (`PedGerente`).
- Para financeiro/NF: direito **Gerar NF** (`PedGeraNf`), quando aplicável.

---

## Como abrir a tela

1. Menu **Pedidos → Gerencia Pedido** (nome conforme menu do cliente).
2. Acesso técnico: `mod=ped`, `form=pedido_venda_gerente_novo`.

Título da tela: **Gerencia de Pedidos**.

---

## Visão da tela principal

A tela mostra **uma única grade** com pedidos prontos para faturamento (situações **Pedido** e **Emitir NF**).

### Filtros (topo)

| Botão | O que lista |
| ----- | ----------- |
| **Mostrar Pedidos Dia** | Pedidos emitidos **hoje** (padrão ao abrir) |
| **Mostrar Pedidos Mes** | Pedidos do **mês corrente** |
| **Todos** | Todos os pedidos nessas situações, sem filtro de data |

### Colunas principais

| Coluna | Significado |
| ------ | ----------- |
| **Cliente** | Nome do cliente |
| **Andamento** | Barra visual: **Pedido → Financ. → NF → Fiscal** — indica em qual etapa o pedido está |
| **Pedido** | Número do pedido e botões de **editar** e **imprimir romaneio** |
| **Emissão / Valor** | Data e valor total |
| **Financeiro** | **Produtos** e **Serviços** — cadastro de parcelas separado por tipo |
| **Fiscal** | **Nota fiscal** (NF-e) e/ou **Cupom** (NFC-e) |

Pedidos em situação **Pedido** exibem **checkbox** na primeira coluna (usado para **agrupar** pedidos do mesmo cliente).

---

## Passo a passo — conferência (romaneio)

1. Localize o pedido na grade (situação **Pedido**).
2. Na coluna **Pedido**, clique no ícone de **impressora** (romaneio/conferência).
3. O sistema abre a impressão em nova janela.
4. **Se o pedido ainda está em Pedido:** após imprimir, a situação avança automaticamente para **Emitir NF** (ou conforme parâmetro **Fluxo pedido** da filial).
5. **Se já está em Emitir NF:** a impressão **só reimprime** o romaneio, sem mudar a situação.

Use a conferência quando o processo da empresa exige **conferir mercadoria** antes ou junto com financeiro/fiscal.

---

## Passo a passo — financeiro sem NF

Para gerar **contas a receber / parcelas** sem emitir nota neste momento:

1. Na coluna **Financeiro**, clique em **Produtos** (mercadorias) ou **Serviços** (se o pedido tiver valor de serviço — botão desabilitado quando serviço = zero).
2. Abre a tela **Cadastro de Parcelas no Financeiro** (dados do pedido já vêm preenchidos).
3. Revise e ajuste **parcelas**, vencimentos, tipo de documento (ex.: boleto), conta de recebimento e observações.
4. Clique em **Confirmar Financeiro**.
5. O pedido passa a constar na etapa **Emitir NF** na barra de andamento (salvo regra específica da filial).

Repita **Produtos** e **Serviços** separadamente quando o pedido tiver os dois tipos de valor.

---

## Passo a passo — emitir NF ou cupom (fiscal + financeiro)

1. Na coluna **Fiscal**, use o botão adequado:
   - **Nota fiscal** — NF-e (série 55 ou quando o pedido não define documento).
   - **Cupom** — NFC-e (série 65 ou quando ambos estão disponíveis).
   - Se no pedido PS foi definido **tipo de documento fiscal** (NF-e ou cupom), a tela mostra **somente** o botão correspondente.
2. Abre a tela integrada de **cadastro de NF e financeiro** (produtos).
3. Confira os **dados principais** no topo: pedido, cliente, condição de pagamento, total, centro de custo, natureza, série, etc.
4. Aba **Parcelas** — configure divisão do pagamento (parcela, vencimento, valor, tipo documento, conta, situação, obs.).
5. Aba **Transportador / Observação** — frete, transportador, volumes, pesos e observações fiscais, se aplicável.
6. Clique em **Confirmar NF** para finalizar cadastro da nota e do financeiro vinculado.

Se a nota ficar **rejeitada** ou **em aberto**, a barra **Andamento** destaca a etapa **Fiscal** — corrija na tela fiscal e tente novamente.

---

## Passo a passo — editar pedido a partir da Gerência

1. Na coluna **Pedido**, clique no ícone de **lápis**.
2. Abre o **cadastro do Pedido PS** em nova aba (alteração).
3. Só é possível editar o que a **situação** do pedido permitir (em **Pedido**, produtos costumam estar bloqueados).

---

## Passo a passo — agrupar pedidos

1. Filtre e localize pedidos em situação **Pedido** do **mesmo cliente**.
2. Marque o **checkbox** de **pelo menos dois** pedidos.
3. Clique no ícone de **chave inglesa** no canto superior direito do painel.
4. No modal, confira **pessoa**, **frete**, **despesas acessórias**, **desconto**, **situação** e **condição de pagamento**; o **total** é somado dos pedidos selecionados.
5. Clique em **Confirmar**.
6. O sistema gera um **novo pedido** com itens agrupados e informa o número gerado.

Só é permitido agrupar pedidos do **mesmo cliente**.

---

## Ordem típica do processo

Combine internamente com faturamento. Sequência comum:

1. **Pedido confirmado** no cadastro PS.
2. **Romaneio/conferência** (opcional, conforme processo).
3. **Financeiro** (Produtos e/ou Serviços), se feito antes da NF.
4. **Nota fiscal** ou **Cupom** na coluna Fiscal.
5. Acompanhamento pela barra **Andamento** até concluir **Fiscal**.

O que **não** pode, em geral: voltar o pedido a uma situação anterior depois de **financeiro quitado** ou **NF vinculada** — o sistema exibe erro explicando o bloqueio.

---

## Checklist antes de chamar suporte

- O pedido está em **Pedido** ou **Emitir NF**?
- Usou o filtro correto (dia / mês / todos)?
- Para agrupar: mesmo cliente e pelo menos **dois** pedidos marcados?
- Leu a **mensagem de erro completa** ao confirmar financeiro ou NF?
- Na barra **Andamento**, qual etapa está **ativa** (laranja)?
- Cliente com **município/endereço** válido (exigido na emissão fiscal)?
