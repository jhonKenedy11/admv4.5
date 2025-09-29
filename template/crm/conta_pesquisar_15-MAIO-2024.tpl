
<script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- page content -->
<section class="height100">
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Pessoa - Pesquisar</h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary"
                                    onClick="javascript:submitCadastro('pesquisar');">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Cadastro</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!--div class="x_content" style="display: none;"-->
                    <div class="x_content">

                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="post"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=credito type=hidden value={$credito}>
                            <input name=from type=hidden value="{$from}">
                            <input name=cep type=hidden value="{$cep}">
                            <input name=check type=hidden value="{$check}">

                            <div class="form-group col-md-8 col-sm-12 col-xs-12">
                                <label>Pessoa</label>
                                <input class="form-control" id="pesNome" name="pesNome"
                                    placeholder="Digite o nome do Pessoa." value={$pesNome}>
                            </div>

                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label>CNPJ ou CPF</label>
                                <input class="form-control" type="text" id="pesCnpjCpf" name="pesCnpjCpf"
                                    placeholder="Digite o CNPJ/CPF." value={$pesCnpjCpf}>
                            </div>

                    </div>


                    <!-- dados adicionaris -->
                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel">
                            <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo" tabindex="-1">
                                <h4 tabindex="-1" class="panel-title">Filtros Adicionais <i tabindex="-1" class="fa fa-chevron-down"></i>
                                </h4>
                            </a>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <div class="x_panel">

                                        <div class="form-group col-md-5 col-sm-12 col-xs-12">
                                            <input class="form-control" type="text" id="pesCidade" name="pesCidade"
                                                placeholder="Digite a cidade." value={$cidade}>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                            <SELECT class="form-control" name="idEstado">
                                                {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                                            </SELECT>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                            <SELECT class="form-control" name="idVendedor">
                                                {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                                            </SELECT>
                                        </div>


                                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                            <SELECT class="form-control" name="idAtividade">
                                                {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                                            </SELECT>
                                        </div>

                                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                            <SELECT class="form-control" name="idClasse">
                                                {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                                            </SELECT>
                                        </div>

                                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                            <SELECT class="form-control" name="idPessoa">
                                                {html_options values=$tipoPessoa_ids output=$tipoPessoa_names selected=$tipoPessoa_id}
                                            </SELECT>
                                        </div>
                                        <div class="form-group col-md-5 col-sm-12 col-xs-12">
                                            <input class="form-control" type="text" id="pesObs" name="pesObs"
                                                placeholder="Digite valor para pesquisa." value={$pesObs}>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of accordion -->


                    </form>

                </div>

            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->



        <div class="col-md-12 col-xs-12">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" {$active1}><a href="#tab_content1" id="dados-tab" role="tab" data-toggle="tab"
                            aria-expanded="true">Pesquisa</a>
                    </li>
                    <li role="presentation" {$active2}><a href="#tab_content2" role="tab" id="rateio-tab" data-toggle="tab"
                            aria-expanded="true">Pedidos</a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade {$active1} small" id="tab_content1"
                        aria-labelledby="home-tab">
                        <!-- panel tabela dados -->
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons1" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: #2A3F54; color: white;">
                                            <th style="width: 50px;"
                                                title="Clique no ícone abaixo e depois na aba PEDIDOS">
                                                <center>
                                                    Pedido
                                                </center>
                                            </th>

                                            <th>Nome</th>
                                            <th>Nome Reduzido</th>
                                            <th>Cidade</th>
                                            <th>Telefone</th>
                                            <th>Classe</th>
                                            <th style="width: 75px;">Pesquisa</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$lanc}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td id="btnPedidos">
                                                    <center>
                                                        <button type="button" class="btn btn-dark btn-xs"
                                                            onclick="javascript:submitLetraPesquisa('{$lanc[i].NOME}','{$lanc[i].CLIENTE}','true');">
                                                            <span class="glyphicon glyphicon-tasks"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </center>
                                                </td>
                                                <td hidden id="idCliente"> {$lanc[i].CLIENTE} </td>
                                                <td hidden id="creditoCliente"> {$lanc[i].CREDITO|number_format:2:",":"."} </td>
                                                <td hidden id="cepCliente"> {$lanc[i].CEP} </td>
                                                <td hidden id="munCliente"> {$lanc[i].CODMUNICIPIO} </td>
                                                <td hidden id="bloqCliente"> {$lanc[i].BLOQUEADO} </td>
                                                <td id="nomeCliente"> {$lanc[i].NOME} </td>
                                                <td> {$lanc[i].NOMEREDUZIDO} </td>
                                                <td> {$lanc[i].CIDADE} - {$lanc[i].UF} </td>
                                                <td id=""> {$lanc[i].FONEAREA} {$lanc[i].FONE} / {$lanc[i].FAXAREA} {$lanc[i].FAX}
                                                </td>
                                                <td> {$lanc[i].BLOQUEADO} </td>

                                                <td class="last">
                                                    <center>
                                                        {if $opcao eq 'pesquisarAtendimento' }
                                                            <button type="button" class="btn btn-success btn-xs"
                                                                onclick="javascript:fechaPesquisaAtendimento('{$lanc[i].CLIENTE}', '{$lanc[i].NOME}','{$lanc[i].FONECONTATO}');">
                                                                <span class="glyphicon glyphicon-ok"
                                                                    aria-hidden="true"></span></button>
                                                        {else if $from eq 'uni_cliente_retira'}
                                                            <button type="button" class="btn btn-success btn-xs"
                                                                onclick="javascript:fechaClienteRetira(this);">
                                                                <span class="glyphicon glyphicon-ok"
                                                                    aria-hidden="true"></span></button>
                                                        {else if $from eq 'uni_cliente_permanece'}
                                                            <button type="button" class="btn btn-success btn-xs"
                                                                onclick="javascript:fechaClientePermanece(this);">
                                                                <span class="glyphicon glyphicon-ok"
                                                                    aria-hidden="true"></span></button>
                                                        {else}
                                                            {if $lanc[i].BLOQUEADO neq 'BLOQUEADO'}
                                                                <button type="button" class="btn btn-success btn-xs"
                                                                    onclick="javascript:fechaLancamento('{$lanc[i].CLIENTE}', '{$lanc[i].NOME}', '{$opcao}' , '{$lanc[i].CREDITO|number_format:2:",":"."}', '{$lanc[i].CEP}', '{$lanc[i].CODMUNICIPIO}', '{$lanc[i].BLOQUEADO}' );">
                                                                    <span class="glyphicon glyphicon-ok"
                                                                        aria-hidden="true"></span></button>
                                                            {/if}
                                                        {/if}

                                                    </center>
                                                </td>
                                            </tr>
                                        {/section}
                                    </tbody>
                                </table>
                            </div> <!-- div class="x_content" = inicio tabela -->
                        </div> <!-- div class="x_panel" = painel principal-->
                    </div>

                    <div role="tabpanel" class="tab-pane fade {$active2} small" id="tab_content2" aria-labelledby="profile-tab">
                        <div class="panel-body">
                            <div class="x_panel">
                                {if $existePedido eq 'yes'}
                                    <table id="datatable-ped" class="table table-bordered jambo_table">
                                        <thead>
                                            <tr style="background: #2A3F54; color: white;">
                                                <th style="width: 50px;">Pedido</th>
                                                <th>Cliente</th>
                                                <th>Vendedor</th>
                                                <th>Emiss&atilde;o</th>
                                                <th>Total Ped</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {section name=i loop=$resultPed}
                                                <tr>
                                                    <td name="total"> {$resultPed[i].ID} </td>
                                                    <td name="total">
                                                        {if $resultPed[i].NOMEREDUZIDO neq ''}
                                                            {$resultPed[i].NOMEREDUZIDO}
                                                        {else}
                                                            {$resultPed[i].NOME}
                                                        {/if}
                                                    </td>
                                                    <td name="total"> {$resultPed[i].VENDEDOR} </td>
                                                    <td name="total"> {$resultPed[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                    <td name="total"> {$resultPed[i].TOTAL|number_format:2:",":"."} </td>

                                                </tr>
                                                <p>
                                                {/section}
                                        </tbody>
                                    </table>
                                {elseif $existePedido eq 'no'}
                                    <div>
                                        <h4 class="NoProd">
                                            <center>CLIENTE NÃO POSSUI PEDIDO</center>
                                        </h4>
                                    </div>
                                {else}
                                    <div>
                                        <h4></h4>
                                    </div>
                                {/if}

                            </div> <!-- div class="x_panel" = painel principal-->
                        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
                    </div> <!-- div class="row "-->



                    {include file="template/database.inc"}
</section>

                    <!-- /Datatables -->

<style>
.height100 {
    height: 100vh;
    background-color: #F7F7F7;
    margin-top: 0;
    margin-bottom: 0;
    padding: 0;
}
.last {
    vertical-align: middle !important;
    padding: 0 !important;
}

#btnPedidos {
    padding: 0;
    vertical-align: middle !important;
}

.NoProd {
    color: #022f51;
    text-shadow: 0 1px 0 #ccc,
        0 2px 0 #c9c9c9,
        0 3px 0 #bbb,
        0 4px 0 #b9b9b9,
        0 5px 0 #aaa,
        0 6px 4px rgba(0, 0, 0, .1),
        0 0 5px rgba(0, 0, 0, .1),
        0 1px 3px rgba(0, 0, 0, .3),
        0 3px 5px rgba(0, 0, 0, .2),
        0 5px 10px rgba(0, 0, 0, .25),
        0 10px 10px rgba(0, 0, 0, .2),
        0 20px 20px rgba(0, 0, 0, .15);
}
.container body{
background-color: aliceblue;
}
</style>
<script>
$('#pesNome').focus()
</script>