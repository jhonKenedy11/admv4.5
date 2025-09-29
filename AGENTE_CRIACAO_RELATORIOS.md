# Agente de Criação de Relatórios ADM v4.5

Este agente orienta e coordena a criação de relatórios seguindo o padrão do sistema ADM v4.5. Ele atua como um **controlador especializado** para relatórios, garantindo consistência, padronização e otimização para impressão.

---

## Padrão de Estrutura de Relatórios

### 1. Estrutura de Arquivos
```
class/<modulo>/c_<nome>_relatorio.php    # Classe de lógica do relatório
forms/<modulo>/p_rel_<nome>.php          # Formulário de parâmetros
template/<modulo>/rel_<nome>.tpl          # Template do relatório
js/<modulo>/s_rel_<nome>.js              # JavaScript (se necessário)
```

### 2. Padrão de Nomenclatura
- **Classe:** `c_<modulo>_relatorio.php` ou `c_<nome>_relatorio.php`
- **Formulário:** `p_rel_<nome>.php`
- **Template:** `rel_<nome>.tpl`
- **JavaScript:** `s_rel_<nome>.js`

---

## Estrutura do Template de Relatório (Padrão Estabelecido)

### CSS de Impressão Otimizado
```css
<style>
      .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
      }

      .message-container h4 {
            color: #6c757d;
            font-size: 1.5rem;
            text-align: center;
      }

      .height100 {
            background-color: #F7F7F7;
            margin: 0;
            padding: 10px;
      }

      .print-block {
            display: flex;
            flex-direction: column;
      }

      .header-section {
            margin-bottom: 10px;
      }

      .dataHora {
            font-size: 9px;
      }

      .table {
            font-size: 10px;
            width: 100%;
            table-layout: fixed;
      }

      .table th {
            padding: 2px 3px !important;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
      }

      .table td {
            padding: 2px 3px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
      }

      .x_panel {
            margin-top: 5px;
      }

      .table-responsive {
            overflow-x: auto;
            max-width: 100%;
      }

      h2 {
            font-size: 14px;
            margin: 5px 0;
      }

      @media print {
            @page {
                  margin: 0.3cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .height100 {
                  min-height: auto !important;
                  padding: 2px !important;
            }

            .print-block {
                  page-break-inside: avoid !important;
                  display: block !important;
            }

            .header-section {
                  margin-bottom: 2px !important;
                  padding: 0 !important;
            }

            .x_panel {
                  margin-top: 1px !important;
            }

            .table-responsive {
                  page-break-inside: avoid !important;
            }

            .table {
                  page-break-inside: avoid !important;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
                  white-space: nowrap !important;
                  overflow: hidden !important;
                  text-overflow: ellipsis !important;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 10px;
                  margin: 1px 0 !important;
                  line-height: 1.2 !important;
            }

            .col-md-4, .col-md-5, .col-md-3 {
                  padding: 1px !important;
            }

            img {
                  max-width: 100px !important;
                  max-height: 25px !important;
            }
      }
</style>
```

### Estrutura HTML do Relatório
```html
<div class="height100">
      <div class="print-block">
            <div class="header-section">
                  <div class="right_col" role="main">
                        <div class="">
                              <div class="col-md-4 col-sm-4 col-xs-4">
                                    <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                              </div>
                              <div class="col-md-5 col-sm-5 col-xs-5">
                                    <div>
                                          <h2 class="text-center">
                                                <strong>{$titulo}</strong><br>
                                                <strong>Período - {$dataIni} - {$dataFim}</strong>
                                          </h2>
                                    </div>
                              </div>
                              <div class="col-md-3 col-sm-3 col-xs-3">
                                    <b class="pull-right dataHora">{$dataImp}</b>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="x_panel">
                  {if count($resultado) > 0}
                        <div class="table-responsive">
                              <table class="table table-striped" style="margin-bottom: 0;">
                                    <thead>
                                          <tr>
                                                <!-- Colunas do relatório -->
                                          </tr>
                                    </thead>
                                    <tbody>
                                          {foreach $resultado as $item}
                                                <tr>
                                                      <!-- Dados do relatório -->
                                                </tr>
                                          {/foreach}
                                    </tbody>
                              </table>
                        </div>
                  {else}
                        <div class="message-container">
                              <h4>Nenhum registro localizado!</h4>
                        </div>
                  {/if}
            </div>

            <div class="row no-print">
                  <div class="col-xs-12 text-center">
                        <button class="btn btn-default" onclick="window.print();">
                              <i class="fa fa-print"></i> Imprimir
                        </button>
                  </div>
            </div>
      </div>
</div>
```

