<!-- page content -->
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                            <img src="images/logo.png" align="right" width="180" height="45" border="0">
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8 form-group text-center">
                            <h2><strong>CURVA ABC</strong></h2>
                            <h4>Período: {$periodoIni} | {$periodoFim}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cód. Fabricante</th>
                                        <th>Produto</th>
                                        <th>Grupo</th>
                                        <th>Qtde Mín</th>
                                        <th>Venda</th>
                                        <th>Qtde</th>
                                        <th>Valor</th>
                                        <th>Num. Vendas</th>
                                        <th>% Part</th>
                                        <th>% Acm</th>
                                        <th>Classificação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$pedido}
                                    <tr>
                                        <td>{$pedido[i].COUNT}</td>
                                        <td>{$pedido[i].ITEMESTOQUE}</td>
                                        <td>{$pedido[i].DESCRICAO}</td>
                                        <td>{$pedido[i].GRUPO}</td>
                                        <td>{$pedido[i].QUANTMINIMA}</td>
                                        <td>{$pedido[i].VENDA}</td>
                                        <td>{$pedido[i].QUANT}</td>
                                        <td>{$pedido[i].VALOR|number_format:2:",":"."}</td>
                                        <td>{$pedido[i].NUMVENDAS}</td>
                                        <td>{$pedido[i].PARTICIPACAO|number_format:4:",":"."}</td>
                                        <td>{$pedido[i].ACUMULADO|number_format:4:",":"."}</td>
                                        <td>{$pedido[i].CLASSIFICACAO}</td>
                                    </tr>
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row no-print">
                        <div class="col-xs-12 text-right">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

