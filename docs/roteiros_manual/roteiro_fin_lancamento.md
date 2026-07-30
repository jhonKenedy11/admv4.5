## Como abrir a tela

1. Login no sistema.
2. Menu **Financeiro** (ou equivalente) → **Lançamentos** / **Lançamentos financeiros** — nome exato depende do menu do cliente.
3. Formulário técnico: `mod=fin`, `form=lancamento`.



---

## Visão geral da lista

Na **consulta**, o usuário costuma ter:

- Filtros por período, pessoa, situação do documento, situação do pagamento, tipo (receita/despesa), conta, etc.
- Grade com lançamentos ou parcelas conforme a configuração da tela.
- Botões de **novo cadastro**, **alterar**, **excluir** (quando permitido), **baixa**, **parcelamento**, **agrupamento**, **anexos** — nem todos aparecem para todos os perfis.

Registrar na captura de tela quais colunas o seu ambiente exibe.



---

## Roteiro A — Incluir novo lançamento

Objetivo: cadastrar um novo lançamento de **receita** ou **despesa** para gerar o(s) título(s)/parcela(s) no financeiro.

1. Na **lista** de lançamentos, clique em **Novo** / **Cadastrar**.
2. No formulário de lançamento, selecione o **tipo**:
  - **Receita**: valores a receber (ex.: vendas, serviços).
  - **Despesa**: valores a pagar (ex.: contas, fornecedores).
3. Informe/seleciona a **Pessoa**:
  - Use a busca por **nome** ou **CNPJ/CPF** (conforme o campo).
  - Confirme se selecionou a pessoa correta antes de seguir.
4. Preencha os dados do documento (conforme campos do seu ambiente):
  - **Documento/Número** (ex.: 12345)
  - **Série** (se existir)
  - **Histórico/Observação** (se existir)
5. Defina as **datas**:
  - **Emissão** (data do documento/lançamento)
  - **Vencimento** (quando deve ser pago/recebido)
  - Se o sistema pedir mais datas (competência, previsão etc.), preencher conforme a regra da empresa.
6. Preencha os **valores**:
  - **Valor total** do lançamento
  - Descontos/juros/multa (se existirem e forem usados)
7. Se o lançamento for **parcelado**:
  - Informe a **quantidade de parcelas**.
  - Confirme a **forma de cálculo** (igual, proporcional, primeira parcela diferente, etc. — conforme o sistema permitir).
  - Verifique na grade de parcelas se:
    - A **soma** das parcelas = **valor total**
    - Os **vencimentos** foram gerados corretamente
8. Selecione os dados financeiros obrigatórios do seu ambiente:
  - **Conta financeira** (conta/banco/caixa que será usado na baixa)
  - **Modo de pagamento** (dinheiro, boleto, pix, transferência, cartão, etc.)
  - **Gênero/Plano de contas** (classificação financeira)
  - **Centro de custo** (se a empresa usa e se o campo for obrigatório)
9. Revise os dados principais (tipo, pessoa, vencimento e valor).
10. Clique em **Salvar** / **Gravar**.
11. Confirme o resultado:
  - Verifique a mensagem de **sucesso** e se o lançamento aparece na lista.
  - Se ocorrer erro, leia a mensagem (ex.: campo obrigatório) e corrija antes de tentar salvar novamente.



---

## Roteiro B — Alterar lançamento existente

Objetivo: alterar um lançamento já cadastrado, usando os campos do formulário **Lançamentos Financeiros - Alteração**.

1. Na tela **Lançamentos Financeiros - Consulta**, use os filtros para localizar o título/parcela:
  - **Data referência** (Lan­çamento/Emissão/Vencimento/Movimento)
  - **Período**
  - **Tipo lançamento** (múltipla seleção)
  - **Situação lançamento** (múltipla seleção)
  - (opcional) **Situação documento**, **Tipo documento**, **Conta**, **Filial/Centro de custo**, **Gênero**, **Pessoa**
2. Na linha desejada, clique no botão **Editar** (ícone de lápis).
3. No formulário, ajuste os campos conforme necessário:
  - **Gênero**: campo “Gênero” (mostra a descrição) → botão de **lupa** para pesquisar/selecionar.
  - **Pessoa**: campo “Pessoa” (mostra o nome) → botão de **lupa** para pesquisar/selecionar.
  - **Dados principais** (parte superior):
    - **Data Vencimento** (`datavenc`)
    - **Modo Pag/Rec** (`modo`)
    - **Conta Bancária** (`conta`)
    - **Tipo Documento** (`tipodocto`)
    - **Valor Original** (`original`)
    - **Centro de Custo** (`centrocusto`)
    - **Observação** (`obs`)
    - **Situação** (`situacaolancamento`)
    - **Data Movimento** (`datamov`)
    - **Número Cheque** (`cheque`) (se usar)
    - **Código Barras** (`doctobancario`) (se usar)
    - **Multa / Juros / Adiantamento / Desconto** → confira o **Valor TOTAL** (`total`) após os ajustes
  - **Aba “Dados Nota”**:
    - **Documento** (`docto`)
    - **Série** (`serie`)
    - **Parcela** (`parcela`) e **N° Parcelas** (`totalParcelas`)
    - **Situação Documento** (`situacaodocto`)
    - **Observação Recibo** (`obscontabil`)
    - **Data Emissão** (`dataemissao`)
