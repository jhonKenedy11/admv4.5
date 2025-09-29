<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_conferencia.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Pedidos</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <h2>Confer&ecirc;ncia - <b>Pedido: {$pedido}</b>
                            {if $mensagem neq ''}
                                <div class="container">
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success fade in">{$mensagem}</div>
                                    {else}    
                                        <div class="alert alert-warning fade in">{$mensagem}</div>
                                    {/if}    
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarConferencia();">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span>
                            </button>
                        </li>
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

                    <FORM class="full" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
                        <input name=mod                 type=hidden value="">   
                        <input name=form                type=hidden value="">   
                        <input name=submenu             type=hidden value={$subMenu}>
                        <input name=id                  type=hidden value={$id}>
                        <input name=pedido              type=hidden value={$pedido}>
                        <input name=origem              type=hidden value={$origem}>
                        <input name=letra               type=hidden value={$letra}>
                        <input name=opcao               type=hidden value={$opcao}>
                        <input name=pesq                type=hidden value={$pesq}>
                        <input name=itensPedido         type=hidden value={$itensPedido}>
                        <input name=fornecedor          type=hidden value="">

                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label>Produto</label>
                            <input class="form-control" type="text" autofocus id="codProduto" name="codProduto" value={$codProduto}>
                        </div>
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>Quantidade</label>
                            <input class="form-control" type="text" id="qtdeConferido" name="qtdeConferido" value={$qtdeConferido}>
                        </div>
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
                        <tr>
                            <th>Produto</th>
                            <th>Código EAN</th>
                            <th>Qtda Pedido</th>
                            <th>Qtda Conferido</th>

                        </tr>
                    </thead>
                    <tbody>


                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}

                                {if $lanc[i].QTSOLICITADA eq  $lanc[i].QTCONFERIDA}
                                    <tr class="green">
                                {else}
                                    <tr class="red">
                                {/if}
                                <td> {$lanc[i].ITEMESTOQUE} - {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].CODIGOBARRAS} </td>
                                <td> {$lanc[i].QTSOLICITADA} </td>
                                <td> {$lanc[i].QTCONFERIDA} </td>
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
