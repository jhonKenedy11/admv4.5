<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_entrega.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="{$mod}">   
            <input name=form          type=hidden value="{$form}">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>
            {if $subMenu eq "alterar"}  
                <input name=id            type=hidden value={$id}> 
            {/if}


            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pedido - 
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                        de Nota fiscal e Financeiro
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert"><strong>Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>

                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar('');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                      {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="serie">Serie</label>
                                <div class="panel panel-default">
                                    <input class="form-control" type="text" id="serie" name="serie" value={$serie}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="cfop">CFOP</label>
                                <div class="panel panel-default">
                                    <input class="form-control" type="text" maxlength="15" id="cfop" name="cfop" value={$cfop}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="natOperacao">Natureza Operação</label>
                                <div class="panel panel-default">
                                    <input class="form-control" type="text" maxlength="40" id="natOperacao" name="natOperacao" value={$natOperacao}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="condPgto">Condição de Pagamento</label>
                                <div class="panel panel-default">
                                    <select name="condPgto" class="form-control">
                                        {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="genero">Genero</label>
                                <div class="panel panel-default">
                                    <select name="genero" class="form-control">
                                        {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 text-left">
                                <label for="conta">Conta</label>
                                <div class="panel panel-default">
                                    <select name="conta" class="form-control">
                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                    </select>
                                </div>
                            </div>
                        </div>

                      <div class="ln_solid"></div>
                        
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  
