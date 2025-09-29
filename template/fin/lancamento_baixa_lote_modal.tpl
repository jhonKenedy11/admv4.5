<div class="modal fade" id="modalBaixaLote" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>Baixa Titulos em Lote </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:rel_lanc_baixado_lote(),submitBaixaLancamentoLote();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
         <div class="modal-body">
          <div class="container-fluid">   
              <div class="col-md-4 small col-sm-12 col-xs-12 has-feedback">
                  <label for="situacao">Conta Bancaria</label>
                  <div class="panel panel-default small line-formated">
                    <select name="contaCombo" id="contaCombo" class="input-sm js-example-basic-single form-control" >
                        {html_options values=$contaCombo_ids selected=$contaCombo_id output=$contaCombo_names}
                    </select>
                 </div>
              </div>
              <div class="col-md-3">
                  <label for="dataVencimento">Data Movimento</label>
                  <input class="form-control" type="text" id="mDataEmissao" name="mDataEmissao" >
              </div>

              <div class="col-md-3">
                  <label for="total">Total</label>
                  <input readonly class="form-control" type="text" id="mTotalBaixar" name="mTotalBaixar" >
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
        $('#mDataEmissao').daterangepicker({
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