---

## Padrão de Relatórios de Estoque (Implementado v4.5)

### Estrutura de Arquivos do Estoque
```
class/est/
├── c_estoque_relatorio.php          # Classe principal (51KB, 1401 linhas)
└── doc_tec_estoque_relatorio.md     # Documentação técnica

forms/est/
└── p_rel_estoque.php                # Formulário principal (9.8KB, 206 linhas)

template/est/
├── rel_estoque_mostra.tpl           # Tela principal com cards (11KB, 242 linhas)
├── rel_estoque_modal_parametros.tpl # Modal de parâmetros (11KB, 261 linhas)
├── rel_estoque_movimentacao.tpl     # Relatório de movimentação (9.1KB, 269 linhas)
└── rel_estoque_resumido.tpl         # Relatório resumido (6.7KB, 192 linhas)

js/est/
└── s_estoque_relatorio.js           # JavaScript (23KB, 645 linhas)
```

### Características da Classe c_estoque_relatorio

#### Propriedades Principais
```php
private $dataIni = NULL;           // Data inicial
private $dataFim = NULL;           // Data final
private $idGrupo = NULL;           // ID do grupo
private $idProduto = NULL;         // ID do produto
private $idLocalizacao = NULL;     // ID da localização
private $tipoMovimento = NULL;     // Tipo de movimento (E/S)
private $descProduto = NULL;       // Descrição do produto
private $descCliente = NULL;       // Descrição do cliente
private $idCentroCusto = NULL;     // ID do centro de custo
private $tipoRelatorio = NULL;     // Tipo do relatório
private $situacaoNota = NULL;      // Situação da nota fiscal
private $curvaAbc = NULL;          // Tipo de curva ABC
private $ordenacao = NULL;         // Tipo de ordenação
```

#### Métodos Principais
- `selectRelatorioMovimentacaoEstoque()` - Movimentações detalhadas
- `selectRelatorioResumido()` - Resumo consolidado
- `selectCurvaAbc()` - Curva ABC
- `selectKardexSintetico()` - Kardex sintético
- `selectKardexAnalitico()` - Kardex analítico
- `selectEstoqueGeral()` - Estoque geral
- `selectEstoqueLocalizacao()` - Estoque por localização
- `selectRelatorioCompras()` - Relatório de compras
- `selectMovimentoEstoqueCliente()` - Movimento por cliente
- `comboRelatorioEstoque()` - Combos de parâmetros
- `buscarProdutosJson()` - Busca de produtos via AJAX
- `buscarClientesJson()` - Busca de clientes via AJAX

### Interface Moderna (rel_estoque_mostra.tpl)

#### Características
- **Cards de Relatórios:** Interface visual com cards para cada tipo de relatório
- **Modal de Parâmetros:** Modal responsivo com filtros avançados
- **Date Range Picker:** Seletor de período com ranges pré-definidos
- **Select2:** Combos com busca e filtros dinâmicos
- **Validação:** jQuery Validate para validação client-side

#### Estrutura de Cards
```html
<div class="col-md-4 col-sm-6">
    <div class="card" onclick="abrirModalRelatorio('movimentacao')">
        <div class="card-body text-center">
            <i class="fa fa-exchange fa-3x text-primary"></i>
            <h5 class="card-title">Movimentação de Estoque</h5>
            <p class="card-text">Relatório detalhado de entradas e saídas</p>
        </div>
    </div>
</div>
```

### Modal de Parâmetros (rel_estoque_modal_parametros.tpl)

