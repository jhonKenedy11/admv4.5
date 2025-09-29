<div class="modal fade" id="modalAlteraQuantEstoque" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Alterar Quantidade Produto </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAtualizarQuantidade('{$id}');">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">              
              
              <div class="col-md-7">
                  <label for="descricao">Produto</label>
                  <input class="form-control" type="text" id="mProduto" name="mProduto">
              </div>
              <div class="col-md-3">
                  <label for="quantidade">Quantidade Anterior</label>
                  <input class="form-control money" type="text" id="mQuantidade" name="mQuantidade">
              </div>
              <div class="col-md-2">
                  <label for="quantidade">Nova Quantidade</label>
                  <input class="form-control money" type="text" id="qtdeEntrada" name="qtdeEntrada" >
              </div>
             
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 
