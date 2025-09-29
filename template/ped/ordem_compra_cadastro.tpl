    <script type="text/javascript" src="{$pathJs}/ped/s_ordem_compra.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="ped">   
            <input name=form                type=hidden value="ordem_compra">   
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
            <input name=itensQtde            type=hidden value='0'>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label for="nome" >
                                    <h2>Ordem Compra {$id} - 
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Altera&ccedil;&atilde;o 
                                        {/if} 
                                    </h2>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" id="nome" name="nome" placeholder="Conta" required
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
                                    <h2> Cond Pagamento</h2>
                                </label>
                                <div class="input-group">
                                    <div class="panel panel-default small">
                                    <select name="condPgto" class="js-example-basic-single form-control" id="condPgto">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1 col-sm-12 col-xs-12 small">
                                <label>
                                    {if ($situacao == 0) or ($situacao == "")}
                                    <button type="button" class="btn btn-primary btn-sm" onClick="javascript:submitDigitacao('');">
                                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Voltar
                                        </button>
                                    
                                    {/if}        
                            
                                </label>
                            </div>

                            <div class="col-md-1 col-sm-12 col-xs-12 small">
                                <label>
                                    {if ($situacao == 0) or ($situacao == "")}
                                    <button type="button" class="btn btn-success btn-sm" onClick="javascript:submitConfirmarSmart('');">
                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Concluir 
                                        </button>
                                    {/if}        
                            
                                </label>
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

                <div class="col-lg-5 col-sm-12 col-xs-12 text-left">
                    <label>Produto</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" 
                               name="pesProduto" value="{$pesProduto}" 
                               onChange="javascript:submitSearch();">
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

                <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                    <label style="color:transparent" >espaço</label>
                    <li style="list-style-type:none;">
                        <button type="button" class="btn btn-warning btn-sm"  onClick="javascript:submitBuscar('');">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa</span>
                        </button>
                    </li>
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
                                <th>Código</th>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Quantidade</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$lancPesq[i].CODFABRICANTE} </td>
                                    <td> {$lancPesq[i].DESCRICAO} </td>
                                    <td> 
                                        <input name="{$lancPesq[i].CODIGO}" type=hidden value={$lancPesq[i].CODIGO}>
                                        <input class="form-control input-sm money" 
                                               title="Digite a qtde para este item." id="quant" name=quant{$lancPesq[i].CODIGO} 
                                               value={0|number_format:2:",":"."} >
                                    </td>
                                    <td align=right> 
                                        <input class="form-control input-sm money" 
                                               title="Digite o valor unitário para este item." id="unitario" name=unitario{$lancPesq[i].CODIGO} 
                                        value={0|number_format:2:",":"."} >
                                    </td>
                                    <td> 
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-link"  onClick="javascript:submitIncluirItemQuantPreco();">
                                                <span class="glyphicon glyphicon-shopping-cart" aria-top="true"></span>
                                            </button>
                                        </span> 
                                    </td>
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
                                <th>Código</th>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Qtde</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Total</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$lancItens}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$lancItens[i].ITEMESTOQUE} </td>
                                    <td> {$lancItens[i].DESCRICAO} </td>
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
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
                    
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#condPgto.js-example-basic-single").select2({
        });
      });
    </script>   

     <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true,
         allowZero: true
        });        
     });
    </script>                               