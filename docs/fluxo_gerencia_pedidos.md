# Fluxo — Gerência de Pedidos

Documentação da tela **Gerência de Pedidos** (`pedido_venda_gerente_novo`), com foco em **arquivos**, **submenus** e **transição de situação** do pedido após conferência, financeiro e fiscal.

---

## Visão geral

| Camada | Arquivo principal | Papel |
| ------ | ----------------- | ----- |
| Apresentação / roteamento | `forms/ped/p_pedido_venda_gerente_novo.php` | Classe `p_pedido_venda_conferecia_novo` — `controle()` despacha por `submenu` |
| Domínio / consultas | `class/ped/c_pedido_venda.php` | `select_pedidoVenda_letra`, `atualizarField`, dados do pedido |
| Agrupamento | `class/ped/c_pedido_venda_gerente_tools.php` | `incluiPedidoAgrupado`, `incluiItensPedidoAgrupado`, `cancelaPedidoAgrupado` |
| Front | `js/ped/s_pedido_venda_gerente_novo.js` | Filtros, romaneio, financeiro, NF, agrupamento |
| Template | `template/ped/pedido_venda_gerente_novo.tpl` | Grade única, barra de andamento, botões por coluna |
| NF / Financeiro produtos | `forms/ped/p_pedido_venda_nf_pecas_novo.php` | `submenu=financeiro` ou `notafiscal` |
| NF / Financeiro serviços | `forms/ped/p_pedido_venda_nf_ps.php` | `submenu=financeiro` |
| Romaneio | `forms/ped/p_pedido_venda_imp_romaneio.php` | Impressão de conferência |

Acesso: `mod=ped`, `form=pedido_venda_gerente_novo`.

---

## Pedidos exibidos na grade

A listagem padrão (`mostraPedidoGerente`) filtra pedidos com **`SITUACAO` 3 ou 6**:

| Valor | Uso na Gerência |
| ----- | ---------------- |
| **6** | **Pedido** confirmado — conferência/romaneio, financeiro, agrupamento |
| **3** | **Emitir NF** — reimpressão de romaneio, financeiro e emissão fiscal |

Outras situações (cotação 5, baixado 9, etc.) **não** entram nesta tela.

Filtros de período (botões no topo):

| Botão | `submenu` | Período |
| ----- | --------- | ------- |
| Mostrar Pedidos Dia | *(vazio)* | Emissão = dia atual |
| Mostrar Pedidos Mes | `todosPedidosMes` | 1º dia do mês até último dia do mês |
| Todos | `todosPedidos` | Sem filtro de data |

---

## Roteamento: `p_pedido_venda_conferecia_novo::controle()`

| `submenu` | Direito | Ação |
| --------- | ------- | ---- |
| *(default)* | `PedGerente` C | `mostraPedidoGerente('')` — pedidos do dia |
| `MesAtual` | S | Mês corrente |
| `todosPedidosMes` | S | Mês corrente (via parâmetro interno) |
| `todosPedidos` | S | Todos (sit 3 e 6) |
| `imprime` | R | Após romaneio em sit **6**: avança situação; sit **3**: só atualiza tela |
| `financeiro` | S | `p_pedido_venda_nf_pecas_novo` → cadastro financeiro **produtos** |
| `financeiroServico` | S | `p_pedido_venda_nf_ps` → cadastro financeiro **serviços** |
| `notafiscal` | S | `p_pedido_venda_nf_pecas_novo` → NF (+ financeiro integrado na mesma tela) |
| `agrupaPedido` | S | Agrupa pedidos marcados (transação) |

---

## Romaneio / conferência (`submenu=imprime`)

Disparado pelo JS `submitImprime()` **somente** quando o pedido está em situação **6**:

1. Abre janela do romaneio (`form=pedido_venda_imp_romaneio`).
2. Envia POST `submenu=imprime` com `id` do pedido.
3. PHP lê situação atual; se ainda for **6**, atualiza `FAT_PEDIDO.SITUACAO`:
   - Se `FAT_PARAMETRO.FLUXOPEDIDO = 'S'` na filial → situação **2**
   - Caso contrário → situação **3** (**Emitir NF**)

