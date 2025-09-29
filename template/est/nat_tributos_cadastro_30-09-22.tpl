<script type="text/javascript" src="{$pathJs}/est/s_nat_tributos.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Natureza de Opera&ccedil;&atilde;o Tributos</h3>
          </div>
        </div>
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="nat_tributos">   
            <input name=id                  type=hidden value={$id}>
            <input name=idNatop             type=hidden value={$idNatop}>
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>

            
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
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        {if ($desabilitarCampos == "N")}
                          <li><button type="button" class="btn btn-warning"  onClick="javascript:submitCopiar('nat_tributos');">
                                  <span class="glyphicon glyphicon-copy" aria-hidden="true"></span><span> Copiar</span></button>
                          </li>
                          <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar('nat_tributos');">
                                  <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                          </li>
                        {else}
                          <li><button type="button" class="btn btn-primary"  onClick="javascript:submitUpdateGeneral('nat_tributos');">
                                  <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Atualizar</span></button>
                          </li>                          
                        {/if}
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('nat_tributos');">
                                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Cancelar</span></button>
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


                      <div class="form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-1 col-md-offset-2" for="centrocusto">Empresa </label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                                <select class="form-control" name="centrocusto" id="tipo" {if ($desabilitarCampos == "N")} disabled {else} disabled style="background-color: #1a8ebb;"{/if}> 
                                    {html_options values=$ccusto_ids selected=$ccusto_id output=$ccusto_names}
                                </select>
                        </div>
                        <label class="control-label col-md-1 col-sm-1 col-xs-1 col-md-offset-1" for="tipo">Tipo </label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                                <select class="form-control" name="tipo" id="tipo" {if ($desabilitarCampos == "N")} disabled {else} disabled style="background-color: #1a8ebb;"{/if}> 
                                    {html_options values=$tipoNatOp_ids selected=$natTipo output=$tipoNatOp_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="natDesc">Natureza Opera&ccedil;&atilde;o 
                        </label>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                          <input  id="natDesc" name="natDesc" type="text" {if ($desabilitarCampos == "N")} disabled {else} disabled style="background-color: #1a8ebb;"{/if} class="form-control" value={$natDesc}>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="uf">Estado <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                                <select class="form-control" name="uf" id="uf" title="Unidade da Federa&ccedil;&atilde;o." 
                                {if ($desabilitarCampos == "N")} enable {else} disabled style="background-color: #1a8ebb;"{/if}>
                                    {html_options values=$uf_ids selected=$uf output=$uf_names}
                                </select>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-3" for="pessoa">Tipo Pessoa <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <select {if ($desabilitarCampos == "N")} enable {else} disabled style="background-color: #1a8ebb;"{/if} 
                               class="form-control" name="pessoa" id="pessoa" title="Classifica&ccedil;&atilde;o que est&aacute; cadastrado na conta (cliente ou fornecedor) para efeito de calculo de imposto.">
                                    {html_options values=$pessoa_ids selected=$pessoa output=$pessoa_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">CST </label>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2" for="origem">Origem Mercadoria <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                                <select {if ($desabilitarCampos == "N")} enable {else} disabled style="background-color: #1a8ebb;"{/if}
                                   class="form-control" name="origem" id="origem" title="Origem da mercadoria para efeito de calculo de imposto.">
                                    {html_options values=$origem_ids selected=$origem output=$origem_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2" for="tribIcms">Cadastro Produto - Tributa&ccedil;&atilde;o ICMS / CSOSN <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <select 
                                {if ($desabilitarCampos == "N")} enable {else} disabled style="background-color: #1a8ebb;"{/if}
                                class="form-control" name="tribIcms" id="tribIcms" title="Classifica&ccedil;&atilde;o do ICMS para efeito de calculo de imposto, mesma ST constante no cadastro do produto.">
                                    {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2" for="tribIcmsSaida">NFe Saída - Tributa&ccedil;&atilde;o ICMS / CSOSN <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <select class="form-control" name="tribIcmsSaida" id="tribIcmsSaida" title="Classifica&ccedil;&atilde;o do ICMS para efeito de calculo de imposto para a NOTA FISCAL DE SÁIDA.">
                                    {html_options values=$tribIcms_saida_ids selected=$tribIcmsSaida output=$tribIcms_saida_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="ncm">NCM </label>
                        <div class="col-md-3 col-sm-3 col-xs-3" >
                              <input  
                              {if ($desabilitarCampos == "N")} enable{else} disabled style="background-color: #1a8ebb;"{/if} class="form-control" id="ncm" name="ncm" type="text" 
                                     title="N&uacute;mero Comum do Mercosul." >
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-3" for="cest">CEST </label>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                              <input class="form-control" id="cest" name="cest" type="text" 
                                    title="C&oacute;digo Especificador da Substitui&ccedil;&atilde;o Tribut&aacute;ria ." value={$cest}>
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="tribIcms">CFOP <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <select class="form-control" name="cfop" id="tribIcms" title="C&oacute;digo Fiscal de Opera&ccedil;&otilde;es e Presta&ccedil;&otilde;es.">
                                    {html_options values=$cfop_ids selected=$cfop output=$cfop_names}
                                </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="tribIcms">ANP <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <select class="form-control" name="anp" id="anp" title="ANP">
                                    {html_options values=$anp_ids selected=$anp output=$anp_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">ICMS </label>
                        <label class="control-label col-md-1 col-sm-2 col-xs-2" for="modBc">Modalidade </label>    
                        <div class="col-md-4 col-sm-3 col-xs-3">
                                <select class="form-control" name="modBc" id="modBc" title="Modalidade de determina&ccedil;&atilde;oo da BC do ICMS.">
                                    {html_options values=$modBc_ids selected=$modBc output=$modBc_names}
                                </select>
                        </div>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">Aliquota</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input class="form-control has-feedback-left" type="text" id="aliqIcms" name="aliqIcms" 
                                       title="Percentual da aliquota de ICMS."value={$aliqIcms}>
                                <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                      </div>    
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">Red. Base</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="aliqIcms" name="redBaseIcms" type="text" 
                                     title="Percentual Redu&ccedil;&atilde;o Base ICMS." value={$redBaseIcms}>
                                <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Diferimento</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="percDiferido" name="percDiferido" type="text" 
                                     title="Percentual Diferimento para calculo do ICMS." value={$percDiferido}>
                                <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                      </div>
                          
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3"></label>
                            <label class="control-label col-md-1 col-sm-2 col-xs-2" for="cbenef">Cód Benefício</label>    
                            <div class="col-md-4 col-sm-3 col-xs-3">
                                <select class="form-control" name="cbenef" id="cbenef" title="Cod Benefício">
                                    {html_options values=$cbenef_ids selected=$cbenef_id output=$cbenef_names}
                                </select>
                            </div>                            
                      </div>    
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Substitui&ccedil;&atilde;o Tribut&aacute;ria </label>
                        <label class="control-label col-md-1 col-sm-2 col-xs-2" for="modBcSt">Modalidade </label>    
                        <div class="col-md-4 col-sm-3 col-xs-3">
                                <select class="form-control" name="modBcSt" id="modBcSt" title="Modalidade de determina&ccedil;&atilde;oo da BC do ICMS ST.">
                                    {html_options values=$modBcSt_ids selected=$modBcSt output=$modBcSt_names}
                                </select>
                        </div>                
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>                        
                        <label class="control-label col-md-1 col-sm-3 col-xs-6">&Iacute;ndice MVA</label>
                        <div class="col-md-3 col-sm-2 col-xs-12">
                            <input class="form-control has-feedback-left" type="text" id="mvast" name="mvast" 
                                   title="Percentual da Margem de Valor Agregado ou Ajustado."value={$mvast}>
                            <span class="form-control-feedback left glyphicon glyphicon-italic" aria-hidden="true"><b></b></span></b>
                        </div>  
                        <label class="control-label col-md-1 col-sm-3 col-xs-6">MVA ST AJustada</label>
                        <div class="col-md-3 col-sm-2 col-xs-12">
                            <input class="form-control has-feedback-left" type="text" id="mvastajustada" name="mvastajustada" value={$mvastajustada}>
                            <span class="form-control-feedback left glyphicon glyphicon-italic" aria-hidden="true">
                            <b></b>
                            </span>
                            </b>
                        </div>
                      </div>
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"> </label>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">Aliquota</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="aliqicmsst" name="aliqicmsst" type="text" 
                                     title="Aliquata ICMS Substitui&ccedil;&atilde;o Tribut&aacute;ria." value={$aliqicmsst}>
                                <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Red. Base</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="percReducaoBcSt" name="percReducaoBcSt" type="text" 
                                     title="Percentual Redu&ccedil;&atilde;o Base ICMS Substitui&ccedil;&atilde;o Tribut&aacute;ria." value={$percReducaoBcSt}>
                                <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                      </div>    
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">IPI</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="ipi" name="ipi" type="text" 
                                     title="Percentual da aliquota Imposto sobre produtos industrializados." value={$ipi}>
                              <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                              <label class="control-label col-md-3 col-sm-3 col-xs-6">Inside IPI BC</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <select class="form-control" name="insideIpiBc" id="insideIpiBc" title="Valor IPI comp&otilde; a BC de ICMS.">
                                    {html_options values=$boolean_ids selected=$insideIpiBc output=$boolean_names}
                                </select>
                            </div>
                      </div>    
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">ISS</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="iss" name="iss" type="text" 
                                     title="Percentual da aliquota Imposto Sobre Servi&ccedil;o." value={$iss}>
                               <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-6">Aliq ICMSST Simples</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="aliqicmssimplesst" name="aliqicmssimplesst" type="text" 
                                     title="Percentual da aliquota ICMS, para calculo de ST (empresa do simples)" value={$aliqicmssimplesst}>
                               <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>
                      </div>  
                       
                      <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <label class="control-label col-md-1 col-sm-3 col-xs-6">ALIQ FCP St</label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                              <input class="form-control has-feedback-left" id="aliqfcpst" name="aliqfcpst" type="text" 
                                     title="aliqfcpst" value={$aliqfcpst}>
                               <span class="form-control-feedback left" aria-hidden="true"><b>%</b></span></b>
                            </div>                            
                      </div>   
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3">PIS </label>
                        <label class="control-label col-md-1 col-sm-2 col-xs-2" for="cstPis">CST </label>    
                        <div class="col-md-4 col-sm-3 col-xs-3">
                                <select class="form-control" name="cstPis" id="cstPis" title="Cst PIS.">
                                    {html_options values=$pisCofins_ids selected=$cstPis output=$pisCofins_names}
                                </select>
                        </div>
                                <label class="control-label col-md-1 col-sm-3 col-xs-6">Al&iacute;quota PIS</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input class="form-control has-feedback-left" type="text" id="aliqPis" name="aliqPis" 
                                   title="Al&iacute;quota PIS em percentual ou valor (depende do CST)"value={$aliqPis}>
                            <span class="form-control-feedback left glyphicon glyphicon-font" aria-hidden="true"><b></b></span></b>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3">COFINS </label>
                        <label class="control-label col-md-1 col-sm-2 col-xs-2" for="cstCofins">CST </label>    
                        <div class="col-md-4 col-sm-3 col-xs-3">
                                <select class="form-control" name="cstCofins" id="cstCofins" title="Cst COFINS.">
                                    {html_options values=$pisCofins_ids selected=$cstCofins output=$pisCofins_names}
                                </select>
                        </div>
                                <label class="control-label col-md-1 col-sm-3 col-xs-6">Al&iacute;quota COFINS</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <input class="form-control has-feedback-left" type="text" id="aliqCofins" name="aliqCofins" 
                                   title="Al&iacute;quota COFINS em percentual ou valor (depende do CST)"value={$aliqCofins}>
                            <span class="form-control-feedback left glyphicon glyphicon-font" aria-hidden="true"><b></b></span></b>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Pessoa </label>                                                
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label for="contribuinteICMS">Contribuinte de ICMS</label>
                            <select class="form-control" name="contribuinteICMS" id="contribuinteICMS" title="Contribuinte de ICMS.">
                                {html_options values=$boolean_ids selected=$contribuinteICMS output=$boolean_names}
                            </select>
                        </div>  
                                    
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label for="consumidorFinal">Consumidor Final</label>
                            <select class="form-control" name="consumidorFinal" id="consumidorFinal" title="Consumidor final.">
                                {html_options values=$boolean_ids selected=$consumidorFinal output=$boolean_names}
                            </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="obs">Observa&ccedil;&atilde;o Legisla&ccedil;&atilde;o
                        </label>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <textarea class="resizable_textarea form-control" id="legislacao" name="legislacao" rows="3" 
                                      title="Descri&ccedil;&atilde;o da legisla&ccedil;&atilde;o para ser impresso na Nota Fiscal.">{$legislacao}</textarea>
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
    <!-- jquery.inputmask -->
    <script>
//      $(document).ready(function() {
//        $(":input").inputmask("'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0'");
//        $(":input").inputmask();
//      });
    </script>
    <!-- /jquery.inputmask -->               