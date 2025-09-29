<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_nf.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Pedidos</h3>
              </div>
            </div>

            <div class="row">

                <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
                    <input name=mod           type=hidden value="ped">   
                    <input name=form          type=hidden value="pedido_venda_nf">   
                    <input name=opcao         type=hidden value="{$opcao}">
                    <input name=id            type=hidden value="">
                    <input name=letra         type=hidden value={$letra}>
                    <input name=submenu       type=hidden value={$subMenu}>

              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <h2>Emitir Nota Fiscal
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
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

                    

                </form>
                  </div>

                </div> <!-- x_panel -->
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->



        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Emissão</th>
                            <th>Total</th>
                            <th style="width: 80px;">Nota</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].ID} </td>
                                <td> {$lanc[i].NOME} </td>
                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

                                <td >
                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitCadastro('{$lanc[i].ID}');">Nota Fiscal</button>
                                </td>
                            </tr>
                        <p>
                    {/section} 

                    </tbody>
                </table>

              </div> <!-- div class="x_panel"-->
            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
