<style>
  .x_panel {
    border-radius: 5px;
  }

  .height100 {
    height: 100vh;
    background-color: #fbfbff;
    margin-top: 0;
    margin-bottom: 0;
    padding: 0;
  }

  {if $danfe eq ''}
    .right_col, .container{
      background-color: #2e2e31 !important;
    }
    .height100 {
      height: 100vh;
      background-color: #2e2e31 !important;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
    }
  {/if}

  #top {
    background-color: #F7F7F7 !important;
  }

  @keyframes tipsy {
    0 {
      transform: translateX(-50%) translateY(-50%) rotate(0deg);
    }

    100% {
      transform: translateX(-50%) translateY(-50%) rotate(360deg);
    }
  }

  body {
    font-family: helvetica, arial, sans-serif;
    background-color: #2e2e31;
  }

  p {
    color: #fffbf1;
    text-shadow: 0 20px 25px #2e2e31, 0 50px 60px #2e2e31;
    font-size: 40px;
    font-weight: bold;
    text-decoration: none;
    letter-spacing: -3px;
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
  }

  p:before,
  p:after {
    content: '';
    padding: .9em .4em;
    position: absolute;
    left: 50%;
    width: 115%;
    top: 50%;
    display: block;
    border: 7px solid red;
    transform: translateX(-50%) translateY(-50%) rotate(0deg);
    animation: 10s infinite alternate ease-in-out tipsy;
  }

  p:before {
    border-color: #d9524a #d9524a rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
    z-index: -1;
  }

  p:after {
    border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) #d9524a #d9524a;
  }
  .right_col{
    min-height: 1px !important;
  }
</style>

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

  {if $danfe eq ''}
    <p>Nota Fiscal não localizada!</p>
  {else}
    <!-- page content -->
    <div class="right_col" role="main">
      <div class="small">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
          ACTION="{$SCRIPT_NAME}" METHOD="post">
          <input name=mod type=hidden value="">
          <input name=form type=hidden value="">
          <input name=submenu type=hidden value={$subMenu}>
          <input name=id type=hidden value={$id}>
          <input name=idnf type=hidden value={$id}>
          <input name=fornecedor type=hidden value={$id}>
          <input name=pessoa type=hidden value={$pessoa}>
          <input name=letra type=hidden value={$letra}>
          <input name=opcao type=hidden value={$opcao}>
          <input name=centroCusto type=hidden value={$filial_id}>
          <input name=retorno type=hidden value={$retorno}>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              {if $origem !== 'imprimeDanfe'}
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                      {if $mensagem neq ''}
                        <div class="alert alert-danger small" role="alert">&nbsp;{$mensagem}</div>
                      {/if}
                    </h2>


                    <ul class="nav navbar-right panel_toolbox">
                      {if $retorno eq 'atendimento_nf'}
                        <li><button type="button" class="btn btn-primary"
                            onClick="javascript:submitVoltarCadAtendimentoNf('{$numPedido}');">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span>
                              Voltar</span></button>
                        </li>
                      {else}
                        <li><button type="button" class="btn btn-primary" onClick="javascript:submitVoltar('');">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span><span>
                              Voltar</span></button>
                        </li>
                      {/if}

                      <li><button type="button" class="btn btn-success"
                          onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra=|{$numPedido}|PED');">
                          <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Boleto
                          </span></button>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                </div>
              </div>
            </div>
            <!--col -->
          {/if}
      </div>
    </div>
    </form>

    {if $danfe neq ''}
      <div id="print" class="col-md-6 col-sm-12 col-xs-12">

        <iframe src="{$danfe}" style="width:900px; height:1000px;">
        </iframe>
      </div>
    {/if}
    </div>
  {/if}
  {include file="template/form.inc"}
<section>