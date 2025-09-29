# Agente de Criação de JS ADM v4.5 (Padrão Atualizado)

## Padrão para Upload/Preview de Logo/Foto (Novo)

- Funções separadas para abrir modal, carregar logo, salvar logo, excluir logo, gerar preview.
- Preview dinâmico após upload/exclusão.
- Integração AJAX com backend.
- Feedbacks com SweetAlert2.

### Exemplos de funções
```js
function abrirModalLogo(empresa_id) {
    $('#ModalAnexoLogo').modal('show');
    $('#id_empresa_logo').val(empresa_id);
    carregarLogoEmpresa(empresa_id);
}
function carregarLogoEmpresa(empresa_id) {
    $('#logoExistente').html('<div class="col-12 text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i><p class="mt-2">Carregando logo...</p></div>');
    $.ajax({
        url: 'index.php?form=empresa&mod=util&submenu=carregarLogoEmpresa&opcao=blank&id_empresa=' + empresa_id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.forEach(logo => {
                    if (logo.id && logo.id_empresa && logo.extensao && logo.caminho_completo) {
                        html += `<div class="col-12 text-center" style="position: relative;">${gerarVisualizacaoLogo(logo)}<div class="btnManutencao mt-2"><button type="button" class="btn btn-danger btn-xs" onClick="excluirLogoEmpresa(${logo.id})"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span>Apagar</span></button><button type="button" class="btn btn-primary btn-xs" onclick="openLogo('${logo.caminho_completo}')"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span><span>Abrir</span></button></div></div>`;
                    }
                });
                $('#logoExistente').html(html);
            } else {
                $('#logoExistente').html('<div class="col-12 text-center py-4"><p>Nenhuma logo anexada</p></div>');
            }
        },
        error: function() {
            $('#logoExistente').html('<div class="col-12 text-center py-4"><i class="fas fa-exclamation-triangle text-danger"></i><p class="text-danger">Erro ao carregar logo.</p></div>');
        }
    });
}
function gerarVisualizacaoLogo(logo) {
    const extensao = logo.extensao.toUpperCase();
    if (extensao === 'PNG') {
        return `<img src="${logo.caminho_completo}" class="img-rounded img-responsive tagImg" style="max-height: 150px; width: auto; margin: 0 auto;"/>`;
    } else {
        return `<span>Arquivo não suportado</span>`;
    }
}
function openLogo(url) {
    window.open(url, '_blank');
}
function salvarLogoEmpresa() {
    const empresaId = $('#id_empresa_logo').val();
    const fileInput = $('#logoEmpresa')[0];
    const file = fileInput.files[0];
    if (!file) {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Selecione um arquivo PNG antes de enviar.' });
        return;
    }
    const fileName = file.name;
    const fileExt = fileName.split('.').pop().toLowerCase();
    if (fileExt !== 'png') {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'Tipo de arquivo inválido. Apenas PNG é permitido.' });
        return;
    }
    const maxSize = 2000000;
    if (file.size > maxSize) {
        Swal.fire({ icon: 'error', title: 'Erro!', text: 'O arquivo é muito grande. Tamanho máximo permitido: 2MB.' });
        return;
    }
    const formData = new FormData();
    formData.append('file', file);
    formData.append('id_empresa', empresaId);
    $.ajax({
        url: 'index.php?form=empresa&mod=util&submenu=salvarLogoEmpresa&opcao=blank',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $('#btnSalvarLogo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
        },
        success: function(response) {
            $('#logoEmpresa').val('');
            let data;
            try { data = typeof response === 'string' ? JSON.parse(response) : response; } catch (e) { data = {}; }
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message || 'Logo anexada com sucesso.', showConfirmButton: true, timer: 4000 }).then(() => { carregarLogoEmpresa(empresaId); });
            } else {
                Swal.fire({ icon: 'error', title: 'Erro!', text: data.message || 'Falha ao anexar logo.' });
            }
        },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Erro!', text: xhr.responseText || 'Falha ao anexar logo.' });
        },
        complete: function() {
            $('#btnSalvarLogo').prop('disabled', false).html('Anexar');
        }
    });
}
function excluirLogoEmpresa(id_logo) {
    Swal.fire({ title: 'Tem certeza?', text: 'Você não poderá reverter isso!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Sim, excluir!', cancelButtonText: 'Cancelar' }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({ url: 'index.php?form=empresa&mod=util&submenu=excluirLogoEmpresa&id_logo=' + id_logo, type: 'GET', success: function(response) { Swal.fire('Excluído!', 'A logo foi excluída.', 'success'); carregarLogoEmpresa($('#id_empresa_logo').val()); }, error: function(error) { Swal.fire('Erro!', 'Erro ao excluir a logo.', 'error'); } });
        }
    });
}
```

### Perguntas orientadoras para o usuário
- Qual o nome do módulo/entidade?
- O anexo será único ou múltiplo?
- Deseja preview dinâmico?
- Precisa de botão para excluir/abrir?
- O backend já possui método de upload?
- Integração com SweetAlert2?

### Exemplo de prompt para ativação
> "Quero funções JS para upload de logo da empresa, preview dinâmico, exclusão e feedbacks, igual ao padrão de anexos de obras."

---

## Objetivo
Padronizar a criação e integração de scripts JavaScript nos módulos do ADM v4.5, com foco em:
- Uso de jQuery (padrão do projeto)
- Integração AJAX (com e sem retorno de HTML)
- Manipulação de formulários (submit tradicional e via AJAX)
- Boas práticas de organização e nomenclatura

---

## 1. Estrutura Recomendada

