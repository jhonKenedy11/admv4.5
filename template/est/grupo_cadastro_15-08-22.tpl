<!--<script type="text/javascript" src="{$pathJs}/est/s_grupo.js"> </script> -->
<script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
            <h3>Grupos</h3>
          </div>
        </div>
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="est">   
            <input name=form          type=hidden value="grupo">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=grupoBase     type=hidden value={$grupoBase}>
            <input name=nivel         type=hidden value={$nivel}>

            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                        {include file="../bib/msg.tpl"}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="submit" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmar();">
                          <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar();">
                          <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                      
                    <form class="container" novalidate="" action="/echo" method="POST" id="myForm">

                      {if $subMenu eq "cadastrar"}
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Grupo Base<span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" maxlength="15" name="grpBase" disabled value={$grupoBase}>
                          </div>
                        </div>
                      {/if}    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">C&oacute;digo do Grupo <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" maxlength="15" required="true" id="id" 
                                   name="id" {if $subMenu eq "alterar"} readonly {/if} 
                                   placeholder="Digite o código do grupo." value={$id}>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descricao">Descri&ccedil;&atilde;o <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" maxlength="40" required="true" id="descricao" name="descricao" placeholder="Digite a descrição." value={$descricao}>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipo">Tipo <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="tipo" class="form-control">
                                  {html_options values=$tipoGrupo_ids selected=$tipo output=$tipoGrupo_names}
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nivel">N&iacute;vel <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <input class="form-control" type="text" disabled id="nvl" name="nvl" value={$nivel}>
                          </div>
                        </div>

                        <div class="ln_solid"></div>

                      </form>

                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}
    
<style type="text/css">

.form-control:focus {
    border-color: #159ce4;
    transition: all 0.7s ease;
}

</style>
