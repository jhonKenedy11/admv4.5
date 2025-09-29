    <script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Produto</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="produto">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=idEquiv             type=hidden value={$idEquiv}>
            <input name=letra               type=hidden value="{$letra}">
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesquisa	        type=hidden value={$pesquisa}>
            <input name=loc                 type=hidden value={$loc}>
            <input name=ns                  type=hidden value={$ns}>
            <input name=idNF                type=hidden value={$idNF}>
            <input name=pessoa              type=hidden value={$pessoa}>
            <input name=fornecedor          type=hidden value="">
            <input name=contaEquiv          type=hidden value="{$contaEquiv}">
            {* <input name=ncm              type=hidden value="$ncm"> *}
            <input name=codigo              type=hidden value="">
            <input name=codEquivalente      type=hidden value="{$codEquivalente}">
            <input name=idReparo            type=hidden value="{$idReparo}">
            
            
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
                    </h2>
                    {include file="../bib/msg.tpl"} 

                    <div class="col-md-1 col-sm-12 col-xs-12">
                        <input class="form-control" type="text" READONLY size="30" name="id"  value={$id}>
                    </div>                   
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="submit" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmar('');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
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


                    <br/>
                    <form class="container" novalidate="" action="/echo" method="POST" id="myForm">

                        <div class="row">

                            <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                                <label for="desc">Descri&ccedil;&atilde;o</label>
                                    <input class="form-control" type="text" maxlength="100" required="true" name="desc" value={$desc}>
                            </div>

                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <label for="uni">Unidade</label>
                                <input class="form-control" type="text" required="true" name="uni" maxlength="3" placeholder="UN" value={$uni}>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="uniFracionada">Un. Fracionada</label>
                                <select class="form-control" id="uniFracionada" name="uniFracionada" required="true">
                                    {html_options values=$boolean_ids selected=$boolean output=$boolean_names}
                                </select>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="grupo">Grupo</label>
                                    <!--<SELECT class="form-control" name="grupo" > 
                                        {html_options values=$grupo_ids selected=$grupo output=$grupo_names}
                                    </SELECT>-->
                                
                                    <select class="js-example-basic-single form-control" name="grupo" id="grupo">
                                        {html_options values=$grupo_ids selected=$grupo output=$grupo_names}
                                    </select>
                            </div>

                        </div>
                        
                        <div class="row" style="margin-top: 7px">
                            
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="descricaoDetalhada">Descri&ccedil;&atilde;o Detalhada</label>
                                <textarea class="resizable_textarea form-control" style="height:;" placeholder="Descrição detalhada do produto" id="descricaoDetalhada" name="descricaoDetalhada" rows="2" >{$descricaoDetalhada}</textarea>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="obs" >Observa&ccedil;&atilde;o</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="2" >{$obs}</textarea>
                            </div>

                        </div>

                        <div class="row" style="margin-top: 12px">
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <label for="nome">Fabricante / Fornecedor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           READONLY value="{$pessoaNome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-6" >
                                <label for="codFabricante">C&oacute;d. Fabricante</label>
                                <input class="form-control" type="text" size="30" name="codFabricante" placeholder="Código produto fabricante" maxlength="25" value={$codFabricante}>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-6" >
                                <label for="codBarras">C&oacute;digo Barras/EAN</label>
                                <input class="form-control" type="text" name="codBarras" required="true" placeholder="Código de barras do produto" maxlength="25" value={$codBarras}>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="localizacao">Localiza&ccedil;&atilde;o</label>
                                <input class="form-control" type="text"  name="localizacao" maxlength="10" placeholder="Localização f&iacute;sica do produto" value={$localizacao}>
                            </div>


                        </div>

                        <div class="row">

                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="ncm">NCM</label>
                                <select class="js-example-basic-single form-control" name="ncm" id="ncm" required="true">
                                        {html_options values=$ncm_ids selected=$ncm output=$ncm_names}
                                </select>
                            </div>

                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="cest">CEST</label>
                                <input class="form-control" type="text" size="30" name="cest" maxlength="15" placeholder="Cest" value={$cest}>
                            </div>

                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="anp">ANP</label>
                                <select class="js-example-basic-single form-control" name="anp" id="anp">
                                        {html_options values=$anp_ids selected=$anp output=$anp_names}
                                </select>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="cep">Origem</label>
                                <select name="origem" class="form-control" required="true">
                                    {html_options values=$origem_ids selected=$origem output=$origem_names}
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="tipo">Tributa&ccedil;&atilde;o ICMS</label>
                                <select class="form-control" name=tribIcms required="true">
                                    {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                </select>
                            </div>

                        </div>              
                                                 
                  </div>
                </div>
            </form>

                            
                <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="quantidade-tab" role="tab" data-toggle="tab" aria-expanded="true">Quantidade</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="valores-tab" data-toggle="tab" aria-expanded="false">Valores</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="promocao-tab" data-toggle="tab" aria-expanded="false">Promo&ccedil;&atilde;o</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content5" role="tab" id="dados-tab" data-toggle="tab" aria-expanded="false">Dados Adicionais</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content6" role="tab" id="dados-tab" data-toggle="tab" aria-expanded="false">Equivalencia / Ultima Compra</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content8" role="tab" id="dados-tab" data-toggle="tab" aria-expanded="false">Reparos</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content7" role="tab" id="dados-tab" data-toggle="tab" aria-expanded="false">Tabela de Preço</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                            <div class="row">

                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantMinima">Estoque M&iacute;nimo</label>
                                    <input class="form-control money" type="text" maxlength="10" name="quantMinima" placeholder="Qtda. M&iacute;nima no estoque" value={$quantMinima}>
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantMaxima">Estoque M&aacute;ximo</label>
                                    <input class="form-control money" type="text" maxlength="10" name="quantMaxima" placeholder="Qtda. M&aacute;xima no estoque" value={$quantMaxima}>
                                </div>

                                 <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantAtual">Quantidade Dispon&iacute;vel</label>
                                    <!--div class="input-group"-->
                                        <input type="text" class="form-control money" maxlength="10" id="quantAtual" name="quantAtual"
                                               value="{$quantAtual}">
                                        <!--span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" id="btnSaldoAtual">
                                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                            </button>
                                        </span-->                                
                                    <!--/div-->
                                </div>

                                {if $subMenu neq "cadastrar"}
                                
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantReservada">Quantidade Reservada</label>
                                    <input type="text" class="form-control money" id="quantReservada" name="quantReservada" value="{$quantReservada}">
                                </div>

                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantTotal">Quantidade TOTAL</label>
                                    <input type="text" class="form-control money" id="quantTotal" name="quantTotal" value="{$quantTotal}">
                                </div>

                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="quantNova">NOVA Quantidade</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control money" title="Informar a quantidade positiva ou negativa para somar com a quantidade atual" id="quantNova" name="quantNova">
                                        </div>
                                        <div class="col-sm-4">
                                            <button class="btn btn-danger" id="btnAjustaEstoque" title="Clique para ajustar o estoque" onClick="javascript:submitAjustaEstoque();">
                                                Ajusta Estoque
                                            </button>                                           
                                        </div>
                                    </div>                                    
                                </div> 

                                {else}
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="quantReservada">Quantidade Reservada</label>
                                    <input type="text" class="form-control money" id="quantReservada" name="quantReservada" value="{$quantReservada}">
                                </div>

                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="quantTotal">Quantidade TOTAL</label>
                                    <input type="text" class="form-control money" id="quantTotal" name="quantTotal" value="{$quantTotal}">
                                </div>
                                {/if}                                                           

                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                            <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="custoCompra">&Uacute;ltima Compra</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="custoCompra" name="custoCompra"
                                        maxlength="9" onchange="javascript:calculaTotal();" value={$custoCompra}>
                                     <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                    </div>  
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="custoMedio">Custo M&eacute;dio</label>
                                    <div class="panel panel-default">
                                    <input class="form-control money has-feedback-left" type="money" id="custoMedio" name="custoMedio"
                                        maxlength="9" onchange="javascript:calculaTotal();" value={$custoMedio}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                    </div>  
                                </div>
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="custoReposicao">Custo Reposi&ccedil;&atilde;o</label>
                                    <input class="form-control money has-feedback-left" type="text" id="custoReposicao" name="custoReposicao" 
                                           placeholder="Valor de reposição" onchange="javascript:calculaTotal();"  value={$custoReposicao}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                </div>

                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="precoInformado">Valor Informado Custo</label>
                                    <input class="form-control money has-feedback-left" type="text" id="precoInformado" name="precoInformado" 
                                           placeholder="Digite o valor informado custo." onchange="javascript:calculaTotal();"  value={$precoInformado}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                </div>
                                
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="precoMinimo">Pre&ccedil;o M&iacute;nimo</label>
                                    <input class="form-control money has-feedback-left" type="text" id="precoMinimo" id="precoMinimo" name="precoMinimo" 
                                           placeholder="Digite o valor mínimo." value={$precoMinimo}>
                                <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="precoBase">Pre&ccedil;o Base</label>
                                    <select class="form-control money" name="precoBase" onchange="javascript:calculaTotal();">
                                        {html_options values=$precoBase_ids selected=$precoBase_id output=$precoBase_names}
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="percCalculo">C&aacute;lculo</label>
                                    <input class="form-control money has-feedback-left" type="text" id="percCalculo" name="percCalculo" 
                                           placeholder="% calculo preço venda." onchange="javascript:calculaTotal();" value={$percCalculo}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>&#37;</b></span>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="venda">Pre&ccedil;o Venda</label>
                                    <input class="form-control money has-feedback-left" type="text" id="venda" name="venda" 
                                           placeholder="Valor de venda" onchange="javascript:calculaPerc();" value={$venda}>
                                    <span class="form-control-feedback left" aria-hidden="true" id="CssSifrao"><b>R$</b></span>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="dataChange">Data de Altera&ccedil;&atilde;o</label>
                                <input class="form-control" type="text" READONLY size="15" 
                                       id="dateChange" name="dateChange" data-inputmask="'mask': '99/99/9999'" placeholder="Data de Alteração" value={$dateChange} >
                                </div>
                            </div>

                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                            <div class="row">

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="precoPromocao">Valor Unitário</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" type="money" id="precoPromocao" name="precoPromocao"
                                            required="required"  maxlength="9" value={$precoPromocao}>
                                        <span class="form-control-feedback left" aria-hidden="true" id="Cpromo"><b>R$</b></span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                    <label for="inicioPromocao">Data Inicio</label>
                                    <input class="form-control has-feedback-left" type="text" id="inicioPromocao" 
                                           name="inicioPromocao" data-inputmask="'mask': '99/99/9999'" 
                                           placeholder="Data Inicio da Promoção." value={$inicioPromocao}>
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                                
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                    <label for="fimPromocao">Data Fim</label>
                                    <input class="form-control has-feedback-left" type="text" id="fimPromocao" 
                                           name="fimPromocao" data-inputmask="'mask': '99/99/9999'" 
                                           placeholder="Data Fim da Promoção." value={$fimPromocao}>
                                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="quantLimite">Q. Limite</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="money" id="quantLimite" name="quantLimite"
                                            required="required"  maxlength="11" value={$quantLimite}>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="tipoPromocao">Tipo Promoção</label>
                                    <select class="form-control" name="tipoPromocao" >
                                        {html_options values=$tipoPromocao_ids selected=$tipoPromocao_id output=$tipoPromocao_names}
                                    </select>
                                </div>
                            </div>

                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="bloqueado">Data Cadastro</label>
                                    <input class="form-control" type="text" READONLY size="15" name="dataCadastro" value={$dataCadastro}>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="codProdutoAnvisa" >Código Produto Anvisa</label>
                                    <input class="form-control" type="text"  name="codProdutoAnvisa" maxlength="45" placeholder="Código produto registrado na ANVISA" value={$codProdutoAnvisa}>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6  text-left">
                                    <label for="peso">Peso</label>
                                    <div class="panel panel-default">
                                        <input class="form-control money has-feedback-left" type="money" id="peso" name="peso"
                                            required="required"  maxlength="9" value={$peso}>
                                        <span class="form-control-feedback left" aria-hidden="true" id="Cpromo"><b>Kg</b></span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                <label for="dataForaLinha">Data Fora Linha</label>
                                <input class="form-control has-feedback-left" type="text" size="15" 
                                       id="dataForaLinha" name="dataForaLinha" data-inputmask="'mask': '99/99/9999'" placeholder="Data produto sai de Linha" value={$dataForaLinha}>
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                                
                            </div>

                            </div>
                                
                        </div>
                                
                                
                        <!-- TAB EQUIVALENCIA -->        
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content6" aria-labelledby="profile-tab">

                            <div class="col-md-2 small col-sm-6 col-xs-6">
                                    <label for="codEquivalente">Código Equivalente</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" maxlength="25" id="codEquivalente" name="codEquivalente"
                                            required="required" value={$codEquivalente}>                
                                    </div>  
                            </div>

                            <div class="col-md-5 small col-sm-12 col-xs-12">
                                <label for="nomeEquiv">Fabricante / Fornecedor</label>
                                <div class="input-group" input-sm>
                                    <input type="text" class="form-control input-sm" id="nomeEquivalente" name="nomeEquivalente"
                                           READONLY placeholder="Conta" value="{$nomeEquivalencia}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarequivalente');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>

                            <div class="col-md-1 small col-sm-12 col-xs-12">
                                    <label for="nfUltimaCompraEquiv">Número NF</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="money" maxlength="11" id="nfUltimaCompraEquiv" name="nfUltimaCompraEquiv"
                                            required="required" value={$nfUltimaCompraEquiv}>        
                                    </div>
                                </div>

                            <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                <label for="dataUltimaCompraEquiv">Data Ultima Compra</label>
                                <input class="form-control input-sm" type="text" id="dataUltimaCompraEquiv" 
                                       name="dataUltimaCompraEquiv" data-inputmask="'mask': '99/99/9999'" 
                                       value={$dataUltimaCompraEquiv}>
                            </div>

                            <div class="col-md-2 small col-sm-12 col-xs-12">
                                    <label for="quantUltimaCompraEquiv">Quantidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="money" maxlength="11" id="quantUltimaCompraEquiv" name="quantUltimaCompraEquiv"
                                            required="required" placeholder="Quantidade Ultima Compra." value={$quantUltimaCompraEquiv}>                
                                    </div>  
                            </div>

                            <div class="col-md-1 col-sm-12 col-xs-12 has-feedback" id="btnAdd">
                                <button type="button" class="btn btn-success"  onClick="javascript:submitConfirmarEquivalencia();">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> </span></button>                            
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 has-feedback">
                                <table id="datatable-buttons" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: #2A3F54; color: white;">
                                            <th>Código Equivalente</th>                                    
                                            <th>Conta</th>                                    
                                            <th>Numero NF</th>
                                            <th>Data Ultima Compra</th>
                                            <th>Quantidade</th>
                                            <th style="width: 120px;">Excluir</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$equiv}
                                            <tr>
                                                <td> {$equiv[i].CODEQUIVALENTE} </td>
                                                <td> {$equiv[i].NOME} </td>
                                                <td> {$equiv[i].NFULTIMACOMPRA} </td>
                                                <td> {$equiv[i].DATAULTIMACOMPRA|date_format:"%d/%m/%Y"} </td>
                                                <td> {$equiv[i].QUANTULTIMACOMPRA} </td>
                                                <td >
                                                    <button type="button" title="Deletar" class="btn btn-danger btn-xs" onclick="javascript:submitExcluirEquivalencia('{$equiv[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button-->
                                                </td>
                                            </tr>
                                        {/section} 

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <!-- REPAROS -->        
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content8" name="tab_content8" aria-labelledby="profile-tab">
                            <!-- Var de teste se existe item -->
                            <input name=prodExiste id=prodExiste type=hidden value="{$prodExiste}">
                            <div class="col-md-2 small col-sm-6 col-xs-6">
                                    <label for="reparoCodProduto">Código Produto</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" maxlength="25" id="reparoCodProduto" name="reparoCodProduto"
                                            required="required" onblur="javascript:buscaProdReparos();" value={$reparoCodProduto}>                
                                    </div>  
                            </div>

                            <div class="col-md-2 small col-sm-6 col-xs-6">
                                    <label for="reparoCodFabricante">Código Fabricante</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" maxlength="25" id="reparoCodFabricante" name="reparoCodFabricante"
                                            onblur="javascript:buscaProdReparos();" value={$reparoCodFabricante}>            
                                    </div>  
                            </div>

                            <div class="col-md-6 small col-sm-12 col-xs-12">
                                <label for="reparoProdDesc">Produto</label>
                                <div class="input-group" input-sm>
                                    <input type="text" class="form-control input-sm" id="reparoProdDesc" name="reparoProdDesc"
                                           READONLY placeholder="Descrição Produto" value="{$reparoProdDesc}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=pesquisarReparo');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>

                            <div class="col-md-1 small col-sm-12 col-xs-12">
                                    <label for="reparoQuant">Quantidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm money" ype="number" maxlength="11" id="reparoQuant" name="reparoQuant"
                                            required="required" value={$reparoQuant}>        
                                    </div>
                                </div>

                            <div class="col-md-1 col-sm-12 col-xs-12 has-feedback" id="btnAdd">
                                
                                    <button type="button" class="btn-sm btn-success btnInclui"  onClick="javascript:submitConfirmarReparo();">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> </span></button>
                                
                                    <button type="button" class="btn-sm btn-danger btnLimpa" onClick="javascript:limpaInpReparos();">
                                    <span class="glyphicon glyphicon-erase" aria-hidden="true" title="Limpar Campos"></span></button>                            
                                
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 has-feedback">
                                <table id="datatable-buttons" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: #2A3F54; color: white;">
                                            <th>Código Produto</th>                                    
                                            <th>Código Fabrcante</th>                                    
                                            <th>Descrição</th>                                    
                                            <th>Quantidade</th>
                                            <th style="width: 120px;">Excluir</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$reparo}
                                            <tr>
                                                <td> {$reparo[i].PRODUTO_ID} </td>
                                                <td> {$reparo[i].CODFABRICANTE} </td>
                                                <td> {$reparo[i].DESCRICAO} </td>
                                                <td> {$reparo[i].QUANT|number_format:2:",":"."} </td>
                                                <td >
                                                    <button type="button" title="Deletar" class="btn btn-danger btn-xs" onclick="javascript:submitExcluirReparo('{$equiv[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button-->
                                                </td>
                                            </tr>
                                        {/section} 

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <!-- TAB TABELA DE PRECO --> 
                        <div role="tabpanel" class="tab-pane fade" id="tab_content7" aria-labelledby="profile-tab">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 has-feedback">
                                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                                        <thead>
                                            <tr style="background: #2A3F54; color: white;">
                                                <th>Tabela</th>                                    
                                                <th>Validade</th>                                    
                                                <th>Preço Base</th>
                                                <th>Margem</th>
                                                <th>Preço Final</th>
                                                <th style="width: 120px;">Alterar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=i loop=$tabela}
                                                <tr>
                                                    <td> {$tabela[i].NOME} </td>
                                                    <td> {$tabela[i].VALIDADE} </td>
                                                    <td> {$tabela[i].PRECOBASE} </td>
                                                    <td> {$tabela[i].MARGEM} </td>
                                                    <td> {$tabela[i].PRECOFINAL} </td>
                                                    <td >
                                                      <button type="button" title="Alterar" class="btn btn-danger btn-xs" onclick="javascript:submitAlterarItemTabela('{$tabela[i].ID}','{$tabela[i].CODIGO}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                                    </td>
                                                </tr>
                                            {/section} 
                                        </tbody>
                                    </table>
                                </div>                                
                            </div>
                        </div>

                    </div>
                </div> <!-- tabpanel -->
            </div> <!-- panel -->
                            
                            
                                    
              </div>
            </div>

                        
        </form>
      </div>

    {include file="template/form.inc"}  
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    
    <script>
    $("#grupo.js-example-basic-single").select2({
        placeholder: "Selecione o grupo",
        language: "pt-br",
        allowClear: true
    });
    </script>

    <script>
      $(document).ready(function() {
        $("#ncm.js-example-basic-single").select2({
        });
      });

      $(function() {
        $('#dataForaLinha').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });        
        $('#inicioPromocao').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });        
        $('#fimPromocao').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });  
        
	$('#btnSaldoAtual').click(function(){
            //$.getJSON('index.php?mod=est&form=produto&submenu=quant',{}, function(data){
//            var id=$("#id").attr("value");
//            $.getJSON('../astecv3/class/est/c_produto_quant.php', { id: id}, function(data){
            $.getJSON('../astecv3/class/est/c_produto_quant.php',{}, function(data){
                console.log(data);
                var quantidade = data[0].saldo;	
                //console.log(data);
                console.log(quantidade);
                //$('#quantAtual').html(quantidade).show;            
                $('#quantAtual').val(quantidade);
            });
        });        
      });
    </script>
    
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true
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

.form-control:focus {
    border-color: #159ce4;
    transition: all 0.7s ease;
}

#Cpromo{
    margin: 30px 0px 0px 0px;
}

#CssSifrao{
    margin: 30px 0px 0px 0px;
}

#btnAdd{
    margin: 18px 0px 0px 0px;
}
.btnLimpa{
    margin-left: -5px;
}
.btnInclui{
    margin-left: -8px;
}
.form-control{
    border-radius: 10px;
}
.select2-selection--single, .select2-results__option{
    border-radius: 10px !important;
}
</style>