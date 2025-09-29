# Documentação Técnica - Relatórios de Estoque

## Visão Geral

O módulo de relatórios de estoque foi desenvolvido seguindo o padrão do CRM, oferecendo funcionalidades para análise de movimentações de estoque com filtros avançados.

## Estrutura de Arquivos

```
class/est/
├── c_estoque_relatorio.php          # Classe principal de relatórios
└── doc_tec_estoque_relatorio.md     # Esta documentação

forms/est/
└── p_rel_estoque.php                # Formulário PHP

template/est/
├── rel_estoque_mostra.tpl           # Tela principal de relatórios
├── rel_estoque_modal_parametros.tpl # Modal de parâmetros
├── rel_estoque_movimentacao.tpl     # Relatório de movimentação
└── rel_estoque_resumido.tpl         # Relatório resumido

js/est/
└── s_estoque_relatorio.js           # JavaScript do módulo
```

## Funcionalidades

### 1. Relatório de Movimentação de Estoque
- **Descrição**: Relatório detalhado de todas as entradas e saídas de estoque
- **Filtros disponíveis**:
  - Período (data inicial e final)
  - Grupo de produtos
  - Produto específico
  - Localização
  - Tipo de movimento (Entrada/Saída/Todos)

### 2. Relatório Resumido
- **Descrição**: Resumo consolidado de entradas e saídas por produto
- **Informações exibidas**:
  - Total de entradas e saídas por produto
  - Valores monetários de entradas e saídas
  - Saldo quantitativo e monetário

## Classe c_estoque_relatorio

### Propriedades
- `$dataIni`: Data inicial do período
- `$dataFim`: Data final do período
- `$idGrupo`: ID do grupo de produtos
- `$idProduto`: ID do produto
- `$idLocalizacao`: ID da localização
- `$tipoMovimento`: Tipo de movimento (E/S)

### Métodos Principais

#### selectRelatorioMovimentacaoEstoque()
Retorna movimentações detalhadas de estoque com todos os campos relevantes.

#### selectRelatorioResumido()
Retorna resumo consolidado de movimentações por produto.

#### whereMovimentacaoEstoque()
Gera cláusula WHERE dinâmica baseada nos filtros aplicados.

#### comboRelatorioEstoque()
Carrega todas as combos necessárias para os filtros.

## Formulário p_rel_estoque

### Funcionalidades
- Controle de submenu para diferentes tipos de relatório
- Configuração do Smarty para templates
- Processamento de parâmetros POST/GET
- Exibição de relatórios específicos

### Métodos
- `controle()`: Controla o fluxo baseado no submenu
- `mostraRelatorio()`: Exibe tela principal
- `mostraRelatorioMovimentacao()`: Exibe relatório de movimentação
- `mostraRelatorioResumido()`: Exibe relatório resumido

## Templates

### rel_estoque_mostra.tpl
- Interface principal com cards de relatórios
- Modal de parâmetros integrado
- Estilo responsivo e moderno

### rel_estoque_modal_parametros.tpl
- Modal com filtros avançados
- Date range picker para período
- Select2 para combos com busca
- Validação de formulário

### rel_estoque_movimentacao.tpl
- Tabela detalhada de movimentações
- Formatação de valores monetários
- Diferenciação visual entre entradas e saídas
- DataTables com funcionalidades de exportação

### rel_estoque_resumido.tpl
- Tabela consolidada por produto
- Cálculo automático de saldos
- Formatação de valores
- Exportação em múltiplos formatos

## JavaScript (s_estoque_relatorio.js)

### Funcionalidades
- Carregamento dinâmico de produtos por grupo
- Validação de formulários
- Exportação de relatórios
- Formatação de valores
- Mensagens de feedback

### Funções Principais
- `inicializarComponentes()`: Setup inicial
- `carregarProdutosPorGrupo()`: Carregamento dinâmico
- `exportarRelatorio()`: Exportação
- `formatarMoeda()`: Formatação monetária
- `mostrarSucesso()` / `mostrarErro()`: Feedback

