    <script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Produtos</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                        {if $mensagem neq ''}
                            <div class="container">
                                <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                            </div>    
                        {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraPesquisa();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:fechar();">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span><span> Fechar</span>
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

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="produto">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value={$opcao}>
                            <input name=letra         type=hidden value={$letra}>
                            <input name=submenu       type=hidden value={$subMenu}>
                            <input name=pessoa        type=hidden value={$pessoa}>
                            <input name=codProduto    type=hidden value="">
                            <input name=grupo         type=hidden value="">
                            <input name=localizacao   type=hidden value="">
                            <input name=quant         type=hidden value="false">
                            <input name=checkbox      type=hidden value="{$checkbox}">

                        <h2>Produto Nota Fiscal Entrada</h2>    
                        <div class="form-group col-md-6 col-sm-8 col-xs-12">
                            <label>Descri&ccedil;&atilde;o</label>
                            <input class="form-control"  name="produtoNomeNfe"  readonly value="{$produtoNomeNfe}" >
                        </div>
                        <div class="form-group col-md-2 col-sm-3 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control"  name="codFabricanteNfe" readonly value={$codFabricanteNfe} >
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <h2>Pesquisa Produto Equivalente</h2>
                        </div>
                        <div class="form-group col-md-6 col-sm-8 col-xs-12">
                            <label>Descri&ccedil;&atilde;o</label>
                            <input class="form-control" id="produtoNome" name="produtoNome" autofocus placeholder="Digite a descrição."  value="{$produtoNome}" >
                        </div>
                        <div class="form-group col-md-2 col-sm-3 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
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
                        <tr style="background: #2A3F54; color: white;">
                            <th>C&oacute;digo</th>
                            <th>C&oacute;d. Fabricante</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Unidade</th>
                            <th>Qtde Dispon&iacute;vel</th>
                            <th style="width: 80px;">Selecionar</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].CODIGO} </td>
                                <td> {$lanc[i].CODFABRICANTE} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td> {$lanc[i].ESTOQUE} </td>
                                <td class=" last">
                                    {if $origem eq 'nota'}
                                        <button type="button" class="btn btn-success btn-xs" 
                                        onclick="javascript:fechaProdutoNf({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}');">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                    {else}
                                        <button type="button" class="btn btn-success btn-xs" 
                                        onclick="javascript:fechaProdutoPesquisaNfe({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}');">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                    {/if}
                                            
                                </td>
                            </tr>
                        {/section} 

                    </tbody>
                </table>

              </div> <!-- div class="x_panel"-->
            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
