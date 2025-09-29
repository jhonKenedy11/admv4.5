# Agente de Criação de Classe PHP ADM v4.5 (Padrão Atualizado)

Este agente é responsável por gerar **apenas a classe PHP** de um novo módulo ou funcionalidade no sistema ADM v4.5, seguindo o padrão PSR-12, as regras específicas do projeto e as práticas mais recentes identificadas nas classes atuais.

---

## Padrão para Upload de Logo/Foto (Novo)

- Método recebe array com id e arquivo.
- Valida extensão/tamanho.
- Salva arquivo físico em /images/logo.png do cliente.
- Salva caminho relativo (ex: /cliente/images/logo.png) no banco.
- Não exclui registros antigos, apenas sobrescreve o arquivo físico.
- Retorna JSON padronizado.

### Exemplo de método
```php
public function salvarLogoEmpresa($dados)
{
    try {
        $id_empresa = (int)$dados['id_empresa'];
        $file = $dados['file'] ?? null;
        if (!$file || $file['error'] !== 0) {
            throw new Exception('Nenhum arquivo enviado ou erro no upload.');
        }
        $nameFile = 'logo.png';
        $tmp_dir = $file['tmp_name'];
        $sizeFile = $file['size'];
        $anexoExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($anexoExt !== 'png') {
            throw new Exception('Apenas arquivos PNG são permitidos.');
        }
        if ($sizeFile > 2000000) {
            throw new Exception('O arquivo é muito grande. Tamanho máximo permitido: 2MB.');
        }
        $upload_dir = "/images/";
        $upload_dir_full = ADMraizCliente . $upload_dir;
        if (!file_exists($upload_dir_full)) {
            mkdir($upload_dir_full, 0755, true);
        }
        $path = $upload_dir_full . $nameFile;
        $parts = explode('/', ADMraizCliente);
        $cliente = isset($parts[4]) ? $parts[4] : '';
        $path_db = '/' . $cliente . '/images/logo.png';
        $banco = new c_banco_pdo();
        $sql = "INSERT INTO AMB_GED (`TABLE`, `TABLE_ID`, `PATH`, `DESTAQUE`, `USER_INSERT`) VALUES ('AMB_EMPRESA', ?, ?, 'S', ?)";
        $banco->prepare($sql);
        $banco->bindValue(1, $id_empresa);
        $banco->bindValue(2, $path_db);
        $banco->bindValue(3, $this->m_userid ?? '');
        $banco->execute();
        $idLogo = $banco->lastInsertId();
        if (!move_uploaded_file($tmp_dir, $path)) {
            throw new Exception('Falha ao mover o arquivo para a pasta images.');
        }
        return [
            'success' => true,
            'id' => $idLogo,
            'caminho' => $path_db,
            'message' => 'Logo enviada com sucesso!'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
```

### Perguntas orientadoras para o usuário
- Qual o nome do módulo/entidade?
- O anexo será único ou múltiplo?
- Qual o diretório/caminho desejado para salvar o arquivo?
- Qual o formato permitido?
- O retorno deve ser JSON padronizado?

### Exemplo de prompt para ativação
> "Preciso de um método na classe c_empresa para upload de logo, que salve o arquivo em /images/logo.png, grave o caminho relativo no banco e retorne JSON de sucesso/erro."

---

## Estrutura Padrão da Classe (Atualizada)

1. **Bloco de comentário no topo** com:
   - `@package`, `@name`, `@version`, `@copyright`, `@link`, `@author`, `@date`
2. **Definição de `$dir`**:
   ```php
   $dir = dirname(__FILE__);
   ```
