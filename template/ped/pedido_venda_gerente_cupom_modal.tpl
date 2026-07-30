<div class="modal fade" id="modalCupomFiscal" tabindex="-1" role="dialog" aria-labelledby="modalCupomFiscalTitulo">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row" style="margin:0;">
                    <div class="col-xs-12 col-sm-5">
                        <h4 class="modal-title" id="modalCupomFiscalTitulo" style="margin:0;">Cupom fiscal</h4>
                    </div>
                    <div class="col-xs-12 col-sm-7 text-right" id="modalCupomFiscalFooter" style="padding-top:4px;">
                        <button type="button" class="btn btn-success" id="btnCupomEditarPedido" style="display:none;" onclick="cupomGerenteEditarPedidoNovaAba();" title="Abrir pedido para edição em nova aba">
                            <span class="glyphicon glyphicon-pencil"></span> Editar pedido
                        </button>
                        <button type="button" class="btn btn-primary" id="btnCupomNfce" style="display:none;" onclick="cupomGerenteEmitir('cadastraNf');">
                            <span class="glyphicon glyphicon-floppy-save"></span> NFC-e
                        </button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar" style="float:none;margin-left:10px;font-size:28px;line-height:1;opacity:.6;"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="modalCupomFiscalBody">
                <p class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Carregando...</p>
            </div>
        </div>
    </div>
</div>
