# Roteiro — Entrada de NF-e por XML

Documento para montagem do manual em PDF (usuário final). Detalhes de equivalência de produto e financeiro variam por empresa.

**Referência técnica:** `forms/est/p_nota_xml_importa.php`, `js/est/s_nota_xml_importa.js`, templates `template/est/nota_xml_importa.tpl` e `nota_xml_importa_financeiro.tpl`.

---

## Objetivo

Ensinar a **importar uma NF-e** (XML com protocolo de autorização), **visualizar e validar** emitente/itens, **resolver pendências** (fornecedor, produto ou vínculo por equivalência), **gravar a nota de entrada** no estoque e, na sequência, **gerar o financeiro** (contas a pagar) quando aplicável.

---

## Público

Recebimento, fiscal, estoque, financeiro.

---

## Pré-requisitos (como o sistema valida)

- XML **legível e completo** (com estrutura de NF-e que o sistema consegue parsear).
- **Tipo da NF no XML (`tpNF`):** o cadastro da entrada **rejeita** nota cujo XML venha como **entrada** (`tpNF = 0`). A NF-e de **compra** emitida pelo fornecedor costuma vir como **saída do emitente** (`tpNF = 1`). Se o XML estiver com tipo incompatível, o sistema exibe mensagem de tipo incorreto para importação.
- **Destinatário:** o **CNPJ do destinatário** no XML deve existir em **cadastro de empresa** (`amb_empresa`) **com centro de custo** preenchido. Caso contrário, a importação bloqueia (empresa não cadastrada / destinatário incorreto).
- **Fornecedor (emitente):** deve existir em **cadastro de cliente** com o mesmo **CNPJ** do emitente (o sistema trata fornecedor como registro em `FIN_CLIENTE`). Se não existir, a conferência oferece **CADASTRAR** (abre cadastro em nova janela).
- **Natureza de operação:** antes de gravar, selecionar uma **natureza de operação de entrada** no combo da tela (lista `tipo = 'E'` em cadastro de naturezas).
- **Produtos:** o sistema tenta casar item por **código fabricante + fabricante (pessoa)** ou **código equivalente**; linhas sem match exigem **CADASTRAR** produto ou **VINCULAR** equivalência (modal de busca e confirmação).

Parâmetros de empresa (ex.: conferência de estoque após XML) podem alterar **situação** da NF gravada; documentar só se o cliente usar essa regra.

---

## Como abrir a tela

1. Menu **Estoque** / **Fiscal** → **Importação de XML** (nome conforme menu do cliente).
2. Acesso técnico: `mod=est`, `form=nota_xml_importa`.

Título da tela: **Importa nota fiscal por XML**.

<!-- SCREENSHOT_SLOT_X01 -->

---

## Fluxo principal (ordem real dos botões)

### 1. Natureza, arquivo e visualização

1. Em **Natureza Operação**, escolher a natureza de **entrada** adequada.
2. Em **Arquivo XML**, selecionar o arquivo `.xml`.
3. Clicar em **Visualizar XML**.  
   - O sistema envia o formulário (`submenu` técnico `mostra`), lê o arquivo, mantém o XML em campo oculto para as próximas etapas e monta a **tabela de divergências** (fornecedor / nota já importada / itens pendentes) e a **grade de itens** (código no XML, descrição, NCM, CFOP, quantidades, valores, opcional **OS** por linha).

<!-- SCREENSHOT_SLOT_X02 -->

### 2. Validar (atualizar conferência sem sair da tela)

1. Com o XML já carregado, usar **Validar**.  
   - Atualiza via **AJAX** a lista de divergências e a grade de itens (`conferirAjax`).  
   - Use sempre que cadastrar fornecedor, cadastrar produto ou **vincular** equivalência, para limpar pendências.

O botão **Validar** fica oculto se não houver XML ou se a nota já estiver importada (comportamento da tela).

### 3. Resolver pendências na área “Divergências”

1. **Fornecedor não localizado:** botão **CADASTRAR** → abre cadastro em nova janela; após salvar, voltar e clicar **Validar**.
2. **Produto não localizado:** **CADASTRAR** (novo produto) ou **VINCULAR** (abre modal **Selecione o equivalente**: filtrar, buscar, selecionar linha, **Vincular**; ao concluir o sistema chama **Validar** automaticamente).
3. **Nota já importada:** mensagem de NF já cadastrada; pode aparecer ação **GERAR** relacionada a financeiro/cobrança quando não houver lançamento — conforme mensagem da tela.
4. Na grade de itens, cores alternadas indicam **origem do vínculo** (ex.: código+fabricante, só código fabricante, equivalência).

