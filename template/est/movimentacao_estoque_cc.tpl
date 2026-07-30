<style>
.form-control,
.x_panel {
    border-radius: 5px;
}

#movCcPainelUltima {
    display: none;
    margin-bottom: 15px;
}

#movCcPainelUltima.is-visible {
    display: block;
}

#movCcResumoProduto {
    display: none;
    margin-top: 8px;
    padding: 8px 12px;
    background: #f7f7f7;
    border: 1px dashed #ddd;
    border-radius: 4px;
    font-size: 12px;
}

#movCcResumoProduto.is-visible {
    display: block;
}

.mov-cc-info-box {
    background: #f9f9f9;
    border: 1px solid #e5e5e5;
    border-radius: 5px;
    padding: 12px 15px;
    margin-bottom: 15px;
    font-size: 12px;
    color: #555;
}

.mov-cc-info-box h4 {
    margin: 0 0 8px;
    font-size: 13px;
    font-weight: 600;
    color: #333;
}
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Movimentação entre Centros de Custo
                            <small>Entrada de estoque e liberação de pedidos em encomenda</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-success btn-sm" onclick="javascript:submitConfirmarMovCc();">
                                    <span class="glyphicon glyphicon-ok"></span> Confirmar
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-info btn-sm" id="btnRomaneioMovCc" onclick="javascript:romaneio_mov_est_cc_imprime();" disabled>
                                    <span class="glyphicon glyphicon-print"></span> Romaneio
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-warning btn-sm" onclick="javascript:limpaDadosForm();">
                                    <span class="glyphicon glyphicon-refresh"></span> Limpar
                                </button>
                            </li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">

                        <div id="movCcPainelUltima" class="alert alert-success">
                            <strong>Última movimentação:</strong>
                            NF Entrada <span id="movCcDocEntrada">—</span>
                            <span id="movCcBoxSaida"> | NF Saída <span id="movCcDocSaida">—</span></span>
                            | Produto: <span id="movCcDocProduto">—</span>
                            | Qtde: <span id="movCcDocQuantidade">—</span>
                        </div>

                        <form id="lancamento" name="lancamento" method="POST"
                              class="form-horizontal form-label-left" action="{$SCRIPT_NAME}">
                            <input name="mod" type="hidden" value="est">
                            <input name="form" type="hidden" value="movimentacao_estoque_cc">
                            <input name="opcao" type="hidden" value="{$opcao}">
                            <input name="submenu" type="hidden" value="{$subMenu}">
                            <input name="idEntrada" type="hidden" value="{$idEntrada}">
                            <input name="idSaida" type="hidden" value="{$idSaida}">
                            <input name="idPedido" type="hidden" value="">
                            <input name="mDataEntrega" type="hidden" value="">
                            <input name="mCentroCusto" type="hidden" value="">

                            <div class="form-group">
                                <div class="col-lg-6 col-sm-10 col-xs-12">
                                    <label for="movCcSelectProduto">Produto</label>
                                    <select id="movCcSelectProduto" class="form-control" style="width:100%;"></select>
                                    <input type="hidden" id="codProduto" name="codProduto" value="{$codProduto}">
                                    <input type="hidden" id="descProduto" name="descProduto" value="">
                                    <input type="hidden" id="unidade" name="unidade" value="">
                                    <input type="hidden" id="valorVenda" name="valorVenda" value="">
                                    <input type="hidden" id="uniFracionada" name="uniFracionada" value="">
                                    <input type="hidden" id="quantAtual" name="quantAtual" value="">
                                    <div id="movCcResumoProduto">
                                        <strong>Código:</strong> <span id="movCcResumoCod">—</span> &nbsp;|&nbsp;
                                        <strong>Unidade:</strong> <span id="movCcResumoUn">—</span> &nbsp;|&nbsp;
                                        <strong>Estoque:</strong> <span id="movCcResumoEst">—</span>
                                        <span id="movCcResumoFracWrap" style="display:none;"> &nbsp;|&nbsp;
                                            <strong>Fracionado:</strong> <span id="movCcResumoFrac">—</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label>Quantidade</label>
                                    <input class="form-control money" id="qtdeEntrada" name="qtdeEntrada"
                                           placeholder="0,00" value="">
                                </div>
                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label>Modelo</label>
                                    <input class="form-control" id="modelo" readonly name="modelo" maxlength="2"
                                           value="{$modelo|default:'99'}">
                                </div>
                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label>Série NF</label>
                                    <input class="form-control" id="serieNf" readonly name="serieNf" maxlength="3"
                                           value="{$serieNf|default:'TFF'}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label>Centro de Custo Origem</label>
                                    <select class="form-control" name="centroCustoOrigem" id="centroCustoOrigem">
                                        {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCustoOrigem}
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label>Centro de Custo Destino</label>
                                    <select class="form-control" name="centroCustoDestino" id="centroCustoDestino">
                                        {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCustoDestino}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label for="movCcSelectConta">Conta / Pessoa</label>
                                    <select id="movCcSelectConta" class="form-control" style="width:100%;"></select>
                                    <input type="hidden" id="pessoa" name="pessoa" value="{$conta}">
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                    <label for="movCcSelectGenero">Gênero</label>
                                    <select id="movCcSelectGenero" class="form-control" style="width:100%;"></select>
                                    <input type="hidden" id="genero" name="genero" value="{$genero}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label>Observações</label>
                                    <textarea class="form-control" id="obs" name="obs" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="mov-cc-info-box">
                                        <h4><i class="fa fa-info-circle"></i> Informações</h4>
                                        <p style="margin:0;">
                                            Será gerada NF de ajuste (TFF) com movimentação de peças no estoque controlado.
                                            Se origem = destino, gera apenas NF de entrada. Após confirmar, o romaneio abre
                                            automaticamente. Pedidos em encomenda podem ser liberados após a entrada do material.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {include file="modal_produto_encomenda.tpl"}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_movimentacao_estoque_cc.js"></script>
<script>
    window.movCcCentroCustos = {$centroCustoJson|default:'[]'};
    window.movCcDataEntregaPadrao = '{$modalDataEntrega}';
    $(document).ready(function () {
        movCcIniciarTela();
    });
</script>
