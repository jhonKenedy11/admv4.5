<div class="modal fade" id="modalAlteraServico" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Alterar Serviço </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAlteraServico();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              
              <div class="col-md-2">
                  <label for="codigo">Codigo</label>
                  <input Readonly class="form-control" type="text" id="mIdServico" name="mIdServico">
              </div>
              <div class="col-md-10">
                  <label for="descricao">Descricao</label>
                  <input class="form-control" type="text" id="mDescServico" name="mDescServico">
              </div>
              <div class="col-md-3">
                  <label for="quantidade">Unidade</label>
                  <input  class="form-control" type="text" id="mUniServico" name="mUniServico">
              </div>
              <div class="col-md-3">
                  <label for="quantidade">Quantidade</label>
                  <input  class="form-control money" type="text" id="mQtdeServico" name="mQtdeServico" onchange="javascript:calculaTotalModal( '', 'servico')">
              </div>
              <div class="col-md-3">
                  <label for="unitario">Valor Unitário</label>
                  <input  class="form-control money" type="text" id="mVlrUniServico" name="mVlrUniServico" onchange="javascript:calculaTotalModal( '', 'servico')">
              </div>
              <div class="col-md-3">
                  <label for="totalitem">Total</label>
                  <input Readonly class="form-control" type="text" id="mTotalServico" name="mTotalServico">
              </div>
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 