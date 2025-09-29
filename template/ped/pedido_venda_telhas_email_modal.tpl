<div class="modal fade" id="modalEmail" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Para: </h6>
          <input class="form-control input-sm" type="text"  id="destinatario"  name="destinatario" value={$destinatario}>
          <h6 class="modal-title">Assunto: </h6>
          <input class="form-control input-sm" type="text"  id="assunto"  name="assunto" value={$assunto}>
          <h6 title="Com Cópia para:" class="modal-title">Cc: </h6>
          <input class="form-control input-sm" type="text"  id="comCopiaPara"  name="comCopiaPara" value={$comCopiaPara}>
        </div>
        <div class="modal-body">
            <textarea class="form-control" placeholder="Digite o corpo do email." rows="10" id="emailCorpo" name="emailCorpo">{$emailCorpo}</textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onClick="javascript:enviaEmailPedido('{$idPedido}');">Enviar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>                  
    </div>
  </div>
</div> 