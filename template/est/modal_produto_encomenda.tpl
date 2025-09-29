<!-- Modal mostra produto em situacao de encomenda -->
<div id="myModal" class="modal fade bd-example-modal-lg" style="background-color: transparent;" role="dialog">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
    <div class="modal-dialog modal-lg" style="background-color: transparent;">
        <input name=mDataEntrega type=hidden value="{$mDataEntrega}">
        <input name=mCentroCusto type=hidden value="{$mCentroCusto}">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <center><b>Produto em Encomenda</b></center>
                </h4>
            </div>

            <table id="datatable" class="table table-bordered jambo_table">
                <thead>
                    <tr class="">
                        <th style="width: 60px;">
                            <center>Pedido</center>
                        </th>
                        <th>
                            <center>Cliente</center>
                        </th>
                        <th style="width: 75px;">
                            <center>Qtde</center>
                        </th>
                        <th>
                            <center>Descricao</center>
                        </th>
                        <th>
                            <center>C. Custo</center>
                        </th>
                        <th style="width: 100px;">
                            <center>Data Entrega</center>
                        </th>
                        <th>
                            <center>C.Custo Entrega</center>
                        </th>
                        <th>
                            <center>Baixa Ped</center>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    {section name=i loop=$mensagem}
                        <tr id="{$mensagem[i].PEDIDO}">
                            <td align=center> {$mensagem[i].PEDIDO} </td>
                            <td align=center> {$mensagem[i].NOMEREDUZIDO} </td>
                            <td align=center> {$mensagem[i].QTSOLICITADA|number_format:2:",":"."} </td>
                            <td align=center> {$mensagem[i].DESCRICAO} </td>
                            <td align=center> {$mensagem[i].CCUSTO} </td>
                            <td align=center>
                                <input class="form-control" id="modalDataEntrega{$mensagem[i].PEDIDO}" type="text"
                                    name="modalDataEntrega" maxlength="10" data-mask="00/00/0000"
                                    {if $mensagem[i].PRAZOENTREGA eq ''} value={$modalDataEntrega} 
                                    {else}
                                    value={$mensagem[i].PRAZOENTREGA} {/if}>
                            </td>
                            <td align=center>
                                <div class="form-group col-md-10 col-sm-10 col-xs-10" id="divCcEntrega">
                                    <SELECT class="js-example-basic-single form-control" name="modalCentroCusto"
                                        id="modalCentroCusto{$mensagem[i].PEDIDO}">
                                        {html_options values=$centroCusto_ids output=$centroCusto_names selected=$mensagem[i].CENTROCUSTOENTREGA}
                                    </SELECT>
                                </div>
                            </td>
                            <td align=center width="80px">
                                <button type="button" class="btn btn-dark btn-xs" id="btnAtualizaEncomenda"
                                    onclick="javascript:atualizaPedidoEncomenda({$mensagem[i].PEDIDO})">
                                    <span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"
                                        data-toggle="tooltip" title="Baixa Pedido"></span>
                                </button>
                            </td>
                        </tr>
                        <p>
                        {/section}

                </tbody>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!--Fim myModal -->

<!-- Botao hidden que irá inicar a funcao para buscar produto em encomenda -->
<button type="button" hidden name="verificaCotacao" id="verificaCotacao"
    onclick="javascript:buscaProdutoEncomenda();"></button>

<!-- Modal mostra msg status pedido-->
<div id="modalMsg" class="modal fade bd-example-modal-lg" style="background-color: transparent;" role="dialog">
    <div class="modal-dialog modal-lg" style="background-color: transparent;">
        <!-- Modal content-->
        <div class="modal-content" id="mMsg">
            <div class="modal-header" id="modalMsgHeader">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" name="msgPedModal">
                    <center><b>{$msgPedModal}</b></center>
                </h4>
            </div>

        </div>

    </div>
    <!--Fim modalMsg -->
    <style>
        button.close {
            margin-left: 10px !important;
        }

        #modalMsgFooter {
            margin-bottom: -100px;
        }

        #modalMsgHeader {
            border-bottom: none !important;
        }

        #modalMsgFooter {
            border-top: none !important;
        }

        #modalMsgFechar {
            margin-top: 80px !important;
            margin-right: -7px !important;
        }

        #btnCalcel {
            margin-top: 15px;
        }

        .modal-backdrop {
            /* bug fix - no overlay */
            display: none !important;
        }

        #divCcEntrega {
            padding: 0;
        }

        #btnAtualizaEncomenda {
            margin-top: 6px;
        }

        body .container.body .right_col {
            background: #F7F7F7;
            height: 1000px;
        }

        [name=modalCentroCusto] {
            width: 181px;
            padding: 0;
            text-align: center;
        }

        [name=modalDataEntrega] {
            padding: 2px;
            width: 90px;
            text-align: center;
        }
</style>