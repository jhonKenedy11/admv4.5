<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
  .right_col {
    min-height: 100vh;
    background: #f7f7f7;
  }
</style>
<script type="text/javascript" src="{$pathJs}/util/s_empresa.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$bootstrap}/input_mask/jquery.inputmask.js"></script>

<div class="right_col" role="main">
  <div class="">
    <form id="lancamento" name="lancamento" method="POST" class="form-horizontal form-label-left" novalidate action="{$SCRIPT_NAME}">
      <input name="mod" type="hidden" value="util">
      <input name="form" type="hidden" value="empresa">
      <input name="opcao" type="hidden" value="{$opcao}">
      <input name="submenu" type="hidden" value="{if $modo_edicao}alterar{else}salvar{/if}">
      <input id="empresa_id" name="empresa_id" type="hidden" value="{$dados.EMPRESA}">


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Empresa - {if !$dados.amb_empresa}Cadastro{else}Alteração{/if}</h2>
              <ul class="nav navbar-right panel_toolbox">
              <li>
                <button type="button" class="btn btn-danger" onclick="javascript:submitVoltar();" style="margin-left:10px;">
                  <span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Voltar
                </button>
              </li>
                <li>
                  <button type="button" class="btn btn-primary" onclick="javascript:submitSalvar()">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                    <span> Salvar</span>
                  </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              {if isset($mensagem) && $mensagem ne ''}
                <div class="alert {if $tipoMsg == 'sucesso'}alert-success{else}alert-danger{/if} alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {$mensagem}
                </div>
              {/if}
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="nome_empresa">Nome da Empresa:</label>
                  <input type="text" name="nome_empresa" id="nome_empresa" class="form-control" value="{$dados.NOMEEMPRESA}" required placeholder="Razão Social da empresa">
                </div>
                <div class="form-group col-md-6">
                  <label for="nome_fantasia">Nome Fantasia:</label>
                  <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" value="{$dados.NOMEFANTASIA}" placeholder="Nome fantasia">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-2">
                  <label for="cnpj">CNPJ:</label>
                  <input type="text" name="cnpj" id="cnpj" class="form-control" value="{$dados.CNPJ}" required maxlength="14" placeholder="00.000.000/0000-00">
                </div>
                <div class="form-group col-md-2">
                  <label for="inscricao_estadual">Inscrição Estadual:</label>
                  <input type="text" name="inscricao_estadual" id="inscricao_estadual" class="form-control" value="{$dados.INSCESTADUAL}" required maxlength="9" placeholder="Inscrição Estadual">
                </div>
                <div class="form-group col-md-4">
                  <label for="email">E-mail:</label>
                  <input type="email" name="email" id="email" class="form-control" value="{$dados.EMAIL}" placeholder="email@empresa.com.br">
                </div>
                <div class="form-group col-md-2">
                  <label for="telefone">Telefone:</label>
                  <input type="text" name="telefone" id="telefone" class="form-control" value="{$dados.FONEAREA}{$dados.FONENUM}" maxlength="11" data-mask="(00) 00000-0000" placeholder="(00) 00000-0000">
                </div>
                <div class="form-group col-md-2">
                </div>
              </div>
              <!-- Endereço: CEP primeiro, depois os demais campos -->
              <div class="row">
                <div class="form-group col-md-2">
                  <label for="cep">CEP:</label>
                  <input type="text" name="cep" id="cep" class="form-control" value="{$dados.CEP}" maxlength="9" data-mask="00000-000" placeholder="00000-000" onblur="pesquisarEnderecoEmpresa(this.value)">
                </div>
                <div class="form-group col-md-6">
                  <label for="rua">Rua:</label>
                  <input type="text" name="rua" id="rua" class="form-control" value="{$dados.ENDERECO}" placeholder="Rua/Avenida">
                </div>
                <div class="form-group col-md-1">
                  <label for="numero">Número:</label>
                  <input type="text" name="numero" id="numero" class="form-control" value="{$dados.NUMERO}" placeholder="123">
                </div>
                <div class="form-group col-md-3">
                  <label for="complemento">Complemento:</label>
                  <input type="text" name="complemento" id="complemento" class="form-control" value="{$dados.COMPLEMENTO}" placeholder="Complemento">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-3">
                  <label for="bairro">Bairro:</label>
                  <input type="text" name="bairro" id="bairro" class="form-control" value="{$dados.BAIRRO}" placeholder="Bairro">
                </div>
                <div class="form-group col-md-3">
                  <label for="codigo_municipio">Cód. Município:</label>
                  <input type="text" name="codigo_municipio" id="codigo_municipio" class="form-control" value="{$dados.CODMUNICIPIO}" placeholder="Código IBGE">
                </div>
                <div class="form-group col-md-4">
                  <label for="cidade">Cidade:</label>
                  <input type="text" name="cidade" id="cidade" class="form-control" value="{$dados.CIDADE}" placeholder="Cidade">
                </div>
                <div class="form-group col-md-2">
                  <label for="estado">Estado:</label>
                  <input type="text" name="estado" id="estado" class="form-control" value="{$dados.UF}" placeholder="UF">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="regime_tributario">Regime Tributário:</label>
                  <select name="regime_tributario" id="regime_tributario" class="form-control" {if $dados.EMPRESA}disabled{/if}>
                    <option value="1" {if $dados.REGIMETRIBUTARIO == 1}selected{/if}>Simples</option>
                    <option value="2" {if $dados.REGIMETRIBUTARIO == 2}selected{/if}>Lucro Presumido</option>
                    <option value="3" {if $dados.REGIMETRIBUTARIO == 3}selected{/if}>Lucro Real</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="casas_decimais">Casas Decimais:</label>
                  <select name="casas_decimais" id="casas_decimais" class="form-control" {if $dados.EMPRESA}disabled{/if}>
                    <option value="2" {if $dados.CASASDECIMAIS == 2}selected{/if}>2</option>
                    <option value="4" {if $dados.CASASDECIMAIS == 4}selected{/if}>4</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="msg_informacao_complementar">Informação Complementar:</label>
                  <textarea name="msg_informacao_complementar" id="msg_informacao_complementar" class="form-control" rows="2" placeholder="Observações, recados, etc." {if $dados.EMPRESA}readonly{/if}>{$dados.MSG_INFORMACAO_COMPLEMENTAR}</textarea>
                </div>
              </div>
              <!-- Botão/modal de anexo de logo/foto ao final -->
              <div class="row">
                <div class="col-md-12 text-left">
                  <button type="button" class="btn btn-info" onclick="abrirModalLogo({$dados.EMPRESA})" style="margin-top: 10px;">
                    <span class="glyphicon glyphicon-picture"></span> Anexar Logo/Foto
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
{include file="template/database.inc"} 

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

<!-- Modal de Anexo de Logo/Foto -->
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