#### Filtros Disponíveis
- **Período:** Date range picker com ranges (Hoje, Últimos 7 dias, etc.)
- **Grupo:** Combo com todos os grupos ativos
- **Produto:** Combo que filtra por grupo selecionado (AJAX)
- **Localização:** Combo com localizações ativas
- **Tipo de Movimento:** Entrada, Saída ou Todos
- **Cliente/Fornecedor:** Busca com AJAX
- **Centro de Custo:** Combo de centros de custo

#### Funcionalidades JavaScript
- Carregamento dinâmico de produtos por grupo
- Validação de formulários
- Exportação de relatórios
- Formatação de valores monetários
- Mensagens de feedback

### Relatórios Disponíveis

#### 1. Movimentação de Estoque
- **Descrição:** Relatório detalhado de todas as entradas e saídas
- **Campos:** Data, Tipo, Produto, Quantidade, Valor, Documento, Usuário
- **Filtros:** Período, Grupo, Produto, Localização, Tipo
- **Exportação:** CSV, Excel, PDF, Print
- **Arquivo Excel:** `Movimentacao_Estoque_{dataIni}_a_{dataFim}.csv`

#### 2. Resumo Consolidado
- **Descrição:** Resumo de entradas e saídas por produto
- **Campos:** Produto, Total Entradas, Total Saídas, Saldo, Valores
- **Agrupamento:** Por produto
- **Cálculos:** Saldos quantitativos e monetários
- **Arquivo Excel:** `Resumo_Estoque_{dataIni}_a_{dataFim}.csv`

#### 3. Curva ABC
- **Descrição:** Classificação de produtos por valor de estoque
- **Categorias:** A (80%), B (15%), C (5%)
- **Ordenação:** Por valor de estoque decrescente
- **Filtros:** Tipo de curva (valor, quantidade, frequência)
- **Arquivo Excel:** `Curva_ABC_{dataIni}_a_{dataFim}.csv`

#### 4. Kardex Sintético/Analítico
- **Sintético:** Resumo por produto
- **Analítico:** Detalhamento de cada movimento
- **Campos:** Saldos iniciais, movimentações, saldos finais
- **Arquivo Excel Sintético:** `Kardex_Sintetico_{dataIni}_a_{dataFim}.csv`
- **Arquivo Excel Analítico:** `Kardex_Analitico_{dataIni}_a_{dataFim}.csv`

#### 5. Estoque por Localização
- **Descrição:** Posição de estoque por localização
- **Campos:** Localização, Produto, Quantidade, Valor
- **Filtros:** Localização específica
- **Arquivo Excel:** `Estoque_Localizacao_{dataImp}.csv`

#### 6. Relatório de Compras
- **Descrição:** Análise de compras e sugestões
- **Campos:** Produto, Última Compra, Estoque Mínimo, Sugestão
- **Filtros:** Período, Grupo, Situação
- **Arquivo Excel:** `Relatorio_Compras_{dataIni}_a_{dataFim}.csv`
- **Arquivo Excel Sugestões:** `Sugestoes_Compras_{dataIni}_a_{dataFim}.csv`

#### 7. Movimento por Cliente
- **Descrição:** Movimentações de estoque por cliente
- **Campos:** Cliente, Produto, Quantidade, Valor, Documento
- **Filtros:** Período, Cliente, Tipo de movimento
- **Arquivo Excel:** `Movimento_Cliente_{dataIni}_a_{dataFim}.csv`

#### 8. Consulta de Preços
- **Descrição:** Histórico de preços por produto
- **Campos:** Data, Produto, Valor Unitário, Valor Total, Desconto
- **Filtros:** Período, Produto, Cliente
- **Arquivo Excel:** `Consulta_Precos_{dataIni}_a_{dataFim}.csv`

#### 9. Tabela de Preços
- **Descrição:** Posição atual de preços
- **Campos:** Produto, Preço Custo, Preço Venda, Margem
- **Filtros:** Grupo, Localização
- **Arquivo Excel:** `Tabela_Precos_{dataImp}.csv`

