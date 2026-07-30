<div class="modal fade" id="modalCupomPdv" tabindex="-1" role="dialog" aria-labelledby="modalCupomPdvTitulo">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalCupomPdvTitulo">Emitir cupom fiscal</h4>
            </div>
            <div class="modal-body" id="modalCupomPdvBody">
                <p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Carregando...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnPdvModalEmitir" style="display:none;"
                    onclick="pdvModalEmitirNfce();">
                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                    <span> Emitir NFC-e</span>
                </button>
            </div>
        </div>
    </div>
</div>