<!-- SCREENSHOT_SLOT_X03 -->

### 4. Cadastrar a nota de entrada (estoque)

1. Quando não houver divergências bloqueando, o botão **Cadastrar** fica disponível.
2. Clicar **Cadastrar** (`submenu` `cadastrar`).  
   - Grava NF e itens.  
   - Em sucesso: mensagem do tipo *“Nota cadastra, prossiga com o financeiro!”* e a tela segue para **Gerar Financeiro** (não é um único passo “gravar tudo”: financeiro é **etapa seguinte**).

<!-- SCREENSHOT_SLOT_X04 -->

### 5. Gerar financeiro (contas a pagar)

Tela **Gerar Financeiro**:

1. Conferir número, data, total, fornecedor, **série**, **natureza**, **condição de pagamento**, **centro de custo** e **gênero**.
2. Aba **Parcelas:** revisar vencimentos, valores, tipo de documento, conta, situação e observações; usar **+** / **−** se a empresa ajustar quantidade de parcelas (recalcula via `condpg` no servidor).
3. Aba **Transportador / Observação** quando usada.
4. **Confirmar:** o sistema exige que a **soma das parcelas seja igual ao total** da nota; em seguida pede confirmação para **incluir faturamento** (`gerarfinanceiro`).  
   - Sucesso: *“Nota fiscal e financeiro cadastrados!”*  
   - Se financeiro já existir para documento/série/pessoa: mensagem de erro correspondente.

**Cancelar** na tela financeira pergunta confirmação para voltar ao fluxo anterior (`submenu` vazio).

<!-- SCREENSHOT_SLOT_X05 -->

### 6. Outros botões úteis

- **Adicionar novo XML:** limpa XML carregado e remove tabelas da tela para iniciar outro arquivo.
- O sistema **bloqueia F5 / Ctrl+R** na página (aviso ao usuário) — orientar a usar os botões da tela.

<!-- SCREENSHOT_SLOT_X06 -->

---

## Fluxos especiais (documentar só se a empresa usa)

| Tema | Como funciona no sistema |
|------|---------------------------|
| Entrada por **manifesto** | Ação `entradaManifesto`: recupera XML guardado por `idNf` e reabre o fluxo de importação; uso típico quando a nota veio do MDF-e/manifesto. |
| Cobrança após NF já importada | Botão **GERAR** na conferência quando a nota existe mas falta integração financeira (fluxo `cobranca` / tela financeira). |

---

## Erros frequentes (FAQ)

- **XML inválido ou erro de leitura** — selecionar outro arquivo ou XML autorizado completo.
- **Tipo de NF incorreto para importação** — XML com `tpNF` de “entrada” no arquivo; usar XML da NF-e de compra conforme emitida pelo fornecedor (normalmente saída do emitente).
- **Destinatário / empresa** — CNPJ do destinatário não cadastrado ou sem centro de custo na empresa.
- **Fornecedor não cadastrado** — cadastrar pelo **CADASTRAR** da divergência e **Validar** de novo.
- **Produto sem vínculo** — cadastrar ou **VINCULAR** no modal e **Validar**.
- **Soma das parcelas ≠ total** — ajustar valores na grade antes de **Confirmar** no financeiro.
- **Financeiro já existe** — não duplicar inclusão para o mesmo documento/série/pessoa.

---

## Tabela — O que fotografar

| Slot | Conteúdo |
|------|-----------|
| X01 | Caminho no menu até **Importa nota fiscal por XML** |
| X02 | Combo natureza + arquivo + botões **Visualizar XML** / **Validar** / **Cadastrar** |
| X03 | Tabela **Divergências** + início da grade de itens (cores / ações) |
| X04 | **Cadastrar** disponível ou mensagem pós-gravação da NF |
| X05 | Tela **Gerar Financeiro** com aba **Parcelas** |
| X06 | Sucesso “Nota fiscal e financeiro cadastrados!” ou fluxo **Adicionar novo XML** |
