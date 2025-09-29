<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_inventario.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">

            <div class="row">

            <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Inventario Produto - Consulta
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                    <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraCadastroInventario()">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisar</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('alterar')">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span>
                            </button>
                        </li>                        
                        {if $btnAddInventario == true}
                            <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmarProdutoInventario();">
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Adicionar ao Inventario</span>
                                </button>
                            </li>
                        {/if}
                        
                        {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                         <ul class="dropdown-menu" role="menu">
                              <li><button type="button" class="btn btn-warning btn-xs" onClick="javascript:limpaDadosForm();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span> Limpar Dados Formulário</span>
                                    </button>
                              </li> *}
                         </ul>
                        </li>
                        <!-- <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> -->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="inventario">   
                            <input name=id            type=hidden value="{$id}">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=codProduto    type=hidden value={$codProduto}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=descProduto   type=hidden value={$descProduto}>
                            <input name=quantAtual    type=hidden value="{$quantAtual}">
                            <input name=valorVenda    type=hidden value={$valorVenda}> 
                            <input name=uniFracionada type=hidden value="{$uniFracionada}">
                            <input name=pesq          type=hidden value={$pesq}>
                            <input name=grupoSelected type=hidden value="">
                            <input name=dadosInventario type=hidden value="">
                            <input name=tela          type=hidden value={$tela}>
                            <input name="from" type="hidden" value="{$from}">
                            
                            

                        
                        <div class="form-group line-formated">
                            <div class="col-lg-6 col-sm-10 col-xs-10 text-left line-formated">
                                <label>Produto</label>
                                <div class="input-group line-formated">
                                    <input READONLY      
                                    class="form-control" placeholder="Produto" id="pesProduto" 
                                    name="pesProduto" value="{$pesProduto}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=baixa_estoque', 'produto');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-sm-12 col-xs-12">
                               <label>Grupo</label>
                                <SELECT class="select2_multiple form-control" multiple="multiple" id="grupo" name="grupo"> 
                                    {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                </SELECT>
                            </div>
                            <div class="form-group col-md-3 col-sm-3 col-xs-3">
                                <label>Localizacao</label>
                                <input class="form-control" id="localizacao" name="localizacao" placeholder="Localizacao Produto" value={$localizacao} >
                            </div>

                        </div>
                                    

                        
                        
                        
                    </form>
                  </div>

                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th style="width:80px;">Codigo</th>
                                <th>Produto</th>
                                <th style="width:200px;">Grupo</th>
                                <th style="width:100px;">Preco Custo</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].CODIGO} </td>
                                    <td> {$lanc[i].DESCPRODUTO} </td>
                                    <td> {$lanc[i].DESCGRUPO} </td>
                                    <td> {$lanc[i].CUSTOCOMPRA|number_format:2:",":"."} </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                    
                </div> <!-- x_panel -->

                
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->



        
        
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>    
    <!-- select2 -->
    <script>

        $("#grupo.select2_multiple").select2({
          placeholder: "Selecione o Grupo"
        });


    </script>

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>

     $(document).ready(function(){
        $(".money").maskMoney({
         decimal: ",",
         thousands: ".",
         allowNegative: true
        });
    });
    </script>
    <style>
        .line-formated{
            margin-bottom: 1px;
        }
    </style>

   