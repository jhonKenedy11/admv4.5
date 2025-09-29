<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
.x_title {
    padding: -10px 0;
    min-height: auto;
}
.x_title h2 {
    margin: 0;
    font-size: 18px;
}
.alert {
    padding: 8px 12px;
    margin: 5px 0;
    font-size: 12px;
    line-height: 1.2;
}
.alert strong {
    font-size: 12px;
}
.table th.preco-custo-col, .table td.preco-custo-col,
.table th.quantidade-col, .table td.quantidade-col {
    max-width: 50px;
    text-align: center;
    vertical-align: middle;
}
.table th.preco-custo-col input,
.table td.preco-custo-col input,
.table th.quantidade-col input,
.table td.quantidade-col input {
    text-align: center;
    max-width: 80px;
    margin: 0 auto;
    display: block;
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
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <h2>{$referencia}</h2>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('')">
                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-3">
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
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                            </div>
                        </div>
                  </div>

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="inventario">   
                            <input name=id            type=hidden value="{$id}">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=pessoa        type=hidden value={$pessoa}>
                            <input name=fornecedor    type=hidden value={$fornecedor}>
                            <input name=codProduto    type=hidden value={$codProduto}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=descProduto   type=hidden value={$descProduto}>
                            <input name=valorVenda    type=hidden value={$valorVenda}> 
                            <input name=uniFracionada type=hidden value="{$uniFracionada}">
                            <input name=pesq          type=hidden value={$pesq}>
                            <input name=tela          type=hidden value={$tela}>
                            <input name=gerarInventario      type=hidden value="">
                            <input name=idInventarioProduto  type=hidden value="">
                            <input name=dadosInventario      type=hidden value="">
                            <input name=dadosInventarioSaida type=hidden value="">
                            <input name=dadosInventarioEnt   type=hidden value="">
                            <input name=tituloImg   type=hidden value="">
                    </form>
                </div> <!-- x_panel -->
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->


        <div class="x_panel small">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <!-- Botão Pesquisar Itens à esquerda -->
                <div>
                    {if $status neq 'B'}
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalPesquisarItens">
                            <span class="glyphicon glyphicon-search"></span> Pesquisar Itens
                        </button>
                    {/if}
                </div>
                <!-- Botões à direita -->
                <div>
                    <ul class="nav navbar-right panel_toolbox" style="display: flex; gap: 8px; margin: 0;">
                        {if $status neq 'B'}
                            {if $btnAddInventario == true}
                                {if $gerarInventario neq 'disabled' && $gerarInventario neq 'false'}
                                    <li>
                                        <button type="button" class="btn btn-primary" onClick="javascript:ajaxGerarInventario();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Gerar Inventário</span>
                                        </button>
                                    </li>
                                {/if}
                                {if $lancProduto|@count > 0}
                                    <li>
                                        <button type="button" class="btn btn-info" onClick="javascript:submitAlteraProdutoInventario();">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span> Confirmar Alteração</span>
                                        </button>
                                    </li>
                                {/if}
                            {/if}
                        {/if}
                    </ul>
                </div>
            </div>
            {if $gerarInventario eq 'disabled'}
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr class="headings">
                            <th>Cod Produto</th>
                            <th>Produto</th>
                            <th>Grupo</th>
                            <th>Estoque</th>
                            <th>Quantidade</th>
                            <th>Movimentada</th>
                            <th>Preco Custo</th>
                            <th>Manutenção</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$lancProduto}
                        {assign var="total" value=$total+1}
                        <tr class="even pointer">
                            <td> {$lancProduto[i].CODPRODUTO} </td>
                            <td> {$lancProduto[i].DESCPRODUTO} </td>
                            <td> {$lancProduto[i].DESCGRUPO} </td>
                            <td class="quantidade-col"> {$lancProduto[i].ESTOQUE|number_format:2:",":"."} </td>
                            <td class="quantidade-col"> {$lancProduto[i].QUANTIDADENOVA|number_format:2:",":"."} </td>
                            <td class="quantidade-col"> {$lancProduto[i].QUANTIDADEMOVIMENTADA|number_format:2:",":"."} </td>
                            <td class="preco-custo-col"> {$lancProduto[i].PRECOCUSTONOVO|number_format:2:",":"."} </td>
                            
                            <td class=" last">
                                <button type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=est&form=rel_inventario&opcao=imprimir&id={$id}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>  
                                <button type="button" class="btn btn-primary btn-xs" title="Cadastrar imagem" onclick="javascript:submitCadastrarImagem('{$lancProduto[i].ID}','{$lancProduto[i].DESCPRODUTO}');"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></button>
                            </td>
                        </tr>
                        {/section} 
                    </tbody>
                </table>
            {else}
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr class="headings">
                            <th style="max-width: 15px !important"><input type="checkbox" id="checkAll"></th>
                            <th style="max-width: 37px !important">Cod Produto</th>
                            <th style="max-width: 180px !important">Produto</th>
                            <th style="max-width: 90px !important">Grupo</th>
                            <th style="max-width: 50px !important">Estoque</th>
                            <th style="max-width: 60px !important">Quantidade</th>
                            <th style="width: 50px !important">Movimentada</th>
                            <th style="max-width: 20px !important">Preco Custo</th>
                            <th class="invis">unidade produto</th>
                            <th class="invis">unidade fracionada</th>
                            <th style="min-width: 100px !important">Manutenção</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$lancProduto}
                        {assign var="total" value=$total+1}
                        <tr class="even pointer">
                            <td><input type="checkbox" name="prodChecked" id="{$lancProduto[i].ID}"></td>
                            <td style="text-align: center;"> {$lancProduto[i].CODPRODUTO} </td>
                            <td> {$lancProduto[i].DESCPRODUTO} </td>
                            <td> {$lancProduto[i].DESCGRUPO} </td>
                            <td style="text-align: center;"> {$lancProduto[i].ESTOQUE|number_format:2:",":"."} </td>
                            <td class="quantidade-col"> 
                            <input class="form-control input-sm money input-width"
                                        id="quantNova{$lancProduto[i].ID}" name="quantidade{$lancProduto[i].ID}"
                                tabindex="{$smarty.section.i.index*2+1}"
                                value={$lancProduto[i].QUANTIDADENOVA|number_format:2:",":"."} 
                                onChange="javascript:checkProduto({$lancProduto[i].ID})"
                                >
                            </td>
                            <td style="text-align: center;"> {$lancProduto[i].QUANTIDADEMOVIMENTADA|number_format:2:",":"."} </td>
                            <td class="preco-custo-col"> 
                                <input class="form-control col-md-2 col-xs-2 input-sm money"
                                        id="precoCustoNovo{$lancProduto[i].ID}" name="precoCusto{$lancProduto[i].ID}"
                                tabindex="{$smarty.section.i.index*2+2}"
                                value={$lancProduto[i].PRECOCUSTONOVO|number_format:2:",":"."} 
                                onChange="javascript:checkProduto({$lancProduto[i].ID})"
                                >
                            </td>
                            
                            <td class="invis"> {$lancProduto[i].CODPRODUTO} </td>
                            <td class="invis"> {$lancProduto[i].UNIDADE} </td>
                            <td class="invis"> {$lancProduto[i].UNIFRACIONADA} </td>
                            <td class=" last">
                                <button type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=est&form=rel_inventario&opcao=imprimir&id={$id}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>  
                                <button type="button" class="btn btn-primary btn-xs" title="Cadastrar imagem" onclick="javascript:submitCadastrarImagem('{$lancProduto[i].ID}','{$lancProduto[i].DESCPRODUTO}');"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></button>
                                <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lancProduto[i].ID}');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                            </td>
                        </tr>
                        {/section} 
                    </tbody>
                </table>
            {/if}    
            
        </div>
        
          </div>
        </div>



    {include file="template/database.inc"}  
    
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>

     $(document).ready(function(){
        $(".money").maskMoney({
         decimal: ",",
         thousands: ".",
         allowNegative: false,
         allowZero: true
        });
    });
    </script>
    <style>
        .line-formated{
            margin-bottom: 1px;
        }
        .invis {
            display:none;
        }
    </style>

    <!-- Modal de Pesquisa de Itens -->
    {include file="inventario_produto_modal.tpl"}
   
<script>
$(document).ready(function(){
  $('#modalPesquisarItens').modal({
    backdrop: 'static',
    keyboard: false,
    show: false
  });
  $('#checkAll').on('change', function() {
    var checked = $(this).is(':checked');
    $('input[name="prodChecked"]').prop('checked', checked);
  });
});
</script>
   