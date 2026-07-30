# Guia de uso — Pedido de Venda

## Passo a passo — abrir e criar um pedido novo

1. Faça login.
2. No menu, abra **Pedidos → Pedido Venda**.
3. Você cai na **lista** de pedidos. Use filtros (período, situação, cliente, número) se precisar achar/editar um pedido já existente.
4. Para começar um trabalho novo, use o botão/opção de **novo Pedido**.
5. Abre-se a tela de **cadastro**. Em pedido novo, a situação exibida é a **Cotação** — é a fase de **orçamento**: você pode montar e alterar com mais liberdade.

## Passo a passo — montar a cotação

Faça nesta ordem (o próprio sistema costuma avisar se faltar algo obrigatório ao gravar):

1. **Cliente** — clique na **lupa** ao lado do nome, pesquise e selecione a **pessoa** (cliente). Sem cliente não grava.
2. **Contato** — preencha se sua empresa usar esse campo.
3. **Emissão** e **Prazo de entrega** — datas conforme o combinado ou o padrão da loja.
4. **Vendedor** — escolha na lista (é obrigatório para gravar e para incluir itens; se o usuário é vendedor, já é selecionado).
5. **Condição de pagamento** — escolha na lista (também obrigatória para gravar e para buscar produtos).
6. **Observações** — anote o que for importante para expedição, oficina ou financeiro (“retira na loja”, “só entregar após peça X”, etc.).
7. Se sua tela mostrar **centro de custo**, **loja**, **endereço de entrega**, **obra** ou outros campos, preencha conforme o processo da empresa.

## Passo a passo — incluir peças (produtos)

**Antes de começar:** cliente, vendedor e condição de pagamento devem estar preenchidos — o sistema não permite buscar produto sem eles. A inclusão de peças funciona enquanto o pedido está em **Cotação**; depois de confirmar como **Pedido**, os campos de produto ficam bloqueados.

1. Na tela de cadastro, abra a aba **Produtos**.

2. **Localizar o produto** — use um destes caminhos:
   - **Campo Pesquisa:** digite pelo menos **3 caracteres** (código fabricante, código interno, código de barras ou parte da descrição) e pressione **Enter** ou saia do campo (Tab). A busca considera código fabricante, código interno, código de barras, descrição e equivalências. O ícone **ℹ** ao lado do campo explica a ordem dos resultados.
   - **Lupa ao lado do campo Produto:** abre a **pesquisa ampliada** em nova janela. Selecione a linha do produto desejado — os campos voltam preenchidos no pedido e o foco vai para **Quantidade**.
   - Se a busca rápida **não encontrar** resultado, o sistema abre a pesquisa ampliada automaticamente, já com o termo digitado.

3. **Escolher entre equivalências:** quando houver mais de uma opção, aparece uma **grade abaixo** com os produtos encontrados. Clique na **linha** ou marque o **checkbox** do item correto. O sistema preenche código interno, código nota, descrição, unidade e valor unitário. Ao selecionar, o cursor vai para **Quantidade**; abaixo da grade você pode consultar estoque, compras e vendas recentes do item.

4. **Completar a linha:**
   - Informe **Quantidade** (obrigatório).
   - Confira **Valor unitário** (obrigatório; vem do cadastro, mas pode ser ajustado conforme permissão).
   - Preencha **% Desconto** ou **Valor desconto**, se aplicável — o **Total** da linha é calculado automaticamente.
   - Opcional: expanda **Mais informações** para ordem de compra, item da OC ou data de entrega da peça.

5. Clique em **Confirmar** (botão verde com **+**) para **incluir o item na grade**. Se o pedido ainda não tinha número, o sistema grava o cabeçalho na primeira inclusão.

6. Use **Limpar** para apagar os campos da linha e incluir outra peça.

7. **Repita** para cada produto. Na grade, use os botões **Alterar** (lápis) ou **Excluir** (X) nas linhas já incluídas, quando estiverem habilitados.

8. Os totais de **Valor Produto** e **Valor Total** do pedido são atualizados conforme as linhas.

## Passo a passo — gravar a cotação