#### 10. Estoque Geral
- **Descrição:** Posição geral de estoque
- **Campos:** Produto, Estoque, Reserva, Disponível, Valores
- **Filtros:** Grupo, Localização
- **Arquivo Excel:** `Estoque_Geral_{dataImp}.csv`

### Banco de Dados

#### Tabelas Principais
- `EST_MOVIMENTO`: Movimentações de estoque
- `EST_PRODUTO`: Produtos
- `EST_GRUPO`: Grupos de produtos
- `EST_LOCALIZACAO`: Localizações
- `AMB_USUARIO`: Usuários


### Padrões Seguidos

#### 1. Arquitetura MVC
- **Model:** Classe c_estoque_relatorio
- **View:** Templates Smarty
- **Controller:** Formulário p_rel_estoque

#### 2. Padrão do CRM
- Estrutura de arquivos similar
- Nomenclatura consistente
- Funcionalidades de relatório padronizadas

#### 3. Responsividade
- Bootstrap para layout
- DataTables para tabelas
- Select2 para combos

#### 4. Validação
- Validação client-side com jQuery Validate
- Validação server-side no PHP
- Feedback visual para o usuário

#### 5. Exportação para Excel
- **Botão Nativo:** Botão verde "Exportar Excel" em todos os relatórios
- **Formato CSV:** Compatível com Excel, LibreOffice, Google Sheets
- **Nomes Personalizados:** Arquivos com nomes descritivos por relatório
- **Dados Filtrados:** Exporta apenas os dados conforme parâmetros aplicados
- **Tratamento de Dados:** Escapa vírgulas e aspas automaticamente

### Funcionalidade de Exportação para Excel

#### Implementação nos Templates
Todos os templates de relatórios incluem um botão de exportação para Excel com as seguintes características:

#### Botão de Exportação
```html
<button class="btn btn-success" onclick="exportarTabelaParaExcel();">
    <i class="fa fa-file-excel-o"></i> Exportar Excel
</button>
```

#### Script JavaScript Padrão
```javascript
<script type="text/javascript">
function exportarTabelaParaExcel() {
    // Pega a tabela que já está sendo exibida
    var table = document.querySelector('.table-striped');
    if (!table) {
        alert('Tabela não encontrada!');
        return;
    }
    
    // Converte a tabela para CSV
    var csv = '';
    var rows = table.querySelectorAll('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.querySelectorAll('td, th');
        var rowData = [];
        
        for (var j = 0; j < cells.length; j++) {
            var cellText = cells[j].textContent.trim();
            // Escapa vírgulas e aspas
            if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        
        csv += rowData.join(',') + '\n';
    }
    
    // Cria o blob e faz o download
    var blob = new Blob([csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Nome_Relatorio_{$dataIni}_a_{$dataFim}.csv';
    link.click();
}
</script>
```

#### Nomes de Arquivo por Relatório
- **Movimentação:** `Movimentacao_Estoque_{dataIni}_a_{dataFim}.csv`
- **Resumo:** `Resumo_Estoque_{dataIni}_a_{dataFim}.csv`
- **Curva ABC:** `Curva_ABC_{dataIni}_a_{dataFim}.csv`
- **Kardex Sintético:** `Kardex_Sintetico_{dataIni}_a_{dataFim}.csv`
- **Kardex Analítico:** `Kardex_Analitico_{dataIni}_a_{dataFim}.csv`
- **Estoque Geral:** `Estoque_Geral_{dataImp}.csv`
- **Localização:** `Estoque_Localizacao_{dataImp}.csv`
- **Compras:** `Relatorio_Compras_{dataIni}_a_{dataFim}.csv`
- **Sugestões:** `Sugestoes_Compras_{dataIni}_a_{dataFim}.csv`
- **Movimento Cliente:** `Movimento_Cliente_{dataIni}_a_{dataFim}.csv`
- **Consulta Preços:** `Consulta_Precos_{dataIni}_a_{dataFim}.csv`
- **Tabela Preços:** `Tabela_Precos_{dataImp}.csv`

