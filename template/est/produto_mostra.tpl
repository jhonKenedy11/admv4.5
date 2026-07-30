<style>
.form-control,
.x_panel {
  border-radius: 5px;
}
.bottom {
    text-align: left;
}
.dataTables_wrapper {
    padding: 10px 0;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>    
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Produtos - Consulta</h2>

                      {if $mensagem_estoque_limitado neq ''}
                          <span style="color: #856404; font-size: 13px; font-weight: normal; margin-left: 10%; background-color: #fff3cd; padding: 8px 15px; border-radius: 4px; border: 1px solid #ffc107; vertical-align: middle;">
                              <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {$mensagem_estoque_limitado}
                          </span>
                      {/if}

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
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li>
                                  <button type="button" class="btn btn-dark btn-xs" data-toggle="modal" data-target="#modalInutiliza"
                                          onClick="javascript:consultaPrint('relatorio_produto_validade');"><span> Produtos data validade</span></button>
                              </li>
                            </ul>
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
                            <!-- Paginacao -->
                            <input name=quantidade_total_produtos_pesquisados type=hidden id="quantidade_total_produtos_pesquisados" value={$quantidade_total_produtos_pesquisados}>
                            <input name=quantidade_maxima_por_pagina type=hidden id="quantidade_maxima_por_pagina" value={$quantidade_maxima_por_pagina}>
                            <input name=quantidade_pesquisada type=hidden id="quantidade_pesquisada" value={$quantidade_pesquisada}>
                            <input name=total_paginas type=hidden id="total_paginas" value={$total_paginas}>
                            <input name=pagina_atual type=hidden id="pagina_atual" value={$pagina_atual}>
                            <!-- Fim Paginacao -->

                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                          <label>C&oacute;d. Fabricante / EAN</label>
                          <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Cód. Fab. ou EAN"  value={$codFabricante} >
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
                <div class="responsive">
                <table id="datatable-no-paginate" class="table table-bordered jambo_table">
                    <thead>
                    <tr class="headings">
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
                                    <button type="button" class="btn btn-default btn-xs" title="Imprimir etiqueta" onclick="javascript:imprimirEtiquetaProduto({$lanc[i].CODIGO});"><span class="fa fa-print" aria-hidden="true"></span></button>
                                </td>
                            </tr>
                        {/section} 

                    </tbody>
                </table>

                {if isset($quantidade_total_produtos_pesquisados) && $quantidade_total_produtos_pesquisados > 0}
                    <div class="dataTables_wrapper" style="margin-top: 10px;">
                        
                        <div class="row">
                            
                            <!-- esquerda (vazio pra manter alinhamento padrão) -->
                            <div class="col-sm-4"></div>

                            <!-- centro -->
                            <div class="col-sm-4 text-center">
                                <strong>
                                    Mostrando: {$quantidade_pesquisada} de {$quantidade_total_produtos_pesquisados} produtos
                                </strong>
                            </div>

                            <!-- direita -->
                            <div class="col-sm-4 text-right" style="white-space: nowrap;">
                                
                                <button
                                    {if $quantidade_pesquisada < $quantidade_maxima_por_pagina && $pagina_atual == 1}
                                        disabled
                                    {/if}
                                    type="button"
                                    class="btn btn-primary btn-xs"
                                    onclick="paginacao('anterior');">
                                    Anterior
                                </button>

                                <span style="margin: 0 6px;">
                                    Página {$pagina_atual} de {$total_paginas}
                                </span>

                                <button
                                    {if $quantidade_pesquisada < $quantidade_maxima_por_pagina}
                                        disabled
                                    {/if}
                                    type="button"
                                    class="btn btn-primary btn-xs"
                                    onclick="paginacao('proxima');">
                                    Próxima
                                </button>

                            </div>

                        </div>

                    </div>
                    {/if}

              </div> <!-- div class="x_panel"-->
            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div class="responsive"--> 
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    <script src="{$bootstrap}/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('lancamento').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitLetra();
            }
        });
    </script>
    
    <!-- /Datatables -->
