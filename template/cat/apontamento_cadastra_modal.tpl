<div class="modal fade" id="modalCadastraApontamento" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <div class="form-group">
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <h4>{$tituloModal} Apontamento </h4>
              </div> 
              <div class="col-md-8 col-sm-12 col-xs-12" align="right">
              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitConfirmarApontamento();">Confirmar</button>
              <button type="button" class="btn btn-default" data-dismiss="modal" >Fechar</button>
              </div>
          </div>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
              
              <div class="col-md-1">
                  <label for="codigo">ID</label>
                  <input Readonly class="form-control" type="text" id="mIdApontamento" name="mIdApontamento">
              </div>
              <div class="col-md-3">
                  <label for="codigo">Codigo Serviço</label>
                  <input class="form-control" type="text" id="mCodServico" name="mCodServico" value="{$mCodServico}">
              </div>
              <div class="col-md-8">
                  <label for="descricao">Descricao</label>
                  <input class="form-control" type="text" id="mDescricaoApontamento" name="mDescricaoApontamento">
              </div>

              <div class="col-md-3 small col-sm-12 col-xs-12 has-feedback">
                  <label for="idServico">Usuario</label>
                  <div class="panel panel-default small line-formated">
                     <select id="idUser" name="idUser" class="form-control input-sm" title="Atendente" alt="Atendente">
                         {html_options values=$usr_ids selected=$usr output=$usr_names}
                     </select>
                 </div>
              </div>
              <div class="col-md-3">
                  <label for="quantidade">Data Apontamento</label>
                  <input class="form-control input-sm" type="text" id="mData" 
                                            name="mData" placeholder="Data"  
                                            value={$mData}>
              </div>
              <div class="col-md-2">
                  <label for="quantidade">Inicio/Hr(s)</label>
                  <input class="form-control input-sm" type="text" id="mDataInicio" 
                                            name="mDataInicio" placeholder="Data/Hr Inicio"  
                                            value={$mDataInicio}>
              </div>
              <div class="col-md-2">
                  <label for="unitario">Fim/Hr(s)</label>
                  <input class="form-control input-sm" type="text" id="mDataFim" 
                                        name="mDataFim" placeholder="Data/Hr Fim" alt="Data Hr Fim" 
                                        onchange="validaTotalHoras()"
                                        value={$mDataFim}>
              </div>
              <div class="col-md-2">
                  <label for="totalitem">Total Horas</label>
                  <input class="form-control input-sm" readonly type="text" id="mTotalHoras" 
                        name="mTotalHoras" placeholder="Total Horas" value={$mTotalHoras}>
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

        $('#mData').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });  
          
        $('input[id$="mDataInicio"]').inputmask(
            "hh:mm:ss", {
            placeholder: "00:00:00", 
            insertMode: false, 
            showMaskOnHover: false
        });
        $('input[id$="mDataFim"]').inputmask(
            "hh:mm:ss", {
            placeholder: "00:00:00", 
            insertMode: false, 
            showMaskOnHover: false
        });
      });
    </script>
    <script>
        
    </script>
    <style>
    body.modal-open .daterangepicker {
    z-index: 1200 !important;
    }
    </style>