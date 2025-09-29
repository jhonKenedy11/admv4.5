<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>    
<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Produtos - Consulta</h2>
                    {include file="../bib/msg.tpl"}                    
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li>
                                  <button type="button" class="btn btn-dark btn-xs" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:consultaPrint('relatorio_produto_validade');"><span> Produtos data validade</span></button>
                              </li>
                            </ul>
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
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=tituloImg     type=hidden value="{$tituloImg}">
                            <input name=imgBtn        type=hidden value="{$imgBtn}">

                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>C&oacute;d. Fabricante</label>
                            <input class="form-control" id="codFabricante" autofocus name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
                        </div>
                        <div class="form-group col-md-8 col-sm-12 col-xs-12">
                            <label>Descri&ccedil;&atilde;o</label>
                            <input class="form-control" id="produtoNome" name="produtoNome" placeholder="Digite a descrição."  value="{$produtoNome}" >
                        </div>
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>Localização</label>
                            <input  class="form-control" type="text" id="localizacao" name="localizacao" placeholder="Digite a localização."   value={$localizacao}>
                        </div>
                    <!-- dados adicionaris -->                
                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                          </h4>
                        </a>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <div class="x_panel">

                                
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control" name="grupo"> 
                                {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                            </SELECT>
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="quant" value="true"> Produtos com estoque
                            </label>
                          </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="fora" value="true"> Produtos fora de linha
                            </label>
                          </div>
                        </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- end of accordion -->

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
                            <th>Localização</th>
                            <th>Grupo</th>
                            <th>Marca</th>
                            <th>Unidade</th>
                            <th>Qtde Dispon&iacute;vel</th>
                            <!--th>Qtde Reserva</th-->
                            <th>Preço Venda</th>
                            <th style="width: 80px;">Manuten&ccedil;&atilde;o</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].CODIGO} </td>
                                <td> {$lanc[i].CODFABRICANTE} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].LOCALIZACAO} </td>
                                <td> {$lanc[i].NOMEGRUPO} </td>
                                <td> {$lanc[i].NOMEMARCA} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td> {$lanc[i].ESTOQUE|number_format:2:",":"."} </td>
                                <!--td> {$lanc[i].RESERVA} </td-->
                                <td align=right> {$lanc[i].VENDA|number_format:2:",":"."} </td>
                                <td >
                                     
                                    <button {if $imgBtn != true } style="display:none" {/if} type="button" class="btn btn-info btn-xs" title="Adicionar imagem" onclick="javascript:submitCadastrarImagem('{$lanc[i].CODIGO}','{$lanc[i].DESCRICAO}');"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></button>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].CODIGO}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].CODIGO}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
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
    <script src="{$bootstrap}/sweetalert2/dist/sweetalert2.all.min.js"></script>
    
    <!-- /Datatables -->
