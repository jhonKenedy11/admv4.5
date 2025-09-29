# Agente de Criação de Formulário PHP ADM v4.5 (Padrão Atualizado)

Este agente orienta a criação de **formulários PHP** (`p_<nome>.php`) para telas administrativas do ADM v4.5, refletindo as práticas mais recentes do projeto. Garante padronização, clareza, separação de responsabilidades e integração correta com o menu lateral, templates e anexos.

---

## Padrão Atual de Criação de Formulários ADM v4.5

1. **Bloco de comentário no topo** (opcional, mas recomendado)
2. **Includes necessários** (Smarty, classe de negócio, etc.)
3. **Definição da classe do formulário** (ex: `class p_empresa extends c_empresa`)
4. **Construtor:**
   - Inicializa Smarty
   - Recupera variáveis de POST/GET e atribui **diretamente às propriedades** do objeto
   - Passa variáveis globais obrigatórias para o Smarty (`mod`, `form`, `user`, etc.)
   - Define submenu/ação
5. **Método `controle()`:**
   - Switch para tratar as ações principais (incluir, alterar, excluir, listar)
   - Sempre chama a classe para lógica de negócio (CRUD, filtros, etc.)
   - Nunca implementa lógica de negócio no form
   - Passa mensagens e dados para o método de desenho
6. **Método de desenho (`desenhaCadastro<Nome>()`):**
   - Decide entre select geral ou filtrado conforme propriedades do objeto
   - Prepara variáveis para o template (dados, mensagens, contexto)
   - Passa variáveis de contexto para o Smarty (`mod`, `form`, etc.)
   - Chama o template correto (`*_cadastro.tpl` ou `*_mostra.tpl`)
   - Sempre inclui `{include file="template/database.inc"}` ao final do template
7. **Execução principal:** instancia o form e chama `controle()`
8. **Separação de responsabilidades:**
   - Orquestração no form
   - Lógica de negócio na classe
   - Apresentação no template
   - Interação no JS

---

## Padrão de Anexo de Logo/Foto (Novo)

- Modal de anexo com input file (PNG), preview dinâmico (`<div id="logoExistente">`), botão de anexar.
- Integração AJAX com backend, retorno JSON.
- Feedbacks com SweetAlert2.
- Exemplo de chamada: `salvarLogoEmpresa()`.

### Exemplo de Modal de Anexo
```html
<div class="modal fade" id="ModalAnexoLogo" tabindex="-1" role="dialog" aria-labelledby="ModalAnexoLogoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalAnexoLogoLabel">Anexar Logo/Foto da Empresa</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_empresa_logo" value="{$dados.EMPRESA}">
        <div id="logoExistente" class="row"></div>
        <form id="formLogoEmpresa" enctype="multipart/form-data">
          <input type="file" class="form-control-file" id="logoEmpresa" name="logoEmpresa" accept="image/png">
          <small class="text-muted">Apenas arquivos PNG são permitidos.</small>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="btnSalvarLogo" onclick="salvarLogoEmpresa()">Anexar</button>
      </div>
    </div>
  </div>
</div>
```

---

## Perguntas orientadoras para o usuário
- Qual o nome do módulo/entidade?
- O anexo será único ou múltiplo?
- Deseja preview dinâmico?
- Precisa de botão para excluir/abrir?
- O backend já possui método de upload?
- Template em HTML ou Smarty?
- JS com jQuery ou fetch?
- Integração com SweetAlert2?
- O retorno deve ser JSON padronizado?

### Exemplo de prompt para ativação
> "Quero criar um formulário de cadastro de empresa com anexo de logo, preview dinâmico e validação de campos. Gere o HTML, JS e explique como integrar ao backend."

---

## Integração com JS
- Chame `abrirModalLogo({$dados.EMPRESA})` ao abrir a modal.
- O botão de anexar chama `salvarLogoEmpresa()`.
- O preview é atualizado automaticamente após upload.

---

