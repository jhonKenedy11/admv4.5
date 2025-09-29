<style>
#modalPesquisarItens .modal-dialog {
  max-height: 70vh;
  margin-top: 30px;
  margin-bottom: 30px;
}
#modalPesquisarItens .modal-footer {
  position: sticky;
  bottom: 0;
  background: #fff;
  z-index: 10;
  border-top: 1px solid #e5e5e5;
  box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
}
#modalPesquisarItens .modal-content {
  display: flex;
  flex-direction: column;
  max-height: 90vh;
  overflow: hidden;
}
#modalPesquisarItens .modal-body {
  overflow-y: auto;
  flex: 1 1 auto;
}
#tabelaItensPesquisaModal td, 
#tabelaItensPesquisaModal th {
    padding-top: 2px !important;
    padding-bottom: 2px !important;
    line-height: 1 !important;
    font-size: 10px;
}
</style>
<!-- Modal de Pesquisa de Itens para Inventário -->
<div class="modal fade" id="modalPesquisarItens" tabindex="-1" role="dialog" aria-labelledby="modalPesquisarItensLabel">
  <div class="modal-dialog" style="width:90%; max-width:1400px;" role="document">
    <div class="modal-content">
      <form id="formPesquisaItensInventario" name="formPesquisaItensInventario" method="POST" onsubmit="return false;">
        <div class="modal-header" style="display: flex; align-items: center; padding: 10px 15px;">
          <h4 class="modal-title" id="modalPesquisarItensLabel" style="margin: 0;">Pesquisar Itens para Inventário</h4>
        </div>
        <div class="modal-body">
          <div class="row" style="align-items: flex-end;">
            <div class="col-md-1 col-sm-2 col-xs-3">
              <label for="pesqCodigo">Código</label>
              <input type="text" class="form-control input-sm" id="pesqCodigo" name="pesqCodigo" placeholder="Código">
            </div>
            <div class="col-md-3 col-sm-5 col-xs-6">
              <label for="pesqNome">Nome</label>
              <input type="text" class="form-control input-sm" id="pesqNome" name="pesqNome" placeholder="Nome do Produto">
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6">
              <label for="pesqGrupo">Grupo</label>
              <select single="single" class="form-control input-sm" id="pesqGrupo" name="pesqGrupo[]">
                {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
              </select>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6">
              <label for="pesqLocalizacao">Localização</label>
              <input type="text" class="form-control input-sm" id="pesqLocalizacao" name="pesqLocalizacao" placeholder="Localização">
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6">
              <label for="pesqForaLinha">Fora de Linha</label>
              <select class="form-control input-sm" id="pesqForaLinha" name="pesqForaLinha">
                <option value="">Não</option>
                <option value="1">Sim</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12 text-right">
              <button type="submit" class="btn btn-warning btn-sm" id="btnPesquisarItensModal" style="margin-top: 22px; width: 100%;">
                <span class="glyphicon glyphicon-search"></span> Pesquisar
              </button>
            </div>
          </div>
          <div class="row" style="margin-top:15px;">
            <div class="col-md-12">
              <div style="max-height: 400px; overflow-y: auto;">
              <table class="table table-bordered table-hover" id="tabelaItensPesquisaModal">
              <thead>
                <tr>
                  <th style="width:40px;"><input type="checkbox" id="checkAllModal"></th>
                  <th>Produto</th>
                  <th>Grupo</th>
                  <th>Código</th>
                  <th>Localização</th>
                </tr>
                  </thead>
                  <tbody id="tbodyItensPesquisaModal">
                    <!-- Resultados da pesquisa serão inseridos aqui via JS -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:limparCamposPesquisaItensInventarioModal()">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnAdicionarItensSelecionados" onclick="javascript:adicionarItensSelecionadosInventario()">
            <span class="glyphicon glyphicon-plus"></span> Adicionar Selecionados
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).on('change', '#checkAllModal', function() {
    var checked = $(this).is(':checked');
    $('#tbodyItensPesquisaModal input[type="checkbox"][name="itensSelecionados[]"]').prop('checked', checked);
});

$('#formPesquisaItensInventario').on('submit', function(e) {
    e.preventDefault();
    pesquisarItensInventarioModal();
});
</script>