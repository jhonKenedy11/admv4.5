<style type="text/css">
input[type="number"]::-webkit-outer-spin-button, 
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }
    input[type="number"] {
    -moz-appearance: textfield;
}
input, select{
    font-size: 12px !important;
}

#Sproduto{
    position:absolute;
    top:31px ;
    left:30px;
    transform:translate(-50%,-50%)
}

#SIcms{
    position:absolute;
    top:29.5px ;
    left:30px;
    transform:translate(-50%,-50%)
}

#SIpi{
    position:absolute;
    top:30.3px ;
    left:30px;
    transform:translate(-50%,-50%)
}
label   
{
    font-size: 12px;
}
.form-control:focus {
    border-width: 2px;
    border-color: #159ce4;
    transition: all 0.5s ease;
}
.form-control, .x_panel{
    border-radius: 5px;
}
.botao-com-bordas {
    border: 1px solid #000; /* Cor e largura da borda */
    padding: 10px 20px; /* Espaçamento interno do botão */
    text-decoration: none; /* Remover sublinhado do link */
    font-size: 1.5rem;
    color: #000; /* Cor do texto */
    border-radius: 5px; /* Borda arredondada */
    text-align: center; /* Alinhar o texto ao centro */
    transition: opacity 0.3s;
}

.botao-com-bordas:hover {
    background-color: #000;
    opacity: 0.7;
    color: #fff; 
}
.icmsst{
    margin-top: 52px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_produto.js"> </script>

<!-- page content -->
<div class="right_col" role="main" style="padding: 5px;">      
    <div class="small">

        <div class="page-title">
          {* <div class="title_left">
              <h3>Nota Fiscal - Produtos</h3>
          </div> *}
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="nota_fiscal_produto">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=letra               type=hidden value={$letra}>
            <input name=pesquisa            type=hidden value={$pesquisa}>
            <input name="id"                type=hidden value={$id}>
            <input name="idnf"              type=hidden value={$idnf}>
            <input name="pessoa"            type=hidden value={$pessoa}>
            <input name="custoProduto"      type=hidden value={$custoProduto}>
            <input name="dataConferencia"   type=hidden value={$dataConferencia}>
            <input name="telaOrigem"        type=hidden value={$telaOrigem}>
            <input name="codFabricante"     type=hidden value={$codFabricante}>
            <input name="codProdutoNota"    type=hidden value={$codProdutoNota}>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                    {if $opcao neq "receber"}
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                    {else}
                        Recebimento - Baixa
                    {/if}    
                    {include file="../bib/msg.tpl"}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        
                    <!--<li><button type="button" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmarDevolucaoNf('');">-->
                    <li><button type="button" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmar('');">
                       <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                    </li>
                    <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarDevolucaoNf('');">
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

                  <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_produto" id="dados-tab" role="tab" data-toggle="tab" aria-expanded="true">PRODUTO</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_icms" role="tab" id="transportadora-tab" data-toggle="tab" aria-expanded="false">ICMS</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_ipi" role="tab" id="transportadora-tab" data-toggle="tab" aria-expanded="false">IPI / PIS / COFINS</a>
                        </li>
                        {* <li role="presentation" class=""><a href="#tab_pis" role="tab" id="transportadora-tab" data-toggle="tab" aria-expanded="false">PIS</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_cofins" role="tab" id="produtos-tab" data-toggle="tab" aria-expanded="false">COFINS</a>
                        </li> *}
                        <li role="presentation" class=""><a href="#tab_ots" role="tab" id="produtos-tab" data-toggle="tab" aria-expanded="false">OUTROS</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_produto" aria-labelledby="home-tab" style="margin: 0 0 0 25px;">

                            <div class="row">

                                <div class="col-md-12 col-sm-6 col-xs-6  text-left">
                                    <label for="descProduto">Produto</label>
                                    <div class="input-group">
                                        <input class="form-control input" {$readonly} type="text" maxlength="100" id="descProduto" name="descProduto" value={$descProduto}>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn-sm btn-primary" 
                                                {if  $readonly neq 'readonly'}
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&origem=nota&from=nota');">
                                                {/if}    
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>                                
                                    </div>
                                </div>
                            
                            </div>
                            
                            <div class="row">

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="cfop">CFOP</label>
                                    <input class="form-control input" onKeyPress="if(this.value.length==11) return false;" type="number" name="cfop" 
                                        placeholder="Cfop" id="cfop" value={$cfop}>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="ncm">NCM</label>
                                    <input class="form-control input" onKeyPress="if(this.value.length==15) return false;" 
                                        placeholder="Ncm" type="number" name="ncm" value={$ncm}>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="cest">CEST</label>
                                    <input class="form-control" onKeyPress="if(this.value.length==15) return false;" 
                                        placeholder="Cest" type="number" name="cest" value={$cest}>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-6  text-left">
                                    <label for="cbenef">Cód Benefício</label>
                                    <select class="form-control input" name=cbenef>
                                        {html_options values=$cbenef_ids selected=$cbenef output=$cbenef_names}
                                    </select>
                                </div>

                            </div>
                            </br>

                            <div class="row">
                                
                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="codProduto">C&oacute;digo produto</label>
                                    <input class="form-control input" readonly type="number" id="codProduto" name="codProduto" 
                                    value={$codProduto} onKeyPress="if(this.value.length==11) return false;">
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6 text-left">
                                    <label for="unidade">Unidade</label>
                                    <input class="form-control" type="text" name="unidade" maxlength="3" placeholder="UN" value={$unidade}>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="nrserie">N&uacute;mero de S&eacute;rie</label>
                                    <input class="form-control" maxlength="25" id="nrserie" type="text" name="nrserie"
                                     placeholder="N&uacute;mero de S&eacute;rie" value={$nrserie}>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="ordem">OS. Parceiro</label>
                                    <input class="form-control" maxlength="20" id="ordem" type="text" name="ordem"
                                     placeholder="Ordem Serviço" value={$ordem}>
                                </div>

                            </div>
                            </br>

                            <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="quant">Quantidade</label>
                                    <input class="form-control money" type="money" id="quant" name="quant"
                                        required="required" onblur="soma()" maxlength="9" value={$quant}>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="unitario">Valor Unitário</label>
                                    <input class="form-control moneyUnitario has-feedback-left" type="money" id="unitario" name="unitario"
                                        required="required" onblur="soma()" maxlength="14" value="{$unitario|number_format:$casasDecimais:',':'.'}">
                                    <span class="form-control-feedback left" aria-hidden="true" id="Sproduto"><b>R$</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="desconto">Desconto</label>
                                    <input class="form-control money has-feedback-left" type="money" id="desconto" name="desconto"
                                        onblur="soma()" maxlength="14" value={$desconto}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="Sproduto"><b>R$</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="total">Total Produto</label>
                                    <input class="form-control money has-feedback-left" type="money" id="total" name="total"
                                        required="required" onblur="soma()" maxlength="11" value={$total}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="Sproduto"><b>R$</b></span>
                                </div>

                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane fade small" id="tab_icms" aria-labelledby="profile-tab" style="margin: 0 0 0 25px;">

                            <div class="row">

                                <div class="col-md-12 col-sm-12 col-xs-12  text-left">
                                    <label for="origem" title="<orig>">Origem</label>
                                    <select class="form-control input" name=origem>
                                        {html_options values=$origem_ids selected=$origem output=$origem_names}
                                    </select>
                                </div>
                            </div>
                            </br>

                            <div class="row">
                                <div class="col-md-8 col-sm-6 col-xs-6 text-left">
                                    <label for="tribIcms" title="<ICMS>">Tributa&ccedil;&atilde;o ICMS / CSOSN</label>
                                    <select class="form-control input" name=tribIcms>
                                        {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-6 text-left">
                                   <label for="modBc" title="<modBC>">Modalidade</label>    
                                    <select class="form-control" id="modBc" name="modBc" title="Modalidade de determina&ccedil;&atilde;oo da BC do ICMS.">
                                        {html_options values=$modBc_ids selected=$modBc output=$modBc_names}
                                    </select>              
                               </div>
                            </div>
                            </br>

                            <div class="row">

                                <div class="col-md-4 col-sm-6 col-xs-6 text-left">
                                    <label for="bcIcms" title="<vBC>">Base C&aacute;lculo ICMS</label>
                                    <input class="form-control money has-feedback-left" type="money" id="bcIcms" name="bcIcms"
                                        value={$bcIcms} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                </div>

                                <div class="col-md-4 col-sm-6 col-xs-6 text-left">
                                    <label for="aliqIcms" title="<pICMS>">Al&iacute;quota ICMS</label>
                                    <input class="form-control money has-feedback-left" type="money" id="aliqIcms" name="aliqIcms"
                                        value={$aliqIcms} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                </div>
                        

                                <div class="col-md-4 col-sm-6 col-xs-6 text-left">
                                    <label for="valorIcms" title="<vICMS>">Valor ICMS</label>
                                    <input class="form-control money has-feedback-left" type="money" id="valorIcms" name="valorIcms"
                                        value={$valorIcms} maxlength="11"  >
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                </div>
                            
                            </div>
                            </br>

                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6 text-left">
                                    <label for="percReducaoBc" title="<pRedBC>">Al&iacute;quota da Redu&ccedil;&atilde;o de BC</label>
                                    <input class="form-control money has-feedback-left" type="money" id="percReducaoBc" name="percReducaoBc" 
                                        maxlength="5" value={$percReducaoBc}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                </div>
                                
                                <div class="col-md-3 col-sm-6 col-xs-6 text-left">
                                    <label for="valorIcmsOperacao" title="<pRedBC>">Valor ICMS da Operação</label>
                                    <input class="form-control money has-feedback-left" type="money" id="valorIcmsOperacao" name="valorIcmsOperacao" 
                                        maxlength="5" value={$valorIcmsOperacao}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                </div>
                                
                                <div class="col-md-3 col-sm-6 col-xs-6 text-left">
                                    <label for="percDiferido" title="<pDif>">Al&iacute;quota do Diferimento</label>
                                    <input class="form-control money has-feedback-left" type="money" maxlength="5" id="percDiferido" name="percDiferido" value={$percDiferido}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6 text-left">
                                    <label for="valorIcmsDiferido" title="<vICMSDif>">Valor do ICMS Diferido</label>
                                    <input class="form-control money has-feedback-left" type="money" maxlength="11" id="valorIcmsDiferido" name="valorIcmsDiferido" value={$valorIcmsDiferido}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                </div>

                            </div>
                            </br>    
                            

                            <div class = "row">
                                <div class="panel-default" id="accordion" role="tablist" aria-multiselectable="true">
                                        
                                        
                                        <a class="panel-default collapsed botao-com-bordas col-md-12 col-sm-6 col-xs-6" role="tab" id="headingTwo" 
                                            data-toggle="collapse" data-parent="#accordion" href="#icmsstt" aria-expanded="false" aria-controls="collapseTwo">
                                            ICMS Substituição Tributária (ST)
                                        </a>


                                        <div id="icmsstt" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <div class="row icmsst">

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="modBcSt" title="<modBCST>">Modalidade ST</label>    
                                                        <select class="form-control" name="modBcSt" id="modBcSt" title="Modalidade de determina&ccedil;&atilde;oo da BC do ICMS ST.">
                                                            {html_options values=$modBcSt_ids selected=$modBcSt output=$modBcSt_names}
                                                        </select>              
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="valorbcst" title="<vBCST>">Valor da BC do ICMS ST</label>
                                                        <input class="form-control money has-feedback-left" type="money" id="valorbcst" name="valorbcst" maxlength="11" value={$valorbcst}>
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="aliqicmsst" title="<pICMSST>">% do Imposto ICMS ST</label>
                                                        <input class="form-control money has-feedback-left" type="money" maxlength="5" id="aliqicmsst" name="aliqicmsst" value={$aliqicmsst}>
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                                    </div>

                                                </div>
                                                </br>

                                                <div class="row">

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="percReducaoBcSt" title="<pRedBCST>">% Redução BC do ICMS ST</label>
                                                        <input class="form-control money has-feedback-left" type="money" id="percReducaoBcSt" name="percReducaoBcSt" maxlength="5"
                                                            value={$percReducaoBcSt}>
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="percMvaSt" title="<pMVAST>">Al&iacute;quota MVA do ICMS ST</label>
                                                            {if $percMvaSt eq '0,00'}
                                                            <input class="form-control money has-feedback-left" type="money" maxlength="6" id="percMvaSt" name="percMvaSt" value={$percMvaSt}>
                                                            {else}
                                                            <input class="form-control money has-feedback-left" type="money" maxlength="6" id="percMvaSt" name="percMvaSt" value={$percMvaSt}>
                                                            {/if}
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="valoricmsst" title="<vICMSST>">Valor do ICMS ST</label>
                                                        <input class="form-control money has-feedback-left" type="money" id="valoricmsst" name="valoricmsst"
                                                            value={$valoricmsst} maxlength="11">
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                    </div>    

                                                </div>

                                                <div class="row">

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="valorBaseCalculoStRetido" title="<vBCSTRet>">Valor da BC do ICMS ST retido</label>
                                                        <input class="form-control money has-feedback-left" type="money" id="valorBaseCalculoStRetido" name="valorBaseCalculoStRetido" maxlength="11" value={$valorBaseCalculoStRetido}>
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="valorIcmsStRetido" title="<vICMSSTRet>">Valor do ICMS ST retido</label>
                                                            <input class="form-control money has-feedback-left" type="money" maxlength="11" id="valorIcmsStRetido" name="valorIcmsStRetido" value={$valorIcmsStRetido}>
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                    </div>

                                                    <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                        <label for="valorIcmsSubstituto" title="<vICMSSTRet>">Valor do ICMS Substituto</label>
                                                        <input class="form-control money has-feedback-left" type="money" id="valorIcmsSubstituto" name="valorIcmsSubstituto" maxlength="11" value={$valorIcmsSubstituto} >
                                                        <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                    </div>    

                                                </div>
                                            </div>
                                    
                                        </div> <!--FIM class="panel-body" -->
                                </div> <!-- FIM id="accordion" -->  
                            </div>

                            </br>
                            
                            <div class = "row">

                                <div class="panel-default" id="accordion" role="tablist" aria-multiselectable="true">
                                    <a class="panel-default collapsed botao-com-bordas col-md-12 col-sm-6 col-xs-6" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Fundo de Combate à Pobreza (FCP)
                                    </a>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            <div class="row icmsst">

                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="bcFcpSt" title="<vBCFCPST>">Vlr da Base de C&aacute;l do FCP retido por ST</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="bcFcpSt" name="bcFcpSt" 
                                                        maxlength="11" value={$bcFcpSt}>
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                </div>

                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="aliqFcpSt" title="<pFCPST>">% do FCP retido por Substitui&ccedil;&atilde;o Tribut&aacute;ria</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="aliqFcpSt" name="aliqFcpSt"
                                                        maxlength="7" value={$aliqFcpSt}>
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="valorFcpSt" title="<pFCPST>">Valor do FCP retido por Substitui&ccedil;&atilde;o Tribut&aacute;ria</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="valorFcpSt" name="valorFcpSt"
                                                        value={$valorFcpSt} maxlength="11">
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                </div>

                                            </div>
                                            </br>

                                            <div class="row">

                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="bcfcpufdest" title="<vBCFCPUFDest>">Valor da BC FCP na UF de Destino</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="bcfcpufdest" name="bcfcpufdest"
                                                    maxlength="11" value={$bcfcpufdest}>
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="aliqfcpufdest" title="<pFCPUFDest>">% do ICMS Relativo ao FCP na UF de Destino</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="aliqfcpufdest" name="aliqfcpufdest"
                                                        value={$aliqfcpufdest} maxlength="9">
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                    <label for="valorfcpufdest" title="<vFCPUFDest>">Valor do ICMS Relativo ao FCP da UF de Destino</label>
                                                    <input class="form-control money has-feedback-left" type="money" id="valorfcpufdest" name="valorfcpufdest"
                                                        value={$valorfcpufdest} maxlength="11">
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                                </div>

                                            </div>
                                        </div>
                                
                                    </div> <!--FIM class="panel-body" -->
                                </div> <!-- FIM id="myTabContent" -->  
                            </div>

                            </br>

                            <div class = "row">

                                <div class="panel-default" id="ICMSUFdeDestino" role="tablist" aria-multiselectable="true">
                                <div class="panel-default">
                                    <a class="panel-default collapsed botao-com-bordas col-md-12 col-sm-6 col-xs-6" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#ICMSUFdeDestino" href="#collapse" aria-expanded="false" aria-controls="collapseTwo">
                                        ICMS UF de Destino/Ots
                                    </a>
                                <div id="collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div class="row icmsst">

                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="bcicmsufdest" title="<vBCUFDest>">Valor da BC do ICMS na UF de Destino</label>
                                                <input class="form-control money has-feedback-left" type="money" id="bcicmsufdest" name="bcicmsufdest"
                                                    value={$bcicmsufdest} maxlength="11">
                                                <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                            </div>

                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="aliqicmsufdest" title="<pICMSUFDest>">Al&iacute;quota Interna da UF de Destino</label>
                                                <input class="form-control money has-feedback-left" type="money" id="aliqicmsufdest" name="aliqicmsufdest"
                                                    value={$aliqicmsufdest} maxlength="9">
                                                <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                            </div>

                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="valoricmsufdest" title="<vICMSUFDest>">Valor do ICMS Interestadual para a UF de Destino</label>
                                                <input class="form-control money has-feedback-left" type="money" id="valoricmsufdest" name="valoricmsufdest"
                                                    value={$valoricmsufdest} maxlength="11">
                                                <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                            </div>
                                            
                                        </div>
                                        </br>
                                        <div class="row">
      
                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="aliqicmsinter" title="<pICMSInter>">Al&iacute;quota Interestadual das UF Envolvidas</label>
                                                <input class="form-control money has-feedback-left" type="money" id="aliqicmsinter" name="aliqicmsinter"
                                                    value={$aliqicmsinter} maxlength="9">
                                                <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="aliqicmsinterpart" title="<pICMSInterPart>">% Provis&oacute;rio de Partilha do ICMS Interestadual</label>
                                                <input class="form-control money has-feedback-left" type="money" id="aliqicmsinterpart" name="aliqicmsinterpart"
                                                    value={$aliqicmsinterpart} maxlength="9">
                                                <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>&#37;</b></span>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                                <label for="valoricmsufremet" title="<vICMSUFRemet>">Valor do ICMS Inter para a UF do Remetente</label>
                                                <input class="form-control money has-feedback-left" type="money" id="valoricmsufremet" name="valoricmsufremet"
                                                    value={$valoricmsufremet} maxlength="11">
                                                    <span class="form-control-feedback left" aria-hidden="true" id="SIcms"><b>R$</b></span>
                                            </div>

                            
                                        </div>
                                    </div>

                                </div> <!--FIM class="panel-body" -->
                                </div> <!-- FIM id="ICMSUFdeDestino" -->  
                            </div>
                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane fade small" id="tab_ipi" aria-labelledby="profile-tab">
                            <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="cstIpi" title="<CST>">CST IPI</label>    
                                    <select class="form-control" name="cstIpi" id="cstIpi" title="Cst Ipi">
                                        {html_options values=$cstIpi_ids selected=$cstIpi output=$cstIpi_names}
                                    </select>            
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="baseCalculoIpi" title="<vBC>">Valor da BC do IPI</label>
                                    <input class="form-control money has-feedback-left" type="money" maxlength="11" id="baseCalculoIpi" name="baseCalculoIpi" value={$baseCalculoIpi}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>

                                 <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqIpi" title="<pIPI>">Al&iacute;quota IPI</label>
                                    <input class="form-control money has-feedback-left" type="money" id="aliqIpi" name="aliqIpi"
                                        maxlength="5" value={$aliqIpi}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>&#37;</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="valorIpi" title="<vIPI>">Valor IPI</label>
                                    <input class="form-control money has-feedback-left" type="money" id="valorIpi" name="valorIpi"
                                        maxlength="11" value={$valorIpi}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>
                            
                            </div>
                            </br>

                            <div class="row">
                                
                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="cstPis" title="<CST>">CST PIS</label>
                                    <select class="form-control input" name=cstPis>
                                        {html_options values=$pisCofins_ids selected=$cstPis output=$pisCofins_names}
                                    </select>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="bcPis" title="<vBC>">Valor da BC do PIS </label>
                                    <input class="form-control money has-feedback-left" type="money" id="bcPis" name="bcPis"
                                        value={$bcPis} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqPis" title="<pPIS>">Al&iacute;quota PIS</label>
                                    <input class="form-control money has-feedback-left" type="money" id="aliqPis" name="aliqPis"
                                        value={$aliqPis} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>&#37;</b></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="valorPis" title="<vPIS>">Valor PIS</label>
                                    <input class="form-control money has-feedback-left" type="money" id="valorPis" name="valorPis"
                                        value={$valorPis} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>
                            </div>
                            </br>

                            <div class="row">
                            
                            <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                <label for="cstCofins" title="<CST>">CST COFINS</label>
                                <select class="form-control input" name=cstCofins>
                                    {html_options values=$pisCofins_ids selected=$cstCofins output=$pisCofins_names}
                                </select>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                <label for="bcCofins" title="<vBC>">Valor da BC COFINS</label>
                                <input class="form-control money has-feedback-left" type="money" id="bcCofins" name="bcCofins"
                                    value={$bcCofins} maxlength="11">
                                <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                <label for="aliqCofins" title="<pCOFINS>">Al&iacute;quota da COFINS</label>
                                <input class="form-control money has-feedback-left" type="money" id="aliqCofins" name="aliqCofins"
                                    value={$aliqCofins} maxlength="5">
                                <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>&#37;</b></span>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                <label for="valorCofins" title="<vCOFINS>">Valor da COFINS</label>
                                <input class="form-control money has-feedback-left" type="money" id="valorCofins" name="valorCofins"
                                    value={$valorCofins} maxlength="11">
                                <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                            </div>

                        </div>

                        </div> <!-- END #tab_ipi -->

                        {* <div role="tabpanel" class="tab-pane fade small" id="tab_pis" aria-labelledby="profile-tab" style="margin: 0 0 0 25px;">
                            <div class="row">


                            </div>
                        </div> *}

                        {* <div role="tabpanel" class="tab-pane fade small" id="tab_cofins" aria-labelledby="profile-tab" style="margin: 0 0 0 25px;">

                        
                        </div>    *}

                        <div role="tabpanel" class="tab-pane fade small" id="tab_ots" aria-labelledby="profile-tab" style="margin: 0 0 0 25px;">
                            
                            <div class="row">

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="lote">Lote</label>
                                    <input class="form-control" maxlength="30" id="lote" type="text" name="lote"
                                        placeholder="Lote" value={$lote}>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="dataFabricacao">Data Fabrica&ccedil;&atilde;o</label>
                                    <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataFabricacao" name="dataFabricacao" value={$dataFabricacao}>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="dataValidade">Data Validade</label>
                                    <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataValidade"  name="dataValidade" value={$dataValidade}>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="dataGarantia">Data Garantia</label>
                                    <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataGarantia" name="dataGarantia" value={$dataGarantia}>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="rFrete">Frete</label>
                                    <input class="form-control money has-feedback-left" type="money" id="rFrete" tabindex="-1" name="rFrete" disabled value={$rFrete}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="rDesp">Despesas Acess&oacute;rias</label>
                                    <input class="form-control money has-feedback-left" type="money" id="rDesp" tabindex="-1" name="rDesp" disabled value={$rDesp}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="SIpi"><b>R$</b></span>
                                </div>

                            </div>

                        </div>
                    
              </div>
            </div>
        </form>
      </div>


{include file="template/form.inc"}  

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({                  
         decimal: ",",
         thousands: ".",
         allowZero: true,
         precision:{$casasDecimais}
        });      
     });
</script>

<script>
      $(document).ready(function(){
        $(".moneyUnitario").maskMoney({                  
         decimal: ",",
         thousands: ".",
         allowZero: true,
         precision: {$casasDecimais}
        });      
     });
</script>

