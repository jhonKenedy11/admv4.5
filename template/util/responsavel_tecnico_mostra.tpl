<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
  
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_responsavel_tecnico.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <form class="full" NAME="lancamento" id="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
    ACTION={$SCRIPT_NAME}>
    <input name=mod type=hidden value="util">
    <input name=form type=hidden value="responsavel_tecnico">
    <input name=id type=hidden value="">
    <input name=opcao type=hidden value={$opcao}>
    <input name=filtro type=hidden value={$filtro}>
    <input name=submenu type=hidden value={$submenu}>

    <div class="">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Responsáveis Técnicos - Consulta</h2>
              
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:abrirModal();">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                    </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            
            <div class="x_content">

              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                      <tr class="headings">
                        <th align=center class=ColunaTitulo>Nome</th>
                        <th align=center class=ColunaTitulo>CPF</th>
                        <th align=center class=ColunaTitulo>CREA</th>
                        <th align=center class=ColunaTitulo>Telefone</th>
                        <th align=center class=ColunaTitulo>E-mail</th>
                        <th align=center class=ColunaTitulo>Situação</th>
                        <th align=center class=ColunaTitulo>Ações</th>
                      </tr>
                    </thead>
                    <tbody>
                      {section name=i loop=$lanc}
                        <tr>
                          <td>{$lanc[i].NOME}</td>
                          <td>{$lanc[i].CPF}</td>
                          <td>{$lanc[i].CREA}</td>
                          <td>{$lanc[i].TELEFONE}</td>
                          <td>{$lanc[i].EMAIL}</td>
                          <td>
                            {if $lanc[i].SITUACAO == 'A'}
                              <span>Ativo</span>
                            {else}
                              <span>Inativo</span>
                            {/if}
                          </td>
                          <td>
                            <button type="button" title="Alterar" class="btn btn-primary btn-xs"
                              onclick="javascript:abrirModal('{$lanc[i].ID}');">
                              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </button>
                            <button type="button" title="Excluir" class="btn btn-danger btn-xs"
                              onclick="javascript:excluirResponsavel('{$lanc[i].ID}');">
                              <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </button>
                          </td>
                        </tr>
                      {/section}
                     </tbody>
                 </table>
               </div>
             </div>
           </div>
         </div>
       </div>
     </form>
   </div>
   {include file="template/database.inc"}


  <!-- Modal de Cadastro/Edição -->
  <div class="modal fade" id="modalResponsavel" tabindex="-1" role="dialog" aria-labelledby="modalResponsavelLabel">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modalResponsavelLabel" style="display: inline-block; margin: 0; width: 440px;">Novo Responsável Técnico</h4>
          <button type="button" class="btn btn-primary btn-sm" onclick="javascript:salvarResponsavel();" style="margin-left: 10px;">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Salvar
          </button>
          <button type="button" class="close btn-sm" data-dismiss="modal" aria-label="Close" style="margin-left: 10px;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formResponsavel" name="formResponsavel" method="POST" class="form-horizontal form-label-left" novalidate>
            <input type="hidden" id="id" name="id" value="">
            <input type="hidden" id="mod" name="mod" value="util">
            <input type="hidden" id="form" name="form" value="responsavel_tecnico">
            <input type="hidden" id="submenu" name="submenu" value="">
            
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Nome *</label>
                  <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">CPF</label>
                  <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">CREA</label>
                  <input type="text" class="form-control" id="crea" name="crea">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Telefone</label>
                  <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">E-mail</label>
                  <input type="email" class="form-control" id="email" name="email">
                </div>
              </div>              
         
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Situação</label>
                  <select class="form-control" id="situacao" name="situacao">
                    <option value="A">Ativo</option>
                    <option value="I">Inativo</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">CEP</label>
                  <input type="text" class="form-control" id="cep" name="cep" 
                         placeholder="00000-000" 
                         data-inputmask="'mask' : '99999-999'"
                         onblur="pesquisacep(this.value);">
                </div>
              </div>
              <div class="col-md-9">
                <div class="form-group">
                  <label class="control-label">Rua</label>
                  <input type="text" class="form-control" id="rua" name="rua">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Número</label>
                  <input type="text" class="form-control" id="numero" name="numero">
                </div>
              </div>
              <div class="col-md-9">
                <div class="form-group">
                  <label class="control-label">Complemento</label>
                  <input type="text" class="form-control" id="complemento" name="complemento">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Cidade</label>
                  <input type="text" class="form-control" id="cidade" name="cidade">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Estado</label>
                  <input type="text" class="form-control" id="estado" name="estado" readonly>
                </div>
              </div>
            </div>
            
            
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    // Disponibilizar dados do Smarty para JavaScript
    window.lanc = {$lanc|@json_encode};
  </script>
  
</div>

<!-- InputMask -->
<script src="{$bootstrap}/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>
<script>
$(document).ready(function() {
    $(":input").inputmask();
});
</script>