## Integração com Backend
- O backend deve receber o arquivo como `file` e o id como `id_empresa`.
- O retorno deve ser JSON padronizado.

---

## Padrão de Nomenclatura e Variáveis

- **Nome do arquivo:** `p_<nome>.php`
- **Classe:** `p_<nome> extends c_<nome>`
- **Propriedades privadas** para cada campo do formulário
- **Ações** controladas por uma propriedade de submenu/ação (ex: `$this->m_submenu`)
- **Métodos de negócio** (CRUD, validação, etc.) devem ser chamados da classe, nunca implementados no form
- **Templates separados:** sempre usar `<nome>_cadastro.tpl` para cadastro/edição e `<nome>_mostra.tpl` para listagem

---

## Exemplo de Formulário Padrão Atualizado

```php
<?php
if (!defined('ADMpath')) exit;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/util/c_empresa.php");

class p_empresa extends c_empresa
{
    public $smarty = null;
    private $m_submenu = null;
    // Propriedades para cada campo
    private $empresa_id;
    private $nome_empresa;
    private $centro_custo;
    private $cnpj;

    function __construct()
    {
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Atribuição direta
        $this->empresa_id    = $parmPost['empresa_id'] ?? $parmGet['empresa_id'] ?? '';
        $this->nome_empresa  = $parmPost['nome_empresa'] ?? $parmGet['nome_empresa'] ?? '';
        $this->centro_custo  = $parmPost['centro_custo'] ?? $parmGet['centro_custo'] ?? '';
        $this->cnpj          = $parmPost['cnpj'] ?? $parmGet['cnpj'] ?? '';

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = $parmGet['submenu'] ?? $parmPost['submenu'] ?? '';

        // Passagem de variáveis globais para o Smarty
        $this->smarty->assign('mod', 'util');
        $this->smarty->assign('form', 'empresa');
        $this->smarty->assign('user', $_SESSION['user_array'] ?? []);
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'salvar':
                $dados = [
                    'empresa_id'    => $this->empresa_id,
                    'nome_empresa'  => $this->nome_empresa,
                    'centro_custo'  => $this->centro_custo,
                    'cnpj'          => $this->cnpj
                ];
                $resultado = $this->incluiEmpresa($dados);
                if (is_numeric($resultado)) {
                    $this->desenhaCadastroEmpresa('Empresa cadastrada com sucesso!', 'sucesso');
                } else {
                    $this->desenhaCadastroEmpresa($resultado, 'erro');
                }
                break;
            // ... outros cases (alterar, excluir, consultar) seguem o mesmo padrão
            default:
                $this->desenhaCadastroEmpresa();
        }
    }

    function desenhaCadastroEmpresa($mensagem = null, $tipoMsg = null, $dados = null)
    {
        if ($dados === null) {
            // Decide entre select geral ou filtrado
            $dados = $this->empresa_id ? $this->selecionaEmpresaPorId($this->empresa_id) : $this->selecionaTodasEmpresas();
        }
        $this->smarty->assign('dados', $dados);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        // Passa variáveis de contexto para o template
        $this->smarty->assign('mod', 'util');
        $this->smarty->assign('form', 'empresa');
        $this->smarty->display('empresa_cadastro.tpl');
    }
}

// Execução principal
$empresa = new p_empresa();
$empresa->controle();
```

---

## Pontos-Chave do Padrão Atualizado
- **Atribuição direta** de POST/GET para propriedades no construtor
- **Sem uso de set/get** (exceto telas complexas)
- **Método controle** com switch para ações principais, sempre chamando a classe para lógica de negócio
- **Método de desenho** decide entre select geral ou filtrado
- **Passagem correta das variáveis globais** para o Smarty (`mod`, `form`, `user`)
- **Sempre incluir o template global** ao final do template
- **Separação clara** entre lógica, orquestração e apresentação
- **Templates separados** para listagem e cadastro
- **Filtros e SQL otimizados** (apenas campos necessários, joins quando preciso) na classe
- **Controle de abas/tabs** via `$form` no template
- **Integração correta com o menu lateral** (ativação via variáveis de contexto)

