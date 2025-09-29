<section class="height100">
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
          {if $retorno neq 'nota_fiscal'}
              <div class="page-title">
                <div class="title_left">
                    <h3>Nota Fiscal</h3>
                </div>
              </div>
          {/if}
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
  
          </form>
  
        {if $danfe neq ''}
        
        <div id="print" class="col-md-12 col-sm-12 col-xs-12 myIframe" >
            <iframe src="{$danfe}"></iframe>
            <button type="button" class="btn btn-success boleto"  
             onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra=|{$numPedido}|PED');">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span > Boleto </span></button>
        </div>
        
            
       {/if} 
        </div>
  
    </section>
  
  <style>
      .titleImprimi{
        padding: 0;
        margin-left: -450px;
      }
      .height100 {
        height: 100vh;
        background-color: #F7F7F7;
        margin-top: 0;
        margin-bottom: 0;
        padding: 0;
    }
    .myIframe {
    position: static;
    height: 0;
    overflow: auto;
    -webkit-overflow-scrolling: touch; /*<<--- THIS IS THE KEY*/ 
  }
  
  .myIframe iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
  .boleto{
    position: fixed;
    width: 150px;
    margin-top: 14px;
    margin-left: 70%;
  }
  </style>
  
      