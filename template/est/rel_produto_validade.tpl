<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Relatório Produto Validade</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  >  
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
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraCompras();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
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
                <div class="x_content" style="display: none;">
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="rel_compras">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">

                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
                        </div>
                        <div class="form-group col-md-8 col-sm-12 col-xs-12">
                            <label>Descri&ccedil;&atilde;o</label>
                            <input class="form-control" id="produtoNome" name="produtoNome" autofocus placeholder="Digite a descrição."  value="{$produtoNome}" >
                        </div>
                        
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control" name="grupo"> 
                                {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                            </SELECT>
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
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Qtde Dispon&iacute;vel</th>
                            <th>Lote</th>
                            <th>Data Fabricação</th>
                            <th>Data Validade</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            {assign var="margem" value=(($lanc[i].VENDA*100)/$lanc[i].CUSTOCOMPRA)-100}
                            <tr>
                                <td> {$lanc[i].CODPRODUTO} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].QUANT} </td>
                                <td> {$lanc[i].FABLOTE} </td>
                                <td> {$lanc[i].FABDATAFABRICACAO|date_format:"%e %b, %Y"} </td>
                                <td> {$lanc[i].FABDATAVALIDADE|date_format:"%e %b, %Y"} </td>
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
