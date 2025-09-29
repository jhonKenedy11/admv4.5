# Agente de Criação de Template ADM v4.5 (Padrão Atualizado)

## Padrão para Modal de Anexo de Logo/Foto (Novo)

- Modal com input file para upload de logo/foto (PNG).
- Preview dinâmico da imagem após upload (`<div id="logoExistente">`).
- Botão de anexar chama função JS.
- Integração com JS para abrir modal, salvar logo, atualizar preview.

### Exemplo de Modal
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

### Perguntas orientadoras para o usuário
- Qual o nome do módulo/entidade?
- O anexo será único ou múltiplo?
- Deseja preview dinâmico?
- Precisa de botão para excluir/abrir?
- O backend já possui método de upload?
- Integração com JS?

### Exemplo de prompt para ativação
> "Gere o trecho de template para modal de anexo de logo, com preview dinâmico, input file e botões de anexar/abrir/excluir."

---

## Padrão de Criação de Templates ADM v4.5

### Estrutura Geral
- Use a estrutura padrão de layout:
  ```html
  <div class="right_col" role="main">
    <div class="">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            ...
          </div>
        </div>
      </div>
    </div>
  </div>
  ```
- Botões de ação SEMPRE no topo, lado a lado, dentro de `<ul class="nav navbar-right panel_toolbox">`:
  - **Cadastro:** Botão "Salvar" (ícone disquete, `glyphicon-floppy-disk` ou `glyphicon-floppy-save`, classe `btn-success` ou `btn-primary`), SEMPRE type="submit".
  - **Alteração:** Mesmo botão "Salvar".
  - **Voltar:** Botão com seta para a esquerda (`glyphicon-arrow-left` ou `glyphicon-backward`), classe `btn-default`, sempre leva de volta à tela de listagem.
- O botão "Cadastrar Nova" só aparece na tela de listagem/mostra, não no cadastro.
- Campo de filtro acima da tabela (quando aplicável).
- Tabela apenas com os campos necessários, nomes em maiúsculo conforme SQL.
- Campos técnicos (ex: código/id) como hidden se não forem exibidos.
- Conversão de valores técnicos para texto amigável (ex: regime tributário).

### Cadastro x Alteração
- O template deve exibir "Cadastro" quando não houver id/empresa_id (ou campo equivalente).
- Se houver id/empresa_id, exibir "Alteração" e preencher os campos com os dados existentes.
- O controle pode ser feito via `{if $empresa_id}` ou `{if $dados.id}`.

### Inputs e Layout
- Inputs devem ser centralizados ou lado a lado, conforme o número e tamanho dos campos.
- Use sempre `form-group` e grid Bootstrap (`col-md-6`, `col-md-4`, etc) para alinhar campos.
- Evite `<br>`, prefira grid para espaçamento.
- Labels sempre alinhados à esquerda, campos à direita.

### variavel os campos mas exemplo para o cadastro da empresa, pergunte ao dev quais campos devem constar.
### Campos obrigatórios para cadastro de empresa
- Nome da empresa
- Nome fantasia
- CNPJ (campo numérico, máscara de CNPJ, validação obrigatória)
- Inscrição estadual
- Endereço completo: rua, número, complemento, CEP (com via CEP), cidade, estado, bairro, código município
- Contato: e-mail, telefone
- Regime tributário (select: 1=Simples, 2=Lucro Presumido, 3=Lucro Real)
- Casas decimais (parâmetro de faturamento)
- Mensagem informação complementar (textarea)
- Campo técnico: id/empresa_id (hidden)
- Centro de custo: não editável, preenchido automaticamente conforme regra de negócio (ex: empresa 1 → centro de custo 10.000.000)
- Modal para anexo de logo/foto da empresa (ver exemplo abaixo)

### Regras Específicas
- Centro de custo não é preenchido pelo usuário, é sequencial conforme o banco.
- CNPJ deve ser campo numérico com máscara/validação.
- Parâmetro de faturamento é apenas o campo casas decimais.
- Modal de anexo de logo/foto igual ao padrão visto no CRM/Obras.
- Layout responsivo, campos agrupados lado a lado conforme espaço.
- Botões: Salvar (disquete), Voltar (arrowback), sem "Cadastrar Nova" no cadastro.