---

## Mensagens de Sucesso/Erro (SweetAlert2)
- Sempre exiba mensagens de sucesso, erro ou atenção usando SweetAlert2 (Swal.fire) no padrão ADM v4.5.
- O script deve ser incluído usando a variável global `ADMsweetAlert2`:
  ```php
  echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
  echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Mensagem de sucesso!',confirmButtonText: 'OK'}).then(function(){window.location='?mod=util&form=empresa';});</script>";
  // Para erro/atenção:
  echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
  echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: 'Mensagem de erro ou atenção.',confirmButtonText: 'OK'});</script>";
  ```

## Regras e Observações Recentes
- **Centro de custo**: não é editável, é gerado automaticamente no backend, sempre pulando para o próximo múltiplo de 10.000.000 (ex: 10000000 → 20000000 → 30000000).
- **Casas decimais**: não pertence à tabela de empresa, deve ser salva em FAT_PARAMETRO vinculada ao centro de custo, somente após sucesso na inclusão da empresa.
- **Telefone**: sempre separar DDD (dois primeiros dígitos) e número no backend, salvando em campos distintos.
- **CEP**: sempre salvar apenas os números, sem traço ou caracteres especiais.
- **empresa_id**: não é editável nem exibido no cadastro.

## Exemplo de Controle com SweetAlert2
```php
switch ($this->m_submenu) {
    case 'inclui':
        $resultado = $this->incluiEmpresa($dados);
        if (is_numeric($resultado)) {
            echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
            echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa cadastrada com sucesso!',confirmButtonText: 'OK'}).then(function(){window.location='?mod=util&form=empresa';});</script>";
            exit;
        } else {
            echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
            echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
            exit;
        }
        break;
    // ... outros cases (alterar, excluir) seguem o mesmo padrão
}
```

## Observação
- Sempre use a variável global `ADMsweetAlert2` para o caminho do script.
- Não envie campos técnicos (centro de custo, empresa_id) no POST/GET do usuário.
- O backend deve garantir a separação correta de telefone e CEP, e o vínculo correto de casas decimais em FAT_PARAMETRO.

### Especificação sobre chaves MAIÚSCULO e alteração
- O array de dados passado para o template deve manter as chaves em MAIÚSCULO, conforme o retorno do select do banco.
- O template espera essas chaves para preencher os campos em modo de alteração.
- Para telefone, envie FONEAREA e FONENUM separados.
- Para selects, envie o valor em maiúsculo.
- Exemplo de uso no template:
```smarty
<input type="text" name="nome_empresa" value="{$dados.NOMEEMPRESA}">
<input type="text" name="telefone" value="{$dados.FONEAREA}{$dados.FONENUM}">
```

### Padrão de Mensagens SweetAlert2 (atualizado)
- Para mensagens de sucesso (cadastro/alteração), use:
  - `timer: 3000` (3 segundos)
  - `showConfirmButton: false` (sem botão OK)
  - Fecha automaticamente e redireciona após o timer
- Exemplo:
```php
echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa cadastrada/alterada com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=util&form=empresa';});</script>";
```
- Para mensagens de erro/atenção, mantenha `confirmButtonText: 'OK'`.
- Sempre siga esse padrão de fechamento automático para sucesso em todos os fluxos.

*Este agente foi atualizado para refletir as práticas mais recentes e robustas do ADM v4.5, garantindo padronização, clareza e facilidade de manutenção na criação de formulários administrativos.* 

### Boas práticas finais
- Ao finalizar a atualização ou criação de formulário, sempre pergunte ao usuário se deseja remover comandos de debug (`debugger;`, `console.log` no JS, variáveis de debug no PHP). 

### Boas práticas para funções de banco
- Ao criar funções que interagem com o banco de dados (insert, update, select), sempre solicite ao usuário o DDL (estrutura SQL) da tabela envolvida para garantir alinhamento de campos, tipos e regras de negócio. 