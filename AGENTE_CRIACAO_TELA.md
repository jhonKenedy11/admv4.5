# Agente de Criação de Tela Administrativa ADM v4.5

Este agente orienta e coordena a criação de telas administrativas seguindo o padrão do sistema ADM v4.5. Ele atua como um **controlador de agentes**, delegando a geração de cada componente (classe, formulário, JS, template) para o respectivo agente especializado. Assim, garante-se consistência, padronização e separação de responsabilidades no processo de criação de telas.

> **Nota:** Mesmo que nem todos os agentes especializados existam, este agente já está preparado para chamá-los conforme forem implementados (ex: AGENTE_CRIACAO_CLASSE.md, AGENTE_CRIACAO_FORMULARIO.md, AGENTE_CRIACAO_JS.md, AGENTE_CRIACAO_TEMPLATE.md).

---

## Como Utilizar

1. **Informe o nome da funcionalidade** (ex: `empresa`).
2. **Liste os campos** e suas características (nome, tipo, validação, etc).
3. Siga o fluxo abaixo para gerar os arquivos necessários.

---

## Padrão de Nomenclatura dos Arquivos

- **Classe PHP:** `class/util/c_<nome>.php`
- **Formulário PHP:** `forms/util/p_<nome>.php`
- **JavaScript:** `js/util/s_<nome>.js`
- **Template Smarty:** `template/util/<nome>_cadastro.tpl`

> Para telas de exibição/listagem, use o sufixo `_mostra.tpl` no template.

---

## Fluxo de Criação

1. **Receber nome e campos**
2. **Gerar nomes dos arquivos** conforme padrão
3. **Delegar a geração de cada arquivo para o agente correspondente:**
   - **Classe:** Chama o AGENTE_CRIACAO_CLASSE.md
   - **Formulário:** Chama o AGENTE_CRIACAO_FORMULARIO.md
   - **JS:** Chama o AGENTE_CRIACAO_JS.md
   - **Template:** Chama o AGENTE_CRIACAO_TEMPLATE.md
4. **Orientar integração:** Como incluir no menu, permissões, etc.

---

## Exemplo de Uso

**Prompt para o agente:**

```
Crie tela administrativa para: empresa
Campos:
- amb_empresa (texto, obrigatório)
- nome_empresa (texto, obrigatório)
- centro_custo (texto)
- cnpj (texto, validação CNPJ)
- fat_parametros (texto ou select)
- regra_casadecimal (select: 2 ou 4)
```

**Arquivos a criar:**
- `class/util/c_empresa.php`
- `forms/util/p_empresa.php`
- `js/util/s_empresa.js`
- `template/util/empresa_cadastro.tpl`

**Próximos passos:**
1. Criar os arquivos acima com os campos especificados.
2. Integrar o formulário ao menu do sistema.
3. Testar cadastro e edição dos dados.

---

## Agentes Especializados

Este agente coordena com os seguintes agentes especializados:

- **AGENTE_CRIACAO_CLASSE.md** - Geração de classes PHP
- **AGENTE_CRIACAO_FORMULARIO.md** - Geração de formulários PHP
- **AGENTE_CRIACAO_JS.md** - Geração de JavaScript
- **AGENTE_CRIACAO_TEMPLATE.md** - Geração de templates Smarty
- **AGENTE_CRIACAO_RELATORIOS.md** - Geração de relatórios (padrão especializado)

---

## Relatórios ADM v4.5

Para criação de relatórios, utilize o **AGENTE_CRIACAO_RELATORIOS.md** que segue padrões específicos:

### Características dos Relatórios
- **Otimização para impressão** (sem quebras de página indesejadas)
- **Parâmetros configuráveis** (período, filtros, agrupamentos)
- **Templates padronizados** com cabeçalho e tabela
- **CSS de impressão otimizado** (landscape, margens reduzidas)

### Padrão de Arquivos para Relatórios
- **Classe:** `class/<modulo>/c_<nome>_relatorio.php`
- **Formulário:** `forms/<modulo>/p_rel_<nome>.php`
- **Template:** `template/<modulo>/rel_<nome>.tpl`

### Padrão Avançado de Relatórios (Estoque v4.5)
O módulo de estoque implementou um padrão avançado que pode ser replicado para outros módulos:

#### Estrutura Completa
```
class/<modulo>/c_<modulo>_relatorio.php    # Classe principal
forms/<modulo>/p_rel_<modulo>.php         # Formulário principal
template/<modulo>/
├── rel_<modulo>_mostra.tpl              # Tela principal com cards
├── rel_<modulo>_modal_parametros.tpl    # Modal de parâmetros
└── rel_<modulo>_<tipo>.tpl              # Templates específicos
js/<modulo>/s_<modulo>_relatorio.js      # JavaScript
```

#### Características do Padrão Avançado
- **Interface Moderna:** Cards de relatórios com modal de parâmetros
- **Filtros Avançados:** Date range picker, Select2, busca AJAX
- **Múltiplos Relatórios:** Um formulário gerencia vários tipos
- **Exportação Completa:** CSV, Excel, PDF, Print
- **Responsividade:** Bootstrap + DataTables

### Exemplo de Uso para Relatórios
```
Crie relatório para: movimentacao_estoque
Parâmetros:
- Período (obrigatório)
- Grupo (opcional)
- Produto (opcional)
- Localização (opcional)
- Tipo de movimento (E/S)

Campos a exibir:
- Data, Tipo, Produto, Quantidade, Valor, Documento, Usuário
```

---

## Estrutura Básica dos Arquivos

### Classe PHP (`c_<nome>.php`)
- Métodos: `getDados()`, `salvarDados($dados)`, `validar($dados)`

### Formulário PHP (`p_<nome>.php`)
- Carrega classe, busca dados, processa POST, envia para template

### JavaScript (`s_<nome>.js`)
- Validação de campos, envio AJAX (se aplicável)

### Template Smarty (`<nome>_cadastro.tpl`)
- Formulário com campos, botões, mensagens

---

## Recomendações
- Siga sempre o padrão de nomenclatura e estrutura.
- Consulte exemplos existentes no módulo `util`.
- Documente campos e regras de validação no início de cada arquivo.
- Teste o fluxo completo após a criação.

---

*Este agente foi criado para padronizar, agilizar e orquestrar a criação de telas administrativas no ADM v4.5, atuando como controlador dos agentes especializados de cada componente.* 