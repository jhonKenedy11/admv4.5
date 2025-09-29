<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  

    <!-- page content -->
    <div class="right_col" role="main">              

        <div class="">
            <div class="row">
              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>Gerencia de Pedidos
                        
                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
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
                        </strong>
                    </h2>             

                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="{$mod}">   
                        <input name=form          type=hidden value="{$form}">   
                        <input name=origem        type=hidden value="{$origem}">   
                        <input name=opcao         type=hidden value="">
                        <input name=id            type=hidden value="">
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>  
                        <input name=pedidoAgrupado       type=hidden value={$pedidoAgrupado}>  
                        <input name=pessoa               type=hidden value={$pessoa}> 
                        <input name=dadosPed               type=hidden value={$dadosPed}>  

                    <!-- INCLUDES DE MODAL -->
                    {include file="pedido_venda_gerente_agrupa_ped_modal.tpl"}
                    </form>
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th>Pessoa</th>
                                    <th>Pedido</th>
                                    <th>Emiss&atilde;o</th>
                                    <th>Total</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].SITUACAO eq 6}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                        <td> Ped:{$lanc[i].PEDIDO} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                        <td >
                                            <button type="button" class="btn btn-info btn-xs" onclick="javascript:submitImprime('{$lanc[i].ID}', 'index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                        </td>                                                                               
                                    </tr>
                                    {/if}
                                {/section} 
                            </tbody>
                        </table>   
                                                  
                    </div>                    
                  </div> <!-- div class="x_content" = inicio tabela -->
                  
                </div> <!-- div class="x_panel" = painel principal-->

                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Separação
                                {if $mensagem neq ''}
                                    <div class="container">
                                        <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                    </div>
                                {/if}
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                <thead>
                                    <tr style="background: gray; color: white;">
                                        <th>Cliente</th>
                                        <th>Pedido</th>
                                        <th>Emissão</th>
                                        <th>Total</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                        {if $lanc[i].SITUACAO eq '1'}
                                            <tr>
                                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs" onclick="javascript:submitImprime('{$lanc[i].ID}', 'index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                                </td>
                                            </tr>
                                        {/if}
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  

                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Conferência 
                            </h2> 
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitMesAtual();">Mês Atual</button>
                                                   
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><button type="button" class="btn btn-primary btn-xs" onClick="javascript:agrupaPedidoModal();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Agrupar Pedidos</span>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="x_content">
                            
                            <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                
                                <thead>
                                    <tr class="headings">
                                        <th></th>
                                        <th>Pessoa</th>
                                        <th>Pedido</th>
                                        <th>Emiss&atilde;o</th>
                                        <th>Total</th>
                                        <th style="display:none">frete</th>
                                        <th style="display:none">desp acessoria</th>
                                        <th style="display:none">desconto</th>
                                        <th style="display:none">pessoa</th>
                                        <th style="display:none">condPgto</th>
                                        <th style="width: 80px;">#</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                        {if $lanc[i].SITUACAO eq 3}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> <input type="checkBox"  name="pedidoChecked" id="{$lanc[i].PEDIDO}"/> </td>
                                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].FRETE|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].DESPACESSORIAS|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].DESCONTO|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].CLIENTE} </td>
                                                <td style="display:none"> {$lanc[i].CONDPG} </td>
                                                <td >
                                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitCadastro('{$lanc[i].ID}');">Nota Fiscal</button>
                                                </td>
                                            </tr>
                                        {/if}
                                    {/section} 
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>  

                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Entrega Pedido 
                            </h2> 
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitMesAtual();">Mês Atual</button>
                                                   
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><button type="button" class="btn btn-primary btn-xs" onClick="javascript:agrupaPedidoModal();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Agrupar Pedidos</span>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                            <div class="x_content">
                            
                            <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                
                                <thead>
                                    <tr class="headings">
                                        <th></th>
                                        <th>Pessoa</th>
                                        <th>Pedido</th>
                                        <th>Emiss&atilde;o</th>
                                        <th>Total</th>
                                        <th style="display:none">frete</th>
                                        <th style="display:none">desp acessoria</th>
                                        <th style="display:none">desconto</th>
                                        <th style="display:none">pessoa</th>
                                        <th style="display:none">condPgto</th>
                                        <th style="width: 80px;">#</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                        {if $lanc[i].SITUACAO eq 4}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> <input type="checkBox"  name="pedidoChecked" id="{$lanc[i].PEDIDO}"/> </td>
                                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].FRETE|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].DESPACESSORIAS|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].DESCONTO|number_format:2:",":"."} </td>
                                                <td style="display:none"> {$lanc[i].CLIENTE} </td>
                                                <td style="display:none"> {$lanc[i].CONDPG} </td>
                                                <td >
                                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitCadastro('{$lanc[i].ID}');">Nota Fiscal</button>
                                                </td>
                                            </tr>
                                        {/if}
                                    {/section} 
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>  

            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    <!-- /Datatables -->
    
    
    {include file="template/database.inc"}
    
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

    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
        $("#condPgto.js-example-basic-single").select2({
            theme: "classic",
            width: '100%'
        });
      });
    </script>