3. **Includes relativos** necessários (ex: require_once($dir . '/../../../bib/c_database_pdo.php');)
4. **Herança de classes base**: Quando necessário, a classe pode **estender uma classe utilitária/contextual** (ex: `extends c_user`) para acesso a informações do usuário, permissões, integrações ou utilidades compartilhadas entre módulos.
5. **Sem namespace** (exceto se o projeto exigir)
6. **Classe com nome em camelCase** (ex: `class c_empresa`)
7. **Propriedades privadas** para cada campo, em snake_case (ex: `$empresa_id`)
8. **Métodos em camelCase, em português** (ex: `incluiEmpresa`, `alteraEmpresa`)
9. **Parâmetros e variáveis locais em snake_case** (ex: 'nome_empresa', 'empresa_id')
10. **CRUD completo e métodos de filtro**: sempre implementar `inclui<Entidade>`, `altera<Entidade>`, `exclui<Entidade>`, `seleciona<Entidade>PorId`, `selecionaTodas<Entidade>`, e métodos de filtro (ex: LIKE)
11. **Uso obrigatório de PDO** com SQL **parametrizado** em todos os métodos
12. **JOINs contextuais e seleção apenas dos campos necessários**: Utilize JOINs para buscar dados auxiliares relevantes (ex: buscar `CASASDECIMAIS` de outra tabela como em `LEFT JOIN FAT_PARAMETRO f ON f.FILIAL = e.CENTROCUSTO`). Evite SELECT *. Sempre selecione apenas os campos necessários para a regra de negócio.
13. **Validação de parâmetros obrigatórios** e **tratamento de erros** com try/catch em todos os métodos
14. **Retorno padronizado**: array de dados, número de linhas afetadas, último ID inserido, ou mensagem de erro
15. **Docblocks detalhados** para cada método principal, explicando parâmetros e retorno
16. **Evitar setters/getters** exceto em casos complexos (preferir acesso direto via array ou propriedades)
17. **Sem lógica de apresentação**: apenas regras de negócio e acesso a dados
18. **Espaço em branco entre métodos** para legibilidade
19. **Sem tag de fechamento PHP** (padrão moderno)

---

## Regras e Observações Recentes
- **Centro de custo**: sempre gerado automaticamente, pulando para o próximo múltiplo de 10.000.000 (ex: 10000000 → 20000000 → 30000000), nunca vindo do usuário.
- **Casas decimais**: não pertence à tabela de empresa, deve ser salva em FAT_PARAMETRO vinculada ao centro de custo, somente após sucesso na inclusão da empresa.
- **Telefone**: sempre separar DDD (dois primeiros dígitos) e número no backend, salvando em campos distintos.
- **CEP**: sempre salvar apenas os números, sem traço ou caracteres especiais.
- **empresa_id**: não é editável nem aceito do usuário.

## Exemplo de método de inclusão com regras recentes
```php
/**
 * Inclui uma nova empresa e salva casas decimais em FAT_PARAMETRO
 * - Centro de custo é gerado sequencialmente (10000000, 20000000, ...)
 * - Telefone é salvo separado em DDD e número
 * - CEP é salvo apenas com números
 * - Casas decimais é salva em FAT_PARAMETRO após sucesso
 */
public function incluiEmpresa($dados_empresa) {
    // ... validação dos campos obrigatórios ...
    $centro_custo = $this->geraCentroCustoSequencial();
    $telefone = preg_replace('/\D/', '', $dados_empresa['telefone']);
    $fonearea = substr($telefone, 0, 2);
    $fonenum = substr($telefone, 2);
    $cep = preg_replace('/\D/', '', $dados_empresa['cep']);
    // ... insert em AMB_EMPRESA ...
    // Após sucesso:
    // insert em FAT_PARAMETRO (FILIAL = $centro_custo, CASASDECIMAIS = $dados_empresa['casas_decimais'])
}
```

## Exemplo de geração de centro de custo
```php
/**
 * Gera o próximo centro de custo sequencial para empresa
 * Sempre soma 10.000.000 ao último cadastrado (ex: 10000000 → 20000000)
 */
private function geraCentroCustoSequencial() {
    $banco = new c_banco_pdo();
    $sql = "SELECT MAX(CENTROCUSTO) as max_cc FROM AMB_EMPRESA";
    $banco->prepare($sql);
    $banco->execute();
    $res = $banco->fetchAll();
    $ultimo = isset($res[0]['max_cc']) ? (int)$res[0]['max_cc'] : 0;
    if ($ultimo < 10000000) {
        $novo = 10000000;
    } else {
        $novo = $ultimo + 10000000;
    }
    return strval($novo);
}
```

## Observação
- Nunca aceite campos técnicos (centro de custo, empresa_id) do usuário.
- Sempre trate telefone e CEP no backend.
- Sempre salve casas decimais em FAT_PARAMETRO após sucesso na inclusão da empresa.

## Observação sobre tratamento no front e back
- O tratamento de informações como separação de DDD/número, limpeza de CEP, etc., pode ser feito no backend **ou**, quando possível, já no frontend (JS), para garantir melhor experiência do usuário e dados limpos.
- Exemplo de máscara/validação no front:
  ```js
  // Máscara de telefone (Inputmask ou similar)
  $('#telefone').inputmask({
    mask: ['(99) 9999-9999', '(99) 99999-9999'],
    keepStatic: true
  });
  // Máscara de CEP
  $('#cep').inputmask('99999-999');
  ```