## Banco de Dados

### Tabelas Utilizadas
- `EST_MOVIMENTO`: Movimentações de estoque
- `EST_PRODUTO`: Produtos
- `EST_GRUPO`: Grupos de produtos
- `EST_LOCALIZACAO`: Localizações
- `AMB_USUARIO`: Usuários

### Campos Principais
- `M.DATA`: Data da movimentação
- `M.TIPO`: Tipo (E=Entrada, S=Saída)
- `M.QUANTIDADE`: Quantidade movimentada
- `M.VALOR_UNITARIO`: Valor unitário
- `M.VALOR_TOTAL`: Valor total
- `M.DOCUMENTO`: Documento de origem
- `M.OBSERVACAO`: Observações

## Padrões Seguidos

### 1. Arquitetura MVC
- **Model**: Classe c_estoque_relatorio
- **View**: Templates Smarty
- **Controller**: Formulário p_rel_estoque

### 2. Padrão do CRM
- Estrutura de arquivos similar
- Nomenclatura consistente
- Funcionalidades de relatório padronizadas

### 3. Responsividade
- Bootstrap para layout
- DataTables para tabelas
- Select2 para combos

### 4. Validação
- Validação client-side com jQuery Validate
- Validação server-side no PHP
- Feedback visual para o usuário

## Como Usar

### 1. Acesso
```
URL: ?mod=est&form=rel_estoque
```

### 2. Seleção de Relatório
- Clique no card do relatório desejado
- Configure os parâmetros no modal
- Clique em "Gerar Relatório"

### 3. Filtros Disponíveis
- **Período**: Seletor de data com ranges pré-definidos
- **Grupo**: Combo com todos os grupos ativos
- **Produto**: Combo que filtra por grupo selecionado
- **Localização**: Combo com localizações ativas
- **Tipo**: Entrada, Saída ou Todos

### 4. Exportação
- Botões de exportação na tabela
- Formatos: CSV, Excel, PDF, Print
- Dados filtrados conforme parâmetros

## Manutenção

### Adicionar Novo Relatório
1. Criar método na classe `c_estoque_relatorio`
2. Adicionar case no `controle()` do formulário
3. Criar template específico
4. Adicionar card na tela principal

### Modificar Filtros
1. Atualizar propriedades da classe
2. Modificar método `whereMovimentacaoEstoque()`
3. Atualizar combo no formulário
4. Ajustar template do modal

### Personalizar Exportação
1. Implementar método de exportação no formulário
2. Configurar headers HTTP apropriados
3. Formatar dados conforme formato desejado

## Considerações de Performance

### 1. Índices Recomendados
```sql
-- Índices para melhor performance
CREATE INDEX idx_movimento_data ON EST_MOVIMENTO(DATA);
CREATE INDEX idx_movimento_tipo ON EST_MOVIMENTO(TIPO);
CREATE INDEX idx_movimento_produto ON EST_MOVIMENTO(CODIGO_PRODUTO);
CREATE INDEX idx_movimento_localizacao ON EST_MOVIMENTO(ID_LOCALIZACAO);
```

### 2. Otimizações
- Limitação de registros por consulta
- Paginação em relatórios grandes
- Cache de combos frequentes
- Compressão de dados

### 3. Monitoramento
- Log de consultas lentas
- Métricas de uso
- Alertas de performance

## Segurança

### 1. Validação de Entrada
- Sanitização de parâmetros
- Validação de tipos de dados
- Prevenção de SQL Injection

### 2. Controle de Acesso
- Verificação de permissões
- Log de acessos
- Auditoria de relatórios

### 3. Dados Sensíveis
- Mascaramento de valores críticos
- Controle de exportação
- Log de downloads

## Versões

### v4.5 (Atual)
- Implementação inicial
- Relatórios básicos de movimentação
- Filtros essenciais
- Interface responsiva

### Próximas Versões
- Relatórios avançados (Curva ABC, etc.)
- Gráficos e dashboards
- Exportação avançada
- Integração com outros módulos 