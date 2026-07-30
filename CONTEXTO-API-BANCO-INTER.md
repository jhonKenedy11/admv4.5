# Contexto API Banco Inter - Implementações

## 🏗️ ARQUITETURA (6 camadas)

### 1. **p_api_inter.php** (Front Controller)
- **Extends**: `c_api_inter`
- **Entrada**: POST com parâmetros:
  - `submenu` - Define qual ação executar
  - `dados` - Dados da requisição
  - `id_lancamento` - ID do lançamento (quando aplicável)
  - `banco` - Identificador do banco
- **Responsabilidade**: Roteia via `switch(submenu)` para o método correto da controller

### 2. **c_api_inter.php** (Controller)
- **Extends**: `c_user`
- **Responsabilidade**: Orquestra o fluxo e chama o Service

### 3. **c_api_inter_service.php** (Service)
- **Extends**: `c_user`
- **Fluxo completo**:
  1. Consulta dados no DB (via Repository)
  2. Valida dados
  3. Monta JSON (via Builder)
  4. Envia para API (via Gateway)
  5. Atualiza DB com retorno

### 4. **c_api_inter_repository.php** (Repository)
- **Responsabilidade**: Todas as queries SQL (SELECT, INSERT, UPDATE)

### 5. **c_api_inter_json_builder.php** (Builder)
- **Extends/uses**: `c_api_inter_json_builder_validate`
- **Responsabilidades**:
  - Valida campos obrigatórios
  - Valida formatos
  - Monta payload JSON

### 6. **c_api_inter_curl.php** (Gateway)
- **Extends**: `c_user`
- **Construtor**: Carrega certificados mTLS
- **Método principal**: `executarRequisicao($json, $endpoint, $scope)`
- **Funcionalidades**:
  - Detecta ambiente (homolog/prod)
  - Monta URL automaticamente
  - Executa cURL
  - Extrai header e body da resposta
  - Retorna array com resultado

## 📝 CONVENÇÕES DE NOMENCLATURA
- `p_` = page (controller de entrada/Front Controller)
- `c_` = class (classe normal)

## 🔄 FLUXO TÍPICO DE UMA REQUISIÇÃO
