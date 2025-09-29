<style>
.form-control, .x_panel{
    border-radius: 5px;
}
.btnRelatorios{
    width: 100% !important;
}
.dropMenuRel{
    right: -190% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
}

</style>

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_ps.js"> </script>
<!-- page content -->
<div class="right_col" role="main" style="padding: 14px;">

    <div class="">

        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12" style="padding: 1px;">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Consulta pedidos
                            <strong>
                                {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary"
                                    onClick="javascript:submitCadastro('');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Novo
                                        Pedido</span>
                                </button>
                            </li>
                            <li>
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-print"></i></a>
                                    <ul class="dropdown-menu dropMenuRel" role="menu">
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('');">
                                                <span> Relatório Vendas</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Detalhado');">
                                                <span> Relatório Vendas Detalhado</span>
                                            </button>
                                        </li>
                                        
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Item');">
                                                <span> Relatório Vendas Item</span>
                                            </button>
                                        </li>
                                        {*
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Fatura');">
                                                <span> Relatório Vendas Fatura</span>
                                            </button>
                                        </li>
                                        *}
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Vendedor');">
                                                <span> Relatório Vendas Vendedor</span>
                                            </button>
                                        </li>
                                        {*
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Semana');">
                                                <span> Relatório Vendas Semana</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Mes');">
                                                <span> Relatório Vendas Mes</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Motivo');">
                                                <span> Relatório Vendas Motivo</span>
                                            </button>
                                        </li>
                                        *}
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('CondPagamento');">
                                                <span> Relatório Vendas Cond Pagamento</span>
                                            </button>
                                        </li>
                                        {*
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioVendas('Entregas');">
                                                    <span> Relatório de Entregas</span>
                                            </button>                                         
                                        </li>
                                        

                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioFaturaSintetico();">
                                                <span> Relatório Vendas Fatura Sintética</span>
                                            </button>                                         
                                        </li>
                                        *}
                                        <li>
                                            <button type="button" class="btn btn-primary btn-xs btnRelatorios" onClick="javascript:relatorioFaturaAnalitico();">
                                                <span> Relatório Vendas Fatura Analítica</span>
                                            </button>                                         
                                        </li>
                                    </ul>
                                </li>
                            <li>
                                <a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod                    type=hidden value="ped">
                            <input name=form                   type=hidden value="pedido_ps">
                            <input name=id                     type=hidden value="">
                            <input name=opcao                  type=hidden value={$opcao}>
                            <input name=letra                  type=hidden value={$letra}>
                            <input name=submenu                type=hidden value={$subMenu}>
                            <input name=fornecedor             type=hidden value="">
                            <input name=pessoa                 type=hidden value={$pessoa}>
                            <input name=codProduto             type=hidden value={$codProduto}>
                            <input name=unidade                type=hidden value={$unidade}>
                            <input name=situacao               type=hidden value={$situacao}>
                            <input name=situacoesAtendimento   type=hidden value={$situacoesAtendimento}>
                            <input name=dataIni                type=hidden value={$dataIni}>
                            <input name=dataFim                type=hidden value={$dataFim}>
                            <input name=tipoRelatorio          type=hidden value={$tipoRelatorio}>
                            <input name=motivoSelected         type=hidden value={$motivoSelected}>
                            <input name=vendedorSelected       type=hidden value={$vendedorSelected}>
                            <input name=condPagamentoSelected  type=hidden value={$condPagamentoSelected}>
                            <input name=situacaoSelected       type=hidden value={$situacaoSelected}>
                            <input name=centroCustoSelected    type=hidden value={$centroCustoSelected}>

                            <div class="form-group col-md-2 col-sm-6 col-xs-6">
                                <label>Num Pedido</label>
                                <input class="form-control" id="numAtendimento" name="numAtendimento"
                                    placeholder="Num Atendimento." value={$numAtendimento}>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label>Situação</label>
                                <select class="select2_multiple form-control" multiple="multiple" id="situacaoCombo"
                                    name="situacaoCombo">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </select>
                            </div>

                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label class="">Periodo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                        value="{$dataIni} - {$dataFim}">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label class="">Cliente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id="nome" name="nome"
                                        placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary"
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>

                        </form>

                    </div>
                    

                    <div class="form-group col-md-12 col-sm-12 col-xs-6"> 
                        <!-- dados adicionaris -->                
                        <!-- start accordion -->
                        
                        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                           <div class="panel">
                               <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                               <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                               </h4>
                               </a>
                               <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                 <div class="panel-body">
                                    <div class="x_panel">
                                        
      
                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                           <label for="centroCusto">Centro de Custo</label>
                                           <SELECT {if ($verSomenteInfoDaLoja == false)}
                                                            enable
                                                        {else}
                                                            disabled
                                                        {/if} 
                                                        class="select2_multiple form-control" multiple="multiple" id="centroCusto" name="centroCusto"> 
                                               {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                                           </SELECT>
                                       </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                           <label for="motivo">Venda Perdida - Motivo</label>
                                           <SELECT class="select2_multiple form-control" multiple="multiple" id="motivo" name="motivo"> 
                                                {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                                           </SELECT>
                                       </div>

                                       

                                       <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                           <label for="vendedor">Vendedor</label>
                                           <SELECT {if ($vertodoslancamentos )}
                                                            enable
                                                        {else}
                                                            disabled
                                                    {/if}
                                                        class="select2_multiple form-control" multiple="multiple"  id="vendedor" name="vendedor"> 
                                               {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                                           </SELECT>
                                       </div> 

                                       <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                           <label for="condPag">Condição de Pagamento</label>
                                           <SELECT class="select2_multiple form-control" multiple="multiple"  id="condPag" name="condPag"> 
                                               {html_options values=$condPag_ids output=$condPag_names selected=$condPag_id}
                                           </SELECT>
                                       </div>


                                        <div class="form-group col-md-6 col-sm-6 col-xs-6" hidden>
                                            <label for="descProduto">Produto</label>
                                            <div class="input-group">
                                                <input class="form-control"  readonly type="text" id="descProduto" name="descProduto" value="{$descProduto}">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn-sm btn-primary" 
                                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&origem=pedido');">
                                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                        </button>
                                                    </span>                                
                                            </div>
                                        </div>
                                        
                                        
                                    {if $lanc != ""}
                                    <div class="col-md-9 col-sm-9 ">
                                        <canvas id="doughnut-chart" width="800" height="450"></canvas>
                                    </div>
                                    {/if} 
                                    </div>
                                 </div>
                               </div>
                           </div> 

                        </div>
                        <!-- end of accordion  -->
                    </div> 


                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->

            <!-- panel tabela dados -->
            <div class="responsive">
                <div class="x_panel">
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <!--table class="table table-striped jambo_table bulk_action"-->
                        <thead>
                            <tr class="headings">
                                <th style="width:45px;text-align:center">Pedido</th>
                                <th style="width: 50px;">Situação</th>
                                <th>Cliente</th>
                                {if isset($lanc) && isset($lanc[0].OBRA_DESC)}
                                    <th>Obra</th>
                                {/if}
                                <th style="width: 50px;">Emissao</th>
                                <th style="width: 60px;">Total</th>
                                <th style="width: 40px;">Manuten&ccedil;&atilde;o</th>

                            </tr>
                        </thead>
                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td style="text-align:center"> {$lanc[i].ID} </td>
                                    <td style="text-align:center"> {$lanc[i].SITUACAODESC} </td>
                                    <td> {$lanc[i].NOME} </td>
                                    {if isset($lanc[0].OBRA_DESC)}
                                        <td> {$lanc[i].OBRA_DESC} </td>
                                    {/if}
                                    <td style="text-align:center"> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                    <td style="text-align:center"> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-xs"
                                            onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span
                                                class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                data-toggle="tooltip" title="Editar"></span></button>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="javascript:submitCancelar('{$lanc[i].ID}');"><span
                                                class="glyphicon glyphicon-remove" aria-hidden="true"
                                                data-toggle="tooltip" title="Cancelar"></span></button>
                                        <button type="button" class="btn btn-info btn-xs" onclick="javascript:printRomaneio('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>
                                        {* <button type="button" class="btn btn-info btn-xs"
                                            onclick="javascript:abrir('index.php?mod=ped&form=rel_pedido_ps&opcao=imprimir&id={$lanc[i].ID}', 'pedidoPS');"><span
                                                class="glyphicon glyphicon-print" aria-hidden="true"
                                                data-toggle="tooltip" title="Imprimir"></span></button> *}
                                        <!--button type="button" class="btn btn-success btn-xs" onclick="javascript:abrir('index.php?mod=cat&form=orcamento_imprime&opcao=imprimir&id={$lanc[i].ID}');"><span class="glyphicon glyphicon glyphicon-briefcase" aria-hidden="true" data-toggle="tooltip" title="Imprimir orçamento"></span></button-->
                                    </td>
                                </tr>
                                <p>
                                {/section}

                        </tbody>
                    </table>
                    <div id="popup" title="IMPRESSÃO"></div>
                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
    </div> <!-- div class="row "-->
</div> <!-- class='' = controla menu user -->

<!-- /Datatables -->


{include file="template/database.inc"}


<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $("#situacaoCombo.select2_multiple").select2({
            placeholder: "Escolha a Situação",
            allowClear: true
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
    document.addEventListener("keypress", function(e) {
        if (e.keyCode === 13) {
            submitLetra();
        }
    });
</script>
<!-- LINKS PARA POPUP DE IMPRESSAO -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<script>
  $(document).ready(function() {
    $("#centroCusto.select2_multiple").select2({
      allowClear: true,
      width: "95%"
    });

  });
</script>

<script>
  $(document).ready(function() {
    $("#condPag.select2_multiple").select2({
      allowClear: true,
      width: "95%"
    });

  });
</script>

<script>
  $(document).ready(function() {
    $("#vendedor.select2_multiple").select2({
      allowClear: true,
      width: "95%"
    });

  });
</script>

<script>
  $(document).ready(function() {
    $("#situacaoCombo.select2_multiple").select2({
      placeholder: "Escolha a Situação",
      allowClear: true,
      width: "95%"
    });

  });
</script>

<script>
  $(document).ready(function() {
    $("#motivo.select2_multiple").select2({
      placeholder: "Escolha o Motivo",
      allowClear: true,
      width: "90%"
    });

  });
</script>