### Exemplo de Modal de Anexo de Logo/Foto
```html
<!-- Botão para abrir modal -->
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ModalAnexoLogo">
  <span class="glyphicon glyphicon-picture"></span> Anexar Logo/Foto
</button>

<!-- Modal -->
<div class="modal fade" id="ModalAnexoLogo" tabindex="-1" role="dialog" aria-labelledby="ModalAnexoLogoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalAnexoLogoLabel">Anexar Logo/Foto da Empresa</h4>
      </div>
      <div class="modal-body">
        <form id="formLogoEmpresa" enctype="multipart/form-data">
          <input type="file" class="form-control-file" id="logoEmpresa" name="logoEmpresa" accept="image/*">
          <small class="text-muted">Apenas arquivos de imagem (JPEG, PNG) são permitidos.</small>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" onclick="salvarLogoEmpresa()">Anexar</button>
      </div>
    </div>
  </div>
</div>
```

### Máscaras de campos obrigatórios
- Sempre aplique as máscaras de CNPJ, telefone e CEP via script diretamente no template, logo após importar o inputmask, e **não** no JS do módulo.
- Exemplo de script a ser incluído no template:
```html
<script type="text/javascript" src="{$bootstrap}/input_mask/jquery.inputmask.js"></script>
<script>
  $(function() {
    $('#cnpj').inputmask('99.999.999/9999-99');
    $('#cep').inputmask('99999-999');
    $('#telefone').inputmask({
      mask: ['(99) 9999-9999', '(99) 99999-9999'],
      keepStatic: true
    });
  });
</script>
```
- Isso garante que as máscaras sejam aplicadas corretamente mesmo em recarregamentos parciais ou uso de JS modular.

### Ativação do Menu Lateral
- O template **deve sempre incluir** o arquivo de include global ao final:
  ```smarty
  {include file="template/database.inc"}
  ```
- Esse include é responsável por carregar o menu lateral, scripts e recursos globais.
- As variáveis `mod` e `form` devem ser passadas pelo PHP para o Smarty, garantindo que o menu lateral fique ativo e expandido corretamente.

### Observações
- Inclua sempre os includes de JS e CSS globais via variáveis Smarty.
- O template deve ocupar toda a tela, mesmo sem dados.
- Para abas, use nav-tabs Bootstrap e `{if $form == '...'}` para marcar ativo.
- O padrão é válido para todos os tipos de entidade/módulo.

### Exemplos de Botões
```html
<li>
  <button type="submit" class="btn btn-success">
    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
    <span> Salvar</span>
  </button>
</li>
<li>
  <button type="button" class="btn btn-default" onclick="window.location='?mod=util&form=empresa';">
    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Voltar
  </button>
</li>
```

### Mensagens de Sucesso/Erro (SweetAlert2)
- Sempre exiba mensagens de sucesso, erro ou atenção usando SweetAlert2 (Swal.fire) no padrão ADM v4.5.
- O script deve ser incluído usando a variável global `ADMsweetAlert2`:
  ```php
  echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
  echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Mensagem de sucesso!',confirmButtonText: 'OK'}).then(function(){window.location='?mod=util&form=empresa';});</script>";
  // Para erro/atenção:
  echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
  echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: 'Mensagem de erro ou atenção.',confirmButtonText: 'OK'});</script>";
  ```
- No template, inclua o script com:
  ```html
  <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
  ```

### Observação sobre uso com AJAX
- **Se o retorno for via AJAX**, a exibição do SweetAlert2 deve ser feita pelo JavaScript do frontend, e não pelo echo do PHP.
- Exemplo em JS:
  ```js
  // Sucesso
  Swal.fire({
    icon: 'success',
    title: 'Sucesso',
    width: 510,
    text: 'Empresa cadastrada com sucesso!',
    confirmButtonText: 'OK'
  }).then(function(){ window.location = '?mod=util&form=empresa'; });
  // Erro/Atenção
  Swal.fire({
    icon: 'warning',
    title: 'Atenção',
    width: 510,
    text: msgRetorno,
    confirmButtonText: 'OK'
  });
  ```

