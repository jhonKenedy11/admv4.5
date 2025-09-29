<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_telhas.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<style>
.swal-modal {
    width: 550px;
}

.swal-title {
    font-size: 22px;
}
.btnRelatorios{
    width: 100% !important;
}
.dropMenuRel{
    right: -190% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
}
.form-control, .x_panel, .select2-selection {
border-radius: 5px !important;
}
.accordion .panel{
border-radius: 5px !important;
}
</style>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div id="msgAlert">
                    <h2>Pedidos Vendas
                        
                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" style="font-size: 14px;" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong></strong>{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" style="font-size: 14px;" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                        </strong>
                    </h2>
                    </div>

                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra('');">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Novo Pedido</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-print"></i></a>
                            <ul class="dropdown-menu dropMenuRel" role="menu">
                            <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#"
                                          onClick="javascript:relBonus();"><span> Relatório Bonus</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('');"><span> Relatório Vendas</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Detalhado');"><span> Relatório Vendas Detalhado</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Item');"><span> Relatório Vendas Item</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Fatura');"><span> Relatório Vendas Fatura Geral</span></button>
                                          
                              </li>
                            <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Fatura', 'A');"><span> Relatório Vendas Fatura Em Aberto</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Vendedor');"><span> Relatório Vendas Vendedor</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Semana');"><span> Relatório Vendas Semana</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Mes');"><span> Relatório Vendas Mes</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('Motivo');"><span> Relatório Vendas Motivo</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('CondPagamento');"><span> Relatório Vendas Cond Pagamento</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal"
                                          onClick="javascript:relatorioVendas('Entregas');"><span> Relatório de Entregas</span></button>                                         
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relatorioVendas('ItemEntrega');"><span> Relatório Vendas Item Entrega</span></button>
                                          
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal"
                                          onClick="javascript:relatorioFaturaSintetico();"><span> Relatório Vendas Fatura Sintética</span></button>                                         
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal"
                                          onClick="javascript:relatorioFaturaAnalitico();"><span> Relatório Vendas Fatura Analítica</span></button>                                         
                              </li>
                              <li>
                                  <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:relEstoqueDisponivelVenda('estoqueDisponivelVenda');"><span> Relatório Estoque Disponivel Venda</span></button>
                                          
                              </li>
                                <li>
                                    <button type="button" class="btn btn-primary btn-xs btnRelatorios" data-toggle="modal" data-target="#modalInutiliza"
                                    onClick="javascript:relatorioVendas('pedNaoEntregue');"><span> Relatório Pedidos não Entregue </span></button>
                                          
                                </li>
                              
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                        {if (($agruparPedidosSituacao == 4) and ($permiteAgruparPedidos == true))}
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitAgruparPedidos();">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Agrupar Pedidos</span>
                            </button>
                        </li>
                        {/if}
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="ped">   
                        <input name=form          type=hidden value={$form}>   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=letra_old     type=hidden value={$letra_old}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=fornecedor    type=hidden value="">
                        <input name=pessoa        type=hidden value={$pessoa}>
                        <input name=codProduto    type=hidden value={$codProduto}>
                        <input name=unidade       type=hidden value={$unidade}>
                        <input name=dataIni       type=hidden value={$dataIni}>
                        <input name=dataFim       type=hidden value={$dataFim}>
                        <input name=agrupar_pedidos type=hidden value={$agrupar_pedidos}>
                        <input name=tipoRelatorio         type=hidden value={$tipoRelatorio}>
                        <input name=motivoSelected        type=hidden value={$motivoSelected}>
                        <input name=vendedorSelected      type=hidden value={$vendedorSelected}>
                        <input name=condPagamentoSelected type=hidden value={$condPagamentoSelected}>
                        <input name=situacaoSelected      type=hidden value={$situacaoSelected}>
                        <input name=centroCustoSelected   type=hidden value={$centroCustoSelected}>
                        <input name=tipoEntregaSelected   type=hidden value={$tipoEntregaSelected}>
                        <input name=situacao              type=hidden value={$situacao}>
                        <input name=cep                   type=hidden id="cep" value={$cep}>
                        <input name=codMunicipio          type=hidden id="codMunicipio" value={$codMunicipio}>

                        <textarea name=emailBody  style="display:none" value={$emailBody}></textarea>
                        
                        
                        <div class="form-group col-md-2 col-sm-3 col-xs-3">
                                <label>C&oacute;d. Pedido</label>
                                <input class="form-control" id="codPedido" name="codPedido" placeholder="Código do Pedido."  value={$codPedido} >
                        </div>
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label>Situação</label>
                              <select class="select2_multiple form-control" multiple="multiple" id="situacaoCombo" name="situacaoCombo">
                                {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                              </select>
                        </div>
      
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label class="">Período</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            <label class="">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="nome" name="nome" placeholder="Conta" value="{$nome}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" 
                                            onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>                                
                            </div>
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


                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
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

                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                            <label for="tipoEntrega">Tipo Entrega</label>
                                            <SELECT class="select2_multiple form-control" multiple="multiple"  id="tipoEntrega" name="tipoEntrega"> 
                                                {html_options values=$tipoEntrega_ids output=$tipoEntrega_names selected=$tipoEntrega_id}
                                            </SELECT>
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

             

                        <div class="modal fade" id="modalVendaPerdida" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input hidden type="text" name="cotacao" id="cotacao" value="{$cotacao}">
                                            <label>Motivo</label>
                                            <div class="panel panel-default small">
                                                <select name="motivoPerdido" id="motivoPerdido"  class="form-control">
                                                    {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                                                </select>
                                            </div>
                                        </div>
                                                
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:salvarMotivoNoPedido(cotacao.value);">Salvar</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>                  
                                </div>
                            </div>
                        </div>
                        
                        <!--MODAL COTACAO EM ABERTO MOSTRA -->
                        <div id="modalCotAberto" class="modal fade" style="background-color: transparent;" role="dialog">
                              <div class="modal-dialog"  style="background-color: transparent;">    
                                <!-- Modal content-->
                                  <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><center><b>Cota&ccedil;&otilde;es em Aberto</b></center></h4>
                                      </div>

                                      <table id="datatable" class="table table-bordered jambo_table">
                                          <thead>
                                              <tr class="">
                                                  <th><center>Pedido</center></th>
                                                  <th><center>Emiss&atilde;o</center></th>
                                                  <th><center>Valor</center></th>
                                                  <th><center>Centro Custo</center></th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                              {section name=i loop=$resultCotacao}
                                                  <tr>
                                                      <td align=center><b> {$resultCotacao[i].PEDIDO} </b></td>
                                                      <td align=center> {$resultCotacao[i].EMISSAO|date_format:"%d/%m/%Y"} </td>                                         
                                                      <td align=center> {$resultCotacao[i].TOTAL|number_format:2:",":"."} </td>
                                                      <td align=center> {$resultCotacao[i].CENTROCUSTO} </td>
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
                        <!--FIM MODAL COTACAO EM ABERTO MOSTRA -->

                        {include file="pedido_venda_telhas_email_modal.tpl"}
                        
                        </form>

                              
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
                                    <th style="width: 55px;">Pedido</th>
                                    <th>Conta</th>
                                    <th>Vendedor</th>
                                    <th style="width: 50px;">Data Emissão</th>
                                    <th style="width: 55px;">Prazo Entrega</th>
                                    <th style="width: 55px;">Entregue</th>
                                    <th style="width: 45px;">Situa&ccedil;&atilde;o</th>
                                    
                                    {if $permiteVisualizarMarkup == true}
                                        <th style="width: 45px;">Markup</th>
                                    {/if}
                                    
                                    <th>Total</th>
                                    <th style="width: 95px;">Manuten&ccedil;&atilde;o</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    {assign var="perc" value={$lanc[i].SITUACAO*20}+20}
                                    <tr {if $lanc[i].COTACAO_CLIENTE > 1 } style="background-color:#FFD9D9;" {/if}>
                                        <td>
                                        {if (($agruparPedidosSituacao == 4) and ($permiteAgruparPedidos == true))}
                                            <input type="checkBox"  name="pedidoChecked" id="{$lanc[i].PEDIDO}"/>
                                        {/if}{$lanc[i].PEDIDO} </td>                                         
                                        <td> {$lanc[i].NOMEREDUZIDO} </td>
                                        <td> {$lanc[i].VENDEDOR} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PRAZOENTREGA} </td>
                                        <td> {$lanc[i].DATAENTREGA|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PADRAO} </td>
                                        
                                        {if $permiteVisualizarMarkup == true}
                                            <td align=left> {$lanc[i].MARKUP|number_format:2:",":"."} </td> 
                                        {/if}  
                                        
                                        <td align=left> {$lanc[i].TOTAL|number_format:2:",":"."} </td>                                        
                                        <td>
                                            {if $sistema eq "PECAS"}
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span
                                                        class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                        data-toggle="tooltip" title="Editar"></span></button>
                                                <button type="button" class="btn btn-warning btn-xs"
                                                    onclick="javascript:submitEstornar('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-refresh" aria-hidden="true"
                                                        data-toggle="tooltip" title="Estornar"></span></button>
                                                <button type="button" class="btn btn-danger btn-xs"
                                                    onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-remove" aria-hidden="true"
                                                        data-toggle="tooltip" title="Excluir"></span></button>
                                                <button type="button" class="btn btn-info btn-xs"
                                                    onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-print" aria-hidden="true"
                                                        data-toggle="tooltip" title="Impressão"></span></button>
                                            {else}
                                                <div class="">
                                                <button {if ($lanc[i].SITUACAO == 10) }
                                                        {if ($permiteAprovarPedidos == true)} enable {else} disabled {/if}
                                                    {elseif ($lanc[i].SITUACAO == 7) } disabled 
                                                    {/if} type="button"
                                                    class="btn btn-primary btn-xs"
                                                    onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span
                                                        class="glyphicon glyphicon-pencil" aria-hidden="true"
                                                        data-toggle="tooltip" title="Editar"></span></button>
                                                {if ($permiteEstornarPedido !== 'false')}
                                                    {if ($lanc[i].SITUACAO == 6) || ($lanc[i].SITUACAO == 13)|| ($lanc[i].SITUACAO == 3)}
                                                        <!-- se for pedido -->
                                                        <button type="button" class="btn btn-warning btn-xs"
                                                            onclick="javascript:submitEstornar('{$lanc[i].ID}');"><span
                                                                class="glyphicon glyphicon-refresh" aria-hidden="true"
                                                                data-toggle="tooltip" title="Estornar"></span></button>
                                                    {/if}

                                                {/if}
                                                {if ($lanc[i].SITUACAO == 5) or ($lanc[i].SITUACAO == 10)}
                                                    <!-- se for cotacao -->
                                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                        data-target="#modalVendaPerdida"
                                                        onclick="vendaPerdida({$lanc[i].ID})"><span span
                                                            class="glyphicon glyphicon-alert" aria-hidden="true"
                                                            data-toggle="tooltip" title="Venda Perdida"></span></button>
                                                {/if}
                                                <button {if ($lanc[i].SITUACAO != 6)} disabled {/if} type="button"
                                                    title="Autoriza NFe" class="btn btn-info btn-xs"
                                                    onclick="javascript:submitNFE('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-font" aria-hidden="true"></span></button>
                                                </div>
                                                <div>
                                                {if $pedDesaprova eq 'true'}
                                                <button {if ($lanc[i].SITUACAO !== '10' or $lanc[i].SITUACAO !== '12')} disable {/if} type="button"
                                                        class="btn btn-warning btn-xs"
                                                        onclick="javascript:submitPedidoDesaprovado('{$lanc[i].ID}');"><span
                                                            class="fa fa-thumbs-down" aria-hidden="true" data-toggle="tooltip"
                                                            title="Desaprovar"></span></button>
                                                {/if}
                                                {if  ($pedImprime eq 'true') or ($lanc[i].SITUACAO == 6) or ($lanc[i].SITUACAO == 9)}
                                                    <button type="button"
                                                        class="btn btn-info btn-xs" {if ($lanc[i].SITUACAO == 0)}
                                                        onclick="javascript:submitExcluir('{$lanc[i].ID}');" {else}
                                                            onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"
                                                        {/if}><span
                                                            class={if ($lanc[i].SITUACAO == 0)} "glyphicon glyphicon-remove"
                                                            {else} "glyphicon glyphicon-print" 
                                                            {/if} aria-hidden="true"
                                                            data-toggle="tooltip"
                                                            {if ($lanc[i].SITUACAO == 0)}title="Excluir">{else}title="Impressão">{/if}</span>
                                                    </button>
                                                {/if}
                                                
                                                <button {if ($lanc[i].SITUACAO != 6) and ($lanc[i].SITUACAO != 9)} disabled
                                                    {/if} type="button" class="btn btn-danger btn-xs"
                                                    onclick="javascript:atualizarDataEntrega('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-ok-circle" aria-hidden="true"
                                                        data-toggle="tooltip" title="Entregue"></span></button>
                                                </div>
                                                <button {if (($lanc[i].SITUACAO == 5) or ($lanc[i].SITUACAO == 6))} 
                                                {else}
                                                    disabled {/if} type="button" class="btn btn-info btn-xs"
                                                    data-toggle="modal" data-backdrop="static" data-target="#modalEmail"
                                                    onclick="javascript:buscaEmailCliente('{$lanc[i].ID}','{$lanc[i].CLIENTE}');"><span
                                                        class="glyphicon glyphicon-envelope" aria-hidden="true"
                                                        title="Enviar Email"></span></button>
                                                <button {if ($lanc[i].COTACAO_CLIENTE) > '1' } {else} disabled {/if}
                                                    type="button" class="btn btn-dark btn-xs"
                                                    onclick="javascript:buscaCotacaoMostra('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-folder-open" aria-hidden="true"
                                                        data-toggle="tooltip" title="Cotação Em Aberto"></span></button>
                                                <button type="button" class="btn btn-dark btn-xs"
                                                    onclick="javascript:abrir(
                                                        'index.php?mod=crm&form=contas_acompanhamento&opcao=imprimir&submenu=cadastrar&idPedido={$lanc[i].ID}&pessoa={$lanc[i].CLIENTE}&pessoaNome={$lanc[i].NOMEREDUZIDO}');">
                                                    <span class="glyphicon glyphicon-text-background" aria-hidden="true"
                                                        data-toggle="tooltip" title="Acompanhamento Cotações"></span>
                                                </button>

                                            {/if}
</td>
                                    </tr>
                                <p>
                            {/section} 

                            </tbody>
                        </table>
                       </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    <!-- /Datatables -->
    
    
    {include file="template/database.inc"}
    
    <script>
        new Chart(document.getElementById("doughnut-chart"), {
            type: 'doughnut',
            data: {
            labels: [{$labels}],
            datasets: [
                {
                    backgroundColor: [{$bckgroundColor}],
                    data: [{$dados}]
                }
            ]
            },
            options: {
                title: {
                    display: true
                }
            }
        });
    </script>

<!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>

    <script>
    function setaDadosModal(valor) {
        document.getElementById('motivo_pedido_id').value = valor;}
    </script>

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

    <script>
      $(document).ready(function() {
        $("#tipoEntrega.select2_multiple").select2({
          allowClear: true,
          width: "95%"
        });

      });
    </script>

    <!-- daterangepicker -->
    <script type="text/javascript">
        $('input[name="dataConsulta"]').daterangepicker(
        {
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
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

    <script>
    function vendaPerdida(cotacao){
        var cotacao = cotacao;
        $("#cotacao").val(cotacao);    
    }
    </script>
<!-- /daterangepicker -->