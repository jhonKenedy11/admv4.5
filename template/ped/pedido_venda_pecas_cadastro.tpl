    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_pecas.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="ped">   
            <input name=form                type=hidden value={$form}>   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=nrItem              type=hidden value={$nrItem}>
            <input name=totalPedido         type=hidden value={$totalPedido}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesq                type=hidden value={$pesq}>
            <input name=itensPedido         type=hidden value={$itensPedido}>
            <input name=fornecedor          type=hidden value="">
            <input name=pessoa              type=hidden value={$pessoa}>
            <input name=situacao            type=hidden value={$situacao}>
            <input name=itensQtde           type=hidden value='0'>
            <input name=pesLocalizacao      type=hidden value=''>
            <input name=exibirmotivo        type=hidden value={$exibirmotivo}>
            <input name=itensperdido        type=hidden value={$itensperdido}>
            <input name=dataEntrega         type=hidden value={$dataEntrega}>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
            
                  <div class="x_title">
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="nome" >
                                    <h2>Pedido - 
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Altera&ccedil;&atilde;o 
                                        {/if} 
                                    </h2>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" id="nome" name="nome" placeholder="Pessoa" required
                                           value="{$nome}" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="natOp" >
                                    <h2>Natureza Operação&emsp;</h2>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".bs-impostos-modal">
                                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                    </button>
                                    
                                    <div class="modal fade bs-impostos-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                      <div class="modal-dialog modal-sm">
                                        <div class="modal-content">

                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">Impostos Pedido</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs-8">ICMS   Base: {$baseIcms|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs-8">Valor: {$valorIcms|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs-8">PIS    Base: {$basePis|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs-8">Valor: {$valorPis|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs-8">COFINS Base: {$baseCofins|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-8 col-sm-8 col-xs8">Valor: {$valorCofins|number_format:2:",":"."}<span class="required"></span>
                                              </label>
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                          </div>

                                        </div>
                                      </div>
                                    </div>
                                    
                                    
                                </label>
                                <div class="input-group">
                                    <div class="panel panel-default small">
                                        <SELECT class="form-control" name="natop" > 
                                            {html_options values=$natop_ids output=$natop_names selected=$natop_id}
                                        </SELECT>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-xs-6 text-left">
                                <label><h2> Entrega</h2></label>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Data de Entrega." id="dataEntrega" 
                                        name="dataEntrega" value="{$dataEntrega}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="natOp" >
                                    <h2> Cond Pagamento</h2>
                                </label>
                                <div class="input-group">
                                    <div class="panel panel-default small">
                                    <select name="condPgto" class="form-control">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <label for="centroCusto" >
                                    <h2>Centro de Custo</h2>
                                </label>
                                <div class="input-group">
                                    <div class="panel panel-default small">
                                    <select name="centroCusto" class="form-control">
                                        {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12 small">
                                {if ($situacao == 0) or ($situacao == "")}
                                <label for="natOp" >
                                  
                                    
                                    <button type="button" class="btn btn-primary" onClick="javascript:submitDigitacao('');">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"> </span>&nbsp&nbsp&nbsp&nbspVoltar</button>
                                                                                                    
                                </label>
                                <label for="natOp" >
                                
                                    <button type="button" class="btn btn-success" onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>Concluir</button>
                                                                
                                </label>
                                {/if}   
                            </div>
                        
                        {if $mensagem neq ''}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger small" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       

                        {/if}
                    <div class="clearfix"></div>
                  </div>
                <div class="x_content">
                <div class="row">
                    <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                        <label for="promocoes">Promoções</label>
                        <div class="panel" >
                            <input type="checkbox" class="js-switch" id="promocoes" name="promocoes" {if $promocoes eq 'S'} checked {/if} value="{$promocoes}" /> 
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12 text-left">
                        <label>Produto</label>
                        <div class="form-group">
                            <input class="form-control" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" 
                                name="pesProduto" value="{$pesProduto}" 
                                onChange="javascript:submitBuscar('');">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-xs-6 text-left">
                        <label>Localização</label>
                        <div class="form-group">
                            <input class="form-control" placeholder="Digite a localização do produto para pesquisar." id="pesLocalizacao" 
                                name="pesLocalizacao" value="{$pesLocalizacao}"
                                onChange="javascript:submitBuscar('');">
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

                    
                    <div class="col-lg-2 col-sm-5 col-xs-5 text-left">
                        <label for="desconto">R$ Desconto</label>
                        <div class="form-group">
                            <input class="form-control" placeholder="Valor de Desconto." id="itensQtde" name="desconto" value={$desconto|number_format:2:",":"."} >
                        </div>
                    </div>

                    <div class="col-lg-1 col-sm-1 col-xs-1 text-left">  
                        <br>       
                        <button  type="button" class="btn btn-warning btm-sm"  onClick="javascript:submitBuscar('');">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>Pesquisa
                        </button>         
                    </div>
            </div>
            </div><!-- x_content -->
            </div><!-- x_panel -->

              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Produtos <small>Selecione produtos para o pedido</small></h2>
                    <!--ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up fa-2x topRight"></i></a>
                      </li>
                      <li><button type="button" class="btn btn-small btn-link" onClick="javascript:submitVoltar('');">
                           <a><i class="fa fa-remove fa-2x"></i></a>
                        </button>
                      </li>
                      <li><button type="button" class="btn btn-small btn-link"  onClick="javascript:submitIncluirItem('');">
                           <a><i class="fa fa-shopping-cart fa-2x"></i></a>
                        </button>
                      </li>
                    </ul-->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons" class="table table-striped table-condensed table-responsive small">
                        <thead>
                            <tr>
                                <th style="width: 10px;">Código</th>    
                                <th style="width: 30px;">Descri&ccedil;&atilde;o</th>
                                <th style="width: 5px;">Estoque</th>
                                <th style="width: 20px;">Valor Unit&aacute;rio</th>
                                <th style="width: 20px;">Quant. Venda</th>
                                <th style="width: 20px;">Valor Promo&ccedil;&atilde;o</th>
                                <th style="width: 5px;"></th>                               

                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$lancPesq[i].CODFABRICANTE} </td>
                                    <td> {$lancPesq[i].DESCRICAO} </td>
                                    <td class="price-value"> {$lancPesq[i].QUANTIDADE|number_format:2:",":"."}  </td>
                                    <td align=right> 
                                        <input class="form-control input-sm price-value" value={$lancPesq[i].VENDA|number_format:2:",":"."} >
                                    </td>
                                    <td> 
                                        <input name="{$lancPesq[i].CODIGO}" type=hidden value={$lancPesq[i].CODIGO}>
                                        <input class="form-control input-sm" 
                                               title="Digite a qtde para este item." id="quant" name=quant{$lancPesq[i].CODIGO} >
                                    </td>
                                    <td align=right> 
                                        <input class="form-control input-sm" 
                                               title="Digite a qtde para este item." id="quant" name=promocao{$lancPesq[i].CODIGO} 
                                        value={$lancPesq[i].PROMOCAO|number_format:2:",":"."} >
                                    </td>
                                    {if ($situacao == 0) or ($situacao == "")}
                                    <td> 
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-link"  onClick="javascript:submitIncluirItemQuantPrecoPecas();">
                                                <span class="glyphicon glyphicon-shopping-cart" aria-top="true"></span>
                                            </button>
                                        </span> 
                                    </td>
                                    {/if}
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
                        <thead id="theadMotivo">
                            {if ($exibirmotivo == 'S')}
                            <tr>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                <SELECT ID="motivoselecionado" class="form-control btn-sm" name="motivoselecionado" > 
                                    {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                                </SELECT>
                                </div>                     
                                <button type="button" class="btn btn-warning btn-sm"  onClick="javascript:submitPedidoPerdidoSalvar('');">
                                    <span class="glyphicon glyphicon-save" aria-hidden="true"></span><span>Venda Perdida</span>
                                </button> 
                            </tr>
                            {/if}
                            
                            <tr>
                                {if ($exibirmotivo == 'S')}    
                                <th><input type="checkBox" id="checkboxmotivo" name="checkboxmotivo" onClick="javascript:submitSelecionarTodos(this.checked);"/></th>
                                {/if}
                                <th>Código</th>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Qtde</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Desconto</th>
                                <th>Valor Total</th>
                                {if ($exibirmotivo == 'S')}
                                <th><button type="button" class="btn btn-warning btn-xs"  onClick="javascript:submitExibirMotivo('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></th>
                                {/if}                                
                            </tr>
                        </thead>
                        <tbody id="bodyMotivo">
                            {section name=i loop=$lancItens}
                                {assign var="total" value=$total+1}
                                <tr>{if ($exibirmotivo == 'S')}
                                    <td>
                                    <input type="checkBox"  name="checkedPerdido" id="{$lancItens[i].NRITEM}"/>
                                    </td>
                                    {/if}
                                    <td>{$lancItens[i].ITEMFABRICANTE} </td>
                                    <td> {$lancItens[i].DESCRICAO} </td>
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].DESCONTO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                    {if ($situacao == 0) or ($situacao == "")}
                                    <td> <button type="button" class="btn btn-danger btn-xs" onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td>
                                    {/if}
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

        <script>
      $(function() {
        $('#dataEntrega').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });       
      });
    </script>
                    
                                    
                                    