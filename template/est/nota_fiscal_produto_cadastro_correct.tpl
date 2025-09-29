<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_produto.js"> </script>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>

<!-- page content -->
<div class="right_col" role="main">      
    <div class="small">

        <div class="page-title">
          <div class="title_left">
              <h3>Nota Fiscal - Produtos</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="">   
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
                        
                    <li><button type="button" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmarDevolucaoNf('');">
                       <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                    </li>
                    <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarDevolucaoNf('');">
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
                    <form class="container" novalidate="" action="/echo" method="POST" id="myForm">
                        <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="codProduto">C&oacute;digo</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input" readonly type="number" id="codProduto" name="codProduto" 
                                            value={$codProduto} onKeyPress="if(this.value.length==11) return false;">
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-xs-6  text-left">
                                    <label for="descProduto">Produto</label>
                                    <div class="input-group">
                                        <input class="form-control input" {$readonly} readonly type="text" maxlength="100" id="descProduto" name="descProduto" value={$descProduto}>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn-sm btn-primary" 
                                                    {if  $readonly neq 'readonly'}
                                                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&origem=nota');">
                                                    {/if}    
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>                                
                                    </div>
                                </div>
                            <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                <label for="cfop">CFOP</label>
                                <div class="panel panel-default">
                                    <input class="form-control input" onKeyPress="if(this.value.length==11) return false;" {$readonly} type="number" name="cfop" 
                                        placeholder="Cfop" value={$cfop}>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="quant">Quantidade</label>
                                     <div class="panel panel-default">
                                        <input class="form-control money" type="money" id="quant" name="quant"
                                        required="required" value={$quant} onblur="soma()" maxlength="9">
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6 text-left">
                                    <label for="unidade">Unidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" name="unidade" value={$unidade} maxlength="3">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="unitario">Valor Unitário</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" type="money" id="unitario" name="unitario"
                                        required="required" value={$unitario} onblur="soma()" maxlength="14">
                                        <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                    <!--<label for="unitario">Valor Unitário</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" maxlenght="14" type="money" id="unitario" name="unitario" 
                                         value={$unitario} required="required">
                                          <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>-->
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                     <label for="desconto">Desconto</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" type="money" id="desconto" name="desconto"
                                        value={$desconto} onblur="soma()" maxlength="14">
                                        <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqIpi">Aliquota IPI</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqIpi" name="aliqIpi"
                                        value={$aliqIpi} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="valorIpi">Valor IPI</label>
                                     <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorIpi" name="valorIpi"
                                        value={$valorIpi} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="total">Total Produto</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" type="money" id="total" name="total"
                                        required="required" value={$total} onblur="soma()" maxlength="11">
                                        <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="origem">Origem</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input"  readonly="readonly" name=origem>
                                            {html_options values=$origem_ids selected=$origem output=$origem_names disabled=$disable strict=1}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                    <label for="tribIcms">Tributação ICMS / CSOSN</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input" name=tribIcms>
                                            {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="bcIcms">Base Calculo ICMS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcIcms" name="bcIcms"
                                        value={$bcIcms} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqIcms">Aliquota ICMS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqIcms" name="aliqIcms"
                                        value={$aliqIcms} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="valorIcms">Valor ICMS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorIcms" name="valorIcms"
                                        value={$valorIcms} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="cbenef">Cód Benefício</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input" name=cbenef>
                                            {html_options values=$cbenef_ids selected=$cbenef output=$cbenef_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                    <label for="cstPis">CST PIS</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input" name=cstPis>
                                            {html_options values=$pisCofins_ids selected=$cstPis output=$pisCofins_names}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="bcPis">Base Calculo PIS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcPis" name="bcPis"
                                        value={$bcPis} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqPis">Aliquota PIS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqPis" name="aliqPis"
                                        value={$aliqPis} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="valorPis">Valor PIS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorPis" name="valorPis"
                                        value={$valorPis} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                    <label for="cstCofins">CST COFINS</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input" name=cstCofins>
                                            {html_options values=$pisCofins_ids selected=$cstCofins output=$pisCofins_names}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="bcCofins">Base Calculo COFINS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcCofins" name="bcCofins"
                                        value={$bcCofins} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqCofins">Aliquota COFINS</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqCofins" name="aliqCofins"
                                        value={$aliqCofins} maxlength="5">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="valorCofins">Valor COFINS</label>
                                     <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorCofins" name="valorCofins"
                                        value={$valorCofins} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
      
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="bcfcpst">bcfcpst</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcfcpst" name="bcfcpst"
                                        value={$bcfcpst} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqfcpst">aliqfcpst</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqfcpst" name="aliqfcpst"
                                        value={$aliqfcpst} maxlength="9">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="valorfcpst">valorfcpst</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorfcpst" name="valorfcpst"
                                        value={$valorfcpst} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                              <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="bcfcpufdest">bcfcpufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcfcpufdest" name="bcfcpufdest"
                                        value={$bcfcpufdest} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqfcpufdest">aliqfcpufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqfcpufdest" name="aliqfcpufdest"
                                        value={$aliqfcpufdest} maxlength="9">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="valorfcpufdest">valorfcpufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valorfcpufdest" name="valorfcpufdest"
                                        value={$valorfcpufdest} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="bcicmsufdest">bcicmsufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="bcicmsufdest" name="bcicmsufdest"
                                        value={$bcicmsufdest} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqicmsufdest">aliqicmsufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqicmsufdest" name="aliqicmsufdest"
                                        value={$aliqicmsufdest} maxlength="9">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqicmsinter">aliqicmsinter</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqicmsinter" name="aliqicmsinter"
                                        value={$aliqicmsinter} maxlength="9">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqicmsinterpart">aliqicmsinterpart</label>
                                     <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="aliqicmsinterpart" name="aliqicmsinterpart"
                                        value={$aliqicmsinterpart} maxlength="9">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>&#37;</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="valoricmsufdest">valoricmsufdest</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valoricmsufdest" name="valoricmsufdest"
                                        value={$valoricmsufdest} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="valoricmsufremet">valoricmsufremet</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="valoricmsufremet" name="valoricmsufremet"
                                        value={$valoricmsufremet} maxlength="11">
                                    <span class="form-control-feedback left" aria-hidden="true" id="sifrao"><b>R$</b></span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="ncm">NCM</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input" onKeyPress="if(this.value.length==15) return false;" 
                                            placeholder="Ncm" type="number" name="ncm" value={$ncm}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="cest">CEST</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" onKeyPress="if(this.value.length==15) return false;" 
                                            placeholder="Cest" type="number" name="cest" value={$cest}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="ordem">OS. Parceiro</label>
                                    <input class="form-control" maxlength="30" id="ordem" type="text" name="ordem"
                                     placeholder="Ordem Serviço" value={$ordem}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="nrSerie">N&uacute;mero de S&eacute;rie</label>
                                    <input class="form-control" maxlength="25" id="nrSerie" type="text" name="ordem"
                                     placeholder="N&uacute;mero de S&eacute;rie" value={$nrSerie}>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-left">
                                    <label for="lote">Lote</label>
                                    <input class="form-control" maxlength="30" id="lote" type="text" name="lote"
                                     value={$lote} placeholder="Lote">
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataFabricacao">Data Fabrica&ccedil;&atilde;o</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataFabricacao" name="dataFabricacao" value={$dataFabricacao}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataValidade">Data Validade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataValidade"  name="dataValidade" value={$dataValidade}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataGarantia">Data Garantia</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataGarantia" name="dataGarantia" value={$dataGarantia}>

                                    </div>
                                </div>
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
        });      
     });
    </script>
<style type="text/css">
        
input[type="number"]::-webkit-outer-spin-button, 
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }
    input[type="number"] {
    -moz-appearance: textfield;
}

#sifrao{
    position:absolute;
    top:39%;
    left:11%;
    transform:translate(-50%,-50%)
}
</style>