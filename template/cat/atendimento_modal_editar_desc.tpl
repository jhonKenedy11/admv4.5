<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_new.js"> </script>    

<div class="modal fade" id="modalUpdateDesc" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Nova Descrição</h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:atualizaDesc();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
         <div class="modal-body">
          <div class="container-fluid">   
              <div class="col-md-12">
                  <label for="novaDesc">Descrição</label>
                  <textarea class="resizable_textarea form-control" rows="3" type="text" id="novaDesc" name="novaDesc"></textarea>
              </div>
          </div>
        </div>
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div>
  
<style>
    body.modal-open .daterangepicker {
    z-index: 1200 !important;
    }
</style>
