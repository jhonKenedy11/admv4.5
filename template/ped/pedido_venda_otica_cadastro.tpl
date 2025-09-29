    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_compras.js"> </script>
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
            <input name=emissao             type=hidden value={$emissao}>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nome" >
                                    <h2>Pedido - 
                                        {if $subMenu eq "cadastrar"}
                                            Cadastro
                                        {else}
                                            Altera&ccedil;&atilde;o 
                                        {/if} 
                                    </h2>
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
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary" onClick="javascript:submitDigitacao('');">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Voltar
                            </button>
                        </li>
                        <li>
                          <!-- modals -->
                          <!-- Large modal -->
                          <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg">Receita</button>

                          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">

                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel">Dados Receita</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                          <div class="col-md-4"></div>
                                          <div class="col-md-4"><b>O.D.</div>
                                          <div class="col-md-4">O.E.</b></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">ESF&Eacute;RICO</div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="odEsferico" name="odEsferico"
                                                    title="Olho direito esférico." step="0.10" value="{$odEsferico}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="oeEsferico" name="oeEsferico"
                                                    title="Olho esquerdo esférico." step="0.10" value="{$oeEsferico}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">CILINDRICO</div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="odCilindrico" name="odCilindrico"
                                                    title="Olho direito cilindrico." step="0.10" value="{$odCilindrico}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="oeCilindrico" name="oeCilindrico"
                                                    title="Olho esquerdo cilindrico." step="0.10" value="{$oeCilindrico}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">EIXO</div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="odEixo" name="odEixo"
                                                    title="Olho direito eixo." step="0.10" value="{$odEixo}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="oeEixo" name="oeEixo"
                                                    title="Olho esquerdo eixo." step="0.10" value="{$oeEixo}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">A.D.</div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="odAd" name="odAd"
                                                    title="Olho direito esférico." step="0.10" value="{$odAd}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="oeAd" name="oeAd"
                                                    title="Olho esquerdo esférico." step="0.10" value="{$oeAd}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">M&eacute;dico</div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="medico" name="medico"
                                                    title="Digite o nome e CRM do Médico." step="0.10" value="{$medico}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">Data Entrega:</div>
                                            <div class="col-md-4">
                                                <input type="date" class="form-control" id="dataEntrega" name="dataEntrega"
                                                    title="Data prevista da entrega." value="{$dataEntrega}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">Situa&ccedil;&atilde;o</div>
                                            <div class="col-md-4">
                                                  <select class="form-control"  id="situacao" name="situacao">
                                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                                  </select>
                                            </div>
                                        </div>
                                      <div class="form-group">
                                          <label for="message-text" class="form-control-label">Observa&ccedil;&atilde;o Pedido:</label>
                                          <textarea class="form-control" id="obs" name="obs" rows="4" >{$obs}</textarea>
                                      </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>

                              </div>
                            </div>
                          </div>

                          <!-- /modals -->

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
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Cliente" required
                                   value="{$nome}">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" 
                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </button>
                            </span>                                
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="panel panel-default">
                            <select name="condPgto" class="form-control">
                                {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                            </select>
                        </div>
                    </div>
                </div>    
                <div class="row">
                <div class="col-lg-1 col-sm-1 col-xs-1 text-left">
                    <label for="promocoes">Promoções</label>
                    <div class="panel" >
                        <input type="checkbox" class="js-switch" id="promocoes" name="promocoes" {if $promocoes eq 'S'} checked {/if} value="{$promocoes}" /> 
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12 col-xs-12 text-left">
                    <label>Produto</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" 
                               name="pesProduto" value="{$pesProduto}"
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

                <div class="col-lg-1 col-sm-2 col-xs-2 text-left">
                    <label for="itensQtde">Quantidade</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite a qtde a incluir." id="itensQtde" name="itensQtde" value={$itensQtde} >
                    </div>
                </div>
                <div class="col-lg-1 col-sm-2 col-xs-2 text-left">
                    <label for="desconto">Desconto</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Valor de Desconto." id="itensQtde" name="desconto" value={$desconto} >
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
                    <h2>Produtos <small>Selecione produtos para o pedido</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
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
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons" class="table table-striped table-condensed table-responsive small">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Promo&ccedil;&atilde;o</th>
                                <!--th>Ref.</th-->

                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="checkbox"  name="itemCheckbox" id="{$lancPesq[i].CODIGO}" value="{$lancPesq[i].CODIGO}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-small btn-link"  onClick="javascript:submitIncluirItem('');">
                                                        <a><i class="fa fa-shopping-cart fa-1x"></i></a>
                                                </button>
                                            </span>                                
                                        </div>
                                    </td>
                                    <td> {$lancPesq[i].DESCRICAO} </td>
                                    <td class="price-value"> {$lancPesq[i].VENDA|number_format:2:",":"."} </td>
                                    <td align=right> {$lancPesq[i].PROMOCAO|number_format:2:",":"."} </td>
                                    <!--td> {$lancPesq[i].CODIGO} </td-->
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
                                <th>Descri&ccedil;&atilde;oo</th>
                                <th>Qtde</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Desconto</th>
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
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:0:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].DESCONTO|number_format:2:",":"."} </td>
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
                    
                                    
                                    