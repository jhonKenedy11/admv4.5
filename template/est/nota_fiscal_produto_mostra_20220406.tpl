<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_produto.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="small">
            <div class="page-title">
              <div class="title_left">
                <h3>Nota Fiscal - Produtos</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success small" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                                {else}        
                                        <div class="alert alert-error small" role="alert">Erro!&nbsp;{$mensagem}</div>
                                {/if}
                            {/if}
                        </strong>
                    </h2>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <input class="form-control input-sm" type="text" readonly id="situacao"  name="situacao" value={$situacao_name}>
                        </div>                    
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-success"  onClick="javascript:submitVoltarNfMostra({$idnf});">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                    {if $opcao neq "receber"}
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro({$idnf});">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
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
                    <form id="mostra" name="mostra" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value='est'>
                        <input name=form          type=hidden value=''>
                        <input name=opcao         type=hidden value={$opcao}>   
                        <input name=id            type=hidden value={$id}>
                        <input name=idnf          type=hidden value={$idnf}>
                        <input name=pessoa        type=hidden value={$pessoa}>
                        <input name=codProduto    type=hidden value={$codProduto}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=letra         type=hidden value={$letra}>

                        <div class="row">
                            <div class="col-md-1 col-sm-6 col-xs-6">
                                <label for="id">Modelo</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text" readonly id="modelo"  name="modelomostra" value={$modelo}>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-6 col-xs-6">
                                <label for="serie">S&eacute;rie</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text"  readonly maxlength="2" placeholder="Serie NFe." id="serie" name="serie" value={$serie}>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6 text-left">
                                <label for="numero">N&uacute;mero</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text" readonly maxlength="11"  placeholder="Numero NFe." id="numero" name="numero" value={$numero}>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="tipo">Tipo Nota</label>
                                <div class="input-group-sm">
                                    <input class="form-control input-sm" type="text" readonly id="tipo"  name="tipo" value={$tipo_name}>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="emissao">Emiss&atilde;o</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text" readonly name="emissao" value={$emissao}>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="totalnf">Total</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-sm">
                                        <button class="btn btn-default" type="button">R$
                                        </button>
                                    </span>
                                    <input class="form-control input" type="text" readonly  name="totalnf" value={$totalnf}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nome">Pessoa</label>
                                <div class="input-group-sm">
                                    <input type="text" required="required" class="form-control input-sm" id="nome" name="nome" 
                                           readonly value="{$pessoaNome}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nop">Natureza Opera&ccedil;&atilde;o</label>
                                <div class="input-group-sm">
                                    <input class="form-control input-sm" type="text" readonly id="nop"  name="nop" value={$natOperacao_name}>
                                </div>
                            </div>
                        </div>
                    </form>

                  </div>
                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->

        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel small">
                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>Num Item</th>
                            <th>Cód Interno</th>
                            <th>Cód Fabricante</th>
                            <th>Cód Nota</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Uni</th>		
                            <th>Qtde</th>
                            <th>Vl. Uni</th>
                            <th>Vl. Desc</th>
                            <th>Vl. Total</th>		
                            <th>CFOP</th>		
                            <th>Data Fab.</th>                    
                            <th>Status Prod.</th>                    
                            {if $opcao eq "recebimento"}
                                <th>Receber Produto</th>
                            {else}
                            <th>Manuten&ccedil;&atilde;o</th>
                            {/if}
                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="num" value=$num+1}
                            {assign var="total" value=$total+$lanc[i].TOTAL}

                            {if $lanc[i].DATACONFERENCIA eq "0000-00-00 00:00:00" or $lanc[i].DATACONFERENCIA eq ""}
                                <tr bgcolor="{cycle values="#EBEBEB,#AAAAAA"}" color="black" class="DestacaLinha">
                                {else}

                                <tr bgcolor="#FFA500" color="black">

                                {/if}	

                                <td> {$num} </td>
                                <td> {$lanc[i].CODPRODUTO} </td>
                                <td> {$lanc[i].CODFABRICANTE} </td>
                                <td> {$lanc[i].CODIGONOTA} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td> {$lanc[i].QUANT|number_format:2:",":"."} </td>
                                <td> {$lanc[i].UNITARIO|number_format:4:",":"."} </td>
                                <td> {$lanc[i].DESCONTO|number_format:4:",":"."} </td>
                                <td> {($lanc[i].TOTAL+$lanc[i].FRETE+$lanc[i].DESPACESSORIA-$lanc[i].DESCONTO)|number_format:2:",":"."} </td>
                                <td> {$lanc[i].CFOP} </td>
                                <td> {$lanc[i].DATAFABRICACAO|date_format:"%e %b, %Y"} </td>
                                {if $lanc[i].DATACONFERENCIA eq "0000-00-00 00:00:00" or $lanc[i].DATACONFERENCIA eq ""}
                                    <td> N&atilde;o Recebido</td>
                                {else}
                                    <td> Recebido </td>
                                {/if}	
                                {if $opcao eq "receber"}
                                    <td> 
                                    <button type="button" title="Receber Produto" class="btn btn-primary btn-xs" 
                                            onclick="javascript:submitBaixar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-download" aria-hidden="true"></span></button>
                                    </td>
                                {else}
                                    <td>
                                    <a  href="javascript:submitAlterar('{$lanc[i].ID}');"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                    <a  href="javascript:submitExcluir('{$lanc[i].ID}');"><i class="fa fa-trash fa-lg red" aria-hidden="true"></i></a>
                                    </td>

                                {/if}


                            </tr>
                            <p>
                        {/section} 

                    </tbody>
                </table>

              </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
          </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->



    {include file="template/database.inc"}  
                                    
