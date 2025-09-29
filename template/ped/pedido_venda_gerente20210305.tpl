<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente.js"> </script>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_conferencia.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Gerencia de Pedidos</h3>
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
                    <input name=mod           type=hidden value="{$mod}">   
                    <input name=form          type=hidden value="{$form}">   
                    <input name=origem        type=hidden value="{$origem}">   
                    <input name=opcao         type=hidden value="">
                    <input name=id            type=hidden value="">
                    <input name=letra         type=hidden value={$letra}>
                    <input name=submenu       type=hidden value={$subMenu}>


                    

              <!-- panel principal  separacao -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <h2>Pedido
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">


        <!-- panel tabela dados -->  

                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: gray; color: white;">
                            <th>Cliente</th>
                            <th>Pedido</th>
                            <th>Emiss&atilde;o</th>
                            <th>Total</th>
                            <th>#</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {if $lanc[i].SITUACAO eq '6'}
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
                        <p>
                    {/section} 

                    </tbody>
                </table>
               </div> <!-- x_content -->
                    
              </div> <!-- div class="x_panel"-->
            </div> <!-- div col tamanho = tabela principal-->

            
              <!-- panel principal Conferencia -->  
              <!--div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <h2>Confer&ecirc;ncia
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <div class="x_content" style="display: none;>
                <div class="x_content">

        <!-- panel tabela dados >  

                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <!--table id="datatable-buttons-2" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: goldenrod; color: white;">
                            <th>Cliente</th>
                            <th>Pedido</th>
                            <th>Emiss&atilde;o</th>
                            <th>Total</th>
                            <th>#</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {if $lanc[i].SITUACAO eq 2}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>


                                <td >
                                    <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastroConferencia('{$lanc[i].ID}','{$lanc[i].PEDIDO}','pedido_venda_gerente');"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                                </td>
                            </tr>
                            {/if}
                        <p>
                    {/section} 

                    </tbody>
                </table>
               </div--> <!-- x_content -->
                    
              <!--/div> <!-- div class="x_panel"-->
            <!--/div> <!-- div col tamanho = tabela principal-->

              <!-- panel principal Nota Fiscal -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Emitir Nota Fiscal
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                   </h2> 
                   <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitMesAtual();">Mês Atual</button>
                                                   
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

        <!-- panel tabela dados -->  

                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons-2" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: green; color: white;">
                            <th>Cliente</th>
                            <th>Pedido</th>
                            <th>Emiss&atilde;o</th>
                            <th>Total</th>
                            <th style="width: 80px;">#</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {if $lanc[i].SITUACAO eq 3}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

                                <td >
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitCadastro('{$lanc[i].ID}');">Nota Fiscal</button>
                                </td>
                                <!--td >
                                    <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastro('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                    <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastro('{$lanc[i].ID}');"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                                </td-->
                            </tr>
                            {/if}
                        <p>
                    {/section} 

                    </tbody>
                </table>
               </div> <!-- x_content -->
                    
              </div> <!-- div class="x_panel"-->
            </div> <!-- div col tamanho = tabela principal-->

              <!-- panel principal Entrega -->  
              <!--div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <h2>Entrega Pedido
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <!--div class="x_content">

        <!-- panel tabela dados -->  

                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <!--table id="datatable-buttons-2" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: blue; color: white;">
                            <th>Cliente</th>
                            <th>Pedido</th>
                            <th>Emiss&atilde;o</th>
                            <th>Total</th>
                            <th style="width: 80px;">Manuten&ccedil;&atilde;o</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {if $lanc[i].SITUACAO eq 4}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].USERLOGIN} - {$lanc[i].NOMEREDUZIDO} </td>
                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>


                                <td >
                                    <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastro('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
                                    <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastro('{$lanc[i].ID}');"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                                </td>
                            </tr>
                            {/if}
                        <p>
                    {/section} 

                    </tbody>
                </table>
               </div--> <!-- x_content -->
                    
              <!--/div> <!-- div class="x_panel"-->
            <!--/div> <!-- div col tamanho = tabela principal-->

            
            </form>
        </div>  <!-- div row = painel principal-->
        
       </div> <!-- div  "-->
     </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