4. Se a empresa usa **Rateio**, abra a aba **Rateio** e ajuste os percentuais:
  - O sistema valida que o total do rateio deve fechar **100%**.
  - Se precisar salvar apenas o rateio, use o botão **Salvar Rateio** (e lembre: precisa existir **ID** do lançamento).
5. Clique em **Confirmar** (botão superior).
6. Valide o retorno:
  - Mensagem de sucesso (“Financeiro alterado!”) e retorno para a lista.
  - Se aparecer alerta de validação, corrija (ex.: Gênero/Pessoa não selecionados, rateio diferente de 100%).



---

## Roteiro C — Baixa de lançamento

Objetivo: registrar baixa/baixa em lote (movimento) dos títulos selecionados **na tela de consulta**, usando os recursos do menu (ícone de “chave”).

### C1 — Baixa em lote (movimento interno no sistema)

1. Na tela **Lançamentos Financeiros - Consulta**, filtre para listar os títulos que deseja baixar (normalmente em aberto).
2. Na primeira coluna da grade (checkbox), **marque** os lançamentos desejados.
3. Abra o menu de ferramentas (ícone de **chave**) e clique em **Baixa em Lote**.
4. No modal **“Baixa Títulos em Lote”**, preencha:
  - **Conta Bancária** (`contaCombo`)
  - **Data Movimento** (`mDataEmissao`)
  - O **Total** é calculado automaticamente com base nos títulos selecionados.
5. Clique em **Confirmar**.
6. Valide o resultado:
  - Mensagem de sucesso na tela.
  - O sistema abre uma janela com o relatório de baixa em lote.

### C2 — “Baixar para o banco” (integração/API, quando usada)

1. Na linha do título, clique no botão de **download** (tooltip “baixar para o banco” / “Baixa de título”).
2. Aguarde o retorno do sistema:
  - **Sucesso**: mensagem informando que a baixa foi realizada.
  - **Erro**: mensagem com o motivo (ex.: falha de comunicação/validação).
3. Se a empresa não usa integração bancária, este botão pode não fazer parte do processo do usuário final (documentar apenas se for usado no cliente).



---

## Roteiro D — Funções complementares (se existirem no menu da tela)

Objetivo: registrar no manual apenas o que o cliente usa, baseado nos botões que existem nesta tela.

### D1 — Acrescentar parcelas (no cadastro/alteração)

1. Abra um lançamento para **Alteração**.
2. No topo, abra o menu de ferramentas (ícone de **chave**).
3. Clique em **Acrescentar parcelas**.
4. Informe a **Quantidade de Parcelas para Lançamento** quando o sistema solicitar.
5. Confirme e valide a mensagem “Parcela(s) adicionada(s) com sucesso!”.
6. Volte na aba **Dados Nota** e confira:
  - Campo **N° Parcelas** atualizado
  - Parcelas geradas conforme regra do sistema

### D2 — Lançamento em lote (por “Atividade”)

1. Abra um lançamento para **Alteração**.
2. No menu de ferramentas (ícone de **chave**), clique em **Lançamento em lote**.
3. Quando o sistema solicitar, informe o **código da ATIVIDADE**:
  - Este código é a **sigla da atividade** cadastrada em **Cadastros Gerais → Atividade** (ou no cadastro específico do cliente).
  - Use exatamente a sigla conforme está no cadastro (respeitando letras/números).
4. Confirme e valide a mensagem de lançamentos cadastrados com sucesso.
5. Retorne na consulta e filtre pelo período para conferir os lançamentos gerados.

### D3 — Agrupar lançamentos (modal de agrupamento)

1. Na tela de **consulta**, marque **dois ou mais** lançamentos no checkbox da primeira coluna.
2. Abra o menu (ícone de **chave**) e clique em **Agrupar Lançamentos**.
3. Selecione apenas lançamentos que atendam às regras do sistema:
  - Mesma **Pessoa**
  - Mesmo **Tipo de Documento**
4. No modal **“Agrupamento Lançamentos”**, confira/preencha:
  - **Pessoa** (`mPessoa`)
  - **Docto** (`mNumDocto`) (se o cliente utiliza numeração no agrupado)
  - **Data Vencimento** (`mDataVencimento`)
  - **Multa / Juros / Desconto** e o **TOTAL** (`mTotal`)
5. Clique em **Confirmar**.
6. Valide se o lançamento agrupado foi criado e, se aplicável, consulte a aba **Títulos Agrupados** no cadastro.

### D4 — Baixa em lote (atalho do menu)

Usar o roteiro **C1** (a funcionalidade fica no mesmo menu de ferramentas da tela de consulta).

### D5 — Slip em lote (impressão)

