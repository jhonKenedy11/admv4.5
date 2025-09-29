<style>
    .swal-title {
        font-size: 21px;
    }

    .swal-modal {
        width: 510px !important;
    }

    .height100 {
        height: 100vh;
        margin-top: 0;
        margin-bottom: 0;
        padding: 0;
    }

    .form-control {
        border-radius: 5px;
    }

    .select2-selection--single {
        border-radius: 5px !important;
    }

    table {
        border-spacing: 0;
        border-collapse: none !important;
    }

    .table-bordered>thead>tr>th {
        border-radius: 7px !important;
        padding: 5px !important;
    }
</style>

<section class="height100">
<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>

<div class="right_col" role="main">      

<div class="">

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Consulta Produto</h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-dark btn-xs" title="Limpar dados de pesquisa" onClick="javascript:limpaCampos();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                  <span class="fa fa-eraser" aria-hidden="true" title="Limpar dados de pesquisa"></span><span></span>
                            </button>
                        </li>
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
                    <input name=uniFracionada    type=hidden value="{$uniFracionada}"> <!-- baixa estoque -->


                    <div class="form-group col-md-6 col-sm-8 col-xs-12">
                        <label>Descri&ccedil;&atilde;o</label>
                        <input class="form-control" id="produtoNome" name="produtoNome" autofocus placeholder="Digite a descrição."  value="{$produtoNome}" >
                    </div>
                    <div class="form-group col-md-2 col-sm-4 col-xs-12">
                        <label>C&oacute;d. Fabricante</label>
                        <input class="form-control" id="codFabricante" name="codFabricante" placeholder="Código do Fabricante."  value={$codFabricante} >
                    </div>
                    <div class="form-group col-md-4 col-sm-12 col-xs-12">
                        <label for="produtoCombo">Produto</label>
                        <select class="js-example-basic-single form-control" name="produtoCombo" id="produtoCombo">
                                {html_options values=$produto_ids selected=$id_produto output=$produto_names}
                        </select>
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
                            <div role="tabpanel" class="tab-pane fade {$activeTab01}" id="tab_content1" aria-labelledby="home-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                        <table id="datatable" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th>C&oacute;digo</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th><center>Descri&ccedil;&atilde;o</center></th>
                                                    <th>Unidade</th>
                                                    <th>Venda</th>
                                                    <th>Qtd Disp</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                {section name=i loop=$lanc}
                                                    {assign var="total" value=$total+1}
                                                    <tr>
                                                        <td>{$lanc[i].CODIGO}</td>
                                                        <td> {$lanc[i].CODFABRICANTE} </td>
                                                        <td> {$lanc[i].DESCRICAO} </td>
                                                        <td> {$lanc[i].UNIDADE} </td>
                                                        <td> {$lanc[i].VENDA} </td>
                                                        <td> {$lanc[i].ESTOQUE|number_format:2:",":"."} </td>
                                                    </tr>
                                                {/section} 

                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                        <table id="datatable" class="table table-bordered jambo_table">
                                            <thead>
                                                <tr style="background: #2A3F54; color: white;">
                                                    <th>C&oacute;digo Equivalente</th>
                                                    <th>C&oacute;d. Fabricante</th>
                                                    <th style="width: 80px;">Selecionar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {section name=i loop=$equi}
                                                    <tr>
                                                        <td> {$equi[i].CODEQUIVALENTE} </td>
                                                        <td> {$equi[i].CODFABRICANTE} </td>
                                                    </tr>
                                                {/section} 
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade small {$activeTab02}" id="tab_content2" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th width="20%">Emissão</th>
                                                <th width="20%">Tipo</th>
                                                <th width="20%">Docto</th>
                                                <th width="20%">Quantidade</th>
                                                <th width="20%">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {section name=i loop=$notas}
                                                {if $notas[i].DOCTO > 0}
                                                <tr>
                                                    <td name="emissao"> {$notas[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                                    <td name="tipo"> {$notas[i].TIPO} </td>
                                                    <td name="docto"> {$notas[i].DOCTO} </td>
                                                    <td name="qtsolicitada"> {$notas[i].QUANT} </td>
                                                    <td name="total"> {$notas[i].TOTAL} </td>
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
                                                    <td name="total"> {$tabela[i].PRECO} </td>
                                                    <td name="total"> {$tabela[i].IPI} </td>
                                                    <td name="total"> {$tabela[i].PRECOVENDA} </td>
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
                                                    <td name="total"> {$estoque[i].CENTROCUSTO} </td>
                                                    <td name="total"> {$estoque[i].ESTOQUE|number_format:2:",":"."} </td>
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
</section>
    <!-- /Datatables -->
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <script>
    $("#produtoCombo.js-example-basic-single").select2({
        placeholder: "Selecione o Produto",
        language: "pt-br",
        allowClear: true
    });
    </script>