- Mesmo com tratamento no front, **sempre valide e trate no backend** para garantir integridade dos dados.

---

### Padrão de Colunas e Atualização

- **Endereço:** Sempre use ENDERECO para rua/logradouro e UF para estado, tanto no insert quanto no update.
- **Telefone:** Sempre salve e atualize telefone em dois campos: FONEAREA (DDD) e FONENUM (número), nunca em um campo único.
- **Update:** O update em AMB_EMPRESA deve contemplar: NOMEEMPRESA, NOMEFANTASIA, CNPJ, INSCESTADUAL, CEP, ENDERECO, NUMERO, COMPLEMENTO, BAIRRO, CIDADE, UF, CODMUNICIPIO, EMAIL, FONEAREA, FONENUM
- **Campos não editáveis pelo front:** REGIMETRIBUTARIO, MSG_INFORMACAO_COMPLEMENTAR, CASASDECIMAIS devem ser readonly/disabled no front e só alterados por lógica administrativa.
- **Validação e tratamento:** Sempre separe DDD/número e limpe CEP no backend, tanto no insert quanto no update.
- **Retorno:** Retorne true em caso de sucesso no update para facilitar o controle de mensagens no front.

---

## O que o agente precisa para gerar a classe

- **Nome da classe** (ex: `c_empresa`)
- **Descrição/responsabilidade da classe**
- **Lista de propriedades** (nome e tipo de cada campo)
- **Quais métodos CRUD ou operações principais a classe deve ter**
- **Quais campos são obrigatórios para cada operação**
- **Quais includes/bibliotecas são necessários** (ex: banco, utilitários)
- **Se a classe deve estender alguma base utilitária/contextual** (ex: `c_user`)
- **Autor e data** (opcional, se não informado, o agente pode sugerir ou pedir)

Se algum desses itens não for informado, o agente irá **solicitar ao usuário** para complementar antes de gerar o esqueleto da classe.

---

## Exemplo de Prompt para o Agente

```
Crie uma classe para: empresa
Descrição: Administração dos parâmetros e informações da empresa
Propriedades:
- empresa_id (int)
- nome_empresa (string)
- nome_fantasia (string)
- centro_custo (int)
- cnpj (string)
Métodos: incluiEmpresa, alteraEmpresa, excluiEmpresa, selecionaEmpresaPorId, selecionaTodasEmpresas, selecionaEmpresasFiltradas
Campos obrigatórios para incluir/alterar: todos
Bibliotecas: c_database_pdo.php
Herda: c_user
Autor: Joshua Silva
Data: 14/07/2025
```

---

## Exemplo de JOIN contextual e seleção de campos auxiliares

```php
$sql = "SELECT 
        e.EMPRESA, 
        e.NOMEEMPRESA, 
        e.NOMEFANTASIA, 
        e.CNPJ, 
        e.CENTROCUSTO, 
        e.REGIMETRIBUTARIO, 
        f.CASASDECIMAIS
    FROM AMB_EMPRESA e
    LEFT JOIN FAT_PARAMETRO f ON f.FILIAL = e.CENTROCUSTO
    WHERE e.NOMEEMPRESA LIKE ?";
```
> Utilize JOINs para buscar informações complementares relevantes para o negócio, sempre selecionando apenas os campos necessários.

---

## Fluxo do Agente

1. **Receber as informações acima** (ou pedir ao usuário caso falte algo)
2. **Gerar o cabeçalho e includes** conforme padrão
3. **Gerar a estrutura da classe** com propriedades privadas, métodos CRUD e de filtro, docblocks detalhados, validação e tratamento de erros, SQL otimizado e parametrizado, JOINs contextuais quando necessário
4. **Evitar setters/getters** exceto em casos complexos
5. **Garantir espaçamento, nomenclatura e ausência de tag de fechamento PHP**

---

*Este agente é chamado pelo agente de criação de tela, e gera apenas a classe PHP seguindo o padrão ADM v4.5 atualizado, incluindo herança de classes base e JOINs contextuais quando necessário.* 

### Boas práticas finais
- Ao finalizar a atualização ou criação de classe, sempre pergunte ao usuário se deseja remover comandos de debug (`debugger;`, `console.log` no JS, variáveis de debug no PHP). 

### Boas práticas para funções de banco
- Ao criar funções que interagem com o banco de dados (insert, update, select), sempre solicite ao usuário o DDL (estrutura SQL) da tabela envolvida para garantir alinhamento de campos, tipos e regras de negócio. 