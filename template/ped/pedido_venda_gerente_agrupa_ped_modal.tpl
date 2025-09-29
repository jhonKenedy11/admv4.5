<div class="modal fade" id="modalAgrupamentoPed" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Agrupamento Pedido </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAgruparPedidos();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">              
              
              <div class="col-md-6">
                  <label for="descricao">Pessoa</label>
                  <input class="form-control" type="text" id="mPessoa" name="mPessoa">
              </div>
              <div class="col-md-2">
                  <label for="quantidade">Frete</label>
                  <input class="form-control money" type="text" id="mFrete" name="mFrete">
              </div>
              <div class="col-md-2">
                  <label for="quantidade">Desp Acessórias</label>
                  <input class="form-control money" type="text" id="mDespAcessorias" name="mDespAcessorias"  onchange="javascript:calculaTotalModal( '', 'pecas')">
              </div>
              <div class="col-md-2">
                  <label for="unitario">Desconto</label>
                  <input  class="form-control money" type="text" id="mDesconto" name="mDesconto"  onchange="javascript:calculaTotalModal( '', 'pecas')">
              </div>
              <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                  <label for="situacao">Situação</label>
                  <div class="panel panel-default small line-formated">
                     <select id="mSituacao" name="mSituacao" class="form-control input-sm" title="Situação Pedido" alt="Situação Pedido">
                         {html_options values=$situacao_ids selected=$mSituacao output=$situacao_names}
                     </select>
                 </div>
              </div>
              <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                  <label for="situacao">Condição de Pagamento</label>
                  <div class="panel panel-default small line-formated">
                    <select id="condPgto" name="condPgto" class="input-sm js-example-basic-single form-control" >
                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                    </select>
                 </div>
              </div>
              <div class="col-md-4">
                 
              </div>
              <div class="col-md-2">
                  <label for="totalitem">T O T A L </label>
                  <input Readonly class="form-control" type="text" id="mTotal" name="mTotal">
              </div>
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 