1. Confira cliente, vendedor, condição de pagamento e se há pelo menos **um item** (peça ou serviço).
2. Clique em **Confirmar** no topo da tela.
3. Depois da primeira gravação, o pedido passa a ter **número** e você pode **reabri-lo pela lista** para continuar editando **ainda em Cotação**.

Enquanto estiver em **Cotação**, você pode ir ajustando itens e clicando **Confirmar** de novo sempre que precisar salvar.

## Passo a passo — da cotação ao pedido

Quando o cliente **aceitar** o negócio:

1. Revise **itens**, **totais** e **condição de pagamento** pela última vez.
2. Com o pedido **já gravado** e a situação ainda **Cotação**, aparece no topo o botão verde **Pedido** (só nessa fase).
3. Clique em **Pedido**. O sistema pergunta algo como **“Confirmar como Pedido?”** — confirme se estiver tudo certo.
4. Ao concluir, a situação passa a **Pedido**. Nessa hora o sistema pode **validar estoque** e, se a empresa estiver configurada para isso, **reservar** mercadoria — se aparecer erro, leia o texto e ajuste quantidades ou alinhe com estoque.

Depois que está em **Pedido**, boa parte da tela deixa de ser editável (produtos, serviços, cliente, etc.) — não é defeito; protege o que já foi fechado comercialmente e pode seguir para conferência e fiscal.

Se precisar **voltar** a tratar como orçamento **antes** de avançar demais no processo, e o sistema permitir, use o botão **Estorno** (na fase **Pedido**) para retornar à **Cotação** — desde que não exista bloqueio.

## Gerência de Pedidos — o que é cada parte da tela

A tela **Gerência de Pedidos** concentra conferência e faturamento.

### Primeira lista — pedidos já confirmados

Aparecem pedidos que já estão na situação de **pedido** (fechados com o cliente, aguardando o faturamento).

Por linha você costuma ver:

- **Conferência** — botão para **impressão** relacionada à conferência (por exemplo romaneio/conferência do pedido). Use quando o processo da empresa pedir **conferir mercadoria** antes ou junto com o fiscal.
- **Financeiro — Produtos** — abre o fluxo de **cadastro financeiro** ligado às **mercadorias** daquele pedido.

Ou seja: nesta lista você **não emite NF direto**; você **imprime a conferência** quando precisar e **abre o financeiro** por tipo (produto ou serviço), quando o seu papel for esse.

### Segunda lista — Emitir Nota Fiscal

Aparecem pedidos que já estão na situação **emitir NF** (prontos para o passo fiscal na visão do sistema).

- O botão **Nota Fiscal** leva ao fluxo de **emissão/cadastro da nota** daquele pedido.

### Financeiro antes ou depois da nota?

Isso **varia por empresa**. Na rotina típica usando a Gerência:

- **Pedido confirmado** → alguém usa **Produtos** para o que couber no **financeiro** e, quando o pedido estiver na fase **emitir NF**, usa o botão **Nota Fiscal** na lista de baixo.

O que **não** pode, em geral: tentar **voltar** o pedido para uma situação anterior **depois** que já existe **financeiro quitado** ou vínculo que o sistema bloqueia — aí aparece **erro explicando o bloqueio**.

Combine com o **faturamento** qual ordem o seu processo adota (por exemplo: financeiro na Gerência antes da NF, ou NF antes — conforme regra interna).

## Duplicar, OS e outros botões

- **Duplicar** — gera um **novo** pedido copiando dados, em geral voltando para **cotação**, para refazer venda parecida.
- **Gerar OS / Estornar OS** — só onde a empresa usa **ordem de serviço** ligada ao pedido; use conforme processo interno.
- **Cancelar** — já explicado acima; depende de NF/financeiro existente.
- **Baixar pedido** — finalizar o pedido, se já tem nota fiscal ou financeiro cadastrado.

## Checklist antes de chamar suporte

- Cliente e centro de custo corretos?
- Dados do cliente estão corretos?
- Situação do pedido é a que você **realmente** quer para esta etapa?
- Se controla o estoque, tem estoque disponível para o produto?
- Condição de pagamento correta para o combinado com o cliente?
- Leu a **mensagem de erro completa** da última tentativa de salvar?
- Se usa Gerência de Pedidos: o pedido está na **lista de cima** (confirmado) ou na **lista de baixo** (emitir NF)?