Em situação **3**, o botão de impressão **só reimprime** o romaneio (sem alterar situação).

---

## Colunas da grade (template)

| Coluna | Ação |
| ------ | ---- |
| Checkbox | Agrupamento — habilitado só em sit **6** |
| Cliente | Nome do cliente |
| Andamento | Barra **Pedido → Financ. → NF → Fiscal** (estado visual conforme situação, financeiro e NF) |
| Pedido | Número + **Editar** (abre `pedido_ps` alterar) + **Imprimir** romaneio |
| Emissão / Valor | Data e total |
| Financeiro | **Produtos** / **Serviços** (serviços desabilitado se `VALORSERVICOS = 0`) |
| Fiscal | **Nota fiscal** e/ou **Cupom**, conforme `SERIE` do pedido (ver abaixo) |

### Botões fiscais (`SERIE` no pedido)

| `SERIE` | Botões exibidos |
| ------- | ----------------- |
| `65` | Só **Cupom** (NFC-e) |
| `55` | Só **Nota fiscal** (NF-e) |
| Outro / vazio | **Nota fiscal** e **Cupom** |

Cupom: JS define `tipoDocFiscal=65` antes de `notafiscal`. NF-e: fluxo padrão em `p_pedido_venda_nf_pecas_novo`.

---

## Integração NF / Financeiro (`p_pedido_venda_nf_pecas_novo`)

Origem Gerência: instancia com `id` do pedido e origem `financeiro` ou `notafiscal`.

| Entrada | `formNf` | Tela |
| ------- | -------- | ---- |
| `financeiro` | `false` | Cadastro de parcelas (financeiro sem NF) |
| `notafiscal` | `true` | Cadastro integrado NF + parcelas + transportador |

Após sucesso no financeiro de produtos, o pedido costuma avançar para situação **3** (Emitir NF), conforme regras em `cadastraFinanceiro` / parâmetros da filial.

Detalhes de campos das abas **Parcelas** e **Transportador / Observação** estão no roteiro do usuário (`docs/roteiros_manual/roteiro_gerencia_pedidos.md`).

---

## Agrupamento de pedidos

1. Marcar **checkbox** de pedidos em sit **6** do **mesmo cliente** (mínimo 2).
2. Ícone **chave inglesa** → modal com frete, despesas acessórias, desconto, situação e condição de pagamento.
3. `submenu=agrupaPedido`: cancela pedidos originais, cria pedido novo e copia itens (`c_pedido_venda_gerente_tools`).

---

## Barra de andamento (front)

Estados calculados no Smarty por linha (`pedido_venda_gerente_novo.tpl`):

1. **Pedido** — sit **6** sem financeiro/NF pendente
2. **Financ.** — existe financeiro ou etapa ativa intermediária
3. **NF** — sit **3** (emitir NF)
4. **Fiscal** — nota em aberto ou rejeitada (`TEM_NOTA_ABERTA`, `TEM_NOTA_REJEITADA`)

---

## Referência rápida de arquivos

```
forms/ped/p_pedido_venda_gerente_novo.php     → controle(), mostraPedidoGerente, imprime
template/ped/pedido_venda_gerente_novo.tpl    → grade, andamento, botões
js/ped/s_pedido_venda_gerente_novo.js         → submitImprime, financeiro, NF, agrupa
forms/ped/p_pedido_venda_nf_pecas_novo.php    → financeiro / notafiscal produtos
forms/ped/p_pedido_venda_nf_ps.php            → financeiro serviços
class/ped/c_pedido_venda_gerente_tools.php    → agrupamento
```

---

## Manual do usuário (PDF)

Roteiro para montagem do PDF:

- `docs/roteiros_manual/roteiro_gerencia_pedidos.md`
- PDF publicado: `manual/ped/gerenciapedido.pdf`

Relacionado: fluxo do cadastro PS em `docs/fluxo_pedido_ps.md` e `manual/ped/pedidops.pdf`.
