<div class="modal fade" id="modalAlteraPeca" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Alterar Produto </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAlteraPeca();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              
              <div class="col-md-1">
                  <label for="codigo">ID</label>
                  <input Readonly class="form-control" type="text" id="mIdPeca" name="mIdPeca">
              </div>
              <div class="col-md-3">
                  <label for="codigo">Codigo</label>
                  <input class="form-control" type="text" id="mCodPeca" name="mCodPeca">
              </div>
              <div class="col-md-7">
                  <label for="descricao">Descricao</label>
                  <input class="form-control" type="text" id="mDescPeca" name="mDescPeca">
              </div>
              <div class="col-md-1">
                  <label for="quantidade">Unidade</label>
                  <input class="form-control" type="text" id="mUniPeca" name="mUniPeca">
              </div>
              <div class="col-md-2">
                  <label for="quantidade">Quantidade</label>
                  <input class="form-control money" type="text" id="mQtdePeca" name="mQtdePeca"  onchange="javascript:calculaTotalModal( '', 'pecas')">
              </div>
              <div class="col-md-2">
                  <label for="unitario">Valor Unitário</label>
                  <input  class="form-control money" type="text" id="mVlrUniPeca" name="mVlrUniPeca"  onchange="javascript:calculaTotalModal( '', 'pecas')">
              </div>
              <div class="col-md-2">
                  <label for="totalitem">% Desconto</label>
                  <input class="form-control money" type="text" id="mPercDescPeca" name="mPercDescPeca" onchange="javascript:calculaTotalModal( '', 'pecas')">
              </div>
              <div class="col-md-2">
                  <label for="totalitem">Valor Desconto</label>
                  <input  class="form-control money" type="text" id="mDescontoPeca" name="mDescontoPeca" onchange="javascript:calculaTotalModal('desconto', 'pecas')">
              </div>
              <div class="col-md-3">
                  <label for="totalitem">Total</label>
                  <input Readonly class="form-control" type="text" id="mTotalPeca" name="mTotalPeca">
              </div>
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 