### Regras e Observações Recentes
- **Centro de custo**: não é editável, é gerado automaticamente no backend, sempre pulando para o próximo múltiplo de 10.000.000 (ex: 10000000 → 20000000 → 30000000).
- **Casas decimais**: não pertence à tabela de empresa, deve ser salva em FAT_PARAMETRO vinculada ao centro de custo.
- **Telefone**: sempre separar DDD (dois primeiros dígitos) e número no backend, salvando em campos distintos.
- **CEP**: sempre salvar apenas os números, sem traço ou caracteres especiais.
- **empresa_id**: não é editável nem exibido no cadastro.

### Exemplo de Botões e Mensagens SweetAlert2
```php
// Sucesso
echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa cadastrada com sucesso!',confirmButtonText: 'OK'}).then(function(){window.location='?mod=util&form=empresa';});</script>";
// Atenção/Erro
echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: 'Mensagem de erro ou atenção.',confirmButtonText: 'OK'});</script>";
``` 

### Especificação sobre uso de chaves MAIÚSCULO e fluxo de alteração
- O template deve usar as chaves em MAIÚSCULO, conforme o array retornado pelo select do banco, tanto para novo cadastro quanto para alteração.
- No fluxo de alteração, os campos devem ser preenchidos diretamente com essas chaves.
- Para telefone, use `{$dados.FONEAREA}{$dados.FONENUM}` **(apenas no cadastro de empresa; nos demais módulos normalmente o telefone é um campo único)**.
- Para selects, compare com o valor em maiúsculo (ex: `{$dados.REGIMETRIBUTARIO}`).
- **Observação:** A concatenação de campos (ex: FONEAREA + FONENUM) é uma particularidade do cadastro de empresa. Nos demais módulos, normalmente o telefone é um campo único. Sempre observe a estrutura do banco e a regra do módulo ao preencher campos compostos.
- Exemplo de bloco de input para alteração:
```smarty
<input type="text" name="nome_empresa" value="{$dados.NOMEEMPRESA}">
<input type="text" name="telefone" value="{$dados.FONEAREA}{$dados.FONENUM}">
<select name="regime_tributario">
  <option value="1" {if $dados.REGIMETRIBUTARIO == 1}selected{/if}>Simples</option>
  ...
</select>
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

### Boas práticas finais
- Ao finalizar a atualização ou criação de tela, sempre pergunte ao usuário se deseja remover comandos de debug (`debugger;`, `console.log` no JS, variáveis de debug no PHP). 

### Padrão de construção de URL para AJAX
- Para requisições AJAX (upload/anexo), construa a URL no padrão do sistema:
  ```js
  let url = document.URL + '?mod=util&form=empresa&submenu=anexar_logo&opcao=blank';
  $.ajax({ url: url, ... });
  ```
- Sempre use esse padrão para garantir roteamento correto e compatibilidade com o backend. 

## Padrão para Templates de Relatórios (Impressão)

### Estrutura Recomendada para Relatórios
Para garantir que o cabeçalho (logo, título, período, data/hora) e a tabela de dados fiquem sempre juntos na impressão, evitando quebras de página indesejadas (como uma página em branco antes da tabela), siga este padrão:

1. **Envolva o cabeçalho e a tabela em um único `<div>`** com a classe `.print-block`:

```html
<div class="print-block">
  <div class="header-section">
    <!-- Cabeçalho: logo, título, período, data/hora -->
  </div>
  <div class="x_panel">
    <!-- Tabela de dados -->
  </div>
</div>
```

2. **No CSS de impressão**, adicione:

```css
@media print {
  .print-block {
    page-break-inside: avoid !important;
    display: block !important;
  }
  .header-section, .x_panel {
    page-break-inside: avoid !important;
  }
  /* Remova display: flex de containers principais na impressão */
  .height100, .print-container {
    display: block !important;
  }
}
```

3. **Evite usar flex/grid** no container principal durante a impressão.

#### Observações:
- O Chrome e outros navegadores respeitam melhor `page-break-inside: avoid` quando aplicada a um container simples, sem flex/grid.
- Esse padrão deve ser seguido em todos os relatórios ADM v4.5 para garantir impressão limpa e sem páginas em branco entre cabeçalho e tabela.

--- 