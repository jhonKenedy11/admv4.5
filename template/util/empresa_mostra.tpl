<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
  .right_col {
    min-height: 100vh;
    background: #f7f7f7; /* ou a cor padrão do sistema */
  }
</style>
<script type="text/javascript" src="{$pathJs}/util/s_empresa.js"> </script>
<div class="right_col" role="main">
  <div class="">
    <form NAME="lancamento" name="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION="{$SCRIPT_NAME}">
      <input name=mod type=hidden value="util">
      <input name=form type=hidden value="empresa">
      <input name=opcao type=hidden value="{$opcao}">
      <input name=submenu type=hidden value="{$subMenu}">
      <input id="empresa_id" name="empresa_id" type="hidden" value="{$empresa_id}">

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Empresas - Consulta</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li>
                  <button type="submit" class="btn btn-warning" onSubmit="javascript:submitPesquisa('');">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    <span> Pesquisar</span>
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');" style="margin-left:10px;">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Cadastrar Nova
                  </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="form-group">
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <input type="text" class="form-control" name="nome_empresa" placeholder="Filtrar por nome da empresa..." value="{$filtro_nome}">
                </div>
              </div>
              <table class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th style="display:none;">Código</th>
                    <th>Nome</th>
                    <th>Centro de Custo</th>
                    <th>CNPJ</th>
                    <th>Regime Tributário</th>
                    <th>Informação Complementar</th>
                    <th>Casas Decimais</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  {section name=i loop=$dados}
                  <tr>
                    <td style="display:none;"><input type="hidden" name="empresa" value="{$dados[i].EMPRESA|escape}"></td>
                    <td>{$dados[i].NOMEEMPRESA|escape}</td>
                    <td>{$dados[i].CENTROCUSTO|escape}</td>
                    <td>{$dados[i].CNPJ|escape}</td>
                    <td>
                      {if $dados[i].REGIMETRIBUTARIO == 1}
                        Simples
                      {elseif $dados[i].REGIMETRIBUTARIO == 2}
                        Lucro Presumido
                      {elseif $dados[i].REGIMETRIBUTARIO == 3}
                        Lucro Real
                      {else}
                        -
                      {/if}
                    </td>
                    <td>{$dados[i].MSG_INFORMACAO_COMPLEMENTAR|escape}</td>
                    <td>{$dados[i].CASASDECIMAIS|escape}</td>
                    <td>
                      <button type="button" title="Alterar" class="btn btn-primary btn-xs" onClick="javascript:submitConsulta('{$dados[i].EMPRESA}');">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      </button>
                      {*
                      <button type="button" title="Excluir" class="btn btn-danger btn-xs" onClick="javascript:submitExcluir('{$dados[i].EMPRESA}');">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                      </button>
                      *}
                    </td>
                  </tr>
                  {/section}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div> 

{include file="template/database.inc"}
