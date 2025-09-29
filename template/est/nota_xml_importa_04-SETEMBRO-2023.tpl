<script type="text/javascript" src="{$pathJs}/est/s_nota_xml_importa.js"> </script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
            <h3>Nota Fiscal</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form name = "upload" method="post" action={$SCRIPT_NAME} enctype="multipart/form-data">
        
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="nota_xml_importa">  
            <input name=opcao               type=hidden value="">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=letra               type=hidden value={$letra}>
            <input name=imagem              type=hidden value={$imagem}>
            <input name=url                 type=hidden value={$url}>
            <input name=f_name              type=hidden value={$f_name}>
            <input name=f_type              type=hidden value={$f_type}>  
            <input name=f_tmp               type=hidden value={$f_tmp}>  
            <textArea id=xml_arq name=xml_arq style="display:none" >{$xml_arq}</textArea>  

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        Importa Nota Fiscal Arquivo XML
                         <strong>
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
                        </strong>
                    </h2>
                    
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitVisualizar();">
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span><span> Visualizar</span></button>
                        </li>
                        {if $cadastrar eq ''}
                            <li><button type="button" class="btn btn-danger"  onClick="javascript:submitCadastrar();">
                                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span><span> Cadastrar</span></button>
                            </li>
                        {/if}
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

                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <label for="idNatOp">Natureza Opera&ccedil;&atilde;o</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm" name=idNatOp>
                                {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                            </select>
                        </div>
                    </div>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="file">Arquivo XML <span class="required"></span>
                          </label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
				                    <input type="file" size="100" name="file">                            
				                    <input type="text" size="60" name="tempFile" value={$tempFile}>
                            
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
    
