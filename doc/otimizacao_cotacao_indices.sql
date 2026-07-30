-- =====================================================
-- SUGESTÕES DE ÍNDICES PARA OTIMIZAÇÃO DA PESQUISA DE COTAÇÃO
-- =====================================================
-- Execute estes comandos no banco de dados para melhorar a performance
-- da pesquisa de produtos na cotação
-- =====================================================

-- Índice para busca por código fabricante (muito usado)
-- Verifica se já existe antes de criar
CREATE INDEX IF NOT EXISTS idx_produto_codfabricante 
ON EST_PRODUTO(CODFABRICANTE);

-- Índice para busca por código interno (muito usado)
CREATE INDEX IF NOT EXISTS idx_produto_codigo 
ON EST_PRODUTO(CODIGO);

-- Índice composto para busca por código fabricante e código (otimiza buscas combinadas)
CREATE INDEX IF NOT EXISTS idx_produto_codfabricante_codigo 
ON EST_PRODUTO(CODFABRICANTE, CODIGO);

-- Índice para busca por descrição (usado em buscas parciais)
-- IMPORTANTE: Este índice ajuda em buscas LIKE, mas não é tão eficiente quanto índices exatos
CREATE INDEX IF NOT EXISTS idx_produto_descricao 
ON EST_PRODUTO(DESCRICAO(100)); -- Primeiros 100 caracteres para otimizar

-- Índice para a tabela de equivalências (muito importante para performance)
CREATE INDEX IF NOT EXISTS idx_equivalencia_idproduto 
ON EST_PRODUTO_EQUIVALENCIA(IDPRODUTO);

CREATE INDEX IF NOT EXISTS idx_equivalencia_codequivalente 
ON EST_PRODUTO_EQUIVALENCIA(CODEQUIVALENTE);

-- Índice composto para equivalências (otimiza JOINs)
CREATE INDEX IF NOT EXISTS idx_equivalencia_completo 
ON EST_PRODUTO_EQUIVALENCIA(IDPRODUTO, CODEQUIVALENTE);

-- Índice para marca (otimiza JOIN com EST_MARCA)
CREATE INDEX IF NOT EXISTS idx_produto_marca 
ON EST_PRODUTO(MARCA);

-- =====================================================
-- VERIFICAÇÃO DE ÍNDICES EXISTENTES
-- =====================================================
-- Execute para verificar quais índices já existem:
-- SHOW INDEX FROM EST_PRODUTO;
-- SHOW INDEX FROM EST_PRODUTO_EQUIVALENCIA;
-- SHOW INDEX FROM EST_MARCA;

-- =====================================================
-- ANÁLISE DE PERFORMANCE
-- =====================================================
-- Para analisar a performance de uma consulta, use:
-- EXPLAIN SELECT ... (sua consulta aqui)
-- 
-- Verifique se os índices estão sendo usados (coluna 'key' no resultado)
-- Se 'key' estiver NULL, o índice não está sendo usado e pode precisar de ajuste

-- =====================================================
-- MANUTENÇÃO PERIÓDICA
-- =====================================================
-- Execute periodicamente para otimizar as tabelas:
-- OPTIMIZE TABLE EST_PRODUTO;
-- OPTIMIZE TABLE EST_PRODUTO_EQUIVALENCIA;
-- OPTIMIZE TABLE EST_MARCA;

-- =====================================================
-- NOTAS IMPORTANTES
-- =====================================================
-- 1. Índices melhoram SELECT mas podem diminuir performance em INSERT/UPDATE
-- 2. Índices ocupam espaço em disco
-- 3. Índices em campos muito grandes (como DESCRICAO) podem ser limitados
-- 4. Execute ANALYZE TABLE após criar índices para atualizar estatísticas
-- 
-- ANALYZE TABLE EST_PRODUTO;
-- ANALYZE TABLE EST_PRODUTO_EQUIVALENCIA;
-- ANALYZE TABLE EST_MARCA;

