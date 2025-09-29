<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_produto.js"> </script>
<!-- page content -->
<div class="right_col" role="main">      
    <div class="small">

        <div class="page-title">
          <div class="title_left">
              <h3>Nota Fiscal</h3>
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
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        Recebimento Produto
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success small" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {else}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger small" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
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
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarProduto('{$id}');">
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

                        <div class="row">
                            <a href=javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar')>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="codProduto">C&oacute;digo</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="codProduto" name="codProduto" value={$codProduto}>
                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-6 col-xs-6  text-left">
                                    <label for="descProduto">Produto</label>
                                    <div class="form-group input-group">
                                        <input class="form-control input-sm" type="text" id="descProduto" name="descProduto" value={$descProduto}>
                                        <span class="input-group-btn">
                                            <button class="btn-sm btn-primary" type="button"><i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                <label for="cfop">CFOP</label>
                                <div class="panel panel-default">
                                    <input class="form-control input-sm" type="text" name="cfop" value={$cfop}>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="quant">Quantidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="quant"  name="quant" onblur="soma()" value={$quant}>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6  text-left">
                                    <label for="unidade">Unidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text"  name="unidade" value={$unidade}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="unitario">Valor Unitário</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="unitario" name="unitario" onblur="soma()" value={$unitario}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="desconto">Desconto</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="desconto" onblur="soma()"  name="desconto" value={$desconto}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqIpi">Aliquota IPI</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="aliqIpi" name="aliqIpi" value={$aliqIpi}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="valorIpi">Valor IPI</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="valorIpi" name="valorIpi" value={$valorIpi}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="total">TOTAL PRODUTO</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="total" name="total" onblur="soma()" value={$total}>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="origem">Origem</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input-sm" name=origem>
                                            {html_options values=$origem_ids selected=$origem output=$origem_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-6  text-left">
                                    <label for="tribIcms">Tributação ICMS</label>
                                    <div class="panel panel-default">
                                        <select class="form-control input-sm" name=tribIcms>
                                            {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="bcIcms">Base Calculo ICMS</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" name="bcIcms" value={$bcIcms}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="aliqIcms">Aliquota ICMS</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="aliqIcms" name="aliqIcms" value={$aliqIcms}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="valorIcms">Valor ICMS</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" id="valorIcms" name="valorIcms" value={$valorIcms}> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="ncm">NCM</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text"  name="ncm" value={$ncm}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="cest">CEST</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text"  name="cest" value={$cest}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="ordem">OS. Parceiro</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" name="ordem" value={$ordem}>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6  text-left">
                                    <label for="nrSerie">N&uacute;mero de S&eacute;rie</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text"  name="nrSerie"  value={$nrSerie}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-left">
                                    <label for="lote">Lote</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" placeholder="Lote do produto." id="lote" name="lote" value={$lote}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataFabricacao">Data Fabrica&ccedil;&atilde;o</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataFabricacao" name="dataFabricacao" value={$dataFabricacao}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataValidade">Data Validade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataValidade"  name="dataValidade" value={$dataValidade}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="dataGarantia">Data Garantia</label>
                                    <div class="panel panel-default">
                                        <input class="form-control input-sm" type="text" placeholder="dd/mm/aaaa" data-inputmask="'mask': '99/99/9999'" id="dataGarantia" name="dataGarantia" value={$dataGarantia}>

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
</body>            
            
            
            
            
            <table width="650" border="0" align="center">
                <tr>
                    <td width="10" class="MarcadorTitulo"><div align="center"><b>::</b></div></td>
                    <td width='550' class='TituloPagina'> <b>
                            {if $subMenu eq "baixar"}
                                Recebimento Produto Nota Fiscal
                            {/if}

                        </b></td>	
                </tr>
                <tr>
                    <td></td>
                    <td width="650" class="MarcadorTitulo"> <b>{$mensagem} </b></td>
                </tr>	
                <tr>
                    <td class="Pesquisa" colspan="4" height="1"></td>
                </tr>
            </table>
            <br>
            <table  width="950" border="1" align="center">
                <tr>
                    <td class="ColunaSubTitulo" colspan="2"><b><u>Identifica&ccedil;&atilde;o Nota Fiscal</u></b></td>
                    <td class="ColunaTitulo"  align="right" colspan="2">
                        <b>Situa&ccedil;&atilde;o:
                            <SELECT name="situacaomostra" DISABLED> 
                            {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}</b>
                        </SELECT>
                    </td>
                </tr>
                <tr DISABLED>
                    <td class="ColunaTitulo">
                        Modelo:
                        <input type="text" size="3" name="modelomostra" value={$modelo}>
                    </td>
                    <td class="ColunaSubTitulo">
                        S&eacute;rie:
                        <input type="text" size="5" name="serie" value={$serie}>
                        N&uacute;mero:
                        <input type="text" size="8" name="numero" value={$numero}>
                    </td>
                    <td  class="ColunaTitulo">Filial:
                    
                        <select name=centroCusto>
                            {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                        </select>
                    </td>
                </tr>
                <tr DISABLED>
                    <td class="ColunaTitulo" colspan="2">
                        Pessoa:
                    
                        <input type="text" size="11" name="pessoa" value={$pessoa}>
                        <input type="text" size="60" name="nome" disabled value={$pessoaNome}>
                        <a href=javascript:abrir('../pss/p_pessoa.php?pesquisa=lancamento')>
                            <img src='{$pathImagem}/ico_pesquisa.gif' width=14 height=14 border=0> </a>	
                    <td class="ColunaTitulo" colspan="2">
                        Tipo Nota Fiscal:
                    
                        <SELECT name="tipo"> 
                            {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                        </SELECT>
                    </td>
                </tr>
                <tr>
                    <td class="ColunaTitulo" colspan="4" >
                        Produto:
                        <input type="text" size="11" name="codProduto" value={$codProduto}>
                        <input type="text" size="60" name="descricao" value={$descricao}>           
                    </td>
                </tr>
                <tr>
                    <td class="ColunaTitulo">
                        Quantidade:
                        <input type="text" size="8" name="quant" value={$quant}>
                        Valor Unit&aacute;rio:
                        <input type="text" size="12" name="unitario" value={$unitario}>
                    </td>
                    <td class="ColunaSubTitulo" >
                        <b>Numero de S&eacute;rie:</b>
                        <input type="text" size="20" name="numSerie" value={$numSerie}>


                    </td>
                    <td class="ColunaSubTitulo" colspan="2">
                        <b>Localiza&ccedil;&atilde;o:</b>
                        <input type="text" size="20" name="localizacao" value={$localizacao}>

                    </td>
                
                    
                </tr>
                <tr>
                    <td class="ColunaSubTitulo"> <b>Projeto: </b>
                        <SELECT name="projeto"> 
                            {html_options values=$projeto_ids output=$projeto_names selected=$projeto_id}
                        </SELECT>
                    </td>
                    <td class="ColunaSubTitulo">
                        Ordem de Servi&ccedil;o:
                        <input type="text" size="15" name="ordem" value={$ordem}>

                    </td>
                    <td class="ColunaSubTitulo" colspan="2">
                        Data conferencia:
                        <input type="text" size="19" name="dataConferencia" value={$dataConferencia}>

                    </td>
                </tr>


            </table>





            <table width="211" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="179" class="ColunaSubTitulo1">
                        <div align="center">
                            <input type="button" name="button_envia" value="Confirmar" class="CoresBotao" onClick="javascript:submitConfirmarBaixa('NotaFiscal');">
                            <input type="button" name="button_limpa" value="Cancelar" class="CoresBotao" onClick="javascript:submitVoltarRecebimento({$idnf});">
                        </div></td>
                </tr>

            </table> 
        </form>
    </body>
</html>


