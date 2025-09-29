<style>
.tableProd{
    border: 3px solid #a3a3a3 !important;
}
.x_panel{
  border-radius: 5px !important;
}
#divergencia{
  text-align:center;
  height: 36px;
  border-radius: 5px;
}
h5 {
  display: inline-block;
  margin: 0 auto;
  color: #fff;
  font-weight: bold;
  animation: mover 2s ease-in-out infinite;
  background-color: #030303;
  padding: 3px;
  border-radius: 10px;
  width: 120px;
  position: relative;
  top: 4px;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 2px;
    background-color: rgba(255, 179, 194, 0.796);
}
.alert{
    font-weight: bold;
    font-size: 14px;
}
.right_col{
  padding: 6px !important;
}
/* Estilo padrão para o campo editável */
#codProd {
  padding: 5px !important;
  border: 1px solid #ccc;
}

/* Estilo para destacar o campo quando o usuário passar o mouse sobre ele */
#codProd:hover {
  border: 2px solid rgb(62, 83, 245); /* Por exemplo, uma borda vermelha para destacar */
}
.caixa-cor, .legenda1, .legenda2, .legenda3 {
  display: inline-block;
  vertical-align: middle;
  margin-right: -1px;
}

.caixa-cor {
  width: 14px;
  height: 14px;
}


/* Estilos adicionais para a legenda, se necessário */
.legenda1 span, .legenda2 span, .legenda3 span,{
  font-size: 12px;
}
@keyframes mover {
  0% {
      transform: scale(1);
  }
  50% {
      transform: scale(1.08);
  }
  100% {
      transform: scale(1);
  }
}
#bnt_cadastrar{
  display: none;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_xml_importa.js"> </script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
            <h3></h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form name = "upload" method="post" action={$SCRIPT_NAME} enctype="multipart/form-data">
        
            <input name=mod              type=hidden value="est">   
            <input name=form             type=hidden value="nota_xml_importa">  
            <input name=opcao            type=hidden value="">   
            <input name=submenu          type=hidden value={$subMenu}>
            <input name=letra            type=hidden value={$letra}>
            <input name=imagem           type=hidden value={$imagem}>
            <input name=url              type=hidden value={$url}>
            <input name=f_name           type=hidden value={$f_name}>
            <input name=f_type           type=hidden value={$f_type}>  
            <input name=f_tmp            type=hidden value={$f_tmp}>
            <input name=nota_fiscal_div  type=hidden value={$nota_fiscal_div}>
            <input name=existeNotaFiscal type=hidden value={$existeNotaFiscal}>
            <input name=param            type=hidden value={$param}>
            <input name=idNf             type=hidden value={$idNf}>
            <textArea id=xml_arq name=xml_arq style="display:none" >{$xml_arq}</textArea>

            <div class="row" id="cabecalho">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        Importa nota fiscal por XML
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox" id="btnsAcao">
                          <li><button id="btnVisualiza" type="button" class="btn btn-primary"  onClick="javascript:submitVisualizar();">
                            <span class="glyphicon glyphicon-list" aria-hidden="true"></span><span>&nbsp;&nbsp;Visualizar XML</span></button>
                          </li>
                        
                          <li><button {if $xml_arq eq ''} style="display:none" {/if} id="btnAddXml" type="button" class="btn btn-danger"  onClick="javascript:submitAddXml();">
                            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span><span>&nbsp;&nbsp;Adicionar novo XML</span></button>
                          </li>
                          
                          <li><button {if $xml_arq eq '' or $existeNotaFiscal eq '1'} style="display:none" {/if} id="btnValidar" type="button" class="btn btn-warning"  onClick="javascript:submitValidar();">
                            <span style="color:rgb(72, 72, 72);" class="glyphicon glyphicon-retweet" aria-hidden="true"></span><span style="color:rgb(72, 72, 72);">&nbsp;&nbsp;Validar</span></button>
                          </li>

                          {* <li><button {if $xml_arq neq '' and $existeNotaFiscal eq '1' or $existeNotaFiscal eq null} style="display:none" {/if} type="button" id="bnt_cadastrar" class="btn btn-success" onClick="javascript:submitCadastrar();">
                            <span style="color:rgb(72, 72, 72);" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span style="color:rgb(72, 72, 72);">&nbsp;&nbsp;Cadastrar</span></button>
                          </li> *}
                        <li><button type="button" id="bnt_cadastrar" class="btn btn-success" onClick="javascript:submitCadastrar();">
                          <span style="color:rgb(72, 72, 72);" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span style="color:rgb(72, 72, 72);">&nbsp;&nbsp;Cadastrar</span></button>
                        </li>
                        <li><a class="collapse-link"><i name='btnCollapse' class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>

                    {include file="../bib/msg.tpl"}

                    <div class="clearfix"></div>
                    
                    
                  </div>
                  <div class="x_content">

                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <label for="idNatOp">Natureza Opera&ccedil;&atilde;o</label>
                        </br>
                        <div class="input-group">
                            <select class="form-control form-control-sm" name=idNatOp style="border-radius: 5px;">
                                {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                            </select>
                        </div>
                    </div>
                      <div class="form-group">
                          <label class="form-label col-md-3 col-sm-3 col-xs-12" for="input-file">Arquivo XML <span class="required"></span>
                          </label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
				                    <input class="form-control custom-file-input" id="input-file" type="file" placeholder="Escolha um arquivo" 
                            size="100" name="file" style="border-radius: 5px;" value={$tempFile}>                         
				                    {* <input type="text" size="60" name="tempFile" > *}
                            
                        </div>
                      </div>
                                           
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  
    
