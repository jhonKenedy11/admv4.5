    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_novo.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
            <h3>
                {if $situacao eq 6} Pedidos Vendas {else} Cotação Vendas {/if}
            </h3>
          </div>
        </div>

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="ped">   
            <input name=form                type=hidden value={$form}>   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=nrItem              type=hidden value={$nrItem}>
            <input name=totalPedido         type=hidden value={$totalPedido}>
            <input name=letra               type=hidden value={$letra}>
            <input name=letra_old           type=hidden value={$letra_old}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesq                type=hidden value="{$pesq}">
            <input name=itensPedido         type=hidden value={$itensPedido}>
            <input name=itensPedidoCC       type=hidden value={$itensPedidoCC}>
            <input name=fornecedor          type=hidden value="">
            <input name=pessoa              type=hidden value={$pessoa}>
            <input name=cep                 type=hidden value={$cep}>
            <input id="codMunicipio"        name=codMunicipio type=hidden value={$codMunicipio}>
            <input name=situacao            type=hidden value={$situacao}>
            <input name=itensQtde           type=hidden value='0'>
            <input name=pesLocalizacao      type=hidden value=''>
            <input name=exibirmotivo        type=hidden value={$exibirmotivo}>
            <input name=itensperdido        type=hidden value={$itensperdido}>
            <input name=id_prod_preco_min   type=hidden value={$id_prod_preco_min}>
            <input name=desc                type=hidden value={$desc}>
            <input name=codProduto          type=hidden value={$codProduto}>
            <input name=unidade             type=hidden value={$unidade}>
            <input name=descProduto         type=hidden value={$descProduto}>
            <input name=usrAprovacao        type=hidden value={$usrAprovacao}>
            <input name=usraprovacaoconf        type=hidden value={$usraprovacaoconf}>
            <input name=perDesconto         type=hidden value={$perDesconto}>

            <input name=totalItem         type=hidden value={$totalItem}>
            <!--Total original ao alterar o pedido  -->
            <input name=totalOriginal         type=hidden value={$totalOriginal}>
            <input name=pesquisa_prod_vazio   type=hidden value={$pesquisa_prod_vazio}>
            <input name=codFabricante      type=hidden value=''>
            <input name=codProdutoNota      type=hidden value=''>
            <input name=uniProduto      type=hidden value=''>
            <input name=vlrUnitarioPecas      type=hidden value=''>
            <input name=quantidadePecas      type=hidden value=''>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                            {if ($situacao == 0) }                                        
                                Cadastro
                            {/if} 
                        {else}
                            {if $situacao eq 6} Pedido {else} Cotação {/if} {$id}
                        {/if} 
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">                        
                        
                        {if $sistema eq "PECAS"} 
                            {if ($situacao == 5) or ($situacao == "")}      
                                       
                            <li><button
                                type="button" class="btn btn-warning btn-sm"  onClick="javascript:atualizarInfo();">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Recalcular Totais</span></button>
                            </li>   
                            <li><button 
                                    type="button" class="btn btn-primary btn-sm"  onClick="javascript:submitConfirmarCotacao('');">
                                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Cotação</span></button>
                            </li>
                                                   
                            <li><button type="button" class="btn btn-primary btn-sm" onClick="javascript:submitConfirmarSmart('{$pathCliente}');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Pedido</button>
                            </li>
                            {/if}
                            <li><button type="button" class="btn btn-success btn-sm"  onClick="javascript:submitDigitacao('');">
                                    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span> Voltar</span></button>
                            </li>
                        {else}
                        {if $esconderbtn eq "N"}
                            <li>
                                <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#modalCC" onclick="javascript:setaDadosPedido()"><span span  aria-hidden="true" data-toggle="tooltip">C.C.</span></button>
                            </li> 


                            <li><button {if $situacao neq 10} style="display:none" {/if}
                            type="button" class="btn btn-primary btn-sm"  onClick="javascript:submitAprovado('');">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span> Aprovado</span></button>
                            </li>
                            <li><button {if $situacao neq 10} style="display:none" {/if}
                                type="button" class="btn btn-danger btn-sm"  
                                onClick="javascript:submitDesaprovado();">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Desaprovado</span></button>
                            </li>

                            {if $validarDescontoGeral == 'S' }
                                <li>
                                    <button  type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAutoriza">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Cotação</span></button>
                                </li>                              
                                <li>
                                    <button  type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#AutorizarPedido">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Pedido</span></button>
                                </li>                              
                           {else}
                                <li><button 
                                    {if $situacao eq 10}
                                        style=" display:none" 
                                    {else if (($situacao == 5) and ($controlarStatusTela))}
                                        style=" display:none"
                                    {else if $situacao eq 12}
                                        style=" display:none"
                                    {/if}
                                    type="button" class="btn btn-primary btn-sm"  onClick="javascript:submitConfirmarSmart('');">
                                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Cotação</span></button>
                                </li>
                                <li><button 
                                    {if $situacao eq 10}
                                        style=" display:none" 
                                    {else if (($situacao < 5) and ($controlarStatusTela))}
                                        style=" display:none"
                                    {/if}
                                    type="button" class="btn btn-success btn-sm"  
                                    onClick="javascript:submitCadastrarPedido();">
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span><span> Pedido teste</span></button>
                                </li>
                            {/if}  
                            <li><button
                                type="button" class="btn btn-warning btn-sm"  onClick="javascript:atualizarInfo();">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Recalcular Totais</span></button>
                            </li>
                        {/if}
                        <!-- BOTAO PARA CONFIRMAR ALTERAÇÃO -->
                        <li><button {if (($situacao != 6) and (!$validaAlterarPedido)) } style="display:none" {/if} type="button" class="btn btn-primary btn-sm"  
                                    onClick="javascript:submitAlterarPedido();">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>

                        <li><button type="button" class="btn btn-primary btn-sm"  onClick="javascript:submitDigitacao('');">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span> Salvar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger btn-sm"  onClick="javascript:submitDigitacao('');">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                        {if $consulta eq "C"}
                          <li><button type="button" class="btn btn-warning btn-sm"  onClick="javascript:submitLetra();">
                                  <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                              </button>
                          </li>
                        {/if}
                        {if (($situacao == 6))}
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li>
                                <button  type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalCentroCustoEntrega"><span>Centro de Custo Entrega</span></button>
                              </li>
                              <li>
                                <button  type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalPrazoEntrega"><span>Alterar Prazo de Entrega</span></button>
                              </li>
                              {if ($permiteAlterarVendedor == "S") } 
                              <li>
                                <button  type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalVendedor"><span>Alterar Vendedor</span></button>
                              </li>
                              {/if}
                              <li>
                                <button  type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalDataEmissao"><span>Alterar Data Emissão</span></button>
                              </li>
                            </ul>                            
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                        {/if}
                      {/if}
                       <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="javascript:abrir('index.php?mod=ped&form=rel_dados_pedido&opcao=imprimir&parm={$id}');">
                                    <button id="btnDadosPed" type="button" class="btn btn-dark btn-xs"><span>Dados Pedido</span></button>
                                    
                                  </a>
                              </li>
                              {if $situacao eq 6 or $situacao eq 9  } 
                                <li>
                                    <button id="btnParcFin" type="button" class="btn btn-dark btn-xs" data-toggle="modal" data-target="#modalParcelasLanc"><span>Parcelas Financeiro</span></button>
                                </li>
                              {/if}

                               <li>
                                    <button id="btnDuplicaPed" type="button" class="btn btn-dark btn-xs" onClick="javascript:duplicaPedido('{$id}');"><span>Duplicar Pedido</span></button>
                               </li>
                            </ul>
                            
                        </li>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                      </ul>
                    <div class="clearfix"></div>
                  </div>


                {include file="pedido_venda_telhas_cadastro_cc.tpl"}
                {include file="pedido_venda_telhas_parcelas_modal.tpl"}


                <div class="modal fade" id="modalCentroCustoEntrega" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">                      
                        <div class="modal-body">
                            <div class="form-group">
                              <div class="panel panel-default small">
                                    <select id="centroCustoEntrega" name="centroCustoEntrega" class="form-control">
                                        {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                    </select>
                              </div>                              
                              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="atualizarCCEntrega({$id});">Enviar</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>                  
                    </div>
                    </div>
                </div>  

                <div class="modal fade" id="modalPrazoEntrega" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">                      
                        <div class="modal-body">
                            <div class="form-group">
                              <input type="date" name="prazoEntregaNew" id="prazoEntregaNew">
                              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="atualizarPrazoEntrega({$id},prazoEntregaNew.value);">Enviar</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>                  
                    </div>
                    </div>
                </div> 
        
                <div class="modal fade" id="modalVendedor" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <label for="usrfaturaalterar">Vendedor</label>
                                <div class="panel panel-default small">
                                    <select name="usrfaturaalterar" id="usrfaturaalterar" class="form-control">
                                        {html_options values=$usrfatura_ids selected=$usrfatura output=$usrfatura_names}
                                    </select>
                                </div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="atualizarVendedor({$id});">Enviar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                        </div>                  
                    </div>
                    </div>
                </div> 
                

                <div class="modal fade" id="modalAutoriza" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label for="usrfaturaalterar">Vendedor</label>
                                        <select name="usrautorizaconf" id="usrautorizaconf" class="form-control">
                                            {html_options values=$usrautoriza_ids selected=$usrautoriza output=$usrautoriza_names}
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label for="password">Senha</label>
                                        <input type="password" class="form-control input-sm" id="passwordconf" name="passwordconf" 
                                            placeholder="digite senha" value="{$passwordconf}">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <button style="margin-top: 19px;" type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitConfirmarSmartKey('{$usrautoriza}',{$password});">Enviar</button>
                                        <button style="margin-top: 19px;"type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                    </div>
                </div> 
                
                <div class="modal fade" id="AutorizarPedido" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label for="usrfaturaalterar">Vendedor</label>
                                        <select name="usrautorizaconf" id="usrautorizaconf" class="form-control">
                                            {html_options values=$usrautoriza_ids selected=$usrautoriza output=$usrautoriza_names}
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label for="password">Senha</label>
                                        <input type="password" class="form-control input-sm" id="passwordconf" name="passwordconf" 
                                            placeholder="digite senha" value="{$passwordconf}">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <button style="margin-top: 19px;" type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:submitConfirmarSmartKeyPedido('{$usrautoriza}',{$password});">Enviar</button>
                                        <button style="margin-top: 19px;"type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDataEmissao" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">                      
                        <div class="modal-body">
                            <div class="form-group">
                              <input type="date" name="dataEmissaoNew" id="dataEmissaoNew"/>
                              <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="atualizarDataEmissao({$id},dataEmissaoNew.value);">Enviar</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>                  
                    </div>
                    </div>
                </div>
                
        </div>                                    
  
            
                  <div class="x_content">
                        <div class="form-group line-formated">

                            <div class="col-md-5 col-sm-12 col-xs-12 line-formated">
                                <label for="cliente" >Cliente</label>
                                <div class="input-group line-formated">
                                    <input 
                                    {if (($situacao != 6) and ($situacao != 9))}
                                        enable 
                                    {else}
                                        disabled
                                    {/if}   
                                    type="text" class="form-control input-sm" id="nome" name="nome" placeholder="Pessoa" required
                                           value="{$nome}" readonly>
                                    <span class="input-group-btn">
                                        <button 
                                        {if (($situacao != 6) and ($situacao != 9))}
                                            enable 
                                        {else}
                                            disabled
                                        {/if}
                                        type="button" class="btn btn-primary btn-sm" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                                           
                                </div>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-1 line-formated">
                                <label for="credito" >Credito</label>
                                <input type="text" class="form-control input-sm" id="credito" name="credito" placeholder="Credito" required
                                           value="{$credito}" readonly>
                            </div>
 
                            <div class="col-md-3 col-sm-12 col-xs-12 line-formated">
                                <label for="condPagamento" >Condição de Pagamento</label>
                                <div class="input-group line-formated">
                                    <div class="panel panel-default small line-formated">
                                    <select name="condPgto" class="input-sm js-example-basic-single form-control" name="condPgto" id="condPgto">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 line-formated">
                                <label for="centroCustoEntrega">Centro de Custo Entrega</label>
                                <div class="panel panel-default small line-formated">
                                    <select name="centroCustoEntrega" class="input-sm form-control">
                                        {html_options values=$centroCustoEntrega_ids selected=$centroCustoEntrega_id output=$centroCustoEntrega_names}
                                    </select>
                                </div>
                            </div>
                                                    

                    </div>
                  </div>

	            <div class="x_content">
                    <div class="row">
                            <div class="form-group line-formated">
                                <div class="col-md-2 col-sm-6 col-xs-3">
                                    <label for="usrfatura">Vendedor</label>
                                    <div class="panel panel-default small line-formated">
                                        <select name="usrfatura" class="input-sm form-control">
                                            {html_options values=$usrfatura_ids selected=$usrfatura output=$usrfatura_names}
                                        </select>
                                    </div>
                                </div>
                        
                                <div class="col-md-2 col-sm-6 col-xs-6 line-formated">
                                    <label for="emissao">Emissão</label>
                                    <input class="input-sm form-control" placeholder="Data de Emissão." id="emissao" 
                                            name="emissao" value="{$emissao}">
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-4 line-formated">
                                    <label for="prazoEntrega">Entrega</label>
                                    <input class="input-sm form-control" placeholder="Prazo de Entrega." id="prazoEntrega" 
                                            name="prazoEntrega" value="{$prazoEntrega}">
                                </div>
                                
                                <div class="col-md-2 col-sm-12 col-xs-12 line-formated">
                                    <label for="frete">Frete</label>
                                    <input 
                                       {if $adicionadoItem eq 'S'}
                                            readonly
                                        {else if $situacao == 6}
                                            readonly
                                        {else}    
                                            enable 
                                        {/if}
                                        class="input-sm form-control {if $situacao == 6} {else}money{/if}" type="text"  id="frete" name="frete" placeholder="digite o valor do frete" value={$frete}>
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12 line-formated">
                                    <label for="desconto">Desconto</label>
                                    <input
                                       
                                        class="input-sm form-control {if $situacao == 6} {else}money{/if}" type="text"  id="desconto" name="desconto"  placeholder="Desconto Geral" value={$desconto}
                                        {if $digitarDesconto eq 'N'}
                                            readonly
                                        {else if $situacao == 6}
                                            readonly
                                        {else}
                                            onchange="javascript:confirmacaoDesconto()"
                                        {/if}
                                        >
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-4 line-formated">
                                    <label for="despeAcessorias" >Despesas Acessórias</label>
                                    <input 
                                       {if $adicionadoItem eq 'S'}
                                            readonly
                                        {else if $situacao == 6}
                                            readonly
                                       {else}    
                                            enable 
                                       {/if}
                                       class="input-sm form-control {if $situacao == 6} {else}money{/if}" type="text"  id="despAcessorias" name="despAcessorias" placeholder="Despesas acessórias" value={$despAcessorias}>
                                </div>  
                            </div>
                            <div class="form-group line-formated">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="obs" >Observa&ccedil;&atilde;o</label>
                                    <textarea class="resizable_textarea form-control input-sm" id="obs" name="obs" rows="2" >{$obs}</textarea>
                                </div>  
                            </div>
                    
                    </div>
                </div>



                <div class="x_content">
                <div class="row">
                   
                    <div class="col-lg-5 col-sm-10 col-xs-10 text-left">
                        <label>Produto</label>
                        <div class="input-group">
                            <input
                            {if (($situacao != 6) and ($situacao != 9))}
                                enable 
                            {else if (($situacao eq 6) and ($permiteAlterarVenda))}
                                enable 
                            {else}
                                disabled
                            {/if}        
                             class="form-control input-sm" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" 
                                name="pesProduto" value="{$pesProduto}" 
                                onChange="javascript:submitBuscar('');">
                            <span class="input-group-btn">
                                <button 
                                
                                {if (($situacao != 6) and ($situacao != 9))}
                                    enable 
                                {else if (($situacao eq 6) and ($permiteAlterarVenda))}
                                    enable 
                                {else}
                                    disabled
                                {/if}
                                type="button" class="btn btn-primary btn-sm" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=ped_telhas_novo', 'produto');">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </button>
                            </span> 
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-xs-4 text-left">
                        <label>Localização</label>
                        <div class="form-group">
                            <input 
                                {if (($situacao != 6) and ($situacao != 9))}
                                    enable 
                                {else if (($situacao eq 6) and ($permiteAlterarVenda))}
                                    enable 
                                {else}
                                    disabled
                                {/if}
                            class="input-sm form-control" placeholder="Digite a localização do produto para pesquisar." id="pesLocalizacao" 
                                name="pesLocalizacao" value="{$pesLocalizacao}"
                                onChange="javascript:submitBuscar('');">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-4 col-xs-4 text-left">
                        <label>Grupo</label>
                        <div class="panel panel-default">
                            <SELECT class="input-sm form-control" name="grupo"> 
                                {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                            </SELECT>
                        </div>
                    </div>
                    
                    <div class="col-lg-1 col-sm-1 col-xs-1 text-left">  
                        <br>       
                        <button  
                        {if (($situacao != 6)  and  ($situacao != 9))}
                            enable 
                        {else if (($situacao eq 6) and ($permiteAlterarVenda))}
                            enable 
                        {else}
                            disabled
                        {/if}
                        type="button" class="btn btn-warning btn-sm"  onClick="javascript:submitBuscar('');">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>Pesquisa
                        </button>         
                    </div>
            </div>
            </div><!-- x_content -->
            </div><!-- x_panel -->

              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="margin-top: 9px;">
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
                    <h2 style="visibility: hidden">'..'</h2>
                    <button type="button" class="btn btn-primary btn-sm" {if $situacao eq 6} disabled {/if}
                            onClick="javascript:pesquisaProdutoVazio('{$id}', '{$subMenu}')">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons" class="table table-condensed table-responsive small">
                        <thead>
                            <tr>
                                <th>Código</th>    
                                <!-- <th>Descri&ccedil;&atilde;o</th>   
                                <th>Uni</th>  
                                <th>Estoque</th> -->
                                <th>Valor Unit&aacute;rio</th>
                                <th>Quant. Venda</th>
                                <th>% Desconto</th>                               
                                <th></th>                               

                            </tr>
                        </thead>
                        <tbody>


                            {section name=i loop=$lancPesq}
                                {assign var="total" value=$total+1}
                                <tr>
                                    <td hidden> {$lancPesq[i].CODIGO} </td>
                                    <td> 
                                        <input
                                        {if ($permiteDigitarCodigo == false) } 
                                            disabled
                                        {else}    
                                            enable 
                                        {/if}                                         
                                        class="form-control input-sm" name="CODIGONOTA" value={$lancPesq[i].CODIGONOTA}>
                                    </td> 
                                    <!-- <td> {$lancPesq[i].DESCRICAO} </td> 
                                    <td> {$lancPesq[i].UNIDADE} </td>
                                    <td class="price-value"> {$lancPesq[i].QUANTIDADE|number_format:2:",":"."}  </td>-->
                                    <td align=right> 
                                        <input 
                                        {if ($permiteAlterarValor == false) } 
                                            disabled
                                        {else}    
                                            enable 
                                        {/if}
                                        class="form-control input-sm price-value money" value={$lancPesq[i].VENDA|number_format:2:",":"."} >
                                    </td>
                                    <td> 
                                        <input name="{$lancPesq[i].CODIGO}" type=hidden value={$lancPesq[i].CODIGO}>
                                        <input class="form-control input-sm money" 
                                               title="Digite a qtde para este item." id="quant" name=quant{$lancPesq[i].CODIGO} >
                                    </td>
                                    
                                    <td align=right> 
                                        <input class="form-control input-sm money" 
                                               title="Digite a qtde para este item." id="quant" name=vlrDescontoItem{$lancPesq[i].CODIGO} 
                                        value={$lancPesq[i].DESCONTO|number_format:2:",":"."} >
                                    </td> 
                                    <td> 
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-link"  onClick="javascript:submitIncluirItemQuantPrecoPecas('{$str}');">
                                                <span class="glyphicon glyphicon-shopping-cart" aria-top="true"></span>
                                            </button>
                                        </span> 
                                    </td>
                                </tr>
                                <tr>                                    
                                    <td class="price-value" style="padding-top: 0px;
                                        border-top-width: 0px;
                                        text-align: left;"> Estoque {$lancPesq[i].QUANTIDADE|number_format:2:",":"."}  -  {$lancPesq[i].UNIDADE} </td>                                     
                                    <td colspan = "6" style="padding-top: 0px;
                                        border-top-width: 0px;
                                        text-align: left;">
                                        <input style="width: 358px;" 
                                        {if ($permiteAlterarValor == false) } 
                                            disabled
                                        {else}    
                                            enable 
                                        {/if}
                                        value='{$lancPesq[i].DESCRICAO}' maxlength="68">
                                    </td>
                                </tr>
                                <tr>    
                                  <td colspan = "6"
                                   BGCOLOR= white
                                   style="  border-top-width: 0px;
                                            padding-top: 0px;
                                        ">
                                  </td>
                                </tr>
                                
                            {/section} 

                        </tbody>
                    </table>

                  </div>
                </div>
              </div>


              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="height: 47px;">
                    <h2>Compras <small>Produtos carrinho</small></h2>
                    <ul class="nav panel_toolbox">
                        <li>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target=".bs-impostos-modal">
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                            </button>
                        </li>
                        <li>
                            <h4>TOTAL: {$totalPedido|number_format:2:",":"."}</h4>
                        </li>                            
                    </ul>
                    <div class="clearfix"></div>
                    <div class="modal fade bs-impostos-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Impostos Pedido</h4>
                            </div>
                            <div class="modal-body">
                            </div>
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
                                        <th>Nr</th>
                                        <th>Código</th>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Qtde</th>
                                        <th>Valor Unit&aacute;rio</th>
                                        <th>Valor Total</th>
                                        <th style="color:blue">Origem</th>
                                        <th style="color:blue">TribICMS</th>
                                        <th style="color:blue">CSOSN</th> 
                                        <th style="color:orange">Base ST</th>
                                        <th style="color:orange">MVA ST</th>
                                        <th style="color:orange">ST</th>                                
                                    </tr>
                                </thead>
                                <tbody id="bodyMotivo">
                                    {section name=i loop=$lancItens}
                                        {assign var="total" value=$total+1}
                                        <tr><td>{$lancItens[i].NRITEM} </td>
                                            <td>{$lancItens[i].ITEMESTOQUE} </td>
                                            <td contenteditable="true" id="desc1"> {$lancItens[i].DESCRICAO}  </td>
                                            <td align=right> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                            <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                            <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                            <td style="color:blue" align=right> {$lancItens[i].ORIGEM} </td>
                                            <td style="color:blue" align=right> {$lancItens[i].ORIGEM} </td>
                                            <td style="color:blue" align=right> {$lancItens[i].CSOSN} </td>
                                            <td style="color:orange" align=right> {$lancItens[i].BASESUBTRIB|number_format:2:",":"."} </td>
                                            <td style="color:orange" align=right> {$lancItens[i].MVAST|number_format:2:",":"."} </td>
                                            <td style="color:orange" align=right> {$lancItens[i].SUBTRIB|number_format:2:",":"."} </td>
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
                  </div>
                  <div class="x_content">

                    <table id="datatable-buttons2" class="table table-condensed table-responsive small">
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
                                <th>Nr</th>
                                <th>Código</th>
                                <!--<th>Descri&ccedil;&atilde;o</th>-->
                                <th>Qtde</th>
                                <th>Unit&aacute;rio</th>
                                <th>Total Produto</th>
                                <th>Valor Desconto</th>
                                <th>Total Item</th>
                                <th style="display:none">Num OC</th>
                                {if ($permiteAlterarCusto == "S") } 
                                    <th>Custo</th>
                                {/if}
                                {if ($exibirmotivo == 'S')}
                                <th><button type="button" class="btn btn-warning btn-xs"  onClick="javascript:submitExibirMotivo('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></th>
                                {/if}                                
                            </tr>
                        </thead>
                        <tbody id="bodyMotivo">
                            {section name=i loop=$lancItens}
                                {assign var="total" value=$total+1}
                                <tr style="height: 50px;" 
                                    {if ($lancItens[i].PRECOMINIMO > $lancItens[i].UNITARIO) and ($situacao == 10)}style="color:orange"{/if}>
                                    {if ($exibirmotivo == 'S')}
                                    <td>
                                    <input type="checkBox"  name="checkedPerdido" id="{$lancItens[i].NRITEM}"/>
                                    </td>
                                    {/if}
                                    <td>{$lancItens[i].NRITEM} </td>
                                    <td>{$lancItens[i].ITEMESTOQUE} </td>
                                    <!--td>{$lancItens[i].CODIGONOTA} </td-->
                                    <td hidden> 
                                        {$lancItens[i].DESCRICAO}  
                                    </td>
                                    <td align=right> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                    <td align=right> {$lancItens[i].DESCONTO|number_format:2:",":"."} </td>
                                    <td align=right> {($lancItens[i].TOTAL-$lancItens[i].DESCONTO)|number_format:2:",":"."} </td>
                                    <td style="display:none"> {$lancItens[i].NUMEROOC} </td>
                                    {if $sistema != "PECAS"} 
                                        {if ($permiteAlterarCusto == "S") } 
                                            <td align=right> {($lancItens[i].CUSTO/$lancItens[i].QTSOLICITADA)|number_format:2:",":"."} </td>
                                        {/if}
                                        <td style="width:5px">
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" onclick="editar(this, {$lancItens[i].ID}, {$lancItens[i].NRITEM}, {$lancItens[i].PERCDESCONTO} )" data-target="#modalInutiliza"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        </td>
                                        {if ($situacao == 6) and ($permiteGerarBonus == "S") }
                                            <td style="width:5px">
                                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" onclick="devolver(this, {$lancItens[i].ID}, {$lancItens[i].NRITEM} )" data-target="#modalDevolver"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span></button>
                                            </td>
                                        {/if}
                                        {if 
                                        ((($situacao eq 6) and ($permiteAlterarVenda)) or                                        
                                        (($situacao != 6) and ($situacao != 9)))}
                                            <td style="width:5px"> 
                                                <button type="button" class="btn btn-danger btn-xs" onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> 
                                            </td>
                                        {/if}
                                    {else}
                                        {if ($permiteAlterarCusto == "S") } 
                                            <td align=right> {($lancItens[i].CUSTO/$lancItens[i].QTSOLICITADA)|number_format:2:",":"."} </td>
                                        {/if}
                                        
                                        {if ($situacao == 0) or ($situacao == 5) or ($situacao == "")} 
                                            <td>
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" onclick="editar(this, {$lancItens[i].ID}, {$lancItens[i].NRITEM}, {$lancItens[i].PERCDESCONTO} )" data-target="#modalInutiliza"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger btn-xs" onClick="javascript:submitExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM});"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> 

                                            </td> 
                                            <td style="width:5px"> 
                                            </td>                 
                                        {/if}                       
                                    {/if}
                                </tr>
                                <tr>
                                    <td colspan = "8" style="padding-top: 0px;
                                        border-top-width: 0px;
                                        text-align: left;">
                                        {$lancItens[i].DESCRICAO}
                                    </td>
                                </tr>
                                <tr>    
                                  <td colspan = "8"
                                   BGCOLOR= white
                                   style="  border-top-width: 0px;
                                            padding-top: 0px;
                                        ">
                                  </td>
                                </tr>
                        {/section} 

                        </tbody>
                    </table>

                    <div class="modal fade" id="modalInutiliza" role="dialog">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pedido {$id}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="col-md-1">
                                <label for="nritem">Nr Item</label>
                                <input class="form-control" readonly  type="number" id="nritem" name="nritem" value={$nritem}>
                            </div>
                            <div class="col-md-2">
                                <label for="nritem">Nova Pos Item</label>
                                <input class="form-control" type="number" id="positem" name="positem" value={$positem}>
                            </div>
                            <div class="col-md-2">
                                <label for="codigo">Codigo</label>
                                <input  class="form-control" type="text" id="codigo" name="codigo">
                            </div>
                            <div class="col-md-7">
                                <label for="descricao">Descricao</label>
                                <input class="form-control" type="text" id="descricao" name="descricao">
                            </div>
                            <div class="col-md-2">
                                <label for="quantidade">Qtd</label>
                                <input  class="form-control money" type="text" id="quantidade" name="quantidade" onchange="javascript:calculaPerc('')">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="unitario">Valor Unitario</label>
                                <input class="form-control money" type="text" id="unitario" name="unitario" onchange="javascript:calculaPerc('')">
                            </div>

                            <div class="col-md-2">
                                <label for="totalitem">% Desconto</label>
                                <input class="form-control money" type="text" id="percDescontoItem" name="percDescontoItem" onchange="javascript:calculaPerc('')">
                            </div>

                            <div class="col-md-2">
                                <label for="totalitem">Valor Desconto</label>
                                <input  class="form-control money" type="text" id="descontoItem" name="descontoItem" onchange="javascript:calculaPerc('desconto')">
                            </div>                            


                            <div class="col-md-2">
                                <label for="custo">Custo</label>
                                <input {if ($permiteAlterarCusto != "S") } Readonly {/if} class="form-control money" type="text" id="custo" name="custo">
                            </div>
                            <div class="col-md-2">
                                <label for="totalitemDesconto">Total</label>
                                <input Readonly class="form-control" type="text" id="totalitemDesconto" name="totalitemDesconto">
                            </div>
                            <div class="col-md-3">
                                <label for="totalitemDesconto">Numero OC </label>
                                <input class="form-control" type="text" id="numeroOc" name="numeroOc">
                            </div>
                            
                            
                            <div class="col-md-1">
                                <label for="promocoes">Estoque</label>
                                <div class="panel" >
                                    <input type="checkbox" class="js-switch" id="estoque" name="estoque" checked/> 
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:atualizaCustoNovo({$id}, nritem.value, custo.value, totalItem.value, quantidade.value, descricao.value, codigo.value, estoque.checked, descontoItem.value, percDescontoItem.value, unitario.value, numeroOc.value, positem.value );">Confirma</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>


            

                  </div>
                </div>
              </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="modalDevolver" role="dialog">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pedido {$id}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="col-md-2">
                                <label for="nritemD">Nr Item</label>
                                <input Readonly class="form-control" type="text" id="nritemD" name="nritemD">
                            </div>
                            <div class="col-md-3">
                                <label for="codigoD">Codigo</label>
                                <input Readonly class="form-control" type="text" id="codigoD" name="codigoD">
                            </div>
                            <div class="col-md-7">
                                <label for="descricaoD">Descricao</label>
                                <input Readonly class="form-control" type="text" id="descricaoD" name="descricaoD">
                            </div>
                            <div class="col-md-2">
                                <label  for="quantidadeV">Qtd Vendida</label>
                                <input Readonly class="form-control" type="text" id="quantidadeV" name="quantidadeV">
                            </div>
                            <div class="col-md-2">
                                <label for="quantidadeD">Qtd</label>
                                <input class="form-control" type="text" id="quantidadeD" name="quantidadeD" onblur="calcular()">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="unitarioD">Unitario</label>
                                <input Readonly class="form-control" type="text" id="unitarioD" name="unitarioD">
                            </div>

                            <div class="col-md-3">
                                <label for="totalitemD">Total</label>
                                <input Readonly class="form-control" type="text" id="totalitemD" name="totalitemD" >
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="javascript:devolucao({$id}, 
                            document.getElementById('nritemD').value, 
                            document.getElementById('quantidadeD').value, 
                            document.getElementById('quantidadeV').value,
                            document.getElementById('unitarioD').value, 
                            document.getElementById('totalitemD').value);">Confirma</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
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
            theme: "classic"
        });
      });
    </script>
   
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

    

    <script>
      $(function() {
        $('#prazoEntrega').daterangepicker({
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

    <script>
    function editar(e, id, nritem, percDesc, descricao){
                
        var linha = $(e).closest("tr");
        var nritem = linha.find("td:eq(0)").text().trim();        
        var codigo = linha.find("td:eq(1)").text().trim(); 
        var descricao = linha.find("td:eq(2)").text().trim(); 
        var quantidade = linha.find("td:eq(3)").text().trim(); 
        var unitario = linha.find("td:eq(4)").text().trim(); 
        
        var desconto = linha.find("td:eq(6)").text().trim();
        var totalitem = linha.find("td:eq(7)").text().trim();
        var numOc = linha.find("td:eq(8)").text().trim();
        {if ($permiteAlterarCusto == "S") } 
          var custo = linha.find("td:eq(9)").text().trim();  
        {else}
          var custo = 0.00;
        {/if} 
        var percDesconto = percDesc
        percDesconto = percDesconto.toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        percDesconto == '' ?  percDesconto = 0 :  percDesconto = percDesconto;

                
        $("#nritem").val(nritem);
        $("#codigo").val(codigo);
        $("#descricao").val(descricao);
        $("#quantidade").val(quantidade);
        $("#unitario").val(unitario);
        $("#percDescontoItem").val(percDesconto);
        $("#descontoItem").val(desconto);
        $("#totalitemDesconto").val(totalitem);
        $("#custo").val(custo); 
        $("#numeroOc").val(numOc);    
    }
    </script>
                    
    <script>
    function devolver(e, id, nritem){

        var linha = $(e).closest("tr");
        var nritem = linha.find("td:eq(0)").text().trim();
        var codigo = linha.find("td:eq(1)").text().trim(); 
        var descricao  = linha.find("td:eq(2)").text().trim();
        var quantidade = linha.find("td:eq(3)").text().trim();
        var quantidadeV = linha.find("td:eq(3)").text().trim(); 
        var unitario = linha.find("td:eq(4)").text().trim(); 
        var totalitem = linha.find("td:eq(5)").text().trim();
        
        $("#nritemD").val(nritem);
        $("#codigoD").val(codigo);
        $("#descricaoD").val(descricao);
        $("#quantidadeD").val(quantidade);
        $("#quantidadeV").val(quantidadeV);
        $("#unitarioD").val(unitario);
        $("#totalitemD").val(totalitem); 
    }
    </script>

    <script>
    function calcular(e, id, nritem){
        
        var valor1 = document.getElementById('quantidadeD').value;
        valor1 = valor1.replace("." , "");
        valor1 = valor1.replace("," , "."); 
        valor1 = parseFloat(valor1);
        
        var valor2 = document.getElementById('unitarioD').value;
        valor2 = valor2.replace("." , "");
        valor2 = valor2.replace("," , "."); 
        valor2 = parseFloat(valor2);
        
        var qtdV = document.getElementById('quantidadeV').value;
        qtdV = qtdV.replace("." , "");
        qtdV = qtdV.replace("," , "."); 
        qtdV = parseFloat(qtdV);
        
        if (valor1 <= 0 ){
            valor1 = 1;
        } else 
        if (valor1 > qtdV) {
            valor1 = qtdV;
        } 
        
        var total =  valor1 * valor2;
        total = total.toFixed(2);
        total = total.toString(); 
        total = total.replace("." , ",");

        document.getElementById('totalitemD').value = total;
        document.getElementById('quantidadeD').value = valor1;
    }
    </script>          
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowZero: true
        });        
     });
    </script>       

    <style>
        .line-formated{
            margin-bottom: 1px;
        }
        #bodyMotivo {
            font-size:10px;
        }
        #btnDadosPed{
            margin-left: -14px;
        }
        #btnDuplicaPed{
            margin-left: 6px; 
        }
        #btnParcFin{
            margin-left: 6px;
        }
    </style>        