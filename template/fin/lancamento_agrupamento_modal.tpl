<div class="modal fade" id="modalAgrupamentoLanc" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Agrupamento Lançamentos </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitAgruparLancamento();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">   
                      
              
              <div class="col-md-7">
                  <label for="descricao">Pessoa</label>
                  <input class="form-control" type="text" id="mPessoa" name="mPessoa">
              </div>
               <div class="col-md-2">
                  <label for="mNumDocto">Docto</label>
                  <input class="form-control" type="text" id="mNumDocto" name="mNumDocto" maxlength="11">
              </div>  
              <div class="col-md-3">
                  <label for="dataVencimento">Data Vencimento</label>
                  <input class="form-control" type="text" id="mDataVencimento" name="mDataVencimento" >
              </div>
              <div class="col-md-3">
                  <label for="multaItem">Multa</label>
                  <input  class="form-control money" type="text" id="mMulta" name="mMulta">
              </div>
              <div class="col-md-3">
                  <label for="jurosItem">Juros</label>
                  <input  class="form-control money" type="text" id="mJuros" name="mJuros">
              </div>             
              <div class="col-md-3">
                  <label for="descontoItem">Desconto</label>
                  <input  class="form-control money" type="text" id="mDesconto" name="mDesconto">
              </div>  
              <div class="col-md-3">
                  <label for="totalitem">T O T A L </label>
                  <input readonly class="form-control" type="text" id="mTotal" name="mTotal">
              </div>
          </div>
        </div>  
        <div class="modal-footer">
        </div>                
    </div>
  </div>
</div> 
<script>
      $(function() {
        $('#mDataVencimento').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });  
      });
    </script>
<style>
    body.modal-open .daterangepicker {
    z-index: 1200 !important;
    }
</style>
