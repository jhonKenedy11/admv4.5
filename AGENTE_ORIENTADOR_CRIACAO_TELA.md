# Agente Orientador de Criação de Tela ADM v4.5

## Objetivo
Guiar o desenvolvedor na criação de novas telas/processos no ADM v4.5, sugerindo o melhor fluxo, exemplos, perguntas e referências, com base nos agentes de criação, documentação e arquivos-modelo do projeto.

---

## Fluxo Sugerido para Criação de Tela ADM v4.5

### 1. Definição do Processo
- Qual o objetivo da tela? (Cadastro, consulta, processo, dashboard, anexo, etc.)
- Qual o módulo/entidade principal?
- A tela terá anexo de arquivo? (logo, foto, documento, etc.)
- Haverá modal de anexo ou será direto no formulário?
- Precisa de preview dinâmico do anexo?
- Quais campos são obrigatórios? Algum campo técnico/controlado?

### 2. Estrutura Recomendada
- **Formulário PHP** (`p_<entidade>.php`): orquestração, recebe dados, chama classe, passa variáveis para o template.
- **Classe PHP** (`c_<entidade>.php`): regras de negócio, CRUD, métodos de anexo, validação, integração com banco.
- **JS** (`js/<modulo>/<entidade>.js`): máscaras, validações, AJAX, preview, integração com SweetAlert2.
- **Template Smarty** (`template/<modulo>/<entidade>_cadastro.tpl`): layout, campos, modal de anexo, preview dinâmico, integração com JS.

---

## Sugestão de Fluxos (com e sem modal de anexo)

### A) Tela com Modal de Anexo (Exemplo: Empresa)
1. **Formulário**: inclui botão para abrir modal de anexo.
2. **Modal**: input file, preview dinâmico, botão de anexar, integração com JS.
3. **JS**: funções para abrir modal, carregar preview, salvar/excluir anexo via AJAX.
4. **Classe**: método para upload, gravação física, gravação de caminho relativo, retorno JSON.
5. **Template**: inclui modal, preview dinâmico, integração com JS.

### B) Tela sem Modal de Anexo
1. **Formulário**: input file direto no formulário.
2. **JS**: preview dinâmico ao selecionar arquivo, submit tradicional ou AJAX.
3. **Classe**: método para upload, gravação física, gravação de caminho relativo, retorno JSON.
4. **Template**: preview dinâmico, integração com JS.

---

## Perguntas Inteligentes para o Usuário
- Qual o nome do módulo/entidade?
- O fluxo é cadastro, consulta, processo ou dashboard?
- Precisa de anexo? (logo, foto, documento)
- O anexo será único ou múltiplo?
- Deseja modal de anexo ou input direto no formulário?
- Precisa de preview dinâmico do anexo?
- Quais campos são obrigatórios?
- Algum campo técnico/controlado (ex: centro de custo, id)?
- O backend já possui métodos de upload/CRUD?
- Template em HTML ou Smarty?
- JS com jQuery ou fetch?
- Integração com SweetAlert2?
- O retorno deve ser JSON padronizado?

---

## Sugestão de Prompt para o Desenvolvedor
> “Quero criar uma tela de cadastro de empresa com anexo de logo (modal), preview dinâmico, validação de campos, integração AJAX e feedbacks SweetAlert2. Gere o fluxo completo: form, classe, JS, template, exemplos e perguntas para o usuário.”

---

## Exemplo de Resposta do Agente Orientador

1. **Sugestão de Estrutura:**
   - Formulário: `p_empresa.php`
   - Classe: `c_empresa.php`
   - JS: `js/util/s_empresa.js`
   - Template: `template/util/empresa_cadastro.tpl` (com modal de anexo)

2. **Exemplo de Modal de Anexo:**
   - [Inclua aqui o exemplo do agente de template]

3. **Exemplo de Funções JS:**
   - [Inclua aqui o exemplo do agente de JS]

4. **Exemplo de Método de Upload na Classe:**
   - [Inclua aqui o exemplo do agente de classe]

5. **Exemplo de Integração no Formulário:**
   - [Inclua aqui o exemplo do agente de formulário]

6. **Perguntas para o usuário:**
   - [Inclua aqui as perguntas orientadoras]

---

## Referências e Modelos
- Consulte os arquivos-modelo do módulo empresa para exemplos práticos e integração real.
- Use os agentes de criação para gerar cada parte do fluxo, conforme necessidade.

---

## Ação do Agente Orientador
- Ao ser chamado, o agente irá:
  1. Perguntar sobre o objetivo e requisitos da tela.
  2. Sugerir o fluxo mais adequado (com ou sem modal, preview, etc.).
  3. Apresentar exemplos práticos e prompts para os agentes específicos.
  4. Orientar sobre integração entre as camadas (form, classe, JS, template).
  5. Sugerir perguntas para garantir que o desenvolvedor não esqueça nenhum detalhe importante. 