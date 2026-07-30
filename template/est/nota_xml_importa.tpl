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
/* Estilo padrão para o campo editável de código do produto */
.input-cod-prod-xml {
  padding: 5px !important;
  border: 1px solid #ccc;
  transition: border-color 0.2s ease, background-color 0.2s ease;
}

.input-cod-prod-xml:hover {
  border: 2px solid rgb(62, 83, 245);
}

.input-cod-prod-xml.cod-prod-atualizando {
  background-color: #fff9e6 !important;
  border-color: #f0ad4e !important;
}

.input-cod-prod-xml.cod-prod-sucesso {
  border-color: #5cb85c !important;
  background-color: #f0fff0 !important;
}

.input-cod-prod-xml.cod-prod-erro {
  border-color: #d9534f !important;
  background-color: #fff5f5 !important;
}

.cod-prod-linha-atualizando {
  opacity: 0.8;
}

#tableItemns {
  position: relative;
}

.xml-import-validando {
  opacity: 0.65;
  pointer-events: none;
}

.xml-import-validando-msg {
  position: absolute;
  top: 8px;
  right: 12px;
  z-index: 5;
  background: rgba(51, 122, 183, 0.92);
  color: #fff;
  padding: 6px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
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
.equiv-row-selected{
  background-color: #d9edf7 !important;
  box-shadow: inset 3px 0 0 #337ab7;
}
.equiv-row-selected td{
  background-color: #d9edf7 !important;
}
</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_nota_xml_importa.js"> </script>
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
            <input type="hidden" name="xml_token" id="xml_token" value="{$xml_token|default:''}">
            <input type="hidden" name="xml_file_name" id="xml_file_name" value="{$xml_file_name|default:''}">

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
                        
                          <li><button {if not $xml_loaded} style="display:none" {/if} id="btnAddXml" type="button" class="btn btn-danger"  onClick="javascript:submitAddXml();">
                            <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span><span>&nbsp;&nbsp;Adicionar novo XML</span></button>
                          </li>
                          
                          <li><button {if not $xml_loaded or $existeNotaFiscal eq '1' or $destinatario eq false} style="display:none" {/if} id="btnValidar" type="button" class="btn btn-warning"  onClick="javascript:submitValidar();">
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
                            size="100" name="file" accept=".xml,application/xml,text/xml" style="border-radius: 5px;">
                            {if $xml_loaded && $xml_resumo.emitente}
                            <p id="xml-resumo" class="help-block" style="margin-top:8px;">
                              <span class="text-muted">XML em processamento:</span>
                              <strong>{$xml_resumo.emitente}</strong> — NF {$xml_resumo.numero}/{$xml_resumo.serie}
                              {if $xml_file_name} ({$xml_file_name}){/if}
                            </p>
                            {else}
                            <p id="xml-resumo" class="help-block" style="margin-top:8px; display:none;"></p>
                            {/if}
                            
                        </div>
                      </div>
                                           
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

      <div class="modal fade" id="modalVincularEquivalente" tabindex="-1" role="dialog" aria-labelledby="modalVincularEquivalenteLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 940px; max-width: 98%;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:28px;"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="modalVincularEquivalenteLabel">Selecione o equivalente</h4>
            </div>
            <div class="modal-body" style="text-align:left; max-height:520px; overflow-y:auto;">
              <div style="margin-bottom:10px; border:1px solid #e2e2e2; border-radius:6px; padding:8px; background:#fafafa;">
                <div class="row" style="margin:0;">
                  <div class="col-sm-3" style="padding-left:0; padding-right:8px;"><b>Produto de origem (XML)</b></div>
                  <div class="col-sm-3" style="padding-left:0; padding-right:8px;"><b>Cód. XML:</b> <span id="modalOrigemCodigoXml">-</span></div>
                  <div class="col-sm-6" style="padding-left:0; padding-right:0;"><b>Descrição XML:</b> <span id="modalOrigemDescricaoXml">-</span></div>
                </div>
              </div>

              <div style="margin-bottom:10px;">
                <label for="modalEquivTermo" style="font-weight:600;">Filtro de busca do produto</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="modalEquivTermo" placeholder="Digite código ou descrição">
                  <span class="input-group-btn">
                    <button class="btn btn-warning" type="button" id="btnBuscarEquivModal">Buscar</button>
                  </span>
                </div>
              </div>

              <div style="margin-bottom:10px;">
                <label style="font-weight:600;">Selecionado na lista</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="modalEquivSelecionadoInfo" readonly value="Nenhum item selecionado">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="btnConfirmarEquivModal">Vincular</button>
                  </span>
                </div>
              </div>

              <div style="max-height:240px; overflow:auto; border:1px solid #ddd; border-radius:6px;">
                <table style="width:100%; border-collapse:collapse; font-size:12px;">
                  <thead style="position:sticky; top:0; background:#f7f7f7;">
                    <tr>
                      <th style="padding:6px; text-align:left;">Código</th>
                      <th style="padding:6px; text-align:left;">Cód. Fab.</th>
                      <th style="padding:6px; text-align:left;">Descrição</th>
                      <th style="padding:6px; text-align:left;">Marca</th>
                      <th style="padding:6px; text-align:left;">Und.</th>
                    </tr>
                  </thead>
                  <tbody id="modalEquivResultados">
                    <tr><td colspan="5" style="padding:8px; text-align:left;">Informe um filtro e clique em buscar.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    {include file="template/form.inc"}  
    