#### Características Técnicas
- **Compatibilidade:** Funciona em todos os navegadores modernos
- **Formato:** CSV UTF-8 compatível com Excel
- **Tratamento:** Escapa automaticamente vírgulas e aspas nos dados
- **Performance:** Conversão client-side sem requisições ao servidor
- **Segurança:** Não expõe dados sensíveis, apenas dados já exibidos

#### Como Aplicar em Novos Relatórios
1. **Adicionar botão** na seção `no-print` do template
2. **Incluir script** com função `exportarTabelaParaExcel()`
3. **Personalizar nome** do arquivo conforme o relatório
4. **Testar exportação** com dados reais

#### Aplicação em Outros Módulos
Para aplicar a funcionalidade de exportação para Excel em outros módulos (PED, FIN, CRM, etc.):

1. **Copiar o script padrão** para o template do relatório
2. **Personalizar o nome do arquivo** conforme o módulo e tipo de relatório
3. **Ajustar seletores** se necessário (ex: se a tabela não usar classe `table-striped`)
4. **Testar a funcionalidade** com dados reais

#### Exemplo para Módulo PED (Pedidos)
```javascript
// Nome do arquivo personalizado para pedidos
link.download = 'Relatorio_Pedidos_{$dataIni}_a_{$dataFim}.csv';
```

#### Exemplo para Módulo FIN (Financeiro)
```javascript
// Nome do arquivo personalizado para financeiro
link.download = 'Relatorio_Financeiro_{$dataIni}_a_{$dataFim}.csv';
```

#### Considerações Importantes
- **Sintaxe Smarty:** Sempre usar `{ldelim}` e `{rdelim}` para chaves no JavaScript
- **Compatibilidade:** Testar em diferentes navegadores
- **Performance:** A exportação é client-side, não impacta o servidor
- **Segurança:** Apenas dados já exibidos são exportados

### Como Usar

#### 1. Acesso
```
URL: ?mod=est&form=rel_estoque
```

#### 2. Seleção de Relatório
- Clique no card do relatório desejado
- Configure os parâmetros no modal
- Clique em "Gerar Relatório"

#### 3. Exportação
- Botões de exportação na tabela
- Formatos: CSV, Excel, PDF, Print
- Dados filtrados conforme parâmetros

### Manutenção

#### Adicionar Novo Relatório
1. Criar método na classe `c_estoque_relatorio`
2. Adicionar case no `controle()` do formulário
3. Criar template específico
4. Adicionar card na tela principal

#### Modificar Filtros
1. Atualizar propriedades da classe
2. Modificar método WHERE correspondente
3. Atualizar combo no formulário
4. Ajustar template do modal

---

## Aplicando o Padrão de Estoque para Outros Módulos

### Estrutura Recomendada para Novos Módulos

#### 1. Classe Principal
```php
class c_<modulo>_relatorio extends c_user
{
    // Propriedades específicas do módulo
    private $dataIni = NULL;
    private $dataFim = NULL;
    private $parametros = array();
    
    // Métodos principais
    public function selectRelatorioPrincipal()
    public function selectRelatorioSecundario()
    public function comboParametros()
    public function buscarDadosJson()
}
```

#### 2. Formulário Principal
```php
class p_rel_<modulo> extends c_<modulo>_relatorio
{
    function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorio':
                $this->imprimeRelatorio();
                break;
            case 'buscar_dados':
                $dados = $this->buscarDadosJson();
                echo json_encode($dados);
                break;
            default:
                $this->mostraRelatorio();
                break;
        }
    }
}
```

#### 3. Templates Necessários
- `rel_<modulo>_mostra.tpl` - Tela principal com cards
- `rel_<modulo>_modal_parametros.tpl` - Modal de parâmetros
- `rel_<modulo>_<tipo>.tpl` - Templates específicos por relatório

#### 4. JavaScript
- `s_<modulo>_relatorio.js` - Funcionalidades específicas
- Carregamento dinâmico de combos
- Validação de formulários
- Exportação de relatórios

### Exemplo de Implementação para Módulo PED (Pedidos)

