        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>CUPOM FISCAL</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="pdv">
            <input name=form          type=hidden value="cupom">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>
            <input name=cliente       type=hidden value={$cliente}>
            <input name=cpf           type=hidden value="{$cpf}">


            
            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $mensagem neq ''}
                                <div class="alert alert-danger small" role="alert">&nbsp;{$mensagem}</div>
                        {/if}
                    </h2>

                    {if $danfe eq ''}
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-info" onClick="javascript:submitCadastraNfRecibo({$id});">
                            <!--onclick="javascript:abrir('index.php?mod=pdv&form=cupom_recibo&opcao=imprimir&parm={$id}');"-->
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span><span> Recibo</span></button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastraNf({$id});">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitVoltar({$id});">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                    </ul>
                    {else}  
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitVoltar({$id});">
                                <span class="fa fa-file-text" aria-hidden="true"></span><span> Novo Cupom</span></button>
                        </li>
                    </ul>
                    {/if}            
                    <div class="clearfix"></div>
                  </div>
                    <div class="x_content small" {if $danfe neq ''} style="display: none" {/if}>
                    <div class="row">
                        <h4>
                        <div class="col-md-4 col-sm-2 col-xs-2">
                            <label for="data">Valor Pago:</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control" id="valorPago" name="valorPago" 
                                       {if $danfe neq ''} disabled {/if}
                                       onchange="javascript:calculaTotal();" value={$valorPago}>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-4">
                            <label for="modo">Pagar em:</label>
                            <div class="panel panel-default">
                                <select name="modo" class="form-control" 
                                       {if $danfe neq ''} disabled {/if}
                                        >
                                    {html_options values=$modo_ids selected=$modo_id output=$modo_names}
                                </select>
                            </div>
                        </div>
                        </h4>    
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="numItens">Num Itens</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="numItem" name="numItens" readonly value={$numItem}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="total">Valor Itens</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="total" name="totalPedido" readonly value={$totalPedido}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="total">Desconto</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="desconto" name="desconto" 
                                       {if $danfe neq ''} disabled {/if} onchange="javascript:calculaTotal();"
                                       value={$desconto}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="total">Taxa</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="taxa" name="taxa" 
                                       {if $danfe neq ''} disabled {/if} onchange="javascript:calculaTotal();"
                                       value={$taxa}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="totalCupom">Total CUPOM</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="totalCupom" name="totalCupom" readonly value={$totalCupom}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-2 col-xs-2 ">
                            <label for="troco">Troco</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="troco" name="troco" readonly value={$troco}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12 ">
                            <label for="troco">Informa&ccedil;&otilde;es Adicionais</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="4"  
                                    {if $danfe neq ''} disabled {/if} > {$obs}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                                
                        
              </div>
            </div>
        </form>

      {if $danfe neq ''}
      
      <div id="print" class="col-md-6 col-sm-12 col-xs-12" >
          
                <iframe src="{$danfe}" style="width:550px; height:500px;">
                <!--iframe src="http://docs.google.com/gview?url={$danfe}&embedded=true"
                  style="width:550px; height:500px; border: none;" frameborder="0" onLoad="self.print(); window.close()">  -->
                <!--iframe src="../../../admti\nfe\homologacao\pdf\201705\41170509112859000167650010000000971000000971-danfce.pdf"
                  style="width:550px; height:500px;" > 
                  <!--style="width:550px; height:500px;" onLoad="self.print(); window.close()"--> 
              </iframe>
      </div>    
     {/if} 
      </div>

    {include file="template/form.inc"}  

    