<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<div id=total>
<script type="text/javascript" src="{$pathJs}/est/s_baixa_estoque.js"> </script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 id="msgTpl">Movimentação de Estoque - Consulta
                            {if $msg neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>Sucesso!</strong>&nbsp;{$msg}</div>
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
                                                <strong>Aviso!</strong>&nbsp;{$msg}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmarMovCc();">
                                <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                         <ul class="dropdown-menu" role="menu">
                              <li><button type="button" class="btn btn-warning btn-xs" onClick="javascript:limpaDadosForm();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span> Limpar Dados Formulário</span>
                                    </button>
                              </li>
                              <li><button type="button" class="btn btn-warning btn-xs" onClick="javascript:romaneio_mov_est_cc_imprime();">
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span> Romaneio</span>
                                    </button>
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
                          class="form-horizontal form-la<div id=total>el-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod             type=hidden value="est">   
                            <input name=form            type=hidden value="baixa_estoque">   
                            <input name=id              type=hidden value="">
                            <input name=opcao           type=hidden value="{$opcao}">
                            <input name=letra           type=hidden value="{$letra}">
                            <input name=submenu         type=hidden value="{$subMenu}">
                            <input name=pessoa          type=hidden value={$pessoa}>
                            <input name=fornecedor      type=hidden value={$fornecedor}>
                            <input name=codProduto      type=hidden value={$codProduto}>
                            <input name=unidade         type=hidden value={$unidade}>
                            <input name=descProduto     type=hidden value={$descProduto}>
                            <input name=valorVenda      type=hidden value={$valorVenda}> 
                            <input name=uniFracionada   type=hidden value="{$uniFracionada}">
                            <input name=pesq            type=hidden value={$pesq}>
                            <input name=quantAtual      type=hidden value={$quantAtual}>
                            <input name=novaQtdeEstoque type=hidden value={$novaQtdeEstoque}>
                            <input name=dadosLanc       type=hidden value={$dadosLanc}>
                            <input name=idEntrada       type=hidden value={$idEntrada}>
                            <input name=idSaida         type=hidden value={$idSaida}>
                            <input name=idCCEntrada     type=hidden value={$idCCEntrada}>
                            <input name=idCCSaida       type=hidden value={$idCCSaida}>
                            <input name=produto         type=hidden value={$produto}>
                            <input name=quantidade      type=hidden value={$quantidade}>
                            <input name=conta           type=hidden value={$conta}>
                            <input name=genero          type=hidden value={$genero}>
                            <input name=obsNf           type=hidden value={$obsNf}>
                            <input name=modeloNota      type=hidden value={$modeloNota}>
                            <input name=serieNota       type=hidden value={$serieNota}>
                            <input name=idPedido        type=hidden value={$idPedido}>
                            <input name=ccEntrega       type=hidden value={$ccEntrega}>       

                        <div class="form-group">
                            <div class="col-lg-6 col-sm-10 col-xs-10 text-left">
                                <label>Produto</label>
                                <div class="input-group">
                                    <input READONLY      
                                    class="form-control" placeholder="Produto" id="pesProduto" 
                                    name="pesProduto" value="{$pesProduto}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=baixa_estoque');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Quantidade</label>
                                <input class="form-control money" id="qtdeEntrada" name="qtdeEntrada" placeholder="0,00" onchange="javascript:calculaQuantidadeProduto();"  value={$qtdeEntrada} >
                            </div>

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Modelo</label>
                                <input class="form-control" id="modelo" readonly name="modelo" maxlength="2" placeholder="Modelo Nota Fiscal."  value={$modelo} >
                            </div>

                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Série NF</label>
                                <input class="form-control" id="serieNf" readonly name="serieNf" maxlength="3" placeholder="Série Nota Fiscal."  value={$serieNf} >
                            </div>

                        </div>
                       
                        <div class="form-group">
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Centro de Custo Origem</label>
                                <SELECT class="js-example-basic-single form-control" name="centroCustoOrigem" id="centroCustoOrigem"> 
                                    {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCustoOrigem}
                                </SELECT>
                            </div>
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Centro de Custo Destino</label>
                                <SELECT class="js-example-basic-single form-control" name="centroCustoDestino" id="centroCustoDestino"> 
                                    {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCustoDestino}
                                </SELECT>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <label class="">Conta</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id="nome" name="nome" placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="genero" >G&ecirc;nero</label>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="descgenero" name="descgenero" placeholder="Genero" required="required"
                                           value="{$descGenero}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                            
                            <!--div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Num Docto</label>
                                <input class="form-control" id="numDocto" name="numDocto" maxlength="11" placeholder="Numero do Docto."  value={$numDocto} >
                            </div-->
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="desc">Observações</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3" >{$obs}</textarea>
                            </div>  
                        </div>
                        <!-- MODAL PRODUTO ENCOMENDA -->
                        {include file="modal_produto_encomenda.tpl"}
                    </form>
                  </div>

                </div> <!-- x_panel -->
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->
        </div> <!-- div  "-->
        </div> <!-- div role=main-->


{include file="template/database.inc"}  
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<!-- select2 -->
<script> 
$("#centroCustoOrigem.js-example-basic-single").select2({
    placeholder: "Selecione o Centro de Custo",
    allowClear: true
});

$("#centroCustoDestino.js-example-basic-single").select2({
    placeholder: "Selecione o Centro de Custo",
    allowClear: true
});
</script>

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>

<script>
$(document).ready(function(){
   $(".money").maskMoney({
    decimal: ",",
    thousands: "."
   });
});
</script>
    
   