1. Na tela de **consulta**, marque os lançamentos desejados.
2. Abra o menu (ícone de **chave**) e clique em **Slip em Lote**.
3. O sistema abre uma janela com a impressão dos slips dos lançamentos selecionados.
4. Se nada estiver marcado, o sistema alerta para selecionar mais de um lançamento.

### D6 — Atualizar juros (pela consulta)

1. Entenda a regra: os **percentuais de multa e de juros** usados no cálculo **não são digitados no lançamento** nessa rotina — vêm do cadastro da **conta bancária** associada ao título (campo **Conta Bancária** do lançamento). Quem define esses percentuais é o cadastro da conta; a ação **Atualizar Juros** apenas recalcula multa, juros e total com base neles, para os títulos que o sistema considera elegíveis (por exemplo recebimentos em aberto e vencidos).
2. Ajuste os filtros da consulta (período, data referência, **Conta** quando quiser limitar à conta desejada, situações, etc.) para delimitar quais títulos entram na atualização.
3. Abra o menu (ícone de **chave**) e clique em **Atualizar Juros**.
4. Aguarde a mensagem (SweetAlert):
   - **Sucesso:** “Juros atualizados com sucesso!”, ou
   - **Informação:** “Não há lançamentos para atualizar juros!”
5. Se os valores não baterem com o esperado: confira no cadastro da **conta bancária** os campos de **multa** e **juros** e se o título está na **Conta** correta.

### D7 — Reenviar cobrança bancária (gera novo título)

1. Abra um lançamento para **Alteração**.
2. No menu (ícone de **chave**), clique em **Reenviar Cobrança Bancária**.
3. Confirme a mensagem de validação (o sistema **cancela** o título atual e **gera** um novo para cobrança).
4. Após gerar, confira no cadastro os campos bancários (ex.: **Nosso Número**, dados de remessa/retorno) quando aplicável.

### D8 — Clonar financeiro (cópia do lançamento)

1. Com um lançamento aberto, no menu (ícone de **chave**) clique em **Clonar financeiro**.
2. O sistema abre o cadastro em modo **Cópia**.
3. Ajuste os campos necessários e clique em **Confirmar** para gravar como um novo lançamento.

### D9 — Anexos (aba “Anexos” no cadastro)

1. Abra o lançamento em **Alteração** (precisa ter **ID**).
2. Vá na aba **Anexos**.
3. Clique em **SELECIONAR** e escolha o arquivo (aceitos: **JPG**, **JPEG** ou **PDF**).
4. Clique em **Salvar** para anexar.
5. Valide:
  - Arquivo aparece como miniatura/preview.
  - Use **Abrir** para visualizar.
  - Use **Apagar** para excluir.
6. Regras observadas no sistema:
  - Tamanho máximo: **2MB**
  - Extensões aceitas: **JPG/JPEG/PDF**

### D10 — Rateio (aba “Rateio” no cadastro)

1. Abra a aba **Rateio**.
2. Preencha os percentuais por centro de custo (coluna **%**).
3. Confirme que a soma totaliza **100%**.
4. Clique em **Salvar Rateio**.
5. Se o lançamento ainda não tiver ID, salve o lançamento primeiro (o sistema alerta “Salve o lançamento antes de salvar o rateio!”).


| Tema              | O que explicar                                                                          |
| ----------------- | --------------------------------------------------------------------------------------- |
| Parcelas          | Menu **Acrescentar parcelas** (quantidade via prompt) e validação do total de parcelas. |
| Agrupamento       | Menu **Agrupar Lançamentos** (mesma pessoa e tipo documento) + campos do modal.         |
| Baixa em lote     | Menu **Baixa em Lote** (Conta Bancária + Data Movimento).                               |
| Slip em lote      | Menu **Slip em Lote** para impressão.                                                   |
| Anexos            | Aba **Anexos** (JPG/JPEG/PDF até 2MB): selecionar, salvar, abrir, apagar.               |
| Rateio            | Aba **Rateio**: percentuais devem fechar 100% e botão **Salvar Rateio**.                |
| Atualizar juros   | Menu **Atualizar Juros** na consulta; percentuais vêm da **conta bancária** do título; filtro **Conta** restringe o escopo. |
| Cobrança bancária | Menu **Reenviar Cobrança Bancária** (cancela e gera novo).                              |
| Clonar            | Menu **Clonar financeiro** para copiar lançamento.                                      |




---

## Erros comuns (para FAQ no final do PDF)

- Situação do documento ou pagamento impede edição — explicar que é regra de negócio.
- Pessoa ou conta não aparece — verificar cadastro e filtro de filial/empresa.
- Valor da parcela não confere — revisar total versus soma das parcelas.

---

## Tabela — O que fotografar


| Slot | Tela                                                                   |
| ---- | ---------------------------------------------------------------------- |
| 01   | Menu até **Lançamentos**                                               |
| 02   | Lista com filtros visíveis                                             |
| 03   | Cadastro novo preenchido (antes de salvar ou após sucesso)             |
| 04   | Alteração de lançamento                                                |
| 05   | Tela/modal de **baixa**                                                |
| 06   | Exemplo de recurso extra usado pela empresa (anexo, agrupamento, etc.) |


