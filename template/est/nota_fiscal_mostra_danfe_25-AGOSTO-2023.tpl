<!-- Include js  -->
{if $retorno eq 'pedido_venda_nf'}
  <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_nf.js"></script>
{/if}
{if $retorno eq 'pedido_venda_gerente'}
  <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente.js"></script>
{/if}
{if $retorno eq 'pedido_venda_gerente_novo'}
  <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente_novo.js"></script>
{/if}
{if $retorno eq 'nota_fiscal'}
  <script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"></script>
{/if}
{if $retorno eq 'atendimento_nf'}
  <script type="text/javascript" src="{$pathJs}/cat/s_atendimento_nf.js"></script>
{/if}
<!-- script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_imprime_danfe.js"> </script>

script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente.js"> </script-->

<!-- page content -->
<div class="right_col" role="main">      
    <div class="small">

        <div class="page-title">
          <div class="title_left">
              <h3>Nota Fiscal</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="">   
            <input name=form                type=hidden value="">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=idnf                type=hidden value={$id}>
            <input name=fornecedor          type=hidden value={$id}>
            <input name=pessoa              type=hidden value={$pessoa}>   
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=centroCusto         type=hidden value={$filial_id}>   
            <input name=retorno             type=hidden value={$retorno}>   

           
            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $mensagem neq ''}
                                <div class="alert alert-danger small" role="alert">&nbsp;{$mensagem}</div>
                        {/if}
                    </h2>

                    
                    <ul class="nav navbar-right panel_toolbox">
                        {if $retorno eq 'atendimento_nf'}
                          <li><button type="button" class="btn btn-primary"  onClick="javascript:submitVoltarCadAtendimentoNf('{$numPedido}');">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span> Voltar</span></button>
                          </li>
                        {else}
                          <li><button type="button" class="btn btn-primary"  onClick="javascript:submitVoltar('');">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span> Voltar</span></button>
                          </li>
                        {/if}
                        
                        <li><button type="button" class="btn btn-success"  
                             onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra=|{$numPedido}|PED');">
                            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Boleto </span></button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                </div>
                                
                        
              </div>
            </div>
        </form>

      {if $danfe neq ''}
      
      <div id="print" class="col-md-6 col-sm-12 col-xs-12" >
          
                <iframe src="{$danfe}" style="width:900px; height:1000px;">
              </iframe>
      </div>    
     {/if} 
      </div>

    {include file="template/form.inc"}  

    