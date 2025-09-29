<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>

<div class="right_col" role="main">      

<div class="">
    <div class="page-title">
        <div class="title_left"><h3>Produtos</h3></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Consulta
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraPesquisa();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>


                <div class="x_content">

                <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                    class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                    <input name=mod           type=hidden value="est">   
                    <input name=form          type=hidden value="produto">   
                    <input name=id            type=hidden value="">
                    <input name=opcao         type=hidden value={$opcao}>
                    <input name=letra         type=hidden value={$letra}>
                    <input name=submenu       type=hidden value={$subMenu}>
                    <input name=grupo         type=hidden value="">
                    <input name=localizacao   type=hidden value="">
                    <input name=quant         type=hidden value="false">
                    <input name=codigo        type=hidden value="">
                    <input name=from          type=hidden value="{$from}">
                    <input name=quantAtual    type=hidden value="{$quantAtual}"> <!-- baixa estoque -->
                    <input name=valorVenda    type=hidden value="{$valorVenda}"> <!-- baixa estoque -->
                    <input name=uniFracionada type=hidden value="{$uniFracionada}"> <!-- baixa estoque -->
                    <input name=checkbox      type=hidden value="{$checkbox}">

                    <div class="form-group col-md-2 col-sm-3 col-xs-12">
                        <label>C&oacute;digo</label>
                        <input class="form-control" id="codFabricante" name="codFabricante" autofocus placeholder="Código Fabricante."  value={$codFabricante} >
                    </div>
                    <div class="form-group col-md-6 col-sm-8 col-xs-12">
                        <label>Descri&ccedil;&atilde;o {$from}</label>
                        <input class="form-control" id="produtoNome" name="produtoNome"  placeholder="Digite a descrição. {$from}"  value="{$produtoNome}" >
                    </div>
                    
                </form>
            
                </div>

            </div> <!-- x_panel -->
                          
        </div>  <!-- div row = painel principal-->



        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="{$active01}"><a href="#tab_content1" id="dados-tab" role="tab" data-toggle="tab" aria-expanded="true">Pesquisa</a>
                            </li>
                            <li role="presentation" class="{$active02}"><a href="#tab_content2" role="tab" id="rateio-tab" data-toggle="tab" aria-expanded="true">Notas</a>
                            </li>  
                            <li role="presentation" class="{$active03}"><a href="#tab_content3" role="tab" id="importacao-tabela-preco-tab" data-toggle="tab" aria-expanded="true">Tabela</a>
                            </li>   
                            <li role="presentation" class="{$active04}"><a href="#tab_content4" role="tab" id="dados-tab-estoque" data-toggle="tab" aria-expanded="true">Estoque</a>
                            </li>                          
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {$activeTab01} small" id="tab_content1" aria-labelledby="home-tab">
                                <div class="panel-body">
                                    <div class="x_panel small">
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                        <table id="datatable" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th></th>
                                                    <th>C&oacute;digo</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th>Localização</th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th>Unidade</th>
                                                    <th>Venda</th>
                                                    <th>Qtd Disp</th>
                                                    <th style="width: 80px;">Selecionar</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                {section name=i loop=$lanc}
                                                    {assign var="total" value=$total+1}
                                                    <tr>
                                                        <td> 
                                                            <input type="checkBox"  name="pedidoChecked" id="pedidoChecked"
                                                            {if ($pedidoChecked eq 'true')} checked {/if}
                                                            onClick="javascript:submitLetraPesquisa({$lanc[i].CODIGO},'{$lanc[i].CODFABRICANTE}', 'true');"/>
                                                        </td>
                                                        <td> {$lanc[i].CODIGO} </td>
                                                        <td> {$lanc[i].CODFABRICANTE} </td>
                                                        <td> {$lanc[i].LOCALIZACAO} </td>
                                                        <td> {$lanc[i].DESCRICAO} </td>
                                                        <td> {$lanc[i].UNIDADE} </td>
                                                        <td> {$lanc[i].VENDA|number_format:2:",":"."} </td>
                                                        <td> {$lanc[i].ESTOQUE|number_format:2:",":"."} </td>
                                                        <td class=" last">
                                                            {if $from eq 'nota'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoNf({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'baixa_estoque'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaParam({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','null', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'ped_telhas_novo'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaParam({$lanc[i].CODIGO}, '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','null', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'produto_ml'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaParam('{$lanc[i].CODIGO}', '{$lanc[i].DESCRICAO}','{$lanc[i].UNIDADE}','null', '{$lanc[i].ESTOQUE|number_format:2:",":"."}', '{$lanc[i].VENDA|number_format:2:",":"."}', '{$lanc[i].UNIFRACIONADA}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'ordem_compra'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaOC(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisa(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {/if}
                                                                    
                                                        </td>
                                                    </tr>
                                                {/section} 

                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                        <table id="datatable" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th></th>
                                                    <th style="display:none;">C&oacute;digo</th>
                                                    <th>C&oacute;d. Equivalente</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th>Descri&ccedil;&atilde;o</th>
                                                    <th style="display:none;">Unidade</th>
                                                    <th style="display:none;">Venda</th>
                                                    <th style="display:none;">Qtd Disp</th>
                                                    <th style="width: 80px;">Selecionar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$equi}
                                                    <tr>
                                                        <td> 
                                                            <input type="checkBox"  name="pedidoChecked" id="pedidoChecked"
                                                            onClick="javascript:submitLetraPesquisa({$equi[i].CODIGO},'{$equi[i].CODFABRICANTE}');"/>
                                                        </td>
                                                        <td style="display:none;"> {$equi[i].CODIGO} </td>
                                                        <td> {$equi[i].CODEQUIVALENTE} </td>
                                                        <td> {$equi[i].CODFABRICANTE} </td>
                                                        <td> {$equi[i].DESCRICAO} </td>
                                                        <td style="display:none;"> {$equi[i].UNIDADE} </td>
                                                        <td style="display:none;"> {$equi[i].VENDA|number_format:2:",":"."} </td>
                                                        <td style="display:none;" {$equi[i].ESTOQUE|number_format:2:",":"."} </td>
                                                        <td class=" last">
                                                            {if $from eq 'nota'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoNf({$equi[i].CODIGO}, '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'baixa_estoque'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaParam({$equi[i].CODIGO}, '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}','null', '{$equi[i].ESTOQUE|number_format:2:",":"."}', '{$equi[i].VENDA|number_format:2:",":"."}', '{$equi[i].UNIFRACIONADA}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'produto_ml'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaParam('{$equi[i].CODIGO}', '{$equi[i].DESCRICAO}','{$equi[i].UNIDADE}','null', '{$equi[i].ESTOQUE|number_format:2:",":"."}', '{$equi[i].VENDA|number_format:2:",":"."}', '{$equi[i].UNIFRACIONADA}');">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else if $from == 'ordem_compra'}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisaOC(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {else}
                                                                <button type="button" class="btn btn-success btn-xs" 
                                                                onclick="javascript:fechaProdutoPesquisa(this);">
                                                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                            {/if}
                                                                    
                                                        </td>
                                                    </tr>
                                                {/section} 
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade small {$activeTab02} small" id="tab_content2" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel ">
                                        <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th style="width:100px">Código Nota</th>
                                                <th style="width:100px">Tipo Doc</th>
                                                <th style="width:80px">Número</th>
                                                <th style="width:80px">Origem</th>
                                                <th style="width:80px">Documento</th>
                                                <th style="width:100px">Emissão</th>
                                                <th>Pessoa</th>
                                                <th style="width:80px">Quantidade</th>
                                                <th style="width:100px">Vlr Unitário</th>
                                                <th style="width:100px">% Desconto</th>
                                                <th style="width:100px">Vlr Uni Líquido</th>
                                                <th style="width:100px">Vlr ST</th>
                                                <th style="width:100px">Total Produto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$notas}
                                                {if $notas[i].NUMERO > 0}
                                                <tr>
                                                    <td name="codigoNota"> {$notas[i].CODIGONOTA} </td>
                                                    <td name="tipoDoc"> {$notas[i].TIPO} </td>
                                                    <td name="docto"> {$notas[i].NUMERO} </td>
                                                    <td name="tipo"> {$notas[i].ORIGEM} </td>
                                                    <td name="docto"> {$notas[i].DOC} </td>
                                                    <td name="emissao"> {$notas[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                                    <td name="cliente"> {$notas[i].NOME} </td>
                                                    <td name="qtsolicitada"> {$notas[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                    <td name="unitario"> R$ {$notas[i].UNITARIOPEDIDO|number_format:2:",":"."} </td>
                                                    <td name="percDesconto"> {$notas[i].PERCDESCONTO|number_format:2:",":"."} % </td>
                                                    <td name="unitario"> R$ {$notas[i].UNITARIOLIQUIDO|number_format:2:",":"."} </td>
                                                    <td name="totalItem"> {$notas[i].ST|number_format:2:",":"."} </td>
                                                    <td name="total"> R$ {$notas[i].TOTALITEM|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                                {/if}
                                            {/section} 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> 
                            
                            <div role="tabpanel" class="tab-pane fade small {$activeTab03}" id="tab_content3" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th width="25%">Fornecedor</th>
                                                <th width="15%">Cod Original</th>
                                                <th width="30%">Descricao</th>
                                                <th width="10%">Preço</th>
                                                <th width="10%">IPI</th>
                                                <th width="10%">Preço Venda</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$tabela}
                                                <tr>
                                                    <td name="total"> {$tabela[i].NOME} </td>
                                                    <td name="total"> {$tabela[i].CODORIGINAL} </td>
                                                    <td name="total"> {$tabela[i].DESCRICAO} </td>
                                                    <td name="total"> {$tabela[i].PRECO|number_format:2:",":"."} </td>
                                                    <td name="total"> {$tabela[i].IPI} </td>
                                                    <td name="total"> {$tabela[i].PRECOVENDA|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>  
                            <div role="tabpanel" class="tab-pane fade small {$activeTab04}" id="tab_content4" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <table id="datatable-est" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th>Filial</th>
                                                <th>Estoque</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$estoque}
                                                <tr>
                                                    <td name="centroCusto"> {$estoque[i].CENTROCUSTO} </td>
                                                    <td name="estoque"> {$estoque[i].ESTOQUE|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                                             
                        </div>     
                    </div>
                </div> <!-- tabpanel -->                                         
                









            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<style>
td{
    font-size: 10px;
}
tr{
    font-size: 10px;
}
</style>