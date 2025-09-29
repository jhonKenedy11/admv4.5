        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">
        {section name=i loop=$lancItens}
            {assign var="numItem" value=$numItem+1}
        {/section} 

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="pdv">   
            <input name=form                type=hidden value="cupom">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=nrItem              type=hidden value={$nrItem}>
            <input name=totalPedido         type=hidden value={$totalPedido}>
            <input name=numItem             type=hidden value={$numItem}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesq                type=hidden value={$pesq}>
            <input name=itensPedido         type=hidden value={$itensPedido}>
            <input name=fornecedor          type=hidden value="">
            <input name=cliente             type=hidden value={$cliente}>
            <input name=obs                 type=hidden value={$obs}>
            
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="form-group">
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input class="form-control" placeholder="Selecione o Cliente para o Cupom." 
                                   id="nomeCliente" name="nomeCliente" onChange="javascript:submitCliente({$id});" value={$nomeCliente}>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <input class="form-control" placeholder="Digite o CPF para o Cupom." 
                                   id="cpf" name="cpf"  value={$cpf}>
                                  <!-- id="cpf" name="cpf" onChange="javascript:validaCPF(document.lancamento.cpf.value);" value={$cpf}-->
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12 col-md-offset-1 alignright">
                              <!--button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-money"></i>Pagamento</button>
                              <button type="button" class="btn btn-danger btn-sm" onClick="javascript:submitDigitacao('');"><i class="glyphicon glyphicon-remove"></i>Cancelar</button-->
                                <!--a class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                    <i class="fa fa-money"></i> Encerrar
                                </a-->
                                <a class="btn btn-success" onClick="javascript:submitEncerra({$id});">
                                    <i class="fa fa-money"></i> Encerrar
                                </a >
                                <a class="user-profile dropdown-toggle alignright" data-toggle="dropdown">
                                    <i class="fa fa-chevron-circle-down fa-2x"></i>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                      <li><a href="#">Settings 1</a>
                                      </li>
                                      <li><a href="#">Settings 2</a>
                                      </li>
                                    </ul>
                                </a>    
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <input class="form-control" placeholder="Digite o nome do produto para pesquisar." 
                                   id="pesProduto" name="pesProduto" onChange="javascript:submitBuscar('');" value={$pesProduto}>
                        </div>
                        <div class="col-md-1 col-sm-3 col-xs-3">
                                <input type='number' step='0.100' class="form-control" placeholder="Quantidade" id="itensQtde" name="itensQtde" 
                                       value={$itensQtde} >
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-3">
                                <input type='number' step='5' class="form-control  has-feedback-left" placeholder="Valor" id="valor" name="valor" 
                                       value={$valor} >
                                <span class="form-control-feedback left" aria-hidden="true"><b>R$</b></span>
                        </div>
                        <div class="col-md-1 col-sm-6 col-xs-6">
                                <SELECT class="form-control" name="grupo" onChange="javascript:submitBuscar('');"> 
                                    {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                </SELECT>
                        </div>
                    </div>  
                    <div class="form-group">
                        {if $mensagem neq ''}
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-danger small" role="alert">{$mensagem}</div>
                            </div>       
                        {/if}
                    </div>  

            </div><!-- x_panel -->

              <div class="col-md-5 col-sm-5 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>PRODUTOS</h2>
                    <ul class="nav navbar-right panel_toolbox">
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons" class="table table-striped table-condensed table-responsive">
                        <thead>
                            <tr>
                                <th>Descri&ccedil;&atilde;o</th>
                                <th>Valor Unit&aacute;rio</th>
                                <th>Valor Promo&ccedil;&atilde;o</th>
                                <th>Uni</th>
                                <th></th>
                                <!--th>Ref.</th-->

                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <!--td align="center"> <input type="checkbox"  name="itemCheckbox" id="{$lancPesq[i].CODIGO}" value="{$lancPesq[i].CODIGO}"></td-->
                                    <td> {$lancPesq[i].DESCRICAO} </td>
                                    <td class="price-value"> {$lancPesq[i].VENDA|number_format:2:",":"."} </td>
                                    <td align=right> {$lancPesq[i].PROMOCAO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancPesq[i].UNIDADE} </td>
                                    <td> <button type="button" class="btn btn-warning btn-xs" 
                                        onClick="javascript:submitIncluirItem({$lancPesq[i].CODIGO});">
                                        <i class="fa fa-forward fa-chevron-right" aria-hidden="true"></i></button> </td>
                                </tr>
                            <p>
                            {/section} 

                        </tbody>
                    </table>

                  </div>
                </div>
              </div>

              <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-xs-3 ">
                            <h3>CUPOM</h3>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 col-md-offset-1 bg-blue">
                            Itens: {$numItem}
                        </div>
                        <div class="col-md-4 col-sm-3 col-xs-3 col-md-offset-3 bg-blue">
                            <label class="nav panel_toolbox alignleft" >TOTAL: </label>
                            <ul class="nav panel_toolbox alignright">
                                <li><h3>{$totalPedido|number_format:2:",":"."}</h3>
                              </li>
                            </ul>
                        </div>
                    </div>        
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons2" class="table table-striped table-condensed table-responsive">
                        <thead>
                            <tr>
                                <th>Ref.</th>
                                <th>Descrição</th>
                                <th>Qtde</th>
                                <th>UN</th>
                                <th>Valor Unitário</th>
                                <th>Valor Total</th>
                                <th><i class="glyphicon glyphicon-trash"></i></th>

                            </tr>
                        </thead>
                        <tbody>
                            {section name=i loop=$lancItens}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td> {$lancItens[i].ITEMESTOQUE} </td>
                                    <td> {$lancItens[i].DESCRICAO} </td>
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:3:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNIDADE} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                    <td> <button type="button" class="btn btn-danger btn-xs" onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button> </td>
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
                        
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Recebimento e Impress&atilde;o</h4>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="nomeCliente">Nome Cliente</label>
                                <div class="panel panel-default">
                                    <input class="form-control" disabled id="nomeCliente" name="nomeClienteModal" value={$nomeCliente}>
                                </div>
                            </div>
                        </div>          
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                                <div class="panel panel-default">
                                    <select name="condPgto" class="form-control" onchange="javascript:criarTabela()">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                </div>
                            </div>
                        </div>          
                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <label for="qtdItem" >Itens </label>
                                <input class="form-control" id="qtdItem" name="qtdItem"  value={$qtdItem}>
                            </div>
                            <div class="col-md-3 col-sm-2 col-xs-2">
                                <label for="taxa" >TOTAL a Pagar</label>
                                <input class="form-control text-success" id="totalpagar" name="totalpagar"  value={$totalPedido|number_format:2:",":"."}>
                            </div>
                            <div class="col-md-3 col-sm-2 col-xs-2">
                                <label for="taxa" >TOTAL a Pago</label>
                                <input class="form-control" id="totalpago" name="totalpago"  value={$totalPago|number_format:2:",":"."}>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <label for="troco" >Troco</label>
                                <input class="form-control text-danger" id="troco" name="troco"  value={$troco}>
                            </div>
                        </div>          
                            <table id="datatable-buttons-1" class="table table-bordered jambo_table small">
                                <thead>
                                    <tr style="background: gray; color: white;">
                                        <th>Parcela</th>
                                        <th>Data Vencimento</th>
                                        <th>Valor</th>
                                        <th>Tipo Documento</th>
                                        <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$fin}
                                        {assign var="total" value=$total+1}
                                        <tr>
                                            <td> {$fin[i].PARCELA} </td>
                                            <td> 
                                                <input class="form-control" type="text" id="venc" name="venc{$fin[i].PARCELA}" value={$fin[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                            </td>
                                            <td> 
                                                <input class="form-control" type="text" id="valor" name="valor{$fin[i].PARCELA}" value={$fin[i].VALOR|number_format:2:",":"."}>

                                            </td>
                                            <td>
                                                <select id="idTipoDoc" name="tipo{$fin[i].PARCELA}" class="form-control">
                                                    {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                </select>
                                            </td>
                                            <td> 
                                                <select id="idSitucao" name="situacao{$fin[i].PARCELA}" class="form-control">
                                                    {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                </select>
                                            </td>
                                        </tr>
                                    <p>
                                {/section} 

                                </tbody>
                            </table>
                            
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#myModal1">Recaldulo</button>
                    <button type="button" class="btn btn-primary">Salvar mudanças</button>
                  </div>
                </div>
              </div>
            </div>                        

            <!-- Modal -->
            <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Título do modal</h4>
                  </div>
                  <div class="modal-body">

                                   
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#myModal">Recaldulo</button>
                    <button type="button" class="btn btn-primary">Salvar mudanças</button>
                  </div>
                </div>
              </div>
            </div>                        
            
                    </form>

      </div>

    {include file="template/form.inc"}  
                    
                                    
                                    