#### Estrutura de Arquivos
```
class/ped/
├── c_pedido_relatorio.php
└── doc_tec_pedido_relatorio.md

forms/ped/
└── p_rel_pedido.php

template/ped/
├── rel_pedido_mostra.tpl
├── rel_pedido_modal_parametros.tpl
├── rel_pedido_vendas.tpl
└── rel_pedido_resumo.tpl

js/ped/
└── s_pedido_relatorio.js
```

#### Relatórios Sugeridos para PED
- Vendas por período
- Vendas por vendedor
- Vendas por cliente
- Produtos mais vendidos
- Status de pedidos
- Análise de faturamento

### Exemplo de Implementação para Módulo FIN (Financeiro)

#### Estrutura de Arquivos
```
class/fin/
├── c_financeiro_relatorio.php
└── doc_tec_financeiro_relatorio.md

forms/fin/
└── p_rel_financeiro.php

template/fin/
├── rel_financeiro_mostra.tpl
├── rel_financeiro_modal_parametros.tpl
├── rel_financeiro_contas_receber.tpl
└── rel_financeiro_contas_pagar.tpl

js/fin/
└── s_financeiro_relatorio.js
```

#### Relatórios Sugeridos para FIN
- Contas a receber
- Contas a pagar
- Fluxo de caixa
- DRE (Demonstração de Resultados)
- Análise de inadimplência
- Relatório de receitas e despesas

### Checklist para Implementação

#### 1. Preparação
- [ ] Definir relatórios necessários
- [ ] Mapear tabelas e campos
- [ ] Identificar filtros e parâmetros
- [ ] Planejar interface de usuário

#### 2. Desenvolvimento
- [ ] Criar classe principal
- [ ] Implementar métodos de consulta
- [ ] Criar formulário de controle
- [ ] Desenvolver templates
- [ ] Implementar JavaScript

#### 3. Testes
- [ ] Testar consultas SQL
- [ ] Validar filtros
- [ ] Testar exportação para Excel
- [ ] Verificar responsividade
- [ ] Testar impressão
- [ ] Validar nomes dos arquivos exportados
- [ ] Testar compatibilidade com diferentes navegadores
- [ ] Verificar tratamento de caracteres especiais na exportação

#### 4. Documentação
- [ ] Documentar classe
- [ ] Criar documentação técnica
- [ ] Atualizar este agente
- [ ] Registrar no sistema

---

## Fluxo de Criação de Relatório

### 1. Definir Requisitos
- Nome do relatório
- Parâmetros necessários
- Campos a exibir
- Filtros e ordenação

### 2. Criar Arquivos
- **Classe:** Lógica de consulta e filtros
- **Formulário:** Interface de parâmetros
- **Template:** Visualização e impressão
- **JavaScript:** Interações (se necessário)

### 3. Integrar ao Sistema
- Adicionar ao menu
- Configurar permissões
- Testar impressão

---

## Exemplo de Uso

**Prompt para o agente:**
```
Crie relatório para: movimentacao_estoque
Parâmetros:
- Período (obrigatório)
- Grupo (opcional)
- Produto (opcional)
- Localização (opcional)
- Tipo de movimento (E/S)

Campos a exibir:
- Data
- Tipo (Entrada/Saída)
- Produto
- Quantidade
- Valor Unitário
- Valor Total
- Documento
- Usuário
```

**Arquivos a criar:**
- `class/est/c_estoque_relatorio.php`
- `forms/est/p_rel_estoque.php`
- `template/est/rel_estoque_movimentacao.tpl`

---

## Considerações Finais

### Boas Práticas
1. **Sempre teste a impressão** antes de finalizar
2. **Use o padrão de CSS** estabelecido
3. **Mantenha consistência** entre relatórios
4. **Documente parâmetros** e campos
5. **Otimize consultas** para performance

### Padrão de Qualidade
- Relatórios devem imprimir sem quebras indesejadas
- Interface de parâmetros clara e intuitiva
- Performance otimizada para grandes volumes
- Código limpo e bem documentado

---

*Este agente garante que todos os relatórios ADM v4.5 sigam o padrão estabelecido, mantendo consistência visual e funcional, especialmente na impressão.* 