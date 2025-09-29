    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="ped">   
            <input name=form                type=hidden value="pedido_venda_online">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=nrItem              type=hidden value={$nrItem}>
            <input name=totalPedido         type=hidden value={$totalPedido}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesq                type=hidden value={$pesq}>
            <input name=itensPedido         type=hidden value={$itensPedido}>
            <input name=fornecedor          type=hidden value="">
            <input name=pesLocalizacao      type=hidden value="">
            <input name=pessoa              type=hidden value={$pessoa}>
            <input name=situacao            type=hidden value={$situacao}>
            <input name=itensQtde            type=hidden value='0'>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                    <h2>Pedido - 
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Altera&ccedil;&atilde;o 
                                        {/if} 
                                    </h2>
                            </div>
                                <div class="col-md-2 col-sm-5 col-xs-5">
                                    <label>Entrega</label>
                                    <div class="panel panel-default">
                                        <SELECT class="form-control" name="tipoEntrega"> 
                                            {html_options values=$tipoEntrega_ids output=$tipoEntrega_names selected=$tipoEntrega_id}
                                        </SELECT>
                                    </div>
                                </div>
                                <!--div class="input-group">
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Conta" required
                                           value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div-->
                        
                        {if $mensagem neq ''}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger small" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       

                        {/if}
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary" onClick="javascript:submitDigitacao('');">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Voltar
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-success" onClick="javascript:submitConfirmar('');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Concluir 
                            </button>
                        </li>
                        <!--li><button type="button" class="btn btn-info" onClick="javascript:submitVoltar('');">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir
                            </button>
                        </li-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <div class="x_content">
                <div class="row">
                <!--div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                    <label for="promocoes">Promoções</label>
                    <div class="panel" >
                        <input type="checkbox" class="js-switch" id="promocoes" name="promocoes" {if $promocoes eq 'S'} checked {/if} value="{$promocoes}" /> 
                    </div>
                </div-->
                <div class="col-md-2 col-sm-6 col-xs-6">
                    <label for="promocoes">Promoções</label>
                    <select class="form-control" name="promocoes">
                        {html_options values=$promocoes_ids selected=$promocoes_id output=$promocoes_names}
                    </select>
                </div>

                <div class="col-lg-4 col-sm-12 col-xs-12 text-left">
                    <label>Produto</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" 
                               name="pesProduto" value="{$pesProduto}" onChange="javascript:submitBuscar('');">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-5 col-xs-5 text-left">
                    <label>Grupo</label>
                    <div class="panel panel-default">
                        <SELECT class="form-control" name="grupo"> 
                            {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                        </SELECT>
                    </div>
                </div>

                <div class="col-lg-1 col-sm-2 col-xs-2 text-left">
                    <label for="itensQtde">Quantidade</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite a qtde a incluir." id="itensQtde" name="itensQtde" value={$itensQtde} >
                    </div>
                </div>
                <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                    <li><button type="button" class="btn btn-warning"  onClick="javascript:submitBuscar('');">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa Produto</span>
                        </button>
                    </li>
                </div>
            </div>
            </div><!-- x_content -->
            </div><!-- x_panel -->

              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Produtos <small>Digite a quantidade no produto para incluir no pedido</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up fa-2x"></i></a>
                      </li>
                      <li><button type="button" class="btn btn-small btn-link" onClick="javascript:submitVoltar('');">
                           <a><i class="fa fa-remove fa-2x"></i></a>
                        </button>
                      </li>
                      <li>
                        {if $kit eq "2"}
                            <button type="button" class="btn btn-small btn-link"  onClick="javascript:submitIncluirItemQuant();">
                            <a><i class="fa fa-shopping-cart fa-2x"></i></a>
                            </button>
                        {/if}    
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons" class="table table-striped table-condensed table-responsive small">
                        <thead>
                            <tr>
                                <th>Quant</th>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Promo&ccedil;&atilde;o</th>
                                <th>Quant.</th>
                                <!--th>Ref.</th-->

                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                {if $lancPesq[i].PROMOCAO eq 0}
                                    <tr>
                                {else}
                                    {if $lancPesq[i].TIPOPROMOCAO eq 0}
                                        <tr style="color:red;">
                                    {else}
                                        <tr style="color:blue;">
                                    {/if}        
                                {/if} 
                                
                                    <td  style="width: 125px;"class="input-group"> 
                                        <input name="{$lancPesq[i].CODIGO}" type=hidden value={$lancPesq[i].CODIGO}>
                                        {if $kit eq "2"}
                                            <input class="form-control input-sm" min="1" step="1" 
                                                id="quant" name=quant{$lancPesq[i].CODIGO} readonly value={$lancPesq[i].QUANTLIMITE} >
                                        {else}
                                            <input class="form-control input-sm" min="1" step="1" type="number"
                                               title="Digite a qtde para este item." id="quant" name=quant{$lancPesq[i].CODIGO} >
                                        {/if}
                                        <!--input type="checkbox"  name="itemCheckbox" id="{$lancPesq[i].CODIGO}" value="{$lancPesq[i].CODIGO}"-->
                                        <span class="input-group-btn">
                                        {if $kit neq "2"}
                                            <button type="button" class="btn btn-small btn-link btn-mini"  onClick="javascript:submitIncluirItemQuant();">
                                                <span class="glyphicon glyphicon-shopping-cart" aria-top="true"></span>
                                            </button>
                                        {/if}    
                                        </span>                                
                                    </td>
                                    <td> {$lancPesq[i].DESCRICAO} </td>
                                    <td class="price-value"> {$lancPesq[i].VENDA|number_format:2:",":"."} </td>
                                    <td class="price-value"> {$lancPesq[i].PROMOCAO|number_format:2:",":"."} </td>
                                    <td class="price-value"> {$lancPesq[i].QUANTIDADE|number_format:2:",":"."} </td>
                                </tr>
                            <p>
                            {/section} 

                        </tbody>
                    </table>

                  </div>
                </div>
              </div>


              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Compras <small>Produtos carrinho</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><h2>TOTAL: {$totalPedido|number_format:2:",":"."}</h2>
                      </li>
                    </ul>
                    <!--ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up fa-2x"></i></a>
                      </li>
                    </ul-->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive small">
                        <thead>
                            <tr>
                                <th>Ref.</th>
                                <th>Descrição</th>
                                <th>Qtde</th>
                                <th>Valor Unitário</th>
                                <th>Valor Total</th>
                                <th>Cancelar</th>

                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$lancItens}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$lancItens[i].ITEMESTOQUE} </td>
                                    <td> {$lancItens[i].DESCRICAO} </td>
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:0:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                    <td> <button type="button" class="btn btn-danger btn-xs" onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td>
                                </tr>
                            <p>
                        {/section} 

                        </tbody>
                    </table>

                  </div>
                </div>
              </div>

                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  
                    
                                    
                                    