<script type="text/javascript" src="{$pathJs}/est/s_pesquisa_produto_atendimento_new.js"> </script>

<div class="right_col" role="main">      

<div class="">
    <div class="page-title">
        <div class="title_left"><h3>Produtos</h3></div>
    </div>

    <div class="row">
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
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">

                <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                    class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                    <input name=mod           type=hidden value="est">   
                    <input name=form          type=hidden value="produto">   
                    <input name=id            type=hidden value="">
                    <input name=opcao         type=hidden value={$opcao}>
                    <input name=letra         type=hidden value={$letra}>
                    <input name=submenu       type=hidden value={$subMenu}>
                    <input name=grupo         type=hidden value="">
                    <input name=localizacao   type=hidden value="">
                    <input name=quant         type=hidden value="false">
                    <input name=codigo        type=hidden value="">
                    <input name=from          type=hidden value="{$from}">
                    <input name=acao          type=hidden value="{$acao}">
                    <input name=codProduto    type=hidden value="{$codProduto}">
                    <input name=quantidadePecas    type=hidden value="{$quantidadePecas}"> <!-- baixa estoque -->
                    <input name=vlrUnitarioPecas    type=hidden value="{$vlrUnitarioPecas}"> <!-- baixa estoque -->
                    <input name=uniFracionada    type=hidden value="{$uniFracionada}"> <!-- baixa estoque -->
                    <input name=vlrDescontoPecas    type=hidden value={$vlrDescontoPecas}>
                    <input name=percDescontoPecas    type=hidden value={$percDescontoPecas}>

                    <input name=idTipoAtendimento    type=hidden value={$idTipoAtendimento}>
                    <input name=tipoCategoriaAtendimento    type=hidden value={$tipoCategoriaAtendimento}>

                    <div class="form-group col-md-2 col-sm-3 col-xs-12">
                        <label>C&oacute;d. Fabricante</label>
                        <input class="form-control" id="codFabricante" name="codFabricante" autofocus placeholder="Código do Fabricante."  value={$codFabricante} >
                    </div>
                    <div class="form-group col-md-6 col-sm-8 col-xs-12">
                        <label>Descri&ccedil;&atilde;o</label>
                        <input class="form-control" id="produtoNome" name="produtoNome"  placeholder="Digite a descrição."  value="{$produtoNome}" >
                    </div>
                    
                </form>
            
                </div>

            </div> <!-- x_panel -->
                          
        </div>  <!-- div row = painel principal-->



        <!-- panel tabela dados -->  
         <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <table id="datatable-buttons1" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>C&oacute;digo</th>
                            <th>C&oacute;digo Fabricante</th>
                            <th>C&oacute;d. Nota</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Unidade</th>
                            {if $tipoCategoriaAtendimento == 'V'}
                            <th>Preço Venda</th>
                            {else }
                            <th>Preço Custo</th>
                            {/if}
                            <th style="width: 80px;">Selecionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> 
                                    {$lanc[i].CODIGO}
                                </td>
                                <td> {$lanc[i].CODFABRICANTE}</td>
                                <td> {$lanc[i].CODPRODUTONOTA}
                                         
                                </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td>
                                {if $tipoCategoriaAtendimento == 'V'}
                                    {$lanc[i].VENDA|number_format:2:",":"."} 
                                        
                                {else }
                                    {$lanc[i].CUSTOCOMPRA|number_format:2:",":"."} 
                                        
                                {/if}
                                </td>
                                
                                <td class=" last">
                                    <button type="button" class="btn btn-success btn-xs" 
                                    onclick="javascript:fechaProdutoPesquisaAtendimento(this);">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                </td>
                            </tr>
                        {/section} 
                    </tbody>
                </table>

              </div> 
            </div>
                
                                                        
                









            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true,
         allowZero: true
        });        
     });
    </script> 