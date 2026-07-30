{* Modal de emissão de evento fiscal por chave de acesso *}
<div class="modal fade" id="modalEventoCbs" tabindex="-1" role="dialog" aria-labelledby="modalEventoTitulo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalEventoTitulo">Emitir evento</h4>
            </div>
            <div class="modal-body">
                <dl class="dl-horizontal">
                    <dt>Evento</dt><dd id="modalEventoTipo">—</dd>
                    <dt>Chave DF-e</dt><dd id="modalEventoChave" class="apuracao-chave">—</dd>
                    <dt>Papel</dt><dd id="modalEventoPapel">—</dd>
                </dl>
                <div class="form-group">
                    <label for="modalEventoObs">Observação (opcional)</label>
                    <textarea class="form-control" id="modalEventoObs" rows="3"></textarea>
                </div>
                <p class="text-muted">
                    <i class="fa fa-info-circle"></i>
                    O evento será registrado localmente. O envio à Receita Federal será habilitado quando o endpoint oficial for publicado.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="modalEventoConfirmar" onclick="apuracaoConfirmarEvento();">Registrar evento</button>
            </div>
        </div>
    </div>
</div>
