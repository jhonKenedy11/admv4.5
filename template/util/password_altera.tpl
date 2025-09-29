<script type="text/javascript" src="{$pathJs}/util/s_util.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>

        <form id="password" data-parsley-validate class="form-horizontal form-label-left" NAME="password"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="util">   
            <input name=form                type=hidden value="password">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value="password">

            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                            Altera&ccedil;&atilde;o de Senha
                        {if $mensagem neq ''}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-warning" role="alert">{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                        {/if}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmarSenha();">
                                <span class="glyphicon glyphicon-check" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Senha Antiga
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="senhaAnt" name="senhaAnt" type="password" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Digite a sua senha antiga.">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Nova Senha
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="novaSenha" name="novaSenha" type="password" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Digite a nova senha.">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Confirmação Senha
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="confirmacaoSenha" name="confirmacaoSenha" type="password" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Redigite a sua nova senha para confirmação.">
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
                    