- Scripts devem ser criados em `/js/[modulo]/[entidade].js`
- Sempre usar jQuery para manipulação DOM e AJAX
- Funções devem ser nomeadas com prefixo do módulo/entidade para evitar conflitos
- Separar funções utilitárias de funções de evento

---

## 2. Exemplos Reais do Projeto

### 2.1. Acesso e Manipulação de Formulários

- **Acesso ao formulário principal:**
  ```js
  f = document.lancamento;
  f.campo.value = 'valor';
  ```
- **Acesso ao formulário da janela pai (em popup/modal):**
  ```js
  f = window.opener.document.lancamento;
  f.submenu.value = 'cancel';
  window.close();
  ```

---

### 2.2. Abrir Relatórios/Impressão em Nova Aba (blank)

- **Abrir relatório ou impressão em nova aba:**
  ```js
  window.open('index.php?mod=est&form=relatorio&opcao=imprimir&letra=' + f.letra.value, '_blank');
  ```
- **Abrir com nome de janela (para evitar múltiplas abas):**
  ```js
  window.open('index.php?mod=est&form=relatorio&opcao=imprimir&letra=' + f.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
  ```

---

### 2.3. Impressão de Relatórios (Função Completa)

```js
function consultaPrint(form) {
    var f = document.lancamento;
    montaLetra(); // monta string de filtros
    f.mod.value = 'est';
    f.form.value = form;
    f.submenu.value = 'imprime';
    window.open('index.php?mod=est&form=' + form + '&opcao=imprimir&letra=' + f.letra.value, 'consulta', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
}
```

---

### 2.4. Captura de Dados do Formulário

- **Com jQuery:**
  ```js
  var dados = $('#form-filtros').serialize();
  ```
- **Com DOM puro:**
  ```js
  var valor = document.lancamento.campo.value;
  ```
- **Captura de dados em tabelas dinâmicas:**
  ```js
  var table = document.getElementById("datatable-cc");
  for (var i = 1; i < table.rows.length; i++){
      var row = table.rows.item(i).getElementsByTagName("td");
      var valor = row.item(2).getElementsByTagName("input").item(0).value;
      // ...
  }
  ```

---

### 2.5. Montagem de Parâmetros para Relatórios

```js
function montaLetra() {
    var f = document.lancamento;
    f.letra.value = f.dataIni.value + "|" + f.dataFim.value + "|" + f.pessoa.value + "|";
    // ... outros parâmetros
}
```

---

## 3. Exemplo de Funções AJAX

### 3.1. AJAX com Retorno de HTML

```js
function empresaBuscarLista() {
    var filtros = $('#form-filtros').serialize();
    $.ajax({
        url: 'p_empresa.php',
        type: 'GET',
        data: filtros + '&ajax=1',
        success: function(html) {
            $('#div-lista-empresas').html(html);
        },
        error: function() {
            alert('Erro ao buscar empresas.');
        }
    });
}
```

**No PHP (`p_empresa.php`):**
```php
if (isset($_GET['ajax'])) {
    // ... busca dados ...
    $smarty->display('util/empresa_lista_ajax.tpl');
    exit;
}
```

---

### 3.2. AJAX com Retorno de JSON (sem HTML)

```js
function empresaSalvarDados() {
    var dados = $('#form-empresa').serialize();
    $.ajax({
        url: 'p_empresa.php',
        type: 'POST',
        data: dados + '&acao=salvar&ajax=1',
        dataType: 'json',
        success: function(resp) {
            if (resp.sucesso) {
                alert('Empresa salva com sucesso!');
                window.location.reload();
            } else {
                alert('Erro: ' + resp.mensagem);
            }
        },
        error: function() {
            alert('Erro ao salvar empresa.');
        }
    });
}
```

**No PHP:**
```php
if ($_POST['acao'] == 'salvar' && isset($_POST['ajax'])) {
    // ... processa dados ...
    echo json_encode(['sucesso' => true]);
    exit;
}
```

---

## 4. Exemplo de Submit Tradicional e via AJAX

### 4.1. Submit Tradicional

```html
<form id="form-empresa" method="post" action="p_empresa.php">
    <!-- campos -->
    <button type="submit">Salvar</button>
</form>
```
*(O submit é tratado normalmente pelo PHP, sem JS)*

---

### 4.2. Submit via AJAX (Interceptando o Submit)

```js
$(function() {
    $('#form-empresa').on('submit', function(e) {
        e.preventDefault();
        empresaSalvarDados();
    });
});
```

---

## 5. Recomendações Gerais

- Sempre validar dados no front e no back-end.
- Exibir mensagens de erro/sucesso claras ao usuário.
- Usar `data-*` attributes para passar parâmetros do HTML para o JS quando necessário.
- Modularizar funções JS por entidade/módulo.
- Evitar duplicidade de código JS entre telas.

---

## 6. Padrão de Organização de Arquivo JS

```js
// js/util/empresa.js

// Funções de inicialização
document.addEventListener('DOMContentLoaded', function() {
    // binds de eventos
});

// Funções de evento
function empresaBuscarLista() { /* ... */ }
function empresaSalvarDados() { /* ... */ }

// Funções utilitárias
function empresaMostrarMensagem(msg, tipo) { /* ... */ }
```

---

## Resumo para o Agente

> Sempre crie scripts JS por módulo/entidade, use jQuery para AJAX e manipulação de DOM, padronize nomes de funções, e sempre documente exemplos de uso de submit tradicional e via AJAX, com e sem retorno de HTML. Também inclua exemplos reais de manipulação de formulários, abertura de relatórios em blank, e montagem de parâmetros para relatórios. 