<style>
.form-control, .x_panel {
    border-radius: 5px;
}
td{
    font-size: 10px;
}
tr{
    font-size: 10px;
}
.NoProd{
    color: #022f51;
    text-shadow: 0 1px 0 #ccc,
    0 2px 0 #c9c9c9,
    0 3px 0 #bbb,
    0 4px 0 #b9b9b9,
    0 5px 0 #aaa,
    0 6px 4px rgba(0,0,0,.1),
    0 0 5px rgba(0,0,0,.1),
    0 1px 3px rgba(0,0,0,.3),
    0 3px 5px rgba(0,0,0,.2),
    0 5px 10px rgba(0,0,0,.25),
    0 10px 10px rgba(0,0,0,.2),
    0 20px 20px rgba(0,0,0,.15);
}
.panel-body{
    padding: 0;
}
</style>
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

                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label>C&oacute;digo</label>
                        <input class="form-control" id="codFabricante" name="codFabricante" autofocus placeholder="Código Fabricante."  value={$codFabricante} >
                    </div>
                    <div class="form-group col-md-10 col-sm-10 col-xs-12">
                        <label>Descri&ccedil;&atilde;o {$from}</label>
                        <input class="form-control" id="produtoNome" name="produtoNome"  placeholder="Digite a descrição"  value="{$produtoNome}" >
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
                            <li role="presentation" class="{$active05}"><a href="#tab_content5" role="tab" id="dados-tab-cotacao" data-toggle="tab" aria-expanded="true">Cota&ccedil;&atilde;o</a>
                            </li>
                            <li role="presentation" class="{$active06}"><a href="#tab_content6" role="tab" id="dados-tab-pedido" data-toggle="tab" aria-expanded="true">Pedido</a>
                            </li>                       
                            <li role="presentation" class="{$active07}"><a href="#tab_content7" role="tab" id="dados-tab-reparo" data-toggle="tab" aria-expanded="true">Reparo</a>
                            </li>                       
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {$activeTab01} small" id="tab_content1" aria-labelledby="home-tab">
                                <div class="panel-body">
                                    <div class="x_panel small">
                                        <div class="col-md-8 col-sm-8 col-xs-8 tabPrincipal">
                                        <table id="datatable" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th style="width: 10px;"></th>
                                                    <th style="width: 70px;"><center>C&oacute;digo</center></th>
                                                    <th style="width: 50px;"><center>C&oacute;d. nota</center></th>
                                                    <th style="width: 100px;"><center>C&oacute;d. Fabricante</center></th>
                                                    <th style="width: 50px;">Local.</th>
                                                    <th><center> Descri&ccedil;&atilde;o</center></th>
                                                    <th style="width: 70px;"><center>Unidade</center></th>
                                                    <th><center>Venda</center></th>
                                                    <th><center>Qtd Disp</center></th>
                                                    <th style="width: 50px;">Selec.</th>

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
                                                        <td><center> {$lanc[i].CODIGO} </center></td>
                                                        <td><center> {$lanc[i].CODPRODUTONOTA} </center></td>
                                                        <td><center> {$lanc[i].CODFABRICANTE} </center></td>
                                                        <td><center> {$lanc[i].LOCALIZACAO} </center></td>
                                                        <td> {$lanc[i].DESCRICAO} </td>
                                                        <td><center> {$lanc[i].UNIDADE} </center></td>
                                                        <td name="vlrVenda"><center> {$lanc[i].VENDA|number_format:2:",":"."} </center></td>
                                                        <td><center> {$lanc[i].ESTOQUE|number_format:2:",":"."} </center></td>
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
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                        <table id="datatable" class="table table-bordered jambo_table tabEqui">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th style="width: 10px;"></th>
                                                    <th style="display:none; width: 60px;">C&oacute;digo</th>
                                                    <th style="width: 80px;">C&oacute;d. Equiv</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th><center>Descri&ccedil;&atilde;o</center></th>
                                                    <th style="display:none;">Unidade</th>
                                                    <th style="display:none;">Venda</th>
                                                    <th style="display:none;">Qtd Disp</th>
                                                    <th style="width: 50px; ">Selec.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$equi}
                                                    <tr>
                                                        <td> 
                                                            <input type="checkBox"  name="pedidoChecked" id="pedidoChecked"
                                                            onClick="javascript:submitLetraPesquisa({$equi[i].CODIGO},'{$equi[i].CODFABRICANTE}');"/>
                                                        </td>
                                                        <td style="display:none;"><center> {$equi[i].CODIGO} </center></td>
                                                        <td><center> {$equi[i].CODEQUIVALENTE} <center></td>
                                                        <td><center> {$equi[i].CODFABRICANTE} <center></td>
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
                                                                onclick="javascript:fechaProdutoPesquisaOcEqui(this);">
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
                            
                            <!--TABELA COTACAO -->
                            <div role="tabpanel" class="tab-pane fade small {$activeTab05}" id="tab_content5" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                    {if $existeCotacao eq 'yes'}
                                        <table id="datatable-cot" class="table table-bordered jambo_table">
                                            <thead>
                                                 <tr style="background: #2A3F54; color: white;">
                                                    <th>Cota&ccedil;&atilde;o</th>
                                                    <th>Cliente</th>
                                                    <th>C&oacute;digo</th>
                                                    <th>C&oacute;d Fabricante</th>
                                                    <th>Qtd Solicitada</th>
                                                    <th>Emiss&atilde;o</th>
                                                    <th>Total Ped</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$cotacao}
                                                <tr>
                                                    <td name="total"> {$cotacao[i].ID} </td>
                                                    <td name="total"> {$cotacao[i].NOME} </td>
                                                    <td name="total"> {$cotacao[i].ITEMESTOQUE} </td>
                                                    <td name="total"> {$cotacao[i].ITEMFABRICANTE} </td>
                                                    <td name="total"> {$cotacao[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                    <td name="total"> {$cotacao[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                    <td name="total"> {$cotacao[i].TOTAL|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    {elseif $existeCotacao eq 'no'}
                                        <div>
                                            <h4 class="NoProd"><center>PRODUTO NÃO POSSUI COTAÇÃO</center></h4>
                                        </div>
                                    {else}
                                        <div>
                                            <h4></h4>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </div>
                            <!--FIM TABELA COTACAO -->
                            
                            <!--TABELA PEDIDO -->
                            <div role="tabpanel" class="tab-pane fade small {$activeTab06}" id="tab_content6" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                    {if $existePedido eq 'yes'}
                                        <table id="datatable-ped" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th>Cota&ccedil;&atilde;o</th>
                                                    <th>Cliente</th>
                                                    <th>C&oacute;digo</th>
                                                    <th>C&oacute;d Fabricante</th>
                                                    <th>Qtd Solicitada</th>
                                                    <th>Emiss&atilde;o</th>
                                                    <th>Total Ped</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$pedido}
                                                <tr>
                                                    <td name="total"> {$pedido[i].ID} </td>
                                                    <td name="total"> {$pedido[i].NOME} </td>
                                                    <td name="total"> {$pedido[i].ITEMESTOQUE} </td>
                                                    <td name="total"> {$pedido[i].ITEMFABRICANTE} </td>
                                                    <td name="total"> {$pedido[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                    <td name="total"> {$pedido[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                    <td name="total"> {$pedido[i].TOTAL|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    {elseif $existePedido eq 'no'}
                                        <div>
                                            <h4 class="NoProd"><center>PRODUTO NÃO POSSUI PEDIDO</center></h4>
                                        </div>
                                    {else}
                                        <div>
                                            <h4></h4>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </div>
                            <!--FIM TABELA PEDIDO -->  
                            <!--TABELA REPARO -->
                            <div role="tabpanel" class="tab-pane fade small {$activeTab07}" id="tab_content7" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                    {if $existeReparo eq 'yes'}
                                        <div class="col-md-2 small col-sm-2 col-xs-2">
                                                <label for="reparoQuant">Quantidade Adicionar do Reparo</label>
                                                <div class="panel panel-default">
                                                    <input class="form-control input-sm money" ype="number" maxlength="11" id="reparoQuant" name="reparoQuant"
                                                        required="required" value={$reparoQuant}>        
                                                </div>
                                            </div>

                                            <button type="button" class="btn-sm btn-success btnInclui"  onClick="javascript:submitConfirmarReparo();">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> </span></button>
                                        
                                        </div>


                                        <table id="datatable-ped" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                <th>Código Produto</th>                                    
                                                <th>Código Fabrcante</th>                                    
                                                <th>Descrição</th>                                    
                                                <th>Quant. utilizada</th>
                                                <th>Quant. Estoque</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$reparo}
                                                <tr>
                                                <td> {$reparo[i].PRODUTO_ID} </td>
                                                <td> {$reparo[i].CODFABRICANTE} </td>
                                                <td> {$reparo[i].DESCRICAO} </td>
                                                <td> {$reparo[i].QUANT|number_format:2:",":"."} </td>
                                                <td> {0|number_format:2:",":"."} </td>
                                                </tr>
                                                <p>
                                            {/section} 
                                            </tbody>
                                        </table>
                                    {elseif $existeReparo eq 'no'}
                                        <div>
                                            <h4 class="NoProd"><center>PRODUTO NÃO POSSUI REPARO</center></h4>
                                        </div>
                                    {else}
                                        <div>
                                            <h4></h4>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </div>
                            <!--FIM TABELA REPARO -->

                        </div>     
                    </div>
                </div> <!-- tabpanel -->                                         
            </div> <!-- div class="x_panel" = tabela principal-->
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
<script>
document.addEventListener("keypress", function (e) {
    if (e.keyCode === 13) {
        submitLetraPesquisa();
    }
});
</script>
