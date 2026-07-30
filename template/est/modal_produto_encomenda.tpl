<!-- Modal: pedidos em encomenda -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Produto em Encomenda
                    <span id="movCcModalBadge" class="badge">0</span>
                </h4>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="margin:0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:60px;">Pedido</th>
                                <th>Cliente</th>
                                <th class="text-center" style="width:70px;">Qtd Falta</th>
                                <th>Descrição</th>
                                <th class="text-center">C. Custo</th>
                                <th class="text-center" style="width:100px;">Entrega</th>
                                <th class="text-center" style="min-width:150px;">CC Entrega</th>
                                <th class="text-center" style="width:60px;">Ação</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEncomendaModal"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
