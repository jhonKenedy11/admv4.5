<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

    <div class="">

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>DASHBOARD
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetraDash('');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="ped">
                            <input name=form type=hidden value="pedido_venda_telhas_dash">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=fornecedor type=hidden value="">
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=situacao type=hidden value={$situacao}>
                            <input name=dataIni type=hidden value={$dataIni}>
                            <input name=dataFim type=hidden value={$dataFim}>

                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label for="classe">Centro de Custo</label>
                                <SELECT class="select2_multiple form-group" multiple="multiple" id="centroCusto"
                                    name="centroCusto">
                                    {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                                </SELECT>
                            </div>
                        </form>


                    </div>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->


        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->

    </div> <!-- div class="row "-->


    <div class="row">
        <br>
        <div {if $financeiro==''} hidden {/if} class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Operacional</small></h2>
                </div>
                <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive small">
                        <thead id="theadMotivo">

                        </thead>
                        <tbody id="bodyMotivo">
                            {section name=i loop=$financeiro}
                                {if $financeiro[i].TIPOLANCAMENTO == "P"}
                                    {assign var="totalP" value=$totalP+{$financeiro[i].TOTAL}}
                                {/if}

                                {if $financeiro[i].TIPOLANCAMENTO == "R"}

                                    {assign var="totalR" value=$totalR+{$financeiro[i].TOTAL}}

                                    {if (($financeiro[i].SITPGTO eq 'B')&&($totalRB == 0))}
                                        {assign var="totalRB" value=$totalRB+{$financeiro[i].TOTAL}}
                                        <tr>
                                            <td BGCOLOR=#FFD700>TOTAL</td>
                                            <td>{$totalR - {$financeiro[i].TOTAL}} </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td BGCOLOR=#87CEFA>RECEBIDO</td>
                                            <td> </td>
                                        </tr>
                                    {elseif ($totalRB > 0)}
                                        {assign var="totalRB" value=$totalRB+{$financeiro[i].TOTAL}}
                                    {/if}
                                {/if}

                                {if (($financeiro[i].TIPOLANCAMENTO == "R") && ($totalP > 0))}
                                    <td BGCOLOR=#c0c0c0>TOTAL</td>
                                    <td>{$totalP} </td>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    {if $financeiro[i].SITPGTO eq 'A'}

                                        <tr>
                                            <td BGCOLOR=#FFD700>RECEITAS FUTURAS</td>
                                            <td> </td>
                                        </tr>
                                    {/if}
                                    {assign var="totalP" value=0}
                                    {assign var="totalRB" value=0}
                                    {assign var="totalRA" value=0}
                                {/if}

                                <tr>
                                    {if $financeiro[i].TIPOLANCAMENTO == "P"}
                                        <td align=left BGCOLOR=#FA8072>
                                            {$financeiro[i].GENERO}
                                        </td>
                                        <td>{$financeiro[i].TOTAL} </td>
                                    {else}
                                        <td align=left
                                            {if $financeiro[i].SITPGTO eq 'A'}BGCOLOR=#FFD700{else}BGCOLOR=#87CEFA{/if}>
                                            {if $financeiro[i].TIPODOCTO eq 'TRANFERENCIA BANCARIA'}
                                                TRANSFERENCIA BANCARIA
                                            {else if $financeiro[i].TIPODOCTO eq 'PIX'}
                                                PIX
                                            {else if $financeiro[i].TIPODOCTO eq 'A RECEBER'}
                                                A RECEBER
                                            {else if $financeiro[i].TIPODOCTO eq 'CHEQUE'}
                                                CHEQUE
                                            {elseif $financeiro[i].TIPODOCTO eq 'CARTAO DEBITO'}
                                                CARTAO DEBITO
                                            {elseif $financeiro[i].TIPODOCTO eq 'DINHEIRO'}
                                                DINHEIRO
                                            {elseif $financeiro[i].TIPODOCTO eq 'CARTAO CREDITO'}
                                                CARTAO CREDITO
                                            {elseif $financeiro[i].TIPODOCTO eq 'BOLETO'}
                                                BOLETO
                                            {elseif $financeiro[i].TIPODOCTO eq 'BONUS'}
                                                BONUS
                                            {elseif $financeiro[i].GENERO eq 'RECEITAS FUTURAS'}
                                                RECEITAS FUTURAS
                                            {elseif $financeiro[i].GENERO eq 'DESPESAS COM LOGISTICA'}
                                                DESPESAS FIXAS
                                            {elseif $financeiro[i].GENERO eq 'ENTRADA'}
                                                ENTRADA
                                            {/if}
                                        </td>
                                        <td>{$financeiro[i].TOTAL} </td>
                                    {/if}
                                </tr>

                                <p>
                                {/section}
                                <tr>
                                    <td BGCOLOR=#77CEFE>TOTAL</td>
                                    <td>{$totalRB} </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td BGCOLOR=#ADD8E6>TOTAL</td>
                                    <td>{$totalR} </td>
                                </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div {if $financeiro == "" }hidden{/if} class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Gerencial</h2>
                </div>
                <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive small">
                        <tbody id="bodyMotivo">
                            {section name=i loop=$total}
                                <tr>
                                    <td BGCOLOR=#ADFF2F align=left>VALOR DE VENDA</td>
                                    <td>{$total[i].VALORVENDA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td BGCOLOR=#ADFF2F align=left>LUCRO BRUTO</td>
                                    <td>{$total[i].LUCROBRUTO|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td BGCOLOR=#ADFF2F align=left>LUCRO LÍQUIDO</td>
                                    {($total[i].VALORVENDA != 0) ? (((($total[i].LUCROBRUTO - $total[i].DESPESAS) / $total[i].VALORVENDA) * 100)|number_format:2:",":".") : 0}
                                </tr>
                                <tr>
                                    <td BGCOLOR=#ADFF2F align=left>EQUILÍBRIO</td>
                                    <td>
                                        {if $total[i].VALORVENDA > 0}
                                            {((($total[i].LUCROBRUTO - $total[i].DESPESAS) / $total[i].VALORVENDA) * 100)|number_format:2:",":"."}
                                        {else}
                                            0,00
                                        {/if}
                                    </td>
                                </tr>

                                <tr>
                                    <td BGCOLOR=#fbec5d align=left>CUSTO VENDA TOTAL</td>
                                    <td>{$total[i].CUSTOTOTAL|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td BGCOLOR=#fbec5d align=left>MARKUP LOJA</td>
                                    <td>{$total[i].MARKUP|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td BGCOLOR=#fbec5d align=left>MARGEM BRUTA LOJA</td>
                                    <td>{$total[i].MARGEMBRUTA|number_format:2:",":"."} </td>
                                </tr>
                                <p>
                                {/section}

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div {if $totaisDet == "" }hidden{/if} class="row">
        <div class="text-center" style="background-color:#5b87b2" colspan="6">
            <font color="white">GERENCIAL</font>
            </th>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">

                    <table class="table table-bordered jambo_table">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th>Custo Medio</th>
                                <th>MARKUP</th>
                                <th>Margem Bruta</th>
                            </tr>
                        </thead>
                        <tbody>

                            {section name=index loop=$totaisDet}
                                {assign var="CUSTOVENDEDORTOTAL" value= $CUSTOVENDEDORTOTAL + $totaisDet[index].CUSTOVENDEDOR}
                                {assign var="MARKUPTOTAL" value= $MARKUPTOTAL + $totaisDet[index].MARKUP}
                                {assign var="MARGEMBRUTATOTAL" value= $MARGEMBRUTATOTAL + $totaisDet[index].MARGEMBRUTA}
                                <tr>
                                    <td> {$totaisDet[index].VENDEDOR} </td>
                                    <td> {$totaisDet[index].CUSTOVENDEDOR|number_format:2:",":"."} </td>
                                    <td> {$totaisDet[index].MARKUP|number_format:2:",":"."} </td>
                                    <td> {$totaisDet[index].MARGEMBRUTA|number_format:2:",":"."} </td>
                                </tr>
                                <p>
                                {/section}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div {if $forecast == "" }hidden{/if} class="row">
        <div class="text-center" style="background-color:#5b87b2" colspan="6">
            <font color="white">FORECAST</font>
            </th>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive small">
                        <thead id="theadMotivo">

                        </thead>
                        <tbody id="bodyMotivo">
                            {section name=i loop=$forecast}
                                <tr>
                                    <td align=left>META DIÁRIA</td>
                                    <td>{$forecast[i].METADIARIA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>FALTA VOLUME ATINGIMENTO DE META</td>
                                    <td>{$forecast[i].FALTA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>PROJEÇÃO DE VALOR DE VENDAS</td>
                                    <td>{$forecast[i].PROJECAOVALORVENDA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>PROJEÇÃO DE DESPESAS</td>
                                    <td>{$forecast[i].PROJECAODESPESAS|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>PROJEÇÃO DE RECEITAS</td>
                                    <td>{$forecast[i].PROJECAORECEITAS|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>PROJEÇÃO DE LUCRO LIQUIDO</td>
                                    <td>{$forecast[i].PROJECAOLUCROLIQUIDO|number_format:2:",":"."} </td>
                                </tr>
                            {/section}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div {if $forecast == "" }hidden{/if} class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive small">
                        <tbody id="bodyMotivo">
                            {section name=i loop=$forecast}
                                <tr>
                                    <td align=left>DIAS RESTANTES FECHAMENTO MÊS</td>
                                    <td>{$forecast[i].DIASRESTANTESDOMES|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>TICKET MEDIO DE VENDAS</td>
                                    <td>{$forecast[i].TICKETMEDIODEVENDAS|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>LUCRO BRUTO MEDIO POR VENDA</td>
                                    <td>{$forecast[i].LUCROBRUTOMEDIOPORVENDA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td align=left>LUCRO LIQUIDO MEDIO POR VENDA</td>
                                    <td>{$forecast[i].LUCROLIQUIDOMEDIOPORVENDA|number_format:2:",":"."} </td>
                                </tr>
                                <tr>
                                    <td text-align: left>NÚMERO DE VENDAS PROJETADAS</td>
                                    <td>{$forecast[i].NUMERODEVENDASPROJETADAS|string_format:"%d"} </td>
                                </tr>
                                <tr>
                                    <td align=left> </td>
                                    <td> </td>
                                </tr>

                            {/section}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div {if $projecao == "" }hidden{/if} class="row">
        <table class="table table-bordered jambo_table">
            <thead>
                <tr>
                    <th class="text-center" bgcolor="#5b87b2" colspan="6">Projecao</th>
                </tr>
                <tr>
                    <th>Vendedor</th>
                    <th>Proj Vendas</th>
                    <th>Num Vendas</th>
                    <th>Proj Lucro Bruto</th>
                    <th>Proj Lucro Liquido</th>
                </tr>
            </thead>
            <tbody>

                {section name=index loop=$projecao}
                    {assign var="PROJECAOVENDASTOTAL" value= $PROJECAOVENDASTOTAL + $projecao[index].PROJECAOVENDAS}
                    {assign var="NUMERODEVENDASTOTAL" value= $NUMERODEVENDASTOTAL + $projecao[index].NUMERODEVENDAS}
                    {assign var="PROJECAOLUCROBRUTOTOTAL" value= $PROJECAOLUCROBRUTOTOTAL + $projecao[index].PROJECAOLUCROBRUTO}
                    {assign var="PROJECAOLUCROLIQUIDOTOTAL" value= $PROJECAOLUCROLIQUIDOTOTAL + $projecao[index].PROJECAOLUCROLIQUIDO}

                    {if $dash[index].NOME == 'TOTAL'}
                        <tr bgcolor="#6b87b2">
                        {else}
                        <tr>
                        {/if}
                        <td> {$projecao[index].VENDEDOR} </td>
                        <td> {$projecao[index].PROJECAOVENDAS|number_format:2:",":"."} </td>
                        <td> {$projecao[index].NUMERODEVENDAS|number_format:2:",":"."} </td>
                        <td> {$projecao[index].PROJECAOLUCROBRUTO|number_format:2:",":"."} </td>
                        <td> {$projecao[index].PROJECAOLUCROLIQUIDO|number_format:2:",":"."} </td>
                    </tr>
                    <p>
                    {/section}
                    <tr>
                        <td bgcolor="#6495ed"> {"TOTAL"} </td>
                        <td bgcolor="#6495ed"> {$PROJECAOVENDASTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$NUMERODEVENDASTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$PROJECAOLUCROBRUTOTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$PROJECAOLUCROLIQUIDOTOTAL|number_format:2:",":"."} </td>
                    </tr>

            </tbody>
        </table>
    </div>
    <div {if $metas == "" }hidden{/if} class="row">
        <table class="table table-bordered jambo_table">
            <thead>
                <tr>
                    <th class="text-center" bgcolor="#5b87b2" colspan="7">Metas</th>
                </tr>
                <tr>
                    <th>Vendedor</th>
                    <th>Metas Venda</th>
                    <th>ICM Vendas</th>
                    <th>Valor Vendido</th>
                    <th>Custo </th>
                    <th>Lucro Bruto </th>
                    <th>NUM Vendas </th>
                </tr>
            </thead>
            <tbody>

                {section name=index loop=$metas}
                    {assign var="METADEVENDASTOTAL" value= $METADEVENDASTOTAL + $metas[index].METADEVENDAS}
                    {assign var="ICMVENDASTOTAL" value= $ICMVENDASTOTAL + $metas[index].ICMVENDAS}
                    {assign var="VALORVENDIDOTOTAL" value= $VALORVENDIDOTOTAL + $metas[index].VALORVENDIDO}
                    {assign var="ICMVENDASTOTALPERC" value= ($METADEVENDASTOTAL != 0) ? (($VALORVENDIDOTOTAL / $METADEVENDASTOTAL) * 100) : 0 }
                    {assign var="CUSTOTOTALV" value= $CUSTOTOTALV + $metas[index].CUSTOTOTAL  }
                    {assign var="NUMVENDASTOTAL" value= $NUMVENDASTOTAL + $metas[index].NUMVENDAS  }
                    {assign var="LUCROBRUTOTOTAL" value= $LUCROBRUTOTOTAL + $metas[index].LUCROBRUTO   }
                    {assign var="NUMVENDASTOTAL" value= $NUMVENDASTOTAL + $metas[index].NUMVENDAS   }

                    {if $dash[index].NOME == 'TOTAL'}
                        <tr bgcolor="#6b87b2">
                        {else}
                        <tr>
                        {/if}
                        <td> {$metas[index].VENDEDOR} </td>
                        <td> {$metas[index].METADEVENDAS|number_format:2:",":"."} </td>
                        <td> {$metas[index].ICMVENDAS|number_format:2:",":"."} </td>
                        <td> {$metas[index].VALORVENDIDO|number_format:2:",":"."} </td>
                        <td> {$metas[index].CUSTOTOTAL|number_format:2:",":"."} </td>
                        <td> {($metas[index].LUCROBRUTO )|number_format:2:",":"."} </td>
                        <td> {($metas[index].NUMVENDAS )|number_format:2:",":"."} </td>
                    </tr>
                    <p>
                    {/section}
                    <tr>
                        <td bgcolor="#6495ed"> {"TOTAL"} </td>
                        <td bgcolor="#6495ed"> {$METADEVENDASTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$ICMVENDASTOTALPERC|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$VALORVENDIDOTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$CUSTOTOTALV|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$LUCROBRUTOTOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$NUMVENDASTOTAL|number_format:2:",":"."} </td>
                    </tr>

            </tbody>
        </table>

    </div>
    <div {if $metas == "" }hidden{/if} class="row">
        <table class="table table-bordered jambo_table">
            <thead>
                <tr>
                    <th>Vendedor</th>
                    <th>MMLIQUIDA</th>
                    <th>ICM</th>
                    <th>Margem Liquida</th>
                </tr>
            </thead>
            <tbody>

                {section name=index loop=$metas}
                    {assign var="MMLIQUIDATOTAL" value= $MMLIQUIDATOTAL + $metas[index].MMLIQUIDA}
                    {assign var="ICMTOTAL" value= $ICMTOTAL + $metas[index].ICM}
                    {assign var="MARGEMLIQUIDATOTAL" value= $MARGEMLIQUIDATOTAL + $metas[index].MARGEMLIQUIDA}
                    {assign var="ICMTOTALPERC" value= ($MMLIQUIDATOTAL != 0) ? (($MARGEMLIQUIDATOTAL / $MMLIQUIDATOTAL) * 100) : 0 }

                    {if $dash[index].NOME == 'TOTAL'}
                        <tr bgcolor="#6b87b2">
                        {else}
                        <tr>
                        {/if}
                        <td> {$metas[index].VENDEDOR} </td>
                        <td> {$metas[index].MMLIQUIDA|number_format:2:",":"."} </td>
                        <td> {$metas[index].ICM|number_format:2:",":"."} </td>
                        <td> {$metas[index].MARGEMLIQUIDA|number_format:2:",":"."} </td>
                    </tr>
                    <p>
                    {/section}
                    <tr>
                        <td bgcolor="#6495ed"> {"TOTAL"} </td>
                        <td bgcolor="#6495ed"> {$MMLIQUIDATOTAL|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$ICMTOTALPERC|number_format:2:",":"."} </td>
                        <td bgcolor="#6495ed"> {$MARGEMLIQUIDATOTAL|number_format:2:",":"."} </td>
                    </tr>


            </tbody>
        </table>

    </div>

</div> <!-- class='' = controla menu user -->

<!-- /Datatables -->


{include file="template/database.inc"}


<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<script>
    function setaDadosModal(valor) {
        document.getElementById('motivo_pedido_id').value = valor;
    }
</script>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#centroCusto.select2_multiple").select2({
            allowClear: true,
            width: "88%"
        });

    });
</script>



<!-- daterangepicker -->
<script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                firstDay: 1
            }

        },
        //funcao para recuperar o valor digirado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        });
</script>
<!-- /daterangepicker -->

<script>
    new Chart(document.getElementById("bar-chart-grouped"), {
        type: 'bar',
        data: {
            labels: [{$mes}],
            datasets: [
                {$dados}
            ]
        },
        options: {
            title: {
                display: true,
                text: ' '
            }
        }